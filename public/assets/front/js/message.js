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
    onChildRemoved,
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

function getInitials(userName) {
    return userName
        .split(" ")
        .map((word) => word[0]?.toUpperCase())
        .join("");
}

async function isValidImageUrl(profileImageUrl) {
    if (
        profileImageUrl &&
        (profileImageUrl.includes(".jpg") ||
            profileImageUrl.includes(".jpeg") ||
            profileImageUrl.includes(".png")) &&
        (await imageExists(profileImageUrl))
    ) {
        return true;
    }
    return false;
}

function imageExists(url) {
    try {
        return new Promise((resolve) => {
            const img = new Image();
            img.onload = function () {
                resolve(true);
            };
            img.onerror = function () {
                resolve(false);
            };
            img.src = url;
        });
    } catch (e) {}
}

async function updateProfileImg(profileImageUrl, userName) {
    if (await isValidImageUrl(profileImageUrl)) {
        $("#selected-user-profile").replaceWith(
            `<img id="selected-user-profile" src="${profileImageUrl}" alt="user-img">`
        );
        $("#profileIm").replaceWith(
            `<img id="profileIm" src="${profileImageUrl}" alt="cover-img" >`
        );
    } else {
        const initials = getInitials(userName);
        const fontColor = "fontcolor" + initials[0]?.toUpperCase();

        $("#selected-user-profile").replaceWith(
            `<h5 class="${fontColor}" id="selected-user-profile">${initials}</h5>`
        );
        $("#profileIm").replaceWith(
            `<h5 id="profileIm" class="${fontColor}">${initials}</h5>`
        );
    }
}

async function getSelectedUserimg(profileImageUrl, userName) {
    if (await isValidImageUrl(profileImageUrl)) {
        return `<img class="selected-user-img" src="${profileImageUrl}" alt="user-img">`;
    } else {
        const initials = getInitials(userName);
        const fontColor = "fontcolor" + initials[0]?.toUpperCase();

        return `<h5 class="${fontColor} selected-user-img user-img" id="selected-user-profile" src="">${initials}</h5>`;
    }
}

async function getListUserimg(profileImageUrl, userName) {
    if (await isValidImageUrl(profileImageUrl)) {
        return `<img class="user-avatar img-fluid" src="${profileImageUrl}" alt="user-img">`;
    }

    const initials = getInitials(userName);
    const fontColor = "fontcolor" + initials[0]?.toUpperCase();

    return `<h5 class="${fontColor} user-avatar img-fluid" id="selected-user-profile" src="">${initials}</h5>`;
}

