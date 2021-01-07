<?php
include_once ("../../../common.php");
?>
<div id="id04" class="w3-modal w3-animate-opacity no-view">
    <div class="w3-modal-content w3-card-4">
        <div class="w3-container">
            <form name="write_from" id="write_from" method="post" action="">
                <h2>블라인드 사유</h2>
                <div>
                    <input type="text" name="like_content" id="like_content" placeholder="평가 내용을 입력해주세요." required>
                </div>
                <div>
                    <input type="button" value="취소" onclick="modalClose(this)"><input type="button" value="제시하기" onclick="fnPricingUpdate();" >
                </div>
            </form>
        </div>
    </div>
</div>
