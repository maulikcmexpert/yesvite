let eventData = {};
let isCohost = $("#isCohost").val();
var total_activities = 0;
var category = 0;
var items = 0;
var activities = {};
var selected_co_host = $("#cohostId").val() !== "" ? $("#cohostId").val() : "";
var selected_co_host_prefer_by =
    $("#cohostpreferby").val() !== "" ? $("#cohostpreferby").val() : "";
var final_step = $("#step").val() != "" ? $("#step").val() : 1;
var isDraftEvent = $("#isDraft").val() != "" ? $("#isDraft").val() : "";

var swiper;
var isPhonecontact = 0;
var lengtUSer = $("#cohostId").val() !== "" ? 1 : 0;
var selected_gift = [];
var selected_user_name =
    $("#cohostFname").val() !== "" && $("#cohostLname").val() !== ""
        ? $("#cohostFname").val() + " " + $("#cohostLname").val()
        : "";
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
var create_event_phone_scroll=false;                
var create_event_yesvite_scroll=false;                
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

if (giftRegestryDataRaw.length > 0) {
    try {
        var giftRegestryData = JSON.parse(giftRegestryDataRaw);
        giftRegestryData.forEach(function (item) {
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
        const newOption = $("<option></option>")
            .val(currentTimeZone)
            .text(currentTimeZone)
            .prop("selected", true);
        $("#end-time-zone").append(newOption);
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

var swiper = new Swiper(".mySwiper", {
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
            $(this).val("");
            $(this)
                .data("DateTimePicker")
                .date(moment().hours(12).minutes(0).seconds(0));
            // }
        })
        .on("dp.hide", function (e) {
            const selectedTime = e.date ? e.date.format("LT") : "";
            $(this).val(selectedTime);
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
            minYear: 1901,
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
        if (picker.startDate.isValid()) {
            $(this).val(picker.startDate.format("MM-DD-YYYY"));
            $("#rsvp-by-date").next().addClass("floatingfocus");
        }
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
        if (picker.startDate.isValid()) {
            // $(this).val(picker.startDate.format('YYYY-MM-DD'));
            $(this).val(picker.startDate.format("MM-DD-YYYY"));
            $("#rsvp-by-date").next().addClass("floatingfocus");
        }
    });
});

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
// $(document).on('click',,function(){

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
                                            <input class="form-control timepicker" placeholder="HH:MM AM/PM" id="ac-start-time" name="ac-start-time" oninput="clearError()" value="${start_time}" required="" readonly/><span class="input-group-append input-group-addon"><span class="input-group-text"><svg width="21" height="20" viewBox="0 0 21 20" fill="none" xmlns="http://www.w3.org/2000/svg">
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
                                    <input class="form-control timepicker" placeholder="HH:MM AM/PM" id="ac-end-time" name="ac-end-time" oninput="clearError()" required="" readonly/><span class="input-group-append input-group-addon"><span class="input-group-text"><svg width="21" height="20" viewBox="0 0 21 20" fill="none" xmlns="http://www.w3.org/2000/svg">
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
                <path d="M17.1336 12.267L11.8002 2.66699C11.0836 1.37533 10.0919 0.666992 9.00023 0.666992C7.90856 0.666992 6.91689 1.37533 6.20023 2.66699L0.866892 12.267C0.191892 13.492 0.116892 14.667 0.658559 15.592C1.20023 16.517 2.26689 17.0253 3.66689 17.0253H14.3336C15.7336 17.0253 16.8002 16.517 17.3419 15.592C17.8836 14.667 17.8086 13.4837 17.1336 12.267ZM8.37523 6.50033C8.37523 6.15866 8.65856 5.87533 9.00023 5.87533C9.34189 5.87533 9.62523 6.15866 9.62523 6.50033V10.667C9.62523 11.0087 9.34189 11.292 9.00023 11.292C8.65856 11.292 8.37523 11.0087 8.37523 10.667V6.50033ZM9.59189 13.7587C9.55023 13.792 9.50856 13.8253 9.46689 13.8587C9.41689 13.892 9.36689 13.917 9.31689 13.9337C9.26689 13.9587 9.21689 13.9753 9.15856 13.9837C9.10856 13.992 9.05023 14.0003 9.00023 14.0003C8.95023 14.0003 8.89189 13.992 8.83356 13.9837C8.78356 13.9753 8.73356 13.9587 8.68356 13.9337C8.63356 13.917 8.58356 13.892 8.53356 13.8587C8.49189 13.8253 8.45023 13.792 8.40856 13.7587C8.25856 13.6003 8.16689 13.3837 8.16689 13.167C8.16689 12.9503 8.25856 12.7337 8.40856 12.5753C8.45023 12.542 8.49189 12.5087 8.53356 12.4753C8.58356 12.442 8.63356 12.417 8.68356 12.4003C8.73356 12.3753 8.78356 12.3587 8.83356 12.3503C8.94189 12.3253 9.05856 12.3253 9.15856 12.3503C9.21689 12.3587 9.26689 12.3753 9.31689 12.4003C9.36689 12.417 9.41689 12.442 9.46689 12.4753C9.50856 12.5087 9.55023 12.542 9.59189 12.5753C9.74189 12.7337 9.83356 12.9503 9.83356 13.167C9.83356 13.3837 9.74189 13.6003 9.59189 13.7587Z" fill="#E03137" />
            </svg>
        </span>
        <h5>Add +1 limit</h5>
    </div>
    <span>
        <svg width="9" height="16" viewBox="0 0 9 16" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M1.42505 14.6004L6.85838 9.16706C7.50005 8.52539 7.50005 7.47539 6.85838 6.83372L1.42505 1.40039" stroke="#94A3B8" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
    </span>`);
        $("#allow_limit").show();
    } else {
        $("#allow_limit").hide();
        $("#allow_limit_count").val(0);
        eventData.allow_limit_count = 0;
    }
});

$(".allow_limit_toggle").on("click", function () {
    if (
        eventData.allow_limit_count == undefined ||
        eventData.allow_limit_count == ""
    ) {
        $("#allow_limit_count").val(0);
    } else {
        $("#allow_limit_count").val(eventData.allow_limit_count);
    }
    toggleSidebar("sidebar_allow_limit");
});

$("#potluck").on("change", function () {
    // alert()
    if ($(this).is(":checked")) {
        $(".potluck").show();
    } else {
        var category_count = $("#category_count").val();
        console.log(category);

        if (category == 0) {
            $(".potluck").hide();
        } else {
            $(".delete_potluck_title").text("Potluck data will be deleted");
            $(".delete_potluck_text").text(
                "All category and item data that you have entered will be lost if you turn this off"
            );
            $(".delete_category_text").text("");
            $("#delete_potluck_category_id").val("all_potluck");
            $("#deleteModal_potluck").modal("show");
        }
    }
});

$(document).on("click", ".potluck_cancel", function () {
    $("#potluck").prop("checked", true);
});
$(document).on("click", ".group_toggle_close_btn", function () {
    $("#group_toggle_search").val("");
    groupToggleSearch();
});

$("#gift_registry").on("change", function () {
    // alert()
    eventData.gift_registry_data = {};
    if ($(this).is(":checked")) {
        // $(".hidersvp").hide();
        $(".add_gift_registry_count").html(`<span class="me-3">
                <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M17.1336 12.267L11.8002 2.66699C11.0836 1.37533 10.0919 0.666992 9.00023 0.666992C7.90856 0.666992 6.91689 1.37533 6.20023 2.66699L0.866892 12.267C0.191892 13.492 0.116892 14.667 0.658559 15.592C1.20023 16.517 2.26689 17.0253 3.66689 17.0253H14.3336C15.7336 17.0253 16.8002 16.517 17.3419 15.592C17.8836 14.667 17.8086 13.4837 17.1336 12.267ZM8.37523 6.50033C8.37523 6.15866 8.65856 5.87533 9.00023 5.87533C9.34189 5.87533 9.62523 6.15866 9.62523 6.50033V10.667C9.62523 11.0087 9.34189 11.292 9.00023 11.292C8.65856 11.292 8.37523 11.0087 8.37523 10.667V6.50033ZM9.59189 13.7587C9.55023 13.792 9.50856 13.8253 9.46689 13.8587C9.41689 13.892 9.36689 13.917 9.31689 13.9337C9.26689 13.9587 9.21689 13.9753 9.15856 13.9837C9.10856 13.992 9.05023 14.0003 9.00023 14.0003C8.95023 14.0003 8.89189 13.992 8.83356 13.9837C8.78356 13.9753 8.73356 13.9587 8.68356 13.9337C8.63356 13.917 8.58356 13.892 8.53356 13.8587C8.49189 13.8253 8.45023 13.792 8.40856 13.7587C8.25856 13.6003 8.16689 13.3837 8.16689 13.167C8.16689 12.9503 8.25856 12.7337 8.40856 12.5753C8.45023 12.542 8.49189 12.5087 8.53356 12.4753C8.58356 12.442 8.63356 12.417 8.68356 12.4003C8.73356 12.3753 8.78356 12.3587 8.83356 12.3503C8.94189 12.3253 9.05856 12.3253 9.15856 12.3503C9.21689 12.3587 9.26689 12.3753 9.31689 12.4003C9.36689 12.417 9.41689 12.442 9.46689 12.4753C9.50856 12.5087 9.55023 12.542 9.59189 12.5753C9.74189 12.7337 9.83356 12.9503 9.83356 13.167C9.83356 13.3837 9.74189 13.6003 9.59189 13.7587Z" fill="#E03137" />
                </svg>
            </span>
            <h5>Add gift registry</h5>`);
        eventData.gift_registry = "1";
        $(".add_gift_registry").show();
        $("#giftDiv").show();
    } else {
        // delete_session('gift_registry_data');
        $("#giftDiv").hide();
        $(".add_gift_registry").hide();
        eventData.gift_registry = "0";
    }
    // savePage4Data();
});

// function delete_session(session){
//     $.ajax({
//         url: base_url + "event/delete_sessions",
//         type: "POST",
//         headers: {
//             "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
//         },
//         data: {delete_session:session},
//         success: function (response) {
//             console.log(response);
//             if(session == 'gift_registry_data'){
//                 $('#registry_list').html('');
//             }
//         },
//         error: function (xhr, status, error) {
//             console.log("AJAX error: " + error);
//         },
//     });
// }

$("#add_cohost").on("change", function () {
    // alert()
    if ($(this).is(":checked")) {
        // $(".hidersvp").hide();
        $(".add_cohost").show();
    } else {
        $(".add_cohost").hide();
    }
});

$("#thankyou_messages").on("change", function () {
    if ($(this).is(":checked")) {
        $("#thankyouDiv").show();
    } else {
        $("#thankyouDiv").hide();
    }
});

$(document).on("keyup", ".search_name", function () {
    search_name = $(this).val();
    page = 1;
    $("#yesviteUser").html("");
    loadMoreData(page, search_name);
});

function loadMoreData(page, search_name) {
    $.ajax({
        url: base_url + "contacts/loadcreate_event?page=" + page,
        type: "POST",
        data: {
            search_name: search_name,
            _token: $('meta[name="csrf-token"]').attr("content"), // Adding CSRF token
        },
        beforeSend: function () {
            $("#loader").show();
        },
    })
        .done(function (data) {
            if (data.html == " ") {
                $("#loader").html("No more contacts found");
                return;
            }
            $("#loader").hide();
            $("#yesviteUser").html(data);
        })
        .fail(function (jqXHR, ajaxOptions, thrownError) {
            alert("server not responding...");
        });
}

// $(document).on("keyup", ".co_host", function () {
//     search_name = $(this).val();
//     page = 1;
//     $("#yesviteUser").html("");
//     loadMoreDatacoshost(page, search_name);
// });

// function loadMoreDatacoshost(page, search_name) {
//     $.ajax({
//         url: base_url + "contacts/loadcreate_event?page=" + page,
//         type: "POST",
//         data: {
//             search_name: search_name,
//             _token: $('meta[name="csrf-token"]').attr("content"), // Adding CSRF token
//         },
//         beforeSend: function () {
//             $("#loader").show();
//         },
//     })
//         .done(function (data) {
//             if (data.html == " ") {
//                 $("#loader").html("No more contacts found");
//                 return;
//             }
//             $("#loader").hide();
//             $("#yesviteUser").html(data);
//         })
//         .fail(function (jqXHR, ajaxOptions, thrownError) {
//             alert("server not responding...");
//         });
// }

// $(document).ready(function () {
$(document).on("change", 'input[name^="add_by_"]', function () {
    var currentName = $(this).attr("name");
    var userId = $(this).val();

    if ($(this).is(":checked")) {
        $('input[name^="add_by_"]')
            .filter(function () {
                return (
                    $(this).val() === userId &&
                    $(this).attr("name") !== currentName
                );
            })
            .prop("checked", false);
    }
});

$(document).on("click", 'input[name="email_invite[]"]', function (e) {
    var inviteCount = parseInt($("#currentInviteCount").val());

    if ($(this).is(":disabled")) {
        e.preventDefault();
        return;
    }
    $("#loader").css("display", "block");

    var userId = $(this).val();
    var isChecked = $(this).is(":checked");
    var email = $(this).data("email");
    var is_contact = $(this).data("contact");
    // if(inviteCount > 15){
    //     e.preventDefault();
    //     return;
    // }

    if (isChecked == true || isChecked == "true") {
        // $('input[name="email_invite[]"]').attr('disabled', true);
        // $(this).prop("disabled", true);
        $.ajax({
            url: base_url + "event/store_user_id",
            method: "POST",
            data: {
                user_id: userId,
                is_checked: isChecked,
                email: email,
                is_contact: is_contact,
                _token: $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (response) {
                console.log(response);
                var currentInviteCount = parseInt(
                    $("#currentInviteCount").val()
                );
                currentInviteCount++;
                $("#currentInviteCount").val(currentInviteCount);
                if (response.is_duplicate == 1) {
                    $("#user_tel-" + userId).remove();
                    $(".user_id_tel-" + userId).remove();
                }

                var total_guest = 0;
                var max_guest = $("#coins").val();
                // if (total_guest == max_guest) {

                // } else {
                $(".inivted_user_list").append(response.view);
                // var length = responsive_invite_user();
                // if(length < 4){
                console.log({ is_yesvite: response.is_yesvite });
                console.log({ is_phone: response.is_phone });
                $(".all_user_list").remove();
                if (response.is_yesvite == "1") {
                    $(".user-list-responsive_yesvite").empty();
                    $(".user-list-responsive_yesvite").html(
                        response.responsive_view
                    );
                }
                if (response.is_phone == "1") {
                    $(".user-list-responsive_phone").empty();
                    $(".user-list-responsive_phone").html(
                        response.responsive_view
                    );
                }
                // }else{
                //     add_user_counter();
                // }

                guest_counter(0, max_guest);
                // $('input[name="email_invite[]"]').prop('disabled', false);
                // if(currentInviteCount >= 15){
                //     $('.user_choice').prop('disabled',true);
                // }
                $("#loader").css("display", "none");
                // }
            },
            error: function (xhr, status, error) {},
        });
    } else {
        if (is_contact != "1") {
            is_contact = null;
        }
        delete_invited_user(userId, is_contact);
        $("#loader").css("display", "none");
    }
});

function responsive_invite_user() {
    var length = $(".responsive_invite_user").length;
    length = length / 3;
    return length;
}

function add_user_counter() {
    var counter = $(".users-data.invited_user").length - 4;
    console.log(counter);
    var all_user_list_length = $(".all_user_list").length;
    if (all_user_list_length < 1) {
        $(".user-list-responsive")
            .append(`<div class="guest-contact all_user_list">
                    <div class="guest-img">
                        <span class="update_user_count">+${counter}</span>
                    </div>
                    <span class="all-contact">See all</h6>
                </div>`);
    } else {
        $(".update_user_count").text("+" + counter);
    }
}

function guest_counter(total_guest, max_guest) {
    var total_guest = $(".users-data.invited_user").length;
    var Alreadyguest = $(".users-data.invited_users").length;

    $("#event_guest_count").text(total_guest + " Guests");
    $(".invite-count").text(total_guest + Alreadyguest);
    console.log(total_guest);
    console.log(max_guest);

    var remainingCount = max_guest - total_guest;
    if (remainingCount < 0) {
        $(".invite-left_d").text("Invites | 0 Left");
    } else {
        $(".invite-left_d").text("Invites | " + remainingCount + " Left");
    }
    // $(".invite-left_d").text(
    //     "Invites | " + remainingCount + " Left"
    // );
    $("#event_guest_left_count").val(remainingCount);
}

function delete_invited_user(userId, is_contact = "0") {
    console.log("IS contact", is_contact);
    $.ajax({
        url: base_url + "event/delete_user_id",
        method: "POST",
        data: {
            user_id: userId,
            is_contact: is_contact,
            _token: $('meta[name="csrf-token"]').attr("content"), // Adding CSRF token
        },
        success: function (response) {
            var currentInviteCount = parseInt($("#currentInviteCount").val());
            currentInviteCount--;
            $("#currentInviteCount").val(currentInviteCount);

            $(".user-list-responsive").empty();
            if (response.is_yesvite == "1") {
                $(".user-list-responsive_yesvite").empty();
                $(".user-list-responsive_yesvite").html(
                    response.responsive_view
                );
            }
            if (response.is_phone == "1") {
                $(".user-list-responsive_phone").empty();
                $(".user-list-responsive_phone").html(response.responsive_view);
            }
            // $(".user-list-responsive").html(response.responsive_view);
            if (is_contact == "1") {
                console.log("IS contact new", is_contact);
                $("#contact_tel-" + userId).remove();
                $(".sync_user_id_tel-" + userId).remove();
            } else {
                $("#user_tel-" + userId).remove();
                $(".user_id_tel-" + userId).remove();
            }
            var total_guest = $(".users-data.invited_user").length;
            var alreadyguest = $(".users-data.invited_users").length;
            $("#event_guest_count").text(total_guest + " Guests");
            $(".invite-count").text(total_guest + alreadyguest);

            // var max_guest = 15;
            var max_guest = $("#coins").val();
            var remainingCount = max_guest - total_guest;

            if (remainingCount < 0) {
                $(".invite-left_d").text("Invites | 0 Left");
            } else {
                $(".invite-left_d").text(
                    "Invites | " + remainingCount + " Left"
                );
            }
            $("#event_guest_left_count").val(remainingCount);
            console.log("User ID deleted successfully.");

            $("#loader").css("display", "none");
        },
        error: function (xhr, status, error) {
            console.error("An error occurred while storing the User ID.");
        },
    });
}

$(document).on("click", 'input[name="mobile[]"]', function (e) {
    // if ($(this).is(':disabled')) {
    //     e.preventDefault();
    //     return;
    // }
    var userId = $(this).val();
    var isChecked = $(this).is(":checked");
    var mobile = $(this).data("mobile");
    var is_contact = $(this).data("contact");
    if (isChecked == true || isChecked == "true") {
        $("#loader").css("display", "block");
        $.ajax({
            url: base_url + "event/store_user_id",
            method: "POST",
            data: {
                user_id: userId,
                is_checked: isChecked,
                mobile: mobile,
                is_contact: is_contact,
                _token: $('meta[name="csrf-token"]').attr("content"), // Adding CSRF token
            },
            success: function (response) {
                console.log(response);

                var currentInviteCount = $("#currentInviteCount").val();
                currentInviteCount++;
                $("#currentInviteCount").val(currentInviteCount);

                if (response.is_duplicate == 1) {
                    $("#user-" + userId).remove();
                    $(".user_id-" + userId).remove();
                    // $(".user-list-responsive").empty();
                    // $(".user-list-responsive").html(response.responsive_view);
                }

                var total_guest = $(".users-data.invited_user").length;
                $("#event_guest_count").text(total_guest + " Guests");
                $(".invite-count").text(total_guest);

                var max_guest = $("#coins").val();
                var remainingCount = max_guest - total_guest;
                // if(currentInviteCount >= 15){
                //     $('.user_choice').prop('disabled',true);
                // }
                $(".inivted_user_list").append(response.view);
                // $(".user-list-responsive").empty();
                // $(".user-list-responsive").html(response.responsive_view);
                console.log({ is_yesvite: response.is_yesvite });
                console.log({ is_phone: response.is_phone });
                if (response.is_yesvite == "1") {
                    $(".user-list-responsive_yesvite").empty();
                    $(".user-list-responsive_yesvite").html(
                        response.responsive_view
                    );
                }
                if (response.is_phone == "1") {
                    $(".user-list-responsive_phone").empty();
                    $(".user-list-responsive_phone").html(
                        response.responsive_view
                    );
                }
                guest_counter(0, max_guest);
                $("#loader").css("display", "none");

                // var length = responsive_invite_user();
                // if(length < 4){
                //     $('.all_user_list').remove();
                //     $(".user-list-responsive").empty();
                //     $(".user-list-responsive").html(response.responsive_view);
                //     // $(".user-list-responsive").append(response.responsive_view);
                // }else{
                //     // add_user_counter();
                // }
                // if(remainingCount < 0){
                //     $(".invite-left_d").text("Invites | 0 Left");
                // }else{
                //     $(".invite-left_d").text("Invites | " + remainingCount + " Left");

                // }
                // $("#event_guest_left_count").val(remainingCount);
            },
            error: function (xhr, status, error) {},
        });
    } else {
        $("#loader").css("display", "block");

        $.ajax({
            url: base_url + "event/delete_user_id",
            method: "POST",
            data: {
                user_id: userId,
                is_contact: is_contact,
                _token: $('meta[name="csrf-token"]').attr("content"), // Adding CSRF token
            },
            success: function (response) {
                if (is_contact == "1") {
                    $("#contact_tel-" + userId).remove();
                    $(".sync_user_id_tel-" + userId).remove();
                } else {
                    $("#user_tel-" + userId).remove();
                    $(".user_id_tel-" + userId).remove();
                }
                var currentInviteCount = $("#currentInviteCount").val();
                currentInviteCount--;
                $("#currentInviteCount").val(currentInviteCount);
                var total_guest = $(".users-data.invited_user").length;
                var alreadyguest = $(".users-data.invited_users").length;
                $("#event_guest_count").text(total_guest + " Guests");
                $(".invite-count").text(total_guest + 0);

                var max_guest = $("#coins").val();
                var remainingCount = max_guest - total_guest;

                if (remainingCount < 0) {
                    $(".invite-left_d").text("Invites | 0 Left");
                } else {
                    $(".invite-left_d").text(
                        "Invites | " + remainingCount + " Left"
                    );
                }
                $("#event_guest_left_count").val(remainingCount);
                $("#loader").css("display", "none");

                console.log("User ID deleted successfully.");
            },
            error: function (xhr, status, error) {
                console.error("An error occurred while storing the User ID.");
            },
        });
    }
});

$(document).on("click", "#openDivButton", function () {
    $("#categoryDiv").slideDown();
});

$("#addItemButton").click(function () {
    $("#categoryModal").modal("show");
});

$("#addGiftRegistry").click(function () {
    $("#giftModal").modal("show");
});

$("#addThankyou").click(function () {
    $("#thankyouModal").modal("show");
});

$(document).on("click", ".edit_card", function () {
    $("#editthankyouModal").modal("show");

    var temp_name = $(this)
        .closest(".thankyoucardlist")
        .find('input[name="thankyoucard[]"]')
        .data("template_name");
    var send = $(this)
        .closest(".thankyoucardlist")
        .find('input[name="thankyoucard[]"]')
        .data("send");
    var message = $(this)
        .closest(".thankyoucardlist")
        .find('input[name="thankyoucard[]"]')
        .data("message");

    $("#edit_templatename").val(temp_name);
    $("#edit_send").val(send);
    $("#edit_thank_message").val(message);
    $("#old_name").val(temp_name);
    console.log(temp_name);
});

var potluck = [];
var potluckkey = -1;
var activePotluck = 0;

function setPotluckActivekey(key, name) {
    $("#category_index").val(key);
    var categoryQuantity = $("#missing-category-" + key).text();
    $("#hidden_category_name").val(name);
    $("#hidden_category_quantity").val(categoryQuantity);
    $(".sub-cat-pot").text("0/30");
    $(".category_heading").text("Add Item Under: " + name);
    activePotluck = key;
}

$(document).on("click", ".add_category_btn", function () {
    var categoryName = $("#categoryName").val();
    var categoryQuantity = $("#category_quantity").val();
    var edit_category_id = $("#hidden_potluck_key").val();

    if (categoryName == "") {
        $("#categoryNameError")
            .css("display", "block")
            .css("color", "red")
            .text("Please enter category name");
        return;
    }
    if (categoryQuantity == "" || categoryQuantity < 1) {
        $("#category_quantity_error")
            .css("display", "block")
            .css("color", "red")
            .text("Please select quantity");
        return;
    }
    // console.log(categoryQuantity);
    if (edit_category_id == "") {
        potluckkey++;
        potluck[potluckkey] = categoryName;
    }
    $.ajax({
        url: base_url + "event/category_session",
        method: "POST",
        data: {
            category_name: categoryName,
            potluckkey: potluckkey,
            categoryQuantity: categoryQuantity,
            edit_category_id: edit_category_id,
            _token: $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (response) {
            $("#hidden_potluck_key").val("");
            $("#add_update_category_head").text("Add New Category");
            $("#categoryName").val("");
            $("#categoryNameError").text("");
            $("#category_quantity_error").css("display", "none");
            $("#category_quantity").val(1);
            if (response.status == 1) {
                console.log(categoryName);
                $("#hidden_category_name").val(categoryName);
                $("#hidden_category_quantity").val(categoryQuantity);
                if (edit_category_id == "") {
                    $(".potluck-category").append(response.view);
                    category++;
                    $("#category_count").val(category);
                }
                toggleSidebar("sidebar_potluck");
                potluck_cateogry_item_count();
            } else if (response.status == 2) {
                console.log(response);
                // $("#hidden_category_name").val(categoryName);
                // $("#hidden_category_quantity").val(categoryQuantity);
                $(".category_name-" + edit_category_id).text(categoryName);
                // $('#missing-category-'+edit_category_id).text(categoryQuantity);
                $(".total-potluck-category-" + edit_category_id).val(
                    categoryQuantity
                );
                $(".edit_potluck_category-" + edit_category_id).attr(
                    "data-id",
                    edit_category_id
                );
                $(".edit_potluck_category-" + edit_category_id).attr(
                    "data-category_name",
                    categoryName
                );
                $(".edit_potluck_category-" + edit_category_id).attr(
                    "data-category_quantity",
                    categoryQuantity
                );
                if (response.qty == 1) {
                    $("#potluck-" + edit_category_id).hide();
                } else {
                    $("#potluck-" + edit_category_id).show();
                }
                toggleSidebar("sidebar_potluck");
                potluck_cateogry_item_count();
            } else {
                potluckkey--;
                toastr.error("category already exist");
            }
        },
        error: function (xhr, status, error) {
            console.error("An error occurred while storing the User ID.");
        },
    });
    // $(".add_sub_category").show();
    // $(".add_category").css("display", "none");
    // $(this).css("display", "none");
    // $(".add_category_item_btn").show();
});

$(document).on("click", ".edit_category", function () {
    var id = $(this).data("id");
    var category_name = $(".category_name-" + id).text();
    var category_quantity = $(".total-potluck-category-" + id).val();
    console.log(id);
    console.log(category_name);
    console.log(category_quantity);
    $("#categoryName").val(category_name);
    $("#category_quantity").val(category_quantity);
    $("#hidden_potluck_key").val(id);
    $("#add_update_category_head").text("Edit Category");
    toggleSidebar("sidebar_addcategory");
});

$(document).on("click", ".add_potluck_item", function () {
    var potluckkey = $(this).data("id");
    var categoryName = $(".category_name-" + potluckkey).text();
    setPotluckActivekey(potluckkey, categoryName);
    toggleSidebar("sidebar_addcategoryitem");
});

$(document).on("click", ".add_category_item_btn", function () {
    var category_index = $("#category_index").val();
    var category_name = $("#hidden_category_name").val();
    var totalmissing = $("#missing-category-" + activePotluck).text();
    var category_quantity = $("#hidden_category_quantity").val();
    var itemName = $("#item_name").val();
    if (itemName == "") {
        $("#item_name_error")
            .css("display", "block")
            .css("color", "red")
            .text("Please enter description.");
        return;
    }

    var itemQuantity = $("#item_quantity").val();
    if (itemQuantity == "" || itemQuantity < 1) {
        $("#item_quantity_error")
            .css("display", "block")
            .css("color", "red")
            .text("Please select quantity.");
        return;
    }

    if ($("#self_bring").is(":checked")) {
        var self_bring = 1;
    } else {
        var self_bring = 0;
    }
    var self_bringQuantity = $("#self_bring_qty").val();

    $.ajax({
        url: base_url + "event/category_item_session",
        method: "POST",
        data: {
            category_index: category_index,
            category_name: category_name,
            category_quantity: category_quantity,
            itemName: itemName,
            selfbring: self_bring,
            self_bringQuantity: self_bringQuantity,
            itemQuantity: itemQuantity,
            totalmissing: totalmissing,
            _token: $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (response) {
            console.log(response);
            $("#item_name").val("");
            $("#item_quantity").val("1");
            let slide = document.getElementsByClassName(
                "list-slide-" + activePotluck
            );
            $(slide).append(response.view);
            $(".no_item").remove();
            $("#self_bring").prop("checked", false);
            $("#self_bring_quantity_toggle").hide();
            $("#self_bring_qty").val(0);
            if (response.total_item == 0) {
                var svg =
                    '<svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M7.00016 0.333984C3.32683 0.333984 0.333496 3.32732 0.333496 7.00065C0.333496 10.674 3.32683 13.6673 7.00016 13.6673C10.6735 13.6673 13.6668 10.674 13.6668 7.00065C13.6668 3.32732 10.6735 0.333984 7.00016 0.333984ZM10.1868 5.46732L6.40683 9.24732C6.3135 9.34065 6.18683 9.39398 6.0535 9.39398C5.92016 9.39398 5.7935 9.34065 5.70016 9.24732L3.8135 7.36065C3.62016 7.16732 3.62016 6.84732 3.8135 6.65398C4.00683 6.46065 4.32683 6.46065 4.52016 6.65398L6.0535 8.18732L9.48016 4.76065C9.6735 4.56732 9.9935 4.56732 10.1868 4.76065C10.3802 4.95398 10.3802 5.26732 10.1868 5.46732Z" fill="#23AA26"></path></svg>';
                $(".missing-category-svg-" + category_index).html(svg);
                $(".missing-category-h6-" + category_index).css(
                    "color",
                    "#34C05C"
                );
            } else {
                var svg =
                    '<svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M13.5067 9.61399L9.23998 1.93398C8.66665 0.900651 7.87332 0.333984 6.99998 0.333984C6.12665 0.333984 5.33332 0.900651 4.75998 1.93398L0.493318 9.61399C-0.0466816 10.594 -0.106682 11.534 0.326652 12.274C0.759985 13.014 1.61332 13.4207 2.73332 13.4207H11.2667C12.3867 13.4207 13.24 13.014 13.6733 12.274C14.1067 11.534 14.0467 10.5873 13.5067 9.61399ZM6.49998 5.00065C6.49998 4.72732 6.72665 4.50065 6.99998 4.50065C7.27332 4.50065 7.49998 4.72732 7.49998 5.00065V8.33398C7.49998 8.60732 7.27332 8.83398 6.99998 8.83398C6.72665 8.83398 6.49998 8.60732 6.49998 8.33398V5.00065ZM7.47332 10.8073C7.43998 10.834 7.40665 10.8607 7.37332 10.8873C7.33332 10.914 7.29332 10.934 7.25332 10.9473C7.21332 10.9673 7.17332 10.9807 7.12665 10.9873C7.08665 10.994 7.03998 11.0007 6.99998 11.0007C6.95998 11.0007 6.91332 10.994 6.86665 10.9873C6.82665 10.9807 6.78665 10.9673 6.74665 10.9473C6.70665 10.934 6.66665 10.914 6.62665 10.8873C6.59332 10.8607 6.55998 10.834 6.52665 10.8073C6.40665 10.6807 6.33332 10.5073 6.33332 10.334C6.33332 10.1607 6.40665 9.98732 6.52665 9.86065C6.55998 9.83399 6.59332 9.80732 6.62665 9.78065C6.66665 9.75398 6.70665 9.73398 6.74665 9.72065C6.78665 9.70065 6.82665 9.68732 6.86665 9.68065C6.95332 9.66065 7.04665 9.66065 7.12665 9.68065C7.17332 9.68732 7.21332 9.70065 7.25332 9.72065C7.29332 9.73398 7.33332 9.75398 7.37332 9.78065C7.40665 9.80732 7.43998 9.83399 7.47332 9.86065C7.59332 9.98732 7.66665 10.1607 7.66665 10.334C7.66665 10.5073 7.59332 10.6807 7.47332 10.8073Z" fill="#F73C71" /></svg>';
                $(".missing-category-svg-" + category_index).html(svg);
                $(".missing-category-h6-" + category_index).css(
                    "color",
                    "#E20B0B"
                );
            }
            $("#missing-category-" + category_index).text(response.total_item);
            $(".missing-category-h6-" + category_index).show();

            items++;
            potluck_cateogry_item_count();
            toggleSidebar("sidebar_potluck");
            if (response.qty == 1) {
                $("#potluck-" + category_index).hide();
            }

            var total_self_bring = parseInt(
                $(".total-self-bring-" + category_index).text()
            );
            total_self_bring = total_self_bring + parseInt(self_bringQuantity);
            $(".total-self-bring-" + category_index).text(total_self_bring);
        },
        error: function (xhr, status, error) {
            console.error("An error occurred while storing the User ID.");
        },
    });
});

$("#categoryName, #categoryquantity").on("input", function () {
    $("#categoryname").text("");
    $("#quantityLabel").text("");
});

$(document).on("click", ".addSubItemButton", function () {
    // console.log($(this).parent().html());

    var categoryItem = $(this).closest("li");
    var subItemCount = categoryItem.find(".subItemList li").length;
    // var allowedQuantity = parseInt(sessionStorage.getItem("quantity"));
    var allowedQuantity = $(this).parent().find("#quantity").val();
    if (subItemCount < allowedQuantity) {
        $("#subItemModal").data("categoryItem", categoryItem);
        $("#subItemModal").modal("show");
    } else {
        $(this).hide();
    }
});

$("#subItemName, #category_itemquantity").on("input", function () {
    $("#subitem").text("");
    $("#itemquantity").text("");
});

$("#saveSubItemButton").click(function () {
    var itemName = $("#subItemName").val().trim();
    var itemQuantity = parseInt($("#category_itemquantity").val().trim());
    var selfbring = "0";
    if ($("#selfbring").is(":checked")) {
        selfbring = $("#selfbring").val();
    }

    // alert(selfbring);
    if (itemName == "") {
        $("#subitem").text("Item Name cannot be empty.").css("color", "red");
    } else if (itemQuantity == "" || itemQuantity <= 0) {
        $("#itemquantity")
            .text("Please enter the quantity")
            .css("color", "red");
    } else {
        var subItemName = $("#subItemName").val();
        var categoryItem = $("#subItemModal").data("categoryItem");
        var quantity = $("#category_itemquantity").val();

        if (subItemName.trim() !== "") {
            categoryItem
                .find(".subItemList")
                .append(
                    ' <div class="categoryItem" style="border:1px solid;border-radius:5px;"><p>Quantity: ' +
                        quantity +
                        '</p><li class="list-group-item" data-quantity="' +
                        quantity +
                        '" data-selfbring="' +
                        selfbring +
                        '">' +
                        subItemName +
                        ' <i type="button"class="fa-solid fa-trash delete-btn"></li></div>'
                );
            $("#subItemName").val("");
            $("#subItemModal").modal("hide");
        } else {
            alert("Sub-item name cannot be empty.");
        }
    }
});

$(document).on("click", ".delete-btn", function () {
    $(this).closest(".categoryItem").remove();
    if ($(".subItemList li").length == 0) {
        $(".addSubItemButton").show();
    }
});

$(".increase").click(function () {
    var $input = $(this).siblings(".quantity-input");
    var value = parseInt($input.val());
    $input.val(value + 1);
});

$(".decrease").click(function () {
    var $input = $(this).siblings(".quantity-input");
    var value = parseInt($input.val());
    if (value > 1) {
        $input.val(value - 1);
    }
});

// $("#create_eventForm").submit(function () {
//     $("#create_eventForm").find('input[name="categories"]').remove();
//     var categories = [];
//     $("#categoryList .categorylist").each(function (index) {
//         var categoryName = $(this)
//             .contents()
//             .filter(function () {
//                 return this.nodeType === Node.TEXT_NODE;
//             })
//             .text()
//             .trim();
//         var items = [];
//         $(this)
//             .find(".subItemList .list-group-item")
//             .each(function () {
//                 var itemName = $(this).text().trim();
//                 var quantity = $(this).data("quantity");
//                 var selfbring = $(this).data("selfbring");

//                 items.push({
//                     name: itemName,
//                     quantity: quantity,
//                     selfbring: selfbring,
//                 });
//             });
//         var quantity = $(this).find("#quantity").val();
//         categories.push({
//             name: categoryName,
//             items: items,
//             quantity: quantity,
//         });
//     });

//     $("<input>")
//         .attr({
//             type: "hidden",
//             name: "categories",
//             value: JSON.stringify(categories),
//         })
//         .appendTo("#create_eventForm");

//     var categories = [];

//     let structuredData = {};

//     $(".activityContainer").each(function () {
//         let date = $(this).attr("id").replace("activityContainer-", "");
//         let activities = [];

//         $(this)
//             .find(".activity")
//             .each(function () {
//                 let name = $(this).find("input[name^='activities']").val();
//                 let startTime = $(this)
//                     .find("input[name^='activity_start_time']")
//                     .val();
//                 let endTime = $(this)
//                     .find("input[name^='activity_end_time']")
//                     .val();

//                 activities.push({
//                     name: name,
//                     start_time: startTime,
//                     end_time: endTime,
//                 });
//             });

//         structuredData[date] = activities;

//         if ($('input[name="thankyoucard[]"]').is(":checked")) {
//             {
//                 var template_name = $(this).data();
//                 var send = $(this).data();
//                 var message = $(this).data();
//             }
//         }
//     });

//     $("<input>")
//         .attr("type", "hidden")
//         .attr("name", "activity")
//         .val(JSON.stringify(structuredData))
//         .appendTo("#create_eventForm");

//     let selectedData = {};
//     $('input[name="gift[]"]:checked').each(function (index) {
//         let recipientsName = $(this).data("recipients_name");
//         let registryLink = $(this).data("registrylink");

//         selectedData[index] = {
//             recipientsName: recipientsName,
//             registryLink: registryLink,
//         };
//     });

//     // Create a hidden input field with the formatted data
//     $("<input>")
//         .attr({
//             type: "hidden",
//             name: "giftregistry",
//             value: JSON.stringify(selectedData),
//         })
//         .appendTo("#create_eventForm");
// });
// $(document).on("click", "#start-time", function () {
//     $(this).val("");
// });
$(document).on("blur", "#start-time", function () {
    var s_t = $(this).val();
    var start_time = convertTo24Hour($(this).val());
    var end_time = convertTo24Hour($("#end-time").val());
    $("#ac-start-time").val(s_t);

    var activity_start = $("#firstActivityTime").val();
    var activity_end = $("#LastEndTime").val();

    // if (activity_start == activity_end) {
    //     if (end_time != "") {
    //         if (start_time > end_time) {
    //             toastr.error("please select start time before end time");
    //             $(this).val("");
    //         }
    //     }
    // }
    $(".new_activity").html("");
    $(".activity_total_count").text(0);
    $("#end-time").val("");
    $("#ac-end-time").val("");
    $(".step_1_activity").html(
        '<span><i class="fa-solid fa-triangle-exclamation"></i></span>Setup activity schedule'
    );
});

$(document).on("blur", "#ac-start-time", function () {
    var s_t = $(this).val();
    var start_time = convertTo24Hour($(this).val());
    var end_time = convertTo24Hour($("#ac-end-time").val());

    console.log(start_time);
    console.log(end_time);
    var activity_start_time = $('input[name="activity-start-time[]"]').val();

    if (activity_start_time != "" && activity_start_time != undefined) {
        activity_start_time = convertTo24Hour(activity_start_time);
    }

    if (activity_start_time != "" && start_time > activity_start_time) {
        var old_start_date = $("#start-time").val();
        toastr.error(
            "you can not set activity start time before event start time"
        );
        $(this).val(old_start_date);
        $("#start-time").val(old_start_date);
        return;
    }
    $("#start-time").val(s_t);

    var activity_start = $("#firstActivityTime").val();
    var activity_end = $("#LastEndTime").val();

    if (activity_start == activity_end) {
        if (start_time > end_time) {
            toastr.error("please select start time before end time");
            $(this).val("");
        }
    }

    $(".new_activity").html("");
    $("#end-time").val("");
    $(".activity_total_count").text(0);
    $("#ac-end-time").val("");
});

$(document).on("blur", "#ac-end-time", function () {
    var e_t = $(this).val();
    var end_time = convertTo24Hour($(this).val());
    var start_time = convertTo24Hour($("#ac-start-time").val());
    var form = $(this).closest("form");
    var closestActivityEndTime = form
        .find('input[name="activity-end-time[]"]')
        .first()
        .val();

    if (closestActivityEndTime != "" && closestActivityEndTime != undefined) {
        closestActivityEndTime = convertTo24Hour(closestActivityEndTime);
    }
    $("#end-time").val(e_t);

    if (closestActivityEndTime != "" && end_time < closestActivityEndTime) {
        $(this).val("");
    }

    var activity_start = $("#firstActivityTime").val();
    var activity_end = $("#LastEndTime").val();

    if (activity_start == activity_end) {
        if (end_time < start_time) {
            toastr.error("please select end time after it starts");
            $(this).val("");
        }
    }
});

$(document).on("blur", "#end-time", function () {
    var e_t = $(this).val();
    var end_time = convertTo24Hour($(this).val());
    var start_time = convertTo24Hour($("#start-time").val());

    $("#ac-end-time").val(e_t);
    var activity_start = $("#firstActivityTime").val();
    var activity_end = $("#LastEndTime").val();
    if (activity_start == activity_end) {
        if (
            convertTimeToMinutes(end_time) <= convertTimeToMinutes(start_time)
        ) {
            toastr.error("please select end time after it starts");
            $(this).val("");
        }
    }
});

function convertTimeToMinutes(timeStr) {
    var parts = timeStr.split(":");
    var hours = parseInt(parts[0], 10);
    var minutes = parseInt(parts[1], 10);
    return hours * 60 + minutes;
}

// $(document).on("blur", 'input[name="activity-end-time[]"]', function () {
//     var newEndTime = convertTo24Hour($(this).val());
//     var newStartTime = convertTo24Hour(
//         $(this)
//             .closest(".activity-main-wrp")
//             .find('input[name="activity-start-time[]"]')
//             .val()
//     );

//     if (newEndTime != "" && newStartTime != "" && newEndTime <= newStartTime) {
//         var timeParts = newStartTime.split(":");
//         var startDate = new Date();
//         startDate.setHours(parseInt(timeParts[0]));
//         startDate.setMinutes(parseInt(timeParts[1]));
//         startDate.setMinutes(startDate.getMinutes() + 60);
//         var hours = startDate.getHours().toString().padStart(2, "0");
//         var minutes = startDate.getMinutes().toString().padStart(2, "0");
//         var newEndTimeWith30Min = `${hours}:${minutes}`;

//         $(this).val(newEndTimeWith30Min);
//         newEndTime = convertTo12Hour(newEndTimeWith30Min);
//     }

//     var lastActivityTime = $("#LastEndTime").val();
//     var eventEndTime = convertTo24Hour($("#ac-end-time").val());

//     var lastEndTime = "";
//     var startEndTime = "";
//     $("#" + lastActivityTime)
//         .children()
//         .find(".activity_end_time")
//         .each(function () {
//             lastEndTime = $(this).val();
//         });
//     $("#" + lastActivityTime)
//         .children()
//         .find(".activity_start_time")
//         .each(function () {
//             startEndTime = $(this).val();
//         });
//     // console.log(eventEndTime);
//     // console.log(lastEndTime);

//     if (lastEndTime != "") {
//         if (lastEndTime > eventEndTime) {
//             toastr.error("activity can not finish after events end");
//             var lastActivityNode = $("#" + lastActivityTime)
//                 .parent()
//                 .find(".activity_end_time").length;
//             console.log(lastActivityNode);

//             $("#" + lastActivityTime)
//                 .children()
//                 .find(".activity_end_time")
//                 .each(function (index) {
//                     if (lastActivityNode - 1 == index) {
//                         $(this).val("");
//                     }
//                 });
//         }
//     }
//     if (startEndTime != "") {
//         if (startEndTime > eventEndTime) {
//             toastr.error("activity can not finish after events end");
//             var lastActivityNode = $("#" + lastActivityTime)
//                 .parent()
//                 .find(".activity_end_time").length;
//             console.log(lastActivityNode);

//             $("#" + lastActivityTime)
//                 .children()
//                 .find(".activity_start_time")
//                 .each(function (index) {
//                     if (lastActivityNode - 1 == index) {
//                         $(this).val("");
//                     }
//                 });
//         }
//     }

//     // console.log(newEndTime);
//     $(this)
//         .closest(".activity-main-wrp")
//         .next()
//         .find('input[name="activity-start-time[]"]')
//         .val(newEndTime);
// });

let blurExecutedEndTime = false;
$(document).on("click", 'input[name="activity-end-time[]"]', function (e) {
    e.preventDefault();
    var check_start = $(this)
        .closest(".activity-main-wrp")
        .find('input[name="activity-start-time[]"]')
        .val();

    if (check_start == "") {
        toastr.error("First you need to to set Start Time of Event");
        $(this).val("");
        $(this).datetimepicker("hide"); // Hide time picker if open
        $(this).blur();
        return;
    } else {
        // datepicker();
    }
});

$(document).on("blur", 'input[name="activity-end-time[]"]', function (e) {
    // e.preventDefault();
    // var check_start=$(this)
    // .closest(".activity-main-wrp")
    // .find('input[name="activity-start-time[]"]')
    // .val();

    // if(check_start==""){
    //     toastr.error('First you need to to set Start Time of Event');
    //     return;
    // }
    if (!blurExecutedEndTime) {
        blurExecutedEndTime = true;

        var newEndTime = convertTo24Hour($(this).val());
        var newEndTime12 = convertTo24Hour($(this).val());
        var newEndtimeagain = convertTo12Hour(newEndTime12);
        var newStartTime = convertTo24Hour(
            $(this)
                .closest(".activity-main-wrp")
                .find('input[name="activity-start-time[]"]')
                .val()
        );
        var endtimelatesr = convertTo24Hour($(this).val());
        console.log(newStartTime);
        console.log($(this).val());
        if (newStartTime >= endtimelatesr) {
            var newEndTime = moment(newStartTime, "HH:mm")
                .add(1, "hours")
                .format("HH:mm");
            var newEndTime12 = convertTo12Hour(newEndTime);
            $(this).val(newEndTime12);
        }
        if (
            newEndTime != "" &&
            newStartTime != "" &&
            convertTimeToMinutes(newEndTime) <=
                convertTimeToMinutes(newStartTime)
        ) {
            // alert();
            // var timeParts = newStartTime.split(":");
            // var startDate = new Date();
            // startDate.setHours(parseInt(timeParts[0]));
            // startDate.setMinutes(parseInt(timeParts[1]));
            // startDate.setMinutes(startDate.getMinutes() + 60);
            // var hours = startDate.getHours().toString().padStart(2, "0");
            // var minutes = startDate.getMinutes().toString().padStart(2, "0");
            // var newEndTimeWith30Min = `${hours}:${minutes}`;

            // $(this).val(newEndTimeWith30Min);

            var timeParts = newStartTime.split(":");
            var startDate = new Date();
            startDate.setHours(parseInt(timeParts[0]));
            startDate.setMinutes(parseInt(timeParts[1]));
            startDate.setMinutes(startDate.getMinutes() + 60);
            var hours = startDate.getHours();
            var minutes = startDate.getMinutes().toString().padStart(2, "0");

            // Convert to 12-hour format
            var period = hours >= 12 ? "PM" : "AM";
            hours = hours % 12 || 12; // Convert hour '0' to '12' in 12-hour format
            hours = hours.toString().padStart(2, "0");
            var newEndTimeWith30Min = `${hours}:${minutes} ${period}`;
            // $(this).val(newEndTimeWith30Min);
            newEndTime = convertTo24Hour(newEndTimeWith30Min);

            // return;
        }

        var lastActivityTime = $("#LastEndTime").val();
        var eventEndTime = convertTo24Hour($("#ac-end-time").val());

        var lastEndTime = "";
        var startEndTime = "";
        $("#" + lastActivityTime)
            .children()
            .find(".activity_end_time")
            .each(function () {
                lastEndTime = convertTo24Hour($(this).val());
            });
        $("#" + lastActivityTime)
            .children()
            .find(".activity_start_time")
            .each(function () {
                startEndTime = $(this).val();
            });

        console.log(lastEndTime);
        console.log(eventEndTime);
        if (lastEndTime != "") {
            if (
                convertTimeToMinutes(lastEndTime) >
                convertTimeToMinutes(eventEndTime)
            ) {
                // alert()
                toastr.error("activity can not finish after events end");
                var lastActivityNode = $("#" + lastActivityTime)
                    .parent()
                    .find(".activity_end_time").length;

                // $("#" + lastActivityTime)
                //     .children()
                //     .find(".activity_end_time")
                //     .each(function (index) {
                //         if (lastActivityNode - 1 == index) {
                //             $(this).val("");
                //         }
                //     });
            }
        }
        // if (startEndTime != "") {
        //     if (startEndTime > eventEndTime) {
        //         toastr.error("activity can not finish after events end");
        //         var lastActivityNode = $("#" + lastActivityTime)
        //             .parent()
        //             .find(".activity_end_time").length;

        //         $("#" + lastActivityTime)
        //             .children()
        //             .find(".activity_start_time")
        //             .each(function (index) {
        //                 if (lastActivityNode - 1 == index) {
        //                     $(this).val("");
        //                 }
        //             });
        //     }
        // }

        // $(this)
        //     .closest(".activity-main-wrp")
        //     .next()
        //     .find('input[name="activity-start-time[]"]')
        //     .val(newEndtimeagain);

        setTimeout(function () {
            blurExecutedEndTime = false; // Reset after a delay
        }, 500); // Adjust the delay as needed
    }
});

let blurExecuted = false;
$(document).on("blur", 'input[name="activity-start-time[]"]', function () {
    if (!blurExecuted) {
        blurExecuted = true;

        var newstartTime = convertTo24Hour($(this).val());
        var acStartTime = convertTo24Hour($("#ac-start-time").val());
        var newEndTime = convertTo24Hour(
            $(this)
                .closest(".activity-main-wrp")
                .find('input[name="activity-end-time[]"]')
                .val()
        );

        var firstActivityTime = $("#firstActivityTime").val();
        var firstStartTime = convertTo24Hour(
            $("#" + firstActivityTime)
                .children()
                .find(".activity_start_time")
                .val()
        );

        if (
            convertTimeToMinutes(firstStartTime) <
            convertTimeToMinutes(acStartTime)
        ) {
            console.log(
                $("#" + firstActivityTime)
                    .children()
                    .find(".activity_start_time")
                    .val()
            );
            var schedule_start_time = $("#" + firstActivityTime)
                .children()
                .find(".activity_start_time");
            schedule_start_time.prop("readonly", false);
            schedule_start_time.val("45555985sdsddsd");
            schedule_start_time.prop("readonly", true);

            toastr.error("activity can not start before event");
            $("#" + firstActivityTime)
                .children()
                .find(".activity_start_time")
                .val("");

            // return;
        }

        var preEndTime = $(this)
            .closest(".activity-main-wrp")
            .prev()
            .find('input[name="activity-end-time[]"]')
            .val();

        if (preEndTime !== undefined) {
            console.log(preEndTime);
            newstartTime = convertTo24Hour(newstartTime);
            preEndTime = convertTo24Hour(preEndTime);
            if (
                convertTimeToMinutes(newstartTime) <=
                convertTimeToMinutes(preEndTime)
            ) {
                $(this).val(convertTo12Hour(preEndTime));
            }
        } else {
            var eventStartTime = convertTo24Hour($("#ac-start-time").val());
            if (eventStartTime == "" || eventStartTime === undefined) {
                $("#ac-start-time").val(newstartTime);
                $("#start-time").val(newstartTime);
            }
        }

        var lastActivityTime = $("#LastEndTime").val();
        var eventEndTime = convertTo24Hour($("#ac-end-time").val());
        var startEndTime = "";

        $("#" + lastActivityTime)
            .children()
            .find(".activity_start_time")
            .each(function () {
                startEndTime = convertTo24Hour($(this).val());
            });
        if (startEndTime != "") {
            if (
                convertTimeToMinutes(startEndTime) >
                convertTimeToMinutes(eventEndTime)
            ) {
                toastr.error("activity can not finish after events end");
                var lastActivityNode = $("#" + lastActivityTime)
                    .parent()
                    .find(".activity_end_time").length;
                console.log(lastActivityNode);

                $("#" + lastActivityTime)
                    .children()
                    .find(".activity_start_time")
                    .each(function (index) {
                        if (lastActivityNode - 1 == index) {
                            $(this).val("");
                        }
                    });
            }
        }

        if (
            newEndTime != "" &&
            newstartTime != "" &&
            convertTimeToMinutes(newEndTime) <=
                convertTimeToMinutes(newstartTime)
        ) {
            console.log(newEndTime);
            console.log(newstartTime);

            var timeParts = newstartTime.split(":");
            var startDate = new Date();
            startDate.setHours(parseInt(timeParts[0]));
            startDate.setMinutes(parseInt(timeParts[1]));
            startDate.setMinutes(startDate.getMinutes() + 60);
            var hours = startDate.getHours();
            var minutes = startDate.getMinutes().toString().padStart(2, "0");

            // Convert to 12-hour format
            var period = hours >= 12 ? "PM" : "AM";
            hours = hours % 12 || 12; // Convert hour '0' to '12' in 12-hour format
            hours = hours.toString().padStart(2, "0");
            var newEndTimeWith30Min = `${hours}:${minutes} ${period}`;
            // $(this).val(newEndTimeWith30Min);
            $(this)
                .closest(".activity-main-wrp")
                .find('input[name="activity-end-time[]"]')
                .val(newEndTimeWith30Min);
            // newEndTime = convertTo24Hour(newEndTimeWith30Min);
        }
        setTimeout(function () {
            blurExecuted = false; // Reset after a delay
        }, 500); // Adjust the delay as needed
    }
});

// $(document).on("blur", 'input[name="activity-start-time[]"]', function () {
//     var newstartTime = convertTo24Hour($(this).val());
//     var acStartTime = convertTo24Hour($("#ac-start-time").val());
//     // var activityStartTime = $(this).val();
//     // if(newstartTime<activityStartTime){
//     //     alert('please select further time');
//     //     // $(this).val('');
//     // }
//     var firstActivityTime = $("#firstActivityTime").val();
//     var firstStartTime = $("#" + firstActivityTime)
//         .children()
//         .find(".activity_start_time")
//         .val();
//     if (firstStartTime < acStartTime) {
//         toastr.error("activity can not start before event");
//         $("#" + firstActivityTime)
//             .children()
//             .find(".activity_start_time")
//             .val("");
//     }

//     var preEndTime = convertTo24Hour(
//         $(this)
//             .closest(".activity-main-wrp")
//             .prev()
//             .find('input[name="activity-end-time[]"]')
//             .val()
//     );

//     if (preEndTime !== undefined) {
//         console.log(preEndTime);
//         if (newstartTime <= preEndTime) {
//             $(this).val(convertTo12Hour(preEndTime));
//         }
//     } else {
//         var eventStartTime = convertTo24Hour($("#ac-start-time").val());
//         if (eventStartTime == "" || eventStartTime === undefined) {
//             $("#ac-start-time").val(newstartTime);
//             $("#start-time").val(newstartTime);
//         }
//     }

//     var lastActivityTime = $("#LastEndTime").val();

//     var eventEndTime = convertTo24Hour($("#ac-end-time").val());

//     var startEndTime = "";
//     $("#" + lastActivityTime)
//         .children()
//         .find(".activity_start_time")
//         .each(function () {
//             startEndTime = convertTo24Hour($(this).val());
//         });
//     if (startEndTime != "") {
//         if (startEndTime > eventEndTime) {
//             toastr.error("activity can not finish after events end");
//             var lastActivityNode = $("#" + lastActivityTime)
//                 .parent()
//                 .find(".activity_end_time").length;
//             console.log(lastActivityNode);

//             $("#" + lastActivityTime)
//                 .children()
//                 .find(".activity_start_time")
//                 .each(function (index) {
//                     if (lastActivityNode - 1 == index) {
//                         $(this).val("");
//                     }
//                 });
//         }
//     }
// });

//time converstion function

function convertTo24Hour(time) {
    let [timePart, modifier] = time.split(" ");
    let [hours, minutes] = timePart.split(":");
    if (modifier === "PM" && hours !== "12") {
        hours = parseInt(hours, 10) + 12;
    } else if (modifier === "AM" && hours === "12") {
        hours = "00";
    }
    return `${hours.toString()}:${minutes}`;
}

function convertTo12Hour(time) {
    let [hours, minutes] = time.split(":"); // Split hours and minutes
    let modifier = "AM";
    hours = parseInt(hours, 10);
    if (hours >= 12) {
        modifier = "PM";
        if (hours > 12) {
            hours -= 12;
        }
    } else if (hours === 0) {
        hours = 12;
    }
    return `${hours}:${minutes} ${modifier}`;
}
// Function to check end times
// function checkEndTimes() {
//     const scheduleWrapper = document.querySelector('.activity-schedule-wrp');
//     const endTimes = scheduleWrapper.querySelectorAll('.activity_end_time');

//     let exceedsTime = false;

//     endTimes.forEach((endTimeInput) => {
//         const endTime = endTimeInput.value.trim();

//         if (endTime) {
//             const [time, period] = endTime.split(' '); // Split into time and AM/PM
//             const [hours, minutes] = time.split(':'); // Split time into hours and minutes

//             let endHour = parseInt(hours);
//             let endMinute = parseInt(minutes);

//             // Convert to 24-hour format based on AM/PM
//             if (period === 'PM' && endHour !== 12) {
//                 endHour += 12;
//             } else if (period === 'AM' && endHour === 12) {
//                 endHour = 0;
//             }

//             // Check if time exceeds 11:00 PM (23:00)
//             if (endHour > 23 || (endHour === 23 && endMinute > 0)) {
//                 exceedsTime = true;
//             }
//         }
//     });

//     if (exceedsTime) {
//         alert("One or more activities exceed the end time of 11:00 PM.");
//     } else {
//         alert("All activities are within the valid time range.");
//     }
// }

// Trigger the function on save button click

$(document).on("click", "#save_activity_schedule", function () {
    var start_time = $("#ac-start-time").val();
    var end_time = $("#ac-end-time").val();

    let activityendtime;
    // checkEndTimes();
    $("#start-time").val(start_time);
    $("#end-time").val(end_time);
    var isValid = 0;
    // $(".accordion-body.new_activity").each(function () {
    //     var dataId = $(this).data("id");
    //     activities[dataId] = [];
    //     $(this)
    //         .find(".activity-main-wrp")
    //         .each(function (index) {
    //             var id = $(this).data("id");
    //             var description = $(this)
    //                 .find('input[name="description[]"]')
    //                 .val();
    //             var startTime = $(this)
    //                 .find('input[name="activity-start-time[]"]')
    //                 .val();
    //             var endTime = $(this)
    //                 .find('input[name="activity-end-time[]"]')
    //                 .val();
    //             activityendtime = endTime;
    //             $("#desc-error-" + id).text("");
    //             $("#start-error-" + id).text("");
    //             $("#end-error-" + id).text("");

    //             if (description == "") {
    //                 $("#desc-error-" + id)
    //                     .text("Description is required")
    //                     .css("color", "red");
    //                 isValid++;
    //             }
    //             $(this)
    //                 .find('input[name="description[]"]')
    //                 .on("input", function () {
    //                     if ($(this).val() != "") {
    //                         $("#desc-error-" + id).text("");
    //                     }
    //                 });

    //             if (startTime == "") {
    //                 $("#start-error-" + id).text("Start time is required");
    //                 isValid++;
    //             }
    //             $(this)
    //                 .find('input[name="activity-start-time[]"]')
    //                 .on("change", function () {
    //                     if ($(this).val() != "") {
    //                         $("#start-error-" + id).text("");
    //                     }
    //                 });

    //             if (endTime == "") {
    //                 $("#end-error-" + id).text("End time is required");
    //                 isValid++;
    //             }
    //             $(this)
    //                 .find('input[name="activity-end-time[]"]')
    //                 .on("change", function () {
    //                     if ($(this).val() != "") {
    //                         $("#end-error-" + id).text("");
    //                     }
    //                 });

    //             var activity = {
    //                 activity: description,
    //                 "start-time": startTime,
    //                 "end-time": endTime,
    //             };
    //             activities[dataId].push(activity);
    //         });
    //     // toggleSidebar();
    // });
    var showAlert = false; // Move showAlert outside of the loop so it can be checked globally
    $(".accordion-body.new_activity").each(function () {
        var dataId = $(this).data("id");
        activities[dataId] = [];
        var previousEndTime = null;
        // showAlert = false;

        $(this)
            .find(".activity-main-wrp")
            .each(function (index) {
                var id = $(this).data("id");
                var description = $(this)
                    .find('input[name="description[]"]')
                    .val();
                var startTime = $(this)
                    .find('input[name="activity-start-time[]"]')
                    .val();
                var endTime = $(this)
                    .find('input[name="activity-end-time[]"]')
                    .val();

                activityendtime = endTime;

                $("#desc-error-" + id).text("");
                $("#start-error-" + id).text("");
                $("#end-error-" + id).text("");

                if (description == "") {
                    $("#desc-error-" + id)
                        .text("Description is required")
                        .css("color", "red");
                    isValid++;
                }
                $(this)
                    .find('input[name="description[]"]')
                    .on("input", function () {
                        if ($(this).val() != "") {
                            $("#desc-error-" + id).text("");
                        }
                    });

                if (startTime == "") {
                    $("#start-error-" + id).text("Start time is required");
                    isValid++;
                }
                $(this)
                    .find('input[name="activity-start-time[]"]')
                    .on("change", function () {
                        if ($(this).val() != "") {
                            $("#start-error-" + id).text("");
                        }
                    });

                if (endTime == "") {
                    $("#end-error-" + id).text("End time is required");
                    isValid++;
                }
                $(this)
                    .find('input[name="activity-end-time[]"]')
                    .on("change", function () {
                        if ($(this).val() != "") {
                            $("#end-error-" + id).text("");
                        }
                    });

                var activity = {
                    activity: description,
                    "start-time": startTime,
                    "end-time": endTime,
                };
                activities[dataId].push(activity);

                if (
                    previousEndTime &&
                    previousEndTime > startTime &&
                    !showAlert
                ) {
                    toastr.error("Please enter proper time");
                    showAlert = true;
                    // return;
                } else {
                    showAlert = false;
                }
                previousEndTime = endTime;
            });
    });

    if (showAlert == true) {
        return;
    }
    console.log({ activityendtime });

    let lastendtime = convertTo24Hour(end_time);
    let lastScheduleEndtime = convertTo24Hour(activityendtime);

    console.log(lastendtime);
    console.log(lastScheduleEndtime);

    if (lastScheduleEndtime > lastendtime) {
        toastr.error("Please enter proper time");
        return;
    }

    if (isValid == 0) {
        if (total_activities >= 1) {
            // if (total_activities == 1) {
            //     $(".step_1_activity").text(total_activities + " Activity");
            // } else {
            $(".step_1_activity").text(total_activities + " Activities");
            // }
        } else {
            $(".step_1_activity").html(
                '<span><i class="fa-solid fa-triangle-exclamation"></i></span>Setup activity schedule'
            );
        }

        toggleSidebar();
        console.log(activities);
        eventData.activity = activities;
    }
});

$("#saveGiftRegistryButton").click(function () {
    // e.preventDefault();
    // var categoryName = $("#categoryName").val().trim();
    // var categoryQuantity = parseInt($("#categoryquantity").val().trim());

    // // console.log(categoryQuantity);
    // // $('#errorLabel').text('');
    // if (categoryName == "") {
    //     $("#categoryname")
    //         .text("Category Name cannot be empty.")
    //         .css("color", "red");
    // } else if (categoryQuantity == "" || categoryQuantity <= 0) {
    //     $("#quantityLabel")
    //         .text("Please enter the quantity")
    //         .css("color", "red");
    // } else {
    var Recipients_name = $("#RecipientsName").val();

    var registryLink = $("#registry_link").val();

    if (Recipients_name.trim() !== "") {
        var categoryItem = `<li class="list-group-item categorylist">
            ${Recipients_name}</br>
            ${registryLink}
        <input type="checkbox" id="${Recipients_name}" name="gift[]" data-recipients_name='${Recipients_name}' data-registryLink='${registryLink}'/><i type="button" class="fa-solid fa-trash deletegiftregistry" style="margin-left:330px;display:inline"></i>
        </li>`;
        $("#giftRegistrylist").append(categoryItem);
        $("#RecipientsName").val("");
        $("#giftModal").modal("hide");

        $("#RecipientsName").val("");
        $("#registry_link").val("");
    } else {
        alert("Category name cannot be empty.");
        // }
    }
});

$("#saveThankyoucard").click(function () {
    var template_name = $("#templatename").val();

    var send = $("#send").val();

    var message = $("#thank_message").val();

    if (template_name.trim() !== "") {
        var thankyou = `<li class="list-group-item thankyoucardlist ${template_name}">
 
        <input type="checkbox" id="${template_name}" name="thankyoucard[]" data-template_name='${template_name}' data-send='${send}' data-message='${message}'/><i type="button" class="fa-regular fa-pen-to-square edit_card" style="margin-left:290px;display:inline"></i><i type="button" class="fa-solid fa-trash deletecard" style="margin-left:330px;display:inline"></i>
        <h5>${template_name}</h5></br>
        <p>${message}</p>
        </li>`;
        $("#thankyoulist").append(thankyou);
        $("#thankyouModal").modal("hide");
        $("#templatename").val("");
        $("#send").val("");
        $("#thank_message").val("");
    } else {
        alert("Category name cannot be empty.");
        // }
    }
});

$(document).on("click", ".deletecard", function () {
    $(this).closest(".thankyoucardlist").remove();
});

$(document).on("change", 'input[name="gift[]"]', function () {
    let checkedCount = $('input[type="checkbox"]:checked').length;

    // if (checkedCount > 3) {
    //     alert("You can only select up to two recipients.");
    //     $(this).prop("checked", false);
    // }
});

$(document).on("click", "#edit_saveThankyoucard", function () {
    var template_name = $("#edit_templatename").val();
    var send = $("#edit_send").val();
    var message = $("#edit_thank_message").val();
    var old = $("#old_name").val();

    var li = "." + old;
    $(li).remove();

    var thankyou = `<li class="list-group-item thankyoucardlist ${template_name}">
 
    <input type="checkbox" id="${template_name}" name="thankyoucard[]" data-template_name='${template_name}' data-send='${send}' data-message='${message}'/><i type="button" class="fa-regular fa-pen-to-square edit_card" style="margin-left:290px;display:inline"></i><i type="button" class="fa-solid fa-trash deletecard" style="margin-left:330px;display:inline"></i>
    <h5>${template_name}</h5></br>
    <p>${message}</p>
    </li>`;
    $("#thankyoulist").append(thankyou);
    $("#thankyouModal").modal("hide");
    $("#templatename").val("");
    $("#send").val("");
    $("#thank_message").val("");

    $("#editthankyouModal").modal("hide");

    // console.log()
});

$(document).on("click", "#next_setting", function () {
    $("#loader").css("display", "block");
    savePage3Data();
    checkbox_count();
    $("#loader").css("display", "none");
});

$(document).on("click", "#next_design", function () {
    console.log(eventData);
    console.log(dbJson);
    loadAgain();
    // $(".step_1").hide();
    // handleActiveClass(".li_design");
    // $(".pick-card").addClass("active");
    // $(".design-span").addClass("active");
    // $(".li_event_detail").find(".side-bar-list").addClass("menu-success");
    // $(".li_event_detail").addClass("menu-success");

    // $(".step_2").show();
    // $(".event_create_percent").text("25%");
    // $(".current_step").text("1 of 4");
    // active_responsive_dropdown("drop-down-event-design", "drop-down-pick-card");

    // final_step = 2;
    // eventData.step = final_step;
});

if ($(".edit-design").hasClass("active")) {
    $("#close_createEvent").css("display", "none");
} else {
    $("#close_createEvent").css("display", "block");
}
// $(document).on("click",'.edit-design',function(){
//     $('#close_createEvent').css('display','none');
// });
$(document).on("click", "#close_createEvent", function () {
    var event_type = $("#event-type").val();
    var event_name = $("#event-name").val();
    var event_date = $("#event-date").val();

    if (event_type == "") {
        $("#deleteModal").modal("show");
        // confirm('Event type is empty. Are you sure you want to proceed?')
        return;
    }
    // // if (event_name == "") {
    // //     $("#deleteModal").modal("show");
    // //     return;
    // // }
    if (event_date == "") {
        $("#deleteModal").modal("show");
        return;
    }

    // $('#loader').css('display','block');
    $("#loader").css("display", "block");

    if (event_date != "") {
        // if (event_name != "" && event_date != "") {
        // if (event_type != "" && event_name != "" && event_date != "") {
        let text = $(".current_step").text();
        let firstLetter = text.split(" ")[0];
        // if(firstLetter == '1'){
        //     // savePage1Data('close');
        //     var event_type = $("#event-type").val();
        //     var event_name = $("#event-name").val();
        //     var hostedby = $("#hostedby").val();
        //     var event_date = $("#event-date").val();
        //     var start_time = $("#start-time").val();
        //     var start_time_zone =  $('#start-time-zone').val();
        //     var schedule = $('#schedule').is(":checked");
        //     var end_time = $("#end_time").is(":checked");
        //     var rsvp_by_date_set = $("#rsvp_by_date").is(":checked");
        //     var end_time_zone =  $('#end-time-zone').val();
        //     var address_2 = $("#address2").val();
        //     var address1 = $("#address1").val();
        //     var city = $("#city").val();
        //     var state = $("#state").val();
        //     var zipcode = $("#zipcode").val();
        //     var id = $("#id").val();
        //     var rsvp_by_date = $("#rsvp-by-date").val();
        //     var event_id = $("#event_id").val();
        //     var description = $("#description").val();
        //     var message_to_guests = $("#message_to_guests").val();
        //     var latitude = $("#latitude").val();
        //     var longitude = $("#longitude").val();

        //     var desgin_selected= eventData.desgin_selected;

        //     var events_schedule = '0';
        //     var rsvp_end_time_set = '0';

        //     if(rsvp_by_date_set){
        //         rsvp_by_date_set = '1';
        //     }else{
        //         rsvp_by_date_set = '0';
        //     }
        //     if(schedule){
        //         events_schedule = '1';
        //     }
        //     var rsvp_end_time = '';
        //     if(end_time){
        //         rsvp_end_time = $('#end-time').val();
        //         rsvp_end_time_set = '1';
        //     }

        //     // eventData = {
        //         eventData.event_id = $('#event_id').val();
        //         eventData.event_type = event_type;
        //         eventData.event_name = event_name;
        //         eventData.hosted_by = hostedby;
        //         eventData.event_date = event_date;
        //         eventData.start_time = start_time;
        //         eventData.rsvp_start_timezone = start_time_zone;
        //         eventData.events_schedule = events_schedule;
        //         eventData.activity = activities;
        //         eventData.rsvp_end_time_set = rsvp_end_time_set;
        //         eventData.rsvp_end_time = rsvp_end_time;
        //         eventData.rsvp_end_timezone = end_time_zone;
        //         eventData.rsvp_by_date_set = rsvp_by_date_set;
        //         eventData.rsvp_by_date = rsvp_by_date;
        //         eventData.event_location = description;
        //         eventData.address1 = address1;
        //         eventData.address_2 = address_2;
        //         eventData.city = city;
        //         eventData.state= state;
        //         eventData.zipcode= zipcode;
        //         eventData.message_to_guests=message_to_guests;
        //         eventData.event_id=event_id;
        //         eventData.desgin_selected=desgin_selected;
        //         eventData.latitude=latitude;
        //         eventData.longitude=longitude;
        //     // };
        //     eventData.step = firstLetter;
        //     $(".step_2").hide();
        // }
        if (final_step == 2) {
            savePage1Data(1);
        }
        if (final_step == 3) {
            var savePage3Result = savePage3Data(1);
            console.log(savePage3Result);

            if (savePage3Result === false) {
                $("#loader").css("display", "none");
                return; // Exit if savePage3Data returns a stopping condition
            }
        }

        eventData.step = final_step;
        eventData.isdraft = "1";
        savePage4Data();

        console.log(eventData);

        $.ajax({
            url: base_url + "event/store",
            type: "POST",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            data: eventData,
            success: function (response) {
                if (response == 1) {
                    window.location.href = "home";
                    toastr.success("Event Saved as Draft");
                    setTimeout(function () {
                        $("#loader").css("display", "none");
                    }, 4000);
                }
            },
            error: function (xhr, status, error) {
                console.log("AJAX error: " + error);
            },
        });
    } else {
        eventData.isdraft = "1";
        eventData.step = "1";
        $.ajax({
            url: base_url + "event/store",
            type: "POST",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            data: eventData,
            success: function (response) {
                if (response == 1) {
                    window.location.href = "home";
                    toastr.success("Event Saved as Draft");
                    setTimeout(function () {
                        $("#loader").css("display", "none");
                    }, 4000);
                }
            },
            error: function (xhr, status, error) {
                console.log("AJAX error: " + error);
            },
        });
    }
});

function focus_timeOut(type) {
    setTimeout(function () {
        $("#" + type).focus();
    }, 100);
}
// $('input[type="text"],textarea').on('keydown', function(e) {
//     var event_name = $("#event-name").val();
//     if(event_name==""){
//         if (e.key === " " || e.keyCode === 32) {
//             e.preventDefault(); // Prevent spacebar input
//         }
//     }
// });

$('input[type="text"], textarea').on("keydown", function (e) {
    var currentValue = $(this).val(); // Get the value of the current input/textarea
    if (currentValue === "") {
        if (e.key === " " || e.keyCode === 32) {
            e.preventDefault(); // Prevent spacebar input if empty
        }
    }
});

$('input[type="text"],textarea').on("paste", function (e) {
    const clipboardData = (
        e.originalEvent.clipboardData || window.clipboardData
    ).getData("text");
    if ($.trim(clipboardData) === "") {
        e.preventDefault();
    }
});

function savePage1Data(close = null) {
    var event_type = $("#event-type").val();
    var event_name = $("#event-name").val();
    var hostedby = $("#hostedby").val();
    var event_date = $("#event-date").val();
    var start_time = $("#start-time").val();
    var start_time_zone = $("#start-time-zone").val();
    var end_time_zone = $("#end-time-zone").val();
    var schedule = $("#schedule").is(":checked");
    var end_time = $("#end_time").is(":checked");
    // var rsvp_end_time = $("#end_time").is(":checked");
    var rsvp_end_time = $("#end-time").val();

    var rsvp_by_date_set = $("#rsvp_by_date").is(":checked");
    var address_2 = $("#address2").val();
    var address1 = $("#address1").val();
    var city = $("#city").val();
    var state = $("#state").val();
    var zipcode = $("#zipcode").val();
    var id = $("#id").val();
    var description = $("#description").val();
    var message_to_guests = $("#message_to_guests").val();
    var latitude = $("#latitude").val();
    var longitude = $("#longitude").val();

    var events_schedule = "0";
    var rsvp_end_time_set = "0";

    // if(rsvp_by_date_set){
    //     rsvp_by_date_set = '1';
    // }else{
    //     rsvp_by_date_set = '0';
    // }

    if (close == null || close == "") {
        // var activity=$('.new_append_activity').length;
        // console.log(activity);
        if ($("#schedule").is(":checked")) {
            var activity = $(".event_all_activity_list").length;
            console.log(activity);
            if (activity == 0) {
                toastr.error("Event Schedule: Please set event schedule");
                return;
            }
        }

        if (schedule) {
            events_schedule = "1";
        }
        // var rsvp_end_time = "";
        if (end_time) {
            // rsvp_end_time_set = "1";
            if (rsvp_end_time == "") {
                $("#end-time-error")
                    .css("display", "block")
                    .css("color", "red")
                    .text("End Time: Please select end time");
                focus_timeOut("end-time");
                return;
            } else {
                $("#end-time-error")
                    .css("display", "none")
                    .text("End Time: Please select end time");
            }
        }

        var rsvp_by_date = "";
        if (rsvp_by_date_set) {
            rsvp_by_date = $("#rsvp-by-date").val();
            rsvp_by_date_set = "1";
            if (rsvp_by_date == "") {
                $("#event-rsvpby-error")
                    .css("display", "block")
                    .css("color", "red")
                    .text("RSVP by Date : Please select RSVP date");
                return;
            } else {
                $("#event-rsvpby-error").css("display", "none");
            }
            rsvp_by_date_set = "1";
        } else {
            rsvp_by_date_set = "0";
        }

        if ($("#rsvp_by_date").is(":checked")) {
            rsvp_by_date = $("#rsvp-by-date").val();
            console.log(rsvp_by_date);

            if (rsvp_by_date == "") {
                $("#event-rsvpby-error")
                    .css("display", "block")
                    .css("color", "red")
                    .text("Please select an RSVPby date");
                return;
            } else {
                $("#event-rsvpby-error").css("display", "none");
            }
        }
        if (event_type == "") {
            $("#event-type-error")
                .css("display", "block")
                .css("color", "red")
                .text("Event Type: Please select an event type");
            focus_timeOut("event-type");
            return;
        } else {
            $("#event-type-error").css("display", "none");
        }
        if (event_name == "") {
            $("#event-name-error")
                .css("display", "block")
                .css("color", "red")
                .text("Please enter event name");
            focus_timeOut("event-name");
            return;
        } else {
            $("#event-name-error").css("display", "none");
        }
        if (hostedby == "") {
            $("#event-host-error")
                .css("display", "block")
                .css("color", "red")
                .text("Please enter event host name");
            focus_timeOut("hostedby");
            return;
        } else {
            $("#event-host-error").css("display", "none");
        }
        if (event_date == "") {
            $("#event-date-error")
                .css("display", "block")
                .css("color", "red")
                .text("Event Date: Please select an event date");
            focus_timeOut("event-date");
            return;
        } else {
            $("#event-date-error").css("display", "none");
        }
        if (start_time == "") {
            $("#event-start_time-error")
                .css("display", "block")
                .css("color", "red")
                .text("Start Time: Please select start time");
            focus_timeOut("start-time");
            return;
        } else {
            $("#event-start_time-error").css("display", "none");
        }
        if ($("#isCheckAddress").is(":checked")) {
            if (address1 == "") {
                $("#event-address1-error")
                    .css("display", "block")
                    .css("color", "red")
                    .text("Please enter address1");
                focus_timeOut("address1");
                return;
            } else {
                $("#event-address1-error").css("display", "none");
            }
            if (city == "") {
                $("#event-city-error")
                    .css("display", "block")
                    .css("color", "red")
                    .text("Please enter city");
                focus_timeOut("city");
                return;
            } else {
                $("#event-city-error").css("display", "none");
            }
            if (state == "") {
                $("#event-state-error")
                    .css("display", "block")
                    .css("color", "red")
                    .text("Please enter state");
                focus_timeOut("state");
                return;
            } else {
                $("#event-state-error").css("display", "none");
            }
            if (zipcode == "") {
                $("#event-zipcode-error")
                    .css("display", "block")
                    .css("color", "red")
                    .text("Please enter zipcode");
                focus_timeOut("zipcode");
                return;
            } else {
                $("#event-zipcode-error").css("display", "none");
            }
        }
    }
    if (
        // event_type != "" &&
        event_name != "" &&
        hostedby != "" &&
        event_date != "" &&
        start_time != ""
        // address1 != "" &&
        // city != "" &&
        // state != "" &&
        // zipcode != ""
    ) {
        if (rsvp_end_time_set == "1" && start_time_zone != end_time_zone) {
            $("#end-time-zone").focus();
            $("#end-time-zone-error")
                .text(
                    "End Time zone : Please select same start time zone and end time zone"
                )
                .css("display", "block")
                .css("color", "red");
            return;
        }
        // eventData = {
        if (end_time) {
            rsvp_end_time_set = "1";
        }

        if (rsvp_by_date_set) {
            rsvp_by_date_set = "1";
        } else {
            rsvp_by_date_set = "0";
        }

        eventData.event_id = $("#event_id").val();
        eventData.event_type = event_type;
        eventData.event_name = event_name;
        eventData.hosted_by = hostedby;
        eventData.event_date = event_date;
        eventData.rsvp_by_date_set = rsvp_by_date_set;
        eventData.rsvp_by_date = rsvp_by_date;
        eventData.start_time = start_time;
        eventData.rsvp_start_timezone = start_time_zone;
        eventData.rsvp_end_time_set = rsvp_end_time_set;
        eventData.rsvp_end_time = rsvp_end_time;
        eventData.rsvp_end_timezone = end_time_zone;
        eventData.event_location = description;
        eventData.address1 = address1;
        eventData.address_2 = address_2;
        eventData.state = state;
        eventData.zipcode = zipcode;
        eventData.city = city;
        eventData.message_to_guests = message_to_guests;
        eventData.events_schedule = events_schedule;
        eventData.longitude = longitude;
        eventData.latitude = latitude;
        // activity: activities,
        // };
        // alert();
        let text = $(".current_step").text();
        let firstLetter = text.split(" ")[0];

        var date = new Date(event_date);
        var formattedDate = date.toLocaleDateString("en-US", {
            month: "long",
            day: "numeric",
        });

        // var formattedTime = convertTo12HourFormat(start_time);
        // if(close == 'next'){
        //     $(".step_1").hide();
        //     handleActiveClass('.li_design');
        //     $('.pick-card').addClass('active');
        //     $('.design-span').addClass('active');
        //     $('.li_event_detail').find(".side-bar-list").addClass("menu-success");
        // }
        // // alert(description);
        // // $(".titlename").text(hostedby);
        // // $(".event_name").text(event_name);
        // // $(".event_date").text(formattedDate);
        // // $(".event_address").text(description);
        // // $(".event_time").text(formattedTime);
        // $(".step_2").show();
        // $('.event_create_percent').text('25%');
        // $('.current_step').text('1 of 4');
        // active_responsive_dropdown('drop-down-event-design','drop-down-pick-card');
        // if(final_step == 1){
        //     final_step = 2;
        // }
        // eventData.step = final_step;
        // console.log(eventData);

        // ---------------newcode-------------
        if (close == null || close == "") {
            $(".step_1").css("display", "none");
            $(".step_2").css("display", "none");
            $("#edit-design-temp").css("display", "none");
            $(".step_4").css("display", "none");
            $(".step_final_checkout").css("display", "none");
            $(".step_3").show();
            $(".pick-card").addClass("menu-success");
            $(".edit-design").addClass("menu-success");
            $(".event_create_percent").text("75%");
            $(".current_step").text("3 of 4");
            $("#sidebar_select_design_category").css("display", "none");
            active_responsive_dropdown("drop-down-event-guest");
            console.log("handleActiveClass");

            handleActiveClass(".li_guest");
            $(".li_event_detail")
                .find(".side-bar-list")
                .addClass("menu-success");
            $(".li_event_detail").addClass("menu-success");

            var type = "all";
            const stepVal = $("#CheckCuurentStep").val();
            // alert(stepVal);
            if (stepVal == "0") {
                get_user(type);
            }
            $("#CheckCuurentStep").val("1");

            final_step = 3;
        }
        // $(".step_1").css("display", "none");
        // $(".step_2").css("display", "none");
        // $("#edit-design-temp").css("display", "none");
        // $(".step_4").css("display", "none");
        // $(".step_final_checkout").css("display", "none");
        // $(".step_3").show();
        // $('.pick-card').addClass('menu-success');
        // $('.edit-design').addClass('menu-success');
        // $('.event_create_percent').text('75%');
        // $('.current_step').text('3 of 4');
        // $('#sidebar_select_design_category').css('display','none');
        // active_responsive_dropdown('drop-down-event-guest');
        // handleActiveClass('.li_guest');
        // $('.li_event_detail').find(".side-bar-list").addClass("menu-success");
        // var type="all"
        // const stepVal= $("#CheckCuurentStep").val();
        // // alert(stepVal);
        // if(stepVal=="0"){
        //     get_user(type);
        // }
        // $("#CheckCuurentStep").val("1");

        // final_step = 3;
    }

    // eventData.page1 = {
    //     event_type: event_type,
    //     event_name: event_name,
    //     hostedby: hostedby,
    //     event_date: event_date,
    //     start_time: start_time,
    // };

    // console.log(eventData);

    // window.location.href = '/create-event-page-2';
}

function savePage3Data(close = null) {
    // let invited_user_ids = [];

    // eventData.invited_user_ids = invited_user_ids;

    // console.log(eventData);

    // $.ajax({
    //     url: base_url + "event/get_all_invited_guest",

    //     type: "POST",
    //     headers: {
    //         "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    //     },

    //     success: function (response) {
    if (close == null || close == "") {
        var checkedCheckbox = parseInt($(".invite-count").text());
        if (checkedCheckbox == 0) {
            toastr.error("please first select at list one guest to invite");
            $("#loader").css("display", "none");

            return;
        }
    }
    $(".list_all_invited_user").empty();
    // $(".list_all_invited_user").append(response);
    if (close == null || close == "") {
        $("step_1").hide();
        $(".step_2").hide();
        $(".step_3").hide();
        console.log("handleActiveClass");

        handleActiveClass(".li_setting");
        $(".event_create_percent").text("99%");
        $(".current_step").text("4 of 4");
        $(".step_4").show();
        $(".li_guest").find(".side-bar-list").addClass("menu-success");
        $(".li_guest").addClass("menu-success");
        active_responsive_dropdown("drop-down-event-setting");
        if (final_step == 3) {
            final_step = 4;
        }
        eventData.step = final_step;
    }
    // $("step_1").hide();
    // $(".step_2").hide();
    // $(".step_3").hide();
    // handleActiveClass('.li_setting');
    // $('.event_create_percent').text('99%');
    // $('.current_step').text('4 of 4');
    // $(".step_4").show();
    // $('.li_guest').find(".side-bar-list").addClass("menu-success");
    // active_responsive_dropdown('drop-down-event-setting');
    // if(final_step == 3){
    //     final_step = 4;
    // }
    // eventData.step = final_step;
    //     },
    //     error: function (xhr, status, error) {
    //         console.log("AJAX error: " + error);
    //     },
    // });
}

function savePage4Data() {
    eventData.eventSetting = "1";

    if ($("#allow_for_1_more").is(":checked")) {
        eventData.allow_for_1_more = "1";

        $(".allow_limit_toggle").show();
    } else {
        $(".allow_limit_toggle").hide();
        eventData.allow_for_1_more = "0";
    }

    if ($("#only_adults").is(":checked")) {
        eventData.only_adults = "1";
    } else {
        eventData.only_adults = "0";
    }

    if ($("#thankyou_message").is(":checked")) {
        eventData.thankyou_message = "1";
        $(".thankyou_card").show();
    } else {
        $(".add_new_thankyou_card").html(`<span class="me-3">
                <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M17.1336 12.267L11.8002 2.66699C11.0836 1.37533 10.0919 0.666992 9.00023 0.666992C7.90856 0.666992 6.91689 1.37533 6.20023 2.66699L0.866892 12.267C0.191892 13.492 0.116892 14.667 0.658559 15.592C1.20023 16.517 2.26689 17.0253 3.66689 17.0253H14.3336C15.7336 17.0253 16.8002 16.517 17.3419 15.592C17.8836 14.667 17.8086 13.4837 17.1336 12.267ZM8.37523 6.50033C8.37523 6.15866 8.65856 5.87533 9.00023 5.87533C9.34189 5.87533 9.62523 6.15866 9.62523 6.50033V10.667C9.62523 11.0087 9.34189 11.292 9.00023 11.292C8.65856 11.292 8.37523 11.0087 8.37523 10.667V6.50033ZM9.59189 13.7587C9.55023 13.792 9.50856 13.8253 9.46689 13.8587C9.41689 13.892 9.36689 13.917 9.31689 13.9337C9.26689 13.9587 9.21689 13.9753 9.15856 13.9837C9.10856 13.992 9.05023 14.0003 9.00023 14.0003C8.95023 14.0003 8.89189 13.992 8.83356 13.9837C8.78356 13.9753 8.73356 13.9587 8.68356 13.9337C8.63356 13.917 8.58356 13.892 8.53356 13.8587C8.49189 13.8253 8.45023 13.792 8.40856 13.7587C8.25856 13.6003 8.16689 13.3837 8.16689 13.167C8.16689 12.9503 8.25856 12.7337 8.40856 12.5753C8.45023 12.542 8.49189 12.5087 8.53356 12.4753C8.58356 12.442 8.63356 12.417 8.68356 12.4003C8.73356 12.3753 8.78356 12.3587 8.83356 12.3503C8.94189 12.3253 9.05856 12.3253 9.15856 12.3503C9.21689 12.3587 9.26689 12.3753 9.31689 12.4003C9.36689 12.417 9.41689 12.442 9.46689 12.4753C9.50856 12.5087 9.55023 12.542 9.59189 12.5753C9.74189 12.7337 9.83356 12.9503 9.83356 13.167C9.83356 13.3837 9.74189 13.6003 9.59189 13.7587Z" fill="#E03137"/>
                </svg>
            </span>
            <h5>Select thank you card</h5>`);
        eventData.thank_you_card_id = null;
        $(".thankyou_card").hide();
        eventData.thankyou_message = "0";
    }
    console.log(eventData.thank_you_card_id);

    if ($("#add_co_host").is(":checked")) {
        $(".contactData").css("display", "none");
        $(".guest-contacts-wrp").removeClass("guest-contacts-test");

        eventData.add_co_host = "1";
        $(".add_co_host").show();
    } else {
        $(".contactData").css("display", "none");
        $(".guest-contacts-wrp").removeClass("guest-contacts-test");

        eventData.co_host = "";
        eventData.co_host_prefer_by = "";
        selected_co_host = "";
        selected_co_host_prefer_by = "";
        $(".add_co_host").css("display", "none");
        $(".add_new_co_host").html(`<span class="me-3">
                        <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M17.1336 12.267L11.8002 2.66699C11.0836 1.37533 10.0919 0.666992 9.00023 0.666992C7.90856 0.666992 6.91689 1.37533 6.20023 2.66699L0.866892 12.267C0.191892 13.492 0.116892 14.667 0.658559 15.592C1.20023 16.517 2.26689 17.0253 3.66689 17.0253H14.3336C15.7336 17.0253 16.8002 16.517 17.3419 15.592C17.8836 14.667 17.8086 13.4837 17.1336 12.267ZM8.37523 6.50033C8.37523 6.15866 8.65856 5.87533 9.00023 5.87533C9.34189 5.87533 9.62523 6.15866 9.62523 6.50033V10.667C9.62523 11.0087 9.34189 11.292 9.00023 11.292C8.65856 11.292 8.37523 11.0087 8.37523 10.667V6.50033ZM9.59189 13.7587C9.55023 13.792 9.50856 13.8253 9.46689 13.8587C9.41689 13.892 9.36689 13.917 9.31689 13.9337C9.26689 13.9587 9.21689 13.9753 9.15856 13.9837C9.10856 13.992 9.05023 14.0003 9.00023 14.0003C8.95023 14.0003 8.89189 13.992 8.83356 13.9837C8.78356 13.9753 8.73356 13.9587 8.68356 13.9337C8.63356 13.917 8.58356 13.892 8.53356 13.8587C8.49189 13.8253 8.45023 13.792 8.40856 13.7587C8.25856 13.6003 8.16689 13.3837 8.16689 13.167C8.16689 12.9503 8.25856 12.7337 8.40856 12.5753C8.45023 12.542 8.49189 12.5087 8.53356 12.4753C8.58356 12.442 8.63356 12.417 8.68356 12.4003C8.73356 12.3753 8.78356 12.3587 8.83356 12.3503C8.94189 12.3253 9.05856 12.3253 9.15856 12.3503C9.21689 12.3587 9.26689 12.3753 9.31689 12.4003C9.36689 12.417 9.41689 12.442 9.46689 12.4753C9.50856 12.5087 9.55023 12.542 9.59189 12.5753C9.74189 12.7337 9.83356 12.9503 9.83356 13.167C9.83356 13.3837 9.74189 13.6003 9.59189 13.7587Z" fill="#E03137" />
                        </svg>
                        </span>
                        <h5>Select your co-host</h5>`);
        // $('.add_co_host').html(`<div class="d-flex align-items-center justify-content-between w-100">
        //     <div class="d-flex align-items-center add_new_co_host">
        //         <span class="me-3">
        //             <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
        //                 <path d="M17.1336 12.267L11.8002 2.66699C11.0836 1.37533 10.0919 0.666992 9.00023 0.666992C7.90856 0.666992 6.91689 1.37533 6.20023 2.66699L0.866892 12.267C0.191892 13.492 0.116892 14.667 0.658559 15.592C1.20023 16.517 2.26689 17.0253 3.66689 17.0253H14.3336C15.7336 17.0253 16.8002 16.517 17.3419 15.592C17.8836 14.667 17.8086 13.4837 17.1336 12.267ZM8.37523 6.50033C8.37523 6.15866 8.65856 5.87533 9.00023 5.87533C9.34189 5.87533 9.62523 6.15866 9.62523 6.50033V10.667C9.62523 11.0087 9.34189 11.292 9.00023 11.292C8.65856 11.292 8.37523 11.0087 8.37523 10.667V6.50033ZM9.59189 13.7587C9.55023 13.792 9.50856 13.8253 9.46689 13.8587C9.41689 13.892 9.36689 13.917 9.31689 13.9337C9.26689 13.9587 9.21689 13.9753 9.15856 13.9837C9.10856 13.992 9.05023 14.0003 9.00023 14.0003C8.95023 14.0003 8.89189 13.992 8.83356 13.9837C8.78356 13.9753 8.73356 13.9587 8.68356 13.9337C8.63356 13.917 8.58356 13.892 8.53356 13.8587C8.49189 13.8253 8.45023 13.792 8.40856 13.7587C8.25856 13.6003 8.16689 13.3837 8.16689 13.167C8.16689 12.9503 8.25856 12.7337 8.40856 12.5753C8.45023 12.542 8.49189 12.5087 8.53356 12.4753C8.58356 12.442 8.63356 12.417 8.68356 12.4003C8.73356 12.3753 8.78356 12.3587 8.83356 12.3503C8.94189 12.3253 9.05856 12.3253 9.15856 12.3503C9.21689 12.3587 9.26689 12.3753 9.31689 12.4003C9.36689 12.417 9.41689 12.442 9.46689 12.4753C9.50856 12.5087 9.55023 12.542 9.59189 12.5753C9.74189 12.7337 9.83356 12.9503 9.83356 13.167C9.83356 13.3837 9.74189 13.6003 9.59189 13.7587Z" fill="#E03137" />
        //             </svg>
        //         </span>
        //         <h5>Select your co-host</h5>
        //     </div>
        //     <span>
        //         <svg width="9" height="16" viewBox="0 0 9 16" fill="none" xmlns="http://www.w3.org/2000/svg">
        //             <path d="M1.42505 14.6004L6.85838 9.16706C7.50005 8.52539 7.50005 7.47539 6.85838 6.83372L1.42505 1.40039" stroke="#94A3B8" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
        //         </svg>
        //     </span>
        // </div>`);
        eventData.add_co_host = "0";
    }

    if ($("#gift_registry").is(":checked")) {
        eventData.gift_registry = "1";
        $(".add_gift_registry").show();
    } else {
        $(".add_gift_registry").hide();
        eventData.gift_registry = "0";
    }

    if ($("#eventwall").is(":checked")) {
        eventData.event_wall = "1";
    } else {
        eventData.event_wall = "0";
    }

    if ($("#guest_list_visible_to_guest").is(":checked")) {
        eventData.guest_list_visible_to_guest = "1";
    } else {
        eventData.guest_list_visible_to_guest = "0";
    }

    if ($("#potluck").is(":checked")) {
        eventData.potluck = "1";
        $(".potluck_toggle").show();
    } else {
        $(".potluck_toggle").hide();
        eventData.potluck = "0";
    }

    if ($("#rsvp_update").is(":checked")) {
        eventData.rsvp_update = "1";
    } else {
        eventData.rsvp_update = "0";
    }

    if ($("#event_wall_post").is(":checked")) {
        eventData.event_wall_post = "1";
    } else {
        eventData.event_wall_post = "0";
    }

    if ($("#rsvp_remainder").is(":checked")) {
        eventData.rsvp_remainder = "1";
    } else {
        eventData.rsvp_remainder = "0";
    }

    if ($("#request_photo").is(":checked")) {
        eventData.request_photo = "1";
    } else {
        eventData.request_photo = "0";
    }

    // if ($("#kid_off_event").is(":checked")) {
    //     eventData.kid_off_event = "1";
    // } else {
    //     eventData.kid_off_event = "0";
    // }
}

