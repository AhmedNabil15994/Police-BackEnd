<script>

    function displayErrorsMsg(data, icon = 'error') {
        // console.log('errors::', $.parseJSON(data.responseText));

        var getJSON = $.parseJSON(data.responseText);

        var output = '<ul>';

        if (typeof getJSON.errors == 'string') {
            output += "<li>" + getJSON.errors + "</li>";
        } else {
            if (getJSON.errors.hasOwnProperty("code")) {
                output += "<li>" + getJSON.errors['code'][0] + "</li>";
            } else {

                for (var error in getJSON.errors) {
                    output += "<li>" + getJSON.errors[error] + "</li>";
                }

            }
        }

        output += '</ul>';

        var wrapper = document.createElement('div');
        wrapper.innerHTML = output;

        swal({
            content: wrapper,
            icon: icon,
            dangerMode: true,

            buttons: {
                close: {
                    className: 'btn btn-danger text-center',
                    text: "{{ __('catalog::frontend.cart.btn.ok') }}",
                    value: 'close',
                    closeModal: true
                },
            }
        })
    }

    function displaySuccessMsg(data) {
        swal({
            closeOnClickOutside: false,
            closeOnEsc: false,
            text: data,
            icon: "success",
            buttons: {
                close: {
                    className: 'btn btn-continue text-center',
                    text: "{{ __('vendor::webservice.rates.btnClose') }}",
                    value: 'close',
                    closeModal: true
                },
            }
        });
    }

    function deleteFromCartByAjax(productID, productType = 'product') {

        $('#headerLoaderDiv').show();

        $.ajax({
            method: "GET",
            url: "{{ url(route('frontend.shopping-cart.deleteByAjax')) }}",
            data: {
                "id": productID,
                "product_type": productType,
            },
            beforeSend: function () {

            },
            success: function (data) {
                $('#prdList-' + productID).remove();

                var cartIcon = $('#cartIcon');
                var cartItemsInfo = $('#cartItemsInfo');

                $('#cartPrdTotal').html(data.result.cartTotal + " {{ __('apps::frontend.master.kwd') }}");

                if (data.result.cartCount == 0) {

                    var info = `
                        <div class="empty-subtitle">{{ __('catalog::frontend.cart.empty') }}</div>
                    `;
                    cartItemsInfo.html(info);
                    $('#cartItemsContainer').empty();
                    $('.counter-number').remove();

                } else {

                    var rowCount = `
                        <span class="counter-number" id="cartPrdCount">${data.result.cartCount}</span>
                    `;
                    cartIcon.append(rowCount);

                    var rowCartItemsInfo = `
                        <div class="subtitle">{{ __('catalog::frontend.cart.you_have') }} <b>( ${data.result.cartCount} )</b> {{ __('catalog::frontend.cart.products_in_your_cart') }}</div>
                    `;
                    cartItemsInfo.html(rowCartItemsInfo);

                }

            },
            error: function (data) {
                displayErrorsMsg(data);
            },
            complete: function (data) {

                var getJSON = $.parseJSON(data.responseText);

                if (getJSON.errors) {
                    displayErrorsMsg(data, 'warning');
                    return true;
                }

                $('#headerLoaderDiv').hide();

            },
        });

    }

    function updateHeaderCart(params) {

        var rowCount = `
            <span class="cart-icon"><i class="ti-shopping-cart"></i></span>
            <span class="counter-number">${params.cartCount}</span>
         `;
        $('#cartIcon').html(rowCount);

        {{--
        var rowCount,
           rowCartItemsInfo,
           rowCartItemsContainer,
           rowLi,
           cartIcon = $('#cartIcon'),
           cartItemsInfo = $('#cartItemsInfo'),
           cartItemsContainer = $('#cartItemsContainer'),
           minicartItems = $('.minicart-items'),
           cartPrdTotal = $('#cartPrdTotal');


       $('.cartItemsInfo').remove();
       rowCount = `
               <span class="counter-number" id="cartPrdCount">${params.cartCount}</span>
           `;
       cartIcon.append(rowCount);

       rowCartItemsInfo = `
               <div class="subtitle">{{ __('catalog::frontend.cart.you_have') }} <b>( ${params.cartCount} )</b> {{ __('catalog::frontend.cart.products_in_your_cart') }}</div>
           `;
       cartItemsInfo.html(rowCartItemsInfo);

       rowLi = `
               <div class="media align-items-center">
                   <div class="pro-img d-flex align-items-center">
                       <img class="img-fluid"
                            src="${params.productImage}"
                            alt="Author">
                   </div>
                   <div class="media-body">
                   <span class="product-name">
                       <a href="${params.productDetailsRoute}">${params.productTitle}</a>
                   </span>
                       <div class="product-price d-block">
                           <span class="text-muted">x ${params.productQuantity}</span>
                           <span>${params.productPrice} {{ __('apps::frontend.master.kwd') }}</span>
                       </div>
                   </div>
                   <button type="button"
                           class="btn remove"
                           onclick="deleteFromCartByAjax(${params.productId}, ${params.product_type})">
                       <i class="ti-trash"></i>
                   </button>
               </div>
           `;

       if (params.cartCount == 1) {

           rowCartItemsContainer = `
               <div class="minicart-items-wrapper">
                    <ol class="minicart-items">

                       <li class="product-item"
                           id="prdList-${params.productId}">
                           ${rowLi}
                       </li>

                   </ol>
               </div>

               <div class="minicart-footer">
                   <div class="subtotal">
                       <span class="label">{{ __('catalog::frontend.cart.subtotal') }} :</span>
                           <span class="price" id="cartPrdTotal">${params.cartSubTotal} {{ __('apps::frontend.master.kwd') }}</span>
                   </div>
                   <div class="actions">
                       <a class="btn btn-viewcart"
                          href="{{ route('frontend.shopping-cart.index') }}">
                           <i class="ti-shopping-cart-full"></i>
                           {{ __('catalog::frontend.cart.cart_details') }}</a>
                   <a class="btn btn-checkout"
                       href="{{ route('frontend.checkout.index') }}">
                               <i class="ti-wallet"></i>
                               {{ __('catalog::frontend.cart.checkout') }}</a>
               </div>
           </div>`;
           cartItemsContainer.html(rowCartItemsContainer);

       } else {
           if ($("#prdList-" + params.productId).length == 0) {
               //it doesn't exist
               $item = `
                   <li class="product-item"
                   id="prdList-${params.productId}">
                       ${rowLi}
                   </li>
               `;
               minicartItems.prepend($item);
           } else {
               //it exist
               $("#prdList-" + params.productId).html(rowLi);
           }
           cartPrdTotal.html(params.cartSubTotal + " " + "{{ __('apps::frontend.master.kwd') }}");
       }
       --}}

    }

    function generalAddToCart(action, productId) {

        var productImage = $('#productImage-' + productId).val();
        var productTitle = $('#productTitle-' + productId).val();
        // var qty = $('#productQuantity-' + productId).val();

        $('#general_add_to_cart-' + productId).hide();
        $('#generalLoaderDiv-' + productId).show();

        $.ajax({
            method: "POST",
            url: action,
            data: {
                // "qty": qty,
                "request_type": 'general_cart',
                "product_type": 'product',
                "vendor_id": '{{ is_null(get_cookie_value(config('core.config.constants.SHIPPING_BRANCH'))) ? null : json_decode(get_cookie_value(config('core.config.constants.SHIPPING_BRANCH')))->vendor_id }}',
                "_token": '{{ csrf_token() }}',
            },
            beforeSend: function () {
            },
            success: function (data) {
                var params = {
                    'productId': productId,
                    'productImage': productImage,
                    'productTitle': productTitle,
                    'productQuantity': data.data.productQuantity,
                    'productPrice': data.data.productPrice,
                    'productDetailsRoute': data.data.productDetailsRoute,
                    'cartCount': data.data.cartCount,
                    'cartSubTotal': data.data.subTotal,
                };

                updateHeaderCart(params);
                displaySuccessMsg(data['message']);
            },
            error: function (data) {
                $('#generalLoaderDiv-' + productId).hide();
                $('#general_add_to_cart-' + productId).show();
                displayErrorsMsg(data);
            },
            complete: function (data) {
                $('#generalLoaderDiv-' + productId).hide();
                $('#general_add_to_cart-' + productId).show();
            },
        });

    }

    function generalAddToFavourites(action, productId) {

        $('#btnAddToFavourites-' + productId).hide();
        $('#generalLoaderDiv-' + productId).show();

        $.ajax({
            method: "POST",
            url: action,
            data: {
                "_token": '{{ csrf_token() }}',
            },
            beforeSend: function () {
            },
            success: function (data) {
                var favouriteBadge = $('#favouriteBadge');
                favouriteBadge.text(data.data.favouritesCount);
                displaySuccessMsg(data['message']);
            },
            error: function (data) {
                $('#generalLoaderDiv-' + productId).hide();
                $('#btnAddToFavourites-' + productId).show();
                displayErrorsMsg(data);
            },
            complete: function (data) {
                $('#generalLoaderDiv-' + productId).hide();
            },
        });

    }

    $(document).on('click', '#btnCheckCoupon', function (e) {

        var token = $(this).closest('.coupon-form').find('input[name="_token"]').val();
        var action = $(this).closest('.coupon-form').attr('action');
        var code = $('#txtCouponCode').val();

        e.preventDefault();

        if (code !== '') {

            $('#loaderCouponDiv').show();

            $.ajax({
                method: "POST",
                url: action,
                data: {
                    "code": code,
                    "_token": token,
                },
                beforeSend: function () {
                },
                success: function (data) {
                    displaySuccessMsg(data);
                },
                error: function (data) {
                    displayErrorsMsg(data);
                },
                complete: function (data) {

                    $('#loaderCouponDiv').hide();
                    var getJSON = $.parseJSON(data.responseText);
                    if (getJSON.data) {
                        showCouponContainer(getJSON.data.coupon_value, getJSON.data.sub_total, getJSON.data.total);
                    }

                },
            });
        }

    });

    function showCouponContainer(coupon_value, subTotal, total) {
        var row = `
            <div class="row mb-10">
                <div
                    class="col-md-6 col-xs-6 fb-1">{{ __('catalog::frontend.cart.coupon_value') }}</div>
                <div id="cartSubTotal"
                     class="col-md-6 col-xs-6 left-text">${coupon_value} {{ __('apps::frontend.master.kwd') }}</div>
            </div>
            `;

        $('#couponContainer').html(row);
        $('#cartSubTotalAmount').html(subTotal + ' ' + "{{ __('apps::frontend.master.kwd') }}");
        $('#cartTotalAmount').html(total + ' ' + "{{ __('apps::frontend.master.kwd') }}");
    }

