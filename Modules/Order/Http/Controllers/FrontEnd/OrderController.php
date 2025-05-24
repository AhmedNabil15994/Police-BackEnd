<?php

namespace Modules\Order\Http\Controllers\FrontEnd;

use Cart;
use Illuminate\Support\Str;
use Modules\Catalog\Traits\FrontEnd\CatalogTrait;
use Modules\Order\Transformers\WebService\OrderWebhookResource;
use Notification;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\MessageBag;
use Modules\Order\Events\ActivityLog;
use Modules\Order\Events\VendorOrder;
use Modules\Catalog\Traits\ShoppingCartTrait;
use Modules\Order\Http\Requests\FrontEnd\CreateOrderRequest;
use Modules\Order\Repositories\FrontEnd\OrderRepository as Order;
use Modules\Order\Notifications\FrontEnd\AdminNewOrderNotification;
use Modules\Order\Notifications\FrontEnd\UserNewOrderNotification;
use Modules\Order\Notifications\FrontEnd\VendorNewOrderNotification;
use Modules\Catalog\Repositories\FrontEnd\ProductRepository as Product;
use Modules\Vendor\Repositories\FrontEnd\VendorRepository as Vendor;
use Modules\Transaction\Services\CbkPaymentService;
use Modules\Transaction\Services\UPaymentService;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class OrderController extends Controller
{
    use ShoppingCartTrait, CatalogTrait;

    protected $payment;
    protected $order;
    protected $product;
    protected $vendor;

    function __construct(Order $order, CbkPaymentService $payment, Product $product, Vendor $vendor)
    {
        $this->payment = $payment;
        $this->order = $order;
        $this->product = $product;
        $this->vendor = $vendor;
    }

    public function index()
    {
        $ordersIDs = isset($_COOKIE[config('core.config.constants.ORDERS_IDS')]) && !empty($_COOKIE[config('core.config.constants.ORDERS_IDS')]) ? (array)\GuzzleHttp\json_decode($_COOKIE[config('core.config.constants.ORDERS_IDS')]) : [];

        if (auth()->user()) {
            $orders = $this->order->getAllByUser($ordersIDs);
            return view('order::frontend.orders.index', compact('orders'));
        } else {
            $userToken = get_cookie_value(config('core.config.constants.CART_KEY')) ?? 'not_found';
            $orders = count($ordersIDs) > 0 ? $this->order->getAllGuestOrders($ordersIDs, $userToken) : [];
            return view('order::frontend.orders.index', compact('orders'));
        }
    }

    public function invoice($id)
    {
        /*if (auth()->user())
            $order = $this->order->findByIdWithUserId($id);
        else
            $order = $this->order->findGuestOrderById($id);*/

        $order = $this->order->findGuestOrderById($id);

        if (!$order)
            return abort(404);

        $order->orderProducts = $order->orderProducts->mergeRecursive($order->orderVariations);
        return view('order::frontend.orders.details', compact('order'));
    }

    public function reOrder($id)
    {
        $order = $this->order->findByIdWithUserId($id);
        $order->orderProducts = $order->orderProducts->mergeRecursive($order->orderVariations);
        return view('order::frontend.orders.re-order', compact('order'));
    }

    public function guestInvoice()
    {
        $savedID = [];
        if (isset($_COOKIE[config('core.config.constants.ORDERS_IDS')]) && !empty($_COOKIE[config('core.config.constants.ORDERS_IDS')])) {
            $savedID = (array)\GuzzleHttp\json_decode($_COOKIE[config('core.config.constants.ORDERS_IDS')]);
        }
        $id = count($savedID) > 0 ? $savedID[count($savedID) - 1] : 0;
        $order = $this->order->findByIdWithGuestId($id);
        if (!$order)
            abort(404);

        $order->orderProducts = $order->orderProducts->mergeRecursive($order->orderVariations);
        return view('order::frontend.orders.invoice', compact('order'))->with([
            'alert' => 'success', 'status' => __('order::frontend.orders.index.alerts.order_success')
        ]);
    }

    public function createOrder(/*Request*/CreateOrderRequest $request)
    {
        /*if ($request['payment'] != 'cash') {
            $order['total'] = 3;
            $order['id'] = '10';
            return redirect()->away($this->payment->send($order, $request['payment']));
        }*/

        // save contact info in client cookie
        $this->saveContactInfoCookie($request->except(['_token', 'payment']));

        $pay_id = Str::random(mt_rand(20, 30));
        $request->request->add(['pay_id' => $pay_id]);

        $errors1 = [];
        $errors2 = [];
        $errors3 = [];
        $errors4 = [];

        foreach (getCartContent() as $key => $item) {

            if ($item->attributes->product->product_type == 'product') {
                $cartProduct = $item->attributes->product;
                $product = $this->product->findOneProduct($cartProduct->id);
                if (!$product) {
                    $prdTitle = optional($cartProduct->translate(locale()))->title;
                    return redirect()->back()->with(['alert' => 'danger', 'status' => __('cart::api.cart.product.not_found') . $prdTitle]);
                }

                ### Start - Check Single Addons Selections - Validation ###
                $selectedAddons = $item->attributes->has('addonsOptions') ? $item->attributes['addonsOptions']['data'] : [];
                $addOnsCheck = $this->checkProductAddonsValidation($selectedAddons, $product);
                if (gettype($addOnsCheck) == 'string')
                    return redirect()->back()->with(['alert' => 'danger', 'status' => $addOnsCheck . ' : ' . $cartProduct->translate(locale())->title]);
                ### End - Check Single Addons Selections - Validation ###

                $product->product_type = 'product';
            } else {
                $cartProduct = $item->attributes->product;
                $product = $this->product->findOneProductVariant($cartProduct->id);
                if (!$product)
                    return redirect()->back()->with(['alert' => 'danger', 'status' => __('cart::api.cart.product.not_found') . $cartProduct->id]);

                $product->product_type = 'variation';
            }

            $productFound = $this->productFound($product, $item);
            if ($productFound) {
                $errors1[] = $productFound;
            }

            $activeStatus = $this->checkActiveStatus($product, $request);
            if ($activeStatus) {
                $errors2[] = $activeStatus;
            }

            if (!is_null($product->qty)) {
                $maxQtyInCheckout = $this->checkMaxQtyInCheckout($product, $request, $cartProduct->qty);
                if ($maxQtyInCheckout) {
                    $errors3[] = $maxQtyInCheckout;
                }
            }

            $vendorStatusError = $this->checkVendorStatus($product);
            if ($vendorStatusError) {
                $errors4[] = $vendorStatusError;
            }
        }

        if ($errors1 || $errors2 || $errors3 || $errors4) {
            $errors = new MessageBag([
                'productCart' => $errors1,
                'productCart2' => $errors2,
                'productCart3' => $errors3,
                'productCart4' => $errors4,
            ]);
            return redirect()->back()->with(["errors" => $errors]);
        }

        if (!is_null(checkPickupDeliveryCookie())) {
            $request->request->add(['state_id' => optional(optional(checkPickupDeliveryCookie())->content)->state_id]);
        } else {
            $request->request->add(['state_id' => null]);
        }

        $pickupDelivery = $request->pickup_delivery;
        $pickupDelivery['pickup_delivery_type'] = optional(checkPickupDeliveryCookie())->type;
        if (optional(checkPickupDeliveryCookie())->type == 'pickup') {
            $pickupDelivery['branch_id'] = optional(optional(checkPickupDeliveryCookie())->content)->vendor_id;
        }
        $request->request->add(['pickup_delivery' => $pickupDelivery]);
        $userToken = $this->getUserCartToken();

        $order = $this->order->create($request, $userToken);
        if (!$order)
            return $this->redirectToFailedPayment();

        if ($request['payment'] != 'cash' && getCartTotal($userToken) > 0) {
            return redirect()->away($this->payment->send($order, $pay_id, $request['payment'], 'frontend-order'));
        }
        return $this->redirectToPaymentOrOrderPage($request, $order);
    }

    public function webhooks(Request $request)
    {
        $this->order->updateOrder($request);
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
            return $order ? $this->redirectToPaymentOrOrderPage($data, null, 'MerchUdf1') : $this->redirectToFailedPayment();
        } else {
            // cancelled | failed
            logger('failed_or_cancelled::');
            logger($request->all());
            logger('data::');
            logger($data);
            $this->order->updateOrderCBK($data);
            return $this->redirectToFailedPayment();
        }
    }

    public function success(Request $request)
    {
        $order = $this->order->updateOrder($request);
        return $order ? $this->redirectToPaymentOrOrderPage($request) : $this->redirectToFailedPayment();
    }

    public function failed(Request $request)
    {
        $this->order->updateOrder($request);
        return $this->redirectToFailedPayment();
    }

    public function redirectToPaymentOrOrderPage($data, $order = null, $orderIdColName = 'OrderID')
    {
        $order = ($order == null) ? $this->order->findById($data[$orderIdColName]) : $this->order->findById($order->id);
        $this->sendNotifications($order);
        $this->clearCart();
        return $this->redirectToInvoiceOrder($order);
    }

    public function redirectToInvoiceOrder($order)
    {
        ################# Start Store Guest Orders In Browser Cookie ######################
        if (isset($_COOKIE[config('core.config.constants.ORDERS_IDS')]) && !empty($_COOKIE[config('core.config.constants.ORDERS_IDS')])) {
            $cookieArray = (array)\GuzzleHttp\json_decode($_COOKIE[config('core.config.constants.ORDERS_IDS')]);
        }
        $cookieArray[] = $order['id'];
        setcookie(config('core.config.constants.ORDERS_IDS'), \GuzzleHttp\json_encode($cookieArray), time() + (5 * 365 * 24 * 60 * 60), '/'); // expires at 5 year
        ################# End Store Guest Orders In Browser Cookie ######################

        if (auth()->user())
            return redirect()->route('frontend.orders.invoice', $order->id)->with([
                'alert' => 'success', 'status' => __('order::frontend.orders.index.alerts.order_success')
            ]);

        return redirect()->route('frontend.orders.guest.invoice');
    }

    public function redirectToFailedPayment()
    {
        return redirect()->route('frontend.checkout.index')->with([
            'alert' => 'danger', 'status' => __('order::frontend.orders.index.alerts.order_failed')
        ]);
    }

    public function sendNotifications($order)
    {
        $this->fireLog($order);

        try{
            if ($order->orderAddress) {
                Notification::route('mail', $order->orderAddress->email)->notify(
                    (new UserNewOrderNotification($order))->locale(locale())
                );
            }

            if (config('setting.contact_us.email')) {
                Notification::route('mail', config('setting.contact_us.email'))->notify(
                    (new AdminNewOrderNotification($order))->locale(locale())
                );
            }
            if ($order->vendors) {
                Notification::route('mail', $this->pluckVendorEmails($order))->notify(
                    (new VendorNewOrderNotification($order))->locale(locale())
                );
            }
        }catch (\Exception $e){}
    }

    public function pluckVendorEmails($order)
    {
        foreach ($order->vendors as $k => $value) {
            $vendor = $this->vendor->findById($value->vendor_id);
            if ($vendor) {
                $emails = $vendor->sellers->pluck('email');
                return $emails;
            }
        }
        return [];
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

    public function orderWebhookDetails($id)
    {
        $orderId = decryptOrderId($id);
        $order = $this->order->findById($orderId);
        if (!$order)
            return $this->error(__('order::api.orders.validations.order_not_found'), [], 401);

        return  response()->json([
            'success' => true,
            'order'   => new OrderWebhookResource($order),
        ]);
    }

}
