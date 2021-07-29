<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/shopdata.php");
include_once($Dir."lib/ext/product_func.php");
include_once($Dir."lib/ext/member_func.php");
include_once($Dir."lib/ext/order_func.php");

#### PG ����Ÿ ���� ####
$_ShopInfo->getPgdata();
########################

$ip = getenv("REMOTE_ADDR");

$sslchecktype="";
if($_POST["ssltype"]=="ssl" && strlen($_POST["sessid"])==64) {
	$sslchecktype="ssl";
}
if($sslchecktype=="ssl") {
	$secure_data=getSecureKeyData($_POST["sessid"]);
	if(!is_array($secure_data)) {
		echo "<html><head><title></title></head><body onload=\"alert('�������� ������ �߸��Ǿ����ϴ�.');history.go(-2);\"></body></html>";exit;
	}
	foreach($secure_data as $key=>$val) {
		${$key}=$val;
	}
} else {
	foreach($_POST as $key=>$val) {
		${$key}=$val;
	}
}

// �ֹ�Ÿ�Ժ� ��ٱ��� ���̺�
//$basket = basketTable($ordertype);
if(_empty($ordertype)) $basket = basketTable('order');
else $basket = basketTable($ordertype);



/**************************************************************************
�ֹ��� ����
***************************************************************************/

$sender_name		= ereg_replace(" ","",$sender_name);
$sender_email		= ereg_replace("'","",$sender_email);
$receiver_name		= ereg_replace(" ","",$receiver_name);
$order_msg			= ereg_replace("'","",$order_msg);
$sender_tel			= ereg_replace("'","",$sender_tel);
$receiver_tel1		= ereg_replace("'","",$receiver_tel1);
$receiver_tel2		= ereg_replace("'","",$receiver_tel2);
$receiver_addr		= ereg_replace("'","",$receiver_addr);
$rpost				= $rpost1.$rpost2;
$loc				= substr($raddr1,0,4);
$receiver_email		= ereg_replace("'","",$receiver_email);
$receiver_message	= ereg_replace("'","",$receiver_message);
$usereserve			= ereg_replace(",","",$usereserve);
$usereserve2		= ereg_replace(",","",$usereserve2);

$orderpatten		= array("(')","(\\\\)");
$orderreplace		= array("","");

/*---------------------------------------------------------------------------*/
if (strlen($paymethod)==0) {
	echo "<html></head><body onload=\"alert('��������� ���õ��� �ʾҽ��ϴ�.');parent.document.form1.process.value='N';parent.ProcessWait('hidden');\"></body></html>";
	exit;
}

if (strlen($usereserve)>0 && !IsNumeric($usereserve)) {
	echo "<html></head><body onload=\"alert('�������� ���ڸ� �Է��Ͻñ� �ٶ��ϴ�.');parent.document.form1.process.value='N';parent.ProcessWait('hidden');\"></body></html>";
	exit;
}

if(strlen($_data->escrow_id)==0 && $paymethod=="Q") {
	echo "
		<html>
			<body onload=\"
				alert('����ũ�� ������ �������� �ʽ��ϴ�.');
				parent.document.form1.process.value='N';
				parent.ProcessWait('hidden');
				parent.document.all.paybuttonlayer.style.display='block';
				parent.document.all.payinglayer.style.display='none';
			\"></body>
		</html>
	";
	exit;
}

$escrow_info = GetEscrowType($_data->escrow_info);
$escrow=$escrow_info['escrow'];
if(strlen($_data->escrow_id)>0 && ($escrow_info["escrowcash"]=="Y" || $escrow_info["escrowcash"]=="A")) {
	$escrowok="Y";
} else {
	$escrowok="N";
	$escrow_info["escrowcash"]="";
	if($escrow_info["onlycash"]!="Y" && (strlen($escrow_info["onlycard"])==0 && strlen($escrow_info["nopayment"])==0)) $escrow_info["onlycash"]="Y";
}

$pg_type="";
switch ($paymethod) {
	case "B":
		break;
	case "V":
		$pgid_info=GetEscrowType($_data->trans_id);
		$pg_type=$pgid_info["PG"];
		break;
	case "O":
		$pgid_info=GetEscrowType($_data->virtual_id);
		$pg_type=$pgid_info["PG"];
		break;
	case "Q":
		$pgid_info=GetEscrowType($_data->escrow_id);
		$pg_type=$pgid_info["PG"];
		break;
	case "C":
		$pgid_info=GetEscrowType($_data->card_id);
		$pg_type=$pgid_info["PG"];
		break;
	case "P":
		$pgid_info=GetEscrowType($_data->card_id);
		$pg_type=$pgid_info["PG"];
		break;
	case "M":
		$pgid_info=GetEscrowType($_data->mobile_id);
		$pg_type=$pgid_info["PG"];
		break;
}
$pg_type=trim($pg_type);

/**********************
* ������ �ӽ� ���� ���� (�׽�Ʈ�� �ּ� �ϱ�)
* A : KCP
* B : LG U+
* C : �ô�����Ʈ
* D : �̴Ͻý�
* E : ���̽�����
***********************/
//$pg_type = "E";

$pmethod=$paymethod.$pg_type;

if ($paymethod!="B" && strlen($pg_type)==0) {
	echo "
		<html>
				<body onload=\"
					alert('�����Ͻ� ��������� �̿��Ͻ� �� �����ϴ�.');
					parent.document.form1.process.value='N';
					parent.ProcessWait('hidden');
					parent.document.all.paybuttonlayer.style.display='block';
					parent.document.all.payinglayer.style.display='none';
				\">
				</body>
		</html>";
	exit;
}

$card_splittype		= $_data->card_splittype;
$card_splitmonth	= $_data->card_splitmonth;
$card_splitprice	= $_data->card_splitprice;

$coupon_ok			= $_data->coupon_ok;			//���� ��� ��� ����
$card_miniprice		= $_data->card_miniprice;		//ī����� �ּ� �ݾ�
$reserve_limit		= $_data->reserve_limit;		//������ �ִ� ��� �ݾ�
$reserve_maxprice	= $_data->reserve_maxprice;		//������ ���� �ִ� �ݾ�

