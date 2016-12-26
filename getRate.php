<?php
    require_once 'global.php';
    use RedBeanPHP\R;

    header('Access-Control-Allow-Origin: *');

    $exchangerate = R::find('exchangerate', 'ORDER BY id DESC LIMIT 10');

    var_dump($exchangerate);
    return;
?>