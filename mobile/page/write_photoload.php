<?php
include_once ("../../common.php");
if (!$filename){
    $result["msg"] = "파일이 등록되지 않았습니다.";
    echo json_encode($result);
    return false;
}
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
        <div class="image_box app" id="box<?php echo $i;?>" <?php if($app){?>onclick="window.android.camereOn2('<?php echo $member["mb_id"];?>','<?php echo $i;?>');"<?php }?> style="background-image: url('<?php echo G5_DATA_URL;?>/product/<?php echo $img;?>');background-position:center;background-size:cover;background-repeat:no-repeat;">
            <label for="images<?php echo $i;?>">
                <img src="<?php echo G5_DATA_URL;?>/product/<?php echo $img;?>" alt="image<?php echo $i;?>" style="opacity: 0" class="img_<?php echo $i;?>">
                <?php if(!$app){?>
                    <input type="file" id="images<?php echo $i;?>" name="files[]" style="display:none;" >
                <?php } ?>
            </label>
        </div>
    <?php }?>
    <?php
    if($image_cnt > 0){
        for($i=$image_cnt;$i<5;$i++){
            ?>
            <div class="image_box" id="box<?php echo $i;?>" style="background-image: url('<?php echo G5_IMG_URL;?>/no_images.svg');background-position:center;background-size:cover;background-repeat:no-repeat;"  <?php if($app){?> onclick="window.android.camereOn2('<?php echo $member["mb_id"];?>','<?php echo $i;?>');" <?php }?> >
                <label for="images<?php echo $i;?>">
                    <img src="<?php echo G5_IMG_URL;?>/no_images.svg" alt="image<?php echo $i;?>" style="opacity: 0" class="img_<?php echo $i;?>">
                    <?php if(!$app){?>
                        <input type="file" id="images<?php echo $i;?>" name="files[]" style="display:none;">
                    <?php } ?>
                </label>
            </div>
        <?php }?>
    <?php }?>
<?php }else{
    for($i=0;$i<5;$i++){
        ?>
        <div class="image_box" id="box<?php echo $i;?>" style="background-image: url('<?php echo G5_IMG_URL;?>/no_images.svg');background-position:center;background-size:cover;background-repeat:no-repeat;" <?php if($app){?> onclick="window.android.camereOn2('<?php echo $member["mb_id"];?>','<?php echo $i;?>');" <?php }?> >
            <label for="images<?php echo $i;?>">
                <img src="<?php echo G5_IMG_URL;?>/no_images.svg" alt="image<?php echo $i;?>" style="opacity: 0" class="img_<?php echo $i;?>">
                <?php if(!$app){?>
                    <input type="file" id="images<?php echo $i;?>" name="files[]" style="display:none;">
                <?php } ?>
            </label>
        </div>
    <?php }
}?>

