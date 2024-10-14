
$(document).ready(function() {
    $('#rsvpForm').on('submit', function(e) {
        var rsvpStatus = $('input[name="rsvp_status"]:checked').val(); 
        var adultsCount = parseInt($('#adultsInput').val()) || 0; 
        var kidsCount = parseInt($('#kidsInput').val()) || 0; 

        if (adultsCount === 0 && kidsCount === 0) {
            e.preventDefault();
            toastr.error("Please add at least one adult or kid."); 
        }

        if (!rsvpStatus) {
            e.preventDefault(); 
            alert("Please select RSVP"); 
        }
    });
});