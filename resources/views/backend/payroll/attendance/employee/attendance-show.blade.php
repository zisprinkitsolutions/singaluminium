

<style>
    input[type=text], select, textarea {
        height: 2.5rem;
        font-size: 12px !important;
    }
    select.form-control:not([multiple]) {
        background-image: none !important;
    }
    .print-hide-print-show{
        display: block;
    }
</style>

 <section class="print-hideen border-bottom" style="padding: 0px 15px; background-color:#475f7b;">
    <div class="d-flex justify-content-between align-item-center">
        <h5 style="font-family:Cambria;font-size: 1.6rem; margin-top:8px;margin-left:13px;color:#ececec !important;"><b>Employee Attendance</b> </h5>

        <div class="d-flex flex-row-reverse">
            <div class="mIconStyleChange">
                <a href="#" class="btn-icon btn btn-danger mIconStyleChange212" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class='bx bx-x'></i></span>
                </a>
            </div>
            <div class="mIconStyleChange">
                <a href="#" title="Print" onclick="handlePrintClick('modal-body-print', 0)" class="btn btn-icon btn-info mIconStyleChange212"><i class='bx bx-printer'></i></a>
            </div>
        </div>
    </div>
</section>


<div class="modal-body" id="modal-body" style="padding: 10px 15px;">
      {{-- Attendance Information --}}
    <div class="">
        <h5 style="font-family:Cambria;font-size: 1.7rem; margin-left:10px;" class="print-hide-print-show"><b>{{$name}} Attendance of {{ date('F Y', strtotime($date)) }}</b></h5>


        <div class="table-responsive" id="employee-attendance-table" style="min-height: 300px;">
            <table class="table table-sm table-hover table-bordered" style="max-width: 100%;">
                <thead class="thead-light">
                    <tr class="text-center" >
                        <th rowspan="2">DATE</th>
                        <th rowspan="2">ATTENDANCE STATUS</th>

                        {{-- morning attendance info  --}}
                        <th colspan="4">MORNING</th>

                        {{-- evening attendance info  --}}
                        <th colspan="4">EVENING</th>

                        <th rowspan="2"> LATE TIME</th>

                        <th rowspan="2"> OVERTIME</th>
                        <th rowspan="2"> WORKING HOURS</th>
                    </tr>
                    <tr>
                        <th style="width: 10%; padding: 4px;">IN TIME</th>
                        <th style="width: 10%; padding: 4px;">OUT TIME</th>
                        <th style="width: 10%; padding: 4px;">REF IN </th>
                        <th style="width: 10%; padding: 4px;">REF OUT </th>
                        {{-- evening attendance info  --}}
                        <th style="width: 10%; padding: 4px;">IN TIME</th>
                        <th style="width: 10%; padding: 4px;">OUT TIME</th>
                        <th style="width: 10%; padding: 4px;">REF IN </th>
                        <th style="width: 10%; padding: 4px;">REF  OUT </th>
                    </tr>
                </thead>
                <tbody>
                    @if($attendanceData && $attendanceData->count() > 0)
                        @foreach ($attendanceData as $key => $data)
                            <tr class="text-center">
                                <td>{{ \Carbon\Carbon::parse($data->date)->format('d/m/Y') }}</td>
                                <td>
                                    @if($data->status == 1)
                                    YES
                                    @elseif($data->status == 0)
                                    Absen
                                    @elseif($data->status == 2)
                                    Leave
                                    @elseif($data->status == 3)
                                    Weekend
                                    @else
                                    NO
                                    @endif
                                </td>
                                {{-- morning attendance  --}}
                                <td>{{ $data->in_time }}</td>
                                <td>{{ $data->out_time }}</td>
                                <td>{{ $data->reference_in_time }}</td>
                                <td>{{ $data->reference_out_time }}</td>

                                {{-- evening attendance  --}}
                                <td>{{ $data->evening_in }}</td>
                                <td>{{ $data->evening_out }}</td>
                                <td>{{ $data->e_reference_in_time }}</td>
                                <td>{{ $data->e_reference_out_time }}</td>


                                <td>{{ $data->total_late_time ?? 'N/A' }}</td>
                                <td>{{ $data->total_overtime ?? 'N/A' }}</td>
                                <td>{{ $data->total_working_hours ?? 'N/A' }}</td>
                            </tr>
                        @endforeach
                    @else
                        <tr><td colspan="9" class="text-center">No Attendance Found !!</td></tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

</div>


