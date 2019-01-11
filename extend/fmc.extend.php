<?php
function send_FCM($reg_id,$title,$content,$urls,$chennal,$chennalname,$mb_id,$pd_id='',$imgurls=''){
    //대화저장일경우 중복알림 저장이 아니라 업데이트 되어야 함
    //알림 저장
    if($chennal == "chat_alarm_set"){
        $sql = "select * from `my_alarms` where pd_id = '{$pd_id}' and mb_id = '{$mb_id}'";
        $id = sql_fetch($sql);
        if($id["id"]==''){
            $sql = "insert into `my_alarms` set mb_id = '{$mb_id}', pd_id='{$pd_id}', alarm_type='{$chennalname}', alarm_title = '{$title}', alarm_content = '{$content}', alarm_link = '{$urls}',alarm_date = now(), alarm_time = now(), alarm_status = 0";
            sql_query($sql);
        }else{
            $sql = "update `my_alarms` set mb_id = '{$mb_id}', pd_id='{$pd_id}', alarm_type='{$chennalname}', alarm_title = '{$title}', alarm_content = '{$content}', alarm_link = '{$urls}',alarm_date = now(), alarm_time = now(), alarm_status = 0 where id = '{$id[id]}'";
            sql_query($sql);
        }
    }else{
        $sql = "insert into `my_alarms` set mb_id = '{$mb_id}', pd_id='{$pd_id}', alarm_type='{$chennalname}', alarm_title = '{$title}', alarm_content = '{$content}', alarm_link = '{$urls}',alarm_date = now(), alarm_time = now(), alarm_status = 0";
        sql_query($sql);
    }

    $sql = "select * from `mysetting` where mb_id = '{$mb_id}'";
    $set = sql_fetch($sql);
    if($set["push_set"] == 0) return false;

    //현재 시간 체크 [에티켓설정시]
    if($set["etiquette_set"]==1){
        $now = date("H:i:s");
        $start = date("H:i:s",strtotime($set["etiquette_time_start"].":00:00"));
        $end = date("H:i:s",strtotime($set["etiquette_time_end"].":00:00"));

        if(strtotime($now) < strtotime($start) && strtotime($now) > strtotime($end)){
            return false;
        }
    }
    //설정 대입
    if($set[$chennal] == 0){
        return false;
    }

    $mbs = get_member($mb_id);

    $apiKey = "AAAATdHUVhc:APA91bHBoGTQnwcHrTgeBbZJaF6dz9TQ2EsMSayHCbsJntos5kqxwF9RT5ujrwfSe8mXZcbIlhKAUEuuYGNV1TDqKtixh08m6HSwjVNIWEZGA9meaJ1kMjs3VuyIn5qp0-pri79r0ql9";
    if(is_array($reg_id)){
        $regId_array = $reg_id;
    }else {
        $regId_array = array($reg_id);
    }
    $url = 'https://fcm.googleapis.com/fcm/send';
    if($mbs["sdkVersion"] == "ios"){
        $fields = array(
            'registration_ids' => $regId_array,
            'priority' => 'high',
            'notification' => array("title" => $title, "body" => $content, "urls" => $urls, "chennal" => $chennal, "channelname" => $chennalname, "imgurlstr" => $imgurls)
        );
    }else {
        $fields = array(
            'registration_ids' => $regId_array,
            'data' => array("title" => $title, "message" => $content, "content_available" => 'true', "urls" => $urls, "chennal" => $chennal, "channelname" => $chennalname, "imgurlstr" => $imgurls),
            'priority' => 'high',
            'sound' => 'default'
        );
    }
    $headers = array(
        'Authorization: key='.$apiKey,
        'Content-Type: application/json'
    );
    $ch = curl_init();
    // Set the URL, number of POST vars, POST data
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
    // Execute post
    $result = curl_exec($ch);
    // Close connection
    curl_close($ch);
    $decode = json_decode($result, true);

    return $result;
}
//전체 보내기
function send_reserve_FCM($title,$content,$urls){
    $apiKey = "AAAAivnhn8g:APA91bF80E_Eqt7LWly5fqYDME_UmsQQDx_nhoiyQtLUo0iwNqUX4cPoaHIStyeUXb3RLT-PckzQyCGS5VZqSw-r6l-NfARyFdH2gV82481_DDmGp9yZUk93JBilVyIoJdrFurdjnErxBCQI--LywGanG7dHur8jhg";
    $sql = "select regid from `g5_member` WHERE regid<>'' and mb_id = 'admin' ;";
    $rs = sql_query($sql);
    //$num = mysql_num_rows($rs);
    for($i=0;$row=sql_fetch_array($rs);$i++){
        $regid[$i] = $row["regid"];
    }
    $url = 'https://fcm.googleapis.com/fcm/send';
    $headers = array(
        'Authorization: key='.$apiKey,
        'Content-Type: application/json'
    );
    $result = "";
    $arr = array();
    $regiSize = round(sizeof($regid)/1000);
    if($regiSize==0){
        $regiSize=1;
    }
    for($i=0;$i<$regiSize;$i++){
        $arr[$i] = array();
        $arr[$i]['data'] = array();
        $arr[$i]['data']['title'] = $title;
        $arr[$i]['data']['message'] = $content;
        $arr[$i]['data']['content_available'] = "true";
        $arr[$i]['data']['urls'] = $urls;
        $arr[$i]['priority'] = 'high';
        $arr[$i]['registration_ids'] = array();
        $size = sizeof($regid);
        if($size > 1000){
            for($j=0;$j<1000;$j++){
                $arr[$i]['registration_ids'][$j] = $regid[$j];
            }
            $regid = array_splice($regid, 1000);
        }else{
            for($j=0;$j<$size;$j++){
                $arr[$i]['registration_ids'][$j] = $regid[$j];
            }
        }
    }
    $Success="";
    $Failure="";
    for($i=0;$i<sizeof($arr);$i++){
        $ch = curl_init();

        // Set the URL, number of POST vars, POST data
        curl_setopt( $ch, CURLOPT_URL, $url);
        curl_setopt( $ch, CURLOPT_POST, true);
        curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($arr[$i]));

        // Execute post
        $result = curl_exec($ch);

        // Close connection
        curl_close($ch);

        $decode = json_decode($result, true);

        $Success .= $decode["success"];
        $Failure .= $decode["failure"];

        $result .= $result;
    }

    return $result;
}

?>