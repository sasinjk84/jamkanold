<?
$Dir="../../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/ext/func.php");
include_once($Dir."lib/shopdata.php");
include_once($Dir."lib/hiworks/bill.class.php");

INCLUDE ("../access.php");



####################### ������ ���ٱ��� check ###############
$PageCode = "or-1";
$MenuCode = "order";
if (!$_usersession->isAllowedTask($PageCode)) {
	INCLUDE ("../AccessDeny.inc.php");
	exit;
}
#########################################################
$bill = new Bill();

if(_empty($bill->errmsg) && $bill->_issueSend($_POST)){
	_alert('���� ��� �Ǿ����ϴ�. - ���̟p������ ���� ���� ó�� �Ͻñ� �ٶ��ϴ�.','-1');
	exit;
}
//_alert($bill->errmsg,'-1');



?>