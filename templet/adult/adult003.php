<?
$curyear = date("Y")-20;
$curdate = $curyear.date("md");
$curdatetemp = substr($curdate,0,4)."/".substr($curdate,4,2)."/".substr($curdate,6,2);
$curdate = substr($curdate,2,6);

if($ssl_type=="Y" && $num=strpos(" ".$ssl_page,"ADULT=")) {
	$is_ssl=substr($ssl_page,$num+5,1);
}

?>

<html>
<head>
<META http-equiv="CONTENT-TYPE" content="text/html; charset=EUC-KR">

<title>�������� - <?=$shoptitle?></title>
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
function chkCtyNo(obj) {
	if (obj.length == 14) {
		var calStr1 = "2345670892345", biVal = 0, tmpCal, restCal;
		
		for (i=0; i <= 12; i++) {
			if (obj.substring(i,i+1) == "-")
				tmpCal = 1
			else
				biVal = biVal + (parseFloat(obj.substring(i,i+1)) * parseFloat(calStr1.substring(i,i+1)));
		}

		restCal = 11 - (biVal % 11);

		if (restCal == 11) {
			restCal = 1;
		}

		if (restCal == 10) {
			restCal = 0;
		}

		if (restCal == parseFloat(obj.substring(13,14))) {
			return true;
		} else {
			return false;
		}
	}
}

function strnumkeyup2(field) {
	if (!isNumber(field.value)) {
		alert("���ڸ� �Է��ϼ���.");
		field.value=strLenCnt(field.value,field.value.length - 1);
		field.focus();
		return;
	}
	if (field.name == "adult_no1") {
		if (field.value.length == 6) {
			form1.adult_no2.focus();
		}
	}
}

