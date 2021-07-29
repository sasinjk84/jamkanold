<?php
/**
 * Created by PhpStorm.
 * User: x2chi-objet
 * Date: 2014-11-27
 * Time: 오후 1:34
 */
$Dir = "../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");

extract($_GET);
extract($_POST);

if( empty($pridx) ) {
	echo "상품정보 누락";
	exit;
}
if( strlen($_ShopInfo->getTempkey()) < 10 ) {
	echo "장바구니 정보 누락";
	exit;
}

$basket = basketTable($ordertype);

$productcode = mysql_fetch_assoc( mysql_query("SELECT productcode FROM tblproduct WHERE pridx = '".$pridx."' ") );
$productcode = $productcode[productcode];

$tempkeyToIdx = mysql_fetch_assoc( mysql_query("SELECT basketidx FROM ".$basket." WHERE tempkey = '".$_ShopInfo->getTempkey()."' AND productcode = '".$productcode."' ") );
$tempkeyToIdx = $tempkeyToIdx['basketidx'];

$rentInfo = rentBasketInfo ( $tempkeyToIdx, $basket );
// 옵션
$optionCnt = array();
if ( _array($rentInfo) ) {
	$optA = explode("|",$rentInfo['options']);
	foreach ( $optA as $v ) {
		if (strlen($v) > 0) {
			$optB = explode(":", $v);
			$optionCnt[$optB[0]] = $optB[1];
		}
	}
}
$rentStartDate = ( empty($rentInfo['sdate']) ? date("Ymd") : str_replace("-","",$rentInfo['sdate']) ); // 대여 시작일
$rentEndDate = ( empty($rentInfo['edate']) ? date("Ymd") : str_replace("-","",$rentInfo['edate']) ); // 대여 종료일

if( $mode == "save" ) {

	if( rentOrderChecker ( $pridx, $p_bookingStartDate, $p_bookingEndDate, $rentOptionList ) == "pass" ) {
		$ok = rentBasketSave ( $_ShopInfo->getTempkey(), $basket, $productcode, $p_bookingStartDate, $p_bookingEndDate, $rentOptionList,( _array($rentInfo) ? "update" : "" ));
		if( $ok == "ok" ) {
			echo "<html><body onload=\"opener.location.href='/front/basket.php?ordertype=".$ordertype."'; self.close();\" </html>";
			exit;
		}
	} else {
		echo "<script>alert('대여 일정과 재고수량을 확인 하시기 바랍니다.\\n예약현황을 확인하세요!'); </script>";
	}
}

?>

<script type="text/javascript" src="../lib/lib.js.php"></script>
<script type="text/javascript" src="../js/rental.js"></script>
<script language="javascript" type="text/javascript" src="../js/miniCalendar.js"></script>
<script type="text/javascript">
	function rentOptChgSubmit ( f ) {
		priceCalc(f);
		if( f.rentOptionList.value.length > 3 ) {
			f.method = "POST";
			f.mode.value = "save";
			f.submit();
		} else {
			alert("옵션 개수를 입력하세요!");
		}
	}
</script>

<form name="rentOptChg">
	<table border="1" cellpadding="0" cellspacing="0" width="100%">
		<tr align='center'>
			<td>옵션명</td>
			<td>상태</td>
			<td>가격</td>
			<td>개수</td>
		</tr>
		<?
		$listSQL = "SELECT * FROM rent_product_option WHERE pridx = ".$pridx;
		$listRES = mysql_query( $listSQL, get_db_conn());
		while ( $listROW = mysql_fetch_assoc($listRES) ) {
			$optionCnt[$listROW['idx']] = ( $optionCnt[$listROW['idx']] > 0 ? $optionCnt[$listROW['idx']] : 0 );
		?>
			<tr align='center'>
				<td><?=$listROW['optionName']?></td>
				<td><?=$goodStatusArray[$listROW['grade']]?></td>
				<td>
					<table width="100%" border="0" cellpadding="0" cellspacing="0" >
						<tr>
							<td>일반가</td>
							<td><?=number_format($listROW['nomalPrice'])?></td>
						</tr>
						<tr>
							<td>성수기</td>
							<td> <?=number_format($listROW['nomalPrice']+$listROW['busySeason'])?></td>
						</tr>
						<tr>
							<td>준성수기</td>
							<td><?=number_format($listROW['nomalPrice']+$listROW['semiBusySeason'])?></td>
						</tr>
						<tr>
							<td>주말가</td>
							<td><?=number_format($listROW['nomalPrice']+$listROW['holidaySeason'])?></td>
						</tr>
					</table>
				</td>
				<td width='40'>
					<input type='text' value='<?=$optionCnt[$listROW['idx']]?>' style='width=30px;' name='rentOptions' idxcode="<?=$listROW['idx']?>">개
				</td>
			</tr>
		<?
		}
		?>
	</table>
	<input type='hidden' value='' name='rentOptionList'>
	<input type='hidden' value='<?=$rentInfo['options']?>' name='orgOptionList'>

	<br />

	출고일:
	<span id="p_bookingStartDateCal" style="position:absolute;display:none;border:1px solid #d9d9d9;padding:3px;background-color: #FFFFFF;"></span>
	<img src="/images/mini_cal_calen.gif" style="cursor:pointer;" onclick="p_bookingStartDateCal.style.display=(p_bookingStartDateCal.style.display=='none' ? 'block' : 'none' );" align="absmiddle">
	<input type="text" name="p_bookingStartDate" id="p_bookingStartDate" value="<?=$rentStartDate?>" style="width:80px;" onclick="p_bookingStartDateCal.style.display=(p_bookingStartDateCal.style.display=='none' ? 'block' : 'none' );" readonly>
	<br />
	반납일 :
	<span id="p_bookingEndDateCal" style="position:absolute;display:none;border:1px solid #d9d9d9;padding:3px;background-color: #FFFFFF;"></span>
	<img src="/images/mini_cal_calen.gif" style="cursor:pointer;" onclick="p_bookingEndDateCal.style.display=(p_bookingEndDateCal.style.display=='none' ? 'block' : 'none' );" align="absmiddle">
	<input type="text" name="p_bookingEndDate" id="p_bookingEndDate" value="<?=$rentEndDate?>" style="width:80px;" onclick="p_bookingEndDateCal.style.display=(p_bookingEndDateCal.style.display=='none' ? 'block' : 'none' );" readonly>
	<script>
		show_cal('<?=$rentStartDate?>','p_bookingStartDateCal','p_bookingStartDate','todayNext');
		show_cal('<?=$rentEndDate?>','p_bookingEndDateCal','p_bookingEndDate','todayNext');
	</script>
	<br />
	<input type='button' onclick="bookingSchedulePop(<?=$pridx?>);" value="출고현황보기">
	<br />
	<input type='button' onclick="bookingPriceCalendalPop();" value="시즌적용달력">

	<br />
	<input type="hidden" name="pridx" value="<?=$pridx?>">
	<input type="button" value="계산하기" onclick="priceCalc(this.form);">
	<div id="priceCalcPrint" style="padding-left:40px;color:#ec2f36;"></div>

	<br />
	<input type="button" value="저장하기" onclick="rentOptChgSubmit(this.form);">
	<input type="button" value="닫기" onclick="self.close();">

	<input type="hidden" value="" name="mode">
</form>