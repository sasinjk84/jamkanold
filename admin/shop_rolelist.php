<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
INCLUDE ("access.php");

####################### ������ ���ٱ��� check ###############
$PageCode = "sh-4";
$MenuCode = "shop";
if (!$_usersession->isAllowedTask($PageCode)) {
	INCLUDE ("AccessDeny.inc.php");
	exit;
}
#########################################################

$type=$_POST["type"];
$mode=$_POST["mode"];
$roleidx=$_POST["roleidx"];
$description=$_POST["description"];
$taskidxs=$_POST["taskidxs"];
$disabled=(int)$_POST["disabled"];

if ($mode=="edit") {
	$sql = "SELECT description, disabled FROM tblsecurityrole ";
	$sql.= "WHERE idx = '".$roleidx."'";
	$result = mysql_query($sql,get_db_conn());
	$rows = mysql_num_rows($result);
	if ($rows > 0) {
		$row = mysql_fetch_object($result);
		$description = $row->description;
		$disabled = (int)$row->disabled;
	} else {
		echo "<script>alert('������ ���ѱ׷� ������ �������� �ʽ��ϴ�.'); location='".$_SERVER[PHP_SELF]."';</script>";
		exit;
	}
	mysql_free_result($result);

	${"check_".$disabled} = "checked";

	$sql = "SELECT taskidx FROM tblsecurityroletask WHERE roleidx = '".$roleidx."'";
	$result = mysql_query($sql,get_db_conn());
	$taskarray = array();
	while($row = mysql_fetch_object($result))
		$taskarray[$row->taskidx] = true;
	mysql_free_result($result);


	if($description == "Administrator") {
		$disabled_disabled = "disabled";
	}
} else if ($mode=="del") {
	$sql = "SELECT description FROM tblsecurityrole WHERE idx = '".$roleidx."'";
	$result = mysql_query($sql,get_db_conn());
	$rows = mysql_num_rows($result);
	if ($rows > 0) {
		$row = mysql_fetch_object($result);
		$description = $row->description;
	} else {
		echo "<script>alert('������ ���ѱ׷� ������ �������� �ʽ��ϴ�.'); location='".$_SERVER[PHP_SELF]."';</script>";
		exit;
	}
	mysql_free_result($result);

	//tblsecurityadminrole ���̺� �ش� roleidx�� �ִ��� �˻��Ѵ�.
	$sql = "SELECT idx FROM tblsecurityadminrole WHERE roleidx = '".$roleidx."' ";
	$result = mysql_query($sql,get_db_conn());
	$flag = (boolean)mysql_num_rows($result);
	mysql_free_result($result);

	if ($flag) {
		echo "<script>alert('�����Ϸ��� ���ٱ��� �׷��� ���� ������Դϴ�.\\n\\n���/�ο�� �������� �ش� ���ѱ׷��� ������� ��� ������ �����Ͻ� �� �����Ͻñ� �ٶ��ϴ�.');location='".$_SERVER[PHP_SELF]."';</script>";
		exit;
	}

	//���ٸ� tblsecurityrole ���̺� �ش� idx ���ڵ� ����
	$sql = "DELETE FROM tblsecurityrole WHERE idx = '".$roleidx."' ";
	mysql_query($sql,get_db_conn());

	//tblsecurityroletask ���̺� roleidx�� �ش��ϴ� ���ڵ� ����
	$sql = "DELETE FROM tblsecurityroletask WHERE roleidx = '".$roleidx."' ";
	mysql_query($sql,get_db_conn());

	echo "<script>alert('�ش� ���ѱ׷� (".$description.")�� �����Ͽ����ϴ�.'); location='".$_SERVER[PHP_SELF]."';</script>";
	exit;
}

