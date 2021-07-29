<?
include_once dirname(__FILE__).'/../class/rentproduct.php';
/**
* Created by PhpStorm. move jhj
* User: x2chi-objet
* Date: 2014-09-19
* Time: 오후 2:05
*/

/* 대여상품 함수 */

// 상품관리
// 본사 관리자
// 수수료 관리

function parseRentRequestOption($option){
	$reqoption = array();
	if(!_empty($option)){
		$parseRent = explode("|",$option);
		
		foreach($parseRent as $optval){
			if(!_empty($optval)){
				$tmpopt = explode(":",$optval);
				if(_isInt($tmpopt[0]) && _isInt($tmpopt[1])){
					if(!isset($tmpopt[$tmpopt[0]])) $reqoption[$tmpopt[0]] = 0;
					$reqoption[$tmpopt[0]] += $tmpopt[1];
				}
			}
		}
	}
	return $reqoption;
}

function rentCommitionByCategory($code,$vender=0){
	$return = array('self'=>0,'main'=>0);	
	
	$tmp = categoryRentInfo($code);	
	if(!_array($tmp)) $tmp = rendDefaultCommi();
	$return['self'] = floatval(pick($tmp['commission_self'],$tmp['self']));
	$return['main'] = floatval(pick($tmp['commission_main'],$tmp['main']));

	if(_isInt($vender)){
		$vg = venderGroupInfo($vender);
		$return['self'] -=  floatval($vg['vgcommi_self']);
		$return['main'] -= floatval($vg['vgcommi_main']);
		
	}
	$return['self'] = max($return['self'],0);
	$return['main'] = max($return['main'],0);
	return $return;
}


// 벤더 그룹 정보
function venderGroupInfo($vender){
	$return = array();
	if(_isInt($vender)){
		$sql = "select vg.* from vender_group_link v inner join vender_group vg on vg.vgidx = v.vgidx where v.vender='".$vender."' limit 1";
		if(false !== $res = mysql_query($sql,get_db_conn())){
			if(mysql_num_rows($res)) $return = mysql_fetch_assoc($res);
		}		
	}
	return $return;
}

// 카테고리별 수수료
function categoryRentInfo($code){
	return rentProduct::getCodeInfo($code);
}

// 상품 또는 카테고리 별 환불 수수료
function rentRefundCommission($code){
	$sql = '';
	if(preg_match('/^[0-9]{18}$/',$code)){
		$sql = "select substr(productcode,1,12) from tblproduct where productcode='".$code."' and rental='2' limit 1";
	}else if(_isInt($code) && !preg_match('/^[0-9]{12}$/',$code)){
		$sql = "select substr(productcode,1,12) from tblproduct where pridx='".$code."' and rental='2' limit 1";
	}
	
	if(!_empty($sql)){
		$code = '';
		if(false !== $res = mysql_query($sql,get_db_conn())){
			if(mysql_num_rows($res)) $code = mysql_result($res,0,0);
		}
	}
	
	$return = array();
	if(preg_match('/^[0-9]{12}$/',$code)){
		$sql = "select r.day,r.percent from rent_refund r where r.code='".$code."' order by r.day asc";	
		if(false !== $res = mysql_query($sql,get_db_conn())){
			if(mysql_num_rows($res)){
				while($row = mysql_fetch_assoc($res)){
					$return[$row['day']] = $row['percent'];
				}
			}
		}
	}
	return $return;
}

// 상품 또는 카테고리 별 장기 할인
function rentLongDiscount($code,$diff=NULL){
	$sql = '';
	if(preg_match('/^[0-9]{18}$/',$code)){
		$sql = "select substr(productcode,1,12) from tblproduct where productcode='".$code."' and rental='2' limit 1";
	}else if(_isInt($code) && !preg_match('/^[0-9]{12}$/',$code)){
		$sql = "select substr(productcode,1,12) from tblproduct where pridx='".$code."' and rental='2' limit 1";
	}
	
	if(!_empty($sql)){
		$code = '';
		if(false !== $res = mysql_query($sql,get_db_conn())){
			if(mysql_num_rows($res)) $code = mysql_result($res,0,0);
		}
	}
	
	$return = array();
	if(preg_match('/^[0-9]{12}$/',$code)){
		if(!_empty($diff)){
			if(_isInt($diff)){
				$sql = "select r.percent from rent_longdiscount r where r.code='".$code."' and r.day <= '".$diff."' order by r.day desc limit 1";
				if(false !== $res = mysql_query($sql,get_db_conn())){
					if(mysql_num_rows($res)){
						return mysql_result($res,0,0);
					}
				}
			}
			return 0;
		}else{
			$sql = "select r.day,r.percent from rent_longdiscount r where r.code='".$code."' order by r.day asc";
			if(false !== $res = mysql_query($sql,get_db_conn())){
				if(mysql_num_rows($res)){
					while($row = mysql_fetch_assoc($res)){
						$return[$row['day']] = $row['percent'];
					}
				}
			}
		}
	}
	return $return;
}

