<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/shopdata.php");
include_once($Dir."lib/ext/product_func.php");
include_once($Dir."lib/ext/member_func.php");
include_once($Dir."lib/ext/order_func.php");
include_once($Dir."lib/ext/coupon_func.php");


$ordertype=$_GET["ordertype"];


$receiver_name= "";
$receiver_email= "";
$receiver_tel11 = "";
$receiver_tel12 = "";
$receiver_tel13 = "";
$receiver_tel21 = "";
$receiver_tel22 = "";
$receiver_tel23 = "";
$receiver_zip1 = "";
$receiver_zip2 = "";
$receiver_addr1 = "";
$receiver_addr2 = "������ �ּ�";

// ������ ����
$pstr=$_GET["pstr"];
if( strlen($pstr) > 3 ){

	$sql = "SELECT * FROM tblpesterinfo WHERE code='".$pstr."' AND state='0'";

	$result=mysql_query($sql,get_db_conn());
	if($row=mysql_fetch_object($result)) {

		//_pr($row);

		$pester_code=$row->code;
		$pester_id=$row->id;
		$tempkey=$row->tempkey;
		$sender_name=$row->sender_name;
		$sender_tel=$row->sender_tel;
		$pester_state=$row->state;

		$receiver_name=$row->receiver_name;
		$receiver_email=$row->sender_email;
		$receiver_tel1 = explode("-",$row->receiver_tel1);
		$receiver_tel11 = $receiver_tel1[0];
		$receiver_tel12 = $receiver_tel1[1];
		$receiver_tel13 = $receiver_tel1[2];
		$receiver_tel2 = explode("-",$row->receiver_tel2);
		$receiver_tel21 = $receiver_tel2[0];
		$receiver_tel22 = $receiver_tel2[1];
		$receiver_tel23 = $receiver_tel2[2];
		$receiver_addr = explode("=",$row->receiver_addr);
		$receiver_zip = explode("-",$receiver_addr[0]);
		$receiver_zip1 = $receiver_zip[0];
		//$receiver_zip2 = $receiver_zip[1];
		$receiver_addr1 = $receiver_addr[1];
		$receiver_addr2 = $receiver_addr[2];

		$sql2 = "SELECT group_code FROM tblmember WHERE id='".$pester_id."' ";
		$result2 = mysql_query($sql2);
		if($row2 = mysql_fetch_object($result2)) {
			$group_code=$row2->group_code;
		}
		mysql_free_result($result2);


		// ��ٱ��� ���� �ؿ���
		$_COOKIE["basketauthkey"] = $tempkey;
		setcookie("basketauthkey", $tempkey, time()+3600, "/".RootPath, getCookieDomain());
		mysql_query("INSERT INTO tblbasket_pester_order SELECT * FROM tblbasket_pester_save WHERE tempkey='".$tempkey."'",get_db_conn());

		$ordertype = "pstr";
	}
	mysql_free_result($result);
	if(!$pester_code){
		echo "<script>alert('�̹� �����ϼ̰ų� �Ǹ� �Ұ��� ��ǰ�Դϴ�.');if(parent){parent.location.href=\"/\";}else{location.href=\"/\";}</script>";
		exit;
	}
}


// �ֹ�Ÿ�Ժ� ��ٱ��� ���̺�
if(_empty($ordertype)) $basket = basketTable('order');
else $basket = basketTable($ordertype);
/*
if(_empty($ordertype)) $basket = basketTable('');
else $basket = basketTable($ordertype);
*/
//ȸ�������� ��� �α���������...
if($_data->member_buygrant=="Y" && strlen($_ShopInfo->getMemid())==0) {
	Header("Location:".$Dir.FrontDir."login.php?chUrl=".getUrl());
	exit;
}


if(strlen($_ShopInfo->getMemid())==0 or $ordertype=="ordernow") {	//��ȸ��
	$basketWhere = "tempkey='".$_ShopInfo->getTempkey()."'";
}else{
	$basketWhere = "memid='".$_ShopInfo->getMemid()."'";
}

//��ٱ��� ����Ű Ȯ��
if(strlen($_ShopInfo->getTempkey())==0 || $_ShopInfo->getTempkey()=="deleted") {
	$_ShopInfo->setTempkey($_data->ETCTYPE["BASKETTIME"]);
}
if(strlen($_ShopInfo->getMemid()) > 0){
	// checkneed
	$sql ="UPDATE tblbasket SET sell_memid ='' WHERE ".$basketWhere." AND sell_memid='".$_ShopInfo->getMemid()."'";
	mysql_query($sql,get_db_conn());
}

if($ordertype == 'recommand'){ // Ÿȸ�� ��õ ���� ��� ó��
	if(!_empty($_REQUEST['rcode'])){
		if(substr($_REQUEST['rcode'],0,5) != 'RECOM'){
			_alert('��ġ �ϴ� ������ �����ϴ�.','/');
			exit;
		}

		$sql = "select * from recommand_request where recomcode='".substr($_REQUEST['rcode'],5)."' limit 1";
		if(false === $res = mysql_query($sql,get_db_conn())) _alert('DB ȣ�� ����','/');
		if(mysql_num_rows($res) < 1) _alert('��ġ�ϴ� ������ ã�� �� �����ϴ�.','/');
		
		$reqinfo = mysql_fetch_assoc($res);
		//if(!_empty($reqinfo['ordercode'])) _alert('�̹� ���� ó�� �� ��õ���Դϴ�.','/');		
		$recomorder = true;
		$recomcode =$reqinfo['recomcode'];

		@mysql_query("delete from tblbasket_recommand where ".$basketWhere,get_db_conn());
		
		//��õ�� ��ǰ �ߺ� �ֹ������ϵ��� ����
		$sql2 = "select * from recommand_basket where recomcode='".$recomcode."' limit 1";
		if(false === $res2 = mysql_query($sql2,get_db_conn())) _alert('DB ȣ�� ����','/');
		if(mysql_num_rows($res2) > 0){
			$reqinfo2 = mysql_fetch_assoc($res2);
			$recom_folder = $reqinfo2['memid']."_".substr($_REQUEST['rcode'],5,8);
			@mysql_query("delete from tblbasket_recommand where basketidx='".$reqinfo2['basketidx']."'",get_db_conn());
		}
//echo substr($_REQUEST['rcode'],5,8);exit;

		mysql_query("insert into tblbasket_recommand (basketidx,tempkey,productcode,opt1_idx,opt2_idx,optidxs,quantity,deli_type,date,sell_memid,ordertype,memid) select basketidx,'".$_ShopInfo->getTempkey()."',productcode,opt1_idx,opt2_idx,optidxs,quantity,deli_type,date,'','recommand','".$_ShopInfo->getMemid()."' from recommand_basket where recomcode ='".$recomcode."'");
		//mysql_query("insert into tblbasket_nomal (basketidx,tempkey,productcode,opt1_idx,opt2_idx,optidxs,quantity,deli_type,date,sell_memid,ordertype,memid,folder) select basketidx,'".$_ShopInfo->getTempkey()."',productcode,opt1_idx,opt2_idx,optidxs,quantity,deli_type,date,'','recommand','".$_ShopInfo->getMemid()."','".$recom_folder."' from recommand_basket where recomcode ='".$recomcode."'");

	}else if(_empty($_ShopInfo->getMemid())){
		_alert('�α��� �Ǿ� ���� �ʽ��ϴ�.','-1');
		exit;
	}else{
	 	@mysql_query("delete from tblbasket_recommand where ".$basketWhere,get_db_conn());
		mysql_query("insert into tblbasket_recommand (basketidx,tempkey,productcode,opt1_idx,opt2_idx,optidxs,quantity,deli_type,date,sell_memid,ordertype,memid) select basketidx,'".$_ShopInfo->getTempkey()."',productcode,opt1_idx,opt2_idx,optidxs,quantity,deli_type,date,'','recommand',memid from recommand_basket where recomcode is null and memid='".$_ShopInfo->getMemid()."'");
	}
}


// ��ٱ��� ������ (Array) ==================================================
$basketItems = getBasketByArray($basket);
//_pr($basketItems);

if($ordertype == 'recommand' && $recomorder !== true){ // Ÿȸ�� ��õ ���� ��� ó��	
	require_once $Dir.'templet/order/order_recommand.php';
	exit;
}

/*
ȸ�� ��� ���� �޼��� ============
	RW : �ݾ� �߰� ����
	RP  : % �߰� ����
	SW : �ݾ� �߰� ����
	SP  : % �߰� ����
*/
$groupMemberSale = "";
if( $basketItems['groupMemberSale'] ) {
	$groupMemberSale .= "
		<font style=\"letter-spacing:0px;\"><b>".$basketItems['groupMemberSale']['name']."</b></font>��(".$basketItems['groupMemberSale']['group'].")�� ȸ�� ��� ����
		<font color=\"#ee0a02\" style=\"letter-spacing:0px;\">".number_format($basketItems['groupMemberSale']['useMoney'])."</font>�� �̻�
		<font  color=\"#ee0a02\">".$basketItems['groupMemberSale']['payType']."</font> ������
	";
	if($basketItems['groupMemberSale']['groupCode']=="RW") {
		$groupMemberSale .= "<font color=#ee0a02 style=letter-spacing:0px;><b>".number_format($basketItems['groupMemberSale']['addMoney'])."</b>��</font>�� �������� �߰��� ������ �帳�ϴ�.";
	} else if($basketItems['groupMemberSale']['groupCode']=="RP") {
		$groupMemberSale .= "<font color=#ee0a02 style=letter-spacing:0px;><b>���űݾ��� ".number_format($basketItems['groupMemberSale']['addMoney'])."</b>%</font>�� ������ �帳�ϴ�.";
	} else if($basketItems['groupMemberSale']['groupCode']=="SW") {
		$groupMemberSale .= "<font color=#ee0a02 style=letter-spacing:0px;><b>���űݾ� ".number_format($basketItems['groupMemberSale']['addMoney'])."</b>��</font>�� �߰��� ���� �˴ϴ�.";
	} else if($basketItems['groupMemberSale']['groupCode']=="SP") {
		$groupMemberSale .= "<font color=#ee0a02 style=letter-spacing:0px;><b>���űݾ��� ".number_format($basketItems['groupMemberSale']['addMoney'])."</b>%</font>�� �߰��� ���� �˴ϴ�.";
	}
	$groupMemberSale .= "<span id=\"couponEventMsg\"></span>";
}




#### PG ����Ÿ ���� ####
$_ShopInfo->getPgdata();
########################


//////  ���� ���� ���� start  ////////////////////////////////////////////////

// ���� ���ݰ��� ���� ���� üũ
$bankonlyCHK = "N";
foreach ( $basketItems['vender'] as $venderKey => $venderValue ) {
	foreach ( $venderValue['products'] as $productsKey=> $productsValue ){
		if( $productsValue['bankonly'] ) {
			$bankonlyCHK = "Y";
		}
	}
}

$escrow_info = GetEscrowType($_data->escrow_info);

$payType = "";

//������
if( preg_match("/^(Y|N)$/", $_data->payment_type) && $escrow_info["onlycard"]!="Y" ) {
	$payType .= "<input type='radio' onclick=\"change_paymethod(1);\" name='sel_paymethod' value='B' id=\"sel_paymethod1\"><label for=\"sel_paymethod1\" style=\"cursor:pointer;\">������ �Ա�</label>&nbsp;&nbsp;";
}

//2:�ſ�ī��: ���ݰ����� ��Ȱ��
if(preg_match("/^(Y|C)$/", $_data->payment_type) && strlen($_data->card_id)>0 AND $bankonlyCHK == "N" ) {
	$payType .= "<input type='radio' onclick=\"change_paymethod(2);\" name='sel_paymethod' value='C' id=\"sel_paymethod2\"><label for=\"sel_paymethod2\" style=\"cursor:pointer;\">�ſ�ī��</label>&nbsp;&nbsp;";
}

