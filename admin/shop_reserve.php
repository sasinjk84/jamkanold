<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
INCLUDE ("access.php");

####################### ������ ���ٱ��� check ###############
$PageCode = "sh-3";
$MenuCode = "shop";
if (!$_usersession->isAllowedTask($PageCode)) {
	INCLUDE ("AccessDeny.inc.php");
	exit;
}
#########################################################

$type=$_POST["type"];
$up_reserveuse=$_POST["up_reserveuse"];
$up_money=$_POST["up_money"];
$up_remoney=$_POST["up_remoney"];
$up_reprice=$_POST["up_reprice"];
$up_reserve_join=$_POST["up_reserve_join"];
$up_canuse=$_POST["up_canuse"];
$up_reserve_maxprice=$_POST["up_reserve_maxprice"];
$up_usecheck=$_POST["up_usecheck"];
$up_reservemoney=$_POST["up_reservemoney"];
$up_reservepercent=$_POST["up_reservepercent"];
$up_coupon_ok=$_POST["up_coupon_ok"];
//$up_coupon_limit_ok = $_POST["up_coupon_limit_ok"];
$up_rcall_type=$_POST["up_rcall_type"];

$cr_ok = $_POST['cr_ok'];
$cr_maxprice = $_POST['cr_maxprice'];
$cr_unit = $_POST['cr_unit'];
$cr_limit = $_POST['cr_limit'];
$cr_sdate = $_POST['cr_sdate'];
$cr_edate = $_POST['cr_edate'];

if($up_usecheck==1) $reserve_limit=0;
else if($up_usecheck==2) $reserve_limit=$up_reservemoney;
else if($up_usecheck==3) $reserve_limit=-$up_reservepercent;
else $reserve_limit=0;

if ($type=="up") {
	if($up_rcall_type=="Y" && $up_money=="Y") $up_rcall_type="Y";
	else if($up_rcall_type=="N" && $up_money=="Y") $up_rcall_type="N";
	else if($up_rcall_type=="Y" && $up_money=="N") $up_rcall_type="M";
	else if($up_rcall_type=="N" && $up_money=="N") $up_rcall_type="T";

	if($up_remoney=="Y") $reserve_useadd=-1;
	else if($up_remoney=="U") $reserve_useadd=-2;
	else if($up_remoney=="A") $reserve_useadd=0;
	else $reserve_useadd = $up_reprice;

	if ($up_reserveuse == "N") {#������ ������� ����
		$sets = " reserve_join = 0, reserve_maxuse = -1 ";
	} else {
		$sets = " reserve_join = '".$up_reserve_join."', reserve_maxuse = '".$up_canuse."' ";
	}
	$sql = "UPDATE tblshopinfo SET ";
	$sql.= "rcall_type		= '".$up_rcall_type."', ";
	$sql.= "reserve_limit	= '".$reserve_limit."', ";
	$sql.= "reserve_maxprice= '".$up_reserve_maxprice."', ";
	$sql.= "reserve_useadd	= '".$reserve_useadd."', ";
	$sql.= $sets.", ";

	$sql.= "cr_ok='{$cr_ok}', ";
	$sql.= "cr_maxprice='{$cr_maxprice}', ";
	$sql.= "cr_unit='{$cr_unit}', ";
	$sql.= "cr_limit='{$cr_limit}', ";
	$sql.= "cr_sdate='{$cr_sdate}', ";
	$sql.= "cr_edate='{$cr_edate}', ";
	//$sql.= "coupon_limit_ok = '".$up_coupon_limit_ok."' , ";
	$sql.= "coupon_ok		= '".$up_coupon_ok."' ";
	mysql_query($sql,get_db_conn());
	DeleteCache("tblshopinfo.cache");
	$onload="<script>alert('������/���� ���� ������ �Ϸ�Ǿ����ϴ�.');</script>\n";

	$log_content = "## �����ݼ��� ## - ��뿩�� : $up_reserveuse, ���������� : $up_reserve_join, �������� $up_canuse �̻� ��밡��, ����:$up_coupon_ok, �߰���������:$reserve_useadd";
	ShopManagerLog($_ShopInfo->getId(),$connect_ip,$log_content);
}

