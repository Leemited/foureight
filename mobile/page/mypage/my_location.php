<?php 
include_once("../../../common.php");
include_once(G5_MOBILE_PATH."/head.login.php");
if($member["mb_id"]==""){
	alert("로그인이 필요합니다.", G5_BBS_URL."/login.php?url=".G5_MOBILE_URL."/page/mypage/settings.php");
}
$chkMobile = true;
$sql = "select * from `mysetting` where mb_id = '{$member[mb_id]}'";
$settings = sql_fetch($sql);

$mylocations = explode(",",$settings["my_locations"]);

$back_url=G5_MOBILE_URL."/page/mypage/settings.php";

?>
<style>
#settings{height:calc(100vh - 42vw);overflow:hidden;position:relative}
#settings .setting_wrap ul li{padding:2.88vw;}
</style>
<div class="sub_head">
	<div class="sub_back" onclick="location.href='<?php echo $back_url;?>'"><img src="<?php echo G5_IMG_URL?>/ic_menu_back.svg" alt=""></div>
	<h2>거래 위치 설정</h2>
	<!-- <div class="sub_add">추가</div> -->
</div>
<div id="settings">
	<form action="<?php echo G5_MOBILE_URL?>/page/mypage/my_location_update.php" method="post">
		<div class="setting_wrap">
			<h2>거래 위치 목록</h2>
			<ul>
				<li>
                    <input type="text" class="setting_input" name="mylocation[]"  placeholder="예) 강남역 1번 출구" value="<?php echo ($mylocations[0])?$mylocations[0]:"";?>">
                    <div class="map_set">
                        <?php if($chkMobile){?>
                        <img src="<?php echo G5_IMG_URL?>/view_pin_black.svg" alt="" onclick="nowLoc(1);">
                        <?php }?>
                        <img src="<?php echo G5_IMG_URL?>/setting_map.svg" alt="" onclick="mapSelect(1)">
                    </div>
                </li>
				<li>
                    <input type="text" class="setting_input" name="mylocation[]"  placeholder="예) 강남역 1번 출구" value="<?php echo ($mylocations[1])?$mylocations[1]:"";?>">
                    <div class="map_set">
                        <?php if($chkMobile){?>
                            <img src="<?php echo G5_IMG_URL?>/view_pin_black.svg" alt="" onclick="nowLoc(2);">
                        <?php }?>
                        <img src="<?php echo G5_IMG_URL?>/setting_map.svg" alt="" onclick="mapSelect(2)">
                    </div>
                </li>
				<li>
                    <input type="text" class="setting_input" name="mylocation[]"  placeholder="예) 강남역 1번 출구" value="<?php echo ($mylocations[2])?$mylocations[2]:"";?>">
                    <div class="map_set">
                        <?php if($chkMobile){?>
                            <img src="<?php echo G5_IMG_URL?>/view_pin_black.svg" alt="" onclick="nowLoc(3);">
                        <?php }?>
                        <img src="<?php echo G5_IMG_URL?>/setting_map.svg" alt="" onclick="mapSelect(3)">
                    </div>
                </li>
				<li>
                    <input type="text" class="setting_input" name="mylocation[]"  placeholder="예) 강남역 1번 출구" value="<?php echo ($mylocations[3])?$mylocations[3]:"";?>">
                    <div class="map_set">
                        <?php if($chkMobile){?>
                            <img src="<?php echo G5_IMG_URL?>/view_pin_black.svg" alt="" onclick="nowLoc(4);">
                        <?php }?>
                        <img src="<?php echo G5_IMG_URL?>/setting_map.svg" alt="" onclick="mapSelect(4)">
                    </div>
                </li>
				<li>
                    <input type="text" class="setting_input" name="mylocation[]"  placeholder="예) 강남역 1번 출구" value="<?php echo ($mylocations[4])?$mylocations[4]:"";?>">
                    <div class="map_set">
                        <?php if($chkMobile){?>
                            <img src="<?php echo G5_IMG_URL?>/view_pin_black.svg" alt="" onclick="nowLoc(5);">
                        <?php }?>
                        <img src="<?php echo G5_IMG_URL?>/setting_map.svg" alt="" onclick="mapSelect(5)">
                    </div>
                </li>
			</ul>
			<div class="btn_group">
				<input type="submit" value="등록" class="setting_btn">
			</div>
		</div>
	</form>
    <div id="map_sel" style="">
        <input type="hidden" name="addr" id="addr">
        <input type="hidden" name="setnum" id="setnum">
        <img src="<?php echo G5_IMG_URL?>/view_pin.svg" alt="" class="map_pin">
        <div id="map" style="width:100%;height:63vw;"></div>
        <div style="padding:2.8vw 0;text-align:center;">
            <input type="button" value="취소" onclick="mapSelect('')" style="border:none;width:48%;-webkit-border-radius: 10vw;-moz-border-radius: 10vw;border-radius: 10vw;background-color: #000;color:#fff;font-size:3vw;padding:2vw 0;">
            <input type="button" value="확인" onclick="mapSet();" style="border:none;width:48%;-webkit-border-radius: 10vw;-moz-border-radius: 10vw;border-radius: 10vw;background-color: #ffe100;color:#000;font-size:3vw;padding:2vw 0;">
        </div>
    </div>
