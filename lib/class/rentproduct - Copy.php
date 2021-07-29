<?
class rentProduct{
	static public $existsPridx = array();
	static public $existsPcode = array();	
	static public $codeInfos	= array();
	static public $locations	= array();
	static public $weekendarr = array('sun','sat');
	static public $goodStatus 	= array("S"=>"새제품", "A"=>"A급", "B"=>"B급", "C"=>"C급");

	// 예약 상태 --------------------------------------------------
	// 대여가능 : 대여가능상태 NN (None)
	// 가예약취소 : 대여가능상태 NN (None Cancle)
	// 입금대기 : 미입금 온라인 예약 신청 (무통장 입금대기) BR (Bank Ready)
	// 미입금취소 : 미입금 온라인 예약 신청 취소 (무통장 입금대기 12시간 초과) BC (Bank Cancle)
	// 예약완료 : 바로결제 및 입금확인 예약완료 BO (Booking Ok)
	// 대여중 : 대여진행중 BI (Booking Ing)
	// 반납완료 : 점검전 반납확인 완료 BE (Booking End)
	// 반납안됨 : 대여기간이 지나도 반납 확인 안됨. OT (OverTime)
	// 정비 : 반납확인 후 정비중.(대여 불가능) RP (Repair)
	static public $bookingStatus = array("NN"=>"가예약", "NC"=>"가예약취소", "BC"=>"미입금취소", "BR"=>"입금대기", "BO"=>"예약완료", "BI"=>"대여중", "BE"=>"반납완료", "OT"=>"반납안됨", "RP"=>"정비" );
	static public $BR_limit  = 12;   // 입금대기 시간 (초과시 입금대기 리스트에서 제외)
	static public $rentLocationType = array( "A" => "출고지", "B" => "렌탈" ); // 장소 타입 ( 출고지A, 렌탈B )
	
	static $conn = NULL;	
		
	private function __construct($pridx){
		$this->info=&self::$existsPridx[$product['pridx']];
		$this->pridx = $this->info['pridx'];
		$this->productcode = $this->info['productcode'];
	}
	
	static function _status($key){	
		if(!_empty($key)) return self::$goodStatus[$key];
		else return self::$goodStatus;
	}
	
	static function _weekendVals($key){	
		return self::$weekendarr;
	}
	
	static function _bookingStatus($key){
		if(!_empty($key)) return self::$bookingStatus[$key];
		else return self::$bookingStatus;
	}
	
	static function locationType($type){
		return self::$rentLocationType[$type];
	}
	
	public function _info(){
		return $this->info;
	}
	
	
	static function _addBasket($rpobj,$param=array()){
		
	}
	
	static function _removeBasket($rpobj,$param=array()){
		
	}
	
	static public function _init(){
		if(is_null(self::$conn)) self::$conn = get_db_conn();
		if(gettype(self::$conn) != 'resource' || get_resource_type(self::$conn) != 'mysql link') exit('데이터 베이스 커넥션 오류');
	}
	
