<?php
include_once ("../../../common.php");
?>

<div id="id05" class="w3-modal w3-animate-opacity no-view">
    <div class="w3-modal-content w3-card-4">
        <div class="w3-container">
            <input type="hidden" name="fin_type" id="fin_type" value="<?php echo $fin_type;?>">
            <input type="hidden" name="fin_od_id" id="fin_od_id" value="<?php echo $od_id;?>">
            <input type="hidden" name="fin_pd_id" id="fin_pd_id" value="<?php echo $pd_id;?>">
            <input type="hidden" name="fin_mb_id" id="fin_mb_id" value="<?php echo $member["mb_id"];?>">
            <input type="hidden" name="fin_likeup" id="fin_likeup" value="">
            <h2>거래완료 및 평가하기</h2>
            <div class="likes" onclick="fnlikes();">
                <span></span>좋아요 <!--<img src="<?php /*echo G5_IMG_URL*/?>/view_like2.svg" alt="" class="likeimg" >-->
            </div>
            <div>
                <input type="text" name="like_content" id="like_content" placeholder="평가 내용을 입력해주세요." required>
            </div>
            <div>
                <input type="button" value="취소" onclick="modalClose(this)"><input type="button" value="확인" onclick="fnFinLikeUpdate2('<?php echo $pd_id;?>','<?php echo $od_id;?>','<?php echo $fin_type;?>','review','<?php echo $member["mb_id"];?>');" style="margin-right: 1vw;" >
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