<?php
    class GTFS{

        var $con;

        function GTFS($con){
            $this->con = $con;
        }

        function stops(){
            $result = mysqli_query($this->con, "SELECT * FROM stops");
            $stops = array();
            while($row = mysqli_fetch_array($result)) {
                $stops[] = array(
                    "stop_code" => $row['stop_code'],
                    "stop_name" => $row['stop_name'],
                    "stop_desc" => $row['stop_desc'],
                    "stop_lat" => $row['stop_lat'],
                    "stop_lon" => $row['stop_lon'],
                    "wheelchair_boarding" => $row['wheelchair_boarding'],
                );
            }
            $output = array("stops"=>$stops);
            header('Content-Type: application/json');
            echo json_encode($output);
        }

        function stop($stop_code){
            $result = mysqli_query($this->con, "SELECT * FROM stops WHERE stop_code = $stop_code");
            $stops = array();
            while($row = mysqli_fetch_array($result)) {
                $stops[] = array(
                    "stop_code" => $row['stop_code'],
                    "stop_name" => $row['stop_name'],
                    "stop_desc" => $row['stop_desc'],
                    "stop_lat" => $row['stop_lat'],
                    "stop_lon" => $row['stop_lon'],
                    "wheelchair_boarding" => $row['wheelchair_boarding'],
                );
            }
            $output = array("stop"=>$stops);
            header('Content-Type: application/json');
            echo json_encode($output);
        }

        function routes(){
            $result = mysqli_query($this->con, "SELECT * FROM routes");
            $stops = array();
            while($row = mysqli_fetch_array($result)) {
                $mode = "";
                switch($row['route_type']){
                    case "2":
                        $mode = "train";
                        break;
                    case "3":
                        $mode = "bus";
                        break;
                    case "0":
                        $mode = "tram";
                        break;
                    default:
                        $mode = "bus";
                        break;
                }
                $stops[] = array(
                    "route_code" => $row['route_id'],
                    "short_name" => $row['route_short_name'],
                    "long_name" => $row['route_long_name'],
                    "route_desc" => $row['route_desc'],
                    "mode" => $mode
                );
            }
            $output = array("routes"=>$stops);
            header('Content-Type: application/json');
            echo json_encode($output);
        }

        function route($route_code){
            $result = mysqli_query($this->con, "SELECT * FROM routes WHERE route_id = '$route_code'");
            $stops = array();
            while($row = mysqli_fetch_array($result)) {
                $mode = "";
                switch($row['route_type']){
                    case "2":
                        $mode = "train";
                        break;
                    case "3":
                        $mode = "bus";
                        break;
                    case "0":
                        $mode = "tram";
                        break;
                    default:
                        $mode = "bus";
                        break;
                }
                $stops[] = array(
                    "route_code" => $row['route_id'],
                    "short_name" => $row['route_short_name'],
                    "long_name" => $row['route_long_name'],
                    "route_desc" => $row['route_desc'],
                    "mode" => $mode
                );
            }
            $output = array("route"=>$stops);
            header('Content-Type: application/json');
            echo json_encode($output);
        }

    }