<?php 
include_once("../../../common.php");
include_once(G5_MOBILE_PATH."/head.login.php");
if($member["mb_id"]==""){
	alert("로그인이 필요합니다.", G5_MOBILE_URL."/page/login_intro.php?url=".G5_MOBILE_URL."/page/mypage/settings.php");
}
$sql = "select * from `mysetting` where id= {$id} and mb_id = '{$member["mb_id"]}'";
$settings = sql_fetch($sql);

$mylocations = explode(",",$settings["my_locations"]);
$mylat = explode(",",$settings["location_lat"]);
$mylng = explode(",",$settings["location_lng"]);


$back_url=G5_MOBILE_URL."/page/mypage/settings.php";
?>
<style>
body{overflow: hidden}
#settings{height:calc(100vh - 28vw);overflow-y:scroll;position:relative}
#settings .setting_wrap ul li{padding:2.88vw;}
</style>
<div class="sub_head">
	<div class="sub_back" onclick="location.href='<?php echo $back_url;?>'"><img src="<?php echo G5_IMG_URL?>/ic_menu_back.svg" alt=""></div>
	<h2>거래 위치 설정</h2>
    <div class="all_clear" onclick="fnLocationReset();"><img src="<?php echo G5_IMG_URL?>/ic_menu_trash_all.svg" alt=""></div>
</div>
<div id="settings">
    <input type="hidden" value="" id="changelat">
    <input type="hidden" value="" id="changelng">
	<!--<form action="<?php /*echo G5_MOBILE_URL*/?>/page/mypage/my_location_update.php" method="post" onsubmit="return false;">-->
		<div class="setting_wrap">
			<h2>거래 위치 목록</h2>
			<ul>
				<li>
                    <input type="text" class="setting_input" id="locs1" placeholder="예) 강남역 1번 출구" style="width:80%" value="<?php echo ($mylocations[0])?$mylocations[0]:"";?>">
                    <div class="map_set">
                        <?php /*if($app){*/?><!--
                        <img src="<?php /*echo G5_IMG_URL*/?>/view_pin_black.svg" alt="" onclick="nowLoc(1);">
                        --><?php /*}*/?>
                        <img src="<?php echo G5_IMG_URL?>/ic_close_locset.svg" alt="" onclick="mapDelete(1)">
                        <img src="<?php echo G5_IMG_URL?>/ic_search.svg" alt="" onclick="mapSelect(1)">
                    </div>
                </li>
				<li>
                    <input type="text" class="setting_input" id="locs2" placeholder="예) 강남역 1번 출구" style="width:80%" value="<?php echo ($mylocations[1])?$mylocations[1]:"";?>">
                    <div class="map_set">
                        <?php /*if($app){*/?><!--
                            <img src="<?php /*echo G5_IMG_URL*/?>/view_pin_black.svg" alt="" onclick="nowLoc(2);">
                        --><?php /*}*/?>
                        <img src="<?php echo G5_IMG_URL?>/ic_close_locset.svg" alt="" onclick="mapDelete(2)">
                        <img src="<?php echo G5_IMG_URL?>/ic_search.svg" alt="" onclick="mapSelect(2)">
                    </div>
                </li>
				<li>
                    <input type="text" class="setting_input" id="locs3" placeholder="예) 강남역 1번 출구" style="width:80%" value="<?php echo ($mylocations[2])?$mylocations[2]:"";?>">
                    <div class="map_set">
                        <?php /*if($app){*/?><!--
                            <img src="<?php /*echo G5_IMG_URL*/?>/view_pin_black.svg" alt="" onclick="nowLoc(3);">
                        --><?php /*}*/?>
                        <img src="<?php echo G5_IMG_URL?>/ic_close_locset.svg" alt="" onclick="mapDelete(3)">
                        <img src="<?php echo G5_IMG_URL?>/ic_search.svg" alt="" onclick="mapSelect(3)">
                    </div>
                </li>
				<li>
                    <input type="text" class="setting_input" id="locs4" placeholder="예) 강남역 1번 출구" style="width:80%" value="<?php echo ($mylocations[3])?$mylocations[3]:"";?>">
                    <div class="map_set">
                        <?php /*if($app){*/?><!--
                            <img src="<?php /*echo G5_IMG_URL*/?>/view_pin_black.svg" alt="" onclick="nowLoc(4);">
                        --><?php /*}*/?>
                        <img src="<?php echo G5_IMG_URL?>/ic_close_locset.svg" alt="" onclick="mapDelete(4)">
                        <img src="<?php echo G5_IMG_URL?>/ic_search.svg" alt="" onclick="mapSelect(4)">
                    </div>
                </li>
				<li>
                    <input type="text" class="setting_input" id="locs5" placeholder="예) 강남역 1번 출구" style="width:80%" value="<?php echo ($mylocations[4])?$mylocations[4]:"";?>">
                    <div class="map_set">
                        <?php /*if($app){*/?><!--
                            <img src="<?php /*echo G5_IMG_URL*/?>/view_pin_black.svg" alt="" onclick="nowLoc(5);">
                        --><?php /*}*/?>
                        <img src="<?php echo G5_IMG_URL?>/ic_close_locset.svg" alt="" onclick="mapDelete(5)">
                        <img src="<?php echo G5_IMG_URL?>/ic_search.svg" alt="" onclick="mapSelect(5)">
                    </div>
                </li>
			</ul>
