<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/shopdata.php");
include_once($Dir."lib/ext/mysql.util.func.php");
include_once($Dir."lib/admin_more.php");

INCLUDE ("access.php");

// ��õ�������ݼ�������
$recom_ok=$_data->recom_ok;
$recom_memreserve=$_data->recom_memreserve;
$recom_memreserve_type=$_data->recom_memreserve_type;
$recom_addreserve=$_data->recom_addreserve;
$arRecomType = explode("",$recom_memreserve_type);
$sns_ok=$_data->sns_ok;
$sns_reserve_type=$_data->sns_reserve_type;
$sns_recomreserve=$_data->sns_recomreserve;
$sns_memreserve=$_data->sns_memreserve;
$arSnsType = explode("",$sns_reserve_type);

function getDeligbn($ordercode,$deli_gbn) {
	//N:��ó��, X:��ۿ�û, S:�߼��غ�, Y:�߼ۿϷ�, C:�ֹ����, R:�ݼ�, D:��ҿ�û, E:ȯ�Ҵ��[��������� ��츸]
	$sql = "SELECT deli_gbn FROM tblorderproduct WHERE ordercode='".$ordercode."' AND NOT (productcode LIKE 'COU%' OR productcode LIKE '999999%') ";
	$sql.= "GROUP BY deli_gbn ";
	$result=mysql_query($sql,get_db_conn());
	$arrdeli=array();
	while($row=mysql_fetch_object($result)) {
		$arrdeli[$row->deli_gbn]=true;
	}
	mysql_free_result($result);

	$res="";
	if($deli_gbn=="N" && count($arrdeli)>0) {
		$res="N";
	} else if($deli_gbn=="S" && count($arrdeli)>0) {
		if($arrdeli["N"]==true) $res="N";
		else $res="S";
	}

	if(preg_match("/^(N|S)$/",$res)) {
		//��ó��, �߼��غ� ������ ������ü������ ���� ����� �ٷ� ����
		$sql = "UPDATE tblorderinfo SET deli_gbn='".$res."' WHERE ordercode='".$ordercode."' ";
		mysql_query($sql,get_db_conn());
	}
	return $res;
}

$ordercode=$_REQUEST["ordercode"];
$type=$_POST["type"];
$mode=$_POST["mode"];
$hidedisplay=$_POST["hidedisplay"];

$order_msg=$_POST["order_msg"];

$rescode=$_POST["rescode"];
$pay_admin_proc=$_POST["pay_admin_proc"];

if($type=="sort") {
	$sort=$_POST["sort"];
}
$totalRecom=$_POST["totalRecom"];
$sell_memid=$_POST["sell_memid"];
$sell_memid_reserve=$_POST["sell_memid_reserve"];
$arSell_id =array();
$arSell_rsv =array();
if($sell_memid){
	$arSell_id = explode("||", substr($sell_memid,0,-2));
	$arSell_rsv = explode("||", substr($sell_memid_reserve,0,-2));
}

if($ordercode==NULL) {
	echo "<script>alert('�߸��� �����Դϴ�.');window.close();</script>";
	exit;
}

$sql="SELECT * FROM tblorderinfo WHERE ordercode='".$ordercode."'";
$result=mysql_query($sql,get_db_conn());
$_ord=mysql_fetch_object($result);
mysql_free_result($result);

if(!$_ord) {
	echo "<script>alert(\"�ش� �ֹ������� �������� �ʽ��ϴ�.\");window.close();</script>";
	exit;
}
unset($isupdate);

//��ǰ���϶�
if($_ord->gift =='1' || $_ord->gift =='2') {
	echo "<script>window.location.href='order_detail2.php?ordercode={$ordercode}';</script>";
	exit;
}

$pgid_info="";
$pg_type="";
switch (substr($_ord->paymethod,0,1)) {
	case "B":
		break;
	case "V":
		$pgid_info=GetEscrowType($_shopdata->trans_id);
		$pg_type=$pgid_info["PG"];
		break;
	case "O":
		$pgid_info=GetEscrowType($_shopdata->virtual_id);
		$pg_type=$pgid_info["PG"];
		break;
	case "Q":
		$pgid_info=GetEscrowType($_shopdata->escrow_id);
		$pg_type=$pgid_info["PG"];
		break;
	case "C":
		$pgid_info=GetEscrowType($_shopdata->card_id);
		$pg_type=$pgid_info["PG"];
		break;
	case "P":
		$pgid_info=GetEscrowType($_shopdata->card_id);
		$pg_type=$pgid_info["PG"];
		break;
	case "M":
		$pgid_info=GetEscrowType($_shopdata->mobile_id);
		$pg_type=$pgid_info["PG"];
		break;
}
$pg_type=trim($pg_type);

$tax_type=$_shopdata->tax_type;

//ī�����/���,  �ڵ������� ��� ó�� (������������ ȣ��)
if (strlen($rescode)>0 && strlen($ordercode)>0) {
	$confirm_data=SendSocketPost(getenv("HTTP_HOST"),str_replace(getenv("HTTP_HOST"),"",$_ShopInfo->getShopurl())."paygate/pgconfirm.php","ordercode=".$ordercode);
	if (strlen($rescode)==1 && $rescode==$confirm_data) {
		$sql = "UPDATE tblorderinfo SET pay_admin_proc='".$rescode."' ";
		$sql.= "WHERE ordercode='".$ordercode."' ";
		mysql_query($sql,get_db_conn());
		$isupdate=true;
	}
	if (strlen($rescode)==1 && $rescode==$confirm_data && $rescode=="C" && substr($_ord->paymethod,0,1)=="P") {
		$sql = "UPDATE tblorderinfo SET deli_gbn='C' ";
		$sql.= "WHERE ordercode='".$ordercode."' AND MID(paymethod,1,1)='P'";
		if(mysql_query($sql,get_db_conn())) {
			$sql = "UPDATE tblorderproduct SET deli_gbn='C' ";
			$sql.= "WHERE ordercode='".$ordercode."' ";
			$sql.= "AND NOT (productcode LIKE '999%' OR productcode LIKE 'COU%') ";
			mysql_query($sql,get_db_conn());
		}
		$isupdate=true;
	}
}

