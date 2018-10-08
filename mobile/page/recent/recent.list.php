<?php
include_once("../../../common.php");

include_once(G5_MOBILE_PATH."/head.login.php");

$page = $_REQUEST["page"];

if($member["mb_id"]){
    $mb_id = $member["mb_id"];
}else{
    $mb_id = session_id();
}

$search = " mb_id = '{$mb_id}' ";

$total=sql_fetch("select count(*) as cnt from `my_search_list` where {$search} ");
if(!$page)
	$page=1;
else
	$page++;
$total=$total['cnt'];
$rows=10;
$start=($page-1)*$rows;
$total_page=ceil($total/$rows);

$sql = "select * from `my_search_list` where {$search} order by sc_status desc , sc_id desc  limit {$start},{$rows}";
$res = sql_query($sql);
while($row = sql_fetch_array($res)){
	$list[] = $row;
}

?>
<div class="sub_head">
	<div class="sub_back" onclick="location.href=g5_url"><img src="<?php echo G5_IMG_URL?>/ic_menu_back.svg" alt=""></div>
	<h2>검색 저장 목록</h2>
	<div class="all_clear" onclick="fnSearchClear();"><img src="<?php echo G5_IMG_URL?>/ic_menu_trash_all.svg" alt=""></div>
</div>
<div id="container" style="background-color:#e7e7e7;height:calc(100vh - 38vw);overflow-y:scroll">
	<section class="recent_list">
		<article class="post">
			<div class="my_search">
                <ul class="lists">
                    <?php for($i=0;$i<count($list);$i++){?>
                        <li class="sc_item <?php if($list[$i]["set_alarm"]== 1){?>save_item<?php }?>" >
                            <div class="types" onclick="fnSearch('<?php echo $list[$i]["sc_id"];?>')">
                                <?php if($list[$i]["sc_priceFrom"] != 0){?>
                                <h2><?php echo $list[$i]["sc_type2"];?></h2>
                                <?php }else{?>

                                <?php }?>
                            </div>
                            <div class="cont" onclick="fnSearch('<?php echo $list[$i]["sc_id"];?>')">
                                <p><?php echo $list[$i]["sc_datetime"];?></p>
                                <?php if($list[$i]["sc_priceFrom"] != 0){?>
                                <h3><?php echo $list[$i]["sc_cate1"];?>|<?php echo number_format($list[$i]["sc_priceFrom"]);?> 원 ~ <?php echo number_format($list[$i]["sc_priceTo"]);?> 원</h3>
                                <span><?php echo $list[$i]["sc_cate2"];?></span>
                                <?php  }else{ ?>
                                    <h3>간편검색 : <?php if($list[$i]["sc_type"]==1){echo "<span>[물건]</span>";}else{echo "<span>[능력]</span>";}?><?php echo $list[$i]["sc_tag"];?>
                                <?php }?>
                            </div>
                            <div class="btn">
<!--                                <input type="button" value="수정" class="schList_btn" onclick="search_edit('--><?php //echo $list[$i]["sc_id"];?><!--')"-->
                                <?php if($list[$i]["set_alarm"]!=1){?>
                                <input type="button" value="알림설정" class="schList_btn" onclick="search_push('<?php echo $list[$i]["sc_id"];?>','on')">
                                <?php }else{ ?>
                                    <input type="button" value="알림해제" class="schList_btn" onclick="search_push('<?php echo $list[$i]["sc_id"];?>','off')">
                                <?php } ?>
                                <input type="button" value="삭제" class="schList_btn" onclick="search_del('<?php echo $list[$i]["sc_id"];?>')">
                            </div>
                        </li>
                    <?php }
                    if(count($list)==0){?>
                        <li class="no-list">검색된 리스트가 없습니다.</li>
                    <?php }?>
                </ul>
            </div>
		</article>
	</section>
</div>
<script>
function fnSearch(sc_id){
    location.href=g5_url+"/index.php?sc_id="+sc_id+"&sctype=research";
}
function search_del(sc_id){
    location.href=g5_url+'/mobile/page/recent/search_delete.php?sc_id='+sc_id;
}
function fnSearchClear(){
    location.href=g5_url+"/mobile/page/recent/search_delete.php?sc_id=all&mb_id=<?php echo $mb_id;?>";
}
function search_push(sc_id,type){
    location.href=g5_url+'/mobile/page/recent/search_push_set.php?sc_id='+sc_id+"&setpush="+type;
}
</script>
<?php 

include_once(G5_MOBILE_PATH."/tail.php");
?>
