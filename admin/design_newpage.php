<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
INCLUDE ("access.php");

####################### ������ ���ٱ��� check ###############
$PageCode = "de-6";
$MenuCode = "design";
if (!$_usersession->isAllowedTask($PageCode)) {
	INCLUDE ("AccessDeny.inc.php");
	exit;
}
#########################################################

$maxpage=50;

$type=$_POST["type"];
$code=$_POST["code"];
$subject=$_POST["subject"];
$menu_type=$_POST["menu_type"];
$menu_code=$_POST["menu_code"];
$member_type=$_POST["member_type"];
$group_code=$_POST["group_code"];
$new_body=$_POST["new_body"];

if(strlen($menu_type)==0) $menu_type="Y";
if(strlen($member_type)==0) $member_type="Y";



// ��� / ����
if ( $type=="store" OR $type=="restore" ) {

	$leftmenu=$menu_type;
	$filename=$member_type;
	if($member_type=="G") {
		$filename=$group_code;
	}
	if($menu_type=="Y") {
		$filename.="".$menu_code;
	}

	$MSG = adminDesingBackup ( $type, 'newpage', $new_body, $subject, $code, $filename, $leftmenu );
	$onload="<script>alert(\"".$MSG."\");</script>";
}


if($type=="delete" && strlen($code)>0) {
	$sql = "DELETE FROM tbldesignnewpage WHERE type='newpage' AND code='".$code."' ";
	mysql_query($sql,get_db_conn());
	$onload="<script>alert(\"���������� ������ �Ϸ�Ǿ����ϴ�.\");</script>";
	$subject="";
	$menu_type="Y";
	$menu_code="";
	$member_type="Y";
	$group_code="";
	$new_body="";
} else if($type=="update" && strlen($new_body)>0) {
	$leftmenu=$menu_type;
	$filename=$member_type;
	if($member_type=="G") {
		$filename=$group_code;
	}
	if($menu_type=="Y") {
		$filename.="".$menu_code;
	}
	if(strlen($code)==0) {
		$sql = "SELECT COUNT(*) as cnt FROM tbldesignnewpage WHERE type='newpage' ";
		$result=mysql_query($sql,get_db_conn());
		$row=mysql_fetch_object($result);
		$cnt=(int)$row->cnt;
		mysql_free_result($result);
		if($cnt==$maxpage) {
			$onload="<script>alert(\"������������ �ִ� ".$maxpage."���������� �����˴ϴ�.\\n\\n�ٸ� �������� ���� �� ����Ͻñ� �ٶ��ϴ�.\");</script>";
		} else {
			$sql = "SELECT MAX(code*1) as maxcode FROM tbldesignnewpage ";
			$sql.= "WHERE type='newpage' ";
			$result=mysql_query($sql,get_db_conn());
			$row=mysql_fetch_object($result);
			$code=(int)$row->maxcode+1;
			mysql_free_result($result);
			$sql = "INSERT tbldesignnewpage SET ";
			$sql.= "type			= 'newpage', ";
			$sql.= "subject			= '".$subject."', ";
			$sql.= "filename		= '".$filename."', ";
			$sql.= "leftmenu		= '".$leftmenu."', ";
			$sql.= "body			= '".$new_body."', ";
			$sql.= "code			= '".$code."' ";
			mysql_query($sql,get_db_conn());
			$onload="<script>alert(\"���������� ����� �Ϸ�Ǿ����ϴ�.\");</script>";
		}
	} else {
		$sql = "UPDATE tbldesignnewpage SET ";
		$sql.= "subject			= '".$subject."', ";
		$sql.= "filename		= '".$filename."', ";
		$sql.= "leftmenu		= '".$leftmenu."', ";
		$sql.= "body			= '".$new_body."' ";
		$sql.= "WHERE type='newpage' AND code='".$code."' ";
		mysql_query($sql,get_db_conn());
		$onload="<script>alert(\"���������� ������ �Ϸ�Ǿ����ϴ�.\");</script>";
	}
}

