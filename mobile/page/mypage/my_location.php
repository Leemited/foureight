<?php 
include_once("../../../common.php");
include_once(G5_MOBILE_PATH."/head.login.php");
if($member["mb_id"]==""){
	alert("로그인이 필요합니다.", G5_MOBILE_URL."/page/login_intro.php?url=".G5_MOBILE_URL."/page/mypage/settings.php");
}
$chkMobile = true;
$sql = "select * from `mysetting` where id= {$id} and mb_id = '{$member["mb_id"]}'";
$settings = sql_fetch($sql);

$mylocations = explode(",",$settings["my_locations"]);

$back_url=G5_MOBILE_URL."/page/mypage/settings.php";

?>
<style>
body{overflow: hidden}
#settings{height:calc(100vh - 30vw);overflow:hidden;position:relative}
#settings .setting_wrap ul li{padding:2.88vw;}
</style>
<div class="sub_head">
	<div class="sub_back" onclick="location.href='<?php echo $back_url;?>'"><img src="<?php echo G5_IMG_URL?>/ic_menu_back.svg" alt=""></div>
	<h2>거래 위치 설정</h2>
	<!-- <div class="sub_add">추가</div> -->
</div>
<div id="settings">
	<form action="<?php echo G5_MOBILE_URL?>/page/mypage/my_location_update.php" method="post" onsubmit="return false;">
		<div class="setting_wrap">
			<h2>거래 위치 목록</h2>
			<ul>
				<li>
                    <input type="text" class="setting_input" name="mylocation[]" id="locs1"  placeholder="예) 강남역 1번 출구" style="width:80%" value="<?php echo ($mylocations[0])?$mylocations[0]:"";?>">
                    <div class="map_set">
                        <?php if($app){?>
                        <img src="<?php echo G5_IMG_URL?>/view_pin_black.svg" alt="" onclick="nowLoc(1);">
                        <?php }?>
                        <img src="<?php echo G5_IMG_URL?>/ic_search.svg" alt="" onclick="mapSelect(1)">
                    </div>
                </li>
				<li>
                    <input type="text" class="setting_input" name="mylocation[]"  id="locs2"  placeholder="예) 강남역 1번 출구" style="width:80%" value="<?php echo ($mylocations[1])?$mylocations[1]:"";?>">
                    <div class="map_set">
                        <?php if($app){?>
                            <img src="<?php echo G5_IMG_URL?>/view_pin_black.svg" alt="" onclick="nowLoc(2);">
                        <?php }?>
                        <img src="<?php echo G5_IMG_URL?>/ic_search.svg" alt="" onclick="mapSelect(2)">
                    </div>
                </li>
				<li>
                    <input type="text" class="setting_input" name="mylocation[]"  id="locs3" placeholder="예) 강남역 1번 출구" style="width:80%" value="<?php echo ($mylocations[2])?$mylocations[2]:"";?>">
                    <div class="map_set">
                        <?php if($app){?>
                            <img src="<?php echo G5_IMG_URL?>/view_pin_black.svg" alt="" onclick="nowLoc(3);">
                        <?php }?>
                        <img src="<?php echo G5_IMG_URL?>/ic_search.svg" alt="" onclick="mapSelect(3)">
                    </div>
                </li>
				<li>
                    <input type="text" class="setting_input" name="mylocation[]"  id="locs4" placeholder="예) 강남역 1번 출구" style="width:80%" value="<?php echo ($mylocations[3])?$mylocations[3]:"";?>">
                    <div class="map_set">
                        <?php if($app){?>
                            <img src="<?php echo G5_IMG_URL?>/view_pin_black.svg" alt="" onclick="nowLoc(4);">
                        <?php }?>
                        <img src="<?php echo G5_IMG_URL?>/ic_search.svg" alt="" onclick="mapSelect(4)">
                    </div>
                </li>
				<li>
                    <input type="text" class="setting_input" name="mylocation[]"  id="locs5" placeholder="예) 강남역 1번 출구" style="width:80%" value="<?php echo ($mylocations[4])?$mylocations[4]:"";?>">
                    <div class="map_set">
                        <?php if($app){?>
                            <img src="<?php echo G5_IMG_URL?>/view_pin_black.svg" alt="" onclick="nowLoc(5);">
                        <?php }?>
                        <img src="<?php echo G5_IMG_URL?>/ic_search.svg" alt="" onclick="mapSelect(5)">
                    </div>
                </li>
			</ul>
			<div class="btn_group">
				<input type="submit" value="등록" class="setting_btn" >
			</div>
		</div>
	</form>
