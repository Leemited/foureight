<?php
include_once ("../../../common.php");
?>
<style>
    #firstApp {height:100vh;}
    #firstApp .item{height:100vh;}
    #firstApp .item h2{font-size:8.8vw;text-align: center;width: 100%;padding-top:12vh}
</style>
<div style="width:100vw;height:100vh;background-color:#fff;top:50%;position: absolute">
    <div class="owl-carousel" id="firstApp">
        <div class="item" style="background-image: url('<?php echo G5_IMG_URL;?>/slide01.jpg');background-size:contain;background-repeat: no-repeat;background-position: center bottom;">
            <h2>글올리기</h2>
        </div>
        <div class="item" style="background-image: url('<?php echo G5_IMG_URL;?>/slide02.jpg');background-size:contain;background-repeat: no-repeat;background-position: center bottom;">
            <h2>계약금 설정</h2>
        </div>
        <div class="item" style="background-image: url('<?php echo G5_IMG_URL;?>/slide03.jpg');background-size:contain;background-repeat: no-repeat;background-position: center bottom;">
            <h2>삽니다 간편등록</h2>
        </div>
        <div class="item" style="background-image: url('<?php echo G5_IMG_URL;?>/slide04.jpg');background-size:contain;background-repeat: no-repeat;background-position: center bottom;">
            <h2>딜하기</h2>
        </div>
        <div class="item" style="background-image: url('<?php echo G5_IMG_URL;?>/slide05.jpg');background-size:contain;background-repeat: no-repeat;background-position: center bottom;">
            <h2>상세검색창</h2>
        </div>
        <div class="item" style="background-image: url('<?php echo G5_IMG_URL;?>/slide06.jpg');background-size:contain;background-repeat: no-repeat;background-position: center bottom;">
            <h2>검색우선순위</h2>
        </div>
        <div class="item" style="background-image: url('<?php echo G5_IMG_URL;?>/slide07.jpg');background-size:contain;background-repeat: no-repeat;background-position: center bottom;">
            <h2>휴지통 기능</h2>
        </div>
        <div class="item" style="background-image: url('<?php echo G5_IMG_URL;?>/slide08.jpg');background-size:contain;background-repeat: no-repeat;background-position: center bottom;">
            <h2>블라인드 기능</h2>
        </div>
        <div class="item" style="background-image: url('<?php echo G5_IMG_URL;?>/slide09.jpg');background-size:contain;background-repeat: no-repeat;background-position: center bottom;">
            <h2>기업회원신청</h2>
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
</script>
