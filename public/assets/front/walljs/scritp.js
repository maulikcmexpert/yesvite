// fill-heart-icon
$(".posts-card-like-btn").on("click", function () {
    const icon = this.querySelector("i");
    icon.classList.toggle("fa-regular");
    icon.classList.toggle("fa-solid");
});

$(".notification-btn").on("click", function () {
    const icon = $(this).find("i");

    // Check if the bell icon is already in "silent" mode
    if (icon.hasClass("fa-bell")) {
        icon.removeClass("fa-bell").addClass("fa-bell-slash"); // Change to silent bell icon
        $(this).html(
            '<i class="fa-regular fa-bell-slash"></i> Enable Notifications'
        );
    } else {
        icon.removeClass("fa-bell-slash").addClass("fa-bell"); // Change back to normal bell icon
        $(this).html(
            '<i class="fa-regular fa-bell"></i> Silence Notifications'
        );
    }
});

// ===header-drodpdown===
const dropdownButton = document.getElementById("dropdownButton");
const dropdownMenu = document.querySelector(".notification-dropdown-menu");

// Toggle dropdown when clicking the button
$("#dropdownButton").on("click", function (event) {
    event.stopPropagation(); // Prevent the click event from bubbling up
    $(".notification-dropdown-menu").toggleClass("show");
});

// Close the dropdown when clicking outside
document.addEventListener("click", (event) => {
    if (
        !dropdownMenu.contains(event.target) &&
        !dropdownButton.contains(event.target)
    ) {
        dropdownMenu.classList.remove("show");
    }
});

// ----header-dropdown-menu---
$(document).ready(function () {
    $(".moblie-menu-bar").click(function () {
        $(".mobile-menu-wrp").toggleClass("active");
        $(".line").toggleClass("active");
        $(".mobile-menu-overlay").toggleClass("active");
    });
    $(".mobile-menu-overlay").click(function () {
        $(".mobile-menu-wrp").removeClass("active");
        $(".line").removeClass("active");
        $(".mobile-menu-overlay").removeClass("active");
    });
});

// Add new option on click
// $(".option-add-btn").on("click", function () {
//   const pollOptionsContainer = $(".poll-options");
//   const optionCount = pollOptionsContainer.children().length + 1;

//   const newOption = $(`
//     <div class="mb-3">
//       <label for="yourquestion" class="form-label d-flex align-items-center justify-content-between">Option ${optionCount}* <span>20/140</span></label>
//       <div class="position-relative">
//         <input type="text" class="form-control"  name="options[]" id="yourquestion" placeholder="">
//         <span class="input-option-delete">
//           <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
//             <path d="M14 3.98665C11.78 3.76665 9.54667 3.65332 7.32 3.65332C6 3.65332 4.68 3.71999 3.36 3.85332L2 3.98665" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
//             <path d="M5.66699 3.31334L5.81366 2.44001C5.92033 1.80668 6.00033 1.33334 7.12699 1.33334H8.87366C10.0003 1.33334 10.087 1.83334 10.187 2.44668L10.3337 3.31334" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
//             <path d="M12.5669 6.09332L12.1336 12.8067C12.0603 13.8533 12.0003 14.6667 10.1403 14.6667H5.86026C4.00026 14.6667 3.94026 13.8533 3.86693 12.8067L3.43359 6.09332" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
//             <path d="M6.88672 11H9.10672" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
//             <path d="M6.33301 8.33334H9.66634" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
//           </svg>
//         </span>
//       </div>
//     </div>
//   `);

//   pollOptionsContainer.append(newOption);

//   // Add delete functionality to the newly created delete button
//   newOption.find(".input-option-delete").on("click", function () {
//     newOption.remove();
//   });
// });

// ===add-photo-vidoe-div-onclick===
// const createphotobtn = document.getElementById("create-photo-btn");
// const createpollbtn = document.getElementById("create-poll-btn");
// const uploadphotodiv = document.querySelector(".create-post-upload-img-wrp");
// const uploadpolldiv = document.querySelector(".create-post-poll-wrp");
// const uploadimgdelete = document.querySelector(".upload-img-delete");
// const uploadpolldelete = document.querySelector(".upload-poll-delete");