</div>
<div id="map_sel" style="z-index:900;background-color: #fff;">
    <input type="hidden" id="setnum" value="">
    <!--<img src="<?php /*echo G5_IMG_URL*/?>/view_pin.svg" alt="" class="map_pin">-->
    <div id="map" style="width:100%;height:40vh;"></div>
    <div class="loc_list">
        <ul class="loc_ul_list">

        </ul>
    </div>
    <div style="padding:2.8vw 0;text-align:center;">
        <input type="button" value="취소" onclick="mapSelect('')" style="border:none;width:48%;-webkit-border-radius: 10vw;-moz-border-radius: 10vw;border-radius: 10vw;background-color: #000;color:#fff;font-size:3vw;padding:2vw 0;">
        <input type="button" value="등록" onclick="mapSet();" style="border:none;width:48%;-webkit-border-radius: 10vw;-moz-border-radius: 10vw;border-radius: 10vw;background-color: #ffe100;color:#000;font-size:3vw;padding:2vw 0;">
    </div>
</div>
<script>
    var locitem = "";
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

    var infowindow = new daum.maps.InfoWindow({zIndex:9002});

    // 마커가 지도 위에 표시되도록 설정합니다
    marker.setMap(map);

    marker.setDraggable(true);

    // 장소 검색 객체를 생성합니다
    var ps = new daum.maps.services.Places();

    // 키워드 검색 완료 시 호출되는 콜백함수 입니다
    function placesSearchCB (data, status, pagination) {
        if (status === daum.maps.services.Status.OK) {
            marker = null;
            $(".loc_ul_list").html('');
            // 검색된 장소 위치를 기준으로 지도 범위를 재설정하기위해
            // LatLngBounds 객체에 좌표를 추가합니다
            var bounds = new daum.maps.LatLngBounds();
            var item="";
            for (var i=0; i<data.length; i++) {
                displayMarker(data[i]);
                var addr = data[i].address_name.split(" ");
                var addr_simple = "["+addr[0]+" "+addr[1]+"]";
                if(lat && lng) {
                    bounds.extend(new daum.maps.LatLng(lat, lng));
                }else{
                    bounds.extend(new daum.maps.LatLng(data[i].y,data[i].x));
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
            }
            // 검색된 장소 위치를 기준으로 지도 범위를 재설정합니다
            map.setBounds(bounds);
        }else{
            $(".loc_ul_list").append("<li>검색된 목록이 없습니다.</li>");
        }
    }

    var markers = [];

    var imageSrc = 'http://t1.daumcdn.net/localimg/localimages/07/mapapidoc/marker_red.png', // 마커이미지의 주소입니다
        imageSize = new daum.maps.Size(64, 69), // 마커이미지의 크기입니다
        imageOption = {offset: new daum.maps.Point(27, 69)}; // 마커이미지의 옵션입니다. 마커의 좌표와 일치시킬 이미지 안에서의 좌표를 설정합니다.
    var locitem;
    function setCenter(lat,lng,place_name,place_address,num) {
        // 이동할 위도 경도 위치를 생성합니다
        var moveLatLon = new daum.maps.LatLng(lat,lng);

        //markers[num].setMap(map)
        // 지도 중심을 이동 시킵니다
        map.setCenter(moveLatLon);

        locitem = "["+place_name+"]"+place_address;
    }

    // 지도에 마커를 표시하는 함수입니다
    function displayMarker(place) {

        // 마커를 생성하고 지도에 표시합니다
        var marker = new daum.maps.Marker({
            map: map,
            position: new daum.maps.LatLng(place.y, place.x)
        });

        // 마커에 클릭이벤트를 등록합니다
        daum.maps.event.addListener(marker, 'click', function() {
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

        markers.push(marker);
    }

    var mapon = false;
    function mapSelect(num){
        console.log($("#locs"+num).val());
        if($("#locs"+num).val()==""){
            alert("거래위치를 입력해주세요");
            return false;
        }
        var loc = $("#locs"+num).val();


        if(mapon==false) {
            ps.keywordSearch(loc, placesSearchCB);
            $("#setnum").val(num);
            $("#map_sel").css({"bottom": "0","top":"6vw","height":"100vh"});
            $("html, body").css("overflow","hidden");
            $("html, body").css("height","100vh");
            mapon = true;
        }else if(mapon==true || num == ''){
            $("#setnum").val('');
            $("#addr").val('');
            $("#map_sel").css({"bottom": "-100vw","top":"unset","height":"100vw"});
            mapon = false;
        }
    }

    function mapSet(){
        var num = $("#setnum").val();
        $(".loclist").html('');
        $("#locs"+num).val(locitem);
        $("#setnum").val('');
        //$("#addr").val('');
        $("#map_sel").css({"bottom": "-100vw","top":"unset","height":"100vw"});
        mapon = false;
    }

    function nowLoc(num){

    }

    $(function(){
        $(document).on("click",".loc_ul_list li",function(){
            if(!$(this).hasClass("active")){
                $(this).addClass("active");
                $(".loc_ul_list li").not($(this)).removeClass("active");
            }
        });
    })
</script>
<?php 
include_once(G5_PATH."/tail.php");
?>