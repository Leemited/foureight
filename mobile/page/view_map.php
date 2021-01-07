<?php
include_once ("../../common.php");

//include_once (G5_MOBILE_PATH."/head.login.php");
?>
<div class="sub_head" style="background-color:#fff">
    <div class="sub_back" onclick="mapViewClose()"><img src="<?php echo G5_IMG_URL?>/ic_menu_back.svg" alt=""></div>
    <h2 style="background-color:transparent;font-size: 4.2vw;padding: 2.6vw;width:100%;"><img src="<?php echo G5_IMG_URL?>/view_pin_black.svg" alt=""><?php echo $location;?></h2>
</div>
<div id="container" style="height:100%;">
    <div id="map"></div>
</div>
<script>
    var lat = '';
    var lng = '';
    var coords = '';
    $(function(){
        var geocoder = new kakao.maps.services.Geocoder();

        var loc = "<?php echo $location;?>";
        var pd_lat = "<?php echo $lat;?>";
        var pd_lng = "<?php echo $lng;?>";
        // 주소로 좌표를 검색합니다
        geocoder.addressSearch(loc, function(result, status) {
            // 정상적으로 검색이 완료됐으면
            if (status === kakao.maps.services.Status.OK) {
                console.log(status);
                lat = result[0].y;
                lng = result[0].x;
                coords = new kakao.maps.LatLng(result[0].y, result[0].x);

                var mapContainer = document.getElementById('map'), // 지도를 표시할 div
                    mapOption = {
                        center: new kakao.maps.LatLng(lat, lng), // 지도의 중심좌표
                        level: 3 // 지도의 확대 레벨
                    };

                var map = new kakao.maps.Map(mapContainer, mapOption); // 지도를 생성합니다

                var imageSrc = g5_url+'/img/view_pin.svg', // 마커이미지의 주소입니다
                    imageSize = new kakao.maps.Size(36, 52), // 마커이미지의 크기입니다
                    imageOption = {offset: new kakao.maps.Point(12, 52)}; // 마커이미지의 옵션입니다. 마커의 좌표와 일치시킬 이미지 안에서의 좌표를 설정합니다.

                // 마커의 이미지정보를 가지고 있는 마커이미지를 생성합니다
                var markerImage = new kakao.maps.MarkerImage(imageSrc, imageSize, imageOption),
                    markerPosition = new kakao.maps.LatLng(lat, lng); // 마커가 표시될 위치입니다

                // 마커를 생성합니다
                var marker = new kakao.maps.Marker({
                    position: markerPosition,
                    image: markerImage // 마커이미지 설정
                });

                marker.setMap(map);

                // 주소-좌표 변환 객체를 생성합니다

                function searchDetailAddrFromCoords(coords,callback) {
                    // 좌표로 법정동 상세 주소 정보를 요청합니다
                    geocoder.coord2Address(coords.getLng(), coords.getLat(),callback);
                }

                searchDetailAddrFromCoords(markerPosition, function(result, status) {
                    if (status === kakao.maps.services.Status.OK) {
                        $("#addr_label").text(result[0].address.region_1depth_name+" "+result[0].address.region_2depth_name+" "+result[0].address.region_3depth_name);
                    }
                });
            }else{
                coords = new kakao.maps.LatLng(pd_lat, pd_lng);

                var mapContainer = document.getElementById('map'), // 지도를 표시할 div
                    mapOption = {
                        center: new kakao.maps.LatLng(pd_lat, pd_lng), // 지도의 중심좌표
                        level: 3 // 지도의 확대 레벨
                    };

                var map = new kakao.maps.Map(mapContainer, mapOption); // 지도를 생성합니다

                var imageSrc = g5_url+'/img/view_pin.svg', // 마커이미지의 주소입니다
                    imageSize = new kakao.maps.Size(36, 52), // 마커이미지의 크기입니다
                    imageOption = {offset: new kakao.maps.Point(12, 52)}; // 마커이미지의 옵션입니다. 마커의 좌표와 일치시킬 이미지 안에서의 좌표를 설정합니다.

                // 마커의 이미지정보를 가지고 있는 마커이미지를 생성합니다
                var markerImage = new kakao.maps.MarkerImage(imageSrc, imageSize, imageOption),
                    markerPosition = new kakao.maps.LatLng(pd_lat, pd_lng); // 마커가 표시될 위치입니다

                // 마커를 생성합니다
                var marker = new kakao.maps.Marker({
                    position: markerPosition,
                    image: markerImage // 마커이미지 설정
                });

                marker.setMap(map);

                // 주소-좌표 변환 객체를 생성합니다

                function searchDetailAddrFromCoords(coords,callback) {
                    // 좌표로 법정동 상세 주소 정보를 요청합니다
                    geocoder.coord2Address(coords.getLng(), coords.getLat(),callback);
                }

                searchDetailAddrFromCoords(markerPosition, function(result, status) {
                    if (status === kakao.maps.services.Status.OK) {
                        $("#addr_label").text(result[0].address.region_1depth_name+" "+result[0].address.region_2depth_name+" "+result[0].address.region_3depth_name);
                    }
                });
            }
        });

        location.hash = "#mapView";
    });
</script>
<?php
//include_once (G5_MOBILE_PATH."/tail.php")
?>
