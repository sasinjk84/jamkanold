<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
INCLUDE ("access.php");

####################### ������ ���ٱ��� check ###############
$PageCode = "st-1";
$MenuCode = "counter";
if (!$_usersession->isAllowedTask($PageCode)) {
	INCLUDE ("AccessDeny.inc.php");
	exit;
}
#########################################################

$type=$_POST["type"];
$searchdate=$_POST["searchdate"];
$print=$_POST["print"];

if(strlen($type)==0) $type="d";
if(strlen($searchdate)==0) $searchdate=date("Ymd");
if($type=="d" && $searchdate==date("Ymd")) $timeview="NO";

?>


<? INCLUDE "header.php"; ?>

<script type="text/javascript" src="lib.js.php"></script>
<script language="JavaScript">
function search_date(type) {
	document.form1.type.value=type;
	document.form1.submit();
}

function view_printpage(){
	window.open("about:blank","popviewprint","height=550,width=700,scrollbars=yes");
	document.form2.print.value="Y";
	document.form2.submit();
}

</script>
<?if($print!="Y"){?>
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
			<? include ("menu_counter.php"); ?>
			</td>

			<td></td>
			<td valign="top">




<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td height="29" colspan="3">
			<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td height="28" class="link" align="left" background="images/con_link_bg.gif"><img src="images/top_link_house.gif" border="0" valign="absmiddle">������ġ : ���м� &gt; Ʈ���� �м� &gt; <span class="2depth_select">�ð��� �� �湮��</span></td>
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
<?} else {?>
			<table cellpadding="5" cellspacing="0" width="680" style="table-layout:fixed">
<?}?>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/counter_timevisit_title.gif" ALT=""></TD>
					</tr><tr>
					<TD width="100%" background="images/title_bg.gif" height="21"></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr><td height="20"></td></tr>
			<tr>
				<td align=center>

				<table cellpadding="0" cellspacing="0" width="100%">
				<form name=form1 method=post action="<?=$_SERVER[PHP_SELF]?>">
				<input type=hidden name=type>
				<input type=hidden name=print value="<?=$print?>">
				<tr>
					<td align=center>
						<A HREF="javascript:search_date('d')"><img src="images/counter_tab_day_<?=($type=="d"?"on":"off")?>.gif" width="74" height="20" border="0"></A>
						<A HREF="javascript:search_date('w')"><img src="images/counter_tab_week_<?=($type=="w"?"on":"off")?>.gif" width="74" height="20" border="0"></A>
						<A HREF="javascript:search_date('m')"><img src="images/counter_tab_month_<?=($type=="m"?"on":"off")?>.gif" width="74" height="20" border="0"></A>
					</td>
				</tr>
				<tr>
					<td align=center><img src="graph/timevisit.php?type=<?=$type?>&date=<?=$searchdate?>"></td>
				</tr>
<?
				if($type=="d") {
					$sql= "SELECT MID(date,9,2) as hour,cnt FROM tblcounter ";
					$sql.="WHERE date LIKE '".$searchdate."%' ";
				} else if($type=="w") {
					$prevdate=date("Ymd00",mktime(0,0,0,date("m"),date("d")-7,date("Y")));
					$nextdate=date("Ymd99");
					$sql ="SELECT SUM(cnt) as cnt,MID(date,9,2) as hour FROM tblcounter ";
					$sql.="WHERE (date<='".$nextdate."' AND date>='".$prevdate."') GROUP BY hour ";
				} else if($type=="m") {
					$date=date("Ym");
					$sql ="SELECT SUM(cnt) as cnt,MID(date,9,2) as hour FROM tblcounter ";
					$sql.="WHERE date LIKE '".$date."%' GROUP BY hour ";
				}
				$sum=0;
				$result = mysql_query($sql,get_db_conn());
				while($row = mysql_fetch_object($result)) {
					$time[$row->hour]=$row->cnt;
					if($max<$row->cnt) $max=$row->cnt;
					$sum+=$row->cnt;
				}
				mysql_free_result($result);
?>
				<tr>
					<td height="3" style="font-size:11px;">
<?
					if($timeview=="NO") {
						echo "* <b><font color=\"#FF6633\">".date("Y�� m�� d�� H�� i��")."</font></b> ����";
					} else {
						echo "* <b><font color=\"#FF6633\">".substr($searchdate,0,4)."�� ".substr($searchdate,4,2)."�� ".($type!="m"?substr($searchdate,6,2)."��":"")."</font></b> ��ü";
					}
					echo " �湮�� ��Ȳ �Դϴ�.";
