<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
INCLUDE ("access.php");

####################### ������ ���ٱ��� check ###############
$PageCode = "go-4";
$MenuCode = "gong";
if (!$_usersession->isAllowedTask($PageCode)) {
	INCLUDE ("AccessDeny.inc.php");
	exit;
}
#########################################################

$sql = "SELECT id, authkey, return_tel FROM tblsmsinfo ";
$result=mysql_query($sql,get_db_conn());
if($row=mysql_fetch_object($result)){
	$return_tel = $row->return_tel;
	$sms_id=$row->id;
	$sms_authkey=$row->authkey;
}
mysql_free_result($result);

$isdisabled="1";
$maxcount=0;
if(strlen($sms_id)==0 || strlen($sms_authkey)==0) {
	$onload="<script>alert('SMS ȸ������ �� ���� �� SMS �⺻ȯ�� ��������\\n\\nSMS ���̵� �� ����Ű�� �Է��Ͻñ� �ٶ��ϴ�.');</script>";
	$isdisabled="0";
} else {
	$smscountdata=getSmscount($sms_id, $sms_authkey);
	if(substr($smscountdata,0,2)=="OK") {
		$maxcount=substr($smscountdata,3);
	} else if(substr($smscountdata,0,2)=="NO") {
		$onload="<script>alert('SMS ȸ�� ���̵� �������� �ʽ��ϴ�.\\n\\nSMS �⺻ȯ�� �������� SMS ���̵� �� ����Ű�� ��Ȯ�� �Է��Ͻñ� �ٶ��ϴ�.');</script>";
		$isdisabled="2";
	} else if(substr($smscountdata,0,2)=="AK") {
		$onload="<script>alert('SMS ȸ�� ����Ű�� ��ġ���� �ʽ��ϴ�.\\n\\nSMS �⺻ȯ�� �������� ����Ű�� ��Ȯ�� �Է��Ͻñ� �ٶ��ϴ�.');</script>";
		$isdisabled="3";
	} else {
		$onload="<script>alert('SMS ������ ����� �Ұ����մϴ�.\\n\\n��� �� �̿��Ͻñ� �ٶ��ϴ�.');</script>";
		$isdisabled="4";
	}
}


$type=$_POST["type"];
$msg=$_POST["msg"];
$from_tel=$_POST["from_tel"];

$cnt=0;
$sql = "SELECT mobile FROM tblsocial_mailing  WHERE  state ='Y' AND mobile!='' ";
$result = mysql_query($sql,get_db_conn());
while($row = mysql_fetch_object($result)) {
	$row->mobile=ereg_replace(",","",$row->mobile);
	$row->mobile=ereg_replace("-","",$row->mobile);
	if(strlen($row->mobile)<10 || strlen($row->mobile)>11){
	} else {
		$tel_list.=",".$row->mobile;
		$cnt++;
	}
}
mysql_free_result($result);
$totellist=substr($tel_list,1);

if($type=="up") {
	########################### TEST ���θ� Ȯ�� ##########################
	DemoShopCheck("������������� �׽�Ʈ�� �Ұ��� �մϴ�.", $_SERVER[PHP_SELF]);
	#######################################################################

	$fromtel=$from_tel;

	$etcmsg = "������������ �߼�";
	if ($cnt <= $maxcount && $cnt>0) {
		$temp=SendSMS($sms_id, $sms_authkey, $totellist, "", $fromtel, 0, $msg, $etcmsg);
		$resmsg=explode("[SMS]",$temp);
		$onload = "<script>alert('".$resmsg[1]."');</script>";
	} else if ($cnt==0) {
		$onload="<script>alert('SMS�� �߼��� ȸ���� �����ϴ�.');</script>";
	} else {
		$onload="<script>alert('SMS �Ӵϰ� �����մϴ�. ������ �̿��ϼ���.');</script>";
	}

	$type="";$msg="";$from_tel="";
}

