<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");

$type=$_POST["type"];
$dir=$_POST["val"];
$file=$_POST["file"];

$path="";

if(strlen($_ShopInfo->getId())==0){
	echo "<script>window.close();</script>";
	exit;
}

$originalpath = $Dir.DataDir."design/";
//$originalpath=ereg_replace("\.\.","",$originalpath);
$originallength=strlen($originalpath);

$dir=ereg_replace("\.\.","",$dir); // �������丮�� �̵� ����
$dir=ereg_replace(" ","",$dir);  // ���� ����

if(strlen($dir)==0)
	$path=$originalpath;
else {
	$dir=ereg_replace("\.\.","",$dir); // �������丮�� �̵� ����
	$dir=ereg_replace(" ","",$dir);  // ���� ����
	$path=$originalpath.$dir."/";
}

if(strlen($path)<strlen($originalpath)) $path=$originalpath;

if($type=="download" && strlen($file)>0) {
	Header("Content-Disposition: attachment; filename=$file");
	Header("Content-Type: application/octet-stream;");
	Header("Pragma: no-cache");
	Header("Expires: 0");
	Header("Content-type: application/octet-stream");

	readfile($path.$file);
	exit;
}

?>

<html>
<head>
<meta http-equiv='Content-Type' content='text/html;charset=euc-kr'>
<title>���ϴٿ�ε�</title>
<style>td {font-size:9pt; font-family: ����;}</style>
<SCRIPT LANGUAGE="JavaScript">
<!--
function PageResize() {
	var oWidth = document.all.table_body.clientWidth + 10;
	var oHeight = document.all.table_body.clientHeight + 70;

	window.resizeTo(oWidth,oHeight);
}

function CheckForm(gbn) {
	iscnt=false;

	for(i=0;i<document.form1.file.options.length;i++) {
		if(document.form1.file.options[i].selected==true) {
			if(document.form1.file.options[i].value.length>0) {
				iscnt=true;
			}
		}
	}
	if(iscnt==false) {
		alert("�ٿ���� ������ �����ϼ���.");
		document.form1.file.focus();
		return;
	}

	document.form1.type.value="download";
	document.form1.submit();
}
//-->
</SCRIPT>
</head>
<body leftmargin=0 topmargin=0 marginwidth=0 marginheight=0 oncontextmenu="return false" style="overflow-x:hidden;overflow-y:hidden;" oncontextmenu="return false" onLoad="PageResize();">
<TABLE WIDTH="350" BORDER=0 CELLPADDING=0 CELLSPACING=0 id=table_body>
<TR>
	<TD>
	<table cellpadding="0" cellspacing="0" width="100%">
	<col width=212></col>
	<col width=></col>
	<col width=20></col>
	<tr>
		<td><img src="images/design_html_title.gif" border="0"></td>
		<td background="images/member_mailallsend_imgbg.gif">&nbsp;</td>
		<td align=right><img src="images/member_mailallsend_img2.gif" border="0"></td>
	</tr>
	</table>
	</TD>
</TR>
<form name=form1 method=post action="<?=$_SERVER[PHP_SELF]?>">
<input type=hidden name=type>
<input type=hidden name=val value="<?=$dir?>">
<tr>
	<TD style="padding:5pt;">
	<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td>
		<select name=file size=20 style="width:100%;font-size:12px;">
<?
		$temp=getFileList(substr($path,0,-1));
		@sort($temp);
		for($i=0;$i<sizeof($temp);$i++) {
			$filename=ereg_replace("\*","",$temp[$i]);
			echo "<option value=\"".$filename."\">".$filename."</option>\n";
			$tok = strtok("\n");
		}
		if ($i==0) echo "<option value=\"\">������ �����ϴ�.";
?>
		</select>
		</td>
	</tr>
	</table>
	</TD>
</tr>
<tr>
	<td style="padding:10">
	<table border=0 cellpadding=2 cellspacing=0 width=100%>
	<tr>
		<td style="color:#6063EA">�ٿ������ ������ ���� �� <FONT COLOR="red">[�ٿ�ε�]</FONT>�� ��������.</td>
	</tr>
	</table>
	</td>
</tr>
<tr><td height=5></td></tr>
<TR>
	<TD align=center>
	<input type=button value="�ٿ�ε�" style="cursor:hand;" onclick="CheckForm()">
	<input type=button value="�ݱ�" style="cursor:hand;" onclick="window.close()">
	</TD>
</TR>
</form>
<tr><td height=10></td></tr>
</table>
</body>
</html>