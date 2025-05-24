<?php

namespace Modules\Vendor\ViewComposers\FrontEnd;

use Modules\Vendor\Repositories\FrontEnd\VendorRepository as Vendor;
use Modules\Vendor\Traits\VendorTrait;
use Modules\Vendor\Transformers\FrontEnd\VendorResource;
use Illuminate\View\View;
use Cache;

class VendorComposer
{
    use VendorTrait;

    public $vendors = [];
    public $branches = [];

    public function __construct(Vendor $vendor)
    {
        $this->vendors = $vendor;
//        $branch = $this->getSingleVendor();
        $this->branches = $vendor->getBranchesByRestaurantIdAndPickup(app('defaultRestaurant')->id ?? null);
    }

    /**
     * Bind data to the view.
     *
     * @param View $view
     * @return void
     */
    public function compose(View $view)
    {
        $view->with(['vendors' => $this->vendors, 'branches' => $this->branches]);
    }
}
