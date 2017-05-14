<?php

/**
 *
 * Created by PhpStorm.
 * User: mogady
 * Date: 06/05/17
 * Time: 06:38 ?
 */
require("Database_Manager.php");

class calculation
{
    private $Source = NULL;
    private $Destination = NULL;
    private $DatabaseM = NULL;
    private $Meter_cost = NULL;

    public function __construct($conn)
    {
        $this->DatabaseM = new Database_Manager($conn);
        $this->Meter_cost = 2;

    }

    /**
     * @param null $Source
     */
    public function setSource($source_id)
    {
        $this->Source = $this->DatabaseM->get_station($source_id);
    }

    /**
     * @return null
     */
    public function getSource()
    {
        return $this->Source;
    }

    /**
     * @param null $Destination
     */
    public function setDestination($destinatio_id)
    {
        $this->Destination = $this->DatabaseM->get_station($destinatio_id);
    }

    /**
     * @return null
     */
    public function getDestination()
    {
        return $this->Destination;
    }

    /*
     * function to calculate cost for any road
     */
    public function calculate_cost($road)
    {
        $metro = 0;
        $cost = 0;
        $numM = 0;
        //if the road is not null
        if ($road) {
            //loop for each elment in thr road(bus or station)
            foreach ($road as $element) {

                if (get_class($element) == "Station") {
                    //if this is a station check if it is metero or not to add metro value
                    if ($element->getMetro()) {
                        $numM = $numM + 1;
                        if ($numM == 2 && !$metro) {
                            $cost = $cost + $this->Meter_cost;
                            //to stop adding values if there is more than one metero station
                            $metro = TRUE;
                        }
                    }

                } elseif (get_class($element) == "Bus") {
                    //if this is a abus add bus value to old cost
                    $cost = $cost + $element->getCost();

                }


            }
        }


        return $cost;

    }

    /*
     * used to determine if there is only one bus between the two stations
     */
    private function is_there_Bus($source_buses, $destination_buses)
    {
        //loop for every source bus
        foreach ($source_buses as $Sbus) {
            //loop for every destination bus
            foreach ($destination_buses as $Dbus) {
                //determine if ther is two buses have the same id
                if ($Sbus->getId() == $Dbus->getId()) {
                    return $Dbus;
                }

            }

        }


    }

    /*
     * used when ther is no bus in the source station pass a center
     */
    private function there_is_NoCenter($buses)
    {
        //loop for every bus in the buses
        foreach ($buses as $bus) {
            // get all stations for every bus
            $stations = $bus->getAssociateStations();
            //loop for every station
            foreach ($stations as $station) {
                if ($station->getMetro()) {
                    //if this station is metro station return the bus and the metro station
                    return array($bus, $station);
                }
                //else : the station is not a metro station
                // get all buses pass in this station
                $Sbuses = $this->DatabaseM->get_associate_buses($station);
                //loop for every bus
                foreach ($Sbuses as $sbus) {
                    //fetermine if there is a bus pass a metero center
                    $Mcenter = $this->get_Metro_center($sbus);
                    //if there is one center
                    if ($Mcenter) {
                        return array($bus, $station, $sbus, $Mcenter);
                    }
                }
            }
        }

    }

    /*
     * used to detrmine the nearest metro center for a bus
     */
    private function get_Metro_center($bus)
    {
        //get all the center stations passed by this bus
        $stations = $this->DatabaseM->pass_by_center($bus);
        //there is no center station passed by this bus
        if (!$stations) {
            return NULL;
        } else {
            //loop for every center station if there is more than one
            foreach ($stations as $station) {
                //for every station determine if it is a metro or not
                if ($station->getMetro()) {
                    return $station;
                } //there is no metro center the bus pass it
                else return NULL;

            }
        }


    }

    /*
     * used to determine the nearest non metro center for array of buses
     */
    private function nearest_center($buses)
    {//loop for every bus
        foreach ($buses as $bus) {
            //determine the centers that the bus pass it
            $centers = $this->DatabaseM->pass_by_center($bus);
            //if there is a center
            if ($centers) {
                //loop for each center if there is more than one
                foreach ($centers as $center) {
                    //determine the buses in each center
                    $center_buses = $this->DatabaseM->get_associate_buses($center);
                    //loop for every bus tho check if it pass a metro station
                    foreach ($center_buses as $Mbus) {
                        //determine the nearest metro station for that bus
                        $Mcenter = $this->get_Metro_center($Mbus);
                        //if there is one
                        if ($Mcenter) {
                            return array($bus, $center, $Mbus, $Mcenter);
                        }

                    }

                }

            }
        }

    }

    /*
     * determine the road if the two stations were metro stations
     */
    public function Metro_road()
    {
        return array(($this->Source), ($this->Destination));
    }

