<?php

namespace Modules\Supplier\Http\Controllers\WebService;

use Illuminate\Http\Request;
use Modules\Supplier\Transformers\WebService\SupplierResource;
use Modules\Supplier\Repositories\WebService\SupplierRepository as Supplier;
use Modules\Apps\Http\Controllers\WebService\WebServiceController;

class SupplierController extends WebServiceController
{
    protected $supplier;

    function __construct(Supplier $supplier)
    {
        $this->supplier = $supplier;
    }

    public function index()
    {
        $supplier = $this->supplier->getRandomPerRequest();
        return $this->response(SupplierResource::collection($supplier));
    }

}
