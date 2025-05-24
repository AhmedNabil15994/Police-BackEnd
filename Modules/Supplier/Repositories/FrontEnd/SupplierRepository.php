<?php

namespace Modules\Supplier\Repositories\FrontEnd;

use Modules\Supplier\Entities\Supplier;

class SupplierRepository
{
    protected $supplier;

    function __construct(Supplier $supplier)
    {
        $this->supplier = $supplier;
    }

    public function getAllActive($order = 'id', $sort = 'desc')
    {
        return $this->supplier->active()->inRandomOrder()->get();
    }

}
