<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
if($is_member){
    $sql = "select * from `mysetting` where mb_id = '{$member["mb_id"]}'";
    $myset = sql_fetch($sql);
}
if($_REQUEST["latlng"]) {
    $locs = explode("/", $_REQUEST["latlng"]);
    $lat = $locs[0];
    $lng = $locs[1];
}

//검색 기본값
$search = "p.pd_status = 0 and p.pd_blind < 10 and p.pd_blind_status = 0 ";

//검색 정렬 기본값
if($_SESSION["list_basic_order"]=="location"){
    $od = " order by p.pd_update desc,p.pd_hits desc, ISNULL(p.pd_lat) asc, distance asc";
}else {
    $od = " order by p.pd_update desc,p.pd_hits desc";
}

if($member["mb_id"]){
    $mb_id = $member["mb_id"];
}else{
    $mb_id = session_id();
}

if($sc_id){
    $sc_where = " sc_tag = '{$stx}'";
    if($cate){
        $sc_where .= " ,sc_cate = '{$cate1}'";
    }
    if($cate2){
        $sc_where .= " ,sc_cate2 = '{$cate2}'";
    }
    if($order_sort){
        $sc_where .= " ,sc_align = '{$order_sort}'";
    }
    if($order_sort_active){
        $sc_where .= " ,sc_align_active = '{$order_sort_active}'";
    }
    if($priceFrom){
        $sc_where .= " ,sc_priceForm = '{$priceFrom}'";
    }
    if($priceTo){
        $sc_where .= " ,sc_priceTo = '{$priceTo}'";
    }
    if($sc_mb_level){
        $sc_where .= " ,sc_mb_level = '{$mb_level}'";
    }
    if($pd_price_type1=="off" || $pd_price_type1=="-1"){
        $sc_where .= " ,sc_price_type1 = -1";
    }else{
        $sc_where .= " ,sc_price_type1 = 0";
    }
    if($pd_price_type2=="off" || $pd_price_type2=="-1"){
        $sc_where .= " ,sc_price_type2 = -1";
    }else{
        $sc_where .= " ,sc_price_type2 = 1";
    }
    if($pd_price_type3=="off" || $pd_price_type1=="-1"){
        $sc_where .= " ,sc_price_type3 = -1";
    }else{
        $sc_where .= " ,sc_price_type3 = 2";
    }
    if($pd_timeFrom){
        $sc_where .= " ,sc_timeFrom = '{$pd_timeFrom}'";
    }
    if($pd_timeTo){
        $sc_where .= " ,sc_timeTo = '{$pd_timeTo}'";
    }
    $sql = "update `my_search_list` set {$sc_where} where sc_id = '{$sc_id}'";
    sql_query($sql);

    $sql = "select * from `my_search_list` where sc_id = '{$sc_id}'";
    $schopt = sql_fetch($sql);
    $set_type = $schopt["sc_type"];
    $type2 = $schopt["sc_type2"];
    $cate1 = $schopt["sc_cate1"];
    $cate2 = $schopt["sc_cate2"];
    $stx = $schopt["sc_tag"];
    $order_sort = $schopt["sc_align"];
    $order_sort_active = $schopt["sc_align_active"];
    $priceFrom = $schopt["sc_priceFrom"];
    $priceTo = $schopt["sc_priceTo"];
    $pd_price_type1 = $schopt["sc_worktime"];
    $pd_price_type2 = $schopt["sc_worktime"];
    $pd_price_type3 = $schopt["sc_worktime"];
    $pd_timeFrom = $schopt["sc_timeFrom"];
    $pd_timeTo = $schopt["sc_timeTo"];
    $mb_level = $schopt["sc_mb_level"];
    if($mb_level==""){
        $mb_level = "on";
    }
    if($pd_price_type1==""){
        $pd_price_type1 = 0;
    }
    if($pd_price_type2==""){
        $pd_price_type2 = 1;
    }
    if($pd_price_type3==""){
        $pd_price_type3 = 2;
    }
}

if ($set_type) {
    $search .= " and p.pd_type = {$set_type}";
    /*if ($set_type == 1) {
        $search .= " and isnull(o.od_id) ";
    }*/
}

if ($type2) {
    $search .= " and p.pd_type2 = {$type2}";
} else {
    $search .= " and p.pd_type2 = 8";
}

