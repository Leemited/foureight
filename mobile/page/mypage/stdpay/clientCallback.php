<?php
include_once ("../../../../common.php");

include_once (G5_MOBILE_PATH."/head.login.php");

if($_REQUEST["rstCode"]=='00') {
    $sql = "select * from `order_temp` where pay_oid = '{$_REQUEST["oid"]}'";
    $res = sql_query($sql);

    while ($row = sql_fetch_array($res)) {
        if ($payKind == 2) {
            $od_pay_status = 2;
            $set = ", vAccountTradeNo = '{$_REQUEST["vAccountTradeNo"]}', vAccount = '{$_REQUEST["vAccount"]}', vAccountBankName = '{$_REQUEST['vAccountBankName']}' , vAccountDate = '{$_REQUEST['vCriticalDate']}'";
        } else {
            $od_pay_status = 1;
        }
        $sql = "insert into `order` set 
                      cid='{$row["cid"]}',
                      pd_id='{$row["pd_id"]}',
                      mb_id='{$row["mb_id"]}',
                      pd_price='{$row["pd_price"]}',
                      od_price='{$row["od_price"]}',
                      od_status='{$row["od_status"]}',
                      od_name='{$row["od_name"]}',
                      od_tel='{$row["od_tel"]}',
                      od_zipcode='{$row["od_zipcode"]}',
                      od_addr1='{$row["od_addr1"]}',
                      od_addr2='{$row["od_addr2"]}',
                      od_content='{$row["od_content"]}',
                      od_pay_type='{$row["od_pay_type"]}',
                      od_pay_status='2', 
                      od_date=now(),
                      od_pd_type='{$row[""]}',
                      od_step='{$row["od_step"]}',
                      group_id='{$row["group_id"]}',
                      pay_oid = '{$row["pay_oid"]}'
                      {$set}
                    ";

        sql_query($sql);

        $sql = "update `cart` set c_status = 2 where cid = '{$row['cid']}' ";
        sql_query($sql);

        $sql = "update `cart` set pd_status =  where cid = '{$row['cid']}' ";
        sql_query($sql);
    }
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
    table th{padding:2vw;text-align: center;font-size:1em;background-color: #eee;font-weight:normal}
    table th{padding:2vw;text-align: left;font-size:1em;background-color:#fff;}
</style>
<div class="sub_head">
    <!--<div class="sub_back" onclick="location.href='<?php /*echo $back_url;*/?>'"><img src="<?php /*echo G5_IMG_URL*/?>/ic_menu_back.svg" alt=""></div>-->
    <h2>결제 완료</h2>
</div>

<div class="alert_list">
    <table>
        <colgroup>
            <col width="40%">
            <col width="*">
        </colgroup>
        <tr>
            <th>결과 메세지</th>
            <td><?php echo $rstMsg;?></td>
        </tr>
        <tr>
            <th>가맹점아이디</th>
            <td><?php echo $mbrId;?></td>
        </tr>
        <tr>
            <th>결제금액</th>
            <td><?php echo $salesPrice;?></td>
        </tr>
        <tr>
            <th>주문번호</th>
            <td><?php echo $oid;?></td>
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
        <?php if($payKind == "1"){
            $payType= $_REQUEST["payType"];                         // 결제 타입
            $authType= $_REQUEST["authType"];                       // 인증 타입
            $cardTradeNo= $_REQUEST["cardTradeNo"];                 // 카드 거래번호
            $cardApprovDate= $_REQUEST["cardApprovDate"];           // 카드 승인일
            $cardApprovTime= $_REQUEST["cardApprovTime"];           // 카드 승인시각
            $cardName= $_REQUEST["cardName"];                       // 카드명
            $cardCode= $_REQUEST["cardCode"];                       // 카드코드
            $installNo= $_REQUEST["installNo"];                     // 할부개월
        ?>
        <tr>
            <th>결제 타입</th>
            <td><?php echo $payType;?></td>
        </tr>
        <tr>
            <th>카드 거래번호</th>
            <td><?php echo $cardTradeNo;?></td>
        </tr>
        <tr>
            <th>카드 승인일</th>
            <td><?php echo $cardApprovDate;?></td>
        </tr>
        <tr>
            <th>카드 승인시각</th>
            <td><?php echo $cardApprovTime;?></td>
        </tr>
        <tr>
            <th>카드명</th>
            <td><?php echo $cardName;?></td>
        </tr>
        <tr>
            <th>카드코드</th>
            <td><?php echo $cardCode;?></td>
        </tr>
        <tr>
            <th>할부개월</th>
            <td><?php echo $installNo;?></td>
        </tr>
        <?php }else if($payKind == "2"){
                $vAccountTradeNo= $_REQUEST["vAccountTradeNo"];         // 가상계좌 거래번호
                $vAccount= $_REQUEST["vAccount"];                       // 가상계좌번호
                $vCriticalDate= $_REQUEST["vCriticalDate"];             // 입금마감일
                $vAccountBankName= $_REQUEST["vAccountBankName"];       // 거래은행명
                $vAccountBankCode= $_REQUEST["vAccountBankCode"];       // 거래은행코드
        ?>
            <tr>
                <th>가상계좌 거래번호</th>
                <td><?php echo $vAccountTradeNo;?></td>
            </tr>
            <tr>
                <th>가상계좌번호</th>
                <td><?php echo $vAccount;?></td>
            </tr>
            <tr>
                <th>입금마감일</th>
                <td><?php echo $vCriticalDate;?></td>
            </tr>
            <tr>
                <th>거래은행명</th>
                <td><?php echo $vAccountBankName;?></td>
            </tr>
        <?php }else if($payKind == "3"){
                $accountTradeNo= $_REQUEST["accountTradeNo"];           // 계좌이체 거래번호
                $accountApprov= $_REQUEST["accountApprov"];             // 계좌이체 승인번호
                $accountApprovDate= $_REQUEST["accountApprovDate"];     // 계좌이체 승인일
                $accountApprovTime= $_REQUEST["accountApprovTime"];     // 계좌이체 승인시각
                $accountBankName= $_REQUEST["accountBankName"];         // 거래은행명
                $accountBankCode= $_REQUEST["accountBankCode"];         // 거래은행코드
            ?>
            <tr>
                <th>계좌이체 거래번호</th>
                <td><?php echo $accountTradeNo;?></td>
            </tr>
            <tr>
                <th>계좌이체 승인번호</th>
                <td><?php echo $accountApprov;?></td>
            </tr>
            <tr>
                <th>계좌이체 승인일</th>
                <td><?php echo $accountApprovDate;?></td>
            </tr>
            <tr>
                <th>계좌이체 승인시각</th>
                <td><?php echo $accountApprovTime;?></td>
            </tr>
            <tr>
                <th>거래은행명</th>
                <td><?php echo $accountBankName;?></td>
            </tr>
        <?php }else if($payKind == "4"){
                $mobileTradeNo= $_REQUEST["mobileTradeNo"];             // 휴대폰 거래번호
                $mobileApprovDate= $_REQUEST["mobileApprovDate"];       // 휴대폰 승인일
                $mobileApprovTime= $_REQUEST["mobileApprovTime"];       // 휴대폰 승인시각
                $BILLTYPE= $_REQUEST["BILLTYPE"];                       // 월자동결제 여부

        ?>
            <tr>
                <th>휴대폰 거래번호</th>
                <td><?php echo $mobileTradeNo;?></td>
            </tr>
            <tr>
                <th>휴대폰 승인일</th>
                <td><?php echo $mobileApprovDate;?></td>
            </tr>
            <tr>
                <th>휴대폰 승인시각</th>
                <td><?php echo $mobileApprovTime;?></td>
            </tr>
        <?php }?>
    </table>
<?php
	//echo("<pre>");
	/*echo("<table width='565' style='width:100%;' align = 'center' border='0' cellspacing='0' cellpadding='0' bgcolor='#FFFFFF'>");
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
        <input type="button" value="결제 확인" class="input_btn" onclick="location.reload();" style="width:50%;background-color:#595959;color:#fff;-webkit-border-radius: 10vw;-moz-border-radius: 10vw;border-radius: 10vw;font-size:3.5vw;padding:2.2vw 0;border:none;font-family: 'nsr', sans-serif;">
    </div>
</div>
<?php
include_once (G5_PATH."/tail.php");
?>
