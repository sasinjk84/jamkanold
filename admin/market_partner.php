<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
INCLUDE ("access.php");

####################### ������ ���ٱ��� check ###############
$PageCode = "ma-1";
$MenuCode = "market";
if (!$_usersession->isAllowedTask($PageCode)) {
	INCLUDE ("AccessDeny.inc.php");
	exit;
}
#########################################################

$max=20;
$type=$_POST["type"];
$partner_id=$_POST["partner_id"];
$up_url=$_POST["up_url"];
$up_id=$_POST["up_id"];
$up_passwd=$_POST["up_passwd"];

unset($onload);
if($type=="insert" && strlen($up_url)>0 && strlen($up_id)>0 && strlen($up_passwd)>0) {
	$sql = "SELECT COUNT(*) as cnt FROM tblpartner ";
	$result = mysql_query($sql,get_db_conn());
	$row=mysql_fetch_object($result);
	$partner_cnt=$row->cnt;
	mysql_free_result($result);
	if($partner_cnt<$max) {
		if (!eregi("^[a-zA-Z0-9]*$", $up_id)) {
			$onload="<script>alert('ID�� ����/���ڸ� �Է� �����մϴ�.');history.go(-1);</script>";
		} else {
			$sql = "SELECT COUNT(*) as cnt FROM tblpartner ";
			$sql.= "WHERE id = '".$up_id."' ";
			$result = mysql_query($sql,get_db_conn());
			$row=mysql_fetch_object($result);
			if ($row->cnt!=0) {
				$onload="<script>alert('����ID�� �ߺ��Ǿ����ϴ�.');history.go(-1);</script>";
			}
			mysql_free_result($result);
		}
		if (strlen($onload)==0) {
			$sql = "INSERT tblpartner SET ";
			$sql.= "id			= '".$up_id."', ";
			$sql.= "passwd		= '".$up_passwd."', ";
			$sql.= "url			= '".$up_url."', ";
			$sql.= "hit_cnt		= 0, ";
			$sql.= "authkey		= '' ";
			$insert = mysql_query($sql,get_db_conn());
			if ($insert) $onload="<script>alert('���޻� ����� �Ϸ�Ǿ����ϴ�.');</script>";
		}
	} else {
		$onload="<script>alert('���޻�� ".$max."�� ���� ����� �����մϴ�.');</script>";
	}
} else if ($type=="delete" && strlen($partner_id)>0) {
	$sql = "DELETE FROM tblpartner WHERE id='".$partner_id."'";
	mysql_query($sql,get_db_conn());
	$onload="<script> alert('�ش� ���޻簡 �����Ǿ����ϴ�.');</script>\n";
} else if ($type=="init" && strlen($partner_id)>0) {
	$sql = "UPDATE tblpartner SET hit_cnt=0 WHERE id='".$partner_id."'";
	mysql_query($sql,get_db_conn());
	$onload="<script> alert('�ش� ���޻��� �� ������ ���� 0���� �ʱ�ȭ �Ͽ����ϴ�.');</script>\n";
}
?>

<? INCLUDE "header.php"; ?>

<script type="text/javascript" src="lib.js.php"></script>
<script language="JavaScript">
function CheckForm() {
	if (document.form1.up_url.value.length==0) {
		document.form1.up_url.focus();
		alert("���޻� URL �Ǵ� �ĺ��ܾ �Է��ϼ���.");
		return;
	}
	if (document.form1.up_id.value.length==0) {
		document.form1.up_id.focus();
		alert("���޻� ���� ���̵� �Է��ϼ���.");
		return;
	}
	if (CheckLength(document.form1.up_id)>20) {
		document.form1.up_id.focus();
		alert("���޻� ���� ���̵�� 20�� ���� �Է� �����մϴ�.");
		return;
	}
	if (document.form1.up_passwd.value.length==0) {
		document.form1.up_passwd.focus();
		alert("���޻� ���� �н����带 �Է��ϼ���.");
		return;
	}
	if (CheckLength(document.form1.up_passwd)>20) {
		document.form1.up_passwd.focus();
		alert("���޻� ���� �н������ 20�� ���� �Է� �����մϴ�.");
		return;
	}
	document.form1.type.value="insert";
	document.form1.submit();
}

