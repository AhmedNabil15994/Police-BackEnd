<?php

namespace Modules\User\Repositories\WebService;

use Modules\User\Entities\Address;
use DB;

class AddressRepository
{
    protected $address;

    function __construct(Address $address)
    {
        $this->address = $address;
    }

    public function getAllByUsrId()
    {
        $authUserId = auth('api')->user() ? auth('api')->user()->id : null;
        return $this->address->where('user_id', $authUserId)->with('state')->orderBy('id', 'DESC')->get();
    }

    public function findById($id)
    {
        $authUserId = auth('api')->user() ? auth('api')->user()->id : null;
        return $this->address->where('user_id', $authUserId)->with('state')->find($id);
    }

    public function findByIdWithoutAuth($id)
    {
        $address = $this->address->with('state')->find($id);
        return $address;
    }

    public function create($request)
    {
        DB::beginTransaction();

        try {
            $authUserId = auth('api')->user() ? auth('api')->user()->id : null;

            $address = $this->address->create([
                'email' => $request['email'] ?? null,
                'username' => $request['username'] ?? null,
                'mobile' => $request['mobile'] ?? null,
                'address' => $request['address'],
                'block' => $request['block'],
                'street' => $request['street'],
                'building' => $request['building'],
                'state_id' => $request['state'],
                'user_id' => $authUserId,
                'district' => $request['district'] ?? null,
            ]);

            DB::commit();
            return $address;

        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function update($request, $address)
    {
        DB::beginTransaction();

        try {

            $address->update([
                'email' => $request['email'] ?? null,
                'username' => $request['username'] ?? null,
                'mobile' => $request['mobile'] ?? null,
                'address' => $request['address'],
                'block' => $request['block'],
                'street' => $request['street'],
                'building' => $request['building'],
                'state_id' => $request['state'],
                'district' => $request['district'] ?? null,
            ]);

            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function delete($id)
    {
        DB::beginTransaction();

        try {

            $model = $this->findById($id);
            $model->delete();

            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
}