    /*
     * determine the road if the source were bus station and the destination is a metro station
     */
    public function bus_metro_road()
    {   //check if the source is not a metro
        if ($this->Destination->getMetro()) {
            //get all the source buses and destination buses
            $Source_buses = $this->DatabaseM->get_associate_buses(($this->Source));
            $Destination_buses = $this->DatabaseM->get_associate_buses(($this->Destination));
            //check first if there is a one bus from that station arrive to destination
            $Obus = $this->is_there_Bus($Source_buses, $Destination_buses);
            //if there is bus return that bus
            if ($Obus) {
                return array($Obus);
            }
            //else :
            //loop for every source bus
            foreach ($Source_buses as $bus) {
                //determine the metro centers for that bus
                $center = $this->get_Metro_center($bus);
                //if there is one
                if ($center) {

                    return array($bus, $center, ($this->Destination));
                }
            }
            //else:there is no metro center for all source buses
            //get the road to the nearest non metro center
            $road = $this->nearest_center($Source_buses);
            //if there is one
            if ($road) {
                array_push($road, $this->getDestination());
                return $road;
            } //there is no nearest non metro centers
            else {
                //get the road to the nearest station that reach metro center
                $road = $this->there_is_NoCenter($Source_buses);
                //there is one
                if ($road) {
                    array_push($road, $this->getDestination());
                    return $road;
                }


            }
        }
        //there is no road
        return NULL;


    }

    /*
     * determine the road if the source were metro and the destination were bus
     */
    public function metro_bus_road()
    {   //check if the source is metro
        if ($this->getSource()->getMetro()) {//determine the source buses and destination buses
            $Source_buses = $this->DatabaseM->get_associate_buses(($this->Source));
            $Destination_buses = $this->DatabaseM->get_associate_buses(($this->Destination));
            //check if there is one bus to reach the destination
            $Obus = $this->is_there_Bus($Source_buses, $Destination_buses);
            if ($Obus) {
                return array($Obus);
            }
            //loop for every destination bus
            foreach ($Destination_buses as $bus) {
                //determine the nearest metro centr for that bus
                $center = $this->get_Metro_center($bus);
                //if there is one return that road
                if ($center) {
                    return array(($this->Source), $center, $bus);
                }

            }
            //else there is no near metero center
            //determine the nearest non metro centers
            $road = array();
            $temps = $this->nearest_center($Destination_buses);
            array_push($road, $this->getSource());
            //if there is one determine the road
            if ($temps) {
                foreach (array_reverse($temps) as $arr) {
                    array_push($road, $arr);
                }
                return $road;
            } //else : there is no nearest non metro center
            else {
                $road = array();
                //check the nearest station that reaches a metro center
                $temps = $this->there_is_NoCenter($Destination_buses);
                //if there is one determine the road
                if ($temps) {
                    foreach (array_reverse($temps) as $arr) {
                        array_push($road, $arr);
                    }
                    return $road;
                }
            }
        }
        //there is no road
        return NULL;

    }

    /*
     * used to determine the road from bus to bus station
     */
    public function bus_bus_road()
    {   //first determine the source buses and destination buses
        $source_buses = $this->DatabaseM->get_associate_buses(($this->Source));
        $destination_buses = $this->DatabaseM->get_associate_buses(($this->Destination));
        //if there is one bus
        $bus = $this->is_there_Bus($source_buses, $destination_buses);
        if ($bus) {
            return array($bus);
        }
        //else:search for the nearest center that have one bus to reach destination
        foreach ($source_buses as $Sbus) {
            $centers = $this->DatabaseM->pass_by_center($Sbus);
            if ($centers) {
                foreach ($centers as $center) {
                    $center_buses = $this->DatabaseM->get_associate_buses($center);
                    $Obus = $this->is_there_Bus($center_buses, $destination_buses);
                    if ($Obus) {
                        $road = array($Sbus, $center, $Obus);
                        return $road;
                    }
                }
            }
        }

        //else:search for the nearest metro stations that reaches between source and destination
        foreach ($source_buses as $Sbus) {
            $SStations = $Sbus->getAssociateStations();
            foreach ($SStations as $Sstation) {
                if ($Sstation->getMetro()) {
                    foreach ($destination_buses as $Dbus) {
                        $DStations = $Dbus->getAssociateStations();
                        foreach ($DStations as $Dstation) {
                            if ($Dstation->getMetro()) {
                                $road = array($Sbus, $Sstation, $Dstation, $Dbus);
                                return $road;
                            }


                        }


                    }

                }


            }


        }
        //loop for every bus in source buses
        foreach ($source_buses as $Sbus) {
            $SStations = $Sbus->getAssociateStations();
            //loop for each station reached by this bus
            foreach ($SStations as $station) {
                //check if there is common bus
                $buses = $this->DatabaseM->get_associate_buses($station);
                $bus = $this->is_there_Bus($buses, $destination_buses);
                if ($bus) {
                    return array($Sbus, $station, $bus);
                }
                //else search for each center passed by this bus
                foreach ($buses as $bus) {
                    $centers = $this->DatabaseM->pass_by_center($bus);
                    if ($centers) {
                        //search for common bus in each center
                        foreach ($centers as $center) {
                            $cbuses = $this->DatabaseM->get_associate_buses($center);
                            $cbus = $this->is_there_Bus($cbuses, $destination_buses);
                            if ($cbus) {
                                return array($Sbus, $station, $bus, $center, $cbus);
                            }
                        }

                    }

                }


            }


        }
        return NULL;


    }


}