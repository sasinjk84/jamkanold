<?

//  ���ް� ������ ����
function addBasket($params=array()){
	global $_ShopInfo,$_data,$Dir;
	$ordertype = $params['ordertype'];
	$baskettbl = basketTable($ordertype);
	
	if($ordertype == 'prebasket'){
		$params['p_bookingStartDate'] = $params['p_bookingStartDate']? $params['p_bookingStartDate'] : $params['pre_bookingStartDate'];
		$params['p_bookingEndDate'] = $params['p_bookingEndDate']? $params['p_bookingEndDate'] :  $params['pre_bookingEndDate'];
		$params['startTime'] = $params['startTime']? $params['startTime'] :  $params['pre_startTime'];
		$params['endTime'] = $params['endTime']? $params['endTime'] :  $params['pre_endTime'];
	}
	

	$productcode = $params['productcode'];

	$return = array();
	//��ٱ��� ����Ű Ȯ��
	/*
	if(_empty($_ShopInfo->getMemid())){
		_alert('�α��� �ؾ߸� ��� ������ ��� �Դϴ�.','-1');
	}else{
		mysql_query("delete from recommand_basket where memid='".$_ShopInfo->getMemid()."' and recomcode is null",get_db_conn());
	}
	*/
	
	/*
	if($_ShopInfo->getMemid() == 'getmall'){
	echo $ordertype;
		exit;
	}*/

	if(_empty($_ShopInfo->getTempkey()) || $_ShopInfo->getTempkey()=="deleted")		$_ShopInfo->setTempkey($_data->ETCTYPE["BASKETTIME"]);
	
	//��ٱ��ϴ��, �ٷα���
	if(preg_match('/^[0-9]{18}$/',$productcode)){//��ٱ��� ���

		if($ordertype=="ordernow") @mysql_query("DELETE FROM ".$baskettbl." WHERE tempkey='".$_ShopInfo->getTempkey()."' ",get_db_conn());	//�ٷα���

		for($i=0;$i<4;$i++){
			${'code'.chr(65+$i)} = substr($productcode,$i*3,3);
			if(strlen(${'code'.chr(65+$i)}) < 3) ${'code'.chr(65+$i)} = '000';			
		}
			
		$sql = "SELECT * FROM tblproductcode WHERE codeA='".$codeA."' AND codeB='".$codeB."' AND codeC='".$codeC."' AND codeD='".$codeD."' limit 1 ";		
		if(false === $cres = mysql_query($sql,get_db_conn())) return array('err'=>'ī�װ� ���� ȣ�� ����');
		if(mysql_num_rows($cres) <1) return array('err'=>'ī�װ� ���� ȣ�� ����2');
		$codeinfo = mysql_fetch_assoc($cres);
	
		if($codeinfo['group_code']=="NO"){ _alert('�ǸŰ� ����� ��ǰ �Դϴ�.','-1'); exit; } //���� �з�			
		else if($codeinfo['group_code']=="ALL" && _empty($_ShopInfo->getMemid())){ _alert('�α��� �ϼž� ��ٱ��Ͽ� ������ �� �ֽ��ϴ�.',$Dir.FrontDir."basket.php"); exit;}	//ȸ���� ���ٰ���
		else if(!_empty($codeinfo['group_code']) && $codeinfo['group_code']!="ALL" && $codeinfo['group_code']!=$_ShopInfo->getMemgroup()){ _alert('�ش� �з��� ���� ������ �����ϴ�.','-1'); exit; }	//�׷�ȸ���� ����
		
		
		$sql = "SELECT pridx,productname,quantity,display,option1,option2,option_quantity,etctype,group_check,rental FROM tblproduct WHERE productcode='".$productcode."' limit 1 ";
		if(false == $res = mysql_query($sql,get_db_conn())) return array('err'=>'��ٱ��� DB ���� ����');
		if(mysql_num_rows($res) < 1) return array('err'=>'��ǰ ������ ã���� �����ϴ�.');
		$product = mysql_fetch_assoc($res);
		
	
		if($product['display'] != 'Y') return array('err'=>'�ش� ��ǰ�� �ǸŰ� ���� �ʴ� ��ǰ�Դϴ�.');		
		if($product['group_check']!="N") {
			if(_empty($_ShopInfo->getMemid())) return array('err'=>'�ش� ��ǰ�� ȸ�� ���� ��ǰ�Դϴ�.');		
			$sqlgc = "SELECT COUNT(productcode) AS groupcheck_count FROM tblproductgroupcode WHERE productcode='".$productcode."' AND group_code='".$_ShopInfo->getMemgroup()."' ";
			if(false === $res = mysql_query($sqlgc,get_db_conn())) return array('err'=>'��ٱ��� DB ���� ����');
			if(mysql_num_rows($res) < 1 || mysql_result($res,0,0) < 1) return array('err'=>"�ش� ��ǰ�� ���� ��� ���� ��ǰ�Դϴ�.");
		}
		
		if($product['rental'] == '2'){		 // ��Ż ��ǰ
			$pinfo = rentProduct::read($productcode);	
			
			if(is_string($params['rentOptionList']) && preg_match('/|[0-9]+/',$params['rentOptionList']))  $options =  parseRentRequestOption($params['rentOptionList']);
			
			if(!_array($options)) return array('err'=>'�ɼ� ���� ����');
			$quantity = 0 ;
			foreach($options as $q) if(_isInt($q)) $quantity += $q;	
			
			
		}else{			
			$quantity = _isInt($params['quantity'])?$params['quantity']:1;
			if(!_isInt($quantity)) return array('err'=>'������ �߸��Ǿ����ϴ�.');
			$opts=$params["opts"];	//�ɼǱ׷� ���õ� �׸� (��:1,1,2,)
			$option1=$params["option1"];	//�ɼ�1
			$option2=$params["option2"];	//�ɼ�2
			
			$orgquantity=$params["orgquantity"];
			$orgoption1=$params["orgoption1"];
			$orgoption2=$params["orgoption2"];
			
			if($ordertype != 'recommandnow'){			
				$sql = "SELECT * FROM ".$baskettbl." WHERE tempkey='".$_ShopInfo->getTempkey()."' AND productcode='".$productcode."' AND opt1_idx='".$option1."' AND opt2_idx='".$option2."' AND optidxs='".$opts."' limit 1 ";
				if(false === $res = mysql_query($sql,get_db_conn())) return array('err'=>'��ٱ��� DB ���� ����');
				if(mysql_num_rows($res)){
					$basketitem = mysql_fetch_assoc($res);
					$quantity += $basketitem['quantity'];
				}
			}
		}
		
		$miniq=1;
		$maxq="?";
		if(!_empty($product['etctype'])){
			$etctemp = explode("",$product['etctype']);
			for($i=0;$i<count($etctemp);$i++) {
				if(substr($etctemp[$i],0,6)=="MINIQ=")     $miniq=substr($etctemp[$i],6);
				if(substr($etctemp[$i],0,5)=="MAXQ=")      $maxq=substr($etctemp[$i],5);
			}

			if(!_empty(dickerview($product['etctype'],0,1))) return array('err'=>"�ش� ��ǰ�� �ǸŰ� ���� �ʽ��ϴ�. �ٸ� ��ǰ�� �ֹ��� �ּ���.");
		}
		
		if ($miniq!=1 && $miniq>1 && $quantity<$miniq) return array('err'=>"�ش� ��ǰ�� �ּ� ".$miniq."�� �̻� �ֹ��ϼž� �մϴ�.");
		if ($maxq!="?" && $maxq>0 && $quantity>$maxq) return array('err'=>"�ش� ��ǰ�� �ִ� ".$maxq."�� ���Ϸ� �ֹ��ϼž� �մϴ�.");	
		
		if($product['rental'] == '2'){ // ��Ż ��ǰ
			$start = trim($params['p_bookingStartDate'].' '.$params['startTime']);
			$end = trim($params['p_bookingEndDate'].' '.$params['endTime']);
			
			$ret = rentProduct::insertBasket($ordertype, $params['ord_deli_type'], $_ShopInfo->getTempkey(),$product['pridx'],$options,$start,$end,$params['selFolder']);
	
			if(!_empty($ret['err']) && $ret['err'] != 'ok'){
				_alert($ret['err'],'-1');
				exit;
			}
		}else{ // �Ϲ� ��ǰ
			if(empty($option1) && !_empty($product['option1']))  $option1=1;
			if(empty($option2) && !_empty($product['option2']))  $option2=1;
			
			if(!_empty($product['quantity'])){
				if($product['quantity'] < 1) return array('err'=>"�ش� ��ǰ�� �ٸ� ���� �ֹ����� ǰ���Ǿ����ϴ�.");
				if($quantity>$product['quantity']) return array('err'=>"�ش� ��ǰ�� ��� ".($_data->ETCTYPE["STOCK"]=="N"?"�����մϴ�.":"���� ".$product['quantity']." �� �Դϴ�."));
			}

			if(!_empty($product['option_quantity'])){
				$optioncnt = explode(",",substr($product['option_quantity'],1));
				if($option2==0) $tmoption2=1;
				else $tmoption2=$option2;
				$optionvalue=$optioncnt[(($tmoption2-1)*10)+($option1-1)];
				if($optionvalue<=0 && $optionvalue!="") return array('err'=>"�ش� ��ǰ�� ���õ� �ɼ��� �ٸ� ���� �ֹ����� ǰ���Ǿ����ϴ�.");
				else if($optionvalue<$quantity && $optionvalue!="") return array('err'=>"�ش� ��ǰ�� ���õ� �ɼ��� ��� ".($_data->ETCTYPE["STOCK"]=="N"?"�����մϴ�.":$optionvalue." �� �Դϴ�."));
			}
			if($ordertype == 'recommandnow'){
				$sql = "insert into recommand_basket set memid='".$_ShopInfo->getMemid()."',productcode='".$productcode."',opt1_idx='1',opt2_idx='',optidxs='',quantity='".$quantity."',deli_type='".$params['ord_deli_type']."',date=NOW()";
			}else{
				if(_array($basketitem)) $sql = "update ".$baskettbl." set quantity='".$quantity."' where basketidx='".$basketitem['basketidx']."'";
				else $sql = "insert into ".$baskettbl." set tempkey='".$_ShopInfo->getTempkey()."', productcode	= '".$productcode."',opt1_idx	= '".$option1."',opt2_idx	= '".$option2."',optidxs		= '".$opts."',quantity	= '".$quantity."',deli_type='".$params['ord_deli_type']."',date= '".date('YmdHis')."',memid= '".$_ShopInfo->getMemid()."',ordertype= '".$ordertype."',folder='".$selFolder."'";
				//echo $sql;exit;
			}
			if(!mysql_query($sql,get_db_conn())) return array('err'=>'��ٱ��� ��� ����');
		}

		if($ordertype=="ordernow") {	//�ٷα���
			_alert('',$Dir.FrontDir."login.php?chUrl=".urlencode( $Dir.FrontDir."order.php?ordertype=ordernow" ));
		}else if($ordertype == 'recommandnow'){
			_alert('','/front/order.php?ordertype=recommand');		
		}else{
			return;
		}
		return;
		exit;
	}else{
		return array('err'=>'��ǰ������ ã���� �����ϴ�.');
	}				
}


