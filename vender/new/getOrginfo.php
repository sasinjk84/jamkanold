<?
// ajax json �� ���� ��׶��� ���� ó���� ����
error_reporting(0);
$Dir="../../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/ext/product_func.php");

$result = array();
array_walk($_REQUEST,'_iconvFromUtf8');

$code = $_REQUEST['code']."000000000";

if($_REQUEST['act']=="price"){
	$sql = "SELECT * FROM code_rent WHERE code='".$code."' ";
	$res = mysql_query($sql,get_db_conn());
	$row = mysql_fetch_object($res);
	mysql_free_result($res);
	
	$result['item_main'] = $row->pricetype;
	$result['halfday'] = $row->halfday;
	$result['halfday_percent'] = $row->halfday_percent;
	$result['oneday_ex'] = $row->oneday_ex;
	$result['time_percent'] = $row->time_percent;
	$result['base_time'] = $row->base_time;
	$result['base_price'] = $row->base_price;
	$result['timeover_price'] = $row->timeover_price;
	$result['checkin_time'] = $row->checkin_time;
	$result['checkout_time'] = $row->checkout_time;

	if($row->pricetype == 'day'){ 
		$result['items'] = "24�ð���<br>";
		
		if($row->halfday=="Y"){
			$result['items'].="���� 12�ð� �뿩���<br>"; 
			$result['items'].="���� 12�ð� ���: 24�ð� ����� ".$row->halfday_percent."%<br>";

			if($row->oneday_ex=="day") $result['items'].="1�� �����ʰ��� ����<br>"; 
			else if($row->oneday_ex=="time"){
				$result['items'].="1�ð� �����ʰ��� ����<br>";
				$result['items'].="(�߰� 1�ð� ���: 24�ð� ����� ".$row->time_percent.")<br>";
			}
		}else{
			$result['items'].="���� 12�ð� �뿩������<br>"; 
		}
	}else if($row->pricetype == 'time'){
		$result['items'] = "1�ð���<br>";
		$result['items'].="�⺻��� : ".$row->base_time."�ð� ".number_format($row->base_price)."��<br>"; 
		$result['items'].="�߰��ð��� : ".number_format($row->timeover_price)."��"; 
	}else if($row->pricetype == 'checkout'){
		$result['items'] = "������(������)";
		$result['items'] .= $row->checkin_time."�� ~ ".$row->checkout_time."��";
	}

}else if($_REQUEST['act']=="longrent"){
	$result['items'] = "";
	$sql = "SELECT * FROM rent_longrent WHERE code='".$code."' ";
	$res = mysql_query($sql,get_db_conn());
	while($row = mysql_fetch_object($res)){
		//$result['items'] .= $row->day."�� �̻� ".$row->percent."% ";
		$result['items'] .= "<div><input type='hidden' name='refundday[]' value='".$row->day."'><input type='hidden' name='refundpercent[]' value='".$row->percent."'><span style='float:left'>".$row->day." ���� ".$row->percent."%</span></div>";
	}
}else if($_REQUEST['act']=="refund"){
	$result['items'] = "";
	$sql = "SELECT * FROM rent_refund WHERE code='".$code."' ";
	$res = mysql_query($sql,get_db_conn());
	while($row = mysql_fetch_object($res)){
		//$result['items'] .= $row->day."�� �̻� ".$row->percent."% ";
		$result['items'] .= "<div><input type='hidden' name='longrent_sday[]' value='".$row->sday."'><input type='hidden' name='longrent_eday[]' value='".$row->eday."'><input type='hidden' name='longrent_percent[]' value='".$row->percent."'><span style='float:left'>".$row->sday."~".$row->eday." �� ".$row->percent."% �߰�</span></div>";
	}
}else if($_REQUEST['act']=="longdiscount"){
	$result['items'] = "";
	$sql = "SELECT * FROM rent_longdiscount WHERE code='".$code."' ";
	$res = mysql_query($sql,get_db_conn());
	while($row = mysql_fetch_object($res)){
		//$result['items'] .= $row->day."�� �̻� ".$row->percent."% ";
		$result['items'] .= "<div><input type='hidden' name='discrangeday[]' value='".$row->day."'><input type='hidden' name='discrangepercent[]' value='".$row->percent."'><span style='float:left'>".$row->day." ���� ".$row->percent."%</span></div>";
	}
}else if($_REQUEST['act']=="season"){
	$sql = "SELECT useseason FROM code_rent WHERE code='".$code."' ";
	$res = mysql_query($sql,get_db_conn());
	$row = mysql_fetch_object($res);
	mysql_free_result($res);

	if($row->useseason == '0') $result['items'] = "������";
	else if($row->useseason == '1') $result['items'] = "������/�񼺼�����";

}

//$result['items'] = $sql;

if(PHP_VERSION > '5.2') array_walk($result,'_encode');
exit(json_encode($result));
?>
