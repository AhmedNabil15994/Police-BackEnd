<div class="tab-pane fade" id="order_status">
    <h3 class="page-title">{{ __('setting::dashboard.settings.form.tabs.order_status_colors') }}</h3>
    <div class="col-md-10">

        {{--@foreach ($orderStatuses as $item)
            <div class="form-group">
                <label class="col-md-2">
                    {{ $item->translate(locale())->title }}
                </label>
                <div class="col-md-3">
                    <input type="color" name="order_status[{{$item->flag}}]"
                           value="{{ config('setting.order_status.'. $item->flag) }}" class="form-control"/>
                </div>
            </div>
        @endforeach--}}

        <hr>
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
        </div>

    </div>
</div>