//if($searchActive=="simple") {
if ($stx) {
    $search .= " and (p.pd_name like '%{$stx}%' or p.pd_tag like '%{$stx}%')";
}
if ($cate1) {
    $search .= " and p.pd_cate = '{$cate1}'";
}
if ($cate2) {
    $search .= " and p.pd_cate2 = '{$cate2}'";
}
if ($priceFrom != '' && $priceTo != '') {
    $search .= " and (p.pd_price+p.pd_price2) between {$priceFrom} and {$priceTo}";
}else{
    if($cate && $cate2) {
        $sql = "select MAX(pd_price+pd_price2) as max, MIN(pd_price+pd_price2) as min from `product` where pd_cate = '{$cate}' and pd_cate2 = '{$cate2}' and pd_status = 0";
        $cateminmax = sql_fetch($sql);
    }else if($cate && !$cate2){
        $sql = "select MAX(pd_price+pd_price2) as max, MIN(pd_price+pd_price2) as min from `product` where pd_cate = '{$cate}' and pd_status = 0";
        $cateminmax = sql_fetch($sql);
    }else if(!$cate && !$cate2){
        $sql = "select MAX(pd_price+pd_price2) as max, MIN(pd_price+pd_price2) as min from `product` where pd_type = '{$set_type}' and pd_status = 0";
        $cateminmax = sql_fetch($sql);
    }
    $search .= " and (p.pd_price+p.pd_price2) between {$cateminmax["min"]} and {$cateminmax["max"]}";
}
if($set_type==2) {
    $pdchk1="off";
    $pdchk2="off";
    if($pd_price_type1 == "0" || $pd_price_type2 == "1" || $pd_price_type3 == "2"){
        $search .= " and (";
    }
    if ($pd_price_type1 == "0") {
        $pdchk1 = "on";
        $search .= " p.pd_price_type = {$pd_price_type1}";
    }
    if($pdchk1=="on" && ($pd_price_type2 == "1" || $pd_price_type3 == "2")){
        $search .= " or ";
    }
    if ($pd_price_type2 == "1") {
        $pdchk2 = "on";
        $search .= " p.pd_price_type = {$pd_price_type2}";
    }
    if($pdchk2=="on" && $pd_price_type3 == "2"){
        $search .= " or ";
    }
    if($pd_price_type3 == "2") {
        $search .= " p.pd_price_type = {$pd_price_type3}";
    }
    if($pd_price_type1=="0" || $pd_price_type2=="1" || $pd_price_type3=="2"){
        $search .= ") ";
    }

    if($pd_timeFrom){
        $search .= " and ((CAST('{$pd_timeFrom}' as unsigned) >= CAST(p.pd_timeFrom as unsigned) and CAST('{$pd_timeFrom}' as unsigned) < CAST(p.pd_timeTo as unsigned))";
    }
    if($pd_timeFrom && $pd_timeTo){
        $search .= " or ";
    }else if($pd_timeFrom && $pd_timeTo == ""){

    }else if($pd_timeFrom == "" && $pd_timeTo){
        $search .= " and (";
    }
    if($pd_timeTo){
        $search .= " (CAST('{$pd_timeTo}' as unsigned) > CAST(p.pd_timeFrom as unsigned) and CAST('{$pd_timeTo}' as unsigned) <= CAST(p.pd_timeTo as unsigned))";
    }
    if($pd_timeFrom || $pd_timeTo){
        $search .= ")";
    }
    if($pd_timeType){
        $search .= " and pd_timeType = '{$pd_timeType}'";
    }
}

/*if($pd_timeFrom && $pd_timeTo){
    $search .= " and CONVERT(p.pd_timeFrom ,UNSIGNED) >= '{$pd_timeFrom}' and CONVERT(p.pd_timeTo, UNSIGNED) <= '{$pd_timeTo}'";
}*/
$ss_id = session_id();
if($member["mb_id"]){
    $wished_id = $member["mb_id"];
    $search .= " and p.pd_id not in (select pd_id from `my_trash` where mb_id = '{$member[mb_id]}') ";
}else{
    $wished_id = $ss_id;
    $search .= " and p.pd_id not in (select pd_id from `my_trash` where mb_id = '{$ss_id}') ";
}
//유저 차단 목록
$block_time = date("Y-m-d H:i:s");
$sql = "select target_id from `member_block` where mb_id = '{$member["mb_id"]}' and '{$block_time}' BETWEEN block_dateFrom and block_dateTo";
$res = sql_query($sql);
while($row = sql_fetch_array($res)){
    $my_block[] = $row["target_id"];
}

