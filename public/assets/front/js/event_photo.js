$(document).ready(function () {
    // Function to update character count


    // Function to validate form fields
    function validateForm() {
        let isValid = true;

        $(".create_post").prop("disabled", !isValid);
    }

    // Submit form on button click
    $(document).on("click", ".create_post", function () {
        // Check if the poll form exists and is valid

        var photoForm = $("#photoForm");
        var textForm = $("#textform");
        //   var postContent = document.getElementById('postContent').value.trim();
        // Fallback to empty string if #postContent does not exist

        console.log(
            "Photo Form:",
            photoForm.length > 0 ? "Exists" : "Does not exist"
        );
        // console.log('Text Form:', textForm.length > 0 ? 'Exists' : 'Does not exist');
        //console.log('Post Content:', postContent);

        // If a photo form exists and is visible, submit it
        if (photoForm.is(":visible") && photoForm.length > 0) {
            // if (postContent === '') {
            //     alert('Please enter some content for the photo post.');
            //     return;
            // }
            // Set the value of the hidden input in the photo form
            //  document.getElementById('photoContent').value = postContent;
            photoForm.submit();
        }
        // If neither form exists, check for a plain text post
        else if (textForm.length > 0 && postContent !== "") {
            textForm.submit();
        }
        // If no valid content is provided, show an alert
        else {
            alert("Please fill all required fields before submitting.");
        }
    });

    // $(".posts-card-like-btn").on("click", function () {
    //     const icon = this.querySelector("i");
    //     icon.classList.toggle("fa-regular");
    //     icon.classList.toggle("fa-solid");
    // });

    $(".show-comments-btn").click(function () {
        $(".posts-card-show-all-comments-wrp").toggleClass("d-none");
    });
    $(".show-comment-reply-btn").click(function () {
        $(".reply-on-comment").toggleClass("d-none");
    });
    $(".likeButton").each(function () {
        const button = $(this);
        const eventPostId = button.data("event-post-id");
        const reaction = userReaction[eventPostId]; // Get the reaction for the current post

        // Set the initial state based on the reaction
        if (reaction === "❤") {
            button.addClass("liked");
            button.find("i").removeClass("fa-regular").addClass("fa-solid"); // Set heart icon to solid
        } else {
            button.removeClass("liked");
            button.find("i").removeClass("fa-solid").addClass("fa-regular"); // Set heart icon to regular
        }
    });

    let longPressTimer;
    let isLongPresss = false;
    let reactionIcons = {
        "❤️": base_url + "assets/front/img/heart-emoji.png", // ❤️
        "\\u{2764}": base_url + "assets/front/img/heart-emoji.png", // ❤️
        "👍": base_url + "assets/front/img/thumb-icon.png", // 👍
        "\\u{1F44D}": base_url + "assets/front/img/thumb-icon.png", // 👍
        "\\u{1F604}": base_url + "assets/front/img/smily-emoji.png", // 😄
        "\\u{1F44F}": base_url + "assets/front/img/smily-emoji.png", // 😄
        "😊": base_url + "assets/front/img/smily-emoji.png", // 😄
        "\\u{1F60D}": base_url + "assets/front/img/eye-heart-emoji.png", // 😍
        "😍": base_url + "assets/front/img/eye-heart-emoji.png", // 😍
        "\\u{1F44F}": base_url + "assets/front/img/clap-icon.png", // 👏
        "👏": base_url + "assets/front/img/clap-icon.png", // 👏
    };
    $(document).on("mousedown", "#likeButton", function () {
        isLongPresss = false; // Reset the flag
        const button = $(this);

        // Start the long press timer
        longPressTimer = setTimeout(() => {
            isLongPresss = true; // Mark as long press
            const emojiDropdown = button
                .closest(".photo-card-head-right")
                .find("#emojiDropdown");
            emojiDropdown.show(); // Show the emoji picker
            //button.find('i').text(''); // Clear the heart icon
        }, 500); // 500ms for long press
    });

    $(document).on("click", "#likeButton", function () {
        return;
        //clearTimeout(longPressTimer); // Clear the long press timer

        // If it's a long press, don't process the click event
        if (isLongPresss) return;

        // Handle single tap like/unlike
        const button = $(this);
        const isLiked = button.hasClass("liked");
        const reaction = isLiked ? "\u{2764}" : "\u{1F90D}"; // Toggle reaction: 💔 or ❤️

        // Toggle like button appearance
        if (isLiked) {
            button.removeClass("liked");
            button.find("i").removeClass("fa-solid").addClass("fa-regular");
        } else {
            button.addClass("liked");
            button.find("i").removeClass("fa-regular").addClass("fa-solid");
        }

        // AJAX call to update the like state
        const eventId = button.data("event-id");
        const eventPostId = button.data("event-post-id");
        $.ajax({
            url: base_url + "event_photo/userPostLikeDislike",
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            contentType: "application/json",
            data: JSON.stringify({
                event_id: eventId,
                event_post_id: eventPostId,
                reaction: reaction,
            }),
            success: function (response) {
                if (response.status === 1) {
                    $(`#likeCount_${eventPostId}`).text(
                        `${response.count} Likes`
                    );
                    $(".modal").on("hidden.bs.modal", function () {
                        $("#postContent").val("");
                        $("#pollForm")[0].reset(); // Reset poll form
                        $("#photoForm")[0].reset(); // Reset photo form
                        $("#imagePreview").empty(); // Clear image preview

                        // Add `d-none` class back to hide the div
                        $(".create-post-upload-img-inner").addClass("d-none");
                    });

                    $(".modal").on("shown.bs.modal", function () {
                        // Remove `d-none` class to show the div
                        $(".create-post-upload-img-inner").removeClass(
                            "d-none"
                        );
                    });
                } else {
                    alert(response.message);
                }
            },
            error: function (xhr) {
                console.error(xhr.responseText);
                alert("An error occurred. Please try again.");
            },
        });
    });

    $(document).on("click", "#emojiDropdown .emoji", function () {
        const selectedEmoji = $(this).data("emoji");
        const button = $(this)
            .closest(".photo-card-head-right")
            .find("#likeButton");
            console.log(selectedEmoji);

        // const emojiDisplay = button.find('#show_Emoji');

        // Replace heart icon with selected emoji
        // emojiDisplay.removeClass();
        // emojiDisplay.text(selectedEmoji);

        // AJAX call to update emoji reaction
        const eventId = button.data("event-id");
        const eventPostId = button.data("event-post-id");
        $.ajax({
            url: base_url + "event_photo/userPostLikeDislike",
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            contentType: "application/json",
            data: JSON.stringify({
                event_id: eventId,
                event_post_id: eventPostId,
                reaction: selectedEmoji,
            }),
            success: function (response) {
                if (response.status === 1) {
                    let reactionImageHtml = "";
                    if (response.is_reaction == "1") {
                        // ✅ User has liked the post, update the reaction image
                        console.log("Like given, updating reaction image...");
                        if (reactionIcons[selectedEmoji]) {
                            reactionImageHtml = `<img src="${reactionIcons[selectedEmoji]}" alt="Reaction Emoji">`;
                        }
                        button.addClass("liked"); // Add liked class
                    } else {
                        // ✅ User has removed like, set the first reaction from response
                        console.log(
                            "Like removed, updating first available reaction..."
                        );
                        if (response.reactionList.length > 0) {
                            let firstReaction = response.reactionList[0];
                            if (firstReaction.startsWith("\\u{")) {
                                firstReaction = String.fromCodePoint(
                                    parseInt(
                                        firstReaction.replace(/\\u{|}/g, ""),
                                        16
                                    )
                                );
                            }
                            if (reactionIcons[firstReaction]) {
                                reactionImageHtml = `<img src="${reactionIcons[firstReaction]}" alt="Reaction Emoji">`;
                            } else {
                                console.log({ firstReaction });
                                console.log(reactionIcons[firstReaction]);
                                //let reaction = "\u{2764}";
                                reactionImageHtml = `<img src="${reactionIcons[selectedEmoji]}" alt="Reaction Emoji">`;
                            }
                        }
                        button.removeClass("liked"); // Remove liked class
                        button.html(
                            '<i class="fa-regular fa-heart" id="show_Emoji"></i>'
                        ); // Reset button to default
                    }

                    // ✅ Update the reaction image container
                    $(`#reactionImage_${eventPostId}`).html(reactionImageHtml);

                    // ✅ Update like count
                    $(`#likeCount_${eventPostId}`).text(
                        `${response.count} Likes`
                    );
                } else {
                    alert(response.message);
                }
            },
            error: function (xhr) {
                console.error(xhr.responseText);
                alert("An error occurred. Please try again.");
            },
        });

        // Hide emoji picker
        $(this).closest("#emojiDropdown").hide();
    });
});

