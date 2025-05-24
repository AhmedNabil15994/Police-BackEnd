<?php

namespace Modules\User\Transformers\WebService;

use Illuminate\Http\Resources\Json\Resource;

class UserAddressResource extends Resource
{
    public function toArray($request)
    {
        $result = [
            'id' => $this->id,
//            'civil_id' => intval($this->civil_id),
//            'civil_id' => $this->civil_id,
            'email' => $this->email,
            'username' => $this->username,
            'mobile' => $this->mobile,
            'block' => $this->block,
            'street' => $this->street,
            'building' => $this->building,
            'state' => $this->state->translate(locale())->title,
            'state_id' => intval($this->state_id),
            'additions' => $this->address,
            'district' => $this->district,
        ];

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

        return $result;
    }
}
