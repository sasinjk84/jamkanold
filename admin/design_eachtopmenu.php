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
$top_body=$_POST["top_body"];
$top_height=(int)$_POST["top_height"];
if($top_height==0) $top_height=70;

if(strlen($code)==0) $code="ALL";

$insertKey = "topmenu";

$subject = '��ܸ޴�ȭ��';
// ��� / ����
if ( $type=="store" OR $type=="restore" ) {
	if($code=="ALL") {
		$MSG = adminDesingBackup ( $type, $insertKey, $top_body, $subject, $code, '', '', 'tbldesign', 'body_top' );
		$MSG = adminDesingBackup ( $type, $insertKey, $top_height, $subject, $code, '', '', 'tbldesign', 'top_height' );
	} else {
		$MSG = adminDesingBackup ( $type, $insertKey, $top_body, $subject, $code );
	}
	$onload="<script>alert(\"".$MSG."\");</script>";
}



if($type=="update" && strlen($top_body)>0) {
	//$top_body = ereg_replace("\"\[","[",$top_body);
	//$top_body = ereg_replace("]\"","]",$top_body);
	if($code=="ALL") {
		$sql = "SELECT COUNT(*) as cnt FROM tbldesign ";
		$result=mysql_query($sql,get_db_conn());
		$row=mysql_fetch_object($result);
		if($row->cnt==0) {
			$sql = "INSERT tbldesign SET ";
			$sql.= "body_top	= '".$top_body."', ";
			$sql.= "top_height	= '".$top_height."' ";
			mysql_query($sql,get_db_conn());
		} else {
			$sql = "UPDATE tbldesign SET ";
			$sql.= "body_top	= '".$top_body."', ";
			$sql.= "top_height	= '".$top_height."' ";
			mysql_query($sql,get_db_conn());
		}
		mysql_free_result($result);
	} else {
		$sql = "SELECT COUNT(*) as cnt FROM tbldesignnewpage ";
		$sql.= "WHERE type='topmenu' AND code='".$code."' ";
		$result=mysql_query($sql,get_db_conn());
		$row=mysql_fetch_object($result);
		if($row->cnt==0) {
			$sql = "INSERT tbldesignnewpage SET ";
			$sql.= "type		= 'topmenu', ";
			$sql.= "body		= '".$top_body."', ";
			$sql.= "code		= '".$code."' ";
			mysql_query($sql,get_db_conn());
		} else {
			$sql = "UPDATE tbldesignnewpage SET ";
			$sql.= "body		= '".$top_body."' ";
			$sql.= "WHERE type='topmenu' AND code='".$code."' ";
			mysql_query($sql,get_db_conn());
		}
		mysql_free_result($result);
	}
	$onload="<script>alert(\"��ܸ޴�ȭ�� ������ ������ �Ϸ�Ǿ����ϴ�.\");</script>";
} else if($type=="delete") {
	if($code=="ALL") {
		$sql = "UPDATE tbldesign SET body_top='' ";
		mysql_query($sql,get_db_conn());
	} else {
		$sql = "DELETE FROM tbldesignnewpage WHERE type='topmenu' AND code='".$code."' ";
		mysql_query($sql,get_db_conn());
	}
	$onload="<script>alert(\"��ܸ޴�ȭ�� ������ ������ �Ϸ�Ǿ����ϴ�.\");</script>";
} else if($type=="clear") {
	$top_body="";
	$sql = "SELECT body FROM tbldesigndefault WHERE type='topmenu' ";
	$result=mysql_query($sql,get_db_conn());
	if($row=mysql_fetch_object($result)) {
		$top_body=$row->body;
	}
	mysql_free_result($result);
}
if($type!="clear") {
	$top_body="";
	if($code=="ALL") {
		$sql = "SELECT * FROM tbldesign ";
		$result=mysql_query($sql,get_db_conn());
		if($row=mysql_fetch_object($result)) {
			$top_body=$row->body_top;
			$top_height=$row->top_height;
		}
		mysql_free_result($result);
	} else {
		$sql = "SELECT * FROM tbldesignnewpage WHERE type='topmenu' AND code='".$code."' ";
		$result=mysql_query($sql,get_db_conn());
		if($row=mysql_fetch_object($result)) {
			$top_body=$row->body;
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
		if(document.form1.top_body.value.length==0) {
			alert("��ܸ޴�ȭ�� ������ ������ �Է��ϼ���.");
			document.form1.top_body.focus();
			return;
		}
		try {
			if(!IsNumeric(document.form1.top_height.value)) {
				alert("��ܸ޴� ���̴� ���ڸ� �Է� �����մϴ�.");
				document.form1.top_height.focus();
				return;
			}
		} catch (e) {}
		document.form1.type.value=type;
		document.form1.submit();
	} else if(type=="delete") {
		if(confirm("��ܸ޴�ȭ�� �������� �����Ͻðڽ��ϱ�?")) {
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
			if(document.form1.top_body.value.length==0) {
				alert("��ܸ޴�ȭ�� ������ ������ �Է��ϼ���.");
				document.form1.top_body.focus();
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
	window.open("http://www.getmall.co.kr/macro/pages/top_macro.html","top_macro","height=800,width=680,scrollbars=no");
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
				<td height="28" class="link" align="left" background="images/con_link_bg.gif"><img src="images/top_link_house.gif" border="0" valign="absmiddle">������ġ : �����ΰ��� &gt; ����������-���� �� ���ϴ�  &gt; <span class="2depth_select">��ܸ޴� �ٹ̱�</span></td>
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
							<TD><IMG SRC="images/design_eachtop_title.gif"  ALT=""></TD>
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
						<td width="172" align=center><IMG SRC="images/design_eachtop_img.gif" WIDTH="159" HEIGHT="100" ALT="" align="baseline"></td>
						<td  class="notice_blue" style="letter-spacing:-0.5pt;">1) ��ܸ޴��� ��ü������(default), �Ǵ� ī�װ���, �޴��� �����Ӱ� �������� �����մϴ�.<br>2) ���������� ���� �� <a href="javascript:parent.topframe.GoMenu(2,'design_option.php');"><span class="font_blue">�����ΰ��� > ��FTP �� �������� ���� > ���������� ���뼱��</span></a> �� �ؾ� ����˴ϴ�.
						<br><b>&nbsp;&nbsp;&nbsp;</b>���+���� ���� ����
						<br><b>&nbsp;&nbsp;&nbsp;</b>��ܸ� ����
						<br>3) <a href="javascript:parent.topframe.GoMenu(2,'design_easytop.php');"><span class="font_blue">�����ΰ��� > Easy ������ ���� > Easy ��� �޴� ����</span></a> ���� �������� ������ �� �ֽ��ϴ�.</p>
						</td>
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
					<TD><IMG SRC="images/design_eachtop_stitle1.gif" WIDTH="174" HEIGHT=31 ALT=""></TD>
					<TD width="100%" background="images/shop_basicinfo_stitle_bg.gif">&nbsp;&nbsp;<a href="javascript:macroview();"><img src="images/btn_macroview.gif" border="0" align="absmiddle" alt="" /></a>&nbsp;&nbsp;<a href="http://www.getmall.co.kr/macro/data/top_design.zip"><img src="images/btn_defaultcode.gif" border="0" align="absmiddle" alt="" /></a></TD>
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
					<TD class="notice_blue"><IMG SRC="images/distribute_img.gif" alt="" /></TD>
					<TD width="100%" class="notice_blue">
						1) �Ŵ����� <b>��ũ�� ��ɾ�</b>�� �����Ͽ� ������ �ϼ���.<br />
						2) <span class="font_orange" style="font-size:11px;"><u>��ܸ޴� ��ũ�� ��ɾ� ���� ����</u> : <b>/main/topp.php</b> (���� ������ ���� ������ �ݵ�� ����Ͻñ� �ٶ��ϴ�.) </span><br />
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
			<tr>
				<td height=20></td>
			</tr>
			<form name=form1 action="<?=$_SERVER[PHP_SELF]?>" method=post>
			<input type=hidden name=type>
			<input type=hidden name=code value="<?=$code?>">
			<input type=hidden name="urls" value="<?=$urls?>">
			<tr>
				<td>
				<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0  style="table-layout:fixed">
				<col width=139></col>
				<col width=></col>
				<TR>
					<TD colspan=2 background="images/table_top_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">�ش� ������ ����</TD>
					<TD class="td_con1"><select name=plist onchange="change_page(options.value)" style="width:330" class="select">
						<option value="ALL" <?if($code=="A")echo"selected";?>>�⺻ ������ (Default)</option>
