<?php

namespace Modules\Cart\Http\Requests\WebService;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Catalog\Entities\ProductAddon;

class CreateOrUpdateCartRequest extends FormRequest
{
    public function rules()
    {
        $rules = [
            'user_token' => 'required',
            'state_id' => 'nullable|exists:states,id',
            'branch_id' => 'nullable|exists:vendors,id',
            'pickup_delivery_type' => 'required|in:delivery,pickup',
        ];
        return $rules;
    }

    public function authorize()
    {
        return true;
    }

    public function messages()
    {
        $messages = [
            'user_token.required' => __('apps::frontend.general.user_token_not_found'),
            'state_id.required' => __('cart::api.validations.state_id.required'),
            'state_id.exists' => __('cart::api.validations.state_id.exists'),
            'branch_id.required' => __('cart::api.validations.branch_id.required'),
            'branch_id.exists' => __('cart::api.validations.branch_id.exists'),
            'pickup_delivery_type.required' => __('cart::api.validations.pickup_delivery_type.required'),
            'pickup_delivery_type.in' => __('cart::api.validations.pickup_delivery_type.in') . 'delivery, pickup',
        ];
        return $messages;
    }

    public function withValidator($validator)
    {
        if (auth('api')->check())
            $userToken = auth('api')->user()->id;
        else
            $userToken = $this->user_token ?? null;

        $validator->after(function ($validator) use ($userToken) {

            if (isset($this->addonsOptions) && !empty($this->addonsOptions) && $this->product_type == 'product') {

                foreach ($this->addonsOptions as $k => $value) {

                    $addOns = ProductAddon::where('product_id', $this->product_id)->where('addon_category_id', $value['id'])->first();
                    if (!$addOns) {
                        return $validator->errors()->add(
                            'addons', __('cart::api.validations.addons.addons_not_found') . ' - ' . __('cart::api.validations.addons.addons_number') . ': ' . $value['id']
                        );
                    }

                    $optionsIds = $addOns->addonOptions ? $addOns->addonOptions->pluck('addon_option_id')->toArray() : [];
                    if ($addOns->type == 'single' && !in_array($value['options'][0], $optionsIds)) {
                        return $validator->errors()->add(
                            'addons', __('cart::api.validations.addons.option_not_found') . ' - ' . __('cart::api.validations.addons.addons_number') . ': ' . $value['options'][0]
                        );
                    }

                    if ($addOns->type == 'multi') {
                        if ($addOns->max_options_count != null && count($value['options']) > intval($addOns->max_options_count)) {
                            return $validator->errors()->add(
                                'addons', __('cart::api.validations.addons.selected_options_greater_than_options_count') . ': ' . $addOns->addonCategory->getTranslation('title', locale())
                            );
                        }

                        if ($addOns->min_options_count != null && count($value['options']) < intval($addOns->min_options_count)) {
                            return $validator->errors()->add(
                                'addons', __('cart::api.validations.addons.selected_options_less_than_options_count') . ': ' . $addOns->addonCategory->getTranslation('title', locale())
                            );
                        }

                        if (isset($value['options']) && count($value['options']) > 0) {
                            foreach ($value['options'] as $i => $item) {
                                if (!in_array($item, $optionsIds)) {
                                    return $validator->errors()->add(
                                        'addons', __('cart::api.validations.addons.option_not_found') . ' - ' . __('cart::api.validations.addons.addons_number') . ': ' . $item
                                    );
                                }
                            }
                        }
                    }
                }
            }

            // if new product - check state_id & branch_id
            if (is_null(getCartItemById($this->product_id, $userToken))) {
                if (empty($this->state_id) && $this->pickup_delivery_type == 'delivery')
                    return $validator->errors()->add(
                        'state_id', __('cart::api.validations.state_id.required')
                    );

                if (empty($this->branch_id))
                    return $validator->errors()->add(
                        'branch_id', __('cart::api.validations.state_id.required')
                    );
            }

        });
        return true;
    }

}
