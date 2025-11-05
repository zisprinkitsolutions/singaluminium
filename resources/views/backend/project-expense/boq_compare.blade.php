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
                        <section id="widgets-Statistics">

                            <div class="row">
                                <div class="col-md-12 text-center invoice-view-wrapper student_profle-print py-2">
                                    <h2> Project Expense  Vs Project BOQ </h2>
                                </div>

                                <div class="col-md-12">
                                    <div class="">
                                        <div class="mx-2 mb-2 pt-2">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="d-flex align-items-center" style="width: 70% !important;">
                                                        @if(Auth::user()->hasPermission('Expense_Create'))
                                                        <a href="{{route("purchase-expense", ['type' => 'new_expense'])}}" class="btn btn-custom-nis btn-expense inputFieldHeight expense_create_model"> New Expense </a>
                                                        @endif
                                                        <a  href="{{route("purchase-expense", ['type' => 'inventory'])}}" class="btn btn-custom-nis btn-inventory inputFieldHeight stock_inventory_model">Inventory</a>

                                                        <a href="{{route('project.expense.report')}}" class="btn btn-custom-nis btn-summary inputFieldHeight"> Summary </a>
                                                    </div>
                                                </div>

                                                <div class="col-md-12 mt-1">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <strong>Project:</strong> {{ optional($project->new_project)->name}}
                                                        </div>
                                                        <div class="col-6">
                                                            <strong>Owner:</strong> {{  optional($project->new_project)->party ?  optional($project->new_project)->party->pi_name : ''}}
                                                        </div>

                                                        <div class="col-3">
                                                            <strong>Plot No :</strong> {{  optional($project->new_project)->plot }}
                                                        </div>
                                                        <div class="col-3">
                                                            <strong>Location:</strong> {{ optional($project->new_project)->location}}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12 my-1">
                                    <canvas id="compare-chart"></canvas>
                                </div>

                                <div class="col-md-12">
                                    <div class="border-botton">
                                        <div class="mx-2">
                                            <div class="table-responsive">
                                                <table class="table table-sm table-bordered border-botton">
                                                    <thead class="thead">
                                                        <tr>
                                                            <th class="text-left"> Task </th>
                                                            <th class="text-right" style="width: 170px;"> BOQ Price  </th>
                                                            <th class="text-right" style="width: 150px;"> Actual Expense </th>
                                                            <th class="text-right" style="width: 100px;"> Paid </th>
                                                            <th class="text-right" style="width: 130px;"> Due Balance  </th>
                                                        </tr>
                                                    </thead>

                                                    <tbody class="user-table-body">
                                                        @foreach ($project_expenses as $item)
                                                        <tr>
                                                            <td> {{$item['task_name']}} </td>
                                                            <td class="text-right">{{$item['estimated_expense']}}</td>
                                                            <td class="text-right">{{$item['actual_expense']}}</td>
                                                            <td class="text-right">{{$item['paid']}}</td>
                                                            <td class="text-right">{{$item['payable']}}</td>
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
                    </section>
                </div>
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
<script src="{{ asset('js/plugin/chart.js') }}"></script>

<script>
    function exportToExcel() {
        var table = document.getElementById("expense");
        var wb = XLSX.utils.table_to_book(table, { sheet: "Expense" });
        XLSX.writeFile(wb, "project-expense.xlsx");
    }

    $(document).ready(function() {
        const expenses = @json($project_expenses);

        // Get full task names
        const fullTaskNames = expenses.map(e => e.task_name);

        // Truncate for labels (first 10 characters + "...")
        const taskNamesShort = fullTaskNames.map(name =>
            name.length > 10 ? name.substring(0, 10) + '…' : name
        );

        const estimated = expenses.map(e => parseFloat(e.estimated_expense.replace(',', '')));
        const actual = expenses.map(e => parseFloat(e.actual_expense.replace(',', '')));
        const paid = expenses.map(e => parseFloat(e.paid.replace(',', '')));
        const payable = expenses.map(e => parseFloat(e.payable.replace(',', '')));

        const ctx = document.getElementById('compare-chart').getContext('2d');
        const compareChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: taskNamesShort, // Use shortened labels here
                datasets: [
                    {
                        label: 'BOQ Price',
                        data: estimated,
                        borderColor: '#003f5c',
                        backgroundColor: 'rgba(0, 63, 92, 0.2)',
                        fill: true,
                        tension: 0.3
                    },
                    {
                        label: 'Actual',
                        data: actual,
                        borderColor: '#bc5090',
                        backgroundColor: 'rgba(188, 80, 144, 0.2)',
                        fill: true,
                        tension: 0.3
                    },
                    {
                        label: 'Paid',
                        data: paid,
                        borderColor: '#ffa600',
                        backgroundColor: 'rgba(255, 166, 0, 0.2)',
                        fill: true,
                        tension: 0.3
                    },
                    {
                        label: 'Payable',
                        data: payable,
                        borderColor: '#58508d',
                        backgroundColor: 'rgba(88, 80, 141, 0.2)',
                        fill: true,
                        tension: 0.3
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Project Task Expense Comparison'
                    },
                    tooltip: {
                        callbacks: {
                            // Show full task name in tooltip title
                            title: function(tooltipItems) {
                                const index = tooltipItems[0].dataIndex;
                                return fullTaskNames[index];
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        ticks: {
                            callback: function(val, index) {
                                return taskNamesShort[index];
                            }
                        }
                    },
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    });
</script>

</script>
@endpush
