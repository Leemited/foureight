<?php
include_once ("../../../common.php");
include_once (G5_PATH."/head.sub.php");
/**
 * User: leemited
 * Date: 2018-10-16
 * Time: 오후 2:34
 */
$back_url = G5_MOBILE_URL."/page/talk/talk.php";

$sql = "select *,c.id as id from `product_chat` as c left join `product` as p on p.pd_id = c.pd_id where c.pd_id = {$pd_id} and room_id = '{$roomid}' order by msg_datetime asc";
$res = sql_query($sql);
while($row = sql_fetch_array($res)){
    $talk_list[] = $row;
    $talk_ids[] = $row["id"];
    $img = explode(",",$row["pd_images"]);
    $img1 = get_images(G5_DATA_PATH."/product/".$img[0],'','');
    //$roomid = $row["room_id"];
    if($member["mb_id"] != $row["send_mb_id"]){
        $sell_mb_id = $row["send_mb_id"];
    }else if($member["mb_id"] != $row["read_mb_id"]){
        $sell_mb_id = $row["read_mb_id"];
    }
}
if(count($talk_ids) > 0){
    $talk_read_ids = implode(",",$talk_ids);
}

//게시글 설정
$sql = "select * from `g5_member` as m left join `mysetting` as s on m.mb_id = s.mb_id where s.mb_id = '{$member[mb_id]}'";
$myset = sql_fetch($sql);
//대화시 간편대화 불러오기
$mywords = explode(":@!",$myset["my_word"]);
$mywordss = explode("!@~",$mywords[2]);

$sql = "select * from `product` where pd_id = {$pd_id}";
$pro = sql_fetch($sql);
?>
<div id="id01" class="w3-modal w3-animate-opacity no-view" style="padding-top:0;">
    <div class="w3-modal-content w3-card-4">
        <div class="w3-container">
            <input type="hidden" value="<?php echo $sell_mb_id;?>" name="sell_mb_id" id="sell_mb_id">
            <input type="hidden" value="<?php echo $pd_id;?>" name="pd_id" id="pd_id">
            <h2>판매등록</h2>
            <div>
                <input type="text" value="<?php if($talk_list[0]["pd_price"]>0){echo $talk_list[0]["pd_price"];}?>" name="price" id="price" placeholder="판매가격" required>
            </div>
            <div>
                <input type="button" value="취소" onclick="modalClose()"><input type="button" value="이회원에게 판매" onclick="addSell();" style="width:auto" >
            </div>
        </div>
    </div>
