<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
INCLUDE ("access.php");

####################### ������ ���ٱ��� check ###############
$PageCode = "sh-1";
$MenuCode = "shop";
if (!$_usersession->isAllowedTask($PageCode)) {
	INCLUDE ("AccessDeny.inc.php");
	exit;
}
#########################################################

$type = $_POST["type"];
$up_privercyname = $_POST["up_privercyname"];
$up_privercyemail = $_POST["up_privercyemail"];
$up_privercy = $_POST["up_privercy"];
$up_privercy2 = $_POST["up_privercy2"];

$up_file1=$_FILES["up_file1"];
$up_file2=$_FILES["up_file2"];

$filepath = $Dir."w3c/";
$fileurl = "http://".$_ShopInfo->getShopurl()."w3c/";

if ($type == "up") {
	$sql = "SELECT COUNT(*) as cnt FROM tbldesign ";
	$result = mysql_query($sql,get_db_conn());
	$row = mysql_fetch_object($result);
	$flag = $row->cnt;
	mysql_free_result($result);

	if ($flag) {
		$onload = "<script> alert('���� ������ �Ϸ�Ǿ����ϴ�.'); </script>";
		$sql = "UPDATE tbldesign SET privercy = '".str_replace("<P>&nbsp;</P>", "", $up_privercy).(strlen(str_replace("<P>&nbsp;</P>", "", $up_privercy2))>0?"=".$up_privercy2:"")."' ";
	} else {
		$onload = "<script> alert('���� ����� �Ϸ�Ǿ����ϴ�.'); </script>";
		$sql = "INSERT tbldesign SET ";
		$sql.= "privercy	= '".$up_privercy.(strlen($up_privercy2)>0?"=".$up_privercy2:"")."' ";
	}
	$insert = mysql_query($sql,get_db_conn());
	if ($insert) {
		$sql = "UPDATE tblshopinfo SET ";
		$sql.= "privercyname	= '".$up_privercyname."', ";
		$sql.= "privercyemail	= '".$up_privercyemail."' ";
		mysql_query($sql,get_db_conn());
		DeleteCache("tblshopinfo.cache");
	}

	if (strlen($up_file1[name])>0) {
		if (strtolower(substr($up_file1[name],strlen($up_file1[name])-3,3))!="xml") {
			$onload = "<script>alert (\"������ǥ�� ����1 Ȯ���ڴ� xml �� �����մϴ�.\");</script>";
		} else if (strtolower($up_file1[name])!="p3p.xml") {
			$onload = "<script>alert (\"������ǥ�� ����1 �̸��� p3p.xml �� �����մϴ�.\");</script>";
		} else {
			$file1_name="p3p.xml";
			if(strlen(RootPath)>0) {
				if($fp=@fopen($up_file1[tmp_name], "r")) {
					$p3pdata=fread($fp, filesize($up_file1[tmp_name]));
					fclose($fp);
					if(strlen($p3pdata)>0) {
						$p3pdata = str_replace("/w3c/p3policy.xml", "/".RootPath."w3c/p3policy.xml", $p3pdata);
						if($fp=fopen($filepath.$file1_name, "w")) {
							fputs($fp, $p3pdata);
							fclose($fp);
						}
					} else {
						$onload = "<script>alert (\"������ǥ�� ����1 ������ �������� �ʽ��ϴ�.\");</script>";
					}
				} else {
					$onload = "<script>alert (\"������ǥ�� ����1 ��� �� ������ �߻��ƽ��ϴ�.\");</script>";
				}
			} else {
				@unlink($filepath.$file1_name);
				move_uploaded_file($up_file1[tmp_name],"$filepath$file1_name");
				chmod("$filepath$file1_name",0644);
			}
		}
	} else if($file1delete=="Y") {
		$file1_name="p3p.xml";
		@unlink($filepath.$file1_name);
	}

	if (strlen($up_file2[name])>0) {
		if (strtolower(substr($up_file2[name],strlen($up_file2[name])-3,3))!="xml") {
			$onload = "<script>alert (\"������ǥ�� ����2 Ȯ���ڴ� xml �� �����մϴ�.\");</script>";
		} else if (strtolower($up_file2[name])!="p3policy.xml") {
			$onload = "<script>alert (\"������ǥ�� ����2 �̸��� p3policy.xml �� �����մϴ�.\");</script>";
		} else {
			$file2_name="p3policy.xml";
			@unlink($filepath.$file2_name);
			move_uploaded_file($up_file2[tmp_name],"$filepath$file2_name");
			chmod("$filepath$file2_name",0644);
		}
	} else if($file2delete=="Y") {
		$file2_name="p3policy.xml";
		@unlink($filepath.$file2_name);
	}
}

