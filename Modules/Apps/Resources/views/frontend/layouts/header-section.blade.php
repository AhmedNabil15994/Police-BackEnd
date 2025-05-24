@section('externalStyle')

    <style>
        .empty-subtitle {
            text-align: center;
            color: #434343;
            font-size: 13px;
        }
    </style>

@endsection

<header class="site-header header-option">
    <div class="header-top">
        <div class="container">
            <div class="topp">
                <ul class="header-top-left">
                    <li>
                        <a href="{{ route('frontend.profile.index') }}">
                            <i class="fa fa-user"></i> <span>{{ __('apps::frontend.master.my_account') }}</span>
                        </a>
                    </li>
                    <li class="menu-item-has-children arrow">
                        <a href="javascript:;">
                            @if(locale() == 'ar')
                                <img src="{{ url('frontend/images/kw.svg') }}"
                                     alt="{{ locale() }}">
                            @else
                                <img src="{{ url('frontend/images/us.svg') }}"
                                     alt="{{ locale() }}">
                            @endif
                            {{ getCurrentLanguageNativeName() }}
                        </a>
                        <ul class="submenu dropdown-menu">
                            @foreach (config('laravellocalization.supportedLocales') as $localeCode => $properties)
                                <li>
                                    <a hreflang="{{ $localeCode }}"
                                       href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}">
                                        @if($localeCode == 'ar')
                                            <img src="{{ url('frontend/images/kw.svg') }}"
                                                 alt="{{ $properties['native'] }}">
                                        @else
                                            <img src="{{ url('frontend/images/us.svg') }}"
                                                 alt="{{ $properties['native'] }}">
                                        @endif
                                        {{ $properties['native'] }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                </ul>
                <ul class="header-top-right">
                    @if(config('setting.contact_us.mobile'))
                        <li>
                            <a href="javascript:;">
                                {{ __('apps::frontend.contact_us.header_title') }}
                                : {{ config('setting.contact_us.mobile') }}
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
    <div class="header-content">
        <div class="container">
            <div class="row">
                <div class="col-lg-2 col-md-3 col-xs-6">
                    <div class="logo-header">
                        <a href="{{ route('frontend.home') }}">
                            <img
                                src="{{ config('setting.logo') ? url(config('setting.logo')) : url('frontend/images/header-logo.png') }}"
                                alt="logo">
                        </a>
                    </div>
                </div>
                <div class="col-lg-8 col-md-6 col-xs-2 text-center">
                    <div class="header-menu">
                        <ul class="header-nav cut_plug-nav">
                            <li class="btn-close hidden-mobile"><i class="fa fa-times" aria-hidden="true"></i></li>
                            <li class="menu-item-has-children {{ activeFrontendHeaderMenu(['frontend.home']) }}">
                                <a href="{{ route('frontend.home') }}"
                                   class="dropdown-toggle">{{ __('apps::frontend.master.home') }}</a>
                            </li>
                            <li class="menu-item-has-children arrow">
                                <a href="javascript:;" class="dropdown-toggle">
                                    {{ __('apps::frontend.master.foods_menu') }}<i class="ti-angle-down"></i>
                                </a>
                                <span class="toggle-submenu hidden-mobile"></span>
                                <ul class="submenu dropdown-menu">
                                    @if(count($headerCategories) > 0)
                                        @foreach($headerCategories as $k => $category)
                                            <li class="menu-item">
                                                <a href="{{ route('frontend.categories.products', $category->slug) }}#cat{{$category->id}}">{{ $category->translate(locale())->title }}</a>
                                            </li>
                                        @endforeach
                                    @endif
                                </ul>
                            </li>
                            <li class="menu-item-has-children {{ activeFrontendHeaderMenu(['frontend.pages.index']) }}">
                                <a href="{{ $aboutUs ? route('frontend.pages.index', $aboutUs->slug) : '#' }}"
                                   class="dropdown-toggle">
                                    {{ __('apps::frontend.master.about_us') }}
                                </a>
                            </li>
                            <li class="menu-item-has-children {{ activeFrontendHeaderMenu(['frontend.contact_us']) }}">
                                <a href="{{ route('frontend.contact_us') }}" class="dropdown-toggle">
                                    {{ __('apps::frontend.master.contact_us') }}
                                </a>
                            </li>
                        </ul>
                    </div>

                </div>
                <div class="col-lg-2 col-md-3 col-xs-4 pr-res-0 header-l-side">
                    <div class="block-minicart dropdown">
                        <a class="minicart" href="{{ route('frontend.shopping-cart.index') }}">
                            <span class="counter qty" id="cartIcon">
                                <span class="cart-icon"><i class="ti-shopping-cart"></i></span>
                                @if(count(getCartContent()) > 0)
                                    <span class="counter-number">{{ count(getCartContent()) }}</span>
                                @endif
                            </span>
                        </a>
                    </div>
                    <span data-action="toggle-nav" class="menu-on-mobile hidden-mobile">
                        <span class="btn-open-mobile home-page">
                            <i class="ti-menu"></i>
                        </span>
                    </span>
                </div>
            </div>
        </div>
    </div>
</header>