</div>
<script>
    var lat = '33.450701';
    var lng = '126.570667';
    <?php if($_SESSION["lat"] && $_SESSION["lng"]){?>
        lat = "<?php echo $_SESSION["lat"];?>";
        lng = "<?php echo $_SESSION["lng"];?>";
    <?php }?>
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

    var geocoder = new daum.maps.services.Geocoder();

    var imageSrc = g5_url+'/img/view_pin.svg', // 마커이미지의 주소입니다
        imageSize = new daum.maps.Size(36, 52), // 마커이미지의 크기입니다
        imageOption = {offset: new daum.maps.Point(12, 52)}; // 마커이미지의 옵션입니다. 마커의 좌표와 일치시킬 이미지 안에서의 좌표를 설정합니다.


    // 지도가 이동, 확대, 축소로 인해 중심좌표가 변경되면 마지막 파라미터로 넘어온 함수를 호출하도록 이벤트를 등록합니다
    daum.maps.event.addListener(map, 'center_changed', function() {
        marker=null;
        // 지도의 중심좌표를 얻어옵니다
        var latlng = map.getCenter();

        searchDetailAddrFromCoords(latlng, function(result, status) {
            if (status === daum.maps.services.Status.OK) {
                $("#addr").val(result[0].address.region_1depth_name+" "+result[0].address.region_2depth_name+" "+result[0].address.region_3depth_name);
            }
        });

    });

    function searchDetailAddrFromCoords(coords,callback) {
        // 좌표로 법정동 상세 주소 정보를 요청합니다
        geocoder.coord2Address(coords.getLng(), coords.getLat(),callback);
    }
    var mapon = false;
    function mapSelect(num){
        if(mapon==false) {
            $("#setnum").val(num);
            $("#map_sel").css({"bottom": "0"});
            mapon = true;
        }else if(mapon==true || num == ''){
            $("#setnum").val('');
            $("#addr").val('');
            $("#map_sel").css({"bottom": "-100vh"});
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
        $("#map_sel").css({"bottom": "-50vh"});
        mapon = false;
    }

    function nowLoc(num){
        if(lat && lng){
            alert(lat + "//" + lng);
            var position = new daum.maps.LatLng(lat,lng);
            searchDetailAddrFromCoords(position, function(result, status) {
                if (status === daum.maps.services.Status.OK) {
                    $("#addr").val(result[0].address.region_1depth_name+" "+result[0].address.region_2depth_name+" "+result[0].address.region_3depth_name);
                    var addr = result[0].address.region_1depth_name+" "+result[0].address.region_2depth_name+" "+result[0].address.region_3depth_name;
                    $("input[name^=mylocation]").each(function(e){
                        if(e == (num-1)){
                            $(this).val(addr);
                        }
                    });
                }
            });
        }else {
            var latlng = window.android.getLocation();
            alert(latlng);
            if(latlng!=""){
                var loc = latlng.split("/");
                var position = new daum.maps.LatLng(loc[0],loc[1]);
                searchDetailAddrFromCoords(position, function(result, status) {
                    if (status === daum.maps.services.Status.OK) {
                        $("#addr").val(result[0].address.region_1depth_name+" "+result[0].address.region_2depth_name+" "+result[0].address.region_3depth_name);
                        var addr = result[0].address.region_1depth_name+" "+result[0].address.region_2depth_name+" "+result[0].address.region_3depth_name;
                        $("input[name^=mylocation]").each(function(e){
                           if(e == (num-1)){
                               $(this).val(addr);
                           }
                        });
                    }
                });
            }
        }

    }
</script>
<?php 
include_once(G5_PATH."/tail.php");
?>