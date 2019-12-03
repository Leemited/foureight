// 전역 변수
var errmsg = "";
var errfld = null;

// 필드 검사
function check_field(fld, msg)
{
    if ((fld.value = trim(fld.value)) == "")
        error_field(fld, msg);
    else
        clear_field(fld);
    return;
}

// 필드 오류 표시
function error_field(fld, msg)
{
    if (msg != "")
        errmsg += msg + "\n";
    if (!errfld) errfld = fld;
    fld.style.background = "#BDDEF7";
}

// 필드를 깨끗하게
function clear_field(fld)
{
    fld.style.background = "#FFFFFF";
}

function trim(s)
{
    var t = "";
    var from_pos = to_pos = 0;

    for (i=0; i<s.length; i++)
    {
        if (s.charAt(i) == ' ')
            continue;
        else
        {
            from_pos = i;
            break;
        }
    }

    for (i=s.length; i>=0; i--)
    {
        if (s.charAt(i-1) == ' ')
            continue;
        else
        {
            to_pos = i;
            break;
        }
    }

    t = s.substring(from_pos, to_pos);
    //				alert(from_pos + ',' + to_pos + ',' + t+'.');
    return t;
}

// 자바스크립트로 PHP의 number_format 흉내를 냄
// 숫자에 , 를 출력
function number_format(data)
{

    var tmp = '';
    var number = '';
    var cutlen = 3;
    var comma = ',';
    var i;
    
    data = data + '';

    var sign = data.match(/^[\+\-]/);
    if(sign) {
        data = data.replace(/^[\+\-]/, "");
    }

    len = data.length;
    mod = (len % cutlen);
    k = cutlen - mod;
    for (i=0; i<data.length; i++)
    {
        number = number + data.charAt(i);

        if (i < data.length - 1)
        {
            k++;
            if ((k % cutlen) == 0)
            {
                number = number + comma;
                k = 0;
            }
        }
    }

    if(sign != null)
        number = sign+number;

    return number;
}

// 새 창
function popup_window(url, winname, opt)
{
    window.open(url, winname, opt);
}


// 폼메일 창
function popup_formmail(url)
{
    opt = 'scrollbars=yes,width=417,height=385,top=10,left=20';
    popup_window(url, "wformmail", opt);
}

// , 를 없앤다.
function no_comma(data)
{
    var tmp = '';
    var comma = ',';
    var i;

    for (i=0; i<data.length; i++)
    {
        if (data.charAt(i) != comma)
            tmp += data.charAt(i);
    }
    return tmp;
}

// 삭제 검사 확인
function del(href)
{
    if(confirm("한번 삭제한 자료는 복구할 방법이 없습니다.\n\n정말 삭제하시겠습니까?")) {
        var iev = -1;
        if (navigator.appName == 'Microsoft Internet Explorer') {
            var ua = navigator.userAgent;
            var re = new RegExp("MSIE ([0-9]{1,}[\.0-9]{0,})");
            if (re.exec(ua) != null)
                iev = parseFloat(RegExp.$1);
        }

        // IE6 이하에서 한글깨짐 방지
        if (iev != -1 && iev < 7) {
            document.location.href = encodeURI(href);
        } else {
            document.location.href = href;
        }
    }
}

// 쿠키 입력
function set_cookie(name, value, expirehours, domain)
{
    var today = new Date();
    today.setTime(today.getTime() + (60*60*1000*expirehours));
    document.cookie = name + "=" + escape( value ) + "; path=/; expires=" + today.toGMTString() + ";";
    if (domain) {
        document.cookie += "domain=" + domain + ";";
    }
}

// 쿠키 얻음
function get_cookie(name)
{
    var find_sw = false;
    var start, end;
    var i = 0;

    for (i=0; i<= document.cookie.length; i++)
    {
        start = i;
        end = start + name.length;

        if(document.cookie.substring(start, end) == name)
        {
            find_sw = true
            break
        }
    }

    if (find_sw == true)
    {
        start = end + 1;
        end = document.cookie.indexOf(";", start);

        if(end < start)
            end = document.cookie.length;

        return unescape(document.cookie.substring(start, end));
    }
    return "";
}

// 쿠키 지움
function delete_cookie(name)
{
    var today = new Date();

    today.setTime(today.getTime() - 1);
    var value = get_cookie(name);
    if(value != "")
        document.cookie = name + "=" + value + "; path=/; expires=" + today.toGMTString();
}

var last_id = null;
function menu(id)
{
    if (id != last_id)
    {
        if (last_id != null)
            document.getElementById(last_id).style.display = "none";
        document.getElementById(id).style.display = "block";
        last_id = id;
    }
    else
    {
        document.getElementById(id).style.display = "none";
        last_id = null;
    }
}

function textarea_decrease(id, row)
{
    if (document.getElementById(id).rows - row > 0)
        document.getElementById(id).rows -= row;
}

function textarea_original(id, row)
{
    document.getElementById(id).rows = row;
}

function textarea_increase(id, row)
{
    document.getElementById(id).rows += row;
}

