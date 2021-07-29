<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
INCLUDE ("access.php");

$ordercodes=substr($_POST["ordercodes"],0,-1);
$paymethod=$_POST["paymethod"];
$paystate=$_POST["paystate"];
$deli_gbn=$_POST["deli_gbn"];
$s_check=$_POST["s_check"];	//���/�Ա��Ϻ� �ֹ���ȸ(ó������)


$CurrentTime = time();

$search_start=$_POST["search_start"];
$search_end=$_POST["search_end"];
$search_s=$search_start?str_replace("-","",$search_start."000000"):str_replace("-","",$period[0]."000000");
$search_e=$search_end?str_replace("-","",$search_end."235959"):date("Ymd",$CurrentTime)."235959";

$tempstart = explode("-",$search_start);
$tempend = explode("-",$search_end);
$termday = (@mktime(0,0,0,$tempend[1],$tempend[2],$tempend[0])-@mktime(0,0,0,$tempstart[1],$tempstart[2],$tempstart[0]))/86400;
if ($termday>366) {
	echo "<script>alert('�ֹ��� EXCEL �ٿ�ε� �Ⱓ�� 1���� �ʰ��� �� �����ϴ�.');location='".$_SERVER[PHP_SELF]."';</script>";
	exit;
}

if(($s_check=="bank_date" || $s_check=="deli_date") && strlen($ordercodes)==0) {
	if ($termday>31) {
		echo "<script>alert('���/�Աݺ� �ֹ��� EXCEL �ٿ�ε� �Ⱓ�� 1���� �ʰ��� �� �����ϴ�.');location='".$_SERVER[PHP_SELF]."';</script>";
		exit;
	}
}


Header("Content-Type: application/octet-stream"); 
Header("Content-Disposition: attachment; filename=order_excel_".date("Ymd",$CurrentTime).".csv"); 
Header("Pragma: no-cache"); 
Header("Expires: 0");


$excel_info = substr($_shopdata->excel_info,1,-1);
$excel_ok  = $_shopdata->excel_ok;

$excelval=array(
	array("����"									,&$date),				#0
	array("�ֹ���"									,&$sender_name),		#1
	array("�ֹ��� ��ȭ(XXXXXXXX)"					,&$sender_telnum),		#2
	array("�ֹ��� ��ȭ(XX-XXXX-XXXX)"				,&$sender_tel),			#3
	array("�̸���"									,&$sender_email),		#4
	array("�ֹ�ID/�ֹ���ȣ"							,&$idnum),				#5
	array("�������"								,&$paymethod),			#6
	array("��������"								,&$pay),				#7
	array("�������(����)"							,&$pay2),				#8
	array("�ֹ��ݾ�"								,&$sumprice),			#9
	array("ó������"								,&$deli_gbn),			#10
	array("�޴»��"								,&$receiver_name),		#11
	array("��ȭ��ȣ �����ȭ"						,&$receiver_tel),		#12
	array("��ȭ��ȣ(XXXXXXXX)"						,&$receiver_tel1num),	#13
	array("�����ȭ(XXXXXXXX)"						,&$receiver_tel2num),	#14
	array("��ȭ��ȣ(XX-XXXX-XXXX)"					,&$receiver_tel1),		#15
	array("�����ȭ(XX-XXXX-XXXX)"					,&$receiver_tel2),		#16
	array("�����ȣ(XXXXXX)"						,&$post1),				#17
	array("�����ȣ(XXX-XXX)"						,&$post2),				#18
	array("�ּ�"									,&$addr),				#19
	array("���޻���"								,&$message),			#20
	array("��ǰ��"									,&$product),			#21
	array("�ɼ�(Ư¡����)"							,&$option2),			#22
	array("����"									,&$quantity),			#23
	array("��ǰ��1-����-�ɼ� ^ ��ǰ��2-����-�ɼ�"	,&$productname),		#24
	array("��ǰ����"								,&$price),				#25
	array("��ǰ ������"								,&$reserve),			#26
	array("��۷�"									,&$deli_price),			#27
	array("���������"								,&$usereserve),			#28
	array("�Ա���"									,&$bank_date),			#29
	array("�����"									,&$deli_date),			#30
	array("�ֹ����ø޸�(������)"					,&$adminmemo),			#31
	array("���˸���"								,&$usermemo),			#32
	array("��ǰ��1-����-�ɼ�^��ǰ��2-����-�ɼ�"		,&$productname2),		#33
	array("��ǰ�� �����ȣ"							,&$deli_num),			#34
	array("�ŷ���ȣ"								,&$ordercode),			#35
	array("��ǰ�ڵ�"								,&$productcode),		#36
	array("�������(ī�峻��)"						,&$pay_data),			#37
	array("�ɼ�"									,&$option),				#38
	array("Ư¡"									,&$addcode),			#39
	array("��ǰ��(�±����ž���)"					,&$product1),			#40
	array("���޻���(�±����ž���)"					,&$messnotag),			#41
	array("����(�ú��� ǥ��)"						,&$orderdate),			#42

	array("��ǰ�� ó������"							,&$prdt_deli_gbn),		#43
	array("��ǰ�� �ֹ��޼���"						,&$prdt_message),		#44
	array("��ǰ�� �����"							,&$prdt_deli_date),		#45
	array("�����ڵ�"								,&$prdt_selfcode),		#46
	array("�ŷ�ó����"								,&$prdt_business)		#47
);

