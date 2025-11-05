<section id="widgets-Statistics" class="m-1 ">
    <div class="row">
        <div class="col-12 profit-center-form">
           <form action="{{ route('new-project.store') }}" method="POST">
                @csrf
                <p class="mb-1">🛠️ This module allows you to add multiple projects at once for faster data entry.</p>

                <div id="projectForms">
                    @php
                    $index = 0;
                    $contract_value = $project->contract_value ?? 0;
                    $vat = $project->vat ?? 0;
                    $variation = $project->variation ?? 0;
                    $total_contract = $contract_value + $vat + $variation;
                    @endphp

                    <div class="project-form border rounded p-1" data-index="{{ $index }}">
                        <input type="hidden" name="projects[{{ $index }}][id]" value="{{ $project->id }}">

                        <div class="row">
                            <!-- Row 1 -->
                            <div class="col-md-4">
                                <label>Project Name</label>
                                <input type="text" name="projects[{{ $index }}][name]" class="form-control"
                                    value="{{ $project->name }}" required>
                            </div>
                            <div class="col-md-4">
                                <label>Owner Name</label>
                                <select name="projects[{{ $index }}][party_id]" class="form-control common-select3">
                                    <option value="">Select...</option>
                                    @foreach ($pInfos as $item)
                                    <option value="{{ $item->id }}" {{ $item->id == $project->party_id ? 'selected' : '' }}>{{
                                        $item->pi_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label>Company Name</label>
                                <select  name="projects[{{ $index }}][company_id]" class="form-control common-select3">
                                    <option value="">Select...</option>
                                    @foreach ($subsidiarys as $subsidiary)
                                    <option value="{{ $subsidiary->id }}" {{ $subsidiary->id == $project->company_id ? 'selected' : '' }}>{{ $subsidiary->company_name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-1">
                                <label>Plot#</label>
                                <input type="text" name="projects[{{ $index }}][plot]" class="form-control"
                                    value="{{ $project->plot }}">
                            </div>
                            <div class="col-md-2">
                                <label>Location</label>
                                <input type="text" name="projects[{{ $index }}][location]" class="form-control"
                                    value="{{ $project->location }}" required>
                            </div>
                            <div class="col-md-2">
                                <label>Project No</label>
                                <input type="text" name="projects[{{ $index }}][project_no]" class="form-control"
                                    value="{{ $project->project_no }}" required>
                            </div>
                            <div class="col-md-2">
                                <label>Project Type</label>
                                <input type="text" name="projects[{{ $index }}][project_type]" class="form-control"
                                    value="{{ $project->project_type }}" required>
                            </div>

                            <!-- Row 2 -->
                            {{-- <div class="col-md-2">
                                <label>Project Code</label>
                                <input type="text" name="projects[{{ $index }}][project_code]" class="form-control"
                                    value="{{ $project->project_code }}" required>
                            </div> --}}

                            <div class="col-md-2">
                                <label>Engineer</label>
                                <input type="text" name="projects[{{ $index }}][engineer]" class="form-control"
                                    value="{{ $project->engineer }}">
                            </div>
                            <div class="col-md-2">
                                <label>Short Name</label>
                                <input type="text" name="projects[{{ $index }}][short_name]" class="form-control"
                                    value="{{ $project->short_name }}">
                            </div>

                            <!-- Row 3 -->
                            <div class="col-md-2">
                                <label>Consultant</label>
                                <input type="text" name="projects[{{ $index }}][consultant]" class="form-control"
                                    value="{{ $project->consultant }}">
                            </div>
                            <div class="col-md-2">
                                <label>Contract Value</label>
                                <input type="number" step="any" name="projects[{{ $index }}][contract_value]"
                                    class="form-control contract_value-edit" value="{{ $contract_value }}">
                            </div>
                            <div class="col-md-2">
                                <label>VAT</label>
                                <input type="number" step="any" name="projects[{{ $index }}][vat]" class="form-control vat-edit"
                                    value="{{ $vat }}">
                            </div>
                            <div class="col-md-2">
                                <label>Variation</label>
                                <input type="number" step="any" name="projects[{{ $index }}][variation]"
                                    class="form-control variation-edit" value="{{ $variation }}">
                            </div>
                            <div class="col-md-2">
                                <label>Total Contract</label>
                                <input type="number" step="any" name="projects[{{ $index }}][total_contract]"
                                    class="form-control total_contract-edit"
                                    value="{{ number_format($total_contract, 2, '.', '') }}" readonly>
                            </div>
                            <div class="col-md-2">
                                <label>Estimation</label>
                                <input type="text" name="projects[{{ $index }}][estimation]" class="form-control"
                                    value="{{ $project->estimation }}">
                            </div>

                            <!-- Row 4 -->
                            <div class="col-md-2">
                                <label>PS Budget</label>
                                <input type="text" name="projects[{{ $index }}][ps_budget]" class="form-control"
                                    value="{{ $project->ps_budget }}">
                            </div>
                            <div class="col-md-2">
                                <label>Status</label>
                                <select name="projects[{{ $index }}][status]" class="form-control">
                                    <option {{ $project->status == 'Planned' ? 'selected' : '' }}>Planned</option>
                                    <option {{ $project->status == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                                    <option {{ $project->status == 'Completed' ? 'selected' : '' }}>Completed</option>
                                    <option {{ $project->status == 'Hold On' ? 'selected' : '' }}>Hold On</option>
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label>Insurance</label>
                                <input type="text" name="projects[{{ $index }}][insurance]" class="form-control"
                                    value="{{ $project->insurance }}">
                            </div>
                            <div class="col-md-2">
                                <label>Contract</label>
                                <input type="text" name="projects[{{ $index }}][contract]" class="form-control"
                                    value="{{ $project->contract }}">
                            </div>
                            <div class="col-md-2">
                                <label>Contract Period</label>
                                <input type="text" name="projects[{{ $index }}][contract_period]" class="form-control"
                                    value="{{ $project->contract_period }}">
                            </div>

                            <!-- Row 5 -->
                            <div class="col-md-2">
                                <label>Area</label>
                                <input type="text" name="projects[{{ $index }}][area]" class="form-control"
                                    value="{{ $project->area }}">
                            </div>
                            <div class="col-md-2">
                                <label>File No</label>
                                <input type="text" name="projects[{{ $index }}][file_no]" class="form-control"
                                    value="{{ $project->file_no }}">
                            </div>
                            <div class="col-md-2">
                                <label>Start Date</label>
                                <input type="text" name="projects[{{ $index }}][start_date]" class="form-control datepicker"
                                    value="{{date('d/m/Y' , strtotime($project->start_date)) }}">
                            </div>
                            <div class="col-md-2">
                                <label>Deadline</label>
                                <input type="text" name="projects[{{ $index }}][deadline]" class="form-control datepicker"
                                    value="{{date('d/m/Y' , strtotime($project->end_date)) }}">
                            </div>
                            <div class="col-md-2">
                                <label>Date</label>
                                <input type="text" name="projects[{{ $index }}][date]" class="form-control datepicker" value="{{date('d/m/Y' , strtotime($project->date)) }}">
                            </div>

                            <div class="col-md-2">
                                <label>Mobile No</label>
                                <input type="text" name="projects[{{ $index }}][mobile_no]" class="form-control" value="{{ $project->mobile_no }}">
                            </div>
                            <div class="col-md-2">
                                <label>Details</label>
                                <input type="text" name="projects[{{ $index }}][details]" class="form-control" value="{{ $project->details }}">
                            </div>
                            <div class="col-md-2">
                                <label>Handover On</label>
                                <input type="text" name="projects[{{ $index }}][handover_on]" class="form-control datepicker"
                                    value="{{date('d/m/Y' , strtotime($project->handover_on)) }}">
                            </div>
                        </div>
                    </div>
                </div>
                @if(Auth::user()->hasPermission('ProjectManagement_Edit'))
                <div class="d-flex justify-content-center align-items-center">
                    <button type="submit" class="btn btn-primary mt-1">Save</button>
                </div>
                @endif
            </form>
        </div>
    </div>
</section>
