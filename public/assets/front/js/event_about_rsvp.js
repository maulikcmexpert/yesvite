$(document).ready(function () {

    let adults = 0;
    let kids = 0;
    // // Initially disable the submit button
    $('button[type="submit"]').prop('disabled', true);

    // Listen for changes in RSVP status (YES/NO)
    $('input[name="rsvp_status"]').change(function () {
        var rsvpStatus = $(this).val();
        console.log(rsvpStatus);
        if (rsvpStatus == "0") {
            $('input[name="adults"]').val(0);
            $('input[name="kids"]').val(0);
            $('.btn-plus, .btn-minus').prop('disabled', true); // Disable buttons
            $('button[type="submit"]').prop('disabled', false); // Allow submission if RSVP is No
        } if (rsvpStatus == "1") {

            adults = $('input[name="adults"]').val();
            kids = $('input[name="adults"]').val();

            $('.btn-plus, .btn-minus').prop('disabled', false); // Enable buttons
            $('button[type="submit"]').prop('disabled', true); // Disable submit initially
        }
        validateForm();
    });

    // Listen for changes in the number of adults and kids
    $('input[name="adults"], input[name="kids"]').on('input', function () {
        validateForm();
    });
    $(document).on("click","#copy_link_btn",function(e){
        e.preventDefault(); // Prevents any default button action
        var copyText = $("#copy_link").val();
        navigator.clipboard.writeText(copyText).then(function() {
        }).catch(function(err) {
            console.error("Failed to copy: ", err);
        });
    });
    // Listen for the click event on the "+" button for adults
    $('.btn-plus').click(function () {
        var rsvpStatus = $('input[name="rsvp_status"]:checked').val();

        // Find the closest input field and increment its value
        let input = $(this).closest('.qty-container').find('input.input-qty');
        let currentValue = parseInt(input.val()) || 0; // Default to 0 if invalid
        if (rsvpStatus == '1') {
            adults = currentValue + 1;
            kids = currentValue + 1;
            if ((currentValue + 1) > 0) {
                $('button[type="submit"]').prop('disabled', false);
            } else {
                $('button[type="submit"]').prop('disabled', true);
            }
        }

        input.val(currentValue + 1); // Increment by 1

        // Trigger validation if needed
        validateForm();
    });

    // Listen for the click event on the "-" button
    $('.btn-minus').click(function () {
        var rsvpStatus = $('input[name="rsvp_status"]:checked').val();

        let input = $(this).closest('.qty-container').find('input.input-qty');
        let currentValue = parseInt(input.val()) || 0; // Default to 0 if invalid
        if (currentValue > 0) {
            if (rsvpStatus == '1') {
                adults = currentValue - 1;
                kids = currentValue - 1;
                if ((currentValue - 1) > 0) {
                    $('button[type="submit"]').prop('disabled', false);
                } else {
                    $('button[type="submit"]').prop('disabled', true);
                }
            }
            input.val(currentValue - 1); // Decrement by 1 (minimum value is 0)
        }

        // Trigger validation if needed
        validateForm();
    });

    // Submit form validation
    // $('form').submit(function (e) {
    //     $('#error-message').text('');

    //     var rsvpStatus = $('input[name="rsvp_status"]:checked').val();
    //     // var adults = parseInt($('input[name="adults"]').val()) || 0;
    //     // var kids = parseInt($('input[name="kids"]').val()) || 0;

    //     if (rsvpStatus == "1" && adults <= 0 && kids <= 0) {

    //         $('#error-message').text('Please select at least one Adult or Kid.').css('color', 'red');
    //         e.preventDefault();
    //     }
    // });

    // Function to validate form and enable/disable submit button
    function validateForm() {
        var rsvpStatus = $('input[name="rsvp_status"]:checked').val();
        var adults = parseInt($('input[name="adults"]').val()) || 0;
        var kids = parseInt($('input[name="kids"]').val()) || 0;
        console.log(adults, kids);
        if (rsvpStatus == "0") {
            $('button[type="submit"]').prop('disabled', false);
        } else if (rsvpStatus == "1" && (adults > 0 || kids > 0)) {
            $('button[type="submit"]').prop('disabled', false);
        }
    }

    // Function to validate form and enable/disable submit button

    document
        .getElementById("openGoogle")
        .addEventListener("click", function () {
            // return;
            const eventDate = $("#eventDate").val();
            const eventEndDate = $("#eventEndDate").val();
            const eventTime = $("#eventTime").val();
            const eventEndTime = $("#eventEndTime").val() || $("#eventTime").val(); // Default value
            const eventName = $("#eventName").val();

            if (!eventDate || !eventTime) {
                toastr.error("Please provide both date and time for the event.");
                return;
            }

            const convertTo24HourFormat = (time) => {
                const [hour, minuteWithPeriod] = time.split(":");
                const [minute, period] = minuteWithPeriod.split(" ");
                let newHour = parseInt(hour);
                if (period.toLowerCase() === "pm" && newHour !== 12) {
                    newHour += 12; // Convert PM time to 24-hour format
                }
                if (period.toLowerCase() === "am" && newHour === 12) {
                    newHour = 0; // Handle 12 AM as midnight
                }
                return `${newHour}:${minute}`;
            };

            const formattedTime = convertTo24HourFormat(eventTime);
            const formattedEndTime = convertTo24HourFormat(eventEndTime);
            const startDateTime = new Date(`${eventDate}T${formattedTime}:00`); // ISO format with correct time

            if (isNaN(startDateTime)) {
                alert(
                    "Invalid start date or time value. Please check the input."
                );
                return;
            }

            let endDateTime;
            if (eventEndDate) {
                const endDateString = `${eventEndDate}T${formattedEndTime}:00`;
                const formattedEndDate = new Date(endDateString);

                if (isNaN(formattedEndDate)) {
                    alert(
                        "Invalid end date or time value. Please check the input."
                    );
                    return;
                }

                endDateTime = formattedEndDate;
            } else {
                endDateTime = new Date(startDateTime);
                endDateTime.setHours(endDateTime.getHours() + 1); // Default to 1 hour duration if no end date is provided
            }

            // Convert to Google Calendar format (without dashes, colons, and milliseconds)
            const formatToGoogleCalendar = (date) => {
                return (
                    date.toISOString().replace(/[-:.]/g, "").slice(0, -4) + "Z"
                );
            };

            const eventDetails = {
                title: eventName || "Meeting with Team",
                start: formatToGoogleCalendar(startDateTime),
                end: formatToGoogleCalendar(endDateTime),
            };

            console.log(eventDetails);

            // Platform-specific calendar opening code (Android / iOS)
            const isAndroid = /Android/i.test(navigator.userAgent);
            const isIOS = /iPhone|iPad|iPod/i.test(navigator.userAgent);

            // Default to Google Calendar URL
            const googleCalendarUrl = `https://calendar.google.com/calendar/render?action=TEMPLATE&text=${encodeURIComponent(
                eventDetails.title
            )}&dates=${eventDetails.start}/${eventDetails.end
                }&sf=true&output=xml`;

            window.open(googleCalendarUrl);
        });

    $(".noattending-btn").on('click', function () {
        var rsvpStatus = $('#statusRsvp').val();

        if (rsvpStatus == 0) {
            $("#option6").prop('checked', true);
            $("#option5").prop('checked', false);
            $('.btn-plus, .btn-minus').prop('disabled',true);
            $("#rsvp_status_adults").val(0);
            $("#rsvp_status_kids").val(0);
        }
    })
    $(".attending-btn").on('click', function () {

        var rsvpStatus = $('#statusRsvp').val();

        if (rsvpStatus == 1) {

            $('button[type="submit"]').prop('disabled',true);
            $("#option6").prop('checked', false);
            $("#option5").prop('checked', true);
            $('.btn-plus, .btn-minus').prop('disabled', false);
            $('button[type="submit"]').prop('disabled', true);
        }
    })
});