//�������Ա�Ȯ��
if($type=="bank" && strlen($ordercode)>0) {
	if($_ord->paymethod=="B" && $tax_type=="Y") {
		$sql = "SELECT COUNT(*) as cnt FROM tbltaxsavelist WHERE ordercode='".$ordercode."' AND type='N' ";
		$result=mysql_query($sql,get_db_conn());
		$row=mysql_fetch_object($result);
		mysql_free_result($result);
		if($row->cnt>0) {
			$flag="Y";
			include($Dir."lib/taxsave.inc.php");
		}
	}

	mysql_query("UPDATE tblorderinfo SET bank_date='".date("YmdHis")."' WHERE ordercode='".$ordercode."' ",get_db_conn());
	$isupdate=true;

	// ��Ż �Ա�Ȯ��
	rentProdSchdCHG($ordercode, "BR", "BO");

	if(strlen($_ord->sender_email)>0) {
		//SendBankMail($_shopdata->shopname, $shopurl, $_shopdata->design_mail, $_shopdata->info_email, $_ord->sender_email, $ordercode);
	}

	$sql="SELECT * FROM tblsmsinfo ";//WHERE mem_bankok='Y'  mem_present
	$result=mysql_query($sql,get_db_conn());
	if($rowsms=mysql_fetch_object($result)) {
		$sms_id=$rowsms->id;
		$sms_authkey=$rowsms->authkey;
		$mem_bankok=$rowsms->mem_bankok;
		$msg_mem_bankok=$rowsms->msg_mem_bankok;
		$fromtel=$rowsms->return_tel;
		$mem_present=$rowsms->mem_present;
		$msg_mem_present=$rowsms->msg_mem_present;
		$use_mms =($rowsms->use_mms=='Y')? $rowsms->use_mms:"";
	}
	mysql_free_result($result);
	if($mem_bankok == "Y"){
		$bankprice=$_ord->price;
		$bankname=$_ord->sender_name;

		if(strlen($msg_mem_bankok)==0) $msg_mem_bankok="[".strip_tags($_shopdata->shopname)."] [DATE]�� �ֹ��� �Ա�Ȯ�� �Ǿ����ϴ�. ���� �߼��� �帮�ڽ��ϴ�.";
		$patten=array("(\[DATE\])","(\[NAME\])","(\[PRICE\])");
		$replace=array(substr($ordercode,0,4)."/".substr($ordercode,4,2)."/".substr($ordercode,6,2),$bankname,$bankprice);

		$msg_mem_bankok=preg_replace($patten,$replace,$msg_mem_bankok);
		$msg_mem_bankok=addslashes($msg_mem_bankok);


		$date=0;
		$etcmsg="�Ա�Ȯ�θ޼���(ȸ��)";
		$temp=SendSMS($sms_id, $sms_authkey, $_ord->sender_tel, "", $fromtel, $date, $msg_mem_bankok, $etcmsg);
	}


	//�����ϱ� ���Ϻ�����
	if($_ord->order_type == "p"){
		$cnt = 1;
		while($cnt > 0){
			$tmpcode = rand(10000,999999);
			$sql = "SELECT count(1) cnt FROM tblpresentcode WHERE code='".$tmpcode."'";
			$result = mysql_query($sql,get_db_conn());
			if($row = mysql_fetch_object($result)) {
				$cnt = (int)$row->cnt;
			}
			mysql_free_result($result);
		}
		$sql = "INSERT tblpresentcode SET code = '".$tmpcode."', ordercode = '".$ordercode."'";
		$insert=mysql_query($sql,get_db_conn());
		if (mysql_errno()==0) {
			$receiver_name = $_ord->receiver_name;
			$receiver_email=$_ord->receiver_email;
			$receiver_tel1=$_ord->receiver_tel1;
			$receiver_message=$_ord->receiver_message;
			if(strlen($receiver_email)>0 && strlen($receiver_message)>0){
				SendPresentMail($_shopdata->shopname, $shopurl, $_shopdata->design_mail, $receiver_message, $_ord->sender_email, $_ord->sender_name, $receiver_email, $receiver_name, $tmpcode);
			}
			if($mem_present == "Y"){
				if(strlen($msg_mem_present)==0) $msg_mem_present="[".strip_tags($_shopdata->shopname)."] [URL] [NAME]���� �����ϼ̽��ϴ�.";
				$patten=array("(\[URL\])","(\[NAME\])");
				$replace=array("http://".$_ShopInfo->getShopurl()."?gft_cd=".$tmpcode,$sender_name);
				$msg_mem_present=preg_replace($patten,$replace,$msg_mem_present);
				$msg_mem_present=addslashes($msg_mem_present);

				$date=0;
				$etcmsg="�����ϱ�޼���(ȸ��)";
				$temp=SendSMS2($sms_id, $sms_authkey, $receiver_tel1, "", $fromtel, $date, $msg_mem_present, $etcmsg, $use_mms);
			}
		}
	}

//�������Ա� ���(ȯ��ó��)
} else if($type=="bankcancel" && strlen($ordercode)>0) {
	if($_ord->deli_gbn=="C" && $_ord->paymethod=="B") {
		$sql = "UPDATE tblorderinfo SET bank_date='".substr($_ord->bank_date,0,8)."X' ";
		$sql.= "WHERE ordercode='".$ordercode."' ";
		mysql_query($sql,get_db_conn());
		$isupdate=true;
	}

//�Ϲ� ������� ���(ȯ��ó��)
} else if($type=="virtualcancel" && strlen($ordercode)>0) {
	if($_ord->deli_gbn=="C" && preg_match("/^(O){1}/", $_ord->paymethod)) {
		$sql = "UPDATE tblorderinfo SET pay_admin_proc='C', bank_date='".substr($_ord->bank_date,0,8)."X' ";
		$sql.= "WHERE ordercode='".$ordercode."' ";
		mysql_query($sql,get_db_conn());
		$isupdate=true;
	}

//�ǽð�������ü ���(ȯ��ó��)
} else if($type=="transcancel" && strlen($ordercode)>0) {
	if($_ord->deli_gbn=="C" && preg_match("/^(V){1}/", $_ord->paymethod)) {
		$sql = "UPDATE tblorderinfo SET pay_admin_proc='C', bank_date='".substr($_ord->bank_date,0,8)."X' ";
		$sql.= "WHERE ordercode='".$ordercode."' ";
		mysql_query($sql,get_db_conn());
		$isupdate=true;
/*
		echo "
			<html>
				<body onload=\"niceform.submit();\">
					<form name=niceform method=post action=\"".$Dir."paygate/E/cancel.php\">
						<input type=hidden name=ordercode value=\"".$_ord->ordercode."\">
						<input type=hidden name=TID value=\"".$_ord->pay_auth_no."\">
						<input type=hidden name=CancelAmt value=\"".$_ord->price."\">
						<input type=hidden name=CancelMsg value=\"������ ���\">
						<input type=hidden name=PartialCancelCode value=\"0\">
					</form>
				</body>
			</html>
		";
		exit;*/
	}

//����غ� ����
} else if($type=="readydeli" && strlen($ordercode)>0) {
	if($_ord->deli_gbn=="N") {
		$sql = "UPDATE tblorderinfo SET deli_gbn='S' WHERE ordercode='".$ordercode."' ";
		if(mysql_query($sql,get_db_conn())) {
			$sql = "UPDATE tblorderproduct SET deli_gbn='S' WHERE ordercode='".$ordercode."' ";
			$sql.= "AND NOT (productcode LIKE '999%' OR productcode LIKE 'COU%') ";
			$sql.= "AND deli_gbn='N' ";
			mysql_query($sql,get_db_conn());
		}
		// ��� ���¿� ���� ��Ż ��ǰ ó��
		$sql = "update rent_schedule s left join tblorderproduct op on op.ordercode=s.ordercode and op.basketidx=s.basketidx set s.status= if(s.start <= now(),'BI','BO') where op.ordercode='".$ordercode."' and op.deli_gbn in ('Y','S') and s.status ='BO'";
		@mysql_query($sql,get_db_conn());
	}
	// ��� ���¿� ���� ��Ż ��ǰ ó��
	$sql = "update rent_schedule s left join tblorderproduct op on op.ordercode=s.ordercode and op.basketidx=s.basketidx set s.status= if(s.start <= now(),'BI','BO') where op.ordercode='".$ordercode."' and op.deli_gbn in ('Y','S') and s.status ='BO'";	
	@mysql_query($sql,get_db_conn());
	echo "<script>if(opener) {opener.history.go(0);} window.close(); </script>";
	exit;

//�߼ۿϷ� ����
} else if($type=="delivery" && strlen($ordercode)>0) {
	$delimailok=$_POST["delimailtype"];	//�߼ۿϷῡ ���� ����/SMS�߼� ���� (Y:�߼�, N:�߼۾���)
	$in_reserve=$_POST["in_reserve"];

	if(preg_match("/^(N|X|S)$/",$_ord->deli_gbn)) {

		$deli_com=$_POST["deli_com"];
		$deli_num=$_POST["deli_num"];
		$deli_name=$_POST["deli_name"];

		$patterns = array("( )","(_)","(-)");
		$replace = array("","","");
		$deli_num = preg_replace($patterns,$replace,$deli_num);

		###����ũ�� ������ ������� ���� - ����ũ�� ������ ��쿡��.....

		if(strlen($deli_name)==0) {
			$deli_name="�ڰ����";
		}
		if(preg_match("/^(Q|P){1}/", $_ord->paymethod)) {

			if($pg_type=="A") {	//KCP
				$query="sitecd=".$pgid_info["ID"]."&sitekey=".$pgid_info["KEY"]."&ordercode=".$ordercode."&deli_num=".$deli_num."&deli_name=".urlencode($deli_name);

				$delivery_data=SendSocketPost(getenv("HTTP_HOST"),str_replace(getenv("HTTP_HOST"),"",$_ShopInfo->getShopurl())."paygate/".substr($_ord->paymethod,1,1)."/delivery.php",$query);

				$delivery_data=substr($delivery_data,strpos($delivery_data,"RESULT=")+7);
				if (substr($delivery_data,0,2)!="OK") {
					$tempdata=explode("|",$delivery_data);
					$errmsg="��������� ����ũ�� ������ �������� ���߽��ϴ�.\\n\\n����� �ٽ� �����Ͻñ� �ٶ��ϴ�.";
					if(strlen($tempdata[1])>0) $errmsg=$tempdata[1];
					echo "<script> alert('".$errmsg."');history.go(-1);</script>";
					exit;
				} else {
					$tempdata=explode("|",$delivery_data);
					if(strlen($tempdata[1])>0) $errmsg=$tempdata[1];
					if(strlen($errmsg)>0) {
						echo "<script> alert('".$errmsg."');</script>";
					}
				}
			} else if($pg_type=="B") {	//LG������
				$delicom_code="";
				if(strlen($deli_com)>0) {
					$sql = "SELECT dacom_code FROM tbldelicompany WHERE code='".$deli_com."' ";
					$result=mysql_query($sql,get_db_conn());
					if($row=mysql_fetch_object($result)) {
						$delicom_code=$row->dacom_code;
					}
					mysql_free_result($result);
				}
				$query="mid=".$pgid_info["ID"]."&mertkey=".$pgid_info["KEY"]."&ordercode=".$ordercode."&deli_num=".$deli_num."&delicom_code=".$delicom_code;

				$delivery_data=SendSocketPost(getenv("HTTP_HOST"),str_replace(getenv("HTTP_HOST"),"",$_ShopInfo->getShopurl())."paygate/".substr($_ord->paymethod,1,1)."/delivery.php",$query);

				$delivery_data=substr($delivery_data,strpos($delivery_data,"RESULT=")+7);
				if (substr($delivery_data,0,2)!="OK") {
					$tempdata=explode("|",$delivery_data);
					$errmsg="��������� ����ũ�� ������ �������� ���߽��ϴ�.\\n\\n����� �ٽ� �����Ͻñ� �ٶ��ϴ�.";
					if(strlen($tempdata[1])>0) $errmsg=$tempdata[1];
					echo "<script> alert('".$errmsg."');history.go(-1);</script>";
					exit;
				} else {
					$tempdata=explode("|",$delivery_data);
					if(strlen($tempdata[1])>0) $errmsg=$tempdata[1];
					if(strlen($errmsg)>0) {
						echo "<script> alert('".$errmsg."');</script>";
					}
				}
			} else if($pg_type=="C") {	//�ô�����Ʈ
				$query="storeid=".$pgid_info["ID"]."&ordercode=".$ordercode;

				$delivery_data=SendSocketPost(getenv("HTTP_HOST"),str_replace(getenv("HTTP_HOST"),"",$_ShopInfo->getShopurl())."paygate/".substr($_ord->paymethod,1,1)."/delivery.php",$query);

				$delivery_data=substr($delivery_data,strpos($delivery_data,"RESULT=")+7);
				if (substr($delivery_data,0,2)!="OK") {
					$tempdata=explode("|",$delivery_data);
					$errmsg="��������� ����ũ�� ������ �������� ���߽��ϴ�.\\n\\n����� �ٽ� �����Ͻñ� �ٶ��ϴ�.";
					if(strlen($tempdata[1])>0) $errmsg=$tempdata[1];
					echo "<script> alert('".$errmsg."');history.go(-1);</script>";
					exit;
				} else {
					$tempdata=explode("|",$delivery_data);
					if(strlen($tempdata[1])>0) $errmsg=$tempdata[1];
					if(strlen($errmsg)>0) {
						echo "<script> alert('".$errmsg."');</script>";
					}
				}
			} else if($pg_type=="D") {	//INICIS
				$delicom_code="";
				if(strlen($deli_com)>0) {
					$sql = "SELECT inicis_code FROM tbldelicompany WHERE code='".$deli_com."' ";
					$result=mysql_query($sql,get_db_conn());
					if($row=mysql_fetch_object($result)) {
						$delicom_code=$row->inicis_code;
					}
					mysql_free_result($result);
				}
				$query="sitecd=".$pgid_info["EID"]."&ordercode=".$ordercode."&deli_num=".$deli_num."&delicom_code=".$delicom_code."&deli_name=".urlencode($deli_name);

				$delivery_data=SendSocketPost(getenv("HTTP_HOST"),str_replace(getenv("HTTP_HOST"),"",$_ShopInfo->getShopurl())."paygate/".substr($_ord->paymethod,1,1)."/delivery.php",$query);

				$delivery_data=substr($delivery_data,strpos($delivery_data,"RESULT=")+7);
				if (substr($delivery_data,0,2)!="OK") {
					$tempdata=explode("|",$delivery_data);
					$errmsg="��������� ����ũ�� ������ �������� ���߽��ϴ�.\\n\\n����� �ٽ� �����Ͻñ� �ٶ��ϴ�.";
					if(strlen($tempdata[1])>0) $errmsg=$tempdata[1];
					echo "<script> alert('".$errmsg."');history.go(-1);</script>";
					exit;
				} else {
					$tempdata=explode("|",$delivery_data);
					if(strlen($tempdata[1])>0) $errmsg=$tempdata[1];
					if(strlen($errmsg)>0) {
						echo "<script> alert('".$errmsg."');</script>";
					}
				}
			} else if($pg_type == "G"){ // �þ� ����ũ��

				$query="ordercode=".$ordercode."&deli_num=".$deli_num."&deli_code=".$deli_com;
				if(_empty($_POST['allatprocess'])){
				?>
				<form name="allatDeliveryForm" action="/paygate/G/delivery.php" method="post">
				<?
					foreach($_POST as $key => $val){
				?>
					<input type="hidden" name="<?=$key?>" value="<?=$val?>"/>
				<?
					}
				?>
				</form>
				<script>
					document.allatDeliveryForm.submit();
				</script>
				<?
				exit;
				}
			}
		}

		$sql = "UPDATE tblorderinfo SET deli_gbn='Y', deli_date='".date("YmdHis")."' ";
		$sql.= "WHERE ordercode='".$ordercode."' ";
		if(mysql_query($sql,get_db_conn())) {
			$sql = "UPDATE tblorderproduct SET";
			if( strlen($deli_com) > 0 ) $sql.= " deli_com='".$deli_com."', ";
			if( strlen($deli_num) > 0 ) $sql.= " deli_num='".$deli_num."', ";
			$sql.= " deli_gbn='Y',";
			$sql.= " deli_date='".date("YmdHis")."' ";
			$sql.= "WHERE ordercode='".$ordercode."' ";
			$sql.= "AND NOT (productcode LIKE '999%' OR productcode LIKE 'COU%') ";
			$sql.= "AND not status like 'R%' ";
			$sql.= "AND deli_gbn!='Y' ";
			mysql_query($sql,get_db_conn());
			
			
			// ��� ���¿� ���� ��Ż ��ǰ ó��
			$sql = "update rent_schedule s left join tblorderproduct op on op.ordercode=s.ordercode and op.basketidx=s.basketidx set s.status= if(s.start <= now(),'BI','BO') where op.ordercode='".$ordercode."' and op.deli_gbn in ('Y','S') and s.status ='BO'";
			@mysql_query($sql,get_db_conn());
			/********* ���� ������ ���� jdy **********/
			insertOrderAdjustDetail($ordercode);
			/********* ���� ������ ���� jdy **********/
		}
		$isupdate=true;

		if($delimailok=="Y") {	//�߼ۿϷ� ������ �߼��� ���
			$delimailtype="N";
			SendDeliMail($_shopdata->shopname, $shopurl, $_shopdata->design_mail, $_shopdata->info_email, $ordercode, $deli_com, $deli_num, $delimailtype);

			if(strlen($_ord->sender_tel)>0) {
				$sql ="SELECT * FROM tblsmsinfo WHERE (mem_delivery='Y' OR mem_delinum='Y') ";
				$result=mysql_query($sql,get_db_conn());
				if($rowsms=mysql_fetch_object($result)) {
					$sms_id=$rowsms->id;
					$sms_authkey=$rowsms->authkey;

					$deliprice=$_ord->price;
					$deliname=$_ord->sender_name;

					$msg_mem_delinum=$rowsms->msg_mem_delinum;
					if(strlen($msg_mem_delinum)==0) {
						$msg_mem_delinum="[".strip_tags($shopname)."] [DELICOM] �����ȣ : [DELINUM] ���� �߼�ó�� �Ǿ����ϴ�.";
					}
					$patten=array("(\[DATE\])","(\[DELICOM\])","(\[DELINUM\])","(\[NAME\])","(\[PRICE\])");
					$replace=array(substr($ordercode,0,4)."/".substr($ordercode,4,2)."/".substr($ordercode,6,2),$deli_name,$deli_num,$deliname,$deliprice);
					$msg_mem_delinum=preg_replace($patten,$replace,$msg_mem_delinum);
					$msg_mem_delinum=addslashes($msg_mem_delinum);

					$msg_mem_delivery=$rowsms->msg_mem_delivery;
					if(strlen($msg_mem_delivery)==0) {
						$msg_mem_delivery="[".strip_tags($shopname)."]���� [DATE]�� �ֹ��� ��ǰ�� �߼��� ��Ƚ��ϴ�. �����մϴ�.";
					}
					$patten=array("(\[DATE\])","(\[NAME\])","(\[PRICE\])");
					$replace=array(substr($ordercode,0,4)."/".substr($ordercode,4,2)."/".substr($ordercode,6,2),$deliname,$deliprice);
					$msg_mem_delivery=preg_replace($patten,$replace,$msg_mem_delivery);
					$msg_mem_delivery=addslashes($msg_mem_delivery);

					$fromtel=$rowsms->return_tel;
					$date=0;
					if($rowsms->mem_delinum=="Y" && strlen($deli_name)>0 && strlen($deli_num)>0) {	//����ȳ��޼���
						$etcmsg="����ȳ��޼���(ȸ��)";
						$temp=SendSMS($sms_id, $sms_authkey, $_ord->sender_tel, "", $fromtel, $date, $msg_mem_delinum, $etcmsg);
					}
					if($rowsms->mem_delivery=="Y") {	//��ǰ�߼۸޼���
						$etcmsg="��ǰ�߼۸޼���(ȸ��)";
						$temp=SendSMS($sms_id, $sms_authkey, $_ord->sender_tel, "", $fromtel, $date, $msg_mem_delivery, $etcmsg);
					}
				}
				mysql_free_result($result);
			}
		}

		// ��ǰ���� �Ϸ�� ��õ�� ������ ó��
		recommandMemReserve($_ord->id);
		
		recommandReservePay($ordercode); // Ÿȸ�� ��õ�� ���� ������ �Ǻ� �� ����

		// ��ǰ ȫ�� URL ��ǰȫ�� ������ ó��
		snsPromoteAccessOrderOK( $ordercode );



		// �ֹ� ������
		if($in_reserve>0) {
			$sql = "SELECT reserve FROM tblmember WHERE id='".$_ord->id."' ";
			$result=mysql_query($sql,get_db_conn());
			if($row=mysql_fetch_object($result)) {
				$reservemoney=$in_reserve + $row->reserve;
				$sql = "UPDATE tblmember SET reserve = ".abs($reservemoney)." ";
				$sql.= "WHERE id='".$_ord->id."' ";
				mysql_query($sql,get_db_conn());

				$sql = "INSERT tblreserve SET ";
				$sql.= "id			= '".$_ord->id."', ";
				$sql.= "reserve		= '".$in_reserve."', ";
				$sql.= "reserve_yn	= 'Y', ";
				$sql.= "content		= '��ǰ ���԰ǿ� ���� ������ ����', ";
				$sql.= "orderdata	= '".$ordercode."' = '".$_ord->price."', ";
				$sql.= "date		= '".date("YmdHis")."' ";
				mysql_query($sql,get_db_conn());
				$in_reserve=0;
			}
			mysql_free_result($result);
		}

		if($pg_type == "G"){
			echo '<script>window.close();</script>';
		}else{
			echo "<script>if(opener) {opener.history.go(0);} window.close(); </script>";
		}
		exit;
	} else if(!preg_match("/^(N|X|S)$/",$_ord->deli_gbn)) {
		echo "<script>alert(\"�̹� ��ҵǰų� �߼۵� ��ǰ�Դϴ�. �ٽ� Ȯ���Ͻñ� �ٶ��ϴ�.\");</script>";
	}

#�ݼ�ó��
} else if($type=="redelivery" && strlen($ordercode)>0) {
	$sql = "UPDATE tblorderinfo SET deli_gbn='R' WHERE ordercode='".$ordercode."' ";
	if(mysql_query($sql,get_db_conn())) {
		$sql = "UPDATE tblorderproduct SET deli_gbn='R' ";
		$sql.= "WHERE ordercode='".$ordercode."' ";
		$sql.= "AND NOT (productcode LIKE 'COU%' OR productcode LIKE '999999%') ";
		mysql_query($sql,get_db_conn());
		
	}

	/********* ���� ������ ���� jdy **********/
	redeliveryOrderAdjustDetail($ordercode);
	/********* ���� ������ ���� jdy **********/

	echo "<script>if(opener) {opener.history.go(0);} window.close(); </script>";
	exit;

//����� �ּ� ������Ʈ
} else if($type=="addressupdate" && strlen($ordercode)>0) {
	$post1=$_POST["post1"];
	//$post2=$_POST["post2"];
	$address1=$_POST["address1"];
	$receiver_addr="�����ȣ : ".$post1."\\n�ּ� : ".$address1;
	$sql = "UPDATE tblorderinfo SET receiver_addr='".$receiver_addr."' ";
	$sql.= "WHERE ordercode='".$ordercode."' ";
	mysql_query($sql,get_db_conn());
	$isupdate=true;

//�������� ������Ʈ
} else if($type=="deliupdate" && strlen($ordercode)>0) {
	$delimailtype=$_POST["delimailtype"];	//�������� ������Ʈ�� ���� ����/SMS�߼� ���� (Y:�߼�, N:�߼۾���)
	$deli_com=$_POST["deli_com"];
	$deli_num=$_POST["deli_num"];

	$patterns = array("( )","(_)","(-)");
	$replace = array("","","");
	$deli_num = preg_replace($patterns,$replace,$deli_num);

	/********
	����ũ�� ������ �������� ���� - ����ũ�� ������ ��쿡��.....
	********/
	if(preg_match("/^(Q|P){1}/", $_ord->paymethod)) {
		//KCP|�ô�����Ʈ�� �������� ������Ʈ ����� ����. (���� �ٸ� PG�� �߰��� �۾�.....)
	}

	$sql = "UPDATE tblorderproduct SET deli_num='".$deli_num."', deli_com='".$deli_com."' ";
	$sql.= "WHERE ordercode='".$ordercode."' ";
	$sql.= "AND NOT (productcode LIKE 'COU%' OR productcode LIKE '999999%') ";
	mysql_query($sql,get_db_conn());

	if(strlen($_ord->sender_email)>0 && $delimailtype=="Y") {
		SendDeliMail($_shopdata->shopname, $shopurl, $_shopdata->design_mail, $_shopdata->info_email, $ordercode, $deli_com, $deli_num, $delimailtype);
	}
	if(strlen($_ord->sender_tel)>0 && $delimailtype=="Y") {
		if(strlen($deli_com)>0 && strlen($deli_name)==0) {
			$sql = "SELECT company_name FROM tbldelicompany WHERE code='".$deli_com."' ";
			$result=mysql_query($sql,get_db_conn());
			if($row=mysql_fetch_object($result)) {
				$deli_name=$row->company_name;
			}
			mysql_free_result($result);
		}
		if(strlen($deli_name)==0) {
			$deli_name="�ڰ����";
		}
		$sql="SELECT * FROM tblsmsinfo WHERE mem_delinum='Y' ";
		$result=mysql_query($sql,get_db_conn());
		if($rowsms=mysql_fetch_object($result)) {
			$sms_id=$rowsms->id;
			$sms_authkey=$rowsms->authkey;

			$deliprice=$_ord->price;
			$deliname=$_ord->sender_name;
			$msg_mem_delinum=$rowsms->msg_mem_delinum;
			if(strlen($msg_mem_delinum)==0) {
				$msg_mem_delinum="[".strip_tags($shopname)."] [DELICOM] �����ȣ : [DELINUM] ���� �߼�ó�� �Ǿ����ϴ�.";
			}
			$patten=array("(\[DATE\])","(\[DELICOM\])","(\[DELINUM\])","(\[NAME\])","(\[PRICE\])");
			$replace=array(substr($ordercode,0,4)."/".substr($ordercode,4,2)."/".substr($ordercode,6,2),$deli_name,$deli_num,$deliname,$deliprice);
			$msg_mem_delinum=preg_replace($patten,$replace,$msg_mem_delinum);
			$msg_mem_delinum=addslashes($msg_mem_delinum);

			$fromtel=$rowsms->return_tel;
			$date=0;
			$etcmsg="����ȳ��޼���(ȸ��)";
			$temp=SendSMS($sms_id, $sms_authkey, $_ord->sender_tel, "", $fromtel, $date, $msg_mem_delinum, $etcmsg);
		}
		mysql_free_result($result);
	}

//KCP/�ô�����Ʈ/�̴Ͻý� - ��ǰ��� �� ��ҿ�û�� ���� ��� �켱 ���꺸�� ���·� ������. (�ݼ� �Ϸ� �� ���ó�� ����)
} else if($type=="okhold" && strlen($ordercode)>0 && ($pg_type=="A" || $pg_type=="C" || $pg_type=="D")) {
	if(preg_match("/^(Y|D)$/", $_ord->deli_gbn) && strlen($_ord->deli_date)==14) {
		if($pg_type=="A") {
			$query="sitecd=".$pgid_info["ID"]."&sitekey=".$pgid_info["KEY"]."&ordercode=".$ordercode;
			$hold_data=SendSocketPost(getenv("HTTP_HOST"),str_replace(getenv("HTTP_HOST"),"",$_ShopInfo->getShopurl())."paygate/".substr($_ord->paymethod,1,1)."/hold.php",$query);
			$hold_data=substr($hold_data,strpos($hold_data,"RESULT=")+7);
			if (substr($hold_data,0,2)!="OK") {
				$tempdata=explode("|",$hold_data);
				$errmsg="���꺸�� ������ ����ũ�� ������ �������� ���߽��ϴ�.\\n\\n����� �ٽ� �����Ͻñ� �ٶ��ϴ�.";
				if(strlen($tempdata[1])>0) $errmsg=$tempdata[1];
				echo "<script> alert('".$errmsg."');history.go(-1);</script>";
				exit;
			} else {
				$tempdata=explode("|",$hold_data);
				if(strlen($tempdata[1])>0) $errmsg=$tempdata[1];
				if(strlen($errmsg)>0) {
					echo "<script> alert('".$errmsg."');</script>";
				}
			}

			$sql = "UPDATE tblorderinfo SET ";
			$sql.= "deli_gbn		= 'H' WHERE ordercode='".$ordercode."' ";
			if(mysql_query($sql,get_db_conn())) {
				$sql = "UPDATE tblorderproduct SET deli_gbn='H' ";
				$sql.= "WHERE ordercode='".$ordercode."' ";
				$sql.= "AND NOT (productcode LIKE 'COU%' OR productcode LIKE '999999%') ";
				mysql_query($sql,get_db_conn());
			}
		} else if($pg_type=="C") {
			$query="sitecd=".$pgid_info["ID"]."&ordercode=".$ordercode;
			$hold_data=SendSocketPost(getenv("HTTP_HOST"),str_replace(getenv("HTTP_HOST"),"",$_ShopInfo->getShopurl())."paygate/".substr($_ord->paymethod,1,1)."/hold.php",$query);
			$hold_data=substr($hold_data,strpos($hold_data,"RESULT=")+7);
			if (substr($hold_data,0,2)!="OK") {
				$tempdata=explode("|",$hold_data);
				$errmsg="���꺸�� ó���� ���� �Ϸ����� ���߽��ϴ�.\\n\\n����� �ٽ� �����Ͻñ� �ٶ��ϴ�.";
				if(strlen($tempdata[1])>0) $errmsg=$tempdata[1];
				echo "<script> alert('".$errmsg."');history.go(-1);</script>";
				exit;
			} else {
				$tempdata=explode("|",$hold_data);
				if(strlen($tempdata[1])>0) $errmsg=$tempdata[1];
				if(strlen($errmsg)>0) {
					echo "<script> alert('".$errmsg."');</script>";
				}
			}

			$sql = "UPDATE tblorderinfo SET ";
			$sql.= "deli_gbn		= 'H' WHERE ordercode='".$ordercode."' ";
			if(mysql_query($sql,get_db_conn())) {
				$sql = "UPDATE tblorderproduct SET deli_gbn='H' ";
				$sql.= "WHERE ordercode='".$ordercode."' ";
				$sql.= "AND NOT (productcode LIKE 'COU%' OR productcode LIKE '999999%') ";
				mysql_query($sql,get_db_conn());
			}
		} else if($pg_type=="D") {
			$query="sitecd=".$pgid_info["EID"]."&ordercode=".$ordercode."&curgetid=".$_ShopInfo->getId();
			$hold_data=SendSocketPost(getenv("HTTP_HOST"),str_replace(getenv("HTTP_HOST"),"",$_ShopInfo->getShopurl())."paygate/".substr($_ord->paymethod,1,1)."/hold.php",$query);
			$hold_data=substr($hold_data,strpos($hold_data,"RESULT=")+7);
			if (substr($hold_data,0,2)!="OK") {
				$tempdata=explode("|",$hold_data);
				$errmsg="���꺸�� ó���� ���� �Ϸ����� ���߽��ϴ�.\\n\\n����� �ٽ� �����Ͻñ� �ٶ��ϴ�.";
				if(strlen($tempdata[1])>0) $errmsg=$tempdata[1];
				echo "<script> alert('".$errmsg."');history.go(-1);</script>";
				exit;
			} else {
				$tempdata=explode("|",$hold_data);
				if(strlen($tempdata[1])>0) $errmsg=$tempdata[1];
				if(strlen($errmsg)>0) {
					echo "<script> alert('".$errmsg."');</script>";
				}
			}

			$sql = "UPDATE tblorderinfo SET ";
			$sql.= "deli_gbn		= 'H' WHERE ordercode='".$ordercode."' ";
			if(mysql_query($sql,get_db_conn())) {
				$sql = "UPDATE tblorderproduct SET deli_gbn='H' ";
				$sql.= "WHERE ordercode='".$ordercode."' ";
				$sql.= "AND NOT (productcode LIKE 'COU%' OR productcode LIKE '999999%') ";
				mysql_query($sql,get_db_conn());
			}
		}
		$isupdate=true;
	}

//��������, �ֹ����
} else if(($type=="recancel" || $type=="recoveryquan" || $type=="recoverycan") && strlen($ordercode)>0) {
	if($_ord->pay_admin_proc!="C" && $_ord->pay_flag=="0000" && preg_match("/^(C){1}/", $_ord->paymethod)) { // �Ϲ� ī�� �ֹ��� �ֹ�������� ī�带 ����ؾ���
		echo "<script>alert('���� ī����Ҹ� �ϼž� �մϴ�.');history.go(-1);</script>";
		exit;
	} else if($_ord->pay_admin_proc!="C" && $_ord->pay_flag=="0000" && preg_match("/^(M){1}/", $_ord->paymethod)) { // �ڵ��� �ֹ��� �ֹ�������� �ڵ��� ������ ����ؾ���
		echo "<script>alert('���� �ڵ������� ��Ҹ� �ϼž� �մϴ�.');history.go(-1);</script>";
		exit;
	}

	/************* ����ũ�� ���� ȯ��(�������) �Ǵ� ���(�ſ�ī��) ***************/
	if(preg_match("/^(Q|P){1}/", $_ord->paymethod)) {
		//Q(������� �Ÿź�ȣ)�� ��쿣 �켱 ȯ�Ҵ�� �� ȯ�ҵǸ� �ڵ� ���ó���ȴ�.

		if($pg_type=="A") {			#KCP
			$query="sitecd=".$pgid_info["ID"]."&sitekey=".$pgid_info["KEY"]."&ordercode=".$ordercode;
		} else if($pg_type=="B") {	#LG������
			$query="mid=".$pgid_info["ID"]."&mertkey=".$pgid_info["KEY"]."&ordercode=".$ordercode;
		} else if($pg_type=="C") {  #�ô�����Ʈ
			$query="storeid=".$pgid_info["ID"]."&ordercode=".$ordercode;
		} else if($pg_type=="D") {  #�̴Ͻý�
			$query="sitecd=".$pgid_info["EID"]."&ordercode=".$ordercode."&curgetid=".$_ShopInfo->getId();
		}

		$cancel_data=SendSocketPost(getenv("HTTP_HOST"),str_replace(getenv("HTTP_HOST"),"",$_ShopInfo->getShopurl())."paygate/".substr($_ord->paymethod,1,1)."/escrow_cancel.php",$query);

		$cancel_data=substr($cancel_data,strpos($cancel_data,"RESULT=")+7);
		if (substr($cancel_data,0,2)!="OK") {
			$tempdata=explode("|",$cancel_data);
			$errmsg="���ó���� ���� �Ϸ� ���� �� �߽��ϴ�.\\n\\n����� �ٽ� �����Ͻñ� �ٶ��ϴ�.";
			if(strlen($tempdata[1])>0) $errmsg=$tempdata[1];
			echo "<script> alert('".$errmsg."');history.go(-1);</script>";
			exit;
		} else {
			$tempdata=explode("|",$cancel_data);
			if(strlen($tempdata[1])>0) $errmsg=$tempdata[1];
			if(strlen($errmsg)>0) {
				echo "<script> alert('".$errmsg."');</script>";
			}
		}
	}

	if($_ord->del_gbn!="R"){ //R�� ��� ������ ������ ���
		if($type=="recoveryquan"){ //�������� ����
			$sql = "SELECT a.productcode,a.productname,a.opt1_name,a.opt2_name,a.quantity, ";
			$sql.= "b.option_quantity,b.option1,b.option2,b.rental,a.package_idx,a.assemble_idx,a.assemble_info FROM tblorderproduct a, tblproduct b ";
			$sql.= "WHERE a.productcode=b.productcode AND a.ordercode='".$ordercode."' ";
			//echo $sql;exit;
			$result=mysql_query($sql,get_db_conn());
			$message="";
			while ($row=mysql_fetch_object($result)) {
				$tmpoptq="";
				if(strlen($artmpoptq[$row->productcode])>0)
					$optq=$artmpoptq[$row->productcode];
				else
					$optq=$row->option_quantity;

				//if(strlen($optq)>51 && substr($row->opt1_name,0,5)!="[OPTG"){
				if(strlen($optq)>51){
					$tmpoptname1=explode(" : ",$row->opt1_name);
					$tmpoptname2=explode(" : ",$row->opt2_name);
					$tmpoption1=explode(",",$row->option1);
					$tmpoption2=explode(",",$row->option2);
					$cnt=1;
					$maxoptq = count($tmpoption1);
					while ($tmpoption1[$cnt]!=$tmpoptname1[1] && $cnt<$maxoptq) {
						$cnt++;
					}
					$opt_no1=$cnt;
					$cnt=1;
					$maxoptq2 = count($tmpoption2);
					while ($tmpoption2[$cnt]!=$tmpoptname2[1] && $cnt<$maxoptq2) {
						$cnt++;
					}
					$opt_no2=$cnt;
					$optioncnt = explode(",",substr($optq,1));

					
					$selNum = ($opt_no2-2)*10+($opt_no1-3);

					//echo "<br>".$optioncnt[$selNum]."<br>";
					if($optioncnt[$selNum]!="") $optioncnt[$selNum]+=$row->quantity;
					for($j=0;$j<10;$j++){
						for($i=0;$i<10;$i++){
							$tmpoptq.=",".$optioncnt[$j*10+$i];
						}
					}
					if(strlen($tmpoptq)>0 && $tmpoptq.","!=$optq){
						$artmpoptq[$row->productcode]=$tmpoptq;
						$tmpoptq=",option_quantity='".$tmpoptq.",'";
					}else{
						$tmpoptq="";
						$message .="[".$row->productname." - ".$row->opt1_name.$row->opt2_name."]\\n";
					}
				}
				$sql = "UPDATE tblproduct SET quantity=quantity+".$row->quantity.$tmpoptq." ";
				$sql.= "WHERE productcode='".$row->productcode."'";
				//exit;
				mysql_query($sql,get_db_conn());

				if(str_replace("","",str_replace(":","",str_replace("=","",$row->assemble_info)))) {
					$assemble_infoall_exp = explode("=",$row->assemble_info);

					if($row->package_idx>0 && strlen(str_replace("","",str_replace(":","",$assemble_infoall_exp[0])))>0) {
						$package_info_exp = explode(":",$assemble_infoall_exp[0]);
						if(strlen($package_info_exp[0])>0) {
							$package_productcode_exp = explode("",$package_info_exp[0]);
							for($k=0; $k<count($package_productcode_exp); $k++) {
								$sql2 = "UPDATE tblproduct SET ";
								$sql2.= "quantity		= quantity+".$row->quantity." ";
								$sql2.= "WHERE productcode='".$package_productcode_exp[$k]."' ";
								mysql_query($sql2,get_db_conn());
							}
						}
					}

					if($row->assemble_idx>0 && strlen(str_replace("","",str_replace(":","",$assemble_infoall_exp[1])))>0) {
						$assemble_info_exp = explode(":",$assemble_infoall_exp[1]);
						if(strlen($assemble_info_exp[0])>0) {
							$assemble_productcode_exp = explode("",$assemble_info_exp[0]);
							for($k=0; $k<count($assemble_productcode_exp); $k++) {
								$sql2 = "UPDATE tblproduct SET ";
								$sql2.= "quantity		= quantity+".$row->quantity." ";
								$sql2.= "WHERE productcode='".$assemble_productcode_exp[$k]."' ";
								mysql_query($sql2,get_db_conn());
							}
						}
					}
				}
			}
			mysql_free_result($result);

			if(strlen($message)==0) $message="���������� ���������� �Ǿ����ϴ�. Ȯ�ιٶ��ϴ�.";
			else $message="�ɼǺ� ���������� �Ʒ��� �������� �����Ͽ����ϴ�.\\n\\n".$message."\\n���� ��ǰ�� ��� ���� �ɼǰ��� �ֹ����� �ɼ� ���� �ٸ��ϴ�.\\n���� ��ǰ���� ���� Ȯ���� �ɼǺ� ���� �����ϼ���\\n�⺻ ������ ������ �����Դϴ�. �ɼǺ� ������ �����ϼ���.";
			$canmess="<script>alert('".$message."');</script>";

			$log_content = "## ��ǰ���� ���� ## - �ֹ���ȣ : ".$ordercode;
			ShopManagerLog($_ShopInfo->getId(),$connect_ip,$log_content);
		} else {
			$canmess="<script>alert('�ش� �ֹ��� ����Ͽ����ϴ�.');</script>";

			$log_content = "## �ֹ���� ## - �ֹ���ȣ : ".$ordercode;
			ShopManagerLog($_ShopInfo->getId(),$connect_ip,$log_content);
		}
	}

	if(preg_match("/^(B|O|Q){1}/", $_ord->paymethod) && $tax_type=="Y") {	//���ݿ����� �ڵ� ����
		$sql = "SELECT COUNT(*) as cnt FROM tbltaxsavelist WHERE ordercode='".$ordercode."' AND type='Y' ";
		$result=mysql_query($sql,get_db_conn());
		$row=mysql_fetch_object($result);
		mysql_free_result($result);
		if($row->cnt>0) {
			$flag="C";
			include($Dir."lib/taxsave.inc.php");
		}
	}

	if (preg_match("/^(Q){1}/", $_ord->paymethod) && strlen($_ord->bank_date)==14 && ($pg_type!="C" && $pg_type!="D")) {
		$deliupdate =" deli_gbn='E' ";	//ȯ�Ҵ��
		$up_deli_gbn="E";
	} else if (preg_match("/^(P){1}/", $_ord->paymethod)) {
		$deliupdate = " deli_gbn='C', pay_admin_proc='C' ";
		$up_deli_gbn="C";
	} else {
		$deliupdate = " deli_gbn='C' ";
		$up_deli_gbn="C";
	}

	if($type=="recoveryquan") {
		if($_ord->del_gbn=="Y") $okdel="R";
		else $okdel="A";
	} else if($type=="recoverycan") $okdel=$_ord->del_gbn;

	$sql = "UPDATE tblorderinfo SET ".$deliupdate.", del_gbn='".$okdel."' ";
	$sql.= "WHERE ordercode='".$ordercode."' ";
	if(mysql_query($sql,get_db_conn())) {
		$sql = "UPDATE tblorderproduct SET deli_gbn='".$up_deli_gbn."' ";
		$sql.= "WHERE ordercode='".$ordercode."' ";
		$sql.= "AND NOT (productcode LIKE 'COU%' OR productcode LIKE '999999%') ";
		mysql_query($sql,get_db_conn());
	}
	$isupdate=true;
	echo $canmess;

//ȸ���� ����� ������ ����ó�� �� �ֹ��� ���ó��
} else if($type=="recoveryres" && strlen($ordercode)>0) {
	if($_ord->deli_gbn!="C" && $_ord->reserve>0 && strlen($_ord->id)>0) {
		if($_ord->deli_gbn!="C" && strlen($_ord->bank_date)>0 && preg_match("/^(Q){1}/", $_ord->paymethod)) {
			//������� ����ũ���� ���
			echo "<script>alert('������� ����ũ�� �Աݰ��� ��� �� ����� �������� ó���ϼž� �մϴ�.');history.go(-1);</script>";
			exit;
		}

		$sql = "UPDATE tblorderinfo SET deli_gbn='C' ";
		$sql.= "WHERE ordercode='".$ordercode."' ";
		if(mysql_query($sql,get_db_conn())) {
			$sql = "UPDATE tblorderproduct SET deli_gbn='C' ";
			$sql.= "WHERE ordercode='".$ordercode."' ";
			$sql.= "AND NOT (productcode LIKE 'COU%' OR productcode LIKE '999999%') ";
			mysql_query($sql,get_db_conn());
		}
		$isupdate=true;

		//������ ��ȸ
		$now_reserve = abs($_ord->reserve);

		//��ҳ��� ��ȸ
		$sql = "select sum(cancel_reserve) as cancel_reserve from part_cancel_reserve where ordercode='".$ordercode."'";
		$result=mysql_query($sql,get_db_conn());
		$row=mysql_fetch_object($result);

		$cancel_reserve = $row->cancel_reserve;
		mysql_free_result($result);

		$now_reserve = $now_reserve-$cancel_reserve;



		$sql = "UPDATE tblmember SET reserve=reserve+".$now_reserve." ";
		$sql.= "WHERE id='".$_ord->id."' ";
		mysql_query($sql,get_db_conn());

		$sql = "INSERT tblreserve SET ";
		$sql.= "id			= '".$_ord->id."', ";
		$sql.= "reserve		= '".$now_reserve."', ";
		$sql.= "reserve_yn	= 'Y', ";
		$sql.= "content		= '�ֹ� ��Ұǿ� ���� ������ ȯ��', ";
		$sql.= "orderdata	= '".$ordercode."=".$_ord->price."', ";
		$sql.= "date		= '".date("YmdHis")."' ";
		mysql_query($sql,get_db_conn());

		$log_content="## ȸ�� ������ ȯ�� ## - �ֹ���ȣ : ".$ordercode." - ������ ".$now_reserve;
		ShopManagerLog($_ShopInfo->getId(),$connect_ip,$log_content);
	}

//�ֹ���ҷ� ���� ȸ������ �����ߴ� ������ ���ó��
} else if($type=="recoveryrecan" && strlen($ordercode)>0) {
	$canreserve=$_POST["canreserve"];
	if(preg_match("/^(Y|D|H)$/",$_ord->deli_gbn) && strlen($_ord->deli_date)==14 && $canreserve>0 && strlen($_ord->id)>0) {
		$sql = "UPDATE tblmember SET reserve=if(reserve<".abs($canreserve).",0,reserve-".abs($canreserve).") ";
		$sql.= "WHERE id='".$_ord->id."' ";
		mysql_query($sql,get_db_conn());

		$sql = "INSERT tblreserve SET ";
		$sql.= "id			= '".$_ord->id."', ";
		$sql.= "reserve		= '-$canreserve', ";
		$sql.= "reserve_yn	= 'Y', ";
		$sql.= "content		= '�ֹ� ��Ұǿ� ���� ������ �������', ";
		$sql.= "orderdata	= '".$ordercode."=".$_ord->price."', ";
		$sql.= "date		= '".date("YmdHis")."' ";
		mysql_query($sql,get_db_conn());

		$sql = "INSERT tblorderproduct SET ";
		$sql.= "ordercode	= '".$ordercode."', ";
		$sql.= "tempkey		= '".$_ord->tempkey."', ";
		$sql.= "productcode	= '99999999999R', ";
		$sql.= "productname	= '�ֹ� ��Ұǿ� ���� ������ �������', ";
		$sql.= "quantity	= '1', ";
		$sql.= "reserve		= '-$canreserve', ";
		$sql.= "date		= '".date("Ymd")."' ";
		mysql_query($sql,get_db_conn());

		$first_recomid = "";
		if($recom_ok =="Y" && $arRecomType[0] == "B"){
			$sql = "SELECT rec_id, reserve FROM tblreservefirst WHERE id='".$_ord->id."' AND ordercode='".$ordercode."' AND cancelchk	= 'N'";
			$result=mysql_query($sql,get_db_conn());
			if($row=mysql_fetch_object($result)) {
				if(trim($row->rec_id) !=""){
					$sql = "INSERT tblreserve SET ";
					$sql.= "id			= '".$row->rec_id."', ";
					$sql.= "reserve		= '-".$row->reserve."', ";
					$sql.= "reserve_yn	= 'Y', ";
					$sql.= "content		= '".$_ord->id."���� �ֹ� ��Ұǿ� ���� ������ �������', ";
					$sql.= "orderdata	= '', ";
					$sql.= "date		= '".date("YmdHis")."' ";
					mysql_query($sql,get_db_conn());

					$sql = "UPDATE tblmember SET reserve=if(reserve<".abs($row->reserve).",0,reserve-".abs($row->reserve).") ";
					$sql.= "WHERE id='".$row->rec_id."' ";
					mysql_query($sql,get_db_conn());
					$first_recomid = $_ord->id;

					$sql = "INSERT tblreservefirst SET ";
					$sql.= "id			= '".$_ord->id."', ";
					$sql.= "reserve		= '".$row->reserve."', ";
					$sql.= "ordercode	= '".$ordercode."', ";
					$sql.= "rec_id		= '".$row->rec_id."', ";
					$sql.= "date		= '".date("YmdHis")."', ";
					$sql.= "cancelchk	= 'Y' ";
					mysql_query($sql,get_db_conn());
				}
			}
			mysql_free_result($result);
		}

		//��õ�� �� sns ȫ����
		if(sizeof($arSell_id)>0 && $arSnsType[0] != "N") {
			for($jj=0;$jj<sizeof($arSell_id);$jj++){
				if($first_recomid != $arSell_id[$jj]) {
					$sql = "INSERT tblreserve SET ";
					$sql.= "id			= '".$arSell_id[$jj]."', ";
					$sql.= "reserve		= '-".$arSell_rsv[$jj]."', ";
					$sql.= "reserve_yn	= 'Y', ";
					$sql.= "content		= '".$_ord->id."���� �ֹ� ��Ұǿ� ���� ������ �������', ";
					$sql.= "orderdata	= '', ";
					$sql.= "date		= '".date("YmdHis")."' ";
					mysql_query($sql,get_db_conn());

					$sql = "UPDATE tblmember SET reserve=if(reserve<".abs($arSell_rsv[$jj]).",0,reserve-".abs($arSell_rsv[$jj]).") ";
					$sql.= "WHERE id='".$arSell_id[$jj]."' ";
					mysql_query($sql,get_db_conn());
				}
			}
		}


		$log_content="## ȸ�� ������ ������� ## - �ֹ���ȣ : ".$ordercode." - ������ -".$canreserve;
		ShopManagerLog($_ShopInfo->getId(),$connect_ip,$log_content);
	}

//�޸� ������Ʈ
} else if($type=="memoupdate" && strlen($ordercode)>0) {
	$memo1=$_POST["memo1"];
	$memo2=$_POST["memo2"];

	$order_msg.="[MEMO]".$memo1;
	if(strlen(trim($memo2))!=0) $order_msg.="[MEMO]".$memo2;
	mysql_query("UPDATE tblorderinfo SET order_msg='".$order_msg."' WHERE ordercode='".$ordercode."'",get_db_conn());
	$isupdate=true;

//�������Ա� ������ ����/������/���� ���� ����
} else if($type=="orderupdate" && strlen($ordercode)>0) {
	$curdate = date("YmdHis");
	$vender=(int)$_POST["vender"];
	$productcode=$_POST["productcode"];
	$opt1_name=$_POST["opt1_name"];
	$opt2_name=$_POST["opt2_name"];
	$reserve=(int)$_POST["reserve"];
	$price=(int)$_POST["price"];
	$quantity=(int)$_POST["quantity"];
	$salereserve=(int)$_POST["salereserve"];
	$salemoney=(int)$_POST["salemoney"];
	$usereserve=(int)$_POST["usereserve"];
	$deli_price=(int)$_POST["deli_price"];
	$sumprice=(int)$_POST["sumprice"];

	if(strlen($productcode)==12 || strlen($productcode)==18) {
		if(strlen($productcode)==18 || substr($productcode,-4)=="GIFT") {
			if ($quantity<1) {
				echo "<script> alert ('������ 1���� ū ���ڷ� �Է��� �ּž� �մϴ�.');history.back();</script>\n";
				exit;
			}
			$setprice=$price/$quantity;
		} else {
			$setprice=$price;
			$quantity=1;
		}
		$sql = "UPDATE tblorderproduct SET ";
		$sql.= "quantity	= '".$quantity."', ";
		$sql.= "price		= '".$setprice."', ";
		$sql.= "reserve		= '".$reserve."' ";
		$sql.= "WHERE vender='".$vender."' ";
		$sql.= "AND ordercode='".$ordercode."' AND productcode='".$productcode."' ";
		$sql.= "AND opt1_name='".$opt1_name."' AND opt2_name='".$opt2_name."' ";
	} else if($productcode==2) { //�׷�ȸ�� ����/����
		if($salereserve>0) $tempdc_price=$salereserve;
		else $tempdc_price=$salemoney;
		$sql = "UPDATE tblorderinfo SET dc_price='".$tempdc_price."' ";
		$sql.= "WHERE ordercode='".$ordercode."' ";
	} else if($productcode==3) { //������ ����
		$sql = "UPDATE tblorderinfo SET reserve='".$usereserve."' ";
		$sql.= "WHERE ordercode='".$ordercode."' ";
	} else if($productcode==4) { //��۷�
		$sql = "UPDATE tblorderinfo SET deli_price='".$deli_price."' ";
		$sql.= "WHERE ordercode='".$ordercode."' ";
	} else if($productcode==5) { //��ü�ݾ�
		$sql = "UPDATE tblorderinfo SET price='".$sumprice."' ";
		$sql.= "WHERE ordercode='".$ordercode."' ";
	}
	if(mysql_query($sql,get_db_conn())) {
		if($productcode=="99999999990X") {	//��۷��� ���
			$sql = "SELECT SUM(price) as in_deli_price FROM tblorderproduct ";
			$sql.= "WHERE ordercode='".$ordercode."' AND productcode='99999999990X' ";
			$result=mysql_query($sql,get_db_conn());
			$row=mysql_fetch_object($result);
			mysql_free_result($result);

			$in_deli_price=$row->in_deli_price;
			$sql = "UPDATE tblorderinfo SET deli_price='".$in_deli_price."' ";
			$sql.= "WHERE ordercode='".$ordercode."' ";
			mysql_query($sql,get_db_conn());
		}
	}
	$isupdate=true;

	$log_content = "## �ֹ���ǰ ���� ## - �ֹ���ȣ : ".$ordercode." - ��ǰ�ڵ� : ".$productcode." - ���� : ".$quantity." - ���� : ".$setprice." - ������ : ".$reserve;
	ShopManagerLog($_ShopInfo->getId(),$connect_ip,$log_content);

//�������Ա� ������ ��ǰ/���� ����
} else if($type=="orderdelete" && strlen($ordercode)>0) {
	$curdate = date("YmdHis");
	$vender=(int)$_POST["vender"];
	$productcode=$_POST["productcode"];
	$opt1_name=$_POST["opt1_name"];
	$opt2_name=$_POST["opt2_name"];

	if(strlen($productcode)==12 || strlen($productcode)==18) {
		$sql = "DELETE FROM tblorderproduct WHERE vender='".$vender."' ";
		$sql.= "AND ordercode='".$ordercode."' AND productcode='".$productcode."' ";
		$sql.= "AND opt1_name='".$opt1_name."' AND opt2_name='".$opt2_name."' ";
	} else if($productcode==2) { //�׷�ȸ�� ����/����
		$sql = "UPDATE tblorderinfo SET dc_price=0 WHERE ordercode='".$ordercode."' ";
	} else if($productcode==3) { //������ ����
		$sql = "UPDATE tblorderinfo SET reserve=0 WHERE ordercode='".$ordercode."' ";
	} else if($productcode==4) { //��۷�
		$sql = "UPDATE tblorderinfo SET deli_price=0 WHERE ordercode='".$ordercode."' ";
	}
	if(mysql_query($sql,get_db_conn())) {
		if($productcode=="99999999990X") {	//��۷��� ���
			$sql = "SELECT SUM(price) as in_deli_price FROM tblorderproduct ";
			$sql.= "WHERE ordercode='".$ordercode."' AND productcode='99999999990X' ";
			$result=mysql_query($sql,get_db_conn());
			$row=mysql_fetch_object($result);
			mysql_free_result($result);

			$in_deli_price=$row->in_deli_price;
			$sql = "UPDATE tblorderinfo SET deli_price='".$in_deli_price."' ";
			$sql.= "WHERE ordercode='".$ordercode."' ";
			mysql_query($sql,get_db_conn());
		}
	}
	$isupdate=true;

	$log_content = "## �ֹ���ǰ ���� ## - �ֹ���ȣ : ".$ordercode." - ��ǰ�ڵ� : ".$productcode;
	ShopManagerLog($_ShopInfo->getId(),$connect_ip,$log_content);

} else if($type=="deligbnup" && strlen($ordercode)>0 && strlen($_POST["prcodes"])>0 && preg_match("/^(N|S|Y)$/",$deli_gbn) && preg_match("/^(N|X|S)$/",$_ord->deli_gbn)) {
	$prcodes=$_POST["prcodes"];
	$deli_gbn=$_POST["deli_gbn"];

	$prcodes=substr($prcodes,0,-1);
	$prlist=ereg_replace(',','\',\'',$prcodes);
	$sql = "UPDATE tblorderproduct SET deli_gbn='".$deli_gbn."', ";
	if($deli_gbn=="Y" ||$deli_gbn=="S") $sql.= "deli_date='".date("YmdHis")."' ";
	else $sql.= "deli_date=NULL ";
	$sql.= "WHERE ordercode='".$ordercode."' AND productcode IN ('".$prlist."') ";
	$sql.= "AND NOT (productcode LIKE 'COU%' OR productcode LIKE '999999%') ";

	if(mysql_query($sql,get_db_conn())){
		// ��� ���¿� ���� ��Ż ��ǰ ó��
		$sql = "update rent_schedule s left join tblorderproduct op on op.ordercode=s.ordercode and op.basketidx=s.basketidx set s.status= if(s.start <= now(),'BI','BO') where op.ordercode='".$ordercode."' and op.deli_gbn in ('Y','S') and s.status ='BO'";		
		@mysql_query($sql,get_db_conn());
		if($_ord->deli_gbn!=$deli_gbn) {
			$rescode=getDeligbn($ordercode,$deli_gbn);
			if(strlen($rescode)>0) {
				$isupdate=true;
			}
		}
	}

} else if($type=="deliinfoup" && strlen($ordercode)>0 && strlen($_POST["prcodes"])>0) {
	$prcodes=$_POST["prcodes"];

	$deliinfo=substr($prcodes,0,-1);
	$ardeli=explode("|",$deliinfo);
	for($i=0;$i<count($ardeli);$i++) {
		$prcode=$deli_com=$deli_num="";
		$prinfo=explode(",",$ardeli[$i]);
		for($j=0;$j<count($prinfo);$j++) {
			if (substr($prinfo[$j],0,7)=="PRCODE=") $prcode=substr($prinfo[$j],7);
			else if (substr($prinfo[$j],0,9)=="DELI_COM=") $deli_com=substr($prinfo[$j],9);
			else if (substr($prinfo[$j],0,9)=="DELI_NUM=") $deli_num=substr($prinfo[$j],9);
		}
		if(strlen($prcode)==18) {
			$sql = "UPDATE tblorderproduct SET deli_gbn='Y', deli_date='".date("YmdHis")."', deli_com='".$deli_com."', deli_num='".$deli_num."' ";
			$sql.= "WHERE ordercode='".$ordercode."' AND productcode='".$prcode."' ";
			$sql.= "AND NOT (productcode LIKE 'COU%' OR productcode LIKE '999999%') ";
			mysql_query($sql,get_db_conn());
		}
	}

	/* Todo
	$sql = "SELECT count(*) deli_gbn FROM tblorderproduct p left join tblorderinfo o on o.ordercode=p.ordercode WHERE p.ordercode='".$ordercode."' and o.deli_gbn !='Y' and  ";
	if(fetchResult($sql,0,0)!='Y') {

		$sql = "SELECT count(*) FROM tblorderproduct WHERE ordercode='".$ordercode."' && deli_gbn!='Y' AND NOT (productcode LIKE 'COU%' OR productcode LIKE '999999%')";
		if(fetchResult($sql,0,0)==0) {
			$ck_y = "Y";
			$tmpdelicom = $deli_com;
			$tmpdelinum = $deli_num;
			$delicomlist=array();

			$sql="SELECT company_name FROM tbldelicompany WHERE code='{$deli_com}' ";
			$tmpdeliname = fetchResult($sql,0,0);
		}
	}*/

} else if($type=="cancelback" && strlen($ordercode)) {
	$sql = "UPDATE tblorderinfo SET deli_gbn='S' ";
	$sql.= "WHERE ordercode='".$ordercode."' ";
	mysql_query($sql,get_db_conn());

	$sql = "UPDATE tblorderproduct SET deli_gbn='S' ";
	$sql.= "WHERE ordercode='".$ordercode."' ";
	mysql_query($sql,get_db_conn());

	//�������̺� ����
	$sql2 = "UPDATE rent_schedule SET status='BO' WHERE ordercode='".$ordercode."' ";
	mysql_query($sql2,get_db_conn());

}

