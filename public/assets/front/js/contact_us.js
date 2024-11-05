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
});