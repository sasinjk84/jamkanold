<?
$Dir="../";
include_once($Dir."lib/init.php");

$productcode=$_REQUEST["productcode"];
$size=$_REQUEST["size"];
?>

<html>
	<head>
		<title>��ǰȮ�뺸��</title>
		<meta http-equiv="CONTENT-TYPE" content="text/html;charset=EUC-KR">
		<meta http-equiv="imagetoolbar" content="no">
		<script type="text/javascript" src="<?=$Dir?>lib/lib.js.php"></script>
		<style>
			td {font-family:����;color:666666;font-size:9pt;}
			tr {font-family:����;color:666666;font-size:9pt;}
			BODY,TD,SELECT,DIV,form,TEXTAREA,center,option,pre,blockquote {font-family:Tahoma;color:000000;font-size:9pt;}
		</style>

		<SCRIPT LANGUAGE="JavaScript">
			<!--
			function primage_view(productcode) {
				tmp = "toolbar=no,menubar=no,resizable=no,status=no,scrollbars=yes";
				sc="yes";
				url = "<?=$Dir.FrontDir?>primage_multiview.php?productcode="+productcode+"&scroll="+sc;
				window.open(url,"primage_view",tmp);
			}
			//-->
		</SCRIPT>
	</head>

	<!--<body topmargin=0 leftmargin=0 rightmargin=0 marginheight=0 marginwidth=0 onselectstart="return false" ondragstart="return false" oncontextmenu="return false">-->
	<body topmargin="0" leftmargin="0" rightmargin="0" marginheight="0" marginwidth="0">
		<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" height="100%">
			<tr>
				<td align="center" style="border:1px solid #e8e8e8;overflow:hidden;"><a href="javascript:primage_view('<?=$productcode?>')"><img src="<?=$Dir?>images/common/trans.gif" border=0 alt="Ŭ���Ͻø� ū �����̹����� ���Ǽ� �ֽ��ϴ�." name=primg width=<?=$size?> height=<?=$size?>></a></td>
			</tr>
			<!--
			<tr><td height=10></td></tr>
			<tr>
				<td align=center><A HREF="javascript:primage_view('<?=$productcode?>')"><img src="<?=$Dir?>images/common/btn_zoom.gif" border=0></A></td>
			</tr>
			<tr><td height=5></td></tr>
			-->
		</table>
	</body>
</html>