@extends('apps::dashboard.layouts.app')
@section('title', __('catalog::dashboard.products.routes.clone'))
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
                    <a href="{{ url(route('dashboard.products.index')) }}">
                        {{__('catalog::dashboard.products.routes.index')}}
                    </a>
                    <i class="fa fa-circle"></i>
                </li>
                <li>
                    <a href="#">{{__('catalog::dashboard.products.routes.clone')}}</a>
                </li>
            </ul>
        </div>

        <h1 class="page-title"></h1>

        <div class="row">
                <form id="form" role="form" class="form-horizontal form-row-seperated" method="post" enctype="multipart/form-data" action="{{route('dashboard.products.store')}}">
                @csrf
                <div class="col-md-12">

                    {{-- RIGHT SIDE --}}
                    <div class="col-md-3">
                        <div class="panel-group accordion scrollable" id="accordion2">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title"><a class="accordion-toggle"></a></h4>
                                </div>
                                <div id="collapse_2_1" class="panel-collapse in">
                                    <div class="panel-body">
                                        <ul class="nav nav-pills nav-stacked">
                                            <li class="active">
                                                <a href="#global_setting" data-toggle="tab">
                                                    {{ __('catalog::dashboard.products.form.tabs.general') }}
                                                </a>
                                            </li>
                                            <li class="">
                                                <a href="#categories" data-toggle="tab">
                                                    {{ __('catalog::dashboard.products.form.tabs.categories') }}
                                                </a>
                                            </li>
                                            <li class="">
                                                <a href="#stock" data-toggle="tab">
                                                    {{ __('catalog::dashboard.products.form.tabs.stock') }}
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#seo" data-toggle="tab">
                                                    {{ __('catalog::dashboard.products.form.tabs.seo') }}
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- PAGE CONTENT --}}
                    <div class="col-md-9">
                        <div class="tab-content">

                            {{-- UPDATE FORM --}}
                            <div class="tab-pane active fade in" id="global_setting">
                                <h3 class="page-title">{{__('catalog::dashboard.products.form.tabs.general')}}</h3>
                                <div class="col-md-10">
                                    @foreach (config('translatable.locales') as $code)
                                    <div class="form-group">
                                        <label class="col-md-2">
                                            {{__('catalog::dashboard.products.form.title')}} - {{ $code }}
                                        </label>
                                        <div class="col-md-9">
                                            <input type="text" name="title[{{$code}}]" class="form-control" data-name="title.{{$code}}" value="{{ $product->translate($code)->title }}">
                                            <div class="help-block"></div>
                                        </div>
                                    </div>
                                    @endforeach

                                    @foreach (config('translatable.locales') as $code)
                                    <div class="form-group">
                                        <label class="col-md-2">
                                            {{__('catalog::dashboard.products.form.description')}} - {{ $code }}
                                        </label>
                                        <div class="col-md-9">
                                            <textarea name="description[{{$code}}]" rows="8" cols="80" class="form-control {{is_rtl($code)}}Editor" data-name="description.{{$code}}">{{ $product->translate($code)->description }}</textarea>
                                            <div class="help-block"></div>
                                        </div>
                                    </div>
                                    @endforeach


                                    @foreach (config('translatable.locales') as $code)
                                    <div class="form-group">
                                        <label class="col-md-2">
                                            {{__('catalog::dashboard.products.form.short_description')}} - {{ $code }}
                                        </label>
                                        <div class="col-md-9">
                                            <textarea name="short_description[{{$code}}]" rows="8" cols="80" class="form-control" data-name="short_description.{{$code}}">{{ $product->translate($code)->short_description }}</textarea>
                                            <div class="help-block"></div>
                                        </div>
                                    </div>
                                    @endforeach

                                    <div class="form-group">
                                        <label class="col-md-2">
                                            {{__('catalog::dashboard.products.form.image')}}
                                        </label>
                                        <div class="col-md-9">
                                            <div class="input-group">
                                                <span class="input-group-btn">
                                                    <a data-input="image" data-preview="holder" class="btn btn-primary lfm">
                                                        <i class="fa fa-picture-o"></i>
                                                        {{__('apps::dashboard.general.upload_btn')}}
                                                    </a>
                                                </span>
                                                <input name="image" class="form-control image" type="text" readonly value="{{ url($product->image) }}">
                                                <span class="input-group-btn">
                                                    <a data-input="image" data-preview="holder" class="btn btn-danger delete">
                                                        <i class="glyphicon glyphicon-remove"></i>
                                                    </a>
                                                </span>
                                            </div>
                                            <span class="holder" style="margin-top:15px;max-height:100px;">
                                                <img src="{{ url($product->image) }}" alt="" style="height: 15rem;">
                                            </span>
                                            <input type="hidden" data-name="image">
                                            <div class="help-block"></div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-2">
                                            {{__('catalog::dashboard.products.form.status')}}
                                        </label>
                                        <div class="col-md-9">
                                            <input type="checkbox" class="make-switch" id="test" data-size="small" name="status" {{($product->status == 1) ? ' checked="" ' : ''}}>
                                            <div class="help-block"></div>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="tab-pane fade in" id="categories">
                                <h3 class="page-title">{{__('catalog::dashboard.products.form.tabs.categories')}}</h3>
                                <div id="jstree">
                                    @include('catalog::dashboard.tree.products.edit',['mainCategories' => $mainCategories])
                                </div>
                                <div class="form-group">
                                    <input type="hidden" name="category_id" id="root_category" value="" data-name="category_id">
                                    <div class="help-block"></div>
                                </div>
                            </div>

                            <div class="tab-pane fade in" id="stock">
                                <h3 class="page-title">{{__('catalog::dashboard.products.form.tabs.stock')}}</h3>
                                <div class="col-md-10">
                                    <div class="form-group">
                                        <label class="col-md-2">
                                            {{__('catalog::dashboard.products.form.cost_price')}}
                                        </label>
                                        <div class="col-md-9">
                                            <input type="text" name="cost_price" class="form-control" data-name="cost_price" value="{{ $product->cost_price }}">
                                            <div class="help-block"></div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2">
                                            {{__('catalog::dashboard.products.form.price')}}
                                        </label>
                                        <div class="col-md-9">
                                            <input type="text" name="price" class="form-control" data-name="price" value="{{ $product->price }}">
                                            <div class="help-block"></div>
                                        </div>
                                    </div>
                                    {{-- <div class="form-group">
                                        <label class="col-md-2">
                                            {{__('catalog::dashboard.products.form.sku')}}
                                        </label>
                                        <div class="col-md-9">
                                            <input type="text" name="sku" class="form-control" data-name="sku" value="{{ $product->sku }}">
                                            <div class="help-block"></div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2">
                                            {{__('catalog::dashboard.products.form.qty')}}
                                        </label>
                                        <div class="col-md-9">
                                            <input type="number" name="qty" class="form-control" data-name="qty" value="{{ $product->qty }}">
                                            <div class="help-block"></div>
                                        </div>
                                    </div> --}}
                                </div>
                            </div>

                            <div class="tab-pane fade in" id="seo">
                                <h3 class="page-title">{{__('catalog::dashboard.products.form.tabs.seo')}}</h3>
                                <div class="col-md-10">

                                    @foreach (config('translatable.locales') as $code)
                                    <div class="form-group">
                                        <label class="col-md-2">
                                            {{__('catalog::dashboard.products.form.meta_keywords')}} - {{ $code }}
                                        </label>
                                        <div class="col-md-9">
                                            <textarea name="seo_keywords[{{$code}}]" rows="8" cols="80" class="form-control" data-name="seo_keywords.{{$code}}"></textarea>
                                            <div class="help-block"></div>
                                        </div>
                                    </div>
                                    @endforeach

                                    @foreach (config('translatable.locales') as $code)
                                    <div class="form-group">
                                        <label class="col-md-2">
                                            {{__('catalog::dashboard.products.form.meta_description')}} - {{ $code }}
                                        </label>
                                        <div class="col-md-9">
                                            <textarea name="seo_description[{{$code}}]" rows="8" cols="80" class="form-control" data-name="seo_description.{{$code}}"></textarea>
                                            <div class="help-block"></div>
                                        </div>
                                    </div>
                                    @endforeach

                                </div>
                            </div>
                            {{-- END UPDATE FORM --}}

                        </div>
                    </div>

                    {{-- PAGE ACTION --}}
                    <div class="col-md-12">
                        <div class="form-actions">
                            @include('apps::dashboard.layouts._ajax-msg')
                            <div class="form-group">
                                <button type="submit" id="submit" class="btn btn-lg blue">
                                    {{__('apps::dashboard.general.add_btn')}}
                                </button>
                                <a href="{{url(route('dashboard.products.index')) }}" class="btn btn-lg red">
                                    {{__('apps::dashboard.general.back_btn')}}
                                </a>
                            </div>
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>
</div>
@stop

