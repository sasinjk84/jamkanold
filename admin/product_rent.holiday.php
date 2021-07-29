<?php
/**
 * Created by PhpStorm.
 * User: x2chi-objet
 * Date: 2014-10-24
 * Time: 오후 2:49
 */
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/class/pages.php");
INCLUDE ("access.php");

extract($_REQUEST);

if ( strlen($dayTitle) > 0 AND strlen($holidayDate) > 0 ) {
	$insertSQL = "INSERT `rent_seasonSet_holiday` SET `title` = '".$dayTitle."', `date` = '".$holidayDate."' ";
	mysql_query($insertSQL,get_db_conn());
	echo "<html></head><body onload=\"alert('등록 되었습니다.');location.href='".$Dir."admin/product_rent.holiday.php';\"></body></html>";
	exit;
}
?>

<html>
<head>
	<script type="text/javascript" src="<?=$Dir?>js/miniCalendar.js"></script>
	<link rel="stylesheet" href="style.css">
</head>
<body>

	<h2 style="background:url('images/member_mailallsend_imgbg.gif');"><img src="images/weekend_popt.gif" alt="성수기/준성수기 등록" /></h2>

	<TABLE WIDTH="96%" align="center" BORDER=0 CELLPADDING=0 CELLSPACING=0>
		<TR>
			<TD><IMG SRC="images/product_season_stitle4.gif" ALT="주말(공휴일) 등록" /></TD>
		</TR>
	</TABLE>
	<TABLE WIDTH="96%" align="center" BORDER=0 CELLPADDING=0 CELLSPACING=0>
		<TR>
			<TD><IMG SRC="images/distribute_01.gif"></TD>
			<TD COLSPAN=2 background="images/distribute_02.gif"></TD>
			<TD><IMG SRC="images/distribute_03.gif"></TD>
		</TR>
		<TR>
			<TD background="images/distribute_04.gif"></TD>
			<TD class="notice_blue"><IMG SRC="images/distribute_img.gif" ></TD>
			<TD width="100%" class="notice_blue">
				1) 주말(공휴일)요금은 1일 단위로만 등록가능합니다.<br />
				2) 성수기와 주말(공휴일)요금이 겹칠 경우 주말(공휴일)요금이 우선됩니다.<br />
				※ 요금적용 순서 : 주말(공휴일)요금 > 성수기 > 준성수기 > 비수기
			</TD>
			<TD background="images/distribute_07.gif"></TD>
		</TR>
		<TR>
			<TD><IMG SRC="images/distribute_08.gif"></TD>
			<TD COLSPAN=2 background="images/distribute_09.gif"></TD>
			<TD><IMG SRC="images/distribute_10.gif"></TD>
		</TR>
		<tr><td height="5"></td></tr>
	</TABLE>

	<form name="seasonInsertForm" method="post" style="margin:0px;padding:0px;">
	<table border="0" cellpadding="0" cellspacing="0" width="96%" align="center" style="margin-bottom:15px;">
		<colgroup>
			<col width="140" />
			<col width="" />
		</colgroup>
		<tr><td background="images/table_top_line.gif" colSpan="2" /></td></tr>
		<tr>
			<td class="table_cell"><img width="8" height="11" src="images/icon_point2.gif" border="0"/> 날짜</td>
			<td class="td_con1">
				<input type="text" name="holidayDate" id="holidayDate" value="<?=date("Ymd")?>" class="input" style="width:80px;" onclick="holidayDateCal.style.display=(holidayDateCal.style.display=='none' ? 'block' : 'none' );" readonly>
				<span id="holidayDateCal" style="position:absolute;display:none;border:1px solid #d9d9d9;padding:3px;background-color: #FFFFFF;z-index:1000;"></span>
			</td>
		</tr>
		<tr><td background="images/table_con_line.gif" colSpan="2" /></tr>
		<tr>
			<td class="table_cell"><img width="8" height="11" src="images/icon_point2.gif" border="0"/> 공휴일 명</td>
			<td class="td_con1"><input type="text" name="dayTitle" size="40" class="input" /> <input type="button" value="입력" onclick="submit();"></td>
		</tr>
		<tr><td background="images/table_top_line.gif" colSpan="2" /></td></tr>
	</table>
	</form>

	<script>
		show_cal('<?=date("Ymd")?>','holidayDateCal','holidayDate');
	</script>

	<table border="0" cellpadding="0" cellspacing="0" width="96%" align="center" class="tableBase">
		<colgroup>
			<col width="100">
			<col width="">
			<col width="80">
		</colgroup>
		<tr>
			<th class="firstTh">날짜</th>
			<th>공휴일 명</th>
			<th>삭제</th>
		</tr>
		<?
		$SQL = "SELECT * FROM rent_seasonSet_holiday ORDER BY date DESC ";
		$RES = mysql_query($SQL,get_db_conn());
		while ( $ROW = mysql_fetch_assoc($RES) ) {
			echo "
				<tr>
					<td class=\"firstTd\">".$ROW['date']."</td>
					<td style=\"padding-left:10px;\">".$ROW['title']."</td>
					<td align=\"center\"><input type='image' src=\"images/btn_del.gif\" onclick=\"delDate('".$ROW['idx']."');\"></td>
				</tr>
			";
		}
		?>
	</table>

	<div style="margin:10px 0px;text-align:center;"><a href="javascript:window.close();"><img src="/images/common/bigview_btnclose.gif" border="0" alt="" /></a></div>

</body>
</html>