if ($type=="insert") {
	$sql = "SELECT description FROM tblsecurityrole WHERE description = '".$description."' ";
	$result = mysql_query($sql,get_db_conn());
	$rows = mysql_num_rows($result);
	mysql_free_result($result);
	if ($rows>0) {
		echo "<script>alert('�Է��Ͻ� ���� �׷���� ���� ������Դϴ�.'); location='".$_SERVER[PHP_SELF]."';</script>";
		exit;
	}

	$sql = "INSERT INTO tblsecurityrole (description,disabled) VALUES ('".$description."','".$disabled."')";
	$insert = mysql_query($sql,get_db_conn());

	if ($insert) {
		$qry = "SELECT LAST_INSERT_ID() ";
		$res = mysql_fetch_row(mysql_query($qry,get_db_conn()));
		$roleidx = $res[0];

		for($i = 0; $i < count($taskidxs); $i++) {
			$taskidx = $taskidxs[$i];

			if ($taskidxs[$i] != "") {
				$sql = "INSERT INTO tblsecurityroletask (roleidx,taskidx) VALUES ('".$roleidx."','".$taskidx."')";
				$insert = mysql_query($sql,get_db_conn());
				if ($insert) {
					if ($taskidx == 0) {
						break;
					}
				}
			}
		}
	}

	echo "<script>alert('���ٱ��� �׷� �߰��� �Ϸ�Ǿ����ϴ�.'); location='".$_SERVER[PHP_SELF]."';</script>";
	exit;
} else if ($type=="edit") {
	$taskarray = array();
	$allowalltask = false;
	for($i = 0; $i < count($taskidxs); $i++) {
		if ($taskidxs[$i] != "") {
			$taskidx = $taskidxs[$i];
			if($taskidx == 0)
				$allowalltask = true;
			if($taskidx > 0)
				$taskarray[$taskidx] = true;
		}
	}

	$sql = "SELECT description FROM tblsecurityrole ";
	$sql.= "WHERE idx != ".$roleidx." AND description = '".$description."'";
	$result = mysql_query($sql,get_db_conn());
	$rows = mysql_num_rows($result);
	mysql_free_result($result);
	if ($rows>0) {
		echo "<script>alert('�Է��Ͻ� ���� �׷���� ���� ������Դϴ�.'); location='".$_SERVER[PHP_SELF]."';</script>";
		exit;
	}

	$sql = "DELETE FROM tblsecurityroletask WHERE roleidx = '".$roleidx."'";
	$delete = mysql_query($sql,get_db_conn());

	if ($delete) {
		$sql = "UPDATE tblsecurityrole SET description='".$description."', disabled=".$disabled." ";
		$sql.= "WHERE idx = '".$roleidx."'";
		$update = mysql_query($sql,get_db_conn());
		if($allowalltask) {
			$sql = "INSERT INTO tblsecurityroletask (roleidx,taskidx) VALUES ('".$roleidx."',0)";
			mysql_query($sql,get_db_conn());
		} else {
			foreach( $taskarray as $k1=>$v1 ) {
				$taskidx = $k1;
				if($taskidx > 0) {
					$sql = "INSERT INTO tblsecurityroletask (roleidx,taskidx) VALUES ('".$roleidx."','".$taskidx."')";
					mysql_query($sql,get_db_conn());
				}
			}
		}
	}

	echo "<script>alert('���ٱ��� �׷� ������ �Ϸ�Ǿ����ϴ�.'); location='".$_SERVER[PHP_SELF]."';</script>";
	exit;
}

$mode = $mode ? $mode : "insert";
if ($mode=="edit") {
	if ($disabled_disabled) {
		$button_value = "images/btn_edit1.gif";
	} else {
		$button_value = "images/btn_edit2.gif";
	}
} else if ($mode=="insert") {
	if ($disabled_disabled) {
		$button_value = "images/btn_badd1.gif";
	} else {
		$button_value = "images/btn_badd2.gif";
	}
}
?>

<? INCLUDE ("header.php"); ?>

