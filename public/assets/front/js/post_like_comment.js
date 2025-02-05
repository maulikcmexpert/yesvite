let guestList = [];
let guestPhoneList =[];

$(document).ready(function () {
    let longPressTimer;
    let isLongPresss = false;


    $(document).on("mousedown", "#likeButton", function () {
        isLongPresss = false; // Reset the flag
        const button = $(this);

        // Start the long press timer
        longPressTimer = setTimeout(() => {
            isLongPresss = true; // Mark as long press
            const emojiDropdown = button
                .closest(".posts-card-like-comment-right")
                .find("#emojiDropdown");
            emojiDropdown.show(); // Show the emoji picker
            //button.find('i').text(''); // Clear the heart icon
        }, 500); // 500ms for long press
    });

    $(document).on("click", "#likeButton", function () {
        clearTimeout(longPressTimer); // Clear the long press timer

        // If it's a long press, don't process the click event
        if (isLongPresss) return;

        // Handle single tap like/unlike
        const button = $(this);
        const isLiked = button.hasClass("liked");
        const reaction = isLiked ? "\u{1F90D}" : "\u{2764}"; // Toggle reaction: 💔 or ❤️


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
            url: base_url + "event_wall/userPostLikeDislike",
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
                } else {
                    // alert(response.message);
                }
            },
            error: function (xhr) {
                console.error(xhr.responseText);
                alert("An error occurred. Please try again.");
            },
        });
    });
    $(document).on("click", "#CommentlikeButton", function () {


        const button = $(this);
        const isLiked = button.hasClass("liked");
        const reaction = isLiked ? "\u{1F90D}" : "\u{2764}"; // Toggle reaction: 💔 or ❤️

        // Extract necessary data
        const eventId = button.data("event-id");
        const eventPostCommentId = button.data("event-post-comment-id");

        // Select both like icons (main comment and nested reply)
        const mainLikeIcon = button.find("i");
        const replyLikeIcon = $(`#comment_like_${eventPostCommentId}`);

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
                    $(`#commentTotalLike_${eventPostCommentId}`).text(`${response.count} Likes`);

                    // Update the reaction display
                    replyLikeIcon.text(`${response.self_reaction}`);
                } else {
                    // alert(response.message);
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
            .closest(".set_emoji_like")
            .find("#likeButton");
        const emojiDisplay = button.find("#show_Emoji");

        // Replace heart icon with selected emoji
        emojiDisplay.removeClass();
        emojiDisplay.text(selectedEmoji);

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

    // Handle comment submission (first-time comment or reply)
    // Handle comment submission
    // Handle comment submission
    // Handle comment submission
    $(document).on("click", ".comment-send-icon", function () {
        const parentWrapper = $(this).closest(".posts-card-main-comment"); // Find the closest comment wrapper
        const commentInput = parentWrapper.find("#post_comment"); // Find the input within the current post
        const comment_on_of = $("#comment_on_of").val();
        // alert(comment_on_of);
        if (comment_on_of !== "1") {
            // Disable the input field
            commentInput.prop("disabled", true);

            // Find and remove the button inside the same parent wrapper
            parentWrapper.find(".posts-card-comm").remove();

            // Show an error message using toastr
            toastr.error("You are not able to comment.");

            return; // Exit the function if commenting is off
        }

        // Enable the input and show the button if commenting is allowed
        commentInput.prop("disabled", false);
        parentWrapper.find(".posts-card-comm").show();

        const commentText = commentInput.val().trim();
        const parentCommentId = $(".parent_comment_id").val() || '';
        console.log("Parent Comment ID:", parentCommentId);

        if (commentText === "") {
            alert("Please enter a comment");
            return;
        }

        const eventId = $(this).data("event-id");
        const eventPostId = $(this).data("event-post-id");

        const url = parentCommentId
            ? base_url + "event_photo/userPostCommentReply"
            : base_url + "event_photo/userPostComment";

        // AJAX request
        $.ajax({
            url: url,
            type: "POST",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            data: {
                comment: commentText,
                event_id: eventId,
                event_post_id: eventPostId,
                parent_comment_id: parentCommentId,
            },
            success: function (response) {
                if (response.success) {
                    const data = response.data;

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

                    const newCommentHTML = `
                <li class="commented-user-wrp" data-comment-id="${
                    data.comment_id
                }">
                    <div class="commented-user-head">
                        <div class="commented-user-profile">
                            <div class="commented-user-profile-img">
                                 ${profileImage}
                            </div>
                            <div class="commented-user-profile-content">
                                <h3>${data.username}</h3>
                                <p>${data.location || ""}</p>
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
                        let replyList = parentComment.find("ul.primary-comment-replies");
                        if (replyList.length === 0) {
                            replyList = $('<ul class="primary-comment-replies"></ul>').appendTo(parentComment);
                        }

                        // Check if the reply is already appended
                        if (replyList.find(`li[data-comment-id="${data.comment_id}"]`).length === 0) {
                            replyList.append(newCommentHTML);
                        }
                    }
                } else {
                    // Append as a new top-level comment
                    const commentList = $(`.posts-card-show-all-comments-wrp.show_${eventPostId}`).find(".top-level-comments");

                    // Check if the comment is already appended
                    if (commentList.find(`li[data-comment-id="${data.comment_id}"]`)) {
                        commentList.append(newCommentHTML);
                    }
                }



                    // Handle replies if any are provided in the response
                    if (
                        data.comment_replies &&
                        data.comment_replies.length > 0
                    ) {
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
                $(".parent_comment_id").val(""); // Reset parent comment ID
                }
                // commentInput.val("");
                // $("#parent_comment_id").val(""); // Reset parent comment ID
            },
            error: function (xhr) {
                console.error(xhr.responseText);
                alert("An error occurred. Please try again.");
            },
        });
    });

    $(document).on("click", ".commented-user-reply-btn", function () {
        // Find the closest comment element
        const parentWrapper = $(this).closest(".posts-card-show-all-comments-wrp").prev(".posts-card-main-comment");

        if (!parentWrapper.length) {
            console.error("Parent wrapper not found!");
            return;
        }

        // Get the username and comment ID from the current comment being replied to
        const parentName = $(this).closest(".commented-user-wrp").find("h3").text().trim();
        const parentId = $(this).closest(".commented-user-wrp").data("comment-id");

        if (!parentId) {
            console.error("Parent Comment ID is missing!");
            return;
        }

        // Set the parent comment ID value in the hidden field for later use in the AJAX request
        $(".parent_comment_id").val(parentId); // Store parent comment ID in a hidden field

        // Set the active class on the currently selected comment
        $(".commented-user-wrp").removeClass("active"); // Remove 'active' from all comments
        $(this).closest(".commented-user-wrp").addClass("active"); // Add 'active' to the current comment

        // Focus the comment box and insert the '@username'
        const commentBox = parentWrapper.find(".post_comment");
        if (!commentBox.length) {
            console.error("Comment input field not found!");
            return;
        }

        // Insert the '@username' into the comment box and focus
        commentBox.val(`@${parentName} `).focus();
    });


    // Handle reply button click (when replying to a comment)
});

$(document).on("keyup", ".search-yesvite", function () {
    var searchQuery = $(this).val().toLowerCase(); // Get the search input value and convert it to lowercase

    // If search is empty, show all contacts
    if (searchQuery === "") {
        $(".yes-contact").show(); // Show all contacts
    } else {
        // Iterate through each invite-contact
        $(".yes-contact").each(function () {
            var contactName = $(this)
                .find(".yesvite-search")
                .data("search")
                .toLowerCase(); // Get the data-search attribute

            // If the search query matches part of the contact name, show the contact
            if (contactName.indexOf(searchQuery) !== -1) {
                $(this).show(); // Show this contact
            } else {
                $(this).hide(); // Hide this contact
            }
        });
    }
});

$(document).on("keyup", ".search-phone", function () {
    var searchQuery = $(this).val().toLowerCase();

    if (searchQuery === "") {
        $(".phone-contact").show();
    } else {
        $(".phone-contact").each(function () {
            var contactName = $(this)
                .find(".phone-search")
                .data("search")
                .toLowerCase();

            if (contactName.indexOf(searchQuery) !== -1) {
                $(this).show(); // Show this contact
            } else {
                $(this).hide(); // Hide this contact
            }
        });
    }
});

var allContactsSuccess = false;
let selectedContacts = [];
let selectedPhoneContacts = [];
$(document).ready(function () {
    const yesviteUrl = base_url + "event_wall/get_yesviteContact"; // URL for yesvite contacts
    //const phoneUrl = base_url + "event_wall/get_phoneContact"; // URL for phone contacts
    const event_id = $('#event_id').val();

    $("#allcontact").on("click", function () {

        $('#home_loader').css('display', 'block');

        guestList=[];
        $('.guest_yesvite').remove();
        $('.phone_yesvite').remove();
        $('.see_invite_nav_yesvite').addClass('active');
        $('.see_invite_nav_phone').removeClass('active');
        $('.phoneContact-checkbox:not(:disabled)').prop('checked', false);
        $('.contact-checkbox:not(:disabled)').prop('checked', false);
        $('.phone-checkbox:not(:disabled)').prop('checked', false);

        localStorage.removeItem("selectedContacts");
        localStorage.removeItem("selectedPhoneContacts");
        // if (allContactsSuccess) {
        //     return;
        // }
        $.ajax({
            url: yesviteUrl,
            type: "POST",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            data: {
                event_id: event_id // Pass event_id in request data
            },
            dataType: "json",
            success: function (response) {
                const contacts = response.yesvite_contacts;
                const container = $(".GuestTabContent");
                container.html(contacts);
                allContactsSuccess = true;
                $('#addguest').modal('show');
                const invitedUsers = response.invited_users;
                $('#home_loader').css('display', 'none');

                // selectedContacts = response.selected_yesvite_user;
                // selectedPhoneContacts = response.selected_phone_user;
                // console.log({selectedContacts,selectedPhoneContacts})
            },
            error: function () {
                toastr.error("No Contacts Found");
                $('#home_loader').css('display', 'none');

                // alert("Failed to load contacts.");
            },
        });
    });

});


// Load selected contacts from local storage on page load
$(document).ready(function () {
    loadSavedContacts();
    loadSavedPhoneContacts();
});

// Load saved contacts
function loadSavedContacts() {
    selectedContacts =
        JSON.parse(localStorage.getItem("selectedContacts")) || [];
    updateModalContent();

    selectedContacts.forEach((contact) => {
        handleCheckboxState(contact, ".contact-checkbox");
    });
}

// Load saved phone contacts
function loadSavedPhoneContacts() {
    selectedPhoneContacts =
        JSON.parse(localStorage.getItem("selectedPhoneContacts")) || [];
    updatePhoneModalContent();

    selectedPhoneContacts.forEach((contact) => {
        handleCheckboxState(contact, ".phoneContact-checkbox");
    });
}
console.log({selectedContacts,selectedPhoneContacts})

// Handle checkbox states
function handleCheckboxState(contact, checkboxSelector) {
    if (contact.selectedEmail) {
        $(
            `${checkboxSelector}[data-id="${contact.id}"][data-type="email"]`
        ).prop("checked", true);
        $(
            `${checkboxSelector}[data-id="${contact.id}"][data-type="phone"]`
        ).prop("checked", false);
    }
    if (contact.selectedPhone) {
        $(
            `${checkboxSelector}[data-id="${contact.id}"][data-type="phone"]`
        ).prop("checked", true);
        $(
            `${checkboxSelector}[data-id="${contact.id}"][data-type="email"]`
        ).prop("checked", false);
    }
}

// // Event listener for contact checkboxes
// $(document).on("change", ".contact-checkbox", function () {

//              handleCheckboxChange(
//         $(this),
//         selectedContacts,
//         "selectedContacts",
//         updateModalContent
//     );

// });

// // Event listener for phone contact checkboxes
// $(document).on("change", ".phoneContact-checkbox", function () {
//     handleCheckboxChange(
//         $(this),
//         selectedPhoneContacts,
//         "selectedPhoneContacts",
//         updatePhoneModalContent
//     );
// });

// // Handle checkbox change for both contact types
// function handleCheckboxChange(
//     $checkbox,
//     contactList,
//     localStorageKey,
//     updateFunction
// ) {
//     const contactData = {
//         id: $checkbox.data("id"),
//         name: $checkbox.data("name"),
//         lastname: $checkbox.data("last"),
//         email: $checkbox.data("email"),
//         phone: $checkbox.data("phone"),
//         selectedEmail: false,
//         selectedPhone: false,
//     };
//     console.log(contactData);

//     if ($checkbox.data("type") === "email") {
//         contactData.selectedEmail = $checkbox.is(":checked");
//         $(`[data-id="${contactData.id}"][data-type="phone"]`).prop(
//             "checked",
//             false
//         );
//     } else if ($checkbox.data("type") === "phone") {
//         contactData.selectedPhone = $checkbox.is(":checked");
//         $(`[data-id="${contactData.id}"][data-type="email"]`).prop(
//             "checked",
//             false
//         );
//     }

//     // Update the contact list
//     const existingIndex = contactList.findIndex((c) => c.id === contactData.id);
//     if (existingIndex !== -1) {
//         contactList.splice(existingIndex, 1); // Remove the existing entry
//     }
//     if (contactData.selectedEmail || contactData.selectedPhone) {
//         contactList.push(contactData); // Add updated entry
//     }

//     // Save to local storage and update UI
//     localStorage.setItem(localStorageKey, JSON.stringify(contactList));
//     updateFunction();
// }

// // Update modal content for email contacts
// function updateModalContent() {
//     updateModal(
//         ".selected-contacts-list",
//         selectedContacts,
//         "selectedContacts",
//         updateModalContent
//     );
//     // Update total count for selected contacts
//     $(".yesvite .number").text(selectedContacts.length);

// }

// // Update modal content for phone contacts
// function updatePhoneModalContent() {
//     updateModal(
//         ".selected-phone-list",
//         selectedPhoneContacts,
//         "selectedPhoneContacts",
//         updatePhoneModalContent
//     );
//     // Update total count for selected phone contacts
//     $(".phone .number").text(selectedPhoneContacts.length);

// }

// // General modal update function
// function updateModal(
//     modalSelector,
//     contactList,
//     localStorageKey,
//     updateFunction
// ) {
//     const $modalBody = $(modalSelector);
//     // $modalBody.empty();
//     contactList.forEach((contact, index) => {
//         const profileImage =
//             contact.profile ||
//             generateProfileImage(contact.name, contact.lastname);
//         const contactHtml = `
//             <div class="guest-user" data-id="${index}">
//                 <div class="guest-user-img">
//                    ${profileImage}
//                   <div class="guest-user add_yesvite_guest_${id}" data-id="${id}">
//                         <svg width="19" height="18" viewBox="0 0 19 18" fill="none" xmlns="http://www.w3.org/2000/svg">
//                             <rect x="1.20312" y="1" width="16" height="16" rx="8" fill="#F73C71" />
//                             <rect x="1.20312" y="1" width="16" height="16" rx="8" stroke="white" stroke-width="2" />
//                             <path d="M6.86719 6.66699L11.5335 11.3333" stroke="white" stroke-linecap="round" stroke-linejoin="round" />
//                             <path d="M6.8649 11.3333L11.5312 6.66699" stroke="white" stroke-linecap="round" stroke-linejoin="round" />
//                         </svg>
//                     </a>
//                 </div>
//                 <h6>${contact.name} ${contact.lastname}</h6>
//             </div>

//         `;
//         $modalBody.append(contactHtml);
//     });
//     const totalHtml = `
//         <a href="#" class="guest-user d-block yesvite ">
//             <div class="guest-user-img guest-total">
//                 <span class="number" id="total-selected-email">${selectedContacts.length}</span>
//                 <span class="content">Total</span>
//          </div>
//          <h6>Sell all</h6>
//         </a>

//     `;

//     $modalBody.append(totalHtml);

//     // Handle removal of contacts
//     $modalBody.off("click").on("click", ".close", function (e) {
//         e.preventDefault();
//         const contactId = $(this).closest(".guest-user").data("id");
//         contactList.splice(contactId, 1);
//         localStorage.setItem(localStorageKey, JSON.stringify(contactList));
//         updateFunction();
//     });
//       // Update total count for selected contacts

// }

// Function to generate profile image
function generateProfileImage(firstname, lastname) {
    firstname = firstname ? String(firstname).trim() : "";
    lastname = lastname ? String(lastname).trim() : "";
    const firstInitial = firstname[0] ? firstname[0].toUpperCase() : "";
    const secondInitial = lastname[0] ? lastname[0].toUpperCase() : "";
    const initials = `${firstInitial}${secondInitial}`;
    const fontColor = `fontcolor${firstInitial}`;
    return `<h5 class="${fontColor} font_name">${initials || "NA"}</h5>`;
}
// Event listener for contact checkboxes
$(document).ready(function () {
    // Event listener for contact checkboxes
        $(document).on("change", ".contact-checkbox", function () {
            // console.log($('#home_loader').length);
            // setTimeout(() => {
                // $('#home_loader').css('display', 'block');
            // }, 500);
            
            
            const id = $(this).data("id");
            const isSelected = $(this).attr('data-prefer'); // Use attr() instead of data()

            const first_name = $(this).data("name");
            const last_name = $(this).data("last");
            const email = $(this).data("email");
            const profile = $(this).data("profile");
            const event_id = $('#event_id').val();

            console.log(
                `Checkbox changed for ID: ${id}, email selected: ${isSelected}, phone selected: ${isSelected}`
            );
            if( $(this).is(":checked")){
                $('.add_yesvite_guest_'+id).remove();
                $(".phone-checkbox[data-id='" + id + "']").prop("checked", false);
                const exists = guestList.some((contact) => contact.id === id);
                var is_duplicate=0;
                if (exists) {
                    is_duplicate=1;
                }else{
                    is_duplicate=0;
                }
                storeAddNewGuest(id,1,isSelected,event_id,'yesvite',is_duplicate);
                addToGuestList(id, isSelected, 1,first_name,last_name,email,profile); // App user = 1 for email (app user)
                $('#home_loader').css('display','none');

            }else{
                guestList = guestList.filter(guest => guest.id !== id);
                const exists = guestList.some((contact) => contact.id === id);
                var is_duplicate=0;
                if (exists) {
                    is_duplicate=1;
                }else{
                    is_duplicate=0;
                }
                storeAddNewGuest(id,0,isSelected,event_id,'yesvite',is_duplicate);

                $('.add_yesvite_guest_'+id).remove();

                console.log(guestList);
            }

        });

        $(document).on("change", ".phone-checkbox", function () {
            // $('#home_loader').css('display', 'block');

            const id = $(this).data("id");
            const isSelected = $(this).attr('data-prefer'); // Use attr() instead of data()

            const first_name = $(this).data("name");
            const last_name = $(this).data("last");
            const email = $(this).data("email");
            const profile = $(this).data("profile");
            const event_id = $('#event_id').val();

            console.log(
                `Checkbox changed for ID: ${id}, email selected: ${isSelected}, phone selected: ${isSelected}`
            );
            if( $(this).is(":checked")){
                const exists = guestList.some((contact) => contact.id === id);
                var is_duplicate=0;
                if (exists) {
                    is_duplicate=1;
                }else{
                    is_duplicate=0;
                }
                console.log(is_duplicate);
                $('.add_yesvite_guest_'+id).remove();
                $(".contact-checkbox[data-id='" + id + "']").prop("checked", false);
                storeAddNewGuest(id,1,isSelected,event_id,'yesvite',is_duplicate);
                addToGuestList(id, isSelected, 1,first_name,last_name,email,profile); // App user = 1 for email (app user)


            }else{
                guestList = guestList.filter(guest => guest.id !== id);
                const exists = guestList.some((contact) => contact.id === id);
                var is_duplicate=0;
                if (exists) {
                    is_duplicate=1;
                }else{
                    is_duplicate=0;
                }
                $('.add_yesvite_guest_'+id).remove();
                storeAddNewGuest(id,0,isSelected,event_id,'yesvite',is_duplicate);

                console.log(guestList);
            }

        });

        function storeAddNewGuest(id,status,prefer_by,event_id,contact,is_duplicate){
            $('#home_loader').css('display', 'block');
        console.log({id,status,prefer_by,event_id,contact,is_duplicate});
        
            $.ajax({
                url: base_url+"store_add_new_guest",
                type: 'GET',
                data: {user_id:id,status:status,prefer_by:prefer_by,event_id:event_id,contact:contact,is_duplicate:is_duplicate},
                success: function (response) {
                 console.log(response);
                 if(response.is_phone=="1"&&response.view!=""){
                    $('.selected-phone-list').remove('.guest-user-phone');
                    $('.selected-phone-list').html(response.view);

                    // $('#home_loader').css('display', 'none');

                        $('#home_loader').css('display', 'none');   
                    
        

                }
                 if(response.view!=""&&response.is_phone=="0"){
                    $('.selected-contacts-list').remove('.guest-users');
                    $('.selected-contacts-list').html(response.view);
                    // $('#home_loader').css('display', 'none');
                        $('#home_loader').css('display', 'none');
        

                }


                },
                error: function (error) {
                  toastr.error('Something went wrong. Please try again!');
                  $('#home_loader').css('display', 'none');   

                },
              });
        }


    // Event listener for phone contact checkboxes
    $(document).on("change", ".phoneContact-checkbox", function () {

        const id = $(this).data("id");
        // const isSelected =$(this).data('prefer');
            const isSelected = $(this).attr('data-type'); // Use attr() instead of data()
            const first_name = $(this).data("name");
            const last_name = $(this).data("last");
            const email = $(this).data("email");
            const profile = "";
            const event_id = $('#event_id').val();

        // Add to the guest list if either email or phone is selected

        console.log(
            `Checkbox changed for ID: ${id}, email selected: ${isSelected}, phone selected: ${isSelected}`
        );
        // Add to the guest list, prefer email if selected, else prefer phone
        // addToGuestList(id, isEmailSelected ? "email" : "phone", 0);


        if( $(this).is(":checked")){
            const exists = guestList.some((contact) => contact.id === id);
            var is_duplicate=0;
            if (exists) {
                is_duplicate=1;
            }else{
                is_duplicate=0;
            }
            console.log(is_duplicate);

            $('.add_yesvite_guest_'+id).remove();
            $(".phoneContact-checkbox")
            .filter(`[data-id="${id}"]`)
            .not(this)
            .prop("checked", false);
         
            storeAddNewGuest(id,1,isSelected,event_id,'phone',is_duplicate);
            addToGuestPhoneList(id, isSelected,'0',first_name,last_name,email,profile); // App user = 1 for email (app user)
            $('#home_loader').css('display','none');


        }else{
            guestList = guestList.filter(guest => guest.id !== id);
            $('.add_phone_guest_'+id).remove();
            storeAddNewGuest(id,0,isSelected,event_id,'phone');
            $('#home_loader').css('display','none');
            console.log(guestList);
        }// App user = 0 for phone (non-app user)
    });



    // Declare guestList outside so it's globally accessible
function addToGuestList(id, preferBy, appUser,first_name,last_name,email,profile) {
    
        console.log("Adding to guest list:", { id, preferBy, appUser });
        const exists = guestList.some((contact) => contact.id === id);
        var is_duplicate=0;
        if (!exists) {
            is_duplicate=0;
            guestList.push({
                id: id,
                prefer_by: preferBy,
                app_user: appUser,
            });
            console.log("Contact added to guest list:", {
                id,
                preferBy,
                appUser,
            });
        } else {
            console.log("Contact already in guest list:", { id });
            is_duplicate=1;
        }

        


        var  profileImage="";
        if(profile!=""){
            profileImage = `<img src="${profile}" alt="Profile Image">` ;
        }else{
            profileImage =generateProfileImage(first_name, last_name);
        }
        var upper_view=$('.selected-contacts-list .guest-users').length;

        if(upper_view<4){
            const $modalBody = $('.selected-contacts-list');
            const contactHtml = `
                <div class="guest-users guest_yesvite add_yesvite_guest_${id}" data-id="${id}">
                    <div class="guest-user-img">
                       ${profileImage}
                        <a  class="close remove_new_added_user" data-id="${id}">
                            <svg width="19" height="18" viewBox="0 0 19 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect x="1.20312" y="1" width="16" height="16" rx="8" fill="#F73C71" />
                                <rect x="1.20312" y="1" width="16" height="16" rx="8" stroke="white" stroke-width="2" />
                                <path d="M6.86719 6.66699L11.5335 11.3333" stroke="white" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M6.8649 11.3333L11.5312 6.66699" stroke="white" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </a>
                    </div>
                    <h6>${first_name} ${last_name}</h6>
                </div>

            `;
            // $modalBody.find(".add_guest_seeall").first().before(contactHtml);
            if ($modalBody.find(".add_guest_seeall").length) {
                $modalBody.find(".add_guest_seeall").first().before(contactHtml);
            } else {
                $modalBody.append(contactHtml);
            }

            // $modalBody.append(contactHtml);
        }else{
            const $modalBody = $('.selected-contacts-list');
            var upper_see=$('.selected-contacts-list .add_guest_seeall').length;
            // alert(upper_see);
            if(upper_see==0&&is_duplicate==0){
                console.log(is_duplicate);

                const totalHtml = `
                <a class="guest-user d-block yesvite add_guest_seeall">
                    <div class="guest-user-img guest-total">
                        <span class="number" id="total-selected-email" data-count="1">+1</span>
                        <span class="content">Total</span>
                 </div>
                 <h6>See all</h6>
                </a>`;
                  $modalBody.append(totalHtml);
            }
            if(upper_see>0){
                console.log(is_duplicate);
                if(is_duplicate==0){
                    var initial= parseInt($('#total-selected-email').attr('data-count'));
                    var new_value= initial+1 ;
                 //    alert(initial);
                    $('#total-selected-email').attr('data-count',new_value);
                    $('#total-selected-email').text('+'+new_value);
                }

            }

             }




        console.log("Updated guest list:", guestList);
    }


function addToGuestPhoneList(id, preferBy, appUser,first_name,last_name,email,profile) {
        console.log("Adding to guest list:", { id, preferBy, appUser });
        const exists = guestList.some((contact) => contact.id === id);
        var is_duplicate_phone=0;

        if (!exists) {
            is_duplicate_phone=0;
            guestList.push({
                id: id,
                prefer_by: preferBy,
                app_user: appUser,
            });
            console.log("Contact added to guest list:", {
                id,
                preferBy,
                appUser,
            });
        } else {
            console.log("Contact already in guest list:", { id });
            is_duplicate_phone=1;
        }

        console.log(is_duplicate_phone);
        var  profileImage="";
        // if(profile!=""){
        //     profileImage = `<img src="${profile}" alt="Profile Image">` ;
        // }else{
            profileImage =generateProfileImage(first_name, last_name);
        // }
        var upper_phone_view=$('.selected-phone-list .guest-user-phone').length;

        if(upper_phone_view<4){
            const $modalBody = $('.selected-phone-list');
            const contactHtml = `
                <div class="guest-user-phone guest_yesvite add_phone_guest_${id}" data-id="${id}">
                    <div class="guest-user-img">
                       ${profileImage}
                        <a class="close remove_new_phone_added_user" data-id="${id}">
                            <svg width="19" height="18" viewBox="0 0 19 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect x="1.20312" y="1" width="16" height="16" rx="8" fill="#F73C71" />
                                <rect x="1.20312" y="1" width="16" height="16" rx="8" stroke="white" stroke-width="2" />
                                <path d="M6.86719 6.66699L11.5335 11.3333" stroke="white" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M6.8649 11.3333L11.5312 6.66699" stroke="white" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </a>
                    </div>
                    <h6>${first_name} ${last_name}</h6>
                </div>

            `;
            // $modalBody.append(contactHtml);

            if ($modalBody.find(".add_guest_phone_seeall").length) {
                $modalBody.find(".add_guest_phone_seeall").first().before(contactHtml);
            } else {
                $modalBody.append(contactHtml);
            }

        }else{
            const $modalBody = $('.selected-phone-list');
            var upper_see_phone=$('.selected-phone-list .add_guest_phone_seeall').length;
            if(upper_see_phone==0 && is_duplicate_phone==0){
                const totalHtml = `
                <a  class="guest-user d-block yesvite add_guest_phone_seeall">
                    <div class="guest-user-img guest-total">
                        <span class="number" id="total-selected-phone" data-count="1">+1</span>
                        <span class="content">Total</span>
                 </div>
                 <h6>See all</h6>
                </a>`;


                  $modalBody.append(totalHtml);

            }
            if(upper_see_phone>0){
                if(is_duplicate_phone==0){
                    var initial= parseInt($('#total-selected-phone').attr('data-count'));
                    var new_value= initial+1 ;
                 //    alert(initial);
                    $('#total-selected-phone').attr('data-count',new_value);
                    $('#total-selected-phone').text('+'+new_value);
                }

            }

             }

    }

$(document).on('click','.remove_new_added_user',function(){

    var user_id=$(this).attr('data-id');
    const event_id = $('#event_id').val();

    $('.add_yesvite_guest_'+user_id).remove();
    storeAddNewGuest(user_id,0,'',event_id,'yesvite');
    $(".contact-checkbox[data-id='" + user_id + "']").prop("checked", false);
    $(".phone-checkbox[data-id='" + user_id + "']").prop("checked", false);
    guestList = guestList.filter(guest => guest.id !== parseInt(user_id));



});

$(document).on('click','.remove_new_phone_added_user',function(){

    var user_id=$(this).attr('data-id');
    const event_id = $('#event_id').val();
    storeAddNewGuest(user_id,0,'',event_id,'phone');
    $('.add_phone_guest_'+user_id).remove();
    $(".phoneContact-checkbox[data-id='" + user_id + "']").prop("checked", false);
    // $(".phone-checkbox[data-id='" + user_id + "']").prop("checked", false);
    guestList = guestList.filter(guest => guest.id !== parseInt(user_id));



});

});

 $(document).on("click", ".add_guest", function (e) {
        e.preventDefault();
        console.log("Guest list before submit:", guestList);
        console.log("Sending guest list:", guestList);
        $.ajax({
            url: base_url + "event_wall/send-invitation", // Your Laravel route
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            data: {
                event_id: $("#event_id").val(), // Event ID from a hidden input
                guest_list: guestList,
            },
            success: function (response) {
                if (response.status === 1) {
                    window.location.reload();
                    toastr.success('Invited successfully');
                    // alert(response.message); // Show success message
                    guestList = []; // Clear guest list after successful submission
                } else {
                    // alert(response.message); // Show error message
                }
            },
            error: function (xhr) {
                alert("Something went wrong. Please try again."); // Handle AJAX errors
            },
        });
});
$(document).on("keyup", ".search_contact", function () {
    console.log($(this).val())
    var searchQuery = $(this).val().toLowerCase(); // Get the search input value and convert it to lowercase

    // If search is empty, show all contacts
    if (searchQuery === "") {
        $(".contact").show(); // Show all contacts
    } else {
        // Iterate through each invite-contact
        $(".contactslist").each(function () {
            var contactName = $(this)
                .find(".contact_search")
                .data("search")
                .toLowerCase(); // Get the data-search attribute

            // If the search query matches part of the contact name, show the contact
            if (contactName.indexOf(searchQuery) !== -1) {
                $(this).show(); // Show this contact
            } else {
                $(this).hide(); // Hide this contact
            }
        });
    }
});

// $(document).on('click','.see-all-guest-right-btn',function(){
//     $.ajax({
//         url: base_url + "event_wall/fetch_all_invited_user", // Your Laravel route
//         method: "POST",
//         headers: {
//             "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
//         },
//         data: {
//             event_id: $("#event_id").val(), // Event ID from a hidden input
//             guest_list: guestList,
//         },
//         success: function (response) {
//             if (response.status === 1) {
//                 window.location.reload();
//                 toastr.success('Invited successfully');
//                 // alert(response.message); // Show success message
//                 guestList = []; // Clear guest list after successful submission
//             } else {
//                 alert(response.message); // Show error message
//             }
//         },
//         error: function (xhr) {
//             alert("Something went wrong. Please try again."); // Handle AJAX errors
//         },
//     });
// });
