<?
// ajax json 을 통한 백그라운드 실행 처리용 파일
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
		$result['items'] = "24시간제<br>";
		
		if($row->halfday=="Y"){
			$result['items'].="당일 12시간 대여허용<br>"; 
			$result['items'].="당일 12시간 요금: 24시간 요금의 ".$row->halfday_percent."%<br>";

			if($row->oneday_ex=="day") $result['items'].="1일 단위초과시 과금<br>"; 
			else if($row->oneday_ex=="time"){
				$result['items'].="1시간 단위초과시 과금<br>";
				$result['items'].="(추가 1시간 요금: 24시간 요금의 ".$row->time_percent.")<br>";
			}
		}else{
			$result['items'].="당일 12시간 대여허용안함<br>"; 
		}
	}else if($row->pricetype == 'time'){
		$result['items'] = "1시간제<br>";
		$result['items'].="기본요금 : ".$row->base_time."시간 ".number_format($row->base_price)."원<br>"; 
		$result['items'].="추가시간당 : ".number_format($row->timeover_price)."원"; 
	}else if($row->pricetype == 'checkout'){
		$result['items'] = "일일제(숙박제)";
		$result['items'] .= $row->checkin_time."시 ~ ".$row->checkout_time."시";
	}

}else if($_REQUEST['act']=="longrent"){
	$result['items'] = "";
	$sql = "SELECT * FROM rent_longrent WHERE code='".$code."' ";
	$res = mysql_query($sql,get_db_conn());
	while($row = mysql_fetch_object($res)){
		//$result['items'] .= $row->day."일 이상 ".$row->percent."% ";
		$result['items'] .= "<div><input type='hidden' name='refundday[]' value='".$row->day."'><input type='hidden' name='refundpercent[]' value='".$row->percent."'><span style='float:left'>".$row->day." 일전 ".$row->percent."%</span></div>";
	}
}else if($_REQUEST['act']=="refund"){
	$result['items'] = "";
	$sql = "SELECT * FROM rent_refund WHERE code='".$code."' ";
	$res = mysql_query($sql,get_db_conn());
	while($row = mysql_fetch_object($res)){
		//$result['items'] .= $row->day."일 이상 ".$row->percent."% ";
		$result['items'] .= "<div><input type='hidden' name='longrent_sday[]' value='".$row->sday."'><input type='hidden' name='longrent_eday[]' value='".$row->eday."'><input type='hidden' name='longrent_percent[]' value='".$row->percent."'><span style='float:left'>".$row->sday."~".$row->eday." 일 ".$row->percent."% 추가</span></div>";
	}
}else if($_REQUEST['act']=="longdiscount"){
	$result['items'] = "";
	$sql = "SELECT * FROM rent_longdiscount WHERE code='".$code."' ";
	$res = mysql_query($sql,get_db_conn());
	while($row = mysql_fetch_object($res)){
		//$result['items'] .= $row->day."일 이상 ".$row->percent."% ";
		$result['items'] .= "<div><input type='hidden' name='discrangeday[]' value='".$row->day."'><input type='hidden' name='discrangepercent[]' value='".$row->percent."'><span style='float:left'>".$row->day." 일전 ".$row->percent."%</span></div>";
	}
}else if($_REQUEST['act']=="season"){
	$sql = "SELECT useseason FROM code_rent WHERE code='".$code."' ";
	$res = mysql_query($sql,get_db_conn());
	$row = mysql_fetch_object($res);
	mysql_free_result($res);

	if($row->useseason == '0') $result['items'] = "사용안함";
	else if($row->useseason == '1') $result['items'] = "성수기/비성수기사용";

}

//$result['items'] = $sql;

if(PHP_VERSION > '5.2') array_walk($result,'_encode');
exit(json_encode($result));
?>
