$(document).ready(function () {
    var galleryItems = [];
    $(".open-event-images").each(function () {
        galleryItems.push({
            src: $(this).data("img"),
            type: "image",
        });
    });
    $(".open-event-images").click(function () {
        var index = $(".open-event-images").index(this);
        $.magnificPopup.open(
            {
                items: galleryItems,
                gallery: {
                    enabled: true,
                },
                type: "image",
            },
            index
        );
    });
});

$(document).ready(function () {
    var galleryItems = [];
    $(".rsvp-zoom-btn").each(function () {
        galleryItems.push({
            src: $(this).data("img"),
            type: "image",
        });
    });
    $(".rsvp-zoom-btn").click(function () {
        var index = $(".rsvp-zoom-btn").index(this);
        $.magnificPopup.open(
            {
                items: galleryItems,
                gallery: {
                    enabled: true,
                },
                type: "image",
            },
            index
        );
    });
});

// $('.rsvp-zoom-btn').click(function() {
//     var imageUrl = $(this).data('img');
//     console.log(imageUrl);  // Log the image URL
// })

function applyStyles() {
    if ($(window).width() <= 767) {
        $(".message-view-box").css("display", "none");
        $(document).on("click", ".chat-data", function () {
            $(".message-view-box").css("display", "block");
            $(".message-chat-lists").css("display", "none");
        });
        $(document).on("click", "#backtomsg-btn", function () {
            $(".message-view-box").css("display", "none");
            $(".message-chat-lists").css("display", "block");
        });
        // $(document).on('click','.chat-data',function(){
        //   $(".message-view-box").css("display", "block");
        //   $(".message-chat-lists").css("display", "none");
        // })
    }
    //  else {
    //   // $(".message-view-box").css("display", "block");
    // }
}

// Apply styles on page load
applyStyles();

// Apply styles on window resize
// $(window).resize(applyStyles);

