
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

  $(document).on('click','.check_rsvp_yes',function (e) {
    var user_id=$(this).data('user_id');
    var event_id=$(this).data('event_id');
    checkRsvpStaus(event_id,user_id);
  })

  
  $(document).on('click','.check_rsvp_no',function (e) {
    var user_id=$(this).data('user_id');
    var event_id=$(this).data('event_id');
  })

  function checkRsvpStaus(event_id,user_id){
        $.ajax({
            url: `${base_url}check_rsvp_status`,
            type: 'GET',
            data: {event_id:event_id,user_id:user_id},
            success: function (response) {
            if(response.rsvp_status=="1"){
              toastr.success('You have already done rsvp YES');  
            }
            else if(response.rsvp_status=="0"){
                toastr.success('You have already done rsvp NO');  
            }
            else{
            

            }

            },
            error: function (xhr, status, error) {

            },
            complete: function () {
            }
        });
  }
