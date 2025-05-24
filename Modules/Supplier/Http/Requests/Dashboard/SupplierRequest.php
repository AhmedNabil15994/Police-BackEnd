<?php

namespace Modules\Supplier\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class SupplierRequest extends FormRequest
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
                    'image' => 'required',
                ];

            //handle updates
            case 'put':
            case 'PUT':
                return [
                    'image' => 'nullable',
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
            'image.required' => __('supplier::dashboard.supplier.validation.image.required'),
        ];
        return $v;

    }
}
