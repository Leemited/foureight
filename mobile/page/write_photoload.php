<?php
include_once ("../../common.php");

if($filename != ""){
    $images = explode(",",$filename);
    $image_cnt = count($images);
    ?>
    <?php for($i=0;$i<count($images);$i++){
        $img = get_images(G5_DATA_PATH."/product/".$images[$i],500,500);
        //$size = getimagesize(G5_DATA_PATH."/product/".$images[$i]);
        //print_r2($size);
        //$exif = @exif_read_data(G5_DATA_PATH."/product/".$images[$i]);
        //print_r2($exif);
        ?>
        <div class="image_box app" id="box<?php echo $i;?>" <?php if($app){?>onclick="window.android.camereOn2('<?php echo $member["mb_id"];?>','<?php echo $i;?>');"  <?php } if($app2){?>onclick="fnEditIos('<?php echo $member["mb_id"];?>','<?php echo $i;?>')"<?php }?>   style="background-image: url('<?php echo G5_DATA_URL;?>/product/<?php echo $img;?>');background-position:center;background-size:cover;background-repeat:no-repeat;">
            <label for="images<?php echo $i;?>">
                <!--<img src="<?php /*echo G5_DATA_URL;*/?>/product/<?php /*echo $img;*/?>" alt="image<?php /*echo $i;*/?>" style="opacity: 0" class="img_<?php /*echo $i;*/?>">-->
                <?php if(!$app && !$app2){?>
                    <input type="file" id="images<?php echo $i;?>" name="files[]" style="display:none;" accept="image/jpg, image/png, image/gif, image/jpeg">
                <?php } ?>
            </label>
        </div>
    <?php }?>
    <?php
    if($image_cnt > 0){
        for($i=$image_cnt;$i<5;$i++){
            ?>
            <div class="image_box" id="box<?php echo $i;?>" style="background-image: url('<?php echo G5_IMG_URL;?>/no_images.svg');background-position:center;background-size:cover;background-repeat:no-repeat;"  <?php if($app){?> onclick="window.android.camereOn2('<?php echo $member["mb_id"];?>','<?php echo $i;?>');"  <?php } if($app2){?>onclick="fnEditIos('<?php echo $member["mb_id"];?>','<?php echo $i;?>')"<?php }?>   >
                <label for="images<?php echo $i;?>">
                    <!--<img src="<?php /*echo G5_IMG_URL;*/?>/no_images.svg" alt="image<?php /*echo $i;*/?>" style="opacity: 0" class="img_<?php /*echo $i;*/?>">-->
                    <?php if(!$app && !$app2){?>
                        <input type="file" id="images<?php echo $i;?>" name="files[]" style="display:none;" accept="image/jpg, image/png, image/gif, image/jpeg">
                    <?php } ?>
                </label>
            </div>
        <?php }?>
    <?php }?>
<?php }else{
    for($i=0;$i<5;$i++){
        ?>
        <div class="image_box" id="box<?php echo $i;?>" style="background-image: url('<?php echo G5_IMG_URL;?>/no_images.svg');background-position:center;background-size:cover;background-repeat:no-repeat;" <?php if($app){?> onclick="window.android.camereOn2('<?php echo $member["mb_id"];?>','<?php echo $i;?>');"  <?php } if($app2){?>onclick="fnEditIos('<?php echo $member["mb_id"];?>','<?php echo $i;?>')"<?php }?>  >
            <label for="images<?php echo $i;?>">
                <!--<img src="<?php /*echo G5_IMG_URL;*/?>/no_images.svg" alt="image<?php /*echo $i;*/?>" style="opacity: 0" class="img_<?php /*echo $i;*/?>">-->
                <?php if(!$app && !$app2){?>
                    <input type="file" id="images<?php echo $i;?>" name="files[]" style="display:none;" accept="image/jpg, image/png, image/gif, image/jpeg">
                <?php } ?>
            </label>
        </div>
    <?php }
}?>

<script>

    function fnEditIos(mb_id,index){
        try{

            var dataString = {
                mb_id : mb_id,
                index : index
            };

            //var dataString = JSON.stringify(data);
            //alert(dataString);
            webkit.messageHandlers.onCamEdit.postMessage(dataString);
        }catch (err) {
            alert(err);
        }
    }
</script>
