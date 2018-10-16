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

if(!$pd_id || $pd_id == ""){

    //등록
    $sql = "insert into `product` set
			pd_name = '{$title}',
			pd_type = '{$type}',
			pd_type2 = '{$wr_type2}',
			pd_cate = '{$cate1}',
			pd_cate2 = '{$cate2}',
			pd_images = '',
			pd_video = '',
			pd_content = '',
			pd_tag = '{$title}',
			pd_location = '',
			pd_location_name = '',
			pd_price = '',
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
			pd_update_cnt = 0";
    if(!sql_query($sql)){
        alert("입력 오류 입니다.다시 요청해 주세요");
    }
}



alert("정상등록되었습니다.", G5_URL);
?>