<script type="text/javascript">
    $(function() {
        
$("#credit_coin").on('input', function(event) {
    const inputVal = $(this).val();
    // Check if the input contains any numbers
    if (/\d/.test(inputVal)) {
        $(this).val(inputVal.replace(/\d/g, '')); // Remove numbers
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