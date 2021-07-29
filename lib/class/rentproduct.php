<?
require_once dirname(__FILE__).'/../ext/product_func.php';
class rentProduct{
	static public $existsPridx = array();
	static public $existsPcode = array();	
	static public $codeInfos	= array();
	static public $locations	= array();
	static public $weekendarr = array('sun','sat','fri');
	static public $goodStatus 	= array("S"=>"새제품", "A"=>"A급", "B"=>"B급", "C"=>"C급");
	static public $skipendtime = 6;

	// 예약 상태 --------------------------------------------------
	// (NN)대여가능 : 대여가능상태 NN (None)
	// (NC)가예약취소 : 대여가능상태 NC(None Cancle)
	// (BR)입금대기 : 미입금 온라인 예약 신청 (무통장 입금대기) BR (Bank Ready)
	// (BC)미입금취소 : 미입금 온라인 예약 신청 취소 (무통장 입금대기 12시간 초과) BC (Bank Cancle)
	// (BO)예약완료 : 바로결제 및 입금확인 예약완료 BO (Booking Ok)
	// (BI)대여중 : 대여진행중 BI (Booking Ing)
	// (BE)대여완료 : 대여완료 BE (Booking End)
	// (CR)반납대기 : 대여 끝나고 반납중 CR (Collect Ready)
	// (CE)반납완료 : 점검전 반납확인 완료 CE (Collecting End)
	// (NR)반납불가 : 반납확인 후 기기 점검 시 기기 파손(No Return)
	// (OT)반납안됨 : 대여기간이 지나도 반납 확인 안됨. OT (OverTime)
	// (RP)정비 : 반납확인 후 정비중.(대여 불가능) RP (Repair)
	static public $bookingStatus = array(
		"NN"=>"대여가능",
		"NC"=>"가예약취소",
		"BC"=>"미입금취소",
		"CC"=>"사용자취소",
		"BR"=>"입금대기",
		"BO"=>"예약완료",
		"BI"=>"대여중",
		"BE"=>"대여완료",
		"CR"=>"반납대기",
		"CE"=>"반납완료",
		"NR"=>"반납불가(파손)",
		"OT"=>"미반납(기간초과)"/*,
		"RP"=>"정비"*/
	);
	static public $NN_limit      = 24;  // 가예약 유효 기간.
	static public $BR_nearlimit  = 2;   // 입금대기 시간 10시간이 되면 임박예고.
	static public $BR_limit      = 24;  // 입금대기 시간 (초과시 입금대기 리스트에서 제외)
	static public $rentLocationType = array( "A" => "출고지", "B" => "렌탈" ); // 장소 타입 ( 출고지A, 렌탈B )

	static $conn = NULL;
	private $pridx=NULL;
	private $productcode=NULL;
	private $info = NULL;
	
	/*
	singleton 처리
	*/
	private function __construct($pridx){
		$this->info=&self::$existsPridx[$product['pridx']];
		$this->pridx = $this->info['pridx'];
		$this->productcode = $this->info['productcode'];
	}
	
	/**
	상품(옵션)상태값 코드에 따른 상태 텍스트 반환
	@param : key goodStatus 배열 내 미칭 되는 key값
	@return key 가 매칭 될 경우는 해당 key 의 상태 텍스트, 매칭 되는 값이 없을 경우는 goodStatus 배열 전체를 반환
	*/
	static function _status($key){	
		if(!_empty($key)) return self::$goodStatus[$key];
		else return self::$goodStatus;
	}
	
	/*
	주말 처리용 weekendarr 반환 - 추가 시 해당 변수에 항목 추가
	*/
	static function _weekendVals($key){	
		return self::$weekendarr;
	}
	
	/**
	예약 관련 상태 코드에 해당하는 텍스트 반환
	매칭 정보가 없을 경우 전체 배열 반환
	*/
	static function _bookingStatus($key){
		if(!_empty($key)) return self::$bookingStatus[$key];
		else return self::$bookingStatus;
	}
	
	/**
	지역 타입키에 따른 텍스트 반환
	*/
	static function locationType($type){
		return self::$rentLocationType[$type];
	}
	
	/**
	info 변수 사용해 해당 항목의 값을 반환 - 사용 안함.
	*/
	public function _info(){
		return $this->info;
	}
	
	// 클레스 초기화 - singleton 처리를 위해서 별도 생성용 함수 사용
	static public function _init(){
		if(is_null(self::$conn)) self::$conn = get_db_conn();
		if(gettype(self::$conn) != 'resource' || get_resource_type(self::$conn) != 'mysql link') exit('데이터 베이스 커넥션 오류');
		self::cancelAuto(); // 예약 주문 자동 취소 처리 - 이후 자동화 에서 처리 필요
	}
	
	/**
	2015.07월 말~ 08월 초 사이 요청사항에 따른 옵션 가격 부분 설정 변경에 따라 가격 항목은 24시간 기준 가격만 가지며 추가 시간 설정 가능한 상품에 대해서는 기준 가격에서 연산 처리
	24시간은 기본 가격이 되고 추가 12시간 당 24시간 가격의 70% 그리고 추가  1시간당 은 24시간 가격의 1/20 으로 계산
	@param $normalprice - 24시간 기준 가격
	@param $type - 구분 12 : 12시간 추가 , 1: 1시간 추가 그외는 그냥 기본 가격 처리
	@return int 연산 가격
	*/	
	static public function calcPriceByNormal($normalprice,$type=NULL){
		$cut = 10;
		$price = $normalprice;
		if(_isInt($normalprice)){
			switch($type){
				case '12':
					$price = intval(round($normalprice*0.7)/$cut)*$cut;
					break;
				case '1':
					$price = intval(round($normalprice/20)/$cut)*$cut;
					break;
				default:
					break;
			}
		}
		return $price;
	}
	
	/**
	상품 정보 로드
	@param String || int  $codeoridx - 상품고유 식별 코드 (18자리) 또는 고유 식별 번호 (int tblproduct.pridx)
	@return mixed 해당 상품의 정보 및 예약 항목 옵션 가격정보등 전체 정보를 포함한 다차원 배열 반환
	*/
	static public function &read($codeoridx){
		global $_ShopInfo;
		$pridx = 0;
		$product = NULL;
		if(preg_match('/^[0-9]{18}$/',$codeoridx) && isset(self::$existsPcode[$codeoridx])) $pridx = self::$existsPcode[$codeoridx];
		else if(_isInt($codeoridx) && isset(self::$existsPridx[$codeoridx])) $pridx = $codeoridx;
		else{			
			if(preg_match('/^[0-9]{18}$/',$codeoridx)){ // 상품 고유코드(18) 일 경우
				$sql = "select p.productcode,p.productname,p.sellprice as prdprice,p.vender,p.today_reserve,p.deli_type,r.* from tblproduct p left join rent_product r using(pridx) where p.productcode='".$codeoridx."' and p.rental='2' limit 1";
				if(false === $res = mysql_query($sql,self::$conn)) exit(mysql_error());	
			}			
			
			if(!$res && _isInt($codeoridx)){ // 상품 고유 식별 번호 일 경우
				$sql = "select p.productcode,p.productname,p.sellprice as prdprice,p.vender,p.today_reserve,p.deli_type,r.* from tblproduct p left join rent_product r using(pridx) where p.pridx='".$codeoridx."' and p.rental='2' limit 1";
				if(false === $res = mysql_query($sql,self::$conn)) exit(mysql_error());
			}
		
			if($res && mysql_num_rows($res)){
				$product = mysql_fetch_assoc($res);
				// 그룹별 할인 정보
				$product['gdiscount'] = getMygroupDiscount($product['productcode']);				
			//	_pr($product['gdiscount']);
				// 옵션 정보
				$product['options'] = self::getoptions($product['pridx']); 
				foreach($product['options'] as $idx=>$optv){
					$product['options'][$idx]['halfPrice'] = self::calcPriceByNormal($optv['nomalPrice'],'12');
				}
				// 예약 정보 관련 변수 초기화
				$product['schedule']=$product['optschedulearray'] = array();
				$product['scheduleRange'] = array();
				
				// 지역정보
				if(!isset(self::$locations[$product['location']])) self::$locations += self::getlocations(array('location'=>$product['location']));								
				$product['locationinfo'] = &self::$locations[$product['location']];
				
								
				self::$existsPridx[$product['pridx']] = $product;
				self::$existsPcode[$product['productcode']] = $product['pridx'];
				
				
				// 카테고리에 할당된 정보 조회:gura - 입점업체개별설정이 있는경우
				self::getVenderRent($product['vender'],$product['pridx'],substr($product['productcode'],0,12)); 
				self::$existsPridx[$product['pridx']]['codeinfo'] = &self::$codeInfos[substr($product['productcode'],0,12)];
				
				if(!isset(self::$existsPridx[$product['pridx']]['codeinfo'])){
					if(!isset(self::$codeInfos[substr($product['productcode'],0,12)])) self::getCodeInfo(substr($product['productcode'],0,12)); 
					self::$existsPridx[$product['pridx']]['codeinfo'] = &self::$codeInfos[substr($product['productcode'],0,12)];
				}
						
				// 옵션 idx 조회용 link
				self::$existsPridx[$product['pridx']]['optkeys'] = array();
				if(_array(self::$existsPridx[$product['pridx']]['options'])){	
					/*성수기 사용안함인경우 
					foreach(self::$existsPridx[$product['pridx']]['options'] as $idx=>$oinfo){
						if(self::$codeInfos[substr($product['productcode'],0,12)]['useseason'] != '1'){ 
							// self::$existsPridx[$product['pridx']]['options'][$idx]['busySeason'] = self::$existsPridx[$product['pridx']]['options'][$idx]['semiBusySeason']= self::$existsPridx[$product['pridx']]['options'][$idx]['holidaySeason']  = 0;
							self::$existsPridx[$product['pridx']]['options'][$idx]['busySeason'] = self::$existsPridx[$product['pridx']]['options'][$idx]['busyHolidaySeason'] = self::$existsPridx[$product['pridx']]['options'][$idx]['semiBusySeason'] = self::$existsPridx[$product['pridx']]['options'][$idx]['semiBusyHolidaySeason'] = self::$existsPridx[$product['pridx']]['options'][$idx]['holidaySeason']= 0;
						}
					}		
					*/
				}
				
				$pridx = $product['pridx'];				
			}
		}	
		
		if(isset(self::$existsPridx[$pridx])) return self::$existsPridx[$pridx];
		return NULL;
	}
	
	/**
	카테고리 확장 정보 호출
	@param string $code 대상 카테고리 코드 (12자리)
	@param bool $forcereload 캐슁 정보 가 있을 경우 강제로 초기화 여부 (기본 flase - 호출 정보가 있을 경우 그냥 사용함)
	@return array 확장 정보를 가진 연관 배열 반환
	*/
	static public function getCodeInfo($code,$forcereload=false){
		if(preg_match('/^([0-9]{12})[0-9]*$/',$code,$mat)){
			$code = $mat[1];
			if(!isset(self::$codeInfos[$code]) || $forcereload === true){
				self::$codeInfos[$code] = array();
				$sql = "select * from code_rent where code='".$code."' limit 1";
				if(false !== $res = mysql_query($sql,self::$conn)){
					if(mysql_num_rows($res))  self::$codeInfos[$code] = mysql_fetch_assoc($res);
				}
			}
			return self::$codeInfos[$code];
		}
		return NULL;
	}


	static public function getVenderRent($vender,$pridx,$code,$forcereload=false){
		
		if(preg_match('/^([0-9]{12})[0-9]*$/',$code,$mat)){
//			$code = $mat[1];
			$sql_ = "SELECT * FROM vender_rent WHERE vender='".$vender."' and pridx='".$pridx."'";
			if(false !== $res_ = mysql_query($sql_,get_db_conn())){
				if(mysql_num_rows($res_)){
					$where = " and pridx='".$pridx."'";
				}else{
					$where = " and pridx='0'";
				}
			}else{
				$where = " and pridx='0'";
			}
			
			if(!isset(self::$codeInfos[$code]) || $forcereload === true){
				self::$codeInfos[$code] = array();
				$sql = "select * from vender_rent where vender='".$vender."' ".$where." limit 1";

				if(false !== $res = mysql_query($sql,self::$conn)){
					if(mysql_num_rows($res))  self::$codeInfos[$code] = mysql_fetch_assoc($res);
				}
			}
			return self::$codeInfos[$code];
		}
		return NULL;
	}
	
	
	/**
	상품별 옵션 정보 호출
	@param int pridx (tblproduct.pridx) 상품 고유 식별 번호
	@param bool $idxiskey 옵션 idx 를 키로 가지는 연관 배열로의 반환 여부 ( 기본 true 반환 배열의 key 가 옵션의 고유 식별 번호와 같다)
	@return array 옵션 정보를 가지는 다차원 연관 배열 반환 (idxiskey 의 값에 따라 연관 배열의 key 와 idx 간 상관 관계등이 결정됨)
	*/
	static public function getoptions($pridx,$idxiskey=true){
		$return = array();
		if(_isInt($pridx)){
			//$sql = "select * from rent_product_option where pridx='".$pridx."' order by grade asc ";//grade desc->asc 변경(고객요청)
			$sql = "select * from rent_product_option where pridx='".$pridx."' order by idx asc ";
			if(false !== $res = mysql_query($sql,self::$conn)){
				if(mysql_num_rows($res)){
					while($row = mysql_fetch_assoc($res)){
						if($idxiskey) $return[$row['idx']]= $row;
						else array_push($return,$row);
					}
				}
			}
		}		
		return $return;
	}
	

	/**
	지역 정보 가져 오기
	@param array 검색 대상 연관 배열 ( key : 컬럼, value : 조회값 ) ex : array('id'=>'getmall')  일 경우 where 의 내용은 `id`='getmall' 
	@return array 조건에 해당하는 지역 정보 연관 배열 반환 반환 배열의 1차 key 가 location의 고유 식별 번호와 동일
	*/
	static public function getlocations($value){ 
		$return = array();
		$sql = "SELECT * FROM `rent_location` ";
		if(_array($value)){
			$where = array();
			foreach ($value as $k => $v){
				if(!_empty($v)) array_push ($where, "`" ._escape($k,false)."` = "._escape($v));
			}
		}
		$where = (_array($where))?" WHERE " . implode (" AND ", $where):'';
		$sql .=$where." ORDER BY `location` ASC";
		if(false !== $res = mysql_query($sql,self::$conn)){
			if(mysql_num_rows($res)){
				while($location =  mysql_fetch_assoc($res) ) {
					$return[$location['location']] = $location;
				}
			}
		}
		return $return;
	}
	
