<?php

namespace Modules\User\Http\Controllers\WebService;

use Illuminate\Http\Request;
use Modules\User\Transformers\WebService\UserResource;
use Modules\User\Http\Requests\WebService\UpdateProfileRequest;
use Modules\User\Repositories\WebService\UserRepository as User;
use Modules\User\Http\Requests\WebService\ChangePasswordRequest;
use Modules\Apps\Http\Controllers\WebService\WebServiceController;

class UserController extends WebServiceController
{
    function __construct(User $user)
    {
        $this->user = $user;
    }

    public function profile()
    {
        $user =  $this->user->userProfile();
        return $this->response(new UserResource($user));
    }

    public function updateProfile(UpdateProfileRequest $request)
    {
        $this->user->update($request);

        $user =  $this->user->userProfile();

        return $this->response(new UserResource($user));
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        $this->user->changePassword($request);

        $user =  $this->user->findById(auth()->id());

        return $this->response(new UserResource($user));
    }
}
