<?php

namespace Modules\Authentication\Http\Controllers\FrontEnd;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Catalog\Traits\ShoppingCartTrait;
use Modules\Authentication\Foundation\Authentication;
use Modules\Authentication\Http\Requests\FrontEnd\LoginRequest;

class LoginController extends Controller
{
    use Authentication, ShoppingCartTrait;

    /**
     * Display a listing of the resource.
     */
    public function showLogin(Request $request)
    {
        return view('authentication::frontend.auth.login-signup', compact('request'));
    }

    /**
     * Login method
     */
    public function postLogin(LoginRequest $request)
    {
        $errors = $this->login($request);
        if ($errors)
            return redirect()->back()->withErrors($errors)->withInput($request->except('password'));

        if (isset($request->type) && $request->type == 'checkout') {
            // $this->removeCartConditionByType('company_delivery_fees', get_cookie_value(config('core.config.constants.CART_KEY')));
            $this->updateCartKey(get_cookie_value(config('core.config.constants.CART_KEY')), auth()->id());
            return redirect()->route('frontend.checkout.index');
        }
        return $this->redirectTo($request);
    }


    /**
     * Logout method
     */
    public function logout(Request $request)
    {
        $this->clearCart();
        auth()->logout();
        return $this->redirectTo($request);
    }


    public function redirectTo($request)
    {
        if ($request['redirect_to'] == 'address')
            return redirect()->route('frontend.order.address.index');


        return redirect()->route('frontend.home');
    }

}
