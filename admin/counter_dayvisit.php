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

$searchdate=$_POST["searchdate"];
$print=$_POST["print"];

if(strlen($searchdate)==0) $searchdate=date("Ym");
if($searchdate==date("Ym")) $nowdate="Y";

$year=substr($searchdate,0,4);
$mon=substr($searchdate,4,2);

$lastdays = array("0","31","28","31","30","31","30","31","31","30","31","30","31");
if(date("L",mktime(0,0,0,$mon,1,$year))) $lastdays[2]="29";

?>


<? INCLUDE "header.php"; ?>

<script type="text/javascript" src="lib.js.php"></script>
<script language="JavaScript">
function search_date() {
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
				<td height="28" class="link" align="left" background="images/con_link_bg.gif"><img src="images/top_link_house.gif" border="0" valign="absmiddle">������ġ : ���м� &gt; Ʈ���� �м� &gt; <span class="2depth_select">���ں� �� �湮��</span></td>
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
					<TD><IMG SRC="images/counter_dayvisit_title.gif" ALT=""></TD>
					</tr>
<tr>
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
				<input type=hidden name=print value="<?=$print?>">
				<tr>
					<td align=center><img src="graph/dayvisit.php?date=<?=$searchdate?>"></td>
				</tr>
<?
				if($searchdate>=date("Ym",mktime(0,0,0,date("m")-1,date("d"),date("Y")))) {
					$sql ="SELECT MID(date,7,2) as day,sum(cnt) as cnt FROM tblcounter ";
					$sql.="WHERE date LIKE '".$searchdate."%' GROUP BY day ";
				} else {	//1�� �� �����ʹ� �� ����Ÿ ���̺��� ã�´�.
					$sql ="SELECT MID(date,7,2) as day, cnt FROM tblcountermonth ";
					$sql.="WHERE date LIKE '".$searchdate."%'";
				}
				$sum=0;
				$result = mysql_query($sql,get_db_conn());
				while($row = mysql_fetch_object($result)){
					$time[$row->day]=$row->cnt;
					if($max<$row->cnt) $max=$row->cnt;
					$sum+=$row->cnt;
				}
				mysql_free_result($result);
?>
				<tr>
					<td height="3" style="font-size:11px;">
<?
					if($nowdate=="Y") {
						echo "* <b><font color=\"#FF6633\">".date("Y�� m�� d��")."</font></b> ����";
					} else {
						echo "* <b><font color=\"#FF6633\">".substr($searchdate,0,4)."�� ".substr($searchdate,4,2)."��</font></b>";
					}
					echo " ���ں� �湮�� ��Ȳ �Դϴ�.";
?>
				</tr>
				<tr>
					<td>
					<table border=0 cellpadding=0 cellspacing=0 width=100%>
					<col width=50></col>
					<col width=50></col>
					<col width=></col>
					<col width=70></col>
					<col width=50></col>
					<col width=50></col>
					<col width=></col>
					<col width=70></col>
					<tr><td colspan=8 height=2 bgcolor=#000000></td></tr>
					<TR>
						<TD class="table_cell" align=center><FONT color=#3d3d3d>��¥</FONT></TD>
						<TD class="table_cell" align=center><FONT color=#3d3d3d>����</FONT></TD>
						<TD class="table_cell" align=center><FONT color=#3d3d3d>�湮�ڼ�</FONT></TD>
						<TD class="table_cell" align=center><FONT color=#3d3d3d>�ۼ�Ʈ</FONT></TD>
						<TD class="table_cell" align=center style="border-left-width:1pt; border-left-color:silver; border-left-style:dashed;"><FONT color=#3d3d3d>��¥</FONT></TD>
						<TD class="table_cell" align=center><FONT color=#3d3d3d>����</FONT></TD>
						<TD class="table_cell" align=center><FONT color=#3d3d3d>�湮�ڼ�</FONT></TD>
						<TD class="table_cell" align=center><FONT color=#3d3d3d>�ۼ�Ʈ</FONT></TD>
					</TR>
					<tr><td colspan=8 height=1 bgcolor=#EDEDED></td></tr>
<?
					$weekname = array("<font color=#FF0000>��</font>","��","ȭ","��","��","��","<font color=#0000FF>��</font>");
					$week = date("w",mktime(0,0,0,$mon,1,$year))-1;
					if($searchdate==date("Ym")) $hour=date("d"); 
					else $hour=$lastdays[(int)$mon];
					$half=ceil($lastdays[(int)$mon]/2);

					for($i=1;$i<=$half;$i++) {
						$count=substr("0".$i,-2);
						$count2=$i+$half;
						if($sum>0) $percent[$count]=$time[$count]/$sum*100;
						else $percent[$count]=0;
						if($pos=strpos($percent[$count],".")) {
							$percent[$count]=substr($percent[$count],0,$pos+3);
						}
						if($sum>0) $percent[$count2]=$time[$count2]/$sum*100;
						else $percent[$count2]=0;
						if($pos=strpos($percent[$count2],".")) {
							$percent[$count2]=substr($percent[$count2],0,$pos+3);
						}

						$visitcnt="&nbsp;";
						$strpercent="&nbsp;";
						if($count<=$hour) {
							$visitcnt=number_format($time[$count])."��";
							$strpercent=$percent[$count]."%";
						}
						$visitcnt2="&nbsp;";
						$strpercent2="&nbsp;";
						if($count2<=$hour) {
							$visitcnt2=number_format($time[$count2])."��";
							$strpercent2=$percent[$count2]."%";
						}

						echo "<tr>\n";
						echo "	<TD class=\"td_con2a\" align=center".($max>0 && $max==$time[$count]?" bgcolor=#E1F1FF":"").">".($max>0 && $max==$time[$count]?"<b><font color=#000000>".$count."</font></b>":$count)."</td>\n";
						echo "	<TD class=\"td_con2a\" align=center".($max>0 && $max==$time[$count]?" bgcolor=#E1F1FF":"").">".($max>0 && $max==$time[$count]?"<b><font color=#000000>".$weekname[($count+$week)%7]."</font></b>":$weekname[($count+$week)%7])."</td>\n";
						echo "	<TD class=\"td_con2a\" align=center".($max>0 && $max==$time[$count]?" bgcolor=#E1F1FF":"")."><font color=#00769D>".($max>0 && $max==$time[$count]?"<b>".$visitcnt."</b>":$visitcnt)."</font></td>\n";
						echo "	<TD class=\"td_con2a\" align=center".($max>0 && $max==$time[$count]?" bgcolor=#E1F1FF":"").">".($max>0 && $max==$time[$count]?"<b><font color=#000000>".$strpercent."</font></b>":$strpercent)."</td>\n";

						echo "	<TD class=\"td_con2a\" align=center".($max>0 && $max==$time[$count2]?" bgcolor=#E1F1FF":"")." style=\"border-left-width:1pt; border-left-color:silver; border-left-style:dashed;\">".($max>0 && $max==$time[$count2]?"<b><font color=#000000>".$count2."</font></b>":($count2<=$lastdays[(int)$mon]?$count2:"&nbsp;"))."</td>\n";
						echo "	<TD class=\"td_con2a\" align=center".($max>0 && $max==$time[$count2]?" bgcolor=#E1F1FF":"").">".($max>0 && $max==$time[$count2]?"<b><font color=#000000>".$weekname[($count2+$week)%7]."</font></b>":($count2<=$lastdays[(int)$mon]?$weekname[($count2+$week)%7]:"&nbsp;"))."</td>\n";
						echo "	<TD class=\"td_con2a\" align=center".($max>0 && $max==$time[$count2]?" bgcolor=#E1F1FF":"")."><font color=#00769D>".($max>0 && $max==$time[$count2]?"<b>".$visitcnt2."</b>":($count2<=$lastdays[(int)$mon]?$visitcnt2:"&nbsp;"))."</font></td>\n";
						echo "	<TD class=\"td_con2a\" align=center".($max>0 && $max==$time[$count2]?" bgcolor=#E1F1FF":"").">".($max>0 && $max==$time[$count2]?"<b><font color=#000000>".$strpercent2."</font></b>":($count2<=$lastdays[(int)$mon]?$strpercent2:"&nbsp;"))."</td>\n";

						echo "</tr>\n";
						echo "<tr><td colspan=8 height=1 bgcolor=#EDEDED></td></tr>\n";
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
						���� ������� 
						<select name=searchdate onchange="search_date()">
<?
						$cnt=11;  
						for($i=0;$i<=$cnt;$i++) {
							$date=date("Ym",mktime(0,0,0,date("m")-$i,1,date("Y")));
							echo "<option value=\"".$date."\"";
							if($date==$searchdate) echo " selected";
							echo ">".substr($date,0,4)."�� ".substr($date,4,2)."��</option>\n";
						}
?>
						</select>
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
						<td><FONT color=#3d3d3d>���ں� ���θ� ���湮�� ���ڸ� ���� �帮�� �ֽ��ϴ�.</FONT></td>
					</tr>
					<tr><td colspan=2 height=5></td></tr>
					<tr>
						<td width="20" align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td>
							<FONT color=#3d3d3d>���� ������ ���湮�� ���ڸ� 1�� �������� ���� �帮�� �ֽ��ϴ�.<br>
							���� �湮�� ���ڿ� ���ں� ���Ϻ� �湮�� ���̸� �ľ��� �� �ֽ��ϴ�.</FONT>
						</td>
					</tr>
					<tr><td colspan=2 height=5></td></tr>
					<tr>
						<td width="20" align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td><FONT color=#3d3d3d>���ں� ���θ� ���湮�� ���ڸ� �м��Ͽ� ���� �湮���� ���� ���� �湮���� �湮�� ������ �м��Ͽ�<br>���� ���θ� ���å�� �ݿ��� �� �ֽ��ϴ�.<br>���� ���, �̹� �� ����Ͽ� �湮�ڰ� ���� ���� ���, �湮�ڰ� ���� ������ �̸��� �������̾���,<br>�ֹ��õ��Ǽ��� �������������� ������ ���, �ͻ��� �̸��� �������� ȿ���� ��ġ�� �ľ��� �� �ֽ��ϴ�.<br>Ư��, �̸��� ������ �� �ܺ����� ������ ȿ���� ���� �򰡵����ν� ������ �������Դϴ�.</FONT></td>
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
<input type=hidden name=searchdate value=<?=$searchdate?>>
</form>

</table>
<?=$onload?>

<? INCLUDE "copyright.php"; ?>
<?}?>