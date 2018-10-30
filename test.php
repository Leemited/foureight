<?php
include_once ("./common.php");
//include_once (G5_EXTEND_PATH."/fcm.extend.php");
$title = "test";
$content = "테스입니다.";
$urls = "http://mave01.cafe24.com";
$apiKey = "AAAATdHUVhc:APA91bHBoGTQnwcHrTgeBbZJaF6dz9TQ2EsMSayHCbsJntos5kqxwF9RT5ujrwfSe8mXZcbIlhKAUEuuYGNV1TDqKtixh08m6HSwjVNIWEZGA9meaJ1kMjs3VuyIn5qp0-pri79r0ql9";
$regId_array=array("cvKj5qAMPx8:APA91bELmU2b6h5JdiISGf1PfaSAQwv39COnx1lF4PukmXPvn5enREAe8mlPC3Qf1S_P7L81OcaZ9tj2JUbcHCtaqGbVHssXppo8rO0tDcaR9tFXNHLNbH1pAlAM10r6xzulsFCjdB1M");
$url = 'https://fcm.googleapis.com/fcm/send';
$fields = array(
    'registration_ids' => $regId_array,
    'data' => array( "title"=>$title,"message" => $content , "content_available" => 'true',"urls" => $urls),
    'priority' => 'high',
    'sound' => 'default'
);
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

print_r2($result);
