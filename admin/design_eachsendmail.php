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
$mail_type=$_POST["mail_type"];
$subject=$_POST["subject"];
$body=$_POST["body"];

$insertKey = $mail_type;

// ��� / ����
if ( $type=="store" OR $type=="restore" ) {
	$MSG = adminDesingBackup ( $type, $mail_type, $body, $subject );
	$onload="<script>alert(\"".$MSG."\");</script>";
}


if($type=="update" && strlen($mail_type)>0 && strlen($body)>0 && strlen($subject)>0) {
	$sql = "SELECT COUNT(*) as cnt FROM tbldesignnewpage WHERE type='".$mail_type."' ";
	$result=mysql_query($sql,get_db_conn());
	$row=mysql_fetch_object($result);
	if($row->cnt==0) {
		$sql = "INSERT tbldesignnewpage SET ";
		$sql.= "type		= '".$mail_type."', ";
		$sql.= "subject		= '".$subject."', ";
		$sql.= "body		= '".$body."' ";
		mysql_query($sql,get_db_conn());
	} else {
		$sql = "UPDATE tbldesignnewpage SET ";
		$sql.= "subject		= '".$subject."', ";
		$sql.= "body		= '".$body."' ";
		$sql.= "WHERE type='".$mail_type."' ";
		mysql_query($sql,get_db_conn());
	}
	mysql_free_result($result);
	$onload="<script>alert(\"�ش� ����ȭ�� ������ ������ �Ϸ�Ǿ����ϴ�.\");</script>";
} else if($type=="delete" && strlen($mail_type)>0) {
	$sql = "DELETE FROM tbldesignnewpage WHERE type='".$mail_type."' ";
	mysql_query($sql,get_db_conn());
	$onload="<script>alert(\"�ش� ����ȭ�� ������ ������ �Ϸ�Ǿ����ϴ�.\");</script>";
} else if($type=="clear" && strlen($mail_type)>0) {
	if($mail_type=="joinmail") {
		$subject="[SHOP] ���� ���� �����Դϴ�.";
	} else if($mail_type=="ordermail") {
		$subject="[SHOP] �ֹ������� Ȯ�� �����Դϴ�.";
	} else if($mail_type=="delimail") {
		$subject="[SHOP] ��ǰ �߼� �����Դϴ�.";
	} else if($mail_type=="bankmail") {
		$subject="[SHOP] �Ա� Ȯ�� �����Դϴ�.";
	} else if($mail_type=="passmail") {
		$subject="[SHOP] �н����� �ȳ������Դϴ�.";
	} else if($mail_type=="authmail") {
		$subject="[SHOP] ȸ�� ���� �����Դϴ�.";
	}
	$body="";
	$sql = "SELECT body FROM tbldesigndefault WHERE type='".$mail_type."' ";
	$result=mysql_query($sql,get_db_conn());
	if($row=mysql_fetch_object($result)) {
		$body=$row->body;
	}
	mysql_free_result($result);
}

if($type!="clear") {
	$subject="";
	$body="";
	if(strlen($mail_type)>0) {
		$sql = "SELECT subject,body FROM tbldesignnewpage WHERE type='".$mail_type."' ";
		$result = mysql_query($sql,get_db_conn());
		if($row=mysql_fetch_object($result)) {
			$subject=$row->subject;
			$body=$row->body;
		}
		mysql_free_result($result);
	}
}

?>

<? INCLUDE "header.php"; ?>