	/**
	예약 일정 조회
	@param 코드 내부 참조
	@return array 예약 일정 및 시간에 따른 수량 등에 관한 복합 연관 배열 반환
	*/
	static public function schedule(){
		$args = func_get_args();
		
		$return = array('err'=>'','options'=>array(),'rangestamp'=>array(),'timegap'=>24,'schedule'=>array(),'optschedule'=>array());
		
		// 첫번째 항목은 대상 상품식별 번호 또는 코드
		if(_empty($args[0]))  return array('err'=>'조회 대상이 지정 되지 않았습니다.'); // 오류
		$codeoridx = $args[0];
		
		// 두번째 항목은 조회 기간
		if(_empty($args[1]))  return array('err'=>'조회 기간 지정 오류'); // 오류
		$start = $args[1];		
		
		// 3번째 항목이 있을 경우
		if(isset($args[2])){
			if(!_empty($args[2]) && is_string($args[2])){ // 문자열 일 경우는 종료일로 처리
				$end = $args[2];				
				if(isset($args[3])){ // 이경우 이후 항목은 옵션 식별 번호 또는 강제 초기화 관련 
					if(is_bool($args[3])) $forcereload = $args[3]; // boolean 이면 강제 초기화
					else if(_array($args[3])) $optidxs = $args[3]; // 배열 이면 옵션 고유 식별 번호
				}	
			}else{ // 종료일 설정을 안하고 (3번째 항목이 숫자가 아님) 이후 설정 전달
				$end = $start; // 시작일 과 종료일을 동일 하게 설정
				if(is_bool($args[3])) $forcereload = $args[2];// boolean 이면 강제 초기화
				else if(_array($args[2])) $optidxs = $args[2];// 배열 이면 옵션 고유 식별 번호
			}
		}
		
		if($forcereload !== true) $forcereload = false;		
		
		$pinfo = self::read($codeoridx); // 상품 정보 호출 ( 메모리에 남겨 두기때문에 있을 경우 DB 중복 호출 하지 않음
		
		if(!$pinfo) return array('err'=>'렌탈 상품이 아닙니다.-sc'); // 오류		
		if($pinfo['codeinfo']['pricetype'] == 'checkout'){
			//$start = substr($start,0,10).' 14:00:00';
			//$end = substr($end,0,10).' 11:00:00';
			//$start = substr($start,0,10).' '.$pinfo['codeinfo']['checkin_time'].':00:00';
			//$end = substr($end,0,10).' '.$pinfo['codeinfo']['checkout_time'].':00:00';
		}
		
		if($pinfo['codeinfo']['pricetype']!='long'){
			$stamp = self::getTimeRange($pinfo['codeinfo']['pricetype']=='time'?1:24,$start,$end); // 시간 범위 파싱 처리 - 시작 시간과 종료 시간 및 카테고리 설정 등에 따라 자동 처리 되어야 하는 부분이 있어서 함수로 처리
			
			if(!_empty($stamp['err'])) return $stamp;
			$return = array_merge($return,$stamp);
			if(_empty($return['rangestamp'][0]) || _empty($return['rangestamp'][0])) return array('err'=>'기간 연산 오류');
		}
		$where = array("p.pridx='".$pinfo['pridx']."'");
		//array_push($where,"IF( s.status = 'BR', s.regDate >= date_add(now(), interval -".self::$BR_limit." hour) AND s.status = 'BR', s.status IN ('BO',  'BI',  'OT',  'RP','NN') )	");

		$allkeys = array_keys($pinfo['options']);
	
		if(_array($optidxs)){
			for($i=count($opkeys)-1;$i>=0;$i--) if(!_isInt($optidxs[$i])) unset($optidxs[$i]);
			if(_array($optidxs)) array_push($where," o.idx in ('".implode("','",$optidxs)."')");			
			$opkeys = $optidxs;
		}else{
			$opkeys = $allkeys;
		}
	
		for($st = $return['rangestamp'][0];$st <= $return['rangestamp'][1];$st+= $return['timegap']*3600){
	
			$key = date('Y-m-d'.(($return['timegap'] == 1)?' H':''),$st);
			
			if($forcereload || !isset(self::$existsPridx[$pinfo['pridx']]['schedule'][$key])){
				self::$existsPridx[$pinfo['pridx']]['schedule'][$key] = array_combine($allkeys,array_fill(0,count($allkeys),0));
			}else{
				foreach($opkeys as $optidx){
					if($forcereload && isset(self::$existsPridx[$pinfo['pridx']]['schedule'][$key][$optidx])) self::$existsPridx[$pinfo['pridx']]['schedule'][$key][$optidx] = 0;					
				}
			}
				
			foreach(self::$existsPridx[$pinfo['pridx']]['schedule'][$key] as $opidx=>$cnt){
				if(!isset(self::$existsPridx[$pinfo['pridx']]['optschedule'][$opidx][$key])) self::$existsPridx[$pinfo['pridx']]['optschedule'][$opidx][$key] = &self::$existsPridx[$pinfo['pridx']]['schedule'][$key][$opidx];
			}
			
			foreach($opkeys as $optidx){// sync
				$return['schedule'][$key][$optidx] = &self::$existsPridx[$pinfo['pridx']]['schedule'][$key][$optidx];
				$return['options'][$optidx] = &self::$existsPridx[$pinfo['pridx']]['options'][$optidx]; 
				$return['optschedule'][$optidx][$key] = &$return['schedule'][$key][$optidx]; 
			}				
		}
		

		if(_array($where)){			
			//array_push($where,"s.`start` <= '".date('Y-m-d H:i:s',$return['rangestamp'][1])."' AND s.`end` >= '".date('Y-m-d H:i:s',strtotime('+'.self::$skipendtime.' hour',$return['rangestamp'][0]))."'");
			array_push($where,"s.`start` <= '".date('Y-m-d H:i:s',$return['rangestamp'][1])."' AND s.`end` >= '".date('Y-m-d H:i:s',$return['rangestamp'][0])."'");
			$sql = "select s.* from rent_schedule s inner join rent_product_option o on o.idx=s.optidx inner join tblproduct p on (p.pridx=o.pridx) ";	
			$sql .= " where	".implode(' and ',$where)." order by s.start,s.end";	
//return array('err'=>$sql);
			if(false === $res = mysql_query($sql,self::$conn)) return array('err'=>'DB 질의 오류');// 오류
			if(mysql_num_rows($res)){				
				while($row = mysql_fetch_assoc($res)){			
					$sst = _strtotime($row['start']);				
					$set = _strtotime($row['end'],true);	
					
					if(_isInt($row['optidx'])) {
					
						for($st = $return['rangestamp'][0];$st <= $return['rangestamp'][1];$st+= $return['timegap']*3600){						
							$key = date('Y-m-d'.(($return['timegap'] == 1)?' H':''),$st);
							if($args[3]=="cal"){//렌탈현황보기(달력)인 경우
								if($pinfo['codeinfo']['pricetype'] == 'day'){
									$sst = _strtotime(date('Y-m-d 00',$sst));
								}
								/*
								if($st < $sst || $st > $set || (!$forcereload && isset(self::$existsPridx[$pinfo['pridx']]['schedule'][$key][$row['optidx']]) && self::$existsPridx[$pinfo['pridx']]['schedule'][$key][$row['optidx']]>0)) continue;
								}
								*/
								if($st < $sst || $st > $set) continue;
							}else{
								/*
								if(($st < $sst && $st > $set) || (!$forcereload && isset(self::$existsPridx[$pinfo['pridx']]['schedule'][$key][$row['optidx']]) && self::$existsPridx[$pinfo['pridx']]['schedule'][$key][$row['optidx']]>0)) continue;
								*/
								if($st < $sst && $st > $set) continue;
							}
							self::$existsPridx[$pinfo['pridx']]['schedule'][$key][$row['optidx']]+=$row['quantity'];
							
						}
						
					}			
				}	
			}
		}
		return $return;		
	}
	
	
	
	
	/**
	입력 기간중 시즌 또는 휴일에 속하는 날짜 확인
	@param int $pridx 상품 고유 식별 번호
	@param string	$start 조회 기간 시작일
	@param string	$end 조회 기간 종료일
	@return array	조회 기간내 휴일 구분에 따른 일자 연관 배열 반환
	*/	
	static public function checkSeason($pridx,$start,$end,$vender){		
		$where = array();
		// busy => 성수기, semi => 준성수기 , holiday => 휴일 , weekend => 주말
		$return= array('busy'=>array(),'semi'=>array(),'holiday'=>array(),'weekend'=>array());
		
		$pinfo = self::read($pridx);
		
		if($pinfo){
			if(!_empty($start)) $startstamp =_strtotime($start);
			if(!_empty($end)) $endstamp = _strtotime($end,true);
			else $endstamp = _strtotime($start,true);
	
			if((_empty($startstamp) || _empty($endstamp)) || $startstamp >= $endstamp) return $return;
			
			
			$chkst = strtotime(date('Y-m-d',$startstamp));
			$chked = strtotime(date('Y-m-d',$endstamp));
			if($pinfo['codeinfo']['useseason'] == '1'){
				array_push($where,"`start` <= '".date('Y-m-d',$endstamp)."' AND `end` >= '".date('Y-m-d',$startstamp)."'");				
		  
				//성수기 : 입점업체 설정이 있을 경우 by gura
				$sql_ = "select * from vender_season_range where vender='".$vender."' and pridx='".$pridx."'";
				if(false !== $res_ = mysql_query($sql_,self::$conn)){
					if(mysql_num_rows($res_)){
						$vender_where = " and pridx='".$pridx."'";
					}else{
						$vender_where = " and pridx='0'";
					}
				}else{
					$vender_where = "and pridx='0'";
				}	

				$sql = "select * from vender_season_range where vender='".$vender."' ".$vender_where." and ".implode(' and ',$where)." order by start asc";	
				if(false !== $res = mysql_query($sql,self::$conn)){
					if(mysql_num_rows($res)){
					  
						while($row = mysql_fetch_assoc($res)){
							$loopst = max(strtotime($row['start']),$chkst);
							$looped = min(strtotime($row['end']),$chked);
							for($c = $loopst;$c<=$looped;$c+=24*3600){
								$date = date('Y-m-d',$c);
								if(!in_array($date,$return[$row['type']])) array_push($return[$row['type']],$date);
							}
						}
				  
					}else{
					
						$sql = "select * from season_range where code='".$pinfo['codeinfo']['code']."' and ".implode(' and ',$where)." order by start asc";	
			  
						if(false !== $res = mysql_query($sql,self::$conn)){
							if(mysql_num_rows($res)){
							  
								while($row = mysql_fetch_assoc($res)){
									$loopst = max(strtotime($row['start']),$chkst);
									$looped = min(strtotime($row['end']),$chked);
									for($c = $loopst;$c<=$looped;$c+=24*3600){
										$date = date('Y-m-d',$c);
										if(!in_array($date,$return[$row['type']])) array_push($return[$row['type']],$date);
									}
								}
							}
						}
					}

				}
			  
				$sql_ = "select * from vender_holiday_list where vender='".$vender."' and pridx='".$pridx."'";
				if(false !== $res_ = mysql_query($sql_,self::$conn)){
					if(mysql_num_rows($res_)){
						$vender_where = " and pridx='".$pridx."'";
					}else{
						$vender_where = " and pridx='0'";
					}
				}else{
					$vender_where = " and pridx='0'";
				}	

			  $sql = "select * from vender_holiday_list where (vender='".$vender."' ".$vender_where.") and (year is null or (year >='".date('Y',$startstamp)."' and year <= '".date('Y',$endstamp)."')) and date >= '".date('md',$startstamp)."' and date <= '".date('md',$endstamp)."' and date regexp '^[0-9]+$' ";
			  if(false !== $res = mysql_query($sql,self::$conn)){
				  if(mysql_num_rows($res)){
					  while($row = mysql_fetch_assoc($res)){						
						  if(!_empty($row['year'])) $return['holiday'][$row['year'].'-'.substr($row['date'],0,2).'-'.substr($row['date'],2,2)] = $row['title'];
						  else{
							  for($i=intval(date('Y',$startstamp));$i<=intval(date('Y',$endstamp));$i++){
								  $return['holiday'][$i.'-'.substr($row['date'],0,2).'-'.substr($row['date'],2,2)] = $row['title'];
							  }
						  }
							  
					  }
				  }
			  }else{

				  $sql = "select * from holiday_list where (code='000000000000' or code='".$pinfo['codeinfo']['code']."') and (year is null or (year >='".date('Y',$startstamp)."' and year <= '".date('Y',$endstamp)."')) and date >= '".date('md',$startstamp)."' and date <= '".date('md',$endstamp)."' and date regexp '^[0-9]+$' ";
				  
				  if(false !== $res = mysql_query($sql,self::$conn)){
					  if(mysql_num_rows($res)){
						  while($row = mysql_fetch_assoc($res)){						
							  if(!_empty($row['year'])) $return['holiday'][$row['year'].'-'.substr($row['date'],0,2).'-'.substr($row['date'],2,2)] = $row['title'];
							  else{
								  for($i=intval(date('Y',$startstamp));$i<=intval(date('Y',$endstamp));$i++){
									  $return['holiday'][$i.'-'.substr($row['date'],0,2).'-'.substr($row['date'],2,2)] = $row['title'];
								  }
							  }
								  
						  }
					  }
				  }
			  }
			}
			
			// 주말 요금
			$weekend = array();
			
			$sql_ = "select * from vender_holiday_list where vender='".$vender."' and pridx='".$pridx."'";
			if(false !== $res_ = mysql_query($sql_,self::$conn)){
				if(mysql_num_rows($res_)){
					$vender_where = " and pridx='".$pridx."'";
				}else{
					$vender_where = " and pridx='0'";
				}
			}else{
				$vender_where = " and pridx='0'";
			}	

			$sql = "select * from vender_holiday_list where vender='".$vender."' ".$vender_where." and title in ('".implode("','",self::$weekendarr)."')";
			if(false !== $res = mysql_query($sql,self::$conn)){
	
				if(mysql_num_rows($res)){
					while($item = mysql_fetch_assoc($res)) if($item['date'] == 'ok') array_push($weekend,$item['title']);
				}
			}

			//입점업체 주말정보가 없는경우 다시 체크
			if(!_array($weekend)){
			
				//if(!_array($weekend)){
					$sql = "select * from holiday_list where code='".$pinfo['codeinfo']['code']."' and title in ('".implode("','",self::$weekendarr)."')";
				
					if(false !== $res = mysql_query($sql,self::$conn)){
						if(mysql_num_rows($res)){
							while($item = mysql_fetch_assoc($res)) if($item['date'] == 'ok') array_push($weekend,$item['title']);
						}
					}
					
					if(!_array($weekend)){
						$sql = "select * from holiday_list where code='000000000000' and title in ('".implode("','",self::$weekendarr)."')";
						
						if(false !== $res =  mysql_query($sql,self::$conn)){
							if(mysql_num_rows($res)){
								while($item = mysql_fetch_assoc($res)) if($item['date'] == 'ok') array_push($weekend,$item['title']);
							}
						}
					}
				//}		
			}


			if(_array($weekend)){
				for($c = $chkst;$c<=$chked;$c+=24*3600){
					$dkey = strtolower(date('D',$c));					
					if(in_array($dkey,$weekend)){
						$datestr = date('Y-m-d',$c);
						if(_empty($return['holiday'][$datestr])) $return['holiday'][$datestr] = '주말요금';					
						array_push($return['weekend'],$datestr);
					}
				}	
			}

			//$return = $sql;

			/*

			$sql = "select * from holiday_list where code='".$pinfo['codeinfo']['code']."' and title in ('".implode("','",self::$weekendarr)."')";
			
			if(false !== $res = mysql_query($sql,self::$conn)){
				if(mysql_num_rows($res)){
					while($item = mysql_fetch_assoc($res)) if($item['date'] == 'ok') array_push($weekend,$item['title']);
				}
			}
			
			if(!_array($weekend)){
				$sql = "select * from holiday_list where code='000000000000' and title in ('".implode("','",self::$weekendarr)."')";
				
				if(false !== $res =  mysql_query($sql,self::$conn)){
					if(mysql_num_rows($res)){
						while($item = mysql_fetch_assoc($res)) if($item['date'] == 'ok') array_push($weekend,$item['title']);
					}
				}
			}			
			
			if(_array($weekend)){
				for($c = $chkst;$c<=$chked;$c+=24*3600){
					$dkey = strtolower(date('D',$c));					
					if(in_array($dkey,$weekend)){
						$datestr = date('Y-m-d',$c);
						if(_empty($return['holiday'][$datestr])) $return['holiday'][$datestr] = '주말요금';					
						array_push($return['weekend'],$datestr);
					}
				}	
			}	

			*/
			
		}		
		return $return;
	}
	
