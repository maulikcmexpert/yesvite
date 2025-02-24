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
    onValue,
} from "https://www.gstatic.com/firebasejs/10.12.2/firebase-database.js";
import {
    getStorage,
    ref as storageRef,
    uploadString,
    getDownloadURL,
    uploadBytes,
} from "https://www.gstatic.com/firebasejs/10.12.2/firebase-storage.js";

$.ajaxSetup({
    headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
});
import { genrateAudio } from "./chat.js";
import { musicPlayer, initializeAudioPlayer } from "./audio.js";

document.getElementById("message-box").addEventListener("input", function () {
    const textarea = this;

    // Reset the height of the textarea
    textarea.style.height = "auto";

    // Calculate and set the new height based on scrollHeight, but limit to a max height
    const maxHeight = 100; // Set your max height here (e.g., 4-5 lines)
    textarea.style.height = Math.min(textarea.scrollHeight, maxHeight) + "px";

    // If the scroll height is greater than the max height, add scrollbar
    if (textarea.scrollHeight > maxHeight) {
        textarea.style.overflowY = "scroll"; // Enable vertical scrolling
    } else {
        textarea.style.overflowY = "hidden"; // Hide scroll if less than max height
    }
});

function formatDate(timestamp) {
    const now = new Date();
    const date = new Date(timestamp);

    // Reset the time part of both dates to midnight
    const nowMidnight = new Date(
        now.getFullYear(),
        now.getMonth(),
        now.getDate()
    );
    const dateMidnight = new Date(
        date.getFullYear(),
        date.getMonth(),
        date.getDate()
    );
    const diffTime = nowMidnight - dateMidnight;
    const diffDays = Math.floor(diffTime / (1000 * 60 * 60 * 24));

    if (diffDays < 1) {
        return "Today";
    } else if (diffDays < 2) {
        return "Yesterday";
    }
    // else if (diffDays < 7) {
    //     return date.toLocaleDateString("en-US", { weekday: "long" }); // Returns day of the week
    // }
    else if (now.getFullYear() === date.getFullYear()) {
        return date.toLocaleDateString("en-US", {
            day: "numeric",
            month: "long",
        }); // Returns day, date, month
    } else {
        return date.toLocaleDateString("en-US", {
            day: "numeric",
            month: "long",
            year: "numeric",
        }); // Returns day, date, month, year
    }
}