// Initialize Firebase
const app = initializeApp(firebaseConfig);
const database = getDatabase(app);
const storage = getStorage(app);
const senderUser = $(".senderUser").val();
const senderUserName = $(".senderUserName").val();
const base_url = $("#base_url").val();
const userRef = ref(database, `users/${senderUser}`);
let replyMessageId = null; // Global variable to hold the message ID to reply to
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
async function handleNewConversation(snapshot) {
    const newConversation = snapshot.val();

    // console.log("New conversation added:", newConversation);
    if (newConversation.conversationId == undefined) {
        return;
    }

    const conversationElement = document.getElementsByClassName(
        `conversation-${newConversation.conversationId}`
    );
    let userStatus = "";
    if (newConversation.group !== "true" && newConversation.group !== true) {
        let userId = newConversation.contactId;
        let userData = await getUser(userId);
        console.log(newConversation.group);
        console.log(userData);
        if (
            userData.userStatus == "Online" ||
            userData.userStatus == "online"
        ) {
            userStatus = `<span class="active"></span>`;
        } else {
            userStatus = `<span class="inactive"></span>`;
        }
    }
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
                await getListUserimg(
                    newConversation.receiverProfile,
                    newConversation.contactName
                )
            );
        $(conversationElement)
            .find(".user-img")
            .find("span")
            .replaceWith(userStatus);

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
    // $("#isGroup").val(newConversation.group);
    // console.log(newConversation.group);
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
    await updateProfileImg(profileImageUrl, selected_user.userName);

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
    onChildChanged(messagesRef, async (snapshot) => {
        UpdateMessageToList(snapshot.key, snapshot.val(), conversationId);
    });
    onChildRemoved(messagesRef, async (snapshot) => {
        RemoveMessageToList(snapshot.key, conversationId);
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
    onChildChanged(messagesRef, async (snapshot) => {
        UpdateMessageToList(snapshot.key, snapshot.val(), conversationId);
    });
    onChildRemoved(messagesRef, async (snapshot) => {
        RemoveMessageToList(snapshot.key, conversationId);
    });
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
    await updateProfileImg(groupInfo.groupProfile, groupInfo.groupName);

    $(".selected_name").val(groupInfo.groupName);

    $(".selected_conversasion").val(conversationId);
    update(userRef, { userChatId: conversationId });
    await addListInMembers(SelecteGroupUser);
    $(".selected-title").html(groupInfo.groupName);
}

$(".conversationId").click(function () {
    let conversationId = $(this).attr("conversationId");
    $(".change-group-name").addClass("d-none");
    const isGroup = $("#isGroup").val();
    if (isGroup == true || isGroup == "true") {
        $(".new-member").removeClass("d-none");
    } else {
        $(".new-member").addClass("d-none");
    }
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
        $(".new-member").removeClass("d-none");
    } else {
        console.log("From user");
        $(".new-member").addClass("d-none");

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
        let downloadURL = "";
        let type = "";
        $(".preview_img").hide();
        const previewImg = $(".preview_img");
        const imageUrl = previewImg.attr("src");

        const previewAudio = $(".recordedAudio");
        const audioUrl = previewAudio.attr("src");

        const audio = $("#file_name").text();

        if (imageUrl) {
            // Determine file type and set the storage path
            let storagePath;
            if (imageUrl.startsWith("data:image/")) {
                storagePath = `Images/${senderUser}/${Date.now()}_${senderUser}-img.png`;
            } else if (imageUrl.startsWith("blob:http:/") && audio != "audio") {
                storagePath = `Video/${senderUser}/${Date.now()}_${senderUser}-video.mp4`;
            } else if (imageUrl.startsWith("blob:http:/") && audio == "audio") {
                storagePath = `Audios/${senderUser}/${Date.now()}_${senderUser}-audio.wav`;
            } else {
                storagePath = `Files/${senderUser}/${Date.now()}_${senderUser}-file.${fileType}`;
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
                downloadURL = await getDownloadURL(fileRef);
                type = "1";
            } catch (e) {}
        } else if (audioUrl) {
            $("#playRecording").hide();
            $("#stopRecording").hide();
            $("#stopPlayback").hide();

            console.log(audioUrl);

            let storagePath;
            storagePath = `Audios/${senderUser}/${Date.now()}_${senderUser}-Audio.wav`;

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
                downloadURL = await getDownloadURL(fileRef);
                type = "3";
            } catch (e) {}
        }

        if (message.trim() == "" && downloadURL == "") {
            return;
        }
        $(this).val(""); // Clear the input field
        const messageData = {
            data: message,
            url: downloadURL,
            type,
            timeStamp: Date.now(),
            isDelete: {},
            isReply: "0",
            isSeen: false,
            react: "",
            senderId: senderUser,
            senderName: senderUserName,
            receiverName: senderUserName,
            status: {},
            replyData: {
                replyChatKey: "",
                replyDocType: "",
                replyMessage: "",
                replyTimeStamp: 0,
                replyUserName: "",
            },
        };

        // alert(isGroup);
        if (isGroup == true || isGroup == "true") {
            const groupName = $(".selected_name").val();
            if (replyMessageId) {
                // Fetch the reply message data
                const replyMessageRef = ref(
                    database,
                    `Groups/${conversationId}/message/${replyMessageId}`
                );
                const replyMessageSnapshot = await get(replyMessageRef);
                const replyMessageData = replyMessageSnapshot.val();

                messageData.replyData = {
                    replyChatKey: replyMessageId,
                    replyMessage: replyMessageData ? replyMessageData.data : "",
                    replyTimeStamp: Date.now(),
                    replyUserName: replyMessageData.senderName,
                    replyDocType: "",
                };
                messageData.isReply = "1";
                // Reset reply message ID after sending
                replyMessageId = null;
            }

            const groupProfilesRef = ref(
                database,
                `Groups/${conversationId}/groupInfo/profiles`
            );
            const groupProfilesSnapshot = await get(groupProfilesRef);
            const newGroupProfiles = groupProfilesSnapshot.val();
            var userAvailable = [];
            newGroupProfiles.map(async (profile) => {
                if (profile.leave == false) {
                    if (profile.id !== senderUser) {
                        userAvailable[profile.id] = 0;
                    }
                    const receiverSnapshot = await get(
                        ref(
                            database,
                            `overview/${profile.id}/${conversationId}`
                        )
                    );
                    await updateOverview(profile.id, conversationId, {
                        lastMessage: `${senderUserName}: ${message}`,
                        unReadCount:
                            profile.id === senderUser
                                ? receiverSnapshot.val()
                                : (receiverSnapshot.val().unReadCount || 0) + 1,
                        timeStamp: Date.now(),
                    });
                }
            });

            messageData.userAvailable = userAvailable;

            await addMessageToGroup(conversationId, messageData);

            // Update all group members' overview
        } else {
            const receiverId = $(".selected_message").val();
            const receiverName = $(".selected_name").val();

            messageData.receiverId = receiverId;
            messageData.receiverName = receiverName;
            if (replyMessageId) {
                // Fetch the reply message data
                const replyMessageRef = ref(
                    database,
                    `Messages/${conversationId}/message/${replyMessageId}`
                );
                const replyMessageSnapshot = await get(replyMessageRef);
                const replyMessageData = replyMessageSnapshot.val();

                messageData.replyData = {
                    replyChatKey: replyMessageId,
                    replyMessage: replyMessageData ? replyMessageData.data : "",
                    replyTimeStamp: Date.now(),
                    replyUserName: senderUserName,
                    replyDocType: "",
                };
                messageData.isReply = "1";
                // Reset reply message ID after sending
                replyMessageId = null;
            }

            messageData.status = { senderUser: { profile: "", read: 1 } };

            await addMessage(conversationId, messageData, receiverId);

            await updateOverview(senderUser, conversationId, {
                lastMessage: `${senderUserName}: ${message}`,
                timeStamp: Date.now(),
            });
            const receiverSnapshot = await get(
                ref(database, `overview/${receiverId}/${conversationId}`)
            );

            if (receiverSnapshot.val() != null) {
                await updateOverview(receiverId, conversationId, {
                    lastMessage: `${senderUserName}: ${message}`,
                    unReadCount: (receiverSnapshot.val().unReadCount || 0) + 1,
                    timeStamp: Date.now(),
                });
            } else {
                const reciverUser = await getUser(receiverId);
                if (!reciverUser) {
                    return;
                }
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
                    ref(database, `overview/${receiverId}/${conversationId}`),
                    receiverConversationData
                );
            }
        }
        const conversationElement = $(`.conversation-${conversationId}`);
        conversationElement.prependTo(".chat-list");
    }
});

