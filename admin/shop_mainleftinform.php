<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
INCLUDE ("access.php");

####################### ������ ���ٱ��� check ###############
$PageCode = "sh-2";
$MenuCode = "shop";
if (!$_usersession->isAllowedTask($PageCode)) {
	INCLUDE ("AccessDeny.inc.php");
	exit;
}
#########################################################

$imagepath = $Dir.DataDir."shopimages/etc/";

$type=$_POST["type"];
$old_image=$_POST["old_image"];
$up_design_type=$_POST["up_design_type"];
$up_body=chop($_POST["up_body"]);
$up_image=$_FILES["up_image"];

if ($type=="up") {
	if ($up_design_type==1) {
		if ($up_image[name] && (strtolower(substr($up_image[name],strlen($up_image[name])-3,3))=="gif" || strtolower(substr($up_image[name],strlen($up_image[name])-3,3))=="jpg")) {
			if ($up_image[size]<153600) {
				$up_image[name]="leftevent".substr($up_image[name],-4);
				if(strlen($old_image)>0 && file_exists($imagepath.$old_image)) {
					unlink($imagepath.$old_image);
				}
				move_uploaded_file($up_image[tmp_name],$imagepath.$up_image[name]);
				chmod($imagepath.$up_image[name],0606);
			} else {
				$up_image[name] = $old_image;
			}
		} else {
			$up_image[name] = $old_image;
		}
	} else {
		@unlink($imagepath.$old_image);
	}

	$sql = "SELECT COUNT(*) as cnt FROM tbldesignnewpage ";
	$sql.= "WHERE type = 'leftevent'";
	$result = mysql_query($sql,get_db_conn());
	$row = mysql_fetch_object($result);
	mysql_free_result($result);
	$cnt=$row->cnt;
	if ($cnt==1) {
		$sql="UPDATE tbldesignnewpage SET ";
		$sql.= "filename	= '".$up_image[name]."', ";
		$sql.= "body		= '".$up_body."', ";
		$sql.= "code		= '".$up_design_type."' ";
		$sql.= "WHERE type = 'leftevent'";
		$onload="<script>alert('�˸����� ���� ������ �Ϸ�Ǿ����ϴ�.');</script>";
	} else {
		$sql="INSERT INTO tbldesignnewpage (type,subject,body,filename,code) VALUES ('leftevent','���� �� �˸����� ������','".$up_body."','".$up_image[name]."','".$up_design_type."')";
		$onload="<script>alert('�˸����� ���� ����� �Ϸ�Ǿ����ϴ�.');</script>";
	}
	mysql_query($sql,get_db_conn());
} else if ($type=="del") {
	$sql="DELETE FROM tbldesignnewpage WHERE type = 'leftevent'";
	$result = mysql_query($sql,get_db_conn());
	$onload="<script>alert('�˸������� �ʱ�ȭ �Ǿ����ϴ�.');</script>";
}

$sql = "SELECT body,filename,code FROM tbldesignnewpage ";
$sql.= "WHERE type = 'leftevent'";
$result = mysql_query($sql,get_db_conn());
if ($row = mysql_fetch_object($result)) {
	$body=$row->body;
	$filename=$row->filename;
	$design_type=$row->code;
} else {
	$filename="";
	$design_type="1";
}
mysql_free_result($result);

${"chk_type".$design_type} = "checked";
?>

<? INCLUDE "header.php"; ?>

