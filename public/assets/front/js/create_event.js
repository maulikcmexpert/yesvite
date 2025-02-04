let eventData = {};
let isCohost = $("#isCohost").val() || "";
var total_activities = $("#TotalSedulare").val();
var category = $("#category_count").val() || 0;
var items = $("#totalCategoryItem").val() || 0;
var eventId = $("#eventID").val();
var activities = {};
var selected_co_host = $("#cohostId").val() !== "" ? $("#cohostId").val() : "";
var selected_co_host_prefer_by =
    $("#cohostpreferby").val() !== "" ? $("#cohostpreferby").val() : "";
var final_step = $("#step").val() != "" ? $("#step").val() : 1;
var isDraftEvent = $("#isDraft").val() != "" ? $("#isDraft").val() : "";
var isCopy = $("#isCopy").val() != "" ? $("#isCopy").val() : "";
eventData.isCopy = isCopy;
var swiper;
var isPhonecontact = 0;
var lengtUSer = $("#cohostId").val() !== "" ? 1 : 0;
var selected_gift = [];
var selected_user_name =
    $("#cohostFname").val() !== "" && $("#cohostLname").val() !== ""
        ? $("#cohostFname").val() + " " + $("#cohostLname").val()
        : "";
var IsPotluck = 0;
eventData.IsPotluck = 0;
var selected_profile_or_text = $("#cohostprofile").val() !== "" ? "1" : "0";

var selected_prefer_by =
    $("#cohostpreferby").val() !== "" ? $("#cohostpreferby").val() : "";
var selected_profilePhoto =
    $("#cohostprofile").val() !== "" ? $("#cohostprofile").val() : "";
var selected_dataId = $("#cohostId").val() !== "" ? $("#cohostId").val() : "";
var co_host_is_selected_close = false;
var get_contact_status = "";

var final_profilePhoto =
    $("#cohostprofile").val() !== "" ? $("#cohostprofile").val() : "";
var final_user_name =
    $("#cohostFname").val() !== "" && $("#cohostLname").val() !== ""
        ? $("#cohostFname").val() + " " + $("#cohostLname").val()
        : "";
var final_dataId = $("#cohostId").val() !== "" ? $("#cohostId").val() : "";
var final_profile_or_text = $("#cohostprofile").val() !== "" ? "1" : "0";
var final_prefer_by =
    $("#cohostpreferby").val() !== "" ? $("#cohostpreferby").val() : "";
var final_initial =
    final_user_name != ""
        ? (
              $("#cohostFname").val().charAt(0) +
              $("#cohostLname").val().charAt(0)
          ).toUpperCase()
        : "";
var create_event_phone_scroll = false;
var create_event_yesvite_scroll = false;
var create_co_event_phone_scroll = false;
var create_co_event_yesvite_scroll = false;

if (final_profile_or_text == "1") {
    $(".guest-img .selected-co-host-image").show();
    $(".guest-img .selected-co-host-image").attr("src", final_profilePhoto);
    $(".guest-img .selected-host-h5").css("display", "none");
} else {
    $(".selected-co-host-image").css("display", "none");
    $(".guest-img .selected-host-h5").text(final_initial);
    var firstinitial = final_initial.charAt(0);
    $(".guest-img .selected-host-h5").removeClass(function (index, className) {
        return (className.match(/\bfontcolor\S+/g) || []).join(" ");
    });

    // Add the new class
    $(".guest-img .selected-host-h5").addClass("fontcolor" + firstinitial);
}
if (selected_dataId != "") {
    var profilePhoto = selected_profilePhoto;
    var user_name = selected_user_name;
    var dataId = selected_dataId;
    var profile_or_text = selected_profile_or_text;
    var prefer_by = selected_prefer_by;
    // console.log(prefer_by);
    eventData.co_host = dataId;
    selected_co_host = dataId;
    selected_co_host_prefer_by = prefer_by;
    var initial = final_initial;

    if (profile_or_text == "1") {
        $(".selected-co-host-image").show();
        $(".selected-co-host-image").attr("src", profilePhoto);
        $(".selected-host-h5").css("display", "none");
    } else {
        // $(".selected-host-h5").show();
        $(".selected-co-host-image").css("display", "none");
        $(".selected-host-h5").text(initial);
    }
    $(".remove_co_host").attr("data-id", selected_co_host);
    $("#remove_co_host_id").val("user-" + selected_co_host);
    $(".selected-host-name").text(user_name);
    // $(".contactData").css("display", "flex");
    $(".guest-contacts-wrp").addClass("guest-contacts-test");

    eventData.co_host_prefer_by = prefer_by;
    if (profile_or_text == "1") {
        $(".add_new_co_host")
            .html(`<span class="mx-3"><div class="contact-img co-host-profile-photo">
                <img src="${final_profilePhoto}"
                    alt="logo">
            </div></span>
            <h5>${user_name}</h5>`);
    } else {
        $(".add_new_co_host").html(`<span class="mx-3"><div class="contact-img">
               <h5 class="fontcolor${firstinitial} add-item-under-text">${initial}</h5>
            </div></span>
            <h5>${user_name}</h5>`);
    }
    toggleSidebar();
}

var limityesvitesc = 10;
var offsetyesvitec = 0;

eventData.cutome_image = $("#design_image").val() || undefined;
eventData.textData = $("#static_information").val() || undefined;

// Ensure eventData.textData is parsed into an object before accessing properties
if (eventData.textData) {
    try {
        eventData.textData = JSON.parse(eventData.textData); // Convert string to object
    } catch (error) {
        console.error("Error parsing textData:", error);
        eventData.textData = {}; // Fallback to empty object if parsing fails
    }
}

// Now safely check if textElements exists
if (eventData.textData != undefined && !eventData?.textData?.textElements) {
    // alert("updated");
    eventData.textData.textElements = eventData?.textData?.textData; // Correct assignment

    console.log(eventData.textData.textElements); // Should now log correct data
    console.log(eventData.textData); // Should log parsed object
}
eventData.step = $("#step").val();
eventData.thank_you_card_id = $("#thankuCardId").val() || undefined;

var giftRegestryDataRaw = $('input[name="giftRegestryData[]"]')
    .map(function () {
        return $(this).val();
    })
    .get();

if (giftRegestryDataRaw != null && giftRegestryDataRaw?.length > 0) {
    try {
        var giftRegestryData = JSON.parse(giftRegestryDataRaw);
        giftRegestryData?.forEach(function (item) {
            selected_gift.push({
                gr_id: item,
            });
        });
        eventData.gift_registry_data = selected_gift;
    } catch (e) {
        console.error("Invalid JSON data:", e);
    }
}

