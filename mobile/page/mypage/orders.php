<?php
include_once ("../../../common.php");
include_once (G5_MOBILE_PATH."/head.login.php");

if(!$group_id){
    alert("주문 상품이 없거나 잘못된 요청입니다.");
    return false;
}

$sql = "select * from `order_temp` as o left join `product` as p on o.pd_id = p.pd_id where group_id = '{$group_id}'  ";
$res = sql_query($sql);
while($row=sql_fetch_array($res)){
    $order_list[] = $row;
    $total += (int)$row["od_price"];
    $cart_ids[] = $row["cid"];
}
$cart_idss = implode(",",$cart_ids);
$order_item_name = $order_list[0]["pd_name"];
if(count($order_list)>1){
    $order_item_name .= " 외 ".(count($order_list)-1)."개";
}

$sql = "select * from `my_address` where mb_id = '{$member["mb_id"]}' order by addr_default desc";
$res = sql_query($sql);
while($row = sql_fetch_array($res)){
    $my_addres[] = $row;
}

$my_addres_li = count($my_addres);

$mb_hp = explode("-",$member["mb_hp"]);

$back_url = G5_MOBILE_URL."/page/mypage/cart.php?group_id=".$group_id."&cart_id=".$cart_idss;
?>
<div id="id00" class="w3-modal w3-animate-opacity no-view">
    <div class="w3-modal-content w3-card-4">
        <div class="w3-container">
                <input type="hidden" value="<?php echo $member["mb_id"];?>" name="mb_id" id="mb_id">
                <input type="hidden" value="<?php echo $member["mb_id"];?>" name="mb_id" id="mb_id">
                <input type="hidden" value="<?php echo $member["mb_id"];?>" name="mb_id" id="mb_id">
                <h2>배송지저장</h2>
                <div>
                    <input type="text" value="" name="addr_name" id="addr_name" placeholder="배송지 이름" required>
                </div>
                <div>
                    <input type="button" value="취소" onclick="modalClose(this)"><input type="button" value="저장" onclick="fnAddrSave(1);" ><input type="button" value="기본배송지로저장" onclick="fnAddrSave(2);" style="width:auto;margin-left:1vw" >
                </div>
        </div>
    </div>
</div>
<div class="sub_head">
    <div class="sub_back" onclick="location.href='<?php echo $back_url;?>'"><img src="<?php echo G5_IMG_URL?>/ic_menu_back.svg" alt=""></div>
    <h2>주문/결제하기</h2>
