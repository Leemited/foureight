<?php
include_once("../../../common.php");
include_once (G5_MOBILE_PATH."/index.set.php");
/*//검색 기본값
$search = "p.pd_status = 0 and p.pd_blind < 10 and p.pd_blind_status = 0";

if($set_type){
    $search .= " and p.pd_type = {$set_type}";
}else{
    $search .= " and p.pd_type = 2";
    $set_type=2;
}
if($type2){
    $search .= " and p.pd_type2 = {$type2}";
}
if($stx){
    $search .= " and (p.pd_name like '%{$stx}%' or p.pd_tag like '%{$stx}%' or p.pd_cate like '%{$stx}%' or p.pd_cate2 like '%{$stx}%')";
}
if($cate){
    $search .= " and p.pd_cate = '{$cate}'";
}
if($cate2){
    $search .= " and p.pd_cate2 = '{$cate2}'";
}
if($priceFrom && $priceTo){
    $search .= " and p.pd_price between '{$priceFrom}' and '{$priceTo}'";
}
if($order_sort){
    $od = "";
    $order_sorts = explode(",",$order_sort);
    $actives = explode(",",$order_sort_active);
    for($i=0;$i<count($order_sorts);$i++){
        if($order_sorts[$i]=="pd_date"){
            if($actives[$i] == 1){
                $checked[$i] = "checked";
                $ods[] = " p.pd_update desc";
            }
            $order_item[$i] = '<label class="align" id="sortable" for="pd_date">'.
                '<input type="checkbox" name="orders[]" value="pd_date" id="pd_date" '.$checked[$i].'>'.
                '<span class="round">최신순</span></label>';
        }
        if($order_sorts[$i]=="pd_price"){
            if($actives[$i] == 1){
                $checked[$i] = "checked";
                $ods[] = " p.pd_price asc";
            }
            $order_item[$i] = '<label class="align" id="sortable" for="pd_price">'.
                '<input type="checkbox" name="orders[]" value="pd_price" id="pd_price" '.$checked[$i].'>'.
                '<span class="round">가격순</span></label>';
        }
        if($order_sorts[$i]=="pd_recom"){
            if($actives[$i] == 1){
                $checked[$i] = "checked";
                $ods[] = " p.pd_recom desc";
            }
            $order_item[$i] = '<label class="align" id="sortable" for="pd_recom">'.
                '<input type="checkbox" name="orders[]" value="pd_recom" id="pd_recom" '.$checked[$i].'>'.
                '<span class="round">추천순</span></label>';
        }
        if($order_sorts[$i]=="pd_hits"){
            if($actives[$i] == 1){
                $checked[$i] = "checked";
                $ods[] = " p.pd_hits desc";
            }
            $order_item[$i] = '<label class="align" id="sortable" for="pd_hits">'.
                '<input type="checkbox" name="orders[]" value="pd_hits" id="pd_hits" '.$checked[$i].'>'.
                '<span class="round">인기순</span></label>';
        }
        if($order_sorts[$i]=="pd_loc"){
            if($actives[$i] == 1){
                $checked[$i] = "checked";
                if($_SESSION["lat"] && $_SESSION["lng"]) {
                    $ods[] = " ISNULL(p.pd_lat) asc, distance asc";
                }
            }
            $order_item[$i] = '<label class="align" id="sortable" for="pd_loc">'.
                '<input type="checkbox" name="orders[]" value="pd_loc" id="pd_loc" '.$checked[$i].'>'.
                '<span class="round">거리순</span></label>';
        }
    }
    $od = " order by ". implode(",",$ods);
}else{
    if($_SESSION["lat"] && $_SESSION["lng"]){
        $od = " order by ISNULL(p.pd_lat) asc, distance asc, p.pd_price asc";
    }else {
        $od = " order by p.pd_price asc";
    }
}

if(($_SESSION["lat"] && $_SESSION["lng"]) || ($lat && $lng)){
    if($lat && $lng) {
        $sel = " , 6371 * 2 * ATAN2(SQRT(POW(SIN(RADIANS({$lat} - p.pd_lat)/2), 2) + POW(SIN(RADIANS({$lng} - p.pd_lng)/2), 2) * COS(RADIANS(p.pd_lat)) * COS(RADIANS({$lat}))), SQRT(1 - POW(SIN(RADIANS({$lat} - p.pd_lat)/2), 2) + POW(SIN(RADIANS({$lng} - p.pd_lng)/2), 2) * COS(RADIANS(p.pd_lat)) * COS(RADIANS({$lat})))) AS distance";
    }
    if($_SESSION["lat"] && $_SESSION["lng"]){
        $sel = " , 6371 * 2 * ATAN2(SQRT(POW(SIN(RADIANS({$_SESSION["lat"]} - p.pd_lat)/2), 2) + POW(SIN(RADIANS({$_SESSION["lng"]} - p.pd_lng)/2), 2) * COS(RADIANS(p.pd_lat)) * COS(RADIANS({$_SESSION["lat"]}))), SQRT(1 - POW(SIN(RADIANS({$_SESSION["lat"]} - p.pd_lat)/2), 2) + POW(SIN(RADIANS({$_SESSION["lng"]} - p.pd_lng)/2), 2) * COS(RADIANS(p.pd_lat)) * COS(RADIANS({$_SESSION["lat"]})))) AS distance";
    }
}


if($mb_level){
    //$search .= " and m.mb_level = 4 ";
}else{
    $search .= " and m.mb_level = 2 ";
}

$ss_id = session_id();
if($member["mb_id"]){
    $wished_id = $member["mb_id"];
    $search .= " and p.pd_id not in (select pd_id from `my_trash` where mb_id = '{$member[mb_id]}') ";
}else{
    $wished_id = $ss_id;
    $search .= " and p.pd_id not in (select pd_id from `my_trash` where mb_id = '{$ss_id}') ";
}

$total=sql_fetch("select count(*) as cnt from `product` where {$search} ");
if(!$page)
    $page=1;
$total=$total['cnt'];
$rows=10;
$start=($page-1)*$rows;
$total_page=ceil($total/$rows);

$sql = "select * {$sel} from `product` as p left join `g5_member` as m on p.mb_id = m.mb_id where {$search} {$od} ";
$res = sql_query($sql);
while($row = sql_fetch_array($res)){
    $list[] = $row;
}*/
include_once(G5_MOBILE_PATH."/head.map.php");

