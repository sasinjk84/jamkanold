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

$subject = '���������� ����';

$insertKey = 'mycoupon';

// ��� / ����
if ( $type=="store" OR $type=="restore" ) {
	$MSG = adminDesingBackup ( $type, $insertKey, $body, $subject, '', '', $leftmenu );
	$MSG .= adminDesingBackup ( $type, 'design_mycoupon', 'U', $subject, '', '', '', 'tblshopinfo', 'design_mycoupon' );
	$onload="<script>alert(\"".$MSG."\");</script>";
}

if($type=="update" && strlen($body)>0) {

	$sql = "SELECT COUNT(*) as cnt FROM tbldesignnewpage WHERE type='mycoupon' ";
	$result=mysql_query($sql,get_db_conn());
	$row=mysql_fetch_object($result);
	if($row->cnt==0) {
		$sql = "INSERT tbldesignnewpage SET ";
		$sql.= "type		= 'mycoupon', ";
		$sql.= "subject		= '���������� ���� ȭ��', ";
		$sql.= "leftmenu	= '".$leftmenu."', ";
		$sql.= "body		= '".$body."' ";
		mysql_query($sql,get_db_conn());
	} else {
		$sql = "UPDATE tbldesignnewpage SET ";
		$sql.= "leftmenu	= '".$leftmenu."', ";
		$sql.= "body		= '".$body."' ";
		$sql.= "WHERE type='mycoupon' ";
		mysql_query($sql,get_db_conn());
	}
	mysql_free_result($result);

	$sql = "UPDATE tblshopinfo SET design_mycoupon='U' ";
	mysql_query($sql,get_db_conn());
	DeleteCache("tblshopinfo.cache");
	$onload="<script>alert(\"���������� ���� ȭ�� ������ ������ �Ϸ�Ǿ����ϴ�.\");</script>";
} else if($type=="delete") {
	$sql = "DELETE FROM tbldesignnewpage WHERE type='mycoupon' ";
	mysql_query($sql,get_db_conn());

	$sql = "UPDATE tblshopinfo SET design_mycoupon='001' ";
	mysql_query($sql,get_db_conn());
	DeleteCache("tblshopinfo.cache");
	$onload="<script>alert(\"���������� ���� ȭ�� ������ ������ �Ϸ�Ǿ����ϴ�.\");</script>";
} else if($type=="clear") {
	$intitle="";
	$body="";
	$sql = "SELECT body FROM tbldesigndefault WHERE type='mycoupon' ";
	$result=mysql_query($sql,get_db_conn());
	if($row=mysql_fetch_object($result)) {
		$body=$row->body;
	}
	mysql_free_result($result);
}

if($type!="clear") {
	$body="";
	$intitle="";
	$sql = "SELECT leftmenu,body FROM tbldesignnewpage WHERE type='mycoupon' ";
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
			alert("���������� ���� ȭ�� ������ ������ �Է��ϼ���.");
			document.form1.body.focus();
			return;
		}
		document.form1.type.value=type;
		document.form1.submit();
	} else if(type=="delete") {
		if(confirm("���������� ���� ȭ�� �������� �����Ͻðڽ��ϱ�?")) {
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
			alert("ȸ������ �Է��� ������ ������ �Է��ϼ���.");
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
	window.open("http://www.getmall.co.kr/macro/pages/coupon_macro.html","coupon_macro","height=800,width=680,scrollbars=no");
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

			<table cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed">
			<tr><td height="8"></td></tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/design_eachmycou_title.gif" ALT=""></TD>
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
					<TD width="100%" class="notice_blue">���������� ���� ȭ�� �������� �����Ӱ� ������ �Ͻ� �� �ֽ��ϴ�.</TD>
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
							<TD><IMG SRC="images/design_eachmycou_stitle1.gif"  ALT=""></TD>
							<TD width="100%" background="images/shop_basicinfo_stitle_bg.gif">
								&nbsp;&nbsp;<a href="javascript:macroview();"><img src="images/btn_macroview.gif" border="0" align="absmiddle" alt="" /></a>&nbsp;&nbsp;<a href="http://www.getmall.co.kr/macro/data/coupon_design.zip"><img src="images/btn_defaultcode.gif" border="0" align="absmiddle" alt="" />
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
						<td ><p class="LIPoint"><B><span class="font_orange">������ ȭ�� ��ũ�θ�ɾ�</span></B>(�ش� ��ũ�θ�ɾ�� �ٸ� ������ ������ �۾��� ����� �Ұ�����)</p></td>
					</tr>
					<tr>
						<td width="20" align="right" valign="top"><p>&nbsp;</p></td>
						<td >
						<table border=0 cellpadding=0 cellspacing=0 width=100%>
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
							<td class=table_cell align=right style="padding-right:15">[COUPON]</td>
							<td class=td_con1 style="padding-left:5;">
							������ <FONT class=font_blue>(��:[COUPON]��)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell width=180 align=right style="padding-right:15" nowrap>[IFCOUPON]<br>[IFELSECOUPON]<br>[IFENDCOUPON]</td>
							<td class=td_con1 width=100% style="padding-left:5;">
							��������Ʈ�� ������ ���� ���� ���� ���
							<pre style="line-height:15px">
<FONT class=font_blue>   <B>[IFCOUPON]</B>
      ������ <FONT COLOR="red"><B>����</B></FONT> ����� ����
   <B>[IFELSECOUPON]</B>
      ������ <FONT COLOR="red"><B>����</B></FONT> ����� ����
   <B>[IFENDCOUPON]</B></font></pre>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell width=180 align=right style="padding-right:15" nowrap>[FORCOUPON]<br>[FORENDCOUPON]</td>
							<td class=td_con1 width=100% style="padding-left:5;">
							[FORCOUPON] ���� �ϳ��� ���� ���� [FORENDCOUPON]
							<pre style="line-height:15px">
<FONT class=font_blue>   [IFCOUPON]
       <B>[FORCOUPON]</B>���� �ϳ��� ���� ���� ���<B>[FORENDCOUPON]</B>
   [IFELSECOUPON]
       ȸ���Կ��� �߱޵� ������ �����ϴ�.
   [IFENDCOUPON]</font></pre>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[COUPON_CODE]</td>
							<td class=td_con1 style="padding-left:5;">
							������ȣ
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[COUPON_SALE]</td>
							<td class=td_con1 style="padding-left:5;">
							����(����/����)
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[COUPON_PRNAME]</td>
							<td class=td_con1 style="padding-left:5;">
							�����ǰ
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[COUPON_NAME]</td>
							<td class=td_con1 style="padding-left:5;">
							������
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[COUPON_PRICE]</td>
							<td class=td_con1 style="padding-left:5;">
							���ѻ���
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[COUPON_DATE1]</td>
							<td class=td_con1 style="padding-left:5;">
							��밡�� ������
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[COUPON_DATE2]</td>
							<td class=td_con1 style="padding-left:5;">
							��밡�� ������ <FONT class=font_blue>(��:[COUPON_DATE1] ~ [COUPON_DATE2])</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[COUPON_DAYS]</td>
							<td class=td_con1 style="padding-left:5;">
							��������
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[AUTH_COUPONCODE]</td>
							<td class=td_con1 style="padding-left:5;">
							���� �ڵ� ���� â ���� ��ư ( �뷮 ���� �߼۽� ����� ���� �� �������� ������ �����ڵ� ��� ��Ͽ� �˾�â ȣ�� ��ư)
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
<?=$onload?>

<? INCLUDE "copyright.php"; ?>