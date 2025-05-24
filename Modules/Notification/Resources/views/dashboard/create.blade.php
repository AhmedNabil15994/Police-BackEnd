@extends('apps::dashboard.layouts.app')
@section('title', __('notification::dashboard.notifications.routes.create'))
@section('content')
    <div class="page-content-wrapper">
        <div class="page-content">
            <div class="page-bar">
                <ul class="page-breadcrumb">
                    <li>
                        <a href="{{ url(route('dashboard.home')) }}">{{ __('apps::dashboard.home.title') }}</a>
                        <i class="fa fa-circle"></i>
                    </li>
                    <li>
                        <a href="javascript:;">{{__('notification::dashboard.notifications.routes.create')}}</a>
                    </li>
                </ul>
            </div>

            <h1 class="page-title"></h1>

            <div class="row">
                <form id="form" role="form" class="form-horizontal form-row-seperated" method="post"
                      enctype="multipart/form-data" action="{{ route('dashboard.notifications.store') }}">
                    @csrf
                    <div class="col-md-12">

                        {{-- CREATE FORM --}}
                        <h3 class="page-title">{{__('notification::dashboard.notifications.form.name')}}</h3>
                        <div class="col-md-12">

                            <div class="form-group">
                                <label class="col-md-2">
                                    {{__('notification::dashboard.notifications.form.msg_title')}}
                                    <span class="required">*</span>
                                </label>
                                <div class="col-md-10">
                                    <input type="text" name="title" class="form-control"
                                           data-name="title">
                                    <div class="help-block"></div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2">
                                    {{__('notification::dashboard.notifications.form.msg_body')}}
                                    <span class="required">*</span>
                                </label>
                                <div class="col-md-10">
                                        <textarea name="body" rows="10" cols="30"
                                                  class="form-control"
                                                  data-name="body"></textarea>
                                    <div class="help-block"></div>
                                </div>
                            </div>

                        </div>
                        {{-- END CREATE FORM --}}

                        {{-- PAGE ACTION --}}
                        <div class="col-md-12">
                            <div class="form-actions text-center">
                                @include('apps::dashboard.layouts._ajax-msg')
                                <div class="form-group">
                                    <button type="submit" id="submit" class="btn btn-lg blue">
                                        {{__('notification::dashboard.notifications.send_btn')}}
                                    </button>
                                </div>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
@stop
