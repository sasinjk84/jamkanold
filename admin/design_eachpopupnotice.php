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
$list_body=$_POST["list_body"];
$list_xsize=$_POST["list_xsize"];
$list_ysize=$_POST["list_ysize"];
$view_body=$_POST["view_body"];
$view_xsize=$_POST["view_xsize"];
$view_ysize=$_POST["view_ysize"];

if($list_xsize==0) $list_xsize=430;
if($list_ysize==0) $list_ysize=470;
if($view_xsize==0) $view_xsize=430;
if($view_ysize==0) $view_ysize=470;

$list_filename=$list_xsize."".$list_ysize;
$view_filename=$view_xsize."".$view_ysize;



$subject = '�������� �˾�';

// ��� / ����
if ( $type=="store" OR $type=="restore" ) {
	$MSG = adminDesingBackup ( $type, 'noticelist', $list_body, $subject." ���â", '', $list_filename );
	$MSG .= adminDesingBackup ( $type, 'noticeview', $view_body, $subject." �� ������", '', $view_filename );
	$onload="<script>alert(\"".$MSG."\");</script>";
}


if($type=="update") {

	$sql = "SELECT COUNT(*) as cnt FROM tbldesignnewpage WHERE type='noticelist' ";
	$result=mysql_query($sql,get_db_conn());
	$row=mysql_fetch_object($result);
	if($row->cnt==0) {
		$sql = "INSERT tbldesignnewpage SET ";
		$sql.= "type		= 'noticelist', ";
		$sql.= "filename	= '".$list_filename."', ";
		$sql.= "subject		= '�������� �˾� ���â', ";
		$sql.= "body		= '".$list_body."' ";
		mysql_query($sql,get_db_conn());
	} else {
		$sql = "UPDATE tbldesignnewpage SET ";
		$sql.= "filename	= '".$list_filename."', ";
		$sql.= "body		= '".$list_body."' ";
		$sql.= "WHERE type='noticelist' ";
		mysql_query($sql,get_db_conn());
	}
	mysql_free_result($result);


	$sql = "SELECT COUNT(*) as cnt FROM tbldesignnewpage WHERE type='noticeview' ";
	$result=mysql_query($sql,get_db_conn());
	$row=mysql_fetch_object($result);
	if($row->cnt==0) {
		$sql = "INSERT tbldesignnewpage SET ";
		$sql.= "type		= 'noticeview', ";
		$sql.= "filename	= '".$view_filename."', ";
		$sql.= "subject		= '�������� �˾� �� ������', ";
		$sql.= "body		= '".$view_body."' ";
		mysql_query($sql,get_db_conn());
	} else {
		$sql = "UPDATE tbldesignnewpage SET ";
		$sql.= "filename	= '".$view_filename."', ";
		$sql.= "body		= '".$view_body."' ";
		$sql.= "WHERE type='noticeview' ";
		mysql_query($sql,get_db_conn());
	}
	mysql_free_result($result);

	$onload="<script>alert(\"�������� �˾�â ������ ������ �Ϸ�Ǿ����ϴ�.\");</script>";
} else if($type=="delete") {
	$sql = "DELETE FROM tbldesignnewpage WHERE type='noticelist' ";
	mysql_query($sql,get_db_conn());
	$sql = "DELETE FROM tbldesignnewpage WHERE type='noticeview' ";
	mysql_query($sql,get_db_conn());
	$onload="<script>alert(\"�������� �˾�â ������ ������ �Ϸ�Ǿ����ϴ�.\");</script>";
} else if($type=="clear") {
	$list_body="";
	$list_xsize=430;
	$list_ysize=470;
	$sql = "SELECT body FROM tbldesigndefault WHERE type='noticelist' ";
	$result=mysql_query($sql,get_db_conn());
	if($row=mysql_fetch_object($result)) {
		$list_body=$row->body;
	}
	mysql_free_result($result);

	$view_body="";
	$view_xsize=430;
	$view_ysize=470;
	$sql = "SELECT body FROM tbldesigndefault WHERE type='noticeview' ";
	$result=mysql_query($sql,get_db_conn());
	if($row=mysql_fetch_object($result)) {
		$view_body=$row->body;
	}
	mysql_free_result($result);
}

