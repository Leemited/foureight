/*function mobile_menu(id,direction){
	$("#"+id).addClass("active");
	$(".mobile_menu > span").css("background","#000");
	$("body").addClass("menu-active-"+direction);
	$("html").css("overflow","hidden");
}*/
$(function(){
	$(".mobile_menu_btn").click(function(){
		$(".mobile_menu").fadeIn(300,function(){
			$(".mobile_menu").addClass("active");
		});
		console.log("A");
        $("html, body").css("overflow","hidden");
        $("html, body").css("height","100vh");
	});
	$(".mobile_menu > span").click(function(){
		$(".mobile_menu").fadeOut(300,function(){
			$(".mobile_menu").removeClass("active");
		});
        $("html").css("overflow","auto");
        $("body").css("overflow","unset");
	});
})
function mobile_sub_menu(t){
	var li=$(t).parent();
	var ul=$(t).parent().parent();
	var menu=$(t).parent().parent().parent();
	ul.find("> li").hide();
	ul.find(".back").show();
	menu.find("> div").hide();
	li.show();
	$(t).hide();
	li.find("div").slideDown();
}
function scroll_top(){
	$("html, body").animate({ scrollTop: 0 }, "slow");
}
function mobile_back(){
	$(".mobile_menu > div > div > ul > li div").css("display","none");
	$(".mobile_menu > div > div > ul > li").slideDown();
	$(".back").css("display","none");
	$(".mobile_menu > div > div > div").slideDown();
	$(".mobile_menu > div > div > ul > li > a").css("display","block");

}
/*
$(function(){
	$(".mobile_menu > span, .mobile_menu .close").click(function(){
		$("body").removeClass("menu-active-left");
		$("body").removeClass("menu-active-right");
		$(".mobile_menu").removeClass("active");
		$(".mobile_menu > span").css("background","none");
		//$("html, body").css("overflow-x","hidden");
		$("html").css("overflow-y","scroll");
	});
});
*/
function number_only(t){
	t.value = t.value.replace(/[^0-9]/g, '');
}

var hash_back="";
//$(window).hashchange( function(){
	//console.log( location.hash + " / " +hash_back );
	if(location.hash=="" && hash_back){
		if(hash_back=="#msg"){
			msg_close();
		}
		if(hash_back=="#modal"){
			modal_close();
		}
		if(hash_back=="#small_modal"){
			modal_close();
			small_modal_close();
		}
		if(hash_back=="#menu"){
			$('#mobile_menu').removeClass('active');
			$("html").css("overflow","auto");
		}
	}else if(location.hash=="" && hash_back == ""){
		modal_close();
		//console.log( location.hash + " / " +hash_back );
	}else if(location.hash=="#modal" && hash_back=="#small_modal"){
		small_modal_close();
	}
//});
function modal_active(){
	document.location.hash="#modal";
	hash_back="#modal";
	$(".modal").addClass('active');
	var div_height=$(".modal > div").height();
	var d_height=$(window).height();
	if(div_height>d_height){
		$(".modal > div").css("height","calc(100vh - 40px)");
	}else{
		var div_top=(d_height-div_height)/2;
		$(".modal > div").animate({"top":div_top+"px"},500);
	}
	$("html").css("overflow","hidden");
}
function msg_active(){
	document.location.hash="#msg";
	hash_back="#msg";
	$(".msg").addClass('active');
	var div_height=$(".msg > div").height();
	var d_height=$(window).height();
	if(div_height>d_height){
		$(".msg > div").css("height",d_height+"px");
	}else{
		var div_top = (d_height/2)-(div_height/2);
		$(".msg > div").animate({"top":div_top+"px"},500);
	}
	$("html").css("overflow","hidden");
}
function modal_close(){
	$(".modal").html("");
	$('.modal').removeClass('active');
	if(location.hash=="#modal" && hash_back=="#modal"){
		hash_back="";
		window.history.back();
	}
	$("html").css("overflow","auto");
}
function msg_close(){
	$(".msg").html("");
	$('.msg').removeClass('active');
	if(location.hash=="#msg" && hash_back=="#msg"){
		hash_back="";
		 window.history.back();
	}
	$("html").css("overflow","auto");
}

