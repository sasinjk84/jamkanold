<?
@set_time_limit(0);
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/ext/func.php");


// �ڵ� ��� ���� ���� ����
$asql = "select * from extra_conf where type='autogroup'";
if(false !== $ares = mysql_query($asql,get_db_conn())){
	$auto = array();
	while($trow = mysql_fetch_assoc($ares)){
		$auto[$trow['name']] = $trow['value'];
	}
}
if(!_array($auto)) exit;

$endDate = strtotime(date('Y-m-'.$auto['rangestart']));  // �ջ� ���� ����

if($auto['rangestart'] >= date('d')) $endDate = strtotime('-1 month',$endDate); // �ջ�������
$startDate = strtotime('-'.$auto['rangemonth'].' month',$endDate); // �ջ������

//echo "�ջ������:".date("Y-m-d",$startDate)."<br>";
//echo "�ջ�������:".date("Y-m-d",$endDate)."<br>";

$where = array();
array_push($where,"o.ordercode>='".date('Ymd',$startDate)."000000'"); // �ջ������
array_push($where,"o.ordercode<='".date('Ymd',$endDate)."235959'"); // �ջ�������
array_push($where,"deli_gbn = 'Y'"); // �ջ����� - ��ۿϷ�


// �׷� ����Ʈ ===========================================
$groupList = array();
$excpgroupList = array();
$excpgroupCodes = array();
$groupOrderType_SQL = "SELECT `value` FROM `extra_conf` WHERE `type` = 'groupconf' AND `name` = 'group_order_type' LIMIT 1 ; ";
$groupOrderType_RES =  mysql_query($groupOrderType_SQL,get_db_conn());
$groupOrderType_ROW = mysql_fetch_assoc($groupOrderType_RES);
$groupOrderType = $groupOrderType_ROW['value'];

switch( $groupOrderType ){
	case '1' : // �ݾ׸� ����
		$groupList_SQL_WHERE = "WHERE group_order_price > 0 or group_excp_auto='Y'";
		$groupList_SQL_ORDERBY = "ORDER BY group_order_price DESC";
		//echo "���űݾ� ����";
		break;
	case '2' : // �Ǽ��� ����
		$groupList_SQL_WHERE = "WHERE group_order_cnt > 0  or group_excp_auto='Y'";
		$groupList_SQL_ORDERBY = "ORDER BY group_order_cnt DESC";
		//echo "���ŰǼ� ����";
		break;
	case '3' : // �ݾ� && �Ǽ� ����
		$groupList_SQL_WHERE = "WHERE (group_order_price > 0 AND group_order_cnt > 0)  or group_excp_auto='Y' ";
		$groupList_SQL_ORDERBY = "ORDER BY group_order_price DESC, group_order_cnt DESC";
		//echo "�ݾ� AND �Ǽ� ���� (�ݾ� �켱)";
		break;
	case '4' : // �ݾ� || �Ǽ� ����
		$groupList_SQL_WHERE = " where group_order_price > 0 or group_order_cnt > 0  or group_excp_auto='Y'";
		$groupList_SQL_ORDERBY = "ORDER BY group_order_price DESC, group_order_cnt DESC";
		//echo "�ݾ� OR �Ǽ� ���� (�ݾ� �켱)";
		break;
	default :
		echo "[ERROR] ���� �ݾ�/���ŰǼ� ���� ���� ����";
		exit;
}
//echo "<br />";

$groupList_SQL = "SELECT group_code, group_order_price, group_order_cnt, group_order_type, use_auto_coupon, groupCouponSendType, group_iossreserve, groupCouponSendType_M, groupCouponSendType_D,group_excp_auto FROM tblmembergroup ".$groupList_SQL_WHERE." ".$groupList_SQL_ORDERBY." ";
$groupList_RESULT = mysql_query($groupList_SQL,get_db_conn());

while ( $groupList_ROW = mysql_fetch_assoc($groupList_RESULT) ){
	if($groupList_ROW['group_excp_auto'] == 'Y'){
		array_push($excpgroupList,$groupList_ROW);
		array_push($excpgroupCodes,$groupList_ROW['group_code']);
	}else{
		array_push($groupList,$groupList_ROW);
	}
}


