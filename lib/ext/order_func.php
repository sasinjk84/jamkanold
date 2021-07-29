<?php


include_once dirname(__FILE__).'/func.php';
include_once dirname(__FILE__).'/base_func.php';


// �뿩 ��ǰ (��Ż) �Լ� ����
include_once dirname (__FILE__) . '/order_func.rent.php';




// ��ٱ��� ���� �������� : ��ȯ���� mysql query ��� resource type  : ������
function getBasketByResource($tblbasket='tblbasket_normal',$vender,$trust_vender,$folder){
	global  $_ShopInfo;
	if(isSeller()=='Y') {
		$sql = "SELECT a.opt1_idx,a.opt2_idx,a.optidxs,a.deli_type,a.quantity,b.vender,b.productcode,b.productname,b.booking_confirm,if(b.productdisprice>0,b.productdisprice,b.sellprice) as sellprice, ";
		$sql.= "b.reserve,b.reservetype,b.addcode,b.tinyimage,b.option_price,b.option_quantity,b.option1,b.option2, b.brand,b.today_reserve, ";
		$sql.= "b.etctype,b.deli_price, b.deli,if(b.productdisprice > 0,b.productdisprice*a.quantity,b.sellprice*a.quantity) as realprice, b.selfcode,a.assemble_list,a.assemble_idx ";
	} else {
		$sql = "SELECT a.opt1_idx,a.opt2_idx,a.optidxs,a.quantity,a.deli_type,b.vender,b.productcode,b.productname,b.booking_confirm,b.sellprice, ";
		$sql.= "b.reserve,b.reservetype,b.addcode,b.tinyimage,b.option_price,b.option_quantity,b.option1,b.option2, b.brand,b.today_reserve, ";
		$sql.= "b.etctype,b.deli_price, b.deli,b.sellprice*a.quantity as realprice, b.selfcode,a.assemble_list,a.assemble_idx ";

	}
	$sql.= ", a.basketidx, b.sns_state,b.present_state,b.pester_state,b.sns_reserve2,b.sns_reserve2_type,d.sell_startdate,d.sell_enddate "; //sns �� ��Ÿ �߰���� ����

	$sql .= ",b.rental";
	if($tblbasket == "tblbasket" || $tblbasket == "tblbasket_noraml") $sql .= ",a.folder,a.reservationCode";
	
	//��ǰ�Ǳ���
	if($tblbasket !="tblbasket2"){
		$sql.= ",v.deli_super, v.deli_price AS venderDeliprice ,b.pridx, b.date, b.tax_yn";
		$sql.=", a.sell_memid ";
	}
	//$sql.= ", c.assemble_type, c.assemble_title ";
	$sql.= " FROM ".$tblbasket." a, tblproduct b ";
	$sql.= "LEFT OUTER JOIN tblproduct_social d ON b.productcode=d.pcode ";

	if($tblbasket !="tblbasket2"){
		$sql.= "LEFT OUTER JOIN tblvenderinfo v ON b.vender=v.vender ";
	}
	//$sql.= "LEFT OUTER JOIN tblassembleproduct c ON b.productcode=c.productcode ";
	//$sql .= " LEFT JOIN tblmemberdiscount dis on (dis.productcode = a.productcode and dis.group_code='".$_ShopInfo->getMemgroup()."') ";
	$sql.= "WHERE 1 ";
	if($tblbasket !="tblbasket2"){
		if($vender > 0){
			$sql.= "AND b.vender = '".$vender."' ";
		}else{
			$sql.= "AND (b.vender = '".$vender."' OR v.deli_super = 'S' )";
		}
	}
	//if(strlen($_ShopInfo->getMemid())==0 or $tblbasket=="tblbasket_ordernow") {	//��ȸ��
	if(strlen($_ShopInfo->getMemid())==0){//��ȸ��
		$sql.= "AND a.tempkey='".$_ShopInfo->getTempkey()."' and memid='' ";
	}else{
		if($tblbasket=="tblbasket_ordernow") {
			$sql.= "AND a.tempkey='".$_ShopInfo->getTempkey()."' ";
		}
		$sql.= "AND a.memid='".$_ShopInfo->getMemid()."' ";
	}
	
	if(($tblbasket == "tblbasket" || $tblbasket == "tblbasket_normal") && !_empty($folder)) $sql .= " and a.folder="._escape($folder)." ";
	$sql.= "AND a.productcode=b.productcode ";
	$sql.= "ORDER BY rental asc,deli ASC, deli_price ASC ";

	return mysql_query($sql,get_db_conn());
}





// ��ٱ��� ���� �������� : ��ȯ���� mysql query ��� resource type
function getBasketByResourceAll($tblbasket='tblbasket_normal'){
	global  $_ShopInfo;
	if(isSeller()=='Y') {
		$sql = "SELECT a.opt1_idx,a.opt2_idx,a.optidxs,a.quantity,b.vender,b.productcode,b.productname,if(b.productdisprice>0,b.productdisprice,b.sellprice) as sellprice, ";
		$sql.= "b.reserve,b.reservetype,b.addcode,b.tinyimage,b.option_price,b.option_quantity,b.option1,b.option2, b.brand, ";
		$sql.= "b.etctype,b.deli_price, b.deli,if(b.productdisprice > 0,b.productdisprice*a.quantity,b.sellprice*a.quantity) as realprice, b.selfcode,a.assemble_list,a.assemble_idx ";
		$sql.= ", a.basketidx, b.sns_state,b.present_state,b.pester_state,b.sns_reserve2,b.sns_reserve2_type,d.sell_startdate,d.sell_enddate "; //sns �� ��Ÿ �߰���� ����
		$sql.= ",v.deli_super";
	} else {
		$sql = "SELECT a.opt1_idx,a.opt2_idx,a.optidxs,a.quantity,b.vender,b.productcode,b.productname,b.sellprice, ";
		$sql.= "b.reserve,b.reservetype,b.addcode,b.tinyimage,b.option_price,b.option_quantity,b.option1,b.option2, b.brand, ";
		$sql.= "b.etctype,b.deli_price, b.deli,b.sellprice*a.quantity as realprice, b.selfcode,a.assemble_list,a.assemble_idx ";
		$sql.= ", a.basketidx, b.sns_state,b.present_state,b.pester_state,b.sns_reserve2,b.sns_reserve2_type,d.sell_startdate,d.sell_enddate "; //sns �� ��Ÿ �߰���� ����
		$sql.= ",v.deli_super";
	}

	//��ǰ�Ǳ���
	if($tblbasket !="tblbasket2") $sql.=", a.sell_memid ";
	$sql.= "FROM ".$tblbasket." a, tblproduct b ";
	$sql.= "LEFT OUTER JOIN tblproduct_social d ON b.productcode=d.pcode ";

	$sql.= "LEFT OUTER JOIN tblvenderinfo v ON b.vender=v.vender ";
	$sql.= "WHERE 1 ";

	if(strlen($_ShopInfo->getMemid())==0){//��ȸ��
		$sql.= "AND a.tempkey='".$_ShopInfo->getTempkey()."' and memid='' ";
	}else{
		if($tblbasket=="tblbasket_ordernow") {
			$sql.= "AND a.tempkey='".$_ShopInfo->getTempkey()."' ";
		}
		$sql.= "AND a.memid='".$_ShopInfo->getMemid()."' ";
	}

	$sql.= "AND a.productcode=b.productcode ";
	$sql.= "ORDER BY a.rental asc,a.date DESC,b.vender desc ";

	return mysql_query($sql,get_db_conn());
}




