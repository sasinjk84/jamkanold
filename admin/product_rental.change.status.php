<?php
session_start();
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/shopdata.php");
INCLUDE ("access.php");

$ordercode = $_GET['ord'];
$basketidx = $_GET['bskidx'];
//_pr($_POST);


$mode = $_POST['mode'];
$m_status = $_POST['status'];
$p_status = $_POST['prd_status'];
$statusinput = $_POST['statusinput'];

if ($mode == "modify") {
	// 예약/렌탈 상태 변경 시
	$m_sql  = "UPDATE rent_schedule SET status = '{$m_status}' ";
	$m_sql .= "WHERE ordercode = '{$ordercode}' AND basketidx = {$basketidx}";
	mysql_query($m_sql, get_db_conn());

	$onload = '<script type="text/javascript">opener.window.location.reload();window.close();</script>';
} else if ($mode == "change_RP") {
	// 제품 상태 변경 시

	$p_sql  = "UPDATE rent_schedule SET prdCheck = '{$p_status}' ";
	$p_sql .= "WHERE ordercode = '{$ordercode}' AND basketidx = {$basketidx}";
	mysql_query($p_sql, get_db_conn());

	if ($p_status == "정상대여가능") {
		$optidx = $_POST['optidx'];
		$quantity = $_POST['quantity'];

		$quan_sql  = "UPDATE rent_product_option SET productCount = productCount+'{$quantity}' ";
		$quan_sql .= "WHERE idx = '{$optidx}'";
		mysql_query($quan_sql, get_db_conn());
	}
} else if($mode == "status_Memo"){
	$i_sql = "UPDATE rent_schedule SET memo = '{$statusinput}'";
	$i_sql .= "WHERE ordercode = '{$ordercode}' AND basketidx = {$basketidx}";
	mysql_query($i_sql, get_db_conn());
}

$sql2 = "SELECT memo from rent_schedule WHERE ordercode = '{$ordercode}' AND basketidx = {$basketidx}";
$result2 = mysql_query($sql2, get_db_conn());
$row2 = mysql_fetch_object($result2);

$sql  = "SELECT rs.*, oi.sender_name, oi.sender_tel,op.productname,op.opt1_name, op.opt2_name, productcode FROM rent_schedule AS rs ";
$sql .= "INNER JOIN tblorderinfo AS oi ON oi.ordercode=rs.ordercode ";
$sql .= "LEFT JOIN tblorderproduct AS op ON op.ordercode=rs.ordercode AND op.basketidx=rs.basketidx ";
$sql .= "WHERE rs.ordercode = '{$ordercode}' AND rs.basketidx = {$basketidx}";
$result = mysql_query($sql, get_db_conn());
$row = mysql_fetch_object($result);

$status = rentProduct::_bookingStatus($row->status);

$disable_arr = array('NN','NC','BR','BC','BO','BI');
$status_disabled = "";
if (in_array($row->status, $disable_arr)) {
	$status_disabled = " disabled='true' ";
}
?>

