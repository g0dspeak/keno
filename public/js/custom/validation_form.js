/* validate form */
$(".form-container").validate({
    errorClass: 'help-block',
    errorElement: "strong",
    rules: {
        card_number: {
            required: true,
            minlength: 12,
            maxlength: 12
        },
        cvn: {
            required: false,
            minlength: 6,
            maxlength: 6
        },
        email: {
            required: true,
            email: true,
            maxlength: 255
        },
        first_name: {
            required: true,
            maxlength: 255
        },
        middle_name: {
            maxlength: 255
        },
        last_name: {
            required: true,
            maxlength: 255
        },
        gender: {
            maxlength: 10
        },
        language: {
            maxlength: 3
        },
        mobile_number: {
            required: true,
            maxlength: 255
        }
    },
    highlight: function(element, errorClass) {
        var formGroup = $(element).closest('.form-group');

        formGroup.addClass('has-error');
    },
    unhighlight: function(element, errorClass) {
        var formGroup = $(element).closest('.form-group');

        formGroup.removeClass('has-error');
    }
});
/* /validate form */