// ��ٱ��� ����Ʈ
// ��ǰ �߰� ���ΰ��� �ӽ� ���� : $test['002002000000000002_2_3']="5000";  �迭['��ǰ�ڵ�_�ɼ�1idx_�ɼ�2idx']='��ǰ ���ΰ�'
function getBasketByArray($tblbasket='tblbasket_normal', $productTest = array(),$folder){
	global $_ShopInfo,$Dir,$_data;

	$basketItems = array();
	if(_empty($tblbasket)) $tblbasket='tblbasket_normal';

	$basketItems['productTest'] = "";
	
	if(strlen($_ShopInfo->getMemid())==0){//��ȸ��
		$basketWhere = "tempkey='".$_ShopInfo->getTempkey()."' and memid='' ";
	}else{
		if($tblbasket=="tblbasket_ordernow") {
			$basketWhere = "tempkey='".$_ShopInfo->getTempkey()."' ";
		}
		$basketWhere = "memid='".$_ShopInfo->getMemid()."' ";
	}

	//echo $_ShopInfo->getTempkey();exit;
	if(strlen($_ShopInfo->getMemid()) > 0 && strlen($_ShopInfo->getTempkey())>0){
		$sql ="UPDATE ".$tblbasket." SET memid ='".$_ShopInfo->getMemid()."' WHERE memid='' and tempkey='".$_ShopInfo->getTempkey()."'";
		//echo $sql;
		mysql_query($sql,get_db_conn());
	}

	/*$deli_sql = "SELECT deli_type FROM ".$tblbasket." WHERE tempkey='".$_ShopInfo->getTempkey()."'";
	$deli_res = mysql_query($deli_sql, get_db_conn());
	// ��ۼ���
	list($basketItems['deli_type']) = mysql_fetch_row($deli_res);*/

	// ���� ����Ʈ SQL
	// venderidx 0 �� ����
	if($tblbasket == 'tblbasket2'){
		$sql = "SELECT 0 as venderidx,pridx FROM ".$tblbasket." a inner join tblproduct b on a.productcode=b.productcode WHERE a.".$basketWhere;

	}else{
	/*
		$sql = "SELECT IF(v.deli_super='S',0,b.vender) as venderidx FROM ".$tblbasket." a inner join tblproduct b on a.productcode=b.productcode left join tblvenderinfo v on v.vender = b.vender WHERE a.".$basketWhere;
		if(!_empty($folder)) $sql .= " and a.folder ="._escape($folder);
		$sql .=" GROUP BY venderidx ";

		*/
		$sql = "SELECT IF(v.deli_super='S',0,b.vender) as venderidx,rp.trust_vender,rp.istrust FROM ".$tblbasket." a inner join tblproduct b on a.productcode=b.productcode left join rent_product rp on b.pridx=rp.pridx  left join tblvenderinfo v on v.vender = b.vender WHERE a.".$basketWhere;
		if(!_empty($folder)) $sql .= " and a.folder ="._escape($folder);
		$sql .=" GROUP BY venderidx ";	
	}

	if(false === $res = mysql_query($sql,get_db_conn())) {
		$basketItems['errmsg'] = mysql_error();
	} else{
		$basketItems['sumprice'] = // �ֹ� �� �հ�
		$basketItems['deli_price'] = // �ֹ� �� ��ۺ�
		$basketItems['excp_group_discount'] = //
		$basketItems['reserve'] = // �� ���� ������
		$basketItems['gift_price'] = //
		$basketItems['reserve_price'] = //
		$basketItems['setquotacnt'] =
		
		$basketItems['productcnt'] = 0; // ��ǰ ����
		$basketItems['arr_prlist'] = array();
		$basketItems['errcnt'] = 0;

		// ���� ����Ʈ
		while($vender = mysql_fetch_assoc($res)){
			if(!_isInt($vender['venderidx']))  $vender['venderidx']=0;

			
			// ���� ������� �ʱ�ȭ
			$basketItems['vender'][$vender['venderidx']]['conf'] = array('deli_price'=>0,'deli_pricetype'=>'','deli_after'=>'','deli_mini'=>0,'deli_limit'=>0);

			if(_isInt($vender['venderidx'])){
				// ������ �ϰ�� ������ ����
				if($vender['istrust']=="0"){
					$sql = "SELECT com_name,deli_price, deli_pricetype, deli_mini, deli_limit,booking_confirm FROM tblvenderinfo WHERE vender='".$vender['trust_vender']."' ";
				}else{
					$sql = "SELECT com_name,deli_price, deli_pricetype, deli_mini, deli_limit,booking_confirm FROM tblvenderinfo WHERE vender='".$vender['venderidx']."' ";
				}
				$res2=mysql_query($sql,get_db_conn());
				if($basketItems['vender'][$vender['venderidx']]['conf'] =mysql_fetch_assoc($res2)){
					if($basketItems['vender'][$vender['venderidx']]['conf']['deli_price']==-9) { // ����
						$basketItems['vender'][$vender['venderidx']]['conf']['deli_price'] = 0;
						$basketItems['vender'][$vender['venderidx']]['conf']['deli_after'] = 'Y';
					}
					// if($_vender->deli_mini==0) $_vender->deli_mini=1000000000;??
				}
				mysql_free_result($res2);
			}else{
				// �⺻ �������
				$basketItems['vender'][$vender['venderidx']]['conf']['com_name'] = '����';
				$basketItems['vender'][$vender['venderidx']]['conf']['deli_pricetype'] = $_data->deli_basefeetype;
				$basketItems['vender'][$vender['venderidx']]['conf']['deli_limit'] = $_data->deli_limit;
				$basketItems['vender'][$vender['venderidx']]['conf']['deli_mini'] = $_data->deli_miniprice;
				$basketItems['vender'][$vender['venderidx']]['conf']['deli_price'] = $_data->deli_basefee;
			}
		}

		

		// ���Ÿ������ ī��Ʈ
		$deliCount = array();

		// ����� ��ٱ��� ���� (���� 0 �� ����) ****************************************************
		foreach($basketItems['vender'] as $vender=>&$venderval){

			$result = getBasketByResource($tblbasket,$vender,'',$folder);

			$venderval['sumprice'] = 0; // ���� ���հ�
			$venderval['delisumprice'] = 0; // ���� ��ۺ� ���� �� ���ž�
			$venderval['deliprice'] = 0; // ���� ��ۺ�
			$venderval['deli_productprice']=0; // �׷� ��ǰ ���� ��ۺ�
			$venderval['reserve_price'] = 0; // ���� ��ǰ ���� ������
			$venderval['products'] = array(); // ��ǰ����

			

			// ����� > ��ǰ�� ��ٱ��� ����
			while($product = mysql_fetch_assoc($result)){

				// ����ǰ , ������ , ���� ��� ���� Ȯ��
				$product['cateAuth'] = categoryAuth($product['productcode']);

				// ���� �Լ� �б� 
				
				if($product['rental'] == '2'){
					if(false == solvBasketItemRental($tblbasket,$product)){
						$basketItems['errcnt']++;
						continue;
					}
				}else{
					solvBasketItemNormal($product,$tblbasket);
				}			
				// ��ǰ��
				$basketItems['productcnt']++;

				// ��ǰ�� ����Ʈ
				$basketItems['arr_prlist'][$product['productcode']]=$product['productname'];
				

				// �׷캰 �ߺ����� �ȵǴ� ��ǰ �� �ݾ�
				//_pr($product);
				if($product['group_over_discount'] == 'N') {
					$basketItems['excp_group_discount'] += $product['realprice'] ;
				}


				// ������ ===========================================================================

				$tempreserve = getReserveConversion($product['reserve'],$product['reservetype'],$product['sellprice'],"N");

				//snsȫ���� ��� ������
				if($_data->sns_ok == "Y" && $product['sns_state'] == "Y" && !_empty($product['sell_memid'])) {
					$tempreserve = getReserveConversionSNS($tempreserve,$product['sns_reserve2'],$product['sns_reserve2_type'],$product['sellprice'],"N");
				}

				// ���� ��ǰ �� �ݾ�
				$venderval['sumprice'] += $product['realprice'];

				// ����ǰ ���� �� ��ǰ �Ѿ�
				if($product['cateAuth']['gift'] == 'Y') $basketItems['gift_price'] += $product['realprice'];

				// ������ ���� �� ��ǰ �Ѿ�
				if($product['cateAuth']['reserve'] == 'Y') $basketItems['reserve_price'] += $product['realprice'];


				// ��ǰ ����������
				$product['reserve'] =$tempreserve*$product['quantity'];

				// ������ ������
				$basketItems['reserve'] += $product['reserve'];

				// ��Ÿ ���� (?)
				if(!_empty($product['etctype'])) {
					$etctemp = explode("",$product['etctype']);
					for($i=0;$i<count($etctemp);$i++){
						switch ($etctemp[$i]) {
							case "BANKONLY": $product['bankonly'] = "Y"; break;
							case "SETQUOTA":
								if($_data->card_splittype=="O" && $product['realprice'] >=$_data->card_splitprice){
									$product['setquota'] = 'Y';
									$basketItems['setquotacnt']++;
								}
								break;
						}
					}// end for
				}


				// �̹��� ===================================================================================
				if(!_empty($product['tinyimage']) && file_exists($Dir.DataDir."shopimages/product/".$product['tinyimage'])){
					$product['tinyimage'] = array('ori'=>$product['tinyimage'],'src'=>$Dir.DataDir."shopimages/product/".$product['tinyimage'],'width'=>'','height'=>'');
				}else{
					$product['tinyimage'] = array('ori'=>$product['tinyimage'],'src'=>$Dir."images/no_img.gif",'width'=>'','height'=>'');
				}
				list($product['tinyimage']['width'],$product['tinyimage']['height']) = getImageSize($product['tinyimage']['src']);
				$product['tinyimage']['big'] = ($product['tinyimage']['width'] > $product['tinyimage']['height'])?'width':'height';
				$product['tinyimage']['bigsize'] = $product['tinyimage'][$product['tinyimage']['big']];
				// $product['tinyimage']['src'] = str_replace($_SERVER['DOCUMENT_ROOT'],'/',realpath($product['tinyimage']['src']));





				// ��ۺ� =====================================================================================

				if ($product['deli_type'] == "�ù�") {
					// ��ǰ�� ��ۺ� ����
					if ( ($product['deli']=="Y" || $product['deli']=="N") && $product['deli_price'] > 0 ) {
						// ������ۺ� ���� �̸鼭 ��ۺ� ���� �Ǿ� ���� (Y/0�ʰ�) �Ǵ� �⺻ ��ۺ� (N)
						// deli =>  Y : ���� ��ۺ� (��ۺ�*����) / N : ��ǰ�� ��ۺ�(���� �������)
						// �׷� ��ǰ ���� ��ۺ� : deli_productprice
						$venderval['deli_productprice'] += $product['deli_price']*(($product['deli']=="Y")?$product['quantity']:1);
					} else if($product['deli'] !="F" && $product['deli'] !="G"){
						// ������ۺ� ���ᰡ �ƴϰų� ������ �ƴϸ�.....(������ǰ ����� ������ �н�)
						// ��ۺ� ����� ��ǰ�ݾ�
						$venderval['delisumprice'] += $product['realprice'];
					}

					// ��� Ÿ�� ���� ī����
					//if( $product['deli_price'] == 0 AND $product['deli'] == "N" && $product['rental']!='2' ) { // �⺻��ۺ� �� ��츸
					if( $product['deli_price'] == 0 AND $product['deli'] == "N") { // �⺻��ۺ� �� ��츸
						$basketItems['vender'][$vender]['deliCount'][$product['deli']][($product['deli_price']>0?"1":"0")]++;
					}
				}else{
					$basketItems['vender'][$vender]['deliCount'][$product['deli']][1]++;
				}

				// ��ǰ���� ����Ʈ ���
				array_push($venderval['products'],$product);

			}// end while product

			$deli_init = intval($venderval['delisumprice'])> 0;
			if($deli_init){
				
				if($venderval['conf']['deli_price'] > 0 && ( $venderval['delisumprice']<$venderval['conf']['deli_mini'] OR $venderval['conf']['deli_mini']==0 ) ) {

					$venderval['deliprice'] += $venderval['conf']['deli_price'];

				} else if(!_empty($venderval['conf']['deli_limit'])) {

					// ���� ��۷�
					$deliLimitList = explode("=",$venderval['conf']['deli_limit']);

					for($deliLimitList_i=0; $deliLimitList_i<count($deliLimitList); $deliLimitList_i++) {
						$deliLimitValues=explode("",$deliLimitList[$deliLimitList_i]);
						if(strlen($deliLimitValues[1])>0) {
							// a �̻� ~ b �̸�
							if( $deliLimitValues[0] <= $venderval['delisumprice'] AND $venderval['delisumprice'] < $deliLimitValues[1] ) {
								$venderval['conf']['deli_price'] = $deliLimitValues[2];
								$venderval['deliprice'] += $venderval['conf']['deli_price'];
								break;
							}
						} else {
							// a �̻� ~
							if( $deliLimitValues[0] <= $venderval['delisumprice'] ) {
								$venderval['conf']['deli_price'] = $deliLimitValues[2];
								$venderval['deliprice'] += $venderval['conf']['deli_price'];
								break;
							}
						}
					}
					// ��۷� 0�� (����) ����
					for($deliLimitList_i=0; $deliLimitList_i<count($deliLimitList); $deliLimitList_i++) {
						$deliLimitValues=explode("",$deliLimitList[$deliLimitList_i]);
						if( $venderval['conf']['deli_mini'] == 1000000000 AND $deliLimitValues[2]==0 ){ //AND strlen($deliLimitValues[1])==0
							$venderval['conf']['deli_mini'] = $deliLimitValues[0];
						}
					}

				}else{//������ �⺻��۷��� ��� �߰�(��� ������)
					$venderval['deliprice'] += $venderval['conf']['deli_price'];
				}
			}

			// �׷� ȸ�� ��ۺ� ���� ��å ( Ÿ�� - 1:�⺻, 2:����, 3:�ݾ��̻� )
			if( strlen($_ShopInfo->getMemgroup()) > 0 AND $vender == 0 ) {
				$groupDeli = memberGroupDelivery ( $_ShopInfo->getMemgroup() );
				//// Ÿ�� "1" �⺻ ��� ��å �н�~
				//// Ÿ�� "2" ����
				if( $groupDeli['type'] == "2" ) {
					$venderval['conf']['deli_price'] = 0;
					$venderval['conf']['deli_mini'] = 0;
					$venderval['deliprice'] = 0;
					$venderval['conf']['deli_pricetype'] = $groupDeli['sumtype'];
					$venderval['conf']['groupDeli'] = $groupDeli['type'];
				}
				//// Ÿ�� "3"�ݾ��̻� ����
				$chk_delisumprice = ( $groupDeli['type'] == "Y" ) ? $venderval['delisumprice'] : $venderval['sumprice'] ;
				if( $groupDeli['type'] == "3" AND $groupDeli['money'] < $chk_delisumprice AND $venderval['conf']['deli_mini'] >= $groupDeli['money'] ) {
					$venderval['conf']['deli_price'] = 0;
					$venderval['conf']['deli_mini'] = $groupDeli['money'];
					$venderval['deliprice'] = 0;
					$venderval['conf']['deli_pricetype'] = $groupDeli['sumtype'];
					$venderval['conf']['groupDeli'] = $groupDeli['type'];
				}
			}


			// ������ۺ� �߰�
			$venderval['deliprice']+=$venderval['deli_productprice'];

			// ������ۻ�ǰ ����(Y) / ����(N)
			if($venderval['conf']['deli_pricetype']=="Y") $venderval['delisumprice'] = $venderval['sumprice'];


			// �� ��ۺ�
			$basketItems['deli_price']+=$venderval['deliprice'];

			//�� �ֹ��ݾ�
			$basketItems['sumprice'] += $venderval['sumprice'];

		}//end foreach vender



		// ȸ���׷캰 �߰�����/�߰����� ��å
		if( strlen($_ShopInfo->getMemid())>0 AND strlen($_ShopInfo->getMemgroup())>0 AND substr($_ShopInfo->getMemgroup(),0,1)!="M" AND isSeller() != 'Y' ) {

			$basketItems['groupMemberSale'] = array();

			$arr_dctype=array("B"=>"����","C"=>"ī��","N"=>"����/ī��");

			$memGroupSQL = "
				SELECT
					a.name, b.group_code, b.group_name, b.group_payment, b.group_usemoney, b.group_addmoney
				FROM
					tblmember a,
					tblmembergroup b
				WHERE
					a.id='".$_ShopInfo->getMemid()."'
					AND
					b.group_code=a.group_code
					AND
					MID(b.group_code,1,1)!='M'
			";
			$memGroupResult=mysql_query($memGroupSQL,get_db_conn());
			if($memGroupRow=mysql_fetch_object($memGroupResult)) {
				$basketItems['groupMemberSale']['name'] = $memGroupRow->name; // ȸ����
				$basketItems['groupMemberSale']['group'] = $memGroupRow->group_name; // �׷��
				$basketItems['groupMemberSale']['useMoney'] = $memGroupRow->group_usemoney; // ���رݾ�
				$basketItems['groupMemberSale']['payType'] = $arr_dctype[$memGroupRow->group_payment]; // �������
				$basketItems['groupMemberSale']['payTypeCode'] = $memGroupRow->group_payment; // �������
				$basketItems['groupMemberSale']['groupCode'] = $type=substr($memGroupRow->group_code,0,2); // �׷��ڵ�
				$basketItems['groupMemberSale']['addMoney'] = $memGroupRow->group_addmoney; // ����ݾ� or ����%
				/*
					RW : �ݾ� �߰� ����
					RP  : % �߰� ����
					SW : �ݾ� �߰� ����
					SP  : % �߰� ����
				*/
			}
			mysql_free_result($memGroupResult);
		}






		// �����
		$tax_free = 0;
		foreach ( $basketItems['arr_prlist'] as $p_code=> $p_name) {

			$chkTaxFree = checkTaxFree($p_code);
			if ($chkTaxFree['tax_yn']) {

				$add_tax_free=0;

				foreach ($basketItems['vender'][$chkTaxFree['vender']]['products'] as $pro_array) {
					if ($pro_array['productcode'] == $p_code) {
						$add_tax_free += $pro_array['realprice'];
					}

				}
				$tax_free = $tax_free + $add_tax_free;
			}

		}
		$basketItems['tax_free'] = $tax_free;

	}// end if


	/*
	echo "<div style=\" height:500px; overflow:scroll;  border:2px solid #ff0000 ;  text-align:left;\">";
	_pr($basketItems);
	echo "</div>";
	*/
	//_pr($basketItems);
	return $basketItems;
}

