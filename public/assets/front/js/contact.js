$(document).ready(function () {
    var base_url = $("#base_url").val();
    var page = 1;

    $(".product-scroll").on("scroll", function () {
        console.log("scrollTop = " + $(this).scrollTop());
        console.log("innerHeight =" + $(this).innerHeight());
        console.log("scrollHeight =" + this.scrollHeight);
        // if ($(this).scrollTop() + $(this).innerHeight() >= this.scrollHeight) {
        page++;
        loadMoreData(page);
        // }
    });

    function loadMoreData(page) {
        $.ajax({
            url: base_url + "contacts/load?page=" + page,
            type: "GET",
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
                $("#yesviteUser").append(data);
            })
            .fail(function (jqXHR, ajaxOptions, thrownError) {
                alert("server not responding...");
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
                        $('#myModal1').modal('hide');
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
});