@section('scripts')

<script>
    // CATEGORIES TREE
    $(function() {
        $('#jstree').jstree();

        $('#jstree').on("changed.jstree", function(e, data) {
            $('#root_category').val(data.selected);
        });
    });

    // PRODUCT HAS RELATION WITH OFFER / NEW ARRIVAL
    $(function() {

        @if ($product->offer)
          $("input#offer-form").prop("disabled", false);
          $('.offer-form').css('display', '');
        @endif

        @if ($product->newArrival)
          $("input#arrival-form").prop("disabled", false);
          $('.arrival-form').css('display', '');
        @endif

    });

    // DISABLED OR UNDISABLED OF STATUS FORM
    $("#offer-status").click(function(e) {

        if ($('#offer-status').is(':checked')) {
            $("input#offer-form").prop("disabled", false);
            $('.offer-form').css('display', '');
        } else {
            $("input#offer-form").prop("disabled", true);
            $('.offer-form').css('display', 'none');
        }

    });

    // DISABLED OR UNDISABLED OF STATUS FORM
    $("#new-arraival-status").click(function(e) {

        if ($('#new-arraival-status').is(':checked')) {
            $("input#arrival-form").prop("disabled", false);
            $('.arrival-form').css('display', '');
        } else {
            $("input#arrival-form").prop("disabled", true);
            $('.arrival-form').css('display', 'none');
        }

    });

    // CHANGE STATUS OF CHECKBOX WITH 0 VALUE OR 1
    function checkFunction() {
        $('[name="offer_status"]').change(function() {
            if ($(this).is(':checked'))
                $(this).next().prop('disabled', true);
            else
                $(this).next().prop('disabled', false);
        });

        $('[name="arrival_status"]').change(function() {
            if ($(this).is(':checked'))
                $(this).next().prop('disabled', true);
            else
                $(this).next().prop('disabled', false);
        });

    }

    // GALLERY FORM / ADD NEW UPLOAD BUTTON
    $(document).ready(function() {
        var html = $("div.getGalleryForm").html();
        $(".addGallery").click(function(e) {
            e.preventDefault();
            $(".galleryForm").append(html);
            $('.lfm').filemanager('image');
        });
    });

    // DELETE UPLOAD BUTTON & IMAGE
    $(".galleryForm").on("click", ".delete-gallery", function(e) {
        e.preventDefault();
        $(this).closest('.form-group').remove();
    });

    var variatns_removed = [];

    $('.variants-delete').click(function() {
        var val = $(this).closest(".filter").find("input[name='variants_ids[]']").val();
        variatns_removed.push(val);
        $("input[name='removed_variants']").val(variatns_removed);

        $(this).closest('.filter').remove();
    });
</script>


<script>

$(".copy_variations_html").on("click", ".delete_options", function(e) {
    e.preventDefault();
    $(this).closest('.form-group').remove();
});


$(document).ready(function() {
  $(".load_variations").click(function(e) {
        e.preventDefault();

        var option_values = [];

        $.each($("input[name='option_values']:checked"), function(){
            option_values.push($(this).val());
        });

        $.ajax({
            type: 'GET',
            url: '{{ url(route('dashboard.values_by_option_id')) }}',
            data: {
              values_ids : option_values
            },
            dataType: 'html',
            encode: true,
            beforeSend: function( xhr ) {
              $('.load_variations').prop('disabled',true);
            }
        })
        .done(function(res) {
          $('.html_option_values').html(res);
          $('.load_variations').prop('disabled',false);
        })
        .fail(function(res) {
          console.log(res);
          alert('please select option values');
        });
    });
});
</script>

@endsection
