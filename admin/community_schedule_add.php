<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");

if(strlen($_ShopInfo->getId())==0){
	echo "<script>alert('�������� ��η� �����Ͻñ� �ٶ��ϴ�.');window.close();</script>";
	exit;
}

$CurrentTime = time();

if ($mode == "add") {
	$subject = addslashes($subject);
	$comment = addslashes($comment);
	$duedate = $year.$month.$day;
	$duetime = $time;
	if ($loop) {
		if ($loop == "day") {
			for($i=0;$i<$loopnum;$i++) {
				if ($i > 0) {
					unset($timestamp);
					unset($duedate);
					$timestamp = mktime(0,0,0,$month,$day,$year)+($i*60*60*24);
					$duedate = date("Ymd",$timestamp);
				}
				$sql = "INSERT INTO tblschedule VALUES ('','".$import."','".$rest."', ";
				$sql.= "'".$subject."','".$comment."','".$duedate."','".$duetime."','".$CurrentTime."')";
				$insert = mysql_query($sql,get_db_conn());
			}
		} else if ($loop == "week") {
			for($i=0;$i<$loopnum;$i++) {
				if ($i > 0) {
					unset($timestamp);
					unset($duedate);
					$timestamp = mktime(0,0,0,$month,$day,$year)+($i*7*60*60*24);
					$duedate = date("Ymd",$timestamp);
				}
				$sql = "INSERT INTO tblschedule VALUES ('','".$import."','".$rest."','".$subject."', ";
				$sql.= "'".$comment."','".$duedate."','".$duetime."','".$CurrentTime."')";
				$insert = mysql_query($sql,get_db_conn());
			}
		} else if ($loop == "month") {
			$tmpYear = $year;
			$tmpMonth = $month;
			$tmpDay = $day;
			for($i=0;$i<$loopnum;$i++) {
				if ($i > 0) {
					unset($timestamp);
					unset($duedate);
					$tmpNum = get_totaldays($tmpYear,$tmpMonth);
					$timestamp = mktime(0,0,0,$tmpMonth,$tmpDay,$tmpYear)+($tmpNum*60*60*24);
					$duedate = date("Ymd",$timestamp);
					$tmpYear = date("Y",$timestamp);
					$tmpMonth = date("m",$timestamp);
					$tmpDay = date("d",$timestamp);
				}
				$sql = "INSERT INTO tblschedule VALUES ('','".$import."','".$rest."','".$subject."', ";
				$sql.= "'".$comment."','".$duedate."','".$duetime."','".$CurrentTime."')";
				$insert = mysql_query($sql,get_db_conn());
			}
		}
		echo "<script>alert('������ �߰��Ǿ����ϴ�.');opener.location.reload();top.close();</script>";
		exit;
	} else {
		$sql = "INSERT INTO tblschedule VALUES ('','".$import."','".$rest."','".$subject."', ";
		$sql.= "'".$comment."','".$duedate."','".$duetime."','".$CurrentTime."')";
		$insert = mysql_query($sql,get_db_conn());

		if ($insert) {
			echo "<script>alert('������ �߰��Ǿ����ϴ�.');opener.location.reload();top.close();</script>";
			exit;
		} else {
			echo "<script>alert('���� �߰��� ������ �߻��Ͽ����ϴ�.');history.go(-1);</script>";
			exit;
		}
	}
}

if (!$year) $year = date("Y");
if (!$month) $month = date("m");
if (!$day) $day = date("d");

$month = $month*1;
$day = $day*1;

if ($month < 10) $month = "0".$month;
if ($day < 10) $day = "0".$day;

$inputY = $year;
$inputM = $month;

$totaldays = get_totaldays($inputY,$inputM);

if ($totaldays <= 0) {
	echo "<script>alert('��¥ ������ �߸��Ǿ����ϴ�.');top.close();</script>";
	exit;
}

function get_totaldays($year,$month) {
	$date = 1;
	while(checkdate($month,$date,$year)) {
		$date++;
	}

	$date--;

	return $date;
}

?>

<html>
<head>
<title>�����췯</title>
<meta http-equiv='content-type' content='text/html; charset=euc-kr'>
<LINK rel="stylesheet" type="text/css" href="style.css">
<SCRIPT LANGUAGE="JavaScript">
<!--
function PageResize() {
	var oWidth = 410;
	var oHeight = 310;

	window.resizeTo(oWidth,oHeight);
}

function form_submit(thisform) {

	if (thisform.subject.value=='') {
		alert('������ �Է��ϼ���');
		thisform.subject.focus();
		return false;
	}

	if (thisform.comment.value=='') {
		alert('������ �Է��ϼ���');
		thisform.comment.focus();
		return false;
	}

	return true;

}
//-->
</SCRIPT>
</head >

<body leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" bgcolor="#F6F6F6" onLoad="PageResize();">
<form action='<?=$_SERVER[PHP_SELF]?>' method='post' onSubmit="return form_submit(this)">
<input type='hidden' name='mode' value='add'>

<TABLE WIDTH="400" BORDER=0 CELLPADDING=0 CELLSPACING=0>
<TR>
	<TD>
	<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td><img src="images/calender_add_title.gif" border="0"></td>
		<td width="100%" background="images/member_mailallsend_imgbg.gif"></td>
	</tr>
	</table>
	</TD>
