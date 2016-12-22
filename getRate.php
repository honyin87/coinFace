<?php
require_once 'global.php';
use RedBeanPHP\R;

header('Access-Control-Allow-Origin: *');

$exchangerate = R::findOne('exchangerate',' ORDER BY date_time DESC ');

echo $exchangerate->json;
return;
?>