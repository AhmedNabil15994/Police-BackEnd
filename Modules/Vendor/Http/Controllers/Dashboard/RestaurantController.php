<?php

namespace Modules\Vendor\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Core\Traits\DataTable;
use Modules\Vendor\Http\Requests\Dashboard\RestaurantRequest;

;

use Modules\Vendor\Transformers\Dashboard\RestaurantResource;
use Modules\Vendor\Repositories\Dashboard\RestaurantRepository as Restaurant;

class RestaurantController extends Controller
{
    protected $restaurant;

    function __construct(Restaurant $restaurant)
    {
        $this->restaurant = $restaurant;
    }

    public function index()
    {
        return view('vendor::dashboard.restaurants.index');
    }

    public function sorting()
    {
        $restaurants = $this->restaurant->getAll('sorting', 'ASC');
        return view('vendor::dashboard.restaurants.sorting', compact('restaurants'));
    }

    public function getAllActiveRestaurants()
    {
        $restaurants = $this->restaurant->getAllActive();
        return Response()->json([true, 'data' => $restaurants]);
    }

    public function storeSorting(Request $request)
    {
        $create = $this->restaurant->sorting($request);

        if ($create) {
            return Response()->json([true, __('apps::dashboard.general.message_create_success')]);
        }

        return Response()->json([false, __('apps::dashboard.general.message_error')]);
    }

    public function datatable(Request $request)
    {
        $datatable = DataTable::drawTable($request, $this->restaurant->QueryTable($request));
        $datatable['data'] = RestaurantResource::collection($datatable['data']);

        return Response()->json($datatable);
    }

    public function create()
    {
        if (config('setting.other.toggle_add_new_restaurant') != 1)
            return abort(404);

        return view('vendor::dashboard.restaurants.create');
    }

    public function store(RestaurantRequest/*Request*/ $request)
    {
        if (config('setting.other.toggle_add_new_restaurant') != 1)
            return abort(404);

        try {
            $create = $this->restaurant->create($request);

            if ($create) {
                return Response()->json([true, __('apps::dashboard.general.message_create_success')]);
            }

            return Response()->json([false, __('apps::dashboard.general.message_error')]);

        } catch (\Exception $e) {
            return Response()->json([false, $e->errorInfo[2]]);
        }
    }

    public function show($id)
    {
        abort(404);
        return view('vendor::dashboard.restaurants.show');
    }

    public function edit($id)
    {
        $restaurant = $this->restaurant->findById($id);
        if (!$restaurant)
            return abort(404);

        return view('vendor::dashboard.restaurants.edit', compact('restaurant'));
    }

    public function update(RestaurantRequest $request, $id)
    {
        try {
            $update = $this->restaurant->update($request, $id);

            if ($update) {
                return Response()->json([true, __('apps::dashboard.general.message_update_success')]);
            }

            return Response()->json([false, __('apps::dashboard.general.message_error')]);
        } catch (\Exception $e) {
            return Response()->json([false, $e->errorInfo[2]]);
        }
    }

    public function destroy($id)
    {
        try {
//            if (!isset(config('setting.other')['is_multi_vendors']) || config('setting.other')['is_multi_vendors'] == 1) {

            if ($this->restaurant->countTable() > 1) {

                $delete = $this->restaurant->delete($id);

                if ($delete) {
                    return Response()->json([true, __('apps::dashboard.general.message_delete_success')]);
                }

                return Response()->json([false, __('apps::dashboard.general.message_error')]);

            } else {
                return Response()->json([false, __('apps::dashboard.general.not_all_items_can_be_deleted')]);
            }

            /*} else
                return abort(404);*/

        } catch (\Exception $e) {
            return Response()->json([false, $e->errorInfo[2]]);
        }
    }

    public function deletes(Request $request)
    {
        try {
//            if (!isset(config('setting.other')['is_multi_vendors']) || config('setting.other')['is_multi_vendors'] == 1) {

            if ($this->restaurant->countTable() > 1) {

                $deleteSelected = $this->restaurant->deleteSelected($request);

                if ($deleteSelected) {
                    return Response()->json([true, __('apps::dashboard.general.message_delete_success')]);
                }

                return Response()->json([false, __('apps::dashboard.general.message_error')]);

            } else {
                return Response()->json([false, __('apps::dashboard.general.not_all_items_can_be_deleted')]);
            }

            /*} else
                return abort(404);*/

        } catch (\Exception $e) {
            return Response()->json([false, $e->errorInfo[2]]);
        }
    }

}
