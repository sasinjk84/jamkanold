<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
INCLUDE ("access.php");

####################### ������ ���ٱ��� check ###############
$PageCode = "or-3";
$MenuCode = "order";
if (!$_usersession->isAllowedTask($PageCode)) {
	INCLUDE ("AccessDeny.inc.php");
	exit;
}
#########################################################


if( !mysql_num_rows ( mysql_query ( "SHOW COLUMNS FROM tblshopinfo LIKE 'tax_scd'" ) ) ) {
	mysql_query ( "ALTER TABLE `tblshopinfo` ADD `tax_scd` VARCHAR(5) NULL AFTER `tax_tid`;" );
}


$tax_cnum=$_shopdata->tax_cnum;
$tax_cname=$_shopdata->tax_cname;
$tax_cowner=$_shopdata->tax_cowner;
$tax_caddr=$_shopdata->tax_caddr;
$tax_ctel=$_shopdata->tax_ctel;
$tax_type=$_shopdata->tax_type;
$tax_rate=$_shopdata->tax_rate;
$tax_mid=$_shopdata->tax_mid;
$tax_tid=$_shopdata->tax_tid;
$tax_scd=$_shopdata->tax_scd;

$mode=$_POST["mode"];
if($mode=="update") {
	########################### TEST ���θ� Ȯ�� ##########################
	DemoShopCheck("������������� �׽�Ʈ�� �Ұ��� �մϴ�.", $_SERVER[PHP_SELF]);
	#######################################################################

	$up_tax_cnum1=$_POST["up_tax_cnum1"];
	$up_tax_cnum2=$_POST["up_tax_cnum2"];
	$up_tax_cnum3=$_POST["up_tax_cnum3"];
	$up_tax_cname=$_POST["up_tax_cname"];
	$up_tax_cowner=$_POST["up_tax_cowner"];
	$up_tax_caddr=$_POST["up_tax_caddr"];
	$up_tax_ctel1=$_POST["up_tax_ctel1"];
	$up_tax_ctel2=$_POST["up_tax_ctel2"];
	$up_tax_ctel3=$_POST["up_tax_ctel3"];
	$up_tax_type=$_POST["up_tax_type"];
	$up_tax_rate=$_POST["up_tax_rate"];
	$up_tax_mid=$_POST["up_tax_mid"];
	$up_tax_mid=$_POST["up_tax_scd"];
	$tax_tid=$_POST["tax_tid"];

	$up_tax_cnum="";
	$up_tax_ctel="";
	if(strlen($up_tax_cnum1)==3 && strlen($up_tax_cnum2)==2 && strlen($up_tax_cnum3)==5) {
		$up_tax_cnum=$up_tax_cnum1.$up_tax_cnum2.$up_tax_cnum3;
	}
	if(strlen($up_tax_ctel1)>0 && strlen($up_tax_ctel2)>0 && strlen($up_tax_ctel3)>0) {
		$up_tax_ctel=$up_tax_ctel1."-".$up_tax_ctel2."-".$up_tax_ctel3;
	}

	$sql = "UPDATE tblshopinfo SET ";
	$sql.= "tax_cnum	= '".$up_tax_cnum."', ";
	$sql.= "tax_cname	= '".$up_tax_cname."', ";
	$sql.= "tax_cowner	= '".$up_tax_cowner."', ";
	$sql.= "tax_caddr	= '".$up_tax_caddr."', ";
	$sql.= "tax_ctel	= '".$up_tax_ctel."', ";
	$sql.= "tax_type	= '".$up_tax_type."', ";
	$sql.= "tax_rate	= '".$up_tax_rate."', ";
	$sql.= "tax_mid		= '".$up_tax_mid."', ";
	$sql.= "tax_tid		= '".$up_tax_tid."', ";
	$sql.= "tax_scd		= '".$up_tax_scd."'";
	if(mysql_query($sql,get_db_conn())) {
		$tax_cnum=$up_tax_cnum;
		$tax_cname=$up_tax_cname;
		$tax_cowner=$up_tax_cowner;
		$tax_caddr=$up_tax_caddr;
		$tax_ctel=$up_tax_ctel;
		$tax_type=$up_tax_type;
		$tax_rate=$up_tax_rate;
		$tax_mid=$up_tax_mid;
		$tax_tid=$up_tax_tid;
		$tax_scd=$up_tax_scd;

		$onload="<script>alert('���ݿ����� ȯ�漳���� �Ϸ�Ǿ����ϴ�.');</script>";
		DeleteCache("tblshopinfo.cache");
	}
}

