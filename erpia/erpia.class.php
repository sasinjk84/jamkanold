<?
/**
* ERPia ������ ���� Ŭ����
* 2012.05.15 code by madmirr@gmail.com
*/

class erpia{
	var $charSet = 'euc-kr'; // �ɸ��ͼ� - �ҽ� ���ڵ� �� ����Ʈ �ҽ� �ڵ�� ��ġ �ؾ� ��.
	var $rn = "\r\n"; // ���� ����
	var $saveLog = false;
	// ���� �� ���� ���� Ȯ�� ����
	var $MasterHost = "getmall.co.kr";
	var $SVStatusFile = "/front/onoff/checkservice.php";
	
	var $LogFileName = 'erpia.log'; // ����� �α� ����� ����
	var $isauth = false; // ���� ���� ����
	var $shopurl='';

	var $svtype = '';
	var $pid = ''; // erpia ���� ���� �Ҷ� ���� Ȯ�ο� pid
	var $pwd = ''; // erpia ���� ���� �Ҷ� ���� Ȯ�ο� pwd
	var $admin_code = ''; // erpia �� ��ϵ� ��ü �ڵ� ( �ǽð� ȣ�� �� ���� ���)
	var $site_code = '001'; // Erpia �� ��ϵ� ����Ʈ �ڵ�
	var $isinited = NULL; // Ȱ��ȭ ���� �Ǻ�
	var $Bcode = array('�������'=>'DEA','�����ù�'=>'HLC','�����ù�'=>'HAN','CJ HTH'=>'HTH','�����ͽ�������'=>'DNG','�����ù�'=>'GEN','KGB�ù�'=>'KGB','���ο�ĸ'=>'YEL','��ü���ù�'=>'EPO','SC������'=>'SCL','�ϳ����ù�'=>'HNL','�����帲�ͽ�������'=>'HAD','�Ͼ��ù�'=>'YAN','�׵���'=>'NED','�¸���ù�'=>'GOD','�̳������ù�'=>'INN','�浿�ù�'=>'KDX','ȣ���ù�'=>'HON','����ù�'=>'YYT','��Ÿ'=>'OTH',''=>'REG','��ü���'=>'DIR','������'=>'LET','��������'=>'FAC','����ù�'=>'DSI','õ���ù�'=>'CHN'); // erpia �ù�� �ڵ� ���ο� �迭
	
	var $BcodeInit = array(); // ��ü���� erpia �� �ù�� ������ ���� �迭

	function erpia(){
		$this->__construct();
	}
	// �ν��Ͻ� ����
	function __construct(){
		$this->_init();	
	}
	
	// ���� �ʱ�ȭ - ����� ������ �ε�
	function _init($reinit=false){
		if($this->isinited === false){
			
		}else{
			if(is_null($this->isinited) || $reinit){		
				if(empty($this->shopurl) && !empty($GLOBALS['shopurl'])){
					$this->shopurl = $GLOBALS['shopurl'];						
				}
				if(substr($this->shopurl,-1,1) == '/') $this->shopurl = substr($this->shopurl,0,-1);
				$result = $this->_sendHTTP('get',$this->MasterHost,$this->SVStatusFile,array('shopurl'=>$this->shopurl));
				$status = explode('|',$result[1]);
				if($status[0] == '1'){
					if($status[1] == '7'){
						$this->svtype = $status[2];
						$this->admin_code = $status[3];
						$this->pid = $status[4];
						$this->pwd = $status[5];
						if($this->svtype !== 'Professional1') $this->isinited = true;
						else $this->isinited = false;							
					}else{
						$this->isinited = false;
					}
				}else{
					if($status[0] == '0') $this->isinited = false;
					else $this->_log('Sv CheckErr',$status[1]);
				}
			}			
		}
		return $this->isinited;
	}
	
	function _checkSvStatus(){
		$result = $this->_sendHTTP('get',$this->MasterHost,$this->SVStatusFile,array('shopurl'=>$this->shopurl));
		echo '<pre>';
		var_dump($result);
		echo '</pre>';
		exit;
	}
	
	/**
	* ���� �� Ȯ�ο�
	*/
	function _val($key){
		$return = NULL;
		switch($key){
			case 'adminCode': $return = $this->admin_code; break;
			case 'isInit': $return = $this->isinited; break;
		}
		return $return;
	}
	
