<?php

namespace Modules\Catalog\Repositories\Dashboard;

use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Modules\Catalog\Entities\Product;
use Modules\Catalog\Entities\ProductImage;
use Hash;
use Illuminate\Support\Facades\DB;
use Modules\Catalog\Entities\WorkingTime;
use Modules\Catalog\Entities\WorkingTimeDetails;
use Modules\Core\Traits\CoreTrait;
use Modules\Core\Traits\SyncRelationModel;
use Modules\Variation\Entities\OptionValue;
use Modules\Variation\Entities\ProductVariant;
use Modules\Vendor\Repositories\Dashboard\VendorRepository as Vendor;
use Modules\Vendor\Traits\VendorTrait;
use Illuminate\Http\UploadedFile;

class ProductRepository
{
    use SyncRelationModel, CoreTrait, VendorTrait;

    protected $imgPath;

    function __construct(
        Product        $product,
        ProductImage   $prdImg,
        OptionValue    $optionValue,
        ProductVariant $variantPrd,
        Vendor         $vendor
    ) {
        $this->product = $product;
        $this->prdImg = $prdImg;
        $this->optionValue = $optionValue;
        $this->variantPrd = $variantPrd;
        $this->vendor = $vendor;
        $this->imgPath = public_path('uploads/products');
    }

    public function getAll($order = 'id', $sort = 'desc')
    {
        $products = $this->product->orderBy($order, $sort)->get();
        return $products;
    }

    public function getAllActive($order = 'id', $sort = 'desc')
    {
        $products = $this->product->active()->orderBy($order, $sort)->get();
        return $products;
    }

    public function findById($id)
    {
        $product = $this->product->withDeleted()->with(['tags', 'images', 'addOns' => function ($q) {
            $q->with('addOnOptions');
        }])->find($id);
        return $product;
    }

    public function findVariantProductById($id)
    {
        return $this->variantPrd->with('productValues')->find($id);
    }

    public function findProductImgById($id)
    {
        return $this->prdImg->find($id);
    }

