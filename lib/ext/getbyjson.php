<?
// ajax json �� ���� ��׶��� ���� ó���� ����
error_reporting(0);
$Dir="../../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/ext/product_func.php");

$result = array();
array_walk($_REQUEST,'_iconvFromUtf8');
switch($_REQUEST['act']){
	case 'getProductGosiTitles':
		$items = _productGosiInfo();
		if(count($items) < 1){
			$result['err'] = '��ϵ� ��ǰ������� �׸��� �����ϴ�.';
		}else{
			$result['err'] = 'ok';
			$result['items'] = $items;
		}
		break;
	case 'getProductGosiItems':
		if(!_isInt($_REQUEST['idx'],true)) $result['err'] = '�ĺ� ���� ��ȣ�� ���޵��� �ʾҽ��ϴ�.';
		else{
			$items = _productGosiInfo($_REQUEST['idx']);
			if(count($items) < 1){
				$result['err'] = '��ϵ� ��ǰ������� ���� �׸��� �����ϴ�.';
			}else{
				$result['err'] = 'ok';
				$result['items'] = $items;
			}
		}
		break;
	case 'getLocallist':
		$sql = "select * from rent_location ";
		if(!_isInt($_REQUEST['vender'],true)) $sql .= " where vender='".$_REQUEST['vender']."'";
		if(false === $res = mysql_query($sql,get_db_conn())) $result['err'] = 'DB ȣ�� ����';
		else{
			$result['err'] = 'ok';
			if(mysql_num_rows($res)){
				$result['items'] = array();
				while($row = mysql_fetch_assoc($res)){
					array_push($result['items'],$row);
				}
			}		}
		break;
	default:
		$result['err'] = '���ǵ��� ���� �޼��� �Դϴ�.';
		break;
}
if(!isset($result['err']) || empty($result['err'])) $result['err'] = 'ok';
if(PHP_VERSION > '5.2') array_walk($result,'_encode');
exit(json_encode($result));
?>
