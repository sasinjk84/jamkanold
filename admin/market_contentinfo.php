<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
INCLUDE ("access.php");

####################### ������ ���ٱ��� check ###############
$PageCode = "ma-1";
$MenuCode = "market";
if (!$_usersession->isAllowedTask($PageCode)) {
	INCLUDE ("AccessDeny.inc.php");
	exit;
}
#########################################################

//����Ʈ ����
$setup[page_num] = 10;
$setup[list_num] = 20;

$block=$_REQUEST["block"];
$gotopage=$_REQUEST["gotopage"];

if ($block != "") {
	$nowblock = $block;
	$curpage  = $block * $setup[page_num] + $gotopage;
} else {
	$nowblock = 0;
}

if (($gotopage == "") || ($gotopage == 0)) {
	$gotopage = 1;
}

$imagepath=$Dir.DataDir."shopimages/etc/";

$type=$_POST["type"];
$date=$_POST["date"];
$old_image=$_POST["old_image"];
$up_subject=$_POST["up_subject"];
$up_content=$_POST["up_content"];
$up_image=$_FILES["up_image"];
$up_image_align=$_POST["up_image_align"];
$up_newdate=$_POST["up_newdate"];
$vdate = date("YmdHis");

if(strlen($up_subject)>0 && $type=="insert") {
	if (strlen($up_image["name"])>0 && (strtolower(substr($up_image["name"],strlen($up_image["name"])-3,3))=="gif" || strtolower(substr($up_image["name"],strlen($up_image["name"])-3,3))=="jpg") ) {
		if ($up_image["size"]<=153600) {
			$up_image["name"] = "cinfo".$up_image["name"];
			move_uploaded_file($up_image[tmp_name],$imagepath.$up_image["name"]);
			chmod($imagepath.$up_image["name"],0606);
		} else {
			$up_image["name"] = "";
		}
	}  else {
		$up_image["name"] = "";
	}
	$sql = "INSERT tblcontentinfo SET ";
	$sql.= "date		= '".$vdate."', ";
	$sql.= "subject		= '".$up_subject."', ";
	$sql.= "image_name	= '".$up_image["name"]."', ";
	$sql.= "image_align	= '".$up_image_align."', ";
	$sql.= "access		= 0, ";
	$sql.= "content		= '".$up_content."' ";
	mysql_query($sql,get_db_conn());
	$onload="<script>alert('����(information) ����� �Ϸ�Ǿ����ϴ�.');</script>\n";
} else if (strlen($date)>0 && $type=="modify") {
	if ($mode=="result") {
		if (strlen($up_image["name"])>0 && (strtolower(substr($up_image["name"],strlen($up_image["name"])-3,3))=="gif" || strtolower(substr($up_image["name"],strlen($up_image["name"])-3,3))=="jpg") ) {
			if ($up_image["size"]<=153600) {
				$up_image["name"] = "cinfo".$up_image["name"];
				if(strlen($old_image)>0 && file_exists($imagepath.$old_image)) unlink($imagepath.$old_image);
				move_uploaded_file($up_image[tmp_name],$imagepath.$up_image["name"]);
				chmod($imagepath.$up_image["name"],0606);
			} else {
				$up_image["name"] = $old_image;
			}
		} else {
			$up_image["name"] = $old_image;
		}
		$sql = "UPDATE tblcontentinfo SET ";
		$sql.= "image_name	= '".$up_image["name"]."', ";
		$sql.= "image_align	= '".$up_image_align."', ";
		$sql.= "subject		= '".$up_subject."', ";
		$sql.= "content		= '".$up_content."' ";
		if($up_newdate=="Y") $sql.= ", date = '".$vdate."' ";
		$sql.= "WHERE date = '".$date."' ";
		mysql_query($sql,get_db_conn());
		$onload="<script>alert('����(information) ������ �Ϸ�Ǿ����ϴ�.');</script>\n";
		unset($type);
		unset($mode);
		unset($date);
	} else {
		$sql = "SELECT * FROM tblcontentinfo WHERE date = '".$date."' ";
		$result = mysql_query($sql,get_db_conn());
		$row = mysql_fetch_object($result);
		mysql_free_result($result);
		if ($row) {
			$subject = ereg_replace("\"","&quot;",$row->subject);
			$content = ereg_replace("\"","&quot;",$row->content);
			$image_name = $row->image_name;
			$image_align = $row->image_align;
		} else {
			$onload="<script>alert('�����Ϸ��� ����(information)�� �������� �ʽ��ϴ�.');<script>";
			unset($type);
			unset($date);
		}
	}
} else if (strlen($date)>0 && $type=="delete") {
	$sql = "SELECT * FROM tblcontentinfo WHERE date = '".$date."' ";
	$result = mysql_query($sql,get_db_conn());
	$row = mysql_fetch_object($result);
	mysql_free_result($result);
	if(strlen($row->image_name)>0) {
		if(file_exists($imagepath.$row->image_name)) unlink($imagepath.$row->image_name);
	}
	$sql = "DELETE FROM tblcontentinfo WHERE date = '".$date."' ";
	mysql_query($sql,get_db_conn());
	$onload="<script> alert('����(information) ������ �Ϸ�Ǿ����ϴ�.');</script>\n";
	unset($type);
	unset($date);
} else if (strlen($date)>0 && $type=="imgdel") {
	$sql = "SELECT * FROM tblcontentinfo WHERE date = '".$date."' ";
	$result = mysql_query($sql,get_db_conn());
	$row = mysql_fetch_object($result);
	mysql_free_result($result);
	if(strlen($row->image_name)>0) {
		if(file_exists($imagepath.$row->image_name)) unlink($imagepath.$row->image_name);
		mysql_query("UPDATE tblcontentinfo SET image_name=NULL,image_align=NULL WHERE date='".$date."'",get_db_conn());
	}
	$onload="<script> alert('�̹��� ������ �Ϸ�Ǿ����ϴ�.');</script>\n";
	unset($type);
	unset($date);
}

