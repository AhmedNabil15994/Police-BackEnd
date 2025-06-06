<?php

namespace Modules\Vendor\Repositories\FrontEnd;

use Modules\Vendor\Entities\Vendor;
use Hash;
use DB;

class VendorRepository
{
    protected $vendor;

    function __construct(Vendor $vendor)
    {
        $this->vendor = $vendor->branch();
    }

    // Get All Subscribed vendors from section slug of vendors
    public function getAllActiveBySectionPaginate($slug)
    {
        $vendors = $this->vendor->active()->whereHas('sections', function ($query) use ($slug) {
            $query->whereTranslation('slug', $slug);
        });

        $vendors = $vendors->when(config('setting.other.enable_subscriptions') == 1, function ($q) {
            return $q->whereHas('subbscription', function ($query) {
                $query->active()->unexpired()->started();
            });
        });

        $vendors = $vendors->orderBy('id', 'DESC')->paginate(24);
        return $vendors;
    }

    // Get All Subscribed vendors from section slug of vendors
    public function getAllActiveByStatePaginate($state)
    {
        $vendors = $this->vendor->active()->with([
            'deliveryCharge' => function ($query) use ($state) {
                $query->where('state_id', $state->id);
            }
        ])->whereHas('deliveryCharge', function ($query) use ($state) {
            $query->where('state_id', $state->id);
        });

        $vendors = $vendors->when(config('setting.other.enable_subscriptions') == 1, function ($q) {
            return $q->whereHas('subbscription', function ($query) {
                $query->active()->unexpired()->started();
            });
        });

        $vendors = $vendors->orderBy('id', 'DESC')->paginate(24);
        return $vendors;
    }

    // Get All Subscribed vendors by filteration attributes
    public function filterVendors($request, $additional = null)
    {
        $vendors = $this->vendor->active();

        $vendors = $vendors->when(config('setting.other.enable_subscriptions') == 1, function ($q) {
            return $q->whereHas('subbscription', function ($query) {
                $query->active()->unexpired()->started();
            });
        });

        if (isset($request['search'])) {

            $vendors->whereHas('translations', function ($query) use ($request) {

                $query->where('description', 'like', '%' . $request['search'] . '%');
                $query->orWhere('title', 'like', '%' . $request['search'] . '%');
                $query->orWhere('slug', 'like', '%' . $request['search'] . '%');

            });

        }

        if (!is_null($additional['state'])) {
            $vendors->with([
                'deliveryCharge' => function ($query) use ($additional) {
                    $query->where('state_id', $additional['state']->id);
                }
            ]);

            $vendors->whereHas('deliveryCharge', function ($query) use ($additional) {

                $query->where('state_id', $additional['state']->id);

            });
        }

        if (isset($request['payment'])) {
            $vendors->with([
                'payments' => function ($query) use ($request) {
                    $query->whereIn('payment_id', $request['payment']);
                }
            ]);

            $vendors->whereHas('payments', function ($query) use ($request) {

                $query->whereIn('payment_id', $request['payment']);

            });

        }

        if (isset($request['status'])) {
            $vendors->with([
                'openingStatus' => function ($query) use ($request) {
                    $query->whereIn('id', $request['status']);
                }
            ]);

            $vendors->whereHas('openingStatus', function ($query) use ($request) {

                $query->whereIn('id', $request['status']);

            });

        }

        if (isset($request['sorted_by'])) {

            if ($request['sorted_by'] == 'a_to_z')
                $vendors->orderByTranslation('title', 'ASC');

            if ($request['sorted_by'] == 'latest')
                $vendors->orderBy('id', 'ASC');

        } else {

            $vendors->orderBy('sorting', 'ASC');

        }

        return $vendors->paginate(24);
    }

    public function findBySlug($slug)
    {
        $vendor = $this->vendor;

        $vendor->when(config('setting.other.enable_subscriptions') == 1, function ($q) {
            return $q->whereHas('subbscription', function ($query) {
                $query->active()->unexpired()->started();
            });
        });

        $vendor = $vendor->whereTranslation('slug', $slug)->first();
        return $vendor;
    }

    public function findById($id)
    {
        $vendor = $this->vendor;

        $vendor->when(config('setting.other.enable_subscriptions') == 1, function ($q) {
            return $q->whereHas('subbscription', function ($query) {
                $query->active()->unexpired()->started();
            });
        });

        $vendor = $vendor->find($id);
        return $vendor;
    }

    public function getFirstBranch()
    {
        return $this->vendor->first();
    }

    public function getBranchesByRestaurantId($id)
    {
        return $this->vendor->where('parent_id', $id)
            ->orderBy('id', 'DESC')
            ->get();
    }

    public function getBranchesByRestaurantIdAndPickup($id)
    {
        return $this->vendor->where('parent_id', $id)
            ->where('enable_pickup', 1)
            ->orderBy('id', 'DESC')
            ->get();
    }

    public function findByRestaurantId($id)
    {
        if (is_null($id))
            return $this->vendor->first();
        else
            return $this->vendor->where('parent_id', $id)->where('is_main_branch', 1)->first();
    }

    public function findFirstMainBranch()
    {
        return $this->vendor->where('is_main_branch', 1)->first() ? $this->vendor->where('is_main_branch', 1)->first() : $this->vendor->first();
    }

    public function getAllActiveVendors()
    {
        $vendors = $this->vendor->active();
        $vendors = $vendors->when(config('setting.other.enable_subscriptions') == 1, function ($q) {
            return $q->whereHas('subbscription', function ($query) {
                $query->active()->unexpired()->started();
            });
        });
        $vendors = $vendors->orderBy('id', 'DESC')->get();
        return $vendors;
    }

    public function getBranchesByState($stateId)
    {
        return $this->vendor
            ->whereHas('deliveryCharge', function ($q) use ($stateId) {
                $q->active();
                $q->where('state_id', $stateId);
            })
            ->with(['deliveryCharge' => function ($q) use ($stateId) {
                $q->active();
                $q->where('state_id', $stateId);
            }])
            ->get();
    }

    public function getBranchByIdAndState($stateId, $vendorId)
    {
        return $this->vendor
            ->whereHas('deliveryCharge', function ($q) use ($stateId) {
                $q->active();
                $q->where('state_id', $stateId);
            })
            ->with(['deliveryCharge' => function ($q) use ($stateId) {
                $q->active();
                $q->where('state_id', $stateId);
            }])
            ->find($vendorId);
    }

    public function checkRouteLocale($model, $slug)
    {
        if ($model->translate()->where('slug', $slug)->first()->locale != locale())
            return false;

        return true;
    }
}