$sql = "SELECT privercy FROM tbldesign ";
$result = mysql_query($sql,get_db_conn());
if ($row=mysql_fetch_object($result)) {
	$privercy_exp = @explode("=", $row->privercy);
	$privercy = ($privercy_exp[0] == "<P>&nbsp;</P>"?"":$privercy_exp[0]);
	$privercy2 = ($privercy_exp[1] == "<P>&nbsp;</P>"?"":$privercy_exp[1]);
}
mysql_free_result($result);
if(strlen($privercy)==0 && file_exists($Dir.AdminDir."privercy.txt")) {
	$fp=fopen($Dir.AdminDir."privercy.txt", "r");
	$privercy=fread($fp,filesize($Dir.AdminDir."privercy.txt"));
	fclose($fp);
}
if(strlen($privercy2)==0 && file_exists($Dir.AdminDir."privercy2.txt")) {
	$fp=fopen($Dir.AdminDir."privercy2.txt", "r");
	$privercy2=fread($fp,filesize($Dir.AdminDir."privercy2.txt"));
	fclose($fp);
}
$sql = "SELECT privercyname, privercyemail FROM tblshopinfo ";
$result = mysql_query($sql,get_db_conn());
$row = mysql_fetch_object($result);
mysql_free_result($result);
$privercyname = $row->privercyname;
$privercyemail = $row->privercyemail;

?>

<? INCLUDE "header.php"; ?>

