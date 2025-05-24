@extends('apps::vendor.layouts.app')
@section('title', __('apps::vendor.home.title') )
@section('content')

    <div class="page-content-wrapper">
        <div class="page-content">

            <div class="page-bar">
                <ul class="page-breadcrumb">
                    <li>
                        <a href="{{ url(route('vendor.home')) }}">{{ __('apps::vendor.home.title') }}</a>
                    </li>
                </ul>
            </div>

            <h1 class="page-title"> {{ __('apps::vendor.home.welcome_message') }} ,
                <small><b style="color:red">{{ Auth::user()->name }} </b></small>
            </h1>


            <div class="portlet light portlet-fit ">
                <div class="portlet-title">
                    <div class="caption">
                        <i class=" icon-layers font-green"></i>
                        <span class="caption-subject font-green bold uppercase">
                        {{ __('apps::vendor.home.my_vendors') }}
                    </span>
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="row">
                        @foreach ($vendors as $vendor)
                            <div class="col-md-3">
                                <div class="mt-widget-2">
                                    <div class="mt-head" style="background-image: url({{ url($vendor->image) }});">
                                        <div class="mt-head-label">
                                            @if ($vendor->subscribed)
                                                <span class="label label-success">
                                                    {{ __('apps::vendor.home.subscriptions.active') }}
                                                </span>
                                            @else
                                                <span class="label label-danger">
                                                    {{ __('apps::vendor.home.subscriptions.unactive') }}
                                                </span>
                                            @endif
                                        </div>
                                        <div class="mt-head-user">
                                            <div class="mt-head-user-info">
                                                <span class="mt-user-name"></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mt-body" style="padding-top: 83px;">
                                        <h3 class="mt-body-title">
                                            <a href="{{ url(route('vendor.edit.info', $vendor->id)) }}">{{ $vendor->translate(locale())->title }}</a>
                                        </h3>
                                        @if ($vendor->subscribed)
                                            <p class="mt-body-description">
                                                {{ __('apps::vendor.home.subscriptions.end_at') }} :
                                                {{ $vendor->subbscription['end_at']  }}
                                            </p>
                                        @else
                                            <p class="mt-body-description" style="color: #ed6a75;">
                                                {{ __('apps::vendor.home.subscriptions.unactive') }}
                                                {{-- : {{ $vendor->subbscription['end_at']  }}--}}
                                            </p>
                                        @endif
                                        @if (!$vendor->subscribed)
                                            <div class="mt-body-actions">
                                                <div class="btn-group btn-group btn-group-justified">
                                                    <a href="javascript:;" class="btn ">
                                                        {{ __('apps::vendor.home.subscriptions.renew') }}
                                                    </a>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