if(strlen($code)>0) {
	$sql = "SELECT * FROM tbldesignnewpage WHERE type='newpage' AND code='".$code."' ";
	$result=mysql_query($sql,get_db_conn());
	if($row=mysql_fetch_object($result)) {
		$subject=$row->subject;
		$menu_type=$row->leftmenu;
		$filename=explode("",$row->filename);
		$member_type=$filename[0];
		$menu_code=$filename[1];
		$new_body=$row->body;
		if(strlen($member_type)>1) {
			$group_code=$member_type;
			$member_type="G";
		}
	}
	mysql_free_result($result);
} else {
	$subject="";
	$menu_type="Y";
	$menu_code="";
	$member_type="Y";
	$group_code="";
	$new_body="";
}
?>

<? INCLUDE "header.php"; ?>

<script type="text/javascript" src="lib.js.php"></script>
<script language="JavaScript">
function CheckForm(type) {
	if(type=="delete") {
		if(document.form1.code.value.length==0) {
			alert("������ �������� �����ϼ���.");
			document.form1.code.focus();
			return;
		} else {
			if(confirm("�ش� �������� �����Ͻðڽ��ϱ�?")) {
				document.form1.type.value=type;
				document.form1.submit();
			}
		}
	} else if(type=="update") {
		if(document.form1.subject.value.length==0) {
			alert("HTML �������� �Է��ϼ���.");
			document.form1.subject.focus();
			return;
		}
		member_type="";
		for(i=0;i<document.form1.member_type.length;i++) {
			if(document.form1.member_type[i].checked==true) {
				member_type=document.form1.member_type[i].value;
				break;
			}
		}
		if(member_type=="G") {
			if(document.form1.group_code.value=="") {
				alert("�ش� ����� �����ϼ���.");
				document.form1.group_code.focus();
				return;
			}
		}
		if(document.form1.new_body.value.length==0) {
			alert("������ ������ �Է��ϼ���.");
			document.form1.new_body.focus();
			return;
		}
		document.form1.type.value=type;
		document.form1.submit();
	}


	// ���
	if(type=="store") {

		if(document.form1.subject.value.length==0) {
			alert("HTML �������� �Է��ϼ���.");
			document.form1.subject.focus();
			return;
		}
		member_type="";
		for(i=0;i<document.form1.member_type.length;i++) {
			if(document.form1.member_type[i].checked==true) {
				member_type=document.form1.member_type[i].value;
				break;
			}
		}
		if(member_type=="G") {
			if(document.form1.group_code.value=="") {
				alert("�ش� ����� �����ϼ���.");
				document.form1.group_code.focus();
				return;
			}
		}
		if(document.form1.new_body.value.length==0) {
			alert("������ ������ �Է��ϼ���.");
			document.form1.new_body.focus();
			return;
		}

		if(confirm("<?=$subject?> �������� ����Ͻðڽ��ϱ�?\n\n�������� �����̴ٸ� \"�����ϱ�\"�� ���� �Ͻ��� ����Ͻñ� �ٶ��ϴ�.\n���� ����� ����ҽ��� ��ü�մϴ�.")) {
			document.form1.type.value=type;
			document.form1.submit();
			return;
		}
	}
	// ����
	if(type=="restore") {
		if(confirm("<?=$subject?> �������� ������� �Ͻðڽ��ϱ�?\n\n���� �ϰ� �Ǹ� �ٷ� ������ ���� �˴ϴ�.")) {
			document.form1.type.value=type;
			document.form1.submit();
			return;
		}
	}

}

function change_page(val) {
	document.form1.type.value="change";
	document.form1.submit();
}

