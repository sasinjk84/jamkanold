<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/shopdata.php");
include_once($Dir."lib/ext/order_func.php");


#### PG ����Ÿ ���� ####
$_ShopInfo->getPgdata();
########################

function getDeligbn($strdeli,$true=true) {
	global $_ShopInfo, $ordercode, $arrdeli;
	if(!is_array($arrdeli)) {
		$sql = "SELECT deli_gbn FROM tblorderproduct WHERE ordercode='".$ordercode."' AND NOT (productcode LIKE 'COU%' OR productcode LIKE '999999%') ";
		$sql.= "GROUP BY deli_gbn ";
		$result=mysql_query($sql,get_db_conn());
		$arrdeli=array();
		while($row=mysql_fetch_object($result)) {
			$arrdeli[]=$row->deli_gbn;
		}
		mysql_free_result($result);
	}

	$res=true;
	for($i=0;$i<count($arrdeli);$i++) {
		if($true==true) {
			if(!preg_match("/^(".$strdeli.")$/", $arrdeli[$i])) {
				$res=false;
				break;
			}
		} else {
			if(preg_match("/^(".$strdeli.")$/", $arrdeli[$i])) {
				$res=false;
				break;
			}
		}
	}
	return $res;
}

$ordercode=$_POST["ordercode"];	//�α����� ȸ���� ��ȸ��
$ordername=$_POST["ordername"]; //��ȸ�� ��ȸ�� �ֹ��ڸ�
$ordercodeid=$_POST["ordercodeid"];	//��ȸ�� ��ȸ�� �ֹ���ȣ 6�ڸ�
$print=$_POST["print"];	//OK�� ��� ����Ʈ

if(strlen($ordercodeid)>0 && strlen($ordercodeid)!=6) {
	echo "<html><head><title></title></head><body onload=\"alert('�ֹ���ȣ 6�ڸ��� ��Ȯ�� �Է��Ͻñ� �ٶ��ϴ�.');window.close();\"></body></html>";exit;
}

$sql = "SELECT gift FROM tblorderinfo WHERE ordercode='{$ordercode}'";
$result = mysql_query($sql,get_db_conn());
$row = mysql_fetch_array($result);

if($row["gift"]=='1'|| $row["gift"]=='2') {
	echo "<script>window.location.href='orderdetailpop2.php?ordercode={$ordercode}';</script>";
	exit;
}

$gift_type=explode("|",$_data->gift_type);

$type=$_POST["type"];
$tempkey=$_POST["tempkey"];
$rescode=$_POST["rescode"];


