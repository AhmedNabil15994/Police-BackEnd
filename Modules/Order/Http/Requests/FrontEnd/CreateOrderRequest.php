<?php

namespace Modules\Order\Http\Requests\FrontEnd;

use Illuminate\Foundation\Http\FormRequest;
use Cart;
use Modules\Catalog\Traits\FrontEnd\CatalogTrait;
use Modules\Vendor\Entities\VendorDeliveryCharge;

class CreateOrderRequest extends FormRequest
{
    use CatalogTrait;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if (optional(checkPickupDeliveryCookie())->type == 'delivery') {
            $rules = [
                'state_id' => 'nullable',
                'username' => 'required|string',
                'mobile' => 'required|string',
                'block' => 'required|string',
                'building' => 'required|string',
                'street' => 'required|string',
                'address' => 'nullable|string',
            ];
        } else {
            $rules = [
                'state_id' => 'nullable',
                'username' => 'required|string',
                'mobile' => 'required|string',
            ];
        }

        $rules['payment'] = 'required|in:cash,online';
        $rules['shipping_company.id'] = 'nullable';
        $rules['shipping_company.day'] = 'nullable';

        return $rules;
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    public function messages()
    {
        $v = [
            'address_type.required' => __('catalog::frontend.checkout.address.validation.address_type.required'),
            'address_type.in' => __('catalog::frontend.checkout.address.validation.address_type.in'),
            'receiver_name.required' => __('catalog::frontend.checkout.address.validation.receiver_name.required'),
            'receiver_name.max' => __('catalog::frontend.checkout.address.validation.receiver_name.max'),
            'receiver_mobile.required' => __('catalog::frontend.checkout.address.validation.receiver_mobile.required'),
            'receiver_mobile.max' => __('catalog::frontend.checkout.address.validation.receiver_mobile.max'),

            'selected_address_id.required' => __('catalog::frontend.checkout.address.validation.selected_address_id.required'),

            'state_id.required' => __('user::frontend.addresses.validations.state_id.required'),
            'state_id.numeric' => __('user::frontend.addresses.validations.state_id.numeric'),
            'mobile.required' => __('user::frontend.addresses.validations.mobile.required'),
            'mobile.numeric' => __('user::frontend.addresses.validations.mobile.numeric'),
            'mobile.digits_between' => __('user::frontend.addresses.validations.mobile.digits_between'),
            'mobile.min' => __('user::frontend.addresses.validations.mobile.min'),
            'mobile.max' => __('user::frontend.addresses.validations.mobile.max'),
            'address.required' => __('user::frontend.addresses.validations.address.required'),
            'address.string' => __('user::frontend.addresses.validations.address.string'),
            'address.min' => __('user::frontend.addresses.validations.address.min'),
            'block.required' => __('user::frontend.addresses.validations.block.required'),
            'block.string' => __('user::frontend.addresses.validations.block.string'),
            'street.required' => __('user::frontend.addresses.validations.street.required'),
            'street.string' => __('user::frontend.addresses.validations.street.string'),
            'building.required' => __('user::frontend.addresses.validations.building.required'),
            'building.string' => __('user::frontend.addresses.validations.building.string'),

            'username.required' => __('user::frontend.addresses.validations.username.required'),
            'username.string' => __('user::frontend.addresses.validations.username.string'),

            'payment.required' => __('order::frontend.orders.validations.payment.required'),
            'payment.in' => __('order::frontend.orders.validations.payment.in'),

            'shipping_company.id.required' => __('catalog::frontend.checkout.validation.vendor_company.required'),
            'shipping_company.day.required' => __('catalog::frontend.checkout.validation.vendor_company_day.required'),

        ];

        /*if (count($this->vendors_ids) > 0) {
            foreach ($this->vendors_ids as $k => $vendorId) {
                $v['vendor_company.' . $vendorId . '.required'] = __('catalog::frontend.checkout.validation.vendor_company.required');
            }
            foreach ($this->vendor_company as $vendorId => $companyId) {
                $v['vendor_company_day.' . $vendorId . '.' . $companyId . '.required'] = __('catalog::frontend.checkout.validation.vendor_company_day.required');
            }
        }*/

        return $v;
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {

            if (optional(checkPickupDeliveryCookie())->type == 'delivery') {

                $companyDeliveryFees = getCartConditionByName(null, 'company_delivery_fees');
                if (is_null($companyDeliveryFees)) {
                    return $validator->errors()->add(
                        'company_delivery_fees', __('order::api.orders.validations.company_delivery_fees.required')
                    );
                }

                $delivery = VendorDeliveryCharge::where('state_id', $companyDeliveryFees->getAttributes()['state_id'])->where('vendor_id', $companyDeliveryFees->getAttributes()['branch_id'])->first();
                if (!$delivery) {
                    return $validator->errors()->add(
                        'delivery_charge', __('order::api.orders.validations.delivery_charge.not_found')
                    );
                } else {
                    if (!is_null($delivery->min_order_amount) && getCartTotal() < floatval($delivery->min_order_amount)) {
                        return $validator->errors()->add(
                            'min_order_amount', __('order::api.orders.validations.min_order_amount_greater_than_cart_total') . ': ' . number_format($delivery->min_order_amount, 3)
                        );
                    }

                    // check if delivery value is changed
                    if (floatval($delivery->delivery) != floatval($companyDeliveryFees->getValue())) {

                        $request = new \stdClass;
                        $userToken = $this->getUserCartToken();
                        $request->state_id = $companyDeliveryFees->getAttributes()['state_id'];
                        $request->vendor_id = $companyDeliveryFees->getAttributes()['branch_id'];
                        $this->saveAndRemoveDeliveryCharge($request, $userToken);

                    }
                }

            }

            if (!is_null($this->payment) && !in_array($this->payment, config('setting.other.supported_payments') ?? [])) {
                return $validator->errors()->add(
                    'payment', __('order::frontend.orders.index.alerts.payment_not_supported_now')
                );
            }

        });

        return true;
    }
}
