<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
INCLUDE ("access.php");

####################### ������ ���ٱ��� check ###############
$PageCode = "pr-6";
$MenuCode = "product";
if (!$_usersession->isAllowedTask($PageCode)) {
	INCLUDE ("AccessDeny.inc.php");
	exit;
}
#########################################################

$type=$_POST["type"];
$mode=$_POST["mode"];
$option_code=$_POST["option_code"];
$description=$_POST["description"];
$option_choice=$_POST["option_choice"];
$option_list=$_POST["option_list"];
if ($type=="delete") {
	if (strlen($option_code)>0) {
		$sql = "DELETE FROM tblproductoption WHERE option_code = '".$option_code."' ";
		$delete = mysql_query($sql,get_db_conn());
		if ($delete) {
			$onload="<script>alert('�ش� �ɼǱ׷� ������ �Ϸ�Ǿ����ϴ�.');</script>";
		}
	}
	$type="insert";
} else if ($type=="modify") {
	if (strlen($option_code)>0) {
		if ($mode=="result") {
			$arroptval = explode("��",$option_list);
			$sql = "UPDATE tblproductoption SET ";
			$sql.= "description		= '".$description."', ";
			for($i=0;$i<10;$i++) {
				$tmp = "0".($i+1);
				$tmp = substr($tmp,-2);
				if (strlen($arroptval[$i])>0) {
					$sql.= "option_value".$tmp." = '".$arroptval[$i]."', ";
				} else {
					$sql.= "option_value".$tmp." = NULL, ";
				}
			}
			$sql.= "option_choice	= '".$option_choice."' ";
			$sql.= "WHERE option_code = '".$option_code."' ";
			$update = mysql_query($sql,get_db_conn());
			if ($update) {
				$onload="<script>alert('�ش� �ɼǱ׷� ���� ������ �Ϸ�Ǿ����ϴ�.');</script>";
			}
		}
		$sql = "SELECT * FROM tblproductoption WHERE option_code = '".$option_code."' ";
		$result = mysql_query($sql,get_db_conn());
		if ($row=mysql_fetch_object($result)) {
			$description = $row->description;
			$option_choice = $row->option_choice;
			$option_value01 = $row->option_value01;
			$option_value02 = $row->option_value02;
			$option_value03 = $row->option_value03;
			$option_value04 = $row->option_value04;
			$option_value05 = $row->option_value05;
			$option_value06 = $row->option_value06;
			$option_value07 = $row->option_value07;
			$option_value08 = $row->option_value08;
			$option_value09 = $row->option_value09;
			$option_value10 = $row->option_value10;
		} else {
			$type="insert";
		}
		mysql_free_result($result);
	}
} else if ($type=="insert" && $mode=="result") {
	$sql = "SELECT MAX(option_code) as maxcode FROM tblproductoption ";
	$result = mysql_query($sql,get_db_conn());
	if ($row = mysql_fetch_object($result)) {
		if($row->maxcode==NULL) $option_code=1000;
		else $option_code=$row->maxcode+10;
	} else {
		$option_code=1000;
	}
	mysql_free_result($result);
	$arroptval = explode("��",$option_list);
	$sql = "INSERT tblproductoption SET ";
	$sql.= "option_code		= '".$option_code."', ";
	$sql.= "description		= '".$description."', ";
	for($i=0;$i<count($arroptval);$i++) {
		$tmp = "0".($i+1);
		$tmp = substr($tmp,-2);
		$sql.= "option_value".$tmp." = '".$arroptval[$i]."', ";
	}
	$sql.= "option_choice	= '".$option_choice."' ";
	$insert = mysql_query($sql,get_db_conn());
	if ($insert) {
		$onload="<script>alert('�ɼǱ׷� ����� �Ϸ�Ǿ����ϴ�.');</script>";
	}
} else {
	$type="insert";
}

?>

<? INCLUDE "header.php"; ?>

