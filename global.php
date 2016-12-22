<?php
	$DB_Address = trim("localhost");
	$DB_User = trim("id373062_coindb");
	$DB_Password = trim("coinDB");
	$DB_Name = trim("id373062_coindb");

	$RATE_URL = "https://openexchangerates.org/api/latest.json?app_id=ebb2ddb30d4d453c8f50bbaa2979b2a5";

	/*$DB_Address = "localhost";
	$DB_User = "root";
	$DB_Password = "1234";
	$DB_Name = "cms";*/


	/*
	* CHY 2016/10/03
	* use RedBeanPHP 4.3.2
	* ORM for MySQL
	* 'xdispense' is to allow underscore in table naming conventions
	*
	*/
	require_once 'vendor/autoload.php';
	use RedBeanPHP\R;

	if (!class_exists('R')) {
		

		if(!R::testConnection()){
			
			R::setup( 'mysql:host='.$DB_Address.';dbname='.$DB_Name.';charset=utf8',$DB_User, $DB_Password );
			
			if(isset($GLOBALS['debug']) && $GLOBALS['debug']){
				R::fancyDebug( TRUE );
			}
		}
	}
?>