//2:�ǽð�������ü
if($escrow_info["onlycard"]!="Y" ) {
	if(strlen($_data->trans_id)>0) {
		$payType .= "<input type='radio' onclick=\"change_paymethod(3);\" name='sel_paymethod' value='V' id=\"sel_paymethod3\"><label for=\"sel_paymethod3\" style=\"cursor:pointer;\">�ǽð�������ü</label>&nbsp;&nbsp;";
	}
}

//3:�������
if($escrow_info["onlycard"]!="Y" ) {
	if(strlen($_data->virtual_id)>0) {
		$payType .= "<input type='radio' onclick=\"change_paymethod(4);\" name='sel_paymethod' value='O' id=\"sel_paymethod4\"><label for=\"sel_paymethod4\" style=\"cursor:pointer;\">�������</label>&nbsp;&nbsp;";
	}
}

//4:����ũ��
if(($escrow_info["escrowcash"]=="A" || $escrow_info["escrowcash"]=="Y") && strlen($_data->escrow_id)>0) {
	$pgid_info="";
	$pg_type="";
	$pgid_info=GetEscrowType($_data->escrow_id);
	$pg_type=trim($pgid_info["PG"]);

	if(preg_match("/^(A|B|C|D|E)$/",$pg_type)) {
		//KCP/������/�ô�����Ʈ/�̴Ͻý�/���̽����� ������� ����ũ�� �ڵ�
		$payType .= "<input type='radio' onclick=\"change_paymethod(5);\" name='sel_paymethod' value='Q' id=\"sel_paymethod5\"><label for=\"sel_paymethod5\" style=\"cursor:pointer;\">".($pg_type=="E"?"����ũ�ΰ���(�ſ�ī��,�ǽð���ü,�������)":"������ݿ�ġ��(����ũ��)")."</label>&nbsp;&nbsp;";
	}
}

//5:�ڵ��� : ���ݰ����� ��Ȱ��
if(strlen($_data->mobile_id)>0 AND $bankonlyCHK == "N" ) {
	$payType .= "<input type='radio' onclick=\"change_paymethod(6);\" name='sel_paymethod' value='M' id=\"sel_paymethod6\"><label for=\"sel_paymethod6\" style=\"cursor:pointer;\">�ڵ��� ����</label>";
}

//���ݰ��� ���� ��ǰ ���Խ� �޼���
if( $bankonlyCHK == "Y" ) {
	$payType .= "&nbsp;&nbsp;&nbsp;<font color='#FF0000'>(*�ֹ� ��ǰ�� [���ݰ���] ��ǰ�� ���ԵǾ� �ſ�ī������� �Ұ����մϴ�.)</font>";
}

//////  ���� ���� ���� end  ////////////////////////////////////////////////








// �������� ���� ��ũ
$offlineCouponInputButton = "<img src='/images/common/order/T01/offlineCouponInputButton.gif' align='absmiddle' style='cursor:pointer;' alt='�������� ���� ���' onclick=\" coupon_check( 'offlinecoupon' );\">";








// shopinfo ����ǰ Ȱ��ȭ ���� ȣ��
$giftInfoRow = @mysql_fetch_object( mysql_query("SELECT `gift_type` FROM `tblshopinfo` LIMIT 1;",get_db_conn()) );
$giftInfoSetArray = explode("|",$giftInfoRow->gift_type);








#��������ľ�
$errmsg="";
$sql = "SELECT a.quantity as sumquantity,b.productcode,b.productname,b.display,b.quantity, ";
$sql.= "b.option_quantity,b.etctype,b.group_check,b.assembleuse,a.assemble_list AS basketassemble_list ";
$sql.= ", c.assemble_list,a.package_idx ";
$sql.= "FROM tblbasket a, tblproduct b ";
$sql.= "LEFT OUTER JOIN tblassembleproduct c ON b.productcode=c.productcode ";
$sql.= "WHERE a.".$basketWhere." ";
$sql.= "AND a.productcode=b.productcode ";

$result=mysql_query($sql,get_db_conn());
$assemble_proquantity_cnt=0;
while($row=mysql_fetch_object($result)) {
	if($row->display!="Y") {
		$errmsg="[".ereg_replace("'","",$row->productname)."]��ǰ�� �ǸŰ� ���� �ʴ� ��ǰ�Դϴ�.\\n";
	}

	// today sale �Ǹ� �ð� ���� check
	if(preg_match('/^899[0-9]{15}$/',$row->productcode)){
		$tsql = "select unix_timestamp(t.end) -unix_timestamp() as remain, t.salecnt+t.addquantity as sellcnt from tblproduct a inner join todaysale t using(pridx) WHERE a.productcode='".$row->productcode."' limit 1";
		if(false === $tres = mysql_query($tsql,get_db_conn())){
			$errmsg="[".ereg_replace("'","",$row->productname)."]�� ������ DB ���� Ȯ�� �ϴ��� ������ �߻��߽��ϴ�..\\n";
		}else{
			if(mysql_num_rows($tres) < 1){
				$errmsg="[".ereg_replace("'","",$row->productname)."]�� ������ ã���� �����ϴ�.\\n";
			}else{
				$trow = mysql_fetch_assoc($tres);
				if($trow['remain'] < 1){
					$errmsg="[".ereg_replace("'","",$row->productname)."]�� �Ǹ� ������ ��ǰ �Դϴ�.\\n";
					mysql_query("delete from tblbasket where a.".$basketWhere." and productcode='".$row->productcode."'",get_db_conn()); // ���� ó��
				}
			}
		}
	}

	if($row->group_check!="N") {
		if(strlen($_ShopInfo->getMemid())>0) {
			$sqlgc = "SELECT COUNT(productcode) AS groupcheck_count FROM tblproductgroupcode ";
			$sqlgc.= "WHERE productcode='".$row->productcode."' ";
			$sqlgc.= "AND group_code='".$_ShopInfo->getMemgroup()."' ";
			$resultgc=mysql_query($sqlgc,get_db_conn());
			if($rowgc=@mysql_fetch_object($resultgc)) {
				if($rowgc->groupcheck_count<1) {
					$errmsg="[".ereg_replace("'","",$row->productname)."]��ǰ�� ���� ��� ���� ��ǰ�Դϴ�.\\n";
				}
				@mysql_free_result($resultgc);
			} else {
				$errmsg="[".ereg_replace("'","",$row->productname)."]��ǰ�� ���� ��� ���� ��ǰ�Դϴ�.\\n";
			}
		} else {
			$errmsg="[".ereg_replace("'","",$row->productname)."]��ǰ�� ȸ�� ���� ��ǰ�Դϴ�.\\n";
		}
	}


	if(strlen($errmsg)==0) {
		$miniq=1;
		$maxq="?";
		if(strlen($row->etctype)>0) {
			$etctemp = explode("",$row->etctype);
			for($i=0;$i<count($etctemp);$i++) {
				if(substr($etctemp[$i],0,6)=="MINIQ=")     $miniq=substr($etctemp[$i],6);
				if(substr($etctemp[$i],0,5)=="MAXQ=")      $maxq=substr($etctemp[$i],5);
			}
		}

		if(strlen(dickerview($row->etctype,0,1))>0) {
			$errmsg="[".ereg_replace("'","",$row->productname)."]��ǰ�� �ǸŰ� ���� �ʽ��ϴ�. �ٸ� ��ǰ�� �ֹ��� �ּ���.\\n";
		}
	}

	if(strlen($errmsg)==0) {
		if ($miniq!=1 && $miniq>1 && $row->sumquantity<$miniq)
			$errmsg.="[".ereg_replace("'","",$row->productname)."]��ǰ�� �ּ� ".$miniq."�� �̻� �ֹ��ϼž� �մϴ�.\\n";

		if ($maxq!="?" && $maxq>0 && $row->sumquantity>$maxq)
			$errmsg.="[".ereg_replace("'","",$row->productname)."]��ǰ�� �ִ� ".$maxq."�� ���Ϸ� �ֹ��ϼž� �մϴ�.\\n";

		if(strlen($row->quantity)>0) {
			if ($row->sumquantity>$row->quantity) {
				if ($row->quantity>0)
					$errmsg.="[".ereg_replace("'","",$row->productname)."]��ǰ�� ��� ".($_data->ETCTYPE["STOCK"]=="N"?"�����մϴ�.":"���� ".$row->quantity." �� �Դϴ�.")."\\n";
				else
					$errmsg.= "[".ereg_replace("'","",$row->productname)."]��ǰ�� ��� �ٸ��� �ֹ����� ������ ��ٱ��� �������� �۽��ϴ�.\\n";
			}
		}
		if($assemble_proquantity_cnt==0) { //�Ϲ� �� ������ǰ���� ��� ��������
			///////////////////////////////// �ڵ�/���� ������� ���� ��� üũ ///////////////////////////////////////////////
			$basketsql = "SELECT productcode,assemble_list,quantity,assemble_idx FROM tblbasket WHERE ".$basketWhere." ";
			$basketresult = mysql_query($basketsql,get_db_conn());
			while($basketrow=@mysql_fetch_object($basketresult)) {
				if($basketrow->assemble_idx>0) {
					if(strlen($basketrow->assemble_list)>0) {
						$assembleprolistexp = explode("",$basketrow->assemble_list);
						for($i=0; $i<count($assembleprolistexp); $i++) {
							if(strlen($assembleprolistexp[$i])>0) {
								$assemble_proquantity[$assembleprolistexp[$i]]+=$basketrow->quantity;
							}
						}
					}
				} else {
					$assemble_proquantity[$basketrow->productcode]+=$basketrow->quantity;
				}
			}
			@mysql_free_result($basketresult);
			$assemble_proquantity_cnt++;
		}
		if(count($assemble_list_exp)>0) { // ������ǰ�� ��� üũ
			$assemprosql = "SELECT productcode,quantity,productname FROM tblproduct ";
			$assemprosql.= "WHERE productcode IN ('".implode("','",$assemble_list_exp)."') ";
			$assemprosql.= "AND display = 'Y' ";
			$assemproresult=mysql_query($assemprosql,get_db_conn());
			while($assemprorow=@mysql_fetch_object($assemproresult)) {
				if(strlen($assemprorow->quantity)>0) {
					if($assemble_proquantity[$assemprorow->productcode]>$assemprorow->quantity) {
						if($assemprorow->quantity>0) {
							$errmsg.="[".ereg_replace("'","",$row->productname)."]��ǰ�� ������ǰ [".ereg_replace("'","",$assemprorow->productname)."] ��� ".($_data->ETCTYPE["STOCK"]=="N"?"�����մϴ�.":"���� ".$assemprorow->quantity." �� �Դϴ�.")."\\n";
						} else {
							$errmsg.="[".ereg_replace("'","",$row->productname)."]��ǰ�� ������ǰ [".ereg_replace("'","",$assemprorow->productname)."] �ٸ� ���� �ֹ����� ǰ���Ǿ����ϴ�.\\n";
						}
					}
				}
			}
			@mysql_free_result($assemproresult);
		} else if(strlen($package_productcode_tmp)>0) {
			$package_productcode_tmpexp = explode("",$package_productcode_tmp);
			$package_quantity_tmpexp = explode("",$package_quantity_tmp);
			$package_productname_tmpexp = explode("",$package_productname_tmp);
			for($i=0; $i<count($package_productcode_tmpexp); $i++) {
				if(strlen($package_productcode_tmpexp[$i])>0) {
					if(strlen($package_quantity_tmpexp[$i])>0) {
						if($assemble_proquantity[$package_productcode_tmpexp[$i]] > $package_quantity_tmpexp[$i]) {
							if($package_quantity_tmpexp[$i]>0) {
								$errmsg.="�ش� ��ǰ�� ��Ű�� [".ereg_replace("'","",$package_productname_tmpexp[$i])."] ��� ".($_data->ETCTYPE["STOCK"]=="N"?"�����մϴ�.":"���� ".$package_quantity_tmpexp[$i]." �� �Դϴ�.")."\\n";
							} else {
								$errmsg.="�ش� ��ǰ�� ��Ű�� [".ereg_replace("'","",$package_productname_tmpexp[$i])."] �ٸ� ���� �ֹ����� ǰ���Ǿ����ϴ�.\\n";
							}
						}
					}
				}
			}
		} else { // �Ϲݻ�ǰ�� ��� üũ
			if(strlen($row->quantity)>0) {
				if($assemble_proquantity[$assemprorow->productcode]>$row->quantity) {
					if ($row->quantity>0) {
						$errmsg.="[".ereg_replace("'","",$row->productname)."]��ǰ�� ��� ".($_data->ETCTYPE["STOCK"]=="N"?"�����մϴ�.":"���� ".$row->quantity." �� �Դϴ�.")."\\n";
					} else {
						$errmsg.= "[".ereg_replace("'","",$row->productname)."]��ǰ�� ��� �ٸ��� �ֹ����� ������ ��ٱ��� �������� �۽��ϴ�.\\n";
					}
				}
			}
		}
		if(strlen($row->option_quantity)>0) {
			$sql = "SELECT opt1_idx, opt2_idx, quantity FROM tblbasket ";
			$sql.= "WHERE ".$basketWhere." ";
			$sql.= "AND productcode='".$row->productcode."' ";
			$result2=mysql_query($sql,get_db_conn());
			while($row2=mysql_fetch_object($result2)) {
				$optioncnt = explode(",",substr($row->option_quantity,1));
				$optionvalue=$optioncnt[($row2->opt2_idx==0?0:($row2->opt2_idx-1))*10+($row2->opt1_idx-1)];

				if($optionvalue<=0 && $optionvalue!="") {
					$errmsg.="[".ereg_replace("'","",$row->productname)."]��ǰ�� �ɼ��� �ٸ� ���� �ֹ����� ǰ���Ǿ����ϴ�.\\n";
				} else if($optionvalue<$row2->quantity && $optionvalue!="") {
					$errmsg.="[".ereg_replace("'","",$row->productname)."]��ǰ�� ���õ� �ɼ��� ��� ".($_data->ETCTYPE["STOCK"]=="N"?"�����մϴ�.":"$optionvalue �� �Դϴ�.")."\\n";
				}
			}
			@mysql_free_result($result2);
		}
	}
}
@mysql_free_result($result);

