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

$('label[for="email"]').addClass('floatingfocus');
$('label[for="password"]').addClass('floatingfocus');