	/**
	예약(주문)에 대한 가격 산출
	@param int pridx 상품 고유 식별 번호 tblproduct.pridx
	@param string options 옵션 고유 번호 및 수량 관련 조합 문자열
	@param string start 렌탈 시작일
	@param string end	렌탈 종료일
	
	@return array 기간 및 금액 오류 정도 등을 담은 복합연관 배열 반환
	*/
	static public function solvPrice($pridx,$options,$start,$end,$vender){
		$return = array('err'=>'','totalprice'=>0,'discprice'=>0,'msg'=>$err,'range'=>$schedule['rangestamp'],'timegap'=>$schedule['timegap'],'discountmsg'=>'','addprice'=>'');		
		$pinfo = self::read($pridx);
		if(!$pinfo) return array('err'=>'렌탈 상품이 아닙니다.-sp');

		// 옵션 조합 문자열 파싱 해서 전달 정보 가공
		if(is_string($options) && preg_match('/|[0-9]+/',$options))  $options =  parseRentRequestOption($options);		
		if(!_array($options)) return array('err'=>'옵션 전달 오류');
		
		if($pinfo['codeinfo']['pricetype']=="long"){

			foreach($options as $optidx => $optCnt){
				$sql = "select * from rent_product_option where idx='".$optidx."' and pridx='".$pridx."' ";
				
				if(false === $res = mysql_query($sql,self::$conn)) return array('err'=>'DB 질의 오류');// 오류
				if(mysql_num_rows($res)){
					while($row = mysql_fetch_assoc($res)){
						if(_isInt($row['idx'])) {							
							if($row['optionPay']=="일시납"){
								$tmpprice = $row['nomalPrice']+$row['prepay'];
							}else{
								$tmpprice = ($row['nomalPrice']/$row['optionName'])+$row['prepay'];
							}
						}
						$totalprice = $row['nomalPrice'];
					}
				}
				// 회원 할인있을 경우 적용
				if(_array($pinfo['gdiscount'])){
					if($pinfo['gdiscount']['discount'] < 1){
						$return['totalprice'] += round($totalprice*(1-$pinfo['gdiscount']['discount']))*$optCnt;
						$return['pricetxt'] += round($tmpprice*(1-$pinfo['gdiscount']['discount']))*$optCnt;
					}else{
						$return['totalprice'] += ($totalprice-$pinfo['gdiscount']['discount'])*$optCnt;
						$return['pricetxt'] += ($tmpprice-$pinfo['gdiscount']['discount'])*$optCnt;
					}
				}else{
					$return['totalprice'] += $totalprice*$optCnt;	
					$return['pricetxt'] += $tmpprice*$optCnt;	
				}

				//return array('err'=>$tmpprice."/".$optCnt);
			}
						
			$realprice = $tmpprice;
			$basicprice = $tmpprice;

		}else{

			// 스케줄 정보 확인	
			$schedule = rentProduct::schedule($pinfo['pridx'],$start,$end,array_keys($options));				
			
			$return['range'] = $schedule['rangestamp'];
			$return['timegap'] = $schedule['timegap'];
			
			$start = date('Y-m-d H:i:s',$return['range'][0]);
			$end = date('Y-m-d H:i:s',$return['range'][1]);
			
			// schedule 를 바탕으로 렌탈 가능 여부 조회
			$check = self::checkRentable($options,$schedule,true);
	//return array('err'=>$check['err']);
			

			foreach($options as $idx=>$cnt){	
				if(isset($schedule['options'][$idx])){
					$opmaxcnt = $schedule['options'][$idx]['productCount'];	
					
					foreach($schedule['optschedule'][$idx] as $date=>$rentcnt){	
						$return["opt_cnt"][$idx] = max($opmaxcnt-$rentcnt,0);
						
					}
				}else{
					$return['err'] = '옵션 고유식별 번호가 올바르지 않습니다.';
				}
			}

			if(!_empty($check['err'])) return array('err'=>$check['err']);
			else if(_array($check['disable'])){
				foreach($check['disable'] as $date=>$ablecnt){
					//$return['err'] = $date.' 예약 불가('.$ablecnt.' 건예약가능)';
					//return array('err'=>$date.' 예약 불가('.$ablecnt.' 건예약가능)');
					//break;
				}
			}

//return array('err'=>$pinfo['codeinfo']['useseason']);
		
			if($pinfo['codeinfo']['useseason'] == '1'){ // 시즌 사용일 경우 시즌 기간 조회
				$season = rentproduct::checkSeason($pridx,$start,$end,$vender);
			}
			$return['diff'] = $diff = datediff_rent($end,$start);
			if($pinfo['codeinfo']['pricetype'] =='checkout'){
				$diff['day']+=1;
				$return['diff']['day'] = $diff['day'];
				$return['diff']['hour'] = 0;
			}else if($pinfo['codeinfo']['pricetype'] =='time'){
				if($diff['day']*24+$diff['hour'] <$pinfo['codeinfo']['base_time']) return array('err'=>'최소 렌탈 시간은 '.$pinfo['codeinfo']['base_time'].'시간 입니다.');
				foreach($schedule['options'] as $optkey=>$val){
					if(!_isInt($val['nomalPrice'])) return array('err'=>'가격 설정 오류');

					$schedule['options'][$optkey]['halfPrice'] = intval(round($val['nomalPrice']*($pinfo['codeinfo']['halfday_percent']/100)));
					$schedule['options'][$optkey]['timePrice'] = intval(round($val['nomalPrice']*($pinfo['codeinfo']['time_percent']/100)));
				}
			}else{
				foreach($schedule['options'] as $optkey=>$val){
					if(!_isInt($val['nomalPrice'])) return array('err'=>'가격 설정 오류');
				}
			}

			
			foreach($options as $optKey => $optCnt ){
				$pricecnt = array('total'=>count($schedule['optschedule'][$optKey]),'busy'=>0,'semi'=>0,'holiday'=>0,'busyHoli'=>0,'semiHoli'=>0);			
				$sdateCnt = array('busy'=>0,'semi'=>0,'holiday'=>0,'busyHoli'=>0,'semiHoli'=>0,'normal'=>0);
				$edateCnt = array('busy'=>0,'semi'=>0,'holiday'=>0,'busyHoli'=>0,'semiHoli'=>0,'normal'=>0);

				$sdate = ""; $edate=""; 
				foreach($schedule['optschedule'][$optKey] as $datekey=>$scnt){
					
					if(_array($season)){
						$date = substr($datekey,0,10);

						if(!_empty($season['holiday'][$date]) && in_array($date,$season['busy'])) $pricecnt['busyHoli']++;						
						else if(_empty($season['holiday'][$date]) && in_array($date,$season['busy'])) $pricecnt['busy']++;
						else if(!_empty($season['holiday'][$date]) && in_array($date,$season['semi'])) $pricecnt['semiHoli']++;
						else if(_empty($season['holiday'][$date]) && in_array($date,$season['semi'])) $pricecnt['semi']++;
						else if(!_empty($season['holiday'][$date]) && !in_array($date,$season['semi']) && !in_array($date,$season['busy'])) $pricecnt['holiday']++;


						$sdate = substr($start,0,10);

						if(!_empty($season['holiday'][$sdate]) && in_array($sdate,$season['busy'])) $sdateCnt['busyHoli']++;						
						else if(_empty($season['holiday'][$sdate]) && in_array($sdate,$season['busy'])) $sdateCnt['busy']++;
						else if(!_empty($season['holiday'][$sdate]) && in_array($sdate,$season['semi'])) $sdateCnt['semiHoli']++;
						else if(_empty($season['holiday'][$sdate]) && in_array($sdate,$season['semi'])) $sdateCnt['semi']++;
						else if(!_empty($season['holiday'][$sdate]) && !in_array($sdate,$season['semi']) && !in_array($sdate,$season['busy'])) $sdateCnt['holiday']++;
						else $sdateCnt['normal']++;

						$edate = substr($end,0,10);

						if(!_empty($season['holiday'][$edate]) && in_array($edate,$season['busy'])) $edateCnt['busyHoli']++;						
						else if(_empty($season['holiday'][$edate]) && in_array($edate,$season['busy'])) $edateCnt['busy']++;
						else if(!_empty($season['holiday'][$edate]) && in_array($edate,$season['semi'])) $edateCnt['semiHoli']++;
						else if(_empty($season['holiday'][$edate]) && in_array($edate,$season['semi'])) $edateCnt['semi']++;
						else if(!_empty($season['holiday'][$edate]) && !in_array($edate,$season['semi']) && !in_array($edate,$season['busy'])) $edateCnt['holiday']++;
						else $edateCnt['normal']++;

					}
				}
				
				if($pinfo['codeinfo']['useseason'] == '0'){//시즌제사용안하는 경우
					$sdateCnt['normal'] = $edateCnt['normal'] = $pricecnt['total'];
				}

				$return['addprice'] = 0;
				/*************************** 1시간제인경우 ************************************/
				if($pinfo['codeinfo']['pricetype'] =='time'){
					
					$diff_day = $diff['day'];
					if($pinfo['multiOpt']==1){//복합옵션인 경우 시간초과금액 옵션가격으로 설정
						$schedule['options'][$optKey]['productTimeover_price'] = $schedule['options'][$optKey]['nomalPrice'];
					}

					$realprice = $schedule['options'][$optKey]['nomalPrice'];
					$realprice += floor(($pricecnt['total']-$pinfo['codeinfo']['base_time']) * $schedule['options'][$optKey]['productTimeover_price']);

					//return array('err'=>$pricecnt['total']);

					$tmpprice = $schedule['options'][$optKey]['nomalPrice'];
					if($schedule['options'][$optKey]['priceDiscP']>0){//일반가 할인이 있는 경우 추가시간당 금액도 같이 할인
						$schedule['options'][$optKey]['productTimeover_price'] = $schedule['options'][$optKey]['productTimeover_price']-($schedule['options'][$optKey]['productTimeover_price']*$schedule['options'][$optKey]['priceDiscP']/100);
					}

					//1시간제 :::: 성수기*주말공휴일
					if($pricecnt['busyHoli'] > 0){
						if($sdateCnt['busyHoli']>0){//첫 시작일이 성수기*주말공휴일인경우
							$tmpprice += floor($schedule['options'][$optKey]['nomalPrice']*$schedule['options'][$optKey]['busyHolidaySeason']/100);	
							$pricecnt_['busyHoli'] = $pricecnt['busyHoli']-$pinfo['codeinfo']['base_time'];
						}else{
							$pricecnt_['busyHoli'] = $pricecnt['busyHoli'];
						}

						$tmpprice += floor($pricecnt_['busyHoli'] * $schedule['options'][$optKey]['productTimeover_price']);
						$tmpprice += floor($pricecnt_['busyHoli'] * $schedule['options'][$optKey]['productTimeover_price']*$schedule['options'][$optKey]['busyHolidaySeason']/100);
			
						if($return['discountmsg']!=""){
							$return['discountmsg'] .= ",";
						}
						
						$return['discountmsg'] .= "성수기*주말공휴일 ".$schedule['options'][$optKey]['busyHolidaySeason']."% 할증";

						$return['addprice'] = $schedule['options'][$optKey]['busyHolidaySeason'];
					}

					//1시간제 :::: 성수기*평일
					if($pricecnt['busy'] > 0){
						if($sdateCnt['busy']>0){//첫 시작일이 성수기*평일인경우
							$tmpprice += floor($schedule['options'][$optKey]['nomalPrice']*$schedule['options'][$optKey]['busySeason']/100);
							$pricecnt_['busy'] = $pricecnt['busy']-$pinfo['codeinfo']['base_time'];
						}else{
							$pricecnt_['busy'] = $pricecnt['busy'];
						}

						$tmpprice += floor($pricecnt_['busy'] * $schedule['options'][$optKey]['productTimeover_price']);
						$tmpprice += floor($pricecnt_['busy'] * $schedule['options'][$optKey]['productTimeover_price']*$schedule['options'][$optKey]['busySeason']/100);
						
						if($return['discountmsg']!=""){
							$return['discountmsg'] .= ",";
						}
						$return['discountmsg'] .= "성수기*평일 ".$schedule['options'][$optKey]['busySeason']."% 할증";
						$return['addprice'] = $schedule['options'][$optKey]['busySeason'];
					}
					
					//1시간제 :::: 준성수기*주말공휴일
					if($pricecnt['semiHoli'] > 0){
						if($sdateCnt['semiHoli']>0){//첫 시작일이 준성수기*주말공휴일인경우
							$tmpprice += floor($schedule['options'][$optKey]['nomalPrice']*$schedule['options'][$optKey]['semiBusyHolidaySeason']/100);
							$pricecnt_['semiHoli'] = $pricecnt['semiHoli']-$pinfo['codeinfo']['base_time'];
						}else{
							$pricecnt_['semiHoli'] = $pricecnt['semiHoli'];
						}

						$tmpprice += floor($pricecnt_['semiHoli'] * $schedule['options'][$optKey]['productTimeover_price']);
						$tmpprice += floor($pricecnt_['semiHoli'] * $schedule['options'][$optKey]['productTimeover_price']*$schedule['options'][$optKey]['semiBusyHolidaySeason']/100);
					
						if($return['discountmsg']!=""){
							$return['discountmsg'] .= ",";
						}
						$return['discountmsg'] .= "준성수기*주말공휴일 ".$schedule['options'][$optKey]['semiBusyHolidaySeason']."% 할증";
						$return['addprice'] = $schedule['options'][$optKey]['semiBusyHolidaySeason'];
					}

					//1시간제 :::: 준성수기*평일
					if($pricecnt['semi'] > 0){
						if($sdateCnt['semi']>0){//첫 시작일이 준성수기*평일인경우
							$tmpprice += floor($schedule['options'][$optKey]['nomalPrice']*$schedule['options'][$optKey]['semiBusySeason']/100);
							$pricecnt_['semi'] = $pricecnt['semi']-$pinfo['codeinfo']['base_time'];
						}else{
							$pricecnt_['semi'] = $pricecnt['semi'];
						}

						$tmpprice += floor($pricecnt_['semi'] * $schedule['options'][$optKey]['productTimeover_price']);
						$tmpprice += floor($pricecnt_['semi'] * $schedule['options'][$optKey]['productTimeover_price']*$schedule['options'][$optKey]['semiBusySeason']/100);
						
						if($return['discountmsg']!=""){
							$return['discountmsg'] .= ",";
						}
						$return['discountmsg'] .= "준성수기*평일 ".$schedule['options'][$optKey]['semiBusySeason']."% 할증";
						$return['addprice'] = $schedule['options'][$optKey]['semiBusySeason'];
					}

					//1시간제 :::: 비성수기*주말공휴일
					if($pricecnt['holiday'] > 0){
						if($sdateCnt['holiday']>0){//첫 시작일이 주말인경우
							$tmpprice += floor($schedule['options'][$optKey]['nomalPrice']*$schedule['options'][$optKey]['holidaySeason']/100);	
							$pricecnt_['holiday'] = $pricecnt['holiday']-$pinfo['codeinfo']['base_time'];
						}else{
							$pricecnt_['holiday'] = $pricecnt['holiday'];
						}

						$tmpprice += floor($pricecnt_['holiday'] * $schedule['options'][$optKey]['productTimeover_price']);
						$tmpprice += floor($pricecnt_['holiday'] * $schedule['options'][$optKey]['productTimeover_price']*$schedule['options'][$optKey]['holidaySeason']/100);
						
						if($return['discountmsg']!=""){
							$return['discountmsg'] .= ",";
						}
						$return['discountmsg'] .= "비성수기*주말공휴일 ".$schedule['options'][$optKey]['holidaySeason']."% 할증";
						$return['addprice'] = $schedule['options'][$optKey]['holidaySeason'];
					}

					$restTime = $pricecnt['total']-$pricecnt['busy']-$pricecnt['busyHoli']-$pricecnt['semiHoli']-$pricecnt['semi']-$pricecnt['holiday'];	

					if($restTime>0){
						if($sdateCnt['normal']>0){//첫 시작일이 비수기*평일인경우	
							$restTime = $restTime-$pinfo['codeinfo']['base_time'];
						}
						$tmpprice += floor($restTime * $schedule['options'][$optKey]['productTimeover_price']);
						$return['discountmsg'] .= "";
					}

					//$basicprice = $schedule['options'][$optKey]['nomalPrice']*($diff_day+1)*24;
					$basicprice = $schedule['options'][$optKey]['nomalPrice'];
					$basicprice += floor($schedule['options'][$optKey]['productTimeover_price']*(($diff_day+1)*24-$pinfo['codeinfo']['base_time']));

				/********************************* 24시간제인경우 ****************************************/
				}else if($pinfo['codeinfo']['pricetype'] =='day'){
					//$tmpprice = $schedule['options'][$optKey]['nomalPrice'];
					
					//당일 12시간요금
					$pinfo['codeinfo']['halfday_percent'] = $schedule['options'][$optKey]['productHalfday_percent'];
					
					//초과시간요금
					if($pinfo['codeinfo']['oneday_ex']=="half"){
						$pinfo['codeinfo']['time_percent'] = $schedule['options'][$optKey]['productOverHalfTime_percent'];
						$pinfo['codeinfo']['time_price'] = $schedule['options'][$optKey]['productOverHalfTime_price'];
					}else if($pinfo['codeinfo']['oneday_ex']=="time"){
						$pinfo['codeinfo']['time_percent'] = $schedule['options'][$optKey]['productOverOneTime_percent'];
						$pinfo['codeinfo']['time_price'] = $schedule['options'][$optKey]['productOverOneTime_price'];
					}

					//24시간제 :::: 성수기*주말공휴일
					if($pricecnt['busyHoli'] > 0){
					
						if($sdateCnt['busyHoli']>0 && $pinfo['codeinfo']['oneday_ex']!="day"){//첫 시작일이 성수기*주말공휴일인경우
							$tmpprice += floor($schedule['options'][$optKey]['nomalPrice']*$schedule['options'][$optKey]['busyHolidaySeason']/100);
							//$pricecnt['busyHoli'] = $pricecnt['busyHoli'] - 1;	
						}
		
						if($edateCnt['busyHoli']>0 && $diff['hour']>0){ //마지막일인 경우 
						
							$tmpprice += $schedule['options'][$optKey]['nomalPrice']*($pricecnt['busyHoli']-1);
							$tmpprice += floor($schedule['options'][$optKey]['nomalPrice']*($pricecnt['busyHoli']-1)*$schedule['options'][$optKey]['busyHolidaySeason']/100);
							
							if($pinfo['codeinfo']['oneday_ex']=="time"){//[1일 초과시 과금 기준] 1시간 단위
								if($diff['day']>=1 && $diff['hour'] >=0){
									//$tmpprice2 = $schedule['options'][$optKey]['nomalPrice'] * (($pinfo['codeinfo']['time_percent']/100)*$diff['hour']);
									$tmpprice2 = $pinfo['codeinfo']['time_price']*$diff['hour'];
									$tmpprice += $tmpprice2+floor($tmpprice2*$schedule['options'][$optKey]['busyHolidaySeason']/100);
								}else if($pinfo['codeinfo']['halfday']=="Y" && $diff['day']<1 && $diff['hour'] <=12) {
									//$tmpprice2 = $schedule['options'][$optKey]['nomalPrice']*$pinfo['codeinfo']['halfday_percent']/100;
									$tmpprice2 = $schedule['options'][$optKey]['productHalfday_price'];
									$tmpprice += $tmpprice2+floor($tmpprice2*$schedule['options'][$optKey]['busyHolidaySeason']/100);
								}else {
									$tmpprice2 = $schedule['options'][$optKey]['nomalPrice'];
									$tmpprice += $tmpprice2+floor($tmpprice2*$schedule['options'][$optKey]['busyHolidaySeason']/100);
								}
							}else if($pinfo['codeinfo']['oneday_ex']=="half"){//[1일 초과시 과금 기준] 12시간 단위
								if($diff['day']>=1 && $diff['hour'] >=0){ 
									if($diff['hour']<=12) $hour_over = 1;
									else $hour_over =2;
									//$tmpprice2 = $schedule['options'][$optKey]['nomalPrice'] * (($pinfo['codeinfo']['time_percent']/100)*$hour_over);
									$tmpprice2 = $pinfo['codeinfo']['time_price']*$hour_over;
									$tmpprice += $tmpprice2+floor($tmpprice2*$schedule['options'][$optKey]['busyHolidaySeason']/100);
								}else if($pinfo['codeinfo']['halfday']=="Y" && $diff['day']<1 && $diff['hour'] <=12){ 
									//$tmpprice2 = $schedule['options'][$optKey]['nomalPrice']*$pinfo['codeinfo']['halfday_percent']/100;
									$tmpprice2 = $schedule['options'][$optKey]['productHalfday_price'];
									$tmpprice += $tmpprice2+floor($tmpprice2*$schedule['options'][$optKey]['busyHolidaySeason']/100);
								}else{
									$tmpprice2 = $schedule['options'][$optKey]['nomalPrice'];
									$tmpprice += $tmpprice2+floor($tmpprice2*$schedule['options'][$optKey]['busyHolidaySeason']/100);
								}
							}else{//[1일 초과시 과금 기준] 1일 단위
								if($pinfo['codeinfo']['halfday']=="Y" && $diff['day']<1 && $diff['hour'] <=12){
									//$tmpprice2 = $schedule['options'][$optKey]['nomalPrice']*$pinfo['codeinfo']['halfday_percent']/100;
									$tmpprice2 = $schedule['options'][$optKey]['productHalfday_price'];
									$tmpprice += $tmpprice2+floor($tmpprice2*$schedule['options'][$optKey]['busyHolidaySeason']/100);
								}else{
									$tmpprice2 = $schedule['options'][$optKey]['nomalPrice'];
									$tmpprice += $tmpprice2+floor($tmpprice2*$schedule['options'][$optKey]['busyHolidaySeason']/100);
								}
							}

						}else{
							$tmpprice += $schedule['options'][$optKey]['nomalPrice']*($pricecnt['busyHoli']);
							$tmpprice += floor($schedule['options'][$optKey]['nomalPrice']*$pricecnt['busyHoli']*$schedule['options'][$optKey]['busyHolidaySeason']/100);
						}

						if($sdateCnt['busyHoli']>0 && $pinfo['codeinfo']['oneday_ex']!="day"){//첫 시작일이 성수기*주말공휴일인경우
							$pricecnt['busyHoli'] = $pricecnt['busyHoli'] + 1;
						}

						if($return['discountmsg']!=""){
							$return['discountmsg'] .= ",";
						}
						if( $schedule['options'][$optKey]['busyHolidaySeason']!=0)
							$return['discountmsg'] .= "성수기*주말공휴일 ".$schedule['options'][$optKey]['busyHolidaySeason']."% 할증";
						
						$return['addprice'] = $schedule['options'][$optKey]['busyHolidaySeason'];
					}

					//24시간제 :::: 성수기*평일
					if($pricecnt['busy'] > 0){
						if($sdateCnt['busy']>0 && $pinfo['codeinfo']['oneday_ex']!="day"){//첫 시작일이 성수기*평일인경우
							$tmpprice += floor($schedule['options'][$optKey]['nomalPrice']*$schedule['options'][$optKey]['busySeason']/100);
							//$pricecnt['busy'] = $pricecnt['busy'] - 1;
						}

						if($edateCnt['busy']>0 && $diff['hour']>0){ //마지막일인 경우 
							$tmpprice += $schedule['options'][$optKey]['nomalPrice']*($pricecnt['busy']-1);
							$tmpprice += floor($schedule['options'][$optKey]['nomalPrice']*($pricecnt['busy']-1)*$schedule['options'][$optKey]['busySeason']/100);
							
							if($pinfo['codeinfo']['oneday_ex']=="time"){//[1일 초과시 과금 기준] 1시간 단위
								if($diff['day']>=1 && $diff['hour'] >=0){
									//$tmpprice2 = $schedule['options'][$optKey]['nomalPrice'] * (($pinfo['codeinfo']['time_percent']/100)*$diff['hour']);
									$tmpprice2 = $pinfo['codeinfo']['time_price']*$diff['hour'];
									$tmpprice += $tmpprice2+floor($tmpprice2*$schedule['options'][$optKey]['busySeason']/100);
								}else if($pinfo['codeinfo']['halfday']=="Y" && $diff['day']<1 && $diff['hour'] <=12) {
									//$tmpprice2 = $schedule['options'][$optKey]['nomalPrice']*$pinfo['codeinfo']['halfday_percent']/100;
									$tmpprice2 = $schedule['options'][$optKey]['productHalfday_price'];
									$tmpprice += $tmpprice2+floor($tmpprice2*$schedule['options'][$optKey]['busySeason']/100);
								}else {
									$tmpprice2 = $schedule['options'][$optKey]['nomalPrice'];
									$tmpprice += $tmpprice2+floor($tmpprice2*$schedule['options'][$optKey]['busySeason']/100);
								}
							}else if($pinfo['codeinfo']['oneday_ex']=="half"){//[1일 초과시 과금 기준] 12시간 단위
								if($diff['day']>=1 && $diff['hour'] >=0){ 
									if($diff['hour']<=12) $hour_over = 1;
									else $hour_over =2;
									//$tmpprice2 = $schedule['options'][$optKey]['nomalPrice'] * (($pinfo['codeinfo']['time_percent']/100)*$hour_over);
									$tmpprice2 = $pinfo['codeinfo']['time_price']*$hour_over;
									$tmpprice += $tmpprice2 + floor($tmpprice2*$schedule['options'][$optKey]['busySeason']/100);
								}else if($pinfo['codeinfo']['halfday']=="Y" && $diff['day']<1 && $diff['hour'] <=12){ 
									//$tmpprice2 = $schedule['options'][$optKey]['nomalPrice']*$pinfo['codeinfo']['halfday_percent']/100;
									$tmpprice2 = $schedule['options'][$optKey]['productHalfday_price'];
									$tmpprice += $tmpprice2+floor($tmpprice2*$schedule['options'][$optKey]['busySeason']/100);
								}else{
									$tmpprice2 = $schedule['options'][$optKey]['nomalPrice'];
									$tmpprice += $tmpprice2+floor($tmpprice2*$schedule['options'][$optKey]['busySeason']/100);
								}
							}else{//[1일 초과시 과금 기준] 1일 단위
								if($pinfo['codeinfo']['halfday']=="Y" && $diff['day']<1 && $diff['hour'] <=12){
									//$tmpprice2 = $schedule['options'][$optKey]['nomalPrice']*$pinfo['codeinfo']['halfday_percent']/100;
									$tmpprice2 = $schedule['options'][$optKey]['productHalfday_price'];
									$tmpprice += $tmpprice2+floor($tmpprice2*$schedule['options'][$optKey]['busySeason']/100);
								}else{
									$tmpprice2 = $schedule['options'][$optKey]['nomalPrice'];
									$tmpprice += $tmpprice2+floor($tmpprice2*$schedule['options'][$optKey]['busySeason']/100);
								}
							}
							
						}else{
							$tmpprice += $schedule['options'][$optKey]['nomalPrice']*($pricecnt['busy']);
							$tmpprice += floor($schedule['options'][$optKey]['nomalPrice']*($pricecnt['busy'])*$schedule['options'][$optKey]['busySeason']/100);
						}
						
						if($sdateCnt['busy']>0 && $pinfo['codeinfo']['oneday_ex']!="day"){//첫 시작일인경우
							$pricecnt['busy'] = $pricecnt['busy'] + 1;
						}
						if($return['discountmsg']!=""){
							$return['discountmsg'] .= ",";
						}
						if( $schedule['options'][$optKey]['busySeason']!=0)
							$return['discountmsg'] .= "성수기*평일 ".$schedule['options'][$optKey]['busySeason']."% 할증";

						$return['addprice'] = $schedule['options'][$optKey]['busySeason'];
					}
					
					//24시간제 :::: 준성수기*주말공휴일
					if($pricecnt['semiHoli'] > 0){

						if($sdateCnt['semiHoli']>0 && $pinfo['codeinfo']['oneday_ex']!="day"){//첫 시작일이 준성수기*주말공휴일인경우
							$tmpprice += floor($tmpprice*$schedule['options'][$optKey]['semiBusyHolidaySeason']/100);
							///$pricecnt['semiHoli'] = $pricecnt['semiHoli'] - 1;
						}

						if($edateCnt['semiHoli']>0 && $diff['hour']>0){ //마지막일인 경우 
							$tmpprice += $schedule['options'][$optKey]['nomalPrice']*($pricecnt['semiHoli']-1);
							$tmpprice += floor($schedule['options'][$optKey]['nomalPrice']*($pricecnt['semiHoli']-1)*$schedule['options'][$optKey]['semiBusyHolidaySeason']/100);
							if($pinfo['codeinfo']['oneday_ex']=="time"){//[1일 초과시 과금 기준] 1시간 단위
								//return array('err'=>"--".$diff['day']."/".$diff['hour']);
								if($diff['day']>=1 && $diff['hour'] >=0){
									//$tmpprice2 = $schedule['options'][$optKey]['nomalPrice'] * (($pinfo['codeinfo']['time_percent']/100)*$diff['hour']);
									$tmpprice2 = $pinfo['codeinfo']['time_price']*$diff['hour'];
									$tmpprice += $tmpprice2+floor($tmpprice2*$schedule['options'][$optKey]['semiBusyHolidaySeason']/100);
									
								}else if($pinfo['codeinfo']['halfday']=="Y" && $diff['day']<1 && $diff['hour'] <=12) {
									//$tmpprice2 = $schedule['options'][$optKey]['nomalPrice']*$pinfo['codeinfo']['halfday_percent']/100;
									$tmpprice2 = $schedule['options'][$optKey]['productHalfday_price'];
									$tmpprice += $tmpprice2+floor($tmpprice2*$schedule['options'][$optKey]['semiBusyHolidaySeason']/100);
									//return array('err'=>$tmpprice);
								}else {
									$tmpprice2 = $schedule['options'][$optKey]['nomalPrice'];
									$tmpprice += $tmpprice2+floor($tmpprice2*$schedule['options'][$optKey]['semiBusyHolidaySeason']/100);
									//return array('err'=>"==".$tmpprice);
								}
							}else if($pinfo['codeinfo']['oneday_ex']=="half"){//[1일 초과시 과금 기준] 12시간 단위
								if($diff['day']>=1 && $diff['hour'] >=0){ 
									if($diff['hour']<=12) $hour_over = 1;
									else $hour_over =2;
									//$tmpprice2 = $schedule['options'][$optKey]['nomalPrice'] * (($pinfo['codeinfo']['time_percent']/100)*$hour_over);
									$tmpprice2 = $pinfo['codeinfo']['time_price']*$hour_over;
									$tmpprice += $tmpprice2+floor($tmpprice2*$schedule['options'][$optKey]['semiBusyHolidaySeason']/100);
								}else if($pinfo['codeinfo']['halfday']=="Y" && $diff['day']<1 && $diff['hour'] <=12){ 
									//$tmpprice2 = $schedule['options'][$optKey]['nomalPrice']*$pinfo['codeinfo']['halfday_percent']/100;
									$tmpprice2 = $schedule['options'][$optKey]['productHalfday_price'];
									$tmpprice += $tmpprice2+floor($tmpprice2*$schedule['options'][$optKey]['semiBusyHolidaySeason']/100);
								}else{
									$tmpprice2 = $schedule['options'][$optKey]['nomalPrice'];
									$tmpprice += $tmpprice2+floor($tmpprice2*$schedule['options'][$optKey]['semiBusyHolidaySeason']/100);
								}
							}else{//[1일 초과시 과금 기준] 1일 단위
								if($pinfo['codeinfo']['halfday']=="Y" && $diff['day']<1 && $diff['hour'] <=12){
									//$tmpprice2 = $schedule['options'][$optKey]['nomalPrice']*$pinfo['codeinfo']['halfday_percent']/100;
									$tmpprice2 = $schedule['options'][$optKey]['productHalfday_price'];
									$tmpprice = $tmpprice2+floor($tmpprice2*$schedule['options'][$optKey]['semiBusyHolidaySeason']/100);
								}else{
									$tmpprice2 = $schedule['options'][$optKey]['nomalPrice'];
									$tmpprice += $tmpprice2+floor($tmpprice2*$schedule['options'][$optKey]['semiBusyHolidaySeason']/100);
								}
							}
							
						}else{
							$tmpprice += $schedule['options'][$optKey]['nomalPrice']*($pricecnt['semiHoli']);
							$tmpprice += floor($schedule['options'][$optKey]['nomalPrice']*$pricecnt['semiHoli']*$schedule['options'][$optKey]['semiBusyHolidaySeason']/100);
							//return array('err'=>"==".$pricecnt['semiHoli']);
						}

						if($sdateCnt['semiHoli']>0 && $pinfo['codeinfo']['oneday_ex']!="day"){//첫 시작일인경우
							$pricecnt['semiHoli'] = $pricecnt['semiHoli'] + 1;
						}

						if($return['discountmsg']!=""){
							$return['discountmsg'] .= ",";
						}
						if( $schedule['options'][$optKey]['semiBusyHolidaySeason']!=0)
							$return['discountmsg'] .= "준성수기*주말공휴일 ".$schedule['options'][$optKey]['semiBusyHolidaySeason']."% 할증";

						$return['addprice'] = $schedule['options'][$optKey]['semiBusyHolidaySeason'];
					}

					//24시간제 :::: 준성수기*평일
					if($pricecnt['semi'] > 0){

						if($sdateCnt['semi']>0 && $pinfo['codeinfo']['oneday_ex']!="day"){//첫 시작일이 준성수기*평일인경우
							$tmpprice += floor($tmpprice*$schedule['options'][$optKey]['semiBusySeason']/100);
							//$pricecnt['semi'] = $pricecnt['semi'] - 1;
						}

						if($edateCnt['semi']>0 && $diff['hour']>0){ //마지막일인 경우 
							$tmpprice += $schedule['options'][$optKey]['nomalPrice']*($pricecnt['semi']-1);
							$tmpprice += floor($schedule['options'][$optKey]['nomalPrice']*($pricecnt['semi']-1)*$schedule['options'][$optKey]['semiBusySeason']/100);
							
							if($pinfo['codeinfo']['oneday_ex']=="time"){//[1일 초과시 과금 기준] 1시간 단위
								if($diff['day']>=1 && $diff['hour'] >=0){
									//$tmpprice2 = $schedule['options'][$optKey]['nomalPrice'] * (($pinfo['codeinfo']['time_percent']/100)*$diff['hour']);
									$tmpprice2 = $pinfo['codeinfo']['time_price']*$diff['hour'];
									$tmpprice += floor($tmpprice2+$tmpprice2*$schedule['options'][$optKey]['semiBusySeason']/100);
								}else if($pinfo['codeinfo']['halfday']=="Y" && $diff['day']<1 && $diff['hour'] <=12) {
									//$tmpprice2 = $schedule['options'][$optKey]['nomalPrice']*$pinfo['codeinfo']['halfday_percent']/100;
									$tmpprice2 = $schedule['options'][$optKey]['productHalfday_price'];
									$tmpprice += $tmpprice2+floor($tmpprice2*$schedule['options'][$optKey]['semiBusySeason']/100);
								}else {
									$tmpprice2 = $schedule['options'][$optKey]['nomalPrice'];
									$tmpprice += $tmpprice2+floor($tmpprice2*$schedule['options'][$optKey]['semiBusySeason']/100);
								}
							}else if($pinfo['codeinfo']['oneday_ex']=="half"){//[1일 초과시 과금 기준] 12시간 단위
								if($diff['day']>=1 && $diff['hour'] >=0){ 
									if($diff['hour']<=12) $hour_over = 1;
									else $hour_over =2;
									//$tmpprice2 = $schedule['options'][$optKey]['nomalPrice'] * (($pinfo['codeinfo']['time_percent']/100)*$hour_over);
									$tmpprice2 = $pinfo['codeinfo']['time_price']*$hour_over;
									$tmpprice += floor($tmpprice2*$schedule['options'][$optKey]['semiBusySeason']/100);
								}else if($pinfo['codeinfo']['halfday']=="Y" && $diff['day']<1 && $diff['hour'] <=12){ 
									//$tmpprice2 = $schedule['options'][$optKey]['nomalPrice']*$pinfo['codeinfo']['halfday_percent']/100;
									$tmpprice2 = $schedule['options'][$optKey]['productHalfday_price'];
									$tmpprice += $tmpprice2+floor($tmpprice2*$schedule['options'][$optKey]['semiBusySeason']/100);
								}else{
									$tmpprice2 = $schedule['options'][$optKey]['nomalPrice'];
									$tmpprice += floor($tmpprice2*$schedule['options'][$optKey]['semiBusySeason']/100);
								}
							}else{//[1일 초과시 과금 기준] 1일 단위
								if($pinfo['codeinfo']['halfday']=="Y" && $diff['day']<1 && $diff['hour'] <=12){
									//$tmpprice2 = $schedule['options'][$optKey]['nomalPrice']*$pinfo['codeinfo']['halfday_percent']/100;
									$tmpprice2 = $schedule['options'][$optKey]['productHalfday_price'];
									$tmpprice = $tmpprice2+floor($tmpprice2*$schedule['options'][$optKey]['semiBusySeason']/100);
								}else{
									$tmpprice2 = $schedule['options'][$optKey]['nomalPrice'];
									$tmpprice += floor($tmpprice2*$schedule['options'][$optKey]['semiBusySeason']/100);
								}
							}
							
						}else{
							$tmpprice += $schedule['options'][$optKey]['nomalPrice']*($pricecnt['semi']);
							$tmpprice += floor($schedule['options'][$optKey]['nomalPrice']*$pricecnt['semi']*$schedule['options'][$optKey]['semiBusySeason']/100);
						}
						
						if($sdateCnt['semi']>0 && $pinfo['codeinfo']['oneday_ex']!="day"){//첫 시작일인경우
							$pricecnt['semi'] = $pricecnt['semi'] + 1;
						}
						
						if($return['discountmsg']!=""){
							$return['discountmsg'] .= ",";
						}
						if( $schedule['options'][$optKey]['semiBusySeason']!=0)
							$return['discountmsg'] .= "준성수기*평일 ".$schedule['options'][$optKey]['semiBusySeason']."% 할증";

						$return['addprice'] = $schedule['options'][$optKey]['semiBusySeason'];
					}

					//24시간제 :::: 비성수기*주말공휴일
					if($pricecnt['holiday'] > 0){

						if($sdateCnt['holiday']>0 && $pricecnt['total']!=$sdateCnt['holiday'] && $pinfo['codeinfo']['oneday_ex']!="day"){//첫 시작일이비성수기*주말공휴일인경우
							$tmpprice += floor($tmpprice*$schedule['options'][$optKey]['holidaySeason']/100);
							//$pricecnt['holiday'] = $pricecnt['holiday'] - 1;
						}

						if($edateCnt['holiday']>0 && $diff['hour']>0){ //마지막일인 경우 
							$tmpprice += $schedule['options'][$optKey]['nomalPrice']*($pricecnt['holiday']-1);
							$tmpprice += floor($schedule['options'][$optKey]['nomalPrice']*($pricecnt['holiday']-1)*$schedule['options'][$optKey]['holidaySeason']/100);
						
							if($pinfo['codeinfo']['oneday_ex']=="time"){//[1일 초과시 과금 기준] 1시간 단위
								if($diff['day']>=1 && $diff['hour'] >=0){
									//$tmpprice2 = $schedule['options'][$optKey]['nomalPrice'] * (($pinfo['codeinfo']['time_percent']/100)*$diff['hour']);
									$tmpprice2 = $pinfo['codeinfo']['time_price']*$diff['hour'];
									$tmpprice += $tmpprice2+floor($tmpprice2+$tmpprice2*$schedule['options'][$optKey]['holidaySeason']/100);
								}else if($pinfo['codeinfo']['halfday']=="Y" && $diff['day']<1 && $diff['hour'] <=12) {
									//$tmpprice2 = $schedule['options'][$optKey]['nomalPrice']*$pinfo['codeinfo']['halfday_percent']/100;
									$tmpprice2 = $schedule['options'][$optKey]['productHalfday_price'];
									$tmpprice += $tmpprice2+floor($tmpprice2*$schedule['options'][$optKey]['holidaySeason']/100);
								}else {
									$tmpprice2 = $schedule['options'][$optKey]['nomalPrice'];
									$tmpprice += $tmpprice2+floor($tmpprice2*$schedule['options'][$optKey]['holidaySeason']/100);
								}
							}else if($pinfo['codeinfo']['oneday_ex']=="half"){//[1일 초과시 과금 기준] 12시간 단위
								if($diff['day']>=1 && $diff['hour'] >=0){ 
									if($diff['hour']<=12) $hour_over = 1;
									else $hour_over =2;
									//$tmpprice2 = $schedule['options'][$optKey]['nomalPrice'] * (($pinfo['codeinfo']['time_percent']/100)*$hour_over);
									$tmpprice2 = $pinfo['codeinfo']['time_price']*$hour_over;
									$tmpprice += $tmpprice2+floor($tmpprice2*$schedule['options'][$optKey]['holidaySeason']/100);
								}else if($pinfo['codeinfo']['halfday']=="Y" && $diff['day']<1 && $diff['hour'] <=12){ 
									//$tmpprice2 = $schedule['options'][$optKey]['nomalPrice']*$pinfo['codeinfo']['halfday_percent']/100;
									$tmpprice2 = $schedule['options'][$optKey]['productHalfday_price'];
									$tmpprice += $tmpprice2+floor($tmpprice2*$schedule['options'][$optKey]['holidaySeason']/100);
								}else{
									$tmpprice2 = $schedule['options'][$optKey]['nomalPrice'];
									$tmpprice += $tmpprice2+floor($tmpprice2*$schedule['options'][$optKey]['holidaySeason']/100);
									//return array('err'=>$tmpprice2 );
								}
							}else{//[1일 초과시 과금 기준] 1일 단위
								if($pinfo['codeinfo']['halfday']=="Y" && $diff['day']<1 && $diff['hour'] <=12){
									//$tmpprice2 = $schedule['options'][$optKey]['nomalPrice']*$pinfo['codeinfo']['halfday_percent']/100;
									$tmpprice2 = $schedule['options'][$optKey]['productHalfday_price'];
									$tmpprice = $tmpprice2+floor($tmpprice2*$schedule['options'][$optKey]['holidaySeason']/100);
								}else{
									$tmpprice2 = $schedule['options'][$optKey]['nomalPrice'];
									$tmpprice += $tmpprice2+floor($tmpprice2*$schedule['options'][$optKey]['holidaySeason']/100);
								}
							}

						}else{
							$tmpprice += $schedule['options'][$optKey]['nomalPrice']*($pricecnt['holiday']);
							$tmpprice += floor($schedule['options'][$optKey]['nomalPrice']*$pricecnt['holiday']*$schedule['options'][$optKey]['holidaySeason']/100);
						}

						
						if($sdateCnt['holiday']>0 && $pinfo['codeinfo']['oneday_ex']!="day"){//첫 시작일인경우
							$pricecnt['holiday'] = $pricecnt['holiday'] + 1;
						}

						if($return['discountmsg']!=""){
							$return['discountmsg'] .= ",";
						}
						if( $schedule['options'][$optKey]['holidaySeason']!=0)
							$return['discountmsg'] .= "비성수기*주말공휴일 ".$schedule['options'][$optKey]['holidaySeason']."% 할증";

						$return['addprice'] = $schedule['options'][$optKey]['holidaySeason'];
					}

					$restTime = $pricecnt['total']-$pricecnt['busy']-$pricecnt['busyHoli']-$pricecnt['semiHoli']-$pricecnt['semi']-$pricecnt['holiday'];
//return array('err'=>$edateCnt['normal']);
					if($restTime>0){
						
						if($sdateCnt['normal']>0 && $pricecnt['total']!=$sdateCnt['normal'] && $pinfo['codeinfo']['oneday_ex']!="day"){//첫 시작일이 비수기*평일인경우
							$restTime = $restTime-1;
						}	

						if($edateCnt['normal']>0 && $diff['hour']>0){ //마지막일인 경우 
							$tmpprice += $schedule['options'][$optKey]['nomalPrice']*($restTime-1);
							
							if($pinfo['codeinfo']['oneday_ex']=="time"){//[1일 초과시 과금 기준] 1시간 단위
								if($diff['day']>=1 && $diff['hour'] >=0){ 
									//$tmpprice2 = $schedule['options'][$optKey]['nomalPrice'] * (($pinfo['codeinfo']['time_percent']/100)*$diff['hour']);
									$tmpprice2 = $pinfo['codeinfo']['time_price']*$diff['hour'];
									$tmpprice += $tmpprice2;
								}else if($pinfo['codeinfo']['halfday']=="Y" && $diff['day']<1 && $diff['hour'] <=12){
									//$tmpprice = $schedule['options'][$optKey]['nomalPrice']*$pinfo['codeinfo']['halfday_percent']/100;
									$tmpprice += $schedule['options'][$optKey]['productHalfday_price'];
								}else{
									$tmpprice += $schedule['options'][$optKey]['nomalPrice'];
								}
							}else if($pinfo['codeinfo']['oneday_ex']=="half"){//[1일 초과시 과금 기준] 12시간 단위
								if($diff['day']>=1 && $diff['hour'] >=0){ 
									if($diff['hour']<=12) $hour_over = 1;
									else $hour_over =2;
									//$tmpprice2 = $schedule['options'][$optKey]['nomalPrice'] * (($pinfo['codeinfo']['time_percent']/100)*$hour_over);
									$tmpprice2 = $pinfo['codeinfo']['time_price']*$hour_over;
									//return array('err'=>$tmpprice2);
									$tmpprice += $tmpprice2;
									
								}else if($pinfo['codeinfo']['halfday']=="Y" && $diff['day']<1 && $diff['hour'] <=12){ 
									//$tmpprice = $schedule['options'][$optKey]['nomalPrice'] * $pinfo['codeinfo']['halfday_percent']/100;
									$tmpprice += $schedule['options'][$optKey]['productHalfday_price'];
									
								}else{
									$tmpprice += $schedule['options'][$optKey]['nomalPrice'];
								}
							}else{//[1일 초과시 과금 기준] 1일 단위
								if($pinfo['codeinfo']['halfday']=="Y" && $diff['day']<1 && $diff['hour'] <=12){
									/*
									$tmpprice2 = $pinfo['codeinfo']['halfday_percent']/100;
									$tmpprice2 = $schedule['options'][$optKey]['nomalPrice']*$tmpprice2;
									$tmpprice = $tmpprice2;*/
									$tmpprice = $schedule['options'][$optKey]['productHalfday_price'];
								}else{
									$tmpprice += $schedule['options'][$optKey]['nomalPrice'];
								}
							}

						}else{
							if($diff['day']==1 && $diff['hour'] <=0){
								$tmpprice = $schedule['options'][$optKey]['nomalPrice'];
							}else if($diff['day']>1 && $diff['hour']==0){//00~00시인 경우 
								$tmpprice = $schedule['options'][$optKey]['nomalPrice']*$restTime;
							}else{
								$tmpprice += $schedule['options'][$optKey]['nomalPrice']*$restTime;
							}		
						}

						$return['discountmsg'] .= "";
					}

					$return['discountmsg'] .= "";
					
					
					if(($diff['day']>=1 && $diff['hour'] >0) || $diff['day']<1){
						if($diff['hour']>0){
							$diff_day = $diff['day']+1;
						}else{
							$diff_day = $diff['day'];
						}
					}else{
						$diff_day = $diff['day'];
					}
					
					//return array('err'=>$diff_day);
					/*
					if($diff['day']>0){
						$schedule['options'][$optKey]['nomalPrice'] = $schedule['options'][$optKey]['nomalPrice'];
					}else{
						$schedule['options'][$optKey]['nomalPrice'] = $tmpprice2;
					}
					*/
					
					//$schedule['options'][$optKey]['nomalPrice'] = $tmpprice2;
					//return array('err'=>$tmpprice2);
					
					$basicprice = $schedule['options'][$optKey]['nomalPrice'] *($diff_day)
						+ $pricecnt['holiday']*round($schedule['options'][$optKey]['nomalPrice']*$schedule['options'][$optKey]['holidaySeason']/100)
						+ $pricecnt['busyHoli']*round($schedule['options'][$optKey]['nomalPrice']*$schedule['options'][$optKey]['busyHolidaySeason']/100)
						+ $pricecnt['semiHoli']*round($schedule['options'][$optKey]['nomalPrice']*$schedule['options'][$optKey]['semiBusyHolidaySeason']/100)
						+ $pricecnt['busy']*round($schedule['options'][$optKey]['nomalPrice']*$schedule['options'][$optKey]['busySeason']/100)
						+ $pricecnt['semi']*round($schedule['options'][$optKey]['nomalPrice']*$schedule['options'][$optKey]['semiBusySeason']/100);
//return array('err'=>$tmpprice."/".$basicprice);

					if($tmpprice>$basicprice){ 
						$priceover = 1;
						$tmpprice = $basicprice;
					}else{
						$priceover = 0;
						$tmpprice = $tmpprice;
					}
					
			
				/*************************** 단기기간제인경우 ************************************/
				}else if($pinfo['codeinfo']['pricetype'] =='period'){
					$diff_day = $diff['day'];
					$tmpprice = $schedule['options'][$optKey]['nomalPrice'];
					$realprice = $schedule['options'][$optKey]['nomalPrice'];
					$basicprice = $tmpprice;
	
				/*************************** 숙박제인경우 ************************************/
				}else{
					$diff_day = $diff['day'];
					$realprice = $schedule['options'][$optKey]['nomalPrice']*$pricecnt['total'];
					$restDay = $pricecnt['total']-$pricecnt['busy']-$pricecnt['busyHoli']-$pricecnt['semiHoli']-$pricecnt['semi']-$pricecnt['holiday'];

					$normalPrice = $schedule['options'][$optKey]['nomalPrice']*$restDay;
					$holidayPrice = $pricecnt['holiday']*round($schedule['options'][$optKey]['nomalPrice']+($schedule['options'][$optKey]['nomalPrice']*$schedule['options'][$optKey]['holidaySeason']/100));
					$busyHolidayPrice = $pricecnt['busyHoli']*round($schedule['options'][$optKey]['nomalPrice']+($schedule['options'][$optKey]['nomalPrice']*$schedule['options'][$optKey]['busyHolidaySeason']/100));
					$semiHoliPrice = $pricecnt['semiHoli']*round($schedule['options'][$optKey]['nomalPrice']+($schedule['options'][$optKey]['nomalPrice']*$schedule['options'][$optKey]['semiBusyHolidaySeason']/100));
					$busyPrice = $pricecnt['busy']*round($schedule['options'][$optKey]['nomalPrice']+($schedule['options'][$optKey]['nomalPrice']*$schedule['options'][$optKey]['busySeason']/100));
					$semiPrice = $pricecnt['semi']*round($schedule['options'][$optKey]['nomalPrice']+($schedule['options'][$optKey]['nomalPrice']*$schedule['options'][$optKey]['semiBusySeason']/100));

					$tmpprice = $normalPrice + $holidayPrice + $busyHolidayPrice + $semiHoliPrice + $busyPrice + $semiPrice;
					$basicprice = $tmpprice;
				}

				// 회원 할인있을 경우 적용
				//$return['member_discount'] = $pinfo['gdiscount']['discount'];
				
				//총합계금액
				$return['totalprice'] += $tmpprice*$optCnt;	
				$return['basicprice'] += $basicprice*$optCnt;	
				
			}//end foreach

		}//end if
		//대여날짜계산
		/*
		if($pinfo['codeinfo']['pricetype'] =='day' || $pinfo['codeinfo']['pricetype'] =='time'){
			if(($diff['day']>=1 && $diff['hour'] >0) || $diff['day']<1){
				if($diff['hour']>12){
					$diff_day = $diff['day']+0.5;
				}else{
					$diff_day = $diff['day'];
				}
			}else{
				$diff_day = $diff['day'];
			}	
		}else{
			$diff_day = $diff['day'];
		}
		*/
		
		$diff_day = $diff['day'];

		// 장기 대여 조회
		if($pinfo['codeinfo']['pricetype'] =='period'){
			$longrentP = venderLongrentCharge($vender,$pridx,abs($diff_day));
			if($longrentP < 0){
				$longrentP = rentLongrentCharge($pridx,abs($diff_day));	
			}
		// 장기 할인 조회
		}else{
			/*
			if($pinfo['codeinfo']['pricetype'] =='day' && $pinfo['codeinfo']['oneday_ex']=="half"){
				//$diff_day = $diff_day+1;
				if(($diff['day']>=1 && $diff['hour'] >0) || $diff['day']<1){
					if($diff['hour']>0){
						$diff_day = $diff_day+1;
					}else{
						$diff_day = $diff_day;
					}
				}else{
					$diff_day = $diff_day;
				}
			}else{
				$diff_day = $diff_day;
			}
			*/
			//$diff_day2 = round($diff_day);
			if($pinfo['codeinfo']['pricetype'] =='day' || $pinfo['codeinfo']['pricetype'] =='time'){
				//$diff_day2 = $diff_day+1;
				if(($diff['day']>=1 && $diff['hour'] >0) || $diff['day']<1){
					if($diff['hour']>0){
						$diff_day2 = $diff_day+1;
					}else{
						$diff_day2 = $diff_day;
					}
				}else{
					$diff_day2 = $diff_day;
				}
			}else{
				$diff_day2 = $diff_day;
			}
			//return array('err'=>$tmpprice."/".$basicprice."/".$diff_day."/".$diff_day2);
			$longdiscountP = venderLongDiscount($vender,$pridx,abs($diff_day));
			if($longdiscountP < 0){
				$longdiscountP = rentLongDiscount($pridx,abs($diff_day));	
			}

			$longdiscountP2 = venderLongDiscount($vender,$pridx,abs($diff_day2));
			if($longdiscountP2 < 0){
				$longdiscountP2 = rentLongDiscount($pridx,abs($diff_day2));	
			}
			
		}

		//장기할인
		if($longdiscountP > 0){
			$return['discprice'] += -1*floor($return['totalprice']*($longdiscountP/100));
		}
		if($longdiscountP2 > 0){
			$return['discprice2'] += -1*floor($return['basicprice']*($longdiscountP2/100));
		}

		//return array('err'=>$tmpprice."/".$basicprice."/".$return['discprice2']);
		//$tmpprice(계산가격)이 $basicprice(기본가격)+할인가격보다 큰 경우
		if($return['totalprice']>$return['basicprice']+$return['discprice2']){ 
			$priceover = 1;
			if($tmpprice>$basicprice){//$tmpprice(계산가격)이 $basicprice(할인가격제외 기본가격)이 큰경우
				$return['discprice'] = $return['discprice2'];
				$return['totalprice'] = $return['basicprice'];
			}else{
				$return['discprice'] = $return['basicprice']+$return['discprice2']-$return['totalprice'];
				$return['totalprice'] = $return['totalprice'];
			}
		}else{
			$priceover = 0;
			$return['discprice'] = $return['discprice'];
			$return['totalprice'] = $return['totalprice'];
		}

		if($pinfo['codeinfo']['pricetype'] =='long'){
			$return['discprice'] = 0;
		}

		
		//장기대여 추가과금
		$return['longrentmsg'] = "";
		$return['longrent'] = 0;
		if($longrentP > 0){
			$return['longrent'] += floor($return['totalprice']*($longrentP/100));
			$return['longrentmsg'] = $longrentP;
		}

		//회원할인금액
		$return['discountprice'] +=($return['totalprice']+$return['discprice']+$return['longrent'])*$return['member_discount'];

		//상품 할인전 원래 금액
		$return['prdrealprice'] = $tmpprice;
	//	$return['prdrealprice'] = $tmpprice*$optCnt;
		
		if($pinfo['codeinfo']['pricetype'] =='day'){
			$return['addprice'] = $return['addprice'];
		}else{
			$return['addprice'] = $tmpprice - $realprice;
		}
		

//		_pr($return);
		$return['msg'] = 'ok';
		return $return;	
	}

	
	// 시간 범위 timestamp 로 반환
	/**
	시간 범위 값 반환 ( 입력값을 조건에 따른 보정 처리 포함)
	@param int timegap 대상 조건 시간
	@param string start 시작일
	@param string end	종료일
	@return array 타입스템프등을 포함한 연관 배열
	*/
	static public function getTimeRange($timegap=24,$start,$end){
		$return = array('err'=>'');		
		
		if(!_empty($start)) $startstamp =_strtotime($start);
		
		//if(!_empty($end)) $endstamp = _strtotime($end);
		//else $endstamp = _strtotime($start,true);

		if(!_empty($end)) $endstamp = _strtotime($end,true);
		else $endstamp = _strtotime($start,true);
		
		if(_empty($startstamp) || _empty($endstamp)) return array('err'=>'입력 범위 오류-범위값이 지정 되지 않았습니다.'); // 오류
		else if($startstamp >= $endstamp) return array('err'=>'입력 범위 오류-시작일이 종료일과 같거나 큰경우'); // 오류
		
		$return['timegap'] = $timegap;
		$return['rangestamp'] = array($startstamp,$endstamp);
		
		return $return;
	}
	