// 상점 기본 수수료
function rendDefaultCommi(){
	$sql = "select commi_self as self,commi_main as main from shop_more_info limit 1";
	$return = array('self'=>0,'main'=>0);
	if(false !== $res = mysql_query($sql,get_db_conn())){
		if(mysql_num_rows($res)){
			$return = mysql_fetch_assoc($res);
		}
	}
	return $return;
}




// 렌탈 상품 표시 (아이콘)
function rentalIcon( $key ){
	$return = "";
	if( $key > 0 && strlen($key) > 0 ) {
		if( $key == 2 ) {
			$return = '<span class="rentIcon">렌탈</span>';
		} else{
			$return = '<span class="sellIcon">판매</span>';
		}
	}
	return $return;
}

// 상품 연동정보
function rentProduct( $pridx ) {
	$SQL = "SELECT R.*, P.* FROM `rent_product` AS R LEFT OUTER JOIN tblproduct as P ON R.`pridx` = P.`pridx` WHERE R.`pridx` = ".$pridx;
	$RESULT = mysql_query($SQL,get_db_conn());
	return mysql_fetch_assoc($RESULT);
}

function isRentProduct($pridx) {
	$SQL = "SELECT R.pridx FROM tblproduct as P inner join `rent_product` R ON R.`pridx` = P.`pridx` WHERE ";
	if(preg_match('/^[0-9]{18}$/',$pridx)) $SQL .= " P.`productcode` = ".$pridx;
	else if(_isInt($pridx)) $SQL .= " P.`pridx` = ".$pridx;
	else return false;
	$SQL .= " and P.rental='2'";	
	if(false !== $RESULT = mysql_query($SQL,get_db_conn())) return mysql_num_rows($RESULT)>0;
	return false;
}


// 상품 연동 등록
function rentProductSave( $value = array() ) {
	$chk = mysql_num_rows(mysql_query("SELECT * FROM `rent_product` WHERE `pridx` = ".$value['pridx'],get_db_conn()));
	$return = "err";
	if( $chk  > 0 ) {
		if($value['goodsType']==1) {
			mysql_query("DELETE FROM `rent_product` WHERE `pridx` = ".$value['pridx'],get_db_conn());
			$return = "delete";
		} else {
			$SQL = "
				UPDATE `rent_product` SET
					`istrust` = '".$value['istrust']."',
					`trustCommi` = ".(!_empty($value['trustCommi'])?"'".$value['trustCommi']."'":'NULL').",
					`location` = '".$value['location']."',
					`itemType` = '".$value['itemType']."',
					`multiOpt` = '".$value['multiOpt']."'				
				WHERE
					`pridx` = '".$value['pridx']."'
				 LIMIT 1
			";
			if( mysql_query($SQL,get_db_conn()) ) {
				$return = "update";
			} else {
				$return = "update err";
			}
		}
	} else {
		$SQL = "
		INSERT `rent_product` SET
			`pridx` = '".$value['pridx']."',
			`istrust` = '".$value['istrust']."',
			`trustCommi` = ".(!_empty($value['trustCommi'])?"'".$value['trustCommi']."'":'NULL').",
			`location` = '".$value['location']."',
			`itemType` = '".$value['itemType']."',
			`multiOpt` = '".$value['multiOpt']."'
		";
		if( mysql_query($SQL,get_db_conn()) ) {
			$return = "insert";
		} else {
			$return = "insert err";
		}
	}
	return $return;
}

//지역정보
//지역등록 / 수정
// 렌트 상품 옵션 정보
function rentProductOptionInfo($idx ){
	
	$return = array();
	$SQL = "SELECT * FROM `rent_product_option` WHERE  `idx` =".$idx." LIMIT 1; ";
	if(false !== $RES = mysql_query( $SQL,get_db_conn())){
		if(mysql_num_rows($RES)){
			$return = mysql_fetch_assoc( $RES );
			$cinfo = categoryRentInfo($ROW['pridx']);
			if($cinfo['useseason'] != '1') $return['busySeason'] = $return['semiBusySeason'] = $return['holidaySeason'] = 0;			
			$return['pricetype'] = $cinfo['pricetype'];			
		}
	}
	return $return;
}

// 렌트 상품 옵션 정보
function rentProductOptionInfoByPridx($pridx,&$cinfo=NULL){	
	$cinfo = categoryRentInfo($pridx);
	$SQL = "SELECT r.* FROM `rent_product_option` r inner join tblproduct p on p.pridx=r.pridx WHERE ";		
	if(preg_match('/[0-9]{18}/',$pridx)) $where .= "p.productcode='".$pridx."'";
	else $where .= "p.pridx='".$pridx."'";
	$SQL .=$where. " order by r.idx asc";
		
	$return = array();
	if(false !== $RES = mysql_query( $SQL,get_db_conn())){	
		if(mysql_num_rows($RES)){
			while($row = mysql_fetch_assoc($RES)){
				if($cinfo['useseason'] != '1') $row['busySeason'] = $row['semiBusySeason'] = $row['holidaySeason'] = 0;
				$return[$row['idx']] = $row;
			}
		}
	}
	return $return;
}


