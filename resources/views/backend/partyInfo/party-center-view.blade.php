<style>
    .table-bordered {
        border: 1px solid #f4f4f4;
    }

    .table {
        width: 100%;
        max-width: 100%;
        margin-bottom: 20px;
    }

    table {
        background-color: transparent;
    }

    table {
        border-spacing: 0;
        border-collapse: collapse;
    }

    .tarek-container {
        width: 85%;
        margin: 0 auto;
        display: grid;
        grid-template-columns: 88% 12%;
        background-color: #ffff;
    }

    .invoice-label {
        font-size: 10px !important
    }

    @media print {

        html,
        body {
            height: 100%;
            overflow: hidden;
        }
    }

    /* ------ table ------ */
    /* striped effect Bootstrap অনুযায়ী */
    .table-striped tbody tr:nth-of-type(odd) {
        background-color: #f9f9f9;
    }



    /* border কালো */
    .table-bordered {
        border: 1px solid #000 !important;
    }

    .table-bordered td,
    .table-bordered th {
        border: 1px solid #000 !important;
        padding: 8px 12px;
        /* 👉 cell space */
    }

    table tbody tr:hover {
        cursor: pointer;
    }

    /* --------- button ------------ */
    /* .custom-btn {
        padding: 6px 10px;
        border-radius: 5px;
        width: 90px;
    } */

    .party-btn {
        padding: 6px 12px;
        /* height একই */
        font-size: 14px;
        min-width: 110px;
        /* প্রস্থ সমান */
        text-align: center;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .party-btn i {
        margin-right: 5px;
        /* আইকন + টেক্সট gap */
    }
</style>
<section class="print-hideen border-bottom" style="padding: 0px 15px;background:#364a60;">
    <div class="row">
        <div class="col-md-6 d-flex align-items-center">
            <h4 class="text-left " style="font-family:Cambria;font-size: 2rem;color:white; margin-bottom:0;">Party Info
            </h4>
        </div>
        <div class="col-md-6">
            <div class="d-flex flex-row-reverse">
                <div class="mIconStyleChange">
                    <a href="#" class="btn-icon btn btn-danger" data-dismiss="modal" aria-label="Close"
                        style="padding-bottom:3px;margin-right: -6px;">
                        <i class='bx bx-x'></i></a>
                </div>
                {{-- <div class="mIconStyleChange">
                    <a href="{{ route('partyInfoEdit', $pInfo->id) }}" class="btn btn-icon btn-success edit-btn"
                        style="padding-bottom:3px;margin-right: -6px;"><i class="bx bx-edit"></i></a>
                </div>
                @if (count($pInfo->effects()) == 0)
                    <div class="mIconStyleChange">
                        <a onclick="event.preventDefault(); deleteAlert(this, 'About to delete party info. Please, confirm?');"
                            href="{{ route('partyInfoDelete', $pInfo) }}" class="btn btn-icon btn-danger"
                            style="padding-bottom:3px;margin-right: -6px;"><i class='bx bx-trash'></i></a>
                    </div>
                @endif --}}
            </div>
        </div>
    </div>
</section>
<section id="widgets-Statistics">
    <div class="row" style="padding: 0 0.8rem;">
        <div class="col-md-12">
            <div class="card-body">
                <div class="row">
                    <div class="table-responsive px-1 pt-1">
                        <table class="table table-bordered table-striped text-left no-hover">
                            <tbody>
                                <tr>
                                    <td width="15%"><strong>Party Code : </strong></td>
                                    <td width="35%">{{ $pInfo->pi_code }}</td>
                                    <td width="15%"><strong>Party Name : </strong></td>
                                    <td width="35%">{{ $pInfo->pi_name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Type : </strong></td>
                                    <td>{{ $pInfo->pi_type }}</td>
                                    <td><strong>
                                            @if (!empty($currency->licence_name))
                                                {{ $currency->licence_name }}
                                            @endif Number
                                            :
                                        </strong></td>
                                    <td>{{ $pInfo->trn_no }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Contact Person : </strong></td>
                                    <td>{{ $pInfo->con_person }}</td>
                                    <td><strong>Contact Number : </strong></td>
                                    <td>{{ $pInfo->con_no }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Phone Number : </strong></td>
                                    <td>{{ $pInfo->phone_no }}</td>
                                    <td><strong>Address : </strong></td>
                                    <td>{{ $pInfo->address }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Email : </strong></td>
                                    <td>{{ $pInfo->email }}</td>
                                    <td><strong>&nbsp;</strong></td>
                                    <td>&nbsp;</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- <div class="btn-group mb-2" role="group" aria-label="Basic mixed styles example">
        <button type="button" class="btn btn-danger">Left</button>
        <button type="button" class="btn btn-warning">Middle</button>
        <button type="button" class="btn btn-success">Right</button>
    </div> --}}

    <div class="container d-flex align-items-center justify-content-center flex-row-reverse">
        @if (count($pInfo->effects()) == 0)
            <a onclick="event.preventDefault(); deleteAlert(this, 'About to delete party info. Please, confirm?');"
                href="{{ route('partyInfoDelete', $pInfo) }}" class="btn btn-icon btn-danger  party-btn">
                <i class='bx bx-trash'></i> Delete
            </a>
        @endif
        <a href="{{ route('partyInfoEdit', $pInfo->id) }}" class="btn btn-icon btn-primary edit-btn party-btn"
                style="margin-right: 5px;">
            <i class="bx bx-edit"></i> Edit
        </a>
    </div>

</section>
@include('backend.tab-file.modal-footer-info')
