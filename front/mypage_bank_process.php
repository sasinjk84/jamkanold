<?php
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");

$id = $_ShopInfo->getMemid();
$name = $_ShopInfo->getMemname();
if($_POST['type']=='add') {
	$bank_name = $_POST['bank_name'];
	$bank_num = $_POST['bank_num'];

	if(!$bank_name || !$bank_num) {
		echo "<html><head><title></title></head><body onload=\"alert('������ ����� �Ѿ���� ���߽��ϴ�.');\"></body></html>";exit;
	}
	$signdate = time();
	$sql = "INSERT INTO tblbankinfo SET id='{$id}', name='{$name}', bank_name='{$bank_name}', bank_num='{$bank_num}', signdate='{$signdate}'";
	if(mysql_query($sql,get_db_conn())){
		echo "<html><head><title></title></head><body onload=\"alert('���°� ��� �Ǿ����ϴ�.');parent.hidePopDiv();\"></body></html>";exit;
	}
}else if($_POST['type']=='del') {
	$bank = $_POST['bank'];
	if(!$bank) {
		echo "<html><head><title></title></head><body onload=\"alert('������ ����� �Ѿ���� ���߽��ϴ�.');\"></body></html>";exit;
	}
	$sql = "DELETE FROM tblbankinfo WHERE id='{$id}' && uid='{$bank}'";
	if(mysql_query($sql,get_db_conn())){
		echo "<html><head><title></title></head><body onload=\"alert('���°� �����Ǿ����ϴ�.');parent.hidePopDiv();\"></body></html>";exit;
	}
}

?>