

<style>
    html, body {
        height:100%;
    }
    thead {
    background: #34465b;
    color: #fff !important;
    height: 30px;
    }
    @media print{
        .table tr th,
        .table tr td{
            color: #000000 !important;
            font-weight:500 !important;
        }
    }
</style>
<section class="print-hideen border-bottom" style="background: #364a60;">
    <div class="d-flex flex-row-reverse">

        <div class="pr-1" style="padding-top: 5px;padding-right: 24px !important;"><a href="#" class="close btn-icon btn btn-danger" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class='bx bx-x'></i></span></a></div>
        {{-- <div class="pr-1" style="padding-top: 5px;padding-right: 6px !important;"><a href="#" onclick="window.print();" class="btn btn-icon btn-success"><i class="bx bx-printer"></i></a></div> --}}

        <div class="pr-1 w-100 pl-2">
            <h4 style="font-family:Cambria;font-size: 2rem;color:white;"> Project Expense Details </h4>
        </div>
    </div>
</section>
<div class="receipt-voucher-hearder invoice-view-wrapper" style="margin: 50px 20px; border-radius: 20px;">
    @include('layouts.backend.partial.modal-header-info')
</div>
<section id="widgets-Statistics">

    <div class="row">
        <div class="col-md-12 text-center invoice-view-wrapper student_profle-print py-2">
            <h2> Project Expense </h2>
        </div>

        <div class="col-md-12">
            <div class="">
                <div class="mx-2 mb-2 pt-2">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-6">
                                    <strong>Project:</strong> {{ optional($project->new_project)->name}}
                                </div>
                                <div class="col-6">
                                    <strong>Owner:</strong> {{  optional($project->new_project)->party ?  optional($project->new_project)->party->pi_name : ''}}
                                </div>

                                <div class="col-6">
                                    <strong>Plot No :</strong> {{  optional($project->new_project)->plot }}
                                </div>
                                <div class="col-6">
                                    <strong>Location:</strong> {{ optional($project->new_project)->location}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="border-botton">
                <div class="mx-2">
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered border-botton">
                            <thead class="thead">
                                <tr>
                                    <th class="text-center"> Date </th>
                                    <th class="text-center"> Purchase No </th>
                                    <th class="text-left">  Supplier </th>
                                    <th class="text-left">  Task </th>
                                    <th class="text-right" style="width: 130px;"> Total Inc (VAT)</th>
                                    <th class="text-right" style="width: 130px;"> Assign To Project  </th>
                                     <th class="text-right" style="width: 100px;"> Inventory  </th>
                                </tr>
                            </thead>

                            <tbody class="user-table-body">
                                @foreach ($project_expenses as $item)
                                <tr class="purch_exp_view" id="{{optional($item->expense)->id}}">
                                    <td class="text-center"> {{optional($item->expense)->date ? date('d/m/Y', strtotime(optional($item->expense)->date)) : ''}} </td>
                                    <td class="text-center"> {{ optional($item->expense)->purchase_no }} </td>
                                    <td> {{optional($item->expense)->party ? optional($item->expense)->party->pi_name : '' }} </td>
                                    <td> {{optional($item->project_task)->task_name ? optional($item->project_task)->task_name : '' }} </td>
                                    <td class="text-right">{{number_format(optional($item->expense)->total_amount,2)}}</td>
                                    <td class="text-right">{{number_format($item->total_amount,2)}}</td>
                                    <td class="text-right">{{number_format(optional($item->expense)->total_amount - $item->total_amount,2)}}</td>
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

<section class="print-hideen mb-2">
    <div class="d-flex flex-row-reverse justify-content-center align-items-center">
        <div class="">
            <a href="#" onclick="window.print();" class="btn btn-icon btn-secondary custom-action-btn" title="Print Now">
                <i class="bx bx-printer"></i> Print
            </a>
        </div>
    </div>
</section>

