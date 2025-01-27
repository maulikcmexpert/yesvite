<script type="text/javascript">
    $(function() {

function allowOnlyNumbers(event) {
    let inputVal = event.target.value;

    // Remove any non-numeric characters
    inputVal = inputVal.replace(/[^0-9]/g, '');

    // Update the input field with the cleaned value
    event.target.value = inputVal;
}

// Attach the event handler to the 'keyup' event of the input with id 'creditcoins'
$(document).on("keyup", "#creditcoins", allowOnlyNumbers);

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