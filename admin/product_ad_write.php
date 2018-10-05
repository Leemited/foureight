<?php
include_once("../common.php");
include_once(G5_PATH."/admin/admin.head.php");
$config = sql_fetch(" select * from {$g5['config_table']} ");

if($ad_id) {
    $sql = "select * from `product_ad` where ad_id = '{$ad_id}'";
    $write = sql_fetch($sql);
}
?>
<!-- 본문 start -->
<div id="wrap">
    <section>
        <header class="admin_title">
            <h1>광고 등록</h1>
        </header>
        <div class="write_form model_list">
            <form action="<?php echo G5_URL?>/admin/product_ad_update.php" method="post" name="fwrite" id="fwrite" enctype="multipart/form-data" >
                <input type="hidden" name="page" id="page" value="<?php echo $page;?>">
                <input type="hidden" name="order" id="order" value="<?php echo $order;?>">
                <input type="hidden" name="desc" id="desc" value="<?php echo $desc;?>">
                <input type="hidden" name="sfl" id="sfl" value="<?php echo $sfl;?>">
                <input type="hidden" name="stx" id="stx" value="<?php echo $stx;?>">
                <input type="hidden" name="ad_id" id="ad_id" value="<?php echo $ad_id;?>">
                <table class="view_tbl">
                    <colgroup>
                        <col width="10%">
                        <col width="90%">
                    </colgroup>
                    <tbody class="pcT">
                    <tr>
                        <th>타입/카테고리</th>
                        <td class="con">
                            <input type="radio" name="ad_type" id="type1" value="1" <?php if($write["ad_type"]==1 || $write["ad_type"] == "1"){echo "checked";}?>><label for="type1">물건</label> <input type="radio" name="ad_type" id="type2" value="2" <?php if($write["ad_type"]==2){echo "checked";}?>><label for="type2">능력</label>
                            <select name="cate1" id="cate1" required>
                                <option>타입을 선택해주세요.</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th>사진</th>
                        <td class="con" style="position:relative;">
                            <input type="file" name="ad_photo" id="ad_photo" <?php if(!$ad_id){echo "required";}?> onchange="$('#ad_photo_name').val(this.value);" style="display:none;"><label for="ad_photo" style="position:absolute;left:calc(60% - 76px);top:20px;padding:5px 10px;background-color:#000;color:#fff">사진선택</label>
                            <input type="text" value="<?php echo $write["ad_photo"];?>" name="ad_photo_name" id="ad_photo_name" required class="write_input01 grid_60" readonly/>
                        </td>
                    </tr>
                    <tr>
                        <th>제목</th>
                        <td class="con"><input type="text" value="<?php echo $write["ad_subject"];?>" name="ad_subject" id="ad_subject" required class="write_input01 grid_60"/></td>
                    </tr>
                    <tr class="content">
                        <th>내용</th>
                        <td class="con"><input type="text" value="<?php echo $write["ad_con"];?>" name="ad_con" id="ad_con" required class="write_input01 grid_60"/></td>
                    </tr>
                    <tr class="content">
                        <th>링크</th>
                        <td class="con"><input type="text" value="<?php echo $write["ad_link"];?>" name="ad_link" id="ad_link" required class="write_input01 grid_60"/>
                        <p>반드시 http://로 시작해야 하며 외부 링크 등록시 해당 링크가 정확한 링크인지 확인 후 등록 바랍니다.</p>
                        </td>
                    </tr><tr class="content">
                        <th>노출 순서</th>
                        <td class="con"><input type="text" value="<?php echo $write["ad_sort"];?>" name="ad_sort" id="ad_sort" required class="write_input01 grid_60"/>
                            <span class="product_cnt">0 부터 까지 입력</span>
                            <p>노출 순서는 등록된 게시물 기준으로 등록 하되, 검색기준에 따라 노출이 안될 수 도 있습니다.</p>
                        </td>
                    </tr>
                    <tr class="content">
                        <th>시작/종료</th>
                        <td class="con">
                            시작 : <input type="text" value="<?php echo $write["ad_from"];?>" name="ad_from" id="ad_from" required class="write_input01 grid_30"/>
                            종료 : <input type="text" value="<?php echo $write["ad_to"];?>" name="ad_to" id="ad_to" required class="write_input01 grid_30"/>
                        </td>
                    </tr>
                    <tr class="content">
                        <th>싱태</th>
                        <td class="con"><input type="radio" name="ad_status" id="on" value="0" <?php if($write["ad_status"]==0){?>checked<?php }?>><label for="on">활성</label> <input type="radio" name="ad_status" id="off" value="1" <?php if($write["ad_status"]==1){?>checked<?php }?>><label for="off">비활성</label></td>
                    </tr>
                    </tbody>
                </table>
        </div>
        <div class="write_gr">
            <input type="button" class="cancel_btn" value="취소" onclick="location.href='<?php echo G5_URL?>/admin/board_list.php?bo_table=<?php echo $bo_table;?>&page=<?php echo $page;?>&sfl=<?php echo $sfl;?>&stx=<?php echo $stx;?>&ca_name=<?php echo $ca_name;?>'">
            <input type="submit" value="<?php if($wr_id){?>수정<?php }else{ ?>등록<?php }?>" class="submit_btn" style="border:none"/>
        </div>
        </form>

    </section>
