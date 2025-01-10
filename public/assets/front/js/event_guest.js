$(document).on('click', '.edit-btn', function () {

    $('.rsvp_status_yes').prop('checked', false); // Attending
    $('.rsvp_status_no').prop('checked', false); // Attending
    $('#editrsvp').modal('hide'); // This will open the modal

    const guestId = $(this).data('guest-id');
    console.log('Guest ID:', guestId);
    $.ajax({
        url: base_url + "event_guest/fetch_guest/" + guestId,// Endpoint to fetch guest details
        method: 'GET',
        success: function (response) {


            console.log("Response received: ", response); // Debugging line
            // Populate the modal with fetched data
            $('#editrsvp .rsvp-img img').attr('src', response.profile || asset('images/default-profile.png'));
            $('#editrsvp h5').text(`${response.firstname} ${response.lastname}`);
            // $('#editrsvp .adult-count').addClass('adult'+guestId);
            // $('#editrsvp  .kid-count').addClass('kids'+guestId);
            $('#editrsvp .adult-count').val(response.adults || 0);
            $('#editrsvp  .kid-count').val(response.kids || 0);
            // Debugging: check the radio buttons before making changes
            console.log("Before changes: Option1 checked: ", $('#option1').prop('checked'));
            console.log("Before changes: Option2 checked: ", $('#option2').prop('checked'));
            if (response.rsvp_status == "1") {
                $('.rsvp_status_yes').prop('checked', true); // Attending
            }
            if (response.rsvp_status == "0") {
                $('.rsvp_status_no').prop('checked', true); // Attending
            }


            $('#editrsvp .save-btn').data('guest-update-id', guestId);
            $('#editrsvp').modal('show');  // This will open the modal
        },
        error: function (error) {
            console.error('Error fetching guest details:', error);
        }
    });
});
$('#editrsvp').on('hidden.bs.modal', function () {
    $('#editrsvp .save-btn').removeData('guest-update-id'); // Remove the data-guest-update-id attribute
});
// $(document).on('click', '.save-btn', function () {
$(document).on('click', '.save-btn', function () {
    const guestId = $(this).data('guest-update-id'); // Retrieve the guest ID
    console.log('Updating Guest ID:', guestId);

    if (!guestId) {
        alert("No guest selected for update.");
        return;
    }

    // Gather updated data
    const updatedData = {
        guestId: guestId,
        adults: $('#editrsvp input[name="adults"]').val(),
        kids: $('#editrsvp input[name="kids"]').val(),
        rsvp_status: $('#editrsvp input[name="rsvp_status"]:checked').val() // Get the selected RSVP status
    };

    $.ajax({
        url: base_url + "event_guest/update_guest/" + guestId, // Endpoint to update guest details
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: updatedData,
        success: function (response) {
            console.log("Update successful: ", response);

            if (response.success) {
                // Update the success message or guest details dynamically
                const successYesDiv = $('.sucess-yes');
                if (successYesDiv.length > 0) {
                    successYesDiv.find('#adults' + response.guest_id).text(`${response.adults} Adults`);
                    successYesDiv.find('#kids' + response.guest_id).text(`${response.kids} Kids`);
                }

                // Hide the modal
                $('#editrsvp').modal('hide');
            }
        },
        error: function (error) {
            console.error('Error updating guest details:', error);
        }
    });



});

