<?php

/**
 * Created by PhpStorm.
 * User: mogady
 * Date: 13/05/17
 * Time: 10:44 ص
 */
require "Database_Manager.php";

class parsing
{
    private $DatabaseM = NULL;

    public function __construct()
    {
        $this->DatabaseM = new Database_Manager();


    }

    /**
     * @param null $parsed_road
     */
    public function setParsedRoad($parsed_road)
    {
        $this->parsed_road = $parsed_road;
    }

    /**
     * @return null
     */
    public function getParsedRoad()
    {
        return $this->parsed_road;
    }


}