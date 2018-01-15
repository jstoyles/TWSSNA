<?php
ini_set('display_errors', 'on');
error_reporting(E_ALL);

require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/php/htmlpurifier/library/HTMLPurifier.auto.php';
$config = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config);

class Purifier{
	public static function purify($value){
		$config = HTMLPurifier_Config::createDefault();
		$purifier = new HTMLPurifier($config);
		return trim($purifier->purify($value));
	}
}

?>