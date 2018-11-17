<?php
// Show battery status as a chart
// GET Parameters:
// name = iSpindle name

include_once("../assets/common_db.php");
include_once("../assets/common_db_query.php");
/* Include the `../src/fusioncharts.php` file that contains functions to embed the charts.*/
include("../assets/fusioncharts.php");
if ($conn->connect_error) {
die("Connection failed: " . $conn->connect_error);
}
$sql = "SELECT Gravity FROM Data ORDER BY Timestamp DESC LIMIT 1";
$result = $conn->query($sql);
$row=mysqli_fetch_assoc($result);
$densactu = $row['Gravity'];
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        $densactu = $row['Gravity'];
    }
}

// "Days Ago parameter set?
if(!isset($_GET['days'])) $_GET['days'] = 0; else $_GET['days'] = $_GET['days'];
$daysago = $_GET['days'];
if($daysago == 0) $daysago = defaultDaysAgo;

// query database for available (active) iSpindels
//$sql_q = "SELECT DISTINCT Name FROM Data
  //  WHERE Timestamp > date_sub(NOW(), INTERVAL ".$daysago." DAY)
    //ORDER BY Name";
//$result=mysqli_query($conn, $sql_q) or die(mysqli_error($conn));

// Check GET parameters (for now: Spindle name and Timeframe to display)
if(!isset($_GET['hours'])) $_GET['hours'] = 0; else $_GET['hours'] = $_GET['hours'];
//if(!isset($_GET['name'])) $_GET['name'] = 'iSpindel000'; else $_GET['name'] = $_GET['name'];
if(!isset($_GET['name'])) $_GET['name'] = 'iSpindel000'; else $_GET['name'] = $_COOKIE['ispindel_name'];
if(!isset($_GET['reset'])) $_GET['reset'] = defaultReset; else $_GET['reset'] = $_GET['reset'];
if(!isset($_GET['days'])) $_GET['days'] = 0; else $_GET['days'] = $_GET['days'];
if(!isset($_GET['weeks'])) $_GET['weeks'] = 0; else $_GET['weeks'] = $_GET['weeks'];
if(!isset($_GET['moving'])) $_GET['moving'] = 120; else $_GET['moving'] = $_GET['moving'];

// Calculate Timeframe in Hours
$timeFrame = $_GET['hours'] + ($_GET['days'] * 24) + ($_GET['weeks'] * 168);
if($timeFrame == 0) $timeFrame = defaultTimePeriod;
$tftemp = $timeFrame;
$tfweeks = floor($tftemp / 168);
$tftemp -= $tfweeks * 168;
$tfdays = floor($tftemp / 24);
$tftemp -= $tfdays * 24;
$tfhours = $tftemp;

$time = date("Y-m-d H:i:s");

//$tempe = round($temperature, 2);
//$tilt = round($angle, 2);

//list($time, $tempe, $tilt, $battery) = getCurrentValues($conn, $_GET['name']);
list($time, $tempe, $tilt, $battery, $interval, $rssi) = getCurrentValues2($conn, $_COOKIE['ispindel_name']);
list($angle, $temperature, $dens) = getChartValuesPlato($conn, $_COOKIE['ispindel_name'], $timeFrame, $_GET['reset']);
//list($angle, $temperature, $dens) = getChartValues_ma($conn, $_COOKIE['ispindel_name'], $timeFrame, $_GET['moving'], $_GET['reset']);
//wifi rssi dBm to quality %
//From experience:
//Less than -50dB (-40, -30 and -20) = 100% of signal strength
//From -51 to -55dB= 90%
//From -56 to -62dB=80%
//From -63 to -65dB=75%
//The below is not good enough for Apple devices
//From -66 to 68dB=70%
//From -69 to 74dB= 60%
//From -75 to 79dB= 50%
//From -80 to -83dB=30%
//Windows laptops can work fine on -80dB however with slower speeds
//wifi alert
if ($rssi <= -100)
{
  $wifialert = '<img src="../assets/img/red.png" class="wifipng" id="wifipng" alt="" title="Statut wifi" style="max-width:1.4%; float: left;">';
}
else if ($rssi >= -99 && $rssi <= -66)
{
  $wifialert = '<img src="../assets/img/orange.png" class="wifipng" id="wifipng" alt="" title="Statut wifi" style="max-width:1.4%; float: left;">';
}
else if ($rssi >= -65)
{
  $wifialert = '<img src="../assets/img/green.png" class="wifipng" id="wifipng" alt="" title="Statut wifi" style="max-width:1.4%; float: left;">';
}

