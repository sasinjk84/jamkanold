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

function showCalendar($year,$month,$total_days) {
	$first_day = date('w', mktime(0,0,0,$month,1,$year));

	unset($valueStr);

	$col = 0;
	for($i=0;$i<$first_day;$i++) {
		if($i == 0) {
			$month_class_str	= "td_con2";
		} else {
			$month_class_str = "td_con1";
		}

		$valueStr .= "<TD class=".$month_class_str." width=\"100\" height=\"90\" valign=\"top\">&nbsp;</td>";
		$col++;
	}

	$sql = "SELECT idx,import,rest,subject,duedate,duetime FROM tblschedule ";
	$sql.= "WHERE duedate LIKE '".$year.$month."%' ORDER BY duetime ASC ";
	$result = mysql_query($sql,get_db_conn());

	unset($data);
	while($row = mysql_fetch_object($result)) {
		if (count($data[$row->duedate]) == 3) {
			continue;
		}

		$data[$row->duedate][count($data[$row->duedate])] = $row;
		if ($row->rest == "Y") {
			$restDate[$row->duedate] = "Y";
		}
	}
	mysql_free_result($result);

	for($j=1;$j<=$total_days;$j++) {
		unset($dayname);
		unset($fontColor);		
		$dayname = $j;

		$enum = $j;
		if ($j < 10) $enum = "0".$j;

		if ($col == 0) {
			$fontColor = "font_orange";
		} else if ($col == 6) {
			$fontColor = "font_blue";
			if ($restDate[$year.$month.$enum] == "Y") {
				$fontColor = "font_orange";
			}
			$dayname = "$j";
		} else {
			if ($restDate[$year.$month.$enum] == "Y") {
				$fontColor = "font_orange";
			} else {
				$fontColor = "c_calender_text";
			}
			$dayname = "$j";
		}

		if($col == 0) {
			$month_class_str	= "td_con2";
		} else {
			$month_class_str = "td_con1";
		}

		$valueStr .= "<TD width=\"100\" height=\"90\" valign=\"top\" class=".$month_class_str." onMouseOver=\"this.style.backgroundColor='#8DDAF4'\" onMouseOut=\"this.style.backgroundColor=''\">";
		$valueStr .= "<TABLE cellSpacing=0 cellPadding=0 width=\"100%\" border=0><TR>";
		if (count($data[$year.$month.$enum]) > 0) {
			$valueStr .= "<TD class=".$fontColor."><b><a href=\"community_schedule_day.php?year=".$year."&month=".$month."&day=".$j."\">".$dayname."(".count($data[$year.$month.$enum]).")</b></a> <FONT class=smallfont><span class=\"font_orange\"><b><img src=\"images/icon_fdr.gif\" width=\"9\" height=\"9\" border=\"0\"></b></span></FONT>";
		} else {
			$valueStr .= "<TD class=".$fontColor."><a href=\"community_schedule_day.php?year=".$year."&month=".$month."&day=".$j."\">".$dayname."</a>";
		}
		$valueStr .= "</TD>";
		$valueStr .= "<TD align=right><B><a style=\"CURSOR:hand;\" onClick=\"OpenWindow('community_schedule_add.php?year=".$year."&month=".$month."&day=".$j."',350,130,'no','schedule')\"><img src=\"images/icon_date_add.gif\" width=\"16\" height=\"16\" border=\"0\" hspace=\"2\" align=absmiddle></a></B></td>";
		$valueStr .= "</TR>";
		$valueStr .= "<tr><TD class=verdana colspan=\"2\">";
		if (count($data[$year.$month.$enum]) > 0) {
			for($kk=0;$kk<count($data[$year.$month.$enum]);$kk++) {
				if ($kk == 0) {
					$valueStr .= "<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">";
				} else {
					$valueStr .= "";
				}

				unset($scheduleSubject);
				$scheduleSubject = $data[$year.$month.$enum][$kk]->subject;
				if ($data[$year.$month.$enum][$kk]->import == "Y") {
					$scheduleSubject = "<B>".$scheduleSubject."</B>";
				}
				
				$valueStr .= "<tr><td width=\"100%\"><img src=\"images/icon_point1.gif\" width=\"6\" height=\"7\" border=\"0\">".$scheduleSubject."</td></tr>";
			}
			$valueStr .= "</table>";
		} else {
			$valueStr .= "</td></tr>";
		}
		$valueStr .= "</table>";
		$valueStr .= "</td>";
		$col++;

		if ($col == 7) {
			$valueStr .= "<TR><TD colspan=\"7\" width=\"760\" background=\"images/table_con_line.gif\"><img src=\"images/table_con_line.gif\" width=\"4\" height=\"1\" border=\"0\"></TD></TR>";
			if ($j != $total_days) {
				$valueStr .= "<tr>";
			}
			$col = 0;
		}
	}

	while($col > 0 && $col < 7) {
		if($i == 0) {
			$month_class_str	= "td_con2";
		} else {
			$month_class_str = "td_con1";
		}

		$valueStr .= "<TD class=".$month_class_str." width=\"100\" height=\"90\" valign=\"top\">&nbsp;</td>";
		$col++;
	}
	$valueStr .= "</tr>";
	
	return $valueStr;
}

