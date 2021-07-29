<?
include_once dirname(__FILE__).'/../class/rentproduct.php';
/**
* Created by PhpStorm. move jhj
* User: x2chi-objet
* Date: 2014-09-19
* Time: ���� 2:05
*/

/* �뿩��ǰ �Լ� */

// ��ǰ����
// ���� ������
// ������ ����

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


// ���� �׷� ����
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

// ī�װ��� ������
function categoryRentInfo($code){
	return rentProduct::getCodeInfo($code);
}

// ��ǰ �Ǵ� ī�װ� �� ���뿩����
function rentLongrentCharge($code,$diff=NULL){
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
				$sql = "select percent from rent_longrent where code='".$code."' and sday <= '".$diff."' and eday <= '".$diff."' order by sday desc limit 1";
				if(false !== $res = mysql_query($sql,get_db_conn())){
					if(mysql_num_rows($res)){
						return mysql_result($res,0,0);
					}
				}
			}
			return 0;
		}else{
			$sql = "select lr.sday,lr.eday,lr.percent from rent_longrent lr where lr.code='".$code."' order by lr.sday asc";	
			if(false !== $res = mysql_query($sql,get_db_conn())){
				if(mysql_num_rows($res)){
					while($row = mysql_fetch_assoc($res)){
						$return[$row['sday']] = $row;
					}
				}
			}
		}
	}
	return $return;
}

// ��ǰ �Ǵ� ī�װ� �� ȯ�� ������
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

// ��ǰ �Ǵ� ī�װ� �� ��� ����
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

// ���� �⺻ ������
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




// ��Ż ��ǰ ǥ�� (������)
function rentalIcon( $key ){
	$return = "";
	if( $key > 0 && strlen($key) > 0 ) {
		if( $key == 2 ) {
			//$return = '<span class="rentIcon">��Ż</span>';
		} else{
			$return = '<span class="sellIcon">�Ǹ�</span>';
		}
	}
	return $return;
}

// ��ǰ ��������
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


// ��ǰ ���� ���
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
					`multiOpt` = '".$value['multiOpt']."',
					`maincommi` = '".$value['maincommi']."',
					`trust_vender` = '".$value['trust_vender']."' ,
					`trust_approve` = '".$value['trust_approve']."'
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
			`multiOpt` = '".$value['multiOpt']."',
			`maincommi` = '".$value['maincommi']."',
			`trust_vender` = '".$value['trust_vender']."',
			`trust_approve` = '".$value['trust_approve']."'
		";
		if( mysql_query($SQL,get_db_conn()) ) {
			$return = "insert";
		} else {
			$return = "insert err";
		}
	}
	return $return;
}

//��������
//������� / ����
// ��Ʈ ��ǰ �ɼ� ����
function rentProductOptionInfo($idx ){
	
	$return = array();
	$SQL = "SELECT * FROM `rent_product_option` WHERE  `idx` =".$idx." LIMIT 1; ";
	if(false !== $RES = mysql_query( $SQL,get_db_conn())){
		if(mysql_num_rows($RES)){
			$return = mysql_fetch_assoc( $RES );
			$cinfo = categoryRentInfo($ROW['pridx']);
			if($cinfo['useseason'] != '1') $return['busySeason'] = $return['busyHolidaySeason'] = $return['semiBusySeason'] = $return['semiBusyHolidaySeason'] = $return['holidaySeason'] = 0;			
			$return['pricetype'] = $cinfo['pricetype'];			
		}
	}
	return $return;
}

// ��Ʈ ��ǰ �ɼ� ����
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
				if($cinfo['useseason'] != '1') $return['busySeason'] = $return['busyHolidaySeason'] = $return['semiBusySeason'] = $return['semiBusyHolidaySeason'] = $return['holidaySeason'] = 0;	
				$return[$row['idx']] = $row;
			}
		}
	}
	return $return;
}


// �ָ� ��� ���� (��� ��,�Ͽ���) �� ���� ����
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


// ���ڴ� �ΰ��� �ϳ��� �־�� ��. 
// $keytype : date = > ��� ���� key �� ���� , �׿ܴ� �ɼ� ���� 
function ProductRentSchedule($pridx,$sdate=NULL,$edate=NULL,$optidxs=array()){
	return rentProduct::schedule($pridx,$sdate,$edate,$optidxs);	
}


//��������Ʈ (�˻����� array)
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

/*
 * �뿩 �߰� �Լ� (syncOrderRent() �Լ��� ��������)
 * Date : 2015 12 18 (�߰�)
 * 
 */
function syncOrderCodeRent( $ordercode, $ordertype, $status ){
	$basket = basketTable($ordertype); // �ǹ̴� ����. ������ �־ ����.

	$sql = "SELECT 	op.productcode, op.ordercode ,r.*,pl.location from tblorderproduct as op INNER JOIN tblproduct as p ON p.productcode = op.productcode left join rent_basket_temp r on r.basketidx=op.basketidx and r.ordertype='".$ordertype."' left join 
			rent_product as pl on p.pridx=pl.pridx
			WHERE op.ordercode = '".$ordercode."' and p.rental='2'";

	$res = mysql_query($sql,get_db_conn());
	if(mysql_num_rows($res)){
		while($row = mysql_fetch_assoc($res) ) {
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
				if($row['ordertype']!="recommand"){
					mysql_query("DELETE FROM rent_basket_temp WHERE basketidx = '".$row['basketidx']."' and ordertype='".$row['ordertype']."' ",get_db_conn());
				}
			}
		}
	}
}

