<?php
/**
* ERPia �ŷ�ó����
* 2012.05.16 code by madmirr@gmail.com
*/
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
require_once('./erpia.class.php'); // �ַ��ȭ �������� ���̺귯 �̵��ô� ��� ���� �ؾ� ��.
$erpia = new erpia();
if($erpia->_auth($_REQUEST['pid'],$_REQUEST['pwd'])){	
	$where = array();	
	if(!empty($_REQUEST['gercode']) && preg_match('/^[0-9]+$/',$_REQUEST['gercode'])){ // �ֹ� ������ ��ȸ �Ұ�� ( 1�� ���)
		array_push($where,'companycode="'.$_REQUEST['gercode'].'"');
		$_REQUEST['page'] = NULL;
		//$limit = ' limit 1';
	}else{
		/*
		if(!empty($_REQUEST['pageCnt']) && !empty($_REQUEST['page']) && is_numeric($_REQUEST['pageCnt']) && is_numeric($_REQUEST['page'])){
			$pageCnt = intval($_REQUEST['pageCnt']);
			$page = intval($_REQUEST['page']);		
			$limit = ' limit '.(($page-1)*$pageCnt).','.$pageCnt;
		}else{
			$limit = '';
		}*/
		/*
		* 2012.05.18 �� DB �󿡴� ��� �� ���� �ð� ���� �ϴ� �ʵ� ���� �̿� ���� �߰����̺� ���� ����� ��� �غ��� ��.	
		if(!empty($_REQUEST['sdate'])){ // ���� ���ڷ� ��ȸ��
			$sdate = strtotime($_REQUEST['sdate']);
			$gap = time() - $sdate;
			//if($gap > 0 && $gap < 3600*24*365)
			if($gap > 0) array_push($where,'p.modifydate >="'.date('Y-m-d H',$sdate).'"');		
		}*/
	}
	
	$limit = $erpia->_limitstr($_REQUEST['page'],$_REQUEST['pageCnt']);
	
	array_push($where,' length(companynum) >9');
	$query = "select * from tblproductbisiness";
	
	// ������ ��� �� ��� �ش� ���� ���� ����
	$ordby = '';	
	$where = (count($where) >0)?' where '.implode(' and ',$where):'';
	$query .= $where.$ordby.$limit; 

	$result = mysql_query($query,get_db_conn());
	
	
	$items = array();
	while($row = mysql_fetch_assoc($result)){
		$item = array();		
		$item['code'] = $row['companycode'];
		$item['name'] = $item['saName'] = $row['companyname'];
		$item['type'] = $row['companybiz'];
		$item['item'] = $row['companyitem'];
		$item['sano'] = preg_replace('/[^0-9]/','',$row['companynum']);	
		$item['ceo'] = $row['companyowner'];	
		$item['taxPost'] = $item['post'] = $row['companypost'];
		$item['taxAddr'] = $row['addr'] = $row['companyaddr'];
		$item['charge'] = $row['companycharge'].' '.$row['companychargeposition'];
		$item['email'] = $row['companyemail'];
		$item['bigo'] = $row['companymemo'];
		$item['tel'] = $row['companytel'];
		$item['hp'] = $row['companyhp'];
		$item['fax'] = $row['companyfax'];
		array_push($items,$item);
	}	
	$erpia->_xml(array('info'=>$items));
}
?>