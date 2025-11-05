
@php
    $c=0;
@endphp
<table class="table table-bordered table-sm " >
    <thead>
        <tr >
            <th style="width: 10%">
                <input type="checkbox" id="vehicle1" class="btn-select-all">
                <label for="vehicle1" style="color: white;">S All</label>
            </th>
            <th>Invoice</th>
            <th>Date</th>
            <th style="width: 15%">Total Amount</th>
            <th style="width:15%" class="d-none"> Discount</th>
            <th style="width: 15%">Due Amount <small>( @if(!empty($currency->symbole)){{$currency->symbole}}@endif)</small></th>
        </tr>
    </thead>
    @php
        $ind=0;
    @endphp
    @foreach ($invoices as $inv)
    <tr id="TRow" >
        <td>
            <input type="checkbox" class="checkbox-record" name="records[{{$ind}}]" value="{{$inv->id}}">
        </td>
        <td>
            {{$inv->invoice_no}}
        </td>
        <td>
            {{date('d/m/Y', strtotime($inv->date))}}
        </td>
        <td>
            {{$inv->total_budget}}
        </td>

        <td class="d-none">
            <input type="number" step="any" value="" name="inv_discount[{{$ind++}}]" class="inv_discount form-control inputFieldHeight">
        </td>
        <td class="inv_due" data-due="{{$inv->due_amount}}">
            {{$inv->due_amount}}
        </td>
    </tr>
    @endforeach
    <tr>
        <td colspan="3"></td>
        <td class="text-center" style="color: black">Total Due</td>
        <td class="total_due">{{$invoices->sum('due_amount')}}</td>
    </tr>
</table>

