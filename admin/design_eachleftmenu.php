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
$code=$_POST["code"];
$left_body=$_POST["left_body"];

if(strlen($code)==0) $code="ALL";


$insertKey = "leftmenu";

$subject = '���ʸ޴�ȭ��';
// ��� / ����
if ( $type=="store" OR $type=="restore" ) {
	if($code=="ALL") {
		$MSG = adminDesingBackup ( $type, $insertKey, $left_body, $subject, $code, '', '', 'tbldesign', 'body_left' );
	} else {
		$MSG = adminDesingBackup ( $type, $insertKey, $left_body, $subject, $code );
	}
	$onload="<script>alert(\"".$MSG."\");</script>";
}



if($type=="update" && strlen($left_body)>0) {
	//$left_body = ereg_replace("\"\[","[",$left_body);
	//$left_body = ereg_replace("]\"","]",$left_body);
	if($code=="ALL") {
		$sql = "SELECT COUNT(*) as cnt FROM tbldesign ";
		$result=mysql_query($sql,get_db_conn());
		$row=mysql_fetch_object($result);
		if($row->cnt==0) {
			$sql = "INSERT tbldesign SET ";
			$sql.= "body_left	= '".$left_body."' ";
			mysql_query($sql,get_db_conn());
		} else {
			$sql = "UPDATE tbldesign SET ";
			$sql.= "body_left	= '".$left_body."' ";
			mysql_query($sql,get_db_conn());
		}
		mysql_free_result($result);
	} else {
		$sql = "SELECT COUNT(*) as cnt FROM tbldesignnewpage ";
		$sql.= "WHERE type='leftmenu' AND code='".$code."' ";
		$result=mysql_query($sql,get_db_conn());
		$row=mysql_fetch_object($result);
		if($row->cnt==0) {
			$sql = "INSERT tbldesignnewpage SET ";
			$sql.= "type		= 'leftmenu', ";
			$sql.= "body		= '".$left_body."', ";
			$sql.= "code		= '".$code."' ";
			mysql_query($sql,get_db_conn());
		} else {
			$sql = "UPDATE tbldesignnewpage SET ";
			$sql.= "body		= '".$left_body."' ";
			$sql.= "WHERE type='leftmenu' AND code='".$code."' ";
			mysql_query($sql,get_db_conn());
		}
		mysql_free_result($result);
	}
	$onload="<script>alert(\"���ʸ޴�ȭ�� ������ ������ �Ϸ�Ǿ����ϴ�.\");</script>";
} else if($type=="delete") {
	if($code=="ALL") {
		$sql = "UPDATE tbldesign SET body_left='' ";
		mysql_query($sql,get_db_conn());
	} else {
		$sql = "DELETE FROM tbldesignnewpage WHERE type='leftmenu' AND code='".$code."' ";
		mysql_query($sql,get_db_conn());
	}
	$onload="<script>alert(\"���ʸ޴�ȭ�� ������ ������ �Ϸ�Ǿ����ϴ�.\");</script>";

}
if($type!="clear") {
	$left_body="";
	if($code=="ALL") {
		$sql = "SELECT * FROM tbldesign ";
		$result=mysql_query($sql,get_db_conn());
		if($row=mysql_fetch_object($result)) {
			$left_body=$row->body_left;
		}
		mysql_free_result($result);
	} else {
		$sql = "SELECT * FROM tbldesignnewpage WHERE type='leftmenu' AND code='".$code."' ";
		$result=mysql_query($sql,get_db_conn());
		if($row=mysql_fetch_object($result)) {
			$left_body=$row->body;
		}
		mysql_free_result($result);
	}
}
?>

<? INCLUDE "header.php"; ?>