<script type="text/javascript" src="lib.js.php"></script>
<SCRIPT LANGUAGE="JavaScript">
<!--
function CheckForm(type) {
	if(form1.description.value.length<=0) {
		alert("���ٱ��� �׷���� �Է��ϼ���.");
		form1.description.focus();
		return;
	}

	var moreselect = true;
	var anyselected = false;
	if (form1["taskidxs[]"].options[0].selected) {
		moreselect = false;
		anyselected = true;
	}
	for(var i=1;i<form1["taskidxs[]"].length;i++){
		if ((form1["taskidxs[]"].options[i].selected) && !moreselect ) {
			alert("������ [ All ] ���ýÿ��� �ٸ� �޴��� ������ �ʿ䰡 �����ϴ�.");
			return;
		} else {
			if(form1["taskidxs[]"].options[i].selected && form1["taskidxs[]"].options[i].value) {
				anyselected = true;
			}
		}
	}
	if (!anyselected ) {
		alert("���ٰ����� �Ҹ޴��� �ϳ��̻� �����ϼž� �մϴ�.");
		form1["taskidxs[]"].focus();
		return;
	}

	var ra = false;
	for(var i=0;i<form1.disabled.length;i++){
		if(form1.disabled[i].checked==true){
			ra=true;
			break;
		}
	}
	if(!ra){
		alert("��뿩�θ� �����ϼ���.");
		form1.disabled[0].focus();
		return;
	}
	form1.type.value=type;
	form1.submit();
}

function check_form(mode,roleidx) {
	if (mode=="del") {
		var con=confirm("�ش� ���ٱ��� �׷��� ���� �Ͻðڽ��ϱ�?");
		if (!con) {
			return false;
		}
	}
	form2.mode.value=mode;
	form2.roleidx.value=roleidx;
	form2.submit();
}
//-->
</SCRIPT>
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
				<td height="28" class="link" align="left" background="images/con_link_bg.gif"><img src="images/top_link_house.gif" border="0" valign="absmiddle">������ġ : �������� &gt; ���ȼ��� &gt; <span class="2depth_select">�׷� �� ���� ����</span></td>
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
			<tr>
				<td height="8"></td>
			</tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/shop_rolelist_title.gif" ALT=""></TD>
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
					<TD width="100%" class="notice_blue"><p>���������� �޴��� ���ٱ��� �׷��� �����մϴ�.</p></TD>
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
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/shop_rolelist_stitle1.gif" WIDTH="152" HEIGHT=31 ALT=""></TD>
					<TD width="100%" background="images/shop_basicinfo_stitle_bg.gif">&nbsp;</TD>
					<TD><IMG SRC="images/shop_basicinfo_stitle_end.gif" WIDTH=10 HEIGHT=31 ALT=""></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr>
				<td height=3></td>
			</tr>
			<form name=form1 action="<?=$_SERVER[PHP_SELF]?>" method=post>
			<input type=hidden name=type>
			<input type=hidden name=roleidx value="<?=$roleidx?>">
			<tr>
				<td>
				<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
				<TR>
					<TD colspan=5 background="images/table_top_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell" width="139" align="center">���ٱ��� �׷��</TD>
					<TD class="table_cell1" width="72" align="center">��뿩��</TD>
					<TD class="table_cell1" width="405" align="center">�׷쿡 ���� ���/�ο��</TD>
					<TD class="table_cell1" width="60" align="center">����</TD>
					<TD class="table_cell1" width="60" align="center">����</TD>
				</TR>
				<TR>
					<TD colspan="5" background="images/table_con_line.gif"></TD>
				</TR>
