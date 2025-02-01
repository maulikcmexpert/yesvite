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
                    alert(response.message);
                }
            },
            error: function (xhr) {
                console.error(xhr.responseText);
                alert("An error occurred. Please try again.");
            },
        });
    });
    $(document).on("click", "#CommentlikeButton", function () {
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
        const parentCommentId =
            $(".commented-user-wrp.active").data("comment-id") || null; // Find active comment if replying

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
                    const profileImage =
                        data.profile || generateProfileImage(data.username);

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

                    const commentCountElement = $(`#comment_${eventPostId}`);
                    const currentCount =
                        parseInt(commentCountElement.text()) || 0;
                    commentCountElement.text(`${currentCount + 1} Comments`);
                    // Clear input field
                    commentInput.val("");

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
                }
            },
            error: function (xhr) {
                console.error(xhr.responseText);
                alert("An error occurred. Please try again.");
            },
        });
    });

    $(document).on("click", ".commented-user-reply-btn", function () {
        // Find the closest '.posts-card-main-comment' wrapper (the main post container)
        const parentWrapper = $(this)
            .closest(".posts-card-show-all-comments-wrp")
            .prev(".posts-card-main-comment");

        if (!parentWrapper.length) {
            console.error("Parent wrapper not found!");
            return;
        }

        // Find the username and comment ID from the current comment being replied to
        const parentName = $(this)
            .closest(".commented-user-wrp")
            .find("h3")
            .text()
            .trim();
        const parentId = $(this)
            .closest(".commented-user-wrp")
            .data("comment-id");

        // Debugging information
        console.log("Parent Wrapper:", parentWrapper);
        console.log("Parent Name:", parentName);
        console.log("Parent ID:", parentId);

        // Set the active class on the currently selected comment
        $(".commented-user-wrp").removeClass("active"); // Remove 'active' from all comments
        $(this).closest(".commented-user-wrp").addClass("active"); // Add 'active' to the current comment

        // Find the comment box inside the parent wrapper and insert the username
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
    var searchQuery = $(this).val().toLowerCase(); // Get the search input value and convert it to lowercase

    // If search is empty, show all contacts
    if (searchQuery === "") {
        $(".phone-contact").show(); // Show all contacts
    } else {
        // Iterate through each invite-contact
        $(".phone-contact").each(function () {
            var contactName = $(this)
                .find(".phone-search")
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

var allContactsSuccess = false;
let selectedContacts = [];
let selectedPhoneContacts = [];
$(document).ready(function () {
    const yesviteUrl = base_url + "event_wall/get_yesviteContact"; // URL for yesvite contacts
    //const phoneUrl = base_url + "event_wall/get_phoneContact"; // URL for phone contacts
    const event_id = $('#event_id').val();

    $("#allcontact").on("click", function () {
        localStorage.removeItem("selectedContacts");
        localStorage.removeItem("selectedPhoneContacts");
        if (allContactsSuccess) {
            return;
        }
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

                const invitedUsers = response.invited_users;
                // selectedContacts = response.selected_yesvite_user;
                // selectedPhoneContacts = response.selected_phone_user;
                // console.log({selectedContacts,selectedPhoneContacts})
            },
            error: function () {
                toastr.error("No Contacts Found");
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

// // Handle checkbox states
// function handleCheckboxState(contact, checkboxSelector) {
//     if (contact.selectedEmail) {
//         $(
//             `${checkboxSelector}[data-id="${contact.id}"][data-type="email"]`
//         ).prop("checked", true);
//         $(
//             `${checkboxSelector}[data-id="${contact.id}"][data-type="phone"]`
//         ).prop("checked", false);
//     }
//     if (contact.selectedPhone) {
//         $(
//             `${checkboxSelector}[data-id="${contact.id}"][data-type="phone"]`
//         ).prop("checked", true);
//         $(
//             `${checkboxSelector}[data-id="${contact.id}"][data-type="email"]`
//         ).prop("checked", false);
//     }
// }

// // Event listener for contact checkboxes
// $(document).on("change", ".contact-checkbox", function () {
//     handleCheckboxChange(
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
//                     <a href="#" class="close">
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
        const id = $(this).data("id");
        const isEmailSelected =
            $(this).data("type") === "email" && $(this).is(":checked");
        const isPhoneSelected =
            $(this).data("type") === "phone" && $(this).is(":checked");

        // Add to the guest list if either email or phone is selected
        const first_name = $(this).data("name");
        const last_name = $(this).data("last");
        const email = $(this).data("email");
        const profile = $(this).data("profile");
        // console.log(
        //     `Checkbox changed for ID: ${id}, email selected: ${isEmailSelected}, phone selected: ${isPhoneSelected}`
        // );
        if( $(this).is(":checked")){
            addToGuestList(id, isEmailSelected ? "email" : "phone", 1,first_name,last_name,email,profile); // App user = 1 for email (app user)
        }else{
            $('.add_yesvite_guest_'+id).remove();
        }
        
    });

    // Event listener for phone contact checkboxes
    $(document).on("change", ".phoneContact-checkbox", function () {
        const id = $(this).data("id");
        const isEmailSelected =
            $(this).data("type") === "email" && $(this).is(":checked");
        const isPhoneSelected =
            $(this).data("type") === "phone" && $(this).is(":checked");

        // Add to the guest list if either email or phone is selected

        console.log(
            `Checkbox changed for ID: ${id}, email selected: ${isEmailSelected}, phone selected: ${isPhoneSelected}`
        );
        // Add to the guest list, prefer email if selected, else prefer phone
        addToGuestList(id, isEmailSelected ? "email" : "phone", 0); // App user = 0 for phone (non-app user)
    });

    // Declare guestList outside so it's globally accessible
    let guestList = [];

function addToGuestList(id, preferBy, appUser,first_name,last_name,email,profile) {
        console.log("Adding to guest list:", { id, preferBy, appUser });
        const exists = guestList.some((contact) => contact.id === id);
        if (!exists) {
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
        }
        var profileImage="";
        if(profile!=""){
            profileImage = profile;      
        }else{
            profileImage =generateProfileImage(first_name, last_name);      
        }
        const $modalBody = $('.selected-contacts-list');
        const contactHtml = `
            <div class="guest-user add_yesvite_guest_${id}" data-id="${id}">
                <div class="guest-user-img">
                   ${profileImage}
                    <a href="#" class="close remove_new_added_user" data-id="${id}">
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
        $modalBody.append(contactHtml);
        console.log("Updated guest list:", guestList); 
}

    $(document).on("click", ".add_guest", function (e) {
        e.preventDefault();

        console.log("Guest list before submit:", guestList);
        // Check if there are any guests to send

        // Log guestList before sending the request
        console.log("Sending guest list:", guestList);

        // Send data to the server via AJAX
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
                    alert(response.message); // Show success message
                    guestList = []; // Clear guest list after successful submission
                } else {
                    alert(response.message); // Show error message
                }
            },
            error: function (xhr) {
                alert("Something went wrong. Please try again."); // Handle AJAX errors
            },
        });
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
