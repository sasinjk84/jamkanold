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

function get_totaldays($year,$month) {
	$date = 1;
	while(checkdate($month,$date,$year)) {
		$date++;
	}

	$date--;

	return $date;
}

function showCalendar($data,$year,$month,$total_days) {
	$first_day = date('w', mktime(0,0,0,$month,1,$year));

	unset($valueStr);
	$valueStr .= "<tr>";
	$col = 0;
	for($i=0;$i<$first_day;$i++) {
		$valueStr .= "<td class=\"calender1\">&nbsp;</td>\n";
		$col++;
	}

	for($j=1;$j<=$total_days;$j++) {
		unset($dayname);
		unset($day_class_str);
		$dayname = $j;
		
		switch ($col)
		{
			case 0 : $day_class_str = "calender_sun1"; break;
			case 6 : $day_class_str = "calender_sat1"; break;
			default : $day_class_str = "calender1";
		}

		$temp_m=substr("0".$month,-2);
		$temp_d=substr("0".$j,-2);
		if($data[$year.$temp_m.$temp_d]=="Y") $day_class_str = "calender_sun1";

		if ($year == date("Y") && intval($month) == intval(date("m")) && $j == intval(date("d"))) {
			$valueStr .= "<td align=center class=\"calender_select1\"><a href='community_schedule_day.php?year=$year&month=$month&day=$j'><font color=\"#FFFFFF\">".$dayname."</font></a></td>\n";
		} else {
			$valueStr .= "<td align=center class=".$day_class_str."><a href='community_schedule_day.php?year=$year&month=$month&day=$j'>".$dayname."</a></td>\n";
		}
		
		$col++;

		if ($col == 7) {
			$valueStr .= "</tr>\n";
			if ($j != $total_days) {
				$loop_count++;
				$valueStr .= "<tr>\n";
			}
			$col = 0;
		}
	}

	while($col > 0 && $col < 7) {
		$valueStr .= "<td class=\"calender1\">&nbsp;</td>\n";
		$col++;
	}
	$valueStr .= "</tr>\n";

	if($loop_count<5) {
		$valueStr .= "<tr><td class=\"calender_sun1\">&nbsp;</td></tr>";
	}

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
				<table  border='0' cellpadding='0' cellspacing='0' width=100%>
				<form action="community_schedule_year.php" method='get'>
				<tr>
					<td>
					<table cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td><a href="community_schedule_year.php?year=<?=$year?>&month=<?=$month?>&day=<?=$day?>"><img src="images/community_schedule_tep1.gif" width="92" height="26" border="0"></a></td>
						<td><a href='community_schedule_month.php?year=<?=$year?>&month=<?=$month?>&day=<?=$day?>' onmouseover="document.m2.src='images/community_schedule_tep2.gif'" onmouseout="document.m2.src='images/community_schedule_tep2r.gif'"><img src="images/community_schedule_tep2r.gif" width="103" height="26" border="0" name='m2'></a></td>
						<td><a href='community_schedule_week.php?year=<?=$year?>&month=<?=$month?>&day=<?=$day?>' onmouseover="document.m3.src='images/community_schedule_tep3.gif'" onmouseout="document.m3.src='images/community_schedule_tep3r.gif'"><img src="images/community_schedule_tep3r.gif" width="93" height="26" border="0" name='m3'></a></td>
						<td><a href='community_schedule_day.php?year=<?=$year?>&month=<?=$month?>&day=<?=$day?>' onmouseover="document.m4.src='images/community_schedule_tep4.gif'" onmouseout="document.m4.src='images/community_schedule_tep4r.gif'"><img src="images/community_schedule_tep4r.gif" width="92" height="26" border="0" name='m4'></a></td>
						<td width="100%">
						<div align="right">
						<table cellpadding="0" cellspacing="0" width="115">
						<tr>
							<td width="73" align=right>
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
							<td width="207" align="right"><input type=image style="MARGIN: 0px 2px 2px 2px" height="25" src="images/btn_search2.gif" width="50" border="0"></td>
						</tr>
						</table>
						</div>
						</td>
					</tr>
					</table>
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
				<table  border='0' cellspacing='0' cellpadding='0' width=100%>
				<TR>
					<TD background="images/table_top_line.gif" colspan="4"></TD>
				</TR>
				<tr>
		<?
				$sql = "SELECT idx,import,rest,subject,duedate,duetime,comment FROM tblschedule ";
				$sql.= "WHERE rest='Y' AND duedate LIKE '".$year."%' ";
				$sql.= "ORDER BY duetime ASC ";
				$result = mysql_query($sql,get_db_conn());
				unset($data);
				while($row = mysql_fetch_object($result)) {
					if ($row->rest == "Y") {
						$data[$row->duedate] = "Y";
					}
				}
				mysql_free_result($result);

				$inputY = $year;
				$col2 = 0;
				for($i=1;$i<=12;$i++) {
					$inputM = $i;
					$totaldays = get_totaldays($inputY,$inputM);

					if($i%4 == 1)
						$tr_class_str = "td_con2";
					else
						$tr_class_str = "td_con1";							
		?>
					<td valign="top">
					<table align='center' border='0' cellspacing='0' width='100%'>
					<TR>
						<TD background="images/table_con_line.gif"></TD>
					</TR>
					<tr>
						<td class="<?=$tr_class_str?>" align=center valign=top background="images/blueline_bg.gif"><a href='community_schedule_month.php?year=<?=$year?>&month=<?=$inputM?>&day=<?=$day?>'><b><font color="#0099CC"><?= $inputM ?>��</font></b></a></td>
					</tr>
					<tr>
						<TD valign="top" class="<?=$tr_class_str?>">
						<table border=0 cellpadding="0" cellspacing="0" width="160" align="center" style="margin-top:5pt; margin-bottom:5pt;">
						<tr align=right>
							<td style="padding-bottom:4pt;"><img src="images/main_calender_date_s1.gif" width="20" height="9" border="0"></td>
							<td style="padding-bottom:4pt;"><img src="images/main_calender_date_m1.gif" width="20" height="9" border="0"></td>
							<td style="padding-bottom:4pt;"><img src="images/main_calender_date_t1.gif" width="20" height="9" border="0"></td>
							<td style="padding-bottom:4pt;"><img src="images/main_calender_date_w1.gif" width="20" height="9" border="0"></td>
							<td style="padding-bottom:4pt;"><img src="images/main_calender_date_thu1.gif" width="20" height="9" border="0"></td>
							<td style="padding-bottom:4pt;"><img src="images/main_calender_date_fri1.gif" width="20" height="9" border="0"></td>
							<td style="padding-bottom:4pt;"><img src="images/main_calender_date_sat.gif" width="20" height="9" border="0"></td>
						</tr>
						<?= showCalendar($data,$inputY,$inputM,$totaldays); ?>
						</table>
						</TD>
					</tr>
					</table>
					</td>
		<?
					$col2++;

					if ($col2 == 4) {
						echo "</tr>";
						if ($i != 12) {
							echo "<tr>";
						}
						$col2 = 0;
					}
				}
		?>
				</form>
				<TR>
					<TD background="images/table_top_line.gif" colspan="4"></TD>
				</TR>
				</table>
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
						<td><span class="font_dotline">���θ� ��������(YEAR)</span></td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td class="space_top" style="letter-spacing:-0.5pt;">- ��(Year) ������ ���θ� �ֿ� ������ ��µ˴ϴ�.</td>
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
