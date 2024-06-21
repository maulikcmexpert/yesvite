// alert();

import { initializeApp } from "https://www.gstatic.com/firebasejs/10.12.2/firebase-app.js";

import {
    getDatabase,
    ref,
    child,
    get,
    push,
    set,
    onChildAdded,
    onChildChanged,
    update,
    off,
} from "https://www.gstatic.com/firebasejs/10.12.2/firebase-database.js";

import {
    getStorage,
    ref as storageRef,
    uploadString,
    getDownloadURL,
    uploadBytes,
} from "https://www.gstatic.com/firebasejs/10.12.2/firebase-storage.js";

// Firebase configuration
const firebaseConfig = {
    apiKey: "AIzaSyAVgJQewYO8h1-_z2mrjaATCqJ4NH8eeCI",
    authDomain: "yesvite-976cd.firebaseapp.com",
    databaseURL: "https://yesvite-976cd-default-rtdb.firebaseio.com",
    projectId: "yesvite-976cd",
    storageBucket: "yesvite-976cd.appspot.com",
    messagingSenderId: "273430667581",
    appId: "1:273430667581:web:d5cc6f6c1cc9829de9e554",
    measurementId: "G-99SYL4VLEF",
};

$.ajaxSetup({
    headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
});
// Initialize Firebase
const app = initializeApp(firebaseConfig);
const database = getDatabase(app);
const storage = getStorage(app);

const senderUser = $(".senderUser").val();
const senderUserName = $(".senderUserName").val();
const base_url = $("#base_url").val();
const userRef = ref(database, `users/${senderUser}`);
// Function to get messages between two users
async function getMessages(userId1, userId2) {
    const messagesRef = ref(database, "Messages");
    try {
        const snapshot = await get(messagesRef);
        const messages = snapshot.val();
        const result = [];
        for (const key in messages) {
            if (
                messages[key].users &&
                messages[key].users.includes(userId1) &&
                messages[key].users.includes(userId2)
            ) {
                result.push({ key: key, ...messages[key] });
            }
        }
        return result;
    } catch (error) {
        console.error("Error retrieving messages:", error);
    }
}

// Function to get user data
async function getUser(userId) {
    const userRef = ref(database, "users/" + userId);
    try {
        const snapshot = await get(userRef);
        return snapshot.val();
    } catch (error) {
        console.error("Error retrieving user data:", error);
    }
}

async function updateOverview(userId, conversationId, data) {
    const overviewRef = ref(database, `overview/${userId}/${conversationId}`);
    await update(overviewRef, data);
}

// Function to add a new message to the database
async function addMessage(conversationId, messageData, contactId) {
    console.log({ conversationId });
    const messagesRef = ref(database, `Messages/${conversationId}/message`);
    await push(messagesRef, messageData);
    const usersRef = ref(database, "Messages/" + conversationId + "/users");
    set(usersRef, {
        0: contactId,
        1: senderUser,
    });
}
// Function to add a new message to the database
async function addMessageToGroup(
    conversationId,
    messageData,
    newGroupMembers = [],
    groupName = ""
) {
    console.log({ conversationId });
    const messagesRef = ref(database, `Groups/${conversationId}/message`);
    await push(messagesRef, messageData);
    if (newGroupMembers.length > 0) {
        const usersRef = ref(database, "Groups/" + conversationId + "/users");
        set(usersRef, newGroupMembers);

        const groupInfoRef = ref(
            database,
            "Groups/" + conversationId + "/groupInfo"
        );

        let groupInfo = {
            conversationId: conversationId,
            createdById: senderUser,
            createdTimeStamp: Date.now(),
            groupDescription: "",
            groupName,
            profiles: {},
            usersStatus: {},
        };
        let i = 0;
        for (const userId of newGroupMembers) {
            const user = await getUser(userId);
            groupInfo.profiles[i] = {
                id: userId,
                image: user?.userProfile,
                isAdmin: userId === senderUser ? "1" : "0",
                leave: false,
                name: user.userName,
            };
            groupInfo.usersStatus[userId] = Date.now();
            i++;
        }

        await set(groupInfoRef, groupInfo);
    }
}
// Function to handle a new conversation
function handleNewConversation(snapshot) {
    const newConversation = snapshot.val();
    // console.log("New conversation added:", newConversation);
    if (newConversation.conversationId == undefined) {
        return;
    }
    const conversationElement = $(
        `.conversation-${newConversation.conversationId}`
    );
    if (conversationElement.length > 0) {
        // Update existing conversation element
        conversationElement
            .find(".user-detail h3")
            .text(newConversation.contactName);
        conversationElement
            .find(".user-detail .last-message")
            .text(newConversation.lastMessage);
        conversationElement
            .find(".user-detail .time-ago")
            .text(timeago.format(newConversation.timeStamp));
        const badgeElement = conversationElement.find(".user-detail .badge");
        badgeElement.text(newConversation.unReadCount);
        if (newConversation.unReadCount == 0) {
            badgeElement.addClass("d-none");
        } else {
            badgeElement.removeClass("d-none");
        }
    } else {
        // Add new conversation element
        $.ajax({
            url: base_url + "getConversation",
            data: { messages: newConversation },
            method: "post",
            success: function (res) {
                $(".chat-list").prepend(res);
                var msgLists = document.getElementsByClassName("msg-list");
                removeSelectedMsg();
                // Add "active" class to the first element with class "msg-list"
                if (msgLists.length > 0) {
                    msgLists[0].classList.add("active");
                }
            },
        });
    }

    const selectedConversationId = $(".selected_conversasion").val();
    if (selectedConversationId === newConversation.conversationId) {
        updateOverview(senderUser, newConversation.conversationId, {
            unRead: false,
            unReadCount: 0,
        });
    }
}

