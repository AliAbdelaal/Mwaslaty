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
        // get all the buses id(s) that pass by this station
        $sql = "SELECT bus_id FROM Bus_stations WHERE station_id = " . $station->getId();
        $results = $this->conn->query($sql);
        //if there's any
        if ($results) {
            //build the array of the common buses
            $buses = array();
            foreach ($results as $result) {
                //pass the bus id to the get bus function that return a bus object with all
                //the info about the bus
                $bus = $this->get_bus(implode($result));
                array_push($buses, $bus);
            }
            return $buses;
        }
        //if there's none
        return NULL;
    }


    /**
     * @param $station_id
     * @return null|Station
     */
    function get_station($station_id)
    {
        //get the station with that id
        $sql = "SELECT * FROM Stations WHERE id =" . $station_id;
        $result = $this->conn->query($sql);
        // if there's any
        if ($result) {
            foreach ($result as $value) {
                //build the station object from the retrieved data
                $station = new Station;
                $station->setId($station_id);
                $station->setName($value['name']);
                $station->setLongitude($value['longitude']);
                $station->setLatitude($value['latitude']);
                $station->setMetro($value['metro']);
                $station->setBus($value['bus']);
                $station->setCentric($value['centric']);
                $station->setBusCount($value['buses_count']);
                return $station;
            }
        }
        return NULL;

    }

    /**
     * @param $bus_id
     * @return Bus|null
     */
    function get_bus($bus_id)
    {
        //get tha bus with that id
        $sql = "SELECT * FROM Buses WHERE id=" . $bus_id;
        $result = $this->conn->query($sql);
        //if there's any
        if ($result) {
            foreach ($result as $value) {
                //build bus object
                $bus = new Bus;
                $bus->setId($value['ID']);
                $bus->setName($value['name']);
                $bus->setCost($value['cost']);

                //get the associated stations

                //first we get the stations id that share that bus
                $sql = "SELECT station_id FROM Bus_stations WHERE bus_id =" . $value['ID'];
                $results = $this->conn->query($sql);
                $associated_stations = array();
                //iterate through the results and build the stations objects
                foreach ($results as $result) {
                    $station = $this->get_station(implode($result));
                    array_push($associated_stations, $station);
                }
                $bus->setAssociateStations($associated_stations);

                return $bus;
            }
        }
        return NULL;
    }

    /**
     * @return array|null
     */
    function get_centric_stations()
    {
        //build the sql query
        $sql = "SELECT ID FROM Stations WHERE centric = 1";
        $results = $this->conn->query($sql);
        //if any
        if ($results) {
            $stations = array();
            //iterate through results and get station objects
            foreach ($results as $result) {
                $station = $this->get_station(implode($result));
                array_push($stations, $station);
            }
            return $stations;
        }
        return NULL;

    }

    /**
     * @param $bus Bus
     */
    function pass_by_center($bus)
    {
        $associate_stations = $bus->getAssociateStations();
        $centric_stations = $this->get_centric_stations();
        //we must compare the stations names
        $associate_stations_strings = array();
        $centric_stations_strings = array();
        foreach ($associate_stations as $station)
            array_push($associate_stations_strings, $station->getID());
        foreach ($centric_stations as $station)
            array_push($centric_stations_strings, $station->getID());

        $intersect = array_intersect($associate_stations_strings, $centric_stations_strings);
        if ($intersect) {
            $stations = array();
            foreach ($intersect as $station_id) {
                array_push($stations, $this->get_station($station_id));
            }
            return $stations;
        } else
            return FALSE;
    }

    /**
     * @param $stationId
     * @param $station Station
     * @return bool
     */
    function update_station($stationId, $station)
    {
        //check if the station exists
        $sql = "SELECT * FROM Stations WHERE ID =" . $stationId;
        $result = $this->conn->query(sql);
        if (!$result) {
            echo "this station doesn't exist";
            return FALSE;
        }
        if (!$station->checkValid()) {
            echo "data is not valid, some fields are missing";
            return FALSE;
        }
        //now update the data
        /*
         * the code to update the database should be here
         * */

        return TRUE;
    }

}