<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
INCLUDE ("access.php");

####################### ������ ���ٱ��� check ###############
$PageCode = "de-2";
$MenuCode = "design";
if (!$_usersession->isAllowedTask($PageCode)) {
	INCLUDE ("AccessDeny.inc.php");
	exit;
}
#########################################################

$type=$_POST["type"];
$design=(int)$_POST["design"];
$filelogo=$_FILES["filelogo"];

$imagepath = $Dir.DataDir."shopimages/etc/";

if($type=="update") {
	if($design==0) {
		$sql = "DELETE FROM tbldesignnewpage WHERE type='bottom' ";
		mysql_query($sql,get_db_conn());
	} else {
		$sql = "SELECT COUNT(*) as cnt FROM tbldesignnewpage WHERE type='bottom' ";
		$result=mysql_query($sql,get_db_conn());
		$row=mysql_fetch_object($result);
		if($row->cnt==0) {
			$sql = "INSERT tbldesignnewpage SET ";
			$sql.= "type		= 'bottom', ";
			$sql.= "subject		= '���θ� �ϴ�', ";
			$sql.= "code		= '".$design."' ";
			mysql_query($sql,get_db_conn());
		} else {
			$sql = "UPDATE tbldesignnewpage SET ";
			$sql.= "code		= '".$design."' ";
			$sql.= "WHERE type='bottom' ";
			mysql_query($sql,get_db_conn());
		}
		mysql_free_result($result);
	}
	$onload="<script>alert(\"���θ� �ϴ� ���ø� ������ �Ϸ�Ǿ����ϴ�.\");</script>";
} else if($type=="logo" && $filelogo[size]>0) {
	$filelogo[name] = ereg_replace(" ","",strtolower($filelogo[name]));
	if(strlen($filelogo[name])>0) {
		$ext = strtolower(substr($filelogo[name],-3));
		if ($ext!="gif" && $ext!="jpg" && $ext!="png") {
			echo "<html></head><body onload=\"alert('���θ� �ΰ�� gif, jpg, png �̹��� ���ϸ� ���ε� �����մϴ�.');location.href='".$_SERVER[PHP_SELF]."'\"></body></html>";exit;
		}
		$size=getimageSize($filelogo[tmp_name]);
		if((190>$size[0] || $size[0]>210) || (69>$size[1] || $size[1]>79)){
			echo "<html></head><body onload=\"alert('���θ� �ΰ� �̹��� ������� 200X74�ȼ��� �̹����� ���ε� �Ͻñ� �ٶ��ϴ�.');location.href='".$_SERVER[PHP_SELF]."'\"></body></html>";exit;
		}
		$filepath = $imagepath."bottom_logo.gif";
		move_uploaded_file($filelogo[tmp_name],$filepath);
		chmod($filepath,0606);
		$onload="<script>alert(\"���θ� �ΰ� ����� �Ϸ�Ǿ����ϴ�.\");</script>";
	}
}

$sql = "SELECT code FROM tbldesignnewpage WHERE type='bottom' ";
$result=mysql_query($sql,get_db_conn());
if($row=mysql_fetch_object($result)) {
	$design=$row->code;
	if($design<3) $design=0;
} else {
	$design=0;
}
mysql_free_result($result);
?>

<? INCLUDE "header.php"; ?>

<script type="text/javascript" src="lib.js.php"></script>
<script language="JavaScript">
function CheckForm() {
	if(confirm("�����Ͻ� ���������� �����Ͻðڽ��ϱ�?\n\n���� �����Ͻø� �ϴ� ������������ ���õ˴ϴ�.")) {
		document.form1.type.value="update";
		document.form1.submit();
	}
}