####### �ֹ���� (����ũ�� ����) #######
if ($type=="cancel" || ($type=="okescrow" && $rescode=="C" && strlen($ordercode)>0)) { //�Ÿź�ȣ �ֹ�������
	$sql = "SELECT price,deli_gbn,reserve,sender_name,paymethod,bank_date FROM tblorderinfo ";
	$sql.= "WHERE ordercode='".$ordercode."' ";
	if($type=="cancel") $sql.= "AND tempkey='".$tempkey."' ";
	$result=mysql_query($sql,get_db_conn());
	if($row=mysql_fetch_object($result)) {
		if (
		(preg_match("/^(Q|P){1}/", $row->paymethod) && !preg_match("/^(C|D|E|H)$/", $row->deli_gbn) && getDeligbn("C|D|E|H",false))
		|| ($_data->ordercancel==0 && ($row->deli_gbn=="S" || $row->deli_gbn=="N") && getDeligbn("N|S",true)) //tblorderproduct�� deli_gbn�� "S|N"�� �ִ��� Ȯ���Ѵ�.
		|| ($_data->ordercancel==2 && $row->deli_gbn=="N" && getDeligbn("N",true)) //tblorderproduct�� deli_gbn�� "N"�� �ִ��� Ȯ���Ѵ�.
		|| ($_data->ordercancel=="1" && $row->paymethod=="B" && strlen($row->bank_date)<12 && $row->deli_gbn=="N" && getDeligbn("N",true))
		) {  // ��۱����� ��� ���� ����� ��������쿡�� �ֹ� ���, ���� �����ϰ�� �ԱݾȵȰǸ�

			//if(preg_match("/^(Q|P){1}/", $row->paymethod))
			$deliok="D";
			//else $deliok="C";
			//printr($_POST);

			if($_POST['bank_name'] != "" ){//ȯ�� ���� ������ ������
				$bankAccountInfo = "<br>ȯ�Ұ������� : ".$_POST['bank_name']." ".$_POST['bank_num']. "(������:". $_POST['bank_owner'].")";
				$banksql = ", pay_data = CONCAT(pay_data,'".$bankAccountInfo."') ";
			}
			
			$cancelsql.= ",cancel_reason='".$_POST['cancel_reason']."',cancel_detail='".$_POST['cancel_detail']."',cancel_date='".date("YmdHis")."',refund_price='".$_POST['refund_price']."',refund_commi='".$_POST['refund_commi']."' ";

			$sql = "UPDATE tblorderinfo SET deli_gbn='".$deliok."'".$banksql.$cancelsql." WHERE ordercode='".$ordercode."' ";
			
			if($type=="cancel") $sql.= "AND tempkey='".$tempkey."' ";
			//echo $sql;exit;
			if(mysql_query($sql,get_db_conn())) {
				$sql = "UPDATE tblorderproduct SET deli_gbn='".$deliok."' ";
				$sql.= $cancelsql;
				$sql.= "WHERE ordercode='".$ordercode."' ";
				$sql.= "AND NOT (productcode LIKE 'COU%' AND productcode LIKE '999999%') ";
				mysql_query($sql,get_db_conn());
/*
				if(empty($ordercodeid) && strlen($_ShopInfo->getMemid())>0 && $row->reserve>0) {
					$sql = "UPDATE tblmember SET reserve=reserve+".abs($row->reserve)." ";
					$sql.= "WHERE id='".$_ShopInfo->getMemid()."' ";
					mysql_query($sql,get_db_conn());

					$sql = "INSERT tblreserve SET ";
					$sql.= "id			= '".$_ShopInfo->getMemid()."', ";
					$sql.= "reserve		= ".$row->reserve.", ";
					$sql.= "reserve_yn	= 'Y', ";
					$sql.= "content		= '�ֹ� ��Ұǿ� ���� ������ ȯ��', ";
					$sql.= "orderdata	= '".$ordercode."=".$row->price."', ";
					$sql.= "date		= '".date("YmdHis")."' ";
					mysql_query($sql,get_db_conn());
				}
*/				
				//�������̺� ����
				$sql2 = "UPDATE rent_schedule SET status='CC' WHERE ordercode='".$ordercode."' ";
				mysql_query($sql2,get_db_conn());

				/////////////// �ֹ���ҽ� �����ڿ��� ������ �߼�
				$maildata=$row->sender_name."������ <font color=blue>".date("Y")."�� ".date("m")."�� ".date("d")."��</font>�� �Ʒ��� ���� �ֹ��� ����ϼ̽��ϴ�.<br><br>";
				$maildata.="<li> ��ҵ� �ֹ��� ��ȣ : $ordercode<br><br>";
				$maildata.="��ҵ� �ֹ��� �����ڸ޴��� �ֹ���ȸ���� Ȯ���Ͻ� �� �ֽ��ϴ�.";

				if (strlen($_data->shopname)>0) $mailshopname = "=?ks_c_5601-1987?B?".base64_encode($_data->shopname)."?=";
				$header=getMailHeader($mailshopname,$_data->info_email);
				if(ismail($_data->info_email)) {
					sendmail($_data->info_email, $_data->shopname." �ֹ���� Ȯ�� �����Դϴ�.", $maildata, $header);
				}

				if(strlen($_data->okcancel_msg)==0)  $_data->okcancel_msg="��ҿ�û�� �Ϸ�Ǿ����ϴ�. �Ǹ�ȸ���� �̹� ��ǰ�� �߼��� ��쿡�� ���ó���� �źεǰų�, ��Ҽ����ᰡ �߰��� �߻��� �� �ֽ��ϴ�.";
				if (preg_match("/^(Q){1}/", $row->paymethod) && strlen($row->bank_date)>=12) $_data->okcancel_msg.=" ���������� �������� ��� �� ȯ��ó���˴ϴ�.";
				if (preg_match("/^(P){1}/", $row->paymethod) && $row->pay_flag=="0000") $_data->okcancel_msg.=" ���������� �������� ��� �� ī�����ó���˴ϴ�.";

				$sqlsms = "SELECT * FROM tblsmsinfo WHERE admin_cancel='Y' ";
				$resultsms= mysql_query($sqlsms,get_db_conn());
				if($rowsms=mysql_fetch_object($resultsms)) {
					if(strlen($ordercode)>0) {
						$sms_id=$rowsms->id;
						$sms_authkey=$rowsms->authkey;

						$totellist=$rowsms->admin_tel;
						if(strlen($rowsms->subadmin1_tel)>8) $totellist.=",".$rowsms->subadmin1_tel;
						if(strlen($rowsms->subadmin2_tel)>8) $totellist.=",".$rowsms->subadmin2_tel;
						if(strlen($rowsms->subadmin3_tel)>8) $totellist.=",".$rowsms->subadmin3_tel;
						$fromtel=$rowsms->return_tel;

						$smsmsg=$row->sender_name."�Բ��� ".substr($ordercode,0,4)."/".substr($ordercode,4,2)."/".substr($ordercode,6,2)."�� �ֹ��Ͻ� �ֹ��� ����ϼ̽��ϴ�.";
						$etcmsg="�ֹ���� �޼���(������)";
						if($rowsms->sleep_time1!=$rowsms->sleep_time2) {
							$date="0";
							$time = date("Hi");
							if($rowsms->sleep_time2<"12" && $time<=substr("0".$rowsms->sleep_time2,-2)."59") $time+=2400;
							if($rowsms->sleep_time2<"12" && $rowsms->sleep_time1>$rowsms->sleep_time2) $rowsms->sleep_time2+=24;

							if($time<substr("0".$rowsms->sleep_time1,-2)."00" || $time>=substr("0".$rowsms->sleep_time2,-2)."59"){
								if($time<substr("0".$rowsms->sleep_time1,-2)."00") $day = date("d");
								else $day=date("d")+1;
								$date = date("Y-m-d H:i:s",mktime($rowsms->sleep_time1,0,0,date("m"),$day,date("Y")));
							}
						}
						$temp=SendSMS($sms_id, $sms_authkey, $totellist, "", $fromtel, $date, $smsmsg, $etcmsg);
					}
				}
				mysql_free_result($resultsms);
				$onload="<script>alert('".$_data->okcancel_msg."');opener.location.href='mypage_orderlist.php';window.close();</script>";
			} else {
				$onload="<script>alert('��û�Ͻ� �۾��� ������ �߻��Ͽ����ϴ�.');</script>";
			}
		} else if (preg_match("/^(Q|P){1}/", $row->paymethod) && preg_match("/^(D)$/", $row->deli_gbn)) {
			$onload="<script>alert('��ҿ�û�� �Ϸ�Ǿ����ϴ�. �Ǹ�ȸ���� �̹� ��ǰ�� �߼��� ��쿡�� ���ó���� �źεǰų�, ��Ҽ����ᰡ �߰��� �߻��� �� �ֽ��ϴ�.');opener.location.href='mypage_orderlist.php';window.close();</script>";
		} else if($_data->ordercancel==0) {
			if(strlen($_data->nocancel_msg)==0) $onload="<script>alert(\"�̹� ��۵� ��ǰ�� �ֽ��ϴ�. ���θ��� �����ֽñ� �ٶ��ϴ�.\");</script>";
			else $onload="<script>alert('$_data->nocancel_msg');</script>";
		} else if($_data->ordercancel==2) {
			if(strlen($_data->nocancel_msg)==0) $onload="<script>alert(\"�߼��غ� �Ϸ�Ǿ� �ù�ȸ�翡 ���޵� ��ǰ�� �ֽ��ϴ�. ���θ��� �����ֽñ� �ٶ��ϴ�.\");</script>";
			else $onload="<script>alert('$_data->nocancel_msg');</script>";
		} else {
			if(strlen($_data->nocancel_msg)==0) $onload="<script>alert(\"��������� ȯ��/��Ҵ� ���θ��� �����ֽñ� �ٶ��ϴ�.\");</script>";
			else $onload="<script>alert('$_data->nocancel_msg');</script>";
		}
	}
}