$(document).ready(function () {
    $("#rsvpForm").on("submit", function (e) {
        var rsvpStatus = $('input[name="rsvp_status"]:checked').val();
        var adultsCount = parseInt($("#adultsInput").val()) || 0;
        var kidsCount = parseInt($("#kidsInput").val()) || 0;

        if (!rsvpStatus) {
            e.preventDefault();
            toastr.error("Please select RSVP");
            return;
        }

        if (rsvpStatus === "1" && adultsCount == 0 && kidsCount == 0) {
            e.preventDefault();
            toastr.error("Please add at least one adult or kid.");
            return;
        }
    });

    // $("#openGoogle").on("click", function () {
    //     const eventDate = $("#eventDate").val();
    //     const eventEndDate = $("#eventEndDate").val();
    //     const eventTime = $("#eventTime").val();
    //     const eventEndTime = $("#eventEndTime").val() || "12:00 PM";
    //     const eventName = $("#eventName").val();

    //     if (!eventDate || !eventTime) {
    //         alert("Please provide both date and time for the event.");
    //         return;
    //     }

    //     const convertTo24HourFormat = (time) => {
    //         const [hour, minuteWithPeriod] = time.split(":");
    //         const [minute, period] = minuteWithPeriod.split(" ");
    //         let newHour = parseInt(hour);
    //         if (period.toLowerCase() === "pm" && newHour !== 12) {
    //             newHour += 12;
    //         }
    //         if (period.toLowerCase() === "am" && newHour === 12) {
    //             newHour = 0;
    //         }
    //         return `${newHour}:${minute}`;
    //     };

    //     const formattedTime = convertTo24HourFormat(eventTime);
    //     const formattedEndTime = convertTo24HourFormat(eventEndTime);
    //     const startDateTime = new Date(`${eventDate}T${formattedTime}:00Z`);

    //     if (isNaN(startDateTime)) {
    //         alert("Invalid start date or time value. Please check the input.");
    //         return;
    //     }

    //     let endDateTime;
    //     if (eventEndDate) {
    //         console.log("eventEndDate:", eventEndDate);
    //         console.log("formattedEndTime:", formattedEndTime);
    //         console.log("formattedEndTime:", formattedEndTime);

    //         const endDateString = `${eventEndDate}T${formattedEndTime}:00Z`;

    //         const formattedEndDate = new Date(endDateString);

    //         if (isNaN(formattedEndDate)) {
    //             alert(
    //                 "Invalid end date or time value. Please check the input."
    //             );
    //             return;
    //         }

    //         endDateTime = formattedEndDate;
    //     } else {
    //         endDateTime = new Date(startDateTime);
    //         endDateTime.setHours(endDateTime.getHours() + 1);
    //     }

    //     const formatToGoogleCalendar = (date) => {
    //         return date.toISOString().replace(/[-:.]/g, "").slice(0, -4) + "Z";
    //     };

    //     const eventDetails = {
    //         title: eventName || "Meeting with Team",
    //         start: formatToGoogleCalendar(startDateTime),
    //         end: formatToGoogleCalendar(endDateTime),
    //     };

    //     const googleCalendarUrl = `https://calendar.google.com/calendar/render?action=TEMPLATE&text=${encodeURIComponent(
    //         eventDetails.title
    //     )}&dates=${eventDetails.start}/${eventDetails.end}&sf=true&output=xml`;

    //     window.open(googleCalendarUrl, "_blank");
    // });

    document
        .getElementById("openGoogle")
        .addEventListener("click", function () {
            // return;
            const eventDate = $("#eventDate").val();
            const eventEndDate = $("#eventEndDate").val();
            const eventTime = $("#eventTime").val();
            const eventEndTime =
                $("#eventEndTime").val() || $("#eventTime").val(); // Default value
            const eventName = $("#eventName").val();

            if (!eventDate || !eventTime) {
                toastr.error(
                    "Please provide both date and time for the event."
                );
                return;
            }

            const convertTo24HourFormat = (time) => {
                const [hour, minuteWithPeriod] = time.split(":");
                const [minute, period] = minuteWithPeriod.split(" ");
                let newHour = parseInt(hour);
                if (period?.toLowerCase() === "pm" && newHour !== 12) {
                    newHour += 12; // Convert PM time to 24-hour format
                }
                if (period?.toLowerCase() === "am" && newHour === 12) {
                    newHour = 0; // Handle 12 AM as midnight
                }
                return `${newHour}:${minute}`;
            };

            const formattedTime = convertTo24HourFormat(eventTime);
            const formattedEndTime = convertTo24HourFormat(eventEndTime);
            const startDateTime = new Date(`${eventDate}T${formattedTime}:00`); // ISO format with correct time

            if (isNaN(startDateTime)) {
                toastr.error(
                    "Invalid start date or time value. Please check the input."
                );
                return;
            }

            let endDateTime;
            if (eventEndDate) {
                const endDateString = `${eventEndDate}T${formattedEndTime}:00`;
                const formattedEndDate = new Date(endDateString);

                if (isNaN(formattedEndDate)) {
                    toastr.error(
                        "Invalid end date or time value. Please check the input."
                    );
                    return;
                }

                endDateTime = formattedEndDate;
            } else {
                endDateTime = new Date(startDateTime);
                endDateTime.setHours(endDateTime.getHours() + 1); // Default to 1 hour duration if no end date is provided
            }

            // Convert to Google Calendar format (without dashes, colons, and milliseconds)
            const formatToGoogleCalendar = (date) => {
                return (
                    date.toISOString().replace(/[-:.]/g, "").slice(0, -4) + "Z"
                );
            };

            const eventDetails = {
                title: eventName || "Meeting with Team",
                start: formatToGoogleCalendar(startDateTime),
                end: formatToGoogleCalendar(endDateTime),
            };

            console.log(eventDetails);

            // Platform-specific calendar opening code (Android / iOS)
            const isAndroid = /Android/i.test(navigator.userAgent);
            const isIOS = /iPhone|iPad|iPod/i.test(navigator.userAgent);

            // Default to Google Calendar URL
            const googleCalendarUrl = `https://calendar.google.com/calendar/render?action=TEMPLATE&text=${encodeURIComponent(
                eventDetails.title
            )}&dates=${eventDetails.start}/${
                eventDetails.end
            }&sf=true&output=xml`;

            window.open(googleCalendarUrl);
        });

    function toggleGuestCount() {
        const isNoSelected = $("#no").is(":checked");
        $(".rsvp_count_member input").prop("disabled", isNoSelected);
        $(".qty-btn-minus, .qty-btn-plus").prop("disabled", isNoSelected);
        $(".rsvp_count_member").css("opacity", isNoSelected ? "0.5" : "1");
        if (isNoSelected) {
            $("#adultsInput").val(0);
            $("#kidsInput").val(0);
        }
    }

    $('input[name="rsvp_status"]').change(function () {
        toggleGuestCount();
    });

    toggleGuestCount();
});

$("#rsvpYesForm").validate({
    rules: {
        firstname: {
            required: true,
        },
        lastname: {
            required: true,
        },
    },
    messages: {
        firstname: {
            required: "Please enter firstname",
        },
        lastname: {
            required: "Please enter lastname",
        },
    },
    errorPlacement: function (error, element) {
        if (element.attr("name") == "firstname") {
            $("#firstnameErrorLabel").html(error);
        } else if (element.attr("name") == "lastname") {
            $("#lastnameErrorLabel").html(error);
        }
    },
    success: function (label, element) {
        if ($(element).attr("name") == "firstname") {
            $("#firstnameErrorLabel").html("");
        } else if ($(element).attr("name") == "lastname") {
            $("#lastnameErrorLabel").html("");
        }
    },
});

