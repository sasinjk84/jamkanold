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
$rmail=$_POST["rmail"];

$shopurl="http://".$_SERVER["HTTP_HOST"];
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
var sendok=0;
function CheckForm() {
	if(document.form1.to.value.length==0) {
		alert("�޴� ��� �̸����� �Է��ϼ���.");
		document.form1.to.focus();
		return;
	}
	if(!IsMailCheck(document.form1.to.value)) {
		alert("�޴� ��� �̸����� �߸��Ǿ����ϴ�.");
		document.form1.to.focus();
		return;
	}
	if(document.form1.from.value.length==0) {
		alert("������ ��� �̸����� �Է��ϼ���.");
		document.form1.from.focus();
		return;
	}
	if(!IsMailCheck(document.form1.from.value)) {
		alert("������ ��� �̸����� �߸��Ǿ����ϴ�.");
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
	sendok++;
	if (sendok>3) { alert('3���̻� ���ӹ߼��� �ȵ˴ϴ�.');return; }
	if(document.form1.style.value=="N"){
		document.form1.body.value='<style>\n'
		+ 'body { background-color: #FFFFFF; font-family: "����"; font-size: x-small; } \n'
		+ '</style>\n'+document.form1.body.value;
	}
	document.form1.style.value="Y";
	document.form1.submit();
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
				<td height="28" class="link" align="left" background="images/con_link_bg.gif"><img src="images/top_link_house.gif" border="0" valign="absmiddle">������ġ : ȸ������ &gt; ȸ������ �ΰ���� &gt; <span class="2depth_select">�������� �߼�</span></td>
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
					<TD><IMG SRC="images/member_mailsend_title.gif"ALT=""></TD>
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
					<TD width="100%" class="notice_blue">���θ� ȸ���� Ư��ȸ�� �Ѹ��� ������ �߼��� �� �ֽ��ϴ�.</TD>
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
			<form name=form1 action="sendmail_process.php" method=post enctype="multipart/form-data" target="hiddenframe">
			<input type=hidden name=type>
			<input type=hidden name=htmlmode value='wysiwyg'>
			<input type=hidden name=style value="N">
			<input type="hidden" name="shopname" value="<?=$shopname?>">
			<input type="hidden" name="shopurl" value="<?=$shopurl?>">
			<tr>
				<td>
				<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
				<col width=139></col>
				<col width=></col>
				<TR>
					<TD colspan=2 background="images/table_top_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">�޴� ��� �̸���</TD>
					<TD class="td_con1"><input name=to size=50 value="<?=$rmail?>" class="input">&nbsp;<span class="font_orange">���ʼ��Է�</span></TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">������ ��� �̸���</TD>
					<TD class="td_con1"><input name=from size=50 value="<?=$shopemail?>" class="input">&nbsp;<span class="font_orange">���ʼ��Է�</span></TD>
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
				<tr>
					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">�� ��</TD>
					<TD class="td_con1"><input name=subject size=80 class="input">&nbsp;<span class="font_orange">���ʼ��Է�</span></TD>
				</tr>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<tr>
					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">÷������</TD>
					<TD class="td_con1"><input type=file name=upfile style="WIDTH: 423px" class="input"></TD>
				</tr>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<tr>
					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">������� ����</TD>
					<TD class="td_con1"><input type=radio name=chk_webedit checked onclick="JavaScript:ChangeEditer('wysiwyg',this)" style="BORDER-RIGHT: medium none; BORDER-TOP: medium none; BORDER-LEFT: medium none; BORDER-BOTTOM: medium none;">��������� �Է��ϱ�(����) <input type=radio name=chk_webedit onclick="JavaScript:ChangeEditer('textedit',this);" style="BORDER-RIGHT: medium none; BORDER-TOP: medium none; BORDER-LEFT: medium none; BORDER-BOTTOM: medium none;">���� HTML�� �Է��ϱ�</TD>
				</tr>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
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
				<td align="center"><a href="javascript:CheckForm();"><img src="images/btn_mailsend.gif" width="124" height="38" border="0"></a></td>
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
						<td><p><span class="font_dotline">���Ϲ߼۽� ���ǻ���</span></p></td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td class="space_top"><p>- ���Ϲ߼��� �޴� ���ϼ����� ��Ʈ��ũ�� ����, ����Ȯ�� �����ּҿ� ���� �߼��� ���� �Ǵ� ���޵��� ���� �� �ֽ��ϴ�.</p></td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td class="space_top"><p>- ȸ�����Խ� ���ϼ��ſ��θ� �������� ���� ȸ���� ���޵��� �����Ƿ� �����߼��� Ȯ���� �ּ���.</p></td>
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