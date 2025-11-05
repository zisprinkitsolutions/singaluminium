
@extends('layouts.backend.app')
@section('content')
<style>
    .table .thead-light th {
        color:#F2F4F4 ;
        background-color: #34465b;
        border-color: #DFE3E7;
    }
    tr:nth-child(even) {
        background-color: #c8d6e357;
    }
</style>
<div class="app-content content print-hideen">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-body">
            @include('backend.payroll.tab-sub-tab._basic_info_header',['activeMenu' => 'policies'])
            <div class="tab-content bg-white">
                <div id="studentProfileList" class="tab-pane active px-2 pt-1" style="max-width: 100%;">
                    @include('backend.payroll.tab-sub-tab._policies_submenu',['activeMenu' => 'polices'])
                    <div class="row" id="table-bordered">
                        <div class="col-12">
                            <div class="cardStyleChange">
                                <div class="">
                                    <div class="table-responsive">
                                        <button type="button" class="btn btn-primary employee_modal_open btn_create formButton float-right mb-1" style="padding:5px" data-modal="#employee-modal" title="Add" data-toggle="#employee-modal" data-target="#studentProfileAdd">
                                            <div class="d-flex align-items-center">
                                                <div class="formSaveIcon">
                                                    <img src="{{asset('assets/backend/app-assets/icon/add-icon.png')}}" width="24">
                                                </div>
                                                <div class=""><span> Add new </span></div>
                                            </div>
                                        </button>
                                        <form id="myform" method="POST" enctype="multipart/form-data">
                                            @csrf
                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-sm">
                                                        <thead class="thead-light" style="white-space:nowrap">
                                                            <tr class="text-center" style="height: 40px;">
                                                                <th>SI No</th>
                                                                <th>Effect Date</th>
                                                                <th>Air Ticket Eligibility</th>
                                                                <th>Apply Overtime</th>
                                                                <th>Cash Redeem</th>
                                                                <th>Vacation Type</th>
                                                                <th>Vacation Paid Type</th>

                                                                <th>Min Days for Ticket Price</th>
                                                                <th>Ticket Price Percentage</th>
                                                                <th>Late Type</th>
                                                                <th>Min Days for Late</th>
                                                                <th>Min Hours for Late</th>
                                                                <th>Salary Loss Rate</th>
                                                                <th>Overtime Rate</th>
                                                                <th>Min Hours for Overtime</th>
                                                                <th>Late Grace Time</th>
                                                                <th>Max Time for Attendance</th>
                                                                <th>Yearly Vacation</th>
                                                                <th>Morning In Time</th>
                                                                <th>Morning Out Time</th>
                                                                <th>Evening In Time</th>
                                                                <th>Evening Out Time</th>
                                                                <th>Description</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($policies as $key => $policy)
                                                            <tr class="text-center employee-edit" style="cursor:pointer"  data-id="{{ route('policies.edit',$policy->id) }}"id="{{$policy->id}}">
                                                                <td>{{ $key + 1 }}</td>
                                                                <td>{{ date('d/m/Y', strtotime($policy->effect_date))}}</td>
                                                                <td>{{ $policy->air_ticket_eligibility }}</td>
                                                                <td>{{ $policy->apply_over_time }}</td>
                                                                <td>{{ $policy->cash_redeem }}</td>
                                                                <td>{{ $policy->vacation_type }}</td>
                                                                <td>{{ $policy->vacation_paid_or_unpaid }}</td>
                                                                <td>{{ $policy->minimum_day_for_ticket_price }}</td>
                                                                <td>{{ $policy->ticket_price_percentage }}</td>
                                                                <td>{{ $policy->late_type }}</td>
                                                                <td>{{ $policy->minimum_day_for_late }}</td>
                                                                <td>{{ $policy->minimum_hours_for_late }}</td>
                                                                <td>{{ $policy->salary_loss }}</td>
                                                                <td>{{ $policy->overtime_rate }}</td>
                                                                <td>{{ $policy->min_hours_for_overtime }}</td>
                                                                <td>{{ $policy->late_grace_time }}</td>
                                                                <td>{{ $policy->maximum_time_for_attendace }}</td>
                                                                <td>{{ $policy->number_of_yearly_vacation }}</td>
                                                                <td>{{ date('h:i:s A', strtotime($policy->m_ref_in_time)) }}</td>
                                                                <td>{{ date('h:i:s A', strtotime($policy->m_ref_out_time)) }}</td>
                                                                <td>{{ date('h:i:s A', strtotime($policy->e_ref_in_time)) }}</td>
                                                                <td>{{ date('h:i:s A', strtotime($policy->e_ref_out_time)) }}</td>
                                                                <td>{{ $policy->description }}</td>

                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@include('backend.payroll.policy.modal')
@endsection

@push('js')


<script src="{{ asset('assets/backend')}}/app-assets/vendors/js/forms/repeater/jquery.repeater.min.js"></script>
<script src="{{ asset('assets/backend')}}/app-assets/js/scripts/forms/form-repeater.js"></script>

{{-- parent --}}
<script>
    @if (count($errors) > 0)
        $('#parentProfileAdd').modal('show');
    @endif
    function printFunction(){
        window.print();
    }


</script>
    <script>
        $(function() {
            $('#2filter-table').excelTableFilter();

        });
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>

    @include('backend.payroll.policy.ajax')


    @endpush