<script type="text/javascript" src="lib.js.php"></script>
<script language="JavaScript">
function CheckForm(type) {
	if(type=="update") {
		if(document.form1.left_body.value.length==0) {
			alert("���ʸ޴�ȭ�� ������ ������ �Է��ϼ���.");
			document.form1.left_body.focus();
			return;
		}
		document.form1.type.value=type;
		document.form1.submit();
	} else if(type=="delete") {
		if(confirm("���ʸ޴�ȭ�� �������� �����Ͻðڽ��ϱ�?")) {
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
		}
	}
	// ����
	if(type=="restore") {
		if(confirm("<?=$subject?> �������� ������� �Ͻðڽ��ϱ�?\n\n���� �ϰ� �Ǹ� �ٷ� ������ ���� �˴ϴ�.")) {
			document.form1.type.value=type;
			document.form1.submit();
		}
	}


	// �̸�����
	if(type=="preview") {
		if( document.form1.code.value != 'ALL' ) {
			if(document.form1.left_body.value.length==0) {
				alert("���ʸ޴�ȭ�� ������ ������ �Է��ϼ���.");
				document.form1.left_body.focus();
				return;
			}
			document.form1.type.value='<?=$insertKey?>';
			document.form1.target="preview";
			document.form1.action="designPreview.php";
			document.form1.submit();
			document.form1.target="";
			document.form1.action="<?=$_SERVER[PHP_SELF]?>";
		} else {
			alert('�⺻�������� �̸����� �Ҽ� �����ϴ�.');
		}
	}

}

function change_page(val) {
	document.form1.type.value="change";
	document.form1.code.value=val;
	document.form1.submit();
}

//��ũ�� ����(�˾�)
function macroview(){
	window.open("http://www.getmall.co.kr/macro/pages/left_macro.html","top_macro","height=800,width=680,scrollbars=no");
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
				<td height="28" class="link" align="left" background="images/con_link_bg.gif"><img src="images/top_link_house.gif" border="0" valign="absmiddle">������ġ : �����ΰ��� &gt; ����������-���� �� ���ϴ�  &gt; <span class="2depth_select">���ʸ޴� �ٹ̱�</span></td>
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
						<TD><IMG SRC="images/design_eachleftmenu_title.gif"  ALT=""></TD>
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
						<td width="172" align=center><IMG SRC="images/design_eachleftmenu_img.gif" WIDTH="159" HEIGHT="100" ALT="" align="baseline"></td>
						<td  class="notice_blue" style="letter-spacing:-0.5pt;">1) ���ʸ޴��� ��ü������(default), �Ǵ� ī�װ���, �޴��� �����Ӱ� �������� �����մϴ�.<br>2) ���������� ���� �� <a href="javascript:parent.topframe.GoMenu(2,'design_option.php');"><span class="font_blue">�����ΰ��� > ��FTP �� �������� ���� > ���������� ���뼱��</span></a> �� �ؾ� ����˴ϴ�.
						<br><b>&nbsp;&nbsp;&nbsp;</b>���+���� ���� ����
						<br><b>&nbsp;&nbsp;&nbsp;</b>���ʸ� ����
						<br>3) <a href="javascript:parent.topframe.GoMenu(2,'design_easyleft.php');"><span class="font_blue">�����ΰ��� > Easy ������ ���� > Easy ���� �޴� ����</span></a> ���� �������� ������ �� �ֽ��ϴ�.</p></td>

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
			<tr><td height="50"></td></tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/design_eachleft_stitle1.gif" WIDTH="174" HEIGHT=31 ALT=""></TD>
					<TD width="100%" background="images/shop_basicinfo_stitle_bg.gif">&nbsp;&nbsp;<a href="javascript:macroview();"><img src="images/btn_macroview.gif" border="0" align="absmiddle" alt="" /></a>&nbsp;&nbsp;<a href="http://www.getmall.co.kr/macro/data/left_design.zip"><img src="images/btn_defaultcode.gif" border="0" align="absmiddle" alt="" /></a></TD>
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
						1) �Ŵ����� <b>��ũ�θ�ɾ�</b>�� �����Ͽ� ������ �ϼ���.<br /><br />
						2) <span class="font_orange" style="font-size:11px;"><u>���ʸ޴� ��ũ�� ��ɾ� ���� ����</u> : <b>/main/menup.php (���ʸ޴� ����), /main/nomenu.php (���ʸ޴� �̻���), /main/menu_text.php</b> (���� ������ ���� ������ �ݵ�� ����Ͻñ� �ٶ��ϴ�.)</span><br />
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
				<td>
				<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0 style="table-layout:fixed">
				<col width=139></col>
				<col width=></col>
				<TR>
					<TD colspan=2 background="images/table_top_line.gif"></TD>
				</TR>
				<form name=form1 action="<?=$_SERVER[PHP_SELF]?>" method=post>
				<input type=hidden name=type>
				<input type=hidden name=code value="<?=$code?>">
				<input type=hidden name="urls" value="<?=$urls?>">
				<TR>
					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">�ش� ������ ����</TD>
					<TD class="td_con1"><select name=plist onchange="change_page(options.value)" style="width:330" class="select">
						<option value="ALL" <?if($code=="ALL")echo"selected";?>>�⺻ ������ (Default)</option>
