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

if($cm_id == "" || !$cm_id){
    echo "답변할 댓글이 선택되지 않았습니다.";
    return false;
}