$tax_cnum1=substr($tax_cnum,0,3);
$tax_cnum2=substr($tax_cnum,3,2);
$tax_cnum3=substr($tax_cnum,5,5);

$arr_ctel=explode("-",$tax_ctel);
$tax_ctel1=$arr_ctel[0];
$tax_ctel2=$arr_ctel[1];
$tax_ctel3=$arr_ctel[2 ];

?>

<? INCLUDE "header.php"; ?>

<script type="text/javascript" src="lib.js.php"></script>
<script language="JavaScript">
function CheckForm() {
	/*
	if(document.form1.up_tax_mid.value.length==0) {
		alert("KCP���� �߱޹��� MID�� �Է��ϼ���.");
		document.form1.up_tax_mid.focus();
		return;
	}
	if(document.form1.up_tax_tid.value.length==0) {
		alert("KCP���� �߱޹��� TID�� �Է��ϼ���.");
		document.form1.up_tax_tid.focus();
		return;
	}
	*/
	if(document.form1.up_tax_cnum1.value.length!=3 || document.form1.up_tax_cnum2.value.length!=2 || document.form1.up_tax_cnum3.value.length!=5) {
		alert("����ڵ�Ϲ�ȣ�� �߸��Ǿ����ϴ�.");
		document.form1.up_tax_cnum1.focus();
		return;
	}
	if(!chkBizNo(document.form1.up_tax_cnum1.value+""+document.form1.up_tax_cnum2.value+""+document.form1.up_tax_cnum3.value)) {
		alert("����ڵ�Ϲ�ȣ�� �߸��Ǿ����ϴ�.");
		return;
	}
	if(document.form1.up_tax_cname.value.length==0) {
		alert("������ ��ȣ���� ��Ȯ�� �Է��ϼ���.");
		document.form1.up_tax_cname.focus();
		return;
	}
	if(document.form1.up_tax_cowner.value.length==0) {
		alert("��ǥ�ڸ��� ��Ȯ�� �Է��ϼ���.");
		document.form1.up_tax_cowner.focus();
		return;
	}
	if(document.form1.up_tax_caddr.value.length==0) {
		alert("����� �ּҸ� ��Ȯ�� �Է��ϼ���. (�����ȣ ����)");
		document.form1.up_tax_caddr.focus();
		return;
	}
	if(document.form1.up_tax_ctel1.value.length==0 || document.form1.up_tax_ctel2.value.length==0 || document.form1.up_tax_ctel3.value.length==0) {
		alert("����� ��ȭ��ȣ�� ��Ȯ�� �Է��ϼ���.");
		document.form1.up_tax_ctel1.focus();
		return;
	}
	if(!IsNumeric(document.form1.up_tax_ctel1.value) || !IsNumeric(document.form1.up_tax_ctel2.value) || !IsNumeric(document.form1.up_tax_ctel3.value)) {
		alert("����� ��ȭ��ȣ�� ��Ȯ�� �Է��ϼ���.");
		document.form1.up_tax_ctel1.focus();
		return;
	}
	if(document.form1.up_tax_type[0].checked!=true && document.form1.up_tax_type[1].checked!=true && document.form1.up_tax_type[2].checked!=true) {
		alert("���ݿ����� �߱޹���� �����ϼ���.");
		document.form1.up_tax_type[2].focus();
		return;
	}
	if(document.form1.up_tax_rate[0].checked!=true && document.form1.up_tax_rate[1].checked!=true) {
		alert("����� ���¸� �����ϼ���. (�ϴ� �޴��� ����)");
		document.form1.up_tax_rate[0].focus();
		return;
	}
	if(confirm("���ݿ����� ȯ�漳�� ������ �����Ͻðڽ��ϱ�?")) {
		document.form1.mode.value="update";
		document.form1.submit();
	}
}
</script>