<?
			$sql = "SELECT codeA, code_name FROM tblproductcode WHERE (type='L' OR type='T' OR type='LX' OR type='TX') ORDER BY sequence DESC ";
			$result=mysql_query($sql,get_db_conn());
			$i=0;
			while($row=mysql_fetch_object($result)) {
				$i++;
				echo "<option value=\"".$row->codeA."\" ";
				if($code==$row->codeA) echo "selected";
				echo ">��з�".$i." - ".$row->code_name."</option>\n";
			}
			mysql_free_result($result);

			$page_list=array("���� ������ ���ʸ޴�","�Խ��� ���� ���ʸ޴�","ȸ�� ���� ���ʸ޴�","���������� ���� ���ʸ޴�","�ֹ��� ���� ���ʸ޴�","�˻� ���� ���ʸ޴�","�귣�� ��ǰ ��� ���� ���ʸ޴�","�귣��� ���� ���ʸ޴�","���� ������ ���ʸ޴�1","���� ������ ���ʸ޴�2","���� ������ ���ʸ޴�3","���� ������ ���ʸ޴�4","���� ������ ���ʸ޴�5","���� ������ ���ʸ޴�6","���� ������ ���ʸ޴�7","���� ������ ���ʸ޴�8","���� ������ ���ʸ޴�9","���� ������ ���ʸ޴�10");
			$page_code=array("MAI","BOA","MEM","MYP","ORD","SEA","BRL","BRM","NE0","NE1","NE2","NE3","NE4","NE5","NE6","NE7","NE8","NE9");

			for($i=0;$i<count($page_list);$i++) {
				echo "<option value=\"".$page_code[$i]."\" ";
				if($code==$page_code[$i]) echo "selected";
				echo ">".$page_list[$i]."</option>\n";
			}
