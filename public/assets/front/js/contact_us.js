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