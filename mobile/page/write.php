<?php
include_once("../../common.php");
include_once(G5_EXTEND_PATH."/image.extend.php");
if(!$is_member){
    alert("로그인이 필요합니다.",G5_BBS_URL."/login.php?url=".G5_MOBILE_URL."/page/write.php");
}
?>
    <div class="loader">
        <img src="<?php echo G5_IMG_URL?>/loader.svg" alt="" style="width:100%;position:relative;z-index:1">
        <div style="background-color:#000;opacity: 0.4;width:100%;height:100%;position:absolute;top:0;left:0;"></div>
    </div>
<?php
include_once(G5_MOBILE_PATH."/head.login.php");

$mySetting = sql_fetch("select * from `mysetting` where mb_id = '{$member[mb_id]}'");
$mywords = explode(",",$mySetting["my_word"]);
$cnt = 0;
for($i=0;$i<count($mylocations);$i++){
    if($mylocations[$i]!=""){
        $locChk = true;
        $cnts++;
    }
}
$cateholder = sql_fetch("select info_text from `categorys` where cate_depth = 2 and cate_name = '{$sc}'");
if($pd_id){
    $sql = "select * from `product` where pd_id = '{$pd_id}'";
    $write = sql_fetch($sql);

    if($pd_id){
        $sql = "select * from `categorys` where cate_type= '{$write[pd_type]}' and cate_depth = 1";
        $cate1 = sql_query($sql);
        while($row = sql_fetch_array($cate1)){
            $ca1[] = $row;
            if($row["cate_name"] == $write["pd_cate"]){
                $ca_id = $row["ca_id"];
            }
        }
        $cate2 = sql_query("select * from `categorys` where cate_type= '{$write["pd_type"]}' and cate_depth = 2 and parent_ca_id = '{$ca_id}'");
        while($row = sql_fetch_array($cate2)){
            $ca2[] = $row;
        }
    }
}
if(!$pd_id) {
    $type = $_REQUEST["type"];
    $c = $_REQUEST["cate1"];
    $sc = $_REQUEST["cate2"];
    $title = $_REQUEST["title"];
    $filename = $_REQUEST["filename"];
    $videoname = $_REQUEST["videoname"];
}else{
    $type = $write["pd_type"];
    $c = $write["pd_cate"];
    $sc = $write["pd_cate2"];
    $title = $write["pd_name"];
    $filename = $write["pd_images"];
    $videoname = $write["pd_videos"];
}
?>

<div id="id01" class="w3-modal w3-animate-opacity no-view" style="padding-top:0;">
	<div class="w3-modal-content w3-card-4">
		<div class="w3-container">
			<h2>개인 문구 등록</h2>
			<div>
				<input type="text" value="" name="words" id="words" placeholder="문구입력" required>
			</div>
			<div>
				<input type="button" value="취소" onclick="modalClose()"><input type="button" value="확인" onclick="addWords();" >
			</div>
		</div>
	</div>
</div>
<div id="id02" class="w3-modal w3-animate-opacity no-view" style="padding-top:0;">
    <div class="w3-modal-content w3-card-4">
        <div class="w3-container">
            <h2>거래 선호 위치</h2>
            <div>
                <ul class="modal_sel">
                    <?php for($i=0;$i<count($mylocations);$i++){?>
                        <li class="locSel<?php echo $i;?>" ><?php echo $mylocations[$i];?></li>
                    <?php }?>
                </ul>
            </div>
            <div>
                <input type="button" value="취소" onclick="modalClose()"><input type="submit" value="확인" onclick="modalClose()" >
            </div>
        </div>
    </div>
</div>
<div id="id03" class="w3-modal w3-animate-opacity no-view" style="padding-top:0;">
    <div class="w3-modal-content w3-card-4" >
        <div class="w3-container">
            <h2>거래위치 입력</h2>
            <!--<div style="background-color:#fff;-webkit-border-radius: 3vw ;-moz-border-radius: 3vw ;border-radius: 3vw;padding:2vw;font-size:4vw;margin:3vw 0;">
                <p>아직 등록된 거래 위치가 없으시내요. <br>거래위치는 마이페이지 설정에서 가능합니다.<br>등록하러 가시겠어요?<br><br>게시글을 올리시고 수정하셔도 괜찮아요!</p>
            </div>-->
            <div>
                <div class="map_set">
                    <input type="text" value="" name="locs1" id="locs1" value="<?php if($write["pd_location"]!=""){echo $write["pd_location"];}?>" placeholder="예)신림역 2번 춮구" required>
                    <?php if($chkMobile){?>
                        <img src="<?php echo G5_IMG_URL?>/view_pin_black.svg" alt="" onclick="nowLoc();">
                    <?php }?>
                    <img src="<?php echo G5_IMG_URL?>/setting_map.svg" alt="" onclick="mapSelect()">
                </div>
            </div>
            <div>
                <input type="button" value="취소" onclick="modalClose()"><input type="button" value="확인" onclick="fnLocs();">
            </div>
        </div>
    </div>
