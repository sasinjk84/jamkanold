<?
$Dir="../../";
header ("Cache-Control : no-cache");
header ("Cache-Control : post-check=0 pre-check=0");
header ("Pragma:no-cache");

include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/shopdata.php");

include_once './sci_pcc.class.php';
$sci_pcc = new sci_pcc();
$retInfo = $sci_pcc->_retInfo($_REQUEST["retInfo"]);
?>
<html>
<head>
<title>����Ȯ�μ��� ���� ó�����</title>
<meta http-equiv="Content-Type" content="text/html; charset=euc-kr">
<script language="javascript" type="text/javascript">
function end(){

	<?
	if(is_array($retInfo) && !empty($retInfo['reqNum']) && (!empty($retInfo['cellNo']) || !empty($retInfo['di']))){
		if(!empty($retInfo['di'])){
			$sql = "SELECT count(*) FROM tblmember WHERE uniqNo='H".$retInfo['di']."' ";
		}else{
			$sql = "SELECT count(*) FROM tblmember WHERE replace(mobile,'-','') ='".$retInfo['cellNo']."' ";
		}

		if(false !== $res = mysql_query($sql,get_db_conn())){
			if(mysql_result($res,0,0) < 1){
				$sql = 'insert into sci_pcc_log set ';
				foreach($retInfo as $k=>$v){
					if(in_array(substr($k,0,3),array('ext','err'))) continue;
					$sql .= $k."='".$v."',";
				}
				$sql = substr($sql,0,-1);
				@mysql_query($sql,get_db_conn());
				switch($retInfo['addVar']){
					case '/front/member_agree.php': //ȸ�� ���� ������
					case '/m/member_agree.php':
	?>

	opener.document.form1.scitype.value = 'pcc';
	opener.document.form1.sciReqNum.value = '<?=$retInfo['reqNum']?>';
	opener.document.form1.submit();
	//if(opener.document.getElementById('sciResult')) opener.document.getElementById('sciResult').innerHTML('<?=$retInfo['name']?> ���� ���� Ȯ���� ó�� �Ǿ����ϴ�.');
	<?
						break;
					default:
	?>
	alert('ȣ�� ������ ���������� ���� ���� �ʾҽ��ϴ�.');
	<?
						break;
				}
			}else{ ?>
		alert('�̹� ���Ե� ȸ�� �Դϴ�.');
	<? 		}
		}else{ ?>
		alert('DB���� ������ �߻��߽��ϴ�.');
	<?	}
	}else{ ?>
		alert('�˼� ���� ������ �߻��߽��ϴ�.');
	<? } ?>
	self.close();

}
</script>
</head>
<body onLoad="javascript:end()">
</body>
</html>

