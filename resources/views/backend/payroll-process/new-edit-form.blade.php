<section class="print-hideen border-bottom">
    <div class="d-flex flex-row-reverse">
        <div class="mIconStyleChange"><a href="#" class="close btn-icon btn btn-danger" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class='bx bx-x'></i></span></a></div>
        {{-- <div class="mIconStyleChange"><a href="#" class="btn btn-icon btn-success"><i class="bx bx-edit"></i></a></div>
        <div class="mIconStyleChange"><a href="#"  onclick="window.print();" class="btn btn-icon btn-secondary"><i class='bx bx-printer'></i></a></div>
        <div class="mIconStyleChange"><a href="#"  onclick="window.print();" class="btn btn-icon btn-primary"><i class='bx bxs-file-pdf'></i></a></div>
        <div class="mIconStyleChange"><a href="#"  onclick="window.print();" class="btn btn-icon btn-light"><i class='bx bxs-virus'></i></a></div> --}}
    </div>
</section>
@include('backend.tab-file.modal-header-info')
<div class="content-body">
    <form class="form form-vertical" action="{{route('payroll-process.update', $payroll_process->id)}}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PATCH')
        <section id="basic-vertical-layouts">
            <div class="row match-height">
                <div class="col-md-12 col-12">
                    <div class="cardStyleChange">
                        <div class="card-body">
                            <div class="form-body">
                                <h4>Payroll Process Edit</h4>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="class-name">Month</label>
                                            <input type="text" class="inputFieldHeight form-control"  value="{{ $payroll_process->month }}" readonly name="month">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="class-name">Year</label>
                                            <input type="text" class="inputFieldHeight form-control"  value="{{ $payroll_process->year }}" readonly name="year">
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-12">
                                        <div class="form-group">
                                            <label for="class-name">Employee Name</label>
                                            <input type="text" class="inputFieldHeight form-control" value="{{$payroll_process->employeeName->fname}} {{$payroll_process->employeeName->mname}}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-12">
                                        <div class="form-group">
                                            <label for="class-name">Employee Salary</label>
                                            <input type="number" name="employee_salary" class="inputFieldHeight form-control" value="{{ $salary_structure->employee_salary }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-12">
                                        <div class="form-group">
                                            <label for="class-name">Salary Payoff</label>
                                            <input type="number" name="employee_salary_payoff" class="inputFieldHeight form-control" value="{{ $salary_structure->employee_salary }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-12">
                                        <div class="form-group">
                                            <label for="class-name">Payoff Method</label>
                                            <select name="payoff_mehtod" id="" class="inputFieldHeight form-control">
                                                <option value="Cash" selected>Cash</option>
                                                <option value="Bank Check">Bank Check</option>
                                                <option value="bKash">bKash</option>
                                                <option value="Nagad">Nagad</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2 d-flex justify-content-end">
                                        <button type="submit" class="btn btn-primary mt-2 mb-2 formButton" title="Search">
                                            <div class="d-flex">
                                                <div class="formSaveIcon">
                                                    <img src="{{asset('assets/backend/app-assets/icon/save-icon.png')}}" width="25">
                                                </div>
                                                <div><span>Save</span></div>
                                            </div>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>                        
            </div>
        </section>
    </form>
</div>
@include('backend.tab-file.modal-footer-info')