function solvBasketItemNormal(&$product,$tblbasket){
	global $_ShopInfo;
// �ɼ� ==============================================================================================
	if(!_empty($product['option1']) && substr($product['option1'],0,5) != '[OPTG' ) $product['option1'] = explode(',',$product['option1']);
	if(!_empty($product['option2'])) $product['option2'] = explode(',',$product['option2']);
	if(!_empty($product['option_quantity'])) $product['option_quantity'] = explode(',',$product['option_quantity']);
	if(!_empty($product['option_price'])) $product['option_price'] = explode(',',$product['option_price']);
	if($product['cateAuth']['reserve'] == "N") $product['reserve'] = 0;
	if(_array($product['option_price']) && $product['opt1_idx']==0){
		
		$sql = "DELETE FROM ".$tblbasket." WHERE productcode='".$product['productcode']."' AND opt1_idx='".$product['opt1_idx']."' AND opt2_idx='".$product['opt2_idx']."' AND optidxs='".$product['optidxs']."' ";

		if(strlen($_ShopInfo->getMemid())==0){//��ȸ��
			$sql.= "AND tempkey='".$_ShopInfo->getTempkey()."' and memid='' ";
		}else{
			if($tblbasket=="tblbasket_ordernow") {
				$sql.= "AND tempkey='".$_ShopInfo->getTempkey()."' ";
			}
			$sql.= "AND memid='".$_ShopInfo->getMemid()."' ";
		}

		mysql_query($sql,get_db_conn());
		_alert("�ʼ� ���� �ɼ� �׸��� �ֽ��ϴ�.\\n�ɼ��� �����Ͻ��� ��ٱ��Ͽ�\\n�����ñ� �ٶ��ϴ�.",$Dir.FrontDir."productdetail.php?productcode=".$product['productcode']);
		exit;
	}
	// �ɼǱ׷�
	$product['optvalue'] = "";
	$optvalue = "";
	if(ereg("^(\[OPTG)([0-9]{4})(\])$",$product['option1'])){
		$product['optionGroup'] = $product['option1'];
		$optioncode = substr($product['option1'],5,4);
		//$product['option1']="";
		$product['option_price']="";

		if(!_empty($product['optidxs'])){
			$tempoptcode = substr($product['optidxs'],0,-1);
			$exoptcode = explode(",",$tempoptcode);
			$resultopt = mysql_query("SELECT * FROM tblproductoption WHERE option_code='".$optioncode."' ",get_db_conn());
			if($rowopt = mysql_fetch_object($resultopt)){
				$optionadd = array (&$rowopt->option_value01,&$rowopt->option_value02,&$rowopt->option_value03,&$rowopt->option_value04,&$rowopt->option_value05,&$rowopt->option_value06,&$rowopt->option_value07,&$rowopt->option_value08,&$rowopt->option_value09,&$rowopt->option_value10);

				$option_choice = $rowopt->option_choice;
				$exoption_choice = explode("",$option_choice);
				foreach($optionadd as $opti=>$optv){
					if(_empty($optv)) continue;
					if($exoption_choice[$opti]==1 && $exoptcode[$opti]==0){
						$delsql = "DELETE FROM ".$tblbasket." WHERE productcode='".$product['productcode']."' AND opt1_idx='".$product['opt1_idx']."' AND opt2_idx='".$product['opt2_idx']."' AND optidxs='".$product['optidxs']."' ";

						if(strlen($_ShopInfo->getMemid())==0){//��ȸ��
							$delsql.= "AND tempkey='".$_ShopInfo->getTempkey()."' and memid='' ";
						}else{
							if($tblbasket=="tblbasket_ordernow") {
								$delsql.= "AND tempkey='".$_ShopInfo->getTempkey()."' ";
							}
							$delsql.= "AND memid='".$_ShopInfo->getMemid()."' ";
						}
						mysql_query($delsql,get_db_conn());
						_alert("�ʼ� ���� �ɼ� �׸��� �ֽ��ϴ�.\\n�ɼ��� �����Ͻ��� ��ٱ��Ͽ�\\n�����ñ� �ٶ��ϴ�.",$Dir.FrontDir."productdetail.php?productcode=".$product['productcode']);
						exit;
					}
					if($exoptcode[$opti]>0){
						$opval = explode("",str_replace('"','',$optv));
						$optvalue.= ", ".$opval[0]." : ";
						$exop = explode(",",str_replace('"','',$opval[$exoptcode[$opti]]));
						if ($exop[1]>0) $optvalue.=$exop[0]."(<font color=#FF3C00>+".number_format($exop[1])."��</font>)";
						else if($exop[1]==0) $optvalue.=$exop[0];
						else $optvalue.=$exop[0]."(<font color=#FF3C00>".number_format($exop[1])."��</font>)";
						$product['sellprice']+=$exop[1];
					}
				} // end foreach
				$product['optvalue'] = substr($optvalue,1);
			}
		}
	}

	// �ɼǿ� ���� ���� ���� üũ
	if (_array($product['option_price']) && !_empty($product['opt1_idx'])) {
		$product['sellprice'] = $product['option_price'][$product['opt1_idx']-1];
	}



	// ��ǰ�� ȸ�������� ���� ���� : ��ǰ���� ����� ȸ�� �׷캰 ����
	// ���� ȸ���̰ų� ��ȸ���ǰ�� ������
	// over_discount : Y �׷캰 �ߺ����ΰ��� / N �ߺ��Ұ�(��ǰ�� ���θ� ����)
	$product['group_discount'] = 0;
	if( isSeller() != 'Y' AND strlen($_ShopInfo->getMemgroup()) > 0 ) {
		$dSql = "SELECT discount,over_discount FROM tblmemberdiscount WHERE productcode='".$product['productcode']."' AND group_code='".$_ShopInfo->getMemgroup()."' AND discountYN='Y'";
		
		if( false !== $dResult = mysql_query($dSql,get_db_conn()) ) {
			if(mysql_num_rows($dResult)){
				$gtmp =  mysql_fetch_row($dResult);
				$product['group_over_discount'] = $gtmp['over_discount'];				
				$product['group_discount'] = 0;
				if($gtmp['discount'] > 0){
					if($gtmp['discount'] < 1) $product['group_discount'] = $product['sellprice']*$gtmp['discount'];
					else $product['group_discount'] = $gtmp['discount'];
				}
			}
		}
	}

	// �׷� ���� ����
	$product['sellprice'] -= $product['group_discount'];


	// �ӽ� ���� ���� ó��
	$key = $product['productcode']."_".$product['opt1_idx']."_".$product['opt2_idx']."_".$product['optidxs'];
	if ( _array($productTest) ) {
		if( strlen($productTest[$key]) > 0 ) {
			$product['sellprice'] -= $productTest[$key];
			$basketItems['productTest'] = "TEST_MODE";
		}
	}
	// ��ǰ �ǸŰ� (���ż�����)
	$product['realprice'] = $product['sellprice']*$product['quantity'];	
}


