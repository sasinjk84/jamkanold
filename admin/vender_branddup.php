<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");

$brand_name=$_GET["brand_name"];
$vender=$_GET["vender"];

if(strlen($_ShopInfo->getId())==0){
	echo "<script>alert('�������� ��η� �����Ͻñ� �ٶ��ϴ�.');window.close();</script>";
	exit;
}

$sql = "SELECT brand_name FROM tblvenderstore WHERE 1=1 ";
if(strlen($vender)>0) {
	$sql.= "AND vender!='".$vender."' ";
}
$sql.= "AND brand_name='".$brand_name."' ";
$result = mysql_query($sql,get_db_conn());
?>

<html>
<title>�̴ϼ��� �ߺ�Ȯ��</title>
<head>
<meta http-equiv="CONTENT-TYPE" content="text/html;charset=EUC-KR">
<link rel="stylesheet" href="style.css" type="text/css">
</head>
<body bgcolor=#ffffff>
<form><center>
<?
if ($row=mysql_fetch_object($result)) {
?>
	<font color=#ff0000><b>�̴ϼ����� �ߺ��Ǿ����ϴ�.</b></font><br><p>
<?
} else {
?>
	<font color=#0000ff><b>�Է��Ͻ� �̴ϼ����� ����Ͻ� �� �ֽ��ϴ�.</b></font><br><p>
<?
}
?>
<br>
<input type=button value=" Ȯ �� " onclick="window.close()">

</form>
</body>
</html>
