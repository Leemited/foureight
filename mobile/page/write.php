<?php
include_once("../../common.php");
if(!$is_member){
    alert("로그인이 필요합니다.",G5_MOBILE_URL."/page/login_intro.php?url=".G5_MOBILE_URL."/page/write.php");
}

$mySetting = sql_fetch("select * from `mysetting` where mb_id = '{$member[mb_id]}'");
$mywords = explode(":@!",$mySetting["my_word"]);
if(count($mywords)<3){

}else if(count($mywords)==3) {
    for ($i = 0; $i < count($mywords); $i++) {
        //0 = 판매시
        //1 = 구매시
        //2 = 대화하기
        $mywordss[] = explode("!@~", $mywords[$i]);
    }
    $mywordss = array_filter($mywordss);
}
if($mySetting["my_locations"]) {
    $mylocations = explode(",", $mySetting["my_locations"]);
    $mylat = explode(",", $mySetting["location_lat"]);
    $mylng = explode(",", $mySetting["location_lng"]);
}

$cateholder = sql_fetch("select info_text1,info_text2 from `categorys` where cate_depth = 2 and cate_name = '{$sc}'");
if($pd_id){
    $sql = "select * from `product` where pd_id = '{$pd_id}'";
    $write = sql_fetch($sql);

    if($pd_id){
        $sql = "select * from `categorys` where cate_type= '{$write["pd_type"]}' and cate_depth = 1";
        $cate1 = sql_query($sql);
        while($row = sql_fetch_array($cate1)){
            $ca1[] = $row;
            if($row["cate_name"] == $write["pd_cate"]){
                $ca_id = $row["ca_id"];
            }
        }
        $cate2 = sql_query("select * from `categorys` where cate_type = '{$write["pd_type"]}' and cate_depth = 2 and parent_ca_id = '{$ca_id}'");
        while($row = sql_fetch_array($cate2)){
            $ca2[] = $row;
        }
    }
}

if(!$pd_id) {//신규등록
    $type1 = $_REQUEST["wr_type1"];
    $pd_type2 = $_REQUEST["pd_type2"];
    $c = $_REQUEST["cate1"];
    $sc = $_REQUEST["cate2"];
    $title = $_REQUEST["title"];
    $filename = $_REQUEST["filename"];
    $videoname = $_REQUEST["videoname"];
    $pd_price = str_replace(",","",$_REQUEST["wr_price"]);
    $pd_price2 = str_replace(",","",$_REQUEST["wr_price2"]);
    if($_REQUEST["pd_timeFrom"]){
        $pd_timeFrom = $_REQUEST["pd_timeFrom"];
    }else {
        $pd_timeFrom = $mySetting["pd_timeFrom"];
        if ($pd_timeFrom == "") {
            $pd_timeFrom = "09";
        }
    }
    if($_REQUEST["pd_timeTo"]){
        $pd_timeTo = $_REQUEST["pd_timeTo"];
    }else {
        $pd_timeTo = $mySetting["pd_timeTo"];
        if ($pd_timeTo == "") {
            $pd_timeTo = "21";
        }
    }
    $pd_timeType = $mySetting["pd_timeType"];
    $pd_video_link = $_REQUEST["pd_video_link"];
    $pd_discount = $_REQUEST["pd_discount"];
    $pd_content = $_REQUEST["pd_content"];
    $pd_price_type = $_REQUEST["pd_price_type"];
    $pd_location = $_REQUEST["pd_location"];
    $pd_location_name = $_REQUEST["pd_location_name"];
    if($mylocations[0] != ""){
        $pd_location = $mylocations[0];
        $pd_location_name = $mylocations[0];
    }
    $pd_infos = $_REQUEST["pd_infos"];
    $pd_lat = $mylat[0];
    $pd_lng = $mylng[0];
    $pd_delivery_use = $_REQUEST["pd_delivery_use"];
}else{
    $type1 = $write["pd_type"];
    $pd_type2 = $write["pd_type2"];
    $c = $write["pd_cate"];
    $sc = $write["pd_cate2"];
    $title = $write["pd_name"];
    $filename = $write["pd_images"];
    $videoname = $write["pd_video"];
    $pd_price = $write["pd_price"];
    $pd_price2 = $write["pd_price2"];
    $pd_timeFrom = $write["pd_timeFrom"];
    $pd_timeTo = $write["pd_timeTo"];
    $pd_timeType = $write["pd_timeType"];
    $pd_video_link = $write["pd_video_link"];
    $pd_discount = $write["pd_discount"];
    $pd_content = $write["pd_content"];
    $pd_price_type = $write["pd_price_type"];
    $pd_location = $write["pd_location"];
    $pd_location_name = $write["pd_location_name"];
    $pd_infos = $write["pd_infos"];
    $pd_lat = $write["pd_lat"];
    $pd_lng = $write["pd_lng"];
    $pd_delivery_use = $write["pd_delivery_use"];
}

$set_type = $type1;
$_SESSION["type1"] = $type1;
include_once(G5_MOBILE_PATH."/head.login.php");
?>
<style>
    #head {
        position: fixed;
        z-index: 901;
    }
    .sub_head{    height: 10vw;
        width: 100%;
        position: fixed;
        box-shadow: 0px -5px 20px 0px #000;
        background-color: #fff;
        z-index: 900;
        top: 6vw;}
    .write_form{position: relative;overflow-y: auto;height:auto;margin-top: 16vw;margin-bottom: 15vw;}
</style>

<div class="sub_head">
	<div class="sub_back" onclick="fnBack('<?php echo $return_url;?>');">
		<img src="<?php echo G5_IMG_URL?>/ic_menu_back.svg" alt="">
	</div>
    <?php if($pd_id){?>
	<h2>게시물 수정</h2>
    <?php }else{?>
	<h2>상세등록</h2>
    <?php }?>
