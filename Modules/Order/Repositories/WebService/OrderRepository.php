<?php

namespace Modules\Order\Repositories\WebService;

use Modules\Order\Entities\OrderStatusesHistory;
use Modules\Order\Traits\OrderCalculationTrait;
use Modules\Variation\Entities\ProductVariant;
use Modules\User\Repositories\WebService\AddressRepository;
use Modules\Order\Entities\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderRepository
{
    use OrderCalculationTrait;

    protected $variantPrd;
    protected $order;
    protected $address;

    function __construct(Order $order, ProductVariant $variantPrd, AddressRepository $address)
    {
        $this->variantPrd = $variantPrd;
        $this->order = $order;
        $this->address = $address;
    }

    public function getAllByUser($userId, $userColumn = 'user_id', $order = 'id', $sort = 'desc')
    {
        $orders = $this->order->with(['orderStatus'])->successOrders()->where($userColumn, $userId)->orderBy($order, $sort)->get();
        return $orders;
    }

    public function findById($id)
    {
        $order = $this->order->with('orderProducts')->find($id);
        return $order;
    }

    public function findByIdWithUserId($id)
    {
        $order = $this->order->where('user_id', auth('api')->id())->find($id);
        return $order;
    }

    public function create($request, $userToken = null)
    {
        $orderData = $this->calculateTheOrder($userToken);

        DB::beginTransaction();

        try {
            $userId = auth('api')->check() ? auth('api')->id() : null;
            if ($request['payment'] == 'cash') {
                $orderStatus = 1; // pending
                $paymentStatus = 4; // cash
            } else {
                $orderStatus = 1; // pending
                $paymentStatus = 1; // pending
            }

            $inputData = [
                'original_subtotal' => $orderData['original_subtotal'],
                'subtotal' => $orderData['subtotal'],
                'off' => $orderData['off'],
                'shipping' => $orderData['shipping'],
                'total' => $orderData['total'],
                'total_profit' => $orderData['profit'],

                /*'total_comission' => $orderData['commission'],
                'total_profit_comission' => $orderData['totalProfitCommission'],
                'vendor_id' => $orderData['vendor_id'],*/

                'user_id' => $userId,
                'user_token' => auth('api')->guest() ? $request->user_id : null,
                'order_status_id' => $orderStatus,
                'payment_status_id' => $paymentStatus,
                'notes' => $request['notes'] ?? null,
                'pickup_delivery' => $request->pickup_delivery ?? null,
            ];

            $shippingTime = null;
            if (isset($request->shipping_time) && $request->shipping_time == 'now')
                $shippingTime = json_encode(['shipping_time' => $request->shipping_time]);
            else
                $shippingTime = json_encode(['shipping_time' => $request->shipping_time, 'shipping_day' => $request->shipping_day, 'shipping_hour' => $request->shipping_hour]);

            $inputData['shipping_time'] = $shippingTime;

            $orderCreated = $this->order->create($inputData);

            if (!is_null($orderStatus)) {
                // Add Order Status History
                $orderCreated->orderStatusesHistory()->sync([$orderStatus => ['user_id' => $userId]]);
            }

            $orderCreated->transactions()->create([
                'method' => $request['payment'],
                'result' => ($request['payment'] == 'cash') ? 'CASH' : null,
            ]);

            $this->createOrderProducts($orderCreated, $orderData);

            if (!empty($orderData['vendors'])) {
                $this->createOrderVendors($orderCreated, $orderData['vendors']);
            }

            if (!is_null($orderData['coupon'])) {
                $orderCreated->orderCoupons()->create([
                    'coupon_id' => $orderData['coupon']['id'],
                    'code' => $orderData['coupon']['code'],
                    'discount_type' => $orderData['coupon']['type'],
                    'discount_percentage' => $orderData['coupon']['discount_percentage'],
                    'discount_value' => $orderData['coupon']['discount_value'],
                    'products' => $orderData['coupon']['products'],
                ]);
            }

            // $this->createOrderCompanies($orderCreated, $request);

            ############ START To Add Order Address ###################
            $this->createOrderAddress($orderCreated, $request);

            /*if ($request->address_type == 'guest_address') {
                $this->createOrderAddress($orderCreated, $request, 'guest_address');
            } elseif ($request->address_type == 'selected_address') {
                // get address by id
                $companyDeliveryFees = getCartConditionByName($userToken, 'company_delivery_fees');
                $addressId = isset($companyDeliveryFees->getAttributes()['address_id'])
                    ? $companyDeliveryFees->getAttributes()['address_id']
                    : null;
                $address = $this->address->findByIdWithoutAuth($addressId);
                if ($address)
                    $this->createOrderAddress($orderCreated, $address, 'selected_address');
                else
                    return false;
            }*/
            ############ END To Add Order Address ###################

            DB::commit();
            return $orderCreated;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function createOrderProducts($orderCreated, $orderData)
    {
        foreach ($orderData['products'] as $product) {

            if ($product['product_type'] == 'product') {
                $data = [
                    'product_id' => $product['product_id'],
                    'off' => $product['off'],
                    'qty' => $product['quantity'],
                    'price' => $product['original_price'],
                    'sale_price' => $product['sale_price'],
                    'original_total' => $product['original_total'],
                    'total' => $product['total'],
                    'total_profit' => $product['total_profit'],
                    'notes' => $product['notes'] ?? null,
                    'add_ons_option_ids' => !empty($product['addonsOptions']) && count($product['addonsOptions']) > 0 ? \GuzzleHttp\json_encode($product['addonsOptions']) : null,
                ];
                $orderProduct = $orderCreated->orderProducts()->create($data);
                foreach ($orderCreated->orderProducts as $value) {
                    if (!is_null($value->product->qty)) {
                        $value->product()->decrement('qty', $value['qty']);
                    }
                }
            } else {
                $orderProduct = $orderCreated->orderVariations()->create([
                    'product_variant_id' => $product['product_id'],
                    'off' => $product['off'],
                    'qty' => $product['quantity'],
                    'price' => $product['original_price'],
                    'sale_price' => $product['sale_price'],
                    'original_total' => $product['original_total'],
                    'total' => $product['total'],
                    'total_profit' => $product['total_profit'],
                    'notes' => $product['notes'] ?? null,
                    // 'add_ons_option_ids' => !empty($product['add_ons_option_ids']) && count($product['add_ons_option_ids']) > 0 ? $product['add_ons_option_ids'] : [],
                ]);

                $productVariant = $this->variantPrd->with('productValues')->find($product['product_id']);

                // add product_variant_values to order variations
                if (count($productVariant->productValues) > 0) {
                    foreach ($productVariant->productValues as $k => $value) {
                        $orderProduct->orderVariantValues()->create([
                            'product_variant_value_id' => $value->id,
                        ]);
                    }
                }

                foreach ($orderCreated->orderVariations as $value) {
                    if (!is_null($value->variant->qty)) {
                        $value->variant()->decrement('qty', $value['qty']);
                    }
                }
            }
        }
    }

    public function createOrderVendors($orderCreated, $vendors)
    {
        foreach ($vendors as $k => $vendor) {
            $orderCreated->vendors()->attach($vendor['id'], [
                'total_comission' => $vendor['commission'],
                'total_profit_comission' => $vendor['totalProfitCommission'],
                'original_subtotal' => $vendor['original_subtotal'],
                'subtotal' => $vendor['subtotal'],
                'qty' => $vendor['qty'],
            ]);
        }
    }

    public function createOrderAddress($orderCreated, $address)
    {
        $orderCreated->orderAddress()->create([
            'username' => $address['address']['username'] ?? null,
            'email' => $address['address']['email'] ?? null,
            'mobile' => $address['address']['mobile'] ?? null,
            'address' => $address['address']['address'] ?? null,
            'block' => $address['address']['block'] ?? null,
            'street' => $address['address']['street'] ?? null,
            'building' => $address['address']['building'] ?? null,
            'lat' => $address['address']['lat'] ?? null,
            'long' => $address['address']['long'] ?? null,
            'state_id' => $address['address']['state_id'] ?? ($address->state_id ?? null),
            'district' => $address['district'] ?? null,
        ]);
    }

    /*public function createOrderAddress($orderCreated, $address, $type = '')
    {
        $data = [];
        if ($type == 'guest_address') {
            $data = [
                'username' => $address['address']['username'] ?? null,
                'email' => $address['address']['email'] ?? null,
                'mobile' => $address['address']['mobile'],
                'address' => $address['address']['address'],
                'block' => $address['address']['block'],
                'street' => $address['address']['street'],
                'building' => $address['address']['building'],
                'state_id' => $address['address']['state_id'],
            ];
        } elseif ($type == 'selected_address') {
            $data = [
                'username' => $address['username'] ?? null,
                'email' => $address['email'] ?? null,
                'mobile' => $address['mobile'],
                'address' => $address['address'],
                'block' => $address['block'],
                'street' => $address['street'],
                'building' => $address['building'],
                'state_id' => $address['state_id'],
            ];
        }
        $orderCreated->orderAddress()->create($data);
    }*/

    public function createOrderCompanies($orderCreated, $request)
    {
        $price = getOrderShipping($request->user_id) ?? 0;

        $data = [
            'company_id' => config('setting.other.shipping_company') ?? null,
            'delivery' => floatval($price) ?? null,
        ];

        if ($request->shipping_company['availabilities']['day_code']) {
            $dayCode = $request->shipping_company['availabilities']['day_code'] ?? '';
            $availabilities = [
                'day_code' => $dayCode,
                'day' => getDayByDayCode($dayCode)['day'],
                'full_date' => getDayByDayCode($dayCode)['full_date'],
            ];

            $data['availabilities'] = \GuzzleHttp\json_encode($availabilities);
        }

        if (config('setting.other.shipping_company')) {
            $orderCreated->companies()->attach(config('setting.other.shipping_company'), $data);
        }
    }

    public function updateOrder($request)
    {
        $order = $this->findById($request['OrderID']);

        $this->updateQtyOfProduct($order, $request);

        $orderStatus = ($request['Result'] == 'CAPTURED') ? 3 : 4;
        $order->update([
            'order_status_id' => $orderStatus,
            'payment_status_id' => ($request['Result'] == 'CAPTURED') ? 2 : 3, // success | failed
            'increment_qty' => true,
        ]);

        // Add Order Status History
        OrderStatusesHistory::create([
            'order_id' => $order->id,
            'order_status_id' => $orderStatus,
            'user_id' => null,
        ]);

        $order->transactions()->updateOrCreate(
            [
                'transaction_id' => $request['OrderID']
            ],
            [
                'auth' => $request['Auth'],
                'tran_id' => $request['TranID'],
                'result' => $request['Result'],
                'post_date' => $request['PostDate'],
                'ref' => $request['Ref'],
                'track_id' => $request['TrackID'],
                'payment_id' => $request['PaymentID'],
            ]
        );

        return $request['Result'] == 'CAPTURED' ? true : false;
    }

    public function updateOrderCBK($request)
    {
        $order = $this->findById($request['MerchUdf1'] ?? null);
        if (is_null($order)) {
            logger('updateOrderCBK::order_is_not_found');
            return false;
        }

        if ($request['Status'] != '1' && $order->increment_qty != true) {
            $this->updateQtyOfProduct($order, $request);
        }

        $orderStatus = ($request['Status'] == '1') ? 7 : 4; // new_order | failed
        $order->update([
            'order_status_id' => $orderStatus,
            'payment_status_id' => ($request['Status'] == '1') ? 2 : 3, // success | failed
            'increment_qty' => true,
        ]);

        // Add Order Status History
        OrderStatusesHistory::create([
            'order_id' => $order->id,
            'order_status_id' => $orderStatus,
            'user_id' => null,
        ]);

        $order->transactions()->updateOrCreate([
            'transaction_id' => $request['MerchUdf1']
        ], [
            'auth' => $request['AuthCode'],
            'tran_id' => $request['TransactionId'],
            'result' => $request['Status'],
            'post_date' => $request['PostDate'],
            'ref' => $request['ReferenceId'],
            'track_id' => $request['TrackId'],
            'payment_id' => $request['PaymentId'],
            'pay_id' => $request['PayId'] ?? null,
        ]);

        return $request['Status'] == '1';
    }

    public function updateQtyOfProduct($order, $request)
    {
        foreach ($order->orderProducts as $value) {
            if (!is_null($value->product->qty))
                $value->product()->increment('qty', $value['qty']);

            $variant = $value->orderVariant;
            if (!is_null($variant)) {
                if (!is_null($variant->variant->qty))
                    $variant->variant()->increment('qty', $value['qty']);
            }
        }
    }
}