	/**
	옵션 문자열 을 파싱해서 스케줄 비교 가능한 형태의 연관 배열 반환
	
	@param int pridx 상품 식별 고유 번호
	@param string options 옵션 및 수량 정보 조합 문자열
	@param string start 시작일
	@param string end 종료일
	
	@return array 옵션 별 예약 시간대 수량 등의 연관 배열 반환
	*/
	static public function parseOptionDate($pridx,$options,$start,$end){
		$return = array('err'=>'');
		$pinfo = self::read($pridx);
		if(!$pinfo) return NULL;
		
		if(is_string($options) && preg_match('/|[0-9]+/',$options))  $options =  parseRentRequestOption($options);
		if(!_array($options)) return array('err'=>'옵션 전달 오류');
		if($pinfo['codeinfo']['pricetype']=='checkout'){
			//if(!_empty($start)) $start = substr($start,0,10).' 14:00:00';
			//if(!_empty($end)) $end = substr($end,0,10).' 11:00:00';

			$pinfo['codeinfo']['checkin_time'] = $pinfo['codeinfo']['checkin_time'];
			$start = substr($start,0,10).' '.$pinfo['codeinfo']['checkin_time'].':00:00';
			$end = substr($end,0,10).' '.$pinfo['codeinfo']['checkout_time'].':00:00';
		}
		if($pinfo['codeinfo']['pricetype']=='long'){
			$start = substr($start,0,10).' '.$pinfo['codeinfo']['checkin_time'].':00:00';
			$end = substr($end,0,10).' '.$pinfo['codeinfo']['checkout_time'].':00:00';
		}
		$stamp = self::getTimeRange($pinfo['codeinfo']['pricetype']=='time'?1:24,$start,$end);
		if(!_empty($stamp['err'])) return array('err'=>$stamp['err']); // 오류
		$return = array_merge($return,$stamp);
		
		$return['items'] = array();
				
		for($st = $return['rangestamp'][0];$st <= $return['rangestamp'][1];$st+= $return['timegap']*3600){
			$key = date('Y-m-d'.(($return['timegap'] == 1)?' H':''),$st);			
			$return['items'][$key] = $options;			
		}
		return $return;
	}
	
