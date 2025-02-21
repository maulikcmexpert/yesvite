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
                        url: "{{URL::to('admin/user/checkAdminEmail')}}",
                        type: "POST",
                        data: {
                            email: function() {
                                return $(".email").val();
                            },

                        },
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
                    required: "Please Enter Email",
                    email: "Please Enter a Valid Email",
                    remote: "Email is already exsits",

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


    $('#roleEditForm').validate({
                    rules: {

                        phone_number: {
                        // required: true,
                        digits: true,
                        minlength: 10,
                        maxlength: 15,
                        // remote: {
                        //     headers: {
                        //         "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        //             "content"
                        //         ),
                        //     },
                        //     url: "{{URL::to('admin/user/check_new_contactnumber')}}",
                        //     type: "POST",
                        //     data: {
                        //         phone_number: function() {
                        //             return $(".phone_number").val();
                        //         },
                        //         id: function() {
                        //             return $("#edit_id").val();
                        //         },
                        //     },
                        // },
                    }

                    },
                    messages: {
                        phone_number: {
                        // required: "Please Enter Mobile Number",
                        required: "Please enter a Phone Number",
                        digits: "Please enter a valid Phone Number",
                        minlength: "Phone Number must be minimum 10 digit",
                        maxlength: "Phone Number must be maxmimum 15 digit",
                        // remote: "Phone Number is already exsits",
                        }
                    }
        });

</script>
