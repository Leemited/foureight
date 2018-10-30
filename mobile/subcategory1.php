<?php 


$sql = "select * from `categorys` where `cate_depth` = 2 and cate_type = 1 order by `cate_order`";
$res = sql_query($sql);
while($row=sql_fetch_array($res)){
	switch($row["parent_ca_id"]){
		case 1:
			$scate[0][] = $row;
			break;
		case 2:
			$scate[1][] = $row;
			break;
		case 3:
			$scate[2][] = $row;
			break;
		case 4:
			$scate[3][] = $row;
			break;
		case 5:
			$scate[4][] = $row;
			break;
		case 6:
			$scate[5][] = $row;
			break;
		case 7:
			$scate[6][] = $row;
			break;
		case 8:
			$scate[7][] = $row;
			break;
		case 9:
			$scate[8][] = $row;
			break;
		case 10:
			$scate[9][] = $row;
			break;
		case 11:
			$scate[10][] = $row;
			break;
		case 12:
			$scate[11][] = $row;
			break;
		case 13:
			$scate[12][] = $row;
			break;
		case 14:
			$scate[13][] = $row;
			break;
		case 15:
			$scate[14][] = $row;
			break;
		case 16:
			$scate[15][] = $row;
			break;
		case 17:
			$scate[16][] = $row;
			break;
		case 18:
			$scate[17][] = $row;
			break;
	}

    $parent[] = $row["parent_ca_id"];
}
?>
<div class="category2">
	<?php for($i=0;$i<count($scate);$i++){ ?>
	<ul class="scate<?php echo ($i+1);?> <?php if($i==0){?>active<?php }?>">
		<?php for($j=0;$j<count($scate[$i]);$j++){?>
		<li id="<?php echo $scate[$i][$j]["cate_code"];?>"><a href="#"><?php echo $scate[$i][$j]["cate_name"];?></a></li>
		<?php }?>
        <li onclick="fnsuggestion2('<?php echo $parent[$i];?>');">제안하기</li>
    </ul>
	<?php }?>

</div>