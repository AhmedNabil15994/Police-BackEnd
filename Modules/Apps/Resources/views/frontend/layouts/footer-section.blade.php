{{--<footer class="footer">--}}
{{--    <div class="container">--}}
{{--        <div class="row">--}}
{{--            <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 footer-logo-icon">--}}
{{--                <img class="footer-logo"--}}
{{--                     src="{{ config('setting.white_logo') ? url(config('setting.white_logo')) : url('frontend/images/footer-logo.png') }}"/>--}}
{{--                <div class="links">--}}
{{--                    <ul>--}}
{{--                        @if(config('setting.contact_us.mobile'))--}}
{{--                            <li>--}}
{{--                                <b>{{ __('apps::frontend.contact_us.info.mobile')}}--}}
{{--                                    : </b> {{ config('setting.contact_us.mobile') }}--}}
{{--                            </li>--}}
{{--                        @endif--}}

{{--                        @if(config('setting.contact_us.technical_support'))--}}
{{--                            <li>--}}
{{--                                <b>{{ __('apps::frontend.contact_us.info.technical_support')}}--}}
{{--                                    : </b> {{ config('setting.contact_us.technical_support') }}--}}
{{--                            </li>--}}
{{--                        @endif--}}
{{--                    </ul>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--            <div class="col-xs-6 col-sm-6 col-md-2 col-lg-2">--}}
{{--                <h3 class="title-of-footer">{{ __('apps::frontend.master.important_links') }}</h3>--}}
{{--                <div class="links">--}}
{{--                    <ul>--}}
{{--                        @foreach($pages as $k => $page)--}}
{{--                            <li>--}}
{{--                                <a href="{{ route('frontend.pages.index', $page->slug) }}">--}}
{{--                                    {{ $page->translate(locale())->title }}--}}
{{--                                </a>--}}
{{--                            </li>--}}
{{--                        @endforeach--}}

{{--                        --}}{{-- <li>--}}
{{--                             <a href="{{ route('frontend.login') }}"> {{ __('authentication::frontend.login.title') }}</a>--}}
{{--                         </li>--}}

{{--                    </ul>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--            <div class="col-xs-6 col-sm-6 col-md-2 col-lg-2">--}}
{{--                <h3 class="title-of-footer">{{ __('apps::frontend.master.website_links') }}</h3>--}}
{{--                <div class="links">--}}
{{--                    <ul>--}}
{{--                        <li>--}}
{{--                            <a href="{{ route('frontend.home') }}">{{ __('apps::frontend.master.home') }}</a>--}}
{{--                        </li>--}}
{{--                        <li>--}}
{{--                            <a href="{{ route('frontend.categories.index') }}"> {{ __('apps::frontend.master.categories') }}</a>--}}
{{--                        </li>--}}
{{--                        <li>--}}
{{--                            <a href="{{ $aboutUs ? route('frontend.pages.index', $aboutUs->slug) : '#' }}">{{ __('apps::frontend.master.about_us') }}</a>--}}
{{--                        </li>--}}
{{--                        <li>--}}
{{--                            <a href="{{ route('frontend.contact_us') }}">{{ __('apps::frontend.master.contact_us') }}</a>--}}
{{--                        </li>--}}
{{--                    </ul>--}}
{{--                </div>--}}
{{--            </div>--}}


{{--            <div class="col-xs-12 col-sm-12 col-md-5 col-lg-5 footer-subscribe">--}}
{{--                <h3 class="title-of-footer">{{ __('apps::frontend.master.mailing_list') }}</h3>--}}
{{--                <div class="subscribe-form">--}}
{{--                    <form>--}}
{{--                        <input type="text"--}}
{{--                               class="form-control"--}}
{{--                               placeholder="{{ __('apps::frontend.contact_us.form.email')}}"/>--}}
{{--                        <button class="btn" type="submit">{{ __('apps::frontend.master.subscribe') }}</button>--}}
{{--                    </form>--}}
{{--                </div>--}}
{{--                <h3 class="title-of-footer">{{ __('apps::frontend.master.payment_method') }}</h3>--}}
{{--                <div class="pay-men">--}}
{{--                    <a href="javascript:;"><img src="{{ url('frontend/images/payment.svg') }}" alt="pay1"></a>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</footer>--}}

<div class="footer-copyright text-center">
    <p>
        {{ __('apps::frontend.footer.copyright') }}
        <a target="_blank" href="https://www.tocaan.com/">
            {{ __('apps::frontend.footer.tocaan_company') }}
        </a>
    </p>
</div>
