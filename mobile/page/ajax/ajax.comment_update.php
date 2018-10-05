<?php
include_once ("../../../common.php");

if($pd_id=="" || !$pd_id){
    echo "잘못된 요청입니다.";
    return false;
}

if($comment == "" || !$comment){
    echo "댓글 내용을 입력해주세요";
    return false;
}

if($mb_id=="" || !$mb_id){
    echo "로그인이 필요합니다.";
    return false;
}

if($comment_re == "") {

    /*if($_SESSION["cm_time"]) {
        $delay = date("i") - $_SESSION["cm_time"];
        if($delay <= 0){
            echo "너무 빠른시간에 연속해서 올릴 수 없습니다.";
            return false;
        }
    }
    $_SESSION["cm_time"] = date("i");*/
    $sql = "insert into `product_comment` set pd_id='{$pd_id}',parent_cm_id = '', comment_re = '', comment_content = '{$comment}',comment_status = '{$secret}', comment_datetime = now(), comment_price = '', mb_id = '{$mb_id}', re_pd_id = ''";
    echo $sql;
    if (sql_query($sql)) {
        $sql = "select * from `product_comment` where pd_id ='{$pd_id}' and mb_id = '{$mb_id}' order by comment_datetime desc limit 0 , 1";
        $cm = sql_fetch($sql);
        ?>
        <li class="<?php if ($cm[$i]["comment_status"] == "3") {
            echo "cm_lock ";
        } ?>" id="cmt<?php echo $cm[$i][cm_id]; ?>">
            <div class="profile">
                <?php if ($cm[$i]["mb_profile"]) { ?>
                    <img src="<?php echo $cm[$i]["mb_profile"]; ?>" alt="" id="profile">
                <?php } else if ($cm[$i]["mb_profile"] == "") { ?>
                    <img src="<?php echo G5_IMG_URL ?>/no-profile.svg" alt="">
                <?php } else if ($cm[$i]["cm_status"] == "3") { ?>
                    <img src="<?php echo G5_IMG_URL ?>/profile_lock.svg" alt="" id="profile">
                <?php } ?>
            </div>
            <div class="coms">
                <p><?php echo $cm[$i]["mb_name"]; ?> / <?php echo $cm[$i]["cm_datetime"]; ?></p>
                <h2><?php echo $cm[$i]["cm_content"]; ?></h2>
                <ul>
                    <li><img src="<?php echo G5_IMG_URL ?>/ic_cm_blind.png" alt=""
                             onclick="fnComBlind('<?php echo $cm[$i]["cm_id"]; ?>');">신고
                    </li>
                    <?php if ($cm[$i]["cm_re"] == 0) { ?>
                        <li>댓글 <span><?php echo number_format($cm[$i]["re_cm_cnt"]); ?></span></li>
                    <?php } ?>
                    <li onclick="fnLike('no','<?php echo $cm[$i]["cm_id"]; ?>')">반대 <span
                                class="unlike<?php echo $cm[$i]["cm_id"]; ?>"><?php echo number_format($cm[$i]["unlike"]); ?></span>
                    </li>
                    <li onclick="fnLike('yes','<?php echo $cm[$i]["cm_id"]; ?>')">추천 <span
                                class="like<?php echo $cm[$i]["cm_id"]; ?>"><?php echo number_format($cm[$i]["like"]); ?></span>
                    </li>
                </ul>
            </div>
        </li>
        <?php
    } else {
        echo "2";
    }
}else{

}

?>
