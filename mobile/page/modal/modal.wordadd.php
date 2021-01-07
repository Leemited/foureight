<?php
include_once ("../../../common.php");
?>
<div id="id01" class="w3-modal w3-animate-opacity no-view" style="padding-top:0;">
    <div class="w3-modal-content w3-card-4">
        <div class="w3-container">
            <h2>개인 문구 등록</h2>
            <div>
                <input type="text" value="" name="words" id="words" placeholder="문구입력" required>
            </div>
            <div>
                <input type="button" value="취소" onclick="modalClose()"><input type="button" value="확인" onclick="addWords();" >
            </div>
        </div>
    </div>
</div>
