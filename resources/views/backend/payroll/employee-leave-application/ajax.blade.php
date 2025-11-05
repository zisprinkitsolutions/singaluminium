<script type="text/javascript">
    $(function() {
            $("#datepicker").datepicker({ dateFormat: "dd/mm/yy" }).val()
    });
</script>
<script>
   $('.start_date, .end_date').on('change', function () {
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


$(document).on("change", ".start_dateu, .end_dateu", function(e) {
    var startDate = $('.start_dateu').datepicker('getDate');
    var endDate = $('.end_dateu').datepicker('getDate');

    if (startDate && endDate) {
        var differenceInDays = (endDate - startDate) / (1000 * 60 * 60 * 24);

        if (differenceInDays >= 0) {
            $('.leave_dayu').val(differenceInDays + 1);
        } else {
            $('.leave_dayu').val('');
            toastr.warning("The 'To' date must be after the 'From' date.");
        }
    } else {
        if (startDate) {
            $('.leave_dayu').val(1);
        } else {
            $('.leave_dayu').val('');
        }
    }
});
    // $(document).on("change", ".emp_id", function(e) {
    //     // Get the selected option's data-emp_id attribute
    //     var emp_id = $(this).find(":selected").data('emp_id');
    //     var remainimg_vacation = $(this).find(":selected").data('remainimg_vacation');
    //     var last_visit = $(this).find(":selected").data('last_visit');
    //     var joining_date = $(this).find(":selected").data('joining_date');
    //     var id = '{{ route('check-vacation') }}';

    //     $.ajax({
    //         url: id,
    //         method: 'get',
    //         data: { emp_id: emp_id, remainimg_vacation: remainimg_vacation , last_visit: last_visit , joining_date: joining_date },
    //         success: function(res) {

    //             if (res.start_date && res.end_date) {

    //                 $('.submit-leave-policy').prop('disabled', false);


    //                 let minDate = $.datepicker.parseDate("dd/mm/yy", res.start_date);
    //                 let maxDate = $.datepicker.parseDate("dd/mm/yy", res.end_date);

    //                 // Initialize or re-initialize the datepickers with min and max dates

    //                 $('.leave_year_form').datepicker({
    //                     dateFormat: "dd/mm/yy",
    //                     minDate: minDate,
    //                     maxDate: maxDate
    //                 });
    //                 $('#leave_year_to').datepicker({
    //                     dateFormat: "dd/mm/yy",
    //                     minDate: minDate,
    //                     maxDate: maxDate
    //                 });

    //             } else {
    //                 // Handle cases where there's no remaining vacation
    //                 toastr.warning(res.message);
    //                 $('.submit-leave-policy').prop('disabled', false);

    //             }
    //         },

    //         error: function(err) {
    //             let error = err.responseJSON;
    //             $.each(error.errors, function(index, value) {
    //                 toastr.error(value);
    //             });
    //         }
    //     });

    // });

    // ********************************************************** add modal show****************************************
    $(document).on("click", ".employee_modal_open", function(e) {
        var modal = $(this).data('modal');
        $(modal).modal('show');
    });

    // ********************************************************** file upload  modal ****************************************
    $(document).on("click", ".employee_file_uplod", function(e) {

        $("#employee_file_uplod_modal").modal('show');
    });

    // ********************************************************** file uploadl show****************************************
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
                // $('.summernote_edit').summernote();


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

