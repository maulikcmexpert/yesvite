// const { error } = require("toastr");

var selectedFiles = null; // To store selected files
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
// // Step 1: Preview the selected media
// function previewStoryImage(event, userId) {
//     const allowedTypes = ['image/png', 'image/jpeg', 'image/jpg', 'image/gif', 'video/mp4', 'video/webm'];
//     const files = event.target.files;
//     const validFiles = [];
//     const invalidFiles = [];

//     const previewContainer = document.getElementById(`preview-${userId}`);
//     const uploadButton = document.getElementById(`upload-button-${userId}`);
//     const previewModal = document.getElementById(`previewModel-${userId}`);
//     previewContainer.innerHTML = ''; // Clear previous preview

//     if (files.length > 0) {
//         Array.from(files).forEach(file => {
//             if (allowedTypes.includes(file.type)) {
//                 validFiles.push(file);
//                 const fileUrl = URL.createObjectURL(file);
//                 let mediaElement;

//                 if (file.type.startsWith('image/')) {
//                     mediaElement = document.createElement('img');
//                 } else if (file.type.startsWith('video/')) {
//                     mediaElement = document.createElement('video');
//                     mediaElement.controls = true; // Add video controls
//                 }

//                 if (mediaElement) {
//                     mediaElement.src = fileUrl;
//                     mediaElement.classList.add('story-preview'); // Add a class for styling
//                     previewContainer.appendChild(mediaElement);
//                 }
//             } else {
//                 invalidFiles.push(file.name);
//             }
//         });

//         if (invalidFiles.length > 0) {
//             alert(`The following files are not valid and will be ignored:\n${invalidFiles.join(', ')}`);
//         }

//         // Show the upload button and modal if valid files exist
//         if (validFiles.length > 0) {
//             uploadButton.style.display = 'flex';
//             if (previewModal) previewModal.style.display = 'flex';
//             previewContainer.style.display = 'flex';
//         }
//     } else {
//         console.log("No file selected.");
//     }
// }
function previewStoryImage(event, userId) {
    const files = event.target.files;
    selectedFiles = files; // Store files for uploading later
    const previewContainer = document.getElementById(`preview-${userId}`);
    const uploadButton = document.getElementById(`upload-button-${userId}`);
    previewContainer.innerHTML = ""; // Clear previous preview

    if (files.length > 0) {
        Array.from(files).forEach((file) => {
            const fileUrl = URL.createObjectURL(file);
            let mediaElement;

            if (file.type.startsWith("image/")) {
                mediaElement = document.createElement("img");
            } else if (file.type.startsWith("video/")) {
                mediaElement = document.createElement("video");
                mediaElement.controls = true; // Add video controls for videos
            }

            if (mediaElement) {
                mediaElement.src = fileUrl;
                mediaElement.classList.add("story-preview"); // Add a class for styling if needed
                previewContainer.appendChild(mediaElement);
            }
        });

        // Show the Upload button after preview
        uploadButton.style.display = "flex";
        const previewModal = document.getElementById(`previewModel-${userId}`); // Modal itself
        if (previewModal && previewContainer) {
            previewModal.style.display = "flex"; // Show the modal
            previewContainer.style.display = "flex"; // Ensure story display is visible
        }
    } else {
        console.log("No file selected.");
    }
}

function closePreviewModal(userId) {
    const previewContainer = document.getElementById(`preview-${userId}`);
    const previewModal = document.getElementById(`previewModel-${userId}`);
    const fileInput = document.getElementById(`story-upload-${userId}`);

    if (previewModal && previewContainer) {
        // Hide the modal and preview container
        previewModal.style.display = "none";
        previewContainer.style.display = "none";

        // Revoke object URLs to free memory
        const mediaElements = previewContainer.querySelectorAll("img, video");
        mediaElements.forEach((media) => {
            URL.revokeObjectURL(media.src); // Revoke object URL
        });

        // Clear the preview container for the next upload
        previewContainer.innerHTML = "";

        // ✅ Reset the file input to allow re-uploading the same file
        fileInput.value = "";
    }
}

// Step 2: Upload the selected files on button click
function uploadStoryImage(eventId, userId) {
    if (!selectedFiles) {
        alert("No files selected for upload.");
        return;
    }

    const formData = new FormData();
    Array.from(selectedFiles).forEach((file) => {
        formData.append("story[]", file);
    });

    formData.append("eventId", eventId);
    formData.append("_token", $('meta[name="csrf-token"]').attr("content"));

    $.ajax({
        url: base_url + "event_wall/createStory",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function (data) {
            console.log(data);
            if (data.status === 1) {
                console.log("Upload successful, adding pink border.");

                const profilePic = document.getElementById(
                    `profile-pic-${userId}`
                );
                const profileContainer = document.getElementById(
                    `profile-container-${userId}`
                );
                const listItem = profilePic
                    ? profilePic.closest(".wall-main-story-item")
                    : null;
                console.log(listItem);
                index = 0;
                // if (profilePic) {
                //     profilePic.style.borderColor = 'red'; // Set border color to red during the upload
                //     profilePic.classList.add('pink-border'); // Add pink border during upload
                // } else {
                //     console.error("Profile picture not found.");
                // }

                if (profileContainer) {
                    profileContainer.style.display = "block";
                }

                if (listItem) {
                    console.log(1);

                    listItem.classList.add("new-story"); // Add the 'new-story' class to the story item
                } else {
                    console.error("List item not found.");
                }

                closePreviewModal(userId);
                // showStories(eventId, userId, true);
                fetchStories(eventId, userId, true, "image");
                // Set the border color to gray after viewing the story
                // setTimeout(() => {

                //     if (listItem) {
                //         listItem.classList.remove("new-story"); // Optionally, remove the 'new-story' class
                //     }
                // }, 10000); // Adjust the timeout duration as needed
            }
        },
        error: function (error) {
            console.error("Error uploading story:", error);
            alert("Image uploading failed.");
        },
    });

    // Reset file input and preview
    document.getElementById(`story-upload-${userId}`).value = "";
    document.getElementById(`preview-${userId}`).innerHTML = "";
    document.getElementById(`upload-button-${userId}`).style.display = "none";
    selectedFiles = null; // Reset selected files
}

// Step 3: SHOW STORY
function showStories(eventId, userId, isNewUpload = false) {
    index = 0;
    const storyDisplay = document.getElementById(`story-display-${userId}`);
    const storyModal = document.getElementById(`storyModal-${userId}`);
    // console.log("showStories index"  + index );
    // Wait until the page is fully loaded
    if (document.readyState !== "complete") {
        console.log("Page is still loading. Waiting to open the story modal.");
        window.addEventListener("load", () => {
            openStoryModal(
                storyDisplay,
                storyModal,
                eventId,
                userId,
                isNewUpload
            );
        });
    } else {
        openStoryModal(storyDisplay, storyModal, eventId, userId, isNewUpload);
    }
}

function AllUserStory(eventId, storyId, isNewUpload = false) {
    console.log("Story ID:", storyId);
    const storyDisplay = document.getElementById(`story-display-${storyId}`);
    const storyModal = document.getElementById(`storyModal-${storyId}`);
    if (storyDisplay && storyModal) {
        storyModal.style.display = "flex";
        storyDisplay.style.display = "flex";
    }
    const storyType = "other";

    // Ensure that the storyId passed corresponds to the correct user's ID
    fetchStories(eventId, storyId, isNewUpload, storyType);

    // Add the gray border class to the profile picture after viewing
    const profilePic = document.querySelector(
        `.story-profile-pic[onclick="AllUserStory(${eventId}, '${storyId}')"]`
    );

    if (profilePic) {
        // Add the gray border class to the profile picture
        profilePic.classList.add("viewed-story");
        profilePic.classList.remove("story-unseen");
    }
}

async function fetchStories(eventId, userId, isNewUpload, storyType) {
    try {
        const response = await fetch(
            `${base_url}event_wall/fetch-user-stories/${eventId}?storyType=${storyType}`
        );
        const data = await response.json();
        console.log(isNewUpload);

        if (data.status !== 1) {
            throw new Error("Failed to fetch stories: " + data.message);
        }
        console.log(userId);

        let storyDisplay = document.getElementById(`story-display-${userId}`);
        if (!storyDisplay) {
            console.error(
                `Element with ID 'story-display-${userId}' not found.`
            );
            // return;
        }
        console.log(storyDisplay); // Log the storyDisplay element
        console.log(storyDisplay.querySelector(".story-content")); // Log the
        const storyContent = storyDisplay.querySelector(".story-content");
        const progressBarContainer = storyDisplay.querySelector(
            ".progress-bar-container"
        );

        // Clear previous content
        storyContent.innerHTML = "";
        progressBarContainer.innerHTML = "";
        index = 0;
        const storyElements = [];
        const storyDurations = [];
        const storyPostTimes = [];

        // Process 'owner_stories'
        if (Array.isArray(data.data.owner_stories)) {
            data.data.owner_stories.forEach((story) => {
                if (story.user_id === userId) {
                    story.story.forEach((storyData) => {
                        const mediaElement = document.createElement(
                            storyData.type === "video" ? "video" : "img"
                        );
                        mediaElement.src = storyData.storyurl;
                        mediaElement.classList.add("story-preview");

                        if (storyData.type === "video") {
                            mediaElement.controls = false;
                            mediaElement.autoplay = false;
                            mediaElement.muted = true;
                        }

                        const storyItemContainer =
                            document.createElement("div");
                        storyItemContainer.classList.add("story-item");
                        storyItemContainer.dataset.storyId = storyData.id;

                        // Add post time
                        if (storyData.post_time) {
                            const postTimeElement = document.createElement("p");
                            postTimeElement.classList.add("post-time");
                            postTimeElement.textContent = storyData.post_time;
                            storyItemContainer.appendChild(postTimeElement);
                            storyPostTimes.push(storyData.post_time); // Store post time
                        }

                        storyItemContainer.appendChild(mediaElement);
                        storyContent.appendChild(storyItemContainer);
                        storyElements.push({
                            element: mediaElement,
                            type: storyData.type,
                        });
                        storyDurations.push(
                            storyData.type === "video" ? 0 : 5000
                        );
                    });
                }
            });
        }

        // Process 'other_stories'
        console.log(data.data.other_stories);
        console.log(storyElements);

        if (Array.isArray(data.data.other_stories)) {
            data.data.other_stories.forEach((story) => {
                console.log("story_id", story.user_id);
                console.log("user_id", userId);
                if (story.user_id == userId) {
                    console.log(story.story);

                    story.story.forEach((allStory) => {
                        const mediaElement = document.createElement(
                            allStory.type === "video" ? "video" : "img"
                        );
                        mediaElement.src = allStory.storyurl;
                        mediaElement.classList.add("story-preview");

                        if (allStory.type === "video") {
                            mediaElement.controls = false;
                            mediaElement.autoplay = false;
                            mediaElement.muted = true;
                        }
                        const storyItemContainer =
                            document.createElement("div");
                        storyItemContainer.classList.add("story-item");
                        storyItemContainer.dataset.storyId = allStory.id;
                        storyElements.push({
                            element: mediaElement,
                            type: allStory.type,
                        });
                        storyDurations.push(
                            allStory.type === "video" ? 0 : 5000
                        );
                        storyPostTimes.push(allStory.post_time); // Store post time
                    });
                }
            });
        }

        if (!storyElements.length) {
            console.warn("No stories available for the specified user.");
            const storyModal = document.getElementById(`storyModal-${userId}`);
            const storyDisplay = document.getElementById(
                `story-display-${userId}`
            );
            storyModal.style.display = "none"; // Open the modal
            storyDisplay.style.display = "none";
        }

        // Pass data to the story display function
        displayStoriesWithProgressBars(
            storyElements,
            storyContent,
            progressBarContainer,
            userId,
            storyDurations,
            isNewUpload,
            storyPostTimes
        );
    } catch (error) {
        console.error("Error fetching stories:", error);
    }
}

