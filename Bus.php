<?php

/**
 * Created by PhpStorm.
 * User: ali
 * Date: 5/5/17
 * Time: 10:13 PM
 */

class Bus
{
    private $id = NULL;
    private $cost = NULL;
    private $name = NULL;
    private $associate_stations = NULL;


    /**~
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getCost()
    {
        return $this->cost;
    }

    /**
     * @param mixed $cost
     */
    public function setCost($cost)
    {
        $this->cost = $cost;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getAssociateStations()
    {
        return $this->associate_stations;
    }

    /**
     * @param mixed $associate_stations
     */
    public function setAssociateStations($associate_stations)
    {
        $this->associate_stations = $associate_stations;
    }

}