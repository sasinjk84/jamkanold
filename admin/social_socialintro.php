<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
INCLUDE ("access.php");

####################### ������ ���ٱ��� check ###############
$PageCode = "go-1";
$MenuCode = "gong";
if (!$_usersession->isAllowedTask($PageCode)) {
	INCLUDE ("AccessDeny.inc.php");
	exit;
}
#########################################################

$type=$_POST["type"];
$up_social_intro=$_POST["up_social_intro"];
$up_image=$_FILES["up_image"];
$up_flash=$_FILES["up_flash"];

$imagepath = $Dir.DataDir."shopimages/etc/";

if ($type=="up") {
	if ($up_image[name]) {
		if (strtolower(substr($up_image[name],strlen($up_image[name])-3,3))!="gif") {
			$onload = "<script>alert (\"�ø��� �̹����� gif���ϸ� �����մϴ�.\");</script>";
		} else if ($up_image[size]>153600) {
			$onload = "<script>alert (\"�ø��� �̹��� �뷮�� 150KB ������ ���ϸ� �����մϴ�.\");</script>";
		} else {
			$image_name="main_logo.gif";
			move_uploaded_file($up_image[tmp_name],"$imagepath$image_name");
			chmod("$imagepath$image_name",0606);
		}
	}

	if ($up_flash[name]) {
		if (strtolower(substr($up_flash[name],strlen($up_flash[name])-3,3))!="swf") {
			$onload = "<script>alert (\"�ø��� �÷��� ������ swf���ϸ� �����մϴ�.\");</script>";
		} else if ($up_flash[size]>20480000) {
			$onload = "<script>alert (\"�ø��� �÷��� ������ �뷮�� 2MB ������ swf���ϸ� �����մϴ�.\");</script>";
		} else {
			$flash_name="social_logo.swf";
			move_uploaded_file($up_flash[tmp_name],"$imagepath$flash_name");
			chmod("$imagepath$flash_name",0606);
		}
	}
} else if ($type=="img" && file_exists($imagepath."main_logo.gif")) {
	$img_url=$imagepath."social_logo.gif";
	unlink("$img_url");
	$onload = "<script>alert (\"�̹����� �����Ǿ����ϴ�.\");</script>";
}else if ($type=="flash" && file_exists($imagepath."main_logo.swf")) {
	$img_url=$imagepath."social_logo.swf";
	unlink("$img_url");
	$onload = "<script>alert (\"�÷��� ������ �����Ǿ����ϴ�.\");</script>";
}

if ($type=="up") {
	if (strlen($up_social_intro) <= 10000) {
		$sql = "SELECT social_intro FROM tblshopinfo ";
		$result = mysql_query($sql,get_db_conn());
		$flag = (boolean)mysql_num_rows($result);
		mysql_free_result($result);
		$sql = "UPDATE tblshopinfo SET ";
		$sql.= "social_intro	= '".$up_social_intro."' ";
		$update = mysql_query($sql,get_db_conn());
		DeleteCache("tblshopinfo.cache");

		if (!$onload) {
			$onload = "<script> alert('���� ������ �Ϸ�Ǿ����ϴ�.'); </script>";
		}
	}
}

$sql = "SELECT social_intro FROM tblshopinfo ";
$result = mysql_query($sql,get_db_conn());
if ($row=mysql_fetch_object($result)) {
	$social_intro = $row->social_intro;
}
mysql_free_result($result);

?>

<? INCLUDE "header.php"; ?>

