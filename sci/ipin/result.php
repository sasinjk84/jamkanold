<?
$Dir="../../";
header ("Cache-Control : no-cache");
header ("Cache-Control : post-check=0 pre-check=0");
header ("Pragma:no-cache");

include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/shopdata.php");

include_once './sci_ipin.class.php';
$sci_ipin = new sci_ipin();
$retInfo = $sci_ipin->_retInfo($_REQUEST["retInfo"]);
?>
<html>
<head>
<title>����Ȯ�μ��� ���� ó�����</title>
<meta http-equiv="Content-Type" content="text/html; charset=euc-kr">
<script language="javascript" type="text/javascript">
function end(){
	
	<?
	if(is_array($retInfo) && !empty($retInfo['reqNum']) && !empty($retInfo['discrHash'])){
		$sql = "select count(*) from tblmember where uniqNo='I".$retInfo['discrHash']."'";
		if(false !== $res = mysql_query($sql,get_db_conn())){		
			if(mysql_result($res,0,0) < 1){
				$sql = 'insert into sci_ipin_log set ';
				foreach($retInfo as $k=>$v){
					if(in_array(substr($k,0,3),array('ext','err'))) continue;
					$sql .= $k."='".$v."',";
				}
				$sql = substr($sql,0,-1);		
				@mysql_query($sql,get_db_conn());
				switch($_REQUEST['mode']){
					case '/front/member_agree.php': //ȸ�� ���� ������
					case '/m/member_agree.php':
	?>
	
	opener.document.form1.scitype.value = 'ipin';
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
<?			}
		}else{ ?>
			alert('DB ���� ����');		
<?		}
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