function displayStoriesWithProgressBars(
    storyElements,
    storyContent,
    progressBarContainer,
    userId,
    storyDurations,
    isNewUpload = false,
    storyPostTimes
) {
    console.log(storyElements);
    console.log(isNewUpload);
    if (!storyElements || storyElements.length === 0) {
        console.error("No stories available to display.");
        return;
    }

    let index = 0; // Start with the first story
    // let index = 0; // Start with the first story

    let currentTimeout = null;
    let currentVideoElement = null;

    function resetProgressBars() {
        console.log("reset index" + index);

        progressBarContainer.innerHTML = "";
        //$('.progress-bar-container').html('');
        storyElements.forEach(() => {
            const progressBar = document.createElement("div");
            progressBar.classList.add("progress-bar");
            const progress = document.createElement("div");
            progress.classList.add("progress");
            progress.style.width = "0%";
            progressBar.appendChild(progress);
            progressBarContainer.appendChild(progressBar);
        });
        index = 0;
    }

    function resetCurrentStory() {
        if (currentTimeout) {
            clearTimeout(currentTimeout);
            currentTimeout = null;
        }
        if (currentVideoElement) {
            currentVideoElement.pause();
            currentVideoElement.currentTime = 0;

            // Remove previous event listeners
            currentVideoElement.ontimeupdate = null;
            currentVideoElement.onended = null;

            currentVideoElement = null;
        }
    }

    function showStory(currentIndex) {
        // progressBarContainer.innerHTML = '';
        resetCurrentStory();
        console.log("show index" + index);

        if (currentIndex >= storyElements.length) {
            console.log(currentIndex);
            console.log(storyElements);

            resetProgressBars();
            progressBarContainer.innerHTML = "";
            storyContent.innerHTML = "";
            const storyModal = document.getElementById(`storyModal-${userId}`);
            const displayModel = document.getElementById(
                `story-display-${userId}`
            );
            //   console.log('TEST');

            if (storyModal && displayModel) {
                console.log(storyModal);
                console.log(displayModel);

                storyModal.style.display = "none";
                displayModel.style.display = "none";
                $(".progress-bar-container").html("");
                storyContent = {};
            }
            index = 0;
            return;
        }
        // console.log(storyContent.innerHTML);
        console.log(storyElements.length);
        storyContent.innerHTML = ""; // Clear the content for the new story

        // Add post time display
        const postTimeElement = document.createElement("p");
        postTimeElement.classList.add("post-time");
        postTimeElement.textContent = storyPostTimes[currentIndex];
        storyContent.appendChild(postTimeElement);

        Array.from(progressBarContainer.children).forEach((bar, idx) => {
            const progress = bar.firstChild;
            if (idx < currentIndex) {
                progress.style.width = "100%";
            } else {
                progress.style.width = "0%";
            }
        });

        const { element, type } = storyElements[currentIndex];
        storyContent.appendChild(element);

        // console.log('progess'+ currentIndex);
        const progress = progressBarContainer.children[currentIndex].firstChild;

        if (type === "image") {
            let progressWidth = 0;
            const displayDuration = storyDurations[currentIndex];
            const increment = 100 / (displayDuration / 10);

            function updateImageProgress() {
                if (progressWidth < 100) {
                    // alert(1);
                    progressWidth += increment;
                    progress.style.width = progressWidth + "%";
                    currentTimeout = setTimeout(updateImageProgress, 10);
                } else {
                    index++;
                    console.log("progess index" + index);
                    showStory(index);
                }
            }

            updateImageProgress();
        } else if (type === "video") {
            resetCurrentStory(); // Ensure previous video is reset
            currentVideoElement = element;
            storyContent.appendChild(element);
            element.play();

            element.ontimeupdate = function () {
                if (element.duration > 0) {
                    const progressPercentage =
                        (element.currentTime / element.duration) * 100;
                    progress.style.width = progressPercentage + "%";
                }
            };

            element.onended = function () {
                index++; // Increment before calling showStory()
                showStory(index);
                console.log("window index" + index);
            };
        }
        let listItem = document
            .getElementById(`profile-pic-${userId}`)
            ?.closest(".wall-main-story-item");
        if (listItem) {
            listItem.classList.remove("new-story");
        }
    }

    var counter = 0;
    storyContent.addEventListener("click", (event) => {
        counter++;

        const contentWidth = storyContent.offsetWidth;
        const clickPosition =
            event.clientX - storyContent.getBoundingClientRect().left;
        // console.log({counter});

        if (clickPosition < contentWidth / 2 && index > 0) {
            index--; // Go back to the previous story
        } else if (clickPosition >= contentWidth / 2) {
            index++; // Advance to the next story
            // console.log("click index"  + index );
        }
        storyContent.innerHTML = "";
        console.log(storyContent);
        showStory(index);
    });

    function initializeStories() {
        resetProgressBars();
        showStory(index);
        index = 0;
    }
    $(document).on("click", ".modal-close", function () {
        var id = $(this).data("id");
        // alert(id);
        closeModal(id);
    });
    function closeModal(userId) {
        resetCurrentStory(); // Clear
        resetProgressBars();

        const modal = document.getElementById(`storyModal-${userId}`);
        const storyDisplay = document.getElementById(`story-display-${userId}`);
        console.log(`Stories for User ${userId}:`, storyElements);
        console.log(`Story Content Element for User ${userId}:`, storyContent);

        const progressBarContainer = document.querySelector(
            ".progress-bar-container"
        );
        storyContent = {};
        modal.style.display = "none";
        storyDisplay.style.display = "none";
        $(".progress-bar-container").html("");
        index = 0;
    }
    // Reset previous state when reopening the modal
    const storyModal = document.getElementById(`storyModal-${userId}`);
    console.log("model index" + index);
    if (storyModal) {
        storyModal.addEventListener("show", () => {
            resetProgressBars(); // Reset progress bars

            showStory(index); // Start from the last viewed story or first
        });
    }

    initializeStories();
}

// Function to open the poll modal
function openPollModal() {
    document.getElementById("pollModal").style.display = "block";
}
function openStoryModal(
    storyDisplay,
    storyModal,
    eventId,
    userId,
    isNewUpload
) {
    if (storyDisplay && storyModal) {
        storyModal.style.display = "flex";
        storyDisplay.style.display = "flex";
    }

    const storyType = "owner";

    // Ensure that the userId passed is correct
    fetchStories(eventId, userId, isNewUpload, storyType);
}
// Function to close the poll modal
function closePollModal() {
    document.getElementById("pollModal").style.display = "none";
}

// Close the modal when clicking outside of it
window.onclick = function (event) {
    const modal = document.getElementById("pollModal");
    if (event.target === modal) {
        modal.style.display = "none";
    }
};
// const displayPollData = (pollData) => {
//     // let html = ``;

//     // pollData.forEach((poll) => {
//     //     console.log('Poll Data:', poll);  // Debugging line

//     //     const pollEndDate = new Date(poll.poll_end_date);
//     //     const currentDate = new Date();
//     //     const isExpired = currentDate > pollEndDate;

//     //     html += `
//     //         <div class="poll-container" data-poll-id="${poll.poll_id}">
//     //             <h3 class="poll-question">${poll.poll_question}</h3>
//     //             <div class="error-message" style="color: red; display: none;" id="errorMessage-${poll.poll_id}"></div>
//     //             <ul class="poll-options">
//     //     `;

//     //     poll.poll_options.forEach(option => {
//     //         const isSelected = option.is_poll_selected;

//     // Disable the vote button if the poll is expired
//     const buttonDisabled = isExpired ? 'disabled' : '';

//     //         html += `
//     //             <li class="poll-option">
//     //                 <button
//     //                     class="option-button ${isSelected ? 'selected' : ''}"
//     //                     data-option-id="${option.id}"
//     //                     data-poll-id="${poll.poll_id}"
//     //                     ${buttonDisabled}
//     //                 >
//     //                     ${option.option} (${option.total_vote_percentage || '0%'})
//     //                 </button>
//     //             </li>
//     //         `;
//     //     });

//     //     html += `</ul></div>`;
//     // });

//     $('#pollContainer').html(html);  // Replace content inside pollContainer

//     // Handle button clicks
//     $('.option-button').on('click', function () {

//         const pollId = $(this).data('poll-id');
//         const errorMessageDiv = $(`#errorMessage-${pollId}`);

//         // Find the specific poll data by pollId
//         const poll = pollData.find(p => p.poll_id === pollId);

//         // Check if the poll is expired
//         if (poll && poll.is_expired) {
//             errorMessageDiv
//                 .text('This poll has expired. Voting is no longer allowed.')
//                 .show();
//         } else {
//             const optionId = $(this).data('option-id');

//             // Call the function to vote
//             voteOnPoll(pollId, optionId);

//             // Add 'selected' class to the clicked option and remove from others
//             $(this).closest('li').addClass('selected').siblings().removeClass('selected');
//         }
//     });
// };

// const fetchPollData = (eventId, eventPostId) => {
//     const url = base_url + "wall/get_poll";

//     $.ajax({
//         url: url,
//         method: 'POST',
//         headers: {
//             'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
//         },
//         contentType: 'application/json',
//         data: JSON.stringify({
//             event_id: eventId,
//             event_post_id: eventPostId,
//         }),
//         success: function (data) {
//             if (data.length > 0) {
//                 // If multiple polls, pass the whole data array
//                 displayPollData(data);
//             } else {
//                 console.error('No poll data returned.');
//             }
//         },
//         error: function (xhr, status, error) {
//             console.error('AJAX Error:', xhr.responseText || error);
//         }
//     });
// };

// fetchPollData(1547, 1);  // Example eventId and eventPostId

$(".option-button").on("click", function () {
    const pollId = $(this).data("poll-id"); // Gets poll ID (e.g., 456)
    const optionId = $(this).data("option-id"); // Gets option ID (e.g., 123)

    // Call the vote function
    voteOnPoll(pollId, optionId);
});

const voteOnPoll = (pollId, optionId) => {
    const url = base_url + "event_wall/votePoll";
    const eventPostId = document.getElementById("event_post_id").value;
    const eventId = document.getElementById("event_id").value;

    $.ajax({
        url: url,
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content"),
        },
        // contentType: 'application/json',
        // data: JSON.stringify({
        //     poll_id: pollId,
        //     option_id: optionId,
        // }),
        data: {
            poll_id: pollId,
            option_id: optionId,
            eventId: eventId,
            eventPostId: eventPostId,
        },
        success: function (data) {
            console.log("AJAX Response:", data);
            if (data.success) {
                // Update the poll UI with the new data received
                updatePollUI(data, pollId);
                //  alert('Vote submitted/updated successfully!');
            } else {
                toastr.error(data.message || "Failed to submit vote.");
            }
        },
        error: function (xhr, status, error) {
            console.error("AJAX Error:", xhr.responseText || error);
        },
    });
};

// Function to update the UI with the new poll data
// function updatePollUI(updatedPollData, pollId) {
//     console.log('Full Response:', updatedPollData);

//     // Check if poll_data is defined and has the expected structure
//     if (updatedPollData && Array.isArray(updatedPollData.poll_data) && updatedPollData.poll_data.length > 0) {
//         const pollContainer = document.querySelector(`.post-card-poll-wrp`);
//         if (!pollContainer) {
//             console.error('Poll container not found.');
//             return;
//         }

//         // Update total votes and remaining time
//         const pollInfo = updatedPollData.poll_data[0];
//         pollContainer.querySelector('h5').innerHTML = `${pollInfo.total_poll_vote} Votes <span>${pollInfo.poll_duration_left} left</span>`;

//         // Ensure poll options are an array
//         if (Array.isArray(pollInfo.poll_options)) {
//             pollInfo.poll_options.forEach(option => {
//                 console.log(`Checking for option ID: ${option.id}`);
//                 const optionElement = document.querySelector(`.poll-click-wrp`);
//                 if (optionElement) {
//                     optionElement.querySelector('.option-button').innerHTML = `${option.option} <span>${option.total_vote_percentage}</span>`;
//                     optionElement.querySelector('.poll-click-progress').style.width = `${option.total_vote_percentage}`;
//                 } else {
//                     console.warn(`Option element for ID ${option.id} not found.`);
//                 }
//             });

//         } else {
//             console.error('poll_options is not defined or is not an array');
//         }
//     } else {
//         console.error('poll_data is not defined or is empty');
//     }
// }

