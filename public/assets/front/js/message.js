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
    remove,
    onDisconnect,
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

function updateProfileImg(profileImageUrl, userName) {
    if (
        profileImageUrl?.includes(".jpg") ||
        profileImageUrl?.includes(".jpeg") ||
        profileImageUrl?.includes(".png")
    ) {
        $("#selected-user-profile").replaceWith(
            `  <img id="selected-user-profile" src="${profileImageUrl}" alt="user-img">`
        );
        $("#profileIm").replaceWith(
            `  <img id="profileIm" src="${profileImageUrl}" alt="cover-img" >`
        );
    } else {
        const initials = userName
            .split(" ")
            .map((word) => word[0]?.toUpperCase())
            .join("");
        const fontColor = "fontcolor" + initials[0]?.toUpperCase();

        $("#selected-user-profile").replaceWith(
            `<h5 class="${fontColor}" id="selected-user-profile">${initials}</h5>`
        );
        $("#profileIm").replaceWith(
            `  <h5 id="profileIm" class="${fontColor}">${initials}</h5>`
        );
    }
}

function getSelectedUserimg(profileImageUrl, userName) {
    if (
        profileImageUrl != undefined &&
        (profileImageUrl.includes(".jpg") ||
            profileImageUrl?.includes(".jpeg") ||
            profileImageUrl?.includes(".png"))
    ) {
        return `<img class="selected-user-img" src="${profileImageUrl}" alt="user-img">`;
    } else {
        const initials = userName
            .split(" ")
            .map((word) => word[0]?.toUpperCase())
            .join("");
        const fontColor = "fontcolor" + initials[0]?.toUpperCase();

        return `<h5 class="${fontColor} selected-user-img user-img" id="selected-user-profile" src="">${initials}</h5>`;
    }
}

