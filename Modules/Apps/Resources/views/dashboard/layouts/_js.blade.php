@if (is_rtl() == 'rtl')
    <script src="/admin/assets/global/plugins/bootstrap-daterangepicker/daterangepicker-rtl.min.js"
            type="text/javascript">
    </script>
@else
    <script src="/admin/assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js" type="text/javascript">
    </script>
@endif

<script src="/vendor/laravel-filemanager/js/single-stand-alone-button.js"></script>


<script>
    $(document).ready(function () {
        $('#clickmewow').click(function () {
            $('#radio1003').attr('checked', 'checked');
        });
    })
</script>

<script type="text/javascript">
    $(document).ready(function () {
        $(".emojioneArea").emojioneArea();
    });
</script>

<style>
    .emojionearea .emojionearea-picker.emojionearea-picker-position-top {
        margin-bottom: -286px !important;
        right: -14px;
        z-index: 90000000000000;
    }

    .emojionearea .emojionearea-button.active + .emojionearea-picker-position-top {
        margin-top: 0px !important;
    }
</style>

<script>
    // DELETE ROW FROM DATATABLE
    function deleteRow(url, rowId = '', flag = '') {
        var _token = $('input[name=_token]').val();

        bootbox.confirm({
            message: '{{__('apps::dashboard.general.delete_message')}}',
            buttons: {
                confirm: {
                    label: '{{__('apps::dashboard.general.yes_btn')}}',
                    className: 'btn-success'
                },
                cancel: {
                    label: '{{__('apps::dashboard.general.no_btn')}}',
                    className: 'btn-danger'
                }
            },

            callback: function (result) {
                if (result) {

                    $.ajax({
                        method: 'DELETE',
                        url: url,
                        data: {
                            _token: _token
                        },
                        success: function (msg) {
                            toastr["success"](msg[1]);
                            if (flag === 'advertising_groups') {
                                $('#advertisingGroup-' + rowId).remove();
                            } else {
                                $('#dataTable').DataTable().ajax.reload();
                            }
                        },
                        error: function (msg) {
                            toastr["error"](msg[1]);
                            $('#dataTable').DataTable().ajax.reload();
                        }
                    });

                }
            }
        });
    }

    // DELETE ROW FROM DATATABLE
    function deleteAllChecked(url) {
        var someObj = {};
        someObj.fruitsGranted = [];

        $("input:checkbox").each(function () {
            var $this = $(this);

            if ($this.is(":checked")) {
                someObj.fruitsGranted.push($this.attr("value"));
            }
        });

        var ids = someObj.fruitsGranted;

        bootbox.confirm({
            message: '{{__('apps::dashboard.general.deleteAll_message')}}',
            buttons: {
                confirm: {
                    label: '{{__('apps::dashboard.general.delete_yes_btn')}}',
                    className: 'btn-success'
                },
                cancel: {
                    label: '{{__('apps::dashboard.general.delete_no_btn')}}',
                    className: 'btn-danger'
                }
            },

            callback: function (result) {
                if (result) {

                    $.ajax({
                        type: "GET",
                        url: url,
                        data: {
                            ids: ids,
                        },
                        success: function (msg) {

                            if (msg[0] == true) {
                                toastr["success"](msg[1]);
                                $('#dataTable').DataTable().ajax.reload();
                            } else {
                                toastr["error"](msg[1]);
                            }

                        },
                        error: function (msg) {
                            toastr["error"](msg[1]);
                            $('#dataTable').DataTable().ajax.reload();
                        }
                    });

                }
            }
        });
    }

    // Print Selected Rows From DATATABLE
    function printAllChecked(url, page = '') {
        var someObj = {};
        someObj.fruitsGranted = [];

        $("input:checkbox").each(function () {
            var $this = $(this);

            if ($this.is(":checked")) {
                var val = $this.attr("value");
                val = val.split(" ")[0];
                someObj.fruitsGranted.push(val);
            }
        });

        var ids = someObj.fruitsGranted;
        if (ids != null && ids != '') {
            window.location.href = url + '?page=' + page + '&ids=' + ids;
        }
    }

    $(document).ready(function () {

        var start = moment().subtract(29, 'days');
        var end = moment();

        function cb(start, end) {
            if (start.isValid() && end.isValid()) {
                $('#reportrange span').html(start.format('YYYY-MM-DD') + ' - ' + end.format('YYYY-MM-DD'));
                $('input[name="from"]').val(start.format('YYYY-MM-DD'));
                $('input[name="to"]').val(end.format('YYYY-MM-DD'));
            } else {
                $('#reportrange .form-control').val('Without Dates');
                $('input[name="from"]').val('');
                $('input[name="to"]').val('');
            }
        }

        $('#reportrange').daterangepicker({
            startDate: start,
            endDate: end,
            ranges: {
                '{{__('apps::dashboard.general.date_range.today')}}': [moment(), moment()],
                '{{__('apps::dashboard.general.date_range.yesterday')}}': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                '{{__('apps::dashboard.general.date_range.7days')}}': [moment().subtract(6, 'days'), moment()],
                '{{__('apps::dashboard.general.date_range.30days')}}': [moment().subtract(29, 'days'), moment()],
                '{{__('apps::dashboard.general.date_range.month')}}': [moment().startOf('month'), moment().endOf('month')],
                '{{__('apps::dashboard.general.date_range.last_month')}}': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
            },
            @if (is_rtl() == 'rtl')
            opens: 'left',
            @endif
            buttonClasses: ['btn'],
            applyClass: 'btn-primary',
            cancelClass: 'btn-danger',
            format: 'YYYY-MM-DD',
            separator: 'to',
            locale: {
                applyLabel: '{{__('apps::dashboard.general.date_range.save')}}',
                cancelLabel: '{{__('apps::dashboard.general.date_range.cancel')}}',
                fromLabel: 'from',
                toLabel: 'to',
                customRangeLabel: '{{__('apps::dashboard.general.date_range.custom')}}',
                firstDay: 1
            }
        }, cb);

        cb(start, end);

    });

