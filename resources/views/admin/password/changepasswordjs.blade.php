<script type="text/javascript">
    $(function() {
        $("#ChangePasswordForm").validate({
        rules: {
            current_password: {
                required: true,
                minlength: 6,
                remote: {
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    url: "{{URL::to('admin/verifyPassword')}}",
                    type: "post",
                    data: {
                        password: function () {
                            return $("#current_password").val();
                        },
                    },
                },
            },
            new_password: {
                required: true,
                minlength: 6,
                maxlength: 20,
            },
            confirm_password: {
                required: true,
                minlength: 6,
                maxlength: 20,
                equalTo: "#new_password",
            },
        },
        messages: {
            current_password: {
                required: "Please enter Current password",
                minlength: "Please enter minimum 6 character",
                remote: "Please enter correct Current password",
            },
            new_password: {
                required: "Please enter New password",
                minlength: "Please enter minimum 6 character",
                maxlength: "Please enter minimum 20 character",

            },
            confirm_password: {
                required: "Please enter confirm password",
                minlength: "Please enter minimum 6 character",
                maxlength: "Please enter minimum 20 character",
                equalTo: "New Password and Confirm Password does not match",
            },
        },
    });
    })
 </script>   