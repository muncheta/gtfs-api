<?php
  include 'config.php';
  include 'gtfs.php';
  $doc = false;
  $con = mysqli_connect(Conf::DB_HOST,Conf::DB_USERNAME,Conf::DB_PASSWORD,Conf::DN_NAME);
  if(mysqli_connect_errno()){
    //DIE
    echo "DB Error";
  }
  $gtfs = new GTFS($con);
  $params = explode(",",$_GET["q"]);
  switch($_GET["method"]){
    case "stops":
      $stops = $gtfs->stops();
      break;
    case "stop":
      $stops = $gtfs->stop($params[0]);
      break;
    case "routes":
      $stops = $gtfs->routes();
      break;
    case "route":
      $stops = $gtfs->route($params[0]);
      break;
    default:
      $doc = true;
      break;
  }
  if($doc){
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>metroNow - GTFS API</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">

  </head>
  <body>
    <div class="container">
      <div class="header">
        <ul class="nav nav-pills pull-right">
          <li class="active"><a href="#">Home</a></li>
          <li><a href="#">About</a></li>
          <li><a href="#">Contact</a></li>
        </ul>
        <h3 class="text-muted">metroNow</h3>
      </div>

      <div class="jumbotron">
        <h1>GTFS API</h1>
      </div>

      <div class="row marketing">
        <div class="col-lg-12">
          <h4>All Stops</h4>
          <h3>GET /?method=stops</h3>
          <p>Example: <a href="/?method=stops">/?method=stops</a></p>
          <p>List all stops.</p>

          <h4>Single Stop</h4>
          <h3>GET /?method=stop&amp;q=&lt;stop_code&gt;</h3>
          <p>Example: <a href="/?method=stop&q=10005">/?method=stop&amp;q=10005</a></p>
          <p>Get stop details.</p>

          <h4>Routes</h4>
          <h3>GET /?method=routes</h3>
          <p>Example: <a href="/?method=routes">/?method=routes</a></p>
          <p>List all routes.</p>

          <h4>Route</h4>
          <h3>GET /?method=route&amp;q=&lt;route_code&gt;</h3>
          <p>Example: <a href="/?method=route&q=100">/?method=route&amp;q=100</a></p>
          <p>Get route details.</p>
        </div>
      </div>

      <div class="footer">
        <p>© Moonshine Laboratory 2014</p>
      </div>

    </div>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>
<?php
  }
  mysqli_close($con);
?>