if($isupdate) {
	$sql="SELECT * FROM tblorderinfo WHERE ordercode='".$ordercode."'";
	$result=mysql_query($sql,get_db_conn());
	$_ord=mysql_fetch_object($result);
	mysql_free_result($result);
	if(!$_ord) {
		echo "<script>alert(\"�ش� �ֹ������� �������� �ʽ��ϴ�.\");window.close();</script>";
		exit;
	}
}

$prescd="N";
if(preg_match("/^(B){1}/", $_ord->paymethod)) {	//������
	if (strlen($_ord->bank_date)>0) $prescd="Y";
} else if(preg_match("/^(V){1}/", $_ord->paymethod)) {	//������ü
	if ($_ord->pay_flag=="0000") $prescd="Y";
} else if(preg_match("/^(M){1}/", $_ord->paymethod)) {	//�ڵ���
	if ($_ord->pay_flag=="0000") $prescd="Y";
} else if(preg_match("/^(O|Q){1}/", $_ord->paymethod)) {	//�������
	if ($_ord->pay_flag=="0000" && strlen($_ord->bank_date)>0) $prescd="Y";
} else {
	if ($_ord->pay_flag=="0000" && $_ord->pay_admin_proc=="Y") $prescd="Y";
}

$sql = "SELECT * FROM tblsmsinfo ";
$result=mysql_query($sql,get_db_conn());
if($row=mysql_fetch_object($result)) {
	$smsok=true;
}
mysql_free_result($result);

$sql = "SELECT vendercnt FROM tblshopcount ";
$result=mysql_query($sql,get_db_conn());
$row=mysql_fetch_object($result);
$vendercnt=$row->vendercnt;
mysql_free_result($result);

if($vendercnt>0){
	$venderlist=array();
	$sql = "SELECT vender,id,com_name,delflag FROM tblvenderinfo ";
	$sql.= "ORDER BY id ASC ";
	$result=mysql_query($sql,get_db_conn());
	while($row=mysql_fetch_object($result)) {
		$venderlist[$row->vender]=$row;
	}
	mysql_free_result($result);
}

$delicomlist=array();
$sql="SELECT * FROM tbldelicompany ORDER BY code ";
$result=mysql_query($sql,get_db_conn());
while($row=mysql_fetch_object($result)) {
	$delicomlist[]=$row;
}
mysql_free_result($result);

$curdate = date("YmdHi",mktime(date("H"),date("i")-30,0,date("m"),date("d"),date("Y")));
?>

<html>
<head>
<meta http-equiv='Content-Type' content='text/html;charset=euc-kr'>
<title>�ֹ��󼼳��� ����</title>
<link rel="stylesheet" href="style.css" type="text/css">
<style type="text/css">
article {border:1px solid grey; border-top:2px solid; width:100%;margin:0;}
h3 {text-align:left;margin-bottom:3px}

