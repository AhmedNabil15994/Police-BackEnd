<div class="tab-pane fade" id="toggle_modules">
    {{--    <h3 class="page-title">{{ __('setting::dashboard.settings.form.tabs.other') }}</h3>--}}
    <div class="col-md-10">

        <div class="form-group">
            <label class="col-md-2">
                {{ __('setting::dashboard.settings.form.toggle_sub_category') }}
            </label>
            <div class="col-md-9">
                <div class="mt-radio-inline">
                    <label class="mt-radio mt-radio-outline">
                        {{ __('setting::dashboard.settings.form.yes') }}
                        <input type="radio" name="other[toggle_sub_category]" value="1"
                               @if (config('setting.other.toggle_sub_category') == 1)
                               checked
                            @endif>
                        <span></span>
                    </label>
                    <label class="mt-radio mt-radio-outline">
                        {{ __('setting::dashboard.settings.form.no') }}
                        <input type="radio" name="other[toggle_sub_category]" value="0"
                               @if (config('setting.other.toggle_sub_category') == 0)
                               checked
                            @endif>
                        <span></span>
                    </label>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-2">
                {{ __('setting::dashboard.settings.form.force_update') }}
            </label>
            <div class="col-md-9">
                <div class="mt-radio-inline">
                    <label class="mt-radio mt-radio-outline">
                        {{ __('setting::dashboard.settings.form.yes') }}
                        <input type="radio" name="other[force_update]" value="1"
                               @if (config('setting.other.force_update') == 1)
                               checked
                            @endif>
                        <span></span>
                    </label>
                    <label class="mt-radio mt-radio-outline">
                        {{ __('setting::dashboard.settings.form.no') }}
                        <input type="radio" name="other[force_update]" value="0"
                               @if (config('setting.other.force_update') == 0)
                               checked
                            @endif>
                        <span></span>
                    </label>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-2">
                {{ __('setting::dashboard.settings.form.is_multi_vendors') }}
            </label>
            <div class="col-md-9">

                <div class="mt-radio-inline">
                    <label class="mt-radio mt-radio-outline">
                        {{ __('setting::dashboard.settings.form.yes') }}
                        <input type="radio" name="other[is_multi_vendors]" value="1"
                               @if (config('setting.other.is_multi_vendors') == 1)
                               checked
                            @endif>
                        <span></span>
                    </label>
                    <label class="mt-radio mt-radio-outline">
                        {{ __('setting::dashboard.settings.form.no') }}
                        <input type="radio" name="other[is_multi_vendors]" value="0"
                               @if (config('setting.other.is_multi_vendors') == 0)
                               checked
                            @endif>
                        <span></span>
                    </label>
                </div>

                <div id="selectVendorLoader" class="text-center" style="display: none; color: #494949;">
                    <b>{{ __('apps::dashboard.general.loader') }}</b>
                </div>

                <div id="toggleAddNewRestaurant"
                     style="{{ config('setting.other.is_multi_vendors') == 0 ? 'display: none;' : 'display: block;' }}">
                    <div class="form-group">
                        <label class="col-md-3">
                            {{ __('setting::dashboard.settings.form.toggle_add_new_restaurant') }}
                        </label>
                        <div class="col-md-9">
                            <div class="mt-radio-inline">
                                <label class="mt-radio mt-radio-outline">
                                    {{ __('setting::dashboard.settings.form.yes') }}
                                    <input type="radio" name="other[toggle_add_new_restaurant]" value="1"
                                           @if (config('setting.other.toggle_add_new_restaurant') == 1)
                                           checked
                                        @endif>
                                    <span></span>
                                </label>
                                <label class="mt-radio mt-radio-outline">
                                    {{ __('setting::dashboard.settings.form.no') }}
                                    <input type="radio" name="other[toggle_add_new_restaurant]" value="0"
                                           @if (config('setting.other.toggle_add_new_restaurant') == 0)
                                           checked
                                        @endif>
                                    <span></span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="selectVendorRow" style="display: none;">
                    <div class="form-group">
                        <label class="col-md-3">
                            {{ __('setting::dashboard.settings.form.choose_vendor') }}
                        </label>
                        <div class="col-md-9">
                            <select id="selectVendors" name="default_vendor" class="form-control select2">
                                <option value=""></option>
                            </select>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        {{--<div class="form-group">
            <label class="col-md-2">
                {{ __('setting::dashboard.settings.form.toggle_roles') }}
            </label>
            <div class="col-md-9">
                <div class="mt-radio-inline">
                    <label class="mt-radio mt-radio-outline">
                        {{ __('setting::dashboard.settings.form.yes') }}
                        <input type="radio" name="other[toggle_roles]" value="1"
                               @if (config('setting.other.toggle_roles') == 1)
                               checked
                            @endif>
                        <span></span>
                    </label>
                    <label class="mt-radio mt-radio-outline">
                        {{ __('setting::dashboard.settings.form.no') }}
                        <input type="radio" name="other[toggle_roles]" value="0"
                               @if (config('setting.other.toggle_roles') == 0)
                               checked
                            @endif>
                        <span></span>
                    </label>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-2">
                {{ __('setting::dashboard.settings.form.enable_subscriptions') }}
            </label>
            <div class="col-md-9">
                <div class="mt-radio-inline">
                    <label class="mt-radio mt-radio-outline">
                        {{ __('setting::dashboard.settings.form.yes') }}
                        <input type="radio" name="other[enable_subscriptions]" value="1"
                               @if (config('setting.other.enable_subscriptions') == 1)
                               checked
                            @endif>
                        <span></span>
                    </label>
                    <label class="mt-radio mt-radio-outline">
                        {{ __('setting::dashboard.settings.form.no') }}
                        <input type="radio" name="other[enable_subscriptions]" value="0"
                               @if (config('setting.other.enable_subscriptions') == 0)
                               checked
                            @endif>
                        <span></span>
                    </label>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-2">
                {{ __('setting::dashboard.settings.form.toggle_supported_countries') }}
            </label>
            <div class="col-md-9">
                <div class="mt-radio-inline">
                    <label class="mt-radio mt-radio-outline">
                        {{ __('setting::dashboard.settings.form.yes') }}
                        <input type="radio" name="other[toggle_supported_countries]" value="1"
                               @if (config('setting.other.toggle_supported_countries') == 1)
                               checked
                            @endif>
                        <span></span>
                    </label>
                    <label class="mt-radio mt-radio-outline">
                        {{ __('setting::dashboard.settings.form.no') }}
                        <input type="radio" name="other[toggle_supported_countries]" value="0"
                               @if (config('setting.other.toggle_supported_countries') == 0)
                               checked
                            @endif>
                        <span></span>
                    </label>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-2">
                {{ __('setting::dashboard.settings.form.toggle_general_notifications') }}
            </label>
            <div class="col-md-9">
                <div class="mt-radio-inline">
                    <label class="mt-radio mt-radio-outline">
                        {{ __('setting::dashboard.settings.form.yes') }}
                        <input type="radio" name="other[toggle_general_notifications]" value="1"
                               @if (config('setting.other.toggle_general_notifications') == 1)
                               checked
                            @endif>
                        <span></span>
                    </label>
                    <label class="mt-radio mt-radio-outline">
                        {{ __('setting::dashboard.settings.form.no') }}
                        <input type="radio" name="other[toggle_general_notifications]" value="0"
                               @if (config('setting.other.toggle_general_notifications') == 0)
                               checked
                            @endif>
                        <span></span>
                    </label>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-2">
                {{ __('setting::dashboard.settings.form.toggle_coupons') }}
            </label>
            <div class="col-md-9">
                <div class="mt-radio-inline">
                    <label class="mt-radio mt-radio-outline">
                        {{ __('setting::dashboard.settings.form.yes') }}
                        <input type="radio" name="other[toggle_coupons]" value="1"
                               @if (config('setting.other.toggle_coupons') == 1)
                               checked
                            @endif>
                        <span></span>
                    </label>
                    <label class="mt-radio mt-radio-outline">
                        {{ __('setting::dashboard.settings.form.no') }}
                        <input type="radio" name="other[toggle_coupons]" value="0"
                               @if (config('setting.other.toggle_coupons') == 0)
                               checked
                            @endif>
                        <span></span>
                    </label>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-2">
                {{ __('setting::dashboard.settings.form.toggle_tags') }}
            </label>
            <div class="col-md-9">
                <div class="mt-radio-inline">
                    <label class="mt-radio mt-radio-outline">
                        {{ __('setting::dashboard.settings.form.yes') }}
                        <input type="radio" name="other[toggle_tags]" value="1"
                               @if (config('setting.other.toggle_tags') == 1)
                               checked
                            @endif>
                        <span></span>
                    </label>
                    <label class="mt-radio mt-radio-outline">
                        {{ __('setting::dashboard.settings.form.no') }}
                        <input type="radio" name="other[toggle_tags]" value="0"
                               @if (config('setting.other.toggle_tags') == 0)
                               checked
                            @endif>
                        <span></span>
                    </label>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-2">
                {{ __('setting::dashboard.settings.form.toggle_advertising') }}
            </label>
            <div class="col-md-9">
                <div class="mt-radio-inline">
                    <label class="mt-radio mt-radio-outline">
                        {{ __('setting::dashboard.settings.form.yes') }}
                        <input type="radio" name="other[toggle_advertising]" value="1"
                               @if (config('setting.other.toggle_advertising') == 1)
                               checked
                            @endif>
                        <span></span>
                    </label>
                    <label class="mt-radio mt-radio-outline">
                        {{ __('setting::dashboard.settings.form.no') }}
                        <input type="radio" name="other[toggle_advertising]" value="0"
                               @if (config('setting.other.toggle_advertising') == 0)
                               checked
                            @endif>
                        <span></span>
                    </label>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-2">
                {{ __('setting::dashboard.settings.form.toggle_order_status') }}
            </label>
            <div class="col-md-9">
                <div class="mt-radio-inline">
                    <label class="mt-radio mt-radio-outline">
                        {{ __('setting::dashboard.settings.form.yes') }}
                        <input type="radio" name="other[toggle_order_status]" value="1"
                               @if (config('setting.other.toggle_order_status') == 1)
                               checked
                            @endif>
                        <span></span>
                    </label>
                    <label class="mt-radio mt-radio-outline">
                        {{ __('setting::dashboard.settings.form.no') }}
                        <input type="radio" name="other[toggle_order_status]" value="0"
                               @if (config('setting.other.toggle_order_status') == 0)
                               checked
                            @endif>
                        <span></span>
                    </label>
                </div>
            </div>
        </div>--}}

    </div>
</div>
