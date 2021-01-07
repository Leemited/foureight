<?php
include_once("../../../common.php");

if(!$stx){
    alert("제목을 입력해 주세요");
    return false;
}

$filter = explode(",",$config["cf_filter"]);

for($i=0;$i<count($filter);$i++){
    if(strpos($stx,$filter[$i])!==false){
        $text = string_star($filter[$i],'harf','left');
        alert("제목에 부적절한 단어[{$text}]가 포함되어 있습니다.");
        exit;
    }
}

$chkstx = explode(" ",$stx);
for($i=0;$i<count($chkstx);$i++){
    $stx2 .= "#".$chkstx[$i];
}

$stx = $stx2;

if(!$mb_id){
    $mb_id = $member["mb_id"];
}

if($set_type!=2){
    $pd_price_type = '';
}

if($priceFrom > -1 && $priceTo > -1) {
    $sql = "select * from `product` where ( price1 + price2 ) BETWEEN '' and '' ";
    if($price_set==1) {
        $con = number_format($priceFrom) . "원 에서 " . number_format($priceTo) . "원 사이선에서 구매 원합니다.";
    }
}

if(!$pd_id || $pd_id == ""){

    //등록
    $sql = "insert into `product` set
			pd_name = '{$stx}',
			pd_type = '{$set_type}',
			pd_type2 = '4',
			pd_cate = '{$cate}',
			pd_cate2 = '{$cate2}',
			pd_images = '',
			pd_video = '',
			pd_content = '{$con}',
			pd_tag = '{$stx}',
			pd_location = '',
			pd_location_name = '',
			pd_price = '{$priceFrom}',
			pd_price2 = '',
			pd_status = 0,
			mb_id = '{$mb_id}',
			pd_words = '',
			pd_video_link = '',
			pd_date = now(),
			pd_update = now(),
			pd_discount = '',
			pd_lat = '',
			pd_lng = '',
			pd_price_type = '',
			pd_timeFrom = '',
			pd_timeTo = '',
			pd_update_cnt = 0";
    //echo $sql;
    if(!sql_query($sql)){
        alert("입력 오류 입니다.다시 요청해 주세요");
    }else{
        $pd_id = sql_insert_id();
        //글등록시 검색 등록된 것과 비교해서 조건에 맞는 회원 불러오기
        //$sql = "select *,m.mb_id as mb_id from `my_search_list` as s left join `g5_member` as m on s.mb_id = m.mb_id where ('{$sub_title}' like CONCAT('%', sc_tag ,'%') or '{$sub_title}' like CONCAT('%', sc_cate1 ,'%') or '{$sub_title}' like CONCAT('%', sc_cate2 ,'%')) {$where} and ({$price} between sc_priceFrom and sc_priceTo) and set_alarm = 1 and sc_type = {$type} and sc_type2 = {$type2} {$search} ";

        $title = explode("#",$stx);
        foreach($title as $key => $value) {
            if($value=="") continue;
            $sql = "select *,m.mb_id as mb_id from `my_search_list` as s left join `g5_member` as m on s.mb_id = m.mb_id where (INSTR('{$value}',s.sc_tag) > 0 or INSTR('{$value}' ,if(s.sc_cate1 != '', s.sc_cate1, 'null')) > 0 or INSTR('{$value}',if(s.sc_cate2 != '', s.sc_cate2, 'null')) > 0 {$where} )  and set_alarm = 1 and s.sc_type = {$set_type} and s.sc_type2 = 4 and (sc_priceFrom <= {$priceFrom} or sc_priceTo >= {$priceFrom} ) and (sc_priceTo >= {$priceTo} or sc_priceTo <= {$priceTo})";
            $res = sql_query($sql);
            while ($row = sql_fetch_array($res)) {
                if ($row["regid"] != "" && $row["mb_id"] != $member["mb_id"]) {
                    send_FCM($row["regid"], "48 검색알림", "[삽니다]" . cut_str($row["sc_tag"],10,"...") . "의 게시물이 등록되었습니다.\r\n새로운 게시글을 확인해보세요.\r\n알림 선택시 바로 확인 가능합니다.", G5_URL . "/?sc_id=" . $row["sc_id"] . "&sctype=research", "search_alarm_set", "검색알림", $row["mb_id"], $pd_id, $img);
                }
            }
        }
    }
}

alert("정상등록되었습니다.", G5_URL."/?");
?>