<script type="text/javascript" src="lib.js.php"></script>
<script language="JavaScript">
function CheckForm() {
	var form = document.form1;
	var option_choice = "";
	var option_list = "";
	if (form.description.value.length==0) {
		form.description.focus();
		alert("�ɼǱ׷���� �Է��ϼ���.");
		return;
	}
	var yy=0;
	for(var i=0;i<10;i++) {
		if (form["option_value"+i].value.length>0) {
			if (form["option_name"+i].value.length==0) {
				form["option_name"+i].focus();
				alert("�ɼǸ��� �Է��ϼ���.");
				return;
			}
			tmpline = form["option_value"+i].value.split("\r\n");
			if (tmpline.length>0) {
				if (yy>0) {
					option_list+="��";
				}
				option_list+=form["option_name"+i].value;
				for(var j=0;j<tmpline.length;j++) {
					tmp = tmpline[j].split(",");

					if (tmp.length>1) {
						if (isNaN(tmp[1])) {
							form["option_value"+i].focus();
							alert("�ش� �Ӽ��� �Ӽ������� ���ڸ� �Է� �����մϴ�.");
							return;
						}
						option_list+=""+tmp[0]+","+tmp[1];
					} else {
						if (tmp.length>0) {
							option_list+=""+tmp[0];
						}
					}
				}
				if (form["option_choice"+i][0].checked == true) {
					no=0;
				} else {
					no=1;
				}
				option_choice+=""+no;
				yy++;
			}
		}
	}

	if (option_choice.length > 0) {
		option_choice = option_choice.substring(1,option_choice.length);
	} else {
		alert("�ɼ��� �ϳ� �̻� �߰����� �ʾҽ��ϴ�.");
		return;
	}
	form.option_choice.value=option_choice;
	form.option_list.value=option_list;
	form.submit();
}