function solvBasketItemRental($baskettable,&$product){	
	global $_ShopInfo;
	
	if($product['cateAuth']['reserve'] == "N") $product['reserve'] = 0;
	$product['realprice'] = 0;
	$rentP = rentProduct::read($product['pridx']);

	//_pr($rentP);
	if(!$rentP){
		_alert('��Ż �Ǻ� ����','-1');
		exit;
	}
	if(!_isInt($product['basketidx'])){
		_alert('�ʼ��� ���� ����','-1');
		exit;
	}
	
	$inbasket = rentProduct::readBasket($baskettable,$_ShopInfo->getTempkey(),$rentP['pridx'],$product['basketidx'],$_ShopInfo->getMemid());
	$items = array();
	if(_array($inbasket['items'][$product['pridx']])){
		foreach($inbasket['items'][$product['pridx']]  as $basketitems){
			$bitem = array();
			$bitem[$basketitems['optidx']] = $basketitems['quantity'];
			$basketitems['solvprice'] = rentProduct::solvPrice($product['pridx'],$bitem,$basketitems['start'],$basketitems['end'],$rentP['vender']);		
			
			$product['realprice'] += $basketitems['solvprice']['totalprice'] - abs($basketitems['solvprice']['discprice']);
			$product['sellprice'] = $product['realprice']/$basketitems['quantity'];
			$product['longdiscount'] =$basketitems['solvprice']['discprice'];
			$product['rentinfo'] = $basketitems;
		}		
		return true;
	}else{	
		$sql = "delete from ".$baskettable." where basketidx='".$product['basketidx']."'";
		@mysql_query($sql,get_db_conn());
		return false;
	}
}


