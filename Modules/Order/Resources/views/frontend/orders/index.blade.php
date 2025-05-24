@extends('apps::frontend.layouts.master')
@section('title', __('apps::frontend.master.my_orders') )
@section('content')

    <div class="second-header d-flex align-items-center">
        <div class="container">
            <h1>{{ __('user::frontend.profile.index.my_orders') }}</h1>
        </div>
    </div>
    <div class="inner-page">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    @include('user::frontend.profile._user-side-menu')
                </div>
                <div class="col-md-9">
                    <div class="">

                        @if(count($orders) > 0)
                            @foreach($orders as $k => $order)
                                <div class="product-item style-2 favorite-item">
                                    <div class="product-inner">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <div class="product-thumb">
                                                    <div class="thumb-inner">
                                                        <a href="javascript:;">
                                                            <img
                                                                src="{{ config('setting.logo') ? url(config('setting.logo')) : url('frontend/images/header-logo.png') }}"
                                                                alt="p8">
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="product-innfo">
                                                    <div class="product-name">
                                                        <a href="{{ route('frontend.orders.invoice', $order->id) }}">
                                                            {{ __('order::frontend.orders.invoice.order_id') }}:
                                                            # {{ $order->id }}
                                                        </a>
                                                    </div>
                                                    <ul>
                                                        <li>
                                                            <i class="ti-credit-card"></i>
                                                            {{ $order->total }} {{ __('apps::frontend.master.kwd') }}
                                                        </li>
                                                        <li><i class="ti-time"></i>{{ $order->created_at }}</li>
                                                    </ul>
                                                    <span class="order-status pending">
                                                        {{ $order->orderStatus->translate(locale())->title }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <b>{{ __('order::frontend.orders.invoice.no_data') }}</b>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('externalJs')

    <script></script>

@endsection
