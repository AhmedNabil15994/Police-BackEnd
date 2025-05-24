@extends('apps::frontend.layouts.master')
@section('title', __('order::frontend.orders.invoice.details_title') )
@section('content')

    <div class="inner-page">
        <div class="container">
            <div class="title-top">
                <h3>{{ __('order::frontend.orders.index.details') }}</h3>
            </div>
            <div class="row">
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
                                <h6>{{ __('order::frontend.orders.invoice.date') }}</h6>
                            </div>
                            <p class="lside">{{ $order->created_at }}</p>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="rside flex-1">
                                <h6>{{ __('order::frontend.orders.invoice.method') }}</h6>
                            </div>
                            <p class="lside">
                                @if($order->transactions->method == 'cash')
                                    {{ __('order::frontend.orders.invoice.cash') }}
                                @else
                                    {{ __('order::frontend.orders.invoice.online') }}
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4"></div>
                <div class="col-md-4">
                    <div class="or-summry">

                        @if(!is_null($order->orderAddress->state))
                            <div class="d-flex align-items-center">
                                <div class="rside flex-1">
                                    <h6>{{ __('order::frontend.orders.invoice.client_address.state') }}</h6>
                                </div>
                                <p class="lside">{{ !is_null($order->orderAddress->state) ? $order->orderAddress->state->translate(locale())->title : '' }}</p>
                            </div>
                        @endif

                        <div class="d-flex align-items-center">
                            <div class="rside flex-1">
                                <h6>
                                    {{ __('order::frontend.orders.invoice.client_address.block') }}
                                    / {{ __('order::frontend.orders.invoice.client_address.building') }}
                                </h6>
                            </div>
                            <p class="lside">{{ $order->orderAddress->block ?? '---' }}
                                / {{ $order->orderAddress->building ?? '---' }}</p>
                        </div>

                        <div class="d-flex align-items-center">
                            <div class="rside flex-1">
                                <h6>{{ __('order::frontend.orders.invoice.client_address.mobile') }}</h6>
                            </div>
                            <p class="lside">
                                {{ $order->orderAddress->mobile ?? '---' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <h5>{{ __('order::frontend.orders.index.products') }}</h5>
                    <table class="table" width="50%">
                        <thead>
                        <tr>
                            <th class="text-left">#</th>
                            <th class="text-left">{{ __('order::frontend.orders.invoice.product_title') }}</th>
                            <th class="text-left">{{ __('order::frontend.orders.invoice.product_qty') }}</th>
                            <th class="text-left">{{ __('order::frontend.orders.invoice.product_price') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(count($order->orderProducts) > 0)
                            @foreach ($order->orderProducts as $key => $orderProduct)
                                <tr class="{{ ++$key % 2 == 0 ? 'even' : '' }} text-left">
                                    <td>{{ $key }}</td>
                                    @if(isset($orderProduct->product_variant_id) && !empty($orderProduct->product_variant_id))
                                        <td>
                                            {{ generateVariantProductData($orderProduct->variant->product, $orderProduct->product_variant_id, $orderProduct->variant->productValues->pluck('option_value_id')->toArray())['name'] }}
                                        </td>
                                    @else
                                        <td>
                                            {{ $orderProduct->product->translate(locale())->title }}
                                            @if(!is_null($orderProduct->add_ons_option_ids) && !empty($orderProduct->add_ons_option_ids))
                                                <hr>
                                                <ol>
                                                    @foreach(json_decode($orderProduct->add_ons_option_ids)->data as $key => $addons)
                                                        <li style="margin: 15px;">
                                                            <b># {{ getAddonsTitle($addons->id) }}</b>
                                                            <ul>
                                                                @foreach($addons->options as $k => $option)
                                                                    <li>- {{ getAddonsOptionTitle($option) }}</li>
                                                                @endforeach
                                                            </ul>
                                                        </li>
                                                    @endforeach
                                                </ol>
                                                <b style="margin: 15px;">{{ json_decode($orderProduct->add_ons_option_ids)->total_amount }} {{ __('apps::frontend.master.kwd') }}</b>
                                            @endif
                                        </td>
                                    @endif
                                    <td>{{ $orderProduct->qty }}</td>
                                    <td>
                                        {{ $orderProduct->price }} {{ __('apps::frontend.master.kwd') }}
                                    </td>
                                </tr>

                            @endforeach
                        @endif
                        </tbody>
                    </table>

                    <table class="table border">
                        <tr>
                            <th><span>{{ __('order::frontend.orders.invoice.subtotal') }}</span></th>
                            <td>
                                <span>{{ $order->subtotal }}</span>
                                <span data-prefix>{{ __('apps::frontend.master.kwd') }}</span>
                            </td>
                        </tr>
                        <tr>
                            <th><span>{{ __('order::frontend.orders.invoice.shipping') }}</span></th>
                            <td>
                                <span>{{ $order->shipping }}</span>
                                <span data-prefix>{{ __('apps::frontend.master.kwd') }}</span>
                            </td>
                        </tr>
                        <tr class="price">
                            <th><span>{{ __('order::frontend.orders.invoice.total') }}</span></th>
                            <td>
                                <span>{{ $order->total }}</span>
                                <span data-prefix>{{ __('apps::frontend.master.kwd') }}</span>
                            </td>
                        </tr>
                    </table>

                </div>
            </div>

            @if(!is_null($order->shipping_time))
                <hr>
                <div class="row text-center">
                    <h5>{{__('order::dashboard.orders.show.other.shipping_time')}}</h5>
                    <div class="col-xs-12 table-responsive">
                        <ul>
                            <li style="margin: 15px;">
                                <b>{{__('order::dashboard.orders.show.other.shipping_time')}} : </b>
                                <span>{{ __('catalog::frontend.checkout.index.shipping_time.' . json_decode($order->shipping_time)->shipping_time) }}</span>
                            </li>

                            @if(json_decode($order->shipping_time)->shipping_time == 'later')
                                <li style="margin: 15px;">
                                    <b>{{__('order::dashboard.orders.show.other.shipping_day')}} : </b>
                                    <span>{{ getDays(json_decode($order->shipping_time)->shipping_day) }}</span>
                                </li>
                                <li style="margin: 15px;">
                                    <b>{{__('order::dashboard.orders.show.other.shipping_hour')}} : </b>
                                    <span>{{ json_decode($order->shipping_time)->shipping_hour }}</span>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            @endif

            <div class="row">
                <div class="col-md-12">
                    <p class="form-row">
                        <button class="btn button-submit print-invoice">
                            <i class="ti-printer"></i> {{ __('order::frontend.orders.invoice.btn.print') }}</button>
                    </p>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('externalJs')

    <script>
        $('.print-invoice').on('click', function () {
            window.print();
        });
    </script>

@endsection
