<?php

namespace Modules\Vendor\ViewComposers\Dashboard;

use Modules\Vendor\Repositories\Dashboard\RestaurantRepository as Restaurant;
use Illuminate\View\View;

class RestaurantComposer
{
    public $restaurant = [];

    public function __construct(Restaurant $restaurant)
    {
        $this->restaurant = $restaurant->getAllActive();
    }

    /**
     * Bind data to the view.
     *
     * @param View $view
     * @return void
     */
    public function compose(View $view)
    {
        $view->with(['restaurants' => $this->restaurant]);
    }
}
