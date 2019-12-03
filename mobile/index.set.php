<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

$ss_id = session_id();
if($member["mb_id"]){
    $wished_id = $member["mb_id"];
}else{
    $wished_id = $ss_id;
}
//검색 기본값
$search = "p.pd_status = 0 and p.pd_blind < 10 and p.pd_blind_status = 0 ";

if($searchActive==""){
    $searchActive = "none";
}

if($member["mb_id"]){
    $mb_id = $member["mb_id"];
}else{
    $mb_id = session_id();
}

$pageWasRefreshed = isset($_SERVER['HTTP_CACHE_CONTROL']) && $_SERVER['HTTP_CACHE_CONTROL'] === 'max-age=0';

if($pageWasRefreshed ) {
    //새로고침하면 스킵
} else {
    if ($searchActive != "save") {
        //그냥 검색일경우
        if ($stx) {
            //전체 검색어 업데이트
            $sql = "insert into `g5_popular` (pp_word,pp_date,pp_ip,mb_id,pp_type) values ('{$stx}', now(), '{$_SERVER["REMOTE_ADDR"]}' , '{$member["mb_id"]}','{$set_type}')";
            sql_query($sql);
            //저장된 검색어를 불러올 경우 실행
            //검색목록 저장 or 업데이트
            //if(!$set_sc_id) {
            $sql = "insert into `my_search_list` set sc_type = '{$set_type}', sc_type2='{$type2}', sc_cate1 = '{$cate}', sc_cate2 = '{$cate2}', sc_priceFrom = '{$priceFrom}', sc_priceTo = '{$priceTo}', sc_align='{$order_sort}',sc_align_active='{$order_sort_active}', sc_tag = '{$stx}', mb_id = '{$mb_id}', sc_datetime = now(), sc_savetype = 1,sc_level = '{$mb_level}', set_alarm = 0";
            sql_query($sql);
        }
    } else if ($searchActive == "save") {
        //전체 검색어 업데이트
        $sql = "insert into `g5_popular` (pp_word,pp_date,pp_ip,mb_id,pp_type) values ('{$stx}', now(), '{$_SERVER["REMOTE_ADDR"]}' , '{$member["mb_id"]}','{$set_type}')";
        sql_query($sql);

        $sql = "insert into `my_search_list` set sc_type = '{$set_type}', sc_type2='{$type2}', sc_cate1 = '{$cate}', sc_cate2 = '{$cate2}', sc_tag = '{$stx}', mb_id = '{$mb_id}', sc_datetime = now(), sc_priceFrom = '{$priceFrom}', sc_priceTo = '{$priceTo}',sc_align='{$order_sort}',sc_align_active='{$order_sort_active}', sc_price_type1 = '{$pd_price_type1}', sc_price_type2 = '{$pd_price_type2}', sc_price_type3 = '{$pd_price_type3}', sc_timeFrom = '{$pd_timeFrom}' , sc_timeTo = '{$pd_timeTo}' , sc_timeType = '{$pd_timeType}', sc_savetype = 2, set_alarm = '{$set_status}',sc_level = '{$mb_level}'";
        sql_query($sql);
        $sc_id = sql_insert_id();
    }
}

