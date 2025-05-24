@extends('apps::frontend.layouts.master')
@section('title', __('catalog::frontend.category_products.title') )

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

        .filter-price .custom-slider-range-price {
            background: #e4e4e4 none repeat scroll 0 0;
            height: 4px;
            margin-bottom: 18px;
            position: relative;
            margin-top: 26px;
        }

        .filter-price .custom-slider-range-price .ui-slider-range {
            background: #5466a6 none repeat scroll 0 0;
            height: 4px;
            left: 65px;
            position: absolute;
            top: 0;
            width: 160px;
        }

        .filter-price .custom-slider-range-price .ui-slider-handle {
            background: #fff none repeat scroll 0 0;
            cursor: pointer;
            height: 15px;
            left: 25px;
            position: absolute;
            top: -6px;
            width: 15px;
            border-radius: 50%;
            border: 2px solid #5466a6;
        }

    </style>

@endsection

@section('content')

    <div class="inner-page">
        <div class="container">

            {{--<div class="row text-center" style="margin-bottom: 10px;">
                <div class="col-lg-12 col-md-6 col-sm-6 d-re-no">
                    <div class="block-search">
                        <form method="get" action="{{ route('frontend.categories.products') }}" class="form-search">
                            <div class="form-content">
                                <div class="search-input">
                                    <input type="search" class="input" name="s" value="{{ request()->get('s') }}"
                                           autocomplete="off">
                                    <i class="ti-search"></i>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>--}}

            @include('apps::frontend.layouts._alerts')

            <div class="home-search">
                @include('core::frontend.shared.pickup-delivery-section')
            </div>

            <div class="account-setting">

                <div class="row">
                    <div class="col-md-3">
                        <div class="categories">
                            <div class="title-top">
                                <h3>{{ __('catalog::frontend.category_products.filter.categories') }}</h3>
                            </div>
                            <ul class="nav nav-sidebar" id="navbar">
                                @foreach($categories as $k => $cat)
                                    <li>
                                        <a class="{{ (isset(request()->get('categories')[$cat->id]) && request()->get('categories')[$cat->id] == $cat->translate(locale())->slug) || ($category && $category->id == $cat->id) ? 'active': '' }}"
                                           href="#cat{{ $cat->id }}">{{ $cat->translate(locale())->title }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="block-search">
                            <form method="get" action="{{ route('frontend.categories.products') }}" class="form-search">
                                <div class="form-content">
                                    <div class="search-input">
                                        <input type="search" class="input" name="s" value="{{ request()->get('s') }}"
                                               autocomplete="off"
                                               placeholder="{{ __('apps::frontend.master.search_by_meal') }}...">
                                        <i class="ti-search"></i>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="account-content">
                            @foreach($categories as $k => $cat)
                                <div class="panel-group" id="cat{{ $cat->id }}">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h4 class="panel-title">
                                                <a data-toggle="collapse" href="#collapse{{ $cat->id }}"
                                                   class="collapseWill">
                                                    {{ $cat->translate(locale())->title }}
                                                    <span>
                                                        <i class="fa fa-caret-right"></i>
                                                    </span>
                                                </a>
                                            </h4>
                                        </div>
                                        <div id="collapse{{ $cat->id }}" class="panel-collapse collapse in">
                                            <div class="panel-body">
                                                <div class="my-fav">
                                                    @foreach($cat->products as $key => $product)
                                                        <div class="product-item style-2">
                                                            <div class="product-inner container-fluid">
                                                                <div class="row">
                                                                    <div class="col-md-2 col-xs-3 pl-0">
                                                                        <div class="product-thumb">
                                                                            <div class="thumb-inner">
                                                                                <a href="{{ route('frontend.products.index', $product->translate(locale())->slug) }}">
                                                                                    <img
                                                                                        src="{{ url($product->image) }}"
                                                                                        alt="p8">
                                                                                    {{--<span class="large">
                                                                                        <img
                                                                                            src="{{ url($product->image) }}"
                                                                                            class="large-image"
                                                                                            alt="adventure">
                                                                                    </span>--}}
                                                                                </a>
                                                                            </div>
                                                                        </div>

                                                                    </div>
                                                                    <div class="col-md-10 col-xs-9">
                                                                        <div class="product-innfo">
                                                                            <div class="product-name">
                                                                                <a
                                                                                    href="{{ route('frontend.products.index', $product->translate(locale())->slug) }}">
                                                                                    {{ $product->translate(locale())->title }}
                                                                                </a>
                                                                            </div>
                                                                            <p>{{ limitString($product->translate(locale())->short_description, 100) }}</p>
                                                                            <div class="row">

                                                                                <div class="col-md-6 col-xs-4 pl-0">
                                                                                    {{--@if($product->offer)
                                                                                        <span class="price price-dark">
                                                                                            <ins>{{ $product->offer->offer_price }} {{ __('apps::frontend.master.kwd') }}</ins>
                                                                                        </span>
                                                                                        <span class="price-dark">
                                                                                            <ins>{{ $product->price }} {{ __('apps::frontend.master.kwd') }}</ins>
                                                                                        </span>
                                                                                    @else
                                                                                        <span class="price price-dark">
                                                                                            <ins>{{ $product->price }} {{ __('apps::frontend.master.kwd') }}</ins>
                                                                                        </span>
                                                                                    @endif--}}
                                                                                </div>

                                                                                <div
                                                                                    class="col-md-6 col-xs-8 addToCart">

                                                                                    {{-- Start First Case --}}
                                                                                    {{--<a class="btn button-submit"
                                                                                       href="{{ route('frontend.products.index', $product->translate(locale())->slug) }}">
                                                                                        {{ __('catalog::frontend.products.product_details') }}
                                                                                    </a>--}}
                                                                                    {{-- End First Case --}}

                                                                                    {{-- Start Second Case --}}
                                                                                    {{--@if($product->offer)
                                                                                        <span class="price price-dark">
                                                                                            <ins>{{ $product->offer->offer_price }} {{ __('apps::frontend.master.kwd') }}</ins>
                                                                                        </span>
                                                                                        <span class="price-dark">
                                                                                            <ins>{{ $product->price }} {{ __('apps::frontend.master.kwd') }}</ins>
                                                                                        </span>
                                                                                    @else
                                                                                        <span class="price price-dark">
                                                                                            <ins>{{ $product->price }} {{ __('apps::frontend.master.kwd') }}</ins>
                                                                                        </span>
                                                                                    @endif--}}
                                                                                    {{-- End Second Case --}}

                                                                                    {{-- Start Third Case --}}
                                                                                    <a class="btn button-submit"
                                                                                       href="{{ route('frontend.products.index', $product->translate(locale())->slug) }}">
                                                                                        @if($product->offer)
                                                                                            {{ $product->offer->offer_price }} {{ __('apps::frontend.master.kwd') }}
                                                                                            /
                                                                                            @if($product->price > 0)
                                                                                                <span
                                                                                                    class="old-product-price">{{ $product->price .' '. __('apps::frontend.master.kwd') }}</span>
                                                                                            @else
                                                                                                {{  __('apps::frontend.master.price_according_to_choice') }}
                                                                                            @endif
                                                                                        @else
                                                                                            {{  $product->price > 0 ? $product->price .' '. __('apps::frontend.master.kwd') : __('apps::frontend.master.price_according_to_choice') }}
                                                                                        @endif
                                                                                    </a>
                                                                                    {{-- End Third Case --}}

                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="col-md-3 cart-block">

                        <div class="mini-cart">
                            <h4 class="order-summ-title">{{ __('catalog::frontend.products.cart') }}</h4>
                            @if(count(getCartContent()) > 0)
                                <div class="cart-order-summery">
                                    <ul class="minicart-items-wrapper">

                                        @foreach ($cartItems as $item)
                                            <li class="cart-item">
                                                <div class="row">
                                                    <div class="col-md-7 col-xs-7 pl-0">
                                                        <div class="cart-item-cont">
                                                            <a class="remove-item" title="delete"
                                                               href="{{ url(route('frontend.shopping-cart.delete', [$item->attributes->product->id, 'product_type' => $item->attributes->product_type])) }}">
                                                                <i class="fa fa-minus-circle"></i>
                                                            </a>
                                                            <h5>
                                                                @if($item->attributes->product_type == 'variation')
                                                                    <a href="{{ route('frontend.products.index', [$item->attributes->product->product->translate(locale())->slug, generateVariantProductData($item->attributes->product->product, $item->attributes->product->id, $item->attributes->selectedOptionsValue)['slug']]) }}">
                                                                        {{ limitString(generateVariantProductData($item->attributes->product->product, $item->attributes->product->id, $item->attributes->selectedOptionsValue)['name'], 30) }}
                                                                    </a>
                                                                @else
                                                                    <a href="{{ url(route('frontend.products.index', [$item->attributes->product->translate(locale())->slug])) }}">
                                                                        {{ limitString($item->attributes->product->translate(locale())->title, 15) }}
                                                                    </a>
                                                                @endif
                                                            </h5>
                                                            <span class="price price-dark">
                                                                @if($item->attributes->has('addonsOptions'))
                                                                    <ins>{{ floatval($item->price) - floatval($item->attributes['addonsOptions']['total_amount']) }} + {{ $item->attributes['addonsOptions']['total_amount'] }} {{ __('catalog::frontend.cart.product_addons') }}</ins>
                                                                @else
                                                                    <ins>{{ $item->price }} {{ __('apps::frontend.master.kwd') }}</ins>
                                                                @endif
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-5 col-xs-5">

                                                        <div class="loaderDiv"
                                                             id="loaderDiv-{{ $item->attributes->product->id }}">
                                                            <img src="{{ url('frontend/images/loading.gif') }}">
                                                            {{--<div class="my-loader"></div>--}}
                                                        </div>

                                                        <form class="form"
                                                              @if($item->attributes->product_type == 'product')
                                                              action="{{ url(route('frontend.shopping-cart.create-or-update', [ $item->attributes->product->translate(locale())->slug ])) }}"
                                                              @else
                                                              action="{{ url(route('frontend.shopping-cart.create-or-update', [ $item->attributes->product->product->translate(locale())->slug, $item->attributes->product->id])) }}"
                                                              @endif
                                                              method="POST"
                                                              data-id="{{ $item->attributes->product->id }}">
                                                            @csrf

                                                            <input type="hidden"
                                                                   id="productImage-{{ $item->attributes->product->id }}"
                                                                   value="{{ url($item->attributes->product->image) }}">
                                                            <input type="hidden"
                                                                   id="productTitle-{{ $item->attributes->product->id }}"
                                                                   value="{{ $item->attributes->product_type == 'product' ? $item->attributes->product->translate(locale())->title : $item->attributes->product->product->translate(locale())->title }}">
                                                            <input type="hidden"
                                                                   id="productType-{{ $item->attributes->product->id }}"
                                                                   value="{{ $item->attributes->product_type == 'product' ? 'product' : 'variation' }}">

                                                            <div class="quantity"
                                                                 id="quantityContainer-{{ $item->attributes->product->id }}">
                                                                <div class="buttons-added">
                                                                    <a href="#" class="sign plus btnIncDecQty"><i
                                                                            class="fa fa-plus"></i></a>
                                                                    <input type="text"
                                                                           id="prd-qty-{{ $item->attributes->product->id }}"
                                                                           value="{{ $item->quantity }}"
                                                                           title="Qty"
                                                                           class="input-text qty text" size="1">
                                                                    <a href="#" class="sign minus btnIncDecQty"><i
                                                                            class="fa fa-minus"></i></a>
                                                                </div>

                                                            </div>
                                                        </form>

                                                    </div>
                                                </div>
                                            </li>
                                        @endforeach

                                    </ul>

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
                                                class="col-md-6 col-xs-6 left-text"
                                                id="deliveryFeesValue">{{ Cart::getCondition('company_delivery_fees') ? number_format(Cart::getCondition('company_delivery_fees')->getValue(), 3) .' '. __('apps::frontend.master.kwd') : __('apps::frontend.master.free') }}</div>
                                        </div>

                                        {{--<div class="row mb-10" id="shippingSection">
                                            <div
                                                class="col-md-6 col-xs-6 fb-1">{{ __('catalog::frontend.checkout.shipping') }}</div>
                                            <div
                                                class="col-md-6 col-xs-6 left-text">{{ Cart::getCondition('company_delivery_fees') ? number_format(Cart::getCondition('company_delivery_fees')->getValue(), 2) : 0 }} {{ __('apps::frontend.master.kwd') }}</div>
                                        </div>--}}

                                    </div>
                                    <div class="cart-summ">
                                        <div class="row mb-10">
                                            <div
                                                class="col-md-6 col-xs-6 fb-1">{{ __('catalog::frontend.checkout.total') }}</div>
                                            <div id="cartTotalAmount"
                                                 class="col-md-6 col-xs-6 left-text">{{ number_format(getCartTotal(), 3) }} {{ __('apps::frontend.master.kwd') }}</div>
                                        </div>

                                        {{--@if(!is_null(getCartConditionByName(null, 'min_order_amount')))
                                            <div class="row mb-10" id="">
                                                <div
                                                    class="col-md-6 col-xs-6 fb-1">{{ __('apps::frontend.master.min_order_amount') }}</div>
                                                <div
                                                    class="col-md-6 col-xs-6 left-text" id="minOrderAmountValue">
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
                            @else
                                <div class="cart-order-summery">
                                    <div class="cart-summ">
                                        <h5 class="empty-cart-title">{{ __('catalog::frontend.cart.empty') }}.</h5>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>

@endsection


@section('pageJs')
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
@endsection

@section('externalJs')

    <script>
        // Slider range price
        $('.custom-slider-range-price').each(function () {
            var min = parseInt($(this).data('min'));
            var max = parseInt($(this).data('max'));
            var unit = $(this).data('unit');
            var value_min = parseInt($(this).data('value-min'));
            var value_max = parseInt($(this).data('value-max'));
            var label_reasult = $(this).data('label-reasult');
            var t = $(this);
            $(this).slider({
                range: true,
                min: min,
                max: max,
                values: [value_min, value_max],
                slide: function (event, ui) {
                    var result = label_reasult + " <span>" + unit + ui.values[0] + ' </span> - <span> ' + unit + ui.values[1] + '</span>';
                    t.closest('.price_slider_wrapper').find('.price_slider_amount').html(result);

                    /************* Edited By Mahmoud Elzohairy **************/
                    t.closest('.price_slider_wrapper').find('#hiddenPriceSliderAmount #priceFrom').val(ui.values[0]);
                    t.closest('.price_slider_wrapper').find('#hiddenPriceSliderAmount #priceTo').val(ui.values[1]);
                }
            });
        });
    </script>

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

                        {{--@if(!in_array(request()->route()->getName(), ['frontend.shopping-cart.index', 'frontend.checkout.index']))--}}
                        {{--updateHeaderCart(params);--}}
                        {{--@endif--}}

                        // displaySuccessMsg(data);

                    },
                    error: function (data) {
                        $('#loaderDiv-' + productId).hide();
                        $('#quantityContainer-' + productId).show();

                        var getJSON = $.parseJSON(data.responseText);
                        if (getJSON['itemQty']) {
                            $('#prd-qty-' + productId).val(getJSON['itemQty']);
                        }
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
