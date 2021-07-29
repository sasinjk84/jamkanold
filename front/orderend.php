<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/shopdata.php");

$ordercode=$_POST["ordercode"];

if(substr($ordercode,0,8)<=date("Ymd",mktime(0,0,0,date("m"),date("d")-3,date("Y")))) {
	echo "<html></head><body onload=\"alert('잘못된 경로로 접근하셨습니다.(0)'); location.href='".$Dir."'\"></body></html>";
	exit;
}

$sql = "SELECT * FROM tblorderinfo WHERE ordercode='".$ordercode."' ";
$result=mysql_query($sql,get_db_conn());
if($row=mysql_fetch_object($result)) {
	$_ord=$row;
	$gift_price=$_ord->price-$row->deli_price;

	$receiver_addr = explode('주소 : ',$_ord->receiver_addr);
	$zipCode  = explode('우편번호 : ',$receiver_addr[0]);

	$sql = "select mobile from tblmember where id='".$_ord->id."'";
	$resultm=mysql_query($sql,get_db_conn());
	if($rowm=mysql_fetch_object($resultm)) {
		if (strlen($rowm->mobile)>0) $mobile = $rowm->mobile;
		$mobile=explode("-",replace_tel(check_num($mobile)));

	}

	$sql_="select distinct(vender) vender from tblorderproduct where ordercode='".$ordercode."' ";
	$result_=mysql_query($sql_,get_db_conn());
	$venderCnt = mysql_num_rows($result_);

	/*복합 스토어인 경우*/
	if($venderCnt>1){ 
		$booking_vender_cnt=0;
		while($row_=mysql_fetch_object($result_)){
			$vender_sql="select * from tblvenderinfo where vender='".$row_->vender."'";
			$vender_res=mysql_query($vender_sql,get_db_conn());
			$vender_row=mysql_fetch_object($vender_res);
			if($vender_row->booking_confirm=="now"){
				$booking_vender_cnt++;
			}
		}
		if($booking_vender_cnt==$venderCnt){ //모두 즉시 확정인 경우
			$msg = "모든 스토어의 예약이 확정되었습니다.";
		}else{
			$msg = "스토어별 예약 확정 조건이 다릅니다.<br>예약확정시간이 지나도 알림이 안 올 경우 각 스토어로 연락 바랍니다.<br>대여가 안되는 경우 자동으로 결제가 취소됩니다.";
		}
	/*단일 스토어인 경우*/
	}else{
		$row_=mysql_fetch_object($result_);
		$vender_sql="select * from tblvenderinfo where vender='".$row_->vender."'";
		$vender_res=mysql_query($vender_sql,get_db_conn());
		$booking_vender = mysql_fetch_object($vender_res);

		$op_sql="select * from tblorderproduct where ordercode='".$ordercode."' AND NOT (productcode LIKE 'COU%' OR productcode LIKE '999999%')";
		$op_res=mysql_query($op_sql,get_db_conn());
		$opCnt = mysql_num_rows($op_res);

		while($op_row=mysql_fetch_object($op_res)){
			$prd_sql="select * from tblproduct where productcode='".$op_row->productcode."' ";
			$prd_res=mysql_query($prd_sql,get_db_conn());
			$prd_row=mysql_fetch_object($prd_res);

			if($prd_row->booking_confirm){//상품설정이 있는 경우
				if($prd_row->booking_confirm=="now"){ //즉시 확정인 경우
					if($opCnt>1){
						$msg .= "[".$prd_row->productname."] 상품의 예약이 확정되었습니다. ";
					}else{
						$msg = "예약이 확정되었습니다.";
					}
				}else{
					$total_sql="select count(*) as totalcnt from tblorderinfo o left join tblorderproduct op on o.ordercode=op.ordercode where op.productcode='".$prd_row->productcode."'";
					$total_res=mysql_query($total_sql,get_db_conn());
					$total_order_cnt = mysql_num_rows($total_res);
					$total_row=mysql_fetch_object($total_res);

					$arrconfirmTime = explode(":",$prd_row->booking_confirm);
					
					if($arrconfirmTime[0]=="00"){
						$confirmTime = $arrconfirmTime[1]."분";
						$confirm_sql="select count(*) as cnt from tblorderinfo o left join tblorderproduct op on o.ordercode=op.ordercode where op.productcode='".$prd_row->productcode."' and timestampdiff(minute,bank_date,prd_status_date)<=".$arrconfirmTime[1];
					}else{
						$confirmTime = $arrconfirmTime[0]."시간";
						$confirm_sql="select count(*) as cnt from tblorderinfo o left join tblorderproduct op on o.ordercode=op.ordercode where op.productcode='".$prd_row->productcode."' and timestampdiff(hour,bank_date,prd_status_date)<=".$arrconfirmTime[0];
					}
					$confirm_res=mysql_query($confirm_sql,get_db_conn());
					$confirm_row=mysql_fetch_object($confirm_res);
					
					if($total_row->totalcnt>0){
						$bookingper = round(($confirm_row->cnt/$total_row->totalcnt) * 100,1);
					}else{
						$bookingper = 99;
					}
/*
					$total_sql="select count(*) as totalcnt from tblorderinfo o left join tblorderproduct op on o.ordercode=op.ordercode where op.productcode='".$prd_row->productcode."'";
					$total_res=mysql_query($total_sql,get_db_conn());
					$total_order_cnt = mysql_num_rows($total_res);
					$total_row=mysql_fetch_object($total_res);

					$confirm_sql="select count(*) as cnt from tblorderinfo o left join tblorderproduct op on o.ordercode=op.ordercode where op.productcode='".$prd_row->productcode."' and timestampdiff(minute,bank_date,prd_status_date)<=".$prd_row->booking_confirm;
					$confirm_res=mysql_query($confirm_sql,get_db_conn());
					$confirm_row=mysql_fetch_object($confirm_res);
					
					if($total_order_cnt>0){
						$bookingper = round(($confirm_row->cnt/$total_row->totalcnt) * 100,1);
					}else{
						$bookingper = 99;
					}
					*/
					
					if($opCnt>1){
						$msg .= "[".$prd_row->productname."] 상품은 예약확정까지 약 ".$confirmTime."(영업시간 기준)이 소요되며, 평균 ".$bookingper."% 예약이 확정됩니다.<br>";
					}else{
						$msg = "이 스토어는 예약확정까지 약 ".$confirmTime."(영업시간 기준)이 소요되며, 평균 ".$bookingper."% 예약이 확정됩니다.<br> 확정 예상 시간이 지나도 알림이 안 올 경우 (".$booking_vender->com_tel.")으로 연락 바랍니다.<br> 예약 확정이 안되는 경우 자동으로 결제가 취소됩니다.";
					}
				}
			}else{//상품설정이 없는 경우 : 상점설정을 가져옴
				if($booking_vender->booking_confirm=="now"){ //즉시 확정인 경우
					if($opCnt>1){
						$msg .= "[".$prd_row->productname."] 상품의 예약이 확정되었습니다. ";
					}else{
						$msg = "예약이 확정되었습니다.";
					}
				}else{
					$total_sql="select count(*) as totalcnt from tblorderinfo o left join tblorderproduct op on o.ordercode=op.ordercode where op.vender='".$row_->vender."'";
					$total_res=mysql_query($total_sql,get_db_conn());
					$total_order_cnt = mysql_num_rows($total_res);
					$total_row=mysql_fetch_object($total_res);


					if($venderinfo['booking_confirm']=="now"){
						$msg =  "결제 즉시 예약 확정 스토어";
					}else{
						$msg =  "결제 후 ";
						$arrconfirmTime = explode(":",$booking_vender->booking_confirm);
						if($arrconfirmTime[0]=="00"){
							$confirmTime = $arrconfirmTime[1]."분";

							$confirm_sql="select count(*) as cnt from tblorderinfo o left join tblorderproduct op on o.ordercode=op.ordercode where op.vender='".$row_->vender."' and timestampdiff(minute,bank_date,prd_status_date)<=".$arrconfirmTime[1];
						}else{
							$confirmTime = $arrconfirmTime[0]."시간";

							$confirm_sql="select count(*) as cnt from tblorderinfo o left join tblorderproduct op on o.ordercode=op.ordercode where op.vender='".$row_->vender."' and timestampdiff(hour,bank_date,prd_status_date)<=".$arrconfirmTime[0];
						}

						$confirm_res=mysql_query($confirm_sql,get_db_conn());
						$confirm_row=mysql_fetch_object($confirm_res);
						
						if($total_row->totalcnt>0){
							$bookingper = round(($confirm_row->cnt/$total_row->totalcnt) * 100,1);
						}else{
							$bookingper = 99;
						}

						if($opCnt>1){
							$msg .= "[".$prd_row->productname."] 상품은 예약확정까지 약 ".$confirmTime."(영업시간 기준)이 소요되며, 평균 ".$bookingper."% 예약이 확정됩니다.<br>";
						}else{
							$msg = "이 스토어는 예약확정까지 약 ".$confirmTime."(영업시간 기준)이 소요되며, 평균 ".$bookingper."% 예약이 확정됩니다.<br> 확정 예상 시간이 지나도 알림이 안 올 경우 (".$booking_vender->com_tel.")으로 연락 바랍니다.<br> 예약 확정이 안되는 경우 자동으로 결제가 취소됩니다.";
						}
					}

/*
					$confirm_sql="select count(*) as cnt from tblorderinfo o left join tblorderproduct op on o.ordercode=op.ordercode where op.vender='".$row_->vender."' and timestampdiff(minute,bank_date,prd_status_date)<=".$booking_vender->booking_confirm;
					$confirm_res=mysql_query($confirm_sql,get_db_conn());
					$confirm_row=mysql_fetch_object($confirm_res);
					
					if($total_order_cnt>0){
						$bookingper = round(($confirm_row->cnt/$total_row->totalcnt) * 100,1);
					}else{
						$bookingper = 99;
					}
					
					if($opCnt>1){
						$msg .= "[".$prd_row->productname."] 상품은 예약확정까지 약 ".$confirmTime."(영업시간 기준)이 소요되며, 평균 ".$bookingper."% 예약이 확정됩니다.<br>";
					}else{
						$msg = "이 스토어는 예약확정까지 약 ".$confirmTime."(영업시간 기준)이 소요되며, 평균 ".$bookingper."% 예약이 확정됩니다.<br> 확정 예상 시간이 지나도 알림이 안 올 경우 (".$booking_vender->com_tel.")으로 연락 바랍니다.<br> 예약 확정이 안되는 경우 자동으로 결제가 취소됩니다.";
					}
					*/
				}
			}//end if
		}//end while

		if($opCnt>1){
			$msg .= "<br>확정 예상 시간이 지나도 알림이 안 올 경우 (".$booking_vender->com_tel.")으로 연락 바랍니다.<br> 예약 확정이 안되는 경우 자동으로 결제가 취소됩니다.";
		}
	}
	
} else {
	echo "<html></head><body onload=\"alert('잘못된 경로로 접근하셨습니다.(1)'); location.href='/'\"></body></html>";
	exit;
}
mysql_free_result($result);

