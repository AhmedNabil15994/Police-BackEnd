<div class="tab-pane fade" id="products">
    {{--    <h3 class="page-title">{{ __('setting::dashboard.settings.form.tabs.products') }}</h3>--}}
    <div class="col-md-10">

        <div class="form-group">
            <label class="col-md-2">
                {{ __('setting::dashboard.settings.form.products.toggle_addons') }}
            </label>
            <div class="col-md-9">
                <div class="mt-radio-inline">
                    <label class="mt-radio mt-radio-outline"> {{ __('apps::dashboard.general.show') }}
                        <input type="radio" name="products[toggle_addons]" value="1"
                            {{ config('setting.products.toggle_addons') == 1 ? 'checked' : '' }}>
                        <span></span>
                    </label>
                    <label class="mt-radio mt-radio-outline">
                        {{ __('apps::dashboard.general.hide') }}
                        <input type="radio" name="products[toggle_addons]" value="0"
                            {{ config('setting.products.toggle_addons') == 0 ? 'checked' : '' }}>
                        <span></span>
                    </label>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-2">
                {{ __('setting::dashboard.settings.form.products.toggle_variations') }}
            </label>
            <div class="col-md-9">
                <div class="mt-radio-inline">
                    <label class="mt-radio mt-radio-outline"> {{ __('apps::dashboard.general.show') }}
                        <input type="radio" name="products[toggle_variations]" value="1"
                            {{ config('setting.products.toggle_variations') == 1 ? 'checked' : '' }}>
                        <span></span>
                    </label>
                    <label class="mt-radio mt-radio-outline">
                        {{ __('apps::dashboard.general.hide') }}
                        <input type="radio" name="products[toggle_variations]" value="0"
                            {{ config('setting.products.toggle_variations') == 0 ? 'checked' : '' }}>
                        <span></span>
                    </label>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-2">
                {{ __('setting::dashboard.settings.form.products.toggle_featured') }}
            </label>
            <div class="col-md-9">
                <div class="mt-radio-inline">
                    <label class="mt-radio mt-radio-outline"> {{ __('apps::dashboard.general.show') }}
                        <input type="radio" name="products[toggle_featured]" value="1"
                            {{ config('setting.products.toggle_featured') == 1 ? 'checked' : '' }}>
                        <span></span>
                    </label>
                    <label class="mt-radio mt-radio-outline">
                        {{ __('apps::dashboard.general.hide') }}
                        <input type="radio" name="products[toggle_featured]" value="0"
                            {{ config('setting.products.toggle_featured') == 0 ? 'checked' : '' }}>
                        <span></span>
                    </label>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-2">
                {{ __('setting::dashboard.settings.form.products.toggle_ordering_time') }}
            </label>
            <div class="col-md-9">
                <div class="mt-radio-inline">
                    <label class="mt-radio mt-radio-outline"> {{ __('apps::dashboard.general.show') }}
                        <input type="radio" name="products[toggle_ordering_time]" value="1"
                            {{ config('setting.products.toggle_ordering_time') == 1 ? 'checked' : '' }}>
                        <span></span>
                    </label>
                    <label class="mt-radio mt-radio-outline">
                        {{ __('apps::dashboard.general.hide') }}
                        <input type="radio" name="products[toggle_ordering_time]" value="0"
                            {{ config('setting.products.toggle_ordering_time') == 0 ? 'checked' : '' }}>
                        <span></span>
                    </label>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-2">
                {{ __('setting::dashboard.settings.form.minimum_products_qty') }}
            </label>
            <div class="col-md-5">
                <input type="number" min="0" class="form-control" name="products[minimum_products_qty]"
                       value="{{ config('setting.products.minimum_products_qty') ?? 0 }}"/>
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-2">
                {{ __('setting::dashboard.settings.form.complete_your_meal') }}
            </label>
            <div class="col-md-5">
                <select name="products[complete_your_meal][]" class="select2 form-control select2-allow-clear">
                    <option value="">
                        ---{{ __('setting::dashboard.settings.form.complete_your_meal') }}
                        ---
                    </option>
                    @foreach($sharedActiveCategories as $k => $category)
                        <option
                            value="{{ $category->id }}" {{ in_array($category->id, config('setting.products.complete_your_meal') ?? []) ? 'selected' : '' }}>
                            {{ $category->translate(locale())->title }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

    </div>
</div>
