<script>
  $(function() {

    $.ajax({
      headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
          "content"
        ),
      },
      method: "POST",
      url: "{{route('getEventData')}}",

      success: function(output) {
        if (output != "") {
          $("#upcomingEvent").html(output);
        } else {
          $("#upcomingEvent").html("No Upcoming Events");
        }
      },
    });



    $("#datepicker").datepicker({
      firstDay: 1,
      minDate: 0
    })

    $(document).on('change', '#datepicker', function(e) {
      var selectedDate = $(this).val();
      $.ajax({
        headers: {
          "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
            "content"
          ),
        },
        method: "POST",
        url: "{{route('getEventData')}}",

        data: {
          date: selectedDate
        },
        success: function(output) {
          if (output != "") {
            $("#upcomingEvent").html(output);
          } else {
            $("#upcomingEvent").html("No Upcoming Events");
          }
        },
      });
    });
  });
</script>