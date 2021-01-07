<?php
include_once ("../../../common.php");
$pro = sql_fetch("select * from `product` where pd_id = '{$pd_id}'");
?>
<div id="id06" class="w3-modal w3-animate-opacity no-view">
    <div class="w3-modal-content w3-card-4">
        <div class="w3-container">
            <h2>알림</h2>
            <div class="con">
                <p>회원님의 게시물[<?php echo $pro["pd_tag"];?>]이 블라인드 처리되었습니다.</p>
            </div>
            <div>
                <input type="button" value="취소" onclick="modalClose(this)">
                <input type="button" value="사유보기" style="width:auto;margin-right:1vw" onclick="location.href='<?php echo G5_MOBILE_URL."/page/mypage/blind_view.php?pd_id=".$pd_id;?>'">
                <input type="button" value="관리자문의" style="width:auto" onclick="fnAdminWrite('<?php echo $pd_id;?>');" >
            </div>
        </div>
    </div>
</div>
