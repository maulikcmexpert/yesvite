
$(document).ready(function () {
    // Function to update character count


    // Function to validate form fields
    function validateForm() {
        let isValid = true;

        $('.create_post').prop('disabled', !isValid);
    }





    // Submit form on button click
    $(document).on('click', '.create_post', function () {
        // Check if the poll form exists and is valid

        var photoForm = $('#photoForm');
        var textForm = $('#textform');
        var postContent = document.getElementById('postContent').value.trim();
        // Fallback to empty string if #postContent does not exist

        console.log('Photo Form:', photoForm.length > 0 ? 'Exists' : 'Does not exist');
        console.log('Text Form:', textForm.length > 0 ? 'Exists' : 'Does not exist');
        console.log('Post Content:', postContent);

        // If a photo form exists and is visible, submit it
        if (photoForm.is(':visible') && photoForm.length > 0) {
            // if (postContent === '') {
            //     alert('Please enter some content for the photo post.');
            //     return;
            // }
            // Set the value of the hidden input in the photo form
            document.getElementById('photoContent').value = postContent;
            photoForm.submit();
        }
        // If neither form exists, check for a plain text post
        else if (textForm.length > 0 && postContent !== '') {
            textForm.submit();
        }
        // If no valid content is provided, show an alert
        else {
            alert('Please fill all required fields before submitting.');
        }
    });




    $(document).on('click', '.open_photo_model', function () {
        // Fetch the post ID from the data attribute
        const postId = $(this).data('post-id');
        const eventId = $(this).data('event-id');

        // Make an AJAX request
        $.ajax({
            url: base_url + "event_photo/fetch-photo-details", // Update with your server-side endpoint
            type: 'POST', // Use GET or POST depending on your API
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: { id: postId, event_id: eventId },
            success: function (response) {
                const dataArray = response.data; // This is an array
                console.log(dataArray);

                if (Array.isArray(dataArray) && dataArray.length > 0) {
                    const data = dataArray[0]; // Access the first object in the array

                    // Profile Image
                    const profileImage = data.profile || '{{ asset("assets/front/img/header-profile-img.png") }}';
                    $('.posts-card-head-left-img img').attr('src', profileImage);

                    // Name
                    const fullName = `${data.firstname} ${data.lastname}`;
                    $('#post_name').text(fullName);

                    // Location
                    const location = data.location.trim() !== '' ? data.location : '';
                    $('#location').text(location);

                    // Post Message
                    $('#post_message').text(data.post_message);

                    const swiperWrapper = $('#media_post');
                    swiperWrapper.empty();
                    if (data.mediaData && data.mediaData.length > 0) {
                        data.mediaData.forEach((media) => {
                            swiperWrapper.append(`
                                <div class="swiper-slide">
                                    <div class="posts-card-show-post-img">
                                        <img src="${media.post_media}" alt="" />
                                    </div>
                                </div>
                            `);
                        });
                    }
                    $('#likes').text(data.total_likes + ' Likes');  // Add 'Likes' after the number
                    $('#comments').text(data.total_comments + ' Comments')
                } else {
                    console.log('No data found in the array.');
                }
            }
        });
    });


    let pressTimer;
    const longPressDelay = 5000; // 1 second long press duration

    $('.open_photo_model').on('mousedown touchstart', function () {
        // alert();
        // $('#detail-photo-modal').modal('hide');
        pressTimer = setTimeout(() => {
            // Show the button and check the checkbox when long pressed
            $(this).closest('.photo-card-photos-wrp').find('.selected-photo-btn').show();
            $(this).closest('.photo-card-photos-wrp').find('.form-check-input').prop('checked', true);
        }, longPressDelay);
    }).on('mouseup touchend', function () {
        clearTimeout(pressTimer);
    }).on('mouseleave', function () {
        clearTimeout(pressTimer);
    });


    $(".posts-card-like-btn").on("click", function () {
        const icon = this.querySelector('i');
        icon.classList.toggle('fa-regular');
        icon.classList.toggle('fa-solid');
    });

    $(".show-comments-btn").click(function () {
        $(".posts-card-show-all-comments-wrp").toggleClass("d-none");
    });
    $(".show-comment-reply-btn").click(function () {
        $(".reply-on-comment").toggleClass("d-none");
    });
    $(".likeButton").each(function () {
        const button = $(this);
        const eventPostId = button.data('event-post-id');
        const reaction = userReaction[eventPostId]; // Get the reaction for the current post

        // Set the initial state based on the reaction
        if (reaction === '❤') {
            button.addClass('liked');
            button.find('i').removeClass('fa-regular').addClass('fa-solid'); // Set heart icon to solid
        } else {
            button.removeClass('liked');
            button.find('i').removeClass('fa-solid').addClass('fa-regular'); // Set heart icon to regular
        }
    });



});
$(document).on('click', '#likeButton', function () {
    // Collect necessary data
    const button = $(this);
    const eventId = $(this).data('event-id'); // Make sure the button has data-event-id
    const eventPostId = $(this).data('event-post-id'); // Make sure the button has data-event-post-id
    const isLiked = $(this).hasClass('liked'); // Check if already liked
    const reaction = isLiked ? '\u{1F494}' : '\u{2764}';
     const userId = $(this).data('user-id');


    $.ajax({
        url: base_url + "event_photo/userPostLikeDislike", // Adjust base_url as necessary
        method: "POST",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Include CSRF token for security
        },
        contentType: "application/json", // Send as JSON
        data: JSON.stringify({
            event_id: eventId,
            event_post_id: eventPostId,
            reaction: reaction
        }),
        success: function (response) {
            if (response.status === 1) {
                // Toggle the like button state
                console.log(response.post_reaction);
                const userReaction = response.post_reaction.find(r => r.user_id === userId); // Find the reaction for the current user
                console.log(userReaction ? userReaction.reaction : 'No reaction'); // Log the user's reaction

                if (userReaction) {
                    // Check the reaction and toggle the button state accordingly
                    if (userReaction.reaction === '\\u{2764}') { // If reaction is heart
                        console.log('Heart reaction');
                        button.addClass('liked');
                        button.find('i').removeClass('fa-regular').addClass('fa-solid'); // Change icon to solid heart
                    } else if (userReaction.reaction === '\\u{1F44F}') { // If reaction is clapping
                        console.log('Clapping reaction');
                        button.removeClass('liked');
                        button.find('i').removeClass('fa-solid').addClass('fa-regular'); // Change icon to regular heart
                    }
                }

                const likeCountId = $('#likeCount_' + eventPostId);

                // Update the count dynamically for this specific button
                $(`#${likeCountId}`).text(`${response.count}`);
            } else {
                alert(response.message); // Show any error message
            }
        },
        error: function (xhr) {
            console.error(xhr.responseText);
            alert('An error occurred. Please try again.');
        }
    });
});
$(document).on('click', '#delete_post', function () {
    const button = $(this);
    const eventId = button.data('event-id');
    const eventPostId = button.data('event-post-id');

    $.ajax({
        url: base_url + "event_photo/deletePost", // Adjust base_url as necessary
        method: "POST",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Include CSRF token for security
        },
        contentType: "application/json", // Send as JSON
        data: JSON.stringify({
            event_id: eventId,
            event_post_id: eventPostId,
        }),
        success: function (response) {
            if (response.success) {
                // Remove the deleted post from the DOM
                button.closest('.delete_post_container').remove(); // Adjust the selector as per your HTML structure
                // setTimeout(function () {
                //     location.reload();
                // }, 2000);
                toastr.success('Event Post Deleted Successfully');
            } else {
                toastr.error('Event Post  Not Deleted');
            }
        },
        error: function (xhr) {
            console.error(xhr.responseText);
            alert('An error occurred. Please try again.');
        }
    });
});