?>
						</select></TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<TR>
					<TD colspan="2"><textarea name=left_body style="WIDTH: 100%; HEIGHT: 300px" class="textarea"><?=htmlspecialchars($left_body)?></textarea></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr><td height=10>&nbsp;</td></tr>
			<tr>
				<td align="center">
					<a href="javascript:CheckForm('update');"><img src="images/botteon_save.gif" width="113" height="38" border="0"></a>&nbsp;&nbsp;&nbsp;&nbsp;
					<a href="javascript:CheckForm('clear');"><img src="images/botteon_bok.gif" width="124" height="38" border="0" hspace="2"></a>&nbsp;&nbsp;&nbsp;&nbsp;
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
						<td><p class="LIPoint"><B><span class="font_orange">���ʸ޴� ��ũ�θ�ɾ�</span></B>(�ش� ��ũ�θ�ɾ�� �ٸ� ������ ������ �۾��� ����� �Ұ�����)</p></td>
					</tr>
					<tr>
						<td width="20" align="right" valign="top"></td>
						<td  style="padding-top:3pt; padding-bottom:10pt;">
						<table border=0 cellpadding=0 cellspacing=0 width=100%>
						<col width=150></col>
						<col width=></col>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[VISIT]</td>
							<td class=td_con1 style="padding-left:5;">
							�湮��ǥ��, �α��ν� �α׾ƿ� ǥ��
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[VISIT2]</td>
							<td class=td_con1 style="padding-left:5;">
							�湮��ǥ��, �α׾ƿ� ǥ�þȵ�
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[EMAIL]</td>
							<td class=td_con1 style="padding-left:5;">
							�̸��� <FONT class=font_blue>(��:&lt;a href=[EMAIL]>���� �Ǵ� ������&lt;/a>)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[RSS]</td>
							<td class=td_con1 style="padding-left:5;">
							RSS �ٷΰ��� <FONT class=font_blue>(��:&lt;a href=[RSS]>RSS&lt;/a>)</font>
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
							<td class=table_cell align=right style="padding-right:15">[LOGIN]</td>
							<td class=td_con1 style="padding-left:5;">
							�α��� <FONT class=font_blue>(��:&lt;a href=[LOGIN]>�α���&lt;/a>)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[LOGOUT]</td>
							<td class=td_con1 style="padding-left:5;">
							�α׾ƿ� <FONT class=font_blue>(��:&lt;a href=[LOGOUT]>�α׾ƿ�&lt;/a>)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[MEMBEROUT]</td>
							<td class=td_con1 style="padding-left:5;">
							ȸ��Ż�� <FONT class=font_blue>(��:&lt;a href=[MEMBEROUT]>ȸ��Ż��&lt;/a>)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[LOGINFORM]</td>
							<td class=td_con1 style="padding-left:5;">
							�α��� ��
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[LOGINFORMU]</td>
							<td class=td_con1 style="padding-left:5;">
							�α��� �� �������� ����� ���� ǥ��
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[BRANDMAP]</td>
							<td class=td_con1 style="padding-left:5;">
							�귣��� <FONT class=font_blue>(��:&lt;a href=[BRANDMAP]>�귣���&lt;/a>)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[REVIEW]</td>
							<td class=td_con1 style="padding-left:5;">
							����ı� ���� <FONT class=font_blue>(��:&lt;a href=[REVIEW]>����ı� ����&lt;/a>)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[ORDER]</td>
							<td class=td_con1 style="padding-left:5;">
							�ֹ���ȸ <FONT class=font_blue>(��:&lt;a href=[ORDER]>�ֹ���ȸ&lt;/a>)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[RESERVEVIEW]</td>
							<td class=td_con1 style="padding-left:5;">
							��������ȸ <FONT class=font_blue>(��:&lt;a href=[RESERVEVIEW]>��������ȸ&lt;/a>)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[MYPAGE]</td>
							<td class=td_con1 style="padding-left:5;">
							���������� <FONT class=font_blue>(��:&lt;a href=[MYPAGE]>����������&lt;/a>)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[MEMBER]</td>
							<td class=td_con1 style="padding-left:5;">
							ȸ������/���� <FONT class=font_blue>(��:&lt;a href=[MEMBER]>ȸ������/����&lt;/a>)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[AUCTION]</td>
							<td class=td_con1 style="padding-left:5;">
							��� <FONT class=font_blue>(��:&lt;a href=[AUCTION]>���&lt;/a>)</font>
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
							<td class=table_cell align=right style="padding-right:15">[GONGGU]</td>
							<td class=td_con1 style="padding-left:5;">
							�������� <FONT class=font_blue>(��:&lt;a href=[GONGGU]>��������&lt;/a>)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[SHOPTEL_������URL]</td>
							<td class=td_con1 style="padding-left:5;">
							���� ��ȭ��ȣ - <FONT class=font_orange>_������URL : ��ȭ��ȣ �տ� �ٴ� ������ URL ("_"���Ұ�)</FONT>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[BRANDLIST_000]</td>
							<td class=td_con1 style="padding-left:5;">
							�귣�� ���
							<br><img width=10 height=0>
							<FONT class=font_orange>_000 : �귣�� ��� ����</FONT>
							<br>
							<FONT class=font_blue>��) [BRANDLIST_200]</FONT>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right bgcolor=#E9A74E style="padding-right:15">�귣�� ��� ���� ��Ÿ�� ����</td>
							<td class=td_con1 bgcolor=#FEEEE2 style="padding-left:5;">
										<img width=10 height=0>
										<FONT class=font_orange>#brandlist_div - �귣�� ��� DIV ��Ÿ�� ���� (��׶����÷� �� ��ũ�ѹ�)</FONT>
										<br><img width=100 height=0>
										<FONT class=font_blue>��) #brandlist_div { background-color:#E6E6E6;<br><img width=100 height=0>
										scrollbar-face-color:#FFFFFF;<br><img width=100 height=0>
										scrollbar-arrow-color:#999999;<br><img width=100 height=0>
										scrollbar-track-color:#FFFFFF;<br><img width=100 height=0>
										scrollbar-highlight-color:#CCCCCC;<br><img width=100 height=0>
										scrollbar-3dlight-color:#FFFFFF;<br><img width=100 height=0>
										scrollbar-shadow-color:#CCCCCC;<br><img width=100 height=0>
										scrollbar-darkshadow-color:#FFFFFF; }</FONT>
										<br><img width=0 height=7><br><img width=10 height=0>
										<FONT class=font_orange>#brandlist_ul - �귣�� ��� UI ��Ÿ�� ���� (��׶����÷�)</FONT>
										<br><img width=100 height=0>
										<FONT class=font_blue>��) #brandlist_ul { background-color:#EFEFEF; }</FONT>
										<br><img width=0 height=7><br><img width=10 height=0>
										<FONT class=font_orange>#brandlist_li - �귣�� ��� LI ��Ÿ�� ���� (��׶����÷�)</FONT>
										<br><img width=100 height=0>
										<FONT class=font_blue>��) #brandlist_li { background-color:#FFFFFF;}</FONT>
				<pre style="line-height:15px">
