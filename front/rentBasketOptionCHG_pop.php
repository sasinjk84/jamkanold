<?php
/**
 * Created by PhpStorm.
 * User: x2chi-objet
 * Date: 2014-11-27
 * Time: ���� 1:34
 */
$Dir = "../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");

extract($_GET);
extract($_POST);

if( empty($pridx) ) {
	echo "��ǰ���� ����";
	exit;
}
if( strlen($_ShopInfo->getTempkey()) < 10 ) {
	echo "��ٱ��� ���� ����";
	exit;
}

$basket = basketTable($ordertype);

$productcode = mysql_fetch_assoc( mysql_query("SELECT productcode FROM tblproduct WHERE pridx = '".$pridx."' ") );
$productcode = $productcode[productcode];

$tempkeyToIdx = mysql_fetch_assoc( mysql_query("SELECT basketidx FROM ".$basket." WHERE tempkey = '".$_ShopInfo->getTempkey()."' AND productcode = '".$productcode."' ") );
$tempkeyToIdx = $tempkeyToIdx['basketidx'];

$rentInfo = rentBasketInfo ( $tempkeyToIdx, $basket );
// �ɼ�
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
$rentStartDate = ( empty($rentInfo['sdate']) ? date("Ymd") : str_replace("-","",$rentInfo['sdate']) ); // �뿩 ������
$rentEndDate = ( empty($rentInfo['edate']) ? date("Ymd") : str_replace("-","",$rentInfo['edate']) ); // �뿩 ������

if( $mode == "save" ) {

	if( rentOrderChecker ( $pridx, $p_bookingStartDate, $p_bookingEndDate, $rentOptionList ) == "pass" ) {
		$ok = rentBasketSave ( $_ShopInfo->getTempkey(), $basket, $productcode, $p_bookingStartDate, $p_bookingEndDate, $rentOptionList,( _array($rentInfo) ? "update" : "" ));
		if( $ok == "ok" ) {
			echo "<html><body onload=\"opener.location.href='/front/basket.php?ordertype=".$ordertype."'; self.close();\" </html>";
			exit;
		}
	} else {
		echo "<script>alert('�뿩 ������ �������� Ȯ�� �Ͻñ� �ٶ��ϴ�.\\n������Ȳ�� Ȯ���ϼ���!'); </script>";
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
			alert("�ɼ� ������ �Է��ϼ���!");
		}
	}
</script>

<form name="rentOptChg">
	<table border="1" cellpadding="0" cellspacing="0" width="100%">
		<tr align='center'>
			<td>�ɼǸ�</td>
			<td>����</td>
			<td>����</td>
			<td>����</td>
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
							<td>�Ϲݰ�</td>
							<td><?=number_format($listROW['nomalPrice'])?></td>
						</tr>
						<tr>
							<td>������</td>
							<td> <?=number_format($listROW['nomalPrice']+$listROW['busySeason'])?></td>
						</tr>
						<tr>
							<td>�ؼ�����</td>
							<td><?=number_format($listROW['nomalPrice']+$listROW['semiBusySeason'])?></td>
						</tr>
						<tr>
							<td>�ָ���</td>
							<td><?=number_format($listROW['nomalPrice']+$listROW['holidaySeason'])?></td>
						</tr>
					</table>
				</td>
				<td width='40'>
					<input type='text' value='<?=$optionCnt[$listROW['idx']]?>' style='width=30px;' name='rentOptions' idxcode="<?=$listROW['idx']?>">��
				</td>
			</tr>
		<?
		}
		?>
	</table>
	<input type='hidden' value='' name='rentOptionList'>
	<input type='hidden' value='<?=$rentInfo['options']?>' name='orgOptionList'>

	<br />

	�����:
	<span id="p_bookingStartDateCal" style="position:absolute;display:none;border:1px solid #d9d9d9;padding:3px;background-color: #FFFFFF;"></span>
	<img src="/images/mini_cal_calen.gif" style="cursor:pointer;" onclick="p_bookingStartDateCal.style.display=(p_bookingStartDateCal.style.display=='none' ? 'block' : 'none' );" align="absmiddle">
	<input type="text" name="p_bookingStartDate" id="p_bookingStartDate" value="<?=$rentStartDate?>" style="width:80px;" onclick="p_bookingStartDateCal.style.display=(p_bookingStartDateCal.style.display=='none' ? 'block' : 'none' );" readonly>
	<br />
	�ݳ��� :
	<span id="p_bookingEndDateCal" style="position:absolute;display:none;border:1px solid #d9d9d9;padding:3px;background-color: #FFFFFF;"></span>
	<img src="/images/mini_cal_calen.gif" style="cursor:pointer;" onclick="p_bookingEndDateCal.style.display=(p_bookingEndDateCal.style.display=='none' ? 'block' : 'none' );" align="absmiddle">
	<input type="text" name="p_bookingEndDate" id="p_bookingEndDate" value="<?=$rentEndDate?>" style="width:80px;" onclick="p_bookingEndDateCal.style.display=(p_bookingEndDateCal.style.display=='none' ? 'block' : 'none' );" readonly>
	<script>
		show_cal('<?=$rentStartDate?>','p_bookingStartDateCal','p_bookingStartDate','todayNext');
		show_cal('<?=$rentEndDate?>','p_bookingEndDateCal','p_bookingEndDate','todayNext');
	</script>
	<br />
	<input type='button' onclick="bookingSchedulePop(<?=$pridx?>);" value="�����Ȳ����">
	<br />
	<input type='button' onclick="bookingPriceCalendalPop();" value="��������޷�">

	<br />
	<input type="hidden" name="pridx" value="<?=$pridx?>">
	<input type="button" value="����ϱ�" onclick="priceCalc(this.form);">
	<div id="priceCalcPrint" style="padding-left:40px;color:#ec2f36;"></div>

	<br />
	<input type="button" value="�����ϱ�" onclick="rentOptChgSubmit(this.form);">
	<input type="button" value="�ݱ�" onclick="self.close();">

	<input type="hidden" value="" name="mode">
</form>