if($type!="clear") {
	$list_body="";
	$list_xsize=0;
	$list_ysize=0;

	$view_body="";
	$view_xsize=0;
	$view_ysize=0;

	$sql = "SELECT filename,body FROM tbldesignnewpage WHERE type='noticelist' ";
	$result=mysql_query($sql,get_db_conn());
	if($row=mysql_fetch_object($result)) {
		$list_body=$row->body;
		$list_size=explode("",$row->filename);
		$list_xsize=(int)$list_size[0];
		$list_ysize=(int)$list_size[1];
		if($list_xsize==0) $list_xsize=430;
		if($list_ysize==0) $list_ysize=470;
	}
	mysql_free_result($result);
	if($list_xsize==0) $list_xsize=430;
	if($list_ysize==0) $list_ysize=470;

	$sql = "SELECT filename,body FROM tbldesignnewpage WHERE type='noticeview' ";
	$result=mysql_query($sql,get_db_conn());
	if($row=mysql_fetch_object($result)) {
		$view_body=$row->body;
		$view_size=explode("",$row->filename);
		$view_xsize=(int)$view_size[0];
		$view_ysize=(int)$view_size[1];
		if($view_xsize==0) $view_xsize=430;
		if($view_ysize==0) $view_ysize=470;
	}
	mysql_free_result($result);
	if($view_xsize==0) $view_xsize=430;
	if($view_ysize==0) $view_ysize=470;
}
?>

<? INCLUDE "header.php"; ?>

