<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
INCLUDE ("access.php");
include_once($Dir."lib/admin_more.php");

if(strlen($_ShopInfo->getId())==0){
	echo "<script>alert('�������� ��η� �����Ͻñ� �ٶ��ϴ�.');window.close();</script>";
	exit;
}

$productcode=$_REQUEST["productcode"];
$mode=$_REQUEST["mode"];
$new_com=$_REQUEST["new_com"];

if(strlen($productcode)==0) {
	echo "<html><head></head><body onload=\"alert('�ش� ��ǰ�� �������� �ʽ��ϴ�.');window.close();\"></body></html>";exit;
}

$sql = "SELECT * FROM tblproduct WHERE productcode = '".$productcode."' ";
$result = mysql_query($sql,get_db_conn());
$data_lows = mysql_num_rows($result);
$data=mysql_fetch_array($result);
mysql_free_result($result);

if($data_lows==0) {
	echo "<html><head></head><body onload=\"alert('�ش� ��ǰ�� �������� �ʽ��ϴ�.');window.close();\"></body></html>";exit;
}


$shop_more_info = getShopMoreInfo();
$account_rule = $shop_more_info['account_rule'];

//��ǰ ������
$com = getProductCommission($productcode);

if ($account_rule=="1") {
	
	
	$title = "���ް�";
	$unit = "��";
	$rq_num = $com['rq_cost'];
	$cf_num = $com['cf_cost'];

	$up_rq_com = "";
	$up_rq_cost = $new_com;

}else {

	$title = "������";
	$unit = "%";
	$rq_num = $com['rq_com'];
	$cf_num = $com['cf_com'];
	
	$up_rq_com = $new_com;
	$up_rq_cost = "";

}

if ($mode=="new") {

	insertCommission($data['vender'], $productcode, $up_rq_com, $up_rq_cost, "","1", $_usersession->id);	
	?>
	<script type="text/javascript">
	<!--
		alert("�����Ǿ����ϴ�.");	
		opener.location.reload();
		window.close(); 
   //-->
	</script>
	<?
	exit();

}else if($mode=="conf"){
	
	$commission_result = $_POST['commission_result'];
	
	confirmCommission($productcode, $commission_result, $_usersession->id);

	?>
	<script type="text/javascript">
	<!--
		alert("�����Ǿ����ϴ�.");	
		opener.location.reload();
		window.close(); 
   //-->
	</script>
	<?
	exit();
}


?>
<html>
<head>
<meta http-equiv='Content-Type' content='text/html;charset=euc-kr'>
<title>��ǰ ������ ����</title>
<link rel="stylesheet" href="style.css" type="text/css">

<script type="text/javascript">
<!--
	function sendit() {
		
		if (confirm("<?= $title ?>�� �����մϴ�. ����Ͻðڽ��ϱ�?")) {
			if (document.form1.new_com.value.length==0) {
				alert("�� <?= $title ?>�� �Է��ϼ���.");
				document.form1.new_com.focus();
				return;
			}

			if(isDigitSpecial(document.form1.new_com.value,"")) {
				alert("<?= $title ?>�� ���ڷθ� �Է��ϼ���.");
				document.form1.new_com.focus();
				return;
			}

			document.form1.mode.value="new";
			document.form1.submit();
		}
	}

	function conf(str) {
		
		if (str =='Y') {
			a_val = "��û �����Ḧ ���� �Ͻðڽ��ϱ�?";
		}else{
			a_val = "��û �����Ḧ �ź� �Ͻðڽ��ϱ�?";
		}
		

		if (confirm(a_val)) {
			document.form1.mode.value="conf";
			document.form1.commission_result.value=str;
			document.form1.submit();
		}
	}

	function isDigitSpecial(objValue,specialStr)
	{
		if(specialStr.length>0) {
			var specialStr_code = parseInt(specialStr.charCodeAt(i));

			for(var i=0; i<objValue.length; i++) {
				var code = parseInt(objValue.charCodeAt(i));
				var ch = objValue.substr(i,1).toUpperCase();

				if((ch<"0" || ch>"9") && code!=specialStr_code) {
					return true;
					break;
				}
			}
		} else {
			for(var i=0; i<objValue.length; i++) {
				var ch = objValue.substr(i,1).toUpperCase();
				if(ch<"0" || ch>"9") {
					return true;
					break;
				}
			}
		}
	}


