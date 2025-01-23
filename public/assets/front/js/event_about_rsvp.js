$(document).ready(function() {
    // Initially disable the submit button
    $('button[type="submit"]').prop('disabled', true);

    // Listen for changes in RSVP status (YES/NO)
    $('input[name="rsvp_status"]').change(function() {
        var rsvpStatus = $(this).val()
        if (rsvpStatus == "0") {
            $('input[name="adults"]').val(0);
            $('input[name="kids"]').val(0);
        }
        validateForm();
    });

    // Listen for changes in the number of adults and kids
    $('input[name="adults"], input[name="kids"]').on('input', function() {
        validateForm();
    });

    // Listen for the click event on the "+" button for adults
    $('.btn-plus').click(function() {
        // Increase the number of adults by 1
        var adults = parseInt($('input[name="adults"]').val()) || 0;  // Default to 0 if not valid

        $('input[name="adults"]').val(adults);

        // Trigger the validation
        validateForm();
    });

    // Listen for the click event on the "-" button for adults
    $('.btn-minus').click(function() {
        // Decrease the number of adults by 1, ensuring it's at least 0
        var adults = parseInt($('input[name="adults"]').val()) || 0;

        $('input[name="adults"]').val(adults);

        // Trigger the validation
        validateForm();
    });

    // Submit form validation
    $('form').submit(function(e) {
        // Clear any previous error message
        $('#error-message').text('');

        // Get the value of RSVP status
        var rsvpStatus = $('input[name="rsvp_status"]:checked').val();

        // If RSVP status is "YES"
        if (rsvpStatus == 1) {
            // Get the current values of adults and kids
            var adults = parseInt($('input[name="adults"]').val()) || 0;  // Default to 0 if not valid
            var kids = parseInt($('input[name="kids"]').val()) || 0;  // Default to 0 if not valid

            // Check if neither adults nor kids is selected
            if (adults <= 0 && kids <= 0) {
                // Show error message inside the div
                $('#error-message').text('Please select at least one Adult or Kid.')
                                     .css('color', 'red');

                // Prevent form submission
                e.preventDefault();
            }
        }
        else {
            $('button[type="submit"]').prop('disabled', false);  // Disable submit button
        }
    });

    // Function to validate form and enable/disable submit button
    function validateForm() {
        // Get the value of RSVP status
        var rsvpStatus = $('input[name="rsvp_status"]:checked').val();
        var adults = parseInt($('input[name="adults"]').val()) || 0;
        var kids = parseInt($('input[name="kids"]').val()) || 0;

        // If RSVP is selected (YES) and either adults or kids is selected, enable submit button
        if (rsvpStatus == "1" && (adults > 0 || kids > 0)) {
            $('button[type="submit"]').prop('disabled', false);  // Enable submit button
            $('#error-message').text('');  // Clear error message
        } if(rsvpStatus == "0" && (adults ==  0 || kids == 0)) {
            $('button[type="submit"]').prop('disabled', false);  // Disable submit button
        }
    }
});