function removeSelectedMsg() {
    var msgLists = document.getElementsByClassName("msg-list");
    for (var i = 0; i < msgLists.length; i++) {
        msgLists[i].classList.remove("active");
    }
}
// Function to handle changes to existing conversations in the overview
function handleConversationChange(snapshot) {
    const updatedConversation = snapshot.val();
    console.log("Conversation changed:", updatedConversation);
    handleNewConversation(snapshot);
}

// Function to update the chat UI
async function updateChat(user_id) {
    $(".msg-lists").html("");
    const selected_user = await getUser(user_id);
    if (!selected_user) return;

    const messageTime = selected_user.userLastSeen
        ? new Date(selected_user.userLastSeen)
        : new Date();
    let lastseen =
        selected_user.userStatus == "offline"
            ? timeago.format(messageTime)
            : "Online";
    $("#selected-user-lastseen").html(lastseen);
    $("#selected-user-name").html(selected_user.userName);

    const profileImageUrl = selected_user.userProfile;
    $("#selected-user-profile").attr("src", profileImageUrl);

    if (
        profileImageUrl.endsWith(".jpg") ||
        profileImageUrl.endsWith(".jpeg") ||
        profileImageUrl.endsWith(".png")
    ) {
        $("#selected-user-profile").attr("src", profileImageUrl);
    } else {
        const initials = selected_user.userName
            .split(" ")
            .map((word) => word[0].toUpperCase())
            .join("");
        const fontColor = "fontcolor" + initials[0].toUpperCase();

        $("#selected-user-profile").replaceWith(
            `<h5 class="${fontColor}">${initials}</h5>`
        );
    }

    $(".selected_name").val(selected_user.userName);

    $(".selected_conversasion").val($(".selected_id").val());
    const conversationId = $(".selected_id").val();
    console.log({ conversationId });

    update(userRef, { userChatId: conversationId });

    const messagesRef = ref(database, `Messages/${conversationId}/message`);
    const selecteduserTypeRef = ref(database, `users/${user_id}`);
    off(messagesRef);
    off(selecteduserTypeRef);
    onChildAdded(messagesRef, async (snapshot) => {
        addMessageToList(snapshot.key, snapshot.val());

        const selectedConversationId = $(".selected_conversasion").val();
        if (selectedConversationId === conversationId) {
            await updateOverview(senderUser, conversationId, {
                unRead: false,
                unReadCount: 0,
            });
        }
    });
    onChildChanged(selecteduserTypeRef, async (snapshot) => {
        if (
            snapshot.key === "userTypingStatus" &&
            snapshot.val() == "Typing..."
        ) {
            const snapshot = await get(selecteduserTypeRef);
            const selectedUserData = snapshot.val();
            console.log({ selectedUserData });

            if (selectedUserData.userChatId == conversationId) {
                $(".typing").text("Typing...");
            }
        } else {
            $(".typing").text("");
        }
    });
}

