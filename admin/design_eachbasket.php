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


$subject = '��ٱ��� ȭ��';

$insertKey = 'basket';

// ��� / ����
if ( $type=="store" OR $type=="restore" ) {
	$MSG = adminDesingBackup ( $type, 'basket', $body, $subject, '', '', $leftmenu );
	$MSG .= adminDesingBackup ( $type, 'design_basket', 'U', $subject, '', '', '', 'tblshopinfo', 'design_basket' );
	$onload="<script>alert(\"".$MSG."\");</script>";
}



if($type=="update" && strlen($body)>0) {

	$sql = "SELECT COUNT(*) as cnt FROM tbldesignnewpage WHERE type='basket' ";
	$result=mysql_query($sql,get_db_conn());
	$row=mysql_fetch_object($result);
	if($row->cnt==0) {
		$sql = "INSERT tbldesignnewpage SET ";
		$sql.= "type		= 'basket', ";
		$sql.= "subject		= '��ٱ��� ȭ��', ";
		$sql.= "leftmenu	= '".$leftmenu."', ";
		$sql.= "body		= '".$body."' ";
		mysql_query($sql,get_db_conn());
	} else {
		$sql = "UPDATE tbldesignnewpage SET ";
		$sql.= "leftmenu	= '".$leftmenu."', ";
		$sql.= "body		= '".$body."' ";
		$sql.= "WHERE type='basket' ";
		mysql_query($sql,get_db_conn());
	}
	mysql_free_result($result);

	$sql = "UPDATE tblshopinfo SET design_basket='U' ";
	mysql_query($sql,get_db_conn());
	DeleteCache("tblshopinfo.cache");
	$onload="<script>alert(\"��ٱ��� ȭ�� ������ ������ �Ϸ�Ǿ����ϴ�.\");</script>";
} else if($type=="delete") {
	$sql = "DELETE FROM tbldesignnewpage WHERE type='basket' ";
	mysql_query($sql,get_db_conn());

	$sql = "UPDATE tblshopinfo SET design_basket='001' ";
	mysql_query($sql,get_db_conn());
	DeleteCache("tblshopinfo.cache");
	$onload="<script>alert(\"��ٱ��� ȭ�� ������ ������ �Ϸ�Ǿ����ϴ�.\");</script>";
} else if($type=="clear") {
	$intitle="";
	$body="";
	$sql = "SELECT body FROM tbldesigndefault WHERE type='basket' ";
	$result=mysql_query($sql,get_db_conn());
	if($row=mysql_fetch_object($result)) {
		$body=$row->body;
	}
	mysql_free_result($result);
}