//-->
</script>
</head>
<body leftmargin=0 topmargin=0 marginwidth=0 marginheight=0 oncontextmenu="return false" style="overflow-x:hidden;" ondragstart="return false" onselectstart="return false" oncontextmenu="return false" onLoad="PageResize();">

<form name=form1 action="<?=$_SERVER[PHP_SELF]?>" method=post enctype="multipart/form-data">
	<input type=hidden name=mode>
	<input type=hidden name=productcode value="<?=$productcode?>">
	<input type=hidden name=commission_result />

<table border=0 cellpadding=0 cellspacing=0 width=500 style="table-layout:fixed;" id=table_body>
<tr>
	<td width=100% align=center>
	<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
	<tr><td height=10></td></tr>
	<tr>
		<td>&nbsp;&nbsp;
		<b>* ��ǰ ������ ����</b>
		</td>
	</tr>
	<tr><td height=10></td></tr>
	<tr>
		<td align="center">
		<? if ($com['status']=="1" ) {?>
			<table border=0 cellpadding=0 cellspacing=0 width=350 style="table-layout:fixed">
				<col width=100></col>
				<col width=></col>
				<TR>
					<TD colspan=2 background="images/table_top_line.gif"></TD>
				</TR>
				<tr>
					<td class="table_cell" align="center">���� <?= $title ?></td>
					<td class="table_cell" align="center">����</td>
				</tr>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<tr>
					<td class="td_con2" align="center"><?= $cf_num ?> <?= $unit ?></td>
					<td class="td_con1" align="center"><?= $rq_num ?> <?= $unit ?>�� ���� ��û &nbsp;
						<button style="border:1px solid #939393;color:#ffffff;background-color:#000000" onclick="conf('Y');">����</button>
						&nbsp;
						<button style="border:1px solid #939393;color:#ffffff;background-color:#000000" onclick="conf('N');">�ź�</button>
				</tr>				
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				
			</table>
		<? }else {?>
			<table border=0 cellpadding=0 cellspacing=0 width=350 style="table-layout:fixed">
				<col width=100></col>
				<col width=></col>
				<TR>
					<TD colspan=2 background="images/table_top_line.gif"></TD>
				</TR>
				<tr>
					<td class="table_cell" align="center">���� <?= $title ?></td>
					<td class="table_cell" align="center">����</td>
				</tr>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<tr>
					<td class="td_con2" align="center"><?= $cf_num ?> <?= $unit ?></td>
					<td class="td_con1" align="center">
					<? if ($com['status']=="2") { ?>
						����
					<? }else{ ?>
						��û�ź�
					<? } ?>
					</td>
				</tr>				
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
			</table>
		<? } ?>
		</td>
	</tr>
	<tr><td height=30></td></tr>
	<tr>
		<td align="center">
			<table border=0 cellpadding=0 cellspacing=0 width=350 style="table-layout:fixed">
				<col width=100></col>
				<col width=></col>
				<TR>
					<TD colspan=2 background="images/table_top_line.gif"></TD>
				</TR>
				<tr>
					<td class="table_cell" align="center">�� <?= $title ?></td>
					<td class="td_con1" align="left">&nbsp;
						<input type="text" size="10" class="input" name="new_com" id="new_com"/>
						&nbsp;
						<button style="border:1px solid #939393;color:#ffffff;background-color:#000000" onclick="sendit();">����</button>
					</td>
				</tr>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
			</table>
		</td>
	</tr>
	<tr><td height=10></td></tr>
	<tr>
		<td align=center><input type="image" src="images/btn_close.gif" width="36" height="18" border="0" vspace="0" hspace="2" onclick="window.close();">
		</td>
	</tr>
	<tr><td height=10></td></tr>
	</table>

	</td>
</tr>
</table>
</form>
</body>
</html>