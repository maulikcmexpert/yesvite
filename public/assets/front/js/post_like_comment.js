
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
    $(document).on('click', '.comment-send-icon', function () {
        const parentWrapper = $(this).closest('.posts-card-main-comment'); // Find the closest comment wrapper
        const commentInput = parentWrapper.find('#post_comment'); // Find the input within the current post

        const commentText = commentInput.val().trim();
        const parentCommentId = $('.commented-user-wrp.active').data('comment-id') || null; // Find active comment if replying

        if (commentText === '') {
            alert('Please enter a comment');
            return;
        }
        console.log(parentCommentId);
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
                    console.log(response.data);
                    $('#post_comment').val(''); // Clear the input field

                    const data = response.data;

                    const profileImage = data.profile || generateProfileImage(data.username);
                    console.log('Profile Image URL:', profileImage);


                    function generateProfileImage(username) {
                        if (!username) return ''; // Return an empty string if the username is undefined

                        // Split the username into parts
                        const nameParts = username.split(' ');

                        // Get the first and second initials
                        const firstInitial = nameParts[0]?.[0]?.toUpperCase() || '';
                        const secondInitial = nameParts[1]?.[0]?.toUpperCase() || '';
                        const initials = `${firstInitial}${secondInitial}`;

                        // Generate a font color class based on the first initial (optional)
                        const fontColor = `fontcolor${firstInitial}`;

                        // Return initials inside an h5 tag with dynamic styling
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
                        const parentComment = $(`li[data-comment-id="${parentCommentId}"]`); // Find the parent comment
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
                        // Append as a new top-level comment
                        $('.posts-card-show-all-comments-inner ul').append(newCommentHTML);
                    }
                }
            },
            error: function (xhr) {
                console.error(xhr.responseText);
                alert('An error occurred. Please try again.');
            }
        });
    });

    // Handle reply button click
    $(document).on('click', '.commented-user-reply-btn', function () {
        const parentName = $(this).closest('.commented-user-wrp').find('h3').text().trim();
        const parentId = $(this).closest('.commented-user-wrp').data('comment-id');

        // Set the active comment for reply
        $('.commented-user-wrp').removeClass('active'); // Clear any previously active comments
        $(this).closest('.commented-user-wrp').addClass('active');

        // Insert the parent username in the input field
        const commentBox = $('#post_comment');
        commentBox.val(`@${parentName} `).focus();
    });


    // Handle reply button click (when replying to a comment)
    $(document).on('click', '.commented-user-reply-btn', function () {
        const parentName = $(this).closest('.commented-user-wrp').find('h3').text().trim();
        const parentId = $(this).closest('.commented-user-wrp').data('comment-id');

        // Set the active comment for reply
        $('.commented-user-wrp').removeClass('active'); // Clear any previously active comments
        $(this).closest('.commented-user-wrp').addClass('active');
        $('#parent_comment_id').val(parentId);
        // Insert the parent username in the input field
        const commentBox = $('#post_comment');
        commentBox.val(`@${parentName} `).focus();
    });



});