if (preg_match("/^(V|O|Q|C|P|M)$/", $_ord->paymethod) && $_ord->deli_gbn=="C") {
	$_ord->pay_data = "결제 중 주문취소";
}

$gift_type=explode("|",$_data->gift_type);
$gift_cnt=0;
if (($_ord->paymethod=="B" || (preg_match("/^(V|O|Q|C|P|M){1}/", $_ord->paymethod) && strcmp($_ord->pay_flag,"0000")==0)) && $_ord->deli_gbn=="N" && strlen($_ShopInfo->getGifttempkey())>0) {
	if ($gift_type[2]=="A" || strlen($gift_type[2])==0 || ($gift_type[2]=="B" && $_ord->paymethod=="B")) {
		if (($gift_type[0]=="M" && strlen($_ShopInfo->getMemid())>0) || $gift_type[0]=="C") { // 회원전용, 비회원+회원
			$sql = "SELECT COUNT(*) as gift_cnt FROM tblgiftinfo ";
			if($gift_type[1]=="N") {
				$sql.= "WHERE gift_startprice<=".$gift_price." AND gift_endprice>".$gift_price." ";
			} else  {
				$sql.= "WHERE gift_startprice<=".$gift_price." ";
			}
			$sql.= "AND (gift_quantity is NULL OR gift_quantity>0) ";
			$result=mysql_query($sql,get_db_conn());
			$row=mysql_fetch_object($result);
			$gift_cnt=$row->gift_cnt;
			mysql_free_result($result);
		}
	}
}
$gift_cnt=0;