$sql2 = "SELECT rcall_type,reserve_limit,reserve_maxprice,reserve_useadd,reserve_maxuse,reserve_join,coupon_ok,coupon_limit_ok, cr_ok, cr_maxprice, cr_unit, cr_limit, cr_sdate, cr_edate ";
$sql2.= "FROM tblshopinfo ";
$result = mysql_query($sql2,get_db_conn());
if ($row = mysql_fetch_object($result)) {
	$reserve_join = $row->reserve_join;
	if ($row->reserve_maxuse ==-1) {
		$reserveuse = "N";
		$canuse = 0;
	} else {
		$reserveuse = "Y";
		$canuse = abs($row->reserve_maxuse);
	}
	if ($row->rcall_type=="Y") {
		$rcall_type = $row->rcall_type;
		$money="Y";
	} else if ($row->rcall_type=="N") {
		$rcall_type = $row->rcall_type;
		$money="Y";
	} else if ($row->rcall_type=="M") {
		$rcall_type="Y";
		$money="N";
	} else {
		$rcall_type="N";
		$money="N";
	}
	$reserve_limit = $row->reserve_limit;
	$reserve_maxprice = $row->reserve_maxprice;
	$coupon_ok = $row->coupon_ok;
	$coupon_limit_ok = $row->coupon_limit_ok;

	if($row->reserve_useadd==-1){
		$remoney="Y";
		$reprice="0";
	}else if($row->reserve_useadd==-2){
		$remoney="U";
		$reprice="0";
	}else if($row->reserve_useadd==0){
		$remoney="A";
		$reprice="0";
	}else {
		$remoney="N";
		$reprice=$row->reserve_useadd;
	}

	$cr_ok = $row->cr_ok;
	$cr_maxprice = $row->cr_maxprice;
	$cr_limit = $row->cr_limit;
	$cr_unit = $row->cr_unit;
	$cr_sdate = $row->cr_sdate;
	$cr_edate = $row->cr_edate;
	if($cr_edate==0) $cr_edate= '';
}
mysql_free_result($result);

${"check_reserveuse".$reserveuse} = "checked";
${"check_money".$money} = "checked";
${"check_remoney".$remoney} = "checked";
${"check_coupon_ok".$coupon_ok} = "checked";
${"check_coupon_limit_ok".$coupon_limit_ok} = "checked";
${"check_rcall_type".$rcall_type} = "checked";
${"cr_ok".$cr_ok} = "checked";
?>

<? INCLUDE "header.php"; ?>

<script type="text/javascript" src="lib.js.php"></script>
<script language="JavaScript">
function CheckForm() {
	var form = document.form1;
	if(form.up_remoney[3].checked==true){
		if(isNaN(form.up_reprice.value)){
			alert('���ڸ� �Է��Ͻñ� �ٶ��ϴ�.');
			form.up_reprice.focus();
			return;
		}
		if(parseInt(form.up_reprice.value)<=0){
			alert('�ݾ��� 0�� �̻� �Է��ϼž� �մϴ�.');
			form.up_reprice.focus();
			return;
		}
	}

	if(isNaN(form.cr_maxprice.value)){
		alert('���ڸ� �Է��Ͻñ� �ٶ��ϴ�.');
		form.cr_max_price.focus();
		return;
	}

	form.type.value="up";
	form.submit();
}

