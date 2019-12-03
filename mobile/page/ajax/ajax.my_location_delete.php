<?php
include_once ("../../../common.php");
if(!$num){
    echo "1";
    return false;
}

if(!$locitem){
    echo "2";
    return false;
}

$num = $num-1;

$sql = "select * from `mysetting` where mb_id = '{$member["mb_id"]}'";
$myset = sql_fetch($sql);

$myloc = explode(",",$myset["my_locations"]);
$mylats = explode(",",$myset["location_lat"]);
$mylngs = explode(",",$myset["location_lng"]);

for($i=0;$i<5;$i++){
    if($i==$num){
        $myloc[$i] = ' ';
        $mylats[$i] = ' ';
        $mylngs[$i] = ' ';
    }
}

$locations = str_replace(" ","",implode(",",$myloc));
$lats = str_replace(" ","",implode(",",$mylats));
$lngs = str_replace(" ","",implode(",",$mylngs));

$sql = "update `mysetting` set my_locations = '{$locations}' , location_lat = '{$lats}' , location_lng = '{$lngs}' where mb_id = '{$member["mb_id"]}'";

if(sql_query($sql)){
    echo "3";
}else{
    echo "4";
}

