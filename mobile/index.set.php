<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

//$ss_id = session_id();
if($member["mb_id"]){
    $wished_id = $member["mb_id"];
}
//검색 기본값
$search = "p.pd_status = 0 and p.pd_blind < 10 and p.pd_blind_status = 0 and m.mb_leave_date = '' ";
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
    if ($stx) {
        //전체 검색어 업데이트
        $sql = "insert into `g5_popular` (pp_word,pp_date,pp_ip,mb_id,pp_type) values ('{$stx}', now(), '{$_SERVER["REMOTE_ADDR"]}' , '{$member["mb_id"]}','{$set_type}')";
        sql_query($sql);
    }
    if ($searchActive != "save") {
        if($is_member) {
            //그냥 검색일경우
            if ($stx) {
                //검색목록 저장 or 업데이트
                //if(!$set_sc_id) {
                $sql = "insert into `my_search_list` set sc_type = '{$set_type}', sc_type2='{$type2}', sc_cate1 = '{$cate}', sc_cate2 = '{$cate2}', sc_priceFrom = '{$priceFrom}', sc_priceTo = '{$priceTo}', sc_align='{$order_sort}',sc_align_active='{$order_sort_active}', sc_tag = '{$stx}', mb_id = '{$mb_id}', sc_datetime = now(), sc_savetype = 1,sc_level = '{$mb_level}', set_alarm = 0";
                sql_query($sql);
            }
        }
    } else if ($searchActive == "save") {
        //전체 검색어 업데이트
        /*$sql = "insert into `g5_popular` (pp_word,pp_date,pp_ip,mb_id,pp_type) values ('{$stx}', now(), '{$_SERVER["REMOTE_ADDR"]}' , '{$member["mb_id"]}','{$set_type}')";
        sql_query($sql);*/
        //print_r2($_REQUEST);
        if($pd_price_type1=="off" || $pd_price_type1 == ""){
            $pd_price_type1 = -1;
        }
        if($pd_price_type2=="off" || $pd_price_type2 == ""){
            $pd_price_type2 = -1;
        }
        if($pd_price_type3=="off" || $pd_price_type3 == ""){
            $pd_price_type3 = -1;
        }
        if($mb_level=="off"){
            $mb_level = -1;
        }

        $sql = "insert into `my_search_list` set sc_type = '{$set_type}', sc_type2='{$type2}', sc_cate1 = '{$cate}', sc_cate2 = '{$cate2}', sc_tag = '{$stx}', mb_id = '{$mb_id}', sc_datetime = now(), sc_priceFrom = '{$priceFrom}', sc_priceTo = '{$priceTo}',sc_align='{$order_sort}',sc_align_active='{$order_sort_active}', sc_price_type1 = '{$pd_price_type1}', sc_price_type2 = '{$pd_price_type2}', sc_price_type3 = '{$pd_price_type3}', sc_timeFrom = '{$pd_timeFrom}' , sc_timeTo = '{$pd_timeTo}' , sc_timeType = '{$pd_timeType}', sc_savetype = 2, set_alarm = '{$set_status}',sc_level = '{$mb_level}'";
        sql_query($sql);
        $sc_id = sql_insert_id();
    }
}

