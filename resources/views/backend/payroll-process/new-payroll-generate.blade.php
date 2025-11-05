@php
    use Carbon\CarbonPeriod;
@endphp
<div class="content-body">
    <form class="form form-vertical" action="{{route('payroll-process.store')}}" method="POST" enctype="multipart/form-data">
        @csrf
        <section id="basic-vertical-layouts">
            <div class="row match-height">
                <div class="col-md-12 col-12">
                    <div class="cardStyleChange">
                        <div class="card-body">
                            <div class="form-body">
                                <h4>Payroll Gerenate</h4>
                                <div class="row">
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="class-name">Month</label>
                                            <select name="month" id="" class="inputFieldHeight form-control" required>
                                                @foreach(CarbonPeriod::create(now()->startOfMonth(), '1 month', now()->addMonths(11)->startOfMonth()) as $date)
                                                    <option value="{{ $date->format('F') }}">
                                                        {{ $date->format('F') }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('month')
                                                <span class="error">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="class-name">Year</label>
                                            <select name="year" class="inputFieldHeight form-control" id="dropdownYear2" required>
                                            </select>
                                            @error('year')
                                                <span class="error">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>                                        
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="checkall">
                                    <label class="form-check-label" for="checkall">All Employee</label> 
                                </div>
                                <table class="table mb-0 table-sm table-hover">
                                    <thead  class="thead-light">
                                        <tr style="height: 50px;">
                                            <th>Checked</th>
                                            <th>Employee Name</th>
                                            <th>Designation</th>
                                            <th>Salary</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($emp_salary_structure as $employee)                        
                                            <tr class="trFontSize border-bottom">
                                                <td>
                                                    <input class="checkbox"  type="checkbox" value="{{$employee->employee_id}}" name="employee_id[]"> 
                                                </td>
                                                <td>{{$employee->employeeName->fname}} {{$employee->employeeName->mname}} </td>
                                                <td>{{$employee->employeeName->employee_role}} </td>
                                                <td>
                                                    {{$employee->employee_salary}}
                                                </td>
                                            </tr>
                                        @endforeach                                            
                                    </tbody>
                                </table>
                                <div class="row">
                                    <div class="col-md-12 d-flex justify-content-end">
                                        <button type="submit" class="btn btn-primary mt-2 mb-2 formButton" title="Save">
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
<script>
     var i, currentYear, startYear, endYear, newOption, dropdownYear;
        dropdownYear2 = document.getElementById("dropdownYear2");
        currentYear = (new Date()).getFullYear();
        startYear = currentYear - 4;
        endYear = currentYear + 3;
        for (i=startYear;i<=endYear;i++) {
        newOption = document.createElement("option");
        newOption.value = i;
        newOption.label = i;
            if (i == currentYear) {
                newOption.selected = true;
            }
            dropdownYear2.appendChild(newOption);
        }
</script>