?>

<HTML>
<HEAD>
<TITLE><?=$_data->shoptitle?> - 주문완료</TITLE>
<META http-equiv="CONTENT-TYPE" content="text/html; charset=EUC-KR">
<META http-equiv="X-UA-Compatible" content="IE=Edge" />

<META name="description" content="<?=(strlen($_data->shopdescription)>0?$_data->shopdescription:$_data->shoptitle)?>">
<META name="keywords" content="<?=$_data->shopkeyword?>">
<script type="text/javascript" src="<?=$Dir?>lib/lib.js.php"></script>

<?include($Dir."lib/style.php")?>
<link href="<?=$Dir?>css/endod_style.css" rel='stylesheet' type='text/css' />

<script src="/js/jquery-ui-1.11.4/external/jquery/jquery.js"></script>
<script src="/js/jquery-ui-1.11.4/jquery-ui.js"></script>

<script language="javascript" type="text/javascript">
var $j = jQuery.noConflict();
</script>
<script language="javascript" type="text/javascript" src="/js/jquery.bpopup.min.js"></script>
<link href="/js/jquery-ui-1.11.4/jquery-ui.css" rel="stylesheet">

<SCRIPT LANGUAGE="JavaScript">
<!--
function OrderDetailPrint(ordercode) {
	document.form2.ordercode.value=ordercode;
	document.form2.print.value="OK";
	window.open("about:blank","orderpop","width=610,height=500,scrollbars=yes");
	document.form2.submit();
}