// Convert emoji character to Unicode escape sequence
function getEmojiUnicode(emoji) {
    switch (emoji) {
        case "❤️":
            return "\u{2764}"; // Heart
        case "😍":
            return "\u{1F60D}"; // Smiling face with heart-eyes
        case "👍":
            return "\u{1F44D}"; // Thumbs up
        case "😂":
            return "\u{1F602}"; // Face with tears of joy
        case "😢":
            return "\u{1F622}"; // Crying face
        default:
            return emoji; // Return as is if not found
    }
}

// Hide emoji picker when clicking outside the post area
$(document).on("click", function (e) {
    if (!$(e.target).closest(".photo-card-head-right").length) {
        $(".photos-likes-options-wrp").hide(); // Hide emoji picker when clicked outside
    }
});

// Hide emoji picker when clicking outside
$(document).on("click", function (e) {
    if (!$(e.target).closest("#likeButton, #emojiDropdown").length) {
        $("#emojiDropdown").hide(); // Hide emoji picker when clicked outside
    }
});

$(document).on("click", function (e) {

    if (!$(e.target).closest(".posts-card-like-comment-right").length) {
        // alert();
        $(".photos-likes-options-wrp").hide(); // Hide emoji picker when clicked outside
    }
});

// Hide emoji picker when clicking outside

// pratik sir code
// $(document).on("click", function (e) {
//     if (!$(e.target).closest("#likeButtonModel, #emojiDropdown1 , #emojiDropdown").length) {
//         $("#emojiDropdown1").hide(); // Hide emoji picker when clicked outside
//         $("#emojiDropdown").hide(); // Hide emoji picker when clicked outside
//     }
// });

// end code


// alfez code

$(document).on('click','#likeButtonModel',function(){
    $("#emojiDropdown").show();
})
//

$(document).on("click", "#delete_post", function () {
    const button = $(this);
    const eventId = button.data("event-id");
    const eventPostId = button.data("event-post-id");

    $.ajax({
        url: base_url + "event_photo/deletePost", // Adjust base_url as necessary
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"), // Include CSRF token for security
        },
        contentType: "application/json", // Send as JSON
        data: JSON.stringify({
            event_id: eventId,
            event_post_id: eventPostId,
        }),
        success: function (response) {
            if (response.success) {
                // Remove the deleted post from the DOM
                button.closest(".delete_post_container").remove(); // Adjust the selector as per your HTML structure
                // setTimeout(function () {
                //     location.reload();
                // }, 2000);
                toastr.success("Event Post Deleted Successfully");
            } else {
                toastr.error("Event Post  Not Deleted");
            }
        },
        error: function (xhr) {
            console.error(xhr.responseText);
            alert("An error occurred. Please try again.");
        },
    });
});

