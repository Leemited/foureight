<?php
include_once ("../../../common.php");
include_once (G5_MOBILE_PATH."/head.login.php");
$back_url = G5_URL;

if(!$is_member){
    alert("로그인이 필요합니다.", G5_MOBILE_URL."/login_intro.php");
}

$now = date("Y-m-d");
$month = date("Y-m-d", strtotime("-3 month"));

$sql = "select * from `my_alarms` where mb_id = '{$member["mb_id"]}' and alarm_date between '{$month}' and '{$now}'";
$res = sql_query($sql);
while($row = sql_fetch_array($res)){
    $alarm[] = $row;
}
?>
<div class="sub_head">
    <div class="sub_back" onclick="location.href='<?php echo $back_url;?>'"><img src="<?php echo G5_IMG_URL?>/ic_menu_back.svg" alt=""></div>
    <h2>알림목록</h2>
    <!-- <div class="sub_add">추가</div> -->
</div>
<div class="alert_list">
    <div>

    </div>
</div>
<script>
$(function(){
    var pd_id = "<?php echo $pd_id;?>";
    var mb_id = "<?php echo $member["mb_id"];?>";
    $.ajax({
       url:g5_url+"/mobile/page/ajax/ajax.alarm_update.php",
       method:"POST",
       data:{pd_id:pd_id,mb_id:mb_id}
    }).done(function(data){
       console.log(data);
    });
});
</script>
<?php
include_once (G5_MOBILE_PATH."/tail.php");
?>
