<?php
// Set the Cookie for filter
  if(isset($_POST['style']) || isset($_POST['nom']) || isset($_POST['ispindel_name']) || isset($_POST['levure']) || isset($_POST['tempebasse']) || isset($_POST['tempehaute']) || isset($_POST['df']) || isset($_POST['batterie']))
{
    setcookie('style', $_POST['style'], time() + 365*24*3600, null, null, false, true);
    setcookie('nom', $_POST['nom'], time() + 365*24*3600, null, null, false, true);
    setcookie('levure', $_POST['levure'], time() + 365*24*3600, null, null, false, true);
    setcookie('tempebasse', $_POST['tempebasse'], time() + 365*24*3600, null, null, false, true);
    setcookie('tempehaute', $_POST['tempehaute'], time() + 365*24*3600, null, null, false, true);
    setcookie('df', $_POST['df'], time() + 365*24*3600, null, null, false, true);
    setcookie('batterie', $_POST['batterie'], time() + 365*24*3600, null, null, false, true);
    setcookie('ispindel_name', $_POST['ispindel_name'], time() + 365*24*3600, null, null, false, true);

    header('Location: settings.php');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
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
  <script src="../assets/js/jquery.blockUI.js"></script>
  <!--   Core JS Files   -->
  <script src="../assets/js/core/jquery.min.js"></script>
  <!-- <script src="../assets/jquery-3.1.1.min.js"></script>-->
  <script type="text/javascript" src="//cdn.fusioncharts.com/fusioncharts/latest/fusioncharts.js"></script>
  <script type="text/javascript" src="//cdn.fusioncharts.com/fusioncharts/latest/themes/fusioncharts.theme.fusion.js"></script>
  <script src="../assets/js/moment.min.js"></script>
  <script src="../assets/js/moment-timezone-with-data.js"></script>
  <script src="../assets/js/highcharts.js"></script>


</head>

<body class="white-content">
  <?php
  include("../assets/common_db.php");
  include("../assets/common_db_query.php");

  // "Days Ago parameter set?
//  if(!isset($_GET['days'])) $_GET['days'] = 0; else $_GET['days'] = $_GET['days'];
//  $daysago = $_GET['days'];
//  if($daysago == 0) $daysago = defaultDaysAgo;

  // query database for available (active) iSpindels
  //$sql_q = "SELECT DISTINCT Name FROM Data
    //  WHERE Timestamp > date_sub(NOW(), INTERVAL ".$daysago." DAY)
    //  ORDER BY Name";
  //$result=mysqli_query($conn, $sql_q) or die(mysqli_error($conn));

$deleteispindel = $_COOKIE['ispindel_name'];
  // query database for available (active) iSpindels
  $sql_q = "SELECT DISTINCT Name FROM Data
      ORDER BY Name";
  $result=mysqli_query($conn, $sql_q) or die(mysqli_error($conn));
  /* Include the `../src/fusioncharts.php` file that contains functions to embed the charts.*/
  // fetch mysql table rows
  if (isset($_POST['valider'])) {
  $hashed_password = password_hash("MONPASSWORD",PASSWORD_DEFAULT, ["cost" => 12]);
  if(password_verify($_POST["mdp1"],$hashed_password)){
    //$sql2 = "TRUNCATE TABLE Data";
    $sql2 = "DELETE FROM Data WHERE Name = '$deleteispindel'";

    $result = mysqli_query($conn, $sql2) or die("Selection Error " . mysqli_error($conn));
    echo '<script language="javascript">';
    echo '$(document).ready(function() {
      $.blockUI();

      setTimeout(function() {
          $.unblockUI({
              onUnblock: function(){ alert("Données précédent brassin supprimées"); }
          });
      }, 10);
  }); ';
    echo '</script>';

  }  elseif (empty($_POST['mdp1'])){
      echo '<script language="javascript">';
      echo '$(document).ready(function() {
        $.blockUI();

        setTimeout(function() {
            $.unblockUI({
                onUnblock: function(){ alert("Entrez votre mot de passe"); }
            });
        }, 10);
    }); ';
      echo '</script>';
    } else {
      echo '<script language="javascript">';
      echo '$(document).ready(function() {
        $.blockUI();

        setTimeout(function() {
            $.unblockUI({
                onUnblock: function(){ alert("Mauvais mot de passe"); }
            });
        }, 10);
    }); ';
      echo '</script>';
    }
  }
  mysqli_close($conn);

  ?>
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
          <li>
            <a href="./wifi.php">
              <i class="tim-icons icon-wifi"></i>
              <p>Wifi</p>
            </a>
          </li>
          <li class="active ">
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
          <div class="col-md-8">
            <form method="post"action ="#">
            <div class="card">
              <div class="card-header">
                <h5 class="title">Réglages type de brassin<i class="text-primary"><img src="../assets/img/lighglass-0.png" alt="" style="max-width:5%; margin-top: -7px; float: inline-end;"></i></h5>
              </div>
              <div class="card-body">
                  <div class="row">
                    <div class="col-md-3 pr-md-1">
                      <div class="form-group">
                        <label>Choix du iSpindle à afficher</label>
                        <select class="form-control" id="nomIspindle" name = "ispindel_name" title="Choose one of the following...">
                              <?php
                                  while($row = mysqli_fetch_assoc($result) )
                                  {
                                      ?>
                                      <option style="display:none"><?php echo $_COOKIE['ispindel_name']?></option>
                                      <option value = "<?php echo ($row['Name'])?>">
                                      <?php echo($row['Name']); }?>
                              </option>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-3 pr-md-1">
                      <div class="form-group">
                        <label>Style de bière</label>
                        <input type="text" class="form-control" id="style" name="style" placeholder="IPA, Porter, Smash..." value="<?php if(isset($_COOKIE['style'])) echo $_COOKIE['style'];?>" {$style}/>
                      </div>
                    </div>
                    <div class="col-md-3 px-md-1">
                      <div class="form-group">
                        <label>Nom de la bière</label>
                        <input type="text" class="form-control" id="nom" name="nom" placeholder="" value="<?php if(isset($_COOKIE['nom'])) echo $_COOKIE['nom'];?>" {$nom}/>
                      </div>
                    </div>
                    <div class="col-md-3 pl-md-1">
                      <div class="form-group">
                        <label>Nom de la levure</label>
                        <input type="text" class="form-control" id="levure" name="levure" placeholder="WLP001, Wyeast, US-05..." value="<?php if(isset($_COOKIE['levure'])) echo $_COOKIE['levure'];?>" {$levure}/>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6 pr-md-1">
                      <div class="form-group">
                        <label>Température de fermentation - Valeur basse / °C</label>
                        <input type="text" class="form-control" id="tempebasse" name="tempebasse" placeholder="18" value="<?php if(isset($_COOKIE['tempebasse'])) echo $_COOKIE['tempebasse'];?>" {$tempebasse}/>
                      </div>
                    </div>
                    <div class="col-md-6 pl-md-1">
                      <div class="form-group">
                        <label>Température de fermentation - Valeur haute / °C</label>
                        <input type="text" class="form-control" id="tempehaute" name="tempehaute" placeholder="21" value="<?php if(isset($_COOKIE['tempehaute'])) echo $_COOKIE['tempehaute'];?>" {$tempehaute}/>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label>Densitée finale souhaitée / SG</label>
                        <input type="text" class="form-control" id="df" name="df" placeholder="1.010" value="<?php if(isset($_COOKIE['df'])) echo $_COOKIE['df'];?>" {$df}/>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-4 pr-md-1">
                      <div class="form-group">
                        <label>Alerte recharger batterie / volts</label>
                        <input type="text" class="form-control" id="batterie" name="batterie" placeholder="2.9" value="<?php if(isset($_COOKIE['batterie'])) echo $_COOKIE['batterie'];?>" {$batterie}/>
                      </div>
                    </div>
                  <!--<div class="col-md-4 px-md-1">
                      <div class="form-group">
                        <label>Country</label>
                        <input type="text" class="form-control" id="nom" placeholder="Country" value=" {$style}/>
                      </div>
                    </div>
                    <div class="col-md-4 pl-md-1">
                      <div class="form-group">
                        <label>Postal Code</label>
                        <input type="number" class="form-control" id="nom" placeholder="ZIP Code" value="" {$style}/>
                      </div>
                    </div> -->
                  </div>
                </form>
              </div>
              <div class="card-footer">
                <input type="submit" class="btn btn-fill btn-primary"></input>
              </div>
            </div>
          </div>
          </form>
        </div>
        <div class="row">
          <div class="col-md-8">
            <form method="post" name="frmSaisie" id="frmSaisie" action ="">
            <div class="card">
              <div class="card-header">
                <h5 class="title">Réglages Base de donnée<i class="text-primary"><img src="../assets/img/GlassDark512.png" alt="" style="max-width:5%; margin-top: -7px; float: inline-end;"></i></h5>
              </div>
              <div class="card-body">
                  <div class="row">
                    <div class="col-md-4 pr-md-1">
                      <div class="form-group">
                        <label for="password"><img src="../assets/img/warning.png" alt="" style="max-width:7%; margin-top: -7px"> Effacer data brassin <img src="../assets/img/warning.png" alt="" style="max-width:7%; margin-top: -7px"></label>
                        <input type="password" class="form-control" id="mdp1" name="mdp1" placeholder="PASSWORD" value=""/>
                      </div>
                    </div>
                  </div>
                </form>
              </div>
              <div class="card-footer">
              <div class="row">
                <input type="submit" value="VALIDER" class="btn btn-fill btn-primary btbdd" id="valider" name="valider"></input>
                <form method='post' action='./csvexport.php'>
                <input type='submit' value='Exporter BDD (CSV)' class="btn btn-fill btn-primary btncsv" name='Export'></input>
                </form>
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

    //password check form
  /*  var variableRecuperee = document.getElementById('variablePassword').value;
    function validation(f) {
      if (f.mdp1.value == '') {
        alert('Veuillez entrer votre mot de passe');
        f.mdp1.focus();
        return false;
        }
      else if (f.mdp1.value != atob(variableRecuperee)) {
        alert('Ce n\'est pas le bon mot de passe!');
        f.mdp1.focus();
        return false;
        }
      else if (f.mdp1.value == atob(variableRecuperee)) {
        alert('Données précédent brassin supprimées');
        return true;
        }
      else {
        f.mdp1.focus();
        return false;
        }
      }*/


  </script>
</body>

</html>