$(document).on("click", ".comment-send-icon_old", function () {
    const commentInput = $("#post_comment");
    const commentText = commentInput.val().trim();
    const commentId = $("#parent_comment_id").val();
    var login_user_id = $("#login_user_id").val();
    const replyParentId = $(this)
        .closest(".reply-on-comment")
        .data("comment-id");

    alert(commentId);
    if (commentText === "") {
        alert("Please enter a comment");
        return;
    }

    const eventId = $(".likeModel").data("event-id"); // Or get this dynamically as needed
    const eventPostId = $(".likeModel").data("event-post-id");

    let url;
    let data = {
        comment: commentText,
        event_id: eventId,
        event_post_id: eventPostId,
    };

    // Check if it's a reply or a normal comment
    if (commentId) {
        url = base_url + "event_photo/userPostCommentReply"; // Reply URL
        data.parent_comment_id = commentId; // Add parent comment ID if replying
    } else {
        url = base_url + "event_photo/userPostComment"; // Normal comment URL
    }

    // Example AJAX request to submit the comment
    $.ajax({
        url: url,
        type: "POST",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        data: data,
        success: function (response) {
            if (response.success) {
                console.log(response.data);
                $("#post_comment").val(""); // Clear the input

                const data = response.data;
                const profileImage = data.profile
                    ? `<img src="${data.profile}" alt="Profile Image" class="profile-img">`
                    : generateProfileImage(data.username);

                function generateProfileImage(username) {
                    if (!username) return ""; // Return an empty string if the username is undefined

                    // Split the username into parts
                    const nameParts = username.split(" ");
                    const firstInitial = nameParts[0]?.[0]?.toUpperCase() || "";
                    const secondInitial =
                        nameParts[1]?.[0]?.toUpperCase() || "";
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
                                    <p>${data.location}</p>
                                </div>
                            </div>
                            <div class="posts-card-like-comment-right">
                                <p>${data.posttime}</p>
                                 <button class="posts-card-like-btn" id="CommentlikeButton" data-event-id="${eventId}" data-event-post-comment-id="${data.id}" data-user-id="${login_user_id}">
                                    <i class="fa-regular fa-heart"></i>
                                    </button>
                            </div>
                        </div>
                        <div class="commented-user-content">
                            <p>${data.comment}</p>
                        </div>
                        <div class="commented-user-reply-wrp">
                            <div class="position-relative d-flex align-items-center gap-2">
                                <button class="posts-card-like-btn"><i class="fa-regular fa-heart"></i></button>
                                <p id="commentTotalLike_${data.id}">
                                  0
                                    </p>
                            </div>
                            <button class="commented-user-reply-btn" data-comment-id="${data.id}">Reply</button>
                        </div>
                        <ul class="primary-comment-replies"></ul>
                    </li>
                `;
                if (!commentId) {
                    $(".posts-card-show-all-comments-inner ul").append(
                        newCommentHTML
                    );
                }

                if (data.comment_replies && data.comment_replies.length > 0) {
                    comment.comment_replies.forEach(function (reply) {
                        let displayName =
                            reply.profile ||
                            generatePlaceholderName(reply.username);
                        const replyHTML = `

                    <div class="commented-user-head">
                        <div class="commented-user-profile">
                            <div class="commented-user-profile-img">
                               ${displayName}
                            </div>
                            <div class="commented-user-profile-content">
                                <h3>${reply.username}</h3>
                                <p>${reply.location || ""}</p>
                            </div>
                        </div>
                        <div class="posts-card-like-comment-right">
                            <p>${reply.posttime || "Just now"}</p>
                            <button class="posts-card-like-btn"><i class="fa-regular fa-heart"></i></button>
                        </div>
                    </div>
                    <div class="commented-user-content">
                        <p>${reply.comment || "No content"}</p>
                    </div>
                    <div class="commented-user-reply-wrp">
                        <div class="position-relative d-flex align-items-center gap-2">
                            <button class="posts-card-like-btn"><i class="fa-regular fa-heart"></i></button>
                            <p>${reply.comment_total_likes || 0}</p>
                        </div>
                        <button class="commented-user-reply-btn">Reply</button>
                    </div>
                `;

                        const li = document.createElement("li");
                        li.className = "reply-on-comment";
                        li.setAttribute("data-comment-id", reply.id);
                        li.innerHTML = replyHTML; // Convert HTML string to actual HTML

                        // Find all existing comments
                        let comments =
                            document.getElementsByClassName("reply-on-comment");
                        console.log(comments);
                        // Convert HTMLCollection to an array and find the target comment
                        const comment = Array.from(comments).find(
                            (el) => el.dataset.commentId === parentCommentId
                        );

                        if (comment) {
                            console.log("Found comment:", comment);

                            // Find the previous sibling (the comment before this one)
                            let previousComment =
                                comment.previousElementSibling;
                            if (!previousComment) {
                                $(comment).parent().prepend(li);
                            }
                            // Loop until we find the nearest previous <ul> with class "primary-comment-replies"
                            while (previousComment) {
                                let parentUl = previousComment.closest(
                                    ".primary-comment-replies"
                                );
                                if (parentUl) {
                                    console.log("Found the ul:", parentUl);
                                    parentUl.prepend(li); // Append the new comment properly

                                    // 🔥 Update the comments list to include the newly added <li>
                                    comments =
                                        document.getElementsByClassName(
                                            "reply-on-comment"
                                        );

                                    console.log(
                                        "Updated comments list:",
                                        comments
                                    );
                                    break;
                                }
                                previousComment =
                                    previousComment.previousElementSibling;
                            }
                        } else {
                            let comments =
                                document.getElementsByClassName(
                                    "commented-user-wrp"
                                );
                            let comment = Array.from(comments).find((el) => {
                                console.log(el.dataset.commentId);
                                console.log(parentCommentId);
                                //  el.dataset.commentId ===
                                // parentCommentId
                                if (el.dataset.commentId == parentCommentId) {
                                    return el;
                                }
                            });
                            if (comment) {
                                console.log(comment);
                                const parentUl = $(comment).find(
                                    ".primary-comment-replies"
                                );
                                console.log(parentUl);
                                if (parentUl.length) {
                                    console.log(
                                        "Found primary-comment-replies under commented-user-wrp, prepending the new comment."
                                    );
                                    parentUl.prepend($(li)); // Insert new comment as the first <li> under the current comment's <ul>
                                    return;
                                }
                            }
                        }
                    });
                }
            }
        },
        error: function (xhr) {
            console.error(xhr.responseText);
            alert("An error occurred. Please try again.");
        },
    });
});
$(document).on("click", ".comment-send-icon", function () {
    const commentInput = $("#post_comment");
    const commentText = commentInput.val().trim();
    const commentId = $("#parent_comment_id").val();
    const parentCommentId = commentId;
    const replyParentId = $(this)
        .closest(".reply-on-comment")
        .data("comment-id");

    if (commentText === "") {
        alert("Please enter a comment");
        return;
    }

    const eventId = $(".likeModel").data("event-id"); // Or get this dynamically as needed
    const eventPostId = $(".likeModel").data("event-post-id");

    let url;
    let data = {
        comment: commentText,
        event_id: eventId,
        event_post_id: eventPostId,
    };

    // Check if it's a reply or a normal comment
    if (commentId) {
        url = base_url + "event_photo/userPostCommentReply"; // Reply URL
        data.parent_comment_id = commentId; // Add parent comment ID if replying
    } else {
        url = base_url + "event_photo/userPostComment"; // Normal comment URL
    }

    // Example AJAX request to submit the comment
    $.ajax({
        url: url,
        type: "POST",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        data: data,
        success: function (response) {
            if (response.success) {
                const data = response.data;
                console.log(data);
                // Generate profile image or initials
                const profileImage = data.profile
                    ? `<img src="${data.profile}" alt="Profile Image" class="profile-img">`
                    : generateProfileImage(data.username);

                function generateProfileImage(username) {
                    if (!username) return ""; // Return an empty string if the username is undefined

                    // Split the username into parts
                    const nameParts = username.split(" ");
                    const firstInitial = nameParts[0]?.[0]?.toUpperCase() || "";
                    const secondInitial =
                        nameParts[1]?.[0]?.toUpperCase() || "";
                    const initials = `${firstInitial}${secondInitial}`;

                    // Generate a font color class based on the first initial
                    const fontColor = `fontcolor${firstInitial}`;
                    return `<h5 class="${fontColor} font_name">${initials}</h5>`;
                }
                // $(".posts-card-like-btn").on("click", function () {
                //     const icon = this.querySelector("i");
                //     icon.classList.toggle("fa-regular");
                //     icon.classList.toggle("fa-solid");
                // });
                const newCommentHTML = `
                                <div class="commented-user-head">
                                <div class="commented-user-profile">
                                    <div class="commented-user-profile-img"> ${profileImage} </div>
                                    <div class="commented-user-profile-content">
                                    <h3>${data.username}</h3>
                                    <p>${data.location || ""}</p>
                                    </div>
                                </div>
                                <div class="posts-card-like-comment-right">
                                    <p>${data.posttime}</p>
                                    <button class="posts-card-like-btn" id="CommentlikeButton" data-event-id="${eventId}" data-event-post-comment-id="${
                    data.id
                }" data-user-id="1">
                                    <i class="fa-regular fa-heart"></i>
                                    </button>
                                </div>
                                </div>
                                <div class="commented-user-content">
                                <p>${data.comment}</p>
                                </div>
                                <div class="commented-user-reply-wrp">
                                <div class="position-relative d-flex align-items-center gap-2">
                                    <button class="posts-card-like-btn" id="CommentlikeButton" data-event-id="${eventId}" data-event-post-comment-id="${
                    data.id
                }" data-user-id="1">
                                    <i class="fa-regular fa-heart" id="show_Emoji"></i>
                                    </button>
                                    <p id="commentTotalLike_${data.id}">
                                  1
                                    </p>
                                </div>
                                <button data-comment-id="${
                                    data.id
                                }" class="commented-user-reply-btn">Reply</button>
                                </div>

                `;
                var replyList;
                if (parentCommentId) {
                    const li = document.createElement("li");
                    li.className = "reply-on-comment";
                    li.setAttribute("data-comment-id", data.id);
                    li.innerHTML = newCommentHTML; // Convert HTML string to actual HTML

                    // Find all existing comments
                    let comments =
                        document.getElementsByClassName("reply-on-comment");
                    console.log(comments);
                    // Convert HTMLCollection to an array and find the target comment
                    const comment = Array.from(comments).find(
                        (el) => el.dataset.commentId === parentCommentId
                    );
                    console.log(comment);

                    if (comment) {
                        console.log("Found comment:", comment);

                        // Find the previous sibling (the comment before this one)
                        let previousComment = comment.previousElementSibling;
                        if (!previousComment) {
                            $(comment).parent().prepend(li);
                        }
                        // Loop until we find the nearest previous <ul> with class "primary-comment-replies"
                        while (previousComment) {
                            let parentUl = previousComment.closest(
                                ".primary-comment-replies"
                            );
                            if (parentUl) {
                                console.log("Found the ul:", parentUl);
                                parentUl.prepend(li); // Append the new comment properly

                                // 🔥 Update the comments list to include the newly added <li>
                                comments =
                                    document.getElementsByClassName(
                                        "reply-on-comment"
                                    );

                                console.log("Updated comments list:", comments);
                                break;
                            }
                            previousComment =
                                previousComment.previousElementSibling;
                        }
                    } else {
                        let comments =
                            document.getElementsByClassName(
                                "commented-user-wrp"
                            );
                        let comment = Array.from(comments).find(
                            (el) => el.dataset.commentId === parentCommentId
                        );
                        if (comment) {
                            console.log(parentCommentId);
                            console.log(comment);

                            const parentUl = $(comment).find(
                                ".primary-comment-replies"
                            );
                            if (parentUl.length) {
                                console.log(
                                    "Found primary-comment-replies under commented-user-wrp, prepending the new comment."
                                );
                                parentUl.prepend($(li)); // Insert new comment as the first <li> under the current comment's <ul>
                                return;
                            }
                        }
                    }
                } else {
                    const li = `<li class="commented-user-wrp" data-comment-id="${data.id}">
                        ${newCommentHTML}
                        <ul class="primary-comment-replies"></ul>
                </li>`;
                    // Append as a new top-level comment
                    const commentList = $(
                        `.posts-card-show-all-comments-wrp`
                    ).find(".top-level-comments");

                    // Check if the comment is already appended
                    if (
                        commentList.find(
                            `li[data-comment-id="${data.comment_id}"]`
                        )
                    ) {
                        commentList.prepend(li);
                        // commentList.append(newCommentHTML);
                    }
                }

                // Handle replies if any are provided in the response
                if (data.comment_replies && data.comment_replies.length > 0) {
                    data.comment_replies.forEach(function (reply) {
                        const replyHTML = `
                        <li class="reply-on-comment" data-comment-id="${
                            reply.id
                        }">
                            <div class="commented-user-head">
                                <div class="commented-user-profile">
                                    <div class="commented-user-profile-img">
                                        <img src="${
                                            reply.profile || "default-image.png"
                                        }" alt="">
                                    </div>
                                    <div class="commented-user-profile-content">
                                        <h3>${reply.username}</h3>
                                        <p>${reply.location || ""}</p>
                                    </div>
                                </div>
                                <div class="posts-card-like-comment-right">
                                    <p>${reply.posttime || "Just now"}</p>
                                    <button class="posts-card-like-btn"><i class="fa-regular fa-heart"></i></button>
                                </div>
                            </div>
                            <div class="commented-user-content">
                                <p>${reply.comment || "No content"}</p>
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

                const commentCountElement = $(`#comment_${eventPostId}`);
                const currentCount = parseInt(commentCountElement.text()) || 0;
                commentCountElement.text(`${currentCount + 1} Comments`);

                // Clear input field
                commentInput.val("");
                $("#parent_comment_id").val(""); // Reset parent comment ID

                let comments =
                    document.getElementsByClassName("commented-user-wrp");
                $("#comments").html(comments.length + " comments");
            }
        },
        error: function (xhr) {
            console.error(xhr.responseText);
            alert("An error occurred. Please try again.");
        },
    });
});

