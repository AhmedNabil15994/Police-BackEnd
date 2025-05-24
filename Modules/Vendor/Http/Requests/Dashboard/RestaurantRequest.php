<?php

namespace Modules\Vendor\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class RestaurantRequest extends FormRequest
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
                    'image' => 'nullable',
                    'title.*' => 'required|unique:vendor_translations,title',
                    'description.*' => 'nullable',
//                    'is_main_branch' => 'required',
                ];

            //handle updates
            case 'put':
            case 'PUT':
                return [
                    'title.*' => 'required|unique:vendor_translations,title,' . $this->id . ',vendor_id',
                    'description.*' => 'nullable',
                    'is_main_branch' => 'required',
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
            'image.required' => __('vendor::dashboard.vendors.validation.image.required'),
            'is_main_branch.required' => __('vendor::dashboard.vendors.validation.is_main_branch.required'),
        ];
        foreach (config('laravellocalization.supportedLocales') as $key => $value) {
            $v["title." . $key . ".required"] = __('vendor::dashboard.vendors.validation.title.required') . ' - ' . $value['native'] . '';
            $v["title." . $key . ".unique"] = __('vendor::dashboard.vendors.validation.title.unique') . ' - ' . $value['native'] . '';
            $v["description." . $key . ".required"] = __('vendor::dashboard.vendors.validation.description.required') . ' - ' . $value['native'] . '';
        }
        return $v;
    }
}
