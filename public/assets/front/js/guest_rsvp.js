$(document).on('click', '.edit_rsvp_guest', function () {
    // Reset the radio buttons to ensure a clean state for each modal interaction
    $('.rsvp_yes').prop('checked', false); // Attending
    $('.rsvp_no').prop('checked', false); // Not attending
    $('#editrsvp3').modal('hide'); // Hide the modal initially to set up data

    const guestId = $(this).data('guest-id');
    const is_sync = $(this).data('is_sync');

    console.log('Guest ID:', guestId);
    $('#editrsvp3 .rsvp-img img').attr('src',"");
    $('#editrsvp3 h5').text("");
    $('#editrsvp3 .adult-count').val("");
    $('#editrsvp3 .kid-count').val("");
    $('#editrsvp3 .rsvp-img h5').remove();
    $.ajax({
        url: base_url + "event_guest/fetch_guest/" + guestId+"/"+is_sync, // Fetch guest data
        method: 'GET',
        success: function (response) {
            console.log("Response received: ", response); // Debugging line
            // Populate the modal with fetched data
            if(response.profile!=""){
                $('#editrsvp3 .rsvp-img img').css('display','block');
                $('#editrsvp3 .rsvp-img img').attr('src', response.profile);
                $('#editrsvp3 .rsvp-img h5').css('display','none');
            }else{
                $('#editrsvp3 .rsvp-img img').css('display','none');
                var profile=generateProfileImage(response.firstname,response.lastname);
                $('#editrsvp3 .rsvp-img').append(profile);
            }
            $('#editrsvp3 .adultcount').val(response.adults || 0);
            $('#editrsvp3 .kidcount').val(response.kids || 0);

            // Update the radio buttons based on RSVP status
            if (response.rsvp_status == "1") {
                $('.rsvp_yes').prop('checked', true); 
                $('.side_menu_minus').prop('disabled',false)
                $('.side_menu_plus').prop('disabled',false)
                // Attending
            }
            if (response.rsvp_status == "0") {
                $('.rsvp_no').prop('checked', true); 
                $('.side_menu_minus').prop('disabled',true)
                $('.side_menu_plus').prop('disabled',true)
            }

            // Store guest ID in the save button data attribute
            $('#editrsvp3 .save-rsvp').data('guest-update-id', guestId);
            $('#editrsvp3 .save-rsvp').data('guest-is_sync', is_sync);

            $('#editrsvp3 .remove-Rsvp-btn').data('guest-update-id', guestId);
            $('#editrsvp3 .remove-Rsvp-btn').data('guest-is_sync', is_sync);
            $('#editrsvp3 .remove-Rsvp-btn').data('user-id', response.user_id);
            $('#editrsvp3 .remove-Rsvp-btn').data('event-id', response.event_id);

            $('#editrsvp3').modal('show'); // Show the modal
        },
        error: function (error) {
            console.error('Error fetching guest details:', error);
        }
    });
});
function generateProfileImage(firstname, lastname) {
    firstname = firstname ? String(firstname).trim() : "";
    lastname = lastname ? String(lastname).trim() : "";
    const firstInitial = firstname[0] ? firstname[0].toUpperCase() : "";
    const secondInitial = lastname[0] ? lastname[0].toUpperCase() : "";
    const initials = `${firstInitial}${secondInitial}`;
    const fontColor = `fontcolor${firstInitial}`;
    return `<h5 class="${fontColor} font_name">${initials || "NA"}</h5>`;
}
$('#editrsvp3').on('hidden.bs.modal', function () {
    // Remove the guest ID data when the modal is closed
    $('#editrsvp3 .save-rsvp').removeData('guest-update-id');
});
// Reset adults and kids values when "Not Attending" is selected
$(document).on('change', '.rsvp_no', function () {
    // Reset adults and kids count to 0
    $('#editrsvp3 .adultcount').val(0);
    $('#editrsvp3 .kidcount').val(0);
  //  $('#editrsvp3 .side_menu_minus, #editrsvp3 . side_menu_plus').addClass('disabled-btn').prop('disabled', true);
    $('.side_menu_minus, .side_menu_plus').prop('disabled', true); // Enable butto
});
$(document).on('change', '.rsvp_yes', function () {


    // Enable plus and minus buttons
    $('.side_menu_minus, .side_menu_plus').prop('disabled', false);
});
$(document).on('click', '.save-rsvp', function () {
    const guestId = $(this).data('guest-update-id'); // Retrieve the guest ID
    console.log('Updating Guest ID:', guestId);

    if (!guestId) {
        alert("No guest selected for update.");
        return;
    }

    // Gather updated data
    const updatedData = {
        guestId: guestId,
        adults: $('#editrsvp3 input[name="adults"]').val(),
        kids: $('#editrsvp3 input[name="kids"]').val(),
        rsvp_status: $('#editrsvp3 input[name="rsvp_status"]:checked').val() // Get the selected RSVP status
    };
    var rsvp_status= $('#editrsvp3 input[name="rsvp_status"]:checked').val(); // Get the selected RSVP status
    var adults= $('#editrsvp3 input[name="adults"]').val();
    var kids=$('#editrsvp3 input[name="kids"]').val();

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

                const guestContainer = $('.guest_rsvp_icon[data-guest-id="' + guestId + '"]');

                // // Remove existing status icon
                guestContainer.find('span').remove();

                $('<div id="pageOverlay"></div>').css({
                    position: 'fixed',
                    top: 0,
                    left: 0,
                    width: '100%',
                    height: '100%',
                    background: 'rgba(255, 255, 255, 0)', // Transparent background
                    zIndex: 9999
                }).appendTo('body');

                
                $('#editrsvp3').modal('hide');
                toastr.success('RSVP updated successfully');
                setTimeout(() => {
                    window.location.reload();
                }, 50); 

                // Append new RSVP status based on the response
                if (response.rsvp_status == '1') {
                    // If the RSVP is "YES"
                    const successYesHtml = `
                        <span id="approve" data-guest-id="${response.guest_id}">
                            <svg viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M10.0013 18.4583C5.33578 18.4583 1.54297 14.6655 1.54297 9.99996C1.54297 5.33444 5.33578 1.54163 10.0013 1.54163C14.6668 1.54163 18.4596 5.33444 18.4596 9.99996C18.4596 14.6655 14.6668 18.4583 10.0013 18.4583ZM10.0013 1.79163C5.47516 1.79163 1.79297 5.47382 1.79297 9.99996C1.79297 14.5261 5.47516 18.2083 10.0013 18.2083C14.5274 18.2083 18.2096 14.5261 18.2096 9.99996C18.2096 5.47382 14.5274 1.79163 10.0013 1.79163Z" fill="#23AA26" stroke="#23AA26" />
                                <path d="M8.46363 11.8285L8.81719 12.1821L9.17074 11.8285L13.4541 7.54518C13.4756 7.52365 13.5063 7.51038 13.5422 7.51038C13.578 7.51038 13.6088 7.52365 13.6303 7.54518C13.6518 7.56671 13.6651 7.59744 13.6651 7.63329C13.6651 7.66914 13.6518 7.69988 13.6303 7.72141L8.9053 12.4464C8.88133 12.4704 8.84974 12.4833 8.81719 12.4833C8.78464 12.4833 8.75304 12.4704 8.72907 12.4464L6.37074 10.0881C6.34921 10.0665 6.33594 10.0358 6.33594 9.99996C6.33594 9.96411 6.34921 9.93337 6.37074 9.91185C6.39227 9.89032 6.423 9.87704 6.45885 9.87704C6.49471 9.87704 6.52544 9.89032 6.54697 9.91185L8.46363 11.8285Z" fill="#23AA26" stroke="#23AA26" />
                            </svg>
                        </span>`;
                    guestContainer.append(successYesHtml);
                } else if (response.rsvp_status == '0') {
                    // If the RSVP is "NO"
                    const successNoHtml = `
                        <span id="cancel" data-guest-id="${response.guest_id}">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect width="20" height="20" rx="10" fill="#E03137" />
                                <path d="M5.91797 5.91663L14.0841 14.0827" stroke="white" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M5.91787 14.0827L14.084 5.91663" stroke="white" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </span>`;
                    guestContainer.append(successNoHtml);
                } else {
                    // If the RSVP is "Pending"
                    const pendingHtml = `
                        <span id="pending" data-guest-id="${response.guest_id}">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect width="20" height="20" rx="10" fill="#94A3B8" />
                                <path d="M15.8327 10C15.8327 13.22 13.2193 15.8334 9.99935 15.8334C6.77935 15.8334 4.16602 13.22 4.16602 10C4.16602 6.78002 6.77935 4.16669 9.99935 4.16669C13.2193 4.16669 15.8327 6.78002 15.8327 10Z" stroke="white" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M12.1632 11.855L10.3549 10.7759C10.0399 10.5892 9.7832 10.14 9.7832 9.77253V7.38086" stroke="white" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </span>`;
                    guestContainer.append(pendingHtml);
                }

                // Hide the modal after updating

            }
        },
        error: function (error) {
            console.error('Error updating guest details:', error);
        }
    });
});
$(document).on('click', '.remove-Rsvp-btn', function () {
    const eventId = $(this).data('event-id');
    const userId = $(this).data('user-id');
    const guestId = $(this).data('guest-update-id');
    // Make the AJAX request to remove the guest from the invite
    $.ajax({
        url: base_url + "event_guest/removeGuestFromInvite", // Endpoint to remove guest
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Include CSRF token
        },
        data: { event_id: eventId ,user_id:userId}, // Pass guestId to the server
        success: function (response) {
            console.log("Remove successful: ", response);

            if (response.success) {
                // // Find the guest container by guestId and remove it from the DOM
                $('.guests-listing-info[data-guest-id="' + guestId + '"]').remove();


                // Hide the modal if it's open
                $('#editrsvp3').modal('hide');
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

$(".modal").on("hidden.bs.modal", function(){
    $("#message_to_host").val('');
});
