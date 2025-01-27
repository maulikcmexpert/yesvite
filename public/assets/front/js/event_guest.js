$(document).on('click', '.edit-btn', function () {
    // Reset the radio buttons to ensure a clean state for each modal interaction
    $('.rsvp_status_yes').prop('checked', false); // Attending
    $('.rsvp_status_no').prop('checked', false); // Not attending
    $('#editrsvp').modal('hide'); // Hide the modal initially to set up data

    const guestId = $(this).data('guest-id');
    console.log('Guest ID:', guestId);
    $.ajax({
        url: base_url + "event_guest/fetch_guest/" + guestId, // Fetch guest data
        method: 'GET',
        success: function (response) {
            console.log("Response received: ", response); // Debugging line
            // Populate the modal with fetched data
            $('#editrsvp .rsvp-img img').attr('src', response.profile || asset('images/default-profile.png'));
            $('#editrsvp h5').text(`${response.firstname} ${response.lastname}`);
            $('#editrsvp .adult-count').val(response.adults || 0);
            $('#editrsvp .kid-count').val(response.kids || 0);

            // Update the radio buttons based on RSVP status
            if (response.rsvp_status == "1") {
                $('.rsvp_status_yes').prop('checked', true); // Attending
            }
            if (response.rsvp_status == "0") {
                $('.rsvp_status_no').prop('checked', true); // Not Attending
            }

            // Store guest ID in the save button data attribute
            $('#editrsvp .save-btn').data('guest-update-id', guestId);
            $('#editrsvp').modal('show'); // Show the modal
        },
        error: function (error) {
            console.error('Error fetching guest details:', error);
        }
    });
});

$('#editrsvp').on('hidden.bs.modal', function () {
    // Remove the guest ID data when the modal is closed
    $('#editrsvp .save-btn').removeData('guest-update-id');
});
// Reset adults and kids values when "Not Attending" is selected
$(document).on('change', '.rsvp_status_no', function () {
    // Reset adults and kids count to 0
    $('#editrsvp .adult-count').val(0);
    $('#editrsvp .kid-count').val(0);
});
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
                // Find the guest container by guestId
                const guestContainer = $('.guest-user-box[data-guest-id="' + guestId + '"]');

                // Find and remove any existing success message for the current guest
                guestContainer.find('.sucess-yes').remove();
                guestContainer.find('.sucess-no').remove();
                guestContainer.find('.no-reply').remove();

                // Now append or update the appropriate div based on RSVP status
                if (response.rsvp_status == '1') {
                    // If the guest's RSVP is "YES"
                    const successYesHtml = `
                        <div class="sucess-yes" data-guest-id="${response.guest_id}">
                            <h5 class="green">YES</h5>
                            <div class="sucesss-cat ms-auto">
                                <h5 id="adults${response.guest_id}">${response.adults} Adults</h5>
                                <h5 id="kids${response.guest_id}">${response.kids} Kids</h5>
                            </div>
                        </div>`;
                    guestContainer.find('.sucess-rsvp-wrp').append(successYesHtml); // Append to the right section
                } else if (response.rsvp_status == '0') {
                    // If the guest's RSVP is "NO"
                    const successNoHtml = `
                        <div class="sucess-no" data-guest-id="${response.guest_id}">
                            <h5>NO</h5>
                        </div>`;
                    guestContainer.find('.sucess-rsvp-wrp').append(successNoHtml); // Append to the right section
                } else if (response.rsvp_status == null) {
                    // If the guest has no reply
                    const noReplyHtml = `
                        <div class="no-reply" data-guest-id="${response.guest_id}">
                            <h5>NO REPLY</h5>
                        </div>`;
                    guestContainer.find('.sucess-rsvp-wrp').append(noReplyHtml); // Append to the right section
                }

                // Hide the modal after updating
                $('#editrsvp').modal('hide');
            }
        },
        error: function (error) {
            console.error('Error updating guest details:', error);
        }
    });
});