// var selected_profile_or_text = "";
// var selected_prefer_by = "";
var eventEditId = $("#eventEditId").val();
var inviteTotalCount = $("#inviteTotalCount").val();
$(".invite-count").text(inviteTotalCount);
$("#event_guest_count").text(inviteTotalCount + " Guests");
var isSetSession = 0;
eventData.allow_limit_count = $("#allow_limit_count").val();
$("#activity-start-time").val("");
$("#activity-end-time").val("");
$(document).ready(function () {
    function getTimeZoneAbbreviation() {
        const date = new Date();
        const offset = -date.getTimezoneOffset();
        const hours = Math.floor(offset / 60);
        const minutes = Math.abs(offset % 60);
        const sign = offset >= 0 ? "+" : "-";

        const gmtOffset = `GMT${sign}${String(hours).padStart(2, "0")}:${String(
            minutes
        ).padStart(2, "0")}`;

        const options = { timeZoneName: "short" };
        const formatter = new Intl.DateTimeFormat("en-US", options);
        const parts = formatter.formatToParts(date);
        const abbreviation = parts.find((part) => part.type === "timeZoneName");

        return abbreviation ? abbreviation.value : gmtOffset;
    }

    const currentTimeZone = getTimeZoneAbbreviation();
    let isOptionExists = false;

    $("#start-time-zone option").each(function () {
        if ($(this).val() === currentTimeZone) {
            $(this).prop("selected", true);
            isOptionExists = true;
            return false;
        }
    });

    if (!isOptionExists) {
        const newOption = $("<option></option>")
            .val(currentTimeZone)
            .text(currentTimeZone)
            .prop("selected", true);
        $("#start-time-zone").append(newOption);
    }

    let isOptionExistsend = false;
    $("#end-time-zone option").each(function () {
        if ($(this).val() === currentTimeZone) {
            $(this).prop("selected", true);
            isOptionExists = true;
            return false;
        }
    });

    if (!isOptionExistsend) {
        const newEndOption = $("<option></option>")
            .val(currentTimeZone)
            .text(currentTimeZone)
            .prop("selected", true);
        $("#end-time-zone").append(newEndOption);
    }

    console.log(getTimeZoneAbbreviation());

    if (performance.navigation.type == performance.navigation.TYPE_RELOAD) {
        //  alert(design);
        $(".user_choice").prop("checked", false);
        $("#YesviteUserAll").html("");
        $.ajax({
            url: base_url + "event/delete-session",
            type: "POST",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            data: {
                session_key: [
                    "user_ids",
                    "category",
                    "category_item",
                    "gift_registry_data",
                    "thankyou_card_data",
                ],
                // image:[design]
                // "user_ids","category","category_item,"gift_registry_data"
            },

            success: function (response) {
                if (response.success) {
                    console.log(response.message);
                } else {
                    console.log("Failed to delete session.");
                }
            },
            error: function (xhr, status, error) {
                console.log("AJAX error: " + error);
            },
        });
    }
    var current_step = $("#current_step").val();
    var event_id = $("#event_id").val();
    if (event_id != "" && current_step != "") {
        var eventDetail = $("#eventDetail").val();
        eventDetail = JSON.parse(eventDetail);
        eventData.event_id = event_id;
        eventData.event_type = eventDetail.event_type_id;
        eventData.event_name = eventDetail.event_name;
        eventData.hosted_by = eventDetail.hosted_by;
        eventData.event_date = eventDetail.start_date;
        eventData.rsvp_by_date_set = eventDetail.rsvp_by_date_set;
        eventData.rsvp_by_date = eventDetail.rsvp_by_date;
        eventData.start_time = eventDetail.rsvp_start_time;
        eventData.rsvp_start_timezone = eventDetail.rsvp_start_timezone;
        eventData.rsvp_end_time_set = eventDetail.rsvp_end_time_set;
        eventData.rsvp_end_time = eventDetail.rsvp_end_time;
        eventData.rsvp_end_timezone = eventDetail.rsvp_end_timezone;
        eventData.event_location = eventDetail.event_location_name;
        eventData.address1 = eventDetail.address_1;
        eventData.address_2 = eventDetail.address_2;
        eventData.state = eventDetail.state;
        eventData.zipcode = eventDetail.zip_code;
        eventData.city = eventDetail.city;
        eventData.message_to_guests = eventDetail.message_to_guests;
        eventData.longitude = eventDetail.longitude;
        eventData.latitude = eventDetail.latitude;
        final_step = eventDetail.step;
        // console.log(eventData);
        // console.log(eventDetail);
        if (current_step == "2") {
            $(".step_1").hide();
            console.log("handleActiveClass");

            handleActiveClass(".li_design");
            $(".pick-card").addClass("active");
            $(".design-span").addClass("active");
            $(".li_event_detail")
                .find(".side-bar-list")
                .addClass("menu-success");
            $(".li_event_detail").addClass("menu-success");
            $(".step_2").show();
            $(".event_create_percent").text("50%");
            $(".current_step").text("2 of 4");
            active_responsive_dropdown(
                "drop-down-event-design",
                "drop-down-pick-card"
            );

            if (final_step == 1) {
                final_step = 2;
            }
        } else if (current_step == "3") {
            $("#myCustomModal").modal("hide");
            $("#exampleModal").modal("hide");
            $("#loader").css("display", "none");
            $(".store_desgin_temp").prop("disabled", false);
            $(".btn-close").prop("disabled", false);
            $(".main-content-wrp").removeClass("blurred");
            $(".step_1").hide();
            $(".step_2").hide();
            $("#edit-design-temp").hide();
            console.log("handleActiveClass");

            handleActiveClass(".li_guest");
            $(".pick-card").addClass("menu-success");
            $(".edit-design").addClass("menu-success");
            $(".edit-design").removeClass("active");
            $(".li_event_detail")
                .find(".side-bar-list")
                .addClass("menu-success");
            $(".li_event_detail").addClass("menu-success");

            $(".li_design").find(".side-bar-list").addClass("menu-success");
            $(".li_design").addClass("menu-success");
            active_responsive_dropdown("drop-down-event-guest");
            $(".event_create_percent").text("75%");
            $(".current_step").text("3 of 4");
            $(".step_3").show();
            final_step = 3;
            var type = "all";
            get_user(type);
        }
    }
    $("#address1").attr("placeholder", "");
    $(".search_user").val("");
});

var swiper = new Swiper(".mySwiper2", {
    slidesPerView: 3.5,
    spaceBetween: 20,
    loop: true,
    navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
    },
    breakpoints: {
        320: {
            slidesPerView: 1.5,
        },

        576: {
            slidesPerView: 2.5,
        },

        768: {
            slidesPerView: 3.5,
        },

        992: {
            slidesPerView: 2,
        },

        1200: {
            slidesPerView: 2,
        },

        1400: {
            slidesPerView: 3.5,
        },
    },
});
// var swiper = new Swiper(".mySwiper", {
//     slidesPerView: 3.5,
//     spaceBetween: 20,
//     loop: true,
//     loopFillGroupWithBlank: false, // Prevents blank slides at the end
//     navigation: {
//         nextEl: ".swiper-button-next",
//         prevEl: ".swiper-button-prev",
//     },
//     breakpoints: {
//         320: {
//             slidesPerView: 1.5,
//         },
//         576: {
//             slidesPerView: 2.5,
//         },
//         768: {
//             slidesPerView: 3.5,
//         },
//         992: {
//             slidesPerView: 2,
//         },
//         1200: {
//             slidesPerView: 2,
//         },
//         1400: {
//             slidesPerView: 3.5,
//         },
//     },
// });

$(document).on("click", ".create-event-btn", function () {
    toggleSidebar("sidebar_create_event");
});

