<?php

namespace Modules\DriverApp\Http\Controllers\WebService\Orders;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Mail;
use Modules\DriverApp\Events\LocationUpdated;
use Modules\DriverApp\Http\Requests\WebService\TrackOrderRequest;
use Modules\DriverApp\Transformers\WebService\OrderResource;
use Modules\DriverApp\Repositories\WebService\OrderRepository as Order;
use Modules\Apps\Http\Controllers\WebService\WebServiceController;
use Modules\Notification\Repositories\Dashboard\NotificationRepository as Notification;
use Modules\Notification\Traits\SendNotificationTrait as SendNotification;
use Modules\Order\Mail\Dashboard\UpdateOrderStatusMail;
use Modules\DriverApp\Repositories\WebService\UserRepository as User;

class OrderController extends WebServiceController
{
    protected $order;
    protected $notification;
    protected $user;
    use SendNotification;

    function __construct(Order $order, Notification $notification, User $user)
    {
        $this->order = $order;
        $this->notification = $notification;
        $this->user = $user;
    }

    public function index(Request $request)
    {
        $orders = $this->order->getAllByVendor();
        return $this->response(OrderResource::collection($orders));
    }

    public function show(Request $request, $id)
    {
        $orderId = decryptOrderId($id);
        $order = $this->order->findDriverOrderById($orderId);
        if (!$order)
            return $this->error(__('driver_app::orders.orders.validations.order_not_found'), [], 422);

        return $this->response(new OrderResource($order));
    }

    public function updateOrderByDriver(Request $request, $id)
    {
        $order = $this->order->findNewOrderById($id);
        if (!$order) {
            return $this->error(__('driver_app::orders.driver.order_assigned_before'), [], 422);
        }

        $check = $this->order->updateOrderByDriver($order, $request, $id);
        if ($check) {
            return $this->response(null);
        } else {
            return $this->error(__('driver_app::orders.driver.oops_error'), [], 422);
        }

    }

    public function updateOrderStatus(Request $request, $id)
    {
        $orderId = decryptOrderId($id);
        $order = $this->order->findById($orderId);
        if (!$order) {
            return $this->error(__('driver_app::orders.orders.validations.order_not_found'), [], 422);
        }

        $check = $this->order->updateOrderStatus($order, $request);
        if ($check) {
            $this->sendNotification($order);
            return $this->response(null);
        } else {
            return $this->error(__('driver_app::orders.driver.oops_error'), [], 422);
        }

    }

    public function sendNotification($order)
    {
        if (!is_null($order->user_id)) { // send notification to user
            $this->sendMobileNotify($order, $order->user_id);
            /*$userEmail = $order->user->email ?? null;
        if (!is_null($userEmail))
        $this->sendEmailNotify($order, $userEmail);*/
        }

        if (!auth('api')->user()->can('driver_access') && !is_null(optional($order->driver)->user)) { // admin send notification to driver
            $this->sendMobileNotify($order, $order->driver->user->id);
            /*$driverEmail = $order->driver->user->email ?? null;
        if (!is_null($driverEmail))
        $this->sendEmailNotify($order, $driverEmail);*/
        }

        if (auth('api')->user()->can('driver_access')) { // driver send notification to admin
            $admins = $this->user->getAllAdmins();
            foreach ($admins as $admin) {
                $this->sendMobileNotify($order, $admin->id);
                /*if (!is_null($admin->email))
            $this->sendEmailNotify($order, $admin->email);*/
            }
        }
        return true;
    }

    public function sendMobileNotify($order, $userId)
    {
        $tokens = $this->notification->getAllUserTokens($userId);
        $locale = app()->getLocale();
        if (count($tokens) > 0) {
            $data = [
                'title' => __('order::dashboard.orders.notification.title'),
                'body' => __('order::dashboard.orders.notification.body') . ' - ' . $order->orderStatus->title,
                'type' => 'order',
                'id' => $order->id,
            ];
            $this->send($data, $tokens);
        }
        return true;
    }

    public function sendEmailNotify($order, $email)
    {
        Mail::to($email)->send(new UpdateOrderStatusMail($order));
        return true;
    }

    public function trackOrder(TrackOrderRequest $request,$orderId)
    {
        $orderId = decryptOrderId($orderId);
        $order = $this->order->findDriverOrderById($orderId);
        if($order && in_array($order->order_status_id,[3,7])){
            $order->locationHistory()->create([
                'latitude'  => $request->latitude,
                'longitude' => $request->longitude,
                'user_id'   => auth('api')->id()
            ]);
            event(new LocationUpdated($order, $request->latitude, $request->longitude));
        }
        return $this->response([]);
    }

    public function driverLocation(TrackOrderRequest $request)
    {
        $order = $this->order->findDriverCurrentOrder();
        $data = [
            'latitude'  => $request->latitude,
            'longitude' => $request->longitude,
            'user_id'   => auth('api')->id(),
        ];

        if($order && in_array($order->order_status_id,[3,7])){
            $data['order_id'] = $order->id;
            $order->locationHistory()->create($data);
            event(new LocationUpdated($order, $request->latitude, $request->longitude));
        }else{
            auth('api')->user()->locationHistory()->create($data);
        }
        return $this->response([]);
    }
}
