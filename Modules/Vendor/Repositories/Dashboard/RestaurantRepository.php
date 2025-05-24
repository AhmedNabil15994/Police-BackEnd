<?php

namespace Modules\Vendor\Repositories\Dashboard;

use Modules\Catalog\Entities\Category;
use Modules\Catalog\Entities\Product;
use Modules\Vendor\Entities\Vendor;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class RestaurantRepository
{
    protected $vendor;
    protected $branch;
    protected $product;
    protected $prodCategory;

    function __construct(Vendor $vendor, Product $product, Category $prodCategory)
    {
        $this->vendor = $vendor->restaurant();
        $this->branch = $vendor->branch();
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
        return $this->vendor->orderBy($order, $sort)->get();
    }

    public function getAllActive($order = 'id', $sort = 'desc')
    {
        return $this->vendor->active()->orderBy($order, $sort)->get();
    }

    public function findById($id)
    {
        return $this->vendor->withDeleted()->find($id);
    }

    public function countTable()
    {
        return $this->vendor->count();
    }

    public function create($request)
    {
        DB::beginTransaction();

        try {

            $restaurant = $this->vendor->create([
                'image' => $request->image ? path_without_domain($request->image) : 'storage/photos/shares/vendors/default.jpg',
                'status' => $request->status ? 1 : 0,
                'enable_delivery' => $request->enable_delivery ? 1 : 0,
                'enable_pickup' => $request->enable_pickup ? 1 : 0,
            ]);

            $this->translateTable($restaurant, $request);

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

        $restaurant = $this->findById($id);
        $restore = $request->restore ? $this->restoreSoftDelete($restaurant) : null;

        try {

            $restaurant->update([
                'image' => $request->image ? path_without_domain($request->image) : $restaurant->image,
                'status' => $request->status ? 1 : 0,
                'enable_delivery' => $request->enable_delivery ? 1 : 0,
                'enable_pickup' => $request->enable_pickup ? 1 : 0,
            ]);

            // Start - update this branch to be a main branch of the restaurant
            if (!is_null($request->is_main_branch)) {

                $this->branch->where('parent_id', $restaurant->id)
                    ->update(['is_main_branch' => 0]);

                $this->branch->where('parent_id', $restaurant->id)
                    ->where('id', $request->is_main_branch)
                    ->update(['is_main_branch' => 1]);

            } else {
                $this->branch->where('parent_id', $restaurant->id)
                    ->update(['is_main_branch' => 0]);
            }
            // End - update this branch to be a main branch of the restaurant

            $this->translateTable($restaurant, $request);

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

    public function QueryTable($request)
    {
        $query = $this->vendor->with(['translations']);

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
}