// Delete Group functionality
$(document).on("click", "#delete_group", function (e) {
    e.stopPropagation();
    var group_id = $(this).data("id");
    $.ajax({
        url: base_url + "event/delete_group",
        type: "POST",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        data: {
            group_id: group_id,
        },
        success: function (response) {
            if (response.status == "1") {
                $(".added_group" + group_id).remove();

                var grplth = $(".group_list .listgroups").length;
                if (grplth == 0) {
                    $(".group_list").html("No data found");
                }
                var sliderIndex = $(
                    '.group-card.view_members[data-id="' + group_id + '"]'
                )
                    .closest(".swiper-slide") // Find the nearest swiper-slide parent
                    .attr("data-swiper-slide-index"); // Get its data-swiper-slide-index attribute

                swiper[0].removeSlide(sliderIndex);
                swiper[1].removeSlide(sliderIndex);
                swiper[2].removeSlide(sliderIndex);
                swiper[0].update(); // Update Swiper after removing the slide
                swiper[1].update(); // Update Swiper after removing the slide
                swiper[2].update(); // Update Swiper after removing the slide
            }
        },
        error: function (xhr, status, error) {
            console.log("AJAX error: " + error);
        },
    });
});

// Add New Group functionality
$(document).on("click", ".add_new_group_member", function () {
    var group_name = $("#new_group_name").val();
    var selectedValues = [];
    $("#groupUsers .user_group_member:checked").each(function () {
        selectedValues.push({
            id: $(this).val(),
            prefer_by: $(this).data("preferby"),
        });
    });
    if (selectedValues.length > 0) {
        $.ajax({
            url: base_url + "event/add_new_group",
            type: "POST",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            data: {
                groupmember: selectedValues,
                groupname: group_name,
            },
            success: function (response) {
                if (response.status == "1") {
                    var grplth = $(".group_list .listgroups").length;
                    if (grplth == 0) {
                        $(".group_list").html("");
                    }
                    $(".group_list").append(response.view);

                    var newItem = `
                        <div class="swiper-slide">
                            <div class="group-card view_members" data-id="${response.data.group_id}">
                                <div>
                                    <h4>${response.data.groupname}</h4>
                                    <p>${response.data.member_count} Guests</p>
                                </div>
                                <span class="ms-auto">
                                    <svg width="16" height="17" viewBox="0 0 16 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M5.93994 13.7797L10.2866 9.43306C10.7999 8.91973 10.7999 8.07973 10.2866 7.56639L5.93994 3.21973" stroke="#E2E8F0" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </span>
                            </div>
                        </div>
                    `;

                    swiper[0].appendSlide(newItem);
                    swiper[0].update(); // Update Swiper after adding the new slide
                    swiper[1].appendSlide(newItem);
                    swiper[1].update(); // Update Swiper after adding the new slide
                    swiper[2].appendSlide(newItem);
                    swiper[2].update(); // Update Swiper after adding the new slide

                    $(".user_choice_group .user_choice").prop("checked", false);
                    toggleSidebar("sidebar_groups");
                    groupToggleSearch("");
                }
            },
            error: function (xhr, status, error) {
                console.log("AJAX error: " + error);
            },
        });
    } else {
        toastr.error("Please select Member");
    }
});

$(document).on("focus", ".inputText", function () {
    $(this).next().addClass("floatingfocus");
});

$(document).on("blur", ".inputText", function () {
    if ($(this).val() == "") {
        $(this).next().removeClass("floatingfocus");
    }
});
// $(function() {
//     $('#event-date').daterangepicker({
//         autoUpdateInput: false,
//         locale: {
//             format: 'MM/DD/YYYY'
//         },
//         startDate: moment().startOf('month'),
//         endDate: moment().endOf('month')
//     }, function(start, end, label) {
//         $('#event-date').val(start.format('YYYY-MM-DD') + ' To ' + end.format('YYYY-MM-DD'));
//     });

//     $('#event-date').on('apply.daterangepicker', function(ev, picker) {
//         $(this).val(picker.startDate.format('YYYY-MM-DD') + ' To ' + picker.endDate.format('YYYY-MM-DD'));
//     });
// });
// $(function() {
//     var selectedDates = new Set();
//     var today = new Date();

//     $("#event-date").datepicker({
//         numberOfMonths: 1,
//         showButtonPanel: true,
//         minDate: today,
//         onSelect: function(dateText, inst) {
//             var date = $(this).datepicker("getDate");
//             var formattedDate = $.datepicker.formatDate("yy-mm-dd", date);

//             if (selectedDates.has(formattedDate)) {
//                 selectedDates.delete(formattedDate);
//             } else {
//                 if (selectedDates.size < 2) {
//                     selectedDates.add(formattedDate);
//                 } else {
//                     $(this).datepicker("setDate", null);
//                     return;
//                 }
//             }

//             var sortedDates = [...selectedDates].sort();
//             $(this).val(sortedDates.join(" To "));
//             // $(".scheduleform").empty();
//             // $(".activity_bar").empty();  // Clear previous activities
//             $(".activity_bar").children().not(".toggle-wrp").remove();

//             if (sortedDates.length > 0) {
//                 var startDate = new Date(sortedDates[0]);
//                 var endDate =
//                     sortedDates.length === 2 ?
//                     new Date(sortedDates[1]) :
//                     new Date(sortedDates[0]);

//                 // Loop through the date range, including startDate and endDate
//                 while (startDate <= endDate) {
//                     var dateID = $.datepicker
//                         .formatDate("yy-mm-dd", startDate)
//                         .replace(/-/g, "");

//                     var formHtml = `
//                      <div class="activity-schedule-wrp">
//   <div class="activity-schedule-head">
//     <h3>${$.datepicker.formatDate("DD - MM d, yy", startDate)}</h3>
//   </div>
//   <div class="activity-schedule-inner new_event_detail_form">
//     <form>
//      ${
//          startDate.getTime() === new Date(sortedDates[0]).getTime()
//              ? `
//                                 <h4>Event Start</h4>
//                                 <div class="row">
//                                     <div class="col-12 mb-4">
//                                         <div class="input-form">
//                                             <input type="time" class="form-control inputText" id="ac-start-time" name="ac-start-time" required="" />
//                                             <label for="start-time" class="form-label input-field floating-label select-label">Start *</label>
//                                         </div>
//                                     </div>
//                                 </div>
//                                 `
//              : ""
//      }
//         <div class="accordion" id="accordionExample">
//           <div class="accordion-item">
//             <div class="accordion-header">
//               <button
//                 class="accordion-button collapsed"
//                 type="button"
//                 data-bs-toggle="collapse"
//                 data-bs-target="#collapseOne${dateID}"
//               >
//                 <div>Activities <span>(3)</span></div>
//                 <i class="fa-solid fa-angle-down"></i>
//               </button>
//               <div class="accordion-button-icons add_more_activity" data-id="${dateID}">
//                 <i class="fa-solid fa-circle-plus"></i>
//               </div>
//             </div>
//             <div
//               id="collapseOne${dateID}"
//               class="accordion-collapse collapse"
//               data-bs-parent="#accordionExample"
//             >
//               <div class="accordion-body new_activity" id="${dateID}" data-id="${$.datepicker.formatDate(
//                         "yy-mm-dd",
//                         startDate
//                     )}">

