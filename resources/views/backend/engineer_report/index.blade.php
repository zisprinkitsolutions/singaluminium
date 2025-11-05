@extends('layouts.backend.app')

@push('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" />
   <style>
        .modal-lg {
            max-width: 90% !important;
        }
        div.dataTables_length {
           margin: 0rem 0 !important;
           float: left !important;
        }
        .modal .data-table.table-responsive {
            max-height: 500px; /* adjust height as needed */
            overflow-y: auto;
        }
    </style>
@endpush

@section('content')
    <!-- BEGIN: Content-->
    @if(Auth::user()->hasPermission('dashboard'))
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-body" id="dashboard">
                <!-- Widgets Statistics start -->
                <section id="widgets-Statistics">
                    <div class="row print-hide">

                        <div class="col-12 px-1 mt-2">
                            <div class="due-fee bg-white px-1">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h2 class="mt-1"> Daily Report (Engineer)  </h2>
                                    <div class="d-flex algin-items-center">
                                        <button class="daily-report-add btn btn-primary inputFieldHeight" style="padding:3px 7px !important;"
                                            data-url="{{route('receipt-voucher-list-show')}}" class="ml-2 due-btn text-dark"
                                            data-toggle="modal" data-target="#voucherPreviewModal"> Add <i class='bx bx-message-alt-add'></i> </button>
                                    </div>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-sm table-bordered text-nowrap">

                                        <thead style="background-color: #2B569A !important">
                                            <tr class="text-center">
                                                <th style="color:white; min-width:90px;"> Date </th>
                                                <th style="color:white; min-width:270px; text-align:left;"> Project Name </th>
                                                <th style="color:white; min-width:120px;"> Project Code </th>
                                                <th style="color:white; min-width:120px; text-align:left;"> Plot No </th>
                                                <th style="color:white; min-width:200px; text-align:left;"> Task </th>
                                                <th style="color:white; min-width:90px;"> Progress % </th>
                                                <th style="color:white; min-width:90px;"> Start Date </th>
                                                <th style="color:white; min-width:90px;"> End Date </th>
                                                <th style="color:white; min-width:90px;">  Status </th>
                                            </tr>
                                        </thead>

                                        <tbody style="font-size: 12px !important;">
                                            @foreach($reports as $report)
                                            <tr class="detail-button" data-url="{{route('engineer.reports.show', $report->id)}}" style="cursor: pointer">
                                                <td class="text-center">{{ date('d/m/Y', strtotime($report->date)) }}</td>
                                                <td>{{ \Illuminate\Support\Str::limit( optional($report->new_project)->name,25,'...') }}</td>
                                                <td class="text-center">{{optional($report->new_project)->project_no }}</td>
                                                <td class="text-left">{{ optional($report->new_project)->plot }}</td>
                                                <td class="text-left">{{ \Illuminate\Support\Str::limit( optional($report->task)->task_name,25) }}</td>
                                                <td class="text-center">{{ $report->total_progress }} % </td>
                                                <td class="text-center">{{ $report->start_date ? date('d/m/Y',strtotime($report->start_date)) : ''}} </td>
                                                <td class="text-center">{{ $report->end_date ? date('d/m/Y', strtotime($report->end_date)) : '' }} </td>
                                                <td class="text-center"> <span class="badge {{$report->status == 'pending' ? 'bg-warning' : 'bg-success'}} text-capitalize"> {{ $report->status}} </span> </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- Widgets Statistics End -->
            </div>
        </div>
    </div>
    @endif

     {{-- Modal  --}}
    <div class="modal fade bd-example-modal-lg" id="voucherPreviewModal" tabindex="-1" rrole="dialog"
        aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div id="voucherPreviewShow">
                    <section class="print-hideen border-bottom" style="background: #364a60;">
                        <div class="d-flex flex-row-reverse">

                            <div class="pr-1" style="padding-top: 5px;padding-right: 24px !important;">
                                <a href="#" class="close btn-icon btn btn-danger" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class='bx bx-x'></i></span></a>
                            </div>

                            <div class="pr-1 w-100 pl-2">
                                <h4 style="font-family:Cambria;font-size: 2rem;color:white;"> Daily Report </h4>
                            </div>
                        </div>
                    </section>

                    <section id="widgets-Statistics" style="padding: 15px 22px;">
                        <form action="{{route('engineer.reports.store')}}" class="daily-report-form" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">

                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <div class="col-6 col-md-4 col-lg-2">
                                    <div class="form-group">
                                        <label for=""> Date  </label>
                                        <input type="text" name="date" value="{{date('d/m/Y')}}" class="form-control datepicker date" data-pre=".null" data-next=".project_id">
                                    </div>
                                </div>

                                <div class="col-6 col-md-4 col-lg-2">
                                    <div class="form-group">
                                        <label for=""> Project </label>
                                        <select name="project_id" id="project_id" class="project_id form-control common-select2" data-pre=".date" data-next=".task_id" required>
                                            <option value=""> Select... </option>
                                            @foreach ($projects as $project)
                                                <option value="{{$project->id}}"> {{$project->project_name}} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-6 col-md-4 col-lg-2">
                                    <div class="form-group">
                                        <label for=""> Task </label>
                                        <select name="task_id" class="form-control common-select2 task_id" data-pre=".project_id" data-next=".item_id" required>

                                        </select>
                                    </div>
                                </div>

                                <div class="col-6 col-md-4 col-lg-2">
                                    <div class="form-group">
                                        <label for=""> Item </label>
                                        <select name="item_id" class="form-control common-select2 item_id" data-pre=".task_id" data-next=".work_details">

                                        </select>
                                    </div>
                                </div>

                                <div class="col-6 col-md-4 col-lg-2">
                                    <div class="form-group">
                                        <label for=""> Start Date </label>
                                        <input type="text" name="start_date" class="form-control datepicker start_date" data-pre=".item_id" data-next=".end_date" autocomplete="off" required>
                                    </div>
                                </div>

                                <div class="col-6 col-md-4 col-lg-2">
                                    <div class="form-group">
                                        <label for=""> End Date  </label>
                                        <input type="text" name="end_date" class="form-control datepicker end_date" data-pre=".start_date" data-next=".work_details" autocomplete="off" required>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responseive">
                                <table class="table table-bordered table-sm daily-report-input-table">
                                    <thead>
                                        <th> Work Details  </th>
                                        <th> Progress % </th>
                                        <th> image </th>
                                        <th> Action </th>
                                    </thead>
                                    <tbody class="daily-report-input-body">
                                        <tr>
                                            <td>
                                                <input type="text" class="form-control inputFieldHeight work_details" placeholder="Work Details" name="work_details[0]"
                                                    data-pre=".item_id" data-next=".work_progress" required>
                                            </td>

                                            <td>
                                                <input type="number" step="any" class="form-control inputFieldHeight" placeholder="Work Progress" name="progress[0]" required
                                                     data-pre=".work_details" data-next=".image">
                                            </td>

                                            <td>
                                                <input type="file" class="form-control inputFieldHeight image" placeholder="image" name="image[0]" required
                                                    data-pre=".image" data-next="save" multiple>
                                            </td>

                                            <td>
                                                <button type="button" class="add-new-row btn btn-primary inputFieldHeight" style="padding:4px 7px !important;" data-index="0"> <i class='bx bx-message-alt-add'></i> </button>
                                                <button type="button" class="start-camera btn btn-primary inputFieldHeight" style="padding:4px 7px !important;" data-index="0"> <i class='bx bxs-camera-plus'></i> </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="my-1">
                                <button class="btn btn-primary inputFieldHeight"> Save </button>
                            </div>
                        </form>
                    </section>
                </div>
            </div>
        </div>
    </div>


    <div id="camera-box" style="position: fixed; z-index: 9999; background: white; padding: 10px; top: 20px; right: 20px; border: 1px solid #ccc; display:none">
        <video id="video" width="320" height="240" autoplay></video><br>
        <button class="start-camera btn-primary inputFieldHeight">Start Camera</button>
        <button class="take-photo btn-primary inputFieldHeight">Take Photo</button>
        <button class="cancel-camera btn-primary inputFieldHeight">Cancel</button>

        <canvas id="canvas" width="320" height="240" style="display:none;"></canvas><br>
        <div class="image-body" style="display: none">
            <img id="photo" alt="Captured Image" width="320" height="240" /> <br>
            <button class="save-photo btn-primary inputFieldHeight" style="margin-top:8px; display-none">Save Image</button>
        </div>

    </div>


    {{-- modal --}}

    <div class="modal fade bd-example-modal-lg" id="voucherPreviewModal1" tabindex="-1" rrole="dialog"
        aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div id="voucherPreviewShow1">

                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    {{-- <script src="{{ asset('assets/backend/app-assets/vendors/js/jquery/jquery.min.js') }}"></script> --}}
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script>

        let currentIndex = 0;

        function updateAddButton(form) {
            form.find('.add-new-row').each(function(){
                $(this).hide();
            });
            const lastRow = form.find('.daily-report-input-body tr:last');
            lastRow.find('.add-new-row').show();
        }

        function addNewRow(row, form) {
            // Get index from button inside the row
            let currentBtn = row.find('.add-new-row');
            currentIndex = parseInt(currentBtn.data('index')) + 1;
            let newRow = `
                <tr>
                    <td>
                        <input type="text" class="form-control inputFieldHeight work_details" placeholder="Work Details" name="work_details[${currentIndex}]" required>
                    </td>
                    <td>
                        <input type="text" class="form-control inputFieldHeight" placeholder="Work Progress" name="progress[${currentIndex}]" required>
                    </td>
                    <td>
                        <input type="file" class="form-control inputFieldHeight image" placeholder="image" name="image[${currentIndex}]" required multiple>
                    </td>
                    <td>
                        <button type="button" class="add-new-row btn btn-primary inputFieldHeight" style="padding:4px 7px !important;" data-index="${currentIndex}">
                            <i class='bx bx-message-alt-add'></i>
                        </button>
                        <button type="button" class="start-camera btn btn-primary inputFieldHeight" style="padding:4px 7px !important;" data-index="${currentIndex}">
                            <i class='bx bxs-camera-plus'></i>
                        </button>

                        <button type="button" class="delete-row btn btn-danger inputFieldHeight" style="padding:4px 7px !important;"> <i class='bx bxs-message-square-x'></i> </button>
                    </td>
                </tr>
            `;

            form.find('.daily-report-input-body').append(newRow);

            updateAddButton(form);
        }

        $(document).on('click', '.add-new-row', function () {
            var form = $(this).closest('.daily-report-form');
            var row = $(this).closest('tr');
            addNewRow(row, form);
        });

        let videoStream;

        $(document).on('click', '.start-camera', function () {
            currentIndex = $(this).data('index'); // store row index

            if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
                navigator.mediaDevices.getUserMedia({ video: true })
                    .then(function (stream) {
                        videoStream = stream;
                        $('#video')[0].srcObject = stream;
                        $('#camera-box').show();
                    })
                    .catch(function (error) {
                        toastr.error("Camera access denied or not available: " + error);
                    });
            } else {
                toastr.error("Camera not supported by your browser.");
            }
        });

        $(document).on('click', '.take-photo', function () {
            const video = $('#video')[0];
            const canvas = $('#canvas')[0];
            const context = canvas.getContext('2d');
            context.drawImage(video, 0, 0, canvas.width, canvas.height);
            const imageData = canvas.toDataURL('image/png');
            $('#photo').attr('src', imageData);
            $('.save-photo').data('image', imageData).show();
            $('.image-body').show();
        });

        $(document).on('click', '.cancel-camera', function () {
            stopCamera();
            resetCameraBox();
        });

        $(document).on('click', '.save-photo', function () {
            const imageData = $(this).data('image');
            if (!imageData || currentIndex === null) {
                alert('No image to save');
                return;
            }

            // Find the row by currentIndex
            const $row = $(`.daily-report-input-body tr`).eq(currentIndex);

            // Remove existing hidden input if present
            $row.find('.captured-image-input').remove();

            // Create new hidden input
            const hiddenInput = $('<input>', {
                type: 'hidden',
                name: `captured_image[${currentIndex}]`,
                class: 'captured-image-input',
                value: imageData
            });

            $row.append(hiddenInput);

            // Optional feedback
            toastr.success('Image saved to row ' + currentIndex);

            // Cleanup
            $('#photo').attr('src', '');
            $('.save-photo').hide().removeData('image');
            $('.image-body').hide();
        });

        // Helpers
        function stopCamera() {
            if (videoStream) {
                let tracks = videoStream.getTracks();
                tracks.forEach(track => track.stop());
                $('#video')[0].srcObject = null;
                videoStream = null;
            }
        }

        function resetCameraBox() {
            $('#camera-box').hide();
            $('#photo').attr('src', '');
            $('.save-photo').hide().removeData('image');
        }


        $(document).on("change", ".project_id", function(e) {
            var project = $(this).val();
            var next = $(this).data('next');

            $('body').addClass('no-loader');

            if(project){
                $.ajax({
                    url: "{{ route('find-project-task') }}",
                    type: "post",
                    cache: false,
                    data: {
                        _token: '{{ csrf_token() }}',
                        project: project,
                    },
                    success: function(response) {
                        $(".task_id").html(response);
                         if (next) {
                            setTimeout(() => {
                                $(next).focus();
                            }, 200);
                        }
                    }
                });
            }
        });

        $(document).on("change", ".task_id", function(e) {
            $('body').addClass('no-loader');
            var task_id = $(this).val();
            if(task_id){
                $.ajax({
                    url: "{{ route('find-project-task-item') }}",
                    type: "post",
                    cache: false,
                    data: {
                        _token: '{{ csrf_token() }}',
                        task_id: task_id,
                    },
                    success: function(response) {
                        $(".item_id").empty().append(response);
                    }
                });
            }
        });

        $(document).on('keydown', 'input, select, textarea', function (e) {
            const form = $('.daily-report-form');

            // ✅ Shift + Enter → go to previous field
            if (e.key === 'Enter' && e.shiftKey) {
                const prevSelector = $(this).data('pre');
                if (prevSelector) {
                    e.preventDefault();
                    $(prevSelector).first().focus();
                }
            }

            // ✅ Enter → go to next field
            else if (e.key === 'Enter' && !e.ctrlKey && !e.altKey) {
                const nextSelector = $(this).data('next');
                if (nextSelector) {
                    e.preventDefault();

                    if (nextSelector === 'save') {
                        form.find('button[type="submit"]').focus(); // or form.submit()
                    } else {
                        const next = $(nextSelector).first();
                        if (next.length) {
                            next.focus();

                            // If next is select2, open it
                            if (next.hasClass('common-select2')) {
                                next.select2('open');
                            }
                        }
                    }
                }
            }

            // ✅ Ctrl + S → Save
            else if (e.ctrlKey && e.key.toLowerCase() === 's') {
                e.preventDefault();
                if (form) {
                    form.requestSubmit();
                }
            }

            // ✅ Alt + → Go to next tab
            else if (e.altKey && e.key === 'ArrowRight') {
                e.preventDefault();
                const nextTabId = activeTab.data('next');
                const focusSelector = activeTab.data('focus1');

                if (nextTabId) {
                    $(nextTabId).click();
                    setTimeout(() => {
                        $(focusSelector).focus();
                    }, 200);
                }
            }

            // ✅ Alt + ← Go to previous tab
            else if (e.altKey && e.key === 'ArrowLeft') {
                e.preventDefault();
                const prevTabId = activeTab.data('pre');
                const focusSelector = activeTab.data('focus');

                if (prevTabId) {
                    $(prevTabId).click();
                    setTimeout(() => {
                        $(focusSelector).focus();
                    }, 200);
                }
            }
        });

        $(document).on('click', '.detail-button', function () {
            const url = $(this).data('url');
            // alert(url);
            if (url) {
                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function (response) {
                        console.log(response);
                        $('#voucherPreviewShow1').html(response);
                        $('#voucherPreviewModal1').modal('show');
                    },
                    error: function () {
                        toastr.error('Failed to load details.');
                    }
                });

            }
        });

        $(document).on('mouseenter', '.datepicker', function(){
            $(this).datepicker({
                changeMonth: true,
                changeYear: true,
                yearRange: "-1000:+1000",
                dateFormat: "dd/mm/yy",
            });
        })

        $(document).on('click', '.delete-row', function(){
            const row = $(this).closest('tr');
            const form = row.closest('.daily-report-form');
            row.remove();

            updateAddButton(form);
        })
    </script>
@endpush
