<?php
include_once ("../../../common.php");
$order = sql_fetch("select * from `order` where od_id = '{$od_id}'");
?>
<div id="id01" class="w3-modal w3-animate-opacity no-view" style="padding-top:0;">
    <div class="w3-modal-content w3-card-4">
        <div class="w3-container">
            <h2>요청내용 확인</h2>
            <div style="display: inline-block;width:100%;margin:4vw auto">
                <ul style="display:inline-block;margin:0 auto;">
                    <li style="background-color:rgba(255,255,255,1);padding:2vw;font-size:3vw;width:65vw;-webkit-border-radius: 5vw;-moz-border-radius: 5vw;border-radius: 5vw;"><?php echo $order["od_fin_content"];?></li>
                </ul>
            </div>
            <div>
                <input type="button" value="확인" onclick="modalClose();" >
            </div>
        </div>
    </div>
</div>