if(strlen($errmsg)>0) {
	echo "<html></head><body onload=\"alert('".$errmsg."');location.href='".$Dir.FrontDir."basket.php';\"></body></html>";
	exit;
}







//���� ������ ���� ���
if($_REQUEST['mode']=="coupon" && strlen($_REQUEST['coupon_code'])==8){
	$onload = '';
	if(strlen($_ShopInfo->getMemid())==0) {	//��ȸ��
		echo "<html></head><body onload=\"alert('�α��� �� ���� �ٿ�ε尡 �����մϴ�.');location.href='".$Dir.FrontDir."login.php?chUrl=".getUrl()."';\"></body></html>";exit;
	}else{
		$sql = "SELECT * FROM tblcouponinfo where coupon_code = '".$_REQUEST['coupon_code']."'";


		$result=mysql_query($sql,get_db_conn());
		if($row=mysql_fetch_object($result)) {
			if($row->issue_tot_no>0 && $row->issue_tot_no<$row->issue_no+1) {
				$onload="<script>alert(\"��� ������ �߱޵Ǿ����ϴ�.\");</script>";
			} else {
				$date=date("YmdHis");
				if($row->date_start>0) {
					$date_start=$row->date_start;
					$date_end=$row->date_end;
				} else {
					$date_start = substr($date,0,10);
					$date_end = date("Ymd",mktime(0,0,0,substr($date,4,2),substr($date,6,2)+abs($row->date_start),substr($date,0,4)))."23";
				}
				$sql = "INSERT tblcouponissue SET ";
				$sql.= "coupon_code	= '".$_REQUEST['coupon_code']."', ";
				$sql.= "id			= '".$_ShopInfo->getMemid()."', ";
				$sql.= "date_start	= '".$date_start."', ";
				$sql.= "date_end	= '".$date_end."', ";
				$sql.= "date		= '".$date."' ";

				mysql_query($sql,get_db_conn());
				if(!mysql_errno()) {
					$sql = "UPDATE tblcouponinfo SET issue_no = issue_no+1 ";
					$sql.= "WHERE coupon_code = '".$_REQUEST['coupon_code']."'";
					mysql_query($sql,get_db_conn());

					$onload="<script>alert(\"�ش� ���� �߱��� �Ϸ�Ǿ����ϴ�.\\n\\n��ǰ �ֹ��� �ش� ������ ����Ͻ� �� �ֽ��ϴ�.\");</script>";
				} else {
					if($row->repeat_id=="Y") {	//������ ��߱��� �����ϴٸ�,,,,
						$sql = "UPDATE tblcouponissue SET ";
						if($row->date_start<=0) {
							$sql.= "date_start	= '".$date_start."', ";
							$sql.= "date_end	= '".$date_end."', ";
						}
						$sql.= "used		= 'N' ";
						$sql.= "WHERE coupon_code='".$_REQUEST['coupon_code']."' ";
						$sql.= "AND id='".$_ShopInfo->getMemid()."' ";
						mysql_query($sql,get_db_conn());
						$onload="<script>alert(\"�ش� ���� �߱��� �Ϸ�Ǿ����ϴ�.\\n\\n��ǰ �ֹ��� �ش� ������ ����Ͻ� �� �ֽ��ϴ�.\");</script>";
					} else {
						$onload="<script>alert(\"�̹� ������ �߱޹����̽��ϴ�.\\n\\n�ش� ������ ��߱��� �Ұ����մϴ�.\");</script>";
					}
				}
			}
		}
		mysql_free_result($result);

	}

	if(_empty($onload)){
		echo $onload;
	}
	?>
	<script language="javascript" type="text/javascript">
		document.location.replace('/front/order.php');
	</script>
	<?
	exit;

}


// ���� ���� ����Ʈ
$mycoupon_codes = getMyCouponList('',true);







$card_miniprice=$_data->card_miniprice;
$deli_area=$_data->deli_area;
$admin_message = $_data->order_msg;
$reserve_limit = $_data->reserve_limit;
$reserve_maxprice = $_data->reserve_maxprice;
if($reserve_limit==0) $reserve_limit=1000000000000;

if($_data->rcall_type=="Y") {
	$rcall_type = $_data->rcall_type;
	$bankreserve="Y";
} else if($_data->rcall_type=="N") {
	$rcall_type = $_data->rcall_type;
	$bankreserve="Y";
} else if($_data->rcall_type=="M") {
	$rcall_type="Y";
	$bankreserve="N";
} else {
	$rcall_type="N";
	$bankreserve="N";
}
$etcmessage=explode("=",$admin_message);



$user_reserve=0;
if(strlen($_ShopInfo->getMemid())>0) {
	$sql = "SELECT * FROM tblmember WHERE id='".$_ShopInfo->getMemid()."' ";
	$result = mysql_query($sql);
	if($row = mysql_fetch_object($result)) {
		$user_reserve = $row->reserve;
		if($user_reserve>$reserve_limit) {
			$okreserve=$reserve_limit;
			$remainreserve=$user_reserve-$reserve_limit;
		} else {
			$okreserve=$user_reserve;
			$remainreserve=0;
		}
		$home_addr="";
		/*
		if(strlen($row->home_post)==6) {
			$home_post1=substr($row->home_post,0,3);
			$home_post2=substr($row->home_post,3,3);
		}
		*/
		$home_post1=$row->home_post;

		$row->home_addr = ereg_replace("\"","",$row->home_addr);
		$home_addr = explode("=",$row->home_addr);
		$home_addr1 = $home_addr[0];
		$home_addr2 = $home_addr[1];

		$office_addr="";
		/*
		if(strlen($row->office_post)==6) {
			$office_post1=substr($row->office_post,0,3);
			$office_post2=substr($row->office_post,3,3);
		}
		*/
		$office_post1=$row->office_post;

		$row->office_addr = ereg_replace("\"","",$row->office_addr);
		$office_addr = explode("=",$row->office_addr);
		$office_addr1 = $office_addr[0];
		$office_addr2 = $office_addr[1];

		$name = $row->name;
		$email = $row->email;
		if (strlen($row->mobile)>0) $mobile = $row->mobile;
		else if (strlen($row->home_tel)>0) $home_tel = $row->home_tel;
		else if (strlen($row->office_tel)>0) $office_tel = $row->office_tel;
		$mobile=explode("-",replace_tel(check_num($mobile)));
		$home_tel=explode("-",replace_tel(check_num($row->home_tel)));

		$group_code=$row->group_code;
		@mysql_free_result($result);
		if(strlen($group_code)>0 && $group_code!=NULL) {
			$sql = "SELECT * FROM tblmembergroup WHERE group_code='".$group_code."' AND MID(group_code,1,1)!='M' ";
			$result=mysql_query($sql);
			if($row=mysql_fetch_object($result)){

				//�׷� �̹��� ���ó�� 20131025 J.Bum
				if(file_exists($Dir.DataDir."shopimages/etc/groupimg_".$row->group_code.".gif")) {
					$royal_img="<img src=\"".$Dir.DataDir."shopimages/etc/groupimg_".$row->group_code.".gif\" border=0>";
				} else {
					$royal_img="<img src=\"".$Dir."images/common/group_img.gif\" border=0>\n";
				}

				$group_code = $row->group_code;
				$org_group_name=$row->group_name;  //�׷������� ���� �߰�
				$group_name=$row->group_name;
				$group_type=substr($row->group_code,0,2); // �׷� Ÿ�� 					RW : �ݾ� �߰� ���� / RP  : % �߰� ���� / SW : �ݾ� �߰� ���� / SP  : % �߰� ����
				$group_usemoney=$row->group_usemoney; // �׷����� ���� �ݾ�
				$group_addmoney=$row->group_addmoney; // �׷����αݾ�
				$group_payment=$row->group_payment; // ���� ���					"B"=>"����","C"=>"ī��","N"=>"����/ī��"
					if($group_payment=="B") {
						$group_name.=" (���ݰ�����)";
					} else if($group_payment=="C") {
						$group_name.=" (ī�������)";
					}
			}
			@mysql_free_result($result);
		}
	} else {
		$_ShopInfo->setMemid("");
	}
}




// ��ȸ�� ����
if( strlen($_ShopInfo->getMemid()) == 0 ){
	$sql = "SELECT privercy FROM tbldesign ";
	$result=mysql_query($sql,get_db_conn());
	if($row=mysql_fetch_object($result)) {
		$privercy_exp = @explode("=", $row->privercy);
		$privercybody=$privercy_exp[1];
	}
	@mysql_free_result($result);

	if(strlen($privercybody)==0) {
		$buffer="";
		$fp=fopen($Dir.AdminDir."privercy2.txt","r");
		if($fp) {
			while (!feof($fp)) {
				$buffer.= fgets($fp, 1024);
			}
		}
		fclose($fp);
		$privercybody=$buffer;
	}

	$pattern=array("(\[SHOP\])","(\[NAME\])","(\[EMAIL\])","(\[TEL\])");
	$replace=array($_data->shopname,$_data->privercyname,"<a href=\"mailto:".$_data->privercyemail."\">".$_data->privercyemail."</a>",$_data->info_tel);
	$privercybody = preg_replace($pattern,$replace,$privercybody);
}


$sumprice = $basketItems['sumprice'];

?>
<HTML>
<HEAD>
<TITLE><?=$_data->shoptitle?> - �ֹ��� �ۼ�</TITLE>
<META http-equiv="CONTENT-TYPE" content="text/html; charset=EUC-KR">
<META http-equiv="X-UA-Compatible" content="IE=Edge" />