if(strlen($pcode)>0){
	$sql = "SELECT * FROM tblproduct A, tblproduct_social B ";
	$sql .="WHERE A.productcode=B.pcode ";
	$sql .="AND productcode = '".$pcode."' ";
	$result=mysql_query($sql,get_db_conn());
	if($row=@mysql_fetch_array($result)){
		$productname= strip_tags($row["productname"]);
		$discount = ($row["consumerprice"]>0)? 100-intval($row["sellprice"]/$row["consumerprice"]*100)."%":"";
	}
	if(strlen($msg)==0)
		$msg="[".strip_tags($_shopdata->shopname)."]".$productname." ".$discount;
}
if(strlen($msg)==0)
		$msg="[".strip_tags($_shopdata->shopname)."]";
?>
<html>
<head>
<meta http-equiv='Content-Type' content='text/html;charset=euc-kr'>
<title>�������� ���ھ˸�</title>
<link rel="stylesheet" href="style.css" type="text/css">
<style type="text/css">
<!--
.textarea_hide2{padding:10px;overflow: hidden; font-family:����; height: 84px; width:200px;color: #191919; FONT-SIZE: 9pt; font-style: normal; border-top-width: 0; border-right-width: 0; border-bottom-width: 0; border-left-width: 0; border:1px solid #ddd; background:#F8F8F8;}
.input_hide2{color:#f00;font-size:12px;border:1px solid #ddd;font-weight:bold;}
TEXTAREA {  clip:   rect(   ); overflow: hidden; background-image:url('');font-family:����;}
.phone {  font-family:����; height: 80px; width: 173px;color: #191919;  FONT-SIZE: 9pt; font-style: normal; background-color: #A8E4ED;; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px}
-->
</style>

<script type="text/javascript" src="lib.js.php"></script>
<script language="JavaScript">
function CheckForm() {
<?if($isdisabled=="1"){?>
	if(document.form1.msg.value.length==0) {
		alert("������ �޼����� �Է��ϼ���.");
		document.form1.msg.focus();
		return;
	}
	cal_pre2();

	if(document.form1.from_tel.value.length==0) {
		alert("������ ��� ��ȭ��ȣ�� �Է��ϼ���.");
		document.form1.from_tel.focus();
		return;
	}

	if(<?=$maxcount?><document.form1.group_mem.value){
		alert("SMS �Ӵϰ� �����մϴ�. ������ �̿��ϼ���.");
		return;
	}
	if(confirm("�ش� ���ڸ� �߼��Ͻðڽ��ϱ�?")){
		document.form1.type.value="up";
		document.form1.submit();
	}
<?}else if($isdisabled=="0"){?>
	alert("SMS ȸ������ �� ���� �� SMS �⺻ȯ�� ��������\n\nSMS ���̵� �� ����Ű�� �Է��Ͻñ� �ٶ��ϴ�.");
<?}else if($isdisabled=="2"){?>
	alert("SMS ȸ�� ���̵� �������� �ʽ��ϴ�.\n\nSMS �⺻ȯ�� �������� SMS ���̵� �� ����Ű�� ��Ȯ�� �Է��Ͻñ� �ٶ��ϴ�.");
<?}else if($isdisabled=="3"){?>
	alert("SMS ȸ�� ����Ű�� ��ġ���� �ʽ��ϴ�.\n\nSMS �⺻ȯ�� �������� ����Ű�� ��Ȯ�� �Է��Ͻñ� �ٶ��ϴ�.");
<?}else if($isdisabled=="4"){?>
	alert("SMS ������ ����� �Ұ����մϴ�.\n\n��� �� �̿��Ͻñ� �ٶ��ϴ�.");
<?}?>
}

function DefaultFrom(checked,ch_type) {
	if(ch_type) {
		if(document.form1.clicknum.checked==false) {
			document.form1.from_tel.value="<?=$return_tel?>";
			document.form1.clicknum.checked = true;
		} else {
			document.form1.from_tel.value="";
			document.form1.clicknum.checked = false;
		}
	} else {
		if(checked==true) {
			document.form1.from_tel.value="<?=$return_tel?>";
		} else {
			document.form1.from_tel.value="";
		}
	}
}

function cal_pre2() {
	obj_msg = document.form1.msg;
	obj_len = document.form1.len_msg;

	strcnt = cal_byte2(obj_msg.value);

	if(strcnt > 80)	{
		reserve = strcnt - 80;
		alert('�޽��� ������ 80����Ʈ�� ������ �����ϴ�.\n\n�ۼ��Ͻ� �޼��� ������ '+ reserve +'byte�� �ʰ��Ǿ����ϴ�.\n\n�ʰ��� �κ��� �ڵ����� �����˴ϴ�.');
		obj_msg.value = nets_check2(obj_msg.value);
		strcnt = cal_byte2(obj_msg.value);
		obj_len.value=strcnt;
		return;
	}
	obj_len.value=strcnt;
}

function cal_byte2(aquery) {
	var tmpStr;
	var temp = 0;
	var onechar;
	var tcount = 0;
	var reserve = 0;

	tmpStr = new String(aquery);
	temp = tmpStr.length;

	for(k=0; k<temp; k++) {
		onechar = tmpStr.charAt(k);
		if(escape(onechar).length > 4) {
			tcount += 2;
		} else {
			tcount ++;
		}
	}
	return tcount;
}

function nets_check2(aquery) {
	var temStr;
	var temp = 0;
	var onechar;
	var tcount;
	tcount = 0;

	tmpStr = new String(aquery);
	temp = tmpStr.length;

	for(k=0;k<temp;k++)	{
		onechar = tmpStr.charAt(k);

		if(escape(onechar).length > 4) {
			tcount += 2;
		} else {
			tcount++;
		}

		if(tcount > 80) {
			tmpStr = tmpStr.substring(0,k);
			break;
		}
	}
	return tmpStr;
}

</script>
</head>
<body leftmargin=0 topmargin=0 marginwidth=0 marginheight=0 >
	<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td height="35" background="images/blueline_bg.gif"><b><span class="font_blue" style="padding-left:10px;">�������� ���� �˸�</span></b></td>
	</tr>
	<tr>
		<td height="10"></td>
	</tr>
	<TR>
		<TD align="right" style="font-size:13px; font-weight:bold;">�߼۰��ɰǼ� : <span style="color:#f00;"><?=number_format($maxcount);?></span>��</TD>
	</TR>
	<TR>
		<TD style="padding-left:10px">�������� ������û�� : <?=(int)$cnt?>��</TD>
	</TR>
	<tr>
		<td height="10"></td>
	</tr>
	<form name=form1 action="<?=$_SERVER[PHP_SELF]?>" method=post>
	<input type=hidden name=type>
	<input type="hidden" name="group_mem" value="<?=(int)$cnt?>">
	<input type="hidden" name="pcode" value="<?=$pcode?>">
	<tr>
		<td style="padding-left:10px">
			<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0 >
			<tr>
				<td colspan="2"><TEXTAREA class="textarea_hide2" name=msg rows=5 cols=26 onkeyup="cal_pre2();" onchange="cal_pre2();" <?if($isdisabled!="1") echo "disabled";?>><?=$msg?></TEXTAREA></td>
			</tr>
			<TR>
				<TD colspan="2" height="35"><input type="text" name="len_msg" value="0" style="PADDING-RIGHT:5px; WIDTH:20px; TEXT-ALIGN:right" onfocus="this.blur();" class="input_hide2">bytes (�ִ�80 bytes)<script>cal_pre2();</script></TD>
			</TR>
			<tr>
				<TD width="120" height="40"><img src="images/icon_point2.gif" width="8" height="11" border="0">������ ��� ��ȣ</TD>
				<TD height="40"><p class="LIPoint"><IMG height=5 width=0><input type=text name=from_tel value="<?=$from_tel?>" size=15 maxlength=15 class="input"><input type=checkbox id="idx_clicknum" name=clicknum value="Y" <?if($clicknum=="Y") echo "checked";?>  onclick="DefaultFrom(this.checked,'')"> <a href="javascript:DefaultFrom('','1');"><img src="images/btn_tel.gif" width="67" height="16" border="0"></a></TD>
			</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td height="20"></td>
	</tr>
	<tr>
		<td height="50" align="center"><a href="javascript:CheckForm();"><img src="images/btn_sms3.gif" width="123" height="38" border="0" <?if($isdisabled!="1") echo "style=\"filter:Alpha(Opacity=60) Gray\"";?>></a></td>
	</tr>
	</form>
	<TR>
		<TD align=right style="padding:5x" height="50"><a href="javascript:window.close();"><img src="images/btn_close.gif" width="36" height="18" border="0" vspace="0" border=0 hspace="2"></a></TD>
	</TR>
	</table>

<?=$onload?>
</body>
</html>