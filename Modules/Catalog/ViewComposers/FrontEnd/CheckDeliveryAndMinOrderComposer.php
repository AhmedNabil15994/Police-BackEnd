<?php

namespace Modules\Catalog\ViewComposers\FrontEnd;

use Illuminate\View\View;
use Modules\Catalog\Traits\FrontEnd\CatalogTrait;

class CheckDeliveryAndMinOrderComposer
{
    use CatalogTrait;

    protected $minOrderAmount;

    public function __construct()
    {
        $refreshResult = $this->checkDeliveryAndMinOrderOnRefresh();
        $minOrder = count($refreshResult) > 0 ? $refreshResult['new_min_order_amount'] : null;
        $this->minOrderAmount = !empty($minOrder) && $minOrder > 0 ? $minOrder . ' ' . __('apps::frontend.master.kwd') : __('apps::frontend.master.min_order_amount_un_limited');
    }

    public function compose(View $view)
    {
        $view->with([
            'min_order_amount' => $this->minOrderAmount,
        ]);
    }
}