</div>
<div class="write_form">
	<form action="<?php echo G5_MOBILE_URL?>/page/write_update.php" method="post" name="writefrm" enctype="multipart/form-data" >
        <input type="hidden" value="<?php echo $return_url;?>" name="return_url">
        <input type="hidden" value="<?php echo $pd_id;?>" name="pd_id">
		<input type="hidden" value="<?php echo $member["mb_id"];?>" name="mb_id">
		<input type="hidden" value="<?php echo $title;?>" name="title" id="title">
		<input type="hidden" value="<?php echo $type1;?>" name="type" id="type">
		<input type="hidden" value="<?php echo $pd_type2;?>" name="type2" id="type2">
		<input type="hidden" value="<?php echo $c;?>" name="cate1" id="cate1">
		<input type="hidden" value="<?php echo $sc;?>" name="cate2" id="cate2">
		<input type="hidden" value="<?php echo $filename;?>" name="filename" id="filename" style="width:100%" onchange="setCookie('filename',this.value,1)">
        <input type="hidden" value="<?php echo $videoname;?>" name="videoname" id="videoname" style="width:100%" onchange="setCookie('videoname',this.value,1)">
        <!--<input type="text" value="" name="addr" id="addr">-->
        <!--<input type="hidden" value="<?php /*echo $write["pd_lat"];*/?>" name="pd_lat" id="pd_lat">
        <input type="hidden" value="<?php /*echo $write["pd_lng"];*/?>" name="pd_lng" id="pd_lng">-->
        <input type="hidden" class="write_input width_80" name="wr_subject" id="wr_subject" value="<?php if($pd_id){echo $write["pd_tag"];}else{echo $title;}?>" >
        <input type="hidden" name="mywords" value="">
        <?php if($type1 == 2){?>
            <section class="write_sec avil">
                <article>
                    <div>
                        <div class="videoArea sc avility">
                            <h2 style="position: relative;">거래 조건 및 유의 사항</h2><br>
                            <p style="font-size:2.8vw;">거래시 유의사항을 적어주세요. 대화하기에서 구매 안내에 사용됩니다.</p>
                            <textarea name="pd_infos" id="pd_infos" cols="30" rows="10" style="margin-top:5vw" required onkeyup="fnfilter(this.value,'pd_infos')" onchange="setCookie('pd_infos',this.value,1)"><?php echo str_replace("<br/>","\r", $pd_infos);?></textarea>
                        </div>
                    </div>
                </article>
            </section>
        <?php }?>
		<section class="write_sec">
			<article>
				<div>
                    <?php if($pd_id){
                        ?>
                    <div class="sch_top">
                        <select name="cate_up" id="cate_up" class="sel_cate input01s" required onchange="setCookie('cate1',this.value,1)">
                            <option value="">1차 카테고리</option>
                            <?php for($i=0;$i<count($ca1);$i++){ ?>
                                <option value="<?php echo $ca1[$i]["ca_id"];?>" <?php if($write["pd_cate"]==$ca1[$i]["cate_name"]){echo "selected";}?>><?php echo $ca1[$i]["cate_name"];?></option>
                            <?php } ?>
                        </select>
                        <select name="cate2_up" id="cate2_up" class="sel_cate input01s" required onchange="setCookie('cate2',this.value,1)">
                            <option value="">2차 카테고리</option>
                            <?php for($i=0;$i<count($ca2);$i++){ ?>
                                <option value="<?php echo $ca2[$i]["ca_id"];?>" <?php if($write["pd_cate2"]==$ca2[$i]["cate_name"]){echo "selected";}?>><?php echo $ca2[$i]["cate_name"];?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <?php }?>
					<!--<div class="write_title" style="display:none;">
						<label class="switch selltype">
							<input type="checkbox" name="sellcode" value="1" <?php /*if($write["pd_type2"]=="4" || $pd_type2 == 2){*/?>checked<?php /*}*/?>>
							<span class="slider round" <?php /*if($write["pd_type2"]=="4" || $pd_type2 == 2){*/?>style="text-align:left"<?php /*}*/?>><?php /*if($write["pd_type2"]=="4" || $pd_type2 == 2){*/?>구매<?php /*}else{*/?>판매<?php /*}*/?></span>
						</label>
					</div>-->
					<div class="write_con">
						<div class="my">
                            <?php if($write['pd_type2']==8 || $pd_type2 == 8){//판매시
                                $work1count = 0;
                                ?>
							<?php for($i=0;$i<count($mywordss[1]);$i++){
								if($mywordss[1][$i]!=""){
                                    $work1count++;
							?>
								<div class="myword"><?php echo $mywordss[1][$i];?><!--<span class="delBtn">X</span><input type="hidden" name="words[]" value="<?php /*echo $mywordss[0][$i];*/?>" id="words" class="words">--></div>
							<?php }
                            } if($work1count==0){?> <div class="myword">등록된 간편문구가 없습니다.</div><?php }?>
                            <?php }else if($write['pd_type2']==4 || $pd_type2 == 4){//구매시
                                $work2count = 0;
                                ?>
                            <?php for($i=0;$i<count($mywordss[0]);$i++){
                                if($mywordss[0][$i]!=""){
                                    $work2count++;
                                    ?>
                                    <div class="myword"><?php echo $mywordss[0][$i];?><!--<span class="delBtn">X</span><input type="hidden" name="words[]" value="<?php /*echo $mywords[$i];*/?>" id="words" class="words">--></div>
                                <?php }
                            }	if($work2count==0){?> <div class="myword">등록된 간편문구가 없습니다.</div><?php }?>
                            <?php } ?>
							<!--<input type="button" value="+ 개인문구 추가하기" class="word_add" onclick="addMyword();">-->
						</div>
						<div class="content">
                            <div class="in_content" style="padding:0;"></div>
							<textarea name="wr_content" id="wr_content" class="autosize" placeholder="상세 설명" onkeyup="fnfilter(this.value,'wr_content')" onchange="setCookie('pd_content',this.value,1)"><?php echo str_replace("<br/>","\r", $pd_content);?></textarea>
						</div>
					</div>
				</div>
			</article>
		</section>
        <section class="write_sec">
            <article>
                <div>
                    <div class="videoArea sc">
                        <h2>거래가능 시간</h2>
                        <div class="pd_times">
                            <select name="pd_timeFrom" id="pd_timeFrom" class="write_input3 sel_cate" style="width:12vw" onchange="setCookie('pd_timeFrom',this.value,1)">
                                <?php for($i = 1; $i< 25; $i++){
                                    $time = str_pad($i,"2","0",STR_PAD_LEFT);
                                    ?>
                                    <option value="<?php echo $time;?>" <?php if($pd_timeFrom==$time){?>selected<?php }?>><?php echo $time;?></option>
                                <?php }?>
                            </select> 시부터
                             ~
                            <input type="checkbox" value="1" name="pd_timeType" id="pd_timetype" <?php if($pd_timeType==1){?>checked<?php }?> style="display:none" onchange="setCookie('pd_timeType',$(this).prop('checked'),1)"><label for="pd_timetype"><img src="<?php echo G5_IMG_URL?>/ic_write_check.svg" alt=""> 익일 </label>
                            <select name="pd_timeTo" id="pd_timeTo" class="write_input3 sel_cate" style="width:12vw;margin-left:1vw" onchange="setCookie('pd_timeTo',this.value,1)">
                                <?php for($i = 1; $i< 25; $i++){
                                    $time = str_pad($i,"2","0",STR_PAD_LEFT);
                                    ?>
                                    <option value="<?php echo $time;?>" <?php if($pd_timeTo==$time){?>selected<?php }?>><?php echo $time;?></option>
                                <?php }?>
                            </select> 시사이
                        </div>
                    </div>
                </div>
            </article>
        </section>
        <section class="write_sec">
            <article>
                <div>
                    <div class="videoArea filelist">
                        <h2>사진수정</h2>
                        <?php if($filename!=""){ ?>
                        <?php
                            $images = explode(",",$filename);
                            $image_cnt = count($images);
                        ?>
                        <?php for($i=0;$i<count($images);$i++){
                            $img = get_images(G5_DATA_PATH."/product/".$images[$i],500,500);
                            ?>
                                <div class="image_box app" id="box<?php echo $i;?>"  style="background-image: url('<?php echo G5_DATA_URL;?>/product/<?php echo $img;?>');">
                                    <label for="images<?php echo $i;?>" <?php if($app){?>onclick="fnOnCamSingle('<?php echo $member["mb_id"];?>','<?php echo $i;?>')" <?php }else if($app2){?>onclick="fnEditIos('<?php echo $member["mb_id"];?>','<?php echo $i;?>')"<?php }?> >
                                        <img src="<?php echo G5_DATA_URL;?>/product/<?php echo $img;?>" alt="image<?php echo $i;?>" style="opacity: 0" class="img_<?php echo $i;?>">
                                        <?php if(!$app){?>
                                        <input type="file" id="images<?php echo $i;?>" name="files[]" style="display:none;" accept="image/jpg, image/png, image/gif, image/jpeg">
                                        <?php } ?>
                                    </label>
                                </div>
                        <?php }?>
                        <?php
                        if($image_cnt > 0){
                        for($i=$image_cnt;$i<5;$i++){
                            ?>
                            <div class="image_box" id="box<?php echo $i;?>" style="background-image: url('<?php echo G5_IMG_URL;?>/no_images.svg');"    >
                                <label for="images<?php echo $i;?>" <?php if($app){?> onclick="fnOnCamSingle('<?php echo $member["mb_id"];?>','<?php echo $i;?>');" <?php }else if($app2){?>onclick="fnEditIos('<?php echo $member["mb_id"];?>','<?php echo $i;?>')"<?php }?>>
                                    <img src="<?php echo G5_IMG_URL;?>/no_images.svg" alt="image<?php echo $i;?>" style="opacity: 0" class="img_<?php echo $i;?>">
                                    <?php if(!$app){?>
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
                                    <label for="images<?php echo $i;?>" <?php if($app){?> onclick="fnOnCamSingle('<?php echo $member["mb_id"];?>','<?php echo $i;?>');" <?php }else if($app2){?>onclick="fnEditIos('<?php echo $member["mb_id"];?>','<?php echo $i;?>')"<?php }?> >
                                        <img src="<?php echo G5_IMG_URL;?>/no_images.svg" alt="image<?php echo $i;?>" style="opacity: 0" class="img_<?php echo $i;?>">
                                        <?php if(!$app){?>
                                        <input type="file" id="images<?php echo $i;?>" name="files[]" style="display:none;" accept="image/jpg, image/png, image/gif, image/jpeg">
                                        <?php } ?>
                                    </label>
                                </div>
                            <?php }
                        }?>
                    </div>
                </div>
                <?php if($app || $app2){?>
                <div class="photo_msg">사진을 로드중입니다.</div>
                <?php }?>
            </article>
        </section>
        <section class="write_sec">
            <article>
                <div>
                    <div class="videoArea">
                        <h2>동영상 수정</h2>
                        <div class="edit_video" id="edit_video" <?php if($app){?>onclick="fnVideoEditApp('<?php echo $member["mb_id"];?>')"<?php } if($app2){?>onclick="fnVideoEdit()"<?php }?>>
                            <label for="video">영상수정</label>
                            <?php if(!$app && !$app2){?>
                            <input type="file" id="video" name="video" style="display:none;" accept="video/mp4">
                            <?php }?>
                        </div>
                        <div class="preview_video" style="position:relative;margin-top:10px;padding-top:10px;border-top:1px solid #ddd;text-align: center">
                        <?php if($write["pd_video"] || $videoname){
                                $video = ($write["pd_video"])?$write["pd_video"]:$videoname;
                            ?>
                            <video controls width="400px;" height="400px;" class="view_video" id="view_video" preload="metadata" src="<?php echo G5_DATA_URL."/product/".$video;?>" type="video/mp4"></video>
                        <?php }?>
                        </div>
                    </div>
                </div>
            </article>
        </section>
		<?php /*if($filename=="" && $chkMobile == false && !$pd_id){*/?><!--
		<section class="write_sec">
			<article>
				<div>
					<div class="videoArea fileAdd">
						<h2>사진첨부</h2>
						<div>
							<input type="text" name="filenames" class="write_input4" id="filenames0" readonly>
							<label for="files0" class="attachbtn" >파일첨부 1</label>
							<input type="file" name="files[]" class="" id="files0" style="display:none;" onchange="$(this).prev().prev().val($(this).val());">
						</div>
						<div>
							<input type="text" name="filenames" class="write_input4" id="filenames0" readonly>
							<label for="files1" class="attachbtn" >파일첨부 2</label>
							<input type="file" name="files[]" class="" id="files1" style="display:none;" onchange="$(this).prev().prev().val($(this).val());">
						</div>
						<div>
							<input type="text" name="filenames" class="write_input4" id="filenames0" readonly>
							<label for="files2" class="attachbtn" >파일첨부 3</label>
							<input type="file" name="files[]" class="" id="files2" style="display:none;" onchange="$(this).prev().prev().val($(this).val());">
						</div>
						<div>
							<input type="text" name="filenames" class="write_input4" id="filenames0" readonly>
							<label for="files3" class="attachbtn" >파일첨부 4</label>
							<input type="file" name="files[]" class="" id="files3" style="display:none;" onchange="$(this).prev().prev().val($(this).val());">
						</div>
						<div>
							<input type="text" name="filenames" class="write_input4" id="filenames0" readonly>
							<label for="files4" class="attachbtn" >파일첨부 5</label>
							<input type="file" name="files[]" class="" id="files4" style="display:none;" onchange="$(this).prev().prev().val($(this).val());">
						</div>
					</div>
				</div>
			</article>
		</section>
		--><?php /*}*/?>
		<section class="write_sec">
			<article>
				<div>
					<div class="videoArea">
						<h2>동영상 링크</h2>
						<div>
							<input type="text" class="write_input2" name="video_link" id="linkText">
							<input type="button" id="add" class="addLink" value="">
						</div>
					</div>
                    <div class="infor" style="margin-top:3vw;">
                        <p>붙여넣기 하신 후 꼭 +버튼을 눌러주세요.</p>
                    </div>
					<div class="videolinks">
                        <?php if($pd_video_link!=""){ ?>
                        <?php $linkVideos = explode(",",$pd_video_link); $linkCount = count($linkVideos); ?>
                        <?php for($i=0;$i<count($linkVideos);$i++){ ?>
                            <div class="link_lists it_<?php echo $i;?>"><?php echo $linkVideos[$i];?><img src="<?php echo G5_IMG_URL?>/ic_write_close.svg" alt="" class="linkDels">
                                <input type="hidden" value="<?php echo $linkVideos[$i];?>" name="links[]" id="" >
                            </div>
                        <?php } ?>
                        <?php } ?>
						<!-- <div class="link_lists">https://www.youtube.com/3e5fdCC <img src="<?php echo G5_IMG_URL?>/ic_write_close.svg" alt=""></div> -->
					</div>
				</div>
			</article>
		</section>
        <?php if($type1==2){?>
        <section class="write_sec">
            <article>
                <div>
                    <div class="videoArea">
                        <h2>배송여부</h2>
                        <div class="deliverys">
                            <input type="checkbox" name="pd_delivery_use" id="delivery_type" <?php if($pd_delivery_use==1){?>checked<?php }?> style="display:none"><label for="delivery_type"><img src="<?php echo G5_IMG_URL?>/ic_write_check.svg" alt=""> 배송필요 </label>
                        </div>
                    </div>
                </div>
            </article>
        </section>
        <?php }?>
		<section class="write_sec">
			<article>
				<div>
					<div class="videoArea sc">
						<h2>검색어</h2>
						<div>
							<input type="text" value="<?php if($title){echo $title;}?>" name="sub_title" id="sub_title" class="write_input3" onkeyup="fnfilter(this.value,'sub_title')" onchange="setCookie('pd_tag',this.value,1)"/>
						</div>
					</div>
					<div class="infor">
						<p>* 검색어 등록시 구분은 "#" 으로 해주세요.</p>
					</div>
				</div>
			</article>
		</section>
		<section class="write_sec pri">
			<article>
				<div>
                    <?php if($type1==2){?>
                        <div class="videoArea sc" style="margin-bottom:3vw;border-bottom:1px solid #ddd">
                            <h2>거래가격 단위</h2>
                            <div>
                                <select name="pd_price_type" id="pd_price_type" class="write_input3 sel_cate" style="width:25vw" onchange="setCookie('pd_price_type',this.value,1)">
                                    <option value="0" <?php if($pd_price_type == 0){?>selected<?php }?>>회당</option>
                                    <option value="1" <?php if($pd_price_type == 1){?>selected<?php }?>>시간당</option>
                                    <option value="2" <?php if($pd_price_type == 2){?>selected<?php }?>>일당</option>
                                </select>
                            </div>
                        </div>
                    <?php }?>
                    <?php if($type1==2){?>
                    <div class="prices step1">
                        <img src="<?php echo G5_IMG_URL?>/ic_won.svg" alt="" > <input type="tel" value="<?php if($pd_price2){echo number_format($pd_price2);}?>" placeholder="" name="price2" id="price2"  class="write_input2 width_80" onkeyup="number_only(this)" onchange="setCookie('pd_price2',this.value,1);"/> 계약금
					</div>
                    <?php }?>
					<div class="prices <?php if($type1==2){?>step2<?php }?>">
						<img src="<?php echo G5_IMG_URL?>/ic_won.svg" alt="" > <input type="tel" value="<?php if($pd_price){echo number_format($pd_price);}?>" placeholder="<?php if($pd_type2== 4){?>구매예상금액<?php }else{?>판매가격<?php }?>" name="price" id="price" required class="write_input2" onkeyup="number_only(this)" onchange="setCookie('pd_price',this.value,1)"/>
                        <?php if($type1==1 && $pd_type2 == 8){?><input type="checkbox" name="discount_use" style="display:none;" id="discount_use" value="1" <?php if($pd_discount==1){?>checked<?php }?>><label for="discount_use">딜가능<img src="<?php echo G5_IMG_URL?>/ic_write_check.svg" alt=""></label><?php }?>
                        <?php if($type1==2){?>거래완료금<?php }?>
                    </div>
				</div>
			</article>
		</section>
		<section class="write_sec">
			<article>
				<div>
					<div class="videoArea sc">
						<h2>거래 위치 선택</h2>
						<div>
							<input type="button" id="add" class="add" value="" onclick="addLoc();">
						</div>
					</div>
					<div class="loclist <?php if(!$pd_id){ echo  "no-loc";}?>">
                        <?php if($pd_location || $pd_location_name){?>
                            <div class="myloc">
                                <?php if($pd_location){echo $pd_location;}else{echo $pd_location_name;}?>
                                <img src="<?php echo G5_IMG_URL?>/ic_write_close.svg" alt="" class="locsDel">
                            </div>
                        <?php } ?>
					</div>
				</div>
                <input type="hidden" value="<?php echo $pd_location;?>" name="locs_name" id="locs_name">
                <input type="hidden" value="<?php echo $pd_location_name;?>" name="locs" id="locs">
                <input type="hidden" value="<?php echo $pd_lat;?>" name="pd_lat" id="pd_lat"/>
                <input type="hidden" value="<?php echo $pd_lng;?>" name="pd_lng" id="pd_lng"/>
			</article>
		</section>
		<div class="submit_btns">
			<input type="button" value="확인" class="submit_btn" onclick="fnSubmit();">
		</div>
	</form>
    <div id="map_sel" style="">
        <!--<img src="<?php /*echo G5_IMG_URL*/?>/view_pin.svg" alt="" class="map_pin">-->
        <div id="map" style="width:100%;height:70%;position:relative;">

        </div>
        <div class="loc_list">
            <ul class="loc_ul_list">
            </ul>
        </div>
        <div class="map_info" style="-webkit-border-radius: 4vw;-moz-border-radius: 4vw;border-radius: 4vw;position:relative;width:80%;z-index:9;height:3vw;overflow: hidden;margin:2vw auto 0 auto;">
            <div style="position:absolute;top:50%;left:50%;-webkit-transform: translate(-50%,-50%);-moz-transform: translate(-50%,-50%);-ms-transform: translate(-50%,-50%);-o-transform: translate(-50%,-50%);transform: translate(-50%,-50%);color:#fff;font-weight:bold;font-size:2.5vw;width:100%;display:inline-block;text-align: center;">지도의 위치를 터치하시면 상세주소가 추가됩니다.</div>
            <!--<div style="width:100%;background-color:rgba(0,0,0,.8);height:100%;"></div>-->
        </div>
        <div style="margin:2.8vw 0;text-align:center;height:16vw;" id="map_sel_btn">
            <input type="button" value="취소" onclick="mapSelect('')" style="border:none;width:48%;-webkit-border-radius: 10vw;-moz-border-radius: 10vw;border-radius: 10vw;background-color: #fff;color:#000;font-size:3vw;padding:2vw 0;">
            <input type="button" value="등록" onclick="mapSet();" style="border:none;width:48%;-webkit-border-radius: 10vw;-moz-border-radius: 10vw;border-radius: 10vw;background-color: #ffe100;color:#000;font-size:3vw;padding:2vw 0;">
        </div>
    </div>
</div>

<div class="bg" style="width:100vw;height:100vh;background-color:#eee;position:fixed;top:0;z-index:-1"></div>
<script>
function setImages(img,index){
    var filename = $("#filename").val();
    var files = filename.split(',');

    var newfile = '';
    if(index >= files.length){
        newfile = filename + "," +img;
    }else {
        for (var i = 0; i < files.length; i++) {
            if (i == index) {
                if (i == 0) {
                    newfile = img;
                } else {
                    newfile = newfile + "," + img;
                }
            } else {
                if (i == 0) {
                    newfile = files[i];
                } else {
                    newfile = newfile + "," + files[i];
                }
            }
        }
    }

    $.ajax({
        url:g5_url+"/mobile/page/ajax/ajax.edit_image.php",
        method:"post",
        data:{img:img},
        //dataType:"json"
    }).done(function(data){
        var imgs = "url('"+g5_url+"/data/product/"+data+"')";
        $("#box"+index).css("background-image",imgs);
        var item = "<span onclick=\"fnDelImg('"+data+"','"+index+"','')\"><img src=\"<?php echo G5_IMG_URL;?>/ic_close_write.svg\" style=\"width:5vw;height:5vw;top:0.5vw;right:0.5vw;position:absolute;\"></span>";
        $("#box"+index).append(item);
        if($("#filename").val()=="") {
            $("#filename").val(img);
        }else{
            $("#filename").val(newfile);
        }
        camOn = false;
    });
}

var videoSection;
$(function(){
    var width = $(".preview_video").width();
    //alert(width);
    $("#view_video").attr("width",width);
    //alert($("#view_video").attr("width"));
    //비디오 사이즈 조절
    <?php if($video || $videoname){?>
    videoSection = document.getElementById("view_video");

    videoSection.play();
    videoSection.pause();

    videoSection.addEventListener("canplay",videoSetWidth,false);

    videoSection.addEventListener("load ",videoSetWidth,false);
    <?php }?>
});
function setVideo(video){
    $("#videoname").val(video);

    setTimeout(function(){
        if($(".view_video").length==0){
            $(".preview_video").html('<video controls width="400px;" height="400px;" class="view_video" id="view_video" preload="metadata" src="" type="video/mp4" ></video>');
        }
        $(".view_video").attr("src",g5_url+"/data/product/"+video);

        //비디오 사이즈 조절
        videoSection = document.getElementById('view_video');
        videoSection.addEventListener("canplay",videoSetWidth,false);

        videoSection.addEventListener("load ",videoSetWidth,false);

        onVideo = false;
        //},2000);
    },1000);
}
function videoSetWidth(){
    console.log("videoSetWidth!!!");
    var videoSection = document.getElementById("view_video");
    var width = videoSection.videoWidth;
    var height = videoSection.videoHeight;
    var elmwidth = $("#edit_video").width();


    if(width != "NaN" || height != "NaN" && width != 0 || height != 0) {
        var ratio = width / elmwidth;
        width = width / ratio;
        height = height / ratio;

        $(".view_video").attr("width", width + "px");
        $(".view_video").attr("height", height + "px");
    }else{
        $(".view_video").attr("width", "auto");
        $(".view_video").attr("height", "auto");
    }
}
var onVideo = false;
function fnVideoEditApp(mb_id){
    if(onVideo==false) {
        window.android.camereOn3('<?php echo $member["mb_id"];?>');
        onVideo = true;
    }
}

function fnVideoEdit(){
    if(onVideo==false) {
        webkit.messageHandlers.onVideoEdit.postMessage('<?php echo $member["mb_id"];?>');
        onVideo = true;
    }
}

function offVideo(){
    onVideo = false;
}

$(document).on("change","#pd_timeFrom",function(){
    if($("#pd_timetype").prop("checked")!=true) {
        var time = $(this).val();
        setCookie("pd_timeFrom", $(this).val(), '1');
        $("#pd_timeTo option").each(function (e) {
            if (Number($(this).val()) < Number(time)) {
                $(this).attr("disabled", true);
            } else {
                $(this).attr("disabled", false);
            }
            if (Number(time) + 1 == e) {
                $(this).attr("selected", true);
            }
        })
    }
});

$(document).on("click","#pd_timetype",function(){
    if($(this).prop("checked")==true){
        $("#pd_timeTo option").each(function(e) {
            $(this).attr("disabled",false);
        });
        setCookie("pd_timetype", 1);
    }else{
        var time = $("#pd_timeFrom").val();

        $("#pd_timeTo option").each(function (e) {
            if (Number($(this).val()) < Number(time)) {
                $(this).attr("disabled", true);
            } else {
                $(this).attr("disabled", false);
            }
            if (Number(time) + 1 == e) {
                $(this).attr("selected", true);
            }
        })
        setCookie("pd_timetype", 0);
    }
});

//판매 구매 설정
$(".round").click(function(){
	if($(this).prev().prop("checked") == true){
		$(this).html("판매");
		$(this).css({"text-align":"right"});
		$(".write_sec.pri").css("display","block");
		$(".write_sec.avil").css("display","block");
        $("#price").attr("required","required");
        $("#pd_infos").attr("required","required");
	}else{
		$(this).html("구매");
		$(this).css({"text-align":"left"});
		$(".write_sec.avil").css("display","none");
        $("#price").attr("placeholder","구매예상가격");
        $("#price2").removeAttr("required");
        $("#pd_infos").removeAttr("required");
	}
});

function fnBack(return_url){
    var url = g5_url;
    if(return_url) {
        url = return_url;
    }
    <?php if($pd_id){?>
	if(confirm("상품/능력 등록을 취소하시겠습니까?")){
		location.href=url;
	}
	<?php }else{ ?>
    if(confirm("상품/능력 수정을 취소하시겠습니까?")){
        location.href=url;
    }
    <?php }?>
}

//상세설명 오토사이즈
$("textarea.autosize").on('keydown keyup', function () {
  $(this).height(1).height( $(this).prop('scrollHeight')+12 );
});

//문구등록
function addMyword(){
	$("#id01").css("display","block");
    $("html, body").css("overflow","hidden");
    $("html, body").css("height","100vh");
	location.hash="#modal";
	//var item = '<div>'+mytext+'<span class="delBtn">X</span><input type="hidden" name="loc[]" value="'+mytext+'"></div>';
}

$(document).on("click",".delBtn",function(){
	$(this).parent().remove();
});

//거래선호위치관련
function addLoc(){
    <?php if($locChk==true){?>

	$.ajax({
        url:g5_url+'/mobile/page/modal/modal.locations.php',
        method:"post"
    }).done(function(data){
        //$("#id02").css("display","block");
        $(".modal").html(data).addClass("active");
        //$(".modal > div").css({"transform":"translate(0,-50)","-webkit-transform":"translate(0,-50)","-ms-transform":"translate(0,-50)","-o-transform":"translate(0,-50)","-moz-transform":"translate(0,-50)"});
        $("html, body").css("overflow","hidden");
        $("html, body").css("height","100vh");
        //var height = $("#id02 .w3-container").height();
        //$(".w3-modal-content").css({"height":height+"px","margin-top":"-"+(height/2)+"px"});
    });
	<?php }else{?>
        $.ajax({
        url:g5_url+'/mobile/page/modal/modal.addlocations.php',
        method:"post",
        data:{}
    }).done(function(data){
        $(".write_form form input,.write_form form select,.write_form form textarea").attr("disabled","disabled");
        $(".modal").html(data).addClass("active");
        //$(".modal > div").css({"transform":"translate(-50%,0)","-webkit-transform":"translate(-50%,0)","-ms-transform":"translate(-50%,0)","-o-transform":"translate(-50%,0)","-moz-transform":"translate(-50%,0)"});
        //$("#id03").css("display","block");
        $("html, body").css("overflow","hidden");
        $("html, body").css("height","100vh");
    });
    <?php }?>
    location.hash="#modal";
}

$(".modal_sel li").click(function(){
	var text = $(this).text();
	if($(this).hasClass("active")){
		$(this).removeClass("active");
		$("[class^=myloc]").each(function(e){
			if($(this).text() == text){
				$(this).remove();
			}
		});
	}else{
        getLatlng(text);
		$(this).addClass("active");
		$(".modal_sel li").not($(this)).removeClass("active");
        $(".loclist").html('');
		var item = '<div class="myloc">'+text+'<img src="'+g5_url+'/img/ic_write_close.svg" alt="" class="locsDel"><input type="hidden" value="'+text+'" name="locs" /></div>';
	}
    $(".loclist").append(item);
});

//링크등록
var linkCnt = Number("<?php echo $linkCount;?>");

$(".addLink").click(function(){
	if(linkCnt>=3){
		alert("링크는 최대 3개까지 등록 가능합니다.");
		return false;
	}
	var link = $("#linkText").val();
	if(link==""){
		alert("링크주소를 입력해주세요");
		return false;
	}
    if(link.indexOf("youtu.be")==-1 && link.indexOf('youtube')==-1){
	    alert("Youtube 링크만 가능합니다.");
	    return false;
    }

    var pd_link="";
    if(pd_link==""){
        pd_link = link;
    }else{
        pd_link = pd_link+","+link;
    }
    setCookie("pd_video_link",pd_link,'1');

	var item = '<div class="link_lists it_'+linkCnt+'">'+link+'<img src="<?php echo G5_IMG_URL?>/ic_write_close.svg" alt="" class="locsDel"></div>';
	var value = '<input type="hidden" value="'+link+'" name="links[]" id=""/>';
	$(".videolinks").append(item);
	$(".it_"+linkCnt).append(value);
	$("#linkText").val("");
	linkCnt++;
});

var fileCnt = 0;
$(".addfile").click(function(){
	if(fileCnt>=5){
		alert("파일은 최대 5개까지 등록 가능합니다.");
		return false;
	}

	$(".fileAttach").append(item);
	$(".it_"+linkCnt).append(value);
	$("#linkText").val("");
	linkCnt++;
});

$(document).on("click",".linkDels",function(){
	var parent = $(this).parent();
	$("#locs1").val('');
	parent.remove();
	linkCnt--;
});

$(document).on("click",".locsDel",function(){
    var parent = $(this).parent();
    $("#locs_name").val('');
    $("#locs").val('');
    $("#pd_lat").val('');
    $("#pd_lng").val('');
    parent.remove();
});

function addWords(){
	var words = $("#words").val();
	if(words==""){
		alert("개인문구를 입력해 주세요");
		return false;
	}

	var item = '<div>'+words+'<span class="delBtn">X</span><input type="hidden" name="words[]" value="'+words+'" id="words" class="words"></div>';
	$(".my").prepend(item);
	alert("추가되었습니다.");
	$("#words").val('');
	modalClose();
}

function fnSubmit(){
    var cnt = 0;
    $(".myloc").each(function(){
        cnt++;
    });
    if(cnt == 0){
        addLoc();
        return false;
    }
    var formData = $("form[name=writefrm]").serialize();

    document.writefrm.submit();

    $(".submit_btn").attr("disabled",true);

    /*$.ajax({
        method:"post",
        url:g5_url+"/mobile/page/write_update.php",
        data:formData
    }).done(function(data){
        console.log(data);
        //alert("등록되었습니다.22");
        //location.replace(data);
    });*/
    //console.log(formData);
    //return true;
    //setCookie("<?php echo $member["mb_id"];?>","done","1");
}
var selLoc = false;
var mapon = false;
function mapSelect(num){
    $(".loc_ul_list li").remove();

    if(upText!=null) {
        clearTimeout(upText);
    }
    upText = null;
    if(typeof($(".modal #id03"))=="undefined" || $(".modal #id03").length == 0){
        return false;
    }
    if($("#locs1").val()==""){
        alert("거래위치를 입력해주세요");
        return false;
    }

    var loc = $("#locs1").val();

    if(mapon==false) {
        $(".write_form form input,.write_form form select,.write_form form textarea").attr("disabled","disabled");
        $("#locs1").blur();
        ps.keywordSearch(loc, placesSearchCB);
        $("#setnum").val(num);
        $("#map_sel").css({"bottom": "0","top":"7vw"});
        $("#helps .item a,#helps").css("padding-bottom","1vw");
        $("html, body").css("overflow","hidden");
        $("html, body").css({"height":"100%","min-height":"100%"});
        $(".modal > div").addClass("no-view");
        $(".modal").css("background","rgba(0,0,0,.85");
        mapon = true;
        selLoc = false;
        $("#locs1").attr("disabled",true);
        location.hash = "#mapsel";
        if(app){
            window.android.HideKeyboard();
        }

        window.addEventListener('load', function(){setTimeout(scrollTo, 0, 0, 1);
        }, false);
        setTimeout(function(){

            var head = $("#head").height();
            var map = $("#map").height();
            var map_btn = $("#map_sel_btn").height();
            var map_info = $(".map_info").height();
            var body = document.body.offsetHeight;
            var list_height = body - (head + map + map_btn + map_info);
            $(".loc_list").height(list_height+"px");
        },1000)
    }else if(mapon==true || num == ''){
        locitem = '';
        $("#setnum").val('');
        $("#addr").val('');
        //$(".write_form form input,.write_form form select,.write_form form textarea").removeAttr("disabled");
        $("#map_sel").removeAttr("style");
        $("#helps .item a,#helps").css("padding-bottom","0");
        $("#locs1").removeAttr("disabled");
        $("#locs1").focus();
        $(".modal").css("background","rgba(0,0,0,.6)");
        //$(".modal > div").removeClass("no-view");
        //setMarkers(null);
        //markers = [];
        mapon = false;
        /*$("html, body").css("overflow","auto");
        $("html, body").css("height","auto");*/
        location.hash = "#modal";
    }
}
var locitem = "";
function mapSet(){
    if(selLoc == false){
        alert("등록할 위치를 선택해주세요.");
        return false;
    }

    $(".loclist").html('');
    $(".loc_ul_list li").each(function(){
        if($(this).hasClass("active")) {
            var text = $(this).text();
            if (text.indexOf("]") != -1) {
                var textchk = text.split("]");
                text = textchk[1];
            }
            if(locitem.indexOf(text)!=-1){
                $(".loclist").append(locitem);
            }else{
                alert('정보가 잘못 되었습니다. 다시 시도해 주세요.');
            }
        }
    });
    locitem = '';
    $("#setnum").val('');
    $("#addr").val('');
    $(".write_form form input,.write_form form select,.write_form form textarea").removeAttr("disabled");
    $("#map_sel").removeAttr("style");
    $("#helps .item a,#helps").css("padding-bottom","0");
    $("#locs1").removeAttr("disabled");
    $("#locs1").focus();
    $(".modal").css("background","rgba(0,0,0,.6)");
    location.hash = "#modal";
    mapon = false;
    $("#locs_name").val(locs_name);
    $("#locs").val(locs);
    if(newlat) {
        $("#pd_lat").val(newlat);
    }
    if(newlng) {
        $("#pd_lng").val(newlng);
    }
    locitem="";
    setMarkers(null);
    markers = [];
    //mapSelect();
    modalClose();
}
function nowLoc(num){
    if(lat && lng){
        var latlng = new kakao.maps.LatLng(lat, lng);
    }else{
        var loc = window.android.getLocation();
        var myloc = loc.split("/");
        var latlng = new kakao.maps.LatLng(myloc[0], myloc[1]);
        lat = myloc[0];
        lng = myloc[1];
    }

    // 지도를 클릭했을때 클릭한 위치에 마커를 추가하도록 지도에 클릭이벤트를 등록합니다
        searchDetailAddrFromCoords(latlng, function(result, status) {
            if (status === kakao.maps.services.Status.OK) {
                var detailAddr = !!result[0].road_address ? '<div>도로명주소 : ' + result[0].road_address.address_name + '</div>' : '';
                detailAddr += '<div>지번 주소 : ' + result[0].address.address_name + '</div>';


                var item = '<div class="myloc">'+result[0].address.address_name+'<img src="'+g5_url+'/img/ic_write_close.svg" alt="" class="locsDel"></div>';

                setCookie("pd_location",result[0].address.address_name);
                setCookie("pd_location_name",result[0].address.address_name);

                $("#pd_lat").val(lat);
                $("#pd_lng").val(lng);
                $("#locs_name").val(result[0].address.address_name);
                $("#locs").val(result[0].address.address_name);

                $(".loclist").html('');
                $(".loclist").append(item);
                modalClose();
            }
        });

}
$(document).on("focus","#locs1",function(){
    $(".modal > div").css({"transform":"translate(-50%,-20%)","-webkit-transform":"translate(-50%,-20%)","-ms-transform":"translate(-50%,-20%)","-o-transform":"translate(-50%,-20%)","-moz-transform":"translate(-50%,-20%)"});
});
$(document).on("blur","#locs1",function(){
    $(".modal > div").css({"transform":"translate(-50%,-50%)","-webkit-transform":"translate(-50%,-50%)","-ms-transform":"translate(-50%,-50%)","-o-transform":"translate(-50%,-50%)","-moz-transform":"translate(-50%,-50%)"});
});
$(document).on("click",".loc_ul_list li",function(){
   if(!$(this).hasClass("active")){
       $(this).addClass("active");
       $(".loc_ul_list li").not($(this)).removeClass("active");
   }
});
$(function(){


    $("#delivery_type").change(function(){
        if($(this).prop("checked")==true){
            if(confirm("배송이 필요한 능력의 경우, \r원할한 거래를 위해 계약금 없이 등록됩니다.\r\n\r\n계속 진행하시겠습니까?")){
                $("#price2").val('')
                $(".prices.step1").hide();
            }else{
                $(this).prop("checked",false);
            }
        }else{
            $("#price2").val('');
            $(".prices.step1").show();
        }
    });

    <?php if($app){?>
    /*var lat = "<?php //echo $_SESSION["lat"];?>";
    var lng = "<?php //echo $_SESSION["lng"];?>";
    console.log(lat+"//"+lng);
    var loc = "강남";
    //초기 진입 지도 위치 확인
    ps.keywordSearch(loc, placesSearchCB);*/
    <?php }?>

    var flag = false;
    <?php if($type1 == 1 && $pd_type2 == 8){?>
        if(flag == false) {
            setTimeout(function () {
                $.ajax({
                    url:g5_url+'/mobile/page/modal/modal.writeinfo.php',
                    method:"post"
                }).done(function(data){
                    console.log(data);
                    //$("#id04").css({"display":"block","z-index":"90000"});
                    $(".modal").html(data);
                    $(".modal").addClass("active");
                    $("html, body").css("overflow","hidden");
                    $("html, body").css("height","100vh");
                    flag = true;
                });
            }, 1000);
        }
    <?php  }?>

    $("#price , #price2").change(function(){
        var price = Number($(this).val());
        price = price.numberFormat();
        $(this).val(price);
    });

    $(".myword").click(function(){
        $("#wr_content").focus();
        var text = $(this).text();
        if(text != "등록된 간편문구가 없습니다.") {
            var leng = text.length;
            var start = document.getElementById("wr_content").selectionStart;
            var content = $("#wr_content").val();
            var orileng = content.slice(0, start).length;
            var addcontent = content.slice(0, start) + text + "\r\n" + content.slice(start);
            $("#wr_content").val(addcontent);
            setCookie("pd_content", addcontent, '1');
            //setTimeout(function(){
            var cusor = orileng + leng + 1;
            $("#wr_content").selectRange(cusor, cusor);
            $("#wr_content").height(1).height($("#wr_content").prop('scrollHeight') + 12);
            //},100);
        }
    });



    $("#cate_up").change(function(){
        var ca_id = $(this).val();
        var name = $("#cate_up option:selected").text();
        //var text = $("#cate option:checked").text();
        $.ajax({
            url:g5_url+"/mobile/page/ajax/ajax.category2.php",
            method:"POST",
            data:{ca_id:ca_id}
        }).done(function(data){
            $("#cate1").val(name);
            $("#cate2_up option").remove();
            $("#cate2_up").append(data);
            //$("#cate1").val(text);
        });
    });
    $("#cate2_up").change(function(){
        var name = $("#cate2_up option:selected").text();
        $("#cate2").val(name);
    });

    var id2_height = $("#id02 .w3-container").height();


    $("input[id^=image]").each(function(e){
        $(this).on("change",function(){
            readUrl(this,e);
        })
    });

    //게시글 등록 엔터
    $("#sub_title").keyup(function (e) {
        var type2 = $("#wr_type2").val();
        var text = $(this).val();
        <?php if(!$app){?>
        //키보드 32
        if (e.keyCode == 32) {
            text = text.replace(" ","#");
            $(this).val(text);
        }
        <?php }else{ ?>
        text = text.replace(" ","#");
        $(this).val(text);
        <?php }?>

        var cnt = text.split("#");
        if (cnt.length > 10) {
            alert("검색어는 최대 10개까지 등록가능합니다.");
            return false;
        }
    });

    //사진 검증
    <?php //if($filename!=""){?>
    setTimeout(function(){
        $.ajax({
            url:g5_url+"/mobile/page/write_photoload.php",
            method:"post",
            data:{filename:'<?php echo trim($filename);?>',app:'<?php echo $app;?>',app2:"<?php echo $app2;?>",pd_id:"<?php echo $write["pd_id"];?>"}
            ,beforeSend:function(){
               $("textarea, input[type=text]").attr("disabled","disabled");
            }
        }).done(function(data){
             $(".filelist").html('<h2>사진수정</h2>');
             $(".filelist").append(data);
             $(".photo_msg").html('');
             $("textarea, input[type=text]").removeAttr("disabled");
        });
    },1000);
    <?php //}?>

    //textarea resizeHeight
    $("textarea.autosize").height(1).height( $("textarea.autosize").prop('scrollHeight')+12 );
});

// 숫자 타입에서 쓸 수 있도록 format() 함수 추가
Number.prototype.numberFormat = function(){
    if(this==0) return 0;

    var reg = /(^[+-]?\d+)(\d{3})/;
    var n = (this + '');

    while (reg.test(n)) n = n.replace(reg, '$1' + ',' + '$2');

    return n;
};


function readUrl(file,cnt){
    if(file.files && file.files[0]){
        var reader = new FileReader();
        reader.onload = function(e){
            $("#box"+cnt).css("background-image","url('"+e.target.result+"')");
        }
        reader.readAsDataURL(file.files[0]);
    }
}
var itemadd = '',addrs = '';
var lat = '';
var lng = '';
var newlat = '',newlng = '', locs_name ='', locs = '';
var marker;
<?php if($_SESSION["lat"] && $_SESSION["lng"]){?>
lat = "<?php echo $_SESSION["lat"];?>";
lng = "<?php echo $_SESSION["lng"];?>";
<?php }?>
var geocoder = new kakao.maps.services.Geocoder();

var mapContainer = document.getElementById('map'), // 지도를 표시할 div
    mapOption = {
        center: new kakao.maps.LatLng(lat, lng), // 지도의 중심좌표
        level: 5 // 지도의 확대 레벨
    };

var map = new kakao.maps.Map(mapContainer, mapOption); // 지도를 생성합니다


var infowindow = new kakao.maps.InfoWindow({zIndex:9002});

// 지도를 클릭했을때 클릭한 위치에 마커를 추가하도록 지도에 클릭이벤트를 등록합니다
kakao.maps.event.addListener(map, 'click', function(mouseEvent) {
    // 클릭한 위치에 마커를 표시합니다

    searchDetailAddrFromCoords(mouseEvent.latLng, function(result, status) {
        if (status === kakao.maps.services.Status.OK) {
            var data;
            var item="";
            data = { x : mouseEvent.latLng.Ga , y : mouseEvent.latLng.Ha, place_name : result[0].address.address_name};
            console.log(data);
            var i = $(".loc_ul_list li").length;
            item += "<li class='active' onclick=\"setCenter(\'"+mouseEvent.latLng.Ha+"\',\'"+mouseEvent.latLng.Ga+"\',\'"+result[0].address.address_name+"\',\'"+result[0].address.address_name+"\',\'"+i+"\')\" >";
            item += result[0].address.address_name;
            item += "</li>";

            $(".loc_ul_list li").removeClass("active");
            $(".loc_ul_list").prepend(item);

            locitem = '<div class="myloc">'+result[0].address.address_name+'<img src="'+g5_url+'/img/ic_write_close.svg" alt="" class="locsDel"></div>';
            displayMarker(data);
            setCookie("pd_location",result[0].address.address_name,'1');
            setCookie("pd_location_name",result[0].address.address_name,'1');

            newlat = mouseEvent.latLng.Ha;
            newlng = mouseEvent.latLng.Ga;

            locs_name = result[0].address.address_name;
            locs = result[0].address.address_name;
        }
    });
});

function searchDetailAddrFromCoords(coords, callback) {
    // 좌표로 법정동 상세 주소 정보를 요청합니다
    geocoder.coord2Address(coords.getLng(), coords.getLat(), callback);
}

// 장소 검색 객체를 생성합니다
var ps = new kakao.maps.services.Places();

// 키워드 검색 완료 시 호출되는 콜백함수 입니다
function placesSearchCB (data, status, pagination) {
    if (status === kakao.maps.services.Status.OK) {
        marker = null;
        // 검색된 장소 위치를 기준으로 지도 범위를 재설정하기위해
        // LatLngBounds 객체에 좌표를 추가합니다
        var bounds = new kakao.maps.LatLngBounds();
        var item="";
        for (var i=0; i<data.length; i++) {
            displayMarker(data[i]);
            var addr = data[i].address_name.split(" ");
            var addr_simple = "["+addr[0]+" "+addr[1]+"]";
            if(lat && lng) {
                bounds.extend(new kakao.maps.LatLng(lat, lng));
            }else{
                bounds.extend(new kakao.maps.LatLng(data[i].y,data[i].x));
            }
            var address = "";
            if(data[i].road_address_name){
                address = data[i].road_address_name;
            }else{
                address = data[i].address_name;
            }
            item += "<li onclick=\"setCenter(\'"+data[i].y+"\',\'"+data[i].x+"\',\'"+data[i].place_name+"\',\'"+address+"\',\'"+i+"\')\" >";
            item += addr_simple+data[i].place_name;
            item += "</li>";
        }
        if(item!="") {
            $(".loc_ul_list").append(item);
        }else{
            $(".loc_ul_list").append("<li>검색된 목록이 없습니다.</li>");
            return false;
        }
        // 검색된 장소 위치를 기준으로 지도 범위를 재설정합니다
        map.setBounds(bounds);
    }else{
        $(".loc_ul_list").append("<li>검색된 목록이 없습니다.</li>");
        return false;
    }
}

var markers = [];

var imageSrc = '<?php echo G5_IMG_URL?>/view_pin.svg', // 마커이미지의 주소입니다
    imageSize = new kakao.maps.Size(36, 40), // 마커이미지의 크기입니다
    imageOption = {offset: new kakao.maps.Point(18, 40)}; // 마커이미지의 옵션입니다. 마커의 좌표와 일치시킬 이미지 안에서의 좌표를 설정합니다.

function setCenter(lat,lng,place_name,place_address,num) {
    // 이동할 위도 경도 위치를 생성합니다
    var moveLatLon = new kakao.maps.LatLng(lat,lng);

    //markers[num].setMap(map)
    // 지도 중심을 이동 시킵니다
    map.setCenter(moveLatLon);

    locitem = '<div class="myloc">'+place_name+'<img src="'+g5_url+'/img/ic_write_close.svg" alt="" class="locsDel"></div>';
    setCookie("pd_location",place_address,'1');
    setCookie("pd_location_name",place_name,'1');

    selLoc = true;

    newlat = lat;
    newlng = lng;
    locs_name = place_name;
    locs = place_address;
    //$("#pd_lat").val(lat);
    //$("#pd_lng").val(lng);
    //$("#locs_name").val(place_name);
    //$("#locs").val(place_address);
}

// 지도에 마커를 표시하는 함수입니다
function displayMarker(place) {
    var markerImage = new kakao.maps.MarkerImage(imageSrc, imageSize, imageOption);

    // 마커를 생성하고 지도에 표시합니다
    var marker = new kakao.maps.Marker({
        map: map,
        position: new kakao.maps.LatLng(place.y, place.x),
        image:markerImage
    });

    // 마커에 클릭이벤트를 등록합니다
    kakao.maps.event.addListener(marker, 'click', function() {
        // 마커를 클릭하면 장소명이 인포윈도우에 표출됩니다
        infowindow.setContent('<div style="padding:5px;font-size:12px;">' + place.place_name + '</div>');
        infowindow.open(map, marker);
        $(".loc_ul_list li").each(function(){
           if(place.place_name == $(this).text()){
               $(this).addClass("active");
               $(".loc_ul_list li").not($(this)).removeClass("active");
           }
        });
    });

    marker.setMap(map);

    markers.push(marker);
}

// 배열에 추가된 마커들을 지도에 표시하거나 삭제하는 함수입니다
function setMarkers(maps) {
    for (var i = 0; i < markers.length; i++) {
        markers[i].setMap(maps);
    }
}

function fnLocs(){
/*
    if(itemadd != '') {
        if(confirm("고객의 위치정보 보호를 위해 대략적인 위치로 표시됩니다.")) {
            $(".loclist").append(itemadd);
            getLatlng(addrs);
            itemadd = '';
            addrs = '';
        }else{
            itemadd = '';
            addrs = '';
            return false;
        }
    }else{
        if(confirm("직접입력할 경우 게시글에 위치가 제대로 표시 되지 않을 수 있습니다.")) {
            var addr = $("#locs1").val();
            var item = '<div class="myloc">' + addr + '<img src="' + g5_url + '/img/ic_write_close.svg" alt="" class="locsDel"><input type="hidden" value="' + addr + '" name="locs" id=""/></div>';
            $(".loclist").html('');
            $(".loclist").append(item);
            getLatlng(addr);
        }else{
            return false;
        }
    }
*/
    modalClose();
}



function getLatlng(loc){
    var geocoder = new kakao.maps.services.Geocoder();
    // 주소로 좌표를 검색합니다
    geocoder.addressSearch(loc, function(result, status) {
        // 정상적으로 검색이 완료됐으면
        if (status === kakao.maps.services.Status.OK) {
            var lat = result[0].y;
            var lng = result[0].x;
            $("#pd_lat").val(lat);
            $("#pd_lng").val(lng);
        }
    });
}

$(function(){
    var cookie_id = getCookie("<?php echo $member["mb_id"];?>");
    <?php if(!$pd_id){?>
    setCookie("<?php echo $member["mb_id"];?>","write","1");
    <?php }?>
    setCookie("wr_type1","<?php echo $type1;?>","1");
    setCookie("pd_type2","<?php echo $pd_type2;?>","1");
    setCookie("cate1","<?php echo $c;?>","1");
    setCookie("cate2","<?php echo $sc;?>","1");
    setCookie("title","<?php echo $title;?>","1");
    setCookie("filename","<?php echo $filename;?>","1");
    setCookie("videoname","<?php echo $videoname;?>","1");
    setCookie("pd_price","<?php echo $pd_price;?>","1");
    setCookie("pd_price2","<?php echo $pd_price2;?>","1");
    setCookie("pd_video_link","<?php echo $pd_video_link;?>","1");
    setCookie("pd_timeFrom","<?php echo $pd_timeFrom;?>","1");
    setCookie("pd_timeTo","<?php echo $pd_timeTo;?>","1");
    setCookie("pd_discount","<?php echo $pd_discount;?>","1");
    setCookie("pd_price_type","<?php echo $pd_price_type;?>","1");
    setCookie("pd_location","<?php echo $pd_location;?>","1");
    setCookie("pd_location_name","<?php echo $pd_location_name;?>","1");
    setCookie("pd_infos",$("#pd_infos").val(),"1");
    //setCookie("pd_content",$("#wr_content").val(),"1");
    if(cookie_id != "") {
        $("#wr_content").change(function () {
            setCookie("pd_content", $(this).val(), '1');
        });
        $("#pd_timeTo").change(function () {
            setCookie("pd_timeTo", $(this).val(), '1');
        });
        $("#pd_infos").change(function () {
            setCookie("pd_infos", $(this).val(), '1');
        });
        /*$("#pd_infos").change(function () {
            setCookie("pd_infos", $(this).val(), '1');
        });*/
        $("#price").change(function () {
            setCookie("pd_price", $(this).val(), '1');
        });
        $("#price2").change(function () {
            setCookie("pd_price2", $(this).val(), '1');
        });
        $("#sub_title").change(function () {
            setCookie("title", $(this).val(), '1');
        });
        $("#discount_use").change(function () {
            setCookie("pd_discount", $(this).val(), '1');
        });
    }

    setTimeout(function(){
        /*if("<?php echo $pd_id;?>" != ""){
            socket.emit('add connect', {pd_id:"<?php echo $pd_id;?>",cnt_type:"minus"});
            //socket.disconnect();
        }*/
    },1000);
});


function fnLocation(location,lat,lng){
    if(confirm("해당 위치로 등록 하시겠습니까?")){
        $(".write_form form input,.write_form form select,.write_form form textarea").removeAttr("disabled");
        $("#pd_lat").val(lat);
        $("#pd_lng").val(lng);
        $("#locs_name").val(location);
        $("#locs").val(location);
        var locitem = "<div class='myloc'>"+location+"<img src='"+g5_url+"/img/ic_write_close.svg' class='locsDel'></div>";
         $(".loclist").html('');
         $(".loclist").html(locitem);
         modalClose();
    }else{
        return false;
    }
}
var camOn = false;
function fnOnCamSingle(mb_id,index){
    if(camOn==false){
        camOn = true;
        window.android.camereOn2(mb_id, index);
    }
}
function camOff(){
    camOn = false;
}
</script>
<?php
include_once(G5_MOBILE_PATH."/tail.php");
?>