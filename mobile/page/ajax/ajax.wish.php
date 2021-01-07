<?php
include_once("../../../common.php");
$mb_id = $_REQUEST["mb_id"];
if($mode=="insert"){
	if($mb_id){
	    $sql = "select * from `product` where pd_id = '{$pd_id}'";
	    $mypro = sql_fetch($sql);
	    if($mb_id==$mypro["mb_id"]){
            echo "myproduct";
            return false;
        }
		$now = date("Y-m-d");
		$recent = sql_query("select * from `wish_product` where mb_id = '{$mb_id}' and pd_id = '{$pd_id}' and ws_date = '{$now}'");
		$cnt = 0;
		while($row = sql_fetch_array($recent)){
			$cnt++;
		}
		if($cnt==0){
			$sql = "insert into `wish_product` (mb_id,pd_id,ws_date)values('{$mb_id}','{$pd_id}','{$now}');";
			if(sql_query($sql)){
                $img = "";
                if ($mypro["pd_images"]) {
                    $imgs = explode(",", $mypro["pd_images"]);
                    $img = G5_DATA_URL . "/product/" . $imgs[0];
                }
                $mbs = get_member($mypro["mb_id"]);
                if($mbs["regid"]) {
                    send_FCM($mbs["regid"], "48 알림", $member["mb_nick"] . "님이 " . cut_str($mypro["pd_tag"], 10, "...") . "의 게시글에 찜을 하셨습니다.^^", G5_URL . "/?pd_id=" . $pd_id, "notice_alarm_set", "기본알림", $mbs["mb_id"], $pd_id, $img, "","");
                }
				echo "ok query";
			}else{
				echo "not ok";
			}
		}
	}
}else if($mode=="delete"){
	if($mb_id){
		$sql = "delete from `wish_product` where mb_id='{$mb_id}' and pd_id = '{$pd_id}'";	
		if(sql_query($sql)){
			echo "delete query";
		}else{
			echo "not delete";
		}
	}else{
		$_SESSION["wr_pd_id"]=str_replace(",,","",str_replace($pd_id,"",$_SESSION["ws_pd_id"]));
		echo "delete session";
	}
}

?>