// 글숫자 검사
function check_byte(content, target)
{
    var i = 0;
    var cnt = 0;
    var ch = '';
    var cont = document.getElementById(content).value;

    for (i=0; i<cont.length; i++) {
        ch = cont.charAt(i);
        if (escape(ch).length > 4) {
            cnt += 2;
        } else {
            cnt += 1;
        }
    }
    // 숫자를 출력
    document.getElementById(target).innerHTML = cnt;

    return cnt;
}

// 브라우저에서 오브젝트의 왼쪽 좌표
function get_left_pos(obj)
{
    var parentObj = null;
    var clientObj = obj;
    //var left = obj.offsetLeft + document.body.clientLeft;
    var left = obj.offsetLeft;

    while((parentObj=clientObj.offsetParent) != null)
    {
        left = left + parentObj.offsetLeft;
        clientObj = parentObj;
    }

    return left;
}

// 브라우저에서 오브젝트의 상단 좌표
function get_top_pos(obj)
{
    var parentObj = null;
    var clientObj = obj;
    //var top = obj.offsetTop + document.body.clientTop;
    var top = obj.offsetTop;

    while((parentObj=clientObj.offsetParent) != null)
    {
        top = top + parentObj.offsetTop;
        clientObj = parentObj;
    }

    return top;
}

function flash_movie(src, ids, width, height, wmode)
{
    var wh = "";
    if (parseInt(width) && parseInt(height))
        wh = " width='"+width+"' height='"+height+"' ";
    return "<object classid='clsid:d27cdb6e-ae6d-11cf-96b8-444553540000' codebase='http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0' "+wh+" id="+ids+"><param name=wmode value="+wmode+"><param name=movie value="+src+"><param name=quality value=high><embed src="+src+" quality=high wmode="+wmode+" type='application/x-shockwave-flash' pluginspage='http://www.macromedia.com/shockwave/download/index.cgi?p1_prod_version=shockwaveflash' "+wh+"></embed></object>";
}

function obj_movie(src, ids, width, height, autostart)
{
    var wh = "";
    if (parseInt(width) && parseInt(height))
        wh = " width='"+width+"' height='"+height+"' ";
    if (!autostart) autostart = false;
    return "<embed src='"+src+"' "+wh+" autostart='"+autostart+"'></embed>";
}

function doc_write(cont)
{
    document.write(cont);
}

var win_password_lost = function(href) {
    window.open(href, "win_password_lost", "left=50, top=50, width=617, height=330, scrollbars=1");
}

$(document).ready(function(){
    $("#login_password_lost, #ol_password_lost").click(function(){
        win_password_lost(this.href);
        return false;
    });
});

/**
 * 포인트 창
 **/
var win_point = function(href) {
    var new_win = window.open(href, 'win_point', 'left=100,top=100,width=600, height=600, scrollbars=1');
    new_win.focus();
}

/**
 * 쪽지 창
 **/
var win_memo = function(href) {
    var new_win = window.open(href, 'win_memo', 'left=100,top=100,width=620,height=500,scrollbars=1');
    new_win.focus();
}

/**
 * 메일 창
 **/
var win_email = function(href) {
    var new_win = window.open(href, 'win_email', 'left=100,top=100,width=600,height=580,scrollbars=0');
    new_win.focus();
}

/**
 * 자기소개 창
 **/
var win_profile = function(href) {
    var new_win = window.open(href, 'win_profile', 'left=100,top=100,width=620,height=510,scrollbars=1');
    new_win.focus();
}

/**
 * 스크랩 창
 **/
var win_scrap = function(href) {
    var new_win = window.open(href, 'win_scrap', 'left=100,top=100,width=600,height=600,scrollbars=1');
    new_win.focus();
}

/**
 * 홈페이지 창
 **/
var win_homepage = function(href) {
    var new_win = window.open(href, 'win_homepage', '');
    new_win.focus();
}

/**
 * 우편번호 창
 **/
