<?php

namespace Modules\Area\Repositories\FrontEnd;

use Modules\Area\Entities\Country;
use Hash;
use DB;

class CountryRepository
{

    function __construct(Country $country)
    {
        $this->country   = $country;
    }

    public function getAllActive($order = 'id', $sort = 'desc')
    {
        $countrys = $this->country->active()->orderBy($order, $sort)->get();
        return $countrys;
    }

}