function getListUserimg(profileImageUrl, userName) {
    if (
        profileImageUrl != undefined &&
        (profileImageUrl.includes(".jpg") ||
            profileImageUrl?.includes(".jpeg") ||
            profileImageUrl?.includes(".png"))
    ) {
        return `<img class="user-avatar img-fluid" src="${profileImageUrl}" alt="user-img">`;
    } else {
        const initials = userName
            .split(" ")
            .map((word) => word[0]?.toUpperCase())
            .join("");
        const fontColor = "fontcolor" + initials[0]?.toUpperCase();

        return `<h5 class="${fontColor} user-avatar img-fluid" id="selected-user-profile" src="">${initials}</h5>`;
    }
}

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
    console.log({ newGroupMembers });
    const messagesRef = ref(database, `Groups/${conversationId}/message`);
    await push(messagesRef, messageData);
    if (newGroupMembers.length > 0) {
        const usersRef = ref(database, "Groups/" + conversationId + "/users");
        await set(usersRef, newGroupMembers);

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
            if (!user) {
                toastr.error(
                    "Some user is not updated, they are not added in group.",
                    "Error!"
                );
                continue;
            }
            groupInfo.profiles[i] = {
                id: userId,
                image: user?.userProfile,
                isAdmin: userId === senderUser ? "1" : "0",
                leave: false,
                name: user?.userName,
            };
            groupInfo.usersStatus[userId] = conversationId;
            i++;
        }
        console.log({ groupInfoRef });
        console.log({ groupInfo });
        await set(groupInfoRef, groupInfo);
    }
}
// Function to handle a new conversation
function handleNewConversation(snapshot) {
    const newConversation = snapshot.val();
    console.log("update");
    console.log(newConversation.conversationId);
    // console.log("New conversation added:", newConversation);
    if (newConversation.conversationId == undefined) {
        return;
    }

    const conversationElement = document.getElementsByClassName(
        `conversation-${newConversation.conversationId}`
    );
    console.log(conversationElement);
    if (conversationElement.length > 0) {
        // Update existing conversation element
        $(conversationElement)
            .find(".user-detail h3")
            .text(newConversation.contactName);
        $(conversationElement)
            .find(".user-detail .last-message")
            .text(newConversation.lastMessage);
        $(conversationElement)
            .find(".user-detail .time-ago")
            .text(timeago.format(newConversation.timeStamp));
        $(conversationElement)
            .find(".user-img")
            .find("img")
            .replaceWith(
                getListUserimg(
                    newConversation.receiverProfile,
                    newConversation.contactName
                )
            );

        const badgeElement = $(conversationElement).find(".user-detail .badge");
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
    $("#isGroup").val(newConversation.group);
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
    $(".member-lists").html("");
    $(".choosen-file").hide();
    if (user_id == "") return;
    const selected_user = await getUser(user_id);
    console.log({ user_id });
    if (!selected_user) {
        alert("user not found in firebase");
        return;
    }

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
    updateProfileImg(profileImageUrl, selected_user.userName);

    $(".selected_name").val(selected_user.userName);
    $(".selected-title").html(selected_user.userName);

    $(".selected_conversasion").val($(".selected_id").val());
    const conversationId = $(".selected_id").val();
    console.log({ conversationId });
    $(".conversationId").attr("conversationId", conversationId);
    update(userRef, { userChatId: conversationId });

    const messagesRef = ref(database, `Messages/${conversationId}/message`);
    const selecteduserTypeRef = ref(database, `users/${user_id}`);
    off(messagesRef);
    off(selecteduserTypeRef);
    onChildAdded(messagesRef, async (snapshot) => {
        addMessageToList(snapshot.key, snapshot.val(), conversationId);

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
var SelecteGroupUser = [];
async function updateChatfromGroup(conversationId) {
    SelecteGroupUser = [];
    $(".msg-lists").html("");
    $(".member-lists").html("");
    $(".choosen-file").show();
    $(".conversationId").attr("conversationId", conversationId);
    const groupInfoRef = ref(database, `Groups/${conversationId}/groupInfo`);
    const snapshot = await get(groupInfoRef);
    const groupInfo = snapshot.val();
    groupInfo.profiles.map((profile) => {
        if (profile.id > 0) {
            SelecteGroupUser[profile.id] = {
                id: profile.id,
                name: profile.name,
                image: profile.image,
                isAdmin: profile.isAdmin,
                leave: profile.leave,
            };
        }
    });
    const messagesRef = ref(database, `Groups/${conversationId}/message`);
    off(messagesRef);
    onChildAdded(messagesRef, async (snapshot) => {
        addMessageToList(snapshot.key, snapshot.val(), conversationId);

        const selectedConversationId = $(".selected_conversasion").val();
        if (selectedConversationId === conversationId) {
            await updateOverview(senderUser, conversationId, {
                unRead: false,
                unReadCount: 0,
            });
        }
    });
    $("#selected-user-lastseen").html(""); // Group doesn't have a last seen
    $("#selected-user-name").html(groupInfo.groupName);
    updateProfileImg(groupInfo.groupProfile, groupInfo.groupName);

    $(".selected_name").val(groupInfo.groupName);

    $(".selected_conversasion").val(conversationId);
    update(userRef, { userChatId: conversationId });
    addListInMembers(SelecteGroupUser);
    $(".selected-title").html(groupInfo.groupName);
}

$(".conversationId").click(function () {
    let conversationId = $(this).attr("conversationId");
    $(".change-group-name").addClass("d-none");
});
// Initialize event listeners
$(document).on("click", ".msg-list", async function () {
    removeSelectedMsg();
    $(this).addClass("active");
    const isGroup = $(this).attr("data-group");
    const conversationId = $(this).attr("data-msgKey");
    $(".selected_id").val(conversationId);

    $("#isGroup").val(isGroup);
    console.log(isGroup);
    $(".member-lists").html("");

    if (isGroup == true || isGroup == "true") {
        console.log("from group");
        await updateChatfromGroup(conversationId);
    } else {
        console.log("From user");

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
if ($("#isGroup").val() == true) {
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

var typeTimeout = 0;
$(".send-message").on("keyup", async function (e) {
    clearTimeout(typeTimeout);
    typeTimeout = setTimeout(() => {
        update(userRef, { userTypingStatus: "Not typing..." });
    }, 1000);
});

$(".send-message").on("keypress", async function (e) {
    update(userRef, { userTypingStatus: "Typing..." });
    if (e.which === 13) {
        var isGroup = $("#isGroup").val();
        const message = $(this).val();
        const conversationId = $(".selected_id").val();
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
                receiverName: senderUserName,
                status: {},
            };

            if (isGroup == true || isGroup == "true") {
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
                const receiverId = $(".selected_message").val();
                const receiverName = $(".selected_name").val();

                messageData.receiverId = receiverId;
                messageData.receiverName = receiverName;

                await addMessage(conversationId, messageData, receiverId);

                await updateOverview(senderUser, conversationId, {
                    lastMessage: `${senderUserName}: ${message}`,
                    timeStamp: Date.now(),
                });
                const receiverSnapshot = await get(
                    ref(database, `overview/${receiverId}/${conversationId}`)
                );
                console.log(receiverId);
                console.log(conversationId);
                console.log(receiverSnapshot.val());
                if (receiverSnapshot.val() != null) {
                    await updateOverview(receiverId, conversationId, {
                        lastMessage: `${senderUserName}: ${message}`,
                        unReadCount:
                            (receiverSnapshot.val().unReadCount || 0) + 1,
                        timeStamp: Date.now(),
                    });
                } else {
                    const reciverUser = await getUser(receiverId);
                    const receiverConversationData = {
                        contactId: receiverId,
                        contactName: receiverName,
                        conversationId: conversationId,
                        group: false,
                        lastMessage: `${senderUserName}: ${message}`,
                        lastSenderId: senderUser,
                        receiverProfile: reciverUser.userProfile,
                        timeStamp: Date.now(),
                        unRead: true,
                        unReadCount: 1,
                    };

                    await set(
                        ref(
                            database,
                            `overview/${receiverId}/${conversationId}`
                        ),
                        receiverConversationData
                    );
                }
            }
            const conversationElement = $(`.conversation-${conversationId}`);
            conversationElement.prependTo(".chat-list");
        }
    }
});

// Function to add a message to the UI list
function addMessageToList(key, messageData, conversationId) {
    if ($(".selected_conversasion").val() != conversationId) {
        return;
    }
    let isGroup = $("#isGroup").val();
    if (
        messageData?.isDelete != undefined &&
        Object.keys(messageData?.isDelete).includes(senderUser)
    ) {
        return;
    }
    const messageElement = `
        <li class="${
            senderUser === messageData.senderId ? "receiver" : "sender"
        }" id="message-${key}">
            <p>${messageData.data}</p>
            <span class="time">${timeago.format(
                new Date(messageData.timeStamp).toLocaleTimeString()
            )}</span>
            <span class="senderName">${
                (isGroup == "true" || isGroup == true) &&
                senderUser != messageData.senderId
                    ? SelecteGroupUser[messageData.senderId].name
                    : ""
            }</span>
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
    const $img = getListUserimg(item.imageUrl, item.label);

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
        $(".selected-user-img").replaceWith(
            getSelectedUserimg(userImgSrc, userName)
        );

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
                                    ${getSelectedUserimg(userImgSrc, userName)}
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

function generateConversationId(userIds) {
    // Create a modifiable copy of the list and sort user IDs to ensure consistency
    const sortedUserIds = userIds.slice().sort();

    // Concatenate sorted user IDs
    const concatenatedIds = sortedUserIds.join("");

    // Generate hash value using SHA-256
    function sha256(str) {
        return crypto.subtle
            .digest("SHA-256", new TextEncoder().encode(str))
            .then((buf) => {
                return Array.prototype.map
                    .call(new Uint8Array(buf), (x) =>
                        ("00" + x.toString(16)).slice(-2)
                    )
                    .join("");
            });
    }

    // Generate the SHA-256 hash and return the first 20 characters
    return sha256(concatenatedIds).then((hash) => hash.substring(0, 20));
}
// Function to find or create a conversation
async function findOrCreateConversation(
    currentUserId,
    contactId,
    contactName,
    receiverProfile
) {
    // const overviewRef = ref(database, `overview/${currentUserId}`);
    // const snapshot = await get(overviewRef);

    // if (snapshot.exists()) {
    //     const overviewData = snapshot.val();
    //     for (const conversationId in overviewData) {
    //         if (overviewData[conversationId].contactId === contactId) {
    //             return conversationId;
    //         }
    //     }
    // }
    // // Check if a conversation exists for the contact
    // const contactOverviewRef = ref(database, `overview/${contactId}`);
    // const contactSnapshot = await get(contactOverviewRef);

    // if (contactSnapshot.exists()) {
    //     const contactOverviewData = contactSnapshot.val();
    //     for (const conversationId in contactOverviewData) {
    //         if (
    //             contactOverviewData[conversationId].contactId === currentUserId
    //         ) {
    //             return conversationId;
    //         }
    //     }
    // }

    const newConversationId = await generateConversationId([
        currentUserId,
        contactId,
    ]);

    // const newConversationRef = push(child(ref(database), "overview"));
    // const newConversationId = newConversationRef.key;

    const newConversationData = {
        contactId: contactId,
        contactName: contactName,
        conversationId: newConversationId,
        group: false,
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
        group: false,
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
    // const overviewRef = ref(database, `overview/${currentUserId}`);
    // const snapshot = await get(overviewRef);

    // if (snapshot.exists()) {
    //     const overviewData = snapshot.val();
    //     for (const conversationId in overviewData) {
    //         if (overviewData[conversationId].groupName === groupName) {
    //             return conversationId;
    //         }
    //     }
    // }

    // const newConversationRef = push(child(ref(database), "overview"));
    // const newConversationId = newConversationRef.key;
    const newConversationId = await generateConversationId(newGroupMembers);

    const newConversationData = {
        contactId: newConversationId,
        contactName: groupName,
        conversationId: newConversationId,
        group: true,
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
            group: true,
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
        const message = $(this).val();
        if (tagCount == 0) {
            return toastr.error(
                "Please select any user for start chat.",
                "Error!"
            );
        } else if (message.trim() == "") {
            return toastr.error(
                "Please enter message for start chat.",
                "Error!"
            );
        }
        if (tagCount > 1) {
            const currentUserId = senderUser;
            const groupName = $("#group-name").val(); // Assuming you have an input for group name
            if (groupName.trim() == "") {
                return toastr.error(
                    "Please enter Group name for create group.",
                    "Error!"
                );
            }
            $("#msgBox").modal("hide");

            const newGroupMembers = $("#selected-user-id")
                .val()
                .split(",")
                .map((id) => id.trim());
            newGroupMembers.push(senderUser);

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
            updateChatfromGroup(conversationId);
            $(".selected_id").val(conversationId);
            $(".selected_message").val(conversationId);
            $(".selected_name").val(groupName);
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
            $("#msgBox").modal("hide");

            updateOverview(currentUserId, selectedMessageId, {
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
        $(this).val("");
    }
});

const isOfflineForDatabase = {
    userStatus: "offline",
    userLastSeen: Date.now(),
};
// await onDisconnect(userRef).update(isOfflineForDatabase);
// Load user images
$(".user-image").each(async function () {
    const dataId = $(this).attr("data-id");
    const user = await getUser(dataId);
    $(this).attr("src", user?.userProfile);
});
function addListInMembers(SelecteGroupUser) {
    let isGroup = $("#isGroup").val();

    var messageElement = ``;
    SelecteGroupUser.map((user) => {
        messageElement += ` <li class="">
                            <div class="chat-data d-flex">
                                <div class="user-img position-relative">
                                    ${getListUserimg(user.image, user.name)}
                                </div>
                                <div class="user-detail d-block ms-3">
                                    <div class="">
                                        <h3>${
                                            user.id == senderUser
                                                ? "You"
                                                : user.name
                                        }</h3>
                                        <span>${
                                            user.isAdmin == "1" ? "Admin" : ""
                                        }</span>
                                    </div>
                                </div>
                            </div>
                        </li>`;
    });
    $(".member-lists").html(messageElement);
}

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
                $(".btn-primary.dropdown-toggle").attr(
                    "aria-expanded",
                    "false"
                );
            };

            reader.readAsDataURL(file);
        } else {
            $(".preview_img").show();
            curElement.attr("src", base_url + "storage/file.png");
            // $("#upload_name").text(file.name);
            $("#send_image").show();
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

    if (!imageUrl) {
        alert("Please select an image to send.");
        return;
    }

    var isGroup = $("#isGroup").val();
    const message = $(this).val();

    // Determine file type and set the storage path
    let storagePath;
    if (imageUrl.startsWith("data:image/")) {
        storagePath = `Images/198/${Date.now()}_${senderUser}.png`;
    } else {
        storagePath = `Files/203/${Date.now()}_${senderUser}`;
    }

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
    } catch (error) {
        console.error("Error uploading image: ", error);
        alert("Failed to upload image. Please try again.");
    }
});

$("#choose-file").on("change", async function () {
    var file = this.files[0];
    var reader = new FileReader();
    reader.onload = function (e) {
        $("#profileIm").replaceWith(
            `<img id="profileIm" src="${e.target.result}" alt="user-img">`
        );
    };
    reader.readAsDataURL(this.files[0]);
    setTimeout(async () => {
        if (file) {
            const fileRef = storageRef(
                storage,
                `/GroupProfile/${senderUser}/${Date.now()}_${file.name}`
            );
            const previewImg = $("#profileIm");
            console.log({ previewImg });
            const imageUrl = previewImg.attr("src");
            console.log({ imageUrl });

            if (imageUrl.startsWith("data:image/")) {
                await uploadString(fileRef, imageUrl, "data_url");
            } else {
                const response = await fetch(imageUrl);
                const blob = await response.blob();
                await uploadBytes(fileRef, blob);
            }
            const downloadURL = await getDownloadURL(fileRef);

            var conversationId = $(".conversationId").attr("conversationid"); // Replace with actual conversation ID
            var groupInfoRef = ref(
                database,
                `/Groups/${conversationId}/groupInfo/`
            );
            $("#selected-user-profile").attr("src", downloadURL);
            await update(groupInfoRef, { groupProfile: downloadURL });
            SelecteGroupUser.map((user) => {
                var groupUserInfoRef = ref(
                    database,
                    `/overview/${user.id}/${conversationId}/`
                );

                update(groupUserInfoRef, { receiverProfile: downloadURL });
            });
        }
    }, 500);
});

$(".delete-conversation").click(async function () {
    var conversationId = $(".conversationId").attr("conversationid");
    if (conversationId && senderUser) {
        // Remove the conversation from the overview
        $(".conversation-" + conversationId).remove();
        var msgLists = $(".msg-list");
        if (msgLists.length > 0) {
            msgLists.first().click(); // Simulate a click event on the first msg-list element
        }
        // Update the Firebase Realtime Database
        var overviewRef = ref(
            database,
            `overview/${senderUser}/${conversationId}`
        );

        await remove(overviewRef);
        var messagesRef = ref(database, `Messages/${conversationId}/message`);
        var messagesSnapshot = await get(messagesRef);

        if (messagesSnapshot.exists()) {
            var messages = messagesSnapshot.val();
            var updates = {};

            // Iterate through all messages and update isDelete for the senderUser
            for (var messageId in messages) {
                console.log(messageId);
                updates[
                    `Messages/${conversationId}/message/${messageId}/isDelete/${senderUser}`
                ] = 1;
            }
            // Apply updates to the Firebase Realtime Database
            await update(ref(database), updates);
        }
    } else {
        console.error("Conversation ID or Sender User ID is missing");
    }
});

$(".selected-title").dblclick(function () {
    let title = $(this).html();
    $(this).hide();
    $(".change-group-name").removeClass("d-none");
    $(".update-group-name").val(title);
});

$("#updateName").click(async function () {
    let newTitle = $(".update-group-name").val();
    let conversationId = $(".conversationId").attr("conversationid");

    if (!conversationId || !newTitle || SelecteGroupUser <= 0) {
        console.error("Conversation ID or new title is missing");
        return;
    }

    // Update group name in every group user's overview

    SelecteGroupUser.map((user) => {
        var groupUserInfoRef = ref(
            database,
            `/overview/${user.id}/${conversationId}/contactName`
        );

        set(groupUserInfoRef, newTitle);
    });

    // Update group name in group info
    const groupInfoRef = ref(
        database,
        `Groups/${conversationId}/groupInfo/groupName`
    );
    await set(groupInfoRef, newTitle);

    // Hide the input and show the updated title
    $(".change-group-name").addClass("d-none");
    $(".selected-title").html(newTitle).show();
});
