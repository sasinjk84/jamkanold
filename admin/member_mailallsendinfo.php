<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
INCLUDE ("access.php");

####################### ������ ���ٱ��� check ###############
$PageCode = "me-3";
$MenuCode = "member";
if (!$_usersession->isAllowedTask($PageCode)) {
	INCLUDE ("AccessDeny.inc.php");
	exit;
}
#########################################################

//����Ʈ ����
$setup[page_num] = 10;
$setup[list_num] = 20;

$block=$_REQUEST["block"];
$gotopage=$_REQUEST["gotopage"];

if ($block != "") {
	$nowblock = $block;
	$curpage  = $block * $setup[page_num] + $gotopage;
} else {
	$nowblock = 0;
}

if (($gotopage == "") || ($gotopage == 0)) {
	$gotopage = 1;
}

$type=$_POST["type"];
$date=$_POST["date"];

if ($type=="delete") {
	$sql = "SELECT * FROM tblgroupmail WHERE date = '".$date."' ";
	$result = mysql_query($sql,get_db_conn());
	if($row=mysql_fetch_object($result)) {
		if ($row->issend=="N" && $row->procok=="N") {
			$sql = "DELETE FROM tblgroupmail WHERE date='".$date."' ";
			mysql_query($sql,get_db_conn());
			unlink($Dir.DataDir."groupmail/".$row->filename);
		}
	}
	mysql_free_result($result);
}
?>

<? INCLUDE "header.php"; ?>

<script type="text/javascript" src="lib.js.php"></script>
<script language="JavaScript">
function CheckForm() {

}

function GoPage(block,gotopage) {
	document.form1.block.value = block;
	document.form1.gotopage.value = gotopage;
	document.form1.submit();
}

function ViewMailling(i) {
	var p = window.open("about:blank","pop","height=550,width=750,scrollbars=yes");
	p.document.write('<title>��ü���� �̸�����</title>');
	p.document.write('<style>\n');
	p.document.write('body { background-color: #FFFFFF; font-family: "����"; font-size: x-small; } \n');
	p.document.write('P {margin-top:2px;margin-bottom:2px;}\n');
	p.document.write('</style>\n');
	p.document.write(document.form1.body[i].value);
}

function CancelMailling(date){
	if(confirm("�ش� ���� �߼��� ����Ͻðڽ��ϱ�?")){
		document.form1.type.value="delete";
		document.form1.date.value=date;
		document.form1.submit();
	}
}

function SendMailling(date){
	msg="�ش� ������ �߼��Ͻðڽ��ϱ�?";
	if(date.length==0) msg="�̹߼۵� ���� ��ü�� �߼��Ͻðڽ��ϱ�?";
	if(confirm(msg)){
		document.sendform.date.value=date;
		document.sendform.submit();
	}
}

</script>
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
			<? include ("menu_member.php"); ?>
			</td>

			<td></td>
			<td valign="top">




<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td height="29" colspan="3">
			<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td height="28" class="link" align="left" background="images/con_link_bg.gif"><img src="images/top_link_house.gif" border="0" valign="absmiddle">������ġ : ȸ������ &gt; ȸ������ �ΰ���� &gt; <span class="2depth_select">��ü���� �߼۳��� ����</span></td>
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
					<TD><IMG SRC="images/member_mailallsend_title1.gif" ALT=""></TD>
					</tr><tr>
					<TD width="100%" background="images/title_bg.gif" height=21></TD>
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
					<TD width="100%" class="notice_blue">���θ� ��üȸ�� �Ǵ� ���ȸ������ �߼��� ���� �� �߼ۿ��θ� Ȯ���� �� �ֽ��ϴ�.</TD>
					<TD background="images/distribute_07.gif"></TD>
				</TR>
				<TR>
					<TD><IMG SRC="images/distribute_08.gif"></TD>
					<TD colspan=2 background="images/distribute_09.gif"></TD>
					<TD><IMG SRC="images/distribute_10.gif"></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr><td height="20"></td></tr>

			<form name=sendform action="sendgroupmail_process.php" method=post target="hiddenframe">
			<input type=hidden name=date>
			</form>

			<form name=form1 action="<?=$_SERVER[PHP_SELF]?>" method=post>
			<input type=hidden name=type>
			<input type=hidden name=date>
			<input type=hidden name=block value="<?=$block?>">
			<input type=hidden name=gotopage value="<?=$gotopage?>">
			<tr>
				<td height=3>
				<table border=0 cellpadding=0 cellspacing=0 width=100%>
				<tr>
					<td align=right>
					<A HREF="javascript:SendMailling('')"><B>�̹߼� ���� ��ü�߼�</B></A>
					</td>
				</tr>
				<tr><td height=5></td></tr>
				</table>
				<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
				<col width=110></col>
				<col width=></col>
				<col width=80></col>
				<col width=80></col>
				<col width=80></col>
				<TR>
					<TD colspan=5 background="images/table_top_line.gif"></TD>
				</TR>
				<TR>
					<TD align=center class="table_cell1">�߼�����</TD>
					<TD align=center class="table_cell1">��������</TD>
					<TD align=center class="table_cell1">�߼۰Ǽ�</TD>
					<TD align=center class="table_cell1">�߼۱���</TD>
					<TD align=center class="table_cell1">ó��</TD>
				</TR>
				<input type=hidden name=body>
				<TR>
					<TD colspan=5 background="images/table_con_line.gif"></TD>
				</TR>