var win_zip = function(frm_name, frm_zip, frm_addr1, frm_addr2, frm_addr3, frm_jibeon) {
    if(typeof daum === 'undefined'){
        alert("다음 우편번호 postcode.v2.js 파일이 로드되지 않았습니다.");
        return false;
    }

    var zip_case = 1;   //0이면 레이어, 1이면 페이지에 끼워 넣기, 2이면 새창

    var complete_fn = function(data){
        // 팝업에서 검색결과 항목을 클릭했을때 실행할 코드를 작성하는 부분.

        // 각 주소의 노출 규칙에 따라 주소를 조합한다.
        // 내려오는 변수가 값이 없는 경우엔 공백('')값을 가지므로, 이를 참고하여 분기 한다.
        var fullAddr = ''; // 최종 주소 변수
        var extraAddr = ''; // 조합형 주소 변수

        // 사용자가 선택한 주소 타입에 따라 해당 주소 값을 가져온다.
        if (data.userSelectedType === 'R') { // 사용자가 도로명 주소를 선택했을 경우
            fullAddr = data.roadAddress;

        } else { // 사용자가 지번 주소를 선택했을 경우(J)
            fullAddr = data.jibunAddress;
        }

        // 사용자가 선택한 주소가 도로명 타입일때 조합한다.
        if(data.userSelectedType === 'R'){
            //법정동명이 있을 경우 추가한다.
            if(data.bname !== ''){
                extraAddr += data.bname;
            }
            // 건물명이 있을 경우 추가한다.
            if(data.buildingName !== ''){
                extraAddr += (extraAddr !== '' ? ', ' + data.buildingName : data.buildingName);
            }
            // 조합형주소의 유무에 따라 양쪽에 괄호를 추가하여 최종 주소를 만든다.
            extraAddr = (extraAddr !== '' ? ' ('+ extraAddr +')' : '');
        }

        // 우편번호와 주소 정보를 해당 필드에 넣고, 커서를 상세주소 필드로 이동한다.
        var of = document[frm_name];

        of[frm_zip].value = data.zonecode;

        of[frm_addr1].value = fullAddr;
        of[frm_addr3].value = extraAddr;

        if(of[frm_jibeon] !== undefined){
            of[frm_jibeon].value = data.userSelectedType;
        }

        of[frm_addr2].focus();
    };

    switch(zip_case) {
        case 1 :    //iframe을 이용하여 페이지에 끼워 넣기
            var daum_pape_id = 'daum_juso_page'+frm_zip,
                element_wrap = document.getElementById(daum_pape_id),
                currentScroll = Math.max(document.body.scrollTop, document.documentElement.scrollTop);
            if (element_wrap == null) {
                element_wrap = document.createElement("div");
                element_wrap.setAttribute("id", daum_pape_id);
                element_wrap.style.cssText = 'display:none;border:1px solid;left:0;width:100%;height:300px;margin:5px 0;position:relative;-webkit-overflow-scrolling:touch;';
                element_wrap.innerHTML = '<img src="//i1.daumcdn.net/localimg/localimages/07/postcode/320/close.png" id="btnFoldWrap" style="cursor:pointer;position:absolute;right:0px;top:-21px;z-index:1" class="close_daum_juso" alt="접기 버튼">';
                jQuery('form[name="'+frm_name+'"]').find('input[name="'+frm_addr1+'"]').before(element_wrap);
                jQuery("#"+daum_pape_id).off("click", ".close_daum_juso").on("click", ".close_daum_juso", function(e){
                    e.preventDefault();
                    jQuery(this).parent().hide();
                });
            }

            new daum.Postcode({
                oncomplete: function(data) {
                    complete_fn(data);
                    // iframe을 넣은 element를 안보이게 한다.
                    element_wrap.style.display = 'none';
                    // 우편번호 찾기 화면이 보이기 이전으로 scroll 위치를 되돌린다.
                    document.body.scrollTop = currentScroll;
                },
                // 우편번호 찾기 화면 크기가 조정되었을때 실행할 코드를 작성하는 부분.
                // iframe을 넣은 element의 높이값을 조정한다.
                onresize : function(size) {
                    element_wrap.style.height = size.height + "px";
                },
                maxSuggestItems : g5_is_mobile ? 6 : 10,
                width : '100%',
                height : '100%'
            }).embed(element_wrap);

            // iframe을 넣은 element를 보이게 한다.
            element_wrap.style.display = 'block';
            break;
        case 2 :    //새창으로 띄우기
            new daum.Postcode({
                oncomplete: function(data) {
                    complete_fn(data);
                }
            }).open();
            break;
        default :   //iframe을 이용하여 레이어 띄우기
            var rayer_id = 'daum_juso_rayer'+frm_zip,
                element_layer = document.getElementById(rayer_id);
            if (element_layer == null) {
                element_layer = document.createElement("div");
                element_layer.setAttribute("id", rayer_id);
                element_layer.style.cssText = 'display:none;border:5px solid;position:fixed;width:300px;height:460px;left:50%;margin-left:-155px;top:50%;margin-top:-235px;overflow:hidden;-webkit-overflow-scrolling:touch;z-index:10000';
                element_layer.innerHTML = '<img src="//i1.daumcdn.net/localimg/localimages/07/postcode/320/close.png" id="btnCloseLayer" style="cursor:pointer;position:absolute;right:-3px;top:-3px;z-index:1" class="close_daum_juso" alt="닫기 버튼">';
                document.body.appendChild(element_layer);
                jQuery("#"+rayer_id).off("click", ".close_daum_juso").on("click", ".close_daum_juso", function(e){
                    e.preventDefault();
                    jQuery(this).parent().hide();
                });
            }

            new daum.Postcode({
                oncomplete: function(data) {
                    complete_fn(data);
                    // iframe을 넣은 element를 안보이게 한다.
                    element_layer.style.display = 'none';
                },
                maxSuggestItems : g5_is_mobile ? 6 : 10,
                width : '100%',
                height : '100%'
            }).embed(element_layer);

            // iframe을 넣은 element를 보이게 한다.
            element_layer.style.display = 'block';
    }
}

/**
 * 새로운 비밀번호 분실 창 : 101123
 **/
win_password_lost = function(href)
{
    var new_win = window.open(href, 'win_password_lost', 'width=617, height=330, scrollbars=1');
    new_win.focus();
}

