<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
INCLUDE ("access.php");

####################### ������ ���ٱ��� check ###############
$PageCode = "co-1";
$MenuCode = "community";
if (!$_usersession->isAllowedTask($PageCode)) {
	INCLUDE ("AccessDeny.inc.php");
	exit;
}
#########################################################

if (!$year) $year = date("Y");
if (!$month) $month = date("m");
if (!$day) $day = date("d");

$month = $month*1;
$day = $day*1;

if ($month < 10) $month = "0".$month;
if ($day < 10) $day = "0".$day;

$inputY = $year;
$inputM = $month;

$totaldays = get_totaldays($inputY,$inputM);

if ($totaldays <= 0) {
	echo "<script>alert('��¥ ������ �߸��Ǿ����ϴ�.');history.go(-1);</script>";
	exit;
}

function get_totaldays($year,$month) {
	$date = 1;
	while(checkdate($month,$date,$year)) {
		$date++;
	}

	$date--;

	return $date;
}

function get_scheduleData($data,$nYear,$nMonth,$nDay,$time) {
	$scheduleData = $data[$time];

	if (count($scheduleData) > 0) {
		for($kk=0;$kk<count($scheduleData);$kk++) {
			if ($kk == 0) {
				$scheduleContent .= "<TABLE cellSpacing=0 cellPadding=0 width=\"633\" border=0>";
			} else {
				$scheduleContent .= "";
			}

			unset($nSubject);
			unset($scheduleSubject);
			$scheduleSubject = "[".stripslashes($scheduleData[$kk]->subject)."]";
			if ($scheduleData[$kk]->import == "Y") {
				$scheduleSubject = "<span class=\"font_orange\">".$scheduleSubject."</span>";
			}

			$nSubject = "<B>".$scheduleSubject."</B> : ".stripslashes($scheduleData[$kk]->comment);

			$scheduleContent .= "<TD width=\"511\"><FONT class=smallfont>".$nSubject." <span class=\"font_orange\"><b><img src=\"images/icon_fdr.gif\" width=\"9\" height=\"9\" border=\"0\"></b></span></FONT></TD>";
			$scheduleContent .= "<TD width=\"122\"><p align=\"right\">";
			$scheduleContent .= "<a style=\"CURSOR:hand;\" onClick=\"modify_win('".$scheduleData[$kk]->idx."')\"><img src=\"images/btn_edit.gif\" width=\"50\" height=\"22\" border=\"0\"></a> <a href=\"javascript:del_check('".$scheduleData[$kk]->idx."')\"><img src=\"images/btn_del.gif\" width=\"50\" height=\"22\" border=\"0\" hspace=\"2\"></a>";
			$scheduleContent .= "</TD></TR>";
		}

		$scheduleContent .= "</TABLE>";

		return $scheduleContent;
	} else {
		return "&nbsp;";		
	}
}

$sql = "SELECT idx,import,rest,subject,comment,duedate,duetime FROM tblschedule ";
$sql.= "WHERE duedate='".$year.$month.$day."' ORDER BY duetime ASC ";
$result = mysql_query($sql,get_db_conn());

unset($data);
while($row = mysql_fetch_object($result)) {
	$data[$row->duetime][count($data[$row->duetime])] = $row;
}
mysql_free_result($result);

?>

<? INCLUDE "header.php"; ?>

<script type="text/javascript" src="lib.js.php"></script>
<SCRIPT LANGUAGE="JavaScript">
<!--
function del_check(sid) {
	if (confirm('������ �����Ͻðڽ��ϱ�?')) {
	window.location.href ='community_schedule_delete.php?sid=' + sid + '&return_page=community_schedule_day.php&year=<?=$year?>&month=<?=$month?>&day=<?=$day?>';
	}
}

