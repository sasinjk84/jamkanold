<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
INCLUDE ("access.php");

function getDeligbn($ordercode,$deli_gbn) {
	//N:��ó��, X:��ۿ�û, S:�߼��غ�, Y:��ۿϷ�, C:�ֹ����, R:�ݼ�, D:��ҿ�û, E:ȯ�Ҵ��[��������� ��츸]
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

$ordercode= isset($_POST["ordercode"]) ? $_POST["ordercode"] : $_GET["ordercode"];
$type=$_POST["type"];
$mode=$_POST["mode"];
$hidedisplay=$_POST["hidedisplay"];

$order_msg=$_POST["order_msg"];

$rescode=$_POST["rescode"];
$pay_admin_proc=$_POST["pay_admin_proc"];

if($type=="sort") {
	$sort=$_POST["sort"];
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
if($type=="reauth" && strlen($ordercode)>0) {
	$sql = "SELECT * FROM tblgift_info WHERE ordercode='{$ordercode}'";
	$result2=mysql_query($sql,get_db_conn());
	if($rows=mysql_fetch_array($result2)) {

		$authcode = $rows['authcode1']."-".$rows['authcode2'];
		SendGiftAuthMail($_shopdata->shopname, $shopurl, $_shopdata->design_mail, $_shopdata->info_email, $ordercode, $authcode,$rows['price']);

		$sql="SELECT * FROM tblsmsinfo WHERE mem_gift='Y' ";
		$result=mysql_query($sql,get_db_conn());
		if($rowsms=mysql_fetch_object($result)) {
			$sms_id=$rowsms->id;
			$sms_authkey=$rowsms->authkey;

			$sname=$_ord->sender_name;

			if(strlen($rowsms->msg_mem_gift)==0) $rowsms->msg_mem_gift="[".strip_tags($_shopdata->shopname)."] [NAME]���� ��ǰ���� �����ϼ̽��ϴ�. ������ȣ�� [AUTHCODE]�Դϴ�.";
			$patten=array("(\[NAME\])","(\[AUTHCODE\])","(\[URL\])");
			$replace=array($sname,$authcode,"http://".$shopurl);

			$msg_mem_gift=preg_replace($patten,$replace,$rowsms->msg_mem_gift);
			$msg_mem_gift=addslashes($msg_mem_gift);

			$fromtel=$rowsms->return_tel;
			$date=0;
			$etcmsg="�����ϱ�޼���(ȸ��)";
			if($rowsms->use_mms=='Y') $use_mms = 'Y';
			else $use_mms = '';
			$temp=SendSMS2($sms_id, $sms_authkey, $_ord->receiver_tel1, "", $fromtel, $date, $msg_mem_gift, $etcmsg, $use_mms);
			$onload =  "<script>alert(\"������ȣ�� ��߼� �Ǿ����ϴ�.\");</script>";
		}
		mysql_free_result($result);
	}
	mysql_free_result($result2);
}
else if($type=="bank" && strlen($ordercode)>0) {
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

	$sql = "SELECT productcode,productname FROM tblorderproduct WHERE ordercode='".$ordercode."'";
	$result = mysql_query($sql,get_db_conn());
	$tmps = mysql_fetch_array($result);
	mysql_free_result($result);

	$sql = "SELECT consumerprice FROM tblproduct WHERE productcode='{$tmps['productcode']}'";
	$result = mysql_query($sql,get_db_conn());
	$save_price = mysql_fetch_array($result);
	$save_price = $save_price['consumerprice'];
	//echo $sql;
	mysql_free_result($result);


	if($_ord->gift=='2') {
		mysql_query("UPDATE tblorderinfo SET bank_date='".date("YmdHis")."', deli_gbn='Y' WHERE ordercode='".$ordercode."' ",get_db_conn());
		mysql_query("UPDATE tblorderproduct SET deli_gbn='Y' WHERE ordercode='".$ordercode."' ",get_db_conn());

		$isupdate=true;

		$sql = "INSERT tblreserve SET ";
		$sql.= "id			= '".$_ord->id."', ";
		$sql.= "reserve		= {$save_price}, ";
		$sql.= "reserve_yn	= 'Y', ";
		$sql.= "content		= '��ǰ�Ǳ��� ������ ��ȯ', ";
		$sql.= "orderdata	= '".$ordercode."=".$_ord->price."', ";
		$sql.= "date		= '".date("YmdHis")."' ";
		//echo $sql;
		mysql_query($sql,get_db_conn());

		$sql = "UPDATE tblmember SET reserve=reserve+".abs($save_price)." ";
		$sql.= "WHERE id='".$_ord->id."' ";
		mysql_query($sql,get_db_conn());
	}
	else if($_ord->gift=='1') {
		mysql_query("UPDATE tblorderinfo SET bank_date='".date("YmdHis")."', deli_gbn='X' WHERE ordercode='".$ordercode."' ",get_db_conn());
		mysql_query("UPDATE tblorderproduct SET deli_gbn='X' WHERE ordercode='".$ordercode."' ",get_db_conn());
		$isupdate=true;

		$sql = "SELECT productcode,productname FROM tblorderproduct WHERE ordercode='".$ordercode."'";
		$result = mysql_query($sql,get_db_conn());
		$tmps = mysql_fetch_array($result);
		mysql_free_result($result);

		$SID = md5(uniqid(rand()));
		$authcode1 = substr($SID, 0, 6);
		$SID = md5(uniqid(rand()));
		$authcode2 = substr($SID, 0, 6);

		$sql = "INSERT tblgift_info SET ";
		$sql.= "ordercode	= '{$ordercode}', ";
		$sql.= "send_id 	= '{$_ord->id}', ";
		$sql.= "name	= '{$tmps['productname']}', ";
		$sql.= "productcode	= '{$tmps['productcode']}', ";
		$sql.= "price	= '{$save_price}', ";
		$sql.= "authcode1	= '{$authcode1}', ";
		$sql.= "authcode2	= '{$authcode2}', ";
		$sql.= "status	= 'A', ";
		$sql.= "signdate	= '".time()."' ";
		mysql_query($sql,get_db_conn());

		$authcode = $authcode1."-".$authcode2;
		SendGiftAuthMail($_shopdata->shopname, $shopurl, $_shopdata->design_mail, $_shopdata->info_email, $ordercode, $authcode,$save_price);

		$sql="SELECT * FROM tblsmsinfo WHERE mem_gift='Y' ";
		$result=mysql_query($sql,get_db_conn());
		if($rowsms=mysql_fetch_object($result)) {
			$sms_id=$rowsms->id;
			$sms_authkey=$rowsms->authkey;

			$sname=$_ord->sender_name;

			if(strlen($rowsms->msg_mem_gift)==0) $rowsms->msg_mem_gift="[".strip_tags($_shopdata->shopname)."] [NAME]���� ��ǰ���� �����ϼ̽��ϴ�. ������ȣ�� [AUTHCODE]�Դϴ�.";
			$patten=array("(\[NAME\])","(\[AUTHCODE\])","(\[URL\])");
			$replace=array($sname,$authcode,"http://".$shopurl);

			$msg_mem_gift=preg_replace($patten,$replace,$rowsms->msg_mem_gift);
			$msg_mem_gift=addslashes($msg_mem_gift);

			$fromtel=$rowsms->return_tel;
			$date=0;
			$etcmsg="�����ϱ�޼���(ȸ��)";
			if($rowsms->use_mms=='Y') $use_mms = 'Y';
			else $use_mms = '';
			$temp=SendSMS2($sms_id, $sms_authkey, $_ord->receiver_tel1, "", $fromtel, $date, $msg_mem_gift, $etcmsg, $use_mms);
		}
		mysql_free_result($result);
	}

	if(strlen($_ord->sender_email)>0) {
		SendBankMail2($_shopdata->shopname, $shopurl, $_shopdata->design_mail, $_shopdata->info_email, $_ord->sender_email, $ordercode);
	}

	$sql="SELECT * FROM tblsmsinfo WHERE mem_bankok='Y' ";
	$result=mysql_query($sql,get_db_conn());
	if($rowsms=mysql_fetch_object($result)) {
		$sms_id=$rowsms->id;
		$sms_authkey=$rowsms->authkey;

		$bankprice=$_ord->price;
		$bankname=$_ord->sender_name;
		$msg_mem_bankok=$rowsms->msg_mem_bankok;
		if(strlen($msg_mem_bankok)==0) $msg_mem_bankok="[".strip_tags($_shopdata->shopname)."] [DATE]�� �ֹ��� �Ա�Ȯ�� �Ǿ����ϴ�.";
		$patten=array("(\[DATE\])","(\[NAME\])","(\[PRICE\])");
		$replace=array(substr($ordercode,0,4)."/".substr($ordercode,4,2)."/".substr($ordercode,6,2),$bankname,$bankprice);

		$msg_mem_bankok=preg_replace($patten,$replace,$msg_mem_bankok);
		$msg_mem_bankok=addslashes($msg_mem_bankok);

		$fromtel=$rowsms->return_tel;
		$date=0;
		$etcmsg="�Ա�Ȯ�θ޼���(ȸ��)";
		$temp=SendSMS($sms_id, $sms_authkey, $_ord->sender_tel, "", $fromtel, $date, $msg_mem_bankok, $etcmsg);
	}
	mysql_free_result($result);

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
	}
	echo "<script>if(opener) {opener.history.go(0);} window.close(); </script>";
	exit;

//��ۿϷ� ����
} else if($type=="delivery" && strlen($ordercode)>0) {
	$delimailok=$_POST["delimailtype"];	//��ۿϷῡ ���� ����/SMS�߼� ���� (Y:�߼�, N:�߼۾���)
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
			}
		}

		$sql = "UPDATE tblorderinfo SET deli_gbn='Y', deli_date='".date("YmdHis")."' ";
		$sql.= "WHERE ordercode='".$ordercode."' ";
		if(mysql_query($sql,get_db_conn())) {
			$sql = "UPDATE tblorderproduct SET deli_gbn='Y', deli_date='".date("YmdHis")."' ";
			$sql.= "WHERE ordercode='".$ordercode."' ";
			$sql.= "AND NOT (productcode LIKE '999%' OR productcode LIKE 'COU%') ";
			$sql.= "AND deli_gbn!='Y' ";
			mysql_query($sql,get_db_conn());
		}
		$isupdate=true;

		if($delimailok=="Y") {	//��ۿϷ� ������ �߼��� ���
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
				$sql.= "orderdata	= '".$ordercode."=".$_ord->price."', ";
				$sql.= "date		= '".date("YmdHis")."' ";
				mysql_query($sql,get_db_conn());
				$in_reserve=0;
			}
			mysql_free_result($result);
		}
		echo "<script>if(opener) {opener.history.go(0);} window.close(); </script>";
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
	echo "<script>if(opener) {opener.history.go(0);} window.close(); </script>";
	exit;