function getInitials(userName) {
    // console.log(userName);
    if (userName === undefined || userName === "") {
        return "Y"; // Default to "Y" if userName is undefined or an empty string
    }

    const initials = userName
        .split(" ")
        .map((word) => word[0]?.toUpperCase())
        .join("")
        .slice(0, 2); // Get only the first and second letters

    return initials;
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

function isValidImageUrlgrp(profileImageUrl) {
    if (
        profileImageUrl &&
        (profileImageUrl.includes(".jpg") ||
            profileImageUrl.includes(".jpeg") ||
            profileImageUrl.includes(".png")) &&
        imageExists(profileImageUrl)
    ) {
        return 1;
    }
    return 0;
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

$("#selected-user-profile").replaceWith(
    `<h5 class="fontcolorS" id="selected-user-profile" >SN</h5>`
);

async function updateProfileImg(profileImageUrl, userName, conversationId) {
    console.log("coming here for update profile");
    let profileIm = document.getElementById("profileIm");
    let profileModel = document.getElementById("profileModel");
    console.log({ profileImageUrl });
    if (await isValidImageUrl(profileImageUrl)) {
        console.log("123");

        $("#selected-user-profile").replaceWith(
            `<img id="selected-user-profile" src="${profileImageUrl}" alt="user-img">`
        );
        $(profileIm).replaceWith(
            `<img id="profileIm" src="${profileImageUrl}" alt="cover-img" >`
        );
        $(profileModel).replaceWith(
            `<img id="profileModel" src="${profileImageUrl}" alt="cover-img" >`
        );
        if (conversationId != "") {
            $(".conversation-" + conversationId)
                .find(".chat-data")
                .find(".user-img")
                .html(
                    `<img class="user-avatar img-fluid" src="${profileImageUrl}" alt="cover-img" >`
                );
        }
    } else {
        console.log("456");

        const initials = getInitials(userName);
        const fontColor = "fontcolor" + initials[0]?.toUpperCase();

        $("#selected-user-profile").replaceWith(
            `<h5 class="${fontColor}" id="selected-user-profile" >${initials}</h5>`
        );
        $(profileIm).replaceWith(
            `<h5 id="profileIm" class="${fontColor}">${initials}</h5>`
        );
        $(profileModel).replaceWith(
            `<h5 id="profileModel" class="${fontColor}">${initials}</h5>`
        );
        if (conversationId != "") {
            $(".conversation-" + conversationId)
                .find(".chat-data")
                .find(".user-img")
                .html(
                    `<h5 class="user-avatar img-fluid ${fontColor}">${initials}</h5>`
                );
        }
    }
}

async function getSelectedUserimg(profileImageUrl, userName) {
    if (await isValidImageUrl(profileImageUrl)) {
        return `<img class="selected-user-img" src="${profileImageUrl}" alt="user-img">`;
    } else {
        const initials = getInitials(userName);
        const fontColor = "fontcolor" + initials[0]?.toUpperCase();

        return `<h5 class="${fontColor} selected-user-img user-img"  src="">${initials}</h5>`;
    }
}

async function getListUserimg(profileImageUrl, userName) {
    if (await isValidImageUrl(profileImageUrl)) {
        return `<img class="user-avatar img-fluid" src="${profileImageUrl}" alt="user-img">`;
    }

    const initials = getInitials(userName);
    const fontColor = "fontcolor" + initials[0]?.toUpperCase();

    return `<h5 class="${fontColor} user-avatar img-fluid" src="">${initials}</h5>`;
}

function getSelectedUserimggrp(profileImageUrl, userName) {
    // console.log(isValidImageUrlgrp(profileImageUrl))
    if (isValidImageUrlgrp(profileImageUrl) == 1) {
        return `<img class="user-avatar img-fluid" src="${profileImageUrl}" alt="user-img">`;
    } else {
        // console.log(userName);

        const initials = getInitials(userName);
        // console.log(initials);

        const fontColor = "fontcolor" + initials[0]?.toUpperCase();

        return `<h5 class="${fontColor} user-avatar img-fluid" src="">${initials}</h5>`;
    }
}

// function getSelectedUserimggrp(profileImageUrl, userName) {
//     return isValidImageUrlgrp(profileImageUrl).then((isValid) => {
//         if (isValid) {
//             return `<img class="user-avatar img-fluid" src="${profileImageUrl}" alt="user-img">`;
//         }

//         const initials = getInitials(userName);
//         const fontColor = "fontcolor" + initials[0]?.toUpperCase();

//         return `<h5 class="${fontColor} user-avatar img-fluid">${initials}</h5>`;
//     });
// }

// Initialize Firebase
const asset_path = $("#asset_path").val();

const reactionImageMap = {
    "1F60D": `${asset_path}/ic_heart_eyes.png`, // Heart Eyes
    "1F604": `${asset_path}/ic_smile.png`, // Smile
    2764: `${asset_path}/ic_like_reaction.png`, // Heart
    "1F44D": `${asset_path}/ic_thumsup.png`, // Thumbs Up
    "1F44F": `${asset_path}/ic_clapping_hand.png`, // Clapping Hands
};

const response = await fetch("/firebase_js.json");
const firebaseConfig = await response.json();
// console.log(firebaseConfig);
const app = initializeApp(firebaseConfig);
const database = getDatabase(app);
const storage = getStorage(app);
const senderUser = $(".senderUser").val();
const senderUserName = $(".senderUserName").val();
const base_url = $("#base_url").val();
const userRef = ref(database, `users/${senderUser}`);
let replyMessageId = null; // Global variable to hold the message ID to reply to
let fileType = null; // Global variable to hold the message ID to reply to
let WaitNewConversation = null; // Global variable to hold the message ID to reply to
let myProfile;
const loader = $(".loader");
loader.css("display", "flex");
// Function to get messages between two users
var firstTime = true;
var isToMove = true;
let closeSpan = `<svg width="17" height="18" viewBox="0 0 17 18" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M8.4974 0.666016C3.90573 0.666016 0.164062 4.40768 0.164062 8.99935C0.164062 13.591 3.90573 17.3327 8.4974 17.3327C13.0891 17.3327 16.8307 13.591 16.8307 8.99935C16.8307 4.40768 13.0891 0.666016 8.4974 0.666016ZM11.2974 10.916C11.5391 11.1577 11.5391 11.5577 11.2974 11.7993C11.1724 11.9243 11.0141 11.9827 10.8557 11.9827C10.6974 11.9827 10.5391 11.9243 10.4141 11.7993L8.4974 9.88268L6.58073 11.7993C6.45573 11.9243 6.2974 11.9827 6.13906 11.9827C5.98073 11.9827 5.8224 11.9243 5.6974 11.7993C5.45573 11.5577 5.45573 11.1577 5.6974 10.916L7.61406 8.99935L5.6974 7.08268C5.45573 6.84102 5.45573 6.44102 5.6974 6.19935C5.93906 5.95768 6.33906 5.95768 6.58073 6.19935L8.4974 8.11602L10.4141 6.19935C10.6557 5.95768 11.0557 5.95768 11.2974 6.19935C11.5391 6.44102 11.5391 6.84102 11.2974 7.08268L9.38073 8.99935L11.2974 10.916Z" fill="#F73C71"/>`;
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
            var user = await getUser(userId);
            if (!user) {
                try {
                    await updateUserInFirebase(userId);
                    user = await getUser(userId);
                    if (!user) {
                        throw new Error(
                            "User not found in Firebase after update"
                        );
                    }
                } catch (error) {
                    toastr.error(
                        "Some user is not updated, they are not added in group.",
                        "Error!"
                    );
                    console.error(error);
                    return;
                }
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
        await set(groupInfoRef, groupInfo);
    }
}
// Function to handle a new conversation
async function handleNewConversation(snapshot) {
    const newConversation = snapshot.val();

    // console.log("New conversation added:", newConversation);
    if (newConversation.conversationId == undefined) {
        console.warn("undefined");
        return;
    }

    const conversationElement = document.getElementsByClassName(
        `conversation-${newConversation.conversationId}`
    );

    let userStatus = "";
    if (newConversation.group !== "true" && newConversation.group !== true) {
        let userId = newConversation.contactId;
        let userData = await getUser(userId);

        if (
            userData?.userStatus == "Online" ||
            userData?.userStatus == "online"
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
            .find(".ms-auto .time-ago")
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
        // $(conversationElement)
        //     .find(".user-img")
        //     .find("span")
        //     .replaceWith(userStatus);

        const userImg = $(conversationElement).find(".user-img");

        const spanElement = userImg.find("span");
        if (spanElement.length) {
            spanElement.replaceWith(userStatus);
        } else {
            userImg.append(userStatus);
        }
        const badgeElement = $(conversationElement).find(
            ".ms-auto .d-flex .my-badge"
        );
        badgeElement.text(newConversation.unReadCount);
        if (parseInt(newConversation.unReadCount) == 0) {
            badgeElement.addClass("d-none");
            $(conversationElement).removeClass("setpink");
            $(conversationElement).attr(
                "data-msgTime",
                newConversation.timeStamp
            );
        } else {
            badgeElement.removeClass("d-none");
            badgeElement.show();
            $(conversationElement).addClass("setpink");
            $(conversationElement).attr(
                "data-msgTime",
                newConversation.timeStamp
            );
            console.log("here");
        }
    } else {
        if (WaitNewConversation == newConversation.conversationId) {
            return;
        }
        WaitNewConversation = newConversation.conversationId;

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
        await updateOverview(senderUser, newConversation.conversationId, {
            unRead: false,
            unReadCount: 0,
        });
        console.log("updateoverview");
    }
    updateUnreadMessageBadge();
    var ele = $(
        document.getElementsByClassName(
            `conversation-${newConversation.conversationId}`
        )
    );
    moveToTopOrBelowPinned(ele);
}
function moveToTopOrBelowPinned(element) {
    if (firstTime == true || !isToMove) {
        isToMove = true;
        return;
    }
    if (element.length <= 0) {
        return;
    }
    console.log("moved====================");

    let $chatList = $(".chat-list"); // Get the chat list container
    let parentDiv = element.closest("div"); // Get the parent div of the li element
    let isPinned = element.hasClass("pinned"); // Check if the element is pinned
    let elementMsgTime = parseInt(element.attr("data-msgtime")); // Get the message time of the element

    // If the element is pinned, move it to the very top
    if (isPinned) {
        console.log("Pinned element moved to the top");
        $chatList.prepend(parentDiv); // Move pinned element to the top
    } else {
        // If not pinned, move it after the last pinned element, or based on `data-msgtime`
        let lastPinnedDiv = $chatList.find("div:has(.pinned)").last();
        let nonPinnedDivs = $chatList.find("div:not(:has(.pinned))");

        if (lastPinnedDiv.length > 0) {
            // Place after the last pinned element
            console.log("Placed after the last pinned element");
            lastPinnedDiv.after(parentDiv);
        } else {
            // Sort by `data-msgtime` if there are no pinned elements
            let inserted = false;
            nonPinnedDivs.each(function () {
                let currentLi = $(this).find("li");
                let currentMsgTime = parseInt(currentLi.attr("data-msgtime"));

                if (elementMsgTime > currentMsgTime) {
                    $(this).before(parentDiv);
                    inserted = true;
                    return false; // Break the loop
                }
            });

            // If not inserted, append it at the end
            if (!inserted) {
                $chatList.append(parentDiv);
            }
        }
    }
}

function reorderPinnedElements($chatList) {
    console.log("reorderPinnedElements");
    let $pinnedElements = $chatList.children("div.pinned");

    // Move all pinned elements to the top of the list in the order they appear
    $pinnedElements.each(function () {
        $chatList.prepend($(this));
    });
}
function removeSelectedMsg() {
    var msgLists = document.getElementsByClassName("msg-list");
    for (var i = 0; i < msgLists.length; i++) {
        msgLists[i].classList.remove("active");
    }
    $(".typing").text("");
}
// Function to handle changes to existing conversations in the overview
function handleConversationChange(snapshot) {
    const updatedConversation = snapshot.val();
    console.log(1234);

    handleNewConversation(snapshot);
}

// Helper function to update user in Firebase from backend
function updateUserInFirebase(user_id) {
    return new Promise((resolve, reject) => {
        $.ajax({
            url: base_url + "updateUserinFB",
            data: { userId: user_id },
            type: "post",
            dataType: "json",
            success: function (res) {
                resolve(res);
            },
            error: function (res) {
                reject(res);
            },
        });
    });
}

// Function to update the chat UI
// $('.empty-massage').css('display','none');
$(".msg-head").css("display", "none");
$(".msg-footer").css("display", "none");

async function updateChat(user_id) {
    $(".msg-lists").html("");
    $(".member-lists").html("");
    $(".choosen-file").hide();

    if (user_id == "") return loader.hide();

    var selected_user = await getUser(user_id);
    if (!selected_user) {
        // $('.msg-head').css('display','none');
        // $('.msg-footer').css('display','none');
        try {
            await updateUserInFirebase(user_id);
            selected_user = await getUser(user_id);
            if (!selected_user) {
                throw new Error("User not found in Firebase after update");
            }
        } catch (error) {
            toastr.error("User not found in Firebase");
            console.error(error);
            return;
        }

        // $('.empty-massage').css('display','block');
    }

    $(".empty-massage").css("display", "none");
    $(".msg-head").css("display", "block");
    $(".msg-footer").css("display", "block");
    $("#selected-user-lastseen").show();

    const messageTime = selected_user.userLastSeen
        ? new Date(selected_user.userLastSeen)
        : new Date();

    console.log(messageTime);

    let lastseen =
        selected_user.userStatus == "offline" ||
        selected_user.userStatus == "Offline"
            ? `last seen at ${timeago.format(messageTime)}`
            : selected_user.userStatus == "Online" ||
              selected_user.userStatus == "online"
            ? "Online"
            : "";
    let userStatusSpan = "";
    if (lastseen == "Online" || lastseen == "online") {
        userStatusSpan = `<span class="active"></span>`;
    } else {
        userStatusSpan = `<span class="inactive"></span>`;
    }
    $("#selected-user-lastseen").html(lastseen);
    $("#selected-user-name").html(selected_user.userName);

    const profileImageUrl = selected_user.userProfile;

    $(".selected_name").val(selected_user.userName);
    $(".selected-title").html(selected_user.userName);

    $(".selected_conversasion").val($(".selected_id").val());
    const conversationId = $(".selected_id").val();

    $(".report-conversation").show();
    $(".report-conversation").attr("data-conversation", conversationId);
    $(".report-conversation").attr("data-userId", user_id);
    $(".conversationId").attr("conversationId", conversationId);
    await updateProfileImg(
        profileImageUrl,
        selected_user.userName,
        conversationId
    );
    const conversationElement = document.getElementsByClassName(
        `conversation-${conversationId}`
    );
    const userImg = $(conversationElement).find(".user-img");

    const spanElement = userImg.find("span");
    if (spanElement.length) {
        spanElement.replaceWith(userStatusSpan);
    } else {
        userImg.append(userStatusSpan);
    }
    update(userRef, { userChatId: conversationId });

    const messagesRef = ref(database, `Messages/${conversationId}/message`);
    const selecteduserTypeRef = ref(database, `users/${user_id}`);
    off(messagesRef);
    off(selecteduserTypeRef);

    // Set up block/unblock observers
    const blockByMeRef = ref(database, `users/${senderUser}/blockByUser`);
    const blockByUserRef = ref(database, `users/${senderUser}/blockByMe`);

    const checkBlockStatus = async () => {
        const blockByMeSnapshot = await get(blockByMeRef);
        const blockByUserSnapshot = await get(blockByUserRef);

        let isBlockedByMe = false;
        let isBlockedByUser = false;

        if (blockByMeSnapshot.exists()) {
            const blockByMeList = blockByMeSnapshot.val();
            isBlockedByMe = blockByMeList.includes(user_id);
        }

        if (blockByUserSnapshot.exists()) {
            const blockByUserList = blockByUserSnapshot.val();
            isBlockedByUser = blockByUserList.includes(user_id);
        }

        if (isBlockedByMe || isBlockedByUser) {
            $(".msg-footer").hide();
            $("#selected-user-lastseen").hide();
        } else {
            $("#selected-user-lastseen").show();
            $(".msg-footer").show();
        }

        if (isBlockedByUser) {
            $(".block-conversation").find("span").text("Unblock");
        } else {
            $(".block-conversation").find("span").text("Block User");
        }
        $(".block-conversation").attr("blocked", isBlockedByUser);
    };

    // Initial block status check
    await checkBlockStatus();

    // Set up listeners for block/unblock changes
    onValue(blockByMeRef, async () => {
        await checkBlockStatus();
    });

    onValue(blockByUserRef, async () => {
        await checkBlockStatus();
    });

    onChildAdded(messagesRef, async (snapshot) => {
        addMessageToList(snapshot.key, snapshot.val(), conversationId);

        const selectedConversationId = $(".selected_conversasion").val();
        if (selectedConversationId === conversationId) {
            await updateOverview(senderUser, conversationId, {
                unRead: false,
                unReadCount: 0,
            });
            console.log("updateoverview");
        }
    });

    onChildChanged(messagesRef, async (snapshot) => {
        UpdateMessageToList(snapshot.key, snapshot.val(), conversationId);
    });

    onChildRemoved(messagesRef, async (snapshot) => {
        RemoveMessageToList(snapshot.key, conversationId);
    });

    onChildChanged(selecteduserTypeRef, async (snapshot) => {
        const Selectedsnapshot = await get(selecteduserTypeRef);
        const selectedUserData = Selectedsnapshot.val();
        const conversationId = $(".selected_id").val();
        const isGroup = $("#isGroup").val();
        let lastseen = "";

        if (isGroup == true || isGroup == "true") {
            lastseen = "";
        } else {
            lastseen =
                selectedUserData.userStatus == "offline" ||
                selectedUserData.userStatus == "Offline"
                    ? `last seen at ${timeago.format(messageTime)}`
                    : selectedUserData.userStatus == "Online" ||
                      selectedUserData.userStatus == "online"
                    ? "Online"
                    : "";
        }
        if (
            snapshot.key === "userTypingStatus" &&
            snapshot.val() == "Typing..."
        ) {
            if (selectedUserData.userChatId == conversationId) {
                $("#selected-user-lastseen").text("Typing...");
            } else {
                $("#selected-user-lastseen").text(lastseen);
            }
        } else {
            $("#selected-user-lastseen").text(lastseen);
        }
    });

    updateMore(conversationId);
    updateUnreadMessageBadge(conversationId);
    loader.hide();
}

var SelecteGroupUser = [];
async function updateChatfromGroup(conversationId) {
    SelecteGroupUser = [];
    $(".msg-lists").html("");
    $(".member-lists").html("");
    $(".choosen-file").show();
    $(".block-conversation").hide();
    $(".report-conversation").hide();

    $(".conversationId").attr("conversationId", conversationId);
    const groupInfoRef = ref(database, `Groups/${conversationId}/groupInfo`);
    const snapshot = await get(groupInfoRef);
    const groupInfo = snapshot.val();
    groupInfo?.profiles?.map((profile) => {
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
    $(".selected_conversasion").val(conversationId);
    const messagesRef = ref(database, `Groups/${conversationId}/message`);
    const profileRef = ref(
        database,
        `Groups/${conversationId}/groupInfo/profiles`
    );
    off(messagesRef);
    onChildChanged(messagesRef, async (snapshot) => {
        // console.log("1");
        UpdateMessageToList(snapshot.key, snapshot.val(), conversationId);
    });
    onChildChanged(profileRef, async (snapshot) => {
        const profile = snapshot.val();
        console.log({ profile });
        const profileIndex = await setProfileIndexCache(conversationId);
        const selectedConversationId = $(".selected_conversasion").val();
        if (
            selectedConversationId === conversationId &&
            snapshot.key != profileIndex &&
            profile.userTypingStatus == true
        ) {
            $(".typing").html(profile.name + " is typing");
        } else {
            $(".typing").html("");
        }
    });
    onChildRemoved(messagesRef, async (snapshot) => {
        // console.log("2");

        RemoveMessageToList(snapshot.key, conversationId);
    });
    onChildAdded(messagesRef, async (snapshot) => {
        // console.log("3");

        addMessageToList(snapshot.key, snapshot.val(), conversationId);

        const selectedConversationId = $(".selected_conversasion").val();
        if (selectedConversationId === conversationId) {
            await updateOverview(senderUser, conversationId, {
                unRead: false,
                unReadCount: 0,
            });
            console.log("udpateoverview");
        }
    });
    $("#selected-user-lastseen").html(""); // Group doesn't have a last seen
    $("#selected-user-name").html(groupInfo.groupName);
    await updateProfileImg(
        groupInfo.groupProfile,
        groupInfo.groupName,
        conversationId
    );

    $(".selected_name").val(groupInfo.groupName);

    $(".empty-massage").css("display", "none");
    $(".msg-head").css("display", "block");
    $(".msg-footer").css("display", "block");
    update(userRef, { userChatId: conversationId });
    await addListInMembers(SelecteGroupUser);
    $(".selected-title").html(groupInfo.groupName);
    $("#isGroup").val("true");
    updateMore(conversationId);
    updateUnreadMessageBadge(conversationId);
    loader.hide();
}

// Initialize event listeners
$(document).on("click", ".msg-list", async function () {
    loader.css("display", "flex");
    $(".empty-massage").css("display", "none");
    removeSelectedMsg();
    closeMedia();
    $(this).addClass("active");
    formattedDate = {};
    const isGroup = $(this).attr("data-group");
    const conversationId = $(this).attr("data-msgKey");
    console.log({ conversationId });
    $(".selected_id").val(conversationId);

    $("#isGroup").val(isGroup);
    $(".member-lists").html("");
    $(".send-message").val("");
    $("#startRecording").attr("style", "display:inline-block;");
    console.log({ conversationId });

    if (isGroup == true || isGroup == "true") {
        await updateChatfromGroup(conversationId);
        console.log({ conversationId });

        $(".new-member").removeClass("d-none");
    } else {
        $(".new-member").addClass("d-none");
        $(".new-members-add").addClass("d-none");

        const userId = $(this).attr("data-userid");
        $(".selected_message").val(userId);
        console.log({ conversationId });

        await updateOverview(senderUser, conversationId, {
            unRead: false,
            unReadCount: 0,
        });
        console.log("updateoverview");
        await updateChat(userId);
        console.log({ conversationId });
    }
    isToMove = false;
});
async function updateMore(conversationId) {
    const overviewSnapshot = await get(
        ref(database, `overview/${senderUser}/${conversationId}`)
    );
    const isGroup = $("#isGroup").val();
    if (isGroup == "true" || isGroup == true || isGroup == "1") {
        $(".block-conversation").each(function () {
            // Check if the element has more than one class
            if (!$(this).hasClass("single")) {
                $(this).hide();
            }
        });
    } else {
        $(".block-conversation").show();
    }
    const overviewData = overviewSnapshot.val();
    if (overviewData) {
        $(".pin-conversation").attr("changeWith", "1");
        $(".mute-conversation").attr("changeWith", "1");
        $(".archive-conversation").attr("changeWith", "1");
        $(".block-conversation").attr("user", overviewData.contactId);
        $(".conversation-" + conversationId)
            .find(".chat-data")
            .find(".pin-svg")
            .addClass("d-none");
        if (overviewData.isPin != undefined) {
            $(".pin-conversation")
                .find("span")
                .text(overviewData.isPin == "1" ? "Unpin" : "Pin");
            $(".pin-conversation").attr(
                "changeWith",
                overviewData.isPin == "1" ? "0" : "1"
            );
            if (overviewData.isPin == "1") {
                $(".conversation-" + conversationId)
                    .find(".chat-data")
                    .find(".pin-svg")
                    .removeClass("d-none");
                $(".unpin-self-icn").show();
                $(".pin-self-icn").hide();
            } else {
                $(".pin-self-icn").show();
                $(".unpin-self-icn").hide();
            }
        } else {
            $(".pin-self-icn").show();
            $(".unpin-self-icn").hide();
        }
        if (overviewData.isMute != undefined) {
            $(".mute-conversation")
                .find("span")
                .text(overviewData.isMute == "1" ? "Unmute" : "Mute");
            $(".mute-conversation").attr(
                "changeWith",
                overviewData.isMute == "1" ? "0" : "1"
            );

            if (overviewData.isMute == "1") {
                $(".mute-self-icn").addClass("d-none");
                $(".unmute-self-icn").removeClass("d-none");
            } else {
                $(".mute-self-icn").removeClass("d-none");
                $(".unmute-self-icn").addClass("d-none");
            }
        }
        if (overviewData.isArchive != undefined) {
            $(".archive-conversation")
                .find("span")
                .text(overviewData.isArchive == "1" ? "Unarchive" : "Archive");
            $(".archive-conversation").attr(
                "changeWith",
                overviewData.isArchive == "1" ? "0" : "1"
            );
        }
    }
}

$(document).on("click", ".pin-conversation", function () {
    const pinChange = $(this).attr("changeWith");
    let conversationId = $(".conversationId").attr("conversationId");
    const overviewRef = ref(
        database,
        `overview/${senderUser}/${conversationId}/isPin`
    );
    set(overviewRef, pinChange);
    $(this)
        .find("span")
        .text(pinChange == "1" ? "Unpin" : "Pin");

    $(".conversation-" + conversationId)
        .children()
        .find(".pin-single-conversation")
        .find("span")
        .text(pinChange == "1" ? "Unpin" : "Pin");

    $(".conversation-" + conversationId)
        .children()
        .find(".pin-single-conversation")
        .attr("changeWith", pinChange == "1" ? "0" : "1");

    $(this).attr("changeWith", pinChange == "1" ? "0" : "1");

    if (pinChange == "1") {
        console.log("here");

        $(".conversation-" + conversationId).addClass("pinned");
        $(".conversation-" + conversationId)
            .find(".chat-data")
            .find(".pin-svg")
            .removeClass("d-none");

        $(".unpin-self-icn").show();
        $(".pin-self-icn").hide();

        $(".conversation-" + conversationId)
            .children()
            .find(".pin1-self-icn")
            .addClass("d-none");
        $(".conversation-" + conversationId)
            .children()
            .find(".unpin1-self-icn")
            .removeClass("d-none");
        moveToTopOrBelowPinned($(`.conversation-${conversationId}`));
    } else {
        $(".conversation-" + conversationId).removeClass("pinned");

        $(".conversation-" + conversationId)
            .find(".chat-data")
            .find(".pin-svg")
            .addClass("d-none");
        $(".pin-self-icn").show();
        $(".unpin-self-icn").hide();
        $(".conversation-" + conversationId)
            .children()
            .find(".pin1-self-icn")
            .removeClass("d-none");
        $(".conversation-" + conversationId)
            .children()
            .find(".unpin1-self-icn")
            .addClass("d-none");
        moveToTopOrBelowPinned($(`.conversation-${conversationId}`));
    }
});
$(document).on("click", ".pin-single-conversation", function (e) {
    e.stopPropagation();
    const pinChange = $(this).attr("changeWith");
    let conversationId = $(this).data("conversation");

    const selectedConversationId = $(".selected_conversasion").val();

    if (selectedConversationId === conversationId) {
        $(".pin-conversation")
            .find("span")
            .text(pinChange == "1" ? "Unpin" : "Pin");

        $(".pin-conversation").attr("changeWith", pinChange == "1" ? "0" : "1");
    }

    const overviewRef = ref(
        database,
        `overview/${senderUser}/${conversationId}/isPin`
    );
    set(overviewRef, pinChange);
    $(this)
        .find("span")
        .text(pinChange == "1" ? "Unpin" : "Pin");
    $(this).attr("changeWith", pinChange == "1" ? "0" : "1");

    if (pinChange == "1") {
        console.log("here");

        $(".conversation-" + conversationId).addClass("pinned");
        $(".conversation-" + conversationId)
            .find(".chat-data")
            .find(".pin-svg")
            .removeClass("d-none");
        if (selectedConversationId === conversationId) {
            $(".unpin-self-icn").show();
            $(".pin-self-icn").hide();
        }
        $(this).children(".pin1-self-icn").addClass("d-none");
        $(this).children(".unpin1-self-icn").removeClass("d-none");
        moveToTopOrBelowPinned($(`.conversation-${conversationId}`));
    } else {
        $(".conversation-" + conversationId).removeClass("pinned");

        $(".conversation-" + conversationId)
            .find(".chat-data")
            .find(".pin-svg")
            .addClass("d-none");
        $(this).children(".pin1-self-icn").removeClass("d-none");
        $(this).children(".unpin1-self-icn").addClass("d-none");
        if (selectedConversationId === conversationId) {
            $(".pin-self-icn").show();
            $(".unpin-self-icn").hide();
        }
        moveToTopOrBelowPinned($(`.conversation-${conversationId}`));
    }
});

$(document).on("click", ".mute-conversation", function () {
    const selectedConversationId = $(".selected_conversasion").val();
    const change = $(this).attr("changeWith");
    let conversationId = $(".conversationId").attr("conversationId");
    const overviewRef = ref(
        database,
        `overview/${senderUser}/${conversationId}/isMute`
    );
    set(overviewRef, change);
    $(this)
        .find("span")
        .text(change == "1" ? "Unmute" : "Mute");
    $(this).attr("changeWith", change == "1" ? "0" : "1");
    if (change == "1") {
        console.log("mute here");
        $(".conversation-" + conversationId).addClass("muted");

        if (selectedConversationId === conversationId) {
            $(".mute-self-icn").addClass("d-none");
            $(".unmute-self-icn").removeClass("d-none");
        }

        $(".conversation-" + conversationId)
            .find(".chat-data")
            .find(".mute-single-conversation")
            .find(".mute1-self-icn")
            .addClass("d-none");

        $(".conversation-" + conversationId)
            .find(".chat-data")
            .find(".mute-single-conversation")
            .find(".unmute1-self-icn")
            .removeClass("d-none");
    } else {
        $(".conversation-" + conversationId).removeClass("muted");

        if (selectedConversationId === conversationId) {
            $(".mute-self-icn").removeClass("d-none");
            $(".unmute-self-icn").addClass("d-none");
        }

        $(".conversation-" + conversationId)
            .find(".chat-data")
            .find(".mute-single-conversation")
            .find(".mute1-self-icn")
            .removeClass("d-none");

        $(".conversation-" + conversationId)
            .find(".chat-data")
            .find(".mute-single-conversation")
            .find(".unmute1-self-icn")
            .addClass("d-none");
    }
    $(".conversation-" + conversationId)
        .find(".chat-data")
        .find(".mute-single-conversation")
        .find("span")
        .text(change == "1" ? "Unmute" : "Mute");

    $(".conversation-" + conversationId)
        .find(".chat-data")
        .find(".mute-single-conversation")
        .attr("changeWith", change == "1" ? "0" : "1");
});

$(document).on("click", ".mute-single-conversation", function (e) {
    e.stopPropagation();

    const change = $(this).attr("changeWith");
    let conversationId = $(this).data("conversation");
    const overviewRef = ref(
        database,
        `overview/${senderUser}/${conversationId}/isMute`
    );
    set(overviewRef, change);

    const selectedConversationId = $(".selected_conversasion").val();
    if (selectedConversationId === conversationId) {
        $(".mute-conversation")
            .find("span")
            .text(change == "1" ? "Unmute" : "Mute");
        $(".mute-conversation").attr("changeWith", change == "1" ? "0" : "1");
    }
    $(this)
        .find("span")
        .text(change == "1" ? "Unmute" : "Mute");
    $(this).attr("changeWith", change == "1" ? "0" : "1");

    if (change == "1") {
        console.log("mute here");
        $(".conversation-" + conversationId).addClass("muted");

        $(this).children(".mute1-self-icn").addClass("d-none");
        $(this).children(".unmute1-self-icn").removeClass("d-none");

        if (selectedConversationId === conversationId) {
            $(".mute-self-icn").addClass("d-none");
            $(".unmute-self-icn").removeClass("d-none");
        }
    } else {
        $(".conversation-" + conversationId).removeClass("muted");

        $(this).children(".mute1-self-icn").removeClass("d-none");
        $(this).children(".unmute1-self-icn").addClass("d-none");

        if (selectedConversationId === conversationId) {
            $(".mute-self-icn").removeClass("d-none");
            $(".unmute-self-icn").addClass("d-none");
        }
    }
    isToMove = false;
});

$(document).on("click", ".block-conversation", async function () {
    const userId = $(this).attr("user");
    const blocked = $(this).attr("blocked") === "true"; // Convert string to boolean

    // References to block lists
    let userRef = ref(database, `users/${senderUser}/blockByMe`);
    let blockuserRef = ref(database, `users/${userId}/blockByUser`);

    // Get the current block lists
    let usersSnapshot = await get(userRef);
    let BlockusersSnapshot = await get(blockuserRef);

    let users = usersSnapshot.exists() ? usersSnapshot.val() : [];
    let blockUsers = BlockusersSnapshot.exists()
        ? BlockusersSnapshot.val()
        : [];

    if (blocked) {
        // Unblock the user
        users = users.filter((id) => id !== userId);
        blockUsers = blockUsers.filter((id) => id !== senderUser);
    } else {
        // Block the user
        if (!users.includes(userId)) {
            users.push(userId);
        }
        if (!blockUsers.includes(senderUser)) {
            blockUsers.push(senderUser);
        }
    }

    // Update the block lists in Firebase
    await set(userRef, users);
    await set(blockuserRef, blockUsers);

    // Update the 'blocked' attribute for future clicks
    $(this).attr("blocked", !blocked);
    let conversationid = $(".conversationId").attr("conversationid");
    $(".conversation-" + conversationid).click();
});

$(document).on("click", ".archive-conversation", function () {
    const change = $(this).attr("changeWith");
    let conversationId = $(".conversationId").attr("conversationId");
    const overviewRef = ref(
        database,
        `overview/${senderUser}/${conversationId}/isArchive`
    );
    set(overviewRef, change);
    $(this)
        .find("span")
        .text(change == "1" ? "Unarchive" : "Archive");
    $(this).attr("changeWith", change == "1" ? "0" : "1");

    $(".conversation-" + conversationId)
        .find(".chat-data")
        .find(".archive-single1-conversation")
        .find("span")
        .text(change == "1" ? "Unarchive" : "Archive");

    $(".conversation-" + conversationId)
        .find(".chat-data")
        .find(".archive-single1-conversation")
        .attr("changeWith", change == "1" ? "0" : "1");

    if (change == "1") {
        $(".conversation-" + conversationId).addClass("archived-list");
        $(".conversation-" + conversationId).removeClass("unarchived-list");
    } else {
        $(".conversation-" + conversationId).addClass("unarchived-list");
        $(".conversation-" + conversationId).removeClass("archived-list");
    }
    $(".archived-list").hide();

    var unarchivelist = document.getElementsByClassName("unarchived-list");
    console.log(unarchivelist);

    let msgLists = $(unarchivelist);
    msgLists[0].click();
});

$(document).on("click", ".archive-single1-conversation", function (e) {
    e.stopPropagation();
    const change = $(this).attr("changeWith");
    let conversationId = $(this).data("conversation");
    const overviewRef = ref(
        database,
        `overview/${senderUser}/${conversationId}/isArchive`
    );
    set(overviewRef, change);
    $(this)
        .find("span")
        .text(change == "1" ? "Unarchive" : "Archive");
    $(this).attr("changeWith", change == "1" ? "0" : "1");

    const selectedConversationId = $(".selected_conversasion").val();
    if (selectedConversationId === conversationId) {
        $(".archive-conversation")
            .find("span")
            .text(change == "1" ? "Unarchive" : "Archive");

        $(".archive-conversation").attr(
            "changeWith",
            change == "1" ? "0" : "1"
        );
    }
    if (change == "1") {
        $(".conversation-" + conversationId).addClass("archived-list");
        $(".conversation-" + conversationId).removeClass("unarchived-list");
    } else {
        $(".conversation-" + conversationId).addClass("unarchived-list");
        $(".conversation-" + conversationId).removeClass("archived-list");
    }
    $(".archived-list").hide();

    var unarchivelist = document.getElementsByClassName("unarchived-list");
    console.log(unarchivelist);
    let msgLists = $(unarchivelist);
    msgLists[0].click();
});

// Initial chat update
if ($("#isGroup").val() == true) {
    updateChatfromGroup($(".selected_id").val());
} else {
    updateChat($(".selected_message").val());
}

$(".archived-list").hide();
$("#archive-list").click(function () {
    var msgLists = [];
    if ($(this).attr("list") == "0") {
        $(".multi-archive").attr("changewith", "0");
        $(this).attr("list", "1");
        $(".archived-list").show();
        $(".unarchived-list").hide();
        msgLists = $(".archived-list");
        $(this).html("Unarchive List");
    } else {
        $(this).attr("list", "0");
        $(".unarchived-list").show();
        $(".archived-list").hide();
        msgLists = $(".unarchived-list");
        $(this).html("Archive List");
        $(".multi-archive").attr("changewith", "1");
    }
    if (msgLists.length > 0) {
        console.log("from click");
        msgLists[0].click();
    }
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

var typeTimeout = 0;
var profileIndexCache = {};

async function setProfileIndexCache(conversationId) {
    if (profileIndexCache[conversationId] === undefined) {
        var groupRef = ref(database, `Groups/${conversationId}`);
        var groupSnapshot = await get(groupRef);

        if (groupSnapshot.exists()) {
            var groupInfo = groupSnapshot.val().groupInfo;
            var profiles = groupInfo.profiles;

            // Find the profile with the matching user ID
            for (let i = 0; i < profiles.length; i++) {
                if (profiles[i].id === senderUser) {
                    console.log({ i });
                    profileIndexCache[conversationId] = i;
                    break;
                }
            }
        }
    }
    console.log(profileIndexCache[conversationId]);
    return profileIndexCache[conversationId];
}

$(".send-message").on("keyup", async function (e) {
    clearTimeout(typeTimeout);
    typeTimeout = setTimeout(async () => {
        const conversationId = $(".selected_id").val();
        var isGroup = $(".conversation-" + conversationId).attr("data-group");

        if (isGroup == "true" || isGroup == true) {
            var profileIndex = await setProfileIndexCache(conversationId);
            // If profileIndex is cached, update the userTypingStatus
            if (profileIndex != undefined) {
                var groupRef = ref(
                    database,
                    `Groups/${conversationId}/groupInfo/profiles/${profileIndex}`
                );

                await update(groupRef, {
                    userTypingStatus: false,
                });
            }
        } else {
            update(userRef, { userTypingStatus: "Not typing..." });
        }
    }, 500);
});
$("#preview").hide();
let mediaRecorder;
let recordedChunks = [];
let stream;
const startButton = document.getElementById("startRecording");
const stopButton = document.getElementById("stopRecording");
const playButton = document.getElementById("playRecording");
const stopPlaybackButton = document.getElementById("stopPlayback");
const audioElement = document.getElementById("recordedAudio");
const close = document.getElementsByClassName("close-audio-btn");
$(".send-message").on("keypress", async function (e) {
    const conversationId = $(".selected_id").val();
    var isGroup = $(".conversation-" + conversationId).attr("data-group");

    if (e.which === 13 && e.shiftKey) {
        return;
    } else if (e.which === 13 && !e.shiftKey) {
        e.preventDefault();
    }

    if (isGroup == "true" || isGroup == true) {
        // Fetch the group profiles
        var profileIndex = await setProfileIndexCache(conversationId);

        if (profileIndex != undefined) {
            var groupRef = ref(
                database,
                `Groups/${conversationId}/groupInfo/profiles/${profileIndex}`
            );

            await update(groupRef, {
                userTypingStatus: true,
            });
        }
    } else {
        await update(userRef, { userTypingStatus: "Typing..." });
    }
    if (e.which === 13) {
        loader.css("display", "flex");
        startButton.style.display = "inline-block";
        $("#isGroup").val(isGroup);
        const message = $(this).val();
        let downloadURL = "";
        let type = "";
        let fileName = "";
        $("#preview").hide();
        $(".preview_img").hide();
        let preview = document.getElementsByClassName("preview_img");
        var previewImg = $(preview);
        const imageUrl = previewImg.attr("src");
        const previewAudio = $(".recordedAudio");
        const audioUrl = previewAudio.attr("src");
        let imagePath = "";
        const audio = $("#file_name").text();
        const file_info = $(".file_info").val();

        if (imageUrl) {
            // Determine file type and set the storage path
            let storagePath;
            if (imageUrl.startsWith("data:image/")) {
                storagePath = `Images/${senderUser}/${Date.now()}_${senderUser}-img.${file_info}`;
                fileName = `${Date.now()}_${senderUser}-img.${file_info}`;
                type = "1";
                imagePath = storagePath;
            } else if (
                imageUrl.startsWith("data:video/mp4") &&
                audio != "audio"
            ) {
                storagePath = `Video/${senderUser}/${Date.now()}_${senderUser}-video.mp4`;
                fileName = `${Date.now()}_${senderUser}-video.mp4`;
                type = "2";
            } else if (imageUrl.startsWith("blob:http:/") && audio == "audio") {
                storagePath = `Audios/${senderUser}/${Date.now()}_${senderUser}-audio.wav`;
                fileName = `${Date.now()}_${senderUser}-audio.wav`;
                type = "3";
            } else {
                storagePath = `Files/${senderUser}/${Date.now()}_${senderUser}-file.${file_info}`;
                fileName = `${Date.now()}_${senderUser}-file.${file_info}`;
                type = "4";
            }
            console.log(type);
            console.log(fileName);
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
                downloadURL = await getDownloadURL(fileRef);
                console.log({ downloadURL });
            } catch (e) {
                console.log(e);
            }
        } else if (audioUrl) {
            $("#playRecording").hide();
            $("#stopRecording").hide();
            $("#stopPlayback").hide();

            let storagePath;
            storagePath = `Audios/${senderUser}/${Date.now()}_${senderUser}-Audio.wav`;
            fileName = `${Date.now()}_${senderUser}-Audio.wav`;
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

        if (message.trim() == "" && downloadURL == "" && audioUrl == "") {
            loader.hide();
            return;
        }
        $(this).val(""); // Clear the input field
        $(this).css("height", "auto");
        const messageData = {
            data: message,
            url: downloadURL,
            fileName,
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
                    // replyTimeStamp: Date.now(),
                    replyTimeStamp: replyMessageData.timeStamp,
                    replyUserName: replyMessageData.receiverName,
                    replyDocType: "",
                };
                messageData.isReply = "1";
                // Reset reply message ID after sending
                replyMessageId = null;
                $(".set-replay-msg").remove();
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
                    let image = messageData.url;

                    if (
                        (receiverSnapshot.val().isMute == undefined ||
                            receiverSnapshot.val().isMute == 0) &&
                        receiverSnapshot.val().group == true
                    ) {
                        await send_push_notification(
                            profile.id,
                            message,
                            conversationId,
                            image,
                            senderUserName
                        );
                    }
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
                    // replyTimeStamp: Date.now(),
                    // replyUserName: senderUserName,
                    replyTimeStamp: replyMessageData.timeStamp,
                    // replyUserName: receiverName,
                    replyUserName: senderUserName,
                    replyDocType: "",
                };
                messageData.isReply = "1";
                // Reset reply message ID after sending
                replyMessageId = null;
                $(".set-replay-msg").remove();
            }

            messageData.status = { senderUser: { profile: "", read: "1" } };

            let image = messageData.url;

            await addMessage(conversationId, messageData, receiverId);

            await updateOverview(senderUser, conversationId, {
                lastMessage: `${senderUserName}: ${message}`,
                timeStamp: Date.now(),
            });
            let receiverSnapshot = await get(
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
                let userData = await get(userRef);
                let userSnap = userData.val();

                const receiverConversationData = {
                    contactId: senderUser,
                    contactName: senderUserName,
                    conversationId: conversationId,
                    group: false,
                    lastMessage: `${senderUserName}: ${message}`,
                    lastSenderId: senderUser,
                    receiverProfile: userSnap?.userProfile,
                    timeStamp: Date.now(),
                    unRead: true,
                    unReadCount: 1,
                };

                await set(
                    ref(database, `overview/${receiverId}/${conversationId}`),
                    receiverConversationData
                );
            }

            receiverSnapshot = await get(
                ref(database, `overview/${receiverId}/${conversationId}`)
            );
            if (
                receiverSnapshot.val().isMute == undefined ||
                receiverSnapshot.val().isMute == 0 ||
                receiverSnapshot.val().isMute == null
            ) {
                await send_push_notification(
                    receiverId,
                    message,
                    conversationId,
                    image,
                    senderUserName
                );
            }
        }
        const conversationElement = $(`.conversation-${conversationId}`);

        moveToTopOrBelowPinned(conversationElement);
        console.log("here");
        $("#file1").val("");
        $("#file2").val("");
        $("#file3").val("");
        closeMedia();
        loader.hide();
    }
});
function closeMedia() {
    let preview = document.getElementsByClassName("preview_img");
    var previewImg = $(preview);
    const previewAudio = $(".recordedAudio");
    previewImg.attr("src", "");
    previewAudio.attr("src", "");
    $("#recordedAudio").attr("src", "");
    $("#musicContainer").hide();
    $("#preview").hide();
    $(".preview_img").hide();
    $("#file1").val("");
    $("#file2").val("");
    $("#file3").val("");
}
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
        console.warn("");
        return;
    }

    const messageEle = document.getElementById(`message-${key}`);
    let isGroup = $("#isGroup").val();
    let msgloop = $(messageEle).data("loop");
    let dataRloop = $(messageEle).data("data-Rloop");
    const messgeElement = createMessageElement(
        key,
        messageData,
        isGroup,
        msgloop,
        dataRloop
    );

    $(messageEle).replaceWith(messgeElement);
    updateTimers(true);
}
function addMessageToList(key, messageData, conversationId) {
    if ($(".selected_conversasion").val() != conversationId) {
        console.warn($(".selected_conversasion").val());
        // console.log(conversationId);
        $(".conversation-" + conversationId).addClass("active");
        // console.log("selectedisnotvalid");
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
    updateTimers();
    setTimeout(function () {
        scrollToBottom();
    }, 1000);
}
let timerTime = 0;
function updateTimers(fast = false) {
    clearTimeout(timerTime);
    if (fast) {
        processTimers();
    } else {
        timerTime = setTimeout(processTimers, 500);
    }
}

function processTimers() {
    let messages = document.querySelectorAll(".chat-box");
    let lastTime = "";
    let lastSender = "";
    let lastElement = null;

    messages.forEach((msg) => {
        let timeElement = msg.querySelector(".time");
        let seenStatus = timeElement.nextElementSibling;
        let time = timeElement.getAttribute("data-time");
        let senderType = msg.classList.contains("sender")
            ? "sender"
            : "receiver";

        if (time === lastTime && senderType === lastSender) {
            if (lastElement) lastElement.style.display = "none";
            if (
                lastElement?.nextElementSibling?.classList.contains(
                    "seenStatus"
                )
            ) {
                lastElement.nextElementSibling.style.display = "none";
            }
        }

        lastTime = time;
        lastSender = senderType;
        lastElement = timeElement;
        timeElement.style.display = "inline";
        if (seenStatus && seenStatus.classList.contains("seenStatus")) {
            seenStatus.style.display = "inline-block";
        }
    });
}

// setInterval(updateTimers, 1000);

var formattedDate = {};
var messageRcvTime = "";
let chatloop = 0;
let recchatloop = 0;
function createMessageElement(
    key,
    messageData,
    isGroup,
    msgLoop = 0,
    recMsgLoop = 0
) {
    messageRcvTime = new Date(messageData.timeStamp).toLocaleTimeString([], {
        hour: "2-digit",
        minute: "2-digit",
        hour12: true,
    });

    const isSender = senderUser == messageData.senderId;
    const isReceiver = senderUser != messageData.senderId;

    chatloop = isSender ? chatloop + 1 : chatloop;
    recchatloop = isReceiver ? recchatloop + 1 : recchatloop;
    if (
        (isGroup == "true" || isGroup == true) &&
        SelecteGroupUser[messageData.senderId] == undefined
    ) {
        // console.log(SelecteGroupUser[messageData.senderId]);
        return;
    }
    const senderName =
        (isGroup == "true" || isGroup == true) && !isSender
            ? SelecteGroupUser[messageData.senderId].name
            : "";
    const sender_userProfile =
        (isGroup == "true" || isGroup == true) && !isSender
            ? // ? SelecteGroupUser[messageData.senderId].userProfile
              SelecteGroupUser[messageData.senderId].image
            : "";
    // console.log(getSelectedUserimg(sender_userProfile,senderName));

    let seenStatus = "";
    let reaction = "";
    let dataWithMedia = "";
    let senderprofile = "";

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
                  .map((reactData) => {
                      const reactionCode = reactData.react.replace(
                          /\\u\{(.+)\}/,
                          "$1"
                      ); // Extract the reaction code
                      const imageUrl = reactionImageMap[reactionCode]; // Get the image URL from the map

                      return `
                  <li class="reaction ${
                      reactData?.react
                          ?.replace(/\\u\{(.+)\}/, "$1")
                          .includes("2764}")
                          ? "heart_reaction"
                          : ""
                  }" data-message-id="${key}">
                      <img src="${imageUrl}" alt="reaction" />
                  </li>
              `;
                  })
                  .join(" ")
            : "";
        reaction = `<ul class="reaction-ul ${messageData?.react}">${reaction}</ul>`;
        senderprofile = getSelectedUserimggrp(sender_userProfile, senderName);
        // console.log(senderprofile);
    } else {
        seenStatus = isSender
            ? messageData.isSeen
                ? "blue-tick"
                : "grey-tick"
            : "";

        const reactionCode = messageData?.react?.replace(/\\u\{(.+)\}/, "$1"); // Extract the reaction code
        console.log(reactionCode);
        const imageUrl = reactionImageMap[reactionCode];
        reaction =
            messageData?.react && messageData?.react.length > 0
                ? `<span class="reaction ${
                      messageData?.react
                          ?.replace(/\\u\{(.+)\}/, "$1")
                          .includes("2764}")
                          ? "heart_reaction"
                          : ""
                  }" data-message-id ="${key}"> <img src="${imageUrl}" alt="reaction" /> </span>`
                : "";
    }

    let emojiAndReplay = isReceiver
        ? `
      <span class="reaction-icon" data-message-id="${key}"><svg width="21" height="20" viewBox="0 0 21 20" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M7.5 8H7.51M13.5 8H13.51M8 13C8.32588 13.3326 8.71485 13.5968 9.14413 13.7772C9.57341 13.9576 10.0344 14.0505 10.5 14.0505C10.9656 14.0505 11.4266 13.9576 11.8559 13.7772C12.2852 13.5968 12.6741 13.3326 13 13M19.5 10C19.5 14.9706 15.4706 19 10.5 19C5.52944 19 1.5 14.9706 1.5 10C1.5 5.02944 5.52944 1 10.5 1C15.4706 1 19.5 5.02944 19.5 10Z" stroke="#CBD5E1" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
</svg></span>
      <span class="reply-icon" data-isReceiver="${isReceiver}" data-message-id="${key}"><svg width="15" height="12" viewBox="0 0 15 12" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M5.89687 3.31028V0.238281L0.296875 5.61428L5.89687 10.9903V7.84148C9.89687 7.84148 12.6969 9.07028 14.6969 11.7583C13.8969 7.91828 11.4969 4.07828 5.89687 3.31028Z" fill="#CBD5E1"/>
</svg></span>`
        : `<span class="reply-icon" data-isReceiver="${isReceiver}" data-message-id="${key}"><svg width="15" height="12" viewBox="0 0 15 12" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M5.89687 3.31028V0.238281L0.296875 5.61428L5.89687 10.9903V7.84148C9.89687 7.84148 12.6969 9.07028 14.6969 11.7583C13.8969 7.91828 11.4969 4.07828 5.89687 3.31028Z" fill="#CBD5E1"/>
</svg></span>`;
    var fileExtension = messageData?.fileName?.substr(
        messageData?.fileName?.lastIndexOf(".") + 1
    );

    let emoji = isReceiver
        ? `
     <span class="reaction-icon" data-message-id="${key}"><svg width="21" height="20" viewBox="0 0 21 20" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M7.5 8H7.51M13.5 8H13.51M8 13C8.32588 13.3326 8.71485 13.5968 9.14413 13.7772C9.57341 13.9576 10.0344 14.0505 10.5 14.0505C10.9656 14.0505 11.4266 13.9576 11.8559 13.7772C12.2852 13.5968 12.6741 13.3326 13 13M19.5 10C19.5 14.9706 15.4706 19 10.5 19C5.52944 19 1.5 14.9706 1.5 10C1.5 5.02944 5.52944 1 10.5 1C15.4706 1 19.5 5.02944 19.5 10Z" stroke="#CBD5E1" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
</svg></span>
     `
        : "";
    dataWithMedia =
        messageData?.type == "1"
            ? `
            <div class="media-msg-inline">
            ${
                senderprofile
                    ? isReceiver
                        ? `<div class="simplemsg-img me-2 user-img new-msg-class"><span>${senderprofile}<span></div>`
                        : ""
                    : ""
            }
                <div class="media-msg">
                    <img src="${messageData?.url}"/>
                    <span class="media-text"> ${
                        messageData?.data != ""
                            ? messageData.data.replace(/\n/g, "<br>")
                            : ""
                    }</span>
                    
                    ${reaction}
                </div>
                    ${emoji}
            </div>
            `
            : messageData?.type == "2"
            ? `
            <div class="media-msg-inline">
            ${
                senderprofile
                    ? isReceiver
                        ? `<div class="simplemsg-img me-2 user-img new-msg-class"><span>${senderprofile}<span></div>`
                        : ""
                    : ""
            }
                <div class="media-msg">
                    <video src="${messageData?.url}" controls></video>
                    <span class="media-text"> ${
                        messageData?.data != ""
                            ? messageData.data.replace(/\n/g, "<br>")
                            : ""
                    }</span>
                   
                    ${reaction}
                </div>
                    ${emoji}
            </div>`
            : messageData?.type == "4"
            ? `
            <div class="media-msg-inline">
            ${
                senderprofile
                    ? isReceiver
                        ? `<div class="simplemsg-img me-2 user-img new-msg-class"><span>${senderprofile}<span></div>`
                        : ""
                    : ""
            }
                <div class="media-msg">
                ${
                    fileExtension === "pdf" || fileExtension == "PDF"
                        ? ` <iframe src="${messageData?.url}" style="width:100%;height:400px;"></iframe>`
                        : ` <a href="${messageData?.url}" class="file_link">${messageData?.fileName}</a>`
                }
                   
                    <span class="media-text"> ${
                        messageData?.data != ""
                            ? messageData.data.replace(/\n/g, "<br>")
                            : ""
                    }</span>
                   
                    ${reaction}
                </div>
                    ${emoji}
            </div>`
            : messageData?.type == "3"
            ? `<div class="media-msg-inline">
            ${
                senderprofile
                    ? isReceiver
                        ? `<div class="simplemsg-img me-2 user-img new-msg-class"><span>${senderprofile}<span></div>`
                        : ""
                    : ""
            }
                <div class="media-msg">
                ${musicPlayer(messageData?.url)}
                <span> ${
                    messageData?.data != ""
                        ? messageData.data.replace(/\n/g, "<br>")
                        : ""
                }</span>
                
                ${reaction}
                </div>
                
                ${emojiAndReplay}
            </div>`
            : `
            <div class="simple-message">
            ${
                senderprofile
                    ? isReceiver
                        ? `<div class="simplemsg-img me-2 user-img new-msg-class"><span>${senderprofile}<span></div>`
                        : ""
                    : ""
            }
             
                <div class="simple-msg-wrap"> 
                    <span class="senderName">${senderName}</span>                   
                     ${
                         messageData?.data != ""
                             ? messageData.data.replace(/\n/g, "<br>")
                             : ""
                     }
                    
                    ${reaction}
                </div>
                ${emojiAndReplay}
              </div>
              `;
    // console.log(messageData);
    const replySection =
        messageData.replyData && messageData.replyData.replyTimeStamp != 0
            ? `
            <div class="reply-section-wrp">
                ${
                    senderprofile != ""
                        ? isReceiver
                            ? `<div class="simplemsg-img me-2 user-img new-msg-class replay-img"><span>${senderprofile}<span></div>`
                            : ""
                        : ""
                }
            <div class="reply-section">
                <span class="senderName">${senderName}</span>            
                <div>
                    <span> ${messageData.replyData.replyMessage}</span>
                    <div class="reply-info">
                        <strong><span class="reply-username">${
                            messageData.replyData.replyUserName
                        }</span>
                        <span class="reply-timestamp">${new Date(
                            messageData.replyData.replyTimeStamp
                        ).toLocaleTimeString([], {
                            hour: "2-digit",
                            minute: "2-digit",
                            hour12: true,
                        })}</span></strong>
                    </div>
                </div>
                <hr>
                <div class="reply-massage"> 
               

                    ${
                        messageData?.type == "1"
                            ? `
                            <div class="media-msg-inline">
                                <div class="media-msg">
                                    <img src="${messageData?.url}"/>
                                    <span>  ${
                                        messageData?.data != ""
                                            ? messageData.data.replace(
                                                  /\n/g,
                                                  "<br>"
                                              )
                                            : ""
                                    }</span>
                                   
                                    ${reaction}
                                </div>
                            </div>`
                            : messageData?.type == "2"
                            ? `
                            <div class="media-msg-inline">
                                <div class="media-msg">
                                    <video src="${
                                        messageData?.url
                                    }" controls></video>
                                    <span> ${
                                        messageData?.data != ""
                                            ? messageData.data.replace(
                                                  /\n/g,
                                                  "<br>"
                                              )
                                            : ""
                                    }
                                    }</span>
                                   
                                    ${reaction}
                                </div>
                            </div>`
                            : messageData?.type == "3"
                            ? `
                            <div class="media-msg-inline">
                                <div class="media-msg">
                                    ${musicPlayer(messageData?.url)}
                                    <span> ${
                                        messageData?.data != ""
                                            ? messageData.data.replace(
                                                  /\n/g,
                                                  "<br>"
                                              )
                                            : ""
                                    }
                                    }</span>
                                    
                                    ${reaction}
                                </div>
                            </div>`
                            : messageData?.type == "4"
                            ? `
                            <div class="media-msg-inline">
                                <div class="media-msg">
                                    <iframe src="${
                                        messageData?.url
                                    }" style="width:100%;height:400px;"></iframe>
                                    <span> ${
                                        messageData?.data != ""
                                            ? messageData.data.replace(
                                                  /\n/g,
                                                  "<br>"
                                              )
                                            : ""
                                    }
                                    }</span>
                                    
                                    ${reaction}
                                </div>
                            </div>`
                            : `
                            <span> ${
                                messageData?.data != "" ? messageData.data : ""
                            }</span>
                            
                                    ${reaction}
                             `
                    }


              
                   
                       
                
                </div>
            </div>
            ${emojiAndReplay}
            </div>`
            : "";
    let daychange = "";
    let msgDate = formatDate(new Date(messageData.timeStamp));
    let chatDate = new Date(messageData.timeStamp);
    let chatSmallDay = "";
    if (msgDate != "Today") {
        chatSmallDay = chatDate.toLocaleDateString("en-US", {
            weekday: "short",
        });
    }

    if (formattedDate.length == 0) {
        daychange =
            "<h5 class='day-line'><span>" +
            chatSmallDay +
            ", " +
            msgDate +
            "</span></h5>";
    } else if (formattedDate[msgDate] === undefined) {
        // console.log(msgDate);
        if (msgDate == "Yesterday") {
            daychange =
                "<h5 class='day-line'><span>" + msgDate + "</span></h5>";
        } else if (msgDate == "Today") {
            daychange =
                "<h5 class='day-line'><span>" + msgDate + "</span></h5>";
        } else {
            daychange =
                "<h5 class='day-line'><span>" +
                chatSmallDay +
                ", " +
                msgDate +
                "</span></h5>";
        }
        // daychange = "<h5 class='day-line'><span>" + chatSmallDay +" "+ msgDate + "</span></h5>";
    }
    formattedDate[msgDate] = "1";
    const time = document.getElementsByClassName(
        `time_${messageRcvTime.replace(/\s/g, "")}`
    );
    let setTimeS = 1;
    let setTimeR = 1;
    if (isSender) {
        console.log("sender");
        if (msgLoop != 0) {
            Array.from(time).forEach((timeElement) => {
                if ($(timeElement).data("loop") > msgLoop) {
                    setTimeS = 0;
                } else {
                    //$(timeElement).text("");
                }
            });
        } else {
            // $(time).text("");
        }
    } else {
        console.log("reciver");
        const Rtime = document.getElementsByClassName(
            `rtime_${messageRcvTime.replace(/\s/g, "")}`
        );
        if (recMsgLoop != 0) {
            Array.from(Rtime).forEach((timeElement) => {
                if ($(timeElement).data("Rloop") > recMsgLoop) {
                    setTimeR = 0;
                } else {
                    // $(timeElement).text("");
                }
            });
        } else {
            //$(Rtime).text("");
        }
    }

    let Dataloop = msgLoop;
    let DataRloop = recMsgLoop;
    let msgTime;
    let timeClass;
    if (isSender) {
        timeClass = `stime_${messageRcvTime.replace(/\s/g, "")}`;
        Dataloop = msgLoop == 0 ? chatloop : msgLoop;
        msgTime = setTimeS == 1 ? messageRcvTime : "";
    } else {
        DataRloop = recMsgLoop == 0 ? recchatloop : recMsgLoop;
        timeClass = `rtime_${messageRcvTime.replace(/\s/g, "")}`;
        msgTime = setTimeR == 1 ? messageRcvTime : "";
    }
    msgTime = messageRcvTime;
    //updateTimers();
    return `<div>
    ${daychange}
        <li class="chat-box ${
            isSender ? "receiver" : "sender"
        }" id="message-${key}" data-loop="${Dataloop}"  data-Rloop="${DataRloop}" >        
            ${replySection == "" ? dataWithMedia : replySection}        
            <span data-loop="${Dataloop}"  data-Rloop="${DataRloop}" data-time="${msgTime}" class="time ${timeClass}" style="display: none;">${msgTime}</span>            
            ${
                isSender
                    ? `<span class="seenStatus ${seenStatus}"  style="display: none;"></span>`
                    : ""
            } 
            </li>
    </div>
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
if ($("#search-user").length) {
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
                        await result.map(async (item) => ({
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
                    const $img = ui.item.imageElement;
                    const $tag = $("<div>", {
                        class: "tag",
                        "data-user-id": selectedUserId,
                    });
                    $tag.append(
                        `<span class="names">${selectedUserName}</span>`
                    );
                    $tag.append(
                        $("<span>", { class: "close-user-btn" }).html(closeSpan)
                    );
                    $tag.append($img);
                    $("#selected-tags-container").prepend($tag);

                    handleSelectedUsers();
                }

                setTimeout(() => {
                    $("#search-user").val("");
                }, 100);
            },
        })
        .data("ui-autocomplete")._renderItem = function (ul, item) {
        const $li = $("<li>");
        const $divMain = $("<div>").addClass(
            "suggestion-item chat-data d-flex"
        );
        const $divImage = $("<div>").addClass("user-img position-relative");
        const $divName = $("<div>").addClass("user-detail d-block ms-3");
        const $img = item.imageElement;
        const $h3 = $("<h3>").text(item.label);
        const $span = $("<span>").text(item.email);

        $divImage.append($img);
        $divName.append($("<div>")).append($h3);
        $divName.append($span);
        $divMain.append($divImage).append($divName);
        $li.append($divMain).appendTo(ul);

        return $li;
    };
}
function updateSelectedUserIds() {
    $("#selected-user-id").val(selectedUserIds.join(","));
}

$(document).on("click", ".close-user-btn", function () {
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
        $("#group-name").val("");
        const $singleTag = $("#selected-tags-container .tag");
        const userName = $singleTag.find(".names").text().trim();
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
        let allNames = "";
        $tags.each(async function (index) {
            if (index < 2) {
                const userName = $(this).find(".names").text().trim();
                const userImgSrc = $(this).find(".img-fluid").prop("outerHTML");

                groupNames += `<div class="multi-img grp-img">
                                    ${userImgSrc}
                                </div>`;
                if (allNames.length > 0) allNames += ", ";
                allNames += userName;
            }
        });
        $(".multi-chat .img-wrp").html(groupNames);
        const moreCount = tagCount - 2;

        if (moreCount > 0) {
            let moreimg = `<div class="multi-img more-img">
                <span>+${moreCount}</span>
            </div>`;
            allNames += `, +${moreCount}`;
            $(".multi-chat .img-wrp").append(moreimg);
        } else {
            $(".more-img").remove();
        }
        // Set multiple names in the selected-user-name
        // const allNames = $tags
        //     .map(function () {
        //         return $(this).clone().find(".names").text().trim();
        //     })
        //     .get()
        //     .join(", ");
        $(".selected-user-name").text(allNames);
    } else {
        $(".chat-user").addClass("d-none");
        $(".multi-chat").addClass("d-none");
        $(".more-img").addClass("d-none");
        $(".empty-massage").show();
    }
}
function handleRemoveConversation(snapshot) {
    const newConversation = snapshot.val();

    // console.log("New conversation added:", newConversation);
    if (newConversation.conversationId == undefined) {
        console.warn("undefined");
        return;
    }

    const conversationElement = document.getElementsByClassName(
        `conversation-${newConversation.conversationId}`
    );
    $(conversationElement).remove();
    const selectedConversationId = $(".selected_conversasion").val();

    if (selectedConversationId === newConversation.conversationId) {
        handleDelete();
    }
}

$(document).on("click", ".usr-list-more", function (e) {
    e.stopPropagation();
    console.log("clicked");
    return;
});
// Initialize overview listeners
const overviewRef = ref(database, `overview/${senderUser}`);
onChildAdded(overviewRef, handleNewConversation);
onChildChanged(overviewRef, handleConversationChange);
onChildRemoved(overviewRef, handleRemoveConversation);
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
    let userData = await get(userRef);
    let userSnap = userData.val();

    const newConversationId = await generateConversationId([
        currentUserId,
        contactId,
    ]);

    const newConversationData = {
        contactId: contactId,
        contactName: contactName,
        conversationId: newConversationId,
        group: false,
        lastMessage: "",
        lastSenderId: currentUserId,
        receiverProfile: receiverProfile,
        timeStamp: Date.now(),
        // unRead: true,
        // unReadCount: 1,
    };

    await set(
        ref(database, `overview/${currentUserId}/${newConversationId}`),
        newConversationData
    );
    console.log({ senderUserName });
    console.log({ currentUserId });
    const receiverConversationData = {
        contactId: currentUserId,
        contactName: senderUserName,
        conversationId: newConversationId,
        group: false,
        lastMessage: "",
        lastSenderId: currentUserId,
        receiverProfile: userSnap?.userProfile,
        timeStamp: Date.now(),
        // unRead: true,
        // unReadCount: 1,
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
$("#send-new-msg").click(function () {
    var e = jQuery.Event("keypress");
    e.which = 13; // Set the key code for Enter
    e.keyCode = 13;
    $("#new_message").trigger(e);
});
let isSending = false;

function debounce(func, delay) {
    let timer;
    return function () {
        clearTimeout(timer);
        timer = setTimeout(func, delay);
    };
}

// Event listener for sending a new message
$("#new_message").on("keypress", async function (e) {
    if (e.which === 13) {
        if (isSending) return;
        isSending = true;
        const tagCount = $("#selected-tags-container .tag").length;
        const message = $(this).val();
        if (tagCount == 0) {
            isSending = false;
            return toastr.error(
                "Please select any user for start chat.",
                "Error!"
            );
        } else if (message.trim() == "") {
            isSending = false;

            return toastr.error(
                "Please enter message for start chat.",
                "Error!"
            );
        }
        setTimeout(() => {
            isSending = false;
        }, 2000);
        loader.css("display", "flex");
        if (tagCount > 1) {
            const currentUserId = senderUser;
            const groupName = $("#group-name").html(); // Assuming you have an input for group name
            $("#group-name").html("");
            if (groupName.trim() == "") {
                isSending = false;

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
                } else {
                    userAvailable[memberID] = Date.now();
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
            const blockByMeRef = ref(
                database,
                `users/${senderUser}/blockByUser`
            );
            const blockByUserRef = ref(
                database,
                `users/${senderUser}/blockByMe`
            );

            const blockByMeSnapshot = await get(blockByMeRef);
            const blockByUserSnapshot = await get(blockByUserRef);

            let isBlockedByMe = false;
            let isBlockedByUser = false;

            if (blockByMeSnapshot.exists()) {
                const blockByMeList = blockByMeSnapshot.val();
                isBlockedByMe = blockByMeList.includes(contactId);
            }

            if (blockByUserSnapshot.exists()) {
                const blockByUserList = blockByUserSnapshot.val();
                isBlockedByUser = blockByUserList.includes(contactId);
            }

            if (isBlockedByMe || isBlockedByUser) {
                $("#msgBox").modal("hide");
                isSending = false;

                return;
            }

            const message = $(this).val();
            const selectedMessageId = conversationId;
            $(".selected_id").val(conversationId);
            $(".selected_message").val(contactId);
            $(".selected_name").val(contactName);

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
            await updateChat(contactId);
            $("#isGroup").val("false");
        }
        $(this).val("");
    }
});

const isOnlineForDatabase = {
    userStatus: "Online",
    userLastSeen: Date.now(),
};

// Object representing the user's status when offline
const isOfflineForDatabase = {
    userStatus: "Offline",
    userLastSeen: Date.now(),
};

// Set up the connection status listener
const connectedRef = ref(database, ".info/connected");
onValue(connectedRef, async (snapshot) => {
    if (snapshot.val() === true) {
        // User is connected
        await update(userRef, isOnlineForDatabase);

        // Set up the onDisconnect function to set status to offline
        await onDisconnect(userRef).update(isOfflineForDatabase);
    } else {
        // User is disconnected (note: this could be triggered before onDisconnect)
        await update(userRef, isOfflineForDatabase);
    }
});
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
    // console.log("check admin", senderIsAdmin);
    if (senderIsAdmin) {
        $(".new-member").removeClass("d-none");
    } else {
        // console.log("else");

        $(".new-member").addClass("d-none");
    }
    $(".new-members-add").addClass("d-none");

    let messageElement = ``;
    const promises = SelecteGroupUser.map(async (user) => {
        const userImageElement = await getListUserimg(user.image, user.name);

        // const removeMember =
        //     user.id != senderUser && senderIsAdmin
        //         ? `<button class="remove-member" data-id="${user.id}">${closeSpan}</button>`
        //         : "";
        let removeMember = "";
        if (senderIsAdmin) {
            removeMember = `<button class="remove-member" data-id="${user.id}">${closeSpan}</button>`;
        } else if (user.id == senderUser) {
            removeMember = `<button class="remove-member" data-id="${user.id}">${closeSpan}</button>`;
        }

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
                                        ${
                                            user.isAdmin == "1"
                                                ? "<span class='group-admin'>Admin</span>"
                                                : ""
                                        }
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
    const userId = $(this).attr("data-id");
    var conversationId = $(".conversationId").attr("conversationid");

    var overviewRef = ref(database, `overview/${userId}/${conversationId}`);

    let senderIsAdmin = false;

    SelecteGroupUser.forEach((user) => {
        console.log(user);
        if (user.id == senderUser && user.isAdmin == "1") {
            senderIsAdmin = true;
        }
    });

    var groupInfoProfileRef = ref(
        database,
        `/Groups/${conversationId}/groupInfo/profiles`
    );
    var groupInfoProfileSnap = await get(groupInfoProfileRef);
    var groupInfoProfileData = groupInfoProfileSnap.val();

    if (groupInfoProfileData) {
        let i = 0;
        console.log({ senderIsAdmin });
        console.log({ senderUser });
        console.log({ userId });

        for (var key in groupInfoProfileData) {
            if (
                userId == senderUser &&
                i == 0 &&
                senderIsAdmin &&
                groupInfoProfileData[key].id != userId &&
                groupInfoProfileData[key].leave == false
            ) {
                i++;
                await update(
                    ref(
                        database,
                        `/Groups/${conversationId}/groupInfo/profiles/${key}`
                    ),
                    { isAdmin: "1" }
                );
            }
            if (groupInfoProfileData[key].id == userId) {
                await update(
                    ref(
                        database,
                        `/Groups/${conversationId}/groupInfo/profiles/${key}`
                    ),
                    { isAdmin: "0", leave: true }
                );
            }
        }
    }

    const groupInfoRef = ref(database, `Groups/${conversationId}/groupInfo`);
    const snapshot = await get(groupInfoRef);
    const groupInfo = snapshot.val();
    groupInfo?.profiles.map((profile) => {
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
    $("#listBox").modal("hide");
    await addListInMembers(SelecteGroupUser);
    await remove(overviewRef);
    $(".conversation-" + conversationId).click();
});

$(".updateGroup").click(function () {
    let title = $(".selected-title").html();
    $(".selected-title").hide();
    $(this).hide();
    $(".change-group-name").removeClass("d-none");
    $(".update-group-name").val(title);
});

$(".conversationId").click(function () {
    let conversationId = $(this).attr("conversationId");
    $(".change-group-name").addClass("d-none");
    $(".selected-title").show();
    $("#group-selected-user-id").val("");
    newSelectedUserIds = [];
    let isGroup = $("#isGroup").val();
    if (isGroup == "true" || isGroup == true) {
        $(".updateGroup").show();
        let senderIsAdmin = false;

        // Check if the senderUser is an admin
        SelecteGroupUser.forEach((user) => {
            if (user.id == senderUser && user.isAdmin == "1") {
                senderIsAdmin = true;
            }
        });
        // console.log("check admin", senderIsAdmin);
        if (senderIsAdmin) {
            $(".new-member").removeClass("d-none");
            $(".choosen-file").show();
            $(".updateGroup").show();
        } else {
            $(".new-member").addClass("d-none");
            $(".choosen-file").hide();
            $(".updateGroup").hide();
        }
    } else {
        $(".updateGroup").hide();
        $(".new-member").addClass("d-none");
    }
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
    $("#selected-user-name").html(newTitle);
    $(".selected_name").val(newTitle);
    $(".updateGroup").show();
    updateChatfromGroup(conversationId);
});

$("#new-member").click(function () {
    $(".new-member").addClass("d-none");
    $(".new-members-add").removeClass("d-none");
    $("#group-selected-tags-container .tag").remove();
    selectedgrpUserIds = SelecteGroupUser.map(
        (user) => user.leave == false && user.id
    ).filter((id) => id);
});
$(".close-group-modal").click(function () {
    $(".new-members-add").addClass("d-none");
    $(".new-member").removeClass("d-none");
});

var selectedgrpUserIds = [];
var newSelectedUserIds = [];
if ($("#group-search-user").length) {
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
                                closeSpan
                            )
                        );
                    $("#group-selected-tags-container").prepend($tag);
                }

                setTimeout(() => {
                    $("#group-search-user").val("");
                }, 100);
            },
        })
        .data("ui-autocomplete")._renderItem = function (ul, item) {
        const $li = $("<li>");
        const $divMain = $("<div>").addClass(
            "suggestion-item chat-data d-flex"
        );
        const $divImage = $("<div>").addClass("user-img position-relative");
        const $divName = $("<div>").addClass("user-detail d-block ms-3");
        const $img = item.imageElement;
        // console.log($img);
        const $span = $("<h3>").text(item.label);

        $divImage.append($img);
        $divName.append($("<div>")).append($span);
        $divMain.append($divImage).append($divName);
        $li.append($divMain).appendTo(ul);

        return $li;
    };
}
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
        // console.log(newSelectedUserIds);

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

        await Promise.all(
            newSelectedUserIds.map(async (userId) => {
                userId = userId.toString();
                let user = await getUser(userId);
                if (!user) {
                    try {
                        await updateUserInFirebase(userId);
                        user = await getUser(userId);
                        if (!user) {
                            throw new Error(
                                "User not found in Firebase after update"
                            );
                        }
                    } catch (error) {
                        toastr.error(
                            "Some user is not updated, they are not added in group.",
                            "Error!"
                        );
                        console.error(error);
                        return;
                    }
                }
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

                // Update the user's overview data
                const overviewRef = ref(
                    database,
                    `overview/${userId}/${conversationId}`
                );

                await set(overviewRef, {
                    contactId: conversationId,
                    contactName: $(".selected_name").val(),
                    conversationId: conversationId,
                    group: true,
                    lastMessage: "",
                    lastSenderId: "",
                    receiverProfile:
                        groupInfo?.groupProfile == undefined
                            ? ""
                            : groupInfo?.groupProfile,
                    timeStamp: Date.now(),
                    unRead: false,
                    unReadCount: 0,
                });
            })
        );

        // Update Firebase with new group info and users
        await set(groupInfoRef, groupInfo);
        await set(groupUsersRef, users);

        $(".new-members-add").addClass("d-none");
        $(".new-member").addClass("d-none");
        console.log("from click");

        $(".conversation-" + conversationId).click();
        // Clear the newSelectedUserIds array after adding
        newSelectedUserIds = [];
        updateSelectedgrpUserIds();
        $("#group-selected-tags-container .tag").remove();
    } catch (error) {
        console.error("Error adding new users to the group:", error);
    }
});

$(document).on("click", ".close-group-btn", function () {
    const $tag = $(this).parent(".tag");
    const userId = $tag.data("user-id");

    // $(this).parent(".tag").remove();
    selectedgrpUserIds = selectedgrpUserIds.filter((id) => id !== userId);
    newSelectedUserIds = newSelectedUserIds.filter((id) => id !== userId);
    updateSelectedgrpUserIds();
    $tag.remove();
});
$("#new-message").click(function () {
    selectedUserIds = [];
    $("#new_message").val("");
    $("#selected-tags-container .tag").remove();
    updateSelectedUserIds();
    handleSelectedUsers();
});
function generateReactionsAndReply() {
    $(document).on("click", function (event) {
        if (
            !$(event.target).closest(".reaction-dialog, .reaction-icon").length
        ) {
            $(".reaction-dialog").remove();
        }
    });
    $(document).on("click", ".reaction-icon", function (event) {
        event.stopPropagation();
        const messageId = $(this).data("message-id");
        const reactionDialog = `
        <div class="reaction-dialog" id="reaction-dialog-${messageId}">
            <span class="reaction-option" data-reaction="\\u{1F60D}"><img src="${asset_path}/ic_heart_eyes.png"/></span>
            <span class="reaction-option" data-reaction="\\u{1F604}"><img src="${asset_path}/ic_smile.png"/></span>
            <span class="reaction-option heart_reaction" data-reaction="\\u{2764}"><img src="${asset_path}/ic_like_reaction.png"/></span>
            <span class="reaction-option" data-reaction="\\u{1F44D}"><img src="${asset_path}/ic_thumsup.png"/></span>
            <span class="reaction-option" data-reaction="\\u{1F44F}"><img src="${asset_path}/ic_clapping_hand.png"/></span>
        </div>
    `;
        $(".reaction-dialog").remove(); // Remove any existing reaction dialogs

        $(this).append(reactionDialog);
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
        } catch (error) {
            console.error("Error updating reaction in Firebase:", error);
        }
    });

    $(document).on("click", ".reply-icon", async function () {
        $(".set-replay-msg").remove();
        replyMessageId = $(this).data("message-id");
        let isReceiver = $(this).data("isReceiver");
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
                            <div class='replay-child'>
                                <div class='d-flex flex-column'>
                                    <span class='replay-user'>${
                                        !isReceiver
                                            ? "You"
                                            : replyMessageData.receiverName
                                    }</span>
                                    <span class='replay-msg'>${
                                        replyMessageData.data
                                    }</span>
                                </div>
                                
                            <span class='close-replay'>&times</span>
                            </div>
                    </div>`;
        } else {
            const replyMessageRef = ref(
                database,
                `Messages/${conversationId}/message/${replyMessageId}`
            );
            const replyMessageSnapshot = await get(replyMessageRef);
            const replyMessageData = replyMessageSnapshot.val();
            // console.log(replyMessageData);

            replay = `<div class='set-replay-msg'>
            <div class='replay-child'>
              <div class='d-flex flex-column'>
                        <span class='replay-user'>${
                            !isReceiver ? "You" : senderUserName
                        }</span>
                        <span class='replay-msg' data-time='${
                            replyMessageData.timeStamp
                        }'>${replyMessageData.data}</span>
                        
                           </div>
                                
                            <span class='close-replay'>&times</span>
                            </div>
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
    let profileModel = document.getElementById("profileModel");
    let selected_user_profile = document.getElementById(
        "selected-user-profile"
    );
    var file = this.files[0];
    var reader = new FileReader();
    reader.onload = function (e) {
        $(profileModel).replaceWith(
            `<img id="profileModel" src="${e.target.result}" alt="user-img">`
        );
    };
    reader.readAsDataURL(this.files[0]);
    setTimeout(async () => {
        if (file) {
            const fileRef = storageRef(
                storage,
                `/GroupProfile/${senderUser}/${Date.now()}_${file.name}`
            );
            let profileModel = document.getElementById("profileModel");
            const previewImg = $(profileModel);
            const imageUrl = previewImg.attr("src");
            console.log(imageUrl);
            if (imageUrl?.startsWith("data:image/")) {
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
            $(selected_user_profile).replaceWith(
                `<img src="${downloadURL}" id="selected-user-profile"/>`
            );
            $(profileModel).replaceWith(
                `<img src="${downloadURL}" id="profileModel"/>`
            );

            $(".conversation-" + conversationId)
                .find(".chat-data")
                .find(".user-img")
                .html(
                    `<img class="user-avatar img-fluid" src="${downloadURL}" alt="cover-img" >`
                );

            await update(groupInfoRef, { groupProfile: downloadURL });
            SelecteGroupUser.map((user) => {
                var groupUserInfoRef = ref(
                    database,
                    `/overview/${user.id}/${conversationId}/`
                );

                update(groupUserInfoRef, { receiverProfile: downloadURL });
            });
        }
    }, 800);
});

async function startRecording() {
    recordedChunks = [];
    try {
        stream = await navigator.mediaDevices.getUserMedia({ audio: true });
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
    } catch (err) {
        console.error("Error accessing microphone: ", err);
        toastr.error("Failed to access microphone. Please try again.");
    }
}

function playRecording() {
    $(".close-audio-btn").show();

    const blob = new Blob(recordedChunks, { type: "audio/wav" });
    const audioURL = URL.createObjectURL(blob);

    audioElement.src = audioURL;
    // audioElement.style.display = "block";

    playButton.style.display = "none";
    // stopPlaybackButton.style.display = "inline-block";

    // audioElement.play().catch((err) => {
    //     console.error("Error playing audio: ", err);
    //     alert("Failed to play recorded audio.");
    // });
}
async function stopRecording() {
    if (mediaRecorder && mediaRecorder.state === "recording") {
        mediaRecorder.stop();
        $("#send_audio").show();
        $("#musicContainer").show();

        stopButton.style.display = "none";

        // Wait for the MediaRecorder to finish saving data
        await new Promise((resolve) => {
            mediaRecorder.onstop = resolve;
        });
        stream.getTracks().forEach((track) => track.stop());
        // Call playRecording() to initiate playback
        playRecording();
        setTimeout(() => {
            const newPlayer = document.querySelector("#audioContainer");
            newPlayer.classList.remove("initialized");
            initializeAudioPlayer(newPlayer);
        }, 500);
    } else {
        console.error("MediaRecorder is not recording.");
    }
}

startButton.addEventListener("click", startRecording);
stopButton.addEventListener("click", stopRecording);
playButton.addEventListener("click", playRecording);
stopPlaybackButton.addEventListener("click", stopPlayback);
$("#musicContainer").hide();

$(".close-audio-btn").on("click", function () {
    $("#musicContainer").hide();
    $("#send_audio").hide();
    $(".preview_img").attr("src", "");
    $(".recordedAudio").attr("src", "");

    $(".upload-box").val("");
    $(".file_info").val("");

    startButton.style.display = "inline-block";
});

$(".preview_img").hide();
$("#preview_file").hide();
$(".preview_file").hide();

$(".upload-box").change(function () {
    $(".file_info").val("");
    var curElement = $(".preview_img");
    var file = this?.files[0] != undefined ? this?.files[0] : [];
    if (file.length <= 0) {
        return;
    }
    console.log("new file");
    var name = file?.name;
    displayFiles(this.files, name);
    $(".dropdown-menu").removeClass("show");

    var fileExtension = file.name.substr(file.name.lastIndexOf(".") + 1);
    console.log(fileExtension);
    if (file) {
        var reader = new FileReader();

        if (file.type.match("image.*")) {
            reader.onload = function (e) {
                curElement.attr("src", e.target.result).show();
            };

            reader.readAsDataURL(file);
        } else if (file.type.match("video.*")) {
            curElement.attr("src", URL.createObjectURL(file)).show();

            $("#file_name").text("");
        } else if (file.type.match("audio.*")) {
            curElement.attr("src", URL.createObjectURL(file)).show();

            $("#file_name").text("audio");
        } else if (file.type === "application/pdf") {
            // Handling PDF files
            reader.onload = function (e) {
                curElement
                    .attr("src", URL.createObjectURL(e.target.result))
                    .show();
            };
            reader.readAsDataURL(file);
        } else if (
            file.type === "application/vnd.ms-excel" ||
            file.type ===
                "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
        ) {
            // Handle Excel file display
            reader.onload = function (e) {
                curElement
                    .attr("src", URL.createObjectURL(e.target.result))
                    .hide();
            };
        } else {
            // Handle other file types (text, docs)
            reader.onload = function (e) {
                curElement
                    .attr("src", URL.createObjectURL(e.target.result))
                    .hide();
            };
            reader.readAsText(file);
        }
        $(".file_info").val(fileExtension);
        $("#file_name").text(file.name);

        $(".send-message").focus();
    } else {
        alert("Please select a file.");
        $(".preview_img").hide();
        curElement.attr("src", "");
    }
});

function displayFiles(files, name) {
    var preview = document.getElementById("preview");
    $(preview).show();
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
                } else if (fileType === "image") {
                    previewElement = document.createElement("img");
                    previewElement.style.maxWidth = "100%";
                } else if (file.type === "application/pdf") {
                    previewElement = document.createElement("iframe");
                    previewElement.style.maxWidth = "100%";
                    previewElement.style.height = "400px";
                } else {
                    previewElement = document.createElement("img");
                    var previewElementnew = document.createElement("p");
                    previewElementnew.className = "preview_file";
                    previewElementnew.textContent = `File selected: ${file.name}`;
                    previewItem.appendChild(previewElementnew);
                    previewElement.style.display = "none";
                    previewItem.style.height = "auto";
                }
                var closeButton = document.createElement("button");
                closeButton.innerHTML = closeSpan;
                closeButton.className = "close-preview";
                previewItem.appendChild(closeButton);

                previewElement.className = "preview_img";
                previewElement.src = e.target.result;
                previewItem.appendChild(previewElement);
                preview.appendChild(previewItem);
                // $(previewElement).show();
            };
        })(file);

        reader.readAsDataURL(file);
    }
}

$(document).on("click", ".close-preview", function () {
    $("#preview").hide();
    $(".preview_img").attr("src", "");
    $(".upload").val("");
});

async function getTotalUnreadMessageCount() {
    const userId = senderUser; // Assuming senderUser is the ID of the current user
    const overviewRef = ref(database, `overview/${userId}`);
    const snapshot = await get(overviewRef);
    let totalUnreadCount = 0;
    if (await snapshot.exists()) {
        const conversations = await snapshot.val();
        for (let conversationId in conversations) {
            if (
                conversations[conversationId].unReadCount &&
                conversations[conversationId].contactName
            ) {
                totalUnreadCount =
                    totalUnreadCount +
                    parseInt(conversations[conversationId].unReadCount, 10);

                // console.log(totalUnreadCount);
            }
        }
    }

    return totalUnreadCount;
}

// Function to update badge with unread message count
async function updateUnreadMessageBadge(conversationId = null) {
    console.log("updateUnreadMessageBadge");
    const totalUnreadCount = await getTotalUnreadMessageCount();
    console.log(totalUnreadCount);
    if (parseInt(totalUnreadCount) > 0) {
        $(".badge").show();
        $(".g-badge").show();
        $(".g-badge").html(parseInt(totalUnreadCount));
        $(".badge").html(parseInt(totalUnreadCount));
    } else {
        $(".g-badge").hide();
        $(".badge").hide();
        $(".g-badge").html("");
        $(".badge").html("");
    }

    $(".set-replay-msg").remove();
    replyMessageId = null;
}

// Call the function on page load
$(document).ready(function () {
    updateUnreadMessageBadge();
});
$(document).on("click", ".bulk-check .form-check-input", function (event) {
    event.stopPropagation(); // Prevent event propagation to .msg-list

    const checkedConversations = $(
        "input[name='checked_conversation[]']:checked"
    );
    let allPinned = true;
    let somePinned = false;
    let allMuted = true;
    let someMuted = false;
    if (checkedConversations.length <= 0) {
        $(".multi-pin").attr("changeWith", "1");
        $(".pin-icn").removeClass("d-none");
        $(".unpin-icn").addClass("d-none");

        $(".multi-mute").attr("changeWith", "1");
        $(".mute-icn").removeClass("d-none");
        $(".unmute-icn").addClass("d-none");
        return;
    }
    checkedConversations.each(function () {
        const conversationElement = $(this).parent().parent();
        if (conversationElement.hasClass("pinned")) {
            somePinned = true;
        } else {
            allPinned = false;
        }

        if (conversationElement.hasClass("muted")) {
            someMuted = true;
        } else {
            allMuted = false;
        }
    });
    if (allPinned) {
        $(".multi-pin").attr("changeWith", "0");
        $(".pin-icn").addClass("d-none");
        $(".unpin-icn").removeClass("d-none");
    } else {
        $(".multi-pin").attr("changeWith", "1");
        $(".pin-icn").removeClass("d-none");
        $(".unpin-icn").addClass("d-none");
    }
    console.log({ allMuted });
    if (allMuted) {
        $(".multi-mute").attr("changeWith", "0");
        $(".mute-icn").addClass("d-none");
        $(".unmute-icn").removeClass("d-none");
    } else {
        $(".multi-mute").attr("changeWith", "1");
        $(".mute-icn").removeClass("d-none");
        $(".unmute-icn").addClass("d-none");
    }
});
$(".bulk-edit").click(function () {
    var bulkcheck = document.getElementsByClassName("bulk-check");
    $(bulkcheck).removeClass("d-none");
    $(".chat-functions").removeClass("d-none");
    $(".bulk-edit-option").hide();
    $(".chat-header-searchbar").hide();
});
$(".bulk-back").click(function () {
    var bulkcheck = document.getElementsByClassName("bulk-check");
    $(bulkcheck).addClass("d-none");
    $(".chat-functions").addClass("d-none");
    $(".bulk-edit-option").show();
    $(".check-counter").text("");
    $(".chat-header-searchbar").show();

    $("input[name='checked_conversation[]']").prop("checked", false);
});

$(document).on("change", "input[name='checked_conversation[]']", function () {
    const checkedCount = $(
        "input[name='checked_conversation[]']:checked"
    ).length;
    $(".check-counter").text(checkedCount);
});
$(".multi-pin").click(async function () {
    const checkedConversations = $(
        "input[name='checked_conversation[]']:checked"
    )
        .toArray()
        .reverse();
    if (checkedConversations.length <= 0) {
        return;
    }
    const pinChange = $(this).attr("changeWith");
    $(this).attr("changeWith", pinChange == "1" ? "0" : "1");
    if (pinChange == "1") {
        $(".unpin-icn").removeClass("d-none");
        $(".pin-icn").addClass("d-none");
    } else {
        $(".pin-icn").removeClass("d-none");
        $(".unpin-icn").addClass("d-none");
    }

    const promises = [];
    checkedConversations.forEach(function (element) {
        const conversationId = $(element).val();
        const overviewRef = ref(
            database,
            `overview/${senderUser}/${conversationId}/isPin`
        );
        promises.push(set(overviewRef, pinChange));
        $(".conversation-" + conversationId)
            .children()
            .find(".pin-single-conversation")
            .find("span")
            .text(pinChange == "1" ? "Unpin" : "Pin");

        $(".conversation-" + conversationId)
            .children()
            .find(".pin-single-conversation")
            .attr("changeWith", pinChange == "1" ? "0" : "1");

        if (pinChange == "1") {
            // conversationElement.prependTo(".chat-list");

            $(".conversation-" + conversationId).addClass("pinned");
            $(`.conversation-${conversationId}`)
                .find(".chat-data")
                .find(".pin-svg")
                .removeClass("d-none");

            $(".conversation-" + conversationId)
                .children()
                .find(".pin1-self-icn")
                .addClass("d-none");
            $(".conversation-" + conversationId)
                .children()
                .find(".unpin1-self-icn")
                .removeClass("d-none");
            const conversationElement = $(`.conversation-${conversationId}`);
            moveToTopOrBelowPinned(conversationElement);
        } else {
            $(".conversation-" + conversationId).removeClass("pinned");
            $(`.conversation-${conversationId}`)
                .find(".chat-data")
                .find(".pin-svg")
                .addClass("d-none");

            $(".conversation-" + conversationId)
                .children()
                .find(".pin1-self-icn")
                .removeClass("d-none");
            $(".conversation-" + conversationId)
                .children()
                .find(".unpin1-self-icn")
                .addClass("d-none");
            const conversationElement = $(`.conversation-${conversationId}`);
            moveToTopOrBelowPinned(conversationElement);
        }
    });

    try {
        await Promise.all(promises);
        $("input[name='checked_conversation[]']").prop("checked", false);

        $(this)
            .find("span")
            .text(pinChange == "1" ? "Unpin" : "Pin");
        $(this).attr("changeWith", pinChange == "1" ? "0" : "1");
    } catch (error) {
        console.error("Error updating pin status:", error);
    }
    console.log("from click");

    $(".bulk-back").click();
    toastr.success("Selected conversations have been updated.");
});

$(".multi-mute").click(function () {
    const checkedConversations = $(
        "input[name='checked_conversation[]']:checked"
    );
    const promises = [];
    if (checkedConversations.length <= 0) {
        return;
    }
    const change = $(this).attr("changeWith");
    $(this).attr("changeWith", change == "1" ? "0" : "1");

    checkedConversations.each(function () {
        const conversationId = $(this).val();
        if (change == "1") {
            $(".conversation-" + conversationId).addClass("muted");
            $(".conversation-" + conversationId)
                .children()
                .find(".mute1-self-icn")
                .addClass("d-none");
            $(".conversation-" + conversationId)
                .children()
                .find(".unmute1-self-icn")
                .removeClass("d-none");
        } else {
            $(".conversation-" + conversationId).removeClass("muted");
            $(".conversation-" + conversationId)
                .children()
                .find(".mute1-self-icn")
                .removeClass("d-none");
            $(".conversation-" + conversationId)
                .children()
                .find(".unmute1-self-icn")
                .addClass("d-none");
        }
        $(".conversation-" + conversationId)
            .find(".chat-data")
            .find(".mute-single-conversation")
            .find("span")
            .text(change == "1" ? "Unmute" : "Mute");

        $(".conversation-" + conversationId)
            .find(".chat-data")
            .find(".mute-single-conversation")
            .attr("changeWith", change == "1" ? "0" : "1");

        const overviewRef = ref(
            database,
            `overview/${senderUser}/${conversationId}/isMute`
        );
        set(overviewRef, change);
        promises.push(set(overviewRef, change));
    });

    $("input[name='checked_conversation[]']").prop("checked", false);
    console.log("from click");

    $(".bulk-back").click();
    toastr.success("Selected conversations have been updated.");
});

$(".multi-archive").click(function (e) {
    e.stopPropagation();
    const change = $(this).attr("changeWith");
    $(this).attr("changeWith", change == "1" ? "0" : "1");

    const checkedConversations = $(
        "input[name='checked_conversation[]']:checked"
    );
    if (checkedConversations.length <= 0) {
        return;
    }
    const promises = [];

    checkedConversations.each(function () {
        const conversationId = $(this).val();
        const overviewRef = ref(
            database,
            `overview/${senderUser}/${conversationId}/isArchive`
        );

        $(".conversation-" + conversationId)
            .find(".chat-data")
            .find(".archive-single1-conversation")
            .find("span")
            .text(change == "1" ? "Unarchive" : "Archive");

        $(".conversation-" + conversationId)
            .find(".chat-data")
            .find(".archive-single1-conversation")
            .attr("changeWith", change == "1" ? "0" : "1");

        if (change == "1") {
            $(".conversation-" + conversationId).addClass("archived-list");
            $(".conversation-" + conversationId).removeClass("unarchived-list");
        } else {
            $(".conversation-" + conversationId).addClass("unarchived-list");
            $(".conversation-" + conversationId).removeClass("archived-list");
        }
        promises.push(set(overviewRef, change));
    });

    Promise.all(promises)
        .then(() => {
            $("input[name='checked_conversation[]']").prop("checked", false);
            toastr.success(
                change == "1"
                    ? "Archived successfully"
                    : "Unarchived successfully"
            );
            console.log("from click");

            $(".archived-list").hide();

            // $("#archive-list").attr("list", "1").click();
            var unarchivelist =
                document.getElementsByClassName("unarchived-list");
            console.log(unarchivelist);
            let msgLists = $(unarchivelist);
            if (msgLists.length > 0) {
                msgLists[0].click(); // Ensure the list exists before clicking
            }
        })
        .catch((error) => {
            toastr.error("An error occurred while archiving/unarchiving.");
            console.error(error);
        });
    console.log("from click");

    $(".bulk-back").click();
});
$(".multi-read").click(function () {
    const checkedConversations = $(
        "input[name='checked_conversation[]']:checked"
    );
    if (checkedConversations.length <= 0) {
        return;
    }
    checkedConversations.each(function () {
        const conversationId = $(this).val();
        const overviewRef = ref(
            database,
            `overview/${senderUser}/${conversationId}/`
        );
        update(overviewRef, { unRead: false, unReadCount: 0 });
    });
    $("input[name='checked_conversation[]']").prop("checked", false);
    console.log("from click");

    $(".bulk-back").click();
    toastr.success("Selected conversations have been read.");
});

$(document).on("click", ".delete-conversation", async function () {
    var conversationId = $(".conversationId").attr("conversationid");
    const isGroup = $("#isGroup").val();

    if (!conversationId || !senderUser) {
        console.error("Conversation ID or Sender User ID is missing");
        return;
    }

    await deleteConversation(conversationId, isGroup);
    toastr.success("conversation have been deleted.");
});

$(document).on("click", ".delete-single-conversation", async function (e) {
    e.stopPropagation();
    var conversationId = $(this).data("conversation");
    const isGroup = $(this).data("isGroup");

    if (!conversationId || !senderUser) {
        console.error("Conversation ID or Sender User ID is missing");
        return;
    }

    await deleteConversation(conversationId, isGroup);
    toastr.success("conversation have been deleted.");
});
$(document).on("click", ".multi-delete", async function () {
    const checkedConversations = $(
        "input[name='checked_conversation[]']:checked"
    );
    if (checkedConversations.length <= 0) {
        return;
    }
    if (checkedConversations.length === 0) {
        alert("No conversations selected for deletion.");
        return;
    }

    const promises = [];
    checkedConversations.each(function () {
        const conversationId = $(this).val();
        const isGroup = $(this).attr("isGroup");
        promises.push(deleteConversation(conversationId, isGroup));
    });

    try {
        await Promise.all(promises);
        toastr.success("Selected conversations have been deleted.");
    } catch (error) {
        console.error("Error deleting conversations:", error);
    }
    console.log("from click");

    $(".bulk-back").click();
});

async function deleteConversation(conversationId, isGroup) {
    if (!conversationId || !senderUser) {
        console.error("Conversation ID or Sender User ID is missing");
        return;
    }

    // Remove conversation element from DOM
    $(`.conversation-${conversationId}`).remove();

    var overviewRef = ref(database, `overview/${senderUser}/${conversationId}`);
    await remove(overviewRef);

    if (isGroup === "true" || isGroup == true) {
        const groupInfoProfileRef = ref(
            database,
            `/Groups/${conversationId}/groupInfo/profiles`
        );
        const groupInfoProfileSnap = await get(groupInfoProfileRef);
        const groupInfoProfileData = groupInfoProfileSnap.val();

        if (groupInfoProfileData) {
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
        const messagesRef = ref(database, `Messages/${conversationId}/message`);
        const messagesSnapshot = await get(messagesRef);

        if (messagesSnapshot.exists()) {
            const messages = messagesSnapshot.val();
            const updates = {};

            for (var messageId in messages) {
                updates[
                    `Messages/${conversationId}/message/${messageId}/isDelete/${senderUser}`
                ] = "1";
            }
            await update(ref(database), updates);
        }
    }

    handleDelete();
}
function handleDelete() {
    var msgLists = $(".msg-list");
    if (msgLists.length > 0) {
        console.log("from click");

        msgLists.first().click(); // Simulate a click event on the first msg-list element
    } else {
        $(".msg-lists").html("");
        $(".selected-user-name").html("Start new chat");
        updateProfileImg("", "Start New");
        $(".conversationId").attr("conversationid", "");
        $(".selected_id").val("");
        $(".selected_message").val("");
        $(".selected_message").val("");

        $(".selected_name").val("");
        $("#isGroup").val("");
        $("#selected-user-name").html("Start new chat");
        $("#selected-user-lastseen").html("");
        $(".member-lists").html("");
        $(".selected-title").html("Start new chat");
        $(".empty-massage").css("display", "block");
        $(".msg-head").css("display", "none");
        $(".msg-footer").css("display", "none");
    }
}

async function send_push_notification(
    user_id,
    message,
    conversationId,
    storagePath,
    senderUser
) {
    const userSnapshot = await get(ref(database, `users/${user_id}/`));

    if (userSnapshot.exists()) {
        const user = userSnapshot.val();

        // console.log(senderUser);
        var key = firebaseConfig.server_key;
        const receiverName = $(".selected_name").val();
        // console.log(storagePath);

        var to = user?.userToken != undefined ? user?.userToken : "";
        if (to == "") {
            return;
        }
        var data = {
            title: senderUser,
            message: message,
            icon: "firebase-logo.png",
            senderUid: user.userId,
            conversationId: conversationId,
            click_action: "testClick",
            senderProfile: user.userProfile,
            // notification_image: storagePath,
            notification_image: storagePath,
            type: "chat",
        };

        var notification = {
            // title: user.userName,
            // body: message,
            // image: storagePath,
            title: senderUser,
            body: message,
            sound: "default",
            message: message,
            color: "#79bc64",
        };

        const payload = {
            message: {
                data: {
                    clickAction: "testClick",
                    conversationId: conversationId,
                    imageLink: "imageLink",
                    message: message,
                    senderProfile: user.userProfile,
                    senderUid: user.userId,
                    title: senderUser,
                    type: "chat",
                },
                notification: {
                    body: message,
                    title: senderUser,
                },
                token: to,
            },
        };
        fetch("get_access_token")
            .then((response) => response.json())
            .then((data) => {
                var accessToken = data.access_token;
                fetch(
                    "https://fcm.googleapis.com/v1/projects/yesvite-3a2c8/messages:send",
                    {
                        // fetch("https://fcm.googleapis.com/v1/projects/yesvite-976cd/messages:send", {
                        method: "POST",
                        headers: {
                            Authorization: "Bearer " + accessToken,
                            "Content-Type": "application/json",
                        },
                        body: JSON.stringify(payload),
                    }
                )
                    .then(function (response) {})
                    .catch(function (error) {
                        console.error(error);
                    });
            })
            .catch((error) => {
                console.error("Error fetching access token:", error);
            });
    }
}

$(document).on("click", ".reaction", function () {
    var m_id = $(".selected_conversasion").val();
    var senderId = $(".senderUser").val();
    var isGroup = $("#isGroup").val();

    var c_id = $(this).data("message-id");

    // var c_id=$(this).closest($(".reaction-icon").data("message-id"));
    // console.log(isGroup);
    // console.log(c_id);
    // console.log(senderId);

    deletereaction(isGroup, c_id, m_id, senderId);

    // alert(c_id);
});

async function deletereaction(
    isGroup,
    messageId,
    conversationId,
    senderId = null
) {
    if (isGroup == true || isGroup == "true") {
        const messagesRef = ref(
            database,
            `Groups/${conversationId}/message/${messageId}/messageReact`
        );
        const messagesSnapshot = await get(messagesRef);
        // console.log(messagesSnapshot.val());
        if (messagesSnapshot.exists()) {
            var overviewRef = ref(
                database,
                `Groups/${conversationId}/message/${messageId}/messageReact/${senderId}`
            );
            await remove(overviewRef);
        }
    } else {
        const messagesRef = ref(
            database,
            `Messages/${conversationId}/message/${messageId}`
        );
        const messagesSnapshot = await get(messagesRef);

        if (messagesSnapshot.exists()) {
            const messages = messagesSnapshot.val();
            // console.log(messages.receiverId);
            if (messages.receiverId == senderId) {
                const updates = {};
                updates[
                    `Messages/${conversationId}/message/${messageId}/react`
                ] = "";

                await update(ref(database), updates);
            }
        }
    }
}

$(document).on("keyup", "#serach_user_from_list", function () {
    var searchTerm = $(this).val().toLowerCase().trim();

    // Iterate through each `li` in the chat list
    var msglist = document.getElementsByClassName("msg-list");
    $(msglist).each(function () {
        var searchData = $(this).data("search").toLowerCase().trim();

        // Check if the search term is contained in the data-search attribute
        if (searchData.includes(searchTerm)) {
            // If match found, remove the 'd-none' class to display the item
            $(this).removeClass("d-none");
        } else {
            // If no match, add 'd-none' class to hide the item
            $(this).addClass("d-none");
        }
    });
});
var hostCreated = false;
if ($("#host_id").length && $("#nav-messaging-tab").length) {
    $("#nav-messaging-tab").on("click", async function () {
        if (hostCreated == true) {
            return;
        }
        hostCreated = true;

        var co_host_id = $("#co_host_id").val();
        var co_host_name = $("#co_host_name").val();
        var co_host_profile = $("#co_host_profile").val();
        if (co_host_id != "") {
            await sendMessageHost(
                co_host_id,
                co_host_name,
                co_host_profile,
                "co-host"
            );
        }

        var hostId = $("#host_id").val();
        var hostName = $("#host_name").val();
        var hostImage = $("#host_profile").val();
        await sendMessageHost(hostId, hostName, hostImage, "host");
    });
}
if ($(".chost-msg").length) {
    $(".chost-msg").on("click", async function () {
        var co_host_id = $("#co_host_id").val();
        var co_host_name = $("#co_host_name").val();
        var co_host_profile = $("#co_host_profile").val();
        if (co_host_id != "") {
            await sendMessageHost(
                co_host_id,
                co_host_name,
                co_host_profile,
                "co-host"
            );
        }
    });
}

if ($(".host-msg").length) {
    $(".host-msg").on("click", async function () {
        var hostId = $("#host_id").val();
        var hostName = $("#host_name").val();
        var hostImage = $("#host_profile").val();
        await sendMessageHost(hostId, hostName, hostImage, "host");
    });
}
async function sendMessageHost(contactId, contactName, receiverProfile, type) {
    const currentUserId = senderUser;
    const conversationId = await findOrCreateSingleConversation(
        currentUserId,
        contactId,
        contactName,
        receiverProfile
    );
    const blockByMeRef = ref(database, `users/${senderUser}/blockByUser`);
    const blockByUserRef = ref(database, `users/${senderUser}/blockByMe`);

    const blockByMeSnapshot = await get(blockByMeRef);
    const blockByUserSnapshot = await get(blockByUserRef);

    let isBlockedByMe = false;
    let isBlockedByUser = false;

    if (blockByMeSnapshot.exists()) {
        const blockByMeList = blockByMeSnapshot.val();
        isBlockedByMe = blockByMeList.includes(contactId);
    }

    if (blockByUserSnapshot.exists()) {
        const blockByUserList = blockByUserSnapshot.val();
        isBlockedByUser = blockByUserList.includes(contactId);
    }

    if (isBlockedByMe || isBlockedByUser) {
        return;
    }

    // const message = $(this).val();
    const selectedMessageId = conversationId;
    $(".selected_id").val(conversationId);
    $(".selected_message").val(contactId);
    $(".selected_name").val(contactName);

    var ele = $(
        document.getElementsByClassName(`conversation-${conversationId}`)
    );
    $(".msg-list").removeClass("active");
    $(ele).addClass("active");
    $(ele).find(".user-detail").children().find(".host-type").text(type);
    $(ele)
        .find(".user-detail")
        .children()
        .find(".host-type")
        .removeClass("d-none");

    await updateChat(contactId);
}
if ($(".msg-btn").length && $("#nav-messaging-tab").length) {
    $(".msg-btn").on("click", function () {
        $("#nav-messaging-tab").click();
    });
}
async function findOrCreateSingleConversation(
    currentUserId,
    contactId,
    contactName,
    receiverProfile
) {
    let userData = await get(userRef);
    let userSnap = userData.val();

    const newConversationId = await generateConversationId([
        currentUserId,
        contactId,
    ]);
    let receiverSnapshot = await get(
        ref(database, `overview/${currentUserId}/${newConversationId}`)
    );
    if (receiverSnapshot.val() != null) {
        console.log("no need to new ");
        return newConversationId;
    }
    const newConversationData = {
        contactId: contactId,
        contactName: contactName,
        conversationId: newConversationId,
        group: false,
        lastMessage: "",
        lastSenderId: currentUserId,
        receiverProfile: receiverProfile,
        timeStamp: Date.now(),
        // unRead: true,
        // unReadCount: 1,
    };

    await set(
        ref(database, `overview/${currentUserId}/${newConversationId}`),
        newConversationData
    );

    return newConversationId;
}
setTimeout(function () {
    firstTime = false;
    loader.hide();
}, 5000);

function applyStyles() {
    if ($(window).width() <= 767) {
        $("#backtomsg-btn").show();

        $(".chatbox").css("display", "none");
        $(document).on("click", ".chat-data", function () {
            $(".chatbox").css("display", "block");
            $(".chat-lists").css("display", "none");
        });
        $(document).on("click", "#backtomsg-btn", function () {
            $(".chatbox").css("display", "none");
            $(".chat-lists").css("display", "block");
            var msgLists = $(".msg-list");
            if (msgLists.length > 0) {
                console.log("from click");

                msgLists.last().click();
            }
        });
        // $(document).on('click','.chat-data',function(){
        //   $(".chatbox").css("display", "block");
        //   $(".chat-lists").css("display", "none");
        // })
    } else {
        $("#backtomsg-btn").hide();
        // $(".chatbox").css("display", "block");
    }
}

// Apply styles on page load
applyStyles();

//backup code for moveToTopOrBelowPinned
// function moveToTopOrBelowPinned(element) {
//     if (firstTime == true || !isToMove) {
//         isToMove = true;
//         return;
//     }
//     if (element.length <= 0) {
//         return;
//     }
//     console.log("moved====================");

//     let $chatList = $(".chat-list"); // Get the chat list container
//     let parentDiv = element.closest("div"); // Get the parent div of the li element
//     let isPinned = element.hasClass("pinned"); // Check if the element is pinned

//     // If the element is pinned, move it to the very top
//     if (isPinned) {
//         console.log("pinned on top");
//         $chatList.prepend(parentDiv); // Move pinned element to the top
//     } else {
//         // If not pinned, move it after the last pinned element
//         let lastPinnedDiv = $chatList.find("div:has(.pinned)").last();
//         console.log(lastPinnedDiv);
//         if (lastPinnedDiv.length > 0) {
//             lastPinnedDiv.after(parentDiv); // Place after the last pinned parent div
//         } else {
//             $chatList.prepend(parentDiv); // If no pinned elements exist, prepend the parent div to the very top
//         }
//     }
// }
$("#chatreport").validate({
    rules: {
        report_type: {
            required: true, // Specify 'required' instead of 'true'
        },
    },
    messages: {
        report_type: {
            required: "Please select a report type.", // Custom error message
        },
    },
});

$(document).on(
    "click",
    ".report-single-conversation, .report-conversation",
    function () {
        var conversation = $(this).attr("data-conversation");
        var userId = $(this).attr("data-userid"); // Ensure the attribute name matches exactly

        $("#report_conversation_id").val(conversation);
        $("#to_be_reported_user_id").val(userId);
    }
);

let host_id = $("#host_id").val();
if (host_id != undefined && host_id != "") {
    let host_image = $("#host_image").val();
    let host_name = $("#host_name").val();
    await sendMessageHost(host_id, host_name, host_image, "host");
}
