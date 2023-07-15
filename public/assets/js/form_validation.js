var date = new Date();
var today = new Date(date.getFullYear(), date.getMonth(), date.getDate());

$('#last_period_datepicker').datepicker({
    format: "mm/dd/yyyy",
    todayHighlight: true,
    autoclose: true,
    orientation: "bottom",
    endDate: '+0d',
});

$('#birthdate_datepicker').datepicker({
    format: "mm/dd/yyyy",
    todayHighlight: true,
    autoclose: true,
    orientation: "bottom"
});

$('#last_period_datepicker').datepicker('setDate', today);
$('#birthdate_datepicker').datepicker('setDate', today);

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$.validator.setDefaults({
    submitHandler: function () {

        var form = $('#newFeminineForm');

        $.ajax({
            url: '../admin/new-feminine',
            type: 'POST',
            dataType: 'json',
            data: {
                first_name: form.find('#first_name').val(),
                last_name: form.find('#last_name').val(),
                middle_name: form.find('#middle_name').val(),
                email: form.find('#email_address').val(),
                birthdate: form.find('#birthdate').val(),
                menstruation_status: form.find('#menstruation_status').val(),
                last_period_date: form.find('#last_period_date').val(),
                remarks: form.find('#remarks').val(),
            },
            success: function (data) {
                if(data) {
                    $('#feminine_count').text(data.feminine_count.feminine_count);
                    $('#active_feminine_count').text(data.feminine_count.active_feminine_count);
                    $('#inactive_feminine_count').text(data.feminine_count.inactive_feminine_count);

                    if (window.location.href.includes('admin/feminine-list')) {
                        $('#feminine_table').DataTable().ajax.reload();
                    }
                    
                    $('#newFeminineModal').modal('hide');
                    $('#newFeminineForm').trigger('reset');

                    $('#last_period_datepicker').datepicker('setDate', today);

                    iziToast.success({
                        close: false,
                        displayMode: 2,
                        layout: 2,
                        position: 'topCenter',
                        drag: false,
                        title: 'Success!',
                        message: data.message,
                        transitionIn: 'bounceInDown',
                        transitionOut: 'fadeOutUp',
                        timeout: 7000 // 7 seconds
                    });
                }
            },
            error: function (response) {
                iziToast.error({
                    close: false,
                    displayMode: 2,
                    position: 'topCenter',
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

$("#newFeminineForm").validate({
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
            date: "Please enter a valid date",
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

$('#newFeminineModal').on('shown.bs.modal', function () {
    $('#newFeminineForm').trigger('reset');
    $('#last_period_datepicker').datepicker('setDate', today);
    $('#birthdate_datepicker').datepicker('setDate', today);
});

$('#newFeminineModal').on('hidden.bs.modal', function () {
    $('#newFeminineForm').trigger('reset');
    $('#last_period_datepicker').datepicker('setDate', today);
});