<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
INCLUDE ("access.php");

####################### ������ ���ٱ��� check ###############
$PageCode = "de-5";
$MenuCode = "design";
if (!$_usersession->isAllowedTask($PageCode)) {
	INCLUDE ("AccessDeny.inc.php");
	exit;
}
#########################################################

$type=$_POST["type"];
$body=$_POST["body"];
$intitle=$_POST["intitle"];

if($intitle=="Y") {
	$leftmenu="Y";
} else {
	$leftmenu="N";
}


$subject = 'MyPage ȭ��';

$insertKey = 'mypage';

// ��� / ����
if ( $type=="store" OR $type=="restore" ) {
	$MSG = adminDesingBackup ( $type, $insertKey, $body, $subject, '', '', $leftmenu );
	$MSG .= adminDesingBackup ( $type, 'design_mypage', 'U', $subject, '', '', '', 'tblshopinfo', 'design_mypage' );
	$onload="<script>alert(\"".$MSG."\");</script>";
}


if($type=="update" && strlen($body)>0) {

	$sql = "SELECT COUNT(*) as cnt FROM tbldesignnewpage WHERE type='mypage' ";
	$result=mysql_query($sql,get_db_conn());
	$row=mysql_fetch_object($result);
	if($row->cnt==0) {
		$sql = "INSERT tbldesignnewpage SET ";
		$sql.= "type		= 'mypage', ";
		$sql.= "subject		= 'MyPage ȭ��', ";
		$sql.= "leftmenu	= '".$leftmenu."', ";
		$sql.= "body		= '".$body."' ";
		mysql_query($sql,get_db_conn());
	} else {
		$sql = "UPDATE tbldesignnewpage SET ";
		$sql.= "leftmenu	= '".$leftmenu."', ";
		$sql.= "body		= '".$body."' ";
		$sql.= "WHERE type='mypage' ";
		mysql_query($sql,get_db_conn());
	}
	mysql_free_result($result);

	$sql = "UPDATE tblshopinfo SET design_mypage='U' ";
	mysql_query($sql,get_db_conn());
	DeleteCache("tblshopinfo.cache");
	$onload="<script>alert(\"MyPage ȭ�� ������ ������ �Ϸ�Ǿ����ϴ�.\");</script>";
} else if($type=="delete") {
	$sql = "DELETE FROM tbldesignnewpage WHERE type='mypage' ";
	mysql_query($sql,get_db_conn());

	$sql = "UPDATE tblshopinfo SET design_mypage='001' ";
	mysql_query($sql,get_db_conn());
	DeleteCache("tblshopinfo.cache");
	$onload="<script>alert(\"MyPage ȭ�� ������ ������ �Ϸ�Ǿ����ϴ�.\");</script>";
} else if($type=="clear") {
	$intitle="";
	$body="";
	$sql = "SELECT body FROM tbldesigndefault WHERE type='mypage' ";
	$result=mysql_query($sql,get_db_conn());
	if($row=mysql_fetch_object($result)) {
		$body=$row->body;
	}
	mysql_free_result($result);
}

if($type!="clear") {
	$body="";
	$intitle="";
	$sql = "SELECT leftmenu,body FROM tbldesignnewpage WHERE type='mypage' ";
	$result = mysql_query($sql,get_db_conn());
	if($row=mysql_fetch_object($result)) {
		$body=$row->body;
		$intitle=$row->leftmenu;
	} else {
		$intitle="Y";
	}
	mysql_free_result($result);
}

?>

<? INCLUDE "header.php"; ?>