function checkreserve(val){
	for(i=0;i<3;i++){
		if(i==(val-1)) {
			document.form1.up_usecheck[i].checked=true;
		} else {
			document.form1.up_usecheck[i].checked=false;
		}
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
				<td height="28" class="link" align="left" background="images/con_link_bg.gif"><img src="images/top_link_house.gif" border="0" valign="absmiddle">������ġ : �������� &gt; ���θ� � ���� &gt; <span class="2depth_select">������/���� ����</span></td>
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
					<TD><IMG SRC="images/shop_reserve_title.gif" WIDTH="208" HEIGHT=32 ALT=""></TD>
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
					<TD width="100%" class="notice_blue"><p>�����ڿ� ���� ������/���� ���� ���ǰ� ��밡�� ����, �⺻ ���޺����� ������ �� �ֽ��ϴ�.</p></TD>
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
					<TD><IMG SRC="images/shop_reserve_stitle1.gif" WIDTH="151" HEIGHT=31 ALT=""></TD>
					<TD width="100%" background="images/shop_basicinfo_stitle_bg.gif">&nbsp;</TD>
					<TD><IMG SRC="images/shop_basicinfo_stitle_end.gif" WIDTH=10 HEIGHT=31 ALT=""></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr><td height=3></td></tr>
			<form name=form1 action="<?=$_SERVER[PHP_SELF]?>" method=post>
			<input type=hidden name=type>
			<tr>
				<td>
				<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
				<TR>
					<TD height="1" colspan="2" bgcolor="#B9B9B9"></TD>
				</TR>
				<TR>
					<TD class="table_cell" width="145"><img src="images/icon_point2.gif" width="8" height="11" border="0">���Ž� ������ ��뿩��</TD>
					<TD class="td_con1"><input type=radio id="idx_reserveuse1" name=up_reserveuse value="Y" <?=$check_reserveuseY?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_reserveuse1>�����: ������ �������� ���� ������ ����</label><br>
					<input type=radio id="idx_reserveuse2" name=up_reserveuse value="N" <?=$check_reserveuseN?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_reserveuse2>������ : �ֹ��ÿ� ��밡���� ���� ������ �� ���ݾ� �Է��׸��� ��ǥ��</label><br>
					</TD>
				</TR>
				<TR>
					<TD height="1" colspan="2" bgcolor="#B9B9B9"></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr>
				<td>
				<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
				<tr>
					<td height=3 colspan=2></td>
				</tr>
				<tr>
					<td colspan=2>
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
							<TD class="notice_blue" valign="top">&nbsp;</TD>
							<TD width="100%" class="space"><span class=font_blue><b>������ ��� ���</b><br>
							- <span class="font_orange">ī�װ��� ������ ���</span> : <a href="javascript:parent.topframe.GoMenu(4,'product_reserve.php');"><span class="font_blue">��ǰ���� > ��ǰ �ϰ����� > ������ �ϰ�����</span></a>(�� �Ǵ� % ������ �ϰ����)<br>
							- <span class="font_orange">��ǰ�� ������ ���</span>&nbsp;&nbsp;&nbsp;: <a href="javascript:parent.topframe.GoMenu(4,'product_allupdate.php');"><span class="font_blue">��ǰ���� > ��ǰ �ϰ����� > ��ǰ �ϰ� �������</span></a>(�� ������ ��ǰ�� �������)<br>
							&nbsp;&nbsp;
							&nbsp;&nbsp;
							&nbsp;<b>&nbsp;&nbsp;</b><a href="javascript:parent.topframe.GoMenu(4,'product_register.php');"><span class="font_blue">��ǰ���� >ī�װ�/��ǰ���� > ��ǰ ��� �� ����</span></a> (�� ������ ��ǰ�� �������)<br>
							- <span class="font_orange">�⺻������+���ݰ����� �߰������� ���</span> : <a href="javascript:parent.topframe.GoMenu(1,'shop_payment.php');"><span class="font_blue">�������� > ���θ� � ���� > ��ǰ �������� ��ɼ���</span></a> (10������ ����)<br>
							<b>&nbsp;&nbsp;</b>�⺻������(��ǰ�� �Է��� ������)�� 0��+ ���ݰ����� �߰��������� 10% = ��� ��ǰ�� ������ 10% ����˴ϴ�.<br>
							<b>&nbsp;&nbsp;</b>��, �� ��� ���� �����ÿ��� �����Ǹ� ī������ÿ��� ������ 0���Դϴ�.
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
					</TABLE>
					</td>
				</tr>
				</table>
				</td>
			</tr>
			<tr><td height="20"></td></tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/shop_reserve_stitle2.gif" WIDTH="151" HEIGHT=31 ALT=""></TD>
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
					<TD height="1" colspan="2" bgcolor="#B9B9B9"></TD>
				</TR>
				<TR>
					<TD class="table_cell" width="145"><img src="images/icon_point2.gif" width="8" height="11" border="0">��� ������ ��������</TD>
					<TD class="td_con1"><input type=radio id="idx_money1" name=up_money value="Y" <?=$check_moneyY?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_money1>��� �������ܿ��� ��� ����(����)</label>  &nbsp;<input type=radio id="idx_money2" name=up_money value="N" <?=$check_moneyN?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_money2>���ݰ����ø� ��밡��</label></TD>
				</TR>
				<TR>
					<TD height="1" colspan="2" bgcolor="#B9B9B9"></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr><td height="30"></td></tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/shop_reserve_stitle3.gif" border="0"></TD>
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
					<TD height="1" colspan="2" bgcolor="#B9B9B9"></TD>
				</TR>
				<TR>
					<TD class="table_cell" width="145"><img src="images/icon_point2.gif" width="8" height="11" border="0">������ ����Ͽ�<br>&nbsp;&nbsp;������ �߰����� ����</TD>
					<TD class="td_con1">
					<table cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td width="585"><input type=radio id="idx_remoney1" name=up_remoney value="Y" <?=$check_remoneyY?> onclick='document.form1.up_reprice.disabled=true;'><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_remoney1>������ ����Ͽ� �����ص� �������������� ���� �߰�</label></td>
					</tr>
					<tr>
						<td width="585"><input type=radio id="idx_remoney2" name=up_remoney value="U" <?=$check_remoneyU?> onclick='document.form1.up_reprice.disabled=true;'><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_remoney2>����� �������� ������ ���űݾ� ��� ����</label><span class="font_blue">(���űݾ�-���������)</span></td>
					</tr>
					<tr>
						<td width="585"><input type=radio id="idx_remoney3" name=up_remoney value="A" <?=$check_remoneyA?> onclick='document.form1.up_reprice.disabled=true;'><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_remoney3>�������� ����Ͽ� ������ ��� ������������ �߰��� �ȵ�</label><span class="font_blue">(ȸ�� ��޺� �߰������� ������ ����)</span></td>
					</tr>
					<tr>
						<td width="585"><input type=radio id="idx_remoney4" name=up_remoney value="N" <?=$check_remoneyN?> onclick='document.form1.up_reprice.disabled=false;'><input type=text name=up_reprice value="<?=$reprice?>" size=8 maxlength=6 class="input"><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_remoney4>�� �̻� ������ ���� �߰� �����ȵ�</label></td>
					</tr>
					<tr>
						<td width="585" class="font_orange" style="padding-top:6pt;">&nbsp;* ���� �������� ����Ͽ� <b>���Ž� �߰��������θ� ����</b>�Ͻ� �� �ֽ��ϴ�.</td>
					</tr>
					</table>
					<? if($remoney!="N") echo "<script>document.form1.up_reprice.disabled=true;</script>"; ?>
					</TD>
				</TR>
				<TR>
					<TD height="1" colspan="2" bgcolor="#B9B9B9"></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr><td height="30"></td></tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/shop_reserve_stitle4.gif" WIDTH="151" HEIGHT=31 ALT=""></TD>
					<TD width="100%" background="images/shop_basicinfo_stitle_bg.gif">&nbsp;</TD>
					<TD><IMG SRC="images/shop_basicinfo_stitle_end.gif" WIDTH=10 HEIGHT=31 ALT=""></TD>
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
					<TD width="100%" class="notice_blue"><p>1) ȸ���� ������ ������� �̻��� �Ǹ� �ֹ����� �ڵ����� [������ �Է�â] �����˴ϴ�.<br>2) ȸ���� ��밡���� ������������ <B>1ȸ ����ѵ�</B>�� <B>�ݾ�</B> �Ǵ� <B>����(%)</B>�� �����Ͻ� �� �ֽ��ϴ�.</p></TD>
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
					<TD height="1" colspan="2" bgcolor="#B9B9B9"></TD>
				</TR>
				<TR>
					<TD class="table_cell" width="145"><img src="images/icon_point2.gif" width="8" height="11" border="0">�ű�ȸ�� ����������</TD>
					<TD class="td_con1"><select name=up_reserve_join class="select_selected"  style="width:100px">
						<option  <? if($reserve_join==0) echo "selected "; ?> value=0>����
