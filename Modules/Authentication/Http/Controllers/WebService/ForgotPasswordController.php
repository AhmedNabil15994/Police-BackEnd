<?php

namespace Modules\Authentication\Http\Controllers\WebService;

use Illuminate\Http\Request;
use Modules\User\Transformers\WebService\UserResource;
use Modules\Apps\Http\Controllers\WebService\WebServiceController;
use Modules\Authentication\Http\Requests\WebService\ForgetPasswordRequest;
use Modules\Authentication\Notifications\WebService\ResetPasswordNotification;
use Modules\Authentication\Repositories\WebService\AuthenticationRepository as Authentication;

class ForgotPasswordController extends WebServiceController
{
    function __construct(Authentication $auth)
    {
        $this->auth = $auth;
    }

    public function forgetPassword(ForgetPasswordRequest $request)
    {
        $token = $this->auth->createToken($request);

        $token['user']->notify((new ResetPasswordNotification($token))->locale(locale()));

        return $this->response([], __('authentication::api.forget_password.messages.success') );
    }
}
