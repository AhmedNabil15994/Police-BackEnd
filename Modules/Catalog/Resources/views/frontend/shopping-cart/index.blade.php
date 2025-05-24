@extends('apps::frontend.layouts.master')
@section('title', __('catalog::frontend.cart.title'))

@section('externalStyle')
    <style>

        /* start loader style */

        .loaderDiv {
            display: none;
            margin: 15px 35px;
            justify-content: center;
        }

        .loaderDiv .my-loader {
            border: 10px solid #f3f3f3;
            border-radius: 50%;
            border-top: 10px solid #3498db;
            width: 70px;
            height: 70px;
            -webkit-animation: spin 2s linear infinite; /* Safari */
            animation: spin 2s linear infinite;
        }

        /* end loader style */

        .empty-cart-title {
            text-align: center;
        }

    </style>
@endsection

@section('content')

    <div class="inner-page">
        <div class="container">
            <div class="title-top">
                <h3>{{ __('catalog::frontend.cart.products') }}</h3>
            </div>
            <div class="account-setting">

                @include('apps::frontend.layouts._alerts')

                @if(count(getCartContent()) > 0)
                    <div class="row">
                        <div class="col-md-8">
                            <div class="my-fav">

                                @foreach ($items as $item)
                                    <div class="product-item style-2 favorite-item">
                                        <div class="product-inner">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <div class="product-thumb">
                                                        <div class="thumb-inner">
                                                            <a href="javascript:;"><img
                                                                    src="{{ url($item->attributes->product->image) }}"
                                                                    alt="p8"></a>
                                                        </div>
                                                    </div>
                                                    <div class="product-innfo">
                                                        <div class="product-name">
                                                            @if($item->attributes->product_type == 'variation')
                                                                <a href="{{ route('frontend.products.index', [$item->attributes->product->product->translate(locale())->slug, generateVariantProductData($item->attributes->product->product, $item->attributes->product->id, $item->attributes->selectedOptionsValue)['slug']]) }}">
                                                                    {{ generateVariantProductData($item->attributes->product->product, $item->attributes->product->id, $item->attributes->selectedOptionsValue)['name'] }}
                                                                </a>
                                                            @else
                                                                <a href="{{ url(route('frontend.products.index', [$item->attributes->product->translate(locale())->slug])) }}">
                                                                    {{ $item->attributes->product->translate(locale())->title }}
                                                                </a>
                                                            @endif
                                                        </div>
                                                        <p>{!! limitString($item->attributes->product->translate(locale())->short_description, 60) !!}</p>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="inner favorite-item-opt">
                                                        <span class="price price-dark">
                                                            @if($item->attributes->has('addonsOptions'))
                                                                {{--<ins>{{ floatval($item->price) - floatval($item->attributes['addonsOptions']['total_amount']) }} + {{ $item->attributes['addonsOptions']['total_amount'] }} {{ __('catalog::frontend.cart.product_addons') }}</ins>--}}
                                                                <ins>{{ $item->attributes['addonsOptions']['total_amount'] }} {{ __('catalog::frontend.cart.product_addons') }}</ins>
                                                            @else
                                                                <ins>{{ $item->price }} {{ __('apps::frontend.master.kwd') }}</ins>
                                                            @endif
                                                           ( {{ __('catalog::frontend.cart.qty') }} {{ $item->quantity }} )
                                                        </span>

                                                        <a class="remove-wishlist" title="delete"
                                                           href="{{ url(route('frontend.shopping-cart.delete', [$item->attributes->product->id, 'product_type' => $item->attributes->product_type])) }}">
                                                            <i class="fa fa-trash-o"></i>
                                                        </a>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                            </div>
                        </div>
                        <div class="col-md-4 cart-block in-cart">
                            <div class="cart-order-summery">

                                @include('catalog::frontend.shopping-cart._coupon_form')

                                <div id="couponContainer" class="cart-summ">
                                    @if(\Cart::getCondition('coupon_discount') != null && \Cart::getCondition('coupon_discount')->getValue() != 0)
                                        <div class="row mb-10">
                                            <div class="col-md-6 col-xs-6 fb-1">
                                                {{ __('catalog::frontend.cart.coupon_value') }}
                                            </div>
                                            <div class="col-md-6 col-xs-6 left-text">
                                                {{ number_format(abs(Cart::getCondition('coupon_discount')->getValue()), 3) }} {{ __('apps::frontend.master.kwd') }}
                                            </div>
                                        </div>
                                    @endif

                                    @if(!is_null(getCartItemsCouponValue()) && getCartItemsCouponValue() != 0)
                                        <div class="row mb-10">
                                            <div class="col-md-6 col-xs-6 fb-1">
                                                {{ __('catalog::frontend.cart.coupon_value') }}
                                            </div>
                                            <div class="col-md-6 col-xs-6 left-text">
                                                {{ number_format(getCartItemsCouponValue(), 3) }} {{ __('apps::frontend.master.kwd') }}
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <h6>{{ __('catalog::frontend.cart.cart_details') }}</h6>
                                <div class="cart-summ">
                                    <div class="row mb-10">
                                        <div
                                            class="col-md-6 col-xs-6 fb-1">{{ __('catalog::frontend.cart.subtotal') }}</div>
                                        <div id="cartSubTotalAmount"
                                             class="col-md-6 col-xs-6 left-text">{{ number_format(getCartSubTotal(), 3) }} {{ __('apps::frontend.master.kwd') }}</div>
                                    </div>

                                    <div class="row mb-10" id="shippingSection"
                                         style="{{ optional(checkPickupDeliveryCookie())->type == 'pickup' ? 'display:none':'' }}">
                                        <div
                                            class="col-md-6 col-xs-6 fb-1">{{ __('catalog::frontend.checkout.shipping') }}</div>
                                        <div
                                            class="col-md-6 col-xs-6 left-text" id="deliveryFeesValue">
                                            {{ Cart::getCondition('company_delivery_fees') ? number_format(Cart::getCondition('company_delivery_fees')->getValue(), 3) .' '. __('apps::frontend.master.kwd') : __('apps::frontend.master.free') }}
                                        </div>
                                    </div>

                                    <div class="row mb-10">
                                        <div
                                            class="col-md-6 col-xs-6 fb-1">{{ __('catalog::frontend.checkout.total') }}</div>
                                        <div id="cartTotalAmount"
                                            class="col-md-6 col-xs-6 left-text">
                                            {{ number_format(getCartTotal(), 3) }} {{ __('apps::frontend.master.kwd') }}
                                        </div>
                                    </div>

                                    {{--@if(!is_null(getCartConditionByName(null, 'min_order_amount')))
                                        <div class="row mb-10">
                                            <div
                                                class="col-md-6 col-xs-6 fb-1">{{ __('apps::frontend.master.min_order_amount') }}</div>
                                            <div
                                                class="col-md-6 col-xs-6 left-text">
                                                {{ getCartConditionByName(null, 'min_order_amount')->getAttributes()['amount'] == null ? __('apps::frontend.master.min_order_amount_un_limited') : getCartConditionByName(null, 'min_order_amount')->getAttributes()['amount'] .' '. __('apps::frontend.master.kwd') }}
                                            </div>
                                        </div>
                                    @endif--}}

                                </div>

                                <div class="minicart-footer text-center">
                                    <a class="btn button-submit d-block"
                                       href="{{ route('frontend.checkout.index') }}">
                                        {{ __('catalog::frontend.cart.btn.checkout') }}
                                    </a>
                                </div>

                            </div>
                        </div>
                    </div>
                @else
                    <div class="row">
                        <div class="col-md-12 alert alert-danger">
                            <h4 class="empty-cart-title">{{ __('catalog::frontend.cart.empty') }}.</h4>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>

