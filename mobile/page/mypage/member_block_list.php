<?php
include_once ("../../../common.php");
include_once (G5_MOBILE_PATH."/head.login.php");

$sql = "select * from `member_block` as b left join `g5_member` as m on b.target_id = m.mb_id where b.mb_id = '{$member["mb_id"]}'";
//echo $sql;
$res = sql_query($sql);
$i=0;
while($row = sql_fetch_array($res)){
    $list[$i] = $row;
    $sStartTime = strtotime($row["block_dateForm"]);
    $sEndTime = strtotime($row["block_dateTo"]);

    $list[$i]["dateFrom"] = date("Y-m-d", strtotime($row["block_dateFrom"]));
    $list[$i]["dateTo"] = date("Y-m-d", strtotime($row["block_dateTo"]));

    $sDiffTime = $sEndTime - $sStartTime;
    if($row["block_status"]!=1) {
        $list[$i]['month'] = (int)date("m", strtotime($sDiffTime)) . "개월 차단";
    }else{
        $list[$i]['month'] = "영구차단";
    }
    $i++;
}
$back_url = G5_MOBILE_URL."/page/mypage/settings.php";
?>
<div class="sub_head">
    <div class="sub_back" onclick="location.href='<?php echo $back_url;?>'"><img src="<?php echo G5_IMG_URL?>/ic_menu_back.svg" alt=""></div>
    <h2>차단회원 목록</h2>
    <!-- <div class="sub_add">추가</div> -->
</div>
<div class="block_list">
    <ul>
        <?php for($i=0;$i<count($list);$i++){?>
        <li>
            <div><?php echo $list[$i]["target_id"];?></div>
            <p>차단 시작일 : <?php echo $list[$i]["dateFrom"];?> ~ 종료일 : <?php echo $list[$i]["dateTo"];?> </p><p class="point">[<?php echo $list[$i]["month"];?>]</p>
            <span onclick="location.href=g5_url+'/mobile/page/mypage/member_block_update.php?type=cancel&id=<?php echo $list[$i]["id"];?>'"> 차단취소 </span>
        </li>
        <?php }?>
        <?php if(count($list) == 0){?>
            <li class="no-list">
                차단된 회원이 없습니다.
            </li>
        <?php }?>
    </ul>
</div>
<?php
include_once (G5_MOBILE_PATH."/tail.php");
?>
