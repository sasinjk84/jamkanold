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
	$ptype="prnew";
	$pmsg="�Ż�ǰ";
} else if($code=="2") {
	$ptype="prbest";
	$pmsg="�α��ǰ";
} else if($code=="3") {
	$ptype="prhot";
	$pmsg="��õ��ǰ";
} else if($code=="4") {
	$ptype="prspecial";
	$pmsg="Ư����ǰ";
}

if($intitle=="Y") {
	$leftmenu="Y";
} else {
	$leftmenu="N";
}


$insertKey = $ptype;

$subject = "���� ".$pmsg." ȭ��";

// ��� / ����
if ( $type=="store" OR $type=="restore" ) {
	$MSG = adminDesingBackup ( $type, $ptype, $body, $subject );
	$onload="<script>alert(\"".$MSG."\");</script>";
}




if($type=="update" && strlen($body)>0 && preg_match("/^(1|2|3|4){1}/", $code)) {

	$sql = "SELECT COUNT(*) as cnt FROM tbldesignnewpage WHERE type='".$ptype."' ";
	$result=mysql_query($sql,get_db_conn());
	$row=mysql_fetch_object($result);
	if($row->cnt==0) {
		$sql = "INSERT tbldesignnewpage SET ";
		$sql.= "type		= '".$ptype."', ";
		$sql.= "subject		= '���� ".$pmsg." ȭ��', ";
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
} else if($type=="delete" && preg_match("/^(1|2|3|4){1}/", $code)) {
	if($code=="1") {
		$ptype="prnew";
		$pmsg="�Ż�ǰ";
	} else if($code=="2") {
		$ptype="prbest";
		$pmsg="�α��ǰ";
	} else if($code=="3") {
		$ptype="prhot";
		$pmsg="��õ��ǰ";
	} else if($code=="4") {
		$ptype="prspecial";
		$pmsg="Ư����ǰ";
	}

	$sql = "DELETE FROM tbldesignnewpage WHERE type='".$ptype."' ";
	mysql_query($sql,get_db_conn());

	$sql = "UPDATE tblshopinfo SET design_".$ptype."='001' ";
	mysql_query($sql,get_db_conn());
	DeleteCache("tblshopinfo.cache");
	$onload="<script>alert(\"".$pmsg." ȭ�� ������ ������ �Ϸ�Ǿ����ϴ�.\");</script>";
} else if($type=="clear" && preg_match("/^(1|2|3|4){1}/", $code)) {
	$intitle="";
	$body="";
	if($code=="1") {
		$sql = "SELECT body FROM tbldesigndefault WHERE type='prnew' ";
	} else if($code=="2") {
		$sql = "SELECT body FROM tbldesigndefault WHERE type='prbest' ";
	} else if($code=="3") {
		$sql = "SELECT body FROM tbldesigndefault WHERE type='prhot' ";
	} else if($code=="4") {
		$sql = "SELECT body FROM tbldesigndefault WHERE type='prspecial' ";
	}
	$result=mysql_query($sql,get_db_conn());
	if($row=mysql_fetch_object($result)) {
		$body=$row->body;
	}
	mysql_free_result($result);
}

if($type!="clear" && preg_match("/^(1|2|3|4){1}/", $code)) {
	if($code=="1") $ptype="prnew";
	else if($code=="2") $ptype="prbest";
	else if($code=="3") $ptype="prhot";
	else if($code=="4") $ptype="prspecial";

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
	window.open("http://www.getmall.co.kr/macro/pages/section_macro.html","section_macro","height=800,width=680,scrollbars=no");
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



			<table cellpadding="0" cellspacing="0" width="100%">
			<tr><td height="8"></td></tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/design_eachsection_title.gif"ALT=""></TD>
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
					<TD width="100%" class="notice_blue"><p>���� ���Ǻ� ȭ�� �������� �����Ӱ� ������ �Ͻ� �� �ֽ��ϴ�.</p></TD>
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
					<TD><IMG SRC="images/design_eachsction_stitle1.gif" ALT=""></TD>
					<TD width="100%" background="images/shop_basicinfo_stitle_bg.gif">
						&nbsp;&nbsp;<a href="javascript:macroview();"><img src="images/btn_macroview.gif" border="0" align="absmiddle" alt="" /></a>&nbsp;&nbsp;<a href="http://www.getmall.co.kr/macro/data/section_design.zip"><img src="images/btn_defaultcode.gif" border="0" align="absmiddle" alt="" /></a>
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
				<td style="padding-top:3pt;">
				<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0 style="table-layout:fixed">
				<col width=139></col>
				<col width=></col>
				<TR>
					<TD colspan=2 background="images/table_top_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">���ǻ�ǰ ȭ�� ����</TD>
					<TD class="td_con1"><select name=code onchange="change_page(options.value)" style="width:330" class="input">
					<option value="1" <?if($code=="1")echo"selected";?>>�Ż�ǰ</option>
					<option value="2" <?if($code=="2")echo"selected";?>>�α��ǰ</option>
					<option value="3" <?if($code=="3")echo"selected";?>>��õ��ǰ</option>
					<option value="4" <?if($code=="4")echo"selected";?>>Ư����ǰ</option>
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
					<TD COLSPAN=3 width="100%" valign="top"  style="padding-top:8pt; padding-bottom:8pt; padding-left:4pt;"  class="menual_bg">
					<table cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td width="20" align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td ><p class="LIPoint"><B><span class="font_orange">��ǰ���� ȭ�� ��ũ�θ�ɾ�</span></B>(�ش� ��ũ�θ�ɾ�� �ٸ� ������ ������ �۾��� ����� �Ұ�����)</p></td>
					</tr>
					<tr>
						<td width="20" align="right" valign="top"><p>&nbsp;</p></td>
						<td >
						<table border=0 cellpadding=0 cellspacing=0 width=100%>
						<col width=150></col>
						<col width=></col>
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
	f.mode.value = 'section';
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