function updatePollUI(data, pollId) {
    const eventPostId = document.getElementById("event_post_id").value;
    const eventId = document.getElementById("event_id").value;

    console.log("Event ID:", eventId);
    console.log("Event Post ID:", eventPostId);

    $.ajax({
        url: base_url + "event_wall/get_poll",
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content"),
        },
        data: {
            eventId: eventId,
            eventPostId: eventPostId,
        },
        success: function (data) {
            console.log("AJAX Response:", data);
            if (Array.isArray(data) && data.length > 0) {
                const pollContainer = document.querySelector(
                    ".post-card-poll-wrp"
                );
                if (!pollContainer) {
                    console.error("Poll container not found.");
                    return;
                }

                // Find the specific poll data by pollId
                const pollInfo = data.find((poll) => poll.poll_id === pollId);
                if (pollInfo) {
                    // Check if the poll has expired
                    if (pollInfo.is_expired) {
                        // Display the expiration message
                        const errorMessage = document.getElementById(
                            `errorMessage-${pollId}`
                        );
                        if (errorMessage) {
                            errorMessage.textContent =
                                "This poll has expired. No votes here.";
                            errorMessage.style.display = "block"; // Show the error message
                        }

                        // Disable all buttons in the poll
                        pollContainer
                            .querySelectorAll(".poll-click-wrp .option-button")
                            .forEach((button) => {
                                button.disabled = true;
                                button.style.cursor = "not-allowed";
                            });
                        return; // Exit early since poll is expired
                    }

                    // Update total votes and remaining time
                    pollContainer.querySelector(
                        "h5"
                    ).innerHTML = `${pollInfo.total_poll_vote} Votes <span>${pollInfo.poll_duration_left} left</span>`;

                    // Ensure poll options are an array
                    if (Array.isArray(pollInfo.poll_options)) {
                        pollInfo.poll_options.forEach((option) => {
                            console.log(`Checking for option ID: ${option.id}`);
                            const optionElement = document.querySelector(
                                `.poll-click-wrp[data-option-id="${option.id}"]`
                            );
                            if (optionElement) {
                                optionElement.querySelector(
                                    ".option-button"
                                ).innerHTML = `${option.option} <span>${option.total_vote_percentage}</span>`;
                                optionElement.querySelector(
                                    ".poll-click-progress"
                                ).style.width = option.total_vote_percentage;
                            } else {
                                console.warn(
                                    `Option element for ID ${option.id} not found. Check the structure of the HTML or the timing of this code.`
                                );
                            }
                        });
                    } else {
                        console.error(
                            "poll_options is not defined or is not an array"
                        );
                    }
                } else {
                    console.warn(
                        "Poll data for the specified poll ID not found."
                    );
                }
            } else {
                console.error("poll_data is not defined or is empty");
            }
        },
        error: function (xhr, status, error) {
            console.error("AJAX Error:", xhr.responseText || error);
        },
    });
}

$(document).ready(function () {
    let optionCounter = 2;
    // Add new option
    $("#addOptionBtn").on("click", function () {
        optionCounter++;
        const newOptionHtml = $("#AddHtml").html();
        const $newOption = $(newOptionHtml);
        $newOption.find(".option-number").text(optionCounter);
        $("#options-container").append($newOption);
    });
    // Remove an option
    $(document).on("click", ".remove", function () {
        $(this).closest(".form-group").remove();
        optionCounter = 0;
        $("#options-container .form-group").each(function () {
            optionCounter++;
            $(this)
                .find("label")
                .text("Option " + optionCounter + ":");
        });
    });
});

// Function to close the modal

$(document).ready(function () {
    // Function to update character count
    function updateCharCount(inputField) {
        const maxLength = 140;
        const charCount = $(inputField).val().length;

        // Update the span element with current character count
        $(inputField)
            .closest(".mb-3")
            .find(".char-count")
            .text(`${charCount}/${maxLength}`);

        // Disable the input field if the maximum length is reached
        if (charCount >= maxLength) {
            $(inputField).val($(inputField).val().substring(0, maxLength));
            charCount = maxLength; // Adjust count after trimming
        }
        // } else {
        //     $(inputField).prop('disabled', false);
        // }
    }

    // Function to validate form fields
    function validateForm() {
        let hasError = false;

        // Clear previous errors
        $("#question_error").text('');
        $("#duration_error").text('');

        // Validate question and duration
        const question = $("input[name='question']").val().trim();
        const duration = $("select[name='duration']").val();

        if (question === "") {
            $("#question_error").text("Question is required.");
            hasError = true;
        }

        if (duration === "") {
            $("#duration_error").text("Please select a duration.");
            hasError = true;
        }

        // Validate options
        $("input[name='options[]']").each(function (index) {
            const val = $(this).val().trim();
            const inputWrapper = $(this).closest(".option_new");
            // $(".option-error").remove();
            // Remove any existing error
            inputWrapper.find(".option-error").remove();

            const optionIndex = index + 1;

            if (val === "") {
                inputWrapper.append(
                    `<div class='option-error text-danger mt-1' style="font-size: 12px">Option ${optionIndex} is required.</div>`
                );
                hasError = true;
            }
        });



        return !hasError;
    }



    // Apply the maxlength limit and validate form on input load
    $("input.form-control").each(function () {
        $(this).attr("maxlength", 140); // Set maxlength for input fields
        updateCharCount(this); // Initialize character count
    });

    // // Initial form validation
    // validateForm();

    // Update character count on input change
    $("#pollForm").on("input", "input.form-control", function () {
        updateCharCount(this); // Update char count
        //validateForm(); // Revalidate the form
    });

    // Update form validation on select change
    $("#pollForm").on("change", "select", function () {
        //validateForm();
    });

    // Add new poll option dynamically
    $(".option-add-btn").on("click", function () {
        const pollOptionsContainer = $(".poll-options");
        const optionCount = pollOptionsContainer.children().length + 1;
        console.log(optionCount);
        const newOption = $(`
            <div class="mb-3 option-poll option_new">
                <label class="form-label d-flex align-items-center justify-content-between">
                    <p>Option <span class="option-number">${optionCount}</span>*</p>
                    <span class="char-count">0/140</span>
                </label>
                <div class="position-relative">
                    <input type="text" class="form-control poll-option-input" name="options[]" required>
                    <span class="input-option-delete">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M14 3.98665C11.78 3.76665 9.54667 3.65332 7.32 3.65332C6 3.65332 4.68 3.71999 3.36 3.85332L2 3.98665" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M5.66699 3.31334L5.81366 2.44001C5.92033 1.80668 6.00033 1.33334 7.12699 1.33334H8.87366C10.0003 1.33334 10.087 1.83334 10.187 2.44668L10.3337 3.31334" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M12.5669 6.09332L12.1336 12.8067C12.0603 13.8533 12.0003 14.6667 10.1403 14.6667H5.86026C4.00026 14.6667 3.94026 13.8533 3.86693 12.8067L3.43359 6.09332" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M6.88672 11H9.10672" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M6.33301 8.33334H9.66634" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                </div>
            </div>
        `);

        pollOptionsContainer.append(newOption);

        // Bind delete functionality
        newOption.find(".input-option-delete").on("click", function () {
            newOption.remove();
            $(".option-error").remove();
            $("#question_error").text('');
            $("#duration_error").text('');
            renumberOptions(); // Call function to renumber options after deletion
        });


    });

    // Function to renumber options correctly after deletion
    function renumberOptions() {
        $(".poll-options .option-poll").each(function (index) {
            $(this)
                .find(".option-number")
                .text(index + 3);
            $(this).find(".char-count").text("0/140"); // Reset char count
        });
    }
    $("#postContent").keypress(function (event) {
        if (event.which === 13 && !event.shiftKey) {
            event.preventDefault(); // Prevents new line in textarea

            let postContent = $("#postContent").val().trim(); // Get content and remove spaces

            if (postContent.length > 0) {
                // Check if content exists
                if ($("#textform").length) {
                    // Check if form exists
                    $("#textform").submit(); // Submit the form
                } else {
                    console.log("Form not found!"); // Debugging purpose
                }
            } else {
                console.log("Post content is empty! Form not submitted.");
            }
        }
    });



    // Submit form on button click
    $(document).on("click", ".create_post_btn", function () {
        var $this = $(this);

        // Prevent multiple clicks
        if ($this.prop("disabled")) {
            return;
        }

        var pollForm = $("#pollForm");
        var photoForm = $("#photoForm");
        var postContent = $(".post_message").val().trim();


        if (pollForm.is(":visible") && pollForm.length > 0) {
            document.getElementById("pollContent").value = postContent;

            if (!validateForm()) return;

            $this
                .html('<div class="s-loader"><div></div><div></div><div></div><div></div></div>')
                .prop("disabled", true);

            pollForm.submit();
        }
        else if (photoForm.is(":visible") && photoForm.length > 0) {
            var photoInput = document.getElementById("fileInput");
            let imagePreview = document.getElementById("imagePreview");

            let photoPostType = document.getElementById("photoPostType");

            // // Check if no photo is uploaded AND no content is entered
            // if (
            //     (!photoInput || photoInput.files.length === 0) &&
            //     imagePreview.children.length === 0

            // ) {
            //     toastr.error(
            //         "Please upload a photo or enter some content for the photo post."
            //     );
            //     return;
            // }
            const dataTransfer = new DataTransfer();
            console.log(storedFiles);
            storedFiles.forEach(file => dataTransfer.items.add(file));

            // Create a new file input element and append to form
            const newInput = document.createElement("input");
            newInput.type = "file";
            newInput.name = "files[]";
            newInput.multiple = true;
            newInput.files = dataTransfer.files;
            newInput.style.display = "none";

            photoForm.append(newInput);



            let hasImages = imagePreview && imagePreview.children ? imagePreview.children.length > 0 : false;


            let hasUploadedPhotos = photoInput && photoInput.files ? photoInput.files.length > 0 : false;


            if (!hasUploadedPhotos && !hasImages && postContent === "") {
                toastr.error("Please upload a photo or enter some content for the post.");
                return; // Prevent form submission
            }


            if (photoPostType) {
                if ((photoInput && photoInput.files.length > 0) || (imagePreview && imagePreview.children.length > 0)) {
                    photoPostType.value = 1;
                } else {
                    photoPostType.value = 0;
                }
            } else {
                console.error("photoPostType element not found!");
            }


            // Show loader inside the button and disable it
            $this
                .html(
                    '<div class="s-loader"><div></div><div></div><div></div><div></div></div>'
                )
                .prop("disabled", true);

            photoForm.submit();
        } else {
            toastr.error("Please fill all required fields before submitting.");
        }
    });
    // Live validation for Question
    $(document).on("input", "input[name='question']", function () {
        const val = $(this).val().trim();
        if (val !== "") {
            $("#question_error").text("");
        } else {
            $("#question_error").text("Question is required.");
        }
    });

    // Live validation for Duration
    $(document).on("change", "select[name='duration']", function () {
        const val = $(this).val();
        if (val !== "") {
            $("#duration_error").text("");
        } else {
            $("#duration_error").text("Please select a duration.");
        }
    });

    // Live validation for each Option
    $(document).on("input", "input[name='options[]']", function () {
        const $input = $(this);
        const val = $input.val().trim();

        // Remove error if exists
        $input.next(".option-error").remove();

        // Add error if empty again
        if (val === "") {
            $input.after("<div class='option-error text-danger mt-1'>This option is required.</div>");
        }
    });


    $(document).on("click", "#send_post_msg", function (e) {
        e.preventDefault(); // Prevents new line in textarea

        var postContent = $("#postContent").val().trim(); // Get content and remove spaces
        if (postContent === "") {
            toastr.error("Please enter a text");
            return;
        }
        if (postContent.length > 0) {
            // Check if content exists
            if ($("#textform").length) {
                // Check if form exists
                $("#textform").submit(); // Submit the form
            } else {
                console.log("Form not found!"); // Debugging purpose
            }
        } else {
            console.log("Post content is empty! Form not submitted.");
        }
    });
});

