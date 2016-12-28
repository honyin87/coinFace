<?php
    require_once 'global.php';
    use RedBeanPHP\R;

    header('Access-Control-Allow-Origin: *');

    $exchangerate = R::findOne('exchangerate');

    var_dump($exchangerate->dest_cur);
?>