function check_menutype(val) {
	if(val=="Y") {
		document.form1.menu_code.disabled=false;
	} else {
		document.form1.menu_code.disabled=true;
	}
}
function check_membertype(val) {
	if(val=="G") {
		document.form1.group_code.disabled=false;
	} else {
		document.form1.group_code.disabled=true;
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
				<td height="28" class="link" align="left" background="images/con_link_bg.gif"><img src="images/top_link_house.gif" border="0" valign="absmiddle">������ġ : �������� &gt; ���ȼ��� &gt; <span class="2depth_select">���/�ο�� ����</span></td>
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
					<TD><IMG SRC="images/design_newpage_title.gif" ALT=""></TD>
					</tr>
<tr>
<TD width="100%" background="images/title_bg.gif" height="21"></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr>
				<td height="3"></td>
			</tr>
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
					<TD width="100%" class="notice_blue"><p>���� �Ϲ��������� ��� �� �����Ͻ� �� �ֽ��ϴ�.</p></TD>
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
					<TD><IMG SRC="images/design_newpage_stitle1.gif" WIDTH="190" HEIGHT=31 ALT=""></TD>
					<TD width="100%" background="images/shop_basicinfo_stitle_bg.gif"></TD>
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
					<TD class="notice_blue"><IMG SRC="images/distribute_img.gif" ></TD>
					<TD width="100%" class="notice_blue">1) HTML �Է��� �����ϹǷ� ���Ͻô� ���������� �ۼ��Ͽ� ����Ͻø� �˴ϴ�.(HTML�� ����, �κ�HTML, TEXT ���� �ȵ�)<br>
					2) �ִ� 50���������� �����˴ϴ�.<br>
					3) ���ʸ޴��� ���� �������� ��� [���������� ���뼱��]�� �ؾ� �մϴ�.(���ø� ���ÿ���  ���ø��� ���������� ���)<br>
					<b>&nbsp;&nbsp;&nbsp;</b><a href="javascript:parent.topframe.GoMenu(2,'design_option.php');"><span class="font_blue">�����ΰ��� > ��FTP �� �������� ���� > ���������� ���뼱��</span></a> [���+���� ���� ����][���ʸ� ����]<br>
					4) ������ ���� �޴�<br>
					&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:parent.topframe.GoMenu(1,'shop_displaytype.php');"><span class="font_blue">�������� > ���θ� ȯ�� ���� > ������/���� ����</span></a>
					</p></TD>
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
				<td style="padding-top:3pt;">
				<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
				<TR>
					<TD colspan=2 background="images/table_top_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell" width="139"><img src="images/icon_point2.gif" width="8" height="11" border="0">������ ����</TD>
					<TD class="td_con1" ><select name=code onchange="change_page(options.value)" style="width:330" class="select">
						<option value="">���ο� ������ ����</option>
<?
			unset($arr_newpage);
			$sql = "SELECT subject,code FROM tbldesignnewpage WHERE type='newpage' ";
			$result=mysql_query($sql,get_db_conn());
			while($row=mysql_fetch_object($result)) {
				echo "<option value=\"".$row->code."\" ";
				if($code==$row->code) echo "selected";
				echo ">code : ".$row->code." - ".$row->subject."</option>\n";
				$arr_newpage[]=$row;
			}
			mysql_fetch_object($result);
?>
					</select></TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell" width="139"><img src="images/icon_point2.gif" width="8" height="11" border="0">HTML ������</TD>
					<TD class="td_con1" >
					<table cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td width="90%"><input type=text name=subject value="<?=$subject?>"  style="WIDTH:100%" class="input"></td>
						<td width="10%"><p align="center"><?if(strlen($code)>0) echo "�ڵ� : ".$code;?></p></td>
					</tr>
					</table>
					</TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell" width="139"><img src="images/icon_point2.gif" width="8" height="11" border="0">���� ����</TD>
					<TD class="td_con1" ><input type=radio id="idx_menutype0" name=menu_type value="Y" <?if($menu_type=="Y")echo"checked";?> style="BORDER-RIGHT: medium none; BORDER-TOP: medium none; BORDER-LEFT: medium none; BORDER-BOTTOM: medium none;" onclick="check_menutype(this.value)"> <label style='cursor:hand; TEXT-DECORATION: none;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_menutype0>���/���� �޴� ������</label>(�� �������� ��� �޴��� ��µ��� ����)<br>
						<input type=radio id="idx_menutype1" name=menu_type value="T" <?if($menu_type=="T")echo"checked";?> style="BORDER-RIGHT: medium none; BORDER-TOP: medium none; BORDER-LEFT: medium none; BORDER-BOTTOM: medium none;" onclick="check_menutype(this.value)"> <label style='cursor:hand; TEXT-DECORATION: none;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_menutype1>��� �޴��� ���</label>(�� �������� ��/�ϴ� �޴� ��� ��µ��� ����)<br>
						<input type=radio id="idx_menutype2" name=menu_type value="N" <?if($menu_type=="N")echo"checked";?> style="BORDER-RIGHT: medium none; BORDER-TOP: medium none; BORDER-LEFT: medium none; BORDER-BOTTOM: medium none;" onclick="check_menutype(this.value)"> <label style='cursor:hand; TEXT-DECORATION: none;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_menutype2>���/���� �޴� ��� �����</label>(��������/�������� ��� ����)</TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell" width="139"><img src="images/icon_point2.gif" width="8" height="11" border="0">���ʸ޴� ����</TD>
					<TD class="td_con1" ><select name="menu_code" style="width:150px" class="select">
