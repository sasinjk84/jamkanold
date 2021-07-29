<?php
require_once dirname(__FILE__).'/rent.php';

// �뿩 ���� ����Ʈ - todo
function productScheduleList( $data ) {
	global $BR_limit;
	if(_empty($BR_limit)) $BR_limit = rentProduct::$BR_limit;
	extract($data);
	$return = array();
	$sqlWhere = array();
	if( strlen($pridx) > 0 ) array_push($sqlWhere, " ps.`pridx` = ".$pridx." ");
	if( strlen($ordercode) > 0 ) array_push($sqlWhere, " ps.`ordercode` = '".$ordercode."' ");
	if( strlen($productcode) > 0 ) array_push($sqlWhere, " ps.`productcode` = '".$productcode."' ");
	if( strlen($location) > 0 ) array_push($sqlWhere, " ps.`location` = ".$location." ");
	if( strlen($dateStart) > 0 ) array_push($sqlWhere, " ps.`bookingStartDate` <= '".$dateStart."' ");
	if( strlen($dateEnd) > 0 ) array_push($sqlWhere, " ps.`bookingEndDate` >= '".$dateEnd."' ");
	if( strlen($memId) > 0 ) array_push($sqlWhere, " o.id = '".$memId."' ");
	$sqlWhere = " WHERE IF( ps.status = 'BR', ps.regDate >= date_add(now(), interval -".$BR_limit." hour) AND ps.status = 'BR', ps.status != 'BR' ) AND ".implode(" AND ", $sqlWhere);
	$sql = "
		SELECT
			ps.idx, ps.pridx, ps.options, ps.productcode, ps.ordercode, ps.location, ps.bookingStartDate, ps.bookingEndDate, ps.status, ps.regDate
			, o.id, o.sender_name as onnerName, o.sender_tel as onnerTel, o.sender_email as onnerMail, o.price, o.deli_price
			, op.productname
		FROM
			rent_product_schedule as ps
			INNER JOIN tblorderinfo as o ON o.ordercode = ps.ordercode
			INNER JOIN tblorderproduct as op ON op.ordercode = ps.ordercode AND op.productcode = ps.productcode
	";
	$sql = $sql.$sqlWhere;
//	echo $sql;
	$result=mysql_query($sql,get_db_conn());
	while ( $row=mysql_fetch_assoc($result) ) {
		$return[$row['idx']] = $row;
		$opt1 = explode("|",$row['options']);
		if( _array($opt1) ) {
			$return[$row['idx']]['optCnt'] = count($opt1)-1;
			foreach ( $opt1 as $v ) {
				if (strlen($v) > 0) {
					$opt2 = explode(":", $v);
					$optInfo = rentProductOptionInfo($opt2[0]);
					$return[$row['idx']]['opt'][$opt2[0]] = $optInfo;
					$return[$row['idx']]['opt'][$opt2[0]]['orderCnt'] = $opt2[1];
				}
			}
		}
	}
	return $return;
}



// �뿩 ��Ȳ - ��ǰ��� -- todo
// �ش� ��,��,�� ���� �˻��� ��ǰ �� �ɼ� ���
function bookingProductList( $type, $value ) { 
	if( strlen($value) == 0 ) {
		return array("err"=>"������");
	}
	$return = array();
	switch ( $type ) {
		// ������
		case 'M' :
			if(strlen($value) == 8){
				$sDate = substr($value,0,6)."01";
				$eDate = substr($value,0,6).date("t",strtotime($sDate));

			}else{				
				$sDate = $value."01";
				$eDate = $value.date("t",strtotime($sDate));
			}			
			break;

		// �ִ���
		case 'W' :
			if(strlen($value) == 8){
				$days = weekDays2($value);			
				$sDate = $days['sDate'];
				$eDate = $days['eDate'];
			}else{
				$days = weekDays($value);
				$sDate = $days['sDate'];
				$eDate = $days['eDate'];
			}
			break;

		// �ϴ���
		case 'D' :
			$sDate = $value;
			$eDate = $value;
			break;
		default :
			return array("err"=>"���Ÿ�Դ���");
	}
	$return = bookingProducts( $sDate, $eDate );
	return $return;
}


