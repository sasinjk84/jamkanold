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
$code=$_POST["code"];
$intitle=$_POST["intitle"];

if(strlen($code)==0) {
	$code="1";
}


if($code=="1") {
	$ptype="tag";
	$pmsg="�α��±�";
} else if($code=="2") {
	$ptype="tagsearch";
	$pmsg="�±װ˻�";
}

if($intitle=="Y") {
	$leftmenu="Y";
} else {
	$leftmenu="N";
}

$insertKey = $ptype;


$subject = $pmsg." ȭ��";

// ��� / ����
if ( $type=="store" OR $type=="restore" ) {
	$MSG = adminDesingBackup ( $type, $ptype, $body, $subject, '', '', $leftmenu );
	$onload="<script>alert(\"".$MSG."\");</script>";
}



if($type=="update" && strlen($body)>0 && preg_match("/^(1|2){1}/", $code)) {

	$sql = "SELECT COUNT(*) as cnt FROM tbldesignnewpage WHERE type='".$ptype."' ";
	$result=mysql_query($sql,get_db_conn());
	$row=mysql_fetch_object($result);
	if($row->cnt==0) {
		$sql = "INSERT tbldesignnewpage SET ";
		$sql.= "type		= '".$ptype."', ";
		$sql.= "subject		= '".$pmsg." ȭ��', ";
		$sql.= "leftmenu	= '".$leftmenu."', ";
		$sql.= "body		= '".$body."' ";
		mysql_query($sql,get_db_conn());
	} else {
		$sql = "UPDATE tbldesignnewpage SET ";
		$sql.= "leftmenu	= '".$leftmenu."', ";
		$sql.= "body		= '".$body."' ";
		$sql.= "WHERE type='".$ptype."' ";
		mysql_query($sql,get_db_conn());
	}
	mysql_free_result($result);

	$sql = "UPDATE tblshopinfo SET design_".$ptype."='U' ";
	mysql_query($sql,get_db_conn());
	DeleteCache("tblshopinfo.cache");
	$onload="<script>alert(\"".$pmsg." ȭ�� ������ ������ �Ϸ�Ǿ����ϴ�.\");</script>";
} else if($type=="delete" && preg_match("/^(1|2){1}/", $code)) {
	if($code=="1") {
		$ptype="tag";
		$pmsg="�α��±�";
	} else if($code=="2") {
		$ptype="tagsearch";
		$pmsg="�±װ˻�";
	}

	$sql = "DELETE FROM tbldesignnewpage WHERE type='".$ptype."' ";
	mysql_query($sql,get_db_conn());

	$sql = "UPDATE tblshopinfo SET design_".$ptype."='001' ";
	mysql_query($sql,get_db_conn());
	DeleteCache("tblshopinfo.cache");
	$onload="<script>alert(\"".$pmsg." ȭ�� ������ ������ �Ϸ�Ǿ����ϴ�.\");</script>";
} else if($type=="clear" && preg_match("/^(1|2){1}/", $code)) {
	$intitle="";
	$body="";
	if($code=="1") {
		$sql = "SELECT body FROM tbldesigndefault WHERE type='tag' ";
	} else if($code=="2") {
		$sql = "SELECT body FROM tbldesigndefault WHERE type='tagsearch' ";
	}
	$result=mysql_query($sql,get_db_conn());
	if($row=mysql_fetch_object($result)) {
		$body=$row->body;
	}
	mysql_free_result($result);
}




