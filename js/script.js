/*function mobile_menu(id,direction){
	$("#"+id).addClass("active");
	$(".mobile_menu > span").css("background","#000");
	$("body").addClass("menu-active-"+direction);
	$("html").css("overflow","hidden");
}*/
$(function(){
    if(location.href.indexOf("app_mb_id")>-1){
        history.pushState(null, null, g5_url);
    }
	$(".mobile_menu_btn").click(function(){
		$(".mobile_menu").fadeIn(300,function(){
			$(".mobile_menu").addClass("active");
		});
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

	$("body").removeClass("hide");
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

function fnsuggestion(type){
	if($(".mobile_menu").attr("class").indexOf("active")!=-1){
		$(".mobile_menu").fadeOut(300,function(){
			$(".mobile_menu").removeClass("active");
		});
	}
	$.ajax({
        url:g5_url+'/mobile/page/modal/modal.inquiry.php',
        method:"post",
        data:{}
    }).done(function(data){
        //$("#id00").css({"display":"block","z-index":"100000"});
        $(".modal").html(data);
        $(".modal").addClass("active");
        $("html, body").css("overflow","hidden");
        $("html, body").css("height","100vh");
        location.hash="#modal";
        if(!type) {
            $("#id00 #cate_name").attr("readonly", false);
            $("#id00 #cate_name2").css("display", "none");
        }else{
            $("#id00 #cate_name").css("display", "none");
            $("#id00 #cate_name").attr("required", false);
            $("#id00 #cate_name2").css("display", "none");
            $("#id00 #cate_content").attr("placeholder", "자유롭게 제안해 주세요.");
        }
    });

}

function fnsuggestion2(ca_id){
    var cate_name = "";
    $.ajax({
        url:g5_url+'/mobile/page/modal/modal.inquiry.php',
        method:"post",
        data:{}
    }).done(function(data){
        $(".modal").html(data).addClass("active");
        $("html, body").css("overflow","hidden");
        $("html, body").css("height","100vh");
        location.hash="#modal";
        $.ajax({
            url:g5_url+"/mobile/page/ajax/ajax.category_name.php",
            method:"POST",
            data:{ca_id:ca_id}
        }).done(function(data){
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
    });

}

function closeMenu(){
    $(".mobile_menu").fadeOut(300,function(){
        $(".mobile_menu").removeClass("active");
    });
    location.hash='';
}

function modalCloseThis(){
    if(pd_id){ socket.emit('add connect', {pd_id:pd_id,cnt_type:"minus"}) }
    $("html").css({"overflow":"auto","height":"100%"});
    $("body").css({"overflow":"unset","height":"auto"});
    $("#id0s").css("display","none");
    $("#id0s .con").html('');
    var url = location.href;
    url = url.replace("?pd_id="+pd_id+"#view","");
    history.pushState(null, null, url);
    $(document).scrollTop(window.oriScroll);
    location.hash='';
}

function modalCloseTalk(){
    $(".talk").hide();
    $(".view_bottom").show();
    $(".view_detail").show();
    clearInterval(talkID);
    location.hash='#view';
}
function modalClose(){
    $("html").css({"overflow":"auto","height":"auto"});
    $("body").css({"overflow":"unset","height":"auto"});

    $(".no-view").each(function(){
        if($(this).parent().hasClass("modal")) {
            $(this).parent().removeClass("active");
            $(".modal").html('');
        }else {
            $(this).css("display", "none");
        }
    });
    var url = location.href;
    if(url.indexOf("pd_id")>-1){
        location.hash='#view';
    }
    if(url.indexOf("detail")>-1){
        location.hash='#detailview';
    }
}
function modalClose2(){
    $(".no-view").each(function(){
        if($(this).parent().hasClass("modal")) {
            $(this).parent().removeClass("active");
            $(".modal").html('');
        }else {
            $(this).css("display", "none");
        }
    });
    $("body").removeAttr("style");
    location.hash='#detailview';
}

function fnSetting(){
    fnKeywordClose();
    if($(".search_setting").attr("id") == "menuon"){
        //$("#set").val(1);
        $(".search_setting").attr("id","");
        $(".search_setting").css("top","-100vh");
        $("html,body").removeAttr("style");
        location.hash='';
    }else{
        $(".keyword").removeClass("active");
        var type = $("#set_type").val();
        var type2 = $("#set_type2").val();
        if(type == 2 && (type2=="" || type== 8)){
            $(".timesel").css("display", "block");
            $(".meettime").css("display", "block");
        }
        //$("#set").val(2);
        location.hash = "#search";
        $(".search_setting").attr("id","menuon");
        $(".search_setting").css({"top":"16.5vw","z-index":"20"});
        $("html, body").css("overflow","hidden");
        $("html, body").css("height","100vh");
    }
}

var set_search = '';

function fnSetting2(){
    $("#searchActive").val("simple");
    fnKeywordClose();
    if($(".search_setting").attr("id") == "menuon"){
        $("#set").val(1);
        $(".search_setting").attr("id","");
        $(".search_setting").css("top","-100vh");
        $("html,body").removeAttr("style");
		set_search = "Y";
        fnlist(1,'searchtrue');
        location.hash='';
    }else{
        $("#set").val(2);
        location.hash = "#search";
        $(".search_setting").attr("id","menuon");
        $(".search_setting").css({"top":"16.5vw","z-index":"20"});
        $("html, body").css("overflow","hidden");
        $("html, body").css("height","100vh");
    }
    var stx = $("#stx").val();
    if(stx!=""){
        $.ajax({
            url:g5_url+'/mobile/page/ajax/ajax.set_session.php',
            method:"post",
            data:{key:'stx',value:stx}
        });
    }else{
        $.ajax({
            url:g5_url+'/mobile/page/ajax/ajax.set_session.php',
            method:"post",
            data:{key:'stx',value:''}
        });
    }

    var cates = $(".sch_btn").val();
    if(cates!="카테고리선택"){
        cates = cates.split(" > ");
        $.ajax({
            url:g5_url+'/mobile/page/ajax/ajax.set_session.php',
            method:"post",
            data:{key:'cate',value:cates[0]}
        });
        $.ajax({
            url:g5_url+'/mobile/page/ajax/ajax.set_session.php',
            method:"post",
            data:{key:'cate2',value:cates[1]}
        });
    }else{
        $.ajax({
            url:g5_url+'/mobile/page/ajax/ajax.set_session.php',
            method:"post",
            data:{key:'cate',value:''}
        });
        $.ajax({
            url:g5_url+'/mobile/page/ajax/ajax.set_session.php',
            method:"post",
            data:{key:'cate2',value:''}
        });
    }

    if($("#mb_level").prop("checked")==true){
        $.ajax({
            url:g5_url+'/mobile/page/ajax/ajax.set_session.php',
            method:"post",
            data:{key:'mb_level',value:4}
        });
    }else{
        $.ajax({
            url:g5_url+'/mobile/page/ajax/ajax.set_session.php',
            method:"post",
            data:{key:'mb_level',value:''}
        });
    }

    if($("#workcnt").prop("checked") == true){
        $.ajax({
            url:g5_url+'/mobile/page/ajax/ajax.set_session.php',
            method:"post",
            data:{key:'pd_price_type',value:$("#workcnt").val()}
        });
    }
    if($("#worktime").prop("checked") == true){
        $.ajax({
            url:g5_url+'/mobile/page/ajax/ajax.set_session.php',
            method:"post",
            data:{key:'pd_price_type',value:$("#worktime").val()}
        });
    }
    if($("#workday").prop("checked") == true){
        $.ajax({
            url:g5_url+'/mobile/page/ajax/ajax.set_session.php',
            method:"post",
            data:{key:'pd_price_type',value:$("#workday").val()}
        });
    }
    if($("#eight").prop("checked") == true){
        $.ajax({
            url:g5_url+'/mobile/page/ajax/ajax.set_session.php',
            method:"post",
            data:{key:'type2',value:4}
        });
    }else{
        $.ajax({
            url:g5_url+'/mobile/page/ajax/ajax.set_session.php',
            method:"post",
            data:{key:'type2',value:8}
        });
    }

    if($("#pd_timeFrom").val()!=""){
        $.ajax({
            url:g5_url+'/mobile/page/ajax/ajax.set_session.php',
            method:"post",
            data:{key:'pd_timeFrom',value:$("#pd_timeFrom").val()}
        });
    }else{
        $.ajax({
            url:g5_url+'/mobile/page/ajax/ajax.set_session.php',
            method:"post",
            data:{key:'pd_timeFrom',value:''}
        });
    }
    if($("#pd_timeTo").val()!=""){
        $.ajax({
            url:g5_url+'/mobile/page/ajax/ajax.set_session.php',
            method:"post",
            data:{key:'pd_timeTo',value:$("#pd_timeTo").val()}
        });
    }else{
        $.ajax({
            url:g5_url+'/mobile/page/ajax/ajax.set_session.php',
            method:"post",
            data:{key:'pd_timeTo',value:''}
        });
    }

    if($("#pd_timetype").prop("checked")==true){
        $.ajax({
            url:g5_url+'/mobile/page/ajax/ajax.set_session.php',
            method:"post",
            data:{key:'pd_timetype',value:1}
        });
    }else{
        $.ajax({
            url:g5_url+'/mobile/page/ajax/ajax.set_session.php',
            method:"post",
            data:{key:'pd_timetype',value:''}
        });
    }
    if($("#sc_priceFrom").val()!=""){
        $.ajax({
            url:g5_url+'/mobile/page/ajax/ajax.set_session.php',
            method:"post",
            data:{key:'priceFrom',value:$("#sc_priceFrom").val()}
        });
    }else{
        $.ajax({
            url:g5_url+'/mobile/page/ajax/ajax.set_session.php',
            method:"post",
            data:{key:'priceFrom',value:'0'}
        });
    }
    if($("#sc_priceTo").val()!=""){
        $.ajax({
            url:g5_url+'/mobile/page/ajax/ajax.set_session.php',
            method:"post",
            data:{key:'priceTo',value:$("#sc_priceTo").val()}
        });
    }else{
        $.ajax({
            url:g5_url+'/mobile/page/ajax/ajax.set_session.php',
            method:"post",
            data:{key:'priceTo',value:'500000'}
        });
    }
    $.ajax({
        url:g5_url+'/mobile/page/ajax/ajax.set_session.php',
        method:"post",
        data:{key:'order_sort',value:$("#order_sort").val()}
    });
    $.ajax({
        url:g5_url+'/mobile/page/ajax/ajax.set_session.php',
        method:"post",
        data:{key:'order_sort_active',value:$("#order_sort_active").val()}
    });
}


function fnSettingMap2(){
    if($(".search_setting").attr("id") == "menuon"){
        $(".search_setting").attr("id","");
        $(".search_setting").css("top","-100vh");
        $("html,body").removeAttr("style");
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
	location.hash= '#view';
}

function hideDetail(){
    $(".view_top").css("display","block");
    $(".view_detail").css("top","100vh");
    $(".detail_arrow").stop(true).animate({top:'-60vw',opacity:1},500);
    var video = 0;
    $(".youtube-player").each(function(e){
        video++;
    })
    if(video!=0){
        $(".youtube-player").each(function(e){
            $('.youtube-player')[e].contentWindow.postMessage('{"event":"command","func":"' + 'stopVideo' + '","args":""}', '*');
        });
    }
    location.hash= '#view';
}

function cateClose(chk){
    $(".category_menu, .category_menu2, .category_menu3, .category_menu4").fadeOut(300,function(){
        $(".category_menu, .category_menu2, .category_menu3, .category_menu4").removeClass("active");
        //location.hash = '';
    });
    /*if(chk){
        location.hash = "#writes"
    }*/
}

function blindClose(){
	$("#id01s").css("display","none");
	location.hash = '#detailview';
}

function mapViewClose(){
    $("#id01s").css("display","none");
    location.hash = '#detailview';
}

var upText = null;
function fnfilter(content,attr){
    //location.hash = "#"+attr;
    if(upText!=null){
        clearTimeout(upText);
    }
    upText = setTimeout(
        function() {
            $.ajax({
                url: g5_url + "/mobile/page/ajax/ajax.filter.php",
                method: "POST",
                data: {content: content},
                dataType: "json"
            }).done(function (data) {
                if (data.content != "") {
                    if (location.href.indexOf("#") != -1) {
                        $("#debug").css({"background-color": "#fff", "color": "#000"});
                    }
                    $("#debug").addClass("active");
                    $("#debug").html("금칙어가 포함되어 있습니다.");
                    setTimeout(removeDebug, 2000);
                    if (location.href.indexOf("#") != -1) {
                        setTimeout(function () {
                            $("#debug").css({"background-color": "#000", "color": "#fff"});
                        }, 2000);
                    }
                    var retext = content.replace(data.content, "");
                    $("#" + attr).val(retext);

                }
            });

            $(this).keyup(function(e){
               if(e.keyCode == 13 && attr == "locs1"){
                   mapSelect();
               }
            });

        }
    ,500);
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

$(window).on('hashchange', function() {
    var type = location.hash.replace("#","");
    /*if(type=="type1"){
        $("#type1").removeAttr("checked");
        $(".top_header").removeClass("bg2");
        $("#mobile_header").removeClass("bg2");
        $(".user_box").removeClass("bg2");
        $(".wished").removeClass("bg2");
        $(".search_setting").removeClass("bg2");
        $(".copyright").removeClass("bg2");
        $(".ft_menu_04 img").attr("src",g5_url+"/img/bottom_icon_03.svg");

        $(this).html("물건");
        $(this).css({"text-align":"right"});

        $("#id01 .pd_price_type").css("display","none");
        $("#set_type").val(1);
        //$("#set_type2").val(1);
        $("#wr_type1").val(1);

        $(".timesel").css("display","none");
        $(".meettime").css("display","none");

        $(".top_header").css("background-color","#000");
        $("#stx").attr("placeholder","원하는 물건이 있으세요?");
        $(".text").addClass("bg1");
        $(".text").removeClass("bg2");
        $(".text img").attr("src",g5_url+"/img/write_text_1.svg");
        $(".write_btn img").attr("src",g5_url+"/img/ic_write_btn.svg");
        $("#theme-color").attr("content","#000000");
        $("#wr_price").attr("placeholder","판매금액");
        $("#wr_price").css("width","70%");
        $("#wr_price2").css("display","none");
        //카테고리 설정
        $.ajax({
            url:g5_url+"/mobile/page/ajax/ajax.category.php",
            method:"POST",
            data:{type1:"1"}
        }).done(function(data){
            $("#cate option").remove();
            $("#cate").append(data);
            $("#cate2 option").remove();
            $("#cate2").append("<option value=''>전체</option>");
        });

        $.ajax({
            url:g5_url+"/mobile/page/ajax/ajax.set_session.php",
            method:"post",
            data:{key:"type1",value:"1"}
        }).done(function(data){
            //console.log(data);
        });
        fnlist(1,'');
    }else{
        $("#type1").prop("checked");
        $(".top_header").addClass("bg2");
        $("#mobile_header").addClass("bg2");
        $(".wished").addClass("bg2");
        $(".user_box").addClass("bg2");
        $(".search_setting").addClass("bg2");
        $(".copyright").addClass("bg2");
        $(".ft_menu_04 img").attr("src",g5_url+"/img/bottom_icon_03_2.svg");
        $(this).html("능력");
        $(this).css({"text-align":"left"});
        $("#set_type").val(2);
        $("#wr_type1").val(2);
        if($("#set_type2").val() == 8 || $("#set_type2").val() == "") {
            $(".timesel").css("display", "block");
            $(".meettime").css("display", "block");
        }
        $(".top_header").css("background-color","#ff3d00");
        $("#stx").attr("placeholder","누군가의 도움이 필요한가요?");
        $("#id01 .pd_price_type").css("display","block");
        $(".text").addClass("bg2");
        $(".text").removeClass("bg1");
        $(".text img").attr("src",g5_url+"/img/write_text_2.svg");
        $(".write_btn img").attr("src",g5_url+"/img/ic_write_btn_2.svg");
        $("#theme-color").attr("content","#000000");
        $("#wr_price").attr("placeholder","판매금액");
        $("#wr_price").css("width","30%");
        $("#wr_price2").css("display","none");
        location.hash = "#type2";
        //카테고리 설정
        $.ajax({
            url:g5_url+"/mobile/page/ajax/ajax.category.php",
            method:"POST",
            data:{type1:"2"}
        }).done(function(data){
            $("#cate option").remove();
            $("#cate").append(data);
            $("#cate2 option").remove();
            $("#cate2").append("<option value=''>전체</option>");
        });

        $.ajax({
            url:g5_url+"/mobile/page/ajax/ajax.set_session.php",
            method:"post",
            data:{key:"type1",value:"2"}
        }).done(function(data){
            //console.log(data);
        });
        fnlist(1,'');
    }
    priceSet();*/
});


function fnKeywordClose(){
    $('.keyword').removeClass('active');
    $('.keyword li').removeClass('active');
    $('.keyword div').removeClass('active');
}
