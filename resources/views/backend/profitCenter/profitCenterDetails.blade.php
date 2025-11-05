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
            <div class="tab-content bg-white p-2 active">
                <div class="tab-pane active">
                    <div>
                        @include('clientReport.setup._cost_center_submenu',['activeMenu' => 'profit_center'])
                        <section class="mt-3">
                            <div class="mt-2">
                                <div class="row mb-1">
                                    <div class="col-md-6">
                                        <form>
                                        <input type="text" name="search_value" class="form-control inputFieldHeight" placeholder="Search By Code, Name">
                                        </form>
                                    </div>
                                    <div class="col-md-6 text-right">
                                        <button type="button" class="btn btn-xs btn-primary btn_create formButton" title="Add" data-toggle="modal" data-target="#profitCenter" style="padding-top: 6px;padding-bottom: 6px;">
                                            <div class="d-flex">
                                                <div class="formSaveIcon">
                                                    <img src="{{asset('/icon/add-icon.png')}}" width="25">
                                                </div>
                                                <div><span>Add</span></div>
                                            </div>
                                        </button>
                                        {{-- <a href="#" class="btn btn-xs mPrint formButton" id="listPrint" title="Print"><img  src="{{asset('/icon/print-icon.png')}}" alt="" srcset="" class="img-fluid" width="30"> Print</a> --}}
                                        <a href="#" class="btn btn-xs mExcelButton formButton" onclick="exportTableToCSV('profitcenterdetails.csv')" title="Export to Excel"><img  src="{{asset('/icon/excel-icon.png')}}" alt="" srcset="" class="img-fluid" width="30">Excel</a href="#">
                                    </div>
                                </div>
                                <div class="cardStyleChange">
                                    <table class="table mb-0 table-sm table-hover">
                                        <thead  class="thead-light">
                                            <tr class="text-center" style="height: 40px;">
                                                <th>Profit Center Code</th>
                                                <th>Profit Center Name</th>
                                                <th>Activity</th>
                                                <th>Person Resposible</th>
                                                <th style="padding-left: 20px;">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($profitDetails as $pCenter)
                                            <tr class="text-center trFontSize" style="height: 40px;">
                                                <td>{{ $pCenter->pc_code }}</td>
                                                <td>{{ $pCenter->pc_name }}</td>
                                                <td>{{ $pCenter->activity }}</td>
                                                <td>{{ $pCenter->prsn_responsible }}</td>

                                                <td>
                                                    <div style="margin-top: -12px;">
                                                        <a href="{{ route('profitCenEdit', $pCenter) }}" class="btn" style="height: 30px; width: 30px;" title="Edit"><img src="{{ asset('/icon/edit-icon.png')}}" style=" height: 25px; width: 25px;"></a>
                                                        <a href="{{ route('profitCenDelete', $pCenter) }}" onclick="return confirm('about to delete profit center. Please, Confirm?')"  class="btn" style="height: 25px; width: 25px;padding: 0.467rem 0.8rem;" title="Delete"><img src="{{ asset('/icon/delete-icon.png')}}" style=" height: 25px; width: 25px; margin-left: -12px;"></a>
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
                                    {{ $profitDetails->links() }}
                                </div>
                            </div>
                        </section>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="profitCenter" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="padding: 5px 15px;background:#364a60;">
                <h5 class="modal-title" id="exampleModalLabel" style="font-family:Cambria;font-size: 2rem;color:white;">Profit Center Details</h5>
                <div class="d-flex align-items-center">
                    <button type="button" class="project-btn bg-danger text-white" data-dismiss="modal" aria-label="Close" style="padding: 3px 12px;" data-bs-toggle="tooltip" data-bs-placement="right" title="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    {{-- @include('alerts.alerts') --}}
                </div>
            </div>
            <div class="modal-body" style="padding: 5px 5px;">
                <section id="widgets-Statistics" class="mr-1 ml-1 mb-1">
                    <div class="row">
                        <div class="col-12 profit-center-form">

                                @isset($profitCenter)
                                    <form action="{{ route('profitCentersUpdate', $profitCenter) }}" method="POST">
                                @else
                                    <form action="{{ route('profitCenterPost') }}" method="POST">
                                    @endisset
                                    @csrf
                                <div class="cardStyleChange">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label>Code</label>
                                            <input type="text" id="" class="form-control inputFieldHeight" name="" value="{{isset($p_code)? $p_code:$profitCenter->pc_code}}" disabled placeholder="Profit Center Code">
                                        </div>

                                        <div class="col-md-4">
                                            <label>Profit Center Name</label>
                                            <input type="text" id="pc_name" class="form-control inputFieldHeight" name="pc_name" value="{{ isset($profitCenter) ? $profitCenter->pc_name : '' }}" placeholder="Profit Center Name"  required>
                                                @error('pc_name')
                                            <div class="btn btn-sm btn-danger">{{ $message }}
                                            </div>
                                                    @enderror
                                        </div>

                                        <div class="col-md-2">
                                            <label>Activities</label>
                                            <input type="text" id="activity" class="form-control inputFieldHeight" name="activity" value="{{ isset($profitCenter) ? $profitCenter->activity : '' }}" placeholder="Activity"  >
                                                @error('activity')
                                            <div class="btn btn-sm btn-danger">{{ $message }}</div>
                                                @enderror
                                        </div>

                                        <div class="col-md-4">
                                            <label>Person responsible</label>
                                            <input type="text" id="prsn_responsible" class="form-control inputFieldHeight" name="prsn_responsible" value="{{ isset($profitCenter) ? $profitCenter->prsn_responsible : '' }}" placeholder="Person responsible"  >
                                                @error('prsn_responsible')
                                            <div class="btn btn-sm btn-danger">{{ $message }}</div>
                                                @enderror
                                        </div>

                                            <div class="col-12 d-flex justify-content-end mt-2">
                                                <button class="btn btn-info profit-center-form-btn formButton" data_target="{{ route('profitCenterForm') }}" id="profitCenterButton" style="margin-right: 0.2rem !important;"><img src="{{ asset('/icon/add-icon.png')}}" alt="" srcset="" class="image-fluid" width="25">New</button>
                                                <button type="submit" class="btn btn-primary formButton" title="Form Save" style="margin-right: 0.2rem !important;">
                                                    <div class="d-flex">
                                                        <div class="formSaveIcon">
                                                            <img  src="{{asset('/icon/save-icon.png')}}" alt="" srcset="" class="img-fluid" width="25">
                                                        </div>
                                                        <div><span> Save</span></div>
                                                    </div>
                                                </button>
                                                <button type="reset" class="btn btn-light-secondary formButton" title="Form Reset">
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
                            </form>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>
