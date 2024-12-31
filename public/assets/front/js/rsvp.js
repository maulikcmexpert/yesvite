
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
    var all_notification="";
    $(".notifications").each(function () {
        if ($(this).is(":checked")) {
            alert();
            all_notification="1"
        }
    });
    console.log(all_notification);
return;
    $.ajax({
        url: `${base_url}check_rsvp_status`,
        type: 'GET',
        data: {event_id:event_id,user_id:user_id,sync_id:sync_id,all_notification:all_notification},
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


   
  function initMap() {
    const map = new google.maps.Map(document.getElementById("map"), {
      center: { lat: -34.397, lng: 150.644 },
      zoom: 8,
    });
  }

  initMap();