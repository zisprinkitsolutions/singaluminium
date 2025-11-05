@extends('layouts.backend.app')
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.0/css/toastr.css" rel="stylesheet" />
@section('content')
@include('layouts.backend.partial.style')
<style>
        .changeColStyle span {
            min-width: 16%;
        }

        .changeColStyle .select2-container--default .select2-selection--single .select2-selection__arrow b {
            display: none;
        }

        .journaCreation {
            background: #1214161c;
        }

        .transaction_type {
            padding-right: 5px;
            padding-left: 5px;
            padding-bottom: 5px;
        }

        @media only screen and (max-width: 1500px) {
            .custome-project span {
                max-width: 140px;
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
        td
        {
            font-size: 12px !important;
            height: 25px !important;
            text-align: center;
        }

        .table-sm th, .table-sm td {
            padding: 4px 6px;
        }

        #invoice td{
            padding: 13px 6px !important;
        }

        tr:nth-child(even) {
            background-color: #c8d6e357;
        }
        tr{
            cursor: pointer;
        }

        .card-body {
            flex: 1 1 auto;
            min-height: 1px;
            padding: 0rem !important;
        }

        .card {
            margin-bottom: 0rem;
            box-shadow: none;
        }
        .select2-results__option{
            /* background: #da7d7d4b !important; */
            padding: 0 5px !important;
        }
        .change-body{
            display: none !important;
        }
        .file-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px;
            border: 1px solid #ddd;
            margin-bottom: 5px;
        }

        .delete-btn {
            background: #ff4444;
            color: white;
            border: none;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            cursor: pointer;
        }

        .delete-btn:hover {
            background: #cc0000;
        }
        .text-white{
            color: #fff !important;
        }
        .td-width .select2-container{
            width: 290px !important;
        }
        .master-account-head{
            display: none !important;
        }
        .user-table-body{
            padding: 0 !important;
        }
        .master-account-body{
            padding: 0 !important;
        }
        .master-account-button{
            display: none !important;
        }
        @media print{
            .nav.nav-tabs ~ .tab-content{
                border: #fff !important;
            }
            .print-hidden{
                display: none !important;
            }
        }

        .custom-btn{
            width: 25px;
            height: 25px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            border: none;
            border-radius: 5px;
        }