<script type="text/javascript" src="lib.js.php"></script>
<SCRIPT LANGUAGE="JavaScript">
<!--
function CheckForm(type) {
	if(type=="update") {
		if(document.form1.mail_type.value.length==0) {
			alert("�ش� ����ȭ���� �����ϼ���.");
			document.form1.mail_type.focus();
			return;
		}
		if(document.form1.subject.value.length==0) {
			alert("�ش� ���������� �Է��ϼ���.");
			document.form1.subject.focus();
			return;
		}
		if(document.form1.body.value.length==0) {
			alert("�ش� ����ȭ�� ������ ������ �Է��ϼ���.");
			document.form1.body.focus();
			return;
		}
		document.form1.type.value=type;
		document.form1.submit();
	} else if(type=="delete") {
		if(confirm("�ش� ����ȭ�� �������� �����Ͻðڽ��ϱ�?")) {
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
		if(document.form1.mail_type.value.length==0) {
			alert("�ش� ����ȭ���� �����ϼ���.");
			document.form1.mail_type.focus();
			return;
		}
		if(document.form1.subject.value.length==0) {
			alert("�ش� ���������� �Է��ϼ���.");
			document.form1.subject.focus();
			return;
		}
		if(document.form1.body.value.length==0) {
			alert("�ش� ����ȭ�� ������ ������ �Է��ϼ���.");
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

function change_page(val) {
	document.form1.type.value="change";
	document.form1.submit();
}

//��ũ�� ����(�˾�)
function macroview(){
	window.open("http://www.getmall.co.kr/macro/pages/sendmail_macro.html","sendmail_macro","height=800,width=680,scrollbars=no");
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
				<td height="28" class="link" align="left" background="images/con_link_bg.gif"><img src="images/top_link_house.gif" border="0" valign="absmiddle">������ġ : �����ΰ��� &gt; ����������-������ ���� &gt; <span class="2depth_select">���� ȭ�� �ٹ̱�</span></td>
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
					<TD><IMG SRC="images/design_eachsendmail_title.gif" ALT=""></TD>
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
					<TD width="100%" class="notice_blue"><p>���� ȭ�� �������� �����Ӱ� ������ �Ͻ� �� �ֽ��ϴ�.</p></TD>
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
					<TD><IMG SRC="images/design_eachsendmail_stitle1.gif" WIDTH="190" HEIGHT=31 ALT=""></TD>
					<TD width="100%" background="images/shop_basicinfo_stitle_bg.gif">
						&nbsp;&nbsp;<a href="javascript:macroview();"><img src="images/btn_macroview.gif" border="0" align="absmiddle" alt="" /></a>&nbsp;&nbsp;<a href="http://www.getmall.co.kr/macro/data/sendmail_design.zip"><img src="images/btn_defaultcode.gif" border="0" align="absmiddle" alt="" />
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
					<TD width="100%" class="notice_blue">1) �Ŵ����� <b>��ũ�θ��ɾ�</b>�� �����Ͽ� ������ �ϼ���. - ���Ϻ���  ���븸 ���� �����մϴ�.<br>2) [�⺻������]+[�����ϱ�] �ϸ� �⺻ ���ø��� ���������� ����˴ϴ�.<br>3) [�����ϱ�] -> ���� ����ϴ� ���� ���ø��� ���� ���������� ����˴ϴ�.(����ȭ���� ������ ���ø��� �������� �ʽ��ϴ�.)<br>4) <b>���� ���� �ʼ�, �̹��� ��� ���� ���θ� �ּ� �ݵ�� �Է�</b><br>&nbsp;&nbsp;&nbsp;&nbsp;(�� : http://www.abc.co.kr/design/����ID/�̹�����.gif �Ǵ� http://[URL]/design/����ID/�̹�����.gif)</TD>
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
				<td style="padding-top:3pt;">
				<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0 style="table-layout:fixed">
				<col width=139></col>
				<col width=></col>
				<TR>
					<TD colspan=2 background="images/table_top_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">���� ȭ�� ����</TD>
					<TD class="td_con1"><select name=mail_type onchange="change_page(options.value)" style="width:330px;" class="select">
						<option value="">���� ȭ���� �����ϼ���.</option>
<?
			$mail_list=array("�ű� ȸ������ ���� ����","�ֹ� ��û Ȯ�� ����","�ֹ� �߼� ����","�ֹ� �Ա� Ȯ�� ����","���̵�/�н����� �ȳ� ����","ȸ������ ���� (B2B �����ÿ���)");
			$mail_code=array("joinmail","ordermail","delimail","bankmail","passmail","authmail");
			for($i=0;$i<count($mail_list);$i++) {
				echo "<option value=\"".$mail_code[$i]."\" ";
				if($mail_type==$mail_code[$i]) echo "selected";
				echo ">".$mail_list[$i]."</option>\n";
			}
?>
						</select></TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">���� ����</TD>
					<TD class="td_con1"><input type=text name=subject value="<?=$subject?>" size=70 class="input" style="width:98%"></TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<TR>
					<TD colspan="2"><textarea name=body style="WIDTH: 100%; HEIGHT: 300px" class="textarea"><?=htmlspecialchars($body)?></textarea></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr><td height=10></td></tr>
			<tr>
				<td align="center"><a href="javascript:CheckForm('update');"><img src="images/botteon_save.gif" width="113" height="38" border="0"></a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:CheckForm('clear');"><img src="images/botteon_bok.gif" width="124" height="38" border="0" hspace="2"></a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:CheckForm('delete');"><img src="images/botteon_del.gif" width="113" height="38" border="0" hspace="0"></a><!-- &nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:CheckForm('preview');"><img src="images/botteon_prev.gif" width="113" height="38" border="0" hspace="2"></a> -->&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:CheckForm('store');"><img src="images/botteon_store.gif" border="0" hspace="2" alt="����ϱ�"></a>&nbsp;&nbsp;&nbsp;<a href="javascript:CheckForm('restore');"><img src="images/botteon_restore.gif" border="0" hspace="2" alt="��������ϱ�"></a></td>
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
						<td width="20" align="right" valign="top">&nbsp;</td>
						<td  style="padding-top:3pt; padding-bottom:10pt;">
						<table border=0 cellpadding=0 cellspacing=0 width=100%>
						<col width=150></col>
						<col width=></col>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell colspan=2 align=center>
							<B>�ű� ȸ������ ���� ����</B>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[SHOP]</td>
							<td class=td_con1 style="padding-left:5;">
							���θ� �̸� - ���� ���� �� ���뿡 ��밡��
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[NAME]</td>
							<td class=td_con1 style="padding-left:5;">
							���� ȸ�� �̸� - ���� ���� �� ���뿡 ��밡��
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[MESSAGE]</td>
							<td class=td_con1 style="padding-left:5;">
							�ű� ȸ������ ���� �޼��� - ���� ���뿡�� ��밡��
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[URL]</td>
							<td class=td_con1 style="padding-left:5;">
							���θ� URL - ���� ���뿡�� ��밡��
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr><td colspan=2 height=5></td></tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell colspan=2 align=center>
							<B>�ֹ� ��û Ȯ�� ����</B>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[SHOP]</td>
							<td class=td_con1 style="padding-left:5;">
							���θ� �̸� - ���� ���� �� ���뿡 ��밡��
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[NAME]</td>
							<td class=td_con1 style="padding-left:5;">
							���� ȸ�� �̸� - ���� ���� �� ���뿡 ��밡��
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[DATE]</td>
							<td class=td_con1 style="padding-left:5;">
							�ֹ����� - ���� ���񿡸� ��밡�� ��)2006�� 05�� 03��
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[CURDATE]</td>
							<td class=td_con1 style="padding-left:5;">
							�ֹ����� - ���� ���뿡�� ��밡�� ��)2006�� 05�� 03��
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[MAILDATA]</td>
							<td class=td_con1 style="padding-left:5;">
							�ֹ��� ���� - ���� ���뿡�� ��밡��
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[MESSAGE]</td>
							<td class=td_con1 style="padding-left:5;">
							�ֹ� �޼��� - ���� ���뿡�� ��밡��
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[URL]</td>
							<td class=td_con1 style="padding-left:5;">
							���θ� URL - ���� ���뿡�� ��밡��
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr><td colspan=2 height=5></td></tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell colspan=2 align=center>
							<B>�ֹ� �߼� ����</B>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[SHOP]</td>
							<td class=td_con1 style="padding-left:5;">
							���θ� �̸� - ���� ���� �� ���뿡 ��밡��
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[DELIVERYURL]</td>
							<td class=td_con1 style="padding-left:5;">
							�������� URL - ���� ���뿡�� ��밡��
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[DELIVERYNUM]</td>
							<td class=td_con1 style="padding-left:5;">
							�ù� �����ȣ - ���� ���뿡�� ��밡��
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[DELIVERYCOMPANY]</td>
							<td class=td_con1 style="padding-left:5;">
							�ù� ȸ��� - ���� ���뿡�� ��밡��
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[URL]</td>
							<td class=td_con1 style="padding-left:5;">
							���θ� URL - ���� ���뿡�� ��밡��
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[DELIVERYDATE]</td>
							<td class=td_con1 style="padding-left:5;">
							��۳�¥ - ���� ���뿡�� ��밡�� ��)2006/05/03
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[ORDERDATE]</td>
							<td class=td_con1 style="padding-left:5;">
							�ֹ���¥ - ���� ���뿡�� ��밡�� ��)2006/05/03
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15;line-height:17px">[IFDELICHANGE][ELSEDELICHANGE]   [ENDDELICHANGE]</td>
							<td class=td_con1 style="padding-left:5;line-height:17px">
							[IFDELICHANGE]��ǰ �߼� �� ���������� ����� ��� �޼���[ELSEDELICHANGE]
							<br>��ǰ�߼� �޼���[ENDDELICHANGE]
							<br>- ���� ���뿡�� ��밡��
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15;line-height:17px">[IFDELINUM] [ENDDELINUM]</td>
							<td class=td_con1 style="padding-left:5;">
							�����ȣ�� �����Ұ�� �޼��� �Է� - ���� ���뿡�� ��밡��
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15;line-height:17px">[IFDELIURL][ELSEDELIURL]   [ENDDELIURL]</td>
							<td class=td_con1 style="padding-left:5;line-height:17px">
							[IFDELIURL]��������ý����� �����Ұ�� �޼���[ELSEDELIURL]
							<br>��������ý����� �������� ������� �޼���[ENDDELIURL]
							<br>- ���� ���뿡�� ��밡��
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr><td colspan=2 height=5></td></tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell colspan=2 align=center>
							<B>�ֹ� �Ա� Ȯ�� ����</B>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[SHOP]</td>
							<td class=td_con1 style="padding-left:5;">
							���θ� �̸� - ���� ���� �� ���뿡 ��밡��
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[BANKDATE]</td>
							<td class=td_con1 style="padding-left:5;">
							�Ա�Ȯ�� ��¥ - ���� ���뿡�� ��밡�� ��)2006/05/03
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[ORDERDATE]</td>
							<td class=td_con1 style="padding-left:5;">
							�ֹ����� - ���� ���뿡�� ��밡�� ��)2006�� 05�� 03��
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[URL]</td>
							<td class=td_con1 style="padding-left:5;">
							���θ� URL - ���� ���뿡�� ��밡��
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr><td colspan=2 height=5></td></tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell colspan=2 align=center>
							<B>���̵�/�н����� �ȳ� ����</B>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[SHOP]</td>
							<td class=td_con1 style="padding-left:5;">
							���θ� �̸� - ���� ���� �� ���뿡 ��밡��
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[NAME]</td>
							<td class=td_con1 style="padding-left:5;">
							ȸ�� �̸� - ���� ���� �� ���뿡 ��밡��
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[ID]</td>
							<td class=td_con1 style="padding-left:5;">
							ȸ�� ���̵� - ���� ���뿡�� ��밡��
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[PASSWORD]</td>
							<td class=td_con1 style="padding-left:5;">
							ȸ�� ��й�ȣ - ���� ���뿡�� ��밡��
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[URL]</td>
							<td class=td_con1 style="padding-left:5;">
							���θ� URL - ���� ���뿡�� ��밡��
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr><td colspan=2 height=5></td></tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell colspan=2 align=center>
							<B>ȸ������ ���� (B2B���� �����ڰ� ȸ�������� �߼�)</B>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[SHOP]</td>
							<td class=td_con1 style="padding-left:5;">
							���θ� �̸� - ���� ���� �� ���뿡 ��밡��
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[ID]</td>
							<td class=td_con1 style="padding-left:5;">
							ȸ�� ���̵� - ���� ���뿡�� ��밡��
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[OKDATE]</td>
							<td class=td_con1 style="padding-left:5;">
							ȸ�� ������¥ - ���� ���뿡�� ��밡�� ��)2006/05/03
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[URL]</td>
							<td class=td_con1 style="padding-left:5;">
							���θ� URL - ���� ���뿡�� ��밡��
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
	if(document.form1.body.value.length==0) {
		alert("������ ������ �Է��ϼ���.");
		document.form1.body.focus();
		return;
	}

	f = document.prevForm;
	f.mode.value = 'sendmail';
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