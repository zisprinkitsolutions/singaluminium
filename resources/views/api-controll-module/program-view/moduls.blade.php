
@extends('layouts.backend.app')
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.0/css/toastr.css" rel="stylesheet" />
@section('content')
    @include('layouts.backend.partial.style')
    <style>
        .changeColStyle span {
            min-width: 16%;
        }
        .changeColStyle .select2-container--default .select2-selection--single .select2-selection__arrow b {
            display: none;
        }
        .journaCreation {
            background: #1214161c;
        }

        .transaction_type {
            padding-right: 5px;
            padding-left: 5px;
            padding-bottom: 5px;
        }

        @media only screen and (max-width: 1500px) {
            .custome-project span {
                max-width: 140px;
            }
        }

        thead {
            background: #34465b;
            color: #fff !important;
        }

        th {
            color: #fff !important;
            font-size: 11px !important;
            height: 25px !important;
            text-align: center !important;
        }

        td {
            font-size: 12px !important;
            height: 25px !important;
        }

        .table-sm th,
        .table-sm td {
            padding: 0rem;
        }

        .card-body {
            flex: 1 1 auto;
            min-height: 1px;
            padding: 0rem !important;
        }

        .card {
            margin-bottom: 0rem;
            box-shadow: none;
        }
        .dataTables_wrapper .dataTables_filter input {

            padding: 0px !important;

        }
        div.dataTables_wrapper div.dataTables_filter, div.dataTables_wrapper div.dataTables_length {
            margin: 0rem 0 !important;
        }
        .list-group-item {
        position: relative;
        display: block;
        padding: 0px !important;
        margin-left: 20px;
        color: #596F88;
        background-color: #FFFFFF;
        border: none;
    }
    .list-group .list-group-item i {
        font-size: 12px !important;
    }
    </style>
<div class="app-content content print-hideen">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-body">
            {{-- @include('clientReport.sales._header',['activeMenu' => 'list']) --}}
            <div class="nav nav-tabs master-tab-section print-hideen" id="nav-tab" role="tablist">

                <a href="{{route("requirement-list")}}" class="nav-item nav-link " role="tab" aria-controls="nav-contact" aria-selected="false">
                    <div class="master-icon text-cente">
                        <img src="{{asset('icon/invoice.png')}}" alt="" srcset="" class="img-fluid" width="50">
                    </div>
                    <div>  UPDATE LIST </div>
                </a>
                <a href="{{route("moduls-list")}}" class="nav-item nav-link active" role="tab" aria-controls="nav-contact" aria-selected="false" id="mJournalAuthorizationSection">
                    <div class="master-icon text-cente">
                        <img src="{{asset('icon/list.png')}}" alt="" srcset="" class="img-fluid" width="50">
                    </div>
                    <div>Modules</div>
                </a>

            </div>
            <div class="tab-content journaCreation">
                <div id="journaCreation" class="tab-pane bg-white active">
                    <div class="py-1 px-1">
                        {{-- @include('clientReport.sales._subhead_sale_list', [
                            'activeMenu' => 'tax-invoice',
                        ]) --}}
                    </div>
                    <section id="widgets-Statistics" style="padding-left: 8px; padding-bottom: 10px;" >
                            <div class="col-md-12">
                                <div class=" ">
                                    <div class="cardStyleChange" >
                                        <div class="card-body bg-white" id="data_data">

                                        </div>
                                    </div>

                                </div>
                            </div>

                        </form>
                    </section>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Update Form -->
<div class="modal fade bd-example-modal-lg" id="editRequirementModal" tabindex="-1" role="dialog" aria-labelledby="modalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <!-- Modal Header -->
            <section class="border-bottom" style="padding: 10px 15px; background: #364a60; color: #fff;">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 id="modalTitle" style="margin: 0;">Add Comment</h5>
                    <a href="#" class="close btn-icon btn btn-danger" data-dismiss="modal" aria-label="Close" style="padding: 5px;" title="Close">
                        <span aria-hidden="true"><i class='bx bx-x'></i></span>
                    </a>
                </div>
            </section>

        </div>
    </div>
</div>

@endsection

@push('js')

