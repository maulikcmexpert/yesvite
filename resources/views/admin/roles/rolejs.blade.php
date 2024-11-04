<script>
    $(document).ready(function() {
        $("#roleStoreForm").validate({
            rules: {
                name: {
                    required: true,
                    // minlength: 3
                },
                // email: {
                //     required: true,
                //     email: true
                // },

                email: {
                    required: true,
                    email: true,
                    remote: {
                        headers: {
                            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                                "content"
                            ),
                        },
                        url: base_url + "/admin/checkAdminEmail",
                        type: "post",

                    },
                },
                password: {
                    required: true,
                    minlength: 6
                },
                role: {
                    required: true,
                    // minlength: 3
                }
            },
            messages: {
                name: {
                    required: "Please enter a name",
                    // minlength: "Name must be at least 3 characters long"
                },
                // email: {
                //     required: "Please enter an email",
                //     email: "Please enter a valid email address"
                // },
                email: {
                    required: "Email is required ",
                    email: "Please enter valid email",
                    remote: "This email is already exist.",
                },
                password: {
                    required: "Please provide a password",
                    minlength: "Password must be at least 6 characters long"
                },
                role: {
                    required: "Please enter a role",
                    // minlength: "Role must be at least 3 characters long"
                }
            },
            submitHandler: function(form) {
                form.submit();
            }
        });
    });
</script>
