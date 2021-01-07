<?php
include_once("../../../common.php");
include_once (G5_LIB_PATH."/thumbnail.lib.php");

include(G5_MOBILE_PATH."/page/ajax/ajax.index.list_set.php");

$adchk = false;

for($i=0;$i<count($list);$i++){

    $sql = "select count(*) as cnt from `order` where pd_id = '{$list[$i]["pd_id"]}' and od_status = 1 and od_pay_status = 2";
    $chkres = sql_fetch($sql);
    if($chkres["cnt"] > 0) continue;

    if($list[$i]["pd_lat"]==0 && $list[$i]["pd_lng"]==0){
        $dist = "정보없음";
    }else {
        $dist = round($list[$i]["distance"],1) . "km";
    }
    switch($list[$i]["pd_type"]){
        case "1":
            $type = "4";
            break;
        case "2":
            $type = "8";
            break;
    }

    for($j=0;$j<count($wished);$j++){
        if($wished[$j]["pd_id"]==$list[$i]["pd_id"]){
            $flag = true;
            break;
        }else{
            $flag = false;
        }
    }

    switch ($list[$i]["pd_price_type"]){
        case 0:
            $pd_price_type = "<span class='bg2'>회</span>";
            break;
        case 1:
            $pd_price_type = "<span class='bg1'>시</span>";
            break;
        case 2:
            $pd_price_type = "<span class='bg3'>일</span>";
            break;
    }

    $wished_cnt = sql_fetch("select count(*)as cnt from `wish_product` where pd_id = {$list[$i]["pd_id"]}");
    switch (strlen($wished_cnt["cnt"])){
        case 1: //일
            $wishedcnt = $wished_cnt["cnt"];
            break;
        case 2: //십
            $wishedcnt = $wished_cnt["cnt"];
            break;
        case 3: //백
            $wishedcnt = $wished_cnt["cnt"];
            break;
        case 4: //천
            $wishedcnt = substr($wished_cnt["cnt"],0,1)." T";
            break;
        case 5: //만
            $wishedcnt = substr($wished_cnt["cnt"],0,1)." M";
            break;
        case 6: //십만
            $wishedcnt = substr($wished_cnt["cnt"],0,2)." M";
            break;
        case 7: //백만
            $wishedcnt = substr($wished_cnt["cnt"],0,3)." M";
            break;
    }

    if($list[$i]["pd_date"]) {
        $loc_data = $list[$i]["pd_date"];
        if($list[$i]["pd_update"]){
            $loc_data = $list[$i]["pd_update"];
        }
        $now = date("Y-m-d H:i:s");
        $time_gep = round((strtotime($now) - strtotime($loc_data)) / 3600);
        if($time_gep == 0){
            $time_gep = "몇 분전";
        }else if($time_gep < 24){
            $time_gep = $time_gep."시간 전";
        }else if($time_gep > 24){
            $time_gep = round($time_gep / 24)."일 전";
        }
    }else{
        $time_gep = "정보 없음";
    }

    if($page >= 2) {
        $addcnt = (($page * 10) - 10) + ($i + 1);
    }else {
        $addcnt = $i;
    }

    if(count($listadd) > 0 && $adchk == false) {
        //for ($k = 0; $k < count($listadd); $k++) {
            //if ($type2 == "8") {
            $rand = rand(0, count($listadd) - 1);
            if ($listadd[$rand]["ad_sort"] == $addcnt) {
                $adchk = true;
                ?>
                <div class="grid__item ad_list <?php if ($list_type == "list") { echo " type_list";} ?> " onclick="location.href='<?php echo $listadd[$rand]["ad_link"]; ?>'">
                    <div>
                        <div class="ad_mark">AD</div>
                        <?php if ($listadd[$rand]["ad_photo"] != "") {?>
                            <div class="item_images" style="background-image:url('<?php echo G5_DATA_URL ?>/product/<?php echo $listadd[$rand]["ad_photo"]; ?>');background-repeat:no-repeat;background-size:cover;background-position:center;min-height: 28vw;">
                                <img src="<?php echo G5_DATA_URL ?>/product/<?php echo $listadd[$rand]["ad_photo"]; ?>" alt="ad" class="main" style="opacity:0">
                            </div>
                        <?php } ?>
                        <div class="bottom">
                            <div>
                                <h1 class="ad_h1"><?php echo $listadd[$rand]["ad_subject"]; ?></h1>
                            </div>
                            <?php if ($listadd[$rand]["ad_con"]) { ?>
                                <h2 class="ad_h2"><?php echo $listadd[$rand]["ad_con"]; ?></h2>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="clear"></div>
                </div>
                <?php
                unset($listadd[$rand]);
            }
            array_filter($listadd);
        //}
            /* else if ($type2 == "4") {
                if ($listadd[$k]["ad_sort2"] == $addcnt) {
                    */?><!--
                    <div class="grid__item ad_list <?php /*if ($list_type == "list") {echo " type_list";} */?>" onclick="location.href='<?php /*echo $listadd[$k]["ad_link"]; */?>'">
                        <div>
                            <div class="ad_mark">AD</div>
                            <?php /*if ($listadd[$k]["ad_photo"] != "") {
                                */?>
                                <div class="item_images"
                                     style="background-image:url('<?php /*echo G5_DATA_URL */?>/product/<?php /*echo $listadd[$k]["ad_photo"]; */?>');background-repeat:no-repeat;background-size:cover;background-position:center;min-height: 28vw;">
                                    <img src="<?php /*echo G5_DATA_URL */?>/product/<?php /*echo $listadd[$k]["ad_photo"]; */?>"
                                         alt="ad" class="main" style="opacity:0">
                                </div>
                            <?php /*} */?>
                            <div class="bottom">
                                <div>
                                    <h1 class="ad_h1"><?php /*echo $listadd[$k]["ad_subject"]; */?></h1>
                                </div>
                                <?php /*if ($listadd[$k]["ad_con"]) { */?>
                                    <h2 class="ad_h2"><?php /*echo $listadd[$k]["ad_con"]; */?></h2>
                                <?php /*} */?>
                            </div>

                        </div>
                        <div class="clear"></div>
                    </div>
                    --><?php
/*                }
            }*/
        //}
    }
    ?>
<div class="grid__item ajax_list <?php if($img1!=""){echo "images_list";}?> <?php if($flag){echo "wishedon";}?>  <?php if($list_type=="list"||$_SESSION["list_type"]=="list"){echo " type_list";}?> <?php if($list[$i]["pd_blind"]>=10){?>blinds<?php }?>" id="list_<?php echo $list[$i]['pd_id'];?>">
    <?php //echo $sqls;?>
    <?php if($list[$i]["pd_blind"]>=10){?>
        <div class="blind_bg">
            <input type="button" value="사유보기" class="list_btn"  >
        </div>
    <?php }?>
    <div class="wished_active <?php if($list[$i]["pd_type"]==2){?>bg2<?php }?>" style="" id="heart_<?php echo $list[$i]["pd_id"];?>">
        <div class="wished_ani">
            <img class="heart" src="<?php echo G5_IMG_URL;?>/ic_wish_on<?php if($list[$i]["pd_type"]==2){?>2<?php }?>.svg" alt="">
        </div>
    </div>
    <div class="in_grid">
		<?php if($list[$i]["pd_images"]!=""){
            $img = explode(",",$list[$i]["pd_images"]);
            $img[0] = trim($img[0]);
            if(!is_file(G5_DATA_PATH."/product/thumb-".$img[0])) {
                $img1 = get_images(G5_DATA_PATH . "/product/" . $img[0], 300, '');
            }else{
                $img1 = "thumb-".$img[0];
            }
            if(is_file(G5_DATA_PATH."/product/".$img1)){
		?>
		<div class="item_images" style="background-repeat:no-repeat;background-position:center;min-height: 28vw;background-image:url('<?php echo G5_DATA_URL?>/product/<?php echo $img1;?>');background-size:cover;" >
            <?php if($img1!=""){?>
                <img src="<?php echo G5_DATA_URL?>/product/<?php echo $img1;?>" alt="" class="main" >
            <?php }else{ ?>
                <img src="<?php echo G5_IMG_URL?>/no-profile.svg" alt="" class="main" >
            <?php }?>
		</div>
		<?php }else{
		$tags = explode("/",$list[$i]["pd_tag"]);
		$rand = rand(1,13);
		?>
		<div class="bg rand_bg<?php echo $rand;?> item_images" >
			<div class="tags">
                <?php //for($k=0;$k<count($tags);$k++){
					$rand_font = rand(3,6);
				?>
				<div class="rand_size<?php echo $rand_font;?>"><?php echo $list[$i]["pd_tag"];?></div>
				<?php //}?>
			</div>
			<div class="clear"></div>
		</div>
		<?php }?>
		<?php }else{
            $tags = explode("/",$list[$i]["pd_tag"]);
            $rand = rand(1,13);
        ?>
        <div class="bg rand_bg<?php echo $rand;?> item_images" >
            <div class="tags">
                <?php //for($k=0;$k<count($tags);$k++){
                    $rand_font = rand(3,6);
                    ?>
                    <div class="rand_size<?php echo $rand_font;?>"><?php echo $list[$i]["pd_tag"];?></div>
                <?php //}?>
            </div>
            <div class="clear"></div>
        </div>
        <?php }?>
		<div class="top">
			<div>
                <h2 style="font-weight:normal"><?php echo ($list[$i]["mb_level"]==4)?"<img src='".G5_IMG_URL."/ic_pro.svg'>":"　";?><?php // echo "<span style='color:gray'><br><br>합계:".$list[$i]["sums"]."//최신:".$list[$i]["updates"]."//조회:".$list[$i]["hits"]."//거리:".$list[$i]["distances"]."//가격:".$list[$i]["prices"]."//추천:".$list[$i]["recoms"]."//".$list[$i]["pd_update"]."</span>";?></h2>
				<div>
					<ul>
                        <?php /*if($list_type=="list"||$_SESSION["list_type"]=="list"){*/?><!--
                        <li style="margin-right:2vw;"><?php /*echo $time_gep;*/?></li>
                        --><?php /*}*/?>
						<li><img src="<?php echo G5_IMG_URL?>/ic_hit<?php if($_SESSION["list_type"] == "grid"){echo "_list";}?>.svg" alt=""><span><?php echo $list[$i]["pd_hits"];?></span></li>
						<?php if($app || $app2){?>
						<li><img src="<?php echo G5_IMG_URL?>/ic_loc<?php if($_SESSION["list_type"] == "grid"){echo "_list";}?>.svg" alt=""><span><?php echo $dist;?></span></li>
						<?php }?>
					</ul>
				</div>
			</div>
		</div>
        <?php if($list[$i]["new"]==true){ ?>
            <div class="new" style="">
                <img src="<?php echo G5_IMG_URL?>/ic_list_new.svg" alt="">
            </div>
        <?php }?>
		<div class="bottom">
            <?php if($list[$i]["pd_name"]){
                switch($list[$i]["pd_type2"]){
                    case "4":
                        $pt2 = "[삽니다]";
                        break;
                }
                ?>
                <h2><?php echo ($pt2)?$pt2." ".$list[$i]["pd_tag"]:$list[$i]["pd_tag"];?></h2>
            <?php }?>
			<div>

                <?php if($list[$i]["pd_type2"]==4){?>
                    <?php if($list[$i]["pd_price"]==0){?>
                        <h1>가격 제시</h1>
                    <?php }else{ ?>
                        <h1>￦ <?php echo number_format($list[$i]["pd_price"]+$list[$i]["pd_price2"]);?> <?php if($list[$i]["pd_type"]==2 && $list[$i]["pd_type2"]==8){echo $pd_price_type;}?></h1>
                    <?php }?>
                <?php }else{?>
                    <?php if($list[$i]["pd_type"]==2){$class = "bgs2";}else{$class="";}?>
                    <h1 <?php if($list[$i]["pd_images"]!="" && $list[$i]["pd_price"]+$list[$i]["pd_price2"]==0){?>style="text-shadow: none"<?php }?>> <?php echo ($list[$i]["pd_price"]+$list[$i]["pd_price2"]==0)?"<span class='color {$class}'>무료나눔</span>":"￦ ".number_format($list[$i]["pd_price"]+$list[$i]["pd_price2"]);?> <?php if($list[$i]["pd_type"]==2 && $list[$i]["pd_type2"]==8){echo $pd_price_type;}?></h1>
                <?php }?>
                <?php if($wished_cnt["cnt"]>0 && $flag){?>
                    <div class="list_wished_cnt active wished <?php if($list[$i]["pd_type"]==2){?>bg2<?php }?>"><?php echo $wishedcnt;?></div>
                <?php }else{?>
                    <div class="list_wished_cnt wished <?php if($list[$i]["pd_type"]==2){?>bg2<?php }?>"><?php echo $wishedcnt;?></div>
				<?php }?>
			</div>
		</div>
		
	</div>
</div>

<?php }

if($adchk==false) {
    if ($total == 0 && count($listadd) > 0 && $page == 1) {
        //if(count($listadd)>1) {
        $rand = rand(0, count($listadd) - 1);
        //}
        //for($k=0;$k<count($listadd);$k++){
        /*if(count($listadd)>1) {
            if ($k == $rand) continue;
        }*/
        ?>
        <div class="grid__item ad_list <?php if ($list_type == "list") {echo " type_list";} ?>" onclick="location.href='<?php echo $listadd[$rand]["ad_link"]; ?>'">
            <div>
                <div class="ad_mark">AD</div>
                <?php if ($listadd[$rand]["ad_photo"] != "") {?>
                    <div class="item_images" style="background-image:url('<?php echo G5_DATA_URL ?>/product/<?php echo $listadd[$rand]["ad_photo"]; ?>');background-repeat:no-repeat;background-size:cover;background-position:center;min-height: 28vw;">
                        <img src="<?php echo G5_DATA_URL ?>/product/<?php echo $listadd[$rand]["ad_photo"]; ?>" alt="ad" class="main" style="opacity:0">
                    </div>
                <?php } ?>
                <div class="bottom">
                    <div>
                        <h1 class="ad_h1"><?php echo $listadd[$rand]["ad_subject"]; ?></h1>
                    </div>
                    <?php if ($listadd[$rand]["ad_con"]) { ?>
                        <h2 class="ad_h2"><?php echo $listadd[$rand]["ad_con"]; ?></h2>
                    <?php } ?>
                </div>
            </div>
            <div class="clear"></div>
        </div>
        <?php
        //}
        $listadd=null;
    } else if ($total > count($listadd) && count($listadd) > 0 && $page == 1) {

        $rand = rand(0, count($listadd) - 1);
        //for($i=0;$i<count($listadd);$i++){
        //if($k==$rand) continue;
        ?>
        <div class="grid__item ad_list <?php if ($list_type == "list") {echo " type_list";} ?>" onclick="location.href='<?php echo $listadd[$rand]["ad_link"]; ?>'">
            <div>
                <div class="ad_mark">AD</div>
                <?php if ($listadd[$rand]["ad_photo"] != "") {?>
                    <div class="item_images" style="background-image:url('<?php echo G5_DATA_URL ?>/product/<?php echo $listadd[$rand]["ad_photo"]; ?>');background-repeat:no-repeat;background-size:cover;background-position:center;min-height: 28vw;">
                        <img src="<?php echo G5_DATA_URL ?>/product/<?php echo $listadd[$rand]["ad_photo"]; ?>" alt="ad" class="main" style="opacity:0">
                    </div>
                <?php } ?>
                <div class="bottom">
                    <div>
                        <h1 class="ad_h1"><?php echo $listadd[$rand]["ad_subject"]; ?></h1>
                    </div>
                    <?php if ($listadd[$rand]["ad_con"]) { ?>
                        <h2 class="ad_h2"><?php echo $listadd[$rand]["ad_con"]; ?></h2>
                    <?php } ?>
                </div>
            </div>
            <div class="clear"></div>
        </div>
        <?php
        //}
        $listadd=null;
    } else if ($total <= count($listadd) && count($listadd) > 0 && $page == 1) {
        $rand = rand(0, count($listadd) - 1);
        //for($i=0;$i<count($listadd);$i++){
        //if($k==$rand) continue;
        ?>
        <div class="grid__item ad_list <?php if ($list_type == "list") {echo " type_list";} ?>" onclick="location.href='<?php echo $listadd[$rand]["ad_link"]; ?>'">
            <div>
                <div class="ad_mark">AD</div>
                <?php if ($listadd[$rand]["ad_photo"] != "") { ?>
                    <div class="item_images" style="background-image:url('<?php echo G5_DATA_URL ?>/product/<?php echo $listadd[$rand]["ad_photo"]; ?>');background-repeat:no-repeat;background-size:cover;background-position:center;min-height: 28vw;">
                        <img src="<?php echo G5_DATA_URL ?>/product/<?php echo $listadd[$rand]["ad_photo"]; ?>" alt="ad" class="main" style="opacity:0">
                    </div>
                <?php } ?>
                <div class="bottom">
                    <div>
                        <h1 class="ad_h1"><?php echo $listadd[$rand]["ad_subject"]; ?></h1>
                    </div>
                    <?php if ($listadd[$rand]["ad_con"]) { ?>
                        <h2 class="ad_h2"><?php echo $listadd[$rand]["ad_con"]; ?></h2>
                    <?php } ?>
                </div>

            </div>
            <div class="clear"></div>
        </div>
        <?php
        //}
        $listadd=null;
    }
}
if(count($list)==0){echo "no-list";}


if(count($list)!=0){
?>
<script>
    $(function(){
        // initial items reveal
        //그리드 아이템 가로 스크롤체크
        $(".ajax_list").each(function(e){

            var id = $(this).attr("id");
            var item = document.getElementById(id);
            var swiper = new Hammer(item);


            swiper.on('swipeleft',function(e){
                $.ajax({
                    url:g5_url+"/mobile/page/ajax/ajax.remove_item.php",
                    method:"POST",
                    data:{pd_id:pd_id}
                }).done(function(data){
                    if(data=="1") {
                        $("#"+id).remove();
                        $grid.masonry('remove', this).masonry("layout");
                        $("#mobile_header #mobile_menu_btn").addClass("active");
                        $(".trash-ani").addClass("active");
                        $("#debug").addClass("active");
                        $("#debug").html("휴지통으로 이동되었습니다.");
                        setTimeout(function(){$(".trash-icon").addClass("active");},1000);
                        setTimeout(removeDebug, 1500);
                    }else if(data=="3"){
                        $("#debug").addClass("active");
                        $("#debug").html("자신의 글은 휴지통에 보낼 수 없습니다.");
                        setTimeout(removeDebug, 1500);
                    }
                });
            });
            swiper.on("swiperight",function(e){

                $.ajax({
                    url:g5_url+"/mobile/page/ajax/ajax.remove_item.php",
                    method:"POST",
                    data:{pd_id:pd_id}
                }).done(function(data){
                    if(data=="1") {
                        $("#"+id).remove();
                        $grid.masonry('remove', this).masonry("layout");
                        $("#mobile_header #mobile_menu_btn").addClass("active");
                        $(".trash-ani").addClass("active");
                        $("#debug").addClass("active");
                        $("#debug").html("휴지통으로 이동되었습니다.");
                        setTimeout(function(){$(".trash-icon").addClass("active");},1000);
                        setTimeout(removeDebug, 1500);
                    }else if(data=="3"){
                        $("#debug").addClass("active");
                        $("#debug").html("자신의 글은 휴지통에 보낼 수 없습니다.");
                        setTimeout(removeDebug, 1500);
                    }
                });
            });

            var swiperm = new Hammer.Manager(item);
            var pd_id = id.replace("list_","");


            // Tap recognizer with minimal 2 taps
            swiperm.add( new Hammer.Tap({ event: 'doubletap', taps: 2 }) );
            // Single tap recognizer
            swiperm.add( new Hammer.Tap({ event: 'singletap' }) );

            // we want to recognize this simulatenous, so a quadrupletap will be detected even while a tap has been recognized.
            swiperm.get('doubletap').recognizeWith('singletap');
            // we only want to trigger a tap, when we don't have detected a doubletap
            swiperm.get('singletap').requireFailure('doubletap');

            swiperm.on("singletap ", function(ev) {
                if(ev.type == "singletap"){
                    if(tabchk==false) {
                        fn_viewer(pd_id);
                        tabchk=true;
                    }
                    if(tabchk==true){
                        setTimeout(function(){
                            tabchk = false;
                            console.log("wow" + tabchk);
                        },1000);
                    }
                }
            });

            swiperm.on("doubletap",function(ev){
                if(ev.type == "doubletap"){
                    if($("#"+id).hasClass("wishedon")){
                        $("#"+id).removeClass("wishedon");
                        var wished = $("#"+id).children().find($(".wished"));
                        wished.removeClass("element-animation");
                        wished.removeClass("active");
                        $("#heart_"+pd_id).removeClass("active");
                        $.ajax({
                            url:g5_url+"/mobile/page/ajax/ajax.wish.php",
                            method:"POST",
                            data:{pd_id:pd_id,mode:"delete",mb_id:"<?php echo $wished_id;?>"}
                        }).done(function(data){
                            console.log(data);
                        });
                    }else{
                        $("#"+id).addClass("wishedon");
                        var wished = $("#"+id).children().find($(".wished"));
                        wished.removeClass("element-animation");
                        wished.addClass("element-animation");
                        wished.addClass("active");
                        $("#heart_"+pd_id).addClass("active");
                        $.ajax({
                            url:g5_url+"/mobile/page/ajax/ajax.wish.php",
                            method:"POST",
                            data:{pd_id:pd_id,mode:"insert",mb_id:"<?php echo $wished_id;?>"}
                        }).done(function(data){
                            console.log(data);
                        });
                    }
                }
            });
        });
    });

    window.onload = function() {
        var placeholder = document.querySelector('.in_grid'),
            small = placeholder.querySelector('.item_images')

        // 1: load small image and show it
        var img = new Image();
        img.src = small.src;
        img.onload = function () {
            small.classList.add('loaded');
        };

        // 2: load large image
        var imgLarge = new Image();
        imgLarge.src = placeholder.dataset.large;
        imgLarge.onload = function () {
            imgLarge.classList.add('loaded');
        };
        placeholder.appendChild(imgLarge);
    }

</script>

<?php }?>



