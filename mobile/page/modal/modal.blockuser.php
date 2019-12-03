<?php
include_once ("../../../common.php");

$mb = get_member($mb_id);

?>
<div id="id09" class="w3-modal w3-animate-opacity no-view">
    <div class="w3-modal-content w3-card-4">
        <div class="w3-container">
            <form action="<?php echo G5_MOBILE_URL?>/page/mypage/member_block_update.php" method="post" name="blockform">
                <input type="hidden" name="target_id" id="target_id" value="<?php echo $mb["mb_id"];?>">
                <input type="hidden" name="mb_id"  id="mb_id" value="<?php echo $member["mb_id"];?>">
                <input type="hidden" name="block_date" id="block_date" value="1">
                <h2>유저차단</h2>
                <div class="con">
                    <ul class="modal_sel">
                        <li id="status1" class="active" >1개월 차단</li>
                        <li id="status2" class="" >6개월 차단</li>
                        <li id="status3" class="" >영구차단</li>
                    </ul>
                </div>
                <div>
                    <input type="button" value="취소" onclick="modalClose(this)">
                    <input type="button" value="차단하기" onclick="fn_block();" style="width:auto">
                </div>
            </form>
        </div>
    </div>
</div>