@endsection

@section('externalJs')

    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    <script>

        $(document).on('click', '.btnIncDecQty', function (e) {

            var token = $(this).closest('.form').find('input[name="_token"]').val();
            var action = $(this).closest('.form').attr('action');
            var qty = parseInt($(this).closest('.form').find('.qty').val());

            var productId = $(this).closest('.form').attr('data-id');
            var productType = $(this).closest('.form').find('#productType-' + productId).val();
            var productImage = $(this).closest('.form').find('#productImage-' + productId).val();
            var productTitle = $(this).closest('.form').find('#productTitle-' + productId).val();

            if ($(this).is('.plus')) {
                qty += 1;
            } else {
                if (qty != 0) {
                    qty -= 1;
                }
            }

            e.preventDefault();

            if (parseInt(qty) > 0) {

                $('#loaderDiv-' + productId).show();
                $(this).closest('.form').find('.quantity').hide();

                $.ajax({
                    method: "POST",
                    url: action,
                    data: {
                        "qty": qty,
                        "request_type": 'cart',
                        "product_type": productType,
                        "vendor_id": '{{ is_null(get_cookie_value(config('core.config.constants.SHIPPING_BRANCH'))) ? null : json_decode(get_cookie_value(config('core.config.constants.SHIPPING_BRANCH')))->vendor_id }}',
                        "_token": token,
                    },
                    beforeSend: function () {
                    },
                    success: function (data) {
                        var params = {
                            'productId': productId,
                            'productImage': productImage,
                            'productTitle': productTitle,
                            'productQuantity': qty,
                            'productPrice': data.data.productPrice,
                            'productDetailsRoute': data.data.productDetailsRoute,
                            'cartCount': data.data.cartCount,
                            'cartSubTotal': data.data.subTotal,
                        };

                        @if(!in_array(request()->route()->getName(), ['frontend.shopping-cart.index', 'frontend.checkout.index']))
                        updateHeaderCart(params);
                        @endif

                        // displaySuccessMsg(data);

                    },
                    error: function (data) {
                        $('#loaderDiv-' + productId).hide();
                        $('#quantityContainer-' + productId).show();

                        displayErrorsMsg(data);
                    },
                    complete: function (data) {

                        $('#loaderDiv-' + productId).hide();
                        $('#quantityContainer-' + productId).show();

                        var getJSON = $.parseJSON(data.responseText);

                        if (getJSON.data) {
                            $('#cartSubTotalAmount').html(getJSON.data.subTotal + " " + " {{ __('apps::frontend.master.kwd') }}");
                            $('#cartTotalAmount').html(getJSON.data.total + " " + " {{ __('apps::frontend.master.kwd') }}");
                        }

                    },
                });
            }

        });

    </script>

@endsection