<div class="modal fade bd-example-modal-lg" id="profitCenterPrintModal" tabindex="-1" rrole="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
      <div class="modal-content">
        <div id="profitCenterPrintShow">

        </div>
      </div>
    </div>
</div>
@endsection

@push('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.0/js/toastr.js"></script>
    <script>

         if( $('#pc_name').val()!='')
        {
            $('#profitCenter').modal('show')

        }
        function printFunction(){
            window.print();
        }
        $(document).on("click", "#listPrint", function(e) {
            e.preventDefault();
            $.ajax({
                url: "{{URL('profit-center-list-print')}}",
                type: "post",
                cache: false,
                data:{
                    _token:'{{ csrf_token() }}',
                },
                success: function(response){
                    document.getElementById("profitCenterPrintShow").innerHTML = response;
                    $('#profitCenterPrintModal').modal('show');
                    setTimeout(printFunction, 500);
                }
            });
        });
        $(document).ready(function() {

            var delay = (function() {
                var timer = 0;
                return function(callback, ms) {
                    clearTimeout(timer);
                    timer = setTimeout(callback, ms);
                };
            })();
            $(document).on("click", ".profit-center-form-btn", function(e) {
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
                            $(".profit-center-form").empty().append(response.page);
                        },
                        error: function() {
                            //   alert('no');
                        }
                    });
                }, 999);
            });
        });
        // $(document).on("click", "#profitCenterButton", function(e){
        //     document.getElementById("").removeAttribute('disabled');
        // });
    </script>
@endpush
