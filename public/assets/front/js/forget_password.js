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

$("#forgetpasswordemail").validate({
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

$.validator.addMethod(
    "passwordCheck",
    function (value, element) {
        return (
            this.optional(element) ||
            /^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{6,}$/.test(value)
        );
    },
    "At least 6 characters with a combination of letters, numbers, and a special character"
);

$("#change_forgetpassword").validate({
    rules: {
        new_password: {
            required: true,
            passwordCheck: true, 
        },
        conform_password: {
            required: true,
            passwordCheck: true, 
            equalTo: "#new_password",
        },
    },
    messages: {
        new_password: {
            required: "Please enter your New password",
            passwordCheck:
            "At least 6 characters with a combination of letters, numbers, and a special character",
    },
        conform_password: {
            required: "Please Re-type your New password",
            // minlength: "Please enter minimum 8 character",
            passwordCheck:
            "At least 6 characters with a combination of letters, numbers, and a special character",
    },
    },

    // errorPlacement: function(error, element) {
    //     if (element.attr("name") === "new_password") {
    //         $("#new_password-error").text(error.text()); // Only append error text to .passworderr

    //     }
    //      else if (element.attr("name") === "conform_password") {
    //         $("#cpassword-error").text(error.text()); // Adjust for conform_password error if needed
 
    //     }
    // }
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
$(document).on('click', '#Next_btn_otp', function () {
    // var now = new Date();
    // var formattedTime = now.toLocaleTimeString();
    // // alert(formattedTime);
    $('#forgetpasswordemail').submit();

});

document.getElementById("otpverify").addEventListener("click", function (event) {

    const now = new Date();
    console.log(submitotptime+""+now)
    const timeDiff = now - submitotptime; // Difference in milliseconds

    // Convert milliseconds to minutes
    const diffInMinutes = Math.floor(timeDiff / (1000 * 60));

    // Check if the time difference is greater than or equal to 15 minutes
    if (diffInMinutes >= 15) {
        var generated_otp = $("#generated_otp").val('');
        // alert('OTP expired');
        toastr.error("Your Otp is Expired Please click on Resend Link to get new Otp");
        return;
    }else{
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
                $('#otpform ').submit();
            } else {
                $("#otp-error").text("OTP is incorrect");
                event.preventDefault();
            }
        }

    }
   
});

$(document).on("click", "#resend_otp", function () {
    var email = $("#useremail").val();
    $('#loader').css('display','block');
    $.ajax({
        url: base_url + "otp_verify",
        type: "POST",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        data: {
            email: email,
        },
        success: function (response) {
            if (response.success == "1") {
                console.log(response.otp);
                $("#generated_otp").val(response.otp);
                $('#otp1').val('');
                $('#otp2').val('');
                $('#otp3').val('');
                $('#otp4').val('');
                $('#loader').css('display','none');
                toastr.success("Otp Resend Sucessfully");
                // location.reload();
                submitotptime = new Date();
            }   
        },
        error: function (xhr, status, error) {
            console.log("AJAX error: " + error);
        },
    });
});


