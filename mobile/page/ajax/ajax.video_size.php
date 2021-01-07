<?php
include_once ("../../../common.php");

print_r2($_REQUEST);

if(is_file(G5_DATA_PATH."/product/".$file_name)){
    $file = filesize(G5_DATA_PATH."/product/".$file_name);
    print_r2($file);
}

?>