<script type="text/javascript" src="lib.js.php"></script>
<script language="Javascript1.2" src="htmlarea/editor.js"></script>
<script>
//_editor_url = "htmlarea/";
function CheckForm(){
	//var tmpobj1=document.all["_up_privercy_editor"];
	//var tmpobj2=document.all["_up_privercy2_editor"];
	//form1.up_privercy.value=tmpobj1.contentWindow.document.body.innerHTML;
	//form1.up_privercy2.value=tmpobj2.contentWindow.document.body.innerHTML;
	form1.type.value="up";
	form1.submit();
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
				<td height="28" class="link" align="left" background="images/con_link_bg.gif"><img src="images/top_link_house.gif" border="0" valign="absmiddle">������ġ : �������� &gt; ���� �⺻���� ���� &gt; <span class="2depth_select">���θ� ����������޹�ħ</span></td>
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
			<form name=form1 action="<?=$_SERVER[PHP_SELF]?>" method=post enctype="multipart/form-data">
			<input type=hidden name=type>
			<tr><td height="8"></td></tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/shop_privercyinfo_title.gif"  ALT=""></TD>
				</TR>
				<TR>
					<TD width="100%" background="images/title_bg.gif" HEIGHT=21></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr><td height=3></td></tr>
			<tr>
				<td>
				<table WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<tr>
					<td><img src="images/distribute_01.gif" WIDTH=7 HEIGHT=7 ALT=""></td>
					<td COLSPAN=2 background="images/distribute_02.gif"></td>
					<td><img src="images/distribute_03.gif" WIDTH=8 HEIGHT=7 ALT=""></td>
				</tr>
				<tr>
					<td background="images/distribute_04.gif"><img src="images/distribute_04.gif" WIDTH=7 HEIGHT="4" ALT=""></td>
					<td class="notice_blue"><img src="images/distribute_img.gif"></td>
					<td width="100%" class="notice_blue">����������޹�ħ, ����å���� ������ �����մϴ�.</b></td>
					<td background="images/distribute_07.gif"><img src="images/distribute_07.gif" WIDTH=8 HEIGHT="4" ALT=""></td>
				</tr>
				<tr>
					<td><img src="images/distribute_08.gif" WIDTH=7 HEIGHT=8 ALT=""></td>
					<td COLSPAN=2 background="images/distribute_09.gif"></td>
					<td><img src="images/distribute_10.gif" WIDTH=8 HEIGHT=8 ALT=""></td>
				</tr>
				</table>
				</td>
			</tr>
			<tr><td height="20"></td></tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/shop_privercyinfo_stitle2.gif" border="0"></TD>
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
					<TD><IMG SRC="images/distribute_01.gif" WIDTH=7 HEIGHT=7 ALT=""></TD>
					<TD COLSPAN=2 background="images/distribute_02.gif"></TD>
					<TD><IMG SRC="images/distribute_03.gif" WIDTH=8 HEIGHT=7 ALT=""></TD>
				</TR>
				<TR>
					<TD background="images/distribute_04.gif"><IMG SRC="images/distribute_04.gif" WIDTH=7 HEIGHT="4" ALT=""></TD>
					<TD class="notice_blue"><IMG SRC="images/distribute_img.gif"></TD>
					<TD width="100%" class="notice_blue">����������޹�ħ ������ǥ�� ������ ����մϴ�.<br><br><br>
					<b>����������޹�ħ ������ ǥ�� ���� ��� ����</b><br>
					1. <a href="http://www.checkprivacy.co.kr" target="_blank"><font color="#FF4C00">http://www.checkprivacy.co.kr</font></a> ���� ����������޹�ħ ������ ǥ�ø� �ۼ� �ϼ���.<br>
					2. ����������޹�ħ ������ ǥ�� �ۼ� �Ϸ� �� ������ ǥ�� ������ �ٿ�ε� �޽��ϴ�.<br>
					3. ���� ���������� ���� �� �ش� ������ �׸� �°� ������ ǥ�� ������ ����մϴ�.<br>
					4. ����� ���������� �Ϸ� �ƴٸ� <a href="http://www.checkprivacy.co.kr/user/" target="_blank"><font color="#FF4C00">http://www.checkprivacy.co.kr/user/</font></a> ���� ���� Ȯ���ϼ���.<br>
					&nbsp;&nbsp;&nbsp;&nbsp;�� ������丮 ��� ���θ��� ���� Ȯ�ν� ������丮���� �Է��� �ּ���.<br>
					&nbsp;&nbsp;&nbsp;&nbsp;�� ������丮�� ��������� ���� ��� p3p.xml ������ <a href="http://www.checkprivacy.co.kr" target="_blank"><font color="#FF4C00">http://www.checkprivacy.co.kr</font></a>���� �޾� ���� �� �ּ���.<br>
					</TD>
					<TD background="images/distribute_07.gif"><IMG SRC="images/distribute_07.gif" WIDTH=8 HEIGHT="4" ALT=""></TD>
				</TR>
				<TR>
					<TD><IMG SRC="images/distribute_08.gif" WIDTH=7 HEIGHT=8 ALT=""></TD>
					<TD COLSPAN=2 background="images/distribute_09.gif"></TD>
					<TD><IMG SRC="images/distribute_10.gif" WIDTH=8 HEIGHT=8 ALT=""></TD>
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
					<TD background="images/table_top_line.gif" width="160"><img src="images/table_top_line.gif"></TD>
					<TD background="images/table_top_line.gif"  ></TD>
				</TR>
				<TR>
					<TD class="table_cell" ><img src="images/icon_point2.gif" width="8" height="11" border="0">������ǥ�� ����1<br>&nbsp;&nbsp;<font color="#FF4C00">p3p.xml</font></TD>
					<TD class="td_con1"><input type=file name=up_file1 value="" class="input" style="width:100%;"><br>
					<?=(file_exists($filepath."p3p.xml")?"���ε� Ȯ�� : <A HREF=\"".$fileurl."p3p.xml\" target=_blank>".$fileurl."p3p.xml</a>&nbsp;&nbsp;
					<input type=\"checkbox\" name=\"file1delete\" value=\"Y\" id=\"idx_file1delete\"><label style='cursor:hand;' onmouseover=\"style.textDecoration='underline'\" onmouseout=\"style.textDecoration='none'\" for=\"idx_file1delete\">����</label>":"��ϵ� ������ �����ϴ�.")?></TD>
				</TR>
				<TR>
					<TD colspan="2"  background="images/table_con_line.gif"><img src="images/table_con_line.gif" width="4" height="1" border="0"></TD>
				</TR>
				<TR>
					<TD class="table_cell" ><img src="images/icon_point2.gif" width="8" height="11" border="0">������ǥ�� ����2<br>&nbsp;&nbsp;<font color="#FF4C00">p3policy.xml</font></TD>
					<TD class="td_con1"><input type=file name=up_file2 value="" class="input" style="width:100%;"><br>
					<?=(file_exists($filepath."p3policy.xml")?"���ε� Ȯ�� : <A HREF=\"".$fileurl."p3policy.xml\" target=_blank>".$fileurl."p3policy.xml</a>&nbsp;&nbsp;
					<input type=\"checkbox\" name=\"file2delete\" value=\"Y\" id=\"idx_file2delete\"><label style='cursor:hand;' onmouseover=\"style.textDecoration='underline'\" onmouseout=\"style.textDecoration='none'\" for=\"idx_file2delete\">����</label>":"��ϵ� ������ �����ϴ�.")?></TD>
				</TR>
				<TR>
					<TD colspan="2"  background="images/table_con_line.gif"><img src="images/table_con_line.gif" width="4" height="1" border="0"></TD>
				</TR>
				</table>
				</td>
			</tr>
			<tr><td height="50"></td></tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/shop_privercyinfo_stitle1.gif" ALT=""></TD>
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
					<TD><IMG SRC="images/distribute_01.gif" WIDTH=7 HEIGHT=7 ALT=""></TD>
					<TD COLSPAN=2 background="images/distribute_02.gif"></TD>
					<TD><IMG SRC="images/distribute_03.gif" WIDTH=8 HEIGHT=7 ALT=""></TD>
				</TR>
				<TR>
					<TD background="images/distribute_04.gif"><IMG SRC="images/distribute_04.gif" WIDTH=7 HEIGHT="4" ALT=""></TD>
					<TD class="notice_blue"><IMG SRC="images/distribute_img.gif"></TD>
					<TD width="100%" class="notice_blue">����������޹�ħ, ����å���� ������ �����մϴ�.<br><br><br>
					<b>����������޹�ħ ���� ��� ����</b><br>
					1. <a href="http://www.checkprivacy.co.kr" target="_blank"><font color="#FF4C00">http://www.checkprivacy.co.kr</font></a> ���� ����������޹�ħ ������ ǥ�� ��� �� �߱޵� ����������޹�ħ ������ �����մϴ�.<br>
					2. �ݵ�� �Ʒ� ������ ��ϵ� ������ �����, <b>[���θ� ���� �������� ��޹�ħ]</b>�� ����� ������ �ٿ��ֱ� ���ֽñ� �ٶ��ϴ�.<br>
					3. <b>[ȸ������ / ��ȸ�� ���Ž� �������� ��޹�ħ]</b>�� ȸ������/��ȸ�����Ž� ������ �����ϴ� ������ ���ؼ� �����ϰ� �־��ּ���.<br>
					&nbsp;&nbsp;&nbsp;&nbsp;���� �⺻���� �� �߰������� ���������� ������ �ʿ��� ������ �ִٸ� ��ü������ ������ �߰��� ������ �־��ֽñ� �ٶ��ϴ�.
				   </TD>
					<TD background="images/distribute_07.gif"><IMG SRC="images/distribute_07.gif" WIDTH=8 HEIGHT="4" ALT=""></TD>
				</TR>
				<TR>
					<TD><IMG SRC="images/distribute_08.gif" WIDTH=7 HEIGHT=8 ALT=""></TD>
					<TD COLSPAN=2 background="images/distribute_09.gif"></TD>
					<TD><IMG SRC="images/distribute_10.gif" WIDTH=8 HEIGHT=8 ALT=""></TD>
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
					<TD background="images/table_top_line.gif" width="160"><img src="images/table_top_line.gif"></TD>
					<TD background="images/table_top_line.gif"  ></TD>
				</TR>
				<TR>
					<TD class="table_cell" ><img src="images/icon_point2.gif" width="8" height="11" border="0">������������ å����</TD>
					<TD class="td_con1"><input type=text name=up_privercyname value="<?=$privercyname?>" size=15 maxlength=10 onKeyUp="chkFieldMaxLen(10)" class="input"></TD>
				</TR>
				<TR>
					<TD colspan="2"  background="images/table_con_line.gif"><img src="images/table_con_line.gif" width="4" height="1" border="0"></TD>
				</TR>
				<TR>
					<TD class="table_cell" ><img src="images/icon_point2.gif" width="8" height="11" border="0">å���� E-mail</TD>
					<TD class="td_con1" ><input type=text name=up_privercyemail value="<?=$privercyemail?>" size=35 maxlength=50 onKeyUp="chkFieldMaxLen(50)" class="input"></TD>
				</TR>
				<TR>
					<TD colspan="2"  background="images/table_con_line.gif"><img src="images/table_con_line.gif" width="4" height="1" border="0"></TD>
				</TR>
				</table>
				</td>
			</tr>
			<tr>
				<td height=3></td>
			</tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0 style="table-layout:fixed">
				<col width=7></col>
				<col width=></col>
				<col width=8></col>
				<TR>
					<TD><IMG SRC="images/distribute_01.gif"></TD>
					<TD background="images/distribute_02.gif"></TD>
					<TD><IMG SRC="images/distribute_03.gif"></TD>
				</TR>
				<TR>
					<TD background="images/distribute_04.gif"></TD>
					<TD class="notice_blue" valign="top">
					<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
					<TR>
						<TD class="notice_blue" valign="top">&nbsp;</TD>
						<TD width="100%" class="space"><span class=notice_blue>1) <B>[NAME]</B>, <B>[EMAIL]</B>�� [�����⺻����]�� ������������ å���ڸ�� E-mail�� �ڵ� �Էµ˴ϴ�. <br>2) <B>[SHOP]</B>�� �������� �ڵ� �Էµ˴ϴ�.<br>3) <B>[TEL]</B>�� [�����⺻����]�� ����� ��ȭ��ȣ�� �ڵ� �Էµ˴ϴ�.</span></TD>
					</TR>
					</TABLE>
					</TD>
					<TD background="images/distribute_07.gif"></TD>
				</TR>
				<TR>
					<TD><IMG SRC="images/distribute_08.gif"></TD>
					<TD background="images/distribute_09.gif"></TD>
					<TD><IMG SRC="images/distribute_10.gif"></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr>
				<td>
				<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
				<tr>
					<td height="10" colspan="2"></td>
				</tr>
				<TR>
					<TD background="images/table_top_line.gif" colspan="2" height="3"></TD>
				</TR>
				<TR>
					<TD class="table_cell" colspan="2" align="center">���θ� ���� �������� ��޹�ħ</TD>
				</TR>
				<TR>
					<TD colspan="2"  background="images/table_con_line.gif"><img src="images/table_con_line.gif" width="4" height="1" border="0"></TD>
				</TR>
				<TR>
					<TD width="100%" colspan="2">
					<table cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td><textarea name=up_privercy rows=15 wrap=off style="width:100%" class="textarea"><?=$privercy?>