/*//Change batterie color sans cookie
$volt = $battery;
if( $volt >= 3.6 ) {
  $cylfillcolor = '#3cff00';
}else if( $volt >= 3 && $volt <= 3.5 ){
  $cylfillcolor = '#ffde01';
}else if( $volt <= 2.9 ){
  $cylfillcolor = '#fe441f';
}else {
  $cylfillcolor = '#3cff00';
}*/
//Change batterie color
$voltalerte = $_COOKIE['batterie'];
$volt = $battery;
if( $volt > $voltalerte ) {
  $cylfillcolor = '#3cff00';
}else if( $volt <= $voltalerte ){
  $cylfillcolor = '#fe441f';
}
/*//Change Temp color
$degre = $tempe;
if( $degre >= 22 ) {
  $gaugeFillColor = '#fe441f';
}else if( $degre >= 18 && $degre <= 21 ){
  $gaugeFillColor = '#3cff00';
}else if( $degre >= 0 && $tempe <= 17 ){
  $gaugeFillColor = '#17f1ff';
}else {
  $gaugeFillColor = '#fe441f';
}*/
//Change Temp color
$tempehaute = $_COOKIE['tempehaute'];
$tempebasse = $_COOKIE['tempebasse'];
$degre = $tempe;
if( $degre >= $tempehaute ) {
  $gaugeFillColor = '#fe441f';
}else if( $degre >= $tempebasse && $degre <= $tempehaute ){
  $gaugeFillColor = '#3cff00';
}else if( $tempe < $tempebasse ){
  $gaugeFillColor = '#17f1ff';
}else {
  $gaugeFillColor = '#fe441f';
}

$imghops = '<div class="hoppng" id="hoppng"><img src="../assets/img/icons-hops-beer.png" alt="" style="max-width:3%; float: right;"></div>';
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <!--AUTO REFRESH <meta http-equiv="refresh" content="60"> AUTO REFRESH-->
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="icon" type="image/png" href="../assets/img/iconedash114.png">
  <link rel="shortcut icon" href="../assets/img/iconedash57.png" />
  <link rel="apple-touch-icon" href="../assets/img/iconedash57.png" />
  <link rel="apple-touch-icon" sizes="72x72" href="../assets/img/iconedash72.png" />
  <link rel="apple-touch-icon" sizes="114x114" href="../assets/img/iconedash114.png" />
  <link rel="apple-touch-icon" sizes="144x144" href="../assets/img/iconedash144.png" />
  <title>
    TITRE
  </title>
  <!--     Fonts and icons     -->
  <link href="https://fonts.googleapis.com/css?family=Poppins:200,300,400,600,700,800" rel="stylesheet" />
  <link href="https://use.fontawesome.com/releases/v5.0.6/css/all.css" rel="stylesheet">
  <!-- Nucleo Icons -->
  <link href="../assets/css/nucleo-icons.css" rel="stylesheet" />
  <!-- CSS Files -->
  <link href="../assets/css/black-dashboard.css?v=1.0.0" rel="stylesheet" />
  <!--   Core JS Files   -->
  <script src="../assets/js/core/jquery.min.js"></script>
  <!-- <script src="../assets/jquery-3.1.1.min.js"></script>-->
  <script src="../assets/js/fusioncharts.js"></script>
  <script src="../assets/js/fusioncharts.theme.fusion.js"></script>
  <script src="../assets/js/fusioncharts.powercharts.js"></script>
  <script src="../assets/js/fusioncharts.charts.js"></script>
  <script src="../assets/js/moment.min.js"></script>
  <script src="../assets/js/moment-timezone-with-data.js"></script>
  <script src="../assets/js/highcharts.js"></script>

