<?php
include_once ("../../../common.php");
/**
 * User: leemited
 * Date: 2018-10-16
 * Time: 오전 10:03
 */
$data = array();
//프로필 세팅
$mb = get_member($read_mb_id);

$sql = "select * from `product_chat` where pd_id = {$pd_id} and room_id = '{$roomid}' and id not in ($read_id) order by msg_datetime asc ";
$data["sql"] = $sql;
$res = sql_query($sql);
while($row = sql_fetch_array($res)){
    $talk[] = $row;
}
//받는 사람 아이디
$data['ids'] = $read_id;
$today = date("Y-m-d");
for($i=0;$i<count($talk);$i++){
    if($talk[$i]["msg_date"] == $today){
        $date = (date("a",strtotime($talk[$i]["msg_time"]))=="am")?"오전":"오후";
        $date .= " ".substr($talk[$i]["msg_time"],0,5);
    }else{
        $ampm = (date("a",strtotime($talk[$i]["msg_time"]))=="am")?"오전":"오후";
        $date = $talk[$i]["msg_date"]."<br>".$ampm." ".substr($talk[$i]["msg_time"],0,5);
    }
    //보낸사람 과 내아이디 체크
    if($mb_id == $talk[$i]["send_mb_id"]) {
        $data["msg"][] =
            "<div class='msg_box my_msg'>" .
                "<div class='in_box'>" .
                    "<div class='date'>".$date."</div>" .
                    "<div class='msg'>".nl2br(str_replace(" ", "&nbsp;",$talk[$i]["message"]))."</div>" .
                    "<div class='arrow'><img src='".G5_IMG_URL."/ic_chat.png' alt=''></div>".
                "</div>" .
            "</div>";
    }else{
        if($mb['mb_profile']){
            $background = "background-image:url(".$mb["mb_profile"].")";
        }else{
            $background = "background-image:url(".G5_IMG_URL."/no-profile.svg".")";
        }
        if($mb["mb_nick"]){
            $nick = $mb["mb_nick"];
        }else{
            $nick = "닉네임정보 없음";
        }
        $data["msg"][] =
            "<div class='msg_box read_msg'>" .
                "<div class='in_box'>" .
                    "<div class='read_profile' style='position:relative;".$background.";background-size:cover;background-repeat:no-repeat;background-position:center;width:13vw;height:13vw;-webkit-box-shadow: 0 0 2vw RGBA(0,0,0,0.3);-moz-box-shadow: 0 0 2vw RGBA(0,0,0,0.3);box-shadow: 0 0 2vw RGBA(0,0,0,0.3);border-radius: 50%;border: 3px solid #fff;'></div>" .
                    "<div class='box_con'>" .
                        "<div class='read_name'>".$nick."</div>" .
                        "<div class='msg'>".nl2br(str_replace(" ", "&nbsp;",$talk[$i]["message"]))."</div>" .
                        "<div class='date'>".$date."</div>" .
                        //"<div class='arrow'><img src='".G5_IMG_URL."/ic_chat_reply.png'></div>" .
                    "</div>" .
                "</div>" .
                "<div class='clear'></div>" .
            "</div>";
    }
    $read_id .= ",".$talk[$i]["id"];
    $data["ids"] = $read_id;
}
$data["cnt"] = count($talk);
echo json_encode($data);
?>