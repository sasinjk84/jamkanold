<?
require_once dirname(__FILE__).'/../lib/ext/func.php';

class naverCheckout{
	var $hidden = false; // ��ư ��� ���� ����
	var $istest = true; // �׽�Ʈ �� ��� true (���� �ּ� �� �ٲ�)
	var $ismobile = false; // ����� ������ �Ǻ�
	var $isbasket = false; // ��ٱ����� ��� ��ǰ ���� ���� �� ���
	var $charset = 'utf-8';
	var $shopId = ''; // ������ id
	var $certificationKey = ''; // ����������Ű
	var $buyButtonImageKey = ''; // ��ư����Ű
	var $commonId = ''; // ��������Ű
	
	var $checkoutHost = '';
	var $sendHost = '';
	var $wihsUrl = '';
	var $orderUrl = '';
	
	var $products = array();
	var $totalPrice =0;
	var $deliPrice = 0;
	var $deliType = 'PAYED';
	
	
	var $domain = '';
	var $url_goods = '/front/productdetail.php';
	var $url_cart = '/front/basket.php';
	
	var $url_mgoods = '/m/productdetail_tab01.php';
	var $url_mcart = '/m/basket.php';
	
	var $url_image = '/data/shopimages/product/';
	var $url_thumbimage = '/data/shopimages/product/';
	
	var $mode = '';

	function naverCheckout($ismobile=NULL){
		global $_ShopInfo;
	//	if($_ShopInfo->getMemid() == 'getmall') $this->hidden = false;
		//$this->ismobile = ($ismobile === true);
		// ����� �ڵ� �̵� ��� ����
		$this->ismobile = $ismobile = (preg_match("/(PSP|Symbian|Nokia|LGT|mobile|Mobile|Mini|iphone|SAMSUNG|Windows Phone|Android|Galaxy)/", $_SERVER['HTTP_USER_AGENT']))?true:false;
		
		if($this->istest === true){	
			$checkoutHost = 'test-checkout.naver.com';
			$checkoutHostM = 'test-m.checkout.naver.com';
		}else{
			$checkoutHost = 'checkout.naver.com';
			$checkoutHostM = 'm.checkout.naver.com';
		}
		
	//	$this->checkoutHost = $this->ismobile?$checkoutHostM:$checkoutHost;	
		$this->checkoutHost = $checkoutHost;	
		if($this->ismobile){
			$this->sendHost = $checkoutHostM;
		}else{
			$this->sendHost = $this->checkoutHost;
		}
		
		$this->wihsUrl= $this->charset == 'utf-8' ? 'POST /customer/api/wishlist.nhn HTTP/1.1' : 'POST /customer/api/CP949/wishlist.nhn HTTP/1.1';	
		$this->orderUrl = $this->charset == 'utf-8' ? 'POST /customer/api/order.nhn HTTP/1.1' : 'POST /customer/api/CP949/order.nhn HTTP/1.1';
		
		
		// ��Ʈ��ȣ�� ������� ���͸��Ѵ�.
		/*
		preg_match("/([a-z0-9-.]*)\.([a-z]{2,3})/", $_SERVER['HTTP_HOST'], $matches);
		$this->domain = 'http://' . $matches[0];*/
		$this->domain = $this->getDomain();
	}
	
	function _getCommonId(){
		return $this->commonId;
	}
	
	function setItesmType($type='product'){
		$this->isbasket = ($type != 'product');
		
	}
	
	function getDomain(){
		preg_match("/([a-z0-9-.]*)\.([a-z]{2,3})/", $_SERVER['HTTP_HOST'], $matches);
		$domain = 'http://' . $matches[0];
		return $domain;
	}
	
