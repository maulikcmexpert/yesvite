
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