<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
INCLUDE ("access.php");

####################### ������ ���ٱ��� check ###############
$PageCode = "de-1";
$MenuCode = "design";
if (!$_usersession->isAllowedTask($PageCode)) {
	INCLUDE ("AccessDeny.inc.php");
	exit;
}
#########################################################

$type=$_POST["type"];

if($type=="modify") {
	$sel_topleft=$_POST["sel_topleft"];
	$sel_mainetc=$_POST["sel_mainetc"];

	$sql = "SELECT * FROM tbltempletinfo WHERE icon_type='".$_shopdata->icon_type."' ";
	$result=mysql_query($sql,get_db_conn());
	if($row=mysql_fetch_object($result)) {
		$templet_icon_type=$row->icon_type;
		$templet_top_type=$row->top_type;
		$templet_main_type=$row->main_type;
		$templet_menu_type=$row->menu_type;
	} else {
		$templet_icon_type="003";
		$templet_top_type="top003";
		$templet_main_type="main003";
		$templet_menu_type="menu003";
	}
	mysql_free_result($result);
	if($sel_topleft!="NO" || $sel_mainetc!="NO") {
		$qry="";
		if($sel_topleft=="ALL") {
			$qry.="top_type='topp', menu_type='menup',";
		} else if($sel_topleft=="TOP") {
			$qry.="top_type='topp', menu_type='".$templet_menu_type."',";
		} else if($sel_topleft=="LEFT") {
			$qry.="top_type='".$templet_top_type."', menu_type='menup',";
		} else if($sel_topleft=="NO") {
			$qry.="top_type='".$templet_top_type."', menu_type='".$templet_menu_type."',";
		}/* else if() {

		} else if() {

		} else if() {

		}*/

		if($sel_mainetc=="M") {
			$qry.="title_type='Y', main_type='mainm',";
		} else if($sel_mainetc=="N") {
			$qry.="title_type='Y', main_type='mainn',";
		} else if($sel_mainetc=="P") {
			//$qry.="title_type='Y', main_type='mainp',";
			$qry.="title_type='Y', main_type='".$templet_main_type."',";
		} else if($sel_mainetc=="NO") {
			$qry.="title_type='N', main_type='".$templet_main_type."',";
		}
		$qry=substr($qry,0,-1);
		if(strlen($qry)>0) {
			$sql = "UPDATE tblshopinfo SET ".$qry." ";
			$update=mysql_query($sql,get_db_conn());
			$onload="<script>alert(\"���������� ���뼱���� �Ϸ�Ǿ����ϴ�.\");</script>";
		}
	} else {
		$sql = "UPDATE tblshopinfo SET ";
		$sql.= "top_type	= '".$templet_top_type."', ";
		$sql.= "menu_type	= '".$templet_menu_type."', ";
		$sql.= "main_type	= '".$templet_main_type."', ";
		$sql.= "title_type	= 'N', ";
		$sql.= "icon_type	= '".$templet_icon_type."' ";
		$update=mysql_query($sql,get_db_conn());
		$onload="<script>alert(\"������ ���ø����� ����Ǿ����ϴ�.\");</script>";
	}
	DeleteCache("tblshopinfo.cache");
}

$sql = "SELECT top_type, menu_type, main_type, title_type, icon_type FROM tblshopinfo ";
$result=mysql_query($sql,get_db_conn());
$row=mysql_fetch_object($result);

$design_top_type=$row->top_type;
$design_menu_type=$row->menu_type;
$design_main_type=$row->main_type;
$design_title_type=$row->title_type;
$design_icon_type=$row->icon_type;
mysql_free_result($result);
?>

<? INCLUDE "header.php"; ?>

