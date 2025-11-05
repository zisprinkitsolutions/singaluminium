@extends('layouts.backend.app')
@push('css')
    @include('layouts.backend.partial.style')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.0/css/toastr.css" rel="stylesheet" />
    <style>
        thead{
            background: #34465b;
            color: #fff !important;
        }
        tr th{
            color: #fff !important;
        }
    </style>
@endpush
@section('content')
    <div class="app-content content print-hideen">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-body">
                @include('clientReport.project._header')
                <div class="tab-content bg-white">
                    <div id="journaCreation" class="tab-pane active">
                        <section class="p-1" id="widgets-Statistics">
                            <div class="d-flex justify-content-between">
                                <h5> Edit Bill Of Quantity (BOQ) </h5>
                                <div class="d-flex">
                                    <a href="{{route('boq.index')}}" class="btn btn-primary ml-1">
                                        BOQ List
                                    </a>
                                </div>
                            </div>

                            <form action="{{route('boq.update', $boq->id)}}" class="mt-1" method="POST">
                                @csrf
                                @method('put')

                                <input type="hidden" name="status" id="formStatus" value="final">
                                <div class="row">
                                    <div class="col-md-3 from-group">
                                        <label for=""> Company Name </label>
                                        <select name="party_id" id="party_id" required class="form-control inputFieldHeight inputFieldHeight">
                                            <option value="{{$boq->party_id}}"> {{optional($boq->party)->pi_name}} </option>
                                        </select>
                                    </div>

                                    <div class="col-md-3 from-group">
                                        <label for=""> Date </label>
                                        <input type="text" name="date" class="form-control inputFieldHeight datepicker inputFieldHeight" required autocomplete="off" value="{{date('d/m/Y')}}">
                                    </div>

                                    <div class="col-12">
                                        <div class="table-responsive boq-table mt-2">
                                            <table class="table  table-sm ">
                                                <thead>
                                                    <tr>
                                                        <th >Description</th>
                                                        <th style="width: 10%">QTY</th>
                                                        <th style="width: 10%">SQM</th>
                                                        <th style="width: 10%">Rate</th>
                                                        <th style="width: 15%">Total Amount</th>
                                                        <th class="NoPrint" style="width: 20px;padding: 2px;">
                                                            <button type="button" class="btn btn-sm btn-success addBtn"style="border: 1px solid green; color: #fff; border-radius: 10px;padding: 5px;" onclick="BtnAdd('#TRow', '#TBody','group-a')">
                                                                <i class="bx bx-plus" style="color: white;margin-top: -5px;"></i>
                                                            </button>
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody id="TBody">
                                                    @foreach ($boq->items as $key => $item)
                                                        <tr class="text-center invoice_row">
                                                            <td>
                                                                <div class="d-flex justy-content-between align-items-center" >
                                                                    <input type="text" name="group-a[{{$key}}][description]" value="{{$item->item_description}}"  placeholder="Item Description" class="form-control inputFieldHeight description" required>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <input type="number" step="any" name="group-a[{{$key}}][qty]" step="any" value="{{$item->qty}}" placeholder="QTY" class="text-center form-control inputFieldHeight qty"style="width: 100%;" required>
                                                            </td>
                                                            <td>
                                                                <input type="number" step="any" name="group-a[{{$key}}][sqm]" step="any" placeholder="SQM" value="{{$item->sqm}}" class="text-center form-control inputFieldHeight sqm"style="width: 100%;" required>
                                                            </td>
                                                            <td>
                                                                <input type="number" step="any" name="group-a[{{$key}}][amount]" step="any" required placeholder="Rate" value="{{$item->rate}}" class="text-center form-control inputFieldHeight amount"style="width: 100%;">
                                                            </td>

                                                            <td>
                                                                <input type="number" step="any" name="group-a[{{$key}}][sub_gross_amount]" required value="{{$item->total}}" class="text-center form-control sub_gross_amount inputFieldHeight" placeholder="Total Amount" style="width: 100%;" readonly>
                                                            </td>
                                                            <td class="NoPrint add_button text-center d-flex" style="margin-top: 5px;">
                                                                <button type="button" class="bg-danger custom-btn" onclick="BtnDelItem(this)">
                                                                    <i class="bx bx-trash"></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                    <tr id="TRow" class="text-center invoice_row d-none">
                                                        <td>
                                                            <div class="d-flex justy-content-between align-items-center" >
                                                                <input type="text" name="group-a[{{count($boq->tasks)+1}}][description]" disabled  placeholder="Item Description" class="form-control inputFieldHeight description" required>
                                                            </div>
                                                        </td>

                                                        <td>
                                                            <input type="number" step="any" name="group-a[{{count($boq->tasks)+1}}][qty]" step="any" placeholder="QTY" class="text-center form-control inputFieldHeight qty"style="width: 100%;" disabled required>
                                                        </td>
                                                        <td>
                                                            <input type="number" step="any" name="group-a[{{count($boq->tasks)+1}}][sqm]" step="any" placeholder="SQM" class="text-center form-control inputFieldHeight sqm"style="width: 100%;" disabled required>
                                                        </td>
                                                        <td>
                                                            <input type="number" step="any" name="group-a[{{count($boq->tasks)+1}}][amount]" step="any" required placeholder="Rate" disabled class="text-center form-control inputFieldHeight amount"style="width: 100%;">
                                                        </td>

                                                        <td>
                                                            <input type="number" step="any" name="group-a[{{count($boq->tasks)+1}}][sub_gross_amount]" required disabled class="text-center form-control sub_gross_amount inputFieldHeight" placeholder="Total Amount" style="width: 100%;" readonly>
                                                        </td>
                                                        <td class="NoPrint add_button text-center d-flex" style="margin-top: 5px;">
                                                            <button type="button" class="bg-danger custom-btn" onclick="BtnDelItem(this)">
                                                                <i class="bx bx-trash"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                                <tbody>
                                                    <tr>
                                                        <td class="text-right pr-1" colspan="4" style="color: black">AMOUNT</td>
                                                        <td><input type="number" step="any" readonly
                                                                id="taxable_amount"
                                                                class="text-center form-control inputFieldHeight2 @error('taxable_amount') error @enderror inputFieldHeight taxable_amount"
                                                                name="taxable_amount" value="{{$boq->amount}}"
                                                                placeholder="AMOUNT" readonly required>
                                                            @error('taxable_amount')
                                                                <span class="error">{{ $message }}</span>
                                                            @enderror
                                                        </td>
                                                    </tr>

                                                    <tr class="text-center">
                                                        <td class="text-right pr-1" colspan="4" style="color: black">VAT 5%</td>
                                                        <td><input type="number" step="any" readonly
                                                                id="total_vat"
                                                                class="text-center inputFieldHeight2 form-control @error('total_vat') error @enderror inputFieldHeight total_vat"
                                                                name="total_vat" value="{{$boq->vat}}"
                                                                placeholder="@if (!empty($currency->vat_name)) {{ $currency->vat_name }} @endif SUBTOTAL"
                                                                readonly required>
                                                            @error('total_vat')
                                                                <span class="error">{{ $message }}</span>
                                                            @enderror
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td class="text-right pr-1" colspan="4" style="color: black">TOTAL AMOUNT</td>
                                                        <td><input type="number" step="any" readonly
                                                                id="total_amount"
                                                                class="text-center inputFieldHeight2 form-control @error('total_amount') error @enderror inputFieldHeight total_amount"
                                                                name="total_amount" value="{{$boq->total_amount}}"
                                                                placeholder="TOTAL " readonly required>
                                                            @error('total_amount')
                                                                <span class="error">{{ $message }}</span>
                                                            @enderror
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="d-flex justify-content-end">
                                            <button type="submit" class="btn btn-primary ml-1"> Save </button>
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
@endsection