<B>[��� ��]</B> - ���� ������ �Ʒ��� ���� �����Ͻø� �˴ϴ�.

<FONT class=font_blue>&lt;style>
  #brandlist_div { background-color:#E6E6E6;
  scrollbar-face-color:#FFFFFF;
  scrollbar-arrow-color:#999999;
  scrollbar-track-color:#FFFFFF;
  scrollbar-highlight-color:#CCCCCC;
  scrollbar-3dlight-color:#FFFFFF;
  scrollbar-shadow-color:#CCCCCC;
  scrollbar-darkshadow-color:#FFFFFF; }
  #brandlist_ul { background-color:#EFEFEF; }
  #brandlist_li { background-color:#FFFFFF; }
&lt;/style></FONT></pre>
							</td>
						</tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[BANNER]</td>
							<td class=td_con1 style="padding-left:5;">
							���ǥ�� (��ʰ� ���ʿ� ��ġ�� ���� ��쿡��)
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[LEFTEVENT]</td>
							<td class=td_con1 style="padding-left:5;">
							���� �̺�Ʈ/���˸�����
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
							<td class=table_cell align=right style="padding-right:15">[SPEITEM]</td>
							<td class=td_con1 style="padding-left:5;">
							Ÿ��Ʋ �̹����� �ִ� Ư����ǰ
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[SPEITEM_N]</td>
							<td class=td_con1 style="padding-left:5;">
							Ÿ��Ʋ �̹����� ���� Ư����ǰ
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[POLL]</td>
							<td class=td_con1 style="padding-left:5;">
							Ÿ��Ʋ �̹����� �ִ� ��ǥ
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[POLL_N]</td>
							<td class=td_con1 style="padding-left:5;">
							Ÿ��Ʋ �̹����� ���� ��ǥ
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[ESTIMATE]</td>
							<td class=td_con1 style="padding-left:5;">
							�¶��ΰ����� - &lt;a href=[ESTIMATE]>�¶��ΰ�����&lt;/a>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[PRLIST_??_???_?_���ζ��ι�׶���URL_������URL]</td>
							<td class=td_con1 style="padding-left:5;">
							��ǰ��з� �ڵ�ǥ�� - "_"���Ұ�
										<br><img width=10 height=0>
										<FONT class=font_orange>_?? : ��з� ������ ����(�ȼ�) - �������� ���� ��� "?" �Է�</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>_??? : ��з� ���̺��� ���� ����(�ȼ�) - �������� ���� ��� "?" �Է�</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>_? : ��з� ���̿� ���ζ��� ǥ�ÿ���(Y/N) - �������� ���� ��� "?" �Է�</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>���ζ��ι�׶���URL : �������� ���� ��� "?" �Է�</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>������URL : �������� ���� ��� "?" ���</FONT>
										<br>
										<FONT class=font_blue>��) [PRLIST_5_190_Y_���ζ��ι�׶���URL_������URL]</FONT>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[BOARDLIST_??_???_?_���ζ��ι�׶���URL_������URL]</td>
							<td class=td_con1 style="padding-left:5;">
							�Խ��Ǹ���Ʈ �ڵ�ǥ�� - "_"���Ұ�
										<br><img width=10 height=0>
										<FONT class=font_orange>_?? : �Խ��Ǹ���Ʈ ������ ����(�ȼ�) - �������� ���� ��� "?" �Է�</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>_??? : �Խ��Ǹ���Ʈ ���̺��� ���� ����(�ȼ�) - �������� ���� ��� "?" �Է�</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>_? : �Խ��Ǹ���Ʈ ���̿� ���ζ��� ǥ�ÿ���(Y/N) - �������� ���� ��� "?" �Է�</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>���ζ��ι�׶���URL : �������� ���� ��� "?" �Է�</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>������URL : �������� ���� ��� "?" ���</FONT>
										<br>
										<FONT class=font_blue>��) [BOARDLIST_5_190_Y_���ζ��ι�׶���URL_������URL]</FONT>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right bgcolor=#FBDED2 style="padding-right:15">[SEARCHFORMSTART]</td>
							<td class=td_con1 style="padding-left:5;">
							�˻��� ����
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right bgcolor=#FBDED2 style="padding-right:15">[SEARCHKEYWORD_000]</td>
							<td class=td_con1 style="padding-left:5;">
							�˻��� �˻��� �Է� �ؽ�Ʈ�� <FONT class=font_orange>(_000:�ؽ�Ʈ�� ������[�ȼ�����])</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right bgcolor=#FBDED2 style="padding-right:15">[SEARCHOK]</td>
							<td class=td_con1 style="padding-left:5;">
							�˻�Ȯ�� ��ư <FONT class=font_blue>(��:&lt;a href=[SEARCHOK]>�˻�&lt;/a>)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right bgcolor=#FBDED2 style="padding-right:15">[SEARCHFORMEND]</td>
							<td class=td_con1 style="padding-left:5;">
							�˻��� ��
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td colspan=2 style="padding:10">
							<pre style="line-height:15px">