$("#rsvpNoForm").validate({
    rules: {
        firstname: {
            required: true,
        },
        lastname: {
            required: true,
        },
    },
    messages: {
        firstname: {
            required: "Please enter firstname",
        },
        lastname: {
            required: "Please enter lastname",
        },
    },
    errorPlacement: function (error, element) {
        if (element.attr("name") == "firstname") {
            $("#firstnameErrorLabelno").html(error);
        } else if (element.attr("name") == "lastname") {
            $("#lastnameErrorLabelno").html(error);
        }
    },
    success: function (label, element) {
        if ($(element).attr("name") == "firstname") {
            $("#firstnameErrorLabelno").html("");
        } else if ($(element).attr("name") == "lastname") {
            $("#lastnameErrorLabelno").html("");
        }
    },
});
// $('#rsvp-yes-modal').on('hide.bs.modal', function (e) {
//     if (!$('#rsvpYesForm').valid()) {
//         e.preventDefault();
//     }
// });

let initialFormData = {};

// Store initial data for firstname and lastname when modal is first opened
$("#rsvp-yes-modal").on("show.bs.modal", function () {
    if ($.isEmptyObject(initialFormData)) {
        initialFormData["firstname"] = $("#firstname").val();
        initialFormData["lastname"] = $("#lastname").val();
    }
});
$("#rsvp-yes-modal").on("hidden.bs.modal", function () {
    $("#lastnameErrorLabel").text("");
    $("#firstnameErrorLabel").text("");

    $("#firstname").val(initialFormData["firstname"]);
    $("#lastname").val(initialFormData["lastname"]);
});
// Store initial data for firstname and lastname when modal is first opened
$("#rsvp-no-modal").on("show.bs.modal", function () {
    if ($.isEmptyObject(initialFormData)) {
        initialFormData["firstname"] = $(".no_firstname").val();
        initialFormData["lastname"] = $(".no_lastname").val();
    }
});
$("#rsvp-no-modal").on("hidden.bs.modal", function () {
    $("#lastnameErrorLabelno").text("");
    $("#firstnameErrorLabelno").text("");

    $(".no_firstname").val(initialFormData["firstname"]);
    $(".no_lastname").val(initialFormData["lastname"]);
});

$("#rsvp-yes-modal").on("hide.bs.modal", function (e) {
    if ($(e.relatedTarget).hasClass("yes_rsvp_btn")) {
        if (!$("#rsvpYesForm").valid()) {
            // Prevent the modal from closing if the form is invalid
            e.preventDefault();
            // toastr.error("Please correct the errors before proceeding.");
        }
    }
});
$("#rsvp-no-modal").on("hide.bs.modal", function (e) {
    if ($(e.relatedTarget).hasClass("no_rsvp_btn")) {
        if (!$("#rsvpNoForm").valid()) {
            // Prevent the modal from closing if the form is invalid
            e.preventDefault();
            // toastr.error("Please correct the errors before proceeding.");
        }
    }
});
// $('#rsvp-no-modal').on('hide.bs.modal', function (e) {
//     if (!$('#rsvpNoForm').valid()) {
//         e.preventDefault();
//     }
// });

$(document).on("click", ".yes_rsvp_btn", function (e) {
    if (!$("#rsvpYesForm").valid()) {
        e.preventDefault();
        return;
    }
    e.preventDefault();
    var adultsCount = parseInt($("#adults").val()) || 0;
    var kidsCount = parseInt($("#kids").val()) || 0;
    var firstname = $(".yes_firstname").val();
    var lastname = $(".yes_lastname").val();

    if (adultsCount == 0 && kidsCount == 0) {
        e.preventDefault();
        toastr.error("Please add at least one adult or kid.");
        return;
    }

    $("#rsvpYesForm").submit();
    $("#rsvp-yes-modal").modal("hide");
});

$(document).on("click", ".no_rsvp_btn", function (e) {
    if (!$("#rsvpNoForm").valid()) {
        e.preventDefault();
        return;
    }
    $("#rsvpNoForm").submit();
    $("#rsvp-no-modal").modal("hide");
});

$(document).ready(function () {
    $("#rsvp-yes-modal").on("hidden.bs.modal", function () {
        $("#adults").val("0");
        $("#kids").val("0");
        $(".message_to_host").val("");
        // $(".firstname").val("");
        // $(".lastname").val("");
    });
});
$(document).ready(function () {
    $("#rsvp-no-modal").on("hidden.bs.modal", function () {
        $("#adults").val("0");
        $("#kids").val("0");
        $(".message_to_host").val("");
        // $(".firstname").val("");
        // $(".lastname").val("");
    });
});

