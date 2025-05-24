<?php

namespace Modules\Advertising\Transformers\WebService;

use Illuminate\Http\Resources\Json\JsonResource;

class AdvertisingResource extends JsonResource
{
    public function toArray($request)
    {
        $result = [
            'id' => $this->id,
            'sort' => $this->sort,
        ];

        if (locale() == 'ar')
            $result['image'] = !is_null($this->image_ar) ? url($this->image_ar) : null;
        else
            $result['image'] = !is_null($this->image_en) ? url($this->image_en) : null;

        if (is_null($this->advertable_id) && !is_null($this->link)) {
            $result['target'] = 'external';
            $result['link'] = $this->link;
        } elseif (!is_null($this->advertable_id) && $this->morph_model == 'Product') {
            $result['target'] = 'product';
            $result['link'] = $this->advertable_id;
        } elseif (!is_null($this->advertable_id) && $this->morph_model == 'Category') {
            $result['target'] = 'category';
            $result['link'] = $this->advertable_id;
        }

        return $result;
    }
}
