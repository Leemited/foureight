<?php
include_once ("../../../common.php");
include_once (G5_PATH."/head.sub.php");

?>
<style>
    #firstApp {height:100vh;}
    #firstApp .item{height:100vh;}
    #firstApp .item h2{font-size:8.8vw;text-align: center;width: 100%;padding-top:12vh}
    .close{position:fixed;top:5vw;right:5vw;width:8vw;height:8vw;z-index: 10}

    .owl-carousel .owl-dots{position: absolute;top: 8vh;left: 50%;transform: translateX(-50%);}
    .owl-carousel .owl-dot{width:3vw;height:3vw;display:inline-block;margin:0 0.5vw;background-color:#ddd;-webkit-border-radius:50% 50%;-moz-border-radius:50% 50%;border-radius:50% 50%;}
    .owl-carousel .owl-dot.active{background-color:#00b3ff}
</style>
<div style="width:100vw;height:100vh;background-color:#fff;top:50%;position: fixed;-webkit-transform: translate(0,-50%);-moz-transform: translate(0,-50%);-ms-transform: translate(0,-50%);-o-transform: translate(0,-50%);transform: translate(0,-50%);">
    <div class="close" onclick="fnClose();"><img src="<?php echo G5_IMG_URL;?>/view_close2.svg" alt=""></div>
    <div class="owl-carousel" id="firstApp">
        <div class="item" style="background-image: url('<?php echo G5_IMG_URL;?>/slide01.jpg');background-size:contain;background-repeat: no-repeat;background-position: center bottom;">
            <h2>글올리기</h2>
        </div>
        <div class="item" style="background-image: url('<?php echo G5_IMG_URL;?>/slide02.jpg');background-size:contain;background-repeat: no-repeat;background-position: center bottom;">
            <h2>계약금 설정</h2>
            <!--<div class="close" onclick="fnClose();"><img src="<?php /*echo G5_IMG_URL;*/?>/view_close2.svg" alt=""></div>-->
        </div>
        <div class="item" style="background-image: url('<?php echo G5_IMG_URL;?>/slide03.jpg');background-size:contain;background-repeat: no-repeat;background-position: center bottom;">
            <h2>삽니다 간편등록</h2>
            <!--<div class="close" onclick="fnClose();"><img src="<?php /*echo G5_IMG_URL;*/?>/view_close2.svg" alt=""></div>-->
        </div>
        <div class="item" style="background-image: url('<?php echo G5_IMG_URL;?>/slide04.jpg');background-size:contain;background-repeat: no-repeat;background-position: center bottom;">
            <h2>딜하기</h2>
            <!--<div class="close" onclick="fnClose();"><img src="<?php /*echo G5_IMG_URL;*/?>/view_close2.svg" alt=""></div>-->
        </div>
        <div class="item" style="background-image: url('<?php echo G5_IMG_URL;?>/slide05.jpg');background-size:contain;background-repeat: no-repeat;background-position: center bottom;">
            <h2>상세검색창</h2>
            <!--<div class="close" onclick="fnClose();"><img src="<?php /*echo G5_IMG_URL;*/?>/view_close2.svg" alt=""></div>-->
        </div>
        <div class="item" style="background-image: url('<?php echo G5_IMG_URL;?>/slide06.jpg');background-size:contain;background-repeat: no-repeat;background-position: center bottom;">
            <h2>검색우선순위</h2>
            <!--<div class="close" onclick="fnClose();"><img src="<?php /*echo G5_IMG_URL;*/?>/view_close2.svg" alt=""></div>-->
        </div>
        <div class="item" style="background-image: url('<?php echo G5_IMG_URL;?>/slide07.jpg');background-size:contain;background-repeat: no-repeat;background-position: center bottom;">
            <h2>휴지통 기능</h2>
            <!--<div class="close" onclick="fnClose();"><img src="<?php /*echo G5_IMG_URL;*/?>/view_close2.svg" alt=""></div>-->
        </div>
        <div class="item" style="background-image: url('<?php echo G5_IMG_URL;?>/slide08.jpg');background-size:contain;background-repeat: no-repeat;background-position: center bottom;">
            <h2>블라인드 기능</h2>
            <!--<div class="close" onclick="fnClose();"><img src="<?php /*echo G5_IMG_URL;*/?>/view_close2.svg" alt=""></div>-->
        </div>
        <div class="item" style="background-image: url('<?php echo G5_IMG_URL;?>/slide09.jpg');background-size:contain;background-repeat: no-repeat;background-position: center bottom;">
            <h2>기업회원신청</h2>
            <!--<div class="close" onclick="fnClose();"><img src="<?php /*echo G5_IMG_URL;*/?>/view_close2.svg" alt=""></div>-->
        </div>
    </div>
</div>
<script>
    var owl = $("#firstApp");

    owl.owlCarousel({
        items:1,
        loop:false,
        nav:false,
        dot:true
    });

    function fnClose(){
        if(confirm("기능설명은 도움말에서 다시 확인 가능합니다.")) {
            location.href = g5_url;
        }
    }
</script>
<?php
include_once (G5_PATH."/tail.sub.php");