@extends('layouts.backend.app')
@push('css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.0/css/toastr.css" rel="stylesheet" />
@endpush
@section('title', 'salary-structures')
@section('content')
@include('layouts.backend.partial.style')

<div class="app-content content print-hideen">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-body">
            <div class="tab-content bg-white">
                <div id="employeeAttendance" class="tab-pane active">
                    <div class="content-body">
                        <div class="content-body">
                            <!-- Bordered table start -->
                            <div class="row" id="table-bordered">
                                <div class="col-12">
                                    <div class="cardStyleChange">
                                        <div class="d-flex card-header">
                                            <h4 class="flex-grow-1">Salary Process OF "{{ $employee->name }}"</h4>
                                            {{-- <button type="button" class="btn btn-primary btn_create formButton" title="Add" data-toggle="modal" data-target="#createModal">
                                                <div class="d-flex">
                                                    <div class="formSaveIcon">
                                                        <img src="{{asset('assets/backend/app-assets/icon/add-icon.png')}}" width="25">
                                                    </div>
                                                    <div><span>Add New</span></div>
                                                </div>
                                            </button> --}}
                                        </div>
                                        <div class="card-body">
                                            <!-- table bordered -->
                                            <form class="form form-vertical" action="{{ route('salary-process.update',  $employee->id) }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <div class="row d-flex justify-content-end">
                                                    {{-- <div class="col-md-3">
                                                        Year
                                                        <input type="text" class="form-control" min="2020" name="date" placeholder="DD/MM/YY" id="datepicker" required>
                                                    </div> --}}
                                                    {{-- <input type="hidden" class="form-control" min="2020" name="month" placeholder="DD/MM/YY" id="datepicker" required> --}}
                                                    <input type="hidden" class="form-control" name="employee_id" value="{{ $employee->id }}"  required>

                                                </div>
                                                @method('PUT')
                                                <table class="table mb-0 table-sm table-hover" >
                                                    <thead  class="thead-light">
                                                        <tr style="height: 50px;">
                                                            <th> <input type="checkbox" id="vehicle1" class="btn-select-all"  name="vehicle1" value="Bike">
                                                                <label for="vehicle1">Check All</label>
                                                                </th>
                                                            <th>Head</th>
                                                            {{-- <th>Type</th> --}}
                                                            <th class="text-center">Amount</th>
                                                        </tr>
                                                    </thead>
                                                    @php
                                                        $l_count=0;
                                                    @endphp
                                                    <tbody class="table-sm">
                                                        @php
                                                            $i=0;

                                                        @endphp

                                                        {{-- {{dd($employee)}} --}}
                                                        @foreach ($components as $component)
                                                        @php
                                                        // dd($employee->id);
                                                            ++$i;
                                                        @endphp
                                                        <tr class="trFontSize">
                                                            <td><input type="checkbox" id="" class="checkbox-record check" name="records[head][{{ $i }}]" {{ $component->check($employee->id)? 'checked':"" }}  value="{{$component->id}}"></td>

                                                            <td>{{$component->name}}</td>
                                                            <td class="d-none">
                                                                <select name="records[type][{{ $i }}]" id="" class="form-control check-dep " >
                                                                    <option value="">Select...</option>
                                                                    @foreach ($component_types as $component_type)
                                                                        <option value="{{$component_type->id}}" {{$component_type->id == 2?'selected':''}}>{{$component_type->name}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </td>
                                                            {{-- <td><input type="number" name="records[amount][{{ $i }}]" value="{{ $grade->feeAmount($grade->id,$component->id) ? $grade->feeAmount($grade->id,$component->id):($employee->extraCom($component->id)? ($employee->extraCom($component->id)->value):'') }}" {{ $grade->feeCheck($component->id)? 'readonly':"" }} class="form-control check-dep2" id=""></td> --}}
                                                            <td><input type="number" name="records[amount][{{ $i }}]" value="{{ $component->check($employee->id)?$component->check($employee->id):'' }}" class="form-control check-dep2" id=""></td>

                                                        </tr>
                                                        @endforeach


                                                    </tbody>
                                                </table>
                                                <p class="text-right"><button class="btn btn-info mt-1" type="submit">Procced</button></p>

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
</div>

@endsection
@push('js')

<link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
 <script type="text/javascript">
     $(function() {
             $("#datepicker").datepicker({ dateFormat: "dd/mm/yy" }).val()
     });
 </script>
<script>
   

        $(document).on("click", ".check", function(e) {

        $(this).closest(".check-dep").removeAttr('required');
        $(this).closest(".check-dep2").removeAttr('required');

        });
        // Page Script
        // $('#edit_all').click(function (event) {
            $(document).on("click", "#edit_all", function(e) {

            if (this.checked) {
                // Iterate each checkbox
                $(':checkbox').each(function () {
                    this.checked = true;
                });
            } else {
                $(':checkbox').each(function () {
                    this.checked = false;
                });
            }
        });


        $('.btn-select-all').click(function (event) {
        if (this.checked) {
            // Iterate each checkbox
            $(':checkbox').each(function () {
                this.checked = true;
            });
        } else {
            $(':checkbox').each(function () {
                this.checked = false;
            });
        }
    });

    });
</script>
@endpush