<!--			<div class="btn_group">
				<input type="button" value="등록" class="setting_btn" onclick="fnSubmit()" >
			</div>-->
		</div>
    <form action="<?php echo G5_MOBILE_URL?>/page/mypage/my_location_update.php" method="post" name="mylocation_form" >
        <input type="hidden" name="mylocation[]" id="location1" value="<?php echo ($mylocations[0])?$mylocations[0]:"";?>">
        <input type="hidden" name="mylat[]" id="mylat1" value="<?php echo ($mylat[0])?$mylat[0]:"";?>">
        <input type="hidden" name="mylng[]" id="mylng1" value="<?php echo ($mylng[0])?$mylng[0]:"";?>">
        <input type="hidden" name="mylocation[]" id="location2" value="<?php echo ($mylocations[1])?$mylocations[1]:"";?>">
        <input type="hidden" name="mylat[]" id="mylat2" value="<?php echo ($mylat[1])?$mylat[1]:"";?>">
        <input type="hidden" name="mylng[]" id="mylng2" value="<?php echo ($mylng[1])?$mylng[1]:"";?>">
        <input type="hidden" name="mylocation[]" id="location3" value="<?php echo ($mylocations[2])?$mylocations[2]:"";?>">
        <input type="hidden" name="mylat[]" id="mylat3" value="<?php echo ($mylat[2])?$mylat[2]:"";?>">
        <input type="hidden" name="mylng[]" id="mylng3" value="<?php echo ($mylng[2])?$mylng[2]:"";?>">
        <input type="hidden" name="mylocation[]" id="location4" value="<?php echo ($mylocations[3])?$mylocations[3]:"";?>">
        <input type="hidden" name="mylat[]" id="mylat4" value="<?php echo ($mylat[3])?$mylat[3]:"";?>">
        <input type="hidden" name="mylng[]" id="mylng4" value="<?php echo ($mylng[3])?$mylng[3]:"";?>">
        <input type="hidden" name="mylocation[]" id="location5" value="<?php echo ($mylocations[4])?$mylocations[4]:"";?>">
        <input type="hidden" name="mylat[]" id="mylat5" value="<?php echo ($mylat[4])?$mylat[4]:"";?>">
        <input type="hidden" name="mylng[]" id="mylng5" value="<?php echo ($mylng[4])?$mylng[4]:"";?>">
    </form>
	<!--</form>-->
