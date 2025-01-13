
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
        ;
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
                    const profileImage = data.profile || '{{ asset("assets/front/img/header-profile-img.png") }}';
                    $('.posts-card-head-left-img img').attr('src', profileImage);
                    $('.likeModel').data('event-id', data.event_id).data('event-post-id', data.id);
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
                    $('#likes').text(data.total_likes + ' Likes');
                    // Add 'Likes' after the number
                    $('#comments').text(data.total_comments + ' Comments')
                    const likeButton = $('.likeModel');
                    console.log('Self Reaction:', data.self_reaction); // Debugging
                    if (data.self_reaction === '\u{2764}') {
                        likeButton.find('i').removeClass('fa-regular').addClass('fa-solid'); // Filled heart
                    } else {
                        likeButton.find('i').removeClass('fa-solid').addClass('fa-regular');; // Empty heart
                    }
                    const commentsWrapper = $('.posts-card-show-all-comments-inner ul');
                    commentsWrapper.empty(); // Clear existing comments

                    if (data.latest_comment && Array.isArray(data.latest_comment)) {
                        data.latest_comment.forEach(comment => {
                            commentsWrapper.append(`
                                <li class="commented-user-wrp" data-comment-id="${comment.id}">
                                  <input type="hidden" id="parent_comment_id" value="${comment.id }">
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
    });


    let pressTimer;
    const longPressDelay = 5000; // 1 second long press duration


    // }).on('mouseleave', function () {
    //     clearTimeout(pressTimer);
    // });


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

$(document).on('click', '.comment-send-icon', function () {
    const commentInput = $('#post_comment');
    const commentText = commentInput.val().trim();
    const commentId = $('.commented-user-wrp').data('comment-id');

    // alert(commentId);
    if (commentText === '') {
        alert('Please enter a comment');
        return;
    }

    const eventId = $('.likeModel').data('event-id'); // Or get this dynamically as needed
    const eventPostId = $('.likeModel').data('event-post-id');
    console.log(eventId, eventPostId);

    var url ;
    if(commentId){
        url =  base_url + "event_photo/userPostCommentReply";
    }else{
        url =  base_url + "event_photo/userPostComment";
    }
    // Example AJAX request to submit the comment
    $.ajax({
        url: url, // Replace with your actual endpoint
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            comment: commentText,
            event_id: eventId,
            event_post_id: eventPostId,
            parent_comment_id:commentId
        },
        success: function (response) {
            if (response.success) {
                console.log(response.data);
                $('#post_comment').text('');
                $('#post_comment').val('');

                const data = response.data;

                const newCommentHTML = `
                    <li class="commented-user-wrp">
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

                    </li>
                `;

                // Append the new comment to the list
                $('.posts-card-show-all-comments-inner ul').append(newCommentHTML);

                if (data.comment_replies && data.comment_replies.length > 0) {
                    data.comment_replies.forEach(function(reply) {
                        const replyHTML = `
                            <li class="reply-on-comment" data-comment-id="${reply.id}">
                                <div class="commented-user-head">
                                    <div class="commented-user-profile">
                                        <div class="commented-user-profile-img">
                                            <img src="${reply.profile || 'default-image.png'}" alt="">
                                        </div>
                                        <div class="commented-user-profile-content">
                                            <h3>${reply.username}</h3>
                                            <p>${reply.location || 'Unknown'}</p>
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

                        // Append the reply inside the current comment's reply list (ul)
                        $(this).closest('li').find('.commented-user-reply-wrp ul').append(replyHTML);
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
    const parentId = $(this).closest('.commented-user-wrp').data('comment-id');  // Update this according to your HTML structure
console.log(parentId);
    // Insert the parent's name as @ParentName into the input box
    const commentBox = $('#post_comment'); // Replace with your input box ID or class
    commentBox.val(`@${parentName} `).focus(); // Add @Name and set focus to the input box

    // Set the parent comment ID in a hidden input or store it in a variable
    $('#parent_comment_id').val(parentId);  // Assuming you have a hidden input with id 'parent_comment_id'
});
$('.img_click').on('mousedown', function () {
    console.log("yes")
    var that = $(this);
    // $('#detail-photo-modal').modal('hide');
    pressTimer = setTimeout(() => {

        // Show the button and check the checkbox when long pressed
        that.closest('.photo-card-photos-wrp').find('.selected-photo-btn').show();
        that.closest('.photo-card-photos-wrp').find('.form-check-input').prop('checked', true);
    },longPressDelay);
})