<?
	$i = 100;
	while($i < 50001) {
		unset($r_select);
		if($reserve_join==$i) {
			$r_select = "selected";
		}
		echo "<option  value=\"".$i."\" ".$r_select.">".number_format($i)."</option>\n";
		if($i<500) { $i = $i +100; }
		elseif($i<2000) { $i = $i +500; }
		elseif($i<5000) { $i = $i +1000; }
		else { $i = $i +5000; }
	}
?>
						</select> ��&nbsp;&nbsp;<span class="font_orange">* ȸ������ ��� �����Ǵ� ������</span></TD>
				</TR>
				<TR>
					<TD colspan="2"  background="images/table_con_line.gif"><img src="images/table_con_line.gif" width="4" height="1" border="0"></TD>
				</TR>
				<TR>
					<TD class="table_cell" width="145"><img src="images/icon_point2.gif" width="8" height="11" border="0">��� ������ ���� ������</TD>
					<TD class="td_con1" ><select name=up_canuse class="select_selected" style="width:100px">
<?
	$i = 0;
	while($i < 200001) {
		unset($r_select);
		if($canuse==$i){
			$r_select = "selected";
		}
		echo "<option value=\"".$i."\" ".$r_select.">".number_format($i)."</option>\n";
		if($i<1000) { $i = $i +100; }
		else if($i<10000) { $i = $i +1000; }
		elseif($i<20000) { $i = $i +5000; }
		elseif($i<100000) { $i = $i +10000; }
		else { $i = $i +20000; }
	}