function all_close(){
	msg_close();
	small_modal_close();
	modal_close();
	if(location.hash=="#msg" || location.hash=="#small_modal" || location.hash=="#modal"){
		hash_back="";
		history.back();
	}
	$("html").css("overflow","auto");
}

var fromHistoryBack = false;
var myHistory;
try {
    myHistory = JSON.parse(sessionStorage.getItem('myHistory'));
} catch (e) {};
if (myHistory) {
    if (myHistory[myHistory.length-1].href == window.location.href 
            && myHistory[myHistory.length-1].referrer == document.referrer) {
        //alert('새로고침 되었습니다.');
    } else {
        if (myHistory.length > 1) {
            if (myHistory[myHistory.length-2].href == window.location.href 
                    && myHistory[myHistory.length-2].referrer == document.referrer) {
                fromHistoryBack = true;
                myHistory.pop();
                sessionStorage.setItem('myHistory', JSON.stringify(myHistory));
            }
        }
        if (myHistory.length > 10 && !fromHistoryBack) {
            myHistory.shift();
            sessionStorage.setItem('myHistory', JSON.stringify(myHistory));
        }
        if (!fromHistoryBack) {
            myHistory.push({ 
                href: window.location.href,
                referrer: document.referrer
            });
            sessionStorage.setItem('myHistory', JSON.stringify(myHistory));
        }
    }
} else {
    var newHistory = [{ 
        href: window.location.href, 
        referrer: document.referrer
    }];
    sessionStorage.setItem('myHistory', JSON.stringify(newHistory));
}

function fnsuggestion(){
	if($(".mobile_menu").attr("class").indexOf("active")!=-1){
		$(".mobile_menu").fadeOut(300,function(){
			$(".mobile_menu").removeClass("active");
		});
	}
    $("#id00 #cate_name").attr("readonly", false);
    $("#id00 #cate_name2").css("display","none");
	$("#id00").css({"display":"block","z-index":"100000"});
    $("html, body").css("overflow","hidden");
    $("html, body").css("height","100vh");
    location.hash="#modal";
}

function fnsuggestion2(ca_id){
    var cate_name = "";
    $.ajax({
        url:g5_url+"/mobile/page/ajax/ajax.category_name.php",
        method:"POST",
        data:{ca_id:ca_id}
    }).done(function(data){
        console.log(data);
        cate_name = data;
        if(cate_name!="") {
            $("#id00 #cate_name").val(cate_name);
            $("#id00 #cate_name").attr("readonly", true);
        }
    });
    if($(".mobile_menu").attr("class").indexOf("active")!=-1){
        $(".mobile_menu").fadeOut(300,function(){
            $(".mobile_menu").removeClass("active");
        });
    }
    $("#id00").css({"display":"block","z-index":"100000"});
    $("html, body").css("overflow","hidden");
    $("html, body").css("height","100vh");
    location.hash="#modal";
}

function closeMenu(){
    $(".mobile_menu").fadeOut(300,function(){
        $(".mobile_menu").removeClass("active");
    });
    location.hash='';
}

function modalCloseThis(){
    $("html").css({"overflow":"auto","height":"100%"});
    $("body").css({"overflow":"unset","height":"auto"});
    $("#id0s").css("display","none");

    location.hash='';
    $(document).scrollTop(window.oriScroll);
}

function modalCloseTalk(){
    $(".talk").hide();
    $(".view_bottom").show();
    $(".view_detail").show();
    clearInterval(talkID);
    location.hash='';
}
function modalClose(){
    $("html").css("overflow","auto");
    $("body").css("overflow","unset");
    $(".no-view").each(function(){
        $(this).css("display","none");
    });
    //$("#id01").css("display","none");
    location.hash='';
    //$(document).scrollTop(window.oriScroll);
}
function modalClose2(){
    $(".no-view").each(function(){
        $(this).css("display","none");
    });
    location.hash='#detailview';
}

