<?php

namespace Modules\Slider\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class SliderRequest extends FormRequest
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
                    'link' => 'required',
                    'start_at' => 'nullable|date',
                    'end_at' => 'nullable|date',
                    'title.*' => 'nullable',
                    'short_description.*' => 'nullable',
                ];

            //handle updates
            case 'put':
            case 'PUT':
                return [
                    'image' => 'nullable',
                    'link' => 'required',
                    'start_at' => 'nullable|date',
                    'end_at' => 'nullable|date',
                    'title.*' => 'nullable',
                    'short_description.*' => 'nullable',
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
            'image.required' => __('slider::dashboard.slider.validation.image.required'),
            'link.required' => __('slider::dashboard.slider.validation.link.required'),
            'start_at.required' => __('slider::dashboard.slider.validation.start_at.required'),
            'start_at.date' => __('slider::dashboard.slider.validation.start_at.date'),
            'end_at.required' => __('slider::dashboard.slider.validation.end_at.required'),
            'end_at.date' => __('slider::dashboard.slider.validation.end_at.date'),
        ];
        foreach (config('laravellocalization.supportedLocales') as $key => $value) {
            $v['title.' . $key . '.required'] = __('slider::dashboard.slider.validation.title.required') . ' - ' . $value['native'] . '';
        }
        return $v;
    }
}
