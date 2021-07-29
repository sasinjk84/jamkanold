<?
// ajax json 을 통한 백그라운드 실행 처리용 파일
error_reporting(0);
$Dir="../../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/ext/product_func.php");

$result = array();
array_walk($_REQUEST,'_iconvFromUtf8');

//$code = $_REQUEST['code']."000000000";
$code = $_REQUEST['code'];

$trustArr = explode("::",$_REQUEST["trust_vender"]);


if($trustArr[1]=="take"){//받은위탁인 경우 
	$trust_vender = $_REQUEST["vender"];
}else{
	$trust_vender = $trustArr[0];
}


$sql = "SELECT * FROM tbltrustmanage ";
$sql.= "WHERE vender='".$trust_vender."'";
$res = mysql_query($sql,get_db_conn());
$row = mysql_fetch_object($res);
mysql_free_result($res);


$arrPr_commi = explode("//",$row->product_commi);
for($i=0;$i<sizeof($arrPr_commi)-1;$i++){
	$arrCommi[$i] = explode(":",$arrPr_commi[$i]);

	if($arrCommi[$i][0]==$_REQUEST["code"]){
		$result['maincommi'] = $arrCommi[$i][1];break;
	}else{
		$result['maincommi'] = 0;
	}
}


//$result['items'] = $sql;

if(PHP_VERSION > '5.2') array_walk($result,'_encode');
exit(json_encode($result));
?>
