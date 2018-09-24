<?php
require 'Destination.php';
/**
* 
*/
class Flight
{
	public $icao;
	public $reg;
	public $altitude;
	public $position;
	public $speed;
	public $speed_type;
	public $vertical_speed;
	public $turbulence_cat;
	public $destination;
	public $destination_name;
	public $grounded;
	
	function __construct($flightdata) {
		$this->icao = $flightdata['Icao'];
		$this->reg = $flightdata['Reg'];
		$this->altitude = $flightdata['Alt'];
		$this->position = new destination($flightdata['Lat'], $flightdata['Long']);
		$this->speed = $flightdata['Spd'];
		$this->speed_type = $flightdata['SpdTyp'];
		$this->vertical_speed = $flightdata['Vsi'];
		$this->turbulence_cat = $flightdata['WTC'];
		if (array_key_exists('To', $flightdata)) {
			$this->destination_name = $flightdata['To'];
			$to = Destination::findById(substr($flightdata['To'], 0, 4));
			if ($to instanceof Destination) {
				$this->destination = $to;
			} else {
			$this->destination = NULL;
			}
		}
		$this->grounded = $flightdata['Gnd'];
	}
}