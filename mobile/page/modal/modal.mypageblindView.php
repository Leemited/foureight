<?php
include_once ("../../../common.php");

if($pd_id==""){
    echo "1";
    return false;
}

$sql = "select *,count(blind_content) as cnt from `product_blind` where pd_id={$pd_id} group by blind_content";
$res = sql_query($sql);
while($row = sql_fetch_array($res)){
    $blind_list[] = $row;
}

//게시글 정보
$sql = "select * from `product` where pd_id = {$pd_id}";
$product = sql_fetch($sql);

?>
<div id="id06" class="w3-modal w3-animate-opacity no-view">
    <div class="w3-modal-content w3-card-4">
        <div class="w3-container">
            <h2>사유보기</h2>
            <div class="con">
                <div>
                    <h3><?php echo "[".$product["pd_cate"]." | ".$product["pd_cate2"]."] ". $product["pd_name"];?></h3>
                    <div>
                        <ul>
                            <?php for($i=0;$i<count($blind_list);$i++){?>
                                <li><?php echo $blind_list[$i]["blind_content"]."/".$blind_list[$i]["cnt"]."건";?></li>
                            <?php }
                            if(count($blind_list)==0){
                                ?>
                                <li>등록된 사유가 없거나 <br>관리자가 직접 처리한 게시물입니다.</li>
                            <?php }?>
                        </ul>
                    </div>
                </div>
            </div>
            <div>
                <input type="button" value="확인" onclick="modalClose(this)">
                <input type="button" value="상세보기" id="blind_view_btn" style="width:auto" onclick="location.href='http://484848.co.kr/mobile/page/mypage/blind_view.php?pd_id=<?php echo $pd_id;?>&backurl=<?php echo G5_MOBILE_URL.'/page/mypage/mypage.php';?>'">
                <input type="button" value="관리자문의" id="admin_qa" style="width:auto"  onclick="fnAdminWrite('<?php echo $pd_id;?>')">
            </div>
        </div>
    </div>
</div>