if($type!="clear" && preg_match("/^(1|2){1}/", $code)) {
	if($code=="1") $ptype="tag";
	else if($code=="2") $ptype="tagsearch";

	$body="";
	$intitle="";
	$sql = "SELECT leftmenu,body FROM tbldesignnewpage WHERE type='".$ptype."' ";
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
			alert("������ ������ �Է��ϼ���.");
			document.form1.body.focus();
			return;
		}
		document.form1.type.value=type;
		document.form1.submit();
	} else if(type=="delete") {
		if(confirm("�������� �����Ͻðڽ��ϱ�?")) {
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
			alert("������ ������ �Է��ϼ���.");
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
	window.open("http://www.getmall.co.kr/macro/pages/tag_macro.html","tag_macro","height=800,width=680,scrollbars=no");
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
				<td height="28" class="link" align="left" background="images/con_link_bg.gif"><img src="images/top_link_house.gif" border="0" valign="absmiddle">������ġ : �����ΰ��� &gt; ����������-������ ���� &gt; <span class="2depth_select">�±� ȭ�� �ٹ̱�</span></td>
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
							<TD><IMG SRC="images/design_eachtag_title.gif" ALT=""></TD>
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
					<TD width="100%" class="notice_blue"><p>�α��±� �� �±װ˻� ȭ�� �������� �����Ӱ� ������ �Ͻ� �� �ֽ��ϴ�.</p></TD>
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
					<TD><IMG SRC="images/design_eachtag_stitle1.gif" WIDTH="192" HEIGHT=31 ALT=""></TD>
					<TD width="100%" background="images/shop_basicinfo_stitle_bg.gif">
						&nbsp;&nbsp;<a href="javascript:macroview();"><img src="images/btn_macroview.gif" border="0" align="absmiddle" alt="" /></a>&nbsp;&nbsp;<a href="http://www.getmall.co.kr/macro/data/tag_design.zip"><img src="images/btn_defaultcode.gif" border="0" align="absmiddle" alt="" /></a>
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
			<tr>
				<td height="3"></td>
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
					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">�±� ȭ�� ����</TD>
					<TD class="td_con1"><select name=code onchange="change_page(options.value)" style="width:330" class="input">
					<option value="1" <?if($code=="1")echo"selected";?>>�α��±�ȭ��</option>
					<option value="2" <?if($code=="2")echo"selected";?>>�±װ˻�ȭ��</option>
					</select></TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<TR>
					<TD colspan="2"><TEXTAREA style="WIDTH: 100%; HEIGHT: 300px" name=body class="textarea"><?=htmlspecialchars($body)?></TEXTAREA><br><input type=checkbox name=intitle value="Y" <?if($intitle=="Y")echo"checked";?>> <b><span style="letter-spacing:-0.5pt;"><span class="font_orange">�⺻ Ÿ��Ʋ �̹��� ���� - Ÿ��Ʋ ���� �κк��� ������ ����</span>(��üũ�� ���� Ÿ��Ʋ �̹��� ���������� ���� �����Ͽ� ���)</b></span></TD>
				</TR>
				</TABLE>
				</td>
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
						<td width="20" align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td ><p class="LIPoint"><B><span class="font_orange">�±� ȭ�� ��ũ�θ�ɾ�</span></B>(�ش� ��ũ�θ�ɾ�� �ٸ� ������ ������ �۾��� ����� �Ұ�����)</p></td>
					</tr>
					<tr>
						<td width="20" align="right" valign="top"><p>&nbsp;</p></td>
						<td >
						<table border=0 cellpadding=0 cellspacing=0 width=100%>
						<col width=150></col>
						<col width=></col>

						<?if($code=="1"){?>

						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[TAGDATESTART]</td>
							<td class=td_con1 style="padding-left:5;">
							����Ⱓ (���� ������) <FONT class=font_blue>(��:����Ⱓ : [TAGDATESTART] ~ [TAGDATEEND])</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[TAGDATEEND]</td>
							<td class=td_con1 style="padding-left:5;">
							����Ⱓ (���� ������) <FONT class=font_blue>(��:����Ⱓ : [TAGDATESTART] ~ [TAGDATEEND])</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[TAGSORT1]</td>
							<td class=td_con1 style="padding-left:5;">
							"�����ټ�" ���� ��ư <FONT class=font_blue>(��:&lt;a href=[TAGSORT1]>�����ټ� ����&lt;/a>)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[TAGSORT2]</td>
							<td class=td_con1 style="padding-left:5;">
							"�α��" ���� ��ư <FONT class=font_blue>(��:&lt;a href=[TAGSORT2]>�α�� ����&lt;/a>)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[TAGLIST]</td>
							<td class=td_con1 style="padding-left:5;">
							�±׸�� <FONT class=font_blue>(��:&lt;a href=[TAGLIST]>�±׸��&lt;/a>)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[TAGSEARCHINPUT_�Է��� ��Ÿ��]</td>
							<td class=td_con1 style="padding-left:5;">
							�±װ˻� �Է��� <FONT class=font_blue>(��:[TAGSEARCHINPUT_width:150px;])</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[TAGSEARCHOK]</td>
							<td class=td_con1 style="padding-left:5;">
							�±װ˻� ��ư <FONT class=font_blue>(��:&lt;a href=[TAGSEARCHOK]>�˻�&lt;/a>)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>

						<?}else if($code=="2"){?>

						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[TAGLINK]</td>
							<td class=td_con1 style="padding-left:5;">
							�α��±� �ٷΰ��� ��ũ <FONT class=font_blue>(��:&lt;a href=[TAGLINK]>�α��±� �ٷΰ���&lt;/a>)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[TAGSEARCHINPUT_�Է��� ��Ÿ��]</td>
							<td class=td_con1 style="padding-left:5;">
							�±װ˻� �Է��� <FONT class=font_blue>(��:[TAGSEARCHINPUT_width:150px;])</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[TAGSEARCHOK]</td>
							<td class=td_con1 style="padding-left:5;">
							�±װ˻� ��ư <FONT class=font_blue>(��:&lt;a href=[TAGSEARCHOK]>�˻�&lt;/a>)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[TAGKEYWORD]</td>
							<td class=td_con1 style="padding-left:5;">
							�±� �˻��� <FONT class=font_blue>(��:[TAGKEYWORD](��)�� �±װ� ��ϵǾ� �ִ� ��ǰ�Դϴ�.)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[TAGTOTAL]</td>
							<td class=td_con1 style="padding-left:5;">
							�±װ˻� ����Ǽ� <FONT class=font_blue>(��:�� [TAGTOTAL]���� ��ǰ�� �����մϴ�)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[IFTAG]<br>[IFELSETAG]<br>[IFENDTAG]</td>
							<td class=td_con1 style="padding-left:5;">
							�±װ˻� ����� ���� ���� ���� ���
							<pre style="line-height:15px">
<font color=blue>   <B>[IFTAG]</B>
      �±װ˻� ����� <FONT COLOR="red"><B>����</B></FONT> ����� ����
   <B>[IFELSETAG]</B>
      �±װ˻� ����� <FONT COLOR="red"><B>����</B></FONT> ����� ����
   <B>[IFENDTAG]</B></font></pre>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[FORTAG]<br>[FORENDTAG]</td>
							<td class=td_con1 style="padding-left:5;">
							[FORTAG] �˻��� ��ǰ �Ѱ��� ���� ���� [FORENDTAG]
							<pre style="line-height:15px">
<font color=blue>   [IFTAG]
       <B>[FORTAG]</B>��ǰ �ϳ��� ���� ���� ���<B>[FORENDTAG]</B>
   [IFELSETAG]
       [TAGKEYWORD](��)�� �±װ� ��ϵ� ��ǰ�� �����ϴ�.
   [IFENDTAG]</font></pre>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[TAG_PRIMG]</td>
							<td class=td_con1 style="padding-left:5;">
							��ǰ�̹���
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[TAG_PRNAME]</td>
							<td class=td_con1 style="padding-left:5;">
							��ǰ��
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[TAG_ADDCODE]</td>
							<td class=td_con1 style="padding-left:5;">
							��ǰƯ�̻���
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[TAG_PRTITLE]</td>
							<td class=td_con1 style="padding-left:5;">
							��ǰƯ�̻��װ� ��ǰ�� ���� ���
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[TAG_CONSUMPRICE]</td>
							<td class=td_con1 style="padding-left:5;">
							���߰��� <FONT class=font_blue>(��:[TAG_CONSUMPRICE]��)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[TAG_SELLPRICE]</td>
							<td class=td_con1 style="padding-left:5;">
							�ǸŰ��� <FONT class=font_blue>(��:[TAG_SELLPRICE]��)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[TAG_RESERVE]</td>
							<td class=td_con1 style="padding-left:5;">
							������ <FONT class=font_blue>(��:[TAG_RESERVE]��)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[TAG_COUNT]</td>
							<td class=td_con1 style="padding-left:5;">
							��� �±׼� <FONT class=font_blue>(��:&lt;B>[TAG_COUNT]&lt;/B>��)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell bgcolor=#F0F0F0>
							<pre style="line-height:15px">
  [FORPRTAG_?]
  [TAGNAME] [IFETC][ENDETC]
  [FORENDPRTAG]</pre>
							</td>
							<td class=td_con1 style="padding-left:5;">
							�ش��ǰ �±� �Ѱ��� ���� ������� <FONT class=font_orange>(_? : ����� �±� ����)</font>
							<pre style="line-height:15px">
<font color=blue> <B>[FORPRTAG_3]</B>
     [TAGNAME] - �±��̸� [IFETC][ENDETC]- �±��̸� �� ������ <br>
     <FONT class=font_blue>(��:[IFETC],[ENDETC])</font> <font color=red><B>���</B> : ��ǻ��<B>,</B>��Ʈ��<B>,</B>����ũž</font>
 <B>[FORENDPRTAG]</B></font></pre>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[TAG_QUICKVIEW]</td>
							<td class=td_con1 style="padding-left:5;">
							��ǰ ���� ��ư <FONT class=font_blue>(��:&lt;a href=[TAG_QUICKVIEW]>����&lt;/a>)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[TAG_WISH]</td>
							<td class=td_con1 style="padding-left:5;">
							���ø���Ʈ ��� ��ư <FONT class=font_blue>(��:&lt;a href=[TAG_WISH]>���ø���Ʈ&lt;/a>)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[TAG_BASKET]</td>
							<td class=td_con1 style="padding-left:5;">
							��ٱ��� ��� ��ư <FONT class=font_blue>(��:&lt;a href=[TAG_BASKET]>��ٱ���&lt;/a>)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[PAGE]</td>
							<td class=td_con1 style="padding-left:5;">
							������ ǥ��
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>

						<?}?>

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
	f.mode.value = 'tag';
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