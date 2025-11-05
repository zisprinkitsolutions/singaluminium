@extends('layouts.backend.app')
@section('content')
@include('layouts.backend.partial.style')

    <!-- BEGIN: Content-->
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-body">
                <h4>Supplier Wise Purchase Report</h4>
                <form >
                    <div class="row">
                        <div class="col-md-10 ">
                            <select name="party_name" id="party_name" class="form-control common-select2">
                                <option value="">Select Name</option>
                                @foreach ($parties as $item)
                                    <option value="{{ $item->id }}" @if ($partyInfo) {{ $item->id == $partyInfo->id ? 'selected':''}} @endif >{{ $item->pi_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 justify-content-end text-right">
                            <button type="submit" class="btn mSearchingBotton mb-2 formButton" title="Search"  id="clickDate">
                                <div class="d-flex">
                                    <div class="formSaveIcon">
                                        <img src="{{asset("assets/backend/app-assets/icon/searching-icon.png")}}" width="25">
                                    </div>
                                    <div><span>Search</span></div>
                                </div>
                            </button>
                        </div>
                    </div>
                </form>
                @if ($purchase_lists)
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Purchase No</th>
                                <th>Code</th>
                                <th>Category/Service</th>
                                <th>Brand</th>
                                <th>Sub-Category</th>
                                <th>Amount</th>
                                <th>Unit</th>
                                <th>Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $total_amount=0; @endphp
                            @foreach ($purchase_lists as $invoice)
                                @foreach ($invoice->purchaseItems as $item)
                                    <tr>
                                        <th><a href="{{ route('purchaseView', $invoice) }}">{{ $invoice->purchase_no }}</a></th>
                                        <th>
                                                {{$item->barcode }}

                                        </th>
                                        <th>{{ $item->category->name }}</th>
                                        <th>{{ isset( $item->brand)?$item->brand->name:"" }}</th>
                                        <th>{{ isset($item->subBrand)?$item->subBrand->name:"" }}</th>
                                        <th>{{ $item->amount }}</th>
                                        <th>{{ $item->unitP->name }}</th>
                                        <th>{{$item->total_price}}</th>
                                    </tr>
                                @endforeach
                                @php  $total_amount=$total_amount+$invoice->TotalAmount(); @endphp
                            @endforeach
                            <tr class="border-top">
                                <td colspan="7" class="text-right">TOTAL AMOUNT (AED):</td>
                                <td>{{$total_amount}}</td>
                            </tr>
                        </tbody>
                    </table>
                @endif

            </div>
        </div>
    </div>
    <!-- END: Content-->
@endsection