<?
				$colspan=5;
				$sql = "SELECT COUNT(*) as t_count FROM tblgroupmail ";
				$result = mysql_query($sql,get_db_conn());
				$row = mysql_fetch_object($result);
				$t_count = $row->t_count;
				mysql_free_result($result);
				$pagecount = (($t_count - 1) / $setup[list_num]) + 1;

				$sql = "SELECT * FROM tblgroupmail ORDER BY date DESC ";
				$sql.= "LIMIT " . ($setup[list_num] * ($gotopage - 1)) . ", " . $setup[list_num];
				$result = mysql_query($sql,get_db_conn());
				$cnt=0;
				while($row=mysql_fetch_object($result)) {
					$number = ($t_count-($setup[list_num] * ($gotopage-1))-$cnt);
					$cnt++;
					$str_date = substr($row->date,0,4)."/".substr($row->date,4,2)."/".substr($row->date,6,2)." ".substr($row->date,8,2).":".substr($row->date,10,2).":".substr($row->date,12,2);
					echo "<tr>\n";
					echo "	<TD align=center class=\"td_con2\">".$str_date."</td>\n";
					echo "	<TD class=\"td_con1\"><b><A HREF=\"javascript:ViewMailling('".$cnt."');\">".htmlspecialchars($row->subject)."</A></b></td>\n";
					echo "	<TD align=center class=\"td_con1\"><B><span class=\"font_orange\">".number_format($row->okcnt)."��</span></B></td>\n";
					echo "	<TD align=center class=\"td_con1\">";
					if($row->issend=="Y") echo "<img src=\"images/icon_stateend.gif\" border=\"0\">";
					else if($row->procok=="Y") echo "<img src=\"images/icon_stateing.gif\" border=\"0\">";
					else echo "<img src=\"images/icon_state.gif\" border=\"0\">";
					echo "	</td>\n";
					echo "	<TD align=center class=\"td_con1\">";
					if($row->issend=="Y") echo "-";										//�߼ۿϷ�
					else if($row->procok=="Y") echo "-";								//�߼��غ���
					else echo "<A HREF=\"javascript:CancelMailling('".$row->date."')\"><B>�߼����</B></A><br><A HREF=\"javascript:SendMailling('".$row->date."')\"><B>�߼��ϱ�</B></A>";						//�߼۴��
					echo "	</TD>\n";
					echo "</tr>\n";
					echo "<tr>\n";
					echo "	<TD colspan=5 background=\"images/table_con_line.gif\"></TD>\n";
					echo "</tr>\n";
					echo "<input type=hidden name=body value=\"".htmlspecialchars($row->body)."\">";
				}
				mysql_free_result($result);

				if ($cnt==0) {
					echo "<tr><td class=\"td_con2\" colspan=".$colspan." align=center>�߼�/������� ���� ������ �����ϴ�.</td></tr>";
				}
?>
				<TR>
					<TD colspan=5 background="images/table_top_line.gif"></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td>
				<table cellpadding="0" cellspacing="0" width="100%">
