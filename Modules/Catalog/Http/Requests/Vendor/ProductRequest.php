<?php

namespace Modules\Catalog\Http\Requests\Vendor;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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

                $result = [
                    'title.*' => 'required',
//                    'title.*' => 'required|unique:product_translations,title',
                    'price' => 'required|numeric|min:0',
                    'qty' => 'required|integer|min:1',
                    'variation_price.*' => 'sometimes|required',
                    'variation_qty.*' => 'sometimes|required',
                    'variation_status.*' => 'sometimes|required',
                    'variation_sku.*' => 'nullable',
                    'sku' => 'nullable',
                    'category_id' => 'required',
                    'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',

                    /*'offer_price' => 'sometimes|required|numeric',
                    'start_at' => 'sometimes|required|date',
                    'end_at' => 'sometimes|required|date',

                    'v_offers.*.offer_price' => 'required|numeric',
                    'v_offers.*.start_at' => 'sometimes|required|date',
                    'v_offers.*.end_at' => 'sometimes|required|date',*/

                    'shipment.width' => 'sometimes|nullable|numeric',
                    'shipment.length' => 'sometimes|nullable|numeric',
                    'shipment.weight' => 'sometimes|nullable|numeric',
                    'shipment.height' => 'sometimes|nullable|numeric',
                    'vshipment.*.width' => 'sometimes|nullable|numeric',
                    'vshipment.*.length' => 'sometimes|nullable|numeric',
                    'vshipment.*.weight' => 'sometimes|nullable|numeric',
                    'vshipment.*.height' => 'sometimes|nullable|numeric',

                    'images' => 'nullable|array',
                    'tags' => 'nullable|array',
                ];

                if (config('setting.other.is_multi_vendors') == 1) {
                    $result['vendor_id'] = 'required';
                }

                if ($this->offer_status) {
                    $result['offer_price'] = 'required_if:offer_status,on|numeric';
                    $result['start_at'] = 'required_if:offer_status,on|date';
                    $result['end_at'] = 'required_if:offer_status,on|date';
                }

                if ($this->v_offers) {
                    $result['v_offers.*.offer_price'] = 'required_if:v_offers.*.status,on|numeric';
                    $result['v_offers.*.start_at'] = 'required_if:v_offers.*.status,on|date';
                    $result['v_offers.*.end_at'] = 'required_if:v_offers.*.status,on|date';
                }

                if (isset($this->images) && !empty($this->images)) {
                    foreach ($this->images as $k => $img) {
                        $result['images.' . $k] = 'mimes:jpeg,png,jpg,gif,svg|max:1024';
                    }
                }

                return $result;

            //handle updates
            case 'put':
            case 'PUT':
                $rules = [
                    'variation_price.*' => 'sometimes|required',
                    'variation_qty.*' => 'sometimes|required',
                    'variation_status.*' => 'sometimes|required',
                    'variation_sku.*' => 'nullable',

                    '_variation_price.*' => 'sometimes|required',
                    '_variation_qty.*' => 'sometimes|required',
                    '_variation_status.*' => 'sometimes|required',
                    '_variation_sku.*' => 'nullable',

                    '_vshipment.*.width' => 'sometimes|nullable|numeric',
                    '_vshipment.*.length' => 'sometimes|nullable|numeric',
                    '_vshipment.*.weight' => 'sometimes|nullable|numeric',
                    '_vshipment.*.height' => 'sometimes|nullable|numeric',
                    'sku' => 'nullable',
                    'category_id' => 'required',
                    'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',

                    'shipment.width' => 'sometimes|nullable|numeric',
                    'shipment.length' => 'sometimes|nullable|numeric',
                    'shipment.weight' => 'sometimes|nullable|numeric',
                    'shipment.height' => 'sometimes|nullable|numeric',
                    'vshipment.*.width' => 'sometimes|nullable|numeric',
                    'vshipment.*.length' => 'sometimes|nullable|numeric',
                    'vshipment.*.weight' => 'sometimes|nullable|numeric',
                    'vshipment.*.height' => 'sometimes|nullable|numeric',

                    'images' => 'nullable|array',
                    'tags' => 'nullable|array',
                ];

                if (config('setting.other.is_multi_vendors') == 1) {
                    $rules['vendor_id'] = 'required';
                }

                if (auth()->user()->can('edit_products_price')) {
                    $rules['price'] = 'required|numeric|min:0';

                    $rules['offer_price'] = 'required_if:offer_status,on|numeric';
                    $rules['start_at'] = 'required_if:offer_status,on|date';
                    $rules['end_at'] = 'required_if:offer_status,on|date';

                    $rules['v_offers.*.offer_price'] = 'required_if:v_offers.*.status,on|numeric';
                    $rules['v_offers.*.start_at'] = 'required_if:v_offers.*.status,on|date';
                    $rules['v_offers.*.end_at'] = 'required_if:v_offers.*.status,on|date';

                    $rules['_v_offers.*.offer_price'] = 'required_if:_v_offers.*.status,on|numeric';
                    $rules['_v_offers.*.start_at'] = 'required_if:_v_offers.*.status,on|date';
                    $rules['_v_offers.*.end_at'] = 'required_if:_v_offers.*.status,on|date';
                }

                if (auth()->user()->can('edit_products_qty')) {
                    $rules['qty'] = 'required|integer|min:1';
                }

                if (auth()->user()->can('edit_products_title')) {
                    $rules['title.*'] = 'required';
//                    $rules['title.*'] = 'required|unique:product_translations,title,' . $this->id . ',product_id';
                }

                if (isset($this->images) && !empty($this->images)) {
                    foreach ($this->images as $k => $img) {
                        $rules['images.' . $k] = 'mimes:jpeg,png,jpg,gif,svg|max:1024';
                    }
                }

                return $rules;
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
            'price.required' => __('catalog::dashboard.products.validation.price.required'),
            'vendor_id.required' => __('catalog::dashboard.products.validation.vendor_id.required'),
            'qty.required' => __('catalog::dashboard.products.validation.qty.required'),
            'qty.integer' => __('catalog::dashboard.products.validation.qty.numeric'),
            'price.numeric' => __('catalog::dashboard.products.validation.price.numeric'),
            'sku.required' => __('catalog::dashboard.products.validation.sku.required'),
            'qty.numeric' => __('catalog::dashboard.products.validation.qty.numeric'),
            'variation_price.*.required' => __('catalog::dashboard.products.validation.variation_price.required'),
            'variation_qty.*.required' => __('catalog::dashboard.products.validation.variation_qty.required'),
            'variation_status.*.required' => __('catalog::dashboard.products.validation.variation_status.required'),
            'variation_sku.*.required' => __('catalog::dashboard.products.validation.variation_sku.required'),

            'category_id.required' => __('catalog::dashboard.products.validation.category_id.required'),

            '_variation_price.*.required' => __('catalog::dashboard.products.validation.variation_price.required'),
            '_variation_qty.*.required' => __('catalog::dashboard.products.validation.variation_qty.required'),
            '_variation_status.*.required' => __('catalog::dashboard.products.validation.variation_status.required'),
            '_variation_sku.*.required' => __('catalog::dashboard.products.validation.variation_sku.required'),

            'offer_price.required_if' => __('catalog::dashboard.products.validation.offer_price.required'),
            'offer_price.numeric' => __('catalog::dashboard.products.validation.offer_price.numeric'),
            'start_at.required_if' => __('catalog::dashboard.products.validation.start_at.required'),
            'start_at.date' => __('catalog::dashboard.products.validation.start_at.date'),
            'end_at.required_if' => __('catalog::dashboard.products.validation.end_at.required'),
            'end_at.date' => __('catalog::dashboard.products.validation.end_at.date'),

            'v_offers.*.offer_price.numeric' => __('catalog::dashboard.products.validation.offer_price.numeric'),
            'v_offers.*.offer_price.required_if' => __('catalog::dashboard.products.validation.offer_price.required'),
            'v_offers.*.start_at.required_if' => __('catalog::dashboard.products.validation.start_at.required'),
            'v_offers.*.start_at.date' => __('catalog::dashboard.products.validation.start_at.date'),
            'v_offers.*.end_at.required_if' => __('catalog::dashboard.products.validation.end_at.required'),
            'v_offers.*.end_at.date' => __('catalog::dashboard.products.validation.end_at.date'),

            '_v_offers.*.offer_price.numeric' => __('catalog::dashboard.products.validation.offer_price.numeric'),
            '_v_offers.*.offer_price.required_if' => __('catalog::dashboard.products.validation.offer_price.required'),
            '_v_offers.*.start_at.required_if' => __('catalog::dashboard.products.validation.start_at.required'),
            '_v_offers.*.start_at.date' => __('catalog::dashboard.products.validation.start_at.date'),
            '_v_offers.*.end_at.required_if' => __('catalog::dashboard.products.validation.end_at.required'),
            '_v_offers.*.end_at.date' => __('catalog::dashboard.products.validation.end_at.date'),


            'shipment.width.required' => __('catalog::dashboard.products.validation.width.required'),
            'shipment.width.numeric' => __('catalog::dashboard.products.validation.width.numeric'),
            'shipment.length.required' => __('catalog::dashboard.products.validation.length.required'),
            'shipment.length.numeric' => __('catalog::dashboard.products.validation.length.numeric'),
            'shipment.weight.required' => __('catalog::dashboard.products.validation.weight.required'),
            'shipment.weight.numeric' => __('catalog::dashboard.products.validation.weight.numeric'),
            'shipment.height.required' => __('catalog::dashboard.products.validation.height.required'),
            'shipment.height.numeric' => __('catalog::dashboard.products.validation.height.numeric'),
            'vshipment.*.width.required' => __('catalog::dashboard.products.validation.width.required'),
            'vshipment.*.width.numeric' => __('catalog::dashboard.products.validation.width.numeric'),
            'vshipment.*.length.required' => __('catalog::dashboard.products.validation.length.required'),
            'vshipment.*.length.numeric' => __('catalog::dashboard.products.validation.length.numeric'),
            'vshipment.*.weight.required' => __('catalog::dashboard.products.validation.weight.required'),
            'vshipment.*.weight.numeric' => __('catalog::dashboard.products.validation.weight.numeric'),
            'vshipment.*.height.required' => __('catalog::dashboard.products.validation.height.required'),
            'vshipment.*.height.numeric' => __('catalog::dashboard.products.validation.height.numeric'),

            '_vshipment.*.width.required' => __('catalog::dashboard.products.validation.width.required'),
            '_vshipment.*.width.numeric' => __('catalog::dashboard.products.validation.width.numeric'),
            '_vshipment.*.length.required' => __('catalog::dashboard.products.validation.length.required'),
            '_vshipment.*.length.numeric' => __('catalog::dashboard.products.validation.length.numeric'),
            '_vshipment.*.weight.required' => __('catalog::dashboard.products.validation.weight.required'),
            '_vshipment.*.weight.numeric' => __('catalog::dashboard.products.validation.weight.numeric'),
            '_vshipment.*.height.required' => __('catalog::dashboard.products.validation.height.required'),
            '_vshipment.*.height.numeric' => __('catalog::dashboard.products.validation.height.numeric'),

            'image.required' => __('catalog::dashboard.products.validation.image.required'),
            'image.image' => __('catalog::dashboard.products.validation.image.image'),
            'image.mimes' => __('catalog::dashboard.products.validation.image.mimes'),
            'image.max' => __('catalog::dashboard.products.validation.image.max'),
        ];

        foreach (config('laravellocalization.supportedLocales') as $key => $value) {
            $v['title.' . $key . '.required'] = __('catalog::dashboard.products.validation.title.required') . ' - ' . $value['native'] . '';
            $v["title." . $key . ".unique"] = __('catalog::dashboard.products.validation.title.unique') . ' - ' . $value['native'] . '';
        }
        return $v;
    }
}
