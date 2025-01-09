
$(document).ready(function() {
    $('#rsvpForm').on('submit', function(e) {
        var rsvpStatus = $('input[name="rsvp_status"]:checked').val(); 
        var adultsCount = parseInt($('#adultsInput').val()) || 0; 
        var kidsCount = parseInt($('#kidsInput').val()) || 0; 
      
        if (!rsvpStatus) {
            e.preventDefault(); 
            toastr.error("Please select RSVP"); 
            return;
        }

        if (rsvpStatus === '1'&& adultsCount == 0 && kidsCount == 0) {
            e.preventDefault();
            toastr.error("Please add at least one adult or kid."); 
            return;
        }

        
    });

    //  $("#openGoogel").on("click",function(){
    //  const eventDate= $("eventDate").val();
    //  const eventTime= $("eventTime").val();
    //  const eventName= $("eventName").val();
     
    //   const googleCalendarUrl = "https://calendar.google.com";
    //   window.open(googleCalendarUrl, "_blank");
    //  })

    $("#openGoogle").on("click", function () {
      const eventDate = $("#eventDate").val(); // e.g., "2025-01-10"
      const eventTime = $("#eventTime").val(); // e.g., "10:00"
      const eventName = $("#eventName").val(); // e.g., "Team Meeting"
      console.log(eventDate,eventTime,eventName)
      if (!eventDate || !eventTime || !eventName) {
          alert("Please fill in all event details.");
          return;
      }
  
      // Convert the event date and time to Google Calendar format
      // const eventStart = new Date(`${eventDate}T${eventTime}`).toISOString().replace(/-|:|\.\d+/g, '');
      // const eventEnd = new Date(new Date(`${eventDate}T${eventTime}`).getTime() + 60 * 60 * 1000).toISOString().replace(/-|:|\.\d+/g, ''); // Add 1 hour to the start time
  // alert(eventStart);
      // Google Calendar URL
      const googleCalendarUrl = `https://calendar.google.com/calendar/render?action=TEMPLATE&text=${encodeURIComponent(eventName)}&dates=${eventDate}/${eventDate}&details=&location=&sf=true&output=xml`;
  
      // Open Google Calendar with the pre-filled event
      window.open(googleCalendarUrl, "_blank");
  });
  

    function toggleGuestCount() {
        const isNoSelected = $('#no').is(':checked');
        $('.rsvp_count_member input').prop('disabled', isNoSelected);
        $('.qty-btn-minus, .qty-btn-plus').prop('disabled', isNoSelected);
        $('.rsvp_count_member').css('opacity', isNoSelected ? '0.5' : '1');
        if (isNoSelected) {
            $('#adultsInput').val(0);
            $('#kidsInput').val(0);
        }
    }

    $('input[name="rsvp_status"]').change(function() {
        toggleGuestCount();
    });

    toggleGuestCount();
});

$(document).on('click','.yes_rsvp_btn',function (e) {
    e.preventDefault();
    var adultsCount = parseInt($('#adults').val()) || 0; 
    var kidsCount = parseInt($('#kids').val()) || 0; 

    if (adultsCount == 0 && kidsCount == 0) {
        e.preventDefault();
        toastr.error("Please add at least one adult or kid."); 
        return;
    }

    $('#rsvpYesForm').submit();
  });

  $(document).on('click','.no_rsvp_btn',function (e) {
    e.preventDefault();
    $('#rsvpNoForm').submit();

  })

  $(document).ready(function () {
    $('#rsvp-yes-modal').on('hidden.bs.modal', function () {
        $('#adults').val('0');
        $('#kids').val('0');
        $('.message_to_host').val('');
        $('.firstname').val('');
        $('.lastname').val('');
    });
});
$(document).ready(function () {
  $('#rsvp-no-modal').on('hidden.bs.modal', function () {
      $('#adults').val('0');
      $('#kids').val('0');
      $('.message_to_host').val('');
      $('.firstname').val('');
      $('.lastname').val('');
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


$("#rsvp-yes-modal").on('show.bs.modal', function (e) {
    e.preventDefault();  
});
$("#rsvp-no-modal").on('show.bs.modal', function (e) {
    e.preventDefault();  
});
  $(document).on('click','.check_rsvp_yes',function (e) {
    e.preventDefault();
    var user_id=$(this).data('user_id');
    var event_id=$(this).data('event_id');
    var sync_id=$(this).data('sync_id');
    var modal = $(this).data('bs-target');


    $.ajax({
        url: `${base_url}check_rsvp_status`,
        type: 'GET',
        data: {event_id:event_id,user_id:user_id,sync_id:sync_id},
        success: function (response) {
            var status=response.rsvp_status;
            // console.log(status);
            if(status=="1"){
                toastr.success('You have already done RSVP YES');
            }else if(status=="cohost"){
                toastr.success('You have are a cohost');
            }
                else{
                        $(modal).off('show.bs.modal');  
                        $(modal).modal('show');  
         }

    //      if(status=="cohost"){
    //         toastr.success('You are a cohost');
    //     }else{
    //                 $(modal).off('show.bs.modal');  
    //                 $(modal).modal('show');  
    //  }

        },
        error: function (xhr, status, error) {
        },
        complete: function () {
        }
    });
    
  })

  
  $(document).on('click','.check_rsvp_no',function (e) {
    e.preventDefault();
    var user_id=$(this).data('user_id');
    var event_id=$(this).data('event_id');
    var sync_id=$(this).data('sync_id');
    var modal = $(this).data('bs-target');
    $.ajax({
        url: `${base_url}check_rsvp_status`,
        type: 'GET',
        data: {event_id:event_id,user_id:user_id,sync_id:sync_id},
        success: function (response) {
            var status=response.rsvp_status;
            // console.log(status);
            if(status=="0"){
                toastr.error('You have already done RSVP NO');
            }else{
                        $(modal).off('show.bs.modal');  
                        $(modal).modal('show');  
         }
        },
        error: function (xhr, status, error) {
        },
        complete: function () {
        }
    });
  })

//   latitude
//   logitude
   

const latitude=parseFloat(document.getElementById("event_latitude").value);
const longitutde=parseFloat(document.getElementById("event_logitude").value);
const address=document.getElementById("event_address").value;

  function initMap() {
    // Create the map
    if ((latitude === 0.0 && longitutde === 0.0) || 
    (latitude === 0 && longitutde === 0)) {
    
      console.log("Address to geocode: " + address);
    
      const geocoder = new google.maps.Geocoder();
      geocoder.geocode({ address: address }, function (results, status) {
        if (status === "OK") {
          const location = results[0].geometry.location;
          const lat = location.lat();
          const lng = location.lng();
          
          console.log("Latitude: " + lat);
          console.log("Longitude: " + lng);
           createMap(lat, lng );
         } else {
           alert("Geocode was not successful for the following reason: " + status);
         }
       });
    }else{
      const mapElement = document.getElementById("map");
      mapElement.style.height = "198px";
      mapElement.style.width = "100%";
  
          
      const map = new google.maps.Map(document.getElementById("map"), {
        center: { lat: latitude, lng: longitutde },
        zoom: 15,
      });
  
      // Create the marker
      const marker = new google.maps.Marker({
        position: {  lat: latitude, lng: longitutde},
        map: map,
        title: "test location", // Optional: adds a tooltip on hover
      });
    }
 
  }


  function createMap(lat, lng) {
    console.log(lat+'  '+lng);
    
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