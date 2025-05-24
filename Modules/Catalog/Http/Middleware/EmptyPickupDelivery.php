<?php

namespace Modules\Catalog\Http\Middleware;

use Closure;

class EmptyPickupDelivery
{
    public function handle($request, Closure $next)
    {
        if (is_null(checkPickupDeliveryCookie())) {
            return redirect()->route('frontend.categories.products')->withErrors(['pickup_delivery_error' => __('catalog::frontend.products.alerts.choose_state_or_branch')]);
        } else {
            if (checkPickupDeliveryCookie()->type == 'pickup' && is_null(optional(optional(checkPickupDeliveryCookie())->content)->vendor_id)) {
                return redirect()->route('frontend.categories.products')->withErrors(['choose_pickup_branch' => __('catalog::frontend.products.alerts.choose_pickup_branch')]);
            }

            if (checkPickupDeliveryCookie()->type == 'delivery' && is_null(optional(optional(checkPickupDeliveryCookie())->content)->state_id)) {
                return redirect()->route('frontend.categories.products')->withErrors(['choose_delivery_state' => __('catalog::frontend.products.alerts.choose_delivery_state')]);
            }
        }
        return $next($request);
    }
}
