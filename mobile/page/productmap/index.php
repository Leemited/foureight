<?php
include_once("../../../common.php");
//검색 기본값
$search = "p.pd_status = 0 and p.pd_blind < 10 and p.pd_blind_status = 0";

//검색 정렬 기본값
if($_SESSION["list_basic_order"]=="location"){
    $od = " order by ISNULL(p.pd_lat) asc,  distance asc, p.pd_update desc, p.pd_date desc";
}else {
    $od = " order by p.pd_update desc, p.pd_date desc";
}

if($schopt){

    $stx = $schopt["sc_tag"];

    $search .= " and ( p.pd_name like '%{$stx}%' or p.pd_tag like '%{$stx}%' or p.pd_content like '%{$stx}%')";

    $type1 = $schopt["sc_type"];
    $type2 = $schopt["sc_type2"];
    $cate1 = $schopt["sc_cate1"];
    $cate2 = $schopt["sc_cate2"];

    if($type1){
        $search .= " and p.pd_type = '{$type1}' ";
    }
    if($type2){
        $search .= " and p.pd_type2 = '{$type2}' ";
    }
    if($cate1){
        $search .= " and p.pd_cate = '{$cate1}' ";
    }
    if($cate2){
        $search .= " and p.pd_cate2 = '{$cate2}' ";
    }

    $priceFrom = $schopt["sc_priceFrom"];
    $priceTo = $schopt["sc_priceTo"];

    if($priceFrom!=0 && $priceTo!=0) {
        $search .= " and p.pd_price between '{$priceFrom}' and '{$priceTo}'";
    }

    $align = $schopt["sc_align"];
    $aligns = explode(",", $align);
    //정렬 초기화

    if($align !=""){
        $od = " order by ";
        for ($i = 0; $i < count($aligns); $i++) {
            switch ($aligns[$i]) {
                case "pd_date":
                    $align_active[$i] = $schopt["sc_od_date"];
                    if($schopt["sc_od_date"]==1){
                        if($od==" order by "){
                            $od .= " p.pd_date desc";
                        }else {
                            $od .= " , p.pd_date desc";
                        }
                    }
                    break;
                case "pd_price":
                    $align_active[$i] = $schopt["sc_od_price"];
                    if($schopt["sc_od_price"]==1){
                        if($od==" order by "){
                            $od .= " p.pd_price desc";
                        }else {
                            $od .= " , p.pd_price desc";
                        }
                    }
                    break;
                case "pd_recom":
                    $align_active[$i] = $schopt["sc_od_recom"];
                    if($schopt["sc_od_recom"]==1){
                        if($od==" order by "){
                            $od .= " p.pd_recom desc";
                        }else {
                            $od .= " , p.pd_recom desc";
                        }
                    }
                    break;
                case "pd_hit":
                    $align_active[$i] = $schopt["sc_od_hit"];
                    if($schopt["sc_od_hit"]==1){
                        if($od==" order by "){
                            $od .= " p.pd_hits desc";
                        }else {
                            $od .= " , p.pd_hits desc";
                        }
                    }
                    break;
                case "pd_loc":
                    $align_active[$i] = $schopt["sc_od_loc"];
                    if($_SESSION["lat"] && $_SESSION["lng"]){
                        if($schopt["sc_od_loc"]==1){
                            if($od==" order by "){
                                $od .= " distance desc";
                            }else {
                                $od .= " , distance desc";
                            }
                        }
                    }
                    break;
            }
        }
        $actives = implode(",", $align_active);
    }
}else{
    if($_SESSION["type1"]==1 || $_SESSION["type1"] == ""){
        $type1 = 1;
        $search .= " and p.pd_type = 1";
    }else if($_SESSION["type1"]==2){
        $search .= " and p.pd_type = 2";
    }
}

if($_SESSION["lat"] && $_SESSION["lng"]){
    $sel = " , 6371 * 2 * ATAN2(SQRT(POW(SIN(RADIANS({$_SESSION["lat"]} - p.pd_lat)/2), 2) + POW(SIN(RADIANS({$_SESSION["lng"]} - p.pd_lng)/2), 2) * COS(RADIANS(p.pd_lat)) * COS(RADIANS({$_SESSION["lat"]}))), SQRT(1 - POW(SIN(RADIANS({$_SESSION["lat"]} - p.pd_lat)/2), 2) + POW(SIN(RADIANS({$_SESSION["lng"]} - p.pd_lng)/2), 2) * COS(RADIANS(p.pd_lat)) * COS(RADIANS({$_SESSION["lat"]})))) AS distance";
}

$total=sql_fetch("select count(*) as cnt from `product` where {$search} ");
if(!$page)
    $page=1;
