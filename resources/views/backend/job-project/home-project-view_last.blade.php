
<style>
    .custom-search{
        background: #9b9fa3 !important;
        padding: 6px 10px;
    }
    @media print{
        .print-hideen{
            display: none !important;
        }
    }
</style>

<section class="print-hideen border-bottom" style="padding: 5px 15px;background:#364a60;">
    <div class="row">
        <div class="col-md-6"> <h3 style="font-family:Cambria;font-size: 2rem;color:white;">Projects </h3></div>
        <div class="col-md-6">
            <div class="d-flex flex-row-reverse" style="padding-top: 6px;">
                <div class=""><a href="#" class="btn-icon btn btn-danger" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class='bx bx-x'></i></span></a></div>
                {{-- <div class="" style="padding-right: 3px;"><a href="#" onclick="window.print();" class="btn btn-icon btn-success"><i class="bx bx-printer"></i></a></div> --}}
            </div>
        </div>
    </div>
</section>

<div style="margin: 10px 20px;">
    @include('layouts.backend.partial.modal-header-info')
</div>

<section class="p-1" id="widgets-Statistics">
    <div class="row">

        <div class="col-md-7 justify-content-start">
            <form class="search_home_reports" data="project/home-project" method="get" class="">
                <div class="form-group d-flex ">
                    <input type="text" name="search" class="form-control search w-100" style="margin-right: 10px;"
                        placeholder="Search Project">

                    <select name="company_id" class="form-control inputFieldHeight common-select2 ml-1">
                        <option value="">Select...</option>
                        <option value="seabridge" {{$company_id=='seabridge' ? 'selected' : '' }}>
                            SEA BRIDGE BUILDING CONT. LLC</option>
                        @foreach ($subsidiarys as $subsidiary)
                        <option value="{{ $subsidiary->id }}" {{$company_id==$subsidiary->id ?
                            'selected' : '' }}>{{
                            $subsidiary->company_name }}
                        </option>
                        @endforeach
                    </select>
                    <button type="submit" class="project-btn action-btn bg-info ml-1 search_home_reports_btn" title="Search"
                        style="background: #9ba19c;color: white;border: none;border-radius: 5px;">
                        <div class="d-flex">
                            <div class="formSaveIcon">
                                <img src="{{ asset('assets/backend/app-assets/icon/searching-icon.png') }}" width="25">
                            </div>
                            <div><span>Search</span></div>
                        </div>
                    </button>
                </div>
            </form>
        </div>
        <div class="col-md-5 d-flex float-right justify-content-end">
            <div class="">
                <table class="table table-bordered table-sm"
                    style=" font-weight: 500; font-size: 14px;">
                    <thead>
                        <tr
                            style="background-color: #34465b;  font-weight: 600; font-size: 14px;">
                            <th
                                style="color: white !important; font-weight: 600; font-size: 14px;">
                                Total Ongoing Projects</th>
                            <th
                                style="color: white !important; font-weight: 600; font-size: 14px;">
                                Total Contract Amount</th>

                            {{-- <th
                                style="color: white !important; font-weight: 600; font-size: 14px;">
                                Total Taxable Amount</th>
                            <th
                                style="color: white !important; font-weight: 600; font-size: 14px;">
                                Total VAT</th> --}}
                        </tr>
                    </thead>
                    <tbody>
                        <tr style="color: #000; font-weight: 500; font-size: 15px; text-align: right;">
                            <td>{{ $total_ongoing_project }}</td>
                            <td>{{ number_format($total_contact_amount, 2) }}</td>
                            {{-- <td>{{ number_format($total_budget, 2) }}</td>
                            <td>{{ number_format($total_vat, 2) }}</td> --}}
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="data-table table-responsive ">
        <table class="table table-sm">
            <thead style="background-color:#34465b !important; ">
                <tr class="text-center">
                    <th class="text-left" style="color:#fff;"> SI NO</th>
                    <th class="text-left" style="color:#fff;padding-left: 0px;"> Assigned To ..</th>
                    <th class="text-left" style="color:#fff;padding-left: 0px;"> Owner / Party </th>
                    <th style="color:#fff;"> Project No </th>
                    <th class="text-left" style="color:#fff;padding-left: 0px;"> Project</th>
                    <th style="color:#fff;"> PLOT </th>
                    <th style="color:#fff; text-align:left;padding-left: 0px;"> Location </th>

                    <th style="width:10%;color:#fff;" class="text-center"> amount ({{
                        $currency->symbole }})
                        <br>
                        {{number_format($total_budget , 2)}}
                    </th>
                    <th style="min-width: 110px;color:#fff;" class="text-center"> Start Date </th>
                    <th style="min-width: 100px;color:#fff;" class="text-center"> End Date </th>
                    <th style="width:5%;color:#fff;" style="white-space: nowrap" title="Progress">
                        Progress
                    </th>

                    {{-- <th style="width:10%;color:#fff;" class="text-center"> Action </th> --}}
                </tr>
            </thead>
            <tbody>
                @foreach ($projects as $key => $project)


                <tr class="text-center text-uppercase">
                    <td>{{ ($projects->currentPage() - 1) * $projects->perPage() + $key + 1 }}</td>
                    <td class="view-project text-left" data-id="{{ $project->id }}"
                        data-url="{{ route('projects.show', $project->id) }}"
                        data-quotation="{{$project->quotation->project_code??''}}"
                        data-invoice="{{ $project->is_invoice }}"
                        title="{{ $project->company->company_name??'SEA BRIDGE BUILDING CONT. LLC' }}">
                        {{ \Illuminate\Support\Str::limit(@$project->company->company_name, 15) ?? \Illuminate\Support\Str::limit('SEA BRIDGE BUILDING CONT. LLC', 15)}} </td>

                    <td class="view-project text-left" data-id="{{ $project->id }}"
                        data-url="{{ route('projects.show',$project->prospect->id) }}"
                        data-quotation="{{$project->quotation->project_code??''}}"
                        data-invoice="{{ $project->is_invoice }}" title="{{ $project->party->pi_name }}">
                        {{\Illuminate\Support\Str::limit($project->party->pi_name,15) }} </td>

                    <td class="view-project" data-id="{{ $project->id }}" data-url="{{ route('projects.show', $project->id) }}"
                        data-quotation="{{ optional($project->quotation)->project_code ?? '' }}"
                        data-invoice="{{ $project->is_invoice ?? '' }}">
                        {{ optional(optional(optional($project->quotation)->boq)->project)->project_no ?? '' }}
                    </td>

                    <td class="view-project text-left" data-id="{{ $project->id }}" data-url="{{ route('projects.show', $project->id) }}"
                        data-quotation="{{ optional($project->quotation)->project_code ?? '' }}"
                        data-invoice="{{ $project->is_invoice ?? '' }}"
                        title="{{ $project->project_id ? optional($project->new_project)->name ?? '' : $project->project_name }}">
                        {{ \Illuminate\Support\Str::limit($project->project_id ? optional($project->new_project)->name ?? '' :
                        $project->project_name, 25) }}
                    </td>
                    <td class="view-project" style="padding: 0px 15px !important;" data-id="{{ $project->id }}" data-url="{{ route('projects.show', $project->id) }}"
                        data-quotation="{{ optional($project->quotation)->project_code ?? '' }}"
                        data-invoice="{{ $project->is_invoice ?? '' }}">
                        {{ optional(optional(optional($project->quotation)->boq)->project)->plot ?? '' }}
                    </td>
                    <td class="view-project" style="text-align:left;" data-id="{{ $project->id }}"
                        data-url="{{ route('projects.show', $project->id) }}"
                        data-quotation="{{ optional($project->quotation)->project_code ?? '' }}"
                        data-invoice="{{ $project->is_invoice ?? '' }}">
                        {{ optional(optional(optional($project->quotation)->boq)->project)->location ?? '' }}
                    </td>



                    <td style="width:10%;" class="text-center view-project" data-id="{{ $project->id }}"
                        data-url="{{ route('projects.show', $project->id) }}"
                        data-quotation="{{$project->quotation->project_code??''}}"
                        data-invoice="{{ $project->is_invoice }}"> {{
                        number_format($project->total_budget,2) }}
                    </td>
                    <td class="text-center view-project" data-id="{{ $project->id }}"
                        data-url="{{ route('projects.show', $project->id) }}"
                        data-quotation="{{$project->quotation->project_code??''}}"
                        data-invoice="{{ $project->is_invoice }}">
                        {{ $project->start_date?date('d/m/Y', strtotime($project->start_date)):'...'
                        }} </td>
                    <td class="text-center view-project" data-id="{{ $project->id }}"
                        data-url="{{ route('projects.show', $project->id) }}"
                        data-quotation="{{$project->quotation->project_code??''}}"
                        data-invoice="{{ $project->is_invoice }}"> {{
                        $project->end_date?date('d/m/Y', strtotime($project->end_date)) :'...' }}
                    </td>
                    <td style="width:10%" class="view-project" data-id="{{ $project->id }}"
                        data-url="{{ route('projects.show', $project->id) }}"
                        data-quotation="{{$project->quotation->project_code??''}}"
                        data-invoice="{{ $project->is_invoice }}">
                        {{ $project->avarage_complete ?
                        number_format($project->avarage_complete,2,'.','') : 0 }} %
                    </td>

                    {{-- <td class="text-center">
                        <div class="d-flex justify-content-center">
                            <button class="project-btn btn-primary view-project" data-id="{{ $project->id }}"
                                data-url="{{ route('projects.show', $project->id) }}"
                                data-quotation="{{$project->quotation->project_code??''}}"
                                data-invoice="{{ $project->is_invoice }}" title="View"><i class="fa fa-eye"></i>
                            </button>



                            <a href="#" class="project-btn roi-report"
                                data-url="{{route('new.project.roy.report',['project_id' => $project->id, 'print' => false])}}"
                                title="ROI Report" style="margin-left: 0.2rem !important;background-color:#0f648b;"
                                title="Document">
                                <i class="fa fa-file-image" style="font-size:16px"></i>
                            </a>

                            <a href="#" class="project-btn document_upload" data-id="{{ $project->id }}"
                                data-url="{{ $project->project_name }}" title="Document"
                                style="margin-left: 0.2rem !important;background-color:#0ead5e;" title="Document"><i
                                    class="fa fa-file-image" style="font-size:16px"></i>
                            </a>
                        </div>

                    </td> --}}
                </tr>
                @endforeach
                {{-- <tr style=" background-color: #3d4a94 !important;">
                    <td style="color:#fff !important;" colspan="7" class="text-right mr-1"> Total</td>
                    <td style="color:#fff !important;">{{number_format($total_budget , 2)}}</td>
                    <td colspan="3"></td>
                </tr> --}}
            </tbody>
        </table>

        {!! $projects->links() !!}
    </div>
</section>

<div class="d-flex flex-row-reverse justify-content-center mb-2" >
    <div class="print-hideen" >
        <a href="#" onclick="window.print();" class="btn btn-icon btn-secondary custom-action-btn" title="Print Now">
            <i class="bx bx-printer"></i> Print
        </a>
    </div>
</div>

<div class="img receipt-bg invoice-view-wrapper">
    <img src="{{ asset('img/finallogo.PNG') }}" class="img-fluid" style="position: fixed; top: 420px; left: 200px; opacity: 0.2; width: 650px !important; height: 250px;" alt="">

    {{-- <img src="{{ asset('img/finallogo.jpeg') }}" class="img-fluid" style="position: fixed; top:100px; left:0px; opacity:0.1;width:100%; " alt=""> --}}
</div>

