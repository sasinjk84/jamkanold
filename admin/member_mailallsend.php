<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
INCLUDE ("access.php");

########################### TEST ���θ� Ȯ�� ##########################
DemoShopCheck("������������� ������ �Ұ��� �մϴ�.", "history.go(-1)");
#######################################################################

####################### ������ ���ٱ��� check ###############
$PageCode = "me-3";
$MenuCode = "member";
if (!$_usersession->isAllowedTask($PageCode)) {
	INCLUDE ("AccessDeny.inc.php");
	exit;
}
#########################################################

$shopemail=$_shopdata->info_email;
$shopname=$_shopdata->shopname;

$from=$_POST["from"];
$rname=$_POST["rname"];
$group_code=$_POST["group_code"];
$subject=$_POST["subject"];
$body=$_POST["body"];

if (strlen($subject)>0 && strlen($body)>0) {
	$qry = "WHERE (news_yn='Y' OR news_yn='M') ";
	if ($group_code!="ALL") $qry.= "AND group_code = '".$group_code."' ";

	$sql = "SELECT COUNT(*) as cnt FROM tblmember ";
	$sql.= $qry;
	$result = mysql_query($sql,get_db_conn());
	$row = mysql_fetch_object($result);
	$cnt = $row->cnt;
	mysql_free_result($result);

	$sql = "SELECT email, name, date, id FROM tblmember ";
	$sql.= $qry;
	$result = mysql_query($sql,get_db_conn());

	$maildate = date("YmdHis");
	$filename = $maildate.".php";
	if ($cnt>0) $fp=fopen($Dir.DataDir."groupmail/".$filename,"w");

	$count=0;
	while ($row=mysql_fetch_object($result)) {
		if (strpos($row->email,"@")!=false && strpos($row->email,".")!=false && strpos($row->email,"'")==false) {
			fputs($fp,"<?".$row->email.",".$row->name.",".$row->date.",".$row->id."?>\n");
			$count++;
		}
	}
	mysql_free_result($result);
	if ($cnt>0) fclose($fp);

	if ($count==0) {
		echo "<script>alert('������ ���� ȸ���� �����ϴ�.');history.go(-1);</script>";
		exit;
	} else {
		$html="Y";
		$body = ereg_replace("\[NOMAIL\]","<a href=http://".$shopurl."[NOMAIL]>���Űź�</a>",$body);

		$sql = "INSERT tblgroupmail SET ";
		$sql.= "date		= '".$maildate."', ";
		$sql.= "issend		= 'N', ";
		$sql.= "html		= '".$html."', ";
		$sql.= "fromemail	= '".$from."', ";
		$sql.= "shopname	= '".$rname."', ";
		$sql.= "filename	= '".$filename."', ";
		$sql.= "subject		= '".$subject."', ";
		$sql.= "body		= '".$body."' ";
		mysql_query($sql,get_db_conn());

		#�߼� ���μ����� ȣ���ؾ��ұ�??? �ƴϸ� [��ü���� �߼۳��� ����]���� �ϰ� �߼��� �� �ְ� ���ٱ�???

		echo "<script>alert('��ü���� �߼��غ� �Ϸ�Ǿ����ϴ�.\\n\\n��Ʈ��ũ ���ϰ� ���� �����ð��뿡 �߼��Ͻñ� �ٶ��ϴ�.\\n\\n########## [��ü���� �߼۳��� ����]���� �߼� ##########');</script>";
		exit;
	}
}

if (strlen($shopemail)==0) {
	echo "<script>alert(\"[��������]=>[�⺻��������]���� ������ �̸����� �Է��ϼž� �մϴ�.\");parent.topframe.location.href=\"JavaScript:GoMenu(1,'shop_basicinfo.php')\";</script>";
	exit;
}

?>

<? INCLUDE "header.php"; ?>

<script type="text/javascript" src="lib.js.php"></script>
<script language="Javascript1.2" src="htmlarea/editor.js"></script>
<script>
_editor_url = "htmlarea/";

function ChangeEditer(mode,obj){
	if (mode==form1.htmlmode.value) {
		return;
	} else {
		obj.checked=true;
		editor_setmode('body',mode);
	}
	form1.htmlmode.value=mode;
}

