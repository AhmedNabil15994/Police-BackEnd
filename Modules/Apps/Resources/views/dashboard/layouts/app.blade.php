<!DOCTYPE html>
<html lang="{{ locale() }}" dir="{{ is_rtl() }}">

@if (is_rtl() == 'rtl')
    @include('apps::dashboard.layouts._head_rtl')
@else
    @include('apps::dashboard.layouts._head_ltr')
@endif

<body class="page-header-fixed page-sidebar-closed-hide-logo page-content-white page-md">
<div class="page-wrapper">

    @include('apps::dashboard.layouts._header')

    <div class="clearfix"></div>

    <div class="page-container">
        @include('apps::dashboard.layouts._aside')

        @yield('content')
    </div>

    @include('apps::dashboard.layouts._footer')
</div>

<audio controls id="audio-notify-alarm" preload="auto" muted="muted" style="display: none">
    <source src="{{ url('uploads/media/doorbell-5.mp3') }}">
    The Native audio playback is not supported.
</audio>

@include('apps::dashboard.layouts._jquery')
@include('apps::dashboard.layouts._js')
</body>
</html>
