@extends('layouts.backend.app')
@push('css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.0/css/toastr.css" rel="stylesheet" />

@endpush
@section('content')
@include('backend.tab-file.style')
<style>
    .accordion .pluseMinuseIcon.collapsed::before {
        content: "\f067";
        ;
        cursor: pointer;
        border: 1px solid rgb(123, 123, 123);
    }

    .accordion .pluseMinuseIcon::before {
        font-family: 'FontAwesome';
        content: "\f068";
        cursor: pointer;
        border: 1px solid rgb(123, 123, 123);
    }

    .rowStyle {
        cursor: pointer;
        border-left: dotted;
        padding: 3px;
        margin-bottom: 2px;
    }

    .findMasterAcc {
        cursor: pointer;
    }

    .nav.nav-tabs~.tab-content {
        padding-top: 1px;
    }

    /* ================My Code============= */
    .bg-secondary {
        background-color: #34465b !important;
        border-radius: 40px;
        color: white !important;
        padding: 2px 5px 2px 5px !important;
    }

    a.bg-secondary:hover,
    a.bg-secondary:focus,
    button.bg-secondary:hover,
    button.bg-secondary:focus {
        background-color: #475f7b30 !important;
        color: black !important;
    }

    tr:nth-child(even) {
        background-color: #c8d6e357;
    }

    a.text-dark:hover,
    a.text-dark:focus {
        color: #ffffff !important;
    }

    .btn-outline-secondary {
        border-radius: 40px;
        padding: 0.2px 9px 0.2px 9px !important;
    }

    .table .thead-light th {
        color: #F2F4F4;
        background-color: #34465b;
        border-color: #DFE3E7;
    }
    /* -------- submit button -------- */
    .btn-icon-text img {
        margin-right: 3px;
    }

    .select2-results__option[aria-selected] {
        cursor: pointer;
        text-align: left;
    }
</style>
<div class="app-content content print-hideen">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-body">
            @include('clientReport.setup._header',['activeMenu' => 'chart-of-account'])
            <div class="tab-content bg-white">
                <div id="masterAccount" class="tab-pane active" style="width:100%; max-width:1000px;">
                    @include('clientReport.setup.chart-of-account-sub',['activeMenu' => 'master-ac'])
                    <section class="mr-1 ml-1" style="max-width: 1000px">
                        <div class="mt-1">
                            <div class="row">
                                <div class="col-md-6">
                                    <form>
                                        <input type="text" name="q"
                                            class="form-control input-xs inputFieldHeight ajax-search"
                                            placeholder="Search By A/C Code, A/C Head, Definition, VAT Type, A/C Type"
                                            data-url="{{ route('admin.masterAccSearchAjax',$id=" masterAcc") }}">
                                    </form>
                                </div>

                                <div class="col-md-6 text-right">
                                    @if(Auth::user()->hasPermission('Setup_Create'))
                                    <button type="button" class="btn btn-xs mExcelButton formButton bg-primary mb-1"
                                        title="Add" data-toggle="modal" data-target="#masterAccountCreate"
                                        style="padding-top: 6px;padding-bottom: 6px;width: 150px;">
                                        <img src="{{asset('/icon/add-icon.png')}}" class="img-fluid" width="20">
                                        Account
                                    </button>
                                    @endif

                                    <a href="{{route('master-account-export')}}"
                                        class="btn btn-xs mExcelButton formButton mb-1" style="width: 150px;"
                                        title="Export to Excel">
                                        <img src="{{asset('/icon/excel-icon.png')}}" class="img-fluid" width="30">
                                        Excel Export
                                    </a>

                                </div>
                            </div>
                            <div class="cardStyleChange" style="height: 500px; overflow-y:auto;">
                                <table class="table mb-0 table-sm table-hover">
                                    <thead class="thead-light" style="position: sticky; top:-2px; z-index:99;">
                                        <tr class="mTheadTr" style="height: 40px;text-align:center;">
                                            <th style="width:10%;" class="text-center">Code</th>
                                            <th class="text-left" style="width:30%;">Head</th>
                                            <th class="text-left" style="width:25%;">Definition</th>
                                            <th style="width:15%;">A/C Type</th>
                                            <th style="width:10%;">
                                                @if(!empty($currency->vat_name)){{$currency->vat_name}} @endif Type</th>
                                            <th class="text-right" style="padding-left: 20px;" style="width:10%;">Action
                                            </th>
                                        </tr>
                                    </thead>

                                    <tbody class="user-table-body">
                                        @foreach ($masterDetails as $masterAcc1)
                                        <tr class="trFontSize" style="height: 40px;text-align:center;">
                                            <td class="view"
                                                data-url="{{route('chart-acctount.details',$masterAcc1->id)}}">{{
                                                $masterAcc1->mst_ac_code }}</td>
                                            <td class="text-left view"
                                                data-url="{{route('chart-acctount.details',$masterAcc1->id)}}">{{
                                                $masterAcc1->mst_ac_head }}</td>
                                            <td class="text-left view"
                                                data-url="{{route('chart-acctount.details',$masterAcc1->id)}}">{{
                                                $masterAcc1->mst_definition }}</td>
                                            <td class="view"
                                                data-url="{{route('chart-acctount.details',$masterAcc1->id)}}">{{
                                                $masterAcc1->mst_ac_type }}</td>
                                            <td class="view"
                                                data-url="{{route('chart-acctount.details',$masterAcc1->id)}}">{{
                                                $masterAcc1->vat_type }}</td>

                                            <td>
                                                <div style="margin-top: -12px;">
                                                    @if ($masterAcc1->office_id !=0)
                                                    <a href="{{ route('masterEdit',$masterAcc1) }}" class="btn"
                                                        style="height: 30px; width: 30px;" title="Edit"><img
                                                            src="{{ asset('/icon/edit-icon.png')}}"
                                                            style=" height: 25px; width: 25px;"></a>
                                                    <a href="{{ route('masterDelete',$masterAcc1) }}"
                                                        onclick="event.preventDefault(); deleteAlert(this, 'About to delete account head. Please, confirm?');"
                                                        class="btn"
                                                        style="height: 25px; width: 25px;padding: 0.467rem 0.8rem;"
                                                        title="Delete"><img src="{{ asset('/icon/delete-icon.png')}}"
                                                            style=" height: 25px; width: 25px; margin-left: -12px;"></a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>

                                </table>
                                {{$masterDetails->links()}}
                            </div>

                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="masterAccountCreate" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header" style="padding: 5px 15px;background:#364a60;">
                    <h5 class="modal-title" id="exampleModalLabel"
                        style="font-family:Cambria;font-size: 2rem;color:white;"> Master Account Details</h5>
                    <div class="d-flex align-items-center">
                        <button type="button" class="project-btn bg-danger text-white" data-dismiss="modal"
                            aria-label="Close" style="padding: 3px 12px;border: none; border-radius: 5px;"
                            data-bs-toggle="tooltip" data-bs-placement="right" title="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>
                <div class="modal-body" style="padding: 5px 5px;">
                    <section id="widgets-Statistics" class="mr-1 ml-1 mb-1" data-select2-id="widgets-Statistics">
                        <!-- <div class="row">
                            <div class="col-md-6  mt-2 mb-2">
                                <h4>Master Account Details</h4>
                            </div>
                            <div class="col-md-6  mt-2 mb-2" style="text-align: right;">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal" aria-label="Close">Close</button>
                            </div>
                                {{-- @include('alerts.alerts') --}}
                        </div> -->
                        <div class="row" data-select2-id="16">
                            <div class="col-12">

                                @isset($masterAcc)
                                <form action="{{ route('masterDetailsUpdate', $masterAcc) }}" method="POST">
                                @else
                                <form action="{{ route('masterDetailsPost') }}" method="POST">
                                @endisset
                                    @csrf

                                    <div class="cardStyleChange  pt-1">
                                        <div class="form-row text-left">

                                            <!-- Account -->
                                            <div class="form-group col-md-4 col-12 mb-2">
                                                <label for="category"><strong>Account :</strong></label>
                                                <select name="category" id="category" class="common-select2 form-control inputFieldHeight" required>
                                                    <option value="">Select Category...</option>
                                                    @foreach ($categories as $item)
                                                        <option value="{{ $item->id }}" {{ isset($masterAcc) && $masterAcc->category_id == $item->id ? 'selected' : '' }}>
                                                            {{ $item->title }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('category')
                                                    <span class="text-danger small">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <!-- Code -->
                                            <div class="form-group col-md-4 col-12 mb-2">
                                                <label for="mst_ac_code"><strong>Code :</strong></label>
                                                <input type="text" id="mst_ac_code"
                                                    class="form-control inputFieldHeight"
                                                    name="mst_ac_code"
                                                    value="{{ isset($masterAcc)?$masterAcc->mst_ac_code:'' }}"
                                                    placeholder="Master A/C Code" disabled>
                                                @error('mst_ac_code')
                                                    <span class="text-danger small">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <!-- Definition -->
                                            <div class="form-group col-md-4 col-12 mb-2">
                                                <label for="mst_definition"><strong>Definition :</strong></label>
                                                <select name="mst_definition" id="mst_definition" class="common-select2 form-control inputFieldHeight" required>
                                                    <option value="">Select...</option>
                                                    @foreach ($mst_definitions as $item)
                                                        <option value="{{ $item->title }}" {{ isset($masterAcc) && $masterAcc->mst_definition == $item->title ? 'selected' : '' }}>
                                                            {{ $item->title }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('mst_definition')
                                                    <span class="text-danger small">{{ $message }}</span>
                                                @enderror
                                            </div>

                                        </div> <!-- 1st row -->

                                        <div class="form-row text-left">
                                            <!-- Type -->
                                            <div class="form-group col-md-4 col-12 mb-2">
                                                <label for="mst_ac_type"><strong>Type :</strong></label>
                                                <select name="mst_ac_type" id="mst_ac_type" class="common-select2 form-control inputFieldHeight" required>
                                                    <option value="">Select...</option>
                                                    @foreach ($mstAccType as $item)
                                                        <option value="{{ $item->title }}" {{ isset($masterAcc) && $masterAcc->mst_ac_type == $item->title ? 'selected' : '' }}>
                                                            {{ $item->title }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('mst_ac_type')
                                                    <span class="text-danger small">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <!-- Head -->
                                            <div class="form-group col-md-4 col-12 mb-2">
                                                <label for="mst_ac_head"><strong>Head :</strong></label>
                                                <input type="text" id="mst_ac_head"
                                                    class="form-control inputFieldHeight"
                                                    name="mst_ac_head"
                                                    value="{{ isset($masterAcc)?$masterAcc->mst_ac_head:'' }}"
                                                    placeholder="Master A/C Head" required>
                                                @error('mst_ac_head')
                                                    <span class="text-danger small">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <!-- VAT Type -->
                                            <div class="form-group col-md-4 col-12 mb-2">
                                                <label for="vat_type"><strong>{{ !empty($currency->vat_name) ? $currency->vat_name : 'VAT' }} Type :</strong></label>
                                                <select name="vat_type" id="vat_type" class="common-select2 form-control inputFieldHeight" required>
                                                    <option value="">Select...</option>
                                                    @foreach ($vat_types as $item)
                                                        <option value="{{ $item->title }}" {{ isset($masterAcc) && $masterAcc->vat_type == $item->title ? 'selected' : '' }}>
                                                            {{ $item->title }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('vat_type')
                                                    <span class="text-danger small">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div> <!-- 2nd row -->

                                        <!-- Buttons -->
                                        <div class="d-flex justify-content-center gap-2 ">
                                            @isset($masterAcc)
                                                <a href="{{ route('new-chart-of-account') }}" class="btn btn-info formButton mr-1">
                                                    <img src="{{ asset('/icon/add-icon.png')}}" width="20" class="mr-1"> New
                                                </a>
                                            @endisset

                                            @if(Auth::user()->hasPermission('Setup_Create'))
                                                <button type="submit" class="btn btn-primary formButton" title="Form Save">
                                                    <div class="d-flex align-items-center btn-icon-text">
                                                        <img src="{{asset('/icon/save-icon.png')}}" width="20" class="me-1">
                                                        <span>{{ isset($masterAcc) ? 'Update' : 'Save' }}</span>
                                                    </div>
                                                </button>
                                            @endif
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
</div>

<div class="modal fade" id="excel_import" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Company List</h5>
                {{-- <a href="{{asset('asmaa-transport truck service add excel sample.xlsx')}}">Sample Download</a> --}}
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{route('master-account-excel-import')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-1">
                        <label for="">Select Company</label>
                        <select name="office_id" class="form-control common-select2" id="" style="width: 100%" required>
                            <option value="">Select Company</option>
                            @foreach ($offices as $item)
                            <option value="{{$item->id}}">{{$item->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <p class="text-danger">If you copy data from this company, your previous <strong>Master Account,
                            Account Head and Sub Account with all Transaction</strong> data will remove !</p>
                    {{-- <div class="mb-1">
                        <input type="file" required class="form-control" name="excel_file" accept=".xlsx">
                    </div> --}}
                    @php
                    $token = time()+rand(10000,99999);
                    @endphp
                    <input type="hidden" name="token" value="{{$token}}">
                    <div class="text-right">
                        <button type="submit" class="btn btn-primary"
                            onclick="return confirm('Please Confirm ?')">Upload</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="view-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog {{--modal-lg--}} modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body" id="view-modal-body" style="padding: 0 !important;">

            </div>
        </div>
    </div>
</div>
</div>
@endsection

@push('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.0/js/toastr.js"></script>
{{-- <script src="{{ asset('storage/upload/vendors/js/jquery/jquery.min.js') }}"></script> --}}
<script>
    if( $('#mst_ac_head').val()!='')
        {
            $('#masterAccountCreate').modal('show')

        }

        $(document).ready(function() {
            $('#category').change(function() {
                // alert(1);
                if ($(this).val() != '') {
                    var value = $(this).val();
                    var _token = $('input[name="_token"]').val();

                    $.ajax({
                        url: "{{ route('findMastedCode') }}",
                        method: "POST",
                        data: {
                            value: value,
                            _token: _token,
                        },
                        success: function(response) {
                            $("#mst_ac_code").val(response);
                        }

                    })
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

        $(document).on("click", ".findMasterAcc", function(e) {
            e.preventDefault();
            var that = $(this);

            var urls = that.attr("data-target");
            delay(function() {
                $.ajax({
                    url: urls,
                    type: 'GET',
                    cache: false,
                    dataType: 'json',
                    success: function(response) {
                        //   alert('ok');
                        // console.log(response);
                        $(".pagination").remove();
                        $(".user-table-body").empty().append(response.page);
                    },
                    error: function() {
                        //   alert('no');
                    }
                });
            }, 999);
        });
        $(document).on("click", ".editAccHead", function(e) {
            e.preventDefault();
            var that = $(this);

            var urls = that.attr("data-target");
            delay(function() {
                $.ajax({
                    url: urls,
                    type: 'GET',
                    cache: false,
                    dataType: 'json',
                    success: function(response) {
                        //   alert('ok');
                        // console.log(response);
                        $(".pagination").remove();
                        $(".user-table-body").empty().append(response.page);
                    },
                    error: function() {
                        //   alert('no');
                    }
                });
            }, 999);
        });

        $(document).on('click', '.view', function() {
            var url = $(this).data('url');

            $.ajax({
                url: url,
                type: 'get',
                success: function(response) {
                    $('#view-modal-body').html(response);
                    $('#view-modal').modal('show');
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', error);
                }
            });
        });

    });
</script>
@endpush
