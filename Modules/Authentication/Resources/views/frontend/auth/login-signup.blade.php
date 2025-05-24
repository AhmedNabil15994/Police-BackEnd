@extends('apps::frontend.layouts.master')
@section('title', __('authentication::frontend.login.login_or_signup') )

@section('externalStyle')
    <style>
        p.text-danger {
            margin-bottom: 10px !important;
        }
    </style>
@endsection

@section('content')

    <div class="second-header d-flex align-items-center">
        <div class="container">
            <h1>{{ __('authentication::frontend.login.login_or_signup') }}</h1>
        </div>
    </div>
    <div class="inner-page">
        <div class="container">
            <div class="login-page">
                <div class="row">
                    <div class="col-md-5">
                        <div class="login-form">
                            <h5 class="title-login">{{ __('authentication::frontend.login.title') }}</h5>
                            <p class="p-title-login">{{ __('authentication::frontend.login.login_welcome_msg') }}</p>
                            <form class="login" method="POST" action="{{ route('frontend.post_login') }}">
                                @csrf
                                <input type="hidden" name="redirect_to" value="{{$request['route']}}">
                                <input type="hidden" name="formName" value="loginForm">

                                <p class="form-row form-row-wide">
                                    <input type="text"
                                           name="email"
                                           id="email"
                                           value="{{ old('email') }}"
                                           autocomplete="off"
                                           placeholder="{{ __('authentication::frontend.login.form.email_or_mobile')}}"
                                           class="input-text">
                                @if(old('formName') == 'loginForm')
                                    @error('email')
                                    <p class="text-danger m-2" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </p>
                                    @enderror
                                    @endif
                                    </p>

                                    <p class="form-row form-row-wide">
                                        <input type="password"
                                               name="password"
                                               id="password"
                                               placeholder="{{ __('authentication::frontend.login.form.password')}}"
                                               class="input-text">
                                    @if(old('formName') == 'loginForm')
                                        @error('password')
                                        <p class="text-danger m-2" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </p>
                                        @enderror
                                        @endif
                                        </p>
                                        <div class="form-row form-row-wide">
                                            <ul>
                                                <li class="inline-block-li checkboxes">
                                                    <input id="check-er"
                                                           type="checkbox"
                                                           name="remember"
                                                        {{ old('remember') ? 'checked' : '' }}>
                                                    <label
                                                        for="check-er">{{ __('authentication::frontend.login.form.remember_me') }}</label>
                                                </li>
                                                <li class="inline-block-li forgot-password">
                                                    <a href="{{ route('frontend.password.request') }}"
                                                       class="">{{ __('authentication::frontend.login.form.btn.forget_password') }}</a>
                                                </li>
                                            </ul>
                                        </div>

                                        <p class="form-row">
                                            <input type="submit"
                                                   value="{{  __('authentication::frontend.login.form.btn.login') }}"
                                                   name="btnLogin"
                                                   class="button-submit btn-block">
                                        </p>
                            </form>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <span class="se-vert"></span>
                    </div>
                    <div class="col-md-5">
                        <div class="login-form signin-block">
                            <h5 class="title-login">{{ __('authentication::frontend.register.register_new_account') }}</h5>
                            <p class="p-title-login">{{ __('authentication::frontend.register.register_welcome_msg') }}</p>
                            <form class="login" method="post" action="{{ route('frontend.register') }}">
                                @csrf
                                <input type="hidden" name="formName" value="registerForm">

                                <p class="form-row form-row-wide">
                                    <input type="email"
                                           name="name"
                                           autocomplete="off"
                                           value="{{ old('name') }}"
                                           placeholder="{{ __('authentication::frontend.register.form.name') }}"
                                           class="input-text">
                                @if(old('formName') == 'registerForm')
                                    @error('name')
                                    <p class="text-danger m-2" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </p>
                                    @enderror
                                    @endif
                                    </p>
                                    <p class="form-row form-row-wide">
                                        <input type="email"
                                               name="email"
                                               autocomplete="off"
                                               value="{{ old('email') }}"
                                               placeholder="{{ __('authentication::frontend.register.form.email') }}"
                                               class="input-text">
                                    @if(old('formName') == 'registerForm')
                                        @error('email')
                                        <p class="text-danger m-2" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </p>
                                        @enderror
                                        @endif
                                        </p>

                                        <p class="form-row form-row-wide">
                                            <input type="text"
                                                   name="mobile"
                                                   id="mobile"
                                                   placeholder="{{ __('authentication::frontend.register.form.mobile') }}"
                                                   {{--pattern="[0-9]+"--}}
                                                   maxlength="8"
                                                   value="{{ old('mobile') }}"
                                                   class="input-text">
                                            <input type="hidden" id="country_code" name="country_code">

                                        @if(old('formName') == 'registerForm')
                                            @error('mobile')
                                            <p class="text-danger m-2" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </p>
                                            @enderror
                                            @endif
                                            </p>
                                            <p class="form-row form-row-wide">
                                                <input type="password"
                                                       name="password"
                                                       placeholder="{{ __('authentication::frontend.register.form.password') }}"
                                                       class="input-text">
                                            @if(old('formName') == 'registerForm')
                                                @error('password')
                                                <p class="text-danger m-2" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </p>
                                                @enderror
                                                @endif
                                                </p>
                                                <p class="form-row form-row-wide">
                                                    <input type="password"
                                                           name="password_confirmation"
                                                           placeholder="{{ __('authentication::frontend.register.form.password_confirmation') }}"
                                                           class="input-text">
                                                </p>
                                                <p class="form-row form-row-wide">
                                                    {{ __('authentication::frontend.register.terms_msg') }}
                                                    <a href="#">{{ __('authentication::frontend.register.terms_and_conditions') }}</a>
                                                </p>

                                                <p class="form-row">
                                                    <input type="submit"
                                                           value="{{ __('authentication::frontend.register.btn.register') }}"
                                                           name="btnRegister"
                                                           class="button-submit btn-block">
                                                </p>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('externalJs')

    <script>
        /*var mobile = window.intlTelInput(document.querySelector("#mobile"), {
        separateDialCode: true
        });

        function setCode() {
        $("#country_code").val(mobile.getSelectedCountryData().iso2.toUpperCase());
        }*/
    </script>

@endsection
