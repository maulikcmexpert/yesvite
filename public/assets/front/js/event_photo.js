$(document).ready(function () {
    var reactionIcons = {
        "❤️": base_url + "assets/front/img/heart-emoji.png", // ❤️
        "\\u{2764}": base_url + "assets/front/img/heart-emoji.png", // ❤️
        "👍": base_url + "assets/front/img/thumb-icon.png", // 👍
        "\\u{1F44D}": base_url + "assets/front/img/thumb-icon.png", // 👍

        "\\u{1F60A}": base_url + "assets/front/img/smily-emoji.png", // 😄
        "😊": base_url + "assets/front/img/smily-emoji.png", // 😄
        "\\u{1F60D}": base_url + "assets/front/img/eye-heart-emoji.png", // 😍
        "😍": base_url + "assets/front/img/eye-heart-emoji.png", // 😍
        "\\u{1F44F}": base_url + "assets/front/img/clap-icon.png", // 👏
        "👏": base_url + "assets/front/img/clap-icon.png", // 👏
    };
    function validateForm() {
        let isValid = true;

        $(".create_post").prop("disabled", !isValid);
    }

    // Submit form on button click
    $(document).on("click", ".create_post", function () {
        // Check if the poll form exists and is valid
        var $this = $(this); // Ca
        var photoForm = $("#photoForm");
        var textForm = $("#textform");
        var photoInput = document.querySelector(".fileInputtype");
        var imagePreview = $("#imagePreview").children().length; // Che
        console.log(
            "Photo Form:",
            photoForm.length > 0 ? "Exists" : "Does not exist"
        );

        if (photoForm.is(":visible") && photoForm.length > 0) {
            if (photoInput.files.length === 0 && imagePreview === 0) {
                toastr.error(
                    "Please upload a photo or enter some content for the photo post."
                );
                return;
            }
            // const input1 = document.getElementById("fileInput");
            // const input2 = document.getElementById("fileInput2");
            // if (!input1 || !input2) {
            //     console.error("One or both file input elements are missing");
            //     return;
            // }

            // const dataTransfer = new DataTransfer();

            // for (let i = 0; i < input1.files.length; i++) {
            //     dataTransfer.items.add(input1.files[i]);
            // }

            // for (let i = 0; i < input2.files.length; i++) {
            //     dataTransfer.items.add(input2.files[i]);
            // }

            // input1.files = dataTransfer.files;
            $this.html('<div class="s-loader"><div></div><div></div><div></div><div></div></div>').prop("disabled", true);
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
        $("#detail-photo-modal .modal-content").toggleClass("active")
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

    $(document).on("mousedown", ".like-btn", function () {
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

    $(document).on("click", "#emojiDropdown .emoji", function () {
        const selectedEmoji = $(this).data("emoji");
        const button = $(this)
            .closest(".photo-card-head-right")
            .find(".like-btn");
        console.log(selectedEmoji);

        // const emojiDisplay = button.find('#show_Emoji');

        // Replace heart icon with selected emoji
        // emojiDisplay.removeClass();
        // emojiDisplay.text(selectedEmoji);

        // AJAX call to update emoji reaction
        const eventId = button.data("event-id");
        const eventPostId = button.data("event-post-id");
        console.log(eventId, eventPostId);
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
                    // const post = {
                    //     id: eventPostId,
                    //     reactionList: response.reactionList,
                    //     // self_reaction: response.self_reaction,
                    //     total_likes: response.count
                    // };
                    // document.getElementById("postCardEmoji").innerHTML = renderReactions(post);
                    let reactionImageHtml = "";
                    if (response.is_reaction == "1") {
                        // ✅ User has liked the post, update the reaction image
                        console.log("Like given, updating reaction image...");
                        if (reactionIcons[selectedEmoji]) {
                            console.log(reactionIcons[selectedEmoji]);
                            reactionImageHtml = `<img src="${reactionIcons[selectedEmoji]}" alt="Reaction Emoji">`;
                        }
                        button.addClass("liked"); // Add liked class
                    } else {
                        // ✅ User has removed like, set the first reaction from response
                        console.log(
                            "Like removed sf.kkshdfhjkfhjkhfjkhsdjkjkshfjksdhfhfdj, updating first available reaction..."
                        );
                        if (response.reactionList.length > 0) {
                            let firstReaction =
                                response.reactionList[0].reaction; // ✅
                            if (firstReaction.startsWith("\\u{")) {
                                firstReaction = String.fromCodePoint(
                                    parseInt(
                                        firstReaction.replace(/\\u{|}/g, ""),
                                        16
                                    )
                                );
                            }
                            if (reactionIcons[selectedEmoji]) {
                                reactionImageHtml = `<img src="${reactionIcons[selectedEmoji]}" alt="Reaction Emoji">`;
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

                    $(`#reactionImage_${eventPostId}`).html(reactionImageHtml);

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
                    setTimeout(function () {
                        location.reload();
                    }, 2000);
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
                        const firstInitial =
                            nameParts[0]?.[0]?.toUpperCase() || "";
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

                    if (
                        data.comment_replies &&
                        data.comment_replies.length > 0
                    ) {
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
                                document.getElementsByClassName(
                                    "reply-on-comment"
                                );
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
                }
            },
            error: function (xhr) {
                console.error(xhr.responseText);
                alert("An error occurred. Please try again.");
            },
        });
    });
    $(document).on("click", ".comment-send-icon", function (e) {
        sendComment.call(this); // Ensure `this` refers to the clicked button
    })
    $(document).on("keypress", "#post_comment", function (e) {
        if (e.which === 13) { // 13 is the key code for Enter
            e.preventDefault(); // Prevents newline in the input field
            $(this).next(".comment-send-icon").click(); // Trigger click on send button
            $(".parent_comment_id").val("");
        }
    });

    function sendComment() {

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
                        const firstInitial =
                            nameParts[0]?.[0]?.toUpperCase() || "";
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
                                    <button class="posts-card-like-btn" id="CommentlikeButton" data-event-id="${eventId}" data-event-post-comment-id="${data.id
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
                                    <button class="posts-card-like-btn" id="CommentlikeButton" data-event-id="${eventId}" data-event-post-comment-id="${data.id
                        }" data-user-id="1">
                                    <i class="fa-regular fa-heart" id="show_Emoji"></i>
                                    </button>
                                    <p id="commentTotalLike_${data.id}">
                                  1
                                    </p>
                                </div>
                                <button data-comment-id="${data.id
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
                    if (
                        data.comment_replies &&
                        data.comment_replies.length > 0
                    ) {
                        data.comment_replies.forEach(function (reply) {
                            const replyHTML = `
                        <li class="reply-on-comment" data-comment-id="${reply.id
                                }">
                            <div class="commented-user-head">
                                <div class="commented-user-profile">
                                    <div class="commented-user-profile-img">
                                        <img src="${reply.profile || "default-image.png"
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
                    const currentCount =
                        parseInt(commentCountElement.text()) || 0;
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

    }
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
    const longPressDelay = 2000; // 3 seconds for long press

    let pressTimer;
    let isLongPress = false;
    let bulkSelectActive = false;
    // Function to handle the long press action
    function handleLongPress(element) {

        console.log("Long press detected ");
        bulkSelectActive = false;
        console.log("Bulk Select :", bulkSelectActive);

        const photoCard = element.closest(".photo-card-photos-wrp");
        photoCard.find(".selected-photo-btn").show();
        photoCard.find(".selected_image").prop("checked", true);


        toggleBulkSelectWrapper();
    }

    // Function to toggle visibility of the bulk-select-photo-wrp
    function toggleBulkSelectWrapper() {
        const selected_bulk_image = $(".selected_bulk_image:checked").length;
        const bulkDeleteBtn = $(".select_bulk_btn"); // Wrapper for delete button
        const deleteBtn = $(".bulk_delete"); // Actual delete button
        const downloadBtn = $(".downloadBtn"); // Download button
        const bulkSelectWrapper = $(".selecte_delete_photos");
        const login_user = $('#login_user_id').val(); // Get logged-in user ID
        let allOwnPosts = true; // Flag to check if all selected posts belong to user

        $(".selected_bulk_image:checked").each(function () {
            const post_user_id = $(this).attr('data-user_id');
            if (post_user_id !== login_user) {
                allOwnPosts = false;
            }
        });

        if (selected_bulk_image > 0) {
            bulkDeleteBtn.removeClass("d-none"); // Show bulk select wrapper
            bulkDeleteBtn.find(".bulk_delete_selected p").text(`${selected_bulk_image} Photos Selected`);

            if (allOwnPosts) {
                deleteBtn.removeClass("d-none"); // Show delete button only for user's own photos
            } else {
                deleteBtn.addClass("d-none"); // Hide delete button if mixed selection
            }

            downloadBtn.removeClass("d-none"); // Always show download button when images are selected
            $('.add_new_photo_btn').addClass('d-none'); // Hide 'add new photo' button when selecting
        } else {
            bulkSelectWrapper.addClass("d-none"); // Hide bulk select wrapper
            bulkDeleteBtn.addClass("d-none"); // Hide bulk delete button


            // $('.selected-bulk-btn').css('display','none');
            // $('.set_emoji_like').css('display','flex');

            deleteBtn.addClass("d-none"); // Hide delete button
            downloadBtn.addClass("d-none"); // Hide download button
            $('.add_new_photo_btn').removeClass('d-none'); // Show 'add new photo' button
        }
    }



    $(document).on("change", ".selected_bulk_image", function () {
        const photoCard = $(this).closest(".photo-card-photos-wrp");

        if ($(this).is(":checked")) {
            photoCard.find(".selected-bulk-btn").show();
        } else {
            photoCard.find(".selected-bulk-btn").hide();

        }
        if ($(".selected_bulk_image:checked").length === 0) {
            bulkSelectActive = false;
        }

        if ($(".selected_bulk_image:checked").length === 0) {
              $('.selected-bulk-btn').css('display','none');
            $('.set_emoji_like').css('display','flex');
        }
        toggleBulkSelectWrapper(); // Update bulk selection UI
    });




    $(document).on("click", ".img_click", function (e) {
        if (bulkSelectActive) {
            e.preventDefault(); // Stop default modal behavior

            var login_user = $('#login_user_id').val();
            var post_user_id = $(this).attr('data-user_id');

            console.log(login_user);
            console.log(post_user_id);

            // if(login_user!=post_user_id){
            //     toastr.success('You can bulk delete your own photos only');
            //     return;
            // }

            const checkbox = $(this).closest(".photo-card-photos-wrp").find(".selected_bulk_image");
            checkbox.prop("checked", !checkbox.prop("checked")); // Toggle checkbox

            if (checkbox.prop("checked")) {
                $(this).closest(".photo-card-photos-wrp").find(".selected-bulk-btn").show();
            } else {
                $(this).closest(".photo-card-photos-wrp").find(".selected-bulk-btn").hide();

                // Disable bulk selection if no checkboxes are selected
                if ($(".selected_bulk_image:checked").length === 0) {
                    bulkSelectActive = false;
                }
            }

            toggleBulkSelectWrapper();
        } else {
            // Allow modal to open if bulk selection is NOT active
            return true;
        }
    });


    $(".bulk_select").on("click", function (e) {
        e.preventDefault();
        const button = $(this);
        const eventId = button.data("event-id");
        const eventPostId = button.data("event-post-id");

        bulkSelectActive = true;

        $('.selected-bulk-btn').css('display','flex');
        $('.set_emoji_like').css('display','none');

        console.log("Bulk Select Mode Active:", bulkSelectActive);

        const closestCheckbox = $(this).closest(".photo-card-head").find(".selected_bulk_image");
        if (closestCheckbox.length) {
            closestCheckbox.prop("checked", true).trigger("change");
        }

        toggleBulkSelectWrapper(); // Update bulk selection UI

    });

    // On checkbox change event, toggle the visibility of the bulk select wrapper
    $(".form-check-input").on("change", function () {
        toggleBulkSelectWrapper();
    });
    $(".download_img").on("click", function () {
        let selectedMedia = [];

        $(".selected_bulk_image:checked").each(function () {
            let mediaSrc = $(this).data("image-src");
            console.log(mediaSrc);


            // Parse JSON if necessary
            if (typeof mediaSrc === "string") {
                try {
                    mediaSrc = JSON.parse(mediaSrc);
                } catch (e) {
                    console.error("Invalid JSON format in data-image-src:", mediaSrc);
                    return;
                }
            }

            if (Array.isArray(mediaSrc)) {
                selectedMedia = selectedMedia.concat(mediaSrc);
            } else {
                selectedMedia.push(mediaSrc);
            }
        });

        if (selectedMedia.length > 0) {
            downloadMediaSequentially(selectedMedia, 0);
        }

        // Uncheck all selected items and update UI
        $(".selected_bulk_image").prop("checked", false);
        $(".selected-bulk-btn").hide();
        bulkSelectActive = false;
        $('.set_emoji_like').css('display','flex');

        toggleBulkSelectWrapper();
    });



    // Function to download images and videos as files
    function downloadMediaSequentially(media, index) {
        if (index >= media.length) return; // Stop when all are processed

        const mediaSrc = media[index];
        const extension = mediaSrc.split(".").pop().toLowerCase(); // Get file extension
        const filename = `media_${index + 1}.${extension}`; // Dynamic filename

        fetch(mediaSrc)
            .then(response => response.blob())
            .then(blob => {
                const link = document.createElement("a");
                const url = URL.createObjectURL(blob);
                link.href = url;
                link.download = filename;
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                URL.revokeObjectURL(url); // Free memory

                // Delay next download to prevent browser blocking
                setTimeout(() => {
                    downloadMediaSequentially(media, index + 1);
                }, 500);
            })
            .catch(error => console.error("Download error:", error));
    }

    $(document).on("click", ".download_img_single", function () {
        // Find the image source stored in the data attribute
        const imgSrc = $(this).attr("data-src");
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
        $('.set_emoji_like').css('display','flex');

    });
    $(document).on("click", ".bulk_delete", function () {
        const login_user = $('#login_user_id').val(); // Get logged-in user ID

        const selectedPosts = $(".selected_bulk_image:checked").map(function () {
            const postUserId = $(this).data("user_id"); // Fetch the user ID of the post
            const postId = $(this).data("event-post-id"); // Fetch the post ID


            if (postUserId == login_user) {
                return { event_post_id: postId };
            } else {
                return null; // Exclude non-owner posts
            }
        }).get();

        if (selectedPosts.length === 0) {
            toastr.error("You can only delete your own posts.");
            return;
        }

        $.ajax({
            url: base_url + "event_photo/Bulk_deletePost", // Adjust base_url as necessary
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            contentType: "application/json",
            data: JSON.stringify({ posts: selectedPosts }), // Send array of event_post_id objects
            success: function (response) {
                if (response.success) {
                    console.log(response);

                    // Remove deleted posts from the UI
                    response.post_id.forEach(function (postId) {
                        $(".bulk_delete_id_" + postId).remove();
                    });

                    // Reset bulk selection mode
                    bulkSelectActive = false;
                    $(".selected_bulk_image").prop("checked", false);
                    $(".selected-bulk-btn").hide();
                    $('.set_emoji_like').css('display','flex');
                    toggleBulkSelectWrapper(); // Update UI

                    toastr.success("Selected posts deleted successfully.");
                } else {
                    toastr.error(response.message);
                }
            },
            error: function (xhr) {
                console.error(xhr.responseText);
                alert("An error occurred. Please try again.");
            },
        });
    });


    $(document).on("click", ".open_photo_model", function (e) {

        clearTimeout(pressTimer); // Clear the timer
        console.log("Mouse up or leave detected");
        if (bulkSelectActive) {
            e.preventDefault();
            $("#detail-photo-modal").modal("hide");
            return;
        }

        $("#detail-photo-modal").modal("show");
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
        // swiper.destroy(true, true);
        console.log(rawData.length);

        if (rawData.length > 1) {
            swiperWrapper.removeClass("hideswipe");

            // swiper.destroy(true, true);
            document.getElementsByClassName(
                "swiper-button-next"
            )[0].style.display = "flex";
            document.getElementsByClassName(
                "swiper-button-prev"
            )[0].style.display = "flex";
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
            // swiper.destroy(true, true);
            document.getElementsByClassName(
                "swiper-button-next"
            )[0].style.display = "none";
            document.getElementsByClassName(
                "swiper-button-prev"
            )[0].style.display = "none";
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
        $("#host_display").text("");

        $("#host_display").hide();
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
                        total_likes: data.total_likes,
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
                        $("#host_display").show();
                        $("#host_display").text("Host");
                        $("#host_display").addClass("host");
                    }
                    if (data.is_co_host == "1") {
                        $("#host_display").show();
                        const co_host = `${data.is_co_host}`;
                        $("#host_display").text("co_host");
                        $("#host_display").addClass("host");
                    }
                    // const login_user_id = $("#login_user_id").val();
                    // $("#report_btn").show();

                    // if (data.user_id == login_user_id) {
                    //     $("#report_btn").hide();
                    // }
                    let messageLink = $(".message-link");
                    let encrypted_id = data.encrypted_id;
                    if (encrypted_id) {
                        let messageRoute = `/messages/${encrypted_id}`;
                        messageLink.attr("href", messageRoute);
                    }

                    if (data.user_id == login_user_id) {
                        $(".message-link").addClass('d-none');


                    }
                    if (data.user_id != login_user_id) {
                        $(".message-link").removeClass('d-none');


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

                    const reactionVal = data.reactionList.forEach((that) => { });

                    // $("#likeCount").text(data.total_likes + " Likes");
                    // Add 'Likes' after the number
                    $("#comments").text(data.total_comments + " Comments");

                    console.log("Self Reaction:", data.self_reaction); // Debugging
                    console.log(typeof data.self_reaction); // Output: string

                    var reaction_store = data.self_reaction.trim();

                    console.log(reaction_store);

                    let reactionImageHtml = $("#likeButtonModel");
                    console.log(reactionImageHtml);

                    if (reactionIcons[reaction_store]) {
                        console.log(reactionIcons[reaction_store]);

                        reactionImageHtml = `<img src="${reactionIcons[reaction_store]}" alt="">`;
                    } else {
                        // If reaction_store is not found, show a default icon
                        reactionImageHtml = `<i class="fa-regular fa-heart"></i>`;
                    }
                    $(`#likeButtonModel`).html(reactionImageHtml);
                    let reaction_list = response.reactionList;
                    //    reaction_list.each(function () {

                    //     $(`#reactionImage`).html(reactionIcons[reaction_store]);
                    //    });

                    document.getElementById("postCardEmoji").innerHTML =
                        renderReactions(post);
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
                    updateReactions(data.reactionList);

                    const commentsWrapper = $(
                        ".posts-card-show-all-comments-inner ul"
                    );
                    commentsWrapper.empty(); // Clear existing comments

                    if (
                        data.latest_comment &&
                        Array.isArray(data.latest_comment)
                    ) {
                        data.latest_comment.forEach((comment) => {
                            let parentCommentId = comment.id;
                            let displayName = comment.profile
                                ? `<img src="${comment.profile}" alt="User Profile" class="profile-image">`
                                : generatePlaceholderName(comment.username);

                            commentsWrapper.append(`
                            <li class="commented-user-wrp" data-comment-id="${comment.id
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
                                         <button class="posts-card-like-btn" id="CommentlikeButton" data-event-id="${eventId}" data-event-post-comment-id="${comment.id
                                }" data-user-id="${login_user_id}">
                                    <i class="fa-regular fa-heart"></i>
                                    </button>
                                        <p id="commentTotalLike_${comment.id
                                }">${comment.comment_total_likes || 0
                                }</p>
                                    </div>
                                    <button class="commented-user-reply-btn" data-comment-id="${comment.id
                                }">Reply</button>
                                </div>
 <ul class="primary-comment-replies"></ul>
                            </li>

                        `);

                            if (
                                comment.comment_replies &&
                                comment.comment_replies.length > 0
                            ) {
                                comment.comment_replies.forEach(function (
                                    reply
                                ) {
                                    let displayName = reply.profile
                                        ? `<img src="${reply.profile}" alt="User Profile" class="profile-image">`
                                        : generatePlaceholderName(
                                            reply.username
                                        );
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
                                <button class="commented-user-reply-btn" data-comment-id="${reply.id
                                        }">Reply</button>
                            </div>
                        `;

                                    const li = document.createElement("li");
                                    li.className = "reply-on-comment";
                                    li.setAttribute(
                                        "data-comment-id",
                                        reply.id
                                    );
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
                                            el.dataset.commentId ===
                                            parentCommentId
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
                                            let parentUl =
                                                previousComment.closest(
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
                                                console.log(
                                                    el.dataset.commentId
                                                );
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
                        const firstInitial =
                            nameParts[0]?.[0]?.toUpperCase() || "";
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

        function updateReactions(reactions) {
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

            reactions.forEach((reactionData) => {
                let reactionType = "";
                let emojiSrc = "";

                // Extract user details from the reaction object
                const { reaction, firstname, lastname, profile, location } =
                    reactionData;
                // Map each reaction to a type
                switch (reaction) {
                    case "\\u{2764}": // Heart
                        reactionType = "heart";
                        break;
                    case "\\u{1F44D}": // Thumbs Up
                        reactionType = "thumb";
                        break;
                    case "\\u{1F60A}": // Smiley
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
                const profileContent =
                    profile && profile !== ""
                        ? `<img src="${profile}" alt="">`
                        : `<h5 class="fontcolor${firstname ? firstname[0].toUpperCase() : ""
                        }">${firstname ? firstname[0].toUpperCase() : ""}${lastname ? lastname[0].toUpperCase() : ""
                        }</h5>`;
                // Create reaction list item
                const reactionItem = `<li class="reaction-info-wrp">
                                    <div class="commented-user-head">
                                        <div class="commented-user-profile">
                                            <div class="commented-user-profile-img">
                                            ${profileContent}
                                            </div>
                                            <div class="commented-user-profile-content">
                                                  <h3>${firstname} ${lastname}</h3>


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
        const button = $(this).closest(".emoji_set").find("#likeButtonModel");
        const emojiDisplay = button.find("#show_comment_emoji");
        const eventId = button.data("event-id");
        const eventPostId = button.data("event-post-id");
        const button_main = $("#likeButton_" + eventPostId);
        console.log(selectedEmoji);

        // Replace heart icon with selected emoji
        emojiDisplay.removeClass();
        emojiDisplay.text(selectedEmoji);

        // AJAX call to update emoji reaction

        console.log(eventId, eventPostId);
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
                    console.log(response.reactionList);

                    // const post = {
                    //     id: eventPostId,
                    //     reactionList: response.reactionList,
                    //     // self_reaction: response.self_reaction,
                    //     total_likes: response.count
                    // };
                    // document.getElementById("postCardEmoji").innerHTML = renderReactions(post);
                    let reactionImageHtml = "";
                    if (response.is_reaction == "1") {
                        // ✅ User has liked the post, update the reaction image
                        console.log("Like given, updating reaction image...");
                        if (reactionIcons[selectedEmoji]) {
                            console.log(reactionIcons[selectedEmoji]);
                            reactionImageHtml = `<img src="${reactionIcons[selectedEmoji]}" alt="Reaction Emoji">`;
                        }
                        button.addClass("liked"); // Add liked class
                    } else {
                        // ✅ User has removed like, set the first reaction from response
                        console.log(
                            "Like removed , updating first available reaction..."
                        );
                        if (response.reactionList.length > 0) {
                            let firstReaction =
                                response.reactionList[0].reaction; // ✅
                            if (firstReaction.startsWith("\\u{")) {
                                firstReaction = String.fromCodePoint(
                                    parseInt(
                                        firstReaction.replace(/\\u{|}/g, ""),
                                        16
                                    )
                                );
                            }
                            if (reactionIcons[selectedEmoji]) {
                                reactionImageHtml = `<img src="${reactionIcons[selectedEmoji]}" alt="Reaction Emoji">`;
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
                        );
                    }

                    button_main.html(reactionImageHtml);
                    $(`#reactionImage_model_${eventPostId}`).html(
                        reactionImageHtml
                    );
                    $(`#reactionImage_${eventPostId}`).html(reactionImageHtml);

                    $(`#like_${eventPostId}`).text(`${response.count} Likes`);
                    $(`#likeCount_${eventPostId}`).text(
                        `${response.count} Likes`
                    );
                    updateReactions(response.reactionList);
                } else {
                    alert(response.message);
                }
            },
            error: function (xhr) {
                console.error(xhr.responseText);
                alert("An error occurred. Please try again.");
            },
        });
        function updateReactions(reactions) {
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

            reactions.forEach((reactionData) => {
                let reactionType = "";
                let emojiSrc = "";

                // Extract user details from the reaction object
                const { reaction, firstname, lastname, profile, location } =
                    reactionData;
                // Map each reaction to a type
                switch (reaction) {
                    case "\\u{2764}": // Heart
                        reactionType = "heart";
                        break;
                    case "\\u{1F44D}": // Thumbs Up
                        reactionType = "thumb";
                        break;
                    case "\\u{1F60A}": // Smiley
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
                const profileContent =
                    profile && profile !== ""
                        ? `<img src="${profile}" alt="">`
                        : `<h5 class="fontcolor${firstname ? firstname[0].toUpperCase() : ""
                        }">${firstname ? firstname[0].toUpperCase() : ""}${lastname ? lastname[0].toUpperCase() : ""
                        }</h5>`;
                // Create reaction list item
                const reactionItem = `<li class="reaction-info-wrp">
                                    <div class="commented-user-head">
                                        <div class="commented-user-profile">
                                            <div class="commented-user-profile-img">
                                            ${profileContent}
                                            </div>
                                            <div class="commented-user-profile-content">
                                                  <h3>${firstname} ${lastname}</h3>


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
        // Hide emoji picker
        $(this).closest("#emojiDropdown1").hide();

        // Define visibility options

        // Dynamically set the hidden values in the forms
        $("form").on("submit", function () {
            // Fetch the visibility and commenting status to update the form's hidden inputs before submission
            const visibility =
                $('input[name="post_privacy"]:checked').val() || "1"; // Default to Everyone if null
            const allowComments = $("#allowComments").is(":checked")
                ? "1"
                : "0";

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
                        allLikeIcons
                            .removeClass("fa-regular")
                            .addClass("fa-solid");
                    } else {
                        allLikeIcons
                            .removeClass("fa-solid")
                            .addClass("fa-regular");
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
        let i = 0; // Count displayed reactions
        let j = 0;

        for (let reactionData of reactionList) {
            if (i >= 3) break; // Limit to 3 reactions

            let { reaction, firstname, lastname, profile } = reactionData;

            let emojiSrc = reactionIcons[reaction] || null; // Get emoji image
            if (emojiSrc) {
                let listItemId =
                    j === 0 && selfReaction === reaction
                        ? `id="reactionImage_model_${post.id}"`
                        : "";
                reactionHtml += `<li ${listItemId}><img src="${emojiSrc}" alt="Emoji"></li>`;
                if (j === 0 && selfReaction === reaction) j++;
                i++;
            }
        }

        // If no reactions found, show an empty reaction placeholder
        if (j === 0 && i < 3) {
            reactionHtml += `<li id="reactionImage_model_${post.id}"></li>`;
        }

        let likeCountHtml = `<p id="like_${post.id}">${post.total_likes} Likes</p>`;

        return reactionHtml + likeCountHtml;
    }

    $(document).on("click", "#likeButtonModel", function () {
        console.log("asd");
        setTimeout(function () {
            $("#emojiDropdown1").show();
            console.log("asd");
        }, 1000);

        $("#emojiDropdown1").css("display", "block");
        console.log($("#emojiDropdown1"));
    });
    $(".posts-card-like-comment-right").each(function () {
        const $container = $(this); // Get the current container
        const $likeButton = $container.find(".posts-card-like-btn"); // Find the like button within the container
        const $emojiDropdown = $container.find(".photos-likes-options-wrp"); // Find the emoji dropdown within the container
        let pressTimer;

        // Handle long press to show emoji dropdown (for both desktop and mobile)

        //   $likeButton.on("mouseup touchend mouseleave touchcancel", function () {
        //     clearTimeout(pressTimer); // Clear the timer if button is released or mouse/touch leaves
        //   });

        // Handle emoji click
        $emojiDropdown.on("click", ".emoji", function () {
            const emoji = $(this).data("emoji");

            // Remove the heart icon and set emoji inside the button
            $likeButton.html(`<img src='${reactionIcons[emoji]}'/>`); // Show selected emoji inside button

            $emojiDropdown.hide(); // Hide emoji dropdown after selection
        });

        // Optional: Hide the emoji dropdown if you click outside of it
        //   $(document).on("click touchstart", function (e) {
        //   $(document).on('click','#likeButtonModel',function(e){
        //       if (!$container.is(e.target) && $container.has(e.target).length === 0) {
        //         $emojiDropdown.hide(); // Hide emoji dropdown if click is outside
        //       }
        //   })
        //   });
    });
});
$(document).ready(function () {
    const visibilityOptions = {
        1: "Everyone",
        2: "RSVP’d - Yes",
        3: "RSVP’d - No",
        4: "RSVP’d - No Reply",
    };

    function loadSettings() {
        console.log("Loading settings..."); // Debugging
        let savedVisibility =
            "1";
        let savedAllowComments =
            "1";


        $('input[name="post_privacy"][value="' + savedVisibility + '"]').prop(
            "checked",
            true
        );
        $("#allowComments").prop("checked", savedAllowComments === "1");

        $(".hiddenVisibility").val(savedVisibility);
        $(".hiddenAllowComments").val(savedAllowComments);

        $("#savedSettingsDisplay").html(`
            <h4>${visibilityOptions[savedVisibility]} <i class="fa-solid fa-angle-down"></i></h4>
        `);
    }

    // Page load settings
    loadSettings();

    // Check modal ID & trigger loadSettings()
    $("#add-new-photomodal").on("show.bs.modal", function () {
        console.log("Modal Opened & Settings Loaded"); // Debugging
        loadSettings();
    });

    // Save Button Click Handler
    $("#saveSettings").on("click", function () {
        const visibility = $('input[name="post_privacy"]:checked').val() || "1";
        const allowComments = $("#allowComments").is(":checked") ? "1" : "0";

        localStorage.setItem("post_privacys", visibility);
        localStorage.setItem("commenting_on_off", allowComments);

        $(".hiddenVisibility").val(visibility);
        $(".hiddenAllowComments").val(allowComments);

        $("#savedSettingsDisplay").html(`
            <h4>${visibilityOptions[visibility]} <i class="fa-solid fa-angle-down"></i></h4>
        `);

        console.log("Saved Settings:", { visibility, allowComments });
    });



    var selectedReportType = ""; // Store selected report type

    // Open modal when clicking the report button
    $(".reportbtn").on("click", function () {
        var eventId = $(this).data("event-id");
        var postId = $(this).data("event-post-id");

        // Store event ID and post ID in the modal's data attributes
        $("#submitreport").data("event-id", eventId);
        $("#submitreport").data("post-id", postId);

        // Reset previous selections
        $(".report-option").removeClass("active");
        selectedReportType = "";
        $("#violation-textbox").val("");
        $(".btn-submit-report").prop("disabled", true);
        $this.html('<div class="s-loader"><div></div><div></div><div></div><div></div></div>').prop("disabled", true);

        // Show the modal
        $("#submitreport").modal("show");
    });

    // Handle report type selection
    $(".report-option").on("click", function () {
        $(".report-option").removeClass("active");
        $(this).addClass("active");
        selectedReportType = $(this).data("report-type");

        // Enable submit button when a report type is selected
        $(".btn-submit-report").prop("disabled", false);
    });

    // Submit report via AJAX
    $(".btn-submit-report").on("click", function () {
        var eventId = $("#submitreport").data("event-id");
        var postId = $("#submitreport").data("post-id");
        var violationDetails = $("#violation-textbox").val();
        var media_id = $("#media_id").val();
        if (!selectedReportType) {
            toastr.error("Please select a report type.");
            return;
        }

        $.ajax({
            url: base_url + "event_wall/postMediaReport", // Adjust endpoint
            type: "POST",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            data: {
                event_id: eventId,
                event_post_id: postId,
                report_type: selectedReportType,
                report_description: violationDetails,
                post_media_id: media_id
            },
            success: function (response) {
                if (response.status === 1) {
                    toastr.success(response.message);
                    setTimeout(function () {
                        $("#submitreport").modal("hide");
                    }, 2000);
                }
            },
            error: function (xhr, status, error) {
                console.error("Error:", error);
                // alert("Failed to submit the report. Please try again later.");
            },
        });
    });



});
