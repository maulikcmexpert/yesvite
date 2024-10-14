const toastr = require("toastr");

$(document).ready(function() {
    $('#rsvpForm').on('submit', function(e) {
        var adultsCount = parseInt($('#adultsInput').val()) || 0; 
        var kidsCount = parseInt($('#kidsInput').val()) || 0; 

        if (adultsCount === 0 && kidsCount === 0) {
            e.preventDefault();
            toastr.success('Please select at least one adult or kid');
        }
    });
});