// $("#create-photo-btn").click(function () {
//   $(".create-post-upload-img-wrp").removeClass("d-none");
//   $(".create-post-poll-wrp").addClass("d-none");
// });

// $("#create-poll-btn").click(function () {
//   $(".create-post-poll-wrp").removeClass("d-none");
//   $(".create-post-upload-img-wrp").addClass("d-none");
// });

// $(".upload-img-delete").click(function () {
//   $(".create-post-upload-img-wrp").addClass("d-none");
// });

// $(".upload-poll-delete").click(function () {
//   $(".create-post-poll-wrp").addClass("d-none");
// });

// $(".show-comments-btn").click(function () {
//     $(".posts-card-show-all-comments-wrp").toggleClass("d-none");
// });

// $(".show-comment-reply-btn").click(function () {
//     $(".reply-on-comment").toggleClass("d-none");
// });

// ===month-scroll-event===
function sticky_relocate() {
    var window_top = $(window).scrollTop();
    var div_top = $("#scrollStatus")?.offset()?.top;
    var activeTab = $(".tab-pane.active");

    if (activeTab.length > 0) {
        if (window_top > div_top) {
            const myElement = document.querySelector("#tabbtn1");
            if (myElement.classList.contains("sticknew")) {
                $("#tabbtn1")
                    .addClass("stick extra-stick")
                    .removeClass("sticknew");
            } else {
                $("#tabbtn1").addClass("stick").removeClass("sticknew");
            }
        } else {
            $("#tabbtn1").removeClass("stick");
        }
    }

    var div_top2 = $("#scrollStatus2").offset()?.top;
    if (activeTab.length > 0) {
        if (window_top > div_top2) {
            const myElement = document.querySelector("#tabbtn2");
            if (myElement.classList.contains("sticknew")) {
                $("#tabbtn2")
                    .addClass("stick extra-stick")
                    .removeClass("sticknew");
            } else {
                $("#tabbtn2").addClass("stick").removeClass("sticknew");
            }
        } else {
            $("#tabbtn2").removeClass("stick");
        }
    }

    var div_top3 = $("#scrollStatus3").offset()?.top;
    if (activeTab.length > 0) {
        if (window_top > div_top3) {
            const myElement = document.querySelector("#tabbtn3");
            if (myElement.classList.contains("sticknew")) {
                $("#tabbtn3")
                    .addClass("stick extra-stick")
                    .removeClass("sticknew");
            } else {
                $("#tabbtn3").addClass("stick").removeClass("sticknew");
            }
        } else {
            $("#tabbtn3").removeClass("stick");
        }
    }
}

$(function () {
    $(window).scroll(sticky_relocate);
    sticky_relocate();
});

function sticky_relocate1() {
    var window_top = $(window).scrollTop();
    var div_top = $("#scrollStatus2")?.offset()?.top;
    var activeTab = $(".tab-pane.active");

    console.log("Window Top:", window_top);
    console.log("Div Top:", div_top);
    console.log("Active Tab:", activeTab);
}

