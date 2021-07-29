 <?
error_reporting(E_ALL);
ini_set("display_errors", 0);

$Dir="../../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
require_once($Dir."lib/ext/func.php");
require_once($Dir."lib/ext/coupon_func.php");
$result = array();
try{
	switch($_REQUEST['act']){
		case 'confirmdiscountchange':
			if(!preg_match('/^[0-9]{18}$/',$_REQUEST['code'])) throw new ErrorException('코드값 오류');
			$sql = "insert into tblmemberdiscount (group_code,productcode,discount) select group_code,productcode,discount from  discount_chgrequest d where d.productcode='".$_REQUEST['code']."' ON DUPLICATE KEY UPDATE discount=d.discount";			
			if(false === mysql_query($sql,get_db_conn())) throw new ErrorException('DB 처리 오류 1');
			if(false === mysql_query("delete from discount_chgrequest where productcode='".$_REQUEST['code']."'",get_db_conn())) throw new ErrorException('DB 처리 오류 2');			
			break;
		case 'rejectdiscountchange':
			if(!preg_match('/^[0-9]{18}$/',$_REQUEST['code'])) throw new ErrorException('코드값 오류');
			if(false === mysql_query("delete from discount_chgrequest where productcode='".$_REQUEST['code']."'",get_db_conn())) throw new ErrorException('DB 처리 오류');
			break;
		case 'confirmreservechange'://적럽금
			if(!preg_match('/^[0-9]{18}$/',$_REQUEST['code'])) throw new ErrorException('코드값 오류');
			$sql = "insert into tblmemberreserve (group_code,productcode,reserve) select group_code,productcode,reserve from  reserve_chgrequest d where d.productcode='".$_REQUEST['code']."' order by rcidx asc ON DUPLICATE KEY UPDATE reserve=d.reserve";			
			if(false === mysql_query($sql,get_db_conn())) throw new ErrorException('DB 처리 오류 1');
			if(false === mysql_query("delete from reserve_chgrequest where productcode='".$_REQUEST['code']."'",get_db_conn())) throw new ErrorException('DB 처리 오류 2');			
			break;
		case 'rejectreservechange':
			if(!preg_match('/^[0-9]{18}$/',$_REQUEST['code'])) throw new ErrorException('코드값 오류');
			if(false === mysql_query("delete from reserve_chgrequest where productcode='".$_REQUEST['code']."'",get_db_conn())) throw new ErrorException('DB 처리 오류');
			break;
		case 'confirmRereservechange'://추천인적럽금
			if(!preg_match('/^[0-9]{18}$/',$_REQUEST['code'])) throw new ErrorException('코드값 오류');
			$sql = "insert into tblreseller_reserve (group_code,productcode,reserve) select group_code,productcode,reserve from  reseller_reserve_chgrequest d where d.productcode='".$_REQUEST['code']."' order by rrcidx asc ON DUPLICATE KEY UPDATE reserve=d.reserve";			
			if(false === mysql_query($sql,get_db_conn())) throw new ErrorException('DB 처리 오류 1');
			if(false === mysql_query("delete from reseller_reserve_chgrequest where productcode='".$_REQUEST['code']."'",get_db_conn())) throw new ErrorException('DB 처리 오류 2');			
			break;
		case 'rejectRereservechange':
			if(!preg_match('/^[0-9]{18}$/',$_REQUEST['code'])) throw new ErrorException('코드값 오류');
			if(false === mysql_query("delete from reseller_reserve_chgrequest where productcode='".$_REQUEST['code']."'",get_db_conn())) throw new ErrorException('DB 처리 오류');
			break;
		case 'changeReReserv':
			if(!preg_match('/^[0-9]{18}$/',$_REQUEST['code'])) throw new ErrorException('코드값 오류');
			if($_REQUEST['cftype'] == '1'){
				$sql = "update tblproduct p left join req_chgresellerreserv r on r.productcode=p.productcode set p.reseller_reserve=r.reseller_reserve where p.productcode='".$_REQUEST['code']."'";			
				if(false === mysql_query($sql,get_db_conn())) throw new ErrorException('DB 처리 오류 1');				
			}
			if(false === mysql_query("delete from req_chgresellerreserv where productcode='".$_REQUEST['code']."'",get_db_conn())) throw new ErrorException('DB 처리 오류 2');
			break;
		case 'setuseseason':
			if(!preg_match('/^[0-9]{12}$/',$_REQUEST['code'])) throw new ErrorException('코드값 오류');
			$sql = "insert into code_rent set code='".$_REQUEST['code']."',useseason='".($_REQUEST['useseason']=='1'?'1':'0')."' on DUPLICATE key update useseason='".($_REQUEST['useseason']=='1'?'1':'0')."'";
			if(false === mysql_query($sql,get_db_conn())) throw new ErrorException('DB 연동 오류');
			break;			
		case 'setpricetype':
			if(!preg_match('/^[0-9]{12}$/',$_REQUEST['code'])) throw new ErrorException('코드값 오류');
			if(!in_array($_REQUEST['pricetype'],array('time', 'day', 'checkout'))) throw new  ErrorException('가격 형태 오류');
			$sql = "insert into code_rent set code='".$_REQUEST['code']."',pricetype='".$_REQUEST['pricetype']."' on DUPLICATE key update useseason='".$_REQUEST['pricetype']."'";
			if(false === mysql_query($sql,get_db_conn())) throw new ErrorException('DB 연동 오류');
			break;
		case 'setreseller_reserve':
			if(!preg_match('/^[0-9]{12}$/',$_REQUEST['code'])) throw new ErrorException('코드값 오류');
			if(!_isInt($_REQUEST['reseller_reserve'],true) || $_REQUEST['reseller_reserve'] > 100) throw new  ErrorException('적립율은 100이내의 숫자로 입력하셔야 합니다.');
			$sql = "update tblproductcode set reseller_reserve='".($_REQUEST['reseller_reserve']/100)."' where concat(codeA,codeB,codeC,codeD) = '".$_REQUEST['code']."'";
			if(false === mysql_query($sql,get_db_conn())) throw new ErrorException('DB 연동 오류');
			break;
		default:
			throw new ErrorException('정의되지 않은 요청 입니다.');
			break;
	}
	$result['err']='ok';
}catch(Exception $e){
	$result['err'] = $e->getMessage();	
}

// php  5.2.0 이상은 추가
$phpVer = str_replace(".","",phpversion());
if( $phpVer >= 520 ) array_walk($result,'_encode');

echo json_encode($result);
exit;
?>