    public function create($request)
    {
        DB::beginTransaction();

        try {

            $data = [
                //                'image' => $request->image ? path_without_domain($request->image) : url(config('setting.logo')),
                'status' => $request->status == 'on' ? 1 : 0,
                'price' => $request->price,
                'sku' => $request->sku,
                "shipment" => $request->shipment,
            ];

            if (!is_null($request->image)) {
                $imgName = $this->uploadImage($this->imgPath, $request->image);
                $data['image'] = 'uploads/products/' . $imgName;
            } else {
                $data['image'] = url(config('setting.logo'));
            }

            if ($request->manage_qty == 'limited')
                $data['qty'] = $request->qty;
            else
                $data['qty'] = null;

            /*if (config('setting.other.is_multi_vendors') == 1)
                $data['vendor_id'] = $request->vendor_id;
            else
                $data['vendor_id'] = config('setting.default_vendor') ?? null;*/

            if (auth()->user()->can('pending_products_for_approval'))
                $data['pending_for_approval'] = $request->pending_for_approval == 'on' ? 1 : 0;

            if (config('setting.products.toggle_featured') == 1)
                $data['featured'] = $request->featured == 'on' ? 1 : 0;

            $product = $this->product->create($data);

            $this->translateTable($product, $request);

            $cats = empty($request->category_id) ? 1 : int_to_array($request->category_id);
            $product->categories()->sync($cats);

            if ($request->offer_status != "on") {
                $this->productVariants($product, $request);
            }

            $this->productOffer($product, $request);

            // Add Product Images
            if (isset($request->images) && !empty($request->images)) {
                foreach ($request->images as $k => $img) {
                    $imgName = $this->uploadImage($this->imgPath, $img);
                    $product->images()->create([
                        'image' => $imgName,
                    ]);
                }
            }

            // Add Product Tags
            if (/*config('setting.other.toggle_tags') == 1 && */isset($request->tags) && !empty($request->tags)) {
                $tags = $this->removeEmptyValuesFromArray($request->tags);
                $product->tags()->sync($tags);
            }

            if (isset($request->restaurants) && !empty($request->restaurants)) {

                $branchesData = [];
                if (empty($request->branches)) { // if branches not selected - get all restaurants branches if it existed
                    $branchesData = $this->buildSyncDataByRestaurantId($request->restaurants);
                } else { // if select at least one branch
                    foreach ($request->branches as $key => $values) {
                        // Check if selected restaurant have branches
                        if (in_array($key, $request->restaurants)) {
                            foreach ($values as $k => $branchId) {
                                $branchesData[$branchId] = ['provider_id' => $key];
                            }
                        } else {
                            $RestaurantBranches = $this->getBranchesByRestaurantId($key)->pluck('id')->toArray();
                            if (count($RestaurantBranches) > 0) {
                                foreach ($RestaurantBranches as $k => $branchId) {
                                    $branchesData[$branchId] = ['provider_id' => $key];
                                }
                            }
                        }
                    }
                }

                if (count($branchesData) > 0) {
                    $product->productVendors()->sync($branchesData);
                }
            }

            if (!is_null($request->customize_ordering_time) && isset($request->days_status) && !empty($request->days_status)) {
                foreach ($request->days_status as $k => $dayCode) {

                    if (array_key_exists($dayCode, $request->is_full_day)) {
                        if ($request->is_full_day[$dayCode] == '1') {
                            $product->workingTimes()->create([
                                'timeable_type' => get_class($product),
                                'day_code' => $dayCode,
                                'status' => true,
                                'is_full_day' => true,
                            ]);
                        } else {
                            $availability = [
                                'timeable_type' => get_class($product),
                                'day_code' => $dayCode,
                                'status' => true,
                                'is_full_day' => false,
                            ];
                            $workingDay = $product->workingTimes()->create($availability);

                            foreach ($request->availability['time_from'][$dayCode] as $key => $time) {
                                $workingDay->workingTimeDetails()->create([
                                    'time_from' => date("H:i:s", strtotime($time)),
                                    'time_to' => date("H:i:s", strtotime($request->availability['time_to'][$dayCode][$key])),
                                ]);
                            }
                        }
                    }
                }
            }

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function update($request, $id)
    {
        DB::beginTransaction();
        $product = $this->findById($id);
        $restore = $request->restore ? $this->restoreSoftDelte($product) : null;

        if (isset($request->images) && !empty($request->images)) {
            $sync = $this->syncRelation($product, 'images', $request->images);
        }

        try {

            $data = [
                'status' => $request->status == 'on' ? 1 : 0,
                'sku' => $request->sku,
                "shipment" => $request->shipment,
            ];

            /*if (config('setting.other.is_multi_vendors') == 1)
                $data['vendor_id'] = $request->vendor_id;
            else
                $data['vendor_id'] = config('setting.default_vendor') ?? null;*/

            if (auth()->user()->can('edit_products_price'))
                $data['price'] = $request->price;

            if (auth()->user()->can('edit_products_qty')) {
                if ($request->manage_qty == 'limited')
                    $data['qty'] = $request->qty;
                else
                    $data['qty'] = null;
            }

            if (auth()->user()->can('edit_products_image')) {
                if ($request->image) {
                    File::delete($product->image); ### Delete old image
                    $imgName = $this->uploadImage($this->imgPath, $request->image);
                    $data['image'] = 'uploads/products/' . $imgName;
                } else {
                    $data['image'] = $product->image;
                }
            }

            if (auth()->user()->can('pending_products_for_approval'))
                $data['pending_for_approval'] = $request->pending_for_approval == 'on' ? 1 : 0;

            if (config('setting.products.toggle_featured') == 1)
                $data['featured'] = $request->featured == 'on' ? 1 : 0;

            $product->update($data);

            $this->translateTable($product, $request, 'edit');

            if (auth()->user()->can('edit_products_category')) {
                $cats = empty($request->category_id) ? 1 : int_to_array($request->category_id);
                $product->categories()->sync($cats);
            }

            if ($request->offer_status == "on") {
                $product->variants()->delete();
            } else {
                $this->productVariants($product, $request);
            }

            if (auth()->user()->can('edit_products_price'))
                $this->productOffer($product, $request);

            if (auth()->user()->can('edit_products_gallery')) {
                // Create Or Update Product Images
                if (isset($request->images) && !empty($request->images)) {

                    // Update Old Images
                    if (isset($sync['updated']) && !empty($sync['updated'])) {
                        foreach ($sync['updated'] as $k => $id) {
                            $oldImgObj = $product->images()->find($id);
                            File::delete('uploads/products/' . $oldImgObj->image); ### Delete old image
                            $img = $request->images[$id];
                            $imgName = $this->uploadImage($this->imgPath, $img);
                            $oldImgObj->update([
                                'image' => $imgName,
                            ]);
                        }
                    }

                    // Add New Images
                    foreach ($request->images as $k => $img) {
                        if (!in_array($k, $sync['updated'])) {
                            $imgName = $this->uploadImage($this->imgPath, $img);
                            $product->images()->create([
                                'image' => $imgName,
                            ]);
                        }
                    }
                }
            }

            // Update Product Tags
            if (/*config('setting.other.toggle_tags') == 1 && */isset($request->tags) && !empty($request->tags)) {
                $tags = $this->removeEmptyValuesFromArray($request->tags);
                $product->tags()->sync($tags);
            }

            if (isset($request->restaurants) && !empty($request->restaurants)) {

                $branchesData = [];
                if (empty($request->branches)) { // if branches not selected - get all restaurants branches if it existed
                    $branchesData = $this->buildSyncDataByRestaurantId($request->restaurants);
                } else { // if select at least one branch
                    foreach ($request->branches as $key => $values) {
                        // Check if selected restaurant have branches
                        if (in_array($key, $request->restaurants)) {
                            foreach ($values as $k => $branchId) {
                                $branchesData[$branchId] = ['provider_id' => $key];
                            }
                        } else {
                            $RestaurantBranches = $this->getBranchesByRestaurantId($key)->pluck('id')->toArray();
                            if (count($RestaurantBranches) > 0) {
                                foreach ($RestaurantBranches as $k => $branchId) {
                                    $branchesData[$branchId] = ['provider_id' => $key];
                                }
                            }
                        }
                    }
                }

                if (count($branchesData) > 0) {
                    $product->productVendors()->sync($branchesData);
                }
            }

            // START Edit Work Times Over Weeks

            if (!is_null($request->customize_ordering_time) && isset($request->days_status) && !empty($request->days_status)) {

                $deletedProducts = $this->syncRelationModel($product, 'workingTimes', 'day_code', $request->days_status);

                foreach ($request->days_status as $k => $dayCode) {

                    if (array_key_exists($dayCode, $request->is_full_day)) {
                        if ($request->is_full_day[$dayCode] == '1') {

                            $availabilityArray = [
                                'timeable_type' => get_class($product),
                                'day_code' => $dayCode,
                                'status' => true,
                                'is_full_day' => true,
                            ];

                            $product->workingTimes()->updateOrCreate(['day_code' => $dayCode], $availabilityArray);
                        } else {
                            $availability = [
                                'timeable_type' => get_class($product),
                                'day_code' => $dayCode,
                                'status' => true,
                                'is_full_day' => false,
                            ];
                            $workingDay = $product->workingTimes()->updateOrCreate(['day_code' => $dayCode], $availability);
                            $workingDay->workingTimeDetails()->delete();
                            foreach ($request->availability['time_from'][$dayCode] as $key => $time) {
                                $workingDay->workingTimeDetails()->create([
                                    'time_from' => date("H:i:s", strtotime($time)),
                                    'time_to' => date("H:i:s", strtotime($request->availability['time_to'][$dayCode][$key])),
                                ]);
                            }
                        }
                    }
                }

                if (!empty($deletedProducts['deleted'])) {
                    WorkingTime::whereIn('day_code', $deletedProducts['deleted'])->delete();
                }
            } else {
                $product->workingTimes()->delete();
            }

            // END Edit Work Times Over Weeks

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function approveProduct($request, $id)
    {
        DB::beginTransaction();
        $product = $this->findById($id);

        try {
            $data = [];
            if (auth()->user()->can('review_products')) {
                $data['pending_for_approval'] = $request->pending_for_approval == 'on' ? true : false;
                $product->update($data);
            } else
                return false;

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function restoreSoftDelte($model)
    {
        $model->restore();
    }

    public function translateTable($model, $request, $action = '')
    {
        foreach (config('translatable.locales') as $k => $locale) {

            if ($action == '' || $action == 'create' || ($action == 'edit' && auth()->user()->can('edit_products_title')))
                $model->translateOrNew($locale)->title = $request['title'][$locale];

            if ($action == '' || $action == 'create' || ($action == 'edit' && auth()->user()->can('edit_products_description'))) {
                $model->translateOrNew($locale)->short_description = $request['short_description'][$locale];
                $model->translateOrNew($locale)->description = $request['description'][$locale];

                /*if (!is_null($request['short_description'][$locale])) $model->translateOrNew($locale)->short_description = $request['short_description'][$locale];
                if (!is_null($request['description'][$locale])) $model->translateOrNew($locale)->description = $request['description'][$locale];*/
            }

            $model->translateOrNew($locale)->seo_description = $request['seo_description'][$locale];
            $model->translateOrNew($locale)->seo_keywords = $request['seo_keywords'][$locale];

            /*if (!is_null($request['seo_description'][$locale])) $model->translateOrNew($locale)->seo_description = $request['seo_description'][$locale];
            if (!is_null($request['seo_keywords'][$locale])) $model->translateOrNew($locale)->seo_keywords = $request['seo_keywords'][$locale];*/
        }

        $model->save();
    }

    public function delete($id)
    {
        DB::beginTransaction();

        try {

            $model = $this->findById($id);
            if ($model)
                File::delete($model->image); ### Delete old image

            if ($model->trashed()) :
                $model->forceDelete();
            else :
                $model->delete();
            endif;

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function deleteSelected($request)
    {
        DB::beginTransaction();

        try {

            foreach ($request['ids'] as $id) {
                $model = $this->delete($id);
            }

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function deleteProductImg($id)
    {
        DB::beginTransaction();

        try {

            $model = $this->findProductImgById($id);

            if ($model) {
                File::delete('uploads/products/' . $model->image); ### Delete old image
                $model->delete();
            }

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function QueryTable($request)
    {
        $query = $this->product->with(['translations', 'vendor']);
        $query = $query->approved();

        $query->where(function ($query) use ($request) {
            $query->where('id', 'like', '%' . $request->input('search.value') . '%');
            $query->orWhere(function ($query) use ($request) {
                $query->whereHas('translations', function ($query) use ($request) {
                    $query->where('title', 'like', '%' . $request->input('search.value') . '%');
                    $query->orWhere('slug', 'like', '%' . $request->input('search.value') . '%');
                });
            });
        });

        return $this->filterDataTable($query, $request);
    }

    public function reviewProductsQueryTable($request)
    {
        $query = $this->product->with(['translations', 'vendor']);
        $query = $query->notApproved();

        $query->where(function ($query) use ($request) {
            $query->where('id', 'like', '%' . $request->input('search.value') . '%');
            $query->orWhere(function ($query) use ($request) {
                $query->whereHas('translations', function ($query) use ($request) {
                    $query->where('title', 'like', '%' . $request->input('search.value') . '%');
                    $query->orWhere('slug', 'like', '%' . $request->input('search.value') . '%');
                });
            });
        });

        return $this->filterDataTable($query, $request);
    }

    public function filterDataTable($query, $request)
    {
        // Search Categories by Created Dates
        if (isset($request['req']['from']) && $request['req']['from'] != '')
            $query->whereDate('created_at', '>=', $request['req']['from']);

        if (isset($request['req']['to']) && $request['req']['to'] != '')
            $query->whereDate('created_at', '<=', $request['req']['to']);

        if (isset($request['req']['deleted']) && $request['req']['deleted'] == 'only')
            $query->onlyDeleted();

        if (isset($request['req']['deleted']) && $request['req']['deleted'] == 'with')
            $query->withDeleted();

        if (isset($request['req']['status']) && $request['req']['status'] == '1')
            $query->active();

        if (isset($request['req']['status']) && $request['req']['status'] == '0')
            $query->unactive();

        if (isset($request['req']['vendor']) && !empty($request['req']['vendor']))
            $query->where('vendor_id', $request['req']['vendor']);

        if (isset($request['req']['categories']) && $request['req']['categories'] != '') {
            $query->whereHas('categories', function ($query) use ($request) {
                $query->where('product_categories.category_id', $request['req']['categories']);
            });
        }

        return $query;
    }

    public function productVariants($model, $request)
    {

        $oldValues = isset($request['variants']['_old']) ? $request['variants']['_old'] : [];

        $sync = $this->syncRelation($model, 'variants', $oldValues);

        if ($sync['deleted']) {
            $model->variants()->whereIn('id', $sync['deleted'])->delete();
        }

        if ($sync['updated']) {

            foreach ($sync['updated'] as $id) {

                foreach ($request['upateds_option_values_id'] as $key => $varianteId) {

                    $variation = $model->variants()->find($id);

                    $variation->update([
                        'sku' => $request['_variation_sku'][$id],
                        'price' => $request['_variation_price'][$id],
                        'status' => isset($request['_variation_status'][$id]) && $request['_variation_status'][$id] == 'on' ? 1 : 0,
                        'qty' => $request['_variation_qty'][$id],
                        "shipment" => isset($request["_vshipment"][$id]) ? $request["_vshipment"][$id] : null,
                        'image' => $request['_v_images'][$id] ? path_without_domain($request['_v_images'][$id]) : $model->image
                    ]);

                    if (isset($request["_v_offers"][$id]))
                        $this->variationOffer($variation, $request["_v_offers"][$id]);
                }
            }
        }

        $selectedOptions = [];

        if ($request['option_values_id']) {

            foreach ($request['option_values_id'] as $key => $value) {

                // dd($request->all(), $key);

                $variant = $model->variants()->create([
                    'sku' => $request['variation_sku'][$key],
                    'price' => $request['variation_price'][$key],
                    'status' => isset($request['variation_status'][$key]) && $request['variation_status'][$key] == 'on' ? 1 : 0,
                    'qty' => $request['variation_qty'][$key],
                    "shipment" => isset($request["vshipment"][$key]) ? $request["vshipment"][$key] : null,
                    'image' => $request['v_images'][$key] ? path_without_domain($request['v_images'][$key]) : $model->image
                ]);


                if (isset($request["v_offers"][$key]))
                    $this->variationOffer($variant, $request["v_offers"][$key]);


                foreach ($value as $key2 => $value2) {

                    $optVal = $this->optionValue->find($value2);
                    if ($optVal) {
                        if (!in_array($optVal->option_id, $selectedOptions)) {
                            array_push($selectedOptions, $optVal->option_id);
                        }
                    }

                    $option = $model->options()->updateOrCreate([
                        'option_id' => $optVal->option_id,
                        'product_id' => $model['id'],
                    ]);

                    $variant->productValues()->create([
                        'product_option_id' => $option['id'],
                        'option_value_id' => $value2,
                        'product_id' => $model['id'],
                    ]);
                }
            }
        }

        /*if (count($selectedOptions) > 0) {
            foreach ($selectedOptions as $option_id) {
                $option = $model->options()->updateOrCreate([
                    'option_id' => $option_id,
                    'product_id' => $model['id'],
                ]);
            }
        }*/

        /*if (count($selectedOptions) > 0) {
            $model->productOptions()->sync($selectedOptions);
        }*/
    }

    public function productOffer($model, $request)
    {
        if (isset($request['offer_status']) && $request['offer_status'] == 'on') {

            $model->offer()->updateOrCreate(
                ['product_id' => $model->id],
                [
                    'status' => ($request['offer_status'] == 'on') ? true : false,
                    'offer_price' => $request['offer_price'] ? $request['offer_price'] : $model->offer->offer_price,
                    'start_at' => $request['start_at'] ? $request['start_at'] : $model->offer->start_at,
                    'end_at' => $request['end_at'] ? $request['end_at'] : $model->offer->end_at,
                ]
            );
        } else {
            if ($model->offer) {
                $model->offer()->delete();
            }
        }
    }

    public function variationOffer($model, $request)
    {

        if (isset($request['status']) && $request['status'] == 'on') {

            $model->offer()->updateOrCreate(
                ['product_variant_id' => $model->id],
                [
                    'status' => ($request['status'] == 'on') ? true : false,
                    'offer_price' => $request['offer_price'] ? $request['offer_price'] : $model->offer->offer_price,
                    'start_at' => $request['start_at'] ? $request['start_at'] : $model->offer->start_at,
                    'end_at' => $request['end_at'] ? $request['end_at'] : $model->offer->end_at,
                ]
            );
        } else {
            if ($model->offer) {
                $model->offer()->delete();
            }
        }
    }

    public function getProductDetailsById($id)
    {
        $product = $this->product->query();

        $product = $product->with([
            'categories',
            'vendor',
            'tags',
            'images',
            'offer',
            'variants' => function ($q) {
                $q->with(['offer', 'productValues' => function ($q) {
                    $q->with(['productOption.option', 'optionValue']);
                }]);
            },
            'addOns' => function ($q) {
                $q->with('addOnOptions');
            }
        ]);

        $product = $product->find($id);
        return $product;
    }

    public function buildSyncDataByRestaurantId($restaurants)
    {
        $branchesData = [];
        foreach ($restaurants as $key => $restaurantId) {
            $RestaurantBranches = $this->getBranchesByRestaurantId($restaurantId)->pluck('id')->toArray();
            if (count($RestaurantBranches) > 0) {
                foreach ($RestaurantBranches as $k => $branchId) {
                    $branchesData[$branchId] = ['provider_id' => $restaurantId];
                }
            }
        }
        return $branchesData;
    }

    public function syncRelationModel($model, $relation, $columnName = 'id', $arrayValues = null)
    {
        $oldIds = $model->$relation->pluck($columnName)->toArray();
        $data['deleted'] = array_values(array_diff($oldIds, $arrayValues));
        $data['updated'] = array_values(array_intersect($oldIds, $arrayValues));
        return $data;
    }
}
