<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/shopdata.php");

unset($isadmin);
if($_ShopInfo->getId()=="admin") {
	$isadmin=true;
}

$type=$_REQUEST["type"];	//view:���
$survey_code=$_REQUEST["survey_code"];
$val=$_REQUEST["val"];

if(strlen($survey_code)>0) {
	$sql = "SELECT * FROM tblsurveymain WHERE survey_code='".$survey_code."' ";
	$result=mysql_query($sql,get_db_conn());
	if($row=mysql_fetch_object($result)) {
		$sdata=$row;
	} else {
		echo "<html><head><title></title></head><body onload=\"alert('�ش� ��ǥ������ �������� �ʽ��ϴ�.');history.go(-1)\"></body></html>";exit;
	}
	mysql_free_result($result);
}

if($type=="result") {
	if(substr($sdata->grant_type,0,1)=="N" && strlen($_ShopInfo->getMemid())==0) {
		echo "<html><head><title></title></head><body onload=\"alert('�α����� �ϼž� ��ǥ�� �����Ͻ� �� �ֽ��ϴ�.');window.close()\"></body></html>";exit;
	}

	if ($_COOKIE["survey"]==$survey_code) {
		$onload="<script>alert(\"�̹� �������翡 �����ϼ̽��ϴ�\");</script>";
	} else {
		setcookie("survey",$survey_code,time()+2592000);
		$sql = "UPDATE tblsurveymain SET survey_cnt".$val."=survey_cnt".$val."+1 ";
		$sql.= "WHERE survey_code='".$survey_code."' ";
		mysql_query($sql,get_db_conn());

		$sdata->{"survey_cnt".$val}+=1;

		$onload="<script>alert (\"������ �ּż� �����մϴ�.\")</script>";
	}
	$type="view";
} else if($type=="memo") {
	if(substr($sdata->grant_type,1,1)=="N" && strlen($_ShopInfo->getMemid())==0) {
		echo "<html><head><title></title></head><body onload=\"alert('�α����� �ϼž� �ǰ߾��Ⱑ �����մϴ�.');history.go(-1);\"></body></html>";exit;
	}

	$name=$_REQUEST["name"];
	$memo=$_REQUEST["memo"];
	$ip=getenv("REMOTE_ADDR");
	$date=date("YmdHis");

	$sql = "SELECT MAX(no)+1 as no FROM tblsurveyresult WHERE survey_code='".$survey_code."' ";
	$result=mysql_query($sql,get_db_conn());
	$row=mysql_fetch_object($result);
	$no=$row->no;
	if($no<=0) $no=1;
	mysql_free_result($result);

	$sql = "INSERT tblsurveyresult SET ";
	$sql.= "survey_code	= '".$survey_code."', ";
	$sql.= "no			= '".$no."', ";
	$sql.= "name		= '".$name."', ";
	$sql.= "ip			= '".$ip."', ";
	$sql.= "subject		= '".$memo."', ";
	$sql.= "date		= '".$date."' ";
	mysql_query($sql,get_db_conn());
	$onload="<script>alert(\"��ϵǾ����ϴ�.\")</script>";

	$type="view";
} else if($type=="delete") {
	if($isadmin==true) {
		$no=$_REQUEST["no"];
        $sql = "DELETE FROM tblsurveyresult WHERE survey_code='".$survey_code."' AND no='".$no."' ";
        mysql_query($sql,get_db_conn());
        $onload="<script>alert(\"�����Ǿ����ϴ�.\")</script>";
	}
	$type="view";
}