// �ڵ� ��� ���� ====================================================
$runAutoGroupSchedule = true;

// ���� ����� ����
$updateStamp = strtotime(date('Y-m-').$auto['upday']);

if(date('d') < $auto['upday']) $updateStamp = strtotime('-1 month'.$updateStamp); // �±� ó�� �� �� ����	

// ���� ���� �Ǻ�
if($auto['runtype'] == 'user'){	
	$runAutoGroupSchedule = true;
	if(!_empty($auto['lastrun']) && preg_match('/^(2[0-9]{3})-([0-9]{2})-([0-9]{2})$/',$auto['lastrun'],$mat)){
		if(checkdate($mat[2],$mat[3],$mat[1]) && $auto['lastrun'] <= date('Y-m-d') && strtotime($auto['lastrun']) >= $endDate){
			$runAutoGroupSchedule = false;
		}
	}
}else if(date('d') != $auto['upday']){
	$runAutoGroupSchedule = false;
}


if($runAutoGroupSchedule === true){
	// �ش� �׷� ������ ȸ�� ����Ʈ �߰�
	foreach($groupList as $gKey => $group){
		// �׷��� �ֻ��� ���� �׷� �ϰ�� true
		$endChk = ( empty($groupList[$gKey-1]) )?true:false;

		// ��� �˻� ����
		$having = "";
		switch( $groupOrderType ){
			case '1' : // �ݾ׸� ����
				$having .= " having sum(o.price) >= ".($group['group_order_price']).( $endChk ? "" : " AND sum(o.price) < ".($groupList[$gKey-1]['group_order_price']) );
				break;
			case '2' : // �Ǽ��� ����
				$having .= " having count(o.ordercode) >= ".($group['group_order_cnt']).($endChk ? "" : " AND count(o.ordercode) < ".($groupList[$gKey-1]['group_order_cnt']) );
				break;
			case '3' : // �ݾ� && �Ǽ� ����
				$having .= " having ( sum(o.price) >= ".($group['group_order_price']).($endChk ? "" : " AND sum(o.price) < ".($groupList[$gKey-1]['group_order_price']))." ) AND ( count(o.ordercode) >= ".($group['group_order_cnt']).($endChk ? "" : " AND count(o.ordercode) < ".($groupList[$gKey-1]['group_order_cnt']))." ) ";
				break;
			case '4' : // �ݾ� || �Ǽ� ����
				$having .= " having ( sum(o.price) >= ".($group['group_order_price']).($endChk ? "" : " AND sum(o.price) < ".($groupList[$gKey-1]['group_order_price']))." ) OR ( count(o.ordercode) >= ".($group['group_order_cnt']).($endChk ? "" : " AND count(o.ordercode) < ".($groupList[$gKey-1]['group_order_cnt']))." ) ";
				break;
		}


	//	$memSql = "SELECT m.id, m.group_code as memGroup, '".$group['group_code']."' as setGroup, ( SELECT l.changedate FROM autogroup_log as l WHERE l.currentclass = m.group_code AND l.id = m.id ORDER BY l.changedate DESC LIMIT 1 ) as updates FROM tblmember as m, ( SELECT o.id FROM tblorderinfo o WHERE ".implode(' and ',$where)." GROUP BY o.id ".$having." ) as s WHERE m.group_code not in ('".implode("','",array_merge(array($group['group_code'])))."') AND s.id=m.id AND m.member_out != 'Y'";
		$memSql = "SELECT m.id, m.group_code as memGroup, '".$group['group_code']."' as setGroup, ( SELECT l.changedate FROM autogroup_log as l WHERE l.currentclass = m.group_code AND l.id = m.id and l.classstate !='C' ORDER BY l.changedate DESC LIMIT 1 ) as updates FROM tblmember as m, ( SELECT o.id FROM tblorderinfo o WHERE ".implode(' and ',$where)." GROUP BY o.id ".$having." ) as s WHERE s.id=m.id AND m.member_out != 'Y' ";
		
		if(_array($excpgroupCodes)) $memSql .=  " and m.group_code not in ('".implode("','",$excpgroupCodes)."')";		
		$memRes = mysql_query($memSql,get_db_conn());
		if(mysql_num_rows($memRes) < 1) continue;
		
		while($mem = mysql_fetch_assoc($memRes)){			
			// ������ �����Ⱓ
			$saveDate = 0;
			if(!_empty($mem['updates'])){
				$saveDate = strtotime('+'.$auto['keepclass'].' month',strtotime($mem['updates']));
			}
			
			// ���� �׷� ���� �ڵ� ( -1 : error, 0���� ~ )
			$memGroupCodeKey=-1;

			foreach($groupList as $gKeyTemp => $groupTemp){				
				if(array_search($mem['memGroup'],$groupList[$gKeyTemp]) ) $memGroupCodeKey = $gKeyTemp;
			}			
			$chg = false;
			/*
			// ��� ��
			if( $memGroupCodeKey > $gKey) { // ���������θ� ��޾�
				$chg = true;
				$classstate = "U";
			}

			// ��� ����
			if( $saveDate < strtotime(date("Ymd")) AND $memGroupCodeKey == $gKey ){
				// ȸ����� ����
				$memberUpSQL = "UPDATE tblmember SET changedate = now() WHERE currentclass = '".$group['group_code']."' AND id = '".$mem['id']."' ";
				mysql_query($memberUpSQL,get_db_conn());
			}

			// ��� �ٿ�
			if( $saveDate < strtotime(date("Ymd")) AND $memGroupCodeKey < $gKey ){ // ��������Ⱓ - ��� �ٿ� ����
				$chg = true;
				$classstate = "D";
			}
			*/
			if($memGroupCodeKey == -1){
				$chg = true;
				$classstate = "F";
			}else if( $memGroupCodeKey > $gKey) { // ���������θ� ��޾�
				$chg = true;
				$classstate = "U";
			}else if($memGroupCodeKey == $gKey){ // ��� ���� ���� ���
				echo 'same';
				/*
				$logSQL = "INSERT INTO autogroup_log SET  beforeclass = '".$mem['memGroup']."', currentclass = '".$group['group_code']."', id = '".$mem['id']."', classstate = 'S', changedate = '".date('Y-m-d H:i:s',$updateStamp)."' ";				
				//$memberUpSQL = "UPDATE autogroup_log SET changedate = '".date('Y-m-d H:i:s',$updateStamp)."' WHERE currentclass = '".$group['group_code']."' AND id = '".$mem['id']."' ";
				mysql_query($memberUpSQL,get_db_conn());*/
			}else if($saveDate >= $updateStamp){ // ��� �����Ⱓ ����
				echo 'alive';
			}else{ // ���� ���	$memGroupCodeKey < $gKey			
				$chg = true;
				$classstate = "D";
			}			
			
			if($chg){
				// �ش� ��� ������/���� ���� - �α� ���� ����
				$memLogSQL = "SELECT count(*) FROM autogroup_log WHERE currentclass = '".$group['group_code']."' AND id = '".$mem['id']."' ";
				$logCnt = '0';
				if(false !== $logRes = mysql_query($memLogSQL,get_db_conn())){
					$logCnt = mysql_result($logRes,0,0);
				}

				// ��� �α�
				//$logSQL = "INSERT INTO autogroup_log SET  beforeclass = '".$mem['memGroup']."', currentclass = '".$group['group_code']."', id = '".$mem['id']."', classstate = '".$classstate."', changedate = now() ";
				$logSQL = "INSERT INTO autogroup_log SET  beforeclass = '".$mem['memGroup']."', currentclass = '".$group['group_code']."', id = '".$mem['id']."', classstate = '".$classstate."', changedate = '".date('Y-m-d H:i:s',$updateStamp)."' ";
				
				mysql_query($logSQL,get_db_conn());

				// ȸ����� ����
				$memberUpSQL = "UPDATE tblmember SET group_code = '".$group['group_code']."' WHERE id = '".$mem['id']."' ";	
				
				$memberUpOK = mysql_query($memberUpSQL,get_db_conn());

				//echo "<br />[".$mem['group_code']."=>".$group['group_code']."] ��� ".($classstate=="U"?"��":"�ٿ�")." ���� ȸ�� : ".$mem['id'];


				// ������ / ���� �߱�
				if( false !== $memberUpOK AND $logCnt == 0 ){ // on gift

					// ����������
					if(intval($group['group_iossreserve']) > 0 ){
						$reserveSQL = "UPDATE tblmember SET reserve = reserve+".intval($group['group_iossreserve'])." WHERE id ='".$mem['id']."' ";
						$reserveOK = mysql_query($reserveSQL,get_db_conn());
						if($reserveOK){
							// ������ ���� �����丮
						//	$reserveLogSQL = "INSERT INTO tblreserve SET id = '".$mem['id']."', reserve = '".$group['group_iossreserve']."', reserve_yn = 'Y', content = '�±�(".$group['group_code'].")�� ���� ����', date = '".date('YmdHis')."' ";
							$reserveLogSQL = "INSERT INTO tblreserve SET id = '".$mem['id']."', reserve = '".intval($group['group_iossreserve'])."', reserve_yn = 'Y', content = '�±�(".$group['group_code'].")�� ���� ����', date = '".date('YmdHis',$updateStamp)."' ";
							mysql_query($reserveLogSQL,get_db_conn());
							//echo " (������ ����)";
						}
					}

					// �������� - ���� 1ȸ ������ ��쿡��
					if( $group['groupCouponSendType'] == 1 AND $group['use_auto_coupon'] == 1 ){

						$couponSQL = "SELECT * FROM group_coupon AS GC inner join tblcouponinfo AS CI ON GC.coupon_code = CI.coupon_code WHERE GC.group_code = '".$group['group_code']."' ";
						$couponResult = mysql_query($couponSQL,get_db_conn());
						while($couponRow = mysql_fetch_assoc($couponResult)){
							/*
							$date = date("YmdHis");
							if( $couponRow['date_start'] > 0 ) {
								$date_start = $couponRow['date_start'];
								$date_end = $couponRow['date_end'];
							} else {
								$date_start = substr($date,0,10);
								$date_end = date("Ymd",mktime(0,0,0,substr($date,4,2),substr($date,6,2)+abs($couponRow['date_start']),substr($date,0,4)))."23";
							}*/
							if( $couponRow['date_start'] > 0 ) {
								$date_start = $couponRow['date_start'];
								$date_end = $couponRow['date_end'];
							} else {
								$date_start = substr($date,0,10);
								$date_end = date("Ymd23",strtotime('+'.abs($couponRow['date_start']).' day',$updateStamp));
							}
							

							// �� �߱� ����
							$isCouponSQL = "SELECT * FROM `tblcouponissue` WHERE `coupon_code` = '".$couponRow['coupon_code']."' AND `id` = '".$mem['id']."' ;";
							$isCouponResult = mysql_query($isCouponSQL,get_db_conn());
							$isCouponRow = mysql_fetch_assoc ( $isCouponResult );

							if( mysql_num_rows( $isCouponResult ) ){
								$saveCouponSQL = "UPDATE `tblcouponissue` SET `used` = 'N', `date_start` = '".$date_start."', `date_end` = '".$date_end."' WHERE `coupon_code` = '".$couponRow['coupon_code']."' AND `id` = '".$mem['id']."' ; ";
							} else{
							//	$saveCouponSQL = "INSERT `tblcouponissue` SET `used` = 'N', `date_start` = '".$date_start."', `date_end` = '".$date_end."', `date` = '".$date."', `coupon_code` = '".$couponRow['coupon_code']."', `id` = '".$mem['id']."' ;";
								$saveCouponSQL = "INSERT `tblcouponissue` SET `used` = 'N', `date_start` = '".$date_start."', `date_end` = '".$date_end."', `date` = '".date("YmdHis",$updateStamp)."', `coupon_code` = '".$couponRow['coupon_code']."', `id` = '".$mem['id']."' ;";
							
							}
							mysql_query($saveCouponSQL,get_db_conn());

							// ȸ�� �������� ���
							//$logSQL = "INSERT INTO autogroup_log SET  beforeclass = '', currentclass = '".$group['group_code']."', id = '".$mem['id']."', classstate = 'C', changedate = now() ";
							$logSQL = "INSERT INTO autogroup_log SET  beforeclass = '', currentclass = '".$group['group_code']."', id = '".$mem['id']."', classstate = 'C', changedate = '".date('Y-m-d H:i:s',$updateStamp)."'  ";
							mysql_query($logSQL,get_db_conn());

						} // end while
					} // end if
				} // on Change
				
			} 

		} // end mem while loop		
		//_pr($groupList);
	} // end group foreach loop
	

	// �ڵ� ���� �߱� ====================================================
	// �ش� �׷� ������ ȸ�� ����Ʈ
	// �ش� �׷� ������ ȸ�� ����Ʈ �߰�
	//_pr($groupList);

	foreach($groupList as $gKey => $group){
		if($group['groupCouponSendType'] == 2 AND $group['use_auto_coupon'] == 1){			
			$memSql = "SELECT m.id, m.group_code, ( SELECT l.changedate FROM autogroup_log as l WHERE l.currentclass = m.group_code AND l.id = m.id and l.classstate='C' ORDER BY l.changedate DESC LIMIT 1 ) as updates FROM tblmember as m WHERE m.group_code = '".$group['group_code']."' AND m.member_out != 'Y'";
			$memRes = mysql_query($memSql,get_db_conn());
			while($mem = mysql_fetch_assoc($memRes)){				
				if(_empty($mem['updates']) || substr($mem['updates'],0,10) < date('Y-m-d',$updateStamp)){
					// ȸ�� �������� ���
					$logSQL = "INSERT INTO autogroup_log SET  beforeclass = '', currentclass = '".$group['group_code']."', id = '".$mem['id']."', classstate = 'C', changedate = '".date('Y-m-d H:i:s',$updateStamp)."'";
					mysql_query($logSQL,get_db_conn());

					//���� ����
					$couponSQL = "SELECT * FROM group_coupon AS GC inner join tblcouponinfo AS CI ON GC.coupon_code = CI.coupon_code WHERE GC.group_code = '".$group['group_code']."' ";
					$couponResult = mysql_query($couponSQL,get_db_conn());
					while($couponRow = mysql_fetch_assoc($couponResult)){
						if($couponRow['date_start'] > 0){
							$date_start = $couponRow['date_start'];
							$date_end = $couponRow['date_end'];
						} else {
							$date_start = substr($date,0,10);
						//	$date_end = date("Ymd",mktime(0,0,0,substr($date,4,2),substr($date,6,2)+abs($couponRow['date_start']),substr($date,0,4)))."23";
							$date_end = date("Ymd23",strtotime('+'.abs($couponRow['date_start']).' day',$updateStamp));
						}

						// �� �߱� ����
						$isCouponSQL = "SELECT * FROM `tblcouponissue` WHERE `coupon_code` = '".$couponRow['coupon_code']."' AND `id` = '".$mem['id']."' ;";
						$isCouponResult = mysql_query($isCouponSQL,get_db_conn());
						$isCouponRow = mysql_fetch_assoc ( $isCouponResult );

						if( mysql_num_rows( $isCouponResult ) ){
							$saveCouponSQL = "UPDATE `tblcouponissue` SET `used` = 'N', `date_start` = '".$date_start."', `date_end` = '".$date_end."' WHERE `coupon_code` = '".$couponRow['coupon_code']."' AND `id` = '".$mem['id']."' ; ";
						} else{
							$saveCouponSQL = "INSERT `tblcouponissue` SET `used` = 'N', `date_start` = '".$date_start."', `date_end` = '".$date_end."', `date` = '".date("YmdHis",$updateStamp)."', `coupon_code` = '".$couponRow['coupon_code']."', `id` = '".$mem['id']."' ;";
						}
						mysql_query($saveCouponSQL,get_db_conn());
					}
				}
			}
		}
	}

	if(mysql_result(mysql_query("select count(*) from extra_conf where type='autogroup' and name='lastrun'",get_db_conn()),0,0) > 0){
		@mysql_query("update extra_conf set value='".date('Y-m-d')."' where type='autogroup' and name='lastrun'",get_db_conn());
	}else{
		@mysql_query("insert into extra_conf set type='autogroup',name='lastrun',value='".date('Y-m-d')."'",get_db_conn());
	}
} // end ������ �ջ�ݿ���

//echo "�۾��Ϸ�!";
?>