</div>
<div id="map_sel" style="z-index:900;background-color: #fff;">
    <input type="hidden" id="setnum" value="">
    <!--<img src="<?php /*echo G5_IMG_URL*/?>/view_pin.svg" alt="" class="map_pin">-->
    <div id="map" style="width:100%;height:30vh;"></div>
    <div class="loc_list">
        <ul class="loc_ul_list">

        </ul>
    </div>
    <div class="map_info" style="-webkit-border-radius: 4vw;-moz-border-radius: 4vw;border-radius: 4vw;position:relative;width:80%;z-index:9;height:3vw;overflow: hidden;margin:2vw auto 0 auto;">
        <div style="position:absolute;top:50%;left:50%;-webkit-transform: translate(-50%,-50%);-moz-transform: translate(-50%,-50%);-ms-transform: translate(-50%,-50%);-o-transform: translate(-50%,-50%);transform: translate(-50%,-50%);color:#000;font-weight:bold;font-size:2.5vw;width:100%;display:inline-block;text-align: center;">지도의 위치를 터치하시면 상세주소가 추가됩니다.</div>
        <!--<div style="width:100%;background-color:rgba(0,0,0,.8);height:100%;"></div>-->
    </div>
    <div id="map_sel_btn" style="padding:2.8vw 0;text-align:center;height:16vw;">
        <input type="button" value="취소" onclick="mapSelect('')" style="border:none;width:48%;-webkit-border-radius: 10vw;-moz-border-radius: 10vw;border-radius: 10vw;background-color: #000;color:#fff;font-size:3vw;padding:2vw 0;">
        <input type="button" value="등록" onclick="mapSet();" style="border:none;width:48%;-webkit-border-radius: 10vw;-moz-border-radius: 10vw;border-radius: 10vw;background-color: #ffe100;color:#000;font-size:3vw;padding:2vw 0;">
    </div>