//               </div>
//             </div>
//           </div>
//         </div>

//         ${
//             startDate.getTime() ===
//             new Date(sortedDates[sortedDates.length - 1]).getTime()
//                 ? `
//                                 <h4 class="mt-3 ac-end-time" style="display:none">Event Ends</h4>
//                                 <div class="col-12 ac-end-time" style="display:none">
//                                     <div class="input-form">
//                                         <input type="time" class="form-control inputText" id="ac-end-time" name="ac-end-time" required="" />
//                                         <label for="end-time" class="form-label input-field floating-label select-label">End Time</label>
//                                     </div>
//                                 </div>
//                                 `
//                 : ""
//         }
//       </div>
//     </form>
//   </div>
// </div>

//                     `;

//                     $(".activity_bar").append(formHtml);

//                     // Increment the date by one day
//                     startDate.setDate(startDate.getDate() + 1);
//                 }

//                 var save_btn = `<div class="activity-schedule-inner-btn">
//                             <button class="cmn-btn" onclick="toggleSidebar()" id="save_activity_schedule">
//                                Save
//                             </button>
//                         </div>`;

//                 $(".activity_bar").append(save_btn);
//             }
//         },
//     });
// });

//   $('.event_time').timepicker({
//     showInputs: false
//   });
//   $('.event_time').val('')

// $('.event_time').timepicker({
//     autoclose: true,
//     //showSeconds: true,
//     minuteStep: 1
// });

// var firstOpen = true;
// var time;
// $('.event_time').datetimepicker({
//     useCurrent: false,
//     format: "hh:mm A"
// }).on('dp.show', function() {
//     if(firstOpen) {
//         time = moment().startOf('day');
//         firstOpen = false;
//     } else {
//         time = "01:00 PM"
//     }
//     // $(this).data('DateTimePicker').date(time);
// });

if (/Mobi/.test(navigator.userAgent)) {
    // if mobile device, use native pickers
    $(".date input").attr("type", "date");
    // $(".time input").attr("type", "time");
} else {
    // if desktop device, use DateTimePicker
    // $("#datepicker").datetimepicker({
    //   useCurrent: false,
    //   format: "DD-MMM-YYYY",
    //   showTodayButton: true,
    //   icons: {
    //     next: "fa fa-chevron-right",
    //     previous: "fa fa-chevron-left",
    //     today: 'todayText',
    //   }
    // });
    // $(".timepicker").datetimepicker({
    //     // keepOpen: true,
    //     format: "LT",
    //     icons: {
    //         up: "fa fa-chevron-up",
    //         down: "fa fa-chevron-down",
    //     },
    //     // debug: true
    // });
    // $('.bootstrap-datetimepicker-widget').on('click', function(e) {
    //     e.stopPropagation();  // Prevents closing the picker on click inside the widget
    // });
}

// $(document).on('click','.timepicker', function(){
//    datepicker();
// })
// datepicker();
function getClosest15MinuteTime() {
    const now = new Date();
    const minutes = now.getMinutes();
    const roundedMinutes = Math.ceil(minutes / 15) * 15; // Round up to the nearest 15 minutes
    if (roundedMinutes === 60) {
        now.setHours(now.getHours() + 1); // Increment the hour if rounded to 60
        now.setMinutes(0); // Reset minutes to 0
    } else {
        now.setMinutes(roundedMinutes);
    }
    now.setSeconds(0); // Reset seconds to 0
    now.setMilliseconds(0); // Reset milliseconds to 0
    return now;
}
// $(".timepicker").on("dp.show", function () {
//     $(this).val(""); // Clear the input when the picker is shown
// });
// function datepicker() {
//     $(".timepicker")
//         .datetimepicker({
//             //  keepOpen: true,
//             format: "LT",
//             icons: {
//                 up: "fa fa-chevron-up",
//                 down: "fa fa-chevron-down",
//             },
//             useCurrent: false,
//             ignoreReadonly: true,
//             stepping: 15,
//             // defaultDate: getClosest15MinuteTime(), // Set the closest 15-minute time as the default

//             // Set stepping to 15 minutes
//             // defaultDate: now
//             //  debug: true
//         })
//         .on("dp.show", function () {
//             const picker = $(this).data("DateTimePicker");
//             // const closest15MinTime = getClosest15MinuteTime();
//             //         const closest15MinTime = moment().hours(12).minutes(0).seconds(0);

//             // // Set the picker to the closest 15-minute time dynamically
//             // picker.date(closest15MinTime);
//             const startTime = $(".start_timepicker").val();
//             const startMoment = startTime ? moment(startTime, "LT") : moment().hours(12).minutes(0).seconds(0);
//             const closest15MinTime = startMoment.clone().add(1, 'hours');
//              picker.date(closest15MinTime);
//         })
//         .on("dp.hide", function (e) {
//             // Automatically set the selected value in the input field when the picker closes
//             // const selectedTime = e.date ? e.date.format("LT") : ""; // Format the selected time
//             // $(this).val(selectedTime); // Set the formatted time value in the input field
//         });

//     // Ensure input field is clear when the page loads
//     $(this).val("");
//     $(this).val("");
// }
// datepicker();

// function datepicker() {
//     $(".timepicker.activity_start_time").each(function (index) {
//         const startPicker = $(this).datetimepicker({
//             format: "LT",
//             icons: {
//                 up: "fa fa-chevron-up",
//                 down: "fa fa-chevron-down",
//             },
//             useCurrent: false,
//             ignoreReadonly: true,
//             stepping: 15,
//         })
//         .on("dp.show", function () {
//             const picker = $(this).data("DateTimePicker");
//             const previousEndTime = index > 0 ? $(".activity_end_time").eq(index - 1).val() : "";

//             // If previous end time exists, set current start time 1 hour after it
//             if (previousEndTime) {
//                 const previousEndMoment = moment(previousEndTime, "LT");
//                 picker.date(previousEndMoment.add(1, 'hours'));
//             } else {
//                 // Default to current time or a specific default time
//                 picker.date(moment().hours(12).minutes(0).seconds(0));
//             }
//         })
//         .on("dp.change", function (e) {
//             const selectedStartTime = e.date ? e.date : moment().hours(12).minutes(0).seconds(0);
//             const endTimePicker = $(".activity_end_time").eq(index).data("DateTimePicker");

//             // Set the end time picker to one hour after the selected start time
//             const endTime = selectedStartTime.clone().add(1, 'hours');
//             endTimePicker.date(endTime);
//         });

//         // Ensure input field is clear when the page loads
//         $(this).val("");
//     });

//     $(".timepicker.activity_end_time").each(function (index) {
//         const endPicker = $(this).datetimepicker({
//             format: "LT",
//             icons: {
//                 up: "fa fa-chevron-up",
//                 down: "fa fa-chevron-down",
//             },
//             useCurrent: false,
//             ignoreReadonly: true,
//             stepping: 15,
//         })
//         .on("dp.show", function () {
//             const picker = $(this).data("DateTimePicker");
//             const startTime = $(this).closest("div").find(".activity_start_time").val();
//             const startMoment = startTime ? moment(startTime, "LT") : moment().hours(12).minutes(0).seconds(0);