if(count($my_block)>0){
    $block_id = "'".implode("','",$my_block)."'";
    $search .= " and p.mb_id not in ({$block_id}) ";
}

$psearch = str_replace("p.","",$search);
//$psearch = str_replace("and isnull(o.od_id) ","",$psearch);

if($mb_level=="on"){

}else{
    $search .= " and m.mb_level = 2 ";
}

//&& $searchActive=="simple"
if($order_sort && $searchActive!="none"){
    $od = "";
    $order_sorts = explode(",",$order_sort);
    $actives = explode(",",$order_sort_active);
    for($i=0;$i<count($order_sorts);$i++){
        $rownum = 0;
        /*if($order_sorts[$i]=="pd_date"){
            if($actives[$i] == 1){
                $checked[$i] = "checked";
                $sels[] = "updates";
                $joins .= " left join (select pd_id, @updatenum := @updatenum + 1 as updates from `product`, (SELECT @updatenum := {$rownum}) as R where {$psearch} order by pd_update) a on p.pd_id = a.pd_id ";
                $ods[] = " p.pd_update desc";
            }
            $order_item[$i] = '<label class="align" id="sortable" for="pd_date">'.
                '<input type="checkbox" name="orders[]" value="pd_date" id="pd_date" '.$checked[$i].' >'.
                '<span class="round">최신순</span></label>';
        }*/
        if($order_sorts[$i]=="pd_price"){
            if($actives[$i] == 1){
                $checked[$i] = "checked";
                $sels[] = "prices";
                $joins .= " left join (select pd_id, @pricesum := @pricesum + 1 as prices from `product`, (SELECT @pricesum := {$rownum}) as R where {$psearch} order by pd_price + pd_price desc ) c on p.pd_id = c.pd_id ";
                $ods[] = " p.pd_price + p.pd_price2 asc";
            }
            $order_item[$i] = '<label class="align" id="sortable" for="pd_price">'.
                '<input type="checkbox" name="orders[]" value="pd_price" id="pd_price" '.$checked[$i].' >'.
                '<span class="round">가격순</span></label>';
        }
        if($order_sorts[$i]=="pd_recom"){
            if($actives[$i] == 1){
                $checked[$i] = "checked";
                $sels[] = "recoms";
                $joins .= " left join (select pd_id, @recomsum := @recomsum + 1 as recoms from `product`, (SELECT @recomsum := {$rownum}) as R where {$psearch} order by pd_recom asc) d on p.pd_id = d.pd_id ";
                $ods[] = " p.pd_recom desc";
            }
            $order_item[$i] = '<label class="align" id="sortable" for="pd_recom">'.
                '<input type="checkbox" name="orders[]" value="pd_recom" id="pd_recom" '.$checked[$i].' >'.
                '<span class="round">추천순</span></label>';
        }
        if($order_sorts[$i]=="pd_hits"){
            if($actives[$i] == 1){
                $checked[$i] = "checked";
                $sels[] = "hits";
                $joins .= " left join (select pd_id, @hitsnum := @hitsnum + 1 as hits from `product`, (SELECT @hitsnum := {$rownum}) as R where {$psearch} order by pd_hits asc) b on p.pd_id = b.pd_id ";
                $ods[] = " p.pd_hits desc";
            }
            $order_item[$i] = '<label class="align" id="sortable" for="pd_hits">'.
                '<input type="checkbox" name="orders[]" value="pd_hits" id="pd_hits" '.$checked[$i].' >'.
                '<span class="round">인기순</span></label>';
        }
        if($order_sorts[$i]=="pd_loc"){
            if($actives[$i] == 1){
                $checked[$i] = "checked";
                if($_SESSION["lat"] && $_SESSION["lng"]) {
                    $joins .= " left join (select 6371 * 2 * ATAN2(SQRT(POW(SIN(RADIANS({$_SESSION["lat"]} - pd_lat)/2), 2) + POW(SIN(RADIANS({$_SESSION["lng"]} - pd_lng)/2), 2) * COS(RADIANS(pd_lat)) * COS(RADIANS({$_SESSION["lat"]}))), SQRT(1 - POW(SIN(RADIANS({$_SESSION["lat"]} - pd_lat)/2), 2) + POW(SIN(RADIANS({$_SESSION["lng"]} - pd_lng)/2), 2) * COS(RADIANS(pd_lat)) * COS(RADIANS({$_SESSION["lat"]})))) AS dd, pd_id , @locsum := @locsum + 1 as distances from `product`,(select @locsum := {$rownum}) as R where {$psearch} order by dd desc) e on p.pd_id = e.pd_id ";
                    $sels[] = "distances";
                    $ods[] = " ISNULL(p.pd_lat) desc, distance desc";
                }
            }
            $order_item[$i] = '<label class="align" id="sortable" for="pd_loc">'.
                '<input type="checkbox" name="orders[]" value="pd_loc" id="pd_loc" '.$checked[$i].' >'.
                '<span class="round">거리순</span></label>';
        }
    }
    if(count($sels)>0){
        for($i=0;$i<count($sels);$i++){
            $sel .= ",ceil((".$sels[$i]."/total.cnt) * 100) as {$sels[$i]}";
        }
        //$sel_sum = ",".implode("+",$sels)." as sums ";
        if(count($sels)==1){
            $sel_sum = ",ceil((".$sels[0]."/total.cnt)*100) as sums";
        }else if(count($sels)==2){
            for($i=0;$i<count($sels);$i++){
                if($i==0) $sel_sum = ", (";
                $sel_sum .= "(ceil(({$sels[$i]}/total.cnt) * 100) ";
                if($i==0) $sel_sum .= " * (70/100)) + ";
                if($i==1) $sel_sum .= " * (30/100))) as sums ";
            }
        }else if(count($sels)==3){
            for($i=0;$i<count($sels);$i++){
                if($i==0) $sel_sum = ", (";
                $sel_sum .= "(ceil(({$sels[$i]}/total.cnt) * 100) ";
                if($i==0) $sel_sum .= " * (50/100)) + ";
                if($i==1) $sel_sum .= " * (30/100)) + ";
                if($i==2) $sel_sum .= " * (20/100))) as sums ";
            }
        }else if(count($sels)==4){
            for($i=0;$i<count($sels);$i++){
                if($i==0) $sel_sum = ", (";
                $sel_sum .= "(ceil(({$sels[$i]}/total.cnt) * 100) ";
                if($i==0) $sel_sum .= " * (40/100)) + ";
                if($i==1) $sel_sum .= " * (30/100)) +";
                if($i==2) $sel_sum .= " * (20/100)) + ";
                if($i==3) $sel_sum .= " * (10/100))) as sums ";
            }
        }else if(count($sels)==5){
            for($i=0;$i<count($sels);$i++){
                if($i==0) $sel_sum = ", (";
                $sel_sum .= "(ceil(({$sels[$i]}/total.cnt) * 100) ";
                if($i==0) $sel_sum .= " * (37/100)) + ";
                if($i==1) $sel_sum .= " * (27/100)) + ";
                if($i==2) $sel_sum .= " * (17/100)) + ";
                if($i==3) $sel_sum .= " * (12/100)) + ";
                if($i==4) $sel_sum .= " * (7/100))) as sums ";
            }
        }
    }
    if($sc_id) {
        $od = " order by p.pd_update desc, sums desc";
    }else{
        $od = " order by sums desc";
    }
}

