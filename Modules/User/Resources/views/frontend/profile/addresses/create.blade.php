@extends('apps::frontend.layouts.master')
@section('title', __('user::frontend.addresses.create.title'))
@section('content')

    <div class="second-header d-flex align-items-center">
        <div class="container">
            <h1>{{ __('user::frontend.addresses.create.title') }}</h1>
        </div>
    </div>

    <div class="inner-page">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    @include('user::frontend.profile._user-side-menu')
                </div>
                <div class="col-md-9">
                    <div class="row">
                        <div class="col-md-8">
                            @include('apps::frontend.layouts._alerts')

                            <form class="edit-form order-address"
                                  action="{{ url(route('frontend.profile.address.store')) }}" method="post">
                                @csrf

                                <div class="row">
                                    <div class="col-md-12">
                                        <p class="form-row">
                                            <label>{{__('user::frontend.addresses.form.username')}}</label>
                                            <input type="text"
                                                   name="username"
                                                   value="{{ old('username') }}"
                                                   autocomplete="off"
                                                   placeholder="{{__('user::frontend.addresses.form.username')}}"
                                                   class="input-text">
                                        </p>
                                    </div>
                                    <div class="col-md-12">
                                        <p class="form-row">
                                            <label>{{__('user::frontend.addresses.form.states')}}</label>
                                            <select class="select-detail" name="state">
                                                <option>{{ __('user::frontend.addresses.form.states') }}</option>
                                                @if(isset($states) && count($states) > 0)
                                                    @foreach ($states as $state)

                                                        <option
                                                            value="{{$state->id}}" @if(get_cookie_value(config('core.config.constants.ORDER_STATE_ID')))
                                                            {{ get_cookie_value(config('core.config.constants.ORDER_STATE_ID')) == $state->id ? 'selected' : '' }}
                                                            @else
                                                            {{ old('state') == $state->id ? 'selected' : '' }}
                                                            @endif >
                                                            {{ $state->translate(locale())->title }}
                                                        </option>

                                                    @endforeach
                                                @endif
                                            </select>
                                            <input type="hidden" class="stateName" name="order_state_name"
                                                   value="{{ get_cookie_value(config('core.config.constants.ORDER_STATE_NAME')) ? get_cookie_value(config('core.config.constants.ORDER_STATE_NAME')) : ''}}">
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="form-row">
                                            <label>{{__('user::frontend.addresses.form.building')}}</label>
                                            <input type="text"
                                                   name="building"
                                                   value="{{ old('building') }}"
                                                   autocomplete="off"
                                                   placeholder="{{__('user::frontend.addresses.form.building')}}"
                                                   class="input-text">
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="form-row">
                                            <label>{{__('user::frontend.addresses.form.block')}}</label>
                                            <input type="text"
                                                   name="block"
                                                   value="{{ old('block') }}"
                                                   autocomplete="off"
                                                   placeholder="{{__('user::frontend.addresses.form.block')}}"
                                                   class="input-text">
                                        </p>
                                    </div>
                                    <div class="col-md-12">
                                        <p class="form-row">
                                            <label>{{__('user::frontend.addresses.form.mobile')}}</label>
                                            <input type="text"
                                                   class="input-text"
                                                   name="mobile" value="{{ old('mobile') }}"
                                                   autocomplete="off"
                                                   placeholder="{{__('user::frontend.addresses.form.mobile')}}">
                                        </p>
                                    </div>
                                    <div class="col-md-12">
                                        <p class="form-row">
                                            <label>{{__('user::frontend.addresses.form.street')}}</label>
                                            <input type="text"
                                                   name="street"
                                                   value="{{ old('street') }}"
                                                   autocomplete="off"
                                                   placeholder="{{__('user::frontend.addresses.form.street')}}"
                                                   class="input-text">
                                        </p>
                                    </div>
                                    <div class="col-md-12">
                                        <p class="form-row">
                                            <label>{{__('user::frontend.addresses.form.district')}}</label>
                                            <input type="text"
                                                   name="district"
                                                   value="{{ old('district') }}"
                                                   autocomplete="off"
                                                   placeholder="{{__('user::frontend.addresses.form.district')}}"
                                                   class="input-text">
                                        </p>
                                    </div>
                                    <div class="col-md-12">
                                        <p class="form-row">
                                            <label>{{__('user::frontend.addresses.form.address_details')}}</label>
                                            <textarea name="address"
                                                      rows="4"
                                                      class="input-text"
                                                      autocomplete="off"
                                                      placeholder="{{__('user::frontend.addresses.form.address_details')}}">{{ old('address') }}</textarea>
                                        </p>
                                    </div>
                                </div>
                                <p class="form-row">
                                    <input type="submit"
                                           value="{{ __('user::frontend.profile.index.btn_add_new_address') }}"
                                           name="save"
                                           class="button-submit">
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
        $(document).ready(function () {

            /*$('.stateSelectBox').on("change", function () {
                var stateName = $("option:selected", this).text();
                $('.stateName').val(stateName);
            });*/

        });

    </script>

@endsection