	/**
	장바구니 등록 항목 조회
	@param string baskettbl 장바구니 타입에 따른 테이블 
	@param string tempkey	장바구니 식별 용 문자열
	@param int	pridx	[선택] 상품 고유 식별 번호
	@param int basketidx	[선택] 장바구니 상품 항목 고유 식별 번호
	@return array 항목 수 및 항목별 정도 등 에 관한 복합 다층 배열 반환
	*/	
	static public function readBasket($baskettbl,$tempkey,$pridx=NULL,$basketidx=NULL,$memid=""){		
		$return = array('err'=>'','items'=>array());	

		if(strlen($memid)==0) {	//비회원
			$basketWhere = "tempkey='".$tempkey."' and memid=''";
		}else{
			$basketWhere = "memid='".$memid."'";
		}

		$sql =  "SELECT r.*,b.basketidx,b.ordertype,b.reservationCode,p.pridx from rent_basket_temp r  inner join `".$baskettbl."` b on r.basketidx = b.basketidx and r.ordertype = b.ordertype left join tblproduct p on p.productcode = b.productcode WHERE b.".$basketWhere." and p.rental='2' ";
						
		if(_isInt($pridx)) $sql .= " and p.pridx='".$pridx."' ";
		if(_isInt($basketidx)) $sql .= " and b.basketidx='".$basketidx."' ";
	
		if(false === $res = mysql_query($sql,self::$conn)) return array('err'=>'DB 접속 오류');	
		if(mysql_num_rows($res) <1) return $return;			
		
		while($row= mysql_fetch_assoc($res)){
			if(is_null($pridx) && _isInt($basketidx)){
				$return['items'] = $row;
				
				break;
			}else{
				if(!isset($return['items'][$row['pridx']])) $return['items'][$row['pridx']] = array();
				
				array_push($return['items'][$row['pridx']],$row);
			}
		}
		return $return;
	}
	