<script>
    $(document).ready(function () {
        // Fetch data via AJAX
        const api_url_ = $('#api_url_moduls').val();
        const api_endpoint = $('#api_url').val();
        const company_name_api = $('#company_name_api').val();

        function load_data() {
            $.ajax({
                url: api_url_,
                method: 'GET',
                success: function (response) {
                    const data = response.data; // Get the data array from the API response
                    let content = '';

                    // Loop through the main data array
                    data.forEach((agreementModule, index) => {
                        content += `
                        <div class="row mb-4">
                            <div class="col-12 ">
                            <h4>Provided module as described below</h4>

                                <div class="tree-container" >
                                    <div class="row">
                        `;

                        // Loop through the items inside each agreement module
                        agreementModule.items.forEach((item, key) => {
                            const sub_modules = item.sub_module.split(','); // Split sub-modules into an array

                            content += `
                            <div class="col-3 mt-1">
                                <div class="tree-node">
                                    <div class="node-header">
                                        <!-- Toggle Button -->
                                        <button class="btn btn-sm toggle-btn p-0" data-target=".ul-${index}-${key}">
                                            <i class="fas fa-minus-circle"></i>
                                        </button>
                                        <strong>${item.module} </strong>
                                    </div>
                                    <!-- Sub-modules -->
                                    <div class="node-children ul-${index}-${key}"">
                                        <ul class="list-group">
                            `;

                            // Add sub-module items
                            sub_modules.forEach(sub_module => {
                                content += `
                                    <li class="list-group-item">
                                        <i class="fas fa-arrow-right"></i> ${sub_module.trim()}
                                    </li>
                                `;
                            });

                            content += `
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            `;
                        });
                        content += ` <div class="col-sm-12 col-12" style="border-top: 1px solid #a6a6a6; padding-top: 10px; margin-top: 25px;">
                                <div> <strong style="text-transform: uppercase;font-size: 14px;"> Zisprink Comments: </strong> ${agreementModule.provider_comment}</div>
                            </div>
                            <div class="col-sm-12 col-12" style="padding-bottom:10px">
                                <div > <strong style="text-transform: uppercase;font-size: 14px;"> ${company_name_api} Comments: </strong> <span class="comment client-comment" style="cursor:pointer" >${agreementModule.client_coment || 'No Comment Found Yet'} </span> <input class="dataupdate" style="margin: 0px; padding: 10px; border: none; outline-color: #fdfdfd00; width:100%;border: 1px solid #d0caca;border-radius: 5px;" name='comment' data-id="${agreementModule.id}" placeholder="Write your comment here."> </div>
                            </div>`
                        content += `
                                    </div>
                                </div>
                            </div>
                        </div>
                        `;
                    });

                    // Append the generated HTML to the target div
                    $('#data_data').html(content);

                    // Add toggle functionality
                    $(document).on('click', ".toggle-btn", function () {
                        var target = $(this).data("target");
                        $(target).toggle();

                        var icon = $(this).find("i");
                        if ($(target).is(":visible")) {
                            icon.removeClass("fa-plus-circle").addClass("fa-minus-circle");
                        } else {
                            icon.removeClass("fa-minus-circle").addClass("fa-plus-circle");
                        }
                    });
                },
                error: function (err) {
                    const error = err.responseJSON;
                    if (error && error.errors) {
                        $.each(error.errors, function (index, value) {
                            toastr.error(value);
                        });
                    } else {
                        toastr.error('An unexpected error occurred.');
                    }
                }
            });
        }

        load_data(); // Call the function to fetch and render data



        $(document).on("change", ".dataupdate", function (e) {
            e.preventDefault();
            var id = $(this).data('id');
            var comment = $(this).val();

            $.ajax({
                url: api_endpoint + '/api/modules-update',
                method: 'POST',
                data: {id:id,comment:comment},

                success: function (response) {

                    if (response.status == 'success') {
                        toastr.success('Comment added successfully!');
                        $('.comment').show().html(response.value);
                        $('.dataupdate').val('')
                    }else{
                        toastr.error('Something went wrong. Please try again.');

                    }
                },
                error: function (err) {
                    if (err.responseJSON && err.responseJSON.errors) {
                        $.each(err.responseJSON.errors, function (index, errorMessage) {
                            toastr.error(errorMessage);
                        });
                    } else {
                        toastr.error('Something went wrong. Please try again.');
                    }
                }
            });
        });

    });

</script>
@endpush