<META name="description" content="<?=(strlen($_data->shopdescription)>0?$_data->shopdescription:$_data->shoptitle)?>">
<META name="keywords" content="<?=$_data->shopkeyword?>">
<script type="text/javascript" src="<?=$Dir?>lib/lib.js.php"></script>
<script type="text/javascript" src="<?=$Dir?>js/jquery-1.8.3.min.js"></script>
<link type="text/css" rel="stylesheet" href="/css/newUI.css" />
<script>
	var $j = jQuery.noConflict();
</script>


<?include($Dir."lib/style.php")?>


<SCRIPT LANGUAGE="JavaScript">
<!--
var coupon_limit = "<?=$_data->coupon_limit_ok?>";
function change_message(gbn) {
	if(gbn==1) {
		document.all["msg_idx2"].style.display="none";
		document.all["msg_idx1"].style.display="";
		document.form1.msg_type.value=gbn;
	} else if(gbn==2) {
		document.all["msg_idx2"].style.display="";
		document.all["msg_idx1"].style.display="none";
		document.form1.msg_type.value=gbn;
	}
}



function changeDiscount(gbn){
	var defaultprice = parseInt($j("#sumprice").val()); //�� �����ݾ�
	//var coupon = parseInt($j("#coupon_price").val()); //���� ����� ��
	var coupon = 0;
	$j("#usereserve2").val($j("#reserve_price").val());
	var userMileage = parseInt($j("#usereserve").val()); // ����� ������
	var userMileage2 = parseInt($j("#usereserve2").val()); // ����������
	var deli_price = parseInt($j("#deliprice").val()); // ��ۺ�
	var setprice = 0; // ���� �ݾ�
	
	if(isNaN(userMileage)) userMileage = 0;
	if(isNaN(userMileage2)) userMileage2 = 0;
	if(isNaN(coupon)) coupon = 0;
	if(isNaN(defaultprice)) defaultprice = 0;
	if(isNaN(deli_price)) deli_price = 0;

	setprice = parseInt(defaultprice-userMileage-userMileage2-coupon);
	
	if(gbn=="dis"){//�������
		if(confirm("���� �� ���޵Ǵ� �������� �̸� ���� ���� �� �ֽ��ϴ�.\r\n������� ��ȯ�ÿ��� ȸ����޿� ���� �����ݸ� ���εǸ�, �߰��������� ���ܵ˴ϴ�.\r\n ������� �����ðڽ��ϱ�?")){
			$j("#dis_txt").hide();
			$j("#res_txt").show();
			
			$j("#rsvTxt").html("<font color=\"#ff0000\">"+0+"��</font>");
			// �Ѱ����ݾ�
			var total_price =  parseInt( setprice + deli_price );
			//$j("#disp_reserve").text(number_format(0-userMileage-userMileage2)); // ������ ����
			$j("#now_disp_last").text(number_format(userMileage2)); // �������
			$j("#disp_last_price").text(number_format(total_price));	// ���������ݾ�
			
			$j("#now_disp").show();
			$j("#disp_reserve_1").text(number_format(userMileage2)); 
			$j("#disp_last_price_1").text(number_format(total_price));
		}
	}else{
		$j("#dis_txt").show();
		$j("#res_txt").hide();
		$j("#usereserve2").val(0);
		$j("#rsvTxt").html("<font color=\"#ff0000\">"+number_format($j("#reserve_price").val())+"��</font>");
		var setprice = parseInt(defaultprice-coupon); // ���� �ݾ�
		// �Ѱ����ݾ�
		var total_price =  parseInt( setprice + deli_price - userMileage );
		//$j("#disp_reserve").text(0); // ������ ����
		$j("#now_disp_last").text(0); // �������
		$j("#disp_last_price").text(number_format(total_price));	// ���������ݾ�
		
		$j("#now_disp").hide();
		$j("#disp_reserve_1").text(0); 
		$j("#disp_last_price_1").text(number_format(total_price));
	}
}

//�ֹ��� ������ ������
function SameCheck(checked) {
	if(checked==true) {
		document.form1.receiver_name.value=document.form1.sender_name.value;
		document.form1.receiver_tel21.value=document.form1.sender_hp1.value;
		document.form1.receiver_tel22.value=document.form1.sender_hp2.value;
		document.form1.receiver_tel23.value=document.form1.sender_hp3.value;
		document.form1.receiver_tel11.value=document.form1.sender_tel1.value;
		document.form1.receiver_tel12.value=document.form1.sender_tel2.value;
		document.form1.receiver_tel13.value=document.form1.sender_tel3.value;
		document.form1.email.value			=document.form1.sender_email.value;

		document.form1.rpost1.value=document.form1.spost1.value;
		//document.form1.rpost2.value=document.form1.spost2.value;
		document.form1.raddr1.value=document.form1.saddr1.value;
		document.form1.raddr2.value=document.form1.saddr2.value;
	} else {
		document.form1.receiver_name.value="";
		document.form1.receiver_tel11.value="";
		document.form1.receiver_tel12.value="";
		document.form1.receiver_tel13.value="";
		document.form1.receiver_tel21.value="";
		document.form1.receiver_tel22.value="";
		document.form1.receiver_tel23.value="";
		document.form1.email.value='';

		document.form1.rpost1.value='';
		//document.form1.rpost2.value='';
		document.form1.raddr1.value='';
		document.form1.raddr2.value='';
	}
}


<?
	if(strlen($_ShopInfo->getMemid())>0){
?>
	// ȸ���� ��� �ּ� ����
	function addrchoice() {
		if(document.form1.addrtype[0].checked==true) { // ����
			document.form1.rpost1.value="<?=$home_post1?>";
			//document.form1.rpost2.value="<?=$home_post2?>";
			document.form1.raddr1.value="<?=$home_addr1?>";
			document.form1.raddr2.value="<?=$home_addr2?>";
		} else if(document.form1.addrtype[1].checked==true) { // ȸ��
			document.form1.rpost1.value="<?=$office_post1?>";
			//document.form1.rpost2.value="<?=$office_post2?>";
			document.form1.raddr1.value="<?=$office_addr1?>";
			document.form1.raddr2.value="<?=$office_addr2?>";
		} else if(document.form1.addrtype[2].checked==true) { // �ֱٹ����
			window.open("<?=$Dir.FrontDir?>addrbygone.php","addrbygone","width=100,height=100,toolbar=no,menubar=no,scrollbars=yes,status=no");
		}else if(document.form1.addrtype[4].checked==true){ // �ּҷ�
			window.open("<?=$Dir.FrontDir?>mydelivery.php","addrbygone","width=100,height=100,toolbar=no,menubar=no,scrollbars=yes,status=no");
		}
	}

	// ������ üũ
	function reserve_check(temp) {
		temp=parseInt(temp);
		if(isNaN(document.form1.usereserve.value)) {
			document.form1.usereserve.value=0;
			document.form1.okreserve.value=temp;
			document.form1.usereserve.focus();
			alert('���ڸ� �Է��ϼž� �մϴ�.');
			return;
		}
		if(parseInt(document.form1.usereserve.value)>temp) {
			document.form1.usereserve.value=0;
			document.form1.okreserve.value=temp;
			document.form1.usereserve.focus();
			alert('��밡�� ������ ���� ���ų� �Ȱ��� �Է��ϼž� �մϴ�.');
			return;
		}
		document.form1.okreserve.value=parseInt(temp-document.form1.usereserve.value);
		document.form1.usereserve.value=temp-document.form1.okreserve.value;
	}
<?
	}
?>

// �ּ� �˻�â
function get_post( t ) {
	window.open("<?=$Dir.FrontDir?>addr_search.php?form=form1&post="+t+"post&addr="+t+"addr1&gbn=2","f_post","resizable=yes,scrollbars=yes,x=100,y=200,width=370,height=250");
}


// �����ٿ�ε�
function issue_coupon(coupon_code,productcode){
	location.href="?mode=coupon&coupon_code="+coupon_code+"&productcode="+productcode;
}


// �������� ( offlinecoupon : ��������������� )
function coupon_check( offlinecoupon ){
	resetCoupon();

	var offlinecouponURL = "";
	if( offlinecoupon == "offlinecoupon" ) {
		offlinecouponURL = "?offlinecoupon=popup";
	}
	window.open("/front/couponpop.php"+offlinecouponURL,"couponpopup","width=720,height=750,toolbar=no,menubar=no,scrollbars=yes,status=no");
}


// �ֹ����
function ordercancel(gbn) {
	if(gbn=="cancel" && document.form1.process.value=="N") {
		document.location.href="basket.php";
	} else {
		if (PROCESS_IFRAME.chargepop) {
			if (gbn=="cancel") alert("����â�� �������Դϴ�. ����Ͻ÷��� ����â���� ����ϱ⸦ ��������.");
		} else {
			PROCESS_IFRAME.PaymentOpen();
		}
	}
}


function PaymentOpen() {
	PROCESS_IFRAME.PaymentOpen();
}

//-->
</SCRIPT>

<?

$mingiftprice = 0;
if(false !== $gres = mysql_query("select min(gift_startprice) from tblgiftinfo",get_db_conn())){
	if(mysql_num_rows($gres)) $mingiftprice = mysql_result($gres,0,0);
}

?>





