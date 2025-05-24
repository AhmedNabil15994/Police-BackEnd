<?php

namespace Modules\User\Transformers\WebService;

use Illuminate\Http\Resources\Json\Resource;
use Modules\Area\Traits\AreaTrait;

class UserResource extends Resource
{
    use AreaTrait;

    public function toArray($request)
    {
        $result = [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'calling_code' => $this->calling_code,
            'mobile' => $this->mobile,
            'image' => url($this->image),
        ];

        if (!is_null($this->country)) {
            $result['country'] = $this->getCountryInfoByCode($this->country);
        } else {
            $result['country'] = null;
        }

        return $result;
    }
}