async function updateChatfromGroup(conversationId) {
    $(".msg-lists").html("");
    const messagesRef = ref(database, `Groups/${conversationId}/message`);
    off(messagesRef);
    onChildAdded(messagesRef, async (snapshot) => {
        addMessageToList(snapshot.key, snapshot.val());

        const selectedConversationId = $(".selected_conversasion").val();
        if (selectedConversationId === conversationId) {
            await updateOverview(senderUser, conversationId, {
                unRead: false,
                unReadCount: 0,
            });
        }
    });

    const groupInfoRef = ref(database, `Groups/${conversationId}/groupInfo`);
    const snapshot = await get(groupInfoRef);
    const groupInfo = snapshot.val();

    $("#selected-user-lastseen").html(""); // Group doesn't have a last seen
    $("#selected-user-name").html(groupInfo.groupName);
    $("#selected-user-profile").attr("src", "path_to_group_default_image"); // Set a default group image if needed
    $(".selected_name").val(groupInfo.groupName);

    $(".selected_conversasion").val(conversationId);
    update(userRef, { userChatId: conversationId });
}

// Initialize event listeners
$(document).on("click", ".msg-list", async function () {
    removeSelectedMsg();
    $(this).addClass("active");
    const isGroup = $(this).attr("data-group");
    const conversationId = $(this).attr("data-msgKey");
    $(".selected_id").val($(this).attr("data-msgKey"));
    $("#isGroup").val(isGroup);
    if (isGroup == "1") {
        await updateChatfromGroup(conversationId);
    } else {
        const userId = $(this).attr("data-userid");
        $(".selected_message").val(userId);
        await updateOverview(senderUser, conversationId, {
            unRead: false,
            unReadCount: 0,
        });
        await updateChat(userId);
    }
});

// Initial chat update
if ($("#isGroup").val() == "1") {
    updateChatfromGroup($(".selected_id").val());
} else {
    updateChat($(".selected_message").val());
}

function scrollFunction() {
    const container = document.getElementById("msgBody");
    const element = document.getElementById("msgbox");
    const offsetTop = element.offsetTop - container.offsetTop;
    container.scroll({
        top: offsetTop,
        behavior: "smooth",
    });
}
$(".send-message").on("keypress", async function (e) {
    if (e.which === 13) {
        var isGroup = $("#isGroup").val();
        const message = $(this).val();
        if (message.trim() !== "") {
            $(this).val(""); // Clear the input field
            const messageData = {
                data: message,
                timeStamp: Date.now(),
                isDelete: {},
                isReply: "0",
                isSeen: false,
                react: "",
                senderId: senderUser,
                senderName: senderUserName,
                status: {},
            };

            if (isGroup == "1") {
                const conversationId = $(".selected_id").val();
                const groupName = $(".selected_name").val();

                // Fetch group members from Firebase
                const groupMembersRef = ref(
                    database,
                    `Groups/${conversationId}/users`
                );
                const groupMembersSnapshot = await get(groupMembersRef);
                const newGroupMembers = groupMembersSnapshot.val();
                console.log({ newGroupMembers });
                await addMessageToGroup(conversationId, messageData);

                // Update all group members' overview
                for (const userId of newGroupMembers) {
                    await updateOverview(userId, conversationId, {
                        lastMessage: `${senderUserName}: ${message}`,
                        unReadCount: userId === senderUser ? 0 : 1,
                        timeStamp: Date.now(),
                    });
                }
            } else {
                const selectedMessageId = $(".selected_id").val();
                const receiverId = $(".selected_message").val();
                const receiverName = $(".selected_name").val();

                messageData.receiverId = receiverId;
                messageData.receiverName = receiverName;

                await addMessage(selectedMessageId, messageData, receiverId);

                await updateOverview(senderUser, selectedMessageId, {
                    lastMessage: `${senderUserName}: ${message}`,
                    timeStamp: Date.now(),
                });
                const receiverSnapshot = await get(
                    ref(database, `overview/${receiverId}/${selectedMessageId}`)
                );
                await updateOverview(receiverId, selectedMessageId, {
                    lastMessage: `${senderUserName}: ${message}`,
                    unReadCount: (receiverSnapshot.val().unReadCount || 0) + 1,
                    timeStamp: Date.now(),
                });

                const conversationElement = $(
                    `.conversation-${selectedMessageId}`
                );
                conversationElement.prependTo(".chat-list");
            }
        }
    }
});

