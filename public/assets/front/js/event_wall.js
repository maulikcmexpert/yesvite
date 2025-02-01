let selectedFiles = null; // To store selected files

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
    previewContainer.innerHTML = ''; // Clear previous preview

    if (files.length > 0) {
        Array.from(files).forEach(file => {
            const fileUrl = URL.createObjectURL(file);
            let mediaElement;

            if (file.type.startsWith('image/')) {
                mediaElement = document.createElement('img');
            } else if (file.type.startsWith('video/')) {
                mediaElement = document.createElement('video');
                mediaElement.controls = true; // Add video controls for videos
            }

            if (mediaElement) {
                mediaElement.src = fileUrl;
                mediaElement.classList.add('story-preview'); // Add a class for styling if needed
                previewContainer.appendChild(mediaElement);
            }
        });

        // Show the Upload button after preview
        uploadButton.style.display = 'flex';
        const previewModal = document.getElementById(`previewModel-${userId}`); // Modal itself
        if (previewModal && previewContainer) {
            previewModal.style.display = 'flex'; // Show the modal
            previewContainer.style.display = 'flex'; // Ensure story display is visible
        }
    } else {
        console.log("No file selected.");
    }
}

function closePreviewModal(userId) {
    const previewContainer = document.getElementById(`preview-${userId}`);
    const previewModal = document.getElementById(`previewModel-${userId}`); // Correct ID
    if (previewModal && previewContainer) {
        previewModal.style.display = 'none'; // Close the modal
        previewContainer.style.display = 'none'; // Hide the preview container
    }
}

