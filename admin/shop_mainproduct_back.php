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

$type=$_POST["type"];
$mnew_cols=$_POST["mnew_cols"];
$mnew_type=$_POST["mnew_type"];
$mnew_rows=$_POST["mnew_rows"];

$mbest_cols=$_POST["mbest_cols"];
$mbest_type=$_POST["mbest_type"];
$mbest_rows=$_POST["mbest_rows"];

$mhot_cols=$_POST["mhot_cols"];
$mhot_type=$_POST["mhot_type"];
$mhot_rows=$_POST["mhot_rows"];

$main_notice_num=$_POST["main_notice_num"];
$main_special_num=$_POST["main_special_num"];
$main_special_type=$_POST["main_special_type"];
$main_info_num=$_POST["main_info_num"];

$prlist_num=$_POST["prlist_num"];

if ($type=="up" && strlen($mnew_rows)>0) {
	$mnew_num=$mnew_rows*$mnew_cols;
	$mbest_num=$mbest_rows*$mbest_cols;
	$mhot_num=$mhot_rows*$mhot_cols;
	
	$main_newprdt=$mnew_num."|".$mnew_cols."|".$mnew_type;
	$main_bestprdt=$mbest_num."|".$mbest_cols."|".$mbest_type;
	$main_hotprdt=$mhot_num."|".$mhot_cols."|".$mhot_type;

	$sql = "UPDATE tblshopinfo SET ";
	$sql.= "main_newprdt		= '".$main_newprdt."', ";
	$sql.= "main_bestprdt		= '".$main_bestprdt."', ";
	$sql.= "main_hotprdt		= '".$main_hotprdt."', ";
	$sql.= "main_notice_num		= '".$main_notice_num."', ";
	$sql.= "main_special_num	= '".$main_special_num."', ";
	$sql.= "main_special_type	= '".$main_special_type."', ";
	$sql.= "main_info_num		= '".$main_info_num."', ";
	$sql.= "prlist_num			= '".$prlist_num."' ";
	$result = mysql_query($sql,get_db_conn());
	DeleteCache("tblshopinfo.cache");
	$onload="<script> alert('���� ������ �Ϸ�Ǿ����ϴ�. $msg'); </script>";
}

$sql = "SELECT * FROM tblshopinfo ";
$result = mysql_query($sql,get_db_conn());
if($row=mysql_fetch_object($result)) {
	$main_newprdt = explode("|",$row->main_newprdt);
	$main_bestprdt = explode("|",$row->main_bestprdt);
	$main_hotprdt = explode("|",$row->main_hotprdt);
	
	$main_notice_num = $row->main_notice_num;
	$main_special_num = $row->main_special_num;
	$main_special_type = $row->main_special_type;
	$main_info_num = $row->main_info_num;
	
	$mnew_num=$main_newprdt[0];
	$mnew_cols=$main_newprdt[1];
	$mnew_type=$main_newprdt[2];
	$mbest_num=$main_bestprdt[0];
	$mbest_cols=$main_bestprdt[1];
	$mbest_type=$main_bestprdt[2];
	$mhot_num=$main_hotprdt[0];
	$mhot_cols=$main_hotprdt[1];
	$mhot_type=$main_hotprdt[2];
	
	$mnew_rows = $mnew_num / $mnew_cols;
	$mbest_rows = $mbest_num / $mbest_cols;
	$mhot_rows = $mhot_num / $mhot_cols;

	$prlist_num = $row->prlist_num;
}
mysql_free_result($result);
?>

<? INCLUDE "header.php"; ?>

