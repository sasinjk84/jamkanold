<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
INCLUDE ("access.php");

####################### ������ ���ٱ��� check ###############
$PageCode = "de-4";
$MenuCode = "design";
if (!$_usersession->isAllowedTask($PageCode)) {
	INCLUDE ("AccessDeny.inc.php");
	exit;
}
#########################################################

$type=$_POST["type"];
$main_body=$_POST["main_body"];


// �̸����� URL
$urls = "/main/main.php?";

$insertKey = "mainpage";

$subject = '����������';

// ��� / ����
if ( $type=="store" OR $type=="restore" ) {
	$MSG = adminDesingBackup ( $type, $insertKey, $main_body, $subject );
	$onload="<script>alert(\"".$MSG."\");</script>";
}


if($type=="update" && strlen($main_body)>0) {
	$sql = "SELECT COUNT(*) as cnt FROM tbldesignnewpage ";
	$sql.= "WHERE type='mainpage' ";
	$result=mysql_query($sql,get_db_conn());
	$row=mysql_fetch_object($result);
	if($row->cnt==0) {
		$sql = "INSERT tbldesignnewpage SET ";
		$sql.= "type		= 'mainpage', ";
		$sql.= "subject		= '����������', ";
		$sql.= "body		= '".$main_body."' ";
		mysql_query($sql,get_db_conn());
	} else {
		$sql = "UPDATE tbldesignnewpage SET ";
		$sql.= "body		= '".$main_body."' ";
		$sql.= "WHERE type='mainpage' ";
		mysql_query($sql,get_db_conn());
	}
	mysql_free_result($result);
	$onload="<script>alert(\"���� ���� ������ ������ �Ϸ�Ǿ����ϴ�.\");</script>";
} else if($type=="delete") {
	$sql = "DELETE FROM tbldesignnewpage WHERE type='mainpage' ";
	mysql_query($sql,get_db_conn());
	$onload="<script>alert(\"���� ���� ������ ������ �Ϸ�Ǿ����ϴ�.\");</script>";
} else if($type=="clear") {
	$main_body="";
	$sql = "SELECT body FROM tbldesigndefault WHERE type='mainpage' ";
	$result=mysql_query($sql,get_db_conn());
	if($row=mysql_fetch_object($result)) {
		$main_body=$row->body;
	}
	mysql_free_result($result);
}

if($type!="clear") {
	$main_body="";
	$sql = "SELECT body FROM tbldesignnewpage WHERE type='mainpage' ";
	$result=mysql_query($sql,get_db_conn());
	if($row=mysql_fetch_object($result)) {
		$main_body=$row->body;
	}
	mysql_free_result($result);
}
?>

<? INCLUDE "header.php"; ?>

