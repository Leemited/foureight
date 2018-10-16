<?php
include_once ("../../../common.php");
include_once (G5_MOBILE_PATH."/head.login.php");

//상대 아이디 체크
//개인간 대화 불러오기 (피드백 설정에 맞게 6개월 이전 체크)
$sql = "select *,p.pd_id as pd_id from `product` as p left join `product_chat` as c on p.pd_id = c.pd_id where p.pd_blind_status != 1 and c.read_mb_id = '{$member['mb_id']}' or c.read_mb_id = '{$member['mb_id']}' group by c.send_mb_id order by c.msg_datetime desc";
$res = sql_query($sql);
while($row = sql_fetch_array($res)){
    $my_talk[] = $row;
}
?>

<div class="sub_head">
    <div class="sub_back" onclick="location.href=g5_url"><img src="<?php echo G5_IMG_URL?>/ic_menu_back.svg" alt=""></div>
    <h2>대화목록</h2>
</div>
<div id="container" style="background-color:#e7e7e7;height:calc(100vh - 38vw);overflow-y:scroll">
    <div class="talk_list">
    <?php for($i=0;$i<count($my_talk);$i++){
    $img = explode(",",$my_talk[$i]["pd_images"]);
    $img1 = get_images(G5_DATA_PATH."/product/".$img[0],'300','300');
    $mb = get_member($my_talk[$i]["read_mb_id"]);
    ?>
        <div class="talk_item" onclick="fnTalkView('<?php echo $my_talk[$i]['pd_id'];?>','<?php echo $my_talk[$i]['send_mb_id'];?>','<?php echo $img1;?>');">
            <?php 
            if(is_file(G5_DATA_PATH."/product/".$img1)){ ?>
            <div class="pd_image" style="<?php if($img1!=""){?>background-image:url('<?php echo G5_DATA_URL?>/product/<?php echo $img1;?>') <?php }else{ ?>background-image:url('<?php echo G5_IMG_URL?>/no-profile.svg')<?php }?>;background-size:cover;background-repeat:no-repeat;background-position:center;">
                <?php if($img1!=""){?>
                    <img src="<?php echo G5_DATA_URL?>/product/<?php echo $img1;?>" alt="" class="main" style="opacity:0">
                <?php }else{ ?>
                    <img src="<?php echo G5_IMG_URL?>/no-profile.svg" alt="" class="main" style="opacity:0">
                <?php }?>
            </div>
            <div class="pd_talk_con">
                <div><?php echo $my_talk[$i]["pd_name"];?> | <?php echo $mb["mb_name"];?></div>
                <div><?php echo $my_talk[$i]["message"];?></div>
                <div><?php echo $my_talk[$i]["msg_datetime"];?></div>
            </div>
            <?php } ?>
        </div>
        <?php }?>
    </div>
</div>
<script>
function fnTalkView(pd_id,send_mb_id,back){
    location.href= g5_url+"/mobile/page/talk/talk_view.php?pd_id="+pd_id+"&send_mb_id="+send_mb_id+"&back="+back;
}
</script>
<?php
include_once (G5_MOBILE_PATH."/tail.php");