//             // Set end time to 1 hour after start time if it's empty
//             picker.date(startMoment.clone().add(1, 'hours'));
//         })
//         .on("dp.change", function (e) {
//             const selectedEndTime = e.date ? e.date : moment().hours(12).minutes(0).seconds(0);
//             $(this).val(selectedEndTime.format("LT"));

//             // Set the next start time based on the selected end time
//             const nextStartTime = $(".activity_start_time").eq(index + 1);
//             if (nextStartTime.length) {
//                 const startPicker = nextStartTime.data("DateTimePicker");
//                 const newStartTime = selectedEndTime.clone().add(1, 'hours');
//                 startPicker.date(newStartTime);
//             }
//         });

//         // Ensure input field is clear when the page loads
//         $(this).val("");
//     });
// }
function datepicker() {
    $(".timepicker.activity_start_time").each(function (index) {
        const endPicker = $(this)
            .datetimepicker({
                format: "LT",
                icons: {
                    up: "fa fa-chevron-up",
                    down: "fa fa-chevron-down",
                },
                useCurrent: false,
                ignoreReadonly: true,
                stepping: 15,
            })
            .on("dp.show", function () {
                const picker = $(this).data("DateTimePicker");
                const currentActivity = $(this).closest(".activity-main-wrp");

                const startTime = $(this)
                    .closest("div")
                    .find("#ac-start-time")
                    .val();
                // const startMoment = startTime ? moment(startTime, "LT") : moment().hours(12).minutes(0).seconds(0);
                let startMoment = startTime
                    ? moment(startTime, "LT")
                    : moment().hours(12).minutes(0).seconds(0);
                const previousActivity =
                    currentActivity.prev(".activity-main-wrp");

                if (previousActivity.length > 0) {
                    const previousEndTime = previousActivity
                        .find(".activity_end_time")
                        .val();
                    if (previousEndTime) {
                        // If previous end time exists, use it as the new start time
                        // startMoment = moment(previousEndTime, "LT");
                        startMoment = moment(previousEndTime, "LT").add(
                            1,
                            "hours"
                        );
                        picker.date(startMoment);
                    }
                } else {
                    picker.date(startMoment.clone().add(1, "hours"));
                }

                // Set the picker date to the adjusted start time (if any)
            })
            .on("dp.close", function () {
                const picker = $(this).data("DateTimePicker");
                const startTime = $(this)
                    .closest("div")
                    .find(".activity_start_time")
                    .val();
                const startMoment = startTime
                    ? moment(startTime, "LT")
                    : moment().hours(12).minutes(0).seconds(0);

                $(this).val(startMoment);
            });
    });

    $(".timepicker.activity_end_time").each(function (index) {
        const endPicker = $(this)
            .datetimepicker({
                format: "LT",
                icons: {
                    up: "fa fa-chevron-up",
                    down: "fa fa-chevron-down",
                },
                useCurrent: false,
                ignoreReadonly: true,
                stepping: 15,
            })
            .on("dp.show", function () {
                const picker = $(this).data("DateTimePicker");

                // Get the start time from the related input field
                const startTime = $(this)
                    .closest(".activity-main-wrp")
                    .find('input[name="activity-start-time[]"]')
                    .val();
                const emdtMoment = moment(startTime, "LT");

                console.log("Start time:", startTime);

                // If start time exists, use it; otherwise, default to 12:00 PM
                const startMoment = startTime
                    ? moment(startTime, "LT")
                    : moment().hours(12).minutes(0).seconds(0);

                // Set the end time to 1 hour after the start time, only when picker is first shown
                if (!picker.date()) {
                    // Check if the picker date is empty (first time showing)
                    picker.date(startMoment.clone().add(1, "hours"));
                }
            })
            // .on("dp.show", function () {
            //     const picker = $(this).data("DateTimePicker");
            //     // const startTime = $(this).closest("div").find(".activity_start_time").val();
            //     const startTime =   $(this)
            //     .closest(".activity-main-wrp")
            //     .find('input[name="activity-start-time[]"]')
            //     .val();

            //     console.log(startTime);
            //     const startMoment = startTime ? moment(startTime, "LT") : moment().hours(12).minutes(0).seconds(0);

            //     // Set the end time to 1 hour after the start time whenever the end time picker is shown
            //     picker.date(startMoment.clone().add(1, "hours"));
            // })

            .on("dp.close", function () {
                alert();
                // const picker = $(this).data("DateTimePicker");
                // const startTime = $(this).closest("div").find(".activity_start_time").val();
                // const startMoment = moment(startTime, "LT") : moment().hours(12).minutes(0).seconds(0);
                // const startMoment = moment(startTime, "LT") ;

                // picker.date(startMoment.clone().add(1, "hours"));

                // const currentActivity = $(this).closest(".activity-main-wrp");
                // const nextActivity = currentActivity.next(".activity-main-wrp"); // Find the next activity
                // if (nextActivity.length > 0) {
                //     const nextStartPicker = nextActivity.find(".activity_start_time");
                //     if (nextStartPicker.length > 0) {
                //         const nextStartMoment = startMoment.clone().add(1, "hours");
                //         nextStartPicker.val(nextStartMoment.format("LT")); // Update next activity's start_time
                //     }
                // }

                // Set end time to 1 hour after start time if it's empty (only in picker)
                // $(this).val(startMoment);
                const picker = $(this).data("DateTimePicker");

                // Get the selected end time directly from the picker
                const selectedEndTime = picker.date();

                if (selectedEndTime) {
                    // If a time is selected, update the input field with the selected time
                    $(this).val(selectedEndTime.format("LT"));
                } else {
                    // If no time is selected, set it to 1 hour after start time
                    const startTime = $(this)
                        .closest(".activity-main-wrp")
                        .find('input[name="activity-start-time[]"]')
                        .val();
                    const startMoment = moment(startTime, "LT");
                    $(this).val(
                        startMoment.clone().add(1, "hours").format("LT")
                    );
                }

                console.log(selectedEndTime);
                console.log(selectedEndTime);
            });
    });
}