document.addEventListener("DOMContentLoaded", function () {
    let lastScrollTop = 0;

    document
        .getElementById("scrollStatus")
        .addEventListener("scroll", function () {
            const currentScrollTop = this.scrollTop;
            const windowTop = $(window).scrollTop();
            const divTop = $("#scrollStatus")?.offset()?.top;

            if (currentScrollTop > lastScrollTop && windowTop > divTop) {
                // Scrolling down and past the top of #scrollStatus
                $(".all-events-month-show")
                    .addClass("stick extra-stick")
                    .removeClass("sticknew");
            } else if (currentScrollTop > lastScrollTop) {
                // Scrolling down, but not past the top of #scrollStatus
                $(".all-events-month-show")
                    .addClass("sticknew")
                    .removeClass("stick");
            } else if (currentScrollTop <= 0) {
                // Scrolling back up to the top
                $(".all-events-month-show").removeClass("sticknew extra-stick");
            }

            lastScrollTop = currentScrollTop;
        });

    document
        .getElementById("scrollStatus2")
        .addEventListener("scroll", function () {
            const currentScrollTop = this.scrollTop;
            const windowTop = $(window).scrollTop();
            const divTop = $("#scrollStatus2")?.offset()?.top;

            if (currentScrollTop > lastScrollTop && windowTop > divTop) {
                // Scrolling down and past the top of #scrollStatus
                $(".all-events-month-show")
                    .addClass("stick extra-stick")
                    .removeClass("sticknew");
            } else if (currentScrollTop > lastScrollTop) {
                // Scrolling down, but not past the top of #scrollStatus
                $(".all-events-month-show")
                    .addClass("sticknew")
                    .removeClass("stick");
            } else if (currentScrollTop <= 0) {
                // Scrolling back up to the top
                $(".all-events-month-show").removeClass("sticknew extra-stick");
            }

            lastScrollTop = currentScrollTop;
        });

    document
        .getElementById("scrollStatus3")
        .addEventListener("scroll", function () {
            const currentScrollTop = this.scrollTop;
            const windowTop = $(window).scrollTop();
            const divTop = $("#scrollStatus3")?.offset()?.top;

            if (currentScrollTop > lastScrollTop && windowTop > divTop) {
                // Scrolling down and past the top of #scrollStatus
                $(".all-events-month-show")
                    .addClass("stick extra-stick")
                    .removeClass("sticknew");
            } else if (currentScrollTop > lastScrollTop) {
                // Scrolling down, but not past the top of #scrollStatus
                $(".all-events-month-show")
                    .addClass("sticknew")
                    .removeClass("stick");
            } else if (currentScrollTop <= 0) {
                // Scrolling back up to the top
                $(".all-events-month-show").removeClass("sticknew extra-stick");
            }

            lastScrollTop = currentScrollTop;
        });
});