function PartnerDelete(id) {
	if(confirm("�ش� ���޻縦 �����Ͻðڽ��ϱ�?")){
		document.form2.type.value="delete";
		document.form2.partner_id.value=id;
		document.form2.submit();
	}
}
function PartnerInit(id) {
	if(confirm("�ش� ���޻� �� ������ ���� �ʱ�ȭ �Ͻðڽ��ϱ�?")){
		document.form2.type.value="init";
		document.form2.partner_id.value=id;
		document.form2.submit();
	}
}
function PartnerOrder(id,pw) {
	document.form3.id.value=id;
	document.form3.passwd.value=pw;
	document.form3.submit();
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
			<? include ("menu_market.php"); ?>
			</td>

			<td></td>
			<td valign="top">




<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td height="29" colspan="3">
			<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td height="28" class="link" align="left" background="images/con_link_bg.gif"><img src="images/top_link_house.gif" border="0" valign="absmiddle">������ġ : ���������� &gt; ���������� &gt; <span class="2depth_select">���޸����� ����</span></td>
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






			<table cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed">
			<form name=form1 action="<?=$_SERVER[PHP_SELF]?>" method=post>
			<input type=hidden name=type>
			<tr><td height="8"></td></tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/market_partner_title.gif" ALT=""></TD>
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
					<TD width="100%" class="notice_blue">���޻� ���� �� ���޹�ʸ� ���� ������,�ֹ���踦 Ȯ���Ͻ� �� �ֽ��ϴ�.</TD>
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
				<td>																	
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/market_partner_stitle1.gif" WIDTH="187" HEIGHT=31 ALT=""></TD>
					<TD width="100%" background="images/shop_basicinfo_stitle_bg.gif"></TD>
					<TD><IMG SRC="images/shop_basicinfo_stitle_end.gif" WIDTH=10 HEIGHT=31 ALT=""></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr><td height=3></td></tr>
			<tr>
				<td>																	
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/distribute_01.gif"></TD>
					<TD COLSPAN=2 background="images/distribute_02.gif"></TD>
					<TD><IMG SRC="images/distribute_03.gif"></TD>
				</TR>
				<TR>
					<TD background="images/distribute_04.gif"></TD>
					<TD class="notice_blue"><IMG SRC="images/distribute_img.gif" ></TD>
					<TD width="100%" class="notice_blue">
					1) &quot;�ʱ�ȭ&quot; ��ư Ŭ���� ���޻縦 ���� �湮 �����ڰ� &quot;0&quot;���� �ʱ�ȭ �˴ϴ�.
					<br>2) &quot;�ֹ���ȸ&quot; ��ư Ŭ���� ���޻縦 ���Ͽ� �湮�� ���� �ֹ���ȸ�� �Ͻ� �� �ֽ��ϴ�.
					</TD>
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
			<tr><td height=3></td></tr>
			<tr>
				<td>																													
				<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
				<col width=></col>
				<col width=120></col>
				<col width=80></col>
				<col width=70></col>
				<col width=90></col>
				<col width=90></col>
				<col width=65></col>
				<TR>
					<TD colspan=7 background="images/table_top_line.gif"></TD>
				</TR>
				<TR align=center>
					<TD class="table_cell">���޻� URL �Ǵ� �ĺ��ܾ�</TD>
					<TD class="table_cell1">����ID[��й�ȣ]</TD>
					<TD class="table_cell1">��������</TD>
					<TD class="table_cell1">�����ֹ�</TD>
					<TD class="table_cell1">�ֹ���ȸ</TD>
					<TD class="table_cell1">�ʱ�ȭ</TD>
					<TD class="table_cell1">����</TD>
				</TR>
				<TR>
					<TD colspan="7" background="images/table_con_line.gif"></TD>
				</TR>
<?
				$today=date("Ymd");
				$sql = "SELECT a.id, a.passwd, a.url, a.hit_cnt, count(b.ordercode) as order_cnt ";
				$sql.= "FROM tblpartner a LEFT JOIN tblorderinfo b ON b.ordercode LIKE '".$today."%' ";
				$sql.= "AND b.partner_id=a.id GROUP BY a.id, a.passwd, a.url ";
				$result = mysql_query($sql,get_db_conn());
				$cnt=0;
				while($row=mysql_fetch_object($result)) {
					$cnt++;
					echo "<TR>\n";
					echo "	<TD align=center class=\"td_con2\">".$row->url."</TD>\n";
					echo "	<TD align=center class=\"td_con1\"><b>".$row->id."</b> (<b><span class=\"font_orange\">".$row->passwd."</span></b>)</TD>\n";
					echo "	<TD align=center class=\"td_con1\">".number_format($row->hit_cnt)."</TD>\n";
					echo "	<TD align=center class=\"td_con1\"><span class=\"font_orange\"><b>".number_format($row->order_cnt)."</b></span></TD>\n";
					echo "	<TD align=center class=\"td_con1\"><a href=\"javascript:PartnerOrder('".$row->id."','".$row->passwd."');\"><img src=\"images/btn_search1.gif\" height=\"25\" border=\"0\"></a></TD>\n";
					echo "	<TD align=center class=\"td_con1\"><a href=\"javascript:PartnerInit('".$row->id."');\"><img src=\"images/btn_first.gif\" width=\"74\" height=\"25\" border=\"0\"></a></TD>\n";
					echo "	<TD align=center class=\"td_con1\"><a href=\"javascript:PartnerDelete('".$row->id."');\"><img src=\"images/btn_del.gif\" width=\"50\" height=\"22\" border=\"0\"></a></TD>\n";
					echo "</TR>\n";
					echo "<TR>\n";
					echo "	<TD colspan=\"7\" background=\"images/table_con_line.gif\"></TD>\n";
					echo "</TR>\n";
				}
				mysql_free_result($result);
				if ($cnt==0) {
					echo "<tr><td class=td_con2 colspan=7 align=center>��ϵ� ���޻簡 �������� �ʽ��ϴ�..</td></tr>";
				}