	/**
	* erpia �� �ù� �ڵ�� ��ü ���� �ù�� ���� ��ȣ �� ���� ó���� ���� �ʱ�ȭ �Լ�
	*/
	function _delyInit(){
		$sql="SELECT * FROM tbldelicompany ORDER BY company_name ";
		$result=mysql_query($sql,get_db_conn());
		$this->BcodeInit = array();
		while($row=mysql_fetch_assoc($result)) {
			//$delicomlist[$row->code]=$row;			
			// ���� �ù� �����ù�,KT������,�ǿ��ù�,Ʈ����ù�,����ù�,�ż�����ؽ��ù�
			$name = $row['company_name'];
			if(!empty($this->Bcode[$name])) $this->BcodeInit[$row['code']] = $this->Bcode[$name];
			else $this->BcodeInit[$row['code']] = $this->Bcode['��Ÿ'];			
		}
		mysql_free_result($result);
	}
	
	/**
	* ��ü �ù� ȸ�� �ڵ带 �Է¹޾Ƽ� erpia ���� �ù� �ڵ带 ��ȯ
	* ��Ͽ� ���� ��� ��Ÿ �� �ش��ϴ� �ڵ� ��ȯ
	*/
	function _delyCodeErpia($code,$forceRefresh=false){
		if(!empty($code)){			
			if(count($this->BcodeInit) < 1 || $forceRefresh === true) $this->_delyInit();
			
			$return =  $this->BcodeInit[$code];
			if(empty($return)) $return = 'OTH';
			return $return;
		}else return '';		
	}
	
	/**
	* erpia �� �ù� �ڵ带 ��ü �ù� ȸ�� ��ȣ�� ��ȯ
	* ��Ͽ� ���� ��� '����' �� �ش��ϴ� ���� ��ȯ
	*/
	function _deliComGetmall($Tcode){
		if(!empty($Tcode)){			
			if(count($this->BcodeInit) < 1 || $forceRefresh === true) $this->_delyInit();
			$deli_com = array_search($Tcode,$this->BcodeInit);
			if(!$deli_com) $deli_com = '';
			return $return;
		}else return '';	
	}
	
	
	
	/**
	* ���� Ȯ�� �Լ�
	* erpia Ŭ���̾�Ʈ�� url ���� ������ ����ϴ� id �� ��й�ȣ�� - Ŭ���̾�Ʈ���������� �������� �ٸ� ���� ������ �Է��Ҽ� ������ ������ ���ؼ� ���� �ؾ���.
	*/
	function _auth($pid,$pwd){
		if($this->_init() && !empty($pid) && !empty($pwd)){		
			if($pid === $this->pid && $pwd === $this->pwd){
				$this->isauth = true;
			}else{
				$this->isauth = false;
				$this->_log('accErr','���� ���� ������ �ùٸ��� �ʽ��ϴ�.');
			}
			return $this->isauth;
		}else{
			return false;
		}
	}
	
	/**
	* ������ �׽�Ʈ �α� ��� �Լ�
	* TODO :����� �Ϸ��� �ش� �Լ� ó�� �ʿ�
	*/
	
	function _log($code,$msg){
		if(empty($code) && empty($msg)) return;
		if($this->saveLog && false){
			if(false !== $fp = fopen($_SERVER['DOCUMENT_ROOT'].'/'.$this->LogFileName,'a+')){
				$str = date('Y-m-d H:i:s').': ['.$code.']'.$msg.$this->rn;
				fwrite($fp,$str);
				fclose($fp);
			}
		}
	}
	
	// xml ��� (head ����) - �ش� �Լ��� ȣ�� �ϴ� ������������ �Լ� ��� �� ��� ������ ������ �ȵ�(�ش� ����)
	function _xml($array_xml){
		$str = 
		$xml['root'] = '';
		if(is_array($array_xml) && count($array_xml) > 0){			
			 $xml['root'] = $array_xml;		
			 /*
			@header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
			@header("Last-Modified: " . gmdate("D, d M Y H:i ") . " GMT");
			@header("cache-contril: no-cache,must-revalidate");
			@header("pragma: no-cache");
			@header("Content-type: application/xml; charset=".$this->charSet);
	*/
			echo '<?xml version="1.0" encoding="'.$this->charSet.'" ?>';
			echo ($this->_xmlNode($xml));			
			//exit;					
		}else{
		//	exit;
		}
	
	}
	
