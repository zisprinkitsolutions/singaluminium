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

    // ********************************************************** file uploadl show ****************************************

    // **************************  show and eidt modal and update data by ajax *************************************

    $(document).on("click", ".employee-salary-certificate", function(e) {
        var id = $(this).data('id');

        $.ajax({
            url: id,
            method: 'get',
            processData: false,
            contentType: false,
            success: function(res) {
                var print_contain = res.page;

                // Create an iframe to hold the content
                var iframe = document.createElement('iframe');
                iframe.style.position = "absolute";
                iframe.style.width = "0px";
                iframe.style.height = "0px";
                iframe.style.border = "none";

                document.body.appendChild(iframe);
                var doc = iframe.contentWindow || iframe.contentDocument;
                doc.document.open();
                doc.document.write(print_contain);
                doc.document.close();

                iframe.onload = function() {
                    doc.focus();
                    doc.print();
                    document.body.removeChild(iframe);
                }
            },
            error: function(err) {
                let error = err.responseJSON;
                $.each(error.errors, function(index, value) {
                    toastr.error(value);
                });
            }
        });
    });

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
                $('.summernote').summernote();
                generateBarcode(res.emp_id);

            },
            error: function(err) {
                let error = err.responseJSON;
                $.each(error.errors, function(index, value) {
                    toastr.error(value);

                })
            }
        });
    });

    $(document).on('submit', '.attendance-search-form', function(e){
        e.preventDefault();

        var url = $(this).attr('action');
        var formData = $(this).serialize();

        $.ajax({
            url: url,
            type: 'GET',
            data: formData,
            beforeSend: function() {
                // optional: show loader
            },
            success: function(response) {
                $('#attendance-table-body').html(response);
            },
            error: function(xhr) {
                console.log(xhr.responseText);
            }
        });
    });

    //currency name select
    $(document).on("change", "#employment_location", function(e) {
        if ($(this).val() != '') {
            var id = $(this).val();
            // alert(category);
            var _token = $('input[name="_token"]').val();
            $.ajax({
                url: "{{ route('find-currency') }}",
                method: "GET",
                data: {
                    id: id,
                    _token: _token,
                },
                success: function(response) {
                    console.log(response);
                        $("#currency").val(response.currency);
                }
            })
        }
    });

    $(document).on('click', '.payslip-button', function () {
        var year = $('#payslip_year').val();
        var month = $(this).data('month');
        var id = $('#paysip_user_id').val();
        var all = $(this).data('all');

        var url = "{{ route('download.payslip') }}" +
            `?year=${year}&month=${month}&id=${id}&all=${all}`;

        fetch(url, {
            method: 'GET',
            headers: {
                'Accept': 'application/pdf',
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Failed to download PDF.');
            }
            return response.blob();
        })
        .then(blob => {
            const fileURL = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = fileURL;
            a.download = `payslip_${id}_${month || 'all'}.pdf`;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            window.URL.revokeObjectURL(fileURL);
        })
        .catch(err => {
            console.error(err);
            toastr.error("PDF download failed");
        });
    });

    // Employee edit start
    $(document).on("click", ".employee-edit", function(e) {
        $('#personal-info').removeClass('active show');

        var id = $(this).data('id');
        $.ajax({
            url: id,
            method: 'get',
            processData: false,
            contentType: false,
            success: function(res) {
                $("#edit-modal").empty().append(res.page);
                $('.summernote').summernote();
                $(".datepicker").datepicker({ dateFormat: "dd/mm/yy" });
                $('#personal-infou').addClass('active show');
                $('.first_name').focus();
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
                    toastr.success('Employee Profile Added Succesfully');
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
