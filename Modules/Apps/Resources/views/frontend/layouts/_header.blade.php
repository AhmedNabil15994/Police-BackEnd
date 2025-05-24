<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', '--') || {{ config('setting.app_name.'. locale()) }} </title>
    <meta name="description" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}"/>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet"
          type="text/css"/>
    <link rel="stylesheet" href="{{ url('frontend/css/animate.css') }}">
    @if(locale() == 'ar')
        <link rel="stylesheet" href="{{ url('frontend/css/bootstrap-ar.min.css') }}">
    @else
        <link rel="stylesheet" href="{{ url('frontend/css/bootstrap.min.css') }}">
    @endif

    <link rel="stylesheet" href="{{ url('frontend/css/font-awesome.css') }}">
    <link rel="stylesheet" href="{{ url('frontend/css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ url('frontend/css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ url('frontend/css/chosen.css') }}">
    <link rel="stylesheet" href="{{ url('frontend/css/smoothproducts.css') }}">

    {{-- Start - Bind Css Code From Dashboard Daynamic --}}
    {!! config('setting.custom_codes.css_in_head') ?? null !!}
    {{-- End - Bind Css Code From Dashboard Daynamic --}}

<!-- Template style -->
    @if(locale() == 'ar')
        <link rel="stylesheet" href="{{ url('frontend/css/style-ar.css') }}">
    @else
        <link rel="stylesheet" href="{{ url('frontend/css/style-en.css') }}">
    @endif

    <link rel="icon"
          href="{{ config('setting.favicon') ? url(config('setting.favicon')) : url('frontend/favicon.png') }}"/>

    <style>
        /* start loader style */

        #loaderDiv,
        #headerLoaderDiv {
            display: none;
            margin: 15px auto;
            justify-content: center;
        }

        .generalLoaderDiv {
            display: none;
            margin: 15px 100px;
            justify-content: center;
        }

        #loaderCouponDiv {
            display: none;
            margin: 15px 100px;
            justify-content: center;
        }

        #loaderDiv .my-loader,
        #headerLoaderDiv .my-loader,
        .generalLoaderDiv .my-loader,
        #loaderCouponDiv .my-loader {
            border: 10px solid #f3f3f3;
            border-radius: 50%;
            border-top: 10px solid #3498db;
            width: 70px;
            height: 70px;
            -webkit-animation: spin 2s linear infinite;
            /* Safari */
            animation: spin 2s linear infinite;
        }

        /* Safari */
        @-webkit-keyframes spin {
            0% {
                -webkit-transform: rotate(0deg);
            }

            100% {
                -webkit-transform: rotate(360deg);
            }
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* end loader style */

        .empty-cart-title {
            text-align: center;
        }

        #shippingBranches {
            margin-top: 10px;
        }

        #shippingBranchesLoader {
            display: none;
            text-align: center;
            margin-top: 10px;
        }

        .addToCart .btn.button-submit {
            outline: none !important;
            border: 1px solid #000000 !important;
            color: #000000 !important;
            background: transparent !important;
        }

        span.select2-container{
            width: 100% !important;
        }

        .old-product-price {
            text-decoration: line-through !important;
            color: grey !important;
        }

    </style>

    @yield('externalStyle')

    {{-- Start - Bind Js Code From Dashboard Daynamic --}}
    {!! config('setting.custom_codes.js_before_head') ?? null !!}
    {{-- End - Bind Js Code From Dashboard Daynamic --}}

    @include('apps::frontend.layouts.custom-color', ['mainColor' => config('setting.other.site_color') ?? '#000000'])
</head>
