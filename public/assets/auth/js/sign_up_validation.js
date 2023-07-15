var date = new Date();
var today = new Date(date.getFullYear(), date.getMonth(), date.getDate());

$('#birthdate_datepicker').datepicker({
    format: "mm/dd/yyyy",
    todayHighlight: true,
    autoclose: true,
    orientation: "bottom",
    endDate: '+0d',
});

$('#birthdate_datepicker').datepicker('setDate', today);

$("#sign_up_form").validate({
    rules: {
        first_name: {
            required: true,
        },
        last_name: {
            required: true,
        },
        email: {
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
        password: {
            required: true,
            minlength: 6
        },
        password_confirmation: {
            required: true,
            equalTo: "#password"
        },
        birthdate: {
            required: true,
            date: true,
        }
    },
    messages: {
        first_name: {
            required: "Please enter your first name",
        },
        last_name: {
            required: "Please enter your last name",
        },
        email: {
            required: "Please enter your active email address",
        },
        menstruation_status: {
            required: "Please select your current menstruation status",
        },
        password: {
            required: "Please enter your password",
            minlength: "Your password must be at least 6 characters long"
        },
        password_confirmation: {
            required: "Please re-enter your password",
            equalTo: "Your password confirmation must be same as password"
        },
        birthdate: {
            required: "Please enter your birthdate",
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