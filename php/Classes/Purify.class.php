<?php

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