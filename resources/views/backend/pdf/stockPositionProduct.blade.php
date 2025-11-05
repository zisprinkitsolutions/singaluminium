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
                        <tr>
                        <td>{{ $product ->category->name }}</td>

                        <td>{{ isset($product ->brand)? $product ->brand->name:"" }}{{ isset($product ->subBrand)? ', '.$product ->subBrand->name:"" }}</td>
                        <td>{{ $product ->barcode }}</td>
                        <td>{{ isset($product ->stock)? ($product ->stock->gallon!=null? $product ->stock->gallon:"00"):"00" }}</td>
                        <td>{{ isset($product ->stock)?($product ->stock->liter!=null? $product ->stock->liter:"00"):"00"  }}</td>
                        <td>{{ isset($product ->stock)? ($product ->stock->pcs!=null? $product ->stock->pcs:"00"):"00"  }}</td>
                    </tr>
                    </table>
                </div>
            </div>
        </div>
    </section>
@endsection
