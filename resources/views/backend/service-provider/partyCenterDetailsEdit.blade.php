@extends('layouts.backend.app')
@push('css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.0/css/toastr.css" rel="stylesheet" />
    <style>
    .close {
        font-size: 45px;
        font-weight: 600;
      }
    </style>

@endpush
@section('content')
@include('backend.tab-file.style')
<div class="app-content content print-hideen">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-body">

            <div class="tab-content bg-white">
                <div>
                    <section id="widgets-Statistics" class="mr-1 ml-1 mb-1">
                        <div class="row">
                            <div class="col-md-6  mt-2 mb-2">
                                <h4>Service Provider Update</h4>
                            </div>
                                {{-- @include('alerts.alerts') --}}
                        </div>
                        <div class="row"  style="padding-left: 10px; padding-right: 10px">
                            <div class="col-12">

                                @isset($partyInfo)
                                <form action="{{ route('partyInfoUpdate', $partyInfo) }}" method="POST" enctype="multipart/form-data">
                                    @else
                                <form action="{{ route('partyInfoPost') }}" method="POST" enctype="multipart/form-data">
                                    @endisset
                                    @csrf
                                    <div class="cardStyleChange">
                                        <div class="row">
                                            <div class="col-md-3 changeColStyle">
                                                <label>Party Code</label>
                                                <input type="text" id="" class="form-control inputFieldHeight" name="" value="{{ $partyInfo->pi_code }}" placeholder="Party Info Code" disabled readonly>
                                            </div>

                                            <div class="col-md-3 changeColStyle">
                                                <label>Party Info Name</label>
                                                <input type="text" id="pi_name" class="form-control inputFieldHeight" name="pi_name" value="{{ isset($partyInfo) ? $partyInfo->pi_name : '' }}" placeholder="Party Info Name" required>
                                                    @error('pi_name')
                                                <div class="btn btn-sm btn-danger">{{ $message }}</div>
                                                    @enderror
                                            </div>

                                            <div class="col-md-3 changeColStyle">
                                                <label>Party Type</label>
                                                <select name="pi_type" class="common-select2" style="width: 100% !important" id="" required>
                                                <option value="">Select...</option>
                                                    @foreach ($costTypes as $item)
                                                <option value="{{ $item->title }}"{{ isset($partyInfo) ? ($partyInfo->pi_type == $item->title ? 'selected' : '') : '' }}> {{ $item->title }}</option>
                                                    @endforeach
                                                </select>
                                                    @error('pi_type')
                                                <div class="btn btn-sm btn-danger">{{ $message }}</div>
                                                    @enderror
                                            </div>

                                            <div class="col-md-3 changeColStyle">
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

                                            <div class="col-md-3 changeColStyle">
                                                <label>Contact Person</label>
                                                <input type="text" id="con_person" class="form-control inputFieldHeight" name="con_person" value="{{ isset($partyInfo) ? $partyInfo->con_person : '' }}" placeholder="Contact Person">
                                                    @error('con_person')
                                                <div class="btn btn-sm btn-danger">{{ $message }}</div>
                                                    @enderror
                                            </div>

                                            <div class="col-md-3 changeColStyle">
                                                <label>Mobile Phone No</label>
                                                <input type="number" id="con_no" class="form-control inputFieldHeight" name="con_no" value="{{ isset($partyInfo) ? $partyInfo->con_no : '' }}" placeholder="Mobile No">
                                                    @error('con_no')
                                                <div class="btn btn-sm btn-danger">{{ $message }}</div>
                                                    @enderror
                                            </div>

                                            <div class="col-md-3 changeColStyle">
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
                                            <div class="col-md-3 changeColStyle">
                                                <label>Contract Document</label>
                                                <input type="file" class="form-control inputFieldHeight" name="document1">
                                                @error('document1')
                                                <div class="btn btn-sm btn-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-3 changeColStyle">
                                                <label>Other Document</label>
                                                <input type="file" class="form-control inputFieldHeight" name="files[]" multiple >
                                                @error('file')
                                                <div class="btn btn-sm btn-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                            <div class="row data d-flex justify-content-end">
                                                @if(count($others) != 0)
                                                    @foreach($others as $others)
                                                        <div class="col-md-1 img" style="height: 60px; width: 60px;">
                                                            {{-- <a href=""   class="close delete-img"></a> --}}
                                                    {{-- <span data_target="{{ route('othersDelete', $others->id) }}" class="close delete-img" >&times;</span> --}}
                                                            <span class="btn btn-warning invoice-item-delete" id="" data_target="{{ route('othersDelete',$others) }}"><i class="bx bx-trash"></i></span>

                                                                @if ($others->extension == 'pdf')
                                                                    <a href="{{ asset('storage/upload/service-provider/'.$others->filename)}}" target="_blank">

                                                                        <img src="{{ asset('/icon/pdf-download-icon-2.png')}}" alt="jugyjugyt" style="height: 100%; width: 100%;">
                                                                    </a>
                                                                @else
                                                                    <a href="{{ asset('storage/upload/service-provider/'.$others->filename)}}" target="_blank">
                                                                        <img src="{{ asset('storage/upload/service-provider/'.$others->filename)}}" alt="" style="height: 100%; width: 100%;">
                                                                    </a>
                                                                @endif
                                                        </div>
                                                    @endforeach
                                                @endif
                                            </div>
                                            <div class="row">
                                            <div class="col-md-3 d-flex justify-content-end changeColStyle">
                                                @isset($partyInfo)
                                                <a href="{{ route('service-provider.index') }}" class="btn btn-info mr-1 formButton mt-2"><img src="{{ asset('/icon/add-icon.png')}}" alt="" srcset="" class="image-fluid" width="25"> New</a>
                                                @endisset
                                                <button type="submit" class="btn mr-1 btn-primary formButton mt-2" title="Form Save">
                                                    <div class="d-flex">
                                                        <div class="formSaveIcon">
                                                            <img  src="{{asset('/icon/save-icon.png')}}" alt="" srcset="" class="img-fluid" width="25">
                                                        </div>
                                                        <div><span> Update</span></div>
                                                    </div>
                                                </button>
                                            </div>

                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </section>
                    <hr>

                    <section class="mr-1 ml-1 mt-3">
                        <div class="mt-2">
                            <div class="row">
                                <div class="col-md-6 mb-1">
                                <form>
                                <input type="text" name="search_value" class="form-control inputFieldHeight" placeholder="Search By Code, Party Name, TRN Number">
                                </form>
                            </div>
                                <div class="col-md-6 text-right">
                                    <a href="#" class="btn btn-xs mPrint formButton" id="partyCenterListPrint" title="Print"><img  src="{{asset('/icon/print-icon.png')}}" alt="" srcset="" class="img-fluid" width="30"> Print</a>
                                    <a href="#" class="btn btn-xs mExcelButton formButton" onclick="exportTableToCSV('PartyInfos.csv')" title="Export to Excel"><img  src="{{asset('/icon/excel-icon.png')}}" alt="" srcset="" class="img-fluid" width="30">Export To Excel</a href="#">
                                </div>
                            </div>
                            <div class="cardStyleChange">
                                <table class="table mb-0 table-sm table-hover">
                                    <thead  class="thead-light">
                                        <tr style="height: 50px;">
                                            <th>Party Code</th>
                                            <th>Party Name</th>
                                            <th>Type</th>
                                            <th>TRN Number</th>
                                            <th>Contact Person</th>
                                            {{-- <th>Contact Number</th> --}}
                                            {{-- <th>Phone Number</th>
                                            <th>Address</th>
                                            <th>Email</th> --}}
                                            <th style="text-align:center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($partyInfos as $pInfo)
                                        <tr class="trFontSize">
                                            <td>{{ $pInfo->pi_code }}</td>
                                            <td>{{ $pInfo->pi_name }}</td>
                                            <td>{{ $pInfo->pi_type }}</td>
                                            <td>{{ $pInfo->trn_no }}</td>
                                            <td>{{ $pInfo->con_person }}</td>
                                            {{-- <td>{{ $pInfo->con_no }}</td> --}}
                                            {{-- <td>{{ $pInfo->phone_no }}</td>
                                            <td>{{ $pInfo->address }}</td>
                                            <td>{{ $pInfo->email }}</td> --}}

                                            <td style="padding-bottom: 11px; padding-top: 0px">
                                               <div class="d-flex justify-content-end">
                                                <a href="#" class="btn partyCenterView" style="height: 30px; width: 30px;" title="Preview" id="{{$pInfo->id}}"><img src="{{ asset('/icon/view-icon.png')}}" style=" height: 30px; width: 30px;"></a>
                                                <a href="{{ route('service-provider.edit', $pInfo->id) }}" class="btn" style="height: 30px; width: 30px;" title="Edit"><img src="{{ asset('/icon/edit-icon.png')}}" style=" height: 30px; width: 30px;"></a>
                                                <a href="{{ route('partyInfoDelete', $pInfo) }}" onclick="return confirm('about to delete service provider. Please, Confirm?')"  class="btn" style="height: 30px; width: 30px;" title="Delete"><img src="{{ asset('/icon/delete-icon.png')}}" style=" height: 30px; width: 30px; margin-left: -12px;"></a>
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
    <script>
        function printFuntion(){
            window.print();
        }
        $(document).on("click", "#partyCenterListPrint", function(e) {
            e.preventDefault();
            $.ajax({
                url: "{{URL('service-provider-list-print')}}",
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
        //Delete others
        $(document).on("click", '.invoice-item-delete', function(event) {
                event.preventDefault();
                // alert(1);
                var that = $(this);
                var urls = that.attr("data_target");
                var _token = $('input[name="_token"]').val();
                // alert(invoice_no);
                $.ajax({
                    url: urls,
                    method: "GET",
                    _token: _token,

                    success: function(response) {
                        // alert("hukka");
                        console.log(response);
                        $(".data").empty().append(response.page);

                    },
                    error: function() {
                        //   alert('no');
                    }
                });

            });
        // $(document).on("click", ".close", function(e) {
        //     e.preventDefault();
        //     alert(0);
        //     var urls = that.attr("data_target");

        //     var _token = $('input[name="_token"]').val();
        //     $.ajax({
        //         url: urls,
        //         method: "GET",
        //         data: {
        //             emp: emp,
        //             _token: _token,
        //         },
        //         success: function(response) {
        //             // console.log(response);
        //             if(response.check_unique == 'yes'){
        //                 toastr.warning("This emplyee slaray structure already created","Warning");
        //                 $("#emp_name").val('').change();
        //                 $("div.emp-select select").val('').change();
        //                 $("#wages_type").val('');
        //             }else{
        //                 $("div.emp-select select").val(response.page.id).change();
        //                 $("#wages_type").val(response.wages);
        //             }
        //         }
        //     });
        // });

        $(document).on("click", ".partyCenterPrint", function(e) {
            e.preventDefault();
            var id= $(this).attr('id');
            $.ajax({
                url: "{{URL('service-provider-print')}}",
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
                url: "{{URL('service-provider-preview')}}",
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
                // alert(urls);
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
