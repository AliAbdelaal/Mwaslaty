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
    private $metro_or_not = NULL;
    private $bus_or_not = NULL;
    private $id = NULL;



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
    public function getMetroOrnot()
    {
        return $this->metro_or_not;
    }

    /**
     * @param mixed $metro_or_not
     */
    public function setMetroOrnot($metro_or_not)
    {
        $this->metro_or_not = $metro_or_not==1? TRUE : FALSE;
    }

    /**
     * @return mixed
     */
    public function getBusOrnot()
    {
        return $this->bus_or_not;
    }

    /**
     * @param mixed $bus_or_not
     */
    public function setBusOrnot($bus_or_not)
    {
        $this->bus_or_not = $bus_or_not==1? TRUE : FALSE;
    }

    public function checkValid()
    {
        if($this->name==NULL || $this->longitude == NULL || $this->latitude == NULL
            ||$this->metro_or_not == NULL || $this->bus_or_not == NULL )
        {
            return FALSE ;
        }
        return TRUE ;
    }



}