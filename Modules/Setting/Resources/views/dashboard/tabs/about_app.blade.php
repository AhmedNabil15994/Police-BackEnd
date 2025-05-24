<div class="tab-pane fade" id="about_app">
    {{--    <h3 class="page-title">{{ __('setting::dashboard.settings.form.tabs.about_app') }}</h3>--}}
    <div class="col-md-10">

        {{--  tab for lang --}}
        <ul class="nav nav-tabs">
            @foreach (config('translatable.locales') as $code)
                <li class="@if($loop->first) active @endif">
                    <a data-toggle="tab"
                       href="#about_app_tab_{{$code}}">{{__('catalog::dashboard.products.form.tabs.input_lang',["lang"=>$code])}}</a>
                </li>
            @endforeach
        </ul>

        {{--  tab for content --}}
        <div class="tab-content">

            @foreach (config('translatable.locales') as $code)
                <div id="about_app_tab_{{$code}}" class="tab-pane fade @if($loop->first) in active @endif">

                    <div class="form-group">
                        <label class="col-md-2">
                            {{ __('setting::dashboard.settings.form.app_download_description') }} - {{ $code }}
                        </label>
                        <div class="col-md-9">
                            <input type="text" class="form-control"
                                   name="about_app[app_download_description][{{$code}}]"
                                   value="{{ config('setting.about_app.app_download_description.' . $code) }}"/>
                        </div>
                    </div>

                </div>
            @endforeach

        </div>

        <div class="form-group">
            <label class="col-md-2">
                {{ __('setting::dashboard.settings.form.android_download_url') }}
            </label>
            <div class="col-md-9">
                <input type="text" class="form-control" name="about_app[android_download_url]"
                       value="{{config('setting.about_app.android_download_url') ?? ''}}"/>
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-2">
                {{ __('setting::dashboard.settings.form.ios_download_url') }}
            </label>
            <div class="col-md-9">
                <input type="text" class="form-control" name="about_app[ios_download_url]"
                       value="{{config('setting.about_app.ios_download_url') ?? ''}}"/>
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-2">
                {{ __('setting::dashboard.settings.form.android_download_image') }}
            </label>
            <div class="col-md-9">
                <div class="input-group">
                    <span class="input-group-btn">
                        <a data-input="android_download_image" data-preview="holder" class="btn btn-primary lfm">
                            <i class="fa fa-picture-o"></i>
                            {{__('apps::dashboard.general.upload_btn')}}
                        </a>
                    </span>
                    <input name="about_app[android_download_image]"
                           class="form-control android_download_image"
                           type="text"
                           readonly
                           value="{{ config('setting.about_app.android_download_image') ? url(config('setting.about_app.android_download_image')) : ''}}">
                </div>
                <span class="holder" style="margin-top:15px;max-height:100px;">
                    <img
                        src="{{ config('setting.about_app.android_download_image') ? url(config('setting.about_app.android_download_image')) : ''}}"
                        style="height: 6rem;">
                </span>
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-2">
                {{ __('setting::dashboard.settings.form.ios_download_image') }}
            </label>
            <div class="col-md-9">
                <div class="input-group">
                    <span class="input-group-btn">
                        <a data-input="ios_download_image" data-preview="holder" class="btn btn-primary lfm">
                            <i class="fa fa-picture-o"></i>
                            {{__('apps::dashboard.general.upload_btn')}}
                        </a>
                    </span>
                    <input name="about_app[ios_download_image]"
                           class="form-control ios_download_image"
                           type="text"
                           readonly
                           value="{{ config('setting.about_app.ios_download_image') ? url(config('setting.about_app.ios_download_image')) : ''}}">
                </div>
                <span class="holder" style="margin-top:15px;max-height:100px;">
                    <img
                        src="{{ config('setting.about_app.ios_download_image') ? url(config('setting.about_app.ios_download_image')) : ''}}"
                        style="height: 6rem;">
                </span>
            </div>
        </div>

    </div>
</div>
