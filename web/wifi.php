<?php

// Show battery status as a chart
// GET Parameters:
// name = iSpindle name

include_once("../assets/common_db.php");
include_once("../assets/common_db_query.php");
/* Include the `../src/fusioncharts.php` file that contains functions to embed the charts.*/
include("../assets/fusioncharts.php");

// Check GET parameters (for now: Spindle name and Timeframe to display)
if(!isset($_GET['hours'])) $_GET['hours'] = 0; else $_GET['hours'] = $_GET['hours'];
if(!isset($_GET['name'])) $_GET['name'] = 'iSpindel000'; else $_GET['name'] = $_GET['name'];
if(!isset($_GET['reset'])) $_GET['reset'] = defaultReset; else $_GET['reset'] = $_GET['reset'];
if(!isset($_GET['days'])) $_GET['days'] = 0; else $_GET['days'] = $_GET['days'];
if(!isset($_GET['weeks'])) $_GET['weeks'] = 0; else $_GET['weeks'] = $_GET['weeks'];

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

list($time, $temperature, $angle, $battery, $interval, $rssi) = getCurrentValues2($conn, $_GET['name']);
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
if ($rssi <= -90)
{
  $quality = 0;
}
else if ($rssi >= -50)
{
  $quality = 100;
}
else
{
  $quality = 2 * ($rssi + 100);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <!--AUTO REFRESH <meta http-equiv="refresh" content="60"> AUTO REFRESH-->
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../assets/img/favicon.png">
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
  <script src="../assets/js/fusioncharts.widgets.js"></script>
  <script src="../assets/js/moment.min.js"></script>
  <script src="../assets/js/moment-timezone-with-data.js"></script>
  <script src="../assets/js/highcharts.js"></script>

<script type="text/javascript">

FusionCharts.ready(function() {
  var chart = new FusionCharts({
      type: 'vled',
      renderAt: 'chart-container',
      width: '250',
      height: '400',
      id: 'myWifi',
      dataFormat: 'json',
      dataSource: {
        "chart": {
          "theme": "fusion",
          "caption": "Qualité wifi de la dernière émission",
          "lowerLimit": "0",
          "upperLimit": "100",
          "lowerLimitDisplay": "",
          "upperLimitDisplay": "",
          "numberSuffix": "%",
          "valueFontSize": "12",
          "showhovereffect": "0",
          "showvalue": "0",
          "ledSize": "3",
          "ledGap": "1",
          "ChartBottomMargin": "20",
        },
        "colorRange": {
          "color": [{
              "minValue": "0",
              "maxValue": "25",
              "code": "#e44a00"
            },
            {
              "minValue": "25",
              "maxValue": "66",
              "code": "#f8bd19"
            },
            {
              "minValue": "66",
              "maxValue": "100",
              "code": "#6baa01"
            }
          ]
        },
        "value": "<?php echo $quality;?>"
      }

    })
    .render();
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
          <li>
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
          <li class="active ">
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
      <div class="content" style="height: 100%;">
        <div class="row" style="height: 100%;">
          <div class="col-12" style="height: 100%;">
            <div class="card card-chart" style="height: 100%;">
              <div class="card-header">
                <div class="row" style="height: 100%;">
                </div>
              </div>
              <div class="card-body" style="height: 100%;">
                <div class="card card-chart" style="height: 100%;">
                <div class="chart-area" style="height: 100%;" id="chart-container">
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

  <!--   Core JS Files   -->
  <!--   <script src="../assets/js/core/popper.min.js"></script>-->
  <script src="../assets/js/core/bootstrap.min.js"></script>
  <script src="../assets/js/plugins/perfect-scrollbar.jquery.min.js"></script>
  <!--  Google Maps Plugin    -->
  <!-- Place this tag in your head or just before your close body tag. -->
  <!-- Chart JS
  <script src="../assets/js/plugins/chartjs.min.js"></script>-->
  <!--  Notifications Plugin
  <script src="../assets/js/plugins/bootstrap-notify.js"></script>-->
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