	function btn($authkey='', $id=''){
		$available= 'N';
		$str = '';
		if(!$this->hidden){
			if($this->isbasket!==true){
				$_pdata = $GLOBALS['_pdata'];
				if(!isset($_pdata) || !is_object($_pdata)) $str = 'üũ�ƿ���������';			
				//else $available=(($_pdata->checkout != 'Y' || (strlen(trim($_pdata->quantity)) > 0 && $_pdata->quantity <= 0 ) || $_pdata->sellprice <= 0)? 'N' : 'Y'); // üũ�ƿ� ���� �ʵ尡 �ִ� ���
				else $available=( substr($_pdata->productcode,0,3) =='898' || ((strlen(trim($_pdata->quantity)) > 0 && $_pdata->quantity <= 0 ) || $_pdata->sellprice <= 0)? 'N' : 'Y'); // üũ�ƿ� ���� �ʵ尡 �ִ� ���
			}else{
				if(_empty($authkey)) $str = '��ٱ��� �ĺ�Ű ���� ����';
				else $available= $this->checkBasketItem($authkey, $id);
			}
	
			if(_empty($str)){
				$str = $this->getScript();
				$str .= "\r\n".'<script language="JavaScript">'."\r\n<!--\r\nfunction notBuy(){\r\nalert(\"�˼��մϴ�. NAVER Checkout���� ���Ű� �Ұ��� ��ǰ�Դϴ�.\");\r\nreturn false;\r\n}\r\n//-->\r\n</script>\r\n";
				$str .= '<script language="JavaScript">'."\r\n";
				$str .= "//<![CDATA[\r\nnhn.CheckoutButton.apply(\r\n{\r\n";
				$str .= "BUTTON_KEY: \"".$this->buyButtonImageKey."\",\r\n";
				
				if($this->isbasket!==true){
					if(!$this->ismobile) $str .= "TYPE: \"D\",\r\n";
					else $str .= "TYPE: \"MA\",\r\n";				
					$str .= "COLOR: 2 ,\r\n";
				}else{
					if(!$this->ismobile) $str .= "TYPE: \"D\",\r\n";
					else $str .= "TYPE: \"MA\",\r\n";				
					$str .= "COLOR: 2 ,\r\n";
				}
				if($this->isbasket === true) $str .= "COUNT: 1,\r\n";
				else $str .= "COUNT: 2,\r\n";
				
				$str .= "ENABLE: \"".$available."\" ,\r\n";
				
				if($available == 'Y'){
					if($this->isbasket) $str .= "BUY_BUTTON_HANDLER: _cartNaverCheckout,\r\n";
					else $str .= "BUY_BUTTON_HANDLER: _orderNaverCheckout,\r\n";
				}else $str .= "BUY_BUTTON_HANDLER : notBuy,\r\n";
				/*
				$str .= "WISHLIST_BUTTON_HANDLER: _wishlistNaverCheckout,\r\n";
				*/
				if($this->ismobile){
					$str .= "WISHLIST_BUTTON_HANDLER: _mwishlistNaverCheckout,\r\n";
				}else{
					$str .= "WISHLIST_BUTTON_HANDLER: _wishlistNaverCheckout,\r\n";
				}
				$str .= '"":""'."\r\n}\r\n);\r\n//]]>\r\n</script>";
			}		
		}
		return $str;
	}
	
	
	function getScript($fulltxt=true){
		$script = ($this->istest)?'http://test-checkout.naver.com':'http://checkout.naver.com';
		$script .= ($this->ismobile)?'/customer/js/mobile/checkoutButton.js':'/customer/js/checkoutButton.js';
		
		if($fulltxt === true) $script = '<script type="text/javascript" src="'.$script.'" charset="UTF-8"></script>';
		return $script;
	}
	
	function checkDetailRedirect($chkindex=false){
		//if($this->ismobile && preg_match('!^/front!',$_SERVER['PHP_SELF']) && isset($_REQUEST['NaPm'])){
		if($this->ismobile && ($chkindex === true || preg_match('!^/front!',$_SERVER['PHP_SELF']))  && isset($_REQUEST['NaPm']) && preg_match('/^[0-9]{18}$/',$_REQUEST['productcode'])){
		//	echo "Location:/m/productdetail_tab01.php?productcode=".$_REQUEST['productcode'].'&NaPm='.urlencode($_REQUEST['NaPm'])	;
			header("Location:/m/productdetail_tab01.php?productcode=".$_REQUEST['productcode'].'&NaPm='.urlencode($_REQUEST['NaPm']));
			exit;		
		}
	}
	
