<?
// ���� ���� Ȯ�� ���� �� ó�� �κ�
include_once './sci_pcc.class.php';

$sci_pcc = new sci_pcc();

$reqInfo = $sci_pcc->_reqInfo($_REQUEST['mode']);
?>
<html>
    <head>
        <title>����ſ������� ����Ȯ�μ���</title>
        <meta http-equiv="Content-Type" content="text/html; charset=euc-kr">

<script language="javascript" type="text/javascript">
function scipcc(){
<? if(empty($reqInfo)){?>
	alert('���� Ȯ�� ��û ó���� ����');
	//self.close();
<? }else{ ?>
	document.reqCBAForm.submit();
<? } ?>
}
//-->
</script>
</head>
<body onLoad="javascript:scipcc()">
<form name="reqCBAForm" method="post" action = "https://pcc.siren24.com/pcc_V3/jsp/pcc_V3_j10.jsp">
    <input type="hidden" name="reqInfo"     value = "<?=$reqInfo?>">
    <input type="hidden" name="retUrl"      value = "32http://<?=$_SERVER['HTTP_HOST']?>/sci/pcc/result.php">
</form>
</body>
</html>