<?
			$page_list=array("���� ������ ���ʸ޴�","�Խ��� ���� ���ʸ޴�","ȸ�� ���� ���ʸ޴�","���������� ���� ���ʸ޴�","�ֹ��� ���� ���ʸ޴�","�˻� ���� ���ʸ޴�","���� ������ ���ʸ޴�1","���� ������ ���ʸ޴�2","���� ������ ���ʸ޴�3","���� ������ ���ʸ޴�4","���� ������ ���ʸ޴�5","���� ������ ���ʸ޴�6","���� ������ ���ʸ޴�7","���� ������ ���ʸ޴�8","���� ������ ���ʸ޴�9","���� ������ ���ʸ޴�10");
			$page_code=array("MAI","BOA","MEM","MYP","ORD","SEA","NE0","NE1","NE2","NE3","NE4","NE5","NE6","NE7","NE8","NE9");

			for($i=0;$i<count($page_list);$i++) {
				echo "<option value=\"".$page_code[$i]."\" ";
				if($menu_code==$page_code[$i]) echo "selected";
				echo ">".$page_list[$i]."</option>\n";
			}
?>
					</select>&nbsp;&nbsp;&nbsp;&nbsp;<span class="font_orange">* ���/���� �޴� ��� ����� ��쿡�� ����˴ϴ�.</span><script>check_menutype("<?=$menu_type?>")</script></TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<tr>
					<TD class="table_cell" width="139"><img src="images/icon_point2.gif" width="8" height="11" border="0">ȸ���� ����</TD>
					<TD class="td_con1" ><input type=radio id="idx_membertype0" name=member_type value="Y" <?if($member_type=="Y")echo"checked";?> style="BORDER-RIGHT: medium none; BORDER-TOP: medium none; BORDER-LEFT: medium none; BORDER-BOTTOM: medium none;" onclick="check_membertype(this.value)"> <label style='cursor:hand; TEXT-DECORATION: none;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_membertype0>ȸ��</label>&nbsp;
						<input type=radio id="idx_membertype1" name=member_type value="N" <?if($member_type=="N")echo"checked";?> style="BORDER-RIGHT: medium none; BORDER-TOP: medium none; BORDER-LEFT: medium none; BORDER-BOTTOM: medium none;" onclick="check_membertype(this.value)"> <label style='cursor:hand; TEXT-DECORATION: none;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_membertype1>ȸ��+��ȸ��</label>&nbsp;
						<input type=radio id="idx_membertype2" name=member_type value="G" <?if($member_type=="G")echo"checked";?> style="BORDER-RIGHT: medium none; BORDER-TOP: medium none; BORDER-LEFT: medium none; BORDER-BOTTOM: medium none;" onclick="check_membertype(this.value)"> <label style='cursor:hand; TEXT-DECORATION: none;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_membertype2>Ư�� ȸ�����</label>Ư�� ȸ�����&nbsp;<select name=group_code style="width:250px" class="select">
						<option value="">�ش� ����� �����ϼ���</option>
