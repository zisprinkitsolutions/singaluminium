@extends('layouts.backend.app')

@section('content')
@php
    use Carbon\CarbonPeriod;
@endphp
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row">
        </div>
        <div class="content-body">
            <form class="form form-vertical" action="{{route('payroll-process.store')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <section id="basic-vertical-layouts">
                    <div class="row match-height">
                        <div class="col-md-12 col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Payroll Process</h4>
                                </div>
                                <div class="card-body">
                                    <div class="form-body">
                                        <div class="row">
                                            <div class="col-md-4 col-12">
                                                <div class="form-group">
                                                    <label for="class-name">Month</label>
                                                    <select name="month" id="" class="form-control" required>
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
                                            <div class="col-md-4 col-12">
                                                <div class="form-group">
                                                    <label for="class-name">Year</label>
                                                    <select name="year" class="form-control" id="dropdownYear" required>
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
                                        <hr>
                                        <table class="table table-bordered mb-1">
                                            <thead>
                                                <tr>
                                                    <th>Checked</th>
                                                    <th>Employee Name</th>
                                                    <th>Designation</th>
                                                    <th>Salary</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($emp_salary_structure as $employee)                        
                                                    <tr>
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
                                            <div class="col-12 d-flex justify-content-end">
                                                <button type="submit" class="btn btn-primary mr-1">
                                                    Process
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
    </div>
</div>
@endsection
@push('js')
<script type="text/javascript">
    var i, currentYear, startYear, endYear, newOption, dropdownYear;
        dropdownYear = document.getElementById("dropdownYear");
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
        dropdownYear.appendChild(newOption);
        }
</script>
<script type='text/javascript'>
    $(document).ready(function(){
      // Check or Uncheck All checkboxes
      $("#checkall").change(function(){
        var checked = $(this).is(':checked');
        if(checked){
          $(".checkbox").each(function(){
            $(this).prop("checked",true);
          });
        }else{
          $(".checkbox").each(function(){
            $(this).prop("checked",false);
          });
        }
      });    
     // Changing state of CheckAll checkbox 
     $(".checkbox").click(function(){    
       if($(".checkbox").length == $(".checkbox:checked").length) {
         $("#checkall").prop("checked", true);
       } else {
         $("#checkall").prop("checked", false);
       }
   
     });
   });
</script>
@endpush