<script type="text/javascript">
// chart courbe temperature et angle
$(function ()
{
  var chart;

  $(document).ready(function()
  {
    Highcharts.setOptions({
      global: {
      	timezone: 'Europe/Paris'
      }
    });

    chart = new Highcharts.Chart(
    {
      chart:
      {
        renderTo: 'containerChartTop',
        type: 'spline',
        //zoomType: 'x',
        scrollablePlotArea: {
           minWidth: 700
       }
      },
      title:
      {
        text: '<?php echo $_COOKIE['nom'] , ' [', $_COOKIE['style'] , ']', ' : ', $_COOKIE['ispindel_name'];?>',
        useHTML : 'true'
      },
      subtitle:
      { text: 'Interval : <?php echo gmdate("H:i:s", $interval) , '  / Levure utilisée :  ', $_COOKIE['levure'];?>'
      },
      xAxis:
      {
  type: 'datetime',
  startOnTick: false,
  endOnTick: false,
  showFirstLabel: true,
  showLastLabel: true,
  dateTimeLabelFormats: {
              millisecond: '%H:%M:%S.%L',
              second: '%H:%M:%S',
              minute: '%H:%M',
              hour: '%H:%M',
              day: '%e. %b',
              week: '%e. %b',
              month: '%b \'%y',
              year: '%Y'
                  },
        labels: {
                        formatter:function(){
                            return Highcharts.dateFormat('%H:%M',this.value);
                            }
                },
	gridLineWidth: 1,
	title:
        {
          text: 'heure de la journée'
        }
      },
      yAxis: [
      {
	startOnTick: false,
	endOnTick: false,
  showFirstLabel: false,
  showLastLabel: true,
  minRange: 2,
  min: 1.000,
	max: 1.120,
	title:
        {
          text: 'SG'
        },
	labels:
        {
          align: 'right',
          reserveSpace: true,
          x: 3,
          y: 16,
          formatter: function()
          {
            return this.value +' SG'
          }
        },
	showFirstLabel: false
      },{
         // linkedTo: 0,
	 startOnTick: false,
	 endOnTick: false,
	 min: -5,
	 max: 35,
	 gridLineWidth: 0,
         opposite: true,
         title: {
            text: '°C'
         },
         labels: {
            align: 'right',
            x: -3,
            y: 16,
          formatter: function()
          {
            return this.value +' °C'
          }
         },
	showFirstLabel: false
        }
      ],

      tooltip:
      {
        valueDecimals: 2,
	crosshairs: [false, false],
        formatter: function()
        {
	   if(this.series.name == 'La température') {
           	return '<b>'+ this.series.name +' </b>à '+ Highcharts.dateFormat('%H:%M', new Date(this.x)) +' est de  '+' <b>'+ Math.round(this.y * 10) / 10 +' °C'+' </b>';
	   } else {
	   	return '<b>'+ this.series.name +' </b>à '+ Highcharts.dateFormat('%H:%M', new Date(this.x)) +' est de  '+' <b>'+ Math.round(this.y * 1000) / 1000 +' SG'+' </b>';
	   }
        }
      },
      legend:
      {
        enabled: true
      },
      credits:
      {
        enabled: false
      },
      series:
      [
	  {
          name: 'La densité',
	        color: '#ffd669',
          data: [<?php echo $dens;?>],
          marker:
          {
            symbol: 'diamond',
            enabled: false,
            states:
            {
              hover:
              {
                symbol: 'diamond',
                enabled: true,
                radius: 8
              }
            }
          }
          },
	  {
          name: 'La température',
	        yAxis: 1,
	        color: '#9adfff',
          data: [<?php echo $temperature;?>],
          marker:
          {
            symbol: 'diamond',
            enabled: false,
            states:
            {
              hover:
              {
                symbol: 'diamond',
                enabled: true,
                radius: 8
              }
            }
          }

        }
      ] //series
    });
  });
});

