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
$snavi3 = "������ �����ϱ�";

/*
// ���� ������ Ȯ��
error_reporting(E_ALL);
ini_set("display_errors", 1);
*/
if (!$_usersession->isAllowedTask($PageCode)) {
	INCLUDE ("AccessDeny.inc.php");
	exit;
}
#########################################################

// file_list ��ȸ �Լ�
function myallfile($dir, $ext = ''){
	$file_arr = array();
	if (is_dir($dir)){
	    if ($dh = opendir($dir)){
	        while (($file = readdir($dh)) !== false){
	            $type = filetype($dir . $file);

	            if($type == 'file'){
	                if($ext != ''){
                        $ext = strtolower($ext);
                        $temp = explode('.',$file);
                        if(strtolower($temp[count($temp)-1]) == $ext) $file_arr[] = $dir.$file;
	                }
	                else    $file_arr[] = $dir.$file;
	            }
	            else if($type == 'dir' && ($file != '.' && $file != '..'))
	            {

	                $temp = myallfile($dir.$file.'/', $ext);
	                if(is_array($temp)){
	                	$file_arr = array_merge($file_arr, $temp);
	                }
	            }
	        }
	        closedir($dh);
	    }
	    return $file_arr;
	}
	return 0;
}

$path_root = $_SERVER['DOCUMENT_ROOT'].'data/revert/';
$revert_list = myallfile($path_root);

$type	= getInjection($_GET['type']);
$delno	= getInjection($_GET['delno']);
$file_name	= getInjection($_GET['file_name']);

//����
if($type == 'delete') {
	if(!$delno) $onload = "<script>alert(\"������ ����� ���õ��� �ʾҽ��ϴ�.\");</script>";

	if($backup->design_delete($delno,$_ShopInfo->getId())) $onload = "<script>alert(\"���� �Ǿ����ϴ�.\");</script>";
	else $onload = "<script>alert(\"���� �� ������ �߻� �߽��ϴ�.\");</script>";

} elseif ($type == 'revert') {
	//DB�������
	if(!$delno) $onload = "<script>alert(\"������ ����� ���õ��� �ʾҽ��ϴ�.\");</script>";

	if($backup->design_revert($delno)) $onload = "<script>alert(\"������ ������ �Ϸ� �Ǿ����ϴ�.\");</script>";
	else $onload = "<script>alert(\"���� �� ������ �߻� �߽��ϴ�.\");</script>";

//���Ͼ��ε�� ����
} elseif ($type == 'file_revert') {
	$backup->setFileTgz($_FILES['dbl_ftpnm']);
	$result = $backup->file_revert();
	if($result=="Notzip") $onload = "<script>alert(\"�ùٸ� ���������� �ƴմϴ�. ������,����Ȯ���ڸ� Ȯ���� �ּ���.\");</script>";
	elseif($result=="Notsize") $onload = "<script>alert(\"8M ���� �������ϸ� ���ε� �����մϴ�.\");</script>";
	elseif($result=="OK") $onload = "<script>alert(\"������ ������ �Ϸ� �Ǿ����ϴ�.\");</script>";
	else $onload = "<script>alert(\"���� �� ������ �߻� �߽��ϴ�.\");</script>";
//���� ���� ���ε�� ����
} elseif ($type == 'file_revert2') {
	$backup->setFileTgz($file_name);
	$result = $backup->file_revert('ftp');
	if($result=="Notzip") $onload = "<script>alert(\"�ùٸ� ���������� �ƴմϴ�. ������,����Ȯ���ڸ� Ȯ���� �ּ���.\");</script>";
	elseif($result=="OK") $onload = "<script>alert(\"������ ������ �Ϸ� �Ǿ����ϴ�.\");</script>";
	else $onload = "<script>alert(\"���� �� ������ �߻� �߽��ϴ�.\");</script>";
}
//������ �ҷ�����
$result = $db->select("tbldesign_backup_list",array(
						'field' => "*",
						'where' => "dbl_type = 'backup' and dbl_use = 'y'",
						"order" => "dbl_no desc" ));
?>