	/**
	특정 옵션이 특정 기간내에 대여 가능한지의 여부 확인
	@param array options 예약 하려는 옵션 및 수량 시간 등에 관한 복합 정보 배열
	@param array schedule 상품 스케줄 정복 가져 오기를 통해 받은 예약 일정 정보 배열
	@param bool findone  1개의 불가 항목 발견시 중지여부 (기본 false 전체 항목에 대해서 확인 true 일 경우 옵션중 하나만 예약 불가조건이 발생 해서 중지 시킴
	@return array 에러 메시지 및 불가 일자에 관한 연관 배열
	*/
	static public function checkRentable($options,&$schedule=array(),$findone=false){
		$return = array('err'=>'','disable'=>array());
	
		if(!_empty($schedule['err'])) $return['err'] = $schedule['err'];
		else{		
			foreach($options as $idx=>$cnt){				
				if(isset($schedule['options'][$idx])){
					$opmaxcnt = $schedule['options'][$idx]['productCount'];				
					
					foreach($schedule['optschedule'][$idx] as $date=>$rentcnt){										
						//if(substr($date,0,10) <= date('Y-m-d')){
						if(substr($date,0,13) <= date('Y-m-d H')){//당일예약인경우
							//$return['disable'][$date]= $cnt;
							if($cnt+$rentcnt > $opmaxcnt){
								$return['disable'][$date]= max($opmaxcnt-$rentcnt,0);
							}
							//if($findone) break;
						}else if($cnt+$rentcnt > $opmaxcnt){
							$return['disable'][$date]= max($opmaxcnt-$rentcnt,0);
							if($findone) break;
						}
					}
				}else{
					$return['err'] = '옵션 고유식별 번호가 올바르지 않습니다.';
				}
			}
		}		
		return $return;
	}
	