$isproductall="N";
if(ereg("24|33",$excel_info)){	//��ǰ��1-����-�ɼ� ^ ��ǰ��2-����-�ɼ� �� ���
	$isproductall="Y";
}
$isproduct ="N";
if(ereg("2([12356]{1})|34|38|39|40|43|44|45|46|47",$excel_info)){
	$isproduct="Y";
}
$arr_excel = explode(",",$excel_info);
$cnt = count($arr_excel);

if(strlen($ordercodes)>0) $ordercodes="'".str_replace(",","','",$ordercodes)."'";

if($isproduct=="Y" || $isproductall=="Y") {
	if(($s_check=="bank_date" || $s_check=="deli_date") && strlen($ordercodes)==0) {
		$tablecode=$s_check;
		$tempordercode="";
		$sql = "SELECT ordercode FROM tblorderinfo WHERE ".$tablecode." >= '".$search_s."' AND ".$tablecode." <= '".$search_e."' ";
		$result = mysql_query($sql,get_db_conn());
		while($row=mysql_fetch_object($result)) {
			$tempordercode.=",'".$row->ordercode."'";
		}
		mysql_free_result($result);
		$tempordercode=substr($tempordercode,1);
	} else {
		$tablecode="ordercode";
	}

	if($termday<=92) {
		$sql = "SELECT * FROM tblorderoption ";
		if(strlen($tempordercode)>0) 
			$sql.= "WHERE ordercode IN (".$tempordercode.") ";
		else if(strlen($ordercodes)>0) 
			$sql.= "WHERE ordercode IN (".$ordercodes.") ";
		else
			$sql.= "WHERE ordercode >= '".$search_s."' AND ordercode <= '".$search_e."' ";
		$result = mysql_query($sql,get_db_conn());
		while($row = mysql_fetch_object($result)) {
			$optionkey=$row->ordercode.$row->productcode.$row->opt_idx;
			$addoption[$optionkey]=$row->opt_name;
		}
		mysql_free_result($result);
	}

	$sql = "SELECT a.ordercode,a.id,a.price as sumprice,a.reserve as usereserve,a.deli_price,a.paymethod, ";
	$sql.= "a.pay_data,a.bank_date,a.pay_flag,a.pay_admin_proc,a.deli_gbn,a.deli_date, ";
	$sql.= "a.sender_name,a.sender_email,a.sender_tel,a.receiver_name,a.receiver_tel1,a.receiver_tel2, ";
	$sql.= "a.receiver_addr,a.order_msg,a.del_gbn, b.deli_gbn as prdt_deli_gbn,b.deli_date as prdt_deli_date, ";
	$sql.= "b.deli_num,b.productcode,b.productname,b.opt1_name,b.opt2_name,b.opt3_name,b.opt4_name, ";
	$sql.= "b.addcode,b.quantity,b.price,b.reserve,b.date,b.order_prmsg,b.selfcode,b.productbisiness ";
	$sql.= "FROM tblorderinfo a LEFT JOIN tblorderproduct b ON ";

	if(strlen($ordercodes)>0)
		$sql.= "b.ordercode=a.ordercode WHERE a.".$tablecode." IN (".$ordercodes.") ";
	else
		$sql.= "b.ordercode=a.ordercode WHERE a.".$tablecode." >= '".$search_s."' AND a.".$tablecode." <= '".$search_e."' ";
	if (strlen($deli_gbn)>0) $sql.= "AND a.deli_gbn = '".$deli_gbn."' ";

	if($paystate=="Y") {		//�Ա�
		if(preg_match("/^(B|O|Q)$/",$paymethod)) $sql.= "AND LENGTH(a.bank_date)=14 ";	//������/�������/�ǽð�
		else if(preg_match("/^(C|P|M|V)$/",$paymethod)) $sql.= "AND a.pay_admin_proc!='C' AND a.pay_flag='0000' ";	//�ſ�ī��/�ڵ���
		else $sql.= "AND ((MID(a.paymethod,1,1) IN ('B','O','Q') AND LENGTH(a.bank_date)=14) OR (MID(a.paymethod,1,1) IN ('C','P','M','V') AND a.pay_admin_proc!='C' AND a.pay_flag='0000')) ";
	} else if($paystate=="B") {	//���Ա�
		if(preg_match("/^(B|O|Q)$/",$paymethod)) $sql.= "AND (a.bank_date IS NULL OR a.bank_date='') ";
		else if(preg_match("/^(C|P|M|V)$/",$paymethod)) $sql.= "AND a.pay_admin_proc='C' AND a.pay_flag!='0000' ";
		else $sql.= "AND ((MID(a.paymethod,1,1) IN ('B','O','Q') AND (a.bank_date IS NULL OR a.bank_date='')) OR (MID(a.paymethod,1,1) IN ('C','P','M','V') AND a.pay_flag!='0000' AND a.pay_admin_proc='C')) ";
	} else if($paystate=="C") {	//ȯ��
		if(preg_match("/^(B|O|Q)$/",$paymethod)) $sql.= "AND LENGTH(a.bank_date)=9 ";
		else if(preg_match("/^(C|P|M|V)$/",$paymethod)) $sql.= "AND a.pay_admin_proc='C' AND a.pay_flag='0000' ";
		else $sql.= "AND ((MID(a.paymethod,1,1) IN ('B','O','Q') AND LENGTH(a.bank_date)=9) OR (MID(a.paymethod,1,1) IN ('C','P','M','V') AND a.pay_flag='0000' AND a.pay_admin_proc='C')) ";
	}

	if (strlen($paymethod)>0) $sql.= "AND a.paymethod LIKE '".$paymethod."%' ";
	$sql.= "AND NOT (b.productcode LIKE '999%' OR b.productcode LIKE 'COU%') ";
	$sql.= "ORDER BY a.ordercode DESC ";
} else {
	if(strlen($ordercodes)>0) $tablecode="ordercode";
	if(strlen($tablecode)==0) $tablecode="ordercode";
	$sql = "SELECT *,price as sumprice,reserve as usereserve FROM tblorderinfo ";

	if(strlen($ordercodes)>0)
		$sql.= "WHERE ".$tablecode." IN (".$ordercodes.") ";
	else 
		$sql.= "WHERE ".$tablecode." >= '".$search_s."' AND ".$tablecode." <= '".$search_e."' ";

	if (strlen($deli_gbn)>0) $sql.= "AND deli_gbn = '".$deli_gbn."' ";

	if($paystate=="Y") {		//�Ա�
		if(preg_match("/^(B|O|Q)$/",$paymethod)) $sql.= "AND LENGTH(bank_date)=14 ";	//������/�������/�ǽð�
		else if(preg_match("/^(C|P|M|V)$/",$paymethod)) $sql.= "AND pay_admin_proc!='C' AND pay_flag='0000' ";	//�ſ�ī��/�ڵ���
		else $sql.= "AND ((MID(paymethod,1,1) IN ('B','O','Q') AND LENGTH(bank_date)=14) OR (MID(paymethod,1,1) IN ('C','P','M','V') AND pay_admin_proc!='C' AND pay_flag='0000')) ";
	} else if($paystate=="B") {	//���Ա�
		if(preg_match("/^(B|O|Q)$/",$paymethod)) $sql.= "AND (bank_date IS NULL OR bank_date='') ";
		else if(preg_match("/^(C|P|M|V)$/",$paymethod)) $sql.= "AND pay_admin_proc='C' AND pay_flag!='0000' ";
		else $sql.= "AND ((MID(paymethod,1,1) IN ('B','O','Q') AND (bank_date IS NULL OR bank_date='')) OR (MID(paymethod,1,1) IN ('C','P','M','V') AND pay_flag!='0000' AND pay_admin_proc='C')) ";
	} else if($paystate=="C") {	//ȯ��
		if(preg_match("/^(B|O|Q)$/",$paymethod)) $sql.= "AND LENGTH(bank_date)=9 ";
		else if(preg_match("/^(C|P|M|V)$/",$paymethod)) $sql.= "AND pay_admin_proc='C' AND pay_flag='0000' ";
		else $sql.= "AND ((MID(paymethod,1,1) IN ('B','O','Q') AND LENGTH(bank_date)=9) OR (MID(paymethod,1,1) IN ('C','P','M','V') AND pay_flag='0000' AND pay_admin_proc='C')) ";
	}

	if (strlen($paymethod)>0) $sql.= "AND paymethod LIKE '".$paymethod."%' ";

	$sql.= "ORDER BY ordercode DESC";
}