<script type="text/javascript" src="lib.js.php"></script>
<SCRIPT LANGUAGE="JavaScript">
<!--
function CheckForm(type) {
	if(type=="update") {
		if(document.form1.body.value.length==0) {
			alert("MyPage ȭ�� ������ ������ �Է��ϼ���.");
			document.form1.body.focus();
			return;
		}
		document.form1.type.value=type;
		document.form1.submit();
	} else if(type=="delete") {
		if(confirm("MyPage ȭ�� �������� �����Ͻðڽ��ϱ�?")) {
			document.form1.type.value=type;
			document.form1.submit();
		}
	} else if(type=="clear") {
		alert("�⺻�� ���� �� [�����ϱ�]�� Ŭ���ϼ���. Ŭ�� �� �������� ����˴ϴ�.");
		document.form1.type.value=type;
		document.form1.submit();
	}

	// ���
	if(type=="store") {
		if(confirm("<?=$subject?> �������� ����Ͻðڽ��ϱ�?\n\n�������� �����̴ٸ� \"�����ϱ�\"�� ���� �Ͻ��� ����Ͻñ� �ٶ��ϴ�.\n���� ����� ����ҽ��� ��ü�մϴ�.")) {
			document.form1.type.value=type;
			document.form1.submit();
			return;
		}
	}
	// ����
	if(type=="restore") {
		if(confirm("<?=$subject?> �������� ������� �Ͻðڽ��ϱ�?\n\n���� �ϰ� �Ǹ� �ٷ� ������ ���� �˴ϴ�.")) {
			document.form1.type.value=type;
			document.form1.submit();
			return;
		}
	}

	// �̸�����
	if(type=="preview") {
		if(document.form1.body.value.length==0) {
			alert("MyPage ȭ�� ������ ������ �Է��ϼ���.");
			document.form1.body.focus();
			return;
		}
		document.form1.type.value='<?=$insertKey?>';
		document.form1.target="preview";
		document.form1.action="designPreview.php";
		document.form1.submit();
		document.form1.target="";
		document.form1.action="<?=$_SERVER[PHP_SELF]?>";
	}


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
			<? include ("menu_design.php"); ?>
			</td>

			<td></td>
			<td valign="top">




<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td height="29" colspan="3">
			<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td height="28" class="link" align="left" background="images/con_link_bg.gif"><img src="images/top_link_house.gif" border="0" valign="absmiddle">������ġ : �����ΰ��� &gt; ����������-������ ���� &gt; <span class="2depth_select">MyPage ȭ�� �ٹ̱�</span></td>
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
			<tr><td height="8"></td></tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/design_mypageview_title.gif" ALT=""></TD>
					</tr>
<tr>
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
					<TD width="100%" class="notice_blue">MyPage ȭ�� �������� �����Ӱ� ������ �Ͻ� �� �ֽ��ϴ�.</TD>
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
					<TD><IMG SRC="images/design_mypageview_stitle.gif" WIDTH="250" HEIGHT=31 ALT=""></TD>
					<TD width="100%" background="images/shop_basicinfo_stitle_bg.gif"></TD>
					<TD><IMG SRC="images/shop_basicinfo_stitle_end.gif" WIDTH=10 HEIGHT=31 ALT=""></TD>
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
					<TD width="100%" class="notice_blue">1) �Ŵ����� <b>��ũ�θ�ɾ�</b>�� �����Ͽ� ������ �ϼ���.</span><br>2) [�⺻������]+[�����ϱ�], [�����ϱ�]�ϸ� �⺻���ø����� ����(���������� �ҽ� ����)�� -> ���ø� �޴����� ���ϴ� ���ø� ����.<br>3) �⺻�� �����̳� �����ϱ� ���̵� ���ø� �����ϸ� ������������ �����˴ϴ�.(���������� �ҽ��� ������)</TD>
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
			<tr><td height="3"></td></tr>
			<form name=form1 action="<?=$_SERVER[PHP_SELF]?>" method=post>
			<input type=hidden name=type>
			<tr>
				<td style="padding-top:2px;"><textarea name=body style="WIDTH: 100%; HEIGHT: 300px" class="textarea"><?=htmlspecialchars($body)?></textarea><br><input type=checkbox name=intitle value="Y" <?if($intitle=="Y")echo"checked";?> style="BORDER-RIGHT: medium none; BORDER-TOP: medium none; BORDER-LEFT: medium none; BORDER-BOTTOM: medium none;"> <b><span style="letter-spacing:-0.5pt;"><span class="font_orange">�⺻ Ÿ��Ʋ �̹��� ���� - Ÿ��Ʋ ���� �κк��� ������ ����</span>(��üũ�� ���� Ÿ��Ʋ �̹��� ���������� ���� �����Ͽ� ���)</b></span></td>
			</tr>
			<tr><td height=10></td></tr>
			<tr>
				<td align="center"><a href="javascript:CheckForm('update');"><img src="images/botteon_save.gif" width="113" height="38" border="0"></a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:CheckForm('clear');"><img src="images/botteon_bok.gif" width="124" height="38" border="0" hspace="2"></a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:CheckForm('delete');"><img src="images/botteon_del.gif" width="113" height="38" border="0" hspace="0"></a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:CheckForm('preview');"><img src="images/botteon_prev.gif" width="113" height="38" border="0" hspace="2"></a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:CheckForm('store');"><img src="images/botteon_store.gif" border="0" hspace="2" alt="����ϱ�"></a>&nbsp;&nbsp;&nbsp;<a href="javascript:CheckForm('restore');"><img src="images/botteon_restore.gif" border="0" hspace="2" alt="��������ϱ�"></a></td>
			</tr>
			</form>
			<tr><td height=20></td></tr>
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
						<td ><p class="LIPoint"><b><span class="font_orange">MyPage ��ũ�θ�ɾ�</span></b>(�ش� ��ũ�θ�ɾ�� �ٸ� ������ ������ �۾��� ����� �Ұ�����)</p></td>
					</tr>
					<tr>
						<td width="20" align="right" valign="top"><p>&nbsp;</p></td>
						<td >
						<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
						<col width=150></col>
						<col width=></col>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell colspan=2 align=center>
							<B>���������� �޴����� ��ũ�� ����</B>
							</td>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[MENU_MYHOME]</td>
							<td class=td_con1 style="padding-left:5;">
							���������� Ȩ <FONT class=font_blue>(��:&lt;a href=[MENU_MYHOME]>���������� Ȩ&lt;/a>)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[MENU_MYORDER]</td>
							<td class=td_con1 style="padding-left:5;">
							�ֹ����� <FONT class=font_blue>(��:&lt;a href=[MENU_MYORDER]>�ֹ�����&lt;/a>)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[MENU_MYPERSONAL]</td>
							<td class=td_con1 style="padding-left:5;">
							1:1���Խ��� <FONT class=font_blue>(��:&lt;a href=[MENU_MYPERSONAL]>1:1���Խ���&lt;/a>)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[MENU_MYWISH]</td>
							<td class=td_con1 style="padding-left:5;">
							���ø���Ʈ <FONT class=font_blue>(��:&lt;a href=[MENU_MYWISH]>���ø���Ʈ&lt;/a>)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[MENU_MYRESERVE]</td>
							<td class=td_con1 style="padding-left:5;">
							������ <FONT class=font_blue>(��:&lt;a href=[MENU_MYRESERVE]>������&lt;/a>)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[MENU_MYCOUPON]</td>
							<td class=td_con1 style="padding-left:5;">
							���� <FONT class=font_blue>(��:&lt;a href=[MENU_MYCOUPON]>����&lt;/a>)</font>
							</td>
						</tr>
						<? if($_shopdata->recom_url_ok == "Y" || $_shopdata->sns_ok == "Y"){ ?>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[MENU_PROMOTE]</td>
							<td class=td_con1 style="padding-left:5;">
							ȫ������ <FONT class=font_blue>(��:&lt;a href=[MENU_PROMOTE]>ȫ������&lt;/a>)</font>
							</td>
						</tr>
						<? } ?>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[MENU_GONGGU]</td>
							<td class=td_con1 style="padding-left:5;">
							�������� <FONT class=font_blue>(��:&lt;a href=[MENU_GONGGU]>��������&lt;/a>)</font>
							</td>
						</tr>
						<? if(getVenderUsed()==true) { ?>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[MENU_MYCUSTSECT]</td>
							<td class=td_con1 style="padding-left:5;">
							�ܰ���� <FONT class=font_blue>(��:&lt;a href=[MENU_MYCUSTSECT]>�ܰ����&lt;/a>)</font>
							</td>
						</tr>
						<? } ?>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[MENU_MYINFO]</td>
							<td class=td_con1 style="padding-left:5;">
							ȸ���������� <FONT class=font_blue>(��:&lt;a href=[MENU_MYINFO]>ȸ����������&lt;/a>)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[MENU_MYOUT]</td>
							<td class=td_con1 style="padding-left:5;">
							ȸ��Ż�� <FONT class=font_blue>(��:&lt;a href=[MENU_MYOUT]>ȸ��Ż��&lt;/a>)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr><td colspan=2 height=10></td></tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell colspan=2 align=center>
							<B>ȸ������ ��ũ�� ����</B>
							</td>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[ID]</td>
							<td class=td_con1 style="padding-left:5;">
							ȸ�� ���̵�
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[NAME]</td>
							<td class=td_con1 style="padding-left:5;">
							ȸ�� �̸�
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[EMAIL]</td>
							<td class=td_con1 style="padding-left:5;">
							ȸ�� �̸��� <FONT class=font_blue>(��:&lt;a href="mailto:[EMAIL]">[EMAIL]&lt;/a>)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[ADDRESS1]</td>
							<td class=td_con1 style="padding-left:5;">
							�⺻�ּ�
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[ADDRESS2]</td>
							<td class=td_con1 style="padding-left:5;">
							���ּ�
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[TEL]</td>
							<td class=td_con1 style="padding-left:5;">
							����ȭ
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[MOBILE]</td>
							<td class=td_con1 style="padding-left:5;">
							�޴���ȭ
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[RESERVE]</td>
							<td class=td_con1 style="padding-left:5;">
							���� ������ <FONT class=font_blue>(��:[RESERVE]��)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[RESERVE_MORE]</td>
							<td class=td_con1 style="padding-left:5;">
							������ ���� ���� <FONT class=font_blue>(��:&lt;a href=[RESERVE_MORE]>������ ���� ����&lt;/a>)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[COUPON]</td>
							<td class=td_con1 style="padding-left:5;">
							���� ������ <FONT class=font_blue>(��:[COUPON]��)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[COUPON_MORE]</td>
							<td class=td_con1 style="padding-left:5;">
							���� ���� ��ȸ <FONT class=font_blue>(��:&lt';a href=[COUPON_MORE]>���� ���� ����&lt;/a>)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[GIFT_AUTH]</td>
							<td class=td_con1 style="padding-left:5;">
							��ǰ�� ���� <FONT class=font_blue>(��:&lt';a href=[GIFT_AUTH]> ���� &lt;/a>)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>

						<tr>
							<td class=table_cell align=right style="padding-right:15">[ORDERCOUNT]</td>
							<td class=td_con1 style="padding-left:5;">
							�ֹ���Ȳ �Ǽ� <FONT class=font_blue>(��:[ORDERCOUNT]��)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[DELIREADY]</td>
							<td class=td_con1 style="padding-left:5;">
							�߼��غ� �Ǽ� <FONT class=font_blue>(��:[DELIREADY]��)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[DELICOMPLATE]</td>
							<td class=td_con1 style="padding-left:5;">
							�߼ۿϷ� �Ǽ� <FONT class=font_blue>(��:[DELICOMPLATE]��)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[REFUND]</td>
							<td class=td_con1 style="padding-left:5;">
							ȯ�ҽ�û �Ǽ� <FONT class=font_blue>(��:[REFUND]��)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[REPAYMENT]</td>
							<td class=td_con1 style="padding-left:5;">
							ȯ�ҿϷ� �Ǽ� <FONT class=font_blue>(��:[REPAYMENT]��)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>


						<tr><td colspan=2 height=10></td></tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell colspan=2 align=center>
							<B>Ư��ȸ�� ���� ��ũ�� ����</B>
							</td>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell width=180 align=right style="padding-right:15" nowrap>[IFROYAL]<br>[IFENDROYAL]</td>
							<td class=td_con1 width=100% style="padding-left:5;">
							Ư��ȸ���� ���� ���� ��� (Ư��ȸ���� ��쿡�� ���� ���)
							<pre style="line-height:15px">
<FONT class=font_blue>   <B>[IFROYAL]</B>
      ����
   <B>[IFENDROYAL]</B></font></pre>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[ROYAL_IMG]</td>
							<td class=td_con1 width=100% style="padding-left:5;">
							Ư��ȸ�� �̹��� ǥ��
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[ROYAL_MSG1]</td>
							<td class=td_con1 width=100% style="padding-left:5;">
							Ư��ȸ�� ���� �޼���1 - �ڵ����
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell width=180 align=right style="padding-right:15" nowrap>[IFROYALMSG2]<br>[IFENDROYALMSG2]</td>
							<td class=td_con1 width=100% style="padding-left:5;">
							��� �Ӽ��� ���� ����� �޼���2�� �ʿ���� ������ ����ؾ���
							<pre style="line-height:15px">
<FONT class=font_blue>   <B>[IFROYALMSG2]</B>
      ���� (��:[ROYAL_MSG2])
   <B>[IFENDROYALMSG2]</B></font></pre>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[ROYAL_MSG2]</td>
							<td class=td_con1 style="padding-left:5;">
							Ư��ȸ�� ���� �޼���2 <FONT class=font_blue>(��:[IFROYALMSG2] [ROYAL_MSG2] [IFENDROYALMSG2])</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>

						<tr>
							<td class=table_cell align=right style="padding-right:15">[IF_MEMBER_S]<br />[IF_MEMBER_E]</td>
							<td class=td_con1 style="padding-left:5;">
								ȸ����� �߰����� ����/��<br />
								<FONT class=font_blue>
									��� ��:<br />
									<b>[IF_MEMBER_S]</b><br />
									&nbsp;&nbsp;[NEXT_GRP_NAME] ��ޱ��� ���� ���űݾ� : [NEXT_GRP_R_PRICE]��<br />
									<b>[IF_MEMBER_E]</b>
								</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[NEXT_GRP_NAME]</td>
							<td class=td_con1 style="padding-left:5;">
								���� ���� ȸ�� ��� ��Ī <FONT class=font_blue>(��: ���� ȸ�� ��� : [NEXT_GRP_NAME])</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[NEXT_GRP_R_PRICE]</td>
							<td class=td_con1 style="padding-left:5;">
								���� ���űݾ� <FONT class=font_blue>(��: ���� ���űݾ� : [NEXT_GRP_R_PRICE])</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[NEXT_GRP_R_CNT]</td>
							<td class=td_con1 style="padding-left:5;">
								���� ���ŰǼ� <FONT class=font_blue>(��: ���� ���ŰǼ� : [NEXT_GRP_R_CNT])</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[NEXT_GRP_DATE_S]</td>
							<td class=td_con1 style="padding-left:5;">
								���űⰣ(�������űݾ� �����Ⱓ) ����
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[NEXT_GRP_DATE_E]</td>
							<td class=td_con1 style="padding-left:5;">
								���űⰣ(�������űݾ� �����Ⱓ) ��
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[NEXT_GRP_CNT]</td>
							<td class=td_con1 style="padding-left:5;">
								���ŰǼ�
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[NEXT_GRP_KEEP]</td>
							<td class=td_con1 style="padding-left:5;">
								��������Ⱓ
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[NEXT_GRP_PRICE]</td>
							<td class=td_con1 style="padding-left:5;">
								���űݾ�
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>





						<tr><td colspan=2 height=10></td></tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell colspan=2 align=center>
							<B>SNS ä�� �� ȫ��URL���� ��ũ�� ����</B>
							</td>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell width=180 align=right style="padding-right:15" nowrap>[IFHONGBO]<br>[IFENDHONGBO]</td>
							<td class=td_con1 width=100% style="padding-left:5;">
							ȫ��URL ���� �ּ� �� �����ݰ�������
							<pre style="line-height:15px">  <FONT class=font_blue><B>[IFHONGBO]</B>
����(��:
	[NAME]���� �Ұ��� ������� &lt;B>[MEMBERCNT]��&lt;/B>�� ģ���� �����Ͽ����ϴ�.
	[NAME]�Ը��� ������ URL �ּҴ� &lt;B>[MEMHONGBOURL]&lt;/B>�Դϴ�. &lt;a href=[HONGBOPOPUP]>�ҹ�����&lt;a>
	[MEMADDRESERVE]
	)
   <B>[IFENDHONGBO]</B></font></pre>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[MEMBERCNT]</td>
							<td class=td_con1 width=100% style="padding-left:5;">���� ��õ�� �ο��� </td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[MEMHONGBOURL]</td>
							<td class=td_con1 width=100% style="padding-left:5;">���� ȫ��URL�ּ� </td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[HONGBOPOPUP]</td>
							<td class=td_con1 width=100% style="padding-left:5;">�ҹ����� ��â��ũ </td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[MEMADDRESERVE]</td>
							<td class=td_con1 width=100% style="padding-left:5;">��õ�� ���� �߰������� ���� </td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell width=180 align=right style="padding-right:15" nowrap>[MEMSNSINFO]</td>
							<td class=td_con1 width=100% style="padding-left:5;"> SNS ä�� ����</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr><td colspan=2 height=10></td></tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell colspan=2 align=center>
							<B>�ֱ� �ֹ����� ���� ��ũ�� ����</B>
							</td>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[ORDTAB1_�⺻�̹���URL_���õ��̹���URL]</td>
							<td class=td_con1 style="padding-left:5;">
							�Ϲݻ�ǰ�ֹ���ȸ �̹��� ��ư
							<br><FONT class=font_blue>(��:[ORDTAB1_/<?=RootPath.DataDir?>design/menutab01off.gif_/<?=RootPath.DataDir?>design/menutab01on.gif])</font>
							<br>
							�̹��� URL�� "_" ���Ұ�
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[ORDTAB2_�⺻�̹���URL_���õ��̹���URL]</td>
							<td class=td_con1 style="padding-left:5;">
							���������ֹ���ȸ �̹��� ��ư
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[ORDTAB3_�⺻�̹���URL_���õ��̹���URL]</td>
							<td class=td_con1 style="padding-left:5;">
							��ǰ���ֹ���ȸ �̹��� ��ư
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[IFGIFTCARD]<br>[IFELSEGIFTCARD]<br>[IFENDGIFTCARD]</td>
							<td class=td_con1 style="padding-left:5;">
							��ǰ���ֹ� �ʵ� ���
							<br><FONT class=font_blue>(��:
							<br>&lt;td>[IFGIFTCARD]����ó������[IFELSEGIFTCARD]��ۻ���[IFENDGIFTCARD]&lt;/td>
							<br>&lt;td>[IFGIFTCARD]������ȣ[IFELSEGIFTCARD]�������[IFENDGIFTCARD]&lt;/td></font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[ORDER_MORE]</td>
							<td class=td_con1 width=100% style="padding-left:5;">
							�ֱ� �ֹ����� ��ü���� <FONT class=font_blue>(��:&lt;a href=[ORDER_ALL]>��ü����&lt;/a>)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell width=180 align=right style="padding-right:15" nowrap>[IFORDER]<br>[IFELSEORDER]<br>[IFENDORDER]</td>
							<td class=td_con1 width=100% style="padding-left:5;">
							�ֹ� ������ ���� ���� ���� ���
							<pre style="line-height:15px">
<FONT class=font_blue>   <B>[IFORDER]</B>
      �ֹ� ������ <FONT COLOR="red"><B>����</B></FONT> ����� ����
   <B>[IFELSEORDER]</B>
      �ֹ� ������ <FONT COLOR="red"><B>����</B></FONT> ����� ����
   <B>[IFENDORDER]</B></font></pre>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell width=180 align=right style="padding-right:15" nowrap>[FORORDER]<br>[FORENDORDER]</td>
							<td class=td_con1 width=100% style="padding-left:5;">
							[FORORDER] �ֹ� ���� �ϳ��� ���� ���� [FORENDORDER]
							<pre style="line-height:15px">
<FONT class=font_blue>   [IFORDER]
       <B>[FORORDER]</B>�ֹ� ���� �ϳ��� ���� ���� ���<B>[FORENDORDER]</B>
   [IFELSEORDER]
       �ֹ������� �����ϴ�.
   [IFENDORDER]</font></pre>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[FORPRODUCT]<br>[FORENDPRODUCT]</td>
							<td class=td_con1 style="padding-left:5;">
							[FORPRODUCT] �ֹ����� �ϳ��� ���� ��ǰ��� �� ������� ���� [FORENDPRODUCT]
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[ORDER_NAME]</td>
							<td class=td_con1 style="padding-left:5;">
							�ֹ���ǰ�� - [FORPRODUCT] [FORENDPRODUCT] ���뿡 ���
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[ORDER_DELISTAT]</td>
							<td class=td_con1 style="padding-left:5;">
							��ۻ��� - [FORPRODUCT] [FORENDPRODUCT] ���뿡 ���
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[IFDELISEARCH]<br>[IFELSEDELISEARCH]<br>[IFENDDELISEARCH]</td>
							<td class=td_con1 style="padding-left:5;">
							��� �Ϸ�� ��ǰ�� ��������� ���� ���� ���� ��� - [FORPRODUCT] [FORENDPRODUCT]�� ���
							<pre style="line-height:15px">
<font class=font_blue>   <B>[IFDELISEARCH]</B>
	   ��� �Ϸ�� ��ǰ�� ��������� <FONT COLOR="red"><B>����</B></FONT> ����� ����
   <B>[IFELSEDELISEARCH]</B>
	   ��� �Ϸ�� ��ǰ�� ��������� <FONT COLOR="red"><B>����</B></FONT> ����� ����
   <B>[IFENDDELISEARCH]</B></font></pre>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[ORDER_DELICOM]</td>
							<td class=td_con1 style="padding-left:5;">
							�ù��� - �� [IFDELISEARCH]�� ���
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[ORDER_DELISEARCH]</td>
							<td class=td_con1 style="padding-left:5;">
							������� ��ư (��:&lt;a href=[ORDER_DELISEARCH]>�������&lt;/a>) - �� [IFDELISEARCH]�� ���
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[ORDER_DATE]</td>
							<td class=td_con1 style="padding-left:5;">
							�ֹ�����
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[ORDER_METHOD]</td>
							<td class=td_con1 style="padding-left:5;">
							�������
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[ORDER_PRICE]</td>
							<td class=td_con1 style="padding-left:5;">
							�����ݾ� <FONT class=font_blue>(��:[ORDER_PRICE]��)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[ORDER_DETAIL]</td>
							<td class=td_con1 style="padding-left:5;">
							�ֹ��� ��ư �Լ� <FONT class=font_blue>(��:&lt;a href=[ORDER_DETAIL]>�ֹ�����ȸ&lt;/a>)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[ORDER_DELI]</td>
							<td class=td_con1 style="padding-left:5;">
							�����Ȳ ��ư �Լ� <FONT class=font_blue>(��:&lt;a href=[ORDER_DELI]>�����Ȳ��ȸ&lt;/a>)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[REVIEW_WRITE]</td>
							<td class=td_con1 style="padding-left:5;">��ǰ�� �ۼ��ϱ� ��ư</td>
						</tr>


						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr><td colspan=2 height=10></td></tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell colspan=2 align=center>
							<B>�ֱ� ���ǳ��� ���� ��ũ�� ����</B>
							</td>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[PERSONAL_MORE]</td>
							<td class=td_con1 width=100% style="padding-left:5;">
							�ֱ� ���ǳ��� ��ü���� <FONT class=font_blue>(��:&lt;a href=[PERSONAL_MORE]>��ü����&lt;/a>)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell width=180 align=right style="padding-right:15" nowrap>[IFPERSONAL]<br>[IFELSEPERSONAL]<br>[IFENDPERSONAL]</td>
							<td class=td_con1 width=100% style="padding-left:5;">
							���� ������ ���� ���� ���� ���
							<pre style="line-height:15px">
<FONT class=font_blue>   <B>[IFPERSONAL]</B>
      ���� ������ <FONT COLOR="red"><B>����</B></FONT> ����� ����
   <B>[IFELSEPERSONAL]</B>
      ���� ������ <FONT COLOR="red"><B>����</B></FONT> ����� ����
   <B>[IFENDPERSONAL]</B></font></pre>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell width=180 align=right style="padding-right:15" nowrap>[FORPERSONAL]<br>[FORENDPERSONAL]</td>
							<td class=td_con1 width=100% style="padding-left:5;">
							[FORPERSONAL] ���� ���� �ϳ��� ���� ���� [FORENDPERSONAL]
							<pre style="line-height:15px">
<FONT class=font_blue>   [IFPERSONAL]
       <B>[FORPERSONAL]</B>���� ���� �ϳ��� ���� ���� ���<B>[FORENDPERSONAL]</B>
   [IFELSEPERSONAL]
       ���ǳ����� �����ϴ�.
   [IFENDPERSONAL]</font></pre>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[PERSONAL_DATE]</td>
							<td class=td_con1 style="padding-left:5;">
							���ǳ�¥
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[PERSONAL_SUBJECT]</td>
							<td class=td_con1 style="padding-left:5;">
							��������
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[PERSONAL_REPLY]</td>
							<td class=td_con1 style="padding-left:5;">
							�亯����
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[PERSONAL_REDATE]</td>
							<td class=td_con1 style="padding-left:5;">
							�亯��¥
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>

						<tr><td colspan=2 height=10></td></tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell colspan=2 align=center>
							<B>���ø���Ʈ ���� ��ũ�� ����</B>
							</td>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[WISH_MORE]</td>
							<td class=td_con1 width=100% style="padding-left:5;">
							���ø���Ʈ ��ü���� <FONT class=font_blue>(��:&lt;a href=[WISH_MORE]>��ü����&lt;/a>)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[WISH_LIST???]</td>
							<td class=td_con1 style="padding-left:5;">
							���ø���Ʈ ���
									<br><img width=10 height=0>
									<FONT class=font_orange>? : ��ǰ ǥ�� ���� (1~8)</FONT>
									<br><img width=10 height=0>
									<FONT class=font_orange>? : ��ǰ ���߰��� ǥ�ÿ���(Y/N)</FONT>
									<br><img width=10 height=0>
									<FONT class=font_orange>? : ��ǰ ������ ǥ�ÿ���(Y/N)</FONT>
									<br>
									<FONT class=font_blue>��) [WISH_LIST5NY]</FONT>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>

						<tr><td colspan=2 height=10></td></tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell colspan=2 align=center>
							<B>�������ſ��� ��ũ�� ����</B>
							</td>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[RECIVEMAIL]</td>
							<td class=td_con1 style="padding-left:5px;">�̸��� ���ſ���</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[RECIVESMS]</td>
							<td class=td_con1 style="padding-left:5px;">SMS ���ſ���</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[LASTLOGIN]</td>
							<td class=td_con1 style="padding-left:5px;">������ �α��� �ð�</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>

						</table>
						</td>
					</tr>
					<tr>
						<td width="20" colspan="2"><p>&nbsp;</p></td>
					</tr>
					<tr>
						<td width="20" align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td ><p class="LIPoint">����,�帲�������� �����ͷ� �ۼ��� �̹�����ε� �۾������� Ʋ���� �� ������ �����ϼ���!</p></td>
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
<script>
function prevPage(){
	if(document.form1.body.value.length==0) {
		alert("������ ������ �Է��ϼ���.");
		document.form1.body.focus();
		return;
	}

	f = document.prevForm;
	f.mode.value = 'mypage';
	f.code.value = document.form1.body.value;
	f.submit();
}
</script>

<form name="prevForm" method="post" action="design_prev_post.php" target="_blank">
	<input type="hidden" name="code">
	<input type="hidden" name="mode">
</form>


<?=$onload?>

<? INCLUDE "copyright.php"; ?>