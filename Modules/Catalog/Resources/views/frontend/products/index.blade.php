@extends('apps::frontend.layouts.master')
@section('title', __('apps::frontend.products.details.title') )
@section('externalStyle')
    <style>
        #loaderDiv {
            margin: 15px 242px !important;
        }
    </style>
@endsection
@section('content')

    <div class="container">
        <div class="inner-page">

            <div class="home-search">
                @include('core::frontend.shared.pickup-delivery-section')
            </div>

            <div class="product-details">

                <div id="emptyCartFirstlySection">
                    @if(!is_null($deliveryShippingBranch) && !is_null($currentShippingBranch))
                        @if(($deliveryShippingBranch['branch_id'] ?? null) != optional(optional($currentShippingBranch)->content)->vendor_id)
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="alert alert-danger alert-dismissible text-center" role="alert">
                                        <span>{{ __('catalog::frontend.products.alerts.empty_cart_firstly') }}</span>&nbsp;
                                        <a href="{{ route('frontend.shopping-cart.index') }}">
                                            <b>{{ __('catalog::frontend.cart.btn.got_to_shopping_cart') }}</b>
                                        </a>
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endif
                </div>

                <div id="chooseStateOrBranchSection">
                    @if(is_null($currentShippingBranch))
                        <div class="row">
                            <div class="col-md-12">
                                <div class="alert alert-danger alert-dismissible text-center" role="alert">
                                    <span>{{ __('catalog::frontend.products.alerts.choose_state_or_branch') }}</span>&nbsp;
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="row">

                    <div class="col-md-6">
                        <div class="main-image sp-wrap" id="mainProductSlider">

                            @if($product->image)
                                <a href="{{ url($product->image) }}">
                                    <img src="{{ url($product->image) }}" class="img-responsive" alt="img">
                                </a>
                            @endif

                            @foreach($product->images as $k => $img)
                                <a href="{{ url('uploads/products/' . $img->image) }}">
                                    <img src="{{ url('uploads/products/' . $img->image) }}" class="img-responsive"
                                         alt="img">
                                </a>
                            @endforeach

                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="entry-summary">
                            <div class="product-name">
                                <h3>{{ $product->translate(locale())->title }}</h3>
                                <p>{!! $product->translate(locale())->short_description !!}</p>
                                <h5 style="margin-top: 20px">
                                    @if($product->offer)
                                        <span class="price-before old-product-price">
                                            {{ number_format($product->price, 3) }} {{ __('apps::frontend.master.kwd') }}
                                        </span>&nbsp;
                                        <b id="prdPrice">{{ number_format($product->offer->offer_price, 3) }} {{ __('apps::frontend.master.kwd') }}</b>
                                    @else
                                        <b id="prdPrice">{{ number_format($product->price, 3) }} {{ __('apps::frontend.master.kwd') }}</b>
                                    @endif
                                </h5>
                            </div>

                            <div class="more-options bg-white mb-30">

                                @if(count($product->addOns) > 0)

                                    @foreach($product->addOns as $k => $addOn)

                                        @if($addOn->type == 'multi')
                                            <div class="item-block-dec multi-addons-content">
                                                <h2 class="block-title border-bottom pb-20 mb-20 multi-addonsId"
                                                    data-id="{{ $addOn->addonCategory->id }}">{{ $addOn->addonCategory->getTranslation('title', locale()) }}</h2>

                                                @if(count($addOn->addonOptions) > 0)
                                                    @foreach($addOn->addonOptions as $k => $addOnOption)
                                                        @if(!empty($addOnOption->addonOption))
                                                        <div class="d-flex align-items-center">
                                                            <div class="checkboxes flex-1">
                                                                <input id="check-{{$addOnOption->addonOption->id}}"
                                                                       class="addOnsMultiOption"
                                                                       data-price="{{ $addOnOption->addonOption->price }}"
                                                                       type="checkbox"
                                                                       name="addOnsOptionDefault[{{$addOnOption->addonOption->id}}][]"
                                                                       value="{{ $addOnOption->addonOption->id }}"
                                                                @if(getCartItemById($product->id))
                                                                    {{ selectedCartAddonsOption($product, $addOn->addonCategory->id, $addOnOption->addonOption->id) }}
                                                                    @else {{ $addOnOption->default == 1 ? 'checked' : '' }} @endif>
                                                                <label
                                                                    for="check-{{$addOnOption->addonOption->id}}">
                                                                    @if(!is_null($addOnOption->addonOption->image))
                                                                        <img
                                                                            src="{{ url($addOnOption->addonOption->image) }}"
                                                                            alt=""
                                                                            class="img-thumbnail" style="height: 35px;">
                                                                    @endif
                                                                    {{ $addOnOption->addonOption->getTranslation('title', locale()) }}
                                                                </label>
                                                            </div>
                                                            @if(floatval($addOnOption->addonOption->price) > 0)
                                                                <p class="mb-0">{{ number_format($addOnOption->addonOption->price, 3) }} {{ __('apps::frontend.master.kwd') }}</p>
                                                            @endif
                                                        </div>
                                                        @endif
                                                    @endforeach
                                                @endif

                                            </div>
                                        @else
                                            <div class="item-block-dec single-addons-content">
                                                <h2 class="block-title border-bottom border-top pt-20 pb-20 mb-20 single-addonsId"
                                                    data-id="{{ $addOn->addonCategory->id }}">{{ $addOn->addonCategory->getTranslation('title', locale()) }}</h2>

                                                @if(count($addOn->addonOptions) > 0)
                                                    @foreach($addOn->addonOptions as $k => $addOnOption)

                                                        <div class="d-flex align-items-center">
                                                            <div class="checkboxes radios flex-1">
                                                                <input id="check-{{$addOnOption->addonOption->id}}"
                                                                       class="addOnsSingleOption singleAddonRadio-{{ $addOn->addonCategory->id }}"
                                                                       data-price="{{ $addOnOption->addonOption->price }}"
                                                                       data-addon-id="{{ $addOn->addonCategory->id }}"
                                                                       name="addOnsOptionDefault[{{$addOn->addonCategory->id}}]"
                                                                       value="{{ $addOnOption->addonOption->id }}"
                                                                       type="radio"
                                                                @if(getCartItemById($product->id))
                                                                    {{ selectedCartAddonsOption($product, $addOn->addonCategory->id, $addOnOption->addonOption->id) }} @else {{ $addOnOption->default == 1 ? 'checked' : '' }} @endif>
                                                                <label
                                                                    for="check-{{$addOnOption->addonOption->id}}">
                                                                    @if(!is_null($addOnOption->addonOption->image))
                                                                        <img
                                                                            src="{{ url($addOnOption->addonOption->image) }}"
                                                                            alt=""
                                                                            class="img-thumbnail" style="height: 35px;">
                                                                    @endif
                                                                    {{ $addOnOption->addonOption->getTranslation('title', locale()) }}
                                                                </label>
                                                            </div>
                                                            @if(floatval($addOnOption->addonOption->price) > 0)
                                                                <p class="mb-0">{{ number_format($addOnOption->addonOption->price, 3) }} {{ __('apps::frontend.master.kwd') }}</p>
                                                            @endif

                                                        </div>
                                                    @endforeach
                                                @endif

                                            </div>
                                        @endif
                                    @endforeach

                                @endif

                            </div>

                            <div id="addToCartSection">
                                @if(!is_null($currentShippingBranch) && optional(optional(checkPickupDeliveryCookie())->content)->vendor_id)
                                    @if(is_null($product->variants) || count($product->variants) == 0)
                                        <form class="form"
                                              action="{{ route('frontend.shopping-cart.create-or-update', [ $product->translate(locale())->slug ]) }}"
                                              method="POST" data-id="{{ $product->id }}">
                                            @csrf
                                            <input type="hidden" id="productImage-{{ $product->id }}"
                                                   value="{{ url($product->image) }}">
                                            <input type="hidden" id="productTitle-{{ $product->id }}"
                                                   value="{{ $product->translate(locale())->title }}">
                                            <input type="hidden" id="productType"
                                                   value="product">
                                            <input type="hidden" id="selectedOptions"
                                                   value="">
                                            <input type="hidden" id="selectedOptionsValue"
                                                   value="">

                                            <div class="text-center p-int-cart">
                                                {{--<h3>الاجمالي: 71 د.ك</h3>--}}

                                                <div class="form-group">
                                                    <label>{{ __('catalog::frontend.products.notes') }}</label>
                                                    <textarea class="form-control"
                                                              id="notes"
                                                              name="notes"
                                                              placeholder="{{ __('catalog::frontend.products.notes') }}"
                                                              rows="5">{{ getCartItemById($product->id) ? getCartItemById($product->id)->attributes['notes'] : '' }}</textarea>
                                                </div>

                                                <div id="responseMsg"></div>

                                                <div id="loaderDiv">
                                                    <img src="{{ url('frontend/images/loading.gif') }}">
                                                    {{--<div class="my-loader"></div>--}}
                                                </div>

                                                <div class="quantity">
                                                    <div class="buttons-added">
                                                        <a href="#" class="sign plus"><i class="fa fa-plus"></i></a>
                                                        <input type="text"
                                                               id="prodQuantity"
                                                               value="{{ getCartItemById($product->id) ? getCartItemById($product->id)->quantity : '1' }}"
                                                               title="Qty" class="input-text qty text" size="1">
                                                        <a href="#" class="sign minus"><i class="fa fa-minus"></i></a>
                                                    </div>
                                                </div>

                                                <button id="btnAddToCart"
                                                        class="btn button-submit"
                                                        type="button">
                                                    {{ __('catalog::frontend.products.add_to_cart') }}
                                                </button>

                                            </div>
                                        </form>
                                    @endif
                                @endif
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

