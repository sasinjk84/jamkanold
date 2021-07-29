<?
$Dir="../../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
Header("Pragma: no-cache");

function getMertkey($gbn) {
	if($f=@file(DirPath.AuthkeyDir."pg")) {
		for($i=0;$i<count($f);$i++) {
			$f[$i]=trim(str_replace("\n","",$f[$i]));
			if (substr($f[$i],0,strlen($gbn))==$gbn) {
				return decrypt_authkey(substr($f[$i],strlen($gbn)));
				break;
			}
		}
	}
}

function write_success($noti){
	//������ ���� log����� �˴ϴ�. log path���� �� dbó����ƾ�� �߰��Ͽ� �ֽʽÿ�.	
	//write_log("log/write_success.log", $noti);
	return true;
}

function write_failure($noti){
	//������ ���� log����� �˴ϴ�. log path���� �� dbó����ƾ�� �߰��Ͽ� �ֽʽÿ�.	
	//write_log("log/write_failure.log", $noti);
	return true;
}

function write_hasherr($noti) {
	//������ ���� log����� �˴ϴ�. log path���� �� dbó����ƾ�� �߰��Ͽ� �ֽʽÿ�.	
	//write_log("log/write_hasherr.log", $noti);
	return true;
}

function write_log($file, $noti) {
	$fp = fopen($file, "a+");
	ob_start();
	print_r($noti);
	$msg = ob_get_contents();
	ob_end_clean();
	fwrite($fp, $msg);
	fclose($fp);
}

function get_param($name){
	global $HTTP_POST_VARS, $HTTP_GET_VARS;
	if (!isset($HTTP_POST_VARS[$name]) || $HTTP_POST_VARS[$name] == "") {
		if (!isset($HTTP_GET_VARS[$name]) || $HTTP_GET_VARS[$name] == "") {
			return false;
		} else {
			 return $HTTP_GET_VARS[$name];
		}
	}
	return $HTTP_POST_VARS[$name];
}


// �����޿��� ���� value
$respcode="";       // �����ڵ�: 0000(����) �׿� ����
$respmsg="";        // ����޼���
$hashdata="";       // �ؽ���
$transaction="";    // �������� �ο��� �ŷ���ȣ
$mid="";            // �������̵� 
$oid="";            // �ֹ���ȣ
$amount="";         // �ŷ��ݾ�
$currency="";       // ��ȭ�ڵ�('410':��ȭ, '840':�޷�)
$paytype="";        // ���������ڵ�
$msgtype="";        // �ŷ������� ���� �������� ������ �ڵ�
$paydate="";        // �ŷ��Ͻ�(�����Ͻ�/��ü�Ͻ�)
$buyer="";          // �����ڸ�
$productinfo="";    // ��ǰ����
$buyerssn="";       // �������ֹε�Ϲ�ȣ
$buyerid="";        // ������ID
$buyeraddress="";   // �������ּ�
$buyerphone="";     // ��������ȭ��ȣ
$buyeremail="";     // �������̸����ּ�
$receiver="";       // �����θ�
$receiverphone="";  // ��������ȭ��ȣ
$deliveryinfo="";   // �������
$producttype="";    // ��ǰ����
$productcode="";    // ��ǰ�ڵ�
$financecode="";    // ��������ڵ�(ī������/�����ڵ�)
$financename="";    // ��������̸�(ī���̸�/�����̸�)

$authnumber="";     // ���ι�ȣ(�ſ�ī��)
$cardnumber="";     // ī���ȣ(�ſ�ī��)
$cardexp="";        // ��ȿ�Ⱓ(�ſ�ī��)
$cardperiod="";     // �Һΰ�����(�ſ�ī��)	
$nointerestflag=""; //�������Һο���(�ſ�ī��) - '1'�̸� �������Һ� '0'�̸� �Ϲ��Һ�
$transamount="";    // ȯ������ݾ�(�ſ�ī��)
$exchangerate="";   // ȯ��(�ſ�ī��)

$pid="";            // ������/�޴��������� �ֹε�Ϲ�ȣ(������ü/�޴���) 
$accountowner="";   // ���¼������̸�(������ü) 
$accountnumber="";  // ���¹�ȣ(������ü, �������Ա�) 

$telno="";          // �޴�����ȣ(�޴���)

