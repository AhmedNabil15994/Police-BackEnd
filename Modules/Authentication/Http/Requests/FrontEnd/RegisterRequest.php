<?php

namespace Modules\Authentication\Http\Requests\FrontEnd;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:191',
//            'country_code' => 'required|string|bail|size:2',
//            'mobile'     => ['required', 'unique:users,mobile', 'phone:'.request('country_code')],
            'mobile' => ['required', 'unique:users,mobile', 'max:8'],
            'email' => 'required|email|unique:users,email|max:191',
            'password' => 'required|confirmed|min:6|max:191',
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
            'name.required' => __('authentication::frontend.register.validation.name.required'),
            'mobile.required' => __('authentication::frontend.register.validation.mobile.required'),
            'mobile.unique' => __('authentication::frontend.register.validation.mobile.unique'),
            'mobile.phone' => __('authentication::frontend.register.validation.mobile.phone'),
            'mobile.digits_between' => __('authentication::frontend.register.validation.mobile.digits_between'),
            'mobile.max' => __('authentication::frontend.register.validation.mobile.max'),
            'email.required' => __('authentication::frontend.register.validation.email.required'),
            'email.unique' => __('authentication::frontend.register.validation.email.unique'),
            'email.email' => __('authentication::frontend.register.validation.email.email'),
            'password.required' => __('authentication::frontend.register.validation.password.required'),
            'password.min' => __('authentication::frontend.register.validation.password.min'),
            'password.confirmed' => __('authentication::frontend.register.validation.password.confirmed'),
        ];

        return $v;
    }
}
