@extends('apps::frontend.layouts.master')
@section('title', __('authentication::frontend.reset.title') )
@section('content')

    <div class="inner-page">
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <div class="login-form">
                        <h5 class="title-login">{{ __('authentication::frontend.reset.title') }}</h5>
                        {{--<p class="p-title-login">اهلا بك، يمكنك استعادة كلمة المرور من هنا</p>--}}

                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                <center>
                                    {{ session('status') }}
                                </center>
                            </div>
                        @endif

                        <form class="login" method="POST" action="{{ route('frontend.password.update') }}">
                            @csrf
                            <p class="form-row form-row-wide">
                                <input type="email"
                                       name="email"
                                       autocomplete="off"
                                       value="{{ old('email') }}"
                                       placeholder="{{ __('authentication::frontend.register.form.email') }}"
                                       class="input-text">
                            @error('email')
                            <p class="text-danger m-2" role="alert">
                                <strong>{{ $message }}</strong>
                            </p>
                            @enderror
                            </p>

                            <p class="form-row form-row-wide">
                                <input type="email"
                                       name="password"
                                       placeholder="{{ __('authentication::frontend.register.form.password') }}"
                                       class="input-text">
                            @error('password')
                            <p class="text-danger m-2" role="alert">
                                <strong>{{ $message }}</strong>
                            </p>
                            @enderror
                            </p>

                            <p class="form-row form-row-wide">
                                <input type="email"
                                       name="password_confirmation"
                                       placeholder="{{ __('authentication::frontend.register.form.password_confirmation') }}"
                                       class="input-text">
                            @error('token')
                            <p class="text-danger m-2" role="alert">
                                <strong>{{ $message }}</strong>
                            </p>
                            @enderror
                            </p>

                            <p class="form-row">
                                <input type="submit"
                                       value="{{  __('authentication::frontend.reset.form.btn.reset') }}"
                                       name="save"
                                       class="button-submit btn-block">
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop
