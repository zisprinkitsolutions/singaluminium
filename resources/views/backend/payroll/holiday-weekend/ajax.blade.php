<script type="text/javascript">
    $(function() {
            $("#datepicker").datepicker({ dateFormat: "dd/mm/yy" }).val()
    });

    $('.multiople-select').select2({
            placeholder: "Select Days",
            allowClear: true
        });
</script>
<script>

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

    $(document).on("change", ".select-employee", function(e) {
    var url = '{{ route('weekend-default.check') }}';
    var tr = $(this).closest('tr');
    var emp_id = tr.find('.select-employee').val();

    $.ajax({
        url: url,
        method: 'GET',
        data: { emp_id: emp_id },
        success: function(res) {
            tr.find('.weekend-date').attr('placeholder', res);
        },
        error: function(err) {
            let error = err.responseJSON;
            $.each(error.errors, function(index, value) {
                toastr.error(value);
            });
        }
    });
});

</script>

