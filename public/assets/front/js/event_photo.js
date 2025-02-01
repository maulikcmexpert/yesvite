
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

let longPressTimer;
let isLongPresss = false;

$(document).on('mousedown', '#likeButton', function () {
    isLongPresss = false; // Reset the flag
    const button = $(this);

    // Start the long press timer
    longPressTimer = setTimeout(() => {
        isLongPresss = true; // Mark as long press
        const emojiDropdown = button.closest('.photo-card-head-right').find('#emojiDropdown');
        emojiDropdown.show(); // Show the emoji picker
        //button.find('i').text(''); // Clear the heart icon
    }, 500); // 500ms for long press
});

$(document).on('click', '#likeButton', function () {
    clearTimeout(longPressTimer); // Clear the long press timer

    // If it's a long press, don't process the click event
    if (isLongPresss) return;

    // Handle single tap like/unlike
    const button = $(this);
    const isLiked = button.hasClass('liked');
    const reaction = isLiked ? '\u{2764}' : '\u{1F90D}'; // Toggle reaction: 💔 or ❤️

    // Toggle like button appearance
    if (isLiked) {
        button.removeClass('liked');
        button.find('i').removeClass('fa-solid').addClass('fa-regular');
    } else {
        button.addClass('liked');
        button.find('i').removeClass('fa-regular').addClass('fa-solid');
    }

    // AJAX call to update the like state
    const eventId = button.data('event-id');
    const eventPostId = button.data('event-post-id');
    $.ajax({
        url: base_url + "event_photo/userPostLikeDislike",
        method: "POST",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        contentType: "application/json",
        data: JSON.stringify({ event_id: eventId, event_post_id: eventPostId, reaction: reaction }),
        success: function (response) {
            if (response.status === 1) {
                $(`#likeCount_${eventPostId}`).text(`${response.count} Likes`);
            } else {
                alert(response.message);
            }
        },
        error: function (xhr) {
            console.error(xhr.responseText);
            alert('An error occurred. Please try again.');
        }
    });
});

$(document).on('click', '#emojiDropdown .emoji', function () {
    const selectedEmoji = $(this).data('emoji');
    const button = $(this).closest('.photo-card-head-right').find('#likeButton');
    const emojiDisplay = button.find('#show_Emoji');

    // Replace heart icon with selected emoji
    emojiDisplay.removeClass();
    emojiDisplay.text(selectedEmoji);

    // AJAX call to update emoji reaction
    const eventId = button.data('event-id');
    const eventPostId = button.data('event-post-id');
    $.ajax({
        url: base_url + "event_photo/userPostLikeDislike",
        method: "POST",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        contentType: "application/json",
        data: JSON.stringify({ event_id: eventId, event_post_id: eventPostId, reaction: selectedEmoji }),
        success: function (response) {
            if (response.status === 1) {
                $(`#likeCount_${eventPostId}`).text(`${response.count} Likes`);
            } else {
                alert(response.message);
            }
        },
        error: function (xhr) {
            console.error(xhr.responseText);
            alert('An error occurred. Please try again.');
        }
    });

    // Hide emoji picker
    $(this).closest('#emojiDropdown').hide();
});


});






// Convert emoji character to Unicode escape sequence
function getEmojiUnicode(emoji) {
    switch (emoji) {
        case '❤️':
            return '\u{2764}';  // Heart
        case '😍':
            return '\u{1F60D}';  // Smiling face with heart-eyes
        case '👍':
            return '\u{1F44D}';  // Thumbs up
        case '😂':
            return '\u{1F602}';  // Face with tears of joy
        case '😢':
            return '\u{1F622}';  // Crying face
        default:
            return emoji;  // Return as is if not found
    }
}



// Hide emoji picker when clicking outside the post area
$(document).on('click', function (e) {
    if (!$(e.target).closest('.photo-card-head-right').length) {
        $('.photos-likes-options-wrp').hide(); // Hide emoji picker when clicked outside
    }
});


