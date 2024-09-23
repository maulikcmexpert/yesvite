$(document).on("click", ".design-card", function () {
    var url = $(this).data("url");
    var template = $(this).data("template");
    $(".modal-design-card").empty();
    $(".modal-design-card").load(url, function (response, status, xhr) {
        if (status == "error") {
            $(".modal-design-card").html("<p>Error loading view.</p>");
        }
        var beforeTo = eventData.event_date.split('To')[0];
        console.log(beforeTo);
        var date = new Date(beforeTo);
        var formattedDate = date.toLocaleDateString("en-US", {
            month: "long",
            day: "numeric",
            // year: "numeric",
        });

        var year = date.getFullYear();

        var monthNames = [
            "January",
            "February",
            "March",
            "April",
            "May",
            "June",
            "July",
            "August",
            "September",
            "October",
            "November",
            "December",
        ];
        var month = monthNames[date.getMonth()];
        var dayOfMonth = date.getDate();
        var dayNames = [
            "Sunday",
            "Monday",
            "Tuesday",
            "Wednesday",
            "Thursday",
            "Friday",
            "Saturday",
        ];
        var dayOfWeek = dayNames[date.getDay()];
        var formattedTime = convertTo12HourFormat(eventData.start_time);

        $(".titlename").text(eventData.hostedby);
        $(".event_name").text(eventData.event_name);
        $(".event_date").text(formattedDate);
        $(".event_address").text(eventData.event_location);
        if(eventData.rsvp_end_time != undefined && eventData.rsvp_end_time != ""){
            $(".event_time").text(eventData.start_time +" to "+eventData.rsvp_end_time);
        }else{
            $(".event_time").text(eventData.start_time);

        }

        if (template == "template_5") {
            var e_year =
                '<span class="e_month">' +
                month +
                "</span>" +
                dayOfMonth +
                '<span class="e_year">' +
                year +
                "</span>";
            $(".e_date").html(e_year);
            $(".day").text(dayOfWeek);
        }
        if (template == "template_2") {
            var e_date= (month.substring(0,3)).toUpperCase()+', '+year+" "+"<span>"+dayOfWeek+"</span>";
            $('.e_date').html(e_date);
            // $('.titlename').text(eventData.event_name);
        }
        if (template == "template_3") {
            var e_date= (month.substring(0,3)).toUpperCase()+', '+year+" "+"<span>"+dayOfWeek+"</span>";
            $('.e_date').html(e_date);
            // $('.titlename').text(eventData.event_name);
        }
        if (template == "template_6") {
            var e_date_time= dayOfWeek+', '+dayOfMonth+' '+month+' '+year+' at '+ eventData.start_time
            $('.e_date_time').text(e_date_time);
            $('.titlename').text(eventData.event_name);
        }

        if (template == "template_7") {
            $('.month').text(month);
            $('.e_date').text(year);
            $('.e_time').text(eventData.start_time);
        }

        if (template == "template_8") {
            $('.month').text((month.substring(0,3)).toUpperCase());
            $('.e_date').text(dayOfMonth);
            $('.year').text(year); 
        }

        if (template == "template_9") {
            $('.titlename').text(eventData.event_name);
            $('.e_date').text(dayOfMonth+'/'+(date.getMonth()+1)+'/'+year);
            $('.year').text(year); 
        }

        if (template == "template_10") {
            $('.e_details').text(dayOfWeek+' '+month+' '+dayOfMonth+', '+year+' '+ eventData.start_time+' AT '+ eventData.address);
        }

        if (template == "template_11") {
            $('.e_details').text(dayOfWeek+' '+month+' '+dayOfMonth+', '+year+' '+ eventData.start_time+' AT '+ eventData.address);
        }
        $("#exampleModal").modal("show");
    });
});
