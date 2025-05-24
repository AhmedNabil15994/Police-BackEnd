@extends('apps::frontend.layouts.master')
@section('title', __('order::frontend.orders.invoice.title') )
@section('content')

    <div class="inner-page">
        <div class="container">
            <div class="title-top">
                <h3>{{ session('status') }}</h3>
            </div>
            <div class="row">
                <div class="col-md-4"></div>
                <div class="col-md-4">
                    <div class="or-summry">
                        <div class="d-flex align-items-center">
                            <div class="rside flex-1">
                                <h6>{{ __('order::frontend.orders.invoice.order_id') }}</h6>
                            </div>
                            <p class="lside">{{ $order->id }}</p>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="rside flex-1">
                                <h6>{{ __('order::frontend.orders.invoice.status') }}</h6>
                            </div>
                            <p class="lside">{{ $order->orderStatus->translate(locale())->title }}</p>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="rside flex-1">
                                <h6>{{ __('order::frontend.orders.invoice.total') }}</h6>
                            </div>
                            <p class="lside">
                                {{ $order->total }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4"></div>
            </div>
        </div>
    </div>


@endsection

@section('externalJs')

    <script></script>

@endsection
