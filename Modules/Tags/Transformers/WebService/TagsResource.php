<?php

namespace Modules\Tags\Transformers\WebService;

use Illuminate\Http\Resources\Json\Resource;

class TagsResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->translate(locale())->title,
        ];
    }
}
