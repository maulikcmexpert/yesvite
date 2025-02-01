$(document).on('click', '.edit_guest_rsvp', function () {
    // Reset the radio buttons to ensure a clean state for each modal interaction
    $('.rsvp_status_yes').prop('checked', false); // Attending
    $('.rsvp_status_no').prop('checked', false); // Not attending
    $('#editrsvp').modal('show'); // Hide the modal initially to set up data

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
                $('.edit-rsvp-qty').prop("disabled",false); // Not Attending

            }
            if (response.rsvp_status == "0") {
                $('.rsvp_status_no').prop('checked', true);
                $('.edit-rsvp-qty').prop("disabled",true); // Not Attending
            }

            // Store guest ID in the save button data attribute
            $('#editrsvp .guest-rsvp-edit-btn').data('guest-update-id', guestId);
            $('#editrsvp .remove_guest_page').data('guest-update-id', guestId);
            $('#editrsvp .remove_guest_page').data('user-id', response.user_id);
            $('#editrsvp .remove_guest_page').data('event-id', response.event_id);
            $('#editrsvp').modal('show'); // Show the modal
        },
        error: function (error) {
            console.error('Error fetching guest details:', error);
        }
    });
});

$('#editrsvp').on('hidden.bs.modal', function () {
    $('#editrsvp .guest-rsvp-edit-btn').removeData('guest-update-id');
});
// Reset adults and kids values when "Not Attending" is selected
$(document).on('change', '.rsvp_status_no', function () {
    $('.edit-rsvp-qty').prop("disabled",true);
    $('#editrsvp .adult-count').val(0);
    $('#editrsvp .kid-count').val(0);
});
$(document).on('change', '.rsvp_status_yes', function () {
    $('.edit-rsvp-qty').prop("disabled",false);
});

$(document).on('click', '.guest-rsvp-edit-btn', function () {
    const guestId = $(this).data('guest-update-id'); // Retrieve the guest ID
    console.log('Updating Guest ID:', guestId);

    if (!guestId) {
        alert("No guest selected for update.");
        return;
    }

    // Declare total variables
    let totalAttending = parseInt($('.totalAttending').text()) || 0;
    let totalAdults = parseInt($('.totalAdults').text()) || 0;
    let totalKids = parseInt($('.totalKids').text()) || 0;

    // Gather updated data
    const updatedData = {
        guestId: guestId,
        adults: $('#editrsvp input[name="adults"]').val(),
        kids: $('#editrsvp input[name="kids"]').val(),
        rsvp_status: $('#editrsvp input[name="rsvp_status"]:checked').val() // Get the selected RSVP status
    };

    var rsvp_status= $('#editrsvp input[name="rsvp_status"]:checked').val(); // Get the selected RSVP status
    var adults= $('#editrsvp input[name="adults"]').val();
    var kids=$('#editrsvp input[name="kids"]').val();

    console.log({rsvp_status,adults,kids});
    if(rsvp_status==undefined||rsvp_status==""){
        toastr.error('Please select RSVP');
        return;
    }
    if((rsvp_status=="1")&&(adults=='0'&&kids=='0')){
        toastr.error('Please select at least one adult or kid');
        return;
    }
   
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
                const adultsCount = parseInt(response.adults) || 0;
                const kidsCount = parseInt(response.kids) || 0;

                // Find and remove any existing success message for the current guest
                guestContainer.find('.sucess-yes').remove();
                guestContainer.find('.sucess-no').remove();
                guestContainer.find('.no-reply').remove();
                    $('<div id="pageOverlay"></div>').css({
                        position: 'fixed',
                        top: 0,
                        left: 0,
                        width: '100%',
                        height: '100%',
                        background: 'rgba(255, 255, 255, 0)', // Transparent background
                        zIndex: 9999
                    }).appendTo('body');

                    $('#editrsvp').modal('hide');

                    setTimeout(() => {
                        window.location.reload();
                    }, 50); 

                // Now append or update the appropriate div based on RSVP status
                if (response.rsvp_status == '1') {
                    // If the guest's RSVP is "YES"
                    const successYesHtml = `
                        <div class="sucess-yes" data-guest-id="${response.guest_id}">
                            <h5 class="green">YES</h5>
                            <div class="sucesss-cat ms-auto">
                                <h5 id="adults${response.guest_id}">${adultsCount} Adults</h5>
                                <h5 id="kids${response.guest_id}">${kidsCount} Kids</h5>
                            </div>
                        </div>`;
                    guestContainer.find('.check_status').append(successYesHtml); // Append to the right section

                    // Update total counts
                    totalAttending++;
                    totalAdults += adultsCount;
                    totalKids += kidsCount;
                } else if (response.rsvp_status == '0') {
                    // If the guest's RSVP is "NO"
                    const successNoHtml = `
                        <div class="sucess-no" data-guest-id="${response.guest_id}">
                            <h5>NO</h5>
                        </div>`;
                    guestContainer.find('.check_status').append(successNoHtml); // Append to the right section

                    // Decrease totals if a guest is marked as NO
                    totalAttending--;
                    totalAdults -= adultsCount;
                    totalKids -= kidsCount;
                } else if (response.rsvp_status == null) {
                    // If the guest has no reply
                    const noReplyHtml = `
                        <div class="no-reply" data-guest-id="${response.guest_id}">
                            <h5>NO REPLY</h5>
                        </div>`;
                    guestContainer.find('.check_status').append(noReplyHtml); // Append to the right section
                }

                // Update the total counts dynamically
                $('.totalAttending').text(totalAttending);
                $('.totalAdults').text(totalAdults);
                $('.totalKids').text(totalKids);

                // Hide the modal after updating
                $('#editrsvp').modal('hide');
            }
        },
        error: function (error) {
            console.error('Error updating guest details:', error);
        }
    });
});

