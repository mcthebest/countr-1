<?php
$config = json_decode(file_get_contents('config.json'));

session_start();

if ($_SESSION["state"] !== true) {
    $_SESSION["serverwants"] = $_GET["id"];
    header('Location: /?action=login');
}

$URL = $config->api.'/'.$_GET["id"].'/'.$_SESSION["user"]->id.'?auth='.$config->authorization;

$result = file_get_contents($URL, false);
$response = json_decode($result);
if ($response->error == true)
    echo '<script>window.history.back();</script>';
include 'Mobile_Detect.php';
$detect = new Mobile_Detect();

if ($response->premium >= 1 && $response->premium < 3) {
    $refreshingvalue = 30;
} elseif ($response->premium >= 3 && $detect->isMobile()) {
    $refreshingvalue = 20;
} elseif ($response->premium >= 3) {
    $refreshingvalue = 3;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <link rel="apple-touch-icon" sizes="180x180" href="/assets/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/assets/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/assets/favicon/favicon-16x16.png">
    <link rel="manifest" href="/assets/favicon/site.webmanifest">
    <link rel="mask-icon" href="/assets/favicon/safari-pinned-tab.svg" color="#424242">
    <meta name="msapplication-TileColor" content="#2b5797">
    <meta name="theme-color" content="#ffffff">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>
        Countr
    </title>
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
    <!-- CSS Files -->
    <link href="/assets/css/bootstrap.min.css" rel="stylesheet" />
    <link href="/assets/css/now-ui-dashboard.css?v=1.5.0" rel="stylesheet" />
    <link href="/assets/demo/demo.css" rel="stylesheet" />
    <link href="/leaderboard.css" rel="stylesheet" />
</head>

<body class="">
<div class="wrapper ">
    <div class="sidebar" data-color="orange">
        <div class="logo">
            <img src="/assets/img/Countr_Premium.png" class="logo-mini" />
            <a href="/" class="simple-text logo-normal">
                <p style="text-align: center">Countr</p>
            </a>
        </div>
        <div class="sidebar-wrapper" id="sidebar-wrapper">
            <ul class="nav">
                <li>
                    <a href="/dashboard.php">
                        <i class="now-ui-icons design_app"></i>
                        <p>Server List</p>
                    </a>
                </li>
                <li>
                    <a href="/viewguild.php/?id=<?php echo $_GET["id"] ?>">
                        <i class="now-ui-icons files_paper"></i>
                        <p>Server</p>
                    </a>
                </li>
                <li class="active ">
                    <a href="/leaderboard.php/?id=<?php echo $_GET["id"] ?>">
                        <i class="now-ui-icons design_bullet-list-67"></i>
                        <p>Leaderboard</p>
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <div class="main-panel" id="main-panel">
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg navbar-transparent  bg-primary  navbar-absolute">
            <div class="container-fluid">
                <div class="navbar-wrapper">
                    <div class="navbar-toggle">
                        <button type="button" class="navbar-toggler">
                            <span class="navbar-toggler-bar bar1"></span>
                            <span class="navbar-toggler-bar bar2"></span>
                            <span class="navbar-toggler-bar bar3"></span>
                        </button>
                    </div>
                    <p class="navbar-brand">Server</p>
                </div>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navigation" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
                </button>
            </div>
        </nav>
        <!-- End Navbar -->
        <div class="panel-header panel-header-lg">
            <h1 style="color:white; text-align: center"><?php echo $response->guild->name ?></h1>
            <?php
            if ($detect->isMobile()) {

            } else {
                echo '<h3 style="color:white; text-align: center">Leaderboard</h3>';
            }
            ?>
        </div>
        <div class="content">
            <div class="row">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Count Activity</h4>
                            <h6 id="counter"><?php
                                if ($response->premium >= 1 && $response->premium < 3) {
                                    echo 'Refreshing in 30 secs';
                                } elseif ($response->premium >= 3 && $detect->isMobile()) {
                                    echo 'Refreshing in 20 secs';
                                } elseif ($response->premium >= 3) {
                                    echo 'Refreshed: Live';
                                } else {
                                    echo ':D';
                                }
                                ?></h6>
                        </div>
                        <div class="card-body">
                            <div class="table">
                                <div class="table-cell">
                                    <ul class="leader" id="leaderboard">
                                        <?php
                                        $counter = 0;
                                        foreach ($response->leaderboard as &$value) {
                                            $counter = $counter + 1;
                                            echo '<li>
                                            <span class="list_num">'.$counter.'</span>
                                            <h2>'.$value->tag.'<span class="number">'.$value->count.'</span></h2>
                                        </li>';
                                        }
                                        ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
        </div>
        <footer class="footer">
            <div class=" container-fluid ">
                <div class="copyright" id="copyright">
                    &copy;
                    <script>
                        document.getElementById('copyright').appendChild(document.createTextNode(new Date().getFullYear()))
                    </script>, Designed by <a href="https://www.thefabicraft.com" target="_blank">TheFabiCraft</a>. Coded by <a href="https://www.thefabicraft.com" target="_blank">TheFabiCraft</a> for <a href="https://www.promise.solutions" target="_blank">Promise Solutions</a>.
                </div>
            </div>
        </footer>
    </div>
</div>
<!--   Core JS Files   -->
<script src="/assets/js/core/jquery.min.js"></script>
<script src="/assets/js/core/popper.min.js"></script>
<script src="/assets/js/core/bootstrap.min.js"></script>
<script src="/assets/js/plugins/perfect-scrollbar.jquery.min.js"></script>
<!--  Google Maps Plugin    -->
<script src="https://maps.googleapis.com/maps/api/js?key=YOUR_KEY_HERE"></script>
<!-- Chart JS -->
<script src="/assets/js/plugins/chartjs.min.js"></script>
<!--  Notifications Plugin    -->
<script src="/assets/js/plugins/bootstrap-notify.js"></script>
<!-- Control Center for Now Ui Dashboard: parallax effects, scripts for the example pages etc -->
<script src="/assets/js/now-ui-dashboard.min.js?v=1.5.0" type="text/javascript"></script>
<script src="/assets/demo/demo.js"></script>

<?php
if ($response->premium >= 1) {
    echo "<script>
setInterval(() => {
  fetch('https://api.promise.solutions/countr-analytics/leaderboard/".$_GET['id']."')
  .then((response) => {
    return response.json();
  })
  .then((leaderboard) => {
    let newleaderboard = '';  
    let counter = 0;
    leaderboard.leaderboard.forEach(element => {
        counter++;
        newleaderboard += '<li><span class=\"list_num\">' + counter + '</span><h2>' + element.tag + '<span class=\"number\">' + element.count + '</span></h2></li>';
    });
    document.getElementById('leaderboard').innerHTML = newleaderboard;
  });
}, ".$refreshingvalue."000);
</script>";
}
if ($response->premium >= 1 && $response->premium < 3) {
    echo "<script>
let startvalue = ".$refreshingvalue."
setInterval(() => {
    if (startvalue === 0) startvalue = ".$refreshingvalue."
    startvalue--;
    document.getElementById('counter').innerHTML = 'Refreshing in '+ startvalue + ' secs'
}, 1000);
</script>";
}
if ($response->premium >= 3 && $detect->isMobile()) {
    echo "<script>
let startvalue = ".$refreshingvalue."
setInterval(() => {
    if (startvalue === 0) startvalue = ".$refreshingvalue."
    startvalue--;
    document.getElementById('counter').innerHTML = 'Refreshing in '+ startvalue + ' secs'
}, 1000);
</script>";
}
?>
</body>

</html>