</div>
<div class="orders">
    <div class="order_items">
        <div class="item_top">
            <?php if($order_list[0]["pd_images"]!=""){
                $img = explode(",",$order_list[0]["pd_images"]);
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
                    $tags = explode("/",$order_list[0]["pd_tag"]);
                    $rand = rand(1,13);
                    ?>
                    <div class="bg rand_bg<?php echo $rand;?> item_images" >
                        <div class="tags" style="display:table">
                            <?php //for($k=0;$k<count($tags);$k++){
                                $rand_font = rand(3,6);
                                ?>
                                <div class="rand_size<?php echo $rand_font;?>" style="display:table-cell;vertical-align: middle;text-align: center"><?php echo $order_list[0]["pd_tag"];?></div>
                            <?php //}?>
                        </div>
                        <div class="clear"></div>
                    </div>
                <?php }?>
            <?php }else{
                $tags = explode("#",$order_list[0]["pd_tag"]);
                $rand = rand(1,13);
                ?>
                <div class="bg rand_bg<?php echo $rand;?> item_images" >
                    <div class="tags" style="display:table;width:100%;height:100%;">
                        <?php //for($k=0;$k<count($tags);$k++){
                            $rand_font = rand(3,6);
                            ?>
                            <div class="rand_size<?php echo $rand_font;?>" style="display:table-cell;vertical-align: middle;text-align: center"><?php echo $order_list[0]["pd_tag"];?></div>
                        <?php //}?>
                    </div>
                    <div class="clear"></div>
                </div>
            <?php }?>
            <div class="item_info">
                <h2><?php echo $order_item_name;?></h2>
                <div>주문 금액 <?php echo number_format($total);?> 원</div>
            </div>
            <div class="clear"></div>
        </div>
        <div class="item_bottom">
            <div>
                <h2>총금액 <span><?php echo number_format($total);?> 원</span></h2>
            </div>
        </div>
    </div>
    <div class="address_tab">
        <ul>
            <?php if(count($my_addres) == 0){?>
            <li class="active" style="width:20%;">기본배송지</li>
            <?php }else{
                for($i=0;$i<count($my_addres);$i++){
                ?>
                    <li style="width:20%;float:left" id='<?php echo $my_addres[$i]["id"];?>' <?php if($my_addres[$i]["addr_default"] == 1){?>class="active"<?php }?> ><?php if($my_addres[$i]["addr_default"]==1){?>기본배송지<?php }else{ if($my_addres[$i]["addr_name"]){echo $my_addres[$i]["addr_name"];}else{echo "이름없음";}}?></li>
            <?php }
                }?>
        </ul>
        <div class="clear"></div>
    </div>
    <form action="<?php echo G5_MOBILE_URL?>/page/mypage/order_update.php" name="order_form_update">
    <div class="order_write">
        <input type="hidden" name="group_id" id="group_id" value="<?php echo $group_id;?>">
        <div class="write_form">
            <div class="row">
                <div class="cell title">받는분</div>
                <div class="cell inputs">
                    <input type="text" name="od_name" id="od_name" value="<?php echo $member["mb_name"];?>" class="order_input02" required>
                    <input type="button" value="배송지저장" class="order_addr" onclick="fnAddr();">
                </div>
            </div>
            <div class="row">
                <div class="cell title">우편번호</div>
                <div class="cell inputs">
                    <input type="text" name="od_zipcode" id="od_zipcode" value="<?php echo $member["mb_zip1"];?>" class="order_input02" required readonly>
                    <input type="button" value="우편번호 검색" class="order_addr" onclick="sample3_execDaumPostcode()">
                </div>
                <div id="wraps" style="display:none;border:1px solid;width:100%;height:300px;margin:5px 0;position:relative">
                    <img src="//t1.daumcdn.net/localimg/localimages/07/postcode/320/close.png" id="btnFoldWrap" style="cursor:pointer;position:absolute;right:0px;top:-1px;z-index:1" onclick="foldDaumPostcode()" alt="접기 버튼">
                </div>
            </div>
            <div class="row">
                <div class="cell title">기본주소</div>
                <div class="cell inputs">
                    <input type="text" name="od_address1" id="od_address1" value="<?php echo $member["mb_addr1"];?>" class="order_input" required readonly>
                </div>
            </div>
            <div class="row">
                <div class="cell title">상세주소</div>
                <div class="cell inputs">
                    <input type="text" name="od_address2" id="od_address2" value="<?php echo $member["mb_addr2"];?>" class="order_input" required>
                </div>
            </div>
            <div class="row">
                <div class="cell title">연락처</div>
                <div class="cell inputs">
                    <select name="tel1" id="tel1" class="hps" required>
                        <option value="010">010</option>
                        <option value="019">019</option>
                        <option value="018">018</option>
                        <option value="017">017</option>
                        <option value="070">070</option>
                        <option value="02">02</option>
                        <option value="031">031</option>
                        <option value="032">032</option>
                        <option value="033">033</option>
                        <option value="041">041</option>
                        <option value="042">042</option>
                        <option value="043">043</option>
                        <option value="044">044</option>
                        <option value="051">051</option>
                        <option value="052">052</option>
                        <option value="053">053</option>
                        <option value="054">054</option>
                        <option value="055">055</option>
                        <option value="061">061</option>
                        <option value="062">062</option>
                        <option value="063">063</option>
                        <option value="064">064</option>
                    </select> -
                    <input type="text" name="tel2" id="tel2" maxlength="4" value="<?php echo $mb_hp[1];?>" class="hps" required> - <input type="text" name="tel3" id="tel3" maxlength="4" value="<?php echo $mb_hp[2];?>" class="hps" required>
                </div>
            </div>
            <div class="row">
                <div class="cell full">
                    <input type="text" name="od_content" id="od_content" class="order_input" placeholder="판매자에게 남기는 말(50자 이내);">
                </div>
            </div>
        </div>
    </div>
    <div class="order_info">
        <h2>사기조회 / 안전개래 안내</h2>
        <div>
            48에서는 직접적인 거래 사기와 피해를 보장해 주지 않습니다.<br>
            따라서 결제전 신중하게 판단 하시기 바랍니다.
            <div class="order_info_link" style="position: relative;width:100%;text-align: center;margin:4vw 0 6vw;display: inline-block">
            <div style="background-color:#0c0c94;width:calc(50% - 7vw);padding:2vw 3vw;font-size:3vw;margin-right:1vw;color:#fff;margin-top:2vw;text-align: center;-webkit-border-radius: 4vw;-moz-border-radius: 4vw;border-radius: 4vw;float:left" onclick="location.href='https://thecheat.co.kr/rb/?mod=_search'">
                <img src="<?php echo G5_IMG_URL;?>/logo1.png" alt="" style="width:36%"> 사기조회 하기
            </div>
            <div style="background-color:#2584c6;width:calc(50% - 7vw);padding:2vw 3vw;font-size:3vw;margin-left:1vw;color:#FFF;margin-top:2vw;text-align: center;-webkit-border-radius: 4vw;-moz-border-radius: 4vw;border-radius: 4vw;float:left" onclick="location.href='https://www.unicro.co.kr/index.jsp'">
                <img src="<?php echo G5_IMG_URL;?>/unicro_logo.jpg" alt="" style="width:36%"> 안전거래 하기
            </div>
            </div>
            <div class="clear"></div>
        </div>
    </div>
    <div class="order_btns">
        <!-- <input type="button" value="직거래" class="order_btn" onclick="orderDirect()"> -->
        <input type="button" value="즉시결재" class="order_btn2" onclick="orderUpdate()">
    </div>
    </form>
