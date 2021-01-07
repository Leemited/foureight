<?php
include_once("../../common.php");

if(!$title){
    alert("제목을 입력해 주세요");
    return false;
}

$filter = explode(",",$config["cf_filter"]);

for($i=0;$i<count($filter);$i++){
    if(strpos($title,$filter[$i])!==false){
        $text = string_star($filter[$i],'harf','left');
        alert("제목에 부적절한 단어[{$text}]가 포함되어 있습니다.");
        exit;
    }
}

if(!$mb_id){
    $mb_id = $member["mb_id"];
}

$con = "0 원에서 ".number_format($wr_price)." 원 사이선에서 구매 원합니다.";

if(!$pd_id || $pd_id == ""){

    //등록
    $sql = "insert into `product` set
			pd_name = '{$title}',
			pd_type = '{$wr_type1}',
			pd_type2 = '4',
			pd_cate = '{$cate1}',
			pd_cate2 = '{$cate2}',
			pd_images = '',
			pd_video = '',
			pd_content = '{$con}',
			pd_tag = '{$title}',
			pd_location = '',
			pd_location_name = '',
			pd_price_type = '{$pd_price_type}',
			pd_price = '{$wr_price}',
			pd_price2 = '{$wr_price2}',
			pd_status = 0,
			mb_id = '{$mb_id}',
			pd_words = '',
			pd_video_link = '',
			pd_date = now(),
			pd_update = now(),
			pd_discount = '',
			pd_lat = '',
			pd_lng = '',
			pd_update_cnt = 0";
    if(!sql_query($sql)){
        alert("입력 오류 입니다.다시 요청해 주세요");
    }

    $pd_id = sql_insert_id();

    $total_price = $wr_price + $wr_price2;

    //글등록시 검색 등록된 것과 비교해서 조건에 맞는 회원 불러오기
    $title = explode("#",$title);
    foreach($title as $key => $value) {
        $sql = "select *,m.mb_id as mb_id from `my_search_list` as s left join `g5_member` as m on s.mb_id = m.mb_id where (INSTR('{$value}',s.sc_tag) > 0 or INSTR('{$value}',if(s.sc_cate1 != '', s.sc_cate1, 'null')) > 0 or INSTR('{$value}',if(s.sc_cate2 != '', s.sc_cate2, 'null')) > 0 {$where})  and set_alarm = 1 and s.sc_type = {$wr_type1} and s.sc_type2 = {$wr_type2} {$search} and sc_priceFrom <= {$total_price} and sc_priceTo >= {$total_price}";
        $res = sql_query($sql);
        while ($row = sql_fetch_array($res)) {
            if($row["regid"]!="" && $row["mb_id"] != $member["mb_id"]) {
                send_FCM($row["regid"],"48 검색알림","[삽니다]".cut_str($row["sc_tag"],10,"...")."의 게시물이 등록되었습니다.\r\n새로운 게시글을 확인해보세요.\r\n알림 선택시 바로 확인 가능합니다.", G5_URL."/?sc_id=".$row["sc_id"]."&sctype=research","search_alarm_set","검색알림",$row["mb_id"],$pd_id,$img);
            }
        }
    }
}

alert("정상등록되었습니다.", G5_URL);
?>