</style>
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-body">
            @include('clientReport.purchase._header', ['activeMenu' => 'purchase_expense'])
            <div class="tab-content journaCreation">
                <div id="journaCreation" class="tab-pane bg-white active pb-3">
                    <section id="widgets-Statistics" style="padding-left: 8px;">
                        @include('layouts.backend.partial.modal-header-info')
                            <div class="col-md-12 pt-2">
                                <div class="cardStyleChange" >
                                    <div class="card-body bg-white print-hidden">
                                        <div class="d-flex justify-content-between align-items-center print-hidden">
                                            <div class="d-flex align-items-center" style="width: 70% !important;">
                                                @if(Auth::user()->hasPermission('Expense_Create'))
                                                <button class="btn btn-primary inputFieldHeight expense_create_model" style="padding:3px 8px !important;"> New Expense </button>
                                                @endif
                                                <button class="btn btn-primary inputFieldHeight stock_inventory_model" style="padding:3px 8px !important; margin-left:10px;">&nbsp;&nbsp;&nbsp;&nbsp; Inventory &nbsp;&nbsp;&nbsp;&nbsp;</button>

                                                <a href="{{route('project.expense.report')}}" style="margin-left:10px; padding:3px 8px !important;" class="btn btn-primary inputFieldHeight"> Summary </a>

                                                <div style="padding-left:10px;">
                                                    <input type="text" name="search" id="search" class="form-control inputFieldHeight" placeholder="Search by Expense No">
                                                </div>

                                                <div style="padding-left:10px;width: 25% !important;">
                                                    <select name="party_search" id="party_search" class="common-select2 inputFieldHeight w-100">
                                                        <option value="">Select...</option>
                                                        @foreach ($pInfos as $party)
                                                            <option value="{{ $party->id }}">{{ $party->pi_name }} </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div style="padding-left:10px;">
                                                    <input type="text" name="date_search" id="date_search" class="form-control inputFieldHeight datepicker" placeholder="Search by Date">
                                                </div>
                                            </div>

                                            <div class="d-flex text-right">
                                                <button class="btn btn-info inputFieldHeight" style="padding:3px 8px !important;  width: 120px; margin-right:5%;" data-toggle="modal" data-target="#excel_import"> Excel Import  </button>
                                                <button class="btn btn-info inputFieldHeight" style="padding:3px 8px !important; width: 120px; margin-right:5%;" onclick="window.print()"> Print  </button>
                                                <button onclick="exportToExcel();" class="btn btn-success inputFieldHeight" style="padding:3px 8px !important; width: 120px;"> Excel Export </button>
                                            </div>
                                        </div><br>

                                        <h5 class="invoice-view-wrapper"> Expense List  </h5>

                                        <table class="table table-bordered table-sm" id="expense">
                                            <thead class="thead">
                                                <tr >
                                                    <th>Project Name</th>
                                                    <th>Date</th>
                                                    <th>Building</th>
                                                    <th>VR</th>
                                                    <th>INV</th>
                                                    <th>Description</th>
                                                    <th>Amount</th>
                                                    <th>AC Head</th>
                                                </tr>
                                            </thead>
                                            <tbody id="purch-body">
                                                @foreach ($expenses as $item)
                                                   <tr>
                                                    @php
                                                        $journal = App\Journal::where('purchase_expense_id', $item->id)->first();
                                                        $journal_record = App\JournalRecord::where('journal_id', $journal->id)->whereNotNull('compnay_id')->first();
                                                        $project_info = App\JobProject::find($journal_record->compnay_id??null);
                                                    @endphp
                                                        <td>{{$project_info->project_name??''}}</td>
                                                        <td>{{date('d/m/Y',strtotime($item->date))}}</td>
                                                        <td>{{$item->narration}}</td>
                                                        <td>{{$item->invoice_no}}</td>
                                                        <td></td>
                                                        <td></td>
                                                        <td>{{$item->total_amount}}</td>
                                                        <td>
                                                            @foreach ($item->items as $p_item)
                                                                {{$p_item->head_sub?$p_item->head_sub->name:$p_item->head->fld_ac_head}}
                                                            @endforeach
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        {!! $expenses->links()!!}
                                    </div>
                                </div>
                            </div>
                        @include('layouts.backend.partial.modal-footer-info')
                    </section>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- modal --}}

    <!-- END: Content-->
    <div class="modal fade bd-example-modal-lg" id="voucherPreviewModal" tabindex="-1" rrole="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div id="voucherPreviewShow">

                </div>
            </div>
        </div>
    </div>
        <div class="modal fade bd-example-modal-lg" id="voucherPreviewModal2" tabindex="-1" rrole="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div id="voucherPreviewShow2">

                </div>
            </div>
        </div>
    </div>
    <div class="modal fade bd-example-modal-lg" id="voucherDetailsPrintModal" tabindex="-1" rrole="dialog"
        aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div id="voucherDetailsPrint">

                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="balanceAdd" tabindex="-1" role="dialog" aria-labelledby="balanceAddLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-body d-flex">
                <h4 class="mr-auto">Add Balance</h4>
                <button type="button" class="btn  btn_create mr-1 float-right formButton"style=" background: #1a233a;color:#fff" title="Yes" id="new_balance_add">
                    Yes
                </button>
                <button type="button" class="btn btn_create mr-1 float-right formButton btn-danger"style=" background: #1a233a;color:#fff" title="No" data-dismiss="modal" aria-label="Close">
                    NO
                </button>
            </div>
          </div>
        </div>
    </div>



    <div class="modal fade bd-example-modal-lg" id="inventory_list_model" tabindex="-1" rrole="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header" style="padding: 5px 18px;background:#364a60;">
                    <h5 class="modal-title" id="exampleModalLabel" style="font-family:Cambria;font-size: 2rem;color:white;">Project Inventory Allocation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <section id="widgets-Statistics" class="mr-1 ml-1 mb-2 accountHeadStyle HeadStyle">
                    <div class="col-12 mt-2 m-0 p-0" id="inventory_list_model_content">

                    </div>
                </section>
            </div>
        </div>
    </div>

    <div class="modal fade bd-example-modal-lg" id="expense_create_model" tabindex="-1" role="dialog"  aria-labelledby="expense_create_modelLabel" aria-hidden="true"  data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content" id="expense_create_model_content">
                {{-- @include('backend.purchase-expense.create') --}}
            </div>
        </div>
    </div>

    <div class="modal fade bd-example-modal-lg" id="stock_inventory_model" tabindex="-1" role="dialog"  aria-labelledby="stock_inventory_modelLabel" aria-hidden="true"  data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div id="stock_inventory_model_content">

                </div>
                {{-- @include('backend.inventory.stock-report') --}}
            </div>
        </div>
    </div>
    <div class="modal fade bd-example-modal-lg" id="project_expense_model" tabindex="-1" rrole="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header" style="padding: 5px 18px;background:#364a60;">
                    <h5 class="modal-title" id="exampleModalLabel" style="font-family:Cambria;font-size: 2rem;color:white;">Project Inventory Allocation</h5>
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

    <div class="modal fade bd-example-modal-lg" id="subsidiary_model" tabindex="-1" rrole="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header" style="padding: 5px 18px;background:#364a60;">
                    <h5 class="modal-title" id="exampleModalLabel" style="font-family:Cambria;font-size: 2rem;color:white;">Subsidiary Allocation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <section id="widgets-Statistics" class="mr-1 ml-1 mb-2 accountHeadStyle HeadStyle">
                    <div class="col-12 mt-2 m-0 p-0" id="subsidiary_model_content">

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
    <div class="modal fade bd-example-modal-lg" id="check_project_expense_model" tabindex="-1" role="dialog"  aria-labelledby="check_project_expense_modelLabel" aria-hidden="true"  data-backdrop="static" data-keyboard="false"> <!-- Prevents closing on click outside or ESC -->
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header" style="padding: 5px 18px;background:#364a60;">
                    <h5 class="modal-title" id="check_project_expense_modelLabel" style="color: #fff">Project Expense base on Account Head</h5>
                    <a href="#" class="close btn-icon btn btn-danger" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"> <i class='bx bx-x' style="padding-top: 8px; "></i> </span>
                    </a>
                </div>
                <div class="modal-body" id="check_project_expense_content">
                    <!-- Content loaded via AJAX -->
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="excel_import" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">Import MS Excel</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{route('expense-excel-import')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        {{-- <div class="mb-1">
                            <select name="expense_project_name" id="" required class="form-control common-select2 w-100">
                                <option value="">Select....</option>
                                @foreach ($project_lists as $item)
                                    <option value="{{$item->id}}">{{ $item->project_id?$item->new_project->name:$item->project_name }}-{{$item->project_code}}</option>
                                @endforeach
                            </select>
                        </div> --}}
                        <input type="file" required class="form-controll" name="excel_file" accept=".xlsx, .xls, .csv">
                        @php
                        $token = time()+rand(10000,99999);
                        @endphp
                        <input type="hidden" name="token" value="{{$token}}">
                        <button type="submit" class="btn btn-primary text-right">Upload</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
        <div class="modal fade bd-example-modal-lg" id="add-new-expense" tabindex="-1" rrole="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <section class="print-hideen border-bottom">
                    <div class="d-flex" style="background:#364a60;">
                        <h4 class="mt-1 ml-1 mr-auto" style="color: #fff !important;">Create Expense Head</h4>

                        <div class="mIconStyleChange">
                            <a href="#" class="btn btn-sm btn-info d-none hide-unhide" id="add-product">Back<span
                                    class="text-center" style="font-size: 18px;color:#fff;"></span></a>
                            <!--<a href="#" class="btn btn-sm btn-info hide-unhide" style="margin-right: 3px" id="add-other-product">Add Other</a>-->

                            <a href="#" class="close btn-icon btn btn-danger" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">
                                    <i class='bx bx-x'></i>
                                </span>
                            </a>
                        </div>
                    </div>
                </section>

                <section id="widgets-Statistics" class="mr-1 ml-1 mb-2 accountHeadStyle HeadStyle">
                    <div class="row">
                        <div class="col-12">
                            <div class="row">
                                <div class="col-md-12 col-12 mt-1 ">
                                    <div class="form-group">
                                        <label>Master Account</label>
                                        <select name="findMasterAcc" class="form-control inputFieldHeight findMasterAcc common-select2" style="width: 100% !important;">
                                            <option value="">Select-------</option>
                                            @foreach ($special_master_details as $masterAcc)
                                            <option value="{{ route('findMasterAcc', $masterAcc) }}" >{{ $masterAcc->mst_ac_code }} - {{ $masterAcc->mst_ac_head }}</option>
                                            @endforeach
                                            @foreach ($master_details as $masterAcc)
                                            <option value="{{ route('findMasterAcc', $masterAcc) }}" >{{ $masterAcc->mst_ac_code }} - {{ $masterAcc->mst_ac_head }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            </div>
                        </div>
                        <div class="col-12 mt-2 user-table-body">

                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
@endsection
@push('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.0/js/toastr.js"></script>
<script src="{{ asset('assets/backend')}}/app-assets/vendors/js/forms/select/select2.full.min.js"></script>
<script src="{{ asset('assets/backend')}}/app-assets/js/scripts/forms/select/form-select2.js"></script>
<script src="{{ asset('assets/backend')}}/app-assets/vendors/js/forms/repeater/jquery.repeater.min.js"></script>
<script src="{{ asset('assets/backend')}}/app-assets/js/scripts/forms/form-repeater.js"></script>
{{-- js work by mominul start --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>
    <script>
        var index = 1;

        document.addEventListener('DOMContentLoaded', function() {
            // Use delegation
            document.body.addEventListener('click', function(e) {
                // Add new A/C Head
                if (e.target.classList.contains('add-head')) {
                    index++;
                    const group = e.target.closest('.ac-head-group');
                    const newGroup = group.cloneNode(true);

                    var newId = group.querySelector

                    newGroup.querySelector('.form-check-label').setAttribute('for', `add-unit_${index}`);
                    const checkbox = newGroup.querySelector('.form-check-input');
                    checkbox.value = null   ;
                    checkbox.setAttribute('id', `add-unit_${index}`);

                    newGroup.querySelector('input').value = '';
                    const button = newGroup.querySelector('button');
                    button.classList.remove('btn-success', 'add-head');
                    button.classList.add('btn-danger', 'remove-head');
                    button.textContent = '-';

                    group.parentNode.appendChild(newGroup);
                }

                // Remove A/C Head
                if (e.target.classList.contains('remove-head')) {
                    const group = e.target.closest('.ac-head-group');
                    group.remove();
                }
            });
        });
        $(document).ready(function(){
            const lpo = @json($lpo);
            const type = @json($type);

            if(lpo){
                $('.expense_create_model').click();

                setTimeout(function () {
                    $('#party_info').append(`
                        <option value="${lpo.party_id}" selected>${lpo.party.pi_name}</option>
                    `).trigger('change');

                    if (lpo && lpo.items.length > 0) {
                        lpo.items.forEach((item, index) => {
                            if(index != 0){
                                BtnAdd('#TRow', '#TBody','group-a');
                            }

                            let lastRow = $('#TBody tr').last();
                            lastRow.find('.description').val(item.item_description);
                            lastRow.find('.qty').val(item.qty);
                            lastRow.find('.unit').val(item.unit_id).trigger('change');
                            lastRow.find('.amount').val(item.amount.toFixed(2));
                            lastRow.find('.vat_amount').val(item.vat.toFixed(2));
                            lastRow.find('.sub_gross_amount').val(item.total_amount.toFixed(2));
                        });

                        $('#taxable_amount').val(lpo.amount.toFixed(2));
                        $('#total_vat').val(lpo.vat.toFixed(2));
                        $('#total_amount').val(lpo.total_amount.toFixed(2));
                    }
                }, 3000);
            }

            if(type && type == 'new_expense'){
                $('.expense_create_model').click();
            }else if(type && type == 'inventory'){
                $('.stock_inventory_model').click();
            }
        });

        function refreshPage() {
            window.location.reload();
        }

        function exportToExcel() {
            var table = document.getElementById("expense");
            var wb = XLSX.utils.table_to_book(table, { sheet: "Expense" });
            XLSX.writeFile(wb, "expense-list.xlsx");
        }

        $(window).on('load', function() {
            $.ajax({
                url: "{{ route('temp-cogs-clear') }}",
                type: "post",
                cache: false,
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                },
                error: function(xhr) {
                    console.log('Error:', xhr);
                }
            });
        });

        $(document).on('click', '.expense_create_model', function(e){
            e.preventDefault();
            var id = $(this).attr('id');
            $.ajax({
                url: "{{ route('expense-create-model-content') }}",
                type: "post",
                cache: false,
                data: {
                    _token: '{{ csrf_token() }}',
                    id: id,
                },
                success: function(response) {
                    document.getElementById("expense_create_model_content").innerHTML = response;
                    $('.party-info').select2();
                    $('#expense_create_model').modal('show');
                    BtnAdd('#TRow', '#TBody','group-a');
                }
            });
        });
        $(document).on('click', '.stock_inventory_model', function(e){
            e.preventDefault();
            $.ajax({
                url: "{{ route('inventory-create-model-content') }}",
                type: "post",
                cache: false,
                data: {
                    _token: '{{ csrf_token() }}',
                },
                success: function(response) {
                    document.getElementById("stock_inventory_model_content").innerHTML = response;
                    $('#stock_inventory_model').modal('show');
                }
            });
        });
        $(document).on("click", ".approve_view", function(e) {
            e.preventDefault();
            var id = $(this).attr('id');
            $.ajax({
                url: "{{ route('approve_purch-exp-modal') }}",
                type: "post",
                cache: false,
                data: {
                    _token: '{{ csrf_token() }}',
                    id: id,
                },
                success: function(response) {
                    document.getElementById("voucherPreviewShow").innerHTML = response;
                    $('#voucherPreviewModal').modal('show')
                }
            });
        });
        $(document).on("click", ".expense_view", function(e) {
            e.preventDefault();
            var id = $(this).attr('id');
            $.ajax({
                url: "{{ route('purch-exp-modal') }}",
                type: "post",
                cache: false,
                data: {
                    _token: '{{ csrf_token() }}',
                    id: id,
                },
                success: function(response) {
                    document.getElementById("voucherPreviewShow").innerHTML = response;
                    $('#voucherPreviewModal').modal('show')
                }
            });
        });
        $(document).on("click", ".expense-edit", function(e) {
            e.preventDefault();
            var id = $(this).attr('id');
            $.ajax({
                url: "{{ route('purchase-expense-edit') }}",
                type: "post",
                cache: false,
                data: {
                    _token: '{{ csrf_token() }}',
                    id: id,
                },
                success: function(response) {
                    document.getElementById("voucherPreviewShow").innerHTML = '';
                    $('#voucherPreviewModal').modal('hide')

                    document.getElementById("expense_create_model_content").innerHTML = response;
                    $('.party-info').select2();
                    $('#expense_create_model').modal('show');
                }
            });
        });
        $('#search').keyup(function() {
            if ($(this).val() != '') {
                var value = $(this).val();
                var party = $('#party_search').val();
                var date = $('#date_search').val();
                var _token = $('input[name="_token"]').val();
                $.ajax({
                    url: "{{ route('search-purchase-expense') }}",
                    method: "POST",
                    data: {
                        value: value,
                        party: party,
                        date:date,
                        _token: _token,
                    },
                    success: function(response) {
                        $("#purch-body").empty().append(response);
                    }
                })
            }
        });

        $('#party_search').change(function() {
            if ($(this).val() != '') {
                var party = $(this).val();
                var value = $('#search').val();
                var date = $('#date_search').val();
                var _token = $('input[name="_token"]').val();
                $.ajax({
                    url: "{{ route('search-purchase-expense') }}",
                    method: "POST",
                    data: {
                        value: value,
                        party: party,
                        date:date,
                        _token: _token,
                    },
                    success: function(response) {

                        $("#purch-body").empty().append(response);
                    }
                })
            }
        });

        $('#date_search').change(function() {
            if ($(this).val() != '') {
                var date = $(this).val();
                var value = $('#search').val();
                var party = $('#party_search').val();
                var _token = $('input[name="_token"]').val();
                $.ajax({
                    url: "{{ route('search-purchase-expense') }}",
                    method: "POST",
                    data: {
                        value: value,
                        party: party,
                        date:date,
                        _token: _token,
                    },
                    success: function(response) {

                        $("#purch-body").empty().append(response);
                    }
                })
            }
        });
    </script>
    {{-- js work by mominul end --}}

    <script>
        $(document).on('click', '.stock_inventory_model', function(){
            $('#inventory_list_model').modal('hide');
        })
        $(document).on('click', '#new_balance_add', function(e){
            $("#balanceAdd").modal('hide');
            $("#newbalanceAdd").modal('show');
        })
        $(document).on("click", ".btn_create", function(e) {
            e.preventDefault();
            // alert('Alhamdulillah');
            setTimeout(function() {
                $('.multi-acc-head').select2();
                $('.multi-tax-rate').select2();
            }, 1000);
        });
        $(document).on('submit', '#cogs_project_expense_update', function(e) {
            e.preventDefault();
            var form = $(this);
            var url = form.attr('action');
            var data = new FormData(this);
            var task_total_amount = $('#cogs_total_amount').val();
            var max_amount = $('#cogs_max_amount').val();
            var max_qty = $('#cogs_max_qty').val();
            var task_total_qty = $('#cogs_total_qty').val();
            if(Number(task_total_qty) != Number(max_qty)){
                toastr.warning('Please check your QTY');
            }else if(Number(task_total_amount) != Number(max_amount)){
                toastr.warning('Please check your amount');
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
                        toastr.success('Added Successfully!');
                    }
                });
            }
        });
        $(document).on('submit', '#editFormSubmit', function(e){
            e.preventDefault(); // avoid executing the actual submit of the form.
            var form = $(this);
            var formData = new FormData(this);
            $.ajax({
                url: "{{ route('check-project-expense-edit') }}",
                method: "POST",
                data: formData,
                processData: false,  // Important for FormData
                contentType: false,   // Important for FormData
                success: function(response) {
                    document.getElementById("check_project_expense_content").innerHTML = response;
                    $('#check_project_expense_model').modal('show');
                },
                error: function(xhr, status, error) {
                    console.error(error); // Helpful for debugging
                }
            });
        })

        $(document).on('submit', '#formSubmit', function(e){
            e.preventDefault(); // avoid executing the actual submit of the form.
            var form = $(this);
            var formData = new FormData(this);

            $.ajax({
                url: "{{ route('check-project-expense') }}",
                method: "POST",
                data: formData,
                processData: false,  // Important for FormData
                contentType: false,   // Important for FormData
                success: function(response) {
                    document.getElementById("check_project_expense_content").innerHTML = response;
                    $('#check_project_expense_model').modal('show');
                },
                error: function(xhr, status, error) {
                    console.error(error); // Helpful for debugging
                }
            });
        });

        function purchase_expense(formElement) {
            var form = $(formElement); // Convert to jQuery object if needed
            console.log(formElement);
            var url = form.attr('action');
            var data = new FormData(formElement);
            $.ajax({
                url: url,
                method: 'POST',
                data: data,
                contentType: false,
                cache: false,
                processData: false,
                success: function(response) {
                    if (response.warning) {
                        toastr.warning("{{ Session::get('message') }}", response.warning);
                    } else if (response.status) {
                        // Handle validation errors
                        for (var i = 0; i < Object.keys(response.status).length; i++) {
                            var key = i + ".invoice";
                            if (response.status.hasOwnProperty(key)) {
                                var errorMessages = response.status[key];
                                for (var j = 0; j < errorMessages.length; j++) {
                                    toastr.warning(errorMessages[j]);
                                }
                            }
                        }
                    } else {
                        $('#check_project_expense_model').modal('hide');
                        $('#expense_create_model').modal('hide');
                        $("#submitButton").prop("disabled", true);
                        $(".deleteBtn").prop("disabled", true);
                        $(".addBtn").prop("disabled", true);
                        document.getElementById("voucherPreviewShow").innerHTML = '';
                        document.getElementById("voucherPreviewShow").innerHTML = response.preview;
                        $('#purch-body').html(response.expense_list);
                        $('#voucherPreviewModal').modal('show');
                        $("#newButton").removeClass("d-none");
                        $("#submitButton").addClass("d-none");
                    }
                },
                error: function(err) {
                    let error = err.responseJSON;
                    if (error && error.errors) {
                        $.each(error.errors, function(index, value) {
                            toastr.error(value, "Error");
                        });
                    } else {
                        toastr.error("An unknown error occurred.");
                    }
                }
            });
        }
        function purchase_expense_edit(formElement) {
            var form = $(formElement); // Convert to jQuery object if needed
            console.log(formElement);
            var url = form.attr('action');
            var data = new FormData(formElement);
            $.ajax({
                url: url,
                method: 'POST',
                data: data,
                contentType: false,
                cache: false,
                processData: false,
                success: function(response) {
                    if (response.warning) {
                        toastr.warning("{{ Session::get('message') }}", response.warning);
                    } else if (response.status) {
                        // Handle validation errors
                        for (var i = 0; i < Object.keys(response.status).length; i++) {
                            var key = i + ".invoice";
                            if (response.status.hasOwnProperty(key)) {
                                var errorMessages = response.status[key];
                                for (var j = 0; j < errorMessages.length; j++) {
                                    toastr.warning(errorMessages[j]);
                                }
                            }
                        }
                    } else {
                        $('#check_project_expense_model').modal('hide');
                        $('#expense_create_model').modal('hide');
                        $("#submitButton").prop("disabled", true);
                        $(".deleteBtn").prop("disabled", true);
                        $(".addBtn").prop("disabled", true);
                        document.getElementById("voucherPreviewShow2").innerHTML = response.preview;
                        $('#purch-body').html(response.expense_list);
                        $('#voucherPreviewModal2').modal('show');
                        $("#newButton").removeClass("d-none");
                        $("#submitButton").addClass("d-none");
                    }
                },
                error: function(err) {
                    let error = err.responseJSON;
                    if (error && error.errors) {
                        $.each(error.errors, function(index, value) {
                            toastr.error(value, "Error");
                        });
                    } else {
                        toastr.error("An unknown error occurred.");
                    }
                }
            });
        }
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
                        toastr.success('Added Successfully!');
                    }
                });
            }
        });
        $("#date").focus();
        $('#project').change(function() {
            console.log($(this).val());
            if ($(this).val() != '') {
                var value = $(this).val();
                var _token = $('input[name="_token"]').val();
                $.ajax({
                    url: "{{ route('findProject') }}",
                    method: "POST",
                    data: {
                        value: value,
                        _token: _token,
                    },
                    success: function(response) {
                        console.log(response);
                        $("#owner").val(response.owner_name);
                        $("#location").val(response.address);
                        $("#address").val(response.address);
                        $("#mobile").val(response.cont_no);
                    }
                })
            }
        });

        $(document).on('change', '#pay_mode', function(e){
            if ($(this).val() == 'Cheque') {
                $(".deposit_date").attr('required',true);
                $("#bank_branch").attr('required',true);
                $("#issuing_bank").attr('required',true);
                $("#cheque_no").attr('required',true);
                $('.cheque-content').show();
            } else {
                $(".deposit_date").removeAttr('required');
                $("#bank_branch").removeAttr('required');;
                $("#issuing_bank").removeAttr('required');
                $("#cheque_no").removeAttr('required');
                $('.cheque-content').hide();
            }

            var pay_mode = $(this).val();
            if(pay_mode == 'Bank'){
                $('#bank_name').show();
                $("#bank_id").attr('required',true);
            }else{
                $('#bank_name').val(null).trigger('change');
                $('#bank_name').hide();
                $("#bank_id").removeAttr('required');
            }
        });
        $('#pay_ment_type').change(function() {
            if ($(this).val() == 'Borrow') {
                $("#paty_id2").attr('required',true);
                $('.b_party').show();

            } else {
                $("#paty_id2").removeAttr('required');
                $('.b_party').hide();
            }
        });
        $(document).on("keyup", "#pi_code", function(e) {
            // alert(1);
            var value = $(this).val();
            var _token = $('input[name="_token"]').val();
            if ($(this).val() != '') {
                $.ajax({
                    url: "{{ route('partyInfoInvoice3') }}",
                    method: "POST",
                    data: {
                        value: value,
                        _token: _token,
                    },
                    success: function(response) {
                        console.log(response);
                        var qty = 1;
                        if (response != '') {
                            $("div.search-item-pi select").val(response.id);
                            $('.common-select2').select2();
                            $("#trn_no").val(response.trn_no);
                            $("#party_contact").val(response.con_no);
                            $("#party_address").val(response.address);

                            $("#invoice_no").focus();
                        }
                    }
                })
            }
        });

        $(document).on("change", "#party", function(e) {
            e.preventDefault();
            $('.date').val('')
            var id = $(this).val();
            var invoice_no = $('#invoice_no').val();
            $.ajax({
                url: "{{ URL('find-invoice') }}",
                type: "post",
                cache: false,
                data: {
                    _token: '{{ csrf_token() }}',
                    id: id,
                    invoice_no: invoice_no,
                },
                success: function(response) {
                    $('#table-body').empty().append(response);
                }
            });
        });

        $(document).on("change", "#invoice_no", function(e) {
            var inv = $(this).val();
            var party = $('#party_info').val();
            var _token = $('input[name="_token"]').val();
            $.ajax({
                url: "{{ route('invoice_no_validation') }}",
                method: "POST",
                data: {
                    inv: inv,
                    party: party,
                    _token: _token,
                },
                success: function(response) {
                    if (response.warning) {
                        toastr.warning(response.warning);
                        $('#invoice_no').val('')

                    }

                    if (response.error) {
                        toastr.error(response.error);
                        $('#invoice_no').val('')
                    }
                }
            })
        });

        $(document).on("change", "#invoice_type", function(e) {
            if ($(this).val() == 'Tax Invoice') {
                $('.vat-exist').show();
                $('.vat_rate').val('');
                $(".vat_rate").attr('required',true);
                $('.colspan-update').each(function() {
                    this.colSpan = 5;
                });
            } else {
                $('.vat-exist').hide();
                $('.vat_amount').val(0);
                $(".vat_rate").removeAttr('required');
                $('.colspan-update').each(function() {
                    this.colSpan = 3;
                });

            }
            total()
        });

        function total() {
            var sum=0;
            var total_vat = 0;
            $('.amount').each(function() {
                var this_amount = $(this).val();
                this_amount = (this_amount === '') ? 0 : this_amount;
                var this_amount = parseFloat(this_amount);
                sum = sum + this_amount;
            });
            $('.vat_amount').each(function() {
                var this_amount = $(this).val();
                this_amount = (this_amount === '') ? 0 : this_amount;
                var this_amount = parseFloat(this_amount);
                //
                total_vat = total_vat + this_amount;
            });
            var taxable = sum.toFixed(2)
            var vat = total_vat.toFixed(2)
            var total =(vat*1)+(taxable*1)
            $(".taxable_amount").val(taxable);
            $(".total_vat").val(vat);
            $(".total_amount").val((total.toFixed(2)));
        };
        $(document).on("keyup", ".amount", function(e) {
            var amount = $(this).val();
            var invoice_type = $('#invoice_type').val();
            var vat_amount = 0;
            if (invoice_type == 'Tax Invoice') {
                var vat_rate = $(this).closest("tr").find(".vat_rate").val();
                vat_amount = (vat_rate / 100) * amount;

                amount = (amount*1) + vat_amount;
            }
            amount=amount*1;
            $(this).closest("tr").find(".vat_amount").val(vat_amount.toFixed(2));
            $(this).closest("tr").find(".sub_gross_amount").val(amount.toFixed(2));
            total();

        });

        $(document).on("change", ".vat_rate", function(e) {
            var amount = $(this).closest("tr").find(".amount").val();
            var invoice_type = $('#invoice_type').val();
            var vat_amount = 0;
            if (invoice_type == 'Tax Invoice') {
                var vat_rate = $(this).val();
                vat_amount = (vat_rate / 100) * amount;

                amount = (amount*1) + vat_amount;
            }
            $(this).closest("tr").find(".vat_amount").val(vat_amount.toFixed(2));

            $(this).closest("tr").find(".sub_gross_amount").val(amount.toFixed(2));

            total();
        });

        function BtnDelItem(v) {
            $(v).parent().parent().remove();
            $("#TBody").find("tr").each(function(index) {
                $(this).find("th").first().html(index);
            });
            // total amount
            // total amount
            var sum=0;
            var total_vat = 0;
            $('.amount').each(function() {
                var this_amount = $(this).val();
                this_amount = (this_amount === '') ? 0 : this_amount;
                var this_amount = parseFloat(this_amount);
                sum = sum + this_amount;
            });
            $('.vat_amount').each(function() {
                var this_amount = $(this).val();
                this_amount = (this_amount === '') ? 0 : this_amount;
                var this_amount = parseFloat(this_amount);
                //
                total_vat = total_vat + this_amount;
            });
            var taxable = sum.toFixed(2)
            var vat = total_vat.toFixed(2)
            var total =(vat*1)+(taxable*1)
            $(".taxable_amount").val(taxable);
            $(".total_vat").val(vat);
            $(".total_amount").val((total.toFixed(2)));
        }
        $(document).on('keyup', '.task_amount', function(e){
            project_total_amount();
        });
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
            project_total_qty();
        });
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
        BtnAdd('#TRow', '#TBody','group-a');
        function BtnAdd(trow, tbody, inputs) {
            var $trow = $(trow);
            var $tbody = $(tbody);
            var newRow = $trow.clone().removeClass("d-none").removeAttr('id');
            newRow.find("input, select, textarea").prop('disabled', false);
            newRow.find("select").addClass('commom-select3');
            newRow.find(".date-").addClass('datepicker');

            var $rows = $tbody.children('tr').not($trow);
            var lastIndex = 0;
            var lastRow = $rows.last();

            // Dynamic input handling
            var lastInputName = lastRow.find("input[name^='" + inputs + "']").attr('name');
            var lastSelectName = lastRow.find("select[name^='" + inputs + "']").attr('name');
            var lastTextName = lastRow.find("textarea[name^='" + inputs + "']").attr('name');
            var lastName = lastInputName || lastSelectName || lastTextName;

            var match = lastName && lastName.match(/\[(\d+)\]/);
            if (match) {
                lastIndex = parseInt(match[1], 10);
            }

            var newIndex = lastIndex + 1;

            newRow.find("input, select, textarea").attr('name', function(index, name) {
                return name.replace(/\[\d+\]/, '[' + newIndex + ']');
            });

            newRow.appendTo(tbody);

            // Initialize select2 on the newly added row
            newRow.find(".commom-select3").each(function() {
                if ($(this).data('select2')) {
                    $(this).select2('destroy');
                }
                $(this).select2();
            });
        }
        task_BtnAdd('#task_TRow', '#task_TBody','group-a');
        function task_BtnAdd(trow, tbody, inputs) {
            var $trow = $(trow);
            var $tbody = $(tbody);
            var newRow = $trow.clone().removeClass("d-none").removeAttr('id');
            newRow.find("input, select, textarea").prop('disabled', false);
            newRow.find("select").addClass('commom-select4');
            newRow.find(".date-").addClass('datepicker');

            var $rows = $tbody.children('tr').not($trow);
            var lastIndex = 0;
            var lastRow = $rows.last();

            // Dynamic input handling
            var lastInputName = lastRow.find("input[name^='" + inputs + "']").attr('name');
            var lastSelectName = lastRow.find("select[name^='" + inputs + "']").attr('name');
            var lastTextName = lastRow.find("textarea[name^='" + inputs + "']").attr('name');
            var lastName = lastInputName || lastSelectName || lastTextName;

            var match = lastName && lastName.match(/\[(\d+)\]/);
            if (match) {
                lastIndex = parseInt(match[1], 10);
            }

            var newIndex = lastIndex + 1;

            newRow.find("input, select, textarea").attr('name', function(index, name) {
                return name.replace(/\[\d+\]/, '[' + newIndex + ']');
            });

            newRow.appendTo(tbody);

            // Initialize select2 on the newly added row
            newRow.find(".commom-select4").each(function() {
                if ($(this).data('select2')) {
                    $(this).select2('destroy');
                }
                $(this).select2();
            });
        }
        function project_total_amount(){
            total = 0;
            $('.task_amount').each(function() {
                var this_amount = $(this).val();
                this_amount = (this_amount === '') ? 0 : this_amount;
                var this_amount = parseFloat(this_amount);
                total = total + this_amount;
            });
            $(".task_total_amount").val((total.toFixed(2)));
        }
        function BtnDel(v) {
            /* Delete Button */
            $(v).parent().parent().remove();
            $("#TBody").find("tr").each(function(index) {
                $(this).find("th").first().html(index);
            });
            project_total_amount();
            project_total_qty();
        }

        function inventory_assign_btn(r){
            var current_tr = $(r).closest('tr');
            var head_id = current_tr.find('.inventory_expense_head').val();
            var qty = current_tr.find('.imventory_qty').val();
            if(!head_id){
                toastr.warning('Please select account head');
            }else if(!qty){
                toastr.warning('Please give QTY');
            }else{
                $.ajax({
                    url: "{{ route('inventory-project-expense') }}",
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
                        $('#stock_inventory_model').modal('hide');
                        task_BtnAdd('#task_TRow', '#task_TBody','group-a');
                    }
                });

            }
        }
        $(document).on('click', '.expanse_add', function(e){
            e.preventDefault();
            $("#add-new-expense").modal('show');
        });
        function delay(callback, ms) {
            var timer = 0;
            clearTimeout(timer);
            timer = setTimeout(callback, ms);
        }

        $(document).on("change", ".findMasterAcc", function(e) {
            e.preventDefault();
            var urls = $(this).val();

            if (!urls) {
                console.error("URL is empty or invalid.");
                return;
            }
            console.log("URL:", urls); // Check the URL being passed

            delay(function() {
                $.ajax({
                    url: urls,
                    type: 'GET',
                    cache: false,
                    dataType: 'json',
                    success: function(response) {
                        if (response.page) {
                            $(".user-table-body").empty().append(response.page);
                        } else {
                            console.warn("Response does not contain 'page' key.");
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.error("AJAX error:", textStatus, errorThrown);
                    }
                });
            }, 999);
        });
        $(document).on('submit', '#formSubmit_new_head', function(e) {
            e.preventDefault();
            var form = $(this);
            var url = form.attr('action');
            var type = 'ajax';
            var data = new FormData(this);
            data.append('type', type);

            $.ajax({
                url: url,
                method: 'POST',
                data: data,
                contentType: false,
                cache: false,
                processData: false,
                success: function(response) {
                    if (response.warning) {
                        toastr.warning(response.warning);
                    } else if (response.errors) {
                        $.each(response.errors, function(key, value) {
                            toastr.error(value[0]);
                        });
                    } else {
                        var expenseSelect = $('.expense_head');
                        var newOption = new Option(response.fld_ac_head, response.id);
                        $(newOption)
                        .addClass('head')
                        .attr('data-unit', response.is_unit);
                        if(response.fld_definition=='Cost of Sales / Goods Sold'){
                            $(newOption).attr('data-value', 'Yes');
                        }else{
                            $(newOption).attr('data-value', 'No');
                        }
                        expenseSelect.append(newOption);
                        $('#add-new-expense').modal('hide');
                        $('.common-select2').select2();
                        toastr.success('Added Successfully!');
                    }
                },
                error: function(jqXHR) {
                    if (jqXHR.status === 422) {
                        var errors = jqXHR.responseJSON.errors;
                        $.each(errors, function(key, value) {
                            toastr.error(value[0]);
                        });
                    } else {
                        toastr.warning('Something went wrong!');
                    }
                }
            });
        });
        let selectedFiles = [];
        $(document).on('change', '#fileInput', function(e){
            // Add new files to our array
            selectedFiles = selectedFiles.concat(Array.from(e.target.files));
            updateFileListDisplay();
            updateFileInput();
        });

        function updateFileListDisplay() {
            const fileList = document.getElementById('fileList');
            fileList.innerHTML = '';

            selectedFiles.forEach((file, index) => {
                const fileItem = document.createElement('div');
                fileItem.className = 'file-item';
                fileItem.innerHTML = `
                    <span>${file.name}</span>
                    <button class="delete-btn" data-index="${index}">×</button>
                `;
                fileList.appendChild(fileItem);
            });

            // Add delete functionality
            document.querySelectorAll('.delete-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const index = parseInt(this.getAttribute('data-index'));
                    selectedFiles.splice(index, 1);
                    updateFileListDisplay();
                    updateFileInput(); // Update the actual file input
                });
            });
        }

        function updateFileInput() {
            const fileInput = document.getElementById('fileInput');
            const dataTransfer = new DataTransfer();

            selectedFiles.forEach(file => {
                dataTransfer.items.add(file);
            });

            fileInput.files = dataTransfer.files;
        }
        $(document).on('mouseenter', '.datepicker', function(){
            $(this).datepicker({
                changeMonth: true,
                changeYear: true,
                yearRange: "-1000:+1000",
                dateFormat: "dd/mm/yy",
            });
        });
        $(document).on("click", ".inventory-list", function(e) {
            var type = $(this).attr('id');
            var _token = $('input[name="_token"]').val();
            if(type=='pending-inventory'){
                url = "{{ route('pendding-inventory') }}"
            }else{
                url = "{{ route('approval-inventory') }}"
            }
            $.ajax({
                url: url,
                method: "POST",
                data: {
                    type: type,
                    _token: _token,
                },
                success: function(response) {
                    $('#inventory_list_model_content').empty().append(response);
                    $('#inventory_list_model').modal('show');
                    $('#stock_inventory_model').modal('hide');
                }
            })
        });
        $(document).on('click','.temp-inventory-view',function(e) {
            var id = $(this).attr('id');
            $.ajax({
                url: "{{ route('temp-inventory-show') }}",
                type: "post",
                cache: false,
                data: {
                    _token: '{{ csrf_token() }}',
                    id: id,
                },
                success: function(response) {
                    $('#project_expense_model_content1').empty().append(response);
                    $('#project_expense_model1').modal('show');
                }
            });
        })
        $(document).on('click','.inventory-view',function(e) {
            var id = $(this).attr('id');
            $.ajax({
                url: "{{ route('inventory-show')}}",
                type: "post",
                cache: false,
                data: {
                    _token: '{{ csrf_token() }}',
                    id: id,
                },
                success: function(response) {
                    $('#project_expense_model_content1').empty().append(response);
                    $('#project_expense_model1').modal('show');
                }
            });
        })
        $(document).on('click','.inventory-edit',function(e) {
            var id = $(this).attr('id');
            $.ajax({
                url: "{{ route('inventory-edit') }}",
                type: "post",
                cache: false,
                data: {
                    _token: '{{ csrf_token() }}',
                    id: id,
                },
                success: function(response) {
                    $('#project_expense_model_content1').empty().append();
                    $('#project_expense_model1').modal('hide');

                    $('#stock_inventory_model').modal('hide');
                    $('#project_expense_model_content').empty().append(response);
                    $('#project_expense_model').modal('show');
                    task_BtnAdd('#task_TRow', '#task_TBody','group-a');
                    $('.common-select2').select2();
                }
            });
        })
        $(document).on('change', '.expense_head', function(e){
            var current_tr = $(this).closest("tr");
            var dataValue = current_tr.find('.expense_head option:selected').data('value');
            var datasubsidiary = current_tr.find('.expense_head option:selected').data('subsidiary');
            if (dataValue == 'Yes') {
                current_tr.find('.add_button .project_add').removeClass('d-none');
            } else {
                current_tr.find('.add_button .project_add').addClass('d-none');
            }
            if (datasubsidiary == 'Yes') {
                current_tr.find('.add_button .subsidiary_add').removeClass('d-none');
            } else {
                current_tr.find('.add_button .subsidiary_add').addClass('d-none');
            }
        })
        function CogsBtnProjectItem(r){
            var current_tr = $(r).closest('tr');
            var head_id = current_tr.find('.expense_head').val();
            var amount = current_tr.find('.amount').val();
            var qty = current_tr.find('.qty').val();
            if(!head_id){
                toastr.warning('Please select account head');
            }else if(amount == 0){
                toastr.warning('Please give expense amount');
            }else if(!qty){
                toastr.warning('Please give QTY');
            }else{
                $.ajax({
                    url: "{{ route('cogs-project-expense') }}",
                    type: "post",
                    cache: false,
                    data: {
                        _token: '{{ csrf_token() }}',
                        head_id: head_id,
                        amount: amount,
                        qty: qty,
                    },
                    success: function(response) {
                        $('#subsidiary_model_content').empty().append('');
                        $('#project_expense_model_content').empty().append(response);
                        $('#project_expense_model').modal('show');
                        task_BtnAdd('#task_TRow', '#task_TBody','group-a');
                        $('.common-select2').select2();
                    }
                });

            }
        }
        function subsidiaryAdd(r){
            var current_tr = $(r).closest('tr');
            var head_id = current_tr.find('.expense_head').val();
            var amount = current_tr.find('.amount').val();
            var qty = current_tr.find('.qty').val();
            if(!head_id){
                toastr.warning('Please select account head');
            }else if(amount == 0){
                toastr.warning('Please give expense amount');
            }else if(!qty){
                toastr.warning('Please give QTY');
            }else{
                $.ajax({
                    url: "{{ route('subsidiary-create') }}",
                    type: "post",
                    cache: false,
                    data: {
                        _token: '{{ csrf_token() }}',
                        head_id: head_id,
                        amount: amount,
                        qty: qty,
                    },
                    success: function(response) {
                        $('#project_expense_model_content').empty().append('');
                        $('#subsidiary_model_content').empty().append(response);
                        $('#subsidiary_model').modal('show');
                        task_BtnAdd('#task_TRow', '#task_TBody','group-a');
                        $('.common-select2').select2();
                    }
                });

            }
        }
        function subsidiaryEdit(r){
            var purchase_id = $('#purchase_id').val();
            var current_tr = $(r).closest('tr');
            var head_id = current_tr.find('.expense_head').val();
            var amount = current_tr.find('.amount').val();
            var qty = current_tr.find('.qty').val();
            if(!head_id){
                toastr.warning('Please select account head');
            }else if(amount == 0){
                toastr.warning('Please give expense amount');
            }else if(!qty){
                toastr.warning('Please give QTY');
            }else{
                $.ajax({
                    url: "{{ route('subsidiary-edit') }}",
                    type: "post",
                    cache: false,
                    data: {
                        _token: '{{ csrf_token() }}',
                        head_id: head_id,
                        amount: amount,
                        qty: qty,
                        purchase_id:purchase_id
                    },
                    success: function(response) {
                        $('#subsidiary_model_content').empty().append(response);
                        $('#subsidiary_model').modal('show');
                        task_BtnAdd('#task_TRow', '#task_TBody','group-a');
                        $('.common-select2').select2();
                    }
                });

            }
        }

        function BtnProjectItem(r){
            var purchase_id = $('#purchase_id').val();
            var current_tr = $(r).closest('tr');
            var head_id = current_tr.find('.expense_head').val();
            var amount = current_tr.find('.amount').val();
            var qty = current_tr.find('.qty').val();
            if(!head_id){
                toastr.warning('Please select account head');
            }else if(amount == 0){
                toastr.warning('Please give expense amount');
            }else if(!qty){
                toastr.warning('Please give QTY');
            }else{
                $.ajax({
                    url: "{{ route('cogs-project-expense-edit') }}",
                    type: "post",
                    cache: false,
                    data: {
                        _token: '{{ csrf_token() }}',
                        head_id: head_id,
                        amount: amount,
                        qty: qty,
                        purchase_id:purchase_id
                    },
                    success: function(response) {                        
                        $('#subsidiary_model_content').empty().append('');
                        $('#project_expense_model_content').empty().append(response);
                        $('#project_expense_model').modal('show');
                        task_BtnAdd('#task_TRow', '#task_TBody','group-a');
                        $('.common-select2').select2();
                    }
                });

            }
        }
        function subsidiary_total(){
            total_qty = 0;
            $('.subsidiary_qty').each(function() {
                var this_qty = $(this).val();
                this_qty = (this_qty === '') ? 0 : this_qty;
                var this_qty = parseFloat(this_qty);
                total_qty = total_qty + this_qty;
            });
            $(".subsidiary_total_qty").val((total_qty.toFixed(2)));

            total_amount = 0;
            $('.subsidiary_amount').each(function() {
                var this_amount = $(this).val();
                this_amount = (this_amount === '') ? 0 : this_amount;
                var this_amount = parseFloat(this_amount);
                total_amount = total_amount + this_amount;
            });
            $(".subsidiary_total_amount").val((total_amount.toFixed(2)));
        }

        function cogs_total(){
            total_qty = 0;
            $('.cogs_task_qty').each(function() {
                var this_qty = $(this).val();
                this_qty = (this_qty === '') ? 0 : this_qty;
                var this_qty = parseFloat(this_qty);
                total_qty = total_qty + this_qty;
            });
            $(".cogs_total_qty").val((total_qty.toFixed(2)));

            total_amount = 0;
            $('.cogs_task_amount').each(function() {
                var this_amount = $(this).val();
                this_amount = (this_amount === '') ? 0 : this_amount;
                var this_amount = parseFloat(this_amount);
                total_amount = total_amount + this_amount;
            });
            $(".cogs_total_amount").val((total_amount.toFixed(2)));
        }
        $(document).on('keyup', '.cogs_task_qty', function(e){
            var qty = $(this).val();
            var current_tr = $(this).closest('tr');
            var max_amount = $('#cogs_max_amount').val();
            var max_qty = $('#cogs_max_qty').val();
            var rate = Number(max_amount)/Number(max_qty);
            var amount = rate*qty;
            current_tr.find('.cogs_task_amount').val(amount.toFixed(2));
            cogs_total();
        });
        function COGS_Btn_Del(v){
            $(v).parent().parent().remove();
            $("#TBody").find("tr").each(function(index) {
                $(this).find("th").first().html(index);
            });
            cogs_total();
        }
        $(document).on('keyup', '.subsidiary_qty', function(e){
            var qty = $(this).val();
            var current_tr = $(this).closest('tr');
            var max_amount = $('#subsidiary_max_amount').val();
            var max_qty = $('#subsidiary_max_qty').val();
            var rate = Number(max_amount)/Number(max_qty);
            var amount = rate*qty;
            current_tr.find('.subsidiary_amount').val(amount.toFixed(2));
            subsidiary_total();
        });
        function subsidiary_Btn_Del(v){
            $(v).parent().parent().remove();
            $("#TBody").find("tr").each(function(index) {
                $(this).find("th").first().html(index);
            });
            subsidiary_total();
        }
        $(document).on('submit', '#subsidiary_store', function(e) {
            e.preventDefault();
            var form = $(this);
            var url = form.attr('action');
            var data = new FormData(this);
            var task_total_amount = $('#subsidiary_total_amount').val();
            var max_amount = $('#subsidiary_max_amount').val();
            var max_qty = $('#subsidiary_max_qty').val();
            var task_total_qty = $('#subsidiary_total_qty').val();
            if(Number(task_total_qty) > Number(max_qty)){
                toastr.warning('The quantity must be equal.');
            }else if(Number(task_total_amount) > Number(max_amount)){
                toastr.warning('Please check your amount');
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
                        $("#subsidiary_model").modal('hide');
                        toastr.success('Added Successfully!');
                    }
                });
            }
        });
        $(document).on('submit', '#subsidiary_update', function(e) {
            e.preventDefault();
            var form = $(this);
            var url = form.attr('action');
            var data = new FormData(this);
            var task_total_amount = $('#subsidiary_total_amount').val();
            var max_amount = $('#subsidiary_max_amount').val();
            var max_qty = $('#subsidiary_max_qty').val();
            var task_total_qty = $('#subsidiary_total_qty').val();
            if(Number(task_total_qty) > Number(max_qty)){
                toastr.warning('The quantity must be equal.');
            }else if(Number(task_total_amount) > Number(max_amount)){
                toastr.warning('Please check your amount');
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
                        $("#subsidiary_model").modal('hide');
                        toastr.success('Added Successfully!');
                    }
                });
            }
        });
        $(document).on('submit', '#cogs_project_expense_store', function(e) {
            e.preventDefault();
            var form = $(this);
            var url = form.attr('action');
            var data = new FormData(this);
            var task_total_amount = $('#cogs_total_amount').val();
            var max_amount = $('#cogs_max_amount').val();
            var max_qty = $('#cogs_max_qty').val();
            var task_total_qty = $('#cogs_total_qty').val();
            if(Number(task_total_qty) != Number(max_qty)){
                toastr.warning('The quantity must be equal.');
            }else if(Number(task_total_amount) != Number(max_amount)){
                toastr.warning('Please check your amount');
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
                        toastr.success('Added Successfully!');
                    }
                });
            }
        });
        $(document).on('submit', '#PaymentformSubmit', function(e){
            e.preventDefault(); // avoid executing the actual submit of the form.
            var form = $(this);
            var url = form.attr('action');
            var data = new FormData(this);
            $.ajax({
                url: url,
                method: 'POST',
                data: data,
                contentType: false,
                cache: false,
                processData: false,
                success: function(response) {
                    if (response.warning) {
                        toastr.warning("{{ Session::get('message') }}", response.warning);
                    } else if (response.status) {
                        // Handle validation errors
                        for (var i = 0; i < Object.keys(response.status).length; i++) {
                            var key = i + ".invoice";
                            if (response.status.hasOwnProperty(key)) {
                                var errorMessages = response.status[key];
                                for (var j = 0; j < errorMessages.length; j++) {
                                    toastr.warning(errorMessages[j]);
                                }
                            }
                        }
                    } else {
                        $("#submitButton").prop("disabled", true)
                        $(".deleteBtn").prop("disabled", true)
                        $(".addBtn").prop("disabled", true)
                        document.getElementById("voucherPreviewShow").innerHTML = response;
                        $('#voucherPreviewModal').modal('show');
                    }
                },
                error: function(err) {
                    let error = err.responseJSON;
                    $.each(error.errors, function(index, value) {
                        toastr.error(value, "Error");
                    });
                }
            });
        });
        $(document).on('change', '#pay_mode', function(e){
            var to_account = $(this).val();
            if (to_account == 'Petty Cash') {
                $('#paid_by').attr('required', true);
                $('#paid_by').attr('disabled', false);
            } else {
                $('#paid_by').val(null).trigger('change');
                $('#paid_by').attr('required', false);
                $('#paid_by').attr('disabled', true);
            }
        })

        $(document).on('click', '.approve-btn' ,function(e){
            e.preventDefault();
            var url = $(this).attr('href');
            var  message = 'Are you sure';
            Swal.fire(alertDesign(message, 'approve'))
            .then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url:url,
                        'type':'get',
                        success:function(res){
                            document.getElementById("voucherPreviewShow").innerHTML = res.preview;
                            $('#purch-body').html(res.expense_list);
                            Swal.fire(alertDesign('Expense approved successfully', 'Create payment voucher!'))
                            .then((result) => {
                                if (result.isConfirmed) {
                                    $('#paymentModal').modal('show');
                                }
                            });
                        }
                    })
                }
            });
        })


        $(document).on('change', '.account-head', function(e){
            var tr = $(this).closest('tr');
            var selectedOption = $(this).find('option:selected');
            var unitId = selectedOption.data('unit');

            var unitSelect = tr.find('.unit');
            var qtyInput = tr.find('.qty');


            if (unitId) {
                unitSelect.val(unitId).trigger('change');
                unitSelect.prop('disabled', false);
                qtyInput.prop('disabled', false).val(1);
            }else{
                unitSelect.prop('disabled', true).val('');
                qtyInput.prop('disabled', true).val(1);
            }
        });

    </script>
@endpush
