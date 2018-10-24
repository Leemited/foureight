<?php

require_once dirname(__FILE__) . '/config.php';

?>

<script>

var pd_id = '<?=$pd_id?>';
var whish_cnt = "<?=$wish['cnt'];?>";
if(whish_cnt == ""){
    whish_cnt = 0;
}
console.log("aA " + whish_cnt);
socket.connect();

socket.emit('add connect', {pd_id:pd_id});

socket.on('view connect', function(data){
    var cnt = Number(whish_cnt) + Number(data);
    $(".view_cnt .count").html(cnt);
});
socket.on('insert connect', function(data){
    var cnt = Number(whish_cnt) + Number(data);
    $(".view_cnt .count").html(cnt);
});
/*
socket.on('view disconnect', function(data){
    var cnt = Number(whish_cnt) + Number(data);
    $(".view_cnt .count").html(cnt);
});
socket.on('insert disconnect', function(data){
    var cnt = Number(whish_cnt) + Number(data);
    $(".view_cnt .count").html(cnt);
});
*/
$(".view_top .close").click(function(){
    whish_cnt = 0;
    socket.disconnect();
});
</script>