// Function to add a message to the UI list
function RemoveMessageToList(key, conversationId) {
    if ($(".selected_conversasion").val() != conversationId) {
        return;
    }
    const messageEle = document.getElementById(`message-${key}`);
    $(messageEle).remove();
}
function UpdateMessageToList(key, messageData, conversationId) {
    if ($(".selected_conversasion").val() != conversationId) {
        return;
    }
    console.log("update");
    const messageEle = document.getElementById(`message-${key}`);
    let isGroup = $("#isGroup").val();
    const messgeElement = createMessageElement(key, messageData, isGroup);
    console.log(messgeElement);
    $(messageEle).replaceWith(messgeElement);
}
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

    const messageElement = createMessageElement(key, messageData, isGroup);
    $(".msg-lists").append(messageElement);

    if (
        (isGroup != "true" || isGroup != true) &&
        senderUser === messageData.receiverId
    ) {
        markMessageAsSeen(conversationId, key);
    } else {
        if (senderUser !== messageData.senderId) {
            markGroupAsSeen(conversationId, key);
        }
    }

    scrollToBottom();
}

function createMessageElement(key, messageData, isGroup) {
    const isSender = senderUser === messageData.senderId;
    const isReceiver = senderUser !== messageData.senderId;
    const senderName =
        (isGroup == "true" || isGroup == true) && !isSender
            ? SelecteGroupUser[messageData.senderId].name
            : "";
    let seenStatus = "";
    let reaction = "";
    let dataWithMedia = "";
    if (isGroup == "true" || isGroup == true) {
        if (
            messageData.userAvailable != undefined &&
            Object.values(messageData.userAvailable).some(
                (value) => value === 0
            )
        ) {
            seenStatus = "grey-tick";
        } else {
            seenStatus = "blue-tick";
        }
        reaction = messageData?.messageReact
            ? Object.values(messageData.messageReact)
                  .map((reactData) =>
                      String.fromCodePoint(
                          parseInt(
                              reactData.react.replace(/\\u\{(.+)\}/, "$1"),
                              16
                          )
                      )
                  )
                  .join(" ")
            : "";
    } else {
        seenStatus = isSender
            ? messageData.isSeen
                ? "blue-tick"
                : "grey-tick"
            : "";
        reaction =
            messageData?.react != "" && messageData?.react
                ? String?.fromCodePoint(
                      parseInt(
                          messageData?.react?.replace(/\\u\{(.+)\}/, "$1"),
                          16
                      )
                  )
                : "";
    }
    dataWithMedia =
        messageData?.type == "1"
            ? `<div class="media-msg">
                <img src="${messageData?.url}"/>
                <span>${messageData?.data != "" ? messageData.data : ""}</span>
                 ${
                     isSender
                         ? `<span class="seenStatus ${seenStatus}"></span>`
                         : ""
                 } 
                ${reaction ? `<span class="reaction">${reaction}</span>` : ""}
            </div>`
            : messageData?.type == "3"
            ? `<div class="media-msg">
            <audio controls src="${messageData?.url}"></audio>
            <span>${
                messageData?.data != "" ? messageData.data : ""
            }</span></div>`
            : `
            <div class="simple-message"> 
                <p> 
                    <span class="senderName">${senderName}</span>
                    ${messageData?.data != "" ? messageData.data : ""}
                    ${
                        isSender
                            ? `<span class="seenStatus ${seenStatus}"></span>`
                            : ""
                    } 
                    ${
                        reaction
                            ? `<span class="reaction">${reaction}</span>`
                            : ""
                    }
                </p>
                ${
                    isReceiver
                        ? `
                          <span class="reaction-icon" data-message-id="${key}">😊</span>
                          <span class="reply-icon" data-message-id="${key}">↩️</span>`
                        : ""
                }
              </div>
              `;

    const replySection =
        messageData.replyData && messageData.replyData.replyTimeStamp != 0
            ? `
        
        <div class="reply-section">
            <strong>${messageData.replyData.replyMessage}</strong>
            <div class="reply-info">
                <span class="reply-username">${
                    messageData.replyData.replyUserName
                }</span>
                <span class="reply-timestamp">${timeago.format(
                    new Date(messageData.replyData.replyTimeStamp)
                )}</span>
            </div>
            <hr>
        </div>`
            : "";
    return `
        <li class="${isSender ? "receiver" : "sender"}" id="message-${key}">
         ${replySection}
            
            ${dataWithMedia}
         
            <span class="time">${timeago.format(
                new Date(messageData.timeStamp)
            )}</span>
            
            
             
            
        </li>
    `;
}
function markMessageAsSeen(conversationId, key) {
    const msgRef = ref(database, `/Messages/${conversationId}/message/${key}`);
    update(msgRef, { isSeen: true });
}
function markGroupAsSeen(conversationId, key) {
    const msgRef = ref(
        database,
        `/Groups/${conversationId}/message/${key}/userAvailable/${senderUser}/`
    );
    set(msgRef, Date.now());
}

