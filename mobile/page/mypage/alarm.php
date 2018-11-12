<?php
include_once ("../../../common.php");
include_once (G5_MOBILE_PATH."/head.login.php");
$back_url = G5_URL;

if(!$is_member){
    alert("로그인이 필요합니다.", G5_MOBILE_URL."/login_intro.php");
}

$now = date("Y-m-d");
$month = date("Y-m-d", strtotime("-3 month"));

$sql = "select *,m.pd_id as pd_id from `my_alarms` as m left join `product` as p on m.pd_id = p.pd_id where m.mb_id = '{$member["mb_id"]}' and m.alarm_date between '{$month}' and '{$now}' order by m.alarm_date desc, m.alarm_time desc";
$res = sql_query($sql);
while($row = sql_fetch_array($res)){
    $alarm[] = $row;
}
$today = date("Y-m-d H:i:s");
?>
<div class="sub_head">
    <div class="sub_back" onclick="location.href='<?php echo $back_url;?>'"><img src="<?php echo G5_IMG_URL?>/ic_menu_back.svg" alt=""></div>
    <h2>알림목록</h2>
    <!-- <div class="sub_add">추가</div> -->
</div>
<div class="alert_list">
    <div class="infos">
        <p>* 알람은 최대 3개월까지 보관됩니다.</p>
    </div>
    <div class="list_con">
        <?php for($i=0;$i<count($alarm);$i++){
            //시간차 구하기 [1일이상 차이난다면 일수 아니면 시간,분]
            $alarm_date = $alarm[$i]["alarm_date"]." ".$alarm[$i]["alarm_time"];
            $diff[$i] = dateDiff($alarm_date,$today);
            if($diff[$i]["d"] > 0){

                $diff_date = ((int)$diff[$i]["d"])."일 전";
            }else{
                if($diff[$i]["H"]>0) {
                    $diff_date = ((int)$diff[$i]["H"]) . "시간 전";
                }else{
                    $diff_date = ((int)$diff[$i]["i"]) . "분 전";
                }
            }

            ?>
            <!--<p><?php /*echo $alarm[$i]["alarm_type"];*/?></p>-->
            <div class="alarm_item <?php if($alarm[$i]["alarm_status"] != 0){?>alarm_read<?php }?>" onclick="alarmRead('<?php echo $alarm[$i]["pd_id"];?>','<?php echo $alarm[$i]["alarm_type"];?>','<?php echo $alarm[$i]["id"];?>','<?php echo $alarm[$i]['alarm_link'];?>')">
                <?php if($alarm[$i]["pd_id"]){?>
                <?php if($alarm[$i]["pd_images"]!=""){
                    $img = explode(",",$alarm[$i]["pd_images"]);
                    $img1 = get_images(G5_DATA_PATH."/product/".$img[0],'','');
                    if(is_file(G5_DATA_PATH."/product/".$img1)){
                        ?>
                        <div class="item_images" style="background-image:url('<?php echo G5_DATA_URL?>/product/<?php echo $img1;?>');background-repeat:no-repeat;background-size:cover;background-position:center;">
                            <?php if($img1!=""){?>
                                <img src="<?php echo G5_DATA_URL?>/product/<?php echo $img1;?>" alt="" class="main" style="opacity:0">
                            <?php }else{ ?>
                                <img src="<?php echo G5_IMG_URL?>/no-profile.svg" alt="" class="main" style="opacity:0">
                            <?php }?>
                        </div>
                    <?php }else{
                        //$tags = explode("/",$alarm[$i]["pd_tag"]);
                        $rand = rand(1,13);
                        ?>
                        <div class="bg rand_bg<?php echo $rand;?> item_images" style="display: table;">
                            <div class="tags">
                                <?php //for($k=0;$k<count($tags);$k++){
                                    $rand_font = rand(3,6);
                                    ?>
                                    <div class="rand_size<?php echo $rand_font;?>"><?php echo $alarm[$i]["pd_tag"];?></div>
                                <?php //}?>
                            </div>
                            <div class="clear"></div>
                        </div>
                    <?php }?>
                <?php }else{
                    $tags = explode("#",$alarm[$i]["pd_tag"]);
                    $rand = rand(1,13);
                    ?>
                    <div class="bg rand_bg<?php echo $rand;?> item_images" style="display: table;">
                        <div class="tags">
                            <?php $rand_font = rand(3,6); ?>
                                <div class="rand_size<?php echo $rand_font;?>"><?php echo $alarm[$i]["pd_tag"];?></div>
                        </div>
                        <div class="clear"></div>
                    </div>
                <?php }?>
                <?php }else{?>
                    <div class="item_images" style="background-image:url('<?php echo G5_IMG_URL?>/logo.svg');background-repeat:no-repeat;background-size:70% 70%;background-position:center;background-color:#eee;">
                    </div>
                <?php }?>
                <div class="item_text">
                    <!--<div class="btns">
                        <input type="button" value="삭제">
                    </div>-->
                    <p><?php echo $alarm[$i]["alarm_date"];?> <?php echo $alarm[$i]["alarm_time"];?> <span><?php echo $diff_date;?></span></p>
                    <h2>" <?php echo $alarm[$i]["alarm_title"];?> "</h2>
                    <div>
                        <?php
                        //alarm_type : 1 = 구매관련 , 2 = 좋아요 , 3 = 검색등록 , 4 = 댓글,대화, 5 = 기타
                        // 1,2 = 해당 요청 아이디 표시
                        // 3,4,5 = 기본 문구
                        ?>
                        <?php echo cut_str($alarm[$i]["alarm_content"],'30','...');?>
                    </div>
                </div>
                <div class="clear"></div>
            </div>
        <?php
        //알림 업데이트
            $sql = "update `my_alarms` set alarm_status = 1 where id = '{$alarm[$i]["id"]}'";
            sql_query($sql);
        }?>
    </div>
</div>
<script>
function alarmRead(pd_id,type,id,link){
    location.href=link;
}
</script>
<?php
include_once (G5_MOBILE_PATH."/tail.php");
?>
