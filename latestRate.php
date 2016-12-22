<?php
require_once 'global.php';
use RedBeanPHP\R;
//echo 'mysql:host='.$DB_Address.';dbname='.$DB_Name.';charset=utf8';
//echo 'RedBean PHP version: '.R::getVersion() ;

/**
* Serious stuff
* Getting request from openexchangerate.org - latest rate
*/

//$url = "https://openexchangerates.org/api/latest.json?app_id=ebb2ddb30d4d453c8f50bbaa2979b2a5";
$url=$RATE_URL;
//$url="latest.json";

//  Initiate curl
$ch = curl_init();
// Disable SSL verification
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
// Will return the response, if false it print the response
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// Set the url
curl_setopt($ch, CURLOPT_URL,$RATE_URL);
// Execute
$result=curl_exec($ch);
// Closing
curl_close($ch);

// Will dump a beauty json :3
//var_dump(json_decode($result, true));

//echo $result;


/**
* Add record into DB
*
**/
	date_default_timezone_set('Asia/Kuala_Lumpur');
	$date = date('Y-m-d H:i:s');

	$exchangerate = R::dispense('exchangerate');

	$exchangerate->json = $result;
	$exchangerate->date_time = $date;
	
     
    R::store($exchangerate);

?>