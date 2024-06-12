var base_url = $("#base_url").val();
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
        "At least 8 characters with a combination of letters and numbers"
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
                remote: {
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    url: base_url + "check-email", // Your Laravel API endpoint
                    type: "POST",
                    data: {
                        email: function () {
                            return $("#email").val();
                        },
                    },
                },
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
                remote: "Email is already exists",
            },
            zip_code: {
                required: "Please enter your zip code",
            },
            password: {
                required: "Please enter your password",
                passwordCheck:
                    "At least 8 characters with a combination of letters and numbers",
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
            if ($(element).attr("name") == "password") {
                $("#passValidation").html(
                    '<span><svg width="21" height="20" viewBox="0 0 21 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M10.5001 18.3346C15.0834 18.3346 18.8334 14.5846 18.8334 10.0013C18.8334 5.41797 15.0834 1.66797 10.5001 1.66797C5.91675 1.66797 2.16675 5.41797 2.16675 10.0013C2.16675 14.5846 5.91675 18.3346 10.5001 18.3346Z" fill="#0DAC5F" /><path d="M6.95825 10.0014L9.31659 12.3597L14.0416 7.64307" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" /></svg></span><span class="character-con" > At least 8 characters with a combination of letters and numbers</span>'
                );
            }
        },
        submitHandler: function (form) {
            form.submit();
        },
        invalidHandler: function (event, validator) {
            if (
                validator.errorList.some(
                    (error) => error.element.name === "password"
                )
            ) {
                $("#passValidation").html("");
            }
        },
    });
    $("#business").validate({
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
                remote: {
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    url: base_url + "check-email", // Your Laravel API endpoint
                    type: "POST",
                    data: {
                        email: function () {
                            return $("#businessemail").val();
                        },
                    },
                },
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
                equalTo: "#businesspassword",
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
                remote: "Email is already exists",
            },
            zip_code: {
                required: "Please enter your zip code",
            },
            password: {
                required: "Please enter your password",
                passwordCheck:
                    "At least 8 characters with a combination of letters and numbers",
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
            if ($(element).attr("name") == "password") {
                $("#passValidation").html(
                    '<span><svg width="21" height="20" viewBox="0 0 21 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M10.5001 18.3346C15.0834 18.3346 18.8334 14.5846 18.8334 10.0013C18.8334 5.41797 15.0834 1.66797 10.5001 1.66797C5.91675 1.66797 2.16675 5.41797 2.16675 10.0013C2.16675 14.5846 5.91675 18.3346 10.5001 18.3346Z" fill="#0DAC5F" /><path d="M6.95825 10.0014L9.31659 12.3597L14.0416 7.64307" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" /></svg></span><span class="character-con" > At least 8 characters with a combination of letters and numbers</span>'
                );
            }
        },
        submitHandler: function (form) {
            form.submit();
        },
        invalidHandler: function (event, validator) {
            if (
                validator.errorList.some(
                    (error) => error.element.name === "password"
                )
            ) {
                $("#passValidation").html("");
            }
        },
    });
});

// Add keyup event listener to the password field
$("#password").on("keyup", function () {
    var password = $(this).val();
    var isValid = /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/.test(password);

    if (isValid) {
        $("#passValidation").html(
            '<span><svg width="21" height="20" viewBox="0 0 21 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M10.5001 18.3346C15.0834 18.3346 18.8334 14.5846 18.8334 10.0013C18.8334 5.41797 15.0834 1.66797 10.5001 1.66797C5.91675 1.66797 2.16675 5.41797 2.16675 10.0013C2.16675 14.5846 5.91675 18.3346 10.5001 18.3346Z" fill="#0DAC5F" /><path d="M6.95825 10.0014L9.31659 12.3597L14.0416 7.64307" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" /></svg></span><span class="character-con" > At least 8 characters with a combination of letters and numbers</span>'
        );
    } else {
        $("#passValidation").html("");
    }
});
$("#businesspassword").on("keyup", function () {
    var password = $(this).val();
    var isValid = /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/.test(password);

    if (isValid) {
        $("#businesspassValidation").html(
            '<span><svg width="21" height="20" viewBox="0 0 21 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M10.5001 18.3346C15.0834 18.3346 18.8334 14.5846 18.8334 10.0013C18.8334 5.41797 15.0834 1.66797 10.5001 1.66797C5.91675 1.66797 2.16675 5.41797 2.16675 10.0013C2.16675 14.5846 5.91675 18.3346 10.5001 18.3346Z" fill="#0DAC5F" /><path d="M6.95825 10.0014L9.31659 12.3597L14.0416 7.64307" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" /></svg></span><span class="character-con" > At least 8 characters with a combination of letters and numbers</span>'
        );
    } else {
        $("#businesspassValidation").html("");
    }
});
