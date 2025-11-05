<form class="repeater job-project-tasks" action="{{ route('projects.store') }}" method="post">
    @csrf
    <div class="d-flex">
        <div class="form-group w-100 d-flex flex-column">
            <label for=""> Lpo  Project Name </label>
            <select name="project_name"  class="form-control project_name select2 @error('project_name') is_invalid @enderror">
                <option disabled selected> Select Project</option>
                @foreach ($lpo_projects as $lpo_project)
                    <option value="{{ $lpo_project->project_name }}" data-id={{ $lpo_project->id }}> {{ $lpo_project->project_name }}</option>
                @endforeach
            </select>

            <input type="hidden" name="lpo_projects_id" value="{{ $lpo_project->id}}">
            <input type="hidden" name="lpo_projects_budget" value="{{ $lpo_project->total_budget}}">
        </div>

        <div class="form-group w-100 ml-1 d-flex flex-column">
            <label for=""> Customer Name </label>
            <select name="customer_id" class="form-control select2 customer_id @error('customer_id') is-invalid @enderror">
                <option  selected disabled> Select Customer </option>
                @foreach ($customers as $customer)
                    <option value="{{ $customer->id }}" class="customer_{{$customer->id}}"> {{ $customer->pi_name }} </option>
                @endforeach
            </select>
            @error('customer_id')
                <p class="text-danger"> {{ $message }}</p>
            @enderror
        </div>

    </div>

    <div class="d-flex">
        <div class="form-group w-100">
            <label for=""> Desctiption  </label>
            <textarea name="project_description"  cols="30" rows="2" placeholder="Description max 200 characters"
            class="form-control project_description  @error('project_description') is-invalid @enderror">{{ old('project_description') }}</textarea>
            @error('project_description')
                <p class="text-danger"> {{ $message }}</p>
            @enderror
        </div>

        <div class="ml-1 d-flex w-100">
            <div class="form-group">
                <label for=""> Estimated Starting Date </label>
                <input type="text" name="start_date" class="date start_date form-control @error('start_date') is-invalid @enderror" value="" autocomplete="off">
                @error('start_date')
                <p class="text-danger"> {{ $message }}</p>
            @enderror
            </div>

            <div class="form-group ml-1">
                <label for=""> Estimated End Date </label>
                <input type="text" name="end_date" class="date end_date form-control @error('end_date') is-invalid @enderror" value="" autocomplete="off">
                @error('end_date')
                <p class="text-danger"> {{ $message }}</p>
            @enderror
            </div>
        </div>
    </div>

    <div class="job-project-tasks">
        <div class="d-flex justify-content-between align-items-center mb-1">
            <h2 class="tasks-title"> Project Tasks </h2>
            {{-- <button type="button" class="add_items project-btn"> Add </button> --}}
        </div>

        <table class="auto-index repeater1 table table-sm">
            <thead>
                <tr>
                    <th class="text-center"> S.NO </th>
                    <th> Task Name </th>
                    <th> Description </th>
                    <th class="text-center"> Unit </th>
                    <th class="text-center"> Qty </th>
                    <th class="text-center"> Rate </th>
                    <th class="text-center"> Discount </th>
                    <th class="text-center"> Amount ({{$currency->symbole}}) </th>
                    {{-- <th class="text-center"> Action </th> --}}
                </tr>
            </thead>
            <tbody id="input-container">
                @foreach ($lpo_project->tasks as $key => $task)
                <tr>
                    <td class="text-center">
                        @if($lpo_project->invoice_type == 'task_base')
                            <input type="checkbox" class="task_checkbox" name="invoice_tasks[{{$key}}]" value="{{$task->id}}" checked>
                            <input type="hidden" value="{{$task->id}}" name="task_id[{{$key}}]">
                         @endif
                     </td>
                    <td style="width: 20%">
                        <input type="text" name="task_name[{{$key}}]" class="form-control @error('task_name') is-invalid @enderror" required autocomplete="off" value="{{$task->task_name}}" readonly>
                    </td>

                    <td style="width: 35%">
                        <textarea name="description[{{$key}}]"  cols="30" rows="1" class="form-control" readonly required>{{$task->description}}</textarea>
                    </td>

                    <td>
                        <input type="text" name="unit[{{$key}}]" class="form-control text-center unit" required readonly value="{{$task->unit}}">
                    </td>
                    <td>
                        <input type="number" name="qty[{{$key}}]" class="form-control text-center qty" required readonly  value='{{$task->qty}}' >
                    </td>
                    <td>
                        <input type="number" name="rate[{{$key}}]" class="form-control text-center rate" required readonly value='{{$task->rate}}' >
                    </td>
                    <td>
                        <input type="number" name="discount[{{$key}}]" class="form-control text-center rate" required readonly value='{{$task->discount}}' >
                    </td>
                    <td>
                        <input type="number" step="any" name="amount[{{$key}}]" class="form-control amount text-center" required readonly value="{{$task->amount}}">
                    </td>

                    {{-- <td class="text-center">
                        <button  type="button" class="delete_items project-btn"> <i class="bx bx-trash"></i> </button>
                    </td> --}}
                </tr>
                @endforeach

            </tbody>
            <tbody>

                    <tr>
                        <td class="text-center d-none"> </td>
                        <td  colspan="7" class="text-right"> <span class="mr-1">  Total  </span> </td>
                        <td  colspan="1"> <input type="number" name="total" readonly step="0.01" class="form-control text-center total value-shoe-total" value="{{$lpo_project->budget}}">  </td>
                    </tr>


                    <tr>
                        <td class="text-center d-none"> </td>
                        <td  colspan="7" class="text-right"> <span class="mr-1">  Discount </span> </td>
                        <td  colspan="1"> <input type="number" name="discount"  step="0.01" class="form-control text-center discount show-discount-value"  readonly value="{{$lpo_project->discount}}">  </td>
                    </tr>


                    <tr>
                        <td class="text-center d-none"> </td>
                        <td  colspan="7" class="text-right"> <span class="mr-1"> Total Amount ({{$currency->symbole}}) </span> </td>
                        <td  colspan="1"> <input type="number" name="total_amount" step="0.01" class="form-control text-center total_amount tatal_amount_show" value="{{$lpo_project->total_budget}}" readonly>  </td>
                    </tr>


                    <tr>
                        <td class="text-center d-none"> </td>
                        <td  colspan="7" class="text-right"> <span class="mr-1"> Advance Amount ({{$currency->symbole}}) </span> </td>
                        <td  colspan="1"> <input type="number" name="advance_amount" step="0.01" class="form-control text-center advance_amount advance_show" max="{{$lpo_project->total_budget}}" min="1" value="" placeholder="advance amount">  </td>
                    </tr>

                    <tr class="pay-mode-part" style="display:none">
                        <td class="text-center d-none"> </td>
                        <td  colspan="7" class="text-right"> <span class="mr-1"> Payment Mode  </span> </td>
                        <td  colspan="1">
                            <select name="payment_mode" id="payment_mode" class="form-control">
                                @foreach (\App\Paymode::whereIn('id',[1,3])->get() as $paymode)
                                    <option value="{{$paymode->title}}" {{$paymode->title=='Card'?'selected':''}}> {{$paymode->title}} </option>
                                @endforeach
                            </select>
                        </td>
                    </tr>
            </tbody>
        </table>
        <div class="d-flex justify-content-end mt-1">
            <button type="submit" class="project-btn save-btn"> Save </button>
        </div>
    </div>
</form>
