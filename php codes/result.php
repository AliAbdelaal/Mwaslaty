<?php

header("Content-Type: application/json;charset=utf-8");


require("Go.php");

$servername = "local";
$username = "root";
$password = "password";
$database = "Mwaslaty";


// Create connection
$conn = new mysqli($servername, $username, $password, $database);
$sSQL = 'SET CHARACTER SET utf8';
$conn->query($sSQL);

function make_json_road($path)
{
    if ($path == NULL)
        return array('Checkpoint' => [], 'Cost' => 'NULL');

    if (sizeof($path) == 3 && $path[0][0] == "Station" && $path[1][0] == "Station" && $path[0][2] == "1" && $path[1][2] == "1") {
        return array('Checkpoint' => [array("StationID" => $path[0][1], "BusID" => "NULL")], 'Cost' => "2");
    } else {
        $json_array = array("Checkpoint" => []);

        $past = 0;

        for ($i = 0; $i < sizeof($path) - 1; $i += 2) {
            if ($path[$i][0] == "Bus" && $path[$i + 1][0] == "Station") {
                $json_array["Checkpoint"] = array_merge($json_array["Checkpoint"],
                    [array('StationID' => $path[$i][1], 'BusID' => $path[$i + 1][1])]);

            } elseif (($path[$i][0] == "Station" && $path[$i + 1][0] == "Station") || $path[$i][0] == "Station") {
                $json_array["Checkpoint"] = array_merge($json_array["Checkpoint"],
                    [array('StationID' => $path[$i][1], 'BusID' => "NULL")]);
            }

        }
        $json_array =
            array_merge($json_array,
                array('Cost' => $path[sizeof($path) - 1]));
        return $json_array;
    }
}

$source = $_GET["SID"];
$dist = $_GET["DID"];

//if ($_SERVER["REQUEST_METHOD"] == "POST") {
//    $source = $_POST["SID"];
//    $dist = $_POST["DID"];
//}
$calculate = new Go($conn);

$calculate->setSource($source);
$calculate->setDestination($dist);

$best_road = $calculate->best_cost_road();
$road = $calculate->road();
print_r($best_road);
print_r($road);

//echo(json_encode(make_json_road($best_road), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