	// _xml �Լ����� ���ڷ� ���޵� array �� xml ���ڿ� ������ ���� �Լ�
	function _xmlNode($nodearr,$nName=NULL,$depth=0){
		$return = '';
		if(is_array($nodearr)){
			foreach($nodearr as $nodeKey=>$nodeVal){			
				if(is_array($nodeVal)){
					if(is_numeric($nodeKey) && !empty($nName)){
						$nodeName = $nName;
					}else{
						$nodeName = $nodeKey;
					}
				
					$keys= array_keys($nodeVal);
					$isNumeric = true;
					for($i=0;$i<count($keys);$i++){
						if(!is_numeric($keys[$i])){
							$isNumeric = false;
							break;
						}
					}					
					$tmpstr = $this->_xmlNode($nodeVal,$nodeName,$depth+1);
					if($isNumeric) $return .= $tmpstr;
					else $return .= $this->rn.'<'.$nodeName.'>'.$tmpstr.$this->rn.'</'.$nodeName.'>';				
				}else if(is_object($nodeVal)){
					$return .= '<'.$nodeKey.'>Object</'.$nodeKey.'>'; // ��ü �Ľ� �� ���� �ʿ��ϸ� �߰�
				}else{
					//if(preg_match_all("/[#\&\+\-%@=\/\\\:;,\.'\"\^`~\_|\!\?\*$#<>()\[\]\{\}]/",$nodeVal)) $return .=  $this->rn.'<'.$nodeKey.'><!CDATA['.$nodeVal.']]></'.$nodeKey.'>';
					$nodeName = (is_numeric($nodeKey) && !empty($nName))?$nName:$nodeKey;
					if(preg_match("/[\\\'\"\^`~\_|$<>()=]/",$nodeVal)) $return .=  $this->rn.'<'.$nodeName.'><![CDATA['.$nodeVal.']]></'.$nodeName.'>';
					//if(preg_match("/[^ a-zA-Z0-9\-\@\.\,_]/",$nodeVal)) $return .=  $this->rn.'<'.$nodeKey.'><![CDATA['.$nodeVal.']]></'.$nodeKey.'>';
					else $return .=  $this->rn.'<'.$nodeName.'>'.$nodeVal.'</'.$nodeName.'>';
				}			
			}
		}	
		return $return;
	}
	
	// ��� ��½� �鿩���Ⱑ �ʿ� �Ұ�� ���
	function _tab($size){
		return str_repeat("\t",intval($size));
	}
	
	// �ǽð� ����ȭ ó�� �Լ� - �ֹ�,��ǰ ���� �� ���� �ÿ� �ش� ���μ������� ȣ��
	function _realTimeSync($mode,$param){
		if($this->_init() && !empty($this->admin_code)){
			switch($mode){
				case 'goods':
					if(preg_match('/^[0-9]{18}$/',$param)) $this->_sendHTTP('get','www.erpia.net','/out/b2c.asp',array('mode'=>'goods','admin_code'=>$this->admin_code,'code'=>$param));
					else $this->_log('realtimesync','��ǰ �ڵ尡 �ùٸ��� �ʽ��ϴ�.');
					break;
				case 'order':
					if(preg_match('/^[0-9A-Z]+$/',$param)) $this->_sendHTTP('get','www.erpia.net','/out/b2c.asp',array('mode'=>'order','admin_code'=>$this->admin_code,'code'=>$param));
					else  $this->_log('realtimesync','�ֹ� �ڵ尡 �ùٸ��� �ʽ��ϴ�.');
					break;
				default:
					break;
			}
		}else{
			//$this->_log('realtimesync','���� ���� ���� ����');
		}
	}
	
