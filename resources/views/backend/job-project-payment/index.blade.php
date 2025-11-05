@extends('layouts.backend.app')
@push('css')
@include('layouts.backend.partial.style')
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.0/css/toastr.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
<style>
    body{
		counter-reset: Serial;
	}
    .auto-index,.auto-index th,.auto-index td{
        border: 1px solid #ddd;
    }
    .auto-index,.auto-index td{
        border: 1px solid #ddd;
        padding-left: 6px  !important;
        margin: 0 !important;
    }
    .project-btn{
        border: none;
        color: #fff;
        font-size: 15px;
        font-weight: 500px;
        padding:3px 10px;
        border-radius: 5px;
    }

    .auto-index td:first-child:before{
        counter-increment: Serial;      /* Increment the Serial counter */
        content:  counter(Serial);  /* Display the counter */
    }
    .table{
        border: 1px solid #ddd;
        /* border-right: 1px solid #ddd; */
    }
    .table th{
        background-color: #eee;
        border-bottom: 2px solid #ddd;
        padding: 10px 8px;
    }
    .table td{
        padding-left: 8px !important;
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
        font-weight: 400;
        text-transform: capitalize;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered{
        font-size: 16px !important;
    }

    .select2-container--default .select2-selection--single {
        height: 35px !important;
    }
    .save-btn{
        background: #406343;
    }
    .form-control{
        height: 35px !important;
    }
    .view-btn{
        background-color: #5F6F94;
    }
    .create-btn{
        background-color: #1F8A70;
    }
    .select2-container{
        width:100% !important;
    }
</style>
@endpush

@section('content')
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-body">
            @include('backend.job-project._header')
            <div class="tab-content bg-white">
                <div id="journaCreation" class="tab-pane active">
                    <section class="p-1" id="widgets-Statistics">
                        <div class="sub-menu d-flex justify-content-end">
                            <button  class="project-btn create-btn"> New Payment </button>
                        </div>
                        <div class="payment-data table-responsive mt-1">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th> Project </th>
                                        <th>  customer </th>
                                        <th class="text-center">total</th>
                                        <th class="text-center">Payment</th>
                                        <th class="text-center"> Due </th>
                                        <th class="text-center"> date </th>
                                        <th class="text-center"> Action </th>
                                    </tr>
                                </thead>
                                <tbody id="payment-data">
                                    @foreach ($payments as $payment)
                                        <tr>
                                            <td> {{ $payment['project_name'] }} </td>
                                            <td>{{ $payment['party_name'] }}</td>
                                            <td class="text-center">  {{ $payment['total_budget'] }}</td>
                                            <td class="text-center">  {{ $payment['payment_amount'] }}</td>
                                            <td class="text-center"> {{ $payment['due_amount'] }}</td>
                                            <td class="text-center"> {{date('d/m/y',strtotime($payment['date'])) }}</td>
                                            <td class="text-center"> <button class="project-btn view-btn" data-id="{{ $payment['id'] }}"> View </button> </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            {!! $project_payments->links() !!}
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="form-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header" style="padding: 5px 15px;">
          <h5 class="modal-title" id="exampleModalLabel">  </h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body" id="create-modal" style="padding: 5px 15px;">
            <div class="d-flex">
                <div class="form-group w-100">
                    <label for=""> Select Project  </label>
                    <select name="job_project_id" class="form-control w-100 job_project_id select2-input">
                        <option  selected disabled> Select Projects</option>
                        @foreach ($projects as $project)
                            <option value="{{ $project->id }}"> {{ $project->project_name }} </option>
                        @endforeach
                    </select>
                    <p class="text-danger error-job_project_id"> </p>

                </div>

                <div class="form-group w-100 ml-1" style="padding-right:6px;">
                    <label for=""> Customer Name </label>
                    <select name="party_info_id" class="form-control party_info_id">
                        <option selected disabled> Select Customer </option>
                    </select>

                    <p class="text-danger error-party_info_id"> </p>

                </div>

                <div class="form-group w-100 ml-1">
                    <label for=""> Date </label>
                    <input type="text" name="date" value="{{ old('date',date('d/m/Y')) }}" class="date form-control">
                    <p class="text-danger error-date"></p>
                </div>

                <div class="d-flex w-100">
                    <div class="form-group w-100 ml-1">
                        <label for=""> Payment Amount </label>
                        <input type="text" name="payment_amount" value="{{ old('payment_amount') }}" class="payment_amount form-control" data-due="0">
                        <p class="text-danger error-payment_amount"></p>
                    </div>
                </div>

            </div>

            <div class="project-tasks d-none">
                <h2 class="tasks-title"> Project Tasks </h2>
                <div class="mt-1 mb-2">
                    <table class="repeater1 table table-sm">
                        <thead>
                            <tr>
                                <th class=""> Payment NO </th>
                                <th class="text-center"> Date </th>
                                <th class="text-center"> Payment Amount </th>
                                <th style="width:20%" class="text-center"> Blance </th>
                            </tr>
                        </thead>
                        <tbody id="task-container">
                            <tr>
                                <td colspan="2"></td>
                                <td style="text-align:center;font-weight:500;color:#475F7B;font-size:13;letter-spacing: 1px;text-transform:capitalize;"> Project Bugdet </td>
                                <td class="budget" style="text-align:center;font-weight:500;color:#475F7B;font-size:13;letter-spacing: 1px;text-transform:capitalize;">  </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="modal-body" id="view-modal" style="padding: 5px 15px;">
        </div>
        <div class="modal-footer" style="padding: 5px 15px;">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary form-btn" btn-type="create">  </button>
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
            $('.select2-input').select2();
            $('.date').datepicker({dateFormat:'dd/mm/yy'})
        });

        $(document).on('click','.create-btn',function(e){
            e.preventDefault();
            $('.form-btn').text('Create Payment')
            $('.modal-title').html('Payment')
            $('#view-modal').hide();
            $('#create-modal').show();
            $('#form-modal').modal('show');
            $('.modal-footer').show();
        })

        $(document).on('click','.form-btn',function(){
            let btn_type = $(this).attr('btn-type');
            storePayment(btn_type)
        })

        function storePayment(btn_type){
            let url = ' ';
            let type = 'post';
            let data = {
                _token: $('input[name="_token"]').val(),
                job_project_id: $('.job_project_id').val(),
                party_info_id : $('.party_info_id').val(),
                payment_amount: $('.payment_amount').val(),
                date: $('.date').val(),
            }
            if(btn_type == 'create'){
                url = "{{ route('payments.store') }}";
            }else{
                //
            }
            $.ajax({
                url:url,
                type:type,
                data :data,
                success:function(data){
                    let column = "<tr>"
                        +"<td>" + data.project_name + "</td>"
                        +"<td>" + data.party_name + "</td>"
                        +"<td class='text-center'>" + data.budget + "</td>"
                        +"<td class='text-center'>" + data.payment_amount +"</td>"
                        +"<td class='text-center'>" + data.due + "</td>"
                        +"<td class='text-center'>" + data.date + "</td>"
                        +"<td class='text-center'>" +"<button class='project-btn view-btn' data-id="+ data.id +">  View </button> </td>"
                        +"</tr>"

                    $('#payment-data').append(column);
                    toastr.success('Payment has beed created successfully');

                    $('#form-modal').modal('hide');
                    setTimeout(function() {
                        location.reload();
                    },1000);
                },
                error:function(error){
                    toastr.error(`Validation fail, try agagin`);
                    $.each(error.responseJSON.errors, function(key, val){
                        $('p.error'+'-'+key).text(val[0]);
                        $('p.error'+'-'+key).siblings().addClass('is-invalid');
                    })
                }
            })
        }

        $(document).on('change','.job_project_id',function(){
            let id = $(this).val();
            let url ="{{ route('jobproject.details',":id") }}"
            url = url.replace(':id',id);

            $.ajax({
                type: "GET",
                url:url,
                success:function(data){
                    $('.party_info_id').html("<option value="+data.party.id +">" + data.party.pi_name +" </option>");
                    addTasks(data.payment,data.total_budget)
                    $('.project-tasks').removeClass('d-none');
                    $('.payment_amount').attr('data-due',data.due);
                    $('.budget').html('DR ' + data.total_budget);
                },
                error:function(error){
                    console.log(error);
                }
            })

        })

        function addTasks(data,total_budget) {
            $("#task-container").empty();
            var total_column = "<tr>"
                                +'<td  colspan="2"> </td>'
                                +'<td  colspan="1" class="text-center"> Total Budget </td>'
                                +'<td  colspan="1" class="text-center">'+ 'DR ' + total_budget +'</td>'
                            +'</tr>'
            $("#task-container").append(total_column );

            let balance = total_budget ;

            $.each(data,function(key,index){
                let date = new Date(index.date);
                balance -= parseInt(index.payment_amount);

                var groupData = "<tr>"
                                + "<td class=''>" + 'Payment No ' + index.id + "</td>"
                                + "<td class='text-center'>"
                                + date.getDate()  + '/' +(date.getMonth() + 1) + '/' +  date.getFullYear()
                                +"</td>"
                                + "<td class='text-center'>"
                                + index.payment_amount
                                +"</td>"
                                +"<td style='text-align:center'>"
                                + 'DR ' + balance
                                +"</td>"
                                +"</tr>";

                $("#task-container").append(groupData);
            })

        }

        $(document).on('click','.view-btn',function(){
            let id = $(this).attr('data-id');
            let url =  "{{ route('payments.show',":id") }}";
            url = url.replace(':id',id);
            $.ajax({
                url:url,
                type:'get',
                success:function(res){
                    $('.modal-title').html('Payment view')
                    $('#view-modal').html(res);
                    $('.modal-footer').hide();
                    $('#create-modal').hide();
                    $('#view-modal').show();
                    $('#form-modal').modal('show');
                },
                error:function(error){
                    toastr.error(`Unexpected error, plaese try again!`);
                }
            })
        })

        $(document).on('keyup','.payment_amount',function(){
            let due = parseInt($(this).attr('data-due'));
            let payment = parseInt($(this).val());

            if(payment <= 0 || payment > due){
                $('p.error-payment_amount').html("Unvalid amount max " + due);
                $('.form-btn').attr('disabled',true);
            }else{
                $('.form-btn').attr('disabled',false);
                $('p.error-payment_amount').removeClass('text-danger').html('max ' + due);
            }
        })


    </script>
@endpush
