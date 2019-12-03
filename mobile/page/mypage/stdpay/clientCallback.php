<?php
include_once ("../../../../common.php");
include_once (G5_MOBILE_PATH."/head.login.php");
if($_REQUEST["rstCode"]=='00') {
    $od_id = array_pop(explode("_",$_REQUEST["oid"]));
    $sql = "select *,o.mb_id as mb_id,p.mb_id as pd_mb_id,p.pd_id as pd_id from `order` as o left join `product` as p on o.pd_id = p.pd_id where o.od_id = '{$od_id}'";
    $pro = sql_fetch($sql);
    $app_mb_id = $pro["pd_mb_id"];

    if($pro["pay_oid"] && $pro["pd_type"]==2 && $pro["od_step"]==1){ //2차결제
        $sql = "update `order` set 
                  pay_oid2 = '{$_REQUEST["pay_oid"]}',
                  od_step = 2,
                  od_step2_price = '{$salesPrice}'
                  /*od_fin_status = 1,
                  od_fin_datetime = now()*
                  where od_id = '{$od_id}'
                ";
        sql_query($sql);

        $pd_mb = get_member($pro["pd_mb_id"]);
        //판매자에게 알림
        if ($pd_mb["regid"]) {
            $img = "";
            if ($pro["pd_images"]) {
                $imgs = explode(",", $pro["pd_images"]);
                $img = G5_DATA_URL . "/product/" . $imgs[0];
            }
            send_FCM($pd_mb["regid"], "48 결제 완료 알림", $pro["pd_tag"] . "의 잔금결제가 완료 되었습니다.", G5_MOBILE_URL . '/page/mypage/mypage_order.php?type=2&od_cate=1&pd_type=' . $pro["pd_type"], 'fcm_buy_channel', '구매일림', $pd_mb["mb_id"], $pro['pd_id'], $img);
        }
    }
    if($pro["pay_oid"]=="" && $pro["od_step"]==0){ //1차결제
        //주문상태 업데이트
        $sql = "update `order` set 
                  od_pay_type='{$_REQUEST["payKind"]}',
                  od_pay_status='1', 
                  od_date=now(),
                  od_step=1,
                  pay_oid = '{$_REQUEST["oid"]}'
                  where od_id = '{$od_id}'
                ";

        sql_query($sql);

        if ($pro["od_pd_type"] == 1) {
            //물건일 경우 판매 완료 처리
            $sql = "update `product` set pd_status = 10 where pd_id = '{$pro["pd_id"]}'";
            sql_query($sql);

        } else {
            //능력일경우
            //카트 상태 업데이트
            /*$sql = "update `cart` set c_status = 1 where cid = '{$row['cid']}' ";
            sql_query($sql);*/
            if ($pro["pd_price2"]) {//계약금이 있는 경우
                $sql = "update `order` set od_step = 1, od_step1_price = '{$od["od_price"]}' where od_id = '{$od_id}'";
                sql_query($sql);
            } else {//계약금이 없는 경우
                $sql = "update `order` set od_step = 2 where od_id = '{$od_id}'";
                sql_query($sql);
            }
        }
        $pd_mb = get_member($pro["pd_mb_id"]);
        //판매자에게 알림
        if ($pd_mb["regid"]) {
            $img = "";
            if ($pro["pd_images"]) {
                $imgs = explode(",", $pro["pd_images"]);
                $img = G5_DATA_URL . "/product/" . $imgs[0];
            }
            send_FCM($pd_mb["regid"], "48 결제 완료 알림", $pro["pd_tag"] . "의 결제가 완료 되었습니다.", G5_MOBILE_URL . '/page/mypage/mypage_order.php?type=2&od_cate=1&pd_type=' . $pro["pd_type"], 'fcm_buy_channel', '구매일림', $pd_mb["mb_id"], $pro['pd_id'], $img);
        }
        //}
    }
}else{
    $od_id = array_pop(explode("_",$_SESSION["oid"]));
    $sql = "select *,o.mb_id as mb_id,p.mb_id as pd_mb_id,p.pd_id as pd_id from `order` as o left join `product` as p on o.pd_id = p.pd_id where o.od_id = '{$od_id}'";
    $pro = sql_fetch($sql);
    //set_session('od_temp_id','');
    //echo G5_MOBILE_URL."/page/mypage/orders.php?od_id={$od_id}&app_mb_id={$pro["mb_id"]}";
    echo "<script>location.replace('".G5_MOBILE_URL."/page/mypage/orders.php?od_id={$od_id}&app_mb_id={$pro["mb_id"]}')</script>";
}
// 공통
$mbrId=$_REQUEST["mbrId"];                                      // 가맹점아이디
$rstCode= $_REQUEST["rstCode"];                                 // 결과코드
$rstMsg= $_REQUEST["rstMsg"];                                   // 결과 메세지
$oid= $_REQUEST["oid"];                                         // 가맹점 주문번호
$payKind= $_REQUEST["payKind"];                                 // 결제종류[1:카드, 2:가상계좌, 3:계좌이체, 4:휴대폰]
$salesPrice=$_REQUEST["salesPrice"];                            // 결제 금액
?>
<style>
    table{width:100%;padding:2vw;}
    table th{padding:2vw;text-align: center;font-size:1em;background-color: #fff;font-weight:normal;width:20%}
    table td{padding:2vw;text-align: left;font-size:1em;background-color:#fff;width:80%}
</style>
<div class="sub_head">
    <!--<div class="sub_back" onclick="location.href='<?php /*echo $back_url;*/?>'"><img src="<?php /*echo G5_IMG_URL*/?>/ic_menu_back.svg" alt=""></div>-->
    <h2><?php if($_REQUEST["rstCode"]=='00') {?>결제 완료 정보<?php }else{?>결제 취소 정보<?php }?></h2>
</div>
<?php if($_REQUEST["rstCode"]=='00') {
$sql = "insert into `order_payment_info` set pay_oid = '{$oid}', pay_kind = '{$payKind}'";
sql_query($sql);
?>
<div class="alert_list" >
    <div class="list_con" style="height:auto;padding:0">
        <div class="product_img">
            <?php
            if($pro["pd_images"]!="") {
                $img = explode(",", $pro["pd_images"]);
                $img1 = get_images(G5_DATA_PATH . "/product/" . $img[0], '', '');
                if (is_file(G5_DATA_PATH . "/product/" . $img1)) {
                    $pro_img = G5_DATA_URL . "/product/" . $img1;
                } else {
                    $pro_img = '';
                }
            }else{
                $pro_img = '';
            }
            ?>
            <?php if($pro_img){?>
                <div style="background-image:url('<?php echo $pro_img;?>');width:40vw;height:40vw;margin:3vw auto;background-size:cover;background-position: center;background-repeat:no-repeat;display:block;position: relative;border:2px solid #fff;"></div>
            <?php }?>
            <h2 style="text-align: center;font-size:4vw;margin:3vw auto;"><?php echo $pro["pd_name"];?></h2>
        </div>
    </div>
    <table>
        <colgroup>
            <col width="40%">
            <col width="*">
        </colgroup>

        <!--<tr>
            <th>결과 메세지</th>
            <td><?php /*echo $rstMsg; */?></td>
        </tr>
        <tr>
            <th>가맹점아이디</th>
            <td><?php /*echo $mbrId; */?></td>
        </tr>-->
        <tr>
            <th>결제금액</th>
            <td><?php echo $salesPrice; ?></td>
        </tr>
        <tr>
            <th>주문번호</th>
            <td><?php echo $oid; ?></td>
        </tr>
    </table>
    <table>
        <colgroup>
            <col width="40%">
            <col width="*">
        </colgroup>
        <tr>
            <th colspan="2">결제정보</th>
        </tr>
        <?php if ($payKind == "1") {
            $payType = $_REQUEST["payType"];                         // 결제 타입
            $authType = $_REQUEST["authType"];                       // 인증 타입
            $cardTradeNo = $_REQUEST["cardTradeNo"];                 // 카드 거래번호
            $cardApprovDate = $_REQUEST["cardApprovDate"];           // 카드 승인일
            $cardApprovTime = $_REQUEST["cardApprovTime"];           // 카드 승인시각
            $cardName = $_REQUEST["cardName"];                       // 카드명
            $cardCode = $_REQUEST["cardCode"];                       // 카드코드
            $installNo = $_REQUEST["installNo"];                     // 할부개월

            $sql = "insert into `order_payment_card_info` set pay_oid = '{$oid}', payType = '{$payType}', authType = '{$authType}', cardTradeNo = '{$cardTradeNo}', cardApprovDate = '{$cardApprovDate}', cardApprovTime = '{$cardApprovTime}', cardName = '{$cardName}', cardCode = '{$cardCode}', installNo = '{$installNo}'";
            sql_query($sql);
            ?>
            <tr>
                <th>결제 타입</th>
                <td><?php echo $payType; ?></td>
            </tr>
            <tr>
                <th>카드 거래번호</th>
                <td><?php echo $cardTradeNo; ?></td>
            </tr>
            <tr>
                <th>카드 승인일</th>
                <td><?php echo $cardApprovDate; ?></td>
            </tr>
            <tr>
                <th>카드 승인시각</th>
                <td><?php echo $cardApprovTime; ?></td>
            </tr>
            <tr>
                <th>카드명</th>
                <td><?php echo $cardName; ?></td>
            </tr>
            <tr>
                <th>카드코드</th>
                <td><?php echo $cardCode; ?></td>
            </tr>
            <tr>
                <th>할부개월</th>
                <td><?php echo $installNo; ?></td>
            </tr>
        <?php } else if ($payKind == "2") {
            $vAccountTradeNo = $_REQUEST["vAccountTradeNo"];         // 가상계좌 거래번호
            $vAccount = $_REQUEST["vAccount"];                       // 가상계좌번호
            $vCriticalDate = $_REQUEST["vCriticalDate"];             // 입금마감일
            $vAccountBankName = $_REQUEST["vAccountBankName"];       // 거래은행명
            $vAccountBankCode = $_REQUEST["vAccountBankCode"];       // 거래은행코드
            ?>
            <tr>
                <th>가상계좌 거래번호</th>
                <td><?php echo $vAccountTradeNo; ?></td>
            </tr>
            <tr>
                <th>가상계좌번호</th>
                <td><?php echo $vAccount; ?></td>
            </tr>
            <tr>
                <th>입금마감일</th>
                <td><?php echo $vCriticalDate; ?></td>
            </tr>
            <tr>
                <th>거래은행명</th>
                <td><?php echo $vAccountBankName; ?></td>
            </tr>
        <?php } else if ($payKind == "3") {

            $accountTradeNo = $_GET["accountTradeNo"];           // 계좌이체 거래번호
            //$accountApprov = $_REQUEST["accountApprov"];             // 계좌이체 승인번호
            $accountApprovDate = $_GET["ApprovDate"];     // 계좌이체 승인일
            $accountApprovTime = $_GET["accountApprovTime"];     // 계좌이체 승인시각
            $accountBankName = urldecode($_GET["accountBankName"]);         // 거래은행명
            $accountBankCode = $_GET["accountBankCode"];         // 거래은행코드
            $sql = "insert into `order_payment_account_info` set pay_oid = '{$oid}', accountTradeNo = '{$accountTradeNo}', accountApprov = '{$accountApprov}', accountApprovDate = '{$accountApprovDate}', accountApprovTime = '{$accountApprovTime}', accountBankName = '{$accountBankName}', accountBankCode = '{$accountBankCode}'";
            sql_query($sql);
            ?>
            <tr>
                <th>계좌이체 거래번호</th>
                <td><?php echo $accountTradeNo; ?></td>
            </tr>
            <!--<tr>
                <th>계좌이체 승인번호</th>
                <td><?php /*echo $accountApprov; */?></td>
            </tr>-->
            <tr>
                <th>계좌이체 승인일</th>
                <td><?php echo $accountApprovDate; ?></td>
            </tr>
            <tr>
                <th>계좌이체 승인시각</th>
                <td><?php echo $accountApprovTime; ?></td>
            </tr>
            <tr>
                <th>거래은행명</th>
                <td><?php echo $accountBankName; ?></td>
            </tr>
        <?php } else if ($payKind == "4") {
            $mobileTradeNo = $_REQUEST["mobileTradeNo"];             // 휴대폰 거래번호
            $mobileApprovDate = $_REQUEST["mobileApprovDate"];       // 휴대폰 승인일
            $mobileApprovTime = $_REQUEST["mobileApprovTime"];       // 휴대폰 승인시각
            $BILLTYPE = $_REQUEST["BILLTYPE"];                       // 월자동결제 여부

            ?>
            <tr>
                <th>휴대폰 거래번호</th>
                <td><?php echo $mobileTradeNo; ?></td>
            </tr>
            <tr>
                <th>휴대폰 승인일</th>
                <td><?php echo $mobileApprovDate; ?></td>
            </tr>
            <tr>
                <th>휴대폰 승인시각</th>
                <td><?php echo $mobileApprovTime; ?></td>
            </tr>
        <?php } ?>
    </table>
<?php }else{?>

<?php }
	/*echo("<pre>");
	echo("<table width='565' style='width:100%;' align = 'center' border='0' cellspacing='0' cellpadding='0' bgcolor='#FFFFFF'>");
	echo "<colgroup><col width='30%'><col width='*'></colgroup>";
	echo("<tr><th class='td01'><p>결과코드</p></th>");
	echo("<td class='td02'><p>" . $rstCode . "</p></td></tr>");
	echo("<tr><th class='line' colspan='2'><p></p></th></tr>");
	echo("<tr><th class='td01'><p>결과메세지</p></th>");
	echo("<td class='td02'><p>" . $rstMsg . "</p></td></tr>");
	
	echo("<tr><th class='line' colspan='2'><p></p></th></tr>");
	echo("<tr><th class='td01'><p>가맹점아이디</p></th>");
	echo("<td class='td02'><p>" . $mbrId ."</p></td></tr>");

	echo("<tr><th class='line' colspan='2'><p></p></th></tr>");
	echo("<tr><th class='td01'><p>결제금액</p></th>");
	echo("<td class='td02'><p>" . $salesPrice . "원</p></td></tr>");
	echo("<tr><th class='line' colspan='2'><p></p></th></tr>");
	echo("<tr><th class='td01'><p>주문번호</p></th>");
	echo("<td class='td02'><p>" . $oid . "</p></td></tr>");

        // 신용카드
        if($payKind == "1"){
                $payType= $_REQUEST["payType"];                         // 결제 타입
                $authType= $_REQUEST["authType"];                       // 인증 타입
                $cardTradeNo= $_REQUEST["cardTradeNo"];                 // 카드 거래번호
                $cardApprovDate= $_REQUEST["cardApprovDate"];           // 카드 승인일
                $cardApprovTime= $_REQUEST["cardApprovTime"];           // 카드 승인시각
                $cardName= $_REQUEST["cardName"];                       // 카드명
                $cardCode= $_REQUEST["cardCode"];                       // 카드코드
                $installNo= $_REQUEST["installNo"];                     // 할부개월
		
		echo("<tr><th class='line' colspan='2'><p></p></th></tr>");
		echo("<tr><th class='td01'><p>결제타입</p></th>");
		echo("<td class='td02'><p>" . $payType . "</p></td></tr>");
		echo("<tr><th class='line' colspan='2'><p></p></th></tr>");
		echo("<tr><th class='td01'><p>거래번호</p></th>");
		echo("<td class='td02'><p>" . $cardTradeNo . "</p></td></tr>");
		echo("<tr><th class='line' colspan='2'><p></p></th></tr>");
		echo("<tr><th class='td01'><p>카드승인일</p></th>");
		echo("<td class='td02'><p>" . $cardApprovDate . "</p></td></tr>");
		echo("<tr><th class='line' colspan='2'><p></p></th></tr>");
		echo("<tr><th class='td01'><p>카드승인시각</p></th>");
		echo("<td class='td02'><p>" . $cardApprovTime . "</p></td></tr>");
		echo("<tr><th class='line' colspan='2'><p></p></th></tr>");
		echo("<tr><th class='td01'><p>카드명</p></th>");
		echo("<td class='td02'><p>" . $cardName . "</p></td></tr>");
		echo("<tr><th class='line' colspan='2'><p></p></th></tr>");
		echo("<tr><th class='td01'><p>카드코드</p></th>");
		echo("<td class='td02'><p>" . $cardCode . "</p></td></tr>");
		echo("<tr><th class='line' colspan='2'><p></p></th></tr>");
		echo("<tr><th class='td01'><p>할부개월</p></th>");
		echo("<td class='td02'><p>" . $installNo . "</p></td></tr>");

        // 가상계좌
        }else if($payKind == "2"){
                $vAccountTradeNo= $_REQUEST["vAccountTradeNo"];         // 가상계좌 거래번호
                $vAccount= $_REQUEST["vAccount"];                       // 가상계좌번호
                $vCriticalDate= $_REQUEST["vCriticalDate"];             // 입금마감일
                $vAccountBankName= $_REQUEST["vAccountBankName"];       // 거래은행명
                $vAccountBankCode= $_REQUEST["vAccountBankCode"];       // 거래은행코드

		echo("<tr><th class='line' colspan='2'><p></p></th></tr>");
		echo("<tr><th class='td01'><p>거래번호</p></th>");
		echo("<td class='td02'><p>" . $vAccountTradeNo . "</p></td></tr>");
		echo("<tr><th class='line' colspan='2'><p></p></th></tr>");
		echo("<tr><th class='td01'><p>가상계좌번호</p></th>");
		echo("<td class='td02'><p>" . $vAccount . "</p></td></tr>");
		echo("<tr><th class='line' colspan='2'><p></p></th></tr>");
		echo("<tr><th class='td01'><p>입금마감일</p></th>");
		echo("<td class='td02'><p>" . $vCriticalDate . "</p></td></tr>");
		echo("<tr><th class='line' colspan='2'><p></p></th></tr>");
		echo("<tr><th class='td01'><p>거래은행명</p></th>");
		echo("<td class='td02'><p>" . $vAccountBankName . "</p></td></tr>");
		echo("<tr><th class='line' colspan='2'><p></p></th></tr>");
		echo("<tr><th class='td01'><p>거래은행코드</p></th>");
		echo("<td class='td02'><p>" . $vAccountBankCode . "</p></td></tr>");

        // 계좌이체
        }else if($payKind == "3"){
                $accountTradeNo= $_REQUEST["accountTradeNo"];           // 계좌이체 거래번호
                $accountApprov= $_REQUEST["accountApprov"];             // 계좌이체 승인번호
                $accountApprovDate= $_REQUEST["accountApprovDate"];     // 계좌이체 승인일
                $accountApprovTime= $_REQUEST["accountApprovTime"];     // 계좌이체 승인시각
                $accountBankName= $_REQUEST["accountBankName"];         // 거래은행명
                $accountBankCode= $_REQUEST["accountBankCode"];         // 거래은행코드

		echo("<tr><th class='line' colspan='2'><p></p></th></tr>");
		echo("<tr><th class='td01'><p>거래번호</p></th>");
		echo("<td class='td02'><p>" . $accountTradeNo . "</p></td></tr>");
		echo("<tr><th class='line' colspan='2'><p></p></th></tr>");
		echo("<tr><th class='td01'><p>거래승인</p></th>");
		echo("<td class='td02'><p>" . $accountApprov . "</p></td></tr>");
		echo("<tr><th class='line' colspan='2'><p></p></th></tr>");
		echo("<tr><th class='td01'><p>승인일</p></th>");
		echo("<td class='td02'><p>" . $accountApprovDate . "</p></td></tr>");
		echo("<tr><th class='line' colspan='2'><p></p></th></tr>");
		echo("<tr><th class='td01'><p>승인시각</p></th>");
		echo("<td class='td02'><p>" . $accountApprovTime . "</p></td></tr>");
		echo("<tr><th class='line' colspan='2'><p></p></th></tr>");
		echo("<tr><th class='td01'><p>거래은행명</p></th>");
		echo("<td class='td02'><p>" . $accountBankName . "</p></td></tr>");
		echo("<tr><th class='line' colspan='2'><p></p></th></tr>");
		echo("<tr><th class='td01'><p>거래은행코드</p></th>");
		echo("<td class='td02'><p>" . $accountBankCode . "</p></td></tr>");
          
        // 휴대폰
        }else if($payKind == "4"){
                $mobileTradeNo= $_REQUEST["mobileTradeNo"];             // 휴대폰 거래번호
                $mobileApprovDate= $_REQUEST["mobileApprovDate"];       // 휴대폰 승인일
                $mobileApprovTime= $_REQUEST["mobileApprovTime"];       // 휴대폰 승인시각
                $BILLTYPE= $_REQUEST["BILLTYPE"];                       // 월자동결제 여부

		echo("<tr><th class='line' colspan='2'><p></p></th></tr>");
		echo("<tr><th class='td01'><p>거래번호</p></th>");
		echo("<td class='td02'><p>" . $mobileTradeNo . "</p></td></tr>");
		echo("<tr><th class='line' colspan='2'><p></p></th></tr>");
		echo("<tr><th class='td01'><p>승인일</p></th>");
		echo("<td class='td02'><p>" . $mobileApprovDate . "</p></td></tr>");
		echo("<tr><th class='line' colspan='2'><p></p></th></tr>");
		echo("<tr><th class='td01'><p>승인시각</p></th>");
		echo("<td class='td02'><p>" . $mobileApprovTime . "</p></td></tr>");
                
                // 휴대폰 월자동 결제 시
                if($BILLTYPE == "21"){
                        $BATCH_KEY= $_REQUEST["BATCH_KEY"];

			echo("<tr><th class='line' colspan='2'><p></p></th></tr>");
			echo("<tr><th class='td01'><p>자동결제 KEY</p></th>");
			echo("<td class='td02'><p>" . $BATCH_KEY . "</p></td></tr>");
                }
        }
        echo "</table>";*/
?>


    <div class="btns" style="width:100%;padding:6vw 0;background-color:#fff;text-align: center;position: absolute;bottom:0;left:0;">
        <input type="button" value="결제 확인" class="input_btn" onclick="location.replace(g5_url+'/mobile/page/mypage/mypage_order.php?od_cate=2&pd_type=<?php echo $pro["pd_type"];?>&od_id=<?php echo $od_id;?>&app_mb_id=<?php echo $pro["mb_id"];?>')" style="width:50%;background-color:#595959;color:#fff;-webkit-border-radius: 10vw;-moz-border-radius: 10vw;border-radius: 10vw;font-size:3.5vw;padding:2.2vw 0;border:none;font-family: 'nsr', sans-serif;">
    </div>
</div>
<?php
include_once (G5_PATH."/tail.php");
?>
