@extends('layouts.backend.app')
@push('css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.0/css/toastr.css" rel="stylesheet" />

@endpush
@section('content')
@include('layouts.backend.partial.style')
<style>
    .accordion .pluseMinuseIcon.collapsed::before{
        content: "\f067";;
        cursor: pointer;
        border: 1px solid rgb(123, 123, 123);
    }
    .accordion .pluseMinuseIcon::before {
        font-family: 'FontAwesome';
        content: "\f068";
        cursor: pointer;
        border: 1px solid rgb(123, 123, 123);
    }
    .rowStyle{
        cursor: pointer;
        border-left: dotted;
        padding: 3px;
        margin-bottom: 2px;
    }
    .findMasterAcc{
        cursor: pointer;
    }
    .card {
    margin-bottom: 0px !important;
    box-shadow: -8px 12px 18px 0 rgb(25 42 70 / 13%);
    transition: all .3s ease-in-out, background 0s, color 0s, border-color 0s;
}

label {
    color: #475F7B;
    font-size: 9px;
    text-transform: uppercase;
    font-weight: 500;
}
</style>
<div class="app-content content print-hideen">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-body">
            <div class="tab-content bg-white">
                <div id="masterAccount" class="tab-pane active p-2">
                    <div class="d-flex align-items-center gap-2" style="border-bottom: 1px solid #ddd">


                        <a href="{{route('opening-cash-asset')}}" class="nav-item nav-link {{ Request::is('*opening-cash-asset*') ? 'text-white bg-secondary' : ' text-dark'}}" role="tab" aria-controls="nav-contact" aria-selected="false">
                            <div class="">Opening Assets</div>
                        </a>



                        <a href="{{route('opening-others')}}" class="nav-item nav-link {{ Request::is('*opening-others*') ? 'text-white bg-secondary' : ' text-dark'}}" role="tab" aria-controls="nav-contact" aria-selected="false">
                            <div class="">Opening Others</div>
                        </a>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <form action="{{ route('opening-cash-asset.store') }}" method="POST" >
                                @csrf
                                <div class="row d-flex justify-content-center mx-1 p-0">
                                    <div class="col-md-4 form-group">
                                        <label for="">Date </label>
                                        <input type="date"
                                            value="{{ Carbon\Carbon::now()->format('Y-m-d') }}"
                                            class="form-control" name="date" id="date" required>
                                    </div>
                                </div>
                                <div class="row pb-1 d-flex justify-content-center mx-1">

                                    <div class="col-md-5">
                                        <div class="repeater-default" id="form-repeat-container">
                                            <div data-repeater-list="group-a">
                                                <div data-repeater-item>
                                                    <div class="row every-form-row d-flex align-items-center">

                                                        <div class="col-md-6 changeColStyle text-center px-0 mx-0">
                                                            <label for="">Item</label>
                                                            <select name="item_name"  class="form-control form-control-sm" required>
                                                            <option value="">Select...</option>
                                                            @foreach($items as $item)
                                                                <option value="{{$item->id}}">{{$item->fld_ac_head}}</option>
                                                            @endforeach
                                                        </select>
                                                        </div>


                                                        <div class="col-md-5 changeColStyle text-center px-0 mx-0">
                                                            <label for="">Total Price</label>
                                                            <input type="number" name="total_price" class="form-control form-control-sm total_price"  step="any" placeholder="Cost Price" required>

                                                        </div>
                                                        <div class="col-md-1  d-flex align-items-center">
                                                            <button type="button" class="btn btn-sm btn-danger formButton mDeleteIcon mt-1" data-repeater-delete title="Delete">
                                                                <div class="d-flex align-items-right">
                                                                    <div class="formSaveIcon">
                                                                        <img  src="{{asset('assets/backend/app-assets/icon/delete-icon.png')}}" alt="" srcset=""  width="15">
                                                                    </div>
                                                                    <div><span> Delete</span></div>
                                                                </div>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <div class="form-group">
                                                        <div class="col p-0">
                                                            <button type="button" class="btn btn-primary btn_create formButton" data-repeater-delete title="Add" data-repeater-create>
                                                                <div class="d-flex">
                                                                    <div class="formSaveIcon">
                                                                        <img  src="{{asset('assets/backend/app-assets/icon/add-icon.png')}}" alt="" srcset=""  width="25">
                                                                    </div>
                                                                    <div><span>Add</span></div>
                                                                </div>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-12">
                                                    <div>
                                                        <input type="number" step="any" id="total_amount" class="form-control @error('total_amount') error @enderror inputFieldHeight" name="total_amount" value="" placeholder="Total Amount" readonly required>
                                                        @error('total_amount')
                                                        <span class="error">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 text-center">
                                        <button type="submit"
                                                            class="btn btn-sm final-save-btn only-save-btn  btn-primary" id="final_save">
                                                            Save</button>
                                                            <a  class="btn btn-sm btn-warning" onClick="refreshPage()">Refresh</a>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.0/js/toastr.js"></script>
    {{-- <script src="{{ asset('assets/backend/app-assets/vendors/js/jquery/jquery.min.js') }}"></script> --}}
    <script src="{{ asset('assets/backend')}}/app-assets/vendors/js/forms/repeater/jquery.repeater.min.js"></script>
    <script src="{{ asset('assets/backend')}}/app-assets/js/scripts/forms/form-repeater.js"></script>

<script>
 var i = 1;
    $(document).ready(function() {
            $(document).on("keyup", ".total_price", function(e) {
                sum_class()
            });

        function sum_class()
            {
                var sum = 0;
                $('.total_price').each(function() {
                if(this.value != '')
                {
                    sum += parseFloat(this.value);
                }
                });
                $("#total_amount").val(sum);
            }
    });
</script>
@endpush


