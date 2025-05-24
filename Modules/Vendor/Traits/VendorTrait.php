<?php

namespace Modules\Vendor\Traits;

use Illuminate\Support\MessageBag;
use Modules\Vendor\Entities\Rate;
use Modules\Vendor\Entities\Vendor;

trait VendorTrait
{
    public function getVendorTotalRate($modelRelation)
    {
        $rateCount = $modelRelation->count();
        $rateSum = $modelRelation->sum('rating');
        $totalRate = floatval($rateCount) != 0 ? floatval($rateSum) / floatval($rateCount) : 0;
        return $totalRate;
    }

    public function getVendorRatesCount($modelRelation)
    {
        $rateCount = $modelRelation->count();
        return $rateCount;
    }

    public function checkUserRateOrder($id)
    {
        $rate = Rate::where('user_id', auth()->id())
            ->where('order_id', $id)
            ->first();
        return $rate ? true : false;
    }

    public function getOrderRate($id)
    {
        $rate = Rate::where('order_id', $id)->value('rating');
        return $rate ? $rate : 0;
    }

    public function getBranchesByRestaurantId($restaurantId, $order = 'id', $sort = 'desc')
    {
        return Vendor::where('parent_id', $restaurantId)->orderBy($order, $sort)->get();
    }

    public function defaultVendorCondition($model, $vendorId)
    {
        return $model->whereHas('productVendors', function ($query) use ($vendorId) {
            $query->where('vendor_products.vendor_id', $vendorId);
        });
    }

    public function getSingleVendor()
    {
//        return app('vendorObject');
        return app('vendorObject') ? (Vendor::where('parent_id', app('vendorObject')->id)->where('is_main_branch', 1)->first() ?? Vendor::where('parent_id', app('vendorObject')->id)->first()) : null;
    }
}