function CheckForm() {
	var name = document.form1.name;
	var adult_no1 = document.form1.adult_no1;
	var adult_no2 = document.form1.adult_no2;
	if (name.value == "") {
		alert("�̸��� �Է��ϼ���.");
		name.focus();
		return;
	}

	if (adult_no1.value == "") {
		alert("�ֹε�Ϲ�ȣ�� �Է��ϼ���.");
		adult_no1.focus();
		return;
	}
	if (adult_no2.value == "") {
		alert("�ֹε�Ϲ�ȣ�� �Է��ϼ���.");
		adult_no2.focus();
		return;
	}

	var bb;
	bb = chkCtyNo(adult_no1.value+"-"+adult_no2.value);
	
	if (!bb) {
		alert("�߸��� �ֹε�Ϲ�ȣ �Դϴ�.\n\n�ٽ� �Է��ϼ���");
		adult_no1.focus();
		return;
	}

	if (parseInt(adult_no1)><?=$curdate?>) {
		alert("<?=$curdatetemp?> ������ ����Ͻ� �и� ���尡���մϴ�.");
		document.form1.adult_no1.focus();
		return;
	}
	<?if($ssl_type=="Y" && strlen($ssl_domain)>0 && strlen($ssl_port)>0 && $is_ssl=="Y") {?>
		document.form1.action='https://<?=$ssl_domain?><?=($ssl_port!="443"?":".$ssl_port:"")?>/<?=RootPath.SecureDir?>adultcheck.php';
	<?}?>
	document.form1.submit();
}
//-->
</SCRIPT>
</head>
<body bgcolor="white" text="black" link="blue" vlink="purple" alink="red" leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" background="<?=$Dir.AdultDir?>images/adultintro_skin3_bg.gif">
<table cellpadding="0" cellspacing="0" width="100%">
<form name=form1 method=post action="<?=$Dir?>index.php">
<?if($ssl_type=="Y" && strlen($ssl_domain)>0 && strlen($ssl_port)>0 && $is_ssl=="Y") {?>
<input type=hidden name=shopurl value="<?=getenv("HTTP_HOST")?>">
<?}?>
<tr>
	<td background="<?=$Dir.AdultDir?>images/adultintro_skin3_top.gif">						
	<table align="center" cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td><IMG SRC="<?=$Dir.AdultDir?>images/adultintro_skin3_top.gif" border="0"></td>
		<td><IMG SRC="<?=$Dir.AdultDir?>images/adultintro_skin3_top1.gif" border="0"></td>
		<td width="100%" valign="top">
		<table cellpadding="0" cellspacing="0" width="100%">
		<col width="422"></col>
		<col width=></col>
		<tr>
			<td colspan="2"><IMG SRC="<?=$Dir.AdultDir?>images/adultintro_skin3_top2.gif" border="0"></td>
		</tr>
		<tr>
			<td valign="top">
			<table cellpadding="0" cellspacing="0">
			<tr>
				<td><IMG SRC="<?=$Dir.AdultDir?>images/adultintro_skin3_text1.gif" border="0"></td>
				<td><input type=text name=name tabindex="1" style="width:100px" class="input"></td>
			</tr>
			<tr>
				<td><IMG SRC="<?=$Dir.AdultDir?>images/adultintro_skin3_text2.gif" border="0"></td>
				<td>
				<table cellpadding="0" cellspacing="0">
				<tr>
					<td><input type=text name="adult_no1" maxlength="6" onKeyUp="return strnumkeyup2(this);" tabindex="2" class="input" style="width:100px"></td>
					<td><font color="FFFFFF"><b>-</b></font></td>
					<td><input type=password name="adult_no2" maxlength="7" onKeyUp="return strnumkeyup2(this);" tabindex="3" class="input"  style="width:100px"></td>
				</tr>
				</table>
				</td>
			</tr>
			</table>
			</td>
			<td><IMG SRC="<?=$Dir.AdultDir?>images/adultintro_skin3_top4.gif" border="0"></td>
		</tr>
		</table>
		</td>
	</tr>
	<tr>
		<td background="<?=$Dir.AdultDir?>images/adultintro_skin3_top3.gif"></td>
		<td background="<?=$Dir.AdultDir?>images/adultintro_skin3_top3.gif"></td>
		<td valign="top" background="<?=$Dir.AdultDir?>images/adultintro_skin3_top3.gif">
		<table cellpadding="0" cellspacing="0" width="100%">
		<col width="422"></col>
		<col width=></col>
		<tr>
			<td align="center"><a href="javascript:CheckForm();"><IMG SRC="<?=$Dir.AdultDir?>images/adultintro_skin3_btn1.gif" ALT="���θ� �̿��ϱ�" border="0"></a><a href="javascript:history.go(-1);"><IMG SRC="<?=$Dir.AdultDir?>images/adultintro_skin3_btn2.gif" ALT="19�� �̸� ������" hspace="7" border="0"></a></td>
			<td><img src="<?=$Dir.AdultDir?>images/adultintro_skin3_top5.gif" border="0"></td>
		</tr>
		</table>
		</td>
	</tr>
	</table>
	</td>
</tr>
<tr>
	<td align="center" valign="top" background="<?=$Dir.AdultDir?>images/adultintro_skin3_textbg1.gif">
	<table cellpadding="0" cellspacing="0">
	<tr>
		<td height="15"></td>
	</tr>
	<tr>
		<td align="center" style=";font-size:25px"><B><?=$url?></B></td>
	</tr>
	<tr>
		<td height="15"></td>
	</tr>
	<tr>
		<td>��&nbsp;http://<?=$url?> ���νǸ������� ���ŷ� �ִ� ����� �Ǹ� ��ȸ���񽺸� �̿��Ͽ�<br>
		&nbsp;&nbsp;&nbsp; �Ǹ������� �Ͽ��� ��Ȯ�� �̸��� �ֹε�Ϲ�ȣ�� �Է��Ͽ��� �մϴ�.<br>
		��&nbsp;<? echo $shopname ?>(<? echo $info_tel ?>),<a href="mailto:<? echo $info_email ?>" style="background-color:#fe9602;color:#ffffff"><? echo $info_email ?></a><br>
		��&nbsp;����ڹ�ȣ:<? echo $companynum ?>&nbsp;&nbsp;��ǥ��:<? echo $companyowner ?>
		<? if (strlen($companyaddr)>0) { ?>
		&nbsp;&nbsp;&nbsp;�ּ�:<? echo $companyaddr ?>
		<? } ?></td>
	</tr>
	</table>
	</td>
</tr>
</form>
</table>
</body>
</html>