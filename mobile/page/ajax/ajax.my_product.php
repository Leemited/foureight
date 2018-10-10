<?php
include_once ("../../../common.php");

if(!$member["mb_id"]){
    echo "1";
    return false;
}

$sql = "select * from `product` where mb_id='{$member[mb_id]}' and pd_status = 0 and pd_type2 = 8";
$res = sql_query($sql);
while($row=sql_fetch_array($res)) {
    $my_pro[] = $row;
}
for($i=0;$i<count($my_pro);$i++){
?>
    <option value="<?php echo $my_pro[$i]["pd_id"];?>"><?php echo $my_pro[$i]["pd_name"];?></option>
<?php
}
if(count($my_pro)==0) {
    echo "2";
}
?>