/*
 * �⺻���� �뿩 �߰� �Լ�
 */
function syncOrderRent( $tempkey, $ordertype, $status ){
	$basket = basketTable($ordertype);

	$sql = "SELECT 	op.productcode, op.ordercode ,r.*,pl.location from tblorderproduct as op INNER JOIN tblproduct as p ON p.productcode = op.productcode left join rent_basket_temp r on r.basketidx=op.basketidx and r.ordertype='".$ordertype."' left join 
			rent_product as pl on p.pridx=pl.pridx
			WHERE op.tempkey = '".$tempkey."' and p.rental='2'";

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
				if($row['ordertype']!="recommand"){
					mysql_query("DELETE FROM rent_basket_temp WHERE basketidx = '".$row['basketidx']."' and ordertype='".$row['ordertype']."' ",get_db_conn());
				}
			}
		}
	}
}


/********
gura
*********/
function venderRentInfo($vender,$pridx,$code){
	return rentProduct::getVenderRent($vender,$pridx,$code);
}

// ��ǰ �Ǵ� ī�װ� �� ���뿩����
function venderLongrentCharge($vender,$pridx,$diff=NULL){
	$sql = '';
	$sql_ = "select * from vender_longrent where vender='".$vender."' and pridx='".$pridx."'";
	if(false !== $res_ = mysql_query($sql_,get_db_conn())){
		if(mysql_num_rows($res_)){
			$vender_where = " and pridx='".$pridx."'";
		}else{
			$vender_where = " and pridx='0'";
		}
	}else{
		$vender_where = "and pridx='0'";
	}	

	/*
	$sql = "select sday,eday,percent from vender_longrent where vender='".$vender."' and pridx='".$pridx."' order by sday asc";	
	if(false !== $res = mysql_query($sql,get_db_conn())){
		if(mysql_num_rows($res)){
			while($row = mysql_fetch_assoc($res)){
				$return[$row['sday']] = $row;
			}
		}
	}
	*/
	
	$return = array();
	if(!_empty($diff)){
		if(_isInt($diff)){
			$sql = "select percent from vender_longrent where vender='".$vender."' ".$vender_where." and sday <= '".$diff."' and eday >= '".$diff."' order by sday desc limit 1";
			if(false !== $res = mysql_query($sql,get_db_conn())){
				if(mysql_num_rows($res)){
					return mysql_result($res,0,0);
				}
			}
		}
		return 0;
	}else{
		$sql = "select sday,eday,percent from vender_longrent where vender='".$vender."' ".$vender_where." order by sday asc";
		if(false !== $res = mysql_query($sql,get_db_conn())){
			if(mysql_num_rows($res)){
				while($row = mysql_fetch_assoc($res)){
					$return[$row['sday']] = $row;
				}
			}
		}
	}
	return $return;
}

// ��ǰ �Ǵ� ī�װ� �� ȯ�� ������
function venderRefundCommission($vender,$pridx){
	$sql = '';

	$return = array();
	$sql = "select r.day,r.percent from vender_refund r where r.vender='".$vender."' and r.pridx='".$pridx."' order by r.day asc";	
	if(false !== $res = mysql_query($sql,get_db_conn())){
		if(mysql_num_rows($res)){
			while($row = mysql_fetch_assoc($res)){
				$return[$row['day']] = $row['percent'];
			}
		}
	}
	return $return;
}

// ��ǰ �Ǵ� ī�װ� �� ��� ����
function venderLongDiscount($vender,$pridx,$diff=NULL){
	$sql = '';
	$sql_ = "select * from vender_longdiscount where vender='".$vender."' and pridx='".$pridx."'";
	if(false !== $res_ = mysql_query($sql_,get_db_conn())){
		if(mysql_num_rows($res_)){
			$vender_where = " and pridx='".$pridx."'";
		}else{
			$vender_where = " and pridx='0'";
		}
	}else{
		$vender_where = "and pridx='0'";
	}	

	$sql = "select day,percent from vender_longdiscount  where vender='".$vender."' ".$vender_where." order by .day asc";
	if(false !== $res = mysql_query($sql,get_db_conn())){
		if(mysql_num_rows($res)){
			while($row = mysql_fetch_assoc($res)){
				$return[$row['day']] = $row['percent'];
			}
		}
	}

	$return = array();
	//if(preg_match('/^[0-9]{12}$/',$code)){
		if(!_empty($diff)){
			if(_isInt($diff)){
				$sql = "select percent from vender_longdiscount where vender='".$vender."' ".$vender_where." and day <= '".$diff."' order by day desc limit 1";
				if(false !== $res = mysql_query($sql,get_db_conn())){
					if(mysql_num_rows($res)){
						return mysql_result($res,0,0);
					}
				}
			}
			return 0;
		}else{
			$sql = "select day,percent from vender_longdiscount where vender='".$vender."' ".$vender_where." order by day asc";
			if(false !== $res = mysql_query($sql,get_db_conn())){
				if(mysql_num_rows($res)){
					while($row = mysql_fetch_assoc($res)){
						$return[$row['day']] = $row['percent'];
					}
				}
			}
		}
//	}
	return $return;
}
?>