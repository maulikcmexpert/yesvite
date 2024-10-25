var base_url = $("#base_url").val();
console.log(base_url);
$("#loginForm").validate({
    rules: {
        email: { required: true, email: true },
        password: { required: true, minlength: 8 },
    },
    messages: {
        email: {
            required: "Please enter Email",
            email: "Please enter valid Email",
        },
        password: {
            required: "Please enter Password",
            minlength: "Password must be at least 8 characters",
        },
        submitHandler: function (form) {
            loaderHandle("#loginUser", "Signing..");
        },
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

$(".form-control").on("focusout change keyup focus", function () {
    var text_val = $(this).val();
    if (text_val === "") {
        $(this).next().removeClass("floatingfocus");
    } else {
        $(this).next().addClass("floatingfocus");
    }
});

$(".form-control").each(function () {
    var text = $(this).val();
    if (text === "") {
        $(this).next().removeClass("floatingfocus");
    } else {
        $(this).next().addClass("floatingfocus");
    }
});

$('html').mouseover(function() {
    $(".form-control").each(function () {
        var text = $(this).val();
        if (text === "") {
            $(this).next().removeClass("floatingfocus");
        } else {
            $(this).next().addClass("floatingfocus");
        }
    });
});
$('label[for="email"]').removeClass("floatingfocus");
$('label[for="password"]').removeClass("floatingfocus");
