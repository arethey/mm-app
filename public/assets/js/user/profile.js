$('#birthdate_datepicker').datepicker({
    format: "mm/dd/yyyy",
    todayHighlight: true,
    autoclose: true,
    orientation: "bottom"
});

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$.validator.setDefaults({
    submitHandler: function () {

        var form = $('#profile_form');

        $.ajax({
            url: '../user/update-profile',
            type: 'POST',
            dataType: 'json',
            data: {
                id: form.find('#id').val(),
                first_name: form.find('#first_name').val(),
                last_name: form.find('#last_name').val(),
                middle_name: form.find('#middle_name').val(),
                email: form.find('#email').val(),
                birthdate: form.find('#birthdate').val(),
                menstruation_status: form.find('#menstruation_status').val(),
                remarks: form.find('#remarks').val(),
            },
            success: function (data) {
                if (data) {
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
            error: function () {
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

$("#profile_form").validate({
    rules: {
        first_name: {
            required: true,
        },
        last_name: {
            required: true,
        },
        email_address: {
            required: true,
            email: true
        },
        menstruation_status: {
            required: true,
        },
        birthdate: {
            required: true,
            date: true
        }
    },
    messages: {
        first_name: {
            required: "Please enter a first name",
        },
        last_name: {
            required: "Please enter a last name",
        },
        email_address: {
            required: "Please enter the active email of the user",
        },
        menstruation_status: {
            required: "Please select the current menstruation status of the user",
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