function startTimePicker() {
    $(".start_timepicker")
        .datetimepicker({
            format: "LT",
            useCurrent: false,
            ignoreReadonly: true,
            stepping: 15,
            icons: {
                up: "fa fa-chevron-up",
                down: "fa fa-chevron-down",
            },
        })
        .on("dp.show", function () {
            // const picker = $(this).data("DateTimePicker");
            // const currentValue = $(this).val();

            // if (currentValue) {
            //     const currentMoment = moment(currentValue, "LT");
            //     if (currentMoment.isValid()) {
            //         picker.date(currentMoment);
            //     }
            // } else {
            // $(this).val("");
            // $(this)
            //     .data("DateTimePicker")
            //     .date(moment().hours(12).minutes(0).seconds(0));
            // }
            var picker = $(this).data("DateTimePicker");
            var currentValue = $(this).val();

            if (currentValue) {
                var currentMoment = moment(currentValue, "LT");
                if (currentMoment.isValid()) {
                    picker.date(currentMoment); // Keep the existing valid value
                }
            } else {
                $(this).val("");
                picker.date(moment().hours(12).minutes(0).seconds(0)); // Set default value
            }
        })
        .on("dp.hide", function (e) {
            const selectedTime = e.date ? e.date.format("LT") : "";
            $(this).val(selectedTime);
            $(this).data(selectedTime);
            const selectedStartTime = e.date
                ? e.date
                : moment().hours(12).minutes(0).seconds(0);
            const endTimePicker = $(".end_timepicker").data("DateTimePicker");
            endTimePicker.date(selectedStartTime.clone().add(1, "hours"));
        });
}
function endTimePicker() {
    $(".end_timepicker")
        .datetimepicker({
            format: "LT",
            useCurrent: false,
            ignoreReadonly: true,
            stepping: 15,
            icons: {
                up: "fa fa-chevron-up",
                down: "fa fa-chevron-down",
            },
        })
        .on("dp.show", function () {
            const picker = $(this).data("DateTimePicker");
            const currentValue = $(this).val();

            if (currentValue) {
                const currentMoment = moment(currentValue, "LT");
                if (currentMoment.isValid()) {
                    picker.date(currentMoment);
                }
            } else {
                const startTime = $(".start_timepicker").val();
                const startMoment = startTime
                    ? moment(startTime, "LT")
                    : moment().hours(12).minutes(0).seconds(0);
                picker.date(startMoment.clone().add(1, "hours"));
            }
        })
        .on("dp.hide", function (e) {
            const selectedTime = e.date ? e.date.format("LT") : "";
            $(this).val(selectedTime);
        });
}

$(document).ready(function () {
    startTimePicker();
    datepicker();
    endTimePicker();
});

// datepicker();
// start_timepicker();

// flatpickr(".event_time", {
//     enableTime: true,
//     noCalendar: true,
//     dateFormat: "h:i K", // Format with AM/PM
//     time_24hr: false, // 12-hour format with AM/PM
//     minuteIncrement: 15, // Set 15-minute intervals
// });

function rsvp_by_date(start_time) {
    var adjustedStartTime = moment(start_time).subtract(1, "days");

    $("#rsvp-by-date").daterangepicker(
        {
            singleDatePicker: true,
            autoUpdateInput: false,
            //   showDropdowns: true,
            minYear: 2024,
            maxDate: adjustedStartTime,
            minDate: moment(),
            minDate: moment().add(0, "days"),
            locale: {
                format: "MM-DD-YYYY", // Set the desired format
            },
            maxYear: parseInt(moment().format("YYYY"), 10),
        },
        function (start, end, label) {
            //   var years = moment().diff(start, 'years');
            //   alert("You are " + years + " years old!");
        }
    );
    $("#rsvp-by-date").on("apply.daterangepicker", function (ev, picker) {
        $(this).val(picker.startDate.format("MM-DD-YYYY"));
        $("#rsvp-by-date").next().addClass("floatingfocus");
    });
    $("#rsvp-by-date").on("hide.daterangepicker", function (ev, picker) {
        // if (picker.startDate.isValid()) {
        //     $(this).val(picker.startDate.format("MM-DD-YYYY"));
        //     $("#rsvp-by-date").next().addClass("floatingfocus");
        // }
    });
}

$(function () {
    var current_event_date = $("#event-date").val();

    $("#rsvp-by-date").daterangepicker(
        {
            singleDatePicker: true,
            autoUpdateInput: false,
            //   showDropdowns: true,
            minYear: 1901,
            //   maxDate: current_event_date,
            //   minDate: moment().add(0, 'days'),
            minDate: moment().startOf("month"),
            // endDate: moment().endOf("month"),
            // minDate: moment().add(1, 'days'),
            minDate: moment(),
            locale: {
                // format: 'YYYY-MM-DD'  // Set the desired format
                format: "MM-DD-YYYY", // Set the desired format
            },
            maxYear: parseInt(moment().format("YYYY"), 10),
        },
        function (start, end, label) {
            //   var years = moment().diff(start, 'years');
            //   alert("You are " + years + " years old!");
        }
    );
    $("#rsvp-by-date").on("apply.daterangepicker", function (ev, picker) {
        // $(this).val(picker.startDate.format('YYYY-MM-DD'));
        $(this).val(picker.startDate.format("MM-DD-YYYY"));
        $("#rsvp-by-date").next().addClass("floatingfocus");
    });
    $("#rsvp-by-date").on("hide.daterangepicker", function (ev, picker) {
        // if (picker.startDate.isValid()) {
        //     // $(this).val(picker.startDate.format('YYYY-MM-DD'));
        //     $(this).val(picker.startDate.format("MM-DD-YYYY"));
        //     $("#rsvp-by-date").next().addClass("floatingfocus");
        // }
    });
});

// $(function () {
//     var selectedDates = new Set();
//     let ed = document.getElementById("event-date");
//     var oldDate = $(ed).attr("data-isDate");
//     $("#event-date").daterangepicker(
//         {
//             autoUpdateInput: false,
//             locale: {
//                 format: "MM/DD/YYYY",
//             },
//             showDropdowns: false,
//             startDate: moment().startOf("month"),
//             minDate: moment(),
//             maxSpan: { days: 2 },
//             minSpan: { days: 1 },
//             singleDatePicker: false, // Start with range picker
//             isInvalidDate: function (date) {
//                 return date.isBefore(moment(), "day"); // Disable past dates
//             },
//             // Event to handle Apply button enable/disable
//             applyButtonClasses: "btn-primary", // Set your button class as needed
//         },
//         function (start, end, label) {
//             if (start.isSame(end, "day")) {
//                 // Single date selected
//                 $("#apply-button").prop("disabled", false); // Enable Apply button
//             } else if (start.isBefore(end)) {
//                 // Multiple dates selected
//                 $("#apply-button").prop("disabled", false); // Enable Apply button
//             }
//             // const isDate = $(this)  // Get the data attribute inside the callback
//             if (start.diff(end, "days") === 0) {
//                 end = start;
//             }
//             selectedDates.clear();
//             // selectedDates.add(start.format("YYYY-MM-DD"));
//             // selectedDates.add(end.format("YYYY-MM-DD"));
//             // var eventDate = start.format("YYYY-MM-DD") + " To " + end.format("YYYY-MM-DD")
//             selectedDates.add(start.format("MM-DD-YYYY"));
//             selectedDates.add(end.format("MM-DD-YYYY"));
//             var eventDate =
//                 start.format("MM-DD-YYYY") + " To " + end.format("MM-DD-YYYY");
//             rsvp_by_date(start.format("MM-DD-YYYY"));
//             if (start.format("MM-DD-YYYY") == end.format("MM-DD-YYYY")) {
//                 eventDate = end.format("MM-DD-YYYY");
//             }
//             $("#event-date").val(eventDate);
//             $(".step_1_activity").html(
//                 '<span><i class="fa-solid fa-triangle-exclamation"></i></span>Setup activity schedule'
//             );

//             $("#event-date").val(eventDate).trigger("change");

//             $(".activity_bar").children().not(".toggle-wrp").remove();
//             // $('#schedule').prop("checked",false);
//             // $('.add-activity-schedule').hide();
//             if (oldDate != "") {
//                 $("#isnewdata").show();
//                 $("#isolddata").hide();
//             }
//             // alert();
//             $("#end_time").prop("checked", false);
//             $(".end-time-create").val("");
//             $(".start-time-create").val("");
//             $(".end_time").css("display", "none");
//             if (selectedDates.size > 0) {
//                 var activities = {};
//                 eventData.activity = {};
//                 var total_activities = 0;
//                 set_activity_html(selectedDates);
//             }
//         }
//     );