function addWish($params=array()){
}



function deleteBasket($baskettbl,$basketidxs=array()){	
	$keys = array();
	if(!_array($basketidxs)) return;
	foreach($basketidxs as $basketidx){
		if(_isInt($basketidx)){			
			$sql = "delete r.* from rent_basket_temp r left join ".$baskettbl." b on b.basketidx=r.basketidx and r.ordertype=b.ordertype where b.basketidx='".$basketidx."'";
		//	echo $sql;
			if(false === mysql_query($sql,get_db_conn())) return false;			
			$sql = "delete from ".$baskettbl." where basketidx='".$basketidx."' limit 1";
			if(false === mysql_query($sql,get_db_conn())) return false;
		}
	}
}

function updateBasketQuantity($ordertype,$basketidx,$quantity){
	global $_ShopInfo;

	$baskettbl = basketTable($ordertype);
	if(_empty($baskettbl) || !_isInt($basketidx) || !_isInt($quantity)) return array('err'=>'���� �� ����');
	
	$sql = "select p.rental from ".$baskettbl." b left join tblproduct p on p.productcode= b.productcode where b.basketidx ='".$basketidx."' limit 1";
	if(false === $res = mysql_query($sql,get_db_conn())) return array('err'=>'DB ���� ����');
	if(mysql_num_rows($res) < 1)  return array('err'=>'����� ã���� �����ϴ�.');
	if(mysql_result($res,0,0) != '2'){
		$sql = "update ".$baskettbl." b set b.quantity= '".$quantity."' where b.basketidx='".$basketidx."'";
		if(false === mysql_query($sql,get_db_conn())) return array('err'=>'DB ó�� ����');
		return array('err'=>'');
	}else{	
		//echo $basketidx;exit;
		$info = rentProduct::readBasket($baskettbl,$_ShopInfo->getTempkey(),NULL,$basketidx,$_ShopInfo->getMemid());
		if(!_empty($info['err'])) return $info;
		if(!_array($info['items']) || $basketidx != $info['items']['basketidx']) return array('err'=>'����� ã���� �����ϴ�.');
		
		$schedules = rentProduct::schedule($info['items']['pridx'],$info['items']['start'],$info['items']['end']);	
		if(!_empty($schedules['err'])) return array('err'=>$schedules['err']);
	
		$tmp = array();	
		$tmp[$info['items']['optidx']] = $quantity;
		$check = rentProduct::checkRentable($tmp,$schedules,true);
		if(!_empty($check['err'])) return array('err'=>$check['err']);
		else if(_array($check['disable'])){
			foreach($check['disable'] as $date=>$ablecnt){
				return array('err'=>$date.' ���� �Ұ�');
				break;
			}
		}	
		$sql = "update ".$baskettbl." b left join rent_basket_temp r on b.basketidx=r.basketidx and b.ordertype=r.ordertype set b.quantity= '".$quantity."',r.quantity='".$quantity."' where b.basketidx='".$basketidx."'";
		if(false === mysql_query($sql,get_db_conn())) return array('err'=>'DB ó�� ����');
		return array('err'=>'');
	}
}

