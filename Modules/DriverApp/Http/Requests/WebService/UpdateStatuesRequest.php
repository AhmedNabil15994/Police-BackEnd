<?php

namespace Modules\DriverApp\Http\Requests\WebService;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStatuesRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'vendor_status_id'     => 'required|exists:vendor_statuses,id',

        ];
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
            'email.required'      =>   __('authentication::api.login.validation.email.required'),
            'email.email'         =>   __('authentication::api.login.validation.email.email'),
            'password.required'   =>   __('authentication::api.login.validation.password.required'),
            'password.min'        =>   __('authentication::api.login.validation.password.min'),
        ];
        return $v;
    }
}
