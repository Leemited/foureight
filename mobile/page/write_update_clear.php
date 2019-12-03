<?php
include_once ("../../common.php");
include_once (G5_PATH.'/head.sub.php');
?>

<script>
    $(function(){
        setCookie('<?php echo $member["mb_id"];?>',"","1");
        setCookie("wr_type1","","1");
        setCookie("pd_type2","","1");
        setCookie("cate1","","1");
        setCookie("cate2","","1");
        setCookie("title","","1");
        setCookie("filename","","1");
        setCookie("videoname","","1");
        setCookie("pd_price","","1");
        setCookie("pd_price2","","1");
        setCookie("pd_video_link","","1");
        setCookie("pd_timeFrom","","1");
        setCookie("pd_timeTo","","1");
        setCookie("pd_discount","","1");
        setCookie("pd_content","","1");
        setCookie("pd_price_type","","1");
        setCookie("pd_location","","1");
        setCookie("pd_location_name","","1");
        setCookie("pd_infos","","1");
        <?php if($pd_id){?>
            location.replace("<?php echo $return_url."/?pd_id=".$pd_id;?>");
        <?php }else{?>
            location.replace(g5_url);
        <?php }?>
    });
</script>
