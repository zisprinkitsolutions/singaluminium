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
    .tasks-title, .Price-title{
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
    .edit-btn{
        background-color: #114b89;
    }
    .select2-container{
        width:300px !important;
    }
    .auto-index td{
        padding: 0 !important;
        margin: 0 !important;
    }
    .delete_items{
        padding:3px 2px 0px 2px !important;
        margin: 0;
        background: #AC4C5E;
    },
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
                            <button  class="project-btn create-btn"> New Expense </button>
                        </div>
                        <div class="payment-data table-responsive mt-1">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th> Project </th>
                                        <th class="text-center">  Items </th>
                                        <th class="text-center"> Date </th>
                                        <th class="text-center"> Expense </th>
                                        <th class="text-center" style="width: 10%;"> Action </th>
                                    </tr>
                                </thead>
                                <tbody id="payment-data">
                                    @foreach ($project_expenses as $expense)
                                        <tr>
                                            <td> {{ $expense->project_name }} </td>
                                            <td class="text-center">{{ $expense->expenses->count() }}</td>
                                            <td class="text-center">  {{$expense->expenses[$expense->expenses->count() - 1]->date }}</td>
                                            <td class="text-center">  {{$expense->expenses->sum('price') }}</td>
                                            <td style="width:10%;" class="d-flex  aigin-items-center">
                                                <button class="project-btn view-btn" data-id="{{ $expense->id }}"> View </button>
                                                <button class="ml-1 project-btn edit-btn" data-url="{{ route('project.expense.edit',$expense->id) }}"> Edit </button>
                                             </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            {!! $project_expenses->links() !!}
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal View-->

<div class="modal fade bd-example-modal-lg" id="form-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header" style="padding: 5px 15px;">
          <h5 class="modal-title" id="exampleModalLabel">  </h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <div class="modal-body" id="view-modal" style="padding: 5px 15px;">


        </div>
      </div>
    </div>
</div>

{{-- edit  --}}
<div class="modal fade bd-example-modal-lg" id="form-edit-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header" style="padding: 5px 15px;">
          <h5 class="modal-title" id="exampleModalLabel"> Expense  </h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <div class="modal-body" id="edit-modal" style="padding: 5px 15px;">


        </div>
      </div>
    </div>
</div>

@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.0/js/toastr.js"></script>
    <script>
        $(document).on('mouseenter','.select2-input',function(){
            $('.select2-input').select2();
        })

        $(document).on('mouseenter','.date',function(){
            $('.date').datepicker({dateFormat:'dd/mm/yy'});
        })

        $(document).on('click','.create-btn',function(e){
            e.preventDefault();

            $.ajax({
                url:"{{route('porject.expense.create') }}",
                type:'get',
                success:function(res){
                    $('#edit-modal').html(res);
                    $('#form-edit-modal .modal-title').html('Create Expenses')
                    $('#form-edit-modal').modal('show');

                },
                error:function(error){
                    toastr.error(error.responseJSON.message);
                }
            })
        })

        $(document).on('click','.edit-btn',function(){
            let url= $(this).attr('data-url');
            $.ajax({
                url:url,
                type:'get',
                success:function(res){
                    $('#edit-modal').html(res);
                    $('#form-edit-modal .modal-title').html('Update Expenses')
                    $('#form-edit-modal').modal('show');
                },
                error:function(error){
                    toastr.error(error.responseJSON.message);
                }
            })
        })


        $(document).on('click','.view-btn',function(){
            let id = $(this).attr('data-id');
            let url =  "{{ route('project.expense.show',":id") }}";
            url = url.replace(':id',id);
            $.ajax({
                url:url,
                type:'get',
                success:function(res){
                    $('.modal-title').html('Expense view')
                    $('#view-modal').html(res);
                    $('.modal-footer').hide();
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

        $(document).on('click','.add_items',function () {
                addInput();
        });

        function addInput() {
            $.ajax({
                url:"{{ route('get.units') }}",
                type:'get',
                success:function(res){
                    var inputGroup = "<tr>"
                            + "<td class='text-center'></td>"
                            + "<td>"
                            + "<input type='text' name='item[]' class='form-control item' required>"
                            +"</td>"
                            +"<td class='text-center'>"
                            +"<select name='unit[]'  class='unit form-control' required>";
                            $.each(res,function(key,index){
                                inputGroup += "<option value="+ index.id +"> " + index.name + " </option>"
                            })
                            inputGroup +="</select>"
                            +"</td>"

                            +"<td>"
                            +"<input type='number' name='qty[]' class='form-control qty' required>"
                            +"</td>"
                            +"<td>"
                            +"<input type='number' name='rate[]' class='form-control rate' required>"
                            +"</td>"
                            +"<td class='text-center'>"
                            +"<input type='number' name='price[]' class='form-control price' required step='any'>"
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
                    toastr.error(`Unexpected error, plaese try again!`);
                }
            })

        }

        $(document).on("click", ".delete_items", function () {
            $(this).closest("tr").remove();
            calculatePrice();
        });

        function calculatePrice(){
            let total_price = 0;
            $('.price').each(function(index,el){
                console.log(parseInt($(this).val()))
                total_price = total_price + parseInt($(this).val());
            })
            $('.total-price').val(parseInt(total_price))
        }

        $(document).on("keyup", ".price", function (event) {
            calculatePrice()
            let closest_tr = $(node).closest('tr');
            let qty = parseInt(closest_tr.find('.qty').val());
            let rate = parseInt(closest_tr.find('.rate').val());
            let price = parseInt(closest_tr.find('.price').val());

            if(price > 0 && rate > 0 && !qty){
                closest_tr.find('.qty').val(price/rate);
            }else if(price && qty && !rate){
                closest_tr.find('.rate').val(price/qty)
            }
        })


        $(document).on('keyup','.qty',function(e){
            calculateRatePriceQty(event.target);
        })

        $(document).on('keyup','.rate',function(e){
            calculateRatePriceQty(event.target);
        })

        function calculateRatePriceQty(node){

            let closest_tr = $(node).closest('tr');
            let qty = parseInt(closest_tr.find('.qty').val());
            let rate = parseInt(closest_tr.find('.rate').val());
            let price = parseInt(closest_tr.find('.price').val());

            if(price > 0 && rate > 0 && !qty){
                closest_tr.find('.qty').val(price/rate);
            }else if(price && qty && !rate){
                closest_tr.find('.rate').val(price/qty)
            }else if(rate && qty && !price){
                closest_tr.find('.price').val(rate*price);
            }

            console.log(price,rate,qty);
        }


    </script>
@endpush