	// 상품 정보 로드
	static public function read($codeoridx){
		$pridx = 0;
		$product = NULL;
		
		
		if(preg_match('/^[0-9]{18}$/',$codeoridx) && isset(self::$existsPcode[$codeoridx])) $pridx = $codeoridx;
		else if(_isInt($codeoridx) && isset(self::$existsPridx[$codeoridx])) $pridx = $codeoridx;
		else{			
			if(preg_match('/^[0-9]{18}$/',$codeoridx)){
				$sql = "select * from tblproduct p left join rent_product r using(pridx) where p.productcode='".$codeoridx."' and p.rental='2' limit 1";
				if(false == $res = mysql_query($sql,self::$conn)) exit(mysql_error());
			}
			
			if(!$res && _isInt($codeoridx)){
				$sql = "select * from tblproduct p left join rent_product r using(pridx) where p.pridx='".$codeoridx."' and p.rental='2' limit 1";
				if(false == $res = mysql_query($sql,self::$conn)) exit(mysql_error());
			}
			
			if($res && mysql_num_rows($res)){
				$product = mysql_fetch_assoc($res);
				$product['options'] = self::getoptions($product['pridx']); // 옵션 산출				
				
				$product['schedule'] =$product['optschedulearray'] = array();
				$product['scheduleRange'] = array();
				// 지역정보
				if(!isset(self::$locations[$product['location']])) self::$locations += self::getlocations(array('location'=>$product['location']));								
				$product['locationinfo'] = &self::$locations[$product['location']];
				
								
				self::$existsPridx[$product['pridx']] = $product;
				self::$existsPcode[$product['productcode']] = &self::$existsPridx[$product['pridx']];
				
				
				
				if(!isset(self::$codeInfos[substr($product['productcode'],0,12)])) self::getCodeInfo(substr($product['productcode'],0,12)); // 코드 인포 정리
				self::$existsPridx[$product['pridx']]['codeinfo'] = &self::$codeInfos[substr($product['productcode'],0,12)];
								
				// 옵션 idx 조회용 link
				self::$existsPridx[$product['pridx']]['optkeys'] = array();
				if(_array(self::$existsPridx[$product['pridx']]['options'])){					
					foreach(self::$existsPridx[$product['pridx']]['options'] as $idx=>$oinfo){
						if(self::$codeInfos[substr($product['productcode'],0,12)]['useseason'] != '1') 
							self::$existsPridx[$product['pridx']]['options'][$idx]['busySeason'] = self::$existsPridx[$product['pridx']]['options'][$idx]['semiBusySeason']= self::$existsPridx[$product['pridx']]['options'][$idx]['holidaySeason']  = 0;
							
						self::$existsPridx[$product['pridx']]['optkeys'][$oinfo['idx']] = &self::$existsPridx[$product['pridx']]['options'][$idx];
					}					
				}
				$pridx = $product['pridx'];				
			}
		}
		
		if(_isInt($pridx) && isset(self::$existsPridx[$pridx])) $pinfo = &self::$existsPridx[$pridx];
		else $pinfo = NULL;		
		return $pridx;
	}
	
	
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
	
	
	// 옵션 정보 가져 오기
	static public function getoptions($pridx,$idxiskey=true){
		$return = array();
		if(_isInt($pridx)){
			$sql = "select * from rent_product_option where pridx='".$pridx."' order by grade desc ";
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
	
	// 로케이션 정보 가져 오기
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
	
	static public function schedule(){
		$args = func_get_args();
		if(_empty($args[0]))  return array('err'=>'조회 대상이 지정 되지 않았습니다.'); // 오류
		$codeoridx = $args[0];
		
		if(_empty($args[0]))  return array('err'=>'조회 기간 지정 오류'); // 오류
		$start = $args[1];		
		
		if(isset($args[2])){
			if(!_empty($args[2]) && is_string($args[2])){
				$end = $args[2];				
				if(isset($args[3])){
					if(is_bool($args[3])) $forcereload = $args[3];
					else if(_array($args[3])) $optidxs = $args[3];
				}	
			}else{
				$end = $start;
				if(is_bool($args[3])) $forcereload = $args[2];
				else if(_array($args[2])) $optidxs = $args[2];
			}
		}
		
		if($forcereload !== true) $forcereload = false;		
		
		$return = array('err'=>'','options'=>array(),'rangestamp'=>array(),'timegap'=>24,'schedule'=>array(),'optschedule'=>array());		
		$pridx = self::read($codeoridx);
		$pinfo = &self::$existsPridx[$pridx];

	
		if(!$pinfo) return array('err'=>'렌탈 상품이 아닙니다.-sc'); // 오류		

		$stamp = self::getTimeRange($codeoridx,$start,$end);
		
		if(!_empty($stamp['err'])) return $stamp;
		$return = array_merge($return,$stamp);
		
		$where = array("p.pridx='".$pinfo['pridx']."'");
		array_push($where,"IF( s.status = 'BR', s.regDate >= date_add(now(), interval -12 hour) AND s.status = 'BR', s.status != 'BR' )	");
		
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
			array_push($where,"s.`start` <= '".date('Y-m-d H:i:s',$return['rangestamp'][1])."' AND s.`end` >= '".date('Y-m-d H:i:s',$return['rangestamp'][0])."'");
			$sql = "select s.* from rent_schedule s inner join rent_product_option o on o.idx=s.optidx inner join tblproduct p on (p.pridx=o.pridx) ";	
			$sql .= " where	".implode(' and ',$where)." order by s.start,s.end";	
			
			if(false === $res = mysql_query($sql,self::$conn)) return array('err'=>'DB 질의 오류');// 오류
			if(mysql_num_rows($res)){				
				while($row = mysql_fetch_assoc($res)){			
					$sst = _strtotime($row['start']);				
					$set = _strtotime($row['end'],true);
					
					if(!_isInt($row['optidx'])) {								
						for($st = $return['rangestamp'][0];$st <= $return['rangestamp'][1];$st+= $return['timegap']*3600){						
							$key = date('Y-m-d'.(($tgap == 1)?' H':''),$st);
							if($st < $sst || $st > $set || (!$forcereload && isset(self::$existsPridx[$pinfo['pridx']]['schedule'][$key][$row['optidx']]) && self::$existsPridx[$pinfo['pridx']]['schedule'][$key][$row['optidx']]>0)) continue;
							self::$existsPridx[$pinfo['pridx']]['schedule'][$key][$row['optidx']]+=$row['quantity'];
						}				
					}			
				}			
			}
		}	
		$return['err'] = 'success';
		return $return;		
	}
	
	
	static public function checkRentable($options,&$schedule=array(),$findone=false,$addQuantity=false){
		$return = array('err'=>'','disable'=>array());
	
		if($schedule['err'] != 'success') $return['err'] = $schedule['err'];
		else{		
			foreach($options as $idx=>$cnt){				
				if(isset($schedule['options'][$idx])){
					$opmaxcnt = $schedule['options'][$idx]['productCount'];				
					foreach($schedule['optschedule'][$idx] as $date=>$rentcnt){
						if($cnt+$rentcnt > $opmaxcnt){
							$return['disable'][$date]= max($opmaxcnt-$cnt,0);
							if($findone) break;
						}else if($addQuantity){
							$schedule['optschedule'][$idx][$date] += $cnt;
						}
					}
				}else{
					$return['err'] = '옵션 고유식별 번호가 올바르지 않습니다.';
				}
			}
		}	
		return $return;
	}
	
	static public function checkSeason($pridx,$start,$end){		
		$where = array();
		$return= array('busy'=>array(),'semi'=>array(),'holiday'=>array(),'weekend'=>array());
		
		$pridx = self::read($pridx);
		$pinfo = &self::$existsPridx[$pridx];
		
		if($pinfo){
			if(!_empty($start)) $startstamp =_strtotime($start);
			if(!_empty($end)) $endstamp = _strtotime($end,true);
			else $endstamp = _strtotime($start,true);
	
			if((_empty($startstamp) || _empty($endstamp)) || $startstamp >= $endstamp) return $return;
			
			
			$chkst = strtotime(date('Y-m-d',$startstamp));
			$chked = strtotime(date('Y-m-d',$endstamp));
			
			array_push($where,"`start` <= '".date('Y-m-d',$endstamp)."' AND `end` >= '".date('Y-m-d',$startstamp)."'");				
		
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
			
			// 주말 요금
			$weekend = array();
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
		}		
		return $return;
	}
	
	static public function solvPrice($pridx,$options,$start,$end){
		$return = array('err'=>'','totalprice'=>0,'discprice'=>0,'msg'=>$err,'range'=>$schedule['rangestamp'],'timegap'=>$schedule['timegap'],'discountmsg'=>'');		
		$pridx = self::read($pridx);
		$pinfo = &self::$existsPridx[$pridx];
		if(!$pinfo) return array('err'=>'렌탈 상품이 아닙니다.-sp');

		if(is_string($options) && preg_match('/|[0-9]+/',$options))  $options =  parseRentRequestOption($options);
		if(!_array($options)) return array('err'=>'옵션 전달 오류');
		
		$schedule = rentProduct::schedule($pinfo['pridx'],$start,$end,array_keys($options));				

		$return['range'] = $schedule['rangestamp'];
		$return['timegap'] = $schedule['timegap'];
		$check = self::checkRentable($options,$schedule,true);
		if(!_empty($check['err'])) return array('err'=>$schedule['err']);
		else if(_array($check['disable'])){
			foreach($check['disable'] as $date=>$ablecnt){
				return array('err'=>$date.' 예약 불가('.$ablecnt.' 건예약가능)');
				break;
			}
		}		
		
		if($pinfo['codeinfo']['useseason'] == '1'){
			$season = rentproduct::checkSeason($pridx,$start,$end);
		}
		$return['diff'] = $diff = datediff($end,$start);			
		$longdiscountP = rentLongDiscount($pridx,abs($diff['day']));				

		foreach($options as $optKey => $optCnt ){			
			$pricecnt = array('total'=>count($schedule['optschedule'][$optKey]),'busy'=>0,'semi'=>0,'holiday'=>0);			
			
			foreach($schedule['optschedule'][$optKey] as $datekey=>$scnt){
				if(_array($season)){
					$date = substr($datekey,0,10);
					if(!_empty($season['holiday'][$date])) $pricecnt['holiday']++;						
					else if(in_array($date,$season['busy'])) $pricecnt['busy']++;
					else if(in_array($date,$season['semi'])) $pricecnt['semi']++;						
				}
			}			
			$tmpprice = $pricecnt['total']*$schedule['options'][$optKey]['nomalPrice'] 
						+ $pricecnt['holiday']*$schedule['options'][$optKey]['holidaySeason'] 
						+ $pricecnt['busy']*$schedule['options'][$optKey]['busySeason']
						+ $pricecnt['semi']*$schedule['options'][$optKey]['semiBusySeason'];
			
			$return['totalprice'] += $tmpprice*$optCnt;	
		}
			
		if($longdiscountP > 0){
			$return['discprice'] = -1*floor($return['totalprice']*($longdiscountP/100));
			$return['discountmsg'] = "(장기대여할인 ".$longdiscountP."%)";
		}
		$return['msg'] = 'ok';
		return $return;	
	}
	
	// 시간 범위 timestamp 로 반환
	static public function getTimeRange($pridx,$start,$end){
		$return = array('err'=>'');
		$pridx = self::read($pridx);
		$pinfo = &self::$existsPridx[$pridx];
		if(!$pinfo) return NULL;
		if(!_empty($start)) $startstamp =_strtotime($start);
		if(!_empty($end)) $endstamp = _strtotime($end,true);
		else $endstamp = _strtotime($start,true);
		
		if(_empty($startstamp) || _empty($endstamp)) return array('err'=>'입력 범위 오류-범위값이 지정 되지 않았습니다.'); // 오류
		else if($startstamp >= $endstamp) return array('err'=>'입력 범위 오류-시작일이 종료일과 같거나 큰경우'); // 오류
		
		if($pinfo['codeinfo']['pricetype'] == 'time') $return['timegap'] = 1;
		else $return['timegap'] = 24;
		
		$return['rangestamp'] = array($startstamp,$endstamp);
		return $return;
	}
	
	static public function parseOptionDate($pridx,$options,$start,$end){
		$return = array('err'=>'');
		$pridx = self::read($pridx);
		$pinfo = &self::$existsPridx[$pridx];
		if(!$pinfo) return NULL;
		
		if(is_string($options) && preg_match('/|[0-9]+/',$options))  $options =  parseRentRequestOption($options);
		if(!_array($options)) return array('err'=>'옵션 전달 오류');
		
		$stamp = self::getTimeRange($pridx,$start,$end);
		if(!_empty($stamp['err'])) return array('err'=>$stamp['err']); // 오류
		$return = array_merge($return,$stamp);
		
		$return['items'] = array();
		for($st = $startstamp;$st <= $endstamp;$st+= $return['timegap']*3600){
			$key = date('Y-m-d'.(($tgap == 1)?' H':''),$st);
			$return['items'][$key] = $options;			
		}
	}
	
	static public function readBasket($baskettbl,$tempkey,$pridx=NULL){		
		$return = array('err'=>'','items'=>array());
		$sql =  "SELECT r.*,p.pridx from rent_basket_temp r left join `".$baskettbl."` b on b.basketidx=r.basketidx and b.ordertype=r.ordertype left join tblproduct p on p.productcode = b.productcode WHERE b.tempkey = '".$tempkey."' and p.rental='2' ";
		if(_isInt($pridx)) $sql .= " and p.pridx='".$pridx."' ";
		
		if(false === $res = mysql_query($sql,self::$conn)) return array('err'=>'DB 접속 오류');		
		if(mysql_num_rows($res) <1) return $return;			
		
		while($row= mysql_fetch_assoc($res)){
			if(!isset($return['items'][$row['pridx']])) $return['items'][$row['productcode']] = array();
			array_push($return['items'][$row['pridx']],$row);
		}
		return $return;
	}
	
	
	static public function insertBasket($baskettbl,$tempkey,$pridx,$options=array(),$start,$end){		
		$pridx = self::read($pridx);
		echo $pridx;
		$pinfo = &self::$existsPridx[$pridx];
		_pr($pinfo);
		$return = array('err'=>'ok');
		if($pinfo){
			if(is_string($options) && preg_match('/|[0-9]+/',$options))  $options =  parseRentRequestOption($options);
			if(!_array($options)) return array('err'=>'옵션 전달 오류');
			
			$inbasket = self::readBasket($baskettbl,$tempkey,$pinfo['pridx']);
			if(!_empty($inbasket)) return $inbasket;
			
			$schedule = self::schedule($pinfo['pridx'],$start,$end,array_keys($options),true);
			
			if(_array($inbasket['items'][$pinfo['pridx']])){
				foreach($inbasket['items'][$pinfo['pridx']]  as $basketitems){
					$bitem = array();
					$bitem[$basketitems['optidx']] = $basketitems['quantity'];
					$chk = self::checkRentable($bitem,$schedule,false,true);		
					if(!_empty($chk['err'])) return $chk;
				}
			}
			_pr($options);
			$chk = self::checkRentable($options,$schedule,false,true);		
			_pr($chk);
			if(!_empty($chk['err'])) return $chk;
			else{
				foreach($options as $optidx=>$quantity){					
					$sql = "insert into rent_basket_temp (basketidx,ordertype,sdate,edate,optidx,quantity) values ('".$basket['basketidx']."','".$basket['ordertype']."','".date('Y-m-d H:i:s',$startstamp)."','".date('Y-m-d H:i:s',$endstamp)."','".$optidx."','".$quantity."') ON DUPLICATE KEY UPDATE quantity= quantity+values(quantity)";		
					echo $sql;
					if(false === mysql_query($sql,get_db_conn())) return array('err'=>mysql_error());
				}
				$return['err'] = 'ok';
			}
		}
		return $return;		
	}
}


rentProduct::_init();
?>