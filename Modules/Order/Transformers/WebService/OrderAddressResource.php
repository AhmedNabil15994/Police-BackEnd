<?php

namespace Modules\Order\Transformers\WebService;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderAddressResource extends JsonResource
{
    public function toArray($request)
    {
        $result = [
            'id' => $this->id,
            'email' => $this->email,
            'mobile' => $this->mobile,
            'username' => $this->username,
            'state_id' => $this->state_id,
            'state' => optional(optional($this->state)->translate(locale()))->title,
            'block' => $this->block,
            'building' => $this->building,
            'street' => $this->street,
            'additions' => $this->address,
            'district' => $this->district,
            'lat' => $this->lat,
            'long' => $this->long,
        ];

        if (!is_null($this->state)) {
            if (is_null($this->state->city)) {
                $result['city'] = null;
            } else {
                $result['city'] = [
                    'id' => $this->state->city->id,
                    'title' => $this->state->city->translate(locale())->title,
                ];
            }

            if (is_null($this->state->city) || is_null($this->state->city->country)) {
                $result['country'] = null;
            } else {
                $result['country'] = [
                    'id' => $this->state->city->country->id,
                    'title' => $this->state->city->country->translate(locale())->title,
                ];
            }
        } else {
            $result['city'] = null;
            $result['country'] = null;
        }

        return $result;
    }
}
