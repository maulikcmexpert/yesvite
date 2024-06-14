$(document).ready(function () {
    var base_url = $("#base_url").val();
    // Initialize jQuery validation
    $("#updateUserForm").validate({
        rules: {
            firstname: "required",
            lastname: "required",
            phone_number: {
                digits: true,
                minlength: 10,
                maxlength: 15,
                remote: {
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    url: base_url + "profile/check-phonenumber", // Your Laravel API endpoint
                    type: "POST",
                    data: {
                        phone_number: function () {
                            return $("#phone_number").val();
                        },
                        id: function () {
                            return $("input[name='id']").val();
                        },
                    },
                },
            },
            zip_code: {
                required: true,
                digits: true,
                minlength: 5,
                maxlength: 9,
            },
        },
        messages: {
            firstname: "Please enter your First name",
            lastname: "Please enter your Last name",
            phone_number: {
                // required: true,
                digits: "Please enter a valid Phone Number",
                minlength: "Phone Number must be minimum 10 digit",
                maxlength: "Phone Number must be maxmimum 15 digit",
                remote: "Phone Number is already exsits",
            },
            zip_code: {
                required: "Please enter Zip Code",
                digit: "Please enter a valid Zip Code",
                minlength: "Zip Code must be minimum 5 digit",
                maxlength: "Zip Code must be maxmimum 9 digit",
            },
        },
        submitHandler: function (form) {
            var formActionURL = $("#updateUserForm").attr("action");
            var formData = $("#updateUserForm").serialize();
            $.ajax({
                method: "POST",
                url: formActionURL,
                dataType: "json",
                data: formData,

                success: function (output) {
                    console.log(output.user);

                    if (output.status == 1) {
                        removeLoaderHandle("#save_changes", "Save Changes");
                        $("#firstname").val(output.user.firstname);
                        $("#lastname").val(output.user.lastname);

                        $("#birth_date").val(output.user.birth_date);
                        $("#email").val(output.user.email);
                        $("#phone_number").val(output.user.phone_number);
                        $("#zip_code").val(output.user.zip_code);
                        $("#about_me").val(output.user.about_me);
                        toastr.success(output.message);
                    } else {
                        removeLoaderHandle("#save_changes", "Save Changes");
                        toastr.error(output.message);
                    }
                },
            });
        },
    });

    // Trigger form submission
    $("#save_changes").click(function () {
        loaderHandle("#save_changes", "Saving");
        $("#updateUserForm").submit();
    });

    

    var profileCroppie = new Croppie(document.getElementById("profileIm"), {
        viewport: { width: 200, height: 200, type: "circle" },
        boundary: { width: 300, height: 300 },
        enableZoom: true,
        enableOrientation: true,
    });

    var bgCroppie = new Croppie(document.getElementById("bgIm"), {
        viewport: { width: 400, height: 200, type: "rectangle" },
        boundary: { width: 400, height: 400 },
        enableZoom: true,
        enableOrientation: true,
    });

    function bindImageToCroppie(croppieInstance, imageUrl) {
        croppieInstance.bind({
            url: imageUrl,
        });
    }

    bindImageToCroppie(profileCroppie, $("#profileIm").attr("src"));

    bindImageToCroppie(bgCroppie, $("#bgIm").attr("src"));

    $("#choose-file").on("change", function () {
        var reader = new FileReader();
        reader.onload = function (e) {
            $("#profileIm").attr("src", e.target.result);
            profileCroppie.bind({
                url: e.target.result,
            });
        };
        reader.readAsDataURL(this.files[0]);
    });

    $("#bg-choose-file").on("change", function () {
        var reader = new FileReader();
        reader.onload = function (e) {
            $("#bgIm").attr("src", e.target.result);
            bgCroppie.bind({
                url: e.target.result,
            });
        };
        reader.readAsDataURL(this.files[0]);
    });

    //for profile image
    $("#profile_save").on("click", function () {
        profileCroppie
            .result({
                type: "base64",
                size: { width: 200, height: 200 },
            })
            .then(function (base64) {
                console.log(base64);

                var blob = dataURItoBlob(base64);

                var croppedFile = new File(
                    [blob],
                    "cropped_profile_image.png",
                    { type: "image/png" }
                );

                var formData = new FormData();
                formData.append("file", croppedFile);

                $.ajax({
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    url: base_url + "upload",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,

                    success: function (response) {
                        if (response.status == 1) {
                            removeLoaderHandle("#profile_save", "Save Changes");
                            toastr.success(response.message);
                            $(document).ready(function () {
                                $(".UserImg").attr("src", response.image);
                            });
                            $("#Edit-modal").modal("hide");
                        } else {
                            toastr.error(response.message);
                        }
                    },
                });

                $("#profileIm").attr("src", base64);
            })
            .catch(function (error) {
                console.error("Croppie error:", error);
            });
    });

    //for background image
    $("#bg_profile_save").on("click", function () {
        bgCroppie
            .result({
                type: "base64",
                size: { width: 964, height: 200 },
            })
            .then(function (base64) {
                console.log(base64);

                var blob = dataURItoBlob(base64);

                var bgcroppedFile = new File(
                    [blob],
                    "cropped_profile_image.png",
                    { type: "image/png" }
                );

                var formData = new FormData();
                formData.append("file", bgcroppedFile);

                $.ajax({
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    url: base_url + "upload_bg_profile",
                    type: "POST",
                    dataType: "json",
                    data: formData,
                    processData: false,
                    contentType: false,

                    success: function (response) {
                        if (response.status == 1) {
                            removeLoaderHandle(
                                "#bg_profile_save",
                                "Save Changes"
                            );
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
                            toastr.success(
                                "Background Profile updated successfully"
                            );
                        }
                        $("#coverImg-modal").modal("hide");
                    },
                });

                $("#profileIm").attr("src", base64);
            })
            .catch(function (error) {
                console.error("Croppie error:", error);
            });
    });

    function dataURItoBlob(dataURI) {
        var byteString = atob(dataURI.split(",")[1]);
        var mimeString = dataURI.split(",")[0].split(":")[1].split(";")[0];
        var ab = new ArrayBuffer(byteString.length);
        var ia = new Uint8Array(ab);
        for (var i = 0; i < byteString.length; i++) {
            ia[i] = byteString.charCodeAt(i);
        }
        return new Blob([ab], { type: mimeString });
    }

    $("#userProfile").on("click", function () {
        setTimeout(function () {
            bindImageToCroppie(profileCroppie, $("#profileIm").attr("src"));
        }, 200);
    });

    $("#bgImage").on("click", function () {
        setTimeout(function () {
            bindImageToCroppie(bgCroppie, $("#bgIm").attr("src"));
        }, 200);
    });

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

    $("#save_password_changes").click(function (event) {
        event.preventDefault();
        if ($("#updateUserPassword").valid()) {
            loaderHandle("#save_password_changes", "Saving");
            $("#updateUserPassword").submit();
        }
    });

    $("#profilePrivacySave").on("click", function () {
        loaderHandle("#profilePrivacySave", "Saving");

        // Serialize the form data
        var formData = $("#profile_privacy").serialize();

        $.ajax({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            url: base_url + "profile/update_profile_privacy",
            type: "POST",
            data: formData,
            processData: true, // Automatically process data into a query string
            contentType: "application/x-www-form-urlencoded; charset=UTF-8", // Default content type for form data
            success: function (response) {
                removeLoaderHandle("#profilePrivacySave", "Save Changes");
                if (response.status == 1) {
                    toastr.success(response.message);
                } else {
                    toastr.error(response.message);
                }
            },
            error: function (xhr, status, error) {
                removeLoaderHandle("#profilePrivacySave", "Save Changes");
                toastr.error("An error occurred: " + error);
            },
        });
    });
});