<script type="text/javascript" src="lib.js.php"></script>
<script>
function ChangeType(type){
	if (type==1) {
		document.form1.up_image.disabled=false;
		document.form1.up_body.disabled=true;
		document.form1.up_body.style.backgroundColor = '#EFEFEF';
		document.form1.up_image.style.backgroundColor = '#FFFFFF';
	} else {
		document.form1.up_image.disabled=true;
		document.form1.up_body.disabled=false;
		document.form1.up_body.style.backgroundColor = '#FFFFFF'; 
		document.form1.up_image.style.backgroundColor = '#EFEFEF';
	}
}
function del(){
	if (confirm("�� �˸������� �ʱ�ȭ �Ͻðڽ��ϱ�?")) {
		document.form1.type.value="del";
		document.form1.submit();
	}
}
function CheckForm(){
	var design_type = "";
	for(var i=0;i<form1.up_design_type.length;i++){
		if(form1.up_design_type[i].checked==true){
			design_type=form1.up_design_type[i].value;
			break;
		}
	}
	if (design_type.length==0) {
		alert("�˸����� ������ Ÿ���� �����ϼ���");
		form1.up_design_type[0].focus();
		return;
	} else if (design_type==1) {
		if (form1.up_image.value.length==0 && "<?=$filename?>"=="") {
			alert("�̹��� ������ �����ϼ���");
			form1.up_image.focus();
			return;
		}
		form1.up_body.value="";
	} else if (design_type==2) {
		if (form1.up_body.value.length==0) {
			alert("������ �Է��ϼ���");
			form1.up_body.focus();
			return;
		}
	}
	if (confirm("����Ͻðڽ��ϱ�?")) {
		document.form1.type.value="up";
		document.form1.submit();
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
			<? include ("menu_shop.php"); ?>
			</td>

			<td></td>
			<td valign="top">




<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td height="29" colspan="3">
			<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td height="28" class="link" align="left" background="images/con_link_bg.gif"><img src="images/top_link_house.gif" border="0" valign="absmiddle">������ġ : �������� &gt; ���θ� ȯ�� ���� &gt; <span class="2depth_select">���� �� �˸� ������</span></td>
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
					<TD><IMG SRC="images/shop_mainleftinform_title.gif" ALT=""></TD>
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
					<TD width="100%" class="notice_blue"><p>1) ���θ� ���� ���� �ϴ� ������ ������ �˸��� �̺�Ʈ/���˸� ���� ����� �� �ֽ��ϴ�.<br>2) <a href="javascript:parent.topframe.GoMenu(2,'design_eachleftmenu.php');"><span class="font_blue">�����ΰ��� > ���������� - ���� �� ���ϴ� > ���ʸ޴� �ٹ̱�</span></a>�� ��� �� ��쿡�� ������� �ʽ��ϴ�.<br>3) �̹��� ��Ϲ��, html��Ϲ���� 1���� ���ð����մϴ�.</p></TD>
					<TD background="images/distribute_07.gif"><IMG SRC="images/distribute_07.gif" ></TD>
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
				<td height="20"></td>
			</tr>
			
			<form name=form1 action="<?=$_SERVER[PHP_SELF]?>" method=post enctype="multipart/form-data">
			<input type=hidden name=type>
			<tr>
				<td>	
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/shop_mainleftinform_stitle2.gif" WIDTH="192" HEIGHT=31 ALT=""></TD>
					<TD width="100%" background="images/shop_basicinfo_stitle_bg.gif">&nbsp;</TD>
					<TD><IMG SRC="images/shop_basicinfo_stitle_end.gif" WIDTH=10 HEIGHT=31 ALT=""></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr>
				<td height=3></td>
			</tr>
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
					<TD width="100%" class="notice_blue"><p>1) GIF(gif), JPG(jpg)����, ���� 200�ȼ��� ����(���λ����� ���� ����). 200�ȼ� �̻��� ��� �ش� �κ��� �����.<br>2) ���ε� ������ �̹��� �뷮�� 150KB �����Դϴ�.</p></TD>
					<TD background="images/distribute_07.gif"><IMG SRC="images/distribute_07.gif" ></TD>
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
				<td height=3></td>
			</tr>
			<tr>
				<td>
				<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
				<TR>
					<TD colspan=2 background="images/table_top_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell" width="760" colspan="2"><input type=radio id="idx_design_type1" name=up_design_type value=1 <?=$chk_type1?> onclick="ChangeType(1)"><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_design_type1><b>�˸����� �̹����� ����ϱ�</b></label></TD>
				</TR>
				<TR>
					<TD colspan="2" width="760" background="images/table_con_line.gif"></TD>
				</TR>
				<TR>
					<TD class=linebottomleft style="PADDING-RIGHT: 5px; PADDING-LEFT: 10px; PADDING-BOTTOM: 5px; PADDING-TOP: 5px" align=left width="745" bgColor=#ffffff colspan="2"><input type=file name=up_image style="WIDTH: 500px; BACKGROUND-COLOR: #ffffff" class="input">