<?
			$count = 0;
			$sql = "SELECT idx as roleidx, description, disabled ";
			$sql.= "FROM tblsecurityrole ORDER BY idx DESC ";
			$result = mysql_query($sql,get_db_conn());
			while($row=mysql_fetch_object($result)) {
				$count++;

				echo "<tr>\n";
				echo "	<TD width=\"146\" class=\"td_con2\" style=\"padding-left:5px\"><span class=font_orange>".$count." &nbsp;-&nbsp; <B>".$row->description."</B></span></td>\n";
				if ($row->disabled == 0) {
					echo "	<TD class=\"td_con1\" width=\"72\" align=\"center\">�����</td>\n";
				} else {
					echo "	<TD class=\"td_con1\" width=\"72\" align=\"center\">������</td>\n";
				}

				$allowedadmins = "";
				$sql = "SELECT a.id FROM tblsecurityadmin a, tblsecurityadminrole r ";
				$sql.= "WHERE a.id = r.id AND r.roleidx = ".$row->roleidx."";
				$result2 = mysql_query($sql,get_db_conn());
				while($row2 = mysql_fetch_object($result2)) {
					$aname = $row2->id;
					if($aname)
						$allowedadmins .= "" . $aname . ", ";
				}
				mysql_free_result($result2);

				if ($allowedadmins) {
					$allowedadmins = substr($allowedadmins,0,(strlen($allowedadmins)-2));
					echo "	<TD class=\"td_con1\" width=\"412\" align=\"center\">".$allowedadmins."</td>\n";
				} else {
					echo "	<TD class=\"td_con1\" width=\"412\" align=\"center\">�ش� �׷쿡 ���Ե� �ο�ڰ� �����ϴ�.</td>\n";
				}
				echo "	<TD align=center class=\"td_con1\" width=\"60\"><a href=\"javascript:check_form('edit','".$row->roleidx."');\"><img src=\"images/btn_edit.gif\" width=\"50\" height=\"22\" border=\"0\"></a></td>\n";
				if ($row->description == "Administrator") {
					echo "	<TD align=center class=\"td_con1\" width=\"60\"><img src=\"images/btn_del1.gif\" width=\"50\" height=\"22\" border=\"0\"></td>\n";
				} else {
					echo "	<TD align=center class=\"td_con1\" width=\"60\"><a href=\"javascript:check_form('del','".$row->roleidx."');\"><img src=\"images/btn_del.gif\" width=\"50\" height=\"22\" border=\"0\"></a></td>\n";
				}
				echo "</tr>\n";
				echo "<tr>\n";
				echo "	<TD colspan=\"5\" background=\"images/table_con_line.gif\"></TD>";
				echo "</tr>\n";
			}
			if ($count == 0) {
				echo "<tr>\n";
				echo "	<TD class=\"td_con1\" align=\"center\" colspan=\"5\">��ϵ� ���� �׷� ������ �����ϴ�.</td>\n";
				echo "</tr>\n";
				echo "<tr>\n";
				echo "	<TD colspan=\"5\" background=\"images/table_con_line.gif\"></TD>";
				echo "</tr>\n";
			}
?>
				<TR>
					<TD colspan=5 background="images/table_top_line.gif"></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr>
				<td></td>
			</tr>
			<tr>
				<td height="30"></td>
			</tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/shop_rolelist_stitle2.gif" WIDTH="152" HEIGHT=31 ALT=""></TD>
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
					<TD width="100%" class="notice_blue">1) �ش� �׷��� <b>���� ������ �޴��� ����</b>�Ͻʽÿ�. <br>2) ������������ �������� �׷��� �����Ͻ÷���<b> &quot;All&quot;</b>�� �����Ͻʽÿ�.</TD>
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
				<td height=3></td>
			</tr>
			<tr>
				<td>
				<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
				<TR>
					<TD colspan=2 background="images/table_top_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell" width="139"><img src="images/icon_point2.gif" width="8" height="11" border="0">���ٱ��� �׷��</TD>
					<TD class="td_con1"><input type=text name=description value="<?=$description?>" size=25 <?=$disabled_disabled?> class="input"></TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell" width="139"><img src="images/icon_point2.gif" width="8" height="11" border="0">���� ���ɸ޴� ����</TD>
					<TD class="td_con1" width="600">
