<script type="text/javascript">
    $(function() {

        function allowOnlyNumbers(event) {
    let inputVal = event.target.value;

    inputVal = inputVal.replace(/[^0-9]/g, '');

    event.target.value = inputVal;
}
$(document).on("keyup","#creditcoins", allowOnlyNumbersOnKeyUp);

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