<script>
	var deli_basefee	= parseInt(<?=$_data->deli_basefee?>); //���θ� ���� ��۷�
	var deli_miniprice	= parseInt(<?=$_data->deli_miniprice?>); //���θ� ���� ��۹��� �ּ� ��ǰ��
	var deli_price = parseInt(<?=$basketItems['deli_price']?>);
	var excp_group_discount = parseInt(<?=$basketItems['excp_group_discount']?>);
	var mingiftprice = parseInt(<?=$giftprice?>);

	var setprice;

	//������� ����
	var groupDiscMoney = parseInt("<?=$basketItems['groupMemberSale']['addMoney']?>"); // ����/���αݾ� �Ǵ� %
	var groupDiscUseMoney = parseInt("<?=$basketItems['groupMemberSale']['useMoney']?>"); // ���� �ݾ�
	var groupDiscPayTypeCode = "<?=$basketItems['groupMemberSale']['payTypeCode']?>"; // ���� ���� ���
	var groupCode = "<?=$basketItems['groupMemberSale']['groupCode']?>"; // �׷��ڵ�



	if(isNaN(mingiftprice) || mingiftprice <1) mingiftprice = 0;

	$j(document).ready(function() {

		// ������
		$j("#usereserve").keyup(function(){
			var possibleMileage = parseInt($j("#okreserve").val());//�ش� �ֹ����� ��밡���� ������
			var defaultprice	= parseInt($j("#sumprice").val());	//�⺻ �� �����ݾ�

			repstr = $j(this).val().replace(/[^0-9]/g,'');
			userMileage = parseInt(repstr);
			if(isNaN(userMileage)) userMileage = 0;
			$j(this).val(userMileage.toString());

			if(userMileage > possibleMileage){
				alert("�ش� �ֹ��� ������ ���� ������ �ݾ��� "+possibleMileage + "�� �Դϴ�.");
				$j("#usereserve").val(possibleMileage.toString());
			}/*else if(userMileage > setprice){
				alert("�ش� �ֹ��� ������ ���� ������ �ݾ��� "+setprice + "�� �Դϴ�");
				$j("#usereserve").val(setprice.toString());
			}*/else{

			}
			//resetCoupon();

			solvPrice();
		});

		$j("input[name=saddr2],input[name=raddr2]").focus(function(){
			if($j(this).val() == '������ �ּ�') $j(this).val('');
		});

		$j("input[name=saddr2],input[name=raddr2]").blur(function(){
			if($j.trim($j(this).val()).length < 1) $j(this).val('������ �ּ�');
		});

		solvPrice();
	});
	

	function allReserve(){
		$j("#usereserve").val($j("#oriuser_reserve").val());

		var possibleMileage = parseInt($j("#okreserve").val());//�ش� �ֹ����� ��밡���� ������
		var defaultprice	= parseInt($j("#sumprice").val());	//�⺻ �� �����ݾ�
		var disp_last_price	= parseInt($j("#disp_last_price").val());	//���������ݾ�

		repstr = $j("#usereserve").val().replace(/[^0-9]/g,'');
		userMileage = parseInt(repstr);
		if(isNaN(userMileage)) userMileage = 0;
		$j("#usereserve").val(userMileage.toString());
		
		if(possibleMileage>disp_last_price){
			possibleMileage = disp_last_price;
		}

		if(userMileage > possibleMileage){
			alert("�ش� �ֹ��� ������ ���� ������ �ݾ��� "+possibleMileage + "�� �Դϴ�.");
			$j("#usereserve").val(possibleMileage.toString());
		}/*else if(userMileage > setprice){
			alert("�ش� �ֹ��� ������ ���� ������ �ݾ��� "+setprice + "�� �Դϴ�");
			$j("#usereserve").val(setprice.toString());
		}*/else{

		}
		//resetCoupon();

		solvPrice();
	}

	function reserdeli(total_price){
		if(total_price > 0 && deli_miniprice > total_price) {
			alert("���� �����ݾ��� " + number_format(deli_miniprice) + " �� ������ ��� �⺻ ��۷� " +number_format(deli_basefee)+ "���� �߰��˴ϴ�");
			$j("#disp_last_price").text(number_format(total_price+deli_basefee));	// ���������ݾ� UI ǥ��
		}
	}

	function resetCoupon(){
		var coupon = parseInt($j("#coupon_price").val()); //���� ���ξ�
		if(!isNaN(coupon) && coupon > 0){
			alert('���� ��� ������ �ʱ�ȭ �˴ϴ�.');
		}
		$j('#couponlist').val('');
		$j('#dcpricelist').val('');
		$j('#couponproduct').val('');
		$j('#coupon_price').val('0');
		$j('#bank_only').val('N');
		$j("#possible_gift_price_used").val("Y");
		$j("#possible_group_dis_used").val("Y");

		solvPrice();
	}



	// �� ���� ******************************************************************************************
	function solvPrice(){

		var possibleMileage = parseInt($j("#okreserve").val());//�ش� �ֹ����� ��밡���� ������
		var userMileage = parseInt($j("#usereserve").val()); // ����� ������
		var userMileage2 = parseInt($j("#usereserve2").val()); // ������� ������
		var gift = parseInt($j("#possible_gift_price").val()); // ����ǰ ���ް��� ���űݾ�
		var coupon = parseInt($j("#coupon_price").val()); //���� ����� ��
		var defaultprice = parseInt($j("#sumprice").val()); //�� �����ݾ�
		var deli_price = parseInt($j("#deliprice").val()); // ��ۺ�

		if(isNaN(possibleMileage)) possibleMileage = 0;
		if(isNaN(userMileage)) userMileage = 0;
		if(isNaN(userMileage2)) userMileage2 = 0;
		if(isNaN(gift)) gift = 0;
		if(isNaN(coupon)) coupon = 0;
		if(isNaN(defaultprice)) defaultprice = 0;
		if(isNaN(deli_price)) deli_price = 0;
		var gdiscount = 0;

		setprice = parseInt(defaultprice-userMileage-userMileage2-coupon); // ���� �ݾ�

		// ������ ���
		if( setprice < 0 ) {
			userMileage = parseInt( userMileage - ( 0 - setprice ) );
			alert("�����ݻ���� "+userMileage+"���� ��밡���մϴ�.\n\n* ���� ��� �� ������å�� ���Ͽ� ���� �Ǵ� ���� ������ ���Ѱ��Դϴ�.");
			setprice = 0;
		}
		$j("#usereserve").val(userMileage);

		//��� ����
		var gdiscount = 0;
		var ispaymentcheck=false;
		if (document.form1.sel_paymethod.length) {					
		  for(i=0;i<document.form1.sel_paymethod.length;i++) {
			  if(document.form1.sel_paymethod[i].checked==true) {
				  document.form1.paymethod.value=document.form1.sel_paymethod[i].value;
				  ispaymentcheck=true;
				  break;
			  }
		  }
		}else if(document.form1.sel_paymethod.checked) {
			ispaymentcheck=true;
		}
		if( isNaN(groupCode) && ispaymentcheck==true && $j("#possible_group_dis_used").val() == "Y" && setprice >= groupDiscMoney && setprice >= groupDiscUseMoney ) {
			if ( groupCode == 'SW' ) {
				gdiscount=groupDiscMoney;
			} else {
				gdiscount= Math.floor((setprice*(groupDiscMoney/100))/100)*100;
			}
					
			// ���� ��Ŀ� ���� ó��
			// "B"=>"����","C"=>"ī��","N"=>"����/ī��"
			if( groupDiscPayTypeCode != "N" ) {
				var paymethodList = ( groupDiscPayTypeCode == "B" ) ? "B|V|O" : "C|M";
				var paymethod = $j("#paymethod").val();
				if( paymethodList.indexOf(paymethod) < 0 ) {
					gdiscount = 0;
				}
			}
		}

		// ������� ���� �ȵ� ���� ��� �޼���
		if ($j("#possible_group_dis_used").val() == "N") {
			$j("#couponEventMsg").html("<br><font color='blue'>����Ͻ� ���� �� ������� ������ ���� �� ���� ������ ���ԵǾ����ϴ�.</font>");
		} else {
			$j("#couponEventMsg").html("");
		}

		setprice -= gdiscount;
		gdiscount = 0-gdiscount;
		$j("#groupdiscount").val(gdiscount);



		// ����ǰ ����
		if(setprice < gift) gift = setprice; // ����ǰ ��밡�� �ݾ�
		giftchoices(gift);

		// �Ѱ����ݾ�
		var total_price =  parseInt( setprice + deli_price );

		// ���÷��� ( UI ǥ�� )
		$j("#disp_coupon").text(number_format(0-coupon)); // �������� ���ݾ�
		$j("#disp_reserve").text(number_format(userMileage)); // ������ ����
		$j("#disp_groupdiscount").text(number_format(gdiscount));	// �������

		$j("#disp_deliprice").text(number_format(deli_price));	// ��۱ݾ�

		$j("#disp_last_price").text(number_format(total_price));	// ���������ݾ�

		$j("#disp_reserve_1").text(number_format(userMileage2)); 
		$j("#disp_last_price_1").text(number_format(total_price));

	}
	// �� ���� �� **************************************************************************************


	// âũ��
	var trident = navigator.userAgent.match(/Trident\/(\d)/i);
	if(trident == null){
		var width = isNaN(window.innerWidth) ? window.clientWidth : window.innerWidth;
		var height = isNaN(window.innerHeight) ? window.clientHeight : window.innerHeight;
	} else {
		/*
		if( trident == "Trident/7,7" ) { // IE 11
			var width = 1200;
			var height = 1000;
		} else {
		*/
			var width = $j(document).width();
			var height = $j(window).height();
		//}
	}


	function windowSize() {
		$j("#PAY_PROCESS_IFRAME").css("width",width);
		$j("#PAY_PROCESS_IFRAME").css("height",height);
		document.getElementById("PAY_PROCESS_IFRAME").style.display = "block";
		document.getElementById("orderPaySel").disabled = "disabled";
		document.getElementById("PAY_PROCESS_IFRAME").focus();
	}


	$j(document).ready(function(){
		$j(window).bind("scroll", function(){
			$j("#PAY_PROCESS_IFRAME").css("top",$j(document).scrollTop());
		});
	});


	$j( window ).resize(function() {

		if(trident == null){
			var width2 = $j(window).width()+100;
			var height2 = isNaN(window.innerHeight) ? window.clientHeight : window.innerHeight;
		} else {
			//if( trident == "Trident/7,7" ) { // IE 11
			//	var width2 = 1200;
			//	var height2 = 1000;
			//} else {
				var width2 = $j(window).width()+100;
				var height2 = $j(window).height();
			//}
		}

		$j("#PAY_PROCESS_IFRAME").css("width",width2);
		$j("#PAY_PROCESS_IFRAME").css("height",height2);
	});
</script>


<?
// PG��� ȣ��
if( strlen($_data->card_id)>0 AND substr($ordertype,0,6) != "pester" ) {
	$pgInfo=GetEscrowType($_data->card_id);
	if( $pgInfo["PG"] == "A" ) {
		// KCP
		echo "<script language='javascript' src='https://pay.kcp.co.kr/plugin/payplus.js'></script>";
		echo "<script language='javascript'> StartSmartUpdate(); </script>";
	}
	if( $pgInfo["PG"] == "B" ) {
		// LG U+
	}
	if( $pgInfo["PG"] == "C" ) {
		// �ô�����Ʈ
		echo "<script language=javascript src=\"http://www.allthegate.com/plugin/AGSWallet.js\"></script>";
		echo "<script language='javascript'> StartSmartUpdate(); </script>";
	}
	if( $pgInfo["PG"] == "D" ) {
		// �̴Ͻý�
		echo "<script language='javascript' src='http://plugin.inicis.com/pay40.js'></script>";
		echo "<script language='javascript'> StartSmartUpdate(); </script>";
	}
	if( $pgInfo["PG"] == "E" ) {
		// ���̽�
		echo "<script src=\"https://web.nicepay.co.kr/flex/js/nicepay_tr.js\" language=\"javascript\"></script>";
		echo "<script language='javascript'> NicePayUpdate(); </script>";
		echo "<script src=\"https://www.vpay.co.kr/KVPplugin_ssl.js\" language=\"javascript\"></script>";
		echo "<script language='javascript'> StartSmartUpdate(); </script>";
	}
}
?>
</HEAD>

<body<?=(substr($_data->layoutdata["MOUSEKEY"],0,1)=="Y"?" oncontextmenu=\"return false;\"":"")?><?=(substr($_data->layoutdata["MOUSEKEY"],1,1)=="Y"?" ondragstart=\"return false;\" onselectstart=\"return false;\"":"")?> leftmargin="0" marginwidth="0" topmargin="0" marginheight="0"><?=(substr($_data->layoutdata["MOUSEKEY"],2,1)=="Y"?"<meta http-equiv=\"ImageToolbar\" content=\"No\">":"")?>


<!-- �ֹ� ó���� ���������� -->
<IFRAME id="PROCESS_IFRAME" name="PROCESS_IFRAME" style="display:none;" width="100%" height="700"></IFRAME>
<IFRAME id="PAY_PROCESS_IFRAME" name="PAY_PROCESS_IFRAME" style="display:none; POSITION: absolute; z-index:9999; border:5px solid #222222;" width="100%" frameborder="0"></IFRAME>


<?
	if(substr($_data->design_order,0,1)=="T") {
		$_data->menu_type="nomenu";
	}

	include ($Dir.MainDir.$_data->menu_type.".php");


	#������ ��ǰ�� �Ϲ� ��ǰ�� �ֹ��� ���
	if($basketItems['productcnt']!=$basketItems['productcnt'] && $basketItems['productcnt']>0 && $_data->card_splittype=="O") {
		echo "<script> alert('[�ȳ�] �����������ǰ�� �Ϲݻ�ǰ�� ���� �ֹ��� �������Һ������� �ȵ˴ϴ�.');</script>";
	}

	if($basketItems['sumprice']<$_data->bank_miniprice) {
		echo "<script>alert('�ֹ� ������ �ּ� �ݾ��� ".number_format($_data->bank_miniprice)."�� �Դϴ�.');location.href='".$Dir.FrontDir."basket.php';</script>";
		exit;
	} else if($basketItems['sumprice']<=0) {
		echo "<script>alert('��ǰ �� ������ 0���� ��� ��ǰ �ֹ��� ���� �ʽ��ϴ�.');location.href='".$Dir.FrontDir."basket.php';</script>";
		exit;
	}
?>


