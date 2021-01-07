<?php
include_once ("../../../common.php");
$pro = sql_fetch("select * from `product` where pd_id = '{$pd_id}'");

?>
<div id="id03" class="w3-modal w3-animate-opacity no-view">
    <div class="w3-modal-content w3-card-4">
        <div class="w3-container">
            <form name="write_from" id="write_from" method="post" action="">
                <input type="hidden" name="up_pd_id" id="up_pd_id" value="">
                <h2>상태변경</h2>
                <div>
                    <ul class="modal_sel status_ul">
                        <li id="status1" <?php if($pro["pd_status"]==0){?>class="active"<?php }?> >판매중</li>
                        <li id="status2" <?php if($pro["pd_status"]==1){?>class="active"<?php }?> >거래중</li>
                        <li id="status3" <?php if($pro["pd_status"]==2){?>class="active"<?php }?> >판매보류</li>
                        <li id="status4" <?php if($pro["pd_status"]==3){?>class="active"<?php }?> >판매완료</li>
                    </ul>
                </div>
                <div>
                    <input type="button" value="취소" onclick="modalClose(this)"><input type="button" value="확인" onclick="fnStatusUpdate('<?php echo $pd_id;?>');" >
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    $("#id03 ul.modal_sel li").each(function(){
        $(this).click(function(){
            if(!$(this).hasClass("active")){
                $(this).addClass("active");
                $("#id03 ul.modal_sel li").not($(this)).removeClass("active");
            }
        })
    });
</script>