/**
 * 설문조사 결과
 **/
var win_poll = function(href) {
    var new_win = window.open(href, 'win_poll', 'width=616, height=500, scrollbars=1');
    new_win.focus();
}

/**
 * 스크린리더 미사용자를 위한 스크립트 - 지운아빠 2013-04-22
 * alt 값만 갖는 그래픽 링크에 마우스오버 시 title 값 부여, 마우스아웃 시 title 값 제거
 **/
$(function() {
    $('a img').mouseover(function() {
        $a_img_title = $(this).attr('alt');
        $(this).attr('title', $a_img_title);
    }).mouseout(function() {
        $(this).attr('title', '');
    });
});

/**
 * 텍스트 리사이즈
**/
function font_resize(id, rmv_class, add_class)
{
    var $el = $("#"+id);

    $el.removeClass(rmv_class).addClass(add_class);

    set_cookie("ck_font_resize_rmv_class", rmv_class, 1, g5_cookie_domain);
    set_cookie("ck_font_resize_add_class", add_class, 1, g5_cookie_domain);
}

/**
 * 댓글 수정 토큰
**/
function set_comment_token(f)
{
    if(typeof f.token === "undefined")
        $(f).prepend('<input type="hidden" name="token" value="">');

    $.ajax({
        url: g5_bbs_url+"/ajax.comment_token.php",
        type: "GET",
        dataType: "json",
        async: false,
        cache: false,
        success: function(data, textStatus) {
            f.token.value = data.token;
        }
    });
}

$(function(){
    $(".win_point").click(function() {
        win_point(this.href);
        return false;
    });

    $(".win_memo").click(function() {
        win_memo(this.href);
        return false;
    });

    $(".win_email").click(function() {
        win_email(this.href);
        return false;
    });

    $(".win_scrap").click(function() {
        win_scrap(this.href);
        return false;
    });

    $(".win_profile").click(function() {
        win_profile(this.href);
        return false;
    });

    $(".win_homepage").click(function() {
        win_homepage(this.href);
        return false;
    });

    $(".win_password_lost").click(function() {
        win_password_lost(this.href);
        return false;
    });

    /*
    $(".win_poll").click(function() {
        win_poll(this.href);
        return false;
    });
    */

    // 사이드뷰
    var sv_hide = false;
    $(".sv_member, .sv_guest").click(function() {
        $(".sv").removeClass("sv_on");
        $(this).closest(".sv_wrap").find(".sv").addClass("sv_on");
    });

    $(".sv, .sv_wrap").hover(
        function() {
            sv_hide = false;
        },
        function() {
            sv_hide = true;
        }
    );

    $(".sv_member, .sv_guest").focusin(function() {
        sv_hide = false;
        $(".sv").removeClass("sv_on");
        $(this).closest(".sv_wrap").find(".sv").addClass("sv_on");
    });

    $(".sv a").focusin(function() {
        sv_hide = false;
    });

    $(".sv a").focusout(function() {
        sv_hide = true;
    });

    // 셀렉트 ul
    var sel_hide = false;
    $('.sel_btn').click(function() {
        $('.sel_ul').removeClass('sel_on');
        $(this).siblings('.sel_ul').addClass('sel_on');
    });

    $(".sel_wrap").hover(
        function() {
            sel_hide = false;
        },
        function() {
            sel_hide = true;
        }
    );

    $('.sel_a').focusin(function() {
        sel_hide = false;
    });

    $('.sel_a').focusout(function() {
        sel_hide = true;
    });

    $(document).click(function() {
        if(sv_hide) { // 사이드뷰 해제
            $(".sv").removeClass("sv_on");
        }
        if (sel_hide) { // 셀렉트 ul 해제
            $('.sel_ul').removeClass('sel_on');
        }
    });

    $(document).focusin(function() {
        if(sv_hide) { // 사이드뷰 해제
            $(".sv").removeClass("sv_on");
        }
        if (sel_hide) { // 셀렉트 ul 해제
            $('.sel_ul').removeClass('sel_on');
        }
    });

    $(document).on( "keyup change", "textarea#wr_content[maxlength]", function(){
        var str = $(this).val();
        var mx = parseInt($(this).attr("maxlength"));
        if (str.length > mx) {
            $(this).val(str.substr(0, mx));
            return false;
        }
    });
});

function get_write_token(bo_table)
{
    var token = "";

    $.ajax({
        type: "POST",
        url: g5_bbs_url+"/write_token.php",
        data: { bo_table: bo_table },
        cache: false,
        async: false,
        dataType: "json",
        success: function(data) {
            if(data.error) {
                alert(data.error);
                if(data.url)
                    document.location.href = data.url;

                return false;
            }

            token = data.token;
        }
    });

    return token;
}

