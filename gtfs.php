<?php
    class GTFS{

        var $con;
        var $realtime_url;
        var $attractions_url;

        function GTFS($con, $realtime_url,$attractions_url){
            $this->con = $con;
            $this->realtime_url = $realtime_url;
            $this->attractions_url = $attractions_url;
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
            //Get Stop Details
            $result = mysqli_query($this->con, "SELECT * FROM stops WHERE stop_code = '$stop_code'");
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
            //Get Stop Next Realtime Services
            $realtime_services = json_decode($this->realtime_services($stop_code));
            $next_services = array();

            //Reference Time
            $comparison_json_timestamp = $realtime_services->StopMonitoringDelivery[0]->ResponseTimestamp;
            preg_match( '/([\d]{10})/', $comparison_json_timestamp, $comparison_matches); // gets just the first 9 digits in that string
            $comparison_unix_timestamp = $comparison_matches[0];
            $comparison_time = date( 'g:i A', $comparison_unix_timestamp);

            foreach($realtime_services->StopMonitoringDelivery[0]->MonitoredStopVisit as $service){
                //print_r($service);
                preg_match( '/([\d]{10})/', $service->MonitoredVehicleJourney->MonitoredCall->LatestExpectedArrivalTime, $matches);
                $arrival_timestamp = $matches[0];
                $expected_arrival_time = date( 'g:i', $arrival_timestamp);
                //$server_time = date( 'g:ia', $comparison_unix_timestamp);
                $diff_secs = $arrival_timestamp - $comparison_unix_timestamp;
                $arrival_minutes = number_format($diff_secs/60,0);

                $trip_id = $service->MonitoredVehicleJourney->FramedVehicleJourneyRef->DatedVehicleJourneyRef;
                $capacity = $this->service_capacity($trip_id,$stop_code);
                $next_services[] = array(
                    "route_code" => $service->MonitoredVehicleJourney->LineRef->Value,
                    "destination" => $service->MonitoredVehicleJourney->DestinationName[0]->Value,
                    "arrives" => $arrival_minutes,
                    "trip" => $trip_id,
                    "capacity" => $capacity
                );   
            }
            $attraction = simplexml_load_string($this->stop_attractions($stops[0]["stop_lat"],$stops[0]["stop_lon"]));
            //print_r($attraction);
            $stop_attractions = array();
            foreach($attraction->products->product_record as $attraction){
                $stop_attractions[] = array(
                    "name" => (string)$attraction->product_name,
                    "description" => (string)$attraction->product_description,
                    "product_image" => (string)$attraction->product_image,
                    "distance" => (string)$attraction->distance_to_location
                );
            }
            $output = array("stop"=>$stops, "next_services"=>$next_services, "attractions"=>$stop_attractions);
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
            while($row = mysqli_fetch_array($result)){
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

        function trip_stops($trip_id, $current_stop){
            //SELECT * FROM stop_times WHERE trip_id = 81853
            $query = "SELECT st.stop_sequence, s.* from stop_times st
            JOIN stops s ON s.stop_id = st.stop_id
            WHERE st.trip_id = $trip_id
            ORDER BY st.stop_sequence ASC";

            $result = mysqli_query($this->con, $query);
            $stops = array();
            $stops_basic = array();
            while($row = mysqli_fetch_array($result)) {
                $stops[] = array(
                    "stop_code" => $row['stop_code'],
                    "stop_name" => $row['stop_name'],
                    "stop_desc" => $row['stop_desc'],
                    "stop_lat" => $row['stop_lat'],
                    "stop_lon" => $row['stop_lon'],
                    "wheelchair_boarding" => $row['wheelchair_boarding'],
                );
                $stops_basic[] = $row['stop_code'];
            }
            $stop_index = array_search($current_stop, $stops_basic);
            //print_r($current_stop);
            //print_r($stops_basic);
            //print_r($stop_index);
            $upcomming_stops = array_splice($stops, $stop_index);
            //print_r($upcomming_stops);
            $output = array("stops"=>$upcomming_stops);
            header('Content-Type: application/json');
            echo json_encode($output);
        }

        function service_capacity($trip_id,$stop_code){
            //$trip_id = 10254;
            //$stop_code = 13278;
            $query = "SELECT AVG(boardings) as average_boardings FROM boardings WHERE trip_id = $trip_id AND stop_id = $stop_code GROUP BY trip_id, stop_id";
            $result = mysqli_query($this->con, $query);
            $average_boardings = 0.0;
            while($row = mysqli_fetch_array($result)){
                $average_boardings = $row["average_boardings"];
            }
            $daily_average = $average_boardings/5;

            //This is smudged because we have insuffiencet boarding data for all stops and trips. The dataset was too big to import.
            $daily_average = rand(1, 20);
            $capacity = "Low";
            if($daily_average > 15){
                $capacity = "High";
            }else{
                if($daily_average > 8){
                    $capacity = "Medium";
                }else{
                    $capacity = "Low";
                }
            }
            return $capacity;
        }

        function realtime_services($stop_code){
            $params = "StopMonitoringDetailLevel=normal&MaximumStopVisits=30&PreviewInterval=120";
            $params = $params."&MonitoringRef=".$stop_code;
            $curl = curl_init();
            curl_setopt_array( $curl, array(
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_URL => $this->realtime_url.$params,
                CURLOPT_TIMEOUT => 200)
            );
            $output = curl_exec( $curl );
            $response_code = curl_getinfo( $curl, CURLINFO_HTTP_CODE );
            $response_content_type = curl_getinfo( $curl, CURLINFO_CONTENT_TYPE );
            curl_close($curl);
            //print_r($output);
            return $output;
        }

        function stop_attractions($lat,$lon){
            $params = "&dist=1&cats=ATTRACTION";
            $params = $params."&latlong=".$lat.",".$lon;
            $curl = curl_init();
            curl_setopt_array( $curl, array(
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_URL => $this->attractions_url.$params,
                CURLOPT_TIMEOUT => 200)
            );
            $output = curl_exec( $curl );
            $response_code = curl_getinfo( $curl, CURLINFO_HTTP_CODE );
            $response_content_type = curl_getinfo( $curl, CURLINFO_CONTENT_TYPE );
            curl_close($curl);
            //print_r($output);
            return $output;
        }

    }