// Step 2: Upload the selected files on button click
function uploadStoryImage(eventId, userId) {
    if (!selectedFiles) {
        alert("No files selected for upload.");
        return;
    }

    const formData = new FormData();
    Array.from(selectedFiles).forEach(file => {
        formData.append('story[]', file);
    });

    formData.append('eventId', eventId);
    formData.append('_token', $('meta[name="csrf-token"]').attr("content"));

    $.ajax({
        url: base_url + "event_wall/createStory",
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        success: function (data) {
            console.log(data);
            if (data.status === 1) {
                console.log("Upload successful, adding pink border.");

                const profilePic = document.getElementById(`profile-pic-${userId}`);
                const profileContainer = document.getElementById(`profile-container-${userId}`);
                const listItem = profilePic ? profilePic.closest('.wall-main-story-item') : null;
                console.log(listItem);
                index = 0;
                // if (profilePic) {
                //     profilePic.style.borderColor = 'red'; // Set border color to red during the upload
                //     profilePic.classList.add('pink-border'); // Add pink border during upload
                // } else {
                //     console.error("Profile picture not found.");
                // }

                if (profileContainer) {
                    profileContainer.style.display = 'block';
                }

                if (listItem) {
                    console.log(1);

                    listItem.classList.add('new-story'); // Add the 'new-story' class to the story item
                } else {
                    console.error("List item not found.");
                }

                closePreviewModal(userId);
                // showStories(eventId, userId, true);
                fetchStories(eventId, userId, true, 'image')
                // Set the border color to gray after viewing the story
                setTimeout(() => {
                    // if (profilePic) {
                    //     profilePic.style.borderColor = 'gray'; // Change the border color to gray after viewing
                    //     profilePic.classList.remove('pink-border'); // Remove the pink border class
                    // }
                    if (listItem) {
                        listItem.classList.remove('new-story'); // Optionally, remove the 'new-story' class
                    }
                }, 5000); // Adjust the timeout duration as needed
            }
        },
        error: function (error) {
            console.error("Error uploading story:", error);
            alert("Image uploading failed.");
        }
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
    if (document.readyState !== 'complete') {
        console.log('Page is still loading. Waiting to open the story modal.');
        window.addEventListener('load', () => {
            openStoryModal(storyDisplay, storyModal, eventId, userId, isNewUpload);
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
        storyModal.style.display = 'flex';
        storyDisplay.style.display = 'flex';
    }
    const storyType = 'other';

    // Ensure that the storyId passed corresponds to the correct user's ID
    fetchStories(eventId, storyId, isNewUpload, storyType);

    // Add the gray border class to the profile picture after viewing
    const profilePic = document.querySelector(`.story-profile-pic[onclick="AllUserStory(${eventId}, '${storyId}')"]`);

    if (profilePic) {
        // Add the gray border class to the profile picture
        profilePic.classList.add('viewed-story');
        profilePic.classList.remove('story-unseen');
    }
}


async function fetchStories(eventId, userId, isNewUpload, storyType) {
    try {
        const response = await fetch(`${base_url}event_wall/fetch-user-stories/${eventId}?storyType=${storyType}`);
        const data = await response.json();
        console.log(isNewUpload);

        if (data.status !== 1) {
            throw new Error('Failed to fetch stories: ' + data.message);
        }
        console.log(userId);

        let storyDisplay = document.getElementById(`story-display-${userId}`);
        if (!storyDisplay) {
            console.error(`Element with ID 'story-display-${userId}' not found.`);
            // return;
        }
        console.log(storyDisplay); // Log the storyDisplay element
        console.log(storyDisplay.querySelector('.story-content')); // Log the
        const storyContent = storyDisplay.querySelector('.story-content');
        const progressBarContainer = storyDisplay.querySelector('.progress-bar-container');

        // Clear previous content
        storyContent.innerHTML = '';
        progressBarContainer.innerHTML = '';
        index = 0;
        const storyElements = [];
        const storyDurations = [];
        const storyPostTimes = [];

        // Process 'owner_stories'
        if (Array.isArray(data.data.owner_stories)) {
            data.data.owner_stories.forEach(story => {
                if (story.user_id === userId) {
                    story.story.forEach(storyData => {
                        const mediaElement = document.createElement(storyData.type === 'video' ? 'video' : 'img');
                        mediaElement.src = storyData.storyurl;
                        mediaElement.classList.add('story-preview');

                        if (storyData.type === 'video') {
                            mediaElement.controls = false;
                            mediaElement.autoplay = false;
                            mediaElement.muted = true;
                        }

                        const storyItemContainer = document.createElement('div');
                        storyItemContainer.classList.add('story-item');
                        storyItemContainer.dataset.storyId = storyData.id;

                        // Add post time
                        if (storyData.post_time) {
                            const postTimeElement = document.createElement('p');
                            postTimeElement.classList.add('post-time');
                            postTimeElement.textContent = storyData.post_time;
                            storyItemContainer.appendChild(postTimeElement);
                            storyPostTimes.push(storyData.post_time); // Store post time
                        }

                        storyItemContainer.appendChild(mediaElement);
                        storyContent.appendChild(storyItemContainer);
                        storyElements.push({ element: mediaElement, type: storyData.type });
                        storyDurations.push(storyData.type === 'video' ? 0 : 5000);
                    });
                }
            });
        }

        // Process 'other_stories'
        if (Array.isArray(data.data.other_stories)) {
            data.data.other_stories.forEach(story => {
                if (story.user_id !== userId) {
                    story.story.forEach(storyData => {
                        const mediaElement = document.createElement(storyData.type === 'video' ? 'video' : 'img');
                        mediaElement.src = storyData.storyurl;
                        mediaElement.classList.add('story-preview');

                        if (storyData.type === 'video') {
                            mediaElement.controls = false;
                            mediaElement.autoplay = false;
                            mediaElement.muted = true;
                        }

                        storyElements.push({ element: mediaElement, type: storyData.type });
                        storyDurations.push(storyData.type === 'video' ? 0 : 5000);
                        storyPostTimes.push(storyData.post_time); // Store post time
                    });
                }
            });
        }

        if (!storyElements.length) {
            console.warn('No stories available for the specified user.');
            const storyModal = document.getElementById(`storyModal-${userId}`);
            const storyDisplay = document.getElementById(`story-display-${userId}`);
            storyModal.style.display = 'none'; // Open the modal
            storyDisplay.style.display = 'none';

        }

        // Pass data to the story display function
        displayStoriesWithProgressBars(storyElements, storyContent, progressBarContainer, userId, storyDurations, isNewUpload, storyPostTimes);

    } catch (error) {
        console.error('Error fetching stories:', error);
    }
}

function displayStoriesWithProgressBars(storyElements, storyContent, progressBarContainer, userId, storyDurations, isNewUpload = false, storyPostTimes) {
    console.log(storyElements);
    console.log(isNewUpload);
    if (!storyElements || storyElements.length === 0) {
        console.error('No stories available to display.');
        return;
    }

    let index = 0; // Start with the first story
    // let index = 0; // Start with the first story

    let currentTimeout = null;
    let currentVideoElement = null;

    function resetProgressBars() {
        console.log("reset index" + index);

        progressBarContainer.innerHTML = '';
        //$('.progress-bar-container').html('');
        storyElements.forEach(() => {
            const progressBar = document.createElement('div');
            progressBar.classList.add('progress-bar');
            const progress = document.createElement('div');
            progress.classList.add('progress');
            progress.style.width = '0%';
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
            currentVideoElement.ontimeupdate = null;
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
            progressBarContainer.innerHTML = '';
            storyContent.innerHTML = '';
            const storyModal = document.getElementById(`storyModal-${userId}`);
            const displayModel = document.getElementById(`story-display-${userId}`);
            //   console.log('TEST');

            if (storyModal && displayModel) {
                console.log(storyModal);
                console.log(displayModel);

                storyModal.style.display = 'none';
                displayModel.style.display = 'none';
                $('.progress-bar-container').html('');
                storyContent = {};


            }
            index = 0;
            return;
        }
        // console.log(storyContent.innerHTML);
        console.log(storyElements.length);
        storyContent.innerHTML = ''; // Clear the content for the new story

        // Add post time display
        const postTimeElement = document.createElement('p');
        postTimeElement.classList.add('post-time');
        postTimeElement.textContent = storyPostTimes[currentIndex];
        storyContent.appendChild(postTimeElement);

        Array.from(progressBarContainer.children).forEach((bar, idx) => {
            const progress = bar.firstChild;
            if (idx < currentIndex) {
                progress.style.width = '100%';
            } else {
                progress.style.width = '0%';
            }
        });

        const { element, type } = storyElements[currentIndex];
        storyContent.appendChild(element);
        // console.log('progess'+ currentIndex);
        const progress = progressBarContainer.children[currentIndex].firstChild;

        if (type === 'image') {
            let progressWidth = 0;
            const displayDuration = storyDurations[currentIndex];
            const increment = 100 / (displayDuration / 10);

            function updateImageProgress() {
                if (progressWidth < 100) {
                    // alert(1);
                    progressWidth += increment;
                    progress.style.width = progressWidth + '%';
                    currentTimeout = setTimeout(updateImageProgress, 10);
                } else {
                    index++;
                    console.log("progess index" + index);
                    showStory(index);
                }
            }

            updateImageProgress();
        }
        else if (type === 'video') {
            currentVideoElement = element;
            element.play();

            element.ontimeupdate = function () {
                if (element.duration > 0) {
                    const progressPercentage = (element.currentTime / element.duration) * 100;
                    progress.style.width = progressPercentage + '%';
                }
            };

            element.onended = function () {
                showStory(index);
                console.log("window index" + index);
                index++;
            };
        }
    }

    var counter = 0;
    storyContent.addEventListener('click', (event) => {
        counter++;


        const contentWidth = storyContent.offsetWidth;
        const clickPosition = event.clientX - storyContent.getBoundingClientRect().left;
        // console.log({counter});

        if (clickPosition < contentWidth / 2 && index > 0) {
            index--; // Go back to the previous story

        } else if (clickPosition >= contentWidth / 2) {
            index++; // Advance to the next story
            // console.log("click index"  + index );

        }
        storyContent.innerHTML = '';
        console.log(storyContent);
        showStory(index);
    });


    function initializeStories() {

        resetProgressBars();
        showStory(index);
        index = 0;

    }
    $(document).on('click', '.modal-close', function () {
        var id = $(this).data('id');
        // alert(id);
        closeModal(id);

    });
    function closeModal(userId) {
        resetCurrentStory(); // Clear
        resetProgressBars();

        const modal = document.getElementById(`storyModal-${userId}`);
        const storyDisplay = document.getElementById(`story-display-${userId}`);
        const progressBarContainer = document.querySelector('.progress-bar-container');
        storyContent = {};
        modal.style.display = 'none';
        storyDisplay.style.display = 'none';
        $('.progress-bar-container').html('');
        index = 0;

    }
    // Reset previous state when reopening the modal
    const storyModal = document.getElementById(`storyModal-${userId}`);
    console.log("model index" + index);
    if (storyModal) {
        storyModal.addEventListener('show', () => {
            resetProgressBars(); // Reset progress bars

            showStory(index); // Start from the last viewed story or first
        });
    }

    initializeStories();
}





// Function to open the poll modal
function openPollModal() {
    document.getElementById('pollModal').style.display = 'block';
}
function openStoryModal(storyDisplay, storyModal, eventId, userId, isNewUpload) {

    if (storyDisplay && storyModal) {
        storyModal.style.display = 'flex';
        storyDisplay.style.display = 'flex';
    }

    const storyType = 'owner';

    // Ensure that the userId passed is correct
    fetchStories(eventId, userId, isNewUpload, storyType);
}
// Function to close the poll modal
function closePollModal() {
    document.getElementById('pollModal').style.display = 'none';

}

// Close the modal when clicking outside of it
window.onclick = function (event) {
    const modal = document.getElementById('pollModal');
    if (event.target === modal) {
        modal.style.display = 'none';
    }
}
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




$('.option-button').on('click', function () {

    const pollId = $(this).data('poll-id');  // Gets poll ID (e.g., 456)
    const optionId = $(this).data('option-id');  // Gets option ID (e.g., 123)

    // Call the vote function
    voteOnPoll(pollId, optionId);
});



const voteOnPoll = (pollId, optionId) => {
    const url = base_url + "event_wall/votePoll";
    const eventPostId = document.getElementById('event_post_id').value;
    const eventId = document.getElementById('event_id').value;

    $.ajax({
        url: url,
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
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
            eventPostId: eventPostId
        },
        success: function (data) {
            console.log('AJAX Response:', data);
            if (data.success) {
                // Update the poll UI with the new data received
                updatePollUI(data, pollId);
                //  alert('Vote submitted/updated successfully!');
            } else {
                alert(data.message || 'Failed to submit vote.');
            }
        },
        error: function (xhr, status, error) {
            console.error('AJAX Error:', xhr.responseText || error);
        }
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
    const eventPostId = document.getElementById('event_post_id').value;
    const eventId = document.getElementById('event_id').value;

    console.log('Event ID:', eventId);
    console.log('Event Post ID:', eventPostId);

    $.ajax({
        url: base_url + "event_wall/get_poll",
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        },
        data: {
            eventId: eventId,
            eventPostId: eventPostId
        },
        success: function (data) {
            console.log('AJAX Response:', data);
            if (Array.isArray(data) && data.length > 0) {
                const pollContainer = document.querySelector('.post-card-poll-wrp');
                if (!pollContainer) {
                    console.error('Poll container not found.');
                    return;
                }

                // Find the specific poll data by pollId
                const pollInfo = data.find(poll => poll.poll_id === pollId);
                if (pollInfo) {
                    // Check if the poll has expired
                    if (pollInfo.is_expired) {
                        // Display the expiration message
                        const errorMessage = document.getElementById(`errorMessage-${pollId}`);
                        if (errorMessage) {
                            errorMessage.textContent = 'This poll has expired. No votes here.';
                            errorMessage.style.display = 'block'; // Show the error message
                        }

                        // Disable all buttons in the poll
                        pollContainer.querySelectorAll('.poll-click-wrp .option-button').forEach(button => {
                            button.disabled = true;
                            button.style.cursor = 'not-allowed';
                        });
                        return; // Exit early since poll is expired
                    }

                    // Update total votes and remaining time
                    pollContainer.querySelector('h5').innerHTML = `${pollInfo.total_poll_vote} Votes <span>${pollInfo.poll_duration_left} left</span>`;

                    // Ensure poll options are an array
                    if (Array.isArray(pollInfo.poll_options)) {
                        pollInfo.poll_options.forEach(option => {
                            console.log(`Checking for option ID: ${option.id}`);
                            const optionElement = document.querySelector(`.poll-click-wrp[data-option-id="${option.id}"]`);
                            if (optionElement) {
                                optionElement.querySelector('.option-button').innerHTML = `${option.option} <span>${option.total_vote_percentage}</span>`;
                                optionElement.querySelector('.poll-click-progress').style.width = option.total_vote_percentage;
                            } else {
                                console.warn(`Option element for ID ${option.id} not found. Check the structure of the HTML or the timing of this code.`);
                            }
                        });
                    } else {
                        console.error('poll_options is not defined or is not an array');
                    }
                } else {
                    console.warn('Poll data for the specified poll ID not found.');
                }
            } else {
                console.error('poll_data is not defined or is empty');
            }
        },
        error: function (xhr, status, error) {
            console.error('AJAX Error:', xhr.responseText || error);
        }
    });
}






$(document).ready(function () {
    let optionCounter = 2;
    // Add new option
    $('#addOptionBtn').on('click', function () {
        optionCounter++;
        const newOptionHtml = $('#AddHtml').html();
        const $newOption = $(newOptionHtml);
        $newOption.find('.option-number').text(optionCounter);
        $('#options-container').append($newOption);
    });
    // Remove an option
    $(document).on('click', '.remove', function () {
        $(this).closest('.form-group').remove();
        optionCounter = 0;
        $('#options-container .form-group').each(function () {
            optionCounter++;
            $(this).find('label').text('Option ' + optionCounter + ':');
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
        let isValid = true;
        $('#pollForm input[required], #pollForm select[required]').each(function () {
            if ($.trim($(this).val()) === '') {
                isValid = false;
                return false; // Break loop
            }
        });
        $('.create_post_btn').prop('disabled', !isValid);
    }

    // Apply the maxlength limit and validate form on input load
    $("input.form-control").each(function () {
        $(this).attr("maxlength", 140); // Set maxlength for input fields
        updateCharCount(this); // Initialize character count
    });

    // // Initial form validation
    // validateForm();

    // Update character count on input change
    $('#pollForm').on('input', 'input.form-control', function () {
        updateCharCount(this); // Update char count
        validateForm(); // Revalidate the form
    });

    // Update form validation on select change
    $('#pollForm').on('change', 'select', function () {
        validateForm();
    });

    // Add new poll option dynamically
    $(".option-add-btn").on("click", function () {
        const pollOptionsContainer = $(".poll-options");
        const optionCount = pollOptionsContainer.children().length + 1;

        const newOption = $(`
            <div class="mb-3 poll-option">
                <label class="form-label d-flex align-items-center justify-content-between">
                    Option <span class="option-number">${optionCount}</span>*
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
            renumberOptions(); // Call function to renumber options after deletion

        });

        validateForm();
    });

    // Function to renumber options correctly after deletion
    function renumberOptions() {
        $(".poll-options .poll-option").each(function (index) {
            $(this).find(".option-number").text(index + 1);
        });
    }


    // Submit form on button click
    $(document).on('click', '.create_post_btn', function () {
        var $this = $(this); // Cache the button

        // Prevent multiple clicks

        // if ($this.prop('disabled')) {
        //     return;
        // }
        // Check if the poll form exists and is valid
        var pollForm = $('#pollForm');
        var photoForm = $('#photoForm');
        var textForm = $('#textform');
        var postContent = document.getElementById('postContent').value.trim();
        // Fallback to empty string if #postContent does not exist
        console.log('Poll Form:', pollForm.length > 0 ? 'Exists' : 'Does not exist');
        console.log('Photo Form:', photoForm.length > 0 ? 'Exists' : 'Does not exist');
        console.log('Text Form:', textForm.length > 0 ? 'Exists' : 'Does not exist');
        console.log('Post Content:', postContent);
        // If a poll form exists and is visible, submit it
        if (pollForm.is(':visible') && pollForm.length > 0 &&  pollForm !== '') {
            // if (postContent === '') {
            //     alert('Please enter some content for the poll.');
            //     return;
            // }
            // Set the value of the hidden input in the poll form
            document.getElementById('pollContent').value = postContent;
            pollForm.submit();
        }
        // If a photo form exists and is visible, submit it
        else if (photoForm.is(':visible') && photoForm.length > 0) {
            // Check if there's a valid photo (adjust this to your actual field for photo upload)
            var photoInput = document.getElementById('fileInput'); // Assuming there's a file input for photo
            if (photoInput && photoInput.files.length === 0) {
                toastr.error('Please upload a photo for the photo post.');
                return
            }


            // Set the value of the hidden input in the photo form
            document.getElementById('photoContent').value = postContent;
            document.getElementById('photoPostType').value = 1;

            // Submit the form
            $this.prop('disabled', true)
            photoForm.submit();
        }
        // If neither form exists, check for a plain text post
        else if (textForm.length > 0 && postContent !== '') {
            if (postContent === '') {
                alert('Please enter some content for the photo post.');
                return;
            }

            document.getElementById('photoPostType').value = 0; //
            $this.prop('disabled', true)
            textForm.submit();
        }
        // If no valid content is provided, show an alert
        else {
            alert('Please fill all required fields before submitting.');
        }
    });


});

// Wait for the entire page to load
// window.onload = function () {
//     // Hide the loader
//     openstoryModal(); // Open the modal after the page loads
// };
$(document).ready(function () {
    // Define visibility options
    const visibilityOptions = {
        1: "Everyone",
        2: "RSVP’d - Yes",
        3: "RSVP’d - No",
        4: "RSVP’d - No Reply",
    };

    // Load saved settings or set defaults
    let savedVisibility = localStorage.getItem('post_privacys') || '1'; // Default: Everyone
    let savedAllowComments = localStorage.getItem('commenting_on_off') === '1'; // Convert to boolean

    // Ensure the default value is set if no saved value exists for comments
    if (savedAllowComments !== true) {
        savedAllowComments = '1'; // Default to true
        localStorage.setItem('commenting_on_off', savedAllowComments);
    }

    // Apply settings to the form
    const visibilityRadio = $('input[name="post_privacy"][value="' + savedVisibility + '"]');
    if (visibilityRadio.length > 0) {
        visibilityRadio.prop('checked', true);
    } else {
        // Fallback to default visibility if saved value is invalid
        savedVisibility = '1';
        $('input[name="post_privacy"][value="1"]').prop('checked', true);
    }

    $('#allowComments').prop('checked', savedAllowComments);

    // Update the hidden input fields dynamically
    $('.hiddenVisibility').val(savedVisibility);
    $('.hiddenAllowComments').val(savedAllowComments ? '1' : '0');

    // Update the display area to show the current saved visibility and commenting status
    const visibilityName = visibilityOptions[savedVisibility];
    $('#savedSettingsDisplay').html(`
        <h4>${visibilityName} <i class="fa-solid fa-angle-down"></i></h4>
        <p>${savedAllowComments === '1' ? "" : ""}</p>
    `);

    // Save Button Click Handler
    $('#saveSettings').on('click', function () {
        // Fetch selected visibility
        const visibility = $('input[name="post_privacy"]:checked').val() || '1'; // Default to Everyone if null
        // Fetch commenting status
        const allowComments = $('#allowComments').is(':checked') ? '1' : '0';

        // Save settings to localStorage
        localStorage.setItem('post_privacys', visibility);
        localStorage.setItem('commenting_on_off', allowComments);

        // Update the hidden input fields dynamically for all forms
        $('.hiddenVisibility').val(visibility);
        $('.hiddenAllowComments').val(allowComments);

        // Update display area
        const visibilityName = visibilityOptions[visibility];
        $('#savedSettingsDisplay').html(`
            <h4>${visibilityName} <i class="fa-solid fa-angle-down"></i></h4>
            <p>${allowComments === '1' ? "" : ""}</p>
        `);

        console.log('Saved Settings:', { visibility, allowComments });
    });

    // Dynamically set the hidden values in the forms
    $('form').on('submit', function () {
        // Fetch the visibility and commenting status to update the form's hidden inputs before submission
        const visibility = $('input[name="post_privacy"]:checked').val() || '1'; // Default to Everyone if null
        const allowComments = $('#allowComments').is(':checked') ? '1' : '0';

        // Dynamically update hidden inputs in the respective forms
        $('#hiddenVisibility').val(visibility);
        $('#hiddenAllowComments').val(allowComments);
    });
});







$(".posts-card-like-btn").on("click", function () {
    const icon = this.querySelector('i');
    icon.classList.toggle('fa-regular');
    icon.classList.toggle('fa-solid');
});

$(".show-btn-comment").click(function () {
    let event_p_id = $(this).attr('event_p_id')
    $(".show_" + event_p_id).toggleClass("d-none");
});

$(".show-comment-reply-btn").click(function () {
    $(".reply-on-comment").toggleClass("d-none");
});


$(document).ready(function () {
    // Handle Hide/Mute/Report Button Click
    $('.postControlButton').on('click', function () {
        // Retrieve necessary data attributes
        var $button = $(this);
        var eventId = $(this).data('event-id');
        var postId = $(this).data('event-post-id');
        var postControl = $(this).data('post-control'); // hide_post, unhide_post, mute, unmute, report


        // AJAX request
        $.ajax({
            url: base_url + "event_wall/postControl", // Adjust the endpoint URL as needed
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                event_id: eventId,
                event_post_id: postId,
                post_control: postControl,

            },
            success: function (response) {
                if (response.status === 1) {
                    console.log(response.type)
                    if (response.type == 'hide_post') {
                        // Find and hide the post using the postId
                        $('.hidden_post[data-post-id="' + postId + '"]').hide();
                    }else if (response.type == "mute") {
                        // Set button for unmuting
                        $button.data('post-control', 'unmute');
                        $button.text("Unmute");

                        // Toggle icon visibility
                        $button.find('#muteIcon').hide();  // Hide mute icon
                        $button.find('#unmuteIcon').show(); // Show unmute icon
                    } else if (response.type === "unmute") {
                        // Set button for muting
                        $button.data('post-control', 'mute');
                        $button.text("Mute");

                        // Toggle icon visibility
                        $button.find('#muteIcon').show();  // Show mute icon
                        $button.find('#unmuteIcon').hide(); // Hide unmute icon
                    }



                    toastr.success(response.message);
                } else {
                    alert('Something went wrong. Please try again.');
                }
            },
            error: function (xhr, status, error) {
                console.error('Error:', error);
                alert('Failed to perform the action. Please try again later.');
            }
        });
    });


});
$(".modal").on("hidden.bs.modal", function(){
    $("#postContent").val('');
    $("#pollForm")[0].reset(); // Correct way to reset a form
    $("#photoForm").reset();
    $("#imagePreview").empty();

});


