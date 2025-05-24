<div class="tab-pane fade" id="logo">

    <h3 class="page-title">{{ __('setting::dashboard.settings.form.tabs.logo') }}</h3>

    <div class="col-md-10">

        <div class="form-group">
            <label class="col-md-2">
                {{ __('setting::dashboard.settings.form.logo') }}
            </label>
            <div class="col-md-9">
                <div class="input-group">
                    <span class="input-group-btn">
                        <a data-input="logo" data-preview="holder" class="btn btn-primary lfm">
                            <i class="fa fa-picture-o"></i>
                            {{__('apps::dashboard.general.upload_btn')}}
                        </a>
                    </span>
                    <input name="images[logo]" class="form-control logo" type="text" readonly
                           value="{{ config('setting.logo') ? url(config('setting.logo')) : ''}}">
                </div>
                <span class="holder" style="margin-top:15px;max-height:100px;">
                    <img src="{{ config('setting.logo') ? url(config('setting.logo')) : ''}}" style="height: 15rem;">
                </span>
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-2">
                {{ __('setting::dashboard.settings.form.white_logo') }}
            </label>
            <div class="col-md-9">
                <div class="input-group">
                    <span class="input-group-btn">
                        <a data-input="white_logo" data-preview="holder" class="btn btn-primary lfm">
                            <i class="fa fa-picture-o"></i>
                            {{__('apps::dashboard.general.upload_btn')}}
                        </a>
                    </span>
                    <input name="images[white_logo]" class="form-control white_logo" type="text" readonly
                           value="{{ config('setting.white_logo') ? url(config('setting.white_logo')) : ''}}">
                </div>
                <span class="holder" style="margin-top:15px;max-height:100px;">
                    <img src="{{ config('setting.white_logo') ? url(config('setting.white_logo')) : ''}}"
                         style="height: 15rem;">
                </span>
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-2">
                {{ __('setting::dashboard.settings.form.favicon') }}
            </label>
            <div class="col-md-9">
                <div class="input-group">
                    <span class="input-group-btn">
                        <a data-input="favicon" data-preview="holder" class="btn btn-primary lfm">
                            <i class="fa fa-picture-o"></i>
                            {{__('apps::dashboard.general.upload_btn')}}
                        </a>
                    </span>
                    <input name="images[favicon]" class="form-control favicon" type="text" readonly
                           value="{{ config('setting.favicon') ? url(config('setting.favicon')) : ''}}">
                </div>
                <span class="holder" style="margin-top:15px;max-height:100px;">
                    <img src="{{ config('setting.favicon') ? url(config('setting.favicon')) : ''}}"
                         style="height: 15rem;">
                </span>
            </div>
        </div>
        <hr>

        <div class="form-group">
            <label class="col-md-2">
                {{ __('setting::dashboard.settings.form.about_us_logo') }}
            </label>
            <div class="col-md-9">
                <div class="input-group">
                    <span class="input-group-btn">
                        <a data-input="about_us_logo" data-preview="holder" class="btn btn-primary lfm">
                            <i class="fa fa-picture-o"></i>
                            {{__('apps::dashboard.general.upload_btn')}}
                        </a>
                    </span>
                    <input name="images[about_us_logo]" class="form-control about_us_logo" type="text" readonly
                           value="{{ config('setting.images.about_us_logo') ? url(config('setting.images.about_us_logo')) : ''}}">
                </div>
                <span class="holder" style="margin-top:15px;max-height:100px;">
                    <img
                        src="{{ config('setting.images.about_us_logo') ? url(config('setting.images.about_us_logo')) : ''}}"
                        style="height: 15rem;">
                </span>
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-2">
                {{ __('setting::dashboard.settings.form.contact_us_logo') }}
            </label>
            <div class="col-md-9">
                <div class="input-group">
                    <span class="input-group-btn">
                        <a data-input="contact_us_logo" data-preview="holder" class="btn btn-primary lfm">
                            <i class="fa fa-picture-o"></i>
                            {{__('apps::dashboard.general.upload_btn')}}
                        </a>
                    </span>
                    <input name="images[contact_us_logo]" class="form-control contact_us_logo" type="text" readonly
                           value="{{ config('setting.images.contact_us_logo') ? url(config('setting.images.contact_us_logo')) : ''}}">
                </div>
                <span class="holder" style="margin-top:15px;max-height:100px;">
                    <img
                        src="{{ config('setting.images.contact_us_logo') ? url(config('setting.images.contact_us_logo')) : ''}}"
                        style="height: 15rem;">
                </span>
            </div>
        </div>

    </div>
</div>
