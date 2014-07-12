<?php
  include 'config.php';
  include 'gtfs.php';
  $doc = false;
  $con = mysqli_connect(Conf::DB_HOST,Conf::DB_USERNAME,Conf::DB_PASSWORD,Conf::DN_NAME);
  if(mysqli_connect_errno()){
    //DIE
    echo "DB Error";
  }
  $gtfs = new GTFS($con,Conf::REALTIME_API,Conf::ATTRACTIONS_API);
  $params = explode(",",$_GET["q"]);
  switch($_GET["method"]){
    case "stops":
      $response = $gtfs->stops();
      break;
    case "stop":
      $response = $gtfs->stop($params[0]);
      break;
    case "routes":
      $response = $gtfs->routes();
      break;
    case "route":
      $response = $gtfs->route($params[0]);
      break;
    case "trip_stops":
      $response = $gtfs->trip_stops($params[0],$params[1]);
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
    <title>BUZZSTOP</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">

  </head>
  <body>
    <div class="container">
      <div class="header">
        <!--<img src="final-ios-icon-80px.png" />
        <h3 class="text-muted">BUZZSTOP - iOS Application</h3>-->
        <div class="media">
          <a class="pull-left" href="#">
            <img src="final-ios-icon-80px.png" />
          </a>
          <div class="media-body">
          <h1 class="media-heading">BUZZSTOP - iOS Application</h1>
          Taking control of your public transport experience.
          </div>
        </div>
      </div>

      <div class="jumbotron">

      </div>

      <div class="row marketing">
        <div class="col-lg-12">
          <h1>BUZZSTOP Public Transport API</h1>
          <p>The iOS application uses a JSON API we built that combines Adelaide Metro GTFS data, Adelaide Metro Real-Time API data and Australian Tourism Data Warehouse API data to create a single point of reference for Public Transport information.</p>
          <p>We have also integrated DPTI's weekly boarding statistic dataset to apply forcasted commuter congestion values to the Real-Time service lists.</p>

          <h3>All Stops</h3>
          <h4>GET /?method=stops</h4>
          <p>Example: <a href="/?method=stops">/?method=stops</a></p>
          <p>List all stops.</p>

          <h3>Single Stop</h3>
          <h4>GET /?method=stop&amp;q=&lt;stop_code&gt;</h4>
          <p>Example: <a href="/?method=stop&q=13282">/?method=stop&amp;q=13418</a></p>
          <p>Get stop details, next real-time service and nearby attractions.</p>

          <h3>Routes</h3>
          <h4>GET /?method=routes</h4>
          <p>Example: <a href="/?method=routes">/?method=routes</a></p>
          <p>List all routes.</p>

          <h3>Route</h3>
          <h4>GET /?method=route&amp;q=&lt;route_code&gt;</h4>
          <p>Example: <a href="/?method=route&q=144">/?method=route&amp;q=144</a></p>
          <p>Get route details.</p>

          <h3>Trip Stops</h3>
          <h4>GET /?method=trip_stops&amp;q=&lt;trip_id&gt;,&lt;stop_code&gt;</h4>
          <p>Example: <a href="/?method=trip_stops&q=130330,13282">/?method=trip_stops&amp;q=130330,13282</a></p>
          <p>List upcomming stops for a specific trip, starting from a specific stop.</p>
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