if ($type=="cancelback") { //��ҿ�ûöȸ��


	$sql = "UPDATE tblorderinfo SET deli_gbn='W' WHERE ordercode='".$ordercode."' ";
	if(mysql_query($sql,get_db_conn())) {
		$sql_ = "UPDATE tblorderproduct SET deli_gbn='W' ";
		$sql_.= "WHERE ordercode='".$ordercode."' ";
		$sql_.= "AND NOT (productcode LIKE 'COU%' AND productcode LIKE '999999%') ";
		mysql_query($sql_,get_db_conn());

		$onload="<script>alert('���öȸ ��û�� �Ϸ�Ǿ����ϴ�.��ұݾ��� �Ա��Ͽ��ų� ��������Ʈ�� ���޵Ǿ��� ���, öȸ��û�� ��ҵǰ� ���ó���� �� �ֽ��ϴ�.');opener.location.href='mypage_orderlist.php';window.close();</script>";
	} else {
		$onload="<script>alert('��û�Ͻ� �۾��� ������ �߻��Ͽ����ϴ�.');</script>";
	}

}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="EUC-KR">
<title>��� �� ȯ�ҿ�û</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="robots" content="index,nofollow">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<!--[if lt lE 9]>
<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js">
</script>
<![endif]-->
<link href="<?=$Dir?>css/style2.css" rel="stylesheet" type="text/css">

<script type="text/javascript" src="<?=$Dir?>lib/lib.js.php"></script>
<script type="text/javascript" src="<?=$Dir?>js/placeholders.min.js"></script>

<SCRIPT LANGUAGE="JavaScript">
<!--
window.moveTo(10,10);
window.resizeTo(810,650);
window.name="orderpop";

function MemoMouseOver(cnt) {
	obj = event.srcElement;
	WinObj=eval("document.all.memo"+cnt);
	obj._tid = setTimeout("MemoView(WinObj)",200);
}
function MemoView(WinObj) {
	WinObj.style.visibility = "visible";
}
function MemoMouseOut(cnt) {
	obj = event.srcElement;
	WinObj=eval("document.all.memo"+cnt);
	WinObj.style.visibility = "hidden";
	clearTimeout(obj._tid);
}

function DeliSearch(url){
	window.open(url,'�����ȸ','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,copyhistory=no,width=550,height=500');
}

function view_product(productcode) {
	opener.location.href="<?=$Dir.FrontDir?>productdetail.php?productcode="+productcode;
}

