
$(document).ready(function () {

    let longPressTimer;
    let isLongPresss = false;

    $(document).on('mousedown', '#likeButton', function () {
        isLongPresss = false; // Reset the flag
        const button = $(this);

        // Start the long press timer
        longPressTimer = setTimeout(() => {
            isLongPresss = true; // Mark as long press
            const emojiDropdown = button.closest('.posts-card-like-comment-right').find('#emojiDropdown');
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
        const reaction = isLiked ? '\u{1F90D}' : '\u{2764}'; // Toggle reaction: 💔 or ❤️

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
            url: base_url + "event_wall/userPostLikeDislike",
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

    // Handle comment submission (first-time comment or reply)
    // Handle comment submission
    // Handle comment submission
    // Handle comment submission
    $(document).on('click', '.comment-send-icon', function () {
        const parentWrapper = $(this).closest('.posts-card-main-comment'); // Find the closest comment wrapper
        const commentInput = parentWrapper.find('#post_comment'); // Find the input within the current post

        const commentText = commentInput.val().trim();
        const parentCommentId = $('.commented-user-wrp.active').data('comment-id') || null; // Find active comment if replying

        if (commentText === '') {
            alert('Please enter a comment');
            return;
        }

        const eventId = $(this).data('event-id');
        const eventPostId = $(this).data('event-post-id');

        const url = parentCommentId
            ? base_url + "event_photo/userPostCommentReply"
            : base_url + "event_photo/userPostComment";

        // AJAX request
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
                parent_comment_id: parentCommentId
            },
            success: function (response) {
                if (response.success) {
                    const data = response.data;

                    // Generate profile image or initials
                    const profileImage = data.profile || generateProfileImage(data.username);

                    function generateProfileImage(username) {
                        if (!username) return ''; // Return an empty string if the username is undefined

                        // Split the username into parts
                        const nameParts = username.split(' ');
                        const firstInitial = nameParts[0]?.[0]?.toUpperCase() || '';
                        const secondInitial = nameParts[1]?.[0]?.toUpperCase() || '';
                        const initials = `${firstInitial}${secondInitial}`;

                        // Generate a font color class based on the first initial
                        const fontColor = `fontcolor${firstInitial}`;
                        return `<h5 class="${fontColor} font_name">${initials}</h5>`;
                    }

                    const newCommentHTML = `
                <li class="commented-user-wrp" data-comment-id="${data.comment_id}">
                    <div class="commented-user-head">
                        <div class="commented-user-profile">
                            <div class="commented-user-profile-img">
                                 ${profileImage}
                            </div>
                            <div class="commented-user-profile-content">
                                <h3>${data.username}</h3>
                                <p>${data.location || ''}</p>
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

                    if (parentCommentId) {
                        // Append as a reply to the parent comment
                        const parentComment = $(`li[data-comment-id="${parentCommentId}"]`);
                        if (parentComment.length > 0) {
                            let replyList = parentComment.find('ul.primary-comment-replies');
                            if (replyList.length === 0) {
                                replyList = $('<ul class="primary-comment-replies"></ul>').appendTo(parentComment);
                            }

                            // Check if the reply is already appended
                            const existingReply = replyList.find(`li[data-comment-id="${data.comment_id}"]`);
                            if (existingReply.length === 0) {
                                replyList.append(newCommentHTML);
                            }
                        }
                    } else {
                        // Append as a new top-level comment
                        const commentList = $(`.posts-card-show-all-comments-wrp.show_${eventPostId}`).find('.top-level-comments');

                        // Check if the comment is already appended
                        const existingComment = commentList.find(`li[data-comment-id="${data.comment_id}"]`);
                        if (existingComment.length === 0) {
                            commentList.append(newCommentHTML);
                        }
                    }
                    const commentCountElement = $(`#comment_${eventPostId}`);
                    const currentCount = parseInt(commentCountElement.text()) || 0;
                    commentCountElement.text(`${currentCount + 1} Comments`);
                    // Clear input field
                    commentInput.val('');

                    // Handle replies if any are provided in the response
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
                            replyList.append(replyHTML);

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



    $(document).on('click', '.commented-user-reply-btn', function () {
        // Find the closest '.posts-card-main-comment' wrapper (the main post container)
        const parentWrapper = $(this).closest('.posts-card-show-all-comments-wrp').prev('.posts-card-main-comment');

        if (!parentWrapper.length) {
            console.error("Parent wrapper not found!");
            return;
        }

        // Find the username and comment ID from the current comment being replied to
        const parentName = $(this).closest('.commented-user-wrp').find('h3').text().trim();
        const parentId = $(this).closest('.commented-user-wrp').data('comment-id');

        // Debugging information
        console.log("Parent Wrapper:", parentWrapper);
        console.log("Parent Name:", parentName);
        console.log("Parent ID:", parentId);

        // Set the active class on the currently selected comment
        $('.commented-user-wrp').removeClass('active'); // Remove 'active' from all comments
        $(this).closest('.commented-user-wrp').addClass('active'); // Add 'active' to the current comment

        // Find the comment box inside the parent wrapper and insert the username
        const commentBox = parentWrapper.find('.post_comment');
        if (!commentBox.length) {
            console.error("Comment input field not found!");
            return;
        }

        // Insert the '@username' into the comment box and focus
        commentBox.val(`@${parentName} `).focus();
    });



    // Handle reply button click (when replying to a comment)



});
$(document).ready(function () {
    $('#home-tab').on('click', function () {
        $.ajax({
            url: base_url + 'event_wall/get_yesviteContact', // API endpoint
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType: 'json',
            success: function (response) {
                const contacts = response.contacts;
                // console.log(contacts);
                const container = $('.yesvite_contact');
                // container.empty(); // Clear previous contacts

                container.html(contacts);


            },
            error: function () {
                alert('Failed to load contacts.');
            }
        });
    });
});
$(document).ready(function () {
    $('#profile-tab').on('click', function () {
        $.ajax({
            url: base_url + 'event_wall/get_phoneContact', // API endpoint
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType: 'json',
            success: function (response) {
                const contacts = response.contacts;
                //  console.log(contacts);
                const container = $('.phone_contact');
                // container.empty(); // Clear previous contacts

                container.html(contacts);


            },
            error: function () {
                alert('Failed to load contacts.');
            }
        });
    });
});
$(document).ready(function () {
    $('#allcontact').on('click', function () {
        $.ajax({
            url: base_url + 'event_wall/get_yesviteContact', // API endpoint
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType: 'json',
            success: function (response) {
                const contacts = response.contacts;
                //  console.log(contacts);
                const container = $('.yesvite_contact');
                // container.empty(); // Clear previous contacts

                container.html(contacts);


            },
            error: function () {
                alert('Failed to load contacts.');
            }
        });
    });
});


let selectedContacts = [];

// Event listener for checkboxes
$(document).on('change', '.contact-checkbox', function () {
    const $checkbox = $(this);
    const contactData = {
        id: $checkbox.data('id'),
        name: $checkbox.data('name'),
        lastname: $checkbox.data('last'),
        email: $checkbox.data('email'),
        phone: $checkbox.data('phone'),
    };
console.log(contactData);
    if ($checkbox.is(':checked')) {
        // Add contact to selected list
        console.log(1);

        selectedContacts.push(contactData);
    } else {
        // Remove contact from selected list
        console.log(2);
        selectedContacts = selectedContacts.filter(contact => contact.id !== contactData.id);
    }

    updateModalContent();
});

// Function to update modal content
// Function to update modal content
function updateModalContent() {
    const $modalBody = $('.selected-contacts-list');
    $modalBody.empty();

    function generateProfileImage(firstname, lastname) {
        const firstInitial = firstname ? firstname[0].toUpperCase() : ''; // Ensures the first letter is uppercase
        const secondInitial = lastname ? lastname[0].toUpperCase() : ''; // Ensures the first letter is uppercase
        const initials = `${firstInitial}${secondInitial}`; // Combine initials
        const fontColor = `fontcolor${firstInitial.toUpperCase()}`; // Ensure consistent fontColor class

        // Return initials inside an h5 tag with dynamic styling
        return `<h5 class="${fontColor} font_name">${initials}</h5>`;
    }


    selectedContacts.forEach(contact => {
        const profileImage = contact.profile || generateProfileImage(contact.name,contact.lastname);

        const contactHtml = `
            <div class="guest-user">
            <div class="guest-user-img">
                    ${profileImage}

                                        <a href="#" class="close">
                                        <svg width="19" height="18" viewBox="0 0 19 18" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <rect x="1.20312" y="1" width="16" height="16" rx="8"
                                                fill="#F73C71" />
                                            <rect x="1.20312" y="1" width="16" height="16" rx="8"
                                                stroke="white" stroke-width="2" />
                                            <path d="M6.86719 6.66699L11.5335 11.3333" stroke="white"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M6.8649 11.3333L11.5312 6.66699" stroke="white"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </a>
                                    </div>
                    <h6>${contact.name} ${contact.lastname}</h6>


            </div>
        `;

        $modalBody.append(contactHtml);
    });
    $('.selected-contacts-list').on('click', '.close', function (e) {
        e.preventDefault();

        // Get the index of the contact to remove
        const $guestUser = $(this).closest('.guest-user');
        const index = $guestUser.data('index');

        // Remove the contact from the array
        selectedContacts.splice(index, 1);

        // Update the modal content
        updateModalContent();
    });
}

// Example usage of the above function
// Add the modal body where selected contacts will be displayed
$('.modal').on('show.bs.modal', function () {
    updateModalContent();
});


// Event listener for removing contacts
$(document).on('click', '.remove-contact', function (e) {
    e.preventDefault();

    const contactId = $(this).data('id');
    selectedContacts = selectedContacts.filter(contact => contact.id !== contactId);
    updateModalContent();

    // Uncheck the corresponding checkbox
    $(`.contact-checkbox[data-id="${contactId}"]`).prop('checked', false);
});

