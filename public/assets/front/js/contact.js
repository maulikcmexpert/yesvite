$(document).ready(function () {
    var base_url = $("#base_url").val();

    // var page = 1;
    var limit = 6;
    var offset = 0;

    var base_url=$('#base_url').val();
    var busy1 = false;
    $("#product-scroll").on("scroll", function () {
        if (busy1) return; 

        var scrollTop = $(this).scrollTop(); 
        var scrollHeight = $(this)[0].scrollHeight; 
        var elementHeight = $(this).height();
            if (scrollTop + elementHeight >= scrollHeight) {
                busy1 = true;
                offset += limit;
                var type="phone";
            loadMoreData(type,offset,limit);
            // function loadMoreData(page, search_name)
            // loadMoreGroups(page, search_group);
            // loadMorePhones(page, search_phone);
        }
    });


    var base_url=$('#base_url').val();
    var busy1 = false;
    
    var limit = 6;
    var offset = 0;
 

    $('#yesviteUser').scroll(function () {
        if (busy1) return; 
        var scrollTop = $(this).scrollTop(); 
        var scrollHeight = $(this)[0].scrollHeight; 
        var elementHeight = $(this).height();
        if (scrollTop + elementHeight >= scrollHeight) {
            busy1 = true;
            offset += limit;
            alert();
            $('.loader').css('display','block');    
    
            $.ajax({
                url: `${base_url}fetch_past_event`,
                type: 'GET',
                data: { limit: limit, offset: offset3,current_month:current_month3},
                success: function (response) {
                     
                },
                error: function (xhr, status, error) {
                    
                }
            });
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

    function loadMoreData(search_name=null,type=null,offset=null,limit=null) {
        $.ajax({
            url: base_url + "contacts/load",
            type: "POST",
            data: {
                search_name: search_name,
                _token: $('meta[name="csrf-token"]').attr("content"), // Adding CSRF token
                type:type,
                offset:offset,
                limit:limit
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


    $(document).on("input","#phone_number", function () {
        // alert();
        let input = $(this).val().replace(/\D/g, ''); // Remove any non-numeric characters
        let formattedNumber = '';
    
        if (input.length <= 3) {
            formattedNumber = input;
        } else if (input.length <= 6) {
            formattedNumber = `${input.slice(0, 3)}-${input.slice(3)}`;
        } else {
            formattedNumber = `${input.slice(0, 3)}-${input.slice(3, 6)}-${input.slice(6, 15)}`;
        }
    
        $(this).val(formattedNumber);
    });


    $.validator.addMethod("phoneUS", function (phone_number, element) {
        phone_number = phone_number.replace(/\D/g, ""); // Remove non-digits for validation
        return (
            this.optional(element) ||
            (phone_number.length === 10 && phone_number.match(/^\d{10}$/))
        );
    }, "Please enter a valid US phone number (e.g., 123-456-7890)");


    $("#add_contact").validate({
        rules: {
            Fname: "required",
            Lname: "required",
            email: {
                required: true,
                email: true,
                remote: {
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    url: base_url + "contacts/check_new_contactemail", // Your Laravel API endpoint
                    type: "POST",
                    data: {
                        email: function () {
                            return $(".addnew_email").val();
                        },
                    },
                },
            },

            phone_number: {
                required: true,
                // digits: true,
                phoneUS: true,
                minlength: 10,
                maxlength: 15,
                remote: {
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    url: base_url + "profile/check_new_contactnumber", // Your Laravel API endpoint
                    type: "POST",
                    data: {
                        phone_number: function () {
                            return $(".addnew_contact").val();
                        },
                        id: function () {
                            return $("input[name='id']").val();
                        },
                    },
                },
            },
        },
        messages: {
            Fname: "Please enter your First name",
            Lname: "Please enter your Last name",
            email: {
                required: "Please enter your email address",
                email: "Please enter a valid email address",
                remote: "Email is already exsits",
            },
            phone_number: {
                required: "Please enter a Phone Number",
                // digits: "Please enter a valid Phone Number",
                phoneUS: "Please enter a valid phone number in the format 123-456-7890",
                minlength: "Phone Number must be minimum 10 digit",
                maxlength: "Phone Number must be maxmimum 15 digit",
                remote: "Phone Number is already exsits",
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
        e.preventDefault(); // Prevent the default action
        $(".form-control").next().addClass("floatingfocus");

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
                    $("#email").val(output.edit.email);
                    $("#phone_number").val(output.edit.phone_number);
                    $("#edit_id").val(output.edit.id);
                }
            },
        });
    });

    $("#yesvitePhones").on("click", ".edit-contact", function (e) {
        // alert();
        e.preventDefault(); // Prevent the default action

        $(".form-control").next().addClass("floatingfocus");

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
                    $("#email").val(output.edit.email);
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
                remote: {
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    url: base_url + "profile/check_new_contactnumber",
                    type: "POST",
                    data: {
                        phone_number: function () {
                            return $(".edit_phone").val();
                        },
                        id: function () {
                            return $("#edit_id").val();
                        },
                    },
                },
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
                remote: "Phone Number is already exsits",
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

    $("#myModal").on("hidden.bs.modal", function (event) {
        $(".form-control").next().removeClass("floatingfocus");
        $("#edit_contact_form .label-error .error").text("");
    });

    $("#myModal1").on("hidden.bs.modal", function (event) {
        $(".form-control").next().removeClass("floatingfocus");
        $("#add_contact .label-error .error").text("");
        $("#add_contact")[0].reset();
    });
});
$('label[for="email"]').removeClass("floatingfocus");