	/**
	예약 요청에 관한 요청 정보 배열 정리
	@param &array 정보 반황용 참조 배열 일자별,옵션별 시간 에 대한 수량 정보 등에 관한 복합 다차원 배열
	@param int pridx 상품 고유 식별 번호 tblproduct.pridx
	@param string options 옵션고유 번호 및 요청 수량에 관한 조합 문자열
	@param string start 시작일
	@param string end	종료일
	@return bool true || array 오류가 있을 경우는 오류 메시지를 포함한 배열 반환
	*/
	static private function solvReqArr(&$rsvArr,$pridx,$options,$start,$end){
		if(!_isInt($pridx)) return array('err'=>'상품 정보 파싱 오류');
		if(!_array($options)) return array('err'=>'상품 옵션 정보 파싱 오류');
		
		$tmp = array();
		$tmp = self::parseOptionDate($pridx,$options,$start,$end);
		
		if(!_empty($tmp['err'])) return $tmp;
		if(!_array($tmp['items'])) return array('err'=>'데이터 파싱 오류');	
		if(!_array($rsvArr)) $rsvArr=$tmp['items'];		
		else{			
			foreach($tmp['items'] as $date=>$vals){
				if(!_array($rsvArr[$date])) $rsvArr[$date] = array();			
				foreach($vals as $optidx=>$qty){
					if(isset($rsvArr[$date][$optidx])) $rsvArr[$date][$optidx] += $qty;					
					else $rsvArr[$date][$optidx] = $qty;					
				}
			}	
		}
		self::schedule($pridx,$start,$end,array_keys($options)); // 스케줄 정보 로드
		return true;
	}
	
	
	/**
	장바구니 등록(넣기)
	@param string ordertype 주문 구분 ( 즉시 구매 등)
	@param string tempkey 장바구니 확인용 식별 문자열
	@param int pridx 상품 고유 식별 번호 tblproduct.pridx
	@param string options 옵션 식별 번호 및 요청 수량 등에 관한 조합문자열 
	@param string start 시작일
	@param string end	종료일
	
	@return array 성공 여부 및 실패시 관련 메시지를 포함한 복합 배열
	*/
	static public function insertBasket($ordertype,$delitype,$tempkey,$pridx,$options=array(),$start,$end,$folder=0){
		global $_ShopInfo;
		$pinfo = self::read($pridx);	
		$return = array('err'=>'ok');
		$baskettbl = basketTable($ordertype);
		
		if($pinfo && (!_empty($baskettbl) || $ordertype == 'recommandnow')){
			if(is_string($options) && preg_match('/|[0-9]+/',$options))  $options =  parseRentRequestOption($options);
			if(!_array($options)) return array('err'=>'옵션 전달 오류');
			
			$rsvArr = array();
			if($ordertype != 'recommandnow'){
				$inbasket = self::readBasket($baskettbl,$tempkey,$pinfo['pridx'],'',$_ShopInfo->getMemid());		
				if(!_empty($inbasket['err'])) return $inbasket;				
			
				if(_array($inbasket['items'][$pinfo['pridx']])){
					foreach($inbasket['items'][$pinfo['pridx']]  as $basketitems){
						$ret = self::solvReqArr($rsvArr,$pinfo['pridx'],array($basketitems['optidx']=>$basketitems['quantity']),$basketitems['start'],$basketitems['end']);
						if(!_empty($ret['err'])) return $ret;
					}
				}
			}
			
			if($pinfo['codeinfo']['pricetype'] != 'long' && $ordertype != 'prebasket'){
				$ret = self::solvReqArr($rsvArr,$pinfo['pridx'],$options,$start,$end);
				if(!_empty($ret['err'])) return $ret;					
				if(!_array($rsvArr)) return array('err'=>'대여가능 여부 확인 오류');
				$schedules = &self::$existsPridx[$pinfo['pridx']]['schedule'];
				$optinfo = &self::$existsPridx[$pinfo['pridx']]['options']; 
				//echo $start."/".$end;exit;
				foreach($rsvArr as $date=>$roptval){
					if(!isset($schedules[$date])) return array('err'=>'렌탈 가능 기간 조회 오류a');
					foreach($roptval as $optidx=>$qty){
						if(!isset($schedules[$date][$optidx])) return array('err'=>'렌탈 가능 기간 조회 오류b');
						if($optinfo[$optidx]['productCount'] - $schedules[$date][$optidx] - $qty <0){
							return array('err'=>$date.' 에 대한 예약이 불가합니다.');
						}
					}
				}
			}
		
			$stamp = self::getTimeRange($pinfo['codeinfo']['pricetype']=='time'?1:24,$start,$end);		
			
			if($pinfo['codeinfo']['pricetype'] != 'long'){
				if($pinfo['codeinfo']['pricetype'] == 'time'){
					if($stamp['rangestamp'][0] < time()-3600 || $stamp['rangestamp'][1] < time()-3600) return array('err'=>'예약시간 범위 오류');
				}else if($stamp['rangestamp'][0] < time()-24*3600 || $stamp['rangestamp'][1] < time()-24*3600) return array('err'=>'예약시간 범위 오류2');
				
			}

			
			foreach($options as $optidx=>$quantity){
				
				if($pinfo['codeinfo']['pricetype']!='long'){
					$indata['start'] = date('Y-m-d H:i:s',$stamp['rangestamp'][0]);
					$indata['end'] = ($pinfo['codeinfo']['pricetype'] == 'checkout')?date('Y-m-d H:00:00',$stamp['rangestamp'][1]):date('Y-m-d H:i:s',$stamp['rangestamp'][1]);
				}else{
				}
				$indata['optidx'] = $optidx;
				$indata['quantity'] = $quantity;
				
				$indata['basketidx'] = '';
				$indata['ordertype'] = $ordertype;
				$indata['folder'] = $folder? $folder : 0;
				$indata['delitype'] = $delitype;
				$indata['memid'] = $_ShopInfo->getMemid();
				
				if(_array($inbasket['items'][$pinfo['pridx']])){
					foreach($inbasket['items'][$pinfo['pridx']]  as $basketitems){
						if(($basketitems['optidx'] == $indata['optidx']) && ($basketitems['start'] == $indata['start']) && ($basketitems['end'] == $indata['end'])){
							$indata['basketidx'] = $basketitems['basketidx'];
							break;
						}
					}
				}
				
				if($ordertype == 'recommandnow'){
					$sql = "insert into recommand_basket set memid='".$_ShopInfo->getMemid()."',productcode='".$pinfo['productcode']."',opt1_idx='1',opt2_idx='',optidxs='',quantity='".$indata['quantity']."',deli_type='".$indata['delitype']."',date=NOW()";
					if(false === $res = mysql_query($sql,self::$conn)) return array('err'=>'DB 오류 #2');
					$indata['basketidx'] = mysql_insert_id(self::$conn);
					$sql = "insert into rent_basket_temp (basketidx,ordertype,start,end,optidx,quantity,deli_type) values ('".$indata['basketidx']."','recommand','".$indata['start']."','".$indata['end']."','".$indata['optidx']."','".$indata['quantity']."','".$indata['delitype']."') ON DUPLICATE KEY UPDATE quantity=values(quantity),start=values(start),end=values(end),optidx=values(optidx)";
				}else{
					if(_isInt($indata['basketidx'])){
						$sql = "insert into rent_basket_temp (basketidx,ordertype,start,end,optidx,quantity,deli_type) values ('".$indata['basketidx']."','".$indata['ordertype']."','".$indata['start']."','".$indata['end']."','".$indata['optidx']."','".$indata['quantity']."','".$indata['delitype']."') ON DUPLICATE KEY UPDATE quantity=quantity+values(quantity),start=values(start),end=values(end),optidx=values(optidx) ";
	//					$sql = "insert into rent_basket_temp ( set quantity=quantity+'".$indata['quantity']."' where basketidx='".$indata['basketidx']."' and ordertype='".$ordertype."'";
					}else{
						$sql = "select max(opt1_idx) from ".$baskettbl." where tempkey='".$tempkey."' and productcode='".$pinfo['productcode']."'";
						$tmpidx=1;
						if(false === $res = mysql_query($sql,self::$conn)) return array('err'=>'DB 오류 #1');
						if(mysql_num_rows($res)) $tmpidx = mysql_result($res,0,0)+1;
						
						$sql = "insert into ".$baskettbl." set tempkey='".$tempkey."', productcode='".$pinfo['productcode']."',opt1_idx='".$tmpidx."',opt2_idx='',optidxs='',quantity='".$indata['quantity']."',deli_type='".$indata['delitype']."',memid= '".$indata['memid']."',date= '".date('YmdHis')."',ordertype='".$ordertype."',folder='".$indata['folder']."' ";

						if(false === $res = mysql_query($sql,self::$conn)) return array('err'=>'DB 오류 #2');
						$indata['basketidx'] = mysql_insert_id(self::$conn);
						$sql = "insert into rent_basket_temp (basketidx,ordertype,start,end,optidx,quantity,deli_type) values ('".$indata['basketidx']."','".$indata['ordertype']."','".$indata['start']."','".$indata['end']."','".$indata['optidx']."','".$indata['quantity']."','".$indata['delitype']."') ON DUPLICATE KEY UPDATE quantity=values(quantity),start=values(start),end=values(end),optidx=values(optidx)";				
					}
					if(false === mysql_query($sql,self::$conn)){
						return array('err'=>'DB 등록 오류');
					}
					$sql = "update ".$baskettbl." b left join rent_basket_temp r on r.basketidx=b.basketidx and r.ordertype=b.ordertype set b.quantity=r.quantity,b.folder='".$indata['folder']."' where b.basketidx='".$indata['basketidx']."'";
				}
				@mysql_query($sql,self::$conn);
			}
			
			$return['err'] = 'ok';
		}
		return $return;		
	}
	
	/**
	자동 실행 처리용 미처리 주문 자동 취소 처리
	설정값 BR_limit 내 처리 되지 않은 요청에 대한 취소 처리 등을 지원
	Todo : 메시지 자동 발송 기능 및 예정 주문에 대한 알람 기능 등의 처리
			cron 등록 등을 통한 자동 호출 방법에 대한 처리
	*/
	static public function cancelAuto(){
		$sql = "update rent_schedule set status = 'NC' where status='NN' and regDate < date_add(now(), interval -".self::$BR_limit." hour)";
		@mysql_query($sql,self::$conn);
		$sql = "update rent_schedule set status = 'BC' where status='BR' and regDate < date_add(now(), interval -".self::$BR_limit." hour)";
		@mysql_query($sql,self::$conn);
		$sql = "update tblorderproduct p left join rent_schedule s on (s.ordercode=p.ordercode and s.basketidx=p.basketidx) set p.deli_gbn='C' where s.status='BC' and p.deli_gbn != 'C'";		
		@mysql_query($sql,self::$conn);		
		$sql = "select o.ordercode,count(p.ordercode) as tcnt,sum(if(p.deli_gbn ='C',1,0)) as ccnt,o.reserve,o.id from tblorderinfo o left join  tblorderproduct p  on o.ordercode=p.ordercode where o.deli_gbn='N' group by p.ordercode";
		if((false != $res = mysql_query($sql,self::$conn)) && mysql_num_rows($res)){
			while($row = mysql_fetch_assoc($res)){
				if($row['tcnt'] == $row['ccnt']){ 
					// 후속 조치가 필요한 부분에 대해서 처리				
					if($row['reserve']>0 && substr($row['id'],-1,1) != 'X'){//적립금 환원
						@mysql_query("UPDATE tblmember SET reserve=reserve+".$row['reserve']." WHERE id='".$row['id']."'",self::$conn);
						$reserve_restore_sql = "INSERT tblreserve SET id = '".$row['id']."', reserve = '".abs($row['reserve'])."' , reserve_yn = 'Y', content='주문취소 의한 적립금 복구', orderdata = '".$row['ordercode']."', date='".date('YmdHis')."' ";
						@mysql_query($reserve_restore_sql,self::$conn);
					}
					@mysql_query("update tblorderinfo set deli_gbn='C' where ordercode='".$row['ordercode']."'",self::$conn);	
						
				}
			}
		}

		// 자동화 작업 처리
		// 1. 예약완료 상태에서 렌탈 시작이 되고 끝나지 않으면 대여중으로 변경.
		$sql = "UPDATE rent_schedule SET status='BI' WHERE status='BO' AND NOW() BETWEEN start AND end";
		mysql_query($sql,self::$conn);
		// select count(*) from rent_schedule s left join tblorderinfo o on o.ordercode=s.ordercode where o.id='".$userid."' and s.status='BI' and s.end <= NOW()
		// 2. 대여중 상태에서 종료시간이 되면 대여종료으로 변경
		$sql = "UPDATE rent_schedule SET status='BE' WHERE status='BI' and end <= NOW()";
		mysql_query($sql,self::$conn);
		
	}
	
	/**
	특정 기간내 렌탈 주문에 관한 정보 호출 ( 벤더일 경우는 해당 벤더의 주문 만 호출
	@param string start 시작일
	@param string end 	종료일
	@param int pridx [옵션] 특정 상품만 확인 할 경우 해당 상품의 고유 식별 번호
	@return array 주문 정보및 옵션별 기간과 수량 등에 관한 정보를 포함한 복합 다차원 배열
	*/
	static public function rangeProduct($start,$end,$pridx=NULL){
		$where = array();	
		if(_isInt($pridx)){
			return self::schedule($pridx,$start,$end);
		}else{
			
			$stamp = rentProduct::getTimeRange(24,$start,$end);
			if(!_empty($stamp['err'])) return $stamp;
			array_push($where,"IF(s.status = 'BR', s.regDate >= date_add(now(), interval -".self::$BR_limit." hour) AND s.status = 'BR', s.status IN ('".implode("','",array_keys(self::$bookingStatus))."')) ");
			array_push($where,"s.`start` <= '".date('Y-m-d H:i:s',$stamp['rangestamp'][1])."' AND s.`end` >= '".date('Y-m-d H:i:s',$stamp['rangestamp'][0])."'");
			
			if(isset($GLOBALS['_VenderInfo']) && _isInt($GLOBALS['_VenderInfo']->getVidx())) array_push($where,"p.vender='".$GLOBALS['_VenderInfo']->getVidx()."'"); //벤더일 경우는 자기꺼만

			$sql = "select ord.id,ord.sender_name,ord.sender_email,ord.sender_tel,ord.receiver_name,ord.receiver_tel1,receiver_email,op.price,p.productname,p.pridx,o.optionName,o.grade,s.* from rent_schedule s inner join tblorderinfo ord on ord.ordercode=s.ordercode  inner join rent_product_option o on o.idx=s.optidx inner join tblproduct p using(pridx) left join tblorderproduct op on op.ordercode=s.ordercode and op.basketidx=s.basketidx where	".implode(' and ',$where)." order by s.start,s.end";		

		//	$sql = "select p.pridx from rent_schedule s inner join rent_product_option o on o.idx=s.optidx inner join tblproduct p on (p.pridx=o.pridx) where	".implode(' and ',$where)."  group by p.pridx order by s.start,s.end";

			$return = array();
			
			$RES = mysql_query( $sql,self::$conn);
			while ( $ROW = mysql_fetch_assoc( $RES ) ) {
				array_push($return,$ROW);		
			}
			return $return;
		}	
	}
	
