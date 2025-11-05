@extends('layouts.pdf.app2')

@section('content')
    <section class="container page-break">
        <div class="row pt-2">
            <div class="col-md-12 text-center">
                <h4> Stock Report</h4>
                <p>{{ date('d M Y') }}</p>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <tr>
                            <th>Category</th>
                            <th>Item</th>
                            <th>Code</th>
                            <th>Gallon</th>
                            <th>Liter</th>
                            <th>PCS</th>
                        </tr>
                        @php
                        $i=0;
                    @endphp
                    @foreach ($category->products as $item)
                    @if ($i==0)
                        <tr>
                            <td rowspan="{{ $category->products->count() }}">{{ $category->name }}</td>

                            <td>{{ isset($item->brand)? $item->brand->name:"" }}{{ isset($item->subBrand)? ', '.$item->subBrand->name:"" }}</td>
                            <td>{{ $item->barcode }} </td>
                            <td>{{ isset($item->stock)? ($item->stock->gallon!=null? $item->stock->gallon:"00"):"00" }}</td>
                            <td>{{ isset($item->stock)?($item->stock->liter!=null? $item->stock->liter:"00"):"00"  }}</td>
                            <td>{{ isset($item->stock)? ($item->stock->pcs!=null? $item->stock->pcs:"00"):"00"  }}</td>
                        </tr>
                        @php
                        $i=1;
                    @endphp
                    @else
                    <tr>

                        <td>{{ isset($item->brand)? $item->brand->name:"" }}{{ isset($item->subBrand)? ', '.$item->subBrand->name:"" }}</td>
                        <td>{{ $item->barcode }} </td>
                        <td>{{ isset($item->stock)? ($item->stock->gallon!=null? $item->stock->gallon:"00"):"00" }}</td>
                        <td>{{ isset($item->stock)?($item->stock->liter!=null? $item->stock->liter:"00"):"00"  }}</td>
                        <td>{{ isset($item->stock)? ($item->stock->pcs!=null? $item->stock->pcs:"00"):"00"  }}</td>
                    </tr>
                    @endif
                    @endforeach
                    </table>
                </div>
            </div>
        </div>
    </section>
@endsection