$(function() {
    $(document).on("click", "form[name=fwrite] input:submit, form[name=fwrite] button:submit, form[name=fwrite] input:image", function() {
        var f = this.form;

        if (typeof(f.bo_table) == "undefined") {
            return;
        }

        var bo_table = f.bo_table.value;
        var token = get_write_token(bo_table);

        if(!token) {
            alert("토큰 정보가 올바르지 않습니다.");
            return false;
        }

        var $f = $(f);

        if(typeof f.token === "undefined")
            $f.prepend('<input type="hidden" name="token" value="">');

        $f.find("input[name=token]").val(token);

        return true;
    });
});

function removeDebug(){
    $("#debug").removeClass("active");
    $(".trash-ani").removeClass("active");
    $(".trash-ani2").removeClass("active");
    $(".trash-icon").removeClass("active");
    $("#mobile_header #mobile_menu_btn").removeClass("active");
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
        type:"POST",
        method:"POST",
        data:{pd_id:id,dWidth:width,dHeight:height},
        async:false
    }).done(function(data){
        var reurl = location.href;
        if(reurl.indexOf("pd_id")==-1) {
            if (reurl.indexOf("?") > -1) {
                reurl = reurl + "&pd_id=" + id
            } else {
                reurl = reurl + "?pd_id=" + id
            }
        }
        history.pushState(null, null, reurl);
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
        var reurl = location.href;
        if(reurl.indexOf("?")>-1){
            reurl = reurl+"&pd_id="+id
        }else{
            reurl = reurl+"?pd_id="+id
        }
        history.pushState(null, null, reurl);
        location.hash = "#view";
        $("#id0s div.con").html('');
        $("#id0s div.con").append(data);
        $("#id0s").css("display","block");
        $("html, body").css("overflow","hidden");
        $("html, body").css("height","100vh");
    });
    //location.href=g5_url+"/mobile/page/view.php?pd_id="+id+"&dWidth="+width+"&dHeight="+height;
}


function fnStatus(pd_id,status,pd_type){
    $("#up_pd_id").val(pd_id);
    if(pd_type=="2"){
        $("#status_buy").css("display","none");
    }
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
    $.ajax({
        url:g5_url+'/mobile/page/modal/modal.productstatus.php',
        method:"post",
        data:{pd_id:pd_id}
    }).done(function(data){
        console.log(data);
        $(".modal").html(data).addClass("active").css("z-index","999999");
        //$("#id03").css({"display":"block","z-index":"9999999"});
        //$("#id03 .w3-modal-content").css({"height":"62vw","margin-top":"-32vw"});
        $("html, body").css("overflow","hidden");
        $("html, body").css("height","100vh");
        location.hash="#modal";
    });
}

function fnStatusUpdate(pd_id){
    var status = $("ul.status_ul li.active").text();
    //var pd_id = $("#up_pd_id").val();
    $.ajax({
        url:g5_url+"/mobile/page/ajax/ajax.product_status_update.php",
        method:"POST",
        data:{status:status,pd_id:pd_id}
    }).done(function(data){
        if(data=="3"){
            alert("해당 물품은 거래중인 상품으로 상태 변경할 수 없습니다.");
            modalClose();
        }else if(data=="4"){
            alert("해당 물품은 판매완료된 상품으로 판매완료 상태이외는 변경할 수 없습니다.");
            modalClose();
        }else if(data=="1"){
            alert("상태변경이 완료 되었습니다.");
            modalClose();
        }else{
            alert("상태변경 오류 입니다. 다시 시도해 주세요.");
            modalClose();
        }
    });
}

function fnCardSel(cardinfo){
    var cardinfo = cardinfo.split("|");
    var expd = cardinfo[1];
    var cardnum = cardinfo[2].split("-");
    $("#od_card_num").val(cardnum);
    $("#od_expd").val(expd);
    $("#card_name").val(cardinfo[0]);
    $("#card_year").val(expd.substr(0,2));
    $("#card_month").val(expd.substr(2,4));
    $("#card_month").val();
}

function fnBlindView(pd_id){
    //window.oriScroll = $(document).scrollTop();

    $.ajax({
        url:g5_url+"/mobile/page/modal/modal.mypageblindView.php",
        method:"POST",
        data:{pd_id:pd_id}
    }).done(function(data){
        $(".modal").html(data).addClass("active");
        //if($("#id06").css("display")=="none"){
            /*$("#blind_view_btn").attr("onclick","location.href='"+g5_url+"/mobile/page/mypage/blind_view.php?pd_id="+pd_id+"'");
            $("#admin_qa").attr("onclick","fnAdminWrite('"+pd_id+"')");
            $("#id06").css("display","block");*/
            $("html, body").css("overflow","hidden");
            $("html, body").css("height","100vh");
            location.hash = "#modal";
        //}
        //$("#id06 .con p").html(data);
    });
}


