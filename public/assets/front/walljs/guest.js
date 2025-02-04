var chartData = document.getElementById("chartData");
if (chartData) {
    var attending = parseInt(chartData.getAttribute("data-attending"));
    var noReply = parseInt(chartData.getAttribute("data-no-reply"));
    var declined = parseInt(chartData.getAttribute("data-declined"));
    var invitations = parseInt(chartData.getAttribute("data-invitation_sent"));

    var options = {
        series: [attending, noReply, declined],
        labels: ["Attending", "No Reply", "Declined"],
        chart: {
            width: 350,
            type: "donut",
        },
        dataLabels: {
            enabled: false,
        },
        plotOptions: {
            pie: {
                donut: {
                    labels: {
                        show: true,
                        total: {
                            show: true,
                            label: "Invites Sent",
                            color: "#0f172a",
                            fontSize: "18px",
                            width: 200,
                            height: 200,
                            fontFamily: "SFProDisplay-Regular",
                            formatter: function (w) {
                                return `${invitations}`;
                            },
                        },
                    },
                },
            },
        },
        colors: ["#0caf60", "#E0E0DE", "#ff3b53"],
        responsive: [
            {
                breakpoint: 480,
                options: {
                    chart: {
                        width: 270,
                    },
                    legend: {
                        show: true,
                    },
                },
            },
        ],
        legend: {
            position: "bottom",
            horizontalAlign: "left",
            offsetY: 0,
            fontSize: "14px",
            width: 500,
            fontFamily: "SFProDisplay-Regular",
            fontWeight: "500",
            formatter: function (seriesName, opts) {
                return (
                    seriesName +
                    '<span style="margin-left: 10px; color: #000;">' +
                    opts.w.globals.series[opts.seriesIndex] +
                    "</span>"
                );
            },
        },
    };

    var chart = new ApexCharts(document.querySelector("#chart1"), options);
    chart.render();
}

$(document).on('click','.see-all-guest-right-btn',function(){
    var event_id=$(this).attr('data-eventId');
    $.ajax({
        url: base_url + "event_wall/fetch_all_invited_user",
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        data: {
            event_id:event_id, 
        },
        success: function (response) {
            if (response.status == 1) {
                $('#guestList').html('');
                $('#guestList').html(response.view);
            } else {
             
            }
        },
        error: function (xhr) {
            alert("Something went wrong. Please try again."); // Handle AJAX errors
        },
    });
});

//   $(document).ready(function () {
//     $(".expand-icon").on("click", function () {
//         const textbox = $("#violation-textbox");
//         textbox.height(textbox.height() === 44 ? 100 : 44);
//     });
// });
