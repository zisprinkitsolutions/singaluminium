<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.0/js/toastr.js"></script>
<script src="{{ asset('assets/backend') }}/app-assets/vendors/js/forms/select/select2.full.min.js"></script>
<script src="{{ asset('assets/backend') }}/app-assets/js/scripts/forms/select/form-select2.js"></script>
<script src="{{ asset('assets/backend') }}/app-assets/vendors/js/forms/repeater/jquery.repeater.min.js"></script>
<script src="{{ asset('assets/backend') }}/app-assets/js/scripts/forms/form-repeater.js"></script>
{{-- js work by mominul start --}}
<script>
    function refreshPage() {
        window.location.reload();
    }
</script>
{{-- js work by mominul end --}}

<script>
    function BtnAdd() {
        /* Add Button */
        var newRow = $("#TBody tr:first").clone();
        newRow.find('textarea').prop('readonly', false);
        newRow.removeClass("d-none");
        newRow.find("input, select,textarea").val('').attr('name', function(index, name) {
            return name.replace(/\[\d+\]/, '[' + ($('#TBody tr').length) + ']');
        });
        newRow.find("th").first().html($('#TBody tr').length + 1);
        newRow.appendTo("#TBody");
        newRow.find(".common-select2").select2();
    }

    function BtnDel(v) {
        /* Delete Button */
        $(v).parent().parent().remove();
        $("#TBody").find("tr").each(function(index) {
            $(this).find("th").first().html(index);
        });
        total()
    }

    // $('.btn_create').click(function(){
    $(document).on("click", ".btn_create", function(e) {
        e.preventDefault();
        // alert('Alhamdulillah');
        setTimeout(function() {
            $('.multi-acc-head').select2();
            $('.multi-tax-rate').select2();
        }, 1000);
    });

    $('#pay_mode').change(function() {
        if ($(this).val() == 'Cheque') {
            $(".deposit_date").attr('required', true);
            $("#bank_branch").attr('required', true);;
            $("#issuing_bank").attr('required', true);
            $("#cheque_no").attr('required', true);
            $('.cheque-content').show();

        } else {
            $(".deposit_date").removeAttr('required');
            $("#bank_branch").removeAttr('required');;
            $("#issuing_bank").removeAttr('required');
            $("#cheque_no").removeAttr('required');
            $('.cheque-content').hide();
        }
    });
    $(document).on('submit', "#formSubmit", function(e) {
        // $("#submitButton").prop("disabled", true)
        e.preventDefault(); // avoid executing the actual submit of the form.

        var form = $(this); // current form

        // // Grab values within this form only
        // var taxableAmountLimit = parseFloat(form.find('.taxable-amount').val()) || 0;
        // var taxableAmount = parseFloat(form.find('#taxable_amount').val()) || 0;

        // var remainingVatLimit = parseFloat(form.find('.remaining-vat').val()) || 0;
        // var totalVat = parseFloat(form.find('#total_vat').val()) || 0;

        // var totalRemainingLimit = parseFloat(form.find('.total-remaining').val()) || 0;
        // var totalAmount = parseFloat(form.find('#total_amount').val()) || 0;

        // var retentionTransfered = parseFloat(form.find('#retention_transferred').val()) || 0;
        // var remainingRetention = taxableAmountLimit - taxableAmount;
        // // Validation checks
        // if (taxableAmount > taxableAmountLimit) {
        //     Swal.fire({
        //         title: "Taxable amount cannot exceed " + taxableAmountLimit,
        //         icon: 'warning',
        //         confirmButtonText: 'OK'
        //     });
        //     return false;
        // }

        // if (totalVat > remainingVatLimit) {
        //     Swal.fire({
        //         title: "VAT amount cannot exceed " + remainingVatLimit,
        //         icon: 'warning',
        //         confirmButtonText: 'OK'
        //     });
        //     return false;
        // }

        // if (retentionTransfered > remainingRetention) {
        //     Swal.fire({
        //         title: "Retention amount cannot exceed " + remainingRetention,
        //         icon: 'warning',
        //         confirmButtonText: 'OK'
        //     });
        //     return false;
        // }

        // if (totalAmount > totalRemainingLimit) {
        //     Swal.fire({
        //         title: "Total amount cannot exceed " + totalRemainingLimit,
        //         icon: 'warning',
        //         confirmButtonText: 'OK'
        //     });
        //     return false;
        // }
        var url = form.attr('action');
        var data = new FormData(this);
        $.ajax({
            url: url,
            method: 'POST',
            data: data,
            contentType: false,
            cache: false,
            processData: false,
            success: function(response) {
                if (response.warning) {
                    toastr.warning("{{ Session::get('message') }}", response.warning);
                } else if (response.status) {
                    // Handle validation errors
                    for (var i = 0; i < Object.keys(response.status).length; i++) {
                        var key = i + ".invoice";
                        if (response.status.hasOwnProperty(key)) {
                            var errorMessages = response.status[key];
                            for (var j = 0; j < errorMessages.length; j++) {
                                toastr.warning(errorMessages[j]);
                            }
                        }
                    }
                } else {
                    document.getElementById("voucherPreviewShow").innerHTML = response.preview;
                    $('#voucherPreviewModal').modal('show');
                    $('#sale-body').html(response.approve_list);
                }
            },
            error: function(err) {
                let error = err.responseJSON;
                $.each(error.errors, function(index, value) {
                    toastr.error(value, "Error");
                });
            }
        });
    });

    $(document).on('submit', "#editFormSubmit", function(e) {
        e.preventDefault(); // avoid executing the actual submit of the form.
        var form = $(this); // current form

        // // Grab values within this form only
        // var taxableAmountLimit = parseFloat(form.find('.taxable-amount').val()) || 0;
        // var taxableAmount = parseFloat(form.find('#taxable_amount').val()) || 0;

        // var remainingVatLimit = parseFloat(form.find('.remaining-vat').val()) || 0;
        // var totalVat = parseFloat(form.find('#total_vat').val()) || 0;

        // var totalRemainingLimit = parseFloat(form.find('.total-remaining').val()) || 0;
        // var totalAmount = parseFloat(form.find('#total_amount').val()) || 0;
        // var retentionTransfered = parseFloat(form.find('#retention_transferred').val()) || 0;
        // var remainingRetention = taxableAmountLimit - taxableAmount;
        // // Validation checks
        // if (taxableAmount > taxableAmountLimit) {
        //     Swal.fire({
        //         title: "Taxable amount cannot exceed " + taxableAmountLimit,
        //         icon: 'warning',
        //         confirmButtonText: 'OK'
        //     });
        //     return false;
        // }

        // if (totalVat > remainingVatLimit) {
        //     Swal.fire({
        //         title: "VAT amount cannot exceed " + remainingVatLimit,
        //         icon: 'warning',
        //         confirmButtonText: 'OK'
        //     });
        //     return false;
        // }

        //   if (retentionTransfered > remainingRetention) {
        //     Swal.fire({
        //         title: "Retention amount cannot exceed " + remainingRetention,
        //         icon: 'warning',
        //         confirmButtonText: 'OK'
        //     });
        //     return false;
        // }

        // if (totalAmount > totalRemainingLimit) {
        //     Swal.fire({
        //         title: "Total amount cannot exceed " + totalRemainingLimit,
        //         icon: 'warning',
        //         confirmButtonText: 'OK'
        //     });
        //     return false;
        // }

        var url = form.attr('action');
        var data = new FormData(this);
        $.ajax({
            url: url,
            method: 'POST',
            data: data,
            contentType: false,
            cache: false,
            processData: false,
            success: function(response) {
                if (response.warning) {
                    toastr.warning("{{ Session::get('message') }}", response.warning);
                } else if (response.status) {
                    // Handle validation errors
                    for (var i = 0; i < Object.keys(response.status).length; i++) {
                        var key = i + ".invoice";
                        if (response.status.hasOwnProperty(key)) {
                            var errorMessages = response.status[key];
                            for (var j = 0; j < errorMessages.length; j++) {
                                toastr.warning(errorMessages[j]);
                            }
                        }
                    }
                } else {
                    // $("#submitButton").prop("disabled", true)
                    // $(".deleteBtn").prop("disabled", true)
                    // $(".addBtn").prop("disabled", true)
                    document.getElementById("voucherPreviewShow").innerHTML = response.preview;
                    $('#voucherPreviewModal').modal('show');
                    $('#sale-body').html(response.approve_list);
                    // $("#newButton").removeClass("d-none")
                    // $("#submitButton").addClass("d-none")
                }
                $('.show-edit-form').hide();
            },
            error: function(err) {
                let error = err.responseJSON;
                $.each(error.errors, function(index, value) {
                    toastr.error(value, "Error");
                });
            }
        });
    });

    $("#date").focus();
    $('#project').change(function() {
        console.log($(this).val());
        if ($(this).val() != '') {
            var value = $(this).val();
            var _token = $('input[name="_token"]').val();
            $.ajax({
                url: "{{ route('findProject') }}",
                method: "POST",
                data: {
                    value: value,
                    _token: _token,
                },
                success: function(response) {
                    $("#owner").val(response.owner_name);
                    $("#location").val(response.address);
                    $("#address").val(response.address);
                    $("#mobile").val(response.cont_no);
                }
            })
        }
    });

    $(document).on('change', '#invoice_type', function() {
        var invoice_type = $(this).val();
        var selectElement = document.getElementById("pay_mode");
        var options = selectElement.options;
        for (var i = 0; i < options.length; i++) {
            var option = options[i];
            if (option.value == 'Advance' && invoice_type != 'Tax Invoice') {
                option.setAttribute('disabled', 'disabled');
            } else {
                option.removeAttribute('disabled', 'disabled');
            }
        }
    })




    $(document).on('change', '#party_id , #party_info', function() {
        var selectElement = document.getElementById("pay_mode");
        var options = selectElement.options;
        if ($(this).val() != '') {
            var value = $(this).val();
            var _token = $('input[name="_token"]').val();
            $.ajax({
                url: "{{ route('partyInfoInvoice2') }}",
                method: "POST",
                data: {
                    value: value,
                    _token: _token,
                },
                success: function(response) {
                    var party = response.info;
                    var projects = response.projects;

                    $("#trn_no").val(party.trn_no);
                    $("#pi_code").val(party.pi_code);
                    $("#party_contact").val(party.con_no);
                    $("#party_address").val(party.address);
                    $("#attention").val(party.con_person);

                    if (party.balance > 0) {
                        $("#available_balance").html('Available Balance ' + party.balance);
                    }

                    $("#invoice_no").focus();
                    for (var i = 0; i < options.length; i++) {
                        var option = options[i];
                        if (option.value == 'Advance' && Number(party.balance) == 0) {
                            option.setAttribute('disabled', 'disabled');
                        } else {
                            option.removeAttribute('disabled', 'disabled');
                        }
                    }

                    $('.job_project_id').empty();
                    $('.job_project_id').append('<option value=""> Select </option>')

                    $.each(projects, function(index, project) {
                        $('.job_project_id').append(
                            `<option value="${project.id}">${project.project_name} (${project.project_code}</option>`
                        );
                    });
                }
            })
        }
    });



    $(document).on("keyup", "#pi_code", function(e) {
        // alert(1);
        var value = $(this).val();
        var _token = $('input[name="_token"]').val();
        if ($(this).val() != '') {
            $.ajax({
                url: "{{ route('partyInfoInvoice3') }}",
                method: "POST",
                data: {
                    value: value,
                    _token: _token,
                },
                success: function(response) {
                    console.log(response);
                    var qty = 1;
                    if (response != '') {
                        $("div.search-item-pi select").val(response.id);
                        $('.common-select2').select2();
                        $("#trn_no").val(response.trn_no);
                        $("#party_contact").val(response.con_no);
                        $("#party_address").val(response.address);

                        $("#invoice_no").focus();
                    }
                }
            })
        }
    });

    $(document).on("change", "#party", function(e) {
        e.preventDefault();
        $('.date').val('')
        var id = $(this).val();
        var invoice_no = $('#invoice_no').val();
        $.ajax({
            url: "{{ URL('find-invoice') }}",
            type: "post",
            cache: false,
            data: {
                _token: '{{ csrf_token() }}',
                id: id,
                invoice_no: invoice_no,
            },
            success: function(response) {
                $('#table-body').empty().append(response);
            }
        });
    });


    $(document).on("keyup", "#invoice_no", function(e) {
        var inv = $(this).val();
        var party = $('#party_info').val();
        var _token = $('input[name="_token"]').val();
        $.ajax({
            url: "{{ route('invoice_no_validation') }}",
            method: "POST",
            data: {
                inv: inv,
                party: party,
                _token: _token,
            },
            success: function(response) {
                if (response.warning) {

                    toastr.warning(response.warning);
                }

            }
        })
    });

    function calculateAmount($row) {
        var rate = parseFloat($row.find(".rate").val()) || 0;
        var qty = parseFloat($row.find(".qty").val()) || 0;
        var vatRate = parseFloat($row.find('.vat_rate').val()) || 0;
        var amount = parseFloat($row.find('.amount').val()) || 0;
        var vatAmount = (amount * vatRate) / 100;
        totalAmount = vatAmount + amount;
        $row.find(".vat_amount").val(vatAmount.toFixed(2));
        $row.find('.sub_total').val(totalAmount.toFixed(2));
        var retntn = $('.retention_transferred').val();
        total(retntn);
    }

    $(document).on('keyup', '.rate , .qty, .amount, .retention_transferred', function() {
        var $row = $(this).closest("tr");
        calculateAmount($row);
    });

    $(document).on('change', '.vat_rate', function() {
        var $row = $(this).closest("tr");
        calculateAmount($row);
    });

    $(document).on("change", "#invoice_type", function(e) {
        total()
    });

    function total(retntn) {
        var sum = 0;
        var vat = 0;
        $('.amount').each(function() {
            var this_amount = $(this).val();
            this_amount = (this_amount === '') ? 0 : this_amount;
            var this_amount = parseFloat(this_amount);
            vat += parseFloat($(this).closest("tr").find('.vat_amount').val()) || 0;
            sum = sum + this_amount;
        });
        var result = sum.toFixed(2)
        var standard_vat_rate = $('#standard_vat_rate').val();
        var invoice_type = $('#invoice_type').val();
        // if(invoice_type!='Direct Invoice' )
        // {
        //     var vat=(standard_vat_rate/100)*result;
        // }
        // else
        // {
        //     var vat=0;
        // }
        var total_amount = sum + vat;
        var retention_amount = 0;
        $(".taxable_amount").val(result);
        $(".retention_amount").val(retention_amount);
        var retention_transferred = retntn * 1;
        $(".total_vat").val(vat.toFixed(2))
        // $(".retention_transferred").val(retention_amount);
        // alert(retention_transferred);
        $(".total_amount").val((total_amount + retention_transferred).toFixed(2));
    };

    $(document).on('submit', '.voucher-img-form', function(e) {
        e.preventDefault(); // stop form submission

        if (!confirm('Are you sure you want to delete this file?')) return;

        let wrapper = $(this).closest('.voucher-img-wrapper');
        let url = $(this).attr('action');

        $.ajax({
            url: url,
            method: 'POST',
            data: {
                _method: 'DELETE', // simulate DELETE request
                _token: '{{ csrf_token() }}'
            },
            success: function(res) {
                wrapper.remove();
                toastr.success(res.message || 'File deleted successfully.');
            },
            error: function() {
                alert('Failed to delete the file.');
            }
        });
    });

    let selectedFiles = [];

    $(document).on('change', '.file_upload', function(e) {
        selectedFiles = Array.from(e.target.files);
        renderFileList(this);
    });

    function renderFileList(inputElement) {
        const list = $(inputElement).closest('.form-group').find('.fileList');
        list.empty();

        selectedFiles.forEach((file, index) => {
            list.append(`
                <li>
                    ${file.name}
                    <button type="button" class="remove-btn" data-index="${index}">Remove</button>
                </li>
            `);
        });
    }

    $(document).on('click', '.remove-btn', function() {
        const index = $(this).data('index');
        selectedFiles.splice(index, 1);

        // Rebuild new FileList and re-assign to input
        const dt = new DataTransfer();
        selectedFiles.forEach(file => dt.items.add(file));
        const fileInput = document.getElementById('voucher_scan');
        fileInput.files = dt.files;

        renderFileList(fileInput);
    });

    $(document).on('click', '.remove-btn', function() {
        const index = $(this).data('index');
        selectedFiles.splice(index, 1);

        // Rebuild new FileList and re-assign to input
        const dt = new DataTransfer();
        selectedFiles.forEach(file => dt.items.add(file));
        const fileInput = document.getElementById('voucher_scan2');
        fileInput.files = dt.files;

        renderFileList(fileInput);
    });


    //edit
    function addEditRow() {
        var newRow = $("#editTBody tr:first").clone();
        newRow.find('textarea').prop('readonly', false);
        newRow.find("input, select,textarea").val('').attr('name', function(index, name) {
            return name.replace(/\[\d+\]/, '[' + ($('#editTBody tr').length) + ']');
        });
        newRow.find("th").first().html($('#editTBody tr').length + 1);
        newRow.appendTo("#editTBody");
        newRow.find(".common-select2").select2();
    }



    $(document).on("change", ".job_project_id", function(e) {
        // alert(1);
        let project = $(this).val();
        var _token = $('input[name="_token"]').val();
        if ($(this).val() != '') {
            $.ajax({
                url: "{{ route('project-limit') }}",
                method: "POST",
                data: {
                    project: project,
                    _token: _token,
                },
                success: function(response) {
                    console.log(response);
                    $('.remaining-vat').val(response.remaining_vat);
                    $('.total-remaining').val(response.total_remaining);
                    $('.taxable-amount').val(response.remaining);
                    $('#total_amount').attr('max', response.total_remaining);
                }
            })
        }
    });
</script>