// Function to add a message to the UI list
function addMessageToList(key, messageData) {
    const messageElement = `
        <li class="${
            senderUser === messageData.senderId ? "receiver" : "sender"
        }" id="message-${key}">
            <p>${messageData.data}</p>
            <span class="time">${timeago.format(
                new Date(messageData.timeStamp).toLocaleTimeString()
            )}</span>
        </li>
       
    `;
    $(".msg-lists").append(messageElement);
    scrollFunction();
}
var selectedUserIds = [];
$("#search-user")
    .autocomplete({
        source: function (request, response) {
            $.ajax({
                url: base_url + "autocomplete-users",
                dataType: "json",
                data: { term: request.term },
                success: function (data) {
                    response(
                        data.map((item) => ({
                            label: item.name,
                            value: item.name,
                            userId: item.id,
                            imageUrl: item.profile,
                            email: item.email,
                        }))
                    );
                },
            });
        },
        minLength: 2,
        select: function (event, ui) {
            const selectedUserId = ui.item.userId;
            const selectedUserName = ui.item.label;
            // const $tag = $("<div>", {
            //     class: "tag",
            //     "data-user-id": selectedUserId,
            // })
            //     .text(selectedUserName)
            //     .append($("<span>", { class: "close-btn" }).html("&times;"));
            // $("#selected-tags-container").append($tag);
            // $("#selected-user-id").val(selectedUserId);

            // // Handle UI updates for selected users
            // handleSelectedUsers();

            if (!selectedUserIds.includes(selectedUserId)) {
                selectedUserIds.push(selectedUserId);
                updateSelectedUserIds();

                const $tag = $("<div>", {
                    class: "tag",
                    "data-user-id": selectedUserId,
                })
                    .text(selectedUserName)
                    .append(
                        $("<span>", { class: "close-btn" }).html("&times;")
                    );
                $("#selected-tags-container").append($tag);

                handleSelectedUsers();
            }

            setTimeout(() => {
                $("#search-user").val("");
            }, 100);
        },
    })
    .data("ui-autocomplete")._renderItem = function (ul, item) {
    const $li = $("<li>");
    const $divMain = $("<div>").addClass("suggestion-item chat-data d-flex");
    const $divImage = $("<div>").addClass("user-img position-relative");
    const $divName = $("<div>").addClass("user-detail d-block ms-3");
    const $img = $("<img>")
        .attr("src", item.imageUrl)
        .addClass("user-avatar img-fluid");
    const $span = $("<h3>").text(item.label);

    $divImage.append($img);
    $divName.append($("<div>")).append($span);
    $divMain.append($divImage).append($divName);
    $li.append($divMain).appendTo(ul);

    return $li;
};

function updateSelectedUserIds() {
    $("#selected-user-id").val(selectedUserIds.join(","));
}

$(document).on("click", ".close-btn", function () {
    const $tag = $(this).parent(".tag");
    const userId = $tag.data("user-id");

    // $(this).parent(".tag").remove();
    selectedUserIds = selectedUserIds.filter((id) => id !== userId);
    updateSelectedUserIds();
    $tag.remove();
    // Handle UI updates for selected users
    handleSelectedUsers();
});

function handleSelectedUsers() {
    const tagCount = $("#selected-tags-container .tag").length;

    if (tagCount === 1) {
        const $singleTag = $("#selected-tags-container .tag");
        const userName = $singleTag
            .clone()
            .children(".close-btn")
            .remove()
            .end()
            .text()
            .trim();
        const userImgSrc = $singleTag.find("img").attr("src");
        $(".selected-user-name").text(userName);
        $(".selected-user-img").attr("src", userImgSrc);
        $(".selected-user-email").attr("href", "mailto:").find("span").text("");

        $(".empty-massage").hide();
        $(".chat-user").removeClass("d-none");
        $(".multi-chat").addClass("d-none");
    } else if (tagCount >= 2) {
        $(".empty-massage").hide();
        $(".chat-user").addClass("d-none");
        $(".multi-chat").removeClass("d-none");
        const $tags = $("#selected-tags-container .tag");
        let groupNames = "";
        $tags.each(function (index) {
            if (index < 2) {
                const userName = $(this).text().trim();
                const userImgSrc = $(this).find("img").attr("src");
                groupNames += `<div class="multi-img grp-img">
                                    <img src="${userImgSrc}" alt="">
                                </div>`;
            }
        });
        $(".multi-chat .img-wrp").html(groupNames);
        const moreCount = tagCount - 2;

        if (moreCount > 0) {
            let moreimg = `<div class="multi-img more-img">
                <span>+${moreCount}</span>
            </div>`;
            $(".multi-chat .img-wrp").append(moreimg);
        } else {
            $(".more-img").remove();
        }
        // Set multiple names in the selected-user-name
        const allNames = $tags
            .map(function () {
                return $(this)
                    .clone()
                    .children(".close-btn")
                    .remove()
                    .end()
                    .text()
                    .trim();
            })
            .get()
            .join(", ");
        $(".selected-user-name").text(allNames);
    } else {
        $(".chat-user").addClass("d-none");
        $(".multi-chat").addClass("d-none");
        $(".more-img").addClass("d-none");
        $(".empty-massage").show();
    }
}
// Initialize overview listeners
const overviewRef = ref(database, `overview/${senderUser}`);
onChildAdded(overviewRef, handleNewConversation);
onChildChanged(overviewRef, handleConversationChange);