.cancelReason {background-color: #efefef;}
.cancelReason ul {width:100%; list-style: none; display: inline-table;padding:0;}
.cancelReason .title {float:left;width:15%; font-weight: bold;}
.cancelReason .inputArea {float:right;width:85%;text-align:left}

.refundInfo ul {display: table;overflow: hidden;width:100%; table-layout: fixed;padding:0;margin:0;}
.refundInfo li {display: table-cell; text-align:center; height:50px; }
.refundInfo .grey {background-color: #efefef; border-right: 1px solid grey;}

.rInfoTop {margin-left:10px; margin-right:10px; border-bottom: 1px solid grey;}
.rInfoTop h3 {text-align:center}
.rInfoBottom dl {width:80%; display: inline-block;}
.rInfoBottom dl dt {float:left;height:25px;text-align: left}
.rInfoBottom dl dd {height:25px; text-align: right;}

</style>
<script type="text/javascript" src="lib.js.php"></script>
<STYLE TYPE="text/css">
<!--
body { font-size: 9pt}
td { font-size: 9pt; line-height: 15pt}
tr { font-size: 9pt}
.break {page-break-before: always;}
-->
</STYLE>
<SCRIPT LANGUAGE="javascript">
<!--

// CS ���� �˾� - (�ֹ��ڵ�, ��ǰ�ڵ�, ����, ȸ�����̵�)
function csManagerPop( order, product, vender ) {
	window.open( "cs_orderInsert.php?o="+order+"&p="+product+"&v="+vender , "csManagerInsert" , "width=620, height=500, menubar=no, status=no" );
}

//document.onkeydown = CheckKeyPress;
//document.onkeyup = CheckKeyPress;
function CheckKeyPress() {
	ekey = event.keyCode;

	if(ekey == 38 || ekey == 40 || ekey == 112 || ekey ==17 || ekey == 18 || ekey == 25 || ekey == 122 || ekey == 116) {
	   event.keyCode = 0;
	   return false;
	 }
}

function PageResize() {
	//var oWidth = document.all.table_body.clientWidth + 26;
	//var oHeight = document.all.table_body.clientHeight + 55;
	var oWidth = 750;
	var oHeight=650;

	window.resizeTo(oWidth,oHeight);
}

var countdeli=countdelinum=countdecan=countbank=countbacan=countvican=counttrcan=countokcan=countokhold=0;

function PagePrint(){
	if(confirm("�ֹ��󼼳����� ����Ʈ �Ͻðڽ��ϱ�?")) {
		print();
	}
}

function Sort(key){
	document.form2.sort.value=key;
	document.form2.type.value="sort";
	document.form2.submit();
}

function ProductInfo(code,prcode,popup) {
	document.form_reg.code.value=code;
	document.form_reg.prcode.value=prcode;
	document.form_reg.popup.value=popup;
	if (popup=="YES") {
		document.form_reg.action="product_register.add.php";
		document.form_reg.target="register";
		window.open("about:blank","register","width=820,height=700,scrollbars=yes,status=no");
	} else {
		document.form_reg.action="product_register.php";
		document.form_reg.target="";
	}
	document.form_reg.submit();
}
function ProductMouseOver(cnt) {
	obj = event.srcElement;
	WinObj=eval("document.all.primage"+cnt);
	obj._tid = setTimeout("ProductViewImage(WinObj)",200);
}
function ProductViewImage(WinObj) {
	WinObj.style.visibility = "visible";
}
function ProductMouseOut(Obj) {
	obj = event.srcElement;
	Obj = document.getElementById(Obj);
	Obj.style.visibility = "hidden";
	clearTimeout(obj._tid);
}

function ChangeUpdateMode(mode){
	document.form1.mode.value=mode;
	document.form1.type.value="";
	document.form1.submit();
}

function OrderUpdate(num,cnt,vender,productcode,opt1_name,opt2_name){
	if(confirm("�ش� ��ǰ�̳� ������ �����Ͻðڽ��ϱ�?")) {
		if(num==1) {
			document.form1.vender.value=vender;
			document.form1.productcode.value=productcode;
			document.form1.opt1_name.value=opt1_name;
			document.form1.opt2_name.value=opt2_name;
			document.form1.quantity.value=document.form1.arquantity[cnt].value;
			document.form1.reserve.value=document.form1.arreserve[cnt].value;
			document.form1.price.value=document.form1.arprice[cnt].value;
		} else {
			document.form1.productcode.value=num;
		}

		document.form1.mode.value="update";
		document.form1.type.value="orderupdate";
		document.form1.submit();
	}
}

function OrderDelete(num,vender,productcode,opt1_name,opt2_name){
	if(confirm("�ش� ��ǰ�̳� ������ �����Ͻðڽ��ϱ�?")) {
		if(num==1) {
			document.form1.vender.value=vender;
			document.form1.productcode.value=productcode;
			document.form1.opt1_name.value=opt1_name;
			document.form1.opt2_name.value=opt2_name;
		} else {
			document.form1.productcode.value=num;
		}

		document.form1.mode.value="update";
		document.form1.type.value="orderdelete";
		document.form1.submit();
	}
}

function RestoreOrder(temp,delivery,reserve) {
	if(temp=="quan") mess="��������";
	else mess="�ֹ����";

	if(document.form2.recoveryquan.value=="Y") {
		alert(mess+"�� �� �ֹ����� ���ؼ� �ѹ��� �����մϴ�.");
		return;
	}
<?
	if ($_ord->deli_gbn!="C" && preg_match("/^(C|P){1}/",$_ord->paymethod) && substr($_ord->ordercode,0,12)>$curdate) {
		echo "	alert(\"ī���ֹ��� ��� �ֹ��������� 30�� ��� �� \"+mess+\"�� �����մϴ�.\\n\\n���� ī������ �Է��� �� �� �ֽ��ϴ�.\");";
	} else if($_ord->pay_admin_proc!="C" && $_ord->pay_flag=="0000" && preg_match("/^(C){1}/", $_ord->paymethod)) {
		echo "	alert(\"ī���ֹ� ��Ҵ� ���� ī����� �� ������ �ּ���.\");";
	} else if($_ord->pay_admin_proc!="C" && $_ord->pay_flag=="0000" && preg_match("/^(M){1}/", $_ord->paymethod)) {
		echo "	alert(\"�޴����ֹ� ��Ҵ� ���� �޴������� ��� �� ������ �ּ���.\");";
	} else {
?>
	if(delivery!="C" && reserve>0 && confirm("ȸ���������� ���� ����ϼž� �մϴ�.")) {
		RestoreReserve();
	} else if(temp=="quan" && confirm("��ǰ������ �ڵ����� ���͵Ǹ�,\n\n�ش��ֹ��� �ֹ���ҵ˴ϴ�.<?=(preg_match("/^(P|Q){1}/",$_ord->paymethod)?"\\n\\n�Ÿź�ȣ ������ ���� �ڵ����� ������ ��Ұ� �˴ϴ�.":"")?>")) {
		document.form2.type.value="recoveryquan";
		document.form2.recoveryquan.value="Y";
		document.form2.submit();
	} else if(temp=="can" && confirm("��ǰ������ �������� �ʽ��ϴ�.\n\n�ش� �ֹ��� ����Ͻðڽ��ϱ�?<?=(preg_match("/^(P|Q){1}/",$_ord->paymethod)?"\\n\\n�Ÿź�ȣ ������ ���� �ڵ����� ������ ��Ұ� �˴ϴ�.":"")?>")) {
		document.form2.type.value="recoverycan";
		document.form2.recoveryquan.value="Y";
		document.form2.submit();
	}
<?	} ?>
}

function RestoreReserve(){
	if(document.form2.recoveryrese.value=="Y"){
		alert("������ ������ �� �ֹ����� ���ؼ� �ѹ��� �����մϴ�.");
		return;
	}
<?
	if ($_ord->deli_gbn!="C" && preg_match("/^(C|P){1}/",$_ord->paymethod) && substr($_ord->ordercode,0,12)>$curdate) {
		 echo "	alert(\"ī���ֹ��� ��� �ֹ��������� 30�� ��� �� ������ ������ �����մϴ�.\\n\\n���� ī������ �Է��� �� �� �ֽ��ϴ�.\");";
	} else {
?>
	if(confirm("ȸ�� �������� �ڵ����� �����Ǹ�,\n\n�ش��ֹ��� �ֹ��ּҵ˴ϴ�.")){
		document.form2.type.value="recoveryres";
		document.form2.recoveryrese.value="Y";
		document.form2.submit();
		document.form2.submit();
	}
<?	} ?>
}

function RestoreReserveCancel(temp) {
	if(document.form2.recoveryrecan.value=="Y") {
		alert("������ ��Ҵ� �� �ֹ����� ���ؼ� �ѹ��� �����մϴ�.");
		return;
	}
	if(confirm("��ǰ ������� ���� ���� �������� �ڵ����� ��ҵ˴ϴ�.\n\n������ ����� �ֹ����� �ݵ�� ��һ��·� �����ϼž� �մϴ�.")) {
		document.form2.type.value="recoveryrecan";
		document.form2.canreserve.value=temp;
		document.form2.recoveryrecan.value="Y";
		document.form2.submit();
	}
}

function SendSMS(tel1,tel2,tel3) {
	number=tel1+"|"+tel2+"|"+tel3;
	document.smsform.number.value=number;
	window.open("about:blank","sendsmspop","width=220,height=350,scrollbars=no");
	document.smsform.submit();
}

function SendMail(mail) {
	try {
		opener.parent.topframe.ChangeMenuImg(3);
		opener.document.mailform.rmail.value=mail;
		opener.document.mailform.submit();
	} catch(e) {}
}

function HideMemo() {
	try {
		membermemo_layer.style.visibility="hidden";
	} catch (e) {}
}

function MemberMemo(id) {
	window.open("about:blank","memopop","width=350,height=350,scrollbars=no");
	document.formmemo.target="memopop";
	document.formmemo.id.value=id;
	document.formmemo.action="member_memopop.php";
	document.formmemo.submit();
	document.formmemo.target="";
	document.formmemo.action="<?=$_SERVER[PHP_SELF]?>";
}

function HideDisplay() {
	document.formhide.submit();
}

/**************************************************************************************************/
//������ ����
function printtax(){
	document.taxprintform.submit();
}
//���ݿ����� ��û
function get_taxsave() {
	window.open("about:blank","taxsavepop","width=266,height=220,scrollbars=no");
	document.taxsaveform.submit();
}

//����� ���
function printaddress(){
	alert("���� �غ����Դϴ�.");
}
//�ݼ�ó��
function delicancel(){
	if(!countdecan){
		if(!confirm("�ݼ�ó�� �Ͻðڽ��ϱ�?")) return;
		countdecan++;
		document.form2.type.value="redelivery";
		document.form2.submit();
	}
}
//ī��/�ڵ��� ���
function card_ask(temp,caltype){
	//card_ask - ���Կ�û
	//card_cancel - ī�����
	if(temp=="card_ask") { //���Կ�û
		if(confirm("�ſ�ī�� ���Կ�û�� �Ͻðڽ��ϱ�?")) {
			<?if($pg_type=="A"){?>
				document.kcpform.action="<?=$Dir?>paygate/A/edi.php";
				document.kcpform.submit();
			<?}else if($pg_type=="B"){?>
			<?}else if($pg_type=="C"){?>
			<?}else if($pg_type=="D"){?>
			<?}?>
		}
	} else if(temp=="card_cancel") {//��ҿ�û
		<?if($pg_type=="A"){?>
			if(confirm("���ó�� �� �ٽ� �ǵ��� �� �����ϴ�.\n\n���� ���ó���� �Ͻðڽ��ϱ�?")) {
				document.kcpform.action="<?=$Dir?>paygate/A/cancel.php";
				document.kcpform.submit();
			}
		<?}else if($pg_type=="B"){?>
			if(confirm("���ó�� �� �ٽ� �ǵ��� �� �����ϴ�.\n\n���� ���ó���� �Ͻðڽ��ϱ�?")) {
				document.dacomform.action="<?=$Dir?>paygate/B/cancel.php";
				document.dacomform.submit();
			}
		<?}else if($pg_type=="C"){?>
			if(caltype == "hp") {
			if(confirm("\n������������������������������  �� ��      ��      ��      �� ��  ����������������������������������    \n��                                                                                                                                    ��    \n��                                                                                                                                    ��    \n��       ��. �޴��� ���� ��� ó���� ���θ� DB���� �ݿ��Ǹ� �ô�����Ʈ�� ���޵��� �ʽ��ϴ�.       ��    \n��                                                                                                                                    ��    \n��       ��. �ô�����Ʈ �޴��� ���� ��Ҵ� �ش� �Уǻ��� ���������������� ó�� �� �ּ���.           ��    \n��                                                                                                                                    ��    \n��                                                                                                                                    ��    \n��������������������������������������������������������������������������������������������    \n\n                               �������ó���� ���θ� DB���� �ݿ��˴ϴ�. ���� �Ͻðڽ��ϱ�?")) {
				document.allthegateform.action="<?=$Dir?>paygate/C/cancel.php";
				document.allthegateform.submit();
			}
			} else {
				if(confirm("���ó�� �� �ٽ� �ǵ��� �� �����ϴ�.\n\n���� ���ó���� �Ͻðڽ��ϱ�?")) {
					document.allthegateform.action="<?=$Dir?>paygate/C/cancel.php";
					document.allthegateform.submit();
				}
			}
		<?} else if($pg_type=="D"){?>
			if(confirm("���ó�� �� �ٽ� �ǵ��� �� �����ϴ�.\n\n���� ���ó���� �Ͻðڽ��ϱ�?")) {
				document.inicisform.action="<?=$Dir?>paygate/D/cancel.php";
				document.inicisform.submit();
			}
		<?} else if($pg_type=="E"){?>
			if(confirm("���ó�� �� �ٽ� �ǵ��� �� �����ϴ�.\n\n���� ���ó���� �Ͻðڽ��ϱ�?")) {
				document.niceform.action="<?=$Dir?>paygate/E/cancel.php";
				document.niceform.submit();
			}
		<?}?>
	}
}
//�߼��غ�, �߼ۿϷ� ó��
function delisend(temp){
	if(!countdeli){
		if(temp=="Y" && !confirm("�Ա�Ȯ���� �ȵ� �ֹ����Դϴ�. ����� �Ϸ��Ͻðڽ��ϱ�?")) return;
		if(temp=="S" && !confirm("�߼��غ� ���ø� �Ͻðڽ��ϱ�?")) return;
		if(temp=="S1" && !confirm("�Ա�Ȯ���� �ȵ� �ֹ����Դϴ�. �߼��غ� ���ø� �Ͻðڽ��ϱ�?")) return;
		//else if(temp=="N" && !confirm("����� �Ϸ��Ͻðڽ��ϱ�?")) return;
		if(temp=="S" || temp=="S1") document.form2.type.value="readydeli";
		else {

			tmpdelicom=document.escrow_form1.escrow_deli_com.value;
			tmpdelinum=document.escrow_form1.escrow_deli_num.value;
			tmpdeliname=document.escrow_form1.escrow_deli_com.options[document.escrow_form1.escrow_deli_com.selectedIndex].text;

			if(document.getElementById("deliescrow")) {
				if(document.getElementById("deliescrow").style.display == "none") {
					document.getElementById("deliescrow").style.display="";
					return;
				}
				if(document.escrow_form1.escrowcaltype.value=="Y" && (!tmpdelicom || !tmpdelinum || !tmpdeliname)) {
					alert("����ũ�� �߼ۿϷ� ó���� ���������� �Է��ؾ߸� ó���� �����մϴ�.");
					return;
				}
			}

			if(confirm("SMS �߼� Ȯ��!!\n\n1. ��ǥ �������� ���Է��� ��� ��۸��Ͽ��� ���������� ��µ��� �ʽ��ϴ�.\n"+"2. ��ǥ �������� ���Է��� ��� �����ȣ �ȳ� SMS �� �߼۵��� �ʽ��ϴ�.\n\n          �߼ۿϷ�� ������ ����/SMS�� �߼��Ͻðڽ��ϱ�?\n\n\n   * ��۾�ü : "+tmpdeliname+"\n\n   * �����ȣ : "+tmpdelinum+"")) {
				document.form2.delimailtype.value="Y";
			} else {
				document.form2.delimailtype.value="N";
			}
			//if(!confirm("���� ����� �Ϸ��Ͻðڽ��ϱ�?")) return;


			document.form2.deli_com.value=tmpdelicom;
			document.form2.deli_num.value=tmpdelinum;
			document.form2.deli_name.value=tmpdeliname;
			document.form2.type.value="delivery";
		}
		countdeli++;

		document.form2.submit();
	}
}

function cgY(){
	document.form2.delimailtype.value="N";
	document.form2.deli_com.value='<?=$tmpdelicom?>';
	document.form2.deli_num.value='<?=$tmpdelinum?>';
	document.form2.deli_name.value='<?=$tmpdeliname?>';
	document.form2.type.value="delivery";
	document.form2.submit();
}

function escrow_deliclose()
{
	if(document.getElementById("deliescrow")) {
		if(document.getElementById("deliescrow").style.display == "") {
			document.escrow_form1.escrow_deli_com.selectedIndex=0;
			document.escrow_form1.escrow_deli_num.value="";
			document.getElementById("deliescrow").style.display = "none";
		}
	}
}

//�������Ա� �Ϸ�ó��
function banksend(){
	if(!countbank){
		if(!confirm("�Ա�Ȯ���� �����Ͻðڽ��ϱ�?")) return;
		countbank++;
		document.form2.type.value="bank";
		document.form2.submit();
	}
}
//������ ȯ��ó��
function bankcancel(){
	if(!countbacan){
		if(!confirm("�Ա�����Ͻðڽ��ϱ�?")) return;
		countbacan++;
		document.form2.type.value="bankcancel";
		document.form2.submit();
	}
}
//�Ϲ� ������� ȯ��ó��
function virtualcancel() {
	if(!countvican){
		if(!confirm("������� �Աݰǿ� ���ؼ� ������ ���� �Ǵ� ���������� ȯ���� �ϼ̽��ϱ�?")) return;
		countvican++;
		document.form2.type.value="virtualcancel";
		document.form2.submit();
	}
}
//�ǽð�������ü ȯ��ó��
function transcancel() {
	if(!counttrcan){
		if(!confirm("�ǽð�������ü �����ǿ� ���ؼ� ������ ���� �Ǵ� ���������� ȯ���� �ϼ̽��ϱ�?")) return;
		counttrcan++;
		document.form2.type.value="transcancel";
		document.form2.submit();
	}
}

<?if($pg_type=="A" || $pg_type=="C" || $pg_type=="D"){?>
//�Ÿź�ȣ ���꺸��
function okhold() {
	if(!countokhold) {
		if(!confirm("�̹� ��۵� �Ÿź�ȣ �����ǿ� ���ؼ� ���꺸�� ó���� �Ͻðڽ��ϱ�?\n\n���꺸�� ó�� �� ��ǰ�� �ݼۿϷ�Ǹ� ���� ���ó���� �����մϴ�.")) return;
		countokhold++;
		document.form2.type.value="okhold";
		document.form2.submit();
	}
}
<?}?>

//�Ÿź�ȣ ���ó��
function okcancel(temp,date) {
	if(!countokcan) {
		if(temp=="Q") {
			if(date.length>0) {
				<?if($pg_type=="A"){?>
				if(!confirm("�Ÿź�ȣ �ֹ��� ���ؼ� ���ó�� �Ͻðڽ��ϱ�?\n\nȯ�Ҵ�� ������ �ݾ��� ȯ�ҵǸ� �ڵ� �ֹ� ��ҵ˴ϴ�.")) return;
				<?}else if($pg_type=="B"){?>
					<?if(strlen($_ord->deli_date)==14){?>
					alert("����ũ�� ȯ��ó���� LG������ ������������� �Ͻñ� �ٶ��ϴ�.\n\nȯ�ҿϷ� �� ���θ��� �ڵ� �ݿ��˴ϴ�."); return;
					<?}?>
				<?}else if($pg_type=="C"){?>

				<?}else if($pg_type=="D"){?>

				<?}?>
			} else {
				<?if($pg_type=="A"){?>
				if(!confirm("�Ÿź�ȣ �ֹ��� ���ؼ� ���ó�� �Ͻðڽ��ϱ�?\n\n�Ա����̹Ƿ� �߱ް��´� �Ҹ�˴ϴ�.")) return;
				<?}else if($pg_type=="B"){?>
				//if(!confirm("�Ÿź�ȣ �ֹ��� ���ؼ� ���ó�� �Ͻðڽ��ϱ�?")) return;
				<?}else if($pg_type=="C"){?>

				<?}else if($pg_type=="D"){?>

				<?}?>
			}
		}
		if(!confirm("�Ÿź�ȣ �ֹ��� ���ؼ� ���ó�� �Ͻðڽ��ϱ�?")) return;
		countokcan++;
		document.form2.type.value="recancel";
		document.form2.submit();
	}
}
/**************************************************************************************************/


function f_addr_search(form,post,addr,gbn) {
	window.open("<?=$Dir.FrontDir?>addr_search.php?form="+form+"&post="+post+"&addr="+addr+"&gbn="+gbn,"f_post","resizable=yes,scrollbars=yes,x=100,y=200,width=370,height=250");
}

function AddressUpdate(){
	if(confirm("������� �ش� �ּҷ� �����Ͻðڽ��ϱ�?")) {
		document.form2.type.value="addressupdate";
		document.form2.submit();
	}
}

function DeliSearch(deli_url){
	window.open(deli_url,"�������","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizeble=yes,copyhistory=no,width=600,height=550");
}

function DeliNumUpdate() {
	if(!countdelinum) {
		if(!confirm("��� ��ǰ�� ��������� �����Ͻðڽ��ϱ�?")) {
			document.form2.delimailtype.value="N";
			return;
		}

		if(confirm("��� ��ǰ�� ������� ���泻���� ����/SMS�� �߼��Ͻðڽ��ϱ�?"))
			document.form2.delimailtype.value="Y";
		else
			document.form2.delimailtype.value="N";

		document.form2.deli_com.value=document.form1.deli_com.value;
		document.form2.deli_num.value=document.form1.deli_num.value;
		document.form2.type.value="deliupdate";
		document.form2.submit();
	}
}

function MemoUpdate(){
	if(confirm("�޸� ���/�����Ͻðڽ��ϱ�?")) {
		document.form2.type.value="memoupdate";
		document.form2.submit();
	}
}

<? if(preg_match("/^(Q){1}/",$_ord->paymethod) && strlen($_ord->bank_date)==14) { ?>
function escrow_bank_account() {
	alert("ȯ�Ұ��� ��� �� �Ÿź�ȣ ���ó���� �Ͻø�\n\n��ϵ� ȯ�Ұ��·� ȯ��ó���˴ϴ�.");
	window.open("about:blank","baccountpop","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizeble=no,copyhistory=no,width=100,height=100");
	document.vform.submit();
}
<? } ?>


function viewVenderInfo(vender) {
	window.open("about:blank","vender_infopop","width=100,height=100,scrollbars=yes");
	document.vForm.vender.value=vender;
	document.vForm.target="vender_infopop";
	document.vForm.submit();
}

function EtcMouseOver(cnt) {
	obj = event.srcElement;
	WinObj=eval("document.all.etcdtl"+cnt);
	obj._tid = setTimeout("EtcView(WinObj)",200);
}
function EtcView(WinObj) {
	WinObj.style.visibility = "visible";
}
function EtcMouseOut(cnt) {
	obj = event.srcElement;
	WinObj=eval("document.all.etcdtl"+cnt);
	WinObj.style.visibility = "hidden";
	clearTimeout(obj._tid);
}

function CheckAll(){
	chkval=document.form1.allcheck.checked;
	if(typeof(document.form1.chkprcode.length)=="number") {
		cnt=document.form1.chkprcode.length;
		for(i=1;i<cnt;i++){
			document.form1.chkprcode[i].checked=chkval;
		}
	}
}

function changeDeliinfo() {

	if(!document.form1.deli_com.value) {
		alert("���ȸ�縦 ���� �Ͻñ� �ٶ��ϴ�.");
		return;
	}

	if(!document.form1.deli_num.value) {
		alert("�����ȣ�� �Է� �Ͻñ� �ٶ��ϴ�.");
		return;
	}

	<?
		if(preg_match("/^(B){1}/",$_ord->paymethod) && strlen($_ord->bank_date)!=14) {
	?>
		if( !confirm("�Ա�Ȯ���� �ȵ� �ֹ����Դϴ�. �����ȣ �ϰ� ����� �Ͻðڽ��ϱ�?") ) {
			return;
		}
	<?
		}
	?>

	//if( document.orderDeliReadyProductobj.value == 'Y' ) { // �߼ۿϷ� + ���� ��ȣ �� ���

		if(typeof(document.form1.chkprcode.length)=="number") {
			document.form1.prcodes.value="";
			cnt=document.form1.chkprcode.length;
			for(i=1;i<cnt;i++){
				if(document.form1.chkprcode[i].checked==true) {
					document.form1.prcodes.value+="PRCODE="+document.form1.chkprcode[i].value+",DELI_COM="+document.form1.deli_com.value+",DELI_NUM="+document.form1.deli_num.value+"|";
				}
			}
		} else {
			alert("��� ��ǰ�� �������� �ʽ��ϴ�.");
			return;
		}
		if(document.form1.prcodes.value.length==0) {
			alert("�����Ͻ� ��ǰ�� �����ϴ�.");
			return;
		}
		if(confirm("���õ� ��ǰ�� ��۾�ü/�����ȣ�� ����(���)�մϴ�.\n\n������ �����Ͻðڽ��ϱ�?")) {
			document.form1.type.value="deliinfoup";
			document.form1.submit();
		}
	//} else { // �߼��غ�
	//}
}

<?if(preg_match("/^(N|X|S)$/",$_ord->deli_gbn) && $_ord->pay_admin_proc!="C" && $prescd=="Y") {?>
function changeDeli(obj) {

	if(typeof(document.form1.chkprcode.length)=="number") {
		document.form1.prcodes.value="";
		cnt=document.form1.chkprcode.length;
		for(i=1;i<cnt;i++){
			if(document.form1.chkprcode[i].checked==true) {
				document.form1.prcodes.value+=document.form1.chkprcode[i].value+",";
			}
		}
	} else {
		alert("��� ��ǰ�� �������� �ʽ��ϴ�.");
		return;
	}
	deli_gbn=obj.value;
	document.form1.prcodes.value="";
	for(i=1;i<document.form1.chkprcode.length;i++) {
		if(document.form1.chkprcode[i].checked==true) {
			document.form1.prcodes.value+=document.form1.chkprcode[i].value+",";
		}
	}
	if(document.form1.prcodes.value.length==0) {
		alert("�����Ͻ� ��ǰ�� �����ϴ�.");
		obj.selectedIndex=0;
		return;
	}
	if(deli_gbn.length>0) {
		delistr="";
		if(deli_gbn=="N") delistr="[��ó��]";
		else if(deli_gbn=="S") delistr="[�߼��غ�]";
		else if(deli_gbn=="Y") delistr="[�߼ۿϷ�]";
		if(confirm("���õ� ��ǰ�� ó�����¸� "+delistr+" ���·� �����Ͻðڽ��ϱ�?")) {
			document.form1.type.value="deligbnup";
			document.form1.submit();
		} else {
			document.form1.prcodes.value="";
			obj.selectedIndex=0;
		}
	} else {
		document.form1.prcodes.value="";
		obj.selectedIndex=0;
	}
}
<?}?>


function rChangeUpdateMode(mode){
	document.form1.mode.value=mode;
	document.form1.type.value="";
	document.form1.submit();
}

// CS ���� �˾� - (�ֹ��ڵ�, ��ǰ�ڵ�, ����, ȸ�����̵�)
function csManagerPop( order, product, vender ) {
	window.open( "cs_orderInsert.php?o="+order+"&p="+product+"&v="+vender , "csManagerInsert" , "width=620, height=500, menubar=no, status=no" );
}




// ��ǰ�� �߼��غ� / ���(�����ȣ) ó��
function orderDeliReadyProductChkAll( V ) {
	if( V == "Y" ) {
		document.form1.deli_com.style.display = 'block';
		document.form1.deli_num.style.display = 'block';
	} else {
		document.form1.deli_com.style.display = 'none';
		document.form1.deli_num.style.display = 'none';
	}
}

function refundsTo(str){
	var goods = '';
	var re_price = 0;
	var dcPriceTotal = 0;

	for(i=1;i<document.form1.chkprcode.length;i++) {

		if(document.form1.chkprcode[i].checked==true){
			var chkprcode_statuss_value = document.form1.chkprcode_statuss;
			if( chkprcode_statuss_value.length ) {
				chkprcode_statuss_value = chkprcode_statuss_value[i-1].value;
			} else {
				chkprcode_statuss_value = chkprcode_statuss_value.value;
			}
			if(chkprcode_statuss_value=='RC' ) {
				alert('�̹� ȯ�� ó���� ���Դϴ�.');
				return;
			}



			if( document.form1.chkprcode_uid.length > 1 ) {
					if( goods == '' ) {
						goods = document.form1.chkprcode_uid[i-1].value;
					} else {
						goods += '|' + document.form1.chkprcode_uid[i-1].value;
					}
				//}chkprcode_uid
				re_price = re_price + parseInt( document.form1.chkprcode_price[i-1].value ) * 1;
			} else {
				goods = document.form1.chkprcode_uid.value;
				re_price = re_price + parseInt( document.form1.chkprcode_price.value ) * 1;
			}



			// ���� ��� ���ΰ�
			var dcPrices = 0;
			if(typeof document.form1.chkprcode[i].dcprice == "undefined"){
				dcPrices = parseInt(document.form1.chkprcode[i].getAttribute('dcprice'));
			}else{
				dcPrices = parseInt( document.form1.chkprcode[i].dcprice );
			}
			if ( !isNaN(dcPrices) && dcPrices > 0){
				dcPriceTotal += dcPrices;
				re_price -= dcPrices;
			}
		}
	}
	if(!goods) {
		alert("�����Ͻ� ��ǰ�� �����ų� �̹� ��û�� ��ǰ�Դϴ�");
		return;
	}

	f = document.reTmpForm;

	f.type.value = "order";
	f.status.value = str;
	f.goods.value = goods;
	f.bank.value = document.getElementById("bank").value;
	f.account_name.value = document.getElementById("account_name").value;
	f.account_num.value = document.getElementById("account_num").value;

	f.action = "order_new_ok.php";

	if (str=='RC') {

		rp = document.getElementById("refund_price");
		rp.value = re_price;

		document.getElementById("re_price_max").value = re_price;

		dcPriceTotalMsg.innerHTML = "<br />����(����/�������) ���� �ݾ� : " + dcPriceTotal + " ��";
		f.dcPriceSend.value = dcPriceTotal;

		refundDivView('Y');

	}else{
		if ( confirm("�����ѻ�ǰ�� ȯ�ҿ�û�� �ʱ�ȭ �Ͻðڽ��ϱ�?") )
		{
			f.submit();
		}
	}
}

function refundsCom() {

	rp = document.getElementById("refund_price");

	if ( document.getElementById("re_reserve") != null ) {
		reserve = document.getElementById("re_reserve").value;
		if (!reReserveChk(reserve)) {
			alert("ȯ�� ������ �����ݺ��� ū�׼��� �Է��ϼ̽��ϴ�.");
			return;
		}
	}

	re_price_max = document.getElementById("re_price_max");

	if (rp.value>re_price_max.value) {
		if( !confirm("��ǰ �ݾ׺��� ū �׼��Դϴ�.\n�׷��� ��� ���� �Ͻðڽ��ϱ�?") ){
			rp.focus();
			return;
		}
	}

	if (rp.value < 0 ) {
		alert("0���� �������� ȯ�� �ݾ����� �Է��Ͻ� �� �����ϴ�.\n��Ͽ��� �������� �����ϼ̴��� Ȯ�����ּ���.");
		rp.focus();
		return;
	}

	f = document.reTmpForm;
	f.re_price.value = rp.value;
	if ( document.getElementById("refund_free") != null ) {
		f.refund_free.value = document.getElementById("refund_free").value;
	}

	if (document.getElementById("re_reserve") != null ) {

		reserve = document.getElementById('re_reserve');
		f.reserve.value = reserve.value;
	}

	//f.dcPriceSend.value = document.getElementById("dcPriceSend").value;

	f.submit();
}

function refundDivView(v) {

	r_div = document.getElementById('refund_div');

	if (document.getElementById("re_reserve") != null ) {
		document.getElementById("re_reserve").value=0;
	}

	if (v=='N') {
		r_div.style.display="none";
	}else {
		if (r_div.style.display=="none") {
			r_div.style.display="block";
		}else{
			r_div.style.display="none";
		}
	}
}

function card_part_cancel(uid) {

<?if($pg_type=="A"){?>
		if(confirm("���ó�� �� �ٽ� �ǵ��� �� �����ϴ�.\n\n���� ���ó���� �Ͻðڽ��ϱ�?")) {
			document.kcpform.uid.value = uid;
			document.kcpform.action="<?=$Dir?>paygate/A/part_cancel.php";
			document.kcpform.submit();
		}
<?}else if($pg_type=="B"){?>
		if(confirm("���ó�� �� �ٽ� �ǵ��� �� �����ϴ�.\n\n���� ���ó���� �Ͻðڽ��ϱ�?")) {
			document.dacomform.uid.value = uid;
			document.dacomform.action="<?=$Dir?>paygate/B/part_cancel.php";
			document.dacomform.submit();
		}
<?}else if($pg_type=="C"){?>
		if(caltype == "hp") {
			if(confirm("\n������������������������������  �� ��      ��      ��      �� ��  ����������������������������������    \n��                                                                                                                                    ��    \n��                                                                                                                                    ��    \n��       ��. �޴��� ���� ��� ó���� ���θ� DB���� �ݿ��Ǹ� �ô�����Ʈ�� ���޵��� �ʽ��ϴ�.       ��    \n��                                                                                                                                    ��    \n��       ��. �ô�����Ʈ �޴��� ���� ��Ҵ� �ش� �Уǻ��� ���������������� ó�� �� �ּ���.           ��    \n��                                                                                                                                    ��    \n��                                                                                                                                    ��    \n��������������������������������������������������������������������������������������������    \n\n                               �������ó���� ���θ� DB���� �ݿ��˴ϴ�. ���� �Ͻðڽ��ϱ�?")) {
				document.allthegateform.uid.value = uid;
				document.allthegateform.action="<?=$Dir?>paygate/C/part_cancel.php";
				document.allthegateform.submit();
			}
		} else {
			if(confirm("���ó�� �� �ٽ� �ǵ��� �� �����ϴ�.\n\n���� ���ó���� �Ͻðڽ��ϱ�?")) {
				document.allthegateform.action="<?=$Dir?>paygate/C/part_cancel.php";
				document.allthegateform.submit();
			}
		}
<?} else if($pg_type=="D"){?>
		if(confirm("���ó�� �� �ٽ� �ǵ��� �� �����ϴ�.\n\n���� ���ó���� �Ͻðڽ��ϱ�?")) {
			document.inicisform.uid.value = uid;
			document.inicisform.action="<?=$Dir?>paygate/D/part_cancel.php";
			document.inicisform.submit();
		}
<?} else if($pg_type=="G"){?>
		if(confirm("���ó�� �� �ٽ� �ǵ��� �� �����ϴ�.\n\n���� ���ó���� �Ͻðڽ��ϱ�?")) {
			document.allatform.action="<?=$Dir?>paygate/G/cancel.php";
			document.allatform.submit();
		}
<?}?>

}

function reserveDivView(v) {

	r_div = document.getElementById('reserve_div');

	if (v=='N') {
		r_div.style.display="none";
	}else {
		if (r_div.style.display=="none") {
			r_div.style.display="";
		}else{
			r_div.style.display="none";
		}
	}
}

function reReserveChk(reserve) {

	if (reserve>0) {
		re_reserve_max = document.getElementById("re_reserve_max").value;

		if ((reserve*1)>(re_reserve_max*1)) {
			return false;
		}
	}

	return true;
}


function reReserveCom() {

	reserve = document.getElementById('re_reserve');

	if (reserve.value=='') {
		alert("ȯ��Ȱ �������� �Է����ּ���.");
		return;
	}

	f = document.reTmpForm;


	f.type.value = "reserve";
	f.re_price.value = reserve.value;
	f.action = "order_new_ok.php";

	f.submit();

}

function rePriceMinusReserve(obj) {

	reserve = obj.value;

	if (reserve=='') reserve = 0;

	re_price_max = document.getElementById("re_price_max").value;

	if (reserve>=0) {
		re_price = re_price_max-reserve;

		rp = document.getElementById("refund_price");

		if (re_price<0) {
			alert("������ ��ұݾ��� �ʹ� Ů�ϴ�.");
			rp.value = re_price_max;
			obj.value = 0;
		}else{
			rp.value = re_price;
		}
	}

}

function ReserveInOut(id){
	window.open("about:blank","reserve_set","width=245,height=140,scrollbars=no");
	document.reserveform.target="reserve_set";
	document.reserveform.id.value=id;
	document.reserveform.type.value="reserve";
	document.reserveform.submit();
}


function ReserveInfo(id) {
	window.open("about:blank","reserve_info","width=500,height=400,scrollbars=yes");
	document.reserveform2.id.value=id;
	document.reserveform2.submit();
}

function reFuAccount() {
	f = document.reTmpForm;

	f.type.value = "bank";
	f.bank.value = document.getElementById("bank").value;
	f.account_name.value = document.getElementById("account_name").value;
	f.account_num.value = document.getElementById("account_num").value;

	f.action = "order_new_ok.php";
	f.submit();
}



function cancelBack(mode){
	document.form1.mode.value=mode;
	document.form1.type.value="cancelback";
	document.form1.submit();
}

// ������� ( 2014-07-08 x2chi )
function popCancle ( o, t ) {
	window.open("orderCancle.php?o="+o+"&t="+t,"popCancle","width=600,height=600,scrollbars=no");
}



//-->
</SCRIPT>
</head>
<!--body leftmargin=0 topmargin=0 marginwidth=0 marginheight=0 oncontextmenu="return false" style="overflow-x:hidden;overflow-y:hidden;" ondragstart="return false" onselectstart="return false" oncontextmenu="return false" onLoad="PageResize();"-->
<body leftmargin=0 topmargin=0 marginwidth=0 marginheight=0 oncontextmenu="PagePrint();return false;" style="overflow-x:hidden;overflow-y:auto;" onLoad="PageResize();" >

<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed;" id=table_body>
	<tr><td><img src="images/tit_orderdetail.gif" alt="" /></td></tr>
	<tr>
		<td height="70" style="border-bottom:1px solid #e0e0e0; padding:0px 17px;">
			<table border="0" cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td><img src="images/tit_orderstep.gif" alt="" /></td>
					<td width="100%">
<?
		$createbutton="<table border=0 cellpadding=0 cellspacing=0>";
		//��ó�� �Ǵ� ��ۿ�û���̰�, ī����Ұ� �ƴϰ�, ������ �Աݰ��̰�, �Ա��� �ȵƴٸ�,,,,,
		//if(preg_match("/^(N|X)$/",$_ord->deli_gbn) && $_ord->pay_admin_proc!="C" && preg_match("/^(B){1}/",$_ord->paymethod) && strlen($_ord->bank_date)!=14) {
		//	$createbutton.="<tr><td align=right style='padding-right:40px' height=15><img src='images/ordtl_arrow1.gif' align=absmiddle></td></tr>";
		//}
		$createbutton.="<tr><td>";

		if(preg_match("/^(N|X|S)$/",$_ord->deli_gbn) && $_ord->pay_admin_proc!="C") {
			//(��ó��/��ۿ�û/�߼ۿϷ�) �ǰ� ī����� ��Ұ��� �ƴϸ�,,,,,
			//$createbutton.="<a href=\"javascript:printaddress()\"><img src=\"images/ordtl_btnprint.gif\" align=absmiddle border=0></a>\n";	//����� ���
			//$createbutton.="<font color=#C0C0C0>|</font>&nbsp;";

			if(preg_match("/^(B){1}/",$_ord->paymethod) && strlen($_ord->bank_date)!=14) {
				//������ �Ա� �������̰�, �Ա��� �ȵ� ���
				//$createbutton.="<a href=\"javascript:banksend()\"><img src=\"images/ordtl_btnbankok.gif\" align=absmiddle border=0></a><img src='images/ordtl_arrow2.gif' align=absmiddle>";	//�Ա�Ȯ��
				$createbutton.="<a href=\"javascript:banksend()\"><img src=\"images/ordtl_btnbankok.gif\" align=absmiddle border=0></a>&nbsp;";	//�Ա�Ȯ��

				//����غ� �ܰ谡 �ƴϸ�,,,,,
				if($_ord->deli_gbn!="S") {
					//$createbutton.="<a href=\"javascript:delisend('S')\"><img src=\"images/ordtl_btndeliready.gif\" align=absmiddle border=0></a><img src='images/ordtl_arrow2.gif' align=absmiddle>";	//�߼��غ�
					$createbutton.="<a href=\"javascript:alert('�Ա� ��Ȯ�� �ֹ��Դϴ�.');\"><img src=\"images/ordtl_btndeliready.gif\" align=absmiddle border=0></a>&nbsp;";	//�߼��غ� // delisend('S1') : �޼��� ���� �н�
					$createbutton.="<a href=\"javascript:alert('�Ա� ��Ȯ�� �ֹ��Դϴ�.')\"><img src=\"images/ordtl_btndeliok.gif\" align=absmiddle border=0></a>\n";	//�߼ۿϷ� // delisend('Y')
				}

			} else if(!preg_match("/^(O|Q){1}/",$_ord->paymethod) || strlen($_ord->bank_date)>=12) {	//������� �Աݰǿ� ���ؼ� �Ա��� �� ���

				//����غ� �ܰ谡 �ƴϸ�,,,,,
				if($_ord->deli_gbn!="S") $createbutton.="<td><a href=\"javascript:delisend('S')\"><img src=\"images/ordtl_btndeliready.gif\" align=absmiddle border=0></a>&nbsp;\n";	//�߼��غ�

				$createbutton.="<div id=deliescrow style=\"position:absolute; z-index:100; display:none;\">\n";
				$createbutton.="<table border=0 cellspacing=1 cellpadding=0 bgcolor=#0099CC width=250>\n";
				$createbutton.="<tr>\n";
				$createbutton.="	<td style=\"padding:10px;\">\n";
				$createbutton.="	<table border=0 cellspacing=1 cellpadding=0 bgcolor=#B9B9B9 width=250>\n";
				$createbutton.="	<form name=escrow_form1>\n";
				if(preg_match("/^(Q|P){1}/", $_ord->paymethod)) {
					$createbutton.="	<input type=hidden name=\"escrowcaltype\" value=\"Y\">\n";
				} else {
					$createbutton.="	<input type=hidden name=\"escrowcaltype\" value=\"N\">\n";
				}
				$createbutton.="	<tr bgcolor=#FFFFFF>\n";
				$createbutton.="		<td class=\"table_cell\" colspan=\"2\" align=\"center\">�� ǥ �� �� �� ��</td>\n";
				$createbutton.="	</tr>\n";
				$createbutton.="	<tr bgcolor=#FFFFFF>\n";
				$createbutton.="		<td class=\"table_cell\"><img src=\"images/icon_point5.gif\" width=\"8\" height=\"11\" border=\"0\">��۾�ü</td>\n";
				$createbutton.="		<td class=\"td_con1\"><select name=escrow_deli_com style=\"width:90; height:18; font-size:8pt;\">\n";
				$createbutton.="		<option value=\"\">����</option>\n";

				for($yy=0;$yy<count($delicomlist);$yy++) {
					if($pg_type=="B" && preg_match("/^(Q|P){1}/", $_ord->paymethod)) {
						if(strlen($delicomlist[$yy]->dacom_code)>0) {
							$createbutton.="		<option value=\"".$delicomlist[$yy]->code."\">".$delicomlist[$yy]->company_name."</option>\n";
						}
					} else {
						$createbutton.="		<option value=\"".$delicomlist[$yy]->code."\">".$delicomlist[$yy]->company_name."</option>\n";
					}
				}
				$createbutton.="		</select>\n";
				$createbutton.="		</td>\n";
				$createbutton.="	</tr>\n";
				$createbutton.="	<tr bgcolor=#FFFFFF>\n";
				$createbutton.="		<td class=\"table_cell\"><img src=\"images/icon_point5.gif\" width=\"8\" height=\"11\" border=\"0\">�����ȣ</td>\n";
				$createbutton.="		<td class=\"td_con1\"><input type=text name=escrow_deli_num value=\"\" size=13 maxlength=20 style=\"height:19;font-size:8pt\"></td>\n";
				$createbutton.="	</tr>\n";
				$createbutton.="	<tr bgcolor=#FFFFFF>\n";
				$createbutton.="		<td height=40 colspan=\"2\" align=\"center\"><a href=\"javascript:delisend('N')\"><img src=\"images/btn_ok3.gif\" border=\"0\"></a>&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"javascript:escrow_deliclose();\"><img src=\"images/ordtl_close.gif\" border=\"0\"></a></td>\n";
				$createbutton.="	</tr>\n";
				$createbutton.="	</table>\n";
				$createbutton.="	</td>\n";
				$createbutton.="</tr>\n";
				$createbutton.="</form>\n";
				$createbutton.="</table>\n";
				$createbutton.="</div>\n";
				$createbutton.="</td>\n";

				$createbutton.="<td style=padding:0px 2px;><a href=\"javascript:delisend('N')\"><img src=\"images/ordtl_btndeliok.gif\" align=absmiddle border=0></a></td>\n";	//�߼ۿϷ�
			}
		} else if($_ord->deli_gbn=="Y") {	//�߼ۿϷ�� �ǿ� ���ؼ�,,,,,,
			//$createbutton.="<a href=\"javascript:printaddress()\"><img src=\"images/ordtl_btnprint.gif\" align=absmiddle border=0></a>\n";	//����� ���
			//$createbutton.="<font color=#C0C0C0>|</font>&nbsp;";
			if(!preg_match("/^(Q|P){1}/",$_ord->paymethod)) $createbutton.="<a href=\"javascript:delicancel()\"><img src=\"images/ordtl_btnreturn.gif\" align=absmiddle border=0></a>\n";	//�ݼ�ó��
		}
		//������ �ԱݿϷ�ǿ� ���ؼ� �ֹ�����̰� ȯ���� �ȵ� ���
		if(preg_match("/^(B){1}/",$_ord->paymethod) && $_ord->deli_gbn=="C" && strlen($_ord->bank_date)>=12) {
			$createbutton.="<a href=\"javascript:bankcancel()\"><img src=\"images/ordtl_btnrefund.gif\" align=absmiddle border=0></a>\n";	//ȯ��ó��
		} else if(preg_match("/^(O){1}/",$_ord->paymethod) && $_ord->deli_gbn=="C" && $_ord->pay_admin_proc!="C") {
			$createbutton.="<a href=\"javascript:virtualcancel()\"><img src=\"images/ordtl_btnrefund.gif\" align=absmiddle border=0></a>\n";	//ȯ��ó��
		} else if(preg_match("/^(V){1}/",$_ord->paymethod) && $_ord->deli_gbn=="C" && $_ord->pay_admin_proc!="C") {
			$createbutton.="<a href=\"javascript:transcancel()\"><img src=\"images/ordtl_btnrefund.gif\" align=absmiddle border=0></a>\n";	//ȯ��ó��
		}
		//�Ÿź�ȣ �������/�ſ�ī��ǿ� ���ؼ� (�ֹ����/ȯ�Ҵ��) ���°� �ƴϸ�,,,,,
		if(preg_match("/^(Q|P){1}/",$_ord->paymethod) && !preg_match("/^(C|E)$/",$_ord->deli_gbn) && $_ord->price>0 && !preg_match("/^(Y|C)$/",$_ord->escrow_result)) {
			if(preg_match("/^(Y|D)$/",$_ord->deli_gbn) && strlen($_ord->deli_date)==14) {	//�߼ۿϷ�� ����ũ�� �������� "���꺸��" ��ư Ȱ��ȭ
				if($pg_type=="A" || $pg_type=="C" || $pg_type=="D") {
					$createbutton.="<a href=\"javascript:okhold()\"><img src=\"images/ordtl_btnescrowhold.gif\" align=absmiddle border=0></a>\n";	//���꺸��
				}
			} else {
				$createbutton.="<a href=\"javascript:okcancel('".substr($_ord->paymethod,0,1)."','".$_ord->bank_date."')\"><img src=\"images/ordtl_btnescrowcancel.gif\" align=absmiddle border=0></a>\n";	//���ó��
			}
		}

		$createbutton.="</td>\n";
		$createbutton.="<td width=\"100%\"><img src=\"images/end_orderstep.gif\" alt=\"\" /></td>";





		//�ſ�ī�� ���� ������ ���
		if(preg_match("/^(C){1}/",$_ord->paymethod) && $_ord->pay_flag=="0000") {
			if($_ord->pay_admin_proc=="N" && strcmp($_ord->pay_flag,"0000")==0) {	//���Կ�û�� �ȵ� ���
				$createbutton.="<td><a href=\"javascript:card_ask('card_ask','card');\"><img src=\"images/ordtl_btncardok.gif\" align=absmiddle border=0 hspace=2></a></td>\n";	//ī�������û
				$createbutton.="<td><a href=\"javascript:card_ask('card_cancel','card');\"><img src=\"images/ordtl_btncardcancel.gif\" align=absmiddle border=0 hspace=2></a></td>\n";	//ī�����
				//$createbutton.="&nbsp;&nbsp;&nbsp;<font color=#C0C0C0>|</font>&nbsp;";
			} else if($_ord->pay_admin_proc=="Y") {	//���Կ�û�� ���
				if(!preg_match("/^(Y|C)$/",$_ord->escrow_result)) {
					$createbutton.="<td><a href=\"javascript:card_ask('card_cancel','card');\"><img src=\"images/ordtl_btncardcancel.gif\" align=absmiddle border=0 hspace=2></a></td>\n";	//ī�����
					//$createbutton.="&nbsp;&nbsp;&nbsp;<font color=#C0C0C0>|</font>&nbsp;";
				}
			}
		//�ڵ��� ������ ���
		} else if (preg_match("/^(M){1}/",$_ord->paymethod) && $_ord->pay_flag=="0000") {

			if($_ord->pay_admin_proc=="N" && strcmp($_ord->pay_flag,"0000")==0) {
				$createbutton.="<td><a href=\"javascript:card_ask('card_cancel','hp')\"><img src=\"images/ordtl_btnpaycancel.gif\" align=absmiddle border=0 hspace=2></a></td>\n";		//�������
				//$createbutton.="&nbsp;&nbsp;&nbsp;<font color=#C0C0C0>|</font>&nbsp;";
			}

		//�ǽð�������ü/�Ϲݰ������ ȯ�Ҿȳ�
		} else if (preg_match("/^(V|O){1}/",$_ord->paymethod) && $_ord->pay_flag=="0000" && $_ord->pay_admin_proc!="C") {
			$createbutton.="<td><a href=\"javascript:card_ask('card_cancel','card');\"><img src=\"images/ordtl_btnVcancel.gif\" align=absmiddle border=0 hspace=2></a></td>\n";	//����������
		}


		if($_ord->pay_admin_proc=="Y" || (preg_match("/^(B){1}/",$_ord->paymethod) && $_ord->deli_gbn!="C" && strlen($_ord->bank_date)==14) || (preg_match("/^(V|O){1}/",$_ord->paymethod) && $_ord->pay_flag=="0000" && $_ord->deli_gbn!="C")) {
			$createbutton.="<td><a href=\"javascript:printtax()\"><img src=\"images/ordtl_btntax.gif\" align=absmiddle border=0 hspace=2></a></td>\n";	//������ �߱�
		}
		if(preg_match("/^(B|O|Q){1}/",$_ord->paymethod) && $tax_type!="N" && $_ord->deli_gbn!="C") {
			$createbutton.="<td><a href=\"javascript:get_taxsave()\"><img src=\"images/ordtl_btntaxsave.gif\" align=absmiddle border=0 hspace=2></a></td>\n";	//���ݿ����� ��û
		}



		$createbutton.="</tr>\n";
		$createbutton.="</table>\n";

		echo $createbutton;

?>
				</td>
			</tr>
		</table>
	</td>
</tr>
<tr><td height="10"></td></tr>
</table>
<?

	$deliTotSql = "SELECT * FROM tblorderproduct WHERE ordercode = '".$_ord->ordercode."' AND SUBSTR(productcode,1,3) NOT LIKE 'COU%' AND SUBSTR(productcode,1,3) NOT LIKE '999%' ";
	$deliTotResult = mysql_query($deliTotSql);
	$deliTotNums = mysql_num_rows($deliTotResult); //���� ��ǰ ����
	mysql_free_result($deliTotResult);


	$deliYesSql = "SELECT * FROM tblorderproduct WHERE ordercode = '".$_ord->ordercode."' AND SUBSTR(productcode,1,3) NOT LIKE 'COU%' AND SUBSTR(productcode,1,3) NOT LIKE '999%' AND deli_gbn = 'Y' ";
	$deliYesResult = mysql_query($deliYesSql);
	$deliYesNums = mysql_num_rows($deliYesResult); // ��۵� ��ǰ ��

	mysql_free_result($deliYesResult);

	?>
<table cellpadding="0" cellspacing="0" border="0" width="96%" align="center">
<!-- ��� ó���� ���� Msg -->
<? if($_ord->deli_gbn != "Y" && ($deliTotNums > 0 && ($deliTotNums == $deliYesNums))){ ?>
	<tr>
		<td style="padding:5px 17px; background-color:#f5f5f5;">
			<span style="font-size:11px; color:#ff5500; font-weight:bold; letter-spacing:-1px;">
			�� ��� ��ǰ�� ��۵Ǿ����� <u>ó���Ϸ� ���� ���� �ֹ���</u> �Դϴ�.<br />
			�� <u>�߼ۿϷ� ó���� �ϼž߸�</u> ������ ���� �� ����Ȯ��, ����ó���� �̷�����ϴ�.
			</span>
		</td>
	</tr>
	<tr><td height="20"></td></tr>
<? } ?>
<tr>
	<td>
		<form name=form1 method=post action="<?=$_SERVER[PHP_SELF]?>">
			<input type=hidden name=type>
			<input type=hidden name=mode>
			<input type=hidden name=ordercode value="<?=$_ord->ordercode?>">
			<input type=hidden name=vender>
			<input type=hidden name=productcode>
			<input type=hidden name=opt1_name>
			<input type=hidden name=opt2_name>
			<input type=hidden name=quantity>
			<input type=hidden name=reserve>
			<input type=hidden name=price>
			<input type=hidden name=arquantity>
			<input type=hidden name=arreserve>
			<input type=hidden name=arprice>
		<table border=0 cellpadding=0 cellspacing=0 width=100%>
			
			<tr>
				<td>
					<table border=0 cellpadding=0 cellspacing=0>
						<tr>
							<td><img src="images/tit_endtrans.gif" alt="" /></td>
							<td>
							<?

						$deliStep = array(
							'S'=>"��۴��(�߼��غ�)",
							'Y'=>"���(�����ȣ)"
						); ?>
						<select name="deli_com" id="deli_com" style="width:90;height:18;font-size:8pt; display:inline;">
							<option value="">����</option>
						<?
						for($yy=0;$yy<count($delicomlist);$yy++) {
							if($pg_type=="B" && preg_match("/^(Q|P){1}/", $_ord->paymethod)) {
								if(strlen($delicomlist[$yy]->dacom_code)>0) {
									echo "		<option value=\"".$delicomlist[$yy]->code."\">".$delicomlist[$yy]->company_name."</option>\n";
								}
							} else {
								echo "		<option value=\"".$delicomlist[$yy]->code."\">".$delicomlist[$yy]->company_name."</option>\n";
							}
						} ?>
						</select>
							</td>
							<td style="padding-left:2px">
								<input type=text name=deli_num id=deli_num value="" size=20 maxlength=20 style="height:19;font-size:8pt; display:inline;" onkeyup="strnumkeyup(this)">
							</td>
							<td style="padding-left:2px">
								<input type=button value='���' style="cursor:hand;color:#FFFFFF;border-color:#666666;background-color:#666666;font-size:8pt;font-family:Tahoma;height:18px;width:50" onclick="changeDeliinfo()">
							</td>
						</tr>
					</table>
				</td>
				<td align="right">
					<table border="0" cellpadding="0" cellspacing="0">
						<tr>
							<td><img src="images/tit_ordersort.gif" alt="" /></td>
							<td>
								<select name=sort style="width:90px; height:18px; font-size:8pt;" onChange="Sort(this.value);">
									<?
										if($_shopdata->ETCTYPE["SELFCODEVIEW"]=="Y" || $_shopdata->ETCTYPE["SELFCODEVIEW"]=="Z" || $_shopdata->ETCTYPE["SELFCODEVIEW"]=="M" || $_shopdata->ETCTYPE["SELFCODEVIEW"]=="N") {
											if(!isset($sort)) {
												$sort="selfcode";
											}
										echo "<option value=\"selfcode\" ".($sort=="selfcode"?"selected":"").">�����ڵ�</option>\n";
										}
									?>
									<option value="" <?=(strlen($sort)==0?"selected":"")?>>��ٱ���</option>
									<option value="productname" <?=($sort=="productname"?"selected":"")?>>��ǰ��</option>
									<option value="price desc" <?=($sort=="price desc"?"selected":"")?>>����</option>
								</select>
							</td>
							<?if($_ord->paymethod=="B" && $mode!="update"){?>
							<!--
							<td style="padding-left:2px;"><a href="javascript:ChangeUpdateMode('update')"><img src="images/ordtl_btnpriceup.gif" align=absmiddle border=0></a></td>
							-->
							<?}?>
						</tr>
					</table>
				</td>
			</tr>
		</table>
	</td>
</tr>
<tr><td height="10"></td></tr>
<tr>
	<td align="center">
	<!-- �ֹ����� ���� -->
	<table border=0 cellpadding=0 cellspacing=1 width=100% bgcolor="#d8d8d8">
	<colgroup>
		<col width=25>
		<?if($vendercnt>0){?>
		<col width=55>
		<?}?>
		<col width=55>
		<col>
		<col width=95>
		<col width=28>
		<col width=45>
		<col width=55>
		<?=($_ord->paymethod=="B" && $mode=="update"?"<col width=30>\n":"")?>
	</colgroup>
	<tr bgcolor=#efefef>
		<td align=center class="page_print">No</td>
		<td align=center><input type=checkbox name=allcheck onClick="CheckAll()"></td>
		<?if($vendercnt>0){?>
		<td align=center style="font-size:11px; font-weight:bold;">������ü</td>
		<?}?>
		<td align=center style="font-size:11px; font-weight:bold;">ó������</td>
		<td align=center style="font-size:11px; font-weight:bold;">��ǰ��</td>
		<td align=center style="font-size:11px; font-weight:bold;">���û���</td>
		<td align=center style="font-size:11px; font-weight:bold;">����</td>
		<td align=center style="font-size:11px; font-weight:bold;">������</td>
		<td align=center style="font-size:11px; font-weight:bold;">����</td>
		<?=($_ord->paymethod=="B" && $mode=="update"?"<td>&nbsp;</td>\n":"")?>
	</tr>

	<input type=hidden name=chkprcode>
	<input type=hidden name=chkdeli_com>
	<input type=hidden name=chkdeli_num>
	<input type=hidden name=prcodes>

<?
	$colspan=7;
	if($vendercnt>0) $colspan++;

	$sql = "SELECT r.*,t.*, (select t1.tax_yn from tblproduct t1 where t1.productcode = t.productcode limit 0, 1 ) as tax_yn FROM tblorderproduct t left join rent_schedule r using (ordercode,basketidx) WHERE ordercode='".$_ord->ordercode."' ";
	if(strlen($sort)>0) $sql.=" ORDER BY ".$sort.",SUBSTR( productcode, 1, 3 ) >  '888' ";
	else $sql.="ORDER BY SUBSTR( productcode, 1, 3 ) >  '888' , vender ASC";

	//
	$groupdiscount_Percent_result=mysql_query($sql,get_db_conn());
	while($groupdiscount_Percent_row=mysql_fetch_object($groupdiscount_Percent_result)) {
		if ($groupdiscount_Percent_row->productcode != '99999999995X') {
			$totalpriceTemp+=$groupdiscount_Percent_row->quantity*$groupdiscount_Percent_row->price;
		}
	}
	$groupdiscount_Percent = round ( 100 - ( 100 * ( $_ord->price / $totalpriceTemp ) ) ); //ȸ���׷�(�߰�)���� ���� %

	$result=mysql_query($sql,get_db_conn());
	$sumquantity=0;
	$totalprice=0;
	$in_reserve=0;
	$cnt=0;
	$taxsaveprname="";
	$arTotalSns = null;
	$totalRecom = 0;
	$sell_memid="";
	$sell_memid_reserve="";
	
	while($row=mysql_fetch_object($result)) {

		//ȯ�� �����ݾ��հ�(��ǰ�� ������ �ջ�);
		$refund_subPrice += ($row->price*$row->quantity*$_ord->refund_commi/100);

		$csManager = "";
		if($row->vender>0) {
			$csManager = "<input type=button value='CS����' style=\"cursor:hand;color:#FFFFFF;border-color:#666666;background-color:#666666;font-size:8pt;font-family:Tahoma; height:18px; width:50px\" onclick=\"csManagerPop('".$_ord->ordercode."','".$row->productcode."','".$row->vender."'); return false;\">";
		}
		
		if(!preg_match('/^[0-8]{1}[0-9]{2}$/',substr($row->productcode,0,3)) && substr($row->productcode,-4,4) != 'GIFT'){

			if ($row->productcode=='99999999995X') {
				$refund_data[]=$row;
			}else{
				$etcdata[]=$row;
			}
			continue;
		} else {															#��¥��ǰ
			$prdata[]=$row;
		}

		$taxsaveprname.=$row->productname.",";
		$cnt++;
		$sumprice=$row->quantity*$row->price;
		$reserve=$row->quantity*$row->reserve;
		$tempopt1=$row->opt1_name;
		if($row->productcode=="99999999999R") $norecan="Y";
		if ($row->productcode!="99999999999X" && substr($row->productcode,0,3)!="COU" && $row->productcode!="99999999999R") {
			$sumquantity+=$row->quantity;
		}
		if($row->status!='RC') $in_reserve+=$reserve;
		$totalprice+=$sumprice;

		
		$optvalue="";
		if(!_isInt($row->optidx)){
			if(ereg("^(\[OPTG)([0-9]{3})(\])$",$row->opt1_name)) {
				$optioncode=$row->opt1_name;
				$row->opt1_name="";
				$sql = "SELECT opt_name FROM tblorderoption WHERE ordercode='".$_ord->ordercode."' AND productcode='".$row->productcode."' ";
				$sql.= "AND opt_idx='".$optioncode."' ";
				$result2=mysql_query($sql,get_db_conn());
				if($row2=mysql_fetch_object($result2)) {
					$optvalue=$row2->opt_name;
				}
				mysql_free_result($result2);
			}
	
			$assemblestr = "";
			$packagestr = "";
			if(($_ord->paymethod!="B" || $mode!="update") && strlen(str_replace("","",str_replace(":","",str_replace("=","",$row->assemble_info))))>0) {
				$assemble_infoall_exp = explode("=",$row->assemble_info);
	
				if($row->package_idx>0 && strlen(str_replace("","",str_replace(":","",$assemble_infoall_exp[0])))>0) {
					$package_info_exp = explode(":", $assemble_infoall_exp[0]);
	
					$package_productcode_exp = explode("", $package_info_exp[0]);
					$package_productname_exp = explode("", $package_info_exp[1]);
					$package_sellprice = $package_info_exp[2];
					$package_packagename = $package_info_exp[3];
	
					if(count($package_info_exp)>2 && strlen($package_packagename)>0) {
						$packagestr.="	<table border=0 width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">\n";
						$packagestr.="	<tr>\n";
						$packagestr.="		<td colspan=\"2\" style=\"word-break:break-all;font-size:8pt;\"><font color=green><b>[</b>��Ű������ : ".$package_packagename."<b>]</b></font></td>\n";
						$packagestr.="	</tr>\n";
						if(strlen(str_replace("","",$package_info_exp[1]))>0) {
							$packagestr.="	<tr>\n";
							$packagestr.="		<td width=\"30\" valign=\"top\" nowrap><font color=\"#008000\" style=\"line-height:10px;\">��<br>����</font></td>\n";
							$packagestr.="		<td width=\"100%\" bgcolor=\"#DDDDDD\">\n";
							$packagestr.="		<table width=\"100%\" cellpadding=\"0\" cellspacing=\"1\">\n";
							$packagestr.="		<col width=\"\">\n";
							$packagestr.="		<col width=\"55\">\n";
							for($k=0; $k<count($package_productname_exp); $k++) {
								if($k==0) {
									$packagestr.="		<tr bgcolor=\"#FFFFFF\">\n";
									$packagestr.="				<td style=\"padding-left:4px;padding-right:4px;word-break:break-all;font-size:8pt;\">".$package_productname_exp[$k]."&nbsp;<span class=\"page_screen\"><a href=\"javascript:ProductInfo('".substr($package_productcode_exp[$k],0,12)."','".$package_productcode_exp[$k]."','YES')\"><img src=images/ordtl_icnnewwin.gif align=absmiddle border=0 vspace=\"1\"></a></span></td>\n";
									$packagestr.="				<td rowspan=\"".count($package_productname_exp)."\" align=\"right\" style=\"padding-left:4px;padding-right:4px;font-size:8pt;\">".number_format((int)$package_sellprice)."</td>\n";
									$packagestr.="		</tr>\n";
								} else {
									$packagestr.="		<tr bgcolor=\"#FFFFFF\">\n";
									$packagestr.="				<td style=\"padding-left:4px;padding-right:4px;word-break:break-all;font-size:8pt;\">".$package_productname_exp[$k]."&nbsp;<span class=\"page_screen\"><a href=\"javascript:ProductInfo('".substr($package_productcode_exp[$k],0,12)."','".$package_productcode_exp[$k]."','YES')\"><img src=images/ordtl_icnnewwin.gif align=absmiddle border=0 vspace=\"1\"></a></span></td>\n";
									$packagestr.="		</tr>\n";
								}
							}
	
							$packagestr.="		</table>\n";
							$packagestr.="		</td>\n";
							$packagestr.="	</tr>\n";
						}
						$packagestr.="	</table>\n";
					}
					@mysql_free_result($alproresult);
				}
	
				if($row->assemble_idx>0 && strlen(str_replace("","",str_replace(":","",$assemble_infoall_exp[1])))>0) {
					$assemblestr.="	<table border=0 width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">\n";
					$assemblestr.="	<tr height=\"2\"><td></td></tr>\n";
					$assemblestr.="	<tr>\n";
					$assemblestr.="		<td width=\"30\" valign=\"top\" nowrap><font color=\"#FF7100\" style=\"line-height:10px;\">��<br>����</font></td>\n";
					$assemblestr.="		<td width=\"100%\" bgcolor=\"#DDDDDD\">\n";
					$assemblestr.="		<table width=\"100%\" cellpadding=\"0\" cellspacing=\"1\">\n";
	
					$assemble_info_exp = explode(":", $assemble_infoall_exp[1]);
	
					if(count($assemble_info_exp)>2) {
						$assemble_productcode_exp = explode("", $assemble_info_exp[0]);
						$assemble_productname_exp = explode("", $assemble_info_exp[1]);
						$assemble_sellprice_exp = explode("", $assemble_info_exp[2]);
	
						for($k=0; $k<count($assemble_productname_exp); $k++) {
							$assemblestr.="		<colgroup>\n";
							$assemblestr.="			<col width=\"\">\n";
							$assemblestr.=" 		<col width=\"55\">\n";
							$assemblestr.="		</colgroup>\n";
							$assemblestr.="		<tr bgcolor=\"#FFFFFF\">\n";
							$assemblestr.="				<td style=\"padding-left:4px;padding-right:4px;word-break:break-all;font-size:8pt;\">".$assemble_productname_exp[$k]."&nbsp;<span class=\"page_screen\"><a href=\"javascript:ProductInfo('".substr($assemble_productcode_exp[$k],0,12)."','".$assemble_productcode_exp[$k]."','YES')\"><img src=images/ordtl_icnnewwin.gif align=absmiddle border=0></a></span></td>\n";
							$assemblestr.="				<td align=\"right\" style=\"padding-left:4px;padding-right:4px;font-size:8pt;\">".number_format((int)$assemble_sellprice_exp[$k])."</td>\n";
							$assemblestr.="		</tr>\n";
						}
					}
					@mysql_free_result($alproresult);
					$assemblestr.="		</table>\n";
					$assemblestr.="		</td>\n";
					$assemblestr.="	</tr>\n";
					$assemblestr.="	</table>\n";
				}
			}
		}


		// ��� ���� ����Ʈ ���� -----------------------------------------------------
		$ordercouponView = "";
		$couponDcPrice = 0;
		$ordercouponSQL = "
			SELECT
				o.*,
				c.coupon_name,
				c.sale_money,
				c.sale_type
			FROM
				`tblordercoupon` AS o
				LEFT JOIN `tblcouponinfo` AS c ON o.couponcode = c.coupon_code
			WHERE `ordercode` = '".$_ord->ordercode."' AND `orderPuid`='".$row->uid."' ; ";
		$ordercouponResult = mysql_query($ordercouponSQL,get_db_conn());
		while( $ordercouponRow = mysql_fetch_assoc ( $ordercouponResult ) ){
			//_pr($ordercouponRow);
			$sale_typeA = ( $ordercouponRow['sale_type'] <= 2 ? "%" : "��" );
			$sale_typeB = ( $ordercouponRow['sale_type'] %2 == 0 ? "����" : "����" );
			$couponPrice = ( $ordercouponRow['sale_type'] %2 == 0 ? $ordercouponRow['dcPrice'] : $ordercouponRow['reserve'] );

			$couponDcPrice += $ordercouponRow['dcPrice'];

			// ��¿�

			$ordercouponView .= "<DIV style=\"border-top:solid #DDDDDD 1px;  padding:5px;\">";
			$ordercouponView .= $ordercouponRow['coupon_name'];
			$ordercouponView .= " - ";
			$ordercouponView .= number_format($ordercouponRow['sale_money']).$sale_typeA .$sale_typeB."���� ��� : ".number_format($couponPrice)."�� ".$sale_typeB;
			$ordercouponView .= "</DIV>";

		}
		// ��� ���� ����Ʈ �� ----------------------------------------------------- //

		$couponDcPrice += $sumprice - ( round( ( $sumprice - ( $sumprice / 100 ) * $groupdiscount_Percent ) / 100 ) * 100 ) ;


		echo "<tr bgcolor=#FFFFFF>\n";

		echo "	<td align=center class=\"page_print\" style=\"font-size:8pt\"><font color=#878787>".$cnt."</td>\n";
		echo "	<td align=center class=\"page_screen\" style=\"font-size:8pt\">";
		if($row->productcode!='99999990GIFT') {
			echo "<input type=checkbox name=chkprcode value=\"".$row->productcode."\" uid=\"".$row->uid."\" statuss=\"".$row->status."\" price=\"".$sumprice."\" dcPrice=\"".$couponDcPrice."\">";

		echo "<input type=\"hidden\" name=\"chkprcode_uid\" value=\"".$row->uid."\" style=\"display:none\" />";
		echo "<input type=\"hidden\" name=\"chkprcode_statuss\" value=\"".$row->status."\" style=\"display:none\" />";
		echo "<input type=\"hidden\" name=\"chkprcode_price\" value=\"".$sumprice."\" style=\"display:none\" />";
		}

		echo "&nbsp;</td>\n";

		if($vendercnt>0) {
			$classred1=$classred2 = '';

			if($row->vender>0) {
				echo "	<td align=center style=\"font-size:8pt\"><a href=\"javascript:viewVenderInfo(".$row->vender.")\"><B>".$venderlist[$row->vender]->id."</B></a></td>\n";
			} else {
				echo "	<td align=center>&nbsp;</td>\n";
			}
		}

		if($row->status) {
				echo "	<td align=center style=\"color:red\">";
				switch($row->status) {					
					case 'RA':
						echo "ȯ�ҽ�û";

						$sql = "select requestor from part_cancel_want where uid='".$row->uid."'";
						$result2=mysql_query($sql,get_db_conn());
						if($row2=mysql_fetch_object($result2)) {

							if ($row2->requestor ==1) {
								 echo "<br/><span style=\"color:blue\">(��)</span>";
							}
						}
						mysql_free_result($result2);


						break;
					case 'RB': echo "ȯ������";  break;
					case 'RC': echo "ȯ�ҿϷ�";  break;
				}
				echo "</td>";
				$classred1 = "<font style='color:red'>";
				$classred2 = "</font>";
		}else{
			echo "	<td align=center></td>";
		}

		if(file_exists($Dir.DataDir."shopimages/product/".$row->productcode."3.gif")) $file=$row->productcode."3.gif";
		else if(file_exists($Dir.DataDir."shopimages/product/".$row->productcode."3.jpg")) $file=$row->productcode."3.jpg";
		else $file="NO";

		// ü�� ��ǰ ǥ�ø� ����
		if(substr($row->productcode,0,3) == '899') $row->productname = '[ü���ǰ]'.$row->productname;
	//	else $row->productname = ($row->rental == '2')?'[Rental]':''.$row->productname;// ��Ż ǥ��

		if($file!="NO") {

			//�̸����� �̹���
			$prImg = ( is_file($Dir.DataDir."shopimages/product/".$file) ) ? "<img name=bigimgs src=\"".$Dir.DataDir."shopimages/product/".$file."\" width=\"60\" align=\"left\" border=\"5\">" : "";

			echo "	<td style=\"font-size:8pt;padding:7,7,5,5;line-height:10pt\">\n";
			echo "	".(strlen($row->selfcode)?"�����ڵ� : ".$row->selfcode."<br>":"")."<span style=\"line-height:10pt\" onMouseOver='ProductMouseOver($cnt)' onMouseOut=\"ProductMouseOut('primage".$cnt."');\">".$prImg.$classred1.$row->productname.$classred2."";
			echo "	<span class=\"page_screen\"><a href=\"javascript:ProductInfo('".substr($row->productcode,0,12)."','".$row->productcode."','YES')\"><img src=images/ordtl_icnnewwin.gif align=absmiddle border=0></a></span>";
			echo "</span>\n";
			echo "	<div id=primage".$cnt." style=\"position:absolute; z-index:100; visibility:hidden;\">\n";
			echo "	<table border=0 cellspacing=1 cellpadding=0 bgcolor=#000000 width=170>\n";
			echo "	<tr bgcolor=#FFFFFF>\n";
			echo "		<td align=center width=100% height=150><img name=bigimgs src=\"".$Dir.DataDir."shopimages/product/".$file."\"></td>\n";
			echo "	</tr>\n";
			echo "	<tr bgcolor=#FFFFFF>\n";
			echo "		<td height=54 bgcolor=#f5f5f5><table border=0><tr><td style=\"line-height:12pt\">���� �ֹ���,����/�̵� ��ǰ�� �̹����� ��ġ���� ������ ������ <font color=red>�����Ͽ� ���</font>�ٶ��ϴ�.</td></tr></table></td>\n";
			echo "	</tr>\n";
			echo "	</table>\n";
			echo "	</div>\n";
			if(strlen($row->addcode)>0) echo "<br><font color=blue><b>[</b>Ư��ǥ�� : ".$row->addcode."<b>]</b></font>";
			if(strlen($optvalue)>0) echo "<br><font color=red><b>[</b>�ɼǻ��� : ".$optvalue."<b>]</b></font>";

			// �뿩 ��ǰ
			if(_isInt($row->optidx)){
				echo "<br>".$row->opt1_name."<font color='0000FF'><strong>[�Ⱓ : ".$row->start." ~ ".$row->end."]</strong></font>";
				if(!empty($row->longdiscount)) echo '<br><span style="color:red">���뿩���� : '.number_format($row->longdiscount).'��</span>';
			}

			echo $packagestr;
			echo $assemblestr;


			// �ֹ� ��ǰ ���� �ڵ� echo $row->uid;


			// �ֹ� ��ǰ�� ����� ��ǰ�� ����
			echo "<div>".$ordercouponView."</div>";



			echo "	</td>\n";
		} else {
			echo "	<td style=\"font-size:8pt;padding:2,5;line-height:10pt\">";
			echo (strlen($row->selfcode)?"�����ڵ� : ".$row->selfcode."<br>":"").$classred1.$row->productname.$classred2;
			if(strlen($row->addcode)>0) echo "<br><font color=blue><b>[</b>Ư��ǥ�� : ".$row->addcode."<b>]</b></font>";
			if(strlen($optvalue)>0) echo "<br><font color=red><b>[</b>�ɼǻ��� : ".$optvalue."<b>]</b></font>";
			echo $packagestr;
			echo $assemblestr;

			if($row->productcode=='99999990GIFT'){
				echo "<br>����û���� : {$row->assemble_info}";
			}
			echo "	</td>\n";
		}

		echo "	<td style=\"font-size:8pt;padding:2,5;line-height:11pt\">";
		if(!_isInt($row->optidx)){
			echo (strlen($row->opt1_name)>0?$classred1.$row->opt1_name.$classred2."<br>":"&nbsp;");
			echo (strlen($row->opt2_name)>0?$classred1.$row->opt2_name.$classred2."<br>":"&nbsp;");
			echo (strlen($row->opt3_name)>0?$row->opt3_name."<br>":"&nbsp;");
			if(strlen($row->opt4_name)>0) echo $row->opt4_name;
		}
		echo "	</td>\n";
		if ($row->productcode=="99999999999X" || substr($row->productcode,0,3)=="COU" || $row->productcode=="99999999999R") { // ���ݰ����� ����ǥ�þ���
			echo "	<td><input type=hidden name=arquantity value=\"1\">&nbsp;</td>\n";
		} else {
			echo "	<td align=center style=\"font-size:8pt\" ".($_ord->paymethod!="B" || $mode!="update"?($row->quantity>1?" bgcolor=#FDE9D5><font color=#000000><b>":">").$classred1.$row->quantity.$classred2:"><input type=text style='text-align:right' name=arquantity value=\"".$row->quantity."\" style=\"font-size:8pt;width:100%\">")."</td>\n";
		}
		echo "	<td align=right style=\"font-size:8pt\">".(strlen($_ord->ordercode)==20 && substr($_ord->ordercode,-1)!="X" && substr($row->productcode,-4)!="GIFT"?($_ord->paymethod!="B" || $mode!="update"?$classred1.number_format($reserve).$classred2."&nbsp;":"<input type=text style='font-size:8pt;text-align:right;width:100%' name=arreserve value=\"".$reserve."\">"):"<input type=hidden name=arreserve>&nbsp;")."</td>\n";
		echo "	<td align=right style=\"font-size:8pt\">".(substr($row->productcode,-4)!="GIFT"?$_ord->paymethod!="B" || $mode!="update"?$classred1.number_format($sumprice).$classred2."&nbsp;":"<input type=text style='font-size:8pt;text-align:right;width:100%' name=arprice value=\"".$sumprice."\">":"&nbsp;<input type=hidden name=arprice>");

		if ($row->tax_yn =="1") {
			echo "<br/><span style=\"color:red;\">(�����)</span>";
		}

		echo "</td>\n";


		if($_ord->paymethod=="B" && $mode=="update") {
			echo "<td align=center><a href=\"javascript:OrderUpdate('1',".$cnt.",'".$row->vender."','".$row->productcode."','".$tempopt1."','".$row->opt2_name."')\"><img src='images/ordtl_miniup.gif' align=absmiddle border=0 alt='��ǰ����'></a><br><img width=0 height=2 border=0><br><a href=\"javascript:OrderDelete('1','".$row->vender."','".$row->productcode."','".$tempopt1."','".$row->opt2_name."')\"><img src='images/ordtl_minidel.gif' align=absmiddle border=0 alt='��ǰ����'></a></td>\n";
		}
		echo "</tr>\n";

		if($_ord->paymethod!="B" || $mode!="update") {
			echo "<tr bgcolor=#FFFFFF style=\"font-size:8pt\">\n";
			echo "	<td align=right style=\"padding:2,5\" colspan=".($_ord->paymethod!="B" || $mode!="update"?$colspan:($colspan+1)).">\n";
			if($row->productcode!='99999990GIFT') {
				echo "	<table border=0 cellpadding=0 cellspacing=0>\n";
				echo "	<tr>\n";
				echo "		<td style=\"padding-left:20\" style=\"font-size:8pt;letter-spacing:-0.5pt;\">".$csManager." &nbsp;&nbsp;&nbsp; ��۾�ü : \n";
				echo "		<span class=\"page_screen\">\n";
				$deli_url="";
				$trans_num="";
				$company_name="";
				for($yy=0;$yy<count($delicomlist);$yy++) {
					if($pg_type=="B" && preg_match("/^(Q|P){1}/", $_ord->paymethod)) {
						if(strlen($delicomlist[$yy]->dacom_code)>0) {
							//echo "		<option value=\"".$delicomlist[$yy]->code."\"";
							if($row->deli_com>0 && $row->deli_com==$delicomlist[$yy]->code) {
								//echo " selected";
								$deli_url=$delicomlist[$yy]->deli_url;
								$trans_num=$delicomlist[$yy]->trans_num;
								$company_name=$delicomlist[$yy]->company_name;
							}
							echo ">".$delicomlist[$yy]->company_name."</option>\n";
						}
					} else {
						//echo "		<option value=\"".$delicomlist[$yy]->code."\"";
						if($row->deli_com>0 && $row->deli_com==$delicomlist[$yy]->code) {
							//echo " selected";
							$deli_url=$delicomlist[$yy]->deli_url;
							$trans_num=$delicomlist[$yy]->trans_num;
							$company_name=$delicomlist[$yy]->company_name;
						}
						//echo ">".$delicomlist[$yy]->company_name."</option>\n";
					}
				}
				echo (strlen($company_name)>0?$company_name:"����");
				echo "		</span>\n";

				echo "		<span class=\"page_print\">".(strlen($company_name)>0?$company_name:"����")."</span>\n";
				echo "		</td>\n";
				echo "		<td style=\"font-size:8pt; letter-spacing:-0.5pt; padding-left:5px; padding-right:5px;\">�����ȣ : \n";
				echo "		<span class=\"page_screen\">\n";
				echo "		".(strlen($row->deli_num)>0?$row->deli_num:"����")."<img width=2 height=0>";
				if(strlen($row->deli_num)>0 && strlen($deli_url)>0) {
					if(strlen($trans_num)>0) {
						$arrtransnum=explode(",",$trans_num);
						$pattern=array("(\[1\])","(\[2\])","(\[3\])","(\[4\])");
						$replace=array(substr($row->deli_num,0,$arrtransnum[0]),substr($row->deli_num,$arrtransnum[0],$arrtransnum[1]),substr($row->deli_num,$arrtransnum[0]+$arrtransnum[1],$arrtransnum[2]),substr($row->deli_num,$arrtransnum[0]+$arrtransnum[1]+$arrtransnum[2],$arrtransnum[3]));
						$deli_url=preg_replace($pattern,$replace,$deli_url);
					} else {
						$deli_url.=$row->deli_num;
					}
					echo "</td><td><input type=button value='����' style=\"cursor:hand;color:#FFFFFF;border-color:#666666;background-color:#666666;font-size:8pt;font-family:Tahoma;height:18px;width:30\" onclick=\"DeliSearch('".$deli_url."')\">";
				} else {
					echo "</td><td><input type=button value='����' style=\"cursor:hand;color:#FFFFFF;border-color:#666666;background-color:#666666;font-size:8pt;font-family:Tahoma;height:18px;width:30\">";
				}
				echo "		</span></td>\n";
				echo "		<td><span class=\"page_print\">".(strlen($row->deli_num)>0?$row->deli_num:"����")."</span></td>\n";
				echo "		<td style=\"padding-left:10px; font-size:8pt; letter-spacing:-0.5pt;\">\n";
				echo "	��ۻ��� : <B>";

				if($row->status == 'RC' ) {
					echo "<font color=red>ȯ�ҿϷ�</font>";
				}else{
					switch($row->deli_gbn) {
						case 'S': echo "�߼��غ�";  break;
						case 'X': echo "��ۿ�û";  break;
						case 'Y': echo "<font color=blue>���</font>";  break;
						case 'D': echo "<font color=blue>��ҿ�û</font>";  break;
						case 'W': echo "<font color=blue>���öȸ ��û</font>"; break;
						case 'N': echo "��ó��";  break;
						case 'E': echo "<font color=red>ȯ�Ҵ��</font>";  break;
						case 'C': echo "<font color=red>�ֹ����</font>";  break;
						case 'R': echo "�ݼ�";  break;
						case 'H': echo "���(<font color=red>���꺸��</font>)";  break;
					}
					if($row->deli_gbn=="D" && strlen($row->deli_date)==14) echo " (���)";
				}

				echo "	</B>";
				echo "		</td>\n";
				echo "	</tr>\n";
				echo "	</table>\n";
			}
			echo "	</td>\n";
			echo "</tr>\n";
		}

		$sql = "SELECT sns_reserve1,sns_reserve1_type,first_reserve,first_reserve_type FROM tblproduct WHERE productcode='".$row->productcode."'";
		$result2=mysql_query($sql,get_db_conn());
		if($row2=mysql_fetch_object($result2)) {
			$sns_reserve1=$row2->sns_reserve1;
			$sns_reserve1_type=$row2->sns_reserve1_type;
			$first_reserve=$row2->first_reserve;
			$first_reserve_type=$row2->first_reserve_type;
		}
		mysql_free_result($result2);


		//��õ�� ȸ������ �� ���Ž� ������
		if($recom_ok =="Y" && $arRecomType[0] == "B"){
			if($arRecomType[1] =="A"){
				if($arRecomType[2] == "N"){
					$totalRecom += $recom_memreserve;
				}else{
					$totalRecom += getReserveConversion($recom_memreserve,$arRecomType[2],$sumprice,"N");
				}
			}else{
				$totalRecom += getReserveConversion($first_reserve,$first_reserve_type,$sumprice,"N");
			}
		}

		//sns ȫ��id
		if(strlen($row->sell_memid)>0 && $sns_ok =="Y" ){
			if($arSnsType[0] == "A"){
				$tempSns =getReserveConversion($sns_recomreserve,$arSnsType[1],$sumprice,"N");
			}else if($arSnsType[0] == "B"){
				$tempSns =getReserveConversion($sns_reserve1,$sns_reserve1_type,$sumprice,"N");
			}else if($arSnsType[0] == "N"){
				$tempSns =0;
			}
			//echo $sns_recomreserve."==".$sns_reserve1_type."==".$sumprice."==".$row->sell_memid."===========".$tempSns."<br>";
			$arTotalSns[$row->sell_memid] += $tempSns;
		}
	}
	mysql_free_result($result);
		echo "</table>\n";


	if($mode!="update") {
		echo "	<table border=0 cellpadding=0 cellspacing=0 width=100%>\n";
		echo "	<tr>\n";
		if(preg_match("/^(N|X|S)$/",$_ord->deli_gbn) && $_ord->pay_admin_proc!="C" && $prescd=="Y") {
			echo "	<td><img src=images/tit_orderstepedit.gif border=0></td>\n";
			echo "	<td><select name=deli_gbn style=\"height:18; font-size:8pt;\" onchange=\"changeDeli(this)\">\n";
			echo "	<option value=\"\">ó������ ����</option>\n";
			echo "	<option value=\"N\">��ó��</option>\n";
			echo "	<option value=\"S\">�߼��غ�</option>\n";
			echo "	<option value=\"Y\">�߼ۿϷ�</option>\n";

			echo "	</select></td>\n";
			echo "	<td width=100%><img src=\"images/end_orderstep.gif\" alt=\"\" /></td>";
		}
		if($_ord->deli_gbn=='Y' || $_ord->deli_gbn=='H') {
		}
		echo "	</tr>\n";
		echo "	</table>\n";
	}
?>
<!--��� ó���� ���� Msg -->
<?if($_ord->deli_gbn != "Y" && ($deliTotNums > 0 && ($deliTotNums == $deliYesNums))){?>
	<table cellpadding="0" cellspacing="0" border="0" width="100%">
		<tr><td height="20"></td></tr>
		<tr>
			<td style="padding:5px 17px; background-color:#f5f5f5;">
				<span style="font-size:11px; color:#ff5500; font-weight:bold; letter-spacing:-1px;">
				�� ��� ��ǰ�� ��۵Ǿ����� <u>ó���Ϸ� ���� ���� �ֹ���</u> �Դϴ�.<br />
				�� <u>�߼ۿϷ� ó���� �ϼž߸�</u> ������ ���� �� ����Ȯ��, ����ó���� �̷�����ϴ�.
				</span>
			</td>
		</tr>
		<tr><td height="20"></td></tr>
	<table>
<?}?>

<? if($_ord->deli_gbn=="D" || $_ord->deli_gbn=="C"){?>
	
	<h3>* ��һ���</h3>
	<div class="cancelReason">
		<ul>
			<li class="title">���û��� : </li>
			<li class="inputArea">
				<?=$_ord->cancel_reason?>
			</li>
			<li class="title">�󼼻��� : </li>
			<li class="inputArea">
				<?=$_ord->cancel_detail?>
			</li>
		</ul>
	</div>
	<h3>* ȯ�� �����ݾ�</h3>
	<article class="refundInfo">
		<ul>
			<li class="grey">
				<div class="rInfoTop">
					<h4>�����ݾ� �հ�</h4>
					<h3><?=number_format($sumprice)?>��</h3>
				</div>
				<div class="rInfoBottom">
					<dl>
						<dt>��ǰ�ݾ�</dt><dd><?=number_format($_ord->price-$_ord->deli_price)?>��</dd>
						<dt>��ۺ�</dt><dd><?=number_format($_ord->deli_price)?>��</dd>
						<dt>����Ʈ</dt><dd><?=number_format($_ord->reserve)?>��</dd>
					</dl>
				</div>
			</li>
			<li class="grey">
				<div class="rInfoTop">
					<h4>ȯ������ ����</h4>
					<h3><?=number_format($refund_subPrice+$_ord->deli_price)?>��</h3>
				</div>
				<div class="rInfoBottom">
					<dl>
						<dt>�Ǹ��� ȯ������</dt><dd><?=number_format($refund_subPrice)?></dd>
						<dt>ȯ�ұ� ������ </dt><dd><?=$_ord->refund_commi?>%</dd>
						<dt>��ۺ�</dt><dd><?=number_format($_ord->deli_price)?>��</dd>
					</dl>
				</div>
			</li>
			<li>
				<?
				$refundPrice = ($sumprice - $refund_subPrice - $_ord->deli_price);
				if($refundPrice-$_ord->reserve>0){
					$pay_refundPrice = $refundPrice-$_ord->reserve;
					$point_refundPrice = $_ord->reserve;
				}else{ //����Ʈ������ ���
					$pay_refundPrice = 0;
					$point_refundPrice = $refundPrice-$pay_refundPrice;
				}
				?>
				<div class="rInfoTop">
					<h4>ȯ�� �����ݾ�</h4>
					<h3><?=number_format($refundPrice)?>��</h3>
				</div>
				<div class="rInfoBottom">
					<dl>
						<dt>���� ȯ�ұݾ�</dt><dd><?=number_format($pay_refundPrice)?>��</dd>
						<dt>����Ʈ ȯ�ұݾ�</dt><dd><?=number_format($point_refundPrice)?>��</dd>
					</dl>
				</div>
			</li>
		</ul>
	</article>
<?
}
?>

<?

	//echo "<tr height=30 bgcolor=#efefef>\n";
	echo "<br/>";
	echo "<div style='width:100%;text-align:left;'><p style='font-weight:bold;font-size:10pt;'>* ���� �ֹ��� ���� ����(����, ����, ��ۺ� �� �߰� ���� ����)</p></div>";

	echo "<table border=0 cellpadding=0 cellspacing=1 width=100% bgcolor=\"#e8e8e8\" style=\"margin-top:5px;\">";
	echo "<col width=25>";
	if($vendercnt>0){ echo "<col width=55>"; }
	echo"
		<colgroup>
		<col width=55>
		<col>
		<col width=95>
		<col width=28>
		<col width=45>
		<col width=55>
		</colgroup>
	";
	//echo "<tr><td bgcolor=#efefef align=center colspan=".($_ord->paymethod!="B" || $mode!="update"?$colspan:($colspan+1))."><span style=\"font-size:11px; font-weight:bold;\">�߰����/����/��������</span></td></tr>\n";
	echo "<tr>
			<td bgcolor=#efefef align=center></td>
			<td bgcolor=#efefef align=center><span style=\"font-size:11px; font-weight:bold;\">������ü</span></td>
			<td bgcolor=#efefef align=center><span style=\"font-size:11px; font-weight:bold;\">ó������</span></td>
			<td bgcolor=#efefef align=center colspan=".($_ord->paymethod!="B" || $mode!="update"?$colspan-3:($colspan))."><span style=\"font-size:11px; font-weight:bold;\">���� �� ���� �� ���γ��� / ��ۺ� �� �߰� ��� ����</span></td>
		</tr>\n";

	if(count($etcdata)>0) {
		for($j=0;$j<count($etcdata);$j++) {

			if( substr($etcdata[$j]->productcode,0,3)=="COU" ){
				continue;
			}
			$cnt++;
			$sumprice=$etcdata[$j]->price;
			$reserve=$etcdata[$j]->reserve;
			$productcode=$etcdata[$j]->productcode;
			$in_reserve+=$reserve;
			$totalprice+=$sumprice;
			echo "<tr bgcolor=#ffffff>\n";

			if	($productcode=="99999999990X" ) { //|| substr($etcdata[$j]->productcode,0,3)=="COU"

				echo "	<td><input type=checkbox name=chkprcode value=\"".$etcdata[$j]->productcode."\" uid=\"".$etcdata[$j]->uid."\" statuss=\"".$etcdata[$j]->status."\" price=\"".$sumprice."\" /></td>\n";

				echo "<input type=\"hidden\" name=\"chkprcode_uid\" value=\"".$etcdata[$j]->uid."\" style=\"display:none\" />";
				echo "<input type=\"hidden\" name=\"chkprcode_statuss\" value=\"".$etcdata[$j]->status."\" style=\"display:none\" />";
				echo "<input type=\"hidden\" name=\"chkprcode_price\" value=\"".$sumprice."\" style=\"display:none\" />";

			}else{
				echo "	<td>&nbsp;</td>\n";
			}

			if($vendercnt>0) {
				if($etcdata[$j]->vender>0) {
					echo "	<td align=center style=\"font-size:8pt\"><a href=\"javascript:viewVenderInfo(".$etcdata[$j]->vender.")\"><B>".$venderlist[$etcdata[$j]->vender]->id."</B></a></td>\n";
				} else {
					echo "	<td align=center>&nbsp;</td>\n";
				}
			}
			echo "	<td bgcolor=#ffffff align=center style=\"color:red\">";

			if ($productcode=="99999999990X") {
				switch($etcdata[$j]->status) {
					case 'RA':
						echo "ȯ�ҽ�û";

							$sql = "select requestor from part_cancel_want where uid='".$etcdata[$j]->uid."'";
							$result2=mysql_query($sql,get_db_conn());
							if($row2=mysql_fetch_object($result2)) {

								if ($row2->requestor ==1) {
									 echo "<br/><span style=\"color:blue\">(��)</span>";
								}
							}
							mysql_free_result($result2);
						break;
					case 'RB': echo "ȯ������";  break;
					case 'RC': echo "ȯ�ҿϷ�";  break;
				}
			}else if(substr($etcdata[$j]->productcode,0,3)=="COU"){
				switch($etcdata[$j]->status) {
					case 'RC': echo "�������";  break;
				}
			}


			$etcdata[$j]->order_prmsg = str_replace(", ","<br />",$etcdata[$j]->order_prmsg);

			echo " </td>\n";
			echo "	<td bgcolor=#ffffff style=\"font-size:8pt;padding:2,5;line-height:10pt\">".$etcdata[$j]->productname." <span class=\"page_screen\"><A style=\"cursor:hand\" onMouseOver='EtcMouseOver($cnt)' onMouseOut=\"EtcMouseOut($cnt);\"><img src=images/btn_more02.gif border=0 align=absmiddle></A>";
			echo "	<div id=etcdtl".$cnt." style=\"position:absolute; z-index:100; visibility:hidden;\">\n";
			echo "	<table border=0 cellpadding=0 cellspacing=0 width=300 bgcolor=#A47917>\n";
			echo "	<tr><td align=center style=\"color:#FFFFFF;padding:5\"><B>###### �ش� ��ǰ�� ######</B></td></tr>\n";
			echo "	<tr><td style=\"font-size:8pt;color:#FFFFFF;padding:10;padding-top:0;line-height:11pt\">".$etcdata[$j]->order_prmsg."</td></tr>\n";
			echo "	</table>\n";
			echo "	</div>\n";
			echo "	</span>\n";
			echo "	</td>\n";
			echo "	<td bgcolor=#ffffff>&nbsp;</td>";


			if ($etcdata[$j]->productcode=="99999999999X" || $etcdata[$j]->productcode=="99999999990X" || $etcdata[$j]->productcode=="99999999997X" || substr($etcdata[$j]->productcode,0,3)=="COU" || $etcdata[$j]->productcode=="99999999999R") { // ���ݰ����� ����ǥ�þ���
				echo "	<td><input type=hidden name=arquantity value=\"1\">&nbsp;</td>\n";
			} else {
				echo "	<td align=center".($_ord->paymethod!="B" || $mode!="update"?($etcdata[$j]->quantity>1?" bgcolor=#FDE9D5 style=\"font-size:8pt\"><font color=#000000><b>":">").$etcdata[$j]->quantity:"><input type=text style='font-size:8pt;text-align:right' name=arquantity value=\"".$etcdata[$j]->quantity."\" style=\"width:100%\">")."</td>\n";
			}
			echo "	<td align=right style=\"font-size:8pt\">";
			if($etcdata[$j]->vender>0) {
				if(strlen($_ord->ordercode)==20 && substr($_ord->ordercode,-1)!="X" && $etcdata[$j]->productcode!="99999999990X" && $etcdata[$j]->productcode!="99999999997X") {
					if($_ord->paymethod!="B" || $mode!="update") {
						echo ($reserve>0?number_format($reserve):"")."&nbsp;";
					} else {
						echo "<input type=hidden name=arreserve>&nbsp;";
					}
				} else {
					echo "<input type=hidden name=arreserve>&nbsp;";
				}
			} else {
				if(strlen($_ord->ordercode)==20 && substr($_ord->ordercode,-1)!="X" && $etcdata[$j]->productcode!="99999999990X" && $etcdata[$j]->productcode!="99999999997X") {
					if($_ord->paymethod!="B" || $mode!="update") {
						echo ($reserve>0?number_format($reserve):"")."&nbsp;";
					} else {
						echo "<input type=text style='font-size:8pt;text-align:right;width:100%' name=arreserve value=\"".$reserve."\">";
					}
				} else {
					echo "<input type=hidden name=arreserve>&nbsp;";
				}
			}
			echo "	</td>\n";

			echo "	<td align=right style=\"font-size:8pt\">".(substr($etcdata[$j]->productcode,-4)!="GIFT"?$_ord->paymethod!="B" || $mode!="update"?number_format($sumprice)."&nbsp;":"<input type=text style='font-size:8pt;text-align:right;width:100%' name=arprice value=\"".$sumprice."\">":"&nbsp;<input type=hidden name=arprice>")."</td>\n";

			if($_ord->paymethod=="B" && $mode=="update") {
				echo "<td align=center style=\"font-size:8pt\"><a href=\"javascript:OrderUpdate('1',".$cnt.",'".$etcdata[$j]->vender."','".$etcdata[$j]->productcode."','','')\"><img src='images/ordtl_miniup.gif' align=absmiddle border=0 alt='��ǰ����'></a><br><img width=0 height=2 border=0><br><a href=\"javascript:OrderDelete('1','".$etcdata[$j]->vender."','".$etcdata[$j]->productcode."','','')\"><img src='images/ordtl_minidel.gif' align=absmiddle border=0 alt='��ǰ����'></a></td>\n";
			}
			echo "</tr>\n";
		}
	}

	if($_ord) {
		// ȸ���ϰ�� ȸ������(ȸ���޸�)�� �����´�.
		if (strlen($_ord->ordercode)==20 && substr($_ord->ordercode,-1)!="X") {
			$sql = "SELECT memo,group_code,rec_id FROM tblmember WHERE id='".$_ord->id."' ";
			$result=mysql_query($sql,get_db_conn());
			if ($row=mysql_fetch_object($result)) {
				$usermemo=$row->memo;
				$group_code=$row->group_code;
				$fist_rec_id=trim($row->rec_id);
			}
			mysql_free_result($result);

			if(strlen($group_code)>0) {
				$sql = "SELECT group_name FROM tblmembergroup WHERE group_code='".$group_code."' ";
				$result=mysql_query($sql,get_db_conn());
				if($row=mysql_fetch_object($result)) {
					$group_name = $row->group_name;
				}
				mysql_free_result($result);
			}
		}

		$dc_price=(int)$_ord->dc_price;
		$salemoney=0;
		$salereserve=0;
		if($dc_price<>0) {
			if($dc_price>0) $salereserve=$dc_price;
			else $salemoney=-$dc_price;
			if (strlen($_ord->ordercode)==20 && substr($_ord->ordercode,-1)!="X") {
				$sql = "SELECT b.group_name FROM tblmember a, tblmembergroup b ";
				$sql.= "WHERE a.id='".$_ord->id."' AND b.group_code=a.group_code AND MID(b.group_code,1,1)!='M'";
				$result=mysql_query($sql,get_db_conn());
				if($row=mysql_fetch_object($result)) {
					$group_name=$row->group_name;
				}
				mysql_free_result($result);
			}
			echo "<tr bgcolor=#FFFFE6>\n";
			echo "	<td>&nbsp;</td>\n";
			echo "	<td>&nbsp;</td>\n";
			if($vendercnt>0) {
				echo "	<td>&nbsp;</td>\n";
			}
			echo "	<td style=\"font-size:8pt;padding:2,5\"><font color=red>�׷�ȸ�� ����/���� : ".$group_name."</font></td>\n";
			echo "	<td>&nbsp;</td>\n";
			echo "	<td>&nbsp;</td>\n";
			echo "	<td align=right style=\"font-size:8pt\">".($salereserve>0?($_ord->paymethod!="B" || $mode!="update"?number_format($salereserve)."&nbsp;":"<input type=text style='font-size:8pt;text-align:right;width:100%' name=salereserve value=\"".$salereserve."\">"):"&nbsp;")."</td>\n";
			echo "	<td align=right style=\"font-size:8pt\">".($salemoney>0?($_ord->paymethod!="B" || $mode!="update"?"-".number_format($salemoney)."&nbsp;":"<input type=text style='font-size:8pt;text-align:right;width:100%' name=salemoney value=\"-".$salemoney."\">"):"&nbsp;")."</td>\n";
			if($_ord->paymethod=="B" && $mode=="update") {
				echo "	<td align=center><a href=javascript:OrderUpdate('2','','','','','')><img src='images/ordtl_miniup.gif' align=absmiddle border=0 alt='����'></a><br><img width=0 height=2 border=0><br><a href=javascript:OrderDelete('2','','','','')><img src='images/ordtl_minidel.gif' align=absmiddle border=0 alt='����'></a></td>\n";
			}
			echo "</tr>\n";
			$in_reserve+=$salereserve;
		}
		if($_ord->reserve>0) {
			echo "<tr bgcolor=#F5F5F5>\n";
			echo "	<td>&nbsp;</td>\n";
				echo "	<td>&nbsp;</td>\n";
			if($vendercnt>0) {
				echo "	<td>&nbsp;</td>\n";
			}
			echo "	<td style=\"font-size:8pt;padding:2,5\"><font color=#0054A6>�����ݻ���</font></td>\n";
			//echo "	<td>&nbsp;</td>\n";
			echo "	<td></td>\n";
			echo "	<td>&nbsp;</td>\n";
			echo "	<td>&nbsp;</td>\n";
			echo "	<td align=right style=\"font-size:8pt\">".($_ord->paymethod!="B" || $mode!="update"?"- ".number_format($_ord->reserve)."&nbsp;":"<input type=text style='font-size:8pt;text-align:right;width:100%' name=usereserve value=\"".$_ord->reserve."\">")."</td>\n";
			if($_ord->paymethod=="B" && $mode=="update") {
				echo "	<td align=center><a href=javascript:OrderUpdate('3','','','','','')><img src='images/ordtl_miniup.gif' align=absmiddle border=0 alt='����'></a><br><img width=0 height=2 border=0><br><a href=javascript:OrderDelete('3','','','','')><img src='images/ordtl_minidel.gif' align=absmiddle border=0 alt='����'></a></td>\n";
			}
			echo "</tr>\n";
		}
		$totalprice=$totalprice-$salemoney-$_ord->reserve;
		if($_shopdata->card_payfee>0 && preg_match("/^(C|P|M){1}/",$_ord->paymethod) && $_ord->price<>$totalprice) {
			echo "<tr bgcolor=#F5F5F5>\n";
			echo "	<td>&nbsp;</td>\n";
			if($vendercnt>0) {
				echo "	<td>&nbsp;</td>\n";
			}
			echo "	<td style=\"font-size:8pt;padding:2,5\"><font color=#F26622>ī�������</font></td>\n";
			echo "	<td>&nbsp;</td>\n";
			echo "	<td>&nbsp;</td>\n";
			echo "	<td>&nbsp;</td>\n";
			echo "	<td align=right style=\"font-size:8pt\">".number_format($_ord->price-$totalprice)."&nbsp;</td>\n";
			echo "</tr>\n";
		}
		$temp = substr($_ord->ordercode,0,4)."/".substr($_ord->ordercode,4,2)."/".substr($_ord->ordercode,6,2)." ".substr($_ord->ordercode,8,2).":".substr($_ord->ordercode,10,2).":".substr($_ord->ordercode,12,2);
		$message=explode("[MEMO]",$_ord->order_msg);
		$message[0]=ereg_replace("\"","&quot;",$message[0]);
		$message[0]=str_replace("\"","",$message[0]);

		$message[0]=ereg_replace("\r\n","<br>\n&nbsp;&nbsp;",$message[0]);
		/*
		$mes1 = explode("<br>",$message[0]);
		$mescnt = count($mes1);
		$message[0]="";
		for($i=0;$i<$mescnt;$i++) {
			//$message[0].=messageview2($mes1[$i],80)."<br>";
		}
		*/
		echo "<tr ";
		if($_ord->reserve==0) echo " bgcolor=#F5F5F5";
		echo ">\n";

		$account_type = "";
		$arpm=array("B"=>"������","V"=>"������ü","O"=>"�������","Q"=>"�������(�Ÿź�ȣ)","C"=>"�ſ�ī��",/*"P"=>"�ſ�ī��(�Ÿź�ȣ)",*/"M"=>"�ڵ���");

		if($_ord->pay_data=="�ſ�ī����� - ī���ۼ���" && substr($_ord->ordercode,0,12)<=$pgdate) $_ord->pay_data=$arpm[substr($_ord->paymethod,0,1)]." ����";

		if (preg_match("/^(B|O|Q){1}/",$_ord->paymethod)) {	//������, �������, ������� ����ũ��
			if($_ord->paymethod=="B") $account_type="�������Ա�";
			else if(substr($_ord->paymethod,0,1)=="O") $account_type="�������";
			else echo $account_type="�Ÿź�ȣ - �������";

		} else if(substr($_ord->paymethod,0,1)=="M") {	//�ڵ��� ����
			$account_type = "�ڵ��� ����";
		} else if(substr($_ord->paymethod,0,1)=="P") {	//�Ÿź�ȣ �ſ�ī��
			$account_type = "�Ÿź�ȣ - �ſ�ī��";
		} else if (substr($_ord->paymethod,0,1)=="C") {	//�Ϲݽſ�ī��
			$account_type = "�ſ�ī��";
		} else if (substr($_ord->paymethod,0,1)=="V") {
			$account_type = "�ǽð� ������ü";
		}

		echo "	<td colspan=".($colspan-4)." style=\"font-size:8pt;padding:5,27\"><B>�� �����ݾ� (<span style='color:blue'>".$account_type."<span/>)</B> </td>\n";

		echo "	<td>&nbsp;</td>\n";
		echo "	<td align=center style=\"font-size:8pt\">".$sumquantity."</td>\n";
		echo "	<td align=right style=\"font-size:8pt\">".(strlen($_ord->ordercode)==20 && substr($_ord->ordercode,-1)!="X"?number_format($in_reserve)."&nbsp;":"&nbsp")."</td>\n";
		echo "	<td align=right style=\"font-size:8pt;font-weight:bold\"> ".($_ord->paymethod!="B" || $mode!="update"?number_format($_ord->price)."&nbsp;":"<input type=text style='font-size:8pt;text-align:right;width:100%' name=sumprice value=\"".$_ord->price."\">")."</td>\n";
		if($_ord->paymethod=="B" && $mode=="update") {
			echo "	<td align=center><a href=javascript:OrderUpdate('5','','','','','')><img src='images/ordtl_miniup.gif' align=absmiddle border=0 alt='�ѱݾ׼���'></a></td>";
		}

		echo "</form>\n";
		echo "</tr>\n";
		echo "</table>";

		if($_ord->deli_gbn=="W"){//�ֹ����öȸ�� ��� 
			echo "<br/>";
				echo "<div style='width:100%;text-align:left;'><p style='font-weight:bold;font-size:10pt;'>* �ֹ����öȸ</p></div>";
				echo "<table border=0 cellpadding=0 cellspacing=1 width=100% bgcolor=\"#e8e8e8\" style=\"margin-top:5px;\">";
				echo "<tr bgcolor=#FFFFFF height=24 class=\"page_screen\">\n";

				echo "	<td align=left style=\"padding:10px 0;\">&nbsp;&nbsp;&nbsp;�� �ֹ��� ���� �ֹ���ҿ�û�� öȸ�Ͽ����ϴ�. ";
				echo "	<input type=\"button\" value=\"öȸ�ϱ�\" style=\"cursor:hand; color:#FFFFFF; border-color:#666666; background-color:#666666; font-size:8pt; font-family:Tahoma; height:20px; width:85px;\" onClick=\"cancelBack();\">";
				echo "	</td>";
				echo "</tr>";
				echo "</table>";

		}

		$candate = date("Ymd",mktime(0,0,0,date("m"),date("d")-15,date("Y")));
		if((!preg_match("/^(R|A)$/", $_ord->del_gbn) && (!preg_match("/^(Q|P){1}/",$_ord->paymethod) || $_ord->price==0))
		|| (!preg_match("/^(R|A)$/", $_ord->del_gbn) && preg_match("/^(Q|P){1}/",$_ord->paymethod) && ($_ord->deli_gbn=="C" || substr($_ord->ordercode,0,8)<$candate) && $_ord->deli_gbn!="Y")) {

			echo "<br/>";
			echo "<div style='width:100%;text-align:left;'><p style='font-weight:bold;font-size:10pt;'>* ��ü�ֹ���� ����</p></div>";
			echo "<table border=0 cellpadding=0 cellspacing=1 width=100% bgcolor=\"#e8e8e8\" style=\"margin-top:5px;\">";
			echo "<tr bgcolor=#FFFFFF height=24 class=\"page_screen\">\n";

			echo "	<td align=left style=\"padding:10px 0;\">&nbsp;&nbsp;&nbsp;�� �ֹ���ü ��ҿ� ���� ";

			//������ ���
			if($norecan!="Y" && $_ord->deli_gbn!="C" && preg_match("/^(Y|D|H)$/",$_ord->deli_gbn) && strlen($_ord->deli_date)==14 && $in_reserve>0 && strlen($_ord->ordercode)==20 && substr($_ord->ordercode,-1)!="X") {
				echo " <a href=\"javascript:RestoreReserveCancel('".$in_reserve."')\"><img src=\"images/ordtl_restorerescan.gif\" border=0 align=absmiddle></a>";
			}
			//��������
			echo " <a href=\"javascript:RestoreOrder('quan','".$_ord->deli_gbn."','".$_ord->reserve."')\"><img src=\"images/ordtl_restorequan.gif\" border=0 align=absmiddle></a>";
			if($_ord->deli_gbn!="C") {
				echo " <a href=\"javascript:RestoreOrder('can','".$_ord->deli_gbn."','".$_ord->reserve."')\"><img src=\"images/ordtl_restorecan.gif\" border=0 align=absmiddle></a>";
			}
			if($_ord->deli_gbn!="C" && $_ord->reserve>0) {
				echo " <a href=\"javascript:RestoreReserve()\"><img src=\"images/ordtl_restoreres.gif\" border=0 align=absmiddle></a>";
			}
			echo "&nbsp;</td>\n";
			echo "</tr>\n";
			echo "</table>";
		}


	//�̹� ȯ�ҵ� �ݾ�
	$sum_re = 0;
	$sql = "SELECT sum(price) as sum_re FROM tblorderproduct WHERE ordercode='".$ordercode."' and productcode='99999999995X' ";
	$result=mysql_query($sql,get_db_conn());
	$row=mysql_fetch_object($result);

	$sum_re = $row->sum_re;

	mysql_free_result($result);

	$ord_price = $_ord->price;
	$now_price = $ord_price - $sum_re;


	//�κ� ��� ������ �鼼�κ� ��ȸ
	$free_mny = 0;

	if (substr($_ord->paymethod,0,1) == "C") {
		$sql = "select * from card_orderinfo_tax where ordercode='".$ordercode."'";
		$result=mysql_query($sql,get_db_conn());
		$row=mysql_fetch_object($result);

		$free_mny = $row->free_mny;
		mysql_free_result($result);

		//��� �Ϸ�Ȱ�
		$sql = "select sum(cancel_free) as cancel_free from card_part_cancel_log  where ordercode='".$ordercode."'";
		$result=mysql_query($sql,get_db_conn());
		$row=mysql_fetch_object($result);

		$cancel_free = $row->cancel_free;
		mysql_free_result($result);

		//��� ��û���ΰ�
		$sql = "select sum(free_mny) as want_cancel_free from card_part_cancel_tax_free  where ordercode='".$ordercode."' and status=0";
		$result=mysql_query($sql,get_db_conn());
		$row=mysql_fetch_object($result);

		$want_cancel_free = $row->want_cancel_free;
		mysql_free_result($result);

		$free_mny = $free_mny - $cancel_free - $want_cancel_free;

	}

	//������ ȯ��
	$re_reserve = $_ord->reserve;

	if ($re_reserve>0) {

		$sql = "select sum(cancel_reserve) as cancel_reserve from part_cancel_reserve where ordercode='".$ordercode."'";
		$result=mysql_query($sql,get_db_conn());
		$row=mysql_fetch_object($result);

		$cancel_reserve = $row->cancel_reserve;
		mysql_free_result($result);

		$re_reserve = $re_reserve-$cancel_reserve;

	}


	//�κ� ȯ�� jdy
	/*if($_ord->deli_gbn != "Y") {

		echo "<br/>";
		echo "	<div style='width:100%;text-align:left; position:relative;'><p style='font-weight:bold;font-size:10pt;'>* �κ���� ����</p></div>";

		if($prescd=="Y" && $_ord->deli_gbn!="C") {
			echo "<table border=0 cellpadding=0 cellspacing=1 width=100% bgcolor=\"#e8e8e8\" style=\"margin-top:5px;\">";

			echo "<tr bgcolor=#FFFFFF height=24 class=\"page_screen\">\n";
			echo "	<td align=left colspan=".($_ord->paymethod!="B" || $mode!="update"?$colspan:($colspan+1))." style=\"padding-top:10px;\">";
			echo "	<table border=0 cellpadding=0 cellspacing=0 width=100%>\n";
			echo "	<tr>\n";
				echo "	<td>&nbsp;&nbsp;&nbsp;���û�ǰ �� �κ���� ��û�� ���� \n";
				echo "	<span style=\"cursor:pointer\"><img src=\"images/btn_norefund.gif\" align=\"absmiddle\" alt=\"ȯ�Ұź�\" onclick=\"refundsTo('')\" /></span>\n";
				echo "	<span style=\"cursor:pointer\"><img src=\"images/btn_refund.gif\" align=\"absmiddle\" alt=\"ȯ�ҽ���\" onclick=\"refundsTo('RC')\" /></span>\n";
				echo "	<span style=\"cursor:pointer\"><img src=\"images/btn_adminrefund.gif\" align=\"absmiddle\" alt=\"������ȯ�ҽ�û\" onclick=\"refundsTo('RA')\" /></span>\n";
				
				?>
				<?
				echo "<div style='width:100%; text-align:left; margin-top:4px;'><p style='color:blue;'>&nbsp;&nbsp;&nbsp;* ��� �ֹ�����Ʈ�� ����,����,�߰���볻�� ����Ʈ���� ��ҿ�û ��ǰ �� ������ �����ϼ���</p></div>";
				echo "	</td>";

			if($_ord->deli_gbn=='Y' || $_ord->deli_gbn=='H') {
			}
			echo "	</tr>\n";
			echo "	</table>\n";

				echo "&nbsp;</td>\n";
				echo "</tr>\n";
			echo "	</table>\n";
			?>

				<!-- <table width="100%" cellpadding="0" cellspacing="0" border="0">
					<tr>
						<td>
							<div id="refund_div" style="position:absolute; width:670px; border:2px solid #acacac; background-color:#ffffff; z-index:999; padding:5px; margin:-80px 0 0 0; display:none;">
								<div style="width:100%; text-align:right"><span style="color:red;font-weight:bold;">* �κ��ֹ���ҿ� ���� ��ۺ� �߻��� ��� ��ۺ� ������ �ݾ��� ó���ݾ׶��� �����Է°����մϴ�.</span>&nbsp;<span style="border:1px solid gray;color:#ffffff;background-color:#000000;padding:2px 4px;cursor:pointer" onClick="refundDivView('N');" >X</span></div>
								<div style="width:100%;margin-top:5px;">
								<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
										<col width=200 />
										<col width= />
									<tr><td height=2 colspan="2" bgcolor=#808080></td></tr>
										<tr>
											<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B>�����ֹ��� �����ݾ�</td>
											<td style=padding:7,10>
												<?= number_format($ord_price) ?>
											</td>
										</tr>
										<tr>
											<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B>�̹� ó���� �ٸ���ǰ ȯ�ұݾ�</td>
											<td style=padding:7,10>
												<?= number_format($sum_re) ?>
											</td>
										</tr>
										<tr>
											<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B>��� ���� �ݾ�</td>
											<td style=padding:7,10>
												<?= number_format($now_price) ?>
											</td>
										</tr>
										<? if ($re_reserve>0) { ?>

										<tr>
											<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B>������ ȯ��</td>
											<td style=padding:7,10>
												��� ������ ������ : <span style="color:red"><?= number_format($re_reserve) ?></span> ��
												<input type="text" size="8" name="re_reserve" id="re_reserve" onKeyUp="rePriceMinusReserve(this)" value=""/> �� ȯ��
											</td>
										</tr>
										<? } ?>
										<tr>
											<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B>��û ȯ�� ó�� �ݾ�</td>
											<td style=padding:7,10>
												<input type="text" size="8" name="refund_price" id="refund_price" value=""/> (���� �Ͻ� �� �ֽ��ϴ�.)<br/>
												<span style="color:red;">* ȸ����� ���ε�, �ֹ� �� ��Ÿ���γ����� ���� ��� �� ��ҿ�û ��ǰ�ݾ׺��� �ִ� ȯ�� ó���ݾ��� ���� �� �ֽ��ϴ�.</span>
												<span style="color:blue;" id="dcPriceTotalMsg"><span>
											</td>
										</tr>
										<? if (substr($_ord->paymethod,0,1) == "C" && $pg_type=="A") { ?>
										<tr>
											<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B>��Ұ��� �����</td>
											<td style=padding:7,10>
												<?= number_format($free_mny) ?>
											</td>
										</tr>
										<tr>
											<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B>��� �����</td>
											<td style=padding:7,10>
												<input type="text" size="8" name="refund_free" id="refund_free" value=""/> <br/>(����� ��ǰ�� ��� �Ͻ� ��� �ش��ǰ�� �ݾ��� �Է����ּ���.)
											</td>
										</tr>

										<? } ?>
										<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
										<tr>
											<td align="right" colspan="2">
												<span style="border:1px solid gray;color:#ffffff;background-color:#000000;padding:3px 8px;cursor:pointer" onClick="refundsCom()">ȯ��ó��</span>
											</td>
										</tr>
										<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
									</table>
								</div>
							</div>
						</td>
					</tr>
				</table> -->

					<!--
					<div id="reserve_div" style="position:absolute;width:400px;border:2px solid #acacac;background-color:#ffffff;z-index:999;padding:5px;margin:0 0 0 -350px;display:none">
						<div style="width:100%;text-align:right"><span style="color:red;font-weight:bold;">* ó���� �������� ȸ������ ȯ���˴ϴ�..</span>&nbsp;<span style="border:1px solid gray;color:#ffffff;background-color:#000000;padding:2px 4px;cursor:pointer" onclick="reserveDivView('N');" >X</span></div>
						<div style="width:100%;margin-top:5px;">
						<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
								<col width=150 />
								<col width= />
							<tr><td height=2 colspan="2" bgcolor=#808080></td></tr>
								<tr>
									<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B>��� ������</td>
									<td style=padding:7,10>
										<?= number_format($_ord->reserve) ?>
									</td>
								</tr>
								<tr>
									<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B>ȯ������ ������</td>
									<td style=padding:7,10>
										<?= number_format($re_reserve) ?>
									</td>
								</tr>
								<tr>
									<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B>ȯ�� ������</td>
									<td style=padding:7,10>
										<input type="text" size="8" name="re_reserve" id="re_reserve" value=""/>
									</td>
								</tr>
								<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
								<tr>
									<td align="right" colspan="2">
										<span style="border:1px solid gray;color:#ffffff;background-color:blue;padding:3px 8px;cursor:pointer" onclick="reReserveCom()">ȯ��ó��</span>
									</td>
								</tr>
								<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
							</table>
						</div>
					</div>
					-->
			<?
		}
	}*/

	echo "<table border=0 cellpadding=0 cellspacing=1 width=100% bgcolor=\"#e8e8e8\" style=\"margin-top:5px;\">";
	echo "
		<colgroup>
		<col>
		<col width=95>
		<col width=45>
		<col width=55>
		</colgroup>
	";

	if(count($refund_data)>0) {

		echo "<tr>
			<td bgcolor=#efefef align=center colspan=4><span style=\"font-size:11px; font-weight:bold;\">�κ� ��һ�ǰ���� / ���� �� ���� �� ���� �� ��ۺ� �� �߰���� �κ���� ����</span></td>
		</tr>\n";


		for($j=0;$j<count($refund_data);$j++) {
			$cnt++;
			$sumprice=$refund_data[$j]->price;
			$reserve=$refund_data[$j]->reserve;
			$in_reserve+=$reserve;
			$totalprice+=$sumprice;
			echo "<tr bgcolor=#ffffff>\n";
			echo "	<td bgcolor=#ffffff style=\"font-size:8pt;padding:2,5;line-height:10pt\">".$refund_data[$j]->productname." (".$refund_data[$j]->date.") <span class=\"page_screen\"></td>\n";

			if ($refund_data[$j]->status =="A" && $sumprice>0) {
				echo "	<td bgcolor=#ffffff><span style=\"padding:3px 8px;height:15px;color:#ffffff;background-color:#FF0000;cursor:pointer\" onclick=\"card_part_cancel('".$refund_data[$j]->uid."');\">ī��κ����</span></td>";
			}else{
				echo "	<td bgcolor=#ffffff>&nbsp;</td>";
			}

			echo "	<td align=right style=\"font-size:8pt\"></td>\n";
			echo "	<td align=right style=\"font-size:8pt\">- ".number_format($sumprice)."</td>\n";

			echo "</tr>\n";

		}

			echo "<tr ";
			if($_ord->reserve==0) echo " bgcolor=#F5F5F5";
			echo ">\n";
			echo "	<td style=\"font-size:8pt;padding:5,27\"><B>�κ���� �� ���ǸŰ�</B> </td>\n";
			echo "	<td>&nbsp;</td>\n";
			echo "	<td align=center style=\"font-size:8pt\"></td>\n";
			echo "	<td align=right style=\"font-size:8pt;font-weight:bold\"> ".number_format($now_price)."</td>\n";

			echo "</tr>\n";
	}



	//ȯ���� ������
	$sql = "SELECT * FROM part_cancel_reserve WHERE ordercode='".$ordercode."' order by reg_date asc";
	$result=mysql_query($sql,get_db_conn());

	$kk = 0;
	while($row=mysql_fetch_object($result)) {

		if ($kk==0) {
		echo "
		<tr>
			<td bgcolor=#ffffff align=center colspan=4 height=10></td>
		</tr>
		<tr>
			<td bgcolor=#efefef align=center colspan=4><span style=\"font-size:11px; font-weight:bold;\">������ ȯ��</span></td>
		</tr>\n";

		}

		echo "<tr bgcolor=#ffffff>\n";

		echo "	<td bgcolor=#ffffff style=\"font-size:8pt;padding:2,5;line-height:10pt\">".$row->memo." (".substr($row->reg_date, 0, 10).")</td>\n";
		echo "	<td><input type=hidden name=arquantity value=\"1\">&nbsp;</td>\n";
		echo "	<td align=right style=\"font-size:8pt\">".number_format($row->cancel_reserve)."&nbsp;</td>\n";
		echo "	<td align=right style=\"font-size:8pt\"></td>\n";

		echo "</tr>\n";

		$kk++;
	}

?>
</table>
<br/>
<?


	//ȯ�Ұ��� jdy
	$sql = "SELECT * FROM order_refund_account WHERE ordercode='".$ordercode."'";
	$result=mysql_query($sql,get_db_conn());
	$row=mysql_fetch_object($result);
		$refund_account_bank = $row->bank;
		$refund_account_name = $row->account_name;
		$refund_account_num = $row->account_num;
		$refund_account_save = true;

	mysql_free_result($result);


	$_ord_pay_data = explode("<br>ȯ�Ұ������� : ", $_ord->pay_data);
	if( strlen($_ord_pay_data[1]) > 0 ) {
		$_ord_pay_dataA = explode(" ", $_ord_pay_data[1]);
		$_ord_pay_dataB = explode("(������:", $_ord_pay_dataA[1]);
		$_ord_pay_dataC = explode(")", $_ord_pay_dataB[1]);
		$refund_account_bank = $_ord_pay_dataA[0];
		$refund_account_name = $_ord_pay_dataB[0];
		$refund_account_num = $_ord_pay_dataC[0];
		$refund_account_save = false;
	}
?>
<div style='width:100%;text-align:left;'><p style='font-weight:bold;font-size:10pt;'>* ��ҿ� ���� ȯ�Ұ�������(���� /ȯ�Ұ�������/������)</p></div>
<table border=0 cellpadding=0 cellspacing=0 width=100% style=margin-top:10px;>
<tr>
	<td>
		<table border=0 cellpadding=3 cellspacing=1 width=100% bgcolor=#d8d8d8>
			<tr>
				<td bgcolor=#ffffff style="padding-bottom:10px;vertical-align:top;">
					��� :
					<input type="text" name="bank" id="bank" size="10" value="<?=$refund_account_bank?>"/>
					/
					<input type="text" name="account_name" id="account_name" size="30" value="<?=$refund_account_name?>"/>
					/
					<input type="text" name="account_num" id="account_num" size="10" value="<?=$refund_account_num?>"/>
					<?
						if( $refund_account_save ) {
					?>
					<input type="button" value="�� ��" style="cursor:hand; color:#FFFFFF; border-color:#666666; background-color:#666666; font-size:8pt; font-family:Tahoma; height:20px; width:45px;" onClick="reFuAccount();">
					<?
						}
					?>
				</td>
			</tr>
		</table>

	</td>
</tr>
</table>


<!--

 ������� ( 2014-07-08 x2chi )

<br/>
<div style='width:100%;text-align:left;'><p style='font-weight:bold;font-size:10pt;'>* ���� ���� ��� ó��</p></div>
<table border=0 cellpadding=0 cellspacing=0 width=100% style=margin-top:10px;>
<tr>
	<td>
		<table border=0 cellpadding=3 cellspacing=1 width=100% bgcolor=#d8d8d8>
			<col width=90 style=\"padding-left:3\">
			<col width=>
			<tr>
				<td bgcolor=#ffffff style="padding-bottom:10px;vertical-align:top;">
					<input type="button" value="���� ���" style="cursor:hand; color:#FFFFFF; border-color:#666666; background-color:#666666; font-size:8pt; font-family:Tahoma; height:20px; width:200px;" onClick="popCancle('<?=$ordercode?>','<?=$_ord->pay_auth_no?>');">
					<br>
					* ���̽����� ���� ó�� ����Դϴ�. ���� �ɼ��� ��Ȯ�� �������� �Է��� �ֽñ� �ٶ��ϴ�.
				</td>
			</tr>
		</table>
	</td>
</tr>
</table>
-->




<?
	$promotInfo =  snsPromoteOrderOkInfo ( $ordercode );
?>
<div style='width:100%;text-align:left;'><p style='font-weight:bold;font-size:10pt;'>* ȫ�� URL ���� ����</p></div>
<table border=0 cellpadding=3 cellspacing=1 width=100% bgcolor=#d8d8d8>
	<tr>
		<td bgcolor=#ffffff style="padding-bottom:10px;vertical-align:top;">
			<?
				if( $promotInfo['rsvA'] ) {
					echo "[<strong>".$promotInfo['pkId']."</strong>]���� ȫ�� URL�� ���� [<strong>".$promotInfo['memId']."</strong>]���� ������ �ֹ��Դϴ�.<br />��ۿϷ�ó���� �Ǹ� ȫ������ [<strong>".$promotInfo['pkId']."</strong>]�Բ�<strong>".$promotInfo['pkRsv']."</strong>���� ���� �˴ϴ�.";
				}
			?>
		</td>
	</tr>
	<tr>
		<td bgcolor=#ffffff style="padding-bottom:10px;vertical-align:top;">
			<?
				if( $promotInfo['rsvB'] ) {
					echo "[<strong>".$promotInfo['pkId']."</strong>]���� ȫ�� URL�� ���� [<strong>".$promotInfo['memId']."</strong>]���� ������ �ֹ��Դϴ�.<br />��ۿϷ�ó���� �Ǹ� �������� [<strong>".$promotInfo['memId']."</strong>]�Բ�<strong>".$promotInfo['memRsv']."</strong>���� ���� �˴ϴ�.";
				}
			?>
		</td>
	</tr>
</table>


<?
		echo "<table border=0 cellpadding=0 cellspacing=0 width=100% style=margin-top:40px;>";
		echo "<tr>\n";
		echo "	<td colspan=".($_ord->paymethod!="B" || $mode!="update"?$colspan:($colspan+1))." align=center>\n";
		echo "	<table border=0 cellpadding=3 cellspacing=1 width=100% bgcolor=#d8d8d8>\n";
		echo "	<colgroup>";
		echo "	<col width=90 style=\"padding-left:3\">\n";
		echo "	<col>\n";
		echo "	</colgroup>";
		//echo "	<tr><td colspan=2 height=5></td></tr>\n";
		echo "	<tr><td bgcolor=#efefef align=center colspan=2><span style=\"font-size:11px; font-weight:bold;\">�ֹ��� ����</span></td></tr>\n";
		echo "	<form name=form2 method=post action=\"".$_SERVER[PHP_SELF]."\">\n";
		echo "	<input type=hidden name=type>\n";
		echo "	<input type=hidden name=ordercode value=\"".$_ord->ordercode."\">\n";
		echo "	<input type=hidden name=id value=\"".urlencode($_ord->id)."\">\n";
		echo "	<tr>\n";
		echo "		<td bgcolor=#f5f5f5 style=font-size:11px;>�ֹ�����</td>\n";
		echo "		<td bgcolor=#ffffff>".$temp;
		if(($_ord->del_gbn=="Y" || $_ord->del_gbn=="R") && !preg_match("/^(Y)$/",$_ord->deli_gbn)) {
			echo " &nbsp;&nbsp;&nbsp;<font color=blue>[�ֹ��ڰ� ������� ��ư�� ���� �ֹ���]</font>";
		}elseif($_ord->del_gbn=="Q"){
			echo " &nbsp;&nbsp;&nbsp;<font color=red>[�����ݾ� ����ġ�� ���� �ڵ� ��ҵ� �ֹ���]</font>";
		}
		echo "		</td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td bgcolor=#f5f5f5 style=font-size:11px;>�ֹ���</td>\n";
		echo "		<td bgcolor=#ffffff>".$_ord->sender_name;
		if(strlen($_ord->ordercode)==20 && substr($_ord->ordercode,-1)!="X") {
			echo "<A HREF=\"javascript:ReserveInfo('".$_ord->id."');\">(".$_ord->id.")</a> ";
			if(strlen($group_name)>0) echo " [ �׷�� : ".$group_name." ] ";
			if($hidedisplay!="Y") {
				echo "<a href=\"javascript:MemberMemo('".$_ord->id."')\"><img src='images/ordtl_icnmemo.gif' align=absmiddle border=0 alt='�޸� �Է�/�����ϱ�'></a> ";
				if(strlen(trim($usermemo))>0) {
					echo "<div id=\"membermemo_layer\" style=\"position:absolute; z-index:20; width:300;\"><table border=0 cellspacing=0 cellpadding=1 bgcolor=#7F7F65><tr><td style=\"padding:3\"><font color=#ffffff>".$usermemo." <a href=\"javascript:HideMemo()\"><img src=\"images/x.gif\" align=absmiddle border=0 alt=\"�����\"></a>&nbsp;</td></tr></table></div>";
				}
			}

			echo "&nbsp;&nbsp;������ : <A HREF=\"javascript:ReserveInOut('".$_ord->id."');\"><img src=\"images/btn_pm.gif\" width=\"35\" height=\"29\" border=\"0\"></A>&nbsp;";
			echo "<A HREF=\"javascript:ReserveInfo('".$_ord->id."');\"><img src=\"images/btn_detail.gif\" width=\"35\" height=\"29\" border=\"0\"></A>";

		} else {
			echo "(��ȸ���ֹ�)";
		}
		echo "		</td>\n";
		echo "	</tr>\n";
		if (strlen($_ord->ip)>0) {
			$ip = $_ord->ip;
			echo "	<tr>\n";
			echo "		<td bgcolor=#f5f5f5 style=font-size:11px;>�ֹ���IP</td>\n";
			echo "		<td bgcolor=#ffffff>".$ip."</td>\n";
			echo "	</tr>\n";
		}
		echo "	<tr>\n";
		echo "		<td bgcolor=#f5f5f5 style=font-size:11px;>����ó</td>\n";
		echo "		<td bgcolor=#ffffff><img src=\"images/ordtl_icntel.gif\" align=absmiddle>��ȭ : ".$_ord->sender_tel."";
		if($smsok==true) {
			echo "<span class=\"page_screen\">&nbsp;<a href=\"javascript:SendSMS('".$_ord->sender_tel."','".$_ord->receiver_tel1."','".$_ord->receiver_tel1."')\"><img src=\"images/ordtl_icnsms.gif\" border=0 align=absmiddle alt='sms������'></a></span>";
		}
		echo "<img src=\"images/ordtl_icnemail.gif\" align=absmiddle>�̸��� : <a href=\"javascript:SendMail('".$_ord->sender_email."')\"><font color=#AA0000>".$_ord->sender_email."</font></a>";
		echo "		</td>\n";
		echo "	</tr>\n";

		// �����ֹ�ǥ�ÿ���
		if ($hidedisplay!="Y") {
			if(strlen($_ord->ordercode)==20 && substr($_ord->ordercode,-1)!="X") {
				$sql = "SELECT COUNT(*) as cnt, SUM(price) as money FROM tblorderinfo ";
				$sql.= "WHERE id='".$_ord->id."' AND deli_gbn='Y'";
				$result=mysql_query($sql,get_db_conn());
				if($row=mysql_fetch_object($result)) {
					$ordercnt=$row->cnt;
					$ordersum=$row->money;
				}
				mysql_free_result($result);
				echo "	<tr>\n";
				echo "		<td bgcolor=#7F7F65 style=font-size:11px;><font color=#ffffff>���� �ֹ�</font></td>\n";
				echo "		<td bgcolor=#7F7F65 style=\"color:#ffffff\">";
				if($ordercnt!=0) {
					echo "�ֹ�Ƚ�� ".$ordercnt."��, ���ֹ��ݾ� ".number_format($ordersum)." (�߼ۿϷ� ����) ";
				} else {
					echo "ù���� ���Դϴ�.".((strlen($fist_rec_id)>0)? "��õ��(".$fist_rec_id.")���� ������".$totalRecom." ����":"");
				}
				echo "		&nbsp;&nbsp;<A HREF=\"javascript:HideDisplay()\"><img src=\"images/x.gif\" align=absmiddle border=0 alt=\"�����\"></A>";
				echo "		</td>\n";
				echo "	</tr>\n";
			}
		}
		if(sizeof($arTotalSns)>0){
			echo "	<tr>\n";
			echo "		<td bgcolor=#f5f5f5>sns ȫ����</td>\n";
			echo "		<td>";
			foreach($arTotalSns as $key => $var) {
				if($fist_rec_id != $key ){
					if($arSnsType[0] != "N") {
						echo $key."���� ������ ".$var." ����<br>";
						$sell_memid .=$key."||";
						$sell_memid_reserve .=$var."||";
					}
				}
			}
			echo "	</td></tr>\n";
		}
		echo "	<tr>\n";
		echo "		<td bgcolor=#f5f5f5 style=font-size:11px;>�޴º�</td>\n";
		echo "		<td bgcolor=#ffffff>".$_ord->receiver_name."</td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td bgcolor=#f5f5f5 valign=top style=\"padding-top:5px; font-size:11px;\">�޴� �ּ�</td>\n";
		echo "		<td bgcolor=#ffffff>\n";
		echo "		<span class=\"page_screen\">\n";
		$address = eregi_replace("\n"," ",trim($_ord->receiver_addr));
		$address = eregi_replace("\r"," ",$address);
		$pos=strpos($address,"�ּ�");
		if ($pos>0) {
			$post = trim(substr($address,0,$pos));
			$address = substr($address,$pos+7);
		}
		$post = ereg_replace("�����ȣ : ","",$post);
		//$arpost = explode("-",$post);
		echo "		�����ȣ : <input name='post1' id='post1' size=5 value=\"".$post."\" onclick=\"this.blur();addr_search_for_daumapi('post1','address1','');\">\n";
		echo "		<input type=button value='�����ȣ�˻�' style=\"cursor:hand;color:#FFFFFF;border-color:#666666;background-color:#666666;font-size:8pt;font-family:Tahoma;height:20px;width:80\" onclick=\"addr_search_for_daumapi('post1','address1','');\"><br />\n";
		echo "		��&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;�� : <input type=text name='address1' id='address1' size=50 value=\"".$address."\"> <input type=button value='�ּҼ���' style=\"cursor:hand;color:#FFFFFF;border-color:#666666;background-color:#666666;font-size:8pt;font-family:Tahoma;height:20px;width:60\" onclick=\"AddressUpdate()\">\n";
		echo "		</span>\n";
		echo "		<span class=\"page_print\">\n";
		echo "		�����ȣ : ".$post."<br>\n";
		echo "		&nbsp;&nbsp;��&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;��: ".$address."\n";
		echo "		</span>\n";
		echo "		</td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td bgcolor=#f5f5f5 style=font-size:11px;>����ó</td>\n";
		echo "		<td bgcolor=#ffffff><img src=\"images/ordtl_icntel.gif\" align=absmiddle>��ȭ : ".$_ord->receiver_tel1." , ".$_ord->receiver_tel2."</td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td bgcolor=#f5f5f5 style=font-size:11px;>���� ���</td>\n";
		echo "		<td bgcolor=#ffffff>";

		$pgdate = date("YmdHi",mktime(date("H")-2,date("i"),0,date("m"),date("d"),date("Y")));
		$arpm=array("B"=>"������","V"=>"������ü","O"=>"�������","Q"=>"�������(�Ÿź�ȣ)","C"=>"�ſ�ī��",/*"P"=>"�ſ�ī��(�Ÿź�ȣ)",*/"M"=>"�ڵ���");

		if($_ord->pay_data=="�ſ�ī����� - ī���ۼ���" && substr($_ord->ordercode,0,12)<=$pgdate) $_ord->pay_data=$arpm[substr($_ord->paymethod,0,1)]." ����";

		if (preg_match("/^(B|O|Q){1}/",$_ord->paymethod)) {	//������, �������, ������� ����ũ��
			if($_ord->paymethod=="B") echo "<font color=#FF5D00>�������Ա�</font>\n";
			else if(substr($_ord->paymethod,0,1)=="O") echo "<font color=#FF5D00>�������</font>\n";
			else echo "�Ÿź�ȣ - �������";

			if(!preg_match("/^(C|D)$/",$_ord->deli_gbn) || $_ord->paymethod=="B"){
				echo "�� ".$_ord->pay_data." ��";
				if($_ord->paymethod=="B" && strlen(trim($_ord->bankname)) > 0) echo "</td>\n</tr>\n<tr>\n<td bgcolor=#f5f5f5 style=font-size:11px;>�Ա��ڸ�</td><td bgcolor=#ffffff>".$_ord->bankname."</td>";
			}else echo "�� ���� ��� ��";
			/*if(!preg_match("/^(C|D)$/",$_ord->deli_gbn) || $_ord->paymethod=="B") echo "�� ".$_ord->pay_data." ��";
			else echo "�� ���� ��� ��";
			*/
			if (strlen($_ord->bank_date)>=12) {
				echo "</td>\n</tr>\n";
				echo "<tr>\n";
				echo "	<td bgcolor=#f5f5f5 style=font-size:11px;><FONT COLOR=red><B>�Ա�Ȯ��</B></FONT></td>\n";
				echo "	<td bgcolor=#ffffff><B><font color=red>".substr($_ord->bank_date,0,4)."/".substr($_ord->bank_date,4,2)."/".substr($_ord->bank_date,6,2)." (".substr($_ord->bank_date,8,2).":".substr($_ord->bank_date,10,2).")</font></B>";
			} else if(strlen($_ord->bank_date)==9) {
				echo "</td>\n</tr>\n";
				echo "<tr>\n";
				echo "	<td bgcolor=#f5f5f5><FONT COLOR=red><B>�Ա�Ȯ��</B></FONT></td>\n";
				echo "	<td><B><font color=red>ȯ��</font></B>";
			}
		} else if(substr($_ord->paymethod,0,1)=="M") {	//�ڵ��� ����
			echo "�ڵ��� ������ ";
			if ($_ord->pay_flag=="0000") {
				if($_ord->pay_admin_proc=="C") echo "�� <font color=red>������� �Ϸ�</font> ��";
				else echo "<font color=red>������ ���������� �̷�������ϴ�.</font>";
			}
			else echo "������ ���еǾ����ϴ�.";
			echo " ��";
		} else if(substr($_ord->paymethod,0,1)=="P") {	//�Ÿź�ȣ �ſ�ī��
			echo "�Ÿź�ȣ - �ſ�ī��";
			if($_ord->pay_flag=="0000") {
				if($_ord->pay_admin_proc=="C") echo "�� <font color=red>ī����� ��ҿϷ�</font> ��";
				else if($_ord->pay_admin_proc=="Y") echo "�� ī�� ���� �Ϸ� * �����մϴ�. : ���ι�ȣ ".$_ord->pay_auth_no." ��";
			}
			else echo "�� ".$_ord->pay_data." ��";
		} else if (substr($_ord->paymethod,0,1)=="C") {	//�Ϲݽſ�ī��
			echo "<font color=#FF5D00>�ſ�ī��</font>\n";
			if($_ord->pay_flag=="0000") {
				if($_ord->pay_admin_proc=="C") echo "�� <font color=red>ī����� ��ҿϷ�</font> ��";
				else if($_ord->pay_admin_proc=="Y") echo "�� ī�� ���� �Ϸ� * �����մϴ�. : ���ι�ȣ ".$_ord->pay_auth_no." ��";
			}
			else echo "�� ".$_ord->pay_data." ��";
		} else if (substr($_ord->paymethod,0,1)=="V") {
			echo "�ǽð� ������ü : ";
			if ($_ord->pay_flag=="0000") {
				if($_ord->pay_admin_proc=="C") echo "�� <font color=005000> [ȯ��]</font> ��";
				else echo "<font color=red>".$_ord->pay_data."</font>";
			}
			else echo "������ ���еǾ����ϴ�.";
		}

		if(preg_match("/^(Q|P){1}/",$_ord->paymethod) && preg_match("/^(Y)$/",$_ord->escrow_result) && $_ord->deli_gbn!="C") echo " - <font color=red><b>[����Ȯ��]</b></font>";
		else if(preg_match("/^(Q|P){1}/",$_ord->paymethod) && preg_match("/^(C)$/",$_ord->escrow_result) && $_ord->deli_gbn=="C") echo " - <font color=red><b>[�������]</b></font>";
		echo "		</td>\n";
		echo "	</tr>\n";
		$ardelivery=array("Y"=>"�߼���","N"=>"�̹߼�","C"=>"�ֹ����","X"=>"��ۿ�û","S"=>"�߼��غ�","D"=>"��ҿ�û","W"=>"���öȸ��û","E"=>"ȯ�Ҵ��","H"=>"���(���꺸��)");
		echo "	<tr>\n";
		echo "		<td bgcolor=#f5f5f5 style=font-size:11px;>�߼� ����</td>\n";
		echo "		<td bgcolor=#ffffff><font color=#A00000>".$ardelivery[$_ord->deli_gbn]."</font>";
		if(strlen($_ord->deli_date)==14) {
			echo " - �߼ۼ����� : ".substr($_ord->deli_date,0,4)."/".substr($_ord->deli_date,4,2)."/".substr($_ord->deli_date,6,2)." (".substr($_ord->deli_date,8,2).":".substr($_ord->deli_date,10,2).")";
		}

		// KCP �Ÿź�ȣ ��������̸鼭 ��ҿ�û�� ��� ȯ�Ұ��� �̸� ���
		if(preg_match("/^(Q){1}/",$_ord->paymethod) && strlen($_ord->bank_date)==14 && ($pg_type!="C" && $pg_type!="D")) {
			if(strlen($_ord->deli_date)!=14) {
				echo " <a href='javascript:escrow_bank_account()'><font color=red><U>[ȯ�Ұ��¼����Է�]</U></font></a>";
			}
		}

		echo "		</td>\n";
		echo "	</tr>\n";
		if($in_reserve>0 && $_ord->deli_gbn=="N" && strlen($_ord->ordercode)==20 && substr($_ord->ordercode,-1)!="X"){
			echo "	<tr>\n";
			echo "		<td colspan=2 bgcolor=#ffffff style=padding-left:95px;><font color=#0000FF>&nbsp;&nbsp;* �߼ۿϷ� ��ư�� ������ ȸ������ ������</font> <font color=#A00000>".number_format($in_reserve)."��</font><font color=#0000FF>�� �����˴ϴ�.</font></td>\n";
			echo "	</tr>\n";
		}
		echo "	<tr height=22>\n";
		echo "		<td bgcolor=#f5f5f5 valign=top style=\"padding-top:5px; font-size:11px;\">�ֹ���û����</td>\n";
		echo "		<td bgcolor=#ffffff>".$message[0]."</td>\n";
		echo "	</tr>\n";

		for($j=0;$j<count($prdata);$j++) {
			if(strlen($prdata[$j]->order_prmsg)>0) {
				echo "	<tr height=22 class=\"page_screen\">\n";
				echo "		<td valign=middle>�ֹ��޼���</td>\n";
				echo "		<td style=\"padding-left:7;word-break:break-all\">";
				echo "	<FONT COLOR=\"#000000\"><B>��ǰ�� :</B></FONT> ".$prdata[$j]->productname."<BR>\n";
				echo "<textarea style=\"width:95%;height:38;overflow-x:hidden;overflow-y:auto;\" readonly>".$prdata[$j]->order_prmsg."</textarea>\n";
				echo "		</td>\n";
				echo "	</tr>\n";
				echo "<tr><td colspan=2 height=5></td></tr>\n";

				echo "	<tr height=22 class=\"page_print\">\n";
				echo "		<td valign=middle>�ֹ��޼���</td>\n";
				echo "		<td style=\"padding-left:7\">";
				echo "		<FONT COLOR=\"#000000\"><B>��ǰ�� :</B></FONT> ".$prdata[$j]->productname."<BR>\n";
				echo "		".$prdata[$j]->order_prmsg."";
				echo "		</td>\n";
				echo "	</tr>\n";
				echo "<tr><td colspan=2 height=3></td></tr>\n";
			}
		}

		echo "	<tr height=58 class=\"page_screen\">\n";
		echo "		<td bgcolor=#f5f5f5 valign=top style=\"padding-top:8px; font-size:11px;\">�ֹ����� �޸�</td>\n";
		echo "		<td bgcolor=#ffffff style=\"padding-top:3\">\n";
		echo "		<font style=\"line-height:20px\">&nbsp;&nbsp;<textarea name=memo1 cols=76 rows=3 style=\"font-size:9pt\">".$message[1]."</textarea>&nbsp;<input type=button value='�� ��' style=\"cursor:hand;color:#FFFFFF;border-color:#666666;background-color:#666666;font-size:8pt;font-family:Tahoma;height:20px;width:40\" onclick=\"MemoUpdate()\"><br>	&nbsp;&nbsp;<font color=#0000FF>*���θ� ��ڸ� Ȯ���Ҽ� �ִ� �ֹ����� �޸� ���� �� �ֽ��ϴ�.</font>";
		echo "		</td>\n";
		echo "	</tr>\n";
		if(strlen($message[1])>0) {
			echo "	<tr height=58 class=\"page_print\">\n";
			echo "		<td bgcolor=#f5f5f5 valign=top style=\"padding-top:8px; font-size:11px;\">�ֹ����� �޸�</td>\n";
			echo "		<td bgcolor=#ffffff style=\"padding-top:3\">".$message[1]."</td>\n";
			echo "	</tr>\n";
		}
		echo "	<tr height=42 class=\"page_screen\">\n";
		echo "		<td bgcolor=#f5f5f5 valign=top style=\"padding-top:8px; font-size:11px;\">���˸���</td>\n";
		echo "		<td bgcolor=#ffffff style=\"padding-top:3\">\n";
		echo "		<font style=\"line-height:20px\">&nbsp;&nbsp;<input type=text name=memo2 size=66 maxlength=100 value=\"".$message[2]."\">&nbsp;<input type=button value='�� ��' style=\"cursor:hand;color:#FFFFFF;border-color:#666666;background-color:#666666;font-size:8pt;font-family:Tahoma;height:20px;width:40\" onclick=\"MemoUpdate()\"><br> &nbsp;&nbsp;<font color=#0000FF>*�Է��� �Ͻø�, �� �ֹ���ȸ ȭ���� ���� ������ �˷��帳�ϴ�.</font>";
		echo "		</td>\n";
		echo "	</tr>\n";
		if(strlen($message[2])>0) {
			echo "	<tr height=58 class=\"page_print\">\n";
			echo "		<td bgcolor=#f5f5f5 valign=top style=\"padding-top:8px; font-size:11px;\">���˸���</td>\n";
			echo "		<td bgcolor=#ffffff style=\"padding-top:3\">".$message[2]."</td>\n";
			echo "	</tr>\n";
		}
		echo "	<input type=hidden name=paymethod value=\"".$_ord->paymethod."\">\n";
		echo "	<input type=hidden name=in_reserve value=\"".$in_reserve."\">\n";
		echo "	<input type=hidden name=sender_email value=\"".$_ord->sender_email."\">\n";
		echo "	<input type=hidden name=sender_tel value=\"".$_ord->sender_tel."\">\n";
		echo "	<input type=hidden name=order_msg value=\"".$message[0]."\">\n";
		echo "	<input type=hidden name=sort>\n";
		echo "	<input type=hidden name=recoveryquan value=\"N\">\n";
		echo "	<input type=hidden name=recoveryrese value=\"N\">\n";
		echo "	<input type=hidden name=canreserve>\n";
		echo "	<input type=hidden name=recoveryrecan value=\"N\">\n";
		echo "	<input type=hidden name=deli_name>\n";
		echo "	<input type=hidden name=deli_com>\n";
		echo "	<input type=hidden name=deli_num>\n";
		echo "	<input type=hidden name=delimailtype value=\"N\">\n";
		echo "	<input type=hidden name=sell_memid_reserve value=\"".$sell_memid_reserve."\">\n";
		echo "	<input type=hidden name=totalRecom value=\"".$totalRecom."\">\n";
		echo "	<input type=hidden name=sell_memid value=\"".$sell_memid."\">\n";
		echo "	</form>\n";
		echo "	</table>\n";
		echo "	</td>\n";
		echo "</tr>\n";

		if($pg_type=="A") {	//KCP
			echo "<form name=kcpform method=post action=\"".$Dir."paygate/A/cancel.php\">\n";
			echo "<input type=hidden name=sitecd value=\"".$pgid_info["ID"]."\">\n";
			echo "<input type=hidden name=sitekey value=\"".$pgid_info["KEY"]."\">\n";
			echo "<input type=hidden name=ordercode value=\"".$_ord->ordercode."\">\n";
			echo "<input type=hidden name=paymethod value=\"".substr($_ord->paymethod,0,1)."\">\n";
			echo "<input type=hidden name=return_host value=\"".urlencode(getenv("HTTP_HOST"))."\">\n";
			echo "<input type=hidden name=return_script value=\"".str_replace(getenv("HTTP_HOST"),"",$_ShopInfo->getShopurl()).AdminDir."order_detail.php"."\">\n";
			echo "<input type=hidden name=return_data value=\"ordercode=".$ordercode."\">\n";
			echo "<input type=hidden name=return_type value=\"form\">\n";
			echo "<input type=hidden name=uid>\n";
			echo "</form>\n";
		} else if($pg_type=="B") {	//LG������
			echo "<form name=dacomform method=post action=\"".$Dir."paygate/B/cancel.php\">\n";
			echo "<input type=hidden name=mid value=\"".$pgid_info["ID"]."\">\n";
			echo "<input type=hidden name=mertkey value=\"".$pgid_info["KEY"]."\">\n";
			echo "<input type=hidden name=ordercode value=\"".$_ord->ordercode."\">\n";
			echo "<input type=hidden name=paymethod value=\"".substr($_ord->paymethod,0,1)."\">\n";
			echo "<input type=hidden name=return_host value=\"".urlencode(getenv("HTTP_HOST"))."\">\n";
			echo "<input type=hidden name=return_script value=\"".str_replace(getenv("HTTP_HOST"),"",$_ShopInfo->getShopurl()).AdminDir."order_detail.php"."\">\n";
			echo "<input type=hidden name=return_data value=\"ordercode=".$ordercode."\">\n";
			echo "<input type=hidden name=return_type value=\"form\">\n";
			echo "<input type=hidden name=uid>\n";
			echo "</form>\n";
		} else if($pg_type=="C") {	//�ô�����Ʈ
			echo "<form name=allthegateform method=post action=\"".$Dir."paygate/C/cancel.php\">\n";
			echo "<input type=hidden name=\"storeid\" value=\"".$pgid_info["ID"]."\">\n";
			echo "<input type=hidden name=\"ordercode\" value=\"".$_ord->ordercode."\">\n";
			echo "<input type=hidden name=\"paymethod\" value=\"".substr($_ord->paymethod,0,1)."\">\n";
			echo "<input type=hidden name=\"return_host\" value=\"".urlencode(getenv("HTTP_HOST"))."\">\n";
			echo "<input type=hidden name=\"return_script\" value=\"".str_replace(getenv("HTTP_HOST"),"",$_ShopInfo->getShopurl()).AdminDir."order_detail.php"."\">\n";
			echo "<input type=hidden name=\"return_data\" value=\"ordercode=".$ordercode."\">\n";
			echo "<input type=hidden name=\"return_type\" value=\"form\">\n";
			echo "<input type=hidden name=uid>\n";
			echo "</form>\n";
		}else if($pg_type=="D") {	//�̴Ͻý�
			echo "<form name=inicisform method=post action=\"".$Dir."paygate/D/cancel.php\">\n";
			echo "<input type=hidden name=sitecd value=\"".$pgid_info["ID"]."\">\n";
			echo "<input type=hidden name=ordercode value=\"".$_ord->ordercode."\">\n";
			echo "<input type=hidden name=paymethod value=\"".substr($_ord->paymethod,0,1)."\">\n";
			echo "<input type=hidden name=return_host value=\"".urlencode(getenv("HTTP_HOST"))."\">\n";
			echo "<input type=hidden name=return_script value=\"".str_replace(getenv("HTTP_HOST"),"",$_ShopInfo->getShopurl()).AdminDir."order_detail.php"."\">\n";
			echo "<input type=hidden name=return_data value=\"ordercode=".$ordercode."\">\n";
			echo "<input type=hidden name=return_type value=\"form\">\n";
			echo "<input type=hidden name=uid>\n";
			echo "</form>\n";
		}else if($pg_type=="E") {	//���̽�
			echo "<form name=niceform method=post action=\"".$Dir."paygate/E/cancel.php\">\n";
			echo "<input type=hidden name=ordercode value=\"".$_ord->ordercode."\">\n";
			echo "<input type=hidden name=TID value=\"".$_ord->pay_auth_no."\">\n";
			echo "<input type=hidden name=CancelAmt value=\"".$_ord->price."\">\n";
			echo "<input type=hidden name=CancelMsg value=\"������ ���\">\n";
			echo "<input type=hidden name=PartialCancelCode value=\"0\">\n"; // ��ü ��� : 0 , �κ���� : 1
			echo "</form>\n";
		} else if($pg_type == "G"){
			echo "<form name=allatform method=post action=\"".$Dir."paygate/G/cancel.php\">\n";
			echo "<input type=hidden name=sitecd value=\"".$pgid_info["ID"]."\">\n";
			echo "<input type=hidden name=sitekey value=\"".$pgid_info["KEY"]."\">\n";
			echo "<input type=hidden name=ordercode value=\"".$_ord->ordercode."\">\n";
			echo "<input type=hidden name=paymethod value=\"".substr($_ord->paymethod,0,1)."\">\n";
			echo "<input type=hidden name=return_host value=\"".urlencode(getenv("HTTP_HOST"))."\">\n";
			echo "<input type=hidden name=return_script value=\"".str_replace(getenv("HTTP_HOST"),"",$_ShopInfo->getShopurl()).AdminDir."order_detail.php"."\">\n";
			echo "<input type=hidden name=return_data value=\"ordercode=".$ordercode."\">\n";
			echo "<input type=hidden name=return_type value=\"form\">\n";
			echo "<input type=hidden name=type value=\"A\">\n";
			echo "<input type=hidden name=uid>\n";
			echo "</form>\n";
		}
	}
?>
	</table>
	<!-- �ֹ����� �� -->
	</td>
</tr>

<form name=form_reg action="product_register.php" method=post>
<input type=hidden name=code>
<input type=hidden name=prcode>
<input type=hidden name=popup>
</form>

<form name=smsform action="sendsms.php" method=post target="sendsmspop">
<input type=hidden name=number>
</form>

<form name=formmemo method=post>
<input type=hidden name=ordercode value="<?=$_ord->ordercode?>">
<input type=hidden name=id>
</form>

<form name=formhide action="<?=$_SERVER[PHP_SELF]?>" method=post>
<input type=hidden name=ordercode value="<?=$_ord->ordercode?>">
<input type=hidden name=hidedisplay value="Y">
</form>

<form name=taxsaveform method=post action="<?=$Dir.FrontDir?>taxsave.php" target=taxsavepop>
<input type=hidden name=ordercode value="<?=$_ord->ordercode?>">
<input type=hidden name=productname value="<?=urlencode(titleCut(htmlspecialchars(strip_tags($taxsaveprname),ENT_QUOTES),30))?>">
</form>

<form name=taxprintform method=post action="taxprint.php">
<input type=hidden name=ordercode value="<?=$_ord->ordercode?>">
</form>

<form name=vform action="<?=$Dir?>paygate/set_bank_account.php" method=post target="baccountpop">
<input type=hidden name=ordercode value="<?=$ordercode?>">
</form>

<form name=vForm action="vender_infopop.php" method=post>
<input type=hidden name=vender>
</form>


<form name="reTmpForm" method="post">
<input type="hidden" name="ordercode" value="<?=$ordercode?>">
<input type="hidden" name="type">
<input type="hidden" name="goods">
<input type="hidden" name="status">
<input type="hidden" name="re_price">
<input type="hidden" name="refund_free">
<input type="hidden" name="bank">
<input type="hidden" name="account_name">
<input type="hidden" name="account_num">
<input type="hidden" name="dcPriceSend" value="0"/>

<input type="hidden" name="reserve">
<input type="hidden" name="re_reserve_max" id="re_reserve_max" value="<?= $re_reserve ?>"/>
<input type="hidden" name="re_price_max" id="re_price_max">
</form>


<form name=reserveform action="reserve_money.php" method=post>
<input type=hidden name=type>
<input type=hidden name=id>
</form>

<form name=reserveform2 action="member_reservelist.php" method=post target=reserve_info>
<input type=hidden name=id>
<input type=hidden name=type>
</form>

<tr><td height=10></td></tr>

</table>



<script src="http://dmaps.daum.net/map_js_init/postcode.v2.js"></script>
<script type="text/javascript">
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
</script>




<!-- CS ���� -->
<? /*
<table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin-top:40px;">
	<tr><td align="right" style="padding-right:17px; padding-bottom:5px;"><a href="http://www.getmall.co.kr/data/cs_manual.zip"><img src="images/btn_csmanual.gif" border="0" align="absmiddle" alt="CS���� �Ŵ���" /></a></td></tr>
	<tr>
		<td style="padding:0px 17px;">
<table border="0" cellpadding="0" cellspacing="1" width="100%" bgcolor="#e0e0e0">
	<colgroup>
	<col width="35" align="center">
	<col width="70" align="center">
	<col>
	<col width="80" align="center">
	<col width="80" align="center">
	<col width="80" align="center">
	<col width="80" align="center">
	</colgroup>
	<tr>
		<td colspan="7" height="32" bgcolor="#efefef"><span style="font-size:11px;"><b>CS ó������</b></span></td>
	</tr>
	<tr bgcolor="#efefef" align="center">
		<td style="height:28px; font-size:11px;">����</td>
		<td style="font-size:11px;">����</td>
		<td style="font-size:11px;">��ǰ��</td>
		<td style="font-size:11px;">����</td>
		<td style="font-size:11px;">�����</td>
		<td style="font-size:11px;">ó����</td>
		<td style="font-size:11px;">�Ϸ���</td>
	</tr>
	<?
		$csOrderListResult = mysql_query("SELECT * FROM `tbl_csManager` WHERE `order` = '".$_ord->ordercode."' ");
		$csOrderListCnt = mysql_num_rows ( $csOrderListResult );
		while ( $csOrderListRow = mysql_fetch_assoc ( $csOrderListResult ) ) {

			$productSQL = "SELECT * FROM `tblorderproduct` WHERE `ordercode`='".$csOrderListRow['order']."' AND `productcode`='".$csOrderListRow['product']."' LIMIT 1 ; ";
			$productResult=mysql_query($productSQL,get_db_conn());
			$productRow=mysql_fetch_assoc ($productResult);

			switch ( substr($csOrderListRow['type'],0,1) ) {
				case 1 : $csOrderType = "<font color='blue'>���</font>"; break;
				case 2 : $csOrderType = "<font color='red'>��ǰ</font>"; break;
				case 3 : $csOrderType = "��Ÿ"; break;
			}
	?>
	<tr bgcolor="#ffffff">
		<td><?=$csOrderType?></td>
		<td><?=$venderlist[$csOrderListRow['vender']]->id?></td>
		<td style="padding:5px;">
			<?=$productRow['productname']?>
			<?=($productRow['opt1_name'])?"(�ɼ�1:".$productRow['opt1_name'].")":""?>
			<?=($productRow['opt2_name'])?"(�ɼ�2:".$productRow['opt2_name'].")":""?>
		</td>
		<td style="padding:5px;"><?=$csOrderListRow['title']?></td>
		<td style="padding:5px; line-height:140%;"><?=$csOrderListRow['adminRegDate']?></td>
		<td style="padding:5px; line-height:140%;"><?=($csOrderListRow['venderRegDate'] > 0)?$csOrderListRow['venderRegDate']:"-"?></td>
		<td style="padding:5px; line-height:140%;"><?=($csOrderListRow['completeRegDate'] > 0)?$csOrderListRow['completeRegDate']:"-"?></td>
	</tr>
	<?
		}

		if( !$csOrderListCnt ){
			echo "<tr><td colspan=7 bgcolor=#ffffff height=24 align=center>��ϵ� CS ������ �����ϴ�.</td></tr>";
		}
	?>
</table>
		</td>
	</tr>
	<tr><td height="20"></td></tr>
</table>
*/ ?>
<div style="clear:both; height:40px; text-align:center;"><a href="javascript:window.close();"><img src="/images/common/bigview_btnclose.gif" border="0"/></a></div>

<?=$onload?>

<? if($ck_y=='Y') echo "<script>cgY();</script>"; ?>

</body>
</html>
