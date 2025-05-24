@extends('apps::frontend.layouts.master')
@section('title', __('user::frontend.profile.index.title'))
@section('content')

    <div class="second-header d-flex align-items-center">
        <div class="container">
            <h1>{{ __('user::frontend.profile.index.update') }}</h1>
        </div>
    </div>
    <div class="inner-page">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    @include('user::frontend.profile._user-side-menu')
                </div>
                <div class="col-md-9">
                    <div class="cart-inner">
                        <form method="post" action="{{ url(route('frontend.profile.update')) }}">
                            @csrf

                            <div class="previous-address">
                                @include('apps::frontend.layouts._alerts')

                                <h2 class="cart-title">{{ __('user::frontend.profile.index.form.form_title') }}</h2>
                                <div class="row">
                                    <div class="col-md-6 col-12">
                                        <div class="form-row">
                                            <label>{{__('user::frontend.profile.index.form.name')}}</label>
                                            <input class="input-text"
                                                   type="text"
                                                   value="{{ auth()->user()->name }}"
                                                   name="name"
                                                   placeholder="{{ __('user::frontend.profile.index.form.name') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-row">
                                            <label>{{__('user::frontend.profile.index.form.email')}}</label>
                                            <input class="input-text"
                                                   type="text"
                                                   name="email"
                                                   value="{{ auth()->user()->email }}"
                                                   placeholder="{{ __('user::frontend.profile.index.form.email') }}">
                                        </div>
                                    </div>
                                </div>

                                {{-- <div class="row">
                                    <div class="col-md-6 col-12">
                                        <div class="form-row">
                                            <select class="select-detail select2-hidden-accessible" name="الدولة"
                                                    data-select2-id="1" tabindex="-1" aria-hidden="true">
                                                <optgroup label="Egypt">
                                                    <option data-select2-id="3">Alexandria</option>
                                                    <option>Cairo</option>
                                                    <option>Aswan</option>
                                                </optgroup>
                                                <optgroup label="Kwuit">
                                                    <option>State name</option>
                                                    <option>State name</option>
                                                    <option>State name</option>
                                                    <option>State name</option>
                                                </optgroup>
                                                <optgroup label="Jurdan">
                                                    <option>State name</option>
                                                    <option>State name</option>
                                                    <option>State name</option>
                                                    <option>State name</option>
                                                </optgroup>

                                            </select>
                                            <!--<span class="select2 select2-container select2-container--default" dir="rtl" data-select2-id="2" style="width: 106.8px;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="false" tabindex="0" aria-disabled="false" aria-labelledby="select2-الدولة-e7-container"><span class="select2-selection__rendered" id="select2-الدولة-e7-container" role="textbox" aria-readonly="true" title="Alexandria">Alexandria</span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span>-->
                                        </div>
                                    </div>--}}

                                <div class="col-md-6 col-12">
                                    <div class="form-row">
                                        <label>{{__('user::frontend.profile.index.form.mobile')}}</label>
                                        <input class="input-text"
                                               type="text"
                                               name="mobile"
                                               value="{{ auth()->user()->mobile }}"
                                               placeholder="{{ __('user::frontend.profile.index.form.mobile') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 col-12">
                                    <label>{{__('user::frontend.profile.index.form.password')}}</label>
                                    <div class="form-row">
                                        <input class="input-text"
                                               type="password"
                                               name="password"
                                               placeholder="{{ __('user::frontend.profile.index.form.password') }}">
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-row">
                                        <label>{{__('user::frontend.profile.index.form.password_confirmation')}}</label>
                                        <input class="input-text"
                                               type="password"
                                               name="password_confirmation"
                                               placeholder="{{ __('user::frontend.profile.index.form.password_confirmation') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="mb-20 mt-20 text-left">
                                <button type="submit" class="btn button-submit">
                                    {{ __('user::frontend.profile.index.form.btn.update') }}
                                </button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
    </div>


@endsection

@section('pageJs')
    <script>
        {{--var mobile = window.intlTelInput(document.querySelector("#mobile"), {--}}
        {{--    initialCountry: '{{ auth()->user()->country_code }}',--}}
        {{--    separateDialCode: true--}}
        {{--});--}}

        {{--function setCode() {--}}
        {{--    $("#country_code").val(mobile.getSelectedCountryData().iso2.toUpperCase());--}}
        {{--}--}}
    </script>
@endsection