function setPackageShow(packageid) {
	if(packageid.length>0 && document.getElementById(packageid)) {
		if(document.getElementById(packageid).style.display=="none") {
			document.getElementById(packageid).style.display="";
		} else {
			document.getElementById(packageid).style.display="none";
		}
	}
}

$j(document).ready(function(){
	$j('#booking_confirm').bPopup({
		closeClass:'closeBtn',
		content:'ajax', //'ajax', 'iframe' or 'image'
        contentContainer:'.bookingConfirm'
        //loadUrl:'/front/wishPopup.php?opti='+opti
	});
});
//-->
</SCRIPT>
</HEAD>

<body<?=(substr($_data->layoutdata["MOUSEKEY"],0,1)=="Y"?" oncontextmenu=\"return false;\"":"")?><?=(substr($_data->layoutdata["MOUSEKEY"],1,1)=="Y"?" ondragstart=\"return false;\" onselectstart=\"return false;\"":"")?> leftmargin="0" marginwidth="0" topmargin="0" marginheight="0"><?=(substr($_data->layoutdata["MOUSEKEY"],2,1)=="Y"?"<meta http-equiv=\"ImageToolbar\" content=\"No\">":"")?>
<? /*<map id="endod_top" >
<area shape="rect" coords="761,121,909,155" href="#" />
<area shape="rect" coords="611,121,759,155" href="#" />
<area shape="rect" coords="461,121,609,155" href="#" />
</map> */ ?>
<?
if(substr($_data->design_order,0,1)=="T") {
	$_data->menu_type="nomenu";

}
include ($Dir.MainDir.$_data->menu_type.".php");
?>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr>
<?

