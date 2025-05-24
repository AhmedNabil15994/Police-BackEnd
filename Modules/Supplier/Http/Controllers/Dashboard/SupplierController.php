<?php

namespace Modules\Supplier\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Core\Traits\DataTable;
use Modules\Supplier\Http\Requests\Dashboard\SupplierRequest;
use Modules\Supplier\Transformers\Dashboard\SupplierResource;
use Modules\Supplier\Repositories\Dashboard\SupplierRepository as Supplier;

class SupplierController extends Controller
{
    protected $supplier;

    function __construct(Supplier $supplier)
    {
        $this->supplier = $supplier;
    }

    public function index()
    {
        return view('supplier::dashboard.index');
    }

    public function datatable(Request $request)
    {
        $datatable = DataTable::drawTable($request, $this->supplier->QueryTable($request));

        $datatable['data'] = SupplierResource::collection($datatable['data']);

        return Response()->json($datatable);
    }

    public function create()
    {
        return view('supplier::dashboard.create');
    }

    public function store(SupplierRequest $request)
    {
        try {
            $create = $this->supplier->create($request);

            if ($create) {
                return Response()->json([true, __('apps::dashboard.general.message_create_success')]);
            }

            return Response()->json([false, __('apps::dashboard.general.message_error')]);
        } catch (\PDOException $e) {
            return Response()->json([false, $e->errorInfo[2]]);
        }
    }

    public function show($id)
    {
        return view('supplier::dashboard.show');
    }

    public function edit($id)
    {
        $supplier = $this->supplier->findById($id);
        return view('supplier::dashboard.edit', compact('supplier'));
    }

    public function update(SupplierRequest $request, $id)
    {
        try {
            $update = $this->supplier->update($request, $id);

            if ($update) {
                return Response()->json([true, __('apps::dashboard.general.message_update_success')]);
            }

            return Response()->json([false, __('apps::dashboard.general.message_error')]);
        } catch (\PDOException $e) {
            return Response()->json([false, $e->errorInfo[2]]);
        }
    }

    public function destroy($id)
    {
        try {
            $delete = $this->supplier->delete($id);

            if ($delete) {
                return Response()->json([true, __('apps::dashboard.general.message_delete_success')]);
            }

            return Response()->json([false, __('apps::dashboard.general.message_error')]);
        } catch (\PDOException $e) {
            return Response()->json([false, $e->errorInfo[2]]);
        }
    }

    public function deletes(Request $request)
    {
        try {
            $deleteSelected = $this->supplier->deleteSelected($request);

            if ($deleteSelected) {
                return Response()->json([true, __('apps::dashboard.general.message_delete_success')]);
            }

            return Response()->json([false, __('apps::dashboard.general.message_error')]);
        } catch (\PDOException $e) {
            return Response()->json([false, $e->errorInfo[2]]);
        }
    }
}
