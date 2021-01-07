<?php
include_once ("../../../common.php");
?>
<div id="id02" class="w3-modal w3-animate-opacity no-view">
    <div class="w3-modal-content w3-card-4">
        <div class="w3-container">
            <input type="hidden" name="like_id" id="like_id" value="">
            <input type="hidden" name="view_pd_type" id="view_pd_type" value="">
            <input type="hidden" name="pd_mb_id" id="pd_mb_id" value="">
            <input type="hidden" name="fin_likeup" id="fin_likeup" value="">
            <h2>평가하기</h2>
            <div class="likes" onclick="fnlikes();">
                <span></span>좋아요 <!--<img src="<?php /*echo G5_IMG_URL*/?>/view_like2.svg" alt="" class="likeimg" >-->
            </div>
            <div>
                <input type="text" name="like_content" id="like_content" placeholder="평가 내용을 입력해주세요." required>
            </div>
            <div>
                <input type="button" value="취소" onclick="modalClose(this)"><input type="button" value="확인" onclick="fnLikeUpdate();" >
            </div>
        </div>
    </div>
</div>

<script>
    function fnlikes(){
        if($(".likes").hasClass("active")){
            $(".likes").removeClass("active");
            $("#fin_likeup").val("");
        }else{
            $(".likes").addClass("active");
            $("#fin_likeup").val("up");
        }
    }
</script>

