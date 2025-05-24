<div class="tab-pane fade" id="other">
    {{--<h3 class="page-title">{{ __('setting::dashboard.settings.form.tabs.other') }}</h3>--}}
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
                            value="{{ $page['id'] }}" {{(config('setting.other')['privacy_policy'] == $page->id) ? ' selected="" ' : ''}}>
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
                            value="{{ $page['id'] }}" {{(config('setting.other')['terms'] == $page->id) ? ' selected="" ' : ''}}>
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
                            value="{{ $page['id'] }}" {{( isset(config('setting.other')['about_us']) && config('setting.other')['about_us'] == $page->id) ? ' selected="" ' : ''}}>
                            {{ $page->translate(locale())->title }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <hr>
        <div class="form-group">
            <label class="col-md-2">
                {{ __('setting::dashboard.settings.form.minimum_products_qty') }}
            </label>
            <div class="col-md-9">
                <input type="number" min="0" class="form-control" name="products[minimum_products_qty]"
                       value="{{ config('setting.products.minimum_products_qty') ?? 0 }}"/>
            </div>
        </div>
        <hr>

        <div class="form-group">
            <label class="col-md-2">
                {{ __('setting::dashboard.settings.form.contacts_whatsapp') }}
            </label>
            <div class="col-md-9">
                <input type="text" class="form-control" name="contact_us[whatsapp]"
                       value="{{config('setting.contact_us.whatsapp') ? config('setting.contact_us.whatsapp') : ''}}"/>
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-2">
                {{ __('setting::dashboard.settings.form.contacts_mobile') }}
            </label>
            <div class="col-md-9">
                <input type="text" class="form-control" name="contact_us[mobile]"
                       value="{{config('setting.contact_us.mobile') ? config('setting.contact_us.mobile') : ''}}"/>
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-2">
                {{ __('setting::dashboard.settings.form.contacts_technical_support') }}
            </label>
            <div class="col-md-9">
                <input type="text" class="form-control" name="contact_us[technical_support]"
                       value="{{config('setting.contact_us.technical_support') ? config('setting.contact_us.technical_support') : ''}}"/>
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


    </div>
</div>