function convertTo12HourFormat(time) {
    var [hours, minutes] = time.split(":");
    var period = hours >= 12 ? "P.M." : "A.M.";
    hours = hours % 12 || 12; // Convert '0' hours to '12'
    return `${hours}:${minutes} ${period}`;
}

function clearError(input = null) {
    if (input == null) {
        return;
    }
    $("#event-type-error").text("");
    $("#event-name-error").text("");
    $("#event-host-error").text("");
    $("#event-date-error").text("");
    $("#event-rsvpby-error").text("");
    $("#event-start_time-error").text("");
    $("#end-time-error").text("");
    $("#event-address1-error").text("");
    $("#event-city-error").text("");
    $("#event-state-error").text("");
    $("#event-zipcode-error").text("");

    // var recipient_name = $("#recipient_name").val().trim();
    // var registry_link = $("#registry_link").val();
    // if (recipient_name != "") {
    //     $("#recipient_name_error").text("");
    // } else {
    //     $("#recipient_name_error")
    //         .text("Please add recipients name")
    //         .css("color", "red");
    // }

    const id = input.id;

    switch (id) {
        case "thankyou_templatename":
            var templatename = input.value;
            if (templatename === "") {
                $("#template_name_error")
                    .text("Please add template name")
                    .css("color", "red");
            } else {
                $("#template_name_error").text("");
            }
            break;

        case "thankyou_when_to_send":
            var when_to_send = input.value;
            if (when_to_send === "") {
                $("#when_to_send_error")
                    .text("Please time when to send message")
                    .css("color", "red");
            } else {
                $("#when_to_send_error").text("");
            }
            break;

        case "message_for_thankyou":
            var message_to_send = input.value;
            if (message_to_send === "") {
                $("#thankyou_message_error")
                    .text("Please add a thankyou message")
                    .css("color", "red");
            } else {
                $("#thankyou_message_error").text("");
            }
            break;

        case "recipient_name":
            var recipient_name = input.value;
            if (recipient_name === "") {
                $("#recipient_name_error")
                    .text("Please add recipients name")
                    .css("color", "red");
                $(".recipient-name-con").text("0/30");
            } else {
                var recipient_length = recipient_name.length;
                $("#recipient_name_error").text("");
                $(".recipient-name-con").text(recipient_length + "/30");
            }
            break;

        case "new_group_name":
            var groupname = input.value;
            if (groupname === "") {
                $("#group_name_error")
                    .text("Please enter group name")
                    .css("color", "red");
            } else {
                $("#group_name_error").text("");
            }
            break;

        case "categoryName":
            var groupname = input.value;
            if (groupname === "") {
                $("#categoryNameError")
                    .text("Please enter category name")
                    .css("color", "red");
                $(".pot-cate-name").text("0/30");
            } else {
                cateLength = groupname.length;
                $("#categoryNameError").text("");
                $(".pot-cate-name").text(cateLength + "/30");
            }
            break;

        // case "item_name":
        //     var itemname = input.value;
        //     if (itemname === "") {
        //         $("#item_name_error")
        //             .text("Please enter description.")
        //             .css("color", "red");
        //         $('.sub-cat-pot').text('0/30');
        //     } else {
        //         itemLength = itemname.length;
        //         $("#item_name_error").text("");
        //         // $('.sub-cat-pot').text(itemLength+'/30');
        //     }
        //     break;

        case "item_name":
            var groupname = input.value;
            if (groupname === "") {
                $("#item_name_error")
                    .text("Please enter item name")
                    .css("color", "red");
                $(".sub-cat-pot").text("0/30");
            } else {
                cateLength = groupname.length;
                $("#item_name_error").text("");
                $(".sub-cat-pot").text(cateLength + "/30");
            }
            break;

        // Add cases for other fields as needed
    }

    // var templatename=$('#thankyou_templatename').val();
    // var when_to_send=$('#thankyou_when_to_send').val();
    // var message_to_send=$('#message_for_thankyou').val();

    // if (templatename != ""||when_to_send!=""||message_to_send!="") {
    //     $("#template_name_error").text("");
    //     $("#when_to_send_error").text("");
    //     $("#thankyou_message_error").text("");
    // } else {
    //     $("#template_name_error").text("Please add template name").css('color','red');
    //     $("#when_to_send_error").text("Please time when to send message").css('color','red');
    //     $("#thankyou_message_error").text("Please add a thankyou message").css('color','red');
    // }

    // if (registry_link != "") {
    //     $("#registry_link_error").text("");
    //     var validurl = validateURL(registry_link);
    //     if (validurl) {
    //         $("#registry_link_error").text("");
    //     } else {
    //         $("#registry_link_error").text("Please enter an valid url");
    //     }
    // }
}

