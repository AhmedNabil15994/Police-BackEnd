@extends('apps::dashboard.layouts.app')
@section('title', __('vendor::dashboard.vendors.update.title'))
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
                        <a href="#">{{__('vendor::dashboard.vendors.update.title')}}</a>
                    </li>
                </ul>
            </div>

            <h1 class="page-title"></h1>

            <div class="row">
                <form id="updateForm" page="form" class="form-horizontal form-row-seperated" method="post"
                      enctype="multipart/form-data" action="{{route('dashboard.vendors.update',$vendor->id)}}">
                    @csrf
                    @method('PUT')
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
                                                        {{ __('vendor::dashboard.vendors.update.form.general') }}
                                                    </a>
                                                </li>

                                                <li>
                                                    <a href="#other" data-toggle="tab">
                                                        {{ __('vendor::dashboard.vendors.update.form.other') }}
                                                    </a>
                                                </li>

                                                {{--<li>
                                                        <a href="#companies" data-toggle="tab">
                                                            {{ __('vendor::dashboard.vendors.create.form.companies_and_states') }}
                                                </a>
                                                </li>--}}

                                                <li>
                                                    <a href="#seo" data-toggle="tab">
                                                        {{ __('vendor::dashboard.vendors.update.form.seo') }}
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

                                {{-- UPDATE FORM --}}
                                <div class="tab-pane active fade in" id="global_setting">
                                    <h3 class="page-title">{{__('vendor::dashboard.vendors.update.form.general')}}</h3>
                                    <div class="col-md-10">


                                        {{--  tab for lang --}}
                                        <ul class="nav nav-tabs">
                                            @foreach (config('translatable.locales') as $code)
                                                <li class="@if($loop->first) active @endif"><a data-toggle="tab"
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
                                                            {{__('vendor::dashboard.vendors.update.form.title')}}
                                                            - {{ $code }}
                                                        </label>
                                                        <div class="col-md-9">
                                                            <input type="text" name="title[{{$code}}]"
                                                                   class="form-control"
                                                                   data-name="title.{{$code}}"
                                                                   value="{{ $vendor->translate($code)->title }}">
                                                            <div class="help-block"></div>
                                                        </div>
                                                    </div>


                                                    <div class="form-group">
                                                        <label class="col-md-2">
                                                            {{__('vendor::dashboard.vendors.update.form.description')}}
                                                            - {{ $code }}
                                                        </label>
                                                        <div class="col-md-9">
                                                    <textarea name="description[{{$code}}]" rows="8" cols="80"
                                                              class="form-control {{is_rtl($code)}}Editor"
                                                              data-name="description.{{$code}}">{{ $vendor->translate($code)->description }}</textarea>
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
                                                       name="is_main_branch"
                                                    {{ $vendor->is_main_branch == 1 ? 'checked' : ''}}>
                                                <div class="help-block"></div>
                                            </div>
                                        </div>--}}

                                        <div class="form-group">
                                            <label class="col-md-2">
                                                {{__('vendor::dashboard.vendors.update.form.status')}}
                                            </label>
                                            <div class="col-md-9">
                                                <input type="checkbox" class="make-switch" id="test" data-size="small"
                                                       name="status" {{($vendor->status == 1) ? ' checked="" ' : ''}}>
                                                <div class="help-block"></div>
                                            </div>
                                        </div>

                                        {{--<div class="form-group">
                                            <label class="col-md-2">
                                                {{__('vendor::dashboard.vendors.update.form.is_trusted')}}
                                            </label>
                                            <div class="col-md-9">
                                                <input type="checkbox" class="make-switch" id="test" data-size="small"
                                                       name="is_trusted" {{($vendor->is_trusted == 1) ? ' checked="" ' : ''}}>
                                                <div class="help-block"></div>
                                            </div>
                                        </div>--}}

                                        <div class="form-group">
                                            <label class="col-md-2">
                                                {{__('vendor::dashboard.restaurants.update.form.enable_pickup')}}
                                            </label>
                                            <div class="col-md-9">
                                                <input type="checkbox" class="make-switch" data-size="small"
                                                       name="enable_pickup" {{ $vendor->enable_pickup == 1 ? ' checked="" ' : ''}}>
                                                <div class="help-block"></div>
                                            </div>
                                        </div>

                                        @if ($vendor->trashed())
                                            <div class="form-group">
                                                <label class="col-md-2">
                                                    {{__('area::dashboard.vendors.update.form.restore')}}
                                                </label>
                                                <div class="col-md-9">
                                                    <input type="checkbox" class="make-switch" id="test"
                                                           data-size="small"
                                                           name="restore">
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                        @endif

                                    </div>
                                </div>

                                <div class="tab-pane fade in" id="other">
                                    <h3 class="page-title">{{__('vendor::dashboard.vendors.update.form.other')}}</h3>
                                    <div class="col-md-10">

                                        {{--<div class="form-group">
                                                <label class="col-md-2">
                                                    MyFatorah Supplier Code
                                                </label>
                                                <div class="col-md-9">
                                                    <input type="text" name="supplier_code_myfatorah" class="form-control"
                                                           data-name="supplier_code_myfatorah"
                                                           value="{{ $vendor->supplier_code_myfatorah }}">
                                                <div class="help-block"></div>
                                            </div>
                                        </div>--}}

                                        <div class="form-group">
                                            <label class="col-md-2">
                                                {{__('vendor::dashboard.vendors.update.form.restaurant')}}
                                            </label>
                                            <div class="col-md-9">
                                                <select name="restaurant_id" data-name="restaurant_id"
                                                        class="form-control select2-allow-clear">
                                                    <option value=""></option>
                                                    @foreach ($restaurants as $item)
                                                        <option value="{{ $item['id'] }}"
                                                            {{ $vendor->parent_id == $item->id ? 'selected' : ''}}>
                                                            {{ $item->translate(locale())->title }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <div class="help-block"></div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-2">
                                                {{__('vendor::dashboard.vendors.update.form.sections')}}
                                            </label>
                                            <div class="col-md-9">
                                                <select name="section_id[]" id="single"
                                                        class="form-control select2-allow-clear"
                                                        multiple>
                                                    <option value=""></option>
                                                    @foreach ($sections as $section)
                                                        <option value="{{ $section['id'] }}"
                                                            {{ $vendor->sections->contains($section->id) ? 'selected=""' : ''}}>
                                                            {{ $section->translate(locale())->title }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-2">
                                                {{__('vendor::dashboard.vendors.update.form.sellers')}}
                                            </label>
                                            <div class="col-md-9">
                                                <select name="seller_id[]" id="single"
                                                        class="form-control select2-allow-clear"
                                                        multiple>
                                                    <option value=""></option>
                                                    @foreach ($sellers as $seller)
                                                        <option value="{{ $seller['id'] }}"
                                                            {{ $vendor->sellers->contains($seller->id) ? 'selected=""' : ''}}>
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
                                                        <option value="{{ $vendorStatus['id'] }}"
                                                                @if ($vendor->vendor_status_id == $vendorStatus['id'])
                                                                selected
                                                            @endif>
                                                            {{ $vendorStatus->translate(locale())->title }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-2">
                                                {{__('vendor::dashboard.vendors.update.form.commission')}} %
                                            </label>
                                            <div class="col-md-9">
                                                <input type="text" name="commission" class="form-control"
                                                       data-name="commission"
                                                       value="{{ $vendor->commission }}">
                                                <div class="help-block"></div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-2">
                                                {{__('vendor::dashboard.vendors.create.form.fixed_commission')}}
                                            </label>
                                            <div class="col-md-9">
                                                <input type="text" name="fixed_commission" class="form-control"
                                                       data-name="fixed_commission"
                                                       value="{{ $vendor->fixed_commission }}">
                                                <div class="help-block"></div>
                                            </div>
                                        </div>

                                        {{--<div class="form-group">
                                                        <label class="col-md-2">
                                                            {{__('vendor::dashboard.vendors.update.form.payments')}}
                                        </label>
                                        <div class="col-md-9">
                                            <div class="mt-checkbox-list">
                                                @foreach ($payments as $payment)
                                                    <label class="mt-checkbox">
                                                        <input type="checkbox" name="payment_id[]" value="{{$payment->id}}"
                                                            {{ $vendor->payments->contains($payment->id) ? 'checked=""' : ''}}>
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
                                                {{__('vendor::dashboard.vendors.update.form.order_limit')}}
                                            </label>
                                            <div class="col-md-9">
                                                <input type="text" name="order_limit" class="form-control"
                                                       data-name="order_limit"
                                                       value="{{ $vendor->order_limit }}">
                                                <div class="help-block"></div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-2">
                                                {{__('vendor::dashboard.vendors.create.form.fixed_delivery')}}
                                            </label>
                                            <div class="col-md-9">
                                                <input type="text" name="fixed_delivery" class="form-control"
                                                       data-name="fixed_delivery"
                                                       value="{{ $vendor->fixed_delivery }}">
                                                <div class="help-block"></div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-2">
                                                {{__('vendor::dashboard.vendors.update.form.image')}}
                                            </label>
                                            <div class="col-md-9">
                                                <div class="input-group">
                                                    <span class="input-group-btn">
                                                        <a data-input="image" data-preview="holder"
                                                           class="btn btn-primary lfm">
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
                                                    <img src="{{url($vendor->image)}}" style="height: 15rem;">
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
                                                       data-name="vendor_email"
                                                       value="{{ $vendor->vendor_email }}">
                                                <div class="help-block"></div>
                                            </div>
                                        </div>

                                        {{--<div class="form-group">
                                                            <label class="col-md-2">
                                                                {{__('vendor::dashboard.vendors.create.form.receive_question')}}
                                        </label>
                                        <div class="col-md-9">
                                            <input type="checkbox" class="make-switch" id="test" data-size="small"
                                                name="receive_question" {{($vendor->receive_question == 1) ? ' checked="" ' : ''}}>
                                            <div class="help-block"></div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2">
                                            {{__('vendor::dashboard.vendors.create.form.receive_prescription')}}
                                        </label>
                                        <div class="col-md-9">
                                            <input type="checkbox" class="make-switch" id="test" data-size="small"
                                                name="receive_prescription"
                                                {{($vendor->receive_prescription == 1) ? ' checked="" ' : ''}}>
                                            <div class="help-block"></div>
                                        </div>
                                    </div>--}}

                                    </div>
                                </div>

                                {{--@include('vendor::dashboard.vendors._companies')--}}

                                <div class="tab-pane fade in" id="seo">
                                    <h3 class="page-title">{{__('vendor::dashboard.vendors.update.form.seo')}}</h3>
                                    <div class="col-md-10">

                                        {{--  tab for lang --}}
                                        <ul class="nav nav-tabs">
                                            @foreach (config('translatable.locales') as $code)
                                                <li class="@if($loop->first) active @endif"><a data-toggle="tab"
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
                                                            {{__('vendor::dashboard.vendors.update.form.meta_keywords')}}
                                                            - {{ $code }}
                                                        </label>
                                                        <div class="col-md-9">
                                <textarea name="seo_keywords[{{$code}}]" rows="8" cols="80" class="form-control"
                                          data-name="seo_keywords.{{$code}}">{{ $vendor->translate($code)->seo_keywords }}</textarea>
                                                            <div class="help-block"></div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="col-md-2">
                                                            {{__('vendor::dashboard.vendors.update.form.meta_description')}}
                                                            - {{ $code }}
                                                        </label>
                                                        <div class="col-md-9">
                                <textarea name="seo_description[{{$code}}]" rows="8" cols="80" class="form-control"
                                          data-name="seo_description.{{$code}}">{{ $vendor->translate($code)->seo_description }}</textarea>
                                                            <div class="help-block"></div>
                                                        </div>
                                                    </div>

                                                </div>
                                            @endforeach

                                        </div>


                                    </div>
                                </div>

                                {{-- END UPDATE FORM --}}

                            </div>
                        </div>

                        {{-- PAGE ACTION --}}
                        <div class="col-md-12">
                            <div class="form-actions">
                                @include('apps::dashboard.layouts._ajax-msg')
                                <div class="form-group">
                                    <button type="submit" id="submit" class="btn btn-lg green">
                                        {{__('apps::dashboard.general.edit_btn')}}
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
