<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");

Header("Pragma: no-cache");

$ordercode=$_POST["ordercode"];
$sendtype=$_POST["sendtype"];
$return_host=urldecode($_POST["return_host"]);
$return_script=urldecode($_POST["return_script"]);
$return_data=urldecode($_POST["return_data"]);

$sql = "SELECT * FROM tblpordercode WHERE ordercode='".$ordercode."' ";
$result=mysql_query($sql,get_db_conn());
if($row=mysql_fetch_object($result)) {
	$paymethod=$row->paymethod;
	if(!preg_match("/^(Q|P)$/", $paymethod)) {
		echo "<html><head><title></title></head><body onload=\"alert('�ش� �Ÿź�ȣ ���������� �������� �ʽ��ϴ�.');window.close();\"></body></html>";exit;
	}	
} else {
	echo "<html><head><title></title></head><body onload=\"alert('�ش� �Ÿź�ȣ ���������� �������� �ʽ��ϴ�.');window.close();\"></body></html>";exit;
}
mysql_free_result($result);

$tblname="";
if(preg_match("/^(P)$/", $paymethod)) {
	$tblname="tblpcardlog";
} else if(preg_match("/^(Q)$/", $paymethod)) {
	$tblname="tblpvirtuallog";
}

$sql = "SELECT * FROM ".$tblname." WHERE ordercode='".$ordercode."' ";
$result=mysql_query($sql,get_db_conn());
if($row=mysql_fetch_object($result)) {
	$_pg=$row;
} else {
	echo "<html><head><title></title></head><body onload=\"alert('�ش� �Ÿź�ȣ ���������� �������� �ʽ��ϴ�.\\n\\n��ڿ��� �����Ͻñ� �ٶ��ϴ�.');window.close();\"></body></html>";exit;
}
mysql_free_result($result);

if($_pg->ok!="Y") {
	echo "<html><head><title></title></head><body onload=\"alert('�ش� �Ÿź�ȣ ���������� ���ε��� �ʾҽ��ϴ�.\\n\\n��ڿ��� �����Ͻñ� �ٶ��ϴ�.');window.close();\"></body></html>";exit;
}

if($_pg->status!="S" && ($_pg->pgtype!="D" || $sendtype!="CNCL")) {
	echo "<html><head><title></title></head><body onload=\"alert('�ش� �Ÿź�ȣ �������� ����� �ȵǾ����ϴ�.\\n\\n��ۿϷ� �� ����Ȯ���� �Ͻ� �� �ֽ��ϴ�.');window.close();\"></body></html>";exit;
}

if($_pg->pgtype=="A") {
	$sitecd=urldecode($_POST["sitecd"]);
	$sitekey=urldecode($_POST["sitekey"]);
} else if($_pg->pgtype=="C") {
	$sitecd=urldecode($_POST["sitecd"]);
}

