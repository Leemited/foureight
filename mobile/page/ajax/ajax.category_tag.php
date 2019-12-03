<?php
include_once("../../../common.php");

$sql = "select * from `categorys` where cate_name = '{$cate2}' and cate_depth = 2 ";
$cate_tag = sql_fetch($sql);
/*$result["catetag"] = $cate_tag["cate_tag"];
$result["info_text1"] = $cate_tag["info_text1"];
$result["info_text2"] = $cate_tag["info_text2"];
$result["ca_id"] = $cate_tag["ca_id"];*/

//echo json_encode($result);

print_r2($_REQUEST);
?>
<script>
var type = "<?php echo $set_type;?>";
var type2 = "<?php echo $pd_type2;?>";
var app = "<?php echo $app;?>";
var app2 = "<?php echo $app2;?>";

console.log(type+"//"+type2+"//"+app+"//"+app2);
</script>