// ����� ����
function checkTaxFree($p_code) {

	$result = array();
	$result['tax_yn'] = 0;
	$result['vender'] = 0;

	$sql = "select tax_yn, vender from tblproduct where productcode='".$p_code."'";
	$resultTax = mysql_query($sql,get_db_conn());
	$rowTax = mysql_fetch_array($resultTax);

	if ($rowTax[0]==1) {
		$result['tax_yn'] = 1;
		$result['vender'] = $rowTax[1];
	}
	return $result;
}







// ���ǿ� ���� �ֹ� ��� ��ȯ
function getOrderList($range_start='',$range_end='',$ordergbn='',$type='',$page='1',$itemtype='object',$limit=''){
	global $_ShopInfo,$setup;
	$where = array();
	$result = array('total'=>0,'total_page'=>0,'page'=>1,'orders'=>array());
	if(!_empty($_ShopInfo->getMemid())){
		array_push($where,"id="._escape($_ShopInfo->getMemid()));
		if(!_empty($range_start) && preg_match('/^[0-9]{6,}$/',$range_start)) array_push($where,"ordercode >= '".$range_start."'");
		if(!_empty($range_end) && preg_match('/^[0-9]{6,}$/',$range_end)) array_push($where,"ordercode <= '".$range_end."'");
		switch($ordergbn){
			case 'S':
				array_push($where,"deli_gbn IN ('S','Y','N','X')");
				break;
			case 'C':
				array_push($where,"deli_gbn IN ('C','D')");
				break;
			case 'R':
				//array_push($where,"(deli_gbn IN ('R','E') OR SUBSTRING(status,1,1)='Y')");
				array_push($where," ( select count(*) from tblorderproduct where ordercode=o.ordercode and status in ('RA', 'RC'))>0 ");
				break;
			case 'P':
				array_push($where,"order_type ='P'");
				break;
			case 'T':
				array_push($where,"( SELECT count(*) FROM `tblproduct` P INNER JOIN `tblorderproduct` OP ON OP.`productcode` = P.`productcode` WHERE OP.`ordercode` = o.ordercode AND P.reservation !='0000-00-00' ) > 0 " );
				break;
		}

		array_push($where,"(del_gbn='N' OR del_gbn='A')");

		switch($type){
			case '2':
				array_push($where,"gift='3'");
				break;
			case '3':
				array_push($where,"gift in('1','2')");
				break;
			default:
				array_push($where,"gift='0'");
				break;
		}
		$where = "where ".implode(" AND ",$where);

		$sql = "SELECT COUNT(*) as t_count FROM tblorderinfo o ".$where;
	
		$res=mysql_query($sql,get_db_conn());
		if($res) $result['total'] = intval(mysql_result($res,0,0));

		if($result['total'] > 0){
			@$result['total_page'] = ceil($result['total']/$setup['list_num']);
			@$result['page'] = min((intval($page) < 0)?1:intval($page),$result['total_page']);
			$ordby = " ORDER BY ordercode DESC ";
			@$limit = " LIMIT ".($setup['list_num']*($result['page']-1)).", ".$setup['list_num'];
			if(_isInt($limit)) $limit = " LIMIT ".$limit;
			else if(_isInt($setup['list_num']))  @$limit = " LIMIT ".($setup['list_num']*($result['page']-1)).", ".$setup['list_num'];
			else $limit = " LIMIT 10";
			$sql = "SELECT ordercode, price, paymethod, pay_admin_proc, pay_flag, bank_date, deli_gbn, gift, tempkey FROM tblorderinfo o ".$where.$ordby.$limit;
			$res = mysql_query($sql,get_db_conn());
			if($res){
				if($itemtype == 'array'){
					while($item = mysql_fetch_assoc($res)) 	array_push($result['orders'],$item);
				}else{
					while($item = mysql_fetch_object($res)) 	array_push($result['orders'],$item);
				}
			}
		}
	}
	return $result;
}