<form name=form1 action="<?=sprintf($Dir.FrontDir."%s.php", ((substr($ordertype,0,6)== "pester")? "pestersend":(($socialshopping == "social")? "ordersend3":"ordersend")) )?>" method=post>
<? if($recomorder === true && !_empty($recomcode)){ ?>
<input type="hidden" name="recomcode" value="<?=$recomcode?>" />
<? } ?>
<table border=0 cellpadding=0 cellspacing=0 style="width:1450px;margin:0px auto;">
	<tr>
		<?
			if ($leftmenu!="N") {
			if ($_data->title_type=="Y" && file_exists($Dir.DataDir."design/order_title.gif")) {
				echo "<td><img src=\"".$Dir.DataDir."design/order_title.gif\" border=\"0\" alt=\"�ֹ����ۼ�\"></td>\n";
			} else {
				echo "<td>\n";
				/*
				echo "<TABLE WIDTH=100% BORDER=0 CELLPADDING=0 CELLSPACING=0>\n";
				echo "<TR>\n";
				echo "	<TD><IMG SRC=".$Dir."images/".$_data->icon_type."/order_title_head.gif></TD>\n";
				echo "	<TD width=100% valign=top background=".$Dir."images/".$_data->icon_type."/order_title_bg.gif></TD>\n";
				echo "	<TD width=40><IMG SRC=".$Dir."images/".$_data->icon_type."/order_title_tail.gif ALT=></TD>\n";
				echo "</TR>\n";
				echo "</TABLE>\n";
				*/
				echo "</td>\n";
			}
			}
		?>
	</tr>
	<tr>
		<td align=center>
		<?
			//echo ($Dir.TempletDir."order/order".$_data->design_order.".php");
			include ($Dir.TempletDir."order/order".$_data->design_order.".php");
		 ?>
		</td>
	</tr>
	<tr><td height="20"></td></tr>
	<tr>
		<td align=center>
			<div id="paybuttonlayer" name="paybuttonlayer" style="text-align:center;height:43px;display:block;">
				<? if(substr($ordertype,0,6) == "pester"){?>
					<A HREF="javascript:CheckForm()" onMouseOver="window.status='������';return true;"><img src="<?=$Dir?>images/common/order/<?=$_data->design_order?>/btn_pester.gif" border="0" align="absmiddle" alt="������" /></A>
				<?}else{?>
					<A HREF="javascript:CheckForm()" onMouseOver="window.status='����';return true;"><img src="<?=$Dir?>images/common/order/<?=$_data->design_order?>/btn_payment.gif" border="0" align="absmiddle" alt="����" /></A>
				<?}?>
				<A HREF="javascript:ordercancel('cancel')" onMouseOver="window.status='���';return true;"><img src="<?=$Dir?>images/common/order/<?=$_data->design_order?>/btn_cancel.gif" border="0" align="absmiddle" /></A>
			</div>
			<div id="payinglayer" name="payinglayer" style="display:none;">
				<table border=0 cellpadding=0 cellspacing=0 width=100%>
					<tr>
						<td align=center><img src="<?=$Dir?>images/common/paying_wait.gif" border=0></td>
					</tr>
				</table>
			</div>
		</td>
	</tr>
	<tr><td height="20"></td></tr>
</table>

<?
	if( strlen($_ShopInfo->getMemid())>0 && $ordertype !='present' && strlen($pstr) == 0 ) echo "<script>document.form1.addrtype[0].checked=true;addrchoice();</script>";
?>
<!-- �Ѱ����ݾ� ���� -->
<input type="hidden" name="sumprice" id="sumprice" value="<?=$basketItems['sumprice']?>" />

<!-- �������� �� ( ���б�ȣ : | )) -->
<!-- �����������Ʈ -->
<input type="hidden" name="couponlist" id="couponlist" value="" />
<!-- ������� ���ξ� ����Ʈ -->
<input type="hidden" name="dcpricelist" id="dcpricelist" value="" />
<!-- ������� ������ ����Ʈ -->
<input type="hidden" name="drpricelist" id="drpricelist" value="" /><!-- -->
<!-- ���������ǰ����Ʈ --><!--  (�����ڵ�_��ǰ�ڵ�_�ɼ�1idx_�ɼ�2idx) -->
<input type="hidden" name="couponproduct" id="couponproduct" value="" />
<!-- ���� ���� ������ ������ ���õ� ��� --><!-- if (���� ���� ������ ������ ���õ� ��� ) Y else N -->
<input type="hidden" name="bank_only" id="couponBankOnly" value="N">
<!-- ��ۺ� -->
<input type='hidden' name='deliprice' id='deliprice' value='<?=$basketItems['deli_price']?>'>
<!-- ���������Ѿ� -->
<input type="hidden" name="coupon_reserve" id="coupon_reserve" value="0" />
<!-- ������� -->
<input type="hidden" name="paymethod" id="paymethod" value="0" />
<!-- ������ ���� �Ұ� ��ǰ ������ ���밡���� ������ �ݾ� , ��� �������� okreserve ���� �۾ƾ� �� -->
<input type="hidden" name="okreserve" id="okreserve" value="<?=$okreserve?>" />

<!-- ���� Ÿ��(?) �����ϱ� �ϰ�� (?) -->
<input type=hidden name=ordertype value="<?=$ordertype?>">

<!-- �鼼? �̰� ��� Ȱ��?? -->
<input type="hidden" name="tax_free" value="<?=$basketItems['tax_free']?>" />

<!-- ����ǰ ��밡�� �ݾ� -->
<input type="hidden" name="possible_gift_price" id="possible_gift_price" value="<?=$basketItems['gift_price']?>" />
<!-- ����ǰ ��밡�� ���� (Y/N) -->
<input type="hidden" name="possible_gift_price_used" id="possible_gift_price_used" value="Y" />
<!-- ȸ�� ��� ���� ���� ���� (Y/N) -->
<input type="hidden" name="possible_group_dis_used" id="possible_group_dis_used" value="Y" />


<!-- �ֹ��޼��� Ÿ�� -->
<input type="hidden" name="msg_type" value="1" />
<!-- ������ �߰� ��۷�..???? -->
<input type="hidden" name="addorder_msg" value="">

<!-- ���� ���� ���� -->
<input type="hidden" name="basketTempList" id="basketTempList" value="">

<!-- ȸ���׷�(�߰�)���� -->
<input type="hidden" name="groupdiscount" id="groupdiscount" value="0">



<!-- ��? ������ �� �����ؼ� �� ������??? �͵� ��ũ��Ʈ��~? -->
<input type=hidden name=process value="N">
<input type=hidden name=pay_data1>
<input type=hidden name=pay_data2>
<input type=hidden name=sender_resno>
<input type=hidden name=sender_tel>
<input type=hidden name=receiver_tel1>
<input type=hidden name=receiver_tel2>
<input type=hidden name=receiver_addr>
<input type=hidden name=order_msg>
<input type=hidden name=pester_tel>



<?
	if($_data->ssl_type=="Y" && strlen($_data->ssl_domain)>0 && strlen($_data->ssl_port)>0 && $_data->ssl_pagelist["ORDER"]=="Y") {
?>
	<input type=hidden name=shopurl value="<?=getenv("HTTP_HOST")?>">
<?
	}
?>
</form>







