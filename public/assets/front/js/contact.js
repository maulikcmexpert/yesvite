$(document).ready(function () {
    var base_url = $("#base_url").val();
    var page = 1;

    $(".product-scroll").on("scroll", function () {
        if ($(this).scrollTop() + $(this).innerHeight() >= this.scrollHeight) {
            page++;
            loadMoreItems(page);
        }
    });

    function loadMoreData(page) {
        $.ajax({
            url: base_url + "contacts/load?page=" + page,
            type: "GET",
            beforeSend: function () {
                $("#loading").show();
            },
        })
            .done(function (data) {
                if (data.html == " ") {
                    $("#loading").html("No more contacts found");
                    return;
                }
                $("#loading").hide();
                $("#yesviteUser").append(data);
            })
            .fail(function (jqXHR, ajaxOptions, thrownError) {
                alert("server not responding...");
            });
    }
});
