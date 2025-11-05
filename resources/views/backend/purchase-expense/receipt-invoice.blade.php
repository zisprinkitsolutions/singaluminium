
@php
    $c=0;
@endphp
<table class="table table-bordered table-sm " >
    <thead>
        <tr >
            <th style="width: 5%">#</th>
            <th style="width: 25%">Purchase</th>
            <th style="width: 20%">Date</th>
            <th style="width: 25%">Total Amount <small>( @if(!empty($currency->symbole)){{$currency->symbole}}@endif)</small></th>
            <th style="width: 25%">Due Amount <small>( @if(!empty($currency->symbole)){{$currency->symbole}}@endif)</small></th>
        </tr>
    </thead>
    @foreach ($expenses as $item)
    <tr id="TRow" class="text-center">
        <td>{{++$c}}</td>
        <td>{{$item->purchase_no}}</td>
        <td>{{date('d/m/Y', strtotime($item->date))}}</td>
        <td>{{$item->total_amount}}</td>
        <td>{{$item->due_amount}}</td>
    </tr>
    @endforeach
    <tr>
        <td colspan="3"></td>
        <td class="text-center" style="color: black">Total Due</td>
        <td class="text-center">{{$expenses->sum('due_amount')}}</td>
    </tr>
</table>