$(document).on("click", ".cancel-btn-createEvent", function () {
    $("#loader").css("display", "block");
    var url = $(this).data("url");
    // console.log(url);
    window.location.href = url;
});

function handleActiveClass(target) {
    $(".side-bar-list").removeClass("active");
    $(".pick-card").removeClass("active");
    $(".edit-design-sidebar").removeClass("active");
    $(".edit-design").removeClass("active");
    if (target == ".li_design .edit-design-sidebar") {
        $(".edit-design-sidebar").addClass("active");
        $(".pick-card").addClass("menu-success");
        $(".edit-design-sidebar").removeClass("menu-success");
    } else if (target == ".li_design .pick-card") {
        $(".pick-card").addClass("active");
        $(".pick-card").removeClass("menu-success");
        $(".edit-design-sidebar").removeClass("menu-success");
    } else {
        $(target).find(".side-bar-list").addClass("active");
    }
}

function handleActivePlan(target) {
    $(".plans-wrap").removeClass("active");
    $(".plan_check").prop("checked", false);

    $(target).addClass("active");
    $(target).find(".plan_check").prop("checked", true);
}
$(document).on("click", ".li_design .edit-design", function (e) {
    e.preventDefault();
    if ($(".edit-design").hasClass("active")) {
        return;
    }
});

