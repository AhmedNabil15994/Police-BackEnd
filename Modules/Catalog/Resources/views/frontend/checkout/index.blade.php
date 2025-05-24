@extends('apps::frontend.layouts.master')
@section('title', __('catalog::frontend.checkout.index.title') )

@section('externalStyle')
    <style>
        /* start loader style */

        #checkoutInformationLoaderDiv {
            display: none;
            margin: 15px auto;
            justify-content: center;
        }

        #deliveryPriceLoaderDiv {
            display: none;
            margin: 15px 112px;
            justify-content: center;
        }

        #checkoutInformationLoaderDiv .my-loader,
        #deliveryPriceLoaderDiv .my-loader {
            border: 10px solid #f3f3f3;
            border-radius: 50%;
            border-top: 10px solid #3498db;
            width: 70px;
            height: 70px;
            -webkit-animation: spin 2s linear infinite;
            /* Safari */
            animation: spin 2s linear infinite;
        }

        /* end loader style */
    </style>
@endsection

@section('content')

    <div class="inner-page">
        <div class="container">
            <div class="title-top">
                <h3>{{ __('catalog::frontend.checkout.index.title') }} </h3>
            </div>

            @include('apps::frontend.layouts._alerts')

            <form method="post" action="{{ route('frontend.orders.create_order') }}">
                @csrf
                <div class="row">
                    <div class="col-md-8">
                        @if(auth()->guest())
                            <div class="check-head">
                                <a href="{{ route('frontend.login', ['type' => 'checkout']) }}"
                                   class="btn button-submit">
                                    {{ __('catalog::frontend.checkout.index.login_to_your_account') }}
                                </a>
                                &nbsp;
                                <p>{{ __('catalog::frontend.checkout.index.continue_as_guest') }}</p>
                            </div>
                        @endif

                        <div class="edit-form order-address">
                            <div class="row">

                                <div class="col-md-12">
                                    <p class="form-row">
                                        <label>{{__('user::frontend.addresses.form.username')}}</label>
                                        <input type="text"
                                               name="username"
                                               value="{{ old('username') ? old('username') : (getContactInfoCookie()->username ?? '') }}"
                                               autocomplete="off"
                                               placeholder="{{__('user::frontend.addresses.form.username')}}"
                                               class="input-text">
                                    </p>
                                </div>

                                <div class="col-md-12">
                                    <p class="form-row">
                                        <label>{{__('user::frontend.addresses.form.mobile')}}</label>
                                        <input type="text"
                                               value="{{ old('mobile') ? old('mobile') : (getContactInfoCookie()->mobile ?? '') }}"
                                               name="mobile" id="txtMobile"
                                               placeholder="{{__('user::frontend.addresses.form.mobile')}}"
                                               autocomplete="off"
                                               class="input-text">
                                    </p>
                                </div>

                                @if(optional(checkPickupDeliveryCookie())->type == 'delivery')

                                    {{--<div class="col-md-12">
                                        <p class="form-row">
                                            <label>{{__('user::frontend.addresses.form.governorate')}}</label>
                                            <input type="text"
                                                   value="{{ old('governorate') }}"
                                                   name="governorate" id="txtGovernorate"
                                                   placeholder="{{__('user::frontend.addresses.form.governorate')}}"
                                                   autocomplete="off"
                                                   class="input-text">
                                        </p>
                                    </div>--}}

                                    <div class="col-md-12">
                                        <p class="form-row">
                                            <label>{{__('user::frontend.addresses.form.states')}}</label>
                                            <select class="select-detail" name="state_id" disabled>
                                                <option>{{ $state ? $state->translate(locale())->title : __('user::frontend.addresses.form.states') }}</option>
                                            </select>
                                        </p>
                                    </div>

                                    <div class="col-md-6">
                                        <p class="form-row">
                                            <label>{{__('user::frontend.addresses.form.block')}}</label>
                                            <input type="text"
                                                   value="{{ old('block') ? old('block') : (getContactInfoCookie()->block ?? '') }}"
                                                   name="block" id="txtBlock"
                                                   placeholder="{{__('user::frontend.addresses.form.block')}}"
                                                   autocomplete="off"
                                                   class="input-text">
                                        </p>
                                    </div>

                                    <div class="col-md-6">
                                        <p class="form-row">
                                            <label>{{__('user::frontend.addresses.form.district')}}</label>
                                            <input type="text"
                                                   value="{{ old('district') ? old('district') : (getContactInfoCookie()->district ?? '') }}"
                                                   name="district"
                                                   id="txtDistrict"
                                                   placeholder="{{__('user::frontend.addresses.form.district')}}"
                                                   autocomplete="off"
                                                   class="input-text">
                                        </p>
                                    </div>

                                    <div class="col-md-6">
                                        <p class="form-row">
                                            <label>{{__('user::frontend.addresses.form.street')}}</label>
                                            <input type="text"
                                                   value="{{ old('street') ? old('street') : (getContactInfoCookie()->street ?? '') }}"
                                                   name="street"
                                                   id="txtStreet"
                                                   placeholder="{{__('user::frontend.addresses.form.street')}}"
                                                   autocomplete="off"
                                                   class="input-text">
                                        </p>
                                    </div>

                                    <div class="col-md-6">
                                        <p class="form-row">
                                            <label>{{__('user::frontend.addresses.form.building')}}</label>
                                            <input type="text"
                                                   value="{{ old('building') ? old('building') : (getContactInfoCookie()->building ?? '') }}"
                                                   name="building" id="txtBuilding"
                                                   placeholder="{{__('user::frontend.addresses.form.building')}}"
                                                   autocomplete="off"
                                                   class="input-text">
                                        </p>
                                    </div>

                                    <div class="col-md-6">
                                        <p class="form-row">
                                            <label>{{__('user::frontend.addresses.form.floor')}}</label>
                                            <input type="text"
                                                   value="{{ old('floor') ? old('floor') : (getContactInfoCookie()->floor ?? '') }}"
                                                   name="floor"
                                                   id="txtFloor"
                                                   placeholder="{{__('user::frontend.addresses.form.floor')}}"
                                                   autocomplete="off"
                                                   class="input-text">
                                        </p>
                                    </div>

                                    <div class="col-md-6">
                                        <p class="form-row">
                                            <label>{{__('user::frontend.addresses.form.flat')}}</label>
                                            <input type="text"
                                                   value="{{ old('flat') ? old('flat') : (getContactInfoCookie()->flat ?? '') }}"
                                                   name="flat"
                                                   id="txtFlat"
                                                   placeholder="{{__('user::frontend.addresses.form.flat')}}"
                                                   autocomplete="off"
                                                   class="input-text">
                                        </p>
                                    </div>

                                @else
                                    <div class="col-md-4">
                                        <p class="form-row">
                                            <label>{{__('user::frontend.addresses.form.car_type')}}</label>
                                            <input type="text"
                                                   value="{{ old('pickup_delivery.car_type') ? old('pickup_delivery.car_type') : (isset(getContactInfoCookie()->pickup_delivery->car_type) ? getContactInfoCookie()->pickup_delivery->car_type : '') }}"
                                                   name="pickup_delivery[car_type]"
                                                   placeholder="{{__('user::frontend.addresses.form.car_type')}}"
                                                   autocomplete="off"
                                                   class="input-text">
                                        </p>
                                    </div>

                                    <div class="col-md-4">
                                        <p class="form-row">
                                            <label>{{__('user::frontend.addresses.form.car_number')}}</label>
                                            <input type="text"
                                                   value="{{ old('pickup_delivery.car_number') ? old('pickup_delivery.car_number') : (isset(getContactInfoCookie()->pickup_delivery->car_number) ? getContactInfoCookie()->pickup_delivery->car_number : '') }}"
                                                   name="pickup_delivery[car_number]"
                                                   placeholder="{{__('user::frontend.addresses.form.car_number')}}"
                                                   autocomplete="off"
                                                   class="input-text">
                                        </p>
                                    </div>

                                    <div class="col-md-4">
                                        <p class="form-row">
                                            <label>{{__('user::frontend.addresses.form.car_color')}}</label>
                                            <input type="text"
                                                   value="{{ old('pickup_delivery.car_color') ? old('pickup_delivery.car_color') : (isset(getContactInfoCookie()->pickup_delivery->car_color) ? getContactInfoCookie()->pickup_delivery->car_color : '') }}"
                                                   name="pickup_delivery[car_color]"
                                                   placeholder="{{__('user::frontend.addresses.form.car_color')}}"
                                                   autocomplete="off"
                                                   class="input-text">
                                        </p>
                                    </div>
                                @endif

                                <div class="col-md-12">
                                    <p class="form-row">
                                        <label>{{__('user::frontend.addresses.form.additional_instructions')}}</label>
                                        <input type="text"
                                               value="{{ old('address') ? old('address') : (getContactInfoCookie() ? getContactInfoCookie()->address : '') }}"
                                               name="address"
                                               id="txtAddress"
                                               placeholder="{{__('user::frontend.addresses.form.additional_instructions')}}"
                                               autocomplete="off"
                                               class="input-text">
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="delivery-option">
                            <h3>{{ __('catalog::frontend.checkout.index.payments') }}</h3>
                            <div class="mb-20">
                                @foreach($paymentMethods as $k => $payment)
                                    <div class="checkboxes radios inline-block">
                                        <input id="payment-{{ $payment->id }}" type="radio" value="{{$payment->code}}"
                                               name="payment" {{ old('payment') == $payment->code ? 'checked' : '' }}>
                                        <label for="payment-{{ $payment->id }}">
                                            {{ $payment->translate(locale())->title}}
                                        </label>
                                        @if($payment->code == 'cash')
                                            <img
                                                src="{{ url('frontend/images/cash-payment.png') }}"
                                                alt="cash payments" style="height: 40px">
                                        @else
                                            <img
                                                src="{{ url('frontend/images/online-payment.jpg') }}"
                                                alt="online payments">
                                        @endif
                                    </div>
                                @endforeach
                            </div>

                            <h3>{{ __('catalog::frontend.checkout.index.shipping_time.title') }}</h3>
                            <div class="mb-20">
                                <div class="checkboxes radios inline-block">
                                    <input id="check-04" type="radio" name="shipping_time"
                                           onclick="changeShippingTime('now')"
                                           value="now" {{ old('shipping_time') != 'later' ? 'checked' : '' }}>
                                    <label
                                        for="check-04">{{ __('catalog::frontend.checkout.index.shipping_time.now') }}</label>
                                </div>
                                <div class="checkboxes radios inline-block">
                                    <input id="check-00" type="radio" name="shipping_time"
                                           onclick="changeShippingTime('later')"
                                           value="later" {{ old('shipping_time') == 'later' ? 'checked' : '' }}>
                                    <label
                                        for="check-00">{{ __('catalog::frontend.checkout.index.shipping_time.later') }}</label>
                                </div>
                            </div>

                            <div class="choose-date" id="shippingTimeInfo"
                                 style="{{ old('shipping_time') == 'later' ? '':'display: none;' }}">
                                <div class="row">
                                    <div class="col-md-6 col-xs-6">
                                        <div class="ch-d">
                                            <h6>{{ __('catalog::frontend.checkout.index.shipping_time.choose_day') }}</h6>
                                            <select name="shipping_day">
                                                @foreach(getDays() as $i => $value)
                                                    <option
                                                        value="{{ $i }}" {{ old('shipping_day') == $i ? 'selected':'' }}>{{ $value }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-xs-6">
                                        <div class="ch-d">
                                            <h6>{{ __('catalog::frontend.checkout.index.shipping_time.choose_time') }}</h6>
                                            <select name="shipping_hour">
                                                @for($i=0; $i < 24; $i++)
                                                    <option
                                                        value="{{ $i }}" {{ old('shipping_hour') == $i ? 'selected':'' }}>{{ $i == 0 ? '00:00': date('H:i', strtotime("+1 hour", strtotime($i-1 . ':00'))) }}</option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn button-submit ch-sub">
                            {{ __('catalog::frontend.checkout.index.confirm_order') }}
                        </button>
                    </div>
                </div>
            </form>

        </div>
    </div>

@endsection

@section('externalJs')

    <script>

        function onStateChanged(val) {
            $('#selectedStateFromAddress').val(val);
            getDeliveryPriceOnStateChanged($('#selectedStateFromAddress').val());
        }

        function checkoutSelectCompany(vendorId, companyId) {
            var thisID = '#checkVendorCompany-' + vendorId + '-' + companyId;
            var stateId = $('#selectedStateFromAddress').val();

            // START TO make radio button selected
            $(`.check-${vendorId}`).prop('checked', false);
            $('.vendor-company-' + vendorId + '-' + companyId).toggleClass("cut-radio-style");
            $(`.checkout-company-${vendorId}:not(${thisID})`).removeClass("cut-radio-style");
            // END TO make radio button selected

            if ($('#checkVendorCompany-' + vendorId + '-' + companyId).attr('data-state') == 0) {
                $('.checkout-company-' + vendorId).attr('data-state', 0);
                $('#checkVendorCompany-' + vendorId + '-' + companyId).attr('data-state', 1);
                // $(`.checkout-company:not(${thisID})`).attr('data-state', 0);
                $("input[name='vendor_company[" + vendorId + "]']").val(companyId);

                getStateDeliveryPrice(vendorId, companyId, stateId, 'checked');

            } else {
                $('.checkout-company-' + vendorId).attr('data-state', 0);
                $("input[name='vendor_company[" + vendorId + "]']").val('');
                getStateDeliveryPrice(vendorId, companyId, stateId, 'un_checked');
            }

        }

        function chooseCompanyDeliveryDay(companyId, dayCode) {

            // $('.day-block-company').not('.deliveryDay-' + dayCode).removeClass('active');
            // $('.deliveryDay-' + dayCode).toggleClass("active");
            // console.log('toggle::', $('.deliveryDay-' + dayCode).toggleClass("active"));

            $(this).toggleClass("active");

            if ($('.deliveryDay-' + dayCode).attr('data-state-value') == 0) {
                $('.day-block-company').attr('data-state-value', 0);
                $('.deliveryDay-' + dayCode).attr('data-state-value', 1);
                $("input[name='shipping_company[day]']").val(dayCode);
            } else {
                $('.day-block-company').attr('data-state-value', 0);
                $("input[name='shipping_company[day]']").val('');
            }

        }

        function getStateDeliveryPrice(vendorId, companyId, stateId, type) {
            var data = {
                'vendor_id': vendorId,
                'company_id': companyId,
                'state_id': stateId,
                'type': type,
            };
            getDeliveryPrice(data, stateId, type, vendorId, companyId);
        }

        function getDeliveryPriceOnStateChanged(stateId, addressId = null) {
            var type = 'selected_state',
                data = {
                    'state_id': stateId,
                    'address_id': addressId,
                    'company_id': $("input[name='shipping_company[id]']").val(),
                    'type': type,
                };
            getDeliveryPrice(data, stateId, type);
        }

        function getDeliveryPrice(data, stateId, type, vendorId = null, companyId = null) {

            $('#deliveryPriceLoaderDiv').show();
            var deliveryPriceRow;

            $.ajax({
                    method: "GET",
                    url: '{{ route('frontend.checkout.get_state_delivery_price') }}',
                    data: data,
                    beforeSend: function () {
                    },
                    success: function (data) {
                        var totalCompaniesDeliveryPrice = $('#totalCompaniesDeliveryPrice');

                        if (type === 'selected_state') {

                            $('.checkedCompanyInput').prop('checked', false);
                            $('.checkedCompany').removeClass("cut-radio-style");
                            $('.checkedCompany').attr('data-state', 0);
                            $(".vendor-company-input").val('');

                            deliveryPriceRow = `
                                <div class="d-flex margin-bottom-20 align-items-center mb-3">
                                    <span class="d-inline-block right-side flex-1"> {{ __('catalog::frontend.checkout.shipping') }}</span>
                                    <span class="d-inline-block left-side"
                                          id="totalDeliveryPrice">${data.data.totalDeliveryPrice} {{ __('apps::frontend.master.kwd') }}</span>
                                </div>
                                `;
                            totalCompaniesDeliveryPrice.html(deliveryPriceRow);

                        } else {

                            if (data.data.price != null) {
                                deliveryPriceRow = `
                                <div class="d-flex margin-bottom-20 align-items-center mb-3">
                                    <span class="d-inline-block right-side flex-1"> {{ __('catalog::frontend.checkout.shipping') }}</span>
                                    <span class="d-inline-block left-side"
                                          id="totalDeliveryPrice">${data.data.totalDeliveryPrice} {{ __('apps::frontend.master.kwd') }}</span>
                                </div>
                                `;
                                totalCompaniesDeliveryPrice.html(deliveryPriceRow);
                            }

                        }

                    },
                    error: function (data) {
                        $('#deliveryPriceLoaderDiv').hide();
                        // $('#btnCheckoutSaveInformation').show();
                        displayErrorsMsg(data);

                        var getJSON = $.parseJSON(data.responseText);

                        if (getJSON.data.price == null) {

                            if (type !== 'selected_state') {
                                $('#check-vendor-company-' + vendorId + '-' + companyId).prop('checked', false);
                                $('.checkout-company-' + vendorId).removeClass("cut-radio-style");
                                $("input[name='vendor_company[" + vendorId + "]']").val('');
                            }

                            var totalCompaniesDeliveryPrice = $('#totalCompaniesDeliveryPrice');
                            deliveryPriceRow = `
                                <div class="d-flex margin-bottom-20 align-items-center mb-3">
                                    <span class="d-inline-block right-side flex-1"> {{ __('catalog::frontend.checkout.shipping') }}</span>
                                    <span class="d-inline-block left-side"
                                          id="totalDeliveryPrice">${data.data.totalDeliveryPrice} {{ __('apps::frontend.master.kwd') }}</span>
                                </div>
                                `;
                            totalCompaniesDeliveryPrice.html(deliveryPriceRow);
                        }
                    },
                    complete: function (data) {
                        $('#deliveryPriceLoaderDiv').hide();
                        var getJSON = $.parseJSON(data.responseText);
                        if (getJSON.data) {
                            $('#cartTotalAmount').html(getJSON.data.total + " {{ __('apps::frontend.master.kwd') }}");
                        }
                    },
                }
            );
        }

        function changeShippingTime(value) {
            if (value === 'later') {
                $('#shippingTimeInfo').show();
            } else {
                $('#shippingTimeInfo').hide();
            }
        }

    </script>

@endsection
