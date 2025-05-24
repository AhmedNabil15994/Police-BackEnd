<?php

namespace Modules\Catalog\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
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
                    'category_id' => 'nullable',
                    'title.*' => 'required|unique:category_translations,title',
                    'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
//                    'color' => 'required_if:category_id,==,null',
                ];

            //handle updates
            case 'put':
            case 'PUT':
                return [
                    'category_id' => 'nullable',
                    'title.*' => 'required|unique:category_translations,title,' . $this->id . ',category_id',
                    'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
//                    'color' => 'required_if:category_id,==,null',
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
            'category_id.required' => __('catalog::dashboard.categories.validation.category_id.required'),
            'color.required_if' => __('catalog::dashboard.categories.validation.color.required_if'),

            'image.required' => __('catalog::dashboard.categories.validation.image.required'),
            'image.image' => __('catalog::dashboard.categories.validation.image.image'),
            'image.mimes' => __('catalog::dashboard.categories.validation.image.mimes'),
            'image.max' => __('catalog::dashboard.categories.validation.image.max'),
        ];

        foreach (config('laravellocalization.supportedLocales') as $key => $value) {
            $v["title." . $key . ".required"] = __('catalog::dashboard.categories.validation.title.required') . ' - ' . $value['native'] . '';
            $v["title." . $key . ".unique"] = __('catalog::dashboard.categories.validation.title.unique') . ' - ' . $value['native'] . '';
        }
        return $v;

    }
}
