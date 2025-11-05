<style>
    .commonSelect2Style span {
        width: 100% !important;
    }

    .select2-container--default.select2-container--open .select2-selection--single .select2-selection__arrow b {
        display: none;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow b {
        display: none;
    }
</style>
<section class="print-hideen border-bottom" style="background-color: #34465b;">
    <div class="row pl-1 pr-1 align-items-center">
        <div class="col-md-6 d-flex align-items-center text-left">
            <h4 class="card-title mb-0" style="font-family:Cambria;font-size: 2rem;color:#fff;"> Subsidiary View</h4>
        </div>
        <div class="col-md-6">
            <div class="d-flex justify-content-end align-items-center">
                {{-- <div class="mIconStyleChange"><a href="#" class="btn btn-icon btn-success"><i
                    class="bx bx-edit"></i></a></div> --}}
                {{-- <div class="mIconStyleChange"><a href="#" onclick="window.print();"
                        class="btn btn-icon btn-secondary"><i class='bx bx-printer'></i></a></div> --}}
                {{-- <div class="mIconStyleChange"><a href="#" onclick="window.print();"
                        class="btn btn-icon btn-primary"><i class='bx bxs-file-pdf'></i></a></div>
                <div class="mIconStyleChange"><a href="#" onclick="window.print();"
                        class="btn btn-icon btn-light"><i class='bx bxs-virus'></i></a></div> --}}
                <div class="mIconStyleChange"><a href="#" class="close btn-icon btn btn-danger"
                        data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i
                                class='bx bx-x'></i></span></a></div>
            </div>
        </div>
    </div>
</section>
@include('backend.tab-file.modal-header-info')
@include('backend.tab-file.style')
<section id="basic-vertical-layouts">
    <div class="row p-1 text-left">
        <!-- Left Column -->
        <div class="col-md-6">
            <ul class="list-group">
                <li class="list-group-item">🏢 <strong>Company Name:</strong> {{ $subsidiary->company_name }}</li>
                <li class="list-group-item">🆔 <strong>TRN No:</strong> {{ $subsidiary->trn_no }}</li>
                <li class="list-group-item">📍 <strong>Address:</strong> {{ $subsidiary->company_address }}</li>

            </ul>
        </div>

        <!-- Right Column -->
        <div class="col-md-6">
            <ul class="list-group">
                <li class="list-group-item">📧 <strong>Email:</strong> {{ $subsidiary->company_email }}</li>
                <li class="list-group-item">📱 <strong>Mobile:</strong> {{ $subsidiary->company_mobile }}</li>
            </ul>
        </div>
    </div>
</section>
<section>
    <div class="d-flex justify-content-center align-items-center print-hideen">
        <div class="mIconStyleChange">
            <a href="#" onclick="window.print();" class="btn btn-icon btn-secondary custom-action-btn" title="Print Now">
                <i class='bx bx-printer'></i> Print
            </a>
        </div>
    </div>
</section>
@include('backend.tab-file.modal-footer-info')

