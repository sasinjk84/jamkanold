<?php
/**
* ERPia �ֹ� ����
* 2012.05.23 code by madmirr@gmail.com
*/
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
require_once('./erpia.class.php'); // �ַ��ȭ �������� ���̺귯 �̵��ô� ��� ���� �ؾ� ��.
$erpia = new erpia();
$erpia->_log('ship Params',print_r($_REQUEST,true));
if($erpia->_auth($_REQUEST['pid'],$_REQUEST['pwd'])){	
	if(empty($_REQUEST['orderNo']) || !preg_match('/^[0-9A-Z]+$/',$_REQUEST['orderNo']) || empty($_REQUEST['Tcode']) || empty($_REQUEST['Tnum'])) throw new InvalidArgumentException('���� ���� �����մϴ�.');
	
	$where = array();
	array_push($where," ordercode='".$_REQUEST['orderNo']."'");
	array_push($where," substr(productcode,1,3) not in ('COU','999')");
	
	if(!empty($_REQUEST['orderSeq']) && intval($_REQUEST['orderSeq']) > 0) array_push($where,"e.Gseq='".$_REQUEST['orderSeq']."'");

	$where = (count($where) >0)?' where '.implode(' and ',$where):'';
	$deli_com = $erpia->_deliComGetmall($_REQUEST['Tcode']);
	/**
	* TODO : ��� ��û �� count ������ ��� ���¸� ��� �غ� �� ��� �Ϸ�� ������ �ʿ� ����
	*/
	$query = "update tblorderproduct left join tblerpiaorder using (vender,ordercode,tempkey,productcode,opt1_name,opt2_name,package_idx,assemble_idx ) set modifydate=NOW(),deli_com='".$deli_com."',deli_num='".$_REQUEST['Tnum']."',deli_gbn= if(deli_gbn = 'N','S',deli_gbn) ".$where;
	mysql_query($query,get_db_conn());
	if(mysql_affected_rows(get_db_conn()) > 0){		
		$query = "update tblorderinfo set deli_gbn = if(deli_gbn='N','Y',deli_gbn) where ordercode='".$_REQUEST['orderNo']."'";
		mysql_query($query,get_db_conn());
		echo 'True';
	}else{
		echo 'False';	
		throw new ErrorException(mysql_error(get_db_conn()));
	}
}
?>