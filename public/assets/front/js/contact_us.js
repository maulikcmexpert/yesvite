$(document).ready(function(){
    // alert();
$("#contact_us_form").validate({
    rules: {
        name: "required",
        email: "required",
        message:"required"
    },
    messages: {
        name: "Please enter your First name",
        email: "Please enter your Email address",
        message:"Please enter your Message"
    },
});

$(".form-control").on("focusout change keyup focus", function () {
    var text_val = $(this).val();
    if (text_val === "") {
        $(this).next().removeClass("floatingfocus");
    } else {
        $(this).next().addClass("floatingfocus");
    }
});

$(".form-control").each(function () {
    var text = $(this).val();
    if (text === "") {
        $(this).next().removeClass("floatingfocus");
    } else {
        $(this).next().addClass("floatingfocus");
    }
});

$('html').mouseover(function() {
    $(".form-control").each(function () {
        var text = $(this).val();
        if (text === "") {
            $(this).next().removeClass("floatingfocus");
        } else {
            $(this).next().addClass("floatingfocus");
        }
    });
}); 
});