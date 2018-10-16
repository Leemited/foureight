<?php 
include_once("../../common.php");
include_once(G5_EXTEND_PATH."/image.extend.php");

//접속 카운트

if($member["mb_id"]){
	$now = date("Y-m-d");
	$recent = sql_query("select * from `recent_product` where mb_id = '{$member[mb_id]}' and pd_id = '{$pd_id}' and pd_date = '{$now}'");
	$cnt = 0;
	while($row = sql_fetch_array($recent)){
		$cnt++;
	}
	if($cnt==0){
		sql_query("insert into `recent_product` (mb_id,pd_id,pd_date)values('{$member[mb_id]}','{$pd_id}','{$now}');");
	}
}else{
	if($_SESSION["pd_id"]==""){
		$_SESSION["pd_id"] = "'".$pd_id."'";
	}else{
		$_SESSION["pd_id"] .= ",'".$pd_id."'";
	}
}
//조회수 업데이트
if(strpos($_SESSION["pd_id"],$pd_id)===false){
	$_SESSION["pd_id"] .= $pd_id.",";
	sql_fetch("update `product` set pd_hits = pd_hits+1 where pd_id = {$pd_id}");
}

//댓글 가져오기
$sql = "select *,m.mb_id as member_id from `product_comment` as c left join `g5_member` as m  on c.mb_id = m.mb_id where c.pd_id = '{$pd_id}' and comment_re = 0 order by comment_datetime asc";
$res = sql_query($sql);
while($row=sql_fetch_array($res)){
    $comment[] = $row;
}



//제시목록
if($_SESSION["lat"] && $_SESSION["lng"]) {
    $sql = "select *, 6371 * 2 * ATAN2(SQRT(POW(SIN(RADIANS({$_SESSION["lat"]} - p.pd_lat)/2), 2) + POW(SIN(RADIANS({$_SESSION["lng"]} - p.pd_lng)/2), 2) * COS(RADIANS(p.pd_lat)) * COS(RADIANS({$_SESSION["lat"]}))), SQRT(1 - POW(SIN(RADIANS({$_SESSION["lat"]} - p.pd_lat)/2), 2) + POW(SIN(RADIANS({$_SESSION["lng"]} - p.pd_lng)/2), 2) * COS(RADIANS(p.pd_lat)) * COS(RADIANS({$_SESSION["lat"]})))) AS distance from `product_pricing` as c left join `product` as p on c.pricing_pd_id = p.pd_id where c.pd_id = '{$pd_id}' order by c.sign_date asc";
}else{
    $sql = "select *,'거리정보 없음' as distance from `product_pricing` as c left join `product` as p on c.pricing_pd_id = p.pd_id where c.pd_id = '{$pd_id}' order by c.sign_date asc";
}
$sing = $sql;
$res = sql_query($sql);
while($row=sql_fetch_array($res)){
    $pricing[] = $row;
}

$sql = "select * from `product` where pd_id = {$pd_id}";
$view = sql_fetch($sql);
$photo = explode(",",$view["pd_images"]);

//게시글 설정
$sql = "select * from `g5_member` as m left join `mysetting` as s on m.mb_id = s.mb_id where s.mb_id = '{$view[mb_id]}'";
$profile = sql_fetch($sql);

$mb_id = $member["mb_id"];
if($mb_id == ""){
    $mb_id = session_id();
}

//해당 제품의 대화목록
$sql = "select  * from `product_chat` where pd_id = {$pd_id} and (send_mb_id = '{$mb_id}' or read_mb_id = '{$mb_id}') order by msg_datetime asc";
$res = sql_query($sql);
while($row = sql_fetch_array($res)){
    $talk_list[] = $row;
    $talk_ids[] = $row["id"];
}
if(count($talk_ids) > 0) {
    $talk_read_ids = implode(",", $talk_ids);
}

if($view["pd_date"]) {
    $loc_data = $view["pd_date"];
    if($view["pd_update"]){
        $loc_data = $view["pd_update"];
    }
    $now = date("Y-m-d H:i:s");
    $time_gep = round((strtotime($now) - strtotime($loc_data)) / 3600);
    if($time_gep == 0){
        $time_gep = "몇 분전";
    }else if($time_gep < 24){
        $time_gep = $time_gep."시간 전";
    }else if($time_gep > 24){
        $time_gep = round($time_gep / 24)."일 전";
    }
}else{
    $time_gep = "정보 없음";
}
$tag = explode("/", $view["pd_tag"]);

for($i=0;$i<count($tag);$i++){
    $tagall .= "#".$tag[$i];
}

$tagimg = get_images(G5_DATA_PATH."/product/".$photo[0]);
if($profile['comment_scret_set']==1){
    $is_comment = true;
}

if($profile["show_hp"]==0){
    $is_hp = true;
}

//wished cnt
$sql = "select count(*)as cnt from `wish_product` where pd_id = '{$view[pd_id]}'";
$wish = sql_fetch($sql);

//my wish check
$sql = "select * from `wish_product` where pd_id = '{$view[pd_id]}' and mb_id = '{$member[mb_id]}'";
$res = sql_query($sql);
$mywish = false;
while($row = sql_fetch_array($res)){
    $mywish = true;
}
$myset = sql_fetch("select * from `mysetting` where mb_id = '{$member[mb_id]}'");
if($myset["comment_secret_set"]=="1"){
    $is_comment= true;
}
//include_once(G5_MOBILE_PATH."/head.view.php");
?>

