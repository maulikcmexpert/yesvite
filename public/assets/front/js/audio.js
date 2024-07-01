// Function to initialize audio player
export function initializeAudioPlayer(player) {
    const audioPlayer = player.querySelector(".audio_player");

    const progressRange = player.querySelector(".progress-range");
    const progressBar = player.querySelector(".progress-bar");

    const currentTime = player.querySelector(".time-elapsed");
    //const duration = player.querySelector(".time-duration");

    const audio = player.querySelector(".audio");

    const playBtn = player.querySelector(".play");

    const speaker = player.querySelector(".speaker");
    const speakerIcon = player.querySelector("#speaker_icon");

    const volInput = player.querySelector('input[name="volume"]');

    // Function to dynamically load an audio file
    function loadSong(songUrl) {
        audio.src = songUrl;
    }

    // Play the audio
    function playSong() {
        player.classList.add("play");
        playBtn.querySelector("i.fas").classList.remove("fa-play");
        playBtn.querySelector("i.fas").classList.add("fa-pause");

        audio.play();
    }

    // Pause the audio
    function pauseSong() {
        player.classList.remove("play");
        playBtn.querySelector("i.fas").classList.add("fa-play");
        playBtn.querySelector("i.fas").classList.remove("fa-pause");

        audio.pause();
    }

    // Display time in minutes and seconds
    function displayTime(time) {
        const minutes = Math.floor(time / 60);
        let seconds = Math.floor(time % 60);
        seconds = seconds > 9 ? seconds : `0${seconds}`;
        return `${minutes}:${seconds}`;
    }

    // Update the progress bar as the audio plays
    function updateProgress() {
        if (audio.duration) {
            const progressPercent = (audio.currentTime / audio.duration) * 100;
            progressBar.style.width = `${progressPercent}%`;
            currentTime.textContent = displayTime(audio.currentTime);
            duration.textContent = displayTime(audio.duration);
        }
    }

    // Scrub through the audio
    function scrub(event) {
        const scrubTime =
            (event.offsetX / progressRange.offsetWidth) * audio.duration;
        audio.currentTime = scrubTime;
    }

    // Display the duration of the audio
    function displayDuration() {
        //    duration.textContent = displayTime(audio.duration);
    }
    console.log(audio);
    if (audio.readyState > 0) {
        displayDuration();
    } else {
        audio.addEventListener("loadedmetadata", displayDuration);
    }

    // Handle volume changes
    function handleRangeUpdate() {
        audio.volume = this.value;
        speakerIcon.className =
            audio.volume === 0 ? "fa fa-volume-off" : "fa fa-volume-up";
    }

    let muted = false;

    // Mute or unmute the audio
    function mute() {
        if (!muted) {
            audio.volume = 0;
            volInput.value = 0;
            speakerIcon.className = "fa fa-volume-off";
            muted = true;
        } else {
            audio.volume = 1;
            volInput.value = 1;
            muted = false;
            speakerIcon.className = "fa fa-volume-up";
        }
    }

    // Event Listeners
    playBtn.addEventListener("click", () => {
        const isPlaying = player.classList.contains("play");

        if (isPlaying) {
            pauseSong();
        } else {
            playSong();
        }
    });
    function setProgress(e) {
        const newTime = e.offsetX / progressRange.offsetWidth;
        progressBar.style.width = `${newTime * 100}%`;
        audioPlayer.currentTime = newTime * audioPlayer.duration;
    }
    // Update progress bar as the audio plays
    audio.addEventListener("timeupdate", updateProgress);

    // Click on progress bar to seek
    progressRange.addEventListener("click", setProgress);

    // Volume controls
    // volInput.addEventListener("change", handleRangeUpdate);
    // volInput.addEventListener("mousemove", handleRangeUpdate);
    // speaker.addEventListener("click", mute);

    // Progress bar scrubbing
    let mouseDown = false;
    progressRange.addEventListener("click", scrub);
    progressRange.addEventListener(
        "mousemove",
        (event) => mouseDown && scrub(event)
    );
    progressRange.addEventListener("mousedown", () => (mouseDown = true));
    progressRange.addEventListener("mouseup", () => (mouseDown = false));

    // Function to dynamically add audio from chat
    function addAudioFromChat(audioUrl) {
        loadSong(audioUrl);
        playSong();
    }

    // Example usage: Adding an audio file from a chat message
    $(document).on("click", ".chat-audio", function () {
        const audioUrl = $(this).data("audio-url");
        addAudioFromChat(audioUrl);
    });
}

