<?php

namespace Modules\Area\Repositories\FrontEnd;

use Modules\Area\Entities\State;
use Hash;
use DB;

class StateRepository
{
    protected $state;

    function __construct(State $state)
    {
        $this->state = $state;
    }

    public function getAll($order = 'id', $sort = 'desc')
    {
        $states = $this->state->orderBy($order, $sort)->get();
        return $states;
    }

    public function getAllActive($order = 'id', $sort = 'desc')
    {
        return $this->state->has('deliveryCharge')->with('deliveryCharge')->active()->orderBy($order, $sort)->get();
    }

    public function getAllActiveStates($order = 'id', $sort = 'desc')
    {
        $states = $this->state->with(['deliveryCharge' => function ($q) {
            $q->active();
        }])->whereHas('deliveryCharge', function ($q) {
            $q->active();
        })->active()->orderBy($order, $sort)->get();
        return $states;
    }

    public function getAllByCityId($cityId)
    {
        $states = $this->state->where('city_id', $cityId)->get();
        return $states;
    }

    public function findBySlug($slug)
    {
        $state = $this->state->whereTranslation('slug', $slug)->first();
        return $state;
    }

    public function findById($id)
    {
        return $this->state->find($id);
    }

    public function checkRouteLocale($model, $slug)
    {
        if ($model->translate()->where('slug', $slug)->first()->locale != locale())
            return false;

        return true;
    }
}
