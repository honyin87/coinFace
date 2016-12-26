<?php
    require_once 'global.php';

    header('Access-Control-Allow-Origin: *');

    $exchangerate = R::find('exchangerate',' ORDER BY date_time DESC ');

    echo $exchangerate->json;
    return;
?>