@extends('layouts.print_app')
@section('content')
<div class="row">
    <div class="col-sm-12">
        <h4 class="text-center" style="margin:0;padding:5px 10px;line-height:29px;background-color:#666f79;color:#fff;"> LOCAL PURCHASE ORDER </h4>
        <div class="customer-info">
            <div class="d-flex justify-content-between">
                <div class="party-details">
                    <p style="background: #afd7da;margin-bottom:8px !important;"> <strong> Name: </strong>   {{ $lpo->party->pi_name ? $lpo->party->pi_name : '...' }} </p>
                    <p style="color:#666f79 !important;margin-bottom:8px !important;"> <strong> Contact Person: </strong>   {{ $lpo->party->con_person ? $lpo->party->con_person : '...' }} </p>
                    <p style="color:#666f79 !important;margin-bottom:8px !important;"> <strong> Contact No: </strong>   {{ $lpo->party->con_no ? $lpo->party->con_no : '...' }} </p>
                    <p style="color:#666f79 !important;margin-bottom:8px !important;"> <strong> Email: </strong>   {{ $lpo->party->email ? $lpo->party->email : '...' }} </p>
                    <p style="color:#666f79 !important;margin-bottom:8px !important;"> <strong> TRN: </strong>   {{ $lpo->party->trn_no ? $lpo->party->trn_no : '...' }} </p>
                </div>

                <div class="text-right" style="padding: 5px 0px;">
                    <p style="color:#666f79 !important;8px !important;">
                        NO.: {{$lpo->lpo_bill_no}}<br>
                    </p>
                    <p style="color:#666f79 !important;8px !important;">
                        Date: {{date('d/m/Y')}} <br>
                    </p>
                     <p style="color:#666f79 !important;8px !important;">
                        Contact Person : {{$lpo->contact_person}} <br>
                    </p>
                    @php
                        $date = $lpo->delivary_date;
                        $timestamp = strtotime($date);
                    @endphp

                    <p style="color:#666f79 !important;8px !important;">
                        Delivery: {{ date('l', $timestamp) . ', ' . date('F', $timestamp) . ' ' . date('d', $timestamp) . ', ' . date('Y', $timestamp) }} <br>
                    </p>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- <div class="mt-1" style="margin-left:6px;">
     <p style="margin-bottom:6px !important;"> <strong> Project: </strong>{{ $lpo->project? $lpo->project->project_name:'' }} </p>
    <p style="margin-bottom:6px !important;"> <strong> Task: </strong> {{ $lpo->task? $lpo->task->task_name:'' }} </p>
    <p style="margin-bottom:6px !important;"> <strong> Sub-task : </strong> {{ $lpo->subTask? $lpo->subTask->item_description:'' }}  </p>
    <p style="margin-bottom:6px !important;"> <strong> Owner: </strong> {{optional($lpo->job_project)->party ? optional($lpo->job_project)->party->pi_name : ''}} </p>
    <p style="margin-bottom:6px !important;"> <strong> Location:  </strong> {{optional($lpo->job_project)->new_project ? optional($lpo->job_project)->location : ''}} </p>
</div> --}}




<p class="pt-1" style="margin-left:6px;">{{$lpo->lpo_for}} </p>