$(document).on('click', '.remove_guest_page', function () {
    const guestId = $(this).data('guest-update-id');
    const eventId = $(this).data('event-id');
    const userId = $(this).data('user-id');

    // Make the AJAX request to remove the guest from the invite
    $.ajax({
        url: base_url + "event_guest/removeGuestFromInvite", // Endpoint to remove guest
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Include CSRF token
        },
        data: { event_id: eventId, user_id: userId }, // Pass guestId to the server
        success: function (response) {
            console.log("Remove successful: ", response);

            if (response.success) {
                // // Find the guest container by guestId and remove it from the DOM
                $('.guest-user-box[data-guest-id="' + guestId + '"]').remove();



                // Hide the modal if it's open
                $('#editrsvp').modal('hide');
            } else {
                alert('Failed to remove guest. Please try again.');
            }
        },
        error: function (error) {
            console.error('Error removing guest:', error);
            alert('An error occurred while removing the guest.');
        }
    });
});

$(document).ready(function () {
    $(".failed_contact_edit").on("click", function () {
        // Get data attributes
        let id = $(this).data("id");
        let first_name = $(this).data("first_name");
        let last_name = $(this).data("last_name");
        let email = $(this).data("email");
        let phone_number = $(this).data("phone_number");
        let prefer_by = $(this).data("prefer_by");

        // Populate modal fields
        $("#edit_id").val(id);
        $("#edit_first_name").val(first_name);
        $("#edit_last_name").val(last_name);
        $("#edit_email").val(email);
        $("#edit_phone_number").val(phone_number);
        $("#edit_prefer_by").val(prefer_by);
    });

    $("#editGuestForm").on("submit", function (e) {
        e.preventDefault();

        let formData = new FormData(this);

        $.ajax({
            url: "{{ route('update_guest') }}",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            success: function (response) {
                if (response.success) {
                    alert("Guest updated successfully!");
                    location.reload();
                } else {
                    alert("Error updating guest.");
                }
            },
            error: function (xhr, status, error) {
                console.error("Error:", error);
            }
        });
    });
});
$(document).on('click', '.delete_failed_contact', function () {
    let userId = $(this).data('user-id');
    let event_id = $('#event_id').val();

    $.ajax({
        url: base_url + "event_guest/removeGuestFromInvite",  // Ensure this route is defined in web.php/api.php
        type: "POST",
        data: JSON.stringify({ user_id: userId, event_id: event_id }),
        contentType: "application/json",
        headers: {
            'Authorization': 'Bearer YOUR_ACCESS_TOKEN', // If using Laravel Passport or Sanctum
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // If CSRF token is needed
        },
        success: function (response) {
            if (response.status === 1) {
                toastr.success(response.message);
                // // Find the guest container by guestId and remove it from the DOM
                $('.invite-contact-wrp[data-user-id="' + userId + '"]').remove();
            } else {
                toastr.error(response.message);
            }
        },
        error: function (xhr, status, error) {
            toastr.error("Something went wrong!");
            console.error(xhr.responseText);
        }
    });

});