// Hide emoji picker when clicking outside
$(document).on('click', function (e) {
    if (!$(e.target).closest('#likeButton, #emojiDropdown').length) {
        $('#emojiDropdown').hide(); // Hide emoji picker when clicked outside
    }
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

$(document).on('click', '.comment-send-icon', function () {
    const commentInput = $('#post_comment');
    const commentText = commentInput.val().trim();
    const commentId = $('.commented-user-wrp').data('comment-id');
    const replyParentId = $('.reply-on-comment').data('comment-id');
    alert(replyParentId);
    if (commentText === '') {
        alert('Please enter a comment');
        return;
    }

    const eventId = $('.likeModel').data('event-id'); // Or get this dynamically as needed
    const eventPostId = $('.likeModel').data('event-post-id');

    let url;
    if (commentId) {
        url = base_url + "event_photo/userPostCommentReply";
    } else {
        url = base_url + "event_photo/userPostComment";
    }

    // Example AJAX request to submit the comment
    $.ajax({
        url: url,
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            comment: commentText,
            event_id: eventId,
            event_post_id: eventPostId,
            parent_comment_id: commentId
        },
        success: function (response) {
            if (response.success) {
                console.log(response.data);
                $('#post_comment').val('');

                const data = response.data;

                const newCommentHTML = `
                    <li class="commented-user-wrp" data-comment-id="${data.comment_id}">
                        <div class="commented-user-head">
                            <div class="commented-user-profile">
                                <div class="commented-user-profile-img">
                                    <img src="${data.profile}" alt="">
                                </div>
                                <div class="commented-user-profile-content">
                                    <h3>${data.username}</h3>
                                    <p>${data.location}</p>
                                </div>
                            </div>
                            <div class="posts-card-like-comment-right">
                                <p>${data.posttime}</p>
                                <button class="posts-card-like-btn"><i class="fa-regular fa-heart"></i></button>
                            </div>
                        </div>
                        <div class="commented-user-content">
                            <p>${data.comment}</p>
                        </div>
                        <div class="commented-user-reply-wrp">
                            <div class="position-relative d-flex align-items-center gap-2">
                                <button class="posts-card-like-btn"><i class="fa-regular fa-heart"></i></button>
                                <p>0</p>
                            </div>
                            <button class="commented-user-reply-btn">Reply</button>
                        </div>
                        <ul class="primary-comment-replies"></ul>
                    </li>
                `;


                if (data.comment_replies && data.comment_replies.length > 0) {
                    data.comment_replies.forEach(function (reply) {
                        const replyHTML = `
                            <li class="reply-on-comment" data-comment-id="${reply.id}">
                                <div class="commented-user-head">
                                    <div class="commented-user-profile">
                                        <div class="commented-user-profile-img">
                                            <img src="${reply.profile || 'default-image.png'}" alt="">
                                        </div>
                                        <div class="commented-user-profile-content">
                                            <h3>${reply.username}</h3>
                                            <p>${reply.location || ''}</p>
                                        </div>
                                    </div>
                                    <div class="posts-card-like-comment-right">
                                        <p>${reply.posttime || 'Just now'}</p>
                                        <button class="posts-card-like-btn"><i class="fa-regular fa-heart"></i></button>
                                    </div>
                                </div>
                                <div class="commented-user-content">
                                    <p>${reply.comment || 'No content'}</p>
                                </div>
                                <div class="commented-user-reply-wrp">
                                    <div class="position-relative d-flex align-items-center gap-2">
                                        <button class="posts-card-like-btn"><i class="fa-regular fa-heart"></i></button>
                                        <p>${reply.comment_total_likes || 0}</p>
                                    </div>
                                    <button class="commented-user-reply-btn">Reply</button>
                                </div>
                            </li>
                        `;

                        // Append the reply inside the current comment's reply list
                        if (commentId) {
                            const parentComment = $(`li[data-comment-id="${commentId}"]`); // Find the parent comment
                            if (parentComment.length > 0) {
                                // If the parent comment has no replies, create a new <ul> for replies
                                let replyList = parentComment.find('ul');
                                if (replyList.length === 0) {
                                    replyList = $('<ul class="primary-comment-replies"></ul>').appendTo(parentComment); // Create <ul> if not exists// Create <ul> if not exists
                                }
                                // Append the new reply under the parent comment's <ul>
                                replyList.append(newCommentHTML);
                            }
                        } else {
                            // If it's a top-level comment, append it to the top-level comment list
                            $('.posts-card-show-all-comments-inner ul').append(newCommentHTML);
                        }
                    });
                }
            }
        },
        error: function (xhr) {
            console.error(xhr.responseText);
            alert('An error occurred. Please try again.');
        }
    });
});

$(document).on('click', '.posts-card-like-btn', function () {
    const icon = this.querySelector('i');
    icon.classList.toggle('fa-regular');
    icon.classList.toggle('fa-solid');
});

// $(document).on('click', '.posts-card-like-btn', function () {
//     const heartIcon = $(this).find('i');

//     if (heartIcon.hasClass('fa-regular')) {
//         heartIcon.removeClass('fa-regular').addClass('fa-solid'); // Toggle to filled heart
//     } else {
//         heartIcon.removeClass('fa-solid').addClass('fa-regular'); // Toggle to empty heart
//     }

//     // Optionally, you can make an AJAX request here to update the server
//     console.log('Heart button clicked');
// });
$(document).on('click', '.commented-user-reply-btn', function () {
    // Alert for testing (optional)


    // Find the parent comment's username
    const parentName = $(this).closest('.commented-user-wrp').find('h3').text().trim();
    console.log(parentName);

    // Find the parent comment's id (assuming it’s stored in a data attribute)
    const parentId = $(this).closest('.commented-user-wrp').data('comment-id');
    const replyParentId = $('.reply-on-comment').data('comment-id'); // Update this according to your HTML structure
    console.log($(this).closest('.reply-on-comment'));
    // Insert the parent's name as @ParentName into the input box
    const commentBox = $('#post_comment'); // Replace with your input box ID or class
    commentBox.val(`@${parentName} `).focus(); // Add @Name and set focus to the input box

    // Set the parent comment ID in a hidden input or store it in a variable
    $('#parent_comment_id').val(parentId);
    $('#reply_comment_id').val(replyParentId);// Assuming you have a hidden input with id 'parent_comment_id'
});
const longPressDelay = 3000; // 3 seconds for long press
let pressTimer;
let isLongPress = false;

// Function to handle the long press action
function handleLongPress(element) {
    console.log("Long press detected");
    // $('#detail-photo-modal').hide();
    // Show the button and check the checkbox
    const photoCard = element.closest('.photo-card-photos-wrp');
    photoCard.find('.selected-photo-btn').show();
    photoCard.find('.form-check-input').prop('checked', true);

    // Check if any checkboxes are selected and toggle the visibility of the bulk select wrapper
    toggleBulkSelectWrapper();
}

// Function to toggle visibility of the bulk-select-photo-wrp
function toggleBulkSelectWrapper() {
    const selectedCount = $('.selected_image:checked').length; // Count selected checkboxes
    const bulkSelectWrapper = $('.phototab-add-new-photos-wrp.bulk-select-photo-wrp');
    console.log(selectedCount);

    if (selectedCount >= 2) {
        bulkSelectWrapper.removeClass('d-none'); // Show the div
        bulkSelectWrapper.find('.phototab-add-new-photos-img p').text(`${selectedCount} Photos Selected`); // Update the count
    } else if (selectedCount <= 1) {
        // bulkSelectWrapper.addClass('d-none');
    }

    // Remove the div if more than 1 image is selected
    // if (selectedCount > 1) {
    //     bulkSelectWrapper.addClass('d-none'); // Hide the div when more than 1 image is selected
    // }
}


// Mouse down event
$('.img_click').on('mousedown', function (e) {
    e.preventDefault();
    console.log("Mouse down detected");
    isLongPress = false;
    const that = $(this);

    // Start the timer for a long press
    pressTimer = setTimeout(() => {
        isLongPress = true; // Set the flag for a long press
        handleLongPress(that); // Execute the long press action
    }, longPressDelay);
});



// On checkbox change event, toggle the visibility of the bulk select wrapper
$('.form-check-input').on('change', function () {
    toggleBulkSelectWrapper();
});

$('.download_img').on('click', function () {
    // $('.form-check-input:checked').each(function () {
    //     console.log('Checkbox selected: ', $(this).data('image-src')); // Check if data-image-src exists
    // });

    // Get selected image URLs from the checkboxes
    const selectedImages = $('.selected_image:checked').map(function () {
        return $(this).data('image-src'); // Get image URLs
    }).get();

    console.log("Selected Images: ", selectedImages);

});
$('.download_img_single').on('click', function () {
    // $('.form-check-input:checked').each(function () {
    //     console.log('Checkbox selected: ', $(this).data('image-src')); // Check if data-image-src exists
    // });

    // Get selected image URLs from the checkboxes
    const imageSrc = $(this).data('image-src');

    console.log("Selected Images: ", imageSrc);
    if (imageSrc) {
        // Create a temporary link element to trigger the download
        const link = document.createElement('a');
        link.href = imageSrc;
        link.download = imageSrc.split('/').pop(); // Use the file name from the URL

        // Trigger the download by programmatically clicking the link
        link.click();
    } else {
        alert('No image source found!');
    }
});

$(document).on('click', '.open_photo_model', function () {
    clearTimeout(pressTimer); // Clear the timer
    console.log("Mouse up or leave detected");

    if (!isLongPress) {
        // If it wasn't a long press, open the modal (short press behavior)
        console.log("Short press detected");
        $('#detail-photo-modal').modal('show');
    } // Open the modal
    // Fetch the post ID from the data attribute
    const postId = $(this).data('post-id');
    const eventId = $(this).data('event-id');
    ;
    const rawData = $(this).data('image'); // Get raw data
    console.log('Raw Data:', rawData); // Debug the raw data
    const swiperWrapper = $('#media_post');
    swiperWrapper.empty();
    if (rawData && rawData.length > 0) {
        rawData.forEach((media) => {
            swiperWrapper.append(`
                <div class="swiper-slide">
                    <div class="posts-card-show-post-img">
                        <img src="${media}" alt="" />
                    </div>
                </div>
            `);
        });
    }
    swiper.destroy(true, true);

    // Reinitialize Swiper
    swiper = new Swiper(".photo-detail-slider", {
        slidesPerView: 1,
        spaceBetween: 30,
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
        },
    });
    //let parentId = null;  // Default to null, assuming no parent

    // if ($('.commented-user-wrp').length > 0) {
    //     // If this is a reply button, get the parent ID from the closest .commented-user-wrp element
    //     parentId = $('.commented-user-wrp').data('parent-id');  // Assuming `data-parent-id` holds the parent_id
    // }
    // console.log(parentId);
    var url;


    url = base_url + "event_photo/fetch-photo-details";



    $.ajax({
        url: url, // Update with your server-side endpoint
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

                const profileImage = data.profile || generateProfileImage(data.firstname, data.lastname);
                console.log('Profile Image URL:', profileImage);
                // Check if profileImage is an image URL or HTML content
                if (profileImage.startsWith('http') || profileImage.startsWith('data:image')) {
                    // If it's a valid image URL, set it as the src of the image tag
                    $('.posts-card-head-left-img').html(`<img src="${profileImage}" alt="Profile Image">`);
                } else {
                    // If it's a placeholder (HTML content), insert it directly inside the div
                    $('.posts-card-head-left-img').html(profileImage);
                }

                function generateProfileImage(firstname, lastname) {
                    const firstInitial = firstname ? firstname[0].toUpperCase() : '';
                    const secondInitial = lastname ? lastname[0].toUpperCase() : '';
                    const initials = `${firstInitial}${secondInitial}`;
                    const fontColor = `fontcolor${firstInitial}`;

                    // Return initials inside an h5 tag with dynamic styling
                    return `<h5 class="${fontColor} font_name">${initials}</h5>`;
                }
                $('.likeModel').data('event-id', data.event_id).data('event-post-id', data.id);
                // Name
                const fullName = `${data.firstname} ${data.lastname}`;
                $('#post_name').text(fullName);

                // Location
                const location = data.location.trim() !== '' ? data.location : '';
                $('#location').text(location);

                // Post Message
                $('#post_message').text(data.post_message);


                $('#likes').text(data.total_likes + ' Likes');
                // Add 'Likes' after the number
                $('#comments').text(data.total_comments + ' Comments')

                console.log('Self Reaction:', data.self_reaction); // Debugging
                console.log(typeof data.self_reaction); // Output: string


                var reaction_store = data.self_reaction.trim(); // Ensure no leading/trailing whitespace
                console.log("Trimmed Reaction Store:", reaction_store); // Log trimmed reaction value
                console.log("Reaction Unicode Code:", reaction_store.charCodeAt(0)); // Log the Unicode code of the first character


                // var reaction_store = data.self_reaction.trim(); // Ensure no leading/trailing whitespace
                console.log(reaction_store);




                // Check and toggle the heart icon based on the reaction
                const likeButton = $('#likeButtonModel').find('i'); // Ensure this targets the right button
                console.log(likeButton);

                var unicodeString = '\\u{2764}';  // This is the string as you want it: "\u{2764}"
                console.log(unicodeString); // Will log the Unicode code as a hex string


                if (reaction_store == unicodeString) {
                    console.log("User has liked the post.");
                    likeButton.removeClass('fa-regular').addClass('fa-solid'); // Add filled heart class
                } else {
                    console.log("User has not liked the post.");
                    likeButton.removeClass('fa-solid').addClass('fa-regular'); // Add empty heart class
                }



                // Update the emoji list based on the reaction
                const reactionList = $('.posts-card-like-comment-left ul');
                reactionList.find('li').each(function () {
                    const img = $(this).find('img');
                    if (img.length) {
                        const emojiSrc = img.attr('src');
                        console.log('Reaction Store:', reaction_store);
                        console.log('Emoji Src:', emojiSrc);

                        // Ensure we compare reaction_store to actual emoji unicode
                        const heartUnicode = '\\u{2764}';
                        const smileUnicode = '\\u{1F60D}';
                        const clapUnicode = '\\u{1F44F}';

                        // Reset all emojis: remove 'selected' class and show all emojis
                        $(this).removeClass('selected').show();  // Remove 'selected' and show

                        // Hide all emojis that don't match the reaction
                        if (reaction_store === heartUnicode && emojiSrc.includes('heart-emoji.png')) {
                            $(this).addClass('selected');
                        } else if (reaction_store === smileUnicode && emojiSrc.includes('eye-heart-emoji.png')) {
                            $(this).addClass('selected');
                        } else if (reaction_store === clapUnicode && emojiSrc.includes('clap-icon.png')) {
                            $(this).addClass('selected');
                        } else {
                            $(this).hide();
                        }
                    } else {
                        console.log('No img tag found in this li element.');
                    }
                });
                updateReactions(data.reactionList, data.firstname, data.lastname, data.profile,data.location);

                const commentsWrapper = $('.posts-card-show-all-comments-inner ul');
                commentsWrapper.empty(); // Clear existing comments

                if (data.latest_comment && Array.isArray(data.latest_comment)) {
                    data.latest_comment.forEach(comment => {
                        commentsWrapper.append(`
                            <li class="commented-user-wrp" data-comment-id="${comment.id}">
                              <input type="hidden" id="parent_comment_id" value="${comment.id}">
                                <div class="commented-user-head">
                                    <div class="commented-user-profile">
                                        <div class="commented-user-profile-img">
                                            <img src="${comment.profile || '{{ asset("assets/front/img/header-profile-img.png") }}'}" alt="">
                                        </div>
                                        <div class="commented-user-profile-content">
                                            <h3>${comment.username || ''}</h3>
                                            <p>${comment.location || ''}</p>
                                        </div>
                                    </div>
                                    <div class="posts-card-like-comment-right">
                                        <p>${comment.posttime || ''}</p>
                                        <button class="posts-card-like-btn">
                                            <i class="fa-regular fa-heart"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="commented-user-content">
                                    <p>${comment.comment || ''}</p>
                                </div>
                                <div class="commented-user-reply-wrp">
                                    <div class="position-relative d-flex align-items-center gap-2">
                                        <button class="posts-card-like-btn">
                                            <i class="fa-regular fa-heart"></i>
                                        </button>
                                        <p>${comment.comment_total_likes || 0}</p>
                                    </div>
                                    <button class="commented-user-reply-btn">Reply</button>
                                </div>

                            </li>
                        `);
                    })
                }

            } else {
                console.log('No data found in the array.');
            }
        }
    });

    function updateReactions(reactions, firstname, lastname, profile,location) {
        console.log(reactions); // Debug the reactions array
        console.log(firstname);
        console.log(lastname);

        const emojiPaths = {
            'heart': '/assets/front/img/heart-emoji.png',
            'thumb': '/assets/front/img/thumb-icon.png',
            'smily': '/assets/front/img/smily-emoji.png',
            'eye-heart': '/assets/front/img/eye-heart-emoji.png',
            'clap': '/assets/front/img/clap-icon.png',
        };

        const allReactionsList = $('#nav-all-reaction ul');
        const heartReactionsList = $('#nav-heart-reaction ul');
        const thumbReactionsList = $('#nav-thumb-reaction ul');
        const smilyReactionsList = $('#nav-smily-reaction ul');
        const eyeHeartReactionsList = $('#nav-eye-heart-reaction ul');
        const clapReactionsList = $('#nav-clap-reaction ul');

        const reactionCounts = {
            heart: 0,
            thumb: 0,
            smily: 0,
            'eye-heart': 0,
            clap: 0,
        };

        // Clear all reaction lists
        allReactionsList.empty();
        heartReactionsList.empty();
        thumbReactionsList.empty();
        smilyReactionsList.empty();
        eyeHeartReactionsList.empty();
        clapReactionsList.empty();
        const getProfileContent = () => {
            if (profile && profile !== '') {
                return `<img src="${profile}" alt="">`;
            } else {
                const firstInitial = firstname ? firstname[0].toUpperCase() : '';
                const secondInitial = lastname ? lastname[0].toUpperCase() : '';
                const initials = `${firstInitial}${secondInitial}`;
                const fontColor = `fontcolor${firstInitial}`;
                return `<h5 class="${fontColor}">${initials}</h5>`;
            }
        };
        // Iterate through reactions array
        reactions.forEach(reaction => {
            let reactionType = '';
            let emojiSrc = '';

            // Map each reaction to a type
            switch (reaction) {
                case '\\u{2764}': // Heart
                    reactionType = 'heart';
                    break;
                case '\\u{1F44D}': // Thumbs Up
                    reactionType = 'thumb';
                    break;
                case '\\u{1F604}': // Smiley
                    reactionType = 'smily';
                    break;
                case '\\u{1F60D}': // Eye-Heart
                    reactionType = 'eye-heart';
                    break;
                case '\\u{1F44F}': // Clap
                    reactionType = 'clap';
                    break;
                default:
                    console.warn(`Unknown reaction: ${reaction}`);
                    return; // Skip unknown reactions
            }

            // Increment the reaction count
            reactionCounts[reactionType]++;

            // Get the emoji image source
            emojiSrc = emojiPaths[reactionType];
            const profileContent = getProfileContent();
            // Create reaction list item
            const reactionItem = `<li class="reaction-info-wrp">
                                    <div class="commented-user-head">
                                        <div class="commented-user-profile">
                                            <div class="commented-user-profile-img">
                                            ${profileContent}
                                            </div>
                                            <div class="commented-user-profile-content">
                                                <h3>${firstname} ${lastname}</h3>
                                                <p> ${location}</p>
                                            </div>
                                        </div>
                                        <div class="posts-card-like-comment-right reaction-profile-reaction-img">
                                            <img src="${emojiSrc}" alt="">
                                        </div>
                                    </div>
                                  </li>`;

            // Append to specific reaction list
            if (reactionType === 'heart') {
                heartReactionsList.append(reactionItem);
            } else if (reactionType === 'thumb') {
                thumbReactionsList.append(reactionItem);
            } else if (reactionType === 'smily') {
                smilyReactionsList.append(reactionItem);
            } else if (reactionType === 'eye-heart') {
                eyeHeartReactionsList.append(reactionItem);
            } else if (reactionType === 'clap') {
                clapReactionsList.append(reactionItem);
            }

            // Append the same item to "All Reactions" list
            console.log('Appending to All Reactions:', reactionItem);
            allReactionsList.append(reactionItem);
        });

        // Update the counts in the navigation tabs
        const totalReactions = Object.values(reactionCounts).reduce((sum, count) => sum + count, 0);
        $('#nav-all-reaction-tab').html(`All ${totalReactions}`);
        $('#nav-heart-reaction-tab').html(
            `<img src="${emojiPaths['heart']}" alt=""> ${reactionCounts.heart}`
        );
        $('#nav-thumb-reaction-tab').html(
            `<img src="${emojiPaths['thumb']}" alt=""> ${reactionCounts.thumb}`
        );
        $('#nav-smily-reaction-tab').html(
            `<img src="${emojiPaths['smily']}" alt=""> ${reactionCounts.smily}`
        );
        $('#nav-eye-heart-reaction-tab').html(
            `<img src="${emojiPaths['eye-heart']}" alt=""> ${reactionCounts['eye-heart']}`
        );
        $('#nav-clap-reaction-tab').html(
            `<img src="${emojiPaths['clap']}" alt=""> ${reactionCounts.clap}`
        );
    }





});

