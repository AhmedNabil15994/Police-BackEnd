<!-- Start JS FILES -->
<script src="{{ url('frontend/js/jquery-2.1.4.min.js') }}"></script>
<script src="{{ url('frontend/js/jquery-ui.min.js') }}"></script>
<script src="{{ url('frontend/js/bootstrap.min.js') }}"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.full.min.js"></script>

<script src="{{ url('frontend/js/owl.carousel.min.js') }}"></script>
<script src="{{ url('frontend/js/wow.min.js') }}"></script>
<script src="{{ url('frontend/js/chosen.jquery.min.js') }}"></script>
<script src="{{ url('frontend/js/jquery.mousewheel.min.js') }}"></script>
<script src="{{ url('frontend/js/smoothproducts.min.js') }}"></script>
<script src="https://maps.google.com/maps/api/js?key=AIzaSyBkdsK7PWcojsO-o_q2tmFOLBfPGL8k8Vg&amp;language=en"></script>

<script type="text/javascript" src="{{ url('frontend/custom/scripts/jquery.cookie.js') }}"></script>

@if(locale() == 'ar')
    <script src="{{ url('frontend/js/script-ar.js') }}"></script>
@else
    <script src="{{ url('frontend/js/script-en.js') }}"></script>
@endif

<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

@include('apps::frontend.layouts._js')

{{-- Start - Bind Js Code From Dashboard Daynamic --}}
{!! config('setting.custom_codes.js_before_body') ?? null !!}
{{-- End - Bind Js Code From Dashboard Daynamic --}}

@yield('externalJs')
