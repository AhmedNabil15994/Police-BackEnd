<?php

namespace Modules\Vendor\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class VendorRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch ($this->getMethod()) {
            // handle creates
            case 'post':
            case 'POST':

                return [
                    'restaurant_id' => 'required',
                    'seller_id' => 'nullable',
                    'image' => 'nullable',
                    'commission' => 'nullable|numeric',
                    'order_limit' => 'nullable|numeric',
                    'fixed_delivery' => 'nullable|numeric',
//                  'title.*'         => 'required',
                    'title.*' => 'required|unique:vendor_translations,title',
                    'description.*' => 'nullable',
                    'companies' => 'nullable|array|exists:companies,id',
                    'states' => 'nullable|array|exists:states,id',
                ];

            //handle updates
            case 'put':
            case 'PUT':
                return [
                    'restaurant_id' => 'required',
                    'seller_id' => 'nullable',
                    'commission' => 'nullable|numeric',
                    'order_limit' => 'nullable|numeric',
                    'fixed_delivery' => 'nullable|numeric',
//                    'title.*'          => 'required',
                    'title.*' => 'required|unique:vendor_translations,title,' . $this->id . ',vendor_id',
                    'description.*' => 'nullable',
                    'companies' => 'nullable|array|exists:companies,id',
                    'states' => 'nullable|array|exists:states,id',
                ];
        }
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
            'restaurant_id.required' => __('vendor::dashboard.vendors.validation.restaurant_id.required'),
            'fixed_delivery.required' => __('vendor::dashboard.vendors.validation.fixed_delivery.required'),
            'fixed_delivery.numeric' => __('vendor::dashboard.vendors.validation.fixed_delivery.numeric'),
            'order_limit.required' => __('vendor::dashboard.vendors.validation.order_limit.required'),
            'order_limit.numeric' => __('vendor::dashboard.vendors.validation.order_limit.numeric'),
            'commission.required' => __('vendor::dashboard.vendors.validation.commission.required'),
            'commission.numeric' => __('vendor::dashboard.vendors.validation.commission.numeric'),
            'payment_id.required' => __('vendor::dashboard.vendors.validation.payments.required'),
            'seller_id.required' => __('vendor::dashboard.vendors.validation.sellers.required'),
            'image.required' => __('vendor::dashboard.vendors.validation.image.required'),
        ];
        foreach (config('laravellocalization.supportedLocales') as $key => $value) {
            $v["title." . $key . ".required"] = __('vendor::dashboard.vendors.validation.title.required') . ' - ' . $value['native'] . '';
            $v["title." . $key . ".unique"] = __('vendor::dashboard.vendors.validation.title.unique') . ' - ' . $value['native'] . '';
            $v["description." . $key . ".required"] = __('vendor::dashboard.vendors.validation.description.required') . ' - ' . $value['native'] . '';
        }
        return $v;
    }
}