function fnSetting(){
    if($(".search_setting").attr("id") == "menuon"){
        //$("#set").val(1);
        $(".search_setting").attr("id","");
        $(".search_setting").css("top","-100vh");
        $("html").css("overflow","auto");
        $("body").css("overflow","unset");
        location.hash='';
    }else{
        var type = $("#set_type").val();
        var type2 = $("#set_type2").val();
        if(type == 2 && (type2=="" || type== 8)){
            $(".timesel").css("display", "block");
            $(".meettime").css("display", "block");
        }
        //$("#set").val(2);
        location.hash = "#search";
        $(".search_setting").attr("id","menuon");
        $(".search_setting").css("top","20vw");
        $("html, body").css("overflow","hidden");
        $("html, body").css("height","100vh");
    }
}

var set_search = '';

function fnSetting2(){
    $("#searchActive").val("simple");
    if($(".search_setting").attr("id") == "menuon"){
        $("#set").val(1);
        $(".search_setting").attr("id","");
        $(".search_setting").css("top","-100vh");
        $("html").css("overflow","auto");
        $("body").css("overflow","unset");
		set_search = "Y";
        fnlist(1,'');
        location.hash='';
    }else{
        $("#set").val(2);
        location.hash = "#search";
        $(".search_setting").attr("id","menuon");
        $(".search_setting").css("top","20vw");
        $("html, body").css("overflow","hidden");
        $("html, body").css("height","100vh");
    }
}


function fnSettingMap2(){
    if($(".search_setting").attr("id") == "menuon"){
        $(".search_setting").attr("id","");
        $(".search_setting").css("top","-100vh");
        $("html").css("overflow","auto");
        $("body").css("overflow","unset");
        location.hash='';
    }else{
        location.hash = "#search";
        $(".search_setting").attr("id","menuon");
        $(".search_setting").css("top","20vw");
        $("html, body").css("overflow","hidden");
        $("html, body").css("height","100vh");
    }
}

function hidePreview(){
	$("#DetailImage").hide();
	location.hash= '';
}

function hideDetail(){
    $(".view_top").css("display","block");
    $(".view_detail").css("top","100vh");
    $(".detail_arrow").stop(true).animate({top:'-60vw',opacity:1},500);
    location.hash= '';
}

function cateClose(){
    $(".category_menu, .category_menu2, .category_menu3, .category_menu4").fadeOut(300,function(){
        $(".category_menu, .category_menu2, .category_menu3, .category_menu4").removeClass("active");
        location.hash = '';
    });
}

function blindClose(){
	$("#id01s").css("display","none");
	location.hash = '';
}

function fnfilter(content,attr){
    $.ajax({
        url:g5_url+"/mobile/page/ajax/ajax.filter.php",
        method:"POST",
        data:{content:content},
        dataType:"json"
    }).done(function(data){
        if(data.content!=""){
            if(location.href.indexOf("#")!=-1){
                $("#debug").css({"background-color":"#fff","color":"#000"});
            }
            $("#debug").addClass("active");
            $("#debug").html("금칙어가 포함되어 있습니다.");
            setTimeout(removeDebug, 1500);
            if(location.href.indexOf("#")!=-1) {
                setTimeout(function () {
                    $("#debug").css({"background-color":"#000","color":"#fff"});
                }, 1500);
            }
            var retext = content.replace(data.content, "");
            $("#"+attr).val(retext);
            if(attr=="wr_content") {
                setCookie("pd_content", retext, 1);
            }else if(attr=="sub_title"){
                setCookie("title", retext, 1);
                setCookie("pd_tag", retext, 1);
            }
        }
    });
}


function setCookie(name, value, expiredays){
    var todayDate = new Date();
    todayDate.setDate( todayDate.getDate() + expiredays );
    document.cookie = name + "=" + escape( value ) + "; path=/; expires=" + todayDate.toGMTString() + ";"
}

function getCookie(cName) {
    cName = cName + '=';
    var cookieData = document.cookie;
    var start = cookieData.indexOf(cName);
    var cValue = '';
    if(start != -1){
        start += cName.length;
        var end = cookieData.indexOf(';', start);
        if(end == -1)end = cookieData.length;
        cValue = cookieData.substring(start, end);
    }
    return unescape(cValue);
}

$.fn.selectRange = function(start, end) {
    return this.each(function() {
        if(this.setSelectionRange) {
            this.focus();
            this.setSelectionRange(start, end);
        } else if(this.createTextRange) {
            var range = this.createTextRange();
            range.collapse(true);
            range.moveEnd('character', end);
            range.moveStart('character', start);
            range.select();
        }
    });
};