</TR>
<tr>
<TD style="padding:10pt;">
	<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
	<TR>
		<TD class=lineleft style="padding-right:15px; border-top-width:1pt; border-top-color:silver; border-top-style:solid;" noWrap align=right width="63" bgColor=#f0f0f0 height="32">��¥</TD>
		<TD class=line style="padding-left:5px; border-top-width:1pt; border-top-color:silver; border-top-style:solid;" width="290" height="32">
		<SELECT name=year size="1" class="select">
<?
		for($y=2000;$y<=date("Y")+5;$y++) {
			unset($select);
			if ($y == $year) $select = "selected";
			echo "<option value='".$y."' ".$select.">".$y." ��</option>";
		}
?>
		</SELECT>
		<SELECT name=month class="select">
<?
		for($y=1;$y<=12;$y++) {
			unset($select);
			unset($yn);
			$yn = $y;
			if ($y<10) $yn = "0".$y;
			if ($yn == $month) $select = "selected";
			echo "<option value='".$yn."' ".$select.">".$yn." ��</option>";
		}
?>
		</SELECT>
		<SELECT name=day class="select">
<?
		for($y=1;$y<=$totaldays;$y++) {
			unset($select);
			unset($yn);
			$yn = $y;
			if ($y<10) $yn = "0".$y;
			if ($yn == $day) $select = "selected";
			echo "<option value='".$yn."' ".$select.">".$yn." ��</option>";
		}
?>
		</SELECT>
		<SELECT name=time class="select">
		<option value='25'>�ð�������</option>
		<option value='6'>6 �� AM</option>
		<option value='7'>7 �� AM</option>
		<option value='8'>8 �� AM</option>
		<option value='9'>9 �� AM</option>
		<option value='10'>10 �� AM</option>
		<option value='11'>11 �� AM</option>
		<option value='12'>12 �� AM</option>
		<option value='13'>1 �� PM</option>
		<option value='14'>2 �� PM</option>
		<option value='15'>3 �� PM</option>
		<option value='16'>4 �� PM</option>
		<option value='17'>5 �� PM</option>
		<option value='18'>6 �� PM</option>
		<option value='19'>7 �� PM</option>
		<option value='20'>8 �� PM</option>
		<option value='21'>9 �� PM</option>
		<option value='22'>10 �� PM</option>
		</SELECT>			 
		</TD>
	</TR>
	<TR>
		<TD class=lineleft style="padding-right:15px; border-top-width:1pt; border-top-color:rgb(222,222,222); border-top-style:solid;" noWrap align=right width="63" bgColor=#f0f0f0 height="32">����</TD>
		<TD class=line style="padding-left:5px; border-top-width:1pt; border-top-color:rgb(222,222,222); border-top-style:solid;" width="290" height="32"><INPUT class="input" maxLength=12 size=15 name=subject style=width:100%> </TD>
	</TR>
	<TR>
		<TD class=lineleft style="padding-right:15px; border-top-width:1pt; border-top-color:rgb(222,222,222); border-top-style:solid;" noWrap align=right width="63" bgColor=#f0f0f0 height="32">����</TD>
		<TD class=line style="padding-top:2pt; padding-bottom:2pt; padding-left:5px; border-top-width:1pt; border-top-color:rgb(222,222,222); border-top-style:solid;" height="32"><textarea rows="3" class="textarea" style=width:100% name="comment"></textarea></TD>
	</TR>
	<TR>
		<TD class=lineleft style="padding-right:15px; border-top-width:1pt; border-bottom-width:1pt; border-top-color:rgb(222,222,222); border-bottom-color:silver; border-top-style:solid; border-bottom-style:solid;" noWrap align=center width="358" bgColor=#f0f0f0 height="32" colspan="2">
		<SELECT name=import class="select">
		<option selected value='N'>�Ϲ�����</option>
		<option value='Y'>�߿�����</option>
		</SELECT>			 
		<SELECT name=rest class="select">
		<option selected value='N'>�������</option>
		<option value='Y'>����������</option>
		</SELECT>
		<SELECT name=loop class="select">
		<option selected value=''>�ݺ�����</option>
		<option value='day'>�ϴ���</option>
		<option value='week'>�ִ���</option>
		<option value='month'>������</option>
		</SELECT>			 
		<SELECT name=loopnum class="select">
		<option value='1'>1 ��</option>
		<option value='2'>2 ��</option>
		<option value='3'>3 ��</option>
		<option value='4'>4 ��</option>
		<option value='5'>5 ��</option>
		<option value='6'>6 ��</option>
		<option value='7'>7 ��</option>
		<option value='8'>8 ��</option>
		<option value='9'>9 ��</option>
		<option value='10'>10 ��</option>
		</SELECT>
		</TD  style="border-bottom-width:1pt; border-bottom-color:silver; border-bottom-style:solid;">
	</TR >
	</TABLE>				
	</TD>
</tr>
<TR>
	<TD align=center><input type="image" src="images/btn_ok1.gif" width="36" height="18" border="0" vspace="0" border=0><a href="javascript:window.close()"><img src="images/btn_close.gif" width="36" height="18" border="0" vspace="0" border=0 hspace="2"></a></TD>
</TR>
</TABLE>

</form>
</body>
</html>