if( $reserve_limit < 1 ) $reserve_limit=1000000000000;

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

if($_data->reserve_useadd==-1) $reserve_useadd="N";
else if($_data->reserve_useadd==-2) $reserve_useadd="U";
else $reserve_useadd=$_data->reserve_useadd;

$etcmessage=explode("=",$_data->order_msg);

#�������� ���ݰ����ÿ��� ��밡���ϰ� ���ݰ����� ���þ�������
if($bankreserve=="N" && !preg_match("/^(B|V|O|Q)$/",$paymethod)) {
	$usereserve=0;
}

$user_reserve=0;

// ȸ�� / ��ȸ��
if(strlen($_ShopInfo->getMemid())>0) {
	// ȸ�� ����
	$sql = "SELECT * FROM tblmember WHERE id='".$_ShopInfo->getMemid()."' ";
	$result=mysql_query($sql,get_db_conn());
	if($row=mysql_fetch_object($result)) {
		$ordercode=unique_id();
		$user_reserve = $row->reserve;
		$group_code=$row->group_code;
		$id=$_ShopInfo->getMemid();
		mysql_free_result($result);

		if(strlen($group_code)>0 && $group_code!=NULL) {
			$sql = "SELECT * FROM tblmembergroup WHERE group_code='".$group_code."' ";
			$result=mysql_query($sql,get_db_conn());
			if($row=mysql_fetch_object($result)) {
				$group_code=$row->group_code;
				$group_name=$row->group_name;
				$group_type=substr($row->group_code,0,2);
				$group_usemoney=$row->group_usemoney;
				$group_addmoney=$row->group_addmoney;
				$group_payment=$row->group_payment;
			}
			mysql_free_result($result);

		}
	} else {
		$_ShopInfo->SetMemNULL();
		// ��ϵ� ȸ���� ������ - ��ȸ��
		$ordercode=unique_id()."X";
		$id="X".date("iHs").$sender_name;
	}
} else {
	//��ȸ�� ������ ��� �ֹ���ȣ �ڿ� X�� ����
	$ordercode=unique_id()."X";
	$id="X".date("iHs").$sender_name;
}










