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

$type=$_POST["type"];
$up_function_use=$_POST["up_function_use"];
$up_nametech_use=$_POST["up_nametech_use"];
$up_account_rule=$_POST["up_account_rule"];
$up_reserve_use=$_POST["up_reserve_use"];
$up_coupon_use=$_POST["up_coupon_use"];
$up_info_view=$_POST["up_info_view"];
$up_relay=$_POST["up_relay"];

if ($type == "up") {
	########################### TEST ���θ� Ȯ�� ##########################
	DemoShopCheck("������������� �׽�Ʈ�� �Ұ��� �մϴ�.", $_SERVER[PHP_SELF]);
	#######################################################################

	$sql = "SELECT * FROM shop_more_info ";
	$result = mysql_query($sql,get_db_conn());
	$data_lows = mysql_num_rows($result);

	if (!$data_lows) {
		$sql = "insert into shop_more_info values(";
		$sql.= "'".$up_function_use."', ";
		$sql.= "'".$up_nametech_use."', ";
		$sql.= "'".$up_account_rule."', ";
		$sql.= "'".$up_reserve_use."', ";
		$sql.= "'".$up_coupon_use."', ";
		$sql.= "'".$up_info_view."', ";		
		$sql.= "'".$up_relay."', ";
		$sql.= "'".$commi_self."', ";
		$sql.= "'".$commi_main."') ";

	}else{

		$sql = "UPDATE shop_more_info  SET ";
		$sql.= "function_use		= '".$up_function_use."', ";
		$sql.= "nametech_use	= '".$up_nametech_use."', ";
		$sql.= "account_rule		= '".$up_account_rule."', ";
		$sql.= "reserve_use		= '".$up_reserve_use."', ";
		$sql.= "coupon_use		= '".$up_coupon_use."', ";
		$sql.= "info_view		= '".$up_info_view."', ";
		$sql.= "relay		= '".$up_relay."', ";
		$sql.= "commi_self		= '".$commi_self."', ";
		$sql.= "commi_main		= '".$commi_main."' ";
	}
	$result = mysql_query($sql,get_db_conn());

	DeleteCache("shop_more_info.cache");
	$onload = "<script> alert('���� ������ �Ϸ�Ǿ����ϴ�.'); </script>";
}

$sql = "SELECT * FROM shop_more_info ";
$result = mysql_query($sql,get_db_conn());
if ($row=mysql_fetch_object($result)) {
	$function_use = $row->function_use;
	$nametech_use = $row->nametech_use;
	$account_rule = $row->account_rule;
	$reserve_use = $row->reserve_use;
	$coupon_use = $row->coupon_use;
	$info_view = $row->info_view;
	$relay = $row->relay;
	$commi_self = $row->commi_self;
	$commi_main = $row->commi_main;
}
mysql_free_result($result);

?>

<? INCLUDE ("header.php"); ?>

<script type="text/javascript" src="lib.js.php"></script>
<script language="JavaScript">

function CheckForm() {
	var form = document.form1;
	form.type.value="up";
	form.submit();
}

function alertFunctionUser() {
	if(!confirm("�����Լ��� �� ������ü ���������Ӹ� �Ұ����ϸ� ������ȭ�鿡���� ������ǰ�� ����Ǵ� ���� ���� ������� �����Ѵٸ� �ݵ�� ��ü �������ǰ�� �̳���� �����ϼž� �մϴ�.\n�������ǰ �̳��⼳�� : �������� > ������ǰ���� > ������ü ��ǰ��Ͽ��� [on][off] ����\n���� ������� ���������� �����Ͻðڽ��ϱ�?")) {
		up_function_use1 = document.getElementById('up_function_use1');
		up_function_use1.checked = true;
	}
}

function alertAccountRule() {
	if (!confirm("��ǰ�� ���ް��� � �� �� ��ǰ�� ���ް��� �ǸŰ��� �ݵ�� �Է��ؾ� �մϴ�.\n���� �����Ͻðڽ��ϱ�?")) {
		up_account_rule0 = document.getElementById('up_account_rule0');
		up_account_rule0.checked = true;
	}
}

