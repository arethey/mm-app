$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$.validator.setDefaults({
    submitHandler: function () {

        var form = $('#editFeminineForm');

        $.ajax({
            url: '../admin/update-feminine',
            type: 'POST',
            dataType: 'json',
            data: {
                id: form.find('#edit_id').val(),
                edit_menstruation_period_id: form.find('#edit_menstruation_period_id').val(),
                first_name: form.find('#edit_first_name').val(),
                last_name: form.find('#edit_last_name').val(),
                middle_name: form.find('#edit_middle_name').val(),
                email: form.find('#edit_email_address').val(),
                birthdate: form.find('#edit_birthdate').val(),
                menstruation_status: form.find('#edit_menstruation_status').val(),
                last_period_date: form.find('#edit_last_period_date').val(),
                remarks: form.find('#edit_remarks').val(),
            },
            success: function(data) {
                if (data) {
                    $('#feminine_table').DataTable().ajax.reload();
                    
                    $('#editFeminineModal').modal('hide');
                    $('#editFeminineForm').trigger('reset');

                    $('#edit_last_period_datepicker').datepicker('setDate', null);

                    iziToast.success({
                        close: false,
                        displayMode: 2,
                        layout: 2,
                        drag: false,
                        position: 'topRight',
                        title: 'Success!',
                        message: data.message,
                        transitionIn: 'bounceInDown',
                        transitionOut: 'fadeOutUp',
                    });
                }
            },
            error: function() {
                iziToast.error({
                    close: false,
                    displayMode: 2,
                    position: 'topRight',
                    drag: false,
                    title: 'Oops!',
                    message: 'Something went wrong, please try again.',
                    transitionIn: 'bounceInDown',
                    transitionOut: 'fadeOutUp',
                });
            }
        });
    }
});

$("#editFeminineForm").validate({
    rules: {
        edit_first_name: {
            required: true,
        },
        edit_last_name: {
            required: true,
        },
        edit_email_address: {
            required: true,
            email: true
        },
        edit_menstruation_status: {
            required: true,
        },
        last_period_date: {
            required: true,
            date: true
        },
        birthdate: {
            required: true,
            date: true
        }
    },
    messages: {
        edit_first_name: {
            required: "Please enter a first name",
        },
        edit_last_name: {
            required: "Please enter a last name",
        },
        edit_email_address: {
            required: "Please enter the active email of the user",
        },
        edit_menstruation_status: {
            required: "Please select the current menstruation status of the user",
        },
        last_period_date: {
            required: "Please select the last known period date of the user",
        },
        birthdate: {
            required: "Please select the birthdate of the user",
            date: "Please enter a valid date"
        }
    },
    errorPlacement: function (label, element) {
        label.addClass('mt-2 text-danger');
        label.insertAfter(element);
    },
    highlight: function (element, errorClass) {
        $(element).parent().addClass('has-danger')
        $(element).addClass('form-control-danger')
    }
});