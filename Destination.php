<?php
define('EARTH_RADIUS', 6371e3); // in meters
define('EARTH_ROTATION_SPEED', 460); // in meters per second
define('KNOT_IN_MS', (1.852/3.6));
/**
* 
*/
class Destination {

	public $latitude;
	public $longitude;
	
	function __construct($lat, $long) {
		$this->latitude = $lat;
		$this->longitude = $long;
	}

	// https://stackoverflow.com/questions/27928/calculate-distance-between-two-latitude-longitude-points-haversine-formula
	function distanceTo($dest) {
		$dLat = deg2rad($dest->latitude - $this->latitude);
		$dLong = deg2rad($dest->longitude - $this->longitude);

		$a = 
			sin($dLat/2) * sin($dLat/2) +
			cos(deg2rad($this->latitude)) * cos(deg2rad($dest->latitude)) *
			sin($dLong/2) * sin($dLong/2);
		$c = 2 * atan2(sqrt($a), sqrt(1-$a));
		$d = EARTH_RADIUS * $c; //Distance in m
		return $d;
	}

	function timeTo($dest, $speed) {
		$dist = $this->distanceTo($dest);
		$rotation_in_favor = $this->longitude > $dest->longitude;
		$speed_in_ms = $speed * KNOT_IN_MS;
		// Earth's rotation doesn't affect the travel times as much as expected,
		// the coriolis effect however does affect the times.
		// if ($rotation_in_favor) {
		// 	$speed_in_ms += EARTH_ROTATION_SPEED;
		// } else {
		// 	$speed_in_ms -= EARTH_ROTATION_SPEED;
		// }
		return $dist/$speed_in_ms;
	}

	public static function findById($ident, $key='airports', $pos_labels=['latitude_deg', 'longitude_deg'], $hashed_key=true) {
		if ($hashed_key) {
			$ident = hash('md4', strtoupper($ident));
		} else {
			$ident = strtoupper($ident);
		}
		foreach (FileManager::$files[$key] as $key => $value) {
			if ($key == $ident) {
				return new Destination((float)$value[$pos_labels[0]], (float)$value[$pos_labels[1]]);
			}
		}
		return false;
	}
}