<style>
    html,
    body {
        height: 100%;
    }

    thead {
        background: #34465b;
        color: #fff !important;
        height: 30px;
    }
    .receipt-bg{
        display: none;
    }
    @media print{
        .receipt-bg{
            display: block;
        }
    }

    /* ------- css -------- */
    /* shobar jonno border */
    .table-bordered td,
    .table-bordered th {
        border: 1px solid black !important;
        padding: 10px !important; /* cell padding off */
        border-collapse: collapse;
    }

    /* hover effect off */
    .no-hover tbody tr:hover {
        background-color: transparent !important;
    }
    /* remove table spacing issues */
    .single-border {
        border-spacing: 0;
        border-collapse: collapse;
    }
</style>

<section class="print-hideen border-bottom" style="background: #364a60;">
    <div class="d-flex flex-row-reverse" style="padding-top: 5px;padding-right: 8px;">
        <div class="pr-1" style="margin-top: 5px;">

            <a href="#" class="btn-icon btn btn-danger " data-dismiss="modal" aria-label="Close"><span
                    aria-hidden="true"><i class='bx bx-x' style="padding-bottom: 3px; top:3px !important"></i></span></a>
        </div>

        @if ($masterAcc->office_id !=0)
            <div class="" style="margin-top: 5px;padding-right: 3px;">
                <a href="{{ route('masterDelete',$masterAcc) }}"  onclick="event.preventDefault(); deleteAlert(this, 'About to delete account head. Please, confirm?');"
                 class="btn btn-sm btn-icon btn-warning" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Delete"><i class="bx bx-trash"></i></a>
            </div>

            <div class="" style="margin-top: 5px;padding-right: 3px;">
                <a href="{{ route('masterEdit',$masterAcc) }}" class="btn btn-sm btn-icon btn-primary" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Edit"><i class="bx bx-edit"></i></a>
            </div>
        @endif

        {{-- <div class="mr-1"><a href="#" onclick="window.print();" class="btn btn-icon btn-light"><i class='bx bxs-virus'></i></a></div> --}}
        <div class="mr-1 w-100 pl-2 text-left" style="margin-top: 5px;">
            <h4 style="font-family:Cambria;font-size: 2rem;color:white;"> Master Account Head </h4>
        </div>
    </div>
</section>
<section>
    <div class="receipt-voucher-hearder invoice-view-wrapper" style=" border: 1px solid; margin: 50px 20px; border-radius: 20px;">
        @include('layouts.backend.partial.modal-header-info')
    </div>

</section>
{{-- <section id="widgets-Statistics">
    <div class="payment-voucher " style="margin: 20px; border-radius: 20px;">
        <p> <strong>Code: </strong> {{$masterAcc->mst_ac_code}}</p>
        <p> <strong>Head: </strong> {{$masterAcc->mst_ac_head}}</p>
        <p> <strong>Defination:</strong> {{$masterAcc->mst_definition}}</p>
        <p> <strong>Account Code: </strong> {{$masterAcc->mst_ac_type}}</p>
    </div>

    <div class="divFooter mb-1 ml-1 footer-margin invoice-view-wrapper">
        Business Software Solutions by
        <span style="color: #0005" class="spanStyle"><img class="img-fluid" src="{{ asset('img/zikash-logo.png')}}" alt="" width="150"></span>
    </div>
</section> --}}
<section id="widgets-Statistics">
    <div class="payment-voucher text-left" style="margin: 20px;">
        <div class="table-responsive">
            <table class="table table-bordered mb-0 no-hover single-border">
                <tbody>
                    <tr>
                        <td style="width:12%"><strong>Code:</strong></td>
                        <td>{{ $masterAcc->mst_ac_code }}</td>
                        <td style="width:12%"><strong>Head:</strong></td>
                        <td>{{ $masterAcc->mst_ac_head }}</td>
                    </tr>
                    <tr>
                        <td><strong>Definition:</strong></td>
                        <td>{{ $masterAcc->mst_definition }}</td>
                        <td><strong>Account Code:</strong></td>
                        <td>{{ $masterAcc->mst_ac_type }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="divFooter mb-1 ml-1 footer-margin invoice-view-wrapper" style="text-align: center; padding: 10px;">
        Business Software Solutions by
        <span style="color: #0005" class="spanStyle">
            <img class="img-fluid" src="{{ asset('img/zikash-logo.png')}}" alt="Zikash Logo" width="150">
        </span>
    </div>
</section>




