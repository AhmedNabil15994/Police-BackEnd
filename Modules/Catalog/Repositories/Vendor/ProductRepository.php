<?php

namespace Modules\Catalog\Repositories\Vendor;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Modules\Catalog\Entities\Product;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Modules\Catalog\Entities\ProductImage;
use Modules\Core\Traits\CoreTrait;
use Modules\Core\Traits\SyncRelationModel;

class ProductRepository
{
    use SyncRelationModel, CoreTrait;

    protected $product;
    protected $addOn;
    protected $addOnOption;
    protected $prdImg;

    function __construct(Product $product, ProductImage $prdImg)
    {
        $this->product = $product;
        $this->prdImg = $prdImg;
    }

    public function getAll($order = 'id', $sort = 'desc')
    {
        $products = $this->product->whereHas('productVendors', function ($query) {
            $query->whereHas('sellers', function ($q) {
                $q->where('seller_id', auth()->user()->id);
            });
        })->orderBy($order, $sort)->get();

        return $products;
    }

    public function findById($id)
    {
        $product = $this->product->whereHas('productVendors', function ($query) {
            $query->whereHas('sellers', function ($q) {
                $q->where('seller_id', auth()->user()->id);
            });
        })->with(['tags', 'images', 'addOns.addOnOptions'])->find($id);

        return $product;
    }

    public function findAddOnsById($id)
    {
        return $this->addOn->with('addOnOptions')->find($id);
    }

    public function findProductImgById($id)
    {
        return $this->prdImg->find($id);
    }

    public function findAddOnsOptionById($id)
    {
        return $this->addOnOption->find($id);
    }


    public function restoreSoftDelete($model)
    {
        $model->restore();
    }

    public function translateTable($model, $request, $action = '')
    {
        foreach (config('translatable.locales') as $k => $locale) {

            if ($action == '' || ($action == 'edit' && auth()->user()->can('edit_products_title')))
                $model->translateOrNew($locale)->title = $request['title'][$locale];

            if ($action == '' || ($action == 'edit' && auth()->user()->can('edit_products_description'))) {
                if (!is_null($request['short_description'][$locale])) $model->translateOrNew($locale)->short_description = $request['short_description'][$locale];
                if (!is_null($request['description'][$locale])) $model->translateOrNew($locale)->description = $request['description'][$locale];
            }

            if (!is_null($request['seo_description'][$locale])) $model->translateOrNew($locale)->seo_description = $request['seo_description'][$locale];
            if (!is_null($request['seo_keywords'][$locale])) $model->translateOrNew($locale)->seo_keywords = $request['seo_keywords'][$locale];
        }

        $model->save();
    }

    public function translateAddOnTable($model, $request)
    {
        foreach ($request['option_name'] as $locale => $value) {
            $model->translateOrNew($locale)->name = $value;
        }

        $model->save();
    }

