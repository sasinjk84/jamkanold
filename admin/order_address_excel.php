<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
INCLUDE ("access.php");

$paymethod=$_POST["paymethod"];
$paystate=$_POST["paystate"];
$deli_gbn=$_POST["deli_gbn"];


$CurrentTime = time();

$search_start=$_POST["search_start"];
$search_end=$_POST["search_end"];
$search_s=$search_start?str_replace("-","",$search_start."000000"):str_replace("-","",$period[0]."000000");
$search_e=$search_end?str_replace("-","",$search_end."235959"):date("Ymd",$CurrentTime)."235959";

$tempstart = explode("-",$search_start);
$tempend = explode("-",$search_end);
$termday = (mktime(0,0,0,$tempend[1],$tempend[2],$tempend[0])-mktime(0,0,0,$tempstart[1],$tempstart[2],$tempstart[0]))/86400;
if ($termday>366) {
	echo "<script>alert('�ֹ��� �ּ� �ٿ�ε� �Ⱓ�� 1���� �ʰ��� �� �����ϴ�.');location='".$_SERVER[PHP_SELF]."';</script>";
	exit;
}

Header("Content-Type: application/octet-stream"); 
Header("Content-Disposition: attachment; filename=order_address_".date("Ymd",$CurrentTime).".csv"); 
Header("Pragma: no-cache"); 
Header("Expires: 0"); 

echo "�ֹ���,ID/�ֹ���ȣ,ó���ܰ�,��������,�����ݾ�,�����»��,E-mail,��ȭ��ȣ,�޴»��,��ȭ��ȣ,�����ȭ��ȣ,�����ȣ,�ּ�\n";

$arpm=array("B"=>"������","V"=>"������ü","O"=>"�������","Q"=>"�������(�Ÿź�ȣ)","C"=>"�ſ�ī��","P"=>"�ſ�ī��(�Ÿź�ȣ)","M"=>"�ڵ���");

if(substr($search_s,0,8)==substr($search_e,0,8)) {
	$qry.= "WHERE ordercode LIKE '".substr($search_s,0,8)."%' ";
} else {
	$qry.= "WHERE ordercode>='".$search_s."' AND ordercode <='".$search_e."' ";
}
if(strlen($paymethod)>0)	$qry.= "AND paymethod LIKE '".$paymethod."%' ";
if(strlen($deli_gbn)>0)		$qry.= "AND deli_gbn='".$deli_gbn."' ";

if($paystate=="Y") {		//�Ա�
	if(preg_match("/^(B|V|O|Q)$/",$paymethod)) $qry.= "AND LENGTH(bank_date)=14 ";	//������/�������/�ǽð�
	else if(preg_match("/^(C|P|M)$/",$paymethod)) $qry.= "AND pay_admin_proc!='C' AND pay_flag='0000' ";	//�ſ�ī��/�ڵ���
	else $qry.= "AND ((MID(paymethod,1,1) IN ('B','V','O','Q') AND LENGTH(bank_date)=14) OR (MID(paymethod,1,1) IN ('C','P','M') AND pay_admin_proc!='C' AND pay_flag='0000')) ";
} else if($paystate=="B") {	//���Ա�
	if(preg_match("/^(B|V|O|Q)$/",$paymethod)) $qry.= "AND (bank_date IS NULL OR bank_date='') ";
	else if(preg_match("/^(C|P|M)$/",$paymethod)) $qry.= "AND pay_admin_proc='C' AND pay_flag!='0000' ";
	else $qry.= "AND ((MID(paymethod,1,1) IN ('B','V','O','Q') AND (bank_date IS NULL OR bank_date='')) OR (MID(paymethod,1,1) IN ('C','P','M') AND pay_flag!='0000' AND pay_admin_proc='C')) ";
} else if($paystate=="C") {	//ȯ��
	if(preg_match("/^(B|V|O|Q)$/",$paymethod)) $qry.= "AND LENGTH(bank_date)=9 ";
	else if(preg_match("/^(C|P|M)$/",$paymethod)) $qry.= "AND pay_admin_proc='C' AND pay_flag='0000' ";
	else $qry.= "AND ((MID(paymethod,1,1) IN ('B','V','O','Q') AND LENGTH(bank_date)=9) OR (MID(paymethod,1,1) IN ('C','P','M') AND pay_flag='0000' AND pay_admin_proc='C')) ";
}
$sql = "SELECT * FROM tblorderinfo ".$qry." ORDER BY ordercode DESC ";
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
	switch($row->deli_gbn) {
		case 'Y': echo "���";  break;
		case 'N': echo "��ó��";  break;
		case 'C': echo "�ֹ����";  break;
		case 'E': echo "ȯ�Ҵ��";  break;
		case 'R': echo "�ݼ�";  break;
		case 'H': echo "���(���꺸��)";  break;
	}
	echo ",";
	echo $arpm[substr($row->paymethod,0,1)];
	if(preg_match("/^(B){1}/", $row->paymethod)) {	//������
		if (strlen($row->bank_date)==9 && substr($row->bank_date,8,1)=="X") echo "[ȯ��]";
		else if (strlen($row->bank_date)>0) echo "[�ԱݿϷ�]";
		else echo "[���Ա�]";
	} else if(preg_match("/^(V){1}/", $row->paymethod)) {	//������ü
		if (strcmp($row->pay_flag,"0000")!=0) echo "[��������]";
		else if ($row->pay_flag=="0000" && $row->pay_admin_proc=="C") echo "[ȯ��]";
		else if ($row->pay_flag=="0000") echo "[�����Ϸ�]";
	} else if(preg_match("/^(M){1}/", $row->paymethod)) {	//�ڵ���
		if (strcmp($row->pay_flag,"0000")!=0) echo "[��������]";
		else if ($row->pay_flag=="0000" && $row->pay_admin_proc=="C") echo "[��ҿϷ�]";
		else if ($row->pay_flag=="0000") echo "[�����Ϸ�]";
	} else if(preg_match("/^(O|Q){1}/", $row->paymethod)) {	//�������
		if (strcmp($row->pay_flag,"0000")!=0) echo "[�ֹ�����]";
		else if ($row->pay_flag=="0000" && $row->pay_admin_proc=="C") echo "[ȯ��]";
		else if ($row->pay_flag=="0000" && strlen($row->bank_date)==0) echo "[���Ա�]";
		else if ($row->pay_flag=="0000" && strlen($row->bank_date)>0) echo "[�ԱݿϷ�]";
	} else {
		if (strcmp($row->pay_flag,"0000")!=0) echo "[ī�����]";
		else if ($row->pay_flag=="0000" && $row->pay_admin_proc=="N") echo "[ī�����]";
		else if ($row->pay_flag=="0000" && $row->pay_admin_proc=="Y") echo "[�����Ϸ�]";
		else if ($row->pay_flag=="0000" && $row->pay_admin_proc=="C") echo "[��ҿϷ�]";
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