<?php

/**
 * Created by PhpStorm.
 * User: ali
 * Date: 5/5/17
 * Time: 10:13 PM
 */
class Station
{
    private $name = NULL;
    private $longitude = NULL;
    private $latitude = NULL;
    private $metro = NULL;
    private $bus = NULL;
    private $centric = NULL ;
    private $bus_count = NULL ;
    private $id = NULL;

    /**
     * @return null
     */
    public function getCentric()
    {
        return $this->centric;
    }


    /**
     * @param $centric
     */
    public function setCentric($centric)
    {
        $this->centric = $centric == 1? TRUE : FALSE;
    }



    /**
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
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * @param mixed $longitude
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;
    }

    /**
     * @return mixed
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * @param mixed $latitude
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;
    }

    /**
     * @return mixed
     */
    public function getMetro()
    {
        return $this->metro;
    }

    /**
     * @param mixed $metro
     */
    public function setMetro($metro)
    {
        $this->metro = $metro==1? TRUE : FALSE;
    }

    /**
     * @return mixed
     */
    public function getBus()
    {
        return $this->bus;
    }

    /**
     * @param mixed $bus
     */
    public function setBus($bus)
    {
        $this->bus = $bus==1? TRUE : FALSE;
    }


    /**
     * @return null
     */
    public function getBusCount()
    {
        return $this->bus_count;
    }

    /**
     * @param null $bus_count
     */
    public function setBusCount($bus_count)
    {
        $this->bus_count = $bus_count;
    }

    public function checkValid()
    {
        if($this->name==NULL || $this->longitude == NULL || $this->latitude == NULL
            || $this->metro == NULL || $this->bus == NULL || $this->bus_count == NULL
            || $this->centric == NULL)
        {
            return FALSE ;
        }
        return TRUE ;
    }



}