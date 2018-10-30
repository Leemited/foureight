<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

if(defined('G5_THEME_PATH')) {
    require_once(G5_THEME_PATH.'/tail.php');
    return;
}
?>

<div id="debug" style="">삭제상태</div>

<?php
//if(G5_DEVICE_BUTTON_DISPLAY && G5_IS_MOBILE) { 
?>

<!-- <a href="<?php echo get_device_change_url(); ?>" id="device_change">PC 버전으로 보기</a> -->

<?php
//}

if ($config['cf_analytics']) {
    echo $config['cf_analytics'];
}
?>


<script>
function fnPricingUpdate(){
    var pd_id = $("#p_pd_id").val();
    var pricing_pd_id = $("#id07 select").val();
    var pricing_content = $("#pricing_content").val();
    var mb_id = "<?php echo $member["mb_id"];?>";
    $.ajax({
        url:g5_url+"/mobile/page/ajax/ajax.pricing_update.php",
        method:"POST",
        data:{pd_id:pd_id,pricing_pd_id:pricing_pd_id,pricing_content:pricing_content,mb_id:mb_id}
    }).done(function(data){
       console.log(data);
       if(data=="0"){
           alert("로그인이 필요합니다.");
           location.href=g5_url+'/mobile/page/login_intro.php';
           return false;
       }if(data=="1"){
           alert("게시글 정보가 없습니다.");
           return false;
        }if(data=="2"){
           alert("제시힐 게시물을 선택해 주세요.");
           return false;
        }if(data=="3"){
            alert("내용을 입력해주세요.");
            return false;
        }if(data=="5"){
            alert("잘못된 요청입니다. 다시 시도해 주세요.");
            return false;
        }
        if(data=="4"){
            alert("제시가 등록되었습니다.");
            modalClose();
        }
    });
}
function fn_viewer(id){
    if(id==""){
        alert("잘못된 요청입니다.");
        return false;
    }
    if($("#list_"+id).hasClass("blinds")){
        return false;
    }

    var width = $("#dWidth").val();
    var height = $("#dHeight").val();
    var url = g5_url+"/mobile/page/view.php";
    window.oriScroll = $(document).scrollTop();
    $.ajax({
        url : url,
        method:"POST",
        data:{pd_id:id,dWidth:width,dHeight:height}
    }).done(function(data){
        //console.log(data);
        location.hash = "#view";
        $("#id0s div.con").html('');
        $("#id0s div.con").append(data);
        $("#id0s").css("display","block");
        $("html, body").css("overflow","hidden");
        $("html, body").css("height","100vh");
    });
	//location.href=g5_url+"/mobile/page/view.php?pd_id="+id+"&dWidth="+width+"&dHeight="+height;
}

function fn_viewer2(id){
    modalCloseThis();
    if(id==""){
        alert("잘못된 요청입니다.");
        return false;
    }
    if($("#list_"+id).hasClass("blinds")){
        return false;
    }

    var width = $("#dWidth").val();
    var height = $("#dHeight").val();
    var url = g5_url+"/mobile/page/view.php";
    window.oriScroll = $(document).scrollTop();
    $.ajax({
        url : url,
        method:"POST",
        data:{pd_id:id,dWidth:width,dHeight:height}
    }).done(function(data){
        location.hash = "#view";
        $("#id0s div.con").html('');
        $("#id0s div.con").append(data);
        $("#id0s").css("display","block");
        $("html, body").css("overflow","hidden");
        $("html, body").css("height","100vh");
    });
    //location.href=g5_url+"/mobile/page/view.php?pd_id="+id+"&dWidth="+width+"&dHeight="+height;
}

function fnRecent(){
	location.href=g5_url+'/mobile/page/recent/recent.list.php';
	/*$.ajax({
		url:g5_url+"/mobile/page/ajax/ajax.recent.list.php",
		method:"POST"
	}).done(function(data){
		//새리스트
		$(".grid").remove();
		var item = '<div class="list_item grid are-images-unloaded"></div>';
		$(".post").append(item);
		$(".grid").append(data);
		initpkgd();
		page=1;	
	});*/
}


function fnStatus(pd_id,status){
    $("#up_pd_id").val(pd_id);
    if("<?php echo $view["pd_type"];?>"=="2"){
        $("#status_buy").css("display","none");
    }

    console.log(status);
    switch (status){
        case "0":
            $("#status1").addClass("active");
            $("#status2").removeClass("active");
            $("#status3").removeClass("active");
            $("#status4").removeClass("active");
            break;
        case "1":
            $("#status1").removeClass("active");
            $("#status2").addClass("active");
            $("#status3").removeClass("active");
            $("#status4").removeClass("active");
            break;
        case "2":
            $("#status1").removeClass("active");
            $("#status2").removeClass("active");
            $("#status3").addClass("active");
            $("#status4").removeClass("active");
            break;
        case "3":
            $("#status1").removeClass("active");
            $("#status2").removeClass("active");
            $("#status3").removeClass("active");
            $("#status4").addClass("active");
            break;
    }

    $("#id03").css({"display":"block","z-index":"9999999"});
    $("#id03 .w3-modal-content").css({"height":"62vw","margin-top":"-32vw"});
    $("html, body").css("overflow","hidden");
    $("html, body").css("height","100vh");
    location.hash="#modal";
}

function fnStatusUpdate(){
    var status = $("#id03 ul.modal_sel li.active").text();
    var pd_id = $("#up_pd_id").val();
    $.ajax({
        url:g5_url+"/mobile/page/ajax/ajax.product_status_update.php",
        method:"POST",
        data:{status:status,pd_id:pd_id}
    }).done(function(data){
        console.log(data);
        if(data=="1"){
            alert("상태변경이 완료 되었습니다.");
            modalClose();
        }else{
            alert("상태변경 오류 입니다. 다시 시도해 주세요.");
            modalClose();
        }
    });
}
function fnBlindView(pd_id){

    //window.oriScroll = $(document).scrollTop();
    $.ajax({
        url:g5_url+"/mobile/page/ajax/ajax.blind_view.php",
        method:"POST",
        data:{pd_id:pd_id}
    }).done(function(data){
        if($("#id06").css("display")=="none"){
            $("#id06").css("display","block");
            $("html, body").css("overflow","hidden");
            $("html, body").css("height","100vh");
            location.hash = "#modal";
        }
        $("#id06 .con p").html(data);
    });
}

$(function() {
    $(document).scroll(function(){
        if($(this).scrollTop() <= 0){
            console.log("A");
            var home = "<?php echo G5_IMG_URL;?>/bottom_icon_home.svg";
            $("#home").attr("onclick","location.href='<?php echo G5_URL;?>'");
            $("#home img").attr("src",home);
        }else{
            console.log("B");
            var home = "<?php echo G5_IMG_URL;?>/bottom_icon_05.svg";
            $("#home").attr("onclick","scroll_top()");
            $("#home img").attr("src",home);
        }
    });
    // 폰트 리사이즈 쿠키있으면 실행
    font_resize("container", get_cookie("ck_font_resize_rmv_class"), get_cookie("ck_font_resize_add_class"));

});
function removeDebug(){
	$("#debug").removeClass("active");
    $("#mobile_header #mobile_menu_btn").removeClass("active");
}


</script>

<?php
include_once(G5_PATH."/tail.sub.php");
?>