@endsection

@section('externalJs')

    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    <script>

        var totalAddonsPrice = parseFloat('{{ $product->offer ? $product->offer->offer_price : $product->price }}');
        var selectedSingleAddonOptionIDs = [];

        $(document).on('click', '#btnAddToCart', function (e) {

            var token = $(this).closest('.form').find('input[name="_token"]').val();
            var action = $(this).closest('.form').attr('action');
            var qty = $('#prodQuantity').val();
            var notes = $('#notes').val();

            var productId = $(this).closest('.form').attr('data-id');
            var productType = $(this).closest('.form').find('#productType').val();
            var productImage = $(this).closest('.form').find('#productImage-' + productId).val();
            var productTitle = $(this).closest('.form').find('#productTitle-' + productId).val();
            var selectedOptions = $(this).closest('.form').find('#selectedOptions').val();
            var selectedOptionsValue = $(this).closest('.form').find('#selectedOptionsValue').val();
            var addOnsOptionIDs = [];
            var options = [];

            e.preventDefault();

            if (parseInt(qty) > 0) {

                $(this).hide();
                $('#loaderDiv').show();

                $('.single-addons-content').each(function (i, item) {
                    let addonsId = $(this).closest('.single-addons-content').find('.single-addonsId').attr('data-id');
                    let options = [];
                    $(this).closest('.single-addons-content').find('input:radio.addOnsSingleOption:checked').each(function (i, item) {
                        if ($(this).val() != null && $(this).val() !== undefined && $(this).val() !== '') {
                            options.push($(this).val());
                        }
                    });

                    if (options.length > 0) {
                        addOnsOptionIDs.push({
                            'id': addonsId,
                            'options': options,
                        });
                    }
                });

                $('.multi-addons-content').each(function (i, item) {
                    let addonsId = $(this).closest('.multi-addons-content').find('.multi-addonsId').attr('data-id');
                    let options = [];
                    $(this).closest('.multi-addons-content').find('input:checkbox.addOnsMultiOption:checked').each(function (i, item) {
                        if ($(this).val() != null && $(this).val() !== undefined && $(this).val() !== '') {
                            options.push($(this).val());
                        }
                    });

                    if (options.length > 0) {
                        addOnsOptionIDs.push({
                            'id': addonsId,
                            'options': options,
                        });
                    }

                });

                // console.log('addOnsOptionIDs:::', addOnsOptionIDs)

                $.ajax({
                    method: "POST",
                    url: action,
                    data: {
                        "qty": qty,
                        "request_type": 'product',
                        "product_type": productType,
                        "selectedOptions": selectedOptions,
                        "selectedOptionsValue": selectedOptionsValue,
                        "addonsOptions": JSON.stringify(addOnsOptionIDs),
                        {{--"vendor_id": '{{ is_null(get_cookie_value(config('core.config.constants.SHIPPING_BRANCH'))) ? null : json_decode(get_cookie_value(config('core.config.constants.SHIPPING_BRANCH')))->vendor_id }}',--}}
                        "vendor_id": '{{ optional(optional(checkPickupDeliveryCookie())->content)->vendor_id }}',
                        "notes": notes ?? null,
                        "_token": token,
                    },
                    beforeSend: function () {
                    },
                    success: function (data) {
                        var params = {
                            'productId': productId,
                            'productImage': productImage,
                            'productTitle': data.data.productTitle,
                            'productQuantity': qty,
                            'productPrice': data.data.productPrice,
                            'productDetailsRoute': data.data.productDetailsRoute,
                            'cartCount': data.data.cartCount,
                            'cartSubTotal': data.data.subTotal,
                        };

                        updateHeaderCart(params);

                        var msg = `
                            <div class="alert alert-success alert-dismissible" role="alert">
                                ${data.message}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        `;
                        $('#responseMsg').html(msg);
                    },
                    error: function (data) {
                        $('#loaderDiv').hide();
                        $('#btnAddToCart').show();

                        let getJSON = $.parseJSON(data.responseText);
                        let error = '';
                        if (getJSON.errors['notes'])
                            error = getJSON.errors['notes'];
                        else
                            error = getJSON.errors;

                        let msg = `
                            <div class="alert alert-danger alert-dismissible" role="alert">
                                ${error}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        `;
                        $('#responseMsg').html(msg);
                    },
                    complete: function (data) {
                        $('#loaderDiv').hide();
                        $('#btnAddToCart').show();
                    },
                });
            }

        });

        function getVariationInfo(e, productId) {

            var selectedOptions = [];
            var selectedOptionsValue = [];

            $('.product-var-options').each(function (i, item) {
                selectedOpt = $(this).attr('data-option-id');
                selectedOptions.push(selectedOpt);
                selectedOptionsValue.push($(this).val());
            });

            if (selectedOptions.length != 0 && !selectedOptionsValue.includes(undefined) && !selectedOptionsValue.includes("")) {
                $.ajax({
                    method: "GET",
                    url: '{{ route('frontend.get_prd_variation_info') }}',
                    data: {
                        "selectedOptions": selectedOptions,
                        "selectedOptionsValue": selectedOptionsValue,
                        "product_id": productId,
                        "_token": '{{ csrf_token() }}',
                    },
                    beforeSend: function () {
                    },
                    success: function (data) {
                        // console.log('data::', data);

                        var variantProduct = data.data.variantProduct;

                        if (variantProduct.sku) {
                            var sku = `
                                <p class="d-flex">
                                    <span class="d-inline-block right-side">{{ __('catalog::frontend.products.sku') }}</span>
                                    <span class="d-inline-block left-side">${variantProduct.sku}</span>
                                </p>
                            `;
                            $('#skuSection').html(sku);
                        }

                        if (variantProduct.price) {
                            if (variantProduct.offer) {
                                var price = `
                                <span class="price-before">${variantProduct.price} {{ __('apps::frontend.master.kwd') }}</span>
                                ${variantProduct.offer.offer_price} {{ __('apps::frontend.master.kwd') }}
                                `;
                            } else {
                                var price = `${variantProduct.price} {{ __('apps::frontend.master.kwd') }}`;
                            }
                            $('#priceSection').html(price);
                        }

                        if (variantProduct.image) {
                            var selectedImg = `
                            <div class="sp-large" style="overflow: hidden; height: auto; width: auto;">
                                <a href="${variantProduct.image}" class="sp-current-big">
                                    <img src="${variantProduct.image}" alt="">
                                </a>
                            </div>
                            `;
                            $('.sp-large').remove();
                            $('#mainProductSlider').prepend(selectedImg);
                        }

                    },
                    error: function (data) {
                        displayErrorsMsg(data);
                    },
                    complete: function (data) {
                        // console.log('data::', data);
                        var getJSON = $.parseJSON(data.responseText);
                        // console.log('getJSON::', getJSON);

                        $('#addVariantPrdToCartSection').html(getJSON.data.form_view);
                    },
                });
            } else {
                $('#addVariantPrdToCartSection').empty();
            }

        }

        @if(!is_null($variantPrd) && !empty($variantPrd->image) && $variantPrd->id == request()->var)
        $(document).ready(function () {
            var img = `
                <div class="sp-large">
                    <a href="{{ $variantPrd->image }}"
                       class="sp-current-big">
                        <img src="{{ $variantPrd->image }}" alt="">
                    </a>
                </div>
            `;
            $('.sp-large').remove();
            $('#mainProductSlider').prepend(img);
        });
        @endif

        {{-- Start - Calculate total addons --}}
        $('.addOnsMultiOption').change(function () {
            if ($(this).is(":checked")) {
                incDecTotalPrice($(this).data('price'));
            } else if ($(this).is(":not(:checked)")) {
                incDecTotalPrice($(this).data('price'), 'decrease');
            }
        });

        $('.addOnsSingleOption').change(function () {

            if ($(this).is(":checked")) {

                var item = selectedSingleAddonOptionIDs.find(x => x.addon_id == $(this).data('addon-id'));
                var itemIndex = selectedSingleAddonOptionIDs.findIndex(x => x.addon_id == $(this).data('addon-id'));
                var object = {};

                if (item != undefined) {
                    var inx = item.addon_option_id;
                    var price = $('#check-' + inx).data('price');
                    selectedSingleAddonOptionIDs.splice(itemIndex, 1);
                    incDecTotalPrice(price, 'decrease');
                }

                object['addon_id'] = $(this).data('addon-id');
                object['addon_option_id'] = $(this).val();
                selectedSingleAddonOptionIDs.push(object);
                incDecTotalPrice($(this).data('price'));
            }
        });

        function incDecTotalPrice(price = 0, operation = 'increase') {
            if (operation === 'increase')
                totalAddonsPrice = totalAddonsPrice + parseFloat(price);
            else
                totalAddonsPrice = totalAddonsPrice - parseFloat(price);

            $('#prdPrice').html(totalAddonsPrice.toFixed(3) + ' ' + '{{ __('apps::frontend.master.kwd') }}');
        }

        $(document).ready(function () {

            $('input:checkbox.addOnsMultiOption:checked').each(function (i, item) {
                incDecTotalPrice($(this).data('price'));
            });

            $('input:radio.addOnsSingleOption:checked').each(function (i, item) {
                var object = {};
                object['addon_id'] = $(this).data('addon-id');
                object['addon_option_id'] = $(this).val();
                selectedSingleAddonOptionIDs.push(object);
                incDecTotalPrice($(this).data('price'));
            });

        });
        {{-- End - Calculate total addons --}}

    </script>

@endsection