// $(".modal").on("hidden.bs.modal", function () {

//     $("#option6").prop('checked',false);
//     $("#option5").prop('checked',false);
//     $("#message_to_host").val(); // Clear image preview
//     $("#rsvp_status_adults").val(0);
//     $("#rsvp_status_kids").val(0);



// });
// $(document).on('click','.btn-close').click(function () {

//     $("#option6").prop('checked',false);
//     $("#option5").prop('checked',false);
//     $("#message_to_host").val(); // Clear image preview
//     $("#rsvp_status_adults").val(0);
//     $("#rsvp_status_kids").val(0);



// });
$(".modal").on("hidden.bs.modal", function () {
    var rsvpStatus = $('#statusRsvp').val();


if (rsvpStatus == 1 ) {
    clearModalValues();

}
if (rsvpStatus == "") {
    clearModalValues();
    clearModalALLValues();


}
 if (rsvpStatus == 0) {
    clearModalALLValues();
}
});

$(document).on("click", ".btn-close", function () {
    var rsvpStatus = $('#statusRsvp').val();

    if (rsvpStatus == 1 ) {
        clearModalValues();

    }
    if (rsvpStatus == "") {
        clearModalValues();
        clearModalALLValues();


    }
     if (rsvpStatus == 0) {
        clearModalALLValues();
    }
});

// Function to clear modal values
function clearModalValues() {
    $("#option6").prop("checked", false);
    $("#option5").prop("checked", false);
    $('button[type="submit"]').prop('disabled', true);
    $("#message_to_host").val(""); // Clear input field

}
function clearModalALLValues() {

    $("#option6").prop('checked', false);
    $("#option5").prop('checked', false);
    $("#message_to_host").val(); // Clear image preview
    $("#rsvp_status_adults").val(0);
    $("#rsvp_status_kids").val(0);

}
