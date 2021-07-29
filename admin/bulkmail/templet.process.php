<?
// ajax json �� ���� ��׶��� ���� ó���� ����
$Dir="../../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/class/bulkmail.php");

$bulkmail = new bulkmail();

include ("../access.php");

####################### ������ ���ٱ��� check ###############
$PageCode = "ma-4";
$MenuCode = "market";
//DemoShopCheck("������������� �׽�Ʈ�� �Ұ��� �մϴ�.", $_SERVER['PHP_SELF']);
$result = array();
if(_DEMOSHOP=="OK" && getenv("REMOTE_ADDR")!=_ALLOWIP){
	$result['err'] = '������������� �׽�Ʈ�� �Ұ��� �մϴ�.';
}else if(!$_usersession->isAllowedTask($PageCode)) {
	$result['err'] = '���� ������ �����ϴ�.';
}else{
	array_walk($_REQUEST,'_iconvFromUtf8');		
	switch($_REQUEST['act']){
		case 'list':
			$res = $bulkmail->_templetList($_REQUEST['tpidx']);
			break;
		case 'read':
			$res = $bulkmail->_readTemplet($_REQUEST['idx']);
			break;
		case 'edit':
		case 'add':
		case 'modify':
			$res = $bulkmail->_editTemplet($_REQUEST);
			break;
		case 'delete':
			$res = $bulkmail->_deleteTemplet($_REQUEST['tpidx']);
			break;
		default:
			$result['err'] = '���ǵ��� ���� �޼��� �Դϴ�.';
			break;
	}
	if(!$res['result']) $result['err'] = $res['msg'];
	else if(isset($res['items'])){
		$result['items'] = array();
		$result['items'] = $res['items'];
	}
	if(!isset($result['err']) || empty($result['err'])) $result['err'] = 'ok';
}

//array_walk($result,'_encode');		
exit(json_encode($result));

// �ʿ� �Լ� ���� - ���� ��ġ��� �Լ� ���� ���� �ʿ�
if(!function_exists('_encode')){
function _encode(&$value,$key){
	if(is_array($value)){
		array_walk($value,'_encode');
	}else{
		$value = iconv('EUC-KR','UTF-8',$value);
	}
}
}

if(!function_exists('_iconvFromUtf8')){
function _iconvFromUtf8(&$value,$key){
	if(is_array($value)){
		array_walk($value,'_iconvFromUtf8');
	}else{
		$value = iconv('UTF-8','EUC-KR',$value);
	}
}
}
?>