$payer="";           // �Ա���(�������Ա�)
$cflag="";           // �������Ա� �÷���(�������Ա�) - 'R':�����Ҵ�, 'I':�Ա�, 'C':�Ա����
$tamount="";         // �Ա��Ѿ�(�������Ա�)
$camount="";         // ���Աݾ�(�������Ա�)
$bankdate="";        // �ԱݶǴ�����Ͻ�(�������Ա�)
$seqno="";			 // �Աݼ���(�������Ա�)
$receiptnumber="";	 // ���ݿ����� ���ι�ȣ
$useescrow="";		 // ���� ����ũ�� ���� ���� (Y:����ũ�� ����, N:����ũ�� ������)


$resp = false;      //������� ��������

$respcode = get_param("respcode");
$respmsg = get_param("respmsg");
$hashdata = get_param("hashdata");
$transaction = get_param("transaction");
$mid = get_param("mid");
$oid = get_param("oid");
$amount = get_param("amount");
$currency = get_param("currency");
$paytype = get_param("paytype");
$msgtype = get_param("msgtype");
$paydate = get_param("paydate");
$buyer = get_param("buyer");
$productinfo = get_param("productinfo");
$buyerssn = get_param("buyerssn");
$buyerid = get_param("buyerid");
$buyeraddress = get_param("buyeraddress");
$buyerphone = get_param("buyerphone");
$buyeremail = get_param("buyeremail");
$receiver = get_param("receiver");
$receiverphone = get_param("receiverphone");
$deliveryinfo = get_param("deliveryinfo");
$producttype = get_param("producttype");
$productcode = get_param("productcode");
$financecode = get_param("financecode");
$financename = get_param("financename");
$authnumber = get_param("authnumber");
$cardnumber = get_param("cardnumber");
$cardexp = get_param("cardexp");
$cardperiod = get_param("cardperiod");
$nointerestflag = get_param("nointerestflag");
$transamount = get_param("transamount");
$exchangerate = get_param("exchangerate");
$pid = get_param("pid");
$accountnumber = get_param("accountnumber");
$accountowner = get_param("accountowner");
$telno = get_param("telno");
$payer = get_param("payer");
$cflag = get_param("cflag");
$tamount = get_param("tamount");
$camount = get_param("camount");
$bankdate = get_param("bankdate");
$seqno = get_param("seqno");
$receiptnumber= get_param("receiptnumber");
$useescrow= get_param("useescrow");

unset($pgid_info);
$mertkey = ""; //�����޿��� �߱��� ����Ű�� ������ �ֽñ� �ٶ��ϴ�.
if($paytype=="SC0010" || $paytype=="SC0030" || $paytype=="SC0040" || $paytype=="SC0060") {
	if($paytype=="SC0010") {
		$pgdata=getMertkey("card_id:::");
	} else if($paytype=="SC0030") {
		$pgdata=getMertkey("trans_id:::");
	} else if($paytype=="SC0040") {
		if($useescrow=="Y") {
			$pgdata=getMertkey("escrow_id:::");
		} else {
			$pgdata=getMertkey("virtual_id:::");
		}
	} else if($paytype=="SC0060") {
		$pgdata=getMertkey("mobile_id:::");
	}
	if($pgdata) {
		$pgid_info=GetEscrowType($pgdata);
	}
}
$mertkey=$pgid_info["KEY"];
   
$hashdata2 = md5($transaction.$mid.$oid.$paydate.$mertkey); 