<div class="row" style="padding: 15px;">
    <div class="col-sm-12">
        <table class="table table-sm table-bordered lpo-table authority-info">
            <tbody class="user-table-body">
                <tr class="border-top">
                    <th  style="border: 1px solid #0867d2 !important;"> No </th>
                    <th  style="border: 1px solid #0867d2 !important;"> Description </th>
                    <th class="text-center"  style="border: 1px solid #0867d2 !important;"> Qty</th>
                    <th  style="border: 1px solid #0867d2 !important;text-align:center;"> Unit </th>
                    <th  style="border: 1px solid #0867d2 !important; text-align:center;"> Rate </th>
                    <th  style="border: 1px solid #0867d2 !important;width:150px; text-align:center;">Amount </th>
                    <th  style="border: 1px solid #0867d2 !important; text-align:center;"> VAT </th>
                    <th  style="border: 1px solid #0867d2 !important; text-align:center;"> Total </th>
                </tr>

                  @foreach ($items as $key => $item)
                    @php
                        $rate_whole = floor($item->rate);
                        $rate_fraction = number_format($item->rate - $rate_whole, 2);
                        $total_whole = floor($item->amount);
                        $total_fraction = number_format($item->amount - $total_whole, 2);
                    @endphp
                  <tr>
                    <td style="border: 1px solid #0867d2 !important;">{{$key+1}}</td>
                    <td style="border: 1px solid #0867d2 !important;">{{$item->item_description}}</td>
                    <td style="border: 1px solid #0867d2 !important; text-align:center;">{{floatval($item->qty)}}</td>
                    <td style="border: 1px solid #0867d2 !important; text-align:center;">{{optional($item->unit)->name}}</td>
                    <td style="border: 1px solid #0867d2 !important; text-align:center;">{{$item->rate}}</td>
                    <td style="border: 1px solid #0867d2 !important; text-align:center;">{{number_format($item->amount,2)}}</td>
                    <td style="border: 1px solid #0867d2 !important; text-align:center;">{{number_format($item->vat,2)}}</td>
                    <td style="border: 1px solid #0867d2 !important; text-align:center;">{{number_format($item->total_amount,2)}}</td>
                 </tr>

                  @endforeach
                  @php
                      $amount_whole = floor($lpo->amount);
                      $amount_fraction = number_format($lpo->amount - $amount_whole, 2);
                      $vat_whole = floor($lpo->vat);
                      $vat_fraction = number_format($lpo->vat - $vat_whole, 2);
                  @endphp
                  <tr>
                    <td style="border: 1px solid #0867d2 !important;  text-align:right !important" colspan="7" class="text-right pr-1">Total Amount</td>
                    <td style="border: 1px solid #0867d2 !important; text-align:center;">{{number_format($lpo->amount,2)}}</td>
                  </tr>
                  <tr>
                    <td style="border: 1px solid #0867d2 !important; text-align:right !important" colspan="7" class="text-right pr-1">VAT@5% </td>
                    <td style="border: 1px solid #0867d2 !important; text-align:center">{{number_format($lpo->vat,2)}}</td>
                  </tr>
                  <tr>
                    <td style="border: 1px solid #0867d2 !important;" colspan="7" class="text-center pr-1">
                        @php
                            $total_whole = floor($lpo->total_amount);
                            $total_fraction = number_format($lpo->total_amount - $total_whole, 2);
                            $f = new NumberFormatter('en', NumberFormatter::SPELLOUT);
                            $amount_in_word = $f->format($total_whole);
                            $amount_in_word2 = $f->format($total_fraction*100);
                        @endphp
                        <div class="d-flex w-100">
                            <div class="d-flex justify-content-between aligin-items-center mb-1 w-100">
                                <span style="width:150px !important; color:#0867d2;font-size:15px;font-weight:bold; line-height:23px !important;">
                                    Total Dhs
                                </span>
                                <div class="w-100" style="border-bottom:1px dashed #0867d2;">
                                    <p style="margin:0 !important;padding:0!important;color:#0867d2;font-size:15px;font-weight:500 !important; padding-left:30px;text-transform: uppercase">
                                        {{ $amount_in_word }} Dirhams
                                        @if ($total_fraction > 0)
                                        {{ '& ' . $amount_in_word2 }}
                                            @else
                                            & No
                                            @endif Fils
                                    </p>
                                </div>

                            </div>
                        </div>
                    </td>
                    {{-- <td style="border: 1px solid #0867d2 !important;" colspan="5" class="text-right pr-1">Total Dhs <span class="pl-2">المجموع درهم</span></td> --}}
                    <td style="border: 1px solid #0867d2 !important;">{{number_format($lpo->total_amount,2)}}</td>
                  </tr>
            </tbody>
        </table>
    </div>
    <div class="col-md-12">
        <div class="row d-flex justify-content-center mt-4">
            <div class="col-md-12">
                <table class="table table-sm table-bordered authority-info">
                    <tr>
                        <th></th>
                        <th>Checked By</th>
                        <th>Prepared By</th>
                        <th>Approved By</th>
                    </tr>
                    <tr>
                        <th>Name</th>
                        <td>{{$lpo->checked_by}}</td>
                        <td>{{$lpo->prepared_by}}</td>
                        <td>{{$lpo->approved_by}}</td>

                    </tr>
                    <tr>
                        <th>Signature</th>
                        <td></td>
                        <td></td>
                        <td></td>

                    </tr>
                    <tr>
                        <th>Date</th>
                        <td></td>
                        <td></td>
                        <td></td>

                    </tr>
                </table>

            </div>

            <div class="col-md-12">
                <h5 style="color:#0867d2;">Pay Terms:</h5>
                <p>{{$lpo->narration}}</p>
            </div>
        </div>
    </div>
</div>
@endsection
