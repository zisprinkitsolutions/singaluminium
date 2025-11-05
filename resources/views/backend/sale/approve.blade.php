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
        background: #34465b;
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
        text-align: center !important;
    }

    .table-sm th, .table-sm td {
        padding: 0rem;
    }

    tr:nth-child(even) {
        background-color: #c8d6e357;
    }
    tr{
    cursor: pointer;
}
</style>
<div class="app-content content print-hideen">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-body">
            @include('clientReport.sales._header',['activeMenu' => 'invoice'])
            <div class="tab-content journaCreation">
                <div id="journaCreation" class="tab-pane bg-white active">
                    <div class="py-1 px-1">
                        @include('clientReport.sales._subhead_sale', [
                            'activeMenu' => 'approve',
                        ])
                    </div>
                    <section id="widgets-Statistics">

                            <div class="col-md-12">
                                <div class="row ">
                                    <div class="cardStyleChange" >
                                        <div class="card-body bg-white">
                                            <div class="row">
                                                <div class="col-4">
                                                    <input type="text" name="search" id="search"
                                                        class="form-control inputFieldHeight"
                                                        placeholder="Search by Bill No, Invoice No">
                                                </div>
                                                <div class="col-4">
                                                    <select name="party_search" id="party_search"
                                                        class="common-select2 inputFieldHeight w-100">
                                                        <option value="">Select Party...</option>
                                                        @foreach ($parties as $party)
                                                            <option value="{{ $party->id }}">{{ $party->pi_name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-4">
                                                    <input type="text" name="date_search" id="date_search"
                                                        class="form-control inputFieldHeight datepicker"
                                                        placeholder="Search by Date">
                                                </div>
                                            </div><br>

                                            <table class="table table-bordered table-sm " style="width: 850px">
                                                <thead class="thead">
                                                    <tr >
                                                        <th style="width: 16%">Date</th>
                                                        <th style="width: 24%">Invoice No</th>
                                                        <th style="width: 40%">Party Name</th>
                                                        <th style="width: 20%">Amount <small>(@if(!empty($currency->symbole)){{$currency->symbole}}@endif)</small></th>


                                                    </tr>
                                                </thead>
                                                    <tbody id="purch-body">
                                                    @foreach ($sales as $item)
                                                    <tr class="sale_view"  id="{{$item->id}}">
                                                        <td>{{date('d/m/Y',strtotime($item->date))}}</td>
                                                        @if($item->invoice_type == 'Proforma Invoice')
                                                        <td>{{$item->proforma_invoice_no}}</td>
                                                        @else
                                                        <td>{{$item->invoice_no}}</td>
                                                        @endif
                                                        <td>{{$item->party->pi_name}}</td>
                                                        <td>{{number_format($item->total_amount,2)}}</td>
                                                    </tr>

                                                    @endforeach
                                                </tbody>

                                            </table>
                                            {!! $sales->links()!!}

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

<script>
$(document).on("click", ".sale_view", function(e) {
e.preventDefault();
var id= $(this).attr('id');
$.ajax({
    url: "{{URL('approve-sale-modal')}}",
    type: "post",
    cache: false,
    data:{
        _token:'{{ csrf_token() }}',
        id:id,
    },
    success: function(response){
        document.getElementById("voucherPreviewShow").innerHTML = response;
        $('#voucherPreviewModal').modal('show')
    }
});
});

$('#search').keyup(function() {
        var value = $(this).val();
        var party = $('#party_search').val();
        var date = $('#date_search').val();
        var type = 'Proforma Invoice'

        var _token = $('input[name="_token"]').val();
        $.ajax({
            url: "{{ route('search-sale') }}",
            method: "POST",
            data: {
                value: value,
                party: party,
                date:date,
                type:type,
                _token: _token,
            },
            success: function(response) {

                $("#purch-body").empty().append(response);
            }
        })

});

$('#party_search').change(function() {
        var party = $(this).val();
        var value = $('#search').val();
        var date = $('#date_search').val();
         var type = 'Proforma Invoice'
        var _token = $('input[name="_token"]').val();
        $.ajax({
            url: "{{ route('search-sale') }}",
            method: "POST",
            data: {
                value: value,
                party: party,
                date:date,
                type:type,
                _token: _token,
            },
            success: function(response) {

                $("#purch-body").empty().append(response);
            }
        })
});

$('#date_search').change(function() {
        var date = $(this).val();
        var value = $('#search').val();
        var party = $('#party_search').val();
         var type = 'Proforma Invoice'
        var _token = $('input[name="_token"]').val();
        $.ajax({
            url: "{{ route('search-sale') }}",
            method: "POST",
            data: {
                value: value,
                party: party,
                date:date,
                type:type,
                _token: _token,
            },
            success: function(response) {

                $("#purch-body").empty().append(response);
            }
        })
});
//edit
$(document).on('change','#party_info',function(){
        var value = $(this).val();
        var _token = $('input[name="_token"]').val();
        $.ajax({
            url: "{{ route('partyInfoInvoice2') }}",
            method: "POST",
            data: {
                value: value,
                _token: _token,
            },
            success: function(response) {
                console.log(response);
                $("#trn_no").val(response.trn_no);
                $("#pi_code").val(response.pi_code);
                $("#party_contact").val(response.con_no);
                $("#party_address").val(response.address);
                $("#invoice_no").focus();
            }
        })

});

function BtnAdd() {
    /* Add Button */
    var newRow = $("#TRow").clone();
    newRow.removeClass("d-none");
    newRow.find("input, select").val('').attr('name', function(index, name) {
        return name.replace(/\[\d+\]/, '[' + ($('#TBody tr').length) + ']');
    });
    newRow.find("th").first().html($('#TBody tr').length + 1);
    newRow.appendTo("#TBody");
    newRow.find(".common-select2").select2();
}

function BtnDel(v) {
    /* Delete Button */
    $(v).parent().parent().remove();

    $("#TBody").find("tr").each(function(index) {
        $(this).find("th").first().html(index);
    });

    total()
}

$(document).on("keyup", ".qty", function(e) {
    var qty = $(this).val();
    var rate =  $(this).closest("tr").find(".rate").val();
    var amount = qty*rate;
    $(this).closest("tr").find(".amount").val(amount);
    total();
});

$(document).on("keyup", ".rate", function(e) {
    var rate = $(this).val();
    var qty =  $(this).closest("tr").find(".qty").val();
    var amount = qty*rate;
    $(this).closest("tr").find(".amount").val(amount);
    total();
});

$(document).on("change", "#invoice_type", function(e) {
total()
});
function total() {
    var sum=0;
    $('.amount').each(function() {
        var this_amount= $(this).val();
        this_amount = (this_amount === '') ? 0 : this_amount;
        var this_amount = parseFloat(this_amount);
        //
        sum = sum+this_amount;
    });
    var result = sum.toFixed(2)
    var standard_vat_rate=$('#standard_vat_rate').val();
    var invoice_type=$('#invoice_type').val();
    if(invoice_type=='Tax Invoice')
    {
        var vat=(standard_vat_rate/100)*result;
    }
    else
    {
        var vat=0;
    }
    // alert(vat);
    var total_vat= vat.toFixed(2)
    $(".taxable_amount").val(result);
    $(".total_vat").val(vat.toFixed(2));
    $(".total_amount").val((total_vat*1)+(result*1));
};

function toggleEditForm(){
    $('.edit-form').toggleClass('d-none')
    $('.sale-view').toggleClass('d-none');
}

$(document).on('submit','#formSubmit',function(e) {
    e.preventDefault(); // avoid executing the actual submit of the form.
    var form = $(this);
    var url = form.attr('action');
    var data = new FormData(this);
    $.ajax({
        url: url,
        method: 'POST',
        data: data,
        contentType: false,
        cache: false,
        processData: false,
        headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()},

        success: function(response) {
            if (response.warning) {
                toastr.warning("{{ Session::get('message') }}", response.warning);
            } else if (response.status) {
                // Handle validation errors
                for (var i = 0; i < Object.keys(response.status).length; i++) {
                    var key = i + ".invoice";
                    if (response.status.hasOwnProperty(key)) {
                        var errorMessages = response.status[key];
                        for (var j = 0; j < errorMessages.length; j++) {
                            toastr.warning(errorMessages[j]);
                        }
                    }
                }
            } else {
                toastr.success("Invoice has been updated successfully",'success');
                $("#submitButton").prop("disabled", true)
                $(".deleteBtn").prop("disabled", true)
                $(".addBtn").prop("disabled", true)
                document.getElementById("voucherPreviewShow").innerHTML = response;
                $('#voucherPreviewModal').modal('show');
                $("#newButton").removeClass("d-none")
                $("#submitButton").addClass("d-none")
            }
        },
        error: function(err) {
            let error = err.responseJSON;
            $.each(error.errors, function(index, value) {
                toastr.error(value, "Error");
            });
        }
    });
});

function refreshPage() {
    window.location.reload();
}
$(document).on('submit', '.voucher-img-form', function (e) {
        e.preventDefault(); // stop form submission

        if (!confirm('Are you sure you want to delete this file?')) return;

        let wrapper = $(this).closest('.voucher-img-wrapper');
        let url = $(this).attr('action');

        $.ajax({
            url: url,
            method: 'POST',
            data: {
                _method: 'DELETE', // simulate DELETE request
                _token: '{{ csrf_token() }}'
            },
            success: function (res) {
                wrapper.remove();
                toastr.success(res.message || 'File deleted successfully.');
            },
            error: function () {
                alert('Failed to delete the file.');
            }
        });
    });

</script>
@endpush