function scrollToBottom() {
    const container = document.getElementById("msgBody");
    container.scroll({
        top: container.scrollHeight,
        behavior: "smooth",
    });
}

var selectedUserIds = [];
$("#search-user")
    .autocomplete({
        source: async function (request, response) {
            try {
                const result = await $.ajax({
                    url: base_url + "autocomplete-users",
                    dataType: "json",
                    data: {
                        term: request.term,
                        selectedUserIds: selectedUserIds,
                    },
                });

                const processedData = await Promise.all(
                    result.map(async (item) => ({
                        label: item.name,
                        value: item.name,
                        userId: item.id,
                        imageUrl: item.profile,
                        email: item.email,
                        imageElement: await getListUserimg(
                            item.profile,
                            item.name
                        ),
                    }))
                );

                response(processedData);
            } catch (error) {
                console.error("Error fetching data", error);
                response([]);
            }
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
    const $img = item.imageElement;
    console.log($img);
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

async function handleSelectedUsers() {
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
            await getSelectedUserimg(userImgSrc, userName)
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
        $tags.each(async function (index) {
            if (index < 2) {
                const userName = $(this).text().trim();
                const userImgSrc = $(this).find("img").attr("src");
                groupNames += `<div class="multi-img grp-img">
                                    ${await getSelectedUserimg(
                                        userImgSrc,
                                        userName
                                    )}
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

async function generateConversationId(userIds) {
    const sortedUserIds = userIds.slice().sort();
    const concatenatedIds = sortedUserIds.join("");

    // Generate hash value using SHA-256
    async function sha256(str) {
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

    const newConversationRef = push(child(ref(database), "overview"));
    const newConversationId = newConversationRef.key;
    // const newConversationId = await generateConversationId(newGroupMembers);

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
                replyData: {
                    replyChatKey: "",
                    replyDocType: "",
                    replyMessage: "",
                    replyTimeStamp: 0,
                    replyUserName: "",
                },
            };

            var userAvailable = [];
            newGroupMembers.map(async (memberID) => {
                if (memberID !== senderUser) {
                    userAvailable[memberID] = 0;
                }
            });

            messageData.userAvailable = userAvailable;
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
            await updateOverview(currentUserId, conversationId, {
                lastMessage: `${senderUserName}: ${message}`,
                timeStamp: Date.now(),
            });
            for (const memberId of newGroupMembers) {
                const receiverSnapshot = await get(
                    ref(database, `overview/${memberId}/${conversationId}`)
                );
                await updateOverview(memberId, conversationId, {
                    lastMessage: `${senderUserName}: ${message}`,
                    unReadCount: (receiverSnapshot.val().unReadCount || 0) + 1,
                    timeStamp: Date.now(),
                });
            }
            $("#isGroup").val("true");
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
        $("#isGroup").val("false");
    }
});

const isOfflineForDatabase = {
    userStatus: "offline",
    userLastSeen: Date.now(),
};
await onDisconnect(userRef).update(isOfflineForDatabase);
// Load user images
$(".user-image").each(async function () {
    const dataId = $(this).attr("data-id");
    const user = await getUser(dataId);
    $(this).attr("src", user?.userProfile);
});

async function addListInMembers(SelecteGroupUser) {
    let isGroup = $("#isGroup").val();
    let senderIsAdmin = false;

    // Check if the senderUser is an admin
    SelecteGroupUser.forEach((user) => {
        if (user.id == senderUser && user.isAdmin == "1") {
            senderIsAdmin = true;
        }
    });

    let messageElement = ``;
    const promises = SelecteGroupUser.map(async (user) => {
        const userImageElement = await getListUserimg(user.image, user.name);

        const removeMember =
            user.id != senderUser && senderIsAdmin
                ? `<button class="remove-member" data-id="${user.id}"><i class="fa-solid fa-xmark"></i></button>`
                : "";

        messageElement +=
            user.leave == false
                ? `<li class="">
                            <div class="chat-data d-flex">
                                <div class="user-img position-relative">
                                    ${userImageElement}
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
                                ${removeMember}
                            </div>
                        </li>`
                : "";
    });

    await Promise.all(promises);
    $(".member-lists").html(messageElement);
}

$(document).on("click", ".remove-member", async function () {
    console.log("remove");
    const userId = $(this).attr("data-id");
    var conversationId = $(".conversationId").attr("conversationid");
    console.log(conversationId);
    console.log(userId);
    var overviewRef = ref(database, `overview/${userId}/${conversationId}`);
    await remove(overviewRef);

    var groupInfoProfileRef = ref(
        database,
        `/Groups/${conversationId}/groupInfo/profiles`
    );
    var groupInfoProfileSnap = await get(groupInfoProfileRef);
    var groupInfoProfileData = groupInfoProfileSnap.val();

    if (groupInfoProfileData) {
        for (var key in groupInfoProfileData) {
            if (groupInfoProfileData[key].id == userId) {
                await update(
                    ref(
                        database,
                        `/Groups/${conversationId}/groupInfo/profiles/${key}`
                    ),
                    { isAdmin: "0", leave: true }
                );
                break;
            }
        }
    }
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
    await addListInMembers(SelecteGroupUser);
});
$(".delete-conversation").click(async function () {
    var conversationId = $(".conversationId").attr("conversationid");
    const isGroup = $("#isGroup").val();
    if (!conversationId || !senderUser) {
        console.error("Conversation ID or Sender User ID is missing");
        return;
    }
    $(".conversation-" + conversationId).remove();
    var msgLists = $(".msg-list");
    if (msgLists.length > 0) {
        msgLists.first().click(); // Simulate a click event on the first msg-list element
    }
    var overviewRef = ref(database, `overview/${senderUser}/${conversationId}`);

    await remove(overviewRef);
    if (isGroup === "true" || isGroup == true) {
        var groupInfoProfileRef = ref(
            database,
            `/Groups/${conversationId}/groupInfo/profiles`
        );
        var groupInfoProfileSnap = await get(groupInfoProfileRef);
        var groupInfoProfileData = groupInfoProfileSnap.val();

        if (groupInfoProfileData) {
            // Check if senderUser is an admin using array method
            var isAdmin = false;
            for (var key in groupInfoProfileData) {
                if (groupInfoProfileData[key].id == senderUser) {
                    if (groupInfoProfileData[key].isAdmin == "1") {
                        isAdmin = true;
                    }
                    await update(
                        ref(
                            database,
                            `/Groups/${conversationId}/groupInfo/profiles/${key}`
                        ),
                        { isAdmin: "0", leave: true }
                    );
                    break;
                }
            }

            // If senderUser is an admin, assign admin to another member
            if (isAdmin) {
                for (var key in groupInfoProfileData) {
                    if (
                        groupInfoProfileData[key].leave == false &&
                        groupInfoProfileData[key].id != senderUser
                    ) {
                        await update(
                            ref(
                                database,
                                `/Groups/${conversationId}/groupInfo/profiles/${key}`
                            ),
                            { isAdmin: "1" }
                        );
                        break;
                    }
                }
            }
        }
    } else {
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

$("#new-member").click(function () {
    $(".new-member").addClass("d-none");
    $(".new-members-add").removeClass("d-none");
    selectedgrpUserIds = SelecteGroupUser.map(
        (user) => user.leave == false && user.id
    ).filter((id) => id);
});
$(".close-group-modal").click(function () {
    $(".new-members-add").addClass("d-none");
    $(".new-member").addClass("d-none");
});

var selectedgrpUserIds = [];
var newSelectedUserIds = [];
$("#group-search-user")
    .autocomplete({
        source: async function (request, response) {
            try {
                const result = await $.ajax({
                    url: base_url + "autocomplete-users",
                    dataType: "json",
                    data: {
                        term: request.term,
                        selectedUserIds: selectedgrpUserIds,
                    },
                });

                const processedData = await Promise.all(
                    result.map(async (item) => ({
                        label: item.name,
                        value: item.name,
                        userId: item.id,
                        imageUrl: item.profile,
                        email: item.email,
                        imageElement: await getListUserimg(
                            item.profile,
                            item.name
                        ),
                    }))
                );

                response(processedData);
            } catch (error) {
                console.error("Error fetching data", error);
                response([]);
            }
        },
        minLength: 2,
        select: function (event, ui) {
            const selectedUserId = ui.item.userId;
            const selectedUserName = ui.item.label;

            if (!selectedgrpUserIds.includes(selectedUserId)) {
                selectedgrpUserIds.push(selectedUserId);
                newSelectedUserIds.push(selectedUserId);
                updateSelectedgrpUserIds();

                const $tag = $("<div>", {
                    class: "tag",
                    "data-user-id": selectedUserId,
                })
                    .text(selectedUserName)
                    .append(
                        $("<span>", { class: "close-group-btn" }).html(
                            "&times;"
                        )
                    );
                $("#group-selected-tags-container").append($tag);
            }

            setTimeout(() => {
                $("#group-search-user").val("");
            }, 100);
        },
    })
    .data("ui-autocomplete")._renderItem = function (ul, item) {
    const $li = $("<li>");
    const $divMain = $("<div>").addClass("suggestion-item chat-data d-flex");
    const $divImage = $("<div>").addClass("user-img position-relative");
    const $divName = $("<div>").addClass("user-detail d-block ms-3");
    const $img = item.imageElement;
    console.log($img);
    const $span = $("<h3>").text(item.label);

    $divImage.append($img);
    $divName.append($("<div>")).append($span);
    $divMain.append($divImage).append($divName);
    $li.append($divMain).appendTo(ul);

    return $li;
};

function updateSelectedgrpUserIds() {
    $("#group-selected-user-id").val(newSelectedUserIds.join(","));
    if (newSelectedUserIds.length > 0) {
        $("#add-group-member").removeClass("d-none");
    } else {
        $("#add-group-member").addClass("d-none");
    }
}
$("#add-group-member").click(async function () {
    try {
        console.log(newSelectedUserIds);

        if (newSelectedUserIds.length === 0) {
            return; // No new users to add
        }

        const conversationId = $(".conversationId").attr("conversationid");
        const groupInfoRef = ref(
            database,
            "Groups/" + conversationId + "/groupInfo"
        );
        const groupUsersRef = ref(
            database,
            "Groups/" + conversationId + "/users"
        );

        // Fetch current group info
        const groupInfoSnapshot = await get(groupInfoRef);
        let groupInfo = groupInfoSnapshot.exists()
            ? groupInfoSnapshot.val()
            : { profiles: {}, usersStatus: {} };

        // Fetch current users
        const usersSnapshot = await get(groupUsersRef);
        let users = usersSnapshot.exists() ? usersSnapshot.val() : [];
        console.log(SelecteGroupUser);

        await Promise.all(
            newSelectedUserIds.map(async (userId) => {
                userId = userId.toString();
                const user = await getUser(userId);
                console.log(user?.userProfile);

                // Check if the user is already in the group
                let userInGroup = false;
                for (let index in groupInfo.profiles) {
                    if (groupInfo.profiles[index].id === userId) {
                        // User found, check the leave status
                        if (groupInfo.profiles[index].leave) {
                            // Update leave status to false
                            groupInfo.profiles[index].leave = false;
                        }
                        userInGroup = true;
                        break;
                    }
                }

                if (!userInGroup) {
                    const newIndex = users.length; // Append new user at the end

                    // Update profiles
                    groupInfo.profiles[newIndex] = {
                        id: userId,
                        image: user?.userProfile || "",
                        isAdmin: "0",
                        leave: false,
                        name: user?.userName || "",
                    };

                    // Update users array
                    users.push(userId);
                }
            })
        );

        console.log(groupInfo);
        // Update Firebase with new group info and users
        await set(groupInfoRef, groupInfo);
        await set(groupUsersRef, users);

        $(".new-members-add").addClass("d-none");
        $(".new-member").addClass("d-none");
        $(".conversation-" + conversationId).click();
        // Clear the newSelectedUserIds array after adding
        newSelectedUserIds = [];
        updateSelectedgrpUserIds();
        $("#group-selected-tags-container").html("");
    } catch (error) {
        console.error("Error adding new users to the group:", error);
    }
});

$(document).on("click", ".close-group-btn", function () {
    const $tag = $(this).parent(".tag");
    const userId = $tag.data("user-id");

    // $(this).parent(".tag").remove();
    selectedgrpUserIds = selectedgrpUserIds.filter((id) => id !== userId);
    updateSelectedgrpUserIds();
    $tag.remove();
});
$("#new-message").click(function () {
    selectedUserIds = [];
    $("#selected-tags-container").html("");
    updateSelectedUserIds();
    handleSelectedUsers();
});
function generateReactionsAndReply() {
    $(document).on("click", ".reaction-icon", function () {
        const messageId = $(this).data("message-id");
        const reactionDialog = `
        <div class="reaction-dialog" id="reaction-dialog-${messageId}">
            <span class="reaction-option" data-reaction="\\u{1F60D}">&#x1F60D;</span>
            <span class="reaction-option" data-reaction="\\u{1F604}">&#x1F604;</span>
            <span class="reaction-option" data-reaction="\\u{2764}}">&#x2764;</span>
            <span class="reaction-option" data-reaction="\\u{1F44D}">&#x1F44D;</span>
            <span class="reaction-option" data-reaction="\\u{1F44F}}">&#x1F44F;</span>
        </div>
    `;
        $(this).after(reactionDialog);
    });

    $(document).on("click", ".reaction-option", async function () {
        const reaction = $(this).data("reaction");
        const isGroup = $("#isGroup").val();
        const messageId = $(this)
            .closest(".reaction-dialog")
            .attr("id")
            .replace("reaction-dialog-", "");
        const conversationId = $(".conversationId").attr("conversationid");

        // Remove the dialog after selecting a reaction
        $(`#reaction-dialog-${messageId}`).remove();

        // Handle sending reaction to the message here
        console.log(`Reaction: ${reaction}, Message ID: ${messageId}`);

        try {
            if (isGroup === "true" || isGroup == true) {
                const reactionRef = ref(
                    database,
                    `Groups/${conversationId}/message/${messageId}/messageReact/${senderUser}/`
                );

                await set(reactionRef, {
                    react: reaction,
                    reactTimeStamp: Date.now(),
                });
            } else {
                const reactionRef = ref(
                    database,
                    `Messages/${conversationId}/message/${messageId}/react`
                );
                await set(reactionRef, reaction);
            }

            console.log("Reaction updated successfully in Firebase");
        } catch (error) {
            console.error("Error updating reaction in Firebase:", error);
        }
    });

    $(document).on("click", ".reply-icon", async function () {
        replyMessageId = $(this).data("message-id");
        let conversationId = $(".conversationId").attr("conversationid");
        let replay = "";
        let isGroup = $("#isGroup").val();
        if (isGroup === "true" || isGroup == true) {
            const replyMessageRef = ref(
                database,
                `Groups/${conversationId}/message/${replyMessageId}`
            );
            const replyMessageSnapshot = await get(replyMessageRef);
            const replyMessageData = replyMessageSnapshot.val();
            replay = `<div class='set-replay-msg'>
                        <span class='replay-user'>${replyMessageData.receiverName}</span>
                        <span class='replay-msg'>${replyMessageData.data}</span>
                         <span class='close-replay'>&times</span>
                        </div>`;
        } else {
            const replyMessageRef = ref(
                database,
                `Messages/${conversationId}/message/${replyMessageId}`
            );
            const replyMessageSnapshot = await get(replyMessageRef);
            const replyMessageData = replyMessageSnapshot.val();

            replay = `<div class='set-replay-msg'>
                        <span class='replay-user'>${senderUserName}</span>
                        <span class='replay-msg'>${replyMessageData.data}</span>
                        <span class='close-replay'>&times</span>
                        </div>`;
        }
        $(".msg-footer").prepend(replay);
    });
    $(document).on("click", ".close-replay", async function () {
        $(".set-replay-msg").remove();
        replyMessageId = null;
    });
}

generateReactionsAndReply();

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

let mediaRecorder;
let recordedChunks = [];

const startButton = document.getElementById("startRecording");
const stopButton = document.getElementById("stopRecording");
const playButton = document.getElementById("playRecording");
const stopPlaybackButton = document.getElementById("stopPlayback");
const audioElement = document.getElementById("recordedAudio");
const close = document.getElementsByClassName("close-audio-btn");

function startRecording() {
    recordedChunks = [];

    navigator.mediaDevices
        .getUserMedia({ audio: true })
        .then((stream) => {
            mediaRecorder = new MediaRecorder(stream);

            mediaRecorder.start();
            startButton.style.display = "none";
            stopButton.style.display = "inline-block";
            playButton.style.display = "none";
            stopPlaybackButton.style.display = "none";
            // close.style.display = "none";

            mediaRecorder.ondataavailable = (event) => {
                recordedChunks.push(event.data);
            };
        })
        .catch((err) => {
            console.error("Error accessing microphone: ", err);
            alert("Failed to access microphone. Please try again.");
        });
}

function playRecording() {
    $(".close-audio-btn").show();

    const blob = new Blob(recordedChunks, { type: "audio/wav" });
    const audioURL = URL.createObjectURL(blob);

    audioElement.src = audioURL;
    audioElement.style.display = "block";

    playButton.style.display = "none";
    // stopPlaybackButton.style.display = "inline-block";

    audioElement.play().catch((err) => {
        console.error("Error playing audio: ", err);
        alert("Failed to play recorded audio.");
    });
}
async function stopRecording() {
    if (mediaRecorder && mediaRecorder.state === "recording") {
        mediaRecorder.stop();
        $("#send_audio").show();
        $("#audioContainer").show();

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

startButton.addEventListener("click", startRecording);
stopButton.addEventListener("click", stopRecording);
playButton.addEventListener("click", playRecording);
stopPlaybackButton.addEventListener("click", stopPlayback);

$(".close-audio-btn").on("click", function () {
    $("#audioContainer").hide();
    $("#send_audio").hide();
    $(".preview_img").attr("src", "");
    $(".recordedAudio").attr("src", "");

    $(".upload-box").val("");
});

$(".preview_img").hide();
$(".upload-box").change(function () {
    var curElement = $(".preview_img");
    var file = this.files[0];
    var name = file.name;
    $(".dropdown-menu").removeClass("show");

    var preview = document.getElementById("preview");

    displayFiles(this.files, name);

    var fileExtension = file.name.substr(file.name.lastIndexOf(".") + 1);

    console.log(fileExtension);

    if (file) {
        var reader = new FileReader();

        if (file.type.match("image.*")) {
            reader.onload = function (e) {
                $("#preview_file").hide();

                curElement.attr("src", e.target.result);
            };

            reader.readAsDataURL(file);
        } else if (file.type.match("video.*")) {
            // Handling video files
            var curElement = $(".preview_img");

            curElement.attr("src", URL.createObjectURL(file));

            $(".preview_img").hide();
            $("#preview_file").hide();
            $("#file_name").text("");
        } else if (file.type.match("audio.*")) {
            var curElement = $(".preview_img");

            curElement.attr("src", URL.createObjectURL(file));

            $(".preview_img").hide();
            $("#preview_file").hide();

            $("#file_name").text("audio");
        } else {
            reader.onload = function (e) {
                curElement.attr("src", e.target.result);
            };
            reader.readAsDataURL(file);
            $(".file_info").val(fileExtension);
            $("#file_name").text(file.name);

            $("#preview_file").show();
            $(".preview_img").hide();
        }
        // reader.readAsArrayBuffer(file);
    } else {
        $(".preview_img").hide();
        curElement.attr("src", "");
    }
});

function displayFiles(files, name) {
    preview.innerHTML = "";

    for (var i = 0; i < files.length; i++) {
        var file = files[i];
        var reader = new FileReader();

        reader.onload = (function (file) {
            return function (e) {
                var fileType = file.type.split("/")[0];
                var previewItem = document.createElement("div");
                previewItem.className = "preview-item";
                var previewElement;

                if (fileType === "video") {
                    previewElement = document.createElement("video");
                    previewElement.controls = true;
                } else if (fileType === "audio") {
                    previewElement = document.createElement("audio");
                    previewElement.controls = true;

                    var fileName = document.createElement("span");
                    fileName.className = "file-name";
                    fileName.textContent = name;
                } else if (fileType === "image") {
                    previewElement = document.createElement("img");
                    previewElement.style.maxWidth = "100%";
                } else {
                    return;
                }

                var closeButton = document.createElement("button");
                closeButton.innerHTML = "&#10006;";
                closeButton.className = "close-button";

                previewElement.src = e.target.result;
                previewItem.appendChild(previewElement);

                if (fileType === "audio") {
                    previewItem.appendChild(fileName);
                }

                previewItem.appendChild(closeButton);
                preview.appendChild(previewItem);
            };
        })(file);

        reader.readAsDataURL(file);
    }
}
