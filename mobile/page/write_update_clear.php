<?php
include_once ("../../common.php");
include_once (G5_PATH.'/head.sub.php');

$pd = sql_fetch("select * from `product` where pd_id ='{$pd_id}'");

//$_SESSION["type2"] = $pd["pd_type"];

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
            <?php if(strpos($return_url,"mypage")!==false){?>
                location.replace("<?php echo $return_url?>");
            <?php }else{
                $regx = '/^[?][pd_id]/';?>
                //console.log("returns1 : <?php echo $return_url;?>");
                <?php if(preg_match($regx,$return_url,$matches)!==false){
                        $return_url = array_shift(explode("?",$return_url));
                    ?>
                    //console.log("returns2 : <?php echo $return_url;?>");
                    location.replace("<?php echo $return_url?>/index.php?pd_id=<?php echo $pd_id;?>");
                <?php }else{?>
                    //console.log("returns3 : <?php echo $return_url;?>");
                    location.replace("<?php echo $return_url?>&pd_id=<?php echo $pd_id;?>");
                <?php }?>
            <?php }?>
        <?php }else{?>
            location.replace(g5_url);
        <?php }?>
    });
</script>
