<div>
    <div class="modal fade" id="employee-modal" tabindex="-1"
        role="dialog" aria-labelledby="employee-modal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="p-0" style="font-family:Cambria;font-size: 2rem;"><b>Add Policy Info</b></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="">
                        <form action="{{ route('policies.store') }}" method="POST">
                            @csrf
                            <div class="row mb-3">
                                <!-- Minimum Day for Ticket Price -->
                                <div class="col-md-3">
                                    <label for="effect_date" class="col-form-label">Effective Date</label>
                                    <input required type="text" autocomplete="off" placeholder="DD/MM/YYY" name="effect_date" id="effect_date" class="form-control datepicker">
                                </div>
                                <!-- Air Ticket Eligibility Field -->
                                <div class="col-md-3">
                                    <label for="air_ticket_eligibility" class="col-form-label">Air Ticket (Yes/No)</label>
                                    <select  required name="air_ticket_eligibility" id="air_ticket_eligibility" class="form-control">
                                        <option value="">Select..</option>
                                        <option value="Yes">Yes</option>
                                        <option value="No">No</option>
                                    </select>
                                </div>

                                <!-- Cash Redeem Field -->
                                <div class="col-md-3">
                                    <label for="apply_over_time" class="col-form-label">Cash Redeem</label>
                                    <select  required name="apply_over_time" id="apply_over_time" class="form-control">
                                        <option value="">Select..</option>
                                        <option value="Yes">Yes</option>
                                        <option value="No">No</option>
                                    </select>
                                </div>
                                  <!-- Cash Redeem Field -->


                                <!-- Vacation paid Type Field -->
                                <div class="col-md-3">
                                    <label for="vacation_paid_or_unpaid" class="col-form-label">Vacation Paid Type</label>
                                    <select  required name="vacation_paid_or_unpaid" id="vacation_paid_or_unpaid" class="form-control">
                                        <option value="">Select..</option>
                                        <option value="Paid">Paid</option>
                                        <option value="Unpaid">Unpaid</option>
                                    </select>
                                </div>
                                 <!-- Vacation Type Field -->
                                 <div class="col-md-3">
                                    <label for="vacation_type" class="col-form-label">Leave Type</label>
                                    <select  required name="vacation_type" id="vacation_type" class="form-control">
                                        {{-- <option value="">Select..</option> --}}
                                        <option value="Fixed Period">Fixed Period</option>
                                        {{-- <option value="Flexible Period">Flexible Period</option> --}}
                                    </select>
                                </div>

                                <!-- Minimum Day for Ticket Price -->
                                {{-- <div class="col-md-3">
                                    <label for="minimum_day_for_ticket_price" class="col-form-label">Minimum Days for applicable vacation</label>
                                    <input required type="number" step="0.01" name="minimum_day_for_ticket_price" id="minimum_day_for_ticket_price" class="form-control">
                                </div> --}}
                                   <!-- Minimum Day for Ticket Price -->
                                <div class="col-md-3">
                                    <label for="minimun_vacation_priod" class="col-form-label">Minimum Leave Period (Year)</label>
                                    <select  required name="minimun_vacation_priod" id="minimun_vacation_priod" class="form-control">
                                        <option value="">Select..</option>
                                        <option value="1">One Year</option>
                                        <option value="2">Two Year</option>
                                    </select>
                                </div>


                                <!-- Ticket Price Percentage -->
                                <div class="col-md-3">
                                    <label for="ticket_price_percentage" class="col-form-label">Ticket allowance (Cash)</label>
                                    <input required type="number" step="0.01" name="ticket_price_percentage" id="ticket_price_percentage" class="form-control">
                                </div>
                                <!-- Number of Yearly Vacations -->
                                <div class="col-md-3">
                                    <label for="number_of_yearly_vacation" class="col-form-label">Total Leave (Days) per Year</label>
                                    <input required type="number" name="number_of_yearly_vacation" id="number_of_yearly_vacation" class="form-control">
                                </div>

                                <!-- Late Type -->
                                <div class="col-md-3">
                                    <label for="late_type" class="col-form-label">Late Type</label>
                                    <select  required name="late_type" id="late_type" class="form-control">
                                        <option value="hours">Hours</option>
                                        <option value="day">Day</option>
                                    </select>
                                </div>

                                <!-- Minimum Days for Late -->
                                {{-- <div class="col-md-3">
                                    <label for="minimum_day_for_late" class="col-form-label">Minimum Days for Late</label>
                                    <input required type="number" step="0.01" name="minimum_day_for_late" id="minimum_day_for_late" class="form-control">
                                </div> --}}

                                <!-- Minimum Hours for Late -->
                                <div class="col-md-3">
                                    <label for="minimum_hours_for_late" class="col-form-label">Minimum Hours for Late</label>
                                    <input required type="number" step="0.01" name="minimum_hours_for_late" id="minimum_hours_for_late" class="form-control">
                                </div>
                                <!-- Salary Loss Rate -->
                                <div class="col-md-3">
                                    <label for="salary_loss" class="col-form-label">Salary Loss Rate  ( Basic salary percentange of day)</label>
                                    <input required type="number" step="0.01" name="salary_loss" id="salary_loss" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <label for="cash_redeem" class="col-form-label">Apply Over Time</label>
                                    <select  required name="cash_redeem" id="cash_redeem" class="form-control">
                                        <option value="">Select..</option>
                                        <option value="Yes">Yes</option>
                                        <option value="No">No</option>
                                    </select>
                                </div>

                                <!-- Overtime Rate -->
                                <div class="col-md-3">
                                    <label for="overtime_rate" class="col-form-label">Overtime Rate ( Percentage)</label>
                                    <input required type="number" step="0.01" name="overtime_rate" id="overtime_rate" class="form-control">
                                </div>

                                <!-- Minimum Hours for Overtime -->
                                <div class="col-md-3">
                                    <label for="min_hours_for_overtime" class="col-form-label">Minimum Hours for Overtime</label>
                                    <input required type="number" step="0.01" name="min_hours_for_overtime" id="min_hours_for_overtime" class="form-control">
                                </div>

                                <!-- Late Grace Time -->
                                <div class="col-md-3">
                                    <label for="late_grace_time" class="col-form-label">Late Grace Time (Minutes)</label>
                                    <input required type="number" name="late_grace_time" id="late_grace_time" class="form-control">
                                </div>


                                <!-- Maximum Time for Attendance -->
                                <div class="col-md-3">
                                    <label for="maximum_time_for_attendace" class="col-form-label">Maximum Time for Attendance ( Minutes)</label>
                                    <input required type="number" step="0.01" name="maximum_time_for_attendace" id="maximum_time_for_attendace" class="form-control" >
                                </div>

                                <!-- m_ref_in_time -->
                                <div class="col-md-3">
                                    <label for="m_ref_in_time" class="col-form-label">Morning Reference Office In Time</label>
                                    <input required type="time" name="m_ref_in_time" id="m_ref_in_time" class="form-control">
                                </div>

                                <!-- m_ref_out_time -->
                                <div class="col-md-3">
                                    <label for="m_ref_out_time" class="col-form-label">Morning Reference Office Out Time</label>
                                    <input required type="time" name="m_ref_out_time" id="m_ref_out_time" class="form-control">
                                </div>

                                <!-- e_ref_in_time -->
                                <div class="col-md-3">
                                    <label for="e_ref_in_time" class="col-form-label">Evening Reference Office In Time</label>
                                    <input required type="time" name="e_ref_in_time" id="e_ref_in_time" class="form-control">
                                </div>

                                <!-- e_ref_out_time -->
                                <div class="col-md-3">
                                    <label for="e_ref_out_time" class="col-form-label">Evening Reference Office Out Time</label>
                                    <input required type="time"  name="e_ref_out_time" id="e_ref_out_time" class="form-control">
                                </div>

                                <!-- Description Field -->
                                <div class="col-md-12">
                                    <label for="description" class="col-form-label">Description</label>
                                    <textarea name="description" id="description" class="form-control" rows="3"></textarea>
                                </div>
                                <!-- Submit Button -->
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary" style="margin-top: 20px;">Save Policy</button>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- **************** Employees edit modal ************************ --}}

    <div class="modal fade" style="width: 100%;" id="employee-modal-edit" tabindex="-1"
        role="dialog" aria-labelledby="employee-modal-edit" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg mt-5" role="document" >
            <div class="modal-content" id="edit-modal">


            </div>
        </div>
    </div>
    {{-- **************** Employees  edit  modal end ************************ --}}
</div>
