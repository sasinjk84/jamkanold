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
$intro=$_POST["intro"];

$intropath=$Dir.DataDir."design/intro.htm";

if ($type=="insert" && strlen($intro)>0) {
	########################### TEST ���θ� Ȯ�� ##########################
	DemoShopCheck("������������� �׽�Ʈ�� �Ұ��� �մϴ�.", $_SERVER[PHP_SELF]);
	#######################################################################

	$intro = stripslashes($intro);
	$fp = fopen($intropath,"w");
	fwrite($fp,$intro);
	fclose($fp);
	$onload="<script>alert(\"��Ʈ�� ȭ�� �������� �Ϸ�Ǿ����ϴ�.\");</script>";
} else if($type=="delete" && file_exists($intropath)) {
	unlink($intropath);
	$onload="<script>alert(\"��Ʈ�� ������ ������ �Ϸ�Ǿ����ϴ�.\");</script>";
}


$intro="";
if(file_exists($intropath)==true){
	$fp = fopen($intropath,"r");
	if($fp) {
		while (!feof($fp)) {$intro.= fgets($fp, 1024);}
	}
	fclose($fp);
}

?>

<? INCLUDE "header.php"; ?>

<script type="text/javascript" src="lib.js.php"></script>
<script language="JavaScript">
function CheckForm(type) {
	if(type=="insert") {
		if(document.form1.intro.value.length==0) {
			alert("��Ʈ�� ������ ������ �Է��ϼ���.");
			document.form1.intro.focus();
			return;
		}
		document.form1.type.value=type;
		document.form1.submit();
	} else if(type=="delete") {
		if(confirm("��Ʈ�� �������� �����Ͻðڽ��ϱ�?")) {
			document.form1.type.value=type;
			document.form1.submit();
		}
	} else if(type=="store") {
		if(confirm("��Ʈ�� ������ �������� ����Ͻðڽ��ϱ�? ���� ����� ����ҽ��� ��ü�մϴ�.")) {
			document.form1.type.value=type;
			document.form1.submit();
		}
	} else if(type=="restore") {
		if(confirm("��Ʈ�� ������ �������� ������� �Ͻðڽ��ϱ�? ���� ����� ����ҽ��� ��ü�մϴ�.")) {
			document.form1.type.value=type;
			document.form1.submit();
		}
	}
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
				<td height="28" class="link" align="left" background="images/con_link_bg.gif"><img src="images/top_link_house.gif" border="0" valign="absmiddle">������ġ : �����ΰ��� &gt; ����������-���� �� ���ϴ�  &gt; <span class="2depth_select">��Ʈ�� ȭ�� �ٹ̱�</span></td>
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
					<TD><IMG SRC="images/design_eachintropage_title.gif" ALT=""></TD>
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
					<TD width="100%" class="notice_blue">��Ʈ�� �������� �����Ͻ� �� �ֽ��ϴ�.</TD>
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
					<TD><IMG SRC="images/design_eachintropage_stitle.gif" WIDTH="181" HEIGHT=31 ALT=""></TD>
					<TD width="100%" background="images/shop_basicinfo_stitle_bg.gif"></TD>
					<TD><IMG SRC="images/shop_basicinfo_stitle_end.gif" WIDTH=10 HEIGHT=31 ALT=""></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr><td height=3></td></tr>
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
					<TD class="notice_blue" valign="top"><IMG SRC="images/distribute_img.gif" ></TD>
					<TD width="100%" class="notice_blue">1) ��Ʈ�� ȭ�� : Ȩ������ �Ǵ� ���θ� ���� ���� ������ �Ұ������� �Դϴ�.<BR>
					&nbsp;&nbsp;&nbsp;&nbsp;�÷��ó� Ȩ������ Ÿ���� �������� �������� �� �� �ֽ��ϴ�.<br>
					2) ��Ʈ�� �������� �����ϸ� ��ٷ� ���θ� �տ� �Է��� ������ ������ ��µ˴ϴ�.<BR>
					&nbsp;&nbsp;&nbsp;&nbsp;�Է¶��� �����̽��ٷ� ����ó���� �ϰ� <b>[�����ϱ�]</b>�� �ϸ� ������ ���� ��ó�� ���̳� ��Ʈ�ο� �� ȭ������ ó���˴ϴ�.<br>
					&nbsp;&nbsp;&nbsp;&nbsp;�� ��� �ݵ�� <b>[�����ϱ�]</b> ��  Ŭ�����ּ���.<br>
					3) <b>[�����ϱ�]</b>�� �����γ����� ���� �������� ������ �ʿ��� ��� ������ �ҽ��� �����Ͽ� ���� �����Ͻñ� �ٶ��ϴ�.<br>
					4) ��Ʈ�� ȭ�� �ٹ̱�� ���μ��θ� ���� ������� �ʽ��ϴ�.</TD>
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
				<td><textarea name=intro style="WIDTH: 100%; HEIGHT: 300px" class="textarea"><?=htmlspecialchars($intro)?></TEXTAREA></td>
			</tr>
			<tr><td height=10></td></tr>
			<tr>
				<td align="center"><a href="javascript:CheckForm('insert');"><img src="images/botteon_save.gif" width="113" height="38" border="0"></a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:CheckForm('delete');"><img src="images/botteon_del.gif" width="113" height="38" border="0" hspace="2"></a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:prevPage();"><img src="images/botteon_prev.gif" width="113" height="38" border="0" hspace="2"></a></td>
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
						<td width="100%"><span class="font_dotline">��Ʈ�� ����������  ���θ� ������������ ��ũ ��� : ���θ� �ּ�/index.php</span></td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td width="100%" class="space_top" style="letter-spacing:-0.5pt;"><b>- ��ũ�±� : </b><font color="#FF6000">&lt;a href="http://<?=$_ShopInfo->getShopurl()?>index.php"&gt;���θ� ����</a></font></td>
					</tr>
					<tr>
						<td height="10" colspan="2"></td>
					</tr>
					<tr>
						<td width="20" align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td width="100%"><span class="font_dotline">���θ����� �����Ǵ� �÷������ �ڹٽ�ũ��Ʈ ����� �� ��� �Ʒ��� �±׸� �߰� �� ����� �ּ���.</span></td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td width="100%" class="space_top" style="letter-spacing:-0.5pt;"><b>- �߰��±� : </b><font color="#FF6000">&lt;script type="text/javascript" src="./lib/lib.js.php"&gt;&lt;/script&gt;</font></td>
					</tr>
					<tr>
						<td height="10" colspan="2"></td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td width="796" class="space_top">
						&nbsp;&nbsp;&nbsp;<b>���÷��� ���� ��� ���</b><br><span class="font_blue">
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;script&gt;<br>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;flash_show("�÷������ϰ��","����ũ��","����ũ��");<br>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;/script&gt;</span>
						</td>
					</tr>
					<tr>
						<td height="10" colspan="2"></td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td width="796" class="space_top">
						&nbsp;&nbsp;&nbsp;<b>���÷��� �� ��� ���(�Ķ���� �߰�)</b><br><span class="font_blue">
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;script&gt;<br>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;embedobj=new embedcls();<br>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;embedobj.init("�÷������ϰ��","����ũ��","����ũ��");<br>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;embedobj.setparam("�Ķ���͸�","�Ķ���Ͱ�");<br>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;embedobj.setparam("�Ķ���͸�","�Ķ���Ͱ�");<br>
						<span style="line-height:5px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;.<br>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;.<br>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;.<br>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;.<br>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;.<br></span>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;embedobj.show();<br>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;/script&gt;</span>
						</td>
					</tr>
					<tr>
						<td height="10" colspan="2"></td>
					</tr>
					<tr>
						<td width="20" align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td width="701">��FTP�� intro.htm �̶�� ������ �����Ͽ� /data/design/ ������ ���ε� �ص� ��Ʈ�� �������� �۵��˴ϴ�.</td>
					</tr>
					<tr>
						<td height="10" colspan="2"></td>
					</tr>
					<tr>
						<td width="20" align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td width="701">��Ʈ�� �������� ����� �̹����� ��FTP�� ���ε��Ͽ� ����Ͻø� �˴ϴ�.</td>
					</tr>
					<tr>
						<td height="10" colspan="2"></td>
					</tr>
					<tr>
						<td width="20" align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td width="701">����,�帲�������� �����ͷ� �ۼ��� �̹�����ε� �۾������� Ʋ���� �� ������ �����ϼ���!</td>
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
	if(document.form1.intro.value.length==0) {
		alert("��Ʈ�� ������ ������ �Է��ϼ���.");
		document.form1.intro.focus();
		return;
	}

	f = document.prevForm;
	f.mode.value = 'intro';
	f.code.value = document.form1.intro.value;
	f.submit();
}
</script>

<form name="prevForm" method="post" action="design_prev_post.php" target="_blank">
	<input type="hidden" name="code">
	<input type="hidden" name="mode">
</form>
<?=$onload?>

<? INCLUDE "copyright.php"; ?>