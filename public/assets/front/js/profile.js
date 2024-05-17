$(document).ready(function () {
    $(document).on("click", "#save_changes", function () {
        var formActionURL = $("#updateUserForm").attr("action");

        $.ajax({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            method: "POST",
            url: formActionURL,
            dataType: "json",
            success: function (output) {
                if (output == true) {
                    table.ajax.reload();
                    toastr.success("Category Deleted successfully !");
                } else {
                    toastr.error("Category don't Deleted !");
                }
            },
        });
    });
});