$(document).on("click", ".li_design .pick-card", function (e) {
    $("#close_createEvent").css("display", "block");

    e.preventDefault();
    $(".subcategory-section").show();
    li_design_click();
});
$(document).on("click", ".li_design .edit-design-sidebar", function (e) {
    $("#close_createEvent").css("display", "block");
    e.preventDefault();
    $(".subcategory-section").hide();
    $(".design-span").addClass("active");
    $(".step_1").css("display", "none");
    $(".step_2").css("display", "none");
    $(".step_3").css("display", "none");
    $(".step_4").css("display", "none");

    $(".step_final_checkout").css("display", "none");
    // active_responsive_dropdown("drop-down-event-design", "drop-down-pick-card");
    $(".event_create_percent").text("25%");
    $(".current_step").text("1 of 4");

    // edit_design_modal();

    var subclass = ".side-bar-sub-list";
    console.log("handleActiveClass");
    handleActiveClass(".li_design .edit-design-sidebar");
});

function li_design_click() {
    // console.log(eventData);
    // if (
    //     eventData.event_type != "" &&
    //     eventData.event_name != "" &&
    //     eventData.hosted_by != "" &&
    //     eventData.event_date != "" &&
    //     eventData.start_time != "" &&
    //     eventData.address1 != "" &&
    //     eventData.city != "" &&
    //     eventData.state != "" &&
    //     eventData.zipcode != "" &&
    //     eventData.event_type != undefined &&
    //     eventData.event_name != undefined &&
    //     eventData.hosted_by != undefined &&
    //     eventData.event_date != undefined &&
    //     eventData.start_time != undefined &&
    //     eventData.address1 != undefined &&
    //     eventData.city != undefined &&
    //     eventData.state != undefined &&
    //     eventData.zipcode != undefined
    // ) {

    $(".design-span").addClass("active");
    $(".step_1").css("display", "none");
    $(".step_2").show();
    $(".step_3").css("display", "none");
    $(".step_4").css("display", "none");
    $("#edit-design-temp").css("display", "none");
    $(".step_final_checkout").css("display", "none");
    active_responsive_dropdown("drop-down-event-design", "drop-down-pick-card");
    $(".event_create_percent").text("25%");
    $(".current_step").text("1 of 4");

    // edit_design_modal();

    var subclass = ".side-bar-sub-list";
    console.log("handleActiveClass");
    handleActiveClass(".li_design .pick-card");
    // }
}

function edit_design_modal() {
    var eventDetail2 = $("#eventDetail").val();
    eventDetail2 = JSON.parse(eventDetail2);
    if (
        eventDetail2.static_information != "" &&
        eventData.desgin_selected === undefined
    ) {
        var static_information_json = JSON.parse(
            eventDetail2.static_information
        );
        dbJson = static_information_json.textData;
        console.log({ dbJson });
        $("#modalImage").attr("src", static_information_json.template_url);
        image = static_information_json.template_url;
        $("#imageEditor2").remove();
        var newCanvas = $("<canvas>", {
            id: "imageEditor2",
            width: static_information_json.width,
            height: static_information_json.height,
        });
        $(".modal-design-card").html(newCanvas);
        $("#exampleModal").modal("show");
        $(".edit_design_tem").attr("data-event_id", eventData.event_id);
        canvas = new fabric.Canvas("imageEditor2", {
            width: static_information_json.width,
            height: static_information_json.height,
            position: "relative",
        });
        const defaultSettings = {
            fontSize: 20,
            letterSpacing: 0,
            lineHeight: 1.2,
        };
        fabric.Image.fromURL(image, function (img) {
            var canvasWidth = canvas.getWidth();
            var canvasHeight = canvas.getHeight();
            var scaleFactor = Math.min(
                canvasWidth / img.width,
                canvasHeight / img.height
            );
            img.set({
                left: 0,
                top: 0,
                scaleX: scaleFactor,
                scaleY: scaleFactor,
                selectable: false,
                hasControls: false,
            });
            canvas.setBackgroundImage(img, canvas.renderAll.bind(canvas));
        });

        const staticInfo = {};
        staticInfo.textElements = dbJson;
        staticInfo.textElements.forEach((element) => {
            console.log(element);

            const textMeasurement = new fabric.Text(element.text, {
                fontSize: element.fontSize * 2,
                fontFamily: element.fontFamily,
                fontWeight: element.fontWeight,
                fontStyle: element.fontStyle,
                underline: element.underline,
                linethrough: element.linethrough,
            });
            const textWidth1 = textMeasurement.width;

            var textLeft = element.centerX;
            var textTop = element.centerY;
            // console.log({textLeft});
            // console.log({textTop});
            // console.log({textWidth1});
            let textElement = new fabric.Textbox(element.text, {
                left: textLeft,
                top: textTop,
                width: textWidth1,
                fontSize: element.fontSize * 2,
                fill: element.fill,
                fontFamily: element.fontFamily,
                fontWeight: element.fontWeight,
                fontStyle: element.fontStyle,
                underline: element.underline,
                linethrough: element.linethrough,
                backgroundColor: element.backgroundColor,
                textAlign: element.textAlign,
                textAlign: "left",
                editable: false,
                selectable: false,
                hasControls: false,
                borderColor: "#2DA9FC",
                cornerColor: "#fff",
                cornerSize: 10,
                transparentCorners: false,
                isStatic: true,
                angle: element?.rotation ? element?.rotation : 0,
            });
            canvas.add(textElement);
        });
    }
}

var design_inner_image = "";
$(document).on("click", ".li_event_details", function () {
    console.log({ eventData });
    console.log("here for save image");
    $("#close_createEvent").css("display", "block");
    $("#sidebar_select_design_category").css("display", "none");

    canvas.discardActiveObject();
    canvas.getObjects().forEach((obj) => {
        if (obj.type === "group") {
            canvas.remove(obj); // Your existing function to add icons
        }
    });
    canvas.renderAll();
    setTimeout(() => {
        console.log("here for save image2");

        var downloadImage = document.getElementById("imageEditor1");
        $("#loader").show();
        // eventData.desgin_selected = 'dom_to_image_not_working_in_server';
        $(this).prop("disabled", true);
        $(".btn-close").prop("disabled", true);
        dbJson = getTextDataFromCanvas();
        console.log({ dbJson });
        eventData.textData = dbJson;
        eventData.temp_id = temp_id;
        console.log(downloadImage);
        // save_image_design(downloadImage);
        if ($("#shape_img").attr("src")) {
            design_inner_image = $("#shape_img").attr("src");
        }
        var old_shape_url = $("#first_shape_img").attr("src");
        console.log("here for save image3");

        domtoimage
            .toBlob(downloadImage)
            .then(function (blob) {
                var formData = new FormData();
                formData.append("image", blob, "design.png");
                formData.append("design_inner_image", design_inner_image);
                formData.append("shapeImageUrl", old_shape_url);
                $.ajax({
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    url: base_url + "event/store_temp_design",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        let image = response.image;
                        eventData.desgin_selected = image;
                        console.log("here for save image4");

                        // if(eventData.step == '1'){
                        //     eventData.step = '2';
                        // }
                        console.log(final_step);
                        if (final_step == 1) {
                            final_step = 2;
                        }
                        console.log(eventData);
                        eventData.step = final_step;
                        console.log("Image uploaded and saved successfully");
                        $("#myCustomModal").modal("hide");
                        $("#exampleModal").modal("hide");
                        $("#loader").css("display", "none");
                        $(".store_desgin_temp").prop("disabled", false);
                        $(".btn-close").prop("disabled", false);
                        $(".main-content-wrp").removeClass("blurred");
                        $(".step_2").hide();
                        $(".step_4").hide();
                        $(".step_3").hide();
                        $("#edit-design-temp").hide();

                        // handleActiveClass('.li_guest');
                        $(".pick-card").addClass("menu-success");
                        $(".edit-design").addClass("menu-success");
                        $(".edit-design").removeClass("active");
                        $(".li_design")
                            .find(".side-bar-list")
                            .addClass("menu-success");
                        $(".li_design").addClass("menu-success");

                        // active_responsive_dropdown('drop-down-event-guest');

                        $(".event_create_percent").text("50%");
                        $(".current_step").text("2 of 4");

                        console.log(eventData);

                        var type = "all";
                        // get_user(type);
                        // $('#sidebar_select_design_category').css('display','none');
                        $(".step_1").show();
                        // $(".step_2").css("display", "none");
                        // $("#edit-design-temp").css("display", "none");
                        // $(".step_3").css("display", "none");
                        // $(".step_4").css("display", "none");
                        // $(".step_final_checkout").css("display", "none");
                        active_responsive_dropdown("drop-down-event-detail");

                        // $('.event_create_percent').text('50%');
                        // $('.current_step').text('2 of 4');
                        console.log("handleActiveClass");

                        handleActiveClass(this);
                        var design = eventData.desgin_selected;
                        $(".li_event_detail")
                            .find(".side-bar-list")
                            .addClass("active");
                        if (design == undefined || design == "") {
                        } else {
                            $(".pick-card").addClass("menu-success");
                            $(".edit-design").addClass("menu-success");
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error(
                            "Failed to upload and save the image:",
                            error
                        );
                    },
                });
            })
            .catch(function (error) {
                console.error("Error capturing image:", error);
            });
        $(".main-content-wrp").addClass("blurred");
    }, 500);
});

$(document).on("click", ".li_event_detail", function () {
    step2Open();
});

function active_responsive_dropdown(current_page, design_page = null) {
    $(".drop-down-event-detail").removeClass("active");
    $(".drop-down-event-design").removeClass("active");
    $(".drop-down-pick-card").removeClass("active");
    $(".drop-down-edit-design").removeClass("active");
    $(".drop-down-event-guest").removeClass("active");
    $(".drop-down-event-setting").removeClass("active");
    $("." + current_page).addClass("active");
    if (design_page) {
        $("." + design_page).addClass("active");
    }
}

$(document).on("click", ".li_guest", function () {
    step3open();
});

$(document).on("click", ".li_setting", function () {
    step4open();
});

$(document).on("click", ".download", function () {
    // downloadPhotoAndUpload();
});

function downloadPhotoAndUpload() {
    var base_url = $("#base_url").text();
    var user_id = $("#user_id").val();
    var user_name = $("#user_name").text();
    var filename = "user.jpg";

    htmlToImage
        .toJpeg(document.getElementById("photo"))
        .then(function (dataUrl) {
            var link = document.createElement("a");
            link.href = dataUrl;
            link.download = filename;
            link.click();
        });
}

$(document).on("click", "#delete_invited_user", function () {
    $("#loader").css("display", "block");
    var id = $(this).data("id");
    var userId = $(this).data("userid");
    var total_guest = $(".users-data.invited_user").length;
    var re_total_guest = total_guest - 1;

    var isDisabled = $(this).prop("disabled");
    if (isDisabled) return;
    $(this).prop("disabled", true);

    $(this).prop("disabled", true);
    var remaining_count = parseInt($("#event_guest_left_count").val());

    var re_total_remaining_count = remaining_count + 1;

    // $("#" + id).remove();
    $("#user-" + userId).remove();
    $(".user_id-" + userId).remove();
    //  $(".user-list-responsive").empty();
    //  $(".user-list-responsive").html(response.responsive_view);

    var checkbox = $("." + id);
    checkbox.prop("checked", false);

    $("#event_guest_count").text(re_total_guest + " Guests");
    $(".invite-count").text(re_total_guest);
    // $(".invite-left_d").text("Invites | " + re_total_remaining_count + " Left");
    if (re_total_remaining_count < 0) {
        $(".invite-left_d").text("Invites | 0 Left");
    } else {
        $(".invite-left_d").text(
            "Invites | " + re_total_remaining_count + " Left"
        );
    }

    $("#event_guest_left_count").val(re_total_remaining_count);
    delete_invited_user(userId);
});

$(document).on("click", "#delete_invited_user_tel", function () {
    $("#loader").css("display", "block");

    var id = $(this).data("id");
    var is_contact = $(this).data("contact");

    var isDisabled = $(this).prop("disabled");
    if (isDisabled) return;
    $(this).prop("disabled", true);

    $("#" + id).remove();
    var checkbox = $("." + id);
    var userId = $(this).data("userid");
    if (is_contact == "1") {
        $("#contact_tel-" + userId).remove();
        $(".contact_model_tel-" + userId).remove();
        $(".user_tel-" + userId).prop("checked", false);
    } else {
        $(".user_id_tel-" + userId).remove();
        checkbox.prop("checked", false);
    }

    var total_guest = $(".users-data.invited_user").length;
    var re_total_guest = total_guest;

    var remaining_count = parseInt($("#event_guest_left_count").val());

    var re_total_remaining_count = remaining_count + 1;

    $("#event_guest_count").text(re_total_guest + " Guests");
    $(".invite-count").text(re_total_guest);
    if (re_total_remaining_count < 0) {
        $(".invite-left_d").text("Invites | 0 Left");
    } else {
        $(".invite-left_d").text(
            "Invites | " + re_total_remaining_count + " Left"
        );
    }
    // $(".invite-left_d").text("Invites | " + re_total_remaining_count + " Left");
    $("#event_guest_left_count").val(re_total_remaining_count);

    delete_invited_user(userId, is_contact);
});

function enforceCheckboxLimit() {
    var checkedCount = $("input[name='email_invite[]']:checked").length;
    // console.log(checkedCount);
    const coins = $("#coins").val();
    if (checkedCount >= coins) {
        $("input[name='email_invite[]']:not(:checked)").prop("disabled", true);
        $("input[name='mobile[]']:not(:checked)").prop("disabled", true);
    } else {
        $("input[name='email_invite[]']").prop("disabled", false);
        $("input[name='mobile[]']").prop("disabled", false);
    }
}

enforceCheckboxLimit();

function toggleSidebar(id = null) {
    console.log(id);
    if (id == "sidebar_add_co_host") {
        document.body.classList.add("no-scroll"); // Disable background scrolling
    }
    const allSidebars = document.querySelectorAll(".sidebar");
    const allOverlays = document.querySelectorAll(".overlay");
    // $(".floatingfocus").removeClass("floatingfocus");
    $("#registry_link_error").text("");
    $(".common_error").text("");

    allSidebars.forEach((sidebar) => {
        if (sidebar.style.right === "0px") {
            sidebar.style.right = "-200%";
            sidebar.style.width = "0px";
        }
    });

    allOverlays.forEach((overlay) => {
        if (overlay.classList.contains("visible")) {
            overlay.classList.remove("visible");
        }
    });
    if (id == null) {
        document.body.classList.remove("no-scroll"); // Re-enable background scrolling
        return;
    }
    const sidebar = document.getElementById(id);
    const overlay = document.getElementById(id + "_overlay");

    if (sidebar.style.right === "0px") {
        sidebar.style.right = "-200%";
        sidebar.style.width = "0px";
        if (overlay) {
            overlay.classList.remove("visible");
        }
    } else {
        sidebar.style.right = "0px";
        sidebar.style.width = "100%";
        if (overlay) {
            overlay.classList.add("visible");
        }
    }
}

$(document).on(
    "change",
    "#YesviteUserAll input[name='email_invite[]']",
    function () {
        // enforceCheckboxLimit();
        if (!$(this).is(":checked")) {
            var check = $(this).data("id");
            var userid = $(this).val();
            $("#" + check).remove();
            $(".user_id-" + userid).remove();
            var total_guest = $(".users-data.invited_user").length;
            $("#event_guest_count").text(total_guest + " Guests");
            $(".invite-count").text(total_guest);

            var max_guest = $("#coins").val();

            var remainingCount = max_guest - total_guest;
            if (remainingCount < 0) {
                $(".invite-left_d").text("Invites | 0 Left");
            } else {
                $(".invite-left_d").text(
                    "Invites | " + remainingCount + " Left"
                );
            }
            $("#event_guest_left_count").val(remainingCount);
        }
    }
);

$(document).on("change", "#YesviteUserAll input[name='mobile[]']", function () {
    // enforceCheckboxLimit();
    if (!$(this).is(":checked")) {
        var check = $(this).data("id");
        var userid = $(this).val();
        $("#" + check).remove();
        $(".user_id_tel-" + userid).remove();
        var total_guest = $(".users-data.invited_user").length;
        $("#event_guest_count").text(total_guest + " Guests");
        $(".invite-count").text(total_guest);

        var max_guest = $("#coins").val();

        var remainingCount = max_guest - total_guest;
        if (remainingCount < 0) {
            $(".invite-left_d").text("Invites | 0 Left");
        } else {
            $(".invite-left_d").text("Invites | " + remainingCount + " Left");
        }
        $("#event_guest_left_count").val(remainingCount);
    }
});

$(document).on("change", "#YesviteUserAll .user_choice", function () {
    var groupId = $(this).closest(".user_choice_group").data("id");
    if ($(this).is(":checked")) {
        $('.user_choice_group[data-id="' + groupId + '"] .user_choice')
            .not(this)
            .prop("checked", false);
    } else {
        var id = $(this).data("id");
        $("#" + id).remove();
    }
});

$(document).on("change", ".user_group_member .user_choice", function () {
    var groupId = $(this).closest(".user_choice_group").data("id");
    if ($(this).is(":checked")) {
        $('.user_choice_group[data-id="' + groupId + '"] .user_choice')
            .not(this)
            .prop("checked", false);
    } else {
        var id = $(this).data("id");
        $("#" + id).remove();
    }
});

$(document).on("click", ".delete_potluck_category", function () {
    var delete_id = $(this).data("id");
    $("#delete_potluck_category_id").val(delete_id);
    $(".delete_potluck_title").text("Delete Category");
    $(".delete_potluck_text").text(
        "Deleting this category will delete all items under this category."
    );
    $(".delete_category_text").text("Category deletion is not reversible");
    $("#deleteModal_potluck").modal("show");
});

$(document).on("click", "#delete_potluck_category_btn", function () {
    var potluck_delete_id = $("#delete_potluck_category_id").val();

    $.ajax({
        url: base_url + "event/delete_potluck_category",

        type: "POST",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        data: {
            potluck_delete_id: potluck_delete_id,
        },

        success: function (response) {
            if (potluck_delete_id == "all_potluck") {
                $(".potluck").hide();
                $(".category-main-dishesh").remove();
                category = 0;
                items = 0;
                potluck_cateogry_item_count();
                return;
            }
            category--;

            $("#category_count").val(category);
            items = items - response;
            // console.log(response);

            potluck_cateogry_item_count();
            $(".potluckmain-" + potluck_delete_id).remove();
            $("#delete_potluck_category_id").val("");
        },
        error: function (xhr, status, error) {
            console.log("AJAX error: " + error);
        },
    });
});

$(document).on("change", "#self_bring", function () {
    if ($(this).is(":checked")) {
        $("#self_bring_quantity_toggle").show();
    } else {
        $("#self_bring_quantity_toggle").hide();
    }
});

function potluck_cateogry_item_count() {
    if (category == 0 && items == 0) {
        $(".potluck_count").html(` <span class="me-3">
            <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M17.1336 12.267L11.8002 2.66699C11.0836 1.37533 10.0919 0.666992 9.00023 0.666992C7.90856 0.666992 6.91689 1.37533 6.20023 2.66699L0.866892 12.267C0.191892 13.492 0.116892 14.667 0.658559 15.592C1.20023 16.517 2.26689 17.0253 3.66689 17.0253H14.3336C15.7336 17.0253 16.8002 16.517 17.3419 15.592C17.8836 14.667 17.8086 13.4837 17.1336 12.267ZM8.37523 6.50033C8.37523 6.15866 8.65856 5.87533 9.00023 5.87533C9.34189 5.87533 9.62523 6.15866 9.62523 6.50033V10.667C9.62523 11.0087 9.34189 11.292 9.00023 11.292C8.65856 11.292 8.37523 11.0087 8.37523 10.667V6.50033ZM9.59189 13.7587C9.55023 13.792 9.50856 13.8253 9.46689 13.8587C9.41689 13.892 9.36689 13.917 9.31689 13.9337C9.26689 13.9587 9.21689 13.9753 9.15856 13.9837C9.10856 13.992 9.05023 14.0003 9.00023 14.0003C8.95023 14.0003 8.89189 13.992 8.83356 13.9837C8.78356 13.9753 8.73356 13.9587 8.68356 13.9337C8.63356 13.917 8.58356 13.892 8.53356 13.8587C8.49189 13.8253 8.45023 13.792 8.40856 13.7587C8.25856 13.6003 8.16689 13.3837 8.16689 13.167C8.16689 12.9503 8.25856 12.7337 8.40856 12.5753C8.45023 12.542 8.49189 12.5087 8.53356 12.4753C8.58356 12.442 8.63356 12.417 8.68356 12.4003C8.73356 12.3753 8.78356 12.3587 8.83356 12.3503C8.94189 12.3253 9.05856 12.3253 9.15856 12.3503C9.21689 12.3587 9.26689 12.3753 9.31689 12.4003C9.36689 12.417 9.41689 12.442 9.46689 12.4753C9.50856 12.5087 9.55023 12.542 9.59189 12.5753C9.74189 12.7337 9.83356 12.9503 9.83356 13.167C9.83356 13.3837 9.74189 13.6003 9.59189 13.7587Z" fill="#E03137"/>
            </svg>
        </span>
        <h5>Select potluck</h5>`);
    } else if (category > 0 && items > 0) {
        if (items == 1) {
            $(".potluck_count").html(` <span class="me-3">
            </span>
            <h5>${category} Categoty . ${items} Item</h5>`);
        } else {
            $(".potluck_count").html(` <span class="me-3">
            </span>
            <h5>${category} Categoty . ${items} Items</h5>`);
        }
    } else if (category > 0 && items == 0) {
        $(".potluck_count").html(` <span class="me-3">
            </span>
            <h5>${category} Categoty . ${items} Items</h5>`);
    }
}

$(document).on("click", ".self_bring_quantity", function () {
    var type = $(this).data("type");
    var self_quantity = parseInt($("#self_bring_qty").val());

    var main_quantity = parseInt($("#item_quantity").val());

    if (type == "plus") {
        if (main_quantity > self_quantity) {
            self_quantity++;
            $("#self_bring_qty").val(self_quantity);
        }
    } else {
        if (self_quantity > 0) {
            self_quantity--;
            $("#self_bring_qty").val(self_quantity);
        }
    }
});

$(document).on("click", "#delete-self-bring", function () {
    $("#self_bring").prop("checked", false);
    $("#self_bring_quantity_toggle").hide();
    $("#self_bring_qty").val(0);
});

$(".qty-btnplus").on("click", function () {
    plusBTN($(this));
});

$(document).on("click", ".qty-btn-plus", function () {
    plusBTN($(this));
});
function plusBTN(that) {
    var categoryItemKey = that.parent().find(".category-item-key").val();
    var categoryIndexKey = that.parent().find(".category-index-key").val();
    var categoryItemQuantity = that
        .parent()
        .find(".category-item-quantity")
        .val();
    var itemQuantityMinus = that.parent().find(".item-quantity-minus").val();
    var input = that.siblings(".input-qty");

    var value = parseInt(input.val());
    input.val(value + 1);
    var quantity = parseInt(that.parent().find(".input-qty").val());
    var innerUserQnt = parseInt(that.parent().find(".innerUserQnt").val());
    var isvalidUserQnt = isNaN(innerUserQnt) ? 0 : innerUserQnt;
    if (quantity > 0) {
        that.parent().find(".item-quantity-minus").val(1);
    }
    console.log({ categoryItemQuantity, quantity });
    // if (categoryItemQuantity >= quantity + isvalidUserQnt) {
    update_self_bring(
        that,
        isvalidUserQnt,
        categoryItemKey,
        categoryIndexKey,
        quantity,
        categoryItemQuantity,
        "plus"
    );
    // } else {
    //     quantity--;

    //     that.parent().children(".input-qty").val(quantity);
    // }
}
$(".qty-btnminus").on("click", function () {
    minusBTN($(this));
});
$(document).on("click", ".qty-btn-minus", function () {
    minusBTN($(this));
});

function minusBTN(that) {
    var categoryItemKey = that.parent().find(".category-item-key").val();
    var categoryIndexKey = that.parent().find(".category-index-key").val();
    var categoryItemQuantity = that
        .parent()
        .find(".category-item-quantity")
        .val();
    var itemQuantityMinus = that.parent().find(".item-quantity-minus").val();
    var input = that.siblings(".input-qty");
    var value = parseInt(input.val());
    if (value > 0) {
        input.val(value - 1);
    }
    var quantity = parseInt(that.parent().find(".input-qty").val());
    var innerUserQnt = parseInt(that.parent().find(".innerUserQnt").val());
    var isvalidUserQnt = isNaN(innerUserQnt) ? 0 : innerUserQnt;
    console.log({ categoryItemQuantity, quantity });

    // if (categoryItemQuantity >= quantity + isvalidUserQnt) {
    if (itemQuantityMinus == 1) {
        update_self_bring(
            that,
            isvalidUserQnt,
            categoryItemKey,
            categoryIndexKey,
            quantity,
            categoryItemQuantity,
            "minus"
        );
        if (quantity == 0) {
            that.parent().find(".item-quantity-minus").val(0);
            // that.parent().children(".input-qty").val(0);
        }
    }
    // } else {
    //     // that.parent().find(".input-qty").val(0);
    // }
}

