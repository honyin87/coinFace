<?php
    require_once 'global.php';
    use RedBeanPHP\R;

    header('Access-Control-Allow-Origin: *');

    $exchangerate = R::find('exchangerate','ORDER BY dest_cur ASC, create_time DESC');

    echo "<table border='1'>";
    echo "<tr><td>Date</td><td>Rate DTTM</td><td>Store DTTM</td><td>Source</td><td>Destination</td><td>Rate</td></tr>";
    foreach($exchangerate as $data){
        echo "<tr>";
        echo "<td>".$data->date."</td>";
        echo "<td>".$data->api_time."</td>";
        echo "<td>".$data->create_time."</td>";
        echo "<td>".$data->src_cur."</td>";
        echo "<td>".$data->dest_cur."</td>";
        echo "<td>".$data->rate."</td>";
        echo "</tr>";
    }        
    echo "</table>";
?>