</script>
<table cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed">
<tr>
	<td valign="top">
	<table cellpadding="0" cellspacing="0" width=100% style="table-layout:fixed">
	<tr>
		<td>
		<table cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed" background="images/con_bg.gif">
		<col width=198></col>
		<col width=10></col>
		<col width=></col>
		<tr>
			<td  valign="top" background="images/leftmenu_bg.gif">
			<? include ("menu_vender.php"); ?>
			</td>

			<td></td>
			<td valign="top">

<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td height="29" colspan="3">
			<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td height="28" class="link" align="left" background="images/con_link_bg.gif"><img src="images/top_link_house.gif" border="0" valign="absmiddle">������ġ : �������� &gt; ���� �⺻���� ���� &gt; <span class="2depth_select">���� �⺻���� ����</span></td>
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





			<table width="100%" cellpadding="0" cellspacing="0">
			<tr><td height="8"></td></tr>
			<tr>
				<td>
				<table width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td><img src="images/shop_basicinfo.gif" border="0"></td>
				</tr>
				<tr>
					<td width="100%" background="images/title_bg.gif" height="21"></td>
				</tr>
				</table>
				</td>
			</tr>
			<tr><td height="20"></td></tr>
			<tr>
				<td>
				<table width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td><img src="images/shop_basicinfo_stitle2.gif" border="0"></td>
					<td width="100%" background="images/shop_basicinfo_stitle_bg.gif"></td>
					<td><img src="images/shop_basicinfo_stitle_end.gif" border="0"></td>
				</tr>
				</table>
				</td>
			</tr>
			<tr><td height="3"></td></tr>
			<tr>
				<td>
				<table width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td><img src="images/distribute_01.gif"></td>
					<td COLSPAN="2" background="images/distribute_02.gif"></td>
					<td><img src="images/distribute_03.gif"></td>
				</tr>
				<tr>
					<td background="images/distribute_04.gif"></td>
					<td class="notice_blue"><img src="images/distribute_img.gif" ></td>
					<td width="100%" class="notice_blue">���θ� ������ �������� ���� ������ ǥ��˴ϴ�. <b>��Ȯ�� �Է��� �ּ���!</b></td>
					<td background="images/distribute_07.gif"></td>
				</tr>
				<tr>
					<td><img src="images/distribute_08.gif"></td>
					<td COLSPAN="2" background="images/distribute_09.gif"></td>
					<td><img src="images/distribute_10.gif"></td>
				</tr>
				</table>
				</td>
			</tr>
			<tr><td height="3"></td></tr>
			<form name=form1 action="<?=$_SERVER[PHP_SELF]?>" method=post>
			<input type=hidden name=type>
			<tr>
				<td>
				<table width="100%" cellpadding="0" cellspacing="0">
				<col width="180"></col>
				<col></col>
				<tr>
					<td height="2" colspan="2" bgcolor="#808080"></td>
				</tr>
				<tr>
					<td class="table_cell"><img src="images/icon_point2.gif" border="0">������ɻ�뿩��</td>
					<td class="td_con1">
						<input type=radio name=up_function_use id=up_function_use1 value="1" <?if($function_use=="1" || strlen($function_use)==0)echo"checked";?>> <label style='cursor:hand; TEXT-DECORATION: none;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=up_function_use1>���</label>
						<img width=20 height=0>
						<input type=radio name=up_function_use id=up_function_use0 value="0" <?if($function_use=="0")echo"checked";?> onclick="alertFunctionUser();"> <label style='cursor:hand; TEXT-DECORATION: none;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=up_function_use0>������</label>
					</td>
				</tr>
				<tr>
					<td height="1" colspan="2" bgcolor="#EDEDED"></td>
				</tr>
				<tr>
					<td class="table_cell"><img src="images/icon_point2.gif" border="0">������ ������ ��뿩��</td>
					<td class="td_con1">
						<input type=radio name=up_nametech_use id=up_nametech_use1 value="1" <?if($nametech_use=="1" || strlen($nametech_use)==0)echo"checked";?>> <label style='cursor:hand; TEXT-DECORATION: none;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=up_nametech_use1>���</label>
						<img width=20 height=0>
						<input type=radio name=up_nametech_use id=up_nametech_use0 value="0" <?if($nametech_use=="0")echo"checked";?>> <label style='cursor:hand; TEXT-DECORATION: none;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=up_nametech_use0>������</label>
					</td>
				</tr>
				<tr>
					<td height="1" colspan="2" bgcolor="#EDEDED"></td>
				</tr>
				<tr>
					<td class="table_cell"><img src="images/icon_point2.gif" border="0">������ؼ���</td>
					<td class="td_con1">
						<input type=radio name=up_account_rule id=up_account_rule0 value="0" <?if($account_rule=="0" || strlen($account_rule)==0)echo"checked";?>> <label style='cursor:hand; TEXT-DECORATION: none;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=up_account_rule0>�Ǹ� ������� �</label>
						<img width=20 height=0>
						<input type=radio name=up_account_rule id=up_account_rule1 value="1" <?if($account_rule=="1")echo"checked";?> onclick="alertAccountRule();"> <label style='cursor:hand; TEXT-DECORATION: none;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=up_account_rule1>��ǰ�� ���ް��� �</label>
					</td>
				</tr>
				<tr>
					<td height="1" colspan="2" bgcolor="#EDEDED"></td>
				</tr>
				<tr>
					<td class="table_cell"><img src="images/icon_point2.gif" border="0">���� ��� ����</td>
					<td class="td_con1">
						<b>������ : </b>
						<input type=radio name=up_reserve_use id=up_reserve_use1 value="1" <?if($reserve_use=="1")echo"checked";?>> <label style='cursor:hand; TEXT-DECORATION: none;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=up_reserve_use1>���</label>
						<img width=20 height=0>
						<input type=radio name=up_reserve_use id=up_reserve_use0 value="0" <?if($reserve_use=="0" || strlen($reserve_use)==0)echo"checked";?>> <label style='cursor:hand; TEXT-DECORATION: none;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=up_reserve_use0>��� ����</label>
						<br/>
						<b>���� : </b>
						<input type=radio name=up_coupon_use id=up_coupon_use1 value="1" <?if($coupon_use=="1")echo"checked";?>> <label style='cursor:hand; TEXT-DECORATION: none;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=up_coupon_use1>���</label>
						<img width=20 height=0>
						<input type=radio name=up_coupon_use id=up_coupon_use0 value="0" <?if($coupon_use=="0" || strlen($coupon_use)==0)echo"checked";?>> <label style='cursor:hand; TEXT-DECORATION: none;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=up_coupon_use0>��� ����</label>
						<br/>
						<span class="font_blue">
						* ���Ұ� üũ �� ������� ������ ����� �� ������ �ش�޴��� ������ ������忡 ������� �ʽ��ϴ�.
						</span>
					</td>
				</tr>
				<tr>
					<td height="1" colspan="2" bgcolor="#EDEDED"></td>
				</tr>
				<tr>
					<td class="table_cell"><img src="images/icon_point2.gif" border="0">������ü�������⿩��</td>
					<td class="td_con1">
						<input type=radio name=up_info_view id=up_disabled1 value="1" <?if($info_view=="1" || strlen($info_view)==0)echo"checked";?>> <label style='cursor:hand; TEXT-DECORATION: none;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=up_disabled1>����</label>
						<img width=20 height=0>
						<input type=radio name=up_info_view id=up_info_view0 value="0" <?if($info_view=="0")echo"checked";?>> <label style='cursor:hand; TEXT-DECORATION: none;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=up_info_view0>�������</label>
						<br/>
						<span class="font_blue">
							* ���� ���� üũ �� ��ȭ��ȣ �� �����ּ� �� ��ü������ ������ �ʽ��ϴ�. ���� ���� �� ���� ������� ������ ���������� �����ϱ� ����
						</span>
					</td>
				</tr>
				<tr>
					<td height="1" colspan="2" bgcolor="#EDEDED"></td>
				</tr>
				<tr>
					<td class="table_cell"><img src="images/icon_point2.gif" border="0">�뿩 �⺻ ������</td>
					<td class="td_con1">���� : <input type="text" name="commi_self" value="<?=$commi_self?>" style="width:60px;" />%&nbsp;&nbsp;/&nbsp;&nbsp;��Ź : <input type="text" name="commi_main" value="<?=$commi_main?>" style="width:60px;" />%
				<tr>
					<td height="1" colspan="2" bgcolor="#EDEDED"></td>
				</tr>
				<tr>
					<td class="table_cell"><img src="images/icon_point2.gif" border="0">�Ǹſ��(����)<br/>��ǰ�Ǹ� �߰��<br/>��Ͽ���</td>
					<td class="td_con1">
						<input type=radio name=up_relay id=up_relay0 value="0" <?if($relay=="0" || strlen($relay)==0)echo"checked";?>> <label style='cursor:hand; TEXT-DECORATION: none;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=up_relay0>��ǰ�Ǹ� �߰��ü �ƴ�</label>
						<img width=20 height=0>
						<input type=radio name=up_relay id=up_relay1 value="1" <?if($relay=="1")echo"checked";?>> <label style='cursor:hand; TEXT-DECORATION: none;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=up_relay1>��ǰ�Ǹ� �߰��ü</label>
						<br/>
						<br/>
							<table width="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td class="menual_con"><img src="images/icon_8.gif" border="0" align="absmiddle"><span class="font_dotline">�������</span></td>
							</tr>
							<tr>
								<td style="padding-left:13px;" class="menual_con">
								<b>1. ������ ���� �</b>
									<div class="font_blue" style="padding-left:13px">
									1)��ǰ�Ǹ� �߰��ü�� �ƴ� �� ��� ����ݾ�=��ǰ�Ǹűݾ�-������ݾ�+��ۺ�-������-��������/���� <br/>
									2)��ǰ�Ǹ� �߰���ü�� �� ��� ����ݾ�=��ǰ�Ǹűݾ�-������ݾ�-�������� �ΰ���+��ۺ�-������-��������/����
									</div>
								<b>2. ��ǰ���ް� ���� �</b>
									<div class="font_blue" style="padding-left:13px">
									1) ��ǰ�Ǹ� �߰��ü�� �ƴ� �� �� ��� ����ݾ�= �ǸŻ�ǰ ��ü ���ް���+��ۺ�-������-��������/���� <br/>
									2) ��ǰ�Ǹ� �߰��ü�� �� �� ��� ����ݾ�=�ǸŻ�ǰ ��ü ���ް���-(��ǰ�Ǹűݾ�-��ǰ���ް���)*0.1+��ۺ�-������-��������/���� <br/>
										<span style="padding-left:13px">* (��ǰ�Ǹűݾ�-��ǰ���ް���)*0.1�� �������� �ΰ����Դϴ�.</span>
									</div>
									*������ �ݾ� = �Ǹűݾ�x�������� <br/>
									*������ �� ������ ��� ���� ��ü�� �δ��ϴ°��� ��Ī���� �մϴ�. <br/>
									*ȸ����޺� ���� �� ��Ÿ ������ �Ǹſ��(����)�� �δ��ϴ°��� ��Ģ���� �մϴ�. <br/>
									*��۷��� ��� ������ ������ �����å�� �����ϴ�.
								</td>
							</tr>
							<tr><td height="20"></td></tr>
							<tr>
								<td class="menual_con"><img src="images/icon_8.gif" border="0" align="absmiddle"><span class="font_dotline">���ݰ�꼭 ó��</span></td>
							</tr>
							<tr>
								<td style="padding-left:13px;" class="menual_con">
								���� ��� ��ü ����ݾ� ���� ���Լ��ݰ�꼭�� ������κ��� ���� <br/>
								���� ��� ��ǰ�Ǹż����ῡ ���� �ΰ����� ���� �� �����ϰ� �Ǹż����ῡ ���� ���⼼�ݰ�꼭�� �����翡�� �߼�, ������� ��ü �Ǹűݾ׿� ���� �����ڿ��� ���ݰ�꼭 �߼�
								</td>
							</tr>
							<tr><td height="20"></td></tr>
							</table>
					</td>
				</tr>
				<tr>
					<td height="1" colspan="2" bgcolor="#EDEDED"></td>
				</tr>
				</table>
				</td>
			</tr>
			<tr><td height="10"></td></tr>
			<tr>
				<td align="center"><a href="javascript:CheckForm();"><img src="images/botteon_save.gif" border="0"></a></td>
			</tr>
			<tr><td height="20"></td></tr>
			</form>
			<!--
			<tr>
				<td>
				<table width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td><img src="images/manual_top1.gif" border="0"></td>
					<td width="100%" background="images/manual_bg.gif"><img src="images/manual_title.gif" border="0"></td>
					<td><img src="images/manual_top2.gif" border="0"></td>
				</tr>
				<tr>
					<td background="images/manual_left1.gif"></td>
					<td style="padding-top:5px;" class="menual_bg">
					<table width="100%" cellpadding="0" cellspacing="0">
					<tr>
						<td class="menual_con"><img src="images/icon_8.gif" border="0" align="absmiddle"><span class="font_dotline">�������</span></td>
					</tr>
					<tr>
						<td style="padding-left:13px;" class="menual_con">
						<b>1. ������ ���� �</b>
							<div class="font_blue" style="padding-left:13px">
							1)��ǰ�Ǹ� �߰��ü�� �ƴ� �� ��� ����ݾ�=��ǰ�Ǹűݾ�-������ݾ�+��ۺ�-������-��������/���� <br/>
							2)��ǰ�Ǹ� �߰���ü�� �� ��� ����ݾ�=��ǰ�Ǹűݾ�-������ݾ�-�������� �ΰ���+��ۺ�-������-��������/����
							</div>
						<b>2. ��ǰ���ް� ���� �</b>
							<div class="font_blue" style="padding-left:13px">
							1) ��ǰ�Ǹ� �߰��ü�� �ƴ� �� �� ��� ����ݾ�= �ǸŻ�ǰ ��ü ���ް���+��ۺ�-������-��������/���� <br/>
							2) ��ǰ�Ǹ� �߰��ü�� �� �� ��� ����ݾ�=�ǸŻ�ǰ ��ü ���ް���-(��ǰ�Ǹűݾ�-��ǰ���ް���)*0.1+��ۺ�-������-��������/���� <br/>
								<span style="padding-left:13px">* (��ǰ�Ǹűݾ�-��ǰ���ް���)*0.1�� �������� �ΰ����Դϴ�.</span>
							</div>
							*������ �ݾ� = �Ǹűݾ�x�������� <br/>
							*������ �� ������ ��� ���� ��ü�� �δ��ϴ°��� ��Ī���� �մϴ�. <br/>
							*ȸ����޺� ���� �� ��Ÿ ������ �Ǹſ��(����)�� �δ��ϴ°��� ��Ģ���� �մϴ�. <br/>
							*��۷��� ��� ������ ������ �����å�� �����ϴ�.
						</td>
					</tr>
					<tr><td height="20"></td></tr>
					<tr>
						<td class="menual_con"><img src="images/icon_8.gif" border="0" align="absmiddle"><span class="font_dotline">���ݰ�꼭 ó��</span></td>
					</tr>
					<tr>
						<td style="padding-left:13px;" class="menual_con">
						���� ��� ��ü ����ݾ� ���� ���Լ��ݰ�꼭�� ������κ��� ���� <br/>
						���� ��� ��ǰ�Ǹż����ῡ ���� �ΰ����� ���� �� �����ϰ� �Ǹż����ῡ ���� ���⼼�ݰ�꼭�� �����翡�� �߼�, ������� ��ü �Ǹűݾ׿� ���� �����ڿ��� ���ݰ�꼭 �߼�
						</td>
					</tr>
					<tr><td height="20"></td></tr>
					</table>
					</td>
					<td background="images/manual_right1.gif"></td>
				</tr>
				<tr>
					<td><img src="images/manual_left2.gif" border="0"></td>
					<td background="images/manual_down.gif"></td>
					<td><img src="images/manual_right2.gif" border="0"></td>
				</tr>
				</table>
				</td>
			</tr>
			-->
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

<? INCLUDE ("copyright.php"); ?>