function update_self_bring_bck(
    categoryItemKey,
    categoryIndexKey,
    quantity,
    categoryItemQuantity,
    type
) {
    $.ajax({
        url: base_url + "event/update_self_bring",
        method: "POST",
        data: {
            categoryItemKey: categoryItemKey,
            categoryIndexKey: categoryIndexKey,
            quantity: quantity,
            _token: $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (response) {
            console.log(quantity + "/" + categoryItemQuantity);
            $("#h6-" + categoryItemKey + "-" + categoryIndexKey).text(
                quantity + "/" + categoryItemQuantity
            );

            $("#missing-category-" + categoryIndexKey).text(response);
            // document.getElementById("#missing-category-" + categoryIndexKey).text(response);
            if (response == 0) {
                var svg =
                    '<svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M7.00016 0.333984C3.32683 0.333984 0.333496 3.32732 0.333496 7.00065C0.333496 10.674 3.32683 13.6673 7.00016 13.6673C10.6735 13.6673 13.6668 10.674 13.6668 7.00065C13.6668 3.32732 10.6735 0.333984 7.00016 0.333984ZM10.1868 5.46732L6.40683 9.24732C6.3135 9.34065 6.18683 9.39398 6.0535 9.39398C5.92016 9.39398 5.7935 9.34065 5.70016 9.24732L3.8135 7.36065C3.62016 7.16732 3.62016 6.84732 3.8135 6.65398C4.00683 6.46065 4.32683 6.46065 4.52016 6.65398L6.0535 8.18732L9.48016 4.76065C9.6735 4.56732 9.9935 4.56732 10.1868 4.76065C10.3802 4.95398 10.3802 5.26732 10.1868 5.46732Z" fill="#23AA26"></path></svg>';
                $(".missing-category-svg-" + categoryIndexKey).html(svg);
                console.log({ categoryIndexKey });
                $(".missing-category-h6-" + categoryIndexKey).css(
                    "color",
                    "#34C05C"
                );
            } else {
                var svg =
                    '<svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M13.5067 9.61399L9.23998 1.93398C8.66665 0.900651 7.87332 0.333984 6.99998 0.333984C6.12665 0.333984 5.33332 0.900651 4.75998 1.93398L0.493318 9.61399C-0.0466816 10.594 -0.106682 11.534 0.326652 12.274C0.759985 13.014 1.61332 13.4207 2.73332 13.4207H11.2667C12.3867 13.4207 13.24 13.014 13.6733 12.274C14.1067 11.534 14.0467 10.5873 13.5067 9.61399ZM6.49998 5.00065C6.49998 4.72732 6.72665 4.50065 6.99998 4.50065C7.27332 4.50065 7.49998 4.72732 7.49998 5.00065V8.33398C7.49998 8.60732 7.27332 8.83398 6.99998 8.83398C6.72665 8.83398 6.49998 8.60732 6.49998 8.33398V5.00065ZM7.47332 10.8073C7.43998 10.834 7.40665 10.8607 7.37332 10.8873C7.33332 10.914 7.29332 10.934 7.25332 10.9473C7.21332 10.9673 7.17332 10.9807 7.12665 10.9873C7.08665 10.994 7.03998 11.0007 6.99998 11.0007C6.95998 11.0007 6.91332 10.994 6.86665 10.9873C6.82665 10.9807 6.78665 10.9673 6.74665 10.9473C6.70665 10.934 6.66665 10.914 6.62665 10.8873C6.59332 10.8607 6.55998 10.834 6.52665 10.8073C6.40665 10.6807 6.33332 10.5073 6.33332 10.334C6.33332 10.1607 6.40665 9.98732 6.52665 9.86065C6.55998 9.83399 6.59332 9.80732 6.62665 9.78065C6.66665 9.75398 6.70665 9.73398 6.74665 9.72065C6.78665 9.70065 6.82665 9.68732 6.86665 9.68065C6.95332 9.66065 7.04665 9.66065 7.12665 9.68065C7.17332 9.68732 7.21332 9.70065 7.25332 9.72065C7.29332 9.73398 7.33332 9.75398 7.37332 9.78065C7.40665 9.80732 7.43998 9.83399 7.47332 9.86065C7.59332 9.98732 7.66665 10.1607 7.66665 10.334C7.66665 10.5073 7.59332 10.6807 7.47332 10.8073Z" fill="#F73C71" /></svg>';
                $(".missing-category-svg-" + categoryIndexKey).html(svg);
                $(".missing-category-h6-" + categoryIndexKey).css(
                    "color",
                    "#E20B0B"
                );
            }
            $(
                ".category-item-total-" +
                    categoryItemKey +
                    "-" +
                    categoryIndexKey
            ).text(quantity);

            if (type == "plus") {
                var current_item = parseInt(
                    $(".total-self-bring-" + categoryIndexKey).text()
                );
                current_item = current_item + 1;
                $(".total-self-bring-" + categoryIndexKey).text(current_item);
            } else if (type == "minus") {
                var current_item = parseInt(
                    $(".total-self-bring-" + categoryIndexKey).text()
                );
                current_item = current_item - 1;
                $(".total-self-bring-" + categoryIndexKey).text(current_item);
            }

            if (quantity == categoryItemQuantity) {
                $(
                    "#lumpia-collapseOne" +
                        "-" +
                        categoryItemKey +
                        "-" +
                        categoryIndexKey
                )
                    .parent()
                    .parent()
                    .find(".accordion-item")
                    .removeClass("red-border");

                $(
                    "#lumpia-collapseOne" +
                        "-" +
                        categoryItemKey +
                        "-" +
                        categoryIndexKey
                )
                    .parent()
                    .parent()
                    .find(".accordion-item")
                    .addClass("green-border");

                $(
                    "#success-svg-" + categoryItemKey + "-" + categoryIndexKey
                ).show();
                $(
                    "#danger-svg-" + categoryItemKey + "-" + categoryIndexKey
                ).hide();
                // var missingCategory = $('#missing-category-'+categoryIndexKey).text();
                // missingCategory--;
                //
            } else {
                $(
                    "#lumpia-collapseOne" +
                        "-" +
                        categoryItemKey +
                        "-" +
                        categoryIndexKey
                )
                    .parent()
                    .parent()
                    .find(".accordion-item")
                    .removeClass("green-border");
                $(
                    "#lumpia-collapseOne" +
                        "-" +
                        categoryItemKey +
                        "-" +
                        categoryIndexKey
                )
                    .parent()
                    .parent()
                    .find(".accordion-item")
                    .addClass("red-border");

                $(
                    "#success-svg-" + categoryItemKey + "-" + categoryIndexKey
                ).hide();
                $(
                    "#danger-svg-" + categoryItemKey + "-" + categoryIndexKey
                ).show();
            }

            // console.log($('#lumpia-collapseOne'+'-'+categoryItemKey+'-'+categoryIndexKey).parent().parent().find('.accordion-item').html());
        },
        error: function (xhr, status, error) {
            console.error("An error occurred while storing the User ID.");
        },
    });
}

$(document).on("click", ".delete-self-bring", function () {
    var categoryItemKey = $(this).data("categoryitem");
    var categoryIndexKey = $(this).data("categoryindex");
    var itemquantity = $(this).data("itemquantity");
    // var userquantity = $(this).data("userquantity");
    var userquantity = parseInt(
        $(this).parent().parent().find(".input-qty").val()
    );
    var innerUserQnt = parseInt($(this).data("inneruserqnt"));
    var isvalidUserQnt = isNaN(innerUserQnt) ? 0 : innerUserQnt;
    var userqnt = parseInt($(this).data("userqnt"));
    var isvalidUserQntity = isNaN(userqnt) ? 0 : userqnt;

    $(this).parent().parent().hide();
    var self_bring_quantity = $(this)
        .parent()
        .parent()
        .find(".qty-container")
        .children(".input-qty")
        .val();

    var total_category_count = parseInt(
        $(".total-self-bring-" + categoryIndexKey).text()
    );

    total_category_count = total_category_count - parseInt(self_bring_quantity);
    $(".total-self-bring-" + categoryIndexKey).text(total_category_count);
    $(this)
        .parent()
        .parent()
        .find(".qty-container")
        .children(".input-qty")
        .val(0);
    var that = $(this);
    // console.log({categoryItemKey,categoryIndexKey, itemquantity,self_bring_quantity})
    // $(this).parent().closest('.qty-container').find('.input-qty').val(0);
    update_self_bring(
        that,
        isvalidUserQnt,
        categoryItemKey,
        categoryIndexKey,
        0,
        itemquantity
    );
});

$(document).on("click", ".add-user-list", function () {
    var listid = $(this).data("listid");
    var target = $(this).data("bs-target");
    $("#" + listid).show();
    $(target).toggleClass("collapse show");
    $(target).removeClass("d-none");
});

function validateURL($input) {
    const errorMessage = $("#registry_link_error");
    // const urlPattern =
    //     /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/;
    // const urlPattern = /^(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+\.\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?$/;
    // const urlPattern = /^(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+\.\S{2,})(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?$/;
    const urlPattern =
        /^(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+\.(com|in|net|org))(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?$/;

    const urlValue = $input.val();
    errorMessage.text("");

    if (urlValue === "") {
        // errorMessage.text("Please add registry link").show();
    } else if (!urlPattern.test(urlValue)) {
        errorMessage
            .text(
                "Please enter a valid Link format (e.g., https://example.com)"
            )
            .css("color", "red")
            .show();
    } else {
        errorMessage.text("").hide();
    }
}

$(document).on("keyup", "#registry_link", function () {
    validateURL($(this));
});
var registry_item = 1;
$(document).on("click", ".add_gift_item_btn", function () {
    var recipient_name = $("#recipient_name").val().trim();
    var registry_link = $("#registry_link").val();
    var registry_edit_item = $("#registry_item_id").val();
    // var regex = /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/;
    // var regex = /^(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+\.\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?$/;
    var regex =
        /^(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+\.(com|in|net|org))(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?$/;

    if (recipient_name == "") {
        $("#recipient_name_error")
            .css("display", "block")
            .css("color", "red")
            .text("Please add recipients name");
        return;
    } else {
        $("#recipient_name_error").text("");
    }

    if (registry_link == "") {
        $("#registry_link_error")
            .css("display", "block")
            .css("color", "red")
            .text("Please add registry link");
        return;
    } else {
        $("#registry_link_error").text("");
    }

    if (registry_link != "" && regex.test(registry_link)) {
        if (registry_edit_item != "") {
            var $registryDiv = $("#registry" + registry_edit_item);
            $registryDiv.find("#added_recipient_name").text(recipient_name);
            $registryDiv.find("#added_registry_link").text(registry_link);
        }

        $.ajax({
            url: base_url + "event/add_new_gift_registry",
            type: "POST",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            data: {
                registry_item: registry_edit_item ? registry_edit_item : "",
                recipient_name: recipient_name,
                registry_link: registry_link,
            },
            success: function (response) {
                console.log(response);
                if (response.status == "1") {
                    toastr.success("Registry Updated");
                    $("#registry_item_id").val("");
                }
                $("#registry_list").append(response.view);

                var giftCardCount = $(".trgistry-content").length;
                $(".add_gift_registry_count").html(`<span class="me-3"></span>
                    <h5>${giftCardCount} Registry</h5>`);

                toggleSidebar("sidebar_gift_registry");
                $("#recipient_name").val("");
                $("#registry_link").val("");
            },
            error: function (xhr, status, error) {
                console.log("AJAX error: " + error);
            },
        });
        registry_item++;
    } else {
        $("#registry_link_error")
            .css("display", "block")
            .css("color", "red")
            .text(
                "Please enter a valid Link format (e.g., https://example.com)"
            );
        return;
    }
});

$(document).on("click", ".delete_gift_registry", function () {
    var id = $(this).data("id");
    $("#registry" + id).remove();
    $.ajax({
        url: base_url + "event/remove_gift_registry",
        type: "POST",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        data: {
            registry_item: id,
        },
        success: function (response) {
            console.log(response);
            countGiftRegestry();
        },
        error: function (xhr, status, error) {
            console.log("AJAX error: " + error);
        },
    });
});

$(document).on("click", ".edit_gift_registry", function () {
    var id = $(this).data("id");

    var registryContent = $(this).closest(".trgistry-content");
    var recipientName = registryContent.find("#added_recipient_name").text();
    var registryLink = registryContent.find("#added_registry_link").text();

    $(".recipient-name-con").text(recipientName.length + "/30");
    $("#recipient_name").val(recipientName);
    $("#registry_link").val(registryLink);
    $("#registry_item_id").val(id);
    $(".gift_registry_heading").text("Edit Gift Registry");
    toggleSidebar("sidebar_gift_registry_item");

    $(".form-control").each(function () {
        var text = $(this).val();
        if (text === "") {
            $(this).next().removeClass("floatingfocus");
        } else {
            $(this).next().addClass("floatingfocus");
        }
    });
});

var thankyou_template_id = 1;
$(document).on("click", ".add_thankyou_card", function () {
    var template_name = $("#thankyou_templatename").val();
    var when_to_send = $("#thankyou_when_to_send").val();
    var thankyou_message = $("#message_for_thankyou").val();
    var edit_template_id = $("#edit_template_id").val();

    if (template_name == "") {
        $("#template_name_error")
            .css("display", "block")
            .css("color", "red")
            .text("Please add template name");
        return;
    } else {
        $("#template_name_error").text("");
    }

    if (when_to_send == "") {
        $("#when_to_send_error")
            .css("display", "block")
            .css("color", "red")
            .text("Please time when to send message");
        return;
    } else {
        $("#when_to_send_error").text("");
    }

    if (thankyou_message == "") {
        $("#thankyou_message_error")
            .css("display", "block")
            .css("color", "red")
            .text("Please add a thankyou message");
        return;
    } else {
        $("#thankyou_message_error").css("display", "none");
    }

    $.ajax({
        url: base_url + "event/add_new_thankyou_card",
        type: "POST",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        data: {
            template_name: template_name,
            when_to_send: when_to_send,
            thankyou_message: thankyou_message,
            thankyou_template_id: edit_template_id ? edit_template_id : "",
        },
        success: function (response) {
            console.log(response);
            if (response.status == "1") {
                toastr.success("Greeting card updated");
                $("#edit_template_id").val("");
            } else {
                toastr.success("Greeting card created");
                $("#edit_template_id").val("");
            }
            $(".list_thankyou_card").html(response.view);
            console.log(eventData.thank_you_card_id);
            if (eventData.thank_you_card_id != undefined) {
                $('input[name="select_thankyou[]"]').each(function () {
                    if ($(this).val() == eventData.thank_you_card_id) {
                        $(this).prop("checked", true); // Check the checkbox
                    }
                });
            }
            var thankscardcount = $(".thank-you-card").length;
            $(".add_new_thankyou_card").html(
                `<span class="me-3"></span><h5>${thankscardcount} Templates available</h5>`
            );

            $("#message_for_thankyou").val("");
            $("#thankyou_when_to_send").val("");
            $("#thankyou_templatename").val("");
            toggleSidebar("sidebar_thankyou_card");
            $(".floatingfocus").removeClass("floatingfocus");
        },
        error: function (xhr, status, error) {
            console.log("AJAX error: " + error);
        },
    });
    thankyou_template_id++;
});

$(document).on("click", ".delete_thankyou_card", function () {
    var id = $(this).data("id");
    $("#thankyou" + id).remove();
    $.ajax({
        url: base_url + "event/remove_thankyou_card",
        type: "POST",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        data: {
            thank_you_card_id: id,
        },
        success: function (response) {
            var thankscardcount = $(".thank-you-card").length;
            if (thankscardcount == 0) {
                $(".add_new_thankyou_card").html(`<span class="me-3">
                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M17.1336 12.267L11.8002 2.66699C11.0836 1.37533 10.0919 0.666992 9.00023 0.666992C7.90856 0.666992 6.91689 1.37533 6.20023 2.66699L0.866892 12.267C0.191892 13.492 0.116892 14.667 0.658559 15.592C1.20023 16.517 2.26689 17.0253 3.66689 17.0253H14.3336C15.7336 17.0253 16.8002 16.517 17.3419 15.592C17.8836 14.667 17.8086 13.4837 17.1336 12.267ZM8.37523 6.50033C8.37523 6.15866 8.65856 5.87533 9.00023 5.87533C9.34189 5.87533 9.62523 6.15866 9.62523 6.50033V10.667C9.62523 11.0087 9.34189 11.292 9.00023 11.292C8.65856 11.292 8.37523 11.0087 8.37523 10.667V6.50033ZM9.59189 13.7587C9.55023 13.792 9.50856 13.8253 9.46689 13.8587C9.41689 13.892 9.36689 13.917 9.31689 13.9337C9.26689 13.9587 9.21689 13.9753 9.15856 13.9837C9.10856 13.992 9.05023 14.0003 9.00023 14.0003C8.95023 14.0003 8.89189 13.992 8.83356 13.9837C8.78356 13.9753 8.73356 13.9587 8.68356 13.9337C8.63356 13.917 8.58356 13.892 8.53356 13.8587C8.49189 13.8253 8.45023 13.792 8.40856 13.7587C8.25856 13.6003 8.16689 13.3837 8.16689 13.167C8.16689 12.9503 8.25856 12.7337 8.40856 12.5753C8.45023 12.542 8.49189 12.5087 8.53356 12.4753C8.58356 12.442 8.63356 12.417 8.68356 12.4003C8.73356 12.3753 8.78356 12.3587 8.83356 12.3503C8.94189 12.3253 9.05856 12.3253 9.15856 12.3503C9.21689 12.3587 9.26689 12.3753 9.31689 12.4003C9.36689 12.417 9.41689 12.442 9.46689 12.4753C9.50856 12.5087 9.55023 12.542 9.59189 12.5753C9.74189 12.7337 9.83356 12.9503 9.83356 13.167C9.83356 13.3837 9.74189 13.6003 9.59189 13.7587Z" fill="#E03137"></path>
                    </svg>
                </span><h5>Select thank you card</h5>`);
            } else {
                $(".add_new_thankyou_card").html(
                    `<span class="me-3"></span><h5>${thankscardcount} Templates available</h5>`
                );
            }
        },
        error: function (xhr, status, error) {
            console.log("AJAX error: " + error);
        },
    });
});

$(document).on("click", ".edit_thankyou_card", function () {
    var id = $(this).data("id");

    var $thankYouCard = $(this).closest(".thank-you-card");
    var templateName = $thankYouCard
        .find("#edit_thankyou_template_name")
        .text();
    var message = $thankYouCard.find("#edit_thankyou_message").text();
    var when_to_send = $thankYouCard.find("#edit_when_to_send").val();

    $("#thankyou_templatename").val(templateName);
    $("#thankyou_when_to_send").val(when_to_send);
    $("#message_for_thankyou").val(message);
    $("#edit_template_id").val(id);

    toggleSidebar("sidebar_add_thankyou_card");
    $(".thankyoucard_heading").text("Edit thank you card");

    $(".form-control").each(function () {
        var text = $(this).val();
        if (text === "") {
            $(this).next().removeClass("floatingfocus");
        } else {
            $(this).next().addClass("floatingfocus");
        }
    });
});

// $(document).on("change", 'input[name="select_thankyou[]"]', function () {
//     $('input[name="select_thankyou[]"]').not(this).prop("checked", false);
// });

$(document).on("click", "#close_thankyou_card_popup", function () {
    $("#thankyou_card_popup").remove();
    ajax_tip_close("thankyou_card");
});

$(document).on("click", ".save_allow_limit", function () {
    var allow_limit = $("#allow_limit_count").val();
    eventData.allow_limit_count = allow_limit;
    console.log(allow_limit);
    if (allow_limit == 0) {
        $(".allow_for_limit_count")
            .html(`<div class="d-flex align-items-center add_new_limit">
                <span class="me-3">
                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M17.1336 12.267L11.8002 2.66699C11.0836 1.37533 10.0919 0.666992 9.00023 0.666992C7.90856 0.666992 6.91689 1.37533 6.20023 2.66699L0.866892 12.267C0.191892 13.492 0.116892 14.667 0.658559 15.592C1.20023 16.517 2.26689 17.0253 3.66689 17.0253H14.3336C15.7336 17.0253 16.8002 16.517 17.3419 15.592C17.8836 14.667 17.8086 13.4837 17.1336 12.267ZM8.37523 6.50033C8.37523 6.15866 8.65856 5.87533 9.00023 5.87533C9.34189 5.87533 9.62523 6.15866 9.62523 6.50033V10.667C9.62523 11.0087 9.34189 11.292 9.00023 11.292C8.65856 11.292 8.37523 11.0087 8.37523 10.667V6.50033ZM9.59189 13.7587C9.55023 13.792 9.50856 13.8253 9.46689 13.8587C9.41689 13.892 9.36689 13.917 9.31689 13.9337C9.26689 13.9587 9.21689 13.9753 9.15856 13.9837C9.10856 13.992 9.05023 14.0003 9.00023 14.0003C8.95023 14.0003 8.89189 13.992 8.83356 13.9837C8.78356 13.9753 8.73356 13.9587 8.68356 13.9337C8.63356 13.917 8.58356 13.892 8.53356 13.8587C8.49189 13.8253 8.45023 13.792 8.40856 13.7587C8.25856 13.6003 8.16689 13.3837 8.16689 13.167C8.16689 12.9503 8.25856 12.7337 8.40856 12.5753C8.45023 12.542 8.49189 12.5087 8.53356 12.4753C8.58356 12.442 8.63356 12.417 8.68356 12.4003C8.73356 12.3753 8.78356 12.3587 8.83356 12.3503C8.94189 12.3253 9.05856 12.3253 9.15856 12.3503C9.21689 12.3587 9.26689 12.3753 9.31689 12.4003C9.36689 12.417 9.41689 12.442 9.46689 12.4753C9.50856 12.5087 9.55023 12.542 9.59189 12.5753C9.74189 12.7337 9.83356 12.9503 9.83356 13.167C9.83356 13.3837 9.74189 13.6003 9.59189 13.7587Z" fill="#E03137" />
                    </svg>
                </span>
                <h5>Add +1 limit</h5>
            </div>
            <span>
                <svg width="9" height="16" viewBox="0 0 9 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M1.42505 14.6004L6.85838 9.16706C7.50005 8.52539 7.50005 7.47539 6.85838 6.83372L1.42505 1.40039" stroke="#94A3B8" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </span>`);
    } else {
        $(".allow_for_limit_count")
            .html(`<div class="d-flex align-items-center add_new_limit">
        <span class="me-3">
        </span>
        <h5>Limit set to ${allow_limit}</h5>
    </div>
    <span>
        <svg width="9" height="16" viewBox="0 0 9 16" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M1.42505 14.6004L6.85838 9.16706C7.50005 8.52539 7.50005 7.47539 6.85838 6.83372L1.42505 1.40039" stroke="#94A3B8" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
    </span>`);
    }

    toggleSidebar();
});

$(document).on("change", 'input[name="guest_list[]"]', function () {
    //    if ($("input[name='guest_list[]']:checked").length > 0) {
    co_host_is_selected_close = false;
    const old_user_id = selected_co_host;
    const newUserID = $(this).val();
    if (lengtUSer > 0 && newUserID != old_user_id) {
        $(this).prop("checked", false);
        toastr.error("You can select only one co-host");
        return;
    } else {
        if ($(this).is(":checked")) {
            var profilePhoto = $(this).data("profile");
            var user_name = $(this).data("username");
            var profile_or_text = $(this).data("profile_or_text");
            var initial = $(this).data("initial");
            var prefer_by_email = $(this).data("prefer_by");
            selected_co_host = $(this).val();

            selected_user_name = user_name;
            selected_profilePhoto = profilePhoto;
            selected_dataId = selected_co_host;
            selected_profile_or_text = profile_or_text;
            selected_prefer_by = prefer_by_email;

            // console.log(profile_or_text);
            if (profile_or_text == "1") {
                $(".selected-co-host-image").show();
                $(".guest-img .selected-co-host-image").attr(
                    "src",
                    profilePhoto
                );
                $(".guest-img .selected-host-h5").css("display", "none");
            } else {
                // $('.guest-img').html(profilePhoto);
                $(".guest-img .selected-host-h5").show();
                $(".guest-img .selected-co-host-image").css("display", "none");
                // $('.guest-img').html(profilePhoto);

                $(".guest-img .selected-host-h5").text(initial);
                var firstinitial = initial.charAt(0);
                // $('.selected-host-h5').removeClass(function (index, className) {
                //     return (className.match(/\bfontcolor\S+/g) || []).join(' ');
                // });
                // $('.selected-host-h5').addClass('fontcolor'+firstinitial);
                $(".guest-img .selected-host-h5").removeClass(function (
                    index,
                    className
                ) {
                    return (className.match(/\bfontcolor\S+/g) || []).join(" ");
                });

                // Add the new class
                $(".guest-img .selected-host-h5").addClass(
                    "fontcolor" + firstinitial
                );
            }
            $(".remove_co_host").attr("data-id", selected_co_host);
            $("#remove_co_host_id").val("user-" + selected_co_host);
            $(".selected-host-name").text(user_name);
            $(".contactData").css("display", "flex");
            $(".guest-contacts-wrp").addClass("guest-contacts-test");

            if (prefer_by_email) {
                if (prefer_by_email == "email") {
                    $(".phoneCheck").prop("checked", false);
                } else {
                    $(".emailCheck").prop("checked", false);
                }
                selected_co_host_prefer_by = prefer_by_email;
            } else {
                if ($("input[name='guest_list[]']:checked").length === 0) {
                    $(".contactData").css("display", "none");
                    $(".guest-contacts-wrp").removeClass("guest-contacts-test");

                    // $('.add_new_co_host').html(`<span class="me-3">
                    //     <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                    //     <path d="M17.1336 12.267L11.8002 2.66699C11.0836 1.37533 10.0919 0.666992 9.00023 0.666992C7.90856 0.666992 6.91689 1.37533 6.20023 2.66699L0.866892 12.267C0.191892 13.492 0.116892 14.667 0.658559 15.592C1.20023 16.517 2.26689 17.0253 3.66689 17.0253H14.3336C15.7336 17.0253 16.8002 16.517 17.3419 15.592C17.8836 14.667 17.8086 13.4837 17.1336 12.267ZM8.37523 6.50033C8.37523 6.15866 8.65856 5.87533 9.00023 5.87533C9.34189 5.87533 9.62523 6.15866 9.62523 6.50033V10.667C9.62523 11.0087 9.34189 11.292 9.00023 11.292C8.65856 11.292 8.37523 11.0087 8.37523 10.667V6.50033ZM9.59189 13.7587C9.55023 13.792 9.50856 13.8253 9.46689 13.8587C9.41689 13.892 9.36689 13.917 9.31689 13.9337C9.26689 13.9587 9.21689 13.9753 9.15856 13.9837C9.10856 13.992 9.05023 14.0003 9.00023 14.0003C8.95023 14.0003 8.89189 13.992 8.83356 13.9837C8.78356 13.9753 8.73356 13.9587 8.68356 13.9337C8.63356 13.917 8.58356 13.892 8.53356 13.8587C8.49189 13.8253 8.45023 13.792 8.40856 13.7587C8.25856 13.6003 8.16689 13.3837 8.16689 13.167C8.16689 12.9503 8.25856 12.7337 8.40856 12.5753C8.45023 12.542 8.49189 12.5087 8.53356 12.4753C8.58356 12.442 8.63356 12.417 8.68356 12.4003C8.73356 12.3753 8.78356 12.3587 8.83356 12.3503C8.94189 12.3253 9.05856 12.3253 9.15856 12.3503C9.21689 12.3587 9.26689 12.3753 9.31689 12.4003C9.36689 12.417 9.41689 12.442 9.46689 12.4753C9.50856 12.5087 9.55023 12.542 9.59189 12.5753C9.74189 12.7337 9.83356 12.9503 9.83356 13.167C9.83356 13.3837 9.74189 13.6003 9.59189 13.7587Z" fill="#E03137" />
                    //     </svg>
                    //     </span>
                    //     <h5>Select your co-host</h5>`);
                }
            }
            lengtUSer++;
        } else {
            if ($("input[name='guest_list[]']:checked").length === 0) {
                selected_co_host = "";
                selected_co_host_prefer_by = "";
                selected_dataId = "";
                lengtUSer = 0;
                $(".contactData").css("display", "none");
                $(".guest-contacts-wrp").removeClass("guest-contacts-test");

                // $('.add_new_co_host').html(`<span class="me-3">
                //     <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                //     <path d="M17.1336 12.267L11.8002 2.66699C11.0836 1.37533 10.0919 0.666992 9.00023 0.666992C7.90856 0.666992 6.91689 1.37533 6.20023 2.66699L0.866892 12.267C0.191892 13.492 0.116892 14.667 0.658559 15.592C1.20023 16.517 2.26689 17.0253 3.66689 17.0253H14.3336C15.7336 17.0253 16.8002 16.517 17.3419 15.592C17.8836 14.667 17.8086 13.4837 17.1336 12.267ZM8.37523 6.50033C8.37523 6.15866 8.65856 5.87533 9.00023 5.87533C9.34189 5.87533 9.62523 6.15866 9.62523 6.50033V10.667C9.62523 11.0087 9.34189 11.292 9.00023 11.292C8.65856 11.292 8.37523 11.0087 8.37523 10.667V6.50033ZM9.59189 13.7587C9.55023 13.792 9.50856 13.8253 9.46689 13.8587C9.41689 13.892 9.36689 13.917 9.31689 13.9337C9.26689 13.9587 9.21689 13.9753 9.15856 13.9837C9.10856 13.992 9.05023 14.0003 9.00023 14.0003C8.95023 14.0003 8.89189 13.992 8.83356 13.9837C8.78356 13.9753 8.73356 13.9587 8.68356 13.9337C8.63356 13.917 8.58356 13.892 8.53356 13.8587C8.49189 13.8253 8.45023 13.792 8.40856 13.7587C8.25856 13.6003 8.16689 13.3837 8.16689 13.167C8.16689 12.9503 8.25856 12.7337 8.40856 12.5753C8.45023 12.542 8.49189 12.5087 8.53356 12.4753C8.58356 12.442 8.63356 12.417 8.68356 12.4003C8.73356 12.3753 8.78356 12.3587 8.83356 12.3503C8.94189 12.3253 9.05856 12.3253 9.15856 12.3503C9.21689 12.3587 9.26689 12.3753 9.31689 12.4003C9.36689 12.417 9.41689 12.442 9.46689 12.4753C9.50856 12.5087 9.55023 12.542 9.59189 12.5753C9.74189 12.7337 9.83356 12.9503 9.83356 13.167C9.83356 13.3837 9.74189 13.6003 9.59189 13.7587Z" fill="#E03137" />
                //     </svg>
                //     </span>
                //     <h5>Select your co-host</h5>`);
            }
        }
    }
});

$(document).on("click", ".remove_co_host", function () {
    lengtUSer = 0;
    var hostId = $(this).data("id");
    // eventData.co_host = '';
    // eventData.co_host_prefer_by = '';
    selected_co_host = "";
    // selected_co_host_prefer_by = '';
    selected_dataId = "";
    co_host_is_selected_close = true;

    $(".contactData").css("display", "none");
    $(".guest-contacts-wrp").removeClass("guest-contacts-test");

    // $('.add_new_co_host').html(`<span class="me-3">
    //         <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
    //         <path d="M17.1336 12.267L11.8002 2.66699C11.0836 1.37533 10.0919 0.666992 9.00023 0.666992C7.90856 0.666992 6.91689 1.37533 6.20023 2.66699L0.866892 12.267C0.191892 13.492 0.116892 14.667 0.658559 15.592C1.20023 16.517 2.26689 17.0253 3.66689 17.0253H14.3336C15.7336 17.0253 16.8002 16.517 17.3419 15.592C17.8836 14.667 17.8086 13.4837 17.1336 12.267ZM8.37523 6.50033C8.37523 6.15866 8.65856 5.87533 9.00023 5.87533C9.34189 5.87533 9.62523 6.15866 9.62523 6.50033V10.667C9.62523 11.0087 9.34189 11.292 9.00023 11.292C8.65856 11.292 8.37523 11.0087 8.37523 10.667V6.50033ZM9.59189 13.7587C9.55023 13.792 9.50856 13.8253 9.46689 13.8587C9.41689 13.892 9.36689 13.917 9.31689 13.9337C9.26689 13.9587 9.21689 13.9753 9.15856 13.9837C9.10856 13.992 9.05023 14.0003 9.00023 14.0003C8.95023 14.0003 8.89189 13.992 8.83356 13.9837C8.78356 13.9753 8.73356 13.9587 8.68356 13.9337C8.63356 13.917 8.58356 13.892 8.53356 13.8587C8.49189 13.8253 8.45023 13.792 8.40856 13.7587C8.25856 13.6003 8.16689 13.3837 8.16689 13.167C8.16689 12.9503 8.25856 12.7337 8.40856 12.5753C8.45023 12.542 8.49189 12.5087 8.53356 12.4753C8.58356 12.442 8.63356 12.417 8.68356 12.4003C8.73356 12.3753 8.78356 12.3587 8.83356 12.3503C8.94189 12.3253 9.05856 12.3253 9.15856 12.3503C9.21689 12.3587 9.26689 12.3753 9.31689 12.4003C9.36689 12.417 9.41689 12.442 9.46689 12.4753C9.50856 12.5087 9.55023 12.542 9.59189 12.5753C9.74189 12.7337 9.83356 12.9503 9.83356 13.167C9.83356 13.3837 9.74189 13.6003 9.59189 13.7587Z" fill="#E03137" />
    //         </svg>
    //         </span>
    //         <h5>Select your co-host</h5>`);
    var delete_co_host = $("#remove_co_host_id").val();

    //   alert(delete_co_host);
    $("." + delete_co_host).prop("checked", false);
    var checkedCheckbox = $('input[name="guest_list[]"]:checked');

    if (checkedCheckbox.length > 0) {
        checkedCheckbox.prop("checked", false); // Uncheck all checked checkboxes
    }
});

$(document).on("click", ".save_event_co_host", function () {
    var checkedCheckbox = $('input[name="guest_list[]"]:checked');
    if ($("#contact-tab").hasClass("active")) {
        get_contact_status = "yesvite";
    }

    if ($("#phone-tab-cantact").hasClass("active")) {
        get_contact_status = "contacts";
    }

    $(".add_co_host").attr("data-contact", get_contact_status);

    if (checkedCheckbox.length === 0) {
        // alert();
        $(".add_new_co_host").html(`<span class="me-3">
            <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M17.1336 12.267L11.8002 2.66699C11.0836 1.37533 10.0919 0.666992 9.00023 0.666992C7.90856 0.666992 6.91689 1.37533 6.20023 2.66699L0.866892 12.267C0.191892 13.492 0.116892 14.667 0.658559 15.592C1.20023 16.517 2.26689 17.0253 3.66689 17.0253H14.3336C15.7336 17.0253 16.8002 16.517 17.3419 15.592C17.8836 14.667 17.8086 13.4837 17.1336 12.267ZM8.37523 6.50033C8.37523 6.15866 8.65856 5.87533 9.00023 5.87533C9.34189 5.87533 9.62523 6.15866 9.62523 6.50033V10.667C9.62523 11.0087 9.34189 11.292 9.00023 11.292C8.65856 11.292 8.37523 11.0087 8.37523 10.667V6.50033ZM9.59189 13.7587C9.55023 13.792 9.50856 13.8253 9.46689 13.8587C9.41689 13.892 9.36689 13.917 9.31689 13.9337C9.26689 13.9587 9.21689 13.9753 9.15856 13.9837C9.10856 13.992 9.05023 14.0003 9.00023 14.0003C8.95023 14.0003 8.89189 13.992 8.83356 13.9837C8.78356 13.9753 8.73356 13.9587 8.68356 13.9337C8.63356 13.917 8.58356 13.892 8.53356 13.8587C8.49189 13.8253 8.45023 13.792 8.40856 13.7587C8.25856 13.6003 8.16689 13.3837 8.16689 13.167C8.16689 12.9503 8.25856 12.7337 8.40856 12.5753C8.45023 12.542 8.49189 12.5087 8.53356 12.4753C8.58356 12.442 8.63356 12.417 8.68356 12.4003C8.73356 12.3753 8.78356 12.3587 8.83356 12.3503C8.94189 12.3253 9.05856 12.3253 9.15856 12.3503C9.21689 12.3587 9.26689 12.3753 9.31689 12.4003C9.36689 12.417 9.41689 12.442 9.46689 12.4753C9.50856 12.5087 9.55023 12.542 9.59189 12.5753C9.74189 12.7337 9.83356 12.9503 9.83356 13.167C9.83356 13.3837 9.74189 13.6003 9.59189 13.7587Z" fill="#E03137" />
            </svg>
        </span>
        <h5>Select your co-host</h5>`);

        eventData.co_host = "";
        eventData.co_host_prefer_by = "";
        selected_co_host = "";
        selected_co_host_prefer_by = "";
        $(".contactData").css("display", "none");
        $(".guest-contacts-wrp").removeClass("guest-contacts-test");

        toggleSidebar();
        return;
    }
    if (selected_dataId != "") {
        final_profilePhoto = checkedCheckbox.data("profile");
        final_user_name = checkedCheckbox.data("username");
        final_dataId = checkedCheckbox.val();
        final_initial = checkedCheckbox.data("initial");
        final_profile_or_text = checkedCheckbox.data("profile_or_text");
        final_prefer_by = checkedCheckbox.data("prefer_by");
        var profilePhoto = selected_profilePhoto;
        var user_name = selected_user_name;
        var dataId = selected_dataId;
        var profile_or_text = selected_profile_or_text;
        var prefer_by = selected_prefer_by;
        // console.log(prefer_by);
        eventData.co_host = dataId;
        selected_co_host = dataId;
        selected_co_host_prefer_by = prefer_by;
        var initial = checkedCheckbox.data("initial");

        if (profile_or_text == "1") {
            $(".selected-co-host-image").show();
            $(".selected-co-host-image").attr("src", profilePhoto);
            $(".selected-host-h5").css("display", "none");
        } else {
            $(".selected-host-h5").show();
            $(".selected-co-host-image").css("display", "none");
            $(".selected-host-h5").text(initial);
        }
        $(".remove_co_host").attr("data-id", selected_co_host);
        $(".selected-host-name").text(user_name);
        $(".contactData").css("display", "flex");
        $(".guest-contacts-wrp").addClass("guest-contacts-test");

        eventData.co_host_prefer_by = prefer_by;
        if (profile_or_text == "1") {
            $(".add_new_co_host")
                .html(`<span class="mx-3"><div class="contact-img co-host-profile-photo">
                    <img src="${profilePhoto}"
                        alt="logo">
                </div></span>
                <h5>${user_name}</h5>`);
        } else {
            $(".add_new_co_host")
                .html(`<span class="mx-3"><div class="contact-img">
                    ${profilePhoto}
                </div></span>
                <h5>${user_name}</h5>`);
        }
        toggleSidebar();
    } else {
        eventData.co_host = "";
        eventData.co_host_prefer_by = "";
        selected_co_host = "";
        selected_co_host_prefer_by = "";
        $(".contactData").css("display", "none");
        $(".guest-contacts-wrp").removeClass("guest-contacts-test");

        $(".add_new_co_host").html(`<span class="me-3">
                <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M17.1336 12.267L11.8002 2.66699C11.0836 1.37533 10.0919 0.666992 9.00023 0.666992C7.90856 0.666992 6.91689 1.37533 6.20023 2.66699L0.866892 12.267C0.191892 13.492 0.116892 14.667 0.658559 15.592C1.20023 16.517 2.26689 17.0253 3.66689 17.0253H14.3336C15.7336 17.0253 16.8002 16.517 17.3419 15.592C17.8836 14.667 17.8086 13.4837 17.1336 12.267ZM8.37523 6.50033C8.37523 6.15866 8.65856 5.87533 9.00023 5.87533C9.34189 5.87533 9.62523 6.15866 9.62523 6.50033V10.667C9.62523 11.0087 9.34189 11.292 9.00023 11.292C8.65856 11.292 8.37523 11.0087 8.37523 10.667V6.50033ZM9.59189 13.7587C9.55023 13.792 9.50856 13.8253 9.46689 13.8587C9.41689 13.892 9.36689 13.917 9.31689 13.9337C9.26689 13.9587 9.21689 13.9753 9.15856 13.9837C9.10856 13.992 9.05023 14.0003 9.00023 14.0003C8.95023 14.0003 8.89189 13.992 8.83356 13.9837C8.78356 13.9753 8.73356 13.9587 8.68356 13.9337C8.63356 13.917 8.58356 13.892 8.53356 13.8587C8.49189 13.8253 8.45023 13.792 8.40856 13.7587C8.25856 13.6003 8.16689 13.3837 8.16689 13.167C8.16689 12.9503 8.25856 12.7337 8.40856 12.5753C8.45023 12.542 8.49189 12.5087 8.53356 12.4753C8.58356 12.442 8.63356 12.417 8.68356 12.4003C8.73356 12.3753 8.78356 12.3587 8.83356 12.3503C8.94189 12.3253 9.05856 12.3253 9.15856 12.3503C9.21689 12.3587 9.26689 12.3753 9.31689 12.4003C9.36689 12.417 9.41689 12.442 9.46689 12.4753C9.50856 12.5087 9.55023 12.542 9.59189 12.5753C9.74189 12.7337 9.83356 12.9503 9.83356 13.167C9.83356 13.3837 9.74189 13.6003 9.59189 13.7587Z" fill="#E03137" />
                </svg>
            </span>
            <h5>Select your co-host</h5>`);
        toggleSidebar();
    }
});

// $(document).on("change", 'input[name="guest_list[]"]', function () {
//     if ($("input[name='guest_list[]']:checked").length > 2) {
//         $(this).prop("checked", false);
//         selected_co_host = $(this).val();
//         var prefer_by_email = $(this).data('email');
//         if(prefer_by_email){
//             selected_co_host_prefer_by = 'email';
//         }else{
//             selected_co_host_prefer_by = 'phone';
//         }
//         console.log(selected_co_host);
//         console.log(selected_co_host_prefer_by);
//         // toastr.error("There can be only one co host");
//     }
// });

$(document).on("click", ".final_checkout", function () {
    var data = eventData;
    // console.log(data);
    // $("#loader").show();
    // $(".main-content-wrp").addClass("blurred");
    // var imagePath = '';
    $("#eventImage").attr(
        "src",
        base_url +
            "public/storage/event_images/" +
            eventData.desgin_selected +
            ""
    );
    $("#eventTempImage").attr(
        "src",
        base_url +
            "public/storage/event_images/" +
            eventData.desgin_selected +
            ""
    );
    console.log(eventData.slider_images);
    const photoSliders = ["sliderImages-1", "sliderImages-2", "sliderImages-3"];
    const sliderImages = eventData.slider_images;
    console.log(sliderImages);
    if (eventData.slider_images != undefined && eventData.slider_images != "") {
        $(".event_images_slider").css("display", "block");
        $(".event_images_template").css("display", "none");
        // eventData.slider_images.forEach((image) => {
        //     const imageHtml = `
        //         <div class="item">
        //             <div class="setting-img">
        //                 <img id="sliderImages" src="${base_url+'public/storage/event_images/'+image.fileName}"  />
        //             </div>
        //         </div>
        //     `;
        //     $('.event_images_slider').append(imageHtml);
        // });

        photoSliders.forEach((sliderClass, index) => {
            const sliderElement = $(`#${sliderClass}`);
            if (sliderElement.length) {
                if (sliderImages[index]) {
                    sliderElement.attr(
                        "src",
                        `${base_url}public/storage/event_images/${sliderImages[index].fileName}`
                    );
                    $(`.${sliderClass}`).css("display", "block");
                } else {
                    sliderElement.css("display", "none");
                }
            }
        });

        $(".event_images_slider").owlCarousel({
            loop: true,
            margin: 10,
            nav: true,
            navText: [
                `<svg width="10" height="18" viewBox="0 0 10 18" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M8.49984 16.9201L1.97984 10.4001C1.20984 9.63008 1.20984 8.37008 1.97984 7.60008L8.49984 1.08008" stroke="#64748B" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        `,
                `<svg width="10" height="18" viewBox="0 0 10 18" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M1.41016 16.9201L7.93016 10.4001C8.70016 9.63008 8.70016 8.37008 7.93016 7.60008L1.41016 1.08008" stroke="#64748B" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>`,
            ],
            responsive: {
                0: {
                    items: 1,
                },
                600: {
                    items: 3,
                },
                1000: {
                    items: 5,
                },
            },
        });
    } else {
        $(".event_images_slider").css("display", "none");
        $(".event_images_template").css("display", "block");
        // $('.event_images_slider').removeClass('owl-carousel');
        // $('.event_images_slider').removeClass('owl-theme');
    }

    // var swiper = new Swiper(".event_images_slider", {
    //     slidesPerView: 1,
    //     loop: false,
    // });

    // if (!$('.event_images_slider').data('owl.carousel')) {

    //     $('.event_images_slider').owlCarousel({
    //         loop: false,
    //         margin: 10,
    //         nav: true,
    //         dots: false,
    //         items: 1,
    //         responsive: {
    //           0: {
    //             items: 1
    //           },
    //           600: {
    //             items: 1
    //           },
    //           1000: {
    //             items: 1
    //           }
    //         }
    //       });
    //     };

    $(".step_1").css("display", "none");
    $(".step_2").css("display", "none");
    $(".step_3").css("display", "none");
    $(".step_4").css("display", "none");
    $(".step_final_checkout").show();
    final_step = 4;
    eventData.step = "4";
    $(".li_setting").find(".side-bar-list").addClass("menu-success");

    // handleActiveClass(this);

    // $.ajax({
    //     url: base_url + "event/store",
    //     type: "POST",
    //     headers: {
    //         "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    //     },
    //     data: data,
    //     success: function (response) {
    //         $("#loader").css('display','none');
    //         $(".main-content-wrp").removeClass("blurred");
    //     },
    //     error: function (xhr, status, error) {
    //         console.log("AJAX error: " + error);
    //     },
    // });
});

$(document).on("click", "#final_create_event", function (e) {
    eventData.is_update_event = "0";
    eventData.isPhonecontact = isPhonecontact;
    var data = eventData;
    console.log(data);
    $("#loader").show();
    $(".main-content-wrp").addClass("blurred");
    e.stopPropagation();
    e.preventDefault();
    // var imagePath = '';

    // $('#eventImage').attr('src',base_url+'public/storage/event_images/'+eventData.desgin_selected+'');
    //     $(".step_1").css("display", "none");
    //     $(".step_2").css("display", "none");
    //     $(".step_3").css("display", "none");
    //     $(".step_4").css("display", "none");
    //     $(".step_final_checkout").show();

    // handleActiveClass(this);

    $.ajax({
        url: base_url + "event/store",
        type: "POST",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        data: data,
        success: function (response) {
            $("#loader").css("display", "none");
            $(".main-content-wrp").removeClass("blurred");

            if (response.is_registry == "1") {
                $("#gift_registry_logo").html(response.view);
                // $('#eventModal').modal('show');
            } else {
                toastr.success("Event Created Successfully");
                // window.location.href="profile";
            }
            $("#eventModal").modal("show");
        },
        error: function (xhr, status, error) {
            console.log("AJAX error: " + error);
        },
    });
});

var busy = false;
var limit = 50;
var offset = 0;
var page = "";

$(document).on("click", ".store_desgin_temp", function () {
    $("#sidebar_select_design_category").css("display", "none");
    canvas.discardActiveObject();
    canvas.getObjects().forEach((obj) => {
        if (obj.type === "group") {
            canvas.remove(obj); // Your existing function to add icons
        }
    });
    canvas.renderAll();

    setTimeout(() => {
        var downloadImage = document.getElementById("download_image");
        $("#loader").show();
        $(this).prop("disabled", true);
        $(".btn-close").prop("disabled", true);
        dbJson = getTextDataFromCanvas();
        console.log(dbJson);
        // dbJson = {
        //     textElements: textData
        // };
        // console.log(dbJson);
        eventData.textData = dbJson;
        eventData.temp_id = temp_id;
        save_image_design(downloadImage);
        $(".main-content-wrp").addClass("blurred");
    }, 500);
});

$(document).on("click", "#next_guest_step", function () {
    savePage1Data();
    // canvas.discardActiveObject();
    // canvas.getObjects().forEach(obj => {
    //     if (obj.type === 'group') {
    //         canvas.remove(obj) // Your existing function to add icons
    //     }
    // });

    // canvas.renderAll();
    // setTimeout(() => {
    //     var downloadImage = document.getElementById("imageEditor1");
    //     $("#loader").show();
    //     $(this).prop("disabled", true);
    //     $('.btn-close').prop("disabled", true);
    //     dbJson = getTextDataFromCanvas();
    //     // console.log(textData);
    //     // dbJson = {
    //     //     textElements: textData
    //     // };
    //     eventData.textData = dbJson;
    //     eventData.temp_id = temp_id;
    //     save_image_design(downloadImage);
    //     $(".main-content-wrp").addClass("blurred");
    // }, 500);
});

var design_inner_image = "";
function save_image_design(downloadImage, textData) {
    if ($("#shape_img").attr("src")) {
        design_inner_image = $("#shape_img").attr("src");
    }
    var old_shape_url = $("#first_shape_img").attr("src");
    eventData.cutome_image = image;
    domtoimage
        .toBlob(downloadImage)
        .then(function (blob) {
            console.log({ blob });

            var formData = new FormData();
            formData.append("image", blob, "design.png");
            formData.append("design_inner_image", design_inner_image);
            formData.append("shapeImageUrl", old_shape_url);
            $.ajax({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
                url: base_url + "event/store_temp_design",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    let image = response.image;
                    eventData.desgin_selected = image;

                    // if(eventData.step == '1'){
                    //     eventData.step = '2';
                    // }
                    console.log(final_step);
                    if (final_step == 2) {
                        final_step = 3;
                    }
                    console.log(eventData);
                    eventData.step = final_step;
                    console.log("Image uploaded and saved successfully");
                    $("#myCustomModal").modal("hide");
                    $("#exampleModal").modal("hide");
                    $("#loader").css("display", "none");
                    $(".store_desgin_temp").prop("disabled", false);
                    $(".btn-close").prop("disabled", false);
                    $(".main-content-wrp").removeClass("blurred");
                    $(".step_2").hide();
                    $("#edit-design-temp").hide();
                    console.log("handleActiveClass");

                    handleActiveClass(".li_guest");
                    $(".pick-card").addClass("menu-success");
                    $(".edit-design").addClass("menu-success");
                    $(".edit-design").removeClass("active");
                    $(".li_design")
                        .find(".side-bar-list")
                        .addClass("menu-success");
                    $(".li_design").addClass("menu-success");

                    active_responsive_dropdown("drop-down-event-guest");

                    $(".event_create_percent").text("75%");
                    $(".current_step").text("3 of 4");

                    $(".step_3").show();
                    console.log(eventData);

                    var type = "all";
                    get_user(type);
                    if (response.shape_image) {
                        eventData.shape_image = response.shape_image;
                    }
                },
                error: function (xhr, status, error) {
                    console.error(
                        "Failed to upload and save the image:",
                        error
                    );
                },
            });
        })
        .catch(function (error) {
            console.error("Error capturing image:", error);
        });
}
var busyyesvite = false;
var limityesvite = 10;
var offsetyesvite = 0;

var NoMoreDataYesviteAll = false;
var NogroupData = false;

$("#YesviteUserAll").on("scroll", function () {
    // console.log(busyyesvite);

    if (busyyesvite||create_event_yesvite_scroll) return;
    var scrollTop = $(this).scrollTop();
    var scrollHeight = $(this)[0].scrollHeight;
    var elementHeight = $(this).height();
    if (scrollTop + elementHeight >= scrollHeight - 2) {
        busyyesvite = true;
        offsetyesvite += limityesvite;
        // var type="yesvite";
        if (NoMoreDataYesviteAll == false) {
            displayRecords(
                limityesvite,
                offsetyesvite,
                (type = "all"),
                (search = null),
                (alluserscroll = 1)
            );
        }
    }
});
function get_user(type) {
    // if (busyyesvite == false) {
    busyyesvite = true;
    page = 3;
    limityesvite = 10;
    offsetyesvite = 0;
    displayRecords(
        limityesvite,
        offsetyesvite,
        type,
        (search = null),
        (alluserscroll = null)
    );
    // }
}
// $('#YesviteUserAll').scroll(function () {
//     if ($(this).scrollTop() + $(this).height() >= $(document).height()) {
//         if(page == 3){
//             busy = true;
//             offset = limit + offset;
//             setTimeout(function () {
//                 displayRecords(limit, offset);
//             }, 500);
//         }
//     }
// });

// After .user-contacts is dynamically added to the DOM
$("#YesviteUserAll").scroll(function () {
    var scrollTop = $(this).scrollTop(); // Current scroll position
    var scrollHeight = $(this)[0].scrollHeight; // Total height of the scrollable area
    var elementHeight = $(this).height(); // Visible height of the element

    // Check if the user has scrolled to the bottom
    // if (scrollTop + elementHeight >= scrollHeight) {
    //     busy = true;
    //     offset = limit + offset;
    //     console.log(offset);
    //     $('#loader').css('display','block');
    //     setTimeout(function () {
    //         displayRecords(limit, offset,'all');

    //     }, 1000);
    // }
});

$("#groupUsers").scroll(function () {
    console.log(busyyesvite);

    if (busyyesvite) return;
    var scrollTop = $(this).scrollTop(); // Current scroll position
    var scrollHeight = $(this)[0].scrollHeight; // Total height of the scrollable area
    var elementHeight = $(this).height(); // Visible height of the element=
    // Check if the user has scrolled to the bottom
    console.log({ scrollTop, elementHeight, scrollHeight });

    if (scrollTop + elementHeight >= scrollHeight - 2) {
        busyyesvite = true;
        offsetyesvite = limityesvite + offsetyesvite;
        if (NogroupData == false) {
            $("#loader").css("display", "block");
        }
        setTimeout(function () {
            if (NogroupData == false) {
                displayRecords(
                    limityesvite,
                    offsetyesvite,
                    "group",
                    null,
                    null,
                    1
                );
            }
        }, 1000);
    }
});
// $("#loader").css('display','block');

function displayRecords(
    lim,
    off,
    type,
    search = null,
    alluserscroll = null,
    groupscroll = null
) {
    var search_name = "";
    if (type != "group") {
        search_name = $(".search_user").val();
        if (search_name != "") {
            offsetyesvite = 0;
        }
    }

    $.ajax({
        type: "GET",
        async: false,
        url: base_url + "event/get_user_ajax",
        data:
            "limit=" +
            lim +
            "&offset=" +
            off +
            "&type=" +
            type +
            "&search_user=" +
            search_name,
        cache: false,
        beforeSend: function () {},
        success: function (html) {
            var currentInviteCount = parseInt($("#currentInviteCount").val());
            const coins = $("#coins").val();
            if(search==""){
                create_event_yesvite_scroll=false
            }else{
                create_event_yesvite_scroll=true
            }
            if (currentInviteCount >= coins) {
                $(".user_choice").prop("disabled", true);
            }
            console.log(html);
            if (html == "" && alluserscroll == 1) {
                // $("#YesviteUserAll").html('No data found');
                NoMoreDataYesviteAll = true;
                $("#loader").css("display", "none");

                return;
            }
            if (html == "" && groupscroll == null) {
                $("#YesviteUserAll").html("No data found");
                $("#loader").css("display", "none");
                return;
            }

            // if(html==""){
            //     // $("#YesviteUserAll").html('No data found');
            //    NoMoreDataYesviteAll=true;
            //    $('#loader').css('display','none');

            //    return;
            // }
            if (type == "all") {
                if (search != null) {
                    $("#YesviteUserAll").html(html);
                    busyyesvite = false;
                } else {
                    $("#YesviteUserAll").append(html);
                    busyyesvite = false;
                }
            } else {
                if (html == "" && groupscroll == 1) {
                    // $("#YesviteUserAll").html('No data found');
                    NogroupData = true;
                    busyyesvite = false;
                    $("#loader").css("display", "none");

                    return;
                }
                if (groupscroll == 1) {
                    $("#groupUsers").append(html);
                } else {
                    $("#groupUsers").html(html);
                }
            }
            busyyesvite = false;

            setTimeout(function () {
                $("#loader").css("display", "none");
            }, 1000);
        },
    });
}
var search_user_ajax_timer = 0;
$(document).on("keyup", ".search_user_ajax", function () {
    search_name = $(this).val();
    offsetyesvite = 0;
    clearTimeout(search_user_ajax_timer);
    search_user_ajax_timer = setTimeout(function () {
        $("#loader").css("display", "block");
        displayRecords(limityesvite, offsetyesvite, "all", search_name);
        // $('#loader').css('display','none');
    }, 750);
});

function loadSearchUser(search_name) {
    $.ajax({
        url: base_url + "event/search_user_ajax",
        type: "POST",
        data: {
            search_name: search_name,
            _token: $('meta[name="csrf-token"]').attr("content"), // Adding CSRF token
        },
        beforeSend: function () {
            $("#loader").show();
        },
    })
        .done(function (data) {
            if (data == "") {
                $("#loader").html("No more contacts found");
                return;
            }
            $("#loader").hide();
            $("#YesviteUserAll").html(data);
        })
        .fail(function (jqXHR, ajaxOptions, thrownError) {
            alert("server not responding...");
        });
}

$(document).on("click", ".add_new_group", function () {
    var group_name = $("#new_group_name").val();
    NogroupData = false;
    if (group_name == "") {
        $("#group_name_error")
            .css("display", "block")
            .css("color", "red")
            .text("Please enter group name");
        return;
    } else {
        $("#group_name_error").css("display", "none");
        toggleSidebar("sidebar_add_group_member");
        var type = "group";
        get_user(type);
    }
});

$("#new_group_name").on("keydown", function (e) {
    if (e.key === "Enter" || e.keyCode === 13) {
        e.preventDefault(); // Prevents the default action of submitting the form or adding a new line
    }
});

$(document).on("click", ".invite_group_member", function () {
    $("#loader").css("display", "block");
    var userId = $(this).val();
    var selectedValues = [];
    $(".user_group_member").each(function () {
        if ($(this).is(":checked") && !$(this).is(":disabled")) {
            var perferby = $(this).data("preferby");
            var invited_by = "";
            if (perferby == "email") {
                invited_by = $(this).data("email");
            } else {
                invited_by = $(this).data("mobile");
            }
            // selectedValues.push({
            //     id: $(this).val(),
            //     preferby: perferby,
            //     invited_by:invited_by
            // });

            const id = $(this).val();
            // Check if the ID is already in the array
            const isIdExists = selectedValues.some((item) => item.id === id);

            if (!isIdExists) {
                selectedValues.push({
                    id: id,
                    preferby: perferby,
                    invited_by: invited_by,
                });
            }

            console.log(id);
            console.log(selectedValues);
        }
    });
    $.ajax({
        url: base_url + "event/invite_user_by_group",
        type: "POST",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        data: {
            users: selectedValues,
        },
        success: function (response) {
            // console.log(response);
            response.data.forEach(function (item, index) {
                if (
                    item.is_duplicate == "1" &&
                    item.userdata &&
                    item.userdata.id
                ) {
                    console.log(item.is_duplicate);
                    $("#user-" + item.userdata.id).remove();
                    $("#user_tel-" + item.userdata.id).remove();
                    // $(".user_id-" + item.userdata.id).remove();
                    // $(".user_id_tel-" + item.userdata.id).remove();
                    // $(".user-list-responsive").empty();
                }
            });
            $(".user-list-responsive").empty();
            // $(".user-list-responsive").html(response.responsive_view);
            $(".user-list-responsive_yesvite").html(response.responsive_view);
            console.log(response);
            $(".inivted_user_list").append(response.view);
            var max_guest = $("#coins").val();
            // var length = responsive_invite_user();
            // $(".user-list-responsive").html(response.responsive_view);
            // if(length < 4){
            //     $('.all_user_list').remove();
            //     $(".user-list-responsive").empty();
            //     $(".user-list-responsive").html(response.responsive_view);
            //     // $(".user-list-responsive").append(response.responsive_view);
            // }else{
            //     // add_user_counter();
            // }
            // $(".inivted_user_list").html('');
            guest_counter(0, max_guest);
            $(".user_choice_group .user_choice").prop("checked", false);

            toggleSidebar();
            $("#YesviteUserAll").html("");
            var type = "all";
            get_user(type);
            // }
        },
        error: function (xhr, status, error) {
            console.log("AJAX error: " + error);
        },
    });
});

$(document).on("click", ".view_members", function () {
    var group_id = $(this).data("id");
    $("#loader").css("display", "block");

    $.ajax({
        url: base_url + "event/list_group_memeber",
        type: "POST",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        data: {
            group_id: group_id,
        },
        success: function (response) {
            if (response.status == "1") {
                $(".user-contacts-sidebar").html("");
                $(".user-contacts-sidebar").html(response.view);

                toggleSidebar("sidebar_list_group_member");
                $("#loader").css("display", "none");
            }
        },
        error: function (xhr, status, error) {
            console.log("AJAX error: " + error);
        },
    });
});

$(document).on("click", ".edit-icon", function () {
    toggleSidebar("sidebar_change_plan");
});

$(document).on("click", ".free_plan", function () {
    handleActivePlan(this);
});

$(document).on("click", ".pro_plan", function () {
    handleActivePlan(this);
});

$(document).on("click", ".pro_year_plan", function () {
    handleActivePlan(this);
});

$(document).on("click", ".continue-btn", function () {
    $("#loader").css("display", "none");
});

$(document).on("change", 'input[name="gift_registry[]"]', function () {
    // if($(this).is(':checked')){
    //     var registry_name=$(this).data('item');
    //     var registry_link=$(this).data('registry');
    //     var gr_id = $(this).val();
    //     selected_gift.push({
    //         registry_name: registry_name,
    //         registry_link: registry_link,
    //         gr_id: gr_id,
    //     });
    // }
    selected_gift = [];

    $('input[name="gift_registry[]"]:checked').each(function () {
        var registry_name = $(this).data("item");
        var registry_link = $(this).data("registry");
        var gr_id = $(this).val();
        selected_gift.push({
            registry_name: registry_name,
            registry_link: registry_link,
            gr_id: gr_id,
        });
    });
    eventData.gift_registry_data = selected_gift;
    console.log(eventData);

    var selected = $('input[name="gift_registry[]"]:checked');
    if (selected.length > 2) {
        $(this).prop("checked", false);
        $(this).blur();
        toastr.error("Maximum two gift registry can select");

        //    selected_gift = [];
        //     $('input[name="gift_registry[]"]:checked').each(function() {
        //         var registry_name = $(this).data('item');
        //         var registry_link = $(this).data('registry');
        //         var gr_id = $(this).val();
        //         selected_gift.push({
        //             registry_name: registry_name,
        //             registry_link: registry_link,
        //             gr_id: gr_id
        //         });
        //     });
        //     eventData.gift_registry_data = selected_gift;
    }
});

$(document).on("click", ".brand-progress", function () {
    var event_id = $(this).data("id");
    // window.location.href="event?id="+event_id;
});

$(document).on("click", ".create_new_event_close_tip", function () {
    $("#create_new_event_tip").removeClass("d-flex");
    $("#create_new_event_tip").hide();

    ajax_tip_close("create_new_event");
});

$(document).on("click", "#guest_list_visible_to_guest", function () {
    if ($(this).is(":checked")) {
        $("#eventwall").prop("checked", true);
    } else {
        $("#eventwall").prop("checked", false);
    }
});

$(document).on("click", 'input[name="select_thankyou[]"]', function () {
    $('input[name="select_thankyou[]"]').not(this).prop("checked", false);
    var i = 0;
    var checkedCount = 0;
    if ($(this).is(":checked")) {
        checkedCount++;
        eventData.thank_you_card_id = $(this).data("id");
        console.log(eventData.thank_you_card_id);
    }
    $("input[name='select_thankyou[]']").each(function (index) {
        i++;
    });
    if (i >= 1 && checkedCount > 0) {
        if (i == 1) {
            $(".add_new_thankyou_card").html(`<span class="me-3"></span>
                <h5>${i} Template available</h5>`);
        } else {
            $(".add_new_thankyou_card").html(`<span class="me-3"></span>
                <h5>${i} Templates available</h5>`);
        }
    } else {
        eventData.thank_you_card_id = "";
        $(".add_new_thankyou_card").html(`<span class="me-3">
                <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M17.1336 12.267L11.8002 2.66699C11.0836 1.37533 10.0919 0.666992 9.00023 0.666992C7.90856 0.666992 6.91689 1.37533 6.20023 2.66699L0.866892 12.267C0.191892 13.492 0.116892 14.667 0.658559 15.592C1.20023 16.517 2.26689 17.0253 3.66689 17.0253H14.3336C15.7336 17.0253 16.8002 16.517 17.3419 15.592C17.8836 14.667 17.8086 13.4837 17.1336 12.267ZM8.37523 6.50033C8.37523 6.15866 8.65856 5.87533 9.00023 5.87533C9.34189 5.87533 9.62523 6.15866 9.62523 6.50033V10.667C9.62523 11.0087 9.34189 11.292 9.00023 11.292C8.65856 11.292 8.37523 11.0087 8.37523 10.667V6.50033ZM9.59189 13.7587C9.55023 13.792 9.50856 13.8253 9.46689 13.8587C9.41689 13.892 9.36689 13.917 9.31689 13.9337C9.26689 13.9587 9.21689 13.9753 9.15856 13.9837C9.10856 13.992 9.05023 14.0003 9.00023 14.0003C8.95023 14.0003 8.89189 13.992 8.83356 13.9837C8.78356 13.9753 8.73356 13.9587 8.68356 13.9337C8.63356 13.917 8.58356 13.892 8.53356 13.8587C8.49189 13.8253 8.45023 13.792 8.40856 13.7587C8.25856 13.6003 8.16689 13.3837 8.16689 13.167C8.16689 12.9503 8.25856 12.7337 8.40856 12.5753C8.45023 12.542 8.49189 12.5087 8.53356 12.4753C8.58356 12.442 8.63356 12.417 8.68356 12.4003C8.73356 12.3753 8.78356 12.3587 8.83356 12.3503C8.94189 12.3253 9.05856 12.3253 9.15856 12.3503C9.21689 12.3587 9.26689 12.3753 9.31689 12.4003C9.36689 12.417 9.41689 12.442 9.46689 12.4753C9.50856 12.5087 9.55023 12.542 9.59189 12.5753C9.74189 12.7337 9.83356 12.9503 9.83356 13.167C9.83356 13.3837 9.74189 13.6003 9.59189 13.7587Z" fill="#E03137"/>
                </svg>
            </span>
            <h5>Select thank you card</h5>`);
    }
});

$(document).on("click", 'input[name="gift_registry[]"]', function () {
    countGiftRegestry();
});

function countGiftRegestry() {
    var i = 0;
    var checkedCount = 0;
    $("input[name='gift_registry[]']").each(function (index) {
        if ($(this).is(":checked")) {
            checkedCount++;
        } else {
            $(this).blur();
        }
        i++;
    });
    if (i >= 1 && checkedCount > 0) {
        if (i == 1) {
            $(".add_gift_registry_count").html(`<span class="me-3"></span>
                <h5>${i} Registry</h5>`);
        } else {
            $(".add_gift_registry_count").html(`<span class="me-3"></span>
                <h5>${i} Registries</h5>`);
        }
    } else {
        $(".add_gift_registry_count").html(`<span class="me-3">
            <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M17.1336 12.267L11.8002 2.66699C11.0836 1.37533 10.0919 0.666992 9.00023 0.666992C7.90856 0.666992 6.91689 1.37533 6.20023 2.66699L0.866892 12.267C0.191892 13.492 0.116892 14.667 0.658559 15.592C1.20023 16.517 2.26689 17.0253 3.66689 17.0253H14.3336C15.7336 17.0253 16.8002 16.517 17.3419 15.592C17.8836 14.667 17.8086 13.4837 17.1336 12.267ZM8.37523 6.50033C8.37523 6.15866 8.65856 5.87533 9.00023 5.87533C9.34189 5.87533 9.62523 6.15866 9.62523 6.50033V10.667C9.62523 11.0087 9.34189 11.292 9.00023 11.292C8.65856 11.292 8.37523 11.0087 8.37523 10.667V6.50033ZM9.59189 13.7587C9.55023 13.792 9.50856 13.8253 9.46689 13.8587C9.41689 13.892 9.36689 13.917 9.31689 13.9337C9.26689 13.9587 9.21689 13.9753 9.15856 13.9837C9.10856 13.992 9.05023 14.0003 9.00023 14.0003C8.95023 14.0003 8.89189 13.992 8.83356 13.9837C8.78356 13.9753 8.73356 13.9587 8.68356 13.9337C8.63356 13.917 8.58356 13.892 8.53356 13.8587C8.49189 13.8253 8.45023 13.792 8.40856 13.7587C8.25856 13.6003 8.16689 13.3837 8.16689 13.167C8.16689 12.9503 8.25856 12.7337 8.40856 12.5753C8.45023 12.542 8.49189 12.5087 8.53356 12.4753C8.58356 12.442 8.63356 12.417 8.68356 12.4003C8.73356 12.3753 8.78356 12.3587 8.83356 12.3503C8.94189 12.3253 9.05856 12.3253 9.15856 12.3503C9.21689 12.3587 9.26689 12.3753 9.31689 12.4003C9.36689 12.417 9.41689 12.442 9.46689 12.4753C9.50856 12.5087 9.55023 12.542 9.59189 12.5753C9.74189 12.7337 9.83356 12.9503 9.83356 13.167C9.83356 13.3837 9.74189 13.6003 9.59189 13.7587Z" fill="#E03137"/>
            </svg>
        </span>
        <h5>Add gift registry</h5>`);
    }
}

$(document).on("click", "#potluck_tip", function (e) {
    e.preventDefault();
    $("#potluck_tip_bar").remove();
    ajax_tip_close("potluck");
});

$(document).on("click", "#co_host_tip_close", function (e) {
    e.preventDefault();
    $("#co_host_tip").remove();
    ajax_tip_close("co_host");
});

$(document).on("click", "#design_tip_bar_close", function (e) {
    e.preventDefault();
    $("#design_tip_bar").remove();
    ajax_tip_close("desgin_tip");
});

$(document).on("click", "#edit_design_tip_bar_close", function (e) {
    e.preventDefault();
    $("#edit_design_tip_bar").remove();
    ajax_tip_close("edit_desgin_tip");
});

function ajax_tip_close(type) {
    $.ajax({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        url: base_url + "event/close_tip",
        method: "POST",
        data: {
            closed: true,
            tip: type,
        },
        success: function (response) {
            // console.log(response);
        },
    });
}

function checkbox_count() {
    var checkedCount_general_setting = $(
        ".general_setting_checkbox:checked"
    ).length;
    var checkedCount_event_page = $(".event_page_checkbox:checked").length;
    var checkedCount_notification = $(".notification_checkbox:checked").length;

    $("#general_setting_checkbox").text(checkedCount_general_setting + "/7");
    $("#event_page_checkbox").text(checkedCount_event_page + "/2");
    $("#notification_checkbox").text(checkedCount_notification + "/4");
}

$(document).on("click", ".checkbox", function () {
    checkbox_count();
});

$(document).on("click", ".open_addcategory", function () {
    $("#categoryName").val("");
    $("#category_quantity").val("1");
    $(".pot-cate-name").text("0/30");
});

$(document).on("click", ".new_group", function () {
    var selectedValues = [];
    $("#new_group_name").val("");
    $("#group_toggle_search").val("");
    toggleSidebar("sidebar_add_groups");
});

$(document).on("click", ".thankyou_card_add_form", function () {
    $("#thankyou_templatename").val("");
    $("#thankyou_when_to_send").val("");
    $("#message_for_thankyou").val("");
    toggleSidebar("sidebar_add_thankyou_card");
    $(".thankyoucard_heading").text("Create new thank you card");
});

$(document).on("click", ".add_new_gift_registry", function () {
    $("#recipient_name").val("");
    $("#registry_link").val("");
    $(".recipient-name-con").text("0/30");
    $(".gift_registry_heading").text("Create Gift Registry");
    toggleSidebar("sidebar_gift_registry_item");
});

$(document).on("keyup", "#group_search_ajax", function () {
    var search_name = $(this).val();
    console.log(search_name);
    $.ajax({
        url: base_url + "event/group_search_ajax",
        type: "POST",
        data: {
            search_name: search_name,
            _token: $('meta[name="csrf-token"]').attr("content"), // Adding CSRF token
        },
        beforeSend: function () {
            $("#loader").show();
        },
    })
        .done(function (data) {
            console.log(data.html);
            if (data.html == "") {
                $(".group_search_list").html("No data found");
                $("#loader").hide();
                return;
            }
            $("#loader").hide();
            $(".group_search_list").html(data.html);
        })
        .fail(function (jqXHR, ajaxOptions, thrownError) {
            alert("server not responding...");
        });
});

$(document).on("keyup", "#group_toggle_search", function () {
    var search_name = $(this).val();
    groupToggleSearch(search_name);
});

function groupToggleSearch(search_name = null) {
    if (search_name == null) {
        search_name = "";
    }
    $.ajax({
        url: base_url + "event/group_toggle_search",
        type: "POST",
        data: {
            search_name: search_name,
            _token: $('meta[name="csrf-token"]').attr("content"), // Adding CSRF token
        },
        beforeSend: function () {
            $("#loader").show();
        },
    })
        .done(function (data) {
            console.log(data.html);
            if (data.html == " ") {
                $("#loader").html("No more contacts found");
                return;
            }
            $("#loader").hide();
            $(".group_search_list_toggle").html(data.html);
        })
        .fail(function (jqXHR, ajaxOptions, thrownError) {
            alert("server not responding...");
        });
}

$(document).on("click", ".listing-arrow", function () {
    if ($(this).parent().find(".list-slide").hasClass("open-potluck-list")) {
        $(this).parent().find(".list-slide").removeClass("open-potluck-list");
    } else {
        $(this).parent().find(".list-slide").addClass("open-potluck-list");
    }
});

$(document).on("click", ".see_all_group", function () {
    $("search_user").val("");
    toggleSidebar("sidebar_groups");
});

var cohostbusy = false;
var cohostlimit = 7;
var cohostoffset = 0;
var cohostNoMoreData = false;

$(document).on("click", ".add_co_host", function () {
    //     var cur_status=$(this).data('contact');
    //        console.log(cur_status);
    //    if(cur_status=="contacts"){
    //         $('#phone-tab-cantact').addClass('active');
    //         $('.add_co_host_tab').removeClass('active');
    //         get_phone_host_list(null,cohostphoneLimit,cohostphoneOffset,false);
    //         setTimeout(() => {
    //             toggleSidebar('sidebar_add_co_host');
    //         }, 500);
    //         return;
    //    }
    cohostNoMoreData = false;
    cohostoffset = 0;
    cohostlimit = 7;
    // cohostphoneOffset=0;
    // cohostphoneLimit=10;
    $("#phone-tab-cantact").removeClass("active");
    isPhonecontact = 0;
    if (selected_co_host != "") {
        lengtUSer = 1;
    } else {
        lengtUSer = 0;
    }

    co_host_is_selected_close = false;
    $(".co_host_search").val("");
    $(".phone_co_host_search").val("");
    $(".add_co_host_tab").addClass("active");
    $("#phone-tab-cantact").removeClass("active");

    $(".list_all_contact_user").css("display", "none");
    $(".list_all_yesvite_user").css("display", "block");

    $(".co_host_search").css("display", "block");
    $(".phone_co_host_search").css("display", "none");

    get_co_host_list("1", null, cohostlimit, cohostoffset, false, 1);
    $("#select_event_cohost").css("display", "block");

    setTimeout(() => {
        toggleSidebar("sidebar_add_co_host");
    }, 500);
});

// $(document).on('click','.add_co_host',function(){

//     $('.co_host_search').val('');
//     get_co_host_list();

//     setTimeout(() => {
//         toggleSidebar('sidebar_add_co_host');
//     }, 500);
// });
var cohostphoneOffset = 0;
var cohostphoneLimit = 10;
var cohostphonebusy = false;
var cohostNoMoreContactData = false;

$(document).on("click", "#phone-tab-cantact", function () {
    // $('.list_all_invited_user').css('display','none');
    // $('.list_all_contact_user').css('display','block');
    $(".co_host_search").val("");
    $(".phone_co_host_search").val("");
    $(".co_host_search").css("display", "none");
    $(".phone_co_host_search").css("display", "block");
    isPhonecontact = 1;
    cohostoffset = 0;
    cohostlimit = 7;

    cohostphoneOffset = 0;
    cohostphoneLimit = 10;
    cohostNoMoreContactData = false;
    get_phone_host_list(null, cohostphoneLimit, cohostphoneOffset, false);
});

$(document).on("click", "#contact-tab", function () {
    $(".co_host_search").val("");
    $(".phone_co_host_search").val("");
    $(".list_all_invited_user").css("display", "block");
    $(".list_all_contact_user").css("display", "none");
    $(".co_host_search").css("display", "block");
    $(".phone_co_host_search").css("display", "none");

    cohostNoMoreData = false;
    cohostphoneOffset = 0;
    cohostphoneLimit = 10;
    var isHost = $(this).attr("data-isHost");
    $("#phone-tab-cantact").removeClass("active");
    get_co_host_list(isHost, null, cohostlimit, cohostoffset, false);
    $("#select_event_cohost").css("display", "block");

    if (co_host_is_selected_close == true) {
        $(".contactData").css("display", "none");
        $(".guest-contacts-wrp").removeClass("guest-contacts-test");
        var checkedCheckbox = $('input[name="guest_list[]"]:checked');

        if (checkedCheckbox.length > 0) {
            checkedCheckbox.prop("checked", false); // Uncheck all checked checkboxes
        }
    }
});

$(document).on("click", ".add_co_host_off", function () {
    if (eventData.co_host != undefined) {
        selected_co_host = eventData.co_host;
    } else {
        selected_co_host = "";
    }

    if (eventData.co_host_prefer_by != undefined) {
        selected_co_host_prefer_by = eventData.co_host_prefer_by;
    } else {
        selected_co_host_prefer_by = "";
    }
    var checkedCheckbox = $('input[name="guest_list[]"]:checked');
    if (checkedCheckbox.length > 0) {
        checkedCheckbox.prop("checked", false); // Uncheck all checked checkboxes
    }
    toggleSidebar();
});

$(document).on("click", ".overlay", function () {
    if (eventData.co_host !== undefined) {
        selected_co_host = eventData.co_host;
    } else {
        selected_co_host = "";
    }
    if (eventData.co_host_prefer_by != undefined) {
        selected_co_host_prefer_by = eventData.co_host_prefer_by;
    } else {
        selected_co_host_prefer_by = "";
    }
    toggleSidebar();
});

function get_co_host_list(
    isHost,
    search_name = null,
    limit,
    offset,
    scroll,
    add_co_host = null
) {
    var app_user = $("#app_user").val();
    var cohostId = $("#cohostId").val();
    var cohostpreferby = $("#cohostpreferby").val();
    if (search_name == null) {
        search_name = "";
    }
    console.log(selected_co_host);
    console.log(selected_co_host_prefer_by);

    if (selected_co_host == "") {
        if (isHost == "1") {
        } else {
            // $(".contactData").css("display", "block");
        }
        $(".contactData").css("display", "none");
        $(".guest-contacts-wrp").removeClass("guest-contacts-test");
        cohostId = "";
        var checkedCheckbox = $('input[name="guest_list[]"]:checked');

        if (checkedCheckbox.length > 0) {
            checkedCheckbox.prop("checked", false); // Uncheck all checked checkboxes
        }
    } else {
        if (co_host_is_selected_close == true) {
            $(".contactData").css("display", "none");
            $(".guest-contacts-wrp").removeClass("guest-contacts-test");
            var checkedCheckbox = $('input[name="guest_list[]"]:checked');

            if (checkedCheckbox.length > 0) {
                checkedCheckbox.prop("checked", false); // Uncheck all checked checkboxes
            }
        } else {
            $(".contactData").css("display", "flex");
            // $(".contactData").css("display", "flex");
            $(".guest-contacts-wrp").addClass("guest-contacts-test");
        }
    }
    //  var checkedCheckbox = $('input[name="guest_list[]"]:checked');

    //  if (checkedCheckbox.length > 0) {
    //      checkedCheckbox.prop('checked', false);  // Uncheck all checked checkboxes
    //  }
    $.ajax({
        url: base_url + "event/get_co_host_list",
        type: "POST",
        data: {
            search_name: search_name,
            limit: limit,
            offset: offset,
            scroll: scroll,
            selected_co_host: selected_co_host,
            selected_co_host_prefer_by: selected_co_host_prefer_by,
            app_user: app_user,
            cohostId: cohostId,
            isCohost: isCohost,
            cohostpreferby: cohostpreferby,
            _token: $('meta[name="csrf-token"]').attr("content"), // Adding CSRF token
        },
        beforeSend: function () {
            $("#loader").show();
        },
    })
        .done(function (data) {
            console.log(data);

            if (data.view == "" && data.scroll == "false") {
                $(".list_all_invited_user").html("No Data Found");
                $("#loader").hide();
                return;
            } else {
                // $(".list_all_invited_user").html('No Data Found');
            }

            if (data.view == "") {
                cohostNoMoreData = true;
                $("#loader").hide();
                return;
            }
            $("#loader").hide();

            if (data.scroll == "true") {
                $(".list_all_invited_user").append(data.view);
            } else {
                $(".list_all_invited_user").html(data.view);
            }

            // $('input[name="guest_list[]"]:checked').each(function () {
            var profilePhoto = $(this).data("profile");
            var user_name = $(this).data("username");
            var profile_or_text = $(this).data("profile_or_text");
            var initial = $(this).data("initial");
            var prefer_by_email = $(this).data("prefer_by");

            // Log or process the data
            console.log("Profile Photo:", profilePhoto);
            console.log("User Name:", user_name);
            console.log("Profile or Text:", profile_or_text);
            console.log("Initial:", initial);
            console.log("Prefer By Email:", prefer_by_email);

            // Update UI based on the `profile_or_text` condition
            if (add_co_host == 1) {
                if (final_profile_or_text == "1") {
                    $(".guest-img .selected-co-host-image").show();
                    $(".guest-img .selected-co-host-image").attr(
                        "src",
                        final_profilePhoto
                    );
                    $(".guest-img .selected-host-h5").css("display", "none");
                } else {
                    // $('.guest-img').html(profilePhoto    );
                    $(".selected-host-h5").show();
                    $(".selected-co-host-image").css("display", "none");
                    $(".guest-img .selected-host-h5").text(final_initial);
                    var firstinitial = final_initial.charAt(0);

                    // $('.selected-host-h5').removeClass(function (index, className) {
                    //     return (className.match(/\bfontcolor\S+/g) || []).join(' ');
                    // });
                    // $('.selected-host-h5').addClass('fontcolor' + firstinitial);

                    $(".guest-img .selected-host-h5").removeClass(function (
                        index,
                        className
                    ) {
                        return (className.match(/\bfontcolor\S+/g) || []).join(
                            " "
                        );
                    });

                    // Add the new class
                    $(".guest-img .selected-host-h5").addClass(
                        "fontcolor" + firstinitial
                    );
                }
                $(".selected-host-name").text(final_user_name);
            } else {
                $('input[name="guest_list[]"]:checked').each(function () {
                    var profilePhoto = $(this).data("profile");
                    var user_name = $(this).data("username");
                    var profile_or_text = $(this).data("profile_or_text");
                    var initial = $(this).data("initial");
                    var prefer_by_email = $(this).data("prefer_by");
                    if (profile_or_text == "1") {
                        $(".guest-img .selected-co-host-image").show();
                        $(".guest-img .selected-co-host-image").attr(
                            "src",
                            profilePhoto
                        );
                        $(".guest-img .selected-host-h5").css(
                            "display",
                            "none"
                        );
                    } else {
                        // $('.guest-img').html(profilePhoto    );
                        $(".selected-host-h5").show();
                        $(".selected-co-host-image").css("display", "none");
                        $(".guest-img .selected-host-h5").text(initial);
                        var firstinitial = initial.charAt(0);

                        // $('.selected-host-h5').removeClass(function (index, className) {
                        //     return (className.match(/\bfontcolor\S+/g) || []).join(' ');
                        // });
                        // $('.selected-host-h5').addClass('fontcolor' + firstinitial);

                        $(".guest-img .selected-host-h5").removeClass(function (
                            index,
                            className
                        ) {
                            return (
                                className.match(/\bfontcolor\S+/g) || []
                            ).join(" ");
                        });

                        // Add the new class
                        $(".guest-img .selected-host-h5").addClass(
                            "fontcolor" + firstinitial
                        );
                    }
                    $(".selected-host-name").text(user_name);
                });
            }

            // if(co_host_is_selected_close==true){
            //     $('.guest-contacts-wrp').css('display','none');
            //     $('.guest-contacts-wrp').removeClass('guest-contacts-test');
            //     var checkedCheckbox = $('input[name="guest_list[]"]:checked');

            //     if (checkedCheckbox.length > 0) {
            //         checkedCheckbox.prop('checked', false);  // Uncheck all checked checkboxes
            //     }
            //     return;
            // }

            cohostbusy = false;
        })
        .fail(function (jqXHR, ajaxOptions, thrownError) {
            alert("server not responding...");
        });
}
function get_phone_host_list(search_name = null, limit, offset, scroll) {
    if (search_name == null) {
        search_name = "";
    }
    var app_user = $("#app_user").val();
    var cohostId = $("#cohostId").val();
    var cohostpreferby = $("#cohostpreferby").val();
    $(".list_all_invited_user").css("display", "none");
    $(".list_all_contact_user").css("display", "block");

    if (selected_co_host == "") {
        $(".contactData").css("display", "none");
        $(".guest-contacts-wrp").removeClass("guest-contacts-test");
    } else {
        if (co_host_is_selected_close == true) {
            $(".contactData").css("display", "none");
            $(".guest-contacts-wrp").removeClass("guest-contacts-test");
            var checkedCheckbox = $('input[name="guest_list[]"]:checked');

            if (checkedCheckbox.length > 0) {
                checkedCheckbox.prop("checked", false); // Uncheck all checked checkboxes
            }
        } else {
            $(".contactData").css("display", "flex");
            $(".guest-contacts-wrp").addClass("guest-contacts-test");
        }
    }
    // var checkedCheckbox = $('input[name="guest_list[]"]:checked');
    // if (checkedCheckbox.length > 0) {
    //     checkedCheckbox.prop('checked', false);  // Uncheck all checked checkboxes
    // }

    // $('input[name="guest_list[]"]:checked').each(function () {
    //     var profilePhoto = $(this).data('profile');
    //     var user_name = $(this).data('username');
    //     var profile_or_text = $(this).data("profile_or_text");
    //     var initial = $(this).data("initial");
    //     var prefer_by_email = $(this).data('prefer_by');

    //     // Log or process the data
    //     console.log("Profile Photo:", profilePhoto);
    //     console.log("User Name:", user_name);
    //     console.log("Profile or Text:", profile_or_text);
    //     console.log("Initial:", initial);
    //     console.log("Prefer By Email:", prefer_by_email);

    //     // Update UI based on the `profile_or_text` condition
    //     if (profile_or_text == '1') {
    //         $('.guest-img .selected-co-host-image').show();
    //         $('.guest-img .selected-co-host-image').attr('src', profilePhoto);
    //         $('.guest-img .selected-host-h5').css('display', 'none');
    //     } else {
    //         // $('.guest-img').html(profilePhoto    );
    //         $('.selected-host-h5').show();
    //         $('.selected-co-host-image').css('display', 'none');
    //         $('.guest-img .selected-host-h5').text(initial);
    //         var firstinitial = initial.charAt(0);

    //         // $('.selected-host-h5').removeClass(function (index, className) {
    //         //     return (className.match(/\bfontcolor\S+/g) || []).join(' ');
    //         // });
    //         // $('.selected-host-h5').addClass('fontcolor' + firstinitial);

    //         $('.guest-img .selected-host-h5').removeClass(function (index, className) {
    //             return (className.match(/\bfontcolor\S+/g) || []).join(' ');
    //         });

    //         // Add the new class
    //         $('.guest-img .selected-host-h5').addClass('fontcolor' + firstinitial);
    //     }
    //     $('.selected-host-name').text(user_name);
    // });

    $.ajax({
        url: base_url + "event/getPhoneContact",
        type: "GET",
        data: {
            search_name: search_name,
            selected_co_host: selected_co_host,
            limit: limit,
            offset: offset,
            scroll: scroll,
            app_user: app_user,
            cohostId: cohostId,
            cohostpreferby: cohostpreferby,
            isCohost: isCohost,
            selected_co_host_prefer_by: selected_co_host_prefer_by,
            _token: $('meta[name="csrf-token"]').attr("content"), // Adding CSRF token
        },
        beforeSend: function () {
            $("#loader").show();
        },
    })
        .done(function (data) {
            // console.log(data);
            if (data.view == "" && data.scroll == "false") {
                // cohostNoMoreContactData = true;
                $(".list_all_contact_user").html("No Data Found");
                $("#loader").hide();
                return;
            }
            if (data.view == "") {
                cohostNoMoreContactData = true;
                // $(".list_all_contact_user").html("No data found");
                $("#loader").hide();
                return;
            }
            $("#loader").hide();
            if (data.scroll == "true") {
                $(".list_all_contact_user").append(data.view);
            } else {
                $(".list_all_contact_user").html(data.view);
            }

            $('input[name="guest_list[]"]:checked').each(function () {
                var profilePhoto = $(this).data("profile");
                var user_name = $(this).data("username");
                var profile_or_text = $(this).data("profile_or_text");
                var initial = $(this).data("initial");
                var prefer_by_email = $(this).data("prefer_by");

                // Log or process the data
                console.log("Profile Photo:", profilePhoto);
                console.log("User Name:", user_name);
                console.log("Profile or Text:", profile_or_text);
                console.log("Initial:", initial);
                console.log("Prefer By Email:", prefer_by_email);

                // Update UI based on the `profile_or_text` condition
                if (profile_or_text == "1") {
                    $(".guest-img .selected-co-host-image").show();
                    $(".guest-img .selected-co-host-image").attr(
                        "src",
                        profilePhoto
                    );
                    $(".guest-img .selected-host-h5").css("display", "none");
                } else {
                    // $('.guest-img').html(profilePhoto    );
                    $(".selected-host-h5").show();
                    $(".selected-co-host-image").css("display", "none");
                    $(".guest-img .selected-host-h5").text(initial);
                    var firstinitial = initial.charAt(0);

                    // $('.selected-host-h5').removeClass(function (index, className) {
                    //     return (className.match(/\bfontcolor\S+/g) || []).join(' ');
                    // });
                    // $('.selected-host-h5').addClass('fontcolor' + firstinitial);

                    $(".guest-img .selected-host-h5").removeClass(function (
                        index,
                        className
                    ) {
                        return (className.match(/\bfontcolor\S+/g) || []).join(
                            " "
                        );
                    });

                    // Add the new class
                    $(".guest-img .selected-host-h5").addClass(
                        "fontcolor" + firstinitial
                    );
                }
                $(".selected-host-name").text(user_name);
            });

            cohostphonebusy = false;
        })
        .fail(function (jqXHR, ajaxOptions, thrownError) {
            alert("server not responding...");
        });
}
let previousScrollTop = 0;
$("#select_event_cohost").on("scroll", function () {
    // alert();
    if (cohostbusy) return;
    var scrollTop = $(this).scrollTop();
    var scrollHeight = $(this)[0].scrollHeight;
    var elementHeight = $(this).height();

    console.log({ scrollTop, scrollHeight, elementHeight });
    // console.log($("#select_event_cohost").data('list'));

    // if (scrollTop + elementHeight >= scrollHeight) {
    //     alert();
    //     cohostbusy = true;
    //     cohostoffset += cohostlimit;
    //     var type="yesvite";
    //     var scroll=true;
    //     get_co_host_list(search_name=null,cohostlimit,cohostoffset,scroll);
    //     }
    // if (scrollTop > previousScrollTop) {
    if (scrollTop + elementHeight >= scrollHeight - 2) {
        cohostbusy = true;
        cohostoffset += cohostlimit;
        var type = "yesvite";
        var scroll = true;
        if (cohostNoMoreData == false) {
            get_co_host_list(
                "0",
                (search_name = null),
                cohostlimit,
                cohostoffset,
                scroll
            );
        }
    }
    // }
    // previousScrollTop = scrollTop;
});

// var cohostphoneOffset=0;
// var cohostphoneLimit=10;
$("#select_contact_event_cohost").on("scroll", function () {
    // alert();
    if (cohostphonebusy) return;
    var scrollTop = $(this).scrollTop();
    var scrollHeight = $(this)[0].scrollHeight;
    var elementHeight = $(this).height();

    console.log({ scrollTop, scrollHeight, elementHeight });

    // if (scrollTop + elementHeight >= scrollHeight) {
    //     alert();
    //     cohostbusy = true;
    //     cohostoffset += cohostlimit;
    //     var type="yesvite";
    //     var scroll=true;
    //     get_co_host_list(search_name=null,cohostlimit,cohostoffset,scroll);
    //     }
    if (scrollTop > previousScrollTop) {
        if (scrollTop + elementHeight >= scrollHeight - 1) {
            cohostphonebusy = true;
            cohostphoneOffset += cohostphoneLimit;
            var type = "yesvite";
            var scroll = true;
            // get_co_host_list(search_name = null, cohostlimit, cohostoffset, scroll);
            if (cohostNoMoreContactData == false) {
                get_phone_host_list(
                    null,
                    cohostphoneLimit,
                    cohostphoneOffset,
                    scroll
                );
            }
        }
    }
    previousScrollTop = scrollTop;
});
$(document).on("keyup", ".co_host_search", function () {
    search_name = $(this).val();
    $("#loader").css("display", "block");
    // $(".list_all_invited_user").empty();
    setTimeout(function () {
        $(".list_all_invited_user").html("");
        // cohostoffset=0;
        // cohostlimit=7;
        get_co_host_list("0", search_name, null, null, false);
    }, 500);
});

$(document).on("keyup", ".phone_co_host_search", function () {
    search_name = $(this).val();
    $("#loader").css("display", "block");
    // $(".list_all_invited_user").empty();
    setTimeout(function () {
        $(".list_all_contact_user").html("");
        // cohostphoneOffset=0;
        // cohostphoneLimit=10;
        get_phone_host_list(search_name, null, null, false);
        // $(".list_all_invited_user").empty();
    }, 500);
});

$(document).on("click", ".add-activity-schedule", function () {
    if (eventData.activity != undefined && eventData.activity != "") {
        toggleSidebar("sidebar_activity_schedule");
    } else {
        toggleSidebar("sidebar_activity_schedule");
    }
});

$(document).on("click", ".thank_you_card_toggle", function () {
    $.ajax({
        url: base_url + "event/get_thank_you_card",
        type: "POST",
        data: {
            _token: $('meta[name="csrf-token"]').attr("content"),
        },
        beforeSend: function () {
            $("#loader").show();
        },
    })
        .done(function (data) {
            $("#loader").hide();
            if (data.status == "1") {
                toastr.success("Greeting card updated");
                $("#registry_item_id").val("");
            }
            $(".list_thankyou_card").html(data.view);

            if (eventData.thank_you_card_id != undefined) {
                $('input[name="select_thankyou[]"]').each(function () {
                    if ($(this).val() == eventData.thank_you_card_id) {
                        $(this).prop("checked", true); // Check the checkbox
                    }
                });
            }
        })
        .fail(function (jqXHR, ajaxOptions, thrownError) {
            alert("server not responding...");
        });
    toggleSidebar("sidebar_thankyou_card");
});

$(document).on("click", ".add_gift_registry", function () {
    $.ajax({
        url: base_url + "event/get_gift_registry",
        type: "POST",
        data: {
            _token: $('meta[name="csrf-token"]').attr("content"),
        },
        beforeSend: function () {
            $("#loader").show();
        },
    })
        .done(function (data) {
            $("#loader").hide();
            $("#registry_list").html(data);

            if (data.status == "1") {
                toastr.success("Gift registry updated");
                $("#registry_item_id").val("");
            }
            $("#registry_list").append(data.view);
            console.log(eventData);
            if (eventData.gift_registry_data != undefined) {
                console.log({ data: eventData.gift_registry_data });
                eventData.gift_registry_data.forEach((element, index) => {
                    console.log(element.gr_id);
                    $('input[name="gift_registry[]"]').each(function () {
                        if ($(this).val() == element.gr_id) {
                            $(this).prop("checked", true); // Check the checkbox
                        }
                    });
                });
            }
        })
        .fail(function (jqXHR, ajaxOptions, thrownError) {
            alert("server not responding...");
        });
    toggleSidebar("sidebar_gift_registry");
});

function getStartEndTimeZone() {
    var end_time = $("#end_time").is(":checked");
    if (end_time) {
        var rsvp_end_time = $("#end-time").val();
        var rsvp_end_time_set = "1";
        var start_time_zone = $("#start-time-zone").val();
        var end_time_zone = $("#end-time-zone").val();

        if (rsvp_end_time_set == "1" && start_time_zone != end_time_zone) {
            $("#end-time-zone").focus();
            $("#end-time-zone-error")
                .text(
                    "End Time zone : Please select same start time zone and end time zone"
                )
                .css("display", "block")
                .css("color", "red");
            return;
        } else {
            $("#end-time-zone-error")
                .text("")
                .css("display", "none")
                .css("color", "red");
        }
    }
}

$(document).on("click", ".all_user_list", function () {
    var is_contact = $(this).data("contact");
    // alert(is_contact);
    $.ajax({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        data: { is_contact: is_contact },
        url: base_url + "event/see_all",
        method: "POST",
        success: function (response) {
            console.log(response);
            $(".see-all-invite-member-wrp").empty();
            $(".see-all-invite-member-wrp").html(response.view);
            toggleSidebar("sidebar_see_all_invite");
        },
    });
});

$(document).on("click", ".cursor-pointer", function () {
    $("#sidebar_select_design_category").toggle();
});

$(document).on("click", ".select_design_category_close", function () {
    $("#sidebar_select_design_category").css("display", "none");
});

$(document).on("click", ".brand-progress", function () {
    // var event_id = $(this).data('id');
    // window.location.href="event?id="+event_id;
});

var limitcontact = 10;
var offsetcontact = 0;
var busycontact = false;

$(document).on("click", "#phone-tab", function () {
    $("#loader").show();
    var search_name = $("#search_contacts").val();
    offsetcontact = 0;
    displayPhoneContacts("all", 10, offsetcontact, search_name, false);
});

var search_contacts = 0;
$(document).on("keyup", "#search_contacts", function () {
    var search_name = $(this).val();
    // console.log(search_name);

    offsetcontact = 0;
    limitcontact = 10;
    clearTimeout(search_contacts);
    search_contacts = setTimeout(function () {
        $("#loader").css("display", "block");
        displayPhoneContacts(
            "all",
            limitcontact,
            offsetcontact,
            search_name,
            false
        );
    }, 750);
});

// $("#YesviteContactsAll").html(html.view);

$("#YesviteContactsAll").on("scroll", function () {
    // clearTimeout(debounceTimer);
    // debounceTimer = setTimeout(() => {
    if (busycontact||create_event_phone_scroll) return;

    var scrollTop = $(this).scrollTop();
    var scrollHeight = $(this)[0].scrollHeight;
    var elementHeight = $(this).height();

    if (scrollTop + elementHeight >= scrollHeight-2) {
        busycontact = true;
        offsetcontact += limitcontact;
        var type = "phone";
        // loadMorePhones(search_name=null,type,offset1,limit);
        displayPhoneContacts(
            "all",
            limitcontact,
            offsetcontact,
            (search_name = ""),
            true
        );

        // function loadMoreData(page, search_name)
        // loadMoreGroups(page, search_group);
        // loadMorePhones(page, search_phone);
    }
    // }, 200);
});

function displayPhoneContacts(type = "all", lim, off, search_name, scroll) {
    $.ajax({
        type: "GET",
        async: false,
        url: base_url + "event/get_contacts",
        data:
            "limit=" +
            lim +
            "&offset=" +
            off +
            "&type=" +
            type +
            "&search_user=" +
            search_name +
            "&scroll=" +
            scroll +
            "&app_user=" +
            app_user +
            "&cohostId=" +
            cohostId,
        cache: false,
        beforeSend: function () {},
        success: function (html) {
            console.log(html);
            isSetSession = 1;
            var currentInviteCount = parseInt($("#currentInviteCount").val());
            const coins = $("#coins").val();
            if(search_name==""){
                create_event_phone_scroll=false;
            }else{
                create_event_phone_scroll=true;
            }
            if (currentInviteCount >= coins) {
                $(".user_choice").prop("disabled", true);
            }
            if (html.view == "" && html.scroll == "true") {
                // $("#YesviteContactsAll").html("No data found");
                $("#loader").css("display", "none");
                return;
            }
            if (html.view == "" && html.scroll == "false") {
                $("#YesviteContactsAll").html("No data found");
                $("#loader").css("display", "none");
                return;
            }
            if (type == "all" && html.scroll == "false") {
                $("#YesviteContactsAll").html(html.view);
            }
            if (html.scroll == "true") {
                $("#YesviteContactsAll").append(html.view);
            }
            busycontact = false;
            setTimeout(function () {
                $("#loader").css("display", "none");
            }, 1000);
        },
    });
}

// $(document).on("click", ".new-temp", function () {
//     // Get the image URL from the data-image attribute
//     var imageUrl = $(this).data("image");

//     // Set the image URL in the modal's image tag
//     $("#modalImage").attr("src", imageUrl);

//     // Show the modal using Bootstrap's modal method
//     // $("#myCustomModal").modal("show");

//     const modalElement = document.getElementById('myCustomModal');
//     const modal = new Modal(modalElement, {
//       backdrop: false,
//       keyboard: true,
//       focus: true
//     });
//     modal.show();
// });

$(document).on("click", ".edit_event_details", function () {
    $("#loader").show();
    // $(this).prop("disabled", true);
    // $('.btn-close').prop("disabled", true);
    if (final_step == 1) {
        final_step = 2;
    }
    eventData.step = final_step;
    $("#loader").css("display", "none");
    $(".store_desgin_temp").prop("disabled", false);
    $(".btn-close").prop("disabled", false);
    $(".main-content-wrp").removeClass("blurred");
    $(".step_2").hide();
    $(".step_4").hide();
    $(".step_3").hide();
    $(".current_step").text("2 of 4");
    $(".step_1").show();
    active_responsive_dropdown("drop-down-event-detail");
    console.log("handleActiveClass");

    handleActiveClass(this);
    $(".li_event_detail").find(".side-bar-list").addClass("active");
    $(".main-content-wrp").addClass("blurred");
});

$("#isCheckAddress").on("click", function () {
    if ($(this).is(":checked")) {
        $(".ckeckedAddress").show();
        var address1 = $("#address1").val();
        var city = $("#city").val();
        var state = $("#state").val();
        var zipcode = $("#zipcode").val();
        // if (address1 == "") {
        //     $("#event-address1-error")
        //         .css("display", "block")
        //         .css("color", "red")
        //         .text("Please enter address1");
        //         focus_timeOut('address1');
        //     return;
        // } else {
        //     $("#event-address1-error").css("display", "none");
        // }
        // if (city == "") {
        //     $("#event-city-error")
        //         .css("display", "block")
        //         .css("color", "red")
        //         .text("Please enter city");
        //         focus_timeOut('city');
        //     return;
        // } else {
        //     $("#event-city-error").css("display", "none");
        // }
        // if (state == "") {
        //     $("#event-state-error")
        //         .css("display", "block")
        //         .css("color", "red")
        //         .text("Please enter state");
        //         focus_timeOut('state');
        //     return;
        // } else {
        //     $("#event-state-error").css("display", "none");
        // }
        // if (zipcode == "") {
        //     $("#event-zipcode-error")
        //         .css("display", "block")
        //         .css("color", "red")
        //         .text("Please enter zipcode");
        //         focus_timeOut('zipcode');
        //     return;
        // } else {
        //     $("#event-zipcode-error").css("display", "none");
        // }
    } else {
        $(".ckeckedAddress").hide();
        $("#zipcode").val("");
        $("#city").val("");
        $("#state").val("");
        $("#address2").val("");
        $("#address1").val("");
    }
});

var search_user_ajax_timer = 0;
$(document).on("keyup", ".searchCategory", function () {
    search_name = $(this).val();
    offset = 0;
    search_user_ajax_timer = setTimeout(function () {
        // $('#loader').css('display','block');
        searchRecords(limit, offset, "all", search_name);
        // $('#loader').css('display','none');
    }, 750);
});

function searchRecords(lim, off, type, search = null) {
    var search_name = $(".searchCategory").val();
    if (search_name !== "") {
        off = 0;
    }
    $.ajax({
        type: "GET",
        url: base_url + "event/getCategory",
        data: {
            search_user: search_name,
        },
        cache: false,
        success: function (html) {
            console.log(html);
            $(".designCategory").html(html.view);
        },
        error: function (xhr, status, error) {
            console.error("Error fetching data:", error);
        },
    });
}

$(document).on("change", ".slider_photo", function (event) {
    // alert();
    var file = event.target.files[0]; // Get the first file (the selected image)
    if (file) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $(".photo-slider-1").attr("src", e.target.result).show();
        };
        reader.readAsDataURL(file);
        $('.photo-edit-delete-1').show();
        $(".design-sidebar").addClass("d-none");
        $(".design-sidebar_7").removeClass("d-none");
        $("#sidebar").addClass("design-sidebar_7");
        $(".close-btn").attr("data-id", "design-sidebar_7");
    }
    setTimeout(() => {
        getLengthofSliderImage();
    }, 500);
});

