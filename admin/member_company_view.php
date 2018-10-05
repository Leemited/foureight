<?php
include_once("../common.php");
include_once(G5_PATH."/admin/admin.head.php");

$mb_id = $_REQUEST["mb_id"];

$view = sql_fetch("select * from `company_info` where `cp_id` = '{$cp_id}'");

?>
<!-- 본문 start -->
<div id="wrap">
    <section>
        <header class="admin_title">
            <h1><?php echo $subject;?></h1>
        </header>
        <article>
            <div class="model_list">
                <div class="etc_gr">
                </div>
                <table class="view_tbl">
                    <colgroup class="pcT2">
                        <col width="12%">
                        <col width="23%">
                        <col width="12%">
                        <col width="23%">
                    </colgroup>
                    <!--<colgroup class="mobileT2">
                        <col width="23%">
                        <col width="*">
                        <col width="*">
                        <col width="*">
                        <col width="*">
                    </colgroup>-->
                    <tbody class="pcT">
                    <tr>
                        <th>사업자명</th>
                        <td colspan="" class=""><?php echo $view["com_name"];?></td>
                        <th>사업자번호</th>
                        <td colspan="" class=""><?php echo $view["com_num"];?></td>
                    </tr>
                    <tr>
                        <th>업종</th>
                        <td ><?php echo $view["com_type1"];?></td>
                        <th>업태</th>
                        <td ><?php echo $view["com_type2"];?></td>
                    </tr>
                    <tr>
                        <th>등록자</th>
                        <td ><?php echo $view["mb_id"];?></td>
                        <th>등록일</th>
                        <td ><?php echo $view["com_datetime"];?></td>
                    </tr>
                    <tr>
                        <th>주소</th>
                        <td colspan="3"><?php echo "[".$view["com_zip"]." ".$view["com_addr1"]." ".$view["com_addr2"];?></td>
                    </tr> 
                    <tr>
                        <th>사업자등록증</th>
                        <td colspan="3">
                            <img src="<?php echo G5_DATA_URL?>/company/<?php echo $view["com_sign"];?>" alt="">
                        </td>
                    </tr>
                    </tbody>
                    <!--<tbody class="mobileT">
                    <tr>
                        <th>제목</th>
                        <td colspan="3" class="subject"><?php /*echo $view["wr_subject"];*/?></td>
                    </tr>
                    <tr>
                        <th>작성자</th>
                        <td colspan="3" class="subject"><?php /*echo $view["wr_name"];*/?></td>
                    </tr>
                    <tr>
                        <th>작성일</th>
                        <td colspan="3" class="subject"><?php /*echo $view["wr_datetime"];*/?></td>
                    </tr>
                    <tr>
                        <th>내용</th>
                        <td colspan="3" class="con"><?php /*echo nl2br($view["wr_content"]);*/?></td>
                    </tr>
                    </tbody>-->
                </table>
            </div>
            <div class="submit_gr no_print">
                <a href="<?php echo G5_URL."/admin/member_company_list.php?page=".$page."&sfl=".$sfl."&stx=".$stx."&order=".$order."&desc=".$desc; ?>" class="adm-btn01">목록</a>
                <?php if($view["status"]==0){?>
                <a href="<?php echo G5_URL."/admin/member_company_update.php?page=".$page."&sfl=".$sfl."&stx=".$stx."&order=".$order."&desc=".$desc."&cp_id=".$cp_id."&status=1"; ?>" class="adm-btn01">승인</a>
                <?php }else if($view["status"]==1){?>
                <a href="<?php echo G5_URL."/admin/member_company_update.php?page=".$page."&sfl=".$sfl."&stx=".$stx."&order=".$order."&desc=".$desc."&cp_id=".$cp_id."&status=0"; ?>" class="adm-btn01">승인취소</a>
                <?php }?>
            </div>
        </article>
    </section>
</div>
<?php
include_once(G5_PATH."/admin/admin.tail.php");
?>
