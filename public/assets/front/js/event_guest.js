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
// $(document).on('click', '.save-btn', function () {
//     const guestId = $(this).data('guest-update-id'); // Retrieve the guest ID
//     console.log('Updating Guest ID:', guestId);

//     if (!guestId) {
//         alert("No guest selected for update.");
//         return;
//     }

//     // Gather updated data
//     const updatedData = {
//         guestId: guestId,
//         adults: $('#editrsvp input[name="adults"]').val(),
//         kids: $('#editrsvp input[name="kids"]').val(),
//         rsvp_status: $('#editrsvp input[name="rsvp_status"]:checked').val() // Get the selected RSVP status
//     };

//     $.ajax({
//         url: base_url + "event_guest/update_guest/" + guestId, // Endpoint to update guest details
//         method: 'POST',
//         headers: {
//             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//         },
//         data: updatedData,
//         success: function (response) {
//             console.log("Update successful: ", response);

//             if (response.success) {
//                 // Update the success message or guest details dynamically
//                 const successYesDiv = $('.sucess-yes');
//                 if (successYesDiv.length > 0) {
//                     successYesDiv.find('#adults' + response.guest_id).text(`${response.adults} Adults`);
//                     successYesDiv.find('#kids' + response.guest_id).text(`${response.kids} Kids`);
//                 }

//                 // Hide the modal
//                 $('#editrsvp').modal('hide');
//             }
//         },
//         error: function (error) {
//             console.error('Error updating guest details:', error);
//         }
//     });



// });

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
                // Clear previous status displays
                const guestBox = $('.guest-user-box').data('guest-id')

console.log(guestBox);

                // Determine the new RSVP status and update the display
                if (response.rsvp_status == '1') {
                    // Display success yes
                    const successYesHtml = `
                        <div class="sucess-yes">
                            <h5 class="green">YES</h5>
                            <div class="sucesss-cat ms-auto">
                             <svg width="15" height="15"
                                                                        viewBox="0 0 15 15" fill="none"
                                                                        xmlns="http://www.w3.org/2000/svg">
                                                                        <path
                                                                            d="M5.625 1.25C3.9875 1.25 2.65625 2.58125 2.65625 4.21875C2.65625 5.825 3.9125 7.125 5.55 7.18125C5.6 7.175 5.65 7.175 5.6875 7.18125C5.7 7.18125 5.70625 7.18125 5.71875 7.18125C5.725 7.18125 5.725 7.18125 5.73125 7.18125C7.33125 7.125 8.5875 5.825 8.59375 4.21875C8.59375 2.58125 7.2625 1.25 5.625 1.25Z"
                                                                            fill="black" fill-opacity="0.2" />
                                                                        <path
                                                                            d="M8.8001 8.84453C7.05635 7.68203 4.2126 7.68203 2.45635 8.84453C1.6626 9.37578 1.2251 10.0945 1.2251 10.8633C1.2251 11.632 1.6626 12.3445 2.4501 12.8695C3.3251 13.457 4.4751 13.7508 5.6251 13.7508C6.7751 13.7508 7.9251 13.457 8.8001 12.8695C9.5876 12.3383 10.0251 11.6258 10.0251 10.8508C10.0188 10.082 9.5876 9.36953 8.8001 8.84453Z"
                                                                            fill="black" fill-opacity="0.2" />
                                                                        <path
                                                                            d="M12.4938 4.58732C12.5938 5.79982 11.7313 6.86232 10.5376 7.00607C10.5313 7.00607 10.5313 7.00607 10.5251 7.00607H10.5063C10.4688 7.00607 10.4313 7.00607 10.4001 7.01857C9.79385 7.04982 9.2376 6.85607 8.81885 6.49982C9.4626 5.92482 9.83135 5.06232 9.75635 4.12482C9.7126 3.61857 9.5376 3.15607 9.2751 2.76232C9.5126 2.64357 9.7876 2.56857 10.0688 2.54357C11.2938 2.43732 12.3876 3.34982 12.4938 4.58732Z"
                                                                            fill="black" fill-opacity="0.2" />
                                                                        <path
                                                                            d="M13.7437 10.369C13.6937 10.9752 13.3062 11.5002 12.6562 11.8565C12.0312 12.2002 11.2437 12.3627 10.4624 12.344C10.9124 11.9377 11.1749 11.4315 11.2249 10.894C11.2874 10.119 10.9187 9.37525 10.1812 8.7815C9.7624 8.45025 9.2749 8.18775 8.74365 7.994C10.1249 7.594 11.8624 7.86275 12.9312 8.72525C13.5062 9.18775 13.7999 9.769 13.7437 10.369Z"
                                                                            fill="black" fill-opacity="0.2" />
                                                                    </svg>
                                <h5 id="adults${response.guest_id}">${response.adults} Adults</h5>
                                <h5 id="kids${response.guest_id}">${response.kids} Kids</h5>
                            </div>
                        </div>`;
                        guestBox.find('.sucess-yes').append(successYesHtml);// Append to the appropriate container
                } else if (response.rsvp_status == '0') {
                    // Display success no
                    const successNoHtml = `
                        <div class="sucess-no">
                            <h5>NO</h5>
                        </div>`;
                    guestBox.find('.sucess-no').append(successNoHtml); // Append to the appropriate container
                } else if (response.rsvp_status == null) {
                    // Display no reply
                    const noReplyHtml = `
                        <div class="no-reply">
                            <h5>NO REPLY</h5>
                        </div>`;
                        guestBox.find('.no-reply').append(noReplyHtml) // Append to the appropriate container
                }

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