	function setItems($param=array()){
		$this->products = array();
		$this->totalPrice = 0;
		if($param['mode'] == 'wish') $this->mode = 'wish';
		else $this->mode = 'order';
		if($param['type'] == 'product'){
			//$this->setProduct($param['goodsId'],$param['goodsCount'],$param['goodsOption']);
			$this->setPrdOpt($param['goodsId'], $param['goodsCount'], $param['goodsOption']);
		}else{			
			$this->setBasket($param['cartId'], $param['id']);
		}
		
	}
	
	function checkBasketItem($authkey, $id){
		$return = 'N';
		/* üũ�ƿ� �ʵ尡 �ִ� ���
		if(false !== $result = mysql_query("SELECT p.checkout,p.quantity FROM tblbasket b left join tblproduct p on p.productcode=p.productcode WHERE b.tempkey='" . $authkey . "'",get_db_conn())){
			if(mysql_num_rows($result)){
				while($info = mysql_fetch_assoc($result)){				
					if($info['checkout'] != 'Y' || (!_empty($info['quantity']) && intval($info['quantity']) <= 0 )) continue; // üũ�ƿ� ���п� �ʵ尡 ���� ���				
					else{
						$return = 'Y';
						break;
					}
				}
			}
			@mysql_free_result($result);
		*/

		$sql = "SELECT p.quantity ";
		$sql.= "FROM tblbasket b left join tblproduct p on p.productcode=p.productcode ";
		if(strlen($id)>0) {
			$sql.= "WHERE b.id='".$id."'";
		} else {
			$sql.= "WHERE b.tempkey='".$authkey."'";
		}

		if(false !== $result = mysql_query($sql, get_db_conn())){
			if(mysql_num_rows($result)){
				while($info = mysql_fetch_assoc($result)){				
					if(!_empty($info['quantity']) && intval($info['quantity']) <= 0 ) continue; // üũ�ƿ� ���п� �ʵ尡 ���� ���				
					else{
						$return = 'Y';
						break;
					}
				}
			}
			@mysql_free_result($result);
		}else{
			echo mysql_error();
		}
		return $return;
	}
	
	function setProduct($productcode,$quantity=0,$opt_comidx=0){
		global $naverOptClass;

		if(!preg_match('/^[0-9]{18}$/',$productcode)) exit('��ǰ �ڵ尡 �ùٸ��� �ʽ��ϴ�.');
		$sql = "select * from tblproduct where productcode='".$productcode."' limit 1";		
		
		if(false === $res = mysql_query($sql,get_db_conn())) exit('Db Error');
		if(!mysql_num_rows($res)) exit('��ǰ ������ ã���� �����ϴ�.');
		
		$info = mysql_fetch_assoc($res);
		//if($info['checkout'] != 'Y' || (!_empty($info['quantity']) && intval($info['quantity']) <= 0 )) continue;
		if(!_empty($info['quantity'])) {
			if(intval($info['quantity']) <= 0) {
				echo "�ش� ��ǰ�� ǰ���Դϴ�.";
				continue;
			} else if(intval($info['quantity']) < intval($quantity)) {
				echo "�ش� ��ǰ�� ��� �����մϴ�.";
				continue;
			}
		}

		$productitem = array();
		$seloption = array();		
		if($this->mode=='order'){
			$miniq = 1;
			$maxq = -1;
			if(strlen($info['etctype'])>0){
				$etctemp = explode("",$info['etctype']);
				for ($i=0;$i<count($etctemp);$i++) {
					if (substr($etctemp[$i],0,6)=="MINIQ=") $miniq=substr($etctemp[$i],6);
					if(substr($etctemp[$i],0,5)=="MAXQ=") $maxq=substr($etctemp[$i],5);
				}
			}
			
			if(intval($quantity) < $miniq) exit('�ּ� ���� ������ '.$miniq.' �� �Դϴ�.');
			if(intval($maxq) > 0 && intval($quantity) > $maxq) exit('�ִ� ���� ������ '.$maxq.' �� �Դϴ�.');
			else $info['quantity'] = intval($quantity);
			unset($info['content']);
			
			//�ɼǰ�, �ɼ� �̸�
			if($opt_comidx>0) {
				$goodsOptions = $naverOptClass->getOptComText($productcode, $opt_comidx);
				$info['sellprice'] = $info['sellprice'] + $naverOptClass->getOptPrice($opt_comidx);
			}
		}

		if($info['sellprice'] > 0){
			$info['realprice'] = $info['sellprice']*$quantity;
			$info['goodsOptions'] = $goodsOptions;
			if(!isset($this->products[$info['vender']]))  $this->products[$info['vender']] = array();
			array_push($this->products[$info['vender']],$info);
		}		

		if($opt_comidx>0) {
			if($naverOptClass->getOptQuantity($opt_comidx)==0) {
				exit('�ش� �ɼ��� ǰ���Դϴ�.');
			}
			if($naverOptClass->getOptQuantity($opt_comidx)<$quantity) {
				exit('�ش� �ɼ��� ��� �����մϴ�. �ش� �ɼ��� ���� '.$naverOptClass->getOptQuantity($opt_comidx).'�� �Դϴ�.');
			}
		}

		//���� ����
		/*if(strlen($info['quantity'])>0) {
			$quan_sql = "UPDATE tblproduct ";
			$quan_sql.= "SET quantity=quantity-".$quantity." ";
			$quan_sql.= "WHERE productcode='".$productcode."' ";
			@mysql_query($quan_sql,get_db_conn());

			if($opt_comidx>0) {
				$quan_sql = "UPDATE tblopt_combi ";
				$quan_sql.= "SET opt_quantity=opt_quantity-".$quantity." ";
				$quan_sql.= "WHERE com_idx='".$opt_comidx."' ";
				@mysql_query($quan_sql,get_db_conn());
			}
		}*/
		
	}
	