function SendMode(type,code) {
	if (type=="delete") {
		if (!confirm("�ش� �ɼǱ׷��� �����Ͻðڽ��ϱ�?")) {
			return;
		}
	}
	document.form2.type.value=type;
	document.form2.option_code.value=code;
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
			<? include ("menu_product.php"); ?>
			</td>

			<td></td>
			<td valign="top">




<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td height="29" colspan="3">
			<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td height="28" class="link" align="left" background="images/con_link_bg.gif"><img src="images/top_link_house.gif" border="0" valign="absmiddle">������ġ : ��ǰ���� &gt; �ɼǱ׷� ��� ���� &gt; <span class="2depth_select">�ɼǱ׷� ��� ����</span></td>
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
					<TD><IMG SRC="images/product_option_title.gif" border="0"></TD>
					</tr><tr>
					<TD width="100%" background="images/title_bg.gif" height=21></TD>
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
					<TD width="100%" class="notice_blue">��ǰ�� ���/������ ������ �ɼǱ׷��� ����� �� �ֽ��ϴ�.</TD>
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
					<TD><IMG SRC="images/product_option_stitle1.gif" WIDTH="192" HEIGHT=31 ALT=""></TD>
					<TD width="100%" background="images/shop_basicinfo_stitle_bg.gif">&nbsp;</TD>
					<TD><IMG SRC="images/shop_basicinfo_stitle_end.gif" WIDTH=10 HEIGHT=31 ALT=""></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr><td height=3></td></tr>
			<tr>
				<td>
				<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
				<TR>
					<TD background="images/table_top_line.gif" colspan="4"></TD>
				</TR>
				<TR align=center>
					<TD class="table_cell">No</TD>
					<TD class="table_cell1" width="65%">�ɼǱ׷��</TD>
					<TD class="table_cell1">����</TD>
					<TD class="table_cell1">����</TD>
				</TR>
				<TR>
					<TD colspan="4" background="images/table_con_line.gif"></TD>
				</TR>
<?
				$setup[page_num] = 10;
				$setup[list_num] = 10;

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

				$sql = "SELECT COUNT(*) as t_count FROM tblproductoption ";
				$result = mysql_query($sql,get_db_conn());
				$row = mysql_fetch_object($result);
				mysql_free_result($result);
				$t_count = $row->t_count;
				$pagecount = (($t_count - 1) / $setup[list_num]) + 1;

				$sql = "SELECT * FROM tblproductoption ";
				$sql.= "ORDER BY option_code DESC LIMIT " . ($setup[list_num] * ($gotopage - 1)) . ", " . $setup[list_num];
				$result = mysql_query($sql,get_db_conn());
				$i=0;
				while($row=mysql_fetch_object($result)) {
					$number = ($t_count-($setup[list_num] * ($gotopage-1))-$i);
					echo "<tr>\n";
					echo "	<TD align=center class=\"td_con2\">".$number."</td>\n";
					echo "	<TD class=\"td_con1\" width=\"65%\">".$row->description."</td>\n";
					echo "	<TD align=center class=\"td_con1\"><A HREF=\"javascript:SendMode('modify','".$row->option_code."');\"><img src=\"images/btn_edit.gif\" width=\"50\" height=\"22\" border=\"0\"></A></td>\n";
					echo "	<TD align=center class=\"td_con1\"><A HREF=\"javascript:SendMode('delete','".$row->option_code."');\"><img src=\"images/btn_del.gif\" width=\"50\" height=\"22\" border=\"0\"></A></td>\n";
					echo "</tr>\n";
					echo "<tr>\n";
					echo "	<TD colspan=\"4\" background=\"images/table_con_line.gif\"></TD>\n";
					echo "</tr>\n";
					$i++;
				}
				mysql_free_result($result);

				if ($i==0) {
					echo "<tr><td class=\"td_con2\" colspan=\"4\" align=\"center\">��ϵ� �ɼǱ׷� ������ �����ϴ�.</td></tr>\n";
				}
?>
				<TR>
					<TD background="images/table_top_line.gif" colspan="4"></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr><td height=10></td></tr>
			<tr>
				<td>
				<table cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td width="100%" align=center class="font_size">
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
							$a_first_block .= "<a href='".$_SERVER[PHP_SELF]."?block=0&gotopage=1' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='ù ������';return true\"><IMG src=\"images/icon_first.gif\" border=0 align=\"absmiddle\"></a>&nbsp;&nbsp;";

							$prev_page_exists = true;
						}

						$a_prev_page = "";
						if ($nowblock > 0) {
							$a_prev_page .= "<a href='".$_SERVER[PHP_SELF]."?block=".($nowblock-1)."&gotopage=".($setup[page_num]*($block-1)+$setup[page_num])."' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='���� ".$setup[page_num]." ������';return true\">[prev]</a>&nbsp;&nbsp;";

							$a_prev_page = $a_first_block.$a_prev_page;
						}

						// �Ϲ� �������� ������ ǥ�úκ�-����

						if (intval($total_block) <> intval($nowblock)) {
							$print_page = "";
							for ($gopage = 1; $gopage <= $setup[page_num]; $gopage++) {
								if ((intval($nowblock*$setup[page_num]) + $gopage) == intval($gotopage)) {
									$print_page .= "<span class=font_orange2><B>[".(intval($nowblock*$setup[page_num]) + $gopage)."]</B></span> ";
								} else {
									$print_page .= "<a href='".$_SERVER[PHP_SELF]."?block=".$nowblock."&gotopage=". (intval($nowblock*$setup[page_num]) + $gopage)."' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='������ : ".(intval($nowblock*$setup[page_num]) + $gopage)."';return true\">[".(intval($nowblock*$setup[page_num]) + $gopage)."]</a> ";
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
									$print_page .= "<a href='".$_SERVER[PHP_SELF]."?block=".$nowblock."&gotopage=".(intval($nowblock*$setup[page_num]) + $gopage)."' onMouseOver=\"window.status='������ : ".(intval($nowblock*$setup[page_num]) + $gopage)."';return true\">[".(intval($nowblock*$setup[page_num]) + $gopage)."]</a> ";
								}
							}
						}		// ������ �������� ǥ�úκ�-��


						$a_last_block = "";
						if ((intval($total_block) > 0) && (intval($nowblock) < intval($total_block))) {
							$last_block = ceil($t_count/($setup[list_num]*$setup[page_num])) - 1;
							$last_gotopage = ceil($t_count/$setup[list_num]);

							$a_last_block .= "&nbsp;&nbsp;<a href='".$_SERVER[PHP_SELF]."?block=".$last_block."&gotopage=".$last_gotopage."' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='������ ������';return true\"><IMG src=\"images/icon_last.gif\" border=0 align=\"absmiddle\" width=\"17\" height=\"14\"></a>";

							$next_page_exists = true;
						}

						// ���� 10�� ó���κ�...

						$a_next_page = "";
						if ((intval($total_block) > 0) && (intval($nowblock) < intval($total_block))) {
							$a_next_page .= "&nbsp;&nbsp;<a href='".$_SERVER[PHP_SELF]."?block=".($nowblock+1)."&gotopage=".($setup[page_num]*($nowblock+1)+1)."' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='���� ".$setup[page_num]." ������';return true\">[next]</a>";

							$a_next_page = $a_next_page.$a_last_block;
						}
					} else {
						$print_page = "<B>[1]</B>";
					}