	// _realTimeSync ȣ��� ���� http ��� ��� �Լ�
	function _sendHTTP($method,$host,$uri='/',$param=array(),$port=80,$timeout=30){
		$method = (strtoupper($method) == 'POST')?'POST':'GET';
		$this->_log('sendHTTP','start '.$method);
		if(is_array($param) && count($param) > 0){			
			$param_get = ($method == 'GET')?'?'.http_build_query($param):'';
			$param_post = ($method == 'POST')?http_build_query($param):'';
		}
		$req = array();
		
		$req[] = $method.' '.$uri.$param_get.' HTTP/1.1';
	    $req[] = 'Host: '. $host; 
 		$req[] = 'Content-Type: application/x-www-form-urlencoded';
		
		if($method == 'POST'){			
			$req[] = 'Content-Length: '.strlen($param_post);
			$req[] = $param_post;
		}		
		$req[] ="Connection: close".$this->rn;
				
		$req = implode($this->rn,$req).$this->rn;
		$this->_log('sendHTTP','req :: '.$req);
		if(false !== $fp = @fsockopen($host,$port,$errcode,$errmsg,$timeout)){			
			$this->_log('sendHTTP','open ');
			fputs($fp,$req);
			$this->_log('sendHTTP','put '.$method);
			
			$buff = array('','');
			$i=0;
			while(!feof($fp)){
				$cont = fgets($fp, 4096);
				if($i < 1 && $cont == $this->rn) $i++;
				$buff[$i] .= $cont;
			}
			//$temp_result.=fread($fp,1024);
			fclose($fp);
			$this->_log('sendHTTP','close');
			return $buff;
		}else{
			$this->_log('syncErr','['.$errcode.']'.$errmsg);
			return '';
		}
	}	
	
	/**
	* �Ϻ� ����ȭ �� ���� ��� ó���� �޼���
	*/
	function _syncBridge_Orders(){
		$query = "INSERT INTO tblerpiaorder (vender,ordercode,tempkey,productcode,opt1_name,opt2_name,package_idx,assemble_idx) select vender,ordercode,tempkey,productcode,opt1_name,opt2_name,package_idx,assemble_idx from tblorderproduct p left join tblerpiaorder e using (vender,ordercode,tempkey,productcode,opt1_name,opt2_name,package_idx,assemble_idx ) where substr(p.productcode,1,3) not in ('COU','999') and isnull( e.Gseq)";
		@mysql_query($query,get_db_conn());
	}
	
	/**
	* �ֹ� ���� ����� erpia ���� �긮�� ���̺� ���� �ð� �� ������ ���� ȣ�� �Լ�
	*/
	function _syncOrderChangeTime($ordercode,$productcode=NULL,$modifydate=NULL){
		if(!empty($ordercode)){
			if(empty($modifydate)) $modifydate= 'NOW()';
			else $modifydate = "'".$modifydate."'";
			$where = array(" ordercode='".$ordercode."'");
			if(!empty($productcode)) array_push($where," productcode='".$productcode."'");
			$where = " where ".implode(' and ',$where);
			
			$query = "update tblerpiaorder set modifydate=".$modifydate.$where;
			mysql_query($query,get_db_conn());
		}
	}
	
	/** �׿� ��Ÿ ���� ��� ������ �Լ� **/	
	function _limitstr($page,$pageCnt){
		$limit = '';
		if(is_null($page) || is_null($pageCnt)){
			$limit = ' limit 1';
		}else if(intval($page) > 0 && intval($pageCnt)){			
			$pageCnt = intval($_REQUEST['pageCnt']);
			$page = intval($_REQUEST['page']);		
			$limit = ' limit '.(($page-1)*$pageCnt).','.$pageCnt;
		}
		return $limit;
	}
	
	function _productQty($productcode){		
		if($this->_init()){			
			if(empty($productcode)){
				$param = array('Admin_Code'=>$this->admin_code,'Site_Code'=>$this->site_code);
			}else{
				$param = array('Admin_Code'=>$this->admin_code,'Site_Code'=>$this->site_code,'G_Code'=>$productcode);
			}		
			return $this->_sendHTTP('GET','www.erpia.net','/xml/Erpia_Goods_Stock.asp',$param,'80');
		}
		/*
		}catch(Exception $e){
			$this->_log('_productQty',$e->getMessage());
		}*/
	}

