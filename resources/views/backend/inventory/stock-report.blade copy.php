@extends('layouts.backend.app')
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.0/css/toastr.css" rel="stylesheet" />
@php
$company_name= \App\Setting::where('config_name', 'company_name')->first();
$company_address= \App\Setting::where('config_name', 'company_address')->first();
$address2= \App\Setting::where('config_name', 'address2')->first();
$company_tele= \App\Setting::where('config_name', 'company_tele')->first();
$company_email= \App\Setting::where('config_name', 'company_email')->first();
$trn_no= \App\Setting::where('config_name', 'trn_no')->first();
$i=1;
@endphp
@section('content')
<style>
    @media print{
        .thermal-print2{
            display: none !important;
        }
        .thermal-table{
            width: 75px;
            font-size: 10px;
            max-width: 75px;
        }
        .dropdown-filter-dropdown{
            display: none;
        }
        .nav.nav-tabs ~ .tab-content {
            border-left: 1px solid #fff !important;
            border-right: 1px solid #fff !important;
            border-bottom: 1px solid #fff !important;
            padding-left: 0;
        }
        .centered {
            text-align: center;
            align-content: center;
        }

        @page {
            margin: 0px !important;
            padding: 0px !important;
        }
    }
        thead {
        background: #34465b;
        color: #fff !important;
    }
    th{
        color: #fff !important;
        font-size: 11px !important;
        height: 25px !important;
        text-align: center !important;
    }