?>
					<!-- ������ ��� ---------------------->
					<?=$a_div_prev_page?>
					<?=$a_prev_page?>
					<?=$print_page?>
					<?=$a_next_page?>
					<?=$a_div_next_page?>
					<!-- ������ ��� �� -->
					</td>
				</tr>
				</table>
				</td>
			</tr>
			<tr><td height="30"></td></tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/product_option_stitle2.gif" WIDTH="192" HEIGHT=31 ALT=""></TD>
					<TD width="100%" background="images/shop_basicinfo_stitle_bg.gif">&nbsp;</TD>
					<TD><IMG SRC="images/shop_basicinfo_stitle_end.gif" WIDTH=10 HEIGHT=31 ALT=""></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr><td height=3></td></tr>
			<form name=form1 method=post action="<?=$_SERVER[PHP_SELF]?>">
			<input type=hidden name=type value="<?=$type?>">
			<input type=hidden name=mode value="result">
			<input type=hidden name=option_code value="<?=$option_code?>">
			<input type=hidden name=option_choice>
			<input type=hidden name=option_list>
			<input type=hidden name=block value="<?=$block?>">
			<input type=hidden name=gotopage value="<?=$gotopage?>">
			<tr>
				<td>
				<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
				<TR>
					<TD colspan=2 background="images/table_top_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell" width="139"><img src="images/icon_point2.gif" width="8" height="11" border="0"><B>�ɼǱ׷��</B></TD>
					<TD class="td_con1" width="600"><input type=text name="description" value="<?=$description?>" size=100 maxlength=200 onKeyDown="chkFieldMaxLen(200);" style="width:100%" class="input"></TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<tr>
					<TD colspan="2" style="padding-top:5pt; padding-bottom:5pt;">
					<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
					<TR>
<?
					$option_choice = explode("",$option_choice);
					$arrsample1 = array("����","������","��Ÿ��","����","������","��Ÿ��","����","������","��Ÿ��","����");
					$arrsample2 = array("�Ķ�,1000","XL,0 �Ǵ� XL","����Ŀ�ù���(14K),10000","����,0 �Ǵ� ����","XXL,1000","������(14K),5000","���,0 �Ǵ� ���","M,0 �Ǵ� M","����Ŀ�ù���(18K),20000","���,0 �Ǵ� ���");
					for($i=0;$i<10;$i++) {
						if ($i!=0 && $i%2==0) {
							echo "</tr><TD colSpan=3 height=20></TD></tr><tr>\n";
						}
						echo "<TD width=\"50%\">\n";

						$cnt = substr("0".($i+1),-2);
						unset($options);
						$options = explode("",${"option_value".$cnt});

						unset($check_choice0);
						unset($check_choice1);
						if ($option_choice[$i]==1)	$check_choice1 = "checked";
						else						$check_choice0 = "checked";
?>
						<TABLE cellSpacing="1" cellPadding="1" width="100%" border=0 bgcolor="#EBEBEB">
						<tr>
							<TD class=lineleft align=middle bgColor=#f0f0f0 colSpan=2><B>�ɼ�<?=($i+1)?></B></td>
						</tr>
						<tr>
							<TD class=lineleft style="PADDING-RIGHT: 5px" noWrap align=right width=120 bgcolor="white">�ɼ��ʼ�����</td>
							<TD class=line style="PADDING-LEFT: 5px" width="100%" bgcolor="white"><input type=radio id="idx_option_choice1<?=$i?>" name="option_choice<?=$i?>" value=0 <?=$check_choice0?>> <label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_option_choice1<?=$i?>>���ʼ�</label>&nbsp;&nbsp;&nbsp;<input type=radio id="idx_option_choice2<?=$i?>" name="option_choice<?=$i?>" value=1 <?=$check_choice1?>> <label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_option_choice2<?=$i?>>�ʼ�</label></td>
						</tr>
						<tr>
							<TD class=lineleft style="PADDING-RIGHT: 5px" noWrap align=right width=120 bgcolor="white">�Ӽ���</td>
							<TD class=line style="PADDING-LEFT: 5px; PADDING-BOTTOM: 3px; LINE-HEIGHT: 15px; PADDING-TOP: 3px" width="100%" bgcolor="white"><input type=text name="option_name<?=$i?>" value="<?=$options[0]?>" style="width:98%" class="input"><BR><FONT class=font_orange>��) <?=$arrsample1[$i]?></font></td>
						</tr>
						<tr>
							<TD class=linebottomleft style="PADDING-RIGHT: 5px" noWrap align=right width=120 bgcolor="white">�Ӽ�,�Ӽ�����</td>
							<TD style="PADDING-RIGHT: 5px; PADDING-LEFT: 5px; PADDING-BOTTOM: 3px; PADDING-TOP: 3px" width="100%" bgcolor="white">