<?
				$total_block = intval($pagecount / $setup[page_num]);

				if (($pagecount % $setup[page_num]) > 0) {
					$total_block = $total_block + 1;
				}

				$total_block = $total_block - 1;

				if (ceil($t_count/$setup[list_num]) > 0) {
					// ����	x�� ����ϴ� �κ�-����
					$a_first_block = "";
					if ($nowblock > 0) {
						$a_first_block .= "<a href='javascript:GoPage(0,1);' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='ù ������';return true\"><IMG src=\"images/icon_first.gif\" border=0 align=\"absmiddle\"></a>&nbsp;&nbsp;";

						$prev_page_exists = true;
					}

					$a_prev_page = "";
					if ($nowblock > 0) {
						$a_prev_page .= "<a href='javascript:GoPage(".($nowblock-1).",".($setup[page_num]*($block-1)+$setup[page_num]).");' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='���� ".$setup[page_num]." ������';return true\">[prev]</a>&nbsp;&nbsp;";

						$a_prev_page = $a_first_block.$a_prev_page;
					}

					// �Ϲ� �������� ������ ǥ�úκ�-����

					if (intval($total_block) <> intval($nowblock)) {
						$print_page = "";
						for ($gopage = 1; $gopage <= $setup[page_num]; $gopage++) {
							if ((intval($nowblock*$setup[page_num]) + $gopage) == intval($gotopage)) {
								$print_page .= "<span class=font_orange2><B>[".(intval($nowblock*$setup[page_num]) + $gopage)."</B>]</span> ";
							} else {
								$print_page .= "<a href='javascript:GoPage(".$nowblock.",".(intval($nowblock*$setup[page_num]) + $gopage).");' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='������ : ".(intval($nowblock*$setup[page_num]) + $gopage)."';return true\">[".(intval($nowblock*$setup[page_num]) + $gopage)."]</a> ";
							}
						}
					} else {
						if (($pagecount % $setup[page_num]) == 0) {
							$lastpage = $setup[page_num];
						} else {
							$lastpage = $pagecount % $setup[page_num];
						}

						for ($gopage = 1; $gopage <= $lastpage; $gopage++) {
							if (intval($nowblock*$setup[page_num]) + $gopage == intval($gotopage)) {
								$print_page .= "<span class=font_orange2><B>[".(intval($nowblock*$setup[page_num]) + $gopage)."]</B></span> ";
							} else {
								$print_page .= "<a href='javascript:GoPage(".$nowblock.",".(intval($nowblock*$setup[page_num]) + $gopage).");' onMouseOver=\"window.status='������ : ".(intval($nowblock*$setup[page_num]) + $gopage)."';return true\">[".(intval($nowblock*$setup[page_num]) + $gopage)."]</a> ";
							}
						}
					}		// ������ �������� ǥ�úκ�-��


					$a_last_block = "";
					if ((intval($total_block) > 0) && (intval($nowblock) < intval($total_block))) {
						$last_block = ceil($t_count/($setup[list_num]*$setup[page_num])) - 1;
						$last_gotopage = ceil($t_count/$setup[list_num]);

						$a_last_block .= "&nbsp;&nbsp;<a href='javascript:GoPage(".$last_block.",".$last_gotopage.");' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='������ ������';return true\"><IMG src=\"images/icon_last.gif\" border=0 align=\"absmiddle\" width=\"17\" height=\"14\"></a>";

						$next_page_exists = true;
					}

					// ���� 10�� ó���κ�...

					$a_next_page = "";
					if ((intval($total_block) > 0) && (intval($nowblock) < intval($total_block))) {
						$a_next_page .= "&nbsp;&nbsp;<a href='javascript:GoPage(".($nowblock+1).",".($setup[page_num]*($nowblock+1)+1).");' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='���� ".$setup[page_num]." ������';return true\">[next]</a>";

						$a_next_page = $a_next_page.$a_last_block;
					}
				} else {
					$print_page = "<B>[1]</B>";
				}
				echo "<tr>\n";
				echo "	<td width=\"100%\" align=center class=\"font_size\">\n";
				echo "		".$a_div_prev_page.$a_prev_page.$print_page.$a_next_page.$a_div_next_page;
				echo "	</td>\n";
				echo "</tr>\n";
?>
				</table>
				</td>
			</tr>
			</form>
			<tr><td height="25"></td></tr>
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
					<TD COLSPAN=3 width="100%" valign="top" class=menual_bg style="padding-top:8pt; padding-bottom:8pt; padding-left:4pt;">
					<table cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td width="20" align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td ><span class="font_dotline">��ü���� �߼۳��� ����</span></td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td  class="space_top">- ���θ� ȸ������ �߼۵� ������ �߼۰Ǽ��� Ȯ���� �� �ֽ��ϴ�.</td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td  class="space_top">- ���Ϲ߼��� ��Ʈ��ũ ���ϰ� ���� �����ð��뿡 �߼��Ͻñ� �����մϴ�.</td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td  class="space_top">- ���Ϲ߼��� [�߼۴��] Ŭ���� ����� �� �ֽ��ϴ�.</td>
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