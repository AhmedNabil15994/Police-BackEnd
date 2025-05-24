<?php

namespace Modules\Slider\Repositories\FrontEnd;

use Modules\Slider\Entities\Slider;

class SliderRepository
{
    protected $slider;

    function __construct(Slider $slider)
    {
        $this->slider = $slider;
    }

    public function getAllActive($order = 'id', $sort = 'desc')
    {
        return $this->slider->active()->startedAndExpired()->inRandomOrder()->get();
//        return $this->slider->active()->unexpired()->started()->inRandomOrder()->get();
    }

}
