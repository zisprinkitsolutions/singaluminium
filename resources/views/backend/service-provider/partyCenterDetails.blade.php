@extends('layouts.backend.app')
@push('css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.0/css/toastr.css" rel="stylesheet" />
@endpush
@section('content')
@include('backend.tab-file.style')
<style>
        tr:nth-child(even) {
        background-color: #c8d6e357;
    }
    a.text-dark:hover, a.text-dark:focus {
        color: #ffffff !important;
    }
    .btn-outline-secondary {
        border-radius: 40px;
        padding: 0.2px 9px 0.2px 9px !important;
    }

    .table .thead-light th {
        color:#F2F4F4 ;
        background-color: #34465b;
        border-color: #DFE3E7;
    }
</style>
<div class="app-content content print-hideen">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-body">
            @include('clientReport.setup._header',['activeMenu' => 'cost_center'])
            <div class="tab-content bg-white p-2">
                <div class="tab-panel active">
                    <div>
                        @include('clientReport.setup._cost_center_submenu',['activeMenu' => 'service_provider'])
                        <section class="mt-3">
                            <div class="mt-2">
                                <div class="row">
                                    <div class="col-md-6 mb-1">
                                    <form>
                                    <input type="text" name="search_value" class="form-control inputFieldHeight" placeholder="Search By Code, Party Name, TRN Number">
                                    </form>
                                </div>
                                    <div class="col-md-6 text-right">
                                        <button type="button" class="btn btn-xs btn-primary btn_create formButton mr-1" title="Add" data-toggle="modal" data-target="#partyInfo" style="padding-top: 6px;padding-bottom: 6px;">
                                            <div class="d-flex">
                                                <div class="formSaveIcon">
                                                    <img src="{{asset('/icon/add-icon.png')}}" width="25">
                                                </div>
                                                <div><span>Add</span></div>
                                            </div>
                                        </button>
                                        <a href="#" class="btn btn-xs mPrint formButton" id="partyCenterListPrint" title="Print"><img  src="{{asset('/icon/print-icon.png')}}" alt="" srcset="" class="img-fluid" width="30"> Print</a>
                                        <a href="#" class="btn btn-xs mExcelButton formButton" onclick="exportTableToCSV('PartyInfos.csv')" title="Export to Excel"><img  src="{{asset('/icon/excel-icon.png')}}" alt="" srcset="" class="img-fluid" width="30">Excel</a href="#">
                                    </div>
                                </div>
                                <div class="cardStyleChange">
                                    <table class="table mb-0 table-sm table-hover">
                                        <thead  class="thead-light">
                                            <tr style="height: 40px;">
                                                <th style="padding-left: 18px;">Party Code</th>
                                                <th>Party Name</th>
                                                <th>Type</th>
                                                <th>@if(!empty($currency->licence_name)){{$currency->licence_name}} @endif Number</th>
                                                <th>Contact Person</th>
                                                <th style="text-align:center;width:10%;">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($partyInfos as $pInfo)
                                            <tr class="trFontSize">
                                                <td style="padding-left: 18px;" class=" part-ledger" style="cursor: pointer;" id="{{$pInfo->id}}" >{{ $pInfo->pi_code }}</td>
                                                <td class=" part-ledger" style="cursor: pointer;" id="{{$pInfo->id}}" >{{ $pInfo->pi_name }}</td>
                                                <td class=" part-ledger" style="cursor: pointer;" id="{{$pInfo->id}}" >{{ $pInfo->pi_type }}</td>
                                                <td class=" part-ledger" style="cursor: pointer;" id="{{$pInfo->id}}" >{{ $pInfo->trn_no }}</td>
                                                <td class=" part-ledger" style="cursor: pointer;" id="{{$pInfo->id}}" >{{ $pInfo->con_person }}</td>
                                                <td style="padding-bottom: 11px; padding-top: 0px">
                                                   <div class="d-flex justify-content-end">
                                                    <a href="#" class="btn partyCenterView" style="height: 25px; width: 25px;" title="Preview" id="{{$pInfo->id}}"><img src="{{ asset('/icon/view-icon.png')}}" style=" height: 25px; width: 25px;"></a>
                                                    <a href="{{ route('partyInfoEdit', $pInfo) }}" class="btn" style="height: 25px; width: 25px;" title="Edit"><img src="{{ asset('/icon/edit-icon.png')}}" style=" height: 25px; width: 25px;"></a>
                                                    <a href="{{ route('partyInfoDelete', $pInfo) }}" onclick="return confirm('about to delete party info. Please, Confirm?')"  class="btn" style="height: 25px; width: 25px;" title="Delete"><img src="{{ asset('/icon/delete-icon.png')}}" style=" height: 25px; width: 25px; margin-left: -12px;"></a>
                                                   </div>

                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 text-right">
                                {{ $partyInfos->links() }}
                                </div>
                            </div>
                        </section>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>


<div class="modal fade bd-example-modal-lg" id="partyLedgerDetailsModal" tabindex="-1" rrole="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
      <div class="modal-content">
        <div id="partyLedgerDetails">

        </div>
      </div>
    </div>
</div>



<div class="modal fade" id="partyInfo" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
            <section id="widgets-Statistics" class="mr-1 ml-1 mb-1">
                        <div class="row">
                            <div class="col-md-6  mt-2 mb-2">
                                <h4>Party Details</h4>
                            </div>
                            <div class="col-md-6  mt-2 mb-2" style="text-align: right;">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal" aria-label="Close">Close</button>
                            </div>
                                {{-- @include('alerts.alerts') --}}
                        </div>
                        <div class="row" style="padding-left: 10px; padding-right: 10px">
                            <div class="col-12 party-info-form">
                                @isset($partyInfo)
                                    <form action="{{ route('partyInfoUpdate', $partyInfo) }}" method="POST">
                                    @else
                                    <form action="{{ route('partyInfoPost') }}" method="POST">
                                        @endisset
                                        @csrf
                                    <div class="cardStyleChange">
                                        <div class="row">
                                            <div class="col-md-2 changeColStyle">
                                                <label>Party Code</label>
                                                <input type="text" id="" class="form-control inputFieldHeight" name="" value="{{isset($cc)? $cc: (isset($partyInfo)?$partyInfo->pi_code:'') }}" placeholder="Party Info Code" disabled readonly>
                                            </div>

                                            <div class="col-md-3 changeColStyle">
                                                <label>Party Info Name</label>
                                                <input type="text" id="pi_name" class="form-control inputFieldHeight" name="pi_name" value="{{ isset($partyInfo) ? $partyInfo->pi_name : '' }}" placeholder="Party Info Name" required>
                                                    @error('pi_name')
                                                <div class="btn btn-sm btn-danger">{{ $message }}</div>
                                                    @enderror
                                            </div>

                                            <div class="col-md-2 changeColStyle">
                                                <label>Party Type</label>
                                                <select name="pi_type" class="common-select2" style="width: 100% !important" id="" required>
                                                <option value="">Select...</option>
                                                    @foreach ($costTypes as $item)
                                                    <option value="{{ $item->title }}" {{ $item->title == 'Supplier' ? 'selected' : '' }}> {{ $item->title }}</option>
                                                    @endforeach
                                                </select>
                                                    @error('pi_type')
                                                <div class="btn btn-sm btn-danger">{{ $message }}</div>
                                                    @enderror
                                            </div>

                                            <div class="col-md-2 changeColStyle">
                                                <label>TRN No</label>
                                                <input type="text" id="trn_no" class="form-control inputFieldHeight" name="trn_no" value="{{ isset($partyInfo) ? $partyInfo->trn_no : '' }}" placeholder="TRN Number" >
                                                    @error('trn_no')
                                                <div class="btn btn-sm btn-danger">{{ $message }}</div>
                                                    @enderror
                                            </div>

                                            <div class="col-md-3 changeColStyle">
                                                <label>Address</label>
                                                <input type="text" id="address" class="form-control inputFieldHeight" name="address" value="{{ isset($partyInfo) ? $partyInfo->address : '' }}" placeholder="Address">
                                                    @error('address')
                                                <div class="btn btn-sm btn-danger">{{ $message }}</div>
                                                    @enderror
                                            </div>

                                            <div class="col-md-2 changeColStyle">
                                                <label>Contact Person</label>
                                                <input type="text" id="con_person" class="form-control inputFieldHeight" name="con_person" value="{{ isset($partyInfo) ? $partyInfo->con_person : '' }}" placeholder="Contact Person">
                                                    @error('con_person')
                                                <div class="btn btn-sm btn-danger">{{ $message }}</div>
                                                    @enderror
                                            </div>

                                            <div class="col-md-2 changeColStyle">
                                                <label>Mobile Phone No</label>
                                                <input type="number" id="con_no" class="form-control inputFieldHeight" name="con_no" value="{{ isset($partyInfo) ? $partyInfo->con_no : '' }}" placeholder="Mobile No">
                                                    @error('con_no')
                                                <div class="btn btn-sm btn-danger">{{ $message }}</div>
                                                    @enderror
                                            </div>

                                            <div class="col-md-2 changeColStyle">
                                                <label>Phone No</label>
                                                <input type="number" id="phone_no" class="form-control inputFieldHeight" name="phone_no" value="{{ isset($partyInfo) ? $partyInfo->phone_no : '' }}" placeholder="Phone No">
                                                    @error('phone_no')
                                                <div class="btn btn-sm btn-danger">{{ $message }}</div>
                                                    @enderror
                                            </div>

                                            <div class="col-md-3 changeColStyle">
                                                <label>Email</label>
                                                <input type="text" id="email" class="form-control inputFieldHeight" name="email" value="{{ isset($partyInfo) ? $partyInfo->email : '' }}" placeholder="Email">
                                                    @error('email')
                                                <div class="btn btn-sm btn-danger">{{ $message }}</div>
                                                    @enderror
                                            </div>

                                                <div class="col-md-3 d-flex justify-content-end changeColStyle">
                                                    <div class="form-group" style="padding-top: 20px;">
                                                        @isset($partyInfo)
                                                        <button class="btn btn-info party-info-form-btn mr-1 formButton" data_target="{{ route('partyInfoForm') }}" id="profitCenterButton"><img src="{{ asset('/icon/add-icon.png')}}" alt="" srcset="" class="image-fluid" width="25">New</button>
                                                        @endisset
                                                        <button type="submit" class="btn mr-1 btn-primary formButton" title="Form Save">
                                                            <div class="d-flex">
                                                                <div class="formSaveIcon">
                                                                    <img  src="{{asset('/icon/save-icon.png')}}" alt="" srcset="" class="img-fluid" width="25">
                                                                </div>
                                                                <div><span> Save</span></div>
                                                            </div>
                                                        </button>
                                                        <button type="reset" class="btn btn-light-secondary formButton" title="Form Reset" disabled>
                                                            <div class="d-flex">
                                                                <div class="formRefreshIcon">
                                                                    <img  src="{{asset('/icon/refresh-icon.png')}}" alt="" srcset="" class="img-fluid" width="25">
                                                                </div>
                                                                <div><span> Reset</span></div>
                                                            </div>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </section>
            </div>
        </div>
    </div>
</div>
<div class="modal fade bd-example-modal-lg" id="partyCenterPreviewModal" tabindex="-1" rrole="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
      <div class="modal-content">
        <div id="partyCenterView">

        </div>
      </div>
    </div>
</div>
<div class="modal fade bd-example-modal-lg" id="partyCenterPrintModal" tabindex="-1" rrole="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
      <div class="modal-content">
        <div id="partyCenterPrint">

        </div>
      </div>
    </div>
</div>
<div class="modal fade bd-example-modal-lg" id="partyCenterListPrintModal" tabindex="-1" rrole="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
      <div class="modal-content">
        <div id="partyCenterListPrintContent">

        </div>
      </div>
    </div>
</div>
@endsection

@push('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.0/js/toastr.js"></script>
    {{-- <script src="{{ asset('assets/backend/app-assets/vendors/js/jquery/jquery.min.js') }}"></script> --}}
    <script>

         if( $('#pi_name').val()!='')
        {
            $('#partyInfo').modal('show')

        }
        function printFuntion(){
            window.print();
        }
        $(document).on("click", "#partyCenterListPrint", function(e) {
            e.preventDefault();
            $.ajax({
                url: "{{URL('party-center-list-print')}}",
                type: "post",
                cache: false,
                data:{
                    _token:'{{ csrf_token() }}',
                },
                success: function(response){
                    document.getElementById("partyCenterListPrintContent").innerHTML = response;
                    $('#partyCenterListPrintModal').modal('show');
                    setTimeout(printFuntion, 500);
                }
            });
        });


        $(document).on("click", ".part-ledger", function(e) {

            e.preventDefault();
            // console.log('Alhamdulillah');
            var id= $(this).attr('id');
            $.ajax({
                url: "{{route('party-ledger-modal')}}",
                type: "post",
                cache: false,
                data:{
                    _token:'{{ csrf_token() }}',
                    id:id,
                },
                success: function(response){
                    // console.log(response);
                    document.getElementById("partyLedgerDetails").innerHTML = response;
                    $('#partyLedgerDetailsModal').modal('show')
                }
            });
        });


        $(document).on("click", ".partyCenterPrint", function(e) {
            e.preventDefault();
            var id= $(this).attr('id');
            $.ajax({
                url: "{{URL('party-center-print')}}",
                type: "post",
                cache: false,
                data:{
                    _token:'{{ csrf_token() }}',
                    id:id,
                },
                success: function(response){
                    document.getElementById("partyCenterPrint").innerHTML = response;
                    $('#partyCenterPrintModal').modal('show');
                    setTimeout(printFuntion, 500);
                }
            });
        });
        $(document).on("click", ".partyCenterView", function(e) {
            e.preventDefault();
            var id= $(this).attr('id');
            $.ajax({
                url: "{{URL('party-center-preview')}}",
                type: "post",
                cache: false,
                data:{
                    _token:'{{ csrf_token() }}',
                    id:id,
                },
                success: function(response){
                    document.getElementById("partyCenterView").innerHTML = response;
                    $('#partyCenterPreviewModal').modal('show')
                }
            });
        });
    </script>


    <script>
        $(document).ready(function() {

            var delay = (function() {
                var timer = 0;
                return function(callback, ms) {
                    clearTimeout(timer);
                    timer = setTimeout(callback, ms);
                };
            })();
            $(document).on("click", ".party-info-form-btn", function(e) {
                e.preventDefault();
                var that = $(this);
                var urls = that.attr("data_target");
                delay(function() {
                    $.ajax({
                        url: urls,
                        type: 'GET',
                        cache: false,
                        dataType: 'json',
                        success: function(response) {
                            //   alert('ok');
                            console.log(response);
                            $(".party-info-form").empty().append(response.page);
                        },
                        error: function() {
                            //   alert('no');
                        }
                    });
                }, 999);
            });



        });
    </script>
@endpush
