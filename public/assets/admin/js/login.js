var base_url = $("#base_url").val();
console.log(base_url);
$("#loginForm").validate({
    rules: {
        email: { required: true, email: true },
        password: { required: true },
    },
    messages: {
        email: {
            required: "Email is required ",
            email: "Please enter valid email",
        },
        password: { required: "Please enter password " },
    },
});
$("#registerPost").validate({
    rules: {
        email: {
            required: true,
            email: true,
            remote: {
                type: "post",
                url: base_url + "/admin/checkEmail",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
            },
        },
        name: { required: true },
        password: { required: true },
        confirm_password: { required: true, equalTo: "#password" },
    },
    messages: {
        email: {
            required: "Email is required ",
            email: "Please enter valid email",
            remote: "This email is already exist.",
        },
        name: { required: "Please enter name " },
        password: { required: "Please enter password " },
        confirm_password: {
            required: "Please enter confirm password ",
            equalTo: "Confrim password and password must be same",
        },
    },
});
$("#forgotForm").validate({
    rules: {
        email: { required: true, email: true },
    },
    messages: {
        email: {
            required: "Email is required ",
            email: "Please enter valid email",
        },
    },
});
$("#updatePassForm").validate({
    rules: {
        password: { required: true },
        confirm_password: { required: true, equalTo: "#password" },
    },
    messages: {
        password: { required: "Please enter password " },
        confirm_password: {
            required: "Please enter confirm password ",
            equalTo: "Confrim password and password must be same",
        },
    },
});

function otp() {
    let inputs = Array.from(document.getElementsByClassName("inputOtp"));
    inputs.forEach((f) =>
        f.addEventListener("keyup", (e) => {
            let val = e.target.value;
            const target = e.target;
            const key = e.key.toLowerCase();

            if (key == "backspace" || key == "delete") {
                target.value = "";
                const prev = target.previousElementSibling;
                if (prev) {
                    prev.focus();
                }
                return;
            }
            if (/[0-9]/.test(val)) {
                let next = e.target.nextElementSibling;
                if (next) next.focus();
            } else {
                e.target.value = "";
            }
        })
    );
}
otp();

$(document).ready(function () {
    $("#twoFactorAuthForm").on("submit", function (e) {
        let isValid = true;
        gatherInputs();
        $("#twoFactorAuthForm-error").html();
        $(".inputOtp").each(function () {
            const value = $(this).val();
            if (value === "" || isNaN(value)) {
                isValid = false;
                $(this).addClass("error");
            } else {
                $(this).removeClass("error");
            }
        });

        if (!isValid) {
            e.preventDefault();
            $("#twoFactorAuthForm-error").html(
                "Please enter a valid 6-digit numeric code."
            );
        }
    });

    $(".inputOtp").on("input", function () {
        if ($(this).val().length === 1) {
            $(this).next(".inputOtp").focus();
        }
    });
});

function gatherInputs() {
    let otp = "";
    for (let i = 1; i <= 6; i++) {
        otp += document.getElementById("otp" + i).value;
    }
    document.getElementById("verification_otp").value = otp;
    return true;
}
