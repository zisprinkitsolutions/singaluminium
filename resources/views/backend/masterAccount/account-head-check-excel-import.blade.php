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
            <form action="{{route('account-head-final-excel-import')}}" method="POST">
                @csrf
                <div class="">
                    <table class="table mb-0 table-sm table-hover table-bordered">
                        <thead  class="" style="position:sticky; background: #1a233a;">
                            <tr style="height: 30px;">
                                <th>Account Head <span class="text-danger">*</span></th>
                                <th>Master Account <span class="text-danger">*</span></th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        @php
                            $exit_head = 0;
                        @endphp
                        <tbody class="table-sm tbody_object" id="tbody_object_value">
                            @foreach ($records as $key => $t_record)
                                <tr class="trFontSize" id="{{'tr'.$t_record->id}}">
                                    <input type="hidden" name="temp_invoice_id[]" value="{{$t_record->id}}" class="temp_invoice_id">
                                    <td class="semi-mini-width">
                                        <input name="account_head[]" style="width: 100% !important;" type="text" value="{{$t_record->account_head}}" required class="tr_value_submit {{$t_record->account_head?'':'error'}}" title="Head name is required">
                                    </td>
                                    <td class="semi-mini-width">
                                        <select name="master_account[]" class="common-select2 inputFieldHeight tr_value_submit" style="width: 100% !important" id="master_account" required>
                                            <option value="">Select...</option>
                                            @foreach ($master_account as $item)
                                            <option value="{{ $item->mst_ac_head }}" {{$t_record->master_account==$item->mst_ac_head?'selected':''}}>{{ $item->mst_ac_head }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="text-center">
                                        <a href="#" class="btn excel-import-delete" title="Delete" onclick="return confirm('Are you sure to delete this?')" style="padding-top: 1px; padding-bottom: 1px; height: 30px; width: 30px;" id="{{$t_record->id}}">
                                            <img src="{{asset('assets/backend/app-assets/icon/delete-icon.png')}}" style=" height: 30px; width: 30px;">
                                        </a>
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
                {{-- onclick="return confirm('Are your sure to submit !')" --}}
                <div class="row">
                    <div class="col-12 text-center">
                        <button type="submit" class="btn btn-primary btn_create formButton mr-1 mt-2" {{$exit_head>0?'disabled':''}} title="Save" id="excel_form_save" onclick="return confirm('Please Confirm ?')">
                            <div class="d-flex">
                                <div class="formSaveIcon">
                                    <img src="{{asset('assets/backend/app-assets/icon/save-icon.png')}}" width="25">
                                </div>
                                <div><span>Save</span></div>
                            </div>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@push('js')
    <script>
        $(document).on('click','.excel-import-delete', function(e){
            var id = $(this).attr('id');
            var _token = $('input[name="_token"]').val();
            $.ajax({
                method: "post",
                url: "{{route('account-head-delete-excel')}}",
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
        $(document).on("change", ".tr_value_submit", function(){
            var _token = $('input[name="_token"]').val();
            $(this).removeClass("div_error");
            tr_value_submit_obje = $(this).closest('.trFontSize');
            var temp_invoice_id = tr_value_submit_obje.find('.temp_invoice_id');
            var id = temp_invoice_id.val();
            var field_name = (this).name;
            var field_name = field_name.slice(0, -2);
            var field_value = $(this).val();
            var _token = $('input[name="_token"]').val();
            if(!field_value){
                toastr.warning("Empty value not updated","Warning");
            }else{
                $.ajax({
                    method: "post",
                    url: "{{route('update-account-head')}}",
                    data: {
                        field_name:field_name,
                        field_value:field_value,
                        id:id,
                        _token: _token,
                    },
                    success: function (response) {
                        toastr.success("Field value updated", "Success",{ timeOut: 500 });
                    }
                });
            }
        })
        $(document).on("change", ".check_error", function(){
            if($(this).val()){
                $(this).removeClass("error");
            }else{
                $(this).addClass("error");
            }
        })
    </script>
@endpush
