<?php
include_once ("../../../common.php");
$mySetting = sql_fetch("select * from `mysetting` where mb_id = '{$member[mb_id]}'");
if($mySetting["my_locations"]) {
    $mylocations = explode(",", $mySetting["my_locations"]);
    $mylat = explode(",", $mySetting["location_lat"]);
    $mylng = explode(",", $mySetting["location_lng"]);
}
?>

<div id="id02" class="w3-modal w3-animate-opacity no-view" style="padding-top:0;">
    <div class="w3-modal-content w3-card-4">
        <div class="w3-container">
            <h2>간편 거래 위치</h2>
            <div>
                <ul class="modal_sel">
                    <?php for($i=0;$i<count($mylocations);$i++){?>
                        <li class="locSel<?php echo $i;?>" onclick="setLocation('','','');" ><?php echo $mylocations[$i];?></li>
                    <?php }?>
                </ul>
            </div>
            <div>
                <input type="button" value="취소" onclick="modalClose()">
            </div>
        </div>
    </div>
</div>
