
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
    $('#rsvpYesForm').submit();
  });

  $(document).on('click','.no_rsvp_btn',function (e) {
    e.preventDefault();
    $('#rsvpNoForm').submit();

  })
  function checkRsvpStatus(event_id,user_id,callback){
    $.ajax({
        url: `${base_url}check_rsvp_status`,
        type: 'GET',
        data: {event_id:event_id,user_id:user_id},
        success: function (response) {
            var status=response.rsvp_status;
            console.log(status);
            callback(status);
        },
        error: function (xhr, status, error) {
            callback(null); // Pass null to indicate an error

        },
        complete: function () {
        }
    });
}

async function checkRsvpStatusAsync(event_id, user_id) {
    try {
        const status = await checkRsvpStatus(event_id, user_id); // Call the function that returns a Promise
        if (status == "1") {
            console.log("1");
        } 
        else if(status=="0"){
            console.log("0");
        }
        else {
            console.log("");
        }
    } catch (error) {
        console.error("Error occurred while checking RSVP status:", error);
    }
}

  $(document).on('click','.check_rsvp_yes',function (e) {
    var user_id=$(this).data('user_id');
    var event_id=$(this).data('event_id');
    var modal = $(this).data('bs-target');

    var current_status=checkRsvpStatusAsync(event_id, user_id);

    console.log(current_status);
  })

  
  $(document).on('click','.check_rsvp_no',function (e) {
    var user_id=$(this).data('user_id');
    var event_id=$(this).data('event_id');
    var modal = $(this).data('target');

  })


