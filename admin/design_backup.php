<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");

INCLUDE ("access.php");
//backup�� �ʿ��� ��� include
include ("../lib/backup.php");				
include ("../lib/backup_config.php");
include ("../lib/lib.new.php");		//dbŬ�����߰� by.jyh
$backup = new backup();
####################### ������ ���ٱ��� check ###############
$PageCode = "de-8";
$MenuCode = "design";
$snavi3 = "������ ����ϱ�";  

/*
error_reporting(E_ALL);
ini_set("display_errors", 1);
*/
if (!$_usersession->isAllowedTask($PageCode)) {
	INCLUDE ("AccessDeny.inc.php");
	exit;
}
#########################################################

if($_POST['type']=='insert') {
	if($backup->getMaxchk()) {	//�ִ� �������üũ
		$dbl_subject	= getInjection($_POST['dbl_subject']);
		$skindir_check	= $_POST['skindir_check'];

		$arr_dir_size = $backup->getdir_list('../data/design/');	//�������������丮

		if($skindir_check=='on') {
			//$backup->setAddDir('../data/design/skin/'.$_shopdata->icon_use_type);	//����� ��Ų������ ���� �մϴ�. 2013-02-12 by.jyh
			//$arr_dir_size = array_merge($arr_dir_size,array('../data/design/skin/'.$_shopdata->icon_use_type));
		}

		//��������� ���������� ����.10M�� ���� by.jyh
		foreach($arr_dir_size as $v) {
			$backup->setdir_size($v);
		}
		if($backup->getdir_size()>10485760) {
			$onload="<script>alert(\"��� �뷮�� 10M�� �ʰ��� �� �����ϴ�.( �������뷮 : ".intval((int)$backup->getdir_size()/1048576)."M)\");</script>";
		} else {
			if($backup->design_backup(array('dbl_type'=>'backup','dbl_subject'=>$dbl_subject))) $onload="<script>alert(\"����� �Ϸ�Ǿ����ϴ�.\");</script>";
			else $onload="<script>alert(\"��� �� ������ �߻� �߽��ϴ�.\");</script>";
		}

	} else {
		$onload = "<script>alert(\"������ ����� �ִ� 10������ �����ϸ� �߰� ����� ���� ��������͸� ���� �� ������ �ּ���.\");</script>";
	}
}
?>

<?INCLUDE ("header.php"); ?>
<script type="text/javascript" src="lib.js.php"></script> 
<script language="JavaScript">
var isSubmitted = false;
function CheckForm(type) {
	if(type=="insert") {
		if(document.form1.dbl_subject.value.length==0) {
			alert("����̸��� �Է��ϼ���.");
			document.form1.dbl_subject.focus();
			return ;
		}

		if(isSubmitted == false) {
			isSubmitted = true;
			document.form1.type.value=type;
			document.form1.submit();
		} else {
			alert("�����͸� �������Դϴ�. Ȯ�θ޼����� ���� �� ���� ��ٸ�����");
			return ;
			//�� �κ��� ��쿡 ���� return false �߰�.
		}
		
	} else {
		alert("�߸��� ���� �Դϴ�.");
		return ;
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
				<td height="28" class="link" align="left" background="images/con_link_bg.gif"><img src="images/top_link_house.gif" border="0" valign="absmiddle">������ġ : �����ΰ��� &gt; ������ ��� ����  &gt; <span class="2depth_select">����ϱ�</span></td>
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
					<TD><IMG SRC="images/design_backup_title.gif" ALT="������ ����ϱ�"></TD>
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
					<TD width="100%" class="notice_blue">���������ο��� �۾��� ������ ����Ͻ� �� �ֽ��ϴ�.</TD>
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
					<TD><IMG SRC="images/design_backup_stitle.gif" ALT="����ϱ�"></TD>
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
					<TD width="100%" class="notice_blue">
						1) ���� ������ �ҽ� ����� �̷�� ���ϴ�.<BR>
						2) ������ ����� ���������� ����ڰ� FTP�� ���� �ø� ���ϰ� ���������� �ҽ��� ��� �˴ϴ�.<BR>
						3) ������ ����� �뷮�� ���� 10M�� �ʰ� �� �� �����ϴ�.<br>
						4) ������ ����� �ִ� 10������ ����� �����ϸ�, 10�� ��� ���¿��� �߰� ��� �� ���� ��� �����͸� ���� �Ͻ� �� ��� ������ �����մϴ�.</TD>
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
			<form name=form1 method=post onsubmit="return false;">
			<input type=hidden name=type>
			<tr>
				<td valign="top" align="center" style="padding-top:20px;">
					<table cellSpacing=0 cellPadding=0 width="100%" border="0" style="bottom-background">
						<!--tr>
							<td colspan="3" height="30" ><input type="checkbox" name="skindir_check" id="skindir_check" checked="checked"><label for="skindir_check">��Ų����(/data/design/skin/<?=$_shopdata->icon_use_type?>)�� ������� <strong style="color:ff0000">����</strong> �ϽǷ��� <strong>üũ����</strong> ���ּ���.</label></td>
						</tr-->
						<tr>
							<td colspan="3" background="images/table_top_line.gif"></td>
						</tr>
						<tr>
							<td class="table_cell" width="140"><img src="images/icon_point2.gif" border="0">����̸�</td>
							<td class="td_con1"><input type="text" name="dbl_subject" id="dbl_subject"  class="input" style="width:100%" onKeyDown="if(event.keyCode == 13) CheckForm('insert');" maxlength="20" /></td>
							<td width="10%"><a href="javascript:CheckForm('insert');"><img src="images/btn_backup.gif" alt="����ϱ�"></a></td>
						</tr>
						<tr>
							<td colspan="3" background="images/table_top_line.gif"></td>
						</tr>
					</table>
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
						<td width="100%"><span class="font_dotline">������� ���� ��� : data/design_backup/</span></td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td width="100%" class="space_top" style="letter-spacing:-0.5pt;">- ������Ͽ� ���� �����ð��� ������ ���ϸ��� �����Ǿ� ����˴ϴ�.</td>
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
<? include ("copyright.php"); ?>