<B>[�˻��� ��]</B>

	<FONT class=font_blue>&lt;table border=0 cellpadding=0 cellspacing=0>
	<B>[SEARCHFORMSTART]</B>
	&lt;tr>
	   &lt;td><B>[SEARCHKEYWORD_120]</B>&lt;/td>
	   &lt;td>&lt;a href=<B>[SEARCHOK]</B>>�˻�&lt;/a>&lt;/td>
	&lt;/tr>
	<B>[SEARCHFORMEND]</B>
	&lt;/table></FONT></pre>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						</table>
						</td>
					</tr>
					<tr>
						<td width="20" align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td><p class="LIPoint">����,�帲�������� �����ͷ� �ۼ��� �̹�����ε� �۾������� Ʋ���� �� ������ �����ϼ���!</p></td>
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
	if(document.form1.left_body.value.length==0) {
		alert("������ ������ �Է��ϼ���.");
		document.form1.left_body.focus();
		return;
	}

	f = document.prevForm;
	f.mode.value = 'menu';
	f.code.value = document.form1.left_body.value;
	f.submit();
}
</script>

<form name="prevForm" method="post" action="design_prev_post.php" target="_blank">
	<input type="hidden" name="code">
	<input type="hidden" name="mode">
</form>
<?=$onload?>

<? INCLUDE "copyright.php"; ?>