// chart thermometre
FusionCharts.ready(function() {
  var chart = new FusionCharts({
      type: 'thermometer',
      renderAt: 'chart-containerTemp',
      id: 'myThm',
      width: '100%',
      height: '100%',
      dataFormat: 'json',
      dataSource: {
        "chart": {
          "theme": "fusion",
          "lowerLimit": "-2.5",
          "upperLimit": "35.5",
          "numberSuffix": "°C",
          "thmBulbRadius": "35",
          "thmHeight": "160",
          "decimals": "2",
          "adjustTM": "1",
          "tickValueStep": "1",
          "showhovereffect": "0",
          "gaugeFillColor": "<?php echo $gaugeFillColor;?>",//#008ee4
          "thmOriginX": "100",
          "theme": "fusion",
          "chartBottomMargin": "20",
          "majorTMNumber": "7",
          "majorTMHeight": "12",
          "minorTMNumber": "5",
          "minorTMHeight": "7",

          //Major Tick Marks Cosmetics
          "majorTMColor": "#666666",
          "majorTMAlpha": "100",
          "majorTMThickness": "2",

          //Minor Tick Marks Cosmetics
          "minorTMColor": "#666666",
          "minorTMAlpha": "50",
          "minorTMThickness": "2",
        },
        "value": "<?php echo $tempe;?>"
      }
    })
    .render();
});

// chart batterie
FusionCharts.ready(function() {
  var fuelWidget = new FusionCharts({
      type: 'cylinder',
      dataFormat: 'json',
      id: 'fuelMeter-4',
      renderAt: 'chart-containerBatterie',
      width: '100%',
      height: '100%',
      dataSource: {
        "chart": {
          "theme": "fusion",
          "caption": "",
          "id": "color",
          "lowerLimit": "2.5",
          "upperLimit": "4.5",
          "numberSuffix": " volts",
          "majorTMNumber": "6",
          "minorTMNumber": "9",
          "adjustTM": "0",
          "majorTMHeight": "12",
          "majorTMThickness": "1",
          "minorTMHeight": "7",
          "cylfillcolor": "<?php echo $cylfillcolor;?>", //44ff44
          "showValue": "0",
          "chartBottomMargin": "60"
        },
        "value": "<?php echo round($battery, 2);?>"
      }
    }).render();
});

// chart angle
const dataSource = {
  "chart": {
    "captionpadding": "0",
    "origw": "320",
    "origh": "300",
    "plotToolText": "<?php echo round($tilt, 2);?> °",
    "gaugeOuterRadius": "220",
    "gaugeInnerRadius": "225",
    "gaugestartangle": "90",
    "gaugeendangle": "0",
    "showvalue": "0",
    "valuefontsize": "30",
    "majortmnumber": "11",
    "majortmthickness": "2",
    "majortmheight": "23",
    "minortmheight": "15",
    "minortmthickness": "1",
    "minortmnumber": "8",
    "showgaugeborder": "0",
    "theme": "fusion"
  },
  "colorrange": {
    "color": [
      {
        "minvalue": "0",
        "maxvalue": "90",
        "code": "#9adfff"
      }
    ]
  },
  "dials": {
    "dial": [
      {
        "value": "<?php echo $tilt;?>",
        "bgcolor": "#ffd669",
        "basewidth": "8"
      }
    ]
  },
  "annotations": {
    "groups": [
      {
        "items": [
          {
            "type": "text",
            "id": "text",
            "text": "°",
            "x": "$gaugeCenterX",
            "y": "$gaugeCenterY + 40",
            "fontsize": "20",
            "color": "#ffd669"
          }
        ]
      }
    ]
  }
};

FusionCharts.ready(function() {
   var myChart = new FusionCharts({
      type: "angulargauge",
      renderAt: "chart-containerTilt",
      id: 'myTilt',
      width: "100%",
      height: "100%",
      dataFormat: "json",
      dataSource
   }).render();
});


FusionCharts.ready(function() {
   var myChart = new FusionCharts({
      type: "vbullet",
      renderAt: "chart-containerDens",
      width: "36%",
      height: "65%",
      dataFormat: "json",
      dataSource:  {
        "chart": {
          "caption": "",
          "decimals": "3",
          "plotFillColor": "#9adfff",
          "targetColor": "#ff5656",
          "subcaption": "",
          "numbersuffix": " SG",
          "showvalue": "0",
          "ticksonright": "1",
          "theme": "fusion",
          "plottooltext": "$datavalue"
        },
        "colorrange": {
          "color": [
            {
              "minvalue": "1.000",
              "maxvalue": "1.179",
              "code": "#ffcc6f"
            }
          ]
        },
        "value": "<?php echo number_format((float)$densactu, 3, '.', '');?>",
        "target": "<?php echo $_COOKIE['df'];?>"
      }
    }).render();
});
</script>
</head>

