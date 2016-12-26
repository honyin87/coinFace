<?php
	define("DBADDR","localhost");
	define("DBUSER","id373062_coindb");
	define("DBPASS","coinDB");
	define("DBNAME","id373062_coindb");
	define("APPID","ebb2ddb30d4d453c8f50bbaa2979b2a5");
	

	$RATE_URL = "https://openexchangerates.org/api/latest.json?app_id=".APPID;

	require_once 'vendor/autoload.php';
	use RedBeanPHP\R;

	if (!class_exists('R')) {
		

		if(!R::testConnection()){
			
			R::setup( 'mysql:host='.DBADDR.';dbname='.DBNAME.';charset=utf8',DBUSER, DBPASS );
			
			if(isset($GLOBALS['debug']) && $GLOBALS['debug']){
				R::fancyDebug( TRUE );
			}
		}
	}
?>