	function setBasket($authkey, $id){
		if(_empty($authkey)) exit('���� �ڵ� ����');

		$sql = "SELECT * FROM tblbasket ";
		if(strlen($id)>0) {
			$sql.= "WHERE id='".$id."' ";
		} else {
			$sql.= "WHERE tempkey='".$authkey."' ";
		}
		$sql.= "ORDER BY date DESC";

		if(false === $result = mysql_query($sql)) exit('DB ����');
		if(mysql_num_rows($result) < 1) _alert('��ٱ����� ��ǰ�� ã�� �� �����ϴ�.');
		
		
		while($bitem = mysql_fetch_assoc($result)){
			$this->setProduct($bitem['productcode'],$bitem['quantity'],$bitem['com_idx']);
		}
		mysql_free_result($result);
	}

	function setPrdOpt($productcode, $opt_quantity, $com_idx) {
		$opt_quantity = explode(",",$opt_quantity);
		$com_idx = explode(",",$com_idx);

		for($i=0, $end=count($com_idx); $i<$end; $i++) {
			$this->setProduct($productcode, $opt_quantity[$i], $com_idx[$i]);
		}
	}
	
	function solvDeli(){
		if(!_array($this->products)) exit('��ǰ ���� ���� ����');
		$this->totalPrice = $this->deliPrice = 0;
		foreach($this->products as $vender=>$items){
			if(_isInt($vender)){											
				$sql = "SELECT deli_price,deli_pricetype,deli_mini,deli_area,deli_limit,deli_area_limit FROM tblvenderinfo WHERE vender='".$vender."' ";			
				$res2=mysql_query($sql,get_db_conn());
				if($vinfo=mysql_fetch_assoc($res2)) {
					$testDeli = $vinfo['deli_price'];
					if($vinfo['deli_price']==-9){
						$vinfo['deli_price']=0;
						$vinfo['deli_after']="Y";
					}
					if ($vinfo['deli_mini']==0) $vinfo['deli_mini']=1000000000;
					$vinfo['sumprice'] = 0;
					$vinfo['deli_productprice'] = $venderval['delisumprice'] = $vinfo['deliprice'] = 0;
				}
				mysql_free_result($res2);		
			}
			
			if($vender==0){
				$sql = "SELECT deli_basefeetype, deli_limit, deli_miniprice, deli_basefee FROM tblshopinfo";
				$result = mysql_query($sql,get_db_conn());
				if($row = mysql_fetch_assoc($result)) {
					$vinfo['deli_pricetype'] = $row['deli_basefeetype'];
					$vinfo['deli_limit'] = $row['deli_limit'];
					$vinfo['deli_mini'] = $row['deli_miniprice'];
					$vinfo['deli_price'] = $row['deli_basefee'];
					$vinfo['sumprice'] = 0;
					$vinfo['deli_productprice'] = $venderval['delisumprice'] = $vinfo['deliprice'] = 0;
				} else {
					$vinfo['deli_pricetype'] = $_data->deli_basefeetype;
					$vinfo['deli_limit'] = $_data->deli_limit;
					$vinfo['deli_mini'] = $_data->deli_miniprice;
					$vinfo['deli_price'] = $_data->deli_basefee;
					$vinfo['sumprice'] = 0;
					$vinfo['deli_productprice'] = $venderval['delisumprice'] = $vinfo['deliprice'] = 0;
				}
			}

			foreach($items as $pidx=>$product){
				$vinfo['sumprice'] += $product['realprice'];
				if(!_empty($this->products[$vender][$pidx]['goodsOptions'])){
					$this->products[$vender][$pidx]['goodsOptions'] = explode('/',$this->products[$vender][$pidx]['goodsOptions']);						
				}else{
					$this->products[$vender][$pidx]['goodsOptions'] = array();
				}
				
				$pdeliprice = 0;
				// ��ۺ� =====================================================================================
				// ��ǰ�� ��ۺ� ����
				if(($product['deli']=="Y" || $product['deli']=="N") && $product['deli_price'] > 0 ){
					// ������ۺ� ���� �̸鼭 ��ۺ� ���� �Ǿ� ���� (Y/0�ʰ�) �Ǵ� �⺻ ��ۺ� (N)
					// deli =>  Y : ���� ��ۺ� (��ۺ�*����) / N : ��ǰ�� ��ۺ�(���� �������)
					// �׷� ��ǰ ���� ��ۺ� : deli_productprice
					
					$pdeliprice = $product['deli_price']*(($product['deli']=="Y")?$product['quantity']:1);
					$vinfo['deli_productprice'] += $pdeliprice;					
					array_push($this->products[$vender][$pidx]['goodsOptions'],'������ۺ�:'.$pdeliprice);					
				} else if($product['deli'] !="F" && $product['deli'] !="G"){
					// ������ۺ� ���ᰡ �ƴϰų� ������ �ƴϸ�.....(������ǰ ����� ������ �н�)
					// ��ۺ� ����� ��ǰ�ݾ�
					$vinfo['delisumprice'] += $product['realprice'];
				}else if($product['deli'] =="G"){
					//if($this->deliType != 'ONDELIVERY') $this->deliType = 'ONDELIVERY';
					if($this->deliType != 'ONDELIVERY') $this->deliType = 'ONDELIVERY';
					array_push($this->products[$vender][$pidx]['goodsOptions'],'���� ��ۻ�ǰ');					
					
					
				}
				
				$this->products[$vender][$pidx]['goodsOptions'] = @implode('/',$this->products[$vender][$pidx]['goodsOptions']);
				/*
				// ��� Ÿ�� ���� ī����
				if( $product['deli_price'] == 0 and $product['deli'] == "N" ) { // �⺻��ۺ� �� ��츸
					$basketItems['vender'][$vender]['deliCount'][$product['deli']][($product['deli_price']>0?"1":"0")]++;
				}
				*/
			}
			
			// ������ۻ�ǰ ����(Y) / ����(N)
			if($vinfo['deli_pricetype']=="Y") $vinfo['delisumprice'] = $vinfo['sumprice'];
			
			if(intval($vinfo['delisumprice'])> 0){
				if($vinfo['deli_price'] > 0 && ( $vinfo['delisumprice']<$vinfo['deli_mini'] OR $vinfo['deli_mini']==0 )){
					$vinfo['deliprice'] += $vinfo['deli_price'];
				}else if(!_empty($vinfo['deli_limit'])) {
					// ���� ��۷�
					$deliLimitList = explode("=",$vinfo['deli_limit']);

					for($deliLimitList_i=0; $deliLimitList_i<count($deliLimitList); $deliLimitList_i++) {
						$deliLimitValues=explode("",$deliLimitList[$deliLimitList_i]);
						if(strlen($deliLimitValues[1])>0) {
							// a �̻� ~ b �̸�
							if( $deliLimitValues[0] <= $vinfo['delisumprice'] AND $vinfo['delisumprice'] < $deliLimitValues[1] ) {
								$vinfo['deli_price'] = $deliLimitValues[2];
								$vinfo['deliprice'] += $vinfo['deli_price'];
								break;
							}
						} else {
							// a �̻� ~
							if( $deliLimitValues[0] <= $vinfo['delisumprice'] ) {
								$vinfo['deli_price'] = $deliLimitValues[2];
								$vinfo['deliprice'] += $vinfo['deli_price'];
								break;
							}
						}
					}
					// ��۷� 0�� (����) ����
					for($deliLimitList_i=0; $deliLimitList_i<count($deliLimitList); $deliLimitList_i++) {
						$deliLimitValues=explode("",$deliLimitList[$deliLimitList_i]);
						if( $vinfo['deli_mini'] == 1000000000 AND $deliLimitValues[2]==0 ){ //AND strlen($deliLimitValues[1])==0
							$vinfo['deli_mini'] = $deliLimitValues[0];
						}
					}
				}
			}
			// ������ۺ� �߰�
			$vinfo['deliprice'] += $vinfo['deli_productprice'];
			
			// �� ��ۺ�
			$this->deliPrice += $vinfo['deliprice'];
			//�� �ֹ��ݾ�
			$this->totalPrice += $vinfo['sumprice'];		
				
		}

		if($this->deliType != 'ONDELIVERY'){			
			$this->deliType = ($this->deliPrice < 1)?'FREE':'PAYED';
		}else{
			if($this->deliPrice < 1) $this->deliType = 'ONDELIVERY'; 
			else $this->deliType = 'PAYED';
		}
		$this->totalPrice +=$this->deliPrice;		
	}	
	