if (strlen($type)==0) $type="insert";
?>

<? INCLUDE "header.php"; ?>

<script type="text/javascript" src="lib.js.php"></script>
<script language="JavaScript">
function CheckForm(type) {
	if(document.form1.up_subject.value.length==0) {
		document.form1.up_subject.focus();
		alert("����(information) ������ �Է��ϼ���");
		return;
	}
	if(document.form1.up_content.value.length==0) {
		document.form1.up_content.focus();
		alert("����(information) ������ �Է��ϼ���");
		return;
	}
	if(type=="modify") {
		if(!confirm("�ش� ����(information)�� �����Ͻðڽ��ϱ�?")) {
			return;
		}
		document.form1.mode.value="result";
	}
	document.form1.type.value=type;
	document.form1.submit();
}
function ContentSend(type,date) {
	if(type=="delete") {
		if(!confirm("�ش� ����(information)�� �����Ͻðڽ��ϱ�?")) return;
	}
	if(type=="imgdel") {
		if(!confirm("�ش� ����(information)�� �̹����� �����Ͻðڽ��ϱ�?")) return;
	}
	document.form1.type.value=type;
	document.form1.date.value=date;
	document.form1.submit();
}
function GoPage(block,gotopage) {
	document.form2.block.value = block;
	document.form2.gotopage.value = gotopage;
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
			<? include ("menu_market.php"); ?>
			</td>

			<td></td>
			<td valign="top">




<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td height="29" colspan="3">
			<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td height="28" class="link" align="left" background="images/con_link_bg.gif"><img src="images/top_link_house.gif" border="0" valign="absmiddle">������ġ : ���������� &gt; ���������� &gt; <span class="2depth_select">����(information) ����</span></td>
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
			<form name=form1 action="<?=$_SERVER[PHP_SELF]?>" method=post enctype="multipart/form-data">
			<input type=hidden name=type>
			<input type=hidden name=mode>
			<input type=hidden name=date value="<?=$date?>">
			<tr><td height="8"></td></tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/market_contentinfo_title.gif" border="0"></TD>
					</tr><tr>
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
					<TD width="100%" class="notice_blue">����(information)�� ���/����/���� �Ͻ� �� �ֽ��ϴ�.</TD>
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
					<TD><IMG SRC="images/market_contentinfo_stitle1.gif" border="0"></TD>
					<TD width="100%" background="images/shop_basicinfo_stitle_bg.gif"></TD>
					<TD><IMG SRC="images/shop_basicinfo_stitle_end.gif" WIDTH=10 HEIGHT=31 ALT=""></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr><td height=3></td></tr>
			<tr>
				<td>																													
				<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
				<col width=140></col>
				<col width=></col>
				<col width=50></col>
				<col width=60></col>
				<col width=60></col>
				<TR>
					<TD colspan=5 background="images/table_top_line.gif"></TD>
				</TR>
				<TR align=center>
					<TD class="table_cell">�������</TD>
					<TD class="table_cell1">����</TD>
					<TD class="table_cell1">��ȸ</TD>
					<TD class="table_cell1">����</TD>
					<TD class="table_cell1">����</TD>
				</TR>
				<TR>
					<TD colspan="5" background="images/table_con_line.gif"></TD>
				</TR>
<?
				$colspan=5;
				$sql = "SELECT COUNT(*) as t_count FROM tblcontentinfo ";
				$result = mysql_query($sql,get_db_conn());
				$row = mysql_fetch_object($result);
				$t_count = $row->t_count;
				mysql_free_result($result);
				$pagecount = (($t_count - 1) / $setup[list_num]) + 1;

				$sql = "SELECT * FROM tblcontentinfo ORDER BY date DESC ";
				$sql.= "LIMIT " . ($setup[list_num] * ($gotopage - 1)) . ", " . $setup[list_num];
				$result = mysql_query($sql,get_db_conn());
				$cnt=0;
				while($row=mysql_fetch_object($result)) {
					$number = ($t_count-($setup[list_num] * ($gotopage-1))-$cnt);
					$str_date = substr($row->date,0,4)."/".substr($row->date,4,2)."/".substr($row->date,6,2)." ".substr($row->date,8,2).":".substr($row->date,10,2).":".substr($row->date,12,2);
					echo "<TR align=center>\n";
					echo "	<TD class=\"td_con2\">".$str_date."</TD>\n";
					echo "	<TD class=\"td_con1\" align=left>".$row->subject."</TD>\n";
					echo "	<TD class=\"td_con1\">".$row->access."</TD>\n";
					echo "	<TD class=\"td_con1\"><a href=\"javascript:ContentSend('modify','".$row->date."');\"><img src=\"images/btn_edit.gif\" width=\"50\" height=\"22\" border=\"0\"></a></TD>\n";
					echo "	<TD class=\"td_con1\"><a href=\"javascript:ContentSend('delete','".$row->date."');\"><img src=\"images/btn_del.gif\" width=\"50\" height=\"22\" border=\"0\"></a></TD>\n";
					echo "</TR>\n";
					echo "<TR>\n";
					echo	"	<TD colspan=".$colspan." background=\"images/table_con_line.gif\"></TD>\n";
					echo "</TR>\n";
					$cnt++;
				}
				mysql_free_result($result);

				if ($cnt==0) {
					echo "<tr><td class=td_con2 colspan=".$colspan." align=center>�˻��� ������ �������� �ʽ��ϴ�.</td></tr>";
				}
?>
				<TR>
					<TD colspan=5 background="images/table_top_line.gif"></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr>
				<td height=10></td>
			</tr>
			<tr>
				<td>
				<table cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td align=center class="font_size">
<?
					$total_block = intval($pagecount / $setup[page_num]);

					if (($pagecount % $setup[page_num]) > 0) {
						$total_block = $total_block + 1;
					}

					$total_block = $total_block - 1;

					if (ceil($t_count/$setup[list_num]) > 0) {
						// ����	x�� ����ϴ� �κ�-����
						$a_first_block = "";
						if ($nowblock > 0) {
							$a_first_block .= "<a href='javascript:GoPage(0,1);' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='ù ������';return true\"><IMG src=\"images/icon_first.gif\" border=0 align=\"absmiddle\"></a>&nbsp;&nbsp;";

							$prev_page_exists = true;
						}

						$a_prev_page = "";
						if ($nowblock > 0) {
							$a_prev_page .= "<a href='javascript:GoPage(".($nowblock-1).",".($setup[page_num]*($block-1)+$setup[page_num]).");' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='���� ".$setup[page_num]." ������';return true\">[prev]</a>&nbsp;&nbsp;";

							$a_prev_page = $a_first_block.$a_prev_page;
						}

						// �Ϲ� �������� ������ ǥ�úκ�-����

						if (intval($total_block) <> intval($nowblock)) {
							$print_page = "";
							for ($gopage = 1; $gopage <= $setup[page_num]; $gopage++) {
								if ((intval($nowblock*$setup[page_num]) + $gopage) == intval($gotopage)) {
									$print_page .= "<span class=font_orange2><B>[".(intval($nowblock*$setup[page_num]) + $gopage)."]</B></span> ";
								} else {
									$print_page .= "<a href='javascript:GoPage(".$nowblock.",".(intval($nowblock*$setup[page_num]) + $gopage).");' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='������ : ".(intval($nowblock*$setup[page_num]) + $gopage)."';return true\">[".(intval($nowblock*$setup[page_num]) + $gopage)."]</a> ";
								}
							}
						} else {
							if (($pagecount % $setup[page_num]) == 0) {
								$lastpage = $setup[page_num];
							} else {
								$lastpage = $pagecount % $setup[page_num];
							}

							for ($gopage = 1; $gopage <= $lastpage; $gopage++) {
								if (intval($nowblock*$setup[page_num]) + $gopage == intval($gotopage)) {
									$print_page .= "<span class=font_orange2><B>[".(intval($nowblock*$setup[page_num]) + $gopage)."]</B></span> ";
								} else {
									$print_page .= "<a href='javascript:GoPage(".$nowblock.",".(intval($nowblock*$setup[page_num]) + $gopage).");' onMouseOver=\"window.status='������ : ".(intval($nowblock*$setup[page_num]) + $gopage)."';return true\">[".(intval($nowblock*$setup[page_num]) + $gopage)."]</a> ";
								}
							}
						}		// ������ �������� ǥ�úκ�-��


						$a_last_block = "";
						if ((intval($total_block) > 0) && (intval($nowblock) < intval($total_block))) {
							$last_block = ceil($t_count/($setup[list_num]*$setup[page_num])) - 1;
							$last_gotopage = ceil($t_count/$setup[list_num]);

							$a_last_block .= "&nbsp;&nbsp;<a href='javascript:GoPage(".$last_block.",".$last_gotopage.");' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='������ ������';return true\"><IMG src=\"images/icon_last.gif\" border=0 align=\"absmiddle\" width=\"17\" height=\"14\"></a>";

							$next_page_exists = true;
						}

						// ���� 10�� ó���κ�...

						$a_next_page = "";
						if ((intval($total_block) > 0) && (intval($nowblock) < intval($total_block))) {
							$a_next_page .= "&nbsp;&nbsp;<a href='javascript:GoPage(".($nowblock+1).",".($setup[page_num]*($nowblock+1)+1).");' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='���� ".$setup[page_num]." ������';return true\">[next]</a>";

							$a_next_page = $a_next_page.$a_last_block;
						}
					} else {
						$print_page = "<B>[1]</B>";
					}
?>
					<?=$a_div_prev_page.$a_prev_page.$print_page.$a_next_page.$a_div_next_page?>
					</td>
				</tr>
				</table>
				</td>
			</tr>
			<tr>
				<td height="30"></td>
			</tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/market_contentinfo_stitle2.gif" border="0"></TD>
					<TD width="100%" background="images/shop_basicinfo_stitle_bg.gif"></TD>
					<TD><IMG SRC="images/shop_basicinfo_stitle_end.gif" WIDTH=10 HEIGHT=31 ALT=""></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr><td height=3></td></tr>
			<tr>
				<td>
				<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
				<col width=140></col>
				<col width=></col>
				<TR>
					<TD colspan=2 background="images/table_top_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">������</TD>
					<TD class="td_con1"><INPUT style="WIDTH: 100%" name=up_subject class="input" value="<?=$subject?>"></TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">����</TD>
					<TD class="td_con1"><TEXTAREA style="WIDTH: 100%; HEIGHT: 200px" name=up_content class="textarea"><? echo $content ?></TEXTAREA></TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">�̹���</TD>
					<TD class="td_con1">
					<SELECT name=up_image_align class="select">
					<option value="left" <? if ($image_align=="left") echo "selected" ?>>��������
					<option value="right" <? if ($image_align=="right") echo "selected" ?>>����������
					<option value="top" <? if ($image_align=="top") echo "selected" ?>>��������
					<option value="bottom" <? if ($image_align=="bottom") echo "selected" ?>>�Ʒ�������
					</SELECT>
					<INPUT style="WIDTH: 65%" type=file name=up_image class="input">
					<?if(strlen($image_name)>0){?>
					<a href="javascript:ContentSend('imgdel','<?=$date?>');"><img src="images/myicon_upload_del.gif" border="0"></a><input type=hidden name=old_image value="<?=$image_name?>">
					<?}?><br><span class="font_orange" style="font-size:11px;letter-spacing:-0.5pt;">* �̹����� 150KB ������ GIF, JPG�� ����</span>
					</TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
<?
				if (strlen($image_name)>0) {
					if (file_exists($imagepath.$image_name)==true) {
						$width = getimagesize($imagepath.$image_name);
						if ($width[0]>=450) $width=" width=450 ";
					}
?>
				<tr>
					<td class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">��ϵ� �̹���</td>
					<TD class="td_con1">
					<img src="<?=$imagepath.$image_name?>" <?=$width?>>
					</td>
				</tr>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<?}?>
				<?if($type=="modify"){?>
				<TR>
					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">����� ���濩��</TD>
					<TD class="td_con1"><INPUT id=idx_newdate type=checkbox CHECKED value=Y name=up_newdate>�ش� �������� ������� ����ð����� �����մϴ�. (�ֱ� ������ ����)</LABEL></TD>
				</TR>
				<?}?>
				<TR>
					<TD colspan=2 background="images/table_top_line.gif"></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr><td height=10></td></tr>
			<tr>
				<td align=center><a href="javascript:CheckForm('<?=$type?>');"><img src="images/botteon_save.gif" width="113" height="38" border="0"></a></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/manual_top1.gif" WIDTH=15 height="45" ALT=""></TD>
					<TD><IMG SRC="images/manual_title.gif" WIDTH=113 height="45" ALT=""></TD>
					<TD width="100%" background="images/manual_bg.gif" height="35"></TD>
					<TD background="images/manual_bg.gif"></TD>
					<td background="images/manual_bg.gif"><IMG SRC="images/manual_top2.gif" WIDTH=18 height="45" ALT=""></td>
				</TR>
				<TR>
					<TD background="images/manual_left1.gif"></TD>
					<TD COLSPAN=3 width="100%" valign="top" bgcolor="white" style="padding-top:8pt; padding-bottom:8pt; padding-left:4pt;" class="menual_bg">
					<table cellpadding="0" cellspacing="0" width="100%">
					<col width=20></col>
					<col width=></col>
					<tr>
						<td align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td><span class="font_dotline">����(information)����</span></td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td class="space_top">- ����(information) �޴��� ���θ� ���� �Ǵ� Ŀ�´�Ƽ ��縦 �����ϴ� �޴� �Դϴ�.</td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td class="space_top">- ����(information)�� ����ȭ�� ���ø����� ���� ������ �⺻���� ��µǰ� ������ �ֽ��ϴ�.<br>
						<b>&nbsp;&nbsp;</b><a href="javascript:parent.topframe.GoMenu(2,'design_main.php');"><span class="font_blue">�����ΰ��� > ���ø�-���� �� ī�װ� > ����ȭ�� ���ø�</span></a></td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td class="space_top">- ����(information) �űԵ�� �Ǵ� ������ "����� ���濩��"�� ������ ���� ����(information) ��½� �ֻ�ܿ� ��ġ�մϴ�.</td>
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
			</form>

			<form name=form2 action="<?=$_SERVER[PHP_SELF]?>" method=post>
			<input type=hidden name=type>
			<input type=hidden name=block value="<?=$block?>">
			<input type=hidden name=gotopage value="<?=$gotopage?>">
			</form>
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