<script type="text/javascript" src="lib.js.php"></script>
<script language="JavaScript">
function CheckForm(type) {
	if(type=="update") {
		if(document.form1.main_body.value.length==0) {
			alert("���� ���� ������ ������ �Է��ϼ���.");
			document.form1.main_body.focus();
			return;
		}
		document.form1.type.value=type;
		document.form1.submit();
	} else if(type=="delete") {
		if(confirm("���� ���� �������� �����Ͻðڽ��ϱ�?")) {
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
		if(document.form1.main_body.value.length==0) {
			alert("���� ���� ������ ������ �Է��ϼ���.");
			document.form1.main_body.focus();
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
	window.open("http://www.getmall.co.kr/macro/pages/main_macro.html","top_macro","height=800,width=680,scrollbars=no");
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
				<td height="28" class="link" align="left" background="images/con_link_bg.gif"><img src="images/top_link_house.gif" border="0" valign="absmiddle">������ġ : �������� &gt; ���ȼ��� &gt; <span class="2depth_select">���/�ο�� ����</span></td>
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
					<TD><IMG SRC="images/design_eachmain_title.gif"  border="0"></TD>
					</tr>
<tr>
<TD width="100%" background="images/title_bg.gif" height="21"></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr><td height="3"></td></tr>
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
						<table cellpadding="0" cellspacing="0" width="686">
							<tr>
								<td width="172" align=center><IMG SRC="images/design_eachmain_img.gif" WIDTH="159" HEIGHT="100" ALT="" align="baseline"></td>
								<td  class="notice_blue" style="letter-spacing:-0.5pt;">1) ���θ� ���κ���(�����߾�+�����޴��� ��� ����)�� �����Ӱ� �������� �����մϴ�.<br>2) ���������� ���� �� <a href="javascript:parent.topframe.GoMenu(2,'design_option.php');"><span class="font_blue">�����ΰ��� > ��FTP �� �������� ���� > ���������� ���뼱��</span></a> �� �ؾ� ����˴ϴ�.
								<br><b>&nbsp;&nbsp;&nbsp;</b>���κ��� ����+��ü������ ���ʸ޴� ���
								<br><b>&nbsp;&nbsp;&nbsp;</b>���κ��� ����+��ü������ ���ʸ޴� �����
								<br>3) <a href="javascript:parent.topframe.GoMenu(2,'design_easycss.php');"><span class="font_blue">�����ΰ��� > Easy ������ ���� > Easy �ؽ�Ʈ �Ӽ� ����</span></a> ���� �� �޴����� �ؽ�Ʈ�Ӽ��� �����մϴ�.</td>

							</tr>
						</table>
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
			<tr><td height="40"></td></tr>
			<form name=form1 action="<?=$_SERVER[PHP_SELF]?>" method=post>
			<input type=hidden name=type>
			<input type=hidden name="urls" value="<?=$urls?>">
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/design_eachmain_stitle1.gif" border="0"></TD>
					<TD width="100%" background="images/shop_basicinfo_stitle_bg.gif">&nbsp;&nbsp;<a href="javascript:macroview();"><img src="images/btn_macroview.gif" border="0" align="absmiddle" alt="" /></a>&nbsp;&nbsp;<a href="http://www.getmall.co.kr/macro/data/main_design.zip"><img src="images/btn_defaultcode.gif" border="0" align="absmiddle" alt="" /></a></TD>
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
					<TD width="100%" class="notice_blue">
						1) �Ŵ����� <b>��ũ�θ�ɾ�</b>�� �����Ͽ� ������ �ϼ���.<br />
						2) <span class="font_orange" style="font-size:11px;"><u>���� ���� ��ũ�� ��ɾ� ���� ����</u> : <b>/main/mainn.php (���ʸ޴� �̻���), /main/mainm.php (���ʸ޴� ����), /main/main_text.php</b> (���� ������ ���� ������ �ݵ�� ����Ͻñ� �ٶ��ϴ�.)</span><br />
						3) [�⺻������]+[�����ϱ�], [�����ϱ�]�ϸ� �⺻���ø����� ����(���������� �ҽ� ����)�˴ϴ�. -> ���ø� �޴����� ���ϴ� ���ø� ����<br />
						4) �⺻�� �����̳� �����ϱ� ���̵� ���ø� �����ϸ� ���������� �����˴ϴ�.(���������� �ҽ��� ������)
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
			<tr><td height=20></td></tr>
			<tr>
				<td><textarea name=main_body style="WIDTH: 100%; HEIGHT: 300px" class="textarea"><?=htmlspecialchars($main_body)?></textarea></td>
			</tr>
			<tr><td height=10></td></tr>
			<tr>
				<td align="center">
					<a href="javascript:CheckForm('update');"><img src="images/botteon_save.gif" width="113" height="38" border="0"></a>&nbsp;&nbsp;&nbsp;
					<a href="javascript:CheckForm('clear');"><img src="images/botteon_bok.gif" width="124" height="38" border="0" hspace="2"></a>&nbsp;&nbsp;&nbsp;
					<a href="javascript:CheckForm('delete');"><img src="images/botteon_del.gif" width="113" height="38" border="0" hspace="0"></a>
					<!--
					<a href="javascript:CheckForm('preview');"><img src="images/botteon_prev.gif" width="113" height="38" border="0" hspace="2"></a>&nbsp;&nbsp;&nbsp;&nbsp;
					<a href="javascript:CheckForm('store');"><img src="images/botteon_store.gif" border="0" hspace="2" alt="����ϱ�"></a>&nbsp;&nbsp;&nbsp;
					<a href="javascript:CheckForm('restore');"><img src="images/botteon_restore.gif" border="0" hspace="2" alt="��������ϱ�"></a>
					-->
				</td>
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
						<td width="100%"><span class="font_dotline">������ �Ӽ� ����</span></td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td width="100%" class="space_top" style="letter-spacing:-0.5pt;">- ���λ�ǰ ����Ÿ�� / ���� �Խñ� ǥ�ð��� ���� : <a href="javascript:parent.topframe.GoMenu(1,'shop_mainproduct.php');"><span class="font_blue">�������� > ���θ� ȯ�� ���� > ��ǰ ������/ȭ�鼳��</span></a></td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td width="100%" class="space_top" style="letter-spacing:-0.5pt;">- ���� Ÿ��Ʋ ���� : <a href="javascript:parent.topframe.GoMenu(2,'design_eachtitleimage.php');"><span class="font_blue">�����ΰ��� > ���� ������- ���� �� ���ϴ� > Ÿ��Ʋ �̹��� ����</span></a></td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td width="100%" class="space_top" style="letter-spacing:-0.5pt;">- �ؽ�Ʈ �Ӽ����� : <a href="javascript:parent.topframe.GoMenu(2,'design_easycss.php');"><span class="font_blue">�����ΰ��� > Easy ������ ���� > Easy �ؽ�Ʈ �Ӽ� ����</span></a></td>
					</tr>
					<tr>
						<td height="20" colspan="2"></td>
					</tr>
					<tr>
						<td width="20" align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td width="701"><p class="LIPoint"><B><span class="font_orange">���� ���� ��ũ�θ�ɾ�</span></B>(�ش� ��ũ�θ�ɾ�� �ٸ� ������ ������ �۾��� ����� �Ұ�����)</p></td>
					</tr>
					<tr>
						<td width="20" align="right" valign="top"></td>
						<td width="701"  style="padding-top:3pt; padding-bottom:10pt;">
						<table border=0 cellpadding=0 cellspacing=0 width=100%>
						<col width=150></col>
						<col width=></col>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[SHOPINTRO]</td>
							<td class=td_con1 style="padding-left:5;">
							���θ� �λ縻 ǥ�� - ���� �Է��ص� ����
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[PRODUCTNEW]</td>
							<td class=td_con1 style="padding-left:5;">
							�űԻ�ǰ <FONT class=font_blue>(��:&lt;a href=[PRODUCTNEW]>�űԻ�ǰ&lt;/a>)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[PRODUCTBEST]</td>
							<td class=td_con1 style="padding-left:5;">
							�α��ǰ <FONT class=font_blue>(��:&lt;a href=[PRODUCTBEST]>�α��ǰ&lt;/a>)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[PRODUCTHOT]</td>
							<td class=td_con1 style="padding-left:5;">
							��õ��ǰ <FONT class=font_blue>(��:&lt;a href=[PRODUCTHOT]>��õ��ǰ&lt;/a>)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[PRODUCTSPECIAL]</td>
							<td class=td_con1 style="padding-left:5;">
							Ư����ǰ <FONT class=font_blue>(��:&lt;a href=[PRODUCTSPECIAL]>Ư����ǰ&lt;/a>)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[NEWITEM1??]</td>
							<td class=td_con1 style="padding-left:5;">
							�űԻ�ǰ - �̹���A��
										<br><img width=10 height=0>
										<FONT class=font_orange>? : ���κ� ��ǰ����(1~8)</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>? : ��������� ������ �Ұ��� �����Է�(1-8)</FONT>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[NEWITEM2??]</td>
							<td class=td_con1 style="padding-left:5;">
							�űԻ�ǰ - �̹���B��
										<br><img width=10 height=0>
										<FONT class=font_orange>? : ���κ� ��ǰ����(1~8)</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>? : ��������� ������ �Ұ��� �����Է�(1-8)</FONT>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[NEWITEM?????????_??]</td>
							<td class=td_con1 style="padding-left:5;">
							�űԻ�ǰ - �̹���A��/�̹���B��
										<br><img width=10 height=0>
										<FONT class=font_orange>? : ���� ������ �űԻ�ǰ ���� (1:�̹���A��, 2:�̹���B��)</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>? : ���κ� ��ǰ����(1~8)</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>? : ��������� ������ �Ұ��� �����Է�(1-8)</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>? : �űԻ�ǰ Ÿ��Ʋ ǥ�ÿ���(Y/N)</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>? : �űԻ�ǰ ������ ���ζ��� ǥ�ÿ���(Y/N/L)</FONT> (L�� ��ǰ�� ���߾� ��� ǥ�õ�)
										<br><img width=10 height=0>
										<FONT class=font_orange>? : �űԻ�ǰ ������ ���ζ��� ǥ�ÿ���(Y/N)</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>? : �űԻ�ǰ ���߰��� ǥ�ÿ���(Y/N)</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>? : �űԻ�ǰ ������ ǥ�ÿ���(Y/N)</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>? : �űԻ�ǰ �±� ǥ�ð���(0-9) : 0�� ��� ǥ�þ���</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>_?? : �űԻ�ǰ����(���Ʒ�) ���� �ִ� 99�ȼ� (���Է½� 5�ȼ�)</FONT>
										<br>
										<FONT class=font_blue>��) [NEWITEM142NNNYN2_10], [NEWITEM222YLYYY2_5]</FONT>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[NEWITEM3??]</td>
							<td class=td_con1 style="padding-left:5;">
							�űԻ�ǰ - ����Ʈ��
										<br><img width=10 height=0>
										<FONT class=font_orange>?? : �űԻ�ǰ �������� (01~20)</FONT>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[NEWITEM3???????]</td>
							<td class=td_con1 style="padding-left:5;">
							�űԻ�ǰ - ����Ʈ��
										<br><img width=10 height=0>
										<FONT class=font_orange>?? : �űԻ�ǰ �������� (01~20)</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>? : �űԻ�ǰ Ÿ��Ʋ ǥ�ÿ���(Y/N)</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>? : �űԻ�ǰ ������ ǥ�ÿ��� (Y/N)</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>? : �űԻ�ǰ ���߰��� ǥ�ÿ���(Y/N)</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>? : �űԻ�ǰ ������ ǥ�ÿ���(Y/N)</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>? : �űԻ�ǰ �±� ǥ�ð���(0-9) : 0�� ��� ǥ�þ���</FONT>
										<br>
										<FONT class=font_blue>��) [NEWITEM304YYYY4]</FONT>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[BESTITEM1??]</td>
							<td class=td_con1 style="padding-left:5;">
							�α��ǰ - �̹���A��
										<br><img width=10 height=0>
										<FONT class=font_orange>? : ���κ� ��ǰ����(1~8)</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>? : ��������� ������ �Ұ��� �����Է�(1-8)</FONT>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[BESTITEM2??]</td>
							<td class=td_con1 style="padding-left:5;">
							�α��ǰ - �̹���B��
										<br><img width=10 height=0>
										<FONT class=font_orange>? : ���κ� ��ǰ����(1~8)</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>? : ��������� ������ �Ұ��� �����Է�(1-8)</FONT>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[BESTITEM?????????_??]</td>
							<td class=td_con1 style="padding-left:5;">
							�α��ǰ - �̹���A��/�̹���B��
										<br><img width=10 height=0>
										<FONT class=font_orange>? : ���� ������ �α��ǰ ���� (1:�̹���A��, 2:�̹���B��)</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>? : ���κ� ��ǰ����(1~8)</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>? : ��������� ������ �Ұ��� �����Է�(1-8)</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>? : �α��ǰ Ÿ��Ʋ ǥ�ÿ���(Y/N)</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>? : �α��ǰ ������ ���ζ��� ǥ�ÿ���(Y/N/L)</FONT> (L�� ��ǰ�� ���߾� ��� ǥ�õ�)
										<br><img width=10 height=0>
										<FONT class=font_orange>? : �α��ǰ ������ ���ζ��� ǥ�ÿ���(Y/N)</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>? : �α��ǰ ���߰��� ǥ�ÿ���(Y/N)</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>? : �α��ǰ ������ ǥ�ÿ���(Y/N)</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>? : �α��ǰ �±� ǥ�ð���(0-9) : 0�� ��� ǥ�þ���</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>_?? : �α��ǰ����(���Ʒ�) ���� �ִ� 99�ȼ� (���Է½� 5�ȼ�)</FONT>
										<br>
										<FONT class=font_blue>��) [BESTITEM142NNNYN2_10], [BESTITEM222YLYYY2_5]</FONT>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[BESTITEM3??]</td>
							<td class=td_con1 style="padding-left:5;">
							�α��ǰ - ����Ʈ��
										<br><img width=10 height=0>
										<FONT class=font_orange>?? : �α��ǰ �������� (01~20)</FONT>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[BESTITEM3???????]</td>
							<td class=td_con1 style="padding-left:5;">
							�α��ǰ - ����Ʈ��
										<br><img width=10 height=0>
										<FONT class=font_orange>?? : �α��ǰ �������� (01~20)</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>? : �α��ǰ Ÿ��Ʋ ǥ�ÿ���(Y/N)</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>? : �α��ǰ ������ ǥ�ÿ��� (Y/N)</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>? : �α��ǰ ���߰��� ǥ�ÿ���(Y/N)</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>? : �α��ǰ ������ ǥ�ÿ���(Y/N)</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>? : �α��ǰ �±� ǥ�ð���(0-9) : 0�� ��� ǥ�þ���</FONT>
										<br>
										<FONT class=font_blue>��) [BESTITEM304YYYY4]</FONT>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[HOTITEM1??]</td>
							<td class=td_con1 style="padding-left:5;">
							��õ��ǰ - �̹���A��
										<br><img width=10 height=0>
										<FONT class=font_orange>? : ���κ� ��ǰ����(1~8)</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>? : ��������� ������ �Ұ��� �����Է�(1-8)</FONT>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[HOTITEM2??]</td>
							<td class=td_con1 style="padding-left:5;">
							��õ��ǰ - �̹���B��
										<br><img width=10 height=0>
										<FONT class=font_orange>? : ���κ� ��ǰ����(1~8)</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>? : ��������� ������ �Ұ��� �����Է�(1-8)</FONT>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[HOTITEM?????????_??]</td>
							<td class=td_con1 style="padding-left:5;">
							��õ��ǰ - �̹���A��/�̹���B��
										<br><img width=10 height=0>
										<FONT class=font_orange>? : ���� ������ ��õ��ǰ ���� (1:�̹���A��, 2:�̹���B��)</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>? : ���κ� ��ǰ����(1~8)</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>? : ��������� ������ �Ұ��� �����Է�(1-8)</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>? : ��õ��ǰ Ÿ��Ʋ ǥ�ÿ���(Y/N)</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>? : ��õ��ǰ ������ ���ζ��� ǥ�ÿ���(Y/N/L)</FONT> (L�� ��ǰ�� ���߾� ��� ǥ�õ�)
										<br><img width=10 height=0>
										<FONT class=font_orange>? : ��õ��ǰ ������ ���ζ��� ǥ�ÿ���(Y/N)</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>? : ��õ��ǰ ���߰��� ǥ�ÿ���(Y/N)</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>? : ��õ��ǰ ������ ǥ�ÿ���(Y/N)</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>? : ��õ��ǰ �±� ǥ�ð���(0-9) : 0�� ��� ǥ�þ���</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>_?? : ��õ��ǰ����(���Ʒ�) ���� �ִ� 99�ȼ� (���Է½� 5�ȼ�)</FONT>
										<br>
										<FONT class=font_blue>��) [HOTITEM142NNNYN2_10], [HOTITEM222YLYYY2_5]</FONT>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[HOTITEM3??]</td>
							<td class=td_con1 style="padding-left:5;">
							��õ��ǰ - ����Ʈ��
										<br><img width=10 height=0>
										<FONT class=font_orange>?? : ��õ��ǰ �������� (01~20)</FONT>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[HOTITEM3???????]</td>
							<td class=td_con1 style="padding-left:5;">
							��õ��ǰ - ����Ʈ��
										<br><img width=10 height=0>
										<FONT class=font_orange>?? : ��õ��ǰ �������� (01~20)</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>? : ��õ��ǰ Ÿ��Ʋ ǥ�ÿ���(Y/N)</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>? : ��õ�� ǥ�ÿ��� (Y/N)</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>? : ��õ��ǰ ���߰��� ǥ�ÿ���(Y/N)</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>? : ��õ��ǰ ������ ǥ�ÿ���(Y/N)</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>? : ��õ��ǰ �±� ǥ�ð���(0-9) : 0�� ��� ǥ�þ���</FONT>
										<br>
										<FONT class=font_blue>��) [HOTITEM304YYYY4]</FONT>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[SPEITEM0?????]</td>
							<td class=td_con1 style="padding-left:5;">
							Ư����ǰ - ��������� Ư����ǰ ����
										<br><img width=10 height=0>
										<FONT class=font_orange>? : ������ǰ����(1-9)</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>? : Ư����ǰ Ÿ��Ʋ ǥ�ÿ���(Y/N)</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>? : Ư����ǰ ���߰��� ǥ�ÿ���(Y/N) </FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>? : Ư����ǰ ������ ǥ�ÿ���(Y/N)</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>? : Ư����ǰ �±� ǥ�ð���(0-9) : 0�� ��� ǥ�þ���</FONT>
										<br>
										<FONT class=font_blue>��) [SPEITEM05YYY2]</FONT>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[SPEITEM1??]</td>
							<td class=td_con1 style="padding-left:5;">
							Ư����ǰ - �̹���A��
										<br><img width=10 height=0>
										<FONT class=font_orange>? : ���κ� ��ǰ����(1~8)</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>? : ��������� ������ �Ұ��� �����Է�(1-8)</FONT>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[SPEITEM2??]</td>
							<td class=td_con1 style="padding-left:5;">
							Ư����ǰ - �̹���B��
										<br><img width=10 height=0>
										<FONT class=font_orange>? : ���κ� ��ǰ����(1~8)</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>? : ��������� ������ �Ұ��� �����Է�(1-8)</FONT>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[SPEITEM?????????_??]</td>
							<td class=td_con1 style="padding-left:5;">
							Ư����ǰ - �̹���A��/�̹���B��
										<br><img width=10 height=0>
										<FONT class=font_orange>? : ���� ������ Ư����ǰ ���� (1:�̹���A��, 2:�̹���B��)</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>? : ���κ� ��ǰ����(1~8)</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>? : ��������� ������ �Ұ��� �����Է�(1-8)</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>? : Ư����ǰ Ÿ��Ʋ ǥ�ÿ���(Y/N)</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>? : Ư����ǰ ������ ���ζ��� ǥ�ÿ���(Y/N/L)</FONT> (L�� ��ǰ�� ���߾� ��� ǥ�õ�)
										<br><img width=10 height=0>
										<FONT class=font_orange>? : Ư����ǰ ������ ���ζ��� ǥ�ÿ���(Y/N)</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>? : Ư����ǰ ���߰��� ǥ�ÿ���(Y/N)</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>? : Ư����ǰ ������ ǥ�ÿ���(Y/N)</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>? : Ư����ǰ �±� ǥ�ð���(0-9) : 0�� ��� ǥ�þ���</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>_?? : Ư����ǰ����(���Ʒ�) ���� �ִ� 99�ȼ� (���Է½� 5�ȼ�)</FONT>
										<br>
										<FONT class=font_blue>��) [SPEITEM142NNNYN2_10], [SPEITEM222YLYYY2_5]</FONT>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[SPEITEM3??]</td>
							<td class=td_con1 style="padding-left:5;">
							Ư����ǰ - ����Ʈ��
										<br><img width=10 height=0>
										<FONT class=font_orange>?? : Ư����ǰ �������� (01~20)</FONT>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[SPEITEM3???????]</td>
							<td class=td_con1 style="padding-left:5;">
							Ư����ǰ - ����Ʈ��
										<br><img width=10 height=0>
										<FONT class=font_orange>?? : Ư����ǰ �������� (01~20)</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>? : Ư����ǰ Ÿ��Ʋ ǥ�ÿ���(Y/N)</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>? : Ư����ǰ ������ ǥ�ÿ��� (Y/N)</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>? : Ư����ǰ ���߰��� ǥ�ÿ���(Y/N)</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>? : Ư����ǰ ������ ǥ�ÿ���(Y/N)</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>? : Ư����ǰ �±� ǥ�ð���(0-9) : 0�� ��� ǥ�þ���</FONT>
										<br>
										<FONT class=font_blue>��) [SPEITEM304YYYY4]</FONT>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right bgcolor=#E9A74E style="padding-right:15">������ǰ(�ű�/�α�/��õ/Ư��) ��Ÿ�� ����</td>
							<td class=td_con1 bgcolor=#FEEEE2 style="padding-left:5;">
										<FONT class=font_orange>#prlist_colline - �̹���/����Ʈ���� ���ζ��� �� ��Ÿ�� ����</FONT>
										<br><img width=100 height=0>
										<FONT class=font_blue>��) #prlist_colline { background-color:#f4f4f4;height:1px; }</FONT>
										<br><img width=0 height=7><br><img width=10 height=0>
										<FONT class=font_orange>#prlist_colline - �̹���/����Ʈ���� ���ζ��� �� ��Ÿ�� ����</FONT>
										<br><img width=100 height=0>
										<FONT class=font_blue>��) #prlist_rowline { background-color:#f4f4f4;width:1px; }</FONT>
							<pre style="line-height:15px">