<style>
    .DetailImage{width:100%;height:100%;background-color:#000;position:absolute;z-index:90000;top:0;left:0;overflow: hidden;transform:translate(0, 0)}
    .DetailImage #imgs{width:100%;height:100%;display:inline-block;vertical-align: middle}
    .DetailImage #imgs img{display: inline-block;position: absolute;max-width: 100%;max-height: 100%;width: auto;height: auto;margin: auto;top: 0;bottom: 0;left: 0;right: 0;}
    .DetailImage .close {position:absolute;top:4vw;right:4vw;width:10vw;height:10vw;}
    .DetailImage .list2{white-space: nowrap;position: relative;font-size: 0;width: 100%;height: 100%;;-webkit-transition: all 500ms;-moz-transition: all 500ms;-ms-transition: all 500ms;-o-transition: all 500ms;transition: all 500ms;}
    .DetailImage .list2 .item2{width:100%;height:100%;display:inline-block;}
    .talk{width:100%;height:100%;position:absolute;z-index:90000;top:0;left:0;overflow: hidden;transform:translate(0, 0)}
    .talk .close {position: absolute;top:3vw;left:5vw;width:7vw;height:7vw;z-index:900;}
    .msg_container{height:calc(100% - 55.3vw);width:100%;overflow-y:scroll;margin-top:12vw}
    .msg_container .msg_bg{height:calc(100% - 30vw);width:100%;position:absolute;top:0;left:0;background-color:#fff;opacity: 0.9;z-index:2}
    .msg_container .no-list{border-radius:10vw;position:relative;z-index:3}
    .msg_container .no-list p{padding: 4px 0;width: calc(100% - 4vw);margin: 2vw;border-radius: 10vw;text-align: center;background: RGBA(0,0,0,0.5);color: #FFF;font-size:3vw;}
    .msg_container .msg_box{width:100%;position: relative;z-index: 10;margin:3vw 0;display: inline-block;}
    .msg_container .msg_box.my_msg .in_box{width:100%;float:right;display:inline-block;}
    .msg_container .msg_box.read_msg .in_box{width:calc(100% - 3vw);display: inline-block;margin-left:3vw;}
    .msg_container .msg_box.read_msg .in_box .box_con{display: inline-block;width: calc(100% - 18vw);margin-left: 2vw;}
    .msg_container .msg_box.my_msg{text-align:right;}
    .msg_container .msg_box.my_msg .msg{max-width:65vw;background-color: #ffe701;color: #000;font-size: 3.6vw;background-image: url();padding: 3vw;font-weight: bold;border-radius: 4vw;display: inline-block;position: relative;margin-right: 3vw;text-align:right;-ms-word-wrap: break-word;word-wrap: break-word;}
    .msg_container .msg_box.my_msg .date{display: inline-block;vertical-align: bottom;margin-right: 2vw;font-size:3vw;}
    .msg_container .msg_box.read_msg{text-align:left}
    .msg_container .msg_box.read_msg .read_profile{display: inline-block;vertical-align: top;}
    .msg_container .msg_box.read_msg .in_box .box_con .read_name{font-size:3.6vw;font-weight:bold;}
    .msg_container .msg_box.read_msg .in_box .box_con .msg{padding: 3vw;font-size: 3.6vw;font-weight: bold;background-color: #fff;border-radius: 4vw;margin: 2vw 0 0 1vw;max-width: 50vw;display: inherit;word-break: break-all;}
    .msg_container .msg_box.read_msg .in_box .box_con .date{display: inline-table;vertical-align: bottom;margin-right: 2vw;font-size:3vw;}
    .msg_controls {padding:4vw 2vw;width:calc(100% - 4vw);background-color:#fff;position: relative;z-index:9;}
    .msg_controls input[type='text']{border:none;font-size:4.6vw;width:60vw}
    .msg_controls .send_msg{border:none;position:absolute;right:3vw;top:2.6vw;-webkit-border-radius:3vw;-moz-border-radius:3vw;border-radius:3vw;background-color:#fee801;padding: 1.6vw 3vw;font-size: 4vw;font-weight: bold;font-family: "nsr",sans-serif;}
    .msg_controls .option{position:absolute;right:24vw;top:2.6vw;-webkit-border-radius:3vw;-moz-border-radius:3vw;border-radius:3vw;background-image:url("<?php echo G5_IMG_URL?>/ic_menu_btn.svg");background-repeat:no-repeat;background-size:80% 80%;background-position:center;background-color:#fee801;width:8vw;height:8vw;}
    .msg_controls .option div{position:absolute;opacity:0;bottom:0;font-size:3.8vw;color:#fff;width:16vw;text-shadow: 0 0 1vw RGBA(0,0,0,0.6);-webkit-transition: all 0.4s ease-in-out;-moz-transition: all 0.4s ease-in-out;-ms-transition: all 0.4s ease-in-out;-o-transition: all 0.4s ease-in-out;transition: all 0.4s ease-in-out;}
    .msg_controls .option .menu1{background-image:url("<?php echo G5_IMG_URL?>/");}
    .msg_controls .option.active .menu1{bottom:13vw;opacity:1;}
    .talk .price {height:31vw;display:table;width:100%;position:relative}
    .talk .price p{color:#fff;font-size:4vw;text-align:center;position:absolute;vertical-align: middle;top:5vw;width:100%}
    .talk .price h2{color:#ffdf00;font-size:9vw;text-align: center;display:table-cell;vertical-align: middle;}
    .talk .price .price_bg{width:100%;height:100%;background-color:#000;opacity:0.8;position: absolute;top: 0;left: 0;z-index: -1;}

</style>
<div class="DetailImage" id="DetailImage">
    <div id="imgs">
        <ul class="list2">
            <?php for($i=0;$i<count($photo);$i++){
                $imgs = get_images(G5_DATA_PATH."/product/".$photo[$i],'','');
                ?>
                <li class="item2" id="item2" style="background-image:url('<?php echo G5_DATA_URL;?>/product/<?php echo $imgs?>');background-size:contain;background-repeat:no-repeat;background-position:center">
                </li>
            <?php }?>
        </ul>
    </div>
    <div class="close" onclick="$('.DetailImage').hide()"><img src="<?php echo G5_IMG_URL?>/ic_view_close.svg" alt=""></div>
</div>
<div class="talk">
    <div class="close" onclick="modalCloseTalk();">
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
                if ($mb_id == $talk_list[$i]["send_mb_id"]) {?>
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
            <?php if($mb_id==$view["mb_id"]){?>
                <?php if($view["pd_type"]==2){?>
                <div class="menu2">거래유의사항</div>
            <?php } }?>
        </div>
    </div>
    
    <div class="price">
        <p><?php if($view['pd_type']==1){ if($view['pd_type2']==4){?>구매예상금액<?php }else{?>판매금액<?php } }else{if($view['pd_type2']==4){?>구매예상금액<?php }else{?>계약금<?php } }?></p>
        <h2><?php if($view["pd_price"]>0){echo "￦ ".number_format($view["pd_price"]);}else{echo "0원";}?></h2>
        <div class="price_bg"></div>
    </div>
    
</div>
<div class="view_containter">
    <div class="view_top">
<!--        <div class="close" onclick="gotolist();">-->
        <div class="close" onclick="modalCloseThis();">
            <img src="<?php echo G5_IMG_URL?>/view_close.svg" alt="" >
        </div>
        <div class="slider_nav">
            <?php for($i=0;$i<count($photo);$i++){?>
                <div class="s_nav <?php if($i==0){echo "active";}?>"></div>
            <?php }?>
            <?php if($view["pd_video"] != ""){?>
                <div class="s_nav"></div>
            <?php } ?>
        </div>
        <div class="wished <?php if($mywish){echo "wishes";}?>" <?php if($view["mb_id"]!=$member["mb_id"]){?>onclick="fnwished('<?php echo $view["pd_id"];?>');"<?php }?> >
            <img src="<?php echo G5_IMG_URL?>/view_wish.svg" alt="">
            <?php if($wish["cnt"]>0){?>
            <span><?php echo $wish["cnt"];?></span>
            <?php }?>
        </div>
    </div>
    <div class="view_bottom">
        <div class="profile">
            <?php if($profile["mb_id"]!=$member["mb_id"]){?>
            <div class="profile_menu active">
                <div class="menu1" onclick="location.href='<?php echo G5_MOBILE_URL;?>/page/mypage/mypage.php?mode=profile&pro_id=<?php echo $view["mb_id"];?>'"><div class="icon_box"><img src="<?php echo G5_IMG_URL?>/view_menu_profile.svg" alt=""></div><div class="text_box">프로필보기</div></div>
                <div class="menu2" onclick="fnTalk();"><div class="icon_box"><img src="<?php echo G5_IMG_URL?>/view_menu_talk.svg" alt=""></div><div class="text_box">간편대화</div></div>
                <?php if($profile["mb_hp"]){?>
                <?php if($profile["sms_set"]==1){?>
                <div class="menu3" onclick="location.href='sms:<?php echo $profile["mb_hp"];?>'"><div class="icon_box"><img src="<?php echo G5_IMG_URL?>/view_menu_msg.svg" alt=""></div><div class="text_box">메시지</div></div>
                <?php }?>
                <?php if($profile["hp_set"]==1){?>
                <div class="menu4" onclick="location.href='tel:<?php echo $profile["mb_hp"];?>'"><div class="icon_box"><img src="<?php echo G5_IMG_URL?>/view_menu_call.svg" alt=""></div><div class="text_box">전화</div></div>
                <?php }?>
                <?php }?>
                <?php if($view["mb_id"]==$member["mb_id"] && $view["pd_update_cnt"] < 5){?>
                <div class="menu5"><div class="icon_box"><img src="<?php echo G5_IMG_URL?>/view_menu_up.svg" alt=""></div><div class="text_box">업하기</div></div>
                <?php }?>
            </div>
            <?php }?>
            <div class="img" onclick="fnProfileMenu();">
                <?php if($profile["mb_profile"]){
                    ?>
                    <img src="<?php echo $profile["mb_profile"];?>" alt="" class="proimg">
                <?php }else{ ?>
                    <img src="<?php echo G5_IMG_URL?>/no-profile.svg" alt="">
                <?php } ?>
            </div>
            <span class="txt">
                <div><?php echo $profile[mb_nick];?></div>
            </span>
                <?php if($profile["like_set"]== 1){?>
            <div class="pd_like">
                <img src="<?php echo G5_IMG_URL?>/view_like.svg" alt="" class="likeimg" id="<?php echo $view["pd_id"];?>">
                <span><?php echo $view["pd_recom"];?></span>
            </div>
                <?php }?>
        </div>
        <div class="content">
            <?php if($view["pd_images"]!="" || $view["pd_video"]!=""){?>
            <h2><?php echo $view["pd_tag"];?></h2>
            <?php }?>
            <?php if($view["pd_price"]==0){?>
                <h1><?php echo "제시요망";?></h1>
            <?php }else{ ?>
                <h1><?php echo "￦ ".number_format($view["pd_price"]);?></h1>
            <?php }?>
        </div>
        <div class="view_btns">
            <?php if($member["mb_id"] == $view["mb_id"]){
                $width = 3;
                ?>
                <?php if($view["pd_update_cnt"] < 5){
                    $width = 4;
                    ?>
                <input type="button" value="업하기" onclick="fnProductUp();" class="point">
                <?php } ?>
                <input type="button" value="상태변경" onclick="fnStatus('<?php echo $view["pd_id"];?>','<?php echo $view["pd_status"];?>');" >
                <input type="button" value="글수정" onclick="location.href='<?php echo G5_MOBILE_URL;?>/page/write.php?pd_id=<?php echo $pd_id;?>'">
                <input type="button" value="삭제" onclick="fnDelete('<?php echo $view[pd_id];?>')">
            <?php }else{
                $width = 1;
                ?>
                <?php if($view["pd_type2"] == 8){?>
                    <?php if($view["pd_discount"]==1){
                        $width = 2; ?>
                    <input type="button" value="흥정하기">
                    <?php }?>
                    <input type="button" value="구매예약" class="point">
                <?php }else if($view["pd_type2"] == 4){
                    $width = 2;
                    ?>
                    <input type="button" value="제시하기" onclick="fnPricing('<?php echo $view["pd_id"];?>')">
                    <input type="button" value="대화하기">
                <?php }?>
            <?php }?>
        </div>
        <div class="view_bottom_bg"></div>
    </div>
    <div class="view_detail">
        <div class="detail_arrow">
            <img src="<?php echo G5_IMG_URL?>/ic_view_arrow.png" alt="">
        </div>
        <div class="detail_content">
            <div class="top">
                <div class="detail_close" >
                    <img src="<?php echo G5_IMG_URL?>/view_close.svg" alt="" >
                </div>
                <div class="info">
                     <div class="view_cnt">
                         <img src="<?php echo G5_IMG_URL?>/ic_hit_list.svg" alt="">
                         <span class="count"><?php echo "";?></span>/<?php echo $view["pd_hits"];?>
                     </div>
                    <div class="view_blind_cnt" <?php if($view["mb_id"]!= $member["mb_id"]){?>onclick="fnBlind('<?php echo $pd_id;?>','')"<?php }?>>
                        <?php
                        switch ($view["pd_blind"]){
                            case 0:
                                $img = "ic_view_blind_00.svg";
                                break;
                            case 1:
                                $img = "ic_view_blind_01.svg";
                                break;
                            case 2:
                                $img = "ic_view_blind_02.svg";
                                break;
                            case 3:
                                $img = "ic_view_blind_03.svg";
                                break;
                            case 4:
                                $img = "ic_view_blind_04.svg";
                                break;
                            case 5:
                                $img = "ic_view_blind_05.svg";
                                break;
                            case 6:
                                $img = "ic_view_blind_06.svg";
                                break;
                            case 7:
                                $img = "ic_view_blind_07.svg";
                                break;
                            case 8:
                                $img = "ic_view_blind_08.svg";
                                break;
                            case 9:
                                $img = "ic_view_blind_09.svg";
                                break;
                            case 10:
                                $img = "ic_view_blind_10.svg";
                                break;
                        }?>
                        <img src="<?php echo G5_IMG_URL."/".$img?>" alt="">
                    </div>
                    <!--<div class="infomenu">
                        <img src="<?php /*echo G5_IMG_URL*/?>/ic_menu_option.svg" alt="">
                        <ul class="view_opt_menu">
                            <li>test1</li>
                            <li>test1</li>
                            <li>test1</li>
                        </ul>
                    </div>-->
                </div>
            </div>
            <div class="con">
                <div class="pd_contents">
                    <h2>상세설명</h2>
                    <p><?php echo ($view["pd_content"])?$view["pd_content"]:"등록된 상세 설명이 없습니다.";?></p>
                </div>
                <?php if($view["pd_video_link"]){?>
                <div class="link_video">
                    <?php $links = explode(",", $view["pd_video_link"]);
                    for($i=0;$i<count($links);$i++){?>
                    <div class="jetpack-video-wrapper">
                        <span class="embed-youtube" style="text-align:center; display: block;">
                            <iframe class="youtube-player" type="text/html" src="<?php echo $links[$i];?>?version=3&rel=1&fs=1&autohide=2&showsearch=0&showinfo=1&iv_load_policy=1&wmode=transparent" allowfullscreen="true" style="border: 0px; display: block; margin: 0px; height: 55vw;" data-ratio="0.5625" data-width="560" data-height="315"></iframe>
                        </span>
                    </div>
                    <?php } ?>
                </div>
                <?php } ?>
                <?php if($view["pd_location"]){
                    if($view["pd_lat"]&&$view["pd_lng"]){?>
                <div class="maps">
                    <div class="times">
                        <img src="<?php echo G5_IMG_URL?>/view_time.svg" alt=""><?php echo $time_gep;?>
                    </div>
                    <div class="addr">
                        <img src="<?php echo G5_IMG_URL?>/view_pin_black.svg" alt="" id="pin"><label for="pin" id="addr_label"><?php echo $view["pd_location"];?></label>
                    </div>
                    <div class="clear"></div>
                    <!--<div class="fullbtn" onclick="location.href='<?php /*echo G5_MOBILE_URL*/?>/page/view_map.php?pd_id=<?php /*echo $pd_id;*/?>&location=<?php /*echo $view["pd_location"];*/?>'">
                        <img src="<?php /*echo G5_IMG_URL;*/?>/ic_fullmap.svg" alt="확대">
                    </div>-->
                    <div id="map" onclick="fnMapView('<?php echo $pd_id;?>','<?php echo $view["pd_location"];?>')"></div>
                </div>
                    <?php }?>
                <h4>거래선호 위치</h4>
                <ul class="my_loc">
                        <li><?php echo $view["pd_location"];?></li>
                </ul>
                <div class="clear"></div>
                 <?php  }?>
                <div class="share">
                    <div id="fb-root"></div>
                    <h2>게시글 공유하기</h2>
                    <ul>
                        <li id="kakao_link">
                            <img src="<?php echo G5_IMG_URL;?>/view_share_kakao.png" alt="">
                        </li>
                        <li class="fb-share-button" onclick="location.href='https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(G5_URL."/mobile/page/view.php?pd_id=".$pd_id."&wr_subject=".$view['pd_name']); ?>&title=<?php echo $view['pd_name']; ?>&description=<?php echo $tagall; ?>&img=<?php echo urlencode(G5_DATA_URL."/product/".$tagimg); ?>'">
                            <img src="<?php echo G5_IMG_URL;?>/view_share_facebook.png" alt="">
                        </li>
                        <!--<li onclick="fnMsg()">
                            <img src="<?php /*echo G5_IMG_URL;*/?>/view_share_msg.png" alt="">
                        </li>-->
                        <li class="clipboard" data-clipboard-action="copy" data-clipboard-target="#copylink">
                            <img src="<?php echo G5_IMG_URL;?>/view_share_link.png" alt="">
                        </li>
                    </ul>
                    <div class="clear"></div>
                </div>
<!--                <div>-->
<!--                    <input type="hidden" name="name" id="name" value="--><?php //if($member["mb_id"]){echo $member["mb_id"];}else{echo "geust";}?><!--">-->
<!--                    <script src="--><?php //echo G5_URL;?><!--/client.js"></script>-->
<!--                    <script src="--><?php //echo G5_URL;?><!--/node_modules/socket.io-client/dist/socket.io.js"></script>-->
<!--                    <div id="message" style="width:400px; height:200px;"></div>-->
<!---->
<!--                    <div id="chatInput">-->
<!--                        메시지를 입력하세요 <input type='text' id='txtChat' name="txtChat">-->
<!--                    </div>-->
<!--                </div>-->
                <?php if(count($pricing)>0){?>
                <div class="comment">
                    <h2>제시목록</h2>
                    <div class="pricing cm_box">
                        <ul class="pri_container">
                            <?php for($i=0;$i<count($pricing);$i++){
                                $rand = rand(1,13);
                                $pd_tag = explode("/",$pricing[$i]["pd_tag"]);
                                ?>
                            <li onclick="fn_viewer2('<?php echo $pricing[$i]["pricing_pd_id"];?>')">
                                <div class="profile <?php if(!$pricing[$i]["pd_images"] || $pricing[$i]["pd_images"]==""){echo "rand_bg".$rand; }?>"  >
                                    <?php
                                    if($pricing[$i]["pd_images"]){
                                        $img = explode(",",$pricing[$i]["pd_images"]);
                                        $img1 = get_images(G5_DATA_PATH."/product/".$img[0],'','');
                                    ?>
                                        <img src="<?php echo G5_DATA_URL."/product/".$img1;?>" alt="">
                                    <?php }else{
                                        for($s=0;$s<count($pd_tag);$s++){
                                            echo "#".$pd_tag[$s];
                                        }
                                    } ?>
                                </div>
                                <div class="coms">
                                    <p><?php echo "￦ ".number_format($pricing[$i]["pd_price"]);?> / <?php echo distTran($pricing[$i]["distance"]);?></p>
                                    <h2><?php echo $pricing[$i]["pricing_content"];?></h2>
<!--                                    <div class="product_vi">
                                        <input type="button" value="물건보기" onclick="fn_viewer2('<?php /*echo $pricing[$i]["pricing_pd_id"];*/?>')">
                                    </div>-->
                                </div>
                            </li>
                            <?php }?>
                        </ul>
                    </div>
                </div>
                <?php }?>
                <div class="comment">
                    <input type="hidden" name="comment_re" id="comment_re" value="">
                    <input type="hidden" name="comment_re_mb_id" id="comment_re_mb_id" value="">
                    <input type="hidden" name="comment_re_cm_id" id="comment_re_cm_id" value="">
                    <h2>댓글</h2>
                    <div class="cm_box">
                        <ul class="cm_container">
                            <?php for($i=0;$i<count($comment);$i++){
                                $sql = "select *,m.mb_id as member_id from `product_comment` as p left join `g5_member` as m on p.mb_id = m.mb_id where p.parent_cm_id = {$comment[$i]["cm_id"]} order by p.comment_datetime asc";
                                $recm_res = sql_query($sql);
                                while($row = sql_fetch_array($recm_res)) {
                                    $recomment[] = $row;
                                }

                                ?>
                                <li class="<?php if($view["mb_id"]!=$member["mb_id"]){if($comment[$i]["comment_status"]=="3" || $is_comment){echo "cm_lock ";} } ?>" id="cmt<?php echo $comment[$i][cm_id];?>">
                                    <div class="profile" <?php if($comment[$i]["member_id"] != $member["mb_id"]){ echo "onclick=fnRecom('".$comment[$i]["cm_id"]."','".$comment[$i]["member_id"]."','".$comment[$i]["mb_name"]."','".$comment[$i]["comment_status"]."')";} ?> >
                                        <?php if($view["mb_id"]!=$member["mb_id"]){if($comment[$i]["comment_status"]=="3"  || $is_comment){ ?>
                                        <img src="<?php echo G5_IMG_URL?>/profile_lock.svg" alt="" id="profile">
                                        <?php }else if($comment[$i]["mb_profile"]){?>
                                            <img src="<?php echo $comment[$i]["mb_profile"];?>" alt="" id="profile">
                                        <?php }else if($comment[$i]["mb_profile"] ==""){ ?>
                                            <img src="<?php echo G5_IMG_URL?>/no-profile.svg" alt="">
                                        <?php } }else { if ($comment[$i]["mb_profile"]) { ?>
                                                <img src="<?php echo $comment[$i]["mb_profile"]; ?>" alt="" id="profile">
                                        <?php } else if ($comment[$i]["mb_profile"] == "") { ?>
                                            <img src="<?php echo G5_IMG_URL ?>/no-profile.svg" alt="">
                                        <?php }}?>
                                    </div>
                                    <div class="coms">
                                        <p><?php echo $comment[$i]["mb_nick"];?> / <?php echo $comment[$i]["comment_datetime"];?></p>
                                        <?php if($view["mb_id"]!=$member["mb_id"]){if($comment[$i]["comment_status"]=="3"  || $is_comment ){?>
                                            <h2 class="loctitle">비공개</h2>
                                        <?php }else{ ?>
                                        <h2><?php echo $comment[$i]["comment_content"];?></h2>
                                        <ul>
                                            <li><img src="<?php echo G5_IMG_URL?>/ic_comment_blind.png" alt="" onclick="fnBlind('<?php echo $view["pd_id"];?>','<?php echo $comment[$i]["cm_id"];?>');">신고</li>
                                            <?php if($comment[$i]["comment_re"]== 0){?>
                                            <li>댓글 <span><?php echo number_format($comment[$i]["re_comment_cnt"]);?></span></li>
                                            <?php }?>
                                            <?php if($comment[$i]["mb_id"]!=$member["mb_id"]){?>
                                            <li onclick="fnLike('no','<?php echo $comment[$i]["cm_id"];?>')">반대 <span class="unlike<?php echo $comment[$i]["cm_id"];?>"><?php echo number_format($comment[$i]["unlike"]);?></span></li>
                                            <li onclick="fnLike('yes','<?php echo $comment[$i]["cm_id"];?>')">추천 <span class="like<?php echo $comment[$i]["cm_id"];?>"><?php echo number_format($comment[$i]["like"]);?></span></li>
                                            <?php }  ?>
                                        </ul>
                                        <?php }}else{ ?>
                                            <h2><?php echo $comment[$i]["comment_content"];?></h2>
                                            <ul>
                                                <li><img src="<?php echo G5_IMG_URL?>/ic_comment_blind.png" alt="" onclick="fnBlind('<?php echo $view["pd_id"];?>','<?php echo $comment[$i]["cm_id"];?>');">신고</li>
                                                <?php if($comment[$i]["comment_re"]== 0){?>
                                                    <li>댓글 <span><?php echo number_format($comment[$i]["re_comment_cnt"]);?></span></li>
                                                <?php }?>
                                                <?php if($comment[$i]["mb_id"]!=$member["mb_id"]){?>
                                                <li onclick="fnLike('no','<?php echo $comment[$i]["cm_id"];?>')">반대 <span class="unlike<?php echo $comment[$i]["cm_id"];?>"><?php echo number_format($comment[$i]["unlike"]);?></span></li>
                                                <li onclick="fnLike('yes','<?php echo $comment[$i]["cm_id"];?>')">추천 <span class="like<?php echo $comment[$i]["cm_id"];?>"><?php echo number_format($comment[$i]["like"]);?></span></li>
                                                <?php }?>
                                            </ul>
                                        <?php }?>
                                    </div>
                                </li>
                            <?php 
                                if(count($recomment)!=0) {
                                    for($j=0;$j<count($recomment);$j++){
                            ?>
                                <li class="re_cm <?php if($view["mb_id"]!=$member["mb_id"]){if($recomment[$j]["comment_status"]=="3" || $is_comment){echo "cm_lock ";} } ?>" id="cmt<?php echo $recomment[$j][parent_cm_id];?>">
                                    <div class="profile" <?php if($recomment[$j]["member_id"] != $member["mb_id"]){ echo "onclick=fnRecom('".$recomment[$j]["cm_id"]."','".$recomment[$j]["member_id"]."','".$recomment[$j]["mb_name"]."','".$recomment[$j]["comment_status"]."')";} ?> >
                                        <?php if($view["mb_id"]!=$member["mb_id"]){if($recomment[$j]["comment_status"]=="3"  || $is_comment){ ?>
                                            <img src="<?php echo G5_IMG_URL?>/profile_lock.svg" alt="" id="profile">
                                        <?php }else if($recomment[$j]["mb_profile"]){?>
                                            <img src="<?php echo $recomment[$j]["mb_profile"];?>" alt="" id="profile">
                                        <?php }else if($recomment[$j]["mb_profile"] ==""){ ?>
                                            <img src="<?php echo G5_IMG_URL?>/no-profile.svg" alt="">
                                        <?php } }else { if ($recomment[$j]["mb_profile"]) { ?>
                                            <img src="<?php echo $recomment[$j]["mb_profile"]; ?>" alt="" id="profile">
                                        <?php } else if ($recomment[$j]["mb_profile"] == "") { ?>
                                            <img src="<?php echo G5_IMG_URL ?>/no-profile.svg" alt="">
                                        <?php }}?>
                                    </div>
                                    <div class="coms">
                                        <p><?php echo $recomment[$j]["mb_name"];?> / <?php echo $recomment[$j]["comment_datetime"];?></p>
                                        <?php if($view["mb_id"]!=$member["mb_id"]){if($recomment[$j]["comment_status"]=="3"  || $is_comment ){?>
                                            <h2 class="loctitle">비공개</h2>
                                        <?php }else{ ?>
                                            <h2><?php echo $recomment[$j]["comment_content"];?></h2>
                                            <ul>
                                                <li><img src="<?php echo G5_IMG_URL?>/ic_comment_blind.png" alt="" onclick="fnBlind('<?php echo $view["pd_id"];?>','<?php echo $recomment[$j]["cm_id"];?>');">신고</li>
                                                <?php if($recomment[$j]["comment_re"]== 0){?>
                                                    <li>댓글 <span><?php echo number_format($recomment[$j]["re_comment_cnt"]);?></span></li>
                                                <?php }?>
                                                <?php if($recomment[$j]["mb_id"]!=$member["mb_id"]){?>
                                                    <li onclick="fnLike('no','<?php echo $recomment[$j]["cm_id"];?>')">반대 <span class="unlike<?php echo $recomment[$j]["cm_id"];?>"><?php echo number_format($recomment[$j]["unlike"]);?></span></li>
                                                    <li onclick="fnLike('yes','<?php echo $recomment[$j]["cm_id"];?>')">추천 <span class="like<?php echo $recomment[$j]["cm_id"];?>"><?php echo number_format($recomment[$j]["like"]);?></span></li>
                                                <?php }  ?>
                                            </ul>
                                        <?php }}else{ ?>
                                            <h2><?php echo $recomment[$j]["comment_content"];?></h2>
                                            <ul>
                                                <li><img src="<?php echo G5_IMG_URL?>/ic_comment_blind.png" alt="" onclick="fnBlind('<?php echo $view["pd_id"];?>','<?php echo $recomment[$j]["cm_id"];?>');">신고</li>
                                                <?php if($recomment[$j]["comment_re"]== 0){?>
                                                    <li>댓글 <span><?php echo number_format($recomment[$j]["re_comment_cnt"]);?></span></li>
                                                <?php }?>
                                                <?php if($recomment[$j]["mb_id"]!=$member["mb_id"]){?>
                                                    <li onclick="fnLike('no','<?php echo $recomment[$j]["cm_id"];?>')">반대 <span class="unlike<?php echo $recomment[$j]["cm_id"];?>"><?php echo number_format($recomment[$j]["unlike"]);?></span></li>
                                                    <li onclick="fnLike('yes','<?php echo $recomment[$j]["cm_id"];?>')">추천 <span class="like<?php echo $recomment[$j]["cm_id"];?>"><?php echo number_format($recomment[$j]["like"]);?></span></li>
                                                <?php }?>
                                            </ul>
                                        <?php }?>
                                    </div>
                                </li>
                            <?php }
                                }
                                unset($recomment);
                                }
                            ?>
                            <?php if(count($comment) == 0){?>
                                <li class="no-comment">등록된 댓글이 없습니다.</li>
                            <?php }?>
                        </ul>
                        <div class="cm_in">
                            <p class="re_com" style="padding:0;margin:0;font-size:2.8vw;position:absolute;top:1vw;left:2vw;"></p>
                            <div <?php if($is_comment){?>style="display:none"<?php }?>>
                                <input type="checkbox" id="secret" name="secret" value="3" style="display:none;" <?php if($is_comment){?>checked<?php }?>><label for="secret" class="secret">비공개</label>
                            </div>
                            <div class="recm_cancel">X</div>
                            <input type="text" id="comment_content" placeholder="댓글을 남겨주세요" class="comment_input"><input type="button" class="comment_btn" id="comment_btn" value="등록">
                        </div>
                    </div>
                </div>

                <div class="detail_arrow_close">
                    <img src="<?php echo G5_IMG_URL?>/ic_view_arrow_close.png" alt="">
                </div>
            </div>
        </div>
        <div class="detail_bg"></div>
    </div>
</div>
<div class="main" id="main">
        <?php if($view["pd_images"]=="" && $view["pd_video"]==""){
            $rand = rand(1,13);
        ?>
        <div class="tags_box">
        <?php  for($i=0;$i<count($tag);$i++){
        ?>
            <div class="tags ">
                <label for="" class=""><?php echo "#".$tag[$i];?></label>
            </div>
        <?php } ?>
        </div>
            <div class="tags_bg rand_bg<?php echo $rand;?>">

            </div>
        <?php }else{?>
        <ul class="list">
            <?php for($i=0;$i<count($photo);$i++){

                $oriSize = getimagesize(G5_DATA_PATH."/product/".$photo[$i]);
                $img = get_images(G5_DATA_PATH."/product/".$photo[$i],$dWidth,$dHeight);
                $size = getimagesize(G5_DATA_PATH."/product/".$img);
                $ratio = $size[0] / $size[1];
                $wRatio = $dWidth/$size[0];
                $hRatio = $dHeight/$size[1];
                //alert($wRatio."//".$hRatio."//".$ratio);
                if($wRatio == $hRatio){
                    //비율 같음
                    $sssize = "contain";
                    $height = "center";
                }else if($wRatio > $hRatio){
                    //너비가 더 큼
                    if ($ratio > 1) {
                        $sssize = "100% auto";
                        $height = "center";
                    }else {
                        $sssize = "cover";
                        $height = "center";
                    }
                }else if($wRatio < $hRatio){
                    //너비가 더 작음
                    if ($ratio > 1) {
                        $sssize = "auto calc(100% - 42vw)";
                        $height = "center calc(100vh - 100vh);height:100%;";
                    } else {
                        $sssize = "auto 100%";
                        $height = "center";
                    }
                }
            ?>
            <li class="item" style="background-image:url('<?php echo G5_DATA_URL;?>/product/<?php echo $img?>');background-size:<?php echo $sssize;?>;background-repeat:no-repeat;background-position:<?php echo $height;?>">
                <input type="hidden" value="<?php echo G5_DATA_URL;?>/product/<?php echo $img?>" id="imagePath">
            </li>
            <?php }?>
            <?php if($view["pd_video"] != ""){

            ?>
            <li class="item video" >
                <video autoplay controls src="<?php echo G5_DATA_URL."/product/".$view["pd_video"];?>" width="360px" height="640px" class="view_video"></video>
            </li>
            <?php } ?>
        </ul>
        <div class="main_back">

        </div>
        <?php }?>
</div>

<a style="display:none;" id="copylink" href="http://mave01.cafe24.com/mobile/page/view.php?pd_id=<?php echo $pd_id;?>">http://mave01.cafe24.com/mobile/page/view.php?pd_id=<?php echo $pd_id;?></a>

<script>
// Slide functions
var index = 0;
var list = "";
$(function(){
    $(".DetailImage").hide();
    $(".talk").hide();
    var height = $(window).height();
    var width = $(window).width();
    var topheight = $(".top_header").height();

    $("#profile").click(function(){
        
    });

    $(".infomenu").click(function(){
        $(".view_opt_menu").toggleClass("active");
    });

    setTimeout(function(){$(".profile_menu").removeClass("active");},1300);

    $(".comment_input").bind("focus",function(){
        $(".view_bottom").css({"position":"absolute","bottom":"-100vh"});
        $(".view_detail").css({"height":"100vh","margin-bottom":"0"});
    });
    $(".comment_input").bind("blur",function(){
        $(".view_bottom").css({"position":"absolute","bottom":"0"});
        $(".view_detail").css({"height":"calc(100vh - 46vw)","margin-bottom":"22.6vh"});
    });

    $(".recm_cancel").click(function(){
        console.log("cancel");
        $("#comment_re").val('');
        $("#comment_re_mb_id").val('');
        $("#comment_re_cm_id").val('');
        $(".comment_input").attr("placeholder","댓글을 남겨주세요");
        $(this).css("display","none");
    });

    $("#comment_btn").click(function(){
        var comment = $(".comment_input").val();
        var secret = $("#secret").val();
        var mb_id = "<?php echo $member["mb_id"];?>";
        var pd_id = "<?php echo $pd_id;?>";
        var comment_re = $("#comment_re").val();
        var comment_re_mb_id = $("#comment_re_mb_id").val();
        var comment_re_cm_id = $("#comment_re_cm_id").val();
        var cm_type = '';
        if(comment_re ==""){
            cm_type = "u";
        }
        if(mb_id == ""){
            alert("로그인이 필요합니다.");
            location.href=g5_bbs_url+'/login.php?url='+g5_url+"/mobile/page/view.php?pd_id=<?php echo $pd_id;?>";
        }
        if(comment == ""){
            alert("댓글을 입력해주세요");
            return false;
        }
        $.ajax({
            url:g5_url+"/mobile/page/ajax/ajax.comment_update.php",
            method:"post",
            data:{comment:comment,mb_id:"<?php echo $member["mb_id"];?>",pd_id:pd_id,secret:secret,cm_type:cm_type,pd_mb_id:"<?php echo $view["mb_id"];?>",comment_re:comment_re,comment_re_mb_id:comment_re_mb_id,comment_re_cm_id:comment_re_cm_id}
        }).done(function(data){
            console.log(data);
            var cls= "";
            if(data=="0"){
                alert("게시물 정보가 없습니다.");
                return false;
            }
            if(data=="1"){
                alert("댓글 내용을 입력해주세요.");
                return false;
            }
            if(data=="2"){
                alert("로그인이 필요합니다.");
                return false;
            }if(data=="3"){
                alert("댓글 등록에 실패하였습니다. 다시 시도해 주세요.");
            }else{
                $(".comment_input").val('');
                if(comment_re == 1){
                    $("#cmt"+comment_re_cm_id+":last-child").appendTo(data);
                }else {
                    $(".cm_box .cm_container").append(data);
                }
                var height = $(".cm_box .cm_container").height();
                console.log(height);
                $(".cm_box .cm_container").scrollTop(height);
            }
        })
    });


    if($("li.video").length > 0){
        $(".view_video").attr({width:width+"px",height:(height - topheight)+"px"});
    }
    <?php if($view["pd_images"]!="" || $view["pd_video"]!=""){?>
    var container = document.getElementById('main');
    var swiper = new Hammer(container);

    swiper.on('swipeleft', function (e) {
        //console.log(e);
        next();
    });
    swiper.on('swiperight', function (e) {
        //console.log(e);
        prev();
    });

    swiper.on('tap', function (e) {
        previewImg();
    });

    var container2 = document.getElementById('DetailImage');
    var swiper2 = new Hammer(container2);

    swiper2.on('swipeleft', function (e) {
        //console.log(e);
        next();
    });
    swiper2.on('swiperight', function (e) {
        //console.log(e);
        prev();
    });

    var container3 = document.getElementById('imgs');
    var swiper3 = new Hammer(container3);

    var list3 = container3.querySelector('.list2');

    swiper3.get("pinch").set({enable : true});
    swiper3.on("pinch",function(e){
        zoomIn(e.scale);
    });
    swiper3.on("pinchend",function(e){
        zoomOut();
    });

    var list = container.querySelector('.list');
    var items = list.querySelectorAll('.item');

    var list2 = container2.querySelector('.list2');
    var items2 = list2.querySelectorAll('.item2');

    function slide () {
        list.style.webkitTransform = 'translate('+(-index * 100)+'%, 0)';
        list.style.backgroundPositionX = (-index * 100)+'%';
        list2.style.webkitTransform = 'translate('+(-index * 100)+'%, 0)';
        list2.style.backgroundPositionX = (-index * 100)+'%';
        slide2(index);
    }

    function next () {
        if (index < items.length - 1 || index < items2.length - 1) {
            index++;
            slide();
        }
    }

    function prev () {
        if (index > 0) {
            index--;
            slide();
        }
    }

    function previewImg(){
        $(".item").not(".video").each(function(e){
            $(".DetailImage").show();
            location.href='#preview';
        })
    }

    function zoomIn(scale){
        $(".item2").eq(index).css("transform","scale("+scale+")");
    }
    function zoomOut(){
        console.log("A");
        $(".item2").eq(index).css({transform:"scale(1)"});
    }

    <?php }?>

    $(".detail_arrow").click(function(){
        $(".view_top").css("display","none");
        $(".view_detail").css("top","0");
        $(".detail_arrow").stop(true).animate({top:'0vw',opacity:0},30);
        location.hash = "#detailview";
    });
    $(".detail_close, .detail_arrow_close").click(function(){
        $(".view_top").css("display","block");
        $(".view_detail").css("top","100vh");
        $(".detail_arrow").stop(true).animate({top:'-60vw',opacity:1},500);
    });
});

function slide2 (index) {
    $(".s_nav").each(function(e){
       if(index == e){
           $(this).addClass("active");
           $(".s_nav").not($(this)).removeClass("active");
       }
    });
}
function gotolist(){
    location.href=g5_url+'/';
}

function fnPricing(pd_id){
    $("#p_pd_id").val(pd_id);
    $.ajax({
        url:g5_url+"/mobile/page/ajax/ajax.my_product.php",
        method:"POST",
        data:{}
    }).done(function(data){
        if(data=="1"){
            alert("로그인이 필요합니다.");
            location.href=g5_bbs_url+"/login.php";
            return false;
        }
        if(data == "2"){
            alert("등록한 게시물이 없습니다.");
            return false;
        }

        $("#id07 select").append(data);
    })
    $("#id07").css("display","block");
    $("html, body").css("overflow","hidden");
    $("html, body").css("height","100vh");
    location.hash = "#modal";
}

function fnwished(pd_id){
    if($(".wished").hasClass("wishes")){
        $.ajax({
            url: g5_url + "/mobile/page/ajax/ajax.wish.php",
            method: "POST",
            data: {pd_id: pd_id, mode: "delete",mb_id:"<?php echo $member["mb_id"];?>"}
        }).done(function (data) {
            $(".wished").removeClass("wishes");
            if($(".wished").find("span").length > 0){
                var cnt = Number($(".wished").find("span").html()) - 1;
                if(cnt == 0){
                    $(".wished").find("span").remove();
                }else {
                    $(".wished").find("span").html(cnt);
                }
                alert("관심목록에서 삭제되었습니다.");
            }
        });
    }else {
        $.ajax({
            url: g5_url + "/mobile/page/ajax/ajax.wish.php",
            method: "POST",
            data: {pd_id: pd_id, mode: "insert",mb_id:"<?php echo $member["mb_id"];?>"}
        }).done(function (data) {
            console.log(data);
            if(data=="ok query") {
                $(".wished").addClass("wishes");
                if ($(".wished").find("span").length > 0) {
                    var cnt = Number($(".wished").find("span").html()) + 1;
                    $(".wished").find("span").html(cnt);
                } else {
                    var cnt = 1;
                    var span = "<span>" + cnt + "</span>";
                    $(".wished").append(span);
                }
                alert("관심목록에 추가되었습니다.");
            }
        });
    }
}

function fnDelete(pd_id){
    if(confirm("해당 게시물을 영구 삭제하겠습니까?")){
        location.href=g5_url+'/mobile/page/product_delete.php?pd_id='+pd_id;
    }else{
        return false;
    }
}

var lat = '';
var lng = '';
var coords = '';
$(function(){
    var geocoder = new daum.maps.services.Geocoder();

    var loc = "<?php echo $view['pd_location'];?>";
    console.log(loc);
    // 주소로 좌표를 검색합니다
    geocoder.addressSearch(loc, function(result, status) {
        // 정상적으로 검색이 완료됐으면
        if (status === daum.maps.services.Status.OK) {
            console.log(status);
            lat = result[0].y;
            lng = result[0].x;
            coords = new daum.maps.LatLng(result[0].y, result[0].x);

            var mapContainer = document.getElementById('map'), // 지도를 표시할 div
                mapOption = {
                    center: new daum.maps.LatLng(lat, lng), // 지도의 중심좌표
                    level: 3 // 지도의 확대 레벨
                };

            var map = new daum.maps.Map(mapContainer, mapOption); // 지도를 생성합니다

            var imageSrc = g5_url+'/img/view_pin.svg', // 마커이미지의 주소입니다
                imageSize = new daum.maps.Size(36, 52), // 마커이미지의 크기입니다
                imageOption = {offset: new daum.maps.Point(12, 52)}; // 마커이미지의 옵션입니다. 마커의 좌표와 일치시킬 이미지 안에서의 좌표를 설정합니다.

            // 마커의 이미지정보를 가지고 있는 마커이미지를 생성합니다
            var markerImage = new daum.maps.MarkerImage(imageSrc, imageSize, imageOption),
                markerPosition = new daum.maps.LatLng(lat, lng); // 마커가 표시될 위치입니다

            // 마커를 생성합니다
            var marker = new daum.maps.Marker({
                position: markerPosition,
                image: markerImage // 마커이미지 설정
            });

            marker.setMap(map);

            map.setDraggable(false);

            // 주소-좌표 변환 객체를 생성합니다

            function searchDetailAddrFromCoords(coords,callback) {
                // 좌표로 법정동 상세 주소 정보를 요청합니다
                geocoder.coord2Address(coords.getLng(), coords.getLat(),callback);
            }

            searchDetailAddrFromCoords(markerPosition, function(result, status) {
                if (status === daum.maps.services.Status.OK) {
                    $("#addr_label").text(result[0].address.region_1depth_name+" "+result[0].address.region_2depth_name+" "+result[0].address.region_3depth_name);
                }
            });
        }
    });


});
//<![CDATA[

// // 카카오링크 버튼을 생성합니다. 처음 한번만 호출하면 됩니다.
Kakao.Link.createDefaultButton({
    container: '#kakao_link',
    objectType: 'feed',
    content: {
        title: '<?php echo $view["pd_name"];?>',
        description: '<?php echo $tagall;?>',
        imageUrl: '<?php echo G5_DATA_URL."/product/".$tagimg; ?>',
        link: {
            mobileWebUrl: g5_url+'/mobile/page/view.php?pd_id=<?php echo $pd_id; ?>',
            webUrl: g5_url+'/mobile/page/view.php?pd_id=<?php echo $pd_id; ?>'
        }
    },
    buttons: [
        {
            title: '웹으로 보기',
            link: {
                mobileWebUrl: g5_url+'/mobile/page/view.php?pd_id=<?php echo $pd_id; ?>',
                webUrl: g5_url+'/mobile/page/view.php?pd_id=<?php echo $pd_id; ?>'
            }
        }/*,
        {
            title: '앱으로 보기',
            link: {
                mobileWebUrl: 'https://developers.kakao.com',
                webUrl: 'https://developers.kakao.com'
            }
        }*/
    ]
});
//]]>

$(function(){
    $("#id03 ul.modal_sel li").each(function(){
        $(this).click(function(){
            if(!$(this).hasClass("active")){
                $(this).addClass("active");
                $("#id03 ul.modal_sel li").not($(this)).removeClass("active");
            }
        })
    })

    var clipboardSupport = true;

    try {
        $.browser.chrome = /chrom(e|ium)/.test(navigator.userAgent.toLowerCase());

        var version = $.browser.version;
        version = new Number(version.substring(0, version.indexOf(".")));

        //모바일 접속인지 확인
        if ((navigator.userAgent.match(/iPhone/i)) || (navigator.userAgent.match(/iPod/i)) || (navigator.userAgent.match(/iPad/i)) || (navigator.userAgent.match(/Android/i))) {
            //클립보드 복사기능이 될경우 (크롬 42+)
            if ($.browser.chrome == true && version >= 42) {
                clipboardSupport = true;
            } else {
                clipboardSupport = false;
            }
        }
    } catch(e) {
    }

    var clipboard = new ClipboardJS(".clipboard");

    clipboard.on("success",function(e){
        alert("링크를 복사하였습니다. \r\n원하는 곳에 공유해주세요.");
    });

    clipboard.on("error",function(e){
       console.log(e);
        alert("링크복사를 지원하지 않는 기기 또는 브라우저 입니다.")
    });


    var width = Number("<?php echo $width?>");
    col = 100 / width;
    $(".view_btns input").css("width","calc("+col+"% - 2vw)");

    <?php if($view["mb_id"]!=$member["mb_id"]){?>
    $(".likeimg").click(function(){
        var pd_id = $(this).attr("id");
        $("#like_id").val(pd_id)
        $("#id02").css({"display":"block","z-index":"9000000"});
        $("html, body").css("overflow","hidden");
        $("html, body").css("height","100vh");
        location.hash="#modal";
    })
    <?php }?>

    //채팅옵션
    $(".option").click(function(){
       $(this).toggleClass("active");
    });
});
function fnProfileMenu(){
    <?php if($profile["mb_id"]==$member["mb_id"]){?>
    location.href=g5_url+'/mobile/page/mypage/mypage.php';
    <?php }else{ ?>
    $(".profile_menu").toggleClass("active");
    <?php }?>
}

function fnProductUp(){
    $.ajax({
        url:g5_url+"/mobile/page/ajax/ajax.product_new.php",
        method:"post",
        data:{pd_id:"<?php echo $pd_id;?>"}
    }).done(function(data){
        if(data=="upcnt"){
            alert('이미 업하기 횟수(5번)를 모두 사용하였습니다.');
        }else if(data=="time"){
            alert("1시간에 한번씩 업할 수 있습니다.");
        }else if(data=="failed"){
            alert("잘못된 요청입니다. 다시 시도해 주세요.");
        }else {
            alert("정상적으로 처리되었습니다.");
        }
    });
}


function fnLike(t,id){
    var cm_id = id;
    var mb_id = "<?php echo $member["mb_id"];?>";
    var pd_id = "<?php echo $pd_id;?>";
    $.ajax({
        url:g5_url+"/mobile/page/ajax/ajax.comment.like.php",
        method:"POST",
        data:{type:t,pd_id:pd_id,cm_id:cm_id,mb_id:mb_id}
    }).done(function(data){
        console.log(data);
        if(data.indexOf('추천') != -1 || data.indexOf("반대") != -1){
            alert(data);
        }else{
            if(t=="yes"){
                $(".like"+cm_id).html(number_format(data));
            }else{
                $(".unlike"+cm_id).html(number_format(data));
            }
        }
    });
}
/*
function fnProLike(){
    $.ajax({
        url:g5_url+"/mobile/page/ajax"
    }).done(function(data){

    })
}*/

function fnBlind(pd_id,cm_id){
    $.ajax({
        url:g5_url+"/mobile/page/blind_write.php",
        method:"post",
        data:{pd_id:pd_id,type:"modal",cm_id:cm_id}
    }).done(function(data){
        $("#id01s").css({"display":"block","z-index":"9002"})
        $("#id01s .con").html('');
        $("#id01s .con").append(data);
        $("html, body").css("overflow","hidden");
        $("html, body").css("height","100vh");
        location.hash = "#blind";
    })
}

function fnMapView(pd_id,location){
    $.ajax({
        url:g5_url+"/mobile/page/view_map.php",
        method:"post",
        data:{pd_id:pd_id,location:location}
    }).done(function(data){
        console.log(data);
        $("#id01s").css({"display":"block","z-index":"9002"})
        $("html, body").css("overflow","hidden");
        $("html, body").css("height","100vh");
        $("#id01s .con").html('');
        $("#id01s .con").append(data);
        location.hash = "#blind";
    })
}

function fnRecom(cm_id,mb_id,mb_name,cm_status){
    $("#comment_re_cm_id").val(cm_id);
    $("#comment_re_mb_id").val(mb_id);
    $("#comment_re").val(1);
    $(".comment_input").attr("placeholder",mb_name+"에게 답변");
    $(".comment_input").focus();
    $(".recm_cancel").css("display","block");
    if(cm_status==3){
        $(".secret").css("display","none");
        $("#secret").attr("checked","true");
    }
   /* $.ajax({
        url:g5_url+"/mobile/page/ajax/ajax.comment_reply.php",
        method:"POST",
        data:{cm_id:cm_id,pd_id:pd_id,mb_id:mb_id}
    }).done(function(data){
        console.log(data);
    });*/
}

//간편대화 시작
//내게시글이 아닐때 이무로 게시물 작성자는 무조건 read_mb_id로 선언
var talkID = "<?php echo $view["pd_id"]?>";

function fnTalk(){
    var mb_id = "<?php echo $member["mb_id"];?>";
    if(mb_id == ""){
        alert("로그인이 필요합니다.");
        location.href=g5_bbs_url+"/login.php";
        return false;
    }
    $(".view_detail").hide();
    $(".view_bottom").hide();
    $(".DetailImage").hide();
    $(".profile_menu").removeClass("active");
    $(".view_top").css("display","block");
    $(".view_detail").css("top","100vh");
    $(".detail_arrow").stop(true).animate({top:'-60vw',opacity:1},500);
    $(".talk").show();
    var element = document.getElementById("msg_container");
    element.scrollTop = element.scrollHeight;
    //대화 갱신
    talkID = setInterval(function(){
        var pd_id = "<?php echo $view["pd_id"];?>";
        var mb_id = "<?php echo $member["mb_id"];?>";
        if(mb_id == ""){
            mb_id = "<?php echo session_id();?>";
        }
        var read_mb_id = "<?php echo $view["mb_id"];?>";
        //누적 메시지 체크
        var read_id = $("#talk_ids").val();

        $.ajax({
            url:g5_url+"/mobile/page/ajax/ajax.read_msg.php",
            method:"post",
            data:{pd_id:pd_id,mb_id:mb_id,read_mb_id:read_mb_id,read_id:read_id},
            dataType:'json'
        }).done(function(data){
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
}

function fnSendMsg(){
    var pd_id = "<?php echo $view["pd_id"];?>";
    var mb_id = "<?php echo $member["mb_id"];?>";
    if(mb_id == ""){
        mb_id = "<?php echo session_id();?>";
    }
    var read_mb_id = "<?php echo $view["mb_id"];?>";
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
function fnReadMsg(){
    var pd_id = "<?php echo $view["pd_id"];?>";
    var mb_id = "<?php echo $member["mb_id"];?>";
    if(mb_id == ""){
        mb_id = "<?php echo session_id();?>";
    }
    var read_mb_id = "<?php echo $view["mb_id"];?>";
    //누적 메시지 체크
    var read_id = $("#talk_ids").val();

    $.ajax({
        url:g5_url+"/mobile/page/ajax/ajax.read_msg.php",
        method:"post",
        data:{pd_id:pd_id,mb_id:mb_id,read_mb_id:read_mb_id,read_id:read_id},
        dataType:'json'
    }).done(function(data){
        if(data.msg.length > 1) {
            for (var i = 0; i < data.msg.length; i++) {
                console.log(data.msg[i]);
                $(".msg_container").append(data.msg[i]);
            }
        }
        $("#talk_ids").val(data.ids);
    });
}

</script>
<?php
//include_once(G5_MOBILE_PATH."/tail.view.php");
?>