if($sc_id){
    $searchActive = "search";
    $sql = "select * from `my_search_list` where sc_id = '{$sc_id}'";
    $schopt = sql_fetch($sql);
    $set_type = $schopt["sc_type"];
    $_SESSION["type1"] = $set_type;
    $type2 = $schopt["sc_type2"];
    $cate = $schopt["sc_cate1"];
    $cate2 = $schopt["sc_cate2"];
    $stx = explode(" ",$schopt["sc_tag"]);
    $order_sort = $schopt["sc_align"];
    $order_sort_active = $schopt["sc_align_active"];
    $scPriceFrom = $schopt["sc_priceFrom"];
    $scPriceTo = $schopt["sc_priceTo"];
    //echo $scPriceFrom."//".$scPriceTo;
    $pd_price_type1 = $schopt["sc_price_type1"];
    $pd_price_type2 = $schopt["sc_price_type2"];
    $pd_price_type3 = $schopt["sc_price_type3"];
    $pd_timeFrom = $schopt["sc_timeFrom"];
    $pd_timeTo = $schopt["sc_timeTo"];
    $pd_timeType = $schopt["sc_timeType"];
    $mb_level = $schopt["sc_level"];
    if($mb_level == -1 ){
        $mb_level = "off";
    }else{
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
    //읽은 게시글 가져오기
    $sql = "select * from `save_read` where sc_id = '{$sc_id}'";
    $res = sql_query($sql);
    while($row = sql_fetch_array($res)){
        $read_pd_id[] = $row["pd_ids"];
    }

}else{
    $set_type = $_SESSION["type1"];
    $type2 = $_SESSION["type2"];
    $cate = $_SESSION["cate"];
    $cate2 = $_SESSION["cate2"];
    //if($searchActive!="") {
    if($_REQUEST["stx"]=="") {
        $stx = explode(" ", $_SESSION["stx"]);
    }
    //}
    $order_sort = $_SESSION["order_sort"];
    $order_sort_active = $_SESSION["order_sort_active"];
    $scPriceFrom = $_SESSION["priceFrom"];
    $scPriceTo = $_SESSION["priceTo"];
    $pd_price_type1 = $_SESSION["pd_price_type1"];
    $pd_price_type2 = $_SESSION["pd_price_type2"];
    $pd_price_type3 = $_SESSION["pd_price_type3"];
    $pd_timeFrom = $_SESSION["pd_timeFrom"];
    $pd_timeTo = $_SESSION["pd_timeTo"];
    $pd_timeType = $_SESSION["pd_timetype"];
    $mb_level = $_SESSION["mb_level"];
    if($mb_level==-1){
        $mb_level = "off";
    }else{
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
if($sc_id=="") {
    if ($_SESSION["list_basic_order"] == "location") {
        $od = " order by p.pd_update desc, ISNULL(p.pd_lat) asc, distance asc, p.pd_price desc";
        $order_sort = "pd_loc,pd_price,pd_hits,pd_recom";
        $order_sort_active = "1,1,0,0";
    } else {
        if ($app || $app2) {
            $od = " order by p.pd_update desc, ISNULL(p.pd_lat) asc, distance asc, p.pd_price desc";
            $order_sort = "pd_loc,pd_price,pd_hits,pd_recom";
            $order_sort_active = "1,1,0,0";
        } else {
            $od = " order by p.pd_update desc, p.pd_price desc";
            $order_sort = "pd_price,pd_loc,pd_hits,pd_recom";
            $order_sort_active = "1,0,0,0";
        }
    }
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
if($type2==""){
    $type2 = 8;
}

if($type2){
    $search .= " and p.pd_type2 = {$type2}";
}else{
    $search .= " and p.pd_type2 = 8";
}

if(!is_array($stx)){
    $stx = explode(" ",$stx);
}

if (count($stx)==1) {
    $search .= " and (p.pd_name like '%{$stx[0]}%' or p.pd_tag like '%{$stx[0]}%')";

    /*if($is_member) {
        //전체 검색어 업데이트
        $sql = "insert into `g5_popular` (pp_word,pp_date,pp_ip,mb_id,pp_type) values ('{$stx[0]}', now(), '{$_SERVER["REMOTE_ADDR"]}' , '{$member["mb_id"]}','{$set_type}')";
        sql_query($sql);
    }*/
}else{
    for($i=0;$i<count($stx);$i++){
        if($stx[$i]=="") continue;
        if($stxs) $stxs .= " and ";
        $stxs .= "( p.pd_name like '%{$stx[$i]}%' or p.pd_tag like '%{$stx[$i]}%' )";

        /*if($is_member) {
            //전체 검색어 업데이트
            $sql = "insert into `g5_popular` (pp_word,pp_date,pp_ip,mb_id,pp_type) values ('{$stx[0]}', now(), '{$_SERVER["REMOTE_ADDR"]}' , '{$member["mb_id"]}','{$set_type}')";
            sql_query($sql);
        }*/
    }
    $search .=  " and (" . $stxs ." )";
}

if($cate){
    $search .= " and p.pd_cate = '{$cate}'";
}
if($cate2){
    $search .= " and p.pd_cate2 = '{$cate2}'";
}

if($scPriceFrom!='' && $scPriceTo!=''){
    $search .= " and {$scPriceFrom} <= (p.pd_price+p.pd_price2) and {$scPriceTo} >= (p.pd_price+p.pd_price2)";
}else{
    if($cate && $cate2) {
        $sql = "select MAX(pd_price+pd_price2) as max, MIN(pd_price+pd_price2) as min from `product` where pd_cate = '{$cate}' and pd_cate2 = '{$cate2}' and pd_status = 0";
        $cateminmax = sql_fetch($sql);
    }else if($cate && !$cate2){
        $sql = "select MAX(pd_price+pd_price2) as max, MIN(pd_price+pd_price2) as min from `product` where pd_cate = '{$cate}' and pd_status = 0";
        $cateminmax = sql_fetch($sql);
    }else if(!$cate && !$cate2){
        $sql = "select MAX(pd_price+pd_price2) as max, MIN(pd_price+pd_price2) as min from `product` where pd_type = '{$set_type}' and pd_type2 = '{$type2}' and pd_status = 0";
        $cateminmax = sql_fetch($sql);
    }
    $search .= " and (p.pd_price+p.pd_price2) between {$cateminmax["min"]} and {$cateminmax["max"]}";
}
if($set_type==2) {
    $pdchk1="off";
    $pdchk2="off";
    if($pd_price_type1 != "off" || $pd_price_type2 != "off" || $pd_price_type3 != "off"){
        $search .= " and (";
    }
    if($pd_price_type1 != "off" || $pd_price_type1==0) {
        $pdchk1 = "on";
        $search .= " p.pd_price_type = 0";
    }
    if($pdchk1=="on" && ($pd_price_type2 != "off" || $pd_price_type3 != "off")){
        $search .= " or ";
    }
    if ($pd_price_type2 != "off") {
        $pdchk2 = "on";
        $search .= " p.pd_price_type = 1";
    }
    if($pdchk2=="on" && $pd_price_type3 != "off"){
        $search .= " or ";
    }
    if($pd_price_type3 != "off") {
        $search .= " p.pd_price_type = 2";
    }
    if($pd_price_type1 != "off" || $pd_price_type2 != "off" || $pd_price_type3 != "off"){
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
$psearch = str_replace("and m.mb_leave_date = ''","",$psearch);

if($mb_level=="on"){

}else{
    $search .= " and m.mb_level = 2 ";
}

if($order_sort){
    $od = "";
    $order_sorts = explode(",",$order_sort);
    $actives = explode(",",$order_sort_active);
    $checked[] = "checked";
        $sels[] = "updates";
        $joins .= " left join (select pd_id, @updatenum := @updatenum + 1 as updates from `product`, (SELECT @updatenum := 0) as R where {$psearch} order by pd_update) a on p.pd_id = a.pd_id ";
        $ods[] = " p.pd_update desc";
    for($i=0;$i<count($order_sorts);$i++){

        $rownum = 0;
        if($order_sorts[$i]=="pd_price"){
            if($actives[$i] == 1){
                $checked[$i] = "checked";
                $sels[] .= "prices";
                $joins .= " left join (select pd_id, @pricesum := @pricesum + 1 as prices from `product`, (SELECT @pricesum := {$rownum}) as R where {$psearch} order by pd_price + pd_price2 desc ) c on p.pd_id = c.pd_id ";
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
                if($set_type==1){
                    $joins .= " left join (select mb_id, @recomsum := @recomsum + 1 as recoms from `g5_member` , (SELECT @recomsum := {$rownum}) as R order by mb_4 asc) d on p.mb_id = d.mb_id ";
                    $total2 = ", (select count(mb_id) as cnt from `g5_member` ) as total2";
                    $ods[] = " m.mb_4 desc";
                }else {
                    $joins .= " left join (select pd_id, @recomsum := @recomsum + 1 as recoms from `product`, (SELECT @recomsum := {$rownum}) as R where {$psearch} order by pd_recom asc) d on p.pd_id = d.pd_id ";
                    $total2 = ", (select count(pd_id) as cnt from `product` where {$psearch} order by pd_recom desc) as total2";
                    $ods[] = " p.pd_recom desc";
                }
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
            //$sel .= ",ceil((".$sels[$i]."/total.cnt) * 100) as {$sels[$i]}";
            if($sels[$i]=="recoms" && $set_type == 2){
                $sel .= ",ceil((if(pd_recom = 0 , 0 ," . $sels[$i] . ")/total2.cnt)*100) as {$sels[$i]}";
            }else if($sels[$i]=="recoms" && $set_type == 1){
                $sel .= ",ceil((" . $sels[$i] . "/total2.cnt)*100) as {$sels[$i]}";
            }else {
                $sel .= ",ceil((" . $sels[$i] . "/total.cnt)*100) as {$sels[$i]}";
            }
        }
        //$sel_sum = ",".implode("+",$sels)." as sums ";
        if(count($sels)==1){
            if($sels[0]=="recoms" && $set_type == 2){
                $sel_sum .= ",ceil((if(pd_recom=0,0,{$sels[0]})/total2.cnt)*100) as sums";
            }else if($sels[0]=="recoms" && $set_type == 1){
                $sel_sum .= ",ceil(({$sels[0]}/total2.cnt)*100) as sums";
            }else {
                $sel_sum .= ",ceil((" . $sels[0] . "/total.cnt)*100) as sums";
            }
        }else if(count($sels)==2){
            for($i=0;$i<count($sels);$i++){
                if($i==0) $sel_sum = ", (";
                if($sels[$i]=="recoms" && $set_type == 2){
                    $sel_sum .= "(ceil((if(pd_recom=0,0,{$sels[$i]})/total2.cnt) * 100) ";
                }else if($sels[$i]=="recoms" && $set_type == 1){
                    $sel_sum .= "(ceil(({$sels[$i]}/total2.cnt)*100)";
                }else{
                    $sel_sum .= "(ceil(({$sels[$i]}/total.cnt) * 100) ";
                }
                if($i==0) $sel_sum .= " * (60/100)) + ";
                if($i==1) $sel_sum .= " * (40/100))) as sums ";
            }
        }else if(count($sels)==3){
            for($i=0;$i<count($sels);$i++){
                if($i==0) $sel_sum = ", (";
                if($sels[$i]=="recoms" && $set_type == 2){
                    $sel_sum .= "(ceil((if(pd_recom=0,0,{$sels[$i]})/total2.cnt) * 100) ";
                }else if($sels[$i]=="recoms" && $set_type == 1){
                    $sel_sum .= "ceil(({$sels[$i]}/total2.cnt)*100)";
                }else{
                    $sel_sum .= "(ceil(({$sels[$i]}/total.cnt) * 100) ";
                }
                if($i==0) $sel_sum .= " * (50/100)) + ";
                if($i==1) $sel_sum .= " * (30/100)) + ";
                if($i==2) $sel_sum .= " * (20/100))) as sums ";
            }
        }else if(count($sels)==4){
            for($i=0;$i<count($sels);$i++){
                if($i==0) $sel_sum = ", (";
                if($sels[$i]=="recoms" && $set_type == 2){
                    $sel_sum .= "(ceil((if(pd_recom=0,0,{$sels[$i]})/total2.cnt) * 100) ";
                }else if($sels[$i]=="recoms" && $set_type == 1){
                    $sel_sum .= "(ceil(({$sels[$i]}/total2.cnt)*100)";
                }else{
                    $sel_sum .= "(ceil(({$sels[$i]}/total.cnt) * 100) ";
                }
                if($i==0) $sel_sum .= " * (40/100)) + ";
                if($i==1) $sel_sum .= " * (30/100)) +";
                if($i==2) $sel_sum .= " * (20/100)) + ";
                if($i==3) $sel_sum .= " * (10/100))) as sums ";
            }
        }else if(count($sels)==5){
            for($i=0;$i<count($sels);$i++){
                if($i==0) $sel_sum = ", (";
                if($sels[$i]=="recoms" && $set_type == 2){
                    $sel_sum .= "(ceil((if(pd_recom=0,0,{$sels[$i]})/total2.cnt) * 100) ";
                }else if($sels[$i]=="recoms" && $set_type == 1){
                    $sel_sum .= "(ceil(({$sels[$i]}/total2.cnt)*100)";
                }else{
                    $sel_sum .= "(ceil(({$sels[$i]}/total.cnt) * 100) ";
                }
                if($i==0) $sel_sum .= " * (37/100)) + ";
                if($i==1) $sel_sum .= " * (27/100)) + ";
                if($i==2) $sel_sum .= " * (17/100)) + ";
                if($i==3) $sel_sum .= " * (12/100)) + ";
                if($i==4) $sel_sum .= " * (7/100))) as sums ";
            }
        }
    }
    if($sc_id) {
        $od = " order by sums desc";
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
//echo $search;
$total=sql_fetch("select count(*) as cnt from `product` as p use index (idx_product) left join `g5_member` as m on p.mb_id = m.mb_id left join `order` as o on p.pd_id = o.pd_id where {$search}");
if(!$page)
    $page=1;
$total=$total['cnt'];
$rows=20;
$start=($page-1)*$rows;
$total_page=ceil($total/$rows);

//내 게시글 블라인드 처리 확인
$sql = "select * from `product` where mb_id = '{$wished_id}' and (pd_blind >= 10 or pd_blind_status = 1) and pd_blind_userchk = 0 order by pd_date asc limit 0 , 1";
$myblind = sql_fetch($sql);

$a = 0;


if(count($read_pd_id)>0){
    $rpd_id = implode(",",$read_pd_id);
    $sql = "select STRAIGHT_JOIN p.* {$sel} {$sel_sum},m.mb_id,o.od_id,m.mb_level from `product` as p use index (idx_product) left join `g5_member` as m on p.mb_id = m.mb_id left join `order` as o on p.pd_id = o.pd_id {$joins}, (select count(pd_id)as cnt from `product` where {$psearch}) as total {$total2} where {$search} and p.pd_id not in ({$rpd_id}) group by p.pd_id {$od} ";
    $res = sql_query($sql);

    while($row = sql_fetch_array($res)){
        //if($row["od_id"]!="" && ($row["pd_type"]==1 && $row["od_status"]==1)) continue;
        $list[$a] = $row;
        $list[$a]["new"]=false;
        if($sc_id) {
            if(count($read_pd_id)>0 || $read_pd_id != null) {
                if (in_array($row["pd_id"], $read_pd_id)) {
                    $list[$a]["new"] = false;
                } else {
                    $list[$a]["new"] = true;
                    $sql = "insert into `save_read` set pd_ids = '{$row["pd_id"]}' , save_datetime = now() , mb_id = '{$member["mb_id"]}', sc_id = '{$sc_id}'";
                    sql_query($sql);
                }
            }else{
                $list[$a]["new"] = true;
                $sql = "insert into `save_read` set pd_ids = '{$row["pd_id"]}' , save_datetime = now() , mb_id = '{$member["mb_id"]}', sc_id = '{$sc_id}'";
                sql_query($sql);
            }
        }else{
            $list[$a]["new"] = false;
        }
        $new_pd_id[] = $row["pd_id"];
        $a++;
    }
}

if(count($new_pd_id)>0){
    $not_pd_id = implode(",",$new_pd_id);
    $search .= " and p.pd_id not in ({$not_pd_id}) ";
    $psearch .= " and pd_id not in ({$not_pd_id}) ";
}


$sql = "select STRAIGHT_JOIN p.* {$sel} {$sel_sum},m.mb_id,o.od_id,m.mb_level from `product` as p use index (idx_product) left join `g5_member` as m on p.mb_id = m.mb_id left join `order` as o on p.pd_id = o.pd_id {$joins}, (select count(pd_id)as cnt from `product` where {$psearch}) as total {$total2} where {$search} group by p.pd_id {$od} limit {$start},{$rows}";

$res = sql_query($sql);
while($row = sql_fetch_array($res)){
    //if($row["od_id"]!="" && ($row["pd_type"]==1 && $row["od_status"]==1)) continue;
    $list[$a] = $row;
    $list[$a]["new"] = false;
    if($sc_id) {
        if (count($read_pd_id) == 0) {
            $list[$a]["new"] = true;
            $sql = "insert into `save_read` set pd_ids = '{$row["pd_id"]}' , save_datetime = now() , mb_id = '{$member["mb_id"]}', sc_id = '{$sc_id}'";
            sql_query($sql);
        }
    }

    $a++;
}



if($wished_id)
    $sql = "select * from `wish_product` where mb_id ='{$wished_id}'";
$res = sql_query($sql);
while($row = sql_fetch_array($res)){
    $wished[] = $row;
}

if($where=="")
    //$where = " and ad_cate = '0' and ad_cate2 = '0'";

if(count($stx)==1 && $_REQUEST["stx"]!=""){
    //if($cate){
    //    $where .= "and ad_keyword like '%{$stx[0]}%'";
    //}else {
    $where = " and ad_keyword like '%{$stx[0]}%'";
    //}
}else{
    for($i=0;$i<count($stx);$i++) {
        if($ad_stxs) $ad_stxs .= " or ";
        $ad_stxs .= " (ad_keyword like '%{$stx[$i]}%')";
    }
    $where = " and (".$ad_stxs.") ";
}

if($_SESSION["type1"]){
    $where .= " and ad_type = '{$_SESSION["type1"]}'";
}

if($cate1 || $cate2 || $stx && (is_array($stx) && $stx[0] != "")) {
    $today = date("Y-m-d H:i");
    $sqlad = "select * from `product_ad` where ad_status = 0 and  '{$today}' >= DATE_FORMAT(CONCAT(ad_from,' ',ad_from_hour,':',ad_from_min), '%Y-%m-%d %H:%i') and '{$today}' < DATE_FORMAT(CONCAT(ad_to,' ',ad_to_hour,':',ad_to_min), '%Y-%m-%d %H:%i') {$where}";
    $res = sql_query($sqlad);
    while ($row = sql_fetch_array($res)) {
        $listadd[] = $row;
    }
}

$stx = implode(" ",$stx);

?>