// $(document).on("click", ".posts-card-like-btn", function () {
//     const icon = this.querySelector("i");
//     icon.classList.toggle("fa-regular");
//     icon.classList.toggle("fa-solid");
// });
$(document).on("click", ".commented-user-reply-btn", function () {
    // Find the closest comment element

    $(".post_comment").val("");

    const parentName = $(this)
        .parent()
        .prev()
        .prev()
        .children()
        .find(".commented-user-profile-content")
        .find("h3")
        .text()
        .trim();
    console.log({ parentName });
    const parentId = $(this).data("comment-id");

    if (!parentId) {
        console.error("Parent Comment ID is missing!");
        return;
    }

    // Set the parent comment ID value in the hidden field for later use in the AJAX request
    $("#parent_comment_id").val(parentId); // Store parent comment ID in a hidden field

    // Set the active class on the currently selected comment
    $(".commented-user-wrp").removeClass("active"); // Remove 'active' from all comments
    $(this).closest(".commented-user-wrp").addClass("active"); // Add 'active' to the current comment

    // Focus the comment box and insert the '@username'
    const commentBox = $("#post_comment");
    if (!commentBox.length) {
        console.error("Comment input field not found!");
        return;
    }

    // Insert the '@username' into the comment box and focus
    commentBox.val(`@${parentName} `).focus();
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
const longPressDelay = 3000; // 3 seconds for long press
let pressTimer;
let isLongPress = false;

// Function to handle the long press action
function handleLongPress(element) {
    console.log("Long press detected");
    // $('#detail-photo-modal').hide();
    // Show the button and check the checkbox
    const photoCard = element.closest(".photo-card-photos-wrp");
    photoCard.find(".selected-photo-btn").show();
    photoCard.find(".form-check-input").prop("checked", true);

    // Check if any checkboxes are selected and toggle the visibility of the bulk select wrapper
    toggleBulkSelectWrapper();
}

// Function to toggle visibility of the bulk-select-photo-wrp
function toggleBulkSelectWrapper() {
    const selectedCount = $(".selected_image:checked").length; // Count selected checkboxes
    const bulkSelectWrapper = $(
        ".phototab-add-new-photos-wrp.bulk-select-photo-wrp"
    );
    console.log(selectedCount);

    if (selectedCount >= 2) {
        bulkSelectWrapper.removeClass("d-none"); // Show the div
        bulkSelectWrapper
            .find(".phototab-add-new-photos-img p")
            .text(`${selectedCount} Photos Selected`); // Update the count
    } else if (selectedCount <= 1) {
        // bulkSelectWrapper.addClass('d-none');
    }

    // Remove the div if more than 1 image is selected
    // if (selectedCount > 1) {
    //     bulkSelectWrapper.addClass('d-none'); // Hide the div when more than 1 image is selected
    // }
}

// Mouse down event
$(".img_click").on("mousedown", function (e) {
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
$(".form-check-input").on("change", function () {
    toggleBulkSelectWrapper();
});

$(".download_img").on("click", function () {
    // $('.form-check-input:checked').each(function () {
    //     console.log('Checkbox selected: ', $(this).data('image-src')); // Check if data-image-src exists
    // });

    // Get selected image URLs from the checkboxes
    const selectedImages = $(".selected_image:checked")
        .map(function () {
            return $(this).data("image-src"); // Get image URLs
        })
        .get();

    console.log("Selected Images: ", selectedImages);
});
$(document).on("click", ".download_img_single", function () {
    // Find the image source stored in the data attribute
    const imgSrc = $(".downloadImg").data("img-src");
    console.log(imgSrc);

    if (imgSrc) {
        // Create an invisible anchor tag to trigger the download
        const downloadLink = document.createElement("a");
        downloadLink.href = imgSrc;
        downloadLink.download = ""; // Optionally, specify the download filename here
        downloadLink.click(); // Trigger the click event to start the download
    } else {
        alert("Image source not found.");
    }
});

let reactionIcons = {
    "❤️": base_url + "assets/front/img/heart-emoji.png", // ❤️
    "\\u{2764}": base_url + "assets/front/img/heart-emoji.png", // ❤️
    "👍": base_url + "assets/front/img/thumb-icon.png", // 👍
    "\\u{1F44D}": base_url + "assets/front/img/thumb-icon.png", // 👍
    "\\u{1F604}": base_url + "assets/front/img/smily-emoji.png", // 😄
    "\\u{1F44F}": base_url + "assets/front/img/smily-emoji.png", // 😄
    "😊": base_url + "assets/front/img/smily-emoji.png", // 😄
    "\\u{1F60D}": base_url + "assets/front/img/eye-heart-emoji.png", // 😍
    "😍": base_url + "assets/front/img/eye-heart-emoji.png", // 😍
    "\\u{1F44F}": base_url + "assets/front/img/clap-icon.png", // 👏
    "👏": base_url + "assets/front/img/clap-icon.png", // 👏
};

$(document).on("click", ".open_photo_model", function () {
    clearTimeout(pressTimer); // Clear the timer
    console.log("Mouse up or leave detected");
    const commentInput = $("#post_comment");
    commentInput.val("");
    if (!isLongPress) {
        // If it wasn't a long press, open the modal (short press behavior)
        console.log("Short press detected");
        $("#detail-photo-modal").modal("show");
    } // Open the modal
    // Fetch the post ID from the data attribute
    var login_user_id = $("#login_user_id").val();
    const postId = $(this).data("post-id");
    const eventId = $(this).data("event-id");
    const rawData = $(this).data("image"); // Get raw data
    console.log("Raw Data:", rawData); // Debug the raw data
    const swiperWrapper = $("#media_post");
    swiperWrapper.empty();
    if (rawData && rawData.length > 0) {
        rawData.forEach((media) => {
            let mediaElement = "";

            if (media.match(/\.(mp4|webm|ogg)$/i)) {
                // If it's a video, use <video> tag
                mediaElement = `
                    <div class="swiper-slide">
                        <div class="posts-card-show-post-img">
                            <video controls>
                                <source src="${media}" type="video/mp4" muted>
                                Your browser does not support the video tag.
                            </video>
                        </div>
                    </div>
                `;
            } else {
                // Otherwise, treat it as an image
                mediaElement = `
                    <div class="swiper-slide">
                        <div class="posts-card-show-post-img">
                            <img src="${media}" alt="Media"  />
                        </div>
                    </div>
                `;
            }

            swiperWrapper.append(mediaElement);
        });
    }
    swiper.destroy(true, true);
    console.log(rawData.length);

    if (rawData.length > 1) {
        swiperWrapper.removeClass("hideswipe");

        swiper.destroy(true, true);
        document.getElementsByClassName("swiper-button-next")[0].style.display =
            "flex";
        document.getElementsByClassName("swiper-button-prev")[0].style.display =
            "flex";
        swiper = new Swiper(".photo-detail-slider", {
            slidesPerView: 1,
            spaceBetween: 30,
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
        });
    } else {
        swiperWrapper.addClass("hideswipe");
        swiper.destroy(true, true);
        document.getElementsByClassName("swiper-button-next")[0].style.display =
            "none";
        document.getElementsByClassName("swiper-button-prev")[0].style.display =
            "none";
        swiper = new Swiper(".photo-detail-slider", {
            slidesPerView: 1,
            spaceBetween: 30,

            loop: false, // 🔹 Ensure looping is disabled
        });
    }
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
        type: "POST", // Use GET or POST depending on your API
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        data: { id: postId, event_id: eventId },
        success: function (response) {
            const dataArray = response.data; // This is an array
            console.log(response);

            if (Array.isArray(dataArray) && dataArray.length > 0) {
                const data = dataArray[0]; // Access the first object in the array

                // Profile Image

                const profileImage =
                    data.profile ||
                    generateProfileImage(data.firstname, data.lastname);
                console.log("Profile Image URL:", profileImage);
                // Check if profileImage is an image URL or HTML content
                if (
                    profileImage.startsWith("http") ||
                    profileImage.startsWith("data:image")
                ) {
                    // If it's a valid image URL, set it as the src of the image tag
                    $(".posts-card-head-left-img").html(
                        `<img src="${profileImage}" alt="Profile Image">`
                    );
                } else {
                    // If it's a placeholder (HTML content), insert it directly inside the div
                    $(".posts-card-head-left-img").html(profileImage);
                }

                const post = {
                    id: postId,
                    reactionList: data.reactionList,
                    self_reaction: data.self_reaction,
                    total_likes: data.total_likes
                };

                function generateProfileImage(firstname, lastname) {
                    const firstInitial = firstname
                        ? firstname[0].toUpperCase()
                        : "";
                    const secondInitial = lastname
                        ? lastname[0].toUpperCase()
                        : "";
                    const initials = `${firstInitial}${secondInitial}`;
                    const fontColor = `fontcolor${firstInitial}`;

                    // Return initials inside an h5 tag with dynamic styling
                    return `<h5 class="${fontColor} font_name">${initials}</h5>`;
                }
                // Host Label Condition
                if (data.is_host == "1") {
                    const host = `${data.is_host}`;
                    $("#host_display").text("Host");
                }
                if (data.is_co_host == "1") {
                    const co_host = `${data.is_co_host}`;
                    $("#host_display").text("co_host");
                }

                $(".likeModel")
                    .data("event-id", data.event_id)
                    .data("event-post-id", data.id);
                // Name
                const fullName = `${data.firstname} ${data.lastname}`;
                $("#post_name").text(fullName);

                // Location
                const location =
                    data.location.trim() !== "" ? data.location : "";
                $("#location").text(location);

                // Post Message
                $("#post_message").text(data.post_message);
                $("#post_time_details").text(data.post_time);

                const reactionVal = data.reactionList.forEach((that) => {

                });

                // $("#likeCount").text(data.total_likes + " Likes");
                // Add 'Likes' after the number
                $("#comments").text(data.total_comments + " Comments");

                console.log("Self Reaction:", data.self_reaction); // Debugging
                console.log(typeof data.self_reaction); // Output: string

                var reaction_store = data.self_reaction.trim();



                console.log(reaction_store);



                let reactionImageHtml = $('#likeButtonModel');
                console.log(reactionImageHtml);

                if (reactionIcons[reaction_store]) {
console.log(reactionIcons[reaction_store]);

                    reactionImageHtml = `<img src="${reactionIcons[reaction_store]}" alt="">`;
                }
                $(`#likeButtonModel`).html(reactionImageHtml);
               let reaction_list = response.reactionList;
            //    reaction_list.each(function () {

            //     $(`#reactionImage`).html(reactionIcons[reaction_store]);
            //    });

            document.getElementById("postCardEmoji").innerHTML = renderReactions(post);
                // Update the emoji list based on the reaction
                const reactionList = $(".posts-card-like-comment-left ul");


                reactionList.find("li").each(function () {
                    const img = $(this).find("img");
                    if (img.length) {
                        const emojiSrc = img.attr("src");
                        console.log("Reaction Store:", reaction_store);
                        console.log("Emoji Src:", emojiSrc);

                        // Define emojis with exact matching Unicode and image source
                        const heartUnicode = "\u{2764}"; //
                        const smileUnicode = "\u{1F60D}"; //
                        const clapUnicode = "\u{1F44F}"; //

                        $(this).removeClass("photo_emoji").show();

                        // Hide and select the correct emoji based on the reaction_store
                        if (
                            reaction_store === heartUnicode &&
                            emojiSrc.includes("heart-emoji.png")
                        ) {
                            console.log("Heart emoji photo_emoji");
                            $(this).addClass("photo_emoji");
                        } else if (
                            reaction_store === smileUnicode &&
                            emojiSrc.includes("smily-emoji.png")
                        ) {
                            console.log("Smile emoji photo_emoji");
                            $(this).addClass("photo_emoji");
                        } else if (
                            reaction_store === clapUnicode &&
                            emojiSrc.includes("clap-icon.png")
                        ) {
                            console.log("Clap emoji photo_emoji");
                            $(this).addClass("photo_emoji");
                        } else {
                            $(this).hide(); // Hide non-matching emojis
                            console.log("No matching emoji found");
                        }
                    } else {
                        console.log("No img tag found in this li element.");
                    }
                });

                // Make sure you update the reactions after filtering them
                updateReactions(
                    data.reactionList,
                    data.firstname,
                    data.lastname,
                    data.profile,
                    data.location
                );

                const commentsWrapper = $(
                    ".posts-card-show-all-comments-inner ul"
                );
                commentsWrapper.empty(); // Clear existing comments

                if (data.latest_comment && Array.isArray(data.latest_comment)) {
                    data.latest_comment.forEach((comment) => {
                        let parentCommentId = comment.id;
                        let displayName = comment.profile
                            ? `<img src="${comment.profile}" alt="User Profile" class="profile-image">`
                            : generatePlaceholderName(comment.username);

                        commentsWrapper.append(`
                            <li class="commented-user-wrp" data-comment-id="${
                                comment.id
                            }">

                                <div class="commented-user-head">
                                    <div class="commented-user-profile">
                                        <div class="commented-user-profile-img">
                                        ${displayName}
                                        </div>
                                        <div class="commented-user-profile-content">
                                            <h3>${comment.username || ""}</h3>
                                            <p>${comment.location || ""}</p>
                                        </div>
                                    </div>
                                    <div class="posts-card-like-comment-right">
                                        <p>${comment.posttime || ""}</p>
                                        <button class="posts-card-like-btn">
                                            <i class="fa-regular fa-heart"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="commented-user-content">
                                    <p>${comment.comment || ""}</p>
                                </div>
                                <div class="commented-user-reply-wrp">
                                    <div class="position-relative d-flex align-items-center gap-2">
                                         <button class="posts-card-like-btn" id="CommentlikeButton" data-event-id="${eventId}" data-event-post-comment-id="${
                            comment.id
                        }" data-user-id="${login_user_id}">
                                    <i class="fa-regular fa-heart"></i>
                                    </button>
                                        <p id="commentTotalLike_${
                                            comment.id
                                        }">${
                            comment.comment_total_likes || 0
                        }</p>
                                    </div>
                                    <button class="commented-user-reply-btn" data-comment-id="${
                                        comment.id
                                    }">Reply</button>
                                </div>
 <ul class="primary-comment-replies"></ul>
                            </li>

                        `);

                        if (
                            comment.comment_replies &&
                            comment.comment_replies.length > 0
                        ) {
                            comment.comment_replies.forEach(function (reply) {
                                let displayName = reply.profile
                                    ? `<img src="${reply.profile}" alt="User Profile" class="profile-image">`
                                    : generatePlaceholderName(reply.username);
                                const replyHTML = `

                            <div class="commented-user-head">
                                <div class="commented-user-profile">
                                    <div class="commented-user-profile-img">
                                       ${displayName}
                                    </div>
                                    <div class="commented-user-profile-content">
                                        <h3>${reply.username}</h3>
                                        <p>${reply.location || ""}</p>
                                    </div>
                                </div>
                                <div class="posts-card-like-comment-right">
                                    <p>${reply.posttime || "Just now"}</p>
                                    <button class="posts-card-like-btn"><i class="fa-regular fa-heart"></i></button>
                                </div>
                            </div>
                            <div class="commented-user-content">
                                <p>${reply.comment || "No content"}</p>
                            </div>
                            <div class="commented-user-reply-wrp">
                                <div class="position-relative d-flex align-items-center gap-2">
                                    <button class="posts-card-like-btn"><i class="fa-regular fa-heart"></i></button>
                                    <p>${reply.comment_total_likes || 0}</p>
                                </div>
                                <button class="commented-user-reply-btn" data-comment-id="${
                                    reply.id
                                }">Reply</button>
                            </div>
                        `;

                                const li = document.createElement("li");
                                li.className = "reply-on-comment";
                                li.setAttribute("data-comment-id", reply.id);
                                li.innerHTML = replyHTML; // Convert HTML string to actual HTML

                                // Find all existing comments
                                let comments =
                                    document.getElementsByClassName(
                                        "reply-on-comment"
                                    );
                                console.log(comments);
                                // Convert HTMLCollection to an array and find the target comment
                                const comment = Array.from(comments).find(
                                    (el) =>
                                        el.dataset.commentId === parentCommentId
                                );

                                if (comment) {
                                    console.log("Found comment:", comment);

                                    // Find the previous sibling (the comment before this one)
                                    let previousComment =
                                        comment.previousElementSibling;
                                    if (!previousComment) {
                                        $(comment).parent().prepend(li);
                                    }
                                    // Loop until we find the nearest previous <ul> with class "primary-comment-replies"
                                    while (previousComment) {
                                        let parentUl = previousComment.closest(
                                            ".primary-comment-replies"
                                        );
                                        if (parentUl) {
                                            console.log(
                                                "Found the ul:",
                                                parentUl
                                            );
                                            parentUl.prepend(li); // Append the new comment properly

                                            // 🔥 Update the comments list to include the newly added <li>
                                            comments =
                                                document.getElementsByClassName(
                                                    "reply-on-comment"
                                                );

                                            console.log(
                                                "Updated comments list:",
                                                comments
                                            );
                                            break;
                                        }
                                        previousComment =
                                            previousComment.previousElementSibling;
                                    }
                                } else {
                                    let comments =
                                        document.getElementsByClassName(
                                            "commented-user-wrp"
                                        );
                                    let comment = Array.from(comments).find(
                                        (el) => {
                                            console.log(el.dataset.commentId);
                                            console.log(parentCommentId);
                                            //  el.dataset.commentId ===
                                            // parentCommentId
                                            if (
                                                el.dataset.commentId ==
                                                parentCommentId
                                            ) {
                                                return el;
                                            }
                                        }
                                    );
                                    if (comment) {
                                        console.log(comment);
                                        const parentUl = $(comment).find(
                                            ".primary-comment-replies"
                                        );
                                        console.log(parentUl);
                                        if (parentUl.length) {
                                            console.log(
                                                "Found primary-comment-replies under commented-user-wrp, prepending the new comment."
                                            );
                                            parentUl.prepend($(li)); // Insert new comment as the first <li> under the current comment's <ul>
                                            return;
                                        }
                                    }
                                }
                            });
                        }
                    });
                }
                function generatePlaceholderName(username) {
                    const nameParts = username.split(" ");
                    const firstInitial = nameParts[0]?.[0]?.toUpperCase() || "";
                    const secondInitial =
                        nameParts[1]?.[0]?.toUpperCase() || "";
                    const initials = `${firstInitial}${secondInitial}`;
                    const fontColor = `fontcolor${firstInitial}`;
                    // Return initials inside an h5 tag with dynamic styling
                    return `<h5 class="${fontColor} font_name">${initials}</h5>`;
                }
            } else {
                console.log("No data found in the array.");
            }
        },
    });

    function updateReactions(
        reactions,
        firstname,
        lastname,
        profile,
        location
    ) {
        console.log(reactions); // Debug the reactions array
        console.log(firstname);
        console.log(lastname);

        const emojiPaths = {
            heart: "/assets/front/img/heart-emoji.png",
            thumb: "/assets/front/img/thumb-icon.png",
            smily: "/assets/front/img/smily-emoji.png",
            "eye-heart": "/assets/front/img/eye-heart-emoji.png",
            clap: "/assets/front/img/clap-icon.png",
        };

        const allReactionsList = $("#nav-all-reaction ul");
        const heartReactionsList = $("#nav-heart-reaction ul");
        const thumbReactionsList = $("#nav-thumb-reaction ul");
        const smilyReactionsList = $("#nav-smily-reaction ul");
        const eyeHeartReactionsList = $("#nav-eye-heart-reaction ul");
        const clapReactionsList = $("#nav-clap-reaction ul");

        const reactionCounts = {
            heart: 0,
            thumb: 0,
            smily: 0,
            "eye-heart": 0,
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
            if (profile && profile !== "") {
                return `<img src="${profile}" alt="">`;
            } else {
                const firstInitial = firstname
                    ? firstname[0].toUpperCase()
                    : "";
                const secondInitial = lastname ? lastname[0].toUpperCase() : "";
                const initials = `${firstInitial}${secondInitial}`;
                const fontColor = `fontcolor${firstInitial}`;
                return `<h5 class="${fontColor}">${initials}</h5>`;
            }
        };
        // Iterate through reactions array
        reactions.forEach((reaction) => {
            let reactionType = "";
            let emojiSrc = "";

            // Map each reaction to a type
            switch (reaction) {
                case "\\u{2764}": // Heart
                    reactionType = "heart";
                    break;
                case "\\u{1F44D}": // Thumbs Up
                    reactionType = "thumb";
                    break;
                case "\\u{1F604}": // Smiley
                    reactionType = "smily";
                    break;
                case "\\u{1F60D}": // Eye-Heart
                    reactionType = "eye-heart";
                    break;
                case "\\u{1F44F}": // Clap
                    reactionType = "clap";
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
                                                <p></p>

                                            </div>
                                        </div>
                                        <div class="posts-card-like-comment-right reaction-profile-reaction-img">
                                            <img src="${emojiSrc}" alt="">
                                        </div>
                                    </div>
                                  </li>`;

            // Append to specific reaction list
            if (reactionType === "heart") {
                heartReactionsList.append(reactionItem);
            } else if (reactionType === "thumb") {
                thumbReactionsList.append(reactionItem);
            } else if (reactionType === "smily") {
                smilyReactionsList.append(reactionItem);
            } else if (reactionType === "eye-heart") {
                eyeHeartReactionsList.append(reactionItem);
            } else if (reactionType === "clap") {
                clapReactionsList.append(reactionItem);
            }

            // Append the same item to "All Reactions" list
            console.log("Appending to All Reactions:", reactionItem);
            allReactionsList.append(reactionItem);
        });

        // Update the counts in the navigation tabs
        const totalReactions = Object.values(reactionCounts).reduce(
            (sum, count) => sum + count,
            0
        );
        $("#nav-all-reaction-tab").html(`All ${totalReactions}`);
        $("#nav-heart-reaction-tab").html(
            `<img src="${emojiPaths["heart"]}" alt=""> ${reactionCounts.heart}`
        );
        $("#nav-thumb-reaction-tab").html(
            `<img src="${emojiPaths["thumb"]}" alt=""> ${reactionCounts.thumb}`
        );
        $("#nav-smily-reaction-tab").html(
            `<img src="${emojiPaths["smily"]}" alt=""> ${reactionCounts.smily}`
        );
        $("#nav-eye-heart-reaction-tab").html(
            `<img src="${emojiPaths["eye-heart"]}" alt=""> ${reactionCounts["eye-heart"]}`
        );
        $("#nav-clap-reaction-tab").html(
            `<img src="${emojiPaths["clap"]}" alt=""> ${reactionCounts.clap}`
        );
    }
});

let longPressTimers;
let isLong_press = false;




$(document).on("click", "#emojiDropdown1 .model_emoji", function () {
    const selectedEmoji = $(this).data("emoji");
    const button = $(this).closest(".emoji_set").find("#likeButton");
    const emojiDisplay = button.find("#show_comment_emoji");

    // Replace heart icon with selected emoji
    emojiDisplay.removeClass();
    emojiDisplay.text(selectedEmoji);

    // AJAX call to update emoji reaction
    const eventId = button.data("event-id");
    const eventPostId = button.data("event-post-id");
    console.log(eventPostId);
    $.ajax({
        url: base_url + "event_photo/userPostLikeDislike",
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        contentType: "application/json",
        data: JSON.stringify({
            event_id: eventId,
            event_post_id: eventPostId,
            reaction: selectedEmoji,
        }),
        success: function (response) {
            if (response.status === 1) {
                $(`#likeCount_${eventPostId}`).text(`${response.count} Likes`);
            } else {
                alert(response.message);
            }
        },
        error: function (xhr) {
            console.error(xhr.responseText);
            alert("An error occurred. Please try again.");
        },
    });

    // Hide emoji picker
    $(this).closest("#emojiDropdown1").hide();
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
    let savedVisibility = localStorage.getItem("post_privacys") || "1"; // Default: Everyone
    let savedAllowComments = localStorage.getItem("commenting_on_off") === "1"; // Convert to boolean

    // Ensure the default value is set if no saved value exists for comments
    if (savedAllowComments !== true) {
        savedAllowComments = "1"; // Default to true
        localStorage.setItem("commenting_on_off", savedAllowComments);
    }

    // Apply settings to the form
    const visibilityRadio = $(
        'input[name="post_privacy"][value="' + savedVisibility + '"]'
    );
    if (visibilityRadio.length > 0) {
        visibilityRadio.prop("checked", true);
    } else {
        // Fallback to default visibility if saved value is invalid
        savedVisibility = "1";
        $('input[name="post_privacy"][value="1"]').prop("checked", true);
    }

    $("#allowComments").prop("checked", savedAllowComments);

    // Update the hidden input fields dynamically
    $(".hiddenVisibility").val(savedVisibility);
    $(".hiddenAllowComments").val(savedAllowComments ? "1" : "0");

    // Update the display area to show the current saved visibility and commenting status
    const visibilityName = visibilityOptions[savedVisibility];
    $("#savedSettingsDisplay").html(`
        <h4>${visibilityName} <i class="fa-solid fa-angle-down"></i></h4>
        <p>${savedAllowComments === "1" ? "" : ""}</p>
    `);

    // Save Button Click Handler
    $("#saveSettings").on("click", function () {
        // Fetch selected visibility
        const visibility = $('input[name="post_privacy"]:checked').val() || "1"; // Default to Everyone if null
        // Fetch commenting status
        const allowComments = $("#allowComments").is(":checked") ? "1" : "0";

        // Save settings to localStorage
        localStorage.setItem("post_privacys", visibility);
        localStorage.setItem("commenting_on_off", allowComments);

        // Update the hidden input fields dynamically for all forms
        $(".hiddenVisibility").val(visibility);
        $(".hiddenAllowComments").val(allowComments);

        // Update display area
        const visibilityName = visibilityOptions[visibility];
        $("#savedSettingsDisplay").html(`
            <h4>${visibilityName} <i class="fa-solid fa-angle-down"></i></h4>
            <p>${allowComments === "1" ? "" : ""}</p>
        `);

        console.log("Saved Settings:", { visibility, allowComments });
    });

    // Dynamically set the hidden values in the forms
    $("form").on("submit", function () {
        // Fetch the visibility and commenting status to update the form's hidden inputs before submission
        const visibility = $('input[name="post_privacy"]:checked').val() || "1"; // Default to Everyone if null
        const allowComments = $("#allowComments").is(":checked") ? "1" : "0";

        // Dynamically update hidden inputs in the respective forms
        $("#hiddenVisibility").val(visibility);
        $("#hiddenAllowComments").val(allowComments);
    });
});
$(".modal").on("hidden.bs.modal", function () {
    $("#photoForm")[0].reset(); // Reset photo form
    $("#imagePreview").empty(); // Clear image preview

    // Add `d-none` class back to hide the div
    $(".create-post-upload-img-inner").addClass("d-none");
});
$(".modal").on("shown.bs.modal", function () {
    // Remove `d-none` class to show the div
    $(".create-post-upload-img-inner").removeClass("d-none");
});
$(document).on("click", "#CommentlikeButton", function () {
    const button = $(this);
    const isLiked = button.hasClass("liked");
    let reaction = "\u{2764}"; // Toggle between 💔 or ❤️// Toggle reaction: 💔 or ❤️

    // Extract necessary data
    const eventId = button.data("event-id");
    const eventPostCommentId = button.data("event-post-comment-id");
    const allLikeButtons = $(
        `button[data-event-post-comment-id='${eventPostCommentId}']`
    );
    const allLikeIcons = allLikeButtons.find("i");

    // Select both like icons (main comment and nested reply)
    const mainLikeIcon = button.find("i");

    // Toggle like button appearance for both elements
    // if (isLiked) {
    //     button.removeClass("liked");
    //     mainLikeIcon.removeClass("fa-solid").addClass("fa-regular");
    //    replyLikeIcon.removeClass("fa-solid").addClass("fa-regular");
    // } else {
    //     button.addClass("liked");
    //     mainLikeIcon.removeClass("fa-regular").addClass("fa-solid");
    //     replyLikeIcon.removeClass("fa-regular").addClass("fa-solid");
    // }

    // AJAX call to update like state
    $.ajax({
        url: base_url + "event_wall/userPostCommentReplyReaction",
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        contentType: "application/json",
        data: JSON.stringify({
            event_id: eventId,
            event_post_comment_id: eventPostCommentId,
            reaction: reaction,
        }),
        success: function (response) {
            if (response.status === 1) {
                console.log(response);

                // Update like count for both main comment and nested reply
                $(`#commentTotalLike_${eventPostCommentId}`).text(
                    `${response.count}`
                );
                if (response.self_reaction == "\u{2764}") {
                    // Update all like buttons with the same comment ID
                    allLikeIcons.removeClass("fa-regular").addClass("fa-solid");
                } else {
                    allLikeIcons.removeClass("fa-solid").addClass("fa-regular");
                }
            }
        },
        error: function (xhr) {
            console.error(xhr.responseText);
            alert("An error occurred. Please try again.");
        },
    });
});

// function renderReactions(post) {
//     let reactionList = post.reactionList || [];
//     let selfReaction = post.self_reaction;
//     let reactionHtml = "";
//     let j = 0;
//     let i = 0;

//     reactionList.forEach((reaction) => {
//         if (i >= 3) return; // Limit to 3 reactions

//         let emojiSrc = reactionIcons[reaction] || null;

//         if (emojiSrc) {
//             let listItemId = (j === 0 && selfReaction === reaction) ? `id="reactionImage_${post.id}"` : "";
//             reactionHtml += `<li ${listItemId}><img src="${emojiSrc}" alt="Emoji"></li>`;
//             if (j === 0 && selfReaction === reaction) j++;
//             i++;
//         }
//     });

//     if (j === 0 && i < 3) {
//         reactionHtml += `<li id="reactionImage_${post.id}"></li>`;
//     }

//     let likeCountHtml = `<p id="likeCount_${post.id}">${post.total_likes} Likes</p>`;

//     return reactionHtml + likeCountHtml;
// }
function renderReactions(post) {
    let reactionList = post.reactionList || [];
    let selfReaction = post.self_reaction;
    let reactionHtml = "";
    let j = 0;
    let i = 0;

    for (let reaction of reactionList) {
        if (i >= 3) break; // Limit to 3 reactions

        let emojiSrc = reactionIcons[reaction] || null;

        if (emojiSrc) {
            let listItemId = (j === 0 && selfReaction === reaction) ? `id="reactionImage_${post.id}"` : "";
            reactionHtml += `<li ${listItemId} style="display:flex;"><img src="${emojiSrc}" alt="Emoji"></li>`;
            if (j === 0 && selfReaction === reaction) j++;
            i++;
        }
    }

    if (j === 0 && i < 3) {
        reactionHtml += `<li id="reactionImage_${post.id}" style="display:flex;"></li>`;
    }

    let likeCountHtml = `<p id="likeCount_${post.id}">${post.total_likes} Likes</p>`;

    return reactionHtml + likeCountHtml;
}

$(document).ready(function () {
    let reactionIcons = {
        "❤️": base_url + "assets/front/img/heart-emoji.png", // ❤️
        "\\u{2764}": base_url + "assets/front/img/heart-emoji.png", // ❤️
        "👍": base_url + "assets/front/img/thumb-icon.png", // 👍
        "\u{1F44D}": base_url + "assets/front/img/thumb-icon.png", // 👍
        "\u{1F604}": base_url + "assets/front/img/smily-emoji.png", // 😄
        "/\u{1F44F}": base_url + "assets/front/img/smily-emoji.png", // 😄
        "😊": base_url + "assets/front/img/smily-emoji.png", // 😄
        "\u{1F60D}": base_url + "assets/front/img/eye-heart-emoji.png", // 😍
        "😍": base_url + "assets/front/img/eye-heart-emoji.png", // 😍
        "\u{1F44F}": base_url + "assets/front/img/clap-icon.png", // 👏
    };
    $(".posts-card-like-comment-right").each(function () {
      const $container = $(this); // Get the current container
      const $likeButton = $container.find(".posts-card-like-btn"); // Find the like button within the container
      const $emojiDropdown = $container.find(".photos-likes-options-wrp"); // Find the emoji dropdown within the container
      let pressTimer;

      // Handle long press to show emoji dropdown (for both desktop and mobile)
      $(document).on('click','#likeButtonModel',function(){
          $emojiDropdown.show(); // Show emoji dropdown after long press
        // pressTimer = setTimeout(function () {
        // }, 500); // Trigger long press after 0.5 seconds
      });

    //   $likeButton.on("mouseup touchend mouseleave touchcancel", function () {
    //     clearTimeout(pressTimer); // Clear the timer if button is released or mouse/touch leaves
    //   });

      // Handle emoji click
      $emojiDropdown.on("click", ".emoji", function () {
        const emoji = $(this).data("emoji");

        // Remove the heart icon and set emoji inside the button
        $likeButton.html(`<span class="emoji"><img src='${reactionIcons[emoji]}'/></span>`); // Show selected emoji inside button

        $emojiDropdown.hide(); // Hide emoji dropdown after selection
      });

      // Optional: Hide the emoji dropdown if you click outside of it
    //   $(document).on("click touchstart", function (e) {
      $(document).on('click','#likeButtonModel',function(){
          if (!$container.is(e.target) && $container.has(e.target).length === 0) {
            $emojiDropdown.hide(); // Hide emoji dropdown if click is outside
          }
      })
    //   });
    });
  });