<?
			$sql = "SELECT group_code,group_name FROM tblmembergroup ";
			$result=mysql_query($sql,get_db_conn());
			while($row=mysql_fetch_object($result)) {
				echo "<option value=\"".$row->group_code."\" ";
				if($group_code==$row->group_code) echo "selected";
				echo ">".$row->group_name."</option>\n";
			}
			mysql_free_result($result);
?>
					</select><script>check_membertype("<?=$member_type?>")</script></TD>
				</tr>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<TR>
					<TD colspan="2"><textarea name=new_body style="WIDTH: 100%; HEIGHT: 350px" class="textarea"><?=htmlspecialchars($new_body)?></textarea></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr><td height=10></td></tr>
			<tr>
				<td align="center"><a href="javascript:CheckForm('update');"><img src="images/botteon_save.gif" width="113" height="38" border="0"></a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:CheckForm('delete');"><img src="images/botteon_del.gif" width="113" height="38" border="0" hspace="2"></a><? if(strlen($code)>0) { ?>&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:CheckForm('store');"><img src="images/botteon_store.gif" border="0" hspace="2" alt="����ϱ�"></a>&nbsp;&nbsp;&nbsp;<a href="javascript:CheckForm('restore');"><img src="images/botteon_restore.gif" border="0" hspace="2" alt="��������ϱ�"></a><? } ?></td>
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
						<td><p class="LIPoint"><B>�� URL����Ʈ</B></p></td>
					</tr>
					<tr>
						<td width="20" align="right" valign="top"><p>&nbsp;</p></td>
						<td>
						<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
<?
		for($i=0;$i<count($arr_newpage);$i++) {
			if($i == count($arr_newpage)-1) {
?>
						<tr>
							<TD class="table_cell4" style="padding-right:15px; border-top-width:1pt; border-bottom-width:1pt; border-top-color:rgb(222,222,222); border-bottom-color:silver; border-top-style:solid; border-bottom-style:solid;" noWrap align=right width=150 bgColor=#f0f0f0 height="27"><?=$arr_newpage[$i]->subject?></td>
							<TD class="td_con1" style="padding-left:5px; border-top-width:1pt; border-bottom-width:1pt; border-top-color:rgb(222,222,222); border-bottom-color:silver; border-top-style:solid; border-bottom-style:solid;" width="100%">&lt;a href="/<?=RootPath.FrontDir?>newpage.php?code=<?=$arr_newpage[$i]->code?>"><?=$arr_newpage[$i]->subject?>&lt;/a></td>
						</tr>
<?
			} else {
?>
						<tr>
							<TD class="table_cell4" style="padding-right:15px; border-top-width:1pt; border-top-color:silver; border-top-style:solid;" noWrap align=right width=150 bgColor=#f0f0f0 height="27"><?=$arr_newpage[$i]->subject?></td>
							<TD class="td_con1" style="padding-left:5px; border-top-width:1pt; border-top-color:silver; border-top-style:solid;" width="100%">&lt;a href="<?=RootPath.FrontDir?>newpage.php?code=<?=$arr_newpage[$i]->code?>"><?=$arr_newpage[$i]->subject?>&lt;/a></td>
						</tr>
<?
			}
		}
		if(count($arr_newpage)==0) {
			echo "<tr><TD class=\"td_con1\" style=\"padding-left:5px; border-top-width:1pt; border-bottom-width:1pt; border-top-color:rgb(222,222,222); border-bottom-color:silver; border-top-style:solid; border-bottom-style:solid;\" width=\"100%\" colspan=\"2\"><B>��ϵ� ������������ �������� �ʽ��ϴ�.</B></td></tr>\n";
		}
?>
						</TABLE>
						</td>
					</tr>
					<tr>
						<td width="20" colspan="2"><p>&nbsp;</p></td>
					</tr>
					<tr>
						<td width="20" align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td><p class="LIPoint">����,�帲�������� �����ͷ� �ۼ��� �̹�����ε� �۾������� Ʋ���� �� ������ �����ϼ���!</p></td>
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