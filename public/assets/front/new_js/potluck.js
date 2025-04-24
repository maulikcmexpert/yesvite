// ======= increement/deecrement rsvp ======
var buttonPlus = $(".qty-btn-plus");
var buttonMinus = $(".qty-btn-minus");

buttonPlus.click(function (e) {
    e.preventDefault();
    e.stopPropagation();

    var $n = $(this).closest(".qty-container").find(".input-qty");
    var currentValue = Number($n.val()) || 0;
    var newValue = currentValue + 1;

    $n.val(newValue);

    if (newValue > 0) {
        $(".error_message_quantity").text("");
    }
});

buttonMinus.click(function (e) {
    e.preventDefault();
    e.stopPropagation();

    var $n = $(this).closest(".qty-container").find(".input-qty");
    var currentValue = Number($n.val()) || 0;

    if (currentValue > 0) {
        var newValue = currentValue - 1;
        $n.val(newValue);

        if (newValue > 0) {
            $(".error_message_quantity").text("");
        }
    }
});

// ==================================================



// ======== potluck-circluler-process
var options = {
  series: [44, 55],
  labels: ['Spoken For', 'Missing Still'],
  chart: {
    width: 350,
    type: 'donut',
  },
  dataLabels: {
    enabled: false
  },
  plotOptions: {
    pie: {
      donut: {
        labels: {
          show: true,
          total: {
            show: true,
            label: 'Potluck Items',
            color: '#0f172a',
            fontSize: '18px',
            fontFamily: 'SFProDisplay-Regular',
            formatter: function (w) {
              return w.globals.seriesTotals.reduce((a, b) => a + b, 0);
            }
          }
        }
      }
    }
  },
  colors: ['#ff3b53', '#0caf60'],
  responsive: [{
    breakpoint: 480,
    options: {
      chart: {
        width: 270
      },
      legend: {
        show: true
      }
    }
  }],
  legend: {
    position: 'bottom',
    horizontalAlign: 'left',
    offsetY: 0,
    fontSize: '14px',
    width: 215,
    fontFamily: 'SFProDisplay-Regular',
    fontWeight: '500',
    formatter: function(seriesName, opts) {

      return seriesName + '<span style="margin-left: 10px; color: #000;">' + opts.w.globals.series[opts.seriesIndex] + '</span>';
    }
  }
};

var chart = new ApexCharts(document.querySelector("#chart"), options);
chart.render();