function CheckForm2() {
	try {
		if(document.form2.filelogo.value.length==0) {
			alert("���θ� �ΰ� �̹����� �����ϼ���.");
			document.form2.filelogo.focus();
			return;
		}
	} catch (e) {
		alert("���θ� �ΰ� �̹����� �����ϼ���.");
		return;
	}
	document.form2.type.value="logo";
	document.form2.submit();
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
				<td height="28" class="link" align="left" background="images/con_link_bg.gif"><img src="images/top_link_house.gif" border="0" valign="absmiddle">������ġ : �����ΰ��� &gt; ���ø�-���� �� ī�װ� &gt; <span class="2depth_select">���θ� �ϴ� ���ø�</span></td>
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
					<TD><IMG SRC="images/design_bottom_title.gif" ALT=""></TD>
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
					<TD width="100%" class="notice_blue"><p>���θ� �ϴ� ȭ�� �������� �����Ͽ� ����Ͻ� �� �ֽ��ϴ�.</p></TD>
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
				<td height="20"></td>
			</tr>
			<form name=form1 action="<?=$_SERVER[PHP_SELF]?>" method=post>
			<input type=hidden name=type>
			<tr>
				<td>
				<table cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td><input type=radio name="design" value="0" <?if($design==0)echo"checked";?> style="BORDER-RIGHT: medium none; BORDER-TOP: medium none; BORDER-LEFT: medium none; BORDER-BOTTOM: medium none"><B>�ؽ�Ʈ��</B></td>
				</tr>
				<tr>
					<td>
					<table cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td width="21">&nbsp;</td>
						<td width="739"><img src="images/design_bottom_img1.gif" border="0" class="imgline"></td>
					</tr>
					</table>
					</td>
				</tr>
				<tr>
					<td><p>&nbsp;</p></td>
				</tr>
				<tr>
					<td><input type=radio name="design" value="3" <?if($design==3)echo"checked";?> style="BORDER-RIGHT: medium none; BORDER-TOP: medium none; BORDER-LEFT: medium none; BORDER-BOTTOM: medium none"><B>ǥ�� �����</B></td>
				</tr>
				<tr>
					<td>
					<table cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td width="21" rowspan="2"><p>&nbsp;</p></td>
						<td width="739"><p><img src="images/design_bottom_img2.gif" border="0" class="imgline"></p></td>
					</tr>
					<tr>
						<td width="739" class="font_orange" style="padding-top:3pt;"><p>* ������ ǥ�� ��� ����ϴ� ��쿡�� ����ϼ���.</p></td>
					</tr>
					</table>
					</td>
				</tr>
				<tr>
					<td><p>&nbsp;</p></td>
				</tr>
				<tr>
					<td><input type=radio name="design" value="4" <?if($design==4)echo"checked";?> style="BORDER-RIGHT: medium none; BORDER-TOP: medium none; BORDER-LEFT: medium none; BORDER-BOTTOM: medium none"><B>���θ� �ΰ���</B></td>
				</tr>
				<tr>
					<td>
					<table cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td width="21" rowspan="2"><p>&nbsp;</p></td>
						<td width="739" height="96" background="images/design_bottom_img3.gif">
						<table class="imgline" cellpadding="0" cellspacing="0" width="100%" height="100%">
						<tr>
							<td width="729">
							<table cellpadding="0" cellspacing="0" width="200" height="74">
							<tr>
								<td width="729"><p align="center">
								<?
								if(file_exists($imagepath."bottom_logo.gif")) {
									echo "<img src=\"".$imagepath."bottom_logo.gif\" align=absmiddle>";
								} else {
									echo "&nbsp;";
								}
								?></p></td>
							</tr>
							</table>
							</td>
						</tr>
						</table>
						</td>
					</tr>
					</form>
					<tr>
						<td width="739">
						<table cellpadding="0" cellspacing="0" width="100%">
						<tr>
							<td width="100%" bgcolor="#ededed" style="padding:4pt;">
							<table cellpadding="0" cellspacing="0" width="100%" bgcolor="white">
							<form name=form2 action="<?=$_SERVER[PHP_SELF]?>" method=post enctype="multipart/form-data">
							<input type=hidden name=type>
							<tr>
								<td width="100%">
								<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
								<TR>
									<TD width="100%" height="30" background="images/blueline_bg.gif"><p align="center"><b><font color="#555555">���θ� �ΰ� ���</font></b></TD>
								</TR>
								<TR>
									<TD width="100%" background="images/table_con_line.gif"></TD>
								</TR>
								<TR>
									<TD width="100%" style="padding:7pt;">
									<table cellpadding="0" cellspacing="0" width="100%">
									<tr>
										<td width="100%">
										<table cellpadding="0" cellspacing="0" width="100%">
										<tr>
											<td width="98%"><input type=file name="filelogo" size=40 style="width:99%" class="input"></td>
											<td><p align="right"><INPUT style="FONT-SIZE: 8pt; BORDER-LEFT-COLOR: #666666; BORDER-BOTTOM-COLOR: #666666; WIDTH: 110px; COLOR: #ffffff; BORDER-TOP-COLOR: #666666; FONT-FAMILY: Tahoma; BACKGROUND-COLOR: #666666; BORDER-RIGHT-COLOR: #666666" onclick="CheckForm2();" type=button value="���θ� �ΰ� ���"></td>
										</tr>
										</table>
										</td>
									</tr>
									<tr>
										<td width="100%" height="25" class="font_orange"><p class="LIPoint">* ���θ� �ΰ� ũ��� <B>200</B>X<B>74</B>�ȼ��� �����ϼž� �մϴ�.<br>
										* ���ε� ���� �̹����� GIF(gif), JPG(jpg), PNG(png)�� �����մϴ�.</p></td>
									</tr>
									</table>
									</TD>
								</TR>
								</TABLE>
								</td>
							</tr>
							</form>
							</table>
							</td>
						</tr>
						</table>
						</td>
					</tr>
					</table>
					</td>
				</tr>
				<tr>
					<td><p>&nbsp;</p></td>
				</tr>
				</table>
				</td>
			</tr>
			<tr>
				<td height=10></td>
			</tr>
			<tr>
				<td align="center"><a href="javascript:CheckForm();"><img src="images/botteon_save.gif" width="113" height="38" border="0"></a></td>
			</tr>
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
					<TD COLSPAN=3 width="100%" valign="top" class="menual_bg" style="padding-top:8pt; padding-bottom:8pt; padding-left:4pt;">
					<table cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td width="20" align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td width="100%"><span class="font_dotline">���θ� �ϴ� ���ø�/���� ����</span></td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td width="100%" class="space_top">
							- <span class="font_orange">���� ������ ���� ������ �ݵ�� ����Ͻñ� �ٶ��ϴ�.(���� ���� �� �߻��� ������ ���� ���� ���񽺸� ������ �帮�� �ʽ��ϴ�.)</span><br />
							- <span class="font_orange">���ø� ���� : /lib/bottom.php</span>
						</td>
					</tr>
					<tr>
						<td height="20" colspan="2"></td>
					</tr>
					<tr>
						<td width="20" align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td width="100%"><span class="font_dotline">�ϴ� �ȳ��� ���� ǥ��</span></td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td width="100%" class="space_top" style="letter-spacing:-0.5pt;">- <a href="javascript:parent.topframe.GoMenu(1,'shop_basicinfo.php');"><span class="font_blue">�������� > ���� �⺻���� ���� > ���� �⺻���� ����</span></a> ���� �ԷµǴ� ��ȣ �� ����ó�� �ڵ����� ǥ��˴ϴ�.</td>
					</tr>
					<tr>
						<td height="20" colspan="2"></td>
					</tr>
					<tr>
						<td width="20" align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td width="100%"><span class="font_dotline">���� ������</span></td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td width="100%" class="space_top" style="letter-spacing:-0.5pt;">- <a href="javascript:parent.topframe.GoMenu(2,'design_eachbottom.php');"><span class="font_blue">�����ΰ��� > ����������-���� �� ���ϴ� > �ϴ�ȭ�� �ٹ̱�</span></a> ���� ���� �������� �� �� �ֽ��ϴ�.</td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td width="100%" class="space_top" style="letter-spacing:-0.5pt;">- ���� ������ ���� ���ø��� ������� �ʽ��ϴ�.</td>
					</tr>
					<tr>
						<td height="20" colspan="2"></td>
					</tr>
					<tr>
						<td width="20" align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td width="100%"><span class="font_dotline">���ø� ������</span></td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td width="100%" class="space_top" style="letter-spacing:-0.5pt;">- �� �޴����� ���ϴ� ���ø����� �缱���ϸ� ������������ �����ǰ� ������ ���ø����� ����˴ϴ�.</td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td width="100%" class="space_top" style="letter-spacing:-0.5pt;">- ���������ο��� [�⺻������] �Ǵ� [�����ϱ�] -> �⺻ ���ø����� ����� -> ���ϴ�  ���ø��� �����Ͻø� �˴ϴ�.</td>
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