<script type="text/javascript" src="lib.js.php"></script>
<script language="JavaScript">
function CheckForm(type) {
	if(type=="update") {
		if(document.form1.list_body.value.length==0) {
			alert("�������� �˾� ��� ������ ������ �Է��ϼ���.");
			document.form1.list_body.focus();
			return;
		}
		if(document.form1.view_body.value.length==0) {
			alert("�������� �˾� �� ������ ������ ������ �Է��ϼ���.");
			document.form1.view_body.focus();
			return;
		}
		if(!IsNumeric(document.form1.list_xsize.value)) {
			alert("�˾� ���â �������� ���ڸ� �Է� �����մϴ�.");
			document.form1.list_xsize.focus();
			return;
		}
		if(!IsNumeric(document.form1.list_ysize.value)) {
			alert("�˾� ���â ���̴� ���ڸ� �Է� �����մϴ�.");
			document.form1.list_ysize.focus();
			return;
		}
		if(!IsNumeric(document.form1.view_xsize.value)) {
			alert("�˾� �� ������ �������� ���ڸ� �Է� �����մϴ�.");
			document.form1.view_xsize.focus();
			return;
		}
		if(!IsNumeric(document.form1.view_ysize.value)) {
			alert("�˾� �� ������ ���̴� ���ڸ� �Է� �����մϴ�.");
			document.form1.view_ysize.focus();
			return;
		}
		document.form1.type.value=type;
		document.form1.submit();
	} else if(type=="delete") {
		if(confirm("�������� �˾�â �������� �����Ͻðڽ��ϱ�?")) {
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
				<td height="28" class="link" align="left" background="images/con_link_bg.gif"><img src="images/top_link_house.gif" border="0" valign="absmiddle">������ġ : �����ΰ��� &gt; ����������-������ ���� &gt; <span class="2depth_select">�������� �˾�â �ٹ̱�</span></td>
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
					<TD><IMG SRC="images/design_eachpopup_title.gif" ALT=""></TD>
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
					<TD width="100%" class="notice_blue"><p>�������� �˾�â �������� �����Ӱ� �����Ͻ� �� �ֽ��ϴ�.</p></TD>
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
					<TD><IMG SRC="images/design_eachpopup_stitle1.gif" ALT=""></TD>
					<TD width="100%" background="images/shop_basicinfo_stitle_bg.gif">&nbsp;</TD>
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
			<form name=form1 action="<?=$_SERVER[PHP_SELF]?>" method=post>
			<input type=hidden name=type>
			<tr>
				<td style="padding-top:3pt;">
				<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0 style="table-layout:fixed">
				<col width=139></col>
				<col width=></col>
				<TR>
					<TD colspan=2 background="images/table_top_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">�˾�â ũ��</TD>
					<TD class="td_con1"><input type=text name=list_xsize value="<?=$list_xsize?>" size=5 maxlength=3 onkeyup="strnumkeyup(this)" class="input"> &times; <input type=text name=list_ysize value="<?=$list_ysize?>" size=5 maxlength=3 onkeyup="strnumkeyup(this)" class="input"> �ȼ�</TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<TR>
					<TD colspan="2"><textarea name=list_body style="WIDTH: 100%; HEIGHT: 300px" class="textarea"><?=htmlspecialchars($list_body)?></textarea></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr><td height="30"></td></tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/design_eachpopup_stitle2.gif"  ALT=""></TD>
					<TD width="100%" background="images/shop_basicinfo_stitle_bg.gif">&nbsp;</TD>
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
			<tr>
				<td style="padding-top:3pt;">
				<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
				<TR>
					<TD colspan=2 background="images/table_top_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell" width="139"><img src="images/icon_point2.gif" width="8" height="11" border="0">�˾�â ũ��</TD>
					<TD class="td_con1" width="600"><input type=text name=view_xsize value="<?=$view_xsize?>" size=5 maxlength=3 onkeyup="strnumkeyup(this)" class="input"> &times; <input type=text name=view_ysize value="<?=$view_ysize?>" size=5 maxlength=3 onkeyup="strnumkeyup(this)" class="input"> �ȼ�</TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<TR>
					<TD colspan="2"><textarea name=view_body style="WIDTH: 100%; HEIGHT: 200px" class="textarea"><?=htmlspecialchars($view_body)?></textarea></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr><td height=10></td></tr>
			<tr>
				<td align="center"><a href="javascript:CheckForm('update');"><img src="images/botteon_save.gif" width="113" height="38" border="0"></a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:CheckForm('clear');"><img src="images/botteon_bok.gif" width="124" height="38" border="0" hspace="2"></a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:CheckForm('delete');"><img src="images/botteon_del.gif" width="113" height="38" border="0" hspace="0"></a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:prevPage();"><img src="images/botteon_prev.gif" width="113" height="38" border="0" hspace="2"></a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:CheckForm('store');"><img src="images/botteon_store.gif" border="0" hspace="2" alt="����ϱ�"></a>&nbsp;&nbsp;&nbsp;<a href="javascript:CheckForm('restore');"><img src="images/botteon_restore.gif" border="0" hspace="2" alt="��������ϱ�"></a></td>
			</tr>
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
						<td width="20" align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td ><p class="LIPoint"><B><span class="font_orange">�������� �˾�â ��ũ�θ�ɾ�</span></B>(�ش� ��ũ�θ�ɾ�� �ٸ� ������ ������ �۾��� ����� �Ұ�����)</p></td>
					</tr>
					<tr>
						<td width="20" align="right" valign="top"><p>&nbsp;</p></td>
						<td >
						<table border=0 cellpadding=0 cellspacing=0 width=100%>
						<col width=150></col>
						<col width=></col>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell colspan=2 align=center bgcolor=#F0F0F0>
							<B>�������� �˾� ����Ʈ ��ũ�� ����</B>
							</td>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[LISTING]</td>
							<td class=td_con1 style="padding-left:5;">
							��� ǥ��
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[PAGEING]</td>
							<td class=td_con1 style="padding-left:5;">
							������ ǥ��
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[CLOSE]</td>
							<td class=td_con1 style="padding-left:5;">
							�˾�â �ݱ� <FONT class=font_blue>(��:&lt;a href=[CLOSE]>�ݱ�&lt;/a>)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr><td colspan=2 height=5></td></tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell colspan=2 align=center bgcolor=#F0F0F0>
							<B>�������� �˾� �� ������ ��ũ�� ����</B>
							</td>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[SUBJECT]</td>
							<td class=td_con1 style="padding-left:5;">
							�������� ����
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[CONTENT]</td>
							<td class=td_con1 style="padding-left:5;">
							�������� ����
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[DATE]</td>
							<td class=td_con1 style="padding-left:5;">
							���� ����
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[ACCESS]</td>
							<td class=td_con1 style="padding-left:5;">
							��ȸ��
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[PREV]</td>
							<td class=td_con1 style="padding-left:5;">
							���� ���� <FONT class=font_blue>(��:&lt;a href=[PREV]>����&lt;/a>)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[NEXT]</td>
							<td class=td_con1 style="padding-left:5;">
							���� ���� <FONT class=font_blue>(��:&lt;a href=[NEXT]>����&lt;/a>)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[LIST]</td>
							<td class=td_con1 style="padding-left:5;">
							��Ϻ��� <FONT class=font_blue>(��:&lt;a href=[LIST]>��Ϻ���&lt;/a>)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[LIST]</td>
							<td class=td_con1 style="padding-left:5;">
							â �ݱ� <FONT class=font_blue>(��:&lt;a href=[CLOSE]>�ݱ�&lt;/a>)</font>
							</td>
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
	if(document.form1.list_body.value.length==0) {
		alert("������ ������ �Է��ϼ���.");
		document.form1.list_body.focus();
		return;
	}

	f = document.prevForm;
	f.mode.value = 'notice';
	f.code.value = document.form1.list_body.value+'<div style="float:left"></div><br /><br />'+document.form1.view_body.value;
	f.submit();
}
</script>

<form name="prevForm" method="post" action="design_prev_post.php" target="_blank">
	<input type="hidden" name="code">
	<input type="hidden" name="mode">
</form>

<?=$onload?>

<? INCLUDE "copyright.php"; ?>