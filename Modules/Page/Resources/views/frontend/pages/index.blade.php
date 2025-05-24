@extends('apps::frontend.layouts.master')
@section('title', $page->translate(locale())->title)
@section('content')

    <div class="second-header d-flex align-items-center"
         style="background: url('{{ config('setting.images.about_us_logo') ? url(config('setting.images.about_us_logo')) : url('frontend/images/pages.png') }}')">
        <div class="container">
            <h1>{{ $page->translate(locale())->title }}</h1>
        </div>
    </div>
    <div class="innerPage">
        <div class="container">

            <div class="sig-page-section">
                {!! $page->translate(locale())->description !!}
            </div>
        </div>
    </div>

@endsection

@section('externalJs')

    <script></script>

@endsection