<script type="text/javascript" src="lib.js.php"></script>
<script>
function CheckForm(gbn) {
	var form = document.form1;
	if(CheckLength(form.up_social_intro)>10000){
		alert('���� �Ұ����� �ѱ�5000��, ����10000�� ���� �Է� �����մϴ�.\n\n�ٽ� Ȯ���Ͻñ� �ٶ��ϴ�.');
		form.up_social_intro.focus();
		return;
	}
	form.type.value=gbn;
	form.submit();
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
			<? include ("menu_gong.php"); ?>
			</td>

			<td></td>
			<td valign="top">




<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td height="29" colspan="3">
			<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td height="28" class="link" align="left" background="images/con_link_bg.gif"><img src="images/top_link_house.gif" border="0" valign="absmiddle">������ġ : �������� &gt; ���� �⺻���� ���� &gt; <span class="2depth_select">���� Ÿ��Ʋ �̹��� ������</span></td>
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








			<table width="100%" cellpadding="0" cellspacing="0">
			<tr><td height="8"></td></tr>
			<tr>
				<td>
				<table width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td><img src="images/shop_mainintro_title.gif" border="0"></td>
					</tr><tr>
					<td width="100%" background="images/title_bg.gif" height=21></td>
				</tr>
				</table>
				</td>
			</tr>
			<tr><td height="20"></td></tr>
			<tr>
				<td>
				<table width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td><img src="images/shop_mainintro_stitle1.gif" border="0"></td>
					<td width="100%" background="images/shop_basicinfo_stitle_bg.gif"></td>
					<td><img src="images/shop_basicinfo_stitle_end.gif" border="0"></td>
				</tr>
				</table>
				</td>
			</tr>
			<tr><td height="3"></td></tr>
			<tr>
				<td>
				<table width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td><img src="images/distribute_01.gif" border="0"></td>
					<td COLSPAN="2" background="images/distribute_02.gif"></td>
					<td><img src="images/distribute_03.gif" border="0"></td>
				</tr>
				<tr>
					<td background="images/distribute_04.gif"></td>
					<td class="notice_blue"><img src="images/distribute_img.gif" border="0"></td>
					<td width="100%" class="notice_blue">1) ���θ� ���� �߾��� Ÿ��Ʋ �̹��� ������ ������ �����մϴ�.<br>
					2) �̹����� <b>�ѱ�, Ư������, ���� ������</b></span>.<br>
					3) �����̹����� �������� �ʴ��� ��ü�Ǹ鼭 �ڵ������˴ϴ�.
					</td>
					<td background="images/distribute_07.gif"></td>
				</tr>
				<tr>
					<td><img src="images/distribute_08.gif" border="0"></td>
					<td COLSPAN="2" background="images/distribute_09.gif"></td>
					<td><img src="images/distribute_10.gif" border="0"></td>
				</tr>
				</table>
				</td>
			</tr>
			<tr><td height="3"></td></tr>
			<form name=form1 action="<?=$_SERVER[PHP_SELF]?>" method=post enctype="multipart/form-data">
			<input type=hidden name=type>
			<tr>
				<td>
				<table width="100%" cellpadding="0" cellspacing="0">
				<col width="140"></col>
				<col width=""></col>
				<tr>
					<td height="1" colspan="2" bgcolor="#B9B9B9"></td>
				</tr>
				<tr>
					<td class="table_cell"><img src="images/icon_point2.gif" border="0">�̹��� ���</td>
					<td class="td_con1"><input type=file name="up_image" class="input" <? if (file_exists($imagepath."main_logo.gif")) { ?> style="width:82%"> <input type=button value="�̹�������" onClick="CheckForm('img');" class="submit1"><?}else{?> style="width:500px"><?}?><br>
					* ��� ������ �̹����� ���� Ȯ���� <span class="font_orange2">GIF(gif)</span> �� �����ϸ� �뷮�� <span class="font_orange2">�ִ� 150KB</span> ���� �����մϴ�.<br>
					* �̹��� ��� �� ������ �Է¶��� <span class="font_orange2">��ũ�θ�ɾ� [MAINIMG]</span>�� �Է��ϸ� ȭ�鿡 ��µ˴ϴ�.</td>
				</tr>
				<tr>
					<td height="1" colspan="2" bgcolor="#EDEDED"></td>
				</tr>
				<? if (file_exists($imagepath."social_logo.gif")) { ?>
				<?
					$width = getimagesize($imagepath."social_logo.gif");
					$imgwidth = $width[0];
					if ($imgwidth>=505) $imgwidth = 505;
				?>
				<tr>
					<td class="table_cell"><img src="images/icon_point2.gif" border="0">��ϵ� �̹��� ����</td>
					<td class="td_con1"><img src="<?=$imagepath?>social_logo.gif" border="0" align="absmiddle" width="<?=$imgwidth?>"></td>
				</tr>
				<tr>
					<td height="1" colspan="2" bgcolor="#EDEDED"></td>
				</tr>
				<? } ?>
				<tr>
					<td class="table_cell"><img src="images/icon_point2.gif" border="0">�÷��� ���</td>
					<td class="td_con1"><input type=file name="up_flash" class="input" <? if (file_exists($imagepath."social_logo.swf")) { ?> style="width:82%"> <input type=button value="�̹�������" onClick="CheckForm('flash');" class="submit1"><?}else{?> style="width:500px"><?}?><br>
					* ��� ������ �÷��ô� ���� Ȯ���� <span class="font_orange2">SWF(swf)</span> �� �����ϸ� �뷮�� <span class="font_orange2">�ִ� 2MB</span> ���� �����մϴ�.<br>* �÷��� ��� �� ������ �Է¶��� <span class="font_orange2">��ũ�θ�ɾ� [MAINFLASH_����X����]</span>�� �Է��ϸ� ȭ�鿡 ��µ˴ϴ�.</td>
				</tr>
				<tr>
					<td height="1" colspan="2" bgcolor="#EDEDED"></td>
				</tr>
				<? if (file_exists($imagepath."main_logo.swf")) { ?>
				<tr>
					<td class="table_cell"><img src="images/icon_point2.gif" border="0">��ϵ� �÷��� ����</td>
					<td class="td_con1"><OBJECT classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,40,0" WIDTH="505" HEIGHT="150" id=myMovieName>
					<PARAM NAME=movie VALUE="<?=$imagepath?>social_logo.swf">
					<PARAM NAME=quality VALUE="high">
					<PARAM NAME=bgcolor VALUE="#FFFFFF">

					<EMBED src="<?=$imagepath?>main_logo.swf" quality="high" bgcolor="#FFFFFF" WIDTH="505" HEIGHT="150" NAME="myMovieName" TYPE="application/x-shockwave-flash" PLUGINSPAGE="http://www.macromedia.com/go/getflashplayer"></EMBED>
					</OBJECT></td>
				</tr>
				<tr>
					<td height="1" colspan="2" bgcolor="#EDEDED"></td>
				</tr>
				<? } ?>
				<tr>
					<td class="table_cell"><img src="images/icon_point2.gif" border="0"><span class="font_orange">������ �Է�</span></td>
					<td class="td_con1">* ����� �̹����� <span class="font_orange2">��ũ�θ�ɾ</span> �Ǵ� <span class="font_orange2">��ũ�θ�ɾ�+������</span>, <span class="font_orange2">��ũ�θ�ɾ� ���� ������ ���븸</span>�� �Է� ����.<br>
					* ��üHTML, �κ�HTML ,TEXT ��� �����˴ϴ�.</td>
				</tr>
				<tr>
					<td height="1" colspan="2" bgcolor="#EDEDED"></td>
				</tr>
				<tr>
					<td  colspan="2" class="space"><textarea name="up_social_intro" rows="10" cols="86" wrap="off" style="width:100%" onKeyDown="chkFieldMaxLen(10000)" class="textarea"><?=$social_intro?></textarea></td>
				</tr>
				<tr>
					<td height="1" colspan="2" bgcolor="#B9B9B9"></td>
				</tr>
				</table>
				</td>
			</tr>
			<tr><td height="10"></td></tr>
			<tr>
				<td>
				<table width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td><img src="images/distribute_01.gif" border="0"></td>
					<td width="100%" background="images/distribute_02.gif"></td>
					<td><img src="images/distribute_03.gif" border="0"></td>
				</tr>
				<tr>
					<td background="images/distribute_04.gif"></td>
					<td class="notice_blue" valign="top">
					<table width="100%" cellpadding="0" cellspacing="0">
					<tr>
						<td style="padding-left:10px;padding-left:10px;">
							<table width="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td class="notice_blue">1) ����� �̹����� ������ �ϱ� : ������ �Է¶���  <b>��ũ�θ�ɾ�</b>�� ����.</td>
							</tr>
							<tr>
								<td class="notice_blue">2) ����� �̹���+ �߰� ������ : <b>��ũ�θ�ɾ�</b> + �ؽ�Ʈ �Ǵ� ���� ������ ���븸 ����.</td>
							</tr>
							<tr>
								<td class="notice_blue">3) ����� �̹����� ������� ���� ������  : <b>��ũ�θ�ɾ�</b>�� ���� �ʰ� ���� ������ ���븸 ����.</td>
							</tr>
							<tr>
								<td class="notice_blue">4) ������ �Է¶��� ������ ��� :  "���� ���θ� url�� �湮���ּż� �����մϴ�."�� ǥ�� �˴ϴ�.</td>
							</tr>
							<tr>
								<td class="notice_blue">5) �ְ� �ѱ� 5,000�ڱ��� �Է�  �����մϴ�.</td>
							</tr>
							<tr>
								<td class="notice_blue">6) ��üHTML, �κ�HTML ,TEXT ��� �����˴ϴ�.</td>
							</tr>
							</table>
						</td>
						<td  valign="top" align=right><img src="images/table_1.gif" border="0"></td>
					</tr>
					</table>
					</td>
					<td background="images/distribute_07.gif"></td>
				</tr>
				<tr>
					<td><img src="images/distribute_08.gif" border="0"></td>
					<td background="images/distribute_09.gif"></td>
					<td><img src="images/distribute_10.gif" border="0"></td>
				</tr>
				</table>
				</td>
			</tr>
			<tr><td height="10"></td></tr>
			<tr>
				<td align="center"><a href="javascript:CheckForm('up');"><img src="images/botteon_save.gif" border="0"></a></td>
			</tr>
			</form>
			<tr><td height="20"></td></tr>
			<tr>
				<td>
				<table width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td><img src="images/manual_top1.gif" border="0"></td>
					<td width="100%" background="images/manual_bg.gif"><img src="images/manual_title.gif" border="0"></td>
					<td><img src="images/manual_top2.gif" border="0"></td>
				</tr>
				<tr>
					<td background="images/manual_left1.gif"></td>
					<td style="padding-top:5px;"  class="menual_bg">
					<table width="100%" cellpadding="0" cellspacing="0">
					<tr>
						<td><img src="images/icon_8.gif" border="0" align="absmiddle"><span class="font_orange2"><b>���� Ÿ��Ʋ ��ũ�θ�ɾ�</b></span>(�ش� ��ũ�θ�ɾ�� �ٸ� ������ ������ �۾��� ����� �Ұ�����)</td>
					</tr>
					<tr>
						<td style="padding-left:13px;" class="space_top">
						<table width="100%" cellpadding="0" cellspacing="0">
						<col width="150" height="28" align="right"></col>
						<col></col>
						<tr>
							<td height="1" colspan="2" bgcolor="#C0C0C0"></td>
						</tr>
						<tr>
							<td class="table_cell">[MAINIMG]</td>
							<td class="td_con1">�Ϲ� �̹���</td>
						</tr>
						<tr>
							<td height="1" colspan="2" bgcolor="#E3E3E3"></td>
						</tr>
						<tr>
							<td class="table_cell">[MAINFLASH_300X500]</td>
							<td class="td_con1">�÷��� ����</td>
						</tr>
						<tr>
							<td height="1" colspan="2" bgcolor="#C0C0C0"></td>
						</tr>
						</table>
						</td>
					</tr>
					<tr><td height="20"></td></tr>
					<tr>
						<td><img src="images/icon_8.gif" border="0" align="absmiddle"><span class="font_dotline">������ ���� �Է� �ȳ�</span></td>
					</tr>
					<tr>
						<td style="padding-left:13px;" class="space_top">��ϵ� �̹����� �÷��ÿܿ� ������ �̹����� �̿��Ͻ� ���� ���� �̹��� �ҽ��� �Է��ϸ� �˴ϴ�.<br>
						�����̹��� ��� : <span class="font_blue"><a href="javascript:parent.topframe.GoMenu(2,'design_webftp.php');">�����ΰ��� > ��FTP, ������ �ɼ� ���� > ��FTP/��FTP�˾�</a></span><br>
						html�±� ���� Text�� �Է��� ��� ��� ������ �⺻�Դϴ�.<br>
						Text&nbsp;/ �κ� html�� ��� �� ��� �ٹٲ�(br)�� ����(Enter]Ű�� �̿��Ͻø� �˴ϴ�.<br>
						�κ� html��)<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;img src=000.jpg&gt; �� (Enter)<br>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[MAINIMG] �� (Enter)<br>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;���θ��� �湮���ּż� �����մϴ�.</td>
					</tr>
					<tr><td height="20"></td></tr>
					<tr>
						<td><img src="images/icon_8.gif" border="0" align="absmiddle"><span class="font_dotline">���� ���� ������ ����� ���</span></td>
					</tr>
					<tr>
						<td style="padding-left:13px;" class="space_top"><span class="font_blue"><a href="javascript:parent.topframe.GoMenu(2,'design_eachmain.php');">�����ΰ��� > ���������� - ���� �� ���ϴ� > ���κ��� �ٹ̱�</a></span> ����<br>
						����Ÿ��Ʋ �̹��� ��ũ�� ��ɾ� <span class="font_orange2">[SHOPINTRO]</span>�� ������� ���� ��� �� �������� ��µ��� �ʽ��ϴ�.</td>
					</tr>
					<tr><td height="20"></td></tr>
					<tr>
						<td><img src="images/icon_8.gif" border="0" align="absmiddle">����,�帲�������� �����ͷ� �ۼ��� �̹�����ε� �۾������� Ʋ���� �� ������ �����ϼ���!</td>
					</tr>
					</table>
					</td>
					<td background="images/manual_right1.gif"></td>
				</tr>
				<tr>
					<td><img src="images/manual_left2.gif" border="0"></td>
					<td background="images/manual_down.gif"></td>
					<td><img src="images/manual_right2.gif" border="0"></td>
				</tr>
				</table>
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
<?= $onload ?>
<? INCLUDE "copyright.php"; ?>