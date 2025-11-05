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

    thead{
        background: #34465b;
        color: #fff !important;
    }

    th{
        color: #fff !important;
        font-size: 13px !important;
        height: 25px !important;
        text-align: center !important;
    }

    td {
        font-size: 15px !important;
        background: #fff;
    }

    .table-sm th,
    .table-sm td {
        padding: 5px;
    }

    .card-body {
        flex: 1 1 auto;
        min-height: 1px;
        padding: 0rem !important;
    }

    .card{
        margin-bottom: 0rem;
        box-shadow: none;
    }

    .select2-results__option{
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

    @media print{
        .nav.nav-tabs ~ .tab-content{
            border: #fff !important;
        }
        .print-hidden{
            display: none !important;
        }
        td,th{
            color:#313131 !important;
        }
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
                                    <div class="card-body bg-white">
                                        <div class="d-flex justify-content-between align-items-center print-hidden">
                                            <form class="d-flex align-items-center" style="width: 70% !important;">
                                                @if(Auth::user()->hasPermission('Expense_Create'))
                                                <a href="{{route("purchase-expense", ['type' => 'new_expense'])}}" class="btn btn-primary inputFieldHeight expense_create_model" style="padding:3px 8px !important;"> New Expense </a>
                                                @endif
                                                <a  href="{{route("purchase-expense", ['type' => 'inventory'])}}" class="btn btn-primary inputFieldHeight stock_inventory_model" style="padding:3px 8px !important; margin-left:10px;">&nbsp;&nbsp;&nbsp;&nbsp; Inventory &nbsp;&nbsp;&nbsp;&nbsp;</a>

                                                <div style="padding-left:10px;width: 25% !important;">
                                                    <select name="project_id" id="party_search" class="common-select2 inputFieldHeight w-100">
                                                        <option value="">Select...</option>
                                                        @foreach ($projects as $project)
                                                            <option value="{{ $project->id }}" {{$project_id == $project->id ? 'selected' : ''}}> {{ $project->project_name }} </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <button class="btn btn-primary inputFieldHeight" style="padding: 2px 5px !important;"> Search </button>
                                            </form>

                                            <div class="d-flex text-right">
                                                {{-- <button class="btn btn-info inputFieldHeight" style="padding:3px 8px !important;  width: 120px; margin-right:5%;" data-toggle="modal" data-target="#excel_import"> Excel Import  </button> --}}
                                                <button class="btn btn-info inputFieldHeight" style="padding:3px 8px !important; width: 120px; margin-right:5%;" onclick="window.print()"> Print  </button>
                                                <button onclick="exportToExcel();" class="btn btn-success inputFieldHeight" style="padding:3px 8px !important; width: 120px;"> Excel Export </button>
                                            </div>
                                        </div><br>

                                        <h5 class="invoice-view-wrapper"> Project Expense Report  </h5>

                                        <table class="table table-bordered table-sm" id="expense">
                                            <thead class="thead">
                                                <tr>
                                                    <th style="text-align: left !important; min-width:fit-content"> Project </th>
                                                    <th style="text-align: left !important; min-width:fit-content"> Location </th>
                                                    <th style="text-align: left !important; min-width: 90px"> Plot No  </th>
                                                    <th style="text-align: center !important; min-width: 90px"> Progress  </th>
                                                    <th style="text-align: right !important; min-width:fit-content">Total Taxable Supplies <small>(@if(!empty($currency->symbole)){{$currency->symbole}}@endif)</small></th>
                                                    <th style="text-align: right !important;min-width: fit-content"> Vat  </th>
                                                    <th style="text-align: right !important;min-width: fit-content"> Total Expense </th>
                                                    <th style="text-align: right !important; min-width: fit-content"> Action </th>
                                                </tr>
                                            </thead>
                                            <tbody id="purch-body">
                                                @foreach ($expenses as $item)
                                                <tr>
                                                    <td class="report-details" data-url="{{route('project.expense.report.details',$item->id)}}" style="text-align: left !important;" title="{{$item->name}}"> {{ \Illuminate\Support\Str::limit($item->name, 25) }} </td>
                                                    <td class="report-details" data-url="{{route('project.expense.report.details',$item->id)}}" style="text-align: lfef !important;"> {{$item->location}} </td>
                                                    <td class="report-details" data-url="{{route('project.expense.report.details',$item->id)}}" style="text-align: left !important;"> {{$item->plot_no}} </td>
                                                    <td class="report-details" data-url="{{route('project.expense.report.details',$item->id)}}" style="text-align:center !important;"> {{$item->progress ?  $item->progress : 0}} % </td>
                                                    <td class="report-details" data-url="{{route('project.expense.report.details',$item->id)}}" style="text-align: right !important;"> {{number_format($item->amount,2)}} </td>
                                                    <td class="report-details" data-url="{{route('project.expense.report.details',$item->id)}}" style="text-align: right !important;"> {{number_format($item->vat,2)}} </td>
                                                    <td class="report-details" data-url="{{route('project.expense.report.details',$item->id)}}" style="text-align: right !important;"> {{number_format($item->total_amount,2)}} </td>
                                                    <td class="text-center">
                                                        <a href="{{route('project.boq.compare',$project->id )}}" title="BOQ Comparison" class="btn-icon btn btn-info"><i class='bx bx-transfer'></i> </a></div>
                                                    </td>
                                                </tr>

                                                @endforeach
                                            </tbody>
                                        </table>

                                        @if($expenses->count())
                                            <!-- your table here -->
                                            {{ $expenses->links() }}
                                        @else
                                            <p>No records found</p>
                                        @endif
                                    </div>
                                </div>

                                @include('layouts.backend.partial.modal-footer-info')
                            </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
</div>
    {{-- modal --}}
    <div class="modal fade bd-example-modal-lg" id="project_expense_model1" tabindex="-1" rrole="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div id="project_expense_model_content1">

                </div>
            </div>
        </div>
    </div>

    {{-- modal --}}

    <div class="modal fade bd-example-modal-lg" id="voucherPreviewModal" tabindex="-1" rrole="dialog"
        aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div id="voucherPreviewShow">

                </div>
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


function exportToExcel() {
    var table = document.getElementById("expense");
    var wb = XLSX.utils.table_to_book(table, { sheet: "Expense" });
    XLSX.writeFile(wb, "project-expense.xlsx");
}

$(document).on('click', '.report-details', function(e){
    e.preventDefault();
    var url = $(this).data('url');
    $.ajax({
        type:'get',
        url:url,
        success:function(res){
            $('#project_expense_model_content1').html(res);
            $('#project_expense_model1').modal('show');
        }
    })
});

$(document).on("click", ".purch_exp_view", function(e) {
    e.preventDefault();
    var id = $(this).attr('id');
    $.ajax({
        url: "{{ URL('purch-exp-modal') }}",
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

</script>
@endpush
