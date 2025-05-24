<?php

namespace Modules\DriverApp\Http\Requests\WebService;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Vendor\Entities\VendorDeliveryCharge;

class TrackOrderRequest extends FormRequest
{
    public function rules()
    {
        return [
            'longitude' => 'required',
            'latitude' => 'required',
        ];
    }

    public function authorize()
    {
        return true;
    }
}