</style>
    @include('layouts.backend.partial.style')

    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-body">
                @include('clientReport.purchase._header', ['activeMenu' => 'purchase_expense'])
                <div class="tab-content journaCreation">
                    <div id="journaCreation" class="tab-pane bg-white active">
                        <div class="py-1 px-1">
                            @include('clientReport.purchase._subhead_purchase', ['activeMenu' => 'inventory'])
                        </div>
                        <section id="widgets-Statistics row">
                            <div class="col-md-12">
                                <div class="cardStyleChange">
                                    <div class="card-body bg-white">
                                            @include('backend.inventory.sub-head', ['activeMenu' => 'inventory'])
                                            <div class="row">
                                                <div class="col-12 p-0">
                                                    <table class="table table-bordered table-sm table-striped 2filter-table thermal-table" id="print-table"  style="width: 850px !important;">
                                                        
                                                        <thead class="thead">
                                                            <tr class="trFontSize text-center">
                                                                <th>Account Head </th>
                                                                <th>Opening Stock </th>
                                                                <th>Qty In </th>
                                                                <th>Qty Out </th>
                                                                <th>Closing Stock</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="purch-body">
                                                            @foreach ($products as $key => $item)
                                                            @php
                                                                $values = $item->openning_product_office( $from, $item->id);
                                                                $quantity = $item->quantity_in( $from, $to, $item->id);
                                                                $quantity_out = $item->quantity_out( $from, $to, $item->id);
                                                                $closing_qunatity = $values['opening'] + $quantity['quantity_in'] - $quantity_out;
                                                                $return_product = $item->return_product( $from, $to, $item->id);
                                                                $c_stock = $item->committed_stock()->sum('qty');
                                                            @endphp
                                                                <tr class="account_head_allocation text-center trFontSize" onclick="BtnProjectItem(this)">
                                                                    <input type="hidden" name="" class="expense_head" value="{{$item->id}}">
                                                                    <input type="hidden" name="" class="qty" value="{{$closing_qunatity}}">
                                                                    <td>{{ $item->product_name }}</td>
                                                                    <td>{{ floatval($values['opening'])}}</td>
                                                                    <td>{{ floatval($quantity['quantity_in'])}}</td>
                                                                    <td>{{ floatval($quantity_out) }}</td>
                                                                    <td>{{ floatval($closing_qunatity)}}</td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
        <div class="modal fade bd-example-modal-lg" id="project_expense_model" tabindex="-1" rrole="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header" style="padding: 5px 18px;background:#364a60;">
                    <h5 class="modal-title" id="exampleModalLabel" style="font-family:Cambria;font-size: 2rem;color:white;">Project Expense Assign</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <section id="widgets-Statistics" class="mr-1 ml-1 mb-2 accountHeadStyle HeadStyle">
                    <div class="col-12 mt-2 m-0 p-0" id="project_expense_model_content">

                    </div>
                </section>
            </div>
        </div>
    </div>
    <div class="modal fade bd-example-modal-lg" id="project_expense_model1" tabindex="-1" rrole="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div id="project_expense_model_content1">

                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
        window.onafterprint = function() {
            location.reload();
        };
        $(document).on('click', '.thermal_print_button', function(){
            $('.thermal-print').addClass('thermal-print2')
            window.print();
        })
        $(document).ready(function() {
            $(document).on('change', '.from-date', function() {
                var date = $(this).val();
                $('.to-date').val(date);
                $('.to-date-toggle').show();
            });
            $(document).on('change', '.to-date', function() {
                var to_date = $(this).val();
                var from_date = $('.from-date').val();

                var date1Milliseconds = Date.parse(from_date.replace(/(\d{2})\/(\d{2})\/(\d{4})/,
                    '$2/$1/$3'));
                var date2Milliseconds = Date.parse(to_date.replace(/(\d{2})\/(\d{2})\/(\d{4})/,
                '$2/$1/$3'));
                var date1 = new Date(date1Milliseconds);
                var date2 = new Date(date2Milliseconds);
                if (date1 > date2) {
                    toastr.warning('Please select greater than from date');
                    $(this).val(from_date);
                }

            })
        })
        function BtnProjectItem(r){
            var current_tr = $(r).closest('tr');
            var head_id = current_tr.find('.expense_head').val();
            var qty = current_tr.find('.qty').val();
            if(!head_id){
                toastr.warning('Please select account head');
            }else if(!qty){
                toastr.warning('Please give QTY');
            }else{
                $.ajax({
                    url: "{{ route('project-expense') }}",
                    type: "post",
                    cache: false,
                    data: {
                        _token: '{{ csrf_token() }}',
                        head_id: head_id,
                        qty: qty,
                    },
                    success: function(response) {
                        $('#project_expense_model_content').empty().append(response);
                        $('#project_expense_model').modal('show');
                    }
                });
            }
        }
        function task_BtnAdd() {
            var newRow = $("#task_TRow").clone();
            newRow.removeClass("d-none");
            newRow.find("input, select,textarea").val('').attr('name', function(index, name) {
                return name.replace(/\[\d+\]/, '[' + ($('#task_TBody tr').length) + ']');
            });
            newRow.find("th").first().html($('#task_TBody tr').length + 1);
            newRow.appendTo("#task_TBody");
            newRow.find(".common-select2").select2();
        }
        $(document).on("change", ".project_id", function(e) {
            var project = $(this).val();
            var tr_object = $(this).closest("tr");
            if(project){
                $.ajax({
                    url: "{{ route('find-project-task') }}",
                    type: "post",
                    cache: false,
                    data: {
                        _token: '{{ csrf_token() }}',
                        project: project,
                    },
                    success: function(response) {
                        tr_object.find(".task_id").empty().append(response);
                    }
                });
            }
        });
        $(document).on("change", ".task_id", function(e) {
            var task_id = $(this).val();
            var tr_object = $(this).closest("tr");
            if(task_id){
                $.ajax({
                    url: "{{ route('find-project-task-item') }}",
                    type: "post",
                    cache: false,
                    data: {
                        _token: '{{ csrf_token() }}',
                        task_id: task_id,
                    },
                    success: function(response) {
                        tr_object.find(".task_item").empty().append(response);
                    }
                });
            }
        });
        
        function BtnDel(v) {
            $(v).parent().parent().remove();
            $("#TBody").find("tr").each(function(index) {
                $(this).find("th").first().html(index);
            });
            project_total_qty();
        }
        function project_total_qty(){
            total = 0;
            $('.task_qty').each(function() {
                var this_qty = $(this).val();
                this_qty = (this_qty === '') ? 0 : this_qty;
                var this_qty = parseFloat(this_qty);
                total = total + this_qty;
            });
            $(".task_total_qty").val((total.toFixed(2)));
        }
        $(document).on('keyup', '.task_qty', function(e){
            var qty = $(this).val();
            var current_tr = $(this).closest('tr');
            var max_amount = $('#max_amount').val();
            var max_qty = $('#max_qty').val();
            var rate = Number(max_amount)/Number(max_qty);
            console.log(rate, max_amount, max_qty);
            current_tr.find('.task_amount').val(rate*qty);
            project_total_qty();
        });
        $(document).on('submit', '#project_expense_store', function(e) {
            e.preventDefault();
            var form = $(this);
            var url = form.attr('action');
            var data = new FormData(this);
            var task_total_amount = $('#task_total_amount').val();
            var max_amount = $('#max_amount').val();
            var max_qty = $('#max_qty').val();
            var task_total_qty = $('#task_total_qty').val();
            if(Number(task_total_amount)>Number(max_amount)){
                toastr.warning('Please check your amount');
            }else if(Number(task_total_qty)>Number(max_qty)){
                toastr.warning('Please check your QTY');
            }
            else{
                $.ajax({
                    url: url,
                    method: 'POST',
                    data: data,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(response) {
                        $("#project_expense_model").modal('hide');
                        $('#project_expense_model_content1').empty().append(response);
                        $('#project_expense_model1').modal('show');
                        toastr.success('Add Success');
                    }
                });
            }
        });
        $(document).on('mouseenter', '.datepicker', function(){
            $(this).datepicker({
                changeMonth: true,
                changeYear: true,
                yearRange: "-1000:+1000",
                dateFormat: "dd/mm/yy",
            });
        });
    </script>
@endpush