// ===mute-post===
$(document).ready(function () {
    $(".mute-post-btn").on("click", function () {
        const $button = $(this);
        const $icon = $button.find(".muteIcon");
        const $buttonText = $button.find(".mute-post-btn-text");

        if ($buttonText.text() === "Mute") {
            $buttonText.text("Unmute");
            $icon.html(`
          <path d="M11.5651 17.1583C10.9068 17.1583 10.1818 16.925 9.45677 16.4667L7.02344 14.9417C6.85677 14.8417 6.6651 14.7833 6.47344 14.7833H5.27344C3.25677 14.7833 2.14844 13.675 2.14844 11.6583V8.32501C2.14844 6.30834 3.25677 5.20001 5.27344 5.20001H6.4651C6.65677 5.20001 6.84844 5.14167 7.0151 5.04167L9.44844 3.51667C10.6651 2.75834 11.8484 2.61667 12.7818 3.13334C13.7151 3.65001 14.2234 4.72501 14.2234 6.16667V13.8083C14.2234 15.2417 13.7068 16.325 12.7818 16.8417C12.4151 17.0583 12.0068 17.1583 11.5651 17.1583ZM5.27344 6.45834C3.95677 6.45834 3.39844 7.01667 3.39844 8.33334V11.6667C3.39844 12.9833 3.95677 13.5417 5.27344 13.5417H6.4651C6.89844 13.5417 7.3151 13.6583 7.68177 13.8917L10.1151 15.4167C10.9234 15.9167 11.6734 16.05 12.1818 15.7667C12.6901 15.4833 12.9818 14.775 12.9818 13.8333V6.17501C12.9818 5.22501 12.6901 4.51667 12.1818 4.24167C11.6734 3.95834 10.9234 4.08334 10.1151 4.59167L7.68177 6.10834C7.3151 6.34167 6.89844 6.45834 6.4651 6.45834H5.27344Z" fill="#F73C71"/>
          <path d="M16.1096 13.9583C15.9763 13.9583 15.8513 13.9166 15.7346 13.8333C15.4596 13.6249 15.4013 13.2333 15.6096 12.9583C16.9179 11.2166 16.9179 8.78328 15.6096 7.04161C15.4013 6.76661 15.4596 6.37494 15.7346 6.16661C16.0096 5.95828 16.4013 6.01661 16.6096 6.29161C18.2513 8.47494 18.2513 11.5249 16.6096 13.7083C16.4929 13.8749 16.3013 13.9583 16.1096 13.9583Z" fill="#F73C71"/>
        `);
        } else {
            $buttonText.text("Mute");
            $icon.html(`
          <path d="M5.83464 14.7916H4.16797C2.1513 14.7916 1.04297 13.6833 1.04297 11.6666V8.33331C1.04297 6.31664 2.1513 5.20831 4.16797 5.20831H5.35964C5.5513 5.20831 5.74297 5.14997 5.90964 5.04997L8.34297 3.52497C9.55964 2.76664 10.743 2.62497 11.6763 3.14164C12.6096 3.65831 13.118 4.73331 13.118 6.17497V6.97497C13.118 7.31664 12.8346 7.59997 12.493 7.59997C12.1513 7.59997 11.868 7.31664 11.868 6.97497V6.17497C11.868 5.22497 11.5763 4.51664 11.068 4.24164C10.5596 3.95831 9.80964 4.08331 9.0013 4.59164L6.56797 6.10831C6.20964 6.34164 5.78464 6.45831 5.35964 6.45831H4.16797C2.8513 6.45831 2.29297 7.01664 2.29297 8.33331V11.6666C2.29297 12.9833 2.8513 13.5416 4.16797 13.5416H5.83464C6.1763 13.5416 6.45964 13.825 6.45964 14.1666C6.45964 14.5083 6.1763 14.7916 5.83464 14.7916Z" fill="#94A3B8"/>
          <path d="M10.4577 17.1583C9.79934 17.1583 9.07434 16.925 8.34934 16.4666C8.05767 16.2833 7.96601 15.9 8.14934 15.6083C8.33267 15.3166 8.71601 15.225 9.00767 15.4083C9.81601 15.9083 10.566 16.0416 11.0743 15.7583C11.5827 15.475 11.8743 14.7666 11.8743 13.825V10.7916C11.8743 10.45 12.1577 10.1666 12.4993 10.1666C12.841 10.1666 13.1243 10.45 13.1243 10.7916V13.825C13.1243 15.2583 12.6077 16.3416 11.6827 16.8583C11.3077 17.0583 10.891 17.1583 10.4577 17.1583Z" fill="#94A3B8"/>
          <path d="M15.0002 13.9584C14.8669 13.9584 14.7419 13.9167 14.6252 13.8334C14.3502 13.625 14.2919 13.2334 14.5002 12.9584C15.5502 11.5584 15.7752 9.70002 15.1002 8.09169C14.9669 7.77502 15.1169 7.40835 15.4336 7.27502C15.7502 7.14169 16.1169 7.29169 16.2502 7.60835C17.1002 9.62502 16.8086 11.9667 15.5002 13.7167C15.3752 13.875 15.1919 13.9584 15.0002 13.9584Z" fill="#94A3B8"/>
          <path d="M16.5237 16.0417C16.3903 16.0417 16.2653 16 16.1487 15.9167C15.8737 15.7084 15.8153 15.3167 16.0237 15.0417C17.807 12.6667 18.1987 9.48338 17.0487 6.74171C16.9153 6.42504 17.0653 6.05838 17.382 5.92504C17.707 5.79171 18.0653 5.94171 18.1987 6.25838C19.5237 9.40838 19.0737 13.0584 17.0237 15.7917C16.907 15.9584 16.7153 16.0417 16.5237 16.0417Z" fill="#94A3B8"/>
          <path d="M1.66589 18.9583C1.50755 18.9583 1.34922 18.9 1.22422 18.775C0.982552 18.5333 0.982552 18.1333 1.22422 17.8916L17.8909 1.22495C18.1326 0.983285 18.5326 0.983285 18.7742 1.22495C19.0159 1.46662 19.0159 1.86662 18.7742 2.10828L2.10755 18.775C1.98255 18.9 1.82422 18.9583 1.66589 18.9583Z" fill="#94A3B8"/>
        `);
        }
    });
});

