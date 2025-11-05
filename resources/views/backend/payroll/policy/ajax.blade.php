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

