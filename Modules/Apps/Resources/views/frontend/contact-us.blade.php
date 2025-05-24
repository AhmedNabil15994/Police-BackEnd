@extends('apps::frontend.layouts.master')
@section('title', __('apps::frontend.contact_us.title') )
@section('content')

    <div class="second-header d-flex align-items-center"
         style="background: url('{{ config('setting.images.contact_us_logo') ? url(config('setting.images.contact_us_logo')) : url('frontend/images/pages.png') }}')">
        <div class="container">
            <h1>{{ __('apps::frontend.contact_us.header_title') }}</h1>
        </div>
    </div>
    <div class="container">
        <div class="innerPage contact">
            <div class="row">
                <div class="col-md-8">

                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            <center>
                                {{ session('status') }}
                            </center>
                        </div>
                    @endif

                    <form class="form-contact" action="{{ url(route('frontend.send-contact-us')) }}" method="post">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="contact-info">
                                    <p class="form-row form-row-wide">
                                        <label>{{ __('apps::frontend.contact_us.form.username')}}
                                            <span class="note-impor">*</span>
                                        </label>
                                        <input type="text"
                                               name="username"
                                               value="{{ old('username') }}"
                                               placeholder="{{ __('apps::frontend.contact_us.form.username')}}"
                                               class="input-text">

                                    @error('username')
                                    <p class="text-danger m-2" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </p>
                                    @enderror

                                    </p>
                                    <p class="form-row form-row-wide">
                                        <label>
                                            {{ __('apps::frontend.contact_us.form.email')}}
                                            <span class="note-impor">*</span>
                                        </label>
                                        <input type="email"
                                               value="{{ old('email') }}"
                                               placeholder="{{ __('apps::frontend.contact_us.form.email')}}"
                                               name="email"
                                               class="input-text">

                                    @error('email')
                                    <p class="text-danger m-2" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </p>
                                    @enderror
                                    </p>
                                    <p class="form-row form-row-wide">
                                        <label>{{ __('apps::frontend.contact_us.form.mobile')}}</label>
                                        <input type="text"
                                               value="{{ old('mobile') }}"
                                               placeholder="{{ __('apps::frontend.contact_us.form.mobile')}}"
                                               name="mobile"
                                               class="input-text">
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <p class="form-row form-row-wide form-text">
                                    <label>
                                        {{ __('apps::frontend.contact_us.form.message')}}
                                        <span class="note-impor">*</span>
                                    </label>
                                    <textarea aria-invalid="false"
                                              class="textarea-control"
                                              name="message"
                                              placeholder="{{ __('apps::frontend.contact_us.form.message')}}">{{ old('message') }}</textarea>

                                @error('message')
                                <p class="text-danger m-2" role="alert">
                                    <strong>{{ $message }}</strong>
                                </p>
                                @enderror
                                </p>
                                <p class="form-row">
                                    <input type="submit"
                                           value="{{ __('apps::frontend.contact_us.form.btn.send')}}"
                                           class="button-submit btn-block">
                                </p>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-md-4 contact-details">

                    <ul>
                        @if(config('setting.contact_us.mobile'))
                            <li>
                                <i class="ti-mobile"></i> <strong>{{ __('apps::frontend.contact_us.info.mobile')}}
                                    :</strong>
                                <span>
                                    <a href="tel:{{ config('setting.contact_us.mobile') }}">
                                     {{ config('setting.contact_us.mobile') }}
                                    </a>
                                </span>
                            </li>
                        @endif

                        @if(config('setting.contact_us.technical_support'))
                            <li>
                                <i class="ti-headphone-alt"></i>
                                <strong>{{ __('apps::frontend.contact_us.info.technical_support')}}:</strong>
                                <span>
                                    <a href="tel:{{ config('setting.contact_us.technical_support') }}">
                                    {{ config('setting.contact_us.technical_support') }}
                                    </a>
                                </span>
                            </li>
                        @endif

                        {{--                        @if(config('setting.contact_us.recruitment_email'))--}}
                        <li>
                            <i class="ti-email"></i>
                            <strong>{{ __('apps::frontend.contact_us.info.recruitment_email')}}:</strong>
                            <span>
                                    <a href="mailto:Careers.policesteak@gmail.com">
                                    Careers.policesteak@gmail.com
                                    </a>
                                </span>
                        </li>
                        {{--                        @endif--}}

                        {{--                        @if(config('setting.contact_us.call_center_number'))--}}
                        <li>
                            <i class="ti-headphone-alt"></i>
                            <strong>{{ __('apps::frontend.contact_us.info.call_center_number')}}:</strong>
                            <span>
                                    <a href="tel:1806666">
                                    1806666
                                    </a>
                                </span>
                        </li>
                        {{--                        @endif--}}

                        <li>
                            <i class="ti-world"></i>
                            <strong>{{ __('apps::frontend.contact_us.info.our_site')}}:</strong>
                            <span>
                                <a href="{{ route('frontend.home') }}">{{ env('APP_URL') }}</a>
                            </span>
                        </li>

                        {{--<li>
                            <i class="ti-email"></i> <strong>{{ __('apps::frontend.contact_us.info.email')}}:</strong>
                            <span>
                                <a href="mailto:{{ config('setting.contact_us.email') }}">
                                    {{ config('setting.contact_us.email') }}
                                </a>
                            </span>
                        </li>--}}
                    </ul>
                    <div class="footer-social mt-30 pt-30">
                        <a href="{{ config('setting.social.facebook') ?? '#' }}" class="social-icon">
                            <i class="ti-facebook"></i>
                        </a>
                        <a href="{{ config('setting.social.instagram') ?? '#' }}" class="social-icon">
                            <i class="ti-instagram"></i>
                        </a>
                        <a href="{{ config('setting.social.linkedin') ?? '#' }}" class="social-icon">
                            <i class="ti-linkedin"></i>
                        </a>
                        <a href="{{ config('setting.social.twitter') ?? '#' }}" class="social-icon">
                            <i class="ti-twitter-alt"></i>
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>

@endsection

@section('externalJs')

    <script>
        $(document).ready(function () {

        });
    </script>

@endsection