// 주말 요금 적용 (모든 토,일요일) 및 정보 리턴
function seasonSet ($day = "", $chk = "") {
	$return = array();
	if ( strlen($day) > 0 AND strlen($chk) > 0 ) {
		$changeDayPriceSQL = "UPDATE rent_seasonSet SET value = '".($chk=="true"?"1":"0")."' WHERE name = '".$day."'";
		mysql_query( $changeDayPriceSQL ,get_db_conn());
		$return['update']="OK";
	}
	$seasonSetRes = mysql_query( "SELECT * FROM rent_seasonSet" ,get_db_conn());
	while ( $row = mysql_fetch_assoc( $seasonSetRes ) ) {
		$return[$row['name']]=$row['value'];
	}
	return $return;
}


function rentSeasonRange($code,$sDate,$eDate){
	exit('err rentSeasonRange');
}


// 일자는 두개중 하나는 넣어야 함. 
// $keytype : date = > 결과 기준 key 과 일자 , 그외는 옵션 기중 
function ProductRentSchedule($pridx,$sdate=NULL,$edate=NULL,$optidxs=array()){
	return rentProduct::schedule($pridx,$sdate,$edate,$optidxs);	
}


//지역리스트 (검색조건 array)
function rentLocalList($value){
	$SQL = "SELECT * FROM `rent_location` ";
	if( _array($value) ) {
		$SQL_WHERE = array();
		foreach ($value as $k => $v) {
			if (strlen ($v) > 0) {
				array_push ($SQL_WHERE, "`" . $k . "` = '" . $v . "' ");
			}
		}
		if (_array($SQL_WHERE)) {
			$SQL .= " WHERE " . implode (" AND ", $SQL_WHERE);
		}
	}
	$SQL .= " ORDER BY `location` ASC";
	$RESULT = mysql_query($SQL,get_db_conn());
	$return = array();
	while ( $ROW =  mysql_fetch_assoc($RESULT) ) {
		$return[$ROW['location']] = $ROW;
	}
	return $return;
}


function syncOrderRent( $tempkey, $ordertype, $status ){
	$basket = basketTable($ordertype);
	/*
	$prdSQL = "
		SELECT
			op.productcode, op.ordercode
			, p.pridx
			, ( SELECT rp.location FROM rent_product as rp WHERE rp.pridx = p.pridx ) as location
			, ( SELECT b.basketidx FROM `".$basket."` as b WHERE b.tempkey = op.tempkey AND b.productcode = op.productcode ) as basketidx
		FROM
			tblorderproduct as op
			INNER JOIN tblproduct as p ON p.productcode = op.productcode
		WHERE
			op.tempkey = '".$tempkey."' and p.rental='2'";
	
	$prdRES = mysql_query($prdSQL,get_db_conn());
	while ( $prdROW = mysql_fetch_assoc($prdRES) ){
		$rentSQL = "SELECT rbt.* FROM rent_basket_temp as rbt WHERE rbt.basketidx = '".$prdROW['basketidx']."' ";	
		$rentRES = mysql_query($rentSQL,get_db_conn());
		if ( mysql_num_rows($rentRES) ) {
			$rentROW = mysql_fetch_assoc($rentRES);
			$sql = "
			INSERT rent_schedule SET
				`optidx` = '".$rentROW['optidx']."',
				`quantity` = '".$rentROW['quantity']."',
				`ordercode` = '".$prdROW['ordercode']."',
				`location` = '".$prdROW['location']."',
				`start` = '".$rentROW['start']."',
				`end` = '".$rentROW['end']."',
				`status` = '".$status."',
				`regDate` = NOW()
			";
			if( mysql_query($sql,get_db_conn()) ) {
				mysql_query("DELETE FROM rent_basket_temp WHERE basketidx = '".$prdROW['basketidx']."' ",get_db_conn());
			}
		}
	}*/
	$sql = "SELECT 	op.productcode, op.ordercode ,r.*,pl.location from tblorderproduct as op INNER JOIN tblproduct as p ON p.productcode = op.productcode left join rent_basket_temp r on r.basketidx=op.basketidx and r.ordertype='".$ordertype."' left join 
			rent_product as pl on p.pridx=pl.pridx
			WHERE op.tempkey = '".$tempkey."' and p.rental='2'";
//	echo $sql;
	$res = mysql_query($sql,get_db_conn());
	if(mysql_num_rows($res)){
		while($row = mysql_fetch_assoc($res) ){			
			$sql = "
			INSERT rent_schedule SET
				`optidx` = '".$row['optidx']."',
				`quantity` = '".$row['quantity']."',
				`ordercode` = '".$row['ordercode']."',
				`basketidx` = '".$row['basketidx']."',
				`location` = '".$row['location']."',
				`start` = '".$row['start']."',
				`end` = '".$row['end']."',
				`status` = '".$status."',
				`regDate` = NOW()
			";
			if(mysql_query($sql,get_db_conn()) ) {
				mysql_query("DELETE FROM rent_basket_temp WHERE basketidx = '".$row['basketidx']."' and ordertype='".$row['ordertype']."' ",get_db_conn());
			}
		}
	}
}

?>