if(!$lat && !$lng) {
    if ($_SESSION["lat"] && $_SESSION["lng"]) {
        $lat = $_SESSION["lat"];
        $lng = $_SESSION["lng"];
    }
}
if($lat && $lng){
    if($lat && $lng) {
        $sel .= " , 6371 * 2 * ATAN2(SQRT(POW(SIN(RADIANS({$lat} - p.pd_lat)/2), 2) + POW(SIN(RADIANS({$lng} - p.pd_lng)/2), 2) * COS(RADIANS(p.pd_lat)) * COS(RADIANS({$lat}))), SQRT(1 - POW(SIN(RADIANS({$lat} - p.pd_lat)/2), 2) + POW(SIN(RADIANS({$lng} - p.pd_lng)/2), 2) * COS(RADIANS(p.pd_lat)) * COS(RADIANS({$lat})))) AS distance";
    }
}
$total=sql_fetch("select count(*) as cnt from `product` as p use index (idx_product) left join `g5_member` as m on p.mb_id = m.mb_id left join `order` as o on p.pd_id = o.pd_id where {$search}");
if(!$page)
    $page=1;
else
    $page++;
$total=$total['cnt'];
$rows=10;
$start=($page-1)*$rows;
$total_page=ceil($total/$rows);
//echo $search;
$sqls = "select STRAIGHT_JOIN p.* {$sel} {$sel_sum},m.mb_id,o.od_id,m.mb_level from `product` as p use index (idx_product) left join `g5_member` as m on p.mb_id = m.mb_id left join `order` as o on o.pd_id = p.pd_id {$joins}, (select count(pd_id)as cnt from `product` where {$psearch}) as total where {$search} group by p.pd_id {$od} limit {$start},{$rows}";
//$sqls = "select STRAIGHT_JOIN p.* {$sel} {$sel_sum},m.mb_id,o.od_id,m.mb_level from `product` as p use index (idx_product) left join `g5_member` as m on p.mb_id = m.mb_id left join `order` as o on o.pd_id = p.pd_id {$joins}, (select count(pd_id)as cnt from `product` where {$psearch}) as total where {$search} {$od} ";
//echo $sqls;
$res = sql_query($sqls);
while($row = sql_fetch_array($res)){
    $list[] = $row;
    $pd_ids2[] = $row["pd_id"];
}

