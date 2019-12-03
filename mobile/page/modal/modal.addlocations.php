<?php
include_once ("../../../common.php");
$mySetting = sql_fetch("select * from `mysetting` where mb_id = '{$member[mb_id]}'");
if($mySetting["my_locations"]) {
    $mylocations = explode(",", $mySetting["my_locations"]);
    $mylat = explode(",", $mySetting["location_lat"]);
    $mylng = explode(",", $mySetting["location_lng"]);
}
?>
<div id="id03" class="w3-modal w3-animate-opacity no-view" style="padding-top:0;">
    <div class="w3-modal-content w3-card-4" >
        <div class="w3-container">
            <h2>거래위치 입력</h2>
            <div>
                <div class="map_set">
                    <input type="text" value="" name="locs1" id="locs1" value="" placeholder="예)신림역 2번 출구" required onkeyup="fnfilter(this.value,'locs1')" onkeydown="fnTest();">
                    <img src="<?php echo G5_IMG_URL?>/ic_search.svg" alt="" onclick="mapSelect()">
                </div>

            </div>
            <div>
                <input type="button" value="취소" onclick="modalClose()"><!--<input type="button" value="확인" onclick="fnLocs();">-->
            </div>
            <br>
            <h2>간편 거래위치 선택</h2>
            <div>
                <?php if(count($mylocations)>0 || $mylocations){?>
                    <ul class="modal_sel">
                        <?php for($i=0;$i<count($mylocations);$i++){
                            if($mylocations[$i]!=""){?>
                                <li class="locSel<?php echo $i;?>"  onclick="fnLocation('<?php echo $mylocations[$i];?>','<?php echo $mylat[$i];?>','<?php echo $mylng[$i];?>');">
                                    <?php echo $mylocations[$i]?>
                                </li>
                            <?php }?>
                        <?php }?>
                    </ul>
                <?php }else{?>
                    <p>MYPAGE -> 설정 -> 거래위치설정에서 등록가능합니다.</p>
                    <input type="button" value="마이페이지" onclick="location.href=g5_url+'/mobile/page/mypage/my_location.php'" style="width: auto;margin-top:2vw">
                <?php }?>
            </div>
        </div>
    </div>
</div>
<script>
    function fnTest(){

    }
</script>