// �Ⱓ�� ���� ��ǰ -- todo
function bookingProducts( $start, $end,$pridx=NULL) {
	return rentProduct::rangeProduct($start,$end,$pridx);
}

// �Ⱓ�� ���º� ��ǰ ����
function bookingCount( $start, $end,$status) {
	return rentProduct::bookingCount($start,$end,$status);
}


// �ְ� �޷� (�⵵��) 201447 : 2014�� 47������
function weekDays ( $value ) {
	$years = substr($value,0,4);
	$weeks = substr($value,4,2);
	$firstday = strtotime($years."-01-01");
	$wday = date('w',$firstday)-1;
	$startDay = strtotime(($weeks-1)." week -".$wday." day", $firstday);
	$sDate = date("Y-m-d", $startDay);
	$eDate = date("Y-m-d", strtotime("+6 day",$startDay) );
	return array( "sDate" => $sDate, "eDate" => $eDate, "years"=>$years, "weeks"=>$weeks );
}

function weekDays2 ($value) { // ���ڰ� ����
	$stamp = strtotime($value);
	$weeks = date('W',$stamp);
	$years = date('Y',$stamp);
	$wday = date('w',$stamp);

	$startDay = strtotime('-'.$wday.' day', $stamp);
	$sDate = date("Y-m-d", $startDay);
	$eDate = date("Y-m-d", strtotime("+6 day",$startDay) );
	return array( "sDate" => $sDate, "eDate" => $eDate, "years"=>$years, "weeks"=>$weeks );
}


// ��Ʈ ��ǰ �ԱݿϷ� -- todo
function rentProdSchdCHG ( $where, $target="", $to ) {
	if( strlen($where) > 0 AND strlen($to) > 0 ) {
		$wheres = array();
		if( strlen($where) > 18 ) {
			array_push ($wheres, " ordercode = '".$where."' ");
		} else {
			array_push ($wheres, " idx = '".$where."' ");
		}
		if( strlen($target) > 0 ) {
			array_push ($wheres, " status = '".$target."' ");
		}
		$rentProdSchdSQL = "UPDATE rent_schedule SET status = '".$to."' WHERE ".implode (" AND ", $wheres);
	}
	if( mysql_query($rentProdSchdSQL,get_db_conn()) ) {
		return "OK";
	} else {
		return "ERR";
	}
}

// ��Ʈ ��ǰ ���� �Ⱓ�� ���.
// �����������ָ������ ���� > �ָ�������� > ��������
function rentSellPrice ($pridx,$option,$sDate, $eDate, $vender ){	
	$return = rentProduct::solvPrice($pridx,$option,$sDate, $eDate, $vender);
	return $return;	
}


// ��� �뿩 ����
function rentLongDiscPrice ($orgPrice, $sDate, $eDate ) {
	$return = array();
	$days = ( $eDate - $sDate ) / 86400;
	$discRate = rentalLongDiscount();
	$temp = 0;
	foreach ( $discRate as $k => $v ) {
		if( $days > $k ) {
			$temp = $v;
		}
	}
	$return['price'] = $orgPrice - ( ( $orgPrice / 100 ) * $temp );
	$return['rete'] = $temp;
	$return['days'] = $days;
	return $return;
}


// ������ ������


// ��ٱ��� -------------