//     $("#event-date").on("apply.daterangepicker", function (ev, picker) {
//         picker.hide();
//         picker.endDate = picker.startDate; // Ensure both dates are the same
//         $(this).val(picker.startDate.format("MM-DD-YYYY")); // Display selected date
//         $("#event-date").next().addClass("floatingfocus");
//     });
//     $("#event-date").on("hide.daterangepicker", function (ev, picker) {
//         picker.show();
//         $("#event-date").next().addClass("floatingfocus");
//     });
// });
// $(document).on('click',,function(){
$(function () {
    var selectedDates = new Set();
    let ed = document.getElementById("event-date");
    var oldDate = $(ed).attr("data-isDate");
    $("#event-date").daterangepicker(
        {
            autoUpdateInput: false,
            locale: {
                format: "MM/DD/YYYY",
            },
            showDropdowns: false,
            startDate: moment().startOf("month"),
            // endDate: moment().endOf("month"),
            // minDate: moment().add(1, 'days'),
            minDate: moment(),
            // alwaysShowCalendars: true, // Keep the calendar visible
            maxSpan: { days: 2 },
        },

        function (start, end, label) {
            // const isDate = $(this)  // Get the data attribute inside the callback

            selectedDates.clear();
            // selectedDates.add(start.format("YYYY-MM-DD"));
            // selectedDates.add(end.format("YYYY-MM-DD"));
            // var eventDate = start.format("YYYY-MM-DD") + " To " + end.format("YYYY-MM-DD")
            selectedDates.add(start.format("MM-DD-YYYY"));
            selectedDates.add(end.format("MM-DD-YYYY"));
            var eventDate =
                start.format("MM-DD-YYYY") + " To " + end.format("MM-DD-YYYY");
            rsvp_by_date(start.format("MM-DD-YYYY"));
            if (start.format("MM-DD-YYYY") == end.format("MM-DD-YYYY")) {
                eventDate = end.format("MM-DD-YYYY");
            }
            $("#event-date").val(eventDate);
            $(".step_1_activity").html(
                '<span><i class="fa-solid fa-triangle-exclamation"></i></span>Setup activity schedule'
            );

            $("#event-date").val(eventDate).trigger("change");

            $(".activity_bar").children().not(".toggle-wrp").remove();
            // $('#schedule').prop("checked",false);
            // $('.add-activity-schedule').hide();
            if (oldDate != "") {
                $("#isnewdata").show();
                $("#isolddata").hide();
            }
            // alert();
            $("#end_time").prop("checked", false);
            $(".end-time-create").val("");
            $(".start-time-create").val("");
            $(".end_time").css("display", "none");
            if (selectedDates.size > 0) {
                var activities = {};
                eventData.activity = {};
                var total_activities = 0;
                set_activity_html(selectedDates);
            }
        }
    );

    $("#event-date").on("apply.daterangepicker", function (ev, picker) {
        picker.hide();
        $("#event-date").next().addClass("floatingfocus");
    });
    $("#event-date").on("hide.daterangepicker", function (ev, picker) {
        picker.show();
        $("#event-date").next().addClass("floatingfocus");
    });
});
$(document).on("change", "#schedule", function () {
    var eventDate = $("#event-date").val();
    var activities = {};
    eventData.activity = {};
    var total_activities = 0;
    $(".step_1_activity").html(
        '<span><i class="fa-solid fa-triangle-exclamation"></i></span>Setup activity schedule'
    );
    if (eventDate != "") {
        if ($(this).is(":checked")) {
            // console.log(eventDate);
            var selectedDates = new Set();
            $(".add-activity-schedule").show();
            if (eventDate.includes(" To ")) {
                // Split and set start and end for a date range
                let [start, end] = eventDate.split(" To ");
                // console.log(start);
                selectedDates.clear();
                selectedDates.add(start);
                selectedDates.add(end);
            } else {
                // For a single date, set both start and end to the same date
                selectedDates.clear();
                selectedDates.add(eventDate);
                selectedDates.add(eventDate);
            }
            // console.log(selectedDates.size);
            if (selectedDates.size > 0) {
                set_activity_html(selectedDates);
            }
        } else {
            $(".activity_bar").html("");
            $(".add-activity-schedule").hide();
            // $(".ac-end-time").hide();
        }
    } else {
        $("#schedule").prop("checked", false);
        toastr.error("please select event date.");
    }
});

function set_activity_html(selectedDates) {
    $(".activity_bar").html("");
    var activities = {};
    eventData.activity = {};
    total_activities = 0;
    var sortedDates = [...selectedDates].sort();
    var startDate = moment(sortedDates[0]);
    var endDate = moment(
        sortedDates.length === 2 ? sortedDates[1] : sortedDates[0]
    );
    var i = 0;
    var start_time = $("#start-time").val();
    // Loop through the date range, including startDate and endDate
    // activity_html_set(startDate,endDate,dateID,sortedDates);
    while (startDate <= endDate) {
        var dateID = startDate.format("YYYYMMDD");
        if (i == 0) {
            $("#firstActivityTime").val(dateID);
        }
        i++;
        var formHtml = `
     <div class="activity-schedule-wrp">
        <div class="activity-schedule-head">
            <h3>${startDate.format("dddd - MMMM D, YYYY")}</h3>
        </div>
        <div class="activity-schedule-inner new_event_detail_form">
            <form>
                ${
                    startDate.isSame(moment(sortedDates[0]), "day")
                        ? `
                            <h4>Event Start</h4>
                            <div class="row">
                                <div class="col-12 mb-4">
                                   <div class="form-group">
                                        <label>Start Time</label>
                                        <div class="input-group time ">
                                            <input class="form-control start_timepicker" placeholder="HH:MM AM/PM" id="ac-start-time" name="ac-start-time" oninput="clearError()" value="${start_time}" required="" readonly/><span class="input-group-append input-group-addon"><span class="input-group-text"><svg width="21" height="20" viewBox="0 0 21 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M18.8334 9.99984C18.8334 14.5998 15.1 18.3332 10.5 18.3332C5.90002 18.3332 2.16669 14.5998 2.16669 9.99984C2.16669 5.39984 5.90002 1.6665 10.5 1.6665C15.1 1.6665 18.8334 5.39984 18.8334 9.99984Z" stroke="#64748B" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M13.5917 12.65L11.0083 11.1083C10.5583 10.8416 10.1917 10.2 10.1917 9.67497V6.2583" stroke="#64748B" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg></span></span>
                                        </div>
                                    </div>
                                </div>
                            </div>`
                        : ""
                }
                <div class="accordion" id="accordionExample">
                    <div class="accordion-item">
                        <div class="accordion-header">
                            <button
                                class="accordion-button collapsed"
                                type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#collapseOne${dateID}">
                                <div>Activities <span class="total_activity-${dateID} activity_total_count">(0)</span></div>
                                <i class="fa-solid fa-angle-down"></i>
                            </button>
                            <div class="accordion-button-icons add_more_activity" data-activity="add_activity_${i}" data-id="${dateID}">
                                <i class="fa-solid fa-circle-plus"></i>
                            </div>
                        </div>
                        <div
                            id="collapseOne${dateID}"
                            class="accordion-collapse collapse"
                            data-bs-parent="#accordionExample">
                            <div class="accordion-body new_activity" id="${dateID}" data-id="${startDate.format(
            "YYYY-MM-DD"
        )}">
                            </div>
                        </div>
                    </div>
                </div>
                ${
                    startDate.isSame(
                        moment(sortedDates[sortedDates.length - 1]),
                        "day"
                    )
                        ? `
                        <div class="ac-end-time" > 
                        <input type="hidden" id="LastEndTime" value="${dateID}" />
                        <h4 class="mt-3 ">Event Ends</h4>
                        <div class="col-12 ac-end-time">
                            <div class="form-group">
                                <label>End Time</label>
                                <div class="input-group time ">
                                    <input class="form-control end_timepicker" placeholder="HH:MM AM/PM" id="ac-end-time" name="ac-end-time" oninput="clearError()" required="" readonly/><span class="input-group-append input-group-addon"><span class="input-group-text"><svg width="21" height="20" viewBox="0 0 21 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M18.8334 9.99984C18.8334 14.5998 15.1 18.3332 10.5 18.3332C5.90002 18.3332 2.16669 14.5998 2.16669 9.99984C2.16669 5.39984 5.90002 1.6665 10.5 1.6665C15.1 1.6665 18.8334 5.39984 18.8334 9.99984Z" stroke="#64748B" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M13.5917 12.65L11.0083 11.1083C10.5583 10.8416 10.1917 10.2 10.1917 9.67497V6.2583" stroke="#64748B" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg></span></span>
                                </div>
                            </div>
                        `
                        : ""
                }
            </form>
        </div>
    </div>
    `;

        $(".activity_bar").append(formHtml);
        // datepicker();
        startDate.add(1, "day");
    }

    var save_btn = `<div class="activity-schedule-inner-btn">
            <button class="cmn-btn" id="save_activity_schedule">
               Save
            </button>
        </div>`;

    $(".activity_bar").append(save_btn);
}