// Function to find or create a conversation
async function findOrCreateConversation(
    currentUserId,
    contactId,
    contactName,
    receiverProfile
) {
    const overviewRef = ref(database, `overview/${currentUserId}`);
    const snapshot = await get(overviewRef);

    if (snapshot.exists()) {
        const overviewData = snapshot.val();
        for (const conversationId in overviewData) {
            if (overviewData[conversationId].contactId === contactId) {
                return conversationId;
            }
        }
    }

    const newConversationRef = push(child(ref(database), "overview"));
    const newConversationId = newConversationRef.key;

    const newConversationData = {
        contactId: contactId,
        contactName: contactName,
        conversationId: newConversationId,
        group: "0",
        lastMessage: "",
        lastSenderId: currentUserId,
        receiverProfile: receiverProfile,
        timeStamp: Date.now(),
        unRead: true,
        unReadCount: 1,
    };

    await set(
        ref(database, `overview/${currentUserId}/${newConversationId}`),
        newConversationData
    );

    const receiverConversationData = {
        contactId: currentUserId,
        contactName: senderUserName,
        conversationId: newConversationId,
        group: "0",
        lastMessage: "",
        lastSenderId: currentUserId,
        receiverProfile: receiverProfile,
        timeStamp: Date.now(),
        unRead: true,
        unReadCount: 1,
    };

    await set(
        ref(database, `overview/${contactId}/${newConversationId}`),
        receiverConversationData
    );

    return newConversationId;
}

