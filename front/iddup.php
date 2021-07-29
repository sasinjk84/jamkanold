<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");

$id=$_REQUEST["id"];

if(strlen($id)<4 || strlen($id)>12) {
	$message="<font color=#FF3300><b>아이디는 4~12자 까지 입력 가능합니다.</b></font>";
} else if(!IsAlphaNumeric($id)) {
	$message="<font color=#FF3300><b>사용 불가능한 문자가 사용되었습니다.</b></font>";
} else if(!eregi("(^[0-9a-zA-Z]{4,12}$)",$id)) {
	$message="<font color=#FF3300><b>사용 불가능한 문자가 사용되었습니다.</b></font>";
} else if(eregi("(\'|\"|\,|\.|&|%|<|>|/|\||\\\\|[ ])",$id)) {
    $message="<font color=#FF3300><b>사용 불가능한 문자가 사용되었습니다.</b></font>";
} else if(strlen($id)<=0) {
    $message="<font color=#FF3300><b>아이디 입력이 안되었습니다.</b></font>";
} else if(strtolower($id)=="admin") {
    $message="<font color=#FF3300><b>사용 불가능한 아이디 입니다.</b></font>";
} else {
	$sql = "SELECT id FROM tblmember WHERE id='".$id."' ";
	$result = mysql_query($sql,get_db_conn());

	if ($row=mysql_fetch_object($result)) {
		$message="<font color=#ff0000><b>아이디가 중복되었습니다.</b></font>";
	} else {
		$sql = "SELECT id FROM tblmemberout WHERE id='".$id."' ";
		$result2 = mysql_query($sql,get_db_conn());
		if($row2=mysql_fetch_object($result2)) {
			$message="<font color=#ff0000><b>아이디가 중복되었습니다.</b></font>";
		} else {
			$message="<font color=#0000ff><b>사용가능한 아이디 입니다.</b></font><div style='margin-top:10px;text-align:center'><a href=\"javascript:useId();\"><img src=\"".$Dir."images/btn_use.gif\" border=\"0\" alt=\"사용하기\"></a></div>";
		}
		mysql_free_result($result2);
	}
	mysql_free_result($result);
}


unset($body);
$sql="SELECT body FROM ".$designnewpageTables." WHERE type='iddup'";
$result=mysql_query($sql,get_db_conn());
if($row=mysql_fetch_object($result)) {
	$body=$row->body;
	$body=str_replace("[DIR]",$Dir,$body);
}
mysql_free_result($result);
?>

<html>
<head>
<title>아이디 중복 확인</title>
<meta http-equiv="CONTENT-TYPE" content="text/html;charset=EUC-KR">
<style>
td	{font-family:"굴림,돋움";color:#4B4B4B;font-size:12px;line-height:17px;}
BODY,DIV,form,TEXTAREA,center,option,pre,blockquote {font-family:Tahoma;color:000000;font-size:9pt;}

A:link    {color:#635C5A;text-decoration:none;}
A:visited {color:#545454;text-decoration:none;}
A:active  {color:#5A595A;text-decoration:none;}
A:hover  {color:#545454;text-decoration:underline;}
.input{font-size:12px;BORDER-RIGHT: #DCDCDC 1px solid; BORDER-TOP: #C7C1C1 1px solid; BORDER-LEFT: #C7C1C1 1px solid; BORDER-BOTTOM: #DCDCDC 1px solid; HEIGHT: 18px; BACKGROUND-COLOR: #ffffff;padding-top:2pt; padding-bottom:1pt; height:19px}
.select{color:#444444;font-size:12px;}
.textarea {border:solid 1;border-color:#e3e3e3;font-family:돋음;font-size:9pt;color:333333;overflow:auto; background-color:transparent}
.btn_pop_close a{display:inline-block;margin-top:10px;padding:10px 20px;border:1px solid #ddd;box-sizing:border-box;text-decoration:none}
.btn_pop_close a:hover{border:1px solid #666}
</style>
<script type="text/javascript">
<!--
	function useId () {
		opener.form1.idChk.value="1";
		window.close();
	}
//-->
</script>
</head>
<body leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" onload="window.resizeTo(282,260);">
<?
if(strlen($body)>0) {
	$pattern=array("(\[MESSAGE\])","(\[OK\])");
	$replace=array($message,"JavaScript:window.close()");
	$body = preg_replace($pattern,$replace,$body);
	if (strpos(strtolower($body),"table")!=false) $body = "<pre>".$body."</pre>";
	else $body = ereg_replace("\n","<br>",$body);

	echo $body;
} else {
?>
<TABLE WIDTH="100%" BORDER="0" CELLPADDING="0" CELLSPACING="0">
	<TR>
		<TD><img src="<?=$Dir?>images/design_adultintro_ids_t.gif" border="0"></TD>
	</TR>
	<TR>
		<TD>
			<table cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td align="center" style="padding:20px 0px;border-bottom:1px solid #f3f3f3"><?=$message?></td>
				</tr>
				<tr>
					<td align="center" class="btn_pop_close"><a href="javascript:window.close()">닫기</a></td>
				</tr>
			</table>
		</TD>
	</TR>
</TABLE>
<?}?>
</center>
</body>
</html>