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
    $("#selected-user-profile").attr("src", selected_user.userProfile);
    $(".selected_name").val(selected_user.userName);

    $(".selected_conversasion").val($(".selected_id").val());
    const conversationId = $(".selected_id").val();
    console.log({ conversationId });

    update(userRef, { userChatId: conversationId });

    const messagesRef = ref(database, `Messages/${conversationId}/message`);
    const selecteduserTypeRef = ref(database, `users/${user_id}`);
    off(messagesRef);
    off(selecteduserTypeRef);
    console.log("hrer");
    onChildAdded(messagesRef, async (snapshot) => {
        console.log("yes");
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

// Initialize event listeners
$(document).on("click", ".msg-list", async function () {
    console.log("clicked");
    removeSelectedMsg();
    $(this).addClass("active");
    const userId = $(this).attr("data-userid");
    $(".selected_id").val($(this).attr("data-msgKey"));
    $(".selected_message").val(userId);
    const conversationId = $(this).attr("data-msgKey");
    await updateOverview(senderUser, conversationId, {
        unRead: false,
        unReadCount: 0,
    });
    await updateChat(userId);
});
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
        console.log("new");
        const message = $(this).val();
        const selectedMessageId = $(".selected_id").val();
        const receiverId = $(".selected_message").val();
        const receiverName = $(".selected_name").val();

        if (message.trim() !== "") {
            $(this).val(""); // Clear the input field
            const messageData = {
                data: message,
                timeStamp: Date.now(),
                isDelete: {},
                isReply: "0",
                isSeen: false,
                react: "",
                receiverId: receiverId,
                receiverName: receiverName,
                replyData: {},
                senderId: senderUser,
                senderName: senderUserName,
                status: {},
            };
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
        }
        const conversationElement = $(`.conversation-${selectedMessageId}`);
        conversationElement.prependTo(".chat-list");
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

// Initial chat update
updateChat($(".selected_message").val());

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
        isGroup: "0",
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
        isGroup: "0",
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

// Event listener for sending a new message
$("#new_message").on("keypress", async function (e) {
    if (e.which === 13) {
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
        $("#msgBox").modal("hide");
        updateChat(contactId);
        if (message.trim() !== "") {
            $(this).val("");
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
});

// Load user images
$(".user-image").each(async function () {
    const dataId = $(this).attr("data-id");
    const user = await getUser(dataId);
    $(this).attr("src", user?.userProfile);
});