<?
							unset($option_value);
							$yy=0;
							for($y=1;$y<count($options);$y++) {
								if (strlen(trim($options[$y]))>0) {
									if ($yy>0) $option_value.= "\r\n";
									$option_value.= trim($options[$y]);
									$yy++;
								}
							}
?>
							<textarea name="option_value<?=$i?>" style="width:100%;height:71" class="textarea"><?=$option_value?></textarea><br>* ���ٿ� �ϳ��� �Ӽ��� �Ӽ����� �Է�<BR>* <FONT class=font_orange>��) <?=$arrsample2[$i]?></FONT>
							</td>
						</tr>
						</table>
<?
						echo "</td>\n";
						if ($i%2==0) {
							echo "<TD noWrap width=20></TD>\n";
						}
					}
?>
					</TABLE>
					</TD>
				</TR>
				<TR>
					<TD colspan=2 background="images/table_top_line.gif"></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr><td height=10></td></tr>
			<tr>
				<td align="center"><a href="javascript:CheckForm();"><img src="images/botteon_save.gif" width="113" height="38" border="0"></a></td>
			</tr>
			</form>
			<form name=form2 method=post action="<?=$_SERVER[PHP_SELF]?>">
			<input type=hidden name=type>
			<input type=hidden name=option_code>
			<input type=hidden name=block value="<?=$block?>">
			<input type=hidden name=gotopage value="<?=$gotopage?>">
			</form>
			<tr><td height=20></td></tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/manual_top1.gif" WIDTH=15 height="45" ALT=""></TD>
					<TD><IMG SRC="images/manual_title.gif" WIDTH=113 height="45" ALT=""></TD>
					<TD width="100%" background="images/manual_bg.gif" height="35"></TD>
					<TD background="images/manual_bg.gif">&nbsp;</TD>
					<td background="images/manual_bg.gif"><IMG SRC="images/manual_top2.gif" WIDTH=18 height="45" ALT=""></td>
				</TR>
				<TR>
					<TD background="images/manual_left1.gif"</TD>
					<TD COLSPAN=3 width="100%" valign="top" bgcolor="white" style="padding-top:8pt; padding-bottom:8pt; padding-left:4pt;" class="menual_bg">
					<table cellpadding="0" cellspacing="0" width="100%">
					<col width=20></col>
					<col width=></col>
					<tr>
						<td align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td><span class="font_dotline">�ɼǱ׷� ��� ����</span></td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td class="space_top">- �ɼǱ׷��� ������ ����� �����մϴ�.</td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td class="space_top">- �ɼǱ׷��� ����/������ �ش� �ɼǱ׷��� ����� ��ǰ�� �ٷ� ����˴ϴ�.</td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td class="space_top">- �ɼ� ��¹���� <a href="javascript:parent.topframe.GoMenu(1,'shop_mainproduct.php');"><span class="font_blue">�������� > ���θ� ȯ�� ���� > ��ǰ ���� ��Ÿ ����</span></a> ���� ������ �� �ֽ��ϴ�.</td>
					</tr>
					<tr>
						<td colspan="2" height="20"></td>
					</tr>
					<tr>
						<td align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td><span class="font_dotline">�ɼǱ׷� ��� ���</span></td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td class="space_top">�� �ɼǱ׷���� �Է��մϴ�.</td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td class="space_top">�� �ɼ��ʼ����θ� ������ �ּ���.</td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td class="space_top">�� �Ӽ����� �Է��� �ּ���.&nbsp;&nbsp;&nbsp;&nbsp;<span class="font_orange">��)������, ����, �뷮, �뵵 ��</span></td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td class="space_top">�� �Ӽ�,�Ӽ������� �Է��� �ּ���. �Ӽ������� ���û����Դϴ�.(���ٿ� �ϳ��� �Ӽ�,�Ӽ����� �Է�)<br>
						&nbsp;&nbsp;&nbsp;&nbsp;<span class="font_orange">��)�Ӽ����� ������ ���<span class="font_blue">(�Ӽ����� �Է�)</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;��)�Ӽ����� �������� ���<span class="font_blue">(�Ӽ����� ���Է�)</span><br>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;���,25600&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;L(100~105)<br>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;����,26600&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;XL(105~110)<br>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;���̺���,25600&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;XXL(110)<br>
						</span></td>
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