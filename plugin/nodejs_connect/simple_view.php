<?php

require_once dirname(__FILE__) . '/config.php';

?>

<script>

var pd_id = '<?=$pd_id?>';
var whish_cnt = "<?=$wish['cnt'];?>";
if(whish_cnt == ""){
    whish_cnt = 0;
}

socket.connect();

socket.emit('add connect', {pd_id:pd_id,cnt_type:"plus"});

socket.on('view connect', function(data){
    var cnt = Number(data.cnt);
    $(".view_cnt .count_"+data.pd_id).html(cnt);
});
socket.on('insert connect', function(data){
    var cnt = Number(data.cnt);
    $(".view_cnt .count_"+data.pd_id).html(cnt);
});

$(".view_top .close").click(function(){

    socket.emit('add connect', {pd_id:pd_id,cnt_type:"minus"})
    socket.disconnect();

});
</script>
