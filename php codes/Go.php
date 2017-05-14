<?php

/**
 * Created by PhpStorm.
 * User: mogady
 * Date: 10/05/17
 * Time: 10:06 ص
 */
require "calculation.php";

class Go
{
    private $calculate = NULL;
    public $parsed_road = array();

    public function __construct($conn)
    {
        $this->calculate = new calculation($conn);
    }

    public function setSource($Source_id)
    {
        $this->calculate->setSource($Source_id);
    }

    public function setDestination($Destination_id)
    {
        $this->calculate->setDestination($Destination_id);
    }

    /*
     * function used to parse the road to array of strings
     */
    public function parse_road($road)
    {

        foreach ($road as $element) {
            if (gettype($element) == "object") {
                if (get_class($element) == "Station") {
                    $station = array();
                    $type = "Station";
                    $name = $element->getName();
                    $longitude = $element->getLongitude();
                    $latitude = $element->getLatitude();
                    $metro = $element->getMetro();
                    $bus = $element->getBus();
                    array_push($station, $type, $name, $longitude, $latitude, $metro, $bus);
                    array_push($this->parsed_road, $station);
                } elseif (get_class($element) == "Bus") {
                    $bus = array();
                    $num = $element->getName();
                    $type = "Bus";
                    array_push($bus, $type, $num);
                    array_push($this->parsed_road, $bus);

                }
            } else {
                array_push($this->parsed_road, $element);


            }


        }


        return $this->parsed_road;
    }

    /*
     * used to determine the right road
     */
    public function road()
    {   //if source and destination is metro stations
        if ((($this->calculate->getSource())->getMetro()) && (($this->calculate->getDestination())->getMetro())) {
            $road = $this->calculate->Metro_road();
            if ($road) {
                array_push($road, $this->calculate->calculate_cost($road));
            }
            return ($this->parse_road($road));
        } //if source is not metro and destination is metro
        elseif (!(($this->calculate->getSource())->getMetro()) && (($this->calculate->getDestination())->getMetro())) {

            $road = $this->calculate->bus_metro_road();
            if ($road) {
                array_push($road, $this->calculate->calculate_cost($road));
            }
            return ($this->parse_road($road));
        } //if source is metro and destination is not
        elseif ((($this->calculate->getSource())->getMetro()) && !(($this->calculate->getDestination())->getMetro())) {
            $road = $this->calculate->metro_bus_road();
            if ($road) {
                array_push($road, $this->calculate->calculate_cost($road));
            }
            return ($this->parse_road($road));
        } //the both are bus stations
        else {

            $road = $this->calculate->bus_bus_road();
            if ($road) {
                array_push($road, $this->calculate->calculate_cost($road));
            }
            return ($this->parse_road($road));
        }

    }

    /*
    * used to determine the best cost road
    */
    function best_cost_road()
    {
        $froad = NULL;
        $roads = array();
        $fcost = 20;//initial value 20
        //if two station are metro get all possible roads
        if (($this->calculate->getSource())->getMetro() && ($this->calculate->getDestination())->getMetro()) {

            $road1 = $this->calculate->Metro_road();
            $road2 = $this->calculate->bus_metro_road();
            $road3 = $this->calculate->metro_bus_road();
            $road4 = $this->calculate->bus_bus_road();
            array_push($roads, $road1, $road2, $road3, $road4);
        } //also get all possible roads
        else {
            $road1 = $this->calculate->bus_metro_road();
            $road2 = $this->calculate->metro_bus_road();
            $road3 = $this->calculate->bus_bus_road();
            array_push($roads, $road1, $road2, $road3);
        }
        //loop for each road and determine the best cost road if found
        foreach ($roads as $road) {
            if ($road) {
                $cost = $this->calculate->calculate_cost($road);
                if ($cost < $fcost) {
                    $froad = $road;
                    $fcost = $cost;
                }

            }

        }
        if ($froad) {
            $Froad = array_merge($froad, array($fcost));
            return ($this->parse_road($Froad));
        } else return NULL;

    }

}