</div>
<script>
    $("#ad_from,#ad_to").datepicker({
        changeMonth: true, // 월을 바꿀수 있는 셀렉트 박스를 표시한다.
        changeYear: true, // 년을 바꿀 수 있는 셀렉트 박스를 표시한다.
        minDate: 'D', // 현재날짜로부터 100년이전까지 년을 표시한다.
        nextText: '다음 달', // next 아이콘의 툴팁.
        prevText: '이전 달', // prev 아이콘의 툴팁.
        numberOfMonths: [1,1], // 한번에 얼마나 많은 월을 표시할것인가. [2,3] 일 경우, 2(행) x 3(열) = 6개의 월을 표시한다.
        stepMonths: 3, // next, prev 버튼을 클릭했을때 얼마나 많은 월을 이동하여 표시하는가.
        yearRange: 'c-100:c+10', // 년도 선택 셀렉트박스를 현재 년도에서 이전, 이후로 얼마의 범위를 표시할것인가.
        showButtonPanel: true, // 캘린더 하단에 버튼 패널을 표시한다.
        currentText: '오늘 날짜' , // 오늘 날짜로 이동하는 버튼 패널
        closeText: '닫기',  // 닫기 버튼 패널
        dateFormat: "yy-mm-dd", // 텍스트 필드에 입력되는 날짜 형식.
        showAnim: "slide", //애니메이션을 적용한다.
        showMonthAfterYear: true , // 월, 년순의 셀렉트 박스를 년,월 순으로 바꿔준다.
        dayNamesMin: ['월', '화', '수', '목', '금', '토', '일'], // 요일의 한글 형식.
        monthNamesShort: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'] // 월의 한글 형식.
    });
    $("#ad_from,#ad_to").on("change",function(){
       var from = $("#ad_from").val();
       var to = $("#ad_to").val();
       var dfrom = new Date(from);
       var dto = new Date(to);
       if(from && to) {
           if (from > to) {
               alert("시작일이 종료일보다 이후 날짜입니다.");
               $("#ad_to").val('');
           }
       }
    });
    $("input[name='ad_type']").change(function(){
        var ad_type = $(this).val();
        $.ajax({
            url:g5_url+"/admin/ajax.category_list.php",
            method:"POST",
            data:{ad_type:ad_type}
        }).done(function(data){
            $("#cate1").html(data);
            $.ajax({
                url:g5_url+"/admin/ajax.category_list_count.php",
                method:"post",
                data:{ad_type:ad_type}
            }).done(function(data){
                $(".product_cnt").html("0부터 "+data+"사이값 입력");
            })
        });
    });
</script>
<?php
include_once(G5_PATH."/admin/admin.tail.php");
?>