</div>
<script>
function orderUpdate(){
    if(confirm("해당 주문건에 대한 결제를 하시겠습니까?")){
        var od_name = $("#od_name").val();
        var od_tel = $("#tel1").val()+"-"+$("#tel2").val()+"-"+$("#tel3").val();
        var od_address = $("#od_address1").val()+" "+$("#od_address2").val();
        var od_zipcode = $("#od_zipcode").val();
        var mt_id = $("#group_id").val();
        var IMP = window.IMP; // 생략해도 괜찮습니다.
        IMP.init("imp55770254"); // "imp00000000" 대신 발급받은 "가맹점 식별코드"를 사용합니다.

        // IMP.request_pay(param, callback) 호출
        IMP.request_pay({ // param
            pg: "html5_inicis",
            escrow:true,
            pay_method: "card",
            merchant_uid: mt_id,
            name: "<?php echo $order_item_name;?>",
            amount: <?php echo $total;?>,
            buyer_email: "<?php echo $member["mb_id"];?>",
            buyer_name: od_name,
            buyer_tel: od_tel,
            buyer_addr: od_address,
            buyer_postcode: od_zipcode,
            m_redirect_url: g5_url+"/mobile/page/mypage/order_update.php?group_id="+mt_id+"&od_name="+od_name+"&od_address1="+$("#od_address1").val()+"&od_address2="+$("#od_address2").val()+"&tel1="+$("#tel1").val()+"&tel2="+$("#tel2").val()+"&tel3="+$("#tel3").val()+"&od_zipcode="+od_zipcode+"&od_content="+$("#od_content").val()
        }, function (rsp) { // callback
            if (rsp.success) {
                console.log(rsp.mt_id);
                document.order_form_update.submit();
            } else {
                alert(rep);
            }
        });
        //document.order_form_update.submit();
    }else{
        return false;
    }
}
function orderDirect(){
    if(confirm("직거래 이용시 판매자의 동의가 필요합니다.")){
        //판매자에게 동의 확인 알림 확인 후 처리
        $.ajax({
            url:g5_url+"/mobile/page/mypage/ajax.pay_direct.php",
            method:"post",
            data:{pd_ids:pd_ids}
        }).done(function(data){

        });
    }else{
        return false;
    }
}

function fnAddr(){
    var zipcode = $("#od_zipcode").val();
    var addr1 = $("#od_address1").val();
    var addr2 = $("#od_address2").val();
    var addr_mbname = $("#od_name").val();
    if(zipcode=="" && addr1 == "" && addr2=="" && addr_mbname==""){
        alert("주소정보를 입력해 주세요.");
        return false;
    }
    $("#id00").css("display","block");
    $("html,body").css("height","100vh");
    $("html,body").css("overflow","hidden");
    location.hash = "modal";
}