    public function translateAddOnOptionTable($model, $options)
    {
        foreach ($options as $locale => $value) {
            $model->translateOrNew($locale)->option = $value;
        }
        $model->save();
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

            if (config('setting.other.is_multi_vendors') == 1)
                $data['vendor_id'] = $request->vendor_id;
            else
                $data['vendor_id'] = config('setting.default_vendor') ?? null;

            if (auth()->user()->can('pending_products_for_approval'))
                $data['pending_for_approval'] = $request->pending_for_approval == 'on' ? 1 : 0;

            if (config('setting.products.toggle_featured') == 1)
                $data['featured'] = $request->featured == 'on' ? 1 : 0;

            $product = $this->product->create($data);

            $this->translateTable($product, $request);

            $product->categories()->sync(int_to_array($request->category_id));
            if ($request->offer_status != "on") {
                $this->productVariants($product, $request);
            }

            $this->productOffer($product, $request);

            // Add Product Images
            if (isset($request->images) && !empty($request->images)) {

                $imgPath = public_path('uploads/products');

                foreach ($request->images as $k => $img) {
                    $imgName = $img->hashName();
                    $img->move($imgPath, $imgName);

                    $product->images()->create([
                        'image' => $imgName,
                    ]);
                }
            }

            // Add Product Tags
            if (/*config('setting.other.toggle_tags') == 1 && */isset($request->tags) && !empty($request->tags)) {

                $tagsCollection = collect($request->tags);
                $filteredTags = $tagsCollection->filter(function ($value, $key) {
                    return $value != null && $value != '';
                });
                $tags = $filteredTags->all();

                $product->tags()->sync($tags);
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
        // dd($request->all());
        $product = $this->findById($id);
        $restore = $request->restore ? $this->restoreSoftDelete($product) : null;

        if (isset($request->images) && !empty($request->images)) {
            $sync = $this->syncRelation($product, 'images', $request->images);
        }

        try {

            $data = [
                'status' => $request->status == 'on' ? 1 : 0,
                'sku' => $request->sku,
                "shipment" => $request->shipment,
            ];

            if (config('setting.other.is_multi_vendors') == 1)
                $data['vendor_id'] = $request->vendor_id;
            else
                $data['vendor_id'] = config('setting.default_vendor') ?? null;

            if (auth()->user()->can('edit_products_price'))
                $data['price'] = $request->price;

            if (auth()->user()->can('edit_products_qty'))
                $data['qty'] = $request->qty;

            if (auth()->user()->can('edit_products_image'))
                $data['image'] = $request->image ? path_without_domain($request->image) : $product->image;

            if (auth()->user()->can('pending_products_for_approval'))
                $data['pending_for_approval'] = $request->pending_for_approval == 'on' ? 1 : 0;

            if (config('setting.products.toggle_featured') == 1)
                $data['featured'] = $request->featured == 'on' ? 1 : 0;

            $product->update($data);

            $this->translateTable($product, $request, 'edit');

            if (auth()->user()->can('edit_products_category')) {
                $product->categories()->sync(int_to_array($request->category_id));
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

                    $imgPath = public_path('uploads/products');

                    // Update Old Images
                    if (isset($sync['updated']) && !empty($sync['updated'])) {

                        foreach ($sync['updated'] as $k => $id) {

                            $oldImgObj = $product->images()->find($id);
                            File::delete('uploads/products/' . $oldImgObj->image); ### Delete old image

                            $img = $request->images[$id];
                            $imgName = $img->hashName();
                            $img->move($imgPath, $imgName);

                            $oldImgObj->update([
                                'image' => $imgName,
                            ]);
                        }
                    }

                    // Add New Images
                    foreach ($request->images as $k => $img) {

                        if (!in_array($k, $sync['updated'])) {

                            $imgName = $img->hashName();
                            $img->move($imgPath, $imgName);

                            $product->images()->create([
                                'image' => $imgName,
                            ]);
                        }
                    }
                }
            }

            // Update Product Tags
            if (/*config('setting.other.toggle_tags') == 1 && */isset($request->tags) && !empty($request->tags)) {

                $tagsCollection = collect($request->tags);
                $filteredTags = $tagsCollection->filter(function ($value, $key) {
                    return $value != null && $value != '';
                });
                $tags = $filteredTags->all();

                $product->tags()->sync($tags);
            }

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
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

    public function QueryTable($request)
    {
        $query = $this->product->with('vendor')->whereHas('productVendors', function ($query) {
            $query->whereHas('sellers', function ($q) {
                $q->where('seller_id', auth()->user()->id);
            });
        });

        if ($request->input('search.value')) {
            $query = $query->where(function ($query) use ($request) {
                $query->where('id', 'like', '%' . $request->input('search.value') . '%');
            })->orWhere(function ($query) use ($request) {
                $query->whereHas('translations', function ($query) use ($request) {
                    $query->where('title', 'like', '%' . $request->input('search.value') . '%');
                    $query->orWhere('slug', 'like', '%' . $request->input('search.value') . '%');
                });
            });
        }

        $query = $this->filterDataTable($query, $request);

        return $query;
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

                    $variant->productValues()->create([
                        'option_value_id' => $value2,
                        'product_id' => $model['id'],
                    ]);
                }
            }
        }
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
                    'offer_price' => $request['offer_price'] ? $request['offer_price'] : ($model->offer->offer_price ?? null),
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