function fnOrderConfirm(od_id,pd_type) {
    var msg = "구매 승인시 해당 물품은 거래중으로 변경되며,\n동일 제품의 구매요청은 삭제 됩니다.";
    if(pd_type==2){
        msg = "구매 승인시 계약금이 있는경우 계약금 선 결제 진행됩니다.";
    }
    if(confirm(msg)) {
        $.ajax({
            url: g5_url + "/mobile/page/ajax/ajax.order_reser_confirm.php",
            mothod: "POST",
            dataType: "json",
            data: {od_id: od_id}
        }).done(function (data) {
            if (data.msg == "1") {
                alert("해당 구매요청을 찾을 수 없습니다.");
            }
            if (data.msg == "3") {
                alert("처리 오류입니다.\r다시 시도해 주세요.");
            }
            if (data.msg == "2") {
                //$("#item_" + cid + " .controls").remove();
                $(".pd_id_"+od_id+" > div input").not($(".pd_id_"+od_id+" > div input:first-child")).remove();
                $(".pd_id_"+od_id+" .btn_controls").append("<input type='button' value='판매취소' class='confirm' onclick='fnOrderCancel("+od_id+",1)'>");
                if(pd_type==1) {//물건
                    if(data.od_id) {
                        for (var i = 0; i < data.od_id.length; i++) {
                            $("#item_" + data.od_id[i]).remove();
                        }
                    }
                    $("#item_" + od_id).prepend('<div class="ordering"><span>결제대기중</span></div>');
                    //$(".orders_pd_id_" + pd_id).remove();
                }else{//능력
                    $("#item_" + od_id).prepend('<div class="ordering"><span>결제대기중</span></div>');
                }
            }
        });
    }
}

function fnShow2(mb_id,pd_id,roomid,type){
    // 연락자 정보 가져오기
    $.ajax({
        url:g5_url+'/mobile/page/modal/modal.contact.php',
        method:"post",
        data:{},
        async:false
    }).done(function(data){
        $(".modal").html(data).addClass("active");
        $.ajax({
            url:g5_url+"/mobile/page/ajax/ajax.get_member2.php",
            method:"post",
            data:{mb_id:mb_id,pd_id:pd_id,roomid:roomid,type:type},
            dataType:"json"
        }).done(function(data){
            $("#id08 .contacts ul").html('');
            $("#id08 .contacts ul").append(data.obj);
            //$("#mb_"+id).toggleClass("active");
            $("#id08").css({"display":"block","z-index":"9002"});
            $("#id08").css("display","block");
            $("body").css({"height":"100vh","overflow":"hidden"})
            location.hash = "#modal";
        });
    });
    // 연락자 정보 가져오기
    /*$.ajax({
        url:g5_url+"/mobile/page/ajax/ajax.get_member2.php",
        method:"post",
        data:{mb_id:mb_id,pd_id:pd_id,roomid:roomid,type:type},
        dataType:"json"
    }).done(function(data){
        $("#id08 .contacts ul").html('');
        $("#id08 .contacts ul").append(data.obj);
        //$("#mb_"+id).toggleClass("active");
        $("#id08").css({"display":"block","z-index":"9002"});
        $("#id08").css("display","block");
        location.hash = "#modal";
    });*/

}
function fnOrderCancel(od_id,c_type){
    var msg = "해당 물건의 판매를 취소 하시겠습니까?";
    if(c_type==2){
        msg = "해당 물건의 구매를 취소 하시겠습니까?";
    }
    if(confirm(msg)){
        $.ajax({
            url:g5_url+"/mobile/page/ajax/ajax.order_reser_cancel.php",
            method:"POST",
            data:{c_type:c_type,od_id:od_id}
        }).done(function(data){
            if(data=="1"){
                alert("해당 구매요청을 찾을 수 없습니다.");
            }
            if(data == "3"){
                alert("처리 오류입니다.\r다시 시도해 주세요.");
            }
            if(data == "2"){
                $("#item_"+od_id).remove();
                if(c_type == 1 ){
                    var count = $("#mul label").text();
                    count = count.replace(",","");
                    count = Number(count) - 1;
                    count = count.numberFormat();
                    $("#mul label").html(count);
                    var topcount = $(".order_tab span").text();
                    topcount = topcount.replace(",","");
                    topcount = Number(topcount) -1;
                    topcount = topcount.numberFormat();
                    $(".order_tab span").html(topcount);
                }else{
                    var count = $("#avil label").text();
                    count = count.replace(",","");
                    count = Number(count) - 1;
                    count = count.numberFormat();
                    $("#avil label").html(count);
                    var topcount = $(".order_tab span").text();
                    topcount = topcount.replace(",","");
                    topcount = Number(topcount) -1;
                    topcount = topcount.numberFormat();
                    $(".order_tab span").html(topcount);
                }
            }
        });
    }else{
        return false;
    }
}

function fnOrderCancel2(cid,pd_id,od_id){
    if(od_id=="") {
        if (confirm("해당 상품의 구매를 취소하시겠습니까?")) {
            $.ajax({
                url: g5_url + '/mobile/page/ajax/ajax.order_reser_cancel2.php',
                mtehod: "post",
                data: {cid: cid, pd_id: pd_id}
            }).done(function (data) {
                if (data == 1) {
                    alert("구매정보가 없습니다.");
                } else if (data == 2) {
                    alert("제품정보가 없습니다.");
                } else {
                    alert("취소 되었습니다.");
                    $("#item_" + cid).remove();
                }
            });
        }
    }else{
        if(confirm("해당 계약을 취소하시겠습니까?\r\n해당 환불 금액은 카드사의 사정에따라 3~4일정도 소요 됩니다.")){
            $.ajax({
                url:g5_url+'/mobile/page/mypage/stdpay/cancel.php',
                method:"post",
                data:{pd_id:pd_id,od_id:od_id}
            }).done(function(data){
                console.log(data);
            });
        }
    }
}

