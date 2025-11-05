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
            @include('clientReport.project._header',['activeMenu' => 'new-project'])
            <div class="tab-content bg-white p-2 active">
                <div class="tab-pane active">
                    <div>
                        <section>
                            <div class="row mb-1">
                                <div class="col-md-8 text-left">

                                    {{-- @if(Auth::user()->hasPermission('ProjectManagement_Create'))
                                    <a href="{{route('boq.sample.list')}}" class="btn btn-xs btn-primary  formButton" title="BOQ Sample">
                                        <div class="d-flex">
                                            <div class="formSaveIcon">
                                                <img src="{{asset('/icon/trial-balence-icon.png')}}" width="25">
                                            </div>
                                            <div><span> BOQ Sample </span></div>
                                        </div>
                                    </a>
                                    @endif --}}

                                    @if(Auth::user()->hasPermission('ProjectManagement_Create'))
                                    <a href="{{route('project.tasks.create')}}" class="btn btn-xs btn-primary btn_create formButton" title="Add">
                                        <div class="d-flex">
                                            <div class="formSaveIcon">
                                                <img src="{{asset('/icon/add-icon.png')}}" width="25">
                                            </div>
                                            <div><span> New Task </span></div>
                                        </div>
                                    </a>
                                    @endif
                                </div>

                                <div class="col-md-4">
                                    <form action="" class="d-flex">
                                        <input type="text" class="form-control inputFieldHeight" name="search" value="{{$search}}" placeholder=" Search by Subtask Task name">
                                        <button class="btn btn-info ml-1 formButton" style="padding:0 30px"> Search </button>
                                    </form>
                                </div>
                            </div>
                            <div class="cardStyleChange">
                                <table class="table mb-0 table-sm table-hover">
                                    <thead  class="thead-light">
                                        <tr style="height: 40px;">
                                            <th style="width:15%;" class="text-left"> Task </th>
                                            <th style="width:20%;" class="text-left"> Description </th>
                                            <th style="width:10%" class="text-center"> Unit </th>
                                            <th style="width:10%" class="text-center"> Qty </th>
                                            <th style="width:10%" class="text-center"> Rate <br> {{ number_format($data['cal_total_rate'], 2) }} </th>
                                            <th style="width:10%" class="text-center"> Amount <br> {{ number_format($data['cal_total_amount'], 2) }} </th>
                                            <th class="text-right pr-2" style="width:10%;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($tasks as $key => $task)
                                            <tr style="height: 40px; border-bottom: 1px solid #e0e0e0;">
                                                <td style="text-align:left;">{{ $task->name }}</td>
                                                <td colspan="4"></td>
                                                <td style="text-align:center;">{{ number_format($task->total_amount,2) }}</td>
                                                <td style="text-align:right; padding-right: 12px;">

                                                    <div style="display: flex; justify-content: flex-end; gap: 5px;">
                                                        @if(Auth::user()->hasPermission('ProjectManagement_Delete'))
                                                        <form action="{{ route('project.tasks.destroy', $task->id) }}" method="POST" style="margin: 0;">
                                                            @csrf
                                                            @method('delete')
                                                            <button onclick="return confirm('Are you want to delete this task?')" style="background: none; border: none; padding: 0;" title="Delete">
                                                                <img src="{{ asset('/icon/delete-icon.png') }}" style="height: 24px; width: 24px;">
                                                            </button>
                                                        </form>
                                                        @endif
                                                        @if(Auth::user()->hasPermission('ProjectManagement_Edit'))
                                                        <a href="{{ route('project.tasks.edit', $task->id) }}" title="Edit">
                                                            <img src="{{ asset('/icon/edit-icon.png') }}" style="height: 24px; width: 24px;">
                                                        </a>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>


                                            @foreach ($task->items as $subkey => $item)
                                                <tr>
                                                    <td colspan=""></td>
                                                    {{-- <td style="text-align:left;" title="{{$item->item_description}}">{{\Illuminate\Support\Str::limit( $item->item_description,40) }}</td> --}}
                                                    <td style="text-align:left;" title="{{ $item->item_description }}">
                                                        {{ \Illuminate\Support\Str::words($item->item_description, 3, '...') }}
                                                    </td>
                                                    <td style="text-align:center;">{{ $item->unit }}</td>
                                                    <td style="text-align:center;">{{ $item->qty }}</td>
                                                    <td style="text-align:center;">{{ number_format($item->rate,2) }}</td>
                                                    <td style="text-align:center;">{{ number_format($item->total,2) }}</td>
                                                    <td></td>
                                                </tr>
                                            @endforeach

                                        @endforeach
                                        {{-- <tr style=" background-color: #3d4a94 !important; color:white;">
                                            <td colspan="4" style="text-align: right ; margin-right:5px;">Total</td>
                                            <td>{{ number_format($data['cal_total_rate'], 2) }}</td>
                                            <td>{{ number_format($data['cal_total_amount'], 2) }}</td>
                                            <td colspan="1"></td>
                                        </tr> --}}
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-1">
                                {{$tasks->links()}}
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- The Modal -->
<div class="modal" id="boq-factor">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header" style="background: #364a60; padding:10px !important;">
        <h4 class="modal-title text-white"> BOQ Sample </h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body" style="padding:10px !important;">
        <form action="{{route('storeBoqFactor')}}" method="POST">
            @csrf
            <table class="table mb-0 table-sm table-hover">
                <thead class="thead-light">
                    <tr>
                        <th class="text-left" style="width:15%;">Project Task</th>
                        <th class="text-left" style="width:15%;">Item</th>
                        <th class="text-left" style="width:10%;">House Type</th>
                        <th class="text-left" style="width:10%;">Work Type</th>
                        <th class="text-center" style="width:10%;">Cost Factor</th>
                        <th class="text-center" style="width:7%;">Area</th>
                        <th class="text-center" style="width:7%;">Unit</th>
                        <th class="text-center" style="width:6%;">Qty</th>
                        <th class="text-left" style="width:10%;">Priority</th>
                        <th class="text-left" style="width:10%;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-left">
                            <select name="project_task[0]" id="project_task" class="form-control inputFieldHeight common-select2 project_task text-left" required>
                                <option value=""> Select </option>
                                @foreach ($tasks as $task)
                                    <option value="{{$task->id}}" data-items='@json($task->items)'>{{$task->name}}</option>
                                @endforeach
                            </select>
                        </td>

                        <td>
                            <select name="project_item[0]" id="project_item" class="form-control inputFieldHeight project_item text-left" required>
                                <option value=""> Select </option>
                            </select>
                        </td>

                        <td>
                            <select name="house_type[0]" id="house_type" class="form-control inputFieldHeight house_type text-left" required>
                                <option value=""> Select </option>
                                <option value="residential"> Residential </option>
                                <option value="villa"> Villa </option>
                                <option value="apartment"> Apartment</option>
                            </select>
                        </td>

                        <td>
                            <select name="work_type[0]" id="work_type" class="form-control inputFieldHeight work_type text-left" required>
                                <option value=""> Select </option>
                                <option value="standard"> Standard</option>
                                <option value="deluxe"> Deluxe </option>
                                <option value="premium"> Premium </option>
                            </select>
                        </td>

                        <td>
                            <input type="number" step="any" name="cost_factor[]" class="form-control inputFieldHeight text-center cost_factor" required value="1">
                        </td>

                        <td>
                            <input type="number" step="any" name="area[]" class="form-control inputFieldHeight text-center area" required>
                        </td>

                        <td>
                            <select name="unit[0]" id="priority" class="form-control inputFieldHeight priority text-left" required>
                                <option value=""> Select </option>
                                <option value="sqft"> Square Feet </option>
                                <option value="sqm"> Square Meter </option>
                                <option value="sqyd"> Square Yard </option>
                                <option value="acre"> Acre </option>
                                <option value="hectare"> Hectare </option>
                            </select>
                        </td>

                        <td>
                            <input type="number" step="any" name="qty[]" class="form-control inputFieldHeight text-center qty" required>
                        </td>


                        <td>
                            <select name="priority[0]" id="priority" class="form-control inputFieldHeight priority text-left" required>
                                <option value=""> Select </option>
                                <option value="high"> High </option>
                                <option value="medium"> Medium </option>
                                <option value="low"> Low </option>
                            </select>
                        </td>

                        <td class="text-left">
                            <button type="button" class="add-row" style="border: none; background:#3d4a94; color:#fff;"> <i class="bx bx-plus"></i> </button>
                            <button type="button" class="remove-row" style="border: none;background:rgb(197, 66, 5); color:#fff;"> <i class="bx bx-trash"></i> </button>
                        </td>
                    </tr>
                </tbody>
            </table>

            <button type="submit" class="btn btn-primary"> Save </button>
        </form>
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>

<div class="modal fade bd-example-modal-lg" id="profitCenterPrintModal" tabindex="-1" rrole="dialog"
   aria-labelledby="myLargeModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false"  style="z-index: 1080;">

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
@endpush
