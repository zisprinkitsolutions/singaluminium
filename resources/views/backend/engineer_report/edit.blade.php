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
</style>

<section class=" border-bottom" style="padding: 5px 30px;background:#364a60;">
    <div class="d-flex flex-row-reverse align-items-center justify-content-between">
        <div class=""><a href="#" class="close btn-sm btn btn-danger"
                data-dismiss="modal" aria-label="Close" style="padding-bottom: 8px;" title="Close"><span
                    aria-hidden="true"><i class='bx bx-x'></i></span></a>
        </div>

        <div class="w-100">
            <h4 style="font-family:Cambria;font-size: 1.4rem;color:white; margin-bottom: 0px; padding: 0px;">
                Working Report
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
            <form action="{{route('engineer.reports.update', $report->id)}}" class="daily-report-form" method="POST" enctype="multipart/form-data">
                @csrf

                @method('put');

                <div class="row">

                    <div class="col-6 col-md-4 col-lg-2">
                        <div class="form-group">
                            <label for=""> Date  </label>
                            <input type="text" name="date" value="{{date('d/m/Y', strtotime($report->date))}}" class="form-control datepicker date" data-pre=".null" data-next=".project_id">
                        </div>
                    </div>

                    <div class="col-6 col-md-4 col-lg-2">
                        <div class="form-group">
                            <label for=""> Project </label>
                            <select name="project_id" id="project_id" class="project_id form-control common-select2" data-pre=".date" data-next=".task_id" required>
                                <option value=""> Select... </option>
                                @foreach ($projects as $project)
                                    <option value="{{$project->id}}" {{$project->id == $report->job_project_id ? 'selected' : ''}}> {{$project->project_name}} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-6 col-md-4 col-lg-2">
                        <div class="form-group">
                            <label for=""> Task </label>
                            <select name="task_id" class="form-control common-select2 task_id" data-pre=".project_id" data-next=".item_id" required>
                                <option value=""> Select... </option>
                                @foreach ($report->job_project->tasks as $task)
                                    <option value="{{$task->id}}" {{$task->id == $report->task_id ? 'selected' : ''}}> {{$task->task_name}} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-6 col-md-4 col-lg-2">
                        <div class="form-group">
                            <label for=""> Item </label>
                            <select name="item_id" class="form-control common-select2 item_id" data-pre=".task_id" data-next=".work_details">
                                <option value=""> Select... </option>
                                @foreach ($report->task->items as $item)
                                    <option value="{{$item->id}}" {{$item->id == $report->item_id ? 'selected' : ''}}> {{$item->item_description}} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-6 col-md-4 col-lg-2">
                        <div class="form-group">
                            <label for=""> Start Date </label>
                            <input type="text" name="start_date" class="form-control datepicker start_date" data-pre=".item_id" data-next=".end_date" autocomplete="off" required
                                value="{{$report->end_date ? date('d/m/Y', strtotime($report->start_date)) : ''}}">
                        </div>
                    </div>

                    <div class="col-6 col-md-4 col-lg-2">
                        <div class="form-group">
                            <label for=""> End Date  </label>
                            <input type="text" name="end_date" class="form-control datepicker end_date" data-pre=".start_date" data-next=".work_details" autocomplete="off" required
                                value="{{$report->end_date ? date('d/m/Y', strtotime($report->end_date)) : ''}}">
                        </div>
                    </div>
                </div>

                <div class="table-responseive">
                    <table class="table table-bordered table-sm daily-report-input-table">
                        <thead>
                            <th> Work Details  </th>
                            <th> Progress % </th>
                            <th> image </th>
                            <th> Action </th>
                        </thead>
                        <tbody class="daily-report-input-body">
                            @if($report->details->count() > 0)
                            @foreach ($report->details as $key => $details)
                            <tr>
                                <td>
                                    <input type="text" class="form-control inputFieldHeight work_details" placeholder="Work Details" name="work_details[{{$key}}]"
                                        data-pre=".item_id" data-next=".work_progress" required value="{{$details->work_details}}">
                                </td>

                                <td>
                                    <input type="number" step="any" class="form-control work_progress inputFieldHeight" placeholder="Work Progress" name="progress[{{$key}}]" required
                                            data-pre=".work_details" data-next=".image" value="{{$details->progress}}">
                                </td>

                                <td>
                                    <input type="file" class="form-control inputFieldHeight image" placeholder="image" name="image[{{$key}}][]"
                                        data-pre=".work_progress" data-next="save" multiple>
                                </td>

                                <td>
                                    <button type="button" class="start-camera btn btn-primary inputFieldHeight" style="padding:4px 7px !important;" data-index="{{$key}}"> <i class='bx bxs-camera-plus'></i> </button>
                                    <button type="button" class="add-new-row btn btn-primary inputFieldHeight" style="padding:4px 7px !important;" data-index="{{$key}}"> <i class='bx bx-message-alt-add'></i> </button>
                                    <button type="button" class="delete-row btn btn-danger inputFieldHeight" style="padding:4px 7px !important;"> <i class='bx bxs-message-square-x'></i> </button>
                                </td>
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td>
                                    <input type="text" class="form-control inputFieldHeight work_details" placeholder="Work Details" name="work_details[0]"
                                        data-pre=".item_id" data-next=".work_progress" required>
                                </td>

                                <td>
                                    <input type="number" step="any" class="form-control inputFieldHeight" placeholder="Work Progress" name="progress[0]" required
                                            data-pre=".work_details" data-next=".image">
                                </td>

                                <td>
                                    <input type="file" class="form-control inputFieldHeight image" placeholder="image" name="image[0]" required
                                        data-pre=".image" data-next="save" multiple>
                                </td>

                                <td>
                                    <button type="button" class="add-new-row btn btn-primary inputFieldHeight" style="padding:4px 7px !important;" data-index="0"> <i class='bx bx-message-alt-add'></i> </button>
                                    <button type="button" class="start-camera btn btn-primary inputFieldHeight" style="padding:4px 7px !important;" data-index="0"> <i class='bx bxs-camera-plus'></i> </button>
                                    <button type="button" class="delete-row btn btn-danger inputFieldHeight" style="padding:4px 7px !important;"> <i class='bx bxs-message-square-x'></i> </button>
                                </td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                <div class="my-1">
                    <button class="btn btn-primary inputFieldHeight"> Save </button>
                </div>
            </form>
        </div>
    </div>
</section>



