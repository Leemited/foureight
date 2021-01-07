<?php
include_once ("../../../common.php");

if($pd_id=="" || !$pd_id){
    echo "0";
    return false;
}

if($comment == "" || !$comment){
    echo "1";
    return false;
}

if($mb_id=="" || !$mb_id){
    echo "2";
    return false;
}

if($comment_re != "1") {
    /*if($_SESSION["cm_time"]) {
        $delay = date("i") - $_SESSION["cm_time"];
        if($delay <= 0){
            echo "너무 빠른시간에 연속해서 올릴 수 없습니다.";
            return false;
        }
    }
    $_SESSION["cm_time"] = date("i");*/
    $sql = "insert into `product_comment` set pd_id='{$pd_id}',parent_cm_id = '', comment_re = '', comment_content = '{$comment}',comment_status = '{$secret}', comment_datetime = now(), comment_price = '', mb_id = '{$mb_id}', re_pd_id = ''";
    if (sql_query($sql)) {

        $sql = "select *,m.mb_id as mb_id from `product_comment` as p left join `g5_member` as m on m.mb_id = p.mb_id where p.pd_id ='{$pd_id}' order by p.comment_datetime desc limit 0 , 1";
        $cm = sql_fetch($sql);

        $sql = "select *,p.mb_id as mb_id from `product` as p left join `g5_member` as m on p.mb_id = m.mb_id where p.pd_id = '{$pd_id}'";
        $pd = sql_fetch($sql);
        if($pd["pd_images"]) {
            $imgs = explode(",",$pd["pd_images"]);
            $img = G5_DATA_URL."/product/".$imgs[0];
        }
        //게시자에게 알림 보내기
        if($pd["mb_id"]!=$mb_id) {
            //send_FCM('fcmid','타이틀','내용','유알엘','체널','체널명','받는아이디','게시글번호','이미지');
            send_FCM($pd["regid"], "48 댓글 알림", cut_str($pd["pd_tag"],10,"...")."에 새 댓글이 등록되었습니다.\r\n빠른 답글은 상호 신뢰의 기준입니다.\r\n비도덕적인 댓글은 반드시 신고바랍니다.", G5_URL . "/index.php?pd_id=" . $pd_id.'&detail=true', "comment_alarm_set", "댓글알림", $pd["mb_id"], $pd_id, $img);
        }
        ?>
        <li class="<?php if($pd_mb_id!=$mb_id && $cm["mb_id"] != $mb_id){if($cm["comment_status"]=="3" || $secret == "3"){echo "cm_lock ";} } ?>" id="cmt<?php echo $cm[cm_id];?>">
            <div class="coms">
                <?php if($pd_mb_id!=$mb_id && $cm["mb_id"] != $mb_id){if($cm["comment_status"]=="3"  || $secret == "3" ){?>
                    <p>비공개 / <?php echo $cm["comment_datetime"];?></p>
                    <h2 class="loctitle">비공개</h2>
                    <ul><li>비공개</li></ul>
                <?php }else{ ?>
                    <p><?php echo $cm["mb_nick"];?> <span>/ <?php echo display_datetime($cm["comment_datetime"]);?></span></p>
                    <h2><?php echo nl2br($cm["comment_content"]);?></h2>
                    <ul>
                        <!--<li><img src="<?php /*echo G5_IMG_URL*/?>/ic_comment_blind.png" alt="" onclick="fnBlind('<?php /*echo $pd_mb_id;*/?>','<?php /*echo $cm["cm_id"];*/?>');">신고</li>
                        <?php /*if($cm["comment_re"]== 0){*/?>
                            <li>댓글 <span><?php /*echo number_format($cm["re_comment_cnt"]);*/?></span></li>
                        <?php /*}*/?>
                        <?php /*if($cm["mb_id"]!=$mb_id){*/?>
                            <li onclick="fnLike('no','<?php /*echo $cm["cm_id"];*/?>')">반대 <span class="unlike<?php /*echo $cm["cm_id"];*/?>"><?php /*echo number_format($cm["unlike"]);*/?></span></li>
                            <li onclick="fnLike('yes','<?php /*echo $cm["cm_id"];*/?>')">추천 <span class="like<?php /*echo $cm["cm_id"];*/?>"><?php /*echo number_format($cm["like"]);*/?></span></li>
                        --><?php /*}  */?>
                    </ul>
                <?php }
                }else{ ?>
                    <p><?php echo $cm["mb_nick"];?> <span>/ <?php echo display_datetime($cm["comment_datetime"]);?></span></p>
                    <h2><?php echo nl2br($cm["comment_content"]);?></h2>
                    <ul>
                        <!--<li><img src="<?php /*echo G5_IMG_URL*/?>/ic_comment_blind.png" alt="" onclick="fnBlind('<?php /*echo $pd_mb_id;*/?>','<?php /*echo $cm["cm_id"];*/?>');">신고</li>
                        <?php /*if($cm["comment_re"]== 0){*/?>
                            <li>댓글 <span><?php /*echo number_format($cm["re_comment_cnt"]);*/?></span></li>
                        <?php /*}*/?>
                        <?php /*if($cm["mb_id"]!=$mb_id){*/?>
                            <li onclick="fnLike('no','<?php /*echo $cm["cm_id"];*/?>')">반대 <span class="unlike<?php /*echo $cm["cm_id"];*/?>"><?php /*echo number_format($cm["unlike"]);*/?></span></li>
                            <li onclick="fnLike('yes','<?php /*echo $cm["cm_id"];*/?>')">추천 <span class="like<?php /*echo $cm["cm_id"];*/?>"><?php /*echo number_format($cm["like"]);*/?></span></li>
                        --><?php /*}*/?>
                    </ul>
                <?php }?>
            </div>
        </li>
        <?php
    } else {
        echo "3";
    }
}else{
    // $mb_id = 댓글등록자
    // $$comment_re_mb_id = 답글 대상자
    // $pd_id = 게시물 번호

    $sql = "update `product_comment` set re_comment_cnt = re_comment_cnt + 1 where cm_id = {$comment_re_cm_id}";
    sql_query($sql);
    $sql = "insert into `product_comment` set pd_id='{$pd_id}',parent_cm_id = '{$comment_re_cm_id}', comment_re = '{$comment_re}', comment_content = '{$comment}',comment_status = '{$secret}', comment_datetime = now(), comment_price = '', mb_id = '{$mb_id}', re_pd_id = ''";
    if (sql_query($sql)) {
        $sql = "select * from `product_comment` as p left join `g5_member` as m on m.mb_id = p.mb_id where p.pd_id ='{$pd_id}' order by p.comment_datetime desc limit 0 , 1";
        $cm = sql_fetch($sql);

        $sql = "select *,p.mb_id as mb_id from `product` as p left join `g5_member` as m on p.mb_id = m.mb_id where p.pd_id = '{$pd_id}'";
        $pd = sql_fetch($sql);
        if($pd["pd_images"]) {
            $imgs = explode(",",$pd["pd_images"]);
            $img = G5_DATA_URL."/product/".$imgs[0];
        }

        $mb = get_member($comment_re_mb_id);//답글 대상자

        //댓글 작성자에게 알림 보내기
        //send_FCM('fcmid','타이틀','내용','유알엘','체널','체널명','받는아이디','게시글번호','이미지');
        send_FCM($mb["regid"], "48 댓글 알림", cut_str($pd["pd_tag"], 10, "...") . "에 답글이 등록되었습니다.\r\n게시물에서 답글을 확인해보세요.\r\n비도덕적인 댓글은 반드시 신고바랍니다.", G5_URL . "/index.php?pd_id=" . $pd_id . '&detail=true', "comment_alarm_set", "댓글알림", $mb["mb_id"], $pd_id, $img);


        ?>
        <li class="re_cm <?php if($pd_mb_id!=$mb_id && $cm["mb_id"] != $mb_id){if($cm["comment_status"]=="3" || $secret == "3"){echo "cm_lock ";} }?> " id="cmt<?php echo $cm[cm_id];?>">
            <!--<div class="profile" <?php /*if($cm["member_id"] != $mb_id){ echo "onclick=fnRecom('".$cm["cm_id"]."','".$mb_id."','".$member["mb_name"]."','".$cm["comment_status"]."')";} */?> >
                <?php /*if($pd_mb_id!=$mb_id){
                    if($cm["comment_status"]=="3"  || $secret == "3"){ */?>
                        <img src="<?php /*echo G5_IMG_URL*/?>/profile_lock.svg" alt="" id="profile">
                    <?php /*}else if($cm["mb_profile"]){*/?>
                        <img src="<?php /*echo $cm["mb_profile"];*/?>" alt="" id="profile">
                    <?php /*}else if($cm["mb_profile"] ==""){ */?>
                        <img src="<?php /*echo G5_IMG_URL*/?>/no-profile.svg" alt="">
                    <?php /*}
                }else {
                    if ($cm["mb_profile"]) { */?>
                        <img src="<?php /*echo $cm["mb_profile"]; */?>" alt="" id="profile">
                    <?php /*} else if ($cm["mb_profile"] == "") { */?>
                        <img src="<?php /*echo G5_IMG_URL */?>/no-profile.svg" alt="">
                    <?php /*}
                }*/?>
            </div>-->
            <div class="coms">
                <?php if($pd_mb_id!=$mb_id && $cm["mb_id"] != $mb_id ){if($cm["comment_status"]=="3"  || $secret == "3" ){?>
                    <p>비공개 <span>/ <?php echo display_datetime($cm["comment_datetime"]);?></span></p>
                    <h2 class="loctitle">비공개</h2>
                    <ul><li>비공개</li></ul>
                <?php }else{ ?>
                    <p><?php echo $cm["mb_nick"];?> <span>/ <?php echo $cm["comment_datetime"];?></span></p>
                    <h2><?php echo nl2br($cm["comment_content"]);?></h2>
                    <ul>
                        <!--<li><img src="<?php /*echo G5_IMG_URL*/?>/ic_comment_blind.png" alt="" onclick="fnBlind('<?php /*echo $pd_mb_id;*/?>','<?php /*echo $cm["cm_id"];*/?>');">신고</li>
                        <?php /*if($cm["comment_re"]== 0){*/?>
                            <li>댓글 <span><?php /*echo number_format($cm["re_comment_cnt"]);*/?></span></li>
                        <?php /*}*/?>
                        <?php /*if($cm["mb_id"]!=$mb_id){*/?>
                            <li onclick="fnLike('no','<?php /*echo $cm["cm_id"];*/?>')">반대 <span class="unlike<?php /*echo $cm["cm_id"];*/?>"><?php /*echo number_format($cm["unlike"]);*/?></span></li>
                            <li onclick="fnLike('yes','<?php /*echo $cm["cm_id"];*/?>')">추천 <span class="like<?php /*echo $cm["cm_id"];*/?>"><?php /*echo number_format($cm["like"]);*/?></span></li>
                        --><?php /*}  */?>
                    </ul>
                <?php }}else{ ?>
                    <p><?php echo $cm["mb_nick"];?> <span>/ <?php echo display_datetime($cm["comment_datetime"]);?></span></p>
                    <h2><?php echo nl2br($cm["comment_content"]);?></h2>
                    <ul>
                        <!--<li><img src="<?php /*echo G5_IMG_URL*/?>/ic_comment_blind.png" alt="" onclick="fnBlind('<?php /*echo $pd_mb_id;*/?>','<?php /*echo $cm["cm_id"];*/?>');">신고</li>
                        <?php /*if($cm["comment_re"]== 0){*/?>
                            <li>댓글 <span><?php /*echo number_format($cm["re_comment_cnt"]);*/?></span></li>
                        <?php /*}*/?>
                        <?php /*if($cm["mb_id"]!=$mb_id){*/?>
                            <li onclick="fnLike('no','<?php /*echo $cm["cm_id"];*/?>')">반대 <span class="unlike<?php /*echo $cm["cm_id"];*/?>"><?php /*echo number_format($cm["unlike"]);*/?></span></li>
                            <li onclick="fnLike('yes','<?php /*echo $cm["cm_id"];*/?>')">추천 <span class="like<?php /*echo $cm["cm_id"];*/?>"><?php /*echo number_format($cm["like"]);*/?></span></li>
                        --><?php /*}*/?>
                    </ul>
                <?php }?>
            </div>
        </li>
        <?php
    } else {
        echo "3";
    }
}

?>