?>
<style>
    #map{margin-top: 17vw;height: calc(100vh - 17vw);}
    #map .wrap{width:26vw;background: #fff;padding: 10px;border-radius: 10px;top:0;left: -16vw;-webkit-box-shadow:  0 0 2px RGBA(0,0,0,0.4);-moz-box-shadow:  0 0 2px RGBA(0,0,0,0.4);box-shadow:  0 0 2px RGBA(0,0,0,0.4);}
    #map .wrap .close{position:absolute;top:1vw;right:1vw;width:4vw;height:4vw;}
    #map .wrap .info{width:100%;font-size: 3vw;word-break: keep-all;overflow: hidden;text-overflow: ellipsis;white-space: nowrap;padding: 3vw 0 0 0;}
    #map .wrap .info p{padding-top:2vw;font-weight:bold;font-size:4vw;text-align:center;}
    #map .wrap .button_area{width:100%;text-align:center;padding-top:3vw;}
    #map .wrap .button_area input{padding:1vw;font-size:3vw;border:none;-webkit-border-radius:5vw;-moz-border-radius:5vw;border-radius:5vw;background-color:#ffe700;color:#000;width:calc(100% - 2vw)}
    #map_list{position:absolute;bottom:0;display:none;width:100%;height:36vh;left:0;z-index:10;overflow:hidden;}
    #map_list ul{overflow-y:scroll;height:100%;}
    #map_list li.item{position:relative;clear:both;padding:1px;width:calc(100% - 2px);-webkit-box-shadow:  0 0 2px RGBA(0,0,0,0.4);-moz-box-shadow:  0 0 2px RGBA(0,0,0,0.4);box-shadow:  0 0 2px RGBA(0,0,0,0.4);margin-bottom: 1vw;display: inline-block;overflow: hidden}
    #map_list li.item .item_images{width:35vw;height:26vw;float:left}
    #map_list li.item .info{float: left;width: calc(65vw - 2px);position: relative;height:26vw;}
    #map_list li.item .info .top{background: #eee;position: absolute;top: 0;width: 100%;height: 6vw;}
    #map_list li.item .info .top h2{font-size: 4vw;position: absolute;left: 1vw;top: 1vw;color: #565656;}
    #map_list li.item .info .top div{position: relative;text-align: right;height: 100%;}
    #map_list li.item .info .top div ul{width: 50vw;text-align: right;right: 0;position: absolute;top: 1px;}
    #map_list li.item .info .top div ul li{float: right;font-size:3vw;margin:0 1vw;line-height:6vw;color:#565656}
    #map_list li.item .info .top div ul li img{width:4.4vw;margin-top:-1vw}
    #map_list li.item .info .bottom{position: absolute;bottom: 0;height: calc(26vw - 7vw);}
    #map_list li.item .info .bottom h2{font-size: 4vw;padding: 1vw 2vw;word-break: keep-all}
    #map_list li.item .info .bottom .price{font-size:4.3vw;width:100%;text-align:right;}
