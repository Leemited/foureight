<?php
include_once ("../../../common.php");

$url = 'https://apis.tracker.delivery/carriers/'.$carriers."/tracks/".$deli_num;
$ch = curl_init();
// Set the URL, number of POST vars, POST data
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
// Execute post
$result = curl_exec($ch);
// Close connection
curl_close($ch);
echo $result;