// �뿩��ǰ ��ٱ��� �ɼ� �߰�
function rentBasketSave ( $tempkey, $basket, $productcode, $sdate, $edate, $options, $update = "" ) {
	$return = "err";
	
	$options = parseRentRequestOption($options);	
	$res = mysql_query("SELECT basketidx, ordertype FROM `".$basket."` WHERE tempkey = '".$tempkey."' AND productcode = '".$productcode."' ",get_db_conn());
	
	if(mysql_num_rows($res)){
		$row = mysql_fetch_assoc($res);
		
		if( $update == "update" ) {
			$basketSQL = "UPDATE rent_basket_temp SET sdate='".$sdate."', edate='".$edate."', options='".$options."' WHERE basketidx='".$row['basketidx']."' AND ordertype='".$row['ordertype']."'";
		} else {
			$basketSQL = "INSERT rent_basket_temp SET basketidx='".$row['basketidx']."', ordertype='".$row['ordertype']."', sdate='".$sdate."', edate='".$edate."', options='".$options."' ";
		}
		$basketRes = mysql_query($basketSQL,get_db_conn());
		if( $basketRes ) {
			$return = "ok";
		} else {
			$return = "db insert err";
		}
	}
	return $return;
}


// �뿩 ��ǰ ��ٱ��� ���� ����
function rentBasketInfo ($tempkey, $basket) {
	$baskets = explode("_",$basket);
	$sql="SELECT sdate, edate, options FROM rent_basket_temp WHERE basketidx = '".$tempkey."' AND ordertype = '".$baskets[1]."' ";
	$res = mysql_query($sql,get_db_conn());
	$row = mysql_fetch_assoc($res);
	return $row;
}


// �뿩 �Ⱓ ���� ���� (pridx�� ��ǰ�ڵ�� ����ص� ��)
function rentOrderChecker ($pridx,$sdate, $edate, $rentOptionList ) {
	$return = "pass";
	if(isRentProduct($pridx) && !_empty($sdate) && !_empty($edate) && !_empty($rentOptionList)) {		
		$options =  parseRentRequestOption($rentOptionList);	
		if(!_array($options)) $return = '�ɼ� ���� ����';
		else{
			$schedule = ProductRentSchedule($pridx,$sdate,$edate);					
			if($schedule['err'] != 'success'){
				$return = $schedule['err'];
			}else{			
				foreach($options as $idx=>$cnt){
					if(isset($schedule['options'][$idx])){
						$opmaxcnt = $schedule['options'][$idx]['productCount'];
						foreach($schedule['optschedule'][$idx] as $date=>$rentcnt){
							if($cnt+$rentcnt > $opmaxcnt){
								$return = $date.' ���� �Ұ�('.($opmaxcnt-$cnt).' �ǿ��డ��)';
								break;
							}
						}
					}else{
						$return = '�ɼ� �����ĺ� ��ȣ�� �ùٸ��� �ʽ��ϴ�.';
					}
				}
			}
		}
	}
	return $return;
}

// �뿩 �ֹ� �߰�
function rentOrderEnd( $primarykey, $ordertype, $status ) {
	// rent.php -> syncOrderRent();
	if (strlen($primarykey) >= 20 && strlen($primarykey) <= 21) {
		syncOrderCodeRent($primarykey, $ordertype, $status);
	} else {
		syncOrderRent($primarykey, $ordertype, $status);
	}
}


// �뿩��ǰ ���� (������) ��� ��å ����
function rentProductSeasonPrice($pridx , $idx) {
	//$selSQL = "SELECT * FROM rent_seasonPrice WHERE pridx = " . $pridx . " ;";
	$selSQL = "SELECT * FROM rent_product_option WHERE pridx = " . $pridx . " AND idx = ".$idx." ;";
	$selRES = mysql_query($selSQL, get_db_conn());
	$selROW = mysql_fetch_assoc($selRES);
	return $selROW;
}


// �ָ������ �߰� Ȯ��
/*
function rentHolidayInfo ( $date ){
	$SQL = "SELECT * FROM rent_seasonSet_holiday WHERE date = ".$date;
	$RES = mysql_query($SQL,get_db_conn());
	$ROW = mysql_fetch_assoc($RES);
	return $ROW;
}
*/
function rentHolidayInfo ($date,$code=''){
	$incode = array('000000000000');
	if(preg_match('/^[0-9]{12}$/',$code)) array_push($incode,$code);
	$SQL = "SELECT * FROM holiday_list WHERE code in ('".implode("','",$incode)."') and (year is null || year ='".substr($date,0,4)."') and  date='".substr($date,4,4)."'";	
	if(false !== $RES = mysql_query($SQL,get_db_conn())){
		return mysql_num_rows($RES)>0;
	}
	else return false;
}

