<?php
include_once ("../../../common.php");
include_once (G5_MOBILE_PATH."/head.login.php");

//상대 아이디 체크
//개인간 대화 불러오기 (피드백 설정에 맞게 6개월 이전 체크)
$sql = "select *,p.mb_id as pd_mb_id from `product` as p left join (select * from `product_chat` order by msg_datetime desc ) as c on p.pd_id = c.pd_id where send_mb_id = '{$member['mb_id']}' or read_mb_id = '{$member['mb_id']}' group by c.room_id order by msg_datetime desc";
$res = sql_query($sql);
while($row = sql_fetch_array($res)){
    $my_talk[] = $row;
}
?>

<div class="sub_head">
    <div class="sub_back" onclick="location.href=g5_url"><img src="<?php echo G5_IMG_URL?>/ic_menu_back.svg" alt=""></div>
    <h2>대화목록</h2>
</div>
<div id="container" style="background-color:#e7e7e7;height:calc(100vh - 28vw);overflow-y:scroll">
    <div class="talk_list">
    <?php for($i=0;$i<count($my_talk);$i++){
    $img = explode(",",$my_talk[$i]["pd_images"]);
    $img1 = get_images(G5_DATA_PATH."/product/".$img[0],'300','300');
    if($my_talk[$i]["send_mb_id"]==$member["mb_id"]){
        $mb = get_member($my_talk[$i]["read_mb_id"]);
    }else{
        $mb = get_member($my_talk[$i]["send_mb_id"]);
    }
    ?>
        <div class="talk_item" onclick="fnTalkView('<?php echo $my_talk[$i]['pd_id'];?>','<?php echo $my_talk[$i]['send_mb_id'];?>','<?php echo $my_talk[$i]['read_mb_id'];?>','<?php echo $img1;?>','<?php echo $my_talk[$i]['room_id'];?>');">
            <?php 
            if($mb["mb_profile"]){ ?>
            <div class="pd_image" style="background-image:url('<?php echo $mb["mb_profile"]?>');background-size:cover;background-repeat:no-repeat;background-position:center;-webkit-border-radius:50% ;-moz-border-radius:50% ;border-radius: 50%;">
            </div>
            <?php }else{?>
            <div class="pd_image" style="background-image:url('<?php echo G5_IMG_URL?>/no-profile.svg');background-size:cover;background-repeat:no-repeat;background-position:center;">
            </div>
            <?php }?>

            <div class="pd_talk_con">
                <div><?php echo $my_talk[$i]["pd_name"];?> | <?php echo $mb["mb_nick"];?></div>
                <div><?php echo str_replace("<br>","\r\n",$my_talk[$i]["message"]);?></div>
                <div><?php echo $my_talk[$i]["msg_datetime"];?></div>
            </div>
        </div>
        <?php }?>
    </div>
</div>
<script>
function fnTalkView(pd_id,send_mb_id,read_mb_id,back,roomid){
    location.href= g5_url+"/mobile/page/talk/talk_view.php?pd_id="+pd_id+"&send_mb_id="+send_mb_id+"&roomid="+roomid;
}
</script>
<?php
include_once (G5_MOBILE_PATH."/tail.php");