<script type="text/javascript" src="lib.js.php"></script>
<script>
function CheckForm(type) {
	if(document.form1.mnew_cols.value>5) {
		rowsresult = false;
		for(var i=0; i<5; i++) {
			if(document.form1.mnew_rows[i].checked) {
				rowsresult = true;
				break;
			}
		}
		if(rowsresult == false) {
			alert('�űԻ�ǰ ���� ��ǰ�� �ټ��� ������ �ּ���.');
			document.form1.mnew_rows[0].focus();
			return;
		}
	}

	if(document.form1.mbest_cols.value>5) {
		rowsresult = false;
		for(var i=0; i<5; i++) {
			if(document.form1.mbest_rows[i].checked) {
				rowsresult = true;
				break;
			}
		}
		if(rowsresult == false) {
			alert('�α��ǰ ���� ��ǰ�� �ټ��� ������ �ּ���.');
			document.form1.mbest_rows[0].focus();
			return;
		}
	}

	if(document.form1.mhot_cols.value>5) {
		rowsresult = false;
		for(var i=0; i<5; i++) {
			if(document.form1.mhot_rows[i].checked) {
				rowsresult = true;
				break;
			}
		}
		if(rowsresult == false) {
			alert('��õ��ǰ ���� ��ǰ�� �ټ��� ������ �ּ���.');
			document.form1.mhot_rows[0].focus();
			return;
		}
	}
	
	if(confirm('���� ������ �����ϰڽ��ϱ�?')) {
		form1.type.value=type;
		form1.submit();
	}
}
//best
function changeimg(temp,temp2){
	temp3="";

	if(temp==1){
		if(temp2.options[temp2.selectedIndex].value<=5) document.form1.plusrow.disabled=false;
		else {
			document.form1.plusrow.checked=false;
			document.form1.plusrow.disabled=true;
		}
		if(document.form1.plusrow.checked==true){
			temp3="A";
		}
		img=document.form1.productimg;
	} else if(temp==2) {
		if(temp2.options[temp2.selectedIndex].value<=5) document.form1.plusnewrow.disabled=false;
		else {
			document.form1.plusnewrow.checked=false;
			document.form1.plusnewrow.disabled=true;
		}
		if(document.form1.plusnewrow.checked==true){
			temp3="A";
		}
		img=document.form1.newimg;
	} else {
		if(temp2.options[temp2.selectedIndex].value<=5) document.form1.plusbestrow.disabled=false;
		else {
			document.form1.plusbestrow.checked=false;
			document.form1.plusbestrow.disabled=true;
		}
		if(document.form1.plusbestrow.checked==true){
			temp3="A";
		}
		img=document.form1.bestimg;
	}
	displaydiv(temp);
	img.src="images/product_num"+temp2.options[temp2.selectedIndex].value+temp3+".gif";
}