function rentHolidayMonth($date,$code=''){
	$incode = array('000000000000');
	if(preg_match('/^[0-9]{12}$/',$code)) array_push($incode,$code);
	
	$return = array('year'=>substr($date,0,4),'month'=>substr($date,4,2),'days'=>array());
	
	$SQL = "SELECT * FROM holiday_list WHERE code in ('".implode("','",$incode)."') and (year is null || year ='".$return['year']."') and  date like '".$return['month']."%'";		
	if(false !== $RES = mysql_query($SQL,get_db_conn())){
		if(mysql_num_rows($RES)){
			while($item = mysql_fetch_assoc($RES)){
				$return['days'][substr($item['date'],2,2)]= $item['title'];
			}
		}
	}	
	
	return $return;
}


//�ָ������ȸ(��,��,��)
function rentHolidayInfo2 ($code=''){
	$incode = array('000000000000');
	if(preg_match('/^[0-9]{12}$/',$code)) array_push($incode,$code);
	$SQL = "SELECT * FROM holiday_list WHERE code in ('".implode("','",$incode)."') and title in ('sat','sun','fri')";
	if(false !== $RES = mysql_query($SQL,get_db_conn())){
		if(mysql_num_rows($RES)){
			while($item = mysql_fetch_assoc($RES)){
				$return['days'][$item['title']]= $item['date'];
			}
		}
	}
	else return false;
}

function vender_rentHolidayInfo ($vender,$pridx){
	$return = array();
	$sql_ = "select * from vender_holiday_list where vender='".$vender."' and pridx='".$pridx."'";
	if(false !== $res_ = mysql_query($sql_,get_db_conn())){
		if(mysql_num_rows($res_)){
			$vender_where = " and pridx='".$pridx."'";
		}else{
			$vender_where = " and pridx='0'";
		}
	}else{
		$vender_where = " and pridx='0'";
	}	

	$SQL = "select * from vender_holiday_list where vender='".$vender."' ".$vender_where." and title in ('sat','sun','fri')";
	if(false !== $RES = mysql_query($SQL,get_db_conn())){
		if(mysql_num_rows($RES)){
			while($item = mysql_fetch_assoc($RES)){
				$return['days'][$item['title']]= $item['date'];
			}
		}
	}
	else return false;

	return $return;
}

function vender_rentHolidayMonth($date,$vender,$pridx){
		
	$return = array('year'=>substr($date,0,4),'month'=>substr($date,4,2),'days'=>array());

	$sql_ = "select * from vender_holiday_list where vender='".$vender."' and pridx='".$pridx."'";
	if(false !== $res_ = mysql_query($sql_,get_db_conn())){
		if(mysql_num_rows($res_)){
			$vender_where = " and pridx='".$pridx."'";
		}else{
			$vender_where = " and pridx='0'";
		}
	}else{
		$vender_where = " and pridx='0'";
	}	

	$SQL = "select * from vender_holiday_list where vender='".$vender."' ".$vender_where." and (year is null || year ='".$return['year']."') and  date like '".$return['month']."%' or title in ('sat','sun','fri')";
	if(false !== $RES = mysql_query($SQL,get_db_conn())){
		if(mysql_num_rows($RES)){
			while($item = mysql_fetch_assoc($RES)){
				if($item['title']=="sun" || $item['title']=="sat" || $item['title']=="fri"){
					$return['days'][$item['date']]= $item['title'];
				}else{
					$return['days'][substr($item['date'],2,2)]= $item['title'];
				}
			}
		}
	}
	
	return $return;
}



