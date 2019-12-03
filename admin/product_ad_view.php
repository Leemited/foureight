<?php
include_once("../common.php");
include_once(G5_PATH."/admin/admin.head.php");
$config = sql_fetch(" select * from {$g5['config_table']} ");
if(!$ad_id){
    alert("잘못된 요청입니다.");
    return;
}

$sql = "select * from `product_ad` where ad_id = '{$ad_id}'";
$write = sql_fetch($sql);
$ad_cate1 = sql_fetch("select * from `categorys` where ca_id = '{$write["ad_cate"]}'");
$ad_cate2 = sql_fetch("select * from `categorys` where ca_id = '{$write["ad_cate2"]}'");
?>
<!-- 본문 start -->
<div id="wrap">
    <section>
        <header class="admin_title">
            <h1>광고 등록</h1>
        </header>
        <div class="write_form model_list">
            <form action="<?php echo G5_URL?>/admin/product_ad_write.php" method="post" name="fwrite" id="fwrite" enctype="multipart/form-data" >
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
                            <?php echo ($write["ad_type"] == "1")? "물건" : "능력";?> / <?php echo $ad_cate1["cate_name"]."|".$ad_cate2["cate_name"];?>
                        </td>
                    </tr>
                    <tr>
                        <th>키워드</th>
                        <td class="con">
                            <?php echo $write["ad_keyword"];?>
                        </td>
                    </tr>
                    <tr>
                        <th>사진</th>
                        <td class="con" style="position:relative;">
                            <img src="<?php echo G5_DATA_URL?>/product/<?php echo $write["ad_photo"];?>" alt="">
                        </td>
                    </tr>
                    <tr>
                        <th>제목</th>
                        <td class="con">
                            <?php echo $write["ad_subject"];?>
                        </td>
                    </tr>
                    <tr class="content">
                        <th>내용</th>
                        <td class="con">
                            <?php echo $write["ad_con"];?>
                        </td>
                    </tr>
                    <tr class="content">
                        <th>링크</th>
                        <td class="con">
                            <?php echo $write["ad_link"];?>
                        </td>
                    </tr>
                    <tr class="content">
                        <th>노출 순서 [판매글]</th>
                        <td class="con">
                            <?php echo $write["ad_sort"];?>
                        </td>
                    </tr>
                    <tr class="content">
                        <th>노출 순서 [구매글]</th>
                        <td class="con">
                            <?php echo $write["ad_sort2"];?>
                        </td>
                    </tr>
                    <tr class="content">
                        <th>시작/종료</th>
                        <td class="con">
                            시작 : <?php echo $write["ad_from"];?> / 종료 : <?php echo $write["ad_to"];?>
                        </td>
                    </tr>
                    <tr class="content">
                        <th>상태</th>
                        <td class="con"><?php if($ad_status == 0){echo "활성";}else{echo "비활성";}?></td>
                    </tr>
                    </tbody>
                </table>
        </div>
        <div class="write_gr">
            <input type="button" class="cancel_btn" value="취소" onclick="location.href='<?php echo G5_URL?>/admin/product_ad_list.php?page=<?php echo $page;?>&sfl=<?php echo $sfl;?>&stx=<?php echo $stx;?>&order=<?php echo $order;?>&desc=<?php echo $desc;?>'">
            <input type="submit" value="<?php if($ad_id){?>수정<?php }else{ ?>등록<?php }?>" class="submit_btn" style="border:none"/>
        </div>
        </form>

    </section>
</div>

<?php
include_once(G5_PATH."/admin/admin.tail.php");
?>
