<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
INCLUDE ("access.php");

####################### ������ ���ٱ��� check ###############
$PageCode = "or-3";
$MenuCode = "order";
if (!$_usersession->isAllowedTask($PageCode)) {
	INCLUDE ("AccessDeny.inc.php");
	exit;
}
#########################################################

$type=$_POST["type"];
$CurrentTime = time();
$period[0] = date("Y-m-d",$CurrentTime);
$period[1] = date("Y-m-d",$CurrentTime-(60*60*24*3));
$period[2] = date("Y-m-d",$CurrentTime-(60*60*24*7));

$search_start=$_POST["search_start"];
$search_end=$_POST["search_end"];

$search_start=$search_start?$search_start:$period[0];
$search_end=$search_end?$search_end:date("Y-m-d",$CurrentTime);
$search_s=$search_start?str_replace("-","",$search_start."000000"):str_replace("-","",$period[0]."000000");
$search_e=$search_end?str_replace("-","",$search_end."235959"):date("Ymd",$CurrentTime)."235959";

$tempstart = explode("-",$search_start);
$tempend = explode("-",$search_end);
$termday = (mktime(0,0,0,$tempend[1],$tempend[2],$tempend[0])-mktime(0,0,0,$tempstart[1],$tempstart[2],$tempstart[0]))/86400;
if ($termday>63) {
	echo "<html></head><body onload=\"alert('�˻��Ⱓ�� 2������ �ʰ��� �� �����ϴ�.');history.go(-1);\"></body></html>";exit;
}

Header("Content-Type: application/octet-stream"); 
Header("Content-Disposition: attachment; filename=taxsave_".date("Ymd",$CurrentTime).".csv"); 
Header("Pragma: no-cache"); 
Header("Expires: 0"); 

$sql = "SELECT * FROM tbltaxsavelist ";
if(substr($search_s,0,8)==substr($search_e,0,8)) {
	$sql.= "WHERE tsdtime LIKE '".substr($search_s,0,8)."%' ";
} else {
	$sql.= "WHERE tsdtime>='".$search_s."' AND tsdtime <='".$search_e."' ";
}
if(strlen($type)>0)	$sql.= "AND type='".$type."' ";
$result=mysql_query($sql,get_db_conn());

unset($arrtax);
unset($arrorder);
unset($ordercode);
$cnt=0;
while($row=mysql_fetch_object($result)) {
	$arrtax[$cnt]=$row;
	$arrtax[$cnt]->number=$number;
	$ordercode.=",'".$row->ordercode."'";
	$cnt++;
}
mysql_free_result($result);

if ($cnt>0) {
	$ordercode=substr($ordercode,1);
	$sql = "SELECT ordercode, sender_name, bank_date, deli_gbn FROM tblorderinfo ";
	$sql.= "WHERE ordercode IN (".$ordercode.") ";
	$result=mysql_query($sql,get_db_conn());
	while($row=mysql_fetch_object($result)) {
		$arrorder[$row->ordercode]=$row;
	}
	mysql_free_result($result);
}

echo "��ȣ,ó������,�ֹ�����,�ֹ���,�ݾ�,ó��,����,��������\n";

$cnt=0;
for($i=0;$i<count($arrtax);$i++) {
	$cnt++;

	$tsdtime=$arrtax[$i]->tsdtime;
	$tsdtime=substr($tsdtime,0,4)."/".substr($tsdtime,4,2)."/".substr($tsdtime,6,2)." (".substr($tsdtime,8,2).":".substr($tsdtime,10,2).")";
	$orderdate=$arrtax[$i]->ordercode;
	$orderdate=substr($orderdate,0,4)."/".substr($orderdate,4,2)."/".substr($orderdate,6,2)." (".substr($orderdate,8,2).":".substr($orderdate,10,2).")";

	echo $cnt.",";
	echo $tsdtime.",";
	echo $orderdate.",";
	echo $arrtax[$i]->name.",";
	echo $arrtax[$i]->amt1."��,";
	if(strlen($arrorder[$arrtax[$i]->ordercode]->deli_gbn)==0) {
		echo "�����߱�";
	} else {
		if(strlen($arrorder[$arrtax[$i]->ordercode]->bank_date)==14) echo "�Ա�";
		else if (strlen($arrorder[$arrtax[$i]->ordercode]->bank_date)==9 && substr($arrorder[$arrtax[$i]->ordercode]->bank_date,8,1)=="X") echo "ȯ��";
		else echo "���Ա�";
		echo "/";
		if($arrorder[$arrtax[$i]->ordercode]->deli_gbn=="Y") echo "���";
		else if($arrorder[$arrtax[$i]->ordercode]->deli_gbn=="S") echo "�߼��غ�";
		else if($arrorder[$arrtax[$i]->ordercode]->deli_gbn=="C") echo "���";
		else if($arrorder[$arrtax[$i]->ordercode]->deli_gbn=="R") echo "�ݼ�";
		else echo "�̹��";
	}
	echo ",";
	if(strlen($arrtax[$i]->error_msg)>0) echo $arrtax[$i]->error_msg;
	echo "\n";
}

?>