function fnAddrSave(type){
    var zipcode = $("#od_zipcode").val();
    var addr1 = $("#od_address1").val();
    var addr2 = $("#od_address2").val();
    var addr_name = $("#addr_name").val();
    var addr_mbname = $("#od_name").val();
    if(addr_name==""){
        alert("배송지명을 입력해 주세요.");
        return false;
    }
    $.ajax({
        url:g5_url+"/mobile/page/mypage/ajax.add_address.php",
        method:"post",
        data:{zipcode:zipcode,addr1:addr1,addr2:addr2,type:type,addr_name:addr_name,addr_mbname:addr_mbname},
        dataType:"json"
    }).done(function(data){
        console.log(data);
        if(data.result == 0){
            alert("배송지저장 갯수를 초과 했습니다.");
        }
        if(data.result==2){
            alert("등록되었습니다.");
            if(type==2){
                var li = "<li id='"+data.id+"' class='active' style='width:20%;float:left'>"+addr_name+"</li>";
                $(".address_tab li").removeClass("active");
                $(".address_tab ul").prepend(li);
            }else{
                var li = "<li id='"+data.id+"' style='width:20%;float:left'>"+addr_name+"</li>";
                $(".address_tab ul").append(li);
            }

        }if(data.result==1){
            alert("중복된 정보 입니다.");
        }if(data.result==3){
            alert("입력 정보 오류로 인해 등록이 취소 되었습니다.");
        }
        modalClose();
    });
}
$(function(){
   $(document).on("click",".address_tab li",function(){
       console.log("A");
      if(!$(this).hasClass("active")){
       console.log("B");
          var id = $(this).attr("id");
          $(this).addClass("active");
          $(".address_tab li").not($(this)).removeClass("active");
          $.ajax({
              url:g5_url+"/mobile/page/mypage/ajax.select_address.php",
              method:"post",
              data:{id:id},
              dataType:"json"
          }).done(function(data){
              console.log(data);
              $("#od_name").val(data.addr_mbname);
              $("#od_zipcode").val(data.addr_zipcode);
              $("#od_address1").val(data.addr_address1);
              $("#od_address2").val(data.addr_address2);
          })
      }
   });
});
</script>
<script src="http://dmaps.daum.net/map_js_init/postcode.v2.js"></script>
<script>
    // 우편번호 찾기 찾기 화면을 넣을 element
    var element_wrap = document.getElementById('wraps');

    function foldDaumPostcode() {
        // iframe을 넣은 element를 안보이게 한다.
        element_wrap.style.display = 'none';
    }

    function sample3_execDaumPostcode() {
        // 현재 scroll 위치를 저장해놓는다.
        var currentScroll = Math.max(document.body.scrollTop, document.documentElement.scrollTop);
        new daum.Postcode({
            oncomplete: function(data) {
                // 검색결과 항목을 클릭했을때 실행할 코드를 작성하는 부분.

                // 각 주소의 노출 규칙에 따라 주소를 조합한다.
                // 내려오는 변수가 값이 없는 경우엔 공백('')값을 가지므로, 이를 참고하여 분기 한다.
                var fullAddr = data.address; // 최종 주소 변수
                var extraAddr = ''; // 조합형 주소 변수

                // 기본 주소가 도로명 타입일때 조합한다.
                if(data.addressType === 'R'){
                    //법정동명이 있을 경우 추가한다.
                    if(data.bname !== ''){
                        extraAddr += data.bname;
                    }
                    // 건물명이 있을 경우 추가한다.
                    if(data.buildingName !== ''){
                        extraAddr += (extraAddr !== '' ? ', ' + data.buildingName : data.buildingName);
                    }
                    // 조합형주소의 유무에 따라 양쪽에 괄호를 추가하여 최종 주소를 만든다.
                    fullAddr += (extraAddr !== '' ? ' ('+ extraAddr +')' : '');
                }

                // 우편번호와 주소 정보를 해당 필드에 넣는다.
                document.getElementById('od_zipcode').value = data.zonecode; //5자리 새우편번호 사용
                document.getElementById('od_address1').value = fullAddr;

                // iframe을 넣은 element를 안보이게 한다.
                // (autoClose:false 기능을 이용한다면, 아래 코드를 제거해야 화면에서 사라지지 않는다.)
                element_wrap.style.display = 'none';

                // 우편번호 찾기 화면이 보이기 이전으로 scroll 위치를 되돌린다.
                document.body.scrollTop = currentScroll;
            },
            // 우편번호 찾기 화면 크기가 조정되었을때 실행할 코드를 작성하는 부분. iframe을 넣은 element의 높이값을 조정한다.
            onresize : function(size) {
                element_wrap.style.height = size.height+'px';
            },
            width : '100%',
            height : '100%'
        }).embed(element_wrap);

        // iframe을 넣은 element를 보이게 한다.
        element_wrap.style.display = 'block';
    }
</script>
<?php
include_once (G5_MOBILE_PATH."/tail.orders.php");
