var base_url = $("#base_url").val();

// $(document).on('click','.popup-videos',function(){
// alert();
// });
// $(document).ready(function () {
//   $(".popup-videos").magnificPopup({
//     disableOn: 320,
//     type: "iframe",
//     mainClass: "mfp-fade",
//     removalDelay: 160,
//     preloader: false,
//     fixedContentPos: false,
//   });
// });

// Reinitialize after AJAX success
$(document).on("click", ".popup-videos", function () {
    $(this)
        .magnificPopup({
            disableOn: 320,
            type: "iframe",
            mainClass: "mfp-fade",
            removalDelay: 160,
            preloader: false,
            fixedContentPos: false,
        })
        .magnificPopup("open");
    return false; // Prevent default behavior
});

$(".notification-btn").on("click", function () {
    const Onnotification = `<svg viewBox="0 0 25 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <path d="M12.5194 2.58331C8.68605 2.58331 5.58188 5.68748 5.58188 9.52081V11.7083C5.58188 12.4166 5.29022 13.4791 4.92563 14.0833L3.60272 16.2916C2.79022 17.6562 3.35272 19.1771 4.85272 19.6771C9.83188 21.3333 15.2173 21.3333 20.1965 19.6771C21.6027 19.2083 22.2069 17.5625 21.4465 16.2916L20.1235 14.0833C19.759 13.4791 19.4673 12.4062 19.4673 11.7083V9.52081C19.4569 5.70831 16.3319 2.58331 12.5194 2.58331Z" stroke="#0F172A" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round"></path>
                      <path d="M15.9688 20.1042C15.9688 22.0104 14.4063 23.5729 12.5 23.5729C11.5521 23.5729 10.6771 23.1771 10.0521 22.5521C9.42708 21.9271 9.03125 21.0521 9.03125 20.1042" stroke="#0F172A" stroke-width="1.5" stroke-miterlimit="10"></path>
                    </svg>`;
    const OffNotification = `<svg width="17" height="20" viewBox="0 0 17 20" fill="none" xmlns="http://www.w3.org/2000/svg">
<g clip-path="url(#clip0_629_6)">
<path d="M8.43113 2C5.36447 2 2.88113 4.48333 2.88113 7.55V9.3C2.88113 9.86667 2.6478 10.7167 2.35613 11.2L1.2978 12.9667C0.647798 14.0583 1.0978 15.275 2.2978 15.675C6.28113 17 10.5895 17 14.5728 15.675C15.6978 15.3 16.1811 13.9833 15.5728 12.9667L14.5145 11.2C14.2228 10.7167 13.9895 9.85833 13.9895 9.3V7.55C13.9811 4.5 11.4811 2 8.43113 2Z" stroke="#0F172A" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round"/>
<path d="M11.1906 16.0166C11.1906 17.5416 9.94062 18.7916 8.41562 18.7916C7.65729 18.7916 6.95729 18.4749 6.45729 17.9749C5.95729 17.4749 5.64062 16.7749 5.64062 16.0166" stroke="#0F172A" stroke-width="1.5" stroke-miterlimit="10"/>
<path d="M1 1L15 19" stroke="#0F172A" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round"/>
</g>
<defs>
<clipPath id="clip0_629_6">
<rect width="17" height="20" fill="white"/>
</clipPath>
</defs>
</svg>
`;

    const icon = $(this).find("i");
    // Check if the bell icon is already in "silent" mode
    if (icon.hasClass("fa-bell")) {
        const svgWrapper = $(this)
            .closest(".upcoming-events-card-notification-wrp")
            .find("svg");
        icon.removeClass("fa-bell").addClass("fa-bell-slash"); // Change to silent bell icon
        $(this).html(
            '<i class="fa-regular fa-bell-slash"></i> Enable Notifications'
        );
        svgWrapper.replaceWith(OffNotification);

        $(this).removeAttr("data-status");

        $(this).attr("data-status", "0");
        var status = "0";
        console.log(status);
        var event_id = $(this).data("event_id");
        var user_id = $(this).data("user_id");
        var is_owner = $(this).data("is_owner");
    } else {
        const svgWrapper = $(this)
            .closest(".upcoming-events-card-notification-wrp")
            .find("svg");
        icon.removeClass("fa-bell-slash").addClass("fa-bell"); // Change back to normal bell icon
        $(this).html(
            '<i class="fa-regular fa-bell"></i> Silence Notifications'
        );
        svgWrapper.replaceWith(Onnotification);

        $(this).removeAttr("data-status");

        $(this).attr("data-status", "1");
        var status = "1";
        console.log(status);
        var event_id = $(this).data("event_id");
        var user_id = $(this).data("user_id");
        var is_owner = $(this).data("is_owner");
    }

    $.ajax({
        url: `${base_url}event/notification_on_off`,
        type: "POST",
        data: {
            is_owner: is_owner,
            user_id: user_id,
            status: status,
            event_id: event_id,
            _token: $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (response) {
            console.log(response);
            if (response.status == 1) {
                toastr.success(response.message);
            }
        },
        error: function (xhr, status, error) {
            console.error("Error fetching events:", error);
            busy = false;
            // $('.loader').css('display','none');
        },
        complete: function () {
            $(".loader").css("display", "none");
        },
    });
});

// ===header-drodpdown===
const dropdownButton = document.getElementById("dropdownButton");
const dropdownMenu = document.querySelector(".notification-dropdown-menu");
const modal = document.getElementById('all-notification-filter-modal');


// document.addEventListener('click', function(event) {
//     // Check if the clicked element is outside the dropdown
//     if (!dropdownToggle.contains(event.target) && !dropdownMenu.contains(event.target)) {
//         // Hide the dropdown menu
//         dropdownMenu.classList.remove('show');
//     }
// });
// Toggle dropdown when clicking the button
$("#dropdownButton").on("click", function (event) {
    event.stopPropagation(); // Prevent the click event from bubbling up
    $(".notification-dropdown-menu").toggleClass("show");
    if ($(".header-profile-dropdown").hasClass("show")) {
        $(".header-profile-dropdown").removeClass("show");
    }
    
});


$(".moblie-menu-bar").on("click", function (event) {
    event.stopPropagation(); // Prevent the click event from bubbling up
    if ($(".notification-dropdown-menu").hasClass("show")) {
        $(".notification-dropdown-menu").removeClass("show");
    }
});
$(".notification-toggle-menu").on("click", function (event) {
    event.stopPropagation(); // Prevent the click event from bubbling up
    if ($(".mobile-menu-wrp").hasClass("active")) {
        $(".mobile-menu-wrp").removeClass("active");
        $(".line").removeClass("active");
    }
});
document.addEventListener("click", (event) => {
    if (
      !dropdownMenu.contains(event.target) && 
      !dropdownButton.contains(event.target) &&
      !modal.contains(event.target) // Close the dropdown if clicked inside the modal
    ) {
      dropdownMenu.classList.remove("show");
    }
  });
// document.addEventListener("click", (event) => {
//   if (
//     !dropdownMenu.contains(event.target) &&
//     !dropdownButton.contains(event.target)
//   ) {
//     dropdownMenu.classList.remove("show");
//   }
// });
// Close the dropdown when clicking outside
// document.addEventListener("click", (event) => {
//   if (
//     !dropdownMenu.contains(event.target) &&
//     !dropdownButton.contains(event.target)
//   ) {
//     dropdownMenu.classList.remove("show");
//   }
// });

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

// ===header-drodpdown===
const upcomingdropdownButton = document.getElementById(
    "upcoming-card-dropdownButton"
);
const upcomingdropdownMenu = document.querySelector(
    ".upcoming-events-card-notification-info"
);

// Toggle dropdown when clicking the button
$("#upcoming-card-dropdownButton").on("click", function (event) {
    event.stopPropagation(); // Prevent the click event from bubbling up

    $("#upcoming-events-card-notification-info").toggleClass("show");
});

// Close the dropdown when clicking outside
// $(document).on("click", function(event) {
//   if (!$(event.target).closest(upcomingdropdownMenu).length && !$(event.target).closest(upcomingdropdownButton).length) {
//     $(upcomingdropdownMenu).removeClass("show");
//   }
// });

// ===create-post-hide-show-setting===
const createpostmainbody = document.querySelector(".create-post-main-body");
const createpostsettingmainbody = document.querySelector(
    ".create-post-setting-main-body"
);
const createpostprofile = document.querySelector(".create-post-profile-wrp");
const backbtn = document.querySelector(".btn-back");

$(".create-post-profile-wrp").on("click", function () {
    $(".create-post-main-body").addClass("d-none");
    $(".create-post-setting-main-body").removeClass("d-none");
});

$(".btn-back").on("click", function () {
    $(".create-post-setting-main-body").addClass("d-none");
    $(".create-post-main-body").removeClass("d-none");
});

$(document).on("change", ".fileInputtype", function (event) {
    console.log(event);

    const files = Array.from(event.target.files);
    const imagePreview = document.getElementById("imagePreview");
    const uploadImgInner = document.querySelector(
        ".create-post-upload-img-inner"
    );
    const uploadHeadButton = document.querySelector(
        ".create-post-head-upload-btn"
    );

    const totalFiles = imagePreview.children.length + files.length;

    // Toggle visibility based on file presence
    if (files.length > 0) {
        uploadImgInner.classList.add("d-none");
        uploadHeadButton.classList.remove("d-none");
    }

    if (totalFiles > 1) {
        for (const previewItem of imagePreview.children) {
            previewItem.classList.remove("col-12");
            previewItem.classList.add("col-6");
        }
    }

    files.forEach((file) => {
        const fileReader = new FileReader();
        fileReader.onload = function (e) {
            const previewDiv = document.createElement("div");
            previewDiv.classList.add(totalFiles === 1 ? "col-12" : "col-6");
            previewDiv.style.position = "relative";

            // Create the delete icon
            const deleteIcon = document.createElement("span");
            deleteIcon.innerHTML = `
        <svg viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M14 3.98665C11.78 3.76665 9.54667 3.65332 7.32 3.65332C6 3.65332 4.68 3.71999 3.36 3.85332L2 3.98665" stroke="#0F172A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
          <path d="M5.6665 3.31331L5.81317 2.43998C5.91984 1.80665 5.99984 1.33331 7.1265 1.33331H8.87317C9.99984 1.33331 10.0865 1.83331 10.1865 2.44665L10.3332 3.31331" stroke="#0F172A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
          <path d="M12.5664 6.09332L12.1331 12.8067C12.0598 13.8533 11.9998 14.6667 10.1398 14.6667H5.85977C3.99977 14.6667 3.93977 13.8533 3.86644 12.8067L3.43311 6.09332" stroke="#0F172A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
          <path d="M6.88672 11H9.10672" stroke="#0F172A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
          <path d="M6.3335 8.33331H9.66683" stroke="#0F172A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
      `;
            deleteIcon.classList.add("uploded-delete-icon");
            // Delete image functionality
            deleteIcon.addEventListener("click", function () {
                imagePreview.removeChild(previewDiv);

                // Show `create-post-upload-img-inner` and hide `create-post-head-upload-btn` if no images left
                if (imagePreview.children.length === 0) {
                    uploadImgInner.classList.remove("d-none");
                    uploadHeadButton.classList.add("d-none");
                }
            });

            previewDiv.appendChild(deleteIcon);

            // Display image or video based on file type
            if (file.type.startsWith("image/")) {
                const img = document.createElement("img");
                img.src = e.target.result;
                img.classList.add("preview-image");
                previewDiv.appendChild(img);
            } else if (file.type.startsWith("video/")) {
                const video = document.createElement("video");
                video.src = e.target.result;
                video.controls = true;
                video.classList.add("preview-video");
                previewDiv.appendChild(video);
            }

            imagePreview.appendChild(previewDiv);
        };

        fileReader.readAsDataURL(file);
    });
});

// Add new option on click
$(".option-add-btn").on("click", function () {
    const pollOptionsContainer = $(".poll-options");
    const optionCount = pollOptionsContainer.children().length + 1;

    const newOption = $(`
    <div class="mb-3">
      <label for="yourquestion" class="form-label d-flex align-items-center justify-content-between">Option ${optionCount}* <span>20/140</span></label>
      <div class="position-relative">
        <input type="text" class="form-control" id="yourquestion" placeholder="">
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

    // Add delete functionality to the newly created delete button
    newOption.find(".input-option-delete").on("click", function () {
        newOption.remove();
    });
});

// ===add-photo-vidoe-div-onclick===
const createphotobtn = document.getElementById("create-photo-btn");
const createpollbtn = document.getElementById("create-poll-btn");
const uploadphotodiv = document.querySelector(".create-post-upload-img-wrp");
const uploadpolldiv = document.querySelector(".create-post-poll-wrp");
const uploadimgdelete = document.querySelector(".upload-img-delete");
const uploadpolldelete = document.querySelector(".upload-poll-delete");

$("#create-photo-btn").click(function () {
    $(".create-post-upload-img-wrp").removeClass("d-none");
    $(".create-post-poll-wrp").addClass("d-none");
});

$("#create-poll-btn").click(function () {
    $(".create-post-poll-wrp").removeClass("d-none");
    $(".create-post-upload-img-wrp").addClass("d-none");
});

$(".upload-img-delete").click(function () {
    $(".create-post-upload-img-wrp").addClass("d-none");
});

$(".upload-poll-delete").click(function () {
    $(".create-post-poll-wrp").addClass("d-none");
});

$(document).on("click", ".header-profile-wrp", function () {
    if ($(".notification-dropdown-menu").hasClass("show")) {
        $(".notification-dropdown-menu").removeClass("show");
    }
});

// // ===month-scroll-event===
// function sticky_relocate() {
//   var window_top = $(window).scrollTop();
//   var div_top = $("#scrollStatus").offset().top;
//   var activeTab = $(".tab-pane.active");

//   if (activeTab.length > 0) {
//     if (window_top > div_top) {
//       const myElement = document.querySelector("#tabbtn1");
//       if (myElement.classList.contains("sticknew")) {
//         $("#tabbtn1").addClass("stick extra-stick").removeClass("sticknew");
//       } else {
//         $("#tabbtn1").addClass("stick").removeClass("sticknew");
//       }
//     } else {
//       $("#tabbtn1").removeClass("stick");
//     }
//   }

//   var div_top2 = $("#scrollStatus2").offset().top;
//   if (activeTab.length > 0) {
//     if (window_top > div_top2) {
//       const myElement = document.querySelector("#tabbtn2");
//       if (myElement.classList.contains("sticknew")) {
//         $("#tabbtn2").addClass("stick extra-stick").removeClass("sticknew");
//       } else {
//         $("#tabbtn2").addClass("stick").removeClass("sticknew");
//       }
//     } else {
//       $("#tabbtn2").removeClass("stick");
//     }
//   }

//   var div_top3 = $("#scrollStatus3").offset().top;
//   if (activeTab.length > 0) {
//     if (window_top > div_top3) {
//       const myElement = document.querySelector("#tabbtn3");
//       if (myElement.classList.contains("sticknew")) {
//         $("#tabbtn3").addClass("stick extra-stick").removeClass("sticknew");
//       } else {
//         $("#tabbtn3").addClass("stick").removeClass("sticknew");
//       }
//     } else {
//       $("#tabbtn3").removeClass("stick");
//     }
//   }
// }

// $(function () {
//   $(window).scroll(sticky_relocate);
//   sticky_relocate();
// });

// function sticky_relocate1() {
//   var window_top = $(window).scrollTop();
//   var div_top = $("#scrollStatus2").offset().top;
//   var activeTab = $(".tab-pane.active");

//   console.log("Window Top:", window_top);
//   console.log("Div Top:", div_top);
//   console.log("Active Tab:", activeTab);
// }

// document.addEventListener("DOMContentLoaded", function () {
//   let lastScrollTop = 0;

//   document
//     .getElementById("scrollStatus")
//     .addEventListener("scroll", function () {
//       const currentScrollTop = this.scrollTop;
//       const windowTop = $(window).scrollTop();
//       const divTop = $("#scrollStatus").offset().top;

//       if (currentScrollTop > lastScrollTop && windowTop > divTop) {
//         // Scrolling down and past the top of #scrollStatus
//         $(".all-events-month-show")
//           .addClass("stick extra-stick")
//           .removeClass("sticknew");
//       } else if (currentScrollTop > lastScrollTop) {
//         // Scrolling down, but not past the top of #scrollStatus
//         $(".all-events-month-show").addClass("sticknew").removeClass("stick");
//       } else if (currentScrollTop <= 0) {
//         // Scrolling back up to the top
//         $(".all-events-month-show").removeClass("sticknew extra-stick");
//       }

//       const container = document.getElementById("scrollStatus");
//       const supports = container.getElementsByClassName("all-events-month-wise-support");
//       let topMonth = null;

//       for (let support of supports) {
//           const rect = support.getBoundingClientRect();
//           const containerRect = container.getBoundingClientRect();

//           // Check if the element is at the top of the scroll
//           if (rect.top >= containerRect.top && rect.bottom <= containerRect.bottom) {
//               topMonth = support.getAttribute("data-month");
//               break;
//           }
//       }

//       if (topMonth) {
//           console.log("Month at the top of the scroll:", topMonth);

//           $('#upcoming_event_month').text(topMonth);
//           // Optionally update the UI to show the current month
//           // $(".all-events-month-show").text(topMonth);
//       }

//       lastScrollTop = currentScrollTop;
//     });

//   document
//     .getElementById("scrollStatus2")
//     .addEventListener("scroll", function () {
//       const currentScrollTop = this.scrollTop;
//       const windowTop = $(window).scrollTop();
//       const divTop = $("#scrollStatus2").offset().top;

//       if (currentScrollTop > lastScrollTop && windowTop > divTop) {
//         // Scrolling down and past the top of #scrollStatus
//         $(".all-events-month-show")
//           .addClass("stick extra-stick")
//           .removeClass("sticknew");
//       } else if (currentScrollTop > lastScrollTop) {
//         // Scrolling down, but not past the top of #scrollStatus
//         $(".all-events-month-show").addClass("sticknew").removeClass("stick");
//       } else if (currentScrollTop <= 0) {
//         // Scrolling back up to the top
//         $(".all-events-month-show").removeClass("sticknew extra-stick");
//       }

//       lastScrollTop = currentScrollTop;
//     });

//   document
//     .getElementById("scrollStatus3")
//     .addEventListener("scroll", function () {
//       const currentScrollTop = this.scrollTop;
//       const windowTop = $(window).scrollTop();
//       const divTop = $("#scrollStatus3").offset().top;

//       if (currentScrollTop > lastScrollTop && windowTop > divTop) {
//         // Scrolling down and past the top of #scrollStatus
//         $(".all-events-month-show")
//           .addClass("stick extra-stick")
//           .removeClass("sticknew");
//       } else if (currentScrollTop > lastScrollTop) {
//         // Scrolling down, but not past the top of #scrollStatus
//         $(".all-events-month-show").addClass("sticknew").removeClass("stick");
//       } else if (currentScrollTop <= 0) {
//         // Scrolling back up to the top
//         $(".all-events-month-show").removeClass("sticknew extra-stick");
//       }

//       lastScrollTop = currentScrollTop;
//     });
// });

// ===month-scroll-event===
// function sticky_relocate() {
//   var window_top = $(window).scrollTop();
//   var div_top = $("#scrollStatus").offset().top;
//   var activeTab = $(".tab-pane.active");

//   if (activeTab.length > 0) {
//     if (window_top > div_top) {
//       const myElement = document.querySelector("#tabbtn1");
//       if (myElement.classList.contains("sticknew")) {
//         $("#tabbtn1").addClass("stick extra-stick").removeClass("sticknew");
//       } else {
//         $("#tabbtn1").addClass("stick").removeClass("sticknew");
//       }
//     } else {
//       $("#tabbtn1").removeClass("stick");
//     }
//   }

//   var div_top2 = $("#scrollStatus2").offset().top;
//   if (activeTab.length > 0) {
//     if (window_top > div_top2) {
//       const myElement = document.querySelector("#tabbtn2");
//       if (myElement.classList.contains("sticknew")) {
//         $("#tabbtn2").addClass("stick extra-stick").removeClass("sticknew");
//       } else {
//         $("#tabbtn2").addClass("stick").removeClass("sticknew");
//       }
//     } else {
//       $("#tabbtn2").removeClass("stick");
//     }
//   }

//   var div_top3 = $("#scrollStatus3").offset().top;
//   if (activeTab.length > 0) {
//     if (window_top > div_top3) {
//       const myElement = document.querySelector("#tabbtn3");
//       if (myElement.classList.contains("sticknew")) {
//         $("#tabbtn3").addClass("stick extra-stick").removeClass("sticknew");
//       } else {
//         $("#tabbtn3").addClass("stick").removeClass("sticknew");
//       }
//     } else {
//       $("#tabbtn3").removeClass("stick");
//     }
//   }
// }

// $(function () {
//   $(window).scroll(sticky_relocate);
//   sticky_relocate();
// });

// function sticky_relocate1() {
//   var window_top = $(window).scrollTop();
//   var div_top = $("#scrollStatus2").offset().top;
//   var activeTab = $(".tab-pane.active");

//   console.log("Window Top:", window_top);
//   console.log("Div Top:", div_top);
//   console.log("Active Tab:", activeTab);
// }

// document.addEventListener("DOMContentLoaded", function () {
//   let lastScrollTop = 0;

//   document
//     .getElementById("scrollStatus")
//     .addEventListener("scroll", function () {
//       const currentScrollTop = this.scrollTop;
//       const windowTop = $(window).scrollTop();
//       const divTop = $("#scrollStatus").offset().top;

//       if (currentScrollTop > lastScrollTop && windowTop > divTop) {
//         // Scrolling down and past the top of #scrollStatus
//         $(".all-events-month-show")
//           .addClass("stick extra-stick")
//           .removeClass("sticknew");
//       } else if (currentScrollTop > lastScrollTop) {
//         // Scrolling down, but not past the top of #scrollStatus
//         $(".all-events-month-show").addClass("sticknew").removeClass("stick");
//       } else if (currentScrollTop <= 0) {
//         // Scrolling back up to the top
//         $(".all-events-month-show").removeClass("sticknew extra-stick");
//       }

//       const container = document.getElementById("scrollStatus2");
//       const supports = container.getElementsByClassName("all-events-month-wise-support");
//       let topMonth = null;

//       for (let support of supports) {
//           const rect = support.getBoundingClientRect();
//           const containerRect = container.getBoundingClientRect();

//           if (rect.bottom >= containerRect.top && rect.top <= containerRect.bottom) {
//               topMonth = support.getAttribute("data-month");
//               break;
//           }
//       }

//       if (topMonth) {
//           console.log("Month at the top of the scroll:", topMonth);
//           $('#tabbtn1').text(topMonth);
//       }

//       lastScrollTop = currentScrollTop;
//     });

//   document
//     .getElementById("scrollStatus2")
//     .addEventListener("scroll", function () {
//       const currentScrollTop = this.scrollTop;
//       const windowTop = $(window).scrollTop();
//       const divTop = $("#scrollStatus2").offset().top;

//       if (currentScrollTop > lastScrollTop && windowTop > divTop) {
//         // Scrolling down and past the top of #scrollStatus
//         $(".all-events-month-show")
//           .addClass("stick extra-stick")
//           .removeClass("sticknew");
//       } else if (currentScrollTop > lastScrollTop) {
//         // Scrolling down, but not past the top of #scrollStatus
//         $(".all-events-month-show").addClass("sticknew").removeClass("stick");
//       } else if (currentScrollTop <= 0) {
//         // Scrolling back up to the top
//         $(".all-events-month-show").removeClass("sticknew extra-stick");
//       }

//       const container = document.getElementById("scrollStatus2");
//       const supports = container.getElementsByClassName("all-events-month-wise-support");
//       let topMonth = null;

//       for (let support of supports) {
//           const rect = support.getBoundingClientRect();
//           const containerRect = container.getBoundingClientRect();

//           if (rect.bottom >= containerRect.top && rect.top <= containerRect.bottom) {
//               topMonth = support.getAttribute("data-month");
//               break;
//           }
//       }

//       if (topMonth) {
//           console.log("Month at the top of the scroll:", topMonth);
//           $('#tabbtn2').text(topMonth);
//       }

//       lastScrollTop = currentScrollTop;
//     });

//   document
//     .getElementById("scrollStatus3")
//     .addEventListener("scroll", function () {
//       const currentScrollTop = this.scrollTop;
//       const windowTop = $(window).scrollTop();
//       const divTop = $("#scrollStatus3").offset().top;

//       if (currentScrollTop > lastScrollTop && windowTop > divTop) {
//         // Scrolling down and past the top of #scrollStatus
//         $(".all-events-month-show")
//           .addClass("stick extra-stick")
//           .removeClass("sticknew");
//       } else if (currentScrollTop > lastScrollTop) {
//         // Scrolling down, but not past the top of #scrollStatus
//         $(".all-events-month-show").addClass("sticknew").removeClass("stick");
//       } else if (currentScrollTop <= 0) {
//         // Scrolling back up to the top
//         $(".all-events-month-show").removeClass("sticknew extra-stick");
//       }

//       const container = document.getElementById("scrollStatus3");
//       const supports = container.getElementsByClassName("all-events-month-wise-support");
//       let topMonth = null;

//       for (let support of supports) {
//           const rect = support.getBoundingClientRect();
//           const containerRect = container.getBoundingClientRect();

//           if (rect.bottom >= containerRect.top && rect.top <= containerRect.bottom) {
//               topMonth = support.getAttribute("data-month");
//               break;
//           }
//       }

//       if (topMonth) {
//           console.log("Month at the top of the scroll:", topMonth);
//           $('#tabbtn3').text(topMonth);
//       }

//       lastScrollTop = currentScrollTop;
//     });
// });
$("#scrollStatus").on("scroll", function () {
    const container = $(this); // The scrolling container
    const supports = container.find(".all-events-month-wise-support");
    let topMonth = null;
    supports.each(function () {
        const rect = this.getBoundingClientRect();
        const containerRect = container[0].getBoundingClientRect();
        if (
            rect.bottom >= containerRect.top &&
            rect.top <= containerRect.bottom
        ) {
            topMonth = $(this).data("month");
            return false;
        }
    });
    if (topMonth) {
        console.log("Month at the top of the scroll:", topMonth);
        $("#tabbtn1").text(topMonth);
    }
});

$("#scrollStatus2").on("scroll", function () {
    const container = $(this); // The scrolling container
    const supports = container.find(".all-events-month-wise-support");
    let topMonth = null;
    supports.each(function () {
        const rect = this.getBoundingClientRect();
        const containerRect = container[0].getBoundingClientRect();
        if (
            rect.bottom >= containerRect.top &&
            rect.top <= containerRect.bottom
        ) {
            topMonth = $(this).data("month");
            return false;
        }
    });
    if (topMonth) {
        console.log("Month at the top of the scroll:", topMonth);
        $("#tabbtn2").text(topMonth);
    }
});

$("#scrollStatus3").on("scroll", function () {
    const container = $(this); // The scrolling container
    const supports = container.find(".all-events-month-wise-support");
    let topMonth = null;
    supports.each(function () {
        const rect = this.getBoundingClientRect();
        const containerRect = container[0].getBoundingClientRect();
        if (
            rect.bottom >= containerRect.top &&
            rect.top <= containerRect.bottom
        ) {
            topMonth = $(this).data("month");
            return false;
        }
    });
    if (topMonth) {
        console.log("Month at the top of the scroll:", topMonth);
        $("#tabbtn3").text(topMonth);
    }
});

function sticky_relocate() {
    var window_top = $(window).scrollTop();
    var div_top = $("#scrollStatus").offset()?.top;
    if (div_top == undefined) {
        return;
    }
    var activeTab = $(".tab-pane.active");

    console.log("Window Top:", window_top);
    console.log("Div Top:", div_top);
    console.log("Active Tab:", activeTab);

    if (activeTab.length > 0) {
        if (window_top > div_top && div_top !== 0) {
            $("#tabbtn1").addClass("stick");
        } else {
            $("#tabbtn1").removeClass("stick");
        }
    }

    var div_top2 = $("#scrollStatus2").offset().top;
    if (activeTab.length > 0) {
        if (window_top > div_top2 && div_top2 !== 0) {
            $("#tabbtn2").addClass("stick");
        } else {
            $("#tabbtn2").removeClass("stick");
        }
    }

    var div_top3 = $("#scrollStatus3").offset().top;
    if (activeTab.length > 0 && div_top3 !== 0) {
        if (window_top > div_top3) {
            $("#tabbtn3").addClass("stick");
        } else {
            $("#tabbtn3").removeClass("stick");
        }
    }
}

$(function () {
    $(window).scroll(sticky_relocate);
    sticky_relocate();
});

$(document).on("click", ".mobile-calender-btn", function () {
    $('.mobile-menu-wrp').removeClass('active');
    $('.line').removeClass('active');
    var text = this.innerText;
    var calendarSvg = `<svg viewBox="0 0 21 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M7.16406 1.66602V4.16602" stroke="white" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M13.8359 1.66602V4.16602" stroke="white" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M3.41406 7.57422H17.5807" stroke="white" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M18 7.08268V14.166C18 16.666 16.75 18.3327 13.8333 18.3327H7.16667C4.25 18.3327 3 16.666 3 14.166V7.08268C3 4.58268 4.25 2.91602 7.16667 2.91602H13.8333C16.75 2.91602 18 4.58268 18 7.08268Z" stroke="white" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M13.5762 11.4167H13.5836" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M13.5762 13.9167H13.5836" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M10.498 11.4167H10.5055" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M10.498 13.9167H10.5055" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M7.41209 11.4167H7.41957" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M7.41209 13.9167H7.41957" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>`;
    var listSvg = `<svg class="" viewBox="0 0 21 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                          <path d="M3 5.83398H18" stroke="white" stroke-width="1.5" stroke-linecap="round"/>
                          <path d="M3 10H18" stroke="white" stroke-width="1.5" stroke-linecap="round"/>
                          <path d="M3 14.166H18" stroke="white" stroke-width="1.5" stroke-linecap="round"/>
                        </svg>`;

    var $textSpan = $(this).find(".responsive-text");
    var $iconSpan = $(this).find(".responsive-icon");
    console.log($(this).html());
    if (text == "Calendar") {
        $textSpan.text("List View");
        $iconSpan.html(listSvg);
        $(".responsive-calendar").css("display", "flex");
        $(".responsive-calender-month-text").css("display", "inline-block");
    }

    if (text == "List View") {
        $textSpan.text("Calendar");
        $iconSpan.html(calendarSvg);
        $(".responsive-calendar").css("display", "none");
        $(".responsive-calender-month-text").css("display", "none");
    }
});

$(document).on("click", ".profile-calender-view", function () {
    var $textSpan = $(".mobile-calender-btn").find(".responsive-text");
    var $iconSpan = $(".mobile-calender-btn").find(".responsive-icon");
    var listSvg = `<svg class="" viewBox="0 0 21 20" fill="none" xmlns="http://www.w3.org/2000/svg">
  <path d="M3 5.83398H18" stroke="white" stroke-width="1.5" stroke-linecap="round"/>
  <path d="M3 10H18" stroke="white" stroke-width="1.5" stroke-linecap="round"/>
  <path d="M3 14.166H18" stroke="white" stroke-width="1.5" stroke-linecap="round"/>
</svg>`;

    $textSpan.text("List View");
    $iconSpan.html(listSvg);

    $(".responsive-calendar").css("display", "flex");
    $(".responsive-calender-month-text").css("display", "inline-block");
});

// $(document).on('click','.event_event_cancel_option',function(){
//   $('.event_event_cancel_option').removeClass('active_option'); // Optional: Remove active class from all others
//   $(this).addClass('active_option');
//   // $(this).addClass('active');

// })

$("#responsive-calender-months").on("scroll", function () {
    const container = $(this); // The scrolling container
    const supports = container.find(".month");
    let topMonth = null;
    supports.each(function () {
        const rect = this.getBoundingClientRect();
        const containerRect = container[0].getBoundingClientRect();
        if (
            rect.bottom >= containerRect.top &&
            rect.top <= containerRect.bottom
        ) {
            topMonth = $(this).data("month");
            return false;
        }
    });
    if (topMonth) {
        console.log("Month at the top of the scroll:", topMonth);
        $(".responsive-calender-month-text").text(topMonth);
    }
});

$(document).on("click", ".notification_read", function () {
    var user_id = $(this).data("user_id");
    $.ajax({
        url: `${base_url}update_notification_read`,
        type: "GET",
        data: { user_id: user_id },
        success: function (response) {
            console.log(response);
            
            if (response.count == 0) {
                $(".notification_count_display").css("display", "none");
                $(".notification-read-dot").css('display','none');
            }
        },
        error: function (xhr, status, error) {
            console.error("Error fetching events:", error);
            // $('.loader').css('display','none');
        },
        complete: function () {},
    });
});

// ==transaction-history==
const ctx = document.getElementById("creditChart").getContext("2d");

// Create a gradient for the border color
const gradientBorder = ctx.createLinearGradient(0, 0, 1, 0);
gradientBorder.addColorStop(0, "#FF31A6");

// Create a pattern canvas
const patternCanvas = document.createElement("canvas");
const patternContext = patternCanvas.getContext("2d");
patternCanvas.width = 18;
patternCanvas.height = 7;

// Draw dots on the pattern canvas
patternContext.fillStyle = "#FFC8DC"; // Dot color
patternContext.fillRect(0, 0, 6, 3);
patternContext.fillStyle = "#FFC8DC"; // Background color
patternContext.fillRect(0, 0, 6, 3);
patternContext.fillRect(0, 0, 6, 3);

// Create a pattern from the canvas
const pattern = ctx.createPattern(patternCanvas, "repeat");

const hiddenData = $("#graph_data").val();
const parsedData = JSON.parse(hiddenData);

// Step 2: Generate labels and data for Chart.js
const labels = parsedData.map(item => item.month); // Extract months
const data = parsedData.map(item => item.current_balance);

let lowestValue = Math.min(...data);
let highestValue = Math.max(...data);

if (lowestValue !== 0) {
    lowestValue = 0;
}

highestValue = highestValue + (highestValue * 0.3);

if (highestValue < 7) {
    highestValue += 2;
}
highestValue = Math.round(highestValue); 

let stepSize = Math.max(Math.floor((highestValue - lowestValue) / 7), 1); // Minimum stepSize of 1
if (stepSize % 2 !== 0) {
    stepSize++; // Ensure stepSize is even for better readability
}
stepSize=Math.round(stepSize);
new Chart(ctx, {
    type: "line",
    data: {
        // labels: ["Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
        labels: labels,
        datasets: [
            {
                label: "Credit Balance",
                data: data,
                // data: [400, 650, 500, 480, 490, 470, 500],
                borderColor: gradientBorder,
                backgroundColor: pattern,
                borderWidth: 4,
                pointBackgroundColor: "#ffffff",
                pointBorderColor: "#ff5ca5",
                pointBorderWidth: 5,
                pointRadius: [0, 0, 0, 0, 0, 0, 8],
                pointHoverRadius: 5,
                tension: 0,
                fill: true,
            },
        ],
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                display: false,
            },
            tooltip: {
                enabled: true,
            },
        },
        scales: {
            x: {
                grid: {
                    display: false,
                },
            },
            y: {
                // beginAtZero: true,
                min:lowestValue,
                max: highestValue,
                ticks: {
                    stepSize: stepSize, 
                },
                grid: {
                    color: "rgba(0, 0, 0, 0.05)",
                },
            },
        },
        layout: {
            padding: 20,
        },
        elements: {
            line: {
                tension: 0,
                borderWidth: 4,
            },
            point: {
                radius: 5,
            },
        },
    },
});