?>
				<TR>
					<TD colspan=7 background="images/table_top_line.gif"></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr><td height=40></td></tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/market_partner_stitle2.gif" WIDTH="187" HEIGHT=31 ALT=""></TD>
					<TD width="100%" background="images/shop_basicinfo_stitle_bg.gif"></TD>
					<TD><IMG SRC="images/shop_basicinfo_stitle_end.gif" WIDTH=10 HEIGHT=31 ALT=""></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr><td height=3></td></tr>
			<tr>
				<td>
				<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
				<col width=155></col>
				<col width=></col>
				<TR>
					<TD colspan=2 background="images/table_top_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">���޻�URL �Ǵ� �ĺ��ܾ�</TD>
					<TD class="td_con1"><INPUT style="WIDTH:100%" maxLength=100 name=up_url class="input"></TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">���޻� ���� ���̵�</TD>
					<TD class="td_con1"><INPUT maxLength=20 name=up_id class="input"> <span class="font_orange">* �ѱ� �Է� �Ұ�.����,��������</span></TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">���޻� ���� �н�����</TD>
					<TD class="td_con1"><INPUT maxLength=20 name=up_passwd class="input"></TD>
				</TR>
				<TR>
					<TD colspan=2 background="images/table_top_line.gif"></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr><td height=10></td></tr>
			<tr>
				<td align=center><a href="javascript:CheckForm();"><img src="images/botteon_save.gif" width="113" height="38" border="0"></a></td>
			</tr>
			<tr>
				<td height="30">&nbsp;</td>
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
						<td><b>���޻� ������ȸ URL</b></td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td style="padding-bottom:5pt;">
						<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
						<TR>
							<TD background="images/table_top_line.gif" width="153"><img src="images/table_top_line.gif"></TD>
							<TD background="images/table_top_line.gif" width="607" ></TD>
						</TR>
						<TR>
							<TD class="table_cell" width="153"><B>���޻� ������ȸ URL</B></TD>
							<TD class="td_con1" width="600"><A href="http://<?=$shopurl.PartnerDir?>index.php" target=_blank><B><span class="font_blue">http://<?=$shopurl.PartnerDir?>index.php</span></B></A></TD>
						</TR>
						<TR>
							<TD background="images/table_top_line.gif" width="153"><img src="images/table_top_line.gif"></TD>
							<TD background="images/table_top_line.gif" width="607"></TD>
						</TR>
						</TABLE>
						</td>
					</tr>
					<tr>
						<td align="right"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td>���޻翡 �˷��ּž� �� ������ȸ URL�Դϴ�.<br></td>
					</tr>
					<tr>
						<td align="right"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td> �߱��� ���̵�/������� �α��� �ϸ� �ش� ���޻縦 ���Ͽ� �湮�� ���� �ֹ������� Ȯ���� �� �ֽ��ϴ�. <br></td>
					</tr>
					<tr>
						<td align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td><b><span class="font_orange"> ���޻翡���� ���θ� ��ũ��� �ȳ� </span></b><br><span class="font_orange">http://<?=$shopurl?>?ref=���޻�URL �Ǵ� �ĺ��ܾ�<br>
						��) �ĺ��ܾ "partner" �� ��� http://<?=$shopurl?>?ref=partner<br>
						<b>&nbsp;</b>&nbsp;&nbsp;&nbsp;&nbsp;���޻�URL�� "http://www.partner.com" �� ��� http://<?=$shopurl?>?ref=http://www.partner.com<br>
						<b>&nbsp;</b>&nbsp;&nbsp;&nbsp;&nbsp;���޻�URL�� "www.partner.com" �� ��� http://<?=$shopurl?>?ref=www.partner.com</span></td>
					</tr>
					<tr>
						<td align="right"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td> �� ����� ���� ���޸� ���Ͽ� ������ â���ϰ�, �� ���Ϳ� ���� �����Ḧ ���޻翡 ����ϴ� ������� �</td>
					</tr>
					</table>
					</TD>
					<TD background="images/manual_right1.gif"><IMG SRC="images/manual_right1.gif" WIDTH=18 HEIGHT="2" ALT=""></TD>
				</TR>
				<TR>
					<TD><IMG SRC="images/manual_left2.gif" WIDTH=15 HEIGHT=8 ALT=""></TD>
					<TD COLSPAN=3 background="images/manual_down.gif"><IMG SRC="images/manual_down.gif" WIDTH="4" HEIGHT=8 ALT=""></TD>
					<TD><IMG SRC="images/manual_right2.gif" WIDTH=18 HEIGHT=8 ALT=""></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr>
				<td height="50"></td>
			</tr>
			</form>

			<form name=form2 action="<?=$_SERVER[PHP_SELF]?>" method=post>
			<input type=hidden name=type>
			<input type=hidden name=partner_id>
			</form>

			<form name=form3 action="http://<?=$shopurl.PartnerDir?>order_search.php" method=post target=_blank>
			<input type=hidden name=id>
			<input type=hidden name=passwd>
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