<?php

namespace Modules\Authentication\Http\Controllers\WebService;

use Illuminate\Http\Request;
use Modules\Apps\Http\Controllers\WebService\WebServiceController;
use Modules\Cart\Traits\CartTrait;
use Modules\User\Transformers\WebService\UserResource;
use Modules\Authentication\Foundation\Authentication;
use Modules\Authentication\Http\Requests\WebService\RegisterRequest;
use Modules\Authentication\Repositories\WebService\AuthenticationRepository as AuthenticationRepo;

class RegisterController extends WebServiceController
{
    use Authentication, CartTrait;

    protected $auth;

    function __construct(AuthenticationRepo $auth)
    {
        $this->auth = $auth;
    }

    public function register(RegisterRequest $request)
    {
        $registered = $this->auth->register($request);

        if ($registered) {
            $this->loginAfterRegister($request);
            if (isset($request->user_token) && !is_null($request->user_token)) {
                $this->updateCartKey($request->user_token, $registered->id);
            }
            return $this->tokenResponse($registered);
        } else {
            return $this->error(__('authentication::api.register.messages.failed'), [], 401);
        }

    }


    public function tokenResponse($user = null)
    {
        $user = $user ? $user : auth('api')->user();

        $token = $this->generateToken($user);

        return $this->response([
            'access_token' => $token->accessToken,
            'user' => new UserResource($user),
            'token_type' => 'Bearer',
            'expires_at' => $this->tokenExpiresAt($token)
        ]);
    }
}
