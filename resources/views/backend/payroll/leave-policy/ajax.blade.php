<script type="text/javascript">
    $(function() {
            $("#datepicker").datepicker({ dateFormat: "dd/mm/yy" }).val()
    });
</script>
<script>


    // ********************************************************** add modal show****************************************
    $(document).on("click", ".employee_modal_open", function(e) {
        var modal = $(this).data('modal');
        $(modal).modal('show');
    });

    // ********************************************************** start_date, .end_date ****************************************
    $(document).on("change", ".start_date, .end_date", function(e) {
            var startDate = $('.start_date').datepicker('getDate');
            var endDate = $('.end_date').datepicker('getDate');

            if (startDate && endDate) {
                var differenceInDays = (endDate - startDate) / (1000 * 60 * 60 * 24);

                if (differenceInDays >= 0) {
                    $('.leave_day').val(differenceInDays + 1);
                } else {
                    $('.leave_day').val('');
                    toastr.warning("The 'To' date must be after the 'From' date.");
                }
            } else {
                if (startDate) {
                    $('.leave_day').val(1);
                } else {
                    $('.leave_day').val('');
                }
            }
        });

    // ********************************************************** start_date, .end_date ****************************************

    // ************************************************ check vacation *********************************************************
    $(document).on("change", ".emp_id", function (e) {
    var selectedOption = $(this).find(":selected");
    var empId = selectedOption.data('emp_id');
    $.ajax({
        url: '{{ route('check-vacation') }}',
        method: 'POST',
        data: { emp_id: empId },
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function (res) {

                const minDate = res.startDate;
                const maxDate = res.endDate;

                if (!minDate || !maxDate) {
                    $('.datepicker_cus').prop('disabled', true);
                } else {
                    $('.datepicker_cus').datepicker('destroy');
                    $('.datepicker_cus').datepicker({
                        dateFormat: 'dd/mm/yy',
                        minDate: new Date(minDate),
                        maxDate: new Date(maxDate),
                    }).prop('disabled', false);
                }
                if(res.message){
                    toastr.warning(res.message)
                }

        },
        error: function (err) {
            let error = err.responseJSON;
            $.each(error.errors, function (index, value) {
                toastr.error(value);
            });
        }
    });
});

    // ************************************************ check vacation *********************************************************

    // Employee edit start
    $(document).on("click", ".employee-edit", function(e) {
        var id = $(this).data('id');
        $.ajax({
            url: id,
            method: 'get',
            processData: false,
            contentType: false,
            success: function(res) {
                $("#edit-modal").empty().append(res);
                $(".datepicker").datepicker({ dateFormat: "dd/mm/yy" });
                $('.summernote_edit').summernote();

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