if ($_data->title_type=="Y" && file_exists($Dir.DataDir."design/orderend_title.gif")) {
	echo "<td><img src=\"".$Dir.DataDir."design/orderend_title.gif\" border=\"0\" alt=\"주문완료\"></td>\n";
} else {
	echo "<td>\n";
	/*
	echo "<TABLE WIDTH=100% BORDER=0 CELLPADDING=0 CELLSPACING=0>\n";
	echo "<TR>\n";
	echo "	<TD><IMG SRC=".$Dir."images/".$_data->icon_type."/orderend_title_head.jpg usemap='#endod_top' ></TD>\n";
	echo "</TR>\n";
	echo "</TABLE>\n";*/
	echo "</td>\n";
}
?>
</tr>
<tr>
	<td align="center"><?
		//echo $Dir.TempletDir."orderend/orderend".$_data->design_order.".php";
		include ($Dir.TempletDir."orderend/orderend".$_data->design_order.".php");
	?></td>
</tr>
<? if($gift_cnt>0) {?>
<tr>
	<td align="center">
	<div id="gift_layer" style="position:absolute; width:381; height:228; z-index:1; visibility: hidden">
	<table border=0 cellpadding=0 cellspacing=0 width=381 height=228 background="">
	<tr>
		<td><img src="<?=$Dir?>images/common/gift_choicebg.gif" border="0" USEMAP="#gifimage"></td>
	</tr>
	</table>
	<MAP NAME="gifimage">
	<AREA SHAPE="rect" COORDS="332,12,377,27" HREF="javascript:gift_close();">
	<AREA SHAPE="rect" COORDS="229,179,324,207" HREF="javascript:getGift();">
	</MAP>
	</div>
	</td>
</tr>
<tr><td height="20"></td></tr>

<form name=giftform method=post action="<?=$Dir.FrontDir?>gift_choice.php" target="gift_popwin">
<input type=hidden name=gift_price value="<?=$gift_price?>">
<input type=hidden name=ordercode value="<?=$ordercode?>">
</form>

<SCRIPT LANGUAGE="JavaScript">
<!--
function gift_show() {
	gift_layer.style.posLeft=screen.availWidth/2-190;
	gift_layer.style.posTop=screen.availHeight/2-90;
	gift_layer.style.visibility="visible";
}
function gift_close() {
	gift_layer.style.visibility="hidden";
}
function getGift() {
	gift_close();
	gift_popwin = window.open("about:blank","gift_popwin","width=700,height=600,scrollbars=yes");
	if (!gift_popwin) gift_show();
	document.giftform.target="gift_popwin";
	document.giftform.submit();
	gift_popwin.focus();
}
getGift();
//-->
</SCRIPT>
<?}?>
</table>



<form name=form2 method=post action="<?=$Dir.FrontDir?>orderdetailpop.php" target="orderpop">
<input type=hidden name=ordercode>
<input type=hidden name=print>
</form>

<?=$onload?>

<? include ($Dir."lib/bottom.php") ?>

<div id="booking_confirm" class="bookingConfirm" style="display:none;">
	<div class="searchPw popwin">
		<div class="spw_wrap">
			<p class="tit">예약 안내</p>
			<div class="spwform">
				<?=$msg?>
				<p id="okDiv" style="width:100%;text-align:center;margin:0px auto;">
					<input type="button" value="확인" class="btn_line btn_close closeBtn"> 
				</p>
			</div>
		</div>
	</div>	
</div>

</BODY>
</HTML>