<?
	if (strlen($filename)>0) {
		if (file_exists($imagepath.$filename)==true) {
			$width = getimagesize($imagepath.$filename);
			if ($width[0]>=200) $width=" width=200 ";
		}
?>
						<img width=20 height=0><img src="<?=$imagepath.$filename?>" <?=$width?>>
						<input type=hidden name=old_image value="<?=$filename?>">
<?
	}
?>
					</TD>
				</TR>
				<TR>
					<TD colspan=2 background="images/table_top_line.gif"></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr>
				<td height="30"></td>
			</tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/shop_mainleftinform_stitle3.gif" WIDTH="192" HEIGHT=31 ALT=""></TD>
					<TD width="100%" background="images/shop_basicinfo_stitle_bg.gif">&nbsp;</TD>
					<TD><IMG SRC="images/shop_basicinfo_stitle_end.gif" WIDTH=10 HEIGHT=31 ALT=""></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr>
				<td height=3></td>
			</tr>
			<tr>
				<td>
				<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
				<TR>
					<TD colspan=2 background="images/table_top_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell" width="760" colspan="2"><b><input type=radio id="idx_design_type2" name=up_design_type value=2 <?=$chk_type2?> onclick="ChangeType(2)"><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_design_type2>�˸����� HTML�� �����ϱ�</label>(���� 200�ȼ� ����, ���� ���� ����)</b>&nbsp;&nbsp;<span class="font_orange">* �˸����� �̹����� ��ϵ� ������ �ڵ� ���� ó���˴ϴ�.</span></TD>
				</TR>
				<TR>
					<TD colspan="2" width="760" background="images/table_con_line.gif"></TD>
				</TR>
				<TR>
					<TD width="100%" colspan="2" class="space"><textarea name=up_body rows=10 wrap=off style="WIDTH: 100%; BACKGROUND-COLOR: #efefef" class="textarea"><?=$body?></textarea></TD>
				</TR>
				<TR>
					<TD colspan=2 background="images/table_top_line.gif"></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td align="center"><a href="javascript:CheckForm();"><img src="images/botteon_save.gif" width="113" height="38" border="0"></a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:del();"><img src="images/btn_initialization.gif" width="113" height="38" border="0" hspace="2"></a></td>
			</tr>
			</form>
			<tr>
				<td height=20></td>
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
					<TD COLSPAN=3 width="100%" valign="top" bgcolor="white" style="padding-top:8pt; padding-bottom:8pt; padding-left:4pt;" class="menual_bg">
					<table cellpadding="0" cellspacing="0" width="99%">
					<tr>
						<td width="163" valign="top"><p><img src="images/shop_mainleftinform_img.gif" border="0"></p></td>
						<td width="100%" valign="top">
						<table cellpadding="0" cellspacing="0" width="100%">
						<tr>
							<td width="20" align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
							<td width="100%"><b>�̺�Ʈ, �����ð�, ���¹�ȣ��</b></td>
						</tr>
						<tr>
							<td width="20" align="right">&nbsp;</td>
							<td width="100%" class="space_top"><p>- �̹��� �Ǵ� ���� html�� ���� ������ Ȱ���� �� �ֽ��ϴ�.</p></td>
						</tr>
						<tr>
							<td width="20" align="right">&nbsp;</td>
							<td width="100%" class="space_top"><p>- ���� ���� ��ʸ� html�� ����ϰų� �̺�Ʈ ��ǰ�Ұ��� �پ��� ������ �������� Ȱ���ϼ���.</p></td>
						</tr>
						<tr>
							<td width="20" align="right">&nbsp;</td>
							<td width="100%" class="space_top"><p>- ����������, Easy�����ο��� ������ ������ ��ġ������ ���� �� �� �ֽ��ϴ�.</p></td>
						</tr>
						<tr>
							<td height="5" colspan="2"></td>
						</tr>
						<tr>
							<td width="20" align="right">&nbsp;</td>
							<td width="100%" class="space_top"><img src="images/shop_mainleftinform_img1.gif" border="0"></td>
						</tr>
						<tr>
							<td height="20" colspan="2"></td>
						</tr>
						<tr>
							<td width="20" align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
							<td width="100%">����,�帲�������� �����ͷ� �ۼ��� �̹�����ε� �۾������� Ʋ���� �� ������ �����ϼ���!</td>
						</tr>
						</table>
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

<script>ChangeType(<?=$design_type?>);</script>

<?=$onload?>

<? INCLUDE "copyright.php"; ?>