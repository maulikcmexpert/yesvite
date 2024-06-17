$(document).ready(function () {
    var base_url = $("#base_url").val();

    var page = 1;

    $(".product-scroll").on("scroll", function () {
        console.log("scrollTop = " + $(this).scrollTop());
        console.log("innerHeight =" + $(this).innerHeight());
        console.log("scrollHeight =" + this.scrollHeight);
        // if ($(this).scrollTop() + $(this).innerHeight() >= this.scrollHeight) {
        if ($(this).scrollTop() + $(this).innerHeight() >= this.scrollHeight) {
            page++;
            // loadMoreData(page, search_name);
            // loadMoreGroups(page, search_group);
            // loadMorePhones(page, search_phone);
        }
    });

    $(document).on("keyup", ".search_name", function () {
        search_name = $(this).val();
        page = 1;
        $("#yesviteUser").html("");
        loadMoreData(page, search_name);
    });

    $(document).on("keyup", ".search_group", function () {
        search_group = $(this).val();
        page = 1;
        $("#yesviteGroups").html("");
        loadMoreGroups(page, search_group);
    });

    $(document).on("keyup", ".search_phone", function () {
        search_phone = $(this).val();
        page = 1;
        $("#yesvitePhones").html("");
        loadMorePhones(page, search_phone);
    });

    function loadMoreData(page, search_name) {
        $.ajax({
            url: base_url + "contacts/load?page=" + page,
            type: "POST",
            data: {
                search_name: search_name,
                _token: $('meta[name="csrf-token"]').attr("content"), // Adding CSRF token
            },
            beforeSend: function () {
                $("#loader").show();
            },
        })
            .done(function (data) {
                if (data.html == " ") {
                    $("#loader").html("No more contacts found");
                    return;
                }
                $("#loader").hide();
                $("#yesviteUser").html(data);
            })
            .fail(function (jqXHR, ajaxOptions, thrownError) {
                alert("server not responding...");
            });
    }

    function loadMoreGroups(page, search_group = "") {
        $.ajax({
            url: base_url + "contacts/loadgroups?page=" + page,
            type: "POST",
            data: {
                search_group: search_group,
                _token: $('meta[name="csrf-token"]').attr("content"), // Adding CSRF token
            },
            beforeSend: function () {
                $("#loader").show();
            },
            success: function (data) {
                if (data.html == " ") {
                    $("#loader").html("No more groups found");
                    return;
                }
                $("#loader").hide();
                $("#yesviteGroups").html(data);
            },
            error: function (jqXHR, ajaxOptions, thrownError) {
                console.error("AJAX Error:", thrownError);
                console.error("Response:", jqXHR.responseText);
                alert("server not responding...");
            },
        });
    }

    function loadMorePhones(page, search_phone = "") {
        $.ajax({
            url: base_url + "contacts/loadphones?page=" + page,
            type: "POST",
            data: {
                search_phone: search_phone,
                _token: $('meta[name="csrf-token"]').attr("content"), // Adding CSRF token
            },
            beforeSend: function () {
                $("#loader").show();
            },
            success: function (data) {
                if (data.html == " ") {
                    $("#loader").html("No more groups found");
                    return;
                }
                $("#loader").hide();
                $("#yesvitePhones").html(data);
            },
            error: function (jqXHR, ajaxOptions, thrownError) {
                console.error("AJAX Error:", thrownError);
                console.error("Response:", jqXHR.responseText);
                alert("server not responding...");
            },
        });
    }

    $(".phone_number").intlTelInput({
        initialCountry: "US",
        separateDialCode: true,
        // utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.4/js/utils.js"
    });

    $("[name=phone_number]").on("blur", function () {
        var instance = $("[name=phone_number]");

        var phoneNumber = instance.intlTelInput(
            "getSelectedCountryData"
        ).dialCode;
        $("#country_code").val(phoneNumber);
    });

    $("#add_contact").validate({
        rules: {
            Fname: "required",
            Lname: "required",
            email: {
                required: true,
                email: true,
            },
            phone_number: {
                required: true,
                digits: true,
                minlength: 10,
                maxlength: 15,
            },
        },
        messages: {
            Fname: "Please enter your First name",
            Lname: "Please enter your Last name",
            email: {
                required: "Please enter your email address",
                email: "Please enter a valid email address",
            },
            phone_number: {
                required: "Please enter a Phone Number",
                digits: "Please enter a valid Phone Number",
                minlength: "Phone Number must be minimum 10 digit",
                maxlength: "Phone Number must be maxmimum 15 digit",
            },
        },
        submitHandler: function (form) {
            var formActionURL = $("#add_contact").attr("action");
            var formData = $("#add_contact").serialize();
            $.ajax({
                method: "POST",
                url: formActionURL,
                // dataType: "json",
                data: formData,

                success: function (output) {
                    console.log(output.user);

                    if (output.status == 1) {
                        removeLoaderHandle("#save_contact", "Save Contact");
                        $("#Fname").val(output.user.firstname);
                        $("#Lname").val(output.user.lastname);

                        $("#email").val(output.user.email);
                        $("#phone_number").val(output.user.phone_number);

                        toastr.success(output.message);

                        $("#add_contact")[0].reset();
                        $("#myModal1").modal("hide");
                    } else {
                        removeLoaderHandle("#save_contact", "Save Contact");
                        toastr.error(output.message);
                    }
                },
            });
        },
    });

    $("#save_contact").click(function () {
        loaderHandle("#save_contact", "Saving");
        $("#add_contact").submit();
    });

    $("#yesviteUser").on("click", ".edit-contact", function (e) {
        alert();
        e.preventDefault(); // Prevent the default action

        var contactId = $(this).data("id");

        $.ajax({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },

            method: "POST",
            url: base_url + "contacts/edit/" + contactId,
            // dataType: "json",
            // data: formData,

            success: function (output) {
                // console.log(output.edit);

                if (output.status == 1) {
                    // alert();
                    $("#edit_Fname").val(output.edit.firstname);
                    $("#edit_Lname").val(output.edit.lastname);
                    $("#phone_number").val(output.edit.phone_number);
                    $("#edit_id").val(output.edit.id);
                }
            },
        });
    });

    $("#edit_contact_form").validate({
        rules: {
            edit_Fname: "required",
            edit_Lname: "required",

            phone_number: {
                required: true,
                digits: true,
                minlength: 10,
                maxlength: 15,
            },
        },
        messages: {
            edit_Fname: "Please enter your First name",
            edit_Lname: "Please enter your Last name",

            phone_number: {
                required: "Please enter a Phone Number",
                digits: "Please enter a valid Phone Number",
                minlength: "Phone Number must be minimum 10 digit",
                maxlength: "Phone Number must be maxmimum 15 digit",
            },
        },
        submitHandler: function (form) {
            var formActionURL = $("#edit_contact_form").attr("action");
            var formData = $("#edit_contact_form").serialize();
            $.ajax({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
                method: "POST",
                url: formActionURL,
                // dataType: "json",
                data: formData,

                success: function (output) {
                    console.log(output.user);

                    if (output.status == 1) {
                        removeLoaderHandle("#save_contact", "Save Contact");
                        $("#Fname").val(output.user.firstname);
                        $("#Lname").val(output.user.lastname);

                        $("#email").val(output.user.email);
                        $("#phone_number").val(output.user.phone_number);

                        toastr.success(output.message);

                        $("#edit_contact_form")[0].reset();
                        $("#myModal").modal("hide");
                        window.location.reload();
                    } else {
                        removeLoaderHandle("#save_contact", "Save Contact");
                        toastr.error(output.message);
                    }
                },
            });
        },
    });

    $("#save_edit_contact").click(function () {
        loaderHandle("#save_edit_contact", "Saving");
        $("#save_edit_contact").submit();
    });
});
