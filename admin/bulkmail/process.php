<?
$Dir="../../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/class/bulkmail.php");
include_once($Dir."lib/class/coupon.php");

$bulkmail = new bulkmail();

include ("../access.php");

####################### ������ ���ٱ��� check ###############
$PageCode = "ma-4";
$MenuCode = "market";
DemoShopCheck("������������� �׽�Ʈ�� �Ұ��� �մϴ�.", $_SERVER['PHP_SELF']);

if (!$_usersession->isAllowedTask($PageCode)) {
		_error('���� ������ �����ϴ�.');
}

switch($_REQUEST['act']){
	case 'send':
		switch($_REQUEST['targetType']){
			case 'member':
				if($_REQUEST['memberType'] == 'membergroup'){									
					$targets = $bulkmail->_targetByMemberGroup($_REQUEST['membergroup'],$_REQUEST['addreject']);
				}else if($_REQUEST['memberType'] == 'mailgroup'){
					$targets = $bulkmail->_targetByMailGroup($_REQUEST['mailgroup'],$_REQUEST['addreject']);
				}else{
					$targets = array('result'=>false,'msg'=>'ȸ������ ���� ����');
				}
				break;
			case 'csv':
				$targets = $bulkmail->_targetByCSV($_FILES['csvFile'],'2');
				break;
			case 'input':
				$targets = array('result'=>true,'msg'=>'','emails'=>array(),'names'=>array(),'mobiles'=>array());
				if(is_array($_REQUEST['email']) && count($_REQUEST['email']) > 0){
					for($i=0;$i<count($_REQUEST['email']);$i++){
						if(!empty($_REQUEST['email'][$i])){
							$email = $_REQUEST['email'][$i];
							$name = $_REQUEST['name'][$i];
							$mobile = $_REQUEST['mobile'][$i];
							array_push($targets['emails'],$email);
							array_push($targets['names'],$name);
							array_push($targets['mobiles'],$mobile);
						}
					}
				}else{
					$targets = array('result'=>false,'msg'=>'��� ���� ����');
				}
				break;
		}
		if(!$targets['result']){
			if(empty($targets['msg'])) $targets['msg'] = '������ �߼����� ���߽��ϴ�.1';
			_back($targets['msg']);
		}
		$_REQUEST['emails'] = $targets['emails'];
		$_REQUEST['names'] = $targets['names'];
		if(count($_REQUEST['emails']) < 1) _back('�߼۴���� �����ϴ�.');

		if($_REQUEST['setCoupon'] == '1'){			
			$_REQUEST['issue_tot_no'] = count($_REQUEST['emails']);
			$coupon = new coupon();			
			$cres = $coupon->_new($_REQUEST);
			if(!$cres['result']) _back($cres['msg']);
			else $_REQUEST['couponcodes'] = $cres['couponcodes'];
			unset($cres);
		}
		
		/// Logo �̹��� ���� ���ε尡 ���� ���
		$logoimgstr = '';
		if($_REQUEST['useDefLogoimg'] == '1'){
			$logoimgstr = '<img src="http://'.$_SERVER['HTTP_HOST'].'/data/shopimages/etc/logo.gif" border="0" />';
		}else{
			if(!empty($_FILES['logoImg']['tmp_name']) && is_uploaded_file($_FILES['logoImg']['tmp_name'])){
				$ext			=	substr(strrchr($_FILES['logoImg']['name'], '.'),1);
				$saveFiles		=	time().md5(uniqid(mt_rand(0, 1000))).".".$ext;		
				if(move_uploaded_file($_FILES['logoImg']['tmp_name'],$_SERVER["DOCUMENT_ROOT"]."/data/editor/".$saveFiles)){
					$logoimgstr = '<img src="http://'.$_SERVER['HTTP_HOST'].'/data/editor/'.$saveFiles.'" border="0" />';
				}
			}
		}
		if(!empty($logoimgstr)) $_REQUEST['mailContents'] = preg_replace('(\[\$LOGO\])',$logoimgstr,$_REQUEST['mailContents']);
		
		$result = $bulkmail->_send($_REQUEST);
		if($result['result'] != true){
			if(empty($result['msg'])) $result['msg'] = '������ �߼����� ���߽��ϴ�.2';
			_back($result['msg']);
		}else{	
			if($_REQUEST['sendSMS'] == '1'){
				$smsInfo = array();
				$sql = "SELECT id, authkey, return_tel FROM tblsmsinfo ";
				$res=mysql_query($sql,get_db_conn());
				$smsInfo=mysql_fetch_assoc($res);
				$smsInfo['reutrn_tel'] = explode('-',$smsInfo['reutrn_tel']);
				mysql_free_result($res);
				$smsInfo['errMsg'] = '';
				
					
				if(!empty($smsInfo['id']) && !empty($smsInfo['authkey'])){
					$smsInfo['ablecount'] = getSmscount($smsInfo['id'], $smsInfo['authkey']);
					if(substr($smsInfo['ablecount'],0,2) == 'OK'){
						$ablecnt = intval(substr($smsInfo['ablecount'],3));	
						$fromtel= implode('-',$_REQUEST['smsfrom']);
						$smsmsg = $_REQUEST['smsmsg'];
						$pattern = array('(\[\$name\])','(\[\$email\])');
						for($jj=0;$jj < count($targets['emails']) && $ablecnt-->0;$jj++){
							if(empty($targets['mobiles'][$jj])) continue;
							$replace = array($targets['emails'][$jj],$targets['names'][$jj]);
							$smsmsg = preg_replace($pattern,$replace,$_REQUEST['smsmsg']);							
							$temp=SendSMS($smsInfo['id'], $smsInfo['authkey'], $targets['mobiles'][$jj], "", $fromtel, '0', $smsmsg, '�뷮����'); 
						}
					}
				}
			}			
			_error('������ �߼��߽��ϴ�.','/admin/bulkmail.php?act=send');
		}
		break;
	case 'group':
		$result = $bulkmail->_setGroup($_REQUEST);
		if($result['result'] != true){
			if(empty($result['msg'])) $result['msg'] = '������ ���� ó�� ���� ���߽��ϴ�.';
			_back($result['msg']);
		}else{	
			_error('���� �Ǿ����ϴ�.','/admin/bulkmail.php?act=group');
		}
		break;
	case 'deleteGroups':
		$result = $bulkmail->_groupDelete($_REQUEST['selGroup']);
		if($result['result'] != true){
			$msg = empty($result['msg'])?'������ �߼����� ���߽��ϴ�.':$result['msg'];			
		}else{	
			$msg = '���� �Ǿ����ϴ�.';						
		}
		_back($msg);
		break;
	case 'deleteGroup':
		$result = $bulkmail->_groupDelete(array($_REQUEST['dgidx']));
		if($result['result'] != true){
			$msg = empty($result['msg'])?'������ �߼����� ���߽��ϴ�.':$result['msg'];			
		}else{	
			$msg = '���� �Ǿ����ϴ�.';						
		}
		_back($msg);
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
?>