//����� �ּ� ������Ʈ
} else if($type=="addressupdate" && strlen($ordercode)>0) {
	$post1=$_POST["post1"];
	$post2=$_POST["post2"];
	$address1=$_POST["address1"];
	$receiver_addr="�����ȣ : ".$post1."-".$post2."\\n�ּ� : ".$address1;
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
			$sql.= "b.option_quantity,b.option1,b.option2,a.package_idx,a.assemble_idx,a.assemble_info FROM tblorderproduct a, tblproduct b ";
			$sql.= "WHERE a.productcode=b.productcode AND a.ordercode='".$ordercode."' ";
			$result=mysql_query($sql,get_db_conn());
			$message="";
			while ($row=mysql_fetch_object($result)) {
				$tmpoptq="";
				if(strlen($artmpoptq[$row->productcode])>0)
					$optq=$artmpoptq[$row->productcode];
				else
					$optq=$row->option_quantity;

				if(strlen($optq)>51 && substr($row->opt1_name,0,5)!="[OPTG"){
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
					if($optioncnt[($opt_no2-1)*10+($opt_no1-1)]!="") $optioncnt[($opt_no2-1)*10+($opt_no1-1)]+=$row->quantity;
					for($j=0;$j<5;$j++){
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

		$sql = "UPDATE tblmember SET reserve=reserve+".abs($_ord->reserve)." ";
		$sql.= "WHERE id='".$_ord->id."' ";
		mysql_query($sql,get_db_conn());

		$sql = "INSERT tblreserve SET ";
		$sql.= "id			= '".$_ord->id."', ";
		$sql.= "reserve		= '".$_ord->reserve."', ";
		$sql.= "reserve_yn	= 'Y', ";
		$sql.= "content		= '�ֹ� ��Ұǿ� ���� ������ ȯ��', ";
		$sql.= "orderdata	= '".$ordercode."=".$_ord->price."', ";
		$sql.= "date		= '".date("YmdHis")."' ";
		mysql_query($sql,get_db_conn());

		$log_content="## ȸ�� ������ ȯ�� ## - �ֹ���ȣ : ".$ordercode." - ������ ".$_ord->reserve;
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
	if($deli_gbn=="Y") $sql.= "deli_date='".date("YmdHis")."' ";
	else $sql.= "deli_date=NULL ";
	$sql.= "WHERE ordercode='".$ordercode."' AND productcode IN ('".$prlist."') ";
	$sql.= "AND NOT (productcode LIKE 'COU%' OR productcode LIKE '999999%') ";
	if(mysql_query($sql,get_db_conn())) {
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
			$sql = "UPDATE tblorderproduct SET deli_com='".$deli_com."', deli_num='".$deli_num."' ";
			$sql.= "WHERE ordercode='".$ordercode."' AND productcode='".$prcode."' ";
			$sql.= "AND NOT (productcode LIKE 'COU%' OR productcode LIKE '999999%') ";
			mysql_query($sql,get_db_conn());
		}
	}
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
//$sql="SELECT * FROM tbldelicompany ORDER BY company_name ";
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
	var oWidth = document.all.table_body.clientWidth + 30;
	//var oHeight = document.all.table_body.clientHeight + 55;
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
	if(temp=="card_ask") {			//���Կ�û
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
<?}?>
	}
}
//�߼��غ�, ��ۿϷ� ó��
function delisend(temp){
	if(!countdeli){
		if(temp=="Y" && !confirm("�Ա�Ȯ���� �ȵ� �ֹ����Դϴ�. ����� �Ϸ��Ͻðڽ��ϱ�?")) return;
		else if(temp=="S" && !confirm("�߼��غ� ���ø� �Ͻðڽ��ϱ�?")) return;
		else if(temp=="N" && !confirm("����� �Ϸ��Ͻðڽ��ϱ�?")) return;
		if(temp=="S") document.form2.type.value="readydeli";
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
					alert("����ũ�� ��ۿϷ� ó���� ���������� �Է��ؾ߸� ó���� �����մϴ�.");
					return;
				}
			}

			if(confirm("\n1. ��ǥ �������� ���Է��� ��� ��۸��Ͽ��� ���������� ��µ��� �ʽ��ϴ�.\n"+"2. ��ǥ �������� ���Է��� ��� �����ȣ �ȳ� SMS �� �߼۵��� �ʽ��ϴ�.\n\n          ��ۿϷ�� ������ ����/SMS�� �߼��Ͻðڽ��ϱ�?\n\n\n   * ��۾�ü : "+tmpdeliname+"\n\n   * �����ȣ : "+tmpdelinum+"")) {
				document.form2.delimailtype.value="Y";
			} else {
				document.form2.delimailtype.value="N";
			}
			if(!confirm("���� ����� �Ϸ��Ͻðڽ��ϱ�?")) return;

			document.form2.deli_com.value=tmpdelicom;
			document.form2.deli_num.value=tmpdelinum;
			document.form2.deli_name.value=tmpdeliname;
			document.form2.type.value="delivery";
		}
		countdeli++;

		document.form2.submit();
	}
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

