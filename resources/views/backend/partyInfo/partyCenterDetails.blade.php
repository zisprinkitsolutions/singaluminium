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
       /* table tbody tr:hover {
        background-color: inherit ;
        } */
</style>

<div class="app-content content print-hideen">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-body">
            @include('clientReport.setup._header',['activeMenu' => 'cost_center'])
            <div class="tab-content bg-white p-2">
                <div class="tab-panel active">
                    <div style="width:100%;max-width:1400px">
                        {{-- @include('clientReport.setup._cost_center_submenu',['activeMenu' => 'party_info']) --}}
                        <section class="" style="max-width:1400px">
                            <div class="mt-2">
                                <div class="row">
                                <div class="col-md-4 mb-1">
                                    <form>
                                        <input type="text" name="search_value" class="form-control inputFieldHeight" style="height: 38px !important" placeholder="Search By Code, Party Name, TRN Number, Party Type" value="{{$search_value}}">
                                    </form>
                                </div>
                                    <div class="col-md-8 text-right pl-0">
                                        @if(Auth::user()->hasPermission('Setup_Create'))
                                        <button type="button" class="btn btn-xs btn-primary btn_create formButton" title="Add" data-toggle="modal" data-target="#partyInfo" style="padding-top: 6px;padding-bottom: 6px;">
                                            <div class="d-flex" style="width:66px;">
                                                <div class="formSaveIcon">
                                                    <img src="{{asset('icon/add-icon.png')}}" width="25">
                                                </div>
                                                <div><span>Add</span></div>
                                            </div>
                                        </button>
                                        @endif
                                        {{-- <a href="#" class="btn btn-xs mPrint formButton" id="partyCenterListPrint" title="Print"><img  src="{{asset('icon/print-icon.png')}}" alt="" srcset="" class="img-fluid" width="30"> Print</a> --}}
                                        <a href="#" class="btn btn-xs mExcelButton formButton" onclick="exportTableToCSV('PartyInfos.csv')" title="Export to Excel"><img  src="{{asset('icon/excel-icon.png')}}" alt="" srcset="" class="img-fluid" width="30">Excel</a href="#">
                                    </div>
                                </div>
                                <div class="cardStyleChange" style="height: 500px ; overflow-y:auto;">
                                    <table class="table mb-0 table-sm table-hover">
                                        <thead  class="thead-light" style="position: sticky; top:-2px; z-index:99;">
                                            <tr class="text-center" style="height: 40px;">

                                                <th style="width:7%; text-align:left;">Code</th>
                                                <th style="width: 35%; text-align:left;"> Name</th>
                                                <th class="text-left" style="width: 10%;">Type</th>
                                                <th style="width: 20%;text-align:left;">@if(!empty($currency->licence_name)){{$currency->licence_name}} @endif </th>
                                                <th style="width:20%; text-align:left;">Contact Person</th>
                                                <th style="width: 7%; text-align:right;">Action</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($partyInfos as $pInfo)
                                            <tr class="text-center trFontSize">
                                                <td class="partyCenterView text-left;" style="cursor: pointer; text-align:left;" id="{{$pInfo->id}}" >{{ $pInfo->pi_code }}</td>
                                                <td class="partyCenterView text-left" style="cursor: pointer;" id="{{$pInfo->id}}" >{{ $pInfo->pi_name }}</td>
                                                <td class="partyCenterView text-left" style="cursor: pointer;" id="{{$pInfo->id}}" >{{ $pInfo->pi_type }}</td>
                                                <td class="partyCenterView text-left" style="cursor: pointer;" id="{{$pInfo->id}}" >{{ $pInfo->trn_no }}</td>
                                                <td class="partyCenterView text-left" style="cursor: pointer;" id="{{$pInfo->id}}" >{{ $pInfo->con_person }}</td>
                                                <td class="text-right">
                                                   <div class="d-flex justify-content-end">
                                                        <a href="#" class="btn partyCenterView" style="height: 25px; width: 25px;padding: 0 2rem !important;" title="Preview" id="{{$pInfo->id}}"><img src="{{ asset('icon/view-icon.png')}}" style=" height: 25px; width: 25px;"></a>
                                                         @if(Auth::user()->hasPermission('Setup_Edit'))
                                                        <a href="{{ route('partyInfoEdit', $pInfo) }}" class="btn edit-btn" style="height: 25px; width: 25px;padding: 0rem !important;" title="Edit"><img src="{{ asset('icon/edit-icon.png')}}" style=" height: 25px; width: 25px;"></a>
                                                        @endif
                                                        @if (count($pInfo->effects())==0)
                                                        @if(Auth::user()->hasPermission('Setup_Delete'))
                                                        <a href="{{ route('partyInfoDelete', $pInfo)}}"
                                                            onclick="event.preventDefault(); deleteAlert(this, 'About to delete party info. Please, confirm?');"
                                                            class="btn" style="height: 25px; width: 25px;padding: 0 0.8rem !important;" title="Delete">
                                                            <img src="{{ asset('icon/delete-icon.png')}}" style=" height: 25px; width: 25px; margin-left: -12px;">
                                                        </a>
                                                        @endif
                                                        @endif

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