	function order(){			
		if(!_array($this->products)) exit('���� ��ǰ ������ ã���� �����ϴ�.');

		if($this->isbasket){		
			$backUrl = $this->domain.(($this->ismobile)?$this->url_mcart:$this->url_cart);
		}else{
			$p = reset($this->products);
			$backUrl = $this->domain.(($this->ismobile)?$this->url_mgoods:$this->url_goods).'?productcode='.$p[0]['productcode'];
		}
		$queryString = 'SHOP_ID=' . urlencode($this->shopId);
		$queryString .= '&CERTI_KEY=' . urlencode($this->certificationKey);

		$queryString .= '&SHIPPING_TYPE='.$this->deliType;
		$queryString .= '&SHIPPING_PRICE=' .$this->deliPrice;
		$queryString .= '&RESERVE1=&RESERVE2=&RESERVE3=&RESERVE4=&RESERVE5=';
		$queryString .= '&BACK_URL='.urlencode($backUrl);
		$queryString .= '&TOTAL_PRICE='.$this->totalPrice;	
		$queryString .= '&NAVER_INFLOW_CODE='.urlencode($_COOKIE['NA_CO']);
		foreach($this->products as $vender){
			foreach($vender as $goods){
				$id = $goods['productcode'];
				$name = strip_tags($goods['productname']);
				$uprice = $goods['sellprice'];
				$count = $goods['quantity'];				
				$tprice = $uprice*$count;				
				$option = strip_tags($goods['goodsOptions']);
				$item = new checkoutItemStack($id, $name,$tprice, $uprice, $option, $count);
				$queryString.='&'.$item->makeQueryString();
			}
		}
		$result = $this->sendSocket($this->orderUrl,$queryString);
		return $result;
	}
	
	
	function wish(){
		if(!_array($this->products)) exit('���� ��ǰ ������ ã���� �����ϴ�.');
		$queryString = 'SHOP_ID=' . urlencode($this->shopId);
		$queryString .= '&CERTI_KEY=' . urlencode($this->certificationKey);
		$queryString .= '&RESERVE1=&RESERVE2=&RESERVE3=&RESERVE4=&RESERVE5=';
		
		
		foreach($this->products as $vender){
			foreach($vender as $goods){
				$id = $goods['productcode'];
				$name = strip_tags($goods['productname']);
				$uprice = $goods['sellprice'];
				$image = $this->domain.$this->url_image.'/'.$goods['maximage'];
				$thumb = $this->domain.$this->url_thumbimage.'/'.$goods['tinyimage'];
				$url = $this->domain.$this->url_goods.'?productcode='.$goods['productcode'];

				$item = new checkoutItemStack_wish($id, $name, $uprice, $image, $thumb, $url);
				$queryString .= '&'.$item->makeQueryString();
			}
		}		
		$result = $this->sendSocket($this->wihsUrl,$queryString);
		return $result;
	}
	