?>
				</tr>
				<tr>
					<td>
					<table border=0 cellpadding=0 cellspacing=0 width=100%>
					<col width=60></col>
					<col width=></col>
					<col width=90></col>
					<col width=60></col>
					<col width=></col>
					<col width=90></col>
					<tr><td colspan=6 height=2 bgcolor=#000000></td></tr>
					<TR>
						<TD class="table_cell" align=center><FONT color=#3d3d3d>�ð�</FONT></TD>
						<TD class="table_cell" align=center><FONT color=#3d3d3d>�湮�ڼ�</FONT></TD>
						<TD class="table_cell" align=center><FONT color=#3d3d3d>�ۼ�Ʈ</FONT></TD>
						<TD class="table_cell" align=center style="border-left-width:1pt; border-left-color:silver; border-left-style:dashed;"><FONT color=#3d3d3d>�ð�</FONT></TD>
						<TD class="table_cell" align=center><FONT color=#3d3d3d>�湮�ڼ�</FONT></TD>
						<TD class="table_cell" align=center><FONT color=#3d3d3d>�ۼ�Ʈ</FONT></TD>
					</TR>
					<tr><td colspan=6 height=1 bgcolor=#EDEDED></td></tr>
<?
					$hour=date("H"); 
					if($sum>0) {
						for($i=0;$i<=11;$i++) {
							$count=substr("0".$i,-2);
							$count2=$i+12;
							$percent[$count]=$time[$count]/$sum*100;
							if($pos=strpos($percent[$count],".")) {
								$percent[$count]=substr($percent[$count],0,$pos+3);
							}
							$percent[$count2]=$time[$count2]/$sum*100;
							if($pos=strpos($percent[$count2],".")) {
								$percent[$count2]=substr($percent[$count2],0,$pos+3);
							}

							$visitcnt="&nbsp;";
							$strpercent="&nbsp;";
							if($timeview<>"NO" || ($timeview=="NO" && $count<=$hour)) {
								$visitcnt=number_format($time[$count])."��";
								$strpercent=$percent[$count]."%";
							}
							$visitcnt2="&nbsp;";
							$strpercent2="&nbsp;";
							if($timeview<>"NO" || ($timeview=="NO" && $count2<=$hour)) {
								$visitcnt2=number_format($time[$count2])."��";
								$strpercent2=$percent[$count2]."%";
							}

							echo "<tr>\n";
							echo "	<TD class=\"td_con2a\" align=center".($max==$time[$count]?" bgcolor=#E1F1FF":"").">".($max==$time[$count]?"<b><font color=#000000>".$count."��</font></b>":$count."��")."</td>\n";
							echo "	<TD class=\"td_con2a\" align=center".($max==$time[$count]?" bgcolor=#E1F1FF":"")."><font color=#00769D>".($max==$time[$count]?"<b>".$visitcnt."</b>":$visitcnt)."</font></td>\n";
							echo "	<TD class=\"td_con2a\" align=center".($max==$time[$count]?" bgcolor=#E1F1FF":"").">".($max==$time[$count]?"<b><font color=#000000>".$strpercent."</font></b>":$strpercent)."</td>\n";
							echo "	<TD class=\"td_con2a\" align=center".($max==$time[$count2]?" bgcolor=#E1F1FF":"")." style=\"border-left-width:1pt; border-left-color:silver; border-left-style:dashed;\">".($max==$time[$count2]?"<b><font color=#000000>".$count2."��</font></b>":$count2."��")."</td>\n";
							echo "	<TD class=\"td_con2a\" align=center".($max==$time[$count2]?" bgcolor=#E1F1FF":"").">".($max==$time[$count2]?"<b><font color=#00769D>".$visitcnt2."</font></b>":$visitcnt2)."</td>\n";
							echo "	<TD class=\"td_con2a\" align=center".($max==$time[$count2]?" bgcolor=#E1F1FF":"").">".($max==$time[$count2]?"<b><font color=#000000>".$strpercent2."</font></b>":$strpercent2)."</td>\n";
							echo "</tr>\n";
							echo "<tr><td colspan=6 height=1 bgcolor=#EDEDED></td></tr>\n";
						}
					} else {
						echo "<tr bgcolor=#FFFFFF><td colspan=6 height=30 class=\"td_con2a\" align=center><font color=#3D3D3D>�ش� �ڷᰡ �����ϴ�.</font></td></tr>\n";
						echo "<tr><td colspan=6 height=1 bgcolor=#EDEDED></td></tr>\n";
					}
