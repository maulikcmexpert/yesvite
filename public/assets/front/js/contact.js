$(document).ready(function () {
    var base_url = $("#base_url").val();
    var page = 1;

    $(".product-scroll").on("scroll", function () {
        console.log($(this).scrollTop());
        console.log($(this).innerHeight());
        console.log($(this).scrollHeight());
        if ($(this).scrollTop() + $(this).innerHeight() >= this.scrollHeight) {
            page++;
            loadMoreData(page);
        }
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
});
