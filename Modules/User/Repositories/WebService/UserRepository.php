<?php

namespace Modules\User\Repositories\WebService;

use Modules\Catalog\Entities\Product;
use Modules\User\Entities\User;
use Hash;
use DB;
use Modules\User\Entities\UserFavourite;

class UserRepository
{
    protected $user;
    protected $favourite;
    protected $product;

    function __construct(User $user, UserFavourite $favourite, Product $product)
    {
        $this->user = $user;
        $this->favourite = $favourite;
        $this->product = $product;
    }

    public function update($request)
    {
        $user = auth()->user();

        if ($request['password'] == null)
            $password = $user['password'];
        else
            $password = Hash::make($request['password']);

        DB::beginTransaction();

        try {

            $user->update([
                'name' => $request['name'],
                'email' => $request['email'],
                'calling_code' => $request['calling_code'] ?? null,
                'mobile' => $request['mobile'],
                'country_id' => $request['country_id'] ?? null,
                'password' => $password,
            ]);

            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function findById($id)
    {
        $user = $this->user->find($id);

        return $user;
    }

    public function changePassword($request)
    {
        $user = $this->findById(auth()->id());

        if ($request['password'] == null)
            $password = $user['password'];
        else
            $password = Hash::make($request['password']);

        DB::beginTransaction();

        try {

            $user->update([
                'password' => $password,
            ]);

            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function userProfile()
    {
        return auth()->user();
    }

    public function favouritesList($request)
    {
        $userId = auth()->id() ?? null;
        $query = $this->product->whereHas('favourites', function ($q) use ($request, $userId) {
            $q->where('user_id', $userId);
        });

        if ($request->branch_id) {
            $query->whereHas('productVendors', function ($q) use ($request) {
                $q->where('vendor_products.vendor_id', $request->branch_id);
            });
        }

        return $query->get();
    }

    public function findFavourite($userId, $prdId)
    {
        return $this->favourite->where(function ($q) use ($userId, $prdId) {
            $q->where('user_id', $userId);
            $q->where('product_id', $prdId);
        })->first();
    }

    public function createFavourite($userId, $prdId)
    {
        return $this->favourite->create([
            'user_id' => $userId,
            'product_id' => $prdId,
        ]);
    }
}
