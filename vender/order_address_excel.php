<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/venderlib.php");
INCLUDE ("access.php");

$paystate=$_POST["paystate"];
$deli_gbn=$_POST["deli_gbn"];
$s_check=$_POST["s_check"];
$search=$_POST["search"];

$CurrentTime = time();

$search_start=$_POST["search_start"];
$search_end=$_POST["search_end"];
$search_s=$search_start?str_replace("-","",$search_start."000000"):str_replace("-","",$period[0]."000000");
$search_e=$search_end?str_replace("-","",$search_end."235959"):date("Ymd",$CurrentTime)."235959";

$tempstart = explode("-",$search_start);
$tempend = explode("-",$search_end);
$termday = (mktime(0,0,0,$tempend[1],$tempend[2],$tempend[0])-mktime(0,0,0,$tempstart[1],$tempstart[2],$tempstart[0]))/86400;
if ($termday>31) {
	echo "<script>alert('�ֹ��� �ּ� �ٿ�ε� �Ⱓ�� 1���� �ʰ��� �� �����ϴ�.');</script>";
	exit;
}

Header("Content-Type: application/octet-stream"); 
Header("Content-Disposition: attachment; filename=order_address_".date("Ymd",$CurrentTime).".csv"); 
Header("Pragma: no-cache"); 
Header("Expires: 0"); 

echo "�ֹ���,ID/�ֹ���ȣ,��������,�����ݾ�,�����»��,E-mail,��ȭ��ȣ,�޴»��,��ȭ��ȣ,�����ȭ��ȣ,�����ȣ,�ּ�\n";

$arpm=array("B"=>"������","V"=>"������ü","O"=>"�������","Q"=>"�������(�Ÿź�ȣ)","C"=>"�ſ�ī��","P"=>"�ſ�ī��(�Ÿź�ȣ)","M"=>"�ڵ���");

$qry.= "WHERE a.ordercode=b.ordercode ";
$qry.= "AND b.vender='".$_VenderInfo->getVidx()."' ";
if(substr($search_s,0,8)==substr($search_e,0,8)) {
	$qry.= "AND a.ordercode LIKE '".substr($search_s,0,8)."%' ";
} else {
	$qry.= "AND a.ordercode>='".$search_s."' AND a.ordercode <='".$search_e."' ";
}
$qry.= "AND NOT (b.productcode LIKE 'COU%' OR b.productcode LIKE '999999%') ";
if(strlen($deli_gbn)>0)	$qry.= "AND b.deli_gbn='".$deli_gbn."' ";
if($paystate=="Y") {		//�Ա�
	$qry.= "AND ((MID(a.paymethod,1,1) IN ('B','V','O','Q') AND LENGTH(a.bank_date)=14) OR (MID(a.paymethod,1,1) IN ('C','P','M') AND a.pay_admin_proc!='C' AND a.pay_flag='0000')) ";
} else if($paystate=="B") {	//���Ա�
	$qry.= "AND ((MID(a.paymethod,1,1) IN ('B','V','O','Q') AND (a.bank_date IS NULL OR a.bank_date='')) OR (MID(a.paymethod,1,1) IN ('C','P','M') AND a.pay_flag!='0000' AND a.pay_admin_proc='C')) ";
} else if($paystate=="C") {	//ȯ��
	$qry.= "AND ((MID(a.paymethod,1,1) IN ('B','V','O','Q') AND LENGTH(a.bank_date)=9) OR (MID(a.paymethod,1,1) IN ('C','P','M') AND a.pay_flag='0000' AND a.pay_admin_proc='C')) ";
}
if(strlen($search)>0) {
	if($s_check=="cd") $qry.= "AND a.ordercode='".$search."' ";
	else if($s_check=="pn") $qry.= "AND b.productname LIKE '".$search."%' ";
	else if($s_check=="mn") $qry.= "AND a.sender_name='".$search."' ";
	else if($s_check=="mi") $qry.= "AND a.id='".$search."' ";
	else if($s_check=="cn") $qry.= "AND a.id='".$search."X' ";
}

$sql = "SELECT a.* FROM tblorderinfo a, tblorderproduct b ".$qry." ";
$sql.= "GROUP BY a.ordercode ORDER BY a.ordercode DESC ";
$result = mysql_query($sql,get_db_conn());
while($row=mysql_fetch_object($result)) {
	if(substr($row->ordercode,20)=="X") {	//��ȸ��
		$strid = substr($row->id,1,6);
	} else {	//ȸ��
		$strid = $row->id;
	}
	$date = substr($row->ordercode,0,4)."-".substr($row->ordercode,4,2)."-".substr($row->ordercode,6,2)." ".substr($row->ordercode,8,2).":".substr($row->ordercode,10,2).":".substr($row->ordercode,12,2);

	echo $date.",";
	echo $strid.",";
	//echo $arpm[substr($row->paymethod,0,1)];
	if(preg_match("/^(B){1}/", $row->paymethod)) {	//������
		if (strlen($row->bank_date)==9 && substr($row->bank_date,8,1)=="X") echo "ȯ��";
		else if (strlen($row->bank_date)>0) echo "�ԱݿϷ�";
		else echo "���Ա�";
	} else if(preg_match("/^(V){1}/", $row->paymethod)) {	//������ü
		if (strcmp($row->pay_flag,"0000")!=0) echo "��������";
		else if ($row->pay_flag=="0000" && $row->pay_admin_proc=="C") echo "ȯ��";
		else if ($row->pay_flag=="0000") echo "�����Ϸ�";
	} else if(preg_match("/^(M){1}/", $row->paymethod)) {	//�ڵ���
		if (strcmp($row->pay_flag,"0000")!=0) echo "��������";
		else if ($row->pay_flag=="0000" && $row->pay_admin_proc=="C") echo "��ҿϷ�";
		else if ($row->pay_flag=="0000") echo "�����Ϸ�";
	} else if(preg_match("/^(O|Q){1}/", $row->paymethod)) {	//�������
		if (strcmp($row->pay_flag,"0000")!=0) echo "�ֹ�����";
		else if ($row->pay_flag=="0000" && $row->pay_admin_proc=="C") echo "ȯ��";
		else if ($row->pay_flag=="0000" && strlen($row->bank_date)==0) echo "���Ա�";
		else if ($row->pay_flag=="0000" && strlen($row->bank_date)>0) echo "�ԱݿϷ�";
	} else {
		if (strcmp($row->pay_flag,"0000")!=0) echo "ī�����";
		else if ($row->pay_flag=="0000" && $row->pay_admin_proc=="N") echo "ī�����";
		else if ($row->pay_flag=="0000" && $row->pay_admin_proc=="Y") echo "�����Ϸ�";
		else if ($row->pay_flag=="0000" && $row->pay_admin_proc=="C") echo "��ҿϷ�";
	}
	echo ",";
	echo "\"".number_format($row->price)."\",";
	echo $row->sender_name.",";
	echo $row->sender_email.",";
	echo "\"".$row->sender_tel."\",";
	echo $row->receiver_name.",";
	echo "\"".$row->receiver_tel1."\",";
	echo "\"".$row->receiver_tel2."\",";
	$row->receiver_addr=ereg_replace("�����ȣ : ","",$row->receiver_addr);
	$row->receiver_addr=ereg_replace("\r","",$row->receiver_addr);
	$row->receiver_addr=ereg_replace("\n","",$row->receiver_addr);
	echo substr($row->receiver_addr,0,strpos($row->receiver_addr,"�ּ�")).",";
	echo substr($row->receiver_addr,(strpos($row->receiver_addr,"�ּ�")+7))."\n";
}
mysql_free_result($result);
?>