$(document).on("change", ".slider_photo_2", function (event) {
    var file = event.target.files[0];
    if (file) {
        $(".photo-slider-2").show();
        var reader = new FileReader();
        $('.photo-edit-delete-2').show();
        reader.onload = function (e) {
            $(".photo-slider-2").attr("src", e.target.result).show();
        };
        reader.readAsDataURL(file);
    }
    setTimeout(() => {
        getLengthofSliderImage();
    }, 500);
});
$(document).on("change", ".slider_photo_3", function (event) {
    var file = event.target.files[0];
    if (file) {
        $(".photo-slider-3").show();
        $('.photo-edit-delete-3').show();
        var reader = new FileReader();
        reader.onload = function (e) {
            $(".photo-slider-3").attr("src", e.target.result).show();
        };
        reader.readAsDataURL(file);
    }
    setTimeout(() => {
        getLengthofSliderImage();
    }, 500);
});

function getLengthofSliderImage() {
    var i = 0;
    $(".slider_img").each(function () {
        var src = $(this).attr("src");
        // console.log(src);
        if (src !== "") {
            i++;
        }
    });
    $(".slider_image_count").text(i + "/3 Photos");
}

$(document).on("click", ".save-slider-image", function () {
    var imageSources = [];
    // $(".slider_img").each(function () {
    //     imageSources.push($(this).attr("src"));
    // });

    $(".slider_img").each(function () {
        var src = $(this).attr("src");
        if (src !== "") {
            imageSources.push({
                src: $(this).attr("src"),
                deleteId: $(this).data("delete"),
            });
        }
    });
    //console.log(imageSources);
    if (imageSources.length > 0) {
        $("#loader").css("display", "block");
        $.ajax({
            url: base_url + "event/save_slider_img",
            method: "POST",
            data: {
                imageSources: imageSources,
                _token: $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (response) {
                var savedImages = response.images;
                eventData.slider_images = savedImages;
                console.log(eventData);
                $("#loader").css("display", "none");
                toastr.success("Slider Image saved Successfully");
            },
            error: function (xhr, status, error) {},
        });
    }
});

$(document).on("click", ".delete_silder", function (e) {
    e.preventDefault();
    var delete_id = $(this).parent().find(".slider_img").data("delete");
    var src = $(this).parent().find(".slider_img").attr("src");
    if (src != "") {
        $("#loader").css("display", "block");
        var $this = $(this);
        var check_slider_img = eventData.slider_images;
        var matchFound = false;
        $.each(check_slider_img, function (index, slider) {
            if (slider.deleteId == delete_id) {
                matchFound = true;
                return false;
            }
        });
        if (matchFound) {
            $.ajax({
                url: base_url + "event/delete_slider_img",
                method: "POST",
                data: {
                    delete_id: delete_id,
                    _token: $('meta[name="csrf-token"]').attr("content"),
                },
                success: function (response) {
                    $this.parent().find(".slider_img").attr("src", "");
                    $(".photo-slider-" + delete_id).hide();
                    toastr.success("Slider Image Deleted Successfully");
                    $("#loader").css("display", "none");
                },
                error: function (xhr, status, error) {},
            });
        } else {
            $(this).parent().find(".slider_img").attr("src", "");
            $(".photo-slider-" + delete_id).hide();
            $(".photo-edit-delete-" + delete_id).hide();
            $("#loader").css("display", "none");
            toastr.success("Slider Image Deleted Successfully");
        }
    }
    setTimeout(() => {
        getLengthofSliderImage();
    }, 500);
});

$(document).on("click", ".edit_checkout", function (e) {
    var isDraftEdit = $(this).attr("data-isDraftEdit");
    if (isDraftEdit) {
        eventData.is_update_event = "0";
        eventData.isDraftEdit = isDraftEdit;
    } else {
        eventData.is_update_event = "1";
    }
    savePage1Data();
    savePage3Data();
    savePage4Data();
    eventData.isPhonecontact = isPhonecontact;
    var data = eventData;

    $("#loader").css("display", "flex");
    // $(".main-content-wrp").addClass("blurred");
    e.stopPropagation();
    e.preventDefault();
    // var imagePath = '';

    // $('#eventImage').attr('src',base_url+'public/storage/event_images/'+eventData.desgin_selected+'');
    //     $(".step_1").css("display", "none");
    //     $(".step_2").css("display", "none");
    //     $(".step_3").css("display", "none");
    //     $(".step_4").css("display", "none");
    //     $(".step_final_checkout").show();

    // handleActiveClass(this);
    eventData.isdraft = "0";
    $.ajax({
        url: base_url + "event/editStore",
        type: "POST",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        data: data,
        success: function (response) {
            $(".main-content-wrp").removeClass("blurred");
            if (response.isupadte == true) {
                if (response.success == true) {
                    toastr.success("Event Updated Successfully");
                    window.location.href = base_url + "home";
                }
            } else {
                if (response.is_registry == "1") {
                    $("#gift_registry_logo").html(response.view);
                    // $('#eventModal').modal('show');
                } else {
                    toastr.success("Event Created Successfully");
                    // window.location.href="profile";
                }
                $("#eventModal").modal("show");
            }
        },
        error: function (xhr, status, error) {
            console.log("AJAX error: " + error);
        },
    });
});

$(document).on("click", ".design-sidebar-action", function () {
    let designId = $(this).attr("design-id");
    if (designId) {
        if (designId == "6") {
            var imgSrc1 = $(".photo-slider-1").attr("src");
            var imgSrc2 = $(".photo-slider-2").attr("src");
            var imgSrc3 = $(".photo-slider-3").attr("src");
            console.log(eventData.slider_images);
            if (
                eventData.slider_images != undefined &&
                eventData.slider_images != ""
            ) {
                $(".design-sidebar").addClass("d-none");
                $(".design-sidebar_7").removeClass("d-none");
                $("#sidebar").addClass("design-sidebar_7");
                $(".close-btn").attr("data-id", "design-sidebar_7");
                const photoSliders = [
                    "photo-slider-1",
                    "photo-slider-2",
                    "photo-slider-3",
                ];

                const sliderImages = eventData.slider_images;

                photoSliders.forEach((sliderClass, index) => {
                    const sliderElement = document.querySelector(
                        `.${sliderClass}`
                    );

                    if (sliderElement && sliderImages[index]) {
                        sliderElement.src = `${base_url}public/storage/event_images/${sliderImages[index].fileName}`;
                        console.log(
                            `Set src for ${sliderClass}: ${sliderElement.src}`
                        );
                    } else {
                        console.log(
                            `No element found for class: ${sliderClass} or missing image data.`
                        );
                    }
                });
            } else {
                $(".design-sidebar").addClass("d-none");
                $(".design-sidebar_" + designId).removeClass("d-none");
                $("#sidebar").addClass("design-sidebar_" + designId);
                $(".close-btn").attr("data-id", "design-sidebar_" + designId);
            }
        } else {
            $(".design-sidebar").addClass("d-none");
            $(".design-sidebar_" + designId).removeClass("d-none");
            $("#sidebar").addClass("design-sidebar_" + designId);
            $(".close-btn").attr("data-id", "design-sidebar_" + designId);
        }
    }
});

$(document).on("click", "#close_editEvent", function (e) {
    // if (final_step == 2) {
    savePage1Data(1);
    // }
    // if (final_step == 3) {
    var savePage3Result = savePage3Data(1);
    if (savePage3Result === false) {
        $("#loader").css("display", "none");
        return;
    }
    // }
    $("#loader").css("display", "flex");
    eventData.step = final_step;
    eventData.isdraft = "1";
    savePage4Data();
    $(".main-content-wrp").addClass("blurred");
    e.stopPropagation();
    e.preventDefault();
    $.ajax({
        url: base_url + "event/editStore",
        type: "POST",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        data: eventData,
        success: function (response) {
            if (response == 1) {
                // window.location.href = base_url + "home";
                toastr.success("Event Saved as Draft");
                // setTimeout(function () {
                //     $("#loader").css("display", "none");
                // }, 4000);
            }
        },
        error: function (xhr, status, error) {
            console.log("AJAX error: " + error);
        },
    });
});

if (final_step == "2" && isCohost == "1") {
    $("#loader").css("display", "flex");
    setTimeout(function () {
        step2Open();
        $(".li_guest").find(".menu-circle-wrp").removeClass("menu-success");
        $(".li_guest").addClass("menu-success");

        $(".li_setting").find(".menu-circle-wrp").removeClass("menu-success");
        $("#loader").css("display", "none");
    }, 1000);
}

if (final_step == "3" && isCohost == "1") {
    $("#loader").css("display", "flex");
    setTimeout(function () {
        step3open();
        $(".li_setting").find(".menu-circle-wrp").removeClass("menu-success");
        $("#loader").css("display", "none");
    }, 1000);
}

if (final_step == "4" && isCohost == "1") {
    $("#loader").css("display", "flex");
    setTimeout(function () {
        step4open();
        $("#loader").css("display", "none");
    }, 1000);
}

function step2Open() {
    $("#close_createEvent").css("display", "block");

    var design = eventData.desgin_selected;
    console.log(design);

    $(".li_event_detail").find(".side-bar-list").addClass("active");
    if (final_step <= 1) {
        $(".side-bar-list").removeClass("active");
    }
    if (design == undefined || design == "") {
    } else {
        $("#sidebar_select_design_category").css("display", "none");
        $("#myCustomModal").modal("hide");
        $("#exampleModal").modal("hide");
        $("#loader").css("display", "none");
        $(".store_desgin_temp").prop("disabled", false);
        $(".btn-close").prop("disabled", false);
        $(".main-content-wrp").removeClass("blurred");
        $(".step_2").hide();
        $(".step_4").hide();
        $(".step_3").hide();
        $(".step_final_checkout").hide();
        $("#edit-design-temp").hide();
        $(".pick-card").addClass("menu-success");
        $(".edit-design").addClass("menu-success");
        $(".edit-design").removeClass("active");
        $(".li_design").find(".side-bar-list").addClass("menu-success");
        $(".li_design").addClass("menu-success");

        $(".event_create_percent").text("50%");
        $(".current_step").text("2 of 4");
        console.log(eventData);
        var type = "all";
        // get_user(type);
        $(".step_1").show();
        active_responsive_dropdown("drop-down-event-detail");
        console.log("handleActiveClass");

        handleActiveClass(".li_event_detail");
        $(".pick-card").addClass("menu-success");
        $(".edit-design").addClass("menu-success");
    }
}

function step3open() {
    $("#close_createEvent").css("display", "block");
    // var eventDetail2 = $('#eventDetail').val();
    // eventDetail2 = JSON.parse(eventDetail2);
    // if(eventDetail2.static_information != '' && (eventData.desgin_selected === undefined)){
    //     var design = eventData.desgin_selected;
    // }else{

    var event_name = $("#event-name").val();
    var hostedby = $("#hostedby").val();
    var event_date = $("#event-date").val();
    var start_time = $("#start-time").val();

    var schedule = $("#schedule").is(":checked");
    var end_time = $("#end_time").is(":checked");
    var rsvp_by_date_set = $("#rsvp_by_date").is(":checked");
    var address_2 = $("#address2").val();
    var address1 = $("#address1").val();
    var city = $("#city").val();
    var state = $("#state").val();
    var zipcode = $("#zipcode").val();

    // var activity=$('.event_all_activity_list').length;
    // console.log(activity);
    // if($('#schedule').is(":checked")){
    //     if(activity==0){
    //         toastr.error('Event Schedule: Please set event schedule');
    //         return;
    //     }
    // }

    if (event_name == "") {
        toastr.error("Please enter event name");
        return;
    }
    if (hostedby == "") {
        toastr.error("Please enter hosted by");
        return;
    }
    if (event_date == "") {
        toastr.error("Please enter start date");
        return;
    }
    if (start_time == "") {
        toastr.error("Please select start time");
        return;
    }
    if (end_time) {
        rsvp_end_time = $("#end-time").val();
        if (rsvp_end_time == "") {
            toastr.error("Please select end time");
            return;
        }
    }
    if (rsvp_by_date_set) {
        rsvp_by_date = $("#rsvp-by-date").val();
        if (rsvp_by_date == "") {
            toastr.error("RSVP by Date : Please select RSVP date");
            return;
        }
    }

    if ($("#isCheckAddress").is(":checked")) {
        if (address1 == "") {
            toastr.error("Please enter address1");
            return;
        }
        if (city == "") {
            toastr.error("Please enter city");
            return;
        }
        if (state == "") {
            toastr.error("Please enter state");
            return;
        }
        if (zipcode == "") {
            toastr.error("Please enter zipcode");
            return;
        }
    }
    var design = eventData.desgin_selected;
    // }

    // if( design == undefined || design == ''){
    //     console.log(final_step);
    if (final_step <= "2") {
        return;
    } else {
        $(".step_1").css("display", "none");
        $(".step_2").css("display", "none");
        $("#edit-design-temp").css("display", "none");
        $(".step_4").css("display", "none");
        $(".step_final_checkout").css("display", "none");
        $(".step_3").show();
        $(".pick-card").addClass("menu-success");
        $(".edit-design").addClass("menu-success");
        $(".event_create_percent").text("75%");
        $(".current_step").text("3 of 4");
        $("#sidebar_select_design_category").css("display", "none");
        active_responsive_dropdown("drop-down-event-guest");
        console.log("handleActiveClass");

        handleActiveClass(".li_guest");
        var type = "all";
        const stepVal = $("#CheckCuurentStep").val();
        // alert(stepVal);
        if (stepVal == "0") {
            get_user(type);
        }
        $("#CheckCuurentStep").val("1");
    }
}

function step4open() {
    $("#close_createEvent").css("display", "block");

    var event_name = $("#event-name").val();
    var hostedby = $("#hostedby").val();
    var event_date = $("#event-date").val();
    var start_time = $("#start-time").val();
    var end_time = $("#end_time").is(":checked");
    var rsvp_by_date_set = $("#rsvp_by_date").is(":checked");
    var address1 = $("#address1").val();
    var city = $("#city").val();
    var state = $("#state").val();
    var zipcode = $("#zipcode").val();

    if (event_name == "") {
        toastr.error("Please enter event name");
        return;
    }
    if (event_name == "") {
        toastr.error("Please enter hosted by");
        return;
    }
    if (hostedby == "") {
        toastr.error("Please enter event name");
        return;
    }
    if (event_date == "") {
        toastr.error("Please enter start date");
        return;
    }
    if (start_time == "") {
        toastr.error("Please enter start time");
        return;
    }
    if (end_time) {
        rsvp_end_time = $("#end-time").val();
        if (rsvp_end_time == "") {
            toastr.error("Please select end time");
            return;
        }
    }
    if (rsvp_by_date_set) {
        rsvp_by_date = $("#rsvp-by-date").val();
        if (rsvp_by_date == "") {
            toastr.error("RSVP by Date : Please select RSVP date");
            return;
        }
    }
    if ($("#isCheckAddress").is(":checked")) {
        if (address1 == "") {
            toastr.error("Please enter address1");
            return;
        }
        if (city == "") {
            toastr.error("Please enter city");
            return;
        }
        if (state == "") {
            toastr.error("Please enter state");
            return;
        }
        if (zipcode == "") {
            toastr.error("Please enter zipcode");
            return;
        }
    }
    var design = eventData.desgin_selected;
    var step3 = eventData.step;
    if (step3 > 3) {
        $("#sidebar_select_design_category").css("display", "none");
        $(".step_1").css("display", "none");
        $(".step_2").css("display", "none");
        $("#edit-design-temp").css("display", "none");
        $(".step_3").css("display", "none");
        $(".step_final_checkout").css("display", "none");
        $(".step_4").show();
        $(".event_create_percent").text("99%");
        $(".current_step").text("4 of 4");
        console.log("handleActiveClass");

        handleActiveClass(".li_setting");
        active_responsive_dropdown("drop-down-event-setting");
        if (design == undefined || design == "") {
        } else {
            $(".pick-card").addClass("menu-success");
            $(".edit-design").addClass("menu-success");
        }
    }
}
var remainingCategoryCount = 0;
var remainingCategoryCountn = 0;
function update_self_bring(
    that,
    innerUserQnt,
    categoryItemKey,
    categoryIndexKey,
    quantity,
    categoryItemQuantity,
    type
) {
    $.ajax({
        url: base_url + "event/update_self_bring",
        method: "POST",
        data: {
            categoryItemKey: categoryItemKey,
            categoryIndexKey: categoryIndexKey,
            quantity: quantity,
            type: type,
            _token: $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (response) {
            var newdata = $(
                "#h6-" + categoryItemKey + "-" + categoryIndexKey
            ).text();
            var parts = newdata.split("/");
            var remain = parseInt(parts[0], 10);
            var extra = 0;
            var misstingquantity = categoryItemQuantity - innerUserQnt;
            if (type == undefined) {
                $("#h6-" + categoryItemKey + "-" + categoryIndexKey).text(
                    innerUserQnt + 0 + "/" + categoryItemQuantity
                );
            } else {
                $("#h6-" + categoryItemKey + "-" + categoryIndexKey).text(
                    innerUserQnt + quantity + "/" + categoryItemQuantity
                );
            }
            var categoryItem = parseInt(
                $(".missing-category-h6-" + categoryIndexKey).text()
            );

            var categoryItemextra = parseInt(
                $(".extra-category-h6-" + categoryIndexKey).text()
            );
            var userQuantity = innerUserQnt + quantity;
            let remaining_count =
                innerUserQnt + quantity - categoryItemQuantity;

            if (categoryItem == 0) {
                $(".extra-category-h6-" + categoryIndexKey).show();
                $("#extra-category-" + categoryIndexKey).show();
                if (type == undefined) {
                } else if (type == "plus") {
                    remainingCategoryCountn = categoryItemextra + 1;
                    $("#extra-category-" + categoryIndexKey).text(
                        remainingCategoryCountn
                    );
                } else {
                    remainingCategoryCountn = categoryItemextra - 1;
                    $("#extra-category-" + categoryIndexKey).text(
                        remainingCategoryCountn
                    );
                }

                if (remainingCategoryCountn < 0) {
                    $("#extra-category-" + categoryIndexKey).text(0);
                    remainingCategoryCount = 1;
                    $("#missing-category-" + categoryIndexKey).text(
                        remainingCategoryCount
                    );
                }

                if (remainingCategoryCountn == 0) {
                    $("#extra-category-" + categoryIndexKey).text(0);
                    $(".extra-category-h6-" + categoryIndexKey).hide();
                    $("#extra-category-" + categoryIndexKey).hide();
                }
            } else {
                // $(".extra-category-h6-" + categoryIndexKey).hide();
                // $("#extra-category-" + categoryIndexKey).hide();
                // $("#extra-category-" + categoryIndexKey).text(0);
                if (type == undefined) {
                } else if (type == "plus") {
                    if (userQuantity > categoryItemQuantity) {
                        $(".extra-category-h6-" + categoryIndexKey).show();
                        $("#extra-category-" + categoryIndexKey).show();
                        remainingCategoryCountn = categoryItemextra + 1;
                        $("#extra-category-" + categoryIndexKey).text(
                            remainingCategoryCountn
                        );
                    } else {
                        $(".extra-category-h6-" + categoryIndexKey).hide();
                        $("#extra-category-" + categoryIndexKey).hide();
                        // $("#extra-category-" + categoryIndexKey).text(0);
                        remainingCategoryCount = categoryItem - 1;
                        $("#missing-category-" + categoryIndexKey).text(
                            remainingCategoryCount
                        );
                    }
                } else {
                    if (userQuantity > categoryItemQuantity) {
                        $(".extra-category-h6-" + categoryIndexKey).show();
                        $("#extra-category-" + categoryIndexKey).show();
                        remainingCategoryCountn = categoryItemextra - 1;
                        $("#extra-category-" + categoryIndexKey).text(
                            remainingCategoryCountn
                        );
                    } else {
                        $(".extra-category-h6-" + categoryIndexKey).hide();
                        $("#extra-category-" + categoryIndexKey).hide();
                        // $("#extra-category-" + categoryIndexKey).text(0);
                        categoryItemextra = 0;
                        if (
                            categoryItemextra == 0 &&
                            userQuantity == categoryItemQuantity
                        ) {
                            remainingCategoryCount = 0 + categoryItem;
                        } else {
                            remainingCategoryCount = categoryItem + 1;
                        }
                        $("#missing-category-" + categoryIndexKey).text(
                            remainingCategoryCount
                        );
                    }
                }
            }

            $("#deleteBring-" + categoryItemKey + "-" + categoryIndexKey).data(
                "extraquantity"
            );
            // if( categoryItemQuantity < quantity || categoryItem == 0 ){

            //    if(categoryItem == 0){
            //         $(".extra-category-h6-" + categoryIndexKey).show();
            //         $("#extra-category-" + categoryIndexKey).show();
            //         var categoryItemextra = parseInt(
            //             $(".extra-category-h6-" + categoryIndexKey).text()
            //         );
            //         if (type == undefined){
            //             if(remaining_count  > 0){
            //                 remainingCategoryCountn = remainingCategoryCountn - remaining_count;
            //                 $("#extra-category-" + categoryIndexKey).text(remainingCategoryCountn);
            //             }
            //             remainingCategoryCount = categoryItem + categoryItemQuantity -  innerUserQnt;
            //             $("#missing-category-" + categoryIndexKey).text(remainingCategoryCount);
            //         }
            //         // else if(type == "plus") {
            //         //     remainingCategoryCountn = categoryItemextra + 1
            //         //     $("#extra-category-" + categoryIndexKey).text(extra);
            //         // }else{
            //         //     remainingCategoryCountn = categoryItemextra - 1;
            //         //     $("#extra-category-" + categoryIndexKey).text(extra);
            //         // }
            //         // if(remainingCategoryCountn == 0){
            //         //     $(".extra-category-h6-" + categoryIndexKey).hide();
            //         //     $("#extra-category-" + categoryIndexKey).hide();
            //         // }

            //         // if(remainingCategoryCountn >=0){
            //         //     $("#extra-category-" + categoryIndexKey).text(remainingCategoryCountn);
            //         // }
            //         // if(remaining_count  > 0){
            //         //     $("#missing-category-" + categoryIndexKey).text(0);
            //         //     var svg =
            //         //     '<svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M13.5067 9.61399L9.23998 1.93398C8.66665 0.900651 7.87332 0.333984 6.99998 0.333984C6.12665 0.333984 5.33332 0.900651 4.75998 1.93398L0.493318 9.61399C-0.0466816 10.594 -0.106682 11.534 0.326652 12.274C0.759985 13.014 1.61332 13.4207 2.73332 13.4207H11.2667C12.3867 13.4207 13.24 13.014 13.6733 12.274C14.1067 11.534 14.0467 10.5873 13.5067 9.61399ZM6.49998 5.00065C6.49998 4.72732 6.72665 4.50065 6.99998 4.50065C7.27332 4.50065 7.49998 4.72732 7.49998 5.00065V8.33398C7.49998 8.60732 7.27332 8.83398 6.99998 8.83398C6.72665 8.83398 6.49998 8.60732 6.49998 8.33398V5.00065ZM7.47332 10.8073C7.43998 10.834 7.40665 10.8607 7.37332 10.8873C7.33332 10.914 7.29332 10.934 7.25332 10.9473C7.21332 10.9673 7.17332 10.9807 7.12665 10.9873C7.08665 10.994 7.03998 11.0007 6.99998 11.0007C6.95998 11.0007 6.91332 10.994 6.86665 10.9873C6.82665 10.9807 6.78665 10.9673 6.74665 10.9473C6.70665 10.934 6.66665 10.914 6.62665 10.8873C6.59332 10.8607 6.55998 10.834 6.52665 10.8073C6.40665 10.6807 6.33332 10.5073 6.33332 10.334C6.33332 10.1607 6.40665 9.98732 6.52665 9.86065C6.55998 9.83399 6.59332 9.80732 6.62665 9.78065C6.66665 9.75398 6.70665 9.73398 6.74665 9.72065C6.78665 9.70065 6.82665 9.68732 6.86665 9.68065C6.95332 9.66065 7.04665 9.66065 7.12665 9.68065C7.17332 9.68732 7.21332 9.70065 7.25332 9.72065C7.29332 9.73398 7.33332 9.75398 7.37332 9.78065C7.40665 9.80732 7.43998 9.83399 7.47332 9.86065C7.59332 9.98732 7.66665 10.1607 7.66665 10.334C7.66665 10.5073 7.59332 10.6807 7.47332 10.8073Z" fill="#F73C71" /></svg>';
            //         //     $(".missing-category-svg-" + categoryIndexKey).html(svg);
            //         //     $(".missing-category-h6-" + categoryIndexKey).css(
            //         //         "color",
            //         //         "#E20B0B"
            //         //     );
            //         // }
            //     }else{
            //     if (type == undefined) {
            //         if (remaining_count > 0) {
            //             remainingCategoryCountn =
            //                 remainingCategoryCountn - remaining_count;
            //             $("#extra-category-" + categoryIndexKey).text(
            //                 remainingCategoryCountn
            //             );
            //         }
            //         remainingCategoryCount =
            //             categoryItem + categoryItemQuantity - innerUserQnt;
            //     }
            //     // else if (type == "plus") {
            //     //     remainingCategoryCount = categoryItem - 1;
            //     // } else {
            //     //     remainingCategoryCount = categoryItem + 1;
            //     // }
            //     if(remainingCategoryCountn == 0){
            //         $(".extra-category-h6-" + categoryIndexKey).hide();
            //         $("#extra-category-" + categoryIndexKey).hide();
            //     }
            //     $("#missing-category-" + categoryIndexKey).text(
            //         remainingCategoryCount
            //     );

            //     }
            // if(remainingCategoryCount <0){
            //     $("#extra-category-" + categoryIndexKey).text(remainingCategoryCount);
            // }else{
            // }

            // document.getElementById("#missing-category-" + categoryIndexKey).text(response);

            if (remainingCategoryCount == 0) {
                // if (response == 0) {
                var svg =
                    '<svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M7.00016 0.333984C3.32683 0.333984 0.333496 3.32732 0.333496 7.00065C0.333496 10.674 3.32683 13.6673 7.00016 13.6673C10.6735 13.6673 13.6668 10.674 13.6668 7.00065C13.6668 3.32732 10.6735 0.333984 7.00016 0.333984ZM10.1868 5.46732L6.40683 9.24732C6.3135 9.34065 6.18683 9.39398 6.0535 9.39398C5.92016 9.39398 5.7935 9.34065 5.70016 9.24732L3.8135 7.36065C3.62016 7.16732 3.62016 6.84732 3.8135 6.65398C4.00683 6.46065 4.32683 6.46065 4.52016 6.65398L6.0535 8.18732L9.48016 4.76065C9.6735 4.56732 9.9935 4.56732 10.1868 4.76065C10.3802 4.95398 10.3802 5.26732 10.1868 5.46732Z" fill="#23AA26"></path></svg>';
                $(".missing-category-svg-" + categoryIndexKey).html(svg);
                console.log({ categoryIndexKey });
                $(".missing-category-h6-" + categoryIndexKey).css(
                    "color",
                    "#34C05C"
                );
            } else {
                var svg =
                    '<svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M13.5067 9.61399L9.23998 1.93398C8.66665 0.900651 7.87332 0.333984 6.99998 0.333984C6.12665 0.333984 5.33332 0.900651 4.75998 1.93398L0.493318 9.61399C-0.0466816 10.594 -0.106682 11.534 0.326652 12.274C0.759985 13.014 1.61332 13.4207 2.73332 13.4207H11.2667C12.3867 13.4207 13.24 13.014 13.6733 12.274C14.1067 11.534 14.0467 10.5873 13.5067 9.61399ZM6.49998 5.00065C6.49998 4.72732 6.72665 4.50065 6.99998 4.50065C7.27332 4.50065 7.49998 4.72732 7.49998 5.00065V8.33398C7.49998 8.60732 7.27332 8.83398 6.99998 8.83398C6.72665 8.83398 6.49998 8.60732 6.49998 8.33398V5.00065ZM7.47332 10.8073C7.43998 10.834 7.40665 10.8607 7.37332 10.8873C7.33332 10.914 7.29332 10.934 7.25332 10.9473C7.21332 10.9673 7.17332 10.9807 7.12665 10.9873C7.08665 10.994 7.03998 11.0007 6.99998 11.0007C6.95998 11.0007 6.91332 10.994 6.86665 10.9873C6.82665 10.9807 6.78665 10.9673 6.74665 10.9473C6.70665 10.934 6.66665 10.914 6.62665 10.8873C6.59332 10.8607 6.55998 10.834 6.52665 10.8073C6.40665 10.6807 6.33332 10.5073 6.33332 10.334C6.33332 10.1607 6.40665 9.98732 6.52665 9.86065C6.55998 9.83399 6.59332 9.80732 6.62665 9.78065C6.66665 9.75398 6.70665 9.73398 6.74665 9.72065C6.78665 9.70065 6.82665 9.68732 6.86665 9.68065C6.95332 9.66065 7.04665 9.66065 7.12665 9.68065C7.17332 9.68732 7.21332 9.70065 7.25332 9.72065C7.29332 9.73398 7.33332 9.75398 7.37332 9.78065C7.40665 9.80732 7.43998 9.83399 7.47332 9.86065C7.59332 9.98732 7.66665 10.1607 7.66665 10.334C7.66665 10.5073 7.59332 10.6807 7.47332 10.8073Z" fill="#F73C71" /></svg>';
                $(".missing-category-svg-" + categoryIndexKey).html(svg);
                $(".missing-category-h6-" + categoryIndexKey).css(
                    "color",
                    "#E20B0B"
                );
            }

            $(
                ".category-item-total-" +
                    categoryItemKey +
                    "-" +
                    categoryIndexKey
            ).text(quantity);

            if (type == "plus") {
                var current_item = parseInt(
                    $(".total-self-bring-" + categoryIndexKey).text()
                );
                current_item = current_item + 1;
                $(".total-self-bring-" + categoryIndexKey).text(current_item);
            } else if (type == "minus") {
                var current_item = parseInt(
                    $(".total-self-bring-" + categoryIndexKey).text()
                );
                current_item = current_item - 1;
                $(".total-self-bring-" + categoryIndexKey).text(current_item);
            }

            if (innerUserQnt + quantity >= categoryItemQuantity) {
                // if ((quantity+innerUserQnt) == categoryItemQuantity) {
                $(
                    "#lumpia-collapseOne" +
                        "-" +
                        categoryItemKey +
                        "-" +
                        categoryIndexKey
                )
                    .parent()
                    .parent()
                    .find(".accordion-item")
                    .removeClass("red-border");

                $(
                    "#lumpia-collapseOne" +
                        "-" +
                        categoryItemKey +
                        "-" +
                        categoryIndexKey
                )
                    .parent()
                    .parent()
                    .find(".accordion-item")
                    .addClass("green-border");

                $(
                    "#success-svg-" + categoryItemKey + "-" + categoryIndexKey
                ).show();
                $(
                    "#danger-svg-" + categoryItemKey + "-" + categoryIndexKey
                ).hide();
                // var missingCategory = $('#missing-category-'+categoryIndexKey).text();
                // missingCategory--;
                //
            } else {
                $(
                    "#lumpia-collapseOne" +
                        "-" +
                        categoryItemKey +
                        "-" +
                        categoryIndexKey
                )
                    .parent()
                    .parent()
                    .find(".accordion-item")
                    .removeClass("green-border");
                $(
                    "#lumpia-collapseOne" +
                        "-" +
                        categoryItemKey +
                        "-" +
                        categoryIndexKey
                )
                    .parent()
                    .parent()
                    .find(".accordion-item")
                    .addClass("red-border");

                $(
                    "#success-svg-" + categoryItemKey + "-" + categoryIndexKey
                ).hide();
                $(
                    "#danger-svg-" + categoryItemKey + "-" + categoryIndexKey
                ).show();
            }

            // console.log($('#lumpia-collapseOne'+'-'+categoryItemKey+'-'+categoryIndexKey).parent().parent().find('.accordion-item').html());
        },
        error: function (xhr, status, error) {
            console.error("An error occurred while storing the User ID.");
        },
    });
}