<body class="white-content">
  <div class="wrapper">
    <div class="sidebar" data="green">
      <!--
        Tip 1: You can change the color of the sidebar using: data-color="blue | green | orange | red"
    -->
      <div class="sidebar-wrapper">
        <div class="logo">
          <a href="javascript:void(0)" class="simple-text logo-mini">

          </a>
          <a href="javascript:void(0)" class="simple-text logo-normal">
            TITRE
          </a>
        </div>
        <ul class="nav">
          <li class="active ">
            <a href="./dashboard.php">
              <i class="tim-icons icon-tv-2"></i>
              <p>Dashboard</p>
            </a>
          </li>
          <li>
            <a href="./densite.php">
              <i class="tim-icons icon-atom"></i>
              <p>Densité</p>
            </a>
          </li>
          <li>
            <a href="./angle.php">
              <i class="tim-icons icon-compass-05"></i>
              <p>Angle °</p>
            </a>
          </li>
          <li>
            <a href="./temp.php">
              <i class="tim-icons icon-sound-wave"></i>
              <p>Température °C</p>
            </a>
          </li>
          <li>
            <a href="./wifi.php">
              <i class="tim-icons icon-wifi"></i>
              <p>Wifi</p>
            </a>
          </li>
          <li>
            <a href="./settings.php">
              <i class="tim-icons icon-settings"></i>
              <p>Réglages</p>
            </a>
          </li>
        </ul>
      </div>
    </div>
    <div class="main-panel">
      <!-- Navbar -->
      <nav class="navbar navbar-expand-lg navbar-absolute navbar-transparent">
        <div class="container-fluid">
          <div class="navbar-wrapper">
            <div class="navbar-toggle d-inline">
              <button type="button" class="navbar-toggler">
                <span class="navbar-toggler-bar bar1"></span>
                <span class="navbar-toggler-bar bar2"></span>
                <span class="navbar-toggler-bar bar3"></span>
              </button>
            </div>
            <a class="navbar-brand" href="javascript:void(0)">MENU</a>
          </div>
          </div>
      </nav>
      <!-- End Navbar -->
      <div class="content">
        <div class="row">
          <div class="col-12">
            <div class="card card-chart">
              <div class="card-header ">
                <h3 class="card-title cardtop"><i class="text-success"></i><?php echo $wifialert , $imghops;?></h3>
                <div class="row">
                </div>
              </div>
              <div class="card-body cardbodytop">
                <div class="card card-chart">
                <div class="chart-area" id="containerChartTop">
                  <canvas id="container"></canvas>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-lg-3">
            <div class="card card-chart">
              <div class="card-header">
                <h5 class="card-category">Angle</h5>
                <h3 class="card-title"><i class="text-success"></i><img src="../assets/img/clipart-beer-Black.png" alt="" style="max-width:10%; margin-top: -6px;"><?php echo round($tilt, 2);?> °</h3>
              </div>
              <div class="card-body">
                <div class="chart-area chart-area-tilt" id="chart-containerTilt">
                  <canvas id="chartLineGreen"></canvas>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-3">
            <div class="card card-chart">
              <div class="card-header">
                <h5 class="card-category">Température Live</h5>
                <h3 class="card-title"><i class="text-info"><img src="../assets/img/clipart-beer-choco.png" alt="" style="max-width:10%; margin-top: -7px;"></i><?php echo round($tempe, 2);?> °C</h3>
              </div>
              <div class="card-body">
                <div class="chart-area" id="chart-containerTemp">
                  <canvas id="CountryChart"></canvas>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-3">
            <div class="card card-chart">
              <div class="card-header">
                <h5 class="card-category">Batterie</h5>
                <h3 class="card-title"><i class="text-primary"><img src="../assets/img/clipart-beer-Red.png" alt="" style="max-width:10%; margin-top: -7px;"></i><?php echo round($battery, 2);?> volts</h3>
              </div>
              <div class="card-body">
                <div class="chart-area" id="chart-containerBatterie">
                  <canvas id="chartLinePurple"></canvas>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-3">
            <div class="card card-chart">
              <div class="card-header">
                <h5 class="card-category">Densité</h5>
                <h3 class="card-title"><i class="text-primary"><img src="../assets/img/clipart-beer-Ipa.png" alt="" style="max-width:10%; margin-top: -7px;"></i><?php echo number_format((float)$densactu, 3, '.', '');?> SG</h3>
              </div>
              <div class="card-body">
                <div class="chart-area">
                  <div id="chart-containerDens"></div>
                  <canvas id="chartLinePurple"></canvas>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      </div>
        <footer class="footer">
        <div class="container-fluid">
          <div class="copyright">
            NIKKO ©
            <script>
              document.write(new Date().getFullYear())
            </script>
            <a href="./copyright.php">Crédits</a>
          </div>
        </div>
      </footer>
    </div>
  </div>

