$(".notification-btn").on("click", function() {
  const icon = $(this).find("i");

  // Check if the bell icon is already in "silent" mode
  if (icon.hasClass("fa-bell")) {
    icon.removeClass("fa-bell").addClass("fa-bell-slash"); // Change to silent bell icon
    $(this).html('<i class="fa-regular fa-bell-slash"></i> Enable Notifications');
  } else {
    icon.removeClass("fa-bell-slash").addClass("fa-bell"); // Change back to normal bell icon
    $(this).html('<i class="fa-regular fa-bell"></i> Silence Notifications');
  }
});

// fill-heart-icon
  $(".posts-card-like-btn").on("click", function() {
  const icon = this.querySelector('i');
  icon.classList.toggle('fa-regular');
  icon.classList.toggle('fa-solid');
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

// ===header-drodpdown===
const upcomingdropdownButton = document.getElementById(
  "upcoming-card-dropdownButton"
);
const upcomingdropdownMenu = document.querySelector(
  ".upcoming-events-card-notification-info"
);

// Toggle dropdown when clicking the button
$('#upcoming-card-dropdownButton').on('click', function(event) {
  event.stopPropagation(); // Prevent the click event from bubbling up
  $('#upcoming-events-card-notification-info').toggleClass('show');
});

// Close the dropdown when clicking outside
$(document).on("click", function(event) {
  if (!$(event.target).closest(upcomingdropdownMenu).length && !$(event.target).closest(upcomingdropdownButton).length) {
    $(upcomingdropdownMenu).removeClass("show");
  }
});

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

$(".show-comments-btn").click(function () {
    $(".posts-card-show-all-comments-wrp").toggleClass("d-none");
});


$(".show-comment-reply-btn").click(function () {
    $(".reply-on-comment").toggleClass("d-none");
});





// ===month-scroll-event===
function sticky_relocate() {
  var window_top = $(window).scrollTop();
  var div_top = $("#scrollStatus").offset().top;
  var activeTab = $(".tab-pane.active");

  if (activeTab.length > 0) {
    if (window_top > div_top) {
      const myElement = document.querySelector("#tabbtn1");
      if (myElement.classList.contains("sticknew")) {
        $("#tabbtn1").addClass("stick extra-stick").removeClass("sticknew");
      } else {
        $("#tabbtn1").addClass("stick").removeClass("sticknew");
      }
    } else {
      $("#tabbtn1").removeClass("stick");
    }
  }

  var div_top2 = $("#scrollStatus2").offset().top;
  if (activeTab.length > 0) {
    if (window_top > div_top2) {
      const myElement = document.querySelector("#tabbtn2");
      if (myElement.classList.contains("sticknew")) {
        $("#tabbtn2").addClass("stick extra-stick").removeClass("sticknew");
      } else {
        $("#tabbtn2").addClass("stick").removeClass("sticknew");
      }
    } else {
      $("#tabbtn2").removeClass("stick");
    }
  }

  var div_top3 = $("#scrollStatus3").offset().top;
  if (activeTab.length > 0) {
    if (window_top > div_top3) {
      const myElement = document.querySelector("#tabbtn3");
      if (myElement.classList.contains("sticknew")) {
        $("#tabbtn3").addClass("stick extra-stick").removeClass("sticknew");
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
  var div_top = $("#scrollStatus2").offset().top;
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
      const divTop = $("#scrollStatus").offset().top;

      if (currentScrollTop > lastScrollTop && windowTop > divTop) {
        // Scrolling down and past the top of #scrollStatus
        $(".all-events-month-show")
          .addClass("stick extra-stick")
          .removeClass("sticknew");
      } else if (currentScrollTop > lastScrollTop) {
        // Scrolling down, but not past the top of #scrollStatus
        $(".all-events-month-show").addClass("sticknew").removeClass("stick");
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
      const divTop = $("#scrollStatus2").offset().top;

      if (currentScrollTop > lastScrollTop && windowTop > divTop) {
        // Scrolling down and past the top of #scrollStatus
        $(".all-events-month-show")
          .addClass("stick extra-stick")
          .removeClass("sticknew");
      } else if (currentScrollTop > lastScrollTop) {
        // Scrolling down, but not past the top of #scrollStatus
        $(".all-events-month-show").addClass("sticknew").removeClass("stick");
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
      const divTop = $("#scrollStatus3").offset().top;

      if (currentScrollTop > lastScrollTop && windowTop > divTop) {
        // Scrolling down and past the top of #scrollStatus
        $(".all-events-month-show")
          .addClass("stick extra-stick")
          .removeClass("sticknew");
      } else if (currentScrollTop > lastScrollTop) {
        // Scrolling down, but not past the top of #scrollStatus
        $(".all-events-month-show").addClass("sticknew").removeClass("stick");
      } else if (currentScrollTop <= 0) {
        // Scrolling back up to the top
        $(".all-events-month-show").removeClass("sticknew extra-stick");
      }

      lastScrollTop = currentScrollTop;
    });
});
