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



$subject = '��ǰ�˻� ���ȭ��';

$insertKey = 'search';

// ��� / ����
if ( $type=="store" OR $type=="restore" ) {
	$MSG = adminDesingBackup ( $type, $insertKey, $body, $subject, '', '', $leftmenu );
	$onload="<script>alert(\"".$MSG."\");</script>";
}


if($type=="update" && strlen($body)>0) {

	$sql = "SELECT COUNT(*) as cnt FROM tbldesignnewpage WHERE type='search' ";
	$result=mysql_query($sql,get_db_conn());
	$row=mysql_fetch_object($result);
	if($row->cnt==0) {
		$sql = "INSERT tbldesignnewpage SET ";
		$sql.= "type		= 'search', ";
		$sql.= "subject		= '��ǰ�˻� ���ȭ��', ";
		$sql.= "leftmenu	= '".$leftmenu."', ";
		$sql.= "body		= '".$body."' ";
		mysql_query($sql,get_db_conn());
	} else {
		$sql = "UPDATE tbldesignnewpage SET ";
		$sql.= "leftmenu	= '".$leftmenu."', ";
		$sql.= "body		= '".$body."' ";
		$sql.= "WHERE type='search' ";
		mysql_query($sql,get_db_conn());
	}
	mysql_free_result($result);

	$sql = "UPDATE tblshopinfo SET design_search='U' ";
	mysql_query($sql,get_db_conn());
	DeleteCache("tblshopinfo.cache");
	$onload="<script>alert(\"��ǰ�˻� ���ȭ�� ������ ������ �Ϸ�Ǿ����ϴ�.\");</script>";
} else if($type=="delete") {
	$sql = "DELETE FROM tbldesignnewpage WHERE type='search' ";
	mysql_query($sql,get_db_conn());

	$sql = "UPDATE tblshopinfo SET design_search='001' ";
	mysql_query($sql,get_db_conn());
	DeleteCache("tblshopinfo.cache");
	$onload="<script>alert(\"��ǰ�˻� ���ȭ�� ������ ������ �Ϸ�Ǿ����ϴ�.\");</script>";
} else if($type=="clear") {
	$intitle="";
	$body="";
	$sql = "SELECT body FROM tbldesigndefault WHERE type='search' ";
	$result=mysql_query($sql,get_db_conn());
	if($row=mysql_fetch_object($result)) {
		$body=$row->body;
	}
	mysql_free_result($result);
}