if($type=="view") {
	$NO_total=$sdata->survey_cnt1+$sdata->survey_cnt2+$sdata->survey_cnt3+$sdata->survey_cnt4+$sdata->survey_cnt5;
	$surveydate=substr($sdata->survey_code,0,4).".".substr($sdata->survey_code,4,2).".".substr($sdata->survey_code,6,2);

	unset($sel_list);

	$j=0;
	if(strlen($sdata->survey_select1)>0) {
		$sel_list[$j]["sel"]=$sdata->survey_select1;
		$sel_list[$j]["cnt"]=$sdata->survey_cnt1;
		$j++;
	}
	if(strlen($sdata->survey_select2)>0) {
		$sel_list[$j]["sel"]=$sdata->survey_select2;
		$sel_list[$j]["cnt"]=$sdata->survey_cnt2;
		$j++;
	}
	if(strlen($sdata->survey_select3)>0) {
		$sel_list[$j]["sel"]=$sdata->survey_select3;
		$sel_list[$j]["cnt"]=$sdata->survey_cnt3;
		$j++;
	}
	if(strlen($sdata->survey_select4)>0) {
		$sel_list[$j]["sel"]=$sdata->survey_select4;
		$sel_list[$j]["cnt"]=$sdata->survey_cnt4;
		$j++;
	}
	if(strlen($sdata->survey_select5)>0) {
		$sel_list[$j]["sel"]=$sdata->survey_select5;
		$sel_list[$j]["cnt"]=$sdata->survey_cnt5;
		$j++;
	}

	$surveyresult="<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"100%\">\n";
	$MAX_barsize =  100 ;
	for($y=0;$y<count($sel_list);$y++) {
		$allocate_rate = @round(($sel_list[$y]["cnt"] / $NO_total) * 100);
		$bar_width = @round(($sel_list[$y]["cnt"] / $NO_total) * $MAX_barsize );
		$surveyresult.="<tr height=\"19\">\n";
		$surveyresult.="	<td width=\"55%\" style=\"font-size:11px;letter-spacing:-0.5pt;\"><img src=\"".$Dir."images/survey/survey_text_icon.gif\" border=\"0\" hspace=\"3\">".$sel_list[$y]["sel"]."</td>\n";
		$surveyresult.="	<td width=\"29%\">\n";
		$surveyresult.="	<table cellpadding=\"0\" cellspacing=\"0\" width=\"".$bar_width."\" align=\"right\">\n";
		$surveyresult.="	<tr>\n";
		$surveyresult.="		<td bgcolor=\"#0099CC\" height=\"6\"></td>\n";
		$surveyresult.="	</tr>\n";
		$surveyresult.="	</table>\n";
		$surveyresult.="	</td>\n";
		$surveyresult.="	<td width=\"16%\" style=\"padding-left:3pt;\"><font color=\"#FF6600\" style=\"font-size:8pt;\"><b>".$sel_list[$y]["cnt"]."</b>(".$allocate_rate."%)</font></td>\n";
		$surveyresult.="</tr>\n";
	}
	$surveyresult.="</table>\n";

	$surveyreview="";
	$surveyreview.="<table border=0 cellpadding=0 cellspacing=0 width=100%>\n";
	$surveyreview.="<tr>\n";
	$surveyreview.="	<td background=\"".$Dir."images/survey/survey_table_02a.gif\" style=\"padding-right:10pt;padding-left:10pt;\">\n";
	$surveyreview.="	<TABLE cellSpacing=0 cellPadding=0 width=\"100%\">\n";
	if($sdata->display=="Y") {
		$surveyreview.="<form name=form1 method=post action=\"".$_SERVER[PHP_SELF]."\">\n";
		$surveyreview.="<input type=hidden name=type value=\"\">\n";
		$surveyreview.="<input type=hidden name=survey_code value=\"".$survey_code."\">\n";
		$surveyreview.="<TR>\n";
		$surveyreview.="	<TD style=\"FONT-SIZE: 12px; LINE-HEIGHT: 16px; LETTER-SPACING: -0.5pt\" height=\"30\">\n";
		$surveyreview.="	<TABLE cellSpacing=\"0\" cellPadding=\"0\" width=\"100%\" border=\"0\">\n";
		$surveyreview.="	<TR>\n";
		$surveyreview.="		<TD width=\"8%\" align=\"center\">�̸�</TD>\n";
		$surveyreview.="		<TD width=\"16%\"><INPUT type=text name=\"name\" size=\"9\" maxLength=\"10\" style=\"FONT-SIZE:11px;WIDTH:100%;HEIGHT:18px;BORDER:#DCDCDC 1px solid;HEIGHT:18px;BACKGROUND-COLOR:#ffffff;padding-top:2pt;padding-bottom:1pt;\"></TD>\n";
		$surveyreview.="		<TD width=\"8%\" align=\"center\">�޸�</TD>\n";
		$surveyreview.="		<TD width=\"56%\"><INPUT type=text name=\"memo\" maxLength=\"250\" style=\"FONT-SIZE:11px;WIDTH:100%;HEIGHT:18px;BORDER:#DCDCDC 1px solid;HEIGHT:18px;BACKGROUND-COLOR:#ffffff;padding-top:2pt;padding-bottom:1pt;\"></TD>\n";
		$surveyreview.="		<TD width=\"12%\" align=\"center\"><a href=\"javascript:CheckForm();\"><img src=\"".$Dir."images/survey/survey_textup.gif\" border=\"0\" align=\"absmiddle\"></a></TD>\n";
		$surveyreview.="	</TR>\n";
		$surveyreview.="	</TABLE>\n";
		$surveyreview.="	</TD>\n";
		$surveyreview.="</TR>\n";
		$surveyreview.="</form>\n";
	}
	$surveyreview.="<TR>\n";
	$surveyreview.="	<TD><HR color=\"#e5e5e5\" noShade SIZE=\"1\"></TD>\n";
	$surveyreview.="</TR>\n";
	$surveyreview.="<TR>\n";
	$surveyreview.="	<TD>\n";

	$sql = "SELECT * FROM tblsurveyresult WHERE survey_code='".$survey_code."' ORDER BY date DESC ";
	$result=mysql_query($sql,get_db_conn());
	$i=0;
	$surveyreview.="<TABLE cellSpacing=\"0\" cellPadding=\"0\" width=\"100%\" border=\"0\">\n";

	while($row=mysql_fetch_object($result)) {
		$i++;
		$date=substr($row->date,0,4)."/".substr($row->date,4,2)."/".substr($row->date,6,2);
		if($i!=1) {
			$surveyreview.="<TR>\n";
			$surveyreview.="	<TD height=\"1\" background=\"".$Dir."images/survey/survey_point_line.gif\"></TD>\n";
			$surveyreview.="</TR>\n";
		}
		$surveyreview.="<TR>\n";
		$surveyreview.="	<TD style=\"padding-top:5pt; padding-bottom:5pt;\">\n";
		$surveyreview.="	<TABLE cellSpacing=\"0\" cellPadding=\"2\" width=\"100%\" border=\"0\">\n";
		$surveyreview.="	<TR>\n";
		$surveyreview.="		<TD><B>".$row->name."</B>\n";
		if($sdata->ip_yn=="Y") {
			$surveyreview.="&nbsp;<FONT style=\"FONT-SIZE: 11px\">[IP : ".$row->ip."]</FONT></TD>\n";
		}
		$surveyreview.="		<TD align=right>\n";
		if($isadmin==true) {
			$surveyreview.="<A HREF=\"javascript:memodel('".$row->no."');\"><img src=\"".$Dir."images/survey/del_x.gif\" border=\"0\" align=\"absmiddle\"></A>&nbsp;&nbsp;";
		}
		$surveyreview.="".$date."</TD>\n";
		$surveyreview.="	</TR>\n";
		$surveyreview.="	<TR>\n";
		$surveyreview.="		<TD colspan=\"2\">".$row->subject."</TD>\n";
		$surveyreview.="	</TR>\n";
		$surveyreview.="	</TABLE>\n";
		$surveyreview.="	</TD>\n";
		$surveyreview.="</TR>\n";
	}
	mysql_free_result($result);
	if($i==0) {
		$surveyreview.="<tr><td align=\"center\" style=\"padding:5;border:#DFDFDF solid 1px\">��ϵ� �ǰ��� �����ϴ�.</td></tr>\n";
		$surveyreview.="<tr><td height=\"5\"></td></tr>\n";
	}

	$surveyreview.="		</TABLE>\n";
	$surveyreview.="		</td>\n";
	$surveyreview.="	</tr>\n";
	$surveyreview.="	</TABLE>\n";
	$surveyreview.="	</td>\n";
	$surveyreview.="</tr>\n";
	if($isadmin==true){
		$surveyreview.="<form name=form2 method=post action=\"".$_SERVER[PHP_SELF]."\">\n";
		$surveyreview.="<input type=hidden name=type value=\"\">\n";
		$surveyreview.="<input type=hidden name=survey_code value=\"".$survey_code."\">\n";
		$surveyreview.="<input type=hidden name=no value=\"\">\n";
		$surveyreview.="</form>\n";
	}
	$surveyreview.="</table>\n";

	$surveytitle=$sdata->survey_content;

?>
<html>
<head>
<title>��ǥ�������</title>
<meta http-equiv="CONTENT-TYPE" content="text/html;charset=EUC-KR">

<script type="text/javascript" src="<?=$Dir?>lib/lib.js.php"></script>
<style>
td	{font-family:"����,����";color:#4B4B4B;font-size:12px;line-height:17px;}
BODY,DIV,form,TEXTAREA,center,option,pre,blockquote {font-family:Tahoma;color:000000;font-size:9pt;}

A:link    {color:#635C5A;text-decoration:none;}
A:visited {color:#545454;text-decoration:none;}
A:active  {color:#5A595A;text-decoration:none;}
A:hover  {color:#545454;text-decoration:underline;}
.input{font-size:12px;BORDER-RIGHT: #DCDCDC 1px solid; BORDER-TOP: #C7C1C1 1px solid; BORDER-LEFT: #C7C1C1 1px solid; BORDER-BOTTOM: #DCDCDC 1px solid; HEIGHT: 18px; BACKGROUND-COLOR: #ffffff;padding-top:2pt; padding-bottom:1pt; height:19px}
.select{color:#444444;font-size:12px;}
.textarea {border:solid 1;border-color:#e3e3e3;font-family:����;font-size:9pt;color:333333;overflow:auto; background-color:transparent}
</style>
<SCRIPT LANGUAGE="JavaScript">
<!--
window.resizeTo(510,530);
function CheckForm() {
	if(document.form1.name.value.length==0) {
		alert("�̸��� �Է��ϼ���.");
		document.form1.name.focus();
		return;
	}
	if(document.form1.memo.value.length==0) {
		alert("�ǰ��� �Է��ϼ���.");
		document.form1.memo.focus();
		return;
	}
	document.form1.type.value="memo";
	document.form1.submit();
}

document.onkeydown = CheckKeyPress;
document.onkeyup = CheckKeyPress;
function CheckKeyPress() {
	ekey = event.keyCode;

	if(ekey == 38 || ekey == 40 || ekey == 112 || ekey ==17 || ekey == 18 || ekey == 25 || ekey == 122 || ekey == 116) {
	   event.keyCode = 0;
	   return false;
	 }
}

<?if($isadmin==true){?>
function memodel(no) {
	if(confirm("�ش� �ǰ��� �����Ͻðڽ��ϱ�?")) {
		document.form2.type.value="delete";
		document.form2.no.value=no;
		document.form2.submit();
	}
}
<?}?>
//-->
</SCRIPT>
</head>

<body topmargin="0" leftmargin="0" rightmargin="0" marginheight="0" marginwidth="0">
<?
$sql="SELECT body FROM ".$designnewpageTables." WHERE type='surveyview'";
$result=mysql_query($sql,get_db_conn());
$body="";
if($row=mysql_fetch_object($result)) {
	$body=$row->body;
	$body=str_replace("[DIR]",$Dir,$body);
} else {
	$body.="<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\n";
	$body.="<tr>\n";
	$body.="	<td><IMG  src=\"".$Dir."images/survey/survey_title.gif\" border=\"0\"></td>\n";
	$body.="</tr>\n";
	$body.="<tr>\n";
	$body.="	<td style=\"padding:10px;padding-left:11px;\">\n";
	$body.="	<table align=\"center\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\n";
	$body.="	<tr>\n";
	$body.="		<td>\n";
	$body.="		<TABLE cellSpacing=\"0\" cellPadding=\"0\" width=\"100%\" border=\"0\">\n";
	$body.="		<TR>\n";
	$body.="			<TD bgcolor=\"#000000\" colSpan=\"4\" height=\"2\"></TD>\n";
	$body.="		</TR>\n";
	$body.="		<TR>\n";
	$body.="			<TD bgcolor=\"#f8f8f8\" style=\"PADDING:5px;letter-spacing:-0.5pt;\"><IMG src=\"".$Dir."images/survey/survey_popup_point2.gif\" border=\"0\"><font color=\"#333333\"><b>��������</b></font></TD>\n";
	$body.="			<TD colspan=\"3\" style=\"PADDING:5px;BORDER-LEFT:#e3e3e3 1px solid;\">[SURVEYTITLE]</TD>\n";
	$body.="		</TR>\n";
	$body.="		<TR>\n";
	$body.="			<TD bgcolor=\"#E8E8E8\" colSpan=\"4\" height=\"1\"></TD>\n";
	$body.="		</TR>\n";
	$body.="		<TR>\n";
	$body.="			<TD width=\"20%\" bgcolor=\"#f8f8f8\" style=\"PADDING:5px;letter-spacing:-0.5pt;\"><IMG src=\"".$Dir."images/survey/survey_popup_point2.gif\" border=\"0\"><font color=\"#333333\"><b>��������</b></font></TD>\n";
	$body.="			<TD width=\"30%\" style=\"PADDING:5px;BORDER-LEFT:#e3e3e3 1pt solid;\">[SURVEYDATE]</TD>\n";
	$body.="			<TD width=\"20%\" bgcolor=\"#f8f8f8\" style=\"PADDING:5px;BORDER-LEFT:#e3e3e3 1pt solid;letter-spacing:-0.5pt;\"><IMG src=\"".$Dir."images/survey/survey_popup_point2.gif\" border=\"0\"><font color=\"#333333\"><b>��ǥ�ڼ�</b></font></TD>\n";
	$body.="			<TD width=\"30%\" style=\"PADDING:5px;BORDER-LEFT: #e3e3e3 1pt solid;\">[SURVEYTOT]</TD>\n";
	$body.="		</TR>\n";
	$body.="		<TR>\n";
	$body.="			<TD bgcolor=\"#E8E8E8\" colSpan=\"4\" height=\"1\"></TD>\n";
	$body.="		</TR>\n";
	$body.="		</TABLE>\n";
	$body.="		</td>\n";
	$body.="	</tr>\n";
	$body.="	<tr>\n";
	$body.="		<td height=\"20\"></td>\n";
	$body.="	</tr>\n";
	$body.="	<tr>\n";
	$body.="		<td>\n";
	$body.="		<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\n";
	$body.="		<tr>\n";
	$body.="			<td height=\"2\" bgcolor=\"#000000\"></td>\n";
	$body.="		</tr>\n";
	$body.="		<tr>\n";
	$body.="			<td style=\"padding:5px;\">[SURVEYRESULT]</td>\n";
	$body.="		</tr>\n";
	$body.="		<tr>\n";
	$body.="			<td height=\"1\" bgcolor=\"#DDDDDD\"></td>\n";
	$body.="		</tr>\n";
	$body.="		</table>\n";
	$body.="		</td>\n";
	$body.="	</tr>\n";
	$body.="	<tr>\n";
	$body.="		<td height=\"20\"></td>\n";
	$body.="	</tr>\n";
	$body.="	<tr>\n";
	$body.="		<td>\n";
	$body.="		<TABLE WIDTH=\"100%\" BORDER=\"0\" CELLPADDING=\"0\" CELLSPACING=\"0\">\n";
	//if($sdata->display=="Y") {
		$body.="		<TR>\n";
		$body.="			<TD><IMG SRC=\"".$Dir."images/survey/survey_table_01a.gif\" border=\"0\"></TD>\n";
		$body.="		</TR>\n";
	//}
	$body.="		<TR>\n";
	$body.="			<TD>[SURVEYREVIEW]</TD>\n";
	$body.="		</TR>\n";
	$body.="		<TR>\n";
	$body.="			<TD><IMG SRC=\"".$Dir."images/survey/survey_table_03a.gif\" border=\"0\"></TD>\n";
	$body.="		</TR>\n";
	$body.="		</TABLE>\n";
	$body.="		</td>\n";
	$body.="	</tr>\n";
	$body.="	<tr>\n";
	$body.="		<td align=\"center\"><A HREF=[SURVEYLIST]><img src=\"".$Dir."images/survey/survey_btn1.gif\" border=\"0\" vspace=\"6\"></a><A HREF=[SURVEYCLOSE]><img src=\"".$Dir."images/survey/survey_btn2.gif\" border=\"0\" vspace=\"6\" hspace=\"5\"></a></td>\n";
	$body.="	</tr>\n";
	$body.="	</table>\n";
	$body.="	</td>\n";
	$body.="</tr>\n";
	$body.="</table>\n";
}
mysql_free_result($result);

$pattern=array ("(\[SURVEYTITLE\])","(\[SURVEYDATE\])","(\[SURVEYTOT\])","(\[SURVEYRESULT\])","(\[SURVEYREVIEW\])","(\[SURVEYLIST\])","(\[SURVEYCLOSE\])");
$replace=array ($surveytitle,$surveydate,$NO_total,$surveyresult,$surveyreview,$_SERVER[PHP_SELF],"javascript:window.close()");
$body=preg_replace($pattern,$replace,$body);

echo "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"480\" style=\"table-layout:fixed\"><tr><td align=\"center\">".$body."</td></tr></table>";

?>

<?=$onload?>
</body>
</html>
<?
} else {
?>

<html>
<head>
<title>��ǥ����Ʈ</title>
<meta http-equiv="CONTENT-TYPE" content="text/html;charset=EUC-KR">

<script type="text/javascript" src="<?=$Dir?>lib/lib.js.php"></script>
<style>
td	{font-family:"����,����";color:#4B4B4B;font-size:12px;line-height:17px;}
BODY,DIV,form,TEXTAREA,center,option,pre,blockquote {font-family:Tahoma;color:000000;font-size:9pt;}

A:link    {color:#635C5A;text-decoration:none;}
A:visited {color:#545454;text-decoration:none;}
A:active  {color:#5A595A;text-decoration:none;}
A:hover  {color:#545454;text-decoration:underline;}
.input{font-size:12px;BORDER-RIGHT: #DCDCDC 1px solid; BORDER-TOP: #C7C1C1 1px solid; BORDER-LEFT: #C7C1C1 1px solid; BORDER-BOTTOM: #DCDCDC 1px solid; HEIGHT: 18px; BACKGROUND-COLOR: #ffffff;padding-top:2pt; padding-bottom:1pt; height:19px}
.select{color:#444444;font-size:12px;}
.textarea {border:solid 1;border-color:#e3e3e3;font-family:����;font-size:9pt;color:333333;overflow:auto; background-color:transparent}
</style>
<SCRIPT LANGUAGE="JavaScript">
<!--
window.resizeTo(510,530);
//-->
</SCRIPT>
</head>

<body topmargin="0" leftmargin="0" rightmargin="0" marginheight="0" marginwidth="0">
<?
$sql="SELECT body FROM ".$designnewpageTables." WHERE type='surveylist'";
$result=mysql_query($sql,get_db_conn());
$body="";
if($row=mysql_fetch_object($result)) {
	$body=$row->body;
	$body=str_replace("[DIR]",$Dir,$body);
} else {
	$body.="<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\n";
	$body.="<tr>\n";
	$body.="	<td><IMG src=\"".$Dir."images/survey/survey_title1.gif\" border=0></td>\n";
	$body.="</tr>\n";
	$body.="<tr>\n";
	$body.="	<td style=\"padding:10px;padding-left:10px;\">\n";
	$body.="	<table align=\"center\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\n";
	$body.="	<tr>\n";
	$body.="		<td>[SURVEYLIST]</td>\n";
	$body.="	</tr>\n";
	$body.="	<tr>\n";
	$body.="		<td align=\"center\"><A HREF=[SURVEYCLOSE]><img src=\"".$Dir."images/survey/survey_btnclose.gif\" border=\"0\" vspace=\"6\"></a></td>\n";
	$body.="	</tr>\n";
	$body.="	<tr>\n";
	$body.="		<td height=\"20\"></td>\n";
	$body.="	</tr>\n";
	$body.="	</table>\n";
	$body.="	</td>\n";
	$body.="</tr>\n";
	$body.="</table>\n";
}
mysql_free_result($result);

$surveylist.="	<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"table-layout:fixed\">\n";
$surveylist.="	<col width=\"40\"></col>\n";
$surveylist.="	<col width=></col>\n";
$surveylist.="	<col width=\"100\"></col>\n";
$surveylist.="	<col width=\"60\"></col>\n";
$surveylist.="	<tr>\n";
$surveylist.="		<td height=\"2\" colspan=\"4\" bgcolor=\"#000000\"></td>\n";
$surveylist.="	</tr>\n";
$surveylist.="	<tr height=\"30\" align=\"center\" bgcolor=\"#F8F8F8\" style=\"letter-spacing:-0.5pt;\">\n";
$surveylist.="		<td><font color=\"#333333\"><b>NO</b></font></td>\n";
$surveylist.="		<td><font color=\"#333333\"><b>��������</b></font></td>\n";
$surveylist.="		<td><font color=\"#333333\"><b>����</b></font></td>\n";
$surveylist.="		<td><font color=\"#333333\"><b>�����ڼ�</b></font></td>\n";
$surveylist.="	</tr>\n";
$surveylist.="	<tr>\n";
$surveylist.="		<td height=\"1\" colspan=\"4\" bgcolor=\"#DDDDDD\"></td>\n";
$surveylist.="	</tr>\n";

$sql = "SELECT * FROM tblsurveymain ORDER BY survey_code DESC ";
$result=mysql_query($sql,get_db_conn());
$i=0;
while($row=mysql_fetch_object($result)) {
	$i++;
	$strdis="";
	if($row->display=="Y") {
		$strdis="<img src=\"".$Dir."images/survey/survey_icon1.gif\" border=\"0\">";
		$strdisc="#FF6600";
		$date="<br><font color=\"".$strdisc."\" style=\"font-size:8pt\"><b>(".substr($row->survey_code,0,4).".".substr($row->survey_code,4,2).".".substr($row->survey_code,6,2).")</b></font>";
	} else {
		$strdis="<img src=\"".$Dir."images/survey/survey_icon2.gif\" border=\"0\">";
		$strdisc="#333333";
		$date="<br><font color=\"".$strdisc."\" style=\"font-size:8pt\">(".substr($row->survey_code,0,4).".".substr($row->survey_code,4,2).".".substr($row->survey_code,6,2).")</font>";
	}

	$surveylist.="<tr align=\"center\" style=\"padding-bottom:3px;padding-top:3px;\">\n";
	$surveylist.="	<td><font color=\"#333333\">".$i."</font></td>\n";
	$surveylist.="	<td align=\"left\"><A HREF=\"".$_SERVER[PHP_SELF]."?type=view&survey_code=".$row->survey_code."\"><font color=\"".$strdisc."\">".$row->survey_content."</font>".$date."</A></td>\n";
	$surveylist.="	<td>".$strdis."</td>\n";
	$surveylist.="	<td><font color=\"#333333\">".number_format($row->survey_cnt1+$row->survey_cnt2+$row->survey_cnt3+$row->survey_cnt4+$row->survey_cnt5)."</font></td>\n";
	$surveylist.="</tr>\n";
	$surveylist.="<tr><td height=\"1\" colspan=\"4\" bgcolor=\"#DDDDDD\"></td></tr>";
}
mysql_free_result($result);
if($i==0) {
	$surveylist.="<tr align=\"center\" style=\"padding-bottom:3px;padding-top:3px;\"><td colspan=\"4\">��ϵ� ��ǥ������ �����ϴ�.</td></tr>";
	$surveylist.="<tr><td height=\"1\" colspan=\"4\" bgcolor=\"#DDDDDD\"></td></tr>";
}
$surveylist.="	</table>\n";

$pattern=array ("(\[SURVEYLIST\])","(\[SURVEYCLOSE\])");
$replace=array ($surveylist,"javascript:window.close()");
$body=preg_replace($pattern,$replace,$body);

echo "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"table-layout:fixed\"><tr><td align=\"center\">".$body."</td></tr></table>";
?>
</body>
</html>
<?}?>