$total=$total['cnt'];
$rows=10;
$start=($page-1)*$rows;
$total_page=ceil($total/$rows);
$ss_id = session_id();
if($member["mb_id"]){
    $wished_id = $member["mb_id"];
    $search .= " and p.pd_id not in (select pd_id from `my_trash` where mb_id = '{$member[mb_id]}') ";
}else{
    $wished_id = $ss_id;
    $search .= " and p.pd_id not in (select pd_id from `my_trash` where mb_id = '{$ss_id}') ";
}
$sql = "select * {$sel} from `product` as p left join `g5_member` as m on p.mb_id = m.mb_id where {$search} {$od} limit {$start},{$rows}";
$iada = $sql;
$res = sql_query($sql);
while($row = sql_fetch_array($res)){
    $pro[] = $row;
}
include_once(G5_MOBILE_PATH."/head.map.php");

?>
<div class="wrap">
    <div class="current" onclick="fnReset();">
        <img src="<?php echo G5_IMG_URL?>/ic_current_map.svg" alt="">
    </div>
	<div id="map" style=""></div>
    <?php if($stx){?>
    <div id="container" >
        <section class="main_list">
            <article class="post" id="post">
                <div class="list_item grid are-images-unloaded" id="test">
                    <div class="grid__item type_list <?php if($flag){echo "wishedon";}?>" id="list_<?php echo $list[$i]['pd_id'];?>">
                        <?php echo $sqls;?>
                        <div>
                            <?php if($list[$i]["pd_images"]!=""){
                                $img = explode(",",$list[$i]["pd_images"]);
                                $img1 = get_images(G5_DATA_PATH."/product/".$img[0],'','');
                                if(is_file(G5_DATA_PATH."/product/".$img1)){
                                    ?>
                                    <div class="item_images" style="background-image:url('<?php echo G5_DATA_URL?>/product/<?php echo $img1;?>');background-repeat:no-repeat;background-size:cover;background-position:center;">
                                        <?php if($img1!=""){?>
                                            <img src="<?php echo G5_DATA_URL?>/product/<?php echo $img1;?>" alt="" class="main" style="opacity:0">
                                        <?php }else{ ?>
                                            <img src="<?php echo G5_IMG_URL?>/no-profile.svg" alt="" class="main" style="opacity:0">
                                        <?php }?>
                                    </div>
                                <?php }else{
                                    $tags = explode("/",$list[$i]["pd_tag"]);
                                    $rand = rand(1,13);
                                    ?>
                                    <div class="bg rand_bg<?php echo $rand;?> item_images" >
                                        <?php echo $search;?>
                                        <div class="tags">
                                            <?php echo "<br><br><br><br><br><br><br>".$align;?>

                                            <?php for($k=0;$k<count($tags);$k++){
                                                $rand_font = rand(3,6);
                                                ?>
                                                <div class="rand_size<?php echo $rand_font;?>">#<?php echo $tags[$k];?></div>
                                            <?php }?>
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
                                        <?php for($k=0;$k<count($tags);$k++){
                                            $rand_font = rand(3,6);
                                            ?>
                                            <div class="rand_size<?php echo $rand_font;?>">#<?php echo $tags[$k];?></div>
                                        <?php }?>
                                    </div>
                                    <div class="clear"></div>
                                </div>
                            <?php }?>
                            <div class="top">
                                <div>
                                    <h2><?php echo ($list[$i]["mb_level"]==4)?"전":"　";?></h2>
                                    <div>
                                        <ul>
                                            <li><img src="<?php echo G5_IMG_URL?>/ic_hit<?php if($list_type == "true"){echo "_list";}?>.svg" alt=""> <?php echo $list[$i]["pd_hits"];?></li>
                                            <?php if($chkMobile){?>
                                                <li><img src="<?php echo G5_IMG_URL?>/ic_loc<?php if($list_type == "true"){echo "_list";}?>.svg" alt=""><?php echo $dist;?></li>
                                            <?php }?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="bottom">
                                <?php if($list[$i]["pd_name"]){
                                    switch($list[$i]["pd_type2"]){
                                        case "4":
                                            $pt2 = "[삽니다]";
                                            break;
                                        case "8":
                                            $pt2 = "[팝니다]";
                                            break;
                                    }
                                    ?>
                                    <h2><?php echo $pt2." ".$list[$i]["pd_name"];?></h2>
                                <?php }?>
                                <div>
                                    <h1>￦ <?php echo number_format($list[$i]["pd_price"]);?></h1>
                                    <?php
                                    if($flag){?>

                                        <img src="<?php echo G5_IMG_URL?>/ic_wish_on.svg" alt="" class="wished" >
                                    <?php }else{ ?>
                                        <img src="<?php echo G5_IMG_URL?>/ic_wish.svg" alt="" class="wished" >
                                    <?php }?>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </article>
        </section>
    </div>
    <?php }?>
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
        level: 3 // 지도의 확대 레벨
    };

