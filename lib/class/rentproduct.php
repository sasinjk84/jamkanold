<?
require_once dirname(__FILE__).'/../ext/product_func.php';
class rentProduct{
	static public $existsPridx = array();
	static public $existsPcode = array();	
	static public $codeInfos	= array();
	static public $locations	= array();
	static public $weekendarr = array('sun','sat','fri');
	static public $goodStatus 	= array("S"=>"����ǰ", "A"=>"A��", "B"=>"B��", "C"=>"C��");
	static public $skipendtime = 6;

	// ���� ���� --------------------------------------------------
	// (NN)�뿩���� : �뿩���ɻ��� NN (None)
	// (NC)��������� : �뿩���ɻ��� NC(None Cancle)
	// (BR)�Աݴ�� : ���Ա� �¶��� ���� ��û (������ �Աݴ��) BR (Bank Ready)
	// (BC)���Ա���� : ���Ա� �¶��� ���� ��û ��� (������ �Աݴ�� 12�ð� �ʰ�) BC (Bank Cancle)
	// (BO)����Ϸ� : �ٷΰ��� �� �Ա�Ȯ�� ����Ϸ� BO (Booking Ok)
	// (BI)�뿩�� : �뿩������ BI (Booking Ing)
	// (BE)�뿩�Ϸ� : �뿩�Ϸ� BE (Booking End)
	// (CR)�ݳ���� : �뿩 ������ �ݳ��� CR (Collect Ready)
	// (CE)�ݳ��Ϸ� : ������ �ݳ�Ȯ�� �Ϸ� CE (Collecting End)
	// (NR)�ݳ��Ұ� : �ݳ�Ȯ�� �� ��� ���� �� ��� �ļ�(No Return)
	// (OT)�ݳ��ȵ� : �뿩�Ⱓ�� ������ �ݳ� Ȯ�� �ȵ�. OT (OverTime)
	// (RP)���� : �ݳ�Ȯ�� �� ������.(�뿩 �Ұ���) RP (Repair)
	static public $bookingStatus = array(
		"NN"=>"�뿩����",
		"NC"=>"���������",
		"BC"=>"���Ա����",
		"CC"=>"��������",
		"BR"=>"�Աݴ��",
		"BO"=>"����Ϸ�",
		"BI"=>"�뿩��",
		"BE"=>"�뿩�Ϸ�",
		"CR"=>"�ݳ����",
		"CE"=>"�ݳ��Ϸ�",
		"NR"=>"�ݳ��Ұ�(�ļ�)",
		"OT"=>"�̹ݳ�(�Ⱓ�ʰ�)"/*,
		"RP"=>"����"*/
	);
	static public $NN_limit      = 24;  // ������ ��ȿ �Ⱓ.
	static public $BR_nearlimit  = 2;   // �Աݴ�� �ð� 10�ð��� �Ǹ� �ӹڿ���.
	static public $BR_limit      = 24;  // �Աݴ�� �ð� (�ʰ��� �Աݴ�� ����Ʈ���� ����)
	static public $rentLocationType = array( "A" => "�����", "B" => "��Ż" ); // ��� Ÿ�� ( �����A, ��ŻB )

	static $conn = NULL;
	private $pridx=NULL;
	private $productcode=NULL;
	private $info = NULL;
	
	/*
	singleton ó��
	*/
	private function __construct($pridx){
		$this->info=&self::$existsPridx[$product['pridx']];
		$this->pridx = $this->info['pridx'];
		$this->productcode = $this->info['productcode'];
	}
	
	/**
	��ǰ(�ɼ�)���°� �ڵ忡 ���� ���� �ؽ�Ʈ ��ȯ
	@param : key goodStatus �迭 �� ��Ī �Ǵ� key��
	@return key �� ��Ī �� ���� �ش� key �� ���� �ؽ�Ʈ, ��Ī �Ǵ� ���� ���� ���� goodStatus �迭 ��ü�� ��ȯ
	*/
	static function _status($key){	
		if(!_empty($key)) return self::$goodStatus[$key];
		else return self::$goodStatus;
	}
	
	/*
	�ָ� ó���� weekendarr ��ȯ - �߰� �� �ش� ������ �׸� �߰�
	*/
	static function _weekendVals($key){	
		return self::$weekendarr;
	}
	
	/**
	���� ���� ���� �ڵ忡 �ش��ϴ� �ؽ�Ʈ ��ȯ
	��Ī ������ ���� ��� ��ü �迭 ��ȯ
	*/
	static function _bookingStatus($key){
		if(!_empty($key)) return self::$bookingStatus[$key];
		else return self::$bookingStatus;
	}
	
	/**
	���� Ÿ��Ű�� ���� �ؽ�Ʈ ��ȯ
	*/
	static function locationType($type){
		return self::$rentLocationType[$type];
	}
	
	/**
	info ���� ����� �ش� �׸��� ���� ��ȯ - ��� ����.
	*/
	public function _info(){
		return $this->info;
	}
	
	// Ŭ���� �ʱ�ȭ - singleton ó���� ���ؼ� ���� ������ �Լ� ���
	static public function _init(){
		if(is_null(self::$conn)) self::$conn = get_db_conn();
		if(gettype(self::$conn) != 'resource' || get_resource_type(self::$conn) != 'mysql link') exit('������ ���̽� Ŀ�ؼ� ����');
		self::cancelAuto(); // ���� �ֹ� �ڵ� ��� ó�� - ���� �ڵ�ȭ ���� ó�� �ʿ�
	}
	
