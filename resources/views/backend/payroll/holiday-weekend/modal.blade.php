<style>
    .select2{
        width:100%;
    }
    .select2-container{
        width:100% !important;
    }
</style>
<div>
    <div class="modal fade"  id="employee-modal" tabindex="-1"
        role="dialog" aria-labelledby="employee-modal" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <section class="print-hideen border-bottom" style="padding: 0px 14px; background-color:#475f7b;">
                    <div class="d-flex justify-content-between align-item-center">
                        <h5 style="font-family:Cambria;font-size: 1.6rem; margin-top:8px;margin-left:13px;color:#ececec !important;"> <b>Add Weekend </b></h5>
                        <div class="mIconStyleChange" style="padding:0;"><a href="#"
                                class="close btn-icon btn btn-danger mIconStyleChange212" data-dismiss="modal"
                                aria-label="Close"><span aria-hidden="true"><i class='bx bx-x'></i></span></a>
                        </div>
                    </div>
                </section>

                <div class="modal-body">
                    <div class="">
                        <form action="{{ route('weekend-holiday-policies.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row mb-3">
                                <table class="table  table-sm ">
                                    <thead>
                                        <tr>
                                            <th style="width: 40%">Employee</th>
                                            <th> Weekend </th>

                                            <th style="width: 120px" class="NoPrint text-center">
                                                <button type="button" class="btn btn-sm btn-success addBtn d-inline-flex align-items-center" style="border: 1px solid green;color: #fff; border-radius: 10px;padding: 5px 10px;"
                                                   onclick="BtnAddALL()">
                                                    <i class="bx bx-plus" style="color: white;"></i>
                                                     Add All
                                                </button>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody id="TBody">
                                        <tr id="TRow" class="text-center d-none invoice_row">
                                            <td class="text-left">
                                                <select name="emp_id[]"  class="form-control search-select select-employee " disabled >
                                                <option value="">Select..</option>
                                                @foreach ($employees as $employee)
                                                    <option value="{{ $employee->id }}" @if(isset($policy) && $policy->emp_id == $employee->id) selected @endif>
                                                        {{ $employee->full_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            </td>
                                            <td><input type="text" name="date[]" autocomplete="off" disabled placeholder="DD/MM/YYY"  class="form-control weekend-date"></td>

                                            <td class="text-center">
                                                <button type="button" class="btn btn-sm "
                                                        style="border: 1px solid #fff;
                                                        color: #fff; border-radius: 10px;padding: 8px 12px; margin: 0px; font-size:12px;background: #10853a;" onclick="BtnAdd('#TRow', '#TBody')">ADD</button>

                                                <button style="border-radius: 10px;padding: 8px 12px; margin: 0px; font-size:10px"
                                                    type="button" class="btn btn-sm btn-danger"
                                                    onclick="event.preventDefault(); deleteAlert(this, 'Are you sure you want to delete?');{ BtnDel1(this); }"
                                                    onclick=" { BtnDel1(this); }">DEL</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

                                <button type="submit" class="btn btn-primary" style="margin-top: 10px; padding:8px 12px; border-radius:10px;">Save Weekend</button>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- default weekend select for employee --}}
    <div class="modal fade"  id="employee-default-weekend-modal" tabindex="-1"
    role="dialog" aria-labelledby="employee-modal" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered " role="document">
        <div class="modal-content">
            <section class="print-hideen border-bottom" style="padding: 0px 14px; background-color:#475f7b;">
                <div class="d-flex justify-content-between align-item-center">
                    <h5 style="font-family:Cambria;font-size: 1.6rem; margin-top:8px;margin-left:13px;color:#ececec !important;"><b>Add Weekend</b></h5>
                            <div class="mIconStyleChange" style="padding:0;"><a href="#"
                            class="close btn-icon btn btn-danger mIconStyleChange212" data-dismiss="modal"
                            aria-label="Close"><span aria-hidden="true"><i class='bx bx-x'></i></span></a>
                    </div>
                </div>
            </section>

            <div class="modal-body">
                <div class="">
                    <form action="{{ route('weekend-default.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row mb-3">
                            <table class="table  table-sm ">
                                <thead>
                                    <tr>
                                        <th style="width: 40%">Employee</th>
                                        <th>Weekend</th>


                                    </tr>
                                </thead>
                                <tbody id="">
                                    @foreach ($employees as $employee)
                                    <tr id="" class="text-center">
                                        <td class="text-center">
                                            <select name="emp_id[]" class="form-control search-select" >
                                                <option value="{{ $employee->id }}">
                                                    {{ $employee->full_name }}
                                                </option>
                                            </select>
                                        </td>
                                        <td>
                                            <select name="days[{{ $employee->id }}][]" class="form-control multiople-select" multiple >
                                                @php
                                                    $defaultDays = json_decode($employee->default_weekend, true);
                                                    $defaultDays = is_array($defaultDays) ? $defaultDays : []; // Ensure $defaultDays is always an array
                                                @endphp
                                                <option value="Monday" {{ in_array('Monday', $defaultDays) ? 'selected' : '' }}>Monday</option>
                                                <option value="Tuesday" {{ in_array('Tuesday', $defaultDays) ? 'selected' : '' }}>Tuesday</option>
                                                <option value="Wednesday" {{ in_array('Wednesday', $defaultDays) ? 'selected' : '' }}>Wednesday</option>
                                                <option value="Thursday" {{ in_array('Thursday', $defaultDays) ? 'selected' : '' }}>Thursday</option>
                                                <option value="Friday" {{ in_array('Friday', $defaultDays) ? 'selected' : '' }}>Friday</option>
                                                <option value="Saturday" {{ in_array('Saturday', $defaultDays) ? 'selected' : '' }}>Saturday</option>
                                                <option value="Sunday" {{ in_array('Sunday', $defaultDays) ? 'selected' : '' }}>Sunday</option>
                                            </select>


                                        </td>
                                    </tr>
                                    @endforeach

                                </tbody>
                            </table>
                            <div>
                                <button type="submit" class="btn btn-primary" style="margin-top: 10px;">Save </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

    {{-- **************** Employees edit modal ************************ --}}

    <div class="modal fade" id="employee-modal-edit" tabindex="-1"
        role="dialog" aria-labelledby="employee-modal-edit" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg mt-5" role="document">
            <div class="modal-content" id="edit-modal">


            </div>
        </div>
    </div>
    {{-- **************** Employees  edit  modal end ************************ --}}
</div>
