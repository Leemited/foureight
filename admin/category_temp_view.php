<?php
include_once("../common.php");
include_once(G5_PATH."/admin/admin.head.php");

$subject = "제안카테고리[보기]";
$view = sql_fetch("select * from `category_user_temp` where `ca_temp_id` = '{$ca_temp_id}'");

?>
<!-- 본문 start -->
<div id="wrap">
    <section>
        <header class="admin_title">
            <h1><?php echo $subject;?></h1>
        </header>
        <article>
            <form action="<?php echo G5_URL?>/admin/category_update.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="ca_id" id="ca_id" value="<?php echo $ca_id;?>">
                <div class="model_list">
                    <div class="etc_gr">
                    </div>
                    <table class="view_tb">
                        <colgroup >
                            <col width="20%">
                            <col width="*">
                        </colgroup>
                        <tbody >
                        <tr>
                            <th>제안자 아이디</th>
                            <td class="subject" style="cursor:auto"><?php echo $view["mb_id"];?></td>
                        </tr>
                        <?php if($view["cate_name"]){?>
                        <tr>
                            <th>제안카테고리명</th>
                            <td class="subject" style="cursor:auto"><?php echo $view["cate_name"];?></td>
                        </tr>
                        <?php if($view["cate_name2"]){?>
                        <tr>
                            <th>제안상세카테고리명</th>
                            <td class="subject" style="cursor:auto">
                                <input type="text" name="cate_tag" id="cate_tag" class="write_input01 grid_90" value="<?php echo $view["cate_tag"];?>" placeholder="<?php if($view["cate_tag"]==""){echo "미등록";}?>">
                                <p>키워드는 ','구분하여 등록바랍니다.</p>
                            </td>
                        </tr>
                        <?php }?>
                        <?php }?>
                        <tr>
                            <th>제안내용</th>
                            <td class="subject" style="cursor:auto"><?php echo $view["cate_temp_content"];?></td>
                        </tr>
                        <tr>
                            <th>제안날짜</th>
                            <td class="subject" style="cursor:auto"><?php echo $view["insert_date"];?></td>
                        </tr>
                        <tr>
                            <th>상태</th>
                            <td class="subject" style="cursor:auto"><?php echo ($view["status"]==0)?"대기":"등록완료";?></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="submit_gr no_print">
                    <input type="button" onclick="location.href='<?php echo G5_URL."/admin/category_temp_list.php?page=".$page; ?>'" class="adm-btn01" style="border:none;width:auto" value="목록">
                    <?php if($view["cate_name"]){?>
                    <input type="button" onclick="fnCateAdd('<?php echo G5_URL."/admin/category_temp_update.php?cate_name=".$view["cate_name"]."&cate_name2=".$view["cate_name2"]."&ca_temp_id=".$view["ca_temp_id"]; ?>')" class="adm-btn01" style="border:none;width:auto" value="바로등록">
                    <?php }?>
                </div>
            </form>
        </article>
    </section>
</div>
<script>
    function fnCateAdd(url){
        var type = $("#cate_type").val();
        var type_name = $("#cate_type option:selected").text();
        if(confirm('해당 카테고리를 ['+type_name+']에 바로 등록하시겠습니까?')){
            location.href=url+"&cate_type="+type+"&temp=true";
        }else{
            return false;
        }
    }
</script>
<?php
include_once(G5_PATH."/admin/admin.tail.php");
?>