$value = array( "msgtype"  		=> $msgtype,
				"transaction" 	=> $transaction,      
				"mid"    		=> $mid,     
				"oid"     		=> $oid,  
				"amount"     	=> $amount,  
				"currency" 		=> $currency,
				"paytype"  		=> $paytype,
				"paydate"  		=> $paydate,
				"buyer"   		=> $buyer,  
				"productinfo"  	=> $productinfo,  
				"respcode" 		=> $respcode,
				"respmsg"  		=> $respmsg,  
				"buyerssn"     	=> $buyerssn,  
				"buyerid"     	=> $buyerid,  
				"buyeraddress"  => $buyeraddress,  
				"buyerphone"    => $buyerphone,  
				"buyeremail"    => $buyeremail,  
				"receiver"     	=> $receiver,  
				"receiverphone" => $receiverphone,  
				"deliveryinfo"  => $deliveryinfo,  
				"producttype"  	=> $producttype,  
				"productcode"  	=> $productcode,  
				"financecode"  	=> $financecode,  
				"financename"  	=> $financename,  
				"authnumber"   	=> $authnumber,  
				"cardnumber"   	=> $cardnumber,  
				"cardexp"     	=> $cardexp,  
				"cardperiod"   	=> $cardperiod,  
				"nointerestflag"=> $nointerestflag,  
				"transamount"  	=> $transamount,  
				"exchangerate" 	=> $exchangerate,  
				"pid"     		=> $pid,  
				"accountnumber"	=> $accountnumber,  
				"accountowner" 	=> $accountowner,  
				"telno" 		=> $telno,  
				"payer" 		=> $payer,  
				"cflag" 		=> $cflag,  
				"tamount" 		=> $tamount,  
				"camount" 		=> $camount,                 
				"bankdate" 		=> $bankdate,  
				"hashdata"    	=> $hashdata,
				"hashdata2"  	=> $hashdata2,
				"seqno"	  		=> $seqno,
				"receiptnumber"	=> $receiptnumber,
				"useescrow"	    => $useescrow);

if ($hashdata2 == $hashdata) {          //�ؽ��� ������ �����ϸ�
	if($respcode == "0000" || $respcode == "RF00") {           //������ �����̸�
		$resp = write_success($value);
	} else {                            //������ �����̸�
		$resp = write_failure($value);
	}
} else {                                //�ؽ��� ������ �����̸�
	write_hasherr($value);
}


if(strlen(RootPath)>0) {
	$hostscript=getenv("HTTP_HOST").getenv("SCRIPT_NAME");
	$pathnum=@strpos($hostscript,RootPath);
	$shopurl=substr($hostscript,0,$pathnum).RootPath;
} else {
	$shopurl=getenv("HTTP_HOST")."/";
}
$return_host=getenv("HTTP_HOST");
$return_script=str_replace(getenv("HTTP_HOST"),"",$shopurl).FrontDir."payprocess.php";


