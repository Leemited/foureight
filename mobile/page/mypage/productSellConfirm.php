<?php
include_once ("../../../common.php");

if(!$mb_id){
    alert("회원정보가 없습니다.");
    return false;
}

$mb = get_member($mb_id);

// 차단된 아이디인가?
if ($mb['mb_intercept_date'] && $mb['mb_intercept_date'] <= date("Ymd", G5_SERVER_TIME)) {
    alert('해당 아이디는 접근이 금지되어 있습니다.');
}

// 탈퇴한 아이디인가?
if ($mb['mb_leave_date'] && $mb['mb_leave_date'] <= date("Ymd", G5_SERVER_TIME)) {
    alert('탈퇴한 아이디 입니다.');
}

$pro = sql_fetch("select * from `product` where pd_id = '{$pd_id}'");

if($pro["pd_status"]!=0 && $pro["pd_type"]==1){
    alert("해당 상품은 판매중이 아닙니다.");
}

if($pro["pd_status"]==10 || $pro["pd_blind_status"] == 1){
    alert("해당 상품은 판매 할 수 없습니다.");
}

$pricingcnt = sql_fetch("select count(*) as cnt from `product_pricing` where pd_id = '{$pd_id}' and status = 1");
if($pricingcnt["cnt"] > 0){
    alert("이미 딜완료 된 상품입니다.");
}

// 해당 게시글 판매중으로 업데이트 (물건일경우)
if($pro["pd_type"]==1) {
    $sql = "update `product` set pd_status = 1 where pd_id = '{$pd_id}'";
    sql_query($sql);
}

$sql = "update `product_pricing` set status = 1 where id = '{$pricing_id}'";

$sql = "insert into `cart` set pd_id = '{$pd_id}', mb_id = '{$mb_id}', c_price='{$price}',c_date = now(), c_status = 1 ";
if(sql_query($sql)){
    if ($pro["pd_images"]) {
        $imgs = explode(",", $pro["pd_images"]);
        $img = G5_DATA_URL . "/product/" . $imgs[0];
    }
    send_FCM($mb["regid"], $pro["pd_tag"], $pro["pd_tag"] . "의 구매가 확정되었습니다.", G5_MOBILE_URL . "/page/mypage/cart.php", 'pricing_set', '제시/딜 알림', $mb["mb_id"], $pd_id, $img);

    alert("구매가 수락되었습니다.",G5_MOBILE_URL."/page/mypage/mypage.php");
}else{
    alert("잘못된 정보 입니다.");
}

?>