function fnRank(pd_id,pd_type,od_id){
    $("#like_id").val(pd_id);
    $("#view_pd_type").val(pd_type);
    $("#od_id").val(od_id);
    $("html").css({"overflow":"hidden","height":"100%"});
    $("body").css({"overflow":"hidden","height":"100%"});
    location.hash = '#modal';
    $("#id02").show();
}

//무조건 마지막 평가시에
function fnOrderfin(od_id,pd_id,fin_type){
    $.ajax({
        url:g5_url+'/mobile/page/modal/modal.likes2.php',
        method:"post",
        data:{od_id:od_id,pd_id:pd_id,fin_type:fin_type}
    }).done(function(data){
        $(".modal").html(data).addClass("active");
        $("html").css({"overflow":"hidden","height":"100%"});
        $("body").css({"overflow":"hidden","height":"100%"});
        location.hash = '#modal';
    });
    //거래평가 모달 띄우기
    /*$("#fin_od_id").val(od_id);
    $("#fin_cid").val(cid);
    $("#fin_pd_id").val(pd_id);
    $("#id05").show();*/
}
function fnOrderRefund(od_id,pay_oid){
    //환불하기 요청
    if(confirm('해당 상품의 결제취소요청을 하시겠습니까?')){
        location.href=g5_url+'/mobile/page/mypage/order_cancel.php?od_id='+od_id+'&pay_oid='+pay_oid+"&type=0";
    }
}
function fnOrderRefundConfirm(od_id,pay_oid,type){
    //환불하기 승인
    var msg = '해당 상품의 결제를 취소하시겠습니까?';
    if(type==1){
        msg = "해당 상품의 환불을 완료 하시겠습니까?";
    }
    if(confirm(msg)){
        location.href=g5_url+'/mobile/page/mypage/stdpay/cancel.php?od_id='+od_id+'&pay_oid='+pay_oid;
    }
}

function fnOrderRefundCancel(od_id,pay_oid){
    //환불하기 거절
    if(confirm('해당 상품의 결제취소요청을 거절하시겠습니까?')){
        location.href=g5_url+'/mobile/page/mypage/order_cancel.php?od_id='+od_id+'&pay_oid='+pay_oid+"&type=1";
    }
}

function fnOrderReturn(pd_id,od_id,pd_type){
    //환불하기
    if(confirm('해당 상품을 환불하시겠습니까?')){
        $.ajax({
            url:g5_url+'/mobile/page/ajax/ajax.order_return.php',
            method:"post",
            data:{od_id:od_id,pd_id:pd_id}
        }).done(function(data){
            if(data==1){
                alert("이미 요청한 건입니다.");
            }else if(data==3){
                alert("주문상태 업데이트 오류입니다.");
            }else{
                alert("요청되었습니다.");
                location.href=g5_url+'/mobile/page/mypage/mypage_order.php?od_cate=2&pd_type='+pd_type;
            }
        });
        //todo:환불요청만 배송은 환불처리완료후 (우선 판매자의 동의 후)
        /*$("#return_pd_id").val(pd_id);
        $("#return_od_id").val(od_id);
        $("html").css({"overflow":"hidden","height":"100%"});
        $("body").css({"overflow":"hidden","height":"100%"});
        location.hash = '#modal';
        $("#id11").show();*/
    }
}

function fnOrderReturnCancel(type,od_id,pd_id){
    var msg = '해당 환불요청을 거절 하시겠습니까?';
    if(type==1)
        msg = "환불 요청을 취소하시겠습니까?";

    if(confirm(msg)){
        location.href=g5_url+'/mobile/page/mypage/mypage_return_cancel.php?od_id='+od_id+'&type='+type+'&pd_id='+pd_id;
    }
}

function fnOrderReturnConfirm(od_id,pd_id,mb_id){
    $.ajax({
        url:g5_url+'/mobile/page/ajax/ajax.order_return_confirm.php',
        method:"post",
        data:{od_id:od_id,pd_id:pd_id,mb_id:mb_id}
    }).done(function(data){
        if(data==1){
            alert("승인이 완료 되었습니다. 환불배송지는 1:1대화 또는 기타 연락처를 통해 전달 바랍니다.");
            location.reload();
        }else{
            alert("환불 승인이 실패하였습니다.\n다시 시도해 주세요.");
        }
    });
}
function fnOrderCancelDelivery(od_id,pd_type,pd_id,type){
    $.ajax({
        url:g5_url+'/mobile/page/modal/modal.deliveryreturnadd.php',
        type:"post",
        data:{od_id:od_id,pd_type:pd_type,pd_id:pd_id,type:type}
    }).done(function(data){
        $(".modal").html(data).addClass("active");
        $("html").css({"overflow":"hidden","height":"100%"});
        $("body").css({"overflow":"hidden","height":"100%"});
        location.hash = '#modal';
    });
    /*$("#return_deli_od_id").val(od_id);
    $("#return_deli_pd_type").val(pd_type);
    $("#return_deli_pd_id").val(pd_id);
   //$("#return_deli_id").val(od_id);
    $("html").css({"overflow":"hidden","height":"100%"});
    $("body").css({"overflow":"hidden","height":"100%"});
    location.hash = '#modal';
    $("#id11").show();*/
}
function fnFinUpdate(pd_id,od_id){
    /*var pd_id = $("#fin_pd_id").val();
    var od_id = $("#fin_od_id").val();*/
    if(confirm('해당 거래에 평가를 건너뛰시겠습니까?')){
        location.reload(g5_url+'/mobile/page/mypage/order_complete.php?pd_id='+pd_id+'&od_id='+od_id);
    }
}

