<div class="tab-pane fade" id="other">
    {{--    <h3 class="page-title">{{ __('setting::dashboard.settings.form.tabs.other') }}</h3>--}}
    <div class="col-md-10">
        <div class="form-group">
            <label class="col-md-2">
                {{ __('setting::dashboard.settings.form.privacy_policy') }}
            </label>
            <div class="col-md-9">
                <select name="other[privacy_policy]" class="form-control select2">
                    <option value=""></option>
                    @foreach ($pages as $page)
                        <option
                            value="{{ $page['id'] }}" {{(config('setting.other.privacy_policy') == $page->id) ? ' selected="" ' : ''}}>
                            {{ $page->translate(locale())->title }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-2">
                {{ __('setting::dashboard.settings.form.terms') }}
            </label>
            <div class="col-md-9">
                <select name="other[terms]" class="form-control select2">
                    <option value=""></option>
                    @foreach ($pages as $page)
                        <option
                            value="{{ $page['id'] }}" {{(config('setting.other.terms') == $page->id) ? ' selected="" ' : ''}}>
                            {{ $page->translate(locale())->title }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-2">
                {{ __('setting::dashboard.settings.form.about_us') }}
            </label>
            <div class="col-md-9">
                <select name="other[about_us]" class="form-control select2">
                    <option value=""></option>
                    @foreach ($pages as $page)
                        <option
                            value="{{ $page['id'] }}" {{ config('setting.other.about_us') == $page->id ? ' selected="" ' : ''}}>
                            {{ $page->translate(locale())->title }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-2">
                {{ __('setting::dashboard.settings.form.shipping_company') }}
            </label>
            <div class="col-md-9">
                <select name="other[shipping_company]" class="form-control select2">
                    <option value=""></option>
                    @foreach ($companies as $company)
                        <option
                            value="{{ $company['id'] }}" {{( isset(config('setting.other')['shipping_company']) && config('setting.other')['shipping_company'] == $company->id) ? ' selected="" ' : ''}}>
                            {{ $company->translate(locale())->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-2">
                {{ __('setting::dashboard.settings.form.site_color') }}
            </label>
            <div class="col-md-4">
                <input type="color" class="form-control" name="other[site_color]"
                       value="{{config('setting.other.site_color') ?? ''}}"/>
            </div>
        </div>
        <hr>
        <div class="form-group">
            <label class="col-md-2">
                {{ __('setting::dashboard.settings.form.show_suppliers_slider') }}
            </label>
            <div class="col-md-9">
                <div class="mt-radio-inline">
                    <label class="mt-radio mt-radio-outline">
                        {{ __('setting::dashboard.settings.form.yes') }}
                        <input type="radio" name="other[show_suppliers_slider]" value="1"
                               @if (config('setting.other.show_suppliers_slider') == 1)
                               checked
                            @endif>
                        <span></span>
                    </label>
                    <label class="mt-radio mt-radio-outline">
                        {{ __('setting::dashboard.settings.form.no') }}
                        <input type="radio" name="other[show_suppliers_slider]" value="0"
                               @if (config('setting.other.show_suppliers_slider') == 0)
                               checked
                            @endif>
                        <span></span>
                    </label>
                </div>
            </div>
        </div>


        <div class="form-group">
            <label class="col-md-2">
                {{ __('setting::dashboard.settings.form.webhook_url') }}
            </label>
            <div class="col-md-9">
                <input type="text" class="form-control" name="other[webhook_url]"
                       value="{{config('setting.other.webhook_url') ? config('setting.other.webhook_url') : ''}}"/>
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-2">
                {{ __('setting::dashboard.settings.form.webhook_token') }}
            </label>
            <div class="col-md-9">
                <input type="text" class="form-control" name="other[webhook_token]"
                       value="{{config('setting.other.webhook_token') ? config('setting.other.webhook_token') : ''}}"/>
            </div>
        </div>
    </div>
</div>
