<?php
	require_once 'global.php';
	use RedBeanPHP\R;

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_URL,$RATE_URL);
	$result=curl_exec($ch);
	curl_close($ch);

	$drate = json_decode($result);
	date_default_timezone_set('Asia/Kuala_Lumpur');	
	
	foreach($drate->rates as $k=>$v){
		$exchangerate = R::dispense('exchangerate');
		$exchangerate->date = date('Y-m-d');
		$exchangerate->api_time = date('Y-m-d H:i:s',$drate->timestamp);
		$exchangerate->create_time = date('Y-m-d H:i:s');
		$exchangerate->src_cur = $drate->base;	
		$exchangerate->dest_cur = $k;
		$exchangerate->rate = $v;
		R::store($exchangerate);
	}
?>