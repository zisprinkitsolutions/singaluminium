@extends('layouts.backend.app')
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.0/css/toastr.css" rel="stylesheet" />
@section('content')
@include('layouts.backend.partial.style')
<style>
    .changeColStyle span{
        min-width: 16%;
    }
    .changeColStyle .select2-container--default .select2-selection--single .select2-selection__arrow b{
        display: none;
    }
    .journaCreation{
        background: #1214161c;
    }
    .transaction_type{
        padding-right:5px;
        padding-left:5px;
        padding-bottom:5px;
    }
    @media only screen and (max-width: 1500px) {
        .custome-project span{
            max-width: 140px;
        }
    }

    thead {
    background: #8d8888;
    color: #fff !important;
}
th{
    color: #fff !important;
    font-size: 11px !important;
    height: 25px !important;
    text-align: center !important;
}
td
{
    font-size: 12px !important;
    height: 25px !important;
}

.table-sm th, .table-sm td {
    padding: 0rem;
}

</style>
<div class="app-content content print-hideen">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-body">
            @include('backend.purchase-expense.tabs')
            <div class="tab-content journaCreation">
                <div id="journaCreation" class="tab-pane active">
                    <section id="widgets-Statistics">
                     
                            <form action="{{ route('receipt-post') }}" method="POST" enctype="multipart/form-data" >
                            @csrf
                            <div class="cardStyleChange bg-white">
                                <div class="card-body pb-1">
                                    <div class="row mx-1 mt-1 d-flex justify-content-center">
                                        <div class="col-md-3 changeColStyle search-item-pi">
                                            <div class="row align-items-center">
                                                 <div class="col-2">
                                                     <label for="">Party Name</label>
                                                     
                                                 </div>
                                                 <div class="col-10">
                                                     <select name="party_info" id="party_info"
                                                     class="common-select2 party-info" style="width: 100% !important" data-target="" required>
                                                         <option value="">Select...</option>
                                                         @foreach ($parties as $item)
                                                             <option value="{{ $item->id }}"
                                                                 {{ isset($journalF) ? ($journalF->party_info_id == $item->id ? 'selected' : '') : '' }}>
                                                                 {{ $item->pi_name }}</option>
                                                         @endforeach
                                                     </select>
                                                     @error('party_info')
                                                         <div class="btn btn-sm btn-danger">{{ $message }}
                                                         </div>
                                                     @enderror
                                                 </div>
                                            </div>
                                         </div>
                                        <div class="col-md-2 changeColStyle">
                                           <div class="row aling-items-center">
                                            <div class="col-3">
                                                <label for="">Party Code </label>
                                            </div>
                                            <div class="col-9">
                                                <input type="text" name="pi_code" id="pi_code" class="form-control inputFieldHeight" required placeholder="Party Code">
                                                @error('party_info')
                                                    <div class="btn btn-sm btn-danger">{{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                           </div>
                                        </div>
                                       
                                        <div class="col-md-2 changeColStyle">
                                            <div class="row align-items-center">
                                                <div class="col-2">
                                                    <label for="">TRN</label>
                                           
                                                </div>
                                                <div class="col-10">
                                                    <input type="text" class="form-control inputFieldHeight"
                                                    value="{{ isset($journalF) ? $journalF->partyInfo->trn_no : '' }}"
                                                    name="trn_no" id="trn_no" class="form-control" readonly>
                                                @error('trn_no')
                                                    <div class="btn btn-sm btn-danger">{{ $message }}
                                                    </div>
                                                @enderror
                                                </div>
                                            </div>
                                        </div>
                                     
                                        <div class="col-md-2 changeColStyle">
                                            <div class="row align-items-center">
                                                <div class="col-4">
                                                    <label for="">Payment Mode</label>
                                          
                                                </div>
                                                <div class="col-8">
                                                    <select name="pay_mode" id="pay_mode" class="form-control inputFieldHeight" required>
                                                        <option value="">Select...</option>
                                                        
                                                        @foreach ($modes as $item)
                                                            <option value="{{ $item->title }}"
                                                                {{ isset($journalF) ? ($journalF->txn_mode == $item->title ? 'selected' : '') : '' }}>
                                                                {{ $item->title }} </option>
                                                        @endforeach
                                                        
                                                    </select>
                                                    @error('pay_mode')
                                                        <div class="btn btn-sm btn-danger">{{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                       
                                        <div class="col-md-3 changeColStyle" id="printarea">
                                            <div class="row align-items-center">
                                                <div class="col-4">
                                                    <label for="">Receipt Date</label>
                                           
                                                </div>
                                                <div class="col-8">
                                                    <input type="date" value="{{ isset($journalF) ? $journalF->date : Carbon\Carbon::now()->format('Y-m-d') }}" class="form-control inputFieldHeight" name="date" id="date" placeholder="dd-mm-yyyy" >
                                                    @error('date')
                                                        <div class="btn btn-sm btn-danger">{{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="row my-1">
                                    <div class="cardStyleChange" style="width: 100%">
                                        <div class="card-body bg-white">
                                            <table class="table table-bordered table-sm ">
                                                <thead>
                                                    <tr >
                                                        <th  style="width: 5%">#</th>
                                                        <th  style="width: 28%">Purchase</th>
                                                        <th style="width: 28%">Due Amount</th>
                                                        <th  style="width: 28%">Pay AMount</th>
                                                        <th  class="NoPrint"> <button type="button" class="btn btn-sm "style="border: 1px solid #fff;
                                                            color: #fff; border-radius: 10px;padding: 5px; margin: 4px;" onclick="BtnAdd()">ADD</button>
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody id="TBody">
                                                    <tr id="TRow" >
                                                        <th  scope="row" style="width: 5%; text-align:center;color:black !important; font-size:14px !important">1</th>
                                                        <td>
                                                             <select name="group-a[0][purchase]"onchange="option(this);"  class="purchase_no form-control multi-acc-head input-due-payment" style="width: 100%;    HEIGHT: 36PX;">
                                                                <option value=""> ----- Choice Option ----</option>
                                                                @foreach ($sales as $item)

                                                                <option value="{{ $item->id }}"
                                                                    {{ $item->id }}>
                                                                    {{ $item->invoice_no }}</option>
                                                            @endforeach
                                                            </select>
                                                        </td>
                                                       
                                                        <td><input type="number" step="any" name="group-a[0][due_amount]" step="any"  class="form-control inputFieldHeight due_amount" readonly></td>
                                                     
                                                        <td><input type="number" step="any"  class="form-control amount_withvat" name="group-a[0][pay_amount]" onkeyup="Calc(this);">
                                                        </td>
                                                        <td class="NoPrint"><button style="border-radius: 10px;padding: 5px; margin: 4px;" type="button" class="btn btn-sm btn-danger"onclick="BtnDel(this)">DELETE</button></td>
                                                    </tr>
                                                </tbody>
                                                <tbody>
                                                    <tr>
                                                        <td colspan="3"></td>
                                                        <td class="text-center" style="color: black">Total Amount</td>
                                                        <td><input type="text" id="total_amount" class="form-control @error('total_amount') error @enderror inputFieldHeight" name="total_amount" value="" placeholder="Total Amount" readonly required>
                                                            @error('total_amount')
                                                            <span class="error">{{ $message }}</span>
                                                            @enderror</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="cardStyleChange">
                                <div class="card-body bg-white">
                                    <div class="row p-1">
                                        <div class="col-sm-7 form-group">
                                            <label for="">Narration</label>
                                            <input type="text" class="form-control inputFieldHeight" name="narration"
                                                id="narration" placeholder="Narration"
                                                value="{{ isset($journalF) ? $journalF->narration : '' }}"
                                                required>
                                        </div>

                                        <div class="col-sm-3 form-group">
                                            <label for="">Voucher Scan/File</label>
                                            <input type="file" class="form-control inputFieldHeight" name="voucher_scan" accept="image/*" >
                                        </div>
                                        <div class="col-sm-2 text-right d-flex justify-content-end mt-2 mb-1">
                                            <button type="submit" class="btn btn-primary formButton" id="formSubmit">
                                                <div class="d-flex">
                                                    <div class="formSaveIcon">
                                                        <img  src="{{asset('assets/backend/app-assets/icon/save-icon.png')}}" alt="" srcset=""  width="25">
                                                    </div>
                                                    <div><span>Save</span></div>
                                                </div>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </section>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- modal --}}
    <!-- END: Content-->
    <div class="modal fade bd-example-modal-lg" id="voucherPreviewModal" tabindex="-1" rrole="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
          <div class="modal-content">
            <div id="voucherPreviewShow">
              
            </div>
          </div>
        </div>
    </div>
    <div class="modal fade bd-example-modal-lg" id="voucherDetailsPrintModal" tabindex="-1" rrole="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
          <div class="modal-content">
            <div id="voucherDetailsPrint">
              
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

{{-- js work by mominul end --}}

<script>
    $(document).ready(function() {

        // $('.btn_create').click(function(){
        $(document).on("click", ".btn_create", function(e){
            e.preventDefault();
            // alert('Alhamdulillah');
            setTimeout(function() {
                $('.multi-acc-head').select2();
                $('.multi-tax-rate').select2();
            }, 1000);
        });

        // on select multiple account head
     
   
        
        $("#date").focus();
       
       
     
        $(document).on('change', '.purchase_no', function(){
            var value = $(this).val();
            var amount_obj= $(this).closest('#TRow').find('.due_amount');
            var _token = $('input[name="_token"]').val();
            $.ajax({
                url: "{{ route('findinvoiceRec') }}",
                method: "POST",
                data: {
                    value: value,
                    _token: _token,
                },
                success: function(response) {
                    console.log(response);
                    $(amount_obj).val(response.due_amount);
                    sum_all_amount();
                }
            })
        });



        $('#party_info').change(function() {
            if ($(this).val() != '') {
                var value = $(this).val();
                var _token = $('input[name="_token"]').val();
                $.ajax({
                    url: "{{ route('partyInfoInvoice2R') }}",
                    method: "POST",
                    data: {
                        value: value,
                        _token: _token,
                    },
                    success: function(response) {
                        console.log(response);
                        $("#trn_no").val(response.info.trn_no);
                        $("#pi_code").val(response.info.pi_code);
                        $(".purchase_no").empty().append(response.page);


                    }
                })
            }
        });

   

        $(document).on("keyup", "#pi_code", function(e) {
            // alert(1);
            var value = $(this).val();
            var _token = $('input[name="_token"]').val();
            if ($(this).val() != '') {
            $.ajax({
                url: "{{ route('partyInfoInvoice3') }}",
                method: "POST",
                data: {
                    value: value,
                    _token: _token,
                },
                success: function(response) {
                    console.log(response);
                    var qty = 1;
                    if (response != '') {
                        $("div.search-item-pi select").val(response.id);
                        $('.common-select2').select2();
                        $("#trn_no").val(response.trn_no);
                        $("#invoice_no").focus();
                    }
                }
            })
        }
        });




        $(document).on("keypress", "#pi_code", function(e) {
            var key = e.which;
            var value = $(this).val();
            if (e.which == 13) {


                $("#party_info").focus();
                e.preventDefault();
                return false;
            }

        });



    

        $(document).on("keypress", "#amount", function(e) {
            var key = e.which;
            var value = $(this).val();
                if (e.which == 13) {
                $("#tax_rate").focus();
                e.preventDefault();
                return false;
            }
        });


     

    });

    function BtnAdd() {
    /* Add Button */
    var newRow = $("#TRow").clone();
    newRow.removeClass("d-none");

    newRow.find("input, select").val('').attr('name', function(index, name) {
        return name.replace(/\[\d+\]/, '[' + ($('#TBody tr').length) + ']');
    });

    newRow.find("th").first().html($('#TBody tr').length+1 );
    newRow.appendTo("#TBody");
    newRow.find(".common-select2").select2();
 }


 $(document).on("click", "#formSubmit", function(e) {
    alert(1);
        e.preventDefault();
        var date = document.getElementById('date').value;
        var charity_id = document.getElementById('charity_id').value;
        var income = document.getElementById('income').value;
        var class_id = document.getElementById('class_id').value;
        var section_id = document.getElementById('section_id').value;
        var student_id = document.getElementById('student_id').value;
        var amount = document.getElementById('amount').value;
        $.ajax({
            url: "{{URL('charity-collection-store')}}",
            type: "post",
            cache: false,
            data:{
                _token:'{{ csrf_token() }}',
                date:date,
                donar_id:charity_id,
                income:income,
                class_id:class_id,
                section_id:section_id,
                student_id:student_id,
                amount:amount,
            },
            success: function(response){
                $('#createModal').modal('hide');
                document.getElementById("printOtherIncomeModalContent").innerHTML = response;
                $('#printOtherIncomeModal').modal('show');
                setTimeout(printFunction, 500);
            }
        });
    });

 function BtnDel(v) {
    /* Delete Button */
    $(v).parent().parent().remove();
    sum_all_amount();

    $("#TBody").find("tr").each(function(index) {
        $(this).find("th").first().html(index);
    });

 }
$(document).on("keyup", ".amount_withvat", function(e) {
    sum_all_amount()
        });

 function sum_all_amount(){
            var sum=0;
            $('.amount_withvat').each(function() {                    
                var this_amount= $(this).val();
                this_amount = (this_amount === '') ? 0 : this_amount;
                this_amount= parseInt(this_amount);
                // 
                sum = sum+this_amount;
            });
            console.log(sum);
            $('#total_amount').val(sum);
        }
    
</script>

@endpush