map = new daum.maps.Map(mapContainer, mapOption); // 지도를 생성합니다
//    var addrs = new Array();
//    <?php //for($i=0;$i<count($pro);$i++){?>
//        addrs[<?php //echo $i;?>//] = "<?php //echo $pro[$i]["pd_location"];?>//";
//    <?php //}?>
//    setLatLng(addrs);

if(lat && lng) {
    // 마커를 표시할 위치와 title 객체 배열입니다
    var positions = [
        <?php for($i=0;$i<count($pro);$i++){
            $imgs = explode(",",$pro[$i]["pd_images"]);
            ?>
        {
            title: "<?php echo $pro[$i]["pd_name"];?>",
            latlng: new daum.maps.LatLng(<?php echo $pro[$i]["pd_lat"];?>,<?php echo $pro[$i]["pd_lng"];?>),
            image: "<?php echo $imgs[0];?>",
            pd_id : "<?php echo $pro[$i]["pd_id"];?>"
        },
        <?php }?>
        {
            title: '현재위치',
            latlng: new daum.maps.LatLng(lat, lng)
        }
    ];
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
var i = 0;
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
        var back = "";
        if (pos.image != "") {
            var img = pos.image;
            back = "background-image:url(" + g5_url + "/data/product/" + img + ");background-size:cover;background-position:center;background-repeat:no-repeat;";
        } else {
            back = "background-color:#efeea1;";
        }

        var content = '<div class="wrap" onclick="closeOverlay('+i+');">' +
            '<div style="width:30vw;height:30vw;min-wdith:30vw;min-height:30vw;max-width:30vw;max-height:30vw;' + back + '"></div>' +
            '<div class="info">' + pos.title + '</div>' +
            '</div>';
        overlay[i] = new daum.maps.CustomOverlay({
            position:pos.latlng
        });

        overlay[i].setContent(content);
        overlay[i].setMap(map);
    }
    i++;
});

function closeOverlay(num){
    console.log(num);
    if(num!=null || num!='') {
        overlay[num].setMap(null);
    }else{
        for(var i = 0; i < overlay.length ; i++){
            overlay[i].setMap(null);
        }
    }
}

$(function(){
    closeOverlay('');
})
    // 인포윈도우를 표시하는 클로저를 만드는 함수입니다
    /*function makeOverListener(map, marker, infowindow) {
        return function() {
            infowindow.close();
            infowindow.open(map, marker);
        };
    }*/



/*
function getCoordsForEachAddr (data, bunchSize, oncomplete) {

    var geocoder = new daum.maps.services.Geocoder();
    var tryCount = 0;
    var doneCount = 0;
    var totalSize = data.length;
    var coordArray = [];

    function indexedCallback (index) {

        return function (status, result) {

            doneCount++;

            if (status === daum.maps.services.Status.OK) {

                var addr = result.addr[0];

                coordArray[index] = {
                    'lat': addr.lat,
                    'lng': addr.lng
                };

            } else {

                coordArray[index] = null;
            }

            if (doneCount === totalSize) {

                oncomplete(coordArray);

            } else if (doneCount === tryCount * bunchSize) {

                doRequest();
            }
        };
    }

    function doRequest () {

        var i = doneCount,
            len = Math.min(totalSize, i + bunchSize);

        tryCount++;

        for (; i < len; i++) {
            geocoder.addr2coord(data[i], indexedCallback(i));
        }
    }

    doRequest();
}

function setLatLng (addrs) {

    getCoordsForEachAddr(addrs, 500, function(data) {

        data.forEach(function(coord, i) {

            if (!coord) {

                return;
            }

            new daum.maps.Marker({
                map: map,
                title: addrs[i],
                position: new daum.maps.LatLng(coord.lat, coord.lng)
            });
        });
    });
}
*/
function fnReset(){
    var latlng = window.android.getLocation();
    var locs = latlng.split("/");
    // 이동할 위도 경도 위치를 생성합니다
    var moveLatLon = new daum.maps.LatLng(locs[0], locs[1]);

    // 지도 중심을 부드럽게 이동시킵니다
    // 만약 이동할 거리가 지도 화면보다 크면 부드러운 효과 없이 이동합니다
    map.panTo(moveLatLon);
}

</script>
<?php 
include_once(G5_MOBILE_PATH."/tail.php");
?>