// Initialize all audio players
export function musicPlayer(url) {
    setTimeout(() => {
        document
            .querySelectorAll(".music-container")
            .forEach(initializeAudioPlayer);
    }, 2500);

    // HTML structure of the music player
    return `
        <div class="music-container">
            <div class="navigation">
                <button class="action-btn action-btn-big play">
                    <i class="fas fa-play"></i>
                </button>
                <div class="music-info">
                    <div class="progress-container progress-range">
                        <div class="grey-bar">
                            <svg width="105" height="18" preserveAspectRatio="none" viewBox="0 0 106 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <g clip-path="url(#clip0_507_5530)">
                                    <path d="M3.5 4.5C3.5 3.67157 2.82843 3 2 3C1.17157 3 0.5 3.67157 0.5 4.5V13.5C0.5 14.3284 1.17157 15 2 15C2.82843 15 3.5 14.3284 3.5 13.5V4.5Z" fill="#94A3B8"></path>
                                    <path d="M9.5 7.5C9.5 6.67157 8.82843 6 8 6C7.17157 6 6.5 6.67157 6.5 7.5V10.5C6.5 11.3284 7.17157 12 8 12C8.82843 12 9.5 11.3284 9.5 10.5V7.5Z" fill="#94A3B8"></path>
                                    <path d="M15.5 1.5C15.5 0.671573 14.8284 0 14 0C13.1716 0 12.5 0.671573 12.5 1.5V16.5C12.5 17.3284 13.1716 18 14 18C14.8284 18 15.5 17.3284 15.5 16.5V1.5Z" fill="#94A3B8"></path>
                                    <path d="M21.5 3.5C21.5 2.67157 20.8284 2 20 2C19.1716 2 18.5 2.67157 18.5 3.5V14.5C18.5 15.3284 19.1716 16 20 16C20.8284 16 21.5 15.3284 21.5 14.5V3.5Z" fill="#94A3B8"></path>
                                    <path d="M27.5 2.5C27.5 1.67157 26.8284 1 26 1C25.1716 1 24.5 1.67157 24.5 2.5V15.5C24.5 16.3284 25.1716 17 26 17C26.8284 17 27.5 16.3284 27.5 15.5V2.5Z" fill="#94A3B8"></path>
                                    <path d="M33.5 4.5C33.5 3.67157 32.8284 3 32 3C31.1716 3 30.5 3.67157 30.5 4.5V13.5C30.5 14.3284 31.1716 15 32 15C32.8284 15 33.5 14.3284 33.5 13.5V4.5Z" fill="#94A3B8"></path>
                                    <path d="M39.5 6C39.5 5.17157 38.8284 4.5 38 4.5C37.1716 4.5 36.5 5.17157 36.5 6V12C36.5 12.8284 37.1716 13.5 38 13.5C38.8284 13.5 39.5 12.8284 39.5 12V6Z" fill="#94A3B8"></path>
                                    <path d="M45.5 7.5C45.5 6.67157 44.8284 6 44 6C43.1716 6 42.5 6.67157 42.5 7.5V10.5C42.5 11.3284 43.1716 12 44 12C44.8284 12 45.5 11.3284 45.5 10.5V7.5Z" fill="#94A3B8"></path>
                                    <path d="M51.5 6C51.5 5.17157 50.8284 4.5 50 4.5C49.1716 4.5 48.5 5.17157 48.5 6V12C48.5 12.8284 49.1716 13.5 50 13.5C50.8284 13.5 51.5 12.8284 51.5 12V6Z" fill="#94A3B8"></path>
                                    <path d="M57.5 6C57.5 5.17157 56.8284 4.5 56 4.5C55.1716 4.5 54.5 5.17157 54.5 6V12C54.5 12.8284 55.1716 13.5 56 13.5C56.8284 13.5 57.5 12.8284 57.5 12V6Z" fill="#94A3B8"></path>
                                    <path d="M63.5 4.5C63.5 3.67157 62.8284 3 62 3C61.1716 3 60.5 3.67157 60.5 4.5V13.5C60.5 14.3284 61.1716 15 62 15C62.8284 15 63.5 14.3284 63.5 13.5V4.5Z" fill="#94A3B8"></path>
                                    <path d="M69.5 1.5C69.5 0.671573 68.8284 0 68 0C67.1716 0 66.5 0.671573 66.5 1.5V16.5C66.5 17.3284 67.1716 18 68 18C68.8284 18 69.5 17.3284 69.5 16.5V1.5Z" fill="#94A3B8"></path>
                                    <path d="M75.5 4.5C75.5 3.67157 74.8284 3 74 3C73.1716 3 72.5 3.67157 72.5 4.5V13.5C72.5 14.3284 73.1716 15 74 15C74.8284 15 75.5 14.3284 75.5 13.5V4.5Z" fill="#94A3B8"></path>
                                    <path d="M81.5 6C81.5 5.17157 80.8284 4.5 80 4.5C79.1716 4.5 78.5 5.17157 78.5 6V12C78.5 12.8284 79.1716 13.5 80 13.5C80.8284 13.5 81.5 12.8284 81.5 12V6Z" fill="#94A3B8"></path>
                                    <path d="M87.5 7.5C87.5 6.67157 86.8284 6 86 6C85.1716 6 84.5 6.67157 84.5 7.5V10.5C84.5 11.3284 85.1716 12 86 12C86.8284 12 87.5 11.3284 87.5 10.5V7.5Z" fill="#94A3B8"></path>
                                    <path d="M93.5 7.5C93.5 6.67157 92.8284 6 92 6C91.1716 6 90.5 6.67157 90.5 7.5V10.5C90.5 11.3284 91.1716 12 92 12C92.8284 12 93.5 11.3284 93.5 10.5V7.5Z" fill="#94A3B8"></path>
                                    <path d="M99.5 2.5C99.5 1.67157 98.8284 1 98 1C97.1716 1 96.5 1.67157 96.5 2.5V15.5C96.5 16.3284 97.1716 17 98 17C98.8284 17 99.5 16.3284 99.5 15.5V2.5Z" fill="#94A3B8"></path>
                                    <path d="M105.5 5C105.5 4.17157 104.828 3.5 104 3.5C103.172 3.5 102.5 4.17157 102.5 5V13C102.5 13.8284 103.172 14.5 104 14.5C104.828 14.5 105.5 13.8284 105.5 13V5Z" fill="#94A3B8"></path>
                                </g>
                                <defs>
                                    <clipPath id="clip0_507_5530">
                                        <rect width="106" height="18" fill="white"></rect>
                                    </clipPath>
                                </defs>
                            </svg>
                        </div>
                        <div class="pink-bar"></div>
                        <div class="progress-container" id="progress-container">
                            <div class="progress" id="progress"></div>
                        </div>
                    </div>
                    <audio class="audio" src="${url}"></audio>
                </div>
                 <div class="time">
                    <span class="time-elapsed">00:00</span>
                    </div>
            </div>
        </div>
    `;
}
