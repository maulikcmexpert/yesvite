let eventData = {};
var total_activities = 0;
var category = 0;
var items = 0;
var activities = {};
var selected_co_host = '';
var selected_co_host_prefer_by = '';
var final_step = 1;

$(document).ready(function () {
    if (performance.navigation.type == performance.navigation.TYPE_RELOAD) {
        //  alert(design);
        $('.user_choice').prop('checked',false);
        $('#YesviteUserAll').html('');
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

    $('.search_user').val('');
});

$(document).on('focus','.inputText',function(){
    $(this).next().addClass('floatingfocus');
})

$(document).on('blur','.inputText',function(){
    if($(this).val() ==''){
        $(this).next().removeClass('floatingfocus');
    }
})
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

$(document).on('click','.timepicker', function(){
   datepicker();
})
datepicker();
function datepicker() {

    
    
    $(".timepicker").datetimepicker({
        format: "LT",
        icons: {
            up: "fa fa-chevron-up",
            down: "fa fa-chevron-down",
        },
        useCurrent: false, 
        ignoreReadonly: true,
        stepping: 15, // Set stepping to 15 minutes
        // defaultDate: now
    });
}

// flatpickr(".event_time", {
//     enableTime: true,
//     noCalendar: true,
//     dateFormat: "h:i K", // Format with AM/PM
//     time_24hr: false, // 12-hour format with AM/PM
//     minuteIncrement: 15, // Set 15-minute intervals
// });

function rsvp_by_date(start_time) {
    var adjustedStartTime = moment(start_time).subtract(1, 'days');

    $('#rsvp-by-date').daterangepicker({
        singleDatePicker: true,
        autoUpdateInput: false,  
        //   showDropdowns: true,
        minYear: 1901,
        maxDate: adjustedStartTime, 
        minDate: moment().add(0, 'days'), 
        locale: {
            format: 'MM-DD-YYYY'  // Set the desired format
        },
        maxYear: parseInt(moment().format('YYYY'),10)
        }, function(start, end, label) {
        //   var years = moment().diff(start, 'years');
        //   alert("You are " + years + " years old!");
    });
    $('#rsvp-by-date').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('MM-DD-YYYY'));
        $('#rsvp-by-date').next().addClass('floatingfocus');
    });
    $('#rsvp-by-date').on('hide.daterangepicker', function(ev, picker) {
        if (picker.startDate.isValid()) {
            $(this).val(picker.startDate.format('MM-DD-YYYY'));
            $('#rsvp-by-date').next().addClass('floatingfocus');
        }
    });
}

$(function() {
    var current_event_date = $("#event-date").val();

    $('#rsvp-by-date').daterangepicker({
      singleDatePicker: true,
      autoUpdateInput: false,  
    //   showDropdowns: true,
      minYear: 1901,
      maxDate: current_event_date, 
      minDate: moment().add(0, 'days'), 
      locale: {
        // format: 'YYYY-MM-DD'  // Set the desired format
        format: 'MM-DD-YYYY'  // Set the desired format
    },
      maxYear: parseInt(moment().format('YYYY'),10)
    }, function(start, end, label) {
    //   var years = moment().diff(start, 'years');
    //   alert("You are " + years + " years old!");
    });
    $('#rsvp-by-date').on('apply.daterangepicker', function(ev, picker) {
        // $(this).val(picker.startDate.format('YYYY-MM-DD'));
        $(this).val(picker.startDate.format('MM-DD-YYYY'));
        $('#rsvp-by-date').next().addClass('floatingfocus');
    });
    $('#rsvp-by-date').on('hide.daterangepicker', function(ev, picker) {
        if (picker.startDate.isValid()) {
            // $(this).val(picker.startDate.format('YYYY-MM-DD'));
            $(this).val(picker.startDate.format('MM-DD-YYYY'));
            $('#rsvp-by-date').next().addClass('floatingfocus');
        }
    });
  });

$(function () {
    var selectedDates = new Set();

    $("#event-date").daterangepicker(
        {
            autoUpdateInput: false,
            locale: {
                format: "MM/DD/YYYY",
            },
            showDropdowns: false,
            startDate: moment().startOf("month"),
            endDate: moment().endOf("month"),
            minDate: moment().add(1, 'days'),  
            // alwaysShowCalendars: true, // Keep the calendar visible
            maxSpan: { days: 2 },
        },
        
        function (start, end, label) {
            
            
            selectedDates.clear();
            // selectedDates.add(start.format("YYYY-MM-DD"));
            // selectedDates.add(end.format("YYYY-MM-DD"));
            // var eventDate = start.format("YYYY-MM-DD") + " To " + end.format("YYYY-MM-DD")
            selectedDates.add(start.format("MM-DD-YYYY"));
            selectedDates.add(end.format("MM-DD-YYYY"));
            var eventDate = start.format("MM-DD-YYYY") + " To " + end.format("MM-DD-YYYY")
            rsvp_by_date(start.format("MM-DD-YYYY"));
            if(start.format("MM-DD-YYYY") == end.format("MM-DD-YYYY")){
                eventDate = end.format("MM-DD-YYYY");
            }
            $("#event-date").val(eventDate);

            $("#event-date").val(eventDate).trigger('change');

            $(".activity_bar").children().not(".toggle-wrp").remove();
            $('#schedule').prop("checked",false);
            $('.add-activity-schedule').hide();
            // alert();
            $("#end_time").prop("checked", false);
            $("#end-time").val("");
            $("#start-time").val("");
            $(".end_time").css("display", "none");
            if (selectedDates.size > 0) {
                var activities = {};
                eventData.activity = {};
                var total_activities = 0;
                set_activity_html(selectedDates);
            }
        }
    );

    $("#event-date").on('apply.daterangepicker', function(ev, picker) {
        picker.show();
        $('#event-date').next().addClass('floatingfocus');
    });
    $('#event-date').on('hide.daterangepicker', function(ev, picker) {
        picker.show();
        $('#event-date').next().addClass('floatingfocus');
    });
   
});

$("#schedule").on("change", function () {
    var eventDate = $("#event-date").val();
    var activities = {};
    eventData.activity = {};
    var total_activities = 0;
    $('.step_1_activity').html('<span><i class="fa-solid fa-triangle-exclamation"></i></span>Setup activity schedule');
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
            $(".activity_bar").html('');
            $(".add-activity-schedule").hide();
            // $(".ac-end-time").hide();
        }
    } else {
        
        $("#schedule").prop("checked", false);
        toastr.error("please select event date.");
    }
});


