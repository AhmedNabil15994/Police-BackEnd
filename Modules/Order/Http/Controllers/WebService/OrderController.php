<?php

namespace Modules\Order\Http\Controllers\WebService;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Str;
use Modules\Cart\Traits\CartTrait;
use Modules\Catalog\Repositories\WebService\CatalogRepository as Catalog;
use Modules\Catalog\Traits\FrontEnd\CatalogTrait;
use Modules\Order\Http\Requests\WebService\CreateOrderRequest;

//use Modules\Order\Http\Requests\WebService\CreateOrderRequestOld;
use Modules\Order\Transformers\WebService\OrderProductResource;
use Modules\Vendor\Repositories\WebService\VendorRepository as Vendor;
use Notification;
use Illuminate\Http\Request;
use Modules\Order\Events\ActivityLog;
use Modules\Order\Events\VendorOrder;

//use Modules\Transaction\Services\PaymentService;
use Modules\Transaction\Services\UPaymentService;
use Modules\Transaction\Services\CbkPaymentService;

use Modules\Order\Transformers\WebService\OrderResource;
use Modules\Order\Repositories\WebService\OrderRepository as Order;

//use Modules\Order\Repositories\WebService\OrderRepositoryOld as Order;
use Modules\Wrapping\Repositories\WebService\WrappingRepository as Wrapping;
use Modules\Company\Repositories\WebService\CompanyRepository as Company;
use Modules\Apps\Http\Controllers\WebService\WebServiceController;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class OrderController extends WebServiceController
{
    use CartTrait;

    protected $payment;
    protected $order;
    protected $company;
    protected $catalog;
    protected $vendor;

    function __construct(Order $order, CbkPaymentService $payment, Company $company, Catalog $catalog, Vendor $vendor)
    {
        $this->payment = $payment;
        $this->order = $order;
        $this->company = $company;
        $this->catalog = $catalog;
        $this->vendor = $vendor;
    }

    public function createOrder(CreateOrderRequest /*Request*/ $request)
    {
        if (auth('api')->check())
            $userToken = auth('api')->user()->id;
        else
            $userToken = $request->user_id;

        $pay_id = Str::random(mt_rand(20, 30));
        $request->request->add(['pay_id' => $pay_id]);

        /*if ($request['payment'] != 'cash') {
            $order['total'] = 3;
            $order['id'] = '10';
            return $this->response([
                'paymentUrl' => $this->payment->send($order, $pay_id, $request['payment'], 'api-order'),
            ]);
        }
        dd($request->all());*/

        foreach (getCartContent($userToken) as $key => $item) {

            if ($item->attributes->product->product_type == 'product') {
                $cartProduct = $item->attributes->product;
                $product = $this->catalog->findOneProduct($cartProduct->id);
                if (!$product)
                    return $this->error(__('cart::api.cart.product.not_found') . $cartProduct->id, [], 401);

                ### Start - Check Single Addons Selections - Validation ###
                $selectedAddons = $item->attributes->has('addonsOptions') ? $item->attributes['addonsOptions']['data'] : [];
                $addOnsCheck = $this->checkProductAddonsValidation($selectedAddons, $product);
                if (gettype($addOnsCheck) == 'string')
                    return $this->error($addOnsCheck . ' : ' . $cartProduct->translate(locale())->title, [], 401);
                ### End - Check Single Addons Selections - Validation ###

                $product->product_type = 'product';
            } else {
                $cartProduct = $item->attributes->product;
                $product = $this->catalog->findOneProductVariant($cartProduct->id);
                if (!$product)
                    return $this->error(__('cart::api.cart.product.not_found') . $cartProduct->id, [], 401);

                $product->product_type = 'variation';
            }

            $checkPrdFound = $this->productFound($product, $item);
            if ($checkPrdFound)
                return $this->error($checkPrdFound, [], 401);

            $checkPrdStatus = $this->checkProductActiveStatus($product, $request);
            if ($checkPrdStatus)
                return $this->error($checkPrdStatus, [], 401);

            if (!is_null($product->qty)) {
                $checkPrdMaxQty = $this->checkMaxQty($product, $cartProduct->qty);
                if ($checkPrdMaxQty)
                    return $this->error($checkPrdMaxQty, [], 401);
            }

            $checkVendorStatus = $this->vendorStatus($product);
            if ($checkVendorStatus)
                return $this->error($checkVendorStatus, [], 401);
        }

        $companyDeliveryFees = getCartConditionByName($userToken, 'company_delivery_fees');
        if (!is_null($companyDeliveryFees)) {
            $request->request->add(['state_id' => $companyDeliveryFees->getAttributes()['state_id']]);
        } else {
            $request->request->add(['state_id' => null]);
        }

        if ($request->pickup_delivery_type == 'pickup') {
            $pickupDelivery = $request->pickupData;
            $pickupDelivery['pickup_delivery_type'] = 'pickup';
        } else {
            $pickupDelivery['pickup_delivery_type'] = 'delivery';
        }
        $request->request->add(['pickup_delivery' => $pickupDelivery]);

        $order = $this->order->create($request, $userToken);
        if (!$order)
            return $this->error('error', [], 401);

        if ($request['payment'] != 'cash') {
            $payment = $this->payment->send($order, $pay_id, $request['payment'], 'api-order');

            return $this->response([
                'paymentUrl' => $payment,
                'order_id'  => $order->id,
                'order_tracking_id' => encryptOrderId($order->id),
            ]);
        }

        $this->fireLog($order);
        $this->clearCart($userToken);

        return $this->response(new OrderResource($order));
    }

    public function callback(Request $request)
    {
        // Verify payment success or failed
        $data = $this->payment->verifyPayment($request->encrp);
        if (isset($data['Status']) && $data['Status'] == '1') {
            // success
            logger('success::');
            logger($request->all());
            logger('data::');
            logger($data);
            $order = $this->order->updateOrderCBK($data);
            return $order ? $this->redirectToPaymentOrOrderPage($data, null, 'MerchUdf1') : $this->failed($request);
        } else {
            // cancelled | failed
            logger('failed_or_cancelled::');
            logger($request->all());
            logger('data::');
            logger($data);
            $this->order->updateOrderCBK($data);
            return $this->failed($request);
        }
    }

    public function failed(Request $request)
    {
        logger('failed::');
        logger($request->all());
        return $this->error(__('order::frontend.orders.index.alerts.order_failed'), [], 401);
    }

    public function redirectToPaymentOrOrderPage($data, $order = null, $orderIdColName = 'OrderID')
    {
        $order = ($order == null) ? $this->order->findById($data[$orderIdColName]) : $this->order->findById($order->id);
        $userToken = auth('api')->check() ? auth('api')->id() : null;
        if ($order) {
            $this->fireLog($order);
            $this->clearCart($userToken);
            return $this->response(new OrderResource($order));
        } else
            return $this->error(__('order::frontend.orders.index.alerts.order_failed'), [], 401);
    }

    /*public function webhooks(Request $request)
    {
        $this->order->updateOrder($request);
    }

    public function success(Request $request)
    {
        $order = $this->order->updateOrder($request);
        if ($order) {
            $orderDetails = $this->order->findById($request['OrderID']);
            $userToken = auth('api')->check() ? auth('api')->id() : ($request->userToken ?? null);
            if ($orderDetails) {
                $this->fireLog($orderDetails);
                $this->clearCart($userToken);
                return $this->response(new OrderResource($orderDetails));
            } else
                return $this->error(__('order::frontend.orders.index.alerts.order_failed'), [], 401);
        }
    }

    public function failed(Request $request)
    {
        $this->order->updateOrder($request);
        return $this->error(__('order::frontend.orders.index.alerts.order_failed'), [], 401);
    }*/

    public function userOrdersList(Request $request)
    {
        if (auth('api')->check()) {
            $userId = auth('api')->id();
            $userColumn = 'user_id';
        } else {
            $userId = $request->user_token ?? 'not_found';
            $userColumn = 'user_token';
        }
        $orders = $this->order->getAllByUser($userId, $userColumn);
        return $this->response(OrderResource::collection($orders));
    }

    public function getOrderDetails(Request $request, $id)
    {
        $order = $this->order->findById($id);

        if (!$order)
            return $this->error(__('order::api.orders.validations.order_not_found'), [], 401);

        $allOrderProducts = $order->orderProducts->mergeRecursive($order->orderVariations);
        return $this->response(OrderProductResource::collection($allOrderProducts));
    }

    public function fireLog($order)
    {
        $dashboardUrl = LaravelLocalization::localizeUrl(url(route('dashboard.orders.show', $order->id)));
        $data = [
            'id' => $order->id,
            'type' => 'orders',
            'url' => $dashboardUrl,
            'base_url'  => route('dashboard.orders.show', encryptOrderId($order->id)),
            'description_en' => 'New Order',
            'description_ar' => 'طلب جديد ',
        ];
        $data2 = [];

        if ($order->vendors) {
            foreach ($order->vendors as $k => $value) {
                $vendor = $this->vendor->findById($value->id);
                if ($vendor) {
                    $vendorUrl = LaravelLocalization::localizeUrl(url(route('vendor.orders.show', $order->id)));
                    $data2 = [
                        'ids' => $vendor->sellers->pluck('id'),
                        'type' => 'vendor',
                        'url' => $vendorUrl,
                        'description_en' => 'New Order',
                        'description_ar' => 'طلب جديد',
                    ];
                }
            }
        }

        pushDataToWebhook($order);
        event(new ActivityLog($data));
        if (count($data2) > 0) {
            event(new VendorOrder($data2));
        }
    }

    public function trackOrder($orderId)
    {
        $orderId = decryptOrderId($orderId);
        $order = $this->order->findById($orderId);
        if (!$order)
            return $this->error(__('order::api.orders.validations.order_not_found'), [], 401);

        if($order && !in_array($order->order_status_id,[3,7])){
            return $this->error(__('order::api.orders.validations.order_not_ready'), [], 401);
        }

        return $this->response(new OrderResource($order));
    }
}
