<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
INCLUDE ("access.php");

####################### ������ ���ٱ��� check ###############
$PageCode = "sh-3";
$MenuCode = "shop";
if (!$_usersession->isAllowedTask($PageCode)) {
	INCLUDE ("AccessDeny.inc.php");
	exit;
}
#########################################################

$escrow_id=$_shopdata->escrow_id;
$escrow_info=$_shopdata->escrow_info;



$mode=$_POST["mode"];
if($mode=="update") {

	$temp_escrow="escrow=".$_POST['escrow']."|escrowcash=Y|escrow_limit=0|";

	$percent=$_POST["percent"];
	if($percent>0) $temp_escrow.="percent=".$percent."|";

	$sql = "UPDATE tblshopinfo SET escrow_info='".$temp_escrow."' ";
	mysql_query($sql,get_db_conn());
	echo "
		<script>
			alert('����ũ�� �������� ������ �Ϸ�Ǿ����ϴ�.');
			location.href='shop_escrow.php';
		</script>";
	exit;
}



$escrow_info = GetEscrowType($escrow_info);

$escrow=$escrow_info['escrow'];

?>

<? INCLUDE "header.php"; ?>

<script type="text/javascript" src="lib.js.php"></script>
<script language="JavaScript">
function CheckForm() {

	if(isNaN(document.form1.percent.value)) {
		alert("����ũ�� ������� �Ҽ��� ������ ���ڸ� �Է��ϼ���.");
		document.form1.percent.focus();
		return;
	}

	if(parseInt(document.form1.percent.value)>10){
		alert("����ũ�� ������� �ִ� 10%���� �Է��� �����մϴ�.");
		document.form1.percent.focus();
		return;
	}

	if(document.form1.percent.value.length>0) {
		if(confirm("����ũ�� �����ᰡ �ΰ��˴ϴ�.\n\n�������� �ŷ������ �ƴ����� ������ �����Ͻðڽ��ϱ�?")) {
			document.form1.mode.value="update";
			document.form1.submit();
		} else {
			return;
		}
	}

	if(confirm("����ũ�� �������� ������ �����Ͻðڽ��ϱ�?")) {
		document.form1.mode.value="update";
		document.form1.submit();
	}
}

<?//if(strlen($escrow_id)>0) {?>
function change_percent(type) {
	if(type==1) {
		document.form1.percent.value="";
		document.form1.percent.disabled=true;
		document.form1.percent.style.background="#f0f0f0";
	} else if(type==2) {
		document.form1.percent.disabled=false;
		document.form1.percent.style.background="";
		document.form1.percent.focus();
	}
}
<?//}?>
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
			<? include ("menu_shop.php"); ?>
			</td>

			<td></td>
			<td valign="top">




<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td height="29" colspan="3">
			<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td height="28" class="link" align="left" background="images/con_link_bg.gif"><img src="images/top_link_house.gif" border="0" valign="absmiddle">������ġ : �������� &gt; ���θ� � ���� &gt; <span class="2depth_select">����ũ�� �������� ����</span></td>
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
			<tr>
				<td height="8"></td>
			</tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/shop_escrow_title.gif"  ALT=""></TD>
					</tr>
