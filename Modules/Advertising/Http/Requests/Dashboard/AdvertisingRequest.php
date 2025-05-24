<?php

namespace Modules\Advertising\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class AdvertisingRequest extends FormRequest
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
                $rules = [
                    'link_type' => 'nullable|in:external,product,category',
                    'link' => 'required_if:link_type,==,external',
                    // 'image' => 'required',
                    'image_ar' => 'required|image|mimes:' . config('core.config.image_mimes') . '|max:' . config('core.config.image_max'),
                    'image_en' => 'required|image|mimes:' . config('core.config.image_mimes') . '|max:' . config('core.config.image_max'),
                    'group_id' => 'required|exists:advertising_groups,id',
                    'start_at' => 'nullable',
                    'end_at' => 'nullable',
                ];

                if ($this->link_type == 'product')
                    $rules['product_id'] = 'required|exists:products,id';

                if ($this->link_type == 'category')
                    $rules['category_id'] = 'required|exists:categories,id';

                return $rules;

            //handle updates
            case 'put':
            case 'PUT':
                $rules = [
                    'link_type' => 'nullable|in:external,product,category',
                    'link' => 'required_if:link_type,==,external',
                    'group_id' => 'required|exists:advertising_groups,id',
                    'start_at' => 'nullable',
                    'end_at' => 'nullable',
                    'image_ar' => 'nullable|image|mimes:' . config('core.config.image_mimes') . '|max:' . config('core.config.image_max'),
                    'image_en' => 'nullable|image|mimes:' . config('core.config.image_mimes') . '|max:' . config('core.config.image_max'),
                ];

                if ($this->link_type == 'product')
                    $rules['product_id'] = 'required|exists:products,id';

                if ($this->link_type == 'category')
                    $rules['category_id'] = 'required|exists:categories,id';

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
            'link_type.required' => __('advertising::dashboard.advertising.validation.link_type.required'),
            'link_type.in' => __('advertising::dashboard.advertising.validation.link_type.in'),
            'link.required_if' => __('advertising::dashboard.advertising.validation.link.required_if'),
            'product_id.required' => __('advertising::dashboard.advertising.validation.product_id.required'),
            'product_id.exists' => __('advertising::dashboard.advertising.validation.product_id.exists'),
            'category_id.required' => __('advertising::dashboard.advertising.validation.category_id.required'),
            'category_id.exists' => __('advertising::dashboard.advertising.validation.category_id.exists'),
            // 'image.required' => __('advertising::dashboard.advertising.validation.image.required'),
            'group_id.required' => __('advertising::dashboard.advertising.validation.group_id.required'),
            'group_id.exists' => __('advertising::dashboard.advertising.validation.group_id.exists'),
            'start_at.required' => __('advertising::dashboard.advertising.validation.start_at.required'),
            'end_at.required' => __('advertising::dashboard.advertising.validation.end_at.required'),

            'image_ar.required' => __('advertising::dashboard.advertising.validation.image_ar.required'),
            'image_ar.image' => __('advertising::dashboard.advertising.validation.image_ar.image'),
            'image_ar.mimes' => __('advertising::dashboard.advertising.validation.image_ar.mimes') . ': ' . config('core.config.image_mimes'),
            'image_ar.max' => __('advertising::dashboard.advertising.validation.image_ar.max') . ': ' . config('core.config.image_max'),

            'image_en.required' => __('advertising::dashboard.advertising.validation.image_en.required'),
            'image_en.image' => __('advertising::dashboard.advertising.validation.image_en.image'),
            'image_en.mimes' => __('advertising::dashboard.advertising.validation.image_en.mimes') . ': ' . config('core.config.image_mimes'),
            'image_en.max' => __('advertising::dashboard.advertising.validation.image_en.max') . ': ' . config('core.config.image_max'),
        ];

        return $v;

    }
}
