<?php
include_once ("../../../common.php");
include_once (G5_PATH."/head.sub.php");
/**
 * User: leemited
 * Date: 2018-10-16
 * Time: 오후 2:34
 */
$back_url = G5_MOBILE_URL."/page/talk/talk.php";

$sql = "select *,c.id as id from `product_chat` as c left join `product` as p on p.pd_id = c.pd_id where c.pd_id = {$pd_id} and (c.send_mb_id = '{$member[mb_id]}' or c.read_mb_id = '{$member[mb_id]}') order by msg_datetime";
$res = sql_query($sql);
while($row = sql_fetch_array($res)){
    $talk_list[] = $row;
    $talk_ids[] = $row["id"];
}
if(count($talk_ids) > 0){
    $talk_read_ids = implode(",",$talk_ids);
}

$img = explode(",",$talk_list[0]["pd_images"]);
$img1 = get_images(G5_DATA_PATH."/product/".$img[0],'','');
?>
<div class="talk talk_view_container" style="<?php if($img1!=""){?>background-image:url('<?php echo G5_DATA_URL."/product/".$img1;?>');<?php }?>background-size:cover;background-repeat:no-repeat;background-position:center;">
    <div class="close" onclick="location.href='<?php echo $back_url;?>'">
        <img src="<?php echo G5_IMG_URL?>/view_close.svg" alt="" >
    </div>
    <div class="msg_container" id="msg_container">
        <div class="msg_bg"></div>
        <?php if(count($talk_list)==0){?>
            <div class="no-list">
                <p>대화방에 참여 하였습니다.</p>
            </div>
        <?php }else {
            $today = date("Y-m-d");
            for ($i = 0; $i < count($talk_list); $i++) {
                if($talk_list[$i]["msg_date"] == $today){
                    $date = (date("a",strtotime($talk_list[$i]["msg_time"]))=="am")?"오전":"오후";
                    $date .= " ".substr($talk_list[$i]["msg_time"],0,5);
                }else{
                    $ampm = (date("a",strtotime($talk_list[$i]["msg_time"]))=="am")?"오전":"오후";
                    $date = $talk_list[$i]["msg_date"]."<br>".$ampm." ".substr($talk_list[$i]["msg_time"],0,5);
                }
                if ($member["mb_id"] == $talk_list[$i]["send_mb_id"]) {?>
                    <div class="msg_box my_msg">
                        <div class="in_box">
                            <div class="date">
                                <?php echo $date;?>
                            </div>
                            <div class="msg">
                                <?php echo $talk_list[$i]["message"];?>
                            </div>
                        </div>
                        <div class="clear"></div>
                    </div>
                <?php } else {
                    $mb = get_member($talk_list[$i]["send_mb_id"]);
                    ?>
                    <div class='msg_box read_msg'>
                        <div class="in_box">
                            <div class="read_profile" style="position:relative;<?php if($mb['mb_profile']){?>background-image:url('<?php echo $mb["mb_profile"];?>')<?php }else{?>background-image:url('<?php echo G5_IMG_URL?>/no-profile.svg')<?php }?>;background-size:cover;background-repeat:no-repeat;background-position:center;width:13vw;height:13vw;-webkit-box-shadow: 0 0 2vw RGBA(0,0,0,0.3);-moz-box-shadow: 0 0 2vw RGBA(0,0,0,0.3);box-shadow: 0 0 2vw RGBA(0,0,0,0.3);border-radius: 50%;
                                border: 3px solid #fff;"></div>
                            <div class="box_con">
                                <div class="read_name"><?php echo $mb["mb_nick"];?></div>
                                <div class='msg'><?php echo $talk_list[$i]["message"];?></div>
                                <div class='date'>
                                    <?php echo $date;?>
                                </div>
                            </div>
                        </div>
                        <div class="clear"></div>
                    </div>
                <?php }
            }
        }?>
    </div>
    <div class="msg_controls">
        <input type="hidden" value="<?php echo $talk_read_ids;?>" id="talk_ids">
        <input type="text" name="talk_content" id="message" value="" placeholder="메세지를 입력하세요.">
        <input type="button" value="보내기" class="send_msg" onclick="fnSendMsg();">
        <div class="option">
            <div class="menu1">개인문구</div>
            <?php if($member["mb_id"]==$talk_list[$i]["mb_id"]){?>
                <?php if($talk_list[$i]["pd_type"]==2){?>
                    <div class="menu2">거래유의사항</div>
                <?php } }?>
        </div>
    </div>

    <div class="price">
        <p><?php if($talk_list[$i]['pd_type']==1){ if($talk_list[$i]['pd_type2']==4){?>구매예상금액<?php }else{?>판매금액<?php } }else{if($talk_list[$i]['pd_type2']==4){?>구매예상금액<?php }else{?>계약금<?php } }?></p>
        <h2><?php if($talk_list[$i]["pd_price"]>0){echo "￦ ".number_format($talk_list[$i]["pd_price"]);}else{echo "0원";}?></h2>
        <div class="price_bg"></div>
    </div>

</div>
<script>
    $(function(){
        var element = document.getElementById("msg_container");
        element.scrollTop = element.scrollHeight;

        //대화 갱신
        setInterval(function(){
            var pd_id = "<?php echo $pd_id;?>";
            var mb_id = "<?php echo $member["mb_id"];?>";
            var read_mb_id = "<?php echo $send_mb_id;?>";
            //누적 메시지 체크
            var read_id = $("#talk_ids").val();

            $.ajax({
                url:g5_url+"/mobile/page/ajax/ajax.read_msg.php",
                method:"post",
                data:{pd_id:pd_id,mb_id:mb_id,read_mb_id:read_mb_id,read_id:read_id},
                dataType:'json'
            }).done(function(data){
                console.log(data);
                if(data.cnt > 0) {
                    for (var i = 0; i < data.cnt; i++) {
                        $(".msg_container").append(data.msg[i]);
                    }
                    element.scrollTop = element.scrollHeight;
                }
                if(data.ids) {
                    $("#talk_ids").val(data.ids);
                }
            });
        },1000);

        //채팅옵션
        $(".option").click(function(){
            $(this).toggleClass("active");
        });
    });
    
    // 내게시글이므로 read_mb_id는 대화 상대에따라 변경
    function fnSendMsg(){
        var pd_id = "<?php echo $pd_id;?>";
        var mb_id = "<?php echo $member["mb_id"];?>";
        var read_mb_id = "<?php echo $send_mb_id;?>";
        var message = $("#message").val();

        //누적 메시지 체크
        var read_id = $("#talk_ids").val();

        //스크롤 위치
        var element = document.getElementById("msg_container");

        $.ajax({
            url:g5_url+"/mobile/page/ajax/ajax.send_msg.php",
            method:"post",
            data:{pd_id:pd_id,mb_id:mb_id,read_mb_id:read_mb_id,message:message,read_id:read_id},
            dataType:"json"
        }).done(function(data){
            console.log(data);
            if(data.status=="success"){
                $(".msg_container").append(data.msg);
                element.scrollTop = element.scrollHeight;
                $("#talk_ids").val(data.ids);
                $("#message").val('');
            }else{
                alert("통신 오류 입니다.");
            }
        });
    }
</script>
<?php
include_once (G5_PATH."/tail.sub.php");