// ===hide-post===
$(".hide-post-btn").on("click", function () {
    const buttonText = $(this).find(".buttonText");
    const icon = $(this).find(".hide-post-svg-icon");

    if (buttonText.text() === "Hide Post") {
        // Change to "Show Post"
        buttonText.text("Show Post");

        // Change icon to "eye"
        icon.html(`
          <path d="M9.99896 13.6084C8.00729 13.6084 6.39062 11.9917 6.39062 10.0001C6.39062 8.00839 8.00729 6.39172 9.99896 6.39172C11.9906 6.39172 13.6073 8.00839 13.6073 10.0001C13.6073 11.9917 11.9906 13.6084 9.99896 13.6084ZM9.99896 7.64172C8.69896 7.64172 7.64062 8.70006 7.64062 10.0001C7.64062 11.3001 8.69896 12.3584 9.99896 12.3584C11.299 12.3584 12.3573 11.3001 12.3573 10.0001C12.3573 8.70006 11.299 7.64172 9.99896 7.64172Z" fill="#94A3B8"></path>
    <path d="M9.99844 17.5166C6.8651 17.5166 3.90677 15.6833 1.87344 12.4999C0.990104 11.1249 0.990104 8.88328 1.87344 7.49994C3.9151 4.31661 6.87344 2.48328 9.99844 2.48328C13.1234 2.48328 16.0818 4.31661 18.1151 7.49994C18.9984 8.87494 18.9984 11.1166 18.1151 12.4999C16.0818 15.6833 13.1234 17.5166 9.99844 17.5166ZM9.99844 3.73328C7.30677 3.73328 4.73177 5.34994 2.93177 8.17494C2.30677 9.14994 2.30677 10.8499 2.93177 11.8249C4.73177 14.6499 7.30677 16.2666 9.99844 16.2666C12.6901 16.2666 15.2651 14.6499 17.0651 11.8249C17.6901 10.8499 17.6901 9.14994 17.0651 8.17494C15.2651 5.34994 12.6901 3.73328 9.99844 3.73328Z" fill="#94A3B8"></path>
    <path class="slash-line" d="M2 2L18 18" stroke="#94A3B8" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"></path>
      `);
    } else {
        // Change back to "Hide Post"
        buttonText.text("Hide Post");

        // Change icon back to the original
        icon.html(`
        <path
          d="M9.99896 13.6084C8.00729 13.6084 6.39062 11.9917 6.39062 10.0001C6.39062 8.00839 8.00729 6.39172 9.99896 6.39172C11.9906 6.39172 13.6073 8.00839 13.6073 10.0001C13.6073 11.9917 11.9906 13.6084 9.99896 13.6084ZM9.99896 7.64172C8.69896 7.64172 7.64062 8.70006 7.64062 10.0001C7.64062 11.3001 8.69896 12.3584 9.99896 12.3584C11.299 12.3584 12.3573 11.3001 12.3573 10.0001C12.3573 8.70006 11.299 7.64172 9.99896 7.64172Z"
          fill="#94A3B8" />
        <path
          d="M9.99844 17.5166C6.8651 17.5166 3.90677 15.6833 1.87344 12.4999C0.990104 11.1249 0.990104 8.88328 1.87344 7.49994C3.9151 4.31661 6.87344 2.48328 9.99844 2.48328C13.1234 2.48328 16.0818 4.31661 18.1151 7.49994C18.9984 8.87494 18.9984 11.1166 18.1151 12.4999C16.0818 15.6833 13.1234 17.5166 9.99844 17.5166ZM9.99844 3.73328C7.30677 3.73328 4.73177 5.34994 2.93177 8.17494C2.30677 9.14994 2.30677 10.8499 2.93177 11.8249C4.73177 14.6499 7.30677 16.2666 9.99844 16.2666C12.6901 16.2666 15.2651 14.6499 17.0651 11.8249C17.6901 10.8499 17.6901 9.14994 17.0651 8.17494C15.2651 5.34994 12.6901 3.73328 9.99844 3.73328Z"
          fill="#94A3B8" />
      `);
    }
});

$(document).ready(function () {
    $(".popup-videos").magnificPopup({
        disableOn: 320,
        type: "iframe",
        mainClass: "mfp-fade",
        removalDelay: 160,
        preloader: false,

        fixedContentPos: false,
    });
});
