<?
session_start();
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");


$ordercode = $_GET['ordercode'];
$smsnum = $_GET['smsnum'];

$ordercodeList = explode(",",$ordercode);

if( strlen( $ordercode ) > 0 ) {
	$setDate = date("YmdHis");
	$i=0;
	foreach ( $ordercodeList as $var ) {

		$chkSQL = "SELECT * FROM tblorderinfo WHERE bank_date IS NULL AND paymethod = 'B' AND ordercode = '".$var."' LIMIT 1 ;";
		if( mysql_num_rows(mysql_query($chkSQL,get_db_conn())) ) {

			$sql = "UPDATE tblorderinfo SET bank_date = '".$setDate."' WHERE bank_date IS NULL AND paymethod = 'B' AND ordercode = '".$var."' LIMIT 1 ;";
			mysql_query($sql,get_db_conn());

			// ��Ż �Ա�Ȯ��
			rentProdSchdCHG($var, "BR", "BO");
			
			//�Ա�Ȯ���� ��ۻ���: ��ó��(N)->��۴��(S)�� ����
			$sql_ = "UPDATE tblorderinfo SET deli_gbn = 'S' WHERE paymethod = 'B' AND ordercode = '".$var."' LIMIT 1 ;";
			mysql_query($sql_,get_db_conn());
			$sql_2 = "UPDATE tblorderproduct SET deli_gbn = 'S' WHERE ordercode = '".$var."';";
			mysql_query($sql_2,get_db_conn());

			$i++;

			// SMS �߼�
			$smssql="SELECT * FROM tblsmsinfo ";
			$smsresult=mysql_query($smssql,get_db_conn());
			if($smsrow=mysql_fetch_object($smsresult)) {
				$sms_id=$smsrow->id;
				$sms_authkey=$smsrow->authkey;
				$mem_bankok=$smsrow->mem_bankok;
				$mem_bankokvender=$smsrow->mem_bankokvender;
				$msg_mem_bankok=$smsrow->msg_mem_bankok;
				$fromtel=$smsrow->return_tel;
				$mem_present=$smsrow->mem_present;
				$msg_mem_present=$smsrow->msg_mem_present;
				$use_mms =($smsrow->use_mms=='Y')? $smsrow->use_mms:"";
			}
			mysql_free_result($result);
			if($mem_bankok == "Y"){

				// �ֹ�����
				$oSql = "SELECT * FROM tblorderinfo WHERE ordercode = '".$var."' LIMIT 1 ;";
				$oResult = mysql_query($oSql,get_db_conn());
				$oRow = mysql_fetch_object ( $oResult );
					$bankprice=$oRow->price;
					$bankname=$oRow->sender_name;
					$senderTel=$oRow->sender_tel;


				// �ֹ� ȸ������ SMS �߼�
				if( strlen($senderTel) > 0 ) {

					if(strlen($msg_mem_bankok)==0) $msg_mem_bankok="[".strip_tags($_shopdata->shopname)."] [DATE]�� �ֹ��� �Ա�Ȯ�� �Ǿ����ϴ�. ���� �߼��� �帮�ڽ��ϴ�.";
					$patten=array("(\[DATE\])","(\[NAME\])","(\[PRICE\])");
					$replace=array(substr($ordercode,0,4)."/".substr($ordercode,4,2)."/".substr($ordercode,6,2),$bankname,$bankprice);

					$msg_mem_bankok=preg_replace($patten,$replace,$msg_mem_bankok);
					$msg_mem_bankok=addslashes($msg_mem_bankok);


					$date=0;
					$etcmsg="�Ա�Ȯ�θ޼���(ȸ��)";
					$temp=SendSMS($sms_id, $sms_authkey, $senderTel , "", $fromtel, $date, $msg_mem_bankok, $etcmsg);
				}

				// ������ ��ǰ�� ������� �����翡�� �Ա�Ȯ�� �޼��� ����
				if( $mem_bankokvender == "Y" ) {
					// �ֹ� ��ǰ ����
					$senderTel = "";
					$opSql = "SELECT * FROM tblorderproduct WHERE ordercode = '".$var."' AND vender > 0 ;";
					$opResult = mysql_query($opSql,get_db_conn());
					while ( $opRow = mysql_fetch_object ( $opResult ) ) {

						// ���� ������ ����ó
						$vSql = "SELECT p_mobile FROM tblvenderinfo WHERE vender = ".$opRow->vender." ;";
						$vResult = @mysql_query($vSql,get_db_conn());
						if( $vRow = @mysql_fetch_object ( $vResult ) ) {
							if( strlen($vRow->p_mobile) > 10 ) $senderTel .= $vRow->p_mobile.",";
						}
					}

					$date=0;
					$etcmsg="�Ա�Ȯ�θ޼���(����)";
					$temp=SendSMS($sms_id, $sms_authkey, $senderTel , "", $fromtel, $date, "v)".$msg_mem_bankok, $etcmsg);
				}
			}

			$scriptAdd = "";
			$productRoopSQL = "SELECT `productcode` FROM `tblorderproduct` WHERE `ordercode` = '".$var."' ; ";
			$productRoopResult = mysql_query($productRoopSQL,get_db_conn());
			while( $productRoopRow = mysql_fetch_assoc ( $productRoopResult ) ){
				if( strlen($productRoopRow['productcode']) == 18 ) {
					$scriptAdd .= "parent.document.getElementById('chkOrderProductCode_".$var.$productRoopRow['productcode']."').style.display='inline';\n";
				}
			}
			echo "
				<script type=\"text/javascript\">
				<!--
					parent.document.getElementById('orderBankOKobj_".$var."').style.display='none';
					parent.document.getElementById('orderBankOKOKobj_".$var."').style.display='block';
					".$scriptAdd ."
				//-->
				</script>
			";
		} else {
			echo "
				<script type=\"text/javascript\">
				<!--
					parent.document.getElementById('orderBankOKobj_".$var."').style.display='block';
					parent.document.getElementById('orderBankOKOKobj_".$var."').style.display='none';
				//-->
				</script>
			";
		}
	}
	if( $i > 1 ) {
		echo "
			<script type=\"text/javascript\">
			<!--
				alert(\"�����Ͻ� �ֹ��� �� ���ԱݰǸ� �ԱݿϷ�ó���߽��ϴ�.\");
			//-->
			</script>
		";
	}

	if( $i == 0 ) {
		echo "
			<script type=\"text/javascript\">
			<!--
				alert(\"�ϰ�ó�������� �ֹ����� �����ϴ�.\");
			//-->
			</script>
		";
	}
}

?>