if($type!="clear") {
	$body="";
	$intitle="";
	$sql = "SELECT leftmenu,body FROM tbldesignnewpage WHERE type='basket' ";
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
			alert("��ٱ��� ȭ�� ������ ������ �Է��ϼ���.");
			document.form1.body.focus();
			return;
		}
		document.form1.type.value=type;
		document.form1.submit();
	} else if(type=="delete") {
		if(confirm("��ٱ��� ȭ�� �������� �����Ͻðڽ��ϱ�?")) {
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
			alert("��ǰ�˻� ���ȭ�� ������ ������ �Է��ϼ���.");
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

//��ũ�� ����(�˾�)
function macroview(){
	window.open("http://www.getmall.co.kr/macro/pages/basket_macro.html","basket_macro","height=800,width=680,scrollbars=no");
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
				<td height="28" class="link" align="left" background="images/con_link_bg.gif"><img src="images/top_link_house.gif" border="0" valign="absmiddle">������ġ : �����ΰ��� &gt; ����������-������ ���� &gt; <span class="2depth_select">��ٱ��� ȭ�� �ٹ̱�</span></td>
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
							<TD><IMG SRC="images/design_jang_title.gif" ALT=""></TD>
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
					<TD width="100%" class="notice_blue">��ٱ��� ȭ�� �������� �����Ӱ� ������ �Ͻ� �� �ֽ��ϴ�.</TD>
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
					<TD><IMG SRC="images/design_jang_stitle.gif" WIDTH="250" HEIGHT=31 ALT=""></TD>
					<TD width="100%" background="images/shop_basicinfo_stitle_bg.gif">
						&nbsp;&nbsp;<a href="javascript:macroview();"><img src="images/btn_macroview.gif" border="0" align="absmiddle" alt="" /></a>&nbsp;&nbsp;<a href="http://www.getmall.co.kr/macro/data/basket_design.zip"><img src="images/btn_defaultcode.gif" border="0" align="absmiddle" alt="" />
					</TD>
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
				<td style="padding-top:2px;"><textarea name=body style="WIDTH: 100%; HEIGHT: 300px" class="textarea"><?=htmlspecialchars($body)?></textarea><br><input type=checkbox name=intitle value="Y" <?if($intitle=="Y")echo"checked";?>> <b><span style="letter-spacing:-0.5pt;"><span class="font_orange">�⺻ Ÿ��Ʋ �̹��� ���� - Ÿ��Ʋ ���� �κк��� ������ ����</span>(��üũ�� ���� Ÿ��Ʋ �̹��� ���������� ���� �����Ͽ� ���)</b></span></td>
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
					<TD COLSPAN=3 width="100%" valign="top" bgcolor="white" style="padding-top:8pt; padding-bottom:8pt; padding-left:4pt;"  class="menual_bg">
					<table cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td width="20"></td>
						<td >
						<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
						<col width=200></col>
						<col width=></col>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell colspan=2 align=center bgcolor=#F0F0F0>
							<B>�������� ���� ��ũ�� ����</B>
							</td>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15"><B>[ONE_START]</B></td>
							<td class=td_con1 style="padding-left:5;">
							�������� ���� (�������� ���� ù�κп� �� ������)
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[ONE_CODEA_���ùڽ� ��Ÿ��]</td>
							<td class=td_con1 style="padding-left:5;">
							�������� 1�� ī�װ� ���ùڽ� <FONT class=font_blue>(��:[ONE_CODEA_width:150px;color:#000000;font-size:11px])</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[ONE_CODEB_���ùڽ� ��Ÿ��]</td>
							<td class=td_con1 style="padding-left:5;">
							�������� 2�� ī�װ� ���ùڽ� <FONT class=font_blue>(��:[ONE_CODEB_width:150px;color:#000000;font-size:11px])</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[ONE_CODEC_���ùڽ� ��Ÿ��]</td>
							<td class=td_con1 style="padding-left:5;">
							�������� 3�� ī�װ� ���ùڽ� <FONT class=font_blue>(��:[ONE_CODEC_width:150px;color:#000000;font-size:11px])</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[ONE_CODED_���ùڽ� ��Ÿ��]</td>
							<td class=td_con1 style="padding-left:5;">
							�������� 4�� ī�װ� ���ùڽ� <FONT class=font_blue>(��:[ONE_CODED_width:150px;color:#000000;font-size:11px])</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[ONE_PRLIST_���ùڽ� ��Ÿ��]</td>
							<td class=td_con1 style="padding-left:5;">
							�������� ��ǰ ����Ʈ ���ùڽ� <FONT class=font_blue>(��:[ONE_PRLIST_width:350px;color:#000000;font-size:11px])</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[ONE_PRIMG]</td>
							<td class=td_con1 style="padding-left:5;">
							�������� ��ǰ�̹���
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[ONE_BASKET]</td>
							<td class=td_con1 style="padding-left:5;">
							�������� ��ٱ��� ��� <FONT class=font_blue>(��:&lt;a href=[ONE_BASKET]>��ٱ��� ���&lt;/a>)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15"><B>[ONE_END]</B></td>
							<td class=td_con1 style="padding-left:5;">
							�������� �� (�������� ���� ������ �κп� �� ������)
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr><td colspan=2 height=5></td></tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell colspan=2 align=center bgcolor=#F0F0F0>
							<B>��ٱ��� ��ǰ ���� ��ũ�� ����</B>
							</td>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell width=180 align=right style="padding-right:15">[IFBASKET]<br>[IFELSEBASKET]<br>[IFENDBASKET]</td>
							<td class=td_con1 width=100% style="padding-left:5;">
							��ٱ��Ͽ� ��ǰ�� ���� ���� ���� ���
							<pre style="line-height:15px">
<font class=font_blue>   <B>[IFBASKET]</B>
      ��ٱ��Ͽ� ��ǰ�� <FONT COLOR="red"><B>����</B></FONT> ����� ����
   <B>[IFELSEBASKET]</B>
      ��ٱ��Ͽ� ��ǰ�� <FONT COLOR="red"><B>����</B></FONT> ����� ����
   <B>[IFENDBASKET]</B></font></pre>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell width=180 align=right style="padding-right:15">[FORBASKET]<br>[FORENDBASKET]</td>
							<td class=td_con1 width=100% style="padding-left:5;">
							[FORBASKET] ��ٱ��� ��ǰ �Ѱ��� ���� ���� ���[FORENDBASKET]
							<pre style="line-height:15px">
<font class=font_blue>   [IFBASKET]
       <B>[FORBASKET]</B>��ǰ �ϳ��� ���� ���� ���<B>[FORENDBASKET]</B>
   [IFELSEBASKET]
       ��ٱ��Ͽ� ��� ��ǰ�� �����ϴ�.
   [IFENDBASKET]</font></pre>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[ITEM_CHKBOX]</td>
							<td class=td_con1 width=100% style="padding-left:5;">��ٱ��� ��ǰ���� üũ�ڽ�</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[BASKET_PRIMG]</td>
							<td class=td_con1 width=100% style="padding-left:5;">
							��ǰ�̹���
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[BASKET_PRNAME]</td>
							<td class=td_con1 width=100% style="padding-left:5;">
							��ǰ��
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[BASKET_ADDCODE1]</td>
							<td class=td_con1 width=100% style="padding-left:5;">
							��ǰ Ư���� ("-"����)
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[BASKET_ADDCODE2]</td>
							<td class=td_con1 width=100% style="padding-left:5;">
							��ǰ Ư���� ("-"������)
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[BASKET_RESERVE]</td>
							<td class=td_con1 width=100% style="padding-left:5;">
							������ <FONT class=font_blue>(��:[BASKET_RESERVE]��)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[BASKET_SELLPRICE]</td>
							<td class=td_con1 width=100% style="padding-left:5;">
							��ǰ���� <FONT class=font_blue>(��:[BASKET_SELLPRICE]��)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[CATE_AUTH_ICON]</td>
							<td class=td_con1 width=100% style="padding-left:5;">������� ������ ǥ��</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[BASKET_QUANTITY]</td>
							<td class=td_con1 width=100% style="padding-left:5;">
							���� �Է¹ڽ�
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[BASKET_QUP]</td>
							<td class=td_con1 width=100% style="padding-left:5;">
							�������� �Լ� <FONT class=font_blue>(��:&lt;a href=[BASKET_QUP]>��������&lt;/a>)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[BASKET_QDN]</td>
							<td class=td_con1 width=100% style="padding-left:5;">
							�������� �Լ� <FONT class=font_blue>(��:&lt;a href=[BASKET_QDN]>��������&lt;/a>)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[BASKET_QUPDATE]</td>
							<td class=td_con1 width=100% style="padding-left:5;">
							��������(����) <FONT class=font_blue>(��:&lt;a href=[BASKET_QUPDATE]>����&lt;/a>)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[BASKET_PRICE]</td>
							<td class=td_con1 width=100% style="padding-left:5;">
							�ֹ��ݾ� <FONT class=font_blue>(��:[BASKET_PRICE]��)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[DELI_STR]</td>
							<td class=td_con1 width=100% style="padding-left:5;">��ۺ�</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[COUPON_LIST]</td>
							<td class=td_con1 width=100% style="padding-left:5;">���� ����Ʈ</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[BASKET_WISHLIST]</td>
							<td class=td_con1 width=100% style="padding-left:5;">
							���ø���Ʈ ��� ��ư <FONT class=font_blue>(��:&lt;a href=[BASKET_WISHLIST]>���ø���Ʈ ���&lt;/a>)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[BASKET_DEL]</td>
							<td class=td_con1 width=100% style="padding-left:5;">
							��ٱ��Ͽ��� ���� ��ư <FONT class=font_blue>(��:&lt;a href=[BASKET_DEL]>����&lt;/a>)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[BASKET_ETCIMG]</td>
							<td class=td_con1 width=100% style="padding-left:5;">
							��ǰƯ�̻��� ���
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell width=180 align=right style="padding-right:15">[IFOPTION]<br>[IFENDOPTION]</td>
							<td class=td_con1 width=100% style="padding-left:5;">
							��ٱ��� ��ǰ�ɼ� ó�� (�ɼ��� ���� ��쿡�� �ɼǳ��� ���)
							<pre style="line-height:15px">
<FONT class=font_blue>   <B>[IFOPTION]</B>
      ��ǰ�ɼ� ���� ��) [BASKET_OPTION]
   <B>[IFENDOPTION]</B></font></pre>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[BASKET_OPTION]</td>
							<td class=td_con1 width=100% style="padding-left:5;">
							��ǰ�ɼǳ��� <FONT class=font_blue>(��:�ɼ� : [BASKET_OPTION])</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[BASKET_PRODUCTPRICE]</td>
							<td class=td_con1 width=100% style="padding-left:5;">
							��ǰ �հ�ݾ� <FONT class=font_blue>(��:��ǰ �հ�ݾ� : [BASKET_PRODUCTPRICE]��)</font>
							</td>
						</tr>
						<!--
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell width=180 align=right style="padding-right:15">[IFPACKAGE]<br>[IFENDPACKAGE]</td>
							<td class=td_con1 width=100% style="padding-left:5;">
							��ٱ��� ��ǰ ��Ű�� ó�� (��Ű���� ���� ��쿡�� ��Ű�� ���� ���)
							<pre style="line-height:15px">
<FONT class=font_blue>   <B>[IFPACKAGE]</B>
      ��ǰ��Ű�� ���� ��) [BASKET_PACKAGE]
   <B>[IFENDPACKAGE]</B></font></pre>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[BASKET_PACKAGE]</td>
							<td class=td_con1 width=100% style="padding-left:5;">
							��Ű�� ���� <FONT class=font_blue>(��:[BASKET_PACKAGE])</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell width=180 align=right style="padding-right:15">[IFPACKAGELIST]<br>[IFENDPACKAGELIST]</td>
							<td class=td_con1 width=100% style="padding-left:5;">
							��ٱ��� ��ǰ ��Ű�� ���� ���� ó�� (��Ű�� ���� ��ǰ�� ���� ��쿡�� ���� ���)
							<pre style="line-height:15px">
<FONT class=font_blue>   <B>[IFPACKAGELIST]</B>
      ��ǰ��Ű�� ���� ��) [BASKET_PACKAGELIST]
   <B>[IFENDPACKAGELIST]</B></font></pre>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[BASKET_PACKAGELIST]</td>
							<td class=td_con1 width=100% style="padding-left:5;">
							��Ű�� ���� ��ǰ ���� <FONT class=font_blue>(��:[BASKET_PACKAGELIST])</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell width=180 align=right style="padding-right:15">[IFASSEMBLE]<br>[IFENDASSEMBLE]</td>
							<td class=td_con1 width=100% style="padding-left:5;">
							��ٱ��� �ڵ�/������ǰ�� ���� (�ڵ�/������ǰ�� ���� ��쿡�� �������� ���)
							<pre style="line-height:15px">
<FONT class=font_blue>   <B>[IFASSEMBLE]</B>
      �ڵ�/������ǰ ���� ��) [BASKET_ASSEMBLE]
   <B>[IFENDASSEMBLE]</B></font></pre>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[BASKET_ASSEMBLE]</td>
							<td class=td_con1 width=100% style="padding-left:5;">
							�ڵ�/���� ���� ��ǰ ���� <FONT class=font_blue>(��:[BASKET_ASSEMBLE])</font>
							</td>
						</tr>
						-->
						<?if($_shopdata->ETCTYPE["VATUSE"]=="Y") { ?>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[BASKET_PRODUCTVAT]</td>
							<td class=td_con1 width=100% style="padding-left:5;">
							VAT �հ�ݾ� <FONT class=font_blue>(��:VAT �հ�ݾ� : [BASKET_PRODUCTVAT]��)</font>
							</td>
						</tr>
						<? } ?>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[BASKET_GROUPSTART]<br>[BASKET_GROUPEND]</td>
							<td class=td_con1 width=100% style="padding-left:5;">
							��ü�� ��ۺ�/�հ�ݾ� ����
							<pre style="line-height:15px">
<font class=font_blue>   <B>[BASKET_GROUPSTART]</B>
      ��� ��) ��ۺ� : [GROUP_DELIPRICE]��, �հ�ݾ� : [GROUP_TOTPRICE]��
   <B>[BASKET_GROUPEND]</B></font></pre>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[GROUP_DELIPRICE]</td>
							<td class=td_con1 width=100% style="padding-left:5;">
							��ü�� ��ۺ� <FONT class=font_blue>(��:��ǰ �հ�ݾ� : [GROUP_DELIPRICE]��)</font><br>[BASKET_GROUPSTART] [BASKET_GROUPEND]�� ���
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[GROUP_TOTPRICE]</td>
							<td class=td_con1 width=100% style="padding-left:5;">
							��ü�� �հ�ݾ� <FONT class=font_blue>(��:��ü�� �հ�ݾ� : [GROUP_TOTPRICE]��)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[BASKET_TOTPRICE]</td>
							<td class=td_con1 width=100% style="padding-left:5;">
							�� �����ݾ� <FONT class=font_blue>(��:�� �����ݾ� : [BASKET_TOTPRICE]��)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[BASKET_TOTRESERVE]</td>
							<td class=td_con1 width=100% style="padding-left:5;">
							�� ������ <FONT class=font_blue>(��:�� ������ : [BASKET_TOTRESERVE]��)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[GROUP_DISCOUNT]</td>
							<td class=td_con1 width=100% style="padding-left:5;">ȸ�� ��޺� ���� ����</td>
						</tr>
						<!--
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[BASKET_PESTER]</td>
							<td class=td_con1 width=100% style="padding-left:5;">
							������ ��ư <FONT class=font_blue>(��:&lt;a href=[BASKET_PESTER]>������&lt;/a>)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[BASKET_PRESENT]</td>
							<td class=td_con1 width=100% style="padding-left:5;">
							�����ϱ� ��ư <FONT class=font_blue>(��:&lt;a href=[BASKET_PRESENT]>�����ϱ�&lt;/a>)</font>
							</td>
						</tr>
						-->
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[BASKET_ORDER]</td>
							<td class=td_con1 width=100% style="padding-left:5;">
							�ֹ��ϱ� ��ư <FONT class=font_blue>(��:&lt;a href=[BASKET_ORDER]>�ֹ��ϱ�&lt;/a>)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[BASKET_SHOPPING]</td>
							<td class=td_con1 width=100% style="padding-left:5;">
							��Ӽ��� ��ư <FONT class=font_blue>(��:&lt;a href=[BASKET_SHOPPING]>��Ӽ���&lt;/a>)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[BASKET_CLEAR]</td>
							<td class=td_con1 width=100% style="padding-left:5;">
							��ٱ��� ���� ��ư <FONT class=font_blue>(��:&lt;a href=[BASKET_CLEAR]>��ٱ��� ����&lt;/a>)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<!--
						<tr><td colspan=2 height=5></td></tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell colspan=2 align=center bgcolor=#F0F0F0>
							<B>Ư��ȸ�� ���� ��ũ�� ����</B>
							</td>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell width=180 align=right style="padding-right:15">[IFROYAL]<br>[IFENDROYAL]</td>
							<td class=td_con1 width=100% style="padding-left:5;">
							Ư��ȸ���� ���� ���� ��� (Ư��ȸ���� ��쿡�� ���� ���)
							<pre style="line-height:15px">
<font class=font_blue>   <B>[IFROYAL]</B>
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
							<td class=table_cell align=right style="padding-right:15">[ROYAL_MSG2]</td>
							<td class=td_con1 width=100% style="padding-left:5;">
							Ư��ȸ�� ���� �޼���2 - �ڵ����
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						-->
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
	f.mode.value = 'basket';
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