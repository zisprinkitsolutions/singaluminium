@extends('layouts.backend.app-pdf')
@section('content')
    <section>
        <div class="container py-4">
            <div class="row">
                <div class="col-md-12">
                <section id="widgets-Statistics">
                    <div class="row">
                        <div class="col-12 mt-1 mb-2">
                            <h4>Master Account Details</h4>
                            <hr>
                        </div>
                    </div>

                        <div class="row">
                                <table id="customers">
                                    <tr style="font-size: 12px; text-align: left !important;">
                                        <th> Code</th>
                                        <th style="text-align: left !important;">Master A/C Head</th>
                                        <th>Desfinition</th>
                                        <th>A/C Type</th>
                                        <th>VAT Type</th>
                                    </tr>

                                    @foreach ($masterDetails as $masterAcc)
                                    <tr style="font-size: 12px; border-bottom: 1px solid black !important;" >
                                        <td>{{ $masterAcc->mst_ac_code }}</td>
                                        <td>{{ $masterAcc->mst_ac_head }}</td>
                                        <td>{{ $masterAcc->mst_definition }}</td>
                                        <td>{{ $masterAcc->mst_ac_type }}</td>
                                        <td>{{ $masterAcc->vat_type }}</td>


                                    </tr>

                                    @endforeach



                                </table>
                        </div>
                </section>
                </div>
            </div>
        </div>
    </section>
@endsection
