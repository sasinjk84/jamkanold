<?php
/**
 * Created by PhpStorm.
 * User: x2chi-objet
 * Date: 2014-10-15
 * Time: 오후 2:13
 */
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/class/pages.php");
INCLUDE ("access.php");

extract($_REQUEST);

if ( $pridx > 0 AND strlen($repairStartDate) == 8 AND strlen($repairEndDate) == 8 ) {
	$insertSQL = "
		INSERT `rent_product_schedule` SET
			`pridx` = '".$pridx."',
			`bookingStartDate` = '".$repairStartDate."',
			`bookingEndDate` = '".$repairEndDate."',
			`status` = 'RP',
			`regDate` = NOW()
	";
	mysql_query($insertSQL,get_db_conn());
	echo "<html></head><body onload=\"alert('등록 되었습니다.대여현황으로 이동합니다.');location.href='".$Dir."front/bookingSchedulePop.php?pridx=".$pridx."&vdate=".strcut($repairStartDate,6,"")."'\"></body></html>";exit;
}
?>

<html>
<head>
	<script type="text/javascript" src="<?=$Dir?>js/miniCalendar.js"></script>
</head>
<body>

	정비 입고
	<form name="repairProductForm" method="post">
	<table>
		<tr>
			<td>입고일</td>
			<td>
				<input type="text" name="repairStartDate" id="repairStartDate" value="<?=date("Ymd")?>" style="width:80px;" readonly>
				<img src="/images/mini_cal_calen.gif" style="cursor:pointer;" onclick="repairStartDateCal.style.display=(repairStartDateCal.style.display=='none' ? 'block' : 'none' );" align="absmiddle">
				<span id="repairStartDateCal" style="position:absolute;display:none;border:1px solid #d9d9d9;padding:3px;background-color: #FFFFFF;z-index:1000;"></span>
			</td>
		</tr>
		<tr>
			<td>출고일</td>
			<td>
				<input type="text" name="repairEndDate" id="repairEndDate" value="<?=date("Ymd")?>" style="width:80px;" readonly>
				<img src="/images/mini_cal_calen.gif" style="cursor:pointer;" onclick="repairEndDateCal.style.display=(repairEndDateCal.style.display=='none' ? 'block' : 'none' );" align="absmiddle">
				<span id="repairEndDateCal" style="position:absolute;display:none;border:1px solid #d9d9d9;padding:3px;background-color: #FFFFFF;z-index:1000;"></span>
			</td>
		</tr>
		<tr>
			<td colspan="2"><input type="button" value="입력" onclick="submit();"></td>
		</tr>
		<input type="hidden" name="pridx" value="<?=$pridx?>">
	</table>
	</form>
	<script>
		show_cal('<?=date("Ymd")?>','repairStartDateCal','repairStartDate');
		show_cal('<?=date("Ymd")?>','repairEndDateCal','repairEndDate');
	</script>

</body>
</html>
