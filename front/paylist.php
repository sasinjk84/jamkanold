<?
	include ($Dir."paygate/paylist.inc.php");
?>

<html>
<head>
<title>����</title>
<meta http-equiv="CONTENT-TYPE" content="text/html;charset=EUC-KR">
<script type="text/javascript" src="<?=$Dir?>lib/lib.js.php"></script>
<SCRIPT LANGUAGE="JavaScript">
<!--
// ī�����â�� ȣ���Ѵ�.
function PaymentOpen() {
	var cval = getCookie("okpayment");
	if (cval!="result") {
		parent.PAY_PROCESS_IFRAME.location.href='<?=$pgurl?>';
	} else if (cval=="result") {
		alert("�����ý��۰��� ������ �̹� �������ϴ�.");
	}
}
//-->
</SCRIPT>
</head>

<body topmargin=0 leftmargin=0 rightmargin=0 marginheight=0 marginwidth=0 onLoad="PaymentOpen()">

</body>
</html>