	function queryString($mode){
	}
	
	function sendSocket($requrl='',$queryString=''){		
		$result = array('Code'=>'','resId'=>'','msg'=>'');
		if(_empty($requrl) || _empty($queryString)) exit('socket param error');
		$req_addr = 'ssl://'.$this->checkoutHost;
		$req_url = $requrl;
		$req_host = $this->checkoutHost;
		$req_port = 443;
		
		
		$nc_sock = fsockopen($req_addr, $req_port, $errno, $errstr);
		if ($nc_sock) {			
			fwrite($nc_sock, $req_url."\r\n" );
			fwrite($nc_sock, "Host: " . $req_host .":" . $req_port . "\r\n" );
			fwrite($nc_sock, "Content-type: application/x-www-form-urlencoded; charset=" . $this->charset . "\r\n");
			fwrite($nc_sock, "Content-length: " . strlen($queryString) . "\r\n");
			fwrite($nc_sock, "Accept: */*\r\n");
			fwrite($nc_sock, "\r\n");
			fwrite($nc_sock, $queryString."\r\n");
			fwrite($nc_sock, "\r\n");

	
			// get header
			while (!feof($nc_sock)) {
				$header = @fgets($nc_sock, 4096);
				if ($header == "\r\n") {
					break;
				}else{
					$headers .= $header;	
				}	
			}
	
			// get body
			while(!feof($nc_sock)) {
				$bodys.=@fgets($nc_sock, 4096);
			}
	
			$result['Code'] = substr($headers, 9, 3);
			if($result['Code'] == 200){
				// success
				$result['resId'] = $bodys;			
			} else {
				// fail
				$result['msg'] = $bodys;	
			}		
			fclose($nc_sock);		
		} else {			
			echo "$errstr ($errno)<br>\n";
			exit(-1);
			//����ó��
		}
		return $result;
	}	
}


