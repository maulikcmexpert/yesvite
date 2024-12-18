// ======= increement/deecrement rsvp ======
var buttonPlus  = $(".qty-btn-plus");
var buttonMinus = $(".qty-btn-minus");

var incrementPlus = buttonPlus.click(function() {
var $n = $(this)
.parent(".qty-container")
.find(".input-qty");
$n.val(Number($n.val())+1 );
});

var incrementMinus = buttonMinus.click(function() {
var $n = $(this)
.parent(".qty-container")
.find(".input-qty");
var amount = Number($n.val());
if (amount > 0) {
  $n.val(amount-1);
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


