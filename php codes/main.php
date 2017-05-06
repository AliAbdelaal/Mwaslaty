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
    echo ',metro ? ='.($station->getMetroOrnot()==TRUE?'yes':'no');
    echo ',bus ? ='.($station->getBusOrnot()==TRUE?'yes':'no');
    echo nl2br("\n\t<b>associated Buses</b>:\n");

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
    echo nl2br("\n\t<b>associated stations</b>:\n");


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

$station = $manager->get_station(90);

if($station)
{
    print_station($station);
    $buses = $manager->get_associate_buses($station);
    foreach ($buses as $bus)
    {
        echo nl2br("bus : ".$bus->getName()."\n");
    }
    echo nl2br("\n-------------------------------\n");
}
else{
    echo nl2br("Shit happens\n");
}






$conn->close();