function testecho(){
	$args = func_get_args();
	echo implode(',',$args).'<br>';
}




class checkoutItemStack{
	var $id;
	var $name;
	var $tprice;
	var $uprice;
	var $option;
	var $count;

	function checkoutItemStack($_id, $_name, $_tprice, $_uprice, $_option, $_count) {
		$this->id = $_id;
		$this->name = $_name;
		$this->tprice = $_tprice;
		$this->uprice = $_uprice;
		$this->option = $_option;
		$this->count = $_count;
	}
	
	function makeQueryString() {
		$ret .= 'ITEM_ID=' . urlencode($this->id);
		$ret .= '&EC_MALL_PID='.urlencode($this->id);
		$ret .= '&ITEM_NAME=' . urlencode($this->name);
		$ret .= '&ITEM_COUNT=' . $this->count;
		$ret .= '&ITEM_OPTION=' . urlencode($this->option);
		$ret .= '&ITEM_TPRICE=' . $this->tprice;
		$ret .= '&ITEM_UPRICE=' . $this->uprice;
		return $ret;
	}

};
	
class checkoutItemStack_wish{
	var $id;
	var $name;
	var $uprice;
	var $image;
	var $thumb;
	var $url;

	function checkoutItemStack_wish($_id, $_name, $_uprice, $_image, $_thumb, $_url) {
		$this->id = $_id;
		$this->name = $_name;
		$this->uprice = $_uprice;
		$this->image = $_image;
		$this->thumb = $_thumb;
		$this->url = $_url;
	}
	
	function makeQueryString() {
		$ret .= 'ITEM_ID=' . urlencode($this->id);
		$ret .= '&EC_MALL_PID='.urlencode($this->id);
		$ret .= '&ITEM_NAME=' . urlencode($this->name);
		$ret .= '&ITEM_UPRICE=' . $this->uprice;
		$ret .= '&ITEM_IMAGE=' . urlencode($this->image);
		$ret .= '&ITEM_THUMB=' . urlencode($this->thumb);
		$ret .= '&ITEM_URL=' . urlencode($this->url);
		return $ret;
	}

};
?>