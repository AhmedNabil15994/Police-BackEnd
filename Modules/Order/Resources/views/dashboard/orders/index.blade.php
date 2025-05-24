@extends('apps::dashboard.layouts.app')
@section('title', __('order::dashboard.orders.index.title'))
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
                        <a href="#">{{__('order::dashboard.orders.index.title')}}</a>
                    </li>
                </ul>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="portlet light bordered">

                        {{-- DATATABLE FILTER --}}
                        <div class="row">
                            <div class="portlet box grey-cascade">
                                <div class="portlet-title">
                                    <div class="caption">
                                        <i class="fa fa-gift"></i>
                                        {{__('apps::dashboard.datatable.search')}}
                                    </div>
                                    <div class="tools">
                                        <a href="javascript:;" class="collapse" data-original-title="" title=""> </a>
                                    </div>
                                </div>
                                <div class="portlet-body" style="padding: 27px 12px 10px !important;">
                                    <div id="filter_data_table">
                                        <div class="panel-body">
                                            <form id="formFilter" class="horizontal-form">
                                                <div class="form-body">
                                                    <div class="row">

                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label class="control-label">
                                                                    {{__('apps::dashboard.datatable.form.date_range')}}
                                                                </label>
                                                                <div id="reportrange" class="btn default form-control">
                                                                    <i class="fa fa-calendar"></i> &nbsp;
                                                                    <span> </span>
                                                                    <b class="fa fa-angle-down"></b>
                                                                    <input type="hidden" name="from">
                                                                    <input type="hidden" name="to">
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <label class="control-label">
                                                                    {{__('apps::dashboard.datatable.form.soft_deleted')}}
                                                                </label>
                                                                <div class="mt-radio-list">
                                                                    <label class="mt-radio">
                                                                        {{__('apps::dashboard.datatable.form.delete_only')}}
                                                                        <input type="radio" value="only"
                                                                               name="deleted"/>
                                                                        <span></span>
                                                                    </label>
                                                                    <label class="mt-radio">
                                                                        {{__('apps::dashboard.datatable.form.with_deleted')}}
                                                                        <input type="radio" value="with"
                                                                               name="deleted"/>
                                                                        <span></span>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        {{--<div class="col-md-2">
                                                            <div class="form-group">
                                                                <label class="control-label">
                                                                    {{__('apps::dashboard.datatable.form.status')}}
                                                                </label>
                                                                <div class="mt-radio-list">
                                                                    <label class="mt-radio">
                                                                        {{__('apps::dashboard.datatable.form.active')}}
                                                                        <input type="radio" value="1" name="status" />
                                                                        <span></span>
                                                                    </label>
                                                                    <label class="mt-radio">
                                                                        {{__('apps::dashboard.datatable.form.unactive')}}
                                                                        <input type="radio" value="0" name="status" />
                                                                        <span></span>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>--}}

                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label class="control-label">
                                                                    {{__('apps::dashboard.datatable.form.search_by_vendor')}}
                                                                </label>
                                                                <select name="vendor"
                                                                        class="searchableSelect form-control select2">
                                                                    <option value=""></option>
                                                                    @foreach ($vendors as $item)
                                                                        <option value="{{ $item->id }}">
                                                                            {{ optional($item->translate(locale()))->title }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </form>
                                            <div class="form-actions">
                                                <button class="btn btn-sm green btn-outline filter-submit margin-bottom"
                                                        id="search">
                                                    <i class="fa fa-search"></i>
                                                    {{__('apps::dashboard.datatable.search')}}
                                                </button>
                                                <button class="btn btn-sm red btn-outline filter-cancel">
                                                    <i class="fa fa-times"></i>
                                                    {{__('apps::dashboard.datatable.reset')}}
                                                </button>
                                            </div>

                                            @permission('statistics')
                                            <div class="form-actions mt-4 text-center">
                                                <div class="col-md-6">
                                                    <h4>{{__('apps::dashboard.datatable.orders_total')}} </h4>
                                                    <span id="sum_total_orders">0</span>
                                                </div>
                                                <div class="col-md-6">
                                                    <h4>{{__('apps::dashboard.datatable.orders_count')}} </h4>
                                                    <span id="count_orders">0</span>
                                                </div>
                                            </div>
                                            @endpermission

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- END DATATABLE FILTER --}}

                        <div class="portlet-title">
                            <div class="caption font-dark">
                                <i class="icon-settings font-dark"></i>
                                <span class="caption-subject bold uppercase">
                                {{__('order::dashboard.orders.index.title')}}
                            </span>
                            </div>
                        </div>

                        {{-- DATATABLE CONTENT --}}
                        <div class="portlet-body">
                            <table class="table table-striped table-bordered table-hover" id="dataTable">
                                <thead>
                                <tr>
                                    <th>
                                        <a href="javascript:;" onclick="CheckAll()">
                                            {{__('apps::dashboard.general.select_all_btn')}}
                                        </a>
                                    </th>
                                    <th>#</th>
                                    <th>{{__('order::dashboard.orders.datatable.client')}}</th>
                                    <th>{{__('order::dashboard.orders.datatable.mobile')}}</th>
                                    <th>{{__('order::dashboard.orders.datatable.subtotal')}}</th>
                                    <th>{{__('order::dashboard.orders.datatable.shipping')}}</th>
                                    <th>{{__('order::dashboard.orders.datatable.total')}}</th>
                                    <th>{{__('order::dashboard.orders.datatable.delivery_pickup_type')}}</th>
                                    <th>{{__('order::dashboard.orders.datatable.status')}}</th>
                                    <th>{{__('order::dashboard.orders.datatable.method')}}</th>
                                    <th>{{__('order::dashboard.orders.datatable.created_at')}}</th>
                                    <th>{{__('order::dashboard.orders.datatable.options')}}</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                        <div class="row">
                            <div class="form-group">

                                <button type="button" class="btn btn-info btn-sm"
                                        onclick="printAllChecked('{{ url(route('dashboard.orders.print_selected_items')) }}', 'orders')">
                                    {{__('apps::dashboard.datatable.print_all_btn')}}
                                </button>

                                <button type="submit" id="deleteChecked" class="btn red btn-sm"
                                        onclick="deleteAllChecked('{{ url(route('dashboard.orders.deletes')) }}')">
                                    {{__('apps::dashboard.datatable.delete_all_btn')}}
                                </button>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('scripts')

    <script>
        function tableGenerate(data = '') {

            var dataTable =
                $('#dataTable').DataTable({

                    'fnDrawCallback': function (data) {
                        $('#count_orders').html(data.json.recordsTotal);
                        $('#sum_total_orders').html(data.json.recordsTotalSum);
                    },
                    "createdRow": function (row, data, dataIndex) {
                        if (data["deleted_at"] != null) {
                            $(row).addClass('danger');
                        }

                        if (data["unread"] == false) {
                            $(row).addClass('danger');
                        }
                    },
                    ajax: {
                        url: "{{ url(route('dashboard.orders.datatable')) }}",
                        type: "GET",
                        data: {
                            req: data,
                        },
                    },
                    language: {
                        url: "//cdn.datatables.net/plug-ins/1.10.16/i18n/{{ucfirst(LaravelLocalization::getCurrentLocaleName())}}.json"
                    },
                    stateSave: true,
                    processing: true,
                    serverSide: true,
                    // responsive: !0,
                    responsive: true,
                    order: [[1, "desc"]],
                    columns: [
                        {data: 'id', className: 'dt-center'},
                        {data: 'id', className: 'dt-center'},
                        {data: 'client', className: 'dt-center'},
                        {data: 'mobile', className: 'dt-center'},
                        {data: 'subtotal', className: 'dt-center'},
                        {data: 'shipping', className: 'dt-center'},
                        {data: 'total', className: 'dt-center'},
                        {data: 'pickup_delivery', className: 'dt-center'},
                        {data: 'order_status_id', className: 'dt-center'},
                        {data: 'transaction', className: 'dt-center', orderable: false},
                        {data: 'created_at', className: 'dt-center'},
                        {data: 'id'},
                    ],
                    columnDefs: [
                        {
                            responsivePriority: 2,
                            targets: 0,
                            width: '30px',
                            className: 'dt-center',
                            orderable: false,
                            render: function (data, type, full, meta) {
                                return `<label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                          <input type="checkbox" value="` + data + ` class="group-checkable" name="ids">
                          <span></span>
                        </label>
                      `;
                            },
                        },
                        {
                            responsivePriority: 1,
                            targets: -1,
                            width: '13%',
                            title: '{{__('order::dashboard.orders.datatable.options')}}',
                            className: 'dt-center',
                            orderable: false,
                            render: function (data, type, full, meta) {

                                // Show
                                var showUrl = '{{ route("dashboard.orders.show", ":id") }}';
                                showUrl = showUrl.replace(':id', full.order_tracking_id);

                                // Delete
                                var deleteUrl = '{{ route("dashboard.orders.destroy", ":id") }}';
                                deleteUrl = deleteUrl.replace(':id', full.order_tracking_id);

                                return `@permission('show_orders')
                                            <a href="` + showUrl + `" class="btn btn-sm btn-warning" title="Show">
                                              <i class="fa fa-eye"></i>
                                            </a>
                                        @endpermission
                                        @permission('delete_orders')
                                        @csrf<a href="javascript:;" onclick="deleteRow('` + deleteUrl + `')" class="btn btn-sm red">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                        @endpermission
                                        `;

                            },
                        },
                        {
                            responsivePriority: 3,
                            targets: 4,
                        },
                        {
                            responsivePriority: 4,
                            targets: 5,
                        },
                    ],
                    dom: 'Bfrtip',
                    lengthMenu: [
                        [10, 25, 50, 100, 500],
                        ['10', '25', '50', '100', '500']
                    ],
                    buttons: [
                        {
                            extend: "pageLength",
                            className: "btn blue btn-outline",
                            text: "{{__('apps::dashboard.datatable.pageLength')}}",
                            exportOptions: {
                                stripHtml: false,
                                columns: ':visible',
                                columns: [1, 2, 3, 4, 5]
                            }
                        },
                        {
                            extend: "print",
                            className: "btn blue btn-outline",
                            text: "{{__('apps::dashboard.datatable.print')}}",
                            exportOptions: {
                                stripHtml: false,
                                columns: ':visible',
                                columns: [1, 2, 3, 4, 5]
                            }
                        },
                        {
                            extend: "pdf",
                            className: "btn blue btn-outline",
                            text: "{{__('apps::dashboard.datatable.pdf')}}",
                            exportOptions: {
                                stripHtml: false,
                                columns: ':visible',
                                columns: [1, 2, 3, 4, 5]
                            }
                        },
                        {
                            extend: "excel",
                            className: "btn blue btn-outline ",
                            text: "{{__('apps::dashboard.datatable.excel')}}",
                            exportOptions: {
                                stripHtml: false,
                                columns: ':visible',
                                columns: [1, 2, 3, 4, 5]
                            }
                        },
                        {
                            extend: "colvis",
                            className: "btn blue btn-outline",
                            text: "{{__('apps::dashboard.datatable.colvis')}}",
                            exportOptions: {
                                stripHtml: false,
                                columns: ':visible',
                                columns: [1, 2, 3, 4, 5]
                            }
                        }
                    ]
                });
        }

        jQuery(document).ready(function () {
            tableGenerate();

            $(".searchableSelect").select2({
                placeholder: "{{__('apps::dashboard.datatable.form.select_option')}}",
                allowClear: true
            });
        });
    </script>

@stop