</script>

<script>

    {{-- $('#shippingStatesSelect').on('change', function () {
        let stateId = this.value;

        if (stateId == "") {
            $('#shippingBranches').empty();
            $('#categoryProductsSection').empty();
            $('#minOrderAmount').hide();
        } else {
            $.ajax({
                    method: "GET",
                    url: '{{ route('frontend.get_branches_by_state') }}',
                    data: {'state_id': stateId},
                    beforeSend: function () {
                        $('#shippingBranchesLoader').show();
                        $('#shippingBranches').empty();
                        $('#categoryProductsSection').empty();
                        $('#minOrderAmount').show();
                        $('#minOrderAmount > #minOrderAmountValue').empty();
                    },
                    success: function (data) {
                    },
                    error: function (data) {
                        $('#shippingBranches').hide();
                    },
                    complete: function (data) {
                        $('#shippingBranchesLoader').hide();
                        $('#shippingBranches').show();

                        let shippingBranchCookie = $.cookie('{{ config('core.config.constants.SHIPPING_BRANCH') }}') != null ? JSON.parse($.cookie('{{ config('core.config.constants.SHIPPING_BRANCH') }}')) : null;
                        var getJSON = $.parseJSON(data.responseText);
                        if (getJSON.data.length > 0) {
                            var item = `<div class="row"><div class="col-md-3"></div>
                                            <div class="col-md-6 col-xs-12">
                                                <select id="branch-${stateId}" class="select-detail selectBranch" onchange="selectBranch(this.value, ${stateId})">
                                                <option value="">--- {{ __('apps::frontend.master.choose_shipping_branches') }} ---</option>`;
                            $.each(getJSON.data, function (i, value) {
                                item +=
                                    `<option value="${value.id}" data-min_order_id="${value.min_order_amount}" ${(shippingBranchCookie != null && shippingBranchCookie.vendor_id == value.id && shippingBranchCookie.state_id == stateId) || getJSON.data.length == 1 ? 'selected' : ''}>
                                     ${value.title}
                                 </option>`;
                            });
                            item += `</select>
                                    </div>
                                    <div class="col-md-3"></div>
                                </div>`;
                            $('#shippingBranches').html(item);

                            var minOrderAmount = '';
                            if (getJSON.data.length == 1) {
                                let vendorId = getJSON.data[0]['id'];
                                minOrderAmount = getJSON.data[0]['min_order_amount'];
                                storeShippingBranchCookie(vendorId, stateId, minOrderAmount);
                                // getDeliveryPriceByStateAndBranch(stateId, vendorId);
                                // reloadSamePage();
                            } else {
                                minOrderAmount = $('#branch-' + stateId).find(':selected').data('min_order_id') ?? '';
                            }
                            setMinOrderAmount(minOrderAmount);

                            /*if (shippingBranchCookie != null) {
                                reloadSamePage();
                            }*/

                        } else {
                            $('#shippingBranches').html(`
                                <h5 class="text-center">{{ __('apps::frontend.master.shipping_branches_not_available') }}</h5>
                            `);
                            $('#minOrderAmount').hide();
                        }
                    },
                }
            );
        }

    });

    function selectBranch(value, stateId) {
        if (value == "" || value == null) {
            $.removeCookie('{{ config('core.config.constants.SHIPPING_BRANCH') }}');
        } else {
            let minOrderAmount = $('#branch-' + stateId).find(':selected').data('min_order_id') ?? '';
            setMinOrderAmount(minOrderAmount);
            storeShippingBranchCookie(value, stateId, minOrderAmount);
            // reloadSamePage();
        }
        // getDeliveryPriceByStateAndBranch(stateId, value);
    }

    function storeShippingBranchCookie(vendorId, stateId, minOrderAmount = '') {
        $.removeCookie('{{ config('core.config.constants.SHIPPING_BRANCH') }}');
        let data = JSON.stringify({'state_id': stateId, 'vendor_id': vendorId, 'min_order_amount': minOrderAmount});
        $.cookie('{{ config('core.config.constants.SHIPPING_BRANCH') }}', data, {path: '/'});
    }

    function setMinOrderAmount(minOrderAmountValue) {
        $('#minOrderAmount > #minOrderAmountValue').text(minOrderAmountValue == '' || minOrderAmountValue == null ? '{{ __('apps::frontend.master.min_order_amount_un_limited') }}' : minOrderAmountValue + ' {{ __('apps::frontend.master.kwd') }}')
    } --}}

    {{--function reloadSamePage() {
        @if(request()->route()->getName() == 'frontend.categories.products')
        window.location.reload();
        @endif
    }--}}

    {{--function storeDeliveryChargeCookie(price) {
    $.removeCookie('{{ config('core.config.constants.DELIVERY_CHARGE') }}');
    $.cookie('{{ config('core.config.constants.DELIVERY_CHARGE') }}', price, {path: '/'});
    }--}}

    {{--function getDeliveryPriceByStateAndBranch(stateId, vendorId) {
        let data = {
            'state_id': stateId,
            'vendor_id': vendorId,
        };

        $.ajax({
                method: "GET",
                url: '{{ route('frontend.checkout.get_state_delivery_price') }}',
                data: data,
                beforeSend: function () {
                },
                success: function (data) {
                },
                error: function (data) {
                    var getJSON = $.parseJSON(data.responseText);
                    if (getJSON.data.deliveryPrice == null) {
                        // Remove delivery cookie
                        storeDeliveryChargeCookie(null);
                    }
                    displayErrorsMsg(data);
                },
                complete: function (data) {
                    var getJSON = $.parseJSON(data.responseText);
                    if (getJSON.data.deliveryPrice == "") {
                        // Remove delivery cookie
                        storeDeliveryChargeCookie(null);
                    } else {
                        // Store Delivery In Cookie
                        storeDeliveryChargeCookie(getJSON.data.deliveryPrice);
                    }
                },
            }
        );
    }--}}

    initialSelect2();

    function initialSelect2() {
        $(".searchSelect").select2({
            dir: '{{ locale() === 'ar' ? "rtl" : "ltr"}}',
            placeholder: "{{__('apps::dashboard.datatable.form.select_option')}}",
            allowClear: true
        });
    }

    function togglePickupDeliveryType(value) {
        if (value === 'delivery') {
            $('#deliveryInfoSection').show();
            $('#pickupInfoSection').hide();
            $('#shippingSection').show();
        } else {
            $('#deliveryInfoSection').hide();
            $('#pickupInfoSection').show();
            $('#shippingSection').hide();
            $('#deliveryBranchesSection').hide();
            $('#emptyBranchesSection').hide();
            $('#minOrderAmountSection').hide();
        }

        initialSelect2();
        $('#shippingStatesSelect, #pickupBranchesSelect, #deliveryBranchesSelect').val('');
        $.cookie('{{ config('core.config.constants.PICKUP_DELIVERY') }}', null, {path: '/'});
    }

    $('#shippingStatesSelect').on('select2:select', function (e) {
        var data = e.params.data;
        var type = $("input[name=pickup_delivery_type]:checked").val();
        getStateBranches(data.id, type);
    });

    $('#pickupBranchesSelect, #shippingStatesSelect, #deliveryBranchesSelect').on('select2:clear', function (e) {
        $.cookie('{{ config('core.config.constants.PICKUP_DELIVERY') }}', null, {path: '/'});
        $('#deliveryBranchesSelect').empty();
    });

    $('#pickupBranchesSelect').on('select2:select', function (e) {
        var data = e.params.data;
        var type = $("input[name=pickup_delivery_type]:checked").val();
        let cookieData = JSON.stringify({'type': type, 'content': {'state_id': null, 'vendor_id': data.id}});
        $.cookie('{{ config('core.config.constants.PICKUP_DELIVERY') }}', cookieData, {path: '/'});

        getDeliveryChargeValue(null, null, 'pickup');
    });

    $('#deliveryBranchesSection #deliveryBranchesSelect').on('change', function (e) {
        var vendorId = $(this).val();
        var type = $("input[name=pickup_delivery_type]:checked").val();
        var stateId = $('#shippingStatesSelect').val();

        if (stateId !== '') {
            getDeliveryChargeValue(stateId, vendorId, type);
        }
    });

    function getDeliveryChargeValue(stateId, branchId = null, type = 'delivery') {
        let data = {
            'state_id': stateId,
            'vendor_id': branchId,
        };

        $.ajax({
                method: "GET",
                url: '{{ route('frontend.checkout.get_state_delivery_price') }}',
                data: data,
                beforeSend: function () {
                },
                success: function (data) {
                },
                error: function (data) {
                    displayErrorsMsg(data);
                },
                complete: function (data) {
                    var getJSON = $.parseJSON(data.responseText);
                    if (type == 'delivery') {
                        let vendorId = getJSON.data['id'];
                        let minOrderAmount = getJSON.data['min_order_amount'] && getJSON.data['min_order_amount'] > 0 ? getJSON.data['min_order_amount'] + ' ' + '{{ __('apps::frontend.master.kwd') }}' : '{{ __('apps::frontend.master.min_order_amount_un_limited') }}';
                        savePickupDeliveryCookie(type, stateId, vendorId, getJSON.data['min_order_amount']);
                        $('#minOrderAmountSection').show().html(`
                            <div class="col-md-12 col-xs-6">
                                <span>{{ __('apps::frontend.master.min_order_amount') }} :</span>
                                <b id="minOrderAmountValue">${minOrderAmount}</b>
                            </div>
                        `);

                        let delivery = getJSON.delivery.deliveryPrice == '' || getJSON.delivery.deliveryPrice == 0 ? '{{ __('apps::frontend.master.free') }}' : getJSON.delivery.deliveryPrice + ' ' + '{{ __('apps::frontend.master.kwd') }}';
                        $('#shippingSection #deliveryFeesValue').show().html(delivery);
                    } else {
                        @if (request()->route()->getName() == 'frontend.products.index')
                        location.reload();
                        @endif
                    }
                    $('#cartSubTotalAmount').html(getJSON.sub_total + ' ' + "{{ __('apps::frontend.master.kwd') }}");
                    $('#cartTotalAmount').html(getJSON.total + ' ' + "{{ __('apps::frontend.master.kwd') }}");
                },
            }
        );
    }

    function getStateBranches(stateId, type = 'delivery') {
        let data = {
            'state_id': stateId,
        };

        $.ajax({
                method: "GET",
                url: '{{ route('frontend.get_branches_by_state') }}',
                data: data,
                beforeSend: function () {
                },
                success: function (data) {
                    $('#deliveryBranchesSelect').empty();
                },
                error: function (data) {
                    displayErrorsMsg(data);
                },
                complete: function (data) {
                    var getJSON = $.parseJSON(data.responseText);

                    if (getJSON.data.length == 0) {
                        $('#deliveryBranchesSelect').empty();
                        $('#deliveryBranchesSection').hide();
                        $('#minOrderAmountSection').hide();
                        $('#emptyBranchesSection').show();
                    } else if (getJSON.data.length == 1 && getJSON.delivery != null) {
                        $('#deliveryBranchesSection').hide();
                        $('#emptyBranchesSection').hide();

                        let vendorId = getJSON.data[0]['id'];
                        let minOrderAmount = getJSON.data[0]['min_order_amount'] && getJSON.data[0]['min_order_amount'] > 0 ? getJSON.data[0]['min_order_amount'] + ' ' + '{{ __('apps::frontend.master.kwd') }}' : '{{ __('apps::frontend.master.min_order_amount_un_limited') }}';
                        savePickupDeliveryCookie(type, stateId, vendorId, getJSON.data[0]['min_order_amount']);
                        $('#minOrderAmountSection').show().html(`
                            <div class="col-md-12 col-xs-6">
                                <span>{{ __('apps::frontend.master.min_order_amount') }} :</span>
                                <b id="minOrderAmountValue">${minOrderAmount}</b>
                            </div>
                        `);

                        let delivery = getJSON.delivery.deliveryPrice == '' || getJSON.delivery.deliveryPrice == 0 ? '{{ __('apps::frontend.master.free') }}' : getJSON.delivery.deliveryPrice + ' ' + '{{ __('apps::frontend.master.kwd') }}';
                        $('#shippingSection #deliveryFeesValue').show().html(delivery)

                    } else {
                        $('#emptyBranchesSection').hide();
                        $('#deliveryBranchesSection').show();
                        var item = `<option value="">--- {{ __('apps::frontend.master.choose_shipping_branches') }} ---</option>`;
                        $.each(getJSON.data, function (i, value) {
                            item +=
                                `<option value="${value.id}" data-min_order_id="${value.min_order_amount}">
                                     ${value.title}
                                 </option>`;
                        });
                        $('#deliveryBranchesSection #deliveryBranchesSelect').html(item);
                        initialSelect2();
                        $('#minOrderAmountSection').hide();
                    }

                    if (getJSON.delivery.sub_total)
                        $('#cartSubTotalAmount').html(getJSON.delivery.sub_total + ' ' + "{{ __('apps::frontend.master.kwd') }}");

                    if (getJSON.delivery.total)
                        $('#cartTotalAmount').html(getJSON.delivery.total + ' ' + "{{ __('apps::frontend.master.kwd') }}");

                },
            }
        );
    }

    function savePickupDeliveryCookie(type, stateId = null, vendorId = null, minOrderAmount = null) {
        let cookieData = JSON.stringify({
            'type': type,
            'content': {'state_id': stateId, 'vendor_id': vendorId, 'min_order_amount': minOrderAmount}
        });
        $.cookie('{{ config('core.config.constants.PICKUP_DELIVERY') }}', cookieData, {path: '/'});

        @if (request()->route()->getName() == 'frontend.products.index')
        location.reload();
        @endif
    }

</script>