function updateBasketDelitype($ordertype,$basketidx,$deli_type){
	global $_ShopInfo;

	$baskettbl = basketTable($ordertype);
	if(_empty($baskettbl) || !_isInt($basketidx) || _empty($deli_type)) return array('err'=>'���� �� ����');
	
	$sql = "update ".$baskettbl." b left join rent_basket_temp r on b.basketidx=r.basketidx and b.ordertype=r.ordertype set b.deli_type= '".$deli_type."',r.deli_type='".$deli_type."' where b.basketidx='".$basketidx."'";
	if(false === mysql_query($sql,get_db_conn())) return array('err'=>'DB ó�� ����');
	return array('err'=>'');
}


function clearBasket($baskettbl){
	global $_ShopInfo;
	if(!_empty($baskettbl)){
		$rsql = "delete r.* from rent_basket_temp r inner join ".$baskettbl." b on b.basketidx=r.basketidx where b.tempkey= '".$_ShopInfo->getTempkey()."'"; // ��Ʈ ���� �ӽ� ���̺� ����
		mysql_query($sql,get_db_conn());
		$sql = "DELETE FROM ".$basket." WHERE tempkey='".$_ShopInfo->getTempkey()."' ";
		mysql_query($sql,get_db_conn());
	}
}


function recommandBasket(){
	
}
?>