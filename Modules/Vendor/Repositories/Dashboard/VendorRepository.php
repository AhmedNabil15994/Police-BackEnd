<?php

namespace Modules\Vendor\Repositories\Dashboard;

use Modules\Catalog\Entities\Category;
use Modules\Catalog\Entities\Product;
use Modules\Core\Traits\CoreTrait;
use Modules\Vendor\Entities\Vendor;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class VendorRepository
{
    use CoreTrait;

    protected $vendor;
    protected $restaurant;
    protected $product;
    protected $prodCategory;

    function __construct(Vendor $vendor, Product $product, Category $prodCategory)
    {
        $this->vendor = $vendor->branch();
        $this->restaurant = $vendor->restaurant();
        $this->product = $product;
        $this->prodCategory = $prodCategory;
    }

    public function countVendors()
    {
        $vendors = $this->vendor->count();
        return $vendors;
    }

    public function countSubscriptionsVendors()
    {
        $query = $this->vendor;

        $query->when(config('setting.other.enable_subscriptions') == 1, function ($q) {
            return $q->whereHas('subbscription', function ($query) {
                $query->active()->unexpired()->started();
            });
        });

        return $query->count();
    }

    public function getAll($order = 'id', $sort = 'desc')
    {
        $vendors = $this->vendor->branch()->orderBy($order, $sort)->get();
        return $vendors;
    }

    public function getAllActive($order = 'id', $sort = 'desc')
    {
        return $this->restaurant->active()->orderBy($order, $sort)->get();
    }

    public function getAllActiveProdCategories($order = 'id', $sort = 'desc')
    {
        return $this->prodCategory->active()->orderBy($order, $sort)->get();
    }

    public function findById($id)
    {
        return $this->vendor->withDeleted()->find($id);
    }

    public function countTable()
    {
        return $this->vendor->count();
    }

    public function getAllByParentId($id, $order = 'id', $sort = 'desc')
    {
        return $this->vendor/*->withDeleted()*/ ->where('parent_id', $id)
            ->orderBy($order, $sort)->get();
    }

    public function getActiveVendorsWithLimitProducts($minQty)
    {
        $query = $this->vendor;

        $query = $query->when(config('setting.other.enable_subscriptions') == 1, function ($q) {
            return $q->whereHas('subbscription', function ($query) {
                $query->active()->unexpired()->started();
            });
        });

        if (config('setting.other.is_multi_vendors') == 0) {
            $query = $query->where('id', config('setting.default_vendor'));
        }

        $query = $query->active()->with(['vendorProducts' => function ($q) use ($minQty) {
            $q->with('variants');
            $q->active();
            $q->where(function ($q) use ($minQty) {
                $q->where('qty', '<=', $minQty);
                $q->orWhereHas('variants', function ($q) use ($minQty) {
                    $q->where('qty', '<=', $minQty);
                });
            });
        }]);
        return $query->get();
    }

    public function create($request)
    {
        DB::beginTransaction();
        try {
            $this->createOrUpdateData($request, 'create', null);
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
        $vendor = $this->findById($id);
        $restore = $request->restore ? $this->restoreSoftDelete($vendor) : null;
        try {
            $this->createOrUpdateData($request, 'update', $vendor);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function updateInfo($request, $id)
    {
        DB::beginTransaction();

        $vendor = $this->findById($id);

        try {

            $vendor->update([
                'vendor_status_id' => $request->vendor_status_id,
//                'receive_question' => $request->receive_question ? 1 : 0,
//                'receive_prescription' => $request->receive_prescription ? 1 : 0,
            ]);

            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function sorting($request)
    {
        DB::beginTransaction();

        try {

            foreach ($request['vendors'] as $key => $value) {

                $key++;

                $this->vendor->find($value)->update([
                    'sorting' => $key,
                ]);

            }

            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function restoreSoftDelete($model)
    {
        $model->restore();
    }

    public function translateTable($model, $request)
    {
        foreach ($request['title'] as $locale => $value) {
            $model->translateOrNew($locale)->title = $value;
            $model->translateOrNew($locale)->description = $request['description'][$locale];
            $model->translateOrNew($locale)->seo_description = $request['seo_description'][$locale];
            $model->translateOrNew($locale)->seo_keywords = $request['seo_keywords'][$locale];
        }

        $model->save();
    }

    public function delete($id)
    {
        DB::beginTransaction();

        try {

            $model = $this->findById($id);

            if ($model->trashed()):
                $model->forceDelete();
            else:
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


    public function getAllPaginatedProducts($request, $order = 'id', $sort = 'desc', $count = 50)
    {
        $query = $this->product->orderBy($order, $sort)->active();

        if (isset($request->category) && !empty($request->category)) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('product_categories.category_id', $request->category);
            });
        }

        return $query->paginate($count);
    }

    public function assignVendorProducts($vendor, $request)
    {
        DB::beginTransaction();

        try {
            if (isset($request->ids) && !empty($request->ids)) {
                foreach ($request->ids as $k => $id) {
                    $pivotArray = ['price' => $request->price[$id], 'qty' => $request->qty[$id]];
                    if (isset($request->status[$id])) {
                        $pivotArray['status'] = isset($request->status[$id]) || $request->status[$id] == 'on' ? 1 : 0;
                    } else {
                        $pivotArray['status'] = 0;
                    }
                    $products_array[$id] = $pivotArray;
                }
                // sync without delete old items
                $vendor->products()->sync($products_array, false);
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
        $query = $this->vendor->with(['translations', 'deliveryCharge']);

        $query->where(function ($query) use ($request) {

            $query
                ->where('id', 'like', '%' . $request->input('search.value') . '%')
                ->orWhere(function ($query) use ($request) {

                    $query->whereHas('translations', function ($query) use ($request) {

                        $query->where('description', 'like', '%' . $request->input('search.value') . '%');
                        $query->orWhere('title', 'like', '%' . $request->input('search.value') . '%');
                        $query->orWhere('slug', 'like', '%' . $request->input('search.value') . '%');

                    });

                });

        });

        return $this->filterDataTable($query, $request);
    }

    public function filterDataTable($query, $request)
    {
        // Search Pages by Created Dates
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

        if (isset($request['req']['sections']) && $request['req']['sections'] != '') {

            $query->whereHas('sections', function ($query) use ($request) {
                $query->where('section_id', $request['req']['sections']);
            });

        }

        return $query;
    }

    public function createOrUpdateData($request, $methodType = '', $vendor = null)
    {
        $data = [
            'parent_id' => $request->restaurant_id,
            'vendor_status_id' => $request->vendor_status_id,
            'supplier_code_myfatorah' => $request->supplier_code_myfatorah ?? null,
            'fixed_commission' => $request->fixed_commission ?? 0,
            'vendor_email' => $request->vendor_email ?? null,
            'commission' => $request->commission ?? 0,
            'order_limit' => $request->order_limit ?? 0,
            'fixed_delivery' => $request->fixed_delivery ?? 0,
            'status' => $request->status ? 1 : 0,
            'is_trusted' => $request->is_trusted ? 1 : 0,
            'enable_pickup' => $request->enable_pickup ? 1 : 0,
            // 'is_main_branch' => $request->is_main_branch ? 1 : 0,
        ];

        if ($methodType == 'create') {
            $data['image'] = $request->image ? path_without_domain($request->image) : 'storage/photos/shares/vendors/default.jpg';
            $vendor = $this->vendor->create($data);
        } elseif ($methodType == 'update') {
            $data['image'] = $request->image ? path_without_domain($request->image) : $vendor->image;
            $vendor->update($data);
        }

        /* // Start - update this branch to be a main branch of the restaurant
         if (!is_null($request->is_main_branch)) {
             $this->vendor->where('parent_id', $request->restaurant_id)
                 ->where('id', '<>', $vendor->id)
                 ->update(['is_main_branch' => 0]);
         }
         // End - update this branch to be a main branch of the restaurant*/

        // $vendor->products()->sync($request->products);

        if (isset($request->seller_id) && !empty($request->seller_id)) {
            $sellers = $this->removeEmptyValuesFromArray($request->seller_id);
            $vendor->sellers()->sync($sellers);
        }

        if (isset($request->section_id) && !empty($request->section_id)) {
            $sections = $this->removeEmptyValuesFromArray($request->section_id);
            $vendor->sections()->sync($sections);
        }

        if (isset($request->payment_id) && !empty($request->payment_id)) {
            $payments = $this->removeEmptyValuesFromArray($request->payment_id);
            $vendor->payments()->sync($payments);
        }

        if (isset($request->companies) && !empty($request->companies)) {
            $companies = $this->removeEmptyValuesFromArray($request->companies);
            $vendor->companies()->sync($companies);
        }

        if (isset($request->states) && !empty($request->states)) {
            $states = $this->removeEmptyValuesFromArray($request->states);
            $vendor->states()->sync($states);
        }

        $this->translateTable($vendor, $request);
    }

}