<?INCLUDE ("header.php"); ?>
<script type="text/javascript" src="lib.js.php"></script>
<script language="JavaScript">
function CheckForm() {
	var form = document.form1;
	var imgform = form.dbl_ftpnm.value;
	var ext = imgform.lastIndexOf(".");	//���� Ȯ���� �ڸ���
	var len = imgform.length;	//�����̸��� ����
	var extnm = imgform.substring(ext+1);	//���� �̸��� ������ Ȯ����

	if(form.dbl_ftpnm.value=="") {
		alert("���������� �������ּ���.");
		form.dbl_ftpnm.focus();
		return false;
	}

	if(extnm !="zip") {
		alert("÷�������� Ȯ���ڴ� zip�� ���ε� �����մϴ�.");
		form.dbl_ftpnm.focus();
		return false;
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
				<td height="28" class="link" align="left" background="images/con_link_bg.gif"><img src="images/top_link_house.gif" border="0" valign="absmiddle">������ġ : �����ΰ��� &gt; ������ ��� ����  &gt; <span class="2depth_select">�����ϱ�</span></td>
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
					<TD width="100%" class="notice_blue">����� ������ �����͸� ���� �� �� �ֽ��ϴ�.</TD>
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
					<TD><IMG SRC="images/design_revert_stitle.gif" ALT="�����ϱ�"></TD>
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
					<TD width="100%" class="notice_blue">1) ������ �ش� ���(/data/design/) ������ ��� ������ �� ������ ���� �Ǹ�, DB�� ������ �����͵� ��� �����ǰ� ���� �Էµ˴ϴ�.<BR>
					2) ���� �� ��������(zip)�� htm���ϸ�, ��ΰ� ��� �޾��� ���� �����ؾ� ���������� ������ ������ �̷�� ���ϴ�.<BR>
					&nbsp;&nbsp;&nbsp;&nbsp;(������� html ���ϸ� & ��θ� ����� ���Ƿ� �����ϸ� �������� ���������� �������� �ʽ��ϴ�.)<BR>
					3) htm ���ε� ������ ������ ���α׷����� ġȯ�Ǽ� ���� �˴ϴ�.<BR>
					4) �ٿ� ���� �������ϸ��� <strong>���� ���� �Ͻø� �ȵ˴ϴ�</strong>. ���� ������ ������ <strong style="color:ff0000">�Ұ���</strong>�� �� �ֽ��ϴ�.</TD>
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
			<form name=form1 action="?type=file_revert" method=post onsubmit="return CheckForm();" enctype='multipart/form-data'>
			<input type=hidden name=type>
			<tr>
				<td valign="top" align="center" style="padding-top:20px;">
					<table cellSpacing=0 cellPadding=0 width="100%" border="0" style="bottom-background">
						<tr>
							<td colspan="2" background="images/table_top_line.gif"></td>
						</tr>
						<tr>
							<td class="table_cell" width="20%"><img src="images/icon_point2.gif" border="0">��������(.zip)</td>
							<td class="td_con1" width="80%"><input type="file" name="dbl_ftpnm" id="dbl_ftpnm"  class="input" style="width:98%" /> </td>
						</tr>
						<tr>
							<td colspan="2" background="images/table_top_line.gif"></td>
						</tr>
						<tr><td height="10"></td></tr>
						<tr>
							<td colspan="2" style="height:35px;text-align:center" ><!--<input type="submit" style="width:200px;" value="�� �� �� ��" />--><input type="image" src="images/botteon_restore.gif"></td>
						</tr>
					</table>
				</td>
			</tr>
			</form>
			<tr><td height="30"></td></tr>
			<tr>
				<td>
					<table width="100%" border=0 cellpadding=0 cellspacing=0>
						<tr>
							<td><img src="images/design_backup_revert_stitle2.gif" alt="������ ���� FTP �ø� �� �����ϱ�"></td>
							<td width="100%"></td>
						</tr>
					</table>
				</td>
			</tr>
			<!-- $revert_list -->
			<tr>
				<td height="3"></td>
			</tr>
			<tr>
				<td valign="top">
					<table width="100%" cellspacing="0" cellpadding="0" border="0">
						<col width="5%"></col>
						<col width=""></col>
						<col width="10%"></col>
						<tr>
							<td colspan="6" background="images/table_top_line.gif"></td>
						</tr>
						<tr align=center>
							<td class="table_cell">No</td>
							<td class="table_cell1">����̸�</td>
							<td class="table_cell1">����</td>
						</tr>
						<tr>
							<td colspan="6" background="images/table_con_line.gif"></td>
						</tr>

		<?
						if(count($revert_list) > 0 AND $revert_list != '0' ) {
							foreach($revert_list as $k => $v) {
								$list = explode("/", $v);
								$list_file_name = array_pop($list);

		?>
						<tr>
							<td class="td_con2" align="center"><?=$k+1?></td>
							<td class="td_con1" align="center"><?=$list_file_name?></td>
							<td class="td_con1" align="center"><a href="?type=file_revert2&file_name=<?=$list_file_name?>" onclick="if(!confirm('���� ���� �Ͻðڽ��ϱ�?\n** �����Ͻø� ���� ������ �����ʹ� ���� �˴ϴ�.**')) return false;">����</a></td>
						</tr>
						<tr>
							<td colspan="4" background="images/table_con_line.gif"></td>
						</tr>
		<?
							}
						}
		?>
					</table>
				</td>
			</tr>
			<!-- �� -->
			<tr><td height=20></td></tr>
			<tr>
				<td>
					<table width="100%" border=0 cellpadding=0 cellspacing=0>
						<tr>
							<td><img src="images/design_backup_revert_stitle3.gif" alt="������ DB�� �����ϱ�"></td>
							<td width="100%"></td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td height="3"></td>
			</tr>
			<tr>
				<td valign="top">
					<table width="100%" cellspacing="0" cellpadding="0" border="0">
						<col width="5%"></col>
						<col width=""></col>
						<col width="15%"></col>
						<col width="10%"></col>
						<col width="10%"></col>
						<col width="10%"></col>
						<tr>
							<td colspan="6" background="images/table_top_line.gif"></td>
						</tr>
						<tr align=center>
							<td class="table_cell">No</td>
							<td class="table_cell1">����̸�</td>
							<td class="table_cell1">����</td>
							<td class="table_cell1">�ٿ�ε�</td>
							<td class="table_cell1">����</td>
							<td class="table_cell1">����</td>
						</tr>
						<tr>
							<td colspan="6" background="images/table_con_line.gif"></td>
						</tr>
		<?				if($result) {
							foreach($result as $k =>$v) {  ?>
						<tr>
							<td class="td_con2" align="center"><?=$k+1?></td>
							<td class="td_con1" align="center"><?=$v['dbl_subject']?></td>
							<td class="td_con1" align="center"><?=$v['dbl_date']?></td>
							<td class="td_con1" align="center"><a href="download.php?dir=<?=DataDir?>design_backup/&no=<?=$v['dbl_no']?>" target=_top>�ٿ�ε�</a></td>
							<td class="td_con1" align="center"><a href="?type=revert&delno=<?=$v['dbl_no']?>" onclick="if(!confirm('���� ���� �Ͻðڽ��ϱ�?\n** �����Ͻø� ���� ������ �����ʹ� ���� �˴ϴ�.**')) return false;">����</a></td>
							<td class="td_con1" align="center"><a href="?type=delete&delno=<?=$v['dbl_no']?>" onclick="if(!confirm('���� ���� �Ͻðڽ��ϱ�?')) return false;">����</a></td>
						</tr>
						<tr>
							<td colspan="6" background="images/table_con_line.gif"></td>
						</tr>
		<?
							}
						}
		?>
					</table>
				</td>
			</tr>
			<tr>
				<td height="20"></td>
			</tr>
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
						<td width="100%"><span class="font_dotline">������ ������ �������� �뷮 : 10M����</span></td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td width="100%" class="space_top" style="letter-spacing:-0.5pt;">- 10MB�� �ʰ��� �� FTP�� ���� �������� ���ε� �� "������ ���� FTP �ø� �� �����ϱ�" ���� ������ �����ϼ���.</td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td width="100%" class="space_top" style="letter-spacing:-0.5pt;">- �������� FTP �� ���ε� �� ��δ� /data/revert/ �Դϴ�.</td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td width="100%" class="space_top" style="letter-spacing:-0.5pt;">- �������� FTP �� ���ε� ���� �� �ʿ� ������ 757 �Դϴ�.</td>
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
