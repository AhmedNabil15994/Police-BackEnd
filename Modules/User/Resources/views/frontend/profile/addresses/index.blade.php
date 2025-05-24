@extends('apps::frontend.layouts.master')
@section('title', __('user::frontend.addresses.index.title'))
@section('content')

    <div class="second-header d-flex align-items-center">
        <div class="container">
            <h1>{{ __('user::frontend.profile.index.addresses') }}</h1>
        </div>
    </div>
    <div class="inner-page">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    @include('user::frontend.profile._user-side-menu')
                </div>
                <div class="col-md-9">

                    @if(count($addresses) > 0)
                        <div class="row">
                            @foreach($addresses as $k => $address)
                                <div class="col-md-12">
                                    <div class="address-item">
                                        <div class="row address-option-row">
                                            <div
                                                class="col-md-4 filed-title">{{__('user::frontend.addresses.form.state_name')}}</div>
                                            <div class="col-md-8 color-brown">{{ $address->state->title }}</div>
                                        </div>
                                        <div class="row address-option-row">
                                            <div
                                                class="col-md-4 filed-title">{{__('user::frontend.addresses.form.address_details')}}</div>
                                            <div class="col-md-8">{{ $address->address ?? '---' }}</div>
                                        </div>
                                        <div class="row address-option-row">
                                            <div
                                                class="col-md-4 filed-title">{{__('user::frontend.addresses.form.mobile')}}</div>
                                            <div class="col-md-8">{{ $address->mobile }}</div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12 address-opt">
                                                <a href="{{ url(route('frontend.profile.address.delete', $address->id)) }}">
                                                    <i class="fa fa-trash-o"></i>
                                                    {{ __('user::frontend.addresses.index.btn.delete') }}</a>
                                                <a href="javascript:;" data-toggle="modal"
                                                   data-target="#addressEditModal-{{$address->id}}">
                                                    <i class="fa fa-edit"></i> {{ __('user::frontend.addresses.index.btn.edit') }}
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            <div class="col-md-12">
                                <a href="{{ route('frontend.profile.address.create') }}"
                                   class="btn main-btn proc-ch-out">
                                    {{ __('user::frontend.addresses.create.title') }}
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="account-content">
                            <div class="empty-address">
                                <i class="ti-location-pin"></i>
                                <h6>{{ __('user::frontend.addresses.index.alert.no_addresses') }}</h6>
                                <a href="{{ route('frontend.profile.address.create') }}"
                                   class="btn main-btn">{{ __('user::frontend.addresses.create.title') }}</a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if(count($addresses) > 0)
        @foreach($addresses as $k => $address)
            <div class="modal fade" id="addressEditModal-{{$address->id}}" tabindex="-1" role="dialog"
                 aria-labelledby=""
                 aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <b class="modal-title"
                                id="exampleModalLongTitle">{{ __('user::frontend.addresses.edit.title') }}</b>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form class="edit-form order-address"
                                  action="{{ url(route('frontend.profile.address.update', $address)) }}"
                                  method="post">
                                @csrf

                                <div class="row">
                                    <div class="col-md-6 col-12">
                                        <p class="form-row">
                                            <input type="text"
                                                   name="username"
                                                   value="{{ $address->username }}"
                                                   autocomplete="off"
                                                   class="input-text"
                                                   placeholder="{{__('user::frontend.addresses.form.username')}}"/>
                                        </p>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <p class="form-row">
                                            <input type="text"
                                                   name="mobile"
                                                   value="{{ $address->mobile }}"
                                                   autocomplete="off"
                                                   class="input-text"
                                                   placeholder="{{__('user::frontend.addresses.form.mobile')}}"/>
                                        </p>
                                    </div>
                                </div>
                                <p class="form-row">

                                    <select class="select-detail stateSelectBox" name="state">
                                        <option>{{ __('user::frontend.addresses.form.states') }}</option>
                                        @if(isset($states) && count($states) > 0)
                                            @foreach ($states as $state)

                                                <option
                                                    value="{{$state->id}}" {{ $state->id == $address->state_id ? 'selected' : '' }}>
                                                    {{ $state->translate(locale())->title }}
                                                </option>

                                            @endforeach
                                        @endif
                                    </select>
                                    <input type="hidden" class="stateName" name="order_state_name"
                                           value="{{ get_cookie_value(config('core.config.constants.ORDER_STATE_NAME')) ? get_cookie_value(config('core.config.constants.ORDER_STATE_NAME')) : ''}}">

                                </p>

                                <div class="row">
                                    <div class="col-md-6 col-12">
                                        <p class="form-row">
                                            <input type="text"
                                                   name="block"
                                                   value="{{ $address->block }}"
                                                   autocomplete="off"
                                                   class="input-text"
                                                   placeholder="{{__('user::frontend.addresses.form.block')}}"/>
                                        </p>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <p class="form-row">
                                            <input type="text" name="building" value="{{ $address->building }}"
                                                   autocomplete="off"
                                                   placeholder="{{__('user::frontend.addresses.form.building')}}"/>
                                        </p>
                                    </div>
                                </div>
                                {{--<div class="form-group">
                                    <input type="text" name="text" placeholder="المنطقة "/>
                                </div>--}}
                                <p class="form-row">
                                    <input type="text"
                                           name="street"
                                           value="{{ $address->street }}"
                                           autocomplete="off"
                                           class="input-text"
                                           placeholder="{{__('user::frontend.addresses.form.street')}}"/>
                                </p>

                                <p class="form-row">
                                    <input type="text"
                                           name="district"
                                           value="{{ $address->district }}"
                                           autocomplete="off"
                                           class="input-text"
                                           placeholder="{{__('user::frontend.addresses.form.district')}}"/>
                                </p>

                                <p class="form-row">
                                    <textarea name="address" rows="4" class="form-control" autocomplete="off"
                                              class="input-text"
                                              placeholder="{{__('user::frontend.addresses.form.address_details')}}">{{ $address->address }}</textarea>
                                </p>

                                <div class="mb-20 mt-20 text-left">
                                    <button type="submit"
                                            style="color: #fff;"
                                            class="btn btn-them">{{ __('user::frontend.addresses.btn.edit') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endif


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
