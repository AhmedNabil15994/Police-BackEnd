<?php

namespace Modules\Supplier\Repositories\Dashboard;

use Modules\Supplier\Entities\Supplier;
use Illuminate\Support\Facades\DB;

class SupplierRepository
{
    protected $supplier;

    function __construct(Supplier $supplier)
    {
        $this->supplier = $supplier;
    }

    public function getAll()
    {
        $supplier = $this->supplier->orderBy('id', 'desc')->get();
        return $supplier;
    }

    public function findById($id)
    {
        $supplier = $this->supplier->find($id);
        return $supplier;
    }

    public function create($request)
    {
        DB::beginTransaction();

        try {
            $supplier = $this->supplier->create([
                'image' => path_without_domain($request->image),
                'status' => $request->status ? 1 : 0,
            ]);

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

        $supplier = $this->findById($id);

        try {

            $supplier->update([
                'image' => $request->image ? path_without_domain($request->image) : $supplier->image,
                'status' => $request->status ? 1 : 0,
            ]);

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

    public function delete($id)
    {
        DB::beginTransaction();

        try {

            $model = $this->findById($id);

            if ($model)
                $model->delete();

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
        $query = $this->supplier;

        $query = $this->filterDataTable($query, $request);

        return $query;
    }

    public function filterDataTable($query, $request)
    {
        // SEARCHING INPUT DATATABLE
        if ($request->input('search.value') != null) {

            $query = $query->where(function ($query) use ($request) {
                $query->where('id', 'like', '%' . $request->input('search.value') . '%');
            });

        }

        // FILTER
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

        return $query;
    }

}