?>
						</select> �� �̻� ������ ��쿡�� ��밡��</TD>
				</TR>
				<TR>
					<TD colspan="2"  background="images/table_con_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell" width="145"><img src="images/icon_point2.gif" width="8" height="11" border="0">��� ������ ��ǰ ���ž�</TD>
					<TD class="td_con1" ><input type=text name=up_reserve_maxprice value="<?=$reserve_maxprice?>" size=10 maxlength=7 class="input"> �� �̻� ���Ž� ������ ��밡��(��ۺ� ����)</TD>
				</TR>
				<TR>
					<TD colspan="2"  background="images/table_con_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell" width="145"><img src="images/icon_point2.gif" width="8" height="11" border="0">������ 1ȸ ����ѵ�</TD>
					<TD class="td_con1" >
					<table cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td width="585"><input type=checkbox name=up_usecheck value=1 <?=($reserve_limit==0?"checked":"")?> onclick="checkreserve('1')"> ���� ������ ��ü�� 1ȸ�� ��밡��</td>
					</tr>
					<tr>
						<td width="585"><input type=checkbox name=up_usecheck value=2 <?=($reserve_limit>0?"checked":"")?> onclick="checkreserve('2')"> <B>����������</B>�� <select name=up_reservemoney class="select">
<?
	$i = 1000;
	while($i < 200001) {
		unset($r_select);
		if($reserve_limit==$i) {
			$r_select = "selected";
		}
		echo "<option value=\"".$i."\" ".$r_select.">".$i."</option>\n";
		if($i<10000) { $i = $i +1000; }
		elseif($i<20000) { $i = $i +5000; }
		elseif($i<100000) { $i = $i +10000; }
		else { $i = $i +20000; }
	}