    // addOnes
    public function deleteAddOns($id)
    {
        DB::beginTransaction();

        try {

            $model = $this->findAddOnsById($id);

            if ($model)
                $model->delete();

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function deleteAddOnsOption($id)
    {
        DB::beginTransaction();

        try {

            $model = $this->findAddOnsOptionById($id);

            if ($model)
                $model->delete();

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

    public function createAddOns($request, $id)
    {
        DB::beginTransaction();

        try {

            $product = $this->findById($id);
            if ($product) {

                $dataInput = [];
                if (isset($request->add_ons_type) && !empty($request->add_ons_type)) {
                    $dataInput['type'] = $request->add_ons_type;
                    if (isset($request->options_count_select) && $request->options_count_select == 'on') {
                        $dataInput['options_count'] = $request->options_count ?? null;
                    }
                }

                if (isset($request->add_on_id) && !empty($request->add_on_id)) {
                    $addOn = AddOn::find($request->add_on_id);
                    $addOn->update($dataInput);
                } else {
                    $addOn = $product->addOns()->create($dataInput);
                }
                $this->translateAddOnTable($addOn, $request);

                // Add AddOns options

                if (count($request->price) > 0 && count($request->rowId) > 0) {
                    foreach ($request->rowId as $k => $rowID) {

                        if (isset($request->add_on_id) && !empty($request->add_on_id)) {
                            ############################## Start Update Options ##############################
                            $OldAddOnOption = AddOnOption::find($rowID);
                            if ($OldAddOnOption) {

                                if (isset($request->image[$k]) && $request->image[$k] instanceof UploadedFile) {
                                    if (!empty($OldAddOnOption->image) && !in_array($OldAddOnOption->image, config('core.config.special_images'))) {
                                        File::delete($OldAddOnOption->image); ### Delete old image
                                    }
                                    $image = $this->uploadAddonsOptionImage($request->image[$k]);
                                } else {
                                    $image = $OldAddOnOption->image;
                                }

                                // Update Old
                                $OldAddOnOption->update([
                                    'price' => $request->price[$k],
                                    'image' => $image,
                                    'default' => isset($request->default) && $request->default == $rowID ? true : null,
                                ]);

                                $optionsArr = array_values($request->option);
                                $this->translateAddOnOptionTable($OldAddOnOption, $optionsArr[$k]);
                            } else {
                                $image = $this->uploadAddonsOptionImage($request->image[$k]);
                                // Add New One
                                $newAddOnOption = $addOn->addOnOptions()->create([
                                    'price' => $request->price[$k],
                                    'image' => $image,
                                    'default' => isset($request->default) && $request->default == $rowID ? true : null,
                                ]);

                                $optionsArr = array_values($request->option);
                                $this->translateAddOnOptionTable($newAddOnOption, $optionsArr[$k]);
                            }
                            ############################## Start Update Options ##############################
                        } else {
                            ############################## Start Add New Options ##############################
                            $image = $this->uploadAddonsOptionImage($request->image[$k]);
                            $addOnOption = $addOn->addOnOptions()->create([
                                'price' => $request->price[$k],
                                'image' => $image,
                                'default' => isset($request->default) && $request->default == $rowID ? true : null,
                            ]);
                            $optionsArr = array_values($request->option);
                            $this->translateAddOnOptionTable($addOnOption, $optionsArr[$k]);
                            ############################## End Add New Options ##############################
                        }
                    }
                }

                DB::commit();
                return $this->findAddOnsById($addOn->id);
            }
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    private function uploadAddonsOptionImage($imgFile)
    {
        if ($imgFile instanceof UploadedFile) {
            $imgName = $this->uploadImage(public_path(config('core.config.product_img_path')), $imgFile);
            $image = config('core.config.product_img_path') . '/' . $imgName;
        } else {
            $image = null;
        }
        return $image;
    }
}