let longPressTimers;
let isLong_press = false;

$(document).on('mousedown', '#likeButtonModel', function () {
    isLong_press = false; // Reset the flag
    const button = $(this);

    // Start the long press timer
    longPressTimer = setTimeout(() => {
        isLong_press = true; // Mark as long press
        const emojiDropdown = button.closest('.posts-card-like-comment-right').find('#emojiDropdown1');
        emojiDropdown.show(); // Show the emoji picker
        //button.find('i').text(''); // Clear the heart icon
    }, 500); // 500ms for long press
});
$(document).on('click', '#likeButtonModel', function () {
    // alert();
    clearTimeout(longPressTimers); // Clear the long press timer

    // If it's a long press, don't process the click event
    if (isLong_press) return;

    // Handle single tap like/unlike
    const button = $(this);
    const isLiked = button.hasClass('liked');
    const reaction = isLiked ? '\u{2764}' : '\u{1F90D}'; // Toggle reaction: 💔 or ❤️
    const likeButton = $(this);
    // Toggle like button appearance
    const icon = $(this).find('i');

    // Toggle the reaction locally
    if (icon.hasClass('fa-regular')) {
        icon.removeClass('fa-regular').addClass('fa-solid'); // Mark as liked
    } else {
        icon.removeClass('fa-solid').addClass('fa-regular'); // Mark as not liked
    }

    // AJAX call to update the like state
    const eventId = button.data('event-id');
    const eventPostId = button.data('event-post-id');
    $.ajax({
        url: base_url + "event_photo/userPostLikeDislike",
        method: "POST",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        contentType: "application/json",
        data: JSON.stringify({ event_id: eventId, event_post_id: eventPostId, reaction: reaction }),
        success: function (response) {
            if (response.status === 1) {

                $(`#likeCount_${eventPostId}`).text(`${response.count} Likes`);
            } else {
                alert(response.message);
            }
        },
        error: function (xhr) {
            console.error(xhr.responseText);
            alert('An error occurred. Please try again.');
        }
    });
});