	/**
	조건에 따른 주문 정보 조회
	@param array param 대상 컬럼등을 key 로 가지고 검색 값을 value로 가지는 연관 배열
	@return array 조회 정보에 대항 복합 다차원배열
	*/
	static public function searchOrder($param=array()){
		$where = array();	
		if(_array($param['status'])) {
			if (in_array("BNC", $param['status'])) {
				// BNC 렌탈 종료 임박 - 상태 코드 없어서 쿼리로 찾아야 함. 이건 단일 배열로 상태값 넣어야 함.
				array_push($where, "s.status='BI' and NOW() between DATE_SUB(s.end, interval ".self::$BR_nearlimit." hour) AND s.end");
			} elseif(in_array("BRA", $param['status'])) {
				// 예약 입금대기
				array_push($where, "s.status='BR' and NOW() between s.regDate and DATE_ADD(s.regDate, interval ".self::$BR_limit." hour)");
			} elseif(in_array("BRB", $param['status'])) {
				// 취소임박 예약
				array_push($where, "s.status='BR' and NOW() between DATE_ADD(s.regDate, interval ".(self::$BR_limit-self::$BR_nearlimit)." hour) and DATE_ADD(s.regDate, interval ".self::$BR_limit." hour)");
			} elseif(in_array("BRC", $param['status'])) {
				// 취소 예약
				array_push($where, "((s.status='BR' and NOW() > DATE_ADD(s.regDate, interval ".self::$BR_limit." hour)) OR ((s.status='BC' or s.status='NC') and s.end >= NOW()))");
			} elseif(in_array("BRD", $param['status'])) {
				// 지난 취소 예약
				array_push($where, "((s.status='BR' and s.end < NOW()) OR ((s.status='BC' or s.status='NC') and s.end < NOW()))");
			} else {
				array_push($where,"s.status in ('".implode("','",$param['status'])."')");
			}
		} else if(!_empty($param['status'])){
			if(false !== strpos($param['status'],',')){
				$tmp = explode(',',$param['status']);
				for($i = count($tmp)-1;$i>=0;$i--){
					if(_empty($tmp[$i])) unset($tmp[$i]);
				}
				if(_array($tmp)) array_push($where,"s.status in ('".implode("','",$tmp)."')");	
			}else{
				array_push($where,"s.status='".$param['status']."'");	
			}
		} else {
			array_push($where,"IF(s.status = 'BR', s.regDate >= date_add(now(), interval -".self::$BR_limit." hour) AND s.status = 'BR', s.status IN ('".implode("','",array_keys(self::$bookingStatus))."')) ");
		}

		if(!_empty($param['start'])) $start = $param['start'];
		if(!_empty($param['end'])) $end = $param['end'];
	//	if(!_empty($start) && _empty($end)) $end = $start;
		if(!_empty($start)){
			if(_empty($end)) $end = $start;
			$stamp = rentProduct::getTimeRange(24,$start,$end);
			if(!_empty($stamp['err'])) return $stamp;
			array_push($where,"s.`start` <= '".date('Y-m-d H:i:s',$stamp['rangestamp'][1])."' AND s.`end` >= '".date('Y-m-d H:i:s',$stamp['rangestamp'][0])."'");
		}
			
		if(!_empty($param['location'])) array_push($where," s.location='".$param['location']."'");			
		if(!_empty($param['id'])) array_push($where," ord.id = '".$param['id']."'");
		if(!_empty($param['receiver_name'])) array_push($where," ord.receiver_name like '%".$param['receiver_name']."%'");
		if(!_empty($param['sender_name'])) array_push($where," ord.sender_name like '%".$param['sender_name']."%'");

		if(isset($GLOBALS['_VenderInfo']) && _isInt($GLOBALS['_VenderInfo']->getVidx())) array_push($where,"p.vender='".$GLOBALS['_VenderInfo']->getVidx()."'"); //벤더일 경우는 자기꺼만	
		else if(!_empty($param['vender'])) array_push($where," p.vender='".$param['vender']."'");

		$orderby = "ord.ordercode DESC,s.start,s.end";
		if (!_empty($param['orderby']) && $param['orderby'] == 'desc') $orderby = 'ord.ordercode DESC,s.start DESC,s.end';
	
		$sql = "select ord.id,ord.sender_name,ord.sender_email,ord.sender_tel,ord.receiver_name,ord.receiver_tel1,receiver_email,op.price,p.productcode,p.productname,p.pridx,p.tinyimage,o.optionName,o.grade,rp.multiOpt,vi.com_name,vi.com_tel,s.* from rent_schedule s inner join tblorderinfo ord on ord.ordercode=s.ordercode inner join rent_product_option o on o.idx=s.optidx inner join tblproduct p using(pridx) inner join tblvenderinfo vi on p.vender=vi.vender inner join rent_product rp using(pridx) left join tblorderproduct op on op.ordercode=s.ordercode and op.basketidx=s.basketidx where ".implode(' and ',$where)." order by ".$orderby;

		$return = array();
		
		$RES = mysql_query( $sql,self::$conn);
		while ( $ROW = mysql_fetch_assoc( $RES ) ) {
			array_push($return,$ROW);
		}
		return $return;
	}	
	
	
	/**
	mypage 등에서 상태 별 주문 등에 대한 수량을 확인 하기 위해 조건별 수량 count
	@param string userid 사용자 id tblmember.id
	@return array 상태 의미를 나타내는 문자열을 key 로 가지고 해당 상태에 해당하는 주문 수를 값으로 가지는 연관 배열
	*/	
	static public function getCount($userid){
		$rentCount = array();
		if(!_empty($userid)){
			// 가예약 (실제 예약은 하지 않고 가예약 처리 -> 24시간까지 유효함)
			$rsql['booking_temp'] = "select count(*) from rent_schedule s left join tblbasket_normal bn on bn.basketidx=s.basketidx where bn.memid='".$userid."' and s.status='NN' and NOW() between s.regDate and DATE_ADD(s.regDate, interval ".self::$NN_limit." hour)";
			// 입금대기 (온라인예약 신청 상태) => 가예약
			$rsql['booking_ready'] = "select count(*) from rent_schedule s left join tblorderinfo o on o.ordercode=s.ordercode  where o.id='".$userid."' and s.status='BR' and NOW() between s.regDate and DATE_ADD(s.regDate, interval ".self::$BR_limit." hour)";
			// 취소 임박 (예약 후 10시간 지나면 가예약 만료까지 2시간 남는 시기)
			$rsql['booking_close_near'] = "select count(*) from rent_schedule s left join tblorderinfo o on o.ordercode=s.ordercode  where o.id='".$userid."' and s.status='BR' and NOW() between DATE_ADD(s.regDate, interval ".(self::$BR_limit-self::$BR_nearlimit)." hour) and DATE_ADD(s.regDate, interval ".self::$BR_limit." hour)";
			// 예약 확정
			$rsql['booking_comp'] ="select count(*) from rent_schedule s left join tblorderinfo o on o.ordercode=s.ordercode  where o.id='".$userid."' and s.status='BO' and s.start > NOW()";
			// 예약 취소
			$rsql['booking_cancle_cur'] = "select count(*) from rent_schedule s left join tblorderinfo o on o.ordercode=s.ordercode where o.id='".$userid."' and ((s.status='BR' and NOW() > DATE_ADD(s.regDate, interval ".self::$BR_limit." hour)) OR ((s.status='BC' or s.status='NC') and s.end >= NOW()))";
			// 지난 예약 취소
			$rsql['booking_cancle_old'] = "select count(*) from rent_schedule s left join tblorderinfo o on o.ordercode=s.ordercode where o.id='".$userid."' and ((s.status='BR' and s.end < NOW()) OR ((s.status='BC' or s.status='NC') and s.end < NOW()))";
			// 렌탈 중
			$rsql['rental'] = "select count(*) from rent_schedule s left join tblorderinfo o on o.ordercode=s.ordercode where o.id='".$userid."' and s.status='BI' and NOW() between s.start AND s.end";
			// 렌탈 반납 임박
			$rsql['rental_close_near'] = "select count(*) from rent_schedule s left join tblorderinfo o on o.ordercode=s.ordercode where o.id='".$userid."' and s.status='BI' and NOW() between DATE_SUB(s.end, interval ".self::$BR_nearlimit." hour) AND s.end";
			// 렌탈 종료
			$rsql['rental_end'] = "select count(*) from rent_schedule s left join tblorderinfo o on o.ordercode=s.ordercode where o.id='".$userid."' and s.status='BE' and s.end <= NOW()";
			// 반납대기
			$rsql['collecting'] = "select count(*) from rent_schedule s left join tblorderinfo o on o.ordercode=s.ordercode where o.id='".$userid."' and s.status='CR' and s.end < NOW()";
			// 반납 완료
			$rsql['rental_comp'] = "select count(*) from rent_schedule s left join tblorderinfo o on o.ordercode=s.ordercode where o.id='".$userid."' and s.status='CE' and s.end < NOW()";
			// 미반납
			$rsql['rental_overtime'] = "select count(*) from rent_schedule s left join tblorderinfo o on o.ordercode=s.ordercode where o.id='".$userid."' and s.status='OT' and s.end < NOW()";
			// 반납불가(파손)
			$rsql['rental_no_return'] = "select count(*) from rent_schedule s left join tblorderinfo o on o.ordercode=s.ordercode where o.id='".$userid."' and s.status='NR' and s.end < NOW()";
			// 정비
			$rsql['repair'] = "select count(*) from rent_schedule s left join tblorderinfo o on o.ordercode=s.ordercode where o.id='".$userid."' and s.status='RP' and s.end < NOW()";

			foreach($rsql as $key=>$sql){
				//echo $sql.' = '.$key.'<br/>';
				$rentCount[$key] = 0;
				if(false !== $res = mysql_query($sql,self::$conn)){
					$rentCount[$key] = mysql_result($res,0,0);
				}
			}
			$rentCount['booking'] = $rentCount['booking_ready'];
			$rentCount['booking_cancle'] = $rentCount['booking_cancle_cur']+$rentCount['booking_cancle_old'];
		}
		return $rentCount;
	}
	
	
	// 옵션 정보 가져 오기
	/**
	옵션 정보 갱신- 상품 옵션 정보 수정 및 삭제 관련 처리
	@param int pridx 상품 고유 식별 번호
	@param array options 상품에 관한 옵션 정보 ( 수량 ,고유번호 등) - 해당 정보를 바탕으로 없을 경우는 삭제를 신규는 추가를 변경은 갱신을 처리함
	@return void
	*/
	static public function updateOptions($pridx,$options=array()){
		$return = array();
		if(_isInt($pridx) && _array($options)){
			$sql = "select idx from rent_product_option where pridx='".$pridx."' order by grade desc ";
			if(false !== $res = mysql_query($sql,self::$conn)){
				$idxs = array();
				if(mysql_num_rows($res)){
					while($row = mysql_fetch_assoc($res)){
						if(_isInt($row['idx'])) array_push($idxs,$row['idx']);
					}
				}
			}
			foreach($options as $option){
				$sql = "set pridx='".$pridx."',grade='"._escape($option['grade'],false)."',optionName='"._escape($option['optionName'],false)."',custPrice='"._escape($option['custPrice'],false)."',priceDiscP='"._escape($option['priceDiscP'],false)."',nomalPrice='"._escape($option['nomalPrice'],false)."',productTimeover_percent='"._escape($option['productTimeover_percent'],false)."',productTimeover_price='"._escape($option['productTimeover_price'],false)."',productHalfday_percent='"._escape($option['productHalfday_percent'],false)."',productHalfday_price='"._escape($option['productHalfday_price'],false)."',productOverHalfTime_percent='"._escape($option['productOverHalfTime_percent'],false)."',productOverHalfTime_price='"._escape($option['productOverHalfTime_price'],false)."',productOverOneTime_percent='"._escape($option['productOverOneTime_percent'],false)."',productOverOneTime_price='"._escape($option['productOverOneTime_price'],false)."',optionPay='"._escape($option['optionPay'],false)."',deposit='"._escape($option['deposit'],false)."',prepay='"._escape($option['prepay'],false)."',busySeason='"._escape($option['busySeason'],false)."',busyHolidaySeason='"._escape($option['busyHolidaySeason'],false)."',semiBusySeason='"._escape($option['semiBusySeason'],false)."',semiBusyHolidaySeason='"._escape($option['semiBusyHolidaySeason'],false)."',holidaySeason='"._escape($option['holidaySeason'],false)."',productCount='"._escape($option['productCount'],false)."'";
				if(_isInt($option['idx'])){
					$sql = "update rent_product_option ".$sql." where idx='".$option['idx']."'";
					if(_array($idxs) && false !== $pos = array_search($option['idx'],$idxs)) unset($idxs[$pos]); // 삭제 대상에서 뺌
				}else{
					$sql = "insert into rent_product_option ".$sql;
				}
				mysql_query($sql,self::$conn);				
			}
			if(_array($idxs)){
				$sql = "delete from rent_product_option where idx in ('".implode("','",$idxs)."') and pridx='".$pridx."'";
				mysql_query($sql,self::$conn);				
			}
		}
	}	

	
	/**
	mypage 등에서 상태 별 주문 등에 대한 수량을 확인 하기 위해 조건별 수량 count
	@param string start 시작일
	@param string end 	종료일
	@param string status 대여상태
	*/
	static public function bookingCount($start,$end,$status){
		$where = array();	

		$stamp = rentProduct::getTimeRange(24,$start,$end);
		if(!_empty($stamp['err'])) return $stamp;
		array_push($where,"s.status='".$status."' ");
		array_push($where,"s.`start` <= '".date('Y-m-d H:i:s',$stamp['rangestamp'][1])."' AND s.`end` >= '".date('Y-m-d H:i:s',$stamp['rangestamp'][0])."'");
		
		if(isset($GLOBALS['_VenderInfo']) && _isInt($GLOBALS['_VenderInfo']->getVidx())) array_push($where,"p.vender='".$GLOBALS['_VenderInfo']->getVidx()."'"); //벤더일 경우는 자기꺼만

		$sql = "select count(*) from rent_schedule s inner join tblorderinfo ord on ord.ordercode=s.ordercode  inner join rent_product_option o on o.idx=s.optidx inner join tblproduct p using(pridx) left join tblorderproduct op on op.ordercode=s.ordercode and op.basketidx=s.basketidx where	".implode(' and ',$where)." order by s.start,s.end";		
	
		$RES = mysql_query( $sql,self::$conn);
		$bookingCount = mysql_result($RES,0,0);

		return $bookingCount;
	
	}

}



// 싱글톤 처리를 위해 객체 생성
rentProduct::_init();
?>