</script>

<script>
    $('.lfm').filemanager('image');

    $('.delete').click(function () {
        $(this).closest('.form-group').find($('.' + $(this).data('input'))).val('');
        $(this).closest('.form-group').find($('.' + $(this).data('preview'))).html('');
    });

</script>

<script src="https://js.pusher.com/5.0/pusher.min.js"></script>

<script>
    {{--var audioAlert = new Audio('{{ url('uploads/media/doorbell-5.mp3') }}');--}}
    var audioAlert = new Audio('{{ url('uploads/media/amazing_notification_2.mp3') }}');
    var pusher = new Pusher('{{env('PUSHER_APP_KEY')}}', {
        cluster: '{{env('PUSHER_APP_CLUSTER')}}',
        forceTLS: true
    });
    pusher.subscribe('{{ config('core.config.constants.DASHBOARD_CHANNEL') }}').bind('{{ config('core.config.constants.DASHBOARD_ACTIVITY_LOG') }}', function (data) {
        $('#dataTable').DataTable().ajax.reload();
        if (data.activity.type === 'orders') {
            openActivity(data.activity);
        }
    });

    function playSound() {
        {{--var audio = new Audio('{{ url('uploads/media/doorbell-5.mp3') }}');--}}
        /*var audio = $('#audio-notify-alarm').get(0);
        audio.play();*/

        audioAlert.loop = true;
        audioAlert.play();
    }

    function stopSound() {
        audioAlert.pause();
        audioAlert.currentTime = 0;
    }

    function openActivity(response) {
        playSound();
        {{--toastr["success"]("{{__('apps::dashboard.general.new_order_received')}}");--}}
        // var showUrl = response.url;
        var showUrl = response.base_url;
        swal({
            title: response.description_{{locale()}},
            icon: "success",
            buttons: true,
            dangerMode: true,
        }).then((willDone) => {
            if (willDone) {
                window.location.href = showUrl;
            }
            stopSound();
        });
    }

</script>

<script>
    $('#restaurantSelect').on('select2:select', function (e) {
        var vData = e.params.data;
        var dataName = $(this).attr("data-name");
        var selectedText = vData.text.trim();
        var selectedValue = vData.id;
        var restaurantLoader = $('#restaurantLoader');

        if (vData.id != null) {

            restaurantLoader.show();
            $('#branchesSelect-' + selectedValue).empty();

            $.ajax({
                url: "{{route('dashboard.get_branches_by_restaurant')}}?restaurant_id=" + selectedValue,
                type: 'get',
                dataType: 'JSON',
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function () {
                },
                success: function (data) {
                    if (data.status == true && dataName == 'single') {
                        $('.branch-select').remove();
                    }
                },
                error: function (data) {
                    restaurantLoader.hide();
                    displayErrors(data);
                },
                complete: function (data) {

                    if (data.responseJSON.status == true) {

                        restaurantLoader.hide();
                        let selectTag = ``;
                        if (dataName == 'single') {
                            selectTag += `<select id="branchesSelect-${selectedValue}" name="branch_id"
                                 class="form-control select2-allow-clear">`;
                        } else {
                            selectTag += `<select id="branchesSelect-${selectedValue}" name="branches[${selectedValue}][]"
                                 class="form-control select2-allow-clear" multiple="">`;
                        }

                        let item = `<div class="form-group branch-select" id="branchArea-${selectedValue}">
                                     <div class="col-md-2"></div>
                                     <div class="col-md-9">
                                     <label>{{__('catalog::dashboard.products.form.branches')}} : # ${selectedText}</label>
                                     ${selectTag}
                                     `;
                        $.each(data.responseJSON.data, function (i, value) {
                            item +=
                                `<option value="${value.id}">
                                     ${value.title}
                                 </option>`;
                        });
                        item += `< /select>
                             </div></div>`;
                        $('#restaurantBranches').prepend(item);
                    }

                    $("#branchesSelect-" + selectedValue).select2({
                        placeholder: "{{__('apps::dashboard.general.select_option')}}",
                    });
                },
            });

        }
    });

    $('#restaurantSelect').on('select2:unselect', function (e) {
        var data = e.params.data;
        $('#branchArea-' + data.id).remove();
    });
</script>
