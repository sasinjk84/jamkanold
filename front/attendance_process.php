<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/shopdata.php");
include_once($Dir."lib/ext/func.php");
include_once($Dir."lib/class/attendance.php");
$attendance = new attendance();

if(_empty($_ShopInfo->getMemid())){
	_alert('�⼮ üũ �̺�Ʈ�� ȸ�� ���� ��� �Դϴ�.',$Dir.FrontDir."login.php?chUrl=".getUrl());
	exit;
}
if(!_isInt($_REQUEST['aidx'])){
	_alert('�⼮ üũ �̺�Ʈ�� ���� ���� �ʾҽ��ϴ�.','-1');
	exit;
}


$attendance->_set($_REQUEST['aidx']);
if($attendance->_get('status') != 1){
	_alert($attendance->_get('statusmsg').' �̺�Ʈ �Դϴ�.','-1');
	exit;	
}

if(false === $attendance->_setStamp($_REQUEST['ment'])){
	_alert('Error : '.$attendance->_msg(),'-1');
	exit;	
}else{
	_alert('���� ó�� �Ǿ����ϴ�.','-1');
	exit;	
}
?>