<tr>
<TD width="100%" background="images/title_bg.gif" height="21"></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr>
				<td height="3"></td>
			</tr>
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
					<TD width="100%" class="notice_blue"><p>1) ���θ��� ������� ��ġ��(����ũ��)�� ���� ������ �Ͻ� �� �ֽ��ϴ�.<br>
					2) ����ũ�� ���Խ� ���Ժ� �� ������� ����ũ�� ���� ȸ�縶�� �ٸ��ϴ�.
					</TD>
				</TR>
				<TR>
					<TD><IMG SRC="images/distribute_08.gif"></TD>
					<TD COLSPAN=2 background="images/distribute_09.gif"></TD>
					<TD><IMG SRC="images/distribute_10.gif"></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr>
				<td height="20"></td>
			</tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/shop_escrow_stitle1.gif"  ALT=""></TD>
					<TD width="100%" background="images/shop_basicinfo_stitle_bg.gif">&nbsp;</TD>
					<TD><IMG SRC="images/shop_basicinfo_stitle_end.gif" WIDTH=10 HEIGHT=31 ALT=""></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr>
				<td height=3></td>
			</tr>
			<form name=form1 action="<?=$_SERVER[PHP_SELF]?>" method=post>
			<input type=hidden name=mode>
			<tr>
				<td>


				<table cellpadding="0" cellspacing="0" width="100%">
				<col width="139"></col>
				<col></col>
				<TR>
					<TD colspan=2 background="images/table_top_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell" rowspan="3"><img src="images/icon_point2.gif" width="8" height="11" border="0">������� ��ġ��<br>&nbsp;&nbsp;(����ũ��) ���뿩��</td>
					<td class="td_con1">
					<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
					<TR height=30>
						<TD>
							<input type=radio id="idx_escrow_y" name=escrow value="Y" <?if($escrow=="Y")echo"checked";?> >
							<label style='cursor:hand; TEXT-DECORATION: none' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_escrow_y>
								<span class="font_orange"><b>������� ��ġ��(����ũ��)�� ����</B>
							</label>
						</TD>
					</TR>
					</TABLE>
					</td>
				</tr>
				<TR>
					<TD colspan=2 background="images/table_con_line.gif"></TD>
				</TR>
				<TR>
					<td colspan=2 class="td_con1">
					<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
					<TR height=30>
						<TD>
							<input type=radio id="idx_escrow_n" name=escrow value="N" <?if($escrow=="N")echo"checked";?>>
							<label style='cursor:hand; TEXT-DECORATION: none' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_escrow_n>
								<span class="font_orange"><b>������� ��ġ��(����ũ��)�� <font color=black>������(���� å�� �߻�)</b>
							</label>
						</TD>
					</TR>
					</TABLE>
					</td>
				</tr>
				<TR>
					<TD colspan=2 background="images/table_top_line.gif"></TD>
				</TR>
				</table>
				</td>
			</tr>
			<tr>
				<td height="30"></td>
			</tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/shop_escrow_stitle2.gif" ALT=""></TD>
					<TD width="100%" background="images/shop_basicinfo_stitle_bg.gif">&nbsp;</TD>
					<TD><IMG SRC="images/shop_basicinfo_stitle_end.gif" WIDTH=10 HEIGHT=31 ALT=""></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr>
				<td height=3></td>
			</tr>
			<tr>
				<td>
				<table cellpadding="0" cellspacing="0" width="100%">
				<col width="139"></col>
				<col></col>
				<TR>
					<TD colspan=2 background="images/table_top_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">����ũ�ο� ������ �ΰ�</td>
					<td class="td_con1">
					<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
					<TR>
						<TD class=linebottomleft style="padding-left:10px;">
						<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
						<TR>
							<TD><p><input type=radio id="idx_percent0" name="escrow_percent" value=0 <?if($escrow_info["percent"]<=0)echo"checked";?> onclick="change_percent(1)"> <label style='cursor:hand; TEXT-DECORATION: none' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_percent0>����ũ�� ������, �� �����ݾ׿��� �߰��ΰ� ����.</label></p></TD>
						</TR>
						<TR>
							<TD><p><input type=radio id="idx_percent1" name="escrow_percent" value=1 <?if($escrow_info["percent"]>0)echo"checked";?> onclick="change_percent(2)"> <label style='cursor:hand; TEXT-DECORATION: none' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_percent1>����ũ�� ������, �� �����ݾ׿��� <input type=text name=percent size=5 maxlength=3 style="PADDING-RIGHT: 5px; FONT-SIZE: 9pt; BACKGROUND: #f0f0f0; TEXT-ALIGN: right" value="<?=$escrow_info["percent"]?>">% ��ŭ, ������ �� <font color=red>�ΰ�</font>�մϴ�.</label><br>
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="font_orange">* ���� å�� �߻��� �� �ֽ��ϴ�.</span></p><script>change_percent(<?=($escrow_info["percent"]<=0?"1":"2")?>);</script></TD>
						</TR>
						</TABLE>
						</TD>
					</TR>
					</TABLE>
					</td>
				</tr>
				<TR>
					<TD colspan=2 background="images/table_top_line.gif"></TD>
				</TR>
				</table>
				</td>
			</tr>
			<tr>
				<td height=20></td>
			</tr>
			<tr>
				<td align="center"><a href="javascript:CheckForm();"><img src="images/botteon_save.gif" width="113" height="38" border="0"></a></td>
			</tr>
			</form>
			<tr>
				<td height=20></td>
			</tr>
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
					<TD COLSPAN=3 width="100%" valign="top" bgcolor="white" style="padding-top:8pt; padding-bottom:8pt; padding-left:4pt;" class="menual_bg">
					<table cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td width="20" align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td ><span class="font_dotline">����ũ�� ���� ���� �ȳ�</span></td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td  class="space_top">- ����ũ�� ������ ������¸� ����մϴ�.(�Ϲ� ������¿� �����ϳ� ��ġ�� �����ϴ� �͸� �ٸ�)</td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td  class="space_top">- ����ũ�� ������ ������� ������¼�����+����ũ�� ���� �������� �ΰ��˴ϴ�.(����ũ�� ���� ȸ�縶�� �ٸ�)</td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td  class="space_top">- ������� �ܿ� �߰� ��������(�ǽð� ������ü, �ſ�ī�����, �ڵ��� ���� ��) �� ���� ������ �������ܸ� �����˴ϴ�.</td>
					</tr>
					<tr>
						<td height="20" colspan="2"></td>
					</tr>
					<tr>
						<td width="20" align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td ><span class="font_dotline">����ũ�� ���� �ŷ�</span></td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td  class="space_top">- �ſ�ī��� �����ϴ� �ŷ�.</td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td class="space_top">- ����� �ʿ����� ���� ��ȭ ���� �����ϴ� �ŷ�.(������ ��)</td>
					</tr>
					<tr>
						<td width="20" align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td ><span class="font_dotline">����ũ�� ������ ����</span></td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td class="space_top">- ��ǰ��� -> ����ũ�ΰ��� ����ȸ�翡 ��۳����� ���� -> ����ũ�μ���ȸ�翡�� ������ ����Ȯ�� ��û -><br>
						<b>&nbsp;&nbsp;</b><span class="font_blue"><b>���� ����Ȯ����</b>&nbsp;&nbsp;<b>&nbsp;&nbsp;�� ���</b></span> -> Ȯ���Ϸκ��� 2���� ����<br>
						<b>&nbsp;&nbsp;</b><span class="font_orange"><b>���� ����Ȯ����&nbsp;���� ���</b></span> -> ����Ϸκ���  5���� ������ �ڵ� ����(�����ڿ��Դ� �ڵ�����Ȯ���� �뺸��)
						</td>
					</tr>
					<tr>
						<td height="20" colspan="2"></td>
					</tr>
					<tr>
						<td width="20" align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td ><span class="font_black">����ũ�� ���Խ� ���Ժ�, �����ϰ� ������� ����ũ�� ���� ȸ�縶�� �ٸ��ϴ�.</span></td>
					</tr>
					<tr>
						<td height="20" colspan="2"></td>
					</tr>
					<tr>
						<td width="20" align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td ><span class="font_black">����ũ�� ���� ȸ��� ���Ծȳ��� ȸ��Ȩ�������� �������ּ���.(����ũ�� ����ȸ��� ���� ������ ȸ�縸 �����˴ϴ�)</span></td>
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
			<tr>
				<td height="50"></td>
			</tr>
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