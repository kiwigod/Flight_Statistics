<?php
/**
* 
*/
class NetworkRequest
{
	public static function JSON($url)
	{
		$req = fopen($url, 'r');
		$res = fgets($req);
		fclose($req);
		return $res;
	}
}