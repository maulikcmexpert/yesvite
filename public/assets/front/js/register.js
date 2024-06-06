$(document).ready(function () {
    // Add a custom validation method for the password
    $.validator.addMethod(
        "passwordCheck",
        function (value, element) {
            return (
                this.optional(element) ||
                /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/.test(value)
            );
        },
        "Password must be at least 8 characters long and contain both letters and numbers."
    );

    $("#register").validate({
        rules: {
            firstname: {
                required: true,
            },
            lastname: {
                required: true,
            },
            email: {
                required: true,
                email: true,
            },
            zip_code: {
                required: true,
            },
            password: {
                required: true,
                passwordCheck: true, // Apply the custom password validation method
            },
            cpassword: {
                required: true,
                equalTo: "#password",
            },
        },
        messages: {
            firstname: {
                required: "Please enter your first name",
            },
            lastname: {
                required: "Please enter your last name",
            },
            email: {
                required: "Please enter your email",
                email: "Please enter a valid email address",
            },
            zip_code: {
                required: "Please enter your zip code",
            },
            password: {
                required: "Please enter your password",
                passwordCheck:
                    "Your password must be at least 8 characters long and contain both letters and numbers",
            },
            cpassword: {
                required: "Please confirm your password",
                equalTo: "Passwords do not match",
            },
        },
        errorPlacement: function (error, element) {
            error.insertAfter(element);
            error.css("color", "red");
        },
        success: function (label, element) {
            if ($(element).attr("name") === "password") {
                $("#passValidation")
                    .text(
                        "At least 8 characters with a combination of letters and numbers"
                    )
                    .css({
                        color: "green",
                        border: "1px solid green",
                        display: "inline-block",
                        padding: "2px 4px",
                        borderRadius: "4px",
                    });
            }
        },
        submitHandler: function (form) {
            form.submit();
        },
        invalidHandler: function (event, validator) {},
    });
});
