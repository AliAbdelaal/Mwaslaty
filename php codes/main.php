<?php
/**
 * Created by PhpStorm.
 * User: ali
 * Date: 4/19/17
 * Time: 4:29 AM
 */

require("Go.php");

/**
 * @param $station Station
 */


$servername = "localhost";
$username = "root";
$password = "password";
$database = "Mwaslaty";


// Create connection
$conn = new mysqli($servername, $username, $password, $database);
$sql= 'SET CHARACTER SET utf8';
$conn->query($sql);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


echo nl2br("Connected successfully \n");

function print_station($station)
{
    echo 'Station : '.$station->getName();
    echo nl2br("\n");
    echo 'ID = '.$station->getId();
    echo ',longitude'.$station->getLongitude();
    echo ',latitude'.$station->getLatitude();
    echo ',metro='.($station->getMetro()==TRUE?'yes':'no');
    echo ',bus='.($station->getBus()==TRUE?'yes':'no');
    echo nl2br("\n");

}

/**
 * @param $bus Bus*/

function print_bus($bus)
{
    echo 'Bus : '.$bus->getName();
    echo nl2br("\n");
    echo 'ID = '.$bus->getId();
    echo ', cost = '.$bus->getCost();
    echo nl2br("\n");


}

$calculate=new Go($conn);

$calculate->setSource(1);
$calculate->setDestination(217);
print_r($calculate->best_cost_road());
print_r($calculate->road());





$conn->close();