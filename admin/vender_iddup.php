<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");

$id=$_GET["id"];

if(strlen($_ShopInfo->getId())==0){
	echo "<script>alert('�������� ��η� �����Ͻñ� �ٶ��ϴ�.');window.close();</script>";
	exit;
}

$sql = "SELECT id FROM tblvenderinfo WHERE id='".$id."' ";
$result = mysql_query($sql,get_db_conn());
?>

<html>
<title>ID�ߺ�Ȯ��</title>
<head>
<meta http-equiv="CONTENT-TYPE" content="text/html;charset=EUC-KR">
<link rel="stylesheet" href="style.css" type="text/css">
</head>
<body bgcolor=#ffffff>
<form><center>
<?
if ($row=mysql_fetch_object($result)) {
?>
	<font color=#ff0000><b>ID�� �ߺ��Ǿ����ϴ�.</b></font><br><p>
<?
} else if(!eregi("^[a-zA-Z0-9]*$", $id)) {
?>
	<font color=#0000ff><b>����,���ڸ� ����Ͻ� �� �ֽ��ϴ�.</b></font><br><p>
<?
} else {
?>
	<font color=#0000ff><b>ID�� ����Ͻ� �� �ֽ��ϴ�.</b></font><br><p>
<?
}
?>
<br>
<input type=button value=" Ȯ �� " onclick="window.close()">

</form>
</body>
</html>