	function _syncProductQty($productcode){
		$standitems = array();				
		if($this->_init() && !empty($productcode)){
			$sqlitems = array();
			$xml = $this->_productQty($productcode);
			$xml = $xml[1];
			$items = array();
			$pos = 0;
			
			while(false !== $pos = strpos($xml,'<Good>',$pos)){
				$epos = strpos($xml,'</Good>',$pos+6);
				$tstr = substr($xml,$pos+6,$epos - $pos);
				if(preg_match_all('!<([a-zA-Z0-9_]+)>([^<]*)</\\1>!',$tstr,$mat)){
					
					$idx_code = array_search('G_Code',$mat[1]);
					$idx_name = array_search('G_Name',$mat[1]);
					$idx_stand = array_search('G_Stand',$mat[1]);
					$idx_qty  = array_search('Qty',$mat[1]);
					$idx_yqty = array_search('YQty',$mat[1]);
				
					$code = $mat[2][$idx_code];
					$name = $mat[2][$idx_name];
					$stand = $mat[2][$idx_stand];
					$qty = $mat[2][$idx_qty];
					$yqty = $mat[2][$idx_yqty];
					if(empty($stand)){
						$sql = "update tblproduct set quantity='".max(0,intval($qty)-intval($yqty))."' where productcode='".$code."'";
						array_push($sqlitems,$sql);
					}else{
						$standitems[$code][$stand] = max(0,intval($qty)-intval($yqty)); 
					}	
				}
				$pos = $epos+7;
			}

			if(count($standitems)){
				$sql = "select productcode,option_quantity,option1,option2 from tblproduct where productcode in ('".implode("','",array_keys($standitems))."')";								
				$result = @mysql_query($sql,get_db_conn()) or die(mysql_error());
				$old = array();
				while($row = mysql_fetch_assoc($result)){					
					$opt1 = explode(',',$row['option1']);
					$opt2 = explode(',',$row['option2']);
					$quantit = explode(',',$row['option_quantity']);
					$old[$row['productcode']]=array($opt1,$opt2,$quantit);						
				}			
				foreach($standitems as $productcode=>$node){
					$chk = $old[$productcode];
					for($i=1;$i<count($chk[0]);$i++){
						for($j=1;$j<count($chk[1]);$j++){
							$k = $i+($j-1)*10;
							$val = $node[$chk[0][$i].'||'.$chk[1][$j]];
							$chk[2][$k] = $val;
						}
					}	
					$sql = "update tblproduct set option_quantity='".implode(',',$chk[2])."' where productcode='".$productcode."'";
					array_push($sqlitems,$sql);
				}		
			}
			$rcnt = 0;
			if(count($sqlitems) > 0){
				foreach($sqlitems as $sql){									
					if(@mysql_query($sql,get_db_conn())) $rcnt++;
				}
				$sqlitems = array();
			}
		}
		return $rcnt;
	}
}


/*
(PHP 5, PECL pecl_http:0.1.0-0.9.0)
http_build_query -- URL ���ڵ��� ���� ���ڿ��� �����մϴ�.
����
string http_build_query ( array $formdata [, string $prefix][, string $arg_separator][, string $numeric_prefix] )
�־��� ����(Ȥ�� �ε���) �迭���� URL ���ڵ��� ���� ���ڿ��� �����մϴ�. formdata�� �迭�̳� �Ӽ��� ������ ��ü�� �� �ֽ��ϴ�. formdata�� �ܼ��� 1���� �����ϼ���, Ȥ�� (�ٸ� �迭�� ������)�迭�� �迭�� �� �ֽ��ϴ�. �⺻ �迭�� ���� �ε����� ����ϰ� numeric_prefix�� �־�����, �⺻ �迭 ���� ���� �ε��� �տ� ���ٿ����ϴ�. �̴� PHP�� �ٸ� CGI ���ø����̼ǿ� ������ �������� �������� �մϴ�. 
*/
if (!function_exists('http_build_query')) {
	function http_build_query($data,$prefix=NULL,$arg_sep=NULL,$base=NULL){
		if(empty($arg_sep)) $arg_sep = ini_get('arg_separator.output');
		if (is_object($data)) $data = get_object_vars($data);
		
		$return = array();		
		foreach((array)$data as $_k => $_v){
			if(is_numeric($key) && !empty($prefix)) $_k = $prefix.$_k;
			$_k = urlencode($_k);
			if(!empty($base)) $_k = $base . '[' . $_k . ']';
			$return[] = (is_array($_v) || is_object($_v))?http_build_query($_v, $prefix, $arg_sep, $_k):$_k.'='.urlencode($_v);
		}
		return implode($arg_sep, $return);
	}
}
?>