/****************************************************
��� ���� �ľ� START
*****************************************************/
	$errmsg="";
	$sql = "SELECT a.quantity as sumquantity,b.productcode,b.productname,b.display,b.quantity,b.group_check,b.social_chk, ";
	$sql.= "b.option_quantity,b.etctype,b.assembleuse,a.assemble_list AS basketassemble_list ";
	$sql.= ", c.assemble_list,a.package_idx ";
	$sql.= "FROM tblbasket a, tblproduct b ";
	$sql.= "LEFT OUTER JOIN tblassembleproduct c ON b.productcode=c.productcode ";
	$sql.= "WHERE a.tempkey='".$_ShopInfo->getTempkey()."' ";
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
						mysql_query("delete from tblbasket where a.tempkey='".$_ShopInfo->getTempkey()."' and productcode='".$row->productcode."'",get_db_conn()); // ���� ó��
					}
				}
			}
		}


		if($row->social_chk =="Y") {	//�ҼȻ�ǰ
			$sql2 = "SELECT count(1) as cnt FROM tblproduct_social WHERE pcode='".$row->productcode."' AND '".time()."' between sell_startdate and sell_enddate ";
			$result2=mysql_query($sql2,get_db_conn());
			if($row2=mysql_fetch_object($result2)) {
				if(strlen($errmsg)==0 && $row2->cnt == 0){
					$errmsg="[".ereg_replace("'","",$row->productname)."]��ǰ�� �ǸŰ� ����� ��ǰ�Դϴ�.\\n";
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
		$assemble_list_exp = array();
		if(strlen($errmsg)==0 && $row->assembleuse=="Y") { // ����/�ڵ� ��ǰ ��Ͽ� ���� ������ǰ üũ
			if(strlen($row->assemble_list)==0) {
				$errmsg="[".ereg_replace("'","",$row->productname)."]��ǰ�� ������ǰ�� �̵�ϵ� ��ǰ�Դϴ�. �ٸ� ��ǰ�� �ֹ��� �ּ���.\\n";
			} else {
				$assemble_list_exp = explode("",$row->basketassemble_list);
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

		$package_productcode_tmp = array();
		$package_quantity_tmp = array();
		$package_productname_tmp = array();
		if(strlen($errmsg)==0 && $row->package_idx>0) { // ��Ű�� ��ǰ ��Ͽ� ���� ������ǰ üũ
			if(count($productcode_package_list[$row->productcode][$row->package_idx])>0) {
				$package_productcode_tmp = $productcode_package_list[$row->productcode][$row->package_idx];
				$package_quantity_tmp = $quantity_package_list[$row->productcode][$row->package_idx];
				$package_productname_tmp = $productname_package_list[$row->productcode][$row->package_idx];
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
				$basketsql = "SELECT productcode,assemble_list,quantity,assemble_idx FROM tblbasket ";
				$basketsql.= "WHERE tempkey='".$_ShopInfo->getTempkey()."' ";
				$basketresult =mysql_query($basketsql,get_db_conn());
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
			} else if(strlen($package_productcode_tmp)>0) { // ��Ű�� ������ǰ�� ��� üũ
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
				$sql.= "WHERE tempkey='".$_ShopInfo->getTempkey()."' ";
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
				mysql_free_result($result2);
			}
		}
	}
	mysql_free_result($result);

	if(strlen($errmsg)>0) {
		echo "<html></head><body onload=\"alert('".$errmsg."');parent.location.href='".$Dir.FrontDir."basket.php'\"></body></html>";
		exit;
	}
/****************************************************
��� ���� �ľ� END
*****************************************************/



// ����ǰ ��� Ȯ�� ���� ----------------------------------------------------------------------------------------------------------------------------------------
$giftinfo = array();
if(!_empty($giftval_seq) && $_REQUEST['apply_gift'] != 'N'){
	$gift_msg = addslashes($gift_msg);
	$sql = "SELECT * FROM tblgiftinfo WHERE gift_regdate='".$giftval_seq."' limit 1";

	if(false !== $result=mysql_query($sql,get_db_conn())){
		if($giftinfo=mysql_fetch_assoc($result)){
			$giftinfo['options'] = array();

			for($g=1;$g<=4;$g++){
				if(!_empty($giftinfo['gift_option'.$g])){
					$tmp = explode(',',$giftinfo['gift_option'.$g]);
					$giftinfo['options'][$g][0] = $tmp[0];
					$giftinfo['options'][$g][1] = array();

					for($gi=1;$gi<count($tmp);$gi++){
						if(strpos($tmp[$gi],':')){
							$tmp2 = explode(':',$tmp[$gi]);
							$giftinfo['options'][$g][1][$tmp2[0]] = $tmp2[1];
						}else{
							$giftinfo['options'][$g][1][$tmp[$gi]] = true;
						}
					}


					if(!_empty($_REQUEST['giftOpt'.$g])){
						if(isset($giftinfo['options'][$g][1][$_REQUEST['giftOpt'.$g]])){
							if($giftinfo['options'][$g][1][$_REQUEST['giftOpt'.$g]] !== true){
								$chkq = $giftinfo['options'][$g][1][$_REQUEST['giftOpt'.$g]]--;
								if($chkq < 1){
									echo "<html></head><body onload=\"alert('����ǰ�� �ش� �ɼ��� �ٸ� ���� ���� ǰ�� �Ǿ����ϴ�. ����ǰ�� �ٽ� ������ �ּ���.');parent.location.href='".$Dir.FrontDir."basket.php'\"></body></html>";
									exit;
								}
							}
							$giftinfo['selopt'][$g] = $giftinfo['options'][$g][0].' : '.$_REQUEST['giftOpt'.$g];
						}
					}
				}
			}

			if(_isInt($giftinfo['quantity'])){
				if($giftinfo['quantity'] < 0){
					echo "<html></head><body onload=\"alert('����ǰ�� �ٸ� ���� ���� ǰ�� �Ǿ����ϴ�. ����ǰ�� �ٽ� ������ �ּ���.');parent.location.href='".$Dir.FrontDir."basket.php'\"></body></html>";
					exit;
				}
			}
			$giftinfo['gift_quantity'] = 1;
		}
	}
}
// ����ǰ ��� Ȯ�� �� ----------------------------------------------------------------------------------------------------------------------------------------//




	// ��ٱ��� ȣ��
	$basketItems = getBasketByArray($basket); // ���� ����ȵ�


	// ��ٱ��� ��ǰ �Է� ���� ==================================================================
	$bankonly = "N";
	$goodname = "";
	foreach($basketItems['vender'] as $vender=>$vendervalue) {


		// ��ǰ ����Ʈ ���� ---------------------------------------------------------------------------------------------------------------------------

		foreach( $vendervalue['products'] as $productKey=>$product ) {
			if($product['rental'] != '2'){ // ��Ż ��ǰ ����
				// �ɼ� �׷� ��� ó�� ���� -----------------------------------------
				$optvalue="";
				$optvalue2="";
				if( ereg("^(\[OPTG)([0-9]{4})(\])$",$product['optionGroup']) ) {
					$optioncode = substr($product['optionGroup'],5,4);
					$product['option_price']="";
					if( $product['optidxs'] != 0 ) {
						$tempoptcode = substr($row->optidxs,0,-1);
						$exoptcode = explode(",",$tempoptcode);
						$sqlopt = "SELECT * FROM tblproductoption WHERE option_code='".$optioncode."' ";
						$resultopt =mysql_query($sqlopt,get_db_conn());
						if($rowopt = mysql_fetch_object($resultopt)){
							$optionadd = array ( &$rowopt->option_value01, &$rowopt->option_value02, &$rowopt->option_value03, &$rowopt->option_value04, &$rowopt->option_value05, &$rowopt->option_value06, &$rowopt->option_value07, &$rowopt->option_value08, &$rowopt->option_value09, &$rowopt->option_value10 );
							$opti=0;
							$optvalue="";
							while(strlen($optionadd[$opti])>0) {
								if($exoptcode[$opti]>0) {
									$opval = explode("",str_replace('"','',$optionadd[$opti]));
									$exop = explode(",",str_replace('"','',$opval[$exoptcode[$opti]]));
									$optvalue.= ", ".$opval[0]." : ";
									if ($exop[1]>0) $optvalue.=$exop[0]."(<font color=#FF3C00>+".$exop[1]."��</font>)";
									else if($exop[1]==0) $optvalue.=$exop[0];
									else $optvalue.=$exop[0]."(<font color=#FF3C00>".$exop[1]."��</font>)";
									$row->sellprice+=$exop[1];
								}
								$opti++;
							}
							$optvalue = substr($optvalue,1);
							$optcnt++;
	
							$optvalue2 = "[OPTG".substr("00".$optcnt,-3)."]";
						}
					}
				}
	
				if(strlen($optvalue2)>0){
					$optvalue2=str_replace("'","\'",$optvalue2);
					$optvalue=str_replace("'","\'",$optvalue);
					$optionGroupSQL = "
						INSERT
							tblorderoptiontemp
						SET
							ordercode = '".$ordercode."',
							productcode = '".$product['productcode']."',
							opt_idx = '".$optvalue2."',
							opt_name = '".trim( $product['optvalue'] )."'
						;
					";
					mysql_query($optionGroupSQL,get_db_conn());
					//echo "<hr>�ɼ� �׷� ���<br> ".$optionGroupSQL;
				}
				// �ɼ� �׷� ��� ó�� �� ----------------------------------------- //
	
	
	
				// ��ǰ DB ���� ���� ���� -----------------------------------------------------------------------
				/*
				$tempoptcnt="";
				if(_array($product['option_quantity'])) {
	
					for($j=0;$j<10;$j++) {
						for($i=0;$i<10;$i++) {
							$selNum = ($product['opt2_idx']-1)*10+($product['opt1_idx']-1);
							$thisNum = $j*10+$i;
							if( $selNum == $thisNum ){
								$tempoptcnt .= ",".($product['option_quantity'][$thisNum+1]-$product['quantity']);
							} else {
								$tempoptcnt .= ",".($product['option_quantity'][$thisNum+1]);
							}
						}
					}
					if(strlen($tempoptcnt)>0) {
						$tempoptcnt=" , option_quantity='".$tempoptcnt."' ";
					}
				} else{
					$tempoptcnt=" , quantity = quantity-".$product['quantity']." ";
				}
	
				$productUpdateSQL = "
					UPDATE
						tblproduct
					SET
						sellcount = sellcount+1
						".$tempoptcnt."
					WHERE
						productcode='".$product['productcode']."'
				";
				mysql_query($productUpdateSQL,get_db_conn());
				*/
				//echo "<hr>��ǰ DB ���� ����<br> ".$productUpdateSQL;
				// ��ǰ DB ���� ���� �� ----------------------------------------------------------------------- //
			} // #��Ż ��ǰ ����


			// ��ǰ DB ���� ���� ���� -----------------------------------------------------------------------
			$tempoptcnt="";
			if($product['rental'] != '2'){ // ��Ż ��ǰ ����
				if(_array($product['option_quantity'])) {

					for($j=0;$j<10;$j++) {
						for($i=0;$i<10;$i++) {
							$selNum = ($product['opt2_idx']-1)*10+($product['opt1_idx']-1);
							$thisNum = $j*10+$i;
							if( $selNum == $thisNum ){
								$tempoptcnt .= ",".($product['option_quantity'][$thisNum+1]-$product['quantity']);
							} else {
								$tempoptcnt .= ",".($product['option_quantity'][$thisNum+1]);
							}
						}
					}
					if(strlen($tempoptcnt)>0) {
						$tempoptcnt=" , option_quantity='".$tempoptcnt."' ";
					}
				} else{
					$tempoptcnt=" , quantity = quantity-".$product['quantity']." ";
				}
			}

			$productUpdateSQL = "
				UPDATE
					tblproduct
				SET
					sellcount = sellcount+1
					".$tempoptcnt."
				WHERE
					productcode='".$product['productcode']."'
			";
			mysql_query($productUpdateSQL,get_db_conn());


			

			// ������ ���� �߰� ���� ����
			if($reserve_useadd!="N" && $usereserve>=$reserve_useadd && $usereserve!=0) {
				$product['reserve']=0;
			} else if($reserve_useadd=="U" && $usereserve!=0) {
				$reservepercent = 100 * ($sumprice-$usereserve) / $sumprice;
				$product['reserve']=round($product['reserve']*($reservepercent/100),-1);
			}


			//������ ������� �� ����
			$mem_reserve = getProductReserve($product['productcode']);
			$product['reserve'] = $mem_reserve;

			$prentinfo['codeinfo'] = venderRentInfo($product['vender'],$product['pridx'],$product['productcode']);

			if($usereserve2>0){
				$product['reserve']=0;

				$usereserve2 = ( $usereserve2 > $sumprice ) ? $sumprice : $usereserve2;
				$sumprice -= $usereserve2;
				$sumprice = ( $sumprice < 0 ) ? 0 : $sumprice;

			}else{
				$product['reserve']=round($sumprice * $product['reserve'],-1);
			}

			//echo $usereserve."/".$usereserve2."/".$product['reserve'];exit;

			if($product['rental'] != '2'){ // ��Ż ��ǰ ����			
				//��ǰ ��� ���� ----------------------------------------------------------------------
				if(strlen($optvalue2) > 0 ) { // �ɼǱ׷� ��ǰ�� ���
					$orderProductOptValue[0] = $optvalue2;
					$orderProductOptValue[1] = '';
				}else{
					if( strlen($product['optvalue']) > 0 ) {
						$orderProductOptValue = explode( ",",  $product['optvalue'] );
					} else{
						$orderProductOptValue[0] = ( strlen($product['option1'][$product['opt1_idx']]) > 0 ) ? $product['option1'][0].":".$product['option1'][$product['opt1_idx']] : '';
						$orderProductOptValue[1] = ( strlen($product['option2'][$product['opt2_idx']]) > 0 ) ? $product['option2'][0].":".$product['option2'][$product['opt2_idx']] : '';
					}
				}
				$product['longdiscount'] = 0;
			}else{ // ��Ż ��ǰ;
				$tmpp = rentProduct::read($product['pridx']);
				$orderProductOptValue[0] = $tmpp['options'][$product['rentinfo']['optidx']]['optionName'];

				if($prentinfo['codeinfo']['pricetype']=="long"){
					if($tmpp['options'][$product['rentinfo']['optidx']]['optionPay']=="�Ͻó�"){
						$orderProductOptValue[1] = $tmpp['options'][$product['rentinfo']['optidx']]['optionPay'];
					}else{
						$orderProductOptValue[1] = $tmpp['options'][$product['rentinfo']['optidx']]['optionPay']." �� ".number_format($tmpp['options'][$product['rentinfo']['optidx']]['nomalPrice']/$tmpp['options'][$product['rentinfo']['optidx']]['optionName'])."��";
					}
				}else{
					$orderProductOptValue[1] = rentProduct::_status($tmpp['options'][$product['rentinfo']['optidx']]['grade']);
				}
			}
			
/*
			//��Ż��ǰ �ɼǼ�������
			$rentRrdpdateSQL = "
				UPDATE
					rent_product_option
				SET
					productCount = productCount-".$product['quantity']." 
				WHERE
					pridx='".$product['pridx']."' 
				AND optionName='".$orderProductOptValue[0]."' 
			";
			mysql_query($rentRrdpdateSQL,get_db_conn());
*/


			$orderProductSQL = "
				INSERT
					tblorderproducttemp
				SET
					vender = '".$product['vender']."',
					ordercode = '".$ordercode."',
					tempkey = '".$_ShopInfo->getTempkey()."',
					productcode = '".$product['productcode']."',
					productname = '".preg_replace($orderpatten,$orderreplace,$product['productname'])."',
					opt1_name = '".trim( $orderProductOptValue[0] )."',
					opt2_name = '".trim( $orderProductOptValue[1] )."',
					addcode = '".$product['addcode']."',
					quantity = '".$product['quantity']."',
					price = '".$product['sellprice']."',
					reserve = '".round($product['reserve']/$product['quantity'])."',
					date = '".$product['date']."',
					selfcode = '".$product['selfcode']."',
					order_prmsg = '".$order_prmsg."',
					sell_memid = '".$product['sell_memid']."',
					longdiscount = '".$product['longdiscount']."',
					basketidx='".$product['basketidx']."'
				;
			";
			
			mysql_query($orderProductSQL,get_db_conn());
			$orderProduct_UID = mysql_insert_id (get_db_conn()); //�ֹ� ��ǰ ���� ��ȣ
			//echo "<hr>��ǰ ��� <br> ".$orderProductSQL;


			// ���� ��� ��ǰ ��Ī �ֹ� ��ǰ ���� ��ȣ -------------------------------------------------
			$orderProductKey = $product['productcode']."_".$product['opt1_idx']."_".$product['opt2_idx']."_".$product['optidxs'];
			$orderProductKey = str_replace(",","",$orderProductKey);
			$orderProductUid[ $orderProductKey ] = $orderProduct_UID; // �ֹ� ��ǰ idx
			//echo "<hr>���� ��� ��ǰ ��Ī Ű �ֹ� ��ǰ ���� ��ȣ <br> ".$orderProductKey." => ".$orderProductUid[ $orderProductKey ];


			// ��ǰ ��� �� -----------------------------------------------------------------------//





			// today sale �Ǹ� �ð� ���� check ���� -----------------------------------------------------------------------
			if(preg_match('/^899[0-9]{15}$/',$product['productcode'])){
				$tsql = "
					update
						todaysale T,
						tblproduct P
					set
						T.salecnt=T.salecnt+".intval($product['quantity'])."
					WHERE
						P.productcode = '".$product['productcode']."'
						AND
						T.pridx=P.pridx
					limit 1 ;
				";
				mysql_query($tsql,get_db_conn());
				//echo "<hr>today sale �Ǹ� �ð� ���� check<br> ".$tsql;
			}
			// today sale �Ǹ� �ð� ���� check �� ----------------------------------------------------------------------- //



			// ���ݰ��� ���� ��ǰ ------------------------------------------------------
			if( $product['bankonly'] == "Y" ) $bankonly = "Y";


			// PG�� ��ǰ ��
			if (strlen($goodname)>0) $goodname = preg_replace($orderpatten,$orderreplace,$product['productname'])." ��.."; else $goodname = preg_replace($orderpatten,$orderreplace,$product['productname']);


		}
		// ��ǰ ����Ʈ �� -----------------------------------------------------------------------------------------------------------------------------//
	}
	// ��ٱ��� ��ǰ �Է� �� ===================================================================//


	// ���ݰ�����ǰ�� �ִµ� ī��������ý�
	if ($bankonly=="Y" && !preg_match("/^(B|V|O|Q)$/",$paymethod)) {
		echo "<html></head><body onload=\"alert('���ݰ��� ��ǰ�� �ֱ� ������ ������ �Ա� ������ �����Ͻ� �� �ֽ��ϴ�.');parent.location.href='./basket.php'\"></body></html>";
		exit;
	}



	// ���� ��� ���� ��� ���� -------------------------------------------------------
	if( !_empty($_ShopInfo->getMemid())
		AND
		$_data->coupon_ok=="Y"
		AND
		!_empty($_REQUEST['couponproduct'])
		AND
		(
			!_empty($_REQUEST['coupon_price'])
			OR
			!_empty($_REQUEST['coupon_reserve'])
		)
	) {

		$couponitems = array();

		$dcpricelist = explode("|",$_REQUEST['dcpricelist']); // ���αݾ׸���Ʈ
		$drpricelist = explode("|",$_REQUEST['drpricelist']); // �����ݾ� ����Ʈ
		$couponproduct = explode("|",$_REQUEST['couponproduct']); // ���������ǰ ����Ʈ

		$tmpcoupon = array();

		// ��������Ʈ
		for($qq=1;$qq<count($couponproduct);$qq++){

			$tmpProductcode = explode("_",$couponproduct[$qq]); // �������� ��ǰ ���� �м� ( �����ڵ�_��ǰ�ڵ�_�ɼ�1_�ɼ�2 )
			$tmpCouponCode = $tmpProductcode[0];

			// ���� ��� ��ǰ ��Ī-------------------------------------------------
			$orderProductUidKey = $tmpProductcode[1]."_".$tmpProductcode[2]."_".$tmpProductcode[3]."_".$tmpProductcode[4];
			$orderProductUidKey = str_replace(",","",$orderProductUidKey);
			$orderUid = $orderProductUid[ $orderProductUidKey ];
			//echo "<hr>���� ��� ��ǰ ��Ī Ű<br> ".$orderProductUidKey." =>".$orderUid;
			if( $dcpricelist[$qq] > 0 ) {
				$orderCouponMatching = "
					INSERT
						tblordercoupon
					SET
						ordercode = '".$ordercode."',
						orderPuid = '".$orderUid."',
						couponcode = '".$tmpCouponCode."' ,
						dcPrice = '".intval($dcpricelist[$qq])."',
						reserve = '".intval($drpricelist[$qq])."'
					;
				";
				mysql_query($orderCouponMatching,get_db_conn());
				//echo "<hr>���� ��� ��ǰ ��Ī<br> ".$orderCouponMatching;
			}
			// ���� ��� ��ǰ ��Ī------------------------------------------------- //


			if( _array($couponitems[$tmpCouponCode]) ) {
				$couponitems[$tmpCouponCode]['coupon_price'] -= intval($dcpricelist[$qq]); // ������ ����
				$couponitems[$tmpCouponCode]['coupon_reserve'] += intval($drpricelist[$qq]); // ������ ����
				$couponitems[$tmpCouponCode]['couponmsg'] .= ','.$basketItems['arr_prlist'][$tmpProductcode[1]]; // ���� ��� ��ǰ ���..
			}else{

				if(!isset($tmpcoupon[$tmpCouponCode]) || !is_object($tmpcoupon[$tmpCouponCode])){
					$sql = "SELECT * FROM tblcouponinfo  WHERE coupon_code ='".$tmpCouponCode."'  limit 1";
					$resultcou =mysql_query($sql,get_db_conn());
					if($rowcou=mysql_fetch_object($resultcou)){
						$tmpcoupon[$rowcou->coupon_code] = $rowcou;
					}
				}

				if(!isset($tmpcoupon[$tmpCouponCode]) || !is_object($tmpcoupon[$tmpCouponCode])){
					continue;
				}
				$rowcou = $tmpcoupon[$tmpCouponCode];

				$tmp = array();
				$tmp['coupon_code'] = $rowcou->coupon_code;
				$tmp['vender']  = $rowcou->vender;
				$tmp['use_point'] = $rowcou->use_point;

				$tmp['coupon_name']=titleCut(50,$rowcou->coupon_name)." - ".number_format($rowcou->sale_money).($rowcou->sale_type<=2?"%":"��").($rowcou->sale_type%2==0?"����":"����")."����";
				$tmp['coupon_name'] = addslashes($tmp['coupon_name']);
				$tmp['coupon_price'] = intval($dcpricelist[$qq])*-1;
				$tmp['coupon_reserve'] = intval($drpricelist[$qq]);

				$tmp['couponmsg'] = $basketItems['arr_prlist'][$tmpProductcode[1]];
				$couponitems[$tmp['coupon_code']] = $tmp;
			}
		}


		if(_array($couponitems)){
			foreach($couponitems as $citem){
				if (isSeller() == 'Y') $citem['coupon_reserve'] = 0; // ����ȸ�� ���� �ȵ�
				$couponSQL = "
					INSERT
						tblorderproducttemp
					SET
						vender = '".$citem['vender']."',
						ordercode = '".$ordercode."',
						tempkey = '".$_ShopInfo->getTempkey()."',
						productcode = 'COU".$citem['coupon_code']."0X',
						productname = '".preg_replace($orderpatten,$orderreplace,$citem['coupon_name'])."',
						quantity = '1',
						price = '".$citem['coupon_price']."',
						reserve = '".$citem['coupon_reserve']."',
						date = '".date("Ymd")."',
						order_prmsg = '".preg_replace($orderpatten,$orderreplace,$citem['couponmsg'])."'
					;
				";
				mysql_query($couponSQL,get_db_conn());
				//echo "<hr>���� ���� ���<br> ".$couponSQL;
			}
		}

	}
	// ���� ��� ���� ��� �� ------------------------------------------------------- //




	//echo "<hr>�ʱ� �Ѱ����ݾ� : ".number_format($sumprice);


	// ������ ���  --------------------------------------------------------------------------------------------------------------------------------------
	if( $usereserve > 0 ) {
		$usereserve = ( $usereserve > $sumprice ) ? $sumprice : $usereserve;// ��������
		$sumprice -= $usereserve;
		$sumprice = ( $sumprice < 0 ) ? 0 : $sumprice;

		// ���� �������� �ٽ� �־� �ְų� ���ش�.
		/*
		if($sumprice == 0 AND $sumprice<=$usereserve) {
			$remain_reserve = $user_reserve - $sumprice;
			$usereserve = $sumprice;
		} else {
		*/
			$remain_reserve=$user_reserve-$usereserve;
		//}

		if( $remain_reserve < 0 ) $remain_reserve = 0;

		if(strlen($_ShopInfo->getMemid())>0 && $_data->reserve_maxuse>=0) {
			$remainReserveSQL = "UPDATE tblmember SET reserve=".abs($remain_reserve)." WHERE id='".$_ShopInfo->getMemid()."' ";
			mysql_query($remainReserveSQL,get_db_conn());
			//echo "<hr>���� �������� �ٽ� �־� �ְų� ���ش�<br> ".$remainReserveSQL;
		}

	}

	//$usereserve = $usereserve + $usereserve2;

	if($sumprice==0) {
		$pay_data="�� ���űݾ� ".number_format($usereserve)."���� ���������� ����";
	}

	//echo "<hr>������ ��� �Ѱ����ݾ� (-".number_format($usereserve).") : ".number_format($sumprice);


	// ���� ���� ���   --------------------------------------------------------------------------------------------------------------------------------------
	if( $coupon_price > 0 ) {
		$coupon_price = ( $coupon_price > $sumprice ) ? $sumprice : $coupon_price;
		$sumprice -= $coupon_price;
		$sumprice = ( $sumprice < 0 ) ? 0 : $sumprice;
	}

	//echo "<hr> ���� ���� ��� �Ѱ����ݾ� (-".number_format($coupon_price).") : ".number_format($sumprice);


	// ȸ���׷�(�߰�)����   --------------------------------------------------------------------------------------------------------------------------------------
	if( $groupdiscount < 0 ) {
		$sumprice += $groupdiscount;
		$sumprice = ( $sumprice < 0 ) ? 0 : $sumprice;
	}

	//echo "<hr> ȸ���׷�(�߰�)���� �Ѱ����ݾ� (-".number_format($groupdiscount).") : ".number_format($sumprice);








	// ��۷� �߰�  --------------------------------------------------------------------------------------------------------------------------------------
	// ���� ��� ���� ����
	$basketTempList = explode("-",$_POST['basketTempList']);
	foreach ( $basketTempList as $val ){
		$sub = explode("|",$val);
		if( strlen($sub[0]) > 0 ) $basketTempArray[$sub[0]] += $sub[1];
	}
	$basketItemsCoupon = getBasketByArray($basket,$basketTempArray); // ���� �����

	// �ѹ�ۺ� �հ迡 ����
	if( $basketItemsCoupon['deli_price'] > 0 ) {
		$sumprice += $basketItemsCoupon['deli_price'];
		$sumprice = ( $sumprice < 0 ) ? 0 : $sumprice;
	}

	/*
	echo "<div style=\" height:500px; overflow:scroll;  border:2px solid #ff0000 ;  text-align:left;\">";
	_pr($basketItemsCoupon);
	echo "</div>";
	*/
	// ��ۺ� ���� ���� ���� ------------------------------------------
	foreach($basketItemsCoupon['vender'] as $venderCoupon=>$vendervalueCoupon) {
		// ��ǰ ����Ʈ ���� ---------------------------------------------------------------------------------------------------------------------------
		$deliPrt = ""; // ��� ó�� �޼���
		$order_prmsg = "";
		foreach( $vendervalueCoupon['products'] as $productCoupon ) {

			// ���� ��۷� ���� ���� ------------------------------------------------------
			if($productCoupon['deli_price']>0){
				$tempDeliPrice = 0;
				if($productCoupon['deli']=="Y"){
					$tempDeliPrice = $productCoupon['deli_price']*$productCoupon['quantity'];
				}else if($productCoupon['deli']=="N") {
					$tempDeliPrice = $productCoupon['deli_price'];
				}
				$deliPrt .= "������(". ( $tempDeliPrice > 0 ? number_format($tempDeliPrice)."��" : "����" ) .", ".preg_replace($orderpatten,$orderreplace,$productCoupon['productname']).")  ";
			}
			$order_prmsg .= "- ".preg_replace($orderpatten,$orderreplace,$productCoupon['productname']).", ";
			// ���� ��۷� ���� �� ------------------------------------------------------//
		}

		if( $vendervalueCoupon['deliprice'] > 0 ) {

			// �⺻ ��ۺ� ����
			if( $vendervalueCoupon['delisumprice'] > 0 ) {
				$deliPrt .= ( $venderCoupon > 0 ? "������ " : "" )."�⺻��ۺ�(". ( $vendervalueCoupon['conf']['deli_price'] > 0 ? number_format($vendervalueCoupon['conf']['deli_price'])."��" : "����" ) .")";
			}

			$deliSQL = "
				INSERT
					tblorderproducttemp
				SET
					vender = '".$venderCoupon."',
					ordercode = '".$ordercode."',
					tempkey = '".$_ShopInfo->getTempkey()."',
					productcode = '99999999990X',
					productname = '��۷�(".$deliPrt.")',
					quantity = '1',
					price = '".$vendervalueCoupon['deliprice']."',
					reserve = '0',
					date = '".date("Ymd")."',
					order_prmsg = '".$order_prmsg."'
				;
			";
			mysql_query($deliSQL,get_db_conn());
			//echo "<hr>��ۺ� ���� ���� <br> ".$deliSQL;exit;
		}
	}
	// ��ۺ� ���� ���� �� --------------------------------------------//

	//echo "<hr>��۷� �߰� �Ѱ����ݾ� (+".number_format($basketItemsCoupon['deli_price']).") : ".number_format($sumprice);






	/*
	// �ΰ��� ���� --------------------------------------------------------------------------------------------------------------------------------------

	if($_data->ETCTYPE["VATUSE"]=="Y") {
		$sumpricevat = return_vat($sumprice);
		if($sumpricevat>0) {
			$sumprice+=$sumpricevat;
			$vatUseSQL = "INSERT INTO tblorderproducttemp (ordercode, tempkey, productcode, productname, quantity, price, reserve, date) VALUES ('".$ordercode."','".$oldtempkey."','99999999997X','�ΰ��� VAT 10% �ΰ�','1','".$sumpricevat."','0','".date("Ymd")."')";
			mysql_query($vatUseSQL,get_db_conn());
		}
	}



	if (preg_match("/^(C|P|M)$/", $paymethod) && $_data->card_payfee>0) {

		// ī������� �߰� ������ ���� - �̰ŵ� �ֹ� ���� ���� �ջ갡 ó�� �ؾ���.... �Ф�
		$tempprice = ((int) ($sumprice * ($_data->card_payfee/100) /100)) * 100;
		$sumprice+=$tempprice;
		$sql = "INSERT INTO tblorderproducttemp (ordercode, tempkey, productcode, productname, quantity, price, reserve, date) VALUES ('".$ordercode."','".$oldtempkey."','99999999998X','ī������� �ݾ׿��� ".$_data->card_payfee."% ������ �ΰ�','1','".$tempprice."','0','".date("Ymd")."')";
		mysql_query($sql,get_db_conn());

	} else if (preg_match("/^(B|V|O|Q)$/",$paymethod) && $_data->card_payfee<0 && $sumprice>$usereserve) {

		// ���ݰ����� ������ ���� & �����ݾ׸����� ���������� �ݾ� �� ���� ������ ���� �Ǵ°� �ƴѰ� ������,,,,,
		if($paymethod=="Q" && $escrow_info["esbank"]=="Y") {

		} else {
			if($_data->card_payfee<-50){
				$_data->card_payfee+=50;
				$saletype="Y";
			}
			$_data->card_payfee=abs($_data->card_payfee);
			$dctemp = floor(($sumprice-$deli_price)/100*$_data->card_payfee/100)*100;
			$dctemp = (isSeller() == 'Y') ? 0 : $dctemp;
			if($saletype=="Y" && strlen($_ShopInfo->getMemid())>0) {
				$sql = "INSERT INTO tblorderproducttemp (ordercode, tempkey, productcode, productname, quantity, price, reserve, date) VALUES ('".$ordercode."','".$oldtempkey."','99999999999X','���ݰ����� �����ݾ׿��� ".$_data->card_payfee."% �߰� ����','1','0','".$dctemp."','".date("Ymd")."')";
				mysql_query($sql,get_db_conn());
			} else if($saletype!="Y") {
				$sumprice -= $dctemp;
				$sql = "INSERT INTO tblorderproducttemp (ordercode, tempkey, productcode, productname, quantity, price, reserve, date) VALUES ('".$ordercode."','".$oldtempkey."','99999999999X','���ݰ����� �����ݾ׿��� ".$_data->card_payfee."% �߰� ����','1',".-$dctemp.",'0','".date("Ymd")."')";
				mysql_query($sql,get_db_conn());
			}
		}
	}

	if ($paymethod=="Q" && $escrow_info["percent"]>0) {  // ����ũ�� ������ �߰� ������ ����
		$templast_price = ((int) ($last_price * ($escrow_info["percent"]/100) /10)) * 10;
		if($templast_price<300) $templast_price=300;
		$last_price+=$templast_price;
		$sql = "INSERT INTO tblorderproducttemp (ordercode, tempkey, productcode, productname, quantity, price, reserve, date) VALUES ('".$ordercode."','".$oldtempkey."','99999999998X','����ũ�� ������ �ݾ׿��� ".$escrow_info["percent"]."% ������ �ΰ�','1','".$templast_price."','0','".date("Ymd")."')";
		mysql_query($sql,get_db_conn());
	}
	*/




	// �ֹ� ���̳ʽ� ���� ���� ����
	if( $sumprice < 1 ) {
		if( $sumprice < 0 OR ( $sumprice == 0 AND $usereserve == 0 AND $coupon_price == 0 ) ) {
			echo "<html><head><title></title></head><body onload=\"alert('�˼��մϴ�.\\n���� ������ �߸� �Ǿ����ϴ�.\\n��õ� �Ͻñ� �ٶ��ϴ�.');history.go(-2);\"></body></html>";
			exit;
		}
	}



	// �ӽ� �ֹ��� ��� =============================================================================
	if ($paymethod=="B") {
		// ������ü�� �Ա� ���� ����
		$pay_data = $pay_data1;
	} else if (preg_match("/^(C|P)$/", $paymethod)) {
		//
		$pay_data = $pay_data2;
	} else if ($paymethod=="V") {
		$pay_data = "�ǽð� ������ü ������";
	}

	$tblorderinfotempSQL = "
		INSERT
			tblorderinfotemp
		SET
			ordercode = '".$ordercode."',
			tempkey = '".$_ShopInfo->getTempkey()."',
			id = '".$id."',
			price = '".$sumprice."',
			deli_price = '".$deliprice."',
			dc_price = '".$groupdiscount."',
			reserve = '".$usereserve."',
			dc_now = '".$usereserve2."',
			paymethod = '".$pmethod."',
			pay_data = '".$pay_data."',
			sender_name = '".$sender_name."',
			sender_email = '".$sender_email."',
			sender_tel = '".$sender_tel."',
			receiver_name = '".$receiver_name."',
			receiver_tel1 = '".$receiver_tel1."',
			receiver_tel2 = '".$receiver_tel2."',
			receiver_addr = '".$receiver_addr."',
			order_msg = '".$order_msg."',
			ip = '".$ip."',
			del_gbn = '',
			partner_id = '".$_ShopInfo->getRefurl()."',
			loc = '".$loc."',
			order_type = '".$ordertype."',
			receiver_email = '".$receiver_email."',
			receiver_message = '".$receiver_message."',
			device = 'P',
			bankname = '".$_REQUEST['bankname']."'
	";
	if($sumprice==0) {
		$tblorderinfotempSQL.= ", bank_date = '".date("YmdHis")."' ";
		if(preg_match("/^(O|Q)$/", $paymethod)) $tblorderinfotempSQL.= ", pay_flag = '0000', ";	//������¸�,,,
	}
	mysql_query($tblorderinfotempSQL,get_db_conn());
	//echo "<hr>�ֹ� ���� ���<br> ".$tblorderinfotempSQL;



	// PG �����ݾ� ���� ����
	$SupplyAmt = $vat = 0;
	$last_price = $sumprice; // ������
	$groupdiscount_Percent = round ( 100 - ( 100 * ( $sumprice / $basketItems['sumprice'] ) ) ); //ȸ���׷�(�߰�)���� ���� %
	$taxfree_groupdiscount = round($basketItems['tax_free']*($groupdiscount_Percent/100)); // �鼼 ȸ���׷�(�߰�)����
	$tax_free = round(($basketItems['tax_free'] - $taxfree_groupdiscount)/100)*100; // �鼼��
	$SupplyAmtTmp = $sumprice - $tax_free;
	if( $SupplyAmtTmp > 0 ) {
		$vat = round( $SupplyAmtTmp / 11 ); // ���� ����
		$SupplyAmt = $SupplyAmtTmp - $vat; // ����������(������ ������ �ݾ�)
	}

	// ��ü �鼼 ��ǰ�� ��� ���� �װ� �鼼��ǰ���� �ٸ��� ����ȭ
	if( $SupplyAmt == 0 AND $vat == 0 AND $last_price != $tax_free ){
		$tax_free = $last_price;
	}

	/*
	// ��ٱ��� ����Ű ����
	$oldtempkey=$_ShopInfo->getTempkey();
	$_ShopInfo->setTempkey($_data->ETCTYPE["BASKETTIME"]);
	$_ShopInfo->setGifttempkey($oldtempkey);
	$_ShopInfo->setOldtempkey($oldtempkey);
	$_ShopInfo->setOkpayment("");
	$_ShopInfo->Save();
	*/

	/*
	echo "�Ѱ����� : ".$last_price.'<br>';
	echo "������ : ".$SupplyAmt.'<br>';
	echo "����VAT : ".$vat.'<br>';
	echo "�鼼�� : ".$tax_free.'<br>';

	exit;
	*/
	
	// ��õ ���� ó��
	if(!_empty($_REQUEST['recomcode'])){
		if(false !== $res = mysql_query("select * from recommand_request where recomcode='".$_REQUEST['recomcode']."' limit 1",get_db_conn())){
			if(mysql_num_rows($res)){
				$reqinfo = mysql_fetch_assoc($res);
				if(_empty($reqinfo['ordercode']) || substr($reqinfo['ordercode'],0,1) != 'R'){
					$sql = "update recommand_request set ordercode='R".$ordercode."' where  recomcode='".$_REQUEST['recomcode']."'";
					@mysql_query($sql,get_db_conn());
				}
			}
		}
	}

	if($paymethod!="B") {

		// ������ �Ա��� �ƴ� ��� ���� ���� ����
		include($Dir.FrontDir."paylist.php");
		exit;

	}


// ������ �Ա��� ���� �ٷ� �ֹ� ó�� �Ϸ�
include($Dir.FrontDir."payresult.php");

?>