function modify_win(sid) {
	var url = 'community_schedule_modify.php?sid='+sid;
	OpenWindow(url,350,130,'no','schedule');
}
//-->
</SCRIPT>
<table cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed">
<tr>
	<td valign="top">
	<table cellpadding="0" cellspacing="0" width=100% style="table-layout:fixed">
	<tr>
		<td>
		<table cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed"  background="images/con_bg.gif">
		<col width=198></col>
		<col width=10></col>
		<col width=></col>
		<tr>
			<td valign="top"  background="images/leftmenu_bg.gif">
			<? include ("menu_community.php"); ?>
			</td>

			<td></td>
			<td valign="top">




<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td height="29" colspan="3">
			<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td height="28" class="link" align="left" background="images/con_link_bg.gif"><img src="images/top_link_house.gif" border="0" valign="absmiddle">������ġ : Ŀ�´�Ƽ &gt; Ŀ�´�Ƽ ����  &gt; <span class="2depth_select">���θ� ��������</span></td>
			</tr>
			</table>
		</td>
	</tr>   
	<tr>
        <td width="16"><img src="images/con_t_01.gif" width="16" height="16" border="0"></td>
        <td background="images/con_t_01_bg.gif"></td>
        <td width="16"><img src="images/con_t_02.gif" width="16" height="16" border="0"></td>
    </tr>
    <tr>
        <td width="16" background="images/con_t_04_bg1.gif"></td>
        <td bgcolor="#ffffff" style="padding:10px">







			<table cellpadding="0" cellspacing="0" width="100%">
			<form action="community_schedule_day.php" method='get'>
			<tr><td height="8"></td></tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/community_schedule_ytitle.gif" ALT=""></TD>
					</tr><tr>
					<TD width="100%" background="images/title_bg.gif" height="21"></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr><td height="3"></td></tr>
			<tr>
				<td style="padding-bottom:3pt;">
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/distribute_01.gif"></TD>
					<TD COLSPAN=2 background="images/distribute_02.gif"></TD>
					<TD><IMG SRC="images/distribute_03.gif"></TD>
				</TR>
				<TR>
					<TD background="images/distribute_04.gif"></TD>
					<TD class="notice_blue"><IMG SRC="images/distribute_img.gif" ></TD>
					<TD width="100%" class="notice_blue">���θ��� �ֿ� ������ �����Ͻ� �� �ֽ��ϴ�.</TD>
					<TD background="images/distribute_07.gif"></TD>
				</TR>
				<TR>
					<TD><IMG SRC="images/distribute_08.gif"></TD>
					<TD COLSPAN=2 background="images/distribute_09.gif"></TD>
					<TD><IMG SRC="images/distribute_10.gif"></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr><td height="20"></td></tr>
			<tr>
				<td background="images/community_schedule_tepbg.gif">
				<table cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td><a href="community_schedule_year.php?year=<?=$year?>&month=<?=$month?>&day=<?=$day?>" onmouseover="document.m1.src='images/community_schedule_tep1.gif'" onmouseout="document.m1.src='images/community_schedule_tep1r.gif'"><img src="images/community_schedule_tep1r.gif" width="92" height="26" border="0" name='m1'></a></td>
					<td><a href="community_schedule_month.php?year=<?=$year?>&month=<?=$month?>&day=<?=$day?>" onmouseover="document.m2.src='images/community_schedule_tep2.gif'" onmouseout="document.m2.src='images/community_schedule_tep2r.gif'"><img src="images/community_schedule_tep2r.gif" width="103" height="26" border="0" name='m2'></a></td>
					<td><a href="community_schedule_week.php?year=<?=$year?>&month=<?=$month?>&day=<?=$day?>" onmouseover="document.m3.src='images/community_schedule_tep3.gif'" onmouseout="document.m3.src='images/community_schedule_tep3r.gif'"><img src="images/community_schedule_tep3r.gif" width="93" height="26" border="0" name='m3'></a></td>
					<td><a href="community_schedule_day.php?year=<?=$year?>&month=<?=$month?>&day=<?=$day?>"><img src="images/community_schedule_tep4.gif" width="92" height="26" border="0"></a></td>
					<td width="100%">
					<div align="right">
					<table cellpadding="0" cellspacing="0" width="200">
					<tr>
						<td width="73">
						<p align="right">
						<SELECT name=year size="1" class="select">
	<?
						for($y=2000;$y<=date("Y")+5;$y++) {
							unset($select);
							if ($y == $year) $select = "selected";
							echo "<option value='".$y."' ".$select.">".$y." ��</option>";
						}
	?>
						</SELECT>
						</td>
						<td width="73">
						<p align="right">
						<SELECT name=month class="select">
	<?
						for($y=1;$y<=12;$y++) {
							unset($select);
							unset($yn);
							$yn = $y;
							if ($y<10) $yn = "0".$y;
							if ($yn == $month) $select = "selected";
							echo "<option value='".$yn."' ".$select.">".$yn." ��</option>";
						}
	?>
						</SELECT>
						</td>
						<td width="73">
						<p align="right">
						<SELECT name=day class="select">
	<?
						for($y=1;$y<=$totaldays;$y++) {
							unset($select);
							unset($yn);
							$yn = $y;
							if ($y<10) $yn = "0".$y;
							if ($yn == $day) $select = "selected";
							echo "<option value='".$yn."' ".$select.">".$yn." ��</option>";
						}
	?>
						</SELECT>
						</td>
						<td width="207" align=right><input type="image" style="MARGIN: 0px 2px 2px 2px" height="25" src="images/btn_search2.gif" width="50" border="0"></td>
					</tr>
					</table>
					</div>
					</td>
				</tr>
				</table>
				</td>
			</tr>
			<tr>
				<td height=3>&nbsp;</td>
			</tr>
			<tr>
				<td>						
				<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
				<TR>
					<TD colspan=2 background="images/table_top_line.gif"></TD>
				</TR>
				<TR>
					<TD width="100" bgcolor="#F0FDFF" height="30" background="images/blueline_bg.gif"><p align="center"><b><font color="#0099CC">�ð�</font></b></TD>
					<TD class="td_con1" width="627" bgcolor="#F0FDFF" background="images/blueline_bg.gif"><p align="center"><b><font color="#0099CC">������ ����</font></b></TD>
				</TR>
				<TR>
					<TD colspan="2" width="760" background="images/table_con_line.gif"><img src="images/table_con_line.gif" width="4" height="1" border="0"></td>
				</TR>
				<TR>
					<TD class="table_cell" width="100"><p align="center"><FONT class=smallfont><B><span style="letter-spacing:0;">�ð������� <a style="CURSOR:hand;" onClick="OpenWindow('community_schedule_add.php?year=<?=$year?>&month=<?=$month?>&day=<?=$day?>',350,130,'no','schedule')"><img src="images/icon_date_add.gif" width="16" height="16" border="0" hspace="2" align=absmiddle></a></span></B></FONT></TD>
					<TD class="td_con1" width="627"><?= get_scheduleData($data,$year,$month,$day,25) ?></TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
	<?
				$timeArray1 = array(6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22);
				$timeArray2 = array("���� 06 ��","���� 07 ��","���� 08 ��","���� 09 ��","���� 10 ��","���� 11 ��","���� 12 ��","���� 01 ��","���� 02 ��","���� 03 ��","���� 04 ��","���� 05 ��","���� 06 ��","���� 07 ��","���� 08 ��","���� 09 ��","���� 10 ��");
				for($i=0;$i<count($timeArray1);$i++) {
					if ($i%2 == 0) $bgcolor = "#f4f4f4";
					else $bgcolor = "#fafafa";
	?>
				<TR>
					<TD width="100" align=center class="table_cell"><span style="letter-spacing:0;"><?= $timeArray2[$i] ?>
					<FONT class=smallfont><B><a href="javascript:OpenWindow('community_schedule_add.php?year=<?=$year?>&month=<?=$month?>&day=<?=$day?>&time=<?=$timeArray1[$i]?>',350,130,'no','schedule')"><img src="images/icon_date_add.gif" width="16" height="16" border="0" hspace="2" align=absmiddle></a></span></B></FONT></TD>
					<TD width="633" class="td_con1"><?= get_scheduleData($data,$year,$month,$day,$timeArray1[$i]) ?></TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
	<?
				}
	?>
				<TR>
					<TD colspan=2 background="images/table_top_line.gif"></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/manual_top1.gif" WIDTH=15 height="45" ALT=""></TD>
					<TD><IMG SRC="images/manual_title.gif" WIDTH=113 height="45" ALT=""></TD>
					<TD width="100%" background="images/manual_bg.gif" height="35"></TD>
					<TD background="images/manual_bg.gif"></TD>
					<td background="images/manual_bg.gif"><IMG SRC="images/manual_top2.gif" WIDTH=18 height="45" ALT=""></td>
				</TR>
				<TR>
					<TD background="images/manual_left1.gif"></TD>
					<TD COLSPAN=3 width="100%" valign="top" bgcolor="white" style="padding-top:8pt; padding-bottom:8pt; padding-left:4pt;"
 class="menual_bg">
					<table cellpadding="0" cellspacing="0" width="100%">
					<col width=20></col>
					<col width=></col>
					<tr>
						<td align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td><span class="font_dotline">���θ� ��������(DAY)</span></td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td class="space_top" style="letter-spacing:-0.5pt;">- ��(Day) ������ ���θ� �ֿ� ������ ��µ˴ϴ�.</td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td class="space_top" style="letter-spacing:-0.5pt;">- ��ϵ� ������ �ش� �Ϻ��� ������ ������ ���ο� ��µ˴ϴ�.</td>
					</tr>
					<tr>
						<td colspan="2" height="20"></td>
					</tr>
					<tr>
						<td align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td><span class="font_dotline">���� ��� ���</span></td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td class="space_top" style="letter-spacing:-0.5pt;">- ��¥ : �ش� ���� ��¥�� �Է��ϼ���. ����Ͻ� ���� ���� �����մϴ�.</td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td class="space_top" style="letter-spacing:-0.5pt;">- ���� : ����ǥ�� ��µǴ� ������ �Է��ϼ���.</td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td class="space_top" style="letter-spacing:-0.5pt;">- ���� : ���� �� ������ �Է��ϼ���.</td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td class="space_top" style="letter-spacing:-0.5pt;">- ���� : �Ϲ�����, �߿��������� ���еǸ� �߿����� ������ ���������� �β��� ǥ��˴ϴ�.</td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td class="space_top" style="letter-spacing:-0.5pt;">- ���� : �������, �������������� ���еǸ� ������ ������ �ش� ��¥�� ������ ���������� ǥ��˴ϴ�.</td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td class="space_top" style="letter-spacing:-0.5pt;">- �ݺ�/ȸ�� : �ش� ������ �ݺ��ֱ�� Ƚ���� �Է��ϸ� �ش� �ֱ⿡ ���� ������ �ڵ��Էµ˴ϴ�.<br>
						<b>&nbsp;&nbsp;</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;��) 3�� 12�� �޸� �ִ��� 2ȸ �ݺ� -> 3�� 12��, 3�� 19�� �α��� ���</td>
					</tr>
					</table>
					</TD>
					<TD background="images/manual_right1.gif"></TD>
				</TR>
				<TR>
					<TD><IMG SRC="images/manual_left2.gif" WIDTH=15 HEIGHT=8 ALT=""></TD>
					<TD COLSPAN=3 background="images/manual_down.gif"></TD>
					<TD><IMG SRC="images/manual_right2.gif" WIDTH=18 HEIGHT=8 ALT=""></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr><td height="50"></td></tr>
			</table>
</td>
        <td width="16" background="images/con_t_02_bg.gif"></td>
    </tr>
    <tr>
        <td width="16"><img src="images/con_t_04.gif" width="16" height="16" border="0"></td>
        <td background="images/con_t_04_bg.gif"></td>
        <td width="16"><img src="images/con_t_03.gif" width="16" height="16" border="0"></td>
    </tr>
    <tr><td height="20"></td></tr>
</table>

			</td>
		</tr>
		</table>
		</td>
	</tr>
	</table>
	</td>
</tr>
</table>

<?=$onload?>

<? INCLUDE "copyright.php"; ?>