if($sc_id){
    $sql = "select *,count(*)as cnt from `save_read` where sc_id = '{$sc_id}' and mb_id = '{$wished_id}' ";
    $saves = sql_fetch($sql);
    $save_pd_id_all = explode(",",$saves["pd_ids"]);

    $searchActive = "search";
    $sql = "select * from `my_search_list` where sc_id = '{$sc_id}'";
    $schopt = sql_fetch($sql);

    $set_type = $schopt["sc_type"];
    $type2 = $schopt["sc_type2"];
    $cate = $schopt["sc_cate1"];
    $cate2 = $schopt["sc_cate2"];
    $stx = $schopt["sc_tag"];
    $order_sort = $schopt["sc_align"];
    $order_sort_active = $schopt["sc_align_active"];
    $priceFrom = $schopt["sc_priceFrom"];
    $priceTo = $schopt["sc_priceTo"];
    $pd_price_type1 = $schopt["sc_price_type1"];
    $pd_price_type2 = $schopt["sc_price_type2"];
    $pd_price_type3 = $schopt["sc_price_type3"];
    $pd_timeFrom = $schopt["sc_timeFrom"];
    $pd_timeTo = $schopt["sc_timeTo"];
    $pd_timeType = $schopt["sc_timeType"];
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
}else{
    $set_type = $_SESSION["type1"];
    $type2 = $_SESSION["type2"];
    $cate = $_SESSION["cate"];
    $cate2 = $_SESSION["cate2"];
    //if($searchActive!="") {
        $stx = $_SESSION["stx"];
    //}
    $order_sort = $_SESSION["order_sort"];
    $order_sort_active = $_SESSION["order_sort_active"];
    $priceFrom = $_SESSION["priceFrom"];
    $priceTo = $_SESSION["priceTo"];
    $pd_price_type1 = $_SESSION["pd_price_type1"];
    $pd_price_type2 = $_SESSION["pd_price_type2"];
    $pd_price_type3 = $_SESSION["pd_price_type3"];
    $pd_timeFrom = $_SESSION["pd_timeFrom"];
    $pd_timeTo = $_SESSION["pd_timeTo"];
    $pd_timeType = $_SESSION["pd_timetype"];
    $mb_level = $_SESSION["mb_level"];
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

//검색 정렬 기본값
if($_SESSION["list_basic_order"]=="location"){
    $od = " order by p.pd_update desc, p.pd_hits desc, ISNULL(p.pd_lat) asc, distance asc";
}else {
    $od = " order by p.pd_update desc, p.pd_hits desc";
}

if($set_type){
    $search .= " and p.pd_type = {$set_type}";
    $type1 = $set_type;

    /*if($type1==1){
        $search .= " and isnull(o.od_id) ";
    }*/
}else{
    if($_SESSION["type1"]==2 || $_SESSION["type1"]==""){
        $set_type = 2;
        $search .= " and p.pd_type = 2";
    }else{
        $set_type = 1;
        $search .= " and p.pd_type = 1";
    }
}

if($type2){
    $search .= " and p.pd_type2 = {$type2}";
}else{
    $search .= " and p.pd_type2 = 8";
}

if($stx){
    $search .= " and (p.pd_name like '%{$stx}%' or p.pd_tag like '%{$stx}%' )";
}
if($cate){
    $search .= " and p.pd_cate = '{$cate}'";
}
if($cate2){
    $search .= " and p.pd_cate2 = '{$cate2}'";
}

if($priceFrom!='' && $priceTo!=''){
    $search .= " and {$priceFrom} <= (p.pd_price+p.pd_price2) and {$priceTo} >= (p.pd_price+p.pd_price2)";
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
    $priceFrom = $cateminmax["min"];
    $priceTo = $cateminmax["max"];
    $search .= " and (p.pd_price+p.pd_price2) between {$cateminmax["min"]} and {$cateminmax["max"]}";
}

if($set_type==2) {
    $pdchk1="off";
    $pdchk2="off";
    if($pd_price_type1==0 || $pd_price_type2==1 || $pd_price_type3==2){
        $search .= " and (";
    }
    if ($pd_price_type1 == 0) {
        $pdchk1 = "on";
        $search .= " p.pd_price_type = {$pd_price_type1}";
    }
    if($pdchk1=="on" && $pd_price_type2 == 1 || $pd_price_type3 == 2){
        $search .= " or ";
    }
    if ($pd_price_type2 == 1) {
        $pdchk2 = "on";
        $search .= " p.pd_price_type = {$pd_price_type2}";
    }
    if($pdchk2=="on" && $pd_price_type3 == 2){
        $search .= " or ";
    }
    if($pd_price_type3 == 2) {
        $search .= " p.pd_price_type = {$pd_price_type3}";
    }
    if($pd_price_type1==0 || $pd_price_type2==1 || $pd_price_type3==2){
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

$search .= " and p.pd_id not in (select pd_id from `my_trash` where mb_id = '{$wished_id}') ";
$psearch = str_replace("p.","",$search);
/*$psearch = str_replace("and isnull(o.od_id) ","",$psearch);*/

if($mb_level=="on"){
}else{
    $search .= " and m.mb_level = 2 ";
}

if($order_sort){
    $od = "";
    $order_sorts = explode(",",$order_sort);
    $actives = explode(",",$order_sort_active);
    for($i=0;$i<count($order_sorts);$i++){
        //if($i!=0) {
        //    $rownum = -5 + $i;
        //}else{
        $rownum = 0;
        //}
        /*if($order_sorts[$i]=="pd_date"){
            if($actives[$i] == 1){
                $checked[$i] = "checked";
                $sels[] .= "updates";
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
                $sels[] .= "prices";
                $joins .= " left join (select pd_id, @pricesum := @pricesum + 1 as prices from `product`, (SELECT @pricesum := {$rownum}) as R where {$psearch} order by pd_price + pd_price desc ) c on p.pd_id = c.pd_id ";
                $ods[] = " p.pd_price + p.pd_price2 asc";
            }
            $order_item[] = '<label class="align" id="sortable" for="pd_price">'.
                '<input type="checkbox" name="orders[]" value="pd_price" id="pd_price" '.$checked[$i].' >'.
                '<span class="round">가격순</span></label>';
        }
        if($order_sorts[$i]=="pd_recom"){
            if($actives[$i] == 1){
                $checked[$i] = "checked";
                $sels[] .= "recoms";
                $joins .= " left join (select pd_id, @recomsum := @recomsum + 1 as recoms from `product`, (SELECT @recomsum := {$rownum}) as R where {$psearch} order by pd_recom asc) d on p.pd_id = d.pd_id ";
                $ods[] = " p.pd_recom desc";
            }
            $order_item[] = '<label class="align" id="sortable" for="pd_recom">'.
                '<input type="checkbox" name="orders[]" value="pd_recom" id="pd_recom" '.$checked[$i].' >'.
                '<span class="round">추천순</span></label>';
        }
        if($order_sorts[$i]=="pd_hits"){
            if($actives[$i] == 1){
                $checked[$i] = "checked";
                $sels[] .= "hits";
                $joins .= " left join (select pd_id, @hitsnum := @hitsnum + 1 as hits from `product`, (SELECT @hitsnum := {$rownum}) as R where {$psearch} order by pd_hits asc) b on p.pd_id = b.pd_id ";
                $ods[] = " p.pd_hits desc";
            }
            $order_item[] = '<label class="align" id="sortable" for="pd_hits">'.
                '<input type="checkbox" name="orders[]" value="pd_hits" id="pd_hits" '.$checked[$i].' >'.
                '<span class="round">인기순</span></label>';
        }
        if($order_sorts[$i]=="pd_loc"){
            if($actives[$i] == 1){
                $checked[$i] = "checked";
                if($_SESSION["lat"] && $_SESSION["lng"]) {
                    $joins .= " left join (select 6371 * 2 * ATAN2(SQRT(POW(SIN(RADIANS({$_SESSION["lat"]} - pd_lat)/2), 2) + POW(SIN(RADIANS({$_SESSION["lng"]} - pd_lng)/2), 2) * COS(RADIANS(pd_lat)) * COS(RADIANS({$_SESSION["lat"]}))), SQRT(1 - POW(SIN(RADIANS({$_SESSION["lat"]} - pd_lat)/2), 2) + POW(SIN(RADIANS({$_SESSION["lng"]} - pd_lng)/2), 2) * COS(RADIANS(pd_lat)) * COS(RADIANS({$_SESSION["lat"]})))) AS dd, pd_id , @locsum := @locsum + 1 as distances from `product`,(select @locsum := {$rownum}) as R where {$psearch} order by dd desc) e on p.pd_id = e.pd_id ";
                    $sels[] .= "distances";
                    $ods[] = " ISNULL(p.pd_lat) desc, distance desc";
                }
            }
            $order_item[] = '<label class="align" id="sortable" for="pd_loc">'.
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
    //$od = " order by ". implode(",",$ods);
    if($sc_id) {
        $od = " order by p.pd_update desc, sums desc";
    }else{
        $od = " order by p.pd_update desc, sums desc";
    }

    //if(count($ods)>0)
    //$od = " order by ". implode(",",$ods);
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
    /*if($_SESSION["lat"] && $_SESSION["lng"]){
        $sel .= " , 6371 * 2 * ATAN2(SQRT(POW(SIN(RADIANS({$_SESSION["lat"]} - p.pd_lat)/2), 2) + POW(SIN(RADIANS({$_SESSION["lng"]} - p.pd_lng)/2), 2) * COS(RADIANS(p.pd_lat)) * COS(RADIANS({$_SESSION["lat"]}))), SQRT(1 - POW(SIN(RADIANS({$_SESSION["lat"]} - p.pd_lat)/2), 2) + POW(SIN(RADIANS({$_SESSION["lng"]} - p.pd_lng)/2), 2) * COS(RADIANS(p.pd_lat)) * COS(RADIANS({$_SESSION["lat"]})))) AS distance";
    }*/
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

//echo "select count(*) as cnt from `product` as p use index (idx_product) left join `g5_member` as m on p.mb_id = m.mb_id left join `order` as o on p.pd_id = o.pd_id where {$search}";

$total=sql_fetch("select count(*) as cnt from `product` as p use index (idx_product) left join `g5_member` as m on p.mb_id = m.mb_id left join `order` as o on p.pd_id = o.pd_id where {$search}");
if(!$page)
    $page=1;
$total=$total['cnt'];
$rows=10;
$start=($page-1)*$rows;
$total_page=ceil($total/$rows);

//내 게시글 블라인드 처리 확인
$sql = "select * from `product` where mb_id = '{$wished_id}' and (pd_blind >= 10 or pd_blind_status = 1) and pd_blind_userchk = 0 order by pd_date asc limit 0 , 1";
$myblind = sql_fetch($sql);

$sql = "select STRAIGHT_JOIN p.* {$sel} {$sel_sum},m.mb_id,o.od_id,m.mb_level from `product` as p use index (idx_product) left join `g5_member` as m on p.mb_id = m.mb_id left join `order` as o on p.pd_id = o.pd_id {$joins}, (select count(pd_id)as cnt from `product` where {$psearch}) as total where {$search} group by p.pd_id {$od} limit {$start},{$rows}";
$res = sql_query($sql);
while($row = sql_fetch_array($res)){
    $list[] = $row;
    $pd_ids[] = $row["pd_id"];
}
/*
if(count($list) > 0) {
    $test = array();
    foreach ($list as $item) {
        $test[] = $item['pd_date'];
    }
    array_multisort($test, SORT_DESC, $list);
}
*/
/*
$sql = "select * {$sel} from `product` as p left join `g5_member` as m on p.mb_id = m.mb_id where {$search} {$od}";
$res = sql_query($sql);
while($row = sql_fetch_array($res)){
    $pd_ids[] = $row["pd_id"];
}*/

//검색 최근 목록 저장
if($sc_id) {
    //항상 업데이트
    if($saves["cnt"] == 0){//등록
        if(count($pd_ids)>0) {
            $pd_id_all = implode(",", $pd_ids);
            $sql = "insert into `save_read` set pd_ids = '{$pd_id_all}', save_datetime = now(), mb_id = '{$wished_id}', sc_id = '{$sc_id}'";
            //echo $sql;
            sql_query($sql);
        }
    }else {//업데이트
        if(count($pd_ids)>0) {
            $diff = array_diff($pd_ids,$save_pd_id_all);
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

//ad 가져오기
if($cate){
    $sql = "select * from `categorys` where cate_name = '{$cate}' and cate_type = '{$set_type}' limit 0, 1";
    $res = sql_fetch($sql);
    if($res["ca_id"]) {
        $where = " and ad_cate = '{$res["ca_id"]}'";
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
    $where .= " and ad_keyword like '%{$stx}%'";
}
if($_SESSION["type1"]){
    $where .= " and ad_type = '{$_SESSION["type1"]}'";
}
if($cate || $cate2 || $stx) {
    $today = date("Y-m-d H:i");
    $sql = "select * from `product_ad` where ad_status = 0 and  '{$today}' >= DATE_FORMAT(CONCAT(ad_from,' ',ad_from_hour,':',ad_from_min), '%Y-%m-%d %H:%i') and '{$today}' < DATE_FORMAT(CONCAT(ad_to,' ',ad_to_hour,':',ad_to_min), '%Y-%m-%d %H:%i') {$where}";
    $res = sql_query($sql);
    while ($row = sql_fetch_array($res)) {
        $listadd[] = $row;
    }
}
?>