</div>
<div class="talk talk_view_container" style="<?php if($img1!=""){?>background-image:url('<?php echo G5_DATA_URL."/product/".$img1;?>');<?php }?>background-size:cover;background-repeat:no-repeat;background-position:center;">
    <div class="close" onclick="location.href='<?php echo $back_url;?>'">
        <img src="<?php echo G5_IMG_URL?>/view_close.svg" alt="" >

    </div>
    <div class="price">
        <h2> <span><?php if($talk_list[0]['pd_type']==1){ if($talk_list[0]['pd_type2']==4){?>구매예상금액<?php }else{?>판매금액<?php } }else{if($talk_list[0]['pd_type2']==4){?>구매예상금액<?php }else{?>계약금<?php } }?></span>
            <?php if($talk_list[0]["pd_price"]>0){echo "￦ ".number_format($talk_list[0]["pd_price"]);}else{echo "0원";}?></h2>
        <?php if($talk_list[0]["pd_type"]==1 && $talk_list[0]["pd_type2"]==8 && $talk_list[0]["mb_id"] == $member["mb_id"]){?>
            <div class="sell_btn">
                <input type="button" value="이 회원에게 판매하기" class="btn" onclick="fnSell();">
            </div>
        <?php }?>
        <div class="price_bg"></div>
    </div>
    <?php if($pro["pd_type"]==2 && $pro["pd_type2"]==8){?>
        <div class="talk_info">
            <input type="button" value="거래유의사항 전달" class="">
        </div>
    <?php }?>
    <div class="msg_container" id="msg_container">
        <div class="msg_bg"></div>
        <?php if(count($talk_list)==0){?>
            <div class="no-list">
                <p>대화방에 참여 하였습니다.</p>
                <?php

                 if($pro["pd_type"]==2 && $pro["pd_infos"] != ""){?>
                    <p style="border-radius: 10px;font-size: 4vw;background-color: #ffe22e;color: #000;text-align: left;padding: 2vw;width: calc(100% - 8vw);"><?php echo nl2br($pro["pd_infos"]);?></p>
                <?php }?>
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
                            <div class="arrow"><img src="<?php echo G5_IMG_URL?>/ic_chat.png" alt=""></div>
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
        <?php if(count($mywordss)>0){?>
        <div class='panel noselect'>
            <div class='admin-panel'>
                <!-- <label class='text' for='toggle'>Admin Settings</label>-->
                <label class='fas fa-bars' for='toggle'></label>
            </div>
            <input type='checkbox' id='toggle' checked="false">
            <div class='menu-panel'>

                <div class='arrow'></div>
                <?php for($i=0;$i<count($mywordss);$i++){
                    if($mywordss[$i]!=""){
                        ?>
                        <a href='#' class='row'>
                            <div class='column-left'><?php echo $mywordss[$i];?></div>
                            <div class='column-right'></div>
                        </a>
                    <?php }
                }?>
            </div>
        </div>
        <?php }?>
        <input type="button" value="보내기" class="send_msg" >
        <!--<div class="option">
            <div class="menu1">개인문구</div>
            <?php /*if($talk_list[0]["pd_type"]==2){*/?>
                <div class="menu2">거래유의사항</div>
            <?php /* }*/?>
        </div>-->
    </div>



</div>
<script>
    var roomid = "<?php echo $roomid;?>";

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
                url:g5_url+"/mobile/page/ajax/ajax.read_msg2.php",
                method:"post",
                data:{pd_id:pd_id,mb_id:mb_id,read_mb_id:read_mb_id,read_id:read_id,roomid:roomid},
                dataType:'json'
            }).done(function(data){
                if(data.cnt > 0) {
                    for (var i = 0; i < data.cnt; i++) {
                        var text = JSON.stringify(data.msg[i]);
                        text = text.replace(/\"/g,"");
                        $(".msg_container").append(text);
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

        //채팅 간편글
        $(".menu-panel a").click(function(){
            fnSendMsSimple($(this).find(".column-left").text());
        });

        $("#message").focus(function(objEvent){
            //스크롤 위치
            setTimeout(function(){element.scrollTop = element.scrollHeight;},1000);
        });

        $(".send_msg").click(function(){
            $("#message").focus();
            var pd_id = "<?php echo $pd_id;?>";
            var mb_id = "<?php echo $member["mb_id"];?>";
            var read_mb_id = "<?php echo $send_mb_id;?>";
            var message = $("#message").val();
            if(message == ""){
                alert('메세지를 입력해 주세요');
                return false;
            }
            //누적 메시지 체크
            var read_id = $("#talk_ids").val();

            //스크롤 위치
            var element = document.getElementById("msg_container");

            $.ajax({
                url:g5_url+"/mobile/page/ajax/ajax.send_msg.php",
                method:"post",
                data:{pd_id:pd_id,mb_id:mb_id,read_mb_id:read_mb_id,message:message,read_id:read_id,roomid:roomid},
                dataType:"json"
            }).done(function(data){
                console.log(data);
                if(data.status=="success"){
                    if(roomid=="") {
                        $(".msg_container").append(data.msg);
                        element.scrollTop = element.scrollHeight;
                        $("#talk_ids").val(data.ids);
                    }
                    $("#message").val('');
                    //$("#message").focus();
                }else{
                    alert("통신 오류 입니다.");
                }
            });
        });
    });

    // 내게시글이므로 read_mb_id는 대화 상대에따라 변경
    function fnSendMsg(){

    }


    function fnSendMsSimple(msg){
        var pd_id = "<?php echo $pd_id;?>";
        var mb_id = "<?php echo $member["mb_id"];?>";
        var read_mb_id = "<?php echo $send_mb_id;?>";
        var message = msg;
        if(message == ""){
            alert('메세지를 입력해 주세요');
            return false;
        }
        //누적 메시지 체크
        var read_id = $("#talk_ids").val();

        //스크롤 위치
        var element = document.getElementById("msg_container");

        $.ajax({
            url:g5_url+"/mobile/page/ajax/ajax.send_msg.php",
            method:"post",
            data:{pd_id:pd_id,mb_id:mb_id,read_mb_id:read_mb_id,message:message,read_id:read_id,roomid:roomid},
            dataType:"json"
        }).done(function(data){
            console.log(data);
            if(data.status=="success"){
                if(roomid=="") {
                    $(".msg_container").append(data.msg);
                    element.scrollTop = element.scrollHeight;
                    $("#talk_ids").val(data.ids);
                }
                $("#message").val('');
            }else{
                alert("통신 오류 입니다.");
            }
        });
    }
    function fnSell(){
        $("#id01").css({"display":"block","z-index":"91"});
        location.hash = "modal";
    }

    function addSell(){
        var price = $("#price").val();
        var pd_id = $("#pd_id").val();
        var sell_mb_id = $("#sell_mb_id").val();

        if(confirm("해당 판매글의 상태가 판매중으로 변경됩니다.\r판매 하시겠습니까?")) {
            //바로 지목 판매이므로 상태는 판매중으로 변경
            $.ajax({
                url: g5_url + "/mobile/page/ajax/ajax.insert_cart.php",
                method: "POST",
                data: {price: price, pd_id: pd_id, sell_mb_id: sell_mb_id, status: 1}
            }).done(function (data) {
                console.log(data);
            });
        }else{
            return false;
        }
    }
</script>
<?php
include_once (G5_PATH."/tail.sub.php");