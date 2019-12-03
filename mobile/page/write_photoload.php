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
        <div class="image_box app" id="box<?php echo $i;?>" style="background-image: url('<?php echo G5_DATA_URL;?>/product/<?php echo $img;?>');" >
            <label style="display:inline-block;height:100%;width:100%" for="images<?php echo $i;?>" <?php if($app){?>onclick="window.android.camereOn2('<?php echo $member["mb_id"];?>','<?php echo $i;?>');"  <?php } if($app2){?>onclick="fnEditIos('<?php echo $member["mb_id"];?>','<?php echo $i;?>')"<?php }?> >
                <?php if(!$app && !$app2){?>
                    <img src="<?php echo G5_DATA_URL;?>/product/<?php echo $img;?>" alt="image<?php echo $i;?>" style="opacity: 0" class="img_<?php echo $i;?>">
                    <input type="file" id="images<?php echo $i;?>" name="files[]" style="display:none;" accept="image/jpg, image/png, image/gif, image/jpeg">
                <?php } ?>
                <!--<input type="text" name="del_img[]" id="del_img<?php /*echo $i;*/?>" value="">-->
            </label>
            <span onclick="fnDelImg('<?php echo $images[$i];?>','<?php echo $i;?>','<?php echo $pd_id;?>')">
                <img src="<?php echo G5_IMG_URL;?>/ic_close_write.svg" alt="" style="width:5vw;height:5vw;top:0.5vw;right:0.5vw;position:absolute;">
            </span>
        </div>
    <?php }?>
    <?php
    if($image_cnt > 0){
        for($i=$image_cnt;$i<5;$i++){
            ?>
            <div class="image_box" id="box<?php echo $i;?>"  style="background-image: url('<?php echo G5_IMG_URL;?>/no_images.svg');"   >
                <label style="display:inline-block;height:100%;width:100%" for="images<?php echo $i;?>" <?php if($app){?>onclick="window.android.camereOn2('<?php echo $member["mb_id"];?>','<?php echo $i;?>');"  <?php } if($app2){?>onclick="fnEditIos('<?php echo $member["mb_id"];?>','<?php echo $i;?>')"<?php }?> >
                    <?php if(!$app && !$app2){?>
                        <img src="<?php echo G5_IMG_URL;?>/no_images.svg" alt="image<?php echo $i;?>" style="opacity: 0" class="img_<?php echo $i;?>">
                        <input type="file" id="images<?php echo $i;?>" name="files[]" style="display:none;" accept="image/jpg, image/png, image/gif, image/jpeg">
                    <?php } ?>
                </label>
            </div>
        <?php }?>
    <?php }?>
<?php }else{
    for($i=0;$i<5;$i++){
        ?>
        <div class="image_box" id="box<?php echo $i;?>" style="background-image: url('<?php echo G5_IMG_URL;?>/no_images.svg');" >
            <label style="display:inline-block;height:100%;width:100%" for="images<?php echo $i;?>" <?php if($app){?>onclick="window.android.camereOn2('<?php echo $member["mb_id"];?>','<?php echo $i;?>');"  <?php } if($app2){?>onclick="console.log('AAA');fnEditIos('<?php echo $member["mb_id"];?>','<?php echo $i;?>')"<?php }?> >
                <?php if(!$app && !$app2){?>
                    <img src="<?php echo G5_IMG_URL;?>/no_images.svg" alt="image<?php echo $i;?>" style="opacity: 0" class="img_<?php echo $i;?>">
                    <input type="file" id="images<?php echo $i;?>" name="files[]" style="display:none;" accept="image/jpg, image/png, image/gif, image/jpeg">
                <?php } ?>
            </label>
        </div>
    <?php }
}?>

<script>
    $(function(){
        $("input[id^=images]").each(function(e){
            var num = e;
            $(this).on("change",function(){
                readUrl(this,num);

            })
        });
    });

    function readUrl(file,cnt){
        if(file.files && file.files[0]){
            var reader = new FileReader();

            reader.onload = function(e){
                $("#box"+cnt).css("background-image","url('"+e.target.result+"')");
                var item = "<span onclick=\"fnDelImg('"+e.target.result+"','"+cnt+"','<?php echo $pd_id;?>')\"><img src=\"<?php echo G5_IMG_URL;?>/ic_close_write.svg\" style=\"width:5vw;height:5vw;top:0.5vw;right:0.5vw;position:absolute;\"></span>";
                $("#box"+cnt).append(item);
            }
            reader.readAsDataURL(file.files[0]);
        }
    }
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
    function fnDelImg(img,index,pd_id) {
        if(confirm("해당 이미지를 삭제 하시겠습니까?")) {
            if(pd_id) {
                $.ajax({
                    url: g5_url + '/mobile/page/ajax/ajax.img_del.php',
                    method: "post",
                    data: {img: img, index: index, pd_id: pd_id},
                    dataType: 'json'
                }).done(function (data) {
                    if (data.msg == 1) {
                        $.ajax({
                            url: g5_url + "/mobile/page/write_photoload.php",
                            method: "post",
                            data: {
                                filename: data.filename,
                                app: '<?php echo $app;?>',
                                app2: "<?php echo $app2;?>",
                                pd_id: pd_id
                            }
                        }).done(function (data) {
                            $(".filelist").html('<h2>사진수정</h2>');
                            $(".filelist").append(data);
                            $(".photo_msg").html('');
                        });
                    }
                });
            }else{
                $("#box"+index).attr("style","background-image:url('"+g5_url+"/img/no_images.svg')");
                $("#box"+index+" span").remove();
                //if () { // ie 일때 input[type=file] init.
                //    $("#images"+index).replaceWith( $("#images"+index).clone(true) );
                //} else { // other browser 일때 input[type=file] init.
                    $("#images"+index).val("");
                    console.log($("#images"+index).val());
                //}
            }
        }
    }
</script>
