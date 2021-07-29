<?
error_reporting(E_ALL);
ini_set("display_errors", 0);

$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
require_once($Dir."lib/ext/func.php");
require_once($Dir."lib/class/rentproduct.php");
include_once($Dir."lib/ext/order_func.php");

try{
	switch($_REQUEST['act']){
		case 'dateDiff':
			$diff = datediff_rent($_REQUEST['end'],$_REQUEST['start']);
			$result['date_diff']= (($diff['day'] >0)?$diff['day'].'일':'').(($diff['hour'] >0)?$diff['hour'].'시간':'');
			break;
		case 'solvPrice':		
			if(is_string($_REQUEST['opt']) && preg_match('/|[0-9]+/',$_REQUEST['opt']))  $options =  parseRentRequestOption($_REQUEST['opt']);			
			if($_REQUEST['istest'] == '1') _pr($_REQUEST);
			if(!_array($options)) throw new Exception('옵션 수량을 입력하세요');
			
			$result = rentProduct::solvPrice($_REQUEST['pridx'], $_REQUEST['opt'], $_REQUEST['sdate'], $_REQUEST['edate'], $_REQUEST['vender']);
	
			if(!_empty($result['err'])) throw new Exception($result['err']);			
			$result['range'][1]+=1;
			if($result['timegap'] == 1) $daypatt = "Y-m-d일 H시";			
			else $daypatt = "Y-m-d일";			
			foreach($result['range'] as $idx=>$val) $result['rangetxt'][$idx] = date($daypatt,$val);
			
			//적립금
			$result['reserv'] = number_format(($result['totalprice']+$result['discprice']+$result['longrent'])*$_REQUEST['reserveconv']*0.01);

			//결제금액
			if($result['pricetxt']){
				$result['pricetxt'] = $result['pricetxt'];
			}else{
				$result['pricetxt'] = $result['totalprice']+$result['discprice']+$result['longrent']-$result['discountprice'];
			}

			//_pr($result);
			break;		
		case 'basketRangeForm':
			$basket = basketTable($_REQUEST['ordertype']);
			$info = rentProduct::readBasket($basket,$_ShopInfo->getTempkey(),NULL,$_REQUEST['basketidx'],$_ShopInfo->getMemid());			
			
			$item = $info['items'];
			$prentinfo = rentProduct::read($item['pridx']);

			$startTime = date('H',strtotime($item['start'])+1);
			$html = '<form id="rangChange" action="/ajaxback/rent.php">';
			//$html.='<div style="background-color:#ff9999">';
			$html.= '<div style="margin-bottom:5px">';
			if($_REQUEST['mode']=="all"){
				$html.= '<input type="radio" name="act" value="basketRangeAllUpdate" checked/>전체상품';
				$html.= '<input type="radio" name="act" value="basketRangeSelUpdate" />선택상품<br>';
				$html.= '<input type="hidden" name="basket_selitem" value="'.$_REQUEST['basket_select_item'].'">';
			}else{
				$html.= '<input type="hidden" name="act" value="basketRangeUpdate" />';
				$html.= '<input type="hidden" name="basketidx" value="'.$_REQUEST['basketidx'].'" />';
			}
			$html.= '</div>';
			//$html.= '<input type="hidden" name="act" value="basketRangeUpdate" />';
			$html.= '<input type="hidden" name="ordertype" value="'.$_REQUEST['ordertype'].'" />';
			$html.= '<input type="hidden" name="pricetype" id="pricetype" value="'.$prentinfo['codeinfo']['pricetype'].'" />';
			$html.= '<input type="hidden" name="sfld" value="'.$_REQUEST['sfld'].'" />';
			$html.= '<input type="hidden" name="quantity" value="'.$_REQUEST['quantity'].'" />';
			$html.= '<div style="border:1px solid #333333;"><span style="float:left;display:inline-block;*display:inline;*zoom:1;line-height:33px;padding:0px 5px 0px 10px;">대여일</span> ';
			$html.= '<input type="text" name="p_bookingSDate" id="p_bookingSDate" value="'.substr($item['start'],5,5).'" class="input1" style="width:80px;">';	
			$html.= '<input type="hidden" name="p_bookingStartDate" id="p_bookingStartDate" value="'.substr($item['start'],0,10).'">';
			
			if($prentinfo['codeinfo']['pricetype'] != 'period'){//단기기간제제외
				$html.= '<select name="startTime" id="startTime" class="select1" style="height:auto;" onchange="disableCheck(this)">';
				
				if($prentinfo['codeinfo']['pricetype'] == 'checkout'){//숙박제
					if($prentinfo['codeinfo']['checkout_time']==0 || $prentinfo['codeinfo']['checkin_time']>$prentinfo['codeinfo']['checkout_time']){
						$end_time = 23;
					}else{
						$end_time = $prentinfo['codeinfo']['checkout_time'];
					}
					for($i=$prentinfo['codeinfo']['checkin_time'];$i<=$end_time;$i++){
						$prentinfo['codeinfo']['checkin_time']=$prentinfo['codeinfo']['checkin_time']?$prentinfo['codeinfo']['checkin_time']:date("H")+1;
						$sel = $i==$prentinfo['codeinfo']['checkin_time']?'selected':'';
						$html .= '<option value="'.sprintf('%02d',$i).'" '.$sel.'>'.sprintf('%02d',$i).'시</option>';
					}
				}else{
					for($i=0;$i<=23;$i++){
						//$sel = $i==$prentinfo['codeinfo']['rent_stime']?'selected':'';
						$sel = $i==$startTime? 'selected':'';

						if($prentinfo['codeinfo']['rent_stime']!="0" && $prentinfo['codeinfo']['rent_etime']!="0" && ($i<$prentinfo['codeinfo']['rent_stime'] || $i>$prentinfo['codeinfo']['rent_etime'])){
							$optionStyle=" class='disabled'";
						}else{
							$optionStyle="";
						}
						$html .= '<option value="'.sprintf('%02d',$i).'" '.$sel.' '.$optionStyle.'>'.sprintf('%02d',$i).'시</option>';
					}
				}
				/*
				if($prentinfo['codeinfo']['checkout_time']==0 || $prentinfo['codeinfo']['checkin_time']>$prentinfo['codeinfo']['checkout_time']){
					$end_time = 23;
				}else{
					$end_time = $prentinfo['codeinfo']['checkout_time'];
				}
				for($i=$prentinfo['codeinfo']['checkin_time'];$i<=$end_time;$i++){
					//$sel = $i==$prentinfo['codeinfo']['checkin_time']?'selected':'';
					$sel = $i==substr($item['start'],11,2)? 'selected':'';
					$html .= '<option value="'.sprintf('%02d',$i).'" '.$sel.'>'.sprintf('%02d',$i).'시</option>';
				}
				*/
				$html.='</select>';
			}
			
			$html.='</div>';
/*
			if($prentinfo['codeinfo']['pricetype'] == 'time') $html .= '&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="startTime" value="'.substr($item['start'],11,2).'" style="border:0px; width:25px;">시&nbsp; 00분';
			else if($prentinfo['codeinfo']['pricetype'] == 'day') $html .= '&nbsp;&nbsp; 00시 ~ ';
			else if($prentinfo['codeinfo']['pricetype'] == 'checkout') $html .= ' 12시 ~ ';		
*/			
			$endDate = date('m-d',strtotime($item['end'])+1);
			$endDate_hidden = date('Y-m-d',strtotime($item['end'])+1);
			$endTime = date('H',strtotime($item['end'])+1);
			$html.= '<br><div style="border:1px solid #333333;margin-top:3px"><span style="float:left;display:inline-block;*display:inline;*zoom:1;line-height:33px;padding:0px 5px 0px 10px;">반납일</span> ';
			$html.= '<input type="text" name="p_bookingEDate" id="p_bookingEDate" value="'.$endDate.'" class="input1" style="width:80px;">';
			$html.= '<input type="hidden" name="p_bookingEndDate" id="p_bookingEndDate" value="'.$endDate_hidden.'" class="input">';

			if($prentinfo['codeinfo']['pricetype'] != 'period'){//단기기간제제외
				$html.= '<select name="endTime" id="endTime" onChange="priceCalc2(this.form);disableCheck(this)" class="select1" style="height:auto;">';

				if($prentinfo['codeinfo']['pricetype'] == 'checkout'){//숙박제
					if($prentinfo['codeinfo']['checkout_time']==0){
						$end_time = 23;
					}else{
						$end_time = $prentinfo['codeinfo']['checkout_time'];
					}
					for($i=0;$i<=$end_time;$i++){
						/*
						if($prentinfo['codeinfo']['checkout_time']==0 && $prentinfo['codeinfo']['pricetype']=="time"){
							$sel = $i==($prentinfo['codeinfo']['checkin_time']+$prentinfo['codeinfo']['base_time'])?'selected':'';
						}else{
							$sel = $i==$prentinfo['codeinfo']['checkout_time']?'selected':'';
						}
						*/
						$sel = $i==$endTime?'selected':'';
						$html .= '<option value="'.sprintf('%02d',$i).'" '.$sel.'>'.sprintf('%02d',$i).'시</option>';
					}
				}else{
					
					for($i=0;$i<=23;$i++){
						/*
						if($prentinfo['codeinfo']['rent_stime']==0 && $prentinfo['codeinfo']['pricetype']=="time"){
							$sel = $i==$prentinfo['codeinfo']['rent_etime']?'selected':'';
						}else{
							$sel = $i==$prentinfo['codeinfo']['rent_etime']?'selected':'';
						}
						*/
						$sel = $i==$endTime?'selected':'';
						if($prentinfo['codeinfo']['rent_stime']!="0" && $prentinfo['codeinfo']['rent_etime']!="0" && ($i<$prentinfo['codeinfo']['rent_stime'] || $i>$prentinfo['codeinfo']['rent_etime'])){
							$optionStyle=" class='disabled'";
						}else{
							$optionStyle="";
						}
						$html .= '<option value="'.sprintf('%02d',$i).'" '.$sel.' '.$optionStyle.'>'.sprintf('%02d',$i).'시</option>';
					}
				}
/*
				if($prentinfo['codeinfo']['checkout_time']==0){
					$end_time = 23;
				}else{
					$end_time = $prentinfo['codeinfo']['checkout_time'];
				}
				for($i=0;$i<=$end_time;$i++){
					if($prentinfo['codeinfo']['checkout_time']==0 && $prentinfo['codeinfo']['pricetype']=="time"){
						$sel = $i==($prentinfo['codeinfo']['checkin_time']+$prentinfo['codeinfo']['base_time'])?'selected':'';
					}else{
						$sel = $i==$prentinfo['codeinfo']['checkout_time']?'selected':'';
					}
					$sel = $i==date('H',strtotime($item['end'])+1)? 'selected':'';
					$html.= '<option value="'.sprintf('%02d',$i).'" '.$sel.'>'.sprintf('%02d',$i).'시</option>';
				}
				*/
				$html.= '</select>';
			}
			$html.='</div>';

			$html.='
			<script>
			$j(function(){
				if($j("#p_bookingSDate")){
					$j("#p_bookingSDate").datepicker({
					  showOn: "both",
					  dateFormat:"mm-dd",
					  dayNames: ["일","월","화","수","목","금","토"],
					  buttonImage: "/images/mini_cal_calen.gif",
					  minDate: 1,
					  buttonImageOnly: true,
					  buttonText: "대여일",	
					  altField: "#p_bookingStartDate",
					  altFormat: "yy-mm-dd",
					  onClose: function( selectedDate ) {
					  }
					  ,onSelect: function( selectedDate ) {
						//$j("#p_bookingEndDate" ).datepicker( "option", "minDate", selectedDate );
						if($j("#p_bookingEDate").val()<$j("#p_bookingSDate").val()){
							//alert("반납일이 대여일이전일 수 없습니다.");
							//$j("#p_bookingSDate").val("대여일");
						}
						fixrenttime();
					  }
					});
			
					$j("#p_bookingEDate").datepicker({
					  showOn: "both",
					  dateFormat:"mm-dd",
					  dayNames: ["일","월","화","수","목","금","토"],
					  buttonImage: "/images/mini_cal_calen.gif",
					  minDate: 1,
					  buttonImageOnly: true,
					  buttonText: "반납일",
					  altField: "#p_bookingEndDate",
					  altFormat: "yy-mm-dd",
					  onClose: function( selectedDate ) {
					  }
					  ,onSelect: function( selectedDate ) {
						//$j( "#p_bookingStartDate" ).datepicker( "option", "maxDate", selectedDate );
						if($j("#p_bookingSDate").val()=="대여일"){
							alert("대여일 먼저 선택하세요.");$j("#p_bookingEDate").val("반납일");
						}
						if($j("#p_bookingEDate").val()<$j("#p_bookingSDate").val()){
							alert("반납일이 대여일이전일 수 없습니다.");$j("#p_bookingEDate").val("반납일");
						}
						fixrenttime();
					  }
					});	
				}
			});
			</script>';
/*
			if($prentinfo['codeinfo']['pricetype'] == 'time'){
				$html .= '<select name="endTime" id="endTime" >';
				for($i=0;$i<=23;$i++){
					$sel = sprintf('%02d',$i)==substr($item['end'],11,2)?'selected':'';
					$html .= '<option value="'.sprintf('%02d',$i).'" '.$sel.'>'.sprintf('%02d',$i).'시</option>';
				}
				$html .= '</select>&nbsp;59분';
			}else if($prentinfo['codeinfo']['pricetype'] == 'day') $html .= '24시';
			else if($prentinfo['codeinfo']['pricetype'] == 'checkout') $html .= '11시'; 
*/		
//$html.='</div>';
			$result['html'] = $html;
			break;
		case 'basketRangeUpdate':
			$basket = basketTable($_REQUEST['ordertype']);
			$info = rentProduct::readBasket($basket,$_ShopInfo->getTempkey(),NULL,$_REQUEST['basketidx'],$_ShopInfo->getMemid());								
			if(!_empty($info['err'])) return $info;
			if(!_array($info['items']) || $basketidx != $info['items']['basketidx']) throw new ErrorException('대상을 찾을수 없습니다.');
			
			$start = trim($_REQUEST['p_bookingStartDate'].' '.$_REQUEST['startTime']);
			$end = trim($_REQUEST['p_bookingEndDate'].' '.$_REQUEST['endTime']);		
			
			$schedules = rentProduct::schedule($info['items']['pridx'],$start,$end);	
		//	_pr($schedules);
			if(!_empty($schedules['err'])) return array('err'=>$schedules['err']);
		
			$tmp = array();	
			$tmp[$info['items']['optidx']] = $quantity;
			
			$check = rentProduct::checkRentable($tmp,$schedules,true);

			if(!_empty($check['err'])) return array('err'=>$check['err']);
			else if(_array($check['disable'])){
				foreach($check['disable'] as $date=>$ablecnt){
					throw new ErrorException($date.' 예약 불가');
				}
			}	
			$sql = "update rent_basket_temp r left join  ".$basket." b on b.basketidx=r.basketidx and b.ordertype=r.ordertype set r.start='".date('Y-m-d H:i:s',$schedules['rangestamp'][0])."',r.end='".date('Y-m-d H:i:s',$schedules['rangestamp'][1])."' where b.basketidx='".$basketidx."'";
			
						
			if(false === mysql_query($sql,get_db_conn())) throw new ErrorException('DB 처리 오류');
			_alert('수정 되었습니다.','/front/basket.php?ordertype='.$_REQUEST['ordertype'].'&sfld='.$_REQUEST['sfld']);
			break;
		
		case 'basketRangeAllUpdate':
			//throw new ErrorException("d");
			$basket = basketTable($_REQUEST['ordertype']);
			$basketItems = getBasketByArray($basket,NULL,$_REQUEST['sfld']);

			// 밴더별 장바구니 정보 (벤더 0 은 본사) ****************************************************
			foreach($basketItems['vender'] as $vender=>&$venderval){

				$res = getBasketByResource($basket,$vender,'',$folder);

				// 밴더별 > 상품별 장바구니 정보
				while($product = mysql_fetch_assoc($res)){
					$start = trim($_REQUEST['p_bookingStartDate'].' '.$_REQUEST['startTime']);
					$end = trim($_REQUEST['p_bookingEndDate'].' '.$_REQUEST['endTime']);		
					
					$schedules = rentProduct::schedule($product['pridx'],$start,$end);	
					if(!_empty($schedules['err'])) throw new ErrorException($schedules['err']);//return array('err'=>$schedules['err']);
				
					$prentinfo['codeinfo'] = rentProduct::getVenderRent($product['vender'],$product['pridx'],$product['productcode']);
					
					$diff = datediff_rent($end,$start);

					if($prentinfo['codeinfo']['pricetype']=="day"){//24시간제
						if($prentinfo['codeinfo']['rent_stime']!=$prentinfo['codeinfo']['rent_etime']){
							if($_REQUEST['startTime']<$prentinfo['codeinfo']['rent_stime'] || $_REQUEST['startTime']>$prentinfo['codeinfo']['rent_etime']) throw new ErrorException('대여 가능한 시간을 벗어난 상품이 있습니다. 다시 선택하세요.');
							if($_REQUEST['endTime']<$prentinfo['codeinfo']['rent_stime'] || $_REQUEST['endTime']>$prentinfo['codeinfo']['rent_etime']) throw new ErrorException('대여 가능한 시간을 벗어난 상품이 있습니다. 다시 선택하세요.');
						}
					}else if($prentinfo['codeinfo']['pricetype']=="time"){//시간제
						if($diff['day']*24+$diff['hour'] <$prentinfo['codeinfo']['base_time']) throw new ErrorException('최소 렌탈 시간은 '.$prentinfo['codeinfo']['base_time'].'시간 입니다.');
						if($prentinfo['codeinfo']['rent_stime']!=$prentinfo['codeinfo']['rent_etime']){
							if($_REQUEST['startTime']<$prentinfo['codeinfo']['rent_stime'] || $_REQUEST['startTime']>$prentinfo['codeinfo']['rent_etime']) throw new ErrorException('대여 가능한 시간을 벗어난 상품이 있습니다. 다시 선택하세요.');
							if($_REQUEST['endTime']<$prentinfo['codeinfo']['rent_stime'] || $_REQUEST['endTime']>$prentinfo['codeinfo']['rent_etime']) throw new ErrorException('대여 가능한 시간을 벗어난 상품이 있습니다. 다시 선택하세요.');
						}
					}else if($prentinfo['codeinfo']['pricetype']=="checkout"){
						if($prentinfo['codeinfo']['rent_stime']!=$prentinfo['codeinfo']['rent_etime']){
							if($_REQUEST['startTime']<$prentinfo['codeinfo']['checkin_time']) throw new ErrorException('대여 가능한 시간을 벗어난 상품이 있습니다. 다시 선택하세요.');
							if($_REQUEST['endTime']>$prentinfo['codeinfo']['checkout_time']) throw new ErrorException('대여 가능한 시간을 벗어난 상품이 있습니다. 다시 선택하세요.');
						}
					}

/*
					$tmp = array();	
					$tmp[$product['optidxs']] = $quantity;
					
					$check = rentProduct::checkRentable($tmp,$schedules,true);

					if(!_empty($check['err'])) throw new Exception($check['err']);//return array('err'=>$check['err']);
					else if(_array($check['disable'])){
						foreach($check['disable'] as $date=>$ablecnt){
							throw new ErrorException($date.' 예약 불가');
						}
					}
					*/
					$sql = "update rent_basket_temp r left join  ".$basket." b on b.basketidx=r.basketidx and b.ordertype=r.ordertype set r.start='".date('Y-m-d H:i:s',$schedules['rangestamp'][0])."',r.end='".date('Y-m-d H:i:s',$schedules['rangestamp'][1])."' where b.basketidx='".$product['basketidx']."'";
					
					//throw new ErrorException($sql);

					if(false === mysql_query($sql,get_db_conn())) throw new ErrorException('DB 처리 오류');
					$i++;
				}
			}



					/*
			foreach($basketItems['vender'] as $vender=>$vendervalue){
			
				for( $i = 0 ; $i < count($vendervalue['products']) ; $i++ ){
					$product = $vendervalue['products'][$i];

					$info = rentProduct::readBasket($basket,NULL,NULL,$product['basketidx'],$_ShopInfo->getMemid());
					if(!_empty($info['err'])) return $info;
					if(!_array($info['items']) || $basketidx != $info['items']['basketidx']) throw new ErrorException('대상을 찾을수 없습니다.');


					$start = trim($_REQUEST['p_bookingStartDate'].' '.$_REQUEST['startTime']);
					$end = trim($_REQUEST['p_bookingEndDate'].' '.$_REQUEST['endTime']);		
					
					$schedules = rentProduct::schedule($info['items']['pridx'],$start,$end);	
					if(!_empty($schedules['err'])) return array('err'=>$schedules['err']);
				
					$tmp = array();	
					$tmp[$info['items']['optidx']] = $quantity;
					
					$check = rentProduct::checkRentable($tmp,$schedules,true);

					if(!_empty($check['err'])) return array('err'=>$check['err']);
					else if(_array($check['disable'])){
						foreach($check['disable'] as $date=>$ablecnt){
							throw new ErrorException($date.' 예약 불가');
						}
					}	
					$sql = "update rent_basket_temp r left join  ".$basket." b on b.basketidx=r.basketidx and b.ordertype=r.ordertype set r.start='".date('Y-m-d H:i:s',$schedules['rangestamp'][0])."',r.end='".date('Y-m-d H:i:s',$schedules['rangestamp'][1])."' where b.basketidx='".$product['basketidx']."'";

					if(false === mysql_query($sql,get_db_conn())) throw new ErrorException('DB 처리 오류');

				}
			}
			*/
			
			//_alert('수정 되었습니다.','/front/basket.php?ordertype='.$_REQUEST['ordertype'].'&sfld='.$_REQUEST['sfld']);
			break;

		case 'basketRangeSelUpdate':
			$basket = basketTable($_REQUEST['ordertype']);
			$arr_sellist = explode(",",$_REQUEST['basket_selitem']);

			for($k=0; $k<count($arr_sellist)-1; $k++) {
				$info = rentProduct::readBasket($basket,$_ShopInfo->getTempkey(),NULL,$arr_sellist[$k],$_ShopInfo->getMemid());
				//if(!_empty($info['err'])) return $info;
				//if(!_array($info['items']) || $basketidx != $info['items']['basketidx']) throw new ErrorException('대상을 찾을수 없습니다.');
				$basketidx = $info['items']['basketidx'];
				$start = trim($_REQUEST['p_bookingStartDate'].' '.$_REQUEST['startTime']);
				$end = trim($_REQUEST['p_bookingEndDate'].' '.$_REQUEST['endTime']);		
				$schedules = rentProduct::schedule($info['items']['pridx'],$start,$end);	
			//	_pr($schedules);
				if(!_empty($schedules['err'])) return array('err'=>$schedules['err']);
			
				$tmp = array();	
				$tmp[$info['items']['optidx']] = $info['items']['quantity'];
				
				$check = rentProduct::checkRentable($tmp,$schedules,true);

				if(!_empty($check['err'])) return array('err'=>$check['err']);
				else if(_array($check['disable'])){
					foreach($check['disable'] as $date=>$ablecnt){
						throw new ErrorException($date.' 예약 불가');
					}
				}	
				$sql = "update rent_basket_temp r left join  ".$basket." b on b.basketidx=r.basketidx and b.ordertype=r.ordertype set r.start='".date('Y-m-d H:i:s',$schedules['rangestamp'][0])."',r.end='".date('Y-m-d H:i:s',$schedules['rangestamp'][1])."' where b.basketidx='".$basketidx."'";
				//echo $sql;exit;
				
				if(false === mysql_query($sql,get_db_conn())) throw new ErrorException('DB 처리 오류');
				
			}

			_alert('수정 되었습니다.','/front/basket.php?ordertype='.$_REQUEST['ordertype'].'&sfld='.$_REQUEST['sfld']);
			break;

		default:
			throw new ErrorException('정의되지 않은 요청 입니다.');
			break;
	}
	$result['err']='ok';
}catch(Exception $e){
	if(in_array($_REQUEST['act'],array('basketRangeUpdate'))){
		_alert($e->getMessage(),'-1');
		exit;
	}
	$result['err'] = $e->getMessage();		
}
//_pr($result);
// php  5.2.0 이상은 추가
$phpVer = str_replace(".","",phpversion());
if( $phpVer >= 520 ) array_walk($result,'_encode');

echo json_encode($result);
exit;
?>