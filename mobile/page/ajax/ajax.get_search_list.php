<?php
include_once ("../../../common.php");

$divtext = explode(" ",$text);
if(count($divtext)>1){
    for($i=0;$i<count($divtext);$i++) {
        $where .= " or (pd_tag like '%{$divtext[$i]}%' or pd_name like '%{$divtext[$i]}%')";
    }
}

//연관검색어
$sql = "select * from `product` where (pd_tag like '%{$text}%' or pd_name like '%{$text}%') {$where}";
$result["sql"]=$sql;
$res = sql_query($sql);
while($row = sql_fetch_array($res)) {

}

//인기검색어
$sql = "select *,count(pp_word) as cnt from g5_popular where pp_word != '' group by pp_word order by cnt desc limit 0, 10";
$res = sql_query($sql);
while($row = sql_fetch_array($res)){
    $result["popular"][] = $row;
}
if(count($result["popular"])==0){
    $result['popular'] = null;
}
//최근검색어
$sql = "select * from g5_popular where mb_id = '{$member["mb_id"]}' order by pp_date desc limit 0, 10";
$res = sql_query($sql);
while($row = sql_fetch_array($res)){
    $result["recent"][] = $row;
}
if(count($result["recent"])==0){
    $result['recent'] = null;
}


echo json_encode($result);
?>