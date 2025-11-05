@extends('layouts.backend.app')
@section('content')
<style>
    .table thead th {
    color: #ffffff !important;
    text-align: center;

  }
  .mini-width {
    max-width: 90px !important;
  }
  .mini-width-input {
    max-width: 80px !important;
  }
  .semi-mini-width{
    max-width: 120px !important;
  }
  .semi-mini-width-input{
    max-width: 100px !important;
  }
  .table-sm th, .table-sm td {
    padding: 0.01rem !important;
  }
  input, select {
    height: 25px !important;
  }
  .div_error{
    border: 1px solid red;
  }
  .div_right{
    border: 1px solid black;
  }
</style>
@include('layouts.backend.partial.style')
<div class="app-content content print-hideen">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-body">
            <button onclick="exportToExcel();" class="btn btn-success inputFieldHeight" style="padding:3px 8px !important; width: 120px;"> Excel Export </button>
            <div class="table-responsive">
                <table class="table mb-0 table-sm table-hover table-bordered" id="expense">
                    <thead  class="" style="position:sticky; background: #1a233a;">
                        <tr style="height: 30px;">
                            <th>Project Code</th>
                            <th>Project Name</th>
                            <th>Date</th>
                            <th>Building</th>
                            <th>VR</th>
                            <th>INV</th>
                            <th>Description</th>
                            <th>Amount</th>
                            <th>Account Head</th>
                        </tr>
                    </thead>
                    <tbody class="table-sm tbody_object" id="tbody_object_value">
                        @foreach ($records as $key => $t_record)
                            <td>{{$t_record->project_code}}</td>
                            <td>{{$t_record->project_name}}</td>
                            <td>{{date('d/m/Y', strtotime($t_record->date))}}</td>
                            <td>{{$t_record->party_id}}</td>
                            <td>{{$t_record->vr}}</td>
                            <td>{{$t_record->bill_no}}</td>
                            <td>{{$t_record->description}}</td>
                            <td>{{$t_record->amount}}</td>
                            <td>{{$t_record->account_head}}</td>
                        </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection
@push('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.0/js/toastr.js"></script>
    <script src="{{ asset('assets/backend')}}/app-assets/vendors/js/forms/select/select2.full.min.js"></script>
    <script src="{{ asset('assets/backend')}}/app-assets/js/scripts/forms/select/form-select2.js"></script>
    <script src="{{ asset('assets/backend')}}/app-assets/vendors/js/forms/repeater/jquery.repeater.min.js"></script>
    <script src="{{ asset('assets/backend')}}/app-assets/js/scripts/forms/form-repeater.js"></script>
    {{-- js work by mominul start --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>
    <script>
        function exportToExcel() {
            var table = document.getElementById("expense");
            var wb = XLSX.utils.table_to_book(table, { sheet: "Expense" });
            XLSX.writeFile(wb, "expense-list.xlsx");
        }
        $(document).on('click', '.btn_create', function(e){
            // $(this).attr('disabled', true);
        })
        $(document).on('click','.excel-import-delete', function(e){
            var id = $(this).attr('id');
            var _token = $('input[name="_token"]').val();
            $.ajax({
                method: "post",
                url: "{{route('delete-excel-truck-entry')}}",
                data: {
                    id:id,
                    _token: _token,
                },
                success: function (response) {
                    if(response ==1){
                        $('#tr'+id).remove();
                        toastr.success("Row deleted", "Success",{ timeOut: 500 });
                    }
                }
            });
        })
    </script>
@endpush
