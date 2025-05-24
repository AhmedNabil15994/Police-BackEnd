<?php

namespace Modules\Supplier\ViewComposers\FrontEnd;

use Modules\Supplier\Repositories\FrontEnd\SupplierRepository as Supplier;
use Illuminate\View\View;
use Cache;

class SupplierComposer
{
    public $supplier = [];

    public function __construct(Supplier $supplier)
    {
        $this->supplier = $supplier->getAllActive();
    }

    /**
     * Bind data to the view.
     *
     * @param View $view
     * @return void
     */
    public function compose(View $view)
    {
        $view->with('supplier', $this->supplier);
    }
}