// 숫자 타입에서 쓸 수 있도록 format() 함수 추가
Number.prototype.numberFormat = function(){
    if(this==0) return 0;

    var reg = /(^[+-]?\d+)(\d{3})/;
    var n = (this + '');

    while (reg.test(n)) n = n.replace(reg, '$1' + ',' + '$2');

    return n;
};

function fnOrderPayConfirm(od_id,pd_id){
    if(confirm('관리자에게 정산요청을 하시겠습니까?')){
        sendPush('admin','빠른 정산요청입니다.',od_id,pd_id);
    }
}

function fnDeliveryConfirm(deli_name,deli_num,pd_type,pd_id,od_id,type){
    var chk = true;
    var carriers = '';
    switch (deli_name){
        case "한진택배":
            carriers = 'kr.hanjin';
            break;
        case "우체국택배":
            carriers = 'kr.epost';
            break;
        case "로젠택배":
            carriers = 'kr.logen';
            break;
        case "대한통운":
            carriers = 'kr.cjlogistics';
            break;
        case "경동택배":
            carriers = 'kr.kdexp';
            break;
        case "DHL":
            carriers = 'de.dhl';
            break;
        case "천일택배":
            carriers = 'kr.chunilps';
            break;
        case "CU편의점택배":
            carriers = 'kr.cupost';
            break;
        case "GSpostbox택배":
            carriers = 'kr.cvsnet';
            break;
        case "대신택배":
            carriers = 'kr.daesin';
            break;
        case "한의사랑택배":
            carriers = 'kr.hanips';
            break;
        case "합동택배":
            carriers = 'kr.hdexp';
            break;
        case "홈픽":
            carriers = 'kr.homepick';
            break;
        case "한서호남택배":
            carriers = 'kr.honamlogis';
            break;
        case "일양로지스":
            carriers = 'kr.ilyanglogis';
            break;
        case "건영택배":
            carriers = 'kr.kunyoung';
            break;
        case "롯데택배":
            carriers = 'kr.lotte';
            break;
        case "SLX":
            carriers = 'kr.slx';
            break;
        case "TNT":
            carriers = 'nl.tnt';
            break;
        case "EMS":
            carriers = 'un.upu.ems';
            break;
        case "Fedex":
            carriers = 'us.fedex';
            break;
        case "UPS":
            carriers = 'us.ups';
            break;
        case "USPS":
            carriers = 'us.usps';
            break;
    }
    $.ajax({
        url:g5_url+'/mobile/page/ajax/ajax.carriers.php',
        method:"post",
        async:true,
        data:{carriers:carriers,deli_num:deli_num},
        dataType:'json'
    }).done(function(data){
        console.log(data);
        /*if(data.carrier=="" || data.carrier==null){
            alert(data.message);
            $("#return_delivery_number").val('');
        }else{*/
            if(type==0){//보내기
                $.ajax({
                    url:g5_url+"/mobile/page/ajax/ajax.order_delivery_update.php",
                    method:"post",
                    data:{od_id:od_id,delivery_name:deli_name,delivery_number:deli_num},
                    dataType:"json"
                }).done(function(data){
                    if(data.result==1){
                        alert("주문정보를 찾지 못했습니다. \n다시 시도해 주세요.")
                    }else if(data.result == 2){
                        alert("배송정보 입력 실패");
                    }else{
                        $("#deli_name").html(delivery_name);
                        $("#deli_num").html(delivery_number);
                        $("#deli_date").html(data.deli_date);
                        modalClose();
                        location.reload();
                    }
                });
            }else{//환불
                location.href=g5_url+'/mobile/page/mypage/mypage_order_return_delivery.php?od_id='+od_id+'&delivery_name='+deli_name+'&delivery_number='+deli_num+"&pd_id="+pd_id+"&pd_type="+pd_type;
            }
        //}
    });
}

function fn_keyover(screen_px,keyboard_px) {
    var over_px=0;

    if(parseInt(keyboard_px) > 0) {
        over_px=parseInt(parseInt(screen.height)*parseInt(keyboard_px))/parseInt(screen_px);
    } else {
        over_px=0;
    }
    document.getElementById("foot").style.bottom =over_px+"px";

}