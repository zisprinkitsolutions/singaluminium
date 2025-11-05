<style>
    .row {
        display: flex;
    }

    .col-md-1 {
        max-width: 8.33% !important;
    }

    .col-md-2 {
        max-width: 16.66% !important;
    }

    .col-md-8 {
        max-width: 66.66% !important;
    }

    .col-md-10 {
        max-width: 83.33% !important;
    }

    .col-md-11 {
        max-width: 91.66% !important;
    }

    .customer-static-content {
        background: #ada8a81c;
    }

    .customer-dynamic-content {
        background: #706f6f33;
    }

    .proview-table tr td,
    .proview-table tr th {
        border: 1px solid black !important;
        padding: 3px 6px;
    }

    .customer-dynamic-content2 {
        background: #fff !important;
    }

    .customer-content {
        border: 1px solid black !important;
    }

    @media print and (color) {
        .proview-table {
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
    }

    @media print {
        .row {
            display: flex;
        }

        .col-md-1 {
            max-width: 8.33% !important;
        }

        .col-md-2 {
            max-width: 16.66% !important;
        }

        .col-md-8 {
            max-width: 66.66% !important;
        }

        .col-md-10 {
            max-width: 83.33% !important;
        }

        .col-md-11 {
            max-width: 91.66% !important;
        }

        .customer-static-content {
            background: #ada8a81c;
        }

        .customer-dynamic-content {
            background: #706f6f33;
        }

        .proview-table tr td,
        table tr th {
            border: 1px solid black !important;
        }

        #widgets-Statistics {
            padding: 2px !important;
        }

        .customer-dynamic-content2 {
            background: #fff !important;
        }

        .customer-content {
            border: 1px solid black !important;
        }
    }
    td,th{
        font-size:14px;
    }
</style>

<section class=" border-bottom" style="padding: 5px 30px;background:#364a60;">
    <div class="d-flex flex-row-reverse">
        <div class="" style="margin-top: 6px;"><a href="#" class="close btn btn-icon btn-danger"
                data-dismiss="modal" aria-label="Close" style="padding-bottom: 8px;" title="Close"><span
                    aria-hidden="true"><i class='bx bx-x'></i></span></a></div>

        <div class="" style="padding-right: 3px;margin-top: 6px;"><a onclick="window.print()"
                target="_blank" class="btn btn-icon btn-success" title="Print"><i class="bx bx-printer"></i></a></div>


        <div class="" style="padding-right: 3px;margin-top: 6px;"><a  data-url="{{route('engineer.reports.edit', $report->id)}}"
                 class="btn btn-icon btn-info detail-button"
                title="Edit"><i class='bx bx-edit'></i></a></div>

        <div class="" style="padding-right: 3px;margin-top: 6px;"><a  href="{{route('engineer.reports.approve', $report->id)}}"
                 class="btn btn-icon btn-info"
                title="Edit"><i class='bx bx-check'></i></a></div>

        @if($report->status == 'disapprove')
        <div class="" style="padding-right: 3px;margin-top: 6px;">
            <a href="{{route('engineer.reports.destroy',$report->id)}}" class="btn btn-icon btn-danger" title="Delete"
                onclick="event.preventDefault(); deleteAlert(this, 'About to delete invoice. Please, confirm?');">
                <i class="bx bx-trash"></i>
            </a>
        </div>
        @endif

        <div class="w-100">
            <h4 style="font-family:Cambria;font-size: 1.4rem;color:white;">
                Work Report
            </h4>
        </div>
    </div>
</section>
@php
    $trn_no = \App\Setting::where('config_name', 'trn_no')->first();
    $company_name = \App\Setting::where('config_name', 'company_name')->first();
@endphp
@include('layouts.backend.partial.modal-header-info')

<section id="widgets-Statistics" class="pt-2 px-1">
    <div class="row">
        <div class="col-sm-12">
            <div class="customer-info m-1">
                <table class="table table-sm table-bordered" style="color: #1d1d1d !important;">
                    <tr>
                        <td class="text-left">
                            <strong style="">Project</strong> <strong>: {{optional($report->new_project)->name}}</strong>
                        </td>
                        <td class="text-right">
                            <strong>Project NO <span>: {{optional($report->new_project)->project_no}}</span></strong>
                        </td>
                    </tr>

                    <tr>
                        <td class="text-left">
                            <strong> Owner Name </strong> <span><b>:</b></span> {{optional($report->new_project)->party ? $report->new_project->party->pi_name :''}}
                        </td>
                        <td class="text-right">
                            <strong> Plot No <span style="padding-left: 12px">: {{optional($report->new_project)->plot}}</span></strong>
                        </td>
                    </tr>

                    <tr>
                        <td class="text-left">
                            <strong> Project Location </strong> <span><b>:</b></span> {{optional($report->new_project)->location}}
                        </td>
                        <td class="text-right">
                            <strong> Average  Progress % <span style="padding-left: 12px">: {{optional($report->job_project)->avarage_complete}} </span></strong>
                        </td>
                    </tr>

                    <tr>
                        <td class="text-left">
                            <strong> Engineer Name </strong> <span><b>:</b></span> {{optional($report->engineer)->full_name}}
                        </td>

                        <td class="text-right">
                            <strong> Employee Code  <span style="padding-left: 12px">: {{optional($report->engineer)->code}}</span></strong>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>


    <div class="row" style="padding: 15px;">
        <div class="col-12 col-lg-6">
            <table class="table table-sm table-bordered border-botton proview-table" style="color: black; ">
                <thead style="background: #706f6f33 !important;color: black;">
                    <tr>
                        <th class="text-left pl-1" style="text-transform: uppercase; color: black !important;"> DESCRIPTION / DETAILS OF Work</th>
                        <th class="text-center" style="text-transform: uppercase; color: black !important;width:100px"> Progress % </th>
                    </tr>
                </thead>

                <tbody class="user-table-body">
                    @foreach ($report->details as $item)
                        <tr class="text-center">
                            <td class="text-left pl-1">
                                {{$item->work_details}}
                            </td>
                            <td class="text-left pl-1">
                                <pre>{{ $item->progress}}</pre>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="col-6">
            <table class="table table-sm table-bordered border-botton proview-table" style="color: black; ">
                <thead style="background: #706f6f33 !important;color: black;">
                    <tr>
                        <th class="text-left pl-1" style="text-transform: uppercase; color: black !important;"> File Name </th>
                        <th class="text-center" style="text-transform: uppercase; color: black !important;width:100px"> Extention  </th>
                    </tr>
                </thead>

                <tbody class="user-table-body">
                    @foreach ($report->documents as $item)
                        <tr class="text-center">
                            <td class="text-left pl-1">
                                {{$item->file_name}}
                            </td>
                            <td class="text-left pl-1">
                                <a href="{{ asset('storage/upload/project-document/' . $item->file_name) }}" target="_blank">
                                    {{$item->ext}}
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>


    <div class="divFooter mb-1 ml-1 invoice-view-wrapper  footer-margin">
        Business Software Solutions by
        <span style="color: #0005" class="spanStyle"><img class="img-fluid"
                src="{{ asset('img/zikash-logo.png')}}" alt="" width="70"></span>
    </div>
</section>