<?
	if (!$privercy) {
		include ("privercy.txt");
	}
?>
						</textarea></td>
					</tr>
					</table>
					</TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr>
				<td>
				<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
				<tr>
					<td height="10" colspan="2"></td>
				</tr>
				<TR>
					<TD background="images/table_top_line.gif" colspan="2" height="3"></TD>
				</TR>
				<TR>
					<TD class="table_cell" colspan="2" align="center">ȸ������ / ��ȸ�� ���Ž� �������� ��޹�ħ</TD>
				</TR>
				<TR>
					<TD colspan="2"  background="images/table_con_line.gif"><img src="images/table_con_line.gif" width="4" height="1" border="0"></TD>
				</TR>
				<TR>
					<TD width="100%" colspan="2">
					<table cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td><textarea name=up_privercy2 rows=15 wrap=off style="width:100%" class="textarea"><?=$privercy2?>
<?
	if (!$privercy2) {
		include ("privercy2.txt");
	}
?>
						</textarea></td>
					</tr>
					</table>
					</TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr>
				<td height=10></td>
			</tr>
			<tr>
				<td align="center"><a href="javascript:CheckForm();"><img src="images/botteon_save.gif" width="113" height="38" border="0"></a></td>
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
					<TD background="images/manual_left1.gif"><IMG SRC="images/manual_left1.gif" WIDTH=15 HEIGHT="5" ALT=""></TD>
					<TD COLSPAN=3 width="100%" valign="top" bgcolor="white" style="padding-top:8pt; padding-bottom:8pt; padding-left:4pt;" class="menual_bg">
					<table cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td width="20" align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td ><span class="font_dotline">����������޹�ħ �� ������ǥ��</span></td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td  class="space_top">
						<span class="font_orange"><b>����������޹�ħ �� ������ǥ��������Ÿ� �̿����� �� ������ȣ� ���� ����(����'������Ÿ���')�� ���� <br>
						������Ʈ�������������� ����ϴ� ��� ����������޹�ħ�� �����ϰ� ������ ǥ�ø� �Ͽ��� �մϴ�.</b></span><br>
						<br>
						<br>
						<span class="font_black">1. ������Ÿ� �̿����� �� ������ȣ � ���� ����</span><br>
						<br>
						&nbsp;&nbsp;&nbsp;<span class="font_orange"><b>- ��27����2(����������޹�ħ�� ����)</b></span><br>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;�� ������ż��������ڵ��� �̿����� ���������� ����ϴ� ��쿡�� ����������޹�ħ�� ���Ͽ� �̸� �̿��ڰ� �������� <br>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;���� Ȯ���� �� �ֵ��� ������źη��� ���ϴ� ����� ���� �����Ͽ��� �Ѵ�.[�����ż� 2007.1.26]<br>
						<br>
						&nbsp;&nbsp;&nbsp;<span class="font_orange"><b>- ��67�� (���·�)</b></span><br>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;�� ���� �� ȣ�� ��� �ϳ��� �ش��ϴ� �ڴ� 1õ���� ������ ���·ῡ ó�Ѵ�.<���� 2007.1.26> 8��3. ��27����2��1��<br>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(��58���� ������ ���� �ؿ�Ǵ� ��츦 �����Ѵ�)�� ������ �����Ͽ� ����������޹�ħ�� �������� �ƴ��� ��<br>
						<br>
						<br>
						<span class="font_black">2. ������Ÿ� �̿����� �� ������ȣ � ���� ���� �����Ģ</span><br>
						<br>
						&nbsp;&nbsp;&nbsp;<span class="font_orange"><b>- ��3����2 (����������޹�ħ�� ���� ��� ��)</b></span><br>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;�� �� ��27����2��1�׿� ���� ������ż��������ڵ��� ���������� ���� ��ҿ� ��ü ���� ����Ͽ� ���� �� ȣ �� <br>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;��� �ϳ� �̻��� ������� ����������޹�ħ�� �����ϵ�, �� ��Ī�� '����������޹�ħ'�̶�� ǥ���Ͽ��� �Ѵ�.<br>
						<br>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;�� ������ż��������ڵ��� ��1����1ȣ�� ���� ����������޹�ħ�� �����ϴ� ��쿡�� �̿��ڰ� ���ͳ��� ���Ͽ� <br>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;����������޹�ħ�� �ֿ� ������ �������� ���� Ȯ���� �� �ֵ��� �ϱ� ���Ͽ� ������ź������ ���Ͽ� ����ϴ� ����� <br>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;���� ������ ǥ�õ� �Բ� �Ͽ��� �Ѵ�.[�����ż� 2007.7.27]<br><br>
						</td>
					</tr>
					</table>
					</TD>
					<TD background="images/manual_right1.gif"><IMG SRC="images/manual_right1.gif" WIDTH=18 HEIGHT="2" ALT=""></TD>
				</TR>
				<TR>
					<TD><IMG SRC="images/manual_left2.gif" WIDTH=15 HEIGHT=8 ALT=""></TD>
					<TD COLSPAN=3 background="images/manual_down.gif"><IMG SRC="images/manual_down.gif" WIDTH="4" HEIGHT=8 ALT=""></TD>
					<TD><IMG SRC="images/manual_right2.gif" WIDTH=18 HEIGHT=8 ALT=""></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr>
				<td height="50"></td>
			</tr>
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
<script language="javascript1.2">
//editor_generate('up_privercy');
//editor_generate('up_privercy2');
</script>

<?=$onload?>

<? INCLUDE "copyright.php"; ?>