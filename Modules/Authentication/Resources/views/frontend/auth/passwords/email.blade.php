@extends('apps::frontend.layouts.master')
@section('title', __('authentication::frontend.password.title') )
@section('content')

    <div class="inner-page">
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <div class="login-form">
                        <h5 class="title-login">{{ __('authentication::frontend.password.title') }}</h5>
                        {{--<p class="p-title-login">اهلا بك، يمكنك استعادة كلمة المرور من هنا</p>--}}

                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                <center>
                                    {{ session('status') }}
                                </center>
                            </div>
                        @endif

                        <form class="login" method="POST"
                              action="{{ route('frontend.password.email') }}">
                            @csrf
                            <p class="form-row form-row-wide">
                                <input type="email"
                                       name="email"
                                       id="email"
                                       value="{{ old('email') }}"
                                       autocomplete="off"
                                       placeholder="{{ __('authentication::frontend.password.form.email')}}"
                                       class="input-text">
                            @error('email')
                            <p class="text-danger m-2" role="alert">
                                <strong>{{ $message }}</strong>
                            </p>
                            @enderror
                            </p>
                            <p class="form-row">
                                <input type="submit"
                                       value="{{  __('authentication::frontend.password.form.btn.password') }}"
                                       name="Signup"
                                       class="button-submit btn-block">
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('externalJs')

    <script></script>

@endsection
