$("#forgetpassword").validate({
    rules: {
        email: {
            required: true,
            email: true,
            remote: {
                type: "post",
                url: base_url + "check_mail",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
                data: {
                    email: function () {
                        return $("#email").val();
                    },
                },
            },
        },
    },
    messages: {
        email: {
            required: "Email is required",
            email: "Please enter a valid email",
            remote: "This email is not registered.",
        },
    },
});

// $("#otpform").validate({
//     rules: {
//         otp: {
//             required: true,
//             equalTo: "#generated_otp"
//         },

//     },
//     messages: {
//         otp: {
//             required: "Otp is required",
//             equalTo:"Otp didn't matched check again"
//         },
//     },
// });

$("#change_forgetpassword").validate({
    rules: {
        new_password: {
            required: true,
            minlength: 8,
        },
        conform_password: {
            required: true,
            minlength: 8,
            equalTo: "#new_password",
        },
    },
    messages: {
        new_password: {
            required: "Please enter your New password",
            minlength: "Please enter minimum 8 character",
        },
        conform_password: {
            required: "Please Re-type your New password",
            minlength: "Please enter minimum 8 character",
            equalTo: "Password did not matched",
        },
    },
});

document.addEventListener("DOMContentLoaded", function () {
    const otpInputs = document.querySelectorAll(".otp__digit");

    otpInputs.forEach((input, index) => {
        input.addEventListener("input", () => {
            if (input.value.length === 1 && index < otpInputs.length - 1) {
                otpInputs[index + 1].focus();
            }
        });

        input.addEventListener("keydown", (event) => {
            if (
                event.key === "Backspace" &&
                input.value.length === 0 &&
                index > 0
            ) {
                otpInputs[index - 1].focus();
            }
        });
    });
});

document.getElementById("otpform").addEventListener("submit", function (event) {
    const otpFields = document.querySelectorAll(".otp__digit");
    let isValid = true;
    let errorMessage = "";

    otpFields.forEach((field) => {
        if (field.value.trim() === "") {
            isValid = false;
        }
    });

    if (!isValid) {
        errorMessage = "Please enter the OTP.";
        document.getElementById("otp-error").textContent = errorMessage;
        event.preventDefault();
    } else {
        document.getElementById("otp-error").textContent = "";
        var otp1 = $("#otp1").val();
        var otp2 = $("#otp2").val();
        var otp3 = $("#otp3").val();
        var otp4 = $("#otp4").val();

        var otp = otp1 + otp2 + otp3 + otp4;
        var generated_otp = $("#generated_otp").val();

        if (otp == generated_otp) {
            $("#otp-error").text("");
        } else {
            $("#otp-error").text("OTP is incorrect");
            event.preventDefault();
        }
    }
});