$mode=$_POST["mode"];	//update
$rescode=$_POST["rescode"];	//Y:����Ȯ��, C:�������
if($mode=="update") {
	if($rescode=="Y") {			//����Ȯ��
		//pg DB������Ʈ �� ������������ �����Ѵ�.
		if($_pg->pgtype=="A") {	//KCP ���Ű��� include
			include "A/escrow_ok.inc.php";
		} else if($_pg->pgtype=="C") {	//AllTheGate ���Ű��� include
			include "C/escrow_ok.inc.php";
		}
		echo "<form name=form1 action=\"http://$return_host$return_script\" method=post>\n";
		echo "<input type=hidden name=rescode value=\"Y\">\n";
		$text = explode("&",$return_data);
		for ($i=0;$i<sizeOf($text);$i++) {
			$textvalue = explode("=",$text[$i]);
			echo "<input type=hidden name=".$textvalue[0]." value=\"".$textvalue[1]."\">\n";
		}
		echo "</form>\n";
		echo "<script>\n";
		echo "if(opener) {\n";
		echo "	if(opener.name==\"orderpop\") {\n";
		echo "		document.form1.target=opener.name;\n";
		echo "		document.form1.submit();\n";
		echo "		window.close();\n";
		echo "	} else {\n";
		echo "		document.form1.submit();\n";
		echo "	}\n";
		echo "} else {\n";
		echo "	document.form1.submit();\n";
		echo "}\n";
		echo "</script>";
		exit;
	} else if($rescode=="C") {	//�������
		if($paymethod=="Q") {
			if($_pg->pgtype=="C") {	//AllTheGate ���Ű��� include
				include "C/escrow_ok.inc.php";
			} else {
				//ȯ�Ұ��������� ������Ʈ �� ������������ �����Ѵ�.
				$refund_account=$_POST["refund_account"];
				$refund_name=$_POST["refund_name"];
				$refund_bank_code=$_POST["refund_bank_code"];
				$sql = "UPDATE tblpvirtuallog SET ";
				$sql.= "refund_account	= '".$refund_account."', ";
				$sql.= "refund_name		= '".$refund_name."', ";
				$sql.= "refund_bank_code= '".$refund_bank_code."' ";
				mysql_query($sql,get_db_conn());
			}
		}
		echo "<form name=form1 action=\"http://$return_host$return_script\" method=post>\n";
		echo "<input type=hidden name=rescode value=\"C\">\n";
		$text = explode("&",$return_data);
		for ($i=0;$i<sizeOf($text);$i++) {
			$textvalue = explode("=",$text[$i]);
			echo "<input type=hidden name=".$textvalue[0]." value=\"".$textvalue[1]."\">\n";
		}
		echo "</form>\n";
		echo "<script>\n";
		echo "if(opener) {\n";
		echo "	if(opener.name==\"orderpop\") {\n";
		echo "		document.form1.target=opener.name;\n";
		echo "		document.form1.submit();\n";
		echo "		window.close();\n";
		echo "	} else {\n";
		echo "		document.form1.submit();\n";
		echo "	}\n";
		echo "} else {\n";
		echo "	document.form1.submit();\n";
		echo "}\n";
		echo "</script>";
		exit;
	}
}

?>

<html>
<head>
<title>�Ÿź�ȣ ���Ű���/���</title>
<meta http-equiv="CONTENT-TYPE" content="text/html;charset=EUC-KR">
<script type="text/javascript" src="<?=$Dir?>lib/lib.js.php"></script>
<?if($paymethod=="Q" && $_pg->pgtype=="D"){?>
<SCRIPT language=javascript src="http://www.hanaescrow.com/js/cpconfirm.js"></SCRIPT>
<?}?>
<style>
td {font-family:Tahoma;color:666666;font-size:9pt;}

tr {font-family:Tahoma;color:666666;font-size:9pt;}
BODY,TD,SELECT,DIV,form,TEXTAREA,center,option,pre,blockquote {font-family:Tahoma;color:000000;font-size:9pt;}

A:link    {color:333333;text-decoration:none;}

A:visited {color:333333;text-decoration:none;}

A:active  {color:333333;text-decoration:none;}

