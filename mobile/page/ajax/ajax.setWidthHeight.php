<?php
include_once ("../../../common.php");

$dWidth = $width;
$dHeight = $height;

$array = array("dHeight" => $height, "dWidth" =>$width);

echo json_encode($array);