function CheckForm() {
	if(document.form1.from.value.length==0) {
		alert("������ ��� �̸����� �Է��ϼ���.");
		document.form1.from.focus();
		return;
	}
	if(document.form1.subject.value.length==0) {
		alert("���� ������ �Է��ϼ���.");
		document.form1.subject.focus();
		return;
	}
	if(document.form1.body.value.length==0) {
		alert("���� ������ �Է��ϼ���.");
		document.form1.body.focus();
		return;
	}
	if (document.form1.sendyn.value=="N") {
		if (confirm("������ �����ðڽ��ϱ�?")) {
			document.form1.body.value='<style>\n'
			+ 'body { background-color: #FFFFFF; font-family: "����"; font-size: x-small; } \n'
			+ '</style>\n'+document.form1.body.value;
			document.form1.sendyn.value="Y";
			document.form1.submit();
		} else return;
	} else {
		alert("�̹� ������ ���°ų� �߼����Դϴ�.");
	}
}

function MailPreview() {
	if (document.form1.body.value.length==0) {
		alert("������ �Է��ϼ���.");return;
	}
	var p = window.open("about:blank","pop","height=550,width=750,scrollbars=yes");
	p.document.write('<title>��ü���� �̸�����</title>');
	p.document.write('<style>\n');
	p.document.write('body { background-color: #FFFFFF; font-family: "����"; font-size: x-small; } \n');
	p.document.write('P {margin-top:2px;margin-bottom:2px;}\n');
	p.document.write('</style>\n');
	p.document.write(document.form1.body.value);
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
			<? include ("menu_member.php"); ?>
			</td>

			<td></td>
			<td valign="top">




<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td height="29" colspan="3">
			<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td height="28" class="link" align="left" background="images/con_link_bg.gif"><img src="images/top_link_house.gif" border="0" valign="absmiddle">������ġ : ȸ������ &gt; ȸ������ �ΰ���� &gt; <span class="2depth_select">��ü���� �߼�</span></td>
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
					<TD><IMG SRC="images/member_mailallsend_title.gif"  ALT=""></TD>
					</tr><tr>
					<TD width="100%" background="images/title_bg.gif" height=21></TD>
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
					<TD width="100%" class="notice_blue">���θ� ��üȸ�� �Ǵ� �׷�ȸ������ ������ �߼��� �� �ֽ��ϴ�.</TD>
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
			<form name=form1 action="<?=$_SERVER[PHP_SELF]?>" method=post enctype="multipart/form-data" target="hiddenframe">
			<input type=hidden name=htmlmode value='wysiwyg'>
			<input type=hidden name=sendyn value="N">
			<tr>
				<td>
				<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
				<col width=139></col>
				<col width=></col>
				<TR>
					<TD colspan=2 background="images/table_top_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">������ ��� �̸���</TD>
					<TD class="td_con1"><input name=from size=50 value="<?=$shopemail?>" onfocus="this.blur();alert('������ ������ [��������]=>[�⺻��������]�� ���θ� ������������ ������ �����մϴ�.');" class="input">&nbsp;<span class="font_orange">���ʼ��Է�</span></TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">������ ��� �̸�</TD>
					<TD class="td_con1"><input name=rname size=50 value="<?=$shopname?>" class="input"></TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">�׷� ����</TD>
					<TD class="td_con1">
						<select name=group_code style="width:273" class="select">
						<option value="ALL">��ü ���� ������
<?
						$sql = "SELECT group_code,group_name FROM tblmembergroup ";
						$result = mysql_query($sql,get_db_conn());
						$count = 0;
						while ($row=mysql_fetch_object($result)) {
							echo "<option value='".$row->group_code."'";
							if ($group_code==$row->group_code) {
								echo " selected";
							}
							echo ">".$row->group_name."</option>";
						}
?>
						</select>
					</TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<tr>
					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">�� ��</TD>
					<TD class="td_con1">
					<table cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td width="290"><input name=subject size=80 class="input"></td>
						<td width="290"><span class="font_orange">���ʼ��Է�</span></td>
					</tr>
					</table>
					</TD>
				</tr>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<tr>
					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">������� ����</TD>
					<TD class="td_con1"><input type=radio name=chk_webedit checked onclick="JavaScript:ChangeEditer('wysiwyg',this)" style="BORDER-RIGHT: medium none; BORDER-TOP: medium none; BORDER-LEFT: medium none; BORDER-BOTTOM: medium none;">��������� �Է��ϱ�(����) <input type=radio name=chk_webedit onclick="JavaScript:ChangeEditer('textedit',this);" style="BORDER-RIGHT: medium none; BORDER-TOP: medium none; BORDER-LEFT: medium none; BORDER-BOTTOM: medium none;">���� HTML�� �Է��ϱ�</TD>
				</tr>
				<TR>
					<TD colspan=2 background="images/table_top_line.gif"></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr>
				<td>
				<table cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td bgcolor="#E0DFE3" style="padding:3"><textarea name=body rows=20 wrap=off style="WIDTH: 100%; HEIGHT: 300px" class="textarea"></TEXTAREA></td>
				</tr>
				</table>
				</td>
			</tr>
			<tr><td height=10></td></tr>
			<tr>
				<td align="center"><a href="javascript:CheckForm();"><img src="images/btn_mailsend.gif" width="124" height="38" border="0"></a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:MailPreview();"><img src="images/btn_view.gif" width="113" height="38" border="0"></a></td>
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
						<td >���Ϲ߼��� �޴� ���ϼ����� ��Ʈ��ũ�� ����, ����Ȯ�� �����ּҿ� ���� �߼��� ���� �Ǵ� ���޵��� ���� �� �ֽ��ϴ�.</td>
					</tr>
					<tr>
						<td width="20" align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td >[NAME], [DATE], [NOMAIL]�� �±״� ���� �߼۽� ��ȯ�Ǿ� �߼۵Ǹ�, �̸����⿡���� �״�� �������ϴ�.</td>
					</tr>
					<tr>
						<td width="20" colspan="2">&nbsp;</td>
					</tr>
					<tr>
						<td width="20" align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td ><b><font color="black">���� ���� ���� �̸� �ִ� ���</font></b></td>
					</tr>
					<tr>
						<td width="20" align="right" valign="top">&nbsp;</td>
						<td >
						<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
						<TR>
							<TD class="table_cell" style="padding-right:15px; border-top-width:1pt; border-top-color:silver; border-top-style:solid;" noWrap align=right width=150 bgColor=#f0f0f0 height="27">�Է¹��</TD>
							<TD class="td_con1" style="padding-left:5px; border-top-width:1pt; border-top-color:silver; border-top-style:solid;" width="100%"><FONT color=#ff4800><B>[NAME]</B></FONT> ���Բ� 2�ְ� �ְ� 20% ���� ���� ���� ��Ư�� Ÿ���� �帳�ϴ�.</TD>
						</TR>
						<TR>
							<TD class="table_cell" style="padding-right:15px; border-top-width:1pt; border-bottom-width:1pt; border-top-color:rgb(222,222,222); border-bottom-color:silver; border-top-style:solid; border-bottom-style:solid;" noWrap align=right width=150 bgColor=#f0f0f0 height="27">�������</TD>
							<TD class="td_con1" style="padding-left:5px; border-top-width:1pt; border-bottom-width:1pt; border-top-color:rgb(222,222,222); border-bottom-color:silver; border-top-style:solid; border-bottom-style:solid;" width="100%"><FONT color=#ff4800><B>ȫ�浿</B></FONT> ���Բ� 2�ְ� �ְ� 20% ���� ���� ���� ��Ư�� Ÿ���� �帳�ϴ�.</TD>
						</TR>
						</TABLE>
						</td>
					</tr>
					<tr>
						<td width="20" colspan="2">&nbsp;</td>
					</tr>
					<tr>
						<td width="20" align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td ><b><font color="black">���� ������ ���� �̸� �ִ� ���</font></b></td>
					</tr>
					<tr>
						<td width="20" align="right" valign="top">&nbsp;</td>
						<td >
						<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
						<TR>
							<TD class="table_cell" style="padding-right:15px; border-top-width:1pt; border-top-color:silver; border-top-style:solid;" noWrap align=right width=150 bgColor=#f0f0f0 height="27">�Է¹��</TD>
							<TD class="td_con1" style="padding-left:5px; border-top-width:1pt; border-top-color:silver; border-top-style:solid;" width="100%"><FONT color=#ff4800><B>[NAME]</B></FONT> ���� �ȳ��ϼ���~ <BR>�̹� ���� ���θ����� ������ǰ ��Ư�� ���� �̺�Ʈ�� �ǽ��մϴ�.</TD>
						</TR>
						<TR>
							<TD class="table_cell" style="padding-right:15px; border-top-width:1pt; border-bottom-width:1pt; border-top-color:rgb(222,222,222); border-bottom-color:silver; border-top-style:solid; border-bottom-style:solid;" noWrap align=right width=150 bgColor=#f0f0f0 height="27">�������</TD>
							<TD class="td_con1" style="padding-left:5px; border-top-width:1pt; border-bottom-width:1pt; border-top-color:rgb(222,222,222); border-bottom-color:silver; border-top-style:solid; border-bottom-style:solid;" width="100%"><FONT color=#ff4800><B>ȫ�浿</B></FONT> ���� �ȳ��ϼ���~ <BR>�̹� ���� ���θ����� ������ǰ ��Ư�� ���� �̺�Ʈ�� �ǽ��մϴ�.</TD>
						</TR>
						</TABLE>
						</td>
					</tr>
					<tr>
						<td width="20" colspan="2">&nbsp;</td>
					</tr>
					<tr>
						<td width="20" align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td >&nbsp;������ �����ô� ��쿡 �� <b><font color="black">���� ����Ȯ�� �� ���Űź� �޼���</font></b>�� �־� �ּ���!</td>
					</tr>
					<tr>
						<td width="20" align="right" valign="top">&nbsp;</td>
						<td >
						<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
						<TR>
							<TD class="table_cell" style="padding-right:15px; border-top-width:1pt; border-top-color:silver; border-top-style:solid;" noWrap align=right width=150 bgColor=#f0f0f0 height="27">�Է¹��</TD>
							<TD class="td_con1" style="padding-left:5px; border-top-width:1pt; border-top-color:silver; border-top-style:solid;" width="100%"><FONT color=#ff4800><B>[NAME]</B></FONT> ���Բ����� <FONT color=#ff4800><B>[DATE]</B></FONT>�� OOO���θ��� ���� �߼ۿ� �����ϼ̽��ϴ�. <BR>���� OOO���θ��� ������ ���̻� �ޱ⸦ ������ ������, <FONT color=#ff4800><B>[NOMAIL]</B></FONT>�� ���ֽñ� �ٶ��ϴ�.</TD>
						</TR>
						<TR>
							<TD class="table_cell" style="padding-right:15px; border-top-width:1pt; border-bottom-width:1pt; border-top-color:rgb(222,222,222); border-bottom-color:silver; border-top-style:solid; border-bottom-style:solid;" noWrap align=right width=150 bgColor=#f0f0f0 height="27">�������</TD>
							<TD class="td_con1" style="padding-left:5px; border-top-width:1pt; border-bottom-width:1pt; border-top-color:rgb(222,222,222); border-bottom-color:silver; border-top-style:solid; border-bottom-style:solid;" width="100%"><FONT color=#ff4800><B>ȫ�浿</B></FONT> ���Բ����� <FONT color=#ff4800><B>2006��04��13�� (08:30)</B></FONT>�� OOO���θ��� ���� �߼ۿ� �����ϼ̽��ϴ�. ���� OOO���θ��� ������ ���̻� �ޱ⸦ ������ ������, <FONT color=#ff4800><B>���Űź�</B></FONT>�� ���ֽñ� �ٶ��ϴ�.</TD>
						</TR>
						</TABLE>
						</td>
					</tr>
					<tr>
						<td width="20" colspan="2">&nbsp;</td>
					</tr>
					<tr>
						<td width="20" align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td ><B><SPAN class=font_orange>��ü���Ϲ߼� �Է���</SPAN></B></td>
					</tr>
					<tr>
						<td width="20" align="right" valign="top">&nbsp;</td>
						<td >
						<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
						<TR>
							<TD class="table_cell" style="padding-right:15px; border-top-width:1pt; border-top-color:silver; border-top-style:solid;" noWrap align=right width=150 bgColor=#f0f0f0 height="27">[NAME]</TD>
							<TD class="td_con1" style="padding-left:5px; border-top-width:1pt; border-top-color:silver; border-top-style:solid;" width="100%">�� �̸��� ��ü�ϴ� �±��Դϴ�. [����� �������뿡 ��� �����մϴ�]</TD>
						</TR>
						<TR>
							<TD class="table_cell" style="padding-right:15px; border-top-width:1pt; border-top-color:rgb(222,222,222); border-top-style:solid;" noWrap align=right width=150 bgColor=#f0f0f0 height="27">[DATE]</TD>
							<TD class="td_con1" style="padding-left:5px; border-top-width:1pt; border-top-color:rgb(222,222,222); border-top-style:solid;" width="100%">ȸ���������� ��ü�ϴ� �±��Դϴ�. [�������뿡�� ��� �����մϴ�]</TD>
						</TR>
						<TR>
							<TD class="table_cell" style="padding-right:15px; border-top-width:1pt; border-bottom-width:1pt; border-top-color:rgb(222,222,222); border-bottom-color:silver; border-top-style:solid; border-bottom-style:solid;" noWrap align=right width=150 bgColor=#f0f0f0 height="27">[NOMAIL]</TD>
							<TD class="td_con1" style="padding-left:5px; border-top-width:1pt; border-bottom-width:1pt; border-top-color:rgb(222,222,222); border-bottom-color:silver; border-top-style:solid; border-bottom-style:solid;" width="100%">���Űź� ��ũ�� ��ü�ϴ� �±��Դϴ�. [�������뿡�� ��� �����մϴ�]</TD>
						</TR>
						</TABLE>
						</td>
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

<script language="javascript">
editor_generate("body");
</script>

<?=$onload?>

<? INCLUDE "copyright.php"; ?>