<div class="modal fade bd-example-modal-lg" id="partyLedgerDetailsModal" data-keyboard="false" data-backdrop="static"
     tabindex="-1" rrole="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
      <div class="modal-content">
        <div id="partyLedgerDetails">

        </div>
      </div>
    </div>
</div>



<div class="modal fade" id="partyInfo" tabindex="-1" data-keyboard="false" data-backdrop="static"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="padding: 5px 15px;background:#364a60;">
                <h5 class="modal-title" id="exampleModalLabel" style="font-family:Cambria;font-size: 2rem;color:white;">Party Details</h5>
                <div class="d-flex align-items-center">
                    <button type="button" class="project-btn bg-danger text-white" data-dismiss="modal" aria-label="Close" style="padding: 5px 10px;border:none; border-radius:5px" data-bs-toggle="tooltip" data-bs-placement="right" title="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    {{-- @include('alerts.alerts') --}}
                </div>
            </div>
            <div class="modal-body" style="padding: 15px 15px;">
                <section id="widgets-Statistics" class="mr-1 ml-1 mb-1">
                    <div class="row">
                        <div class="col-12 party-info-form">
                            @isset($partyInfo)
                                <form action="{{ route('partyInfoUpdate', $partyInfo) }}" method="POST" onreset="select2_change()">
                            @else
                                <form action="{{ route('partyInfoPost') }}" method="POST" onreset="select2_change()">
                            @endisset
                                @csrf
                                <div class="cardStyleChange pt-1">
                                    <div class="form-row text-left">

                                        <!-- Row 1 -->
                                        <div class="col-md-4 mb-2">
                                            <label><strong>Party Code :</strong></label>
                                            <input type="text" class="form-control form-control" value="{{ isset($cc)? $cc: (isset($partyInfo)?$partyInfo->pi_code:'') }}" disabled readonly>
                                        </div>
                                        <div class="col-md-4 mb-2">
                                            <label><strong>Party Info Name :</strong></label>
                                            <input type="text" name="pi_name" class="form-control form-control" value="{{ isset($partyInfo) ? $partyInfo->pi_name : '' }}" placeholder="Party Info Name" required>
                                            @error('pi_name')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-4 mb-2">
                                            <label><strong>Party Type :</strong></label>
                                            <select name="pi_type" class="form-control form-control" required>
                                                <option value="">Select...</option>
                                                @foreach ($costTypes as $item)
                                                    <option value="{{ $item->title }}" {{ isset($partyInfo) ? ($partyInfo->pi_type == $item->title ? 'selected' : '') : '' }}>
                                                        {{ $item->title }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('pi_type')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Row 2 -->
                                        <div class="col-md-4 mb-2">
                                            <label><strong>@if(!empty($currency->licence_name)) {{$currency->licence_name}} @endif No :</strong></label>
                                            <input type="number" name="trn_no" class="form-control form-control" value="{{ isset($partyInfo) ? $partyInfo->trn_no : '' }}" placeholder="TRN Number">
                                            @error('trn_no')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-4 mb-2">
                                            <label><strong>Address :</strong></label>
                                            <input type="text" name="address" class="form-control form-control" value="{{ isset($partyInfo) ? $partyInfo->address : '' }}" placeholder="Address">
                                            @error('address')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-4 mb-2">
                                            <label><strong>Contact Person :</strong></label>
                                            <input type="text" name="con_person" class="form-control form-control" value="{{ isset($partyInfo) ? $partyInfo->con_person : '' }}" placeholder="Contact Person">
                                            @error('con_person')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Row 3 -->
                                        <div class="col-md-4 mb-2">
                                            <label><strong>Mobile Phone No :</strong></label>
                                            <input type="number" name="con_no" class="form-control form-control" value="{{ isset($partyInfo) ? $partyInfo->con_no : '' }}" placeholder="Mobile No">
                                            @error('con_no')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-4 mb-2">
                                            <label><strong>Phone No :</strong></label>
                                            <input type="number" name="phone_no" class="form-control form-control" value="{{ isset($partyInfo) ? $partyInfo->phone_no : '' }}" placeholder="Phone No">
                                            @error('phone_no')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-4 mb-2">
                                            <label><strong>Email :</strong></label>
                                            <input type="text" name="email" class="form-control form-control" value="{{ isset($partyInfo) ? $partyInfo->email : '' }}" placeholder="Email">
                                            @error('email')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Buttons -->
                                        {{-- <div class="col-12 d-flex justify-content-end mt-2">
                                            <button type="submit" class="btn btn-primary btn-sm mr-2" title="Form Save">
                                                <i class="bx bx-save"></i> Save
                                            </button>
                                            <button type="reset" class="btn btn-secondary btn-sm" title="Form Reset" onclick="select2_change()">
                                                <i class="bx bx-refresh"></i> Reset
                                            </button>
                                        </div> --}}
                                        <div class="col-12 d-flex justify-content-center ">
                                            @isset($partyInfo)
                                            {{-- <button class="btn btn-info party-info-form-btn formButton" data_target="{{ route('partyInfoForm') }}" id="profitCenterButton"><img src="{{ asset('icon/add-icon.png')}}" alt="" srcset="" class="image-fluid" width="25">New</button> --}}
                                            @endisset
                                            <button type="submit" class="btn btn-primary formButton" style="margin-right: 5px;" title="Form Save">
                                                <div class="d-flex">
                                                    <div class="formSaveIcon">
                                                        <img  src="{{asset('icon/save-icon.png')}}" alt="" srcset="" class="img-fluid" width="25">
                                                    </div>
                                                    <div><span> Save</span></div>
                                                </div>
                                            </button>
                                            <button type="reset" class="btn btn-light-secondary formButton" title="Form Reset" onclick="select2_change()">
                                                <div class="d-flex">
                                                    <div class="formRefreshIcon">
                                                        <img  src="{{asset('icon/refresh-icon.png')}}" alt="" srcset="" class="img-fluid" width="25">
                                                    </div>
                                                    <div><span> Reset</span></div>
                                                </div>
                                            </button>
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

<div class="modal fade bd-example-modal-lg" id="partyCenterPreviewModal" data-keyboard="false" data-backdrop="static"
    tabindex="-1" rrole="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered {{--modal-lg--}} modal-xl" role="document">
      <div class="modal-content">
        <div id="partyCenterView">

        </div>
      </div>
    </div>
</div>

<div class="modal fade bd-example-modal-lg" id="partyCenterPrintModal" data-keyboard="false" data-backdrop="static"
 tabindex="-1" rrole="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
      <div class="modal-content">
        <div id="partyCenterPrint">

        </div>
      </div>
    </div>
</div>

<div class="modal fade bd-example-modal-lg" id="partyCenterListPrintModal"
    data-keyboard="false" data-backdrop="static"
    tabindex="-1" rrole="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
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

        $(document).on('click', '.edit-btn', function(e){
            e.preventDefault();
            var url = $(this).prop('href');

            $.ajax({
                url:url,
                type:'get',
                success:function(res){
                    $('#partyCenterPreviewModal').modal('show');
                    $('#partyCenterView').html(res);
                }
            })
        })
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