// Function to find or create a group conversation
async function findOrCreateGroupConversation(
    currentUserId,
    newGroupMembers,
    groupName
) {
    const overviewRef = ref(database, `overview/${currentUserId}`);
    const snapshot = await get(overviewRef);

    if (snapshot.exists()) {
        const overviewData = snapshot.val();
        for (const conversationId in overviewData) {
            if (overviewData[conversationId].groupName === groupName) {
                return conversationId;
            }
        }
    }

    const newConversationRef = push(child(ref(database), "overview"));
    const newConversationId = newConversationRef.key;

    const newConversationData = {
        contactId: newConversationId,
        contactName: groupName,
        conversationId: newConversationId,
        group: "1",
        lastMessage: "",
        lastSenderId: currentUserId,
        receiverProfile: "",
        timeStamp: Date.now(),
        unRead: true,
        unReadCount: 1,
    };

    await set(
        ref(database, `overview/${currentUserId}/${newConversationId}`),
        newConversationData
    );

    for (const memberId of newGroupMembers) {
        const memberConversationData = {
            contactId: newConversationId,
            contactName: groupName,
            conversationId: newConversationId,
            group: "1",
            lastMessage: "",
            lastSenderId: currentUserId,
            receiverProfile: "",
            timeStamp: Date.now(),
            unRead: true,
            unReadCount: 1,
        };

        await set(
            ref(database, `overview/${memberId}/${newConversationId}`),
            memberConversationData
        );
    }

    return newConversationId;
}
// Event listener for sending a new message
$("#new_message").on("keypress", async function (e) {
    if (e.which === 13) {
        const tagCount = $("#selected-tags-container .tag").length;
        if (tagCount > 1) {
            const currentUserId = senderUser;
            // const groupName = $("#group-name").val(); // Assuming you have an input for group name
            const groupName = "Pratik test"; // Assuming you have an input for group name
            const newGroupMembers = $("#selected-user-id")
                .val()
                .split(",")
                .map((id) => id.trim());
            newGroupMembers.push(senderUser);
            const message = $(this).val();

            const conversationId = await findOrCreateGroupConversation(
                currentUserId,
                newGroupMembers,
                groupName
            );

            const messageData = {
                data: message,
                timeStamp: Date.now(),
                isDelete: {},
                isReply: "0",
                isSeen: false,
                react: "",
                receiverId: newGroupMembers,
                receiverName: groupName,
                replyData: {},
                senderId: senderUser,
                senderName: senderUserName,
                status: {},
            };

            await addMessageToGroup(
                conversationId,
                messageData,
                newGroupMembers,
                groupName
            );

            for (const memberId of newGroupMembers) {
                await updateOverview(currentUserId, conversationId, {
                    lastMessage: `${senderUserName}: ${message}`,
                    timeStamp: Date.now(),
                });

                const receiverSnapshot = await get(
                    ref(database, `overview/${memberId}/${conversationId}`)
                );
                await updateOverview(memberId, conversationId, {
                    lastMessage: `${senderUserName}: ${message}`,
                    unReadCount: (receiverSnapshot.val().unReadCount || 0) + 1,
                    timeStamp: Date.now(),
                });
            }
        } else {
            const currentUserId = senderUser;
            const contactId = $("#selected-user-id").val();
            const contactName = $(".selected-user-name").html();
            const receiverProfile = $(".selected-user-img").attr("src");

            const conversationId = await findOrCreateConversation(
                currentUserId,
                contactId,
                contactName,
                receiverProfile
            );
            const message = $(this).val();
            const selectedMessageId = conversationId;
            $(".selected_id").val(conversationId);
            $(".selected_message").val(contactId);
            $(".selected_name").val(contactName);

            updateChat(contactId);
            if (message.trim() !== "") {
                const messageData = {
                    data: message,
                    timeStamp: Date.now(),
                    isDelete: {},
                    isReply: "0",
                    isSeen: false,
                    react: "",
                    receiverId: contactId,
                    receiverName: contactName,
                    replyData: {},
                    senderId: senderUser,
                    senderName: senderUserName,
                    status: {},
                };

                await addMessage(selectedMessageId, messageData, contactId);

                await updateOverview(currentUserId, selectedMessageId, {
                    lastMessage: `${senderUserName}: ${message}`,
                    timeStamp: Date.now(),
                });

                const receiverSnapshot = await get(
                    ref(database, `overview/${contactId}/${selectedMessageId}`)
                );
                await updateOverview(contactId, selectedMessageId, {
                    lastMessage: `${senderUserName}: ${message}`,
                    unReadCount: (receiverSnapshot.val().unReadCount || 0) + 1,
                    timeStamp: Date.now(),
                });
            }
        }
        $(this).val("");
        $("#msgBox").modal("hide");
    }
});







////**************************************************This Code is for image and video both....************************************
$(".preview_img").hide();
$("#send_image").hide();
$(".upload-box").change(function () {
    var curElement = $(".preview_img");
    var file = this.files[0];
    if (file) {
        var reader = new FileReader();

        if (file.type.match("image.*")) {
            reader.onload = function (e) {
                $(".preview_img").show();
                curElement.attr("src", e.target.result);
                // $("#upload_name").text(file.name);
                $("#send_image").show();
                // $('.dropdown-menu').hide();
            };

            reader.readAsDataURL(file);
        } else if (file.type.match("video.*")) {
            // Handling video files
            var curElement = $(".preview_img");

            curElement.attr("src", URL.createObjectURL(file));

            $(".preview_img").show();
            $("#send_image").show();
        } else {
            // reader.onload = function (e) {
            //     var curElement = $(".preview_img");
            //     curElement.attr("src", Uint8Array(e.target.result));
            // };

            // $(".preview_img").show();
            // $("#send_image").show();
        }
    } else {
        $(".preview_img").hide();
        curElement.attr("src", "");
    }
});

