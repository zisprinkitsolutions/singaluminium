<script>
 // **********************************************************calculation modal show****************************************

    $(document).on("change", ".risk-rating", function(e) {
            var type = $(this).data('risk_rating');
            var value = $(this).val();
            if (value == 'Medium') {
                $(type).val(2);
                this.style.backgroundColor = '#EBEDA3'
                document.querySelector(type).style.backgroundColor = '#EBEDA3';
            } else if (value == 'High') {
                $(type).val(3);
                this.style.backgroundColor = '#E77CB1'
                document.querySelector(type).style.backgroundColor = '#E77CB1';
            } else {
                $(type).val(1);
                this.style.backgroundColor = '#A2F189'
                document.querySelector(type).style.backgroundColor = '#A2F189';
            }
            //  alert(type)
        });
    //
    // ********************************************************** add modal show****************************************
    $(document).on("click", ".employee_modal_open", function(e) {
        var modal = $(this).data('modal');
        // $('.employee_form')[0].reset();

        // document.querySelector('.fupdate-note').style.display = 'none';
        // document.querySelector('.fupdatate-1').style.display = 'none';
        // document.querySelector('.fsave-1').style.display = 'block';
        // document.querySelector('#fappprove-rejection-button').style.display = 'none';

        $(modal).modal('show');
    });

    // ********************************************************** add modal show****************************************

    // ********************************************************** file upload  modal ****************************************
    $(document).on("click", ".employee_file_uplod", function(e) {

        $("#employee_file_uplod_modal").modal('show');
    });

    // ********************************************************** file uploadl show****************************************

    // **************************  show and eidt modal and update data by ajax*************************************

    $(document).on("click", ".employee", function(e) {
        var id = $(this).data('id');
        // alert(id);
        $.ajax({
            url: id,
            method: 'get',
            processData: false,
            contentType: false,
            success: function(res) {
                $("#edit-modal").empty().append(res.page);
                $('#employee-modal-edit').modal('show');
            },
            error: function(err) {
                let error = err.responseJSON;
                $.each(error.errors, function(index, value) {
                    toastr.error(value);

                })
            }
        });
    });

    // Employee edit start
    $(document).on("click", ".employee-edit", function(e) {


        var id = $(this).data('id');
        // alert(id);
        $.ajax({
            url: id,
            method: 'get',
            processData: false,
            contentType: false,
            success: function(res) {
                $("#edit-modal").empty().append(res.page);
            },
            error: function(err) {
                let error = err.responseJSON;
                $.each(error.errors, function(index, value) {
                    toastr.error(value);

                })
            }
        });
        $('#employee-modal-edit').modal('show');
    });
    // ********************************************************** edit modal show end****************************************
</script>
<script>


    // **********************************************************t type add*****************************************
    $(document).on('click', '#employee_form', function(e) {
        e.preventDefault();
        var form_data = new FormData(document.querySelector('.employee_form'));
       // alert('hello')
        $.ajax({
            url: "{{ route('employees.store') }}",
            method: 'post',
            processData: false,
            contentType: false,
            data: form_data,
            success: function(res) {
                //console.log(res.status)
                if (res.status == 'success') {
                    $('#employee-modal').modal('hide');
                    $('.employee_form')[0].reset();
                    $('.employee_change').load(location.href + ' .employee_change');

                    location.reload();
                  //   $(' ul li a[href="#LEGAL-STATUS"]').click();
                    toastr.success('New employee add successfully');
                }
            },
            error: function(err) {
                let error = err.responseJSON;
                $.each(error.errors, function(index, value) {
                    toastr.error(value);

                })
            }
        });
    })

</script>
