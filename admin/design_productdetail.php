<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
?>

<html>
<meta http-equiv="CONTENT-TYPE" content="text/html;charset=EUC-KR">
<head>
<title>��ǰ �������� ������ ����</title>
<link rel="stylesheet" href="style.css">
<script language="JavaScript">
window.moveTo(10,10);
function choice(code,no) {
	if(confirm(no+'�� �������� �����Ͻðڽ��ϱ�?')){
		try {
			opener.document.form1.up_detail_type.value=code;
		} catch (e) {
			alert("������ ���Ͽ� ������ ������ �ȵǾ����ϴ�.");
		}
		window.close();
	}
}
</script>
</head>
<body leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" oncontextmenu="return false;">
<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
<TR>
	<TD colspan=2>
	<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td><img src="images/product_displaydetail1_img.gif" width="112" height="31" border="0"></td>
		<td width="100%" background="images/popup_top_bg.gif"></td>
		<td align=right><img src="images/member_mailallsend_img2.gif" width="20" height="31" border="0"></td>
	</tr>
	</table>
	</td>
</tr>
<?
$code=$_REQUEST["code"];
$gong=$_REQUEST["gong"];
if ($gong == "Y") {
	$sch="BD";
} else {
	$sch="AD";
}
$sql = "SELECT code FROM tblproductdesigntype ";
$sql.= "WHERE code LIKE '".$sch."%' ORDER BY code ASC ";
$result = mysql_query($sql,get_db_conn());
$i=0;
while($row=mysql_fetch_object($result)) {
	unset($choice_img);
	if (strlen($code)>0 && $code==$row->code) {
		$border=4;
		$choice_img = "<img src=images/btn_selecton.gif align=absmiddle>";
	} else {
		$border=1;
		$choice_img = "<a href=\"JavaScript:choice('".$row->code."','".($i+1)."')\"><img src=images/btn_selectout.gif border=0 align=absmiddle></a>";
	}
	if($i%2==0) echo "<tr>\n";
	echo "	<TD style=\"padding:10pt;\">\n";
	echo "	<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\n";
	echo "	<tr>\n";
	echo "		<td>\n";
	echo "		<table align=\"center\" cellpadding=\"0\" cellspacing=\"0\" width=\"150\">\n";
	echo "		<tr>\n";
	echo "			<td align=center><img src=\"images/product/".$row->code.".gif\" width=\"150\" height=\"150\" border=\"0\" style=\"border-width:".$border."pt; border-color:rgb(222,222,222); border-style:solid;\"></td>\n";
	echo "		</tr>\n";
	echo "		<tr>\n";
	echo "			<td height=4></td>\n";
	echo "		</tr>\n";
	echo "		<tr>\n";
	echo "			<td>\n";
	echo "			<table align=\"center\" cellpadding=\"0\" cellspacing=\"0\" width=\"135\">\n";
	echo "			<tr>\n";
	echo "				<td>".($i+1)."����Ų ".$choice_img."</td>\n";
	echo "			</tr>\n";
	echo "			</table>\n";
	echo "			</td>\n";
	echo "		</tr>\n";
	echo "		</table>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	</table>\n";
	echo "	</TD>\n";
	if($i%2==1) echo "</tr>\n";
	$i++;
}
mysql_free_result($result);
?>
<TR>
	<TD colspan=2><hr size="1" noshade color="#EBEBEB"></TD>
</TR>
<TR>
	<TD colspan=2 align=center><a href="javascript:window.close()"><img src="images/btn_close.gif" width="36" height="18" border="0" vspace="5" border=0 hspace="2"></a></TD>
</TR>
</table>
</body>
</html>