<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/venderlib.php");
INCLUDE ("access.php");

set_time_limit(40);

$connect_ip = getenv("REMOTE_ADDR");
$curdate = date("YmdHis");

$mode=$_POST["mode"];
$prcodes=$_POST["prcodes"];

if($mode=="excel" && strlen($prcodes)>0) {
	$prcodes=substr($prcodes,0,-1);
	$prcodelist=ereg_replace(',','\',\'',$prcodes);

	Header("Content-Disposition: attachment; filename=product_".$_VenderInfo->getId().""."_".date("Ymd").".csv");
	Header("Content-type: application/x-msexcel");

	$sql = "SELECT CONCAT(codeA,codeB,codeC,codeD) as code, type,code_name FROM tblproductcode ";
	$result = mysql_query($sql,get_db_conn());
	while ($row=mysql_fetch_object($result)) {
		$code_name[$row->code] = $row->code_name;
	}
	mysql_free_result($result);

	$patten = array ("\r","\n",",");
	$replace = array ("","<br>","");

	$sql = "SELECT * FROM tblproduct WHERE productcode IN ('".$prcodelist."') AND vender='".$_VenderInfo->getVidx()."' ";
	$sql.= "ORDER BY productcode";
	$result = mysql_query($sql,get_db_conn());

	echo "1���з�,2���з�,3���з�,4���з�,";
	echo "��ǰ�ڵ�,��ǰ��,�Һ��ڰ�,�ǸŰ�,���Ű�,������,������,������,���,ū�̹���,�����̹���,�����̹���,���û���1,����1�ǰ���,���û���2,����,�����,��ǰ��������,����";

	while ($row=mysql_fetch_object($result)) {
		echo "\n";

		$codeA = substr($row->productcode,0,3);
		$codeB = substr($row->productcode,3,3);
		$codeC = substr($row->productcode,6,3);
		$codeD = substr($row->productcode,9,3);
		$code = substr($row->productcode,0,12);
		if($codeB=="000") $codeB="";
		if($codeC=="000") $codeC="";
		if($codeD=="000") $codeD="";
		echo $code_name[$codeA."000000000"].",";
		if(strlen($code_name[$codeA.$codeB."000000"])==0) echo "2���з�����,";
		else echo $code_name[$codeA.$codeB."000000"].",";
		if(strlen($code_name[$codeA.$codeB.$codeC."000"])==0) echo "3���з�����,";
		else echo $code_name[$codeA.$codeB.$codeC."000"].",";
		if(strlen($code_name[$codeA.$codeB.$codeC.$codeD])==0) echo "4���з�����,";
		else echo $code_name[$codeA.$codeB.$codeC.$codeD].",";

		echo "=\"$row->productcode\",";
		echo '"' . str_replace(",","",$row->productname)."\",";
		echo "$row->consumerprice,";
		echo "$row->sellprice,";
		echo "$row->buyprice,";
		echo "$row->production,";
		echo "$row->madein,";
		echo "$row->reserve,";
		if (strlen($row->quantity)==0) echo "������,";
		else echo "$row->quantity,";
		echo "$row->maximage,";
		echo "$row->minimage,";
		echo "$row->tinyimage,";
		echo str_replace(",","|",$row->option1).",";
		echo str_replace(",","^",$row->option_price).",";
		echo str_replace(",","|",$row->option2).",";
		echo str_replace(",","",$row->addcode).",";
		echo substr($row->date,0,8).",";
		echo $row->display.",";
		$content = str_replace($patten,$replace,$row->content);
		echo "$content";
		flush();
	}
	mysql_free_result($result);
}

if($mode=="excelall") {

	Header("Content-Disposition: attachment; filename=product_".$_VenderInfo->getId().""."_".date("Ymd").".csv");
	Header("Content-type: application/x-msexcel");

	$sql = "SELECT CONCAT(codeA,codeB,codeC,codeD) as code, type,code_name FROM tblproductcode ";
	$result = mysql_query($sql,get_db_conn());
	while ($row=mysql_fetch_object($result)) {
		$code_name[$row->code] = $row->code_name;
	}
	mysql_free_result($result);

	$patten = array ("\r","\n",",");
	$replace = array ("","<br>","");

	$sql = "SELECT * FROM tblproduct WHERE vender='".$_VenderInfo->getVidx()."' ";
	$sql.= "ORDER BY productcode";
	$result = mysql_query($sql,get_db_conn());

	echo "1���з�,2���з�,3���з�,4���з�,";
	echo "��ǰ�ڵ�,��ǰ��,�Һ��ڰ�,�ǸŰ�,���Ű�,������,������,������,���,ū�̹���,�����̹���,�����̹���,���û���1,����1�ǰ���,���û���2,����,�����,��ǰ��������,����";

	while ($row=mysql_fetch_object($result)) {
		echo "\n";

		$codeA = substr($row->productcode,0,3);
		$codeB = substr($row->productcode,3,3);
		$codeC = substr($row->productcode,6,3);
		$codeD = substr($row->productcode,9,3);
		$code = substr($row->productcode,0,12);
		if($codeB=="000") $codeB="";
		if($codeC=="000") $codeC="";
		if($codeD=="000") $codeD="";
		echo $code_name[$codeA."000000000"].",";
		if(strlen($code_name[$codeA.$codeB."000000"])==0) echo "2���з�����,";
		else echo $code_name[$codeA.$codeB."000000"].",";
		if(strlen($code_name[$codeA.$codeB.$codeC."000"])==0) echo "3���з�����,";
		else echo $code_name[$codeA.$codeB.$codeC."000"].",";
		if(strlen($code_name[$codeA.$codeB.$codeC.$codeD])==0) echo "4���з�����,";
		else echo $code_name[$codeA.$codeB.$codeC.$codeD].",";

		echo "=\"$row->productcode\",";
		echo '"' . str_replace(",","",$row->productname)."\",";
		echo "$row->consumerprice,";
		echo "$row->sellprice,";
		echo "$row->buyprice,";
		echo "$row->production,";
		echo "$row->madein,";
		echo "$row->reserve,";
		if (strlen($row->quantity)==0) echo "������,";
		else echo "$row->quantity,";
		echo "$row->maximage,";
		echo "$row->minimage,";
		echo "$row->tinyimage,";
		echo str_replace(",","|",$row->option1).",";
		echo str_replace(",","^",$row->option_price).",";
		echo str_replace(",","|",$row->option2).",";
		echo str_replace(",","",$row->addcode).",";
		echo substr($row->date,0,8).",";
		echo $row->display.",";
		$content = str_replace($patten,$replace,$row->content);
		echo "$content";
		flush();
	}
	mysql_free_result($result);
}
?>