$date=date("YmdHis");
if((($paytype=="SC0010" || $paytype=="SC0030" || $paytype=="SC0060") || ($paytype=="SC0040" && $cflag=="R")) && ($msgtype=="GMC" || $msgtype=="BMC" || $msgtype=="WMC" || $msgtype=="CAS")) {
	if($respcode=="0000") {	//�������
		$PAY_FLAG="0000";
		$DELI_GBN="N";
		$MSG1=$respmsg;
		$pay_data=$respmsg;
		$ok="Y";

		if ($paytype == "SC0010") {	//�ſ�ī��
			$tblname="tblpcardlog";
			$paymethod="C";
			if($useescrow=="Y") $paymethod="P";
			$PAY_AUTH_NO=$authnumber;
			$MSG1="������� - ���ι�ȣ : ".$PAY_AUTH_NO;
			$pay_data="���ι�ȣ : ".$authnumber."";

			if($nointerestflag=="1") $noinf="Y";
			else $noinf="N";
			
			$quota=$cardperiod;

			$card_name="";
			$sql = "SELECT card_name FROM tblpcardcode WHERE code='".$financecode."' ";
			$result=mysql_query($sql,get_db_conn());
			if($row=mysql_fetch_object($result)) {
				$card_name=$row->card_name;
			}
			mysql_free_result($result);
		} else if ($paytype == "SC0030") {	//������ü
			$tblname="tblptranslog";
			$paymethod="V";
			$PAY_AUTH_NO="";
			$card_name="";
			$noinf="";
			$quota="";
		} else if ($paytype == "SC0040") { //�������
			$ok="M";
			$tblname="tblpvirtuallog";
			$paymethod="O";
			if($useescrow=="Y") $paymethod="Q";
			$PAY_AUTH_NO="";
			$card_name="";
			$noinf="";
			$quota="";
			$pay_data=$financename." ".$accountnumber;
		} else if ($paytype == "SC0060") { //�޴���
			$tblname="tblpmobilelog";
			$paymethod="M";
			$PAY_AUTH_NO="";
			$card_name="";
			$noinf="";
			$quota="";
		}

		$sql = "INSERT INTO tblpordercode VALUES ('".$oid."','".$paymethod."') ";
		mysql_query($sql,get_db_conn());

		if(mysql_errno()==1062) {
			$sql = "UPDATE ".$tblname." SET ";
			$sql.= "trans_code		= '".$transaction."', ";
			$sql.= "pay_data		= '".$pay_data."', ";
			$sql.= "pgtype			= 'B', ";
			$sql.= "ok				= '".$ok."', ";
			$sql.= "okdate			= '".$date."', ";
			$sql.= "price			= '".$amount."', ";
			if ($paytype == "SC0010") {		//�ſ�ī��
				$sql.= "status			= 'N', ";
				$sql.= "paymethod		= '".$paymethod."', ";
				$sql.= "edidate			= '".$date."', ";
				$sql.= "cardname		= '".$card_name."', ";
				$sql.= "noinf			= '".$noinf."', ";
				$sql.= "quota			= '".$quota."', ";
			} else if($paytype == "SC0030") {	//������ü
				$sql.= "bank_name		= '".$financename."', ";
			} else if($paytype=="SC0040") {	//�������
				$sql.= "status			= 'N', ";
				$sql.= "paymethod		= '".$paymethod."', ";
				$sql.= "sender_name		= '".$buyer."', ";
				$sql.= "account			= '".$accountnumber."', ";
			} else if ($paytype == "SC0060") { //�޴���

			}
			$sql.= "ip				= '".getenv("REMOTE_ADDR")."', ";
			$sql.= "goodname		= '".$productinfo."', ";
			$sql.= "msg				= '".$MSG1."' ";
			$sql.= "WHERE ordercode ='".$oid."' ";
			mysql_query($sql,get_db_conn());
		} else {
			$sql = "INSERT ".$tblname." SET ";
			$sql.= "ordercode		= '".$oid."', ";
			$sql.= "trans_code		= '".$transaction."', ";
			$sql.= "pay_data		= '".$pay_data."', ";
			$sql.= "pgtype			= 'B', ";
			$sql.= "ok				= '".$ok."', ";
			$sql.= "okdate			= '".$date."', ";
			$sql.= "price			= '".$amount."', ";
			if ($paytype == "SC0010") {		//�ſ�ī��
				$sql.= "status			= 'N', ";
				$sql.= "paymethod		= '".$paymethod."', ";
				$sql.= "edidate			= '".$date."', ";
				$sql.= "cardname		= '".$card_name."', ";
				$sql.= "noinf			= '".$noinf."', ";
				$sql.= "quota			= '".$quota."', ";
			} else if($paytype == "SC0030") {	//������ü
				$sql.= "bank_name		= '".$financename."', ";
			} else if($paytype=="SC0040") {	//�������
				$sql.= "status			= 'N', ";
				$sql.= "paymethod		= '".$paymethod."', ";
				$sql.= "sender_name		= '".$buyer."', ";
				$sql.= "account			= '".$accountnumber."', ";
			} else if ($paytype == "SC0060") { //�޴���

			}
			$sql.= "ip				= '".getenv("REMOTE_ADDR")."', ";
			$sql.= "goodname		= '".$productinfo."', ";
			$sql.= "msg				= '".$MSG1."' ";
			mysql_query($sql,get_db_conn());
		}
	} else {	//���ν���
		$PAY_FLAG="9999";
		$DELI_GBN="C";
		$MSG1=$respmsg;
		$pay_data=$respmsg;
		if ($paytype == "SC0010") {	//�ſ�ī��
			$tblname="tblpcardlog";
			$paymethod="C";
			if($useescrow=="Y") $paymethod="P";
			$PAY_AUTH_NO="";
			$card_name="";
			$noinf="";
			$quota="";
		} else if ($paytype == "SC0030") {	//������ü
			$tblname="tblptranslog";
			$paymethod="V";
			$PAY_AUTH_NO="";
			$card_name="";
			$noinf="";
			$quota="";
		} else if ($paytype == "SC0040") { //�������
			$ok="M";
			$tblname="tblpvirtuallog";
			$paymethod="O";
			if($useescrow=="Y") $paymethod="Q";
			$PAY_AUTH_NO="";
			$card_name="";
			$noinf="";
			$quota="";
		} else if ($paytype == "SC0060") { //�޴���
			$tblname="tblpmobilelog";
			$paymethod="M";
			$PAY_AUTH_NO="";
			$card_name="";
			$noinf="";
			$quota="";
		}
		$sql = "INSERT INTO tblpordercode VALUES ('".$oid."','".$paymethod."') ";
		mysql_query($sql,get_db_conn());

		$sql = "INSERT ".$tblname." SET ";
		$sql.= "ordercode		= '".$oid."', ";
		$sql.= "trans_code		= '".$transaction."', ";
		$sql.= "pay_data		= 'ERROR', ";
		$sql.= "pgtype			= 'B', ";
		$sql.= "ok				= 'N', ";
		$sql.= "okdate			= '".$date."', ";
		$sql.= "price			= '".$amount."', ";
		if ($paytype == "SC0010") {		//�ſ�ī��
			$sql.= "status			= 'N', ";
			$sql.= "paymethod		= '".$paymethod."', ";
			$sql.= "edidate			= '".$date."', ";
			$sql.= "cardname		= '".$card_name."', ";
			$sql.= "noinf			= '".$noinf."', ";
			$sql.= "quota			= '".$quota."', ";
		} else if($paytype == "SC0030") {	//������ü
			$sql.= "bank_name		= '".$financename."', ";
		} else if($paytype=="SC0040") {	//�������
			$sql.= "status			= 'N', ";
			$sql.= "paymethod		= '".$paymethod."', ";
			$sql.= "sender_name		= '".$buyer."', ";
			$sql.= "account			= '".$accountnumber."', ";
		} else if ($paytype == "SC0060") { //�޴���

		}
		$sql.= "ip				= '".getenv("REMOTE_ADDR")."', ";
		$sql.= "goodname		= '".$productinfo."', ";
		$sql.= "msg				= '".$MSG1."' ";
		mysql_query($sql,get_db_conn());
	}
	$return_data="ordercode=".$oid."&real_price=".$amount."&pay_data=".$pay_data."&pay_flag=".$PAY_FLAG."&pay_auth_no=".$PAY_AUTH_NO."&deli_gbn=".$DELI_GBN."&message=".$MSG1;
	$return_data2=ereg_replace("'","",$return_data);
	$sql = "INSERT INTO tblreturndata VALUES ('".$oid."','".date("YmdHis")."','".$return_data2."') ";
	mysql_query($sql,get_db_conn());

	$temp = SendSocketPost($return_host,$return_script,$return_data);
	if($temp!="ok") {
		//error (���� �߼�)
		if(strlen(AdminMail)>0) {
			@mail(AdminMail,"[PG] ".$oid." �������� ������Ʈ ����","$return_host<br>$return_script<br>$return_data");
		}
		################## ������ ������ �������� �˸��� ##################
		echo "FAIL"; exit;
		###################################################################
	} else {
		mysql_query("DELETE FROM tblreturndata WHERE ordercode='".$oid."'",get_db_conn());
	}
} else if($paytype=="SC0040" && preg_match("/^(I|C)$/", $cflag) && $msgtype=="CBR") {	#������� �Ա�/���
	$sql = "SELECT * FROM tblpordercode WHERE ordercode='".$oid."' ";
	$result=mysql_query($sql,get_db_conn());
	if($row=mysql_fetch_object($result)) {
		$paymethod=$row->paymethod;
	} else {
		if(strlen(AdminMail)>0) {
			@mail(AdminMail,"[PG] ".$oid." �ֹ���ȣ �������� ����","$sql");
		}
	}
	mysql_free_result($result);

	$tblname="";
	if(preg_match("/^(P)$/", $paymethod)) {
		$tblname="tblpcardlog";
	} else if(preg_match("/^(O|Q)$/", $paymethod)) {
		$tblname="tblpvirtuallog";
	}

	if(strlen(RootPath)>0) {
		$hostscript=getenv("HTTP_HOST").getenv("SCRIPT_NAME");
		$pathnum=@strpos($hostscript,RootPath);
		$shopurl=substr($hostscript,0,$pathnum).RootPath;
	} else {
		$shopurl=getenv("HTTP_HOST")."/";
	}
	$return_host=getenv("HTTP_HOST");
	$return_script=str_replace(getenv("HTTP_HOST"),"",$shopurl).FrontDir."payresult/dacom.php";
	$query="ordercode=".$oid."&price=".$amount."&paytype=".$paytype."&cflag=".$cflag;

	$sql = "SELECT ok, status, noti_id FROM ".$tblname." ";
	$sql.= "WHERE ordercode='".$oid."' AND trans_code='".$transaction."' ";
	$result=mysql_query($sql,get_db_conn());
	if($row=mysql_fetch_object($result)) {
		$query.="&ok=";
		if($cflag=="C") $query.="C";
		else $query.="Y";

		if($cflag=="C") {
			if($row->noti_id==$seqno) {
				if($row->ok=="Y" && $row->status=="N") {
					$send_data=SendSocketPost($return_host, $return_script, $query);
					$send_data=substr($send_data,strpos($send_data,"RESULT=")+7);
					if (substr($send_data,0,2)=="OK") {
						$sql = "UPDATE ".$tblname." SET ";
						$sql.= "ok			= 'M', ";
						$sql.= "bank_price	= NULL, ";
						$sql.= "remitter	= '', ";
						$sql.= "bank_code	= '', ";
						$sql.= "bank_date	= '', ";
						$sql.= "receive_date= '' ";
						$sql.= "WHERE ordercode='".$oid."' AND trans_code='".$transaction."' ";
						mysql_query($sql,get_db_conn());
						if(!mysql_error()) {
							$rescode="0000";
						} else {
							if(strlen(AdminMail)>0) {
								@mail(AdminMail,"[PG] ".$oid." ������� �Ա��뺸��� ������Ʈ ����","$sql");
							}
						}
					}
				}
			}
		} else {
			if($row->ok=="M" && $row->status=="N") {
				$send_data=SendSocketPost($return_host, $return_script, $query);
				$send_data=substr($send_data,strpos($send_data,"RESULT=")+7);
				if (substr($send_data,0,2)=="OK") {
					$sql = "UPDATE ".$tblname." SET ";
					$sql.= "ok			= 'Y', ";
					$sql.= "bank_price	= '".$tamount."', ";
					$sql.= "remitter	= '".$payer."', ";
					$sql.= "bank_code	= '".$financecode."', ";
					$sql.= "bank_date	= '".$bankdate."', ";
					$sql.= "receive_date= '".$date."', ";
					$sql.= "noti_id		= '".$seqno."' ";
					$sql.= "WHERE ordercode='".$oid."' AND trans_code='".$transaction."' ";
					mysql_query($sql,get_db_conn());
					if(!mysql_error()) {
						$rescode="0000";
					} else {
						if(strlen(AdminMail)>0) {
							@mail(AdminMail,"[PG] ".$oid." ������� �Ա��뺸 ������Ʈ ����","$sql");
						}
					}
				}
			}
		}
/*
		$send_data=SendSocketPost($return_host, $return_script, $query);
		$send_data=substr($send_data,strpos($send_data,"RESULT=")+7);
		if (substr($send_data,0,2)=="OK") {
			//DB������Ʈ
			$sql = "UPDATE ".$tblname." SET ";
			if($cflag=="C") {
				$sql.= "ok			= 'N', ";
				$sql.= "bank_price	= NULL, ";
				$sql.= "remitter	= '', ";
				$sql.= "bank_code	= '', ";
				$sql.= "bank_date	= '', ";
				$sql.= "receive_date= '' ";
			} else {
				$sql.= "ok			= 'Y', ";
				$sql.= "bank_price	= '".$tamount."', ";
				$sql.= "remitter	= '".$payer."', ";
				$sql.= "bank_code	= '".$financecode."', ";
				$sql.= "bank_date	= '".$bankdate."', ";
				$sql.= "receive_date= '".$date."', ";
				$sql.= "noti_id		= '".$seqno."' ";
			}
			$sql.= "WHERE ordercode='".$oid."' AND trans_code='".$transaction."' ";
			mysql_query($sql,get_db_conn());
			if(!mysql_error()) {
				$rescode="0000";
			} else {
				if(strlen(AdminMail)>0) {
					@mail(AdminMail,"[PG] ".$oid." ������� �Ա��뺸 ������Ʈ ����","$sql");
				}
			}
		}
*/
	} else {
		//error
	}
} else if(($paytype=="SC0010" || $paytype=="SC0030" || $paytype=="SC0060") && $msgtype=="MCQ") {
	//�ŷ���� (SC0010: �ſ�ī��, SC0030: ������ü, SC0060: �޴���
	$sql = "SELECT * FROM tblpordercode WHERE ordercode='".$oid."' ";
	$result=mysql_query($sql,get_db_conn());
	if($row=mysql_fetch_object($result)) {
		$paymethod=$row->paymethod;
	} else {
		if(strlen(AdminMail)>0) {
			@mail(AdminMail,"[PG] ".$oid." �ֹ���ȣ �������� ����","$sql");
		}
	}
	mysql_free_result($result);

	$tblname="";
	if(preg_match("/^(C|P){1}/", $paymethod))	$tblname="tblpcardlog";
	else if($paymethod=="M")					$tblname="tblpmobilelog";
	else if($paymethod=="V")					$tblname="tblptranslog";
	else if($paymethod=="Q")					$tblname="tblpvirtuallog";

	//���������� ���翩�� Ȯ��
	$sql = "SELECT * FROM ".$tblname." WHERE ordercode='".$oid."' ";
	$result=mysql_query($sql,get_db_conn());
	if($row=mysql_fetch_object($result)) {
		$trans_code=$row->trans_code;
		if($row->ok=="C") {	//�̹� ���ó���� ��
			$rescode="0000";
		}
	}
	mysql_free_result($result);
	
	if(($paytype=="SC0030" && $respcode=="RF00") || ($paytype!="SC0030" && $respcode=="0000")) {
		//������Ʈ
		$sql = "UPDATE ".$tblname." SET ";
		$sql.= "ok			= 'C', ";
		$sql.= "canceldate	= '".date("YmdHis")."' ";
		$sql.= "WHERE ordercode='".$oid."' ";
		mysql_query($sql,get_db_conn());
		if (mysql_errno()) {
			if(strlen(AdminMail)>0) {
				@mail(AdminMail,"[PG] ".$tblname." ��� update ����!","$sql - ".mysql_error());
			}
		}
	} else {
		//��ҽ���
	}
} else if($paytype=="SC0040" && $msgtype=="MCQ" && ($respcode=="RF00" || $respcode=="RF25")) {	//����ũ�� ������� �ԱݿϷ� �� ������ �뺸 (RF00:����ó��, RF25:�̹�ȯ�ҿ�û�Ǿ���)
	if(strlen(RootPath)>0) {
		$hostscript=getenv("HTTP_HOST").getenv("SCRIPT_NAME");
		$pathnum=@strpos($hostscript,RootPath);
		$shopurl=substr($hostscript,0,$pathnum).RootPath;
	} else {
		$shopurl=getenv("HTTP_HOST")."/";
	}
	$return_host=getenv("HTTP_HOST");
	$return_script=str_replace(getenv("HTTP_HOST"),"",$shopurl).FrontDir."payresult/dacom.php";
	$query="ordercode=".$oid."&paytype=".$paytype."&txtype=X";

	########################## status�� "N"�� ��쿡�� ����ó�� #########################
	$tblname="tblpvirtuallog";
	$sql="SELECT ok, status FROM ".$tblname." WHERE ordercode='".$oid."' AND trans_code='".$transaction."' ";
	$result=mysql_query($sql,get_db_conn());
	if($row=mysql_fetch_object($result)) {
		if($row->ok=="Y" && $row->status=="N") {
			$send_data=SendSocketPost($return_host, $return_script, $query);
			$send_data=substr($send_data,strpos($send_data,"RESULT=")+7);
			if (substr($send_data,0,2)=="OK") {
				$sql = "UPDATE ".$tblname." SET ";
				$sql.= "status	= 'F' ";	//ȯ�Ҵ�� ����
				$sql.= "WHERE ordercode='".$oid."' AND trans_code='".$transaction."' ";
				mysql_query($sql,get_db_conn());
				if(!mysql_error()) {
					$rescode="0000";
				} else {
					if(strlen(AdminMail)>0) {
						@mail(AdminMail,"[PG] ".$oid." �������뺸 ������Ʈ ����","$sql");
					}
				}
			}
		} else {
			$rescode="0000";
		}
	} else {
		$rescode="0000";
	}
	mysql_free_result($result);
}





//if($resp) {                              //��������� �����̸�
	echo "OK";
//} else {                                 //��������� �����̸�
//	echo "FAIL";
//}

?>