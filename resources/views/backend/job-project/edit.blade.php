@extends('layouts.backend.app')
@push('css')
@include('layouts.backend.partial.style')
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.0/css/toastr.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
<style>
    body{
		    counter-reset: Serial;
	}
    .project-btn{
        border: none;
        color: #fff;
        font-size: 15px;
        font-weight: 500px;
        padding:3px 10px;
        border-radius: 5px;
    }
    .add_items{
        background: #4CB648;
    }
    .delete_items{
        background: #EA5455;
        padding:3px 3px 2px 3px;
        font-size: 13px;
    }
    .auto-index td:first-child:before{
        counter-increment: Serial;      /* Increment the Serial counter */
        content:  counter(Serial);  /* Display the counter */
    }
    .auto-index,.auto-index th,.auto-index td{
        border: 1px solid #ddd;
    }
    .auto-index,.auto-index td{
        border: 1px solid #ddd;
        padding: 0 !important;
        margin: 0 !important;
    }
    #input-container .form-control{
        border: none;
    }
    #input-container .form-control:focus{
        border: 1px solid #4CB648;
    }
    .tasks-title, .budget-title{
        font-size: 16px;
        color: #313131;
        font-weight: 500;
        text-transform: capitalize;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered{
        font-size: 16px !important;
    }

    .select2-container--default .select2-selection--single {
        height: 35px !important;
    }
    .add-customer{
        background: #4A47A3;
        padding:2px 4px !important;
        margin:0 !important;
    }
    .save-btn{
        background: #406343;
    }
    input.form-control{
        height: 35px !important;
    }
    .form-control{
        color: #444;
    }

</style>
@endpush

@section('content')
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-body">
            @include('clientReport.project._header')
            <div class="tab-content bg-white">
                <div id="journaCreation" class="tab-pane active">
                    <section class="p-1" id="widgets-Statistics">
                        <form class="repeater" action="{{ route('projects.update',$project->id) }}" method="post" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="d-flex">
                                <div class="form-group w-100">
                                    <label for=""> Project Name </label>
                                    <input type="text" name="project_name" value="{{ old('project_name',$project->project_name) }}" autocomplete="off"
                                    class="form-control @error('project_name') is_invalid @enderror" placeholder="Project name" style="margin-top:5px;">

                                    @error('project_name')
                                        <p class="text-danger"> {{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="form-group w-100 ml-1">

                                    <label for="">Site/Delivery </label>
                                    <input type="text" name="site_delivery"
                                        value="{{$lpo_project->site_delivery }}" autocomplete="off"
                                        class="form-control @error('site_delivery') is_invalid @enderror"
                                        placeholder="site delivery ..." style="margin-top: 5px;" required>

                                    @error('site_delivery')
                                        <p class="text-danger"> {{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="form-group w-100 ml-1">
                                    <div class="d-flex justify-content-between" style="margin-bottom: 3px;">
                                        <label for=""> Customer Name </label>
                                        <button type="button" class="project-btn add-customer" data-toggle="modal"  data-target="#add-customer">
                                           <i class="bx bx-plus"></i>
                                        </button>
                                    </div>
                                    <select name="customer_id" class="form-control customer_id @error('customer_id') is-invalid @enderror">
                                        <option  selected disabled> Select Customer </option>
                                        @foreach ($customers as $customer)
                                            <option value="{{ $customer->id }}" {{ old('customer_id',$project->customer_id) == $customer->id ? 'selected' : ' ' }}> {{ $customer->pi_name }} </option>
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
                                    class="form-control @error('project_description') is-invalid @enderror">{{ old('project_description',$project->project_description) }}</textarea>
                                    @error('project_description')
                                        <p class="text-danger"> {{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="ml-1 d-flex w-100">
                                    <div class="form-group">
                                        <label for=""> Estimated Starting Date </label>
                                        <input type="text" name="start_date" class="date form-control @error('start_date') is-invalid @enderror" value="{{ date('d/m/Y',strtotime($project->start_date)) }}" autocomplete="off">
                                        @error('start_date')
                                        <p class="text-danger"> {{ $message }}</p>
                                    @enderror
                                    </div>

                                    <div class="form-group ml-1">
                                        <label for=""> Estimated End Date </label>
                                        <input type="text" name="end_date" class="date form-control @error('end_date') is-invalid @enderror" value="{{ date('d/m/Y',strtotime($project->end_date)) }}" autocomplete="off">
                                        @error('end_date')
                                        <p class="text-danger"> {{ $message }}</p>
                                    @enderror
                                    </div>
                                </div>
                            </div>


                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <h2 class="tasks-title"> Project Tasks </h2>
                                <button type="button" class="add_items project-btn"> Add </button>
                            </div>


                            <table class="auto-index repeater1 table table-sm">
                                <thead>
                                    <tr>
                                        <th class="text-center"> S.NO </th>
                                        <th> Task Name </th>
                                        <th> Description </th>
                                        <th class="text-center"> Budget ({{$currency->symbole}}) </th>
                                        <th class="text-center"> Vat % </th>
                                        <th  class="text-center"> SubTotal  ({{$currency->symbole}}) </th>
                                        <th class="text-center"> Action </th>
                                    </tr>
                                </thead>
                                <tbody id="input-container">
                                    @foreach ($project->tasks as $item)
                                    <tr>
                                        <td class="text-center"> </td>
                                        <td style="width: 20%">
                                            @if ($item->is_invoice == 1)
                                            <input type="text" name="task_name[]" class="form-control @error('task_name') is-invalid @enderror" required value="{{ $item->task_name }}" autocomplete="off" disabled>
                                            @else
                                            <input type="text" name="task_name[]" class="form-control @error('task_name') is-invalid @enderror" required value="{{ $item->task_name }}" autocomplete="off">
                                            @endif
                                        </td>

                                        <td style="width: 30%">
                                            @if ($item->is_invoice == 1)
                                            <textarea name="description[]"  cols="30" rows="1" class="form-control" required disabled>{{ $item->description }}</textarea>
                                            @else
                                            <textarea name="description[]"  cols="30" rows="1" class="form-control" required>{{ $item->description }}</textarea>
                                            @endif

                                        </td>

                                        <td>
                                            @if ($item->is_invoice == 1)
                                            <input type="number" name="budget[]" class="form-control text-center budget" required value="{{ $item->budget }}" value='0.00' step="any" disabled>
                                            @else
                                            <input type="number" name="budget[]" class="form-control text-center budget" required value="{{ $item->budget }}" value='0.00' step="any">
                                            @endif

                                        </td>

                                        <td>
                                            @if ($item->is_invoice == 1)
                                            <select name="vat[]" class="vat form-control" disabled>
                                                @foreach ($vats as $vat )
                                                    <option value="{{ $vat->id }}" {{ $item->vat_id == $vat->id ? 'selected' : ' ' }} data-value={{ $vat->value }} disabled> {{ $vat->name . ' ( ' . $vat->value  .' )' }}</option>
                                                @endforeach
                                            </select>
                                            @else
                                            <select name="vat[]" class="vat form-control">
                                                @foreach ($vats as $vat )
                                                    <option value="{{ $vat->id }}" {{ $item->vat_id == $vat->id ? 'selected' : ' ' }} data-value={{ $vat->value }}> {{ $vat->name . ' ( ' . $vat->value  .' )' }}</option>
                                                @endforeach
                                                </select>
                                            @endif

                                        </td>
                                        <td>
                                            @if ($item->is_invoice == 1)
                                            <input type="number" name="total_budget[]" class="form-control total_budget text-center" required value="{{ $item->total_budget }}" step="any" disabled>
                                            @else
                                            <input type="number" name="total_budget[]" class="form-control total_budget text-center" required value="{{ $item->total_budget }}" step="any" readonly>
                                            @endif

                                        </td>

                                        <td class="text-center">
                                            <button  type="button" class="delete_items project-btn"> <i class="bx bx-trash"></i> </button>
                                        </td>
                                    </tr>
                                    @endforeach<tr>

                                </tbody>
                                <tbody>
                                    <tr>
                                        <tr>
                                            <td class="text-center d-none"> </td>
                                            <td  colspan="5" class="text-right"> <span class="mr-1">  Budget ({{$currency->symbole}}) </span> </td>
                                            <td  colspan="1"> <input type="number" step='any' name="sum_budget" class="form-control text-center budget_sum" value="{{ $project->budget }}" readonly>  </td>
                                        </tr>
                                    </tr>
                                    <tr>
                                        <tr>
                                            <td class="text-center d-none"> </td>
                                            <td  colspan="5" class="text-right"> <span class="mr-1">  Vat ({{$currency->symbole}}) </span> </td>
                                            <td  colspan="1"> <input type="number" step='any' name="total_vat" class="form-control text-center total-vat" value="{{ $project->vat}}" readonly>  </td>
                                        </tr>
                                    </tr>
                                    <tr>
                                        <tr>
                                            <td class="text-center d-none"> </td>
                                            <td  colspan="5" class="text-right"> <span class="mr-1"> Total Budget ({{$currency->symbole}}) </span> </td>
                                            <td  colspan="1"> <input type="number" step='any' name="sum_total_budget" class="form-control text-center total-budget" value="{{ $project->total_budget }}" readonly>  </td>
                                        </tr>
                                    </tr>

                                    <tr>
                                        <tr>
                                            <td class="text-center d-none"> </td>
                                            <td  colspan="5" class="text-right"> <span class="mr-1"> Advance Payment ({{$currency->symbole}}) </span> </td>
                                            <td  colspan="1"> <input type="number" step='any' name="advance_payment" class="form-control text-center" readonly value="{{ $project->advance_payment }}">  </td>
                                        </tr>
                                    </tr>

                                </tbody>
                            </table>
                            <div class="form-group" style="    width: 300px;margin-top: 27px;">
                                <label for=""> Voucher File Upload  </label>
                                <input
                                    class="form-control  @error('voucher_file') is-invalid @enderror" type="file" name="voucher_file" style="height: 45px !important" accept="application/pdf,image/png,image/jpeg,application/msword" >
                                @error('voucher_file')
                                    <p class="text-danger"> {{ $message }}</p>
                                @enderror
                            </div>
                            <div class="d-flex justify-content-end mt-1">
                                <button type="submit" class="project-btn save-btn"> Save </button>
                            </div>
                        </form>
                    </section>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="add-customer" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header" style="padding: 5px 15px;">
          <h5 class="modal-title" id="exampleModalLabel"> Create Party </h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body" style="padding: 5px 15px;">
            <div class="d-flex">
                <div class="form-group w-50">
                    <label for=""> Party Name </label>
                    <input type="text" name="pi_name" class="form-control" required>
                    <p class="error-pi_name text-danger"> </p>
                </div>
                <div class="form-group w-50 ml-1">
                    <label for=""> Contact Person </label>
                    <input type="text" name="con_person" class="form-control" required>
                    <p class="error-con_person text-danger"></p>
                </div>
            </div>
            <div class="d-flex">
                <div class="form-group w-50">
                    <label for=""> Party Type </label>
                    <select name="pi_type" class="form-control">
                        <option selected> Customer </option>
                        <option> Supplier </option>
                        <option> Employee </option>
                        <option> Government Body </option>
                    </select>
                    <p class="error-pi_type text-danger"></p>
                </div>
                <div class="form-group w-50 ml-1">
                    <label for=""> Mobile Phone Number </label>
                    <input type="text" name="phone_no" class="form-control" required>
                    <p class="error-phone_no text-danger"></p>
                </div>
            </div>
            <div class="d-flex">
                <div class="form-group w-50">
                    <label for=""> TRN No </label>
                    <input type="text" name="trn_no" class="form-control" required>
                    <p class="error-trn_no text-danger"></p>
                </div>
                <div class="form-group w-50 ml-1">
                    <label for=""> Phone Number </label>
                    <input type="text" name="con_no" class="form-control" required>
                    <p class="error-con_no text-danger"></p>
                </div>
            </div>

            <div class="d-flex">
                <div class="form-group w-50">
                    <label for=""> Address  </label>
                    <input type="text" name="address" class="form-control" required>
                    <p class="error-address text-danger"></p>
                </div>
                <div class="form-group w-50 ml-1">
                    <label for=""> Email </label>
                    <input type="text" name="email" class="form-control" required>
                    <p class="error-email text-danger"></p>
                </div>
            </div>

        </div>
        <div class="modal-footer" style="padding: 5px 15px;">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary create-party"> Create </button>
        </div>
      </div>
    </div>
</div>
@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.0/js/toastr.js"></script>
    <script>
        $(document).ready(function() {
            $('.customer_id').select2();
            $(".add_items").click(function () {
                addInput();
            });

            $('.date').datepicker({dateFormat:'dd/mm/yy'})

            function addInput() {
                $.ajax({
                    url:"{{ route('get.porjects.vat') }}",
                    type:'get',
                    success:function(vats){
                        var inputGroup = "<tr>"
                                + "<td class='text-center'></td>"
                                + "<td style='width: 20%'>"
                                + "<input type='text' name='task_name[]' class='form-control' required>"
                                +"</td>"
                                + "<td style='width: 30%'>"
                                +"<textarea name='description[]'  cols='30' rows='1' class='form-control' required></textarea>"
                                +"</td>"
                                +"<td>"
                                +"<input type='number' name='budget[]' class='form-control budget text-center' step='any'>"
                                +"</td>"
                                +"<td>"
                                inputGroup +="<select name='vat[]' class='vat form-control'>"
                                $.each(vats,function(key,index){
                                    inputGroup += "<option value="+ index.id +" data-value ="+ index.value+"> " + index.name + '  ( ' + index.value + ' )'  +" </option>"
                                })
                                inputGroup +="</select>"
                                +"</td>"
                                +"<td>"
                                +"<input type='number' name='total_budget[]' class='form-control total_budget text-center' step='any'>"
                                +"</td>"
                                +"<td class='text-center'>"
                                +"<button  type='button' class='delete_items project-btn'>"
                                +"<i class='bx bx-trash'> </i>"
                                +"</button>"
                                +"</td>"
                                +"</tr>";
                        $("#input-container").append(inputGroup);
                    },
                    error:function(error){
                        toastr.error("Something rong Can't add column");
                    }
                })

            }
        });

        $(document).on("click", ".delete_items", function () {
            $(this).closest("tr").remove();
            calculateBudget();
        });

        function calculateBudget(){
            let total_budget = 0;
            let budget = 0
            $('.budget').each(function(index,el){
                budget += parseFloat($(this).val())
            })
            $('.total_budget').each(function(index,el){
                total_budget += parseFloat($(this).val())
            })
            $('.total-budget').val(parseFloat(total_budget).toFixed(2))
            $('.budget_sum').val(parseFloat(budget).toFixed(2))
            $('.total-vat').val(parseFloat(total_budget - budget).toFixed(2))
        }

        $(document).on("keyup", ".budget", function () {
            calculateBudget();
        });

        $(document).on('click','.create-party',function(){
            $.ajax({
                url: "{{ route('jobproject.customer.store') }}",
                method: "POST",
                data: {
                    _token: $('input[name="_token"]').val(),
                    pi_name: $('input[name="pi_name"]').val(),
                    pi_type: $('select[name="pi_type"]').val(),
                    trn_no: $('input[name="trn_no"]').val(),
                    address:$('input[name="address"]').val(),
                    con_person:$('input[name="con_person"]').val(),
                    con_no:$('input[name="con_no"]').val(),
                    phone_no:$('input[name="phone_no"]').val(),
                    email:$('input[name="email"]').val(),
                },
                success: function(data) {
                    $('.customer_id').append("<option value='"+ data.id +"' selected>" + data.pi_name +"</option>");
                    $('.customer_id').select2();

                    $("#add-customer").modal('hide');
                },
                error:function(error){
                    $.each(error.responseJSON.errors, function(key, val){
                        $('p.error'+'-'+key).text(val[0]);
                        $('p.error'+'-'+key).siblings().addClass('is-invalid');
                    })
                }
            })
        })

        $(document).on('change','.vat',function(event){
            let tr = event.target
            calculateVat(tr);
        })

        $(document).on('keyup','.budget',function(event){
            let tr = event.target
            calculateVat(tr);
        })

        function calculateVat(node){
            node = $(node).closest('tr');
            let vat =  parseFloat(node.find('.vat').find(':selected').attr('data-value')) / 100;
            let budget= parseFloat(node.find('.budget').val())
            total_budget =  budget + budget * vat ;
            node.find('.total_budget').val(parseFloat(total_budget).toFixed(2));
            calculateBudget();
        }
     </script>
@endpush
