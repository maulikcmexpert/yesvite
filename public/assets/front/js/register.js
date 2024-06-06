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
                        '<span><svg width="21" height="20" viewBox="0 0 21 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M10.5001 18.3346C15.0834 18.3346 18.8334 14.5846 18.8334 10.0013C18.8334 5.41797 15.0834 1.66797 10.5001 1.66797C5.91675 1.66797 2.16675 5.41797 2.16675 10.0013C2.16675 14.5846 5.91675 18.3346 10.5001 18.3346Z" fill="#0DAC5F" /><path d="M6.95825 10.0014L9.31659 12.3597L14.0416 7.64307" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" /></svg></span><span class="character-con" id="passValidation"> At least 8 characters with a combination of letters and numbers</span>'
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
