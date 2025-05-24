<?php

namespace Modules\Area\Repositories\FrontEnd;

use Modules\Area\Entities\City;
use Hash;
use DB;

class CityRepository
{
    protected $city;

    function __construct(City $city)
    {
        $this->city = $city;
    }

    public function getAllActive($order = 'id', $sort = 'desc')
    {
        $citys = $this->city->with(['translations'])->with([
            'states' => function ($query) {
                $query->active();
            }
        ])->active()->orderBy($order, $sort)->get();

        return $citys;
    }

    public function getCitiesWithStates($order = 'id', $sort = 'desc')
    {
        return $this->city->active()
            ->with(['states.branches' => function ($query) {
                $query->where('vendor_delivery_charges.status', 1);
            }])
            ->orderBy($order, $sort)
            ->get();
    }

}
