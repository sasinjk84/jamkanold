<?
	$Dir="../";
	include_once($Dir."lib/init.php");
	include_once($Dir."lib/lib.php");
	include_once($Dir."lib/shopdata.php");
	INCLUDE ("access.php");


	$mode = "csInsert";


	// �ֹ�����
	$orderSQL = "SELECT `ordercode`,`id`,`sender_name` FROM `tblorderinfo` WHERE `ordercode` = '".$_GET['o']."' LIMIT 1 ; ";
	$orderResult=mysql_query($orderSQL,get_db_conn());
	$orderRow=mysql_fetch_assoc ($orderResult);


	// ��ǰ����
	$productSQL = "SELECT * FROM `tblorderproduct` WHERE `ordercode`='".$_GET['o']."' AND `productcode`='".$_GET['p']."' LIMIT 1 ; ";
	$productResult=mysql_query($productSQL,get_db_conn());
	$productRow=mysql_fetch_assoc ($productResult);

	//N:��ó��, X:��ۿ�û, S:�߼��غ�, Y:��ۿϷ�, C:�ֹ����, R:�ݼ�, D:��ҿ�û, E:ȯ�Ҵ��[��������� ��츸]
	$productState = array (
		"N"=>"��ó��",
		"X"=>"��ۿ�û",
		"S"=>"�߼��غ�",
		"Y"=>"��ۿϷ�",
		"C"=>"�ֹ����",
		"R"=>"�ݼ�",
		"D"=>"��ҿ�û",
		"E"=>"ȯ�Ҵ��"
	);


	// ��������
	$venderSQL = "SELECT * FROM `tblvenderinfo` WHERE `vender`='".$_GET['v']."' LIMIT 1 ";
	$venderResult=mysql_query($venderSQL,get_db_conn());
	$venderRow=mysql_fetch_assoc ($venderResult);


	// ������
	if( substr($_GET['o'],-1) == "X" ) {
		$memInfo = $orderRow['sender_name']."(��ȸ������)";
		$memID = $orderRow['sender_name'];
	} else {
		$memberSQL = "SELECT * FROM `tblmember` WHERE `id`='".$orderRow['id']."' LIMIT 1 ";
		$memberResult=mysql_query($memberSQL,get_db_conn());
		$memberRow=mysql_fetch_assoc ($memberResult);
		$memInfo = $memberRow['name']."(".$memberRow['id'].")";
		$memID = $memberRow['id'];
	}


	// CS ����
	$idx = "";
	$csSQL = "SELECT * FROM `tbl_csManager` WHERE `order`='".$_GET['o']."' AND `product`='".$_GET['p']."' LIMIT 1 ";
	$csResult=mysql_query($csSQL,get_db_conn());
	if( $csRow=mysql_fetch_assoc ($csResult) ) {
		$mode = "csModify";
		$idx = $csRow['idx'];
	}
?>

<link rel="stylesheet" href="style.css">
<script type="text/javascript">
<!--
	// �Է�
	function csOrder ( f ) {

		if( f.title.value == "" ) {
			f.title.focus();
			return false;
		}

		if( f.adminMemo.value == "" ) {
			f.adminMemo.focus();
			return false;
		}

		f.method = "POST";
		f.action = "cs_orderProcess.php";
		f.submit();
	}
//-->
</script>



<table border=0 cellpadding=0 cellspacing=0 width="100%">
	<tr>
		<td background="images/cs_title_bg.gif"><img src="images/cs_title.gif"></td>
	</tr>
</table>

