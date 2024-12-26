var options = {
    series: [30, 10, 5],
    labels: ['Attending', 'No Reply', 'Desclined'],
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
              label: 'Invites Sent',
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
    colors: ['#0caf60', '#E0E0DE', '#ff3b53'],
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
  
  var chart = new ApexCharts(document.querySelector("#chart1"), options);
  chart.render();