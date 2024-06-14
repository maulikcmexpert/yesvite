
$(document).ready(function () {
    var base_url = $("#base_url").val();
    function makeAjaxCall(setting, value) {
        $.ajax({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            url: base_url + "update_account_setting", // Replace with your server endpoint URL
            method: "POST",
            data: {
                setting: setting,
                value: value,
            },
            success: function (response) {
                if (response.status == 1) {
                    toastr.success(response.message);
                } else {
                    toastr.error(response.message);
                }
            },
        });
    }

    $("#photo_via_wifi").change(function () {
        var photo_via_wifi = "0";
        var isChecked = $(this).prop("checked");
        if (isChecked) {
            photo_via_wifi = $(this).val();
        }
        makeAjaxCall("photo_via_wifi", photo_via_wifi);
    });

    $("#show_profile_photo_only_frds").change(function () {
        var show_profile_photo_only_frds = "0";
        var isChecked = $(this).prop("checked");
        if (isChecked) {
            show_profile_photo_only_frds = $(this).val();
        }
        makeAjaxCall(
            "show_profile_photo_only_frds",
            show_profile_photo_only_frds
        );
    });

    $(document).on("change", ".visible", function () {
        var visible = $(this).val();

        makeAjaxCall("visible", visible);
    });

    $.validator.addMethod(
        "typeDelete",
        function (value, element) {
            return this.optional(element) || value === "DELETE";
        },
        "Please type 'DELETE' to confirm."
    );

    $("#DeleteAccount").validate({
        rules: {
            type_word: {
                required: true,
                typeDelete: true,
            },
        },
        messages: {
            type_word: {
                required: "Please type 'DELETE'",
                typeDelete: "Please type 'DELETE' to confirm.",
            },
        },
        submitHandler: function (form) {
            loaderHandle("#DeleteBtn", "Deleting");
            form.submit();
        },
    });

    //  manage notification setting //

    function makeNotificationAjaxCall(setting, type, value) {
        $.ajax({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            url: base_url + "account_settings/update_notification_setting", // Replace with your server endpoint URL
            method: "POST",
            data: {
                type: type,
                setting: setting,
                value: value,
            },
            success: function (response) {
                if (response.status == 1) {
                    toastr.success(response.message);
                } else {
                    toastr.error(response.message);
                }
            },
        });
    }
    $(document).on("change", ".push", function () {
        var push = "0";

        var isPushChecked = $(this).prop("checked");
        if (isPushChecked) {
            push = $(this).val();
        }
        var type = $(this).closest(".right-note").find(".type").val();

        makeNotificationAjaxCall("push", type, push);
    });

    $(document).on("change", ".email", function () {
        var email = "0";

        var isPushChecked = $(this).prop("checked");
        if (isPushChecked) {
            email = $(this).val();
        }
        var type = $(this).closest(".right-note").find(".type").val();

        makeNotificationAjaxCall("email", type, email);
    });

    //  message privacy //

    $("#messagePrivacySave").on("click", function () {
        loaderHandle("#messagePrivacySave", "Saving");
        var formData = $("#messagePrivacy").serialize();
        $.ajax({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            url: base_url + "account_settings/update_message_privacy",
            type: "POST",
            data: formData,

            success: function (response) {
                if (response.status == 1) {
                    removeLoaderHandle("#messagePrivacySave", "Save Changes");
                    toastr.success(response.message);
                } else {
                    toastr.error(response.message);
                }
            },
        });
    });
});