function qna_product(pridx){
	opener.location.href="<?=$Dir.BoardDir?>board.php?pagetype=write&board=<?=$qnasetup->board?>&exec=write&pridx="+pridx;
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


function order_cancel(tempkey,ordercode,bankdate) {	//�ֹ����
	
	if(bankdate != "") {
		document.form1.tempkey.value=tempkey;
		document.form1.ordercode.value=ordercode;
		document.form1.type.value="cancel";
		document.form1.action="order_cancel_pop.php";
		document.form1.submit();
	}else{
		if (confirm("�ֹ���Ұ� �Ϸ�Ǹ� ���޿����� ������ �� �ֹ��� ��������� ��� ��ҵǸ� ��ҵ� �ֹ����� �ٽ� �ǵ��� �� �����ϴ�")) {
			document.form1.tempkey.value=tempkey;
			document.form1.ordercode.value=ordercode;
			document.form1.type.value="cancel";
			document.form1.submit();
		}
	}

}

//-->
</SCRIPT>
<?
	if (strlen($ordercodeid)>0 && strlen($ordername)>0) {	//��ȸ�� �ֹ���ȸ
		$curdate = date("Ymd",mktime(0,0,0,date("m"),date("d")-90,date("Y")))."00000";
		$sql = "SELECT * FROM tblorderinfo WHERE ordercode > '".$curdate."' AND id LIKE 'X".$ordercodeid."%' ";
		$sql.= "AND sender_name='".$ordername."' ";
		$result=mysql_query($sql,get_db_conn());
		if($row=mysql_fetch_object($result)) {
			$_ord=$row;
			$ordercode=$row->ordercode;
			$gift_price=$row->price-$row->deli_price;
		} else {
			echo "<script>alert('��ȸ�Ͻ� �ֹ������� �����ϴ�.\nȸ���ֹ��� �ƴѰ�� �ֹ��� 90���� ����Ͽ��ٸ� ������ ���ǹٶ��ϴ�.');window.close();</script>";
			exit;
		}
		mysql_free_result($result);
	} else {
		$sql = "SELECT * FROM tblorderinfo WHERE ordercode='".$ordercode."' ";
		$result=mysql_query($sql,get_db_conn());
		if($row=mysql_fetch_object($result)) {
			$_ord=$row;
			$gift_price=$row->price-$row->deli_price;
		} else {
			echo "<script>alert('��ȸ�Ͻ� �ֹ������� �����ϴ�.');window.close();</script>";
			exit;
		}
		mysql_free_result($result);
	}

	$_ord->receiver_addr = ereg_replace("�����ȣ :","(",$_ord->receiver_addr);
	$_ord->receiver_addr = ereg_replace("�ּ� :",") ",$_ord->receiver_addr);

	//�ֹ���ҿ�û�� ���
	if($_ord->deli_gbn=="D" || $_ord->deli_gbn=="W"){
		$disabled = "disabled";
	}
?>
</head>
<body>
<header>
	<h2>��� �� ȯ�ҿ�û</h2>
</header>
<nav>
	<ul>
		<li>��ǰ�� �߼۵Ǳ� �� �ֹ� �ǿ� ���ؼ��� ��ҿ�û�� �����մϴ�.</li>
		<li>��, �Ǹ�ȸ���� �̹� ��ǰ�� �߼��� ��쿡�� ���ó���� �źε� �� ������, ��� �����ᰡ �ִ� ��ǰ�� ��쿡�� ��Һ���� û���� �� �ֽ��ϴ�.</li>
		<li>����Ʈ�� ����Ͽ� ������ �ǿ� ���ؼ��� ��ȿ�Ⱓ �� �켱ȯ�� ó���˴ϴ�.</li>
	</ul>
</nav>
<section>
	<h3><img src="<?=$Dir?>images/icon_dot.gif" border=0 align=absmiddle> ��ǰ����</h3>
	<article class="plist">
		<table>
			<thead>
				<th>�̹���</th>
				<th>��ǰ��</th>
				<th>���û���1</th>
				<th>���û���2</th>
				<th>����</th>
				<th>����</th>
				<th>�޸�</th>
				<th>����</th>
				<th>�����ȸ</th>
			</thead>
			<tbody>
<?
		$delicomlist=getDeliCompany();
		$orderproducts = getOrderProduct($row->ordercode);

		$cnt=0;
		$gift_check="N";
		$taxsaveprname="";
		$etcdata=array();
		$giftdata=array();
		$in_reserve=0;
		//while($row=mysql_fetch_object($result)) {
		$orderproductsCnt = count($orderproducts);
		foreach($orderproducts as $row) {
			

			/**********ȯ�Ҽ����� ��������*************/
			if($_ord->deli_gbn=="D" || $_ord->deli_gbn=="W"){
				$rs_sql = "SELECT datediff(start,".$_ord->cancel_date.") as diffTime FROM rent_schedule WHERE ordercode='".$ordercode."'";
			}else{
				$rs_sql = "SELECT datediff(start,now()) as diffTime FROM rent_schedule WHERE ordercode='".$ordercode."'";
			}
			$rs_res = mysql_query($rs_sql,get_db_conn());
			$rsRow = mysql_fetch_object($rs_res);

			$sql = "SELECT pridx,vender FROM tblproduct ";
			$sql.= "WHERE productcode='".$row->productcode."' ";
			$result=mysql_query($sql,get_db_conn());
			$pRow=mysql_fetch_object($result);

			$code=substr($row->productcode,0,12);

			$sql_ = "select max(day) maxDay,percent from vender_refund where vender='".$pRow->vender."' and pridx='".$pRow->pridx."' and day<='".$rsRow->diffTime."'";	
			$result_=mysql_query($sql_,get_db_conn());
			$rRow=mysql_fetch_object($result_);
			if($rRow->maxDay){
				$rentRow = $rRow;
			}else{
				$sql_ = "select max(day) maxDay,percent from vender_refund where vender='".$pRow->vender."' and pridx=0 and day<='".$rsRow->diffTime."'";	
				$result_=mysql_query($sql_,get_db_conn());
				$rRow=mysql_fetch_object($result_);
				if($rRow->maxDay){
					$rentRow = $rRow;
				}else{
					$sql_ = "select max(day) maxDay,percent from rent_refund where code='".$code."' and day<='".$rsRow->diffTime."'";
					$result_=mysql_query($sql_,get_db_conn());
					$rRow=mysql_fetch_object($result_);
					$rentRow = $rRow;
				}
			}

			
			//ȯ�� �����ݾ��հ�(��ǰ�� ������ �ջ�);
			$refund_subPrice += ($row->price*$row->quantity*$rentRow->percent/100);

			/**********ȯ�Ҽ����� ��������*************/



			if (substr($row->productcode,0,3)=="999" || substr($row->productcode,0,3)=="COU") {

				if ($gift_check=="N" && strpos($row->productcode,"GIFT")!==false) $gift_check="Y";

				$etcdata[]=$row;

				if(strpos($row->productcode,"GIFT")!==false) {
					$giftdata[]=$row;
				}

				continue;
			}
			$gift_tempkey=$row->tempkey;
			$taxsaveprname.=$row->productname.",";

			$optvalue="";
			if(ereg("^(\[OPTG)([0-9]{3})(\])$",$row->opt1_name)) {
				$optioncode=$row->opt1_name;
				$row->opt1_name="";
				$sql = "SELECT opt_name FROM tblorderoption WHERE ordercode='".$ordercode."' AND productcode='".$row->productcode."' ";
				$sql.= "AND opt_idx='".$optioncode."' ";
				$result2=mysql_query($sql,get_db_conn());
				if($row2=mysql_fetch_object($result2)) {
					$optvalue=$row2->opt_name;
				}
				mysql_free_result($result2);
			}

			if($row->status!='RC') $in_reserve+=$row->quantity*$row->reserve;


			$packagestr = "";
			$packageliststr = "";
			$assemblestr = "";
			$rowspanstr = 1;
			if(strlen(str_replace("","",str_replace(":","",str_replace("=","",$row->assemble_info))))>0) {
				$assemble_infoall_exp = explode("=",$row->assemble_info);

				if($row->package_idx>0 && strlen(str_replace("","",str_replace(":","",$assemble_infoall_exp[0])))>0) {
					$rowspanstr++;
					$package_info_exp = explode(":", $assemble_infoall_exp[0]);
					$packagestr.="<img src=\"".$Dir."images/common/icn_package.gif\" border=0 align=absmiddle> ".$package_info_exp[3]."(<font color=#FF3C00>+".number_format($package_info_exp[2])."��</font>)";
					$productname_package_list_exp = explode("",$package_info_exp[1]);
					$packageliststr.="<tr>\n";
					$packageliststr.="	<td colspan=\"6\">\n";
					$packageliststr.= "	<table border=0 width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">\n";
					$packageliststr.= "	<tr>\n";
					if(count($productname_package_list_exp)>0 && strlen($productname_package_list_exp[0])>0) {
						$packageliststr.= "		<td width=\"50\" valign=\"top\" style=\"padding-left:12px;\" nowrap><font color=\"#FF7100\" style=\"line-height:10px;\">��<br>����<b>��</b></font></td>\n";
						$packageliststr.= "		<td width=\"100%\">\n";
						$packageliststr.= "		<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-left:1px #DDDDDD solid;\">\n";

						for($k=0; $k<count($productname_package_list_exp); $k++) {
							$packageliststr.= "		<tr>\n";
							$packageliststr.= "			<td bgcolor=\"#FFFFFF\"".($k>0?"style=\"border-top:1px #DDDDDD solid;\"":"").">\n";
							$packageliststr.= "			<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\n";
							$packageliststr.= "			<col width=\"\"></col>\n";
							$packageliststr.= "			<col width=\"124\"></col>\n";
							$packageliststr.= "			<tr>\n";
							$packageliststr.= "				<td style=\"padding:4px;word-break:break-all;font-size:8pt;\"><font color=\"#000000\">".$productname_package_list_exp[$k]."</font>&nbsp;</td>\n";
							$packageliststr.= "				<td align=\"center\" style=\"padding:4px;border-left:1px #DDDDDD solid;font-size:8pt;\">�� ��ǰ 1���� ����1��</td>\n";
							$packageliststr.= "			</tr>\n";
							$packageliststr.= "			</table>\n";
							$packageliststr.= "			</td>\n";
							$packageliststr.= "		</tr>\n";
						}
						$packageliststr.= "		</table>\n";
						$packageliststr.= "		</td>\n";
					} else {
						$packageliststr.= "		<td width=\"50\" valign=\"top\" style=\"padding-left:12px;\" nowrap><font color=\"#FF7100\" style=\"line-height:10px;\">��<br>����<b>��</b></font></td>\n";
						$packageliststr.= "		<td width=\"100%\">\n";
						$packageliststr.= "		<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-left:1px #DDDDDD solid;\">\n";
						$packageliststr.= "		<tr>\n";
						$packageliststr.= "			<td bgcolor=\"#FFFFFF\" style=\"padding:4px;word-break:break-all;font-size:8pt;\"><font color=\"#000000\">������ǰ�� �������� �ʴ� ��Ű��</font></td>\n";
						$packageliststr.= "		</tr>\n";
						$packageliststr.= "		</table>\n";
						$packageliststr.= "		</td>\n";
					}
					$packageliststr.= "	</tr>\n";
					$packageliststr.= "	</table>\n";
					$packageliststr.="	</td>\n";
					$packageliststr.="</tr>\n";
				}

				if($row->assemble_idx>0 && strlen(str_replace("","",str_replace(":","",$assemble_infoall_exp[1])))>0) {
					$rowspanstr++;
					$assemblestr.="<tr>\n";
					$assemblestr.="	<td colspan=\"6\">\n";
					$assemblestr.="	<table border=0 width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">\n";
					$assemblestr.="	<tr>\n";
					$assemblestr.="		<td width=\"50\" valign=\"top\" style=\"padding-left:5px;\" nowrap><font color=\"#FF7100\" style=\"line-height:10px;\">��<br>����<b>��</b></font></td>\n";
					$assemblestr.="		<td width=\"100%\">\n";
					$assemblestr.="		<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-left:1px #DDDDDD solid;\">\n";

					$assemble_info_exp = explode(":", $assemble_infoall_exp[1]);

					if(count($assemble_info_exp)>2) {
						$assemble_productname_exp = explode("", $assemble_info_exp[1]);
						$assemble_sellprice_exp = explode("", $assemble_info_exp[2]);

						for($k=0; $k<count($assemble_productname_exp); $k++) {
							$assemblestr.="		<tr>\n";
							$assemblestr.="			<td bgcolor=\"#FFFFFF\"".($k>0?"style=\"border-top:1px #DDDDDD solid;\"":"").">\n";
							$assemblestr.="			<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\n";
							$assemblestr.="			<col width=\"\"></col>\n";
							$assemblestr.="			<col width=\"67\"></col>\n";
							$assemblestr.="			<col width=\"124\"></col>\n";
							$assemblestr.="			<tr>\n";
							$assemblestr.="				<td style=\"padding:4px;word-break:break-all;font-size:8pt;\">".$assemble_productname_exp[$k]."&nbsp;</td>\n";
							$assemblestr.="				<td align=\"right\" style=\"padding:4px;border-left:1px #DDDDDD solid;border-right:1px #DDDDDD solid;font-size:8pt\">".number_format((int)$assemble_sellprice_exp[$k])."</td>\n";
							$assemblestr.="				<td align=\"center\" style=\"padding:4px;font-size:8pt\">�� ��ǰ 1���� ����1��</td>\n";
							$assemblestr.="			</tr>\n";
							$assemblestr.="			</table>\n";
							$assemblestr.="			</td>\n";
							$assemblestr.="		</tr>\n";
						}
					}
					@mysql_free_result($alproresult);
					$assemblestr.="		</table>\n";
					$assemblestr.="		</td>\n";
					$assemblestr.="	</tr>\n";
					$assemblestr.="	</table>\n";
					$assemblestr.="	</td>\n";
					$assemblestr.="</tr>\n";
				}
			}

			echo "<tr>\n";
			echo "	<td align=center rowspan=\"".$rowspanstr."\" style=\"padding:2px;\">\n";

			if(strlen($row->minimage)>0 && file_exists($Dir.DataDir."shopimages/product/".$row->minimage)==true){
				echo "		<span onMouseOver='ProductMouseOver($cnt)' onMouseOut=\"ProductMouseOut('primage".$cnt."');\">";
				echo "		<img src=".$Dir.DataDir."shopimages/product/".urlencode($row->minimage)." border=0 width=40 height=40>";
				echo "		</span>\n";
				echo "		<div id=primage".$cnt." style=\"position:absolute; z-index:100; visibility:hidden;\">\n";
				echo "		<table border=0 cellspacing=0 cellpadding=0 width=170>\n";
				echo "		<tr bgcolor=#FFFFFF>\n";
				echo "			<td align=center width=100% height=150 style=\"border:#000000 solid 1px\"><img src=".$Dir.DataDir."shopimages/product/".urlencode($row->minimage)."></td>\n";
				echo "		</tr>\n";
				echo "		</table>\n";
				echo "		</div>\n";
			} else {
				echo '<img src="'.$Dir.'images/no_img.gif" border=0 width=40 height=40>';
			}
			echo "	</td>\n";
			echo "	<td style=\"font-size:8pt;padding:5,5,5,5\">";
			if (substr($row->productcode,0,3)!="999" && substr($row->productcode,0,3)!="COU") {
				echo "<a href=\"javascript:view_product('".$row->productcode."')\">";
			}
			echo ($row->sumprice<0?"<font color=#0000FF>":"").$row->productname.(strlen($row->addcode)>0?" - $row->addcode":"")."</a>";
			if(strlen($optvalue)>0) {
				echo "<br><img src=\"".$Dir."images/common/icn_option.gif\" border=0 align=absmiddle> ".$optvalue."";
			}
			if(strlen($packagestr)>0) {
				echo $packagestr;
			}
			echo "	</td>\n";
			echo "	<td>".$row->opt1_name."</td>\n";
			echo "	<td>".$row->opt2_name."</td>\n";
			echo "	<td>".$row->quantity."</td>\n";
			echo "	<td><FONT COLOR=\"#EE1A02\"><B>".number_format($row->sumprice)."</B></FONT></td>\n";
			if(strlen($row->order_prmsg)>0) {
				echo "	<td align=center style=\"font-size:8pt;color:red\"><a style=\"cursor:hand;\" onMouseOver='MemoMouseOver($cnt)' onMouseOut=\"MemoMouseOut($cnt);\">�޸�</a>";
				echo "	<div id=memo".$cnt." style=\"left:160px;position:absolute; z-index:100; visibility:hidden;\">\n";
				echo "	<table width=400 border=0 cellspacing=0 cellpadding=0 bgcolor=#A47917>\n";
				echo "	<tr>\n";
				echo "		<td style=\"padding:5;line-height:12pt\"><font color=#FFFFFF>".nl2br(strip_tags($row->order_prmsg))."</td>\n";
				echo "	</tr>";
				echo "	</table>\n";
				echo "	</div>\n";
				echo "	</td>\n";
			} else {
				echo "	<td>-</td>\n";
			}
			echo "	<td rowspan=\"".$rowspanstr."\">";

			echo orderProductDeliStatusStr($row,$_ord, $orderproductsCnt);

			echo "	</td>\n";
			echo "	<td rowspan=\"".$rowspanstr."\">";
			$deli_url="";
			$trans_num="";
			$company_name="";
			if($row->deli_gbn=="Y") {
				if($row->deli_com>0 && $delicomlist[$row->deli_com]) {
					$deli_url=$delicomlist[$row->deli_com]->deli_url;
					$trans_num=$delicomlist[$row->deli_com]->trans_num;
					$company_name=$delicomlist[$row->deli_com]->company_name;
					echo $company_name."<br>";
					if(strlen($row->deli_num)>0 && strlen($deli_url)>0) {
						if(strlen($trans_num)>0) {
							$arrtransnum=explode(",",$trans_num);
							$pattern=array("(\[1\])","(\[2\])","(\[3\])","(\[4\])");
							$replace=array(substr($row->deli_num,0,$arrtransnum[0]),substr($row->deli_num,$arrtransnum[0],$arrtransnum[1]),substr($row->deli_num,$arrtransnum[0]+$arrtransnum[1],$arrtransnum[2]),substr($row->deli_num,$arrtransnum[0]+$arrtransnum[1]+$arrtransnum[2],$arrtransnum[3]));
							$deli_url=preg_replace($pattern,$replace,$deli_url);
						} else {
							$deli_url.=$row->deli_num;
						}
						echo "<A HREF=\"javascript:DeliSearch('".$deli_url."')\"><img src=".$Dir."images/common/btn_mypagedeliview.gif border=0></A>";
					}
				} else {
					echo "-";
				}
			} else {
				echo "-";
			}
			echo "	</td>\n";
			echo "</tr>\n";
			echo $assemblestr;
			echo $packageliststr;
			$cnt++;
		}
		@mysql_free_result($result);
?>		
			</tbody>
		</table>

<?

$sql = "select * from tblorderproduct where ordercode='".$_ord->ordercode."' and tempkey='".$_ord->tempkey."' and productcode='99999999995X' order by opt1_name asc";
//echo $sql;
$pcancleitems = array();
if(false !== $cres = mysql_query($sql,get_db_conn())){
	while($citem = mysql_fetch_assoc($cres)){
		array_push($pcancleitems, $citem);
	}
}
if(count($pcancleitems)){
?>
	<h3>�κ� ��� ����</h3>
	<article class="plist">
		<table>
			<tr>
				<td>�׸�</td>
				<td>�ݾ�</td>
				<td>����</td>
			</tr>
<?
		$sumcancle = 0;
		for($i=0;$i<count($pcancleitems);$i++) {
			$citem = $pcancleitems[$i];
			$sumcancle +=abs($citem['price']);
?>
			<tr height=28 style=" background:#ffffff">
				<td style="padding-left:10px;"><?=$citem['productname'].' #'.$citem['opt1_name']?></td>
				<td style="text-align:right; padding-right:10px;"><?=number_format(abs($citem['price']))?>��</td>
				<td style="text-align:center"><? echo substr($citem['date'],0,4).'-'.substr($citem['date'],5,2).'-'.substr($citem['date'],7,2); ?></td>
			</tr>
<? 		
		}//end for 

?>

		</table>
		<div style="text-align:right; padding:10px; font-size:14px;">��� �ݾ� �հ� : <span style="font-weight:bold; color:red"><?=number_format($sumcancle)?></span></div>
	</article>
<? 
}//end if
?>

<form name="form1" method="post">
		<div class="cancelReason">
			<fieldset>
				<legend>��һ���</legend>
				<dl>
					<dt class="title">��������</dt>
					<dd class="inputArea">
						<select name="cancel_reason" <?=$disabled?>>
							<option value="�뿩/���� �ǻ����" <?=$_ord->cancel_reason=="�뿩/���� �ǻ����"? "selected":"";?>>�뿩/���� �ǻ����</option>
							<option value="��ǰ�ļ�" <?=$_ord->cancel_reason=="��ǰ�ļ�"? "selected":"";?>>��ǰ�ļ�</option>
							<option value="���/���� ����" <?=$_ord->cancel_reason=="���/���� ����"? "selected":"";?>>���/���� ����</option>
							<option value="��ǰ�н�" <?=$_ord->cancel_reason=="��ǰ�н�"? "selected":"";?>>��ǰ�н�</option>
							<option value="�̹��/�����" <?=$_ord->cancel_reason=="�̹��/�����"? "selected":"";?>>�̹��/�����</option>
						</select>
					</dd>
					<dt class="title">�󼼻���<br /><span class="smallfont">(500��)</span></dt>
					<dd class="inputArea">
						<textarea name="cancel_detail" <?=$disabled?>><?=$_ord->cancel_detail?></textarea>
					</dd>
				</dl>
			</fieldset>
		</div>
	</article>
	<article class="orderInfo">
		<dl>
			<?if(strlen($ordercode)==21 && substr($ordercode,-1)=="X"){?>
			<dt>�ֹ�Ȯ�ι�ȣ</dt>
			<dd><span><?=substr($_ord->id,1,6)?></span></dd>
			<?}?>
			<dt>�ֹ�����</dt>
			<dd><span><?=substr($ordercode,0,4).".".substr($ordercode,4,2).".".substr($ordercode,6,2)?></span></dd>
			<dt>�޴»��</dt>
			<dd><span><?=$_ord->receiver_name?></span></dd>
			<dt>����ּ�</dt>
			<dd><span><?=$_ord->receiver_addr?></span></dd>
			<dt>�������</dt>
			<dd><span>
			<?
			if (preg_match("/^(B|O|Q){1}/",$_ord->paymethod)) {	//������, �������, ������� ����ũ��
				if($_ord->paymethod=="B") echo "<font color=#FF5D00>�������Ա�</font>\n";
				else if(substr($_ord->paymethod,0,1)=="O") echo "<font color=#FF5D00>�������</font>\n";
				else echo "�Ÿź�ȣ - �������";

				if(!preg_match("/^(C|D)$/",$_ord->deli_gbn) || $_ord->paymethod=="B") echo "�� ".$_ord->pay_data." ��";
				else echo "�� ���� ��� ��";

				if (strlen($_ord->bank_date)>=12) {
					echo " �Ա�Ȯ��";
					echo " <font color=red>".substr($_ord->bank_date,0,4)."/".substr($_ord->bank_date,4,2)."/".substr($_ord->bank_date,6,2)." (".substr($_ord->bank_date,8,2).":".substr($_ord->bank_date,10,2).")</font>";
				} else if(strlen($_ord->bank_date)==9) {
					echo " �Ա�Ȯ��";
					echo "(ȯ��)";
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
?>
			</span></dd>
<?
$order_msg=explode("[MEMO]",$_ord->order_msg);
if(strlen($order_msg[0])>0) {
?>
			<dt>���޸�</dt>
			<dd><span><?=nl2br($order_msg[0])?></span></dd>
<?
}
if(strlen($order_msg[2])>0) {
?>
			<dt>�����޸�</dt>
			<dd><span><?=nl2br($order_msg[2])?></span></dd>
<?
}
?>
		</dl>
	</article>


	<h3><img src="<?=$Dir?>images/icon_dot.gif" border=0 align=absmiddle>ȯ�� �����ݾ�</h3>
	<article class="refundInfo">
		<ul>
			<li class="grey">
				<div class="rInfoTop">
					<h4>�����ݾ� �հ�</h4>
					<h3><?=number_format($row->sumprice)?>��</h3>
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
						<? if($rentRow->maxDay){ ?>
						<dt>(���� <?=$rentRow->maxDay?>���� ȯ�ұ� ������ </dt><dd><?=$rentRow->percent?>%)</dd>
						<? } ?>
						<dt>��ۺ�</dt><dd><?=number_format($_ord->deli_price)?>��</dd>
					</dl>
				</div>
			</li>
			<li>
				<?
				$refundPrice = ($row->sumprice - $refund_subPrice - $_ord->deli_price);
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
	<h3><img src="<?=$Dir?>images/icon_dot.gif" border=0 align=absmiddle>ȯ�Ұ���</h3>
	<div class="refund_desc">�ع���� �̹� ���۵ưų� ȯ������ ������ ���� ��쿡�� ȯ�Ұ��·� ȯ�ҵ˴ϴ�.</div>
	<article class="refundBank">
		<ul>
			<li>
				<select name="bank_name" <?=$disabled?>>
					<option value="">�����</option>
					<option value="�������" <?=$ord->bank_name=="�������"? "selected":""; ?>>�������</option>	
					<option value="��������" <?=$ord->bank_name=="��������"? "selected":""; ?>>��������</option>
					<option value="��ȯ����" <?=$ord->bank_name=="��ȯ����"? "selected":""; ?>>��ȯ����</option>
					<option value="��������" <?=$ord->bank_name=="��������"? "selected":""; ?>>��������</option>
					<option value="�����߾�ȸ" <?=$ord->bank_name=="�����߾�ȸ"? "selected":""; ?>>�����߾�ȸ</option>	
					<option value="��������" <?=$ord->bank_name=="��������"? "selected":""; ?>>��������</option>
					<option value="�츮����" <?=$ord->bank_name=="�츮����"? "selected":""; ?>>�츮����</option>
					<option value="��������" <?=$ord->bank_name=="��������"? "selected":""; ?>>��������</option>
					<option value="��������" <?=$ord->bank_name=="��������"? "selected":""; ?>>��������</option>
					<option value="��������" <?=$ord->bank_name=="��������"? "selected":""; ?>>��������</option>
					<option value="��������" <?=$ord->bank_name=="��������"? "selected":""; ?>>��������</option>
					<option value="�ѹ�����" <?=$ord->bank_name=="�ѹ�����"? "selected":""; ?>>�ѹ�����</option>
					<option value="�뱸����" <?=$ord->bank_name=="�뱸����"? "selected":""; ?>>�뱸����</option>
					<option value="�λ�����" <?=$ord->bank_name=="�λ�����"? "selected":""; ?>>�λ�����</option>
					<option value="��������" <?=$ord->bank_name=="��������"? "selected":""; ?>>��������</option>
					<option value="��������" <?=$ord->bank_name=="��������"? "selected":""; ?>>��������</option>
					<option value="��������" <?=$ord->bank_name=="��������"? "selected":""; ?>>��������</option>
					<option value="�泲����" <?=$ord->bank_name=="�泲����"? "selected":""; ?>>�泲����</option>
					<option value="�������ݰ�" <?=$ord->bank_name=="�������ݰ�"? "selected":""; ?>>�������ݰ�</option>
					<option value="��ü��" <?=$ord->bank_name=="��ü��"? "selected":""; ?>>��ü��</option>
					<option value="�ϳ�����" <?=$ord->bank_name=="�ϳ�����"? "selected":""; ?>>�ϳ�����</option>
				</select>
			</li>
			<li><input type="text" name="bank_owner" id="bank_owner" class="txt150" maxlength="4" value="<?=$_ord->bank_owner?>" placeholder="�����ָ�" <?=$disabled?>/></li><li><input type="text" name="bank_num" id="bank_num" class="txt500" value="<?=$_ord->bank_num?>" placeholder="��ȣ(-)�� ���� ���¹�ȣ�� �Է��ϼ���" <?=$disabled?>/></li>
		</ul>
	</article>
<?
	//}
?>
</section>

<input type=hidden name=tempkey>
<input type=hidden name=ordercode>
<input type=hidden name=type>
<input type=hidden name=ordercodeid value="<?=$ordercodeid?>">
<input type=hidden name=ordername value="<?=$ordername?>">
<input type=hidden name=refund_price value="<?=$refundPrice-$_ord->reserve?>">
<input type=hidden name=refund_commi value="<?=$rentRow->percent?>">
</form>

<footer>
<?
		if($print!="OK") {
			if (
			   ($_data->ordercancel==0 && ($_ord->deli_gbn=="S" || $_ord->deli_gbn=="N") && getDeligbn("S|N",true))/*�ֹ���ۿϷ� ���� ��Ұ� �����ϸ� �߼��غ�� �ֹ��� �Ǵ� ��ó���� �ֹ����� ��� ����*/
			|| ($_data->ordercancel==2 && $_ord->deli_gbn=="N" && getDeligbn("N",true))/*�ֹ�����غ� ������ ��Ұ� �����ϸ� ��ó���� �ֹ����� ��� ����*/
			|| ($_data->ordercancel==1 && preg_match("/^(B){1}/", $_ord->paymethod) && strlen($_ord->bank_date)<12 && $_ord->deli_gbn=="N" && getDeligbn("N",true)) /*�ֹ������Ϸ� ������ ��Ұ� �����ϸ� �������Ա����� �Ա��� ��ó���� �ֹ����� ��� ����*/
			) {
				if(!preg_match("/^(Q){1}/", $_ord->paymethod)) {
					echo "<input type=\"button\" id=\"order_cancel\" style=\"margin-top:10px\" class=\"button\" value=\"��ҿ�û\" onclick=\"javascript:order_cancel('".$_ord->tempkey."', '".$_ord->ordercode."','".$_ord->bank_date."')\" />\n";
				}
			} else if($_data->ordercancel==1 && (($_ord->paymethod=="B" && strlen($_ord->bank_date)>=12) || ( preg_match("/^(C|P){1}/", $_ord->paymethod) && strcmp($_ord->pay_flag,"0000")==0)) && $_ord->deli_gbn=="N" && getDeligbn("N",true)){
				if(strlen($_data->nocancel_msg)==0) $_data->nocancel_msg="�ֹ���Ұ� ���� �ʽ��ϴ�.\\n���θ��� �����ϼ���.";
				echo "<input type=\"button\" id=\"order_cancel\" style=\"margin-top:10px\" class=\"button\" value=\"��ҿ�û\" onclick=\"javascript:alert('".$_data->nocancel_msg."')\" />\n";
			}


			if(((substr($_ord->paymethod,0,1)=="P" && $_ord->pay_admin_proc=="Y") || (substr($_ord->paymethod,0,1)=="Q" && $_ord->pay_flag=="0000")) && !preg_match("/^(Y|C)$/",$_ord->escrow_result) && $_ord->deli_gbn!="C") {
				/*
				����ũ�� ������ ������ �´�.
				*/
				$pgid_info="";
				$pg_type="";
				switch (substr($_ord->paymethod,0,1)) {
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

				// ���ó���� �Ǿ�߸� �Ÿź�ȣ
				if (substr($_ord->paymethod,0,1)=="Q" && !preg_match("/^(D|E|H)$/", $_ord->deli_gbn) && getDeligbn("D|E|H",false)) {
					#<!--- ��� ( ��� & ȯ�� �Ѳ����� ó��) -->
					echo "<input type=\"button\" id=\"escrow_cancel\" style=\"margin-top:10px\" class=\"button\" value=\"��ҿ�û\" onclick=\"javascript:escrow_cancel('".$_ord->tempkey."','".$_ord->ordercode."','".$_ord->bank_date."')\" />\n";
				}
			}
			
		}
?>
	<? if($_ord->deli_gbn=="D" || $_ord->deli_gbn=="W"){ ?>
	<input type="button" style="margin-top:10px" class="button" value="�ݱ�" onclick="javascript:window.close()" />
		<? if($_ord->deli_gbn=="W"){?>
			<input type="button" style="margin-top:10px" class="button disable" value="���öȸ ��û��" disabled />
		<? }else{ ?>
			<input type="button" style="margin-top:10px" class="button" value="��ҿ�û öȸ�ϱ�" onclick="javascript:cancelBack('<?=$_ord->ordercode?>')" />
		<? } ?>
	<? }else{ ?>
	<input type="button" style="margin-top:10px" class="button" value="���ư���" onclick="javascript:history.back()" />
	<? } ?>
</footer>



<SCRIPT LANGUAGE="JavaScript">
<!--
function cancelBack(ordercode) {	//���öȸ��û
	if(confirm("��ҿ�û�� �ֹ��ǿ� ���� ���öȸ�� ��û�Ͻðڽ��ϱ�?")) {
		document.form1.ordercode.value=ordercode;
		document.form1.type.value="cancelback";
		document.form1.submit();
	}
}

//-->
</SCRIPT>


<?
	if($type == "cancel_one" ) {
		$onload="<script>order_cancel('".$_ord->tempkey."', '".$_ord->ordercode."','".$_ord->bank_date."');</script>";
	}
?>

<?=$onload?>
</body>
</html>