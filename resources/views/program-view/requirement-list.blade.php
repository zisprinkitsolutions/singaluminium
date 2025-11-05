
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
        div.dataTables_length {
            margin: 0rem 0;
        }
        div.dataTables_wrapper div.dataTables_filter, div.dataTables_wrapper div.dataTables_length {
            margin: 0rem 0 !important;
        }
    </style>
<div class="app-content content print-hideen">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-body">
            {{-- @include('clientReport.sales._header',['activeMenu' => 'list']) --}}
            <div class="nav nav-tabs master-tab-section print-hideen" id="nav-tab" role="tablist">

                <a href="{{route("requirement-list")}}" class="nav-item nav-link active" role="tab" aria-controls="nav-contact" aria-selected="false">
                    <div class="master-icon text-cente">
                        <img src="{{asset('icon/invoice.png')}}" alt="" srcset="" class="img-fluid" width="50">
                    </div>
                    <div>  UPDATE LIST </div>
                </a>
                <a href="{{route("moduls-list")}}" class="nav-item nav-link " role="tab" aria-controls="nav-contact" aria-selected="false" id="mJournalAuthorizationSection">
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
        const api_url_ = $('#api_url_list').val()
        const api_endpoint = $('#api_url').val()

        function load_data(){
            $.ajax({
                url: api_url_,
                method: 'GET',
                success: function (response) {
                    const requirements = response.data;
                    let tableRows = '';

                    // Build table rows dynamically
                    requirements.forEach(client_requirement => {
                        const rawDate = new Date(client_requirement.date);
                        const formattedDate = `${rawDate.getDate().toString().padStart(2, '0')}/${
                            (rawDate.getMonth() + 1).toString().padStart(2, '0')
                        }/${rawDate.getFullYear()}`;

                        const statusStyle = client_requirement.status === 'DELIVERED'
                            ? 'background: #569b05; color: #fff;'
                            : 'background: #ff6a6a; color: #fff;';

                        tableRows += `
                        <tr style="height: 20px !important;" class="p-0 m-0 text-center">
                            <td class="text-center pt-0 mt-0 pb-0 mt-0 ml-1">${formattedDate}</td>
                            <td class="text-left pt-0 mt-0 pb-0 mb-0" ml-1>${client_requirement.requirement}</td>
                            <td class="text-left pt-0 mt-0 pb-0 mb-0 comment" title="On double click here,you can write comments"> <span class="comment-hide"> ${client_requirement.comment ?? 'Double-Click to Write Your Comment'}</span>
                            <input class="dataupdate" style="display:none; margin: 0px; padding: 5px; border: none; outline-color: #fdfdfd00;" name='comment' data-id="${client_requirement.id}" placeholder="Write your comment here.">  </td>
                            <td class="text-left pt-0 mt-0 pb-0 mb-0">${client_requirement.our_comment ?? 'No Comment Found Yet'}
                            </td>
                            <td style="${statusStyle}">${client_requirement.status}</td>
                        </tr>
                        `;
                    });

                    // Create table structure
                    const table = `
                        <table class="table table-bordered table-sm table-striped myTable" id="requirement-table" style="font-size:12px">
                            <thead>
                                <tr class="text-center">
                                    <th class="p-0 m-0 text-center" style="width: 10%">DATE</th>
                                    <th class="p-0 m-0 text-center"  style="width: 50%">UPDATE REQUEST </th>
                                    <th class="="p-0 m-0 text-center"  style="width: 20%">COMMENT</th>
                                    <th class="="p-0 m-0 text-center"  style="width: 20%">ZISPRINK COMMENT</th>
                                    <th class="p-0 m-0 text-center" >STATUS</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${tableRows}
                            </tbody>
                        </table>
                    `;

                    // Append the table to the DOM
                    $('#data_data').html(table);

                    $('#requirement-table').DataTable({
                            "paging": true,
                            "searching": true,
                            "lengthChange": true,
                            "pageLength": 20,
                            "lengthMenu": [
                                [10, 20, 25, 50, 100, 300, 500, 700, 900, 1100, 1300, 1500, 1700, 1900, 2100, 2300,
                                    2500, 2700, 3000, -1
                                ],
                                [10, 20, 25, 50, 100, 300, 500, 700, 900, 1100, 1300, 1500, 1700, 1900, 2100, 2300,
                                    2500, 2700, 3000, "All"
                                ]
                            ],

                            "ordering": false,

                            "language": {
                                "searchPlaceholder": "Search..."
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

        load_data()

         $(document).on("dblclick", ".comment", function (e) {
            e.preventDefault();
            var tr = $(this).closest('tr')
            tr.find('.comment-hide').hide();
            tr.find('.dataupdate').show()
        });

        $(document).on("change", ".dataupdate", function (e) {
            e.preventDefault();
            var id = $(this).data('id');
            var comment = $(this).val();
            var tr = $(this).closest('tr')

            $.ajax({
                url: api_endpoint + '/api/client-requirement-list-update',
                method: 'POST',
                data: {id:id,comment:comment},

                success: function (response) {

                    if (response.status == 'success') {
                        toastr.success('Comment added successfully!');
                        tr.find('.comment-hide').show().html(response.value);
                        tr.find('.dataupdate').hide()
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