// mypage �󿡼� 1���� �� �ֹ� ��ȸ
function getMyOrderList($limit,$type='',$itemtype='object'){
	$curdate=date("Ymd",mktime(0,0,0,(int)date("m")-1,(int)date("d"),date("Y")));
	return getOrderList($curdate,'','',$type,'1',$itemtype,$limit);
}



// �ֹ� �ڵ峻�� �ֹ���ǰ ������ ��ȯ
function getOrderProduct($ordercode,$itemtype='object'){
	$result = array();
	$sql = "SELECT op.*,op.quantity*op.price as sumprice,p.pridx,p.tinyimage,p.minimage, p.reservation,r.optidx,r.location,r.start,r.end,r.status as rentstatus,r.regDate, r.returnDate, rp.trust_vender,rp.istrust FROM tblorderproduct op left join tblproduct p on (op.productcode = p.productcode) left join rent_schedule r on r.ordercode=op.ordercode and r.basketidx=op.basketidx left join rent_product rp on p.pridx=rp.pridx WHERE op.ordercode='".$ordercode."' AND (NOT (op.productcode LIKE 'COU%' OR op.productcode LIKE '999999%') or  op.productcode = '99999990GIFT') order by productcode ";

	$res = mysql_query($sql,get_db_conn());
	
	if($res){
		if($itemtype == 'array'){
			while($item = mysql_fetch_assoc($res)) array_push($result,$item);
		}else{
			while($item = mysql_fetch_object($res)) array_push($result,$item);
		}
	}
	return $result;
}