<B>[��� ��]</B> - ���� ������ �Ʒ��� ���� �����Ͻø� �˴ϴ�.

   <FONT class=font_blue>&lt;style>
	  #prlist_colline { background-color:#f4f4f4;height:1px; }
	  #prlist_rowline { background-color:#f4f4f4;width:1px; }
   &lt;/style></FONT></pre>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[GONGGU]</td>
							<td class=td_con1 style="padding-left:5;">
							�������� ����ȭ�� ǥ��
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[GONGGUN]</td>
							<td class=td_con1 style="padding-left:5;">
							�������� ����ȭ�� ǥ��(Ÿ��Ʋ ����)
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[AUCTION]</td>
							<td class=td_con1 style="padding-left:5;">
							��� ����ȭ�� ǥ��
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[AUCTIONN]</td>
							<td class=td_con1 style="padding-left:5;">
							��� ����ȭ�� ǥ��(Ÿ��Ʋ ����)
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[TODAYSALE]</td>
							<td class=td_con1 style="padding-left:5;">
							�����̼��� <FONT class=font_blue>(��:&lt;a href=[TODAYSALE]>�����̼���&lt;/a>)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[NOTICE1]</td>
							<td class=td_con1 style="padding-left:5;">
							�⺻ �������� ���
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[NOTICE2]</td>
							<td class=td_con1 style="padding-left:5;">
							������¥���� ����տ� �ٴ� ���
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[NOTICE3]</td>
							<td class=td_con1 style="padding-left:5;">
							�պκп� �̹��� ǥ��
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[NOTICE4]</td>
							<td class=td_con1 style="padding-left:5;">
							�պκп� ���ڳ� ��¥ǥ�� ����
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[NOTICE?????_000]</td>
							<td class=td_con1 style="padding-left:5;">
							��������
										<br><img width=10 height=0>
										<FONT class=font_orange>? : ���� ������ �������� Ÿ��</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>? : Ÿ��Ʋ ǥ�ÿ���(Y/N)</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>? : �������� ����(1-9) ���Է½� 4�ȼ�</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>? : NEW ������ ǥ�ÿ��� (Y/N)</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>? : NEW ������ ǥ�ñⰣ (1-9)</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>_000 : ǥ�õ� �������� ���� (�ִ� ���� 200����)</FONT>
										<br>
										<FONT class=font_blue>��) [NOTICE1N5Y1_80]</FONT>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[INFO1]</td>
							<td class=td_con1 style="padding-left:5;">
							�⺻ ���������� ���
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[INFO2]</td>
							<td class=td_con1 style="padding-left:5;">
							�Խó�¥���� ����տ� �ٴ� ���
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[INFO3]</td>
							<td class=td_con1 style="padding-left:5;">
							�պκп� �̹��� ǥ��
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[INFO4]</td>
							<td class=td_con1 style="padding-left:5;">
							�պκп� ���ڳ� ��¥ǥ�� ����
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[INFO???_000]</td>
							<td class=td_con1 style="padding-left:5;">
							����������
										<br><img width=10 height=0>
										<FONT class=font_orange>? : ���� ������ ���������� Ÿ��</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>? : Ÿ��Ʋ ǥ�ÿ���(Y/N)</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>? : ���������� ����(1-9) ���Է½� 4�ȼ�</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>_000 : ǥ�õ� ���������� ���� (�ִ� ���� 200����)</FONT>
										<br>
										<FONT class=font_blue>��) [INFO1N5_80]</FONT>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[BANNER1]</td>
							<td class=td_con1 style="padding-left:5;">
							���� ���Ÿ��
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[BANNER2]</td>
							<td class=td_con1 style="padding-left:5;">
							���η� ǥ�õǴ� Ÿ��
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[POLL]</td>
							<td class=td_con1 style="padding-left:5;">
							���� ��ǥ���
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[POLL_TITLE]</td>
							<td class=td_con1 style="padding-left:5;">
							�������� ���������ν� ����
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[POLL_TITLE2]</td>
							<td class=td_con1 style="padding-left:5;">
							�������� ���������ν� ����-Ÿ��Ʋ �̹��� ����
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[POLL_CHOICE]</td>
							<td class=td_con1 style="padding-left:5;">
							�������� ���������ν� ��ǥ�׸�
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[POLL_BTN1]</td>
							<td class=td_con1 style="padding-left:5;">
							��ǥ�ϱ� ��ũ <FONT class=font_blue>(��:&lt;a href=[POLL_BTN1]>��ǥ&lt;/a>)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[POLL_BTN2]</td>
							<td class=td_con1 style="padding-left:5;">
							������� ��ũ <FONT class=font_blue>(��:&lt;a href=[POLL_BTN2]>�������&lt;/a>)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[REVIEW??????_000]</td>
							<td class=td_con1 style="padding-left:5;">
								��ǰ�� ǥ��
								<br><img width=10 height=0>
								<FONT class=font_orange>? : ��� ���� ��� (0:�ֱٵ�ϼ�, 1:����������)</FONT>
								<br><img width=10 height=0>
								<FONT class=font_orange>? : ��ǰ�� �� ��ũ ���(0:�˾����� ��ǰ�� ���, 1:��ǰ�� ��ǰ ��������)</FONT>
								<br><img width=10 height=0>
								<FONT class=font_orange>? : �ۼ����� ǥ�ù�� (0:�Խ����ڹ�ǥ��, 1:��/��, 2:��/��/��)</FONT>
								<br><img width=10 height=0>
								<FONT class=font_orange>? : ���ο� ǥ���� ��ǰ��� ����(1-9)</FONT>
								<br><img width=10 height=0>
								<FONT class=font_orange>? : ��ǰ�� �� ������ ����(0-9)</FONT>
								<br><img width=10 height=0>
								<FONT class=font_orange>? : ���� ǥ�� ����(Y/N)</FONT>
								<br><img width=10 height=0>
								<FONT class=font_orange>_000 : ǥ�õ� �Խñ� ���� (�ִ� ���� 200����)</FONT>
								<br>
								<FONT class=font_blue>��) [REVIEW10154Y_80], [REVIEW01254N_50]</FONT>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[BOARD?????_000_?]</td>
							<td class=td_con1 style="padding-left:5;">
							�Խ��� ǥ��
										<br><img width=10 height=0>
										<FONT class=font_orange>? : 1,2,3,4,5,6 �������� �Խ��ǿ� ���ؼ� �ֱ� �Խù� ����</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>? : �Խ����� ǥ�ù�� (0:�Խ����ڹ�ǥ��, 1:��/��, 2:��/��/��)</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>? : ���ο� ǥ���� �Խñ� ����(1-9)</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>? : �Խ��� �� ������ ����(0-9)</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>? : �亯�� ���� ����(Y/N)</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>_000 : ǥ�õ� �Խñ� ���� (�ִ� ���� 200����)</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>_? : �Խ��� �ڵ� (�ش� �Խ��ǿ� �ο��� �����ڵ�)</FONT>
										<br>
										<FONT class=font_blue>��) [BOARD1154N_80_<?=$_ShopInfo->getId()?>], [BOARD2154Y_50_<?=$_ShopInfo->getId()?>]</FONT>
							</td>
						</tr>



						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[CODEITEM?_�ش�ī�װ�_????????????]</td>
							<td class=td_con1 style="padding-left:5;">
							����ī�װ��� - �̹���A��/�̹���B��
										<br><img width=10 height=0>
										<FONT class=font_orange>? : 1:�̹���A��, 2:�̹���B��</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>? : 0:������</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>? : ���κ� ��ǰ����(1~8)</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>? : ��������� ������ �Ұ��� �����Է�(1-8)</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>? : ��ǰ ������ ���ζ��� ǥ�ÿ���(Y/N/L)</FONT> (L�� ��ǰ�� ���߾� ��� ǥ�õ�)
										<br><img width=10 height=0>
										<FONT class=font_orange>? : ��ǰ ������ ���ζ��� ǥ�ÿ���(Y/N)</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>? : ��ǰ �Һ��ڰ� ǥ�ÿ���(Y/N)</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>? : ��ǰ ������ ǥ�ÿ���(Y/N)</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>? : ��ǰ �±� ǥ�ð���(0-9) : 0�� ��� ǥ�þ���</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>? : ��ǰ ������ ǥ�ÿ���(Y/N)</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>? : ��ǰ ������ ǥ�ÿ���(Y/N)</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>? : ��ǰ �𵨸� ǥ�ÿ���(Y/N)</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>? : ��ǰ �귣�� ǥ�ÿ���(Y/N)</FONT>
										<br>
										<FONT class=font_blue>��)[CODEITEM1_001000000000_031NNNN0NNNN]</FONT>
							</td>
						</tr>

						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[LOGINFORM]</td>
							<td class=td_con1 style="padding-left:5;">�α��� ��</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[LOGINFORMU]</td>
							<td class=td_con1 style="padding-left:5;">�α��� �� �������� ����� ���� ǥ��</td>
						</tr>

						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[TELNUMBER]</td>
							<td class=td_con1 style="padding-left:5;">������ ��ȭ��ȣ<br />&nbsp;&nbsp;&nbsp;<FONT class=font_orange>�������� > ���� �⺻���� �������� ����� ����� ��ȭ��ȣ ǥ��</font></td>
						</tr>

						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[MAILADDR]</td>
							<td class=td_con1 style="padding-left:5;">������ �̸���<br />&nbsp;&nbsp;&nbsp;<FONT class=font_orange>�������� > ���� �⺻���� �������� ����� �̸��� �ּ� ǥ��</font></td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[ACCOUNTNUM]</td>
							<td class=td_con1 style="padding-left:5;">������ �Ա� ���¹�ȣ<br />&nbsp;&nbsp;&nbsp;<FONT class=font_orange>�������� > ���θ� ����� > ��ǰ �������� ��ɼ��� > ������ �������� �������� ���</font></td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						</table>
						</td>
					</tr>
					<tr>
						<td width="20" align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td width="701"><p class="LIPoint">����,�帲�������� �����ͷ� �ۼ��� �̹�����ε� �۾������� Ʋ���� �� ������ �����ϼ���!</p></td>
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