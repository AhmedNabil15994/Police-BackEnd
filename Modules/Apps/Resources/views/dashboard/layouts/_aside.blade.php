<div class="page-sidebar-wrapper">
    <div class="page-sidebar navbar-collapse collapse">
        <ul class="page-sidebar-menu  page-header-fixed" data-keep-expanded="false" data-auto-scroll="true"
            data-slide-speed="200" style="padding-top: 20px">
            <li class="sidebar-toggler-wrapper hide">
                <div class="sidebar-toggler">
                    <span></span>
                </div>
            </li>

            <li class="nav-item {{ active_menu(['home', '']) }}">
                <a href="{{ url(route('dashboard.home')) }}" class="nav-link nav-toggle">
                    <i class="icon-home"></i>
                    <span class="title">{{ __('apps::dashboard.home.title') }}</span>
                    <span class="selected"></span>
                </a>
            </li>

            {{--@if(auth()->user()->can(['show_roles']))
                <li class="heading">
                    <h3 class="uppercase">{{ __('apps::dashboard.aside.tab.roles_permissions') }}</h3>
                </li>
            @endif

            @permission('show_roles')
            <li class="nav-item {{ active_menu('roles') }}">
                <a href="{{ url(route('dashboard.roles.index')) }}" class="nav-link nav-toggle">
                    <i class="icon-settings"></i>
                    <span class="title">{{ __('apps::dashboard.aside.roles') }}</span>
                </a>
            </li>
            @endpermission--}}

            @if(auth()->user()->can(['show_users', 'show_admins']))
                <li class="heading">
                    <h3 class="uppercase">{{ __('apps::dashboard.aside.tab.users') }}</h3>
                </li>
            @endif

            @permission('show_users')
            <li class="nav-item {{ active_menu('users') }}">
                <a href="{{ url(route('dashboard.users.index')) }}" class="nav-link nav-toggle">
                    <i class="icon-settings"></i>
                    <span class="title">{{ __('apps::dashboard.aside.users') }}</span>
                </a>
            </li>
            @endpermission

            @permission('show_admins')
            <li class="nav-item {{ active_menu('admins') }}">
                <a href="{{ url(route('dashboard.admins.index')) }}" class="nav-link nav-toggle">
                    <i class="icon-settings"></i>
                    <span class="title">{{ __('apps::dashboard.aside.admins') }}</span>
                </a>
            </li>
            @endpermission

            @if (Module::isEnabled('Vendor'))

                @if(auth()->user()->can(['show_restaurants', 'show_vendors', 'show_sections', 'show_sellers', 'show_subscriptions', 'show_packages']))
                    <li class="heading" id="sideMenuVendorsHeadTitle">
                        <h3 class="uppercase">{{ __('apps::dashboard.aside.tab.vendors') }}</h3>
                    </li>
                @endif

                @permission('show_restaurants')
                <li class="nav-item {{ active_menu('restaurants') }}" id="sideMenuRestaurants"
                    {{--style="display: {{ toggleSideMenuItemsByVendorType() }}"--}}>
                    <a href="{{ url(route('dashboard.restaurants.index')) }}" class="nav-link nav-toggle">
                        <i class="icon-settings"></i>
                        <span class="title">{{ __('apps::dashboard.aside.restaurants') }}</span>
                    </a>
                </li>
                @endpermission

                @permission('show_vendors')
                <li class="nav-item {{ active_menu('vendors') }}" id="sideMenuVendors"
                    {{--style="display: {{ toggleSideMenuItemsByVendorType() }}"--}}>
                    <a href="{{ url(route('dashboard.vendors.index')) }}" class="nav-link nav-toggle">
                        <i class="icon-settings"></i>
                        <span class="title">{{ __('apps::dashboard.aside.vendors') }}</span>
                    </a>
                </li>
                @endpermission

                @permission('show_sections')
                <li class="nav-item {{ active_menu('sections') }}" id="sideMenuVendorsSections"
                    style="display: {{ toggleSideMenuItemsByVendorType() }}">
                    <a href="{{ url(route('dashboard.sections.index')) }}" class="nav-link nav-toggle">
                        <i class="icon-settings"></i>
                        <span class="title">{{ __('apps::dashboard.aside.sections') }}</span>
                    </a>
                </li>
                @endpermission

                @permission('show_sellers')
                <li class="nav-item {{ active_menu('sellers') }}" id="sideMenuVendorsSeller"
                    {{--style="display: {{ toggleSideMenuItemsByVendorType() }}"--}}>
                    <a href="{{ url(route('dashboard.sellers.index')) }}" class="nav-link nav-toggle">
                        <i class="icon-settings"></i>
                        <span class="title">{{ __('apps::dashboard.aside.sellers') }}</span>
                    </a>
                </li>
                @endpermission

                @if(config('setting.other.enable_subscriptions') == 1)
                    @if (Module::isEnabled('Subscription'))

                        @permission('show_subscriptions')
                        <li class="nav-item {{ active_menu('subscriptions') }}">
                            <a href="{{ url(route('dashboard.subscriptions.index')) }}" class="nav-link nav-toggle">
                                <i class="icon-settings"></i>
                                <span class="title">{{ __('apps::dashboard.aside.subscriptions') }}</span>
                            </a>
                        </li>
                        @endpermission

                        @permission('show_packages')
                        <li class="nav-item {{ active_menu('packages') }}">
                            <a href="{{ url(route('dashboard.packages.index')) }}" class="nav-link nav-toggle">
                                <i class="icon-settings"></i>
                                <span class="title">{{ __('apps::dashboard.aside.packages') }}</span>
                            </a>
                        </li>
                        @endpermission

                    @endif
                @endif

            @endif

            @if (Module::isEnabled('Catalog'))

                @if(auth()->user()->can(['show_products', 'review_products', 'show_categories', 'show_options', 'show_tags']))
                    <li class="heading">
                        <h3 class="uppercase">{{ __('apps::dashboard.aside.tab.catalog') }}</h3>
                    </li>
                @endif

                @permission('show_products')
                <li class="nav-item {{ active_menu('products') }}">
                    <a href="{{ url(route('dashboard.products.index')) }}" class="nav-link nav-toggle">
                        <i class="icon-settings"></i>
                        <span class="title">{{ __('apps::dashboard.aside.products') }}</span>
                    </a>
                </li>
                @endpermission

                @permission('review_products')
                <li class="nav-item {{ active_menu('review-products') }}" id="sideMenuReviewProducts"
                    {{--style="display: {{ toggleSideMenuItemsByVendorType() }}"--}}>
                    <a href="{{ url(route('dashboard.review_products.index')) }}" class="nav-link nav-toggle">
                        <i class="icon-settings"></i>
                        <span class="title">{{ __('apps::dashboard.aside.review_products') }}</span>
                    </a>
                </li>
                @endpermission

                @permission('show_categories')
                <li class="nav-item {{ active_menu('categories') }}">
                    <a href="{{ url(route('dashboard.categories.index')) }}" class="nav-link nav-toggle">
                        <i class="icon-settings"></i>
                        <span class="title">{{ __('apps::dashboard.aside.categories') }}</span>
                    </a>
                </li>
                @endpermission

                @if(config('setting.products.toggle_variations') == 1)
                    @permission('show_options')
                    <li class="nav-item {{ active_menu('options') }}">
                        <a href="{{ url(route('dashboard.options.index')) }}" class="nav-link nav-toggle">
                            <i class="icon-settings"></i>
                            <span class="title">{{ __('apps::dashboard.aside.options') }}</span>
                        </a>
                    </li>
                    @endpermission
                @endif

                {{--@if (config('setting.other.toggle_tags') == 1)--}}
                @permission('show_tags')
                <li class="nav-item {{ active_menu('tags') }}">
                    <a href="{{ url(route('dashboard.tags.index')) }}" class="nav-link nav-toggle">
                        <i class="icon-tag"></i>
                        <span class="title">{{ __('apps::dashboard.aside.tags') }}</span>
                    </a>
                </li>
                @endpermission
                {{--@endif--}}

                @permission('show_addon_categories')
                <li class="nav-item {{ active_menu('addon-categories') }}">
                    <a href="{{ url(route('dashboard.addon_categories.index')) }}" class="nav-link nav-toggle">
                        <i class="icon-settings"></i>
                        <span class="title">{{ __('apps::dashboard.aside.addon_categories') }}</span>
                    </a>
                </li>
                @endpermission

                @permission('show_addon_options')
                <li class="nav-item {{ active_menu('addon-options') }}">
                    <a href="{{ url(route('dashboard.addon_options.index')) }}" class="nav-link nav-toggle">
                        <i class="icon-settings"></i>
                        <span class="title">{{ __('apps::dashboard.aside.addon_options') }}</span>
                    </a>
                </li>
                @endpermission

            @endif

            @if (Module::isEnabled('Order'))

                @if(auth()->user()->can(['show_orders', 'show_all_orders', 'show_order_statuses']))
                    <li class="heading">
                        <h3 class="uppercase">{{ __('apps::dashboard.aside.tab.orders') }}</h3>
                    </li>
                @endif

                @permission('show_orders')
                <li class="nav-item {{ active_menu('orders') }}">
                    <a href="{{ url(route('dashboard.orders.index')) }}" class="nav-link nav-toggle">
                        <i class="icon-settings"></i>
                        <span class="title">{{ __('apps::dashboard.aside.orders') }}</span>
                    </a>
                </li>
                @endpermission

                @permission('show_all_orders')
                <li class="nav-item {{ active_menu('all-orders') }}">
                    <a href="{{ url(route('dashboard.all_orders.index')) }}" class="nav-link nav-toggle">
                        <i class="icon-settings"></i>
                        <span class="title">{{ __('apps::dashboard.aside.all_orders') }}</span>
                    </a>
                </li>
                @endpermission

                {{--@if (config('setting.other.toggle_order_status') == 1)--}}
                @permission('show_order_statuses')
                <li class="nav-item {{ active_menu('order-statuses') }}">
                    <a href="{{ url(route('dashboard.order-statuses.index')) }}" class="nav-link nav-toggle">
                        <i class="icon-settings"></i>
                        <span class="title">{{ __('apps::dashboard.aside.order_statuses') }}</span>
                    </a>
                </li>
                @endpermission
                {{--@endif--}}

            @endif

            {{-- ############################################################################# --}}

            @if (Module::isEnabled('Company'))

                @if(auth()->user()->can(['show_delivery_charges', 'show_drivers']))
                    <li class="heading">
                        <h3 class="uppercase">{{ __('apps::dashboard.aside.tab.companies') }}</h3>
                    </li>
                @endif

                @permission('show_delivery_charges')
                <li class="nav-item {{ active_menu('vendor-delivery-charges') }}">
                    <a href="{{ url(route('dashboard.delivery-charges.index')) }}" class="nav-link nav-toggle">
                        <i class="icon-settings"></i>
                        <span class="title">{{ __('apps::dashboard.aside.delivery_charges') }}</span>
                    </a>
                </li>
                @endpermission

                @permission('show_drivers')
                <li class="nav-item {{ active_menu('drivers') }}">
                    <a href="{{ url(route('dashboard.drivers.index')) }}" class="nav-link nav-toggle">
                        <i class="icon-settings"></i>
                        <span class="title">{{ __('apps::dashboard.aside.drivers') }}</span>
                    </a>
                </li>
                @endpermission

            @endif

            @if(auth()->user()->can(['show_countries', 'show_cities', 'show_states', 'show_pages', 'show_advertising', 'show_slider', 'show_supplier', 'show_coupon', 'add_notifications', 'show_client_settings']))
                <li class="heading">
                    <h3 class="uppercase">{{ __('apps::dashboard.aside.setting') }}</h3>
                </li>
            @endif

            @if (Module::isEnabled('Area'))

                @permission('show_countries')
                <li class="nav-item {{ active_menu('countries') }}">
                    <a href="{{ url(route('dashboard.countries.index')) }}" class="nav-link nav-toggle">
                        <i class="icon-settings"></i>
                        <span class="title">{{ __('apps::dashboard.aside.countries') }}</span>
                    </a>
                </li>
                @endpermission

                @permission('show_cities')
                <li class="nav-item {{ active_menu('cities') }}">
                    <a href="{{ url(route('dashboard.cities.index')) }}" class="nav-link nav-toggle">
                        <i class="icon-settings"></i>
                        <span class="title">{{ __('apps::dashboard.aside.cities') }}</span>
                    </a>
                </li>
                @endpermission

                @permission('show_states')
                <li class="nav-item {{ active_menu('states') }}">
                    <a href="{{ url(route('dashboard.states.index')) }}" class="nav-link nav-toggle">
                        <i class="icon-settings"></i>
                        <span class="title">{{ __('apps::dashboard.aside.states') }}</span>
                    </a>
                </li>
                @endpermission

            @endif

            @permission('show_pages')
            <li class="nav-item {{ active_menu('pages') }}">
                <a href="{{ url(route('dashboard.pages.index')) }}" class="nav-link nav-toggle">
                    <i class="icon-settings"></i>
                    <span class="title">{{ __('apps::dashboard.aside.pages') }}</span>
                </a>
            </li>
            @endpermission

            @permission('show_advertising')
            <li class="nav-item {{ active_menu('advertising-groups') }}">
                <a href="{{ url(route('dashboard.advertising_groups.index')) }}" class="nav-link nav-toggle">
                    <i class="icon-settings"></i>
                    <span class="title">{{ __('apps::dashboard.aside.advertising_groups') }}</span>
                </a>
            </li>
            @endpermission

            @permission('show_slider')
            <li class="nav-item {{ active_menu('slider') }}">
                <a href="{{ url(route('dashboard.slider.index')) }}" class="nav-link nav-toggle">
                    <i class="icon-settings"></i>
                    <span class="title">{{ __('apps::dashboard.aside.slider') }}</span>
                </a>
            </li>
            @endpermission

            @permission('show_supplier')
            <li class="nav-item {{ active_menu('supplier') }}">
                <a href="{{ url(route('dashboard.supplier.index')) }}" class="nav-link nav-toggle">
                    <i class="icon-settings"></i>
                    <span class="title">{{ __('apps::dashboard.aside.supplier') }}</span>
                </a>
            </li>
            @endpermission

            @permission('show_coupon')
            <li class="nav-item {{ active_menu('coupons') }}">
                <a href="{{ url(route('dashboard.coupons.index')) }}" class="nav-link nav-toggle">
                    <i class="icon-calculator"></i>
                    <span class="title">{{ __('apps::dashboard.aside.coupons') }}</span>
                </a>
            </li>
            @endpermission

            @permission('add_notifications')
            <li class="nav-item {{ active_menu('notifications') }}">
                <a href="{{ url(route('dashboard.notifications.create')) }}" class="nav-link nav-toggle">
                    <i class="icon-settings"></i>
                    <span class="title">{{ __('apps::dashboard.aside.notifications') }}</span>
                </a>
            </li>
            @endpermission

            {{--<li class="nav-item {{ active_menu('setting') }}">
                <a href="{{ url(route('dashboard.setting.index')) }}" class="nav-link nav-toggle">
                    <i class="icon-settings"></i>
                    <span class="title">{{ __('apps::dashboard.aside.setting') }}</span>
                </a>
            </li>--}}

            @permission('show_client_settings')
            <li class="nav-item {{ active_menu('client-setting') }}">
                <a href="{{ url(route('dashboard.client.setting.index')) }}" class="nav-link nav-toggle">
                    <i class="icon-settings"></i>
                    <span class="title">{{ __('apps::dashboard.aside.client_setting') }}</span>
                </a>
            </li>
            @endpermission

            {{--<li class="nav-item {{ active_menu('telescope') }}">
            <a href="{{ url(route('telescope')) }}" class="nav-link nav-toggle">
                <i class="icon-settings"></i>
                <span class="title">{{ __('apps::dashboard.aside.telescope') }}</span>
            </a>
            </li>--}}

        </ul>
    </div>
</div>