$("#send_image").on("click", async function () {
    $("#send_image").hide();
    $(".preview_img").hide();

    const previewImg = $(".preview_img");
    const imageUrl = previewImg.attr("src");

    console.log(imageUrl);

    if (!imageUrl) {
        alert("Please select an image to send.");
        return;
    }

    var isGroup = $("#isGroup").val();
    const message = $(this).val();
    let storagePath;

    // Determine file type and set the storage path
    if (imageUrl.startsWith("data:image/")) {
        storagePath = `Images/198/${Date.now()}_${senderUser}.png`;
    } else if (imageUrl.startsWith("blob:http:/")) {
        storagePath = `Video/198/${Date.now()}_${senderUser}.mp4`;
    } else {
        storagePath = `Files/203/${Date.now()}_${senderUser}`;
    }

    console.log(storagePath);

    // Upload file to Firebase Storage
    const fileRef = storageRef(storage, storagePath);
    try {
        if (imageUrl.startsWith("data:image/")) {
            await uploadString(fileRef, imageUrl, "data_url");
        } else {
            const response = await fetch(imageUrl);
            const blob = await response.blob();
            await uploadBytes(fileRef, blob);
        }
        const downloadURL = await getDownloadURL(fileRef);

        const messageData = {
            data: downloadURL,
            timeStamp: Date.now(),
            isDelete: {},
            isReply: "0",
            isSeen: false,
            react: "",
            senderId: senderUser,
            senderName: senderUserName,
            status: {},
            // imageUrl: downloadURL, // Use the download URL from Firebase Storage
        };

        if (isGroup == "1") {
            const conversationId = $(".selected_id").val();
            const groupName = $(".selected_name").val();

            // Fetch group members from Firebase
            const groupMembersRef = ref(
                database,
                `Groups/${conversationId}/users`
            );
            const groupMembersSnapshot = await get(groupMembersRef);
            const newGroupMembers = groupMembersSnapshot.val();
            console.log({ newGroupMembers });
            await addMessageToGroup(conversationId, messageData);

            // Update all group members' overview
            for (const userId of newGroupMembers) {
                await updateOverview(userId, conversationId, {
                    lastMessage: `${senderUserName}: ${downloadURL}`,
                    unReadCount: userId === senderUser ? 0 : 1,
                    timeStamp: Date.now(),
                });
            }
        } else {
            const selectedMessageId = $(".selected_id").val();
            const receiverId = $(".selected_message").val();
            const receiverName = $(".selected_name").val();

            messageData.receiverId = receiverId;
            messageData.receiverName = receiverName;

            await addMessage(selectedMessageId, messageData, receiverId);

            await updateOverview(senderUser, selectedMessageId, {
                lastMessage: `${senderUserName}: ${downloadURL}`,
                timeStamp: Date.now(),
            });
            const receiverSnapshot = await get(
                ref(database, `overview/${receiverId}/${selectedMessageId}`)
            );
            await updateOverview(receiverId, selectedMessageId, {
                lastMessage: `${senderUserName}: ${downloadURL}`,
                unReadCount: (receiverSnapshot.val().unReadCount || 0) + 1,
                timeStamp: Date.now(),
            });

            const conversationElement = $(`.conversation-${selectedMessageId}`);
            conversationElement.prependTo(".chat-list");
        }

        // If everything is successful, log success
        console.log("Upload successful");
    } catch (error) {
        console.error("Error uploading file: ", error);
        // alert("Failed to upload file. Please try again.");
    }
});
////**************************************************This Code is for image and video both....************************************ END





//*******************************This Code is for audio record....**************************************
$("#send_audio").hide();
let mediaRecorder;
let recordedChunks = [];

const startButton = document.getElementById("startRecording");
const stopButton = document.getElementById("stopRecording");
const playButton = document.getElementById("playRecording");
const stopPlaybackButton = document.getElementById("stopPlayback");
const audioElement = document.getElementById("recordedAudio");

function startRecording() {
    navigator.mediaDevices
        .getUserMedia({ audio: true })
        .then((stream) => {
            mediaRecorder = new MediaRecorder(stream);

            mediaRecorder.start();
            startButton.style.display = "none";
            stopButton.style.display = "inline-block";
            playButton.style.display = "none";
            stopPlaybackButton.style.display = "none";

            mediaRecorder.ondataavailable = (event) => {
                recordedChunks.push(event.data);
            };
        })
        .catch((err) => {
            console.error("Error accessing microphone: ", err);
            alert("Failed to access microphone. Please try again.");
        });
}

async function stopRecording() {
    if (mediaRecorder && mediaRecorder.state === "recording") {
        mediaRecorder.stop();
        $("#send_audio").show();

        startButton.style.display = "inline-block";
        stopButton.style.display = "none";

        // Wait for the MediaRecorder to finish saving data
        await new Promise((resolve) => {
            mediaRecorder.onstop = resolve;
        });

        // Call playRecording() to initiate playback
        playRecording();
    } else {
        console.error("MediaRecorder is not recording.");
    }
}

