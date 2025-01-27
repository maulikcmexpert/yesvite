<script type="text/javascript">
    $(function() {

$("#credit_coin").on('input', function(event) {
    const inputVal = $(this).val();
    if (/\d/.test(inputVal)) {
        $(this).val(inputVal.replace(/\d/g, '')); // Remove numbers
    }
});
$("#credit_coin").on('keypress', function(event) {
    const keyCode = event.which || event.keyCode;

    if ((keyCode < 48 || keyCode > 57) && keyCode !== 8 && keyCode !== 46) {
        event.preventDefault();  // Prevent non-numeric characters
    }
});
$("#addCoin_form").validate({
    rules: {
        credit_coin: {
            required: true,
            pattern: /^[0-9]+$/,  // Ensures only digits (no decimals)
        },

    },
    messages: {
        credit_coin: {
            required: "Please enter credit coins",
            pattern: "Please enter valid credit coins",  
        },

    },

})

$(document).on('click','#addCoin',function(e){
    e.preventDefault(); // Prevent default action, if necessary.
    $(this).prop('disabled', true);
    $('#addCoin_form').submit();
})

    });
</script>