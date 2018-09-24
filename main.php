<?php
require_once 'Flight.php';
require_once 'NetworkRequest.php';
require_once 'FileManager.php';


FileManager::read_csv('res/airports.csv', ['ident', 'name', 'latitude_deg', 'longitude_deg'], 'airports');
// FileManager::read_csv('res/airports.csv', ['ident', 'name', 'latitude_deg', 'longitude_deg'], 'airports_unhashed', false);
// $correct = 0;
// $entries = 0;
// $failed = [];
// foreach (FileManager::$files['airports_unhashed'] as $key => $value) {
// 	$test_val = Destination::findById($key);
// 	if ($test_val instanceof Destination) {
// 		if ($test_val->latitude == $value['latitude_deg'] and $test_val->longitude == $value['longitude_deg']) {
// 			$correct++;
// 		}
// 		else {
// 			array_push($failed, $key);
// 		}
// 	}
// 	$entries++;
// }
// echo $correct , ' entries of the ' , $entries , ' have no hash collision' , PHP_EOL;
// echo var_dump($failed);

// echo var_dump(FileManager::$files);
// echo var_dump(Destination::findById('RKSI'));
// die();

// Sendai Japan to Incheon Korea, assume cruising speed of 450 knots
// $currentPos = Destination::findById('RJSS'); // Sendai Japan
// $destination = Destination::findById('RKSI'); // Incheon S.Korea
// echo 'Distance from current position to destination is ', 
// 	$currentPos->distanceTo($destination)/1000,
// 	'km',
// 	PHP_EOL;
// echo var_dump($currentPos->timeTo($destination, 450));
// die();

$req = NetworkRequest::JSON('https://public-api.adsbexchange.com/VirtualRadar/AircraftList.json?fOpIcaoQ=AAR');
# KLC -> KLM Cityhopper
# AAR -> Asiana Airlines

$flight_data = json_decode($req, true)['acList'];
$flights = [];
foreach ($flight_data as $key => $value) {
	$flights[$value['Reg']] = new Flight($value);
}

echo var_dump($flights);