<table cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed">
<tr>
	<td valign="top">
	<table cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed">
	<tr>
		<td>
		<table cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed"  background="images/con_bg.gif">
		<col width=198></col>
		<col width=10></col>
		<col width=></col>
		<tr>
			<td valign="top"  background="images/leftmenu_bg.gif">
			<? include ("menu_order.php"); ?>
			</td>

			<td></td>
			<td valign="top">




<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td height="29" colspan="3">
			<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td height="28" class="link" align="left" background="images/con_link_bg.gif"><img src="images/top_link_house.gif" width="16" height="12" border="0" valign=absmiddle>������ġ : �ֹ�/���� &gt; ���ݿ����� ���� &gt; <span class="2depth_select">���ݿ����� ȯ�漳��</span></td>
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
				<td height="8">
				</td>
			</tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/order_taxsaveconfig_title.gif"  ALT=""></TD>
					</tr><tr>
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
					<TD background="images/distribute_04.gif"><IMG SRC="images/distribute_04.gif" ></TD>
					<TD class="notice_blue"><IMG SRC="images/distribute_img.gif" ></TD>
					<TD width="100%" class="notice_blue"><p>���ݿ����� �߱��� ���� ����������� �����Ͻ� �� �ֽ��ϴ�.</p></TD>
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




			<form name=form1 action="<?=$_SERVER[PHP_SELF]?>" method=post>
			<input type=hidden name=mode>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/order_taxsaveconfig_stitle1.gif" WIDTH="192" HEIGHT=31 ALT=""></TD>
					<TD width="100%" background="images/shop_basicinfo_stitle_bg.gif"></TD>
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
					<TD background="images/table_top_line.gif" width="153"><img src="images/table_top_line.gif"></TD>
					<TD background="images/table_top_line.gif" width="607" ></TD>
				</TR>
				<TR>
					<TD class="table_cell" width="139"><img src="images/icon_point2.gif" width="8" height="11" border="0">KCP SITE CODE</TD>
					<TD class="td_con1" width="600"><input type=text name=up_tax_scd value="<?=$tax_scd?>" size=6 maxlength=5 class="input_selected"></TD>
				</TR>
				<TR>
					<TD colspan="2" width="760" background="images/table_con_line.gif"><img src="images/table_con_line.gif" width="5" height="1" border="0"></TD>
				</TR>
				<!-- <TR>
					<TD class="table_cell" width="139"><img src="images/icon_point2.gif" width="8" height="11" border="0">KCP MID</TD>
					<TD class="td_con1" width="600"><input type=text name=up_tax_mid value="<?=$tax_mid?>" size=6 maxlength=4 class="input_selected"></TD>
				</TR>
				<TR>
					<TD colspan="2" width="760" background="images/table_con_line.gif"><img src="images/table_con_line.gif" width="4" height="1" border="0"></TD>
				</TR>
				<TR>
					<TD class="table_cell" width="139"><img src="images/icon_point2.gif" width="8" height="11" border="0">KCP TID</TD>
					<TD class="td_con1" width="600"><input type=text name=up_tax_tid value="<?=$tax_tid?>" size=6 maxlength=6 class="input_selected"></TD>
				</TR>
				<TR>
					<TD colspan="2" width="760" background="images/table_con_line.gif"><img src="images/table_con_line.gif" width="4" height="1" border="0"></TD>
				</TR> -->
				<TR>
					<TD class="table_cell" width="139"><img src="images/icon_point2.gif" width="8" height="11" border="0">����ڵ�Ϲ�ȣ</TD>
					<TD class="td_con1" width="600"><input type=text name=up_tax_cnum1 value="<?=$tax_cnum1?>" size=3 class="input_selected"> - <input type=text name=up_tax_cnum2 value="<?=$tax_cnum2?>" size=2 class="input_selected"> - <input type=text name=up_tax_cnum3 value="<?=$tax_cnum3?>" size=5 class="input_selected"></TD>
				</TR>
				<TR>
					<TD colspan="2" width="760" background="images/table_con_line.gif"><img src="images/table_con_line.gif" width="4" height="1" border="0"></TD>
				</TR>
				<TR>
					<TD class="table_cell" width="139"><img src="images/icon_point2.gif" width="8" height="11" border="0">������ ��ȣ</TD>
					<TD class="td_con1" width="600"><input type=text name=up_tax_cname value="<?=$tax_cname?>" size=50 class="input_selected"></TD>
				</TR>
				<TR>
					<TD colspan="2" width="760" background="images/table_con_line.gif"><img src="images/table_con_line.gif" width="4" height="1" border="0"></TD>
				</TR>
				<TR>
					<TD class="table_cell" width="139"><img src="images/icon_point2.gif" width="8" height="11" border="0">��ǥ�ڸ�</TD>
					<TD class="td_con1" width="600"><input type=text name=up_tax_cowner value="<?=$tax_cowner?>" size=20 class="input_selected"></TD>
				</TR>
				<TR>
					<TD colspan="2" width="760" background="images/table_con_line.gif"><img src="images/table_con_line.gif" width="4" height="1" border="0"></TD>
				</TR>
				<TR>
					<TD class="table_cell" width="139"><img src="images/icon_point2.gif" width="8" height="11" border="0">����� �ּ�</TD>
					<TD class="td_con1" width="600"><input type=text name=up_tax_caddr value="<?=$tax_caddr?>" size=70 class="input_selected"></TD>
				</TR>
				<TR>
					<TD colspan="2" width="760" background="images/table_con_line.gif"><img src="images/table_con_line.gif" width="4" height="1" border="0"></TD>
				</TR>
				<TR>
					<TD class="table_cell" width="139"><img src="images/icon_point2.gif" width="8" height="11" border="0">����� ��ȭ��ȣ</TD>
					<TD class="td_con1" width="600"><input type=text name=up_tax_ctel1 value="<?=$tax_ctel1?>" size=3 maxlength=3 class="input_selected" onkeyup="strnumkeyup(this)"> - <input type=text name=up_tax_ctel2 value="<?=$tax_ctel2?>" size=4 maxlength=4 class="input_selected" onkeyup="strnumkeyup(this)"> - <input type=text name=up_tax_ctel3 value="<?=$tax_ctel3?>" size=4 maxlength=4 class="input_selected" onkeyup="strnumkeyup(this)"></TD>
				</TR>
				<TR>
					<TD colspan="2" width="760" background="images/table_con_line.gif"><img src="images/table_con_line.gif" width="4" height="1" border="0"></TD>
				</TR>
				<TR>
					<TD class="table_cell" width="139"><img src="images/icon_point2.gif" width="8" height="11" border="0">�߱޹��</TD>
					<TD class="td_con1" width="600">
						<!-- <input type=radio id="idx_tax_type0" name=up_tax_type value="Y"<?if($tax_type=="Y")echo " checked";?>><label style='cursor:hand; TEXT-DECORATION: none' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_tax_type0>�ڵ��߱�</label>&nbsp;&nbsp;&nbsp; -->
						<input type=radio id="idx_tax_type1" name=up_tax_type value="A"<?if($tax_type=="A")echo " checked";?>><label style='cursor:hand; TEXT-DECORATION: none' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_tax_type1>�����߱�</label>&nbsp;&nbsp;&nbsp;
						<input type=radio id="idx_tax_type2" name=up_tax_type value="N"<?if($tax_type=="N")echo " checked";?>><label style='cursor:hand; TEXT-DECORATION: none' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_tax_type2>������</label></TD>
				</TR>
				<TR>
					<TD colspan="2" width="760" background="images/table_con_line.gif"><img src="images/table_con_line.gif" width="4" height="1" border="0"></TD>
				</TR>
				<TR>
					<TD class="table_cell" width="139"><img src="images/icon_point2.gif" width="8" height="11" border="0">���������</TD>
					<TD class="td_con1" width="600"><input type=radio id="idx_tax_rate0" name=up_tax_rate value="10"<?if($tax_rate=="10")echo " checked";?>><label style='cursor:hand; TEXT-DECORATION: none' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_tax_rate0>�Ϲݰ��������</label>&nbsp;&nbsp;&nbsp;<input type=radio id="idx_tax_rate1" name=up_tax_rate value="0"<?if($tax_rate=="0")echo " checked";?>><label style='cursor:hand; TEXT-DECORATION: none' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_tax_rate1>�Ϲݸ鼼/���̻����</label></TD>
				</TR>
				<TR>
					<TD background="images/table_top_line.gif" width="153"><img src="images/table_top_line.gif"></TD>
					<TD background="images/table_top_line.gif" width="607"></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr>
				<td height=10></td>
			</tr>
			<tr>
				<td align="center"><p><a href="javascript:CheckForm();"><img src="images/botteon_save.gif" width="113" height="38" border="0"></a></p></td>
			</tr>
			</form>
			<tr>
				<td height="20"></td>
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
					<TD background="images/manual_left1.gif"><IMG SRC="images/manual_left1.gif" WIDTH=15 HEIGHT="5" ALT=""></TD>
					<TD COLSPAN=3 width="100%" valign="top" bgcolor="white" style="padding-top:8pt; padding-bottom:8pt; padding-left:4pt;">
					<table cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td width="20" align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td width="701"><span class="font_dotline">���ݿ����� ȯ�漳��</span></td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td width="701" class="space_top" style="letter-spacing:-0.5pt;"><p>- �Ʒ� ������ ��û�� �ֽø� ���ݿ����� ���񽺸� ������ �帳�ϴ�.</p></td>
					</tr>
					<tr>
						<td colspan="2" height="2"></td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td width="701" class="space_top" style="letter-spacing:-0.5pt;"><p><b>&nbsp;&nbsp;</b><span class="font_blue"> ���ݿ����� ������ ��û�� �ۼ�(<a href="http://taxsave.kcp.co.kr/Service03.html" target="_blank">http://taxsave.kcp.co.kr/Service03.html)</a></span><br>
						<b>&nbsp;&nbsp;</b>&nbsp;&nbsp;&nbsp;<b>&nbsp;</b><b>��</b>KCP ���ڰ��� ���� ���̿� �������� �ش�˴ϴ�.<br>
						<b>&nbsp;&nbsp;</b>&nbsp;&nbsp;&nbsp;<b>&nbsp;</b><b>��</b>���� KCP ���ڰ��� ���� �̿��ü�� ��û���� ���� �ۼ��Ͻ� �ʿ䰡 �����ϴ�.</p></td>
					</tr>
					<tr>
						<td colspan="2" height="2"></td>
					</tr>

					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td width="701" class="space_top" style="letter-spacing:-0.5pt;"><p>- �Ϲ� ����������� ���� ���űݾ��� 10%�� �ΰ����� �Ű��մϴ�.</p></td>
					</tr>
					<tr>
						<td colspan="2" height="20"></td>
					</tr>
					<tr>
						<td width="20" align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td width="701"><span class="font_dotline">�߱޹��</span></td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td width="701" class="space_top" style="letter-spacing:-0.5pt;"><p>- <span class="font_blue">�ڵ��߱�</span> : ���θ� ����� �Ա�Ȯ��/�ֹ���� �ܰ迡�� �ڵ����� ���ݿ������� ��û�Ͻ� ������ ���ݿ������� �߱�/��ҵ˴ϴ�.<br>
						<b>&nbsp;</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						���θ� ����� �ԱݿϷ� Ȯ�� �� ���� �ݿ� �� ��ȸ �˴ϴ�.</p></td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td width="701" class="space_top" style="letter-spacing:-0.5pt;"><p>- <span class="font_orange">�����߱�</span> : �������� ���ݿ����� ��û�Ǻ��� [�߱�] ��ư�� �����߸� ���� ����û���� ���۵˴ϴ�.(�߱���ҽÿ��� �������� ����)</p></td>
					</tr>
					<tr>
						<td colspan="2" height="20"></td>
					</tr>
					<tr>
						<td width="20" align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td width="701"><span class="font_dotline">���������</span></td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td width="701" class="space_top" style="letter-spacing:-0.5pt;"><p>- �Ϲݰ�������� : ����ڵ������ �Ϲݰ�������� / ������ǰ �Ǹ�</p></td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td width="701" class="space_top" style="letter-spacing:-0.5pt;"><p>- �Ϲݸ鼼/���̻���� : ����ڵ������ �鼼/���� �����</p></td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td width="701" class="space_top" style="letter-spacing:-0.5pt;"><p>- ���λ���� : �ǸŹ�ǰ�� �����̸� �Ϲݰ��������, �鼼�̸� �Ϲݸ鼼/���̻����</p></td>
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