@extends('apps::dashboard.layouts.app')
@section('title', __('setting::dashboard.settings.routes.index'))

@section('content')
    <div class="page-content-wrapper">
        <div class="page-content">
            <div class="page-bar">
                <ul class="page-breadcrumb">
                    <li>
                        <a href="{{ url(route('dashboard.home')) }}">{{ __('apps::dashboard.home.title') }}</a>
                        <i class="fa fa-circle"></i>
                    </li>
                    <li>
                        <a href="#">{{ __('setting::dashboard.settings.routes.index') }}</a>
                    </li>
                </ul>
            </div>

            <h1 class="page-title"></h1>

            @include('apps::dashboard.layouts._msg')

            <div class="row">
                <form role="form" class="form-horizontal form-row-seperated" method="post"
                      action="{{route('dashboard.setting.update')}}" enctype="multipart/form-data">
                    <div class="col-md-12">
                        @csrf
                        <div class="col-md-3">
                            <div class="panel-group accordion scrollable" id="accordion2">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a class="accordion-toggle">
                                                {{__('setting::dashboard.settings.form.tabs.info')}}
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapse_2_1" class="panel-collapse in">
                                        <div class="panel-body">
                                            <ul class="nav nav-pills nav-stacked">

                                                @permission('show_general_settings')
                                                <li class="active">
                                                    <a href="#global_setting" data-toggle="tab">
                                                        {{ __('setting::dashboard.settings.form.tabs.general') }}
                                                    </a>
                                                </li>
                                                @endpermission

                                                @permission('show_system_settings')
                                                <li>
                                                    <a href="#app" data-toggle="tab">
                                                        {{ __('setting::dashboard.settings.form.tabs.app') }}
                                                    </a>
                                                </li>
                                                @endpermission

                                                @permission('show_email_settings')
                                                <li>
                                                    <a href="#mail" data-toggle="tab">
                                                        {{ __('setting::dashboard.settings.form.tabs.mail') }}
                                                    </a>
                                                </li>
                                                @endpermission

                                                @permission('show_logo_settings')
                                                <li>
                                                    <a href="#logo" data-toggle="tab">
                                                        {{ __('setting::dashboard.settings.form.tabs.logo') }}
                                                    </a>
                                                </li>
                                                @endpermission

                                                @permission('show_social_settings')
                                                <li>
                                                    <a href="#social_media" data-toggle="tab">
                                                        {{ __('setting::dashboard.settings.form.tabs.social_media') }}
                                                    </a>
                                                </li>
                                                @endpermission

                                                @permission('show_products_settings')
                                                <li>
                                                    <a href="#products" data-toggle="tab">
                                                        {{ __('setting::dashboard.settings.form.tabs.products') }}
                                                    </a>
                                                </li>
                                                @endpermission

                                                {{--<li>
                                                    <a href="#order_status" data-toggle="tab">
                                                        {{ __('setting::dashboard.settings.form.tabs.order_status') }}
                                                    </a>
                                                </li>--}}

                                                @permission('show_custom_codes_settings')
                                                <li>
                                                    <a href="#custom_codes" data-toggle="tab">
                                                        {{ __('setting::dashboard.settings.form.tabs.custom_codes') }}
                                                    </a>
                                                </li>
                                                @endpermission

                                                @permission('show_activation_settings')
                                                <li>
                                                    <a href="#toggle_modules" data-toggle="tab">
                                                        {{ __('setting::dashboard.settings.form.tabs.toggle_modules') }}
                                                    </a>
                                                </li>
                                                @endpermission

                                                @permission('show_about_app_settings')
                                                <li>
                                                    <a href="#about_app" data-toggle="tab">
                                                        {{ __('setting::dashboard.settings.form.tabs.about_app') }}
                                                    </a>
                                                </li>
                                                @endpermission

                                                @permission('show_payment_gateway_settings')
                                                <li>
                                                    <a href="#payment_gateway" data-toggle="tab">
                                                        {{ __('setting::dashboard.settings.form.tabs.payment_gateway') }}
                                                    </a>
                                                </li>
                                                @endpermission

                                                @permission('show_other_settings')
                                                <li>
                                                    <a href="#other" data-toggle="tab">
                                                        {{ __('setting::dashboard.settings.form.tabs.other') }}
                                                    </a>
                                                </li>
                                                @endpermission

                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="tab-content">

                                @permission('show_general_settings')
                                @include('setting::dashboard.tabs.general')
                                @endpermission

                                @permission('show_system_settings')
                                @include('setting::dashboard.tabs.app')
                                @endpermission

                                @permission('show_email_settings')
                                @include('setting::dashboard.tabs.mail')
                                @endpermission

                                @permission('show_logo_settings')
                                @include('setting::dashboard.tabs.logo')
                                @endpermission

                                @permission('show_social_settings')
                                @include('setting::dashboard.tabs.social')
                                @endpermission

                                @permission('show_products_settings')
                                @include('setting::dashboard.tabs.products')
                                @endpermission

                                {{--@include('setting::dashboard.tabs.order_status')--}}

                                @permission('show_custom_codes_settings')
                                @include('setting::dashboard.tabs.custom_codes')
                                @endpermission

                                @permission('show_activation_settings')
                                @include('setting::dashboard.tabs.toggle_modules')
                                @endpermission

                                @permission('show_about_app_settings')
                                @include('setting::dashboard.tabs.about_app')
                                @endpermission

                                @permission('show_payment_gateway_settings')
                                @include('setting::dashboard.tabs.payment_gateway')
                                @endpermission

                                @permission('show_other_settings')
                                @include('setting::dashboard.tabs.other')
                                @endpermission

                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-offset-2 col-md-9">
                                <button type="submit" id="submit" class="btn btn-lg blue">
                                    {{__('apps::dashboard.general.edit_btn')}}
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('scripts')

    <script>
        var selectVendorRow = $('#selectVendorRow');
        var selectVendorLoader = $('#selectVendorLoader');

        var sideMenuVendorsHeadTitle = $('#sideMenuVendorsHeadTitle');
        var sideMenuVendorsSeller = $('#sideMenuVendorsSeller');
        var sideMenuVendors = $('#sideMenuVendors');
        var sideMenuVendorsSections = $('#sideMenuVendorsSections');
        var sideMenuReviewProducts = $('#sideMenuReviewProducts');
        var sideMenuRestaurants = $('#sideMenuRestaurants');

        var toggleAddNewRestaurant = $('#toggleAddNewRestaurant');

        @if(config('setting.other.is_multi_vendors') == 0)
        getAllActiveVendors();
        @endif

        $('input[name="other[is_multi_vendors]"]').change(function () {
            var value = $(this).val();
            // console.log('value:::', value);
            if (value == 1) {
                /*selectVendorRow.hide();
                selectVendorLoader.hide();

                sideMenuVendorsHeadTitle.show();
                sideMenuVendorsSeller.show();
                sideMenuVendors.show();
                sideMenuVendorsSections.show();
                sideMenuReviewProducts.show();
                sideMenuRestaurants.show();
                toggleAddNewRestaurant.show();*/
            } else {
                $('#selectVendors').empty();
                getAllActiveVendors();

                /*sideMenuVendorsHeadTitle.hide();
                sideMenuVendorsSeller.hide();
                sideMenuVendors.hide();
                sideMenuVendorsSections.hide();
                sideMenuReviewProducts.hide();
                sideMenuRestaurants.hide();
                toggleAddNewRestaurant.hide();*/
            }
        });

        function getAllActiveVendors() {

            selectVendorLoader.show();

            $.ajax({
                url: "{{route('dashboard.vendors.get_all_active_vendors')}}",
                type: 'get',
                dataType: 'JSON',
                contentType: false,
                cache: false,
                processData: false,

                beforeSend: function () {

                },
                success: function (data) {

                    if (data[0] == true) {

                        selectVendorLoader.hide();
                        // console.log('data.data::', data.data);
                        var vendorID = "{{config('setting.default_vendor')}}";
                        // console.log('vendorID::', vendorID);
                        $.each(data.data, function (i, item) {
                            $('#selectVendors').append(`
                                <option value="${item.id}"
                                    ${item.id == vendorID ? 'selected' : ''}>
                                    ${item.title}
                                </option>`);
                        });

                        selectVendorRow.show();

                        /* Toggle Vendors Containers Based On Single OR Multi-Vendors */
                    }

                },
                error: function (data) {
                    displayErrors(data);
                }
                ,
            });

        }

        $('input[name="payment_gateway[upayment][payment_mode]"], input[name="payment_gateway[cbk_payment][payment_mode]"]').change(function () {
            var value = $(this).val();
            if (value == 'test_mode') {
                $('#testModelData').show();
                $('#liveModelData').hide();
            } else {
                $('#testModelData').hide();
                $('#liveModelData').show();
            }
        });

    </script>

@stop
