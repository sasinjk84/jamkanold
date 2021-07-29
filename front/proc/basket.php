<?
$Dir="../../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/shopdata.php");
include_once($Dir."lib/ext/basket_func.php");


try{
	switch($_REQUEST['act']){
		case 'basketToOrder':
			if(!_array($_REQUEST['basket_select_item'])) throw new ErrorException('대상 상품이 전달 되지 않았습니다.');
			@mysql_query("delete from tblbasket where memid='".$_ShopInfo->getMemid()."'",get_db_conn());
			$sql = "insert into tblbasket select * from tblbasket_normal where  memid='".$_ShopInfo->getMemid()."' and basketidx in('".implode("','",$_REQUEST['basket_select_item'])."')";

			/*
			@mysql_query("delete from tblbasket where tempkey='".$_ShopInfo->getTempkey()."'",get_db_conn());
			$sql = "insert into tblbasket select * from tblbasket_normal where  tempkey='".$_ShopInfo->getTempkey()."' and basketidx in('".implode("','",$_REQUEST['basket_select_item'])."')";
			*/
			if(false === mysql_query($sql,get_db_conn())) _alert('DB 연동 오류','-1');			
			_alert('','/'.FrontDir.'login.php?chUrl='.urlencode($Dir.FrontDir."order.php"));
			break;
		case 'basketToRecommand':
			if(_empty($_ShopInfo->getMemid())) throw new ErrorException('로그인 되어 있지 않습니다.');
			$sql = "delete from recommand_basket where memid='".$_ShopInfo->getMemid()."' and recomcode is null";

			@mysql_query($sql,get_db_conn());
			
			if(!_array($_REQUEST['basket_select_item'])) throw new ErrorException('대상 상품이 전달 되지 않았습니다.');
			
			//$sql = "select * from tblbasket_normal where tempkey='".$_ShopInfo->getTempkey()."' and basketidx in('".implode("','",$_REQUEST['basket_select_item'])."')";
			$sql = "select * from tblbasket_normal where memid='".$_ShopInfo->getMemid()."' and basketidx in('".implode("','",$_REQUEST['basket_select_item'])."')";
			if(!_empty($_REQUEST['sfld'])) $sql .= " and folder="._escape($_REQUEST['sfld']);

			if(false === $res = mysql_query($sql,get_db_conn())) throw new ErrorException('DB 호출 오류');
			if(mysql_num_rows($res) < 1) throw new ErrorException('전달된 대상 상품 정보에 오류가 있습니다.');
			$items = array();
			while($row = mysql_fetch_assoc($res)) array_push($items,$row);

			foreach($items as $item){

				$sql = "insert into recommand_basket set memid='".$_ShopInfo->getMemid()."',productcode='".$item['productcode']."',opt1_idx='".$item['opt1_idx']."',opt2_idx='".$item['opt2_idx']."',optidxs='".$item['optidxs']."',quantity='".$item['quantity']."',deli_type='".$item['deli_type']."',date=NOW()";

				if(false === mysql_query($sql,get_db_conn())){
					echo mysql_error();
					@mysql_query("delete from recommand_basket where memid='".$_ShopInfo->getMemid()."'",get_db_conn());
					throw new ErrorException('DB 연동 오류');
				}
				$basketidx = mysql_insert_id(get_db_conn());

				if(false !== $sres = mysql_query("select count(*) from rent_basket_temp where basketidx='".$item['basketidx']."' limit 1",get_db_conn())){
					if(mysql_result($sres,0,0) > 0){
						$sql = "insert into rent_basket_temp select '".$basketidx."','recommand',start,end,optidx,quantity,deli_type from rent_basket_temp where basketidx=".$item['basketidx']." and ordertype='".$item['ordertype']."'";

						if(false === mysql_query($sql,get_db_conn())) throw new ErrorException('기간 연동 오류');
					}
				}
				
			}
			_alert('','/front/order.php?ordertype=recommand');
			break;
		case 'checkRequest':
			$result = array('err'=>'에러');
			try{
				if(_empty($_ShopInfo->getMemid())) throw new ErrorException('로그인 되어 있지 않습니다.');
				$sms = '';
				$sql ="select mobile from tblmemebr where id='".$_ShopInfo->getMemid()."' ";
				if(false !== $res = mysql_query($sql,get_db_conn())){
					if(mysql_num_rows($res) < 1)  throw new ErrorException('회원정보를 찾을 수 없습니다.');
					$sms = mysql_result($res,0,0);
				}
				
//				$sql = "select * from tblbasket b left join rent_basket_temp t using(basketidx,ordertype) where b.tempkey='".$_ShopInfo->getTempkey()."' and t.start is not null ";
				//$sql = "select * from tblbasket_normal b left join rent_basket_temp t using(basketidx,ordertype) where b.tempkey='".$_ShopInfo->getTempkey()."' and b.basketidx in('".implode("','",$_REQUEST['basket_select_item'])."') and t.start is not null ";


				$sql = "select * from tblbasket_normal b left join rent_basket_temp t using(basketidx,ordertype) where b.memid='".$_ShopInfo->getMemid()."' and b.basketidx in('".implode("','",$_REQUEST['basket_select_item'])."') and t.start is not null ";
				if(!_empty($_REQUEST['sfld'])) $sql .= " and b.folder="._escape($_REQUEST['sfld']);
				
				if(false === $res = mysql_query($sql,get_db_conn())) throw new ErrorException('DB 호출 오류');
				if(mysql_num_rows($res) < 1) throw new ErrorException('장바구니에 등록된 상품이 없습니다.');
				$items = array();
				while($row = mysql_fetch_assoc($res)) array_push($items,$row);
				
				
				foreach($items as $item){
					$sql = "select count(*) from checkRequest where basketidx='".$item['basketidx']."'";
					if(false !== $res = mysql_query($sql,get_db_conn())){
						if(mysql_result($res,0,0) > 0){ // 이미 들어간 경우
							continue;
						}
					}
					
					$sql = "insert into checkRequest set basketidx='".$item['basketidx']."',reqDate=NOW(),sms='".$sms."'";					
					if(false === mysql_query($sql,get_db_conn())){
						throw new ErrorException('DB 연동 오류'.mysql_error());
					}				
				}
				$result['err']='ok';
			}catch(Exception $e){
				$result['err'] = $e->getMessage();
			}
			// php  5.2.0 이상은 추가
			$phpVer = str_replace(".","",phpversion());
			if( $phpVer >= 520 ) array_walk($result,'_encode');
			
			echo json_encode($result);
			exit;

			break;
		case 'reservation':
			$result = array('err'=>'에러');
			try{
				if(_empty($_ShopInfo->getMemid())) throw new ErrorException('로그인 되어 있지 않습니다.');
				
				//$sql = "select * from tblbasket b left join rent_basket_temp t using(basketidx,ordertype) where b.tempkey='".$_ShopInfo->getTempkey()."' and t.start is not null ";
				//$sql = "select * from tblbasket_normal b left join rent_basket_temp t using(basketidx,ordertype) where b.tempkey='".$_ShopInfo->getTempkey()."' and b.basketidx in('".implode("','",$_REQUEST['basket_select_item'])."') and t.start is not null ";
				$sql = "select * from tblbasket_normal b left join rent_basket_temp t using(basketidx,ordertype) where b.memid='".$_ShopInfo->getMemid()."' and b.basketidx in('".implode("','",$_REQUEST['basket_select_item'])."') and t.start is not null ";
				
				if(!_empty($_REQUEST['sfld'])) $sql .= " and b.folder="._escape($_REQUEST['sfld']);
				
				if(false === $res = mysql_query($sql,get_db_conn())) throw new ErrorException('DB 호출 오류');
				if(mysql_num_rows($res) < 1) throw new ErrorException('장바구니에 등록된 상품이 없습니다.');
				$items = array();
				while($row = mysql_fetch_assoc($res)) array_push($items,$row);
				
				$reservationCode = date('YmdHis').rand(1111,9999).'B';
				foreach($items as $item){					
					//$sql = "update tblbasket set reservationCode='".$reservationCode."'  where basketidx='".$item['basketidx']."'";					
					$sql = "update tblbasket_normal set reservationCode='".$reservationCode."'  where basketidx='".$item['basketidx']."'";
					if(false === mysql_query($sql,get_db_conn())){
						throw new ErrorException('DB 연동 오류_revcode');
					}
					if(!_empty($item['reservationCode'])){
						if(false !== $cres = mysql_query("select count(*) from rent_schedule where ordercode='".$item['reservationCode']."' and basketidx='".$item['baketidx']."'",get_db_conn())){
							if(mysql_result($cres,0,0) < 1) $item['reservationCode'] = '';
						}
					}
					
					if(_empty($item['reservationCode'])){
						$sql = "insert into rent_schedule set optidx='".$item['optidx']."',quantity='".$item['quantity']."',ordercode='".$reservationCode."',basketidx='".$item['basketidx']."',start='".$item['start']."',end='".$item['end']."',status='NN',regDate=NOW()";
					}else{
						$sql = "update rent_schedule set optidx='".$item['optidx']."',quantity='".$item['quantity']."',ordercode='".$reservationCode."',start='".$item['start']."',end='".$item['end']."',status='NN',regDate=NOW() where ordercode='".$item['reservationCode']."' and basketidx='".$item['basketidx']."'";
					}
					if(false === mysql_query($sql,get_db_conn())){
						throw new ErrorException('DB 연동 오류');
					}
				}
				$result['err']='ok';
				$result['limitstr'] = '24시간 이내('.date('m월 d일 H시',strtotime('24 hour')).'까지)';
			}catch(Exception $e){
				$result['err'] = $e->getMessage();
			}
			// php  5.2.0 이상은 추가
			$phpVer = str_replace(".","",phpversion());
			if( $phpVer >= 520 ) array_walk($result,'_encode');
			
			echo json_encode($result);
			exit;
			break;
		default:
			throw new ErrorException('정의되지 않은 메서드 입니다.');
			break;
	}
}catch(ErrorException $e){
//	echo $e->getMessage();
	_alert($e->getMessage(),'-1');	
}catch(Exception $e){
	
}
exit;
?>