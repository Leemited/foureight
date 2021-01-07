<?php

?>
<div id="container" >
	<!--<input type="hidden" value="<?php /*if($schopt["sc_type"]){echo $schopt["sc_type"];}else{echo "1";}*/?>" name="write_type" id="write_type">-->
    <!--<div class="write" >
        <div class="write_btn" onclick="<?php /*if(!$is_member){*/?>alert('로그인이 필요합니다.');location.href=g5_url+'/mobile/page/login_intro.php'; <?php /*}else if($member["mb_certify"]==""){ */?>alert('본인인증이 필요합니다.');location.href=g5_url+'/mobile/page/mypage/hp_certify.php';<?php /*}else if($member["mb_id"]){ */?>fnwrite(4);<?php /*} */?> ">
            <?php /*if($set_type == 1){*/?>
                <img src="<?php /*echo G5_IMG_URL*/?>/ic_write_btn.svg" alt="">
            <?php /*}else{ */?>
                <img src="<?php /*echo G5_IMG_URL*/?>/ic_write_btn_2.svg" alt="">
            <?php /*}*/?>
        </div>
        <div class="write_btn2" onclick="<?php /*if(!$is_member){*/?>alert('로그인이 필요합니다.');location.href=g5_url+'/mobile/page/login_intro.php'; <?php /*}else if($member["mb_certify"]==""){ */?>alert('본인인증이 필요합니다.');location.href=g5_url+'/mobile/page/mypage/hp_certify.php';<?php /*}else if($member["mb_id"]){ */?>fnwrite(8);<?php /*} */?> ">
            <?php /*if($set_type == 1){*/?>
                <img src="<?php /*echo G5_IMG_URL*/?>/ic_write_btn.svg" alt="">
            <?php /*}else{ */?>
                <img src="<?php /*echo G5_IMG_URL*/?>/ic_write_btn_2.svg" alt="">
            <?php /*}*/?>
        </div>
        <div class="text <?php /*if($set_type==2 || $set_type ==""){*/?>bg2<?php /*}*/?>" >
            <div>판매글 올리기</div>
            <div>구매글 올리기</div>
        </div>
    </div>-->
    <section class="main_list">
        <article class="post" id="post">
            <div class="list_item grid are-images-unloaded" id="test">
                <?php
                $ad_check = false;
                for($i=0;$i<count($list);$i++){
                    if($list[$i]["pd_lat"]==0 && $list[$i]["pd_lng"]==0){
                        $dist = "정보없음";
                    }else {
                        $dist = round($list[$i]["distance"],1) . "km";
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

                    if(count($listadd) > 0 && $adchk == false) {
                        if($page >= 2) {
                            $addcnt = (($page * 10) - 10) + ($i + 1);
                        }else {
                            $addcnt = $i;
                        }
                        //for ($k = 0; $k < count($listadd); $k++) {
                        /*if ($page == 2) {
                            $addcnt = (($page * 10) - 10) + $i;
                        } else {
                            $addcnt = $i;
                        }*/

                        $rand = rand(0, count($listadd) - 1);
                        if ($listadd[$rand]["ad_sort"] == $addcnt) {
                            $adchk = true;
                            ?>
                            <div class="grid__item ad_list <?php if ($_SESSION["list_type"] == "list") { echo " type_list"; } ?>" onclick="location.href='<?php echo $listadd[$rand]["ad_link"]; ?>'">
                                <div>
                                    <div class="ad_mark">AD</div>
                                    <?php if ($listadd[$rand]["ad_photo"] != "") {?>
                                        <div class="item_images" style="background-repeat:no-repeat;background-size:cover;background-position:center;min-height: 28vw;background-image:url('<?php echo G5_DATA_URL; ?>/product/<?php echo $listadd[$rand]["ad_photo"]; ?>')">
                                            <img src="<?php echo G5_DATA_URL ?>/product/<?php echo $listadd[$rand]["ad_photo"]; ?>" alt="ad" class="main" style="opacity: 0">
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
                        }
                        //}
                    }
                    ?>
                    <div class="grid__item <?php if($flag){echo "wishedon";}?> <?php if($_SESSION["list_type"]=="list"){echo " type_list";}?> <?php if($list[$i]["pd_blind"]>=10){?>blinds<?php }?>" id="list_<?php echo $list[$i]['pd_id'];?>">
                        <?php if($list[$i]["pd_blind"]>=10){?>
                            <div class="blind_bg">
                                <input type="button" value="사유보기" class="list_btn"  >
                            </div>
                        <?php }?>
                        <div class="wished_active" style="" id="heart_<?php echo $list[$i]["pd_id"];?>">
                            <div class="wished_ani">
                                <img class="heart"  src="<?php echo G5_IMG_URL;?>/ic_wish_on<?php if($list[$i]["pd_type"]==2){?>2<?php }?>.svg" alt="">
                            </div>
                        </div>
                        <div class="in_grid">
                            <?php if($list[$i]["pd_images"]!=""){
                                $img = explode(",",$list[$i]["pd_images"]);
                                $img[0] = trim($img[0]);
                                if(!is_file(G5_DATA_PATH."/product/thumb-".$img[0])) {
                                    $img1 = get_images(G5_DATA_PATH . "/product/" . $img[0], 260, '');
                                }else{
                                    $img1 = "thumb-".$img[0];
                                }
                                if(is_file(G5_DATA_PATH."/product/".$img1)){
                                    ?>
                                    <div class="item_images img_large" style="background-repeat:no-repeat;background-size:cover;background-position:center;min-height: 28vw;background-image:url('<?php echo G5_DATA_URL?>/product/<?php echo $img1;?>');" >
                                        <?php if($img1!=""){?>
                                            <img src="<?php echo G5_DATA_URL?>/product/<?php echo $img1;?>" alt="" class="main" style="opacity: 0">
                                        <?php }else{ ?>
                                            <img src="<?php echo G5_IMG_URL?>/no-profile.svg" alt="" class="main" style="">
                                        <?php }?>
                                    </div>
                                <?php }else{
                                    $tags = explode("#",$list[$i]["pd_tag"]);
                                    $rand = rand(1,13);
                                    ?>
                                    <div class="bg rand_bg<?php echo $rand;?> item_images" >
                                        <div class="tags">
                                            <?php for($k=0;$k<count($tags);$k++){
                                                $rand_font = rand(3,6);
                                                if($tags[$k]!=""){
                                                    ?>
                                                    <div class="rand_size<?php echo $rand_font;?>">#<?php echo $tags[$k];?></div>
                                                <?php } }?>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                <?php }?>
                            <?php }else{
                                $tags = explode("#",$list[$i]["pd_tag"]);
                                $rand = rand(1,13);
                                ?>
                                <div class="bg rand_bg<?php echo $rand;?> item_images" >
                                    <div class="tags">
                                        <?php for($k=0;$k<count($tags);$k++){
                                            $rand_font = rand(3,6);
                                            if($tags[$k]!=""){
                                                ?>
                                                <div class="rand_size<?php echo $rand_font;?>">#<?php echo $tags[$k];?></div>
                                            <?php } }?>
                                    </div>
                                    <div class="clear"></div>
                                </div>
                            <?php }?>
                            <!--<div class="top">
                                <div>
                                    <h2 style="font-weight:normal"><?php /*echo ($list[$i]["mb_level"]==4)?"<img src='".G5_IMG_URL."/ic_pro.svg'>":"　";*/?></h2>
                                    <div>
                                        <ul>
                                            <li>
                                                <img src="<?php /*echo G5_IMG_URL*/?>/ic_hit.svg" alt=""><span><?php /*echo $list[$i]["pd_hits"];*/?></span>
                                            </li>
                                            <?php /*if($app || $list[$i]["distance"] && $app2 || $list[$i]["distance"]){*/?>
                                                <li><img src="<?php /*echo G5_IMG_URL*/?>/ic_loc.svg" alt=""><span><?php /*echo $dist;*/?></span></li>
                                            <?php /*}*/?>
                                        </ul>
                                        <div class="clear"></div>
                                    </div>
                                </div>
                            </div>-->
                            <?php if($list[$i]["new"]==true){ ?>
                                <div class="new" style="">
                                    <img src="<?php echo G5_IMG_URL?>/ic_list_new.svg" alt="">
                                </div>
                            <?php }?>
                            <div class="bottom">
                                <?php if($list[$i]["pd_name"]){
                                    /*switch($list[$i]["pd_type2"]){
                                        case "4":
                                            $pt2 = "[삽니다]";
                                            break;
                                    }*/
                                    ?>
                                    <h2><?php echo ($pt2)?$pt2." ".$list[$i]["pd_tag"]:$list[$i]["pd_tag"];?></h2>
                                <?php }?>
                                <div>
                                    <?php if($list[$i]["pd_type2"]==4){?>
                                        <?php if($list[$i]["pd_price"]==0){?>
                                            <h1>가격 제시</h1>
                                        <?php }else{ ?>
                                            <h1>￦ <?php echo number_format($list[$i]["pd_price"]+$list[$i]["pd_price2"]);?></h1>
                                        <?php }?>
                                    <?php }else{?>
                                        <?php if($list[$i]["pd_type"]==2){$class = "bgs2";}else{$class="";}?>
                                        <h1><?php echo ($list[$i]["pd_price"]+$list[$i]["pd_price2"]==0)?"<span class='color {$class}'>무료나눔</span>":"￦ ".number_format($list[$i]["pd_price"]+$list[$i]["pd_price2"]);?> <?php if($list[$i]["pd_type"]==2 && $list[$i]["pd_type2"]==8){echo $pd_price_type;}?></h1>
                                    <?php }?>
                                    <?php if($wished_cnt["cnt"]>0 && $flag){?>
                                        <div class="list_wished_cnt active wished <?php if($list[$i]["pd_type"]==2){?>bg2<?php }?>"></div>
                                    <?php }else{?>
                                        <div class="list_wished_cnt wished <?php if($list[$i]["pd_type"]==2){?>bg2<?php }?>"></div><span><?php echo $wishedcnt;?></span>
                                    <?php }?>
                                </div>
                            </div>

                        </div>
                    </div>
                    <?php
                    $pt2 = "";
                }
                if($adchk==false) {
                    if ($total == 0 && count($listadd) > 0 && $page == 1) {
                        $rand = rand(0, count($listadd) - 1);
                        //for ($k = 0; $k < count($listadd); $k++) {
                        ?>
                        <div class="grid__item ad_list <?php if ($_SESSION["list_type"] == "list") {echo " type_list";} ?>" onclick="location.href='<?php echo $listadd[$rand]["ad_link"]; ?>'">
                            <div>
                                <div class="ad_mark">AD</div>
                                <?php if ($listadd[$rand]["ad_photo"] != "") { ?>
                                    <div class="item_images" style="background-image:url('<?php echo G5_DATA_URL ?>/product/<?php echo $listadd[$rand]["ad_photo"]; ?>');background-repeat:no-repeat;background-size:cover;background-position:center;min-height: 28vw;">
                                        <img src="<?php echo G5_DATA_URL ?>/product/<?php echo $listadd[$rand]["ad_photo"]; ?>"
                                             alt="ad" class="main" style="opacity:0">
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
                        <?php //}
                    } else if ($total > count($listadd) && count($listadd) > 0 && $page == 1) {
                        $rand = rand(0, count($listadd) - 1);
                        ?>
                        <div class="grid__item ad_list <?php if ($_SESSION["list_type"] == "list") {echo " type_list";} ?>" onclick="location.href='<?php echo $listadd[$rand]["ad_link"]; ?>'">
                            <div>
                                <div class="ad_mark">AD</div>
                                <?php if ($listadd[$rand]["ad_photo"] != "") {
                                    ?>
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
                    <?php } else if ($total <= count($listadd) && count($listadd) > 0 && $page == 1) {
                        $rand = rand(0, count($listadd) - 1);
                        //for ($i = 0; $i < count($listadd); $i++) {
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
                    }
                }
                if(count($list)==0 && count($listadd)==0){?>
                    <div class="no-list">
                        검색된 리스트가 없습니다.
                    </div>
                <?php }else{?>
                <?php }?>
            </div>
            <div class="clear"></div>
        </article>
    </section>
</div>
<div class="trash-ani">
    <div class="trash-icon">
        <img src="<?php echo G5_IMG_URL?>/ic_index_trash.svg" alt="">
    </div>
</div>
<script>
    var page=1;
    var $grid = null;
    var scrollchk = true;
    var finish = false;
    var $items = '';
    function initpkgd(type){
//-------------------------------------//
        /*if($grid!=null){
         $grid.masonry('remove',$(".gird"));
         }*/
        // init Masonry

        $grid = $('.grid').masonry({
            itemSelector: '.none', // select none at first
            columnWidth: '.grid__item',
            gutter: 8,
            //  horizontalOrder:true,
            percentPosition: true,
            // nicer reveal transition
            visibleStyle: {transform: 'translateY(0)', opacity: 1},
            hiddenStyle: {transform: 'translateY(0)', opacity: 0}
        });
        $items = $grid.find('.grid__item');
        //$grid.masonry('reloadItems');

        $grid.imagesLoaded({background:true},function () {
            $grid.removeClass('are-images-unloaded');
            $grid.masonry('option', {
                itemSelector: '.grid__item',
                columnWidth: '.grid__item',
                percentPosition: true,
                gutter: 8
            });
            $grid.masonry('appended', $items);
        });
        $grid.imagesLoaded().done(function(instance){
            chklist = false;
            //instance.addClass("done");
        });
//-------------------------------------//
    }
    $(document).ready(function(){

        //masonry 초기화
        initpkgd('');

        //fnlist(1,'');

        <?php if(count($list) == 0){?>
        fnSetting();
        $(".search_setting .no-list").show();
        <?php } ?>

        <?php if($myblind["pd_id"]){?>
        //블라인드 게시물 안내
        var pd_id = "<?php echo $myblind["pd_id"];?>";
        $.ajax({
            url:g5_url+'/mobile/page/modal/modal.blindinfo.php',
            method:"post",
            data:{pd_id:pd_id}
        }).done(function(data){
            $(".modal").html(data).addClass("active");
            /*$("#id06").css("display","block");*/
            $("html, body").css("overflow","hidden");
            $("html, body").css("height","100vh");
            location.hash = "#modal";
        });
        <?php }?>
        <?php if($stx){?>
        $("#stx").val("<?php echo $stx;?>");
        <?php } ?>

        //인기 거리
        $(".align .slider").click(function(){
            if($(this).prev().prop("checked") == true){
                $.ajax({
                    url:g5_url+"/mobile/page/ajax/ajax.set_session.php",
                    method:"post",
                    data:{key:"list_basic_order",value:"hits"}
                }).done(function(data){
                    //console.log(data);
                });
                $(this).html("등록");
                $(this).css({"text-align":"right"});
                //인기순 정렬
                finish = false;
                fnlist(1,'');
            }else{
                <?php if($_SESSION["lat"] && $_SESSION["lng"]){?>
                $.ajax({
                    url:g5_url+"/mobile/page/ajax/ajax.set_session.php",
                    method:"post",
                    data:{key:"list_basic_order",value:"location"}
                }).done(function(data){
                    //console.log(data);
                });
                $(this).html("거리");
                $(this).css({"text-align":"left"});
                //거리순 정렬
                finish = false;
                fnlist(1,'');

                <?php  }else{ ?>
                alert("거리정보가 없어 리스트를 불러올수 없습니다.");
                setTimeout(function(){$("#paplur").removeAttr("checked");},400);
                <?php }?>
            }
        });
        $(document).on("click",".list .slider",function(){
            if($(this).prev().prop("checked") == true){//LIST
                $.ajax({
                    url:g5_url+"/mobile/page/ajax/ajax.set_session.php",
                    method:"post",
                    data:{key:"list_type",value:"gird"}
                }).done(function(data){
                    $("#set_list_type").val("gird");
                    $(".list .slider").css({"background-image":"url(./img/ic_switch_grid.svg)","background-position":"calc(100% - 1vw) center"});
                    $(".grid__item").removeClass("type_list");
                    finish = false;
                    fnlist(1,'false');
                });

            }else{//GIRD
                $.ajax({
                    url:g5_url+"/mobile/page/ajax/ajax.set_session.php",
                    method:"post",
                    data:{key:"list_type",value:"list"}
                }).done(function(data){
                    $("#set_list_type").val("list");
                    $(".list .slider").css({"background-image":"url(./img/ic_switch_list.svg)","background-position":"1vw center"});
                    $(".grid__item").addClass("type_list");
                    finish = false;
                    fnlist(1,'true');
                });

            }
        });

        $(".category ul > li").click(function(){
            console.log("category1//"+location.hash)
            if(!$(this).hasClass("sugg")) {
                $(this).addClass("active");
                $(".category ul li").not($(this)).removeClass("active");
                var id = $(this).attr("id");
                $("." + id).addClass("active");
                $(".category2 ul").not($("." + id)).removeClass("active");
                $("html, body").css("overflow", "hidden");
                $("html, body").css("height", "100vh");
            }
        });
        $(".category_menu .category2 ul li, .category_menu2 .category2 ul li").click(function(){
            var type2 = $("#set_type2").val();
            console.log(type2);
            console.log("category2")
            var c = trim($(this).parent().parent().prev().children().find("li.active").text());
            var sc = trim($(this).text());
            if(sc == "카테고리 제안" || sc == "제안하기") {
                cateClose("write_cate");
                return false;
            }
            if (sc != "") {
                var type = $("#set_type").val();
                if(type2=="") {
                    var type2 = $("#wr_type2").prop("checked");
                }
                var msg = '';
                $.ajax({
                    url: g5_url + "/mobile/page/ajax/ajax.category_info.php",
                    method: "post",
                    data: {cate: sc, type: type},
                    dataType: "json"
                }).done(function (data) {
                    $("#c").val(c);
                    $("#sc").val(sc);

                    var catetag = data.catetag;
                    if(data.info_text) {

                        var info_text = data.info_text;

                        $.ajax({
                            url: g5_url + '/mobile/page/modal/modal.writeconfirm.php',
                            method: "post",
                            data: {infotext: info_text,ca_id: data.ca_id,cate1: c,cate2: sc,set_type: $("#set_type").val(),pd_type2: type2,app: "<?php echo $app;?>",app2: "<?php echo $app2;?>",catetag: data.catetag,ca_id:data.ca_id}
                        }).done(function (data) {
                            $(".modal").html(data).addClass("active");
                            $("html, body").css("overflow", "hidden");
                            $("html, body").css("height", "100vh");
                        });
                        cateClose('chk');
                        return false;
                    }else{
                        $.ajax({
                            url:g5_url+'/mobile/page/modal/modal.write.php',
                            method:"post",
                            data:{set_type:$("#set_type").val(),cate1:c,cate2:sc,pd_type2:type2,app:"<?php echo $app;?>",app2:"<?php echo $app2;?>",set_type2:type2}
                        }).done(function(data) {
                            $(".modal").html(data);
                            $(".modal").addClass("active");
                            $("html, body").css("overflow", "hidden");
                            $("html, body").css("height", "100vh");
                            if (catetag != "") {
                                $("#id01 .write_help").html("예 : " + catetag);
                                if (type == 2) {
                                    $("#id01 .write_help.price_help").html("필요시 계약금을 설정할 수 있습니다.");
                                }
                            } else {
                                $("#id01 .write_help").html("검색어 구분은 띄어쓰기로 가능합니다.");
                                $("#id01 .write_help.price_help").html("필요시 계약금을 설정할 수 있습니다.");
                            }

                            if (type == 1) {
                                $("#wr_price").attr("placeholder", "판매금액");
                                $("#wr_price2").css("display", "none");
                                $("#wr_price").css("width", "70%");
                                $("#wr_price2").css({"display": "none"});
                                $(".pd_price_type").css("display", "none");
                                $(".price_box .write_help.price_help").html('');
                            }
                            if (type == 2) {
                                $(".pd_price_type").css("display", "block");
                                if ($("#id01 .write_help.price_help").length == 0) {
                                    $(".price_box").append('<p class="write_help price_help">필요시 계약금을 설정할 수 있습니다.</p>');
                                } else {
                                    $("#id01 .write_help.price_help").html("필요시 계약금을 설정할 수 있습니다.");
                                }
                                $("#wr_price").attr("placeholder", "거래완료금");
                                $("#wr_price2").attr("placeholder", "계약금");
                                $("#wr_price").css("width", "40%");
                                $("#wr_price2").css({"display": "inline-block", "width": "24%"});
                                $("#wr_price2").css("display", "inline-block");
                            }
                            <?php if($app){?>
                            $("#id01 #wr_title").focus();
                            $("#id01 #wr_title").selectRange(2, 2);
                            window.android.Onkeyboard();
                            <?php }if($app2){?>
                            //setTimeout(function () {
                                $("#id01 #wr_title").focus();
                                //$("#id01 #wr_title").selectRange(2, 2);
                            //}, 1500);
                            <?php }?>
                            cateClose('chk');
                            location.hash = '#writes';
                        });
                    }
                });
            }
        });

        var container = document.getElementById('test');
        var swipe2 = new Hammer(container);
        swipe2.on('swipeleft',function(e){
        });
        swipe2.on('swiperight',function(e){
        });

        //그리드 아이템 가로 스크롤체크
        $("div[id^=list]").each(function(e){
            //$(document).on("each","div[id^=list]",function(e){

            var id = $(this).attr("id");
            var item = document.getElementById(id);
            var swiper = new Hammer(item);

            swiper.on('swipeleft',function(e){
                console.log(e);
                $.ajax({
                    url:g5_url+"/mobile/page/ajax/ajax.remove_item.php",
                    method:"POST",
                    data:{pd_id:pd_id}
                }).done(function(data){
                    console.log(data);
                    if(data=="1") {
                        $("#"+id).remove();
                        $grid.masonry('remove', this).masonry("layout");
                        $("#mobile_header #mobile_menu_btn").addClass("active");
                        $("#debug").addClass("active");
                        $(".trash-ani").addClass("active");
                        $("#debug").html("휴지통으로 이동되었습니다.");
                        setTimeout(function(){$(".trash-icon").addClass("active");},1000);
                        setTimeout(removeDebug, 1500);
                    }else if(data=="3"){
                        $("#debug").addClass("active");
                        $("#debug").html("내 글은 휴지통에 보낼 수 없습니다.");
                        setTimeout(removeDebug, 1500);
                    }
                });
            });
            swiper.on("swiperight",function(e){
                console.log(e);
                $.ajax({
                    url:g5_url+"/mobile/page/ajax/ajax.remove_item.php",
                    method:"POST",
                    data:{pd_id:pd_id}
                }).done(function(data){
                    console.log(data);
                    if(data=="1") {
                        $("#"+id).remove();
                        $grid.masonry('remove', this).masonry("layout");
                        $("#mobile_header #mobile_menu_btn").addClass("active");
                        $("#debug").addClass("active");
                        $(".trash-ani").addClass("active");
                        $("#debug").html("휴지통으로 이동되었습니다.");
                        setTimeout(function(){$(".trash-icon").addClass("active");},1000);
                        setTimeout(removeDebug, 1500);
                    }else if(data=="3"){
                        $("#debug").addClass("active");
                        $("#debug").html("내 글은 휴지통에 보낼 수 없습니다.");
                        setTimeout(removeDebug, 1500);
                    }
                });
            });

            var swiperm = new Hammer.Manager(item);
            var pd_id = id.replace("list_","");


            // Tap recognizer with minimal 2 taps
            swiperm.add( new Hammer.Tap({ event: 'doubletap', taps: 2 }) );
            // Single tap recognizer
            swiperm.add( new Hammer.Tap({ event: 'singletap', interval: 100}) );

            // we want to recognize this simulatenous, so a quadrupletap will be detected even while a tap has been recognized.
            swiperm.get('doubletap').recognizeWith('singletap');
            // we only want to trigger a tap, when we don't have detected a doubletap
            swiperm.get('singletap').requireFailure('doubletap');

            swiperm.on("singletap ", function(ev) {
                if(ev.type == "singletap"){
                    if(tabchk==false) {
                        tabchk=true;
                        fn_viewer(pd_id);
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
                        $.ajax({
                            url:g5_url+"/mobile/page/ajax/ajax.wish.php",
                            method:"POST",
                            data:{pd_id:pd_id,mode:"delete",mb_id:"<?php echo $wished_id;?>"}
                        }).done(function(data){
                            if(data == "delete query") {
                                $("#" + id).removeClass("wishedon");

                                var wished = $("#" + id).children().find($(".wished"));
                                var wished_cnt = $("#" + id).children().find($(".list_wished_cnt"));
                                var wished_total = Number(wished_cnt.text()) - 1;
                                if(wished_total < 0){
                                    wished_total = '';
                                }
                                $("#heart_"+pd_id).removeClass("active");
                                wished.removeClass("element-animation");
                                wished.removeClass("active");
                                wished_cnt.html(wished_total);
                            }else{

                            }
                        });
                    }else{
                        $.ajax({
                            url:g5_url+"/mobile/page/ajax/ajax.wish.php",
                            method:"POST",
                            data:{pd_id:pd_id,mode:"insert",mb_id:"<?php echo $wished_id;?>"}
                        }).done(function(data){
                            if(data=="myproduct"){
                                alert("내가 올린 게시물은 추가할 수 없습니다.");
                            }
                            if(data=="ok query"){
                                $("#"+id).addClass("wishedon");
                                var wished = $("#"+id).children().find($(".wished"));
                                var wished_cnt = $("#" + id).children().find($(".list_wished_cnt"));
                                var wished_total = Number(wished_cnt.text()) + 1;
                                $("#heart_"+pd_id).addClass("active");
                                wished.removeClass("element-animation");
                                wished.addClass("element-animation");
                                wished.addClass("active");
                                wished_cnt.html(wished_total);
                            }
                        });
                    }
                }
            });
        });

        /*$(".list_item").jscroll({
         autoTrigger:true,
         loadingHtml:'<div class="next"></div>',
         nextSelector:"a.nextPage:last"
         });*/
    });
    var chklist = false;
    var searchs = false;
    function fnlist(num,fn_list_type,pd_type){
        if(fn_list_type != "" && fn_list_type == "searchtrue"){
            searchs = true
        }
        //사고팔고/ /카테코리1/카테고리2/가격시작/가격끝/정렬순서1/정렬순서2/정렬순서3/정렬순서4/정렬순서5
        var type1,type2,cate1,cate2,stx,priceFrom,priceTo,sorts,app,app2,mb_id,sc_id,align,orderactive,typecompany,price_type,meetFrom,meetTo,meetType;

        if(num == 1){
            page=0;
        }

        var align = 0;
        var pchk = $("#paplur").is(":checked");
        if(pchk == false) align = 1;
        var latlng = '';
        <?php if($app){?>
        //if(align == 1){

        latlng = window.android.getLocation();
        //}
        <?php }?>
        if($("#sc_id").length > 0) {
            sc_id = $("#sc_id").val();
        }else{
            sc_id = '';
        }

        var searchActive = $("#searchActive").val();
        stx = $("#stx").val();
        if(pd_type=="" || pd_type=="undefined") {
            if (searchActive != "" || sc_id == "") {
                priceFrom = $("#sc_priceFrom").val();
                priceTo = $("#sc_priceTo").val();
                if (searchActive == "simple") {
                    //sc_id = '';
                }
            } else {
                priceTo = '<?php echo $priceTo;?>';
                /*priceTo = $("#sc_priceTo").val();*/
            }
        }

        app = "<?php echo $app;?>";
        app2 = "<?php echo $app2;?>";
        type1 = $("#set_type").val();
        type2 = $("#set_type2").val();
        if($("#levels").prop("checked")){
            typecompany = "on";
        }else {
            typecompany = "off";
        }

        cate1 = $("#cate").val();
        cate2 = $("#cate2").val();

        mb_id = $("#mb_id").val();
        align = $("#order_sort").val();
        orderactive = $("#order_sort_active").val();
        var price_type1 = $("#price_type1").val();
        var price_type2 = $("#price_type2").val();
        var price_type3 = $("#price_type3").val();
        meetFrom = $("#pd_timeFrom").val();
        meetTo = $("#pd_timeTo").val();
        meetType = $("#timeType").val();
        var pd_ids = "<?php echo $saves["pd_ids"];?>";
        var set_list_type = $("#set_list_type").val();

        var searchSaveChk = false;

        //alert(pd_type+"//"+searchActive+"//"+stx +"//"+sc_id);

        if(sc_id != "" && num == 1 && fn_list_type=="searchtrue" && stx!=""){
            //현재 설정값과 다른지 비교
            $.ajax({
                url:g5_url+"/mobile/page/ajax/ajax.search_value_check.php",
                type:"post",
                async:false,
                data:{type1:type1,type2:type2,cate1:cate1,cate2:cate2,align:align,orderactive:orderactive,priceFrom:priceFrom,priceTo:priceTo,meetFrom:meetFrom,meetTo:meetTo,meetType:meetType,price_type:$("#pd_p_type").val(),sc_id:sc_id,stx:stx,typecompany:typecompany,pd_price_type1:price_type1,pd_price_type2:price_type2,pd_price_type3:price_type3,stx:stx}
            }).done(function(data){
                data = data.replace(/\s+/, "");//왼쪽 공백제거
                data = data.replace(/\s+$/g, "");//오른쪽 공백제거
                data = data.replace(/\n/g, "");//행바꿈제거
                data = data.replace(/\r/g, "");//엔터제거
                //console.log(data);
                if(data!=""){
                    if(confirm("현재 검색저장 값이 변경되었습니다.\r\n저장 후 검색하시겠습니까?")) {
                        searchSaveChk = true;
                        //return false;
                    }else{
                        searchSaveChk = false;
                        $("#sc_id").val('');
                    }
                }
            });
        }

        if(num==''){
            searchSaveChk = false;
        }

        /*if(searchSaveChk==false){

         }else {*/
        $.ajax({
            url: g5_url + "/mobile/page/ajax/ajax.index.list.php",
            method: "POST",
            data: {
                pd_type:pd_type,
                page: page,
                list_type: set_list_type,
                stx: stx,
                app: app,
                app2: app2,
                set_type: type1,
                type2: type2,
                cate1: cate1,
                cate2: cate2,
                priceFrom: priceFrom,
                priceTo: priceTo,
                sc_id: sc_id,
                mb_id: mb_id,
                order_sort: align,
                latlng: latlng,
                pd_ids: pd_ids,
                mb_level: typecompany,
                order_sort_active: orderactive,
                set_search: set_search,
                pd_price_type1: price_type1,
                pd_price_type2: price_type2,
                pd_price_type3: price_type3,
                pd_timeFrom: meetFrom,
                pd_timeTo: meetTo,
                pd_timeType: meetType,
                lat: "<?php echo $_SESSION["lat"];?>",
                lng: "<?php echo $_SESSION["lng"]?>",
                searchs: searchs,
                searchActive: searchActive,
                fn_list_type: fn_list_type,
                searchSaveChk:searchSaveChk
            },
            beforeSend: function () {
                <?php if($app){?>
                window.android.showLoading();
                <?php }?>
                chklist = true;
            },
            complete: function (data) {
                <?php if($app){?>
                window.android.hideLoading();
                <?php }?>
                finish = false;
            }
        }).done(function (data) {
            if (data.indexOf("no-list") == -1) {
                if (num == 1) {
                    //새리스트
                    $(".grid").html('');
                    $(".grid").html(data);
                    initpkgd('');
                    page = 1;
                } else {
                    //스크롤
                    var $items = $(data);
                    $items.imagesLoaded(function () {
                        $grid.append($items).masonry('appended', $items);
                    });
                    $items.imagesLoaded().done(function (instance) {
                        chklist = false;
                    });
                    page++;
                }
                $(".search_setting .no-list").css("display", "none");
            } else {
                chklist = false;
                finish = true;
                if (num == 1) {
                    var noitem = '<div class="no-list">검색된 리스트가 없습니다.</div>';
                    $("#test").html(noitem);
                }
                console.log(page);
                if(page == 0) {
                    $(".search_setting .no-list").css("display", "block");
                }
                $("#debug").addClass("active");
                $("#debug").html("목록이 없습니다.");
                setTimeout(removeDebug, 1500);
            }
            scrollchk = true;
            $('#container').off('scroll mousedown DOMMouseScroll mousewheel keyup');
        });
        //}
    }

    function fnwrite(type2){
        var chk = getCookie('<?php echo $member["mb_id"];?>');
        var wr_type1 = getCookie("wr_type1");
        var set_type = $("#set_type").val();
        $("#set_type2").val(type2);
        <?php if($app){?>
        $.ajax({
            url:g5_url+'/plugin/ajax.login_dup_check.php',
            type:"post",
            data:{device:"<?php echo $device;?>",mac:"<?php echo $mac;?>"}
        }).done(function(data){
            data = data.replace(/\s+/, "");//왼쪽 공백제거
            data = data.replace(/\s+$/g, "");//오른쪽 공백제거
            data = data.replace(/\n/g, "");//행바꿈제거
            data = data.replace(/\r/g, "");//엔터제거
            var wr_type1 = getCookie("wr_type1");
            if(trim(data)){
                alert(data);
                window.android.setLogout();
                location.reload();
            }else{
                if(chk!="" && chk == "write" && wr_type1 == set_type){
                    if(confirm('작성중인 글이 있습니다. 해당 글을 계속 작성하시겠습니까?')){
                        var wr_type1 = getCookie('wr_type1');
                        var pd_type2 = getCookie('pd_type2');
                        var cate1 = getCookie('cate1');
                        var cate2 = getCookie('cate2');
                        var title = getCookie('title');
                        var filename = getCookie('filename');
                        var videoname = getCookie('videoname');
                        var wr_price = getCookie('pd_price');
                        var wr_price2 = getCookie('pd_price2');
                        var pd_video_link = getCookie('pd_video_link');
                        var pd_timeFrom = getCookie('pd_timeFrom');
                        var pd_timeTo = getCookie('pd_timeTo');
                        var pd_discount = getCookie('pd_discount');
                        var pd_content = getCookie('pd_content');
                        var pd_price_type = getCookie('pd_price_type');
                        var pd_location = getCookie('pd_location');
                        var pd_location_name = getCookie('pd_location_name');
                        var pd_infos = getCookie('pd_infos');

                        cate1 = encodeURIComponent(cate1,"UTF-8");
                        cate2 = encodeURIComponent(cate2,"UTF-8");
                        pd_content = encodeURIComponent(pd_content,"UTF-8");
                        title = encodeURIComponent(title,"UTF-8");
                        pd_location = encodeURIComponent(pd_location,"UTF-8");
                        pd_location_name = encodeURIComponent(pd_location_name,"UTF-8");
                        pd_infos = encodeURIComponent(pd_infos,"UTF-8");

                        location.replace(g5_url+"/mobile/page/write.php?wr_type1="+wr_type1+"&pd_type2="+pd_type2+"&cate1="+cate1+"&cate2="+cate2+"&title="+title+"&filename="+filename+"&videoname="+videoname+"&wr_price="+wr_price+"&wr_price2="+wr_price2+"&pd_video_link="+pd_video_link+"&pd_timeFrom="+pd_timeFrom+"&pd_timeTo="+pd_timeTo+"&pd_discount="+pd_discount+"&pd_content="+pd_content+"&pd_price_type="+pd_price_type+"&pd_location="+pd_location+"&pd_location_name="+pd_location_name+"&pd_infos="+pd_infos);
                        return false;
                    }else{
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
                    }
                }
                var type = $("#set_type").val();
                if(type == 1){
                    //물건
                    $(".category_menu").fadeIn(300,function(){
                        $(".category_menu").addClass("active");
                        location.hash='#category';
                    });
                }else if(type == 2){
                    //능력
                    $(".category_menu2").fadeIn(300,function(){
                        $(".category_menu2").addClass("active");
                        location.hash='#category';
                    });
                }else{
                    alert("정상적인 방법으로 등록 바랍니다.");
                    return false;
                }
            }
        });
        <?php }else{?>

        if(chk!="" && chk == "write" && wr_type1 == set_type){
            if(confirm('작성중인 글이 있습니다. 해당 글을 계속 작성하시겠습니까?')){
                var wr_type1 = getCookie('wr_type1');
                var pd_type2 = getCookie('pd_type2');
                var cate1 = getCookie('cate1');
                var cate2 = getCookie('cate2');
                var title = getCookie('title');
                var filename = getCookie('filename');
                var videoname = getCookie('videoname');
                var wr_price = getCookie('pd_price');
                var wr_price2 = getCookie('pd_price2');
                var pd_video_link = getCookie('pd_video_link');
                var pd_timeFrom = getCookie('pd_timeFrom');
                var pd_timeTo = getCookie('pd_timeTo');
                var pd_discount = getCookie('pd_discount');
                var pd_content = getCookie('pd_content');
                var pd_price_type = getCookie('pd_price_type');
                var pd_location = getCookie('pd_location');
                var pd_location_name = getCookie('pd_location_name');
                var pd_infos = getCookie('pd_infos');

                cate1 = encodeURIComponent(cate1,"UTF-8");
                cate2 = encodeURIComponent(cate2,"UTF-8");
                pd_content = encodeURIComponent(pd_content,"UTF-8");
                title = encodeURIComponent(title,"UTF-8");
                pd_location = encodeURIComponent(pd_location,"UTF-8");
                pd_location_name = encodeURIComponent(pd_location_name,"UTF-8");
                pd_infos = encodeURIComponent(pd_infos,"UTF-8");

                location.replace(g5_url+"/mobile/page/write.php?wr_type1="+wr_type1+"&pd_type2="+pd_type2+"&cate1="+cate1+"&cate2="+cate2+"&title="+title+"&filename="+filename+"&videoname="+videoname+"&wr_price="+wr_price+"&wr_price2="+wr_price2+"&pd_video_link="+pd_video_link+"&pd_timeFrom="+pd_timeFrom+"&pd_timeTo="+pd_timeTo+"&pd_discount="+pd_discount+"&pd_content="+pd_content+"&pd_price_type="+pd_price_type+"&pd_location="+pd_location+"&pd_location_name="+pd_location_name+"&pd_infos="+pd_infos);
                return false;
            }else{
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
            }
        }
        var type = $("#set_type").val();
        if(type == 1){
            //물건
            $(".category_menu").fadeIn(300,function(){
                $(".category_menu").addClass("active");
                location.hash='#category';
            });
        }else if(type == 2){
            //능력
            $(".category_menu2").fadeIn(300,function(){
                $(".category_menu2").addClass("active");
                location.hash='#category';
            });
        }else{
            alert("정상적인 방법으로 등록 바랍니다.");
            return false;
        }
        <?php }?>
    }
    function fnWriteStep2(url){
        if($("#wr_title").val()=="" || $("#wr_title").val()=="#"){
            alert("검색어를 입력해주세요.");
            return false;
        }
        var formData = $("form[name=write_form]").serialize();
        <?php if(!$app2 && !$app){?>
        location.replace(url+'?'+formData);
        <?php }?>
    }

    function fnOnCam(){
        if($("#wr_title").val()=="" || $("#wr_title").val()=="#"){
            alert("제목을 입력해주세요");
            return false;
        }else{
            var title = $("#wr_title").val();
            var type1 = $("#wr_type1").val();
            var type2 = $("#pd_type2").val();
            var pd_price_type = $("#pd_p_type").val();
            var cate1 = $("#c").val();
            var cate2 = $("#sc").val();
            var wr_price = $("#wr_price").val();
            var wr_price2 = $("#wr_price2").val();

            window.android.camereOn('<?php echo $member["mb_id"];?>',title,cate1,cate2,type1,type2,wr_price,wr_price2,pd_price_type);
        }
    }

    function fnOnCamIos(){
        if($("#wr_title").val()=="" || $("#wr_title").val()=="#"){
            alert("제목을 입력해주세요");
            return false;
        }else{
            var title = $("#wr_title").val();
            var type1 = $("#wr_type1").val();
            var type2 = $("#pd_type2").val();
            var pd_price_type = $("#pd_p_type").val();
            var cate1 = $("#c").val();
            var cate2 = $("#sc").val();
            var wr_price = $("#wr_price").val();
            var wr_price2 = $("#wr_price2").val();
            try{
                var dataString = {mb_id : "<?php echo $member["mb_id"];?>",title : title,cate1 : cate1,cate2 : cate2,type1 : type1,type2 : type2,wr_price : wr_price,wr_price2 : wr_price2,pd_price_type : pd_price_type};
                webkit.messageHandlers.onCam.postMessage(dataString);
            }catch (err){
                console.log(err);
            }
        }
    }


    $(window).scroll(function(){
        var filter = "win16|win32|win64|mac";
        var navi = navigator.platform;
        if(0 > filter.indexOf(navi.toLowerCase())){
            if (Math.ceil($(this).scrollTop()) >= ($(document).height() - $(window).height() - 120)) {
                if (scrollchk == true && finish == false) {
                    fnlist(2, '');
                    scrollchk = false;
                    $('#container').bind('scroll mousedown DOMMouseScroll mousewheel keyup', function (event) {
                        event.preventDefault();
                        event.stopPropagation();
                        $("#container").stop();
                        return false;
                    });
                }
            }
        }else {
            if (Math.ceil($(this).scrollTop()) >= ($(document).height() - $(window).height())) {
                if (scrollchk == true && finish == false) {
                    fnlist(2, '');
                    scrollchk = false;

                    $('#container').bind('scroll mousedown DOMMouseScroll mousewheel keyup', function (event) {
                        event.preventDefault();
                        event.stopPropagation();
                        $("#container").stop();
                        return false;
                    });
                }
            }
        }
    });

    function fnLikeUpdate(){
        var id = $("#like_id").val();
        var mb_id;
        <?php if($member["mb_id"]!=""){ ?>
        mb_id = "<?php echo $member["mb_id"];?>";
        <?php }else{?>
        alert("로그인후 이용바랍니다.");
        location.href=g5_bbs_url+'/login.php';
        return false;
        <?php }?>
        var text = $("#like_content").val();
        var likeup = $("#fin_likeup").val();
        $.ajax({
            url:g5_url+"/mobile/page/like_product.php",
            method:"post",
            dataType:"json",
            data:{pd_id:id,mb_id:mb_id,like_content:text,likeup:likeup}
        }).done(function(data){
            if(data.result=="1"){
                alert('이미 평가한 글입니다.');
            }else if(data.result=="2"){
                alert("평가가 정상 등록됬습니다.");
            }else{
                alert("잘못된 요청입니다.");
            }
            $(".pd_like span").html(data.count);
            modalClose();
        });
    }

    function fnSimpleWrite(){
        if(confirm('간편등록 하시겠습니까?')){
            document.write_form.action = g5_url+"/mobile/page/write_simple_update.php";
            document.write_form.submit();
        }else{
            return false;
        }
    }

    function doNotReload(){
        if((event.ctrlKey == true && (event.keyCode == 78 || event.keyCode == 82)) || (event.keyCode == 116))
        {
            event.keyCode = 0;
            event.cancelBubble = true;
            event.returnValue = false;
        }
    }
    document.onkeydown = doNotReload;

    function fnInputs(e){
        if(e.keyCode == 13){
            $("#wr_price").focus();
        }
    }
    function fnInputsPrice(e){
        if(e.keyCode == 13){
            fnOnCamIos();
        }
    }

    <?php if($pd_id){ ?>
    setTimeout(function(){
        //if($("#id0s").attr("style")=="display: block;") {
        fn_viewer("<?php echo $pd_id;?>","<?php echo $detail;?>")
        //}
    },1500);
    <?php if($detail==true){?>
    <?php }?>
    <?php } ?>

</script>
<script src="<?php echo G5_JS_URL;?>/hammer.js"></script>