A:hover  {color:#CC0000;text-decoration:none;}
</style>
<SCRIPT LANGUAGE="JavaScript">
<!--
function PageResize() {
	var oWidth = document.all.table_body.clientWidth + 10;
	var oHeight = document.all.table_body.clientHeight + 80;

	window.resizeTo(oWidth,oHeight);
}

function set_escrow(temp) {
	if(temp=="Y") {
		document.all["layerC"].style.display="none";
		document.all["layerY"].style.display="";
	} else if(temp=="C") {
		document.all["layerY"].style.display="none";
		document.all["layerC"].style.display="";
	}
	PageResize();
}

function UserDefine()
{
	if(status_cd == "SUCCESS") {
 		document.form1.submit();
	} else if (status_cd == "CANCEL") {
		alert("����Ȯ��/������ ��ҵǾ����ϴ�.");
	} else {
		alert(status_cd);
	}
}

function CheckForm(temp) {
	if(temp=="Y") {
<?
	if($paymethod=="Q"){
		if($_pg->pgtype=="C"){
?>
		if(document.form1.id_no11.value.length==0) {
			alert("�ֹι�ȣ ù°�ڸ��� �Է��� �ּ���.");
			document.form1.id_no11.focus();
			return;
		}
		if(document.form1.id_no22.value.length==0) {
			alert("�ֹι�ȣ ��°�ڸ��� �Է��� �ּ���.");
			document.form1.id_no22.focus();
			return;
		}

		document.form1.id_no1.value=document.form1.id_no11.value;
		document.form1.id_no2.value=document.form1.id_no22.value;
<?
		} else if($_pg->pgtype=="D"){
?>
		document.cporder.ctype.value="CFRM";
		document.form1.mode.value="update";
		document.form1.rescode.value=temp;
		approve();
		return;
<?
		}
	}
?>
		if(!confirm("���Ű����� �Ͻðڽ��ϱ�?")) return;
	} else if(temp=="C") {
<?
	if($paymethod=="Q"){
		if($_pg->pgtype=="C"){
?>
		if(document.form1.id_no111.value.length==0) {
			alert("�ֹι�ȣ ù°�ڸ��� �Է��� �ּ���.");
			document.form1.id_no111.focus();
			return;
		}
		if(document.form1.id_no222.value.length==0) {
			alert("�ֹι�ȣ ��°�ڸ��� �Է��� �ּ���.");
			document.form1.id_no222.focus();
			return;
		}

		document.form1.id_no1.value=document.form1.id_no111.value;
		document.form1.id_no2.value=document.form1.id_no222.value;
<? 
		} else if($_pg->pgtype=="D"){
?>
		document.cporder.ctype.value="CNCL";
		document.form1.mode.value="update";
		document.form1.rescode.value=temp;
		approve();
		return;
<?
		} else {
?>
		if(document.form1.refund_account.value.length==0) {
			alert("ȯ�Ҽ�����¹�ȣ�� �Է��ϼ���.");
			document.form1.refund_account.focus();
			return;
		}
		if(document.form1.refund_name.value.length==0) {
			alert("ȯ�Ҽ�������ָ��� �Է��ϼ���.");
			document.form1.refund_name.focus();
			return;
		}
		if(document.form1.refund_bank_code.value.length==0) {
			alert("ȯ�Ҽ��������� �����ϼ���.");
			document.form1.refund_bank_code.focus();
			return;
		}
<?
		}
	}
?>
		if(!confirm("���Ű����� �Ͻðڽ��ϱ�?\n\n���Ű����� ��ڰ� Ȯ�� �� ���� ���ó�� �˴ϴ�.")) return;
	}
	document.form1.mode.value="update";
	document.form1.rescode.value=temp;
	document.form1.submit();
}
//-->
</SCRIPT>
</head>

<body topmargin=0 leftmargin=0 rightmargin=0 marginheight=0 marginwidth=0 onLoad="PageResize();">
<table border="0" cellpadding="0" cellspacing="0" width="350" style="table-layout:fixed;" id="table_body">
<form name=form1 method=post action="<?=$_SERVER[PHP_SELF]?>">
<input type=hidden name=mode>
<input type=hidden name=rescode>
<input type=hidden name=ordercode value="<?=$ordercode?>">
<input type=hidden name=return_host value="<?=urlencode($return_host)?>">
<input type=hidden name=return_script value="<?=urlencode($return_script)?>">
<input type=hidden name=return_data value="<?=urlencode($return_data)?>">
<?if($_pg->pgtype=="A"){?>
<input type=hidden name=sitecd value="<?=urlencode($sitecd)?>">
<input type=hidden name=sitekey value="<?=urlencode($sitekey)?>">
<?} else if($_pg->pgtype=="C"){?>
<input type=hidden name=sitecd value="<?=urlencode($sitecd)?>">
<input type=hidden name=id_no1 value="">
<input type=hidden name=id_no2 value="">
<?} else if($_pg->pgtype=="D"){?>
<input type=hidden name=sendtype value="<?=$sendtype?>">
<?}?>
<tr>
	<td><img src="<?=$Dir?>images/okescrow_title.gif" border="0"></td>
</tr>
<TR>
	<TD style="padding:3pt;">
	<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td align="right">
<?if($_pg->pgtype=="D" && $sendtype=="CNCL"){?>
		<A HREF="javascript:set_escrow('C')"><font style="font-size:8pt; letter-spacing:-0.5pt;"><b>��������ϱ�</b></font></a></td>
<?} else {?>
		<A HREF="javascript:set_escrow('Y')"><font style="font-size:8pt; letter-spacing:-0.5pt;"><b>���Ű����ϱ�</b></font></a> | <A HREF="javascript:set_escrow('C')"><font style="font-size:8pt; letter-spacing:-0.5pt;"><b>��������ϱ�</b></font></a></td>
<?}?>
	</tr>
	<tr>
		<td>
		<?if($_pg->pgtype=="C"){?>
		<div id="layerY" style="display:none">
		<TABLE cellSpacing="0" cellPadding="0" width="100%" border="0">
		<col width="120"></col>
		<col></col>
		<TR>
			<TD height="2" bgcolor="#000000" colspan="2"></TD>
		</TR>
		<TR>
			<TD bgcolor="#F8F8F8" style="padding:5pt;padding-left:10pt;letter-spacing:-0.5pt;"><img src="<?=$Dir?>images/icon_point2.gif" border="0"><b>�� �� �� ȣ</b></TD>
			<TD class="td_con1"><input type=text name="id_no11" value="" size="6" maxlength="6" class="input" style="width:60;">-<input type=password name="id_no22" value="" size="7" maxlength="7" class="input" style="width:90;"></TD>
		</TR>
		<TR>
			<TD height="1" colspan="2" bgcolor="#EDEDED"></TD>
		</TR>
		<TR>
			<TD height="117" colspan="2" align="center"><font style="font-size:9pt; letter-spacing:-0.5pt;">�ش� �Ÿź�ȣ �����ǿ� ���ؼ� <font color="#FF3300"><b>���Ű���</b></font>�� �Ͻðڽ��ϱ�?</font></TD>
		</TR>
		<TR>
			<TD height="1" colspan="2" bgcolor="#000000"></TD>
		</TR>
		<tr>
			<td align="center" colspan="2"><img src="<?=$Dir?>images/okescrow_b1.gif" border="0" vspace="5" onclick="CheckForm('Y');" style="cursor:hand"></td>
		</tr>
		</TABLE>
		</div>
		<div id="layerC" style="display:none">
		<TABLE cellSpacing="0" cellPadding="0" width="100%" border="0">
		<col width="120"></col>
		<col></col>
		<TR>
			<TD height="2" bgcolor="#000000" colspan="2"></TD>
		</TR>
		<TR>
			<TD bgcolor="#F8F8F8" style="padding:5pt;padding-left:10pt;letter-spacing:-0.5pt;"><img src="<?=$Dir?>images/icon_point2.gif" border="0"><b>�� �� �� ȣ</b></TD>
			<TD class="td_con1"><input type=text name="id_no111" value="" size="6" maxlength="6" class="input" style="width:60;">-<input type=password name="id_no222" value="" size="7" maxlength="7" class="input" style="width:90;"></TD>
		</TR>
		<TR>
			<TD height="1" colspan="2" bgcolor="#EDEDED"></TD>
		</TR>
		<TR>
			<TD height="117" align="center" colspan="2"><font style="font-size:9pt; letter-spacing:-0.5pt;">�ش� �Ÿź�ȣ �����ǿ� ���ؼ� <font color="#FF3300"><b>���Ű���</b></font>�� �Ͻðڽ��ϱ�?</font></TD>
		</TR>
		<TR>
			<TD height="1" bgcolor="#000000" colspan="2"></TD>
		</TR>
		<tr>
			<td align="center" colspan="2"><img src="<?=$Dir?>images/okescrow_b2.gif" border="0" vspace="5" onclick="CheckForm('C');" style="cursor:hand"></td>
		</tr>
		</TABLE>
		</div>
		<?} else if($_pg->pgtype=="D"){?>
		<div id="layerY" style="display:none">
		<TABLE cellSpacing="0" cellPadding="0" width="100%" border="0">
		<col width="120"></col>
		<col></col>
		<TR>
			<TD height="2" bgcolor="#000000" colspan="2"></TD>
		</TR>
		<TR>
			<TD bgcolor="#F8F8F8" style="padding:5pt;padding-left:10pt;letter-spacing:-0.5pt;"><img src="<?=$Dir?>images/icon_point2.gif" border="0"><b>�� �� �� ȣ</b></TD>
			<TD class="td_con1"><?=$row->ordercode?></TD>
		</TR>
		<TR>
			<TD height="1" colspan="2" bgcolor="#EDEDED"></TD>
		</TR>
		<TR>
			<TD height="117" colspan="2" align="center"><font style="font-size:9pt; letter-spacing:-0.5pt;">�ش� �Ÿź�ȣ �����ǿ� ���ؼ� <font color="#FF3300"><b>���Ű���</b></font>�� �Ͻðڽ��ϱ�?</font></TD>
		</TR>
		<TR>
			<TD height="1" colspan="2" bgcolor="#000000"></TD>
		</TR>
		<tr>
			<td align="center" colspan="2"><img src="<?=$Dir?>images/okescrow_b1.gif" border="0" vspace="5" onclick="CheckForm('Y');" style="cursor:hand"></td>
		</tr>
		</TABLE>
		</div>
		<div id="layerC" style="display:none">
		<TABLE cellSpacing="0" cellPadding="0" width="100%" border="0">
		<col width="120"></col>
		<col></col>
		<TR>
			<TD height="2" bgcolor="#000000" colspan="2"></TD>
		</TR>
		<TR>
			<TD bgcolor="#F8F8F8" style="padding:5pt;padding-left:10pt;letter-spacing:-0.5pt;"><img src="<?=$Dir?>images/icon_point2.gif" border="0"><b>�� �� �� ȣ</b></TD>
			<TD class="td_con1"><?=$row->ordercode?></TD>
		</TR>
		<TR>
			<TD height="1" colspan="2" bgcolor="#EDEDED"></TD>
		</TR>
		<TR>
			<TD height="117" align="center" colspan="2"><font style="font-size:9pt; letter-spacing:-0.5pt;">�ش� �Ÿź�ȣ �����ǿ� ���ؼ� <font color="#FF3300"><b>���Ű���</b></font>�� �Ͻðڽ��ϱ�?</font></TD>
		</TR>
		<TR>
			<TD height="1" bgcolor="#000000" colspan="2"></TD>
		</TR>
		<tr>
			<td align="center" colspan="2"><img src="<?=$Dir?>images/okescrow_b2.gif" border="0" vspace="5" onclick="CheckForm('C');" style="cursor:hand"></td>
		</tr>
		</TABLE>
		</div>
		<?} else {?>
		<div id="layerY" style="display:none">
		<TABLE cellSpacing="0" cellPadding="0" width="100%" border="0">
		<TR>
			<TD height="2" bgcolor="#000000"></TD>
		</TR>
		<TR>
			<TD height="117" align="center"><font style="font-size:9pt; letter-spacing:-0.5pt;">�ش� �Ÿź�ȣ �����ǿ� ���ؼ� <font color="#FF3300"><b>���Ű���</b></font>�� �Ͻðڽ��ϱ�?</font></TD>
		</TR>
		<TR>
			<TD height="1" bgcolor="#000000"></TD>
		</TR>
		<tr>
			<td align="center"><img src="<?=$Dir?>images/okescrow_b1.gif" border="0" vspace="5" onclick="CheckForm('Y');" style="cursor:hand"></td>
		</tr>
		</TABLE>
		</div>
		<div id="layerC" style="display:none">
		<TABLE cellSpacing="0" cellPadding="0" width="100%" border="0">
		<col width="120"></col>
		<col></col>
		<TR>
			<TD height="2" bgcolor="#000000" colspan="2"></TD>
		</TR>
		<?if($paymethod=="Q"){?>
		<TR>
			<TD bgcolor="#F8F8F8" style="padding:5pt;padding-left:10pt;letter-spacing:-0.5pt;"><img src="<?=$Dir?>images/icon_point2.gif" border="0"><b>ȯ�Ҽ�����¹�ȣ</b></TD>
			<TD class="td_con1"><input type=text name="refund_account" value="<?=$refund_account?>" size="15" class="input" style="width:100%;"></TD>
		</TR>
		<TR>
			<TD height="1" colspan="2" bgcolor="#EDEDED"></TD>
		</TR>
		<TR>
			<TD bgcolor="#F8F8F8" style="padding:5pt;padding-left:10pt;letter-spacing:-0.5pt;"><img src="<?=$Dir?>images/icon_point2.gif" border="0"><b>ȯ�Ҽ�������ָ�</b></TD>
			<TD class="td_con1"><input type=text name="refund_name" value="<?=$refund_name?>" size="15" class="input" style="width:100%;"></TD>
		</TR>
		<TR>
			<TD height="1" colspan="2" bgcolor="#EDEDED"></TD>
		</TR>
		<TR>
			<TD bgcolor="#F8F8F8" style="padding:5pt;padding-left:10pt;letter-spacing:-0.5pt;"><img src="<?=$Dir?>images/icon_point2.gif" border="0"><b>ȯ�Ҽ������༱��</b></TD>
			<TD class="td_con1"><select name="refund_bank_code" class="select">
				<option value="">����</option>
				<option value="39"<?if($refund_bank_code=="39")echo" selected";?>>�泲����</option>
				<option value="03"<?if($refund_bank_code=="03")echo" selected";?>>�������</option>
				<option value="32"<?if($refund_bank_code=="32")echo" selected";?>>�λ�����</option>
				<option value="07"<?if($refund_bank_code=="07")echo" selected";?>>�����߾�ȸ</option>
				<option value="48"<?if($refund_bank_code=="48")echo" selected";?>>����</option>
				<option value="71"<?if($refund_bank_code=="71")echo" selected";?>>��ü��</option>
				<option value="23"<?if($refund_bank_code=="23")echo" selected";?>>��������</option>
				<option value="06"<?if($refund_bank_code=="06")echo" selected";?>>��������</option>
				<option value="81"<?if($refund_bank_code=="81")echo" selected";?>>�ϳ�����</option>
				<option value="34"<?if($refund_bank_code=="34")echo" selected";?>>��������</option>
				<option value="11"<?if($refund_bank_code=="11")echo" selected";?>>�����߾�ȸ</option>
				<option value="02"<?if($refund_bank_code=="02")echo" selected";?>>�������</option>
				<option value="53"<?if($refund_bank_code=="53")echo" selected";?>>��Ƽ����</option>
				<option value="05"<?if($refund_bank_code=="05")echo" selected";?>>��ȯ����</option>
				<option value="09"<?if($refund_bank_code=="09")echo" selected";?>>���ſ�</option>
				<option value="35"<?if($refund_bank_code=="35")echo" selected";?>>��������</option>
				<option value="16"<?if($refund_bank_code=="16")echo" selected";?>>�����߾�ȸ</option>
				<option value="27"<?if($refund_bank_code=="27")echo" selected";?>>�ѹ�����</option>
				<option value="04"<?if($refund_bank_code=="04")echo" selected";?>>��������</option>
				<option value="31"<?if($refund_bank_code=="31")echo" selected";?>>�뱸����</option>
				<option value="25"<?if($refund_bank_code=="25")echo" selected";?>>��������</option>
				<option value="26"<?if($refund_bank_code=="26")echo" selected";?>>��������</option>
				<option value="20"<?if($refund_bank_code=="20")echo" selected";?>>�츮����</option>
				<option value="37"<?if($refund_bank_code=="37")echo" selected";?>>��������</option>
				<option value="21"<?if($refund_bank_code=="21")echo" selected";?>>��������</option>
				<option value="83"<?if($refund_bank_code=="83")echo" selected";?>>��ȭ����</option>
			</select></TD>
		</TR>
		<TR>
			<TD height="1" colspan="2" bgcolor="#EDEDED"></TD>
		</TR>
		<TR>
			<TD align="center" colspan="2" style="padding-top:4pt;padding-bottom:4pt;"><font style="font-size:9pt; letter-spacing:-0.5pt;">�ش� �Ÿź�ȣ �����ǿ� ���ؼ� <font color="#FF3300"><b>���Ű���</b></font>�� �Ͻðڽ��ϱ�?</font></TD>
		</TR>
		<?} else {?>
		<TR>
			<TD height="117" align="center" colspan="2" style="padding-top:4pt;padding-bottom:4pt;"><font style="font-size:9pt; letter-spacing:-0.5pt;">�ش� �Ÿź�ȣ �����ǿ� ���ؼ� <font color="#FF3300"><b>���Ű���</b></font>�� �Ͻðڽ��ϱ�?</font></TD>
		</TR>
		<?}?>
		<TR>
			<TD height="1" bgcolor="#000000" colspan="2"></TD>
		</TR>
		<tr>
			<td align="center" colspan="2"><img src="<?=$Dir?>images/okescrow_b2.gif" border="0" vspace="5" onclick="CheckForm('C');" style="cursor:hand"></td>
		</tr>
		</TABLE>
		</div>
		<?}?>
		</td>
	</tr>
	</table>
	</td>
</tr>
</form>
<?if($_pg->pgtype=="D"){?>
<form name=cporder>
<input type=hidden name=rfnd_amt value="">
<input type=hidden name=ctype value="">
<input type=hidden name=tid value="<?=$row->trans_code?>">
</form>
<?}?>
</table>

<?=$onload?>

</body>
</html>