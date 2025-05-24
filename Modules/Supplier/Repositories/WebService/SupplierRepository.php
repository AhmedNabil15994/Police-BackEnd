<?php

namespace Modules\Supplier\Repositories\WebService;

use Modules\Supplier\Entities\Supplier;

class SupplierRepository
{
    protected $supplier;

    function __construct(Supplier $supplier)
    {
        $this->supplier = $supplier;
    }

    public function getRandomPerRequest()
    {
        return $this->supplier->active()->inRandomOrder()->get();
    }
}