<script type="text/javascript" src="lib.js.php"></script>
<script language="JavaScript">
function CheckForm() {
	if(confirm("���õ� �׸��� ������������ �ϼ̽��ϱ�?\n\n������������ �ϼ̴ٸ� \"Ȯ��\"��ư�� �����ñ� �ٶ��ϴ�.")) {
		document.form1.type.value="modify";
		document.form1.submit();
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
			<? include ("menu_design.php"); ?>
			</td>

			<td></td>
			<td valign="top">




<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td height="29" colspan="3">
			<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td height="28" class="link" align="left" background="images/con_link_bg.gif"><img src="images/top_link_house.gif" border="0" valign="absmiddle">������ġ : �����ΰ��� &gt; ��FTP �� �������� ����  &gt; <span class="2depth_select">���������� ���뼱��</span></td>
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
					<TD><IMG SRC="images/design_option_title.gif"  ALT=""></TD>
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
					<TD width="100%" class="notice_blue"><p>1) ���� ����, ���, ���� ������, ���� Ÿ��Ʋ�� ���������� ������ �� �� �ֽ��ϴ�.<br>2) ���뼱���� �ϸ� ��ٷ� ���θ��� �ݿ��˴ϴ�.(���ø�, Easy ������ �ڵ�����)</p></TD>
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
			<tr>
				<td height="20"></td>
			</tr>
			<tr>
				<td>
				<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
				<TR>
					<TD height="1" colspan="2" bgcolor="#B9B9B9"></TD>
				</TR>
				<form name=form1 action="<?=$_SERVER[PHP_SELF]?>" method=post>
				<input type=hidden name=type>
				<TR align="center">
					<TD width="47%" class="table_cell">��ܰ� ���� �������� ����</TD>
					<TD width="53%" class="table_cell1">���κ�����  Ÿ��Ʋ �������� ����</TD>
				</tr>
				<TR>
					<TD height="1" colspan="2" bgcolor="#EDEDED"></TD>
				</TR>
				<tr>
					<TD class="td_con2">
					<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
					<tr>
						<td><img src="images/design_option_img01.gif" border="0"></td>
						<td width="100%" valign="top"><input type=radio id="idx_topleft0" name=sel_topleft value="ALL" <? if ($design_top_type=="topp" && $design_menu_type=="menup") echo "checked"; ?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_topleft0><span style="letter-spacing:-0.5pt;">���+���� ���� ����</span></label><BR>
							<input type=radio id="idx_topleft1" name=sel_topleft value="TOP" <? if ($design_top_type=="topp" && $design_menu_type!="menup") echo "checked"; ?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_topleft1><span style="letter-spacing:-0.5pt;">��ܸ� ����</span></label><BR>
							<input type=radio id="idx_topleft2" name=sel_topleft value="LEFT" <? if ($design_top_type!="topp" && $design_menu_type=="menup") echo "checked"; ?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_topleft2><span style="letter-spacing:-0.5pt;">���ʸ� ����</span></label><BR>
							<input type=radio id="idx_topleft3" name=sel_topleft value="NO" <? if ($design_top_type!="topp" && $design_menu_type!="menup") echo "checked"; ?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_topleft3><span style="letter-spacing:-0.5pt;">���+���� ��� ���� ����</span></label><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="font_blue">(���ø����� ����)</span>
						</td>
					</tr>
					</table><br>
					<?if ($design_top_type=="tope" && $design_menu_type!="menue") {  ?>
					<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
					<tr>
						<td><img src="images/design_option_img02.gif" border="0"></td>
						<td width="100%" valign="top"><input type="radio" name="sel_topleft" value="TOPE" checked><span class=font_orange style="letter-spacing:-0.5pt;">Easy ��� �������� ���� ��...<?if($design_menu_type=="menup") echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class=\"font_blue\">(���� �޴� ���������� ����)</span>"; ?></span></td>
					</tr>
					</table>
					<?} else if ($design_top_type!="tope" && $design_menu_type=="menue") { ?>
					<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
					<tr>
						<td><img src="images/design_option_img02.gif" border="0"></td>
						<td width="100%" valign="top"><input type="radio" name="sel_topleft" value="LEFTE" checked><span class=font_orange style="letter-spacing:-0.5pt;">Easy ���� �������� ���� ��...<br><?php if($design_menu_type=="menup") echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class=\"font_blue\">(��� �޴� ���������� ����)</span>"; ?></span></td>
					</tr>
					</table>
					<?} else if ($design_top_type=="tope" && $design_menu_type=="menue") { ?>
					<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
					<tr>
						<td><img src="images/design_option_img02.gif" border="0"></td>
						<td width="100%" valign="top"><input type="radio" name="sel_topleft" value="ALLE" checked><span class=font_orange style="letter-spacing:-0.5pt;">Easy ���/���� �������� ���� ��...</span></td>
					</tr>
					</table>
					<?}?>
					</td>
					<td class="td_con1" valign="top">
					<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
					<tr>
						<td valign="top"><img src="images/design_option_img03.gif" border="0"></td>
						<td width="100%" valign="top"><input type=radio id="idx_mainetc0" name=sel_mainetc value="M" <? if ($design_main_type=="mainm") echo "checked"; ?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_mainetc0><span style="letter-spacing:-0.5pt;">���κ��� ����+<span class="font_orange">���� ���ʸ޴� ���</span></span></label><br>
							<input type=radio id="idx_mainetc1" name=sel_mainetc value="N" <? if ($design_main_type=="mainn") echo "checked"; ?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_mainetc1><span style="letter-spacing:-0.5pt;">���κ��� ����+<span class="font_orange">���� ���ʸ޴� �����</span></span></label><br>
							<input type=radio id="idx_mainetc2" name=sel_mainetc value="P" <? if ($design_title_type=="Y" && strlen($design_main_type)==7) echo "checked"; ?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_mainetc2><span style="letter-spacing:-0.5pt;">���� Ÿ��Ʋ ���������� ����</label></span><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="font_blue">(��ü Ÿ��Ʋ �� ������ �̹����� ����)</span><br>
							<input type=radio id="idx_mainetc3" name=sel_mainetc value="NO" <? if($design_title_type!="Y") echo "checked"; ?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_mainetc3><span style="letter-spacing:-0.5pt;">���κ���+���� Ÿ��Ʋ ��� ���� ����</span></label><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="font_blue">(���ø����� ����)</span>
						</td>
					</tr>
					</table>
					</TD>
				</TR>
				<TR>
					<TD height="1" colspan="2" bgcolor="#B9B9B9"></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr>
				<td height=10></td>
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
					<TD COLSPAN=3 width="100%" valign="top"  style="padding-top:8pt; padding-bottom:8pt; padding-left:4pt;" class="menual_bg">
					<table cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td width="20" align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td ><span class="font_dotline">���� ������ ����</span></td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td  class="space_top">
						- ���뼱���� �ϸ� ��ٷ� ���θ��� �ݿ��˴ϴ�.(���ø�, Easy ������ �ڵ�����)<br>
						<b>&nbsp;&nbsp;</b>�ݴ�� ���ø�, Easy �������� �� �����ϸ� ������������ �ڵ� �����˴ϴ�.<br>
						- ���� ������ -> [���������� ���뼱��] -> ������ ���ϴ� �κ��� üũ�ڽ� ����(�ݵ�� ����κ��� üũ�ؾ߸� ���θ��� �ݿ�)<br>
						- �⺻�� ���� �������� ������ ���(������ �����̶�) ���� üũ�� ���<br>
						<b>&nbsp;&nbsp;</b>��ٷ� ���θ��� [������ �غ����Դϴ�.]��� �ȳ��� �Բ� ���θ��� �󳻿����� ����˴ϴ�.
						</td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td  class="space_top">&nbsp; </td>
					</tr>
					<tr>
						<td width="20" align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td ><span class="font_dotline">�������</span></td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td  class="space_top">
						- ���������� ���� �����ǰ� ������̴� ���ø����� ����˴ϴ�.(���������� �� ������ ������)
						</td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td  class="space_top">&nbsp; </td>
					</tr>
					<tr>
						<td width="20" align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td ><span class="font_dotline">���� Ÿ��Ʋ ���������� ������ Ư��</span></td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td  class="space_top">
						- ��ü Ÿ��Ʋ�̹��� �߿��� ����� �̹����� ����ǰ� ������ �̹������� ����ϴ� ���ø��� Ÿ��Ʋ�� �����˴ϴ�.
						</td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td  class="space_top">&nbsp; </td>
					</tr>
					<tr>
						<td width="20" align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td ><span class="font_dotline">�÷��� ���</span></td>
					</tr>
					<tr>
						<td height="5" colspan="2"></td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td  class="space_top">
						<a href="https://www.microsoft.com/korea/windows/ie/ie6/activex/default.mspx" target=_blank><span class="font_orange">[IE ���� ���� ���� �ȳ�]</span></a><br>
						<a href="https://www.microsoft.com/korea/windows/ie/ie6/activex/activate/default.mspx" target=_blank><span class="font_orange">[ActiveX ��Ʈ�� Ȱ��ȭ ���̵�]</span></a>
						</td>
					</tr>
					<tr>
						<td height="5" colspan="2"></td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td  class="space_top">
						�� ��ũ�� ����� ���� 2006-04-12 ���� IE(Internet Explorer)�� �߿� ������Ʈ�� ����ƽ��ϴ�.<br>
						�������� �÷��� ����� ���ؼ� �Ʒ��� ������ �����ϼż� ����Ͻñ� �ٶ��ϴ�.
						</td>
					</tr>
					<tr>
						<td height="10" colspan="2"></td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td  class="space_top">
						- <b>���� ��� ���</b><br><span class="font_blue">
						&nbsp;&nbsp;&nbsp;&lt;script&gt;<br>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;flash_show("�÷������ϰ��","����ũ��","����ũ��");<br>
						&nbsp;&nbsp;&nbsp;&lt;/script&gt;</span>
						</td>
					</tr>
					<tr>
						<td height="10" colspan="2"></td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td  class="space_top">
						- <b>�� ��� ���(�Ķ���� �߰�)</b><br><span class="font_blue">
						&nbsp;&nbsp;&nbsp;&lt;script&gt;<br>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;embedobj=new embedcls();<br>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;embedobj.init("�÷������ϰ��","����ũ��","����ũ��");<br>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;embedobj.setparam("�Ķ���͸�","�Ķ���Ͱ�");<br>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;embedobj.setparam("�Ķ���͸�","�Ķ���Ͱ�");<br>
						<span style="line-height:5px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;.<br>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;.<br>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;.<br>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;.<br>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;.<br></span>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;embedobj.show();<br>
						&nbsp;&nbsp;&nbsp;&lt;/script&gt;</span>
						</td>
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