// �ֹ� �ڵ峻�� �ֹ���ǰ �� ������ ��ȯ
function getOrderAddtional($ordercode,$itemtype='object') {
	$result = array();
	$sql = "SELECT op.* FROM tblorderproduct op  WHERE op.ordercode='".$ordercode."' AND  (op.productcode LIKE 'COU%' OR op.productcode LIKE '999999%') and op.productcode != '99999990GIFT' ";
	$res = mysql_query($sql,get_db_conn());
	if($res){
		if($itemtype == 'array'){
			while($item = mysql_fetch_assoc($res)) array_push($result,$item);
		}else{
			while($item = mysql_fetch_object($res)) array_push($result,$item);
		}
	}
	return $result;
}





// ��ü ���·� ���� ���� �ֹ��� ��ǰ ������, �ֹ� ������ �ش� ��ǰ�� �ֹ� ���� �޽����� ��ȯ
function orderProductDeliStatusStr($orderproduct,$orderinfo, $cnt){
	$str = '';

	switch($orderproduct->deli_gbn){
		case "C": $str ="�ֹ����"; break;
		case "D": $str ="<font color='#ff0000'>��ҿ�û</font>"; break;
		case "W": $str ="<font color='#ff0000'>���öȸ��û</font>"; break;
		case "E": $str = "ȯ�Ҵ��"; break;
		case "S":
			$str = "�߼��غ�";
			//ȯ�� ������ ������� jdy
		//	$str = getRefundsText($str, $orderproduct, "yes", $orderinfo, $cnt);

			break;
		case "R": $str = "�ݼ�ó��"; break;
		case "H": $str = "�߼ۿϷ� [���꺸��]"; break;
		case "X": $str = ($orderproduct->gift=='1')?"������ȣ�߼�":"�߼��غ�"; break;

		//����� Y�� N, C �� ���?? ��� ����(C)�� �ƴ϶�� �ֹ� ��Ұ� ��. jdy
		case "Y":
			if($orderinfo->gift=='1') $str = "�����������Ϸ�";
			else if($orderinfo->gift=='2') $str = '�����Ϸ�';
			else $str = '�����';

			if ($orderinfo->pay_admin_proc=="C" && $orderinfo->pay_flag=="0000") {
				$str = "�������";
			}else  {

				//ȯ�� ������ ������� jdy
				//$str = getRefundsText($str, $orderproduct, "yes", $orderinfo, $cnt);
			}

			break;

		case"N":
			if (strlen($orderinfo->bank_date)<12 && preg_match("/^(B|O|Q){1}/", $orderinfo->paymethod)) {
				$str = "�Ա�Ȯ����";
				//�Ա�Ȯ���� ��? ��ü�� �̿��� ��Ҹ� �����ϵ���
			//	$str = getRefundsText($str, $orderproduct, "no", $orderinfo, $cnt);

			}else if ($orderinfo->pay_admin_proc=="C" && $orderinfo->pay_flag=="0000") {
				$str = "�������";
			}else if (strlen($orderinfo->bank_date)>=12 || $orderinfo->pay_flag=="0000")  {

//				$str = "�߼��غ�";
				$str = "�����Ϸ�";
			//	$str = getRefundsText($str, $orderproduct, "yes", $orderinfo, $cnt);

			}else {
				$str = "����Ȯ����";
			}
			break;
		default:
			$str = "����"; break;
	}

	return $str;
}

// �ֹ� �� ��ǰ�� ���� ���� ���
function orderProductStatusStr($status){
	$status_arr = array('EA'=>'��ȯ��û','EB'=>'��ȯ����','EC'=>'��ȯ�Ϸ�','RA'=>'ȯ�ҽ�û','RB'=>'ȯ������','RC'=>'ȯ�ҿϷ�'); // �ֹ��� ���� �� �޽���
	$str = '';
	if(!_empty($status) && !_empty($status_arr[$status])) $str = $status_arr[$status];
	return $str;
}

