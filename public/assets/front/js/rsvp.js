$(document).ready(function() {
    $('#rsvpForm').on('submit', function(e) {
        var adultsCount = parseInt($('#adultsInput').val()) || 0; 
        var kidsCount = parseInt($('#kidsInput').val()) || 0; 

        if (adultsCount === 0 && kidsCount === 0) {
            e.preventDefault();
            alert("Please add at least one adult or kid."); 
        }
    });
});