@push('js')
<script>
        BtnAdd('#TRow', '#TBody', 'group-a');

    function BtnAdd(trow, tbody, inputs) {
        var $trow = $(trow);
        var $tbody = $(tbody);
        var newRow = $trow.clone().removeClass("d-none").removeAttr('id');
        newRow.find("input, select, textarea").prop('disabled', false);
        newRow.find("select").addClass('commom-select3');
        newRow.find(".date-").addClass('datepicker');

        var $rows = $tbody.children('tr').not($trow);
        var lastIndex = 0;
        var lastRow = $rows.last();

        // Dynamic input handling
        var lastInputName = lastRow.find("input[name^='" + inputs + "']").attr('name');
        var lastSelectName = lastRow.find("select[name^='" + inputs + "']").attr('name');
        var lastTextName = lastRow.find("textarea[name^='" + inputs + "']").attr('name');
        var lastName = lastInputName || lastSelectName || lastTextName;

        var match = lastName && lastName.match(/\[(\d+)\]/);
        if (match) {
            lastIndex = parseInt(match[1], 10);
        }

        var newIndex = lastIndex + 1;

        newRow.find("input, select, textarea").attr('name', function(index, name) {
            return name.replace(/\[\d+\]/, '[' + newIndex + ']');
        });

        newRow.appendTo(tbody);

        // Initialize select2 on the newly added row
        newRow.find(".commom-select3").each(function() {
            if ($(this).data('select2')) {
                $(this).select2('destroy');
            }
            $(this).select2();
        });
    }
    function BtnDelItem(v) {
        $(v).parent().parent().remove();
        $("#TBody").find("tr").each(function(index) {
            $(this).find("th").first().html(index);
        });
        total();
    }
    $(document).on('keyup', '.sqm, .amount', function(e){
        tr = $(this).closest('tr');
        rate = tr.find('.amount').val();
        sqm = tr.find('.sqm').val();
        amount = Number(sqm)*Number(rate);
        sub_gross_amount = tr.find('.sub_gross_amount').val(amount.toFixed(2));
        total();
    });
    function total() {
        var sum = 0;
        $('.sub_gross_amount').each(function() {
            var this_amount = $(this).val();
            this_amount = (this_amount === '') ? 0 : this_amount;
            var this_amount = parseFloat(this_amount);
            sum = sum + this_amount;
        });
        var taxable = sum.toFixed(2);
        var vat = taxable*0.05;
        var total = (vat * 1) + (taxable * 1)
        $(".taxable_amount").val(taxable);
        $(".total_vat").val(vat.toFixed(2));
        $(".total_amount").val((total.toFixed(2)));
    };
