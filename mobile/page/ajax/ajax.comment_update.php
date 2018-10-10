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
        $sql = "select * from `product_comment` as p left join `g5_member` as m on m.mb_id = p.mb_id where p.pd_id ='{$pd_id}' order by p.comment_datetime desc limit 0 , 1";
        $cm = sql_fetch($sql);
        ?>
        <li class="<?php if($pd_mb_id!=$mb_id){if($cm["comment_status"]=="3" || $secret == "3"){echo "cm_lock ";} } ?>" id="cmt<?php echo $cm[cm_id];?>">
            <div class="profile" <?php if($cm["member_id"] != $mb_id){ echo "onclick=fnRecom('".$cm["cm_id"]."','".$mb_id."','".$member["mb_name"]."','".$cm["comment_status"]."')";} ?> >
                <?php if($pd_mb_id!=$mb_id){
                    if($cm["comment_status"]=="3"  || $secret == "3"){ ?>
                    <img src="<?php echo G5_IMG_URL?>/profile_lock.svg" alt="" id="profile">
                <?php }else if($cm["mb_profile"]){?>
                    <img src="<?php echo $cm["mb_profile"];?>" alt="" id="profile">
                <?php }else if($cm["mb_profile"] ==""){ ?>
                    <img src="<?php echo G5_IMG_URL?>/no-profile.svg" alt="">
                <?php }
                }else {
                    if ($cm["mb_profile"]) { ?>
                    <img src="<?php echo $cm["mb_profile"]; ?>" alt="" id="profile">
                <?php } else if ($cm["mb_profile"] == "") { ?>
                    <img src="<?php echo G5_IMG_URL ?>/no-profile.svg" alt="">
                <?php }
                }?>
            </div>
            <div class="coms">
                <p><?php echo $cm["mb_name"];?> / <?php echo $cm["comment_datetime"];?></p>
                <?php if($pd_mb_id!=$mb_id){if($cm["comment_status"]=="3"  || $secret == "3" ){?>
                    <h2 class="loctitle">비공개</h2>
                <?php }else{ ?>
                    <h2><?php echo $cm["comment_content"];?></h2>
                    <ul>
                        <li><img src="<?php echo G5_IMG_URL?>/ic_comment_blind.png" alt="" onclick="fnBlind('<?php echo $pd_mb_id;?>','<?php echo $cm["cm_id"];?>');">신고</li>
                        <?php if($cm["comment_re"]== 0){?>
                            <li>댓글 <span><?php echo number_format($cm["re_comment_cnt"]);?></span></li>
                        <?php }?>
                        <?php if($cm["mb_id"]!=$mb_id){?>
                            <li onclick="fnLike('no','<?php echo $cm["cm_id"];?>')">반대 <span class="unlike<?php echo $cm["cm_id"];?>"><?php echo number_format($cm["unlike"]);?></span></li>
                            <li onclick="fnLike('yes','<?php echo $cm["cm_id"];?>')">추천 <span class="like<?php echo $cm["cm_id"];?>"><?php echo number_format($cm["like"]);?></span></li>
                        <?php }  ?>
                    </ul>
                <?php }}else{ ?>
                    <h2><?php echo $cm["comment_content"];?></h2>
                    <ul>
                        <li><img src="<?php echo G5_IMG_URL?>/ic_comment_blind.png" alt="" onclick="fnBlind('<?php echo $pd_mb_id;?>','<?php echo $cm["cm_id"];?>');">신고</li>
                        <?php if($cm["comment_re"]== 0){?>
                            <li>댓글 <span><?php echo number_format($cm["re_comment_cnt"]);?></span></li>
                        <?php }?>
                        <?php if($cm["mb_id"]!=$mb_id){?>
                            <li onclick="fnLike('no','<?php echo $cm["cm_id"];?>')">반대 <span class="unlike<?php echo $cm["cm_id"];?>"><?php echo number_format($cm["unlike"]);?></span></li>
                            <li onclick="fnLike('yes','<?php echo $cm["cm_id"];?>')">추천 <span class="like<?php echo $cm["cm_id"];?>"><?php echo number_format($cm["like"]);?></span></li>
                        <?php }?>
                    </ul>
                <?php }?>
            </div>
        </li>
        <?php
    } else {
        echo "3";
    }
}else{
    $sql = "update `product_comment` set re_comment_cnt = re_comment_cnt + 1 where cm_id = {$comment_re_cm_id}";
    sql_query($sql);
    $sql = "insert into `product_comment` set pd_id='{$pd_id}',parent_cm_id = '{$comment_re_cm_id}', comment_re = '{$comment_re}', comment_content = '{$comment}',comment_status = '{$secret}', comment_datetime = now(), comment_price = '', mb_id = '{$mb_id}', re_pd_id = ''";
    if (sql_query($sql)) {
        $sql = "select * from `product_comment` as p left join `g5_member` as m on m.mb_id = p.mb_id where p.pd_id ='{$pd_id}' order by p.comment_datetime desc limit 0 , 1";
        $cm = sql_fetch($sql);
        ?>
        <li class="re_cm <?php if($pd_mb_id!=$mb_id){if($cm["comment_status"]=="3" || $secret == "3"){echo "cm_lock ";} }?> " id="cmt<?php echo $cm[cm_id];?>">
            <div class="profile" <?php if($cm["member_id"] != $mb_id){ echo "onclick=fnRecom('".$cm["cm_id"]."','".$mb_id."','".$member["mb_name"]."','".$cm["comment_status"]."')";} ?> >
                <?php if($pd_mb_id!=$mb_id){
                    if($cm["comment_status"]=="3"  || $secret == "3"){ ?>
                        <img src="<?php echo G5_IMG_URL?>/profile_lock.svg" alt="" id="profile">
                    <?php }else if($cm["mb_profile"]){?>
                        <img src="<?php echo $cm["mb_profile"];?>" alt="" id="profile">
                    <?php }else if($cm["mb_profile"] ==""){ ?>
                        <img src="<?php echo G5_IMG_URL?>/no-profile.svg" alt="">
                    <?php }
                }else {
                    if ($cm["mb_profile"]) { ?>
                        <img src="<?php echo $cm["mb_profile"]; ?>" alt="" id="profile">
                    <?php } else if ($cm["mb_profile"] == "") { ?>
                        <img src="<?php echo G5_IMG_URL ?>/no-profile.svg" alt="">
                    <?php }
                }?>
            </div>
            <div class="coms">
                <p><?php echo $cm["mb_name"];?> / <?php echo $cm["comment_datetime"];?></p>
                <?php if($pd_mb_id!=$mb_id){if($cm["comment_status"]=="3"  || $secret == "3" ){?>
                    <h2 class="loctitle">비공개</h2>
                <?php }else{ ?>
                    <h2><?php echo $cm["comment_content"];?></h2>
                    <ul>
                        <li><img src="<?php echo G5_IMG_URL?>/ic_comment_blind.png" alt="" onclick="fnBlind('<?php echo $pd_mb_id;?>','<?php echo $cm["cm_id"];?>');">신고</li>
                        <?php if($cm["comment_re"]== 0){?>
                            <li>댓글 <span><?php echo number_format($cm["re_comment_cnt"]);?></span></li>
                        <?php }?>
                        <?php if($cm["mb_id"]!=$mb_id){?>
                            <li onclick="fnLike('no','<?php echo $cm["cm_id"];?>')">반대 <span class="unlike<?php echo $cm["cm_id"];?>"><?php echo number_format($cm["unlike"]);?></span></li>
                            <li onclick="fnLike('yes','<?php echo $cm["cm_id"];?>')">추천 <span class="like<?php echo $cm["cm_id"];?>"><?php echo number_format($cm["like"]);?></span></li>
                        <?php }  ?>
                    </ul>
                <?php }}else{ ?>
                    <h2><?php echo $cm["comment_content"];?></h2>
                    <ul>
                        <li><img src="<?php echo G5_IMG_URL?>/ic_comment_blind.png" alt="" onclick="fnBlind('<?php echo $pd_mb_id;?>','<?php echo $cm["cm_id"];?>');">신고</li>
                        <?php if($cm["comment_re"]== 0){?>
                            <li>댓글 <span><?php echo number_format($cm["re_comment_cnt"]);?></span></li>
                        <?php }?>
                        <?php if($cm["mb_id"]!=$mb_id){?>
                            <li onclick="fnLike('no','<?php echo $cm["cm_id"];?>')">반대 <span class="unlike<?php echo $cm["cm_id"];?>"><?php echo number_format($cm["unlike"]);?></span></li>
                            <li onclick="fnLike('yes','<?php echo $cm["cm_id"];?>')">추천 <span class="like<?php echo $cm["cm_id"];?>"><?php echo number_format($cm["like"]);?></span></li>
                        <?php }?>
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
