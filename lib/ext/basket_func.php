<?

//  전달값 통으로 전달
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
	//장바구니 인증키 확인
	/*
	if(_empty($_ShopInfo->getMemid())){
		_alert('로그인 해야만 사용 가능한 기능 입니다.','-1');
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
	
	//장바구니담기, 바로구매
	if(preg_match('/^[0-9]{18}$/',$productcode)){//장바구니 담기

		if($ordertype=="ordernow") @mysql_query("DELETE FROM ".$baskettbl." WHERE tempkey='".$_ShopInfo->getTempkey()."' ",get_db_conn());	//바로구매

		for($i=0;$i<4;$i++){
			${'code'.chr(65+$i)} = substr($productcode,$i*3,3);
			if(strlen(${'code'.chr(65+$i)}) < 3) ${'code'.chr(65+$i)} = '000';			
		}
			
		$sql = "SELECT * FROM tblproductcode WHERE codeA='".$codeA."' AND codeB='".$codeB."' AND codeC='".$codeC."' AND codeD='".$codeD."' limit 1 ";		
		if(false === $cres = mysql_query($sql,get_db_conn())) return array('err'=>'카테고리 정보 호출 오류');
		if(mysql_num_rows($cres) <1) return array('err'=>'카테고리 정보 호출 오류2');
		$codeinfo = mysql_fetch_assoc($cres);
	
		if($codeinfo['group_code']=="NO"){ _alert('판매가 종료된 상품 입니다.','-1'); exit; } //숨김 분류			
		else if($codeinfo['group_code']=="ALL" && _empty($_ShopInfo->getMemid())){ _alert('로그인 하셔야 장바구니에 담으실 수 있습니다.',$Dir.FrontDir."basket.php"); exit;}	//회원만 접근가능
		else if(!_empty($codeinfo['group_code']) && $codeinfo['group_code']!="ALL" && $codeinfo['group_code']!=$_ShopInfo->getMemgroup()){ _alert('해당 분류의 접근 권한이 없습니다.','-1'); exit; }	//그룹회원만 접근
		
		
		$sql = "SELECT pridx,productname,quantity,display,option1,option2,option_quantity,etctype,group_check,rental FROM tblproduct WHERE productcode='".$productcode."' limit 1 ";
		if(false == $res = mysql_query($sql,get_db_conn())) return array('err'=>'장바구니 DB 질의 오류');
		if(mysql_num_rows($res) < 1) return array('err'=>'상품 정보를 찾을수 없습니다.');
		$product = mysql_fetch_assoc($res);
		
	
		if($product['display'] != 'Y') return array('err'=>'해당 상품은 판매가 되지 않는 상품입니다.');		
		if($product['group_check']!="N") {
			if(_empty($_ShopInfo->getMemid())) return array('err'=>'해당 상품은 회원 전용 상품입니다.');		
			$sqlgc = "SELECT COUNT(productcode) AS groupcheck_count FROM tblproductgroupcode WHERE productcode='".$productcode."' AND group_code='".$_ShopInfo->getMemgroup()."' ";
			if(false === $res = mysql_query($sqlgc,get_db_conn())) return array('err'=>'장바구니 DB 질의 오류');
			if(mysql_num_rows($res) < 1 || mysql_result($res,0,0) < 1) return array('err'=>"해당 상품은 지정 등급 전용 상품입니다.");
		}
		
		if($product['rental'] == '2'){		 // 렌탈 상품
			$pinfo = rentProduct::read($productcode);	
			
			if(is_string($params['rentOptionList']) && preg_match('/|[0-9]+/',$params['rentOptionList']))  $options =  parseRentRequestOption($params['rentOptionList']);
			
			if(!_array($options)) return array('err'=>'옵션 전달 오류');
			$quantity = 0 ;
			foreach($options as $q) if(_isInt($q)) $quantity += $q;	
			
			
		}else{			
			$quantity = _isInt($params['quantity'])?$params['quantity']:1;
			if(!_isInt($quantity)) return array('err'=>'수량이 잘못되었습니다.');
			$opts=$params["opts"];	//옵션그룹 선택된 항목 (예:1,1,2,)
			$option1=$params["option1"];	//옵션1
			$option2=$params["option2"];	//옵션2
			
			$orgquantity=$params["orgquantity"];
			$orgoption1=$params["orgoption1"];
			$orgoption2=$params["orgoption2"];
			
			if($ordertype != 'recommandnow'){			
				$sql = "SELECT * FROM ".$baskettbl." WHERE tempkey='".$_ShopInfo->getTempkey()."' AND productcode='".$productcode."' AND opt1_idx='".$option1."' AND opt2_idx='".$option2."' AND optidxs='".$opts."' limit 1 ";
				if(false === $res = mysql_query($sql,get_db_conn())) return array('err'=>'장바구니 DB 접속 오류');
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

			if(!_empty(dickerview($product['etctype'],0,1))) return array('err'=>"해당 상품은 판매가 되지 않습니다. 다른 상품을 주문해 주세요.");
		}
		
		if ($miniq!=1 && $miniq>1 && $quantity<$miniq) return array('err'=>"해당 상품은 최소 ".$miniq."개 이상 주문하셔야 합니다.");
		if ($maxq!="?" && $maxq>0 && $quantity>$maxq) return array('err'=>"해당 상품은 최대 ".$maxq."개 이하로 주문하셔야 합니다.");	
		
		if($product['rental'] == '2'){ // 렌탈 상품
			$start = trim($params['p_bookingStartDate'].' '.$params['startTime']);
			$end = trim($params['p_bookingEndDate'].' '.$params['endTime']);
			
			$ret = rentProduct::insertBasket($ordertype, $params['ord_deli_type'], $_ShopInfo->getTempkey(),$product['pridx'],$options,$start,$end,$params['selFolder']);
	
			if(!_empty($ret['err']) && $ret['err'] != 'ok'){
				_alert($ret['err'],'-1');
				exit;
			}
		}else{ // 일반 상품
			if(empty($option1) && !_empty($product['option1']))  $option1=1;
			if(empty($option2) && !_empty($product['option2']))  $option2=1;
			
			if(!_empty($product['quantity'])){
				if($product['quantity'] < 1) return array('err'=>"해당 상품이 다른 고객의 주문으로 품절되었습니다.");
				if($quantity>$product['quantity']) return array('err'=>"해당 상품의 재고가 ".($_data->ETCTYPE["STOCK"]=="N"?"부족합니다.":"현재 ".$product['quantity']." 개 입니다."));
			}

			if(!_empty($product['option_quantity'])){
				$optioncnt = explode(",",substr($product['option_quantity'],1));
				if($option2==0) $tmoption2=1;
				else $tmoption2=$option2;
				$optionvalue=$optioncnt[(($tmoption2-1)*10)+($option1-1)];
				if($optionvalue<=0 && $optionvalue!="") return array('err'=>"해당 상품의 선택된 옵션은 다른 고객의 주문으로 품절되었습니다.");
				else if($optionvalue<$quantity && $optionvalue!="") return array('err'=>"해당 상품의 선택된 옵션의 재고가 ".($_data->ETCTYPE["STOCK"]=="N"?"부족합니다.":$optionvalue." 개 입니다."));
			}
			if($ordertype == 'recommandnow'){
				$sql = "insert into recommand_basket set memid='".$_ShopInfo->getMemid()."',productcode='".$productcode."',opt1_idx='1',opt2_idx='',optidxs='',quantity='".$quantity."',deli_type='".$params['ord_deli_type']."',date=NOW()";
			}else{
				if(_array($basketitem)) $sql = "update ".$baskettbl." set quantity='".$quantity."' where basketidx='".$basketitem['basketidx']."'";
				else $sql = "insert into ".$baskettbl." set tempkey='".$_ShopInfo->getTempkey()."', productcode	= '".$productcode."',opt1_idx	= '".$option1."',opt2_idx	= '".$option2."',optidxs		= '".$opts."',quantity	= '".$quantity."',deli_type='".$params['ord_deli_type']."',date= '".date('YmdHis')."',memid= '".$_ShopInfo->getMemid()."',ordertype= '".$ordertype."',folder='".$selFolder."'";
				//echo $sql;exit;
			}
			if(!mysql_query($sql,get_db_conn())) return array('err'=>'장바구니 등록 오류');
		}

		if($ordertype=="ordernow") {	//바로구매
			_alert('',$Dir.FrontDir."login.php?chUrl=".urlencode( $Dir.FrontDir."order.php?ordertype=ordernow" ));
		}else if($ordertype == 'recommandnow'){
			_alert('','/front/order.php?ordertype=recommand');		
		}else{
			return;
		}
		return;
		exit;
	}else{
		return array('err'=>'상품정보를 찾을수 없습니다.');
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
	if(_empty($baskettbl) || !_isInt($basketidx) || !_isInt($quantity)) return array('err'=>'전달 값 오류');
	
	$sql = "select p.rental from ".$baskettbl." b left join tblproduct p on p.productcode= b.productcode where b.basketidx ='".$basketidx."' limit 1";
	if(false === $res = mysql_query($sql,get_db_conn())) return array('err'=>'DB 질의 오류');
	if(mysql_num_rows($res) < 1)  return array('err'=>'대상을 찾을수 없습니다.');
	if(mysql_result($res,0,0) != '2'){
		$sql = "update ".$baskettbl." b set b.quantity= '".$quantity."' where b.basketidx='".$basketidx."'";
		if(false === mysql_query($sql,get_db_conn())) return array('err'=>'DB 처리 오류');
		return array('err'=>'');
	}else{	
		//echo $basketidx;exit;
		$info = rentProduct::readBasket($baskettbl,$_ShopInfo->getTempkey(),NULL,$basketidx,$_ShopInfo->getMemid());
		if(!_empty($info['err'])) return $info;
		if(!_array($info['items']) || $basketidx != $info['items']['basketidx']) return array('err'=>'대상을 찾을수 없습니다.');
		
		$schedules = rentProduct::schedule($info['items']['pridx'],$info['items']['start'],$info['items']['end']);	
		if(!_empty($schedules['err'])) return array('err'=>$schedules['err']);
	
		$tmp = array();	
		$tmp[$info['items']['optidx']] = $quantity;
		$check = rentProduct::checkRentable($tmp,$schedules,true);
		if(!_empty($check['err'])) return array('err'=>$check['err']);
		else if(_array($check['disable'])){
			foreach($check['disable'] as $date=>$ablecnt){
				return array('err'=>$date.' 예약 불가');
				break;
			}
		}	
		$sql = "update ".$baskettbl." b left join rent_basket_temp r on b.basketidx=r.basketidx and b.ordertype=r.ordertype set b.quantity= '".$quantity."',r.quantity='".$quantity."' where b.basketidx='".$basketidx."'";
		if(false === mysql_query($sql,get_db_conn())) return array('err'=>'DB 처리 오류');
		return array('err'=>'');
	}
}

function updateBasketDelitype($ordertype,$basketidx,$deli_type){
	global $_ShopInfo;

	$baskettbl = basketTable($ordertype);
	if(_empty($baskettbl) || !_isInt($basketidx) || _empty($deli_type)) return array('err'=>'전달 값 오류');
	
	$sql = "update ".$baskettbl." b left join rent_basket_temp r on b.basketidx=r.basketidx and b.ordertype=r.ordertype set b.deli_type= '".$deli_type."',r.deli_type='".$deli_type."' where b.basketidx='".$basketidx."'";
	if(false === mysql_query($sql,get_db_conn())) return array('err'=>'DB 처리 오류');
	return array('err'=>'');
}


function clearBasket($baskettbl){
	global $_ShopInfo;
	if(!_empty($baskettbl)){
		$rsql = "delete r.* from rent_basket_temp r inner join ".$baskettbl." b on b.basketidx=r.basketidx where b.tempkey= '".$_ShopInfo->getTempkey()."'"; // 렌트 관련 임시 테이블 삭제
		mysql_query($sql,get_db_conn());
		$sql = "DELETE FROM ".$basket." WHERE tempkey='".$_ShopInfo->getTempkey()."' ";
		mysql_query($sql,get_db_conn());
	}
}


function recommandBasket(){
	
}
?>