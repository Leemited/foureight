<?php
include_once ("../../../common.php");

if($_FILES["com_sign"]["tmp_name"]){
    // 디렉토리가 없다면 생성합니다. (퍼미션도 변경하구요.)
    @mkdir(G5_DATA_PATH.'/company/', G5_DIR_PERMISSION);
    @chmod(G5_DATA_PATH.'/company/', G5_DIR_PERMISSION);

    $path = G5_DATA_PATH."/company/";

    $photo = $_FILES["com_sign"]["name"];

    $filetmp = explode(".",$photo);

    $filename = $filetmp[0]."_".date("Ymd_hms").".".$filetmp[1];

    $uploadfile = $path.$filename;

    $error .= move_uploaded_file($_FILES["com_sign"]["tmp_name"],$uploadfile);

    if($error){
        var_dump($error);
    }

    $infile = " , com_sign = '{$filename}'";
}

$sql = "insert into `company_info` set 
com_name = '{$com_name}', 
com_ceo = '{$com_ceo}', 
com_num = '{$com_num}', 
com_type1 = '{$com_type1}', 
com_type2 = '{$com_type2}', 
com_zip = '{$com_zip}', 
com_addr1 = '{$com_addr1}', 
com_addr2 = '{$com_addr2}',
com_addr3 = '{$com_addr3}',
com_addr_jibeon = '{$com_addr_jibeon}',
com_tel = '{$com_tel}',
status = 0,
com_datetime = now() {$infile}";


if(sql_query($sql)){
    alert("사업자신청이 완료 되었습니다.");
}else{
    alert('잘못된 요청입니다. 다시 시도해주세요');
}




