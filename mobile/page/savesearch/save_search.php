<?php 
include_once("../../../common.php");

$align = $_REQUEST["order_sort"];
$sort = explode(",",$align);
$alignActive = explode(",",$_REQUEST["order_sort_active"]);

for($i=0;$i<count($sort);$i++){
    if($sort[$i]=="pd_date") {
        $sorts .= " , sc_od_date = '{$alignActive[$i]}'";
    }
    if($sort[$i]=="pd_hits") {
        $sorts .= " , sc_od_hit = '{$alignActive[$i]}'";
    }
    if($sort[$i]=="pd_recom") {
        $sorts .= " , sc_od_recom = '{$alignActive[$i]}'";
    }
    if($sort[$i]=="pd_loc") {
        $sorts .= " , sc_od_loc = '{$alignActive[$i]}'";
    }
    if($sort[$i]=="pd_price") {
        $sorts .= " , sc_od_price = '{$alignActive[$i]}'";
    }
}

if($member["mb_id"]==""){
    $mb_id = session_id();
}else{
    $mb_id = $member[mb_id];
}

$filter = explode(",",$config["cf_filter"]);

for($i=0;$i<count($filter);$i++){
    if(strpos($sch_text,$filter[$i])!==false){
        $text = string_star($filter[$i],'harf','left');
        alert("검색어에 부적절한 단어[{$text}]가 포함되어 있습니다.");
        eixt;
    }
}

if($formtype == "write"){
    if($member["mb_id"]==""){
        alert("비회원은 로그인후 이용 바랍니다. ", G5_BBS_URL."/login.php");
        return false;
    }
    if($_SESSION["lat"] && $_SESSION["lng"]){
        $set = ", pd_lat = '{$_SESSION['lat']}' , pd_lng = '{$_SESSION['lng']}'";
    }
    $con = number_format($priceFrom) . "원 에서 ".number_format($priceTo)."원 사이선에서 구매 원합니다.";
    $sql = "insert into `product` set pd_type = {$wr_type}, pd_type2 = 4, pd_cate='{$cate}', pd_cate2= '{$cate2}', pd_name = '{$stx}', pd_tag = '{$stx}', pd_content= '{$con}', mb_id = '{$member["mb_id"]}', pd_date = now(), pd_status = 0, pd_price = '{$priceFrom}' {$set} ";
    if(sql_query($sql)){
        alert($sch_text."가 ".$cate."의 ".$cate2."카테고리에 [삽니다]글로 등록되었습니다.");
    }else{
        alert("등록 처리 오류입니다. 다시 시도해 주세요.");
    }
}else {
    if ($formtype == "save") {
        if($saveAgree == "Y") {
            $msg = "검색 설정을 저장하였습니다.";
            $save = " , sc_status = 1 , set_alarm = 1";
        }else{
            $msg = "검색 설정을 저장하였습니다.";
            $save = " , sc_status = 0 , set_alarm = 0";
        }
    }

    $sql = "insert into `my_search_list` set sc_type = '{$set_type}', sc_type2='{$type2}', sc_cate1 = '{$cate}', sc_cate2 = '{$cate2}', sc_tag = '{$sch_text}', sc_priceFrom = '{$priceFrom}',sc_priceTo = '{$priceTo}', sc_align = '{$align}',sc_price_type1 = '{$sc_price_type1}',sc_price_type2 = '{$sc_price_type2}',sc_price_type3 = '{$sc_price_type3}', sc_align_disabled = '{$un_order_sort}' , mb_id = '{$mb_id}',sc_level = '{$mb_level}', sc_datetime = now() {$save} {$sorts}";
    if (sql_query($sql)) {
        $sql = "select * from `my_search_list` where mb_id = '{$mb_id}' order by sc_datetime desc limit 0, 1";
        $sch = sql_fetch($sql);
        $sc_id = $sch["sc_id"];
        if ($formtype == "search") {
            goto_url(G5_URL . "/index.php?sc_id=" . $sc_id);
        } else {
            alert($msg,G5_URL . "/index.php?sc_id=" . $sc_id);
        }
    } else {
        alert("잘못된 요청입니다.");
    }
}
?>