	/**
	2015.07�� ��~ 08�� �� ���� ��û���׿� ���� �ɼ� ���� �κ� ���� ���濡 ���� ���� �׸��� 24�ð� ���� ���ݸ� ������ �߰� �ð� ���� ������ ��ǰ�� ���ؼ��� ���� ���ݿ��� ���� ó��
	24�ð��� �⺻ ������ �ǰ� �߰� 12�ð� �� 24�ð� ������ 70% �׸��� �߰�  1�ð��� �� 24�ð� ������ 1/20 ���� ���
	@param $normalprice - 24�ð� ���� ����
	@param $type - ���� 12 : 12�ð� �߰� , 1: 1�ð� �߰� �׿ܴ� �׳� �⺻ ���� ó��
	@return int ���� ����
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
	��ǰ ���� �ε�
	@param String || int  $codeoridx - ��ǰ���� �ĺ� �ڵ� (18�ڸ�) �Ǵ� ���� �ĺ� ��ȣ (int tblproduct.pridx)
	@return mixed �ش� ��ǰ�� ���� �� ���� �׸� �ɼ� ���������� ��ü ������ ������ ������ �迭 ��ȯ
	*/
	static public function &read($codeoridx){
		global $_ShopInfo;
		$pridx = 0;
		$product = NULL;
		if(preg_match('/^[0-9]{18}$/',$codeoridx) && isset(self::$existsPcode[$codeoridx])) $pridx = self::$existsPcode[$codeoridx];
		else if(_isInt($codeoridx) && isset(self::$existsPridx[$codeoridx])) $pridx = $codeoridx;
		else{			
			if(preg_match('/^[0-9]{18}$/',$codeoridx)){ // ��ǰ �����ڵ�(18) �� ���
				$sql = "select p.productcode,p.productname,p.sellprice as prdprice,p.vender,p.today_reserve,p.deli_type,r.* from tblproduct p left join rent_product r using(pridx) where p.productcode='".$codeoridx."' and p.rental='2' limit 1";
				if(false === $res = mysql_query($sql,self::$conn)) exit(mysql_error());	
			}			
			
			if(!$res && _isInt($codeoridx)){ // ��ǰ ���� �ĺ� ��ȣ �� ���
				$sql = "select p.productcode,p.productname,p.sellprice as prdprice,p.vender,p.today_reserve,p.deli_type,r.* from tblproduct p left join rent_product r using(pridx) where p.pridx='".$codeoridx."' and p.rental='2' limit 1";
				if(false === $res = mysql_query($sql,self::$conn)) exit(mysql_error());
			}
		
			if($res && mysql_num_rows($res)){
				$product = mysql_fetch_assoc($res);
				// �׷캰 ���� ����
				$product['gdiscount'] = getMygroupDiscount($product['productcode']);				
			//	_pr($product['gdiscount']);
				// �ɼ� ����
				$product['options'] = self::getoptions($product['pridx']); 
				foreach($product['options'] as $idx=>$optv){
					$product['options'][$idx]['halfPrice'] = self::calcPriceByNormal($optv['nomalPrice'],'12');
				}
				// ���� ���� ���� ���� �ʱ�ȭ
				$product['schedule']=$product['optschedulearray'] = array();
				$product['scheduleRange'] = array();
				
				// ��������
				if(!isset(self::$locations[$product['location']])) self::$locations += self::getlocations(array('location'=>$product['location']));								
				$product['locationinfo'] = &self::$locations[$product['location']];
				
								
				self::$existsPridx[$product['pridx']] = $product;
				self::$existsPcode[$product['productcode']] = $product['pridx'];
				
				
				// ī�װ��� �Ҵ�� ���� ��ȸ:gura - ������ü���������� �ִ°��
				self::getVenderRent($product['vender'],$product['pridx'],substr($product['productcode'],0,12)); 
				self::$existsPridx[$product['pridx']]['codeinfo'] = &self::$codeInfos[substr($product['productcode'],0,12)];
				
				if(!isset(self::$existsPridx[$product['pridx']]['codeinfo'])){
					if(!isset(self::$codeInfos[substr($product['productcode'],0,12)])) self::getCodeInfo(substr($product['productcode'],0,12)); 
					self::$existsPridx[$product['pridx']]['codeinfo'] = &self::$codeInfos[substr($product['productcode'],0,12)];
				}
						
				// �ɼ� idx ��ȸ�� link
				self::$existsPridx[$product['pridx']]['optkeys'] = array();
				if(_array(self::$existsPridx[$product['pridx']]['options'])){	
					/*������ �������ΰ�� 
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
	ī�װ� Ȯ�� ���� ȣ��
	@param string $code ��� ī�װ� �ڵ� (12�ڸ�)
	@param bool $forcereload ĳ�� ���� �� ���� ��� ������ �ʱ�ȭ ���� (�⺻ flase - ȣ�� ������ ���� ��� �׳� �����)
	@return array Ȯ�� ������ ���� ���� �迭 ��ȯ
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
	��ǰ�� �ɼ� ���� ȣ��
	@param int pridx (tblproduct.pridx) ��ǰ ���� �ĺ� ��ȣ
	@param bool $idxiskey �ɼ� idx �� Ű�� ������ ���� �迭���� ��ȯ ���� ( �⺻ true ��ȯ �迭�� key �� �ɼ��� ���� �ĺ� ��ȣ�� ����)
	@return array �ɼ� ������ ������ ������ ���� �迭 ��ȯ (idxiskey �� ���� ���� ���� �迭�� key �� idx �� ��� ������� ������)
	*/
	static public function getoptions($pridx,$idxiskey=true){
		$return = array();
		if(_isInt($pridx)){
			//$sql = "select * from rent_product_option where pridx='".$pridx."' order by grade asc ";//grade desc->asc ����(����û)
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
	���� ���� ���� ����
	@param array �˻� ��� ���� �迭 ( key : �÷�, value : ��ȸ�� ) ex : array('id'=>'getmall')  �� ��� where �� ������ `id`='getmall' 
	@return array ���ǿ� �ش��ϴ� ���� ���� ���� �迭 ��ȯ ��ȯ �迭�� 1�� key �� location�� ���� �ĺ� ��ȣ�� ����
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
	���� ���� ��ȸ
	@param �ڵ� ���� ����
	@return array ���� ���� �� �ð��� ���� ���� � ���� ���� ���� �迭 ��ȯ
	*/
	static public function schedule(){
		$args = func_get_args();
		
		$return = array('err'=>'','options'=>array(),'rangestamp'=>array(),'timegap'=>24,'schedule'=>array(),'optschedule'=>array());
		
		// ù��° �׸��� ��� ��ǰ�ĺ� ��ȣ �Ǵ� �ڵ�
		if(_empty($args[0]))  return array('err'=>'��ȸ ����� ���� ���� �ʾҽ��ϴ�.'); // ����
		$codeoridx = $args[0];
		
		// �ι�° �׸��� ��ȸ �Ⱓ
		if(_empty($args[1]))  return array('err'=>'��ȸ �Ⱓ ���� ����'); // ����
		$start = $args[1];		
		
		// 3��° �׸��� ���� ���
		if(isset($args[2])){
			if(!_empty($args[2]) && is_string($args[2])){ // ���ڿ� �� ���� �����Ϸ� ó��
				$end = $args[2];				
				if(isset($args[3])){ // �̰�� ���� �׸��� �ɼ� �ĺ� ��ȣ �Ǵ� ���� �ʱ�ȭ ���� 
					if(is_bool($args[3])) $forcereload = $args[3]; // boolean �̸� ���� �ʱ�ȭ
					else if(_array($args[3])) $optidxs = $args[3]; // �迭 �̸� �ɼ� ���� �ĺ� ��ȣ
				}	
			}else{ // ������ ������ ���ϰ� (3��° �׸��� ���ڰ� �ƴ�) ���� ���� ����
				$end = $start; // ������ �� �������� ���� �ϰ� ����
				if(is_bool($args[3])) $forcereload = $args[2];// boolean �̸� ���� �ʱ�ȭ
				else if(_array($args[2])) $optidxs = $args[2];// �迭 �̸� �ɼ� ���� �ĺ� ��ȣ
			}
		}
		
		if($forcereload !== true) $forcereload = false;		
		
		$pinfo = self::read($codeoridx); // ��ǰ ���� ȣ�� ( �޸𸮿� ���� �α⶧���� ���� ��� DB �ߺ� ȣ�� ���� ����
		
		if(!$pinfo) return array('err'=>'��Ż ��ǰ�� �ƴմϴ�.-sc'); // ����		
		if($pinfo['codeinfo']['pricetype'] == 'checkout'){
			//$start = substr($start,0,10).' 14:00:00';
			//$end = substr($end,0,10).' 11:00:00';
			//$start = substr($start,0,10).' '.$pinfo['codeinfo']['checkin_time'].':00:00';
			//$end = substr($end,0,10).' '.$pinfo['codeinfo']['checkout_time'].':00:00';
		}
		
		if($pinfo['codeinfo']['pricetype']!='long'){
			$stamp = self::getTimeRange($pinfo['codeinfo']['pricetype']=='time'?1:24,$start,$end); // �ð� ���� �Ľ� ó�� - ���� �ð��� ���� �ð� �� ī�װ� ���� � ���� �ڵ� ó�� �Ǿ�� �ϴ� �κ��� �־ �Լ��� ó��
			
			if(!_empty($stamp['err'])) return $stamp;
			$return = array_merge($return,$stamp);
			if(_empty($return['rangestamp'][0]) || _empty($return['rangestamp'][0])) return array('err'=>'�Ⱓ ���� ����');
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
			if(false === $res = mysql_query($sql,self::$conn)) return array('err'=>'DB ���� ����');// ����
			if(mysql_num_rows($res)){				
				while($row = mysql_fetch_assoc($res)){			
					$sst = _strtotime($row['start']);				
					$set = _strtotime($row['end'],true);	
					
					if(_isInt($row['optidx'])) {
					
						for($st = $return['rangestamp'][0];$st <= $return['rangestamp'][1];$st+= $return['timegap']*3600){						
							$key = date('Y-m-d'.(($return['timegap'] == 1)?' H':''),$st);
							if($args[3]=="cal"){//��Ż��Ȳ����(�޷�)�� ���
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
	�Է� �Ⱓ�� ���� �Ǵ� ���Ͽ� ���ϴ� ��¥ Ȯ��
	@param int $pridx ��ǰ ���� �ĺ� ��ȣ
	@param string	$start ��ȸ �Ⱓ ������
	@param string	$end ��ȸ �Ⱓ ������
	@return array	��ȸ �Ⱓ�� ���� ���п� ���� ���� ���� �迭 ��ȯ
	*/	
	static public function checkSeason($pridx,$start,$end,$vender){		
		$where = array();
		// busy => ������, semi => �ؼ����� , holiday => ���� , weekend => �ָ�
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
		  
				//������ : ������ü ������ ���� ��� by gura
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
			
			// �ָ� ���
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

			//������ü �ָ������� ���°�� �ٽ� üũ
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
						if(_empty($return['holiday'][$datestr])) $return['holiday'][$datestr] = '�ָ����';					
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
						if(_empty($return['holiday'][$datestr])) $return['holiday'][$datestr] = '�ָ����';					
						array_push($return['weekend'],$datestr);
					}
				}	
			}	

			*/
			
		}		
		return $return;
	}
	
	/**
	����(�ֹ�)�� ���� ���� ����
	@param int pridx ��ǰ ���� �ĺ� ��ȣ tblproduct.pridx
	@param string options �ɼ� ���� ��ȣ �� ���� ���� ���� ���ڿ�
	@param string start ��Ż ������
	@param string end	��Ż ������
	
	@return array �Ⱓ �� �ݾ� ���� ���� ���� ���� ���տ��� �迭 ��ȯ
	*/
	static public function solvPrice($pridx,$options,$start,$end,$vender){
		$return = array('err'=>'','totalprice'=>0,'discprice'=>0,'msg'=>$err,'range'=>$schedule['rangestamp'],'timegap'=>$schedule['timegap'],'discountmsg'=>'','addprice'=>'');		
		$pinfo = self::read($pridx);
		if(!$pinfo) return array('err'=>'��Ż ��ǰ�� �ƴմϴ�.-sp');

		// �ɼ� ���� ���ڿ� �Ľ� �ؼ� ���� ���� ����
		if(is_string($options) && preg_match('/|[0-9]+/',$options))  $options =  parseRentRequestOption($options);		
		if(!_array($options)) return array('err'=>'�ɼ� ���� ����');
		
		if($pinfo['codeinfo']['pricetype']=="long"){

			foreach($options as $optidx => $optCnt){
				$sql = "select * from rent_product_option where idx='".$optidx."' and pridx='".$pridx."' ";
				
				if(false === $res = mysql_query($sql,self::$conn)) return array('err'=>'DB ���� ����');// ����
				if(mysql_num_rows($res)){
					while($row = mysql_fetch_assoc($res)){
						if(_isInt($row['idx'])) {							
							if($row['optionPay']=="�Ͻó�"){
								$tmpprice = $row['nomalPrice']+$row['prepay'];
							}else{
								$tmpprice = ($row['nomalPrice']/$row['optionName'])+$row['prepay'];
							}
						}
						$totalprice = $row['nomalPrice'];
					}
				}
				// ȸ�� �������� ��� ����
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

			// ������ ���� Ȯ��	
			$schedule = rentProduct::schedule($pinfo['pridx'],$start,$end,array_keys($options));				
			
			$return['range'] = $schedule['rangestamp'];
			$return['timegap'] = $schedule['timegap'];
			
			$start = date('Y-m-d H:i:s',$return['range'][0]);
			$end = date('Y-m-d H:i:s',$return['range'][1]);
			
			// schedule �� �������� ��Ż ���� ���� ��ȸ
			$check = self::checkRentable($options,$schedule,true);
	//return array('err'=>$check['err']);
			

			foreach($options as $idx=>$cnt){	
				if(isset($schedule['options'][$idx])){
					$opmaxcnt = $schedule['options'][$idx]['productCount'];	
					
					foreach($schedule['optschedule'][$idx] as $date=>$rentcnt){	
						$return["opt_cnt"][$idx] = max($opmaxcnt-$rentcnt,0);
						
					}
				}else{
					$return['err'] = '�ɼ� �����ĺ� ��ȣ�� �ùٸ��� �ʽ��ϴ�.';
				}
			}

			if(!_empty($check['err'])) return array('err'=>$check['err']);
			else if(_array($check['disable'])){
				foreach($check['disable'] as $date=>$ablecnt){
					//$return['err'] = $date.' ���� �Ұ�('.$ablecnt.' �ǿ��డ��)';
					//return array('err'=>$date.' ���� �Ұ�('.$ablecnt.' �ǿ��డ��)');
					//break;
				}
			}

//return array('err'=>$pinfo['codeinfo']['useseason']);
		
			if($pinfo['codeinfo']['useseason'] == '1'){ // ���� ����� ��� ���� �Ⱓ ��ȸ
				$season = rentproduct::checkSeason($pridx,$start,$end,$vender);
			}
			$return['diff'] = $diff = datediff_rent($end,$start);
			if($pinfo['codeinfo']['pricetype'] =='checkout'){
				$diff['day']+=1;
				$return['diff']['day'] = $diff['day'];
				$return['diff']['hour'] = 0;
			}else if($pinfo['codeinfo']['pricetype'] =='time'){
				if($diff['day']*24+$diff['hour'] <$pinfo['codeinfo']['base_time']) return array('err'=>'�ּ� ��Ż �ð��� '.$pinfo['codeinfo']['base_time'].'�ð� �Դϴ�.');
				foreach($schedule['options'] as $optkey=>$val){
					if(!_isInt($val['nomalPrice'])) return array('err'=>'���� ���� ����');

					$schedule['options'][$optkey]['halfPrice'] = intval(round($val['nomalPrice']*($pinfo['codeinfo']['halfday_percent']/100)));
					$schedule['options'][$optkey]['timePrice'] = intval(round($val['nomalPrice']*($pinfo['codeinfo']['time_percent']/100)));
				}
			}else{
				foreach($schedule['options'] as $optkey=>$val){
					if(!_isInt($val['nomalPrice'])) return array('err'=>'���� ���� ����');
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
				
				if($pinfo['codeinfo']['useseason'] == '0'){//�����������ϴ� ���
					$sdateCnt['normal'] = $edateCnt['normal'] = $pricecnt['total'];
				}

				$return['addprice'] = 0;
				/*************************** 1�ð����ΰ�� ************************************/
				if($pinfo['codeinfo']['pricetype'] =='time'){
					
					$diff_day = $diff['day'];
					if($pinfo['multiOpt']==1){//���տɼ��� ��� �ð��ʰ��ݾ� �ɼǰ������� ����
						$schedule['options'][$optKey]['productTimeover_price'] = $schedule['options'][$optKey]['nomalPrice'];
					}

					$realprice = $schedule['options'][$optKey]['nomalPrice'];
					$realprice += floor(($pricecnt['total']-$pinfo['codeinfo']['base_time']) * $schedule['options'][$optKey]['productTimeover_price']);

					//return array('err'=>$pricecnt['total']);

					$tmpprice = $schedule['options'][$optKey]['nomalPrice'];
					if($schedule['options'][$optKey]['priceDiscP']>0){//�Ϲݰ� ������ �ִ� ��� �߰��ð��� �ݾ׵� ���� ����
						$schedule['options'][$optKey]['productTimeover_price'] = $schedule['options'][$optKey]['productTimeover_price']-($schedule['options'][$optKey]['productTimeover_price']*$schedule['options'][$optKey]['priceDiscP']/100);
					}

					//1�ð��� :::: ������*�ָ�������
					if($pricecnt['busyHoli'] > 0){
						if($sdateCnt['busyHoli']>0){//ù �������� ������*�ָ��������ΰ��
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
						
						$return['discountmsg'] .= "������*�ָ������� ".$schedule['options'][$optKey]['busyHolidaySeason']."% ����";

						$return['addprice'] = $schedule['options'][$optKey]['busyHolidaySeason'];
					}

					//1�ð��� :::: ������*����
					if($pricecnt['busy'] > 0){
						if($sdateCnt['busy']>0){//ù �������� ������*�����ΰ��
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
						$return['discountmsg'] .= "������*���� ".$schedule['options'][$optKey]['busySeason']."% ����";
						$return['addprice'] = $schedule['options'][$optKey]['busySeason'];
					}
					
					//1�ð��� :::: �ؼ�����*�ָ�������
					if($pricecnt['semiHoli'] > 0){
						if($sdateCnt['semiHoli']>0){//ù �������� �ؼ�����*�ָ��������ΰ��
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
						$return['discountmsg'] .= "�ؼ�����*�ָ������� ".$schedule['options'][$optKey]['semiBusyHolidaySeason']."% ����";
						$return['addprice'] = $schedule['options'][$optKey]['semiBusyHolidaySeason'];
					}

					//1�ð��� :::: �ؼ�����*����
					if($pricecnt['semi'] > 0){
						if($sdateCnt['semi']>0){//ù �������� �ؼ�����*�����ΰ��
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
						$return['discountmsg'] .= "�ؼ�����*���� ".$schedule['options'][$optKey]['semiBusySeason']."% ����";
						$return['addprice'] = $schedule['options'][$optKey]['semiBusySeason'];
					}

					//1�ð��� :::: �񼺼���*�ָ�������
					if($pricecnt['holiday'] > 0){
						if($sdateCnt['holiday']>0){//ù �������� �ָ��ΰ��
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
						$return['discountmsg'] .= "�񼺼���*�ָ������� ".$schedule['options'][$optKey]['holidaySeason']."% ����";
						$return['addprice'] = $schedule['options'][$optKey]['holidaySeason'];
					}

					$restTime = $pricecnt['total']-$pricecnt['busy']-$pricecnt['busyHoli']-$pricecnt['semiHoli']-$pricecnt['semi']-$pricecnt['holiday'];	

					if($restTime>0){
						if($sdateCnt['normal']>0){//ù �������� �����*�����ΰ��	
							$restTime = $restTime-$pinfo['codeinfo']['base_time'];
						}
						$tmpprice += floor($restTime * $schedule['options'][$optKey]['productTimeover_price']);
						$return['discountmsg'] .= "";
					}

					//$basicprice = $schedule['options'][$optKey]['nomalPrice']*($diff_day+1)*24;
					$basicprice = $schedule['options'][$optKey]['nomalPrice'];
					$basicprice += floor($schedule['options'][$optKey]['productTimeover_price']*(($diff_day+1)*24-$pinfo['codeinfo']['base_time']));

				/********************************* 24�ð����ΰ�� ****************************************/
				}else if($pinfo['codeinfo']['pricetype'] =='day'){
					//$tmpprice = $schedule['options'][$optKey]['nomalPrice'];
					
					//���� 12�ð����
					$pinfo['codeinfo']['halfday_percent'] = $schedule['options'][$optKey]['productHalfday_percent'];
					
					//�ʰ��ð����
					if($pinfo['codeinfo']['oneday_ex']=="half"){
						$pinfo['codeinfo']['time_percent'] = $schedule['options'][$optKey]['productOverHalfTime_percent'];
						$pinfo['codeinfo']['time_price'] = $schedule['options'][$optKey]['productOverHalfTime_price'];
					}else if($pinfo['codeinfo']['oneday_ex']=="time"){
						$pinfo['codeinfo']['time_percent'] = $schedule['options'][$optKey]['productOverOneTime_percent'];
						$pinfo['codeinfo']['time_price'] = $schedule['options'][$optKey]['productOverOneTime_price'];
					}

					//24�ð��� :::: ������*�ָ�������
					if($pricecnt['busyHoli'] > 0){
					
						if($sdateCnt['busyHoli']>0 && $pinfo['codeinfo']['oneday_ex']!="day"){//ù �������� ������*�ָ��������ΰ��
							$tmpprice += floor($schedule['options'][$optKey]['nomalPrice']*$schedule['options'][$optKey]['busyHolidaySeason']/100);
							//$pricecnt['busyHoli'] = $pricecnt['busyHoli'] - 1;	
						}
		
						if($edateCnt['busyHoli']>0 && $diff['hour']>0){ //���������� ��� 
						
							$tmpprice += $schedule['options'][$optKey]['nomalPrice']*($pricecnt['busyHoli']-1);
							$tmpprice += floor($schedule['options'][$optKey]['nomalPrice']*($pricecnt['busyHoli']-1)*$schedule['options'][$optKey]['busyHolidaySeason']/100);
							
							if($pinfo['codeinfo']['oneday_ex']=="time"){//[1�� �ʰ��� ���� ����] 1�ð� ����
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
							}else if($pinfo['codeinfo']['oneday_ex']=="half"){//[1�� �ʰ��� ���� ����] 12�ð� ����
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
							}else{//[1�� �ʰ��� ���� ����] 1�� ����
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

						if($sdateCnt['busyHoli']>0 && $pinfo['codeinfo']['oneday_ex']!="day"){//ù �������� ������*�ָ��������ΰ��
							$pricecnt['busyHoli'] = $pricecnt['busyHoli'] + 1;
						}

						if($return['discountmsg']!=""){
							$return['discountmsg'] .= ",";
						}
						if( $schedule['options'][$optKey]['busyHolidaySeason']!=0)
							$return['discountmsg'] .= "������*�ָ������� ".$schedule['options'][$optKey]['busyHolidaySeason']."% ����";
						
						$return['addprice'] = $schedule['options'][$optKey]['busyHolidaySeason'];
					}

					//24�ð��� :::: ������*����
					if($pricecnt['busy'] > 0){
						if($sdateCnt['busy']>0 && $pinfo['codeinfo']['oneday_ex']!="day"){//ù �������� ������*�����ΰ��
							$tmpprice += floor($schedule['options'][$optKey]['nomalPrice']*$schedule['options'][$optKey]['busySeason']/100);
							//$pricecnt['busy'] = $pricecnt['busy'] - 1;
						}

						if($edateCnt['busy']>0 && $diff['hour']>0){ //���������� ��� 
							$tmpprice += $schedule['options'][$optKey]['nomalPrice']*($pricecnt['busy']-1);
							$tmpprice += floor($schedule['options'][$optKey]['nomalPrice']*($pricecnt['busy']-1)*$schedule['options'][$optKey]['busySeason']/100);
							
							if($pinfo['codeinfo']['oneday_ex']=="time"){//[1�� �ʰ��� ���� ����] 1�ð� ����
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
							}else if($pinfo['codeinfo']['oneday_ex']=="half"){//[1�� �ʰ��� ���� ����] 12�ð� ����
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
							}else{//[1�� �ʰ��� ���� ����] 1�� ����
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
						
						if($sdateCnt['busy']>0 && $pinfo['codeinfo']['oneday_ex']!="day"){//ù �������ΰ��
							$pricecnt['busy'] = $pricecnt['busy'] + 1;
						}
						if($return['discountmsg']!=""){
							$return['discountmsg'] .= ",";
						}
						if( $schedule['options'][$optKey]['busySeason']!=0)
							$return['discountmsg'] .= "������*���� ".$schedule['options'][$optKey]['busySeason']."% ����";

						$return['addprice'] = $schedule['options'][$optKey]['busySeason'];
					}
					
					//24�ð��� :::: �ؼ�����*�ָ�������
					if($pricecnt['semiHoli'] > 0){

						if($sdateCnt['semiHoli']>0 && $pinfo['codeinfo']['oneday_ex']!="day"){//ù �������� �ؼ�����*�ָ��������ΰ��
							$tmpprice += floor($tmpprice*$schedule['options'][$optKey]['semiBusyHolidaySeason']/100);
							///$pricecnt['semiHoli'] = $pricecnt['semiHoli'] - 1;
						}

						if($edateCnt['semiHoli']>0 && $diff['hour']>0){ //���������� ��� 
							$tmpprice += $schedule['options'][$optKey]['nomalPrice']*($pricecnt['semiHoli']-1);
							$tmpprice += floor($schedule['options'][$optKey]['nomalPrice']*($pricecnt['semiHoli']-1)*$schedule['options'][$optKey]['semiBusyHolidaySeason']/100);
							if($pinfo['codeinfo']['oneday_ex']=="time"){//[1�� �ʰ��� ���� ����] 1�ð� ����
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
							}else if($pinfo['codeinfo']['oneday_ex']=="half"){//[1�� �ʰ��� ���� ����] 12�ð� ����
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
							}else{//[1�� �ʰ��� ���� ����] 1�� ����
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

						if($sdateCnt['semiHoli']>0 && $pinfo['codeinfo']['oneday_ex']!="day"){//ù �������ΰ��
							$pricecnt['semiHoli'] = $pricecnt['semiHoli'] + 1;
						}

						if($return['discountmsg']!=""){
							$return['discountmsg'] .= ",";
						}
						if( $schedule['options'][$optKey]['semiBusyHolidaySeason']!=0)
							$return['discountmsg'] .= "�ؼ�����*�ָ������� ".$schedule['options'][$optKey]['semiBusyHolidaySeason']."% ����";

						$return['addprice'] = $schedule['options'][$optKey]['semiBusyHolidaySeason'];
					}

					//24�ð��� :::: �ؼ�����*����
					if($pricecnt['semi'] > 0){

						if($sdateCnt['semi']>0 && $pinfo['codeinfo']['oneday_ex']!="day"){//ù �������� �ؼ�����*�����ΰ��
							$tmpprice += floor($tmpprice*$schedule['options'][$optKey]['semiBusySeason']/100);
							//$pricecnt['semi'] = $pricecnt['semi'] - 1;
						}

						if($edateCnt['semi']>0 && $diff['hour']>0){ //���������� ��� 
							$tmpprice += $schedule['options'][$optKey]['nomalPrice']*($pricecnt['semi']-1);
							$tmpprice += floor($schedule['options'][$optKey]['nomalPrice']*($pricecnt['semi']-1)*$schedule['options'][$optKey]['semiBusySeason']/100);
							
							if($pinfo['codeinfo']['oneday_ex']=="time"){//[1�� �ʰ��� ���� ����] 1�ð� ����
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
							}else if($pinfo['codeinfo']['oneday_ex']=="half"){//[1�� �ʰ��� ���� ����] 12�ð� ����
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
							}else{//[1�� �ʰ��� ���� ����] 1�� ����
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
						
						if($sdateCnt['semi']>0 && $pinfo['codeinfo']['oneday_ex']!="day"){//ù �������ΰ��
							$pricecnt['semi'] = $pricecnt['semi'] + 1;
						}
						
						if($return['discountmsg']!=""){
							$return['discountmsg'] .= ",";
						}
						if( $schedule['options'][$optKey]['semiBusySeason']!=0)
							$return['discountmsg'] .= "�ؼ�����*���� ".$schedule['options'][$optKey]['semiBusySeason']."% ����";

						$return['addprice'] = $schedule['options'][$optKey]['semiBusySeason'];
					}

					//24�ð��� :::: �񼺼���*�ָ�������
					if($pricecnt['holiday'] > 0){

						if($sdateCnt['holiday']>0 && $pricecnt['total']!=$sdateCnt['holiday'] && $pinfo['codeinfo']['oneday_ex']!="day"){//ù �������̺񼺼���*�ָ��������ΰ��
							$tmpprice += floor($tmpprice*$schedule['options'][$optKey]['holidaySeason']/100);
							//$pricecnt['holiday'] = $pricecnt['holiday'] - 1;
						}

						if($edateCnt['holiday']>0 && $diff['hour']>0){ //���������� ��� 
							$tmpprice += $schedule['options'][$optKey]['nomalPrice']*($pricecnt['holiday']-1);
							$tmpprice += floor($schedule['options'][$optKey]['nomalPrice']*($pricecnt['holiday']-1)*$schedule['options'][$optKey]['holidaySeason']/100);
						
							if($pinfo['codeinfo']['oneday_ex']=="time"){//[1�� �ʰ��� ���� ����] 1�ð� ����
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
							}else if($pinfo['codeinfo']['oneday_ex']=="half"){//[1�� �ʰ��� ���� ����] 12�ð� ����
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
							}else{//[1�� �ʰ��� ���� ����] 1�� ����
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

						
						if($sdateCnt['holiday']>0 && $pinfo['codeinfo']['oneday_ex']!="day"){//ù �������ΰ��
							$pricecnt['holiday'] = $pricecnt['holiday'] + 1;
						}

						if($return['discountmsg']!=""){
							$return['discountmsg'] .= ",";
						}
						if( $schedule['options'][$optKey]['holidaySeason']!=0)
							$return['discountmsg'] .= "�񼺼���*�ָ������� ".$schedule['options'][$optKey]['holidaySeason']."% ����";

						$return['addprice'] = $schedule['options'][$optKey]['holidaySeason'];
					}

					$restTime = $pricecnt['total']-$pricecnt['busy']-$pricecnt['busyHoli']-$pricecnt['semiHoli']-$pricecnt['semi']-$pricecnt['holiday'];
//return array('err'=>$edateCnt['normal']);
					if($restTime>0){
						
						if($sdateCnt['normal']>0 && $pricecnt['total']!=$sdateCnt['normal'] && $pinfo['codeinfo']['oneday_ex']!="day"){//ù �������� �����*�����ΰ��
							$restTime = $restTime-1;
						}	

						if($edateCnt['normal']>0 && $diff['hour']>0){ //���������� ��� 
							$tmpprice += $schedule['options'][$optKey]['nomalPrice']*($restTime-1);
							
							if($pinfo['codeinfo']['oneday_ex']=="time"){//[1�� �ʰ��� ���� ����] 1�ð� ����
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
							}else if($pinfo['codeinfo']['oneday_ex']=="half"){//[1�� �ʰ��� ���� ����] 12�ð� ����
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
							}else{//[1�� �ʰ��� ���� ����] 1�� ����
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
							}else if($diff['day']>1 && $diff['hour']==0){//00~00���� ��� 
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
					
			
				/*************************** �ܱ�Ⱓ���ΰ�� ************************************/
				}else if($pinfo['codeinfo']['pricetype'] =='period'){
					$diff_day = $diff['day'];
					$tmpprice = $schedule['options'][$optKey]['nomalPrice'];
					$realprice = $schedule['options'][$optKey]['nomalPrice'];
					$basicprice = $tmpprice;
	
				/*************************** �������ΰ�� ************************************/
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

				// ȸ�� �������� ��� ����
				//$return['member_discount'] = $pinfo['gdiscount']['discount'];
				
				//���հ�ݾ�
				$return['totalprice'] += $tmpprice*$optCnt;	
				$return['basicprice'] += $basicprice*$optCnt;	
				
			}//end foreach

		}//end if
		//�뿩��¥���
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

		// ��� �뿩 ��ȸ
		if($pinfo['codeinfo']['pricetype'] =='period'){
			$longrentP = venderLongrentCharge($vender,$pridx,abs($diff_day));
			if($longrentP < 0){
				$longrentP = rentLongrentCharge($pridx,abs($diff_day));	
			}
		// ��� ���� ��ȸ
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

		//�������
		if($longdiscountP > 0){
			$return['discprice'] += -1*floor($return['totalprice']*($longdiscountP/100));
		}
		if($longdiscountP2 > 0){
			$return['discprice2'] += -1*floor($return['basicprice']*($longdiscountP2/100));
		}

		//return array('err'=>$tmpprice."/".$basicprice."/".$return['discprice2']);
		//$tmpprice(��갡��)�� $basicprice(�⺻����)+���ΰ��ݺ��� ū ���
		if($return['totalprice']>$return['basicprice']+$return['discprice2']){ 
			$priceover = 1;
			if($tmpprice>$basicprice){//$tmpprice(��갡��)�� $basicprice(���ΰ������� �⺻����)�� ū���
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

		
		//���뿩 �߰�����
		$return['longrentmsg'] = "";
		$return['longrent'] = 0;
		if($longrentP > 0){
			$return['longrent'] += floor($return['totalprice']*($longrentP/100));
			$return['longrentmsg'] = $longrentP;
		}

		//ȸ�����αݾ�
		$return['discountprice'] +=($return['totalprice']+$return['discprice']+$return['longrent'])*$return['member_discount'];

		//��ǰ ������ ���� �ݾ�
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

	
	// �ð� ���� timestamp �� ��ȯ
	/**
	�ð� ���� �� ��ȯ ( �Է°��� ���ǿ� ���� ���� ó�� ����)
	@param int timegap ��� ���� �ð�
	@param string start ������
	@param string end	������
	@return array Ÿ�Խ��������� ������ ���� �迭
	*/
	static public function getTimeRange($timegap=24,$start,$end){
		$return = array('err'=>'');		
		
		if(!_empty($start)) $startstamp =_strtotime($start);
		
		//if(!_empty($end)) $endstamp = _strtotime($end);
		//else $endstamp = _strtotime($start,true);

		if(!_empty($end)) $endstamp = _strtotime($end,true);
		else $endstamp = _strtotime($start,true);
		
		if(_empty($startstamp) || _empty($endstamp)) return array('err'=>'�Է� ���� ����-�������� ���� ���� �ʾҽ��ϴ�.'); // ����
		else if($startstamp >= $endstamp) return array('err'=>'�Է� ���� ����-�������� �����ϰ� ���ų� ū���'); // ����
		
		$return['timegap'] = $timegap;
		$return['rangestamp'] = array($startstamp,$endstamp);
		
		return $return;
	}
	
	/**
	�ɼ� ���ڿ� �� �Ľ��ؼ� ������ �� ������ ������ ���� �迭 ��ȯ
	
	@param int pridx ��ǰ �ĺ� ���� ��ȣ
	@param string options �ɼ� �� ���� ���� ���� ���ڿ�
	@param string start ������
	@param string end ������
	
	@return array �ɼ� �� ���� �ð��� ���� ���� ���� �迭 ��ȯ
	*/
	static public function parseOptionDate($pridx,$options,$start,$end){
		$return = array('err'=>'');
		$pinfo = self::read($pridx);
		if(!$pinfo) return NULL;
		
		if(is_string($options) && preg_match('/|[0-9]+/',$options))  $options =  parseRentRequestOption($options);
		if(!_array($options)) return array('err'=>'�ɼ� ���� ����');
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
		if(!_empty($stamp['err'])) return array('err'=>$stamp['err']); // ����
		$return = array_merge($return,$stamp);
		
		$return['items'] = array();
				
		for($st = $return['rangestamp'][0];$st <= $return['rangestamp'][1];$st+= $return['timegap']*3600){
			$key = date('Y-m-d'.(($return['timegap'] == 1)?' H':''),$st);			
			$return['items'][$key] = $options;			
		}
		return $return;
	}
	
	/**
	��ٱ��� ��� �׸� ��ȸ
	@param string baskettbl ��ٱ��� Ÿ�Կ� ���� ���̺� 
	@param string tempkey	��ٱ��� �ĺ� �� ���ڿ�
	@param int	pridx	[����] ��ǰ ���� �ĺ� ��ȣ
	@param int basketidx	[����] ��ٱ��� ��ǰ �׸� ���� �ĺ� ��ȣ
	@return array �׸� �� �� �׸� ���� �� �� ���� ���� ���� �迭 ��ȯ
	*/	
	static public function readBasket($baskettbl,$tempkey,$pridx=NULL,$basketidx=NULL,$memid=""){		
		$return = array('err'=>'','items'=>array());	

		if(strlen($memid)==0) {	//��ȸ��
			$basketWhere = "tempkey='".$tempkey."' and memid=''";
		}else{
			$basketWhere = "memid='".$memid."'";
		}

		$sql =  "SELECT r.*,b.basketidx,b.ordertype,b.reservationCode,p.pridx from rent_basket_temp r  inner join `".$baskettbl."` b on r.basketidx = b.basketidx and r.ordertype = b.ordertype left join tblproduct p on p.productcode = b.productcode WHERE b.".$basketWhere." and p.rental='2' ";
						
		if(_isInt($pridx)) $sql .= " and p.pridx='".$pridx."' ";
		if(_isInt($basketidx)) $sql .= " and b.basketidx='".$basketidx."' ";
	
		if(false === $res = mysql_query($sql,self::$conn)) return array('err'=>'DB ���� ����');	
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
	Ư�� �ɼ��� Ư�� �Ⱓ���� �뿩 ���������� ���� Ȯ��
	@param array options ���� �Ϸ��� �ɼ� �� ���� �ð� � ���� ���� ���� �迭
	@param array schedule ��ǰ ������ ���� ���� ���⸦ ���� ���� ���� ���� ���� �迭
	@param bool findone  1���� �Ұ� �׸� �߽߰� �������� (�⺻ false ��ü �׸� ���ؼ� Ȯ�� true �� ��� �ɼ��� �ϳ��� ���� �Ұ������� �߻� �ؼ� ���� ��Ŵ
	@return array ���� �޽��� �� �Ұ� ���ڿ� ���� ���� �迭
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
						if(substr($date,0,13) <= date('Y-m-d H')){//���Ͽ����ΰ��
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
					$return['err'] = '�ɼ� �����ĺ� ��ȣ�� �ùٸ��� �ʽ��ϴ�.';
				}
			}
		}		
		return $return;
	}
	
	/**
	���� ��û�� ���� ��û ���� �迭 ����
	@param &array ���� ��Ȳ�� ���� �迭 ���ں�,�ɼǺ� �ð� �� ���� ���� ���� � ���� ���� ������ �迭
	@param int pridx ��ǰ ���� �ĺ� ��ȣ tblproduct.pridx
	@param string options �ɼǰ��� ��ȣ �� ��û ������ ���� ���� ���ڿ�
	@param string start ������
	@param string end	������
	@return bool true || array ������ ���� ���� ���� �޽����� ������ �迭 ��ȯ
	*/
	static private function solvReqArr(&$rsvArr,$pridx,$options,$start,$end){
		if(!_isInt($pridx)) return array('err'=>'��ǰ ���� �Ľ� ����');
		if(!_array($options)) return array('err'=>'��ǰ �ɼ� ���� �Ľ� ����');
		
		$tmp = array();
		$tmp = self::parseOptionDate($pridx,$options,$start,$end);
		
		if(!_empty($tmp['err'])) return $tmp;
		if(!_array($tmp['items'])) return array('err'=>'������ �Ľ� ����');	
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
		self::schedule($pridx,$start,$end,array_keys($options)); // ������ ���� �ε�
		return true;
	}
	
	
	/**
	��ٱ��� ���(�ֱ�)
	@param string ordertype �ֹ� ���� ( ��� ���� ��)
	@param string tempkey ��ٱ��� Ȯ�ο� �ĺ� ���ڿ�
	@param int pridx ��ǰ ���� �ĺ� ��ȣ tblproduct.pridx
	@param string options �ɼ� �ĺ� ��ȣ �� ��û ���� � ���� ���չ��ڿ� 
	@param string start ������
	@param string end	������
	
	@return array ���� ���� �� ���н� ���� �޽����� ������ ���� �迭
	*/
	static public function insertBasket($ordertype,$delitype,$tempkey,$pridx,$options=array(),$start,$end,$folder=0){
		global $_ShopInfo;
		$pinfo = self::read($pridx);	
		$return = array('err'=>'ok');
		$baskettbl = basketTable($ordertype);
		
		if($pinfo && (!_empty($baskettbl) || $ordertype == 'recommandnow')){
			if(is_string($options) && preg_match('/|[0-9]+/',$options))  $options =  parseRentRequestOption($options);
			if(!_array($options)) return array('err'=>'�ɼ� ���� ����');
			
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
				if(!_array($rsvArr)) return array('err'=>'�뿩���� ���� Ȯ�� ����');
				$schedules = &self::$existsPridx[$pinfo['pridx']]['schedule'];
				$optinfo = &self::$existsPridx[$pinfo['pridx']]['options']; 
				//echo $start."/".$end;exit;
				foreach($rsvArr as $date=>$roptval){
					if(!isset($schedules[$date])) return array('err'=>'��Ż ���� �Ⱓ ��ȸ ����a');
					foreach($roptval as $optidx=>$qty){
						if(!isset($schedules[$date][$optidx])) return array('err'=>'��Ż ���� �Ⱓ ��ȸ ����b');
						if($optinfo[$optidx]['productCount'] - $schedules[$date][$optidx] - $qty <0){
							return array('err'=>$date.' �� ���� ������ �Ұ��մϴ�.');
						}
					}
				}
			}
		
			$stamp = self::getTimeRange($pinfo['codeinfo']['pricetype']=='time'?1:24,$start,$end);		
			
			if($pinfo['codeinfo']['pricetype'] != 'long'){
				if($pinfo['codeinfo']['pricetype'] == 'time'){
					if($stamp['rangestamp'][0] < time()-3600 || $stamp['rangestamp'][1] < time()-3600) return array('err'=>'����ð� ���� ����');
				}else if($stamp['rangestamp'][0] < time()-24*3600 || $stamp['rangestamp'][1] < time()-24*3600) return array('err'=>'����ð� ���� ����2');
				
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
					if(false === $res = mysql_query($sql,self::$conn)) return array('err'=>'DB ���� #2');
					$indata['basketidx'] = mysql_insert_id(self::$conn);
					$sql = "insert into rent_basket_temp (basketidx,ordertype,start,end,optidx,quantity,deli_type) values ('".$indata['basketidx']."','recommand','".$indata['start']."','".$indata['end']."','".$indata['optidx']."','".$indata['quantity']."','".$indata['delitype']."') ON DUPLICATE KEY UPDATE quantity=values(quantity),start=values(start),end=values(end),optidx=values(optidx)";
				}else{
					if(_isInt($indata['basketidx'])){
						$sql = "insert into rent_basket_temp (basketidx,ordertype,start,end,optidx,quantity,deli_type) values ('".$indata['basketidx']."','".$indata['ordertype']."','".$indata['start']."','".$indata['end']."','".$indata['optidx']."','".$indata['quantity']."','".$indata['delitype']."') ON DUPLICATE KEY UPDATE quantity=quantity+values(quantity),start=values(start),end=values(end),optidx=values(optidx) ";
	//					$sql = "insert into rent_basket_temp ( set quantity=quantity+'".$indata['quantity']."' where basketidx='".$indata['basketidx']."' and ordertype='".$ordertype."'";
					}else{
						$sql = "select max(opt1_idx) from ".$baskettbl." where tempkey='".$tempkey."' and productcode='".$pinfo['productcode']."'";
						$tmpidx=1;
						if(false === $res = mysql_query($sql,self::$conn)) return array('err'=>'DB ���� #1');
						if(mysql_num_rows($res)) $tmpidx = mysql_result($res,0,0)+1;
						
						$sql = "insert into ".$baskettbl." set tempkey='".$tempkey."', productcode='".$pinfo['productcode']."',opt1_idx='".$tmpidx."',opt2_idx='',optidxs='',quantity='".$indata['quantity']."',deli_type='".$indata['delitype']."',memid= '".$indata['memid']."',date= '".date('YmdHis')."',ordertype='".$ordertype."',folder='".$indata['folder']."' ";

						if(false === $res = mysql_query($sql,self::$conn)) return array('err'=>'DB ���� #2');
						$indata['basketidx'] = mysql_insert_id(self::$conn);
						$sql = "insert into rent_basket_temp (basketidx,ordertype,start,end,optidx,quantity,deli_type) values ('".$indata['basketidx']."','".$indata['ordertype']."','".$indata['start']."','".$indata['end']."','".$indata['optidx']."','".$indata['quantity']."','".$indata['delitype']."') ON DUPLICATE KEY UPDATE quantity=values(quantity),start=values(start),end=values(end),optidx=values(optidx)";				
					}
					if(false === mysql_query($sql,self::$conn)){
						return array('err'=>'DB ��� ����');
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
	�ڵ� ���� ó���� ��ó�� �ֹ� �ڵ� ��� ó��
	������ BR_limit �� ó�� ���� ���� ��û�� ���� ��� ó�� ���� ����
	Todo : �޽��� �ڵ� �߼� ��� �� ���� �ֹ��� ���� �˶� ��� ���� ó��
			cron ��� ���� ���� �ڵ� ȣ�� ����� ���� ó��
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
					// �ļ� ��ġ�� �ʿ��� �κп� ���ؼ� ó��				
					if($row['reserve']>0 && substr($row['id'],-1,1) != 'X'){//������ ȯ��
						@mysql_query("UPDATE tblmember SET reserve=reserve+".$row['reserve']." WHERE id='".$row['id']."'",self::$conn);
						$reserve_restore_sql = "INSERT tblreserve SET id = '".$row['id']."', reserve = '".abs($row['reserve'])."' , reserve_yn = 'Y', content='�ֹ���� ���� ������ ����', orderdata = '".$row['ordercode']."', date='".date('YmdHis')."' ";
						@mysql_query($reserve_restore_sql,self::$conn);
					}
					@mysql_query("update tblorderinfo set deli_gbn='C' where ordercode='".$row['ordercode']."'",self::$conn);	
						
				}
			}
		}

		// �ڵ�ȭ �۾� ó��
		// 1. ����Ϸ� ���¿��� ��Ż ������ �ǰ� ������ ������ �뿩������ ����.
		$sql = "UPDATE rent_schedule SET status='BI' WHERE status='BO' AND NOW() BETWEEN start AND end";
		mysql_query($sql,self::$conn);
		// select count(*) from rent_schedule s left join tblorderinfo o on o.ordercode=s.ordercode where o.id='".$userid."' and s.status='BI' and s.end <= NOW()
		// 2. �뿩�� ���¿��� ����ð��� �Ǹ� �뿩�������� ����
		$sql = "UPDATE rent_schedule SET status='BE' WHERE status='BI' and end <= NOW()";
		mysql_query($sql,self::$conn);
		
	}
	
	/**
	Ư�� �Ⱓ�� ��Ż �ֹ��� ���� ���� ȣ�� ( ������ ���� �ش� ������ �ֹ� �� ȣ��
	@param string start ������
	@param string end 	������
	@param int pridx [�ɼ�] Ư�� ��ǰ�� Ȯ�� �� ��� �ش� ��ǰ�� ���� �ĺ� ��ȣ
	@return array �ֹ� ������ �ɼǺ� �Ⱓ�� ���� � ���� ������ ������ ���� ������ �迭
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
			
			if(isset($GLOBALS['_VenderInfo']) && _isInt($GLOBALS['_VenderInfo']->getVidx())) array_push($where,"p.vender='".$GLOBALS['_VenderInfo']->getVidx()."'"); //������ ���� �ڱⲨ��

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
	���ǿ� ���� �ֹ� ���� ��ȸ
	@param array param ��� �÷����� key �� ������ �˻� ���� value�� ������ ���� �迭
	@return array ��ȸ ������ ���� ���� �������迭
	*/
	static public function searchOrder($param=array()){
		$where = array();	
		if(_array($param['status'])) {
			if (in_array("BNC", $param['status'])) {
				// BNC ��Ż ���� �ӹ� - ���� �ڵ� ��� ������ ã�ƾ� ��. �̰� ���� �迭�� ���°� �־�� ��.
				array_push($where, "s.status='BI' and NOW() between DATE_SUB(s.end, interval ".self::$BR_nearlimit." hour) AND s.end");
			} elseif(in_array("BRA", $param['status'])) {
				// ���� �Աݴ��
				array_push($where, "s.status='BR' and NOW() between s.regDate and DATE_ADD(s.regDate, interval ".self::$BR_limit." hour)");
			} elseif(in_array("BRB", $param['status'])) {
				// ����ӹ� ����
				array_push($where, "s.status='BR' and NOW() between DATE_ADD(s.regDate, interval ".(self::$BR_limit-self::$BR_nearlimit)." hour) and DATE_ADD(s.regDate, interval ".self::$BR_limit." hour)");
			} elseif(in_array("BRC", $param['status'])) {
				// ��� ����
				array_push($where, "((s.status='BR' and NOW() > DATE_ADD(s.regDate, interval ".self::$BR_limit." hour)) OR ((s.status='BC' or s.status='NC') and s.end >= NOW()))");
			} elseif(in_array("BRD", $param['status'])) {
				// ���� ��� ����
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

		if(isset($GLOBALS['_VenderInfo']) && _isInt($GLOBALS['_VenderInfo']->getVidx())) array_push($where,"p.vender='".$GLOBALS['_VenderInfo']->getVidx()."'"); //������ ���� �ڱⲨ��	
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
	mypage ��� ���� �� �ֹ� � ���� ������ Ȯ�� �ϱ� ���� ���Ǻ� ���� count
	@param string userid ����� id tblmember.id
	@return array ���� �ǹ̸� ��Ÿ���� ���ڿ��� key �� ������ �ش� ���¿� �ش��ϴ� �ֹ� ���� ������ ������ ���� �迭
	*/	
	static public function getCount($userid){
		$rentCount = array();
		if(!_empty($userid)){
			// ������ (���� ������ ���� �ʰ� ������ ó�� -> 24�ð����� ��ȿ��)
			$rsql['booking_temp'] = "select count(*) from rent_schedule s left join tblbasket_normal bn on bn.basketidx=s.basketidx where bn.memid='".$userid."' and s.status='NN' and NOW() between s.regDate and DATE_ADD(s.regDate, interval ".self::$NN_limit." hour)";
			// �Աݴ�� (�¶��ο��� ��û ����) => ������
			$rsql['booking_ready'] = "select count(*) from rent_schedule s left join tblorderinfo o on o.ordercode=s.ordercode  where o.id='".$userid."' and s.status='BR' and NOW() between s.regDate and DATE_ADD(s.regDate, interval ".self::$BR_limit." hour)";
			// ��� �ӹ� (���� �� 10�ð� ������ ������ ������� 2�ð� ���� �ñ�)
			$rsql['booking_close_near'] = "select count(*) from rent_schedule s left join tblorderinfo o on o.ordercode=s.ordercode  where o.id='".$userid."' and s.status='BR' and NOW() between DATE_ADD(s.regDate, interval ".(self::$BR_limit-self::$BR_nearlimit)." hour) and DATE_ADD(s.regDate, interval ".self::$BR_limit." hour)";
			// ���� Ȯ��
			$rsql['booking_comp'] ="select count(*) from rent_schedule s left join tblorderinfo o on o.ordercode=s.ordercode  where o.id='".$userid."' and s.status='BO' and s.start > NOW()";
			// ���� ���
			$rsql['booking_cancle_cur'] = "select count(*) from rent_schedule s left join tblorderinfo o on o.ordercode=s.ordercode where o.id='".$userid."' and ((s.status='BR' and NOW() > DATE_ADD(s.regDate, interval ".self::$BR_limit." hour)) OR ((s.status='BC' or s.status='NC') and s.end >= NOW()))";
			// ���� ���� ���
			$rsql['booking_cancle_old'] = "select count(*) from rent_schedule s left join tblorderinfo o on o.ordercode=s.ordercode where o.id='".$userid."' and ((s.status='BR' and s.end < NOW()) OR ((s.status='BC' or s.status='NC') and s.end < NOW()))";
			// ��Ż ��
			$rsql['rental'] = "select count(*) from rent_schedule s left join tblorderinfo o on o.ordercode=s.ordercode where o.id='".$userid."' and s.status='BI' and NOW() between s.start AND s.end";
			// ��Ż �ݳ� �ӹ�
			$rsql['rental_close_near'] = "select count(*) from rent_schedule s left join tblorderinfo o on o.ordercode=s.ordercode where o.id='".$userid."' and s.status='BI' and NOW() between DATE_SUB(s.end, interval ".self::$BR_nearlimit." hour) AND s.end";
			// ��Ż ����
			$rsql['rental_end'] = "select count(*) from rent_schedule s left join tblorderinfo o on o.ordercode=s.ordercode where o.id='".$userid."' and s.status='BE' and s.end <= NOW()";
			// �ݳ����
			$rsql['collecting'] = "select count(*) from rent_schedule s left join tblorderinfo o on o.ordercode=s.ordercode where o.id='".$userid."' and s.status='CR' and s.end < NOW()";
			// �ݳ� �Ϸ�
			$rsql['rental_comp'] = "select count(*) from rent_schedule s left join tblorderinfo o on o.ordercode=s.ordercode where o.id='".$userid."' and s.status='CE' and s.end < NOW()";
			// �̹ݳ�
			$rsql['rental_overtime'] = "select count(*) from rent_schedule s left join tblorderinfo o on o.ordercode=s.ordercode where o.id='".$userid."' and s.status='OT' and s.end < NOW()";
			// �ݳ��Ұ�(�ļ�)
			$rsql['rental_no_return'] = "select count(*) from rent_schedule s left join tblorderinfo o on o.ordercode=s.ordercode where o.id='".$userid."' and s.status='NR' and s.end < NOW()";
			// ����
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
	
	
	// �ɼ� ���� ���� ����
	/**
	�ɼ� ���� ����- ��ǰ �ɼ� ���� ���� �� ���� ���� ó��
	@param int pridx ��ǰ ���� �ĺ� ��ȣ
	@param array options ��ǰ�� ���� �ɼ� ���� ( ���� ,������ȣ ��) - �ش� ������ �������� ���� ���� ������ �űԴ� �߰��� ������ ������ ó����
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
					if(_array($idxs) && false !== $pos = array_search($option['idx'],$idxs)) unset($idxs[$pos]); // ���� ��󿡼� ��
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
	mypage ��� ���� �� �ֹ� � ���� ������ Ȯ�� �ϱ� ���� ���Ǻ� ���� count
	@param string start ������
	@param string end 	������
	@param string status �뿩����
	*/
	static public function bookingCount($start,$end,$status){
		$where = array();	

		$stamp = rentProduct::getTimeRange(24,$start,$end);
		if(!_empty($stamp['err'])) return $stamp;
		array_push($where,"s.status='".$status."' ");
		array_push($where,"s.`start` <= '".date('Y-m-d H:i:s',$stamp['rangestamp'][1])."' AND s.`end` >= '".date('Y-m-d H:i:s',$stamp['rangestamp'][0])."'");
		
		if(isset($GLOBALS['_VenderInfo']) && _isInt($GLOBALS['_VenderInfo']->getVidx())) array_push($where,"p.vender='".$GLOBALS['_VenderInfo']->getVidx()."'"); //������ ���� �ڱⲨ��

		$sql = "select count(*) from rent_schedule s inner join tblorderinfo ord on ord.ordercode=s.ordercode  inner join rent_product_option o on o.idx=s.optidx inner join tblproduct p using(pridx) left join tblorderproduct op on op.ordercode=s.ordercode and op.basketidx=s.basketidx where	".implode(' and ',$where)." order by s.start,s.end";		
	
		$RES = mysql_query( $sql,self::$conn);
		$bookingCount = mysql_result($RES,0,0);

		return $bookingCount;
	
	}

}



// �̱��� ó���� ���� ��ü ����
rentProduct::_init();
?>