// ���� ��� text ���
function getPaymethodStr($paymethod){
	$str = '';
	if (preg_match("/^(B){1}/",$paymethod)) $str = '�������Ա�';
	else if (preg_match("/^(V){1}/",$paymethod)) $str = '�ǽð�������ü';
	else if (preg_match("/^(O){1}/",$paymethod)) $str = '�������';
	else if (preg_match("/^(Q){1}/",$paymethod)) $str = '�������-<FONT COLOR="#FF0000">�Ÿź�ȣ</FONT>';
	else if (preg_match("/^(C){1}/",$paymethod)) $str = '�ſ�ī��';
	else if (preg_match("/^(P){1}/",$paymethod)) $str = '�ſ�ī��-<FONT COLOR="#FF0000">�Ÿź�ȣ</FONT>';
	else if (preg_match("/^(M){1}/",$paymethod)) $str = '�޴���';

	return $str;
}


//��� ��ȣ
function getVenderIdx($code){
	$sql = "SELECT vender FROM tblproduct WHERE productcode='".$code."'";
	$res = mysql_query($sql,get_db_conn());
	$row = mysql_fetch_object($res);
	return $row;
}

// ȸ����޺� ��ۺ� - tblmembergroup - group_carr_free
function memberGroupDelivery ( $group_code ) {
	$sql = "SELECT group_carr_free FROM tblmembergroup WHERE group_code='".$group_code."' LIMIT 1;";
	$res = mysql_query($sql,get_db_conn());
	$row = mysql_fetch_object($res);
	$set = explode("|",$row->group_carr_free);
	$return = array();
	$return['type'] = $set[0];
	$return['money'] = $set[1];
	if( $set[0] == '3' ) $return['sumtype'] = $set[2];
	return $return;
}

//��Ұ��� ��ư jdy
function getRefundsText($str, $orderproduct, $can="yes", $orderinfo, $cnt ) {

	if ($orderproduct->status) {
		switch($orderproduct->status){
			case 'EA': $str="��ȯ��û";  break;
			case 'EB': $str= "��ȯ����";  break;
			case 'EC': $str= "��ȯ�Ϸ�";  break;
			case 'RA': $str= "ȯ�ҽ�û";  break;
			case 'RB': $str= "ȯ������";  break;
			case 'RC': $str= "ȯ�ҿϷ�";  break;
		}
	}else {

		//���� ��� ��ư �߰� jdy
		if( $cnt == 1 ) {
			$str .= "<div style=\"margin-top:3px\"><span style=\"cursor:pointer\" onclick=\"order_cancel('".$orderproduct->tempkey."', '".$orderproduct->ordercode."','".$orderinfo->bank_date."')\"><img src=\"/images/common/mypage/001/mypage_order_cancel_icon01.gif\" alt=\"�ֹ����\" /></span></div>";
		}else{
			$str .= "<div style=\"margin-top:3px\"><span style=\"cursor:pointer\" onclick=\"order_one_cancel('".$orderproduct->ordercode."', '".$orderproduct->productcode."', '".$can."', '".$orderproduct->tempkey."', '".$orderproduct->uid."')\"><img src=\"/images/common/mypage/001/mypage_order_cancel_icon01.gif\" alt=\"�ֹ����\" /></span></div>";
		}
	}

	return $str;
}





// �ֹ�Ÿ�Ժ� ��ٱ��� ���̺�
function basketTable($ordertype) {
	$basketList = array(
		//'' => 'tblbasket', // �Ϲ� ��ٱ���
		'' => 'tblbasket_normal', // �Ϲ� ��ٱ���
		'prebasket' => 'tblbasket_normal', // ���� ��ٱ���
		'order' => 'tblbasket', // �Ϲ� ��ٱ��� �ֹ�
		'pester' => "tblbasket_pester", // ������
		'pstr' => "tblbasket_pester_order", // ������ ����
		'pestersave' => "tblbasket_pester_save", // ������ ����
		'present' => "tblbasket_present", // �����ϱ�
		'ordernow' => "tblbasket_ordernow", // �ٷα���
		'recommand'=>"tblbasket_recommand" // Ÿȸ�� ��õ
		
	);
	return $basketList[$ordertype];
}



function getOrderCnt(){
	global $_ShopInfo;
	$result = array();
	if(!_empty($_ShopInfo->getMemid())){
		$oiSQL = "SELECT ";
		$oiSQL .= "COUNT(distinct oi.ordercode) AS ordercount, "; // �ֹ���Ȳ ��
		$oiSQL .= "SUM(IF((oi.pay_admin_proc = 'Y' OR oi.pay_admin_proc = 'N') AND oi.deli_gbn = 'S',1,0)) AS delireadycount, "; // �߼��غ� ��
		$oiSQL .= "SUM(IF(oi.deli_gbn = 'C',1,0)) AS ordercancel, "; // �ֹ���� ��
		$oiSQL .= "SUM(IF(oi.deli_gbn = 'Y', 1,0)) AS delicomplatecount, "; // �߼ۿϷ� ��
		$oiSQL .= "SUM(IF(op.status = 'RA',1,0)) AS refundcount, "; //ȯ�ҿ�û��
		$oiSQL .= "SUM(IF(op.status = 'RC',1,0)) AS repaymentcount "; //ȯ�ҿϷ��
		$oiSQL .= "FROM tblorderinfo AS oi LEFT OUTER JOIN tblorderproduct AS op ON(oi.ordercode = op.ordercode) ";
		$oiSQL .= "WHERE oi.id = '".$_ShopInfo->getMemid()."' ";
	//	echo $oiSQL;
		if(false !== $ores = mysql_query($oiSQL,get_db_conn())){
			$result = mysql_fetch_assoc($ores);
		}
		
		$sql = "select count(ordercode) from tblorderinfo where pay_admin_proc!='C' and deli_gbn='N' and ((paymethod = 'B' and length(bank_date) > 10) || (substr(paymethod,1,1) in ('O','Q') and pay_flag='0000' and length(bank_date) > 1) || (substr(paymethod,1,1) not in ('O','Q') and pay_flag='0000')) and id = '".$_ShopInfo->getMemid()."'";

		if(false !== $ores = mysql_query($sql,get_db_conn())){
			$tmp = mysql_fetch_assoc($ores);
			$result['pay'] = mysql_result($ores,0,0);
		}
		
		$result['unpay'] = $result['ordercount'] - $result['delireadycount'] - $result['delicomplatecount'] - $result['pay']; 
	}
	return $result;
}
?>