// ������/�ؼ����� Ȯ��
/*
function rentBusySeasonInfo ( $type, $date ){
	$SQL = "SELECT * FROM rent_seasonSet_season WHERE `type` = '".$type."' AND `start` <= ".$date." AND `end` >= ".$date;
	$RES = mysql_query($SQL,get_db_conn());
	$ROW = mysql_fetch_assoc($RES);
	return $ROW;
}
*/
function rentBusySeasonInfo ( $type, $date,$code){	
	if(!preg_match('/^[0-9]{12}$/',$code)) return NULL;
	$SQL = "SELECT * FROM season_range WHERE code='".$code."' and `type` = '".$type."' AND `start` <= ".$date." AND `end` >= ".$date;
	$RES = mysql_query($SQL,get_db_conn());
	$ROW = mysql_fetch_assoc($RES);
	return $ROW;
}

function rentBusySeasonRange($code,$date){
	if(!preg_match('/^[0-9]{12}$/',$code)) return NULL;
	$SQL = "SELECT * FROM season_range WHERE code='".$code."' and `start` <= ".$date." AND `end` >= ".$date;

	$stamp =strtotime(substr($date,0,4).'-'.substr($date,4,2).'-01');
	$seasonStartDate = date('Y-m-d',$stamp);
	$seasonEndDate = date('Y-m-t',$stamp);
	$sql = "select * from season_range where code='".$code."' and ((start between '".$seasonStartDate."' and '".$seasonEndDate."' and  end between '".$seasonStartDate."' and '".$seasonEndDate."') ||  (start <= '".$seasonStartDate."' and end >= '".$seasonEndDate."'))";
	$return = array('year'=>date('Y',$stamp),'month'=>date('m',$stamp),'busy'=>array(),'semi'=>array());
	if(false !== $res = mysql_query($sql,get_db_conn())){
		if(mysql_num_rows($res)){
			while($item = mysql_fetch_assoc($res)){							
				$tarr = &$return[$item['type']];
				$st = (date('Y-m-d',$stamp) >= $item['start'])?1:intval(substr($item['start'],-2));
				$ed = (date('Y-m-t',$stamp) <= $item['end'])?intval(date('t',$stamp)):intval(substr($item['end'],-2));
				for($i=$st;$i<=$ed;$i++){
					array_push($tarr,sprintf('%02d',$i));
				}
			}
		}
	}	
	return $return;
}


//������ü��������
function vender_rentBusySeasonInfo ( $type, $date,$vender,$pridx){	

	$SQL = "SELECT * FROM vender_season_range WHERE vender='".$vender."' and pridx='".$pridx."' and `type` = '".$type."' AND `start` <= ".$date." AND `end` >= ".$date;
	$RES = mysql_query($SQL,get_db_conn());
	$ROW = mysql_fetch_assoc($RES);
	return $ROW;
}