<!DOCTYPE HTML>
<html lang="ko-KR">
<head>
	<meta charset="EUC-KR">
	<title>예약/렌탈 상태 변경<?=" - ".$_data->shoptitle?></title>
	<script type="text/javascript" src="/js/jquery-1.10.2.min.js"></script>
	<script language="JavaScript">
		function ProductInfo(code,prcode,popup) {
			document.form_reg.code.value=code;
			document.form_reg.prcode.value=prcode;
			document.form_reg.popup.value=popup;
			if (popup=="YES") {
				document.form_reg.action="product_register.add.php";
				document.form_reg.target="register";
				window.open("about:blank","register","width=1000,height=700,scrollbars=yes,status=no");
			} else {
				document.form_reg.action="product_register.php";
				document.form_reg.target="";
			}
			document.form_reg.submit();
		}
	</script>

	<style>
		body{width:100%;}
		div,table,select,form{margin:0px;padding:0px;font-size:12px;}
		h1{height:38px;line-height:38px;padding-left:15px;font-size:12px;color:#ffffff;background:#000000;}
		.subText{margin-left:15px;font-size:11px;letter-spacing:-1px;}
		.tbl_base_style{width:96%;margin:0 auto;margin-bottom:20px;border-top:1px solid #222222;text-align:left;}
		.tbl_base_style caption{padding-bottom:5px;text-align:left;font-weight:bold;}
		.tbl_base_style th{width:25%;padding:10px 15px;border-right:1px solid #e5e5e5;border-bottom:1px solid #e5e5e5;background:#f8f8f8;}
		.tbl_base_style td{width:75%;padding:10px;border-bottom:1px solid #e5e5e5;}

		.btn_close{margin-top:20px;text-align:center;}
	</style>

</head>
<body topmargin="0" leftmargin="0">
<h1>예약/렌탈 상태 변경</h1>
<p class="subText">- 예약 및 렌탈 상품의 상태를 변경할 수 있습니다.</p>

<table WIDTH="100%" BORDER="0" CELLPADDING="0" CELLSPACING="0" class="tbl_base_style">
	<caption>* 상품 정보</caption>
	<tbody>
		<tr>
			<th>상품명</th>
			<td><?=$row->productname?></td>
		</tr>
		<tr>
			<th>옵션</th>
			<td><?=$row->opt1_name?><?php echo ($row->opt2_name)?" ({$row->opt2_name})":"";?></td>
		</tr>
	</tbody>
</table>

<table WIDTH="100%" BORDER="0" CELLPADDING="0" CELLSPACING="0" class="tbl_base_style">
	<caption>* 예약 정보</caption>
	<tbody>
		<tr>
			<th>예약자</th>
			<td><?=$row->sender_name?></td>
		</tr>
		<tr>
			<th>전화</th>
			<td><?=$row->sender_tel?></td>
		</tr>
		<tr>
			<th>기간</th>
			<td><?=$row->start.' ~ '.$row->end?></td>
		</tr>
		<tr>
			<th>수량</th>
			<td><?=$row->quantity.' 개'?></td>
		</tr>
		<tr>
			<th>예약/렌탈 상태</th>
			<td>
				<form name="form1" action="<?=$_SERVER["PHP_SELF"]?>?ord=<?=$ordercode?>&bskidx=<?=$basketidx?>" method="POST">
					<input type="hidden" name="mode" value="modify" />
					<select name="status" id="status"<?=$status_disabled?>>
					<?
						$rent_status = rentProduct::_bookingStatus();
						foreach($rent_status AS $key => $val) {
							$opt_disable = (in_array($key, $disable_arr)) ? " disabled=\"true\" ":"";
							$selected = ($val == $status) ? " selected=\"selected\" ":"";
							echo "<option value=\"{$key}\"{$selected}{$opt_disable}>{$val}</option>";
						}
					?>
					</select>
				</form>
				<p style="font-size:11px;margin:0;margin-top:5px;">※ 시스템 대여종료까지는 자동처리되며, 대여종료 완료 후 관리자가 반납상태를 변경관리할 수 있습니다.<br/>※ 관리자가 반납완료 처리 후 제품(정비)상태를 변경할 수 있는 선택기능이 노출됩니다.</p>
			</td>
		</tr>
		<?php
			if (in_array($row->status, array('CE','NR'))) {
				$disabled = ($row->prdCheck == "정상대여가능") ? " disabled='true'" : "";
		?>
		<tr>
			<th>제품 정비 상태</th>
			<td>
				<form name="form2" action="<?=$_SERVER["PHP_SELF"]?>?ord=<?=$ordercode?>&bskidx=<?=$basketidx?>" method="POST">
					<input type="hidden" name="mode" value="change_RP" />
					<input type="hidden" name="optidx" value="<?=$row->optidx?>" />
					<input type="hidden" name="quantity" value="<?=$row->quantity?>" />
					<select name="prd_status" id="prd_status"<?=$disabled?>>
						<option value="제품점검"<?= ($row->prdCheck=="제품점검")?" selected='selected'":"" ?>>제품점검</option>
						<option value="수리중"<?= ($row->prdCheck=="수리중")?" selected='selected'":"" ?>>수리중</option>
						<option value="수리완료"<?= ($row->prdCheck=="수리완료")?" selected='selected'":"" ?>>수리완료</option>
						<option value="정상대여가능"<?= ($row->prdCheck=="정상대여가능")?" selected='selected'":"" ?>>정상대여가능</option>
					</select>
					<button onclick="location.href='JavaScript:ProductInfo(\'<?=substr($row->productcode,0,12)?>\',\'<?=$row->productcode?>\',\'YES\')'">제품상태변경</button>
				</form>
				<p style="font-size:11px;margin:0;margin-top:5px;">※ 정상대여가능을 선택하면 더 이상 선택변경이 불가능 해지며, 상품 재고에 상품이 추가됩니다.</p>
				<form name="form3" action="<?=$_SERVER["PHP_SELF"]?>?ord=<?=$ordercode?>&bskidx=<?=$basketidx?>" method="POST">
					<input type="hidden" name="mode" value="status_Memo">
					<textarea size="40" name="statusinput"><?=$row2->memo?></textarea>
					<input type="button" onclick="statusmemo()" value="저장" name="save">
				</form>
			</td>
		</tr>
		<?php } ?>
	</tbody>
</table>

<div class="btn_close"><a href="javascript:window.close();"><img src="images/btn_close.gif" border="0" alt="" /></a></div>

<form name=form_reg action="product_register.php" method=post>
	<input type=hidden name=code>
	<input type=hidden name=prcode>
	<input type=hidden name=popup>
</form>

<?=$onload;?>

<script type="text/javascript">

$(document).ready(function() {
	$('#status').on('change', function() {
		if (confirm("예약/렌탈 상태를 ["+$('#status option:selected').text()+"]로 변경하시겠습니까?")) {
			document.form1.submit();
		}
	});

	$('#prd_status').on('change', function() {
		if (confirm("제품 상태를 ["+$('#prd_status option:selected').text()+"]로 변경하시겠습니까?")) {
			if ($('#prd_status option:selected').val() == "정상대여가능") {
				if (confirm("제품의 재고가 추가되고, 이전 상태로 되돌릴 수 없습니다.\n[정상대여가능]상태로 바꾸시겠습니까?")) {
					document.form2.submit();
				}
			} else {
				document.form2.submit();
			}
		}
	});
});

function statusmemo(){
	if(confirm("제품 정비 상태를 입력하시겠습니까?")){
		document.form3.submit();
	}
}

</script>
</body>
</html>