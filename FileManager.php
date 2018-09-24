<?php
/**
* 
*/
class FileManager
{
	public static $id_links = [];
	public static $files = [];
	// Using hashes on the keys because string comparison is slow
	public static function read_csv($path, $req_indexes, $save_in_memory='', $hash_key=true) {
		$csv = array_map('str_getcsv', file($path));
		$header = $csv[0];
		foreach ($header as $key => $value) {
			if (!in_array($value, $req_indexes)) {
				unset($header[$key]);
			}
		}
		unset($csv[0]); //remove header
		$values;
		foreach ($csv as $key => $data) {
			$data = array_intersect_key($data, $header);
			$tmp;
			foreach ($header as $col_name => $index) {
				$tmp[$index] =  $data[$col_name];
			}
			if ($hash_key) {
				$values[hash('md4', $tmp[$req_indexes[0]])] = array_slice($tmp, 1);
			} else {
				$values[$tmp[$req_indexes[0]]] = array_slice($tmp, 1);
			}
		}
		unset($csv); // discard csv to save memory

		if (strlen($save_in_memory) > 0) {
			FileManager::$files[$save_in_memory] = $values;
			unset($values); // discard to save memory
			return true;
		}

		return $values;
	}
}