?>

<? INCLUDE "header.php"; ?>

<script type="text/javascript" src="lib.js.php"></script>
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
			<form action="community_schedule_month.php" method='get'>
			<tr><td height="8"></td></tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/community_schedule_ytitle.gif"  ALT=""></TD>
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
					<TD><a href="community_schedule_year.php?year=<?=$year?>&month=<?=$month?>&day=<?=$day?>" onmouseover="document.m1.src='images/community_schedule_tep1.gif'" onmouseout="document.m1.src='images/community_schedule_tep1r.gif'"><img src='images/community_schedule_tep1r.gif' border='0' name='m1'></A></TD>
					<TD><a href='community_schedule_month.php?year=<?=$year?>&month=<?=$month?>&day=<?=$day?>'><img src='images/community_schedule_tep2.gif' border='0' name='m2'></A></TD>
					<TD><a href='community_schedule_week.php?year=<?=$year?>&month=<?=$month?>&day=<?=$day?>' onmouseover="document.m3.src='images/community_schedule_tep3.gif'" onmouseout="document.m3.src='images/community_schedule_tep3r.gif'"><img src='images/community_schedule_tep3r.gif' border='0' name='m3'></A></TD>
					<TD><a href='community_schedule_day.php?year=<?=$year?>&month=<?=$month?>&day=<?=$day?>' onmouseover="document.m4.src='images/community_schedule_tep4.gif'" onmouseout="document.m4.src='images/community_schedule_tep4r.gif'"><img src='images/community_schedule_tep4r.gif' border='0' name='m4'></A></TD>
					<td width="100%">
					<div align="right">
					<table cellpadding="0" cellspacing="0" width="170">
					<tr>
						<td width="73" align="right">
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
						<td width="73"align="right">
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
						<td width="207" align="right"><input type="image" style="MARGIN: 0px 2px 2px 2px" height="25" src="images/btn_search2.gif" width="50" border="0"></td>
					</tr>
					</table>
					</div>
					</td>
				</tr>
				</table>
				
			</tr>
			<tr>
				<td height=3>&nbsp;</td>
			</tr>
			<tr>
				<td>				
				<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
				<TR>
					<TD colspan=7 background="images/table_top_line.gif"></TD>
				</TR>
				<TR align=center>
					<TD width="100" bgcolor="#F0FDFF" height="30" background="images/blueline_bg.gif"><b><span class="font_orange">��(��)</span></b></TD>
					<TD class="td_con1" width="100" bgcolor="#F0FDFF" background="images/blueline_bg.gif"><font color="#0099CC"><b>��(��)</b></font></TD>
					<TD class="td_con1" width="100" bgcolor="#F0FDFF" background="images/blueline_bg.gif"><font color="#0099CC"><b>ȭ(��)</b></font></TD>
					<TD class="td_con1" width="100" bgcolor="#F0FDFF" background="images/blueline_bg.gif"><font color="#0099CC"><b>��(�)</b></font></TD>
					<TD class="td_con1" width="100" bgcolor="#F0FDFF" background="images/blueline_bg.gif"><font color="#0099CC"><b>��(��)</b></font></TD>
					<TD class="td_con1" width="100" bgcolor="#F0FDFF" background="images/blueline_bg.gif"><font color="#0099CC"><b>��(��)</b></font></TD>
					<TD class="td_con1" width="100" bgcolor="#F0FDFF" background="images/blueline_bg.gif"><font color="#8240A3"><b>��(��)</b></font></TD>
				</TR>
				<TR>
					<TD colspan="7" background="images/table_con_line.gif"></TD>
				</TR>
				<?= showCalendar($inputY,$inputM,$totaldays); ?>
				<TR>
					<TD colspan=7 background="images/table_top_line.gif"></TD>
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
					<TD COLSPAN=3 width="100%" valign="top" bgcolor="white" style="padding-top:8pt; padding-bottom:8pt; padding-left:4pt;" class="menual_bg">
					<table cellpadding="0" cellspacing="0" width="100%">
					<col width=20></col>
					<col width=></col>
					<tr>
						<td align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td><span class="font_dotline">���θ� ��������(MONTH)</span></td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td class="space_top" style="letter-spacing:-0.5pt;">- ��(Month) ������ ���θ� �ֿ� ������ ��µ˴ϴ�.</td>
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
			</form>
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