$(document).on("click", ".delete_activity", function () {
    var id = $(this).data("id");
    $("#" + id).remove();
    var getClass = $(this).data("class");
    var total_activity = $(this).data("total_activity");
    var i = 1;
    $(".activity-count-" + getClass).each(function (index) {
        $(this).text(i);
        i++;
    });
    total_activities--;
    i--;
    $(".total_activity-" + total_activity).text("(" + i + ")");
    $(".step_1_activity").text(i + " Activity");

    console.log(total_activities);
});
var numItems = 0;

$(document).on("click", ".create_new_event", function () {
    // alert();
    toggleSidebar("sidebar_change_plan_create");
});

$(document).on("click", ".add_more_activity", function (e) {
    var start_time = $("#ac-start-time").val();
    var firstActivity = $(this).data("activity");

    if (
        (start_time == null || start_time == "") &&
        firstActivity == "add_activity_1"
    ) {
        e.preventDefault();
        toastr.error("Plaese select start time");
        return;
    }
    $(this).parent().find(".accordion-button").addClass("accordian_open");
    $(this).prop("disabled", true);

    var newClass = $(this).parent().next().find(".new_activity").data("id");
    var count = $("." + newClass).length;
    if (count === null || count === undefined) {
        count = 1;
    } else {
        count++;
    }
    console.log(count);
    var id = $(this).data("id");
    $("#collapseOne" + id).addClass("show");
    // var activity = $("#" + id).length;
    // console.log(activity);
    var dt = new Date();
    var time = dt.getHours() + "-" + dt.getMinutes() + "-" + dt.getSeconds();
    var dataid = time + numItems;
    numItems++;
    $.ajax({
        url: base_url + "event/add_activity",
        method: "POST",
        data: {
            dataid: dataid,
            newClass: newClass,
            count: count,
            id: id,
            _token: $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (response) {
            $("#" + id).append(response);
            total_activities++;
            console.log(total_activities);
            datepicker();
            $(".total_activity-" + id).text("(" + count + ")");
            $(".add_more_activity").prop("disabled", false);
        },
        error: function (xhr, status, error) {
            console.error("An error occurred");
        },
    });
});

function addDateCard(date, dateID) {
    let container = document.getElementById(`activityContainer-${date}`);
    addActivity(container, date);
    let addMoreButton = container.querySelector(".add-more-button");
    if (!addMoreButton) {
        addMoreButton = document.createElement("button");
        addMoreButton.type = "button";
        addMoreButton.classList.add("btn", "btn-secondary", "add-more-button");
        addMoreButton.textContent = "Add More";
        addMoreButton.onclick = function () {
            addActivity(container, date);
        };
        container.appendChild(addMoreButton);
    }
}

function addActivity(container, date) {
    const activityDiv = document.createElement("div");
    activityDiv.classList.add("activity");
    activityDiv.innerHTML = `
      <input type="text" name="activities[${date}][]" placeholder="Activity Name">
      <input type="time" name="activity_start_time[${date}][]" placeholder="Start Time">
      <input type="time" name="activity_end_time[${date}][]" placeholder="End Time">
      <button type="button" onclick="removeActivity(this)">Delete</button>
    `;
    container.insertBefore(
        activityDiv,
        container.querySelector(".add-more-button")
    );
}

function removeActivity(button) {
    button.parentElement.remove();
}
$(document).on("click", "#end-time", function () {
    var start_time = $("#start-time").val();

    if (start_time) {
        var startTime = moment(start_time, "hh:mm A"); // Parse the start time string
        var endTime = startTime.clone().add(1, "hours");
        $("#end-time").val(endTime.format("hh:mm A"));
    } else {
        $("#end-time").val(""); // Clear end time if start time is empty
    }
});
$("#end_time").on("change", function () {
    if ($(this).is(":checked")) {
        $(".end_time").show();
        $(".ac-end-time").show();
        var start_time = $("#start-time").val();
        console.log(start_time);

        if (start_time) {
            var startTime = moment(start_time, "hh:mm A"); // Parse the start time string
            // var endTime = startTime.clone().add(1, "hours");
            // $("#end-time").val(endTime.format("hh:mm A"));
        } else {
            $("#end-time").val(""); // Clear end time if start time is empty
        }
    } else {
        $(".end-time-create").val("");
        $(".end_time").hide();
        $(".ac-end-time").hide();
    }
});

$("#rsvp_by_date").on("change", function () {
    if ($(this).is(":checked")) {
        $(".rsvp_by_date").show();
    } else {
        $("#rsvp-by-date").val("");
        $(".rsvp_by_date").hide();
    }
});

$(document).ready(function () {
    $(".design-category").click(function () {
        var selectedSubcategory = $(this).data("subcategory");
        $(".subcategory-section").hide();
        $("#subcategory_" + selectedSubcategory).show();
    });
});

$("#allow_for_1_more").on("change", function () {
    // alert()
    if ($(this).is(":checked")) {
        // $(".hidersvp").hide();
        $(".allow_for_limit_count")
            .html(`<div class="d-flex align-items-center add_new_limit">
        <span class="me-3">
            <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M17.1336 12.267L11.8002 2.66