if($type!="clear") {
	$body="";
	$intitle="";
	$sql = "SELECT leftmenu,body FROM tbldesignnewpage WHERE type='search' ";
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
			alert("��ǰ�˻� ���ȭ�� ������ ������ �Է��ϼ���.");
			document.form1.body.focus();
			return;
		}
		document.form1.type.value=type;
		document.form1.submit();
	} else if(type=="delete") {
		if(confirm("��ǰ�˻� ���ȭ�� �������� �����Ͻðڽ��ϱ�?")) {
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
	window.open("http://www.getmall.co.kr/macro/pages/search_macro.html","search_macro","height=800,width=680,scrollbars=no");
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
				<td height="28" class="link" align="left" background="images/con_link_bg.gif"><img src="images/top_link_house.gif" border="0" valign="absmiddle">������ġ : �����ΰ��� &gt; ����������-������ ���� &gt; <span class="2depth_select">��ǰ�˻� ���ȭ�� �ٹ̱�</span></td>
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
					<TD><IMG SRC="images/design_searchview_title.gif"  ALT=""></TD>
					</tr>
					<tr>
					<TD width="100%" background="images/title_bg.gif"></TD>
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
					<TD width="100%" class="notice_blue"><p>��ǰ�˻� ���ȭ�� �������� �����Ӱ� ������ �Ͻ� �� �ֽ��ϴ�.</p></TD>
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
					<TD><IMG SRC="images/design_searchview_stitle.gif" WIDTH="250" HEIGHT=31 ALT=""></TD>
					<TD width="100%" background="images/shop_basicinfo_stitle_bg.gif">
						&nbsp;&nbsp;<a href="javascript:macroview();"><img src="images/btn_macroview.gif" border="0" align="absmiddle" alt="" /></a>&nbsp;&nbsp;<a href="http://www.getmall.co.kr/macro/data/search_design.zip"><img src="images/btn_defaultcode.gif" border="0" align="absmiddle" alt="" />
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
					<TD COLSPAN=3 width="100%" valign="top"style="padding-top:8pt; padding-bottom:8pt; padding-left:4pt;"  class="menual_bg">
					<table cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td width="20" align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td ><p class="LIPoint"><B><span class="font_orange">��ǰ�˻� ���ȭ�� ��ũ�θ�ɾ�</span></B>(�ش� ��ũ�θ�ɾ�� �ٸ� ������ ������ �۾��� ����� �Ұ�����)</p></td>
					</tr>
					<tr>
						<td width="20"></td>
						<td >
						<table border=0 cellpadding=0 cellspacing=0 width=100%>
						<col width=160></col>
						<col width=></col>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[CODEA_���ùڽ� ��Ÿ��]</td>
							<td class=td_con1 style="padding-left:5;">
								1��ī�װ� ���ùڽ� <FONT class=font_blue>(��:[CODEA_width:150px;color:#000000;font-size:11px])</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[CODEB_���ùڽ� ��Ÿ��]</td>
							<td class=td_con1 style="padding-left:5;">
								2��ī�װ� ���ùڽ� <FONT class=font_blue>(��:[CODEB_width:150px;color:#000000;font-size:11px])</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[CODEC_���ùڽ� ��Ÿ��]</td>
							<td class=td_con1 style="padding-left:5;">
								3��ī�װ� ���ùڽ� <FONT class=font_blue>(��:[CODEC_width:150px;color:#000000;font-size:11px])</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[CODED_���ùڽ� ��Ÿ��]</td>
							<td class=td_con1 style="padding-left:5;">
								4��ī�װ� ���ùڽ� <FONT class=font_blue>(��:[CODED_width:150px;color:#000000;font-size:11px])</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[MINPRICE_�Է��� ��Ÿ��]</td>
							<td class=td_con1 style="padding-left:5;">
								�������� �Է��� <FONT class=font_blue>(��:[MINPRICE_width:120px;color:#000000;font-size:11px])</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[MAXPRICE_�Է��� ��Ÿ��]</td>
							<td class=td_con1 style="padding-left:5;">
								�ְ��� �Է��� <FONT class=font_blue>(��:[MAXPRICE_width:120px;color:#000000;font-size:11px])</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[SCHECK_���ùڽ� ��Ÿ��]</td>
							<td class=td_con1 style="padding-left:5;">
								�˻���� ���ùڽ� <FONT class=font_blue>(��:[SCHECK_width:100px;color:#000000;font-size:11px])</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[KEYWORD_�Է��� ��Ÿ��]</td>
							<td class=td_con1 style="padding-left:5;">
								�˻��� �Է��� <FONT class=font_blue>(��:[KEYWORD_width:200px])</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">
								[SUB_SEARCH1_IF_START]<br />
								[SUB_SEARCH1_IF_END]
							</td>
							<td class=td_con1 style="padding-left:5;">
								��� �� �˻� 1 (�˻� Ű���尡 �������)<br /><br />
								<b>[��� ��]</b><br />
								<FONT class=font_blue>[SUB_SEARCH1_IF_START]<br />
									&nbsp;&nbsp;[KEYWORD1_width:200px;]<br />
								[SUB_SEARCH1_IF_END]</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>

						<tr>
							<td class=table_cell align=right style="padding-right:15">[KEYWORD1_�Է��� ��Ÿ��]</td>
							<td class=td_con1 style="padding-left:5;">
								����� �˻� 1 �˻��� �Է��� <FONT class=font_blue>(��:[KEYWORD1_width:200px])</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>

						<tr>
							<td class=table_cell align=right style="padding-right:15">
								[SUB_SEARCH2_IF_START]<br />
								[SUB_SEARCH2_IF_END]
							</td>
							<td class=td_con1 style="padding-left:5;">
								��� �� �˻� 2 (��� �� �˻� 1 Ű���尡 �������)<br /><br />
								<b>[��� ��]</b><br />
								<FONT class=font_blue>[SUB_SEARCH2_IF_START]<br />
									&nbsp;&nbsp;[KEYWORD2_width:200px;]<br />
								[SUB_SEARCH2_IF_END]</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[KEYWORD2_�Է��� ��Ÿ��]</td>
							<td class=td_con1 style="padding-left:5;">
								����� �˻� 2 �˻��� �Է��� <FONT class=font_blue>(��:[KEYWORD2_width:200px])</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>

						<tr>
							<td class=table_cell align=right style="padding-right:15">[SEARCHOK]</td>
							<td class=td_con1 style="padding-left:5;">
							�˻���ư <FONT class=font_blue>(��:&lt;a href=[SEARCHOK]>[�˻�]&lt;/a>)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[TOTAL]</td>
							<td class=td_con1 style="padding-left:5;">
							�� ��ǰ�� <FONT class=font_blue>(��:�� [TOTAL]��)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>

						<tr>
							<td class=table_cell align=right style="padding-right:15">[SORTNEW]</td>
							<td class=td_con1 style="padding-left:5;">
								�űԵ�� ��ǰ�� ����  <FONT class=font_blue>(��:&lt;a href=[SORTNEW]>�űԵ�ϼ�&lt;/a>)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[SORTBEST]</td>
							<td class=td_con1 style="padding-left:5;">
								�α��ǰ(�Ǹŷ�)�� ����  <FONT class=font_blue>(��:&lt;a href=[SORTBEST]>�α��ǰ��&lt;/a>)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>

						<tr>
							<td class=table_cell align=right style="padding-right:15">[SORTPRODUCTUP]</td>
							<td class=td_con1 style="padding-left:5;">
							������ �������� ����  <FONT class=font_blue>(��:&lt;a href=[SORTPRODUCTUP]>���������&lt;/a>)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[SORTPRODUCTDN]</td>
							<td class=td_con1 style="padding-left:5;">
							������ �������� ���� <FONT class=font_blue>(��:&lt;a href=[SORTPRODUCTDN]>���������&lt;/a>)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[SORTNAMEUP]</td>
							<td class=td_con1 style="padding-left:5;">
							��ǰ�� �������� ���� <FONT class=font_blue>(��:&lt;a href=[SORTNAMEUP]>��ǰ�����&lt;/a>)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[SORTNAMEDN]</td>
							<td class=td_con1 style="padding-left:5;">
							��ǰ�� �������� ���� <FONT class=font_blue>(��:&lt;a href=[SORTNAMEDN]>��ǰ�����&lt;/a>)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[SORTPRICEUP]</td>
							<td class=td_con1 style="padding-left:5;">
							���� ��ǰ���ݼ� <FONT class=font_blue>(��:&lt;a href=[SORTPRICEUP]>���ݼ���&lt;/a>)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[SORTPRICEDN]</td>
							<td class=td_con1 style="padding-left:5;">
							���� ��ǰ���ݼ� <FONT class=font_blue>(��:&lt;a href=[SORTPRICEDN]>���ݼ���&lt;/a>)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[SORTRESERVEUP]</td>
							<td class=td_con1 style="padding-left:5;">
							���� �����ݼ� <FONT class=font_blue>(��:&lt;a href=[SORTRESERVEUP]>�����ݼ���&lt;/a>)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[SORTRESERVEDN]</td>
							<td class=td_con1 style="padding-left:5;">
							���� �����ݼ� <FONT class=font_blue>(��:&lt;a href=[SORTRESERVEDN]>�����ݼ���&lt;/a>)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>

						<tr>
							<td class=table_cell align=right style="padding-right:15">[ONNEW]</td>
							<td class=td_con1 style="padding-left:5;">
								�űԵ�� ��ǰ�� ���� ǥ�� <FONT class=font_blue>(class="sortOn", /lib/style.php ���Ͽ��� css ����)</FONT>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[ONBEST]</td>
							<td class=td_con1 style="padding-left:5;">
								�α��ǰ�� ���� ǥ�� <FONT class=font_blue>(class="sortOn", /lib/style.php ���Ͽ��� css ����)</FONT>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[ONPRICEUP]</td>
							<td class=td_con1 style="padding-left:5;">
								���� ���ݼ� ���� ǥ�� <FONT class=font_blue>(class="sortOn", /lib/style.php ���Ͽ��� css ����)</FONT>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[ONPRICEDN]</td>
							<td class=td_con1 style="padding-left:5;">
								���� ���ݼ� ���� ǥ�� <FONT class=font_blue>(class="sortOn", /lib/style.php ���Ͽ��� css ����)</FONT>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[ONRESERVEDN]</td>
							<td class=td_con1 style="padding-left:5;">
								�����ݼ� ���� ǥ�� <FONT class=font_blue>(class="sortOn", /lib/style.php ���Ͽ��� css ����)</FONT>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[LISTSELECT]</td>
							<td class=td_con1 style="padding-left:5;">
								��ǰ��°��� ����
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
						<tr>
							<td class=table_cell align=right style="padding-right:15">[PRLIST1??]</td>
							<td class=td_con1 style="padding-left:5;">
							��ǰ��� - �̹���A��
										<br><img width=10 height=0>
										<FONT class=font_orange>? : ���κ� ��ǰ����(1~8)</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>? : ��������� ������ �Ұ��� �����Է�(1-8)</FONT>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[PRLIST2??]</td>
							<td class=td_con1 style="padding-left:5;">
							��ǰ��� - �̹���B��
										<br><img width=10 height=0>
										<FONT class=font_orange>? : ���κ� ��ǰ����(1~8)</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>? : ��������� ������ �Ұ��� �����Է�(1-8)</FONT>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[PRLIST????????_??]</td>
							<td class=td_con1 style="padding-left:5;">
							��ǰ��� - �̹���A��/�̹���B��
										<br><img width=10 height=0>
										<FONT class=font_orange>? : ���� ������ ��ǰ��� ���� (1:�̹���A��, 2:�̹���B��)</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>? : ���κ� ��ǰ����(1~8)</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>? : ��������� ������ �Ұ��� �����Է�(1-8)</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>? : ��ǰ ������ ���ζ��� ǥ�ÿ���(Y/N/L)</FONT> (L�� ��ǰ�� ���߾� ��� ǥ�õ�)
										<br><img width=10 height=0>
										<FONT class=font_orange>? : ��ǰ ������ ���ζ��� ǥ�ÿ���(Y/N)</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>? : ��ǰ ���߰��� ǥ�ÿ���(Y/N)</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>? : ��ǰ ������ ǥ�ÿ���(Y/N)</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>? : ��ǰ �±� ǥ�ð���(0-9) : 0�� ��� ǥ�þ���</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>_?? : ��ǰ����(���Ʒ�) ���� �ִ� 99�ȼ� (���Է½� 5�ȼ�)</FONT>
										<br>
										<FONT class=font_blue>��) [PRLIST142NNYN2_10], [PRLIST222LYYY2_5]</FONT>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[PRLIST3??]</td>
							<td class=td_con1 style="padding-left:5;">
							��ǰ��� - ����Ʈ��
										<br><img width=10 height=0>
										<FONT class=font_orange>?? : ��ǰ��� �������� (01~20)</FONT>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[PRLIST3???????]</td>
							<td class=td_con1 style="padding-left:5;">
							��ǰ��� - ����Ʈ��
										<br><img width=10 height=0>
										<FONT class=font_orange>?? : ��ǰ �������� (01~20)</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>? : ��ǰ �̹��� ǥ�ÿ��� (Y/N)</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>? : ��ǰ ������ ǥ�ÿ��� (Y/N)</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>? : ��ǰ ���߰��� ǥ�ÿ���(Y/N)</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>? : ��ǰ ������ ǥ�ÿ���(Y/N)</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>? : ��ǰ �±� ǥ�ð���(0-9) : 0�� ��� ǥ�þ���</FONT>
										<br>
										<FONT class=font_blue>��) [PRLIST304YYYY4]</FONT>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[PRLIST4??_??]</td>
							<td class=td_con1 style="padding-left:5;">
							��ǰ��� - ����������
										<br><img width=10 height=0>
										<FONT class=font_orange>? : ���κ� ��ǰ����(2~4)</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>? : ��������� ������ �Ұ��� �����Է�(1~8)</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>_?? : ��ǰ����(���Ʒ�) ���� �ִ� 99�ȼ� (���Է½� 5�ȼ�)</FONT>
										<br>
										<FONT class=font_blue>��) [PRLIST423_5]</FONT>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right bgcolor=#E9A74E style="padding-right:15">��ǰ��� ��Ÿ�� ����</td>
							<td class=td_con1 bgcolor=#FEEEE2 style="padding-left:5;">
										<img width=15 height=0><FONT class=font_orange>#prlist_colline - �̹���/����Ʈ���� ���ζ��� �� ��Ÿ�� ����</FONT>
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
	f.mode.value = 'search';
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