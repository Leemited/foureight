<?php
require_once dirname(__FILE__) . '/config.php';

//모든 게시글 가저오기
$res = sql_query("select pd_id from `product` where pd_status < 10 and pd_blind < 10 and pd_blind_status = 0");
?>

<script>

var pd_id = '<?php echo $pd_id?>';
//var whish_cnt = "<?php echo $wish['cnt'];?>";
/*if(whish_cnt == ""){
    whish_cnt = 0;
}*/
var pd_ids = new Array();
var pd_ids_cnt = new Array();
<?php while($row = sql_fetch_array($res)){ ?>
    //$pd_ids[] = $row["pd_id"];
    pd_ids.push("<?php echo $row["pd_id"];?>");
    pd_ids_cnt[<?php echo $row["pd_id"];?>] = 0;
<?php } ?>

socket.connect();

socket.emit('add connect', {pd_id:pd_id,cnt_type:"plus",pd_ids:pd_ids,pd_ids_cnt:pd_ids_cnt});

socket.once('view connect', function(data){
    var cnt = Number(data.cnt);
    if(data.pd_id) {
        $(".view_cnt .count_" + data.pd_id).html(cnt);
    }
    if(cnt > 1) {
        $(".view_cnt .count_msg").addClass("active");
    }else{
        $(".view_cnt .count_msg").removeClass("active");
    }
    pd_ids_cnt[data.pd_id] = cnt;
});

socket.on('insert connect', function(data){
    var cnt = Number(data.cnt);
    if(data.pd_id) {
        $(".view_cnt .count_" + data.pd_id).html(cnt);
    }
    if(cnt > 1) {
        $(".view_cnt .count_msg").addClass("active");
    }else{
        $(".view_cnt .count_msg").removeClass("active");
    }
    pd_ids_cnt[data.pd_id] = cnt;
});

$(".view_top .close").click(function(){
    //socket.disconnect();
});
</script>