$result = mysql_query($sql,get_db_conn());

if($title!="NO") {
	for($i=0;$i<$cnt;$i++) {
		if($i!=0) echo ",";
		echo $excelval[$arr_excel[$i]][0];
	}
	echo "\n";
}

$pattern = array("(\r\n)","(\")","(,)","(;)");
$replacement = array(" ","",".","");

$temp = "";
while ($row=mysql_fetch_object($result)) {
	if ($temp!=$row->ordercode) {
		if($isproductall=="Y" && strlen($temp)!=0) {
			for($i=0;$i<$cnt;$i++) {
				if($i!=0) echo ",";
				echo '"' . doubleQuote($excelval[$arr_excel[$i]][1]) . '"';
			}
			echo "\n";
		}
		$ordercode=$row->ordercode;
		$temp=$row->ordercode;
		$date = substr($row->ordercode, 0, 12);
		$date = substr($date,0,4)."/".substr($date,4,2)."/".substr($date,6,2);   //��¥ ���� ����  
		$orderdate = str_replace("/","-",$date)." (".substr($row->ordercode,8,2).":".substr($row->ordercode,10,2).":".substr($row->ordercode,12,2).")";
		$sender_name=$row->sender_name;
		$pay_data=$row->pay_data;
		$sender_email=$row->sender_email;

		if(substr($row->ordercode,20)=="X") {	//��ȸ��
			$idnum = substr($row->id,1,6);
		} else {	//ȸ��
			$idnum = $row->id;
		}

		if(preg_match("/^(B){1}/", $row->paymethod)) {	//������
			$paymethod="������";
			if (strlen($row->bank_date)==9 && substr($row->bank_date,8,1)=="X") $pay="ȯ��";
			else if (strlen($row->bank_date)>0) $pay="�ԱݿϷ�";
			else $pay="���Ա�";
		} else if(preg_match("/^(V){1}/", $row->paymethod)) {	//������ü
			$paymethod="�ǽð�������ü";
			if (strcmp($row->pay_flag,"0000")!=0) $pay="��������";
			else if ($row->pay_flag=="0000" && $row->pay_admin_proc=="C") $pay="ȯ��";
			else if ($row->pay_flag=="0000") $pay="�����Ϸ�";
		} else if(preg_match("/^(M){1}/", $row->paymethod)) {	//�ڵ���
			$paymethod="�ڵ�������";
			if (strcmp($row->pay_flag,"0000")!=0) $pay="��������";
			else if ($row->pay_flag=="0000" && $row->pay_admin_proc=="C") $pay="��ҿϷ�";
			else if ($row->pay_flag=="0000") $pay="�����Ϸ�";
		} else if(preg_match("/^(O|Q){1}/", $row->paymethod)) {	//�������
			if(preg_match("/^(O){1}/", $row->paymethod)) $paymethod="�������";
			else if(preg_match("/^(Q){1}/", $row->paymethod)) $paymethod="�������(�Ÿź�ȣ)";
			if (strcmp($row->pay_flag,"0000")!=0) $pay="�ֹ�����";
			else if ($row->pay_flag=="0000" && $row->pay_admin_proc=="C") $pay="ȯ��";
			else if ($row->pay_flag=="0000" && strlen($row->bank_date)==0) $pay="���Ա�";
			else if ($row->pay_flag=="0000" && strlen($row->bank_date)>0) $pay="�ԱݿϷ�";
		} else {
			if(preg_match("/^(C){1}/", $row->paymethod)) $paymethod="�ſ�ī��";
			else if(preg_match("/^(P){1}/", $row->paymethod)) $paymethod="�ſ�ī��(�Ÿź�ȣ)";
			if (strcmp($row->pay_flag,"0000")!=0) $pay="ī�����";
			else if ($row->pay_flag=="0000" && $row->pay_admin_proc=="N") $pay="ī�����";
			else if ($row->pay_flag=="0000" && $row->pay_admin_proc=="Y") $pay="�����Ϸ�";
			else if ($row->pay_flag=="0000" && $row->pay_admin_proc=="C") $pay="��ҿϷ�";
		}

		$pay2 = $paymethod."[".$pay."]";
		$sumprice=$row->sumprice;
		$deli_price=$row->deli_price;
		$usereserve=$row->usereserve;
		switch($row->deli_gbn) {
			case 'S': $deli_gbn="�߼��غ�";  break;
			case 'X': $deli_gbn="��ۿ�û";  break;
			case 'Y': $deli_gbn="���";  break;
			case 'D': $deli_gbn="��ҿ�û";  break;
			case 'N': $deli_gbn="��ó��";  break;
			case 'E': $deli_gbn="ȯ�Ҵ��";  break;
			case 'C': $deli_gbn="�ֹ����";  break;
			case 'R': $deli_gbn="�ݼ�";  break;
			case 'H': $deli_gbn="���(���꺸��)";  break;
		}
		$sender_telnum=check_num($row->sender_tel);
		$receiver_tel1num=check_num($row->receiver_tel1);
		$receiver_tel2num=check_num($row->receiver_tel2);
		$receiver_tel = $receiver_tel1num." ".$receiver_tel2num;
		$sender_tel = replace_tel($sender_telnum);
		$receiver_tel1 = replace_tel($receiver_tel1num);
		$receiver_tel2 = replace_tel($receiver_tel2num);
		$sender_telnum="=\"".$sender_telnum."\""; 
		$receiver_tel1num="=\"".$receiver_tel1num."\""; 
		$receiver_tel2num="=\"".$receiver_tel2num."\""; 
		$receiver_name=$row->receiver_name;
		$bank_date="=\"".($row->paymethod=="B"?$row->bank_date:substr($row->ordercode,0,14))."\"";
		$deli_date="=\"".$row->deli_date."\"";
		$row->receiver_addr=str_replace("\r\n","",$row->receiver_addr);
		$row->receiver_addr=str_replace("\n","",$row->receiver_addr);
		$receiver_addr=explode("�ּ� : ",$row->receiver_addr);
		$post1 = substr($receiver_addr[0],11,3).substr($receiver_addr[0],15,3);
		$post2 = substr($receiver_addr[0],11,7);
		$addr = $receiver_addr[1]; 
		$mess=explode("[MEMO]",$row->order_msg);
		$message=preg_replace($pattern,$replacement,strip_tags($mess[0]));
		$messnotag=preg_replace($pattern,$replacement,$mess[0]);
		$adminmemo=preg_replace($pattern,$replacement,$mess[1]);
		$usermemo=preg_replace($pattern,$replacement,$mess[2]);
		$quantity=$row->quantity;
		$product1=str_replace(",","",$row->productname);
		$product=strip_tags($product1);
		$productcode="=\"".$row->productcode."\"";
		$price=$row->price;
		$reserve=$row->reserve;
		$option=$option2=$addcode="";
		if (strlen($row->addcode)>0) $addcode=$row->addcode;
		if (strlen($row->opt1_name)>0) {
			if(substr($row->opt1_name,0,5)=="[OPTG") {
				$key=$row->ordercode.$row->productcode.$row->opt1_name;
				$option.=$addoption[$key];
			} else {
				$option.=(strlen($option)==0?"":"-").$row->opt1_name;
			}
		}
		if (strlen($row->opt2_name)>0) $option.=(strlen($option)==0?"":"-").$row->opt2_name;
		if (strlen($row->opt3_name)>0) $option.=(strlen($option)==0?"":"-").$row->opt3_name;
		if (strlen($row->opt4_name)>0) $option.=(strlen($option)==0?"":"-").$row->opt4_name;
		$option2=$addcode.$option;
		$productname=$product."-".$quantity.(strlen($option2)==0?"":"-".$option2);
		$productname2=$productname;
		$deli_num=$row->deli_num;
		$prdt_message=preg_replace($pattern,$replacement,strip_tags($row->order_prmsg));
		$prdt_deli_gbn=$row->prdt_deli_gbn;
		$prdt_deli_date=$row->prdt_deli_date;
		$prdt_selfcode=$row->selfcode;
		$prdt_business=$row->productbisiness;
	} else if($isproductall=="Y") {
		$quantity=$row->quantity;
		$productcode="=\"".$row->productcode."\"";
		$product1=str_replace(",","",$row->productname);
		$product=strip_tags($product1);
		$price=$row->price;
		$reserve=$row->reserve;
		$option=$option2=$addcode="";
		if(strlen($row->addcode)>0) $addcode=$row->addcode;
		if(strlen($row->opt1_name)>0) {
			if(substr($row->opt1_name,0,5)=="[OPTG") {
				$key=$row->ordercode.$row->productcode.$row->opt1_name;
				$option.=$addoption[$key];
			} else {
				$option.=(strlen($option)==0?"":"-").$row->opt1_name;
			}
		}
		if (strlen($row->opt2_name)>0) $option.=(strlen($option)==0?"":"-").$row->opt2_name;
		if (strlen($row->opt3_name)>0) $option.=(strlen($option)==0?"":"-").$row->opt3_name;
		if (strlen($row->opt4_name)>0) $option.=(strlen($option)==0?"":"-").$row->opt4_name;
		$option2=$addcode.$option;
		$productname.=" ^ ".$product."-".$quantity.(strlen($option2)==0?"":"-".$option2);
		$productname2.="^".$product."-".$quantity.(strlen($option2)==0?"":"-".$option2);
		$prdt_message=preg_replace($pattern,$replacement,strip_tags($row->order_prmsg));
		$prdt_deli_gbn=$row->prdt_deli_gbn;
		$prdt_deli_date=$row->prdt_deli_date;
		$prdt_selfcode=$row->selfcode;
		$prdt_business=$row->productbisiness;
	} else { //���� �ֹ��ϰ��
		if($excel_ok=="N") {
			$date=$sender_name=$pay_data=$sender_telnum=$sender_tel=$sender_email=$idnum=$paymethod="";
			$pay=$pay2=$sumprice=$deli_gbn=$receiver_name=$receiver_tel=$receiver_tel1num="";
			$receiver_tel2num=$receiver_tel1=$receiver_tel2=$post1=$post2=$addr=$message=$messnotag="";
			$deli_price=$usereserve=$deli_date=$bank_date=$adminmemo=$usermemo=$ordercode="";
		}
		$quantity=$row->quantity;
		$productcode="=\"".$row->productcode."\"";
		$product1=str_replace(",","",$row->productname);
		$product=strip_tags($product1);
		$price=$row->price;
		$reserve=$row->reserve;
		$option=$option2=$addcode="";
		if(strlen($row->addcode)>0) $addcode=$row->addcode;
		if(strlen($row->opt1_name)>0) {
			if(substr($row->opt1_name,0,5)=="[OPTG") {
				$key=$row->ordercode.$row->productcode.$row->opt1_name;
				$option.=$addoption[$key];
			} else {
				$option.=(strlen($option)==0?"":"-").$row->opt1_name;
			}
		}
		if (strlen($row->opt2_name)>0) $option.=(strlen($option)==0?"":"-").$row->opt2_name;
		if (strlen($row->opt3_name)>0) $option.=(strlen($option)==0?"":"-").$row->opt3_name;
		if (strlen($row->opt4_name)>0) $option.=(strlen($option)==0?"":"-").$row->opt4_name;
		$option2=$addcode.$option;
		$productname=$product."-".$quantity.(strlen($option2)==0?"":"-".$option2);
		$productname2=$productname;
		$deli_num=$row->deli_num;
		$prdt_message=preg_replace($pattern,$replacement,strip_tags($row->order_prmsg));
		$prdt_deli_gbn=$row->prdt_deli_gbn;
		$prdt_deli_date=$row->prdt_deli_date;
		$prdt_selfcode=$row->selfcode;
		$prdt_business=$row->productbisiness;
	}
	if($isproductall=="N") {
		for($i=0;$i<$cnt;$i++) {
			if($i!=0) echo ",";
			echo '"' . doubleQuote($excelval[$arr_excel[$i]][1]) . '"';
		}
		echo "\n";
	}
}
mysql_free_result($result);

if($isproductall=="Y"){
	for($i=0;$i<$cnt;$i++){
		if($i!=0) echo ",";
		echo '"' . doubleQuote($excelval[$arr_excel[$i]][1]) . '"';
	}
	echo "\n";
}

function &doubleQuote($str) {
	return str_replace('"', '""', $str);
}

?>