<?
			$sql = "SELECT codeA, code_name FROM tblproductcode ";
			$sql.= "WHERE (type='L' OR type='T' OR type='LX' OR type='TX') ORDER BY sequence DESC ";
			$result=mysql_query($sql,get_db_conn());
			$i=0;
			while($row=mysql_fetch_object($result)) {
				$i++;
				echo "<option value=\"".$row->codeA."\" ";
				if($code==$row->codeA) echo "selected";
				echo ">��з�".$i." - ".$row->code_name."</option>\n";
			}
			mysql_free_result($result);

			$page_list=array("���� ������ ��ܸ޴�","�Խ��� ���� ��ܸ޴�","ȸ�� ���� ��ܸ޴�","���������� ���� ��ܸ޴�","�ֹ��� ���� ��ܸ޴�","�˻� ���� ��ܸ޴�","�귣�� ��ǰ ��� ���� ��ܸ޴�","�귣��� ���� ��ܸ޴�");
			$page_code=array("MAI","BOA","MEM","MYP","ORD","SEA","BRL","BRM");

			for($i=0;$i<count($page_list);$i++) {
				echo "<option value=\"".$page_code[$i]."\" ";
				if($code==$page_code[$i]) echo "selected";
				echo ">".$page_list[$i]."</option>\n";
			}
?>
						</select>
						<?if($code!="ALL"){?>
						&nbsp; <span class="font_orange"><b>* �������� Ÿ�Կ����� ����˴ϴ�.</b></span>
						<?}?>
					</TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<?if($code=="ALL"){?>
				<TR>
					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">��ܸ޴� ����</TD>
					<TD class="td_con1"><input type=text name=top_height value="<?=$top_height?>" size=3 maxlength=3 onkeyup="strnumkeyup(this)" class="input">�ȼ�</TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<?}?>
				<TR>
					<TD colspan="2">
<textarea name=top_body style="WIDTH: 100%; HEIGHT: 300px" class="textarea">
<?=htmlspecialchars($top_body)?>
��ܸ޴��� ���� ���ø����� �����ϰ� �ֽ��ϴ�.
(�뿩��/�ݳ��� ���ǰ˻����� ���� ���������� ���Ұ�)