function vender_rentBusySeasonRange($vender,$pridx,$date){
	
	$sql_ = "SELECT * FROM vender_season_range WHERE vender='".$vender."' and pridx='".$pridx."'";
	if(false !== $res_ = mysql_query($sql_,get_db_conn())){
		if(mysql_num_rows($res_)){
			$where = " and pridx='".$pridx."'";
		}else{
			$where = " and pridx='0'";
		}
	}

	$SQL = "SELECT * FROM vender_season_range WHERE vender='".$vender."'".$where." and `start` <= ".$date." AND `end` >= ".$date;

	$stamp =strtotime(substr($date,0,4).'-'.substr($date,4,2).'-01');
	$seasonStartDate = date('Y-m-d',$stamp);
	$seasonEndDate = date('Y-m-t',$stamp);
	//$sql = "select * from vender_season_range where vender='".$vender."'".$where." and ((start between '".$seasonStartDate."' and '".$seasonEndDate."' and  end between '".$seasonStartDate."' and '".$seasonEndDate."') ||  (start <= '".$seasonStartDate."' and end >= '".$seasonEndDate."'))";

	$sql = "select * from vender_season_range where vender='".$vender."'".$where." and ((start between '".$seasonStartDate."' and '".$seasonEndDate."' and  end between '".$seasonStartDate."' and '".$seasonEndDate."') ||  (start <= '".$seasonStartDate."' and end >= '".$seasonEndDate."'))";
	$return = array('year'=>date('Y',$stamp),'month'=>date('m',$stamp),'busy'=>array(),'semi'=>array());
	if(false !== $res = mysql_query($sql,get_db_conn())){
		if(mysql_num_rows($res)){
			while($item = mysql_fetch_assoc($res)){							
				$tarr = &$return[$item['type']];
				$st = (date('Y-m-d',$stamp) >= $item['start'])?1:intval(substr($item['start'],-2));
				$ed = (date('Y-m-t',$stamp) <= $item['end'])?intval(date('t',$stamp)):intval(substr($item['end'],-2));
				for($i=$st;$i<=$ed;$i++){
					array_push($tarr,sprintf('%02d',$i));
				}
			}
		}
	}	
	return $return;
}


// ��ⷻŻ ��å
function rentalLongDiscount (){
	$return = array();
	$rentLongDisSQL = "SELECT * FROM rent_long_discount ORDER BY days ASC";
	$rentLongDisRES = mysql_query($rentLongDisSQL,get_db_conn());
	while ( $rentLongDisROW = mysql_fetch_assoc($rentLongDisRES) ) {
		$return[$rentLongDisROW['days']] = $rentLongDisROW['rate'];
	}
	return $return;
}


// ��ǰ�� �ɼ� ī��Ʈ
function retnOptionUseCnt ( $pridx ) {
	$SQL = "SELECT * FROM `rent_product_option` WHERE  `pridx` =".$pridx."; ";
	$RES = mysql_query( $SQL,get_db_conn());
	$ROW = mysql_num_rows( $RES );
	return $ROW;
}



/**
 * ���� ����Ʈ ī�װ� ����
 */
// ī�װ� ����Ʈ
function wishCateList() {
	global $_ShopInfo;
	$return = array();
	$sql = "SELECT * FROM tblwishlist_category WHERE memid = '".$_ShopInfo->getMemid()."' ";
	$res = mysql_query($sql,get_db_conn());
	while ( $row = mysql_fetch_assoc($res) ) {
		$return[$row['idx']] = $row['title'];
	}
	return $return;
}




// ���� ������ ���� ����
function productReviewAverage ( $prcode, $mark = 0 ) {
	if( strlen($prcode) > 10 ) {
		$selSQL = "SELECT * FROM tblproductreviewAverage WHERE productcode = '".$prcode."'";
		$selRES = mysql_query($selSQL,get_db_conn());
		if( $mark == 0 ) {
			$selROW = mysql_fetch_assoc($selRES);
			$return = array();
			$return['productcode'] = $prcode;
			$return['average'] = intval($selROW['average']);
			$return['count'] = intval($selROW['count']);
			return $return;
		}else{
			$reviewSQL = "SELECT count(num) as cnt, avg(marks) as avg FROM tblproductreview WHERE productcode = '".$prcode."' GROUP BY productcode";
			$reviewRES = mysql_query($reviewSQL,get_db_conn());
			$reviewROW = mysql_fetch_assoc($reviewRES);

			$selCNT = mysql_num_rows($selRES);
			if ( $selCNT == 0 ) {
				mysql_query("INSERT tblproductreviewAverage SET average = ".$reviewROW['avg'].", count = ".$reviewROW['cnt'].", productcode = '".$prcode."' ",get_db_conn());
			}else {
				mysql_query("UPDATE tblproductreviewAverage SET average = ".$reviewROW['avg'].", count = ".$reviewROW['cnt']." WHERE productcode = '".$prcode."' ",get_db_conn());
			}
		}
	}
}


?>
