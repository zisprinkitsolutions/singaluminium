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
@php
    $key=0;
@endphp
@section('content')
    <div class="app-content content print-hideen">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-body">
                @include('clientReport.project._header')
                <div class="tab-content bg-white">
                    <div id="journaCreation" class="tab-pane active">
                        <section class="p-1" id="widgets-Statistics">
                            <form action="{{route('boq.store')}}" class="mt-1" method="POST">
                                @csrf
                                <input type="hidden" name="status" id="formStatus" value="final">

                                <div class="d-flex align-items-center">
                                    {{-- <div class="from-group text-left">
                                        <a href="{{route('boq.index')}}" class="btn btn-primary " style="margin-top: 20px; padding:5px 10px;">
                                            BOQ List
                                        </a>
                                    </div> --}}

                                    <div class="from-group" style="width:15%;">

                                        <label for="" class="text-left"> Company Name </label>

                                        <select name="party_id" id="party_id" required class="form-control common-select2 inputFieldHeight" required>
                                            <option value=""> Select </option>
                                            @foreach ($parties as $party)
                                                <option value="{{$party->id}}" {{$party_id == $party->id ? 'selected' : ''}}> {{$party->pi_name}} </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="from-group" style="width:15%; margin-left:5px;">
                                        <label for=""> Project Name </label>
                                        <select name="project_name" id="project_id" class="form-control inputFieldHeight inputFieldHeight" required>
                                            <option value=""> Select </option>
                                        </select>
                                    </div>

                                    <div class="from-group" style="width:10%; margin-left:5px;">
                                        <label for=""> Date </label>
                                        <input type="text" name="date" class="form-control inputFieldHeight datepicker inputFieldHeight" required autocomplete="off" value="{{date('d/m/Y')}}">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12">
                                        <div class="table-responsive boq-table mt-2">
                                            <table class="table  table-sm ">
                                                <thead>
                                                    <tr>
                                                        <th >Description</th>
                                                        <th style="width: 10%" class="text-center">QTY</th>
                                                        <th style="width: 10%" class="text-center">SQM</th>
                                                        <th style="width: 10%" class="text-center">Rate</th>
                                                        <th style="width: 15%" class="text-center">Total Amount</th>
                                                        <th class="NoPrint" style="width: 20px;padding: 2px;">
                                                            <button type="button" class="btn btn-sm btn-success addBtn"style="border: 1px solid green; color: #fff; border-radius: 10px;padding: 5px;" onclick="BtnAdd('#TRow', '#TBody','group-a')">
                                                                <i class="bx bx-plus" style="color: white;margin-top: -5px;"></i>
                                                            </button>
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody id="TBody">
                                                    <tr id="TRow" class="text-center invoice_row d-none">
                                                        <td>
                                                            <div class="d-flex justy-content-between align-items-center" >
                                                                <input type="text" name="group-a[0][description]" disabled  placeholder="Item Description" class="form-control inputFieldHeight description" required>
                                                            </div>
                                                        </td>

                                                        <td>
                                                            <input type="number" step="any" name="group-a[0][qty]" step="any" placeholder="QTY" class="text-center form-control inputFieldHeight qty"style="width: 100%;" disabled required>
                                                        </td>
                                                        <td>
                                                            <input type="number" step="any" name="group-a[0][sqm]" step="any" placeholder="SQM" class="text-center form-control inputFieldHeight sqm"style="width: 100%;" disabled required>
                                                        </td>
                                                        <td>
                                                            <input type="number" step="any" name="group-a[0][amount]" step="any" required placeholder="Rate" disabled class="text-center form-control inputFieldHeight amount"style="width: 100%;">
                                                        </td>

                                                        <td>
                                                            <input type="number" step="any" name="group-a[0][sub_gross_amount]" required disabled class="text-center form-control sub_gross_amount inputFieldHeight" placeholder="Total Amount" style="width: 100%;" readonly>
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
                                                                name="taxable_amount" value=""
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
                                                                name="total_vat" value=""
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
                                                                name="total_amount" value=""
                                                                placeholder="TOTAL " readonly required>
                                                            @error('total_amount')
                                                                <span class="error">{{ $message }}</span>
                                                            @enderror
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                        <div class="d-flex justify-content-center">
                                            <button type="button" class="btn btn-danger darft"> Darft </button>
                                            <button type="submit" class="btn btn-primary" style="margin-left:5px;" > Save </button>
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
        function BtnDel(v) {
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
calculateTotal()

$(document).on('change', '#party_id', function(){
    var party_id = $(this).val();
    var url = "{{route('get.party.project',':party_id')}}";
    url = url.replace(':party_id', party_id);

    $.ajax({
        url:url,
        type:'GET',
        success:function(res){
            $('#project_id').html('<option value=""> Select </option>');
            res.projects.forEach(function(project){
                $('#project_id').append(`<option value="${project.id}"> ${project.project_name} </option>`);
            });
        }
    });
});
let taskIndex = {{$key + 1}};
var tasks = @json($tasks);
$('#addTask').on('click', function () {
    const itemIndex = 0;

    const taskRow = `
        <tr class="task-row" data-task-index="${taskIndex}">
            <td style="width:15%;">
                <div class="d-flex">
                    <input type="text" name="task_name[${taskIndex}]" class="form-control inputFieldHeight task-name" placeholder="Task Name" required>
                </div>
            </td>
            <td colspan="8" style="width:85%;">
                <table class="table table-sm mb-0" style="width:100%;">
                    <tbody class="item-body">
                        <tr>
                            <td style="width:25%; border:none;">
                                <input type="text" name="item_description[${taskIndex}][${itemIndex}]" class="form-control inputFieldHeight" placeholder="Item Details" autocomplete="off" required>
                            </td>
                            <td style="width:10%">
                                <input type="text" name="unit[${taskIndex}][${itemIndex}]" class="form-control inputFieldHeight text-center" placeholder="Unit" required autocomplete="off">
                            </td>
                            <td style="width:10%; border:none;">
                                <input type="number" step="any" name="qty[${taskIndex}][${itemIndex}]" class="form-control inputFieldHeight qty text-right" placeholder="Qty" autocomplete="off" required>
                            </td>
                            <td style="width:10%; border:none;">
                                <input type="number" step="any"  name="rate[${taskIndex}][${itemIndex}]" class="form-control inputFieldHeight rate text-right" placeholder="Rate" autocomplete="off" required>
                            </td>
                            <td style="width:10%; border:none;">
                                <input type="number" step="any" name="amount[${taskIndex}][${itemIndex}]" class="form-control inputFieldHeight total text-right" placeholder="Amount" required>
                            </td>
                            <td style="width:5%">
                                <div class="d-flex">
                                    <button type="button" class="addItemBtn bg-info text-white" style="border: 1px solid #ddd;" title="Add Item">+</button>
                                    <button type="button" class="removeItemBtn bg-danger text-white" style="border: 1px solid #ddd;" title="Remove Item">X</button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
            <td style="width:5%;">
                <button type="button" class="removeTaskBtn bg-danger text-white" style="border: 1px solid #ddd;" title="Remove Task">X</button>
            </td>
        </tr>
    `;

    $('#boq-body').append(taskRow);
    taskIndex++;
});
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




$(document).on('change', '.task_id', function () {
    const $taskRow = $(this).closest('.task-row');
    const $subTaskBody = $taskRow.find('.subtask-row');
    $subTaskBody.empty();

    const selectedOption = $(this).find('option:selected');
    const subtasks = selectedOption.data('subtasks');

    if (Array.isArray(subtasks)) {
        subtasks.forEach(function (subtask, index) {
            addSubtask(subtask, $taskRow, subtasks);
        });
    }
});


// Add item
$(document).on('click', '.addItemBtn', function () {
    const $itemBody = $(this).closest('tbody');
    const $taskRow = $(this).closest('tr.task-row');
    const taskIndex = $taskRow.data('task-index');
    const itemIndex = $itemBody.find('tr').length; // New item index

    const itemRow = `
        <tr>
            <td style="width:25%; border:none;">
                <input type="text" name="item_description[${taskIndex}][${itemIndex}]" class="form-control inputFieldHeight" placeholder="Item Details" autocomplete="off" required>
            </td>
            <td style="width:10%; border:none;">
                <input type="text" name="unit[${taskIndex}][${itemIndex}]" class="form-control inputFieldHeight text-center" placeholder="Unit" autocomplete="off" required>
            </td>
            <td style="width:10%; border:none;">
                <input type="number" step="any" name="qty[${taskIndex}][${itemIndex}]" class="form-control inputFieldHeight qty text-right" placeholder="Qty" autocomplete="off" required>
            </td>
            <td style="width:10%; border:none;">
                <input type="number" step="any" name="rate[${taskIndex}][${itemIndex}]" class="form-control inputFieldHeight rate text-right" placeholder="Rate" autocomplete="off" required>
            </td>
            <td style="width:10%; border:none;">
                <input type="number" step="any" name="amount[${taskIndex}][${itemIndex}]" class="form-control inputFieldHeight total text-right" placeholder="Amount" required>
            </td>
            <td style="width:5%; border:none;">
                <div class="d-flex">
                    <button type="button" class="addItemBtn bg-info text-white" style="border: 1px solid #ddd;" title="Add Item">+</button>
                    <button type="button" class="removeItemBtn bg-danger text-white" style="border: 1px solid #ddd;" title="Remove Item">X</button>
                </div>
            </td>
        </tr>
    `;

    $itemBody.append(itemRow);
});

$(document).on('click', '.removeItemBtn', function () {
    $(this).closest('tr').remove();
});
// Remove task
$(document).on('click', '.removeTaskBtn', function () {
    $(this).closest('.task-row').remove();
});

$(document).on('click', '.removeSubTaskBtn', function () {
    $(this).closest('.subtask-row').remove();
})


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
    var grandTotal = 0;
    $('.qty').each(function(){
        var qty = parseFloat($(this).val());
        var rate = parseFloat($(this).closest('tr').find('.rate').val());
        var total = qty * rate;

        $(this).closest('tr').find('.total').val(total.toFixed(2));

        grandTotal += isNaN(total) ? 0 : total;
    })

    $('.grandTotal').each(function(){
        $(this).text(grandTotal.toFixed(2));
    });
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
