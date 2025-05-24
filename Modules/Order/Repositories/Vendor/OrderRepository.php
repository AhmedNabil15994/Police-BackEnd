<?php

namespace Modules\Order\Repositories\Vendor;

use Modules\Order\Entities\Order;
use Auth;
use DB;

class OrderRepository
{
    function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function monthlyOrders()
    {
        $data["orders_dates"] = $this->order
            ->whereHas('orderStatus', function ($query) {
                $query->successOrderStatus();
            })
            ->whereHas('vendors', function ($q) {
                $q->whereHas('sellers', function ($q) {
                    $q->where('seller_id', auth()->user()->id);
                });
            })
            ->select(\DB::raw("DATE_FORMAT(created_at,'%Y-%m') as date"))
            ->groupBy('date')
            ->pluck('date');

        $ordersIncome = $this->order
            ->whereHas('orderStatus', function ($query) {
                $query->successOrderStatus();
            })
            ->whereHas('vendors', function ($q) {
                $q->whereHas('sellers', function ($q) {
                    $q->where('seller_id', auth()->user()->id);
                });
            })
            ->select(\DB::raw("sum(total) as profit"))
            ->groupBy(\DB::raw("DATE_FORMAT(created_at, '%Y-%m')"))
            ->get();

        $data["profits"] = json_encode(array_pluck($ordersIncome, 'profit'));

        return $data;
    }

    public function ordersType()
    {
        $orders = $this->order
            ->whereHas('vendors', function ($q) {
                $q->whereHas('sellers', function ($q) {
                    $q->where('seller_id', auth()->user()->id);
                });
            })
            ->select("order_status_id", \DB::raw("count(id) as count"))
            ->groupBy('order_status_id')
            ->get();


        foreach ($orders as $order) {

            $status = $order->orderStatus->translate(locale())->title;
            $order->type = $status;

        }

        $data["ordersCount"] = json_encode(array_pluck($orders, 'count'));
        $data["ordersType"] = json_encode(array_pluck($orders, 'type'));

        return $data;
    }

    public function completeOrders()
    {
        $orders = $this->order->whereHas('orderStatus', function ($query) {
            $query->successOrderStatus();
        })
            ->whereHas('vendors', function ($q) {
                $q->whereHas('sellers', function ($q) {
                    $q->where('seller_id', auth()->user()->id);
                });
            })->count();

        return $orders;
    }

    public function totalProfit()
    {
        return $this->order
            ->whereHas('vendors', function ($q) {
                $q->whereHas('sellers', function ($q) {
                    $q->where('seller_id', auth()->user()->id);
                });
            })->whereHas('orderStatus', function ($query) {
                $query->successOrderStatus();
            })->sum('total');
    }

    public function getAll($order = 'id', $sort = 'desc')
    {
        $orders = $this->order
            ->whereHas('vendors', function ($q) {
                $q->whereHas('sellers', function ($q) {
                    $q->where('seller_id', auth()->user()->id);
                });
            })->orderBy($order, $sort)->get();

        return $orders;
    }

    public function findById($id)
    {
        $order = $this->order
            ->with([
                'orderProducts.product',
            ])
            ->whereHas('vendors', function ($q) {
                $q->whereHas('sellers', function ($q) {
                    $q->where('seller_id', auth()->user()->id);
                });
            })
            ->withDeleted()->find($id);

        return $order;
    }

    public function updateUnread($id)
    {
        $order = $this->findById($id);
        if (!$order)
            abort(404);

        $order->update([
            'unread' => true,
        ]);
    }

    public function updateStatus($request, $id)
    {
        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            $order = $this->findById($id);
            if (!$order)
                abort(404);

            $orderData = ['order_status_id' => $request['order_status']];
            if (isset($request['order_notes']) && !empty($request['order_notes']))
                $orderData['order_notes'] = $request['order_notes'];

            $order->update($orderData);
            $order->orderStatusesHistory()->attach([$request['order_status'] => ['user_id' => auth()->id()]]);

            if ($request['user_id']) {
                $order->driver()->delete();
                $order->driver()->updateOrCreate([
                    'user_id' => $request['user_id'],
                    'accepted'  => 1,
                ]);
            }

            \Illuminate\Support\Facades\DB::commit();
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
        $query = $this->order->whereHas('vendors', function ($q) {
            $q->whereHas('sellers', function ($q) {
                $q->where('seller_id', auth()->user()->id);
            });
        });

        if ($request->input('search.value')) {
            $query = $query->where(function ($query) use ($request) {
                $query->where('id', 'like', '%' . $request->input('search.value') . '%');
            });
        }

        $query = $this->filterDataTable($query, $request);
        return $query;
    }

    public function filterDataTable($query, $request)
    {
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
