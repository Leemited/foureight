<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

?>
<!--<div id="id03" class="w3-modal w3-animate-opacity no-view">
    <div class="w3-modal-content w3-card-4">
        <div class="w3-container">
            <form name="write_from" id="write_from" method="post" action="">
                <input type="hidden" name="up_pd_id" id="up_pd_id" value="">
                <h2>상태변경</h2>
                <div>
                    <ul class="modal_sel">
                        <li id="status1" class="active" >판매중</li>
                        <li id="status2" class="" >거래중</li>
                        <li id="status3" class="" >판매보류</li>
                        <li id="status4" class="" >판매완료</li>
                    </ul>
                </div>
                <div>
                    <input type="button" value="취소" onclick="modalClose(this)"><input type="button" value="확인" onclick="fnStatusUpdate();" >
                </div>
            </form>
        </div>
    </div>
</div>-->
<div id="id01s" class="w3-modal w3-animate-opacity no-view">
    <div class="w3-modal-content w3-card-4">
        <div class="w3-container">
            <div class="con">

            </div>
        </div>
    </div>
</div>
<div id="id0s" class="w3-modal w3-animate-opacity">
    <div class="w3-modal-content w3-card-4">
        <div class="w3-container">
            <div class="con">

            </div>
        </div>
    </div>
</div>
<div id="id02" class="w3-modal w3-animate-opacity no-view">
    <div class="w3-modal-content w3-card-4">
        <div class="w3-container">
            <input type="hidden" name="like_id" id="like_id" value="">
            <input type="hidden" name="view_pd_type" id="view_pd_type" value="">
            <h2>평가하기</h2>
            <div class="likes">
                좋아요 <img src="<?php echo G5_IMG_URL?>/view_like.svg" alt="" class="likeimg" >
            </div>
            <div>
                <input type="text" name="like_content" id="like_content" placeholder="평가 내용을 입력해주세요." required>
            </div>
            <div>
                <input type="button" value="취소" onclick="modalClose(this)"><input type="button" value="확인" onclick="fnLikeUpdate();" >
            </div>
        </div>
    </div>
</div>

<!-- 사용안함? -->
<!--<div id="id04" class="w3-modal w3-animate-opacity no-view">
    <div class="w3-modal-content w3-card-4">
        <div class="w3-container">
            <form name="write_from" id="write_from" method="post" action="">
                <h2>블라인드 사유</h2>
                <div>
                    <input type="text" name="like_content" id="like_content" placeholder="평가 내용을 입력해주세요." required>
                </div>
                <div>
                    <input type="button" value="취소" onclick="modalClose(this)"><input type="button" value="제시하기" onclick="fnPricingUpdate();" >
                </div>
            </form>
        </div>
    </div>
</div>-->

<!--<div id="id07" class="w3-modal w3-animate-opacity no-view">
    <div class="w3-modal-content w3-card-4">
        <div class="w3-container">
            <form name="write_from" id="write_from" method="post" action="">
                <input type="hidden" name="p_pd_id" id="p_pd_id" value="">
                <input type="hidden" name="p_type" id="p_type" value="">
                <h2>제시하기</h2>
                <div>
                    <select name="prcing_pd_id" id="prcing_pd_id" required>
                        <option value="">게시물 선택</option>
                    </select>
                    <ul class="blind_ul">
                        <li>
                            <input type="text" placeholder="제시내용을 입력하세요." name="pricing_content" id="pricing_content" required>
                        </li>
                        <li>
                            <input type="text" placeholder="가격을 입력해주세요." name="pricing_price" id="pricing_price" style="margin-top:0;" onkeyup="number_only(this)">
                        </li>
                    </ul>
                </div>
                <div>
                    <input type="button" value="취소" onclick="modalClose(this)"><input type="button" value="제시등록" style="width:auto;padding:2vw 3vw" id="up_btn" onclick="fnPricingUpdate();" >
                </div>
            </form>
        </div>
    </div>
</div>-->

<!--<div id="id08" class="w3-modal w3-animate-opacity no-view">
    <div class="w3-modal-content w3-card-4">
        <div class="w3-container">
            <form name="write_from" id="write_from" method="post" action="">
                <h2>연락하기</h2>
                <div class="contacts">
                    <ul>

                    </ul>
                </div>
                <div>
                    <input type="button" value="닫기" onclick="modalClose2()">
                </div>
            </form>
        </div>
    </div>
</div>-->
<div id="id00" class="w3-modal w3-animate-opacity no-view">
    <div class="w3-modal-content w3-card-4">
        <div class="w3-container">
            <input type="hidden" value="<?php echo $order["od_id"];?>" name="od_id" id="od_id">
            <h2>배송정보 입력</h2>
            <div>
                <input type="hidden" id="deli_od_id" value="">
                <input type="text" name="delivery_name" id="delivery_name" required style="width:50%">
                <select name="deli_sel" id="deli_sel" onchange="$('#delivery_name').val(this.value)" style="width:calc(50% - 8vw);text-align: center;background-color: #FFF;color: #000;position: relative;    margin: 4vw auto;padding: 2vw;font-size: 3.6vw;border-radius: 20vw;border: none;font-family: 'nsr', sans-serif;">
                    <option value="">택배사선택</option>
                    <option value="한진택배">한진택배</option>
                    <option value="우체국택배">우체국택배</option>
                    <option value="옐로우캡">옐로우캡</option>
                    <option value="로젠택배">로젠택배</option>
                    <option value="대한통운">대한통운</option>
                    <option value="경동택배">경동택배</option>
                    <option value="">직접입력</option>
                </select>
                <!--<input type="text" value="" name="delivery_name" id="delivery_name" placeholder="택배사" required >-->
                <input type="text" value="" name="delivery_number" id="delivery_number" placeholder="운송장번호" required style="margin-top:0;">
            </div>
            <div>
                <input type="button" value="취소" onclick="modalClose(this)"><input type="button" value="배송정보 등록" onclick="fnConfirmDelivery();" style="width:auto;margin-left:1vw" >
            </div>
        </div>
    </div>
</div>