</style>
<div class="wrap" style="height:calc(100vh - 29vw);">
    <?php if($stx || $searchActive == "search"){?>
        <div id="map_list" style="display:block;">
            <ul>
                <?php for($i=0;$i<count($list);$i++){
                    if($list[$i]["pd_lat"]==0 && $list[$i]["pd_lng"]==0){
                        $dist = "정보없음";
                    }else {
                        $dist = round($list[$i]["distance"],1) . "km";
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
                    }?>
                    <li onclick="mapCenter('<?php echo $i;?>')" class="item">
                        <?php if($list[$i]["pd_images"]!=""){
                            $img = explode(",",$list[$i]["pd_images"]);
                            $img[0] = trim($img[0]);
                            $img1 = get_images(G5_DATA_PATH."/product/".$img[0],'','');
                            if(is_file(G5_DATA_PATH."/product/".$img1)){
                                ?>
                                <div class="item_images" style="background-image:url('<?php echo G5_DATA_URL?>/product/<?php echo $img1;?>');background-repeat:no-repeat;background-size:cover;background-position:center;min-height: 26vw;">
                                    <?php if($img1!=""){?>
                                        <img src="<?php echo G5_DATA_URL?>/product/<?php echo $img1;?>" alt="" class="main" style="opacity:0">
                                    <?php }else{ ?>
                                        <img src="<?php echo G5_IMG_URL?>/no-profile.svg" alt="" class="main" style="opacity:0">
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
                                            if(trim($tags[$k])!=""){
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
                                        if(trim($tags[$k])!=""){
                                            ?>
                                            <div class="rand_size<?php echo $rand_font;?>">#<?php echo $tags[$k];?></div>
                                        <?php } }?>
                                </div>
                                <div class="clear"></div>
                            </div>
                        <?php }?>
                        <div class="info">
                            <?php if($list[$i]["pd_name"]) {
                                switch ($list[$i]["pd_type2"]) {
                                    case "4":
                                        $pt2 = "[삽니다]";
                                        break;
                                }
                            }
                            ?>
                            <div class="top">
                                <h2><?php echo ($list[$i]["mb_level"]==4)?"<img src='".G5_IMG_URL."/ic_pro.svg'>":"　";?></h2>
                                <div>
                                    <ul>
                                        <?php if($app && $app2 || $list[$i]["distance"]){?>
                                        <li><img src="<?php echo G5_IMG_URL?>/ic_loc_list.svg" alt=""><?php echo $dist;?></li>
                                        <?php }?>
                                        <li><img src="<?php echo G5_IMG_URL?>/ic_hit_list.svg" alt=""> <?php echo $list[$i]["pd_hits"];?></li>
                                        <li><?php echo $time_gep;?></li>
                                    </ul>
                                    <div class="clear"></div>
                                </div>
                            </div>
                            <div class="bottom">
                                <h2><?php echo $pt2. " " . $list[$i]["pd_name"];?></h2>
                                <div class="price">
                                    <?php echo number_format($list[$i]["pd_price"]);?> 원
                                </div>
                            </div>
                        </div>
                    </li>
                <?php }?>
            </ul>
        </div>
    <?php }?>
	<div id="map" style="width:100%;<?php if($stx || $searchActive == "search"){?>height:calc(100vh - 20vw - 41vh);<?php }?>">
        <?php if($app || $app2){?>
        <div class="current" <?php if($app){?>onclick="fnReset();" <?php } if($app2){?>onclick="webkit.messageHandlers.getLocation.postMessage('latlng');"<?php } ?> >
            <img src="<?php echo G5_IMG_URL?>/ic_current_map.svg" alt="">
        </div>
        <?php }?>
    </div>

</div>
<script>
var lat='33.450701',lng='126.570667';
var map ;

<?php if($_SESSION["lat"] || $_SESSION["lng"]){?>
lat = "<?php echo $_SESSION["lat"];?>";
lng = "<?php echo $_SESSION["lng"];?>";
<?php }?>
<?php if($app){?>
var loc = window.android.getLocation();

var locs = loc.split("/");
lat = locs[0];
lng = locs[1];
if(locs[0] == ""){
    alert("위치정보가 없습니다.");
}
<?php }else{
    if($is_mameber){?>
lat = "<?php echo $member["mb_1"];?>";
lng = "<?php echo $member["mb_2"];?>";
<?php }else{ ?>
lat =   "36.642083";
lng =   "127.48938549980389";
<?php } }?>
var mapContainer = document.getElementById('map'), // 지도를 표시할 div
    mapOption = {
        center: new daum.maps.LatLng(lat, lng), // 지도의 중심좌표
        level: 7 // 지도의 확대 레벨
    };

map = new daum.maps.Map(mapContainer, mapOption); // 지도를 생성합니다

if(lat && lng) {
    <?php if(count($list) > 0) {?>
    // 마커를 표시할 위치와 title 객체 배열입니다
    var positions = [
        <?php for($i=0;$i<count($list);$i++){
            if($list[$i]["pd_lat"] && $list[$i]["pd_lng"]){
            $imgs = explode(",",$list[$i]["pd_images"]);
            ?>
        {
            title: "<?php echo $list[$i]["pd_name"];?>",
            price: "<?php echo $list[$i]["pd_price"];?>",
            latlng: new daum.maps.LatLng('<?php echo $list[$i]["pd_lat"];?>','<?php echo $list[$i]["pd_lng"];?>'),
            image: "<?php echo $imgs[0];?>",
            pd_id : "<?php echo $list[$i]["pd_id"];?>"
        },
        <?php } } ?>
        {
            title: '현재위치',
            latlng: new daum.maps.LatLng(lat, lng)
        }
    ];
    <?php }else {?>
    var positions = [
        {
            title: '현재위치',
            latlng: new daum.maps.LatLng(lat, lng)
        }
    ];
    <?php }?>
}else{
    var positions = [
        {
            title: '카카오',
            latlng: new daum.maps.LatLng(33.450705, 126.570677)
        },
        {
            title: '생태연못',
            latlng: new daum.maps.LatLng(33.450936, 126.569477)
        },
        {
            title: '텃밭',
            latlng: new daum.maps.LatLng(33.450879, 126.569940)
        },
        {
            title: '근린공원',
            latlng: new daum.maps.LatLng(33.451393, 126.570738)
        }
    ];
}

// 마커 이미지의 이미지 주소입니다
var imageSrc = "<?php echo G5_IMG_URL?>/view_pin.svg";
var imageSrc2 = "<?php echo G5_IMG_URL?>/ic_current_map.svg";
var overlay = [];
var markers = [];
var i = 0;
var bounds = new daum.maps.LatLngBounds();
positions.forEach(function(pos){
    // 마커 이미지의 이미지 크기 입니다
    var imageSize = new daum.maps.Size(30, 42);
    var markerImage;
    // 마커 이미지를 생성합니다
    if(pos.title=="현재위치") {
        markerImage = new daum.maps.MarkerImage(imageSrc2, imageSize);
    }else {
        markerImage = new daum.maps.MarkerImage(imageSrc, imageSize);
    }


    var marker = new daum.maps.Marker({
        map: map, // 마커를 표시할 지도
        position: pos.latlng, // 마커를 표시할 위치
        //title: positions[i].title, // 마커의 타이틀, 마커에 마우스를 올리면 타이틀이 표시됩니다
        image: markerImage // 마커 이미지
    });


    if(pos.title!="현재위치") {
        //bounds.extend(pos.latlng);

        var back = "";
        if (pos.image != "") {
            var img = pos.image;
            back = "background-image:url(" + g5_url + "/data/product/" + img + ");background-size:contain;background-position:center;background-repeat:no-repeat;";
        } else {
            back = "background-color:#efeea1;";
        }

        var price = ""
        if(pos.price != 0){
            price = pos.price + " 원";
        }else{
            price = "가격 제시"
        }
        
        var content = '<div class="wrap">' +
            '<div class="close" onclick="closeOverlay('+i+');"><img src="'+g5_url+'/img/ic_close_b.png" alt=""></div>' +
            '<div class="img" style="width:100%;height:20vw;min-height:20vw;max-width:100%;max-height:20vw;' + back + '"></div>' +
            '<div class="info">' + pos.title + '<p class="price">'+price+'</p></div>' +
            '<div class="button_area"><input type="button" value="바로가기" onclick="fn_viewer(\''+pos.pd_id+'\')"></div>' +
            '</div>';
        var overlays = new daum.maps.CustomOverlay({
            position:pos.latlng,
            map:map,
            content:content,
            xAnchor:0,
            yAnchor:1.2
        });

        markers.push(pos.latlng);
        overlay.push(overlays);

        daum.maps.event.addListener(marker,'click',function(){
            overlays.setMap(map);
        });
    }

    //bounds.extend(pos.latlng);
    //map.setBounds(bounds);

    i++;
});

function closeOverlay(num){
    if(num!=null || num!='') {
        overlay[num].setMap(null);
    }else{
        for(var i = 0; i < overlay.length ; i++){
            overlay[i].setMap(null);
        }
    }
}

setTimeout(function(){
    for(var i = 0; i < overlay.length ; i++){
        overlay[i].setMap(null);
    }
},1000);
function mapCenter(num){
    map.setCenter(markers[num]);
}

function fnReset(){
    var latlng = window.android.getLocation();
    var locs = latlng.split("/");
    // 이동할 위도 경도 위치를 생성합니다
    var moveLatLon = new daum.maps.LatLng(locs[0], locs[1]);

    // 지도 중심을 부드럽게 이동시킵니다
    // 만약 이동할 거리가 지도 화면보다 크면 부드러운 효과 없이 이동합니다
    map.panTo(moveLatLon);
}

function setLocation(lat,lng){
    // 이동할 위도 경도 위치를 생성합니다
    var moveLatLon = new daum.maps.LatLng(lat, lng);

    // 지도 중심을 부드럽게 이동시킵니다
    // 만약 이동할 거리가 지도 화면보다 크면 부드러운 효과 없이 이동합니다
    map.panTo(moveLatLon);
}

function fnlist(num,fn_list_type){
    map.refresh();
}

$(".category ul > li").click(function(){
    $(this).addClass("active");
    $(".category ul li").not($(this)).removeClass("active");
    var id = $(this).attr("id");
    $("."+id).addClass("active");
    $(".category2 ul").not($("."+id)).removeClass("active");
    $("html, body").css("overflow","hidden");
    $("html, body").css("height","100vh");
});

</script>
<?php 
include_once(G5_MOBILE_PATH."/tail.php");
?>