var tasks = @json($tasks);

$(document).on('change', '#project_id', function(){
    var project_id = $(this).val();
    var url = "{{route('get.project.task',':project_id')}}";
    url = url.replace(':project_id', project_id);
    $.ajax({
        url:url,
        type:'GET',
        success:function(res){
            tasks = res.tasks;
        }
    });
});

// Remove task
$(document).on('click', '.removeTaskBtn', function () {
    $(this).closest('.task-row').remove();
});


$(document).on('click', '.removeSubTaskBtn', function () {
    $(this).closest('.subtask-row').remove();
})

$(document).on('click', '.removeItemBtn', function () {
    $(this).closest('tr').remove();
});

$(document).on('click', '.removeItemBtn', function () {
    $(this).closest('tr').remove();
});

$(document).on('click', '.task-toggler', function () {
    var td = $(this).closest('td');
    var task_id = td.find('select');
    var task_name = td.find('.task-name');

    task_id.each(function () {
        if ($(this).hasClass('d-none')) {
            $(this).removeClass('d-none').prop('disabled', false);
        } else {
            $(this).addClass('d-none').val('').prop('disabled', true);
        }
    });

    task_name.each(function (){
        if ($(this).hasClass('d-none')) {
            $(this).removeClass('d-none').prop('disabled', false);
        } else {
            $(this).addClass('d-none');
        }
    });
});


function calculateTotal(){
    $('.qty').each(function(){
        var qty = parseFloat($(this).val());
        var rate = parseFloat($(this).closest('tr').find('.rate').val());
        var total = qty * rate;

        $(this).closest('tr').find('.total').val(total.toFixed(2));
    })
}

$(document).on('keyup', '.qty, .rate', function () {
    calculateTotal();
});

$('.darft').on('click', function (e) {
    e.preventDefault();
    $('#formStatus').val('draft');
    $('form').submit();
});

</script>
@endpush