function displaydiv(temp){
	var layername2 = new Array ('display1','display2','display3','display4','display5','display6');

	if(temp==1){ 
		start=0; end=2;
		if(document.form1.plusrow.checked==true) shop="display2";
		else  shop="display1";
	} else if(temp==2) { 
		start=2; end=4;
		if(document.form1.plusnewrow.checked==true) shop="display4";
		else  shop="display3";
	} else {
		start=4; end=6;
		if(document.form1.plusbestrow.checked==true) shop="display6";
		else  shop="display5";
	}
	if(document.all){
		for(i=start;i<end;i++) document.all(layername2[i]).style.display="none";
		document.all(shop).style.display="block";
	} else if(document.getElementById){
		for(i=start;i<end;i++) document.getElementByld(layername2[i]).style.display="none";
		document.getElementById(shop).style.display="block";
	} else if(document.layers){
		for(i=start;i<end;i++) document.layers(layername2[i]).display="none";
		document.layers[shop].display="block";
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
				<td height="28" class="link" align="left" background="images/con_link_bg.gif"><img src="images/top_link_house.gif" border="0" valign="absmiddle">������ġ : �������� &gt; ���θ� ȯ�� ���� &gt; <span class="2depth_select">��ǰ ������/ȭ�鼳��</span></td>
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
					<TD><IMG SRC="images/shop_productshow_title.gif" border="0"></TD>
				</TR>
				<TR>
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
					<TD width="100%" class="notice_blue">���θ��� ���� ��ǰ �� ī�װ� ��ǰ�� �������� ���� ��ǰ ���� Ÿ���� ������ �� �ֽ��ϴ�.</TD>
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
			<form name=form1 action="<?=$_SERVER[PHP_SELF]?>" method=post enctype="multipart/form-data">
			<input type=hidden name=type>
			<input type=hidden name=etcvalue value="<?=$etcvalue?>">
			<tr>
				<td>	
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/shop_mainproduct_stitle1.gif"  ALT=""></TD>
					<TD width="100%" background="images/shop_basicinfo_stitle_bg.gif">&nbsp;</TD>
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
					<TD width="100%" class="notice_blue">1) ���������� ������ǰ�� ���δ� ��ǰ���� ���ڿ� �����ټ�, ����Ÿ���� ������ �� �ֽ��ϴ�.<br>2) ���ο� ������ ��ǰ�� ����ϴ��� ���������� ������ ���ڸ�ŭ�� �����˴ϴ�.</TD>
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
			<tr><td height=3></td></tr>
			<tr>
				<td>
				<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
				<TR>
					<TD background="images/table_top_line.gif" width="153"></TD>
					<TD background="images/table_top_line.gif" width="607"></TD>
				</TR>
				<TR>
					<TD class="table_cell" width="760" colspan="2">
					<table cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td>&nbsp;<b>����(����)�� ��ǰ�� : <select name=mnew_cols onchange=changeimg(2,this) style="width:42px" class="select">
						<?
						for($i=1;$i<=8;$i++){
							echo "<option value=\"".$i."\"";
							if($i==$mnew_cols) echo " selected";
							echo ">".$i."��";
						}
						if($mnew_rows>5) $plusnewrow="Y";
						?>
						</select> <input type=checkbox id="idx_plusnewrow1" name=plusnewrow value="Y" <?=$plusnewrow=="Y"?"checked":""?> onclick=changeimg(2,document.form1.mnew_cols)> <label style='cursor:hand; TEXT-DECORATION: none' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_plusnewrow1>5��(����)�̻� �߰�.</label> ���κ� 5�� �������� ����</b></td>
					</tr>
					<tr>
						<td nowrap>
						<input type=radio id="idx_mnew_type0" name=mnew_type value="I" <? if ($mnew_type=="I") echo "checked"; ?>><label style='cursor:hand; TEXT-DECORATION: none' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_mnew_type0><span class="font_orange">�̹���A�� Ÿ�� ����</span> <b>(����)</b> </label>
						<input type=radio id="idx_mnew_type1" name=mnew_type value="D" <? if ($mnew_type=="D") echo "checked"; ?>><label style='cursor:hand; TEXT-DECORATION: none' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_mnew_type1>�̹���B�� Ÿ�� ����</label>
						<input type=radio id="idx_mnew_type2" name=mnew_type value="L" <? if ($mnew_type=="L") echo "checked"; ?>><label style='cursor:hand; TEXT-DECORATION: none' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_mnew_type2>����Ʈ�� Ÿ��(�ټ��� ����) ����</label>
						</td>
					</tr>
					</table>
					</TD>
				</TR>
				<TR>
					<TD colspan="2" width="760" background="images/table_con_line.gif"></TD>
				</TR>
				<TR>
					<TD width="100%" colspan="2" class="space"></td>
				</tr>
				<TR>
					<TD class=linebottomleft style="PADDING-RIGHT: 5px; PADDING-LEFT: 10px; PADDING-BOTTOM: 5px; PADDING-TOP: 5px" align=left width="745" bgColor=#ffffff colspan="2">
					<table cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td width="150"><img src="images/shop_mainproduct_img1.gif" width="177" height="149" border="0"></td>
						<td width="560">
						<table cellpadding="2" cellspacing="0" width="487">
						<TR>
							<TD>
							<div id=display3 style="BORDER-RIGHT: black 0px solid; PADDING-RIGHT: 0px; BORDER-TOP: black 0px solid; DISPLAY: <?=(strlen($plusnewrow)=="0"?"block":"none")?>; PADDING-LEFT: 0px; BACKGROUND: #ffffff; PADDING-BOTTOM: 0px; MARGIN-LEFT: 0px; BORDER-LEFT: black 0px solid; PADDING-TOP: 0px; BORDER-BOTTOM: black 0px solid">
							<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
							<TR>
								<? for($i=1;$i<=5;$i++){?>
									<TD align=middle><p align="center"><input type=radio id="idx_mnew_rows<?=$i?>" name=mnew_rows value="<?=$i?>" <? if ($mnew_rows==$i) echo "checked"; ?>><label style='cursor:hand; TEXT-DECORATION: none' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_mnew_rows<?=$i?>><?=($i)?>��</label></td>
								<?}?>
							</TR>
							</TABLE>
							</DIV>
							<div id=display4 style="BORDER-RIGHT: black 0px solid; PADDING-RIGHT: 0px; BORDER-TOP: black 0px solid; DISPLAY: <?=($plusnewrow=="Y"?"block":"none")?>; PADDING-LEFT: 0px; BACKGROUND: #ffffff; PADDING-BOTTOM: 0px; MARGIN-LEFT: 0px; BORDER-LEFT: black 0px solid; PADDING-TOP: 0px; BORDER-BOTTOM: black 0px solid">
							<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
							<TR>
								<TD align=middle><p align="center">&nbsp;</td>
								<? for($i=6;$i<=8;$i++){ ?>
									<TD align=middle><p align="center"><input type=radio id="idx_mnew_rows<?=$i?>" name=mnew_rows value="<?=$i?>" <? if ($mnew_rows==$i) echo "checked"; ?>><label style='cursor:hand; TEXT-DECORATION: none' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_mnew_rows<?=$i?>><?=($i)?>��</label></td>
								<? }?>
								<TD align=middle><p align="center">&nbsp;</td>
							</TR>
							</TABLE>
							</DIV>
							</td>
						</tr>
						<tr>
							<td width="483"><img src="images/product_num<?=$mnew_cols.($plusnewrow=="Y"?"A":"")?>.gif" align=absmiddle border="0" name=newimg></td>
						</tr>
						</table>
						</td>
					</tr>
					</table>
					</TD>
				</TR>
				<TR>
					<TD background="images/table_top_line.gif" width="153"></TD>
					<TD background="images/table_top_line.gif" width="607"></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr><td height=30></td></tr>
			<tr>
				<td>	
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/shop_mainproduct_stitle2.gif"  ALT=""></TD>
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
					<TD background="images/table_top_line.gif" width="153"></TD>
					<TD background="images/table_top_line.gif" width="607"></TD>
				</TR>
				<TR>
					<TD class="table_cell" width="760" colspan="2">
					<table cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td>&nbsp;<b>����(����)�� ��ǰ�� : <select name=mbest_cols onchange=changeimg(3,this) style="width:40px" class="select">
						<?
						for($i=1;$i<=8;$i++){
							echo "<option value=\"".$i."\"";
							if($i==$mbest_cols) echo " selected";
							echo ">".$i."��";
						}
						if($mbest_rows>5) $plusbestrow="Y";
						?>
						</select> <input type=checkbox id="idx_plusbestrow1" name=plusbestrow value="Y" <?=$plusbestrow=="Y"?"checked":""?> onclick=changeimg(3,document.form1.mbest_cols)> <label style='cursor:hand; TEXT-DECORATION: none' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_plusbestrow1>5��(����)�̻� �߰�.</label> ���κ� 5�� �������� ����</td>
					</tr>
					<tr>
						<td nowrap>
						<input type=radio id="idx_mbest_type0" name=mbest_type value="I" <? if ($mbest_type=="I") echo "checked"; ?>><label style='cursor:hand; TEXT-DECORATION: none' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_mbest_type0><span class="font_orange">�̹���A�� Ÿ�� ����<b>(����)</b></span></label>
						<input type=radio id="idx_mbest_type1" name=mbest_type value="D" <? if ($mbest_type=="D") echo "checked"; ?>><label style='cursor:hand; TEXT-DECORATION: none' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_mbest_type1>�̹���B�� Ÿ�� ����</label>
						<input type=radio id="idx_mbest_type2" name=mbest_type value="L" <? if ($mbest_type=="L") echo "checked"; ?>><label style='cursor:hand; TEXT-DECORATION: none' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_mbest_type2>����Ʈ�� Ÿ�� ����(�ټ��� ����)</label>
						</td>
					</tr>
					</table>
					</TD>
				</TR>
				<TR>
					<TD colspan="2" width="760" background="images/table_con_line.gif"></TD>
				</TR>
				<TR>
					<TD width="100%" colspan="2" class="space"></td>
				</tr>
				<TR>
					<TD class=linebottomleft style="PADDING-RIGHT: 5px; PADDING-LEFT: 10px; PADDING-BOTTOM: 5px; PADDING-TOP: 5px" align=left width="745" bgColor=#ffffff colspan="2">
					<table cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td width="150"><img src="images/shop_mainproduct_img2.gif" width="177" height="149" border="0"></td>
						<td width="560">
						<table cellpadding="2" cellspacing="0" width="487">
						<TR>
							<TD>
							<div id=display5 style="BORDER-RIGHT: black 0px solid; PADDING-RIGHT: 0px; BORDER-TOP: black 0px solid; DISPLAY: <?=(strlen($plusbestrow)=="0"?"block":"none")?>; PADDING-LEFT: 0px; BACKGROUND: #ffffff; PADDING-BOTTOM: 0px; MARGIN-LEFT: 0px; BORDER-LEFT: black 0px solid; PADDING-TOP: 0px; BORDER-BOTTOM: black 0px solid">
							<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
							<TR>
								<? for($i=1;$i<=5;$i++){?>
									<TD align=middle><p align="center"><input type=radio id="idx_mbest_rows<?=$i?>" name=mbest_rows value="<?=$i?>" <? if ($mbest_rows==$i) echo "checked"; ?>><label style='cursor:hand; TEXT-DECORATION: none' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_mbest_rows<?=$i?>><?=($i)?>��</label></td>
								<?}?>
							</TR>
							</TABLE>
							</DIV>
							<div id=display6 style="BORDER-RIGHT: black 0px solid; PADDING-RIGHT: 0px; BORDER-TOP: black 0px solid; DISPLAY: <?=($plusbestrow=="Y"?"block":"none")?>; PADDING-LEFT: 0px; BACKGROUND: #ffffff; PADDING-BOTTOM: 0px; MARGIN-LEFT: 0px; BORDER-LEFT: black 0px solid; PADDING-TOP: 0px; BORDER-BOTTOM: black 0px solid">
							<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
							<TR>
								<TD align=middle><p align="center">&nbsp;</td>
								<? for($i=6;$i<=8;$i++){ ?>
									<TD align=middle><p align="center"><input type=radio id="idx_mbest_rows<?=$i?>" name=mbest_rows value="<?=$i?>" <? if ($mbest_rows==$i) echo "checked"; ?>><label style='cursor:hand; TEXT-DECORATION: none' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_mbest_rows<?=$i?>><?=($i)?>��</label></td>
								<? }?>
								<TD align=middle><p align="center">&nbsp;</td>
							</TR>
							</TABLE>
							</DIV>
							</td>
						</tr>
						<tr>
							<td width="483"><img src="images/product_num<?=$mbest_cols.($plusbestrow=="Y"?"A":"")?>.gif" align=absmiddle border=0 name=bestimg></td>
						</tr>
						</table>
						</td>
					</tr>
					</table>
					</TD>
				</TR>
				<TR>
					<TD background="images/table_top_line.gif" width="153"></TD>
					<TD background="images/table_top_line.gif" width="607"></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr>
				<td height=30></td>
			</tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/shop_mainproduct_stitle3.gif" ALT=""></TD>
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
					<TD background="images/table_top_line.gif" width="153"></TD>
					<TD background="images/table_top_line.gif" width="607"></TD>
				</TR>
				<TR>
					<TD class="table_cell" width="760" colspan="2">
					<table cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td>&nbsp;<b>����(����)�� ��ǰ�� : <select name=mhot_cols onchange=changeimg(1,this) style="width:40px" class="select">
						<?
						for($i=1;$i<=8;$i++){
							echo "<option value=\"".$i."\"";
							if($i==$mhot_cols) echo " selected";
							echo ">".$i."��";
						}
						if($mhot_rows>5) $plusrow="Y";
						?>
						</select> <input type=checkbox id="idx_plusrow1" name=plusrow value="Y" <?=$plusrow=="Y"?"checked":""?> onclick=changeimg(1,document.form1.mhot_cols)> <label style='cursor:hand; TEXT-DECORATION: none' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_plusrow1>5��(����)�̻� �߰�.</label> ���κ� 5�� �������� ����.</td>
					</tr>
					<tr>
						<td nowrap>
						<input type=radio id="idx_mhot_type0" name=mhot_type value="I" <? if ($mhot_type=="I") echo "checked"; ?>><label style='cursor:hand; TEXT-DECORATION: none' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_mhot_type0><span class="font_orange">�̹���A�� Ÿ�� ����<b>(����)</b></span></label>
						<input type=radio id="idx_mhot_type1" name=mhot_type value="D" <? if ($mhot_type=="D") echo "checked"; ?>><label style='cursor:hand; TEXT-DECORATION: none' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_mhot_type1>�̹���B�� Ÿ�� ����</label>
						<input type=radio id="idx_mhot_type2" name=mhot_type value="L" <? if ($mhot_type=="L") echo "checked"; ?>><label style='cursor:hand; TEXT-DECORATION: none' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_mhot_type2>����Ʈ�� Ÿ�� ����(�ټ��� ����)</label>
						</td>
					</tr>
					</table>
					</TD>
				</TR>
				<TR>
					<TD colspan="2" width="760" background="images/table_con_line.gif"></TD>
				</TR>
				<TR>
					<TD width="100%" colspan="2" class="space"></td>
				</tr>
				<TR>
					<TD class=linebottomleft style="PADDING-RIGHT: 5px; PADDING-LEFT: 10px; PADDING-BOTTOM: 5px; PADDING-TOP: 5px" align=left width="745" bgColor=#ffffff colspan="2">
					<table cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td width="150"><img src="images/shop_mainproduct_img3.gif" width="177" height="149" border="0"></td>
						<td width="560">
						<table cellpadding="2" cellspacing="0" width="487">
						<TR>
							<TD>
							<DIV id=display1 style="BORDER-RIGHT: black 0px solid; PADDING-RIGHT: 0px; BORDER-TOP: black 0px solid; DISPLAY: <?=(strlen($plusrow)=="0"?"block":"none")?>; PADDING-LEFT: 0px; BACKGROUND: #ffffff; PADDING-BOTTOM: 0px; MARGIN-LEFT: 0px; BORDER-LEFT: black 0px solid; PADDING-TOP: 0px; BORDER-BOTTOM: black 0px solid">
							<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
							<TR>
								<? for($i=1;$i<=5;$i++){?>
									<TD align=middle><p align="center"><input type=radio id="idx_mhot_rows<?=$i?>" name=mhot_rows  value="<?=$i?>" <? if ($mhot_rows==$i) echo "checked"; ?>><label style='cursor:hand; TEXT-DECORATION: none' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_mhot_rows<?=$i?>><?=($i)?>��</label></td>
								<?}?>
							</TR>
							</TABLE>
							</DIV>
							<DIV id=display2 style="BORDER-RIGHT: black 0px solid; PADDING-RIGHT: 0px; BORDER-TOP: black 0px solid; DISPLAY: <?=($plusrow=="Y"?"block":"none")?>; PADDING-LEFT: 0px; BACKGROUND: #ffffff; PADDING-BOTTOM: 0px; MARGIN-LEFT: 0px; BORDER-LEFT: black 0px solid; PADDING-TOP: 0px; BORDER-BOTTOM: black 0px solid">
							<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
							<TR>
								<TD align=middle><p align="center">&nbsp;</td>
								<? for($i=6;$i<=8;$i++){ ?>
									<TD align=middle><p align="center"><input type=radio id="idx_mhot_rows<?=$i?>" name=mhot_rows  value="<?=$i?>" <? if ($mhot_rows==$i) echo "checked"; ?>><label style='cursor:hand; TEXT-DECORATION: none' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_mhot_rows<?=$i?>><?=($i)?>��</label></td>
								<? }?>
								<TD align=middle><p align="center">&nbsp;</td>
							</TR>
							</TABLE>
							</DIV>
							</td>
						</tr>
						<tr>
							<td width="483"><img src="images/product_num<?=$mhot_cols?><?=($plusrow=="Y"?"A":"")?>.gif" align=absmiddle border=0 name=productimg></td>
						</tr>
						</table>
						</td>
					</tr>
					</table>
					</TD>
				</TR>
				<TR>
					<TD background="images/table_top_line.gif" width="153"></TD>
					<TD background="images/table_top_line.gif" width="607"></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr>
				<td height=10></td>
			</tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
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
						<TD class="notice_blue" valign="top" width="745" colspan="2"></TD>
					</TR>
					<TR>
						<TD class="notice_blue" valign="top">&nbsp;</TD>
						<TD width="100%" class="font_blue" style="padding-right:7pt; padding-left:7pt;">
						<img src="images/mainproduct_imageA.gif" border="0"> <b>�̹���A�� Ÿ��</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src="images/mainproduct_imageB.gif" border="0"> <b>�̹���B�� Ÿ��</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src="images/mainproduct_list.gif" border="0"> <b>����Ʈ�� Ÿ��</b>
						</TD>
					</TR>
					<TR>
						<TD class="notice_blue" valign="top">&nbsp;</TD>
						<TD width="100%" class="font_blue" style="padding-right:7pt; padding-left:7pt;">
						<table cellpadding="0" cellspacing="0" width="100%">
						<tr>
							<td width="10" class="font_blue">1)&nbsp;</td>
							<td width="100%" class="font_blue">�ű�, �α�, ��õ, Ư����ǰ�� ������ �������� �������� �����˴ϴ�.</td>
						</tr>
						<tr>
							<td width="10" class="font_blue">2)</td>
							<td width="100%" class="font_blue"><a href="javascript:parent.topframe.GoMenu(2,'design_eachmain.php');"><span class="font_blue">�����ΰ��� > ���������� - ���� �� ���ϴ� > ���κ��� �ٹ̱�</span></a> ���� ���� ������ ���浵 �����մϴ�.</td>
						</tr>
						<tr>
							<td width="10" class="font_blue">3)</td>
							<td width="100%" class="font_blue">������������ ���κ��� �ٹ̱⿡�� [Ư����ǰ]�� ��ġ�� �����Ӱ� �̵� �� �� �ֽ��ϴ�.</td>
						</tr>
						</table>
						</TD>
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
				</table>
				</td>
			</tr>
			<tr>
				<td height=30></td>
			</tr>



			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/shop_mainproduct_stitle4.gif" ALT=""></TD>
					<TD width="100%" background="images/shop_basicinfo_stitle_bg.gif">&nbsp;</TD>
					<TD><IMG SRC="images/shop_basicinfo_stitle_end.gif" WIDTH=10 HEIGHT=31 ALT=""></TD>
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
					<TD width="100%" class="notice_blue">Ư�� ��ǰ�� ���� �޴����� ǥ�ð��� ������ �մϴ�.</TD>
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
					<TD background="images/table_top_line.gif" width="153"><img src="images/table_top_line.gif"></TD>
					<TD background="images/table_top_line.gif" width="607"></TD>
				</TR>
				<TR>
					<TD align=left width="745" bgColor=#ffffff colspan="2">
					<table cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td width="150" class="table_cell"><img src="images/shop_mainproduct_img4.gif" border="0"></td>
						<td width="560" class="td_con1">
							<table cellpadding="0" cellspacing="0">
								<tr>
									<td>�� Ư����ǰ ǥ�ð���</td>
									<td style="padding-left:10px;"><select name=main_special_num style="width:40px" class="select">
									<?
										for ($i=1;$i<=10;$i++) {
											if ($i==$main_special_num) {
									?>
											<option value="<? echo $i ?>" selected><? echo $i ?>
									<?
											} else {
									?>
											<option value="<? echo $i ?>"><? echo $i ?>
									<?
											}
										}
									?>
										</select>��
									</td>
									<td style="padding-left:10px;"><p align="left">&nbsp;<input type=radio id="idx_main_special_type1" name=main_special_type value="N" <? if($main_special_type == "N") echo "checked ";?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_main_special_type1>������ ��ǰ����Ʈ</label></td>
								</tr>
							</table>
							<div style="margin-top:10px;">* <span class="font_blue"><a href="javascript:parent.topframe.GoMenu(2,'design_eachmain.php');">�����ΰ��� > ���������� - ���� �� ���ϴ� > ���κ��� �ٹ̱�</a></span> ���� ���� �������� �����մϴ�.<br>* �������װ� ������, ���� ������ ������ �����ȭ�鿡�� �ڵ����� ��µ��� �ʽ��ϴ�.</div>
						</td>
					</tr>
					</table>
					</TD>
				</TR>
				<TR>
					<TD colspan=2 background="images/table_top_line.gif"></TD>
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
					<TD><IMG SRC="images/shop_saleout_stitle1.gif"  ALT=""></TD>
					<TD width="100%" background="images/shop_basicinfo_stitle_bg.gif">&nbsp;</TD>
					<TD><IMG SRC="images/shop_basicinfo_stitle_end.gif" WIDTH=10 HEIGHT=31 ALT=""></TD>
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
					<TD width="100%" class="notice_blue">ī�װ� �������� ������ ��ǰ ����Ʈ�� ǥ�ð����� ���� �Ͻ� �� �ֽ��ϴ�.</TD>
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
					<TD background="images/table_top_line.gif" width="153"></TD>
					<TD background="images/table_top_line.gif" width="607"></TD>
				</TR>
				<TR>
					<TD align=left width="745" bgColor=#ffffff colspan="2">
					<table cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td width="150" class="table_cell"><img src="images/shop_saleout_img3.gif" width="177" height="149" border="0"></td>
						<td width="560" class="td_con1">ī�װ� �Ϲݻ�ǰ ������ �ϰ� ���� : <select name=prlist_num class="select" style="width:40px">
