@extends('apps::frontend.layouts.master')
@section('title', __('apps::frontend.home.title') )
@section('content')

    <div class="block-slide slider-home">
        <div class="sliderhome owl-carousel">

            @foreach($sliders as $k => $slider)
                <div class="item-slide">
                    <div class="slide-inner" style="background: url({{ url($slider->image) }})">
                        <div class="mask"></div>
                        <div class="slide-block">
                            <h2>{{ $slider->translate(locale())->title }}</h2>
                            @if($slider->link && $slider->link != '#')
                                <a href="{{ $slider->link }}" class="btn main-btn">
                                    {{ __('apps::frontend.master.btn_order_now') }}
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach

        </div>
    </div>
    <div class="container">

        <div class="home-search">
            @include('core::frontend.shared.pickup-delivery-section')
        </div>

        <div class="list-companies">
            <div class="row">

                @if(count($categories) > 0)
                    @foreach($categories as $k => $category)
                        <a href="{{ route('frontend.categories.products', $category->slug) }}#cat{{$category->id}}"
                           class="col-md-4 col-xs-6">
                            <div class="product-item text-center">
                                <div class="product-inner">
                                    <div class="product-thumb">
                                        <img src="{{ url($category->image) }}"
                                             alt="{{ $category->translate(locale())->title }}">
                                    </div>
                                    <div class="product-name">{{ $category->translate(locale())->title }}</div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                @endif

            </div>
        </div>

        @if(config('setting.other.show_suppliers_slider'))

            <div class="title-top">
                <h3 class="title-block title-with-board title-center">
                    <span></span>
                </h3>
            </div>

            <div class="home-categories">
                <div class="est-categories">
                    <div class="owl-carousel est-categories-items custom-owl">
                        @foreach($suppliers as $k => $supplier)
                            <a href="#" class="item">
                                <div class="cat-image"><img src="{{ url($supplier->image) }}" alt=""/></div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        {{--<div class="home-search">
            <h2 class="text-center">{{ config('setting.about_app.app_download_description.' . locale()) }}</h2>
            <div class="row">
                <div class="col-md-3"></div>
                <div class="col-md-6 text-center">
                    <a class="" href="{{ config('setting.about_app.android_download_url') ?? '#' }}"
                       style="width: 190px">
                        <img
                            src="{{ config('setting.about_app.android_download_image') ?? url('frontend/images/download_app/android_download_image.png') }}"
                            style="height: 6rem;">
                    </a>
                    <a class="" href="{{ config('setting.about_app.ios_download_url') ?? '#' }}" style="width: 190px">
                        <img
                            src="{{ config('setting.about_app.ios_download_image') ?? url('frontend/images/download_app/ios_download_image.png') }}"
                            style="height: 6rem;">
                    </a>
                </div>
                <div class="col-md-3"></div>
            </div>
        </div>--}}

    </div>

@endsection

@section('externalJs')

    <script>
        /*$(".est-categories-items").owlCarousel({
            items: 3,
            responsive: {
                0: {
                    items: 2
                },
                480: {
                    items: 2
                },
                768: {
                    items: 2
                },
                992: {
                    items: 3
                },
                1200: {
                    items: 3
                }
            }
        });*/
    </script>

@endsection