// Wait for the entire page to load
// window.onload = function () {
//     // Hide the loader
//     openstoryModal(); // Open the modal after the page loads
// };
$("#photos_click").on("click", function () {
    $(".create-post-upload-img-inner").removeClass("d-none");
    $(".isNewPost").val('0');

    $(".create-post-head-upload-btn").addClass("d-none");
    $("#create-photo-btn").trigger("click");
});
$("#poll_click").on("click", function () {
    $(".isNewPost").val('0');
    $("#create-poll-btn").trigger("click");
});

$(".posts-card-like-btn").on("click", function () {
    const icon = this.querySelector("i");
    icon?.classList?.toggle("fa-regular");
    icon?.classList?.toggle("fa-solid");
});

$(".show-btn-comment").click(function () {
    let event_p_id = $(this).attr("event_p_id");
    $(".show_" + event_p_id).toggleClass("d-none");
    console.log("click");
});

$(".show-comment-reply-btn").click(function () {
    $(this).parent().find(".reply-on-comment").toggleClass("d-none");
    let currunt = $(this).html().toLowerCase().trim();
    console.log(currunt);
    if (currunt == "show reply") {
        $(this).html("Hide reply");
    } else {
        $(this).html("Show reply");
    }
});

$(document).ready(function () {
    // Handle Hide/Mute/Report Button Click
    $(".postControlButton").on("click", function () {
        var muteIcon = $(this).find("#muteIcon");
        var unmuteIcon = $(this).find("#unmuteIcon");

        // Retrieve necessary data attributes
        var $button = $(this);
        var eventId = $(this).data("event-id");
        var postId = $(this).data("event-post-id");
        var postControl = $(this).data("post-control"); // hide_post, unhide_post, mute, unmute, report

        // AJAX request
        $.ajax({
            url: base_url + "event_wall/postControl", // Adjust the endpoint URL as needed
            type: "POST",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            data: {
                event_id: eventId,
                event_post_id: postId,
                post_control: postControl,
            },
            success: function (response) {
                if (response.status === 1) {
                    if (response.type == "hide_post") {
                        // Find and hide the post using the postId
                        $('.hidden_post[data-post-id="' + postId + '"]').hide();
                        $(
                            '.hidden_post_poll[data-post-id="' + postId + '"]'
                        ).hide();
                    } else if (response.type == "mute") {
                        // Set button for unmuting
                        $button.data("post-control", "unmute");
                        // $button.text("Unmute");

                        // Toggle icon visibility
                        $button.find("#muteIcon").hide(); // Hide mute icon
                        $button.find("#unmuteIcon").show(); // Show unmute icon
                        $button.find(".unmuteClass").show();
                        $button.find(".muteClass").hide();
                    } else if (response.type === "unmute") {
                        // Set button for muting
                        $button.data("post-control", "mute");
                        // $button.text("Mute");

                        // Toggle icon visibility
                        $button.find("#muteIcon").show(); // Show mute icon
                        $button.find(".muteClass").show(); // Show mute icon
                        $button.find("#unmuteIcon").hide(); // Hide unmute icon
                        $button.find(".unmuteClass").hide(); // Hide unmute icon
                    }

                    toastr.success(response.message);
                } else {
                    alert("Something went wrong. Please try again.");
                }
            },
            error: function (xhr, status, error) {
                console.error("Error:", error);
                alert("Failed to perform the action. Please try again later.");
            },
        });
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
        $this
            .html(
                '<div class="s-loader"><div></div><div></div><div></div><div></div></div>'
            )
            .prop("disabled", true);

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
        var media_id = $("#media_id").val();

        var eventId = $("#submitreport").data("event-id");
        var postId = $("#submitreport").data("post-id");
        var violationDetails = $("#violation-textbox").val();

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

    $(document).on("click", "#deletePostButton", function () {
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
    $(document).on("click", ".editPostBtn", function () {
        $(".isNewPost").val('1');
        var eventPostId = $(this).data("event-post-id");
        var eventId = $(this).data("event-id");

        $.ajax({
            url: base_url + "event_wall/fetchPostWall",
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            contentType: "application/json",
            data: JSON.stringify({
                event_id: eventId,
                event_post_id: eventPostId,
            }),
            success: function (response) {
                if (response.status === "success" && response.data.length > 0) {
                    let postData = response.data[0]; // Get the first post data

                    // Set the post content
                    $(".post_message").val(postData.post_message);
                    $(".PostId").val(postData.id);
                    $(".poll_post_id").val(postData.id);
                    // Set hidden input values
                    // Set the radio button selection


                    let savedVisibility = postData.post_privacy  // Default to "1" if undefined

                    // Uncheck all radio buttons first
                    $('input[name="post_privacy"]').prop("checked", false);

                    // Check the saved visibility radio button and trigger change event
                    $('input[name="post_privacy"][value="' + savedVisibility + '"]')
                        .prop("checked", true)
                        .trigger("change");

                    // Update the saved settings display based on post_privacy
                    let privacyText = "";
                    switch (postData.post_privacy) {
                        case "1":
                            privacyText = "Everyone";
                            break;
                        case "2":
                            privacyText = "RSVP’d - Yes";
                            break;
                        case "3":
                            privacyText = "RSVP’d - No";
                            break;
                        case "4":
                            privacyText = "RSVP’d - No Reply";
                            break;

                    }


                    // Update the display with the selected option
                    $("#savedSettingsDisplay").html(`
    <h4>${privacyText} <i class="fa-solid fa-angle-down"></i></h4>
`);


                    // Set the checkbox based on the value (assuming 1 = checked, 0 = unchecked)
                    $('input[name="commenton"]').prop(
                        "checked",
                        postData.comment_on_off == 1
                    );

                    if (postData.post_type == "0") {
                        $(".create-post-upload-img-wrp").remove();



                    }
                    if (postData.post_type == "1") {
                        $("#create-photo-btn").trigger("click");
                        let mediaWrapper = $("#imagePreview");
                        let uploadImgInner = $(".create-post-upload-img-inner");
                        console.log(uploadImgInner);
                        const uploadHeadButton = $(
                            ".create-post-head-upload-btn"
                        );

                        mediaWrapper.empty(); // Clear old images

                        if (postData.mediaData.length > 0) {
                            let colClass =
                                postData.mediaData.length === 1
                                    ? "col-12"
                                    : "col-6";
                            postData.mediaData.forEach((media) => {
                                let mediaElement = ""; // Initialize an empty variable

                                if (media.type === "image") {
                                    // If it's an image
                                    mediaElement = `
                                                <div class="${colClass}" style="position: relative;" id="media-${media.id}" >
                                                    <input type="hidden" name="media-ids[]" value="${media.id}" id="media-ids" />
                                                    <span class="uploded-delete-icon delete_img_edit" data-id="${media.id}">
                                                        <svg viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M14 3.98665C11.78 3.76665 9.54667 3.65332 7.32 3.65332C6 3.65332 4.68 3.71999 3.36 3.85332L2 3.98665" stroke="#0F172A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                            <path d="M5.6665 3.31331L5.81317 2.43998C5.91984 1.80665 5.99984 1.33331 7.1265 1.33331H8.87317C9.99984 1.33331
                                                                10.0865 1.83331 10.1865 2.44665L10.3332 3.31331" stroke="#0F172A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                            <path d="M12.5664 6.09332L12.1331 12.8067C12.0598 13.8533 11.9998 14.6667 10.1398 14.6667H5.85977C3.99977
                                                                14.6667 3.93977 13.8533 3.86644 12.8067L3.43311 6.09332" stroke="#0F172A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                            <path d="M6.88672 11H9.10672" stroke="#0F172A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                            <path d="M6.3335 8.33331H9.66683" stroke="#0F172A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                        </svg>
                                                    </span>
                                                    <img src="${media.post_media}" class="preview-image">
                                                </div>
                                            `;
                                } else if (media.type === "video") {
                                    // If it's a video
                                    mediaElement = `
                                                <div class="${colClass}" style="position: relative;" id="media-${media.id}" >
                                                    <input type="hidden" name="media-ids[]" value="${media.id}" id="media-ids" />
                                                    <span class="uploded-delete-icon delete_img_edit" data-id="${media.id}">
                                                        <svg viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M14 3.98665C11.78 3.76665 9.54667 3.65332 7.32 3.65332C6 3.65332 4.68 3.71999 3.36 3.85332L2 3.98665" stroke="#0F172A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                            <path d="M5.6665 3.31331L5.81317 2.43998C5.91984 1.80665 5.99984 1.33331 7.1265 1.33331H8.87317C9.99984 1.33331
                                                                10.0865 1.83331 10.1865 2.44665L10.3332 3.31331" stroke="#0F172A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                            <path d="M12.5664 6.09332L12.1331 12.8067C12.0598 13.8533 11.9998 14.6667 10.1398 14.6667H5.85977C3.99977
                                                                14.6667 3.93977 13.8533 3.86644 12.8067L3.43311 6.09332" stroke="#0F172A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                            <path d="M6.88672 11H9.10672" stroke="#0F172A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                            <path d="M6.3335 8.33331H9.66683" stroke="#0F172A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                        </svg>
                                                    </span>
                                                    <video controls class="preview-video">
                                                        <source src="${media.post_media}" type="video/mp4">
                                                        Your browser does not support the video tag.
                                                    </video>
                                                </div>
                                            `;
                                }

                                mediaWrapper.append(mediaElement);
                            });

                            // Use event delegation to handle dynamic elements
                            $("#imagePreview").on("click", ".uploded-delete-icon", function () {
                                let mediaId = $(this).data("id"); // Get media ID
                                console.log("Clicked Media ID:", mediaId); // Debugging

                                if (!mediaId) {
                                    console.error("Media ID not found. Check if data-id is correctly set.");
                                    return;
                                }
                                if (imagePreview.children.length === 0) {
                                    alert();
                                    uploadImgInner.removeClass("d-none");


                                    // Clear file input value
                                    currentFileInput.value = "";
                                }
                                let targetDiv = $("#media-" + mediaId);
                                console.log("Target Div:", targetDiv); // Check if the div exists

                                if (targetDiv.length) {
                                    targetDiv.remove(); // Remove the specific div
                                } else {
                                    console.error("Target div not found for ID:", mediaId);
                                }

                            });



                            // Hide the upload section when images are uploaded
                            if (uploadImgInner.length > 0) {
                                console.log({ uploadImgInner });

                                uploadImgInner.addClass("d-none");
                                // uploadImgInner.hide();
                            } else {
                                console.error(
                                    "Element not found: .create-post-upload-img-inner"
                                );
                            }
                            uploadHeadButton.removeClass("d-none");
                        } else {
                            // Show the upload section when no images are present
                            uploadImgInner.removeClass("d-none");
                        }
                    }
                    if (postData.post_type == "2") {
                        $("#create-poll-btn").trigger("click"); // Open poll form modal

                        let pollData = postData.pollData;
                        const maxLength = 140;
                        if (pollData) {
                            $("#yourquestion").val(pollData.poll_question);
                            $("select[name='duration']").val(
                                pollData.total_poll_duration
                            );

                            let options = pollData.poll_options || []; // Get poll options

                            $("#yourquestion").on("input", function () {
                                const charCount = $(this).val().length;
                                $(this)
                                    .closest(".mb-3")
                                    .find(".char-count")
                                    .text(`${charCount}/${maxLength}`);
                            });


                            // Attach event listener for poll options input fields
                            $(".poll-options").on("input", "input[name='options[]']", function () {
                                updateCharCount(this);
                            });

                            // Trigger on page load to reflect any existing values
                            $(".poll_qus,.poll-options input[name='options[]']").each(function () {
                                updateCharCount(this);
                            });
                            // $(".poll-options input[name='options[]']").each(
                            //     (index, element) => {
                            //         if (options[index]) {
                            //             $(element).val(options[index].option); // Set existing options
                            //         }
                            //     }
                            // );
                            $(".poll-options input[name='options[]']").each((index, element) => {
                                if (options[index]) {
                                    $(element).val(options[index].option);
                                    const charCount = $(element).val().length;

                                    // Update the character count display
                                    $(element)
                                        .closest(".mb-3")
                                        .find(".char-count")
                                        .text(`${charCount}/${maxLength}`); // Update existing inputs
                                }
                            });

                            // Append only the missing options
                            let existingInputs = $(".poll-options input[name='options[]']").length;
                            console.log(existingInputs);
                            if (options.length > existingInputs) {
                                options.slice(existingInputs).forEach((option, index) => {
                                    let optionNumber = existingInputs + index + 1; // Ensure numbering is sequential

                                    $(".poll-options").append(`
                                        <div class="mb-3 option-poll">
                                            <label class="form-label d-flex align-items-center justify-content-between">
                                                <p>Option <span class="option-number">${optionNumber}</span>*</p>
                                                <span class="char-count">0/140</span>
                                            </label>
                                            <div class="position-relative">
                                                <input type="text" class="form-control poll-option-input" name="options[]" required value="${option.option}">
                                                  <input type="hidden" name="option-ids[]" value="${option.id}" id="option-ids" />
                                                <span class="input-option-delete delete-polll">
                                                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M14 3.98665C11.78 3.76665 9.54667 3.65332 7.32 3.65332C6 3.65332 4.68 3.71999 3.36 3.85332L2 3.98665" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                        <path d="M5.66699 3.31334L5.81366 2.44001C5.92033 1.80668 6.00033 1.33334 7.12699 1.33334H8.87366C10.0003 1.33334 10.087 1.83334 10.187 2.44668L10.3337 3.31334" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                        <path d="M12.5669 6.09332L12.1336 12.8067C12.0603 13.8533 12.0003 14.6667 10.1403 14.6667H5.86026C4.00026 14.6667 3.94026 13.8533 3.86693 12.8067L3.43359 6.09332" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                        <path d="M6.88672 11H9.10672" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                        <path d="M6.33301 8.33334H9.66634" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                    </svg>
                                                </span>
                                            </div>
                                        </div>
                                    `);
                                });
                                $(".poll_qus").on("input", function () {
                                    updateCharCount(this);
                                });

                                // Attach event listener for poll options input fields
                                $(".poll-options").on("input", "input[name='options[]']", function () {
                                    updateCharCount(this);
                                });

                                // Trigger on page load to reflect any existing values
                                $(".poll_qus,.poll-options input[name='options[]']").each(function () {
                                    updateCharCount(this);
                                });
                                // Delete option functionality
                                $(document).on("click", ".delete-polll", function () {
                                    $(this).closest(".option-poll").remove();
                                    renumberOptions();
                                });

                            }



                        }
                    }

                    // Set existing images if available

                    // Show the modal after setting the values
                    $("#creatpostmodal").modal("show");
                } else {
                    toastr.error("Post data not found.");
                }
            },
            error: function (xhr) {
                console.error(xhr.responseText);
                alert("An error occurred. Please try again.");
            },
        });
    });

    function renumberOptions() {
        $(".poll-options .option-poll").each(function (index) {
            $(this)
                .find(".option-number")
                .text(index + 1);
            $(this).find(".char-count").text("0/140"); // Reset char count
        });
    }
    function updateCharCount(inputField) {
        const maxLength = 140;
        const charCount = $(inputField).val().length;

        // Update the span element with current character count
        $(inputField)
            .closest(".mb-3")
            .find(".char-count")
            .text(`${charCount}/${maxLength}`);

        // Disable the input field if the maximum length is reached
        if (charCount >= maxLength) {
            $(inputField).val($(inputField).val().substring(0, maxLength));
            charCount = maxLength; // Adjust count after trimming
        }

    }
});

$(".modal").on("hidden.bs.modal", function () {
    $("#postContent").val("");
    $("#pollForm")[0].reset(); // Reset poll form
    $("#pollForm")[0].reset();

    $("#photoForm")[0].reset(); // Reset photo form
    $("#imagePreview").empty(); // Clear image preview
    $(".char-count").text("0/140"); // Reset char count
    $(".option-poll").remove();
    // $("#pollForm").find(".option-number").text(index + 1);
    $("#question_error").text('');
    $("#duration_error").text('');
    $(".option-error").remove();
    $(".model_comment").toggleClass("d-none");
    $(".post_comment").val('');
    // Add `d-none` class back to hide the div
    $(".create-post-upload-img-inner").addClass("d-none");
    storedFiles = [];
});

$(".btn-close").on("click", function () {
    $(".char-count").text("0/140"); // Reset char count
    $(".option-poll").remove();
    $("#question_error").text('');
    $("#duration_error").text('');
    $(".option-error").remove();
    $(".post_comment").text('');
    $(".post_comment").val('');
    $(".model_comment").toggleClass("d-none");
    storedFiles = [];
    // Add `d-none` class back to hide the div
});

$(".modal").on("shown.bs.modal", function () {
    // Remove `d-none` class to show the div
    // $(".create-post-upload-img-inner").removeClass("d-none");
});

$(document).on("click", ".select_all_post", function () {
    if ($(this).is(":checked")) {
        $(".wall_post").prop("checked", true);
    } else {
        $(".wall_post").prop("checked", false);
    }
});

$(document).on("click", ".wall_filter_reset", function () {
    $(".select_all_post").prop("checked", true);
    $(".wall_post").prop("checked", true);
    $(".view_wall_filter").attr("data-apply", "0");

    $.ajax({
        url: base_url + "event_wall/wallFilters",
        type: "POST",
        data: JSON.stringify({
            event_id: event_id,
            filters: selectedPostTypes,
            is_delete: "1",
        }),
        contentType: "application/json",
        headers: {
            Authorization: "Bearer YOUR_ACCESS_TOKEN",
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (response) {
            // console.log(response.view);
            window.location.reload();
            // $(".wall-post-content").html();
            // $(".wall-post-content").html(response.view);
            // $("#home_loader").css("display", "none");

            // $("#main-center-modal-filter").modal("hide");
        },
        error: function (xhr, status, error) {
            $("#home_loader").css("loader", "none");
            toastr.error("Something went wrong!");
            console.error(xhr.responseText);
        },
    });
});
$(document).on("click", ".view_wall_filter", function () {
    var applied = $("#is_filter_applied").val();
    console.log(applied);
    if (applied == "0") {
        $(".select_all_post").prop("checked", true);
        $(".wall_post").prop("checked", true);
    }
});

$(document).on("click", ".wall_apply_filter", function () {
    $(".view_wall_filter").attr("data-apply", "1");
    let selectedPostTypes = [];
    let event_id = $(this).data("event_id");
    $(".wall_post:checked").each(function () {
        selectedPostTypes.push($(this).data("post_type"));
    });

    $(".select_all_post:checked").each(function () {
        selectedPostTypes.push($(this).data("post_type"));
    });
    if (selectedPostTypes.length === 0) {
        toastr.error("Please select atleast one filter");
        return;
    }
    console.log(selectedPostTypes);
    $.ajax({
        url: base_url + "event_wall/wallFilters",
        type: "POST",
        data: JSON.stringify({
            event_id: event_id,
            filters: selectedPostTypes,
            is_delete: "0",
        }),
        contentType: "application/json",
        headers: {
            Authorization: "Bearer YOUR_ACCESS_TOKEN",
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (response) {
            // console.log(response.view);
            window.location.reload();
            // $(".wall-post-content").html();
            // $(".wall-post-content").html(response.view);
            // $("#home_loader").css("display", "none");

            // $("#main-center-modal-filter").modal("hide");
        },
        error: function (xhr, status, error) {
            $("#home_loader").css("loader", "none");
            toastr.error("Something went wrong!");
            console.error(xhr.responseText);
        },
    });
});

let longPressTimer;
let isLongPresss = false;

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
$(document).on("click", function (e) {
    if (!$(e.target).closest("#likeButton, #emojiDropdown").length) {
        $("#emojiDropdown").hide();
        $(".photos-likes-options-wrp").hide(); // Hide emoji picker when clicked outside
        // Hide emoji picker when clicked outside
    }
});

function generateProfileImage(firstname, lastname) {
    firstname = firstname ? String(firstname).trim() : "";
    lastname = lastname ? String(lastname).trim() : "";
    const firstInitial = firstname[0] ? firstname[0].toUpperCase() : "";
    const secondInitial = lastname[0] ? lastname[0].toUpperCase() : "";
    const initials = `${firstInitial}${secondInitial}`;
    const fontColor = `fontcolor${firstInitial}`;
    return `<h5 class="${fontColor} font_name">${initials || "NA"}</h5>`;
}
$(document).on("click", ".get_post_emoji_list", function () {
    var post_id = $(this).data("post");

    $("#nav-all-reaction ul").html("");

    $("#nav-all-reaction-tab").html("All 0");
    $(`#nav-heart-reaction ul`).html("");
    $("#heart-count").text("0");

    $(`#nav-thumb-reaction ul`).html("");
    $("#thumb-count").text("0");

    $(`#nav-smily-reaction ul`).html("");
    $("#smily-count").text("0");

    $(`#nav-eye-heart-reaction ul`).html("");
    $("#eye-heart-count").text("0");

    $(`#nav-clap-reaction ul`).html("");
    $("#clap-count").text("0");
    $("#nav-heart-reaction-tab").removeClass("active");
    $("#nav-thumb-reaction-tab").removeClass("active");
    $("#nav-smily-reaction-tab").removeClass("active");
    $("#nav-eye-heart-reaction-tab").removeClass("active");
    $("#nav-clap-reaction-tab").removeClass("active");

    $("#nav-heart-reaction").removeClass("active show");
    $("#nav-smily-reaction").removeClass("active show");
    $("#nav-thumb-reaction").removeClass("active show");
    $("#nav-eye-heart-reaction").removeClass("active show");
    $("#nav-clap-reaction-tab").removeClass("active show");

    $.ajax({
        url: base_url + "event_wall/get_reaction_post_list",
        type: "POST",
        data: JSON.stringify({
            post_id: post_id,
        }),
        contentType: "application/json",
        headers: {
            Authorization: "Bearer YOUR_ACCESS_TOKEN",
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (response) {
            console.log(response);
            if (response.status === 1) {
                let reactionDetail = response.reaction_detail;
                let reactionList = response.reaction_list;
                $("#nav-all-reaction-tab").html(
                    `All  ${reactionDetail.total_count}`
                );
                $.each(reactionList, function (reaction, users) {
                    let tabId = "";
                    let emoji_name = "";
                    let count = "";
                    if (reaction == "\\u{2764}") {
                        tabId = "nav-heart-reaction";
                        emoji_name = "heart-emoji";
                        count = "heart-count";
                    } else if (reaction == "\\u{1F44D}") {
                        tabId = "nav-thumb-reaction";
                        emoji_name = "thumb-icon";
                        count = "thumb-count";
                    } else if (reaction == "\\u{1F60A}") {
                        tabId = "nav-smily-reaction";
                        emoji_name = "smily-emoji";
                        count = "smily-count";
                    } else if (reaction == "\\u{1F60D}") {
                        tabId = "nav-eye-heart-reaction";
                        emoji_name = "eye-heart-emoji";
                        count = "eye-heart-count";
                    } else if (reaction == "\\u{1F44F}") {
                        tabId = "nav-clap-reaction";
                        emoji_name = "clap-icon";
                        count = "clap-count";
                    }

                    let reactionHtml = "";
                    let profile = "";
                    users.forEach((user) => {
                        if (user.profile == "") {
                            profile = generateProfileImage(
                                user.firstname,
                                user.lastname
                            );
                        } else {
                            profile = ` <img src="${user.profile}" alt="">`;
                        }
                        reactionHtml += `
                            <li class="reaction-info-wrp">
                                <div class="commented-user-head">
                                    <div class="commented-user-profile">
                                        <div class="commented-user-profile-img">
                                            ${profile}
                                        </div>
                                        <div class="commented-user-profile-content">
                                            <h3>${user.firstname} ${user.lastname}</h3>
                                            <p>${user.location}</p>
                                        </div>
                                    </div>
                                    <div class="posts-card-like-comment-right reaction-profile-reaction-img">
                                        <img src="${base_url}assets/front/img/${emoji_name}.png" alt="">
                                    </div>
                                </div>
                            </li>
                        `;
                    });

                    if (tabId) {
                        $(`#${tabId} ul`).append(reactionHtml);
                    }

                    $("#nav-all-reaction ul").append(reactionHtml);

                    let reactionCount =
                        reactionDetail.reaction_count[reaction] || 0;
                    $(`#${tabId}-tab`).html(
                        `<img src="${base_url}assets/front/img/${emoji_name}.png" alt=""> <span id="${count}">${reactionCount}</span>`
                    );
                });
                $("#nav-all-reaction-tab").addClass("active");
                $("#nav-all-reaction").addClass("active show");
                $("#reaction-modal").modal("show");
            }
        },
        error: function (xhr, status, error) {
            $("#home_loader").css("loader", "none");
            toastr.error("Something went wrong!");
            console.error(xhr.responseText);
        },
    });
});

// $(document).on('click', function (e) {
//     if (!$(e.target).closest('.photo-card-head-right').length) {
//         $('.photos-likes-options-wrp').hide(); // Hide emoji picker when clicked outside
//     }
// });
// function getEmojiUnicode(emoji) {
//     switch (emoji) {
//         case '❤️':
//             return '\u{2764}';  // Heart
//         case '😍':
//             return '\u{1F60D}';  // Smiling face with heart-eyes
//         case '👍':
//             return '\u{1F44D}';  // Thumbs up
//         case '😂':
//             return '\u{1F602}';  // Face with tears of joy
//         case '😢':
//             return '\u{1F622}';  // Crying face
//         default:
//             return emoji;  // Return as is if not found
//     }
// }

// const longPressDelay = 3000; // 3 seconds for long press
// let pressTimer;
// let isLongPress = false;
$(document).ready(function () {
    const visibilityOptions = {
        1: "Everyone",
        2: "RSVP’d - Yes",
        3: "RSVP’d - No",
        4: "RSVP’d - No Reply",
    };

    function loadSettings() {
        console.log("Loading settings..."); // Debugging
        let savedVisibility = "1";
        let savedAllowComments = "1";

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
    $("#creatpostmodal").on("show.bs.modal", function () {
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
});

// $(document).ready(function () {
//     let userActive = true;
//     let inactivityTimeout;
//     let refreshTimeout;
//     let activeTime = null;
//     let inactiveTime = null;

//     function userIsActive() {
//         if (!userActive) {
//             activeTime = new Date().toLocaleTimeString();
//             console.log("User became active at:", activeTime);
//         }

//         userActive = true;
//         clearTimeout(inactivityTimeout);
//         clearTimeout(refreshTimeout);

//         inactivityTimeout = setTimeout(userIsInactive, 5000);
//         refreshTimeout = setTimeout(() => {
//             console.log("User inactive for 5+ minutes, refreshing page...");
//             location.reload();
//         },300000);
//     }

//     function userIsInactive() {
//         userActive = false;
//         inactiveTime = new Date().toLocaleTimeString();
//         console.log("User became inactive at:", inactiveTime);
//     }

//     document.addEventListener("mousemove", userIsActive);
//     document.addEventListener("keydown", userIsActive);
//     document.addEventListener("touchstart", userIsActive); // For mobile devices

//     inactivityTimeout = setTimeout(userIsInactive, 5000);
//     refreshTimeout = setTimeout(() => {
//         console.log("User inactive for 5+ minutes, refreshing page...");
//         location.reload();
//     }, 300000);
// });


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
// $(document).on("click", ".open_photo_model", function (e) {
//     console.log("Mouse up or leave detected");

//     $("#detail-photo-modal").modal("show");
//     $(".model_comment").addClass("d-none");
//     const commentInput = $("#post_comment");
//     commentInput.val("");

//     // Fetch the post ID from the data attribute
//     var login_user_id = $("#login_user_id").val();
//     // alert(login_user_id);
//     const postId = $(this).data("post-id");
//     const eventId = $(this).data("event-id");
//     console.log(postId, postId);

//     const rawData = $(this).data("image-src"); // Get raw data
//     console.log("Raw Data:", rawData); // Debug the raw data
//     const swiperWrapper = $("#media_post");
//     swiperWrapper.empty();
//     if (rawData && rawData.length > 0) {
//         rawData.forEach((media) => {
//             let mediaElement = "";

//             if (media.match(/\.(mp4|webm|ogg)$/i)) {
//                 // If it's a video, use <video> tag
//                 mediaElement = `
//                 <div class="swiper-slide">
//                     <div class="posts-card-show-post-img">
//                         <video controls>
//                             <source src="${media}" type="video/mp4" muted>
//                             Your browser does not support the video tag.
//                         </video>
//                     </div>
//                 </div>
//             `;
//             } else {
//                 // Otherwise, treat it as an image
//                 mediaElement = `
//                 <div class="swiper-slide">
//                     <div class="posts-card-show-post-img">
//                         <img src="${media}" alt="Media"  />
//                     </div>
//                 </div>
//             `;
//             }

//             swiperWrapper.append(mediaElement);
//         });
//     }
//     // swiper.destroy(true, true);
//     // console.log(rawData.length);

//     if (rawData.length > 1) {
//         swiperWrapper.removeClass("hideswipe");

//         // swiper.destroy(true, true);
//         document.getElementsByClassName(
//             "swiper-button-next"
//         )[0].style.display = "flex";
//         document.getElementsByClassName(
//             "swiper-button-prev"
//         )[0].style.display = "flex";
//         swiper = new Swiper(".photo-detail-slider", {
//             slidesPerView: 1,
//             spaceBetween: 30,
//             navigation: {
//                 nextEl: ".swiper-button-next",
//                 prevEl: ".swiper-button-prev",
//             },
//         });
//         $(".swiper-button-next").show();
//         $(".swiper-button-prev").show();
//     } else {
//         swiperWrapper.addClass("hideswipe");
//         // swiper.destroy(true, true);
//         document.getElementsByClassName(
//             "swiper-button-next"
//         )[0].style.display = "none";
//         document.getElementsByClassName(
//             "swiper-button-prev"
//         )[0].style.display = "none";
//         swiper = new Swiper(".photo-detail-slider", {
//             slidesPerView: 1,
//             spaceBetween: 30,

//             loop: false, // 🔹 Ensure looping is disabled
//         });
//         $(".swiper-button-next").hide();
//         $(".swiper-button-prev").hide();

//     }
//     //let parentId = null;  // Default to null, assuming no parent

//     // if ($('.commented-user-wrp').length > 0) {
//     //     // If this is a reply button, get the parent ID from the closest .commented-user-wrp element
//     //     parentId = $('.commented-user-wrp').data('parent-id');  // Assuming `data-parent-id` holds the parent_id
//     // }
//     // console.log(parentId);
//     var url;

//     url = base_url + "event_photo/fetch-photo-details";
//     $("#host_display").text("");

//     $("#host_display").hide();
//     $.ajax({
//         url: url, // Update with your server-side endpoint
//         type: "POST", // Use GET or POST depending on your API
//         headers: {
//             "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
//         },
//         data: { id: postId, event_id: eventId },
//         success: function (response) {
//             const dataArray = response.data; // This is an array
//             console.log(response);

//             if (Array.isArray(dataArray) && dataArray.length > 0) {
//                 const data = dataArray[0]; // Access the first object in the array

//                 // Profile Image

//                 const profileImage =
//                     data.profile ||
//                     generateProfileImage(data.firstname, data.lastname);
//                 console.log("Profile Image URL:", profileImage);
//                 // Check if profileImage is an image URL or HTML content
//                 if (
//                     profileImage.startsWith("http") ||
//                     profileImage.startsWith("data:image")
//                 ) {
//                     // If it's a valid image URL, set it as the src of the image tag
//                     $(".posts-card-head-left-img").html(
//                         `<img src="${profileImage}" alt="Profile Image">`
//                     );
//                 } else {
//                     // If it's a placeholder (HTML content), insert it directly inside the div
//                     $(".posts-card-head-left-img").html(profileImage);
//                 }

//                 const post = {
//                     id: postId,
//                     reactionList: data.reactionList,
//                     self_reaction: data.self_reaction,
//                     total_likes: data.total_likes,
//                 };

//                 function generateProfileImage(firstname, lastname) {
//                     const firstInitial = firstname
//                         ? firstname[0].toUpperCase()
//                         : "";
//                     const secondInitial = lastname
//                         ? lastname[0].toUpperCase()
//                         : "";
//                     const initials = `${firstInitial}${secondInitial}`;
//                     const fontColor = `fontcolor${firstInitial}`;

//                     // Return initials inside an h5 tag with dynamic styling
//                     return `<h5 class="${fontColor} font_name">${initials}</h5>`;
//                 }
//                 // Host Label Condition
//                 if (data.is_host == "1") {
//                     const host = `${data.is_host}`;
//                     $("#host_display").show();
//                     $("#host_display").text("Host");
//                     $("#host_display").addClass("host");
//                 }
//                 if (data.is_co_host == "1") {
//                     $("#host_display").show();
//                     const co_host = `${data.is_co_host}`;
//                     $("#host_display").text("co_host");
//                     $("#host_display").addClass("host");
//                 }
//                 // const login_user_id = $("#login_user_id").val();
//                 // $("#report_btn").show();

//                 // if (data.user_id == login_user_id) {
//                 //     $("#report_btn").hide();
//                 // }
//                 let messageLink = $(".message-link");
//                 let encrypted_id = data.encrypted_id;
//                 if (encrypted_id) {
//                     let messageRoute = `/messages/${encrypted_id}`;
//                     messageLink.attr("href", messageRoute);
//                 }

//                 if (data.user_id == login_user_id) {
//                     $(".message-link").addClass('d-none');


//                 }
//                 if (data.user_id != login_user_id) {
//                     $(".message-link").removeClass('d-none');


//                 }

//                 $(".likeModel")
//                     .data("event-id", data.event_id)
//                     .data("event-post-id", data.id);
//                 // Name
//                 const fullName = `${data.firstname} ${data.lastname}`;
//                 $("#post_name").text(fullName);

//                 // Location
//                 const location =
//                     data.location.trim() !== "" ? data.location : "";
//                 $("#location").text(location);

//                 // Post Message
//                 $("#post_message").text(data.post_message);
//                 $("#post_time_details").text(data.post_time);

//                 const reactionVal = data.reactionList.forEach((that) => { });

//                 // $("#likeCount").text(data.total_likes + " Likes");
//                 // Add 'Likes' after the number
//                 $("#comments").text(data.total_comments + " Comments");

//                 console.log("Self Reaction:", data.self_reaction); // Debugging
//                 console.log(typeof data.self_reaction); // Output: string

//                 var reaction_store = data.self_reaction.trim();

//                 console.log(reaction_store);

//                 let reactionImageHtml = $("#likeButtonModel");
//                 console.log(reactionImageHtml);

//                 if (reactionIcons[reaction_store]) {
//                     console.log(reactionIcons[reaction_store]);

//                     reactionImageHtml = `<img src="${reactionIcons[reaction_store]}" alt="">`;
//                 } else {
//                     // If reaction_store is not found, show a default icon
//                     reactionImageHtml = `<i class="fa-regular fa-heart"></i>`;
//                 }
//                 $(`#likeButtonModel`).html(reactionImageHtml);
//                 let reaction_list = response.reactionList;
//                 //    reaction_list.each(function () {

//                 //     $(`#reactionImage`).html(reactionIcons[reaction_store]);
//                 //    });

//                 document.getElementById("postCardEmoji").innerHTML =
//                     renderReactions(post);
//                 // Update the emoji list based on the reaction
//                 const reactionList = $(".post_model ul");

//                 reactionList.find("li").each(function () {
//                     const img = $(this).find("img");
//                     if (img.length) {
//                         const emojiSrc = img.attr("src");
//                         console.log("Reaction Store:", reaction_store);
//                         console.log("Emoji Src:", emojiSrc);

//                         // Define emojis with exact matching Unicode and image source
//                         const heartUnicode = "\u{2764}"; //
//                         const smileUnicode = "\u{1F60D}"; //
//                         const clapUnicode = "\u{1F44F}"; //

//                         $(this).removeClass("photo_emoji").show();

//                         // Hide and select the correct emoji based on the reaction_store
//                         if (
//                             reaction_store === heartUnicode &&
//                             emojiSrc.includes("heart-emoji.png")
//                         ) {
//                             console.log("Heart emoji photo_emoji");
//                             $(this).addClass("photo_emoji");
//                         } else if (
//                             reaction_store === smileUnicode &&
//                             emojiSrc.includes("smily-emoji.png")
//                         ) {
//                             console.log("Smile emoji photo_emoji");
//                             $(this).addClass("photo_emoji");
//                         } else if (
//                             reaction_store === clapUnicode &&
//                             emojiSrc.includes("clap-icon.png")
//                         ) {
//                             console.log("Clap emoji photo_emoji");
//                             $(this).addClass("photo_emoji");
//                         } else {
//                             $(this).hide(); // Hide non-matching emojis
//                             console.log("No matching emoji found");
//                         }
//                     } else {
//                         console.log("No img tag found in this li element.");
//                     }
//                 });

//                 // Make sure you update the reactions after filtering them
//                 updateReactions(data.reactionList);

//                 const commentsWrapper = $(
//                     ".posts-card-show-all-comments-inner ul"
//                 );
//                 commentsWrapper.empty(); // Clear existing comments

//                 if (
//                     data.latest_comment &&
//                     Array.isArray(data.latest_comment)
//                 ) {
//                     data.latest_comment.forEach((comment) => {
//                         let parentCommentId = comment.id;
//                         let displayName = comment.profile
//                             ? `<img src="${comment.profile}" alt="User Profile" class="profile-image">`
//                             : generatePlaceholderName(comment.username);

//                         commentsWrapper.append(`
//                         <li class="commented-user-wrp wall_replay_wrp" data-comment-id="${comment.id
//                             }">

//                             <div class="commented-user-head">
//                                 <div class="commented-user-profile">
//                                     <div class="commented-user-profile-img">
//                                     ${displayName}
//                                     </div>
//                                     <div class="commented-user-profile-content">
//                                         <h3>${comment.username || ""}</h3>
//                                         <p>${comment.location || ""}</p>
//                                     </div>
//                                 </div>
//                                 <div class="posts-card-like-comment-right">
//                                     <p>${comment.posttime || ""}</p>
//                                     <button class="posts-card-like-btn" id="CommentlikeButton" data-event-id="${eventId}" data-event-post-comment-id="${comment.id
//                             }" data-user-id="${login_user_id}">
//                             ${comment.is_like == 1
//                                 ? '<i class="fa-solid fa-heart"></i>'
//                                 : '<i class="fa-regular fa-heart"></i>'
//                             }
//                                     </button>
//                                 </div>
//                             </div>
//                             <div class="commented-user-content">
//                                 <p>${comment.comment || ""}</p>
//                             </div>
//                             <div class="commented-user-reply-wrp">
//                                 <div class="position-relative d-flex align-items-center gap-2">
//                                      <button class="posts-card-like-btn" id="CommentlikeButton" data-event-id="${eventId}" data-event-post-comment-id="${comment.id
//                             }" data-user-id="${login_user_id}">
//                                ${comment.is_like == 1
//                                 ? '<i class="fa-solid fa-heart"></i>'
//                                 : '<i class="fa-regular fa-heart"></i>'
//                             }
//                                 </button>
//                                     <p id="commentTotalLike_${comment.id
//                             }">${comment.comment_total_likes || 0
//                             }</p>
//                                 </div>
//                                 <button class="wall_model_replay" data-comment-id="${comment.id
//                             }">Reply</button>
//                             </div>
//                          <ul class="wall_comment_replay_append"></ul>
//                         </li>

//                     `);

//                         if (
//                             comment.comment_replies &&
//                             comment.comment_replies.length > 0
//                         ) {
//                             comment.comment_replies.forEach(function (
//                                 reply
//                             ) {
//                                 let displayName = reply.profile
//                                     ? `<img src="${reply.profile}" alt="User Profile" class="profile-image">`
//                                     : generatePlaceholderName(
//                                         reply.username
//                                     );
//                                 const replyHTML = `

//                                     <div class="commented-user-head">
//                                         <div class="commented-user-profile">
//                                             <div class="commented-user-profile-img">
//                                             ${displayName}
//                                             </div>
//                                             <div class="commented-user-profile-content">
//                                                 <h3>${reply.username}</h3>
//                                                 <p>${reply.location || ""}</p>
//                                             </div>
//                                         </div>
//                                         <div class="posts-card-like-comment-right">
//                                             <p>${reply.posttime || "Just now"}</p>
//                                             <button class="posts-card-like-btn">

//                                              ${reply.is_like == 1
//                                         ? '<i class="fa-solid fa-heart"></i>'
//                                         : '<i class="fa-regular fa-heart"></i>'
//                                     }</button>
//                                         </div>
//                                     </div>
//                                     <div class="commented-user-content">
//                                         <p>${reply.comment || "No content"}</p>
//                                     </div>
//                                     <div class="commented-user-reply-wrp">
//                                         <div class="position-relative d-flex align-items-center gap-2">
//                                             <button class="posts-card-like-btn">
//                                              ${reply.is_like == 1
//                                         ? '<i class="fa-solid fa-heart"></i>'
//                                         : '<i class="fa-regular fa-heart"></i>'
//                                     }</button>
//                                             <p>${reply.comment_total_likes || 0}</p>
//                                         </div>
//                                         <button class="wall_model_replay" data-comment-id="${reply.id
//                                     }">Reply</button>
//                                     </div>
//                                 `;
//                                 const li = document.createElement("li");
//                                 li.className = "wall_replay";
//                                 li.setAttribute(
//                                     "data-comment-id",
//                                     reply.id
//                                 );
//                                 li.innerHTML = replyHTML; // Convert HTML string to actual HTML

//                                 // Find all existing comments
//                                 let comments =
//                                     document.getElementsByClassName(
//                                         "wall_replay"
//                                     );
//                                 console.log(comments);
//                                 // Convert HTMLCollection to an array and find the target comment

//                                 const comment = Array.from(comments).find(
//                                     (el) =>
//                                         el.dataset.commentId ===
//                                         parentCommentId
//                                 );

//                                 if (comment) {
//                                     console.log("Found comment:", comment);

//                                     // Find the previous sibling (the comment before this one)
//                                     let previousComment =
//                                         comment.previousElementSibling;
//                                     if (!previousComment) {
//                                         $(comment).parent().prepend(li);
//                                     }

//                                     // Loop until we find the nearest previous <ul> with class "primary-comment-replies"
//                                     while (previousComment) {
//                                         let parentUl =
//                                             previousComment.closest(
//                                                 ".wall_comment_replay_append"
//                                             );
//                                         if (parentUl) {
//                                             console.log(
//                                                 "Found the ul:",
//                                                 parentUl
//                                             );
//                                             parentUl.prepend(li); // Append the new comment properly

//                                             // 🔥 Update the comments list to include the newly added <li>
//                                             comments =
//                                                 document.getElementsByClassName(
//                                                     "wall_replay"
//                                                 );

//                                             console.log(
//                                                 "Updated comments list:",
//                                                 comments
//                                             );
//                                             break;
//                                         }
//                                         previousComment =
//                                             previousComment.previousElementSibling;
//                                     }
//                                 } else {
//                                     let comments =
//                                         document.getElementsByClassName(
//                                             "wall_replay_wrp"
//                                         );
//                                     let comment = Array.from(comments).find(
//                                         (el) => {
//                                             console.log(
//                                                 el.dataset.commentId
//                                             );
//                                             console.log(parentCommentId);
//                                             //  el.dataset.commentId ===
//                                             // parentCommentId
//                                             if (
//                                                 el.dataset.commentId ==
//                                                 parentCommentId
//                                             ) {
//                                                 return el;
//                                             }
//                                         }
//                                     );
//                                     if (comment) {
//                                         console.log(comment);
//                                         const parentUl = $(".wall_comment_replay_append");
//                                         console.log(parentUl);
//                                         if (parentUl.length) {
//                                             console.log(
//                                                 "Found primary-comment-replies under commented-user-wrp, prepending the new comment."
//                                             );

//                                             parentUl.prepend($(li));


//                                             // Insert new comment as the first <li> under the current comment's <ul>
//                                             return;
//                                         }
//                                     }
//                                 }
//                             });
//                         }
//                     });
//                 }
//                 function generatePlaceholderName(username) {
//                     const nameParts = username.split(" ");
//                     const firstInitial =
//                         nameParts[0]?.[0]?.toUpperCase() || "";
//                     const secondInitial =
//                         nameParts[1]?.[0]?.toUpperCase() || "";
//                     const initials = `${firstInitial}${secondInitial}`;
//                     const fontColor = `fontcolor${firstInitial}`;
//                     // Return initials inside an h5 tag with dynamic styling
//                     return `<h5 class="${fontColor} font_name">${initials}</h5>`;
//                 }
//             } else {
//                 console.log("No data found in the array.");
//             }
//         },
//     });

//     function updateReactions(reactions) {
//         const emojiPaths = {
//             heart: "/assets/front/img/heart-emoji.png",
//             thumb: "/assets/front/img/thumb-icon.png",
//             smily: "/assets/front/img/smily-emoji.png",
//             "eye-heart": "/assets/front/img/eye-heart-emoji.png",
//             clap: "/assets/front/img/clap-icon.png",
//         };

//         const allReactionsList = $("#nav-all-reaction ul");
//         const heartReactionsList = $("#nav-heart-reaction ul");
//         const thumbReactionsList = $("#nav-thumb-reaction ul");
//         const smilyReactionsList = $("#nav-smily-reaction ul");
//         const eyeHeartReactionsList = $("#nav-eye-heart-reaction ul");
//         const clapReactionsList = $("#nav-clap-reaction ul");

//         const reactionCounts = {
//             heart: 0,
//             thumb: 0,
//             smily: 0,
//             "eye-heart": 0,
//             clap: 0,
//         };

//         // Clear all reaction lists
//         allReactionsList.empty();
//         heartReactionsList.empty();
//         thumbReactionsList.empty();
//         smilyReactionsList.empty();
//         eyeHeartReactionsList.empty();
//         clapReactionsList.empty();

//         reactions.forEach((reactionData) => {
//             let reactionType = "";
//             let emojiSrc = "";

//             // Extract user details from the reaction object
//             const { reaction, firstname, lastname, profile, location } =
//                 reactionData;
//             // Map each reaction to a type
//             switch (reaction) {
//                 case "\\u{2764}": // Heart
//                     reactionType = "heart";
//                     break;
//                 case "\\u{1F44D}": // Thumbs Up
//                     reactionType = "thumb";
//                     break;
//                 case "\\u{1F60A}": // Smiley
//                     reactionType = "smily";
//                     break;
//                 case "\\u{1F60D}": // Eye-Heart
//                     reactionType = "eye-heart";
//                     break;
//                 case "\\u{1F44F}": // Clap
//                     reactionType = "clap";
//                     break;
//                 default:
//                     console.warn(`Unknown reaction: ${reaction}`);
//                     return; // Skip unknown reactions
//             }

//             // Increment the reaction count
//             reactionCounts[reactionType]++;

//             // Get the emoji image source
//             emojiSrc = emojiPaths[reactionType];
//             const profileContent =
//                 profile && profile !== ""
//                     ? `<img src="${profile}" alt="">`
//                     : `<h5 class="fontcolor${firstname ? firstname[0].toUpperCase() : ""
//                     }">${firstname ? firstname[0].toUpperCase() : ""}${lastname ? lastname[0].toUpperCase() : ""
//                     }</h5>`;
//             // Create reaction list item
//             const reactionItem = `<li class="reaction-info-wrp">
//                                 <div class="commented-user-head">
//                                     <div class="commented-user-profile">
//                                         <div class="commented-user-profile-img">
//                                         ${profileContent}
//                                         </div>
//                                         <div class="commented-user-profile-content">
//                                               <h3>${firstname} ${lastname}</h3>


//                                         </div>
//                                     </div>
//                                     <div class="posts-card-like-comment-right reaction-profile-reaction-img">
//                                         <img src="${emojiSrc}" alt="">
//                                     </div>
//                                 </div>
//                               </li>`;

//             // Append to specific reaction list
//             if (reactionType === "heart") {
//                 heartReactionsList.append(reactionItem);
//             } else if (reactionType === "thumb") {
//                 thumbReactionsList.append(reactionItem);
//             } else if (reactionType === "smily") {
//                 smilyReactionsList.append(reactionItem);
//             } else if (reactionType === "eye-heart") {
//                 eyeHeartReactionsList.append(reactionItem);
//             } else if (reactionType === "clap") {
//                 clapReactionsList.append(reactionItem);
//             }

//             // Append the same item to "All Reactions" list
//             console.log("Appending to All Reactions:", reactionItem);
//             allReactionsList.append(reactionItem);
//         });

//         // Update the counts in the navigation tabs
//         const totalReactions = Object.values(reactionCounts).reduce(
//             (sum, count) => sum + count,
//             0
//         );
//         $("#nav-all-reaction-tab").html(`All ${totalReactions}`);
//         $("#nav-heart-reaction-tab").html(
//             `<img src="${emojiPaths["heart"]}" alt=""> ${reactionCounts.heart}`
//         );
//         $("#nav-thumb-reaction-tab").html(
//             `<img src="${emojiPaths["thumb"]}" alt=""> ${reactionCounts.thumb}`
//         );
//         $("#nav-smily-reaction-tab").html(
//             `<img src="${emojiPaths["smily"]}" alt=""> ${reactionCounts.smily}`
//         );
//         $("#nav-eye-heart-reaction-tab").html(
//             `<img src="${emojiPaths["eye-heart"]}" alt=""> ${reactionCounts["eye-heart"]}`
//         );
//         $("#nav-clap-reaction-tab").html(
//             `<img src="${emojiPaths["clap"]}" alt=""> ${reactionCounts.clap}`
//         );
//     }
// });
// $(".show-comments-btn").click(function () {
//     $(".model_comment").toggleClass("d-none");
//     $("#detail-photo-modal .modal-content").toggleClass("active")
// });
// $(".show-comment-reply-btn").click(function () {
//     $(".reply-on-comment").toggleClass("d-none");
// });
// $(document).on("click", "#likeButtonModel", function () {
//     console.log("asd");
//     setTimeout(function () {
//         $("#emojiDropdown1").show();
//         console.log("asd");
//     }, 1000);

//     $("#emojiDropdown1").css("display", "block");
//     console.log($("#emojiDropdown1"));
// });
// $(".posts-card-like-comment-right").each(function () {
//     const $container = $(this); // Get the current container
//     const $likeButton = $container.find(".posts-card-like-btn"); // Find the like button within the container
//     const $emojiDropdown = $container.find(".photos-likes-options-wrp"); // Find the emoji dropdown within the container


//     $emojiDropdown.on("click", ".emoji", function () {
//         const emoji = $(this).data("emoji");

//         // Remove the heart icon and set emoji inside the button
//         $likeButton.html(`<img src='${reactionIcons[emoji]}'/>`); // Show selected emoji inside button

//         $emojiDropdown.hide(); // Hide emoji dropdown after selection
//     });


// });
// $(document).on("click", "#emojiDropdown1 .model_emoji", function () {
//     const selectedEmoji = $(this).data("emoji");
//     const button = $(this).closest(".emoji_set").find("#likeButtonModel");
//     const emojiDisplay = button.find("#show_comment_emoji");
//     const eventId = button.data("event-id");
//     const eventPostId = button.data("event-post-id");
//     const button_main = $("#likeButton_" + eventPostId);
//     console.log(selectedEmoji);

//     // Replace heart icon with selected emoji
//     emojiDisplay.removeClass();
//     emojiDisplay.text(selectedEmoji);

//     // AJAX call to update emoji reaction

//     console.log(eventId, eventPostId);
//     console.log(eventPostId);
//     $.ajax({
//         url: base_url + "event_photo/userPostLikeDislike",
//         method: "POST",
//         headers: {
//             "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
//         },
//         contentType: "application/json",
//         data: JSON.stringify({
//             event_id: eventId,
//             event_post_id: eventPostId,
//             reaction: selectedEmoji,
//         }),
//         success: function (response) {
//             if (response.status === 1) {
//                 console.log(response.reactionList);

//                 // const post = {
//                 //     id: eventPostId,
//                 //     reactionList: response.reactionList,
//                 //     // self_reaction: response.self_reaction,
//                 //     total_likes: response.count
//                 // };
//                 // document.getElementById("postCardEmoji").innerHTML = renderReactions(post);
//                 let reactionImageHtml = "";
//                 if (response.is_reaction == "1") {
//                     // ✅ User has liked the post, update the reaction image
//                     console.log("Like given, updating reaction image...");
//                     if (reactionIcons[selectedEmoji]) {
//                         console.log(reactionIcons[selectedEmoji]);
//                         reactionImageHtml = `<img src="${reactionIcons[selectedEmoji]}" alt="Reaction Emoji">`;
//                     }
//                     button.addClass("liked"); // Add liked class
//                 } else {
//                     // ✅ User has removed like, set the first reaction from response
//                     console.log(
//                         "Like removed , updating first available reaction..."
//                     );
//                     if (response.reactionList.length > 0) {
//                         let firstReaction =
//                             response.reactionList[0].reaction; // ✅
//                         if (firstReaction.startsWith("\\u{")) {
//                             firstReaction = String.fromCodePoint(
//                                 parseInt(
//                                     firstReaction.replace(/\\u{|}/g, ""),
//                                     16
//                                 )
//                             );
//                         }
//                         if (reactionIcons[selectedEmoji]) {
//                             reactionImageHtml = `<img src="${reactionIcons[selectedEmoji]}" alt="Reaction Emoji">`;
//                         } else {
//                             console.log({ firstReaction });
//                             console.log(reactionIcons[firstReaction]);
//                             //let reaction = "\u{2764}";
//                             reactionImageHtml = `<img src="${reactionIcons[selectedEmoji]}" alt="Reaction Emoji">`;
//                         }
//                     }
//                     button.removeClass("liked"); // Remove liked class
//                     button.html(
//                         '<i class="fa-regular fa-heart" id="show_Emoji"></i>'
//                     );
//                 }

//                 button_main.html(reactionImageHtml);
//                 $(`#reactionImage_model_${eventPostId}`).html(
//                     reactionImageHtml
//                 );
//                 $(`#reactionImage_${eventPostId}`).html(reactionImageHtml);

//                 $(`#like_${eventPostId}`).text(`${response.count} Likes`);
//                 $(`#likeCount_${eventPostId}`).text(
//                     `${response.count} Likes`
//                 );
//                 updateReactions(response.reactionList);
//             } else {
//                 alert(response.message);
//             }
//         },
//         error: function (xhr) {
//             console.error(xhr.responseText);
//             alert("An error occurred. Please try again.");
//         },
//     });
//     function updateReactions(reactions) {
//         const emojiPaths = {
//             heart: "/assets/front/img/heart-emoji.png",
//             thumb: "/assets/front/img/thumb-icon.png",
//             smily: "/assets/front/img/smily-emoji.png",
//             "eye-heart": "/assets/front/img/eye-heart-emoji.png",
//             clap: "/assets/front/img/clap-icon.png",
//         };

//         const allReactionsList = $("#nav-all-reaction ul");
//         const heartReactionsList = $("#nav-heart-reaction ul");
//         const thumbReactionsList = $("#nav-thumb-reaction ul");
//         const smilyReactionsList = $("#nav-smily-reaction ul");
//         const eyeHeartReactionsList = $("#nav-eye-heart-reaction ul");
//         const clapReactionsList = $("#nav-clap-reaction ul");

//         const reactionCounts = {
//             heart: 0,
//             thumb: 0,
//             smily: 0,
//             "eye-heart": 0,
//             clap: 0,
//         };

//         // Clear all reaction lists
//         allReactionsList.empty();
//         heartReactionsList.empty();
//         thumbReactionsList.empty();
//         smilyReactionsList.empty();
//         eyeHeartReactionsList.empty();
//         clapReactionsList.empty();

//         reactions.forEach((reactionData) => {
//             let reactionType = "";
//             let emojiSrc = "";

//             // Extract user details from the reaction object
//             const { reaction, firstname, lastname, profile, location } =
//                 reactionData;
//             // Map each reaction to a type
//             switch (reaction) {
//                 case "\\u{2764}": // Heart
//                     reactionType = "heart";
//                     break;
//                 case "\\u{1F44D}": // Thumbs Up
//                     reactionType = "thumb";
//                     break;
//                 case "\\u{1F60A}": // Smiley
//                     reactionType = "smily";
//                     break;
//                 case "\\u{1F60D}": // Eye-Heart
//                     reactionType = "eye-heart";
//                     break;
//                 case "\\u{1F44F}": // Clap
//                     reactionType = "clap";
//                     break;
//                 default:
//                     console.warn(`Unknown reaction: ${reaction}`);
//                     return; // Skip unknown reactions
//             }

//             // Increment the reaction count
//             reactionCounts[reactionType]++;

//             // Get the emoji image source
//             emojiSrc = emojiPaths[reactionType];
//             const profileContent =
//                 profile && profile !== ""
//                     ? `<img src="${profile}" alt="">`
//                     : `<h5 class="fontcolor${firstname ? firstname[0].toUpperCase() : ""
//                     }">${firstname ? firstname[0].toUpperCase() : ""}${lastname ? lastname[0].toUpperCase() : ""
//                     }</h5>`;
//             // Create reaction list item
//             const reactionItem = `<li class="reaction-info-wrp">
//                                     <div class="commented-user-head">
//                                         <div class="commented-user-profile">
//                                             <div class="commented-user-profile-img">
//                                             ${profileContent}
//                                             </div>
//                                             <div class="commented-user-profile-content">
//                                                   <h3>${firstname} ${lastname}</h3>


//                                             </div>
//                                         </div>
//                                         <div class="posts-card-like-comment-right reaction-profile-reaction-img">
//                                             <img src="${emojiSrc}" alt="">
//                                         </div>
//                                     </div>
//                                   </li>`;

//             // Append to specific reaction list
//             if (reactionType === "heart") {
//                 heartReactionsList.append(reactionItem);
//             } else if (reactionType === "thumb") {
//                 thumbReactionsList.append(reactionItem);
//             } else if (reactionType === "smily") {
//                 smilyReactionsList.append(reactionItem);
//             } else if (reactionType === "eye-heart") {
//                 eyeHeartReactionsList.append(reactionItem);
//             } else if (reactionType === "clap") {
//                 clapReactionsList.append(reactionItem);
//             }

//             // Append the same item to "All Reactions" list
//             console.log("Appending to All Reactions:", reactionItem);
//             allReactionsList.append(reactionItem);
//         });

//         // Update the counts in the navigation tabs
//         const totalReactions = Object.values(reactionCounts).reduce(
//             (sum, count) => sum + count,
//             0
//         );
//         $("#nav-all-reaction-tab").html(`All ${totalReactions}`);
//         $("#nav-heart-reaction-tab").html(
//             `<img src="${emojiPaths["heart"]}" alt=""> ${reactionCounts.heart}`
//         );
//         $("#nav-thumb-reaction-tab").html(
//             `<img src="${emojiPaths["thumb"]}" alt=""> ${reactionCounts.thumb}`
//         );
//         $("#nav-smily-reaction-tab").html(
//             `<img src="${emojiPaths["smily"]}" alt=""> ${reactionCounts.smily}`
//         );
//         $("#nav-eye-heart-reaction-tab").html(
//             `<img src="${emojiPaths["eye-heart"]}" alt=""> ${reactionCounts["eye-heart"]}`
//         );
//         $("#nav-clap-reaction-tab").html(
//             `<img src="${emojiPaths["clap"]}" alt=""> ${reactionCounts.clap}`
//         );
//     }
//     // Hide emoji picker
//     $(this).closest("#emojiDropdown1").hide();

//     // Define visibility options

//     // Dynamically set the hidden values in the forms
//     $("form").on("submit", function () {
//         // Fetch the visibility and commenting status to update the form's hidden inputs before submission
//         const visibility =
//             $('input[name="post_privacy"]:checked').val() || "1"; // Default to Everyone if null
//         const allowComments = $("#allowComments").is(":checked")
//             ? "1"
//             : "0";

//         // Dynamically update hidden inputs in the respective forms
//         $("#hiddenVisibility").val(visibility);
//         $("#hiddenAllowComments").val(allowComments);
//     });
// });


