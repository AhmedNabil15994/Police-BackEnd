@extends('apps::dashboard.layouts.app')
@section('title', __('vendor::dashboard.vendors.create.title'))
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
                        <a href="{{ url(route('dashboard.vendors.index')) }}">
                            {{__('vendor::dashboard.vendors.index.title')}}
                        </a>
                        <i class="fa fa-circle"></i>
                    </li>
                    <li>
                        <a href="#">{{__('vendor::dashboard.vendors.create.title')}}</a>
                    </li>
                </ul>
            </div>

            <h1 class="page-title"></h1>

            <div class="row">
                <form id="form" role="form" class="form-horizontal form-row-seperated" method="post"
                      enctype="multipart/form-data" action="{{route('dashboard.vendors.store')}}">
                    @csrf
                    <div class="col-md-12">

                        {{-- RIGHT SIDE --}}
                        <div class="col-md-3">
                            <div class="panel-group accordion scrollable" id="accordion2">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title"><a class="accordion-toggle"></a></h4>
                                    </div>
                                    <div id="collapse_2_1" class="panel-collapse in">
                                        <div class="panel-body">
                                            <ul class="nav nav-pills nav-stacked">
                                                <li class="active">
                                                    <a href="#global_setting" data-toggle="tab">
                                                        {{ __('vendor::dashboard.vendors.create.form.general') }}
                                                    </a>
                                                </li>

                                                <li>
                                                    <a href="#other" data-toggle="tab">
                                                        {{ __('vendor::dashboard.vendors.create.form.other') }}
                                                    </a>
                                                </li>

                                                {{--<li>
                                                        <a href="#companies" data-toggle="tab">
                                                            {{ __('vendor::dashboard.vendors.create.form.companies_and_states') }}
                                                </a>
                                                </li>--}}

                                                <li>
                                                    <a href="#seo" data-toggle="tab">
                                                        {{ __('vendor::dashboard.vendors.create.form.seo') }}
                                                    </a>
                                                </li>

                                            </ul>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        {{-- PAGE CONTENT --}}
                        <div class="col-md-9">
                            <div class="tab-content">

                                {{-- CREATE FORM --}}
                                <div class="tab-pane active fade in" id="global_setting">
                                    <h3 class="page-title">{{__('vendor::dashboard.vendors.create.form.general')}}</h3>
                                    <div class="col-md-10">

                                        {{--  tab for lang --}}
                                        <ul class="nav nav-tabs">
                                            @foreach (config('translatable.locales') as $code)
                                                <li class="@if($loop->first) active @endif">
                                                    <a data-toggle="tab"
                                                       href="#first_{{$code}}">{{__('catalog::dashboard.products.form.tabs.input_lang',["lang"=>$code])}}</a>
                                                </li>
                                            @endforeach
                                        </ul>

                                        {{--  tab for content --}}
                                        <div class="tab-content">

                                            @foreach (config('translatable.locales') as $code)
                                                <div id="first_{{$code}}"
                                                     class="tab-pane fade @if($loop->first) in active @endif">

                                                    <div class="form-group">
                                                        <label class="col-md-2">
                                                            {{__('vendor::dashboard.vendors.create.form.title')}}
                                                            - {{ $code }}
                                                        </label>
                                                        <div class="col-md-9">
                                                            <input type="text" name="title[{{$code}}]"
                                                                   class="form-control"
                                                                   data-name="title.{{$code}}">
                                                            <div class="help-block"></div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="col-md-2">
                                                            {{__('vendor::dashboard.vendors.create.form.description')}}
                                                            - {{ $code }}
                                                        </label>
                                                        <div class="col-md-9">
                                                    <textarea name="description[{{$code}}]" rows="8" cols="80"
                                                              class="form-control {{is_rtl($code)}}Editor"
                                                              data-name="description.{{$code}}"></textarea>
                                                            <div class="help-block"></div>
                                                        </div>
                                                    </div>

                                                </div>
                                            @endforeach

                                        </div>

                                        {{--<div class="form-group">
                                            <label class="col-md-2">
                                                {{__('vendor::dashboard.vendors.create.form.is_main_branch')}}
                                            </label>
                                            <div class="col-md-9">
                                                <input type="checkbox" class="make-switch" data-size="small"
                                                       name="is_main_branch">
                                                <div class="help-block"></div>
                                            </div>
                                        </div>--}}

                                        <div class="form-group">
                                            <label class="col-md-2">
                                                {{__('vendor::dashboard.vendors.create.form.status')}}
                                            </label>
                                            <div class="col-md-9">
                                                <input type="checkbox" class="make-switch" id="test" data-size="small"
                                                       name="status">
                                                <div class="help-block"></div>
                                            </div>
                                        </div>

                                        {{--<div class="form-group">
                                            <label class="col-md-2">
                                                {{__('vendor::dashboard.vendors.create.form.is_trusted')}}
                                            </label>
                                            <div class="col-md-9">
                                                <input type="checkbox" class="make-switch" id="test" data-size="small"
                                                       name="is_trusted">
                                                <div class="help-block"></div>
                                            </div>
                                        </div>--}}

                                        <div class="form-group">
                                            <label class="col-md-2">
                                                {{__('vendor::dashboard.restaurants.create.form.enable_pickup')}}
                                            </label>
                                            <div class="col-md-9">
                                                <input type="checkbox" class="make-switch" data-size="small"
                                                       name="enable_pickup">
                                                <div class="help-block"></div>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <div class="tab-pane fade in" id="other">
                                    <h3 class="page-title">{{__('vendor::dashboard.vendors.create.form.other')}}</h3>
                                    <div class="col-md-10">

                                        {{--<div class="form-group">
                                                <label class="col-md-2">
                                                    MyFatorah Supplier Code
                                                </label>
                                                <div class="col-md-9">
                                                    <input type="text" name="supplier_code_myfatorah" class="form-control"
                                                           data-name="supplier_code_myfatorah">
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>--}}

                                        <div class="form-group">
                                            <label class="col-md-2">
                                                {{__('vendor::dashboard.vendors.create.form.restaurant')}}
                                            </label>
                                            <div class="col-md-9">
                                                <select name="restaurant_id" data-name="restaurant_id"
                                                        class="form-control select2-allow-clear">
                                                    <option value=""></option>
                                                    @foreach ($restaurants as $item)
                                                        <option value="{{ $item['id'] }}">
                                                            {{ $item->translate(locale())->title }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <div class="help-block"></div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-2">
                                                {{__('vendor::dashboard.vendors.create.form.sections')}}
                                            </label>
                                            <div class="col-md-9">
                                                <select name="section_id[]" id="single"
                                                        class="form-control select2-allow-clear"
                                                        multiple>
                                                    <option value=""></option>
                                                    @foreach ($sections as $section)
                                                        <option value="{{ $section['id'] }}">
                                                            {{ $section->translate(locale())->title }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-2">
                                                {{__('vendor::dashboard.vendors.create.form.sellers')}}
                                            </label>
                                            <div class="col-md-9">
                                                <select name="seller_id[]" id="single"
                                                        class="form-control select2-allow-clear"
                                                        multiple>
                                                    <option value=""></option>
                                                    @foreach ($sellers as $seller)
                                                        <option value="{{ $seller['id'] }}">
                                                            {{ $seller['name'] }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-2">
                                                {{__('vendor::dashboard.vendors.create.form.vendor_statuses')}}
                                            </label>
                                            <div class="col-md-9">
                                                <select name="vendor_status_id" id="single"
                                                        class="form-control select2-allow-clear">
                                                    <option value=""></option>
                                                    @foreach ($vendorStatuses as $vendorStatus)
                                                        <option value="{{ $vendorStatus['id'] }}">
                                                            {{ $vendorStatus->translate(locale())->title }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-2">
                                                {{__('vendor::dashboard.vendors.create.form.commission')}} %
                                            </label>
                                            <div class="col-md-9">
                                                <input type="text" name="commission" class="form-control"
                                                       data-name="commission">
                                                <div class="help-block"></div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-2">
                                                {{__('vendor::dashboard.vendors.create.form.fixed_commission')}}
                                            </label>
                                            <div class="col-md-9">
                                                <input type="text" name="fixed_commission" class="form-control"
                                                       data-name="fixed_commission">
                                                <div class="help-block"></div>
                                            </div>
                                        </div>

                                        {{--<div class="form-group">
                                                <label class="col-md-2">
                                                    {{__('vendor::dashboard.vendors.create.form.payments')}}
                                            </label>
                                            <div class="col-md-9">
                                                <div class="mt-checkbox-list">
                                                    @foreach ($payments as $payment)
                                                    <label class="mt-checkbox">
                                                        <input type="checkbox" name="payment_id[]" value="{{$payment->id}}">
                                                        <img src="{{ url($payment->image) }}" alt="" style="width: 26px;">
                                                        {{ $payment->translate(locale())->title }}
                                                        <span></span>
                                                    </label>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>--}}

                                        <div class="form-group">
                                            <label class="col-md-2">
                                                {{__('vendor::dashboard.vendors.create.form.order_limit')}}
                                            </label>
                                            <div class="col-md-9">
                                                <input type="text" name="order_limit" class="form-control"
                                                       data-name="order_limit">
                                                <div class="help-block"></div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-2">
                                                {{__('vendor::dashboard.vendors.create.form.fixed_delivery')}}
                                            </label>
                                            <div class="col-md-9">
                                                <input type="text" name="fixed_delivery" class="form-control"
                                                       data-name="fixed_delivery">
                                                <div class="help-block"></div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-2">
                                                {{__('vendor::dashboard.vendors.create.form.image')}}
                                            </label>
                                            <div class="col-md-9">
                                                <div class="input-group">
                                            <span class="input-group-btn">
                                                <a data-input="image" data-preview="holder" class="btn btn-primary lfm">
                                                    <i class="fa fa-picture-o"></i>
                                                    {{__('apps::dashboard.general.upload_btn')}}
                                                </a>
                                            </span>
                                                    <input name="image" class="form-control image" type="text" readonly>
                                                    <span class="input-group-btn">
                                                <a data-input="image" data-preview="holder"
                                                   class="btn btn-danger delete">
                                                    <i class="glyphicon glyphicon-remove"></i>
                                                </a>
                                            </span>
                                                </div>
                                                <span class="holder" style="margin-top:15px;max-height:100px;">
                                        </span>
                                                <input type="hidden" data-name="image">
                                                <div class="help-block"></div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-2">
                                                {{__('vendor::dashboard.vendors.create.form.vendor_email')}}
                                            </label>
                                            <div class="col-md-9">
                                                <input type="text" name="vendor_email" class="form-control"
                                                       data-name="vendor_email">
                                                <div class="help-block"></div>
                                            </div>
                                        </div>

                                        {{--<div class="form-group">
                                                    <label class="col-md-2">
                                                        {{__('vendor::dashboard.vendors.create.form.receive_question')}}
                                        </label>
                                        <div class="col-md-9">
                                            <input type="checkbox" class="make-switch" id="test" data-size="small"
                                                name="receive_question">
                                            <div class="help-block"></div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2">
                                            {{__('vendor::dashboard.vendors.create.form.receive_prescription')}}
                                        </label>
                                        <div class="col-md-9">
                                            <input type="checkbox" class="make-switch" id="test" data-size="small"
                                                name="receive_prescription">
                                            <div class="help-block"></div>
                                        </div>
                                    </div>--}}
                                    </div>
                                </div>

                                {{--@include('vendor::dashboard.vendors._companies')--}}

                                <div class="tab-pane fade in" id="seo">
                                    <h3 class="page-title">{{__('vendor::dashboard.vendors.create.form.seo')}}</h3>
                                    <div class="col-md-10">


                                        {{--  tab for lang --}}
                                        <ul class="nav nav-tabs">
                                            @foreach (config('translatable.locales') as $code)
                                                <li class="@if($loop->first) active @endif">
                                                    <a data-toggle="tab"
                                                       href="#second_{{$code}}">{{__('catalog::dashboard.products.form.tabs.input_lang',["lang"=>$code])}}</a>
                                                </li>
                                            @endforeach
                                        </ul>

                                        {{--  tab for content --}}
                                        <div class="tab-content">

                                            @foreach (config('translatable.locales') as $code)
                                                <div id="second_{{$code}}"
                                                     class="tab-pane fade @if($loop->first) in active @endif">

                                                    <div class="form-group">
                                                        <label class="col-md-2">
                                                            {{__('vendor::dashboard.vendors.create.form.meta_keywords')}}
                                                            - {{ $code }}
                                                        </label>
                                                        <div class="col-md-9">
                                            <textarea name="seo_keywords[{{$code}}]" rows="8" cols="80"
                                                      class="form-control"
                                                      data-name="seo_keywords.{{$code}}"></textarea>
                                                            <div class="help-block"></div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="col-md-2">
                                                            {{__('vendor::dashboard.vendors.create.form.meta_description')}}
                                                            - {{ $code }}
                                                        </label>
                                                        <div class="col-md-9">
                                            <textarea name="seo_description[{{$code}}]" rows="8" cols="80"
                                                      class="form-control"
                                                      data-name="seo_description.{{$code}}"></textarea>
                                                            <div class="help-block"></div>
                                                        </div>
                                                    </div>

                                                </div>
                                            @endforeach

                                        </div>


                                    </div>
                                </div>

                                {{-- END CREATE FORM --}}
                            </div>
                        </div>

                        {{-- PAGE ACTION --}}
                        <div class="col-md-12">
                            <div class="form-actions">
                                @include('apps::dashboard.layouts._ajax-msg')
                                <div class="form-group">
                                    <button type="submit" id="submit" class="btn btn-lg blue">
                                        {{__('apps::dashboard.general.add_btn')}}
                                    </button>
                                    <a href="{{url(route('dashboard.vendors.index')) }}" class="btn btn-lg red">
                                        {{__('apps::dashboard.general.back_btn')}}
                                    </a>
                                </div>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
@stop
