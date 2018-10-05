<?php 
include_once("../common.php");
include_once("./admin.head.php");

?>
<div id="wrap">
	<section>
		<header class="admin_title">
			<h1></h1>
		</header>
		<article>
			<!-- <div class="model_list">
				

				<div class="etc_gr">
					<h2 class="board_t"><?php echo $subject;?></h2>
					<input type="button" value="더보기" class="adm_btn01 more" onclick="location.href='<?php echo G5_URL?>/admin/reservation_list.php'">
					<div class="clear"></div>
				</div>
				<table>
					<colgroup>
						<col width="8%" class="md_none">
						<col width="10%" class="md_none">
						<col width="10%">
						<col width="10%">
						<col width="20%" class="md_none">
						<col width="*" class="md_none">
					</colgroup>
					<thead>
						<tr>
							<th class="md_none">번호</th>							
							<th class="md_none">객실명</th>							
                            <th>예약자</th>
							<th class="call">전화번호</th>
							<th class="md_none">예약일</th>
							<th class="md_none">예약정보</th>
						</tr>
					</thead>
					<tbody>
					<?php
						for($i=0;$i<count($list);$i++){
					?>
						<tr>
							<td class="md_none" onclick="location.href='<?php echo G5_URL."/admin/reservation_view.php?reser_id=".$list[$i]['reser_id']."&page=".$page; ?>'"><?php echo $list[$i]['num']; ?></td>						
							<td class="md_none" onclick="location.href='<?php echo G5_URL."/admin/reservation_view.php?reser_id=".$list[$i]['reser_id']."&page=".$page; ?>'"><?php echo $list[$i]['room_name']; ?></td>
                            <td onclick="location.href='<?php echo G5_URL."/admin/reservation_view.php?reser_id=".$list[$i]['reser_id']."&page=".$page; ?>'"><?php echo $list[$i]["reser_name"];?></td>
							<td class="call" onclick="location.href='<?php echo G5_URL."/admin/reservation_view.php?reser_id=".$list[$i]['reser_id']."&page=".$page; ?>'"><?php echo $list[$i]["reser_phone"];?></td>
							<td onclick="location.href='<?php echo G5_URL."/admin/reservation_view.php?reser_id=".$list[$i]['reser_id']."&page=".$page; ?>'" class="md_none"><?php echo $list[$i]["reser_date"]; ?></td> 
							<td onclick="location.href='<?php echo G5_URL."/admin/reservation_view.php?reser_id=".$list[$i]['reser_id']."&page=".$page; ?>'" class="md_none"><?php echo "체크인 : ".$list[$i]["checkin"]. " 체크아웃 : ".$list[$i]["checkout"]. " 성인 : ".$list[$i]["adult_person"]." 유아 : ".$list[$i]["children_person"]; ?></td> 
						</tr>
					<?php
						}
						if(count($list)==0){
					?>
						<tr>
							<td colspan="6" class="text-center" style="padding:50px 0;">등록된 게시물이 없습니다.</td>
						</tr>
					<?php
						}
					?>
					</tbody>
				</table>
			</div> -->
		</article>
	</section>

</div>
<?php
include_once("./admin.tail.php");
?>