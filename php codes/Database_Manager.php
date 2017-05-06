<?php

/**
 * Created by PhpStorm.
 * User: ali
 * Date: 5/5/17
 * Time: 10:28 PM
 */

require("Station.php");
require("Bus.php");
class Database_Manager
{
    private $conn = NULL;

    /**
     * Database_Manager constructor.
     * @param $conn mysqli
     */
    public function __construct($conn)
    {
        $this->conn = $conn;
    }


    /**
     * @param $station Station
     * @return array|null
     */
    function get_associate_buses($station)
    {
        $sql = "SELECT bus_id FROM Bus_stations WHERE station_id = ".$station->getId() ;
        $results = $this->conn->query($sql);
        if($results)
        {
            $buses = array();
            foreach ($results as $result) {
                $bus = $this->get_bus(implode($result));
                array_push($buses, $bus);
            }
            return $buses ;
        }
        return NULL ;
    }


    /**
     * @param $station_id
     * @return null|Station
     */
    function get_station($station_id)
    {
        $sql = "SELECT * FROM Stations WHERE id =".$station_id;
        $result = $this->conn->query($sql);
        if($result)
        {
            foreach ($result as $value) {
                $station = new Station;
                $station->setId($station_id);
                $station->setName($value['name']);
                $station->setLongitude($value['longitude']);
                $station->setLatitude($value['latitude']);
                $station->setMetroOrnot($value['metro']);
                $station->setBusOrnot($value['bus']);

                return $station;
            }
        }
        return NULL ;

    }

    /**
     * @param $bus_id
     * @return Bus|null
     */
    function get_bus($bus_id)
    {
        $sql = "SELECT * FROM Buses WHERE id=".$bus_id;
        $result = $this->conn->query($sql);
        if($result)
        {
            foreach ($result as $value) {
                $bus = new Bus;
                $bus->setId($value['ID']);
                $bus->setName($value['name']);
                $bus->setCost($value['cost']);

                //get the associated stations

                //first we get the stations id
                $sql = "SELECT station_id FROM Bus_stations WHERE bus_id =".$value['ID'];
                $results = $this->conn->query($sql);
                $associated_stations = array();
                foreach ($results as $result)
                {
                    $station = $this->get_station(implode($result));
                    array_push($associated_stations, $station);
                }
                $bus->setAssociateStations($associated_stations);

                return $bus;
            }
        }
        return NULL ;
    }

    /**
     * @param $stationId
     * @param $station Station
     * @return bool
     */
    function update_station($stationId, $station)
    {
        //check if the station exists
        $sql = "SELECT * FROM Stations WHERE ID =".$stationId;
        $result = $this->conn->query(sql);
        if(!$result)
        {
            echo "this station doesn't exist";
            return FALSE;
        }
        if(!$station->checkValid())
        {
            echo "data is not valid, some fields are missing";
            return FALSE;
        }
        //now update the data

        return TRUE ;
    }

}