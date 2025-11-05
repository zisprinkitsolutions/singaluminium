<div class="modal-header">
    <h5 class="p-0" style="font-family:Cambria;font-size: 2rem;"><b>Policy info</b></h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <div class="">
        <form action="{{ route('policies.update', $policy->id) }}" method="POST">
            @csrf
            @method('PUT') <!-- Since it's an update -->
            <div class="row mb-3">
                <!-- Effective Date -->
                <div class="col-md-3">
                    <label for="effect_date" class="col-form-label">Effective Date</label>
                    <input required type="text" autocomplete="off" placeholder="DD/MM/YYY" name="effect_date" id="effect_date" class="form-control datepicker" value="{{ old('effect_date', date('d/m/Y', strtotime($policy->effect_date))) }}">
                </div>

                <!-- Air Ticket Eligibility -->
                <div class="col-md-3">
                    <label for="air_ticket_eligibility" class="col-form-label">Air Ticket (Yes/No)</label>
                    <select readonly required name="air_ticket_eligibility" id="air_ticket_eligibility" class="form-control">
                        <option value="">Select..</option>
                        <option value="Yes" {{ old('air_ticket_eligibility', $policy->air_ticket_eligibility) == 'Yes' ? 'selected' : '' }}>Yes</option>
                        <option value="No" {{ old('air_ticket_eligibility', $policy->air_ticket_eligibility) == 'No' ? 'selected' : '' }}>No</option>
                    </select>
                </div>

                <!-- Cash Redeem -->
                <div class="col-md-3">
                    <label for="apply_over_time" class="col-form-label">Cash Redeem</label>
                    <select  required name="apply_over_time" id="apply_over_time" class="form-control">
                        <option value="">Select..</option>
                        <option value="Yes" {{ old('apply_over_time', $policy->apply_over_time) == 'Yes' ? 'selected' : '' }}>Yes</option>
                        <option value="No" {{ old('apply_over_time', $policy->apply_over_time) == 'No' ? 'selected' : '' }}>No</option>
                    </select>
                </div>

                <!-- Apply Over Time -->


                <!-- Vacation Type -->
                <div class="col-md-3">
                    <label for="vacation_type" class="col-form-label">Leave Type</label>
                    <select  required name="vacation_type" id="vacation_type" class="form-control">
                        {{-- <option value="">Select..</option> --}}
                        <option value="Fixed Period" {{ old('vacation_type', $policy->vacation_type) == 'Fixed Period' ? 'selected' : '' }}>Fixed Period</option>
                        {{-- <option value="Flexible Period" {{ old('vacation_type', $policy->vacation_type) == 'Flexible Period' ? 'selected' : '' }}>Flexible Period</option> --}}
                    </select>
                </div>
                    <!-- Vacation paid Type Field -->
                    <div class="col-md-3">
                        <label for="vacation_paid_or_unpaid" class="col-form-label">Vacation Paid Type</label>
                        <select  required name="vacation_paid_or_unpaid" id="vacation_paid_or_unpaid" class="form-control">
                            <option value="">Select..</option>
                            <option value="Paid" {{ old('vacation_type', $policy->vacation_paid_or_unpaid) == 'Paid' ? 'selected' : '' }}>Paid</option>
                            <option value="Unpaid" {{ old('vacation_type', $policy->vacation_paid_or_unpaid) == 'Unpaid' ? 'selected' : '' }}>Unpaid</option>
                        </select>
                    </div>
                <!-- Minimum Days for Ticket Price -->
                {{-- <div class="col-md-3">
                    <label for="minimum_day_for_ticket_price" class="col-form-label">Minimum Days for applicable vacation</label>
                    <input required type="number" step="0.01" name="minimum_day_for_ticket_price" id="minimum_day_for_ticket_price" class="form-control" value="{{ old('minimum_day_for_ticket_price', $policy->minimum_day_for_ticket_price) }}">
                </div> --}}
                <div class="col-md-3">
                    <label for="minimun_vacation_priod" class="col-form-label">Minimum Leave Period (Year)</label>
                    <select  required name="minimun_vacation_priod" id="minimun_vacation_priod" class="form-control">
                        <option value="">Select..</option>
                        <option value="1" {{ old('late_type', $policy->minimun_vacation_priod) == '1' ? 'selected' : '' }}>One Year</option>
                        <option value="2" {{ old('late_type', $policy->minimun_vacation_priod) == '2' ? 'selected' : '' }}>Two Year</option>
                    </select>
                </div>

                <!-- Ticket allowance (Cash) -->
                <div class="col-md-3">
                    <label for="ticket_price_percentage" class="col-form-label">Ticket allowance (Cash)</label>
                    <input required type="number" step="0.01" name="ticket_price_percentage" id="ticket_price_percentage" class="form-control" value="{{ old('ticket_price_percentage', $policy->ticket_price_percentage) }}">
                </div>

                <!-- Late Type -->
                <div class="col-md-3">
                    <label for="late_type" class="col-form-label">Late Type</label>
                    <select required name="late_type" id="late_type" class="form-control">
                        <option value="day" {{ old('late_type', $policy->late_type) == 'day' ? 'selected' : '' }}>Day</option>
                        <option value="hours" {{ old('late_type', $policy->late_type) == 'hours' ? 'selected' : '' }}>Hours</option>
                    </select>
                </div>

                <!-- Minimum Days for Late -->
                {{-- <div class="col-md-3">
                    <label for="minimum_day_for_late" class="col-form-label">Minimum Days for Late</label>
                    <input required type="number" step="0.01" name="minimum_day_for_late" id="minimum_day_for_late" class="form-control" value="{{ old('minimum_day_for_late', $policy->minimum_day_for_late) }}">
                </div> --}}

                <!-- Minimum Hours for Late -->
                <div class="col-md-3">
                    <label for="minimum_hours_for_late" class="col-form-label">Minimum Hours for Late</label>
                    <input required type="number" step="0.01" name="minimum_hours_for_late" id="minimum_hours_for_late" class="form-control" value="{{ old('minimum_hours_for_late', $policy->minimum_hours_for_late) }}">
                </div>
                <!-- Salary Loss Rate -->
                <div class="col-md-3">
                    <label for="salary_loss" class="col-form-label">Salary Loss Rate  ( Basic salary percentange of day)</label>
                    <input required type="number" step="0.01" name="salary_loss" id="salary_loss" class="form-control" value="{{ old('salary_loss', $policy->salary_loss) }}">
                </div>
                <div class="col-md-3">
                    <label for="cash_redeem" class="col-form-label">Apply Over Time</label>
                    <select  required name="cash_redeem" id="cash_redeem" class="form-control">
                        <option value="">Select..</option>
                        <option value="Yes" {{ old('cash_redeem', $policy->cash_redeem) == 'Yes' ? 'selected' : '' }}>Yes</option>
                        <option value="No" {{ old('cash_redeem', $policy->cash_redeem) == 'No' ? 'selected' : '' }}>No</option>
                    </select>
                </div>
                <!-- Overtime Rate -->
                <div class="col-md-3">
                    <label for="overtime_rate" class="col-form-label">Overtime Rate ( Percentage)</label>
                    <input required type="number" step="0.01" name="overtime_rate" id="overtime_rate" class="form-control" value="{{ old('overtime_rate', $policy->overtime_rate) }}">
                </div>

                <!-- Minimum Hours for Overtime -->
                <div class="col-md-3">
                    <label for="min_hours_for_overtime" class="col-form-label">Minimum Hours for Overtime</label>
                    <input required type="number" step="0.01" name="min_hours_for_overtime" id="min_hours_for_overtime" class="form-control" value="{{ old('min_hours_for_overtime', $policy->min_hours_for_overtime) }}">
                </div>

                <!-- Late Grace Time -->
                <div class="col-md-3">
                    <label for="late_grace_time" class="col-form-label">Late Grace Time (Minutes)</label>
                    <input required type="number" name="late_grace_time" id="late_grace_time" class="form-control" value="{{ old('late_grace_time', $policy->late_grace_time) }}">
                </div>

                <!-- Maximum Time for Attendance -->
                <div class="col-md-3">
                    <label for="maximum_time_for_attendace" class="col-form-label">Maximum Time for Attendance ( Minutes)</label>
                    <input required type="number" step="0.01" name="maximum_time_for_attendace" id="maximum_time_for_attendace" class="form-control" value="{{ old('maximum_time_for_attendace', $policy->maximum_time_for_attendace) }}">
                </div>

                <!-- Number of Yearly Vacations -->
                <div class="col-md-3">
                    <label for="number_of_yearly_vacation" class="col-form-label">Total Leave (Days) per Year</label>
                    <input required type="number" name="number_of_yearly_vacation" id="number_of_yearly_vacation" class="form-control" value="{{ old('number_of_yearly_vacation', $policy->number_of_yearly_vacation) }}">
                </div>

                <!-- Morning Reference Office In Time -->
                <div class="col-md-3">
                    <label for="m_ref_in_time" class="col-form-label">Morning Reference Office In Time</label>
                    <input required type="time" name="m_ref_in_time" id="m_ref_in_time" class="form-control" value="{{ old('m_ref_in_time',date('H:i', strtotime($policy->m_ref_in_time)))  }}">
                </div>

                <!-- Morning Reference Office Out Time -->
                <div class="col-md-3">
                    <label for="m_ref_out_time" class="col-form-label">Morning Reference Office Out Time</label>
                    <input required type="time" name="m_ref_out_time" id="m_ref_out_time" class="form-control" value="{{ old('m_ref_out_time', date('H:i', strtotime($policy->m_ref_out_time))) }}">
                </div>

                <!-- Evening Reference Office In Time -->
                <div class="col-md-3">
                    <label for="e_ref_in_time" class="col-form-label">Evening Reference Office In Time</label>
                    <input required type="time" name="e_ref_in_time" id="e_ref_in_time" class="form-control" value="{{ old('e_ref_in_time',date('H:i', strtotime( $policy->e_ref_in_time))) }}">
                </div>

                <!-- Evening Reference Office Out Time -->
                <div class="col-md-3">
                    <label for="e_ref_out_time" class="col-form-label">Evening Reference Office Out Time</label>
                    <input required type="time" name="e_ref_out_time" id="e_ref_out_time" class="form-control" value="{{ old('e_ref_out_time',date('H:i', strtotime( $policy->e_ref_out_time))) }}">
                </div>
                <!-- Description Field -->
                <div class="col-md-12">
                    <label for="description" class="col-form-label">Description</label>
                        <label for="e_ref_out_time" class="col-form-label">Evening Reference Office Out Time</label>
                    <textarea name="description" id="description" class="form-control" rows="3">{{$policy->description}}</textarea>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Update Policy</button>
        </form>



    </div>
</div>
