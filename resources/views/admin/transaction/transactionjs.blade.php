<script type="text/javascript">
    $(function() {

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
            pattern: "Please enter a credit coins",  
        },

    },

})

    });
</script>