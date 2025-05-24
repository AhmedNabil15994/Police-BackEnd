<?php

namespace Modules\Authentication\Repositories\WebService;

use Modules\User\Entities\PasswordReset;
use Modules\User\Entities\User;
use Carbon\Carbon;
use Hash;
use DB;

class AuthenticationRepository
{
    protected $user;
    protected $password;

    function __construct(User $user, PasswordReset $password)
    {
        $this->password = $password;
        $this->user = $user;
    }

    public function register($request)
    {
        DB::beginTransaction();

        try {
            $user = $this->user->create([
                'name' => $request['name'],
                'email' => $request['email'] ?? null,
                'calling_code' => $request['calling_code'] ?? null,
                'mobile' => $request['mobile'],
                'country_id' => $request['country_id'] ?? null,
                'password' => Hash::make($request['password']),
                'image' => '/uploads/users/user.png',
            ]);

            DB::commit();
            return $user;

        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }

    }

    public function findUserByEmail($request)
    {
        $user = $this->user->where('email', $request->email)->first();
        return $user;
    }

    public function createToken($request)
    {
        $user = $this->findUserByEmail($request);

        $this->deleteTokens($user);

        $newToken = strtolower(str_random(64));

        $token = $this->password->insert([
            'email' => $user->email,
            'token' => $newToken,
            'created_at' => Carbon::now(),
        ]);

        $data = [
            'token' => $newToken,
            'user' => $user
        ];

        return $data;
    }

    public function resetPassword($request)
    {
        $user = $this->findUserByEmail($request);

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        $this->deleteTokens($user);

        return true;
    }

    public function deleteTokens($user)
    {
        $this->password->where('email', $user->email)->delete();
    }

}