function playRecording() {
    const blob = new Blob(recordedChunks, { type: "audio/wav" });
    const audioURL = URL.createObjectURL(blob);

    audioElement.src = audioURL;
    audioElement.style.display = "block";

    playButton.style.display = "none";
    stopPlaybackButton.style.display = "inline-block";

    audioElement.play().catch((err) => {
        console.error("Error playing audio: ", err);
        alert("Failed to play recorded audio.");
    });
}

function stopPlayback() {
    if (!audioElement.paused) {
        audioElement.pause();
    }
    playButton.style.display = "inline-block";
    stopPlaybackButton.style.display = "none";
}

startButton.addEventListener("click", startRecording);
stopButton.addEventListener("click", stopRecording);
playButton.addEventListener("click", playRecording);
stopPlaybackButton.addEventListener("click", stopPlayback);

$("#send_audio").on("click", async function () {
    $(".recordedAudio").hide();
    $("#playRecording").hide();
    $("#stopRecording").hide();
    $("#stopPlayback").hide();

    $("#send_audio").hide();
    // $(".preview_img").hide();

    const previewAudio = $(".recordedAudio");
    const audioUrl = previewAudio.attr("src");

    console.log(audioUrl);

    if (!audioUrl) {
        alert("Please select audio.");
        return;
    }

    var isGroup = $("#isGroup").val();
    // const message = $(this).val();

    let storagePath;
    // if (audioUrl.startsWith("blob:http/")) {
    storagePath = `Audios/198/${Date.now()}_${senderUser}.audio`;
    // } else {
    // }

    // Upload file to Firebase Storage
    const fileRef = storageRef(storage, storagePath);
    try {
        if (audioUrl.startsWith("blob:http/")) {
            await uploadString(fileRef, audioUrl, "data_url");
        } else {
            const response = await fetch(audioUrl);
            const blob = await response.blob();
            await uploadBytes(fileRef, blob);
        }
        const downloadaudioURL = await getDownloadURL(fileRef);

        const messageData = {
            data: downloadaudioURL,
            timeStamp: Date.now(),
            isDelete: {},
            isReply: "0",
            isSeen: false,
            react: "",
            senderId: senderUser,
            senderName: senderUserName,
            status: {},
            // imageUrl: downloadURL, // Use the download URL from Firebase Storage
        };

        if (isGroup == "1") {
            const conversationId = $(".selected_id").val();
            const groupName = $(".selected_name").val();

            // Fetch group members from Firebase
            const groupMembersRef = ref(
                database,
                `Groups/${conversationId}/users`
            );
            const groupMembersSnapshot = await get(groupMembersRef);
            const newGroupMembers = groupMembersSnapshot.val();
            console.log({ newGroupMembers });
            await addMessageToGroup(conversationId, messageData);

            // Update all group members' overview
            for (const userId of newGroupMembers) {
                await updateOverview(userId, conversationId, {
                    lastMessage: `${senderUserName}: ${downloadaudioURL}`,
                    unReadCount: userId === senderUser ? 0 : 1,
                    timeStamp: Date.now(),
                });
            }
        } else {
            const selectedMessageId = $(".selected_id").val();
            const receiverId = $(".selected_message").val();
            const receiverName = $(".selected_name").val();

            messageData.receiverId = receiverId;
            messageData.receiverName = receiverName;

            await addMessage(selectedMessageId, messageData, receiverId);

            await updateOverview(senderUser, selectedMessageId, {
                lastMessage: `${senderUserName}: ${downloadaudioURL}`,
                timeStamp: Date.now(),
            });
            const receiverSnapshot = await get(
                ref(database, `overview/${receiverId}/${selectedMessageId}`)
            );
            await updateOverview(receiverId, selectedMessageId, {
                lastMessage: `${senderUserName}: ${downloadaudioURL}`,
                unReadCount: (receiverSnapshot.val().unReadCount || 0) + 1,
                timeStamp: Date.now(),
            });

            const conversationElement = $(`.conversation-${selectedMessageId}`);
            conversationElement.prependTo(".chat-list");
        }
    } catch (error) {
        console.error("Error uploading image: ", error);
        // alert("Failed to upload image. Please try again.");
    }
});

//*******************************This Code is for audio record....**************************************END

