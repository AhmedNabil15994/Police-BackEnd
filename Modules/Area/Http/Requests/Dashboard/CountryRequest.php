<?php

namespace Modules\Area\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class CountryRequest extends FormRequest
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
                    'code' => 'required|string|unique:countries,code',
                    'title.*' => 'required',
                    'title.*' => 'required|unique:country_translations,title',
                ];

            //handle updates
            case 'put':
            case 'PUT':
                return [
                    'code' => 'required|string|unique:countries,code,' . $this->id,
                    'title.*' => 'required',
                    'title.*' => 'required|unique:country_translations,title,' . $this->id . ',country_id',
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
        $v["code.required"] = __('area::dashboard.countries.validation.code.required');
        $v["code.string"] = __('area::dashboard.countries.validation.code.string');
        $v["code.unique"] = __('area::dashboard.countries.validation.code.unique');

        foreach (config('laravellocalization.supportedLocales') as $key => $value) {

            $v["title." . $key . ".required"] = __('area::dashboard.countries.validation.title.required') . ' - ' . $value['native'] . '';
            $v["title." . $key . ".unique"] = __('area::dashboard.countries.validation.title.unique') . ' - ' . $value['native'] . '';

        }

        return $v;

    }
}
