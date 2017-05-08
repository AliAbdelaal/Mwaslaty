<?php
/**
 * Created by PhpStorm.
 * User: ali
 * Date: 4/19/17
 * Time: 4:29 AM
 */

require("Database_Manager.php");

/**
 * @param $station Station
 */
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
 * @param $bus Bus
 */
function print_bus($bus)
{
    echo 'Bus : '.$bus->getName();
    echo nl2br("\n");
    echo 'ID = '.$bus->getId();
    echo ', cost = '.$bus->getCost();
    echo nl2br("\n");


}


$servername = "localhost";
$username = "username";
$password = "password";
$database = "Mwaslaty";


// Create connection
$conn = new mysqli($servername, $username, $password, $database);
$sSQL= 'SET CHARACTER SET utf8';
$conn->query($sSQL);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


echo nl2br("Connected successfully \n");
$manager = new Database_Manager($conn);

$stations = $manager->get_centric_stations();

if($stations)
{
    foreach ($stations as $station)
    {
        print_station($station);
    }
}
else{
    echo nl2br("Shit happens\n");
}






$conn->close();
