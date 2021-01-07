<?php
include_once ("../../../common.php");
?>
<div id="id00" class="w3-modal w3-animate-opacity no-view">
    <div class="w3-modal-content w3-card-4">
        <div class="w3-container">
            <form name="write_from" method="post" action="<?php echo G5_URL?>/mobile/page/acomm_insert.php">
                <input type="hidden" value="<?php echo $member["mb_id"];?>" name="mb_id" id="mb_id">
                <h2>제안하기</h2>
                <div>
                    <input type="text" value="" name="cate_name" id="cate_name" placeholder="해당 '카테고리' 가 필요해요!" required>
                    <input type="text" value="" name="cate_name2" id="cate_name2" placeholder="해당 '상세카테고리' 가 필요해요!" >
                    <textarea value="" name="cate_content" id="cate_content"  placeholder="사유를 적어주세요" required></textarea>
                </div>
                <div>
                    <input type="button" value="취소" onclick="modalClose(this)"><input type="submit" value="확인" onclick="" >
                </div>
            </form>
        </div>
    </div>
</div>