function set_activity_html(selectedDates) {
    $(".activity_bar").html('');
    var activities = {};
    eventData.activity = {};
    total_activities = 0;
    var sortedDates = [...selectedDates].sort();
    var startDate = moment(sortedDates[0]);
    var endDate = moment(
        sortedDates.length === 2 ? sortedDates[1] : sortedDates[0]
    );
    var i = 0;
    var start_time = $('#start-time').val();
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
            <h3>${startDate.format("dddd, MMMM D, YYYY")}</h3>
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
                            </div>
                            `
                        : ""
                }
                <div class="accordion" id="accordionExample">
                    <div class="accordion-item">
                        <div class="accordion-header">
                            <button
                                class="accordion-button collapsed"
                                type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#collapseOne${dateID}"
                            >
                                <div>Activities <span class="total_activity-${dateID}">(0)</span></div>
                                <i class="fa-solid fa-angle-down"></i>
                            </button>
                            <div class="accordion-button-icons add_more_activity" data-activity="add_activity_${i}" data-id="${dateID}">
                                <i class="fa-solid fa-circle-plus"></i>
                            </div>
                        </div>
                        <div
                            id="collapseOne${dateID}"
                            class="accordion-collapse collapse"
                            data-bs-parent="#accordionExample"
                        >
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
        datepicker();
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
    console.log(total_activities);
    
});
var numItems = 0;

$(document).on('click','.create_new_event',function(){
    // alert();
    toggleSidebar('sidebar_change_plan_create');
});

$(document).on('click','.create_event_with_plan',function(){
    // toggleSidebar('sidebar_change_plan_create');
    // $('input[name="plan_check"]:checked').each(function () {
    //     var plan = $(this).data('plan');
    //     eventData.plan_selected=plan;
    //     alert(plan);
    //     window.location.href="event";
    // });

    var checkedPlan = $('input[name="plan_check"]:checked'); // Select the checked one
    if (checkedPlan.length) { // Ensure there is a checked checkbox
        var plan = checkedPlan.data('plan');
        eventData.plan_selected = plan;
        // alert(plan);
        window.location.href = "event";
    }
    

});

$(document).on("click", ".add_more_activity", function (e) {
    var start_time = $('#ac-start-time').val();
    var firstActivity = $(this).data('activity');

    if((start_time == null || start_time == "") && firstActivity == 'add_activity_1' ){
        e.preventDefault();
        toastr.error('Plaese select start time');
        return;
    }
    $(this).parent().find('.accordion-button').addClass('accordian_open');
    $(this).prop("disabled", true);

    var newClass = $(this).parent().next().find(".new_activity").data("id");
    var count = $("." + newClass).length;
    if (count === null || count === undefined) {
        count = 1;
    } else {
        count++;
    }

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

$("#end_time").on("change", function () {
    if ($(this).is(":checked")) {
        $(".end_time").show();
        $(".ac-end-time").show();
    } else {
        $(".end_time").hide();
        $(".ac-end-time").hide();
    }
});

$("#rsvp_by_date").on("change", function () {
    if ($(this).is(":checked")) {
        $(".rsvp_by_date").show();
    } else {
        $(".rsvp_by_date").hide();
    }
});




$("#allow_for_1_more").on("change", function () {
    // alert()
    if ($(this).is(":checked")) {
        // $(".hidersvp").hide();
        $('.allow_for_limit_count').html(`<div class="d-flex align-items-center add_new_limit">
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

$('.allow_limit_toggle').on('click',function(){
    if(eventData.allow_limit_count == undefined || eventData.allow_limit_count == ''){
        $('#allow_limit_count').val(0);
    }else{
        $('#allow_limit_count').val(eventData.allow_limit_count);
    }  
    toggleSidebar('sidebar_allow_limit');
})

$("#potluck").on("change", function () {
    // alert()
    if ($(this).is(":checked")) {
        $(".potluck").show();
    } else {
       
        var category_count = $('#category_count').val();
        console.log(category);
        
        if(category == 0){
            $(".potluck").hide();
        }else{
            $(".delete_potluck_title").text('Potluck data will be deleted');
            $(".delete_potluck_text").text('All category and item data that you have entered will be lost if you turn this off');
            $(".delete_category_text").text('');
            $("#delete_potluck_category_id").val('all_potluck');
            $('#deleteModal_potluck').modal('show');
        }
    }
});

$(document).on("click", ".potluck_cancel", function () {
    $('#potluck').prop('checked',true);
});
$(document).on("click", ".group_toggle_close_btn", function () {
    $('#group_toggle_search').val('');
    groupToggleSearch();
});

$("#gift_registry").on("change", function () {
    // alert()
    eventData.gift_registry_data = {};
    if ($(this).is(":checked")) {
        // $(".hidersvp").hide();
        $('.add_gift_registry_count').html(`<span class="me-3">
                                                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M17.1336 12.267L11.8002 2.66699C11.0836 1.37533 10.0919 0.666992 9.00023 0.666992C7.90856 0.666992 6.91689 1.37533 6.20023 2.66699L0.866892 12.267C0.191892 13.492 0.116892 14.667 0.658559 15.592C1.20023 16.517 2.26689 17.0253 3.66689 17.0253H14.3336C15.7336 17.0253 16.8002 16.517 17.3419 15.592C17.8836 14.667 17.8086 13.4837 17.1336 12.267ZM8.37523 6.50033C8.37523 6.15866 8.65856 5.87533 9.00023 5.87533C9.34189 5.87533 9.62523 6.15866 9.62523 6.50033V10.667C9.62523 11.0087 9.34189 11.292 9.00023 11.292C8.65856 11.292 8.37523 11.0087 8.37523 10.667V6.50033ZM9.59189 13.7587C9.55023 13.792 9.50856 13.8253 9.46689 13.8587C9.41689 13.892 9.36689 13.917 9.31689 13.9337C9.26689 13.9587 9.21689 13.9753 9.15856 13.9837C9.10856 13.992 9.05023 14.0003 9.00023 14.0003C8.95023 14.0003 8.89189 13.992 8.83356 13.9837C8.78356 13.9753 8.73356 13.9587 8.68356 13.9337C8.63356 13.917 8.58356 13.892 8.53356 13.8587C8.49189 13.8253 8.45023 13.792 8.40856 13.7587C8.25856 13.6003 8.16689 13.3837 8.16689 13.167C8.16689 12.9503 8.25856 12.7337 8.40856 12.5753C8.45023 12.542 8.49189 12.5087 8.53356 12.4753C8.58356 12.442 8.63356 12.417 8.68356 12.4003C8.73356 12.3753 8.78356 12.3587 8.83356 12.3503C8.94189 12.3253 9.05856 12.3253 9.15856 12.3503C9.21689 12.3587 9.26689 12.3753 9.31689 12.4003C9.36689 12.417 9.41689 12.442 9.46689 12.4753C9.50856 12.5087 9.55023 12.542 9.59189 12.5753C9.74189 12.7337 9.83356 12.9503 9.83356 13.167C9.83356 13.3837 9.74189 13.6003 9.59189 13.7587Z" fill="#E03137" />
                                                    </svg>
                                                </span>
                                                <h5>Add gift registry</h5>`);
        $("#giftDiv").show();
    } else {
        // delete_session('gift_registry_data');
        $("#giftDiv").hide();
    }
    savePage4Data();
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
$(document).on("click", 'input[name="email_invite[]"]', function (e) {
    var inviteCount = parseInt($('#currentInviteCount').val());

    if ($(this).is(':disabled')) {
        e.preventDefault(); 
        return; 
    }
    $("#loader").css('display','block');
    
    var userId = $(this).val();
    var isChecked = $(this).is(":checked");
    var email = $(this).data("email");
    // if(inviteCount > 15){
    //     e.preventDefault(); 
    //     return;
    // }

    if (isChecked == true || isChecked == "true") {
        $('input[name="email_invite[]"]').attr('disabled', true);
        // $(this).prop("disabled", true);
        $.ajax({
            url: base_url + "event/store_user_id",
            method: "POST",
            data: {
                user_id: userId,
                is_checked: isChecked,
                email: email,
                _token: $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (response) {

                var currentInviteCount = parseInt($('#currentInviteCount').val());
                currentInviteCount++;
                $('#currentInviteCount').val(currentInviteCount);
                if (response.is_duplicate == 1) {
                    $("#user_tel-" + userId).remove();
                }
                
                var total_guest = 0;
                var max_guest = 15;
                // if (total_guest == max_guest) {

                // } else {
                    $(".inivted_user_list").append(response.view);
                    guest_counter(0,15);
                    $('input[name="email_invite[]"]').prop('disabled', false);
                    // if(currentInviteCount >= 15){
                    //     $('.user_choice').prop('disabled',true);
                    // }
                    $("#loader").css('display','none');
                   
                // }
            },
            error: function (xhr, status, error) {},
        });
    } else {
        delete_invited_user(userId);
        $("#loader").css('display','none');
    }
});

function guest_counter(total_guest,max_guest){
    total_guest = $(".users-data.invited_user").length;
    $("#event_guest_count").text(total_guest + " Guests");
    $(".invite-count").text(total_guest);
    console.log(max_guest);
    
    var remainingCount = max_guest - total_guest;
    if(remainingCount < 0){
        $(".invite-left_d").text("Invites | 0 Left");
    }else{
        $(".invite-left_d").text("Invites | " + remainingCount + " Left");

    }
    // $(".invite-left_d").text(
    //     "Invites | " + remainingCount + " Left"
    // );
    $("#event_guest_left_count").val(remainingCount);
}

function delete_invited_user(userId) {
    $.ajax({
        url: base_url + "event/delete_user_id",
        method: "POST",
        data: {
            user_id: userId,
            _token: $('meta[name="csrf-token"]').attr("content"), // Adding CSRF token
        },
        success: function (response) {
            var currentInviteCount = parseInt($('#currentInviteCount').val());
            currentInviteCount--;
            $('#currentInviteCount').val(currentInviteCount);

            // if(currentInviteCount < 15){
            //     $('.user_choice').prop('disabled',false);
            // }
            console.log("User ID deleted successfully.");
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

    if (isChecked == true || isChecked == "true") {
        $.ajax({
            url: base_url + "event/store_user_id",
            method: "POST",
            data: {
                user_id: userId,
                is_checked: isChecked,
                mobile: mobile,
                _token: $('meta[name="csrf-token"]').attr("content"), // Adding CSRF token
            },
            success: function (response) {

                var currentInviteCount = $('#currentInviteCount').val();
                currentInviteCount++;
                $('#currentInviteCount').val(currentInviteCount);

                if (response.is_duplicate == 1) {
                    $("#user-" + userId).remove();
                }
                $(".inivted_user_list").append(response.view);
                
                total_guest = $(".users-data.invited_user").length;
                $("#event_guest_count").text(total_guest + " Guests");
                $(".invite-count").text(total_guest);
                
                var max_guest = 15;
                var remainingCount = max_guest - total_guest;
                // if(currentInviteCount >= 15){
                //     $('.user_choice').prop('disabled',true);
                // }
                if(remainingCount < 0){
                    $(".invite-left_d").text("Invites | 0 Left");
                }else{
                    $(".invite-left_d").text("Invites | " + remainingCount + " Left");

                }
                $("#event_guest_left_count").val(remainingCount);
            },
            error: function (xhr, status, error) {},
        });
    } else {
        $.ajax({
            url: base_url + "event/delete_user_id",
            method: "POST",
            data: {
                user_id: userId,
                _token: $('meta[name="csrf-token"]').attr("content"), // Adding CSRF token
            },
            success: function (response) {
                var currentInviteCount = $('#currentInviteCount').val();
                currentInviteCount--;
                $('#currentInviteCount').val(currentInviteCount);

                // if(currentInviteCount < 15){
                //     $('.user_choice').prop('disabled',false);
                // }

                $('#user_tel-'+userId).remove();
                total_guest = $(".users-data.invited_user").length;
                $("#event_guest_count").text(total_guest + " Guests");
                $(".invite-count").text(total_guest);

                var max_guest = 15;
                var remainingCount = max_guest - total_guest;

                if(remainingCount < 0){
                    $(".invite-left_d").text("Invites | 0 Left");
                }else{
                    $(".invite-left_d").text("Invites | " + remainingCount + " Left");

                }
                $("#event_guest_left_count").val(remainingCount);
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
// $("#add_category_btn").click(function () {
//     // e.preventDefault();
//     var categoryName = $("#categoryName").val().trim();
//     var categoryQuantity = parseInt($("#categoryquantity").val().trim());

//     // console.log(categoryQuantity);
//     // $('#errorLabel').text('');
//     if (categoryName == "") {
//         $("#categoryname")
//             .text("Category Name cannot be empty.")
//             .css("color", "red");
//     } else if (categoryQuantity == "" || categoryQuantity <= 0) {
//         $("#quantityLabel")
//             .text("Please enter the quantity")
//             .css("color", "red");
//     } else {
//         var categoryName = $("#categoryName").val();

//         var quantity = $("#categoryquantity").val();
//         sessionStorage.setItem("categoryName", categoryName);
//         sessionStorage.setItem("quantity", quantity);

//         // if (sessionStorage.getItem("quantity") !== null) {
//         //     quantity = parseInt(sessionStorage.getItem("quantity"));
//         //     $('.quantity-input').val(quantity);
//         // } else {
//         //     sessionStorage.setItem(".quantity-input", quantity);
//         // }

//         // if (sessionStorage.getItem("categoryName") !== null) {
//         //     quantity = parseInt(sessionStorage.getItem("categoryName"));
//         //     $('#categoryName').val(quantity);
//         // } else {
//         //     sessionStorage.setItem("#categoryName", quantity);
//         // }

//         if (categoryName.trim() !== "") {
//             var potluck_category = [];
//             potluck_category.push(categoryName);

//             $("#potluck_category").val(potluck_category.join(","));

//             var categoryItem = `<li class="list-group-item categorylist">
//             ${categoryName}
//             <button type="button" class="btn btn-sm btn-secondary float-end addSubItemButton" id="addSubItemButton">Add Sub-Item</button>
//             <input type="hidden" id="quantity" value=${quantity}>
//             <ul class="list-group mt-2 subItemList"></ul>
//         </li>`;
//             $("#categoryList").append(categoryItem);
//             $("#categoryName").val("");
//             $("#categoryModal").modal("hide");
//         } else {
//             alert("Category name cannot be empty.");
//         }
//     }
// });

var potluck = [];
var potluckkey = -1;
var activePotluck = 0;

function setPotluckActivekey(key, name) {
    $("#category_index").val(key);
    $('.sub-cat-pot').text('0/30');
    $(".category_heading").text("Add Item Under: " + name);
    activePotluck = key;
}

$(document).on("click", ".add_category_btn", function () {
    var categoryName = $("#categoryName").val();
    var categoryQuantity = $("#category_quantity").val();

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

    potluckkey++;
    potluck[potluckkey] = categoryName;
    $.ajax({
        url: base_url + "event/category_session",
        method: "POST",
        data: {
            category_name: categoryName,
            potluckkey: potluckkey,
            categoryQuantity: categoryQuantity,
            _token: $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (response) {
            if (response.status == 1) {
                $("#categoryName").val("");
                $("#categoryNameError").text("");
                $("#category_quantity_error").css("display", "none");
                $("#category_quantity").val(1);
                $("#hidden_category_name").val(categoryName);
                $("#hidden_category_quantity").val(categoryQuantity);
                $(".potluck-category").append(response.view);
                toggleSidebar("sidebar_potluck");
                category++;
                $('#category_count').val(category);
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

$(document).on("click", ".add_category_item_btn", function () {
    var category_index = $("#category_index").val();
    var category_name = $("#hidden_category_name").val();
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
            _token: $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (response) {
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
            if(response.total_item == 0){
                var svg =
                    '<svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M7.00016 0.333984C3.32683 0.333984 0.333496 3.32732 0.333496 7.00065C0.333496 10.674 3.32683 13.6673 7.00016 13.6673C10.6735 13.6673 13.6668 10.674 13.6668 7.00065C13.6668 3.32732 10.6735 0.333984 7.00016 0.333984ZM10.1868 5.46732L6.40683 9.24732C6.3135 9.34065 6.18683 9.39398 6.0535 9.39398C5.92016 9.39398 5.7935 9.34065 5.70016 9.24732L3.8135 7.36065C3.62016 7.16732 3.62016 6.84732 3.8135 6.65398C4.00683 6.46065 4.32683 6.46065 4.52016 6.65398L6.0535 8.18732L9.48016 4.76065C9.6735 4.56732 9.9935 4.56732 10.1868 4.76065C10.3802 4.95398 10.3802 5.26732 10.1868 5.46732Z" fill="#23AA26"></path></svg>';
                $(".missing-category-svg-" + category_index).html(svg);
            }else{
                var svg =
                    '<svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M13.5067 9.61399L9.23998 1.93398C8.66665 0.900651 7.87332 0.333984 6.99998 0.333984C6.12665 0.333984 5.33332 0.900651 4.75998 1.93398L0.493318 9.61399C-0.0466816 10.594 -0.106682 11.534 0.326652 12.274C0.759985 13.014 1.61332 13.4207 2.73332 13.4207H11.2667C12.3867 13.4207 13.24 13.014 13.6733 12.274C14.1067 11.534 14.0467 10.5873 13.5067 9.61399ZM6.49998 5.00065C6.49998 4.72732 6.72665 4.50065 6.99998 4.50065C7.27332 4.50065 7.49998 4.72732 7.49998 5.00065V8.33398C7.49998 8.60732 7.27332 8.83398 6.99998 8.83398C6.72665 8.83398 6.49998 8.60732 6.49998 8.33398V5.00065ZM7.47332 10.8073C7.43998 10.834 7.40665 10.8607 7.37332 10.8873C7.33332 10.914 7.29332 10.934 7.25332 10.9473C7.21332 10.9673 7.17332 10.9807 7.12665 10.9873C7.08665 10.994 7.03998 11.0007 6.99998 11.0007C6.95998 11.0007 6.91332 10.994 6.86665 10.9873C6.82665 10.9807 6.78665 10.9673 6.74665 10.9473C6.70665 10.934 6.66665 10.914 6.62665 10.8873C6.59332 10.8607 6.55998 10.834 6.52665 10.8073C6.40665 10.6807 6.33332 10.5073 6.33332 10.334C6.33332 10.1607 6.40665 9.98732 6.52665 9.86065C6.55998 9.83399 6.59332 9.80732 6.62665 9.78065C6.66665 9.75398 6.70665 9.73398 6.74665 9.72065C6.78665 9.70065 6.82665 9.68732 6.86665 9.68065C6.95332 9.66065 7.04665 9.66065 7.12665 9.68065C7.17332 9.68732 7.21332 9.70065 7.25332 9.72065C7.29332 9.73398 7.33332 9.75398 7.37332 9.78065C7.40665 9.80732 7.43998 9.83399 7.47332 9.86065C7.59332 9.98732 7.66665 10.1607 7.66665 10.334C7.66665 10.5073 7.59332 10.6807 7.47332 10.8073Z" fill="#F73C71" /></svg>';
                $(".missing-category-svg-" + category_index).html(svg);
            }
            $("#missing-category-" + category_index).text(response.total_item);
            $(".missing-category-h6-" + category_index).show();

            items++;
            potluck_cateogry_item_count();
            toggleSidebar("sidebar_potluck");
            if (response.qty == 1) {
                $("#potluck-" + category_index).hide();
            }

            var total_self_bring = parseInt($('.total-self-bring-'+category_index).text());
            total_self_bring = total_self_bring + parseInt(self_bringQuantity);
            $('.total-self-bring-'+category_index).text(total_self_bring);
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

$(document).on("blur", "#start-time", function () {
    var s_t = $(this).val();
    var start_time = convertTo24Hour($(this).val());
    var end_time = convertTo24Hour($("#end-time").val());
    $("#ac-start-time").val(s_t);

    var activity_start = $("#firstActivityTime").val();
    var activity_end = $("#LastEndTime").val();

    if (activity_start == activity_end) {
        if (end_time != "") {
            if (start_time > end_time) {
                toastr.error("please select start time before end time");
                $(this).val("");
            }
        }
    }
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
        toastr.error("youuuu can`tttttttt");
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
//         startDate.setMinutes(startDate.getMinutes() + 30);
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

$(document).on("blur", 'input[name="activity-end-time[]"]', function () {

    if (!blurExecutedEndTime) {
        blurExecutedEndTime = true;

        var newEndTime = convertTo24Hour($(this).val());
        var newStartTime = convertTo24Hour(
            $(this)
                .closest(".activity-main-wrp")
                .find('input[name="activity-start-time[]"]')
                .val()
        );

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
            // startDate.setMinutes(startDate.getMinutes() + 30);
            // var hours = startDate.getHours().toString().padStart(2, "0");
            // var minutes = startDate.getMinutes().toString().padStart(2, "0");
            // var newEndTimeWith30Min = `${hours}:${minutes}`;

            // $(this).val(newEndTimeWith30Min);

            var timeParts = newStartTime.split(":");
            var startDate = new Date();
            startDate.setHours(parseInt(timeParts[0]));
            startDate.setMinutes(parseInt(timeParts[1]));
            startDate.setMinutes(startDate.getMinutes() + 30);
            var hours = startDate.getHours();
            var minutes = startDate.getMinutes().toString().padStart(2, "0");

            // Convert to 12-hour format
            var period = hours >= 12 ? "PM" : "AM";
            hours = hours % 12 || 12; // Convert hour '0' to '12' in 12-hour format
            hours = hours.toString().padStart(2, "0");
            var newEndTimeWith30Min = `${hours}:${minutes} ${period}`;
            $(this).val(newEndTimeWith30Min);
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

                $("#" + lastActivityTime)
                    .children()
                    .find(".activity_end_time")
                    .each(function (index) {
                        if (lastActivityNode - 1 == index) {
                            $(this).val("");
                        }
                    });
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

        $(this)
            .closest(".activity-main-wrp")
            .next()
            .find('input[name="activity-start-time[]"]')
            .val(newEndTime);

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
            toastr.error("activity can not start before event");
            $("#" + firstActivityTime)
                .children()
                .find(".activity_start_time")
                .val("");
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
            startDate.setMinutes(startDate.getMinutes() + 30);
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


$(document).on("click", "#save_activity_schedule", function () {
    var start_time = $("#ac-start-time").val();
    var end_time = $("#ac-end-time").val();

    $("#start-time").val(start_time);
    $("#end-time").val(end_time);
    var isValid = 0;
    $(".accordion-body.new_activity").each(function () {
        var dataId = $(this).data("id");
        activities[dataId] = [];
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

                $("#desc-error-" + id).text("");
                $("#start-error-" + id).text("");
                $("#end-error-" + id).text("");

                if (description == "") {
                    $("#desc-error-" + id).text("Description is required").css('color','red');
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
            });
        // toggleSidebar();
    });
    if (isValid == 0) {
        if(total_activities >= 1){
            if(total_activities == 1 ){
                $('.step_1_activity').text(total_activities + ' Activity');
            }else{
                $('.step_1_activity').text(total_activities + ' Activities');
            }
        }else{
            $('.step_1_activity').html('<span><i class="fa-solid fa-triangle-exclamation"></i></span>Setup activity schedule');
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

    $("#loader").css('display','block');
    savePage3Data();
    checkbox_count();
    $("#loader").css('display','none');

});


$(document).on('click','#next_design',function() {
    // alert();
    savePage1Data('next');
    // console.log(eventData);
    // 
})

$(document).on("click", "#close_createEvent", function () {

    var event_type = $("#event-type").val();
    var event_name = $("#event-name").val();
    var event_date = $("#event-date").val();

    if (event_type == "") {
        $("#deleteModal").modal("show");
        // confirm('Event type is empty. Are you sure you want to proceed?')
        return;
    }
    if (event_name == "") {
        $("#deleteModal").modal("show");
        return;
    }
    if (event_date == "") {
        $("#deleteModal").modal("show");
        return;
    }

    $('#loader').css('display','block');

    if (event_type != "" && event_name != "" && event_date != "") {
        let text = $('.current_step').text();
        let firstLetter = text.split(' ')[0]; 
        if(firstLetter == '1'){
            // savePage1Data('close');
            var event_type = $("#event-type").val();
            var event_name = $("#event-name").val();
            var hostedby = $("#hostedby").val();
            var event_date = $("#event-date").val();
            var start_time = $("#start-time").val();
            var start_time_zone =  $('#start-time-zone').val();
            var schedule = $('#schedule').is(":checked");
            var end_time = $("#end_time").is(":checked");
            var rsvp_by_date_set = $("#rsvp_by_date").is(":checked");
            var end_time_zone =  $('#end-time-zone').val();
            var address_2 = $("#address2").val();
            var address1 = $("#address1").val();
            var city = $("#city").val();
            var state = $("#state").val();
            var zipcode = $("#zipcode").val();
            var id = $("#id").val();
            var rsvp_by_date = $("#rsvp-by-date").val();
            var event_id = $("#event_id").val();
            var description = $("#description").val();
            var message_to_guests = $("#message_to_guests").val();
            var latitude = $("#latitude").val();
            var longitude = $("#longitude").val();
            
            var desgin_selected= eventData.desgin_selected;

            var events_schedule = '0';
            var rsvp_end_time_set = '0';

            if(rsvp_by_date_set){
                rsvp_by_date_set = '1';
            }else{
                rsvp_by_date_set = '0';
            }
            if(schedule){
                events_schedule = '1';
            }
            var rsvp_end_time = '';
            if(end_time){
                rsvp_end_time = $('#end-time').val();
                rsvp_end_time_set = '1';
            }

            // eventData = {
                eventData.event_type= event_type;
                eventData.event_name= event_name;
                eventData.hosted_by= hostedby;
                eventData.event_date= event_date;
                eventData.start_time= start_time;
                eventData.rsvp_start_timezone=start_time_zone;
                eventData.events_schedule=events_schedule;
                eventData.activity= activities;
                eventData.rsvp_end_time_set=rsvp_end_time_set;
                eventData.rsvp_end_time=rsvp_end_time;
                eventData.rsvp_end_timezone=end_time_zone;
                eventData.rsvp_by_date_set=rsvp_by_date_set;
                eventData.rsvp_by_date=rsvp_by_date;
                eventData.event_location=description;
                eventData.address1= address1;
                eventData.address_2= address_2;
                eventData.city= city;
                eventData.state= state;
                eventData.zipcode= zipcode;
                eventData.message_to_guests=message_to_guests;
                eventData.event_id=event_id;
                eventData.desgin_selected=desgin_selected;
                eventData.latitude=latitude;
                eventData.longitude=longitude;
            // };
            eventData.step = firstLetter;
            $(".step_2").hide();
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
                if(response==1){ 
                    window.location.href="profile";
                    $('#loader').css('display','none');
                    toastr.success('Event Saved as Draft');
                }

            },
            error: function (xhr, status, error) {
                console.log("AJAX error: " + error);
            },
        });
    }

   
});

function focus_timeOut(type){
    setTimeout(function() {
        $("#"+type).focus();
    }, 100);
}

function savePage1Data(close = null) {

    var event_type = $("#event-type").val();
    var event_name = $("#event-name").val();
    var hostedby = $("#hostedby").val();
    var event_date = $("#event-date").val();
    var start_time = $("#start-time").val();
    var start_time_zone =  $('#start-time-zone').val();
    var end_time_zone =  $('#end-time-zone').val();
    var schedule = $('#schedule').is(":checked");
    var end_time = $("#end_time").is(":checked");
    var rsvp_by_date_set = $('#rsvp_by_date').is(':checked');
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

    var events_schedule = '0';
    var rsvp_end_time_set = '0';

    // if(rsvp_by_date_set){
    //     rsvp_by_date_set = '1';
    // }else{
    //     rsvp_by_date_set = '0';
    // }
    if(schedule){
        events_schedule = '1';
    }
    var rsvp_end_time = '';
    if(end_time){
        rsvp_end_time = $('#end-time').val();
        rsvp_end_time_set = '1';
        if(rsvp_end_time ==''){
            $('#end-time-error').css("display", "block")
                .css("color", "red")
                .text("End Time: Please select end time");
                focus_timeOut('end-time');
                return;
        }else{
            $("#end-time-error").css("display", "none").text("End Time: Please select end time");
        }
    }


    var rsvp_by_date = '';
    if(rsvp_by_date_set){
        rsvp_by_date = $('#rsvp-by-date').val();
        if (rsvp_by_date == "") {
            $("#event-rsvpby-error")
                .css("display", "block")
                .css("color", "red")
                .text("RSVP by Date : Please select RSVP date");
            return;
        } else {
            $("#event-rsvpby-error").css("display", "none");
        }
        rsvp_by_date_set = '1';
    }else{
        rsvp_by_date_set = '0';
    }
    
    if ($('#rsvp_by_date').is(':checked')) {
        rsvp_by_date = $('#rsvp-by-date').val();
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
            focus_timeOut('event-type');
        return;
    } else {
        $("#event-type-error").css("display", "none");
    }
    if (event_name == "") {
        $("#event-name-error")
            .css("display", "block")
            .css("color", "red")
            .text("Please enter event name");
            focus_timeOut('event-name');
        return;
    } else {
        $("#event-name-error").css("display", "none");
    }
    if (hostedby == "") {
        $("#event-host-error")
            .css("display", "block")
            .css("color", "red")
            .text("Please enter event host name");
            focus_timeOut('hostedby');
        return;
    } else {
        $("#event-host-error").css("display", "none");
    }
    if (event_date == "") {
        $("#event-date-error")
            .css("display", "block")
            .css("color", "red")
            .text("Event Date: Please select an event date");
            focus_timeOut('event-date');
        return;
    } else {
        $("#event-date-error").css("display", "none");
    }
    if (start_time == "") {
        $("#event-start_time-error")
            .css("display", "block")
            .css("color", "red")
            .text("Start Time: Please select start time");
            focus_timeOut('start-time');
        return;
    } else {
        $("#event-start_time-error").css("display", "none");
    }
    if (address1 == "") {
        $("#event-address1-error")
            .css("display", "block")
            .css("color", "red")
            .text("Please enter address1");
            focus_timeOut('address1');
        return;
    } else {
        $("#event-address1-error").css("display", "none");
    }
    if (city == "") {
        $("#event-city-error")
            .css("display", "block")
            .css("color", "red")
            .text("Please enter city");
            focus_timeOut('city');
        return;
    } else {
        $("#event-city-error").css("display", "none");
    }
    if (state == "") {
        $("#event-state-error")
            .css("display", "block")
            .css("color", "red")
            .text("Please enter state");
            focus_timeOut('state');
        return;
    } else {
        $("#event-state-error").css("display", "none");
    }
    if (zipcode == "") {
        $("#event-zipcode-error")
            .css("display", "block")
            .css("color", "red")
            .text("Please enter zipcode");
            focus_timeOut('zipcode');
        return;
    } else {
        $("#event-zipcode-error").css("display", "none");
    }
    

    if (
        event_type != "" &&
        event_name != "" &&
        hostedby != "" &&
        event_date != "" &&
        start_time != "" &&
        address1 != "" &&
        city != "" &&
        state != "" &&
        zipcode != ""
    ) {
        if(rsvp_end_time_set == '1' && start_time_zone != end_time_zone){
            $('#end-time-zone').focus();
            $('#end-time-zone-error').text('End Time zone : Please select same start time zone and end time zone')
                .css("display", "block")
                .css("color", "red");
            return;
        }
        // eventData = {
            eventData.event_type= event_type;
            eventData.event_name= event_name;
            eventData.hosted_by= hostedby;
            eventData.event_date= event_date;
            eventData.rsvp_by_date_set=rsvp_by_date_set;
            eventData.rsvp_by_date=rsvp_by_date;
            eventData.start_time= start_time;
            eventData.start_time_zone=start_time_zone;
            eventData.rsvp_end_time_set=rsvp_end_time_set;
            eventData.rsvp_end_time=rsvp_end_time;
            eventData.rsvp_end_timezone=end_time_zone;
            eventData.event_location=description;
            eventData.address1= address1;
            eventData.address_2= address_2;
            eventData.state= state;
            eventData.zipcode= zipcode;
            eventData.city= city;
            eventData.message_to_guests=message_to_guests;
            eventData.events_schedule=events_schedule;
            eventData.longitude=longitude;
            eventData.latitude=latitude;
            // activity: activities,
        // };
        // alert();
        let text = $('.current_step').text();
        let firstLetter = text.split(' ')[0]; 
        eventData.step = firstLetter;
        var date = new Date(event_date);
        var formattedDate = date.toLocaleDateString("en-US", {
            month: "long",
            day: "numeric",
        });
        
        var formattedTime = convertTo12HourFormat(start_time);
        if(close == 'next'){
            $(".step_1").hide();
            handleActiveClass('.li_design');
        }
        // alert(description);
        // $(".titlename").text(hostedby);
        // $(".event_name").text(event_name);
        // $(".event_date").text(formattedDate);
        // $(".event_address").text(description);
        // $(".event_time").text(formattedTime);
        $(".step_2").show();
        $('.event_create_percent').text('50%');
        $('.current_step').text('2 of 4');
        if(final_step == 1){
            final_step = 2;
        }
        console.log(eventData);

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

function savePage3Data() {
    // let invited_user_ids = [];
    var checkedCheckbox = parseInt($('.invite-count').text());

    if (checkedCheckbox == 0) {
        toastr.error("Please select invited user");
        return;
    }

    // eventData.invited_user_ids = invited_user_ids;

    // console.log(eventData);

    // $.ajax({
    //     url: base_url + "event/get_all_invited_guest",

    //     type: "POST",
    //     headers: {
    //         "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    //     },

    //     success: function (response) {
            
            $(".list_all_invited_user").empty();
            // $(".list_all_invited_user").append(response);
            $("step_1").hide();
            $(".step_2").hide();
            $(".step_3").hide();
            handleActiveClass('.li_setting');
            $('.event_create_percent').text('99%');
            $('.current_step').text('4 of 4');
            $(".step_4").show();
            if(final_step == 3){
                final_step = 4;
            }
            eventData.step = '4';
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
        $('.add_new_thankyou_card').html(`<span class="me-3">
                <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M17.1336 12.267L11.8002 2.66699C11.0836 1.37533 10.0919 0.666992 9.00023 0.666992C7.90856 0.666992 6.91689 1.37533 6.20023 2.66699L0.866892 12.267C0.191892 13.492 0.116892 14.667 0.658559 15.592C1.20023 16.517 2.26689 17.0253 3.66689 17.0253H14.3336C15.7336 17.0253 16.8002 16.517 17.3419 15.592C17.8836 14.667 17.8086 13.4837 17.1336 12.267ZM8.37523 6.50033C8.37523 6.15866 8.65856 5.87533 9.00023 5.87533C9.34189 5.87533 9.62523 6.15866 9.62523 6.50033V10.667C9.62523 11.0087 9.34189 11.292 9.00023 11.292C8.65856 11.292 8.37523 11.0087 8.37523 10.667V6.50033ZM9.59189 13.7587C9.55023 13.792 9.50856 13.8253 9.46689 13.8587C9.41689 13.892 9.36689 13.917 9.31689 13.9337C9.26689 13.9587 9.21689 13.9753 9.15856 13.9837C9.10856 13.992 9.05023 14.0003 9.00023 14.0003C8.95023 14.0003 8.89189 13.992 8.83356 13.9837C8.78356 13.9753 8.73356 13.9587 8.68356 13.9337C8.63356 13.917 8.58356 13.892 8.53356 13.8587C8.49189 13.8253 8.45023 13.792 8.40856 13.7587C8.25856 13.6003 8.16689 13.3837 8.16689 13.167C8.16689 12.9503 8.25856 12.7337 8.40856 12.5753C8.45023 12.542 8.49189 12.5087 8.53356 12.4753C8.58356 12.442 8.63356 12.417 8.68356 12.4003C8.73356 12.3753 8.78356 12.3587 8.83356 12.3503C8.94189 12.3253 9.05856 12.3253 9.15856 12.3503C9.21689 12.3587 9.26689 12.3753 9.31689 12.4003C9.36689 12.417 9.41689 12.442 9.46689 12.4753C9.50856 12.5087 9.55023 12.542 9.59189 12.5753C9.74189 12.7337 9.83356 12.9503 9.83356 13.167C9.83356 13.3837 9.74189 13.6003 9.59189 13.7587Z" fill="#E03137"/>
                </svg>
            </span>
            <h5>Select thank you card</h5>`);
        $(".thankyou_card").show();
        eventData.thank_you_card_id = null;
    } else {
        $('.add_new_thankyou_card').html(`<span class="me-3">
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
        $('.guest-contacts-wrp').css('display', 'none');
        eventData.add_co_host = "1";
        $(".add_co_host").show();

    } else {
        $('.guest-contacts-wrp').css('display', 'none');
        eventData.co_host = '';
        eventData.co_host_prefer_by = '';
        selected_co_host = '';
        selected_co_host_prefer_by = '';
        $(".add_co_host").css('display','none');
        $('.add_co_host').html(`<div class="d-flex align-items-center justify-content-between w-100">
            <div class="d-flex align-items-center add_new_co_host">
                <span class="me-3">
                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M17.1336 12.267L11.8002 2.66699C11.0836 1.37533 10.0919 0.666992 9.00023 0.666992C7.90856 0.666992 6.91689 1.37533 6.20023 2.66699L0.866892 12.267C0.191892 13.492 0.116892 14.667 0.658559 15.592C1.20023 16.517 2.26689 17.0253 3.66689 17.0253H14.3336C15.7336 17.0253 16.8002 16.517 17.3419 15.592C17.8836 14.667 17.8086 13.4837 17.1336 12.267ZM8.37523 6.50033C8.37523 6.15866 8.65856 5.87533 9.00023 5.87533C9.34189 5.87533 9.62523 6.15866 9.62523 6.50033V10.667C9.62523 11.0087 9.34189 11.292 9.00023 11.292C8.65856 11.292 8.37523 11.0087 8.37523 10.667V6.50033ZM9.59189 13.7587C9.55023 13.792 9.50856 13.8253 9.46689 13.8587C9.41689 13.892 9.36689 13.917 9.31689 13.9337C9.26689 13.9587 9.21689 13.9753 9.15856 13.9837C9.10856 13.992 9.05023 14.0003 9.00023 14.0003C8.95023 14.0003 8.89189 13.992 8.83356 13.9837C8.78356 13.9753 8.73356 13.9587 8.68356 13.9337C8.63356 13.917 8.58356 13.892 8.53356 13.8587C8.49189 13.8253 8.45023 13.792 8.40856 13.7587C8.25856 13.6003 8.16689 13.3837 8.16689 13.167C8.16689 12.9503 8.25856 12.7337 8.40856 12.5753C8.45023 12.542 8.49189 12.5087 8.53356 12.4753C8.58356 12.442 8.63356 12.417 8.68356 12.4003C8.73356 12.3753 8.78356 12.3587 8.83356 12.3503C8.94189 12.3253 9.05856 12.3253 9.15856 12.3503C9.21689 12.3587 9.26689 12.3753 9.31689 12.4003C9.36689 12.417 9.41689 12.442 9.46689 12.4753C9.50856 12.5087 9.55023 12.542 9.59189 12.5753C9.74189 12.7337 9.83356 12.9503 9.83356 13.167C9.83356 13.3837 9.74189 13.6003 9.59189 13.7587Z" fill="#E03137" />
                    </svg>
                </span>
                <h5>Select your co-host</h5>
            </div>
            <span>
                <svg width="9" height="16" viewBox="0 0 9 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M1.42505 14.6004L6.85838 9.16706C7.50005 8.52539 7.50005 7.47539 6.85838 6.83372L1.42505 1.40039" stroke="#94A3B8" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </span>
        </div>`);
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

    if ($("#kid_off_event").is(":checked")) {
        eventData.kid_off_event = "1";
    } else {
        eventData.kid_off_event = "0";
    }   
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
                $('.recipient-name-con').text('0/30');
            } else {
                var recipient_length = recipient_name.length;
                $("#recipient_name_error").text("");
                $('.recipient-name-con').text(recipient_length+'/30');
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
                    .text("Please enter group name")
                    .css("color", "red");
                $('.pot-cate-name').text('0/30');
            } else {
                cateLength = groupname.length;
                $("#categoryNameError").text("");
                $('.pot-cate-name').text(cateLength+'/30');
            }
            break;
        
        case "item_name":
            var groupname = input.value;
            if (groupname === "") {
                $("#item_name_error")
                    .text("Please enter description.")
                    .css("color", "red");
                $('.sub-cat-pot').text('0/30');
            } else {
                cateLength = groupname.length;
                $("#item_name_error").text("");
                $('.sub-cat-pot').text(cateLength+'/30');
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
    $('#loader').css('display','block');
    var url = $(this).data("url");
    // console.log(url);
    window.location.href = url;
});

function handleActiveClass(target) {
    $(".side-bar-list").removeClass("active");
    $(target).find(".side-bar-list").addClass("active");
}

function handleActivePlan(target) {
    $('.plans-wrap').removeClass('active');
    $('.plan_check').prop('checked', false);

    $(target).addClass('active');
    $(target).find('.plan_check').prop('checked', true); 
}
$(document).on("click", ".li_design", function () {
    console.log(eventData);
    
    if (
        eventData.event_type != "" &&
        eventData.event_name != "" &&
        eventData.hosted_by != "" &&
        eventData.event_date != "" &&
        eventData.start_time != "" &&
        eventData.address1 != "" &&
        eventData.city != "" &&
        eventData.state != "" &&
        eventData.zipcode != "" &&
        eventData.event_type != undefined &&
        eventData.event_name != undefined &&
        eventData.hosted_by != undefined &&
        eventData.event_date != undefined &&
        eventData.start_time != undefined &&
        eventData.address1 != undefined &&
        eventData.city != undefined &&
        eventData.state != undefined &&
        eventData.zipcode != undefined
    ) {
        $(".step_1").css("display", "none");
        $(".step_2").show();
        $(".step_3").css("display", "none");
        $(".step_4").css("display", "none");
        $(".step_final_checkout").css("display", "none");

        $('.event_create_percent').text('50%');
        $('.current_step').text('2 of 4');

        var subclass = ".side-bar-sub-list";
        handleActiveClass('.li_design');
    }
});

$(document).on("click", ".li_event_detail", function () {
    $(".step_1").show();
    $(".step_2").css("display", "none");
    $(".step_3").css("display", "none");
    $(".step_4").css("display", "none");
    $(".step_final_checkout").css("display", "none");

    $('.event_create_percent').text('25%');
    $('.current_step').text('1 of 4');


    handleActiveClass(this);
});

$(document).on("click", ".li_guest", function () {
    var design = eventData.desgin_selected;
    if( design == undefined || design == ''){
        return;
    }else{
        $(".step_1").css("display", "none");
        $(".step_2").css("display", "none");
        $(".step_4").css("display", "none");
        $(".step_final_checkout").css("display", "none");
        $(".step_3").show();

        $('.event_create_percent').text('75%');
        $('.current_step').text('3 of 4');
    
        handleActiveClass('.li_guest');
    }
});

$(document).on("click", ".li_setting", function () {
    var design = eventData.desgin_selected;
    var step3 = eventData.step;
    console.log(step3);
    if( step3 == undefined || (step3 != '3' && step3 != '4') ){
        return;
    }else{

        $(".step_1").css("display", "none");
        $(".step_2").css("display", "none");
        $(".step_3").css("display", "none");
        $(".step_final_checkout").css("display", "none");
        $(".step_4").show();
        $('.event_create_percent').text('99%');
        $('.current_step').text('4 of 4');
        handleActiveClass(this);
    }
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
    var id = $(this).data("id");
    var userId = $(this).data("userid");
    var total_guest = $(".users-data.invited_user").length;
    var re_total_guest = total_guest - 1;

    var remaining_count = parseInt($("#event_guest_left_count").val());

    var re_total_remaining_count = remaining_count + 1;

    $("#" + id).remove();
    var checkbox = $("." + id);
    checkbox.prop("checked", false);

    $("#event_guest_count").text(re_total_guest + " Guests");
    $(".invite-count").text(re_total_guest);
    // $(".invite-left_d").text("Invites | " + re_total_remaining_count + " Left");
    if(re_total_remaining_count < 0){
        $(".invite-left_d").text("Invites | 0 Left");
    }else{
        $(".invite-left_d").text("Invites | " + re_total_remaining_count + " Left");

    }

    $("#event_guest_left_count").val(re_total_remaining_count);
    delete_invited_user(userId);
});

$(document).on("click", "#delete_invited_user_tel", function () {
    var id = $(this).data("id");
    $("#" + id).remove();
    var checkbox = $("." + id);
    var userId = $(this).data("userid");
    checkbox.prop("checked", false);


    var total_guest = $(".users-data.invited_user").length;
    var re_total_guest = total_guest;

    var remaining_count = parseInt($("#event_guest_left_count").val());

    var re_total_remaining_count = remaining_count + 1;

    $("#event_guest_count").text(re_total_guest + " Guests");
    $(".invite-count").text(re_total_guest);
    if(re_total_remaining_count < 0){
        $(".invite-left_d").text("Invites | 0 Left");
    }else{
        $(".invite-left_d").text("Invites | " + re_total_remaining_count + " Left");

    }
    // $(".invite-left_d").text("Invites | " + re_total_remaining_count + " Left");
    $("#event_guest_left_count").val(re_total_remaining_count);





    delete_invited_user(userId);
});

function enforceCheckboxLimit() {
    var checkedCount = $("input[name='email_invite[]']:checked").length;
    // console.log(checkedCount);
    if (checkedCount >= 15) {
        $("input[name='email_invite[]']:not(:checked)").prop("disabled", true);
        $("input[name='mobile[]']:not(:checked)").prop("disabled", true);
    } else {
        $("input[name='email_invite[]']").prop("disabled", false);
        $("input[name='mobile[]']").prop("disabled", false);
    }
}

enforceCheckboxLimit();

function toggleSidebar(id = null) {
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

$(document).on("change", "input[name='email_invite[]']", function () {
    // enforceCheckboxLimit();
    if (!$(this).is(":checked")) {
        var check = $(this).data("id");
        $("#" + check).remove();
        var total_guest = $(".users-data.invited_user").length;
        $("#event_guest_count").text(total_guest + " Guests");
        $(".invite-count").text(total_guest);

        var max_guest = 15;

        var remainingCount = max_guest - total_guest;
        if(remainingCount < 0){
            $(".invite-left_d").text("Invites | 0 Left");
        }else{
            $(".invite-left_d").text("Invites | " + remainingCount + " Left");
        }
        $("#event_guest_left_count").val(remainingCount);
    }
});

$(document).on("change", "input[name='mobile[]']", function () {
    // enforceCheckboxLimit();
    if (!$(this).is(":checked")) {
        var check = $(this).data("id");
        $("#" + check).remove();
        var total_guest = $(".users-data.invited_user").length;
        $("#event_guest_count").text(total_guest + " Guests");
        $(".invite-count").text(total_guest);

        var max_guest = 15;

        var remainingCount = max_guest - total_guest;
        if(remainingCount < 0){
            $(".invite-left_d").text("Invites | 0 Left");
        }else{
            $(".invite-left_d").text("Invites | " + remainingCount + " Left");
        }
        $("#event_guest_left_count").val(remainingCount);
    }
});

$(document).on("change", ".user_choice", function () {
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
    $('.delete_potluck_title').text('Delete Category');
    $('.delete_potluck_text').text('Deleting this category will delete all items under this category.');
    $('.delete_category_text').text('Category deletion is not reversible');
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
            if(potluck_delete_id == 'all_potluck'){
                $(".potluck").hide();
                $('.category-main-dishesh').remove();
                category = 0;
                items = 0;
                potluck_cateogry_item_count();
                return;
            }
            category--;

            $('#category_count').val(category);
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

function potluck_cateogry_item_count(){
    if(category == 0 && items == 0){
        $('.potluck_count').html(` <span class="me-3">
            <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M17.1336 12.267L11.8002 2.66699C11.0836 1.37533 10.0919 0.666992 9.00023 0.666992C7.90856 0.666992 6.91689 1.37533 6.20023 2.66699L0.866892 12.267C0.191892 13.492 0.116892 14.667 0.658559 15.592C1.20023 16.517 2.26689 17.0253 3.66689 17.0253H14.3336C15.7336 17.0253 16.8002 16.517 17.3419 15.592C17.8836 14.667 17.8086 13.4837 17.1336 12.267ZM8.37523 6.50033C8.37523 6.15866 8.65856 5.87533 9.00023 5.87533C9.34189 5.87533 9.62523 6.15866 9.62523 6.50033V10.667C9.62523 11.0087 9.34189 11.292 9.00023 11.292C8.65856 11.292 8.37523 11.0087 8.37523 10.667V6.50033ZM9.59189 13.7587C9.55023 13.792 9.50856 13.8253 9.46689 13.8587C9.41689 13.892 9.36689 13.917 9.31689 13.9337C9.26689 13.9587 9.21689 13.9753 9.15856 13.9837C9.10856 13.992 9.05023 14.0003 9.00023 14.0003C8.95023 14.0003 8.89189 13.992 8.83356 13.9837C8.78356 13.9753 8.73356 13.9587 8.68356 13.9337C8.63356 13.917 8.58356 13.892 8.53356 13.8587C8.49189 13.8253 8.45023 13.792 8.40856 13.7587C8.25856 13.6003 8.16689 13.3837 8.16689 13.167C8.16689 12.9503 8.25856 12.7337 8.40856 12.5753C8.45023 12.542 8.49189 12.5087 8.53356 12.4753C8.58356 12.442 8.63356 12.417 8.68356 12.4003C8.73356 12.3753 8.78356 12.3587 8.83356 12.3503C8.94189 12.3253 9.05856 12.3253 9.15856 12.3503C9.21689 12.3587 9.26689 12.3753 9.31689 12.4003C9.36689 12.417 9.41689 12.442 9.46689 12.4753C9.50856 12.5087 9.55023 12.542 9.59189 12.5753C9.74189 12.7337 9.83356 12.9503 9.83356 13.167C9.83356 13.3837 9.74189 13.6003 9.59189 13.7587Z" fill="#E03137"/>
            </svg>
        </span>
        <h5>Select potluck</h5>`);
    }else if(category > 0 && items > 0){
        if(items == 1){
            $('.potluck_count').html(` <span class="me-3">
            </span>
            <h5>${category} Categoty . ${items} Item</h5>`);
        }else{
            $('.potluck_count').html(` <span class="me-3">
            </span>
            <h5>${category} Categoty . ${items} Items</h5>`);
        }
    }else if(category > 0 && items == 0){
        $('.potluck_count').html(` <span class="me-3">
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

$(document).on("click", ".qty-btn-plus", function () {
    var categoryItemKey = $(this).parent().find(".category-item-key").val();
    var categoryIndexKey = $(this).parent().find(".category-index-key").val();
    var categoryItemQuantity = $(this)
        .parent()
        .find(".category-item-quantity")
        .val();
    var itemQuantityMinus = $(this)
        .parent()
        .find(".item-quantity-minus")
        .val();
    var $input = $(this).siblings(".input-qty");
    var value = parseInt($input.val());
        $input.val(value + 1);
    var quantity = parseInt($(this).parent().find(".input-qty").val());
    console.log(quantity);
    if(quantity > 0){
        $(this).parent().find(".item-quantity-minus").val(1);
    }

    if (categoryItemQuantity >= quantity) {

        update_self_bring(
            categoryItemKey,
            categoryIndexKey,
            quantity,
            categoryItemQuantity,
            'plus'
        );
    } else {
        quantity--;
        $(this).parent().find(".input-qty").val(quantity);
    }
});

$(document).on("click", ".qty-btn-minus", function () {
    
    var categoryItemKey = $(this).parent().find(".category-item-key").val();
    var categoryIndexKey = $(this).parent().find(".category-index-key").val();
    var categoryItemQuantity = $(this)
        .parent()
        .find(".category-item-quantity")
        .val();
    var itemQuantityMinus = $(this)
        .parent()
        .find(".item-quantity-minus")
        .val();
    var $input = $(this).siblings(".input-qty");
    var value = parseInt($input.val());
    if (value > 0) {
        $input.val(value - 1);
    }
    var quantity = parseInt($(this).parent().find(".input-qty").val());
    console.log(quantity);
    if (categoryItemQuantity >= quantity ) {
        if(itemQuantityMinus == 1){
            update_self_bring(
                categoryItemKey,
                categoryIndexKey,
                quantity,
                categoryItemQuantity,
                'minus'
            );
            if(quantity == 0){
                $(this).parent().find(".item-quantity-minus").val(0);
            }
        }
        
    } else {
       
        $(this).parent().find(".input-qty").val(0);
    }
    
});

function update_self_bring(
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
            console.log(quantity+"/"+categoryItemQuantity);
            $("#h6-" + categoryItemKey + "-" + categoryIndexKey).text(
                quantity + "/" + categoryItemQuantity
            );
            $("#missing-category-" + categoryIndexKey).text(response);
            if (response == 0) {
                var svg =
                    '<svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M7.00016 0.333984C3.32683 0.333984 0.333496 3.32732 0.333496 7.00065C0.333496 10.674 3.32683 13.6673 7.00016 13.6673C10.6735 13.6673 13.6668 10.674 13.6668 7.00065C13.6668 3.32732 10.6735 0.333984 7.00016 0.333984ZM10.1868 5.46732L6.40683 9.24732C6.3135 9.34065 6.18683 9.39398 6.0535 9.39398C5.92016 9.39398 5.7935 9.34065 5.70016 9.24732L3.8135 7.36065C3.62016 7.16732 3.62016 6.84732 3.8135 6.65398C4.00683 6.46065 4.32683 6.46065 4.52016 6.65398L6.0535 8.18732L9.48016 4.76065C9.6735 4.56732 9.9935 4.56732 10.1868 4.76065C10.3802 4.95398 10.3802 5.26732 10.1868 5.46732Z" fill="#23AA26"></path></svg>';
                $(".missing-category-svg-" + categoryIndexKey).html(svg);
            } else {
                var svg =
                    '<svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M13.5067 9.61399L9.23998 1.93398C8.66665 0.900651 7.87332 0.333984 6.99998 0.333984C6.12665 0.333984 5.33332 0.900651 4.75998 1.93398L0.493318 9.61399C-0.0466816 10.594 -0.106682 11.534 0.326652 12.274C0.759985 13.014 1.61332 13.4207 2.73332 13.4207H11.2667C12.3867 13.4207 13.24 13.014 13.6733 12.274C14.1067 11.534 14.0467 10.5873 13.5067 9.61399ZM6.49998 5.00065C6.49998 4.72732 6.72665 4.50065 6.99998 4.50065C7.27332 4.50065 7.49998 4.72732 7.49998 5.00065V8.33398C7.49998 8.60732 7.27332 8.83398 6.99998 8.83398C6.72665 8.83398 6.49998 8.60732 6.49998 8.33398V5.00065ZM7.47332 10.8073C7.43998 10.834 7.40665 10.8607 7.37332 10.8873C7.33332 10.914 7.29332 10.934 7.25332 10.9473C7.21332 10.9673 7.17332 10.9807 7.12665 10.9873C7.08665 10.994 7.03998 11.0007 6.99998 11.0007C6.95998 11.0007 6.91332 10.994 6.86665 10.9873C6.82665 10.9807 6.78665 10.9673 6.74665 10.9473C6.70665 10.934 6.66665 10.914 6.62665 10.8873C6.59332 10.8607 6.55998 10.834 6.52665 10.8073C6.40665 10.6807 6.33332 10.5073 6.33332 10.334C6.33332 10.1607 6.40665 9.98732 6.52665 9.86065C6.55998 9.83399 6.59332 9.80732 6.62665 9.78065C6.66665 9.75398 6.70665 9.73398 6.74665 9.72065C6.78665 9.70065 6.82665 9.68732 6.86665 9.68065C6.95332 9.66065 7.04665 9.66065 7.12665 9.68065C7.17332 9.68732 7.21332 9.70065 7.25332 9.72065C7.29332 9.73398 7.33332 9.75398 7.37332 9.78065C7.40665 9.80732 7.43998 9.83399 7.47332 9.86065C7.59332 9.98732 7.66665 10.1607 7.66665 10.334C7.66665 10.5073 7.59332 10.6807 7.47332 10.8073Z" fill="#F73C71" /></svg>';
                $(".missing-category-svg-" + categoryIndexKey).html(svg);
            }
            $(
                ".category-item-total-" +
                    categoryItemKey +
                    "-" +
                    categoryIndexKey
            ).text(quantity);
            
                if(type == 'plus'){
                    var current_item = parseInt($('.total-self-bring-'+categoryIndexKey).text());
                    current_item = current_item + 1;
                    $('.total-self-bring-'+categoryIndexKey).text(current_item);
                }else if(type == 'minus'){
                    var current_item = parseInt($('.total-self-bring-'+categoryIndexKey).text());
                    current_item = current_item - 1;
                    $('.total-self-bring-'+categoryIndexKey).text(current_item);
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
    $(this).parent().parent().hide();
    var self_bring_quantity =  $(this)
        .parent()
        .parent()
        .find(".qty-container")
        .children(".input-qty")
        .val();

    var total_category_count = parseInt($('.total-self-bring-'+categoryIndexKey).text());

    total_category_count = total_category_count - parseInt(self_bring_quantity);
    $('.total-self-bring-'+categoryIndexKey).text(total_category_count);
    $(this)
        .parent()
        .parent()
        .find(".qty-container")
        .children(".input-qty")
        .val(0);

    // $(this).parent().closest('.qty-container').find('.input-qty').val(0);
    update_self_bring(categoryItemKey, categoryIndexKey, 0, itemquantity);
});

$(document).on("click", ".add-user-list", function () {
    var listid = $(this).data("listid");
    var target = $(this).data("bs-target");
    $("#" + listid).show();
    $(target).toggleClass("collapse show");
});

function validateURL($input) {
    const errorMessage = $("#registry_link_error");
    // const urlPattern =
    //     /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/;
    const urlPattern = /^(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+\.\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?$/;
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
    var regex = /^(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+\.\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?$/;

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

    if (registry_link != '' && regex.test(registry_link)) {
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
                registry_item: registry_edit_item
                    ? registry_edit_item
                    : registry_item,
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
                toggleSidebar("sidebar_gift_registry");
                $("#recipient_name").val("");
                $("#registry_link").val("");
            },
            error: function (xhr, status, error) {
                console.log("AJAX error: " + error);
            },
        });
        registry_item++;
    }else{
        $("#registry_link_error")
            .css("display", "block")
            .css("color", "red")
            .text("Please enter a valid Link format (e.g., https://example.com)");
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

    $("#recipient_name").val(recipientName);
    $("#registry_link").val(registryLink);
    $("#registry_item_id").val(id);
    toggleSidebar("sidebar_gift_registry_item");
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
            thankyou_template_id: edit_template_id
                ? edit_template_id
                : '',
        },
        success: function (response) {
            console.log(response);
            if (response.status == "1") {
                toastr.success("Greeting card updated");
                $("#edit_template_id").val("");
            }else{
                toastr.success("Greeting card created");
                $("#edit_template_id").val("");
            }
            $(".list_thankyou_card").html(response.view);
            console.log(eventData.thank_you_card_id);
            if(eventData.thank_you_card_id != undefined){
                $('input[name="select_thankyou[]"]').each(function() {
                    if ($(this).val() == eventData.thank_you_card_id) {
                        $(this).prop('checked', true); // Check the checkbox
                    }
                });
                
            }
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
        success: function (response) {},
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

    console.log(templateName);
    console.log(message);
    console.log(message);

    $("#thankyou_templatename").val(templateName);
    $("#thankyou_when_to_send").val(when_to_send);
    $("#message_for_thankyou").val(message);
    $("#edit_template_id").val(id);

    toggleSidebar("sidebar_add_thankyou_card");
});

$(document).on("change", 'input[name="select_thankyou[]"]', function () {
    $('input[name="select_thankyou[]"]').not(this).prop("checked", false);
});

$(document).on("click", "#close_thankyou_card_popup", function () {
    $("#thankyou_card_popup").remove();
    ajax_tip_close("thankyou_card");
});

$(document).on("click", ".save_allow_limit", function () {
    var allow_limit = $("#allow_limit_count").val();
    eventData.allow_limit_count = allow_limit;
    console.log(allow_limit);
    if(allow_limit == 0){
        $('.allow_for_limit_count').html(`<div class="d-flex align-items-center add_new_limit">
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
    }else{
        $('.allow_for_limit_count').html(`<div class="d-flex align-items-center add_new_limit">
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
    if ($("input[name='guest_list[]']:checked").length > 1) {
        $(this).prop("checked", false);
        toastr.error("You can select only one co-host");
        return;
    }else{
        if ($(this).is(':checked')) {
            var profilePhoto = $(this).data('profile');
            var user_name = $(this).data('username');
            var profile_or_text = $(this).data("profile_or_text");
            var initial = $(this).data("initial");
            selected_co_host = $(this).val();
            // console.log(profile_or_text);
            if(profile_or_text == '1'){
                $('.selected-co-host-image').show();
                $('.selected-co-host-image').attr('src',profilePhoto);
                $('.selected-host-h5').css('display','none');
            }else{
                $('.selected-host-h5').show();
                $('.selected-co-host-image').css('display','none');
                $('.selected-host-h5').text(initial);
                var firstinitial = initial.charAt(0);
                $('.selected-host-h5').addClass('fontcolor'+firstinitial);
            }
            $('.remove_co_host').attr('data-id',selected_co_host);
            $('.selected-host-name').text(user_name);
            $('.guest-contacts-wrp').css('display','flex');
            var prefer_by_email = $(this).data('email');
            if(prefer_by_email){
                selected_co_host_prefer_by = 'email';
            }else{
                if ($("input[name='guest_list[]']:checked").length === 0) {
                    $('.guest-contacts-wrp').css('display', 'none');
                }
            }
        }else{
            if ($("input[name='guest_list[]']:checked").length === 0) {
                $('.guest-contacts-wrp').css('display', 'none');
            }
        }
    } 
});

$(document).on("click",".remove_co_host",function(){
    var hostId = $(this).data('id');
    eventData.co_host = '';
    eventData.co_host_prefer_by = '';
    selected_co_host = '';
    selected_co_host_prefer_by = '';
    $('.guest-contacts-wrp').css('display','none');
    $('.add_new_co_host').html(`<span class="me-3">
            <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M17.1336 12.267L11.8002 2.66699C11.0836 1.37533 10.0919 0.666992 9.00023 0.666992C7.90856 0.666992 6.91689 1.37533 6.20023 2.66699L0.866892 12.267C0.191892 13.492 0.116892 14.667 0.658559 15.592C1.20023 16.517 2.26689 17.0253 3.66689 17.0253H14.3336C15.7336 17.0253 16.8002 16.517 17.3419 15.592C17.8836 14.667 17.8086 13.4837 17.1336 12.267ZM8.37523 6.50033C8.37523 6.15866 8.65856 5.87533 9.00023 5.87533C9.34189 5.87533 9.62523 6.15866 9.62523 6.50033V10.667C9.62523 11.0087 9.34189 11.292 9.00023 11.292C8.65856 11.292 8.37523 11.0087 8.37523 10.667V6.50033ZM9.59189 13.7587C9.55023 13.792 9.50856 13.8253 9.46689 13.8587C9.41689 13.892 9.36689 13.917 9.31689 13.9337C9.26689 13.9587 9.21689 13.9753 9.15856 13.9837C9.10856 13.992 9.05023 14.0003 9.00023 14.0003C8.95023 14.0003 8.89189 13.992 8.83356 13.9837C8.78356 13.9753 8.73356 13.9587 8.68356 13.9337C8.63356 13.917 8.58356 13.892 8.53356 13.8587C8.49189 13.8253 8.45023 13.792 8.40856 13.7587C8.25856 13.6003 8.16689 13.3837 8.16689 13.167C8.16689 12.9503 8.25856 12.7337 8.40856 12.5753C8.45023 12.542 8.49189 12.5087 8.53356 12.4753C8.58356 12.442 8.63356 12.417 8.68356 12.4003C8.73356 12.3753 8.78356 12.3587 8.83356 12.3503C8.94189 12.3253 9.05856 12.3253 9.15856 12.3503C9.21689 12.3587 9.26689 12.3753 9.31689 12.4003C9.36689 12.417 9.41689 12.442 9.46689 12.4753C9.50856 12.5087 9.55023 12.542 9.59189 12.5753C9.74189 12.7337 9.83356 12.9503 9.83356 13.167C9.83356 13.3837 9.74189 13.6003 9.59189 13.7587Z" fill="#E03137" />
            </svg>
            </span>
            <h5>Select your co-host</h5>`);
    $('.user-'+hostId).prop("checked", false);
})

$(document).on("click", ".save_event_co_host", function () {
    var checkedCheckbox = $('input[name="guest_list[]"]:checked');

    if (checkedCheckbox.length > 0) {
        var profilePhoto = checkedCheckbox.data('profile');
        var user_name = checkedCheckbox.data('username');
        var dataId = checkedCheckbox.val();
        var profile_or_text = checkedCheckbox.data("profile_or_text");
        var prefer_by = checkedCheckbox.data("prefer_by");
        // console.log(prefer_by);
        eventData.co_host = dataId;
        selected_co_host = dataId;
        selected_co_host_prefer_by = prefer_by;
        var initial = checkedCheckbox.data("initial");

        if(profile_or_text == '1'){
            $('.selected-co-host-image').show();
            $('.selected-co-host-image').attr('src',profilePhoto);
            $('.selected-host-h5').css('display','none');
        }else{
            $('.selected-host-h5').show();
            $('.selected-co-host-image').css('display','none');
            $('.selected-host-h5').text(initial);
        }
        $('.remove_co_host').attr('data-id',selected_co_host);
        $('.selected-host-name').text(user_name);
        $('.guest-contacts-wrp').css('display','flex');

       
        eventData.co_host_prefer_by = prefer_by;
        if(profile_or_text == '1'){
            $('.add_new_co_host').html(`<span class="mx-3"><div class="contact-img co-host-profile-photo">
                    <img src="${profilePhoto}"
                        alt="logo">
                </div></span>
                <h5>${user_name}</h5>`);
        }else{
            $('.add_new_co_host').html(`<span class="mx-3"><div class="contact-img">
                    ${profilePhoto}
                </div></span>
                <h5>${user_name}</h5>`);
        }
        toggleSidebar();
    }else{
        eventData.co_host = '';
        eventData.co_host_prefer_by = '';
        selected_co_host = '';
        selected_co_host_prefer_by = '';
        $('.guest-contacts-wrp').css('display','none');
        $('.add_new_co_host').html(`<span class="me-3">
                <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M17.1336 12.267L11.8002 2.66699C11.0836 1.37533 10.0919 0.666992 9.00023 0.666992C7.90856 0.666992 6.91689 1.37533 6.20023 2.66699L0.866892 12.267C0.191892 13.492 0.116892 14.667 0.658559 15.592C1.20023 16.517 2.26689 17.0253 3.66689 17.0253H14.3336C15.7336 17.0253 16.8002 16.517 17.3419 15.592C17.8836 14.667 17.8086 13.4837 17.1336 12.267ZM8.37523 6.50033C8.37523 6.15866 8.65856 5.87533 9.00023 5.87533C9.34189 5.87533 9.62523 6.15866 9.62523 6.50033V10.667C9.62523 11.0087 9.34189 11.292 9.00023 11.292C8.65856 11.292 8.37523 11.0087 8.37523 10.667V6.50033ZM9.59189 13.7587C9.55023 13.792 9.50856 13.8253 9.46689 13.8587C9.41689 13.892 9.36689 13.917 9.31689 13.9337C9.26689 13.9587 9.21689 13.9753 9.15856 13.9837C9.10856 13.992 9.05023 14.0003 9.00023 14.0003C8.95023 14.0003 8.89189 13.992 8.83356 13.9837C8.78356 13.9753 8.73356 13.9587 8.68356 13.9337C8.63356 13.917 8.58356 13.892 8.53356 13.8587C8.49189 13.8253 8.45023 13.792 8.40856 13.7587C8.25856 13.6003 8.16689 13.3837 8.16689 13.167C8.16689 12.9503 8.25856 12.7337 8.40856 12.5753C8.45023 12.542 8.49189 12.5087 8.53356 12.4753C8.58356 12.442 8.63356 12.417 8.68356 12.4003C8.73356 12.3753 8.78356 12.3587 8.83356 12.3503C8.94189 12.3253 9.05856 12.3253 9.15856 12.3503C9.21689 12.3587 9.26689 12.3753 9.31689 12.4003C9.36689 12.417 9.41689 12.442 9.46689 12.4753C9.50856 12.5087 9.55023 12.542 9.59189 12.5753C9.74189 12.7337 9.83356 12.9503 9.83356 13.167C9.83356 13.3837 9.74189 13.6003 9.59189 13.7587Z" fill="#E03137" />
                </svg>
            </span>
            <h5>Select your co-host</h5>`);
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

    $('#eventImage').attr('src',base_url+'public/storage/event_images/'+eventData.desgin_selected+'');
        $(".step_1").css("display", "none");
        $(".step_2").css("display", "none");
        $(".step_3").css("display", "none");
        $(".step_4").css("display", "none");
        $(".step_final_checkout").show();
        final_step = 4;
        eventData.step = '4';

  

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
            console.log(response);
            $("#loader").css('display','none');
            $(".main-content-wrp").removeClass("blurred");

            if(response.is_registry=="1"){
                $('#gift_registry_logo').html(response.view);
                // $('#eventModal').modal('show');         
            }else{
                toastr.success('Event Created Successfully');
                // window.location.href="profile";
            }
            $('#eventModal').modal('show');           


        },
        error: function (xhr, status, error) {
            console.log("AJAX error: " + error);
        },
    });
});

var busy = false;
var limit = 50;
var offset = 0;
var page = '';
$(document).on("click", ".store_desgin_temp", function () {
    var downloadImage = document.getElementById("download_image");
    $("#loader").show();
    $(this).prop("disabled", true);
    $('.btn-close').prop("disabled", true);
   

    $(".main-content-wrp").addClass("blurred");

    domtoimage
        .toBlob(downloadImage)
        .then(function (blob) {
            var formData = new FormData();
            formData.append("image", blob, "design.png");
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
                    if(eventData.step == '1'){
                        eventData.step = '2';
                    }
                    console.log(eventData);
                    console.log("Image uploaded and saved successfully");
                    $("#exampleModal").modal("hide");
                    $("#loader").css("display", "none");
                    $('.store_desgin_temp').prop("disabled", false);
                    $(".main-content-wrp").removeClass("blurred");
                    $(".step_2").hide();
                    handleActiveClass('.li_guest');


                    $('.event_create_percent').text('75%');
                    $('.current_step').text('3 of 4');
                    
                    $(".step_3").show();
                    console.log(eventData);
                    if(final_step == 2){
                        final_step == 3;
                    }
                    var type="all"
                    get_user(type);
                    

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
});

function get_user(type){
    if (busy == false) {
        busy = true;
        page = 3;
        displayRecords(limit, offset,type);
    }
    
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
$('#YesviteUserAll').scroll(function () {

    var scrollTop = $(this).scrollTop(); // Current scroll position
    var scrollHeight = $(this)[0].scrollHeight; // Total height of the scrollable area
    var elementHeight = $(this).height(); // Visible height of the element

    // Check if the user has scrolled to the bottom
    if (scrollTop + elementHeight >= scrollHeight) {
        busy = true;
        offset = limit + offset;
        
        $('#loader').css('display','block');
        setTimeout(function () {
            displayRecords(limit, offset,'all');

        }, 1000);
    }
});

$('#groupUsers').scroll(function () {

    var scrollTop = $(this).scrollTop(); // Current scroll position
    var scrollHeight = $(this)[0].scrollHeight; // Total height of the scrollable area
    var elementHeight = $(this).height(); // Visible height of the element

    // Check if the user has scrolled to the bottom
    if (scrollTop + elementHeight >= scrollHeight) {
        busy = true;
        offset = limit + offset;
        
        $('#loader').css('display','block');
        setTimeout(function () {
            displayRecords(limit, offset,'group');
        }, 1000);
    }
});
// $("#loader").css('display','block');

function displayRecords(lim, off,type,search = null,) {
    var search_name = '';
    if(type!='group'){
        search_name = $('.search_user').val();
        if(search_name !=''){
            offset = 0;
        }
    }
    $.ajax({
        type: "GET",
        async: false,
        url: base_url+'event/get_user_ajax',
        data: "limit=" + lim + "&offset=" + off + "&type=" + type + "&search_user=" + search_name,
        cache: false,
        beforeSend: function () {

        },
        success: function (html) {
            var currentInviteCount = parseInt($('#currentInviteCount').val())
            if(currentInviteCount >= 15){
                $('.user_choice').prop('disabled',true);
            }
            if(type=="all"){
                if(search != null){
                    $("#YesviteUserAll").html(html);
                }else{
                    $("#YesviteUserAll").append(html);
                }
        }else{
            $("#groupUsers").html(html);
        }
        busy = false;
        setTimeout(function () {
            $('#loader').css('display','none');
        }, 1000);
    },
    });
}


$(document).on('keyup','.search_user_ajax',function(){
    search_name = $(this).val();
    offset = 0;
    $('#loader').css('display','block');
    setTimeout(function () {
        displayRecords(limit,offset,'all',search_name);
        // $('#loader').css('display','none');
    }, 500);
})

function loadSearchUser(search_name) {
    $.ajax({
        url: base_url + 'event/search_user_ajax',
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

    if (group_name == "") {
        $("#group_name_error")
            .css("display", "block")
            .css("color", "red")
            .text("Please enter group name");
        return;
    } else {
        $("#group_name_error").css("display", "none");
        toggleSidebar("sidebar_add_group_member");
        var type="group";
        get_user(type);
    }
});

$(document).on("click", ".add_new_group_member", function () {
    var group_name = $("#new_group_name").val();
    console.log(group_name);
    var selectedValues = [];
    $(".user_group_member:checked").each(function () {
        selectedValues.push({
            id: $(this).val(),
            prefer_by: $(this).data("preferby"),
        });
    });

    // console.log(group_name);
    console.log(selectedValues);
    if(selectedValues.length > 0){
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
                    $(".group_list").append(response.view);
                    toggleSidebar("sidebar_groups");
                }
            },
            error: function (xhr, status, error) {
                console.log("AJAX error: " + error);
            },
        });
    }else{
        toastr.error('Please select Member');
    }
});

$(document).on("click", ".invite_group_member", function () {
    $('#loader').css('display','block');
    var userId=$(this).val();
    var selectedValues = []; 
$(".user_group_member").each(function () {
    if ($(this).is(":checked")) {
        var perferby=$(this).data('preferby');
        var invited_by="";
        if(perferby=="email"){
            invited_by= $(this).data('email');
        }else{
            invited_by=$(this).data('mobile');
        }

        selectedValues.push({
            id: $(this).val(),
            preferby: perferby,
            invited_by:invited_by
        });
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
        response.data.forEach(function(item, index) {
            console.log(item.is_duplicate);
            if (item.is_duplicate == '1' && item.userdata && item.userdata.id) {
                $("#user-" + item.userdata.id).remove();
            }
        });        
        $(".inivted_user_list").append(response.view);
        // $(".inivted_user_list").html('');
            guest_counter(0,15);

            toggleSidebar();
            $("#YesviteUserAll").html('');
            var type="all";
            get_user(type);
        // }
    },
    error: function (xhr, status, error) {
        console.log("AJAX error: " + error);
    },
});
});


$(document).on("click", "#delete_group", function () {
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
            }
        },
        error: function (xhr, status, error) {
            console.log("AJAX error: " + error);
        },
    });
});

$(document).on("click", ".view_members", function () {
    var group_id = $(this).data("id");
    $('#loader').css('display','block');
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
                $(".user-contacts-sidebar").append(response.view);
                
                toggleSidebar("sidebar_list_group_member");
                $('#loader').css('display','none');

            }
        },
        error: function (xhr, status, error) {
            console.log("AJAX error: " + error);
        },
    });
});


$(document).on('click','.edit-icon',function () {
    toggleSidebar('sidebar_change_plan');
  });   


$(document).on('click','.free_plan',function () { 
    handleActivePlan(this);
 });

 $(document).on('click','.pro_plan',function () { 
    handleActivePlan(this);
 });

 $(document).on('click','.pro_year_plan',function () { 
    handleActivePlan(this);
 });

 $(document).on('click','.continue-btn',function () { 
    $('#loader').css('display','none');
 });
 



 $(document).on('change', 'input[name="gift_registry[]"]',function() {
    var selected_gift = [];
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
    $('input[name="gift_registry[]"]:checked').each(function() {
        var registry_name = $(this).data('item');
        var registry_link = $(this).data('registry');
        var gr_id = $(this).val();
        selected_gift.push({
            registry_name: registry_name,
            registry_link: registry_link,
            gr_id: gr_id,
        });
    });
    eventData.gift_registry_data=selected_gift;
    // console.log(eventData);

    var selected = $('input[name="gift_registry[]"]:checked');
        if (selected.length > 2) {
        $(this).prop('checked', false);
       toastr.error('Maximum two gift registry can select');
       selected_gift = [];
        $('input[name="gift_registry[]"]:checked').each(function() {
            var registry_name = $(this).data('item');
            var registry_link = $(this).data('registry');
            var gr_id = $(this).val();
            selected_gift.push({
                registry_name: registry_name,
                registry_link: registry_link,
                gr_id: gr_id
            });
        });
        eventData.gift_registry_data = selected_gift;
    }
});

$(document).on('click','.brand-progress',function () { 
    var event_id = $(this).data('id');
    // window.location.href="event?id="+event_id;
       
});

$(document).on('click','.create_new_event_close_tip',function () { 
    $('#create_new_event_tip').removeClass('d-flex');
    $('#create_new_event_tip').hide();

    ajax_tip_close("create_new_event");

});


$(document).on('click','#guest_list_visible_to_guest',function () { 
    if($(this).is(':checked')){
        $('#eventwall').prop('checked',true);
    }else{
        $('#eventwall').prop('checked',false);
    }
    
});

$(document).on('click','input[name="select_thankyou[]"]',function () { 
    var i = 0;
    var checkedCount = 0;
    $("input[name='select_thankyou[]']" ).each(function (index) {
        if ($(this).is(':checked')) {
            checkedCount++;
            eventData.thank_you_card_id = $(this).data('id');
        }
        i++;
    });
    if(i>=1 && checkedCount > 0){
        if(i==1){
            $('.add_new_thankyou_card').html(`<span class="me-3"></span>
                <h5>${i} Template available</h5>`);

        }else{
            $('.add_new_thankyou_card').html(`<span class="me-3"></span>
                <h5>${i} Templates available</h5>`);
        }
    }else{
        eventData.thank_you_card_id = '';
        $('.add_new_thankyou_card').html(`<span class="me-3">
                <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M17.1336 12.267L11.8002 2.66699C11.0836 1.37533 10.0919 0.666992 9.00023 0.666992C7.90856 0.666992 6.91689 1.37533 6.20023 2.66699L0.866892 12.267C0.191892 13.492 0.116892 14.667 0.658559 15.592C1.20023 16.517 2.26689 17.0253 3.66689 17.0253H14.3336C15.7336 17.0253 16.8002 16.517 17.3419 15.592C17.8836 14.667 17.8086 13.4837 17.1336 12.267ZM8.37523 6.50033C8.37523 6.15866 8.65856 5.87533 9.00023 5.87533C9.34189 5.87533 9.62523 6.15866 9.62523 6.50033V10.667C9.62523 11.0087 9.34189 11.292 9.00023 11.292C8.65856 11.292 8.37523 11.0087 8.37523 10.667V6.50033ZM9.59189 13.7587C9.55023 13.792 9.50856 13.8253 9.46689 13.8587C9.41689 13.892 9.36689 13.917 9.31689 13.9337C9.26689 13.9587 9.21689 13.9753 9.15856 13.9837C9.10856 13.992 9.05023 14.0003 9.00023 14.0003C8.95023 14.0003 8.89189 13.992 8.83356 13.9837C8.78356 13.9753 8.73356 13.9587 8.68356 13.9337C8.63356 13.917 8.58356 13.892 8.53356 13.8587C8.49189 13.8253 8.45023 13.792 8.40856 13.7587C8.25856 13.6003 8.16689 13.3837 8.16689 13.167C8.16689 12.9503 8.25856 12.7337 8.40856 12.5753C8.45023 12.542 8.49189 12.5087 8.53356 12.4753C8.58356 12.442 8.63356 12.417 8.68356 12.4003C8.73356 12.3753 8.78356 12.3587 8.83356 12.3503C8.94189 12.3253 9.05856 12.3253 9.15856 12.3503C9.21689 12.3587 9.26689 12.3753 9.31689 12.4003C9.36689 12.417 9.41689 12.442 9.46689 12.4753C9.50856 12.5087 9.55023 12.542 9.59189 12.5753C9.74189 12.7337 9.83356 12.9503 9.83356 13.167C9.83356 13.3837 9.74189 13.6003 9.59189 13.7587Z" fill="#E03137"/>
                </svg>
            </span>
            <h5>Select thank you card</h5>`);
    } 
});

$(document).on('click','input[name="gift_registry[]"]',function () { 

    countGiftRegestry();
});

function countGiftRegestry(){
    var i = 0;
    var checkedCount = 0;
    $("input[name='gift_registry[]']" ).each(function (index) {
        if ($(this).is(':checked')) {
            checkedCount++;
        }
        i++;
    });
    if(i>=1 && checkedCount > 0){
        if(i==1){
            $('.add_gift_registry_count').html(`<span class="me-3"></span>
                <h5>${i} Registry</h5>`);

        }else{
            $('.add_gift_registry_count').html(`<span class="me-3"></span>
                <h5>${i} Registries</h5>`);
        }
    }else{
        $('.add_gift_registry_count').html(`<span class="me-3">
            <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M17.1336 12.267L11.8002 2.66699C11.0836 1.37533 10.0919 0.666992 9.00023 0.666992C7.90856 0.666992 6.91689 1.37533 6.20023 2.66699L0.866892 12.267C0.191892 13.492 0.116892 14.667 0.658559 15.592C1.20023 16.517 2.26689 17.0253 3.66689 17.0253H14.3336C15.7336 17.0253 16.8002 16.517 17.3419 15.592C17.8836 14.667 17.8086 13.4837 17.1336 12.267ZM8.37523 6.50033C8.37523 6.15866 8.65856 5.87533 9.00023 5.87533C9.34189 5.87533 9.62523 6.15866 9.62523 6.50033V10.667C9.62523 11.0087 9.34189 11.292 9.00023 11.292C8.65856 11.292 8.37523 11.0087 8.37523 10.667V6.50033ZM9.59189 13.7587C9.55023 13.792 9.50856 13.8253 9.46689 13.8587C9.41689 13.892 9.36689 13.917 9.31689 13.9337C9.26689 13.9587 9.21689 13.9753 9.15856 13.9837C9.10856 13.992 9.05023 14.0003 9.00023 14.0003C8.95023 14.0003 8.89189 13.992 8.83356 13.9837C8.78356 13.9753 8.73356 13.9587 8.68356 13.9337C8.63356 13.917 8.58356 13.892 8.53356 13.8587C8.49189 13.8253 8.45023 13.792 8.40856 13.7587C8.25856 13.6003 8.16689 13.3837 8.16689 13.167C8.16689 12.9503 8.25856 12.7337 8.40856 12.5753C8.45023 12.542 8.49189 12.5087 8.53356 12.4753C8.58356 12.442 8.63356 12.417 8.68356 12.4003C8.73356 12.3753 8.78356 12.3587 8.83356 12.3503C8.94189 12.3253 9.05856 12.3253 9.15856 12.3503C9.21689 12.3587 9.26689 12.3753 9.31689 12.4003C9.36689 12.417 9.41689 12.442 9.46689 12.4753C9.50856 12.5087 9.55023 12.542 9.59189 12.5753C9.74189 12.7337 9.83356 12.9503 9.83356 13.167C9.83356 13.3837 9.74189 13.6003 9.59189 13.7587Z" fill="#E03137"/>
            </svg>
        </span>
        <h5>Add gift registry</h5>`);
    } 
}

$(document).on("click","#potluck_tip", function (e) {
    e.preventDefault();
    $('#potluck_tip_bar').remove();
    ajax_tip_close("potluck");
});


$(document).on("click","#co_host_tip_close", function (e) {
    e.preventDefault();
    $('#co_host_tip').remove();
    ajax_tip_close("co_host");
});

$(document).on("click","#design_tip_bar_close", function (e) {
    e.preventDefault();
    $('#design_tip_bar').remove();
    ajax_tip_close("desgin_tip");
});

function ajax_tip_close(type){
    $.ajax({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                "content"
            ),
        },
        url: base_url+"event/close_tip",
        method: "POST",
        data: {
            closed: true,
            tip:type
        },
        success: function (response) {
            // console.log(response);
        },
    });

}

function checkbox_count(){

    var checkedCount_general_setting = $('.general_setting_checkbox:checked').length;
    var checkedCount_event_page = $('.event_page_checkbox:checked').length;
    var checkedCount_notification = $('.notification_checkbox:checked').length;

    $('#general_setting_checkbox').text(checkedCount_general_setting+'/7');
    $('#event_page_checkbox').text(checkedCount_event_page+'/2');
    $('#notification_checkbox').text(checkedCount_notification+'/4');
}

$(document).on('click','.checkbox',function () {
    checkbox_count();
})

$(document).on('click','.open_addcategory',function () {
    $('#categoryName').val('');
    $('#category_quantity').val('1');
    $('.pot-cate-name').text('0/30');
})

$(document).on('click','.new_group',function () {
    var selectedValues = [];
    $('#new_group_name').val('');
    $('#group_toggle_search').val('');
    toggleSidebar('sidebar_add_groups')
})

$(document).on('click','.thankyou_card_add_form',function () {
    $('#thankyou_templatename').val('');
    $('#thankyou_when_to_send').val('');
    $('#message_for_thankyou').val('');
    toggleSidebar('sidebar_add_thankyou_card');
})

$(document).on('click','.add_new_gift_registry',function () {
    $('#recipient_name').val('');
    $('#registry_link').val('');
    $('.recipient-name-con').text('0/30');
    toggleSidebar('sidebar_gift_registry_item')
})


$(document).on('keyup','#group_search_ajax',function () {
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
            if (data.html == " ") {
                $("#loader").html("No more contacts found");
                return;
            }
            $("#loader").hide();
            $(".group_search_list").html(data.html);
        })
        .fail(function (jqXHR, ajaxOptions, thrownError) {
            alert("server not responding...");
        });
})

$(document).on('keyup','#group_toggle_search',function () {
    var search_name = $(this).val();
    groupToggleSearch(search_name);
})

function groupToggleSearch(search_name = null){
    if(search_name ==null){
        search_name = '';
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

$(document).on('click','.listing-arrow',function(){
    if($(this).parent().find('.list-slide').hasClass('open-potluck-list')){
        $(this).parent().find('.list-slide').removeClass('open-potluck-list');
    }else{
    $(this).parent().find('.list-slide').addClass('open-potluck-list');
}
});
$(document).on('click','.see_all_group',function(){
    $('search_user').val('');
    toggleSidebar('sidebar_groups');
});

$(document).on('click','.add_co_host',function(){
    
    $('.co_host_search').val('');
    get_co_host_list();
    
    setTimeout(() => {
        toggleSidebar('sidebar_add_co_host');
    }, 500);
});

$(document).on('click','.add_co_host_off',function(){
    if(eventData.co_host !== undefined){
        selected_co_host = eventData.co_host;
    }else{
        selected_co_host = '';
    }

    if(eventData.co_host_prefer_by != undefined){
        selected_co_host_prefer_by = eventData.co_host_prefer_by;
    }else{
        selected_co_host_prefer_by = '';
    }
    console.log(selected_co_host);
    console.log(selected_co_host_prefer_by);
    toggleSidebar();
});

function get_co_host_list(search_name=null){
    if(search_name ==null){
        search_name = '';
    }
    console.log(selected_co_host);
    console.log(selected_co_host_prefer_by);
    $.ajax({
        url: base_url + "event/get_co_host_list",
        type: "POST",
        data: {
            search_name: search_name,
            selected_co_host:selected_co_host,
            selected_co_host_prefer_by:selected_co_host_prefer_by,
            _token: $('meta[name="csrf-token"]').attr("content"), // Adding CSRF token
        },
        beforeSend: function () {
            $("#loader").show();
        },
    })
    .done(function (data) {
        // console.log(data);
        if (data == " ") {
            $("#loader").html("No more contacts found");
            return;
        }
        $("#loader").hide();
        $(".list_all_invited_user").html(data);
    })
    .fail(function (jqXHR, ajaxOptions, thrownError) {
        alert("server not responding...");
    });
}

$(document).on('keyup','.co_host_search',function(){
    search_name = $(this).val();
    $('#loader').css('display','block');
    $(".list_all_invited_user").empty();
    setTimeout(function () {
        get_co_host_list(search_name);
    }, 500);
})

$(document).on('click','.add-activity-schedule',function(){
    toggleSidebar('sidebar_activity_schedule');
})

$(document).on('click','.thank_you_card_toggle',function(){
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
        console.log(eventData.thank_you_card_id);
        if(eventData.thank_you_card_id != undefined){
            $('input[name="select_thankyou[]"]').each(function() {
                if ($(this).val() == eventData.thank_you_card_id) {
                    $(this).prop('checked', true); // Check the checkbox
                }
            });
            
        }
    })
    .fail(function (jqXHR, ajaxOptions, thrownError) {
        alert("server not responding...");
    });
    toggleSidebar('sidebar_thankyou_card')
})

$(document).on('click','.add_gift_registry',function(){
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
        if(eventData.gift_registry_data != undefined){
            eventData.gift_registry_data.forEach((element, index) => {
                console.log(element.gr_id);
                $('input[name="gift_registry[]"]').each(function() {
                    if ($(this).val() == element.gr_id) {
                        $(this).prop('checked', true); // Check the checkbox
                    }
                });
            });
        }
        
    })
    .fail(function (jqXHR, ajaxOptions, thrownError) {
        alert("server not responding...");
    });
    toggleSidebar('sidebar_gift_registry');
})

function getStartEndTimeZone(){
    var end_time = $("#end_time").is(":checked");
    if(end_time){
        var rsvp_end_time = $('#end-time').val();
        var rsvp_end_time_set = '1';
        var start_time_zone =  $('#start-time-zone').val();
        var end_time_zone =  $('#end-time-zone').val();

        if(rsvp_end_time_set == '1' && start_time_zone != end_time_zone){
            $('#end-time-zone').focus();
            $('#end-time-zone-error').text('End Time zone : Please select same start time zone and end time zone')
                .css("display", "block")
                .css("color", "red");
            return;
        }else{
            $('#end-time-zone-error').text('')
                .css("display", "none")
                .css("color", "red");
        }
    }

}