//������ȣ ��߼�
function authsend(){
	document.form2.type.value="reauth";
	document.form2.submit();
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
	if(typeof(document.form1.chkprcode.length)=="number") {
		document.form1.prcodes.value="";
		cnt=document.form1.chkprcode.length;
		for(i=1;i<cnt;i++){
			if(document.form1.chkprcode[i].checked==true) {
				document.form1.prcodes.value+="PRCODE="+document.form1.chkprcode[i].value+",DELI_COM="+document.form1.chkdeli_com[i].value+",DELI_NUM="+document.form1.chkdeli_num[i].value+"|";
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
		else if(deli_gbn=="Y") delistr="[��ۿϷ�]";
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

//-->
</SCRIPT>
</head>
<!--body leftmargin=0 topmargin=0 marginwidth=0 marginheight=0 oncontextmenu="return false" style="overflow-x:hidden;overflow-y:hidden;" ondragstart="return false" onselectstart="return false" oncontextmenu="return false" onLoad="PageResize();"-->
<body leftmargin=0 topmargin=0 marginwidth=0 marginheight=0 oncontextmenu="PagePrint();return false;" style="overflow-x:hidden;overflow-y:auto;" onLoad="PageResize();">

<table border=0 cellpadding=0 cellspacing=0 width=645 style="table-layout:fixed;" id=table_body>
<tr class="page_screen">
	<td width=100% align=center>
	<table border=0 cellpadding=0 cellspacing=0 width=100%>
	<col width=5></col>
	<col width=></col>
	<col width=50></col>
	<tr><td colspan=3 height=10></td></tr>
	<tr>
		<td></td>
		<td>
<?
		$createbutton="<table border=0 cellpadding=0 cellspacing=0>";
		//��ó�� �Ǵ� ��ۿ�û���̰�, ī����Ұ� �ƴϰ�, ������ �Աݰ��̰�, �Ա��� �ȵƴٸ�,,,,,
		if(preg_match("/^(N|X)$/",$_ord->deli_gbn) && $_ord->pay_admin_proc!="C" && preg_match("/^(B){1}/",$_ord->paymethod) && strlen($_ord->bank_date)!=14) {
			$createbutton.="<tr><td align=right style='padding-right:40px' height=15><img src='images/ordtl_arrow1.gif' align=absmiddle></td></tr>";
		}
		$createbutton.="<tr><td>";

		//�ſ�ī�� ���� ������ ���
		if(preg_match("/^(C){1}/",$_ord->paymethod) && $_ord->pay_flag=="0000") {
			if($_ord->pay_admin_proc=="N" && strcmp($_ord->pay_flag,"0000")==0) {	//���Կ�û�� �ȵ� ���
				$createbutton.="<a href=\"javascript:card_ask('card_ask','card')\"><img src=\"images/ordtl_btncardok.gif\" align=absmiddle border=0></a>\n";	//ī�������û
				$createbutton.="<a href=\"javascript:card_ask('card_cancel','card')\"><img src=\"images/ordtl_btncardcancel.gif\" align=absmiddle border=0></a>\n";	//ī�����
				$createbutton.="&nbsp;&nbsp;&nbsp;<font color=#C0C0C0>|</font>&nbsp;";
			} else if($_ord->pay_admin_proc=="Y") {	//���Կ�û�� ���
				if(!preg_match("/^(Y|C)$/",$_ord->escrow_result)) {
					$createbutton.="<a href=\"javascript:card_ask('card_cancel','card')\"><img src=\"images/ordtl_btncardcancel.gif\" align=absmiddle border=0></a>\n";	//ī�����
					$createbutton.="&nbsp;&nbsp;&nbsp;<font color=#C0C0C0>|</font>&nbsp;";
				}
			}
		//�ڵ��� ������ ���
		} else if (preg_match("/^(M){1}/",$_ord->paymethod) && $_ord->pay_flag=="0000") {

			if($_ord->pay_admin_proc=="N" && strcmp($_ord->pay_flag,"0000")==0) {
				$createbutton.="<a href=\"javascript:card_ask('card_cancel','hp')\"><img src=\"images/ordtl_btnpaycancel.gif\" align=absmiddle border=0></a>\n";		//�������
				$createbutton.="&nbsp;&nbsp;&nbsp;<font color=#C0C0C0>|</font>&nbsp;";
			}

		//�ǽð�������ü/�Ϲݰ������ ȯ�Ҿȳ�
		} else if (preg_match("/^(V|O){1}/",$_ord->paymethod) && $_ord->pay_flag=="0000" && $_ord->pay_admin_proc!="C") {
			$createbutton.="<a href=\"javascript:alert('�ǽð�������ü �� ������� �������� �ý������� �ڵ� ȯ��ó���� �Ұ��Ͽ���\\n\\n�ֹ����ó�� �� ���������� ���� �Ǵ� ������ ȯ��ó���� �Ͻñ� �ٶ��ϴ�.')\"><img src=\"images/ordtl_refundinfo.gif\" border=0 align=absmiddle></a>\n";
			$createbutton.="&nbsp;<font color=#C0C0C0>|</font>&nbsp;";
		}

		//�ſ�ī�� ������ ���εǾ��ų� �Ǵ� (�������Ա��̰�, �ֹ���Ұ� �ƴϰ�, �Ա��� �Ϸ�Ǿ���) �Ǵ� (�ǽð�������ü�̰� ���������� �����Ȱ��̶��,,,)
		if($_ord->pay_admin_proc=="Y" || (preg_match("/^(B){1}/",$_ord->paymethod) && $_ord->deli_gbn!="C" && strlen($_ord->bank_date)==14) || (preg_match("/^(V|O){1}/",$_ord->paymethod) && $_ord->pay_flag=="0000" && $_ord->deli_gbn!="C")) {
			$createbutton.="<a href=\"javascript:printtax()\"><img src=\"images/ordtl_btntax.gif\" align=absmiddle border=0></a>\n";	//������ �߱�
		}
		if(preg_match("/^(B|O|Q){1}/",$_ord->paymethod) && $tax_type!="N" && $_ord->deli_gbn!="C") {
			$createbutton.="<a href=\"javascript:get_taxsave()\"><img src=\"images/ordtl_btntaxsave.gif\" align=absmiddle border=0></a>\n";	//���ݿ����� ��û
		}
		if(preg_match("/^(N|X|S)$/",$_ord->deli_gbn) && $_ord->pay_admin_proc!="C") {
			//(��ó��/��ۿ�û/��ۿϷ�) �ǰ� ī����� ��Ұ��� �ƴϸ�,,,,,
			//$createbutton.="<a href=\"javascript:printaddress()\"><img src=\"images/ordtl_btnprint.gif\" align=absmiddle border=0></a>\n";	//����� ���
			$createbutton.="<font color=#C0C0C0>|</font>&nbsp;";
			if(preg_match("/^(B){1}/",$_ord->paymethod) && strlen($_ord->bank_date)!=14) {
				//������ �Ա� �������̰�, �Ա��� �ȵ� ���
				$createbutton.="<a href=\"javascript:banksend()\"><img src=\"images/ordtl_btnbankok.gif\" align=absmiddle border=0></a><img src='images/ordtl_arrow2.gif' align=absmiddle>";	//�ԱݿϷ�


			} else if(!preg_match("/^(O|Q){1}/",$_ord->paymethod) || strlen($_ord->bank_date)>=12) {	//������� �Աݰǿ� ���ؼ� �Ա��� �� ���

				//����غ� �ܰ谡 �ƴϸ�,,,,,
				//if($_ord->deli_gbn!="S") $createbutton.="<a href=\"javascript:delisend('S')\"><img src=\"images/ordtl_btndeliready.gif\" align=absmiddle border=0></a>\n";	//�߼��غ�

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
				$createbutton.="		<td class=\"td_con1\"><select name=escrow_deli_com style=\"width:90;height:18;font-size:8pt\">\n";
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

				//$createbutton.="<a href=\"javascript:delisend('N')\"><img src=\"images/ordtl_btndeliok.gif\" align=absmiddle border=0></a>\n";	//��ۿϷ�
			}
		} else if($_ord->deli_gbn=="Y") {	//��ۿϷ�� �ǿ� ���ؼ�,,,,,,
			/*
			$createbutton.="<a href=\"javascript:printaddress()\"><img src=\"images/ordtl_btnprint.gif\" align=absmiddle border=0></a>\n";	//����� ���
			$createbutton.="<font color=#C0C0C0>|</font>&nbsp;";
			if(!preg_match("/^(Q|P){1}/",$_ord->paymethod)) $createbutton.="<a href=\"javascript:delicancel()\"><img src=\"images/ordtl_btndelino.gif\" align=absmiddle border=0></a>\n";	//�ݼ�ó��
			*/
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
			if(preg_match("/^(Y|D)$/",$_ord->deli_gbn) && strlen($_ord->deli_date)==14) {	//��ۿϷ�� ����ũ�� �������� "���꺸��" ��ư Ȱ��ȭ
				if($pg_type=="A" || $pg_type=="C" || $pg_type=="D") {
					$createbutton.="<a href=\"javascript:okhold()\"><img src=\"images/ordtl_btnescrowhold.gif\" align=absmiddle border=0></a>\n";	//���꺸��
				}
			} else {
				$createbutton.="<a href=\"javascript:okcancel('".substr($_ord->paymethod,0,1)."','".$_ord->bank_date."')\"><img src=\"images/ordtl_btnescrowcancel.gif\" align=absmiddle border=0></a>\n";	//���ó��
			}
		}

		$createbutton.="</td></tr>\n";
		$createbutton.="<tr><td height=10></td></tr>\n";
		$createbutton.="</table>\n";

		echo $createbutton;
?>
		</td>

		<td align=right style="padding-right:2pt">
		<!--table border=0 cellspacing=0 cellpadding=0 width=100%>
		<tr><td align=right style="padding-top:7"><img src="images/ordtl_close.gif" border=0 style="cursor:hand" onclick="window.close()"></td></tr>
		</table-->
		</td>
	</tr>
	</table>
	</td>
</tr>
<tr class="page_screen">
	<td>
	<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
	<col width=190></col>
	<col width=></col>
	<col width=100></col>

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
	<tr>
		<td style="padding-left:3pt">
		���� : <select name=sort style="width:90;height:18;font-size:8pt;" onChange="Sort(this.value);">
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
		<td align=center>
<?


?>
		</td>
		<?if($_ord->paymethod=="B" && $mode!="update"){?>
		<td align=right style="padding-right:2pt">
		<!-- <a href="javascript:ChangeUpdateMode('update')"><img src="images/ordtl_btnpriceup.gif" align=absmiddle border=0></a> -->
		</td>
		<?}?>
	</tr>
	</table>
	</td>
</tr>
<tr>
	<td align=center style="padding-left:3">
	<!-- �ֹ����� ���� -->
	<table border=1 cellpadding=0 cellspacing=0 width=100% bordercolorlight=#E2B892 bordercolordark=#ffffff style="table-layout:fixed">
	<col width=25></col>
	<?if($vendercnt>0){?>
	<col width=55></col>
	<?}?>
	<col width=></col>
	<col width=95></col>
	<col width=28></col>
	<col width=45></col>
	<col width=55></col>
	<?=($_ord->paymethod=="B" && $mode=="update"?"<col width=30></col>\n":"")?>
	<tr bgcolor=#efefef>
		<td align=center class="page_print">No</td><td align=center class="page_screen"><input type=checkbox name=allcheck onClick="CheckAll()"></td>
		<?if($vendercnt>0){?>
		<td align=center>������ü</td>
		<?}?>
		<td align=center>��ǰ��</td>
		<td align=center>���û���</td>
		<td align=center>����</td>
		<td align=center>������</td>
		<td align=center>����</td>
		<?=($_ord->paymethod=="B" && $mode=="update"?"<td>&nbsp;</td>\n":"")?>
	</tr>

	<input type=hidden name=chkprcode>
	<input type=hidden name=chkdeli_com>
	<input type=hidden name=chkdeli_num>
	<input type=hidden name=prcodes>

<?
	$colspan=6;
	if($vendercnt>0) $colspan++;

	$sql = "SELECT * FROM tblorderproduct WHERE ordercode='".$_ord->ordercode."' ";
	if(strlen($sort)>0) $sql.="ORDER BY ".$sort." ";
	$result=mysql_query($sql,get_db_conn());
	$sumquantity=0;
	$totalprice=0;
	$in_reserve=0;
	$cnt=0;
	$taxsaveprname="";
	while($row=mysql_fetch_object($result)) {
		if(ereg("^(COU)([0-9]{8})(X)$",$row->productcode)) {				#����
			//if($row->price!=0 && $row->price!=NULL) {
				$etcdata[]=$row;
				continue;
			//}
		} else if(ereg("^(9999999999)([0-9]{1})(X|R)$",$row->productcode)) {
			$etcdata[]=$row;
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
		$in_reserve+=$reserve;
		$totalprice+=$sumprice;

		$optvalue="";
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
						$packagestr.="		<col width=\"\"></col>\n";
						$packagestr.="		<col width=\"55\"></col>\n";
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
						$assemblestr.="		<col width=\"\"></col>\n";
						$assemblestr.="		<col width=\"55\"></col>\n";
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

		echo "<tr bgcolor=#FFFFFF>\n";

		echo "	<td align=center class=\"page_print\" style=\"font-size:8pt\"><font color=#878787>".$cnt."</td>\n";
		echo "	<td align=center class=\"page_screen\" style=\"font-size:8pt\"><input type=checkbox name=chkprcode value=\"".$row->productcode."\"></td>\n";

		if($vendercnt>0) {
			if($row->vender>0) {
				echo "	<td align=center style=\"font-size:8pt\"><a href=\"javascript:viewVenderInfo(".$row->vender.")\"><B>".$venderlist[$row->vender]->id."</B></a></td>\n";
			} else {
				echo "	<td align=center>&nbsp;</td>\n";
			}
		}

		if(file_exists($Dir.DataDir."shopimages/product/".$row->productcode."3.gif")) $file=$row->productcode."3.gif";
		else if(file_exists($Dir.DataDir."shopimages/product/".$row->productcode."3.jpg")) $file=$row->productcode."3.jpg";
		else $file="NO";
		if($file!="NO") {
			echo "	<td style=\"font-size:8pt;padding:7,7,5,5;line-height:10pt\">\n";
			echo "	".(strlen($row->selfcode)?"�����ڵ� : ".$row->selfcode."<br>":"")."<span style=\"line-height:10pt\" onMouseOver='ProductMouseOver($cnt)' onMouseOut=\"ProductMouseOut('primage".$cnt."');\">".$row->productname."";
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
			echo $packagestr;
			echo $assemblestr;
			echo "	</td>\n";
		} else {
			echo "	<td style=\"font-size:8pt;padding:2,5;line-height:10pt\">";
			echo (strlen($row->selfcode)?"�����ڵ� : ".$row->selfcode."<br>":"").$row->productname;
			if(strlen($row->addcode)>0) echo "<br><font color=blue><b>[</b>Ư��ǥ�� : ".$row->addcode."<b>]</b></font>";
			if(strlen($optvalue)>0) echo "<br><font color=red><b>[</b>�ɼǻ��� : ".$optvalue."<b>]</b></font>";
			echo $packagestr;
			echo $assemblestr;
			echo "	</td>\n";
		}

		echo "	<td style=\"font-size:8pt;padding:2,5;line-height:11pt\">";
		echo (strlen($row->opt1_name)>0?$row->opt1_name."<br>":"&nbsp;");
		if(strlen($row->opt2_name)>0) echo $row->opt2_name;
		echo "	</td>\n";
		if ($row->productcode=="99999999999X" || substr($row->productcode,0,3)=="COU" || $row->productcode=="99999999999R") { // ���ݰ����� ����ǥ�þ���
			echo "	<td><input type=hidden name=arquantity value=\"1\">&nbsp;</td>\n";
		} else {
			echo "	<td align=center style=\"font-size:8pt\" ".($_ord->paymethod!="B" || $mode!="update"?($row->quantity>1?" bgcolor=#FDE9D5><font color=#000000><b>":">").$row->quantity:"><input type=text style='text-align:right' name=arquantity value=\"".$row->quantity."\" style=\"font-size:8pt;width:100%\">")."</td>\n";
		}
		echo "	<td align=right style=\"font-size:8pt\">".(strlen($_ord->ordercode)==20 && substr($_ord->ordercode,-1)!="X" && substr($row->productcode,-4)!="GIFT"?($_ord->paymethod!="B" || $mode!="update"?number_format($reserve)."&nbsp;":"<input type=text style='font-size:8pt;text-align:right;width:100%' name=arreserve value=\"".$reserve."\">"):"<input type=hidden name=arreserve>&nbsp;")."</td>\n";
		echo "	<td align=right style=\"font-size:8pt\">".(substr($row->productcode,-4)!="GIFT"?$_ord->paymethod!="B" || $mode!="update"?number_format($sumprice)."&nbsp;":"<input type=text style='font-size:8pt;text-align:right;width:100%' name=arprice value=\"".$sumprice."\">":"&nbsp;<input type=hidden name=arprice>")."</td>\n";

		if($_ord->paymethod=="B" && $mode=="update") {
			echo "<td align=center><a href=\"javascript:OrderUpdate('1',".$cnt.",'".$row->vender."','".$row->productcode."','".$tempopt1."','".$row->opt2_name."')\"><img src='images/ordtl_miniup.gif' align=absmiddle border=0 alt='��ǰ����'></a><br><img width=0 height=2 border=0><br><a href=\"javascript:OrderDelete('1','".$row->vender."','".$row->productcode."','".$tempopt1."','".$row->opt2_name."')\"><img src='images/ordtl_minidel.gif' align=absmiddle border=0 alt='��ǰ����'></a></td>\n";
		}
		echo "</tr>\n";


	}
	mysql_free_result($result);


	echo "<tr height=30 bgcolor=#efefef>\n";
	echo "	<td align=center colspan=".($_ord->paymethod!="B" || $mode!="update"?$colspan:($colspan+1))."><B>�߰����/����/��������</B></td>";
	echo "</tr>\n";

	if(count($etcdata)>0) {
		for($j=0;$j<count($etcdata);$j++) {
			$cnt++;
			$sumprice=$etcdata[$j]->price;
			$reserve=$etcdata[$j]->reserve;
			$in_reserve+=$reserve;
			$totalprice+=$sumprice;
			echo "<tr>\n";
			echo "	<td>&nbsp;</td>\n";
			if($vendercnt>0) {
				if($etcdata[$j]->vender>0) {
					echo "	<td align=center style=\"font-size:8pt\"><a href=\"javascript:viewVenderInfo(".$etcdata[$j]->vender.")\"><B>".$venderlist[$etcdata[$j]->vender]->id."</B></a></td>\n";
				} else {
					echo "	<td align=center>&nbsp;</td>\n";
				}
			}
			echo "	<td style=\"font-size:8pt;padding:2,5;line-height:10pt\">".$etcdata[$j]->productname." <span class=\"page_screen\"><A style=\"cursor:hand\" onMouseOver='EtcMouseOver($cnt)' onMouseOut=\"EtcMouseOut($cnt);\"><img src=images/btn_more02.gif border=0 align=absmiddle></A>";
			echo "	<div id=etcdtl".$cnt." style=\"position:absolute; z-index:100; visibility:hidden;\">\n";
			echo "	<table border=0 cellpadding=0 cellspacing=0 width=300 bgcolor=#A47917>\n";
			echo "	<tr><td align=center style=\"color:#FFFFFF;padding:5\"><B>###### �ش� ��ǰ�� ######</B></td></tr>\n";
			echo "	<tr><td style=\"font-size:8pt;color:#FFFFFF;padding:10;padding-top:0;line-height:11pt\">".$etcdata[$j]->order_prmsg."</td></tr>\n";
			echo "	</table>\n";
			echo "	</div>\n";
			echo "	</span>\n";
			echo "	</td>\n";
			echo "	<td>&nbsp;</td>\n";
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
			$sql = "SELECT memo,group_code FROM tblmember WHERE id='".$_ord->id."' ";
			$result=mysql_query($sql,get_db_conn());
			if ($row=mysql_fetch_object($result)) {
				$usermemo=$row->memo;
				$group_code=$row->group_code;
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
			if($vendercnt>0) {
				echo "	<td>&nbsp;</td>\n";
			}
			echo "	<td style=\"font-size:8pt;padding:2,5\"><font color=#0054A6>�����ݻ���</font></td>\n";
			echo "	<td>&nbsp;</td>\n";
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
		echo "	<td colspan=".($colspan-4)." style=\"font-size:8pt;padding:5,27\"><B>�� �հ�</B> </td>\n";
		echo "	<td>&nbsp;</td>\n";
		echo "	<td align=center style=\"font-size:8pt\">".$sumquantity."</td>\n";
		echo "	<td align=right style=\"font-size:8pt\">".(strlen($_ord->ordercode)==20 && substr($_ord->ordercode,-1)!="X"?number_format($in_reserve)."&nbsp;":"&nbsp")."</td>\n";
		echo "	<td align=right style=\"font-size:8pt\"> ".($_ord->paymethod!="B" || $mode!="update"?number_format($_ord->price)."&nbsp;":"<input type=text style='font-size:8pt;text-align:right;width:100%' name=sumprice value=\"".$_ord->price."\">")."</td>\n";
		if($_ord->paymethod=="B" && $mode=="update") {
			echo "	<td align=center><a href=javascript:OrderUpdate('5','','','','','')><img src='images/ordtl_miniup.gif' align=absmiddle border=0 alt='�ѱݾ׼���'></a></td>";
		}

		echo "</form>\n";

		echo "</tr>\n";

		$candate = date("Ymd",mktime(0,0,0,date("m"),date("d")-15,date("Y")));
		/*
		if((!preg_match("/^(R|A)$/", $_ord->del_gbn) && (!preg_match("/^(Q|P){1}/",$_ord->paymethod) || $_ord->price==0))
		|| (!preg_match("/^(R|A)$/", $_ord->del_gbn) && preg_match("/^(Q|P){1}/",$_ord->paymethod) && ($_ord->deli_gbn=="C" || substr($_ord->ordercode,0,8)<$candate) && $_ord->deli_gbn!="Y")) {
			echo "<tr bgcolor=#FFFFFF height=24 class=\"page_screen\">\n";
			echo "	<td align=right colspan=".($_ord->paymethod!="B" || $mode!="update"?$colspan:($colspan+1))."><B>���Ա� �� �ֹ���ҿ� ���� :</B> <a href=\"javascript:RestoreOrder('quan','".$_ord->deli_gbn."','".$_ord->reserve."')\"><img src=\"images/ordtl_restorequan.gif\" border=0 align=absmiddle></a>";
			if($_ord->deli_gbn!="C") {
				echo " <a href=\"javascript:RestoreOrder('can','".$_ord->deli_gbn."','".$_ord->reserve."')\"><img src=\"images/ordtl_restorecan.gif\" border=0 align=absmiddle></a>";
			}
			if($_ord->deli_gbn!="C" && $_ord->reserve>0) {
				echo " <a href=\"javascript:RestoreReserve()\"><img src=\"images/ordtl_restoreres.gif\" border=0 align=absmiddle></a>";
			}
			if($norecan!="Y" && $_ord->deli_gbn!="C" && preg_match("/^(Y|D|H)$/",$_ord->deli_gbn) && strlen($_ord->deli_date)==14 && $in_reserve>0 && strlen($_ord->ordercode)==20 && substr($_ord->ordercode,-1)!="X") {
				echo " <a href=\"javascript:RestoreReserveCancel('".$in_reserve."')\"><img src=\"images/ordtl_restorerescan.gif\" border=0 align=absmiddle></a>";
			}
			echo "&nbsp;</td>\n";
			echo "</tr>\n";
		}
		*/
        echo "<tr>\n";
		echo "	<td colspan=".($_ord->paymethod!="B" || $mode!="update"?$colspan:($colspan+1))." bgcolor=#fafafa align=center>\n";
		echo "	<table border=0 cellpadding=0 cellspacing=0 width=96%>\n";
		echo "	<col width=90 style=\"padding-left:3\"></col>\n";
		echo "	<col width=></col>\n";
		echo "	<tr><td colspan=2 height=5></td></tr>\n";
		echo "	<form name=form2 method=post action=\"".$_SERVER[PHP_SELF]."\">\n";
		echo "	<input type=hidden name=type>\n";
		echo "	<input type=hidden name=ordercode value=\"".$_ord->ordercode."\">\n";
		echo "	<input type=hidden name=id value=\"".urlencode($_ord->id)."\">\n";
		echo "	<tr>\n";
		echo "		<td>�ֹ� ����</td>\n";
		echo "		<td>: ".$temp;
		if(($_ord->del_gbn=="Y" || $_ord->del_gbn=="R") && !preg_match("/^(Y)$/",$_ord->deli_gbn)) {
			echo " &nbsp;&nbsp;&nbsp;<font color=blue>[�ֹ��ڰ� ������� ��ư�� ���� �ֹ���]</font>";
		}
		echo "		</td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td>�ֹ���</td>\n";
		echo "		<td>: ".$_ord->sender_name;
		if(strlen($_ord->ordercode)==20 && substr($_ord->ordercode,-1)!="X") {
			echo "(".$_ord->id.") ";
			if(strlen($group_name)>0) echo " [ �׷�� : ".$group_name." ] ";
			if($hidedisplay!="Y") {
				echo "<a href=\"javascript:MemberMemo('".$_ord->id."')\"><img src='images/ordtl_icnmemo.gif' align=absmiddle border=0 alt='�޸� �Է�/�����ϱ�'></a> ";
				if(strlen(trim($usermemo))>0) {
					echo "<div id=\"membermemo_layer\" style=\"position:absolute; z-index:20; width:300;\"><table border=0 cellspacing=0 cellpadding=1 bgcolor=#7F7F65><tr><td style=\"padding:3\"><font color=#ffffff>".$usermemo." <a href=\"javascript:HideMemo()\"><img src=\"images/x.gif\" align=absmiddle border=0 alt=\"�����\"></a>&nbsp;</td></tr></table></div>";
				}
			}
		} else {
			echo "(��ȸ���ֹ�)";
		}
		echo "		</td>\n";
		echo "	</tr>\n";
		if (strlen($_ord->ip)>0) {
			$ip = $_ord->ip;
			echo "	<tr>\n";
			echo "		<td>�ֹ���IP</td>\n";
			echo "		<td>: ".$ip."</td>\n";
			echo "	</tr>\n";
		}
		echo "	<tr>\n";
		echo "		<td>����ó</td>\n";
		echo "		<td>: <img src=\"images/ordtl_icntel.gif\" align=absmiddle>��ȭ : ".$_ord->sender_tel."";
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
				echo "		<td bgcolor=#7F7F65><font color=#ffffff>���� �ֹ�</font></td>\n";
				echo "		<td bgcolor=#7F7F65 style=\"color:#ffffff\">: ";
				if($ordercnt!=0) {
					echo "�ֹ�Ƚ�� ".$ordercnt."��, ���ֹ��ݾ� ".number_format($ordersum)." (��ۿϷ� ����) ";
				} else {
					echo "ù���� ���Դϴ�.";
				}
				echo "		&nbsp;&nbsp;<A HREF=\"javascript:HideDisplay()\"><img src=\"images/x.gif\" align=absmiddle border=0 alt=\"�����\"></A>";
				echo "		</td>\n";
				echo "	</tr>\n";
			}
		}

		if($_ord->gift=='1') {
			$address = eregi_replace("\n"," ",trim($_ord->receiver_addr));
			echo "	<tr>\n";
			echo "		<td>�޴º�</td>\n";
			echo "		<td>: ".$_ord->receiver_name."</td>\n";
			echo "	</tr>\n";
			echo "	<tr>\n";
			echo "		<td>�޴º� �̸���</td>\n";
			echo "		<td>: ".$address."</td>\n";
			echo "	</tr>\n";
			echo "	<tr>\n";
			echo "		<td>����ó</td>\n";
			echo "		<td>: <img src=\"images/ordtl_icntel.gif\" align=absmiddle>��ȭ : ".$_ord->receiver_tel1." , ".$_ord->receiver_tel2."</td>\n";
			echo "	</tr>\n";
		}
		echo "	<tr>\n";
		echo "		<td>���� ���</td>\n";
		echo "		<td>: ";

		$pgdate = date("YmdHi",mktime(date("H")-2,date("i"),0,date("m"),date("d"),date("Y")));
		$arpm=array("B"=>"������","V"=>"������ü","O"=>"�������","Q"=>"�������(�Ÿź�ȣ)","C"=>"�ſ�ī��",/*"P"=>"�ſ�ī��(�Ÿź�ȣ)",*/"M"=>"�ڵ���");

		if($_ord->pay_data=="�ſ�ī����� - ī���ۼ���" && substr($_ord->ordercode,0,12)<=$pgdate) $_ord->pay_data=$arpm[substr($_ord->paymethod,0,1)]." ����";

		if (preg_match("/^(B|O|Q){1}/",$_ord->paymethod)) {	//������, �������, ������� ����ũ��
			if($_ord->paymethod=="B") echo "<font color=#FF5D00>�������Ա�</font>\n";
			else if(substr($_ord->paymethod,0,1)=="O") echo "<font color=#FF5D00>�������</font>\n";
			else echo "�Ÿź�ȣ - �������";

			if(!preg_match("/^(C|D)$/",$_ord->deli_gbn) || $_ord->paymethod=="B") echo "�� ".$_ord->pay_data." ��";
			else echo "�� ���� ��� ��";

			if (strlen($_ord->bank_date)>=12) {
				echo "</td>\n</tr>\n";
				echo "<tr>\n";
				echo "	<td><FONT COLOR=red><B>�Ա�Ȯ��</B></FONT></td>\n";
				echo "	<td>: <B><font color=red>".substr($_ord->bank_date,0,4)."/".substr($_ord->bank_date,4,2)."/".substr($_ord->bank_date,6,2)." (".substr($_ord->bank_date,8,2).":".substr($_ord->bank_date,10,2).")</font></B>";
			} else if(strlen($_ord->bank_date)==9) {
				echo "</td>\n</tr>\n";
				echo "<tr>\n";
				echo "	<td><FONT COLOR=red><B>�Ա�Ȯ��</B></FONT></td>\n";
				echo "	<td>: <B><font color=red>ȯ��</font></B>";
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
		$ardelivery=array("Y"=>"�����Ϸ�","N"=>"������","C"=>"�ֹ����","X"=>"������ȣ�߼�","S"=>"�����Ϸ�","D"=>"��ҿ�û","E"=>"ȯ�Ҵ��","H"=>"���(���꺸��)");
		echo "	<tr>\n";
		echo "		<td>���� ����</td>\n";
		if($_ord->gift=='1') {
			if($_ord->deli_gbn!='Y')  echo "		<td>: <font color=#A00000>".$ardelivery[$_ord->deli_gbn]."</font>";
			else echo "		<td>: <font color=#A00000>�����������Ϸ�</font>";
			$sql = "SELECT * FROM tblgift_info WHERE ordercode='{$ordercode}'";
			$result2=mysql_query($sql,get_db_conn());
			$row2 = mysql_fetch_array($result2);
			mysql_free_result($result2);

			echo "&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"javascript:authsend()\" style='color:#FF0000;'>[������ȣ��߼�]</a>";
			echo "<br /> - ������ȣ �߼��� : ".date("Y-m-d H:i",$row2['signdate']).", ������ȣ : {$row2['authcode1']} - {$row2['authcode2']}";
			if($row2['use_id']) {
				echo " <br /> - ������ȣ ����� : ".date("Y-m-d H:i",$row2['use_date']).", ������ȣ ���ȸ�� : {$row2['use_id']}";
			}
		}
		else echo "		<td>: <font color=#A00000>".$ardelivery[$_ord->deli_gbn]."</font>";

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
			echo "		<td>&nbsp;</td>\n";
			echo "		<td><font color=#0000FF>&nbsp;&nbsp;* ��ۿϷ� ��ư�� ������ ȸ������ ������</font> <font color=#A00000>".number_format($in_reserve)."��</font><font color=#0000FF>�� �����˴ϴ�.</font></td>\n";
			echo "	</tr>\n";
		}
		echo "	<tr height=22>\n";
		echo "		<td valign=top style=\"padding-top:5\">�ֹ���û����</td>\n";
		echo "		<td>: ".$message[0]."</td>\n";
		echo "	</tr>\n";

		for($j=0;$j<count($prdata);$j++) {
			if(strlen($prdata[$j]->order_prmsg)>0) {
				echo "	<tr height=22 class=\"page_screen\">\n";
				echo "		<td valign=middle>�ֹ��޼���</td>\n";
				echo "		<td style=\"padding-left:7\">";
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
		echo "		<td valign=top style=\"padding-top:8\">�ֹ����� �޸�</td>\n";
		echo "		<td style=\"padding-top:3\">\n";
		echo "		<font style=\"line-height:20px\">&nbsp;&nbsp;<textarea name=memo1 cols=76 rows=3 style=\"font-size:9pt\">".$message[1]."</textarea>&nbsp;<input type=button value='�� ��' style=\"cursor:hand;color:#FFFFFF;border-color:#666666;background-color:#666666;font-size:8pt;font-family:Tahoma;height:20px;width:40\" onclick=\"MemoUpdate()\"><br>	&nbsp;&nbsp;<font color=#0000FF>*���θ� ��ڸ� Ȯ���Ҽ� �ִ� �ֹ����� �޸� ���� �� �ֽ��ϴ�.</font>";
		echo "		</td>\n";
		echo "	</tr>\n";
		if(strlen($message[1])>0) {
			echo "	<tr height=58 class=\"page_print\">\n";
			echo "		<td valign=top style=\"padding-top:8\">�ֹ����� �޸�</td>\n";
			echo "		<td style=\"padding-top:3\">: \n";
			echo "		".$message[1]."\n";
			echo "		</td>\n";
			echo "	</tr>\n";
		}
		echo "	<tr height=42 class=\"page_screen\">\n";
		echo "		<td valign=top style=\"padding-top:8\">���˸���</td>\n";
		echo "		<td style=\"padding-top:3\">\n";
		echo "		<font style=\"line-height:20px\">&nbsp;&nbsp;<input type=text name=memo2 size=66 maxlength=100 value=\"".$message[2]."\">&nbsp;<input type=button value='�� ��' style=\"cursor:hand;color:#FFFFFF;border-color:#666666;background-color:#666666;font-size:8pt;font-family:Tahoma;height:20px;width:40\" onclick=\"MemoUpdate()\"><br> &nbsp;&nbsp;<font color=#0000FF>*�Է��� �Ͻø�, �� �ֹ���ȸ ȭ���� ���� ������ �˷��帳�ϴ�.</font>";
		echo "		</td>\n";
		echo "	</tr>\n";
		if(strlen($message[2])>0) {
			echo "	<tr height=58 class=\"page_print\">\n";
			echo "		<td valign=top style=\"padding-top:8\">���˸���</td>\n";
			echo "		<td style=\"padding-top:3\">: \n";
			echo "		".$message[2]."\n";
			echo "		</td>\n";
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
			echo "<input type=hidden name=return_script value=\"".str_replace(getenv("HTTP_HOST"),"",$_ShopInfo->getShopurl()).AdminDir."order_detail2.php"."\">\n";
			echo "<input type=hidden name=return_data value=\"ordercode=".$ordercode."\">\n";
			echo "<input type=hidden name=return_type value=\"form\">\n";
			echo "</form>\n";
		} else if($pg_type=="B") {	//LG������
			echo "<form name=dacomform method=post action=\"".$Dir."paygate/B/cancel.php\">\n";
			echo "<input type=hidden name=mid value=\"".$pgid_info["ID"]."\">\n";
			echo "<input type=hidden name=mertkey value=\"".$pgid_info["KEY"]."\">\n";
			echo "<input type=hidden name=ordercode value=\"".$_ord->ordercode."\">\n";
			echo "<input type=hidden name=paymethod value=\"".substr($_ord->paymethod,0,1)."\">\n";
			echo "<input type=hidden name=return_host value=\"".urlencode(getenv("HTTP_HOST"))."\">\n";
			echo "<input type=hidden name=return_script value=\"".str_replace(getenv("HTTP_HOST"),"",$_ShopInfo->getShopurl()).AdminDir."order_detail2.php"."\">\n";
			echo "<input type=hidden name=return_data value=\"ordercode=".$ordercode."\">\n";
			echo "<input type=hidden name=return_type value=\"form\">\n";
			echo "</form>\n";
		} else if($pg_type=="C") {	//�ô�����Ʈ
			echo "<form name=allthegateform method=post action=\"".$Dir."paygate/C/cancel.php\">\n";
			echo "<input type=hidden name=\"storeid\" value=\"".$pgid_info["ID"]."\">\n";
			echo "<input type=hidden name=\"ordercode\" value=\"".$_ord->ordercode."\">\n";
			echo "<input type=hidden name=\"paymethod\" value=\"".substr($_ord->paymethod,0,1)."\">\n";
			echo "<input type=hidden name=\"return_host\" value=\"".urlencode(getenv("HTTP_HOST"))."\">\n";
			echo "<input type=hidden name=\"return_script\" value=\"".str_replace(getenv("HTTP_HOST"),"",$_ShopInfo->getShopurl()).AdminDir."order_detail2.php"."\">\n";
			echo "<input type=hidden name=\"return_data\" value=\"ordercode=".$ordercode."\">\n";
			echo "<input type=hidden name=\"return_type\" value=\"form\">\n";
			echo "</form>\n";
		}else if($pg_type=="D") {	//�̴Ͻý�
			echo "<form name=inicisform method=post action=\"".$Dir."paygate/D/cancel.php\">\n";
			echo "<input type=hidden name=sitecd value=\"".$pgid_info["ID"]."\">\n";
			echo "<input type=hidden name=ordercode value=\"".$_ord->ordercode."\">\n";
			echo "<input type=hidden name=paymethod value=\"".substr($_ord->paymethod,0,1)."\">\n";
			echo "<input type=hidden name=return_host value=\"".urlencode(getenv("HTTP_HOST"))."\">\n";
			echo "<input type=hidden name=return_script value=\"".str_replace(getenv("HTTP_HOST"),"",$_ShopInfo->getShopurl()).AdminDir."order_detail2.php"."\">\n";
			echo "<input type=hidden name=return_data value=\"ordercode=".$ordercode."\">\n";
			echo "<input type=hidden name=return_type value=\"form\">\n";
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

<tr><td height=10></td></tr>

</table>

<?=$onload?>

</body>
</html>