<?
			for ($i=8;$i<=50;$i++) {
				if ($i==$prlist_num) {
?>
				<option value="<? echo $i ?>" selected><? echo $i ?>
<?
				} else {
?>
				<option value="<? echo $i ?>"><? echo $i ?>
<?
				}
			}
?>
						</select>��
						* ��ϵ� ��ǰ���� ���� �������� ���� �Է��� ��� �ڵ����� �������� �߰��˴ϴ�. 1[2][3][4]<br>
						* ��� ī�װ��� �ϰ� ����˴ϴ�.<br>
						* ī�װ��� �ű�, �α�, ��õ ��ǰ�� �������� ī�װ����� ���� ������ �� �ֽ��ϴ�.</td>
					</tr>
					</table>
					</TD>
				</TR>
				<TR>
					<TD colspan=2 background="images/table_top_line.gif"></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr>
				<td height="10"></td>
			</tr>
			
			<tr>
				<td align="center"><a href="javascript:CheckForm('up');"><img src="images/botteon_save.gif" width="113" height="38" border="0"></a></td>
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
					<TD COLSPAN=3 width="100%" valign="top" bgcolor="white" style="padding-top:8pt; padding-bottom:8pt; padding-left:4pt;"  class="menual_bg">
					<table cellpadding="0" cellspacing="0" width="99%">
					<tr>
						<td width="163"><img src="images/shop_mainproduct_img5.gif" border="0"></td>
						<td  valign="top">
						<table cellpadding="0" cellspacing="0" width="100%">
						<tr>
							<td width="20" align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
							<td width="100%"><b>��ġ���� ����<br></b></td>
						</tr>
						<tr>
							<td width="20" align="right" height="38">&nbsp;</td>
							<td width="100%" class="space_top" height="38">- <a href="javascript:parent.topframe.GoMenu(2,'design_eachmain.php');"><span class="font_blue">�����ΰ��� > ���������� - ���� �� ���ϴ� > ���κ��� �ٹ̱�</span></a> ���� ���� ������ ���� �� ��ġ��<br><b>&nbsp;&nbsp;</b>������ �� �ֽ��ϴ�.</td>
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
<?=$onload?>

<? INCLUDE "copyright.php"; ?>