$(document).on('click', '#emojiDropdown1 .model_emoji', function () {
    const selectedEmoji = $(this).data('emoji');
    const button = $(this).closest('.emoji_set').find('#likeButton');
    const emojiDisplay = button.find('#show_comment_emoji');

    // Replace heart icon with selected emoji
    emojiDisplay.removeClass();
    emojiDisplay.text(selectedEmoji);

    // AJAX call to update emoji reaction
    const eventId = button.data('event-id');
    const eventPostId = button.data('event-post-id');
    console.log(eventPostId)
    $.ajax({
        url: base_url + "event_photo/userPostLikeDislike",
        method: "POST",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        contentType: "application/json",
        data: JSON.stringify({ event_id: eventId, event_post_id: eventPostId, reaction: selectedEmoji }),
        success: function (response) {
            if (response.status === 1) {
                $(`#likeCount_${eventPostId}`).text(`${response.count} Likes`);
            } else {
                alert(response.message);
            }
        },
        error: function (xhr) {
            console.error(xhr.responseText);
            alert('An error occurred. Please try again.');
        }
    });

    // Hide emoji picker
    $(this).closest('#emojiDropdown1').hide();
});
$(document).ready(function () {
    // Define visibility options
    const visibilityOptions = {
        1: "Everyone",
        2: "RSVP’d - Yes",
        3: "RSVP’d - No",
        4: "RSVP’d - No Reply",
    };

    // Load saved settings or set defaults
    let savedVisibility = localStorage.getItem('post_privacys') || '1'; // Default: Everyone
    let savedAllowComments = localStorage.getItem('commenting_on_off') === '1'; // Convert to boolean
    console.log('Saved Allow Comments:', savedAllowComments); // Debugging
    if (!savedAllowComments == true) {
        savedAllowComments = '1'; // Default to true
        localStorage.setItem('commenting_on_off', savedAllowComments);
    }
    // Apply settings to the form
    const visibilityRadio = $('input[name="post_privacy"][value="' + savedVisibility + '"]');
    if (visibilityRadio.length > 0) {
        visibilityRadio.prop('checked', true);
    } else {
        // Fallback to default visibility if saved value is invalid
        savedVisibility = '1';
        $('input[name="post_privacy"][value="1"]').prop('checked', true);
    }

    $('#allowComments').prop('checked', savedAllowComments);

    // Update hidden fields with initial values
    $('#hiddenVisibility').val(savedVisibility);
    $('#hiddenAllowComments').val(savedAllowComments ? '1' : '0');

    // Display initial settings
    const visibilityName = visibilityOptions[savedVisibility];
    $('#savedSettingsDisplay').html(`
        <h4>${visibilityName} <i class="fa-solid fa-angle-down"></i></h4>
        <p>${savedAllowComments === '1' ? "" : ""}</p>
    `);

    // Save Button Click Handler
    $('#saveSettings').on('click', function () {
        // Fetch selected visibility
        const visibility = $('input[name="post_privacy"]:checked').val() || '1'; // Default to Everyone if null
        // Fetch commenting status
        const allowComments = $('#allowComments').is(':checked') ? '1' : '0';

        // Save settings to localStorage
        localStorage.setItem('post_privacys', visibility);
        localStorage.setItem('commenting_on_off', 1);

        // Update hidden fields
        $('#hiddenVisibility').val(visibility);
        $('#hiddenAllowComments').val(allowComments);

        // Update display area
        const visibilityName = visibilityOptions[visibility];
        $('#savedSettingsDisplay').html(`
            <h4>${visibilityName} <i class="fa-solid fa-angle-down"></i></h4>
            <p>${allowComments === '1' ? "" : ""}</p>
        `);

        console.log('Saved Settings:', { visibility, allowComments });
    });
});
