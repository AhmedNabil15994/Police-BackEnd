<?php

namespace Modules\Authentication\Http\Controllers\WebService;

use Illuminate\Http\Request;
use Modules\Cart\Traits\CartTrait;
use Modules\User\Transformers\WebService\UserResource;
use Modules\Apps\Http\Controllers\WebService\WebServiceController;
use Modules\Authentication\Foundation\Authentication;
use Modules\Authentication\Http\Requests\WebService\LoginRequest;

class LoginController extends WebServiceController
{
    use Authentication, CartTrait;

    public function postLogin(LoginRequest $request)
    {
        $failedAuth = $this->login($request);

        if ($failedAuth)
            return $this->invalidData($failedAuth, [], 422);

        if (isset($request->user_token) && !is_null($request->user_token)) {
            $this->updateCartKey($request->user_token, auth()->id());
        }
        return $this->tokenResponse();
    }

    public function tokenResponse($user = null)
    {
        $user = $user ? $user : auth()->user();

        $token = $this->generateToken($user);

        return $this->response([
            'access_token' => $token->accessToken,
            'user' => new UserResource($user),
            'token_type' => 'Bearer',
            'expires_at' => $this->tokenExpiresAt($token)
        ]);
    }

    public function logout(Request $request)
    {
        $user = auth()->user()->token()->revoke();
        return $this->response([], __('authentication::api.logout.messages.success'));
    }
}