</div>
<script>
    var itemadd = '',addrs = '';
    var lat = '';
    var lng = '';
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
                var i = $(".loc_ul_list li").length;
                item += "<li class='active' onclick=\"setCenter(\'"+mouseEvent.latLng.Ha+"\',\'"+mouseEvent.latLng.Ga+"\',\'"+result[0].address.address_name+"\',\'"+result[0].address.address_name+"\',\'"+i+"\')\" >";
                item += result[0].address.address_name;
                item += "</li>";
                $(".loc_ul_list li").removeClass("active");
                $(".loc_ul_list").prepend(item);

                //locitem = '<div class="myloc">'+result[0].address.address_name+'<img src="'+g5_url+'/img/ic_write_close.svg" alt="" class="locsDel"><input type="hidden" value="'+result[0].address.address_name+'" name="locs" id="locs"/><input type="hidden" value="'+result[0].address.address_name+'" name="locs_name" id=""/>' + '<input type="hidden" value="'+mouseEvent.latLng.jb+'" name="pd_lat" id=""/><input type="hidden" value="'+mouseEvent.latLng.ib+'" name="pd_lng" id=""/></div>';
                locitem = result[0].address.address_name;
                lat = mouseEvent.latLng.Ga;
                lng = mouseEvent.latLng.Ha;
                displayMarker(data);
                //setCookie("pd_location",result[0].address.address_name,'1');
                //setCookie("pd_location_name",result[0].address.address_name,'1');
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
            $(".loc_ul_list").html('');
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
            }
            // 검색된 장소 위치를 기준으로 지도 범위를 재설정합니다
            map.setBounds(bounds);
        }else{
            $(".loc_ul_list").append("<li>검색된 목록이 없습니다.</li>");
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

        if(place_name) {
            locitem = place_name;
        }else{
            locitem = place_address;
        }
        $("#changelat").val(lat);
        $("#changelng").val(lng);
        //setCookie("pd_location",place_address,'1');
        //setCookie("pd_location_name",place_name,'1');
    }

    // 지도에 마커를 표시하는 함수입니다
    function displayMarker(place) {
        console.log(place);
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

    var mapon = false;
    function mapSelect(num,haskey){
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
            $(".modal").css("background","rgba(0,0,0,.85");

            mapon = true;
            location.hash = "#mapsel";

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
            $("#map_sel").css({"bottom": "-100vw","top":"unset","height":"100vw"});
            $("html, body").css("overflow","hidden");
            $("html, body").css("height","100vh");
            $(".modal").css("background","rgba(0,0,0,.6");

            $(".loc_ul_list li").remove();
            setMarkers(null);
            markers = [];
            mapon = false;
            location.hash = "";
        }
    }

    function mapSet(){
        /*var num = $("#setnum").val();
        $(".loclist").html('');
        $("#locs"+num).val(locitem);
        $("#setnum").val('');
        //$("#addr").val('');
        $("#map_sel").css({"bottom": "-100vw","top":"unset","height":"100vw"});
        mapon = false;*/
        var num = $("#setnum").val();

        $.ajax({
            url:g5_url+'/mobile/page/ajax/ajax.my_location_update.php',
            method:"post",
            data:{locitem:locitem,mylat:$("#changelat").val(),mylng:$("#changelng").val(),num:num}
        }).done(function(data){
            console.log(data);
            if(data=="1"){
                alert("선택된 위치가 없습니다.");
                return false;
            }else if(data=="2"){
                alert("등록정보 오류 입니다.");
                return false;
            }else if(data=="4"){
                alert("업데이트 오류 입니다.");
                return false;
            }else {
                $("#locs" + num).val(locitem);
                $("#location" + num).val(locitem);
                $("#mylat" + num).val($("#changelat").val());
                $("#mylng" + num).val($("#changelng").val());
                $("#map_sel").css({"bottom": "-100vh", "top": "unset", "height": "100vw"});
                $(".loclist").html('');
                $("#setnum").val('');
                $("#addr").val('');
                mapon = false;
                locitem = "";
                setMarkers(null);
                markers = [];
            }
        });
    }

    function mapDelete(num){
        var locitem = $("#locs"+num).val();
        $.ajax({
            url:g5_url+'/mobile/page/ajax/ajax.my_location_delete.php',
            method:"post",
            data:{num:num,locitem:locitem}
        }).done(function(data){
            if(data=="1"){
                alert("삭제할 위치를 선택해주세요.");
            }else if(data=="2"){
                alert("삭제할 위치의 정보가 없습니다.");
            }else if(data=="4"){
                alert("삭제 오류입니다.");
            }else{
                $("#locs" + num).val('');
                $("#location" + num).val('');
                $("#mylat" + num).val('');
                $("#mylng" + num).val('');
                $(".loclist").html('');
                $("#setnum").val('');
                $("#addr").val('');
                mapon = false;
                locitem = "";
                setMarkers(null);
                markers = [];
                $("#debug").addClass("active");
                $("#debug").html("거래위치가 삭제되었습니다.");
                setTimeout(removeDebug, 1500);
            }
        });
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

        searchDetailAddrFromCoords(latlng, function(result, status) {
            if (status === kakao.maps.services.Status.OK) {
                var detailAddr = !!result[0].road_address ? '<div>도로명주소 : ' + result[0].road_address.address_name + '</div>' : '';
                detailAddr += '<div>지번 주소 : ' + result[0].address.address_name + '</div>';

                var item = result[0].address.address_name;

                $("#locs"+num).val(item);

            }
        });
    }

    $(function(){
        $(document).on("click",".loc_ul_list li",function(){
            if(!$(this).hasClass("active")){
                $(this).addClass("active");
                $(".loc_ul_list li").not($(this)).removeClass("active");
            }
        });
    })

    $("input[id^=locs]").each(function(){
        <?php if(!$app){?>
        $(this).keyup(function(e){
            var id = $(this).attr("id");
            id = id.replace("locs","");
            if(e.keyCode == 13){
                mapSelect(id);
            }
        });
        <?php }?>
    });

    function mapKeySet(){
        var id = $(document.activeElement).attr("id");
        id = id.replace("locs","");
        window.android.HideKeyboard();
        mapSelect(id);
    }

    // 배열에 추가된 마커들을 지도에 표시하거나 삭제하는 함수입니다
    function setMarkers(maps) {
        for (var i = 0; i < markers.length; i++) {
            markers[i].setMap(maps);
        }
    }

    function fnSubmit(){
        document.mylocation_form.submit();
    }
    function fnLocationReset(){
        location.href=g5_url+'/mobile/page/mypage/my_location_delete.php';
    }
</script>
<?php 
include_once(G5_PATH."/tail.php");
?>