?>
						</select> <B>��</B> ���� ��밡��</td>
					</tr>
					<tr>
						<td width="585"><IMG height=5 width=0><input type=checkbox name=up_usecheck value=3 <?=($reserve_limit<0?"checked":"")?> onclick="checkreserve('3')"> <B>��ǰ���ž�</B>�� <select name=up_reservepercent class="select">
<?
	for($i=1;$i<=100;$i++){
		unset($r_select);
		if(abs($reserve_limit)==$i) {
			$r_select = "selected";
		}
		echo "<option value=\"".$i."\" ".$r_select.">".$i."</option>\n";
	}
?>
						</select> <B>%</B> ���� ��밡��</td>
					</tr>
					</table>
					</TD>
				</TR>
				<TR>
					<TD height="1" colspan="2" bgcolor="#B9B9B9"></TD>
				</TR>
				</table>
				</td>
			</tr>
			<tr><td height="30"></td></tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/shop_reserve_stitle7.gif" HEIGHT=31 ALT=""></TD>
					<TD width="100%" background="images/shop_basicinfo_stitle_bg.gif">&nbsp;</TD>
					<TD><IMG SRC="images/shop_basicinfo_stitle_end.gif" WIDTH=10 HEIGHT=31 ALT=""></TD>
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
					<TD width="100%"><span class="font_orange"><b>* ������ȯ ��뿩�θ� �����Ͻ� �� �ֽ��ϴ�.</b></span></TD>
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
					<TD height="1" colspan="2" bgcolor="#B9B9B9"></TD>
				</TR>
				<TR>
					<TD class="table_cell" width="145"><img src="images/icon_point2.gif" width="8" height="11" border="0">������ȯ ��뿩��</TD>
					<TD class="td_con1"><input type=radio id="idx_coupon_okc" name=cr_ok value="Y" <?=$cr_okY?>><label style='CURSOR: hand; TEXT-DECORATION: none' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_coupon_okc>�����</label>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type=radio id="idx_coupon_okcc" name=cr_ok value="N" <?=$cr_okN?>><label style="CURSOR: hand; TEXT-DECORATION: none" onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_coupon_okcc>������</label></TD>
				</TR>
				<TR>
					<TD colspan="2"  background="images/table_con_line.gif"><img src="images/table_con_line.gif" width="4" height="1" border="0"></TD>
				</TR>
				<TR>
					<TD class="table_cell" width="145"><img src="images/icon_point2.gif" width="8" height="11" border="0">��밡�� �ݾ׼���</TD>
					<TD class="td_con1" ><input type=text name=cr_maxprice value="<?=$cr_maxprice?>" size=10 maxlength=7 class="input">�� �̻� ������ ��쿡�� ��ȯ����</TD>
				</TR>
				<TR>
					<TD colspan="2"  background="images/table_con_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell" width="145"><img src="images/icon_point2.gif" width="8" height="11" border="0">��ȯ����</TD>
					<TD class="td_con1" >
					    <select name=cr_unit class="select_selected" style="width:100px">
                          <option value="1">��</option>
						  <option value="10">�ʿ�</option>
						  <option value="100">���</option>
						  <option value="1000">õ��</option>
						  <option value="10000">����</option>
						</select>
						<script>document.form1.cr_unit.value='<?=$cr_unit?>';</script>
					</TD>
				</TR>
				<TR>
					<TD colspan="2"  background="images/table_con_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell" width="145"><img src="images/icon_point2.gif" width="8" height="11" border="0">��û�����ֱ⼳��</TD>
					<TD class="td_con1" >
					    <select name=cr_limit class="select_selected" style="width:100px">
                          <option value="0">���Ѿ���</option>
						  <option value="1">��1ȸ</option>
						  <option value="2">��1ȸ</option>
						  <option value="3">��1ȸ</option>
						</select>
						<script>document.form1.cr_limit.value='<?=$cr_limit?>';</script>
					</TD>
				</TR>
				<TR>
					<TD colspan="2"  background="images/table_con_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell" width="145"><img src="images/icon_point2.gif" width="8" height="11" border="0">�ԱݿϷ�����</TD>
					<TD class="td_con1" >��û �� <input type=text name=cr_sdate value="<?=$cr_sdate?>" size=10 maxlength=7 class="input">�� ~ <input type=text name=cr_edate value="<?=$cr_edate?>" size=10 maxlength=7 class="input">�� �̳�</TD>
				</TR>
				<TR>
					<TD height="1" colspan="2" bgcolor="#B9B9B9"></TD>
				</TR>
				</table>
				</td>
			</tr>
			<tr>
				<td height="20"></td>
			</tr>
			<tr>
				<td><IMG SRC="images/shop_reserve_stitle5.gif" border="0"></td>
			</tr>
			<tr>
				<td height="5"></td>
			</tr>
			<tr>
				<td>
					<table cellpadding="0" cellspacing="0" width="100%">
						<TR>
							<TD bgcolor="#B9B9B9" height="1"></TD>
						</TR>
						<tr>
							<td>
								<table cellpadding="0" cellspacing="0" width="100%">
								<col width="150"></col>
								<col width=""></col>
									<TR>
										<TD class="table_cell"><b><img src="images/icon_point2.gif" border="0"></b>���� ���� ����</TD>
										<TD class="td_con1"><input type=radio id="idx_coupon_ok1" name=up_coupon_ok value="Y" <?=$check_coupon_okY?>><label style='CURSOR: hand; TEXT-DECORATION: none' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_coupon_ok1>���� ���</label>  <input type=radio id="idx_coupon_ok2" name=up_coupon_ok value="N" <?=$check_coupon_okN?>><label style="CURSOR: hand; TEXT-DECORATION: none" onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_coupon_ok2>���� ���Ұ�</label>
										</TD>
									</TR>
									<!--
									<TR>
										<TD class="table_cell"><b><img src="images/icon_point2.gif" border="0"></b>���� �������</TD>
										<TD class="td_con1"><input type=radio id="idx_coupon_limit_ok1" name=up_coupon_limit_ok value="Y" <?=$check_coupon_limit_okY?>><label style='CURSOR: hand; TEXT-DECORATION: none' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_coupon_limit_ok1>���� �ֹ��� ���� ���� ���</label>  <input type=radio id="idx_coupon_limit_ok2" name=up_coupon_limit_ok value="N" <?=$check_coupon_limit_okN?>><label style="CURSOR: hand; TEXT-DECORATION: none" onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_coupon_limit_ok2>���� �ֹ��� ���� ���� ���Ұ�</label>
										</TD>
									</TR>
									-->
								</table>
							</td>
						</tr>
						<TR>
							<TD bgcolor="#B9B9B9" height="1"></TD>
						</TR>
					</table>


				</td>
			</tr>
			<tr><td height="10"></td></tr>
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
					<TD width="100%" class="notice_blue"><p>1) <a href="javascript:parent.topframe.GoMenu(7,'market_couponnew.php');"><span class="font_blue">���������� > �������� ���� ����</span></a> ���� ���� ����, �߱޴��, �߱���ȸ�� �� �� �ֽ��ϴ�.<br>2) ������ �����ߴ��� �������Ұ��� ��� ȸ������ ����� �� �����ϴ�.</p></TD>
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
				<td height="30"></td>
			</tr>
			<tr>
				<td><IMG SRC="images/shop_reserve_stitle6.gif" border="0"></td>
			</tr>
			<tr>
				<td height="5"></td>
			</tr>
			<tr>
				<td>
					<table cellpadding="0" cellspacing="0" width="100%">
						<TR>
							<TD bgcolor="#B9B9B9" height="1"></TD>
						</TR>
						<tr>
							<td>
								<table cellpadding="0" cellspacing="0" width="100%">
								<col width="150"></col>
								<col width=""></col>
									<TR>
										<TD class="table_cell"><b><img src="images/icon_point2.gif" border="0"></b>������/���� ���� ����</TD>
										<TD class="td_con1"><input type=radio id="idx_rcall_type1" name=up_rcall_type value="Y" <?=$check_rcall_typeY?>><label style="CURSOR: hand; TEXT-DECORATION: none" onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_rcall_type1>���� ��밡��</label>  &nbsp;&nbsp;<input type=radio id="idx_rcall_type2" name=up_rcall_type value="N" <?=$check_rcall_typeN?>><label style="CURSOR: hand; TEXT-DECORATION: none" onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_rcall_type2>���� ���Ұ�</label>
										</TD>
									</TR>
								</table>
							</td>
						</tr>
						<TR>
							<TD bgcolor="#B9B9B9" height="1"></TD>
						</TR>
					</table>


				</td>
			</tr>
			<tr><td height="10"></td></tr>
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
					<TD width="100%" class="notice_blue"><p>1) ���� ��ǰ���Ž� �����ݰ� ������ ���� ����� �� �ִ��� ������ �� �ֽ��ϴ�.<br>2) ���� ���Ұ� �� ��� ȸ���� ���� ������ ��� �Ǵ� ���� �� �� ��1�� �����մϴ�.</p></TD>
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
					<TD background="images/manual_left1.gif"></TD>
					<TD COLSPAN=3 width="100%" class="menual_bg" valign="top" bgcolor="white" style="padding-top:8pt; padding-bottom:8pt; padding-left:4pt;">
					<table cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td width="20" align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td ><span class="font_dotline">������ ���� �ȳ�</span></td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td  class="space_top">- <b>�������� ���� ���θ��� ��� ���</b> : ���ݰ��� �߰����� ����+��ǰ�� ���� �������� �������� ����<br>
						<b>&nbsp;&nbsp;</b>��ۺ�� ������ ��꿡�� ���ܵ˴ϴ�.<br>
						<b>&nbsp;&nbsp;</b>�������� ��ۿϷ� �� �����˴ϴ�.(�ֹ� ��ҽ� �����ݵ� �ڵ�����, ��ȸ���� �������� �ʽ��ϴ�.)</td>
					</tr>
					<tr>
						<td height="20" colspan="2"></td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td  class="space_top">- <b>����� �������� ������ ���űݾ� ��� ����<span class="font_orange">(���űݾ�-���������)</span>�� ���� �ȳ�</b>
					</tr>
					<tr>
						<td height="5" colspan="2"></td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td  class="space_top"><b>&nbsp;&nbsp;</b><span class="font_blue"><b>������ �̻��</b></span> : ��ǰ����(10,000��)&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;
						&nbsp;= ������(&nbsp;<span class="font_blue">10,000��</span> )�� ���� <span class="font_blue"><b>300�� ����(�Ϲ�������)</b></span><br>
						<b>&nbsp;&nbsp;</b><span class="font_orange"><b>������</b>&nbsp;&nbsp;<b>&nbsp;&nbsp;���</b></span> : ��ǰ����(10,000��) -
						<span class="font_orange">���������(2,000��)</span> = ������(<b>&nbsp;&nbsp;</b><span class="font_orange">8,000��</span> )�� ���� <span class="font_orange"><b>240�� ����</b></span>
					</tr>
					<tr>
						<td height="10" colspan="2"></td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td  class="space_top"><b>&nbsp;&nbsp;</b><span class="font_orange"><b>������ ����� ���������ݾ� 240�� ��� ���</b></span><br>
						<b>&nbsp;&nbsp;</b><span style="letter-spacing:-0.5pt;">�Ϲ������ݡ�(��ǰ����-���������)����ǰ���� = ������ ����� ���������ݾ�&nbsp;&nbsp;=>&nbsp;&nbsp;<span class="font_orange">300����(10,000��-2,000��)��10,000�� = 240��</span></span></td>
					</tr>
					<tr>
						<td height="10" colspan="2"></td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td class="space_top"><b>&nbsp;&nbsp;</b>�������� <span class="font_blue">10,000�� �϶� 300��</span>, <span class="font_orange">8,000�� �϶� 240��</span> ����<span class="font_orange"><b>(���Ϻ����� �������� ���Ǵ� ���)</b></span></td>
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