<?
				if ($mode=="edit") {
					echo "<select name=\"taskidxs[]\" size='20' style=\"WIDTH:100%;\" class=\"textarea\" multiple ";
					if($description == "Administrator")
						echo " disabled";
?>
						>
<?
					if($taskarray[0]) {
						echo "<option value='0' selected>All</option>";
						$all = true;
					} else {
						echo "<option value='0' >All</option>";
						$all = false;
					}

					$sql = "SELECT b.idx as taskidx, b.description, b.taskcode, a.taskgroupcode as taskcat ";
					$sql.= "FROM tblsecuritytaskgroup a, tblsecuritytask b WHERE a.idx = b.taskgroupidx ORDER BY b.taskgroupidx,b.taskorder ASC";
					$result = mysql_query($sql,get_db_conn());
					$temptaskidx = 0;
					$ltaskgroup = "";
					$taskgroup = "";
					$count = 0;
					while($row = mysql_fetch_object($result)) {
						$count++;
						$taskidx = $row->taskidx;
						$tdescription = $row->description;
						$taskcode = $row->taskcode;

						if(!$all) {
							foreach( $taskarray as $k1=>$v1 ) {
								$temptaskidx = $k1;
								if($taskidx == $temptaskidx) {
									$selected = true;
								}
							}
						}
						$taskgroup = $row->taskcat;

						if($count == 1)
							$ltaskgroup = $taskgroup;
						if($count == 1 || $ltaskgroup != $taskgroup)
						if($count == 1)
							$ltaskgroup = $taskgroup;
						if($count == 1 || $ltaskgroup != $taskgroup) {
							$sql2 = "SELECT * FROM tblsecuritytaskgroup WHERE taskgroupcode = '".$taskgroup."' ";
							$result2 = mysql_query($sql2,get_db_conn());
							$row2 = mysql_fetch_object($result2);
							mysql_free_result($result2);
							if($row2->taskgroupcode == $taskgroup) {
								echo chr(10).("<option value='' disabled style=\"background:#FF0000;color:#ffffff;\">-------------------[".$row2->taskgroupname."]-------------------</option>");
							} else {
								continue;
							}
						}

						$ltaskgroup = $taskgroup;
						if($selected)
							echo chr(10).("<option value='".$taskidx."' selected >".$tdescription."</option>");
						else
							echo chr(10).("<option value='".$taskidx."' >".$tdescription."</option>");

						$selected = false;
					}

				} else {
?>
					<select name="taskidxs[]" size='20' style="WIDTH:100%;" class="textarea" multiple>
					<option value='0'>All</option>
<?
					$sql = "SELECT b.idx as taskidx, b.description, b.taskcode, a.taskgroupcode as taskcat ";
					$sql.= "FROM tblsecuritytaskgroup a, tblsecuritytask b WHERE a.idx = b.taskgroupidx ORDER BY b.taskgroupidx,b.taskorder ASC";
					$result = mysql_query($sql,get_db_conn());
					$temptaskidx = 0;
					$ltaskgroup = "";
					$taskgroup = "";
					$count = 0;
					while($row = mysql_fetch_object($result)) {
						$count++;
						$taskidx = $row->taskidx;
						$tdescription = $row->description;
						$taskcode = $row->taskcode;

						$taskgroup = $row->taskcat;

						if($count == 1)
							$ltaskgroup = $taskgroup;
						if($count == 1 || $ltaskgroup != $taskgroup)
						if($count == 1)
							$ltaskgroup = $taskgroup;
						if($count == 1 || $ltaskgroup != $taskgroup) {
							$sql2 = "SELECT * FROM tblsecuritytaskgroup WHERE taskgroupcode = '".$taskgroup."' ";
							$result2 = mysql_query($sql2,get_db_conn());
							$row2 = mysql_fetch_object($result2);
							mysql_free_result($result2);
							if($row2->taskgroupcode == $taskgroup) {
								echo chr(10).("<option value='' disabled style=\"background:#FF0000;color:#ffffff;\">-------------------[".$row2->taskgroupname."]-------------------</option>");
							} else {
								continue;
							}
						}

						$ltaskgroup = $taskgroup;
						echo chr(10).("<option value='".$taskidx."' >".$tdescription."</option>");

						$selected = false;
					}
				}
?>
				</select>
					</TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell" width="139"><img src="images/icon_point2.gif" width="8" height="11" border="0">��뿩��</TD>
					<TD class="td_con1">
						<input type=radio id="idx_disabled1" name=disabled value="0" <?=$check_0?> <?=$disabled_disabled?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_disabled1>�����</label>&nbsp;&nbsp;
						<input type=radio id="idx_disabled2" name=disabled value="1" <?=$check_1?> <?=$disabled_disabled?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_disabled2>������� ����</label>
					</TD>
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
<?
	if (!$disabled_disabled) {
?>
	<tr>
		<td align="center"><a href="javascript:CheckForm('<?=$mode?>');"><img src="<?=$button_value?>" width="113" height="38" border="0"></a></td>
	</tr>
<?
	} else {
?>
	<tr>
		<td align="center"><img src="<?=$button_value?>" width="113" height="38" border="0"></td>
	</tr>
<?
	}
?>
			</form>
			<form name=form2 method=post action="<?=$_SERVER[PHP_SELF]?>">
			<input type=hidden name=mode>
			<input type=hidden name=roleidx>
			</form>
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
	</td>
</tr>
</table>
<?=$onload?>

<? INCLUDE ("copyright.php"); ?>