���ø� ������ FTP �� ���� ���ø� ������ �ٿ�ε��Ͻ� �� ������ �����մϴ�.
- ��� ������ ���ø� ���� ��� : /main/top003.php
(���ø� ���� ������ ���� ������ �ݵ�� ��� �� �����Ͻñ� �ٶ��ϴ�.-�������� ��� �������� ���� �Ұ�)
</textarea>
					</TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr><td height=10></td></tr>
			<tr>
				<td align="center">
					<!--
					<a href="javascript:CheckForm('update');"><img src="images/botteon_save.gif" width="113" height="38" border="0"></a>&nbsp;&nbsp;&nbsp;
					<a href="javascript:CheckForm('clear');"><img src="images/botteon_bok.gif" width="124" height="38" border="0" hspace="2"></a>&nbsp;&nbsp;&nbsp;
					<a href="javascript:CheckForm('delete');"><img src="images/botteon_del.gif" width="113" height="38" border="0" hspace="0"></a>
					<a href="javascript:CheckForm('preview');"><img src="images/botteon_prev.gif" border="0" hspace="2"></a>&nbsp;&nbsp;&nbsp;&nbsp;
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
					<TD COLSPAN=3 width="100%" valign="top" class=menual_bg style="padding-top:8pt; padding-bottom:8pt; padding-left:4pt;"  class="menual_bg">
					<table cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td width="20" align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td ><p class="LIPoint"><B><span class="font_orange">��ܸ޴� ��ũ�θ�ɾ�</span></B>(�ش� ��ũ�θ�ɾ�� �ٸ� ������ ������ �۾��� ����� �Ұ�����)</p></td>
					</tr>
					<tr>
						<td width="20" align="right" valign="top">&nbsp;</td>
						<td   style="padding-top:3pt; padding-bottom:10pt;">

						<table border=0 cellpadding=0 cellspacing=0 width=100%>
						<col width=150></col>
						<col width=></col>
						<col width=150></col>
						<col width=></col>
						<tr><td colspan="4" height="1" bgcolor="#dddddd"></td></tr>
						<tr>
							<td class="table_cell" align="right" style="padding-right:15px;">[VISIT]</td>
							<td class="td_con1" colspan="3" style="padding-left:5px;">�湮��ǥ��, �α��ν� �α׾ƿ� ǥ��</td>
						</tr>
						<tr><td colspan="4" height="1" bgcolor="#dddddd"></td></tr>

						<tr>
							<td class="table_cell" align="right" style="padding-right:15px;">[VISIT2]</td>
							<td class="td_con1" colspan="3" style="padding-left:5px;">�湮��ǥ��, �α׾ƿ� ǥ�þȵ�</td>
						</tr>
						<tr><td colspan="4" height="1" bgcolor="#dddddd"></td></tr>

						<tr>
							<td class="table_cell" align="right" style="padding-right:15px;">[HOME]</td>
							<td class="td_con1" style="padding-left:5px;">HOME <FONT class=font_blue>(��:&lt;a href=[HOME]>HOME&lt;/a&gt;)</FONT></td>
							<td class="table_cell" align="right" style="padding-right:15px;">��ü��� ����</td>
							<td class="td_con1">/main/main.php <span class="font_blue">(��:&lt;a href="/main/main.php"&gt;HOME&lt;/a&gt;)</span></td>
						</tr>
						<tr><td colspan="4" height="1" bgcolor="#dddddd"></td></tr>

						<tr>
							<td class="table_cell" align="right" style="padding-right:15px;">[USEINFO]</td>
							<td class="td_con1" style="padding-left:5px;">�̿�ȳ� <FONT class=font_blue>(��:&lt;a href=[USEINFO]>�̿�ȳ�&lt;/a&gt;)</font></td>
							<td class="table_cell" align="right" style="padding-right:15px;">��ü��� ����</td>
							<td class="td_con1">/front/useinfo.php <span class="font_blue">(��:&lt;a href="/front/useinfo.php"&gt;�̿�ȳ�&lt;/a&gt;)</span></td>
						</tr>
						<tr><td colspan="4" height="1" bgcolor="#dddddd"></td></tr>

						<tr>
							<td class="table_cell" align="right" style="padding-right:15px;">[MEMBER]</td>
							<td class="td_con1" style="padding-left:5px;">ȸ������/���� <FONT class=font_blue>(��:&lt;a href=[MEMBER]>ȸ������/����&lt;/a&gt;)</font></td>
							<td class="table_cell" align="right" style="padding-right:15px;">��ü��� ����</td>
							<td class="td_con1">/front/member_agree.php <span class="font_blue">(��:&lt;a href="/front/member_agree.php"&gt;ȸ������/����&lt;/a&gt;)</span></td>
						</tr>
						<tr><td colspan="4" height="1" bgcolor="#dddddd"></td></tr>

						<tr>
							<td class="table_cell" align="right" style="padding-right:15px;">[LOGIN_START]<br />[LOGIN_END]</td>
							<td class="td_con1" colspan="3" style="padding-left:5px;">
								<span style="line-height:30px;">�α׾ƿ� ������ �� ������ ����</span><br />
								<FONT class=font_blue>
									<b>[LOGIN_START]</b><br />
										&nbsp;&nbsp;&lt;a href=[LOGIN]&gt;�α���&lt/a&gt; | &lt;a href=[MEMBER]&gt;ȸ������&lt;/a&gt;<br />
									<b>[LOGIN_END]</b>
								</FONT>
							</td>
						</tr>
						<tr><td colspan="4" height="1" bgcolor="#dddddd"></td></tr>

						<tr>
							<td class="table_cell" align="right" style="padding-right:15px;">[LOGIN]</td>
							<td class="td_con1" style="padding-left:5px;">�α��� <FONT class=font_blue>(��:&lt;a href=[LOGIN]>�α���&lt;/a&gt;)</font></td>
							<td class="table_cell" align="right" style="padding-right:15px;">��ü��� ����</td>
							<td class="td_con1">/front/login.php <span class="font_blue">(��:&lt;a href="/front/login.php"&gt;�α���&lt;/a&gt;)</span></td>
						</tr>
						<tr><td colspan="4" height="1" bgcolor="#dddddd"></td></tr>
						<tr>
							<td class="table_cell" align="right" style="padding-right:15px;">[LOGOUT_START]<br />[LOGOUT_END]</td>
							<td class="td_con1" colspan="3" style="padding-left:5px;">
								<span style="line-height:30px;">�α��� ������ �� ������ ����</span><br />
								<FONT class=font_blue>
									<b>[LOGOUT_START]</b><br />
										&nbsp;&nbsp;&lt;a href=[LOGOUT]&gt;�α׾ƿ�&lt/a&gt; | &lt;a href=[MEMBER]&gt;ȸ����������&lt;/a&gt;<br />
									<b>[LOGOUT_END]</b>
								</FONT>
							</td>
						</tr>
						<tr><td colspan="4" height="1" bgcolor="#dddddd"></td></tr>

						<tr>
							<td class="table_cell" align="right" style="padding-right:15px;">[LOGOUT]</td>
							<td class="td_con1" style="padding-left:5px;">�α׾ƿ� <FONT class=font_blue>(��:&lt;a href=[LOGOUT]>�α׾ƿ�&lt;/a&gt;)</font></td>
							<td class="table_cell" align="right" style="padding-right:15px;">��ü��� ����</td>
							<td class="td_con1">javascript:logout(); <span class="font_blue">(��:&lt;a href="javascript:logout();"&gt;�α׾ƿ�&lt;/a&gt;)</span></td>
						</tr>
						<tr><td colspan="4" height="1" bgcolor="#dddddd"></td></tr>

						<tr>
							<td class="table_cell" align="right" style="padding-right:15px;">[MEMBEROUT]</td>
							<td class="td_con1" style="padding-left:5px;">ȸ��Ż�� <FONT class=font_blue>(��:&lt;a href=[MEMBEROUT]>ȸ��Ż��&lt;/a&gt;)</font></td>
							<td class="table_cell" align="right" style="padding-right:15px;">��ü��� ����</td>
							<td class="td_con1">/front/mypage_memberout.php <span class="font_blue">(��:&lt;a href="/front/mypage_memberout.php"&gt;ȸ��Ż��&lt;/a&gt;)</span></td>
						</tr>
						<tr><td colspan="4" height="1" bgcolor="#dddddd"></td></tr>

						<tr>
							<td class="table_cell" align="right" style="padding-right:15px;">[WELCOME]</td>
							<td class="td_con1" colspan="3" style="padding-left:5px;">ȸ�� �α��� �λ縻 (��:Guest ��, ȯ���մϴ�.)</td>
						</tr>
						<tr><td colspan="4" height="1" bgcolor="#dddddd"></td></tr>

						<tr>
							<td class="table_cell" align="right" style="padding-right:15px;">[LOGO]</td>
							<td class="td_con1" style="padding-left:5px;">�ΰ��̹��� <FONT class=font_blue>(��:&lt;a href=[HOME]>[LOGO]&lt;/a&gt;)</font></td>
						</tr>
						<tr><td colspan="4" height="1" bgcolor="#dddddd"></td></tr>

						<tr>
							<td class="table_cell" align="right" style="padding-right:15px;">[LOGINFORM]</td>
							<td class="td_con1" colspan="3" style="padding-left:5px;">�α��� ��</td>
						</tr>
						<tr><td colspan="4" height="1" bgcolor="#dddddd"></td></tr>

						<tr>
							<td class="table_cell" align="right" style="padding-right:15px;">[LOGINFORMU]</td>
							<td class="td_con1" colspan="3" style="padding-left:5px;">�α��� �� �������� ����� ���� ǥ��</td>
						</tr>
						<tr><td colspan="4" height="1" bgcolor="#dddddd"></td></tr>

						<tr>
							<td class="table_cell" align="right" style="padding-right:15px;">[BASKET]</td>
							<td class="td_con1" style="padding-left:5px;">��ٱ��� <FONT class=font_blue>(��:&lt;a href=[BASKET]>��ٱ���&lt;/a&gt;)</font></td>
							<td class="table_cell" align="right" style="padding-right:15px;">��ü��� ����</td>
							<td class="td_con1">/front/basket.php <span class="font_blue">(��:&lt;a href="/front/basket.php"&gt;��ٱ���&lt;/a&gt;)</span></td>
						</tr>
						<tr><td colspan="4" height="1" bgcolor="#dddddd"></td></tr>

						<tr>
							<td class="table_cell" align="right" style="padding-right:15px;">[BASKETCOUNT]</td>
							<td class="td_con1" colspan="3" style="padding-left:5px;">��ٱ��� ��ǰ����</td>
						</tr>
						<tr><td colspan="4" height="1" bgcolor="#dddddd"></td></tr>

						<tr>
							<td class="table_cell" align="right" style="padding-right:15px;">[ORDER]</td>
							<td class="td_con1" style="padding-left:5px;">�ֹ���ȸ <FONT class=font_blue>(��:&lt;a href=[ORDER]>�ֹ���ȸ&lt;/a&gt;)</font></td>
							<td class="table_cell" align="right" style="padding-right:15px;">��ü��� ����</td>
							<td class="td_con1">/front/mypage_orderlist.php <span class="font_blue">(��:&lt;a href="/front/mypage_orderlist.php"&gt;�ֹ���ȸ&lt;/a&gt;)</span></td>
						</tr>
						<tr><td colspan="4" height="1" bgcolor="#dddddd"></td></tr>

						<tr>
							<td class="table_cell" align="right" style="padding-right:15px;">[RESERVEVIEW]</td>
							<td class="td_con1" style="padding-left:5px;">��������ȸ <FONT class=font_blue>(��:&lt;a href=[RESERVEVIEW]>��������ȸ&lt;/a&gt;)</font></td>
							<td class="table_cell" align="right" style="padding-right:15px;">��ü��� ����</td>
							<td class="td_con1">/front/mypage_reserve.php <span class="font_blue">(��:&lt;a href="/front/mypage_reserve.php"&gt;��������ȸ&lt;/a&gt;)</span></td>
						</tr>
						<tr><td colspan="4" height="1" bgcolor="#dddddd"></td></tr>

						<tr>
							<td class="table_cell" align="right" style="padding-right:15px;">[MYPAGE]</td>
							<td class="td_con1" style="padding-left:5px;">���������� <FONT class=font_blue>(��:&lt;a href=[MYPAGE]>����������&lt;/a&gt;)</font></td>
							<td class="table_cell" align="right" style="padding-right:15px;">��ü��� ����</td>
							<td class="td_con1">/front/mypage.php <span class="font_blue">(��:&lt;a href="/front/mypage.php"&gt;����������&lt;/a&gt;)</span></td>
						</tr>
						<tr><td colspan="4" height="1" bgcolor="#dddddd"></td></tr>

						<tr>
							<td class="table_cell" align="right" style="padding-right:15px;">[REVIEW]</td>
							<td class="td_con1" style="padding-left:5px;">����ı� ���� <FONT class=font_blue>(��:&lt;a href=[REVIEW]>����ı� ����&lt;/a&gt;)</font></td>
							<td class="table_cell" align="right" style="padding-right:15px;">��ü��� ����</td>
							<td class="td_con1">/front/reviewall.php <span class="font_blue">(��:&lt;a href="/front/reviewall.php"&gt;����ı� ����&lt;/a&gt;)</span></td>
						</tr>
						<tr><td colspan="4" height="1" bgcolor="#dddddd"></td></tr>

						<tr>
							<td class="table_cell" align="right" style="padding-right:15px;">[BOARD]</td>
							<td class="td_con1" style="padding-left:5px;">�Խ��� <FONT class=font_blue>(��:&lt;a href=[BOARD]>�Խ���&lt;/a&gt;)</font></td>
							<td class="table_cell" align="right" style="padding-right:15px;">��ü��� ����</td>
							<td class="td_con1">/board/board.php?board=qna <span class="font_blue">(��:&lt;a href="/board/board.php?board=qna"&gt;�Խ���&lt;/a&gt;)</span></td>
						</tr>
						<tr><td colspan="4" height="1" bgcolor="#dddddd"></td></tr>

						<tr>
							<td class="table_cell" align="right" style="padding-right:15px;">[AUCTION]</td>
							<td class="td_con1" style="padding-left:5px;">��� <FONT class=font_blue>(��:&lt;a href=[AUCTION]>���&lt;/a&gt;)</font></td>
							<td class="table_cell" align="right" style="padding-right:15px;">��ü��� ����</td>
							<td class="td_con1">/auction/auction.php <span class="font_blue">(��:&lt;a href="/auction/auction.php"&gt;���&lt;/a&gt;)</span></td>
						</tr>
						<tr><td colspan="4" height="1" bgcolor="#dddddd"></td></tr>

						<tr>
							<td class="table_cell" align="right" style="padding-right:15px;">[TODAYSALE]</td>
							<td class="td_con1" style="padding-left:5px;">�����̼��� <FONT class=font_blue>(��:&lt;a href=[TODAYSALE]>�����̼���&lt;/a&gt;)</font></td>
							<td class="table_cell" align="right" style="padding-right:15px;">��ü��� ����</td>
							<td class="td_con1">/todayshop/ <span class="font_blue">(��:&lt;a href="/todayshop/"&gt;�����̼���&lt;/a&gt;)</span></td>
						</tr>
						<tr><td colspan="4" height="1" bgcolor="#dddddd"></td></tr>

						<tr>
							<td class="table_cell" align="right" style="padding-right:15px;">[GONGGU]</td>
							<td class="td_con1" style="padding-left:5px;">�������� <FONT class=font_blue>(��:&lt;a href=[GONGGU]>��������&lt;/a&gt;)</font></td>
							<td class="table_cell" align="right" style="padding-right:15px;">��ü��� ����</td>
							<td class="td_con1">/front/gonggu_main.php <span class="font_blue">(��:&lt;a href="/front/gonggu_main.php"&gt;��������&lt;/a&gt;)</span></td>
						</tr>
						<tr><td colspan="4" height="1" bgcolor="#dddddd"></td></tr>

						<tr>
							<td class="table_cell" align="right" style="padding-right:15px;">[USECOUPON_START]<br />[USECOUPON_END]</td>
							<td class="td_con1" style="padding-left:5px;">
								<span style="line-height:30px;">�������� ������� �� ������ ����</span><br />
								<FONT class=font_blue>
									<b>[USECOUPON_START]</b><br />
										&nbsp; &lt;a href=[COUPONALL]&gt;��������&lt;/a&gt;<br />
									<b>[USECOUPON_END]</b>
								</FONT>
							</td>
						</tr>
						<tr><td colspan="4" height="1" bgcolor="#dddddd"></td></tr>

						<tr>
							<td class="table_cell" align="right" style="padding-right:15px;">[COUPONALL]</td>
							<td class="td_con1" style="padding-left:5px;">�������� <FONT class=font_blue>(��:&lt;a href=[COUPONALL]>��������&lt;/a&gt;)</font></td>
							<td class="table_cell" align="right" style="padding-right:15px;">��ü��� ����</td>
							<td class="td_con1">/front/couponlist.php <span class="font_blue">(��:&lt;a href="/front/couponlist.php"&gt;��������&lt;/a&gt;)</span></td>
						</tr>
						<tr><td colspan="4" height="1" bgcolor="#dddddd"></td></tr>

						<tr>
							<td class="table_cell" align="right" style="padding-right:15px;">[PRODUCTGIFT]</td>
							<td class="td_con1" style="padding-left:5px;">�����̿�� ���� <FONT class=font_blue>(��:&lt;a href=[PRODUCTGIFT]>�����̿�� ����&lt;/a&gt;)</font></td>
							<td class="table_cell" align="right" style="padding-right:15px;">��ü��� ����</td>
							<td class="td_con1">/front/productgift.php <span class="font_blue">(��:&lt;a href="/front/productgift.php"&gt;�����̿�� ����&lt;/a&gt;)</span></td>
						</tr>
						<tr><td colspan="4" height="1" bgcolor="#dddddd"></td></tr>

						<tr>
							<td class="table_cell" align="right" style="padding-right:15px;">[HONGBOURL]</td>
							<td class="td_con1" style="padding-left:5px;">ȫ�� ������ <FONT class=font_blue>(��:&lt;a href=[HONGBOURL]>ȫ�� ������&lt;/a&gt;)</font></td>
							<td class="table_cell" align="right" style="padding-right:15px;">��ü��� ����</td>
							<td class="td_con1">javascript:win_hongboUrl(); <span class="font_blue">(��:&lt;a href="javascript:win_hongboUrl();"&gt;ȫ�� ������&lt;/a&gt;)</span></td>
						</tr>
						<tr><td colspan="4" height="1" bgcolor="#dddddd"></td></tr>

						<tr>
							<td class="table_cell" align="right" style="padding-right:15px;">[ESTIMATE]</td>
							<td class="td_con1" style="padding-left:5px;">�¶��ΰ����� <FONT class=font_blue>(��:&lt;a href=[ESTIMATE]>�¶��ΰ�����&lt;/a&gt;)</font></td>
							<td class="table_cell" align="right" style="padding-right:15px;">��ü��� ����</td>
							<td class="td_con1">/front/estimate.php <span class="font_blue">(��:&lt;a href="/front/estimate.php"&gt;�¶��ΰ�����&lt;/a&gt;)</span></td>
						</tr>
						<tr><td colspan="4" height="1" bgcolor="#dddddd"></td></tr>

						<tr>
							<td class="table_cell" align="right" style="padding-right:15px;">[COMPANY]</td>
							<td class="td_con1" style="padding-left:5px;">ȸ��Ұ� <FONT class=font_blue>(��:&lt;a href=[COMPANY]>ȸ��Ұ�&lt;/a&gt;)</font></td>
							<td class="table_cell" align="right" style="padding-right:15px;">��ü��� ����</td>
							<td class="td_con1">/front/estimate.php <span class="font_blue">(��:&lt;a href="/front/company.php"&gt;ȸ��Ұ�&lt;/a&gt;)</span></td>
						</tr>
						<tr><td colspan="4" height="1" bgcolor="#dddddd"></td></tr>

						<tr>
							<td class="table_cell" align="right" style="padding-right:15px;">[EMAIL]</td>
							<td class="td_con1" style="padding-left:5px;">�̸��� <FONT class=font_blue>(��:&lt;a href=[EMAIL]>������&lt;/a&gt;)</font></td>
							<td class="table_cell" align="right" style="padding-right:15px;">��ü��� ����</td>
							<td class="td_con1">javascript:sendmail(); <span class="font_blue">(��:&lt;a href="javascript:sendmail();"&gt;������&lt;/a&gt;)</span></td>
						</tr>
						<tr><td colspan="4" height="1" bgcolor="#dddddd"></td></tr>

						<tr>
							<td class="table_cell" align="right" style="padding-right:15px;">[PRODUCTNEW]</td>
							<td class="td_con1" style="padding-left:5px;">�űԻ�ǰ <FONT class=font_blue>(��:&lt;a href=[PRODUCTNEW]>�űԻ�ǰ&lt;/a&gt;)</font></td>
							<td class="table_cell" align="right" style="padding-right:15px;">��ü��� ����</td>
							<td class="td_con1">/front/productnew.php <span class="font_blue">(��:&lt;a href="/front/productnew.php"&gt;�űԻ�ǰ&lt;/a&gt;)</span></td>
						</tr>
						<tr><td colspan="4" height="1" bgcolor="#dddddd"></td></tr>

						<tr>
							<td class="table_cell" align="right" style="padding-right:15px;">[PRODUCTBEST]</td>
							<td class="td_con1" style="padding-left:5px;">�α��ǰ <FONT class=font_blue>(��:&lt;a href=[PRODUCTBEST]>�α��ǰ&lt;/a&gt;)</font></td>
							<td class="table_cell" align="right" style="padding-right:15px;">��ü��� ����</td>
							<td class="td_con1">/front/productbest.php <span class="font_blue">(��:&lt;a href="/front/productbest.php"&gt;�α��ǰ&lt;/a&gt;)</span></td>
						</tr>
						<tr><td colspan="4" height="1" bgcolor="#dddddd"></td></tr>

						<tr>
							<td class="table_cell" align="right" style="padding-right:15px;">[PRODUCTHOT]</td>
							<td class="td_con1" style="padding-left:5px;">��õ��ǰ <FONT class=font_blue>(��:&lt;a href=[PRODUCTHOT]>��õ��ǰ&lt;/a&gt;)</font></td>
							<td class="table_cell" align="right" style="padding-right:15px;">��ü��� ����</td>
							<td class="td_con1">/front/producthot.php <span class="font_blue">(��:&lt;a href="/front/producthot.php"&gt;��õ��ǰ&lt;/a&gt;)</span></td>
						</tr>
						<tr><td colspan="4" height="1" bgcolor="#dddddd"></td></tr>

						<tr>
							<td class="table_cell" align="right" style="padding-right:15px;">[PRODUCTSPECIAL]</td>
							<td class="td_con1" style="padding-left:5px;">Ư����ǰ <FONT class=font_blue>(��:&lt;a href=[PRODUCTSPECIAL]>Ư����ǰ&lt;/a&gt;)</font></td>
							<td class="table_cell" align="right" style="padding-right:15px;">��ü��� ����</td>
							<td class="td_con1">/front/productspecial.php <span class="font_blue">(��:&lt;a href="/front/productspecial.php"&gt;Ư����ǰ&lt;/a&gt;)</span></td>
						</tr>
						<tr><td colspan="4" height="1" bgcolor="#dddddd"></td></tr>

						<tr>
							<td class="table_cell" align="right" style="padding-right:15px;">[TAG]</td>
							<td class="td_con1" style="padding-left:5px;">�±� �ٷΰ��� <FONT class=font_blue>(��:&lt;a href=[TAG]>�±�&lt;/a&gt;)</font></td>
							<td class="table_cell" align="right" style="padding-right:15px;">��ü��� ����</td>
							<td class="td_con1">/front/tag.php <span class="font_blue">(��:&lt;a href="/front/tag.php"&gt;�±�&lt;/a&gt;)</span></td>
						</tr>
						<tr><td colspan="4" height="1" bgcolor="#dddddd"></td></tr>

						<tr>
							<td class="table_cell" align="right" style="padding-right:15px;">[RSS]</td>
							<td class="td_con1" style="padding-left:5px;">RSS �ٷΰ��� <FONT class=font_blue>(��:&lt;a href=[RSS]>RSS&lt;/a&gt;)</font></td>
							<td class="table_cell" align="right" style="padding-right:15px;">��ü��� ����</td>
							<td class="td_con1">/front/rssinfo.php <span class="font_blue">(��:&lt;a href="/front/rssinfo.php"&gt;RSS&lt;/a&gt;)</span></td>
						</tr>
						<tr><td colspan="4" height="1" bgcolor="#dddddd"></td></tr>

						<tr>
							<td class="table_cell" align="right" style="padding-right:15px;">[NOTICE1]</td>
							<td class="td_con1" colspan="3" style="padding-left:5px;">�⺻ �������� ���</td>
						</tr>
						<tr><td colspan="4" height="1" bgcolor="#dddddd"></td></tr>

						<tr>
							<td class="table_cell" align="right" style="padding-right:15px;">[NOTICE2]</td>
							<td class="td_con1" colspan="3" style="padding-left:5px;">���� ��¥�� ����տ� �ٴ� ���</td>
						</tr>
						<tr><td colspan="4" height="1" bgcolor="#dddddd"></td></tr>

						<tr>
							<td class="table_cell" align="right" style="padding-right:15px;">[NOTICE3]</td>
							<td class="td_con1" colspan="3" style="padding-left:5px;">�պκп� �̹��� ǥ��</td>
						</tr>
						<tr><td colspan="4" height="1" bgcolor="#dddddd"></td></tr>

						<tr>
							<td class="table_cell" align="right" style="padding-right:15px;">[NOTICE4]</td>
							<td class="td_con1" colspan="3" style="padding-left:5px;">�պκп� ���ڳ� ��¥ǥ�� ����</td>
						</tr>
						<tr><td colspan="4" height="1" bgcolor="#dddddd"></td></tr>

						<tr>
							<td class="table_cell" align="right" style="padding-right:15px;">[NOTICE?????_000]</td>
							<td class="td_con1" colspan="3" style="padding-left:5px;">
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
						<tr><td colspan="4" height="1" bgcolor="#dddddd"></td></tr>
						
						<tr>
							<td class=table_cell align=right bgcolor=#FBDED2 style="padding-right:15">[SEARCHFORMSTART]</td>
							<td class="td_con1" style="padding-left:5px;">�˻��� ����</td>
							<td class="table_cell" align="right" style="padding-right:15px;">��ü��� ����</td>
							<td class="td_con1"><span class="font_blue">&lt;form name="search_tform" method="get" action="/front/productsearch.php"&gt; (name�� ����Ұ�)</span></td>
						</tr>
						<tr><td colspan="4" height="1" bgcolor="#dddddd"></td></tr>

						<tr>
							<td class=table_cell align=right bgcolor=#FBDED2 style="padding-right:15">[SEARCHKEYWORD_000]</td>
							<td class="td_con1" style="padding-left:5px;">�˻��� �˻��� �Է� �ؽ�Ʈ�� <FONT class=font_orange>(_000:�ؽ�Ʈ�� ������[�ȼ�����])</font></td>
							<td class="table_cell" align="right" style="padding-right:15px;">��ü��� ����</td>
							<td class="td_con1"><span class="font_blue">&lt;input type="text" name="search" onkeydown="CheckKeyTopSearch()" style="width:200px"&gt; (name�� ����Ұ�)</span></td>
						</tr>
						<tr><td colspan="4" height="1" bgcolor="#dddddd"></td></tr>

						<tr>
							<td class=table_cell align=right bgcolor=#FBDED2 style="padding-right:15">[SEARCHOK]</td>
							<td class="td_con1" style="padding-left:5px;">�˻�Ȯ�� ��ư <FONT class=font_blue>(��:&lt;a href=[SEARCHOK]&gt;�˻�&lt;/a&gt;)</font></td>
							<td class="table_cell" align="right" style="padding-right:15px;">��ü��� ����</td>
							<td class="td_con1">javascript:TopSearchCheck(); <span class="font_blue">(��:&lt;a href="javascript:TopSearchCheck();"&gt;�˻�&lt;/a&gt;)</span></td>
						</tr>
						<tr><td colspan="4" height="1" bgcolor="#dddddd"></td></tr>

						<tr>
							<td class=table_cell align=right bgcolor=#FBDED2 style="padding-right:15">[SEARCHFORMEND]</td>
							<td class="td_con1" style="padding-left:5px;">�˻��� ��</td>
							<td class="table_cell" align="right" style="padding-right:15px;">��ü��� ����</td>
							<td class="td_con1"><span class="font_blue">&lt;/form&gt;</span></td>
						</tr>
						<tr><td colspan="4" height="1" bgcolor="#dddddd"></td></tr>

						<tr>
							<td class="table_cell" align="right" style="padding-right:15px;">[BESTSKEY_000_�α�˻������_�α�˻����ؽ�Ʈ��Ÿ��]</td>
							<td class="td_con1" colspan="3" style="padding-left:5px;">
							�α�˻��� ��� (�α�˻��� ��� ������ �Ǿ��־�� ����)
										<br><img width=10 height=0>
										<FONT class=font_orange>_000 : �α�˻��� ��� �ؽ�Ʈ �� ���� (��: 100) - 100����Ʈ ��� �� "..." ���</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>_�α�˻������ : �α�˻��� ������ (��: "|" �Ǵ� ",") "_"���Ұ�</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>_�α�˻����ؽ�Ʈ��Ÿ�� : �α�˻��� �ؽ�Ʈ ��Ÿ�� (��: color:#FFFFFF;font-size:9px) "_"���Ұ�</FONT>
										<br>
										<FONT class=font_blue>��) [BESTSKEY_100_|_color:#FFFFFF;font-size:9px]</FONT>
							</td>
						</tr>
						<tr><td colspan="4" height="1" bgcolor="#dddddd"></td></tr>
						<tr>
							<td colspan=4 style="padding:10px;">
									<B>[�˻��� ��]</B><br /><br />

									<FONT class=font_blue><B>[SEARCHFORMSTART]</B><br />
									&lt;table border=0 cellpadding=0 cellspacing=0&gt;<br />
									&nbsp;&nbsp;&lt;tr&gt;<br />
									&nbsp;&nbsp;&nbsp;&nbsp;&lt;td&gt;<B>[SEARCHKEYWORD_120]</B>&lt;/td&gt;<br />
									&nbsp;&nbsp;&nbsp;&nbsp;&lt;td&gt;&lt;a href=<B>[SEARCHOK]</B>>�˻�&lt;/a&gt;&lt;/td&gt;<br />
									&nbsp;&nbsp;&lt;/tr&gt;<br />
									&lt;/table&gt;<br />
									<B>[SEARCHFORMEND]</B></FONT>
							</td>
						</tr>
						<tr><td colspan="4" height="1" bgcolor="#dddddd"></td></tr>
						</table>

						</td>
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
	if(document.form1.top_body.value.length==0) {
		alert("������ ������ �Է��ϼ���.");
		document.form1.top_body.focus();
		return;
	}

	f = document.prevForm;
	f.mode.value = 'top';
	f.code.value = document.form1.top_body.value;
	f.submit();
}
</script>

<form name="prevForm" method="post" action="design_prev_post.php" target="_blank">
	<input type="hidden" name="code">
	<input type="hidden" name="mode">
</form>
<?=$onload?>

<? INCLUDE "copyright.php"; ?>