//   function checkRsvpStaus(event_id,user_id,callback){
//     $.ajax({
//         url: `${base_url}check_rsvp_status`,
//         type: 'GET',
//         data: {event_id:event_id,user_id:user_id},
//         success: function (response) {
//             var status=response.rsvp_status;
//             console.log(status);
//         },
//         error: function (xhr, status, error) {
//         },
//         complete: function () {
//         }
//     });
// }

// $("#rsvp-yes-modal").on("show.bs.modal", function (e) {
//     e.preventDefault();
// });
// $("#rsvp-no-modal").on("show.bs.modal", function (e) {
//     e.preventDefault();
// });
$(document).on("click", ".check_rsvp_yes", function (e) {
    e.preventDefault();
    var user_id = $(this).data("user_id");
    var event_id = $(this).data("event_id");
    var sync_id = $(this).data("sync_id");
    var modal = $(this).data("bs-target");

    // $.ajax({
    //     url: `${base_url}check_rsvp_status`,
    //     type: "GET",
    //     data: { event_id: event_id, user_id: user_id, sync_id: sync_id },
    //     success: function (response) {
    //         var status = response.rsvp_status;
    //         // console.log(status);
    //         if (status == "1") {
    //             toastr.success("You have already done RSVP YES");
    //         } else if (status == "cohost") {
    //             // toastr.success("You have are a cohost");
    //         } else {
    //             $(modal).off("show.bs.modal");
    //             $(modal).modal("show");
    //         }

    //         //      if(status=="cohost"){
    //         //         toastr.success('You are a cohost');
    //         //     }else{
    //         //                 $(modal).off('show.bs.modal');
    //         //                 $(modal).modal('show');
    //         //  }
    //     },
    //     error: function (xhr, status, error) {},
    //     complete: function () {},
    // });
});

$(document).on("click", ".check_rsvp_no", function (e) {
    e.preventDefault();
    var user_id = $(this).data("user_id");
    var event_id = $(this).data("event_id");
    var sync_id = $(this).data("sync_id");
    var modal = $(this).data("bs-target");
    $.ajax({
        url: `${base_url}check_rsvp_status`,
        type: "GET",
        data: { event_id: event_id, user_id: user_id, sync_id: sync_id },
        success: function (response) {
            var status = response.rsvp_status;
            // console.log(status);
            if (status == "0") {
                toastr.error("You have already done RSVP NO");
            } else {
                $(modal).off("show.bs.modal");
                $(modal).modal("show");
            }
        },
        error: function (xhr, status, error) {},
        complete: function () {},
    });
});

//   latitude
//   logitude

const latitude = parseFloat(document.getElementById("event_latitude")?.value);
const longitutde = parseFloat(document.getElementById("event_logitude")?.value);
const address = document.getElementById("event_address").value;

function initMap() {
    // Create the map
    if (
        (latitude != undefined && latitude === 0.0 && longitutde === 0.0) ||
        (latitude === 0 && longitutde === 0)
    ) {
        console.log("Address to geocode: " + address);

        const geocoder = new google.maps.Geocoder();
        geocoder.geocode({ address: address }, function (results, status) {
            if (status === "OK") {
                const location = results[0].geometry.location;
                const lat = location.lat();
                const lng = location.lng();

                console.log("Latitude: " + lat);
                console.log("Longitude: " + lng);
                createMap(lat, lng);
            } else {
                toastr.error(
                    "Geocode was not successful for the following reason: " +
                        status
                );
            }
        });
    } else {
        const mapElement = document.getElementById("map");
        mapElement.style.height = "198px";
        mapElement.style.width = "100%";

        const map = new google.maps.Map(document.getElementById("map"), {
            center: { lat: latitude, lng: longitutde },
            zoom: 15,
        });

        // Create the marker
        const marker = new google.maps.Marker({
            position: { lat: latitude, lng: longitutde },
            map: map,
            title: "test location", // Optional: adds a tooltip on hover
        });
    }
}

function createMap(lat, lng) {
    console.log(lat + "  " + lng);

    const map = new google.maps.Map(document.getElementById("map"), {
        center: { lat: lat, lng: lng },
        zoom: 15,
    });

    new google.maps.Marker({
        position: { lat: lat, lng: lng },
        map: map,
        title: "Test Location",
    });
}

$(document).on("click", ".direction-btn", function () {
    const lat = $(this).data("lat");
    const long = $(this).data("long");

    if (lat && long) {
        const googleMapsUrl = `https://www.google.com/maps?q=${lat},${long}`;
        window.open(googleMapsUrl, "_blank");
    }
});

// $('#nav-messaging-tab').on("click", function () {
//   $('.rsvp-footer-btn-wrp').css('display','none');
// });

// $('#nav-invite-tab').on("click", function () {
//   $('.rsvp-footer-btn-wrp').css('display','block');
// });

// $(document).on("click", ".nav-link", function () {
//   alert();
//   $('.rsvp-footer-btn-wrp').css('display','block');
// });

//   initMap();