?>
					</table>
					</td>
				</tr>
				<?if($print!="Y"){?>
				<TR>
					<TD width="100%" background="images/counter_blackline_bg.gif" height="30" align=right>
					<table cellpadding="0" cellspacing="0">
					<tr>
						<td class="font_white" align=right>
						<?if($type=="d") {?>
						���� ������� 
						<select name=searchdate onchange="search_date('d')">
<?
						for($i=59;$i>=0;$i--) {
							$date=date("Ymd",mktime(0,0,0,date("m"),date("d")-$i,date("Y")));
							echo "<option value=\"".$date."\"";
							if($date==$searchdate) echo " selected";
							echo ">".substr($date,0,4)."�� ".substr($date,4,2)."�� ".substr($date,6,2)."��</option>\n";
						}
?>
						</select>
						<?}?>
						</td>
						<td align=right style="padding:0,5,0,5"><A HREF="javascript:view_printpage()"><img src="images/counter_btn_print.gif" width="90" height="20" border="0"></A></td>
					</tr>
					</table>
					</TD>
				</TR>
				<?} else {?>
				<TR>
					<td align=right style="padding:20,20,0,5"><A HREF="javascript:print()"><img src="images/counter_btn_print.gif" width="90" height="20" border="0"></A></td>
				</TR>
				<?}?>
				</form>
				</table>

				</td>
			</tr>
			<tr><td height="30"></td></tr>
<?if($print!="Y"){?>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/manual_top1.gif" WIDTH=15 HEIGHT=45 ALT=""></TD>
					<TD><IMG SRC="images/manual_title.gif" WIDTH=113 HEIGHT=45 ALT=""></TD>
					<TD width="100%" background="images/manual_bg.gif"></TD>
					<TD background="images/manual_bg.gif"></TD>
					<TD><IMG SRC="images/manual_top2.gif" WIDTH=18 HEIGHT=45 ALT=""></TD>
				</TR>
				<TR>
					<TD background="images/manual_left1.gif"></TD>
					<TD COLSPAN=3 width="100%" valign="top" bgcolor="white" style="padding-top:8pt; padding-bottom:8pt; padding-left:4pt;"  class="menual_bg">
					<table cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td width="20" align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td><FONT color=#3d3d3d>�ð� �帧(��/�ְ�/����)�� ���� ���θ� ���湮�� ���ڸ� ���� �帮�� �ֽ��ϴ�.</FONT></td>
					</tr>
					<tr><td colspan=2 height=5></td></tr>
					<tr>
						<td width="20" align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td>
							<FONT color=#3d3d3d>�� �湮�� ���� ������ �Žð��� ��湮�� ���ڸ� �����ϰ� �ֽ��ϴ�.<br>
							���θ��� Ʈ������ ���� ���� �ð��븦 ��/�ְ�/���� �������� �����Ͽ� �ľ��� �� �ֽ��ϴ�.</FONT>
						</td>
					</tr>
					<tr><td colspan=2 height=5></td></tr>
					<tr>
						<td width="20" align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td>
							<FONT color=#3d3d3d>Ʈ������ ���� �ð��븦 ���̱� ���Ͽ�, ���θ� ���θ���̳� �������� ��ȭ�� �� �ֽ��ϴ�.<br>
							�ְ�/���� �������� Ʈ������ ���� �ð��뿡 ���߾� �̺�Ʈ ��ǰ�� �����Ű�� ������ ������ �� �ֽ��ϴ�.</FONT>
						</td>
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
			<tr><td height="30"></td></tr>
<?}?>
			</table>
<?if($print!="Y"){?>

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

<form name=form2 method=post action="<?=$_SERVER[PHP_SELF]?>"  target=popviewprint>
<input type=hidden name=print>
<input type=hidden name=type value=<?=$type?>>
<input type=hidden name=searchdate value=<?=$searchdate?>>
</form>
</table>
<?=$onload?>
<? INCLUDE "copyright.php"; ?>
<?}?>