</div>

<div class="sub_head">
	<div class="sub_back" onclick="fnBack();">
		<img src="<?php echo G5_IMG_URL?>/ic_menu_back.svg" alt="">
	</div>
    <?php if($pd_id){?>
	<h2>게시물 수정</h2>
    <?php }else{?>
	<h2>상세등록</h2>
    <?php }?>
</div>
<div class="write_form">
	<form action="<?php echo G5_MOBILE_URL?>/page/write_update.php" method="post" name="writefrm" enctype="multipart/form-data">
		<input type="hidden" value="<?php echo $pd_id;?>" name="pd_id">
		<input type="hidden" value="<?php echo $member["mb_id"];?>" name="mb_id">
		<input type="hidden" value="<?php echo $title;?>" name="title" id="title">
		<input type="hidden" value="<?php echo $type;?>" name="type" id="type">
		<input type="hidden" value="<?php echo $c;?>" name="cate1" id="cate1">
		<input type="hidden" value="<?php echo $sc;?>" name="cate2" id="cate2">
		<input type="hidden" value="<?php echo $filename;?>" name="filename" id="filename">
        <input type="hidden" value="<?php echo $videoname;?>" name="videoname" id="videoname">
        <!--<input type="text" value="" name="addr" id="addr">-->
        <input type="hidden" value="<?php echo $write["pd_lat"];?>" name="pd_lat" id="pd_lat">
        <input type="hidden" value="<?php echo $write["pd_lng"];?>" name="pd_lng" id="pd_lng">

		<section class="write_sec">
			<article>
				<div>
                    <?php if($pd_id){?>
                    <div class="sch_top">
                        <select name="cate_up" id="cate_up" class="sel_cate input01s" required >
                            <option value="">1차 카테고리</option>
                            <?php for($i=0;$i<count($ca1);$i++){ ?>
                                <option value="<?php echo $ca1[$i]["ca_id"];?>" <?php if($write["pd_cate"]==$ca1[$i]["cate_name"]){echo "selected";}?>><?php echo $ca1[$i]["cate_name"];?></option>
                            <?php } ?>
                        </select>
                        <select name="cate2_up" id="cate2_up" class="sel_cate input01s" required >
                            <option value="">2차 카테고리</option>
                            <?php for($i=0;$i<count($ca2);$i++){ ?>
                                <option value="<?php echo $ca2[$i]["ca_id"];?>" <?php if($write["pd_cate2"]==$ca2[$i]["cate_name"]){echo "selected";}?>><?php echo $ca2[$i]["cate_name"];?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <?php }?>
					<div class="write_title">
						<input type="text" class="write_input width_80" name="wr_subject" id="wr_subject" value="<?php echo $write["pd_name"];?>" required placeholder="<?php echo ($cateholder["info_text"])?$cateholder["info_text"]:"제목을 입력해주세요.";?>">
						<label class="switch selltype">
							<input type="checkbox" name="sellcode" value="1" <?php if($write["pd_type2"]=="4"){?>checked<?php }?>>
							<span class="slider round" <?php if($write["pd_type2"]=="4"){?>style="text-align:left"<?php }?>><?php if($write["pd_type2"]=="4"){?>구매<?php }else{?>판매<?php }?></span>
						</label>
					</div>
					<div class="write_con">
						<div class="my">
							<?php for($i=0;$i<count($mywords);$i++){
								if($mywords[$i]!=""){
							?>
								<div><?php echo $mywords[$i];?><span class="delBtn">X</span><input type="hidden" name="words[]" value="<?php echo $mywords[$i];?>" id="words" class="words"></div>
							<?php }
							}	?>
							<input type="button" value="+ 추가하기" class="word_add" onclick="addMyword()">
						</div>
						<div class="content">
							<textarea name="wr_content" id="wr_content" class="autosize" placeholder="상세 설명"><?php echo str_replace("<br/>","\n", $write["pd_content"]);?></textarea>
						</div>
					</div>
				</div>
			</article>
		</section>
        <?php if($pd_id){?>
        <section class="write_sec">
            <article>
                <div>
                    <div class="videoArea filelist">
                        <h2>사진수정</h2>
                        <?php if($write["pd_images"]!=""){?>
                        <?php $images = explode(",",$write["pd_images"]); $image_cnt = count($images);?>
                        <?php for($i=0;$i<count($images);$i++){
                                $img = get_images2(G5_DATA_PATH."/product/".$images[$i]);
                            ?>
                                <div class="image_box" id="box<?php echo $i;?>" style="background-image: url('<?php echo G5_DATA_URL;?>/product/<?php echo $img;?>');background-position:center;background-size:cover;background-repeat:no-repeat;">
                                    <label for="images<?php echo $i;?>">
                                        <img src="<?php echo G5_DATA_URL;?>/product/<?php echo $img;?>" alt="image<?php echo $i;?>" style="opacity: 0">
                                        <input type="file" id="images<?php echo $i;?>" name="files[]" style="display:none;" >
                                    </label>
                                </div>
                        <?php }?>
                        <?php }?>
                        <?php
                        if($image_cnt > 0){
                        for($i=$image_cnt;$i<5;$i++){
                            ?>
                            <div class="image_box" id="box<?php echo $i;?>" style="background-image: url('<?php echo G5_IMG_URL;?>/no_images.svg');background-position:center;background-size:cover;background-repeat:no-repeat;">
                                <label for="images<?php echo $i;?>">
                                    <img src="<?php echo G5_IMG_URL;?>/no_images.svg" alt="image<?php echo $i;?>" style="opacity: 0">
                                    <input type="file" id="images<?php echo $i;?>" name="files[]" style="display:none;">
                                </label>
                            </div>
                        <?php }?>
                        <?php }else{
                            for($i=0;$i<5;$i++){
                                ?>
                                <div class="image_box" id="box<?php echo $i;?>" style="background-image: url('<?php echo G5_IMG_URL;?>/no_images.svg');background-position:center;background-size:cover;background-repeat:no-repeat;">
                                    <label for="images<?php echo $i;?>">
                                        <img src="<?php echo G5_IMG_URL;?>/no_images.svg" alt="image<?php echo $i;?>" style="opacity: 0">
                                        <input type="file" id="images<?php echo $i;?>" name="files[]" style="display:none;">
                                    </label>
                                </div>
                            <?php }
                        }?>
                    </div>
                </div>
            </article>
        </section>
<!--        <section class="write_sec">-->
<!--            <article>-->
<!--                <div>-->
<!--                    <div class="videoArea videolist">-->
<!--                        <h2>영상 수정</h2>-->
<!--                        <div>-->
<!--                            <input type="button" id="add" class="addLink" value="">-->
<!--                        </div>-->
<!--                        --><?php //if($write["pd_videos"]!=""){ ?>
<!--                            <video src="--><?php //echo G5_DATA_URL;?><!--/product/--><?php //echo $write["pd_videos"];?><!--"></video>-->
<!--                        --><?php //} ?>
<!--                    </div>-->
<!--                </div>-->
<!--            </article>-->
<!--        </section>-->
        <?php } ?>
		<?php if($filename=="" && $chkMobile == false && !$pd_id){?>
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
		<?php }?>
		<section class="write_sec">
			<article>
				<div>
					<div class="videoArea">
						<h2>동영상 링크 추가</h2>
						<div>
							<input type="text" class="write_input2" name="video_link" id="linkText">
							<input type="button" id="add" class="addLink" value="">
						</div>
					</div>
					<div class="videolinks">
                        <?php if($write["pd_video_link"]!=""){ ?>
                        <?php $linkVideos = explode(",",$write["pd_video_link"]); $linkCount = count($linkVideos); ?>
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
		<section class="write_sec">
			<article>
				<div>
					<div class="videoArea sc">
						<h2>검색어</h2>
						<div>
							<input type="text" value="<?php if($pd_id){echo $write["pd_tag"];}else{echo $title;}?>" name="sub_title" id="sub_title" class="write_input3" />
						</div>
					</div>
					<div class="infor">
						<p>* 검색어 등록시 구분은 "/" 으로 해주세요.</p>
					</div>
				</div>
			</article>
		</section>
		<section class="write_sec pri">
			<article>
				<div>
					<div class="prices">
						<img src="<?php echo G5_IMG_URL?>/ic_won.svg" alt="" > <input type="text" value="<?php echo $write["pd_price"];?>" placeholder="판매가격" name="price" id="price" required class="write_input2 width_80" onkeyup="number_only(this);"/>
                        <?php if($type==1){?><input type="checkbox" name="discount_use" style="display:none;" id="discount_use"><label for="discount_use">흥정가능</label><?php }?>
                        <?php if($type==2){?>계약금<?php }?>
                    </div>
                    <?php if($type==2){?>
                    <div class="prices step2">
                        <img src="<?php echo G5_IMG_URL?>/ic_won.svg" alt="" > <input type="text" value="<?php echo $write["pd_price"];?>" placeholder="10,000" name="price2" id="price2" required class="write_input2 width_80" onkeyup="number_only(this);"/> 거래완료금
					</div>
                    <?php }?>
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
                        <?php if($pd_id){?>
                            <div class="myloc">
                                <?php echo $write["pd_location"];?>
                                <img src="<?php echo G5_IMG_URL?>/ic_write_close.svg" alt="" class="locsDel">
                                <input type="hidden" value="<?php echo $write["pd_location"];?>" name="locs" id="">
                            </div>
                        <?php } ?>
					</div>
				</div>
			</article>
		</section>
        <?php if($type == 2){?>
        <section class="write_sec avil">
            <article>
                <div>
                    <div class="videoArea sc avility">
                        <h2>거래 조건 및 유의 사항</h2>
                    </div>
                    <div class="">
                        <textarea name="pd_infos" id="pd_infos" cols="30" rows="10" required placeholder="거래시 유의사항을 적어주세요. 1:1대화에서 구매 안내에 사용됩니다."></textarea>
                    </div>
                </div>
            </article>
        </section>
        <?php }?>
		<div class="submit_btns">
			<input type="submit" value="확인" class="submit_btn" onclick="fnSubmit();">
		</div>
	</form>
    <div id="map_sel" style="">
        <img src="<?php echo G5_IMG_URL?>/view_pin.svg" alt="" class="map_pin">
        <div id="map" style="width:100%;height:40vh;"></div>
        <div style="padding:2.8vw 0;text-align:center;">
            <input type="button" value="취소" onclick="mapSelect('')" style="border:none;width:48%;-webkit-border-radius: 10vw;-moz-border-radius: 10vw;border-radius: 10vw;background-color: #000;color:#fff;font-size:3vw;padding:2vw 0;">
            <input type="button" value="확인" onclick="mapSet();" style="border:none;width:48%;-webkit-border-radius: 10vw;-moz-border-radius: 10vw;border-radius: 10vw;background-color: #ffe100;color:#000;font-size:3vw;padding:2vw 0;">
        </div>
    </div>
</div>

<div class="bg" style="width:100vw;height:100vh;background-color:#eee;position:fixed;top:0;z-index:-1"></div>
<script>
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

function fnBack(){
    <?php if($pd_id){?>
	if(confirm("상품/능력 등록을 취소하시겠습니까?")){
		location.href=g5_url;
	}
	<?php }else{ ?>
    if(confirm("상품/능력 수정을 취소하시겠습니까?")){
        location.href=g5_url;
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
	location.hash="#modal";
	//var item = '<div>'+mytext+'<span class="delBtn">X</span><input type="hidden" name="loc[]" value="'+mytext+'"></div>';
}

$(document).on("click",".delBtn",function(){
	$(this).parent().remove();
});

//거래선호위치관련
function addLoc(){
    <?php if($locChk==true){?>
    var cnt = Number("<?php echo $cnts;?>");
	$("[class^=myloc]").each(function(e){
		for(var i = 0;i < cnt;i++){
			if( $(this).text() == $(".locSel"+i).text() ){
				$(".locSel"+i).addClass("active");
			}
		}
	});
	$("#id02").css("display","block");
	var height = $("#id02 .w3-container").height();
	$(".w3-modal-content").css({"height":height+"px","margin-top":"-"+(height/2)+"px"});
	<?php }else{?>
    $("#id03").css("display","block");
    <?php }?>
    location.hash="#modal";
}
$(".modal_sel li").click(function(){
	var text = $(this).text();
	//$(this).toggleClass("active");
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
		var item = '<div class="myloc">'+text+'<img src="'+g5_url+'/img/ic_write_close.svg" alt="" class="locsDel"><input type="hidden" value="'+text+'" name="locs" id=""/></div>';
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
    $("#locs1").val('');
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
    $('.loader').show();
}

var mapon = false;
function mapSelect(num){
    if(mapon==false) {
        $("#setnum").val(num);
        $("#map_sel").css({"bottom": "0","top":"50%","margin-top":"-25vh"});
        mapon = true;
    }else if(mapon==true || num == ''){
        $("#setnum").val('');
        $("#addr").val('');
        $("#map_sel").css({"bottom": "-50vh","top":"unset","margin-top":"unset"});
        mapon = false;
    }
}

function mapSet(){
    var num = $("#setnum").val();
    var addr = $("#addr").val();
    $("input[name^='mylocation']").each(function(e){
        if((e+1)==num){
            $(this).val(addr);
        }
    });
    $("#setnum").val('');
    $("#addr").val('');
    $("#map_sel").css({"bottom": "-50vh","top":"unset","margin-top":"unset"});
    mapon = false;
}

function nowLoc(num){
    if(lat && lng){
        console.log(lat + "//" + lng);
    }else {
        var latlng = window.android.getLocation();
        console.log(latlng);
    }

}

$(function(){

    $("#cate_up").change(function(){
        var ca_id = $(this).val();
        //var text = $("#cate option:checked").text();
        $.ajax({
            url:g5_url+"/mobile/page/ajax/ajax.category2.php",
            method:"POST",
            data:{ca_id:ca_id}
        }).done(function(data){
            console.log(data);
            $("#cate2_up option").remove();
            $("#cate2_up").append(data);
            //$("#cate1").val(text);
        });
    });

    var id2_height = $("#id02 .w3-container").height();


    $("input[id^=image]").each(function(e){
        $(this).on("change",function(){
            console.log(this);
            readUrl(this,e);
        })
    })
});

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
var lat = '33.450701';
var lng = '126.570667';
<?php if($_SESSION["lat"] && $_SESSION["lng"]){?>
lat = "<?php echo $_SESSION["lat"];?>";
lng = "<?php echo $_SESSION["lng"];?>";
<?php }?>
var geocoder = new daum.maps.services.Geocoder();

var mapContainer = document.getElementById('map'), // 지도를 표시할 div
    mapOption = {
        center: new daum.maps.LatLng(lat, lng), // 지도의 중심좌표
        level: 5 // 지도의 확대 레벨
    };

var map = new daum.maps.Map(mapContainer, mapOption); // 지도를 생성합니다

// 마커가 표시될 위치입니다
var markerPosition  = new daum.maps.LatLng(map.getCenter());

// 마커를 생성합니다
var marker = new daum.maps.Marker({
    position: markerPosition
});

// 마커가 지도 위에 표시되도록 설정합니다
marker.setMap(map);

marker.setDraggable(true);


// 지도가 이동, 확대, 축소로 인해 중심좌표가 변경되면 마지막 파라미터로 넘어온 함수를 호출하도록 이벤트를 등록합니다
daum.maps.event.addListener(map, 'center_changed', function() {
    marker=null;
    // 지도의 중심좌표를 얻어옵니다
    var latlng = map.getCenter();

    searchDetailAddrFromCoords(latlng, function(result, status) {
        if (status === daum.maps.services.Status.OK) {
            $(".loclist").html('');
            $("#locs1").val(result[0].address.region_1depth_name+" "+result[0].address.region_2depth_name+" "+result[0].address.region_3depth_name);
            itemadd = '<div class="myloc">'+result[0].address.region_1depth_name+" "+result[0].address.region_2depth_name+" "+result[0].address.region_3depth_name+'<img src="'+g5_url+'/img/ic_write_close.svg" alt="" class="locsDel"><input type="hidden" value="'+result[0].address.region_1depth_name+" "+result[0].address.region_2depth_name+" "+result[0].address.region_3depth_name+'" name="locs" id=""/></div>';
            addrs = result[0].address.region_1depth_name+" "+result[0].address.region_2depth_name+" "+result[0].address.region_3depth_name;
        }
    });

});

function searchDetailAddrFromCoords(coords,callback) {
    // 좌표로 법정동 상세 주소 정보를 요청합니다
    geocoder.coord2Address(coords.getLng(), coords.getLat(),callback);
}

function fnLocs(){

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

    modalClose();
}



function getLatlng(loc){
    var geocoder = new daum.maps.services.Geocoder();
    // 주소로 좌표를 검색합니다
    geocoder.addressSearch(loc, function(result, status) {
        // 정상적으로 검색이 완료됐으면
        if (status === daum.maps.services.Status.OK) {
            var lat = result[0].y;
            var lng = result[0].x;
            $("#pd_lat").val(lat);
            $("#pd_lng").val(lng);
        }
    });
}
</script>
<?php
include_once(G5_MOBILE_PATH."/tail.php");
?>