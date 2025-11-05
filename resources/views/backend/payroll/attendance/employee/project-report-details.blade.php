<section class="print-hideen border-bottom" style="background: #364a60;">
    <div class="d-flex flex-row-reverse">

        <div class="pr-1" style="padding-top: 8px; !important;">
            <a href="#" class="close btn-icon btn btn-danger" data-dismiss="modal" aria-label="Close"
               style="height:25px; width:25px; display:flex; align-items:center; justify-content:center; padding:0; font-size:18px;">
                <span aria-hidden="true"><i class='bx bx-x'></i></span>
            </a>
        </div>

        <div class="pr-1" style="padding: 8px;padding-right: 0.2rem !important;">
            <a onclick="window.print()" class="btn btn-icon btn-primary"
               style="height:25px; width:25px; display:flex; align-items:center; justify-content:center; padding:0; font-size:18px;">
                <i class="bx bx-printer"></i>
            </a>
        </div>

        <div class="pr-1 w-100 pl-2" style="margin-top: 2px;">
            <h4 style="font-family:Cambria;font-size: 2rem;color:white;"> Project Report Details </h4>
        </div>
    </div>
</section>

<div class="modal-body" style="padding: 5px 5px;">

    @include('layouts.backend.partial.modal-header-info')
    <section id="widgets-Statistics" class="mr-1 ml-1 mb-1">

        <div class="row mt-1">
            <div class="col-12 profit-center-form">
                <div class="row">
                    <div class="col-md-12">
                      <h6><strong style="font-size: 16px;"> Project Name : </strong> {{optional($new_project)->name}}</h6>
                    </div>

                    <div class="col-md-12 search-item-pi">
                        <h6><strong style="font-size: 16px;"> Owner Name: </strong> {{optional($new_project)->party->pi_name??''}}</h6>
                    </div>

                    @if ($new_project)
                    <div class="col-md-3 search-item-pi">
                        <h6><strong style="font-size: 16px;"> Plot No: </strong> {{optional($new_project)->plot}}</h6>
                    </div>

                    <div class="col-md-3 search-item-pi">
                        <h6><strong style="font-size: 16px;"> Location : </strong> {{optional($new_project)->location}}</h6>
                    </div>

                    <div class="col-md-3 search-item-pi">
                        <h6><strong style="font-size: 16px;"> Project No: </strong> {{optional($new_project)->project_no}}</h6>
                    </div>

                    <div class="col-md-3 search-item-pi">
                        <h6><strong style="font-size: 16px;"> Project Type: </strong> {{optional($new_project)->project_type}}</h6>
                    </div>

                    <div class="col-md-3 search-item-pi">
                        <h6><strong style="font-size: 16px;"> Consulting Agent: </strong> {{optional($new_project)->consulting_agent }}</h6>
                    </div>
                    @endif

                </div>
            </div>

            <div class="table-responsive mt-1">
                <table class="table table-bordered table-sm text-center">
                    <thead class="thead-light">
                        <tr>
                            <th style="width:180px; text-align:left;"> Employee Name </th>
                            <th> Working Days </th>
                            <th> Working Hours </th>
                            <th> Overtime </th>
                            <th> Late Time </th>
                            <th> Absent </th>
                            <th> Current Salary </th>
                            <th> Overtime Amount </th>
                            <th> Late Penalty </th>
                            <th> Absent Penalty </th>
                            <th> Total Cost </th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($data as $key => $project)
                            <tr class="details" data-url="{{route('project.working.report.details',$key)}}">
                                <td class="text-left" title="{{$project['employee_name'] ?? 'N/A'}}">
                                    {{ \Illuminate\Support\Str::limit($project['employee_name'] ?? 'N/A', $limit = 30, $end = '...')}}</td>
                                <td>{{ $project['total_days'] ?? 0 }}</td>
                                <td>{{ secondsTotime($project['total_working_hours']) ?? '00:00:00' }}</td>
                                <td>{{ secondsTotime($project['total_overtime']) ?? '00:00:00' }}</td>
                                <td>{{ secondsTotime($project['total_late_time']) ?? '00:00:00' }}</td>
                                <td>{{ $project['total_absen'] ?? 0 }}</td>
                                <td>{{ number_format($project['basic_salary_current_day'] ?? 0, 2) }}</td>
                                <td>{{ number_format($project['overtime_amount'] ?? 0, 2) }}</td>
                                <td>{{ number_format($project['late_amount'] ?? 0, 2) }}</td>
                                <td>{{ number_format($project['total_absen_penalty'] ?? 0, 2) }}</td>
                                <td><strong>{{ number_format($project['total_cost'] ?? 0, 2) }}</strong></td>
                            </tr>
                        @empty

                        <tr>
                            <td colspan="10"> No data found for the selected month. </td>
                        </tr>

                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>
    @include('layouts.backend.partial.modal-footer-info')
</div>