<SCRIPT LANGUAGE="JavaScript">
<!--
	// �ֹ��ϱ�
	function CheckForm() {

		paymethod=document.form1.paymethod.value.substring(0,1);
		<? if(strlen($_ShopInfo->getMemid())==0) { ?>
		if(document.form1.dongi[0].checked!=true) {
			alert("����������ȣ��å�� �����ϼž� ��ȸ�� �ֹ��� �����մϴ�.");
			document.form1.dongi[0].focus();
			return;
		}
		/*
		if(document.form1.sender_name.type=="text") {
			if(document.form1.sender_name.value.length==0) {
				alert("�ֹ��� ������ �Է��ϼ���.");
				document.form1.sender_name.focus();
				return;
			}
			if(!chkNoChar(document.form1.sender_name.value)) {
				alert("�ֹ��� ���Կ� \\(��������) ,  '(��������ǥ) , \"(ū����ǥ)�� �Է��Ͻ� �� �����ϴ�.");
				document.form1.sender_name.focus();
				return;
			}
		}
		*/
		<? } ?>
/*
		if(document.form1.sender_hp1.value.length==0) {
			alert("�ֹ��� �޴�����ȣ�� �Է��ϼ���.");
			document.form1.sender_hp1.focus();
			return;
		}
		if(document.form1.sender_hp2.value.length==0) {
			alert("�ֹ��� �޴�����ȣ�� �Է��ϼ���.");
			document.form1.sender_hp2.focus();
			return;
		}
		if(document.form1.sender_hp3.value.length==0) {
			alert("�ֹ��� �޴�����ȣ�� �Է��ϼ���.");
			document.form1.sender_hp3.focus();
			return;
		}
		if(!IsNumeric(document.form1.sender_hp1.value)) {
			alert("�ֹ��� �޴�����ȣ �Է��� ���ڸ� �Է��ϼ���.");
			document.form1.sender_hp1.focus();
			return;
		}
		if(!IsNumeric(document.form1.sender_hp2.value)) {
			alert("�ֹ��� �޴�����ȣ �Է��� ���ڸ� �Է��ϼ���.");
			document.form2.sender_hp2.focus();
			return;
		}
		if(!IsNumeric(document.form1.sender_hp3.value)) {
			alert("�ֹ��� �޴�����ȣ �Է��� ���ڸ� �Է��ϼ���.");
			document.form3.sender_hp3.focus();
			return;
		}
		document.form1.sender_tel.value=document.form1.sender_hp1.value+"-"+document.form1.sender_hp2.value+"-"+document.form1.sender_hp3.value;

		if(document.form1.sender_email.value.length>0) {
			if(!IsMailCheck(document.form1.sender_email.value)) {
				alert("�ֹ��� �̸��� ������ �߸��Ǿ����ϴ�.");
				document.form1.sender_email.focus();
				return;
			}
		}
*/
		if(document.form1.receiver_name.value.length==0) {
			alert("�޴º� ������ �Է��ϼ���.");
			document.form1.receiver_name.focus();
			return;
		}
		if(!chkNoChar(document.form1.receiver_name.value)) {
			alert("�޴º� ���Կ� \\(��������) ,  '(��������ǥ) , \"(ū����ǥ)�� �Է��Ͻ� �� �����ϴ�.");
			document.form1.receiver_name.focus();
			return;
		}
		/*
		<?//if($ordertype  == "present"){?>
			if(document.form1.receiver_email.value.length==0) {
				alert("�޴º� �̸����� �Է��ϼ���.");
				document.form1.receiver_email.focus();
				return;
			}
			if(document.form1.receiver_email.value.length > 0) {
				if(!IsMailCheck(document.form1.receiver_email.value)) {
					alert("�޴º� �̸��� ������ �߸��Ǿ����ϴ�.");
					document.form1.receiver_email.focus();
					return;
				}
			}
		<?//}?>
		*/
		if(document.form1.receiver_tel11.value.length==0) {
			alert("�޴º� ��ȭ��ȣ�� �Է��ϼ���.");
			document.form1.receiver_tel11.focus();
			return;
		}
		if(document.form1.receiver_tel12.value.length==0) {
			alert("�޴º� ��ȭ��ȣ�� �Է��ϼ���.");
			document.form1.receiver_tel12.focus();
			return;
		}
		if(document.form1.receiver_tel13.value.length==0) {
			alert("�޴º� ��ȭ��ȣ�� �Է��ϼ���.");
			document.form1.receiver_tel13.focus();
			return;
		}
		if(!IsNumeric(document.form1.receiver_tel11.value)) {
			alert("�޴º� ��ȭ��ȣ �Է��� ���ڸ� �Է��ϼ���.");
			document.form1.receiver_tel11.focus();
			return;
		}
		if(!IsNumeric(document.form1.receiver_tel12.value)) {
			alert("�޴º� ��ȭ��ȣ �Է��� ���ڸ� �Է��ϼ���.");
			document.form1.receiver_tel12.focus();
			return;
		}
		if(!IsNumeric(document.form1.receiver_tel13.value)) {
			alert("�޴º� ��ȭ��ȣ �Է��� ���ڸ� �Է��ϼ���.");
			document.form1.receiver_tel13.focus();
			return;
		}
		document.form1.receiver_tel1.value=document.form1.receiver_tel11.value+"-"+document.form1.receiver_tel12.value+"-"+document.form1.receiver_tel13.value;
/*
		if(document.form1.receiver_tel21.value.length==0) {
			alert("�޴º� �ڵ�����ȣ�� �Է��ϼ���.");
			document.form1.receiver_tel21.focus();
			return;
		}
		if(document.form1.receiver_tel22.value.length==0) {
			alert("�޴º� �ڵ�����ȣ�� �Է��ϼ���.");
			document.form1.receiver_tel22.focus();
			return;
		}
		if(document.form1.receiver_tel23.value.length==0) {
			alert("�޴º� �ڵ�����ȣ�� �Է��ϼ���.");
			document.form1.receiver_tel23.focus();
			return;
		}
*/
		if(!IsNumeric(document.form1.receiver_tel21.value)) {
			alert("�޴º� �ڵ�����ȣ �Է��� ���ڸ� �Է��ϼ���.");
			document.form1.receiver_tel21.focus();
			return;
		}
		if(!IsNumeric(document.form1.receiver_tel22.value)) {
			alert("�޴º� �ڵ�����ȣ �Է��� ���ڸ� �Է��ϼ���.");
			document.form1.receiver_tel22.focus();
			return;
		}
		if(!IsNumeric(document.form1.receiver_tel23.value)) {
			alert("�޴º� �ڵ�����ȣ �Է��� ���ڸ� �Է��ϼ���.");
			document.form1.receiver_tel23.focus();
			return;
		}
		document.form1.receiver_tel2.value=document.form1.receiver_tel21.value+"-"+document.form1.receiver_tel22.value+"-"+document.form1.receiver_tel23.value;

		if(document.form1.rpost1.value.length==0) {
			alert("�����ȣ�� �����ϼ���.");
			get_post();
			return;
		}
		if(document.form1.raddr1.value.length==0) {
			alert("�ּҸ� �Է��ϼ���.");
			document.form1.raddr1.focus();
			return;
		}
		if(document.form1.raddr2.value.length==0) {
			alert("���ּҸ� �Է��ϼ���.");
			document.form1.raddr2.focus();
			return;
		}
		if(!chkNoChar(document.form1.raddr2.value)) {
			alert("���ּҿ� \\(��������) ,  '(��������ǥ) , \"(ū����ǥ)�� �Է��Ͻ� �� �����ϴ�.");
			document.form1.raddr2.focus();
			return;
		}

		<?if($ordertype == "p"){?>
			/*
			if(document.form1.in_email.value.length==0) {
				alert("�̸����� �Է��ϼ���.");
				document.form1.in_email.focus();
				return;
			}
			var email = document.form1.in_email.value;
			if(email.indexOf(",") >0){
				arEmail = email.split(",");
				for(i=0;i<arEmail.length;i++){
					if(!IsMailCheck(arEmail[i].trim())) {
						alert("�̸��� ������ �����ʽ��ϴ�.\n\nȮ���Ͻ� �� �ٽ� �Է��ϼ���.");
						document.form1.in_email.focus(); return;
					}
				}
			}else{
				if(!IsMailCheck(email.trim())) {
					alert("�̸��� ������ �����ʽ��ϴ�.\n\nȮ���Ͻ� �� �ٽ� �Է��ϼ���.");
					document.form1.in_email.focus(); return;
				}
			}
			*/
			if(document.form1.receiver_message.value.length==0) {
				alert("������ �Է��ϼ���.");
				document.form1.receiver_message.focus();
				return;
			}

		<?}?>




		<?
			if(substr($ordertype,0,6) == "pester"){
		?>

			document.form1.receiver_addr.value = document.form1.rpost1.value;

			// ������ üũ
			if(document.form1.pester_name.value.length==0) {
				alert("������ ����� ������ �Է��ϼ���.");
				document.form1.pester_name.focus();
				return;
			}
			if(!chkNoChar(document.form1.pester_name.value)) {
				alert("������ ����� ���Կ� \\(��������) ,  '(��������ǥ) , \"(ū����ǥ)�� �Է��Ͻ� �� �����ϴ�.");
				document.form1.pester_name.focus();
				return;
			}

			if(document.form1.pester_tel1.value.length==0) {
				alert("������ ����� ��ȭ��ȣ�� �Է��ϼ���.");
				document.form1.pester_tel1.focus();
				return;
			}
			if(document.form1.pester_tel2.value.length==0) {
				alert("������ ����� ��ȭ��ȣ�� �Է��ϼ���.");
				document.form1.pester_tel2.focus();
				return;
			}
			if(document.form1.pester_tel3.value.length==0) {
				alert("������ ����� ��ȭ��ȣ�� �Է��ϼ���.");
				document.form1.pester_tel3.focus();
				return;
			}
			if(!IsNumeric(document.form1.pester_tel1.value)) {
				alert("������ ����� ��ȭ��ȣ �Է��� ���ڸ� �Է��ϼ���.");
				document.form1.pester_tel1.focus();
				return;
			}
			if(!IsNumeric(document.form1.pester_tel2.value)) {
				alert("������ ����� ��ȭ��ȣ �Է��� ���ڸ� �Է��ϼ���.");
				document.form2.pester_tel2.focus();
				return;
			}
			if(!IsNumeric(document.form1.pester_tel3.value)) {
				alert("������ ����� ��ȭ��ȣ �Է��� ���ڸ� �Է��ϼ���.");
				document.form3.pester_tel3.focus();
				return;
			}
			document.form1.pester_tel.value=document.form1.pester_tel1.value+"-"+document.form1.pester_tel2.value+"-"+document.form1.pester_tel3.value;

			if(document.form1.pester_email.value.length==0) {
				alert("������ ����� �̸����� �Է��ϼ���.");
				document.form1.pester_email.focus();
				return;
			}
			if(document.form1.pester_email.value.length>0) {
				if(!IsMailCheck(document.form1.pester_email.value)) {
					alert("������ ����� �̸��� ������ �߸��Ǿ����ϴ�.");
					document.form1.pester_email.focus();
					return;
				}
			}

			if(document.form1.pester_smstxt.value.length==0) {
				alert("sms ���۸޼����� �Է��ϼ���.");
				document.form1.pester_smstxt.focus();
				return;
			}

			if(document.form1.pester_emailtxt.value.length==0) {
				alert("email ���۸޼����� �Է��ϼ���.");
				document.form1.pester_emailtxt.focus();
				return;
			}

			document.form1.submit();
		<?
			} else {
		?>



			//�ű� �������� üũ!!  ------ 20120430
			try {
				if (document.form1.sel_paymethod.length==null) {
					if(document.form1.sel_paymethod.checked==false) {
						alert("��������� �����ϼ���.");
						document.form1.paymethod.value="";
						return;
					}
					document.form1.paymethod.value=document.form1.sel_paymethod.value;
				} else {
					var ispaymentcheck=false;
					for(i=0;i<document.form1.sel_paymethod.length;i++) {
						if(document.form1.sel_paymethod[i].checked==true) {
							document.form1.paymethod.value=document.form1.sel_paymethod[i].value;
							ispaymentcheck=true;
							break;
						}
					}
					if(ispaymentcheck==false) {
						alert("��������� �����ϼ���.");
						document.form1.paymethod.value="";
						return;
					}
				}
			} catch(e) {
				return;
			}

			if(document.form1.paymethod.value=="B" && document.form1.sel_bankinfo.selectedIndex!=0) {
				document.form1.pay_data1.value=document.form1.sel_bankinfo.options[document.form1.sel_bankinfo.selectedIndex].value;
			} else if(document.form1.paymethod.value=="B" && document.form1.sel_bankinfo.selectedIndex<=0) {
				alert("�Աݰ��¸� �����ϼ���.");
				document.form1.paymethod.value="";
				document.form1.sel_bankinfo.focus();
				return;
			}

			/* ��������üũ ��~~~~~~~~~~~~~~~~~~~~~*/

			<? if(strlen($_ShopInfo->getMemid())>0) { ?>

				/*
				if(document.form1.usereserve.value == '') {
					alert("������ �Է¶��� ������ϴ�.");
					document.form1.usereserve.value = 0;
					document.form1.usereserve.focus();
					return;
				}
				*/

				<? if($_data->reserve_maxuse>=0 && strlen($okreserve)>0 && $okreserve>0) { ?>
				if(document.form1.usereserve.value > <?=$okreserve?>) {
					alert("������ ��밡�ɱݾ׺��� Ů�ϴ�.");
					document.form1.usereserve.focus();
					return;
				} else if(document.form1.usereserve.value < 0) {
					alert("�������� 0������ ũ�� ����ϼž� �մϴ�.");
					document.form1.usereserve.focus();
					return;
				}
				<? } ?>

				<? if($_data->reserve_maxuse>=0 && strlen($okreserve)>0 && $okreserve>0 && $_data->coupon_ok=="Y" && $rcall_type=="N") { ?>
				//if(document.form1.usereserve.value>0 && document.form1.coupon_code.value.length==8){
				if(document.form1.usereserve.value>0 && document.form1.couponlist.value.length>8){
					alert('�����ݰ� ������ ���ÿ� ����� �Ұ����մϴ�.\n���߿� �ϳ��� ����Ͻñ� �ٶ��ϴ�.');
					document.form1.usereserve.focus();
					return;
				}
				<? } ?>

				<? if($_data->reserve_maxuse>=0 && $bankreserve=="N") { ?>
				if (document.form1.usereserve.value>0) {
					if(paymethod!="B" && paymethod!="V" && paymethod!="O" && paymethod!="Q") {
						alert('�������� ���ݰ����ÿ��� ����� �����մϴ�.\n���ݰ����� ������ �ּ���');
						document.form1.paymethod.value="";
						return;
					}
				}
				<? } ?>
			<? } ?>


			prlistcnt="<?=$arr_prlist?>"+0;
			if(document.form1.msg_type.value=="1") {
				message_len = document.form1.order_prmsg.value.length;
				message_end = document.form1.order_prmsg.value.charCodeAt(message_len-1);
				if (message_len>0 && (message_end==39 || message_end==34 || message_end==92) ) {
					document.form1.order_prmsg.value += " ";
				}
			} else if(document.form1.msg_type.value=="2") {
				for(j=0;j<prlistcnt;j++) {
					message_len = document.form1["order_prmsg"+j].value.length;
					message_end = document.form1["order_prmsg"+j].value.charCodeAt(message_len-1);
					if (message_len>0 && (message_end==39 || message_end==34 || message_end==92) ) {
						document.form1["order_prmsg"+j].value += " ";
					}
				}
			}

			document.form1.receiver_addr.value = "�����ȣ : " + document.form1.rpost1.value + "\n�ּ� : " + document.form1.raddr1.value + "  " + document.form1.raddr2.value;

			<? if($_data->coupon_ok=="Y" && strlen($_ShopInfo->getMemid())>0) { ?>
				if (document.form1.bank_only.value=="Y") {
					if(paymethod!="B" && paymethod!="V" && paymethod!="O" && paymethod!="Q") {
						alert("�����Ͻ� ������ ���ݰ����� �����մϴ�.\n���ݰ����� ������ �ּ���");
						document.form1.paymethod.value="";
						return;
					}
				}
			<? } ?>
			document.form1.order_msg.value="";
			if(document.form1.process.value=="N") {
				<? if(strlen($etcmessage[1])>0) {?>
					if(document.form1.nowdelivery.checked==true) {
						document.form1.order_msg.value+="<font color=red>�������� : ������ �������</font>";
					} else {
						document.form1.order_msg.value+="<font color=red>�������� : "+document.form1.year.value+"�� "+document.form1.mon.value+"�� "+document.form1.day.value+"��";
						<? if(strlen($etcmessage[1])==6) { ?>
						document.form1.order_msg.value+=" "+document.form1.time.value;
						<? } ?>
						document.form1.order_msg.value+="</font>";
					}
				<? } ?>

				<? if($etcmessage[2]=="Y") { ?>
					if(document.form1.bankname.value.length>1 && (document.form1.paymethod.length==null && paymethod=="B")) {
						if(document.form1.order_msg.value.length>0) document.form1.order_msg.value+="\n";
						document.form1.order_msg.value+="�Ա��� : "+document.form1.bankname.value;
					}
				<? } ?>

					//������ �߰���۷� Ȯ��
					<?
					/*
						echo "address = \" \"+document.form1.raddr1.value;\n";
						$array_deli = explode("|",$_data->deli_area);
						$cnt= floor(count($array_deli)/2);
						for($i=0;$i<$cnt;$i++){
							$subdeli=explode(",",$array_deli[$i*2]);
							$subcnt=count($subdeli);
							echo "if(";
							for($j=0;$j<$subcnt;$j++){
								if($j!=0) echo " || ";
								echo "address.indexOf(\"".$subdeli[$j]."\")>0";
							}
							echo "){ if(!confirm('";
							if($array_deli[$i*2+1]>0) echo "�ش� ������ ��۷� ".number_format($array_deli[$i*2+1])."���� �߰��˴ϴ�.";
							else echo "�ش� ������ ��۷� ".number_format(abs($array_deli[$i*2+1]))."���� ���ε˴ϴ�.";
							echo "')) return;}\n";
						}
					*/
					?>
					if(document.form1.addorder_msg=="[object]") {
						if(document.form1.order_msg.value.length>0) document.form1.order_msg.value+="\n";
						document.form1.order_msg.value+=document.form1.addorder_msg.value;
					}

					document.form1.process.value="Y";
					document.form1.target = "PROCESS_IFRAME";

				<?if($_data->ssl_type=="Y" && strlen($_data->ssl_domain)>0 && strlen($_data->ssl_port)>0 && $_data->ssl_pagelist["ORDER"]=="Y") {?>
						document.form1.action='https://<?=$_data->ssl_domain?><?=($_data->ssl_port!="443"?":".$_data->ssl_port:"")?>/<?=RootPath.SecureDir?>order.php';
				<?}?>

					document.all.paybuttonlayer.style.display="none";
					document.all.payinglayer.style.display="block";

					document.form1.submit();

			} else {
				ordercancel();
			}

		<?
			}
		?>
	}

	secGifts = "";
	function secGift(vls) {
		f = document.form1;

		$("gift_"+secGifts).style.display = "none";
		tmp = eval("f.img_"+secGifts);
		$("gift_img").src = tmp.value;

		$("gift_"+vls).style.display = "block";
		tmp = eval("f.img_"+vls);
		$("gift_img").src = tmp.value;

		secGifts = vls;

	}

	// �������� ����
	function change_paymethod(val){
		if(val==1 || val==3 || val==4){
			alert("�뿩 ��û ��, 12�ð��� �Ա��� �ȵǸ� �ڵ� �ֹ� ��ҵ˴ϴ�.");
		}

		for(i=0;i<=6;i++){
			if(document.getElementById("simg"+i)){
				document.getElementById("simg"+i).style.display = "none";
			}
		}
		document.getElementById("simg"+val).style.display = "block";

		solvPrice();
	}
