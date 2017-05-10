<?php

/**
 * Created by PhpStorm.
 * User: mogady
 * Date: 06/05/17
 * Time: 06:38 م
 */
require("Database_Manager.php");
class calculation
{
    private $Source = NULL;
    private $Destination=NULL;
    private $DatabaseM=NULL;
    private $Meter_cost=NULL;
    public function __construct(){
        $this->DatabaseM=new Database_Manager();
        $this->Meter_cost=2;

    }

    /**
     * @param null $Source
     */
    public function setSource($Source)
    {
        $this->Source = $Source;
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
    public function setDestination($Destination)
    {
        $this->Destination = $Destination;
    }

    /**
     * @return null
     */
    public function getDestination()
    {
        return $this->Destination;
    }


    function get_Metero_center($bus){
        $stations=($this->DatabaseM)->pass_by_center($bus);
        if($stations==FALSE){return FALSE;}
        else {
            foreach ($stations as $station) {
                if($station->getMetro())
                {return $station;}

            }
        }



    }
    function Metero_road(){


        return array(($this->Source),($this->Destination),$this->Meter_cost);

    }
    function bus_metero_road(){
        $buses=($this->DatabaseM)->get_associate_buses(($this->Source));
        foreach ($buses as $bus) {
            $center = $this->get_Metero_center($bus);
            if ($center) {
                $cost=$bus->getCost()+$this->Meter_cost;
                return array($bus, $center, ($this->Destination),$cost);
            }
        }
        foreach ($buses as $bus){
            $centers=($this->DatabaseM)->pass_by_center($bus);
            foreach ($centers as $center){
                $center_buses=$this->DatabaseM->get_associate_buses($center);
                foreach ($center_buses as $cbus){
                    $Scenter = $this->get_Metero_center($cbus);
                    if ($Scenter) {
                        $cost=$cbus->getCost()+$bus->getCost()+$this->Meter_cost;
                        return array($bus, $center,$cbus,$Scenter,($this->Destination),$cost);
                    }

                }

            }

        }

    }


    }
    function metero_bus_road()
    {

        $buses=($this->DatabaseM)->get_associate_buses(($this->Destination));
        foreach ($buses as $bus){
            $center=$this->get_Metero_center($bus);
            if($center){
                $cost=$bus->getCost()+$this->Meter_cost;
                return array(($this->Source),$center,$bus,$cost);
            }

        }
        foreach ($buses as $bus){
            $centers=($this->DatabaseM)->pass_by_center($bus);
            foreach ($centers as $center){
                $center_buses=$this->DatabaseM->get_associate_buses($center);
                foreach ($center_buses as $cbus){
                    $Scenter = $this->get_Metero_center($cbus);
                    if ($Scenter) {
                        $cost=$cbus->getCost()+$bus->getCost()+$this->Meter_cost;
                        return array($bus, $center,$cbus,$Scenter,($this->Destination),$cost);
                    }

                }

            }

        }



    }
    function bus_bus_road(){
        $source_buses=($this->DatabaseM)->get_associate_buses(($this->Source));
        $destination_buses=($this->DatabaseM)->get_associate_buses(($this->Destination));
        $buses=array_intersect($source_buses,$destination_buses);
        if(count($buses)>0){
            foreach ($buses as $bus){return $bus;}

        }
        else{
            $source_buses=($this->DatabaseM)->get_associate_buses(($this->Source));
            $destination_buses=($this->DatabaseM)->get_associate_buses(($this->Destination));
            foreach ($source_buses as $sbus){
                $source_center=$this->get_Metero_center($sbus);
                if($source_center)
                    foreach ($destination_buses as $dbus){
                        $destination_center=$this->get_Metero_center($dbus);
                        if($destination_center){
                            $cost=$sbus->getCost()+$dbus->getCost()+$this->Metero_cost;
                            return array($sbus,$source_center,$destination_center,$dbus,$cost);
                        }
                    }
                }

            }





    }
    function road(){
        if(($this->Source)->getMetro()&&($this->Destination)->getMetro()){
            $this->Metero_road();}
        elseif ((!($this->Source)->getMetero())&&($this->Destination)->getMetro()){
            bus_metero_road();}

        elseif (($this->Source)->getMetero()&&(!($this->Destination)->getMetro())){
            metero_bus_road();}

        else{bus_bus_road();}
    }
    function cost(){




        
    }