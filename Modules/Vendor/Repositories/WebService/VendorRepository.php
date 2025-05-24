<?php

namespace Modules\Vendor\Repositories\WebService;

use Modules\Vendor\Entities\Vendor;
use Modules\Vendor\Entities\Section;
use Modules\Vendor\Entities\DeliveryCharge;

class VendorRepository
{
    function __construct(Vendor $vendor, Section $section/*, DeliveryCharge $charge*/)
    {
        $this->section = $section;
        $this->vendor = $vendor;
//        $this->charge = $charge;
    }

    public function getAllSections()
    {
        $sections = $this->section->with([
            'vendors' => function ($query) {
                $query->active()->with([
                    'deliveryCharge' => function ($query) {
                        $query->where('state_id', '');
                    }
                ]);

                $query->when(config('setting.other.enable_subscriptions') == 1, function ($q) {
                    return $q->whereHas('subbscription', function ($query) {
                        $query->active()->unexpired()->started();
                    });
                });

                $query->inRandomOrder();
            },
        ]);

        $sections = $sections->whereHas('vendors', function ($query) {
            $query->active();
            $query->when(config('setting.other.enable_subscriptions') == 1, function ($q) {
                return $q->whereHas('subbscription', function ($query) {
                    $query->active()->unexpired()->started();
                });
            });
        })->active()->inRandomOrder()->take(10)->get();
        return $sections;
    }

    public function getAllVendors($request)
    {
        $vendors = $this->vendor->active()->with([
            'deliveryCharge' => function ($query) use ($request) {
                $query->where('state_id', $request->state_id);
            }
        ]);

        $vendors = $vendors->when(config('setting.other.enable_subscriptions') == 1, function ($q) {
            return $q->whereHas('subbscription', function ($query) {
                $query->active()->unexpired()->started();
            });
        });

        if ($request['section_id']) {
            $vendors->whereHas('sections', function ($query) use ($request) {
                $query->where('section_id', $request['section_id']);
            });
        }

        if ($request['state_id']) {
            $vendors->whereHas('deliveryCharge', function ($query) use ($request) {
                $query->where('state_id', $request->state_id);
            });
        }

        if ($request['search']) {
            $vendors->whereHas('translations', function ($query) use ($request) {

                $query->where('description', 'like', '%' . $request['search'] . '%');
                $query->orWhere('title', 'like', '%' . $request['search'] . '%');
                $query->orWhere('slug', 'like', '%' . $request['search'] . '%');

            });
        }

        return $vendors->orderBy('id', 'ASC')->get();
    }

    public function getOneVendor($request)
    {
        $vendor = $this->vendor->active();
        $vendor = $vendor->when(config('setting.other.enable_subscriptions') == 1, function ($q) {
            return $q->whereHas('subbscription', function ($query) {
                $query->active()->unexpired()->started();
            });
        });
        return $vendor->find($request->id);
    }

    /*public function getDeliveryChargesByVendorByState($request)
    {
        $charge = $this->charge
            ->where('vendor_id', $request['vendor_id'])
            ->where('state_id', $request['state_id'])
            ->first();

        return $charge;
    }*/

    public function findById($id)
    {
        $vendor = $this->vendor->with(['companies' => function ($q) {
            $q->with('deliveryCharge', 'availabilities');
        }]);

        $vendor = $vendor->when(config('setting.other.enable_subscriptions') == 1, function ($q) {
            return $q->whereHas('subbscription', function ($query) {
                $query->active()->unexpired()->started();
            });
        });

        return $vendor->find($id);
    }

    public function findVendorByIdAndStateId($id, $stateId)
    {
        $vendor = $this->vendor
            ->with(['companies' => function ($q) use ($stateId) {
                $q->active();
                $q->whereHas('deliveryCharge', function ($query) use ($stateId) {
                    $query->where('state_id', $stateId);
                });
                $q->has('availabilities');
            }]);

        $vendor = $vendor->when(config('setting.other.enable_subscriptions') == 1, function ($q) {
            return $q->whereHas('subbscription', function ($query) {
                $query->active()->unexpired()->started();
            });
        });

        $vendor = $vendor->whereHas('states', function ($query) use ($stateId) {
            $query->where('state_id', $stateId);
        });

        return $vendor->find($id);
    }

    public function getBranchesByRestaurantIdAndPickup($id)
    {
        return $this->vendor->where('parent_id', $id)
            ->where('enable_pickup', 1)
            ->orderBy('id', 'DESC')
            ->get();
    }
}
