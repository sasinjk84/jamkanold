<?php
/**
* ERPia �ֹ� ������ ���� ����
* 2012.05.29 code by madmirr@gmail.com
*/
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
require_once('./erpia.class.php'); // �ַ��ȭ �������� ���̺귯 �̵��ô� ��� ���� �ؾ� ��.
$erpia = new erpia();
$erpia->_log('orderNoList Params',print_r($_REQUEST,true));
if($erpia->_auth($_REQUEST['pid'],$_REQUEST['pwd'])){
	$where = array();

	// erpia ������ bridge ���̺� ������ ���� - �̼��� ������ �߰�
	$erpia->_syncBridge_Orders();	

	// ��û �Ⱓ�� ���� ���
	if(!empty($_REQUEST['sdate']) && preg_match('/^[0-9]{8}$/',$_REQUEST['sdate'])){ // ������ ���� ���� ���		
		array_push($where,'e.modifydate >="'.substr($_REQUEST['sdate'],0,4).'-'.substr($_REQUEST['sdate'],4,2).'-'.substr($_REQUEST['sdate'],6,2).'"');
	}
	
	if(!empty($_REQUEST['edate']) && preg_match('/^[0-9]{8}$/',$_REQUEST['edate'])){ // ������ ���� ���� ���		
		array_push($where,'e.modifydate <="'.substr($_REQUEST['edate'],0,4).'-'.substr($_REQUEST['edate'],4,2).'-'.substr($_REQUEST['edate'],6,2).'"');			
	}
		
	$query = "select ordercode from tblerpiaorder ";
			
	$ordby = '';	// ������ ��� �� ��� �ش� ���� ���� ����
	$where = (count($where) >0)?' where '.implode(' and ',$where):'';	
	//$limit = $erpia->_limitstr($_REQUEST['page'],$_REQUEST['pageCnt']);
	$limit = '';
	$groupby = ' group by ordercode';
	$query .= $where.$groupby.$ordby.$limit;

	$result = mysql_query($query,get_db_conn());
	$items = array();
	
	while($row = mysql_fetch_assoc($result)){
		array_push($items,$row['ordercode']);
	}	
	$erpia->_xml(array('orderNo'=>$items));		
}
?>