//ad 가져오기
if($cate1){
    $sql = "select * from `categorys` where cate_name = '{$cate1}' and cate_type = '{$set_type}' limit 0, 1";
    $res = sql_fetch($sql);
    if($res["ca_id"]) {
        $where .= " and ad_cate = '{$res["ca_id"]}'";
    }
}
if($cate2){
    $sql = "select * from `categorys` where cate_name = '{$cate2}' and cate_type = '{$set_type}' limit 0, 1";
    $res = sql_fetch($sql);
    if($res["ca_id"]) {
        $where .= " and ad_cate2 = '{$res["ca_id"]}'";
    }
}
if($where=="")
    //$where = " and ad_cate = '0' and ad_cate2 = '0'";

if($stx){
    if($cate){
        $where .= "and ad_keyword like '%{$stx}%'";
    }else {
        $where = " and ad_keyword like '%{$stx}%'";
    }
}

if($_REQUEST["set_type"]){
    $where .= " and ad_type = '{$_REQUEST["set_type"]}'";
}


if($cate1 || $cate2 || $stx) {
    $today = date("Y-m-d H:i");
    $sqlad = "select * from `product_ad` where ad_status = 0 and  '{$today}' >= DATE_FORMAT(CONCAT(ad_from,' ',ad_from_hour,':',ad_from_min), '%Y-%m-%d %H:%i') and '{$today}' < DATE_FORMAT(CONCAT(ad_to,' ',ad_to_hour,':',ad_to_min), '%Y-%m-%d %H:%i') {$where}";
    $res = sql_query($sqlad);
    //$a = 0;
    while ($row = sql_fetch_array($res)) {
        $listadd[] = $row;
        /*if ($type2 == "8") {
            if ($row["ad_sort"] > $total) {
                $listadd[$a]["ad_sort"] = $total;
            }
        } else if ($type2 == "4") {
            if ($row["ad_sort2"] > $total) {
                $listadd[$a]["ad_sort2"] = $total;
            }
        }
        $a++;*/
    }

}
//검색 최근 목록 저장
if($sc_id) {
    $sql = "select *,count(*)as cnt from `save_read` where sc_id = '{$sc_id}' and mb_id = '{$wished_id}' ";
    $saves = sql_fetch($sql);
    $save_pd_id_all = explode(",",$saves["pd_ids"]);

    //항상 업데이트
    if($saves["cnt"] == 0){//등록
        if(count($pd_ids2)>0) {
            $pd_id_all = implode(",", $pd_ids2);
            $sql = "insert into `save_read` set pd_ids = '{$pd_id_all}', save_datetime = now(), mb_id = '{$wished_id}', sc_id = '{$sc_id}'";
            //echo $sql;
            sql_query($sql);
        }
    }else {//업데이트
        if(count($pd_ids2)>0) {
            $diff = array_diff($pd_ids2,$save_pd_id_all);
            if(count($diff)>0) {
                if ($saves["pd_ids"] != "") {
                    $pd_id_all = $saves["pd_ids"] . "," . implode(",", $diff);
                } else {
                    $pd_id_all = implode(",", $diff);
                }
            }
        }
        if($pd_id_all!="") {
            $sql = "update `save_read` set pd_ids = '{$pd_id_all}' where sc_id = '{$sc_id}' and mb_id = '{$wished_id}'";
            //echo $sql;
            sql_query($sql);
        }
    }
}

if($wished_id)
    $sql = "select * from `wish_product` where mb_id ='{$wished_id}'";
$res = sql_query($sql);
while($row = sql_fetch_array($res)){
    $wished[] = $row;
}
?>