<?php
include_once ("../../../common.php");

if($type == "yes"){
    $sql = "select count(*) as cnt, like_type from `product_comment_like` where mb_id = '{$mb_id}' and pd_id = '{$pd_id}' and cm_id = '{$cm_id}'";
    $cm_chk = sql_fetch($sql);
    if($cm_chk["cnt"] > 0){
        if($cm_chk["like_type"]=="like"){
            $like_type = "추천";
        }else{
            $like_type = "반대";
        }
        echo "이미 {$like_type}한 댓글입니다.";
        return false;
    }else{
        $sql = "insert into `product_comment_like` set mb_id = '{$mb_id}', pd_id='{$pd_id}', cm_id='{$cm_id}', like_type = 'like', like_datetime = now()";
        if(!sql_query($sql)){
            echo "추천정보를 저장하지 못하였습니다.";
            return false;
        }
        $sql = "update `product_comment` set `like` = `like` + 1 where cm_id = '{$cm_id}'";
        if(!sql_query($sql)){
            echo "댓글 추천정보를 업데이트 하지 못하였습니다.";
            return false;
        }else{
            $sql = "select * from `product_comment` where cm_id = '{$cm_id}'";
            $cnt = sql_fetch($sql);
            echo $cnt["like"];
        }
    }
}else if($type == "no"){
    $sql = "select count(*) as cnt, like_type from `product_comment_like` where mb_id = '{$mb_id}' and pd_id = '{$pd_id}' and cm_id = '{$cm_id}'";
    $cm_chk = sql_fetch($sql);
    if($cm_chk["cnt"] > 0){
        if($cm_chk["like_type"]=="like"){
            $like_type = "추천";
        }else{
            $like_type = "반대";
        }
        echo "이미 {$like_type}한 댓글입니다.";
        return false;
    }else{
        $sql = "insert into `product_comment_like` set mb_id = '{$mb_id}', pd_id='{$pd_id}', cm_id='{$cm_id}', like_type = 'unlike', like_datetime = now()";
        if(!sql_query($sql)){
            echo "반대정보를 저장하지 못하였습니다.";
            return false;
        }
        $sql = "update `product_comment` set `unlike` = `unlike` + 1 where cm_id = '{$cm_id}'";
        if(!sql_query($sql)){
            echo "댓글 반대정보를 업데이트 하지 못하였습니다.";
            return false;
        }else{
            $sql = "select * from `product_comment` where cm_id = '{$cm_id}'";
            $cnt = sql_fetch($sql);
            echo $cnt["unlike"];
        }
    }

}