//-->
</SCRIPT>




<?=$onload?>


<? include ($Dir."lib/bottom.php") ?>







<Script>
	/******************************
		����ǰ
	******************************/
	$j(function(){
		$j('select[name=giftval_seq]').change( function(){ resetGiftOptions();});
		if($j('input[name=range_diff]').val()>0){
			$j('#rangeTxt').html('*�뿩�Ⱓ�� �ٸ� ��ǰ�� �ֽ��ϴ�. �����ϼ���.');
		}
	});

	// ���� ���� ����ǰ ���� ����
	function giftchoices(gprice){
		var tempgprice = parseInt($j('input[name=gift01]').val()); // ������ (��) ����ǰ ���ް��� ���űݾ�
		gprice = parseInt(gprice); // ����� ����ǰ ���ް��� ���űݾ�
		var noGift = ($j('input[name=possible_gift_price_used]').val() == 'N');
		if(!noGift){
			if(isNaN(gprice)) gprice = tempgprice;
			if(isNaN(gprice) || gprice < 1) gprice = 0;
		}else{
			gprice = 0;
		}

		// ����ǰ ���ް��� ���űݾ׿� ������ ���� ����ǰ�� ���� �Ǿ� ������� ����ǰ ���� ����
		var index = $j("select[name=giftval_seq] option").index( $j("select[name=giftval_seq] option:selected") );
		if( tempgprice == gprice && index > 0 ) {
			return false;
		}

		$j('input[name=gift01]').val(gprice);
		if(gprice >= mingiftprice){
			if($j('#giftSelectArea')) $j('#giftSelectArea').css('display','');
			$j.post( '/json_order.php',{'act':'getGife','gift_price':gprice},function(data){
				if(data.err == 'ok'){
					giftReset(data.items);
				}else{
					alert(data.err);
				}
			},'json');
		}else{

			if($j('#giftSelectArea')) $j('#giftSelectArea').css('display','none');
			$j('#noGiftOptionArea').css('display','');
			$j('#giftOptionBox').css('display','none');
			$j('input[name=gift_msg]').attr('disabled','disabled');
		}
	}

	// ����ǰ �ʱ�ȭ
	function giftReset(items){
		$j('select[name=giftval_seq]').find('option:gt(0)').remove();
		resetGiftOptions();

		if($j(items).size() < 1){

			if($j('#giftSelectArea')) $j('#giftSelectArea').css('display','none');

			$j('#noGiftOptionArea').css('display','');
			$j('#giftOptionBox').css('display','none');
			$j('input[name=gift_msg]').attr('disabled','disabled');
		}else{
			if($j('#giftSelectArea')) $j('#giftSelectArea').css('display','');
			$j('#noGiftOptionArea').css('display','none');
			$j('#giftOptionBox').css('display','');
			if($j('input[name=gift_msg]').attr('disabled'))  $j('input[name=gift_msg]').removeAttr('disabled');
			if($j.isArray(items)){
				$j(items).each(function(idx,itm){
					addGiftSelSelect(itm);
				});
			}else{
				$j(items).each(function(idx,itm){
					for(p in itm) addGiftSelSelect(itm[p]);
				});
			}
		}
	}

	// ����ǰ �ɼ� �ʱ�ȭ
	function resetGiftOptions(){
		var $gift = $j("select[name=giftval_seq] option:selected");
		var index =$j("select[name=giftval_seq] option").index($gift);
		$j('#giftOptionArea').html('');

		if($j.trim($j($gift).data('imgsrc')).length < 1){
			$j('#gift_img').attr('src',"/images/no_img.gif");
		}else{
			$j('#gift_img').attr('src','<?=$Dir?>data/shopimages/etc/'+$j($gift).data('imgsrc'));
		}

		if(index > 0){
			$items = $j($gift).data('options');
			//alert($j($items).size());
			if($j($items).size() >0){
				var str = '<table border="0" cellpadding="0" cellspacing="0" style="width:100%">';
				if($j.isArray($items)){
					$j($items).each(function(idx,itm){
						str += '<tr><td style="width:50px;">�ɼ� '+(idx+1)+' :</td>';
						str += '<td><select name="giftOpt'+(idx+1)+'" style="width:90%">';
						$name = itm.name;

						$j(itm.items).each(function(idx,sitm){
							str += '<option value="'+sitm[0]+'">'+$name+' : '+sitm[0]+'</option>';
						});
						str += '</select></td></tr>';
					});
				}else{
					$j($items).each(function(idx,oitm){
						for(p in oitm){
							itm = oitm[p];
							str += '<tr><td style="width:50px;">�ɼ� '+(p)+' :</td>';
							str += '<td><select name="giftOpt'+(p)+'" style="width:90%">';
							$name = itm.name;
							$j(itm.items).each(function(idx,sitm){
								/*
								for(q in sitm){
									alert(q);
									str += '<option value="'+sitm[q]+'">'+$name+' : '+sitm[q]+'</option>';
								}*/
								str += '<option value="'+sitm[0]+'">'+$name+' : '+sitm[0]+'</option>';
							});
							str += '</select></td></tr>';
						}
					});
				}
				str += '</table>';
				$j('#giftOptionArea').html(str);
				//alert(str);
			}
		}
	}

	// ����ǰ ����
	function addGiftSelSelect(itm){
		$j('<option value="'+itm.gift_regdate+'">'+itm.gift_name+'</option>').data('imgsrc',itm.gift_image).data('options',itm.options).appendTo("select[name=giftval_seq]");

	}

	function change(frm){
		var val = frm.order_message.selectedIndex;
		switch(val){
			case 0:
				frm.order_prmsg.value="";
				break;
			case 1:
				frm.order_prmsg.value="��� �� ���� �ٶ��ϴ�.";
				break;
			case 2:
				frm.order_prmsg.value="���� �� ���ǿ� �ð��ּ���.";
				break;
			case 3:
				frm.order_prmsg.value="���� �� ��ȭ �Ǵ� ���� �����ּ���.";
				break;
			case 4:
				frm.order_prmsg.value="";
				frm.order_prmsg.focus();
				break;
		}
	}
</script>


<script src="http://dmaps.daum.net/map_js_init/postcode.v2.js"></script>
<script type="text/javascript">
	<!--
	function addr_search_for_daumapi(post,addr1,addr2) {
		new daum.Postcode({
			oncomplete: function(data) {
				// �˾����� �˻���� �׸��� Ŭ�������� ������ �ڵ带 �ۼ��ϴ� �κ�.

				// �� �ּ��� ���� ��Ģ�� ���� �ּҸ� �����Ѵ�.
				// �������� ������ ���� ���� ��쿣 ����('')���� �����Ƿ�, �̸� �����Ͽ� �б� �Ѵ�.
				var fullAddr = ''; // ���� �ּ� ����
				var extraAddr = ''; // ������ �ּ� ����

				// ����ڰ� ������ �ּ� Ÿ�Կ� ���� �ش� �ּ� ���� �����´�.
				if (data.userSelectedType === 'R') { // ����ڰ� ���θ� �ּҸ� �������� ���
					fullAddr = data.roadAddress;

				} else { // ����ڰ� ���� �ּҸ� �������� ���(J)
					fullAddr = data.jibunAddress;
				}

				// ����ڰ� ������ �ּҰ� ���θ� Ÿ���϶� �����Ѵ�.
				if(data.userSelectedType === 'R'){
					//���������� ���� ��� �߰��Ѵ�.
					if(data.bname !== ''){
						extraAddr += data.bname;
					}
					// �ǹ����� ���� ��� �߰��Ѵ�.
					if(data.buildingName !== ''){
						extraAddr += (extraAddr !== '' ? ', ' + data.buildingName : data.buildingName);
					}
					// �������ּ��� ������ ���� ���ʿ� ��ȣ�� �߰��Ͽ� ���� �ּҸ� �����.
					fullAddr += (extraAddr !== '' ? ' ('+ extraAddr +')' : '');
				}

				// �����ȣ�� �ּ� ������ �ش� �ʵ忡 �ִ´�.
				document.getElementById(post).value = data.zonecode; //5�ڸ� �������ȣ ���
				document.getElementById(addr1).value = fullAddr;

				// Ŀ���� ���ּ� �ʵ�� �̵��Ѵ�.
				if (addr2 != "") {
					document.getElementById(addr2).focus();
				}
			}
		}).open();
	}
	//-->
</script>

</BODY>
</HTML>