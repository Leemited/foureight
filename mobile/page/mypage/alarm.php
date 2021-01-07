<?php
include_once ("../../../common.php");
$back_url = G5_URL;
include_once (G5_MOBILE_PATH."/head.login.php");

if(!$is_member){
    alert("로그인이 필요합니다.", G5_MOBILE_URL."/page/login_intro.php");
}

$now = date("Y-m-d");
$month = date("Y-m-d", strtotime("-3 month"));

$sql = "select *,m.pd_id as pd_id from `my_alarms` as m left join `product` as p on m.pd_id = p.pd_id where m.mb_id = '{$member["mb_id"]}' and m.alarm_date between '{$month}' and '{$now}' and m.alarm_status != 3 order by m.alarm_date desc, m.alarm_time desc limit 0, 15";
$res = sql_query($sql);
while($row = sql_fetch_array($res)){
    $alarm[] = $row;
}
$today = date("Y-m-d H:i:s");
?>
<style>
    #head{position:fixed;}
    .jscroll-inner{padding-bottom: 13vw;}
</style>
<div class="sub_head" style="position:fixed;top:6vw;background-color:#fff;">
    <div class="sub_back" onclick="location.href='<?php echo $back_url;?>'"><img src="<?php echo G5_IMG_URL?>/ic_menu_back.svg" alt=""></div>
    <h2>알림목록</h2>
    <!-- <div class="sub_add">추가</div> -->
</div>
<div class="alert_list" style="margin-top:16vw;padding-top:1vw;overflow-y: inherit;height:auto;background-color:#fff;">
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
            <div class="alarm_item <?php if($alarm[$i]["alarm_status"] != 0){?>alarm_read<?php }?>" id="list_<?php echo $alarm[$i]["id"];?>" onclick="alarmRead('<?php echo $alarm[$i]["pd_id"];?>','<?php echo $alarm[$i]["alarm_type"];?>','<?php echo $alarm[$i]["id"];?>','<?php echo $alarm[$i]['alarm_link'];?>')">
                <?php if($alarm[$i]["pd_id"]){?>
                <?php if($alarm[$i]["pd_images"]!=""){
                    $img = explode(",",$alarm[$i]["pd_images"]);
                    $img[0] = trim($img[0]);
                    if(!is_file(G5_DATA_PATH."/product/thumb-".$img[0])) {
                        $img1 = get_images(G5_DATA_PATH . "/product/" . $img[0], 260, '');
                    }else{
                        $img1 = "thumb-".$img[0];
                    }
                    if(is_file(G5_DATA_PATH."/product/".$img1)){
                        ?>
                        <?php if($img1!=""){?>
                        <div class="item_images" style="background-image:url('<?php echo G5_DATA_URL?>/product/<?php echo $img1;?>');background-repeat:no-repeat;background-size:cover;background-position:center;">
                        <?php }else{ ?>
                        <div class="item_images" style="background-image:url('<?php echo G5_IMG_URL?>/no-profile.svg');background-repeat:no-repeat;background-size:cover;background-position:center;">
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
                        <?php echo nl2br($alarm[$i]["alarm_content"]);?>
                    </div>
                </div>
                <div class="clear"></div>
            </div>
        <?php
        //알림 업데이트
            $sql = "update `my_alarms` set alarm_status = 1 where id = '{$alarm[$i]["id"]}'";
            sql_query($sql);
        }?>
        <div class="next"><a href="<?php echo G5_MOBILE_URL;?>/page//mypage/alarm_list_get.php?page=2" class="nextPage"></a></div>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jscroll/2.4.1/jquery.jscroll.min.js"></script>

<script>
function alarmRead(pd_id,type,id,link){
    location.href=link;
}

$(function() {
    $(".list_con").jscroll({
        autoTrigger:true,
        loadingHtml:'<div class="next"></div>',
        nextSelector:"a.nextPage:last"
    });

    <?php if($app){?>
    window.android.resetBadge();
    <?php } ?>
    $("div[id^=list]").each(function(){
        var id = $(this).attr("id");
        var alarm_id = id.replace("list_","");
        var item = document.getElementById(id);
        var swiper = new Hammer(item);
        swiper.on('swipeleft', function (e) {
            $.ajax({
                url:g5_url+"/mobile/page/ajax/ajax.alarm_delete.php",
                method:"post",
                data:{id:alarm_id}
            }).done(function(data){
                if(data==""){
                    alert("잘못된 처리 입니다.");
                }else if(data=="1"){
                    alert("해당 알림정보가 잘못되었습니다.");
                }else if(data=="2"){
                    $("#"+id).addClass("remove");
                    setTimeout(function(){$("#"+id).remove();},100);
                    //alert("해당 알림은 삭제되었습니다.");
                }else if(data=="3"){
                    alert("해당 알림의 삭제처리오류로 인해 삭제가 되지 않았습니다.");
                }
            });
        });
        swiper.on("swiperight", function (e) {
            $.ajax({
                url:g5_url+"/mobile/page/ajax/ajax.alarm_delete.php",
                method:"post",
                data:{id:alarm_id}
            }).done(function(data){
                if(data==""){
                    alert("잘못된 처리 입니다.");
                }else if(data=="1"){
                    alert("해당 알림정보가 잘못되었습니다.");
                }else if(data=="2"){
                    $("#"+id).addClass("remove");
                    setTimeout(function(){$("#"+id).remove();},100);
                    //alert("해당 알림은 삭제되었습니다.");
                }else if(data=="3"){
                    alert("해당 알림의 삭제처리오류로 인해 삭제가 되지 않았습니다.");
                }
            });
        });
    });
});
</script>
<?php
include_once (G5_MOBILE_PATH."/tail.php");
?>