<table border="0" cellpadding="0" cellspacing="0" width="96%" align="center" style="margin-top:15px;">
	<colgroup>
		<col width="100" bgcolor="#f0f0f0" style="padding-left:20px;"></col>
		<col bgcolor="#f9f9f9" style="padding:5px 5px 5px 10px;"></col>
	</colgroup>
	<form name="csOrderForm">
	<tr><td height="1" bgcolor="#c0c0c0" colspan="2"></td></tr>
	<tr>
		<td class="lineleft">����</td>
		<td>
			<select class="select" name="type">
				<option value="10" <?=( $csRow['type']=="10" )?"selected":""?>>�±�ȯ���</option>
				<option value="11" <?=( $csRow['type']=="11" )?"selected":""?>>��ǰ���� �±�ȯ���</option>
				<option value="12" <?=( $csRow['type']=="12" )?"selected":""?>>������߼�</option>
				<option value="13" <?=( $csRow['type']=="13" )?"selected":""?>>���񽺹߼�</option>
				<option value="21" <?=( $csRow['type']=="21" )?"selected":""?>>��ǰ����</option>
				<option value="31" <?=( $csRow['type']=="31" )?"checked":""?>>�������ǻ���</option>
				<option value="39" <?=( $csRow['type']=="39" )?"checked":""?>>��Ÿ</option>
			</select>
		</td>
	</tr>
	<tr><td height="1" bgcolor="#dedede" colspan="2"></td></tr>
	<tr>
		<td>�����ɼ�</td>
		<td>
			��ü���<input type="checkbox" name="delivery" value="vender" <?=( $csRow['delivery']=="vender" )?"checked":""?>>
			<!-- �����<input type="checkbox" name="customer" value="1" <?=( $csRow['customer']=="1" )?"checked":""?>> -->
		</td>
	</tr>
	<tr><td height="1" bgcolor="#dedede" colspan="2"></td></tr>
	<tr>
		<td>��ü����</td>
		<td>
			<?=$venderRow['com_name']?>(<?=$venderRow['id']?>)
			<input type="hidden" name="vender" value="<?=$_GET['v']?>">
		</td>
	</tr>
	<tr><td height="1" bgcolor="#dedede" colspan="2"></td></tr>
	<tr>
		<td>�ֹ���ȣ</td>
		<td>
			<b><?=$_GET['o']?></b>
			<input type="hidden" name="order" value="<?=$_GET['o']?>">
		</td>
	</tr>
	<tr><td height="1" bgcolor="#dedede" colspan="2"></td></tr>
	<tr>
		<td>��ǰ�ڵ�</td>
		<td>
			<input type="hidden" name="product" value="<?=$_GET['p']?>">


			<table border=0 cellpadding=2 cellspacing=1 width="100%" bgcolor="#dedede">
				<col width=50></col>
				<col width=50></col>
				<col width=120></col>
				<col width=></col>
				<col width=50></col>
				<col width=30></col>
				<tr align='center' bgcolor="#f0f0f0">
					<td>����</td>
					<td>�̹���</td>
					<td>��ǰ�ڵ�</td>
					<td>��ǰ��</td>
					<td>�ǸŰ�</td>
					<td>����</td>
				</tr>
				<tr align='center' bgcolor="#ffffff">
					<td><?=$productState[$productRow['deli_gbn']]?></td>
					<td><img src="/data/shopimages/product/<?=$productRow['productcode']?>3.jpg" width="50" onerror="this.src='/images/no_img.gif';"></td>
					<td><?=$productRow['productcode']?></td>
					<td>
						<?=$productRow['productname']?>
						<?=($productRow['opt1_name'])?"(�ɼ�1:".$productRow['opt1_name'].")":""?>
						<?=($productRow['opt2_name'])?"(�ɼ�2:".$productRow['opt2_name'].")":""?>
					</td>
					<td><?=number_format($productRow['price'])?></td>
					<td><?=$productRow['quantity']?></td>
				</tr>
			</table>


		</td>
	</tr>
	<tr><td height="1" bgcolor="#dedede" colspan="2"></td></tr>
	<tr>
		<td>������</td>
		<td>
			<?=$memInfo?>
			<input type="hidden" name="member" value="<?=$memID?>">
		</td>
	</tr>




	<!-- CS ���� -->
	<tr><td height="1" bgcolor="#dedede" colspan="2"></td></tr>
	<tr>
		<td>����</td>
		<td>
			<input type="text" name="title" value="<?=$csRow['title']?>" style="width:100%;">
		</td>
	</tr>
	<tr><td height="1" bgcolor="#dedede" colspan="2"></td></tr>
	<tr>
		<td>���޻���</td>
		<td>
			<textarea name="adminMemo" style="width:100%; height:100px;"><?=$csRow['adminMemo']?></textarea>
		</td>
	</tr>

	<input type="hidden" name="mode" value="<?=$mode?>">
	<input type="hidden" name="idx" value="<?=$idx?>">

	</form>

	<tr><td height="1" bgcolor="#dedede" colspan="2"></td></tr>
	<tr>
		<td height="30" colspan="2" align="center" bgcolor="#ffffff"><input type="image" src="images/btn_ok1.gif" border="0" onclick="csOrder(csOrderForm);"><a href="javascript:window.close()"><img src="images/btn_close.gif" width="36" height="18" border="0" vspace="0" border=0 hspace="2"></a></td>
	</tr>
</table>
