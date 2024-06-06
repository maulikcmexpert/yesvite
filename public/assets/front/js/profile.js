$(document).ready(function () {
    var base_url = $("#base_url").val();
    // Initialize jQuery validation
    $("#updateUserForm").validate({
        rules: {
            firstname: "required",
            lastname: "required",
            // gender: "required",
            // birth_date: "required",
            // email: {
            //     required: true,
            //     email: true,
            // },
            // phone_number: {
            //     required: true,
            //     digits: true,
            // },
            // address: "required",
            // city: "required",
            // state: "required",
            zip_code: {
                required: true,
                digits: true,
                minlength: 5,
                maxlength: 6,
            },
            // about_me: "required",
        },
        messages: {
            firstname: "Please enter your First name",
            lastname: "Please enter your Last name",
            // gender: "Please select your gender",
            // birth_date: "Please enter your birth date",
            // email: "Please enter a valid email address",
            // phone_number: "Please enter a valid phone number",
            // address: "Please enter your address",
            // city: "Please enter your city",
            //   state: "Please enter your state",
            zip_code: {
                required: "Please enter Zip Code",
                digit: "Please enter a valid Zip Code",
                minlength: "Zip Code must be minimum 5 digit",
                maxlength: "Zip Code must be minimum 6 digit",
            },
            //  about_me: "Please tell us about yourself",
        },
        submitHandler: function (form) {
            // Form validation passed, submit the form via AJAX
            var formActionURL = $("#updateUserForm").attr("action");
            var formData = $("#updateUserForm").serialize();
            $.ajax({
                // headers: {
                //     "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                // },
                method: "POST",
                url: formActionURL,
                dataType: "json",
                data: formData,

                success: function (output) {
                    console.log(output.user);

                    $("#firstname").val(output.user.firstname);
                    $("#lastname").val(output.user.lastname);
                    // $("#male").val(output.user.male);
                    // $("#female").val(output.user.female);
                    $("#birth_date").val(output.user.birth_date);
                    $("#email").val(output.user.email);
                    $("#phone_number").val(output.user.phone_number);
                    $("#zip_code").val(output.user.zip_code);
                    $("#about_me").val(output.user.about_me);
                    if (output.status == 1) {
                        toastr.success(output.message);
                        //  location.reload();
                    } else {
                        //  location.reload();
                        toastr.error(output.message);
                    }
                },
            });
        },
    });

    // Trigger form submission
    $("#save_changes").click(function () {
        $("#updateUserForm").submit();
    });

    $("#profile_save").on("click", function () {
        var formData = new FormData();
        formData.append("file", $("#choose-file")[0].files[0]);

        $.ajax({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            url: base_url + "upload",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,

            success: function (response) {
                toastr.success("Profile updated successfully");
                $(document).ready(function () {
                    $(".UserImg").attr("src", response);
                });
                $("#Edit-modal").modal("hide");
            },
            error: function (response) {
                if ((response = "")) {
                    toastr.success("Profile updated successfully");
                }
                $("#Edit-modal").modal("hide");
            },
        });
    });

    $("#bg_profile_save").on("click", function () {
        var formData = new FormData();
        formData.append("file", $("#bg-choose-file")[0].files[0]);

        $.ajax({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            url: base_url + "upload_bg_profile",
            type: "POST",
            dataType: "json",
            data: formData,
            processData: false,
            contentType: false,

            success: function (response) {
                if (response.status == 1) {
                    toastr.success(response.message);
                    $(document).ready(function () {
                        $(".bg-img").attr("src", response.image);
                    });
                    $("#coverImg-modal").modal("hide");
                } else {
                    toastr.error(response.message);
                }
            },
            error: function (response) {
                if ((response = "")) {
                    toastr.success("Background Profile updated successfully");
                }
                $("#coverImg-modal").modal("hide");
            },
        });
    });

    // $(document).ready(function () {
    var base_url = $("#base_url").val();
    // Initialize jQuery validation
    $("#updateUserPassword").validate({
        rules: {
            current_password: {
                required: true,
                minlength: 8,
                remote: {
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    url: base_url + "profile/verify_password",
                    type: "post",
                    data: {
                        password: function () {
                            return $("#currentPassword").val();
                        },
                    },
                },
            },
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
            current_password: {
                required: "Please enter your Current password",
                minlength: "Please enter minimum 8 character",
                remote: "Please enter correct Current password",
            },
            new_password: {
                required: "Please enter your New password",
                minlength: "Please enter minimum 8 character",
            },
            conform_password: {
                required: "Please Re-type your New password",
                minlength: "Please enter minimum 8 character",
                equalTo: "New Password did not matched",
            },
        },
    });

    // Trigger form submission
    $("#save_password_changes").click(function () {
        $("#updateUserPassword").submit();
    });
});