<!--     <script src="../assets/js/core/popper.min.js"></script>  -->
  <script src="../assets/js/core/bootstrap.min.js"></script>
  <script src="../assets/js/plugins/perfect-scrollbar.jquery.min.js"></script>
  <!--  Google Maps Plugin    -->
  <!-- Place this tag in your head or just before your close body tag.
  <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_KEY_HERE"></script>-->
  <!-- Chart JS
  <script src="../assets/js/plugins/chartjs.min.js"></script>-->
  <!--  Notifications Plugin
  <script src="../assets/js/plugins/bootstrap-notify.js"></script> -->
  <!-- Control Center for Black Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="../assets/js/black-dashboard.min.js?v=1.0.0"></script>

  <script>
    $(document).ready(function() {
      $().ready(function() {
        $sidebar = $('.sidebar');
        $navbar = $('.navbar');
        $main_panel = $('.main-panel');

        $full_page = $('.full-page');

        $sidebar_responsive = $('body > .navbar-collapse');
        sidebar_mini_active = true;
        white_color = false;

        window_width = $(window).width();

        fixed_plugin_open = $('.sidebar .sidebar-wrapper .nav li.active a p').html();



        $('.fixed-plugin a').click(function(event) {
          if ($(this).hasClass('switch-trigger')) {
            if (event.stopPropagation) {
              event.stopPropagation();
            } else if (window.event) {
              window.event.cancelBubble = true;
            }
          }
        });

        $('.fixed-plugin .background-color span').click(function() {
          $(this).siblings().removeClass('active');
          $(this).addClass('active');

          var new_color = $(this).data('color');

          if ($sidebar.length != 0) {
            $sidebar.attr('data', new_color);
          }

          if ($main_panel.length != 0) {
            $main_panel.attr('data', new_color);
          }

          if ($full_page.length != 0) {
            $full_page.attr('filter-color', new_color);
          }

          if ($sidebar_responsive.length != 0) {
            $sidebar_responsive.attr('data', new_color);
          }
        });

        $('.switch-sidebar-mini input').on("switchChange.bootstrapSwitch", function() {
          var $btn = $(this);

          if (sidebar_mini_active == true) {
            $('body').removeClass('sidebar-mini');
            sidebar_mini_active = false;
            blackDashboard.showSidebarMessage('Sidebar mini deactivated...');
          } else {
            $('body').addClass('sidebar-mini');
            sidebar_mini_active = true;
            blackDashboard.showSidebarMessage('Sidebar mini activated...');
          }

          // we simulate the window Resize so the charts will get updated in realtime.
          var simulateWindowResize = setInterval(function() {
            window.dispatchEvent(new Event('resize'));
          }, 180);

          // we stop the simulation of Window Resize after the animations are completed
          setTimeout(function() {
            clearInterval(simulateWindowResize);
          }, 1000);
        });

        $('.switch-change-color input').on("switchChange.bootstrapSwitch", function() {
          var $btn = $(this);

          if (white_color == true) {

            $('body').addClass('change-background');
            setTimeout(function() {
              $('body').removeClass('change-background');
              $('body').removeClass('white-content');
            }, 900);
            white_color = false;
          } else {

            $('body').addClass('change-background');
            setTimeout(function() {
              $('body').removeClass('change-background');
              $('body').addClass('white-content');
            }, 900);

            white_color = true;
          }


        });

        $('.light-badge').click(function() {
          $('body').addClass('white-content');
        });

        $('.dark-badge').click(function() {
          $('body').removeClass('white-content');
        });
      });
    });
  </script>
</body>

</html>
