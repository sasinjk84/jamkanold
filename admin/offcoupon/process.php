<?
if($_REQUEST['act'] == 'exceldown') @set_time_limit(300);
else if($_REQUEST['act'] == 'new') @set_time_limit(0);

$Dir="../../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/class/coupon.php");
include ("../access.php");

####################### ������ ���ٱ��� check ###############
$PageCode = "ma-4";
$MenuCode = "market";
DemoShopCheck("������������� �׽�Ʈ�� �Ұ��� �մϴ�.", $_SERVER['PHP_SELF']);

if (!$_usersession->isAllowedTask($PageCode)) {
		_error('���� ������ �����ϴ�.');
}

$coupon = new coupon();

switch($_REQUEST['act']){
	case 'new':
		//$_REQUEST['issue_tot_no'] = count($_REQUEST['emails']);
		
		$coupon = new coupon();			
		$result = $coupon->_new($_REQUEST);
		if($result['result'] != true){
			if(empty($result['msg'])) $result['msg'] = '������ �������� ���߽��ϴ�.2';
			_back($result['msg']);
		}else{				
			_error('����ó�� �Ǿ����ϴ�.','/admin/offlinecoupon.php');
		}
		break;
	case 'stop':
		$result = $coupon->_stop($_POST['coupon_code']);		
		if($result['result']) $onload="<script>alert('�ش� ������ ���ؼ� �߱����� ó���� �Ϸ�Ǿ����ϴ�.\\n\\n���� �߱޵� ������ ��밡���մϴ�.');</script>";
		break;
	case 'delete':
		$result = $coupon->_delete($_POST['coupon_code']);		
		if($result['result']) _error('������ ���� ���� �Ǿ����ϴ�.','/admin/offlinecoupon.php');
		break;
	case 'issueagain':
		$result = $coupon->_issueRe($_POST['coupon_code'],$_POST['uid']);
		if($result['result']) $onload="<script>alert('".$uid." ȸ���Բ� �ش� ������ ��߱� �Ǿ����ϴ�.');</script>";
		break;
	case 'issuedelete':
		$result = $coupon->_issueDelete($_POST['coupon_code'],$_POST['uid']);
		if($result['result']) $onload="<script>alert('".$uid." ȸ���Կ��� �߱޵� ������ �����Ǿ����ϴ�.');</script>";
		break;	
	case 'exceldown':
		if(empty($_REQUEST['coupon_code'])) _error('�����ڵ� ����.','0');
		$sql = "select * from tblcouponinfo where coupon_code='".$_REQUEST['coupon_code']."' limit 1";
		$res = mysql_query($sql,get_db_conn());
		if(!$res) _error('DB ȣ�� ����','0');
		if(mysql_num_rows($res) < 1) _error('������ ã���� �����ϴ�.','0');
		$cinfo = @mysql_fetch_assoc($res);
		header("Content-Disposition: attachment; filename=couponlist_".$cinfo['coupon_code']."_".date("Ymd").".csv");
		header("Content-type: application/x-msexcel");
	
		$sql = "SELECT o.check_code,i.id from tblcouponissue_off o left join tblcouponissue i on (i.coupon_code = o.coupon_code and i.id=o.id) where o.coupon_code='".$_REQUEST['coupon_code']."' ";
		$patten = array ("\r");
		$replace = array ("");
	
		
		$result = mysql_query($sql,get_db_conn());
	
		$field=array("�����ڵ�","��뿩��","���ID");
		echo getcsvdata($field);
		while ($row=mysql_fetch_object($result)) {
			unset($field);
			$field[]= $row->check_code;
			if(!empty($row->id)){
				$field[]='���';
				$field[]=$row->id;	
			}else{
				$field[]='';
				$field[]='';	
			}
			echo getcsvdata($field);
			flush();
		}
		mysql_free_result($result);
		exit;
		break;
	default:
		_back('���� ���� ���� �޼��� �Դϴ�.');
		break;
}

function _error($msg,$url='/'){	
	exit('<script language="javascript" type="text/javascript">alert("'.str_replace('"',"'",$msg).'"); document.location.replace("'.$url.'");</script>');
}

function _back($msg){
echo '<script language="javascript" type="text/javascript">
		alert("'.str_replace('"',"'",$msg).'");
		history.back();
	</script>';
exit;
}


function getcsvdata($fields = array(), $delimiter = ',', $enclosure = '"') {
	$str = '';
	$escape_char = '\\';
	foreach ($fields as $value) {
		if (strpos($value, $delimiter) !== false ||
		strpos($value, $enclosure) !== false ||
		strpos($value, "\n") !== false ||
		strpos($value, "\r") !== false ||
		strpos($value, "\t") !== false ||
		strpos($value, ' ') !== false) {
			$str2 = $enclosure;
			$escaped = 0;
			$len = strlen($value);
			for ($i=0;$i<$len;$i++) {
				if ($value[$i] == $escape_char) {
					$escaped = 1;
				} else if (!$escaped && $value[$i] == $enclosure) {
					$str2 .= $enclosure;
				} else {
					$escaped = 0;
				}
				$str2 .= $value[$i];
			}
			$str2 .= $enclosure;
			$str .= $str2.$delimiter;
		} else {
			$str .= $value.$delimiter;
		}
	}
	$str = substr($str,0,-1);
	$str .= "\n";
	return $str;
}

?>
