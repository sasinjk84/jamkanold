<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
INCLUDE ("access.php");

####################### ������ ���ٱ��� check ###############
$PageCode = "ma-4";
$MenuCode = "market";
if (!$_usersession->isAllowedTask($PageCode)) {
	INCLUDE ("AccessDeny.inc.php");
	exit;
}
#########################################################

$sql = "SELECT id, authkey, return_tel FROM tblsmsinfo ";
$result=mysql_query($sql,get_db_conn());
if($row=mysql_fetch_object($result)){
	$return_tel = explode("-",$row->return_tel);
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
$tel_list=$_POST["tel_list"];
$msg=$_POST["msg"];
$from_tel1=$_POST["from_tel1"];
$from_tel2=$_POST["from_tel2"];
$from_tel3=$_POST["from_tel3"];
$tel_list=$_POST["tel_list"];
$rsend=$_POST["rsend"];
$rsend_date=$_POST["rsend_date"];
$rsend_hour=$_POST["rsend_hour"];
$rsend_minute=$_POST["rsend_minute"];

if ($type=="up") {
	########################### TEST ���θ� Ȯ�� ##########################
	DemoShopCheck("������������� �׽�Ʈ�� �Ұ��� �մϴ�.", $_SERVER[PHP_SELF]);
	#######################################################################

	if($rsend=="0") {
		$date="0";
	} else {
		$date=$rsend_date." ".$rsend_hour.":".$rsend_minute.":00";
		$tmp_date=str_replace("-","",$rsend_date).$rsend_hour.$rsend_minute."00";
		if($tmp_date<date("YmdHis") || $tmp_date>date("YmdHis",time()+(60*60*24*14))) {
			echo "<script>alert('���೯¥ ������ �߸��Ǿ����ϴ�.\\n\\n�ٽ� Ȯ���Ͻñ� �ٶ��ϴ�.');location='".$_SERVER[PHP_SELF]."';</script>";
			exit;
		}
	}
	$fromtel=$from_tel1."-".$from_tel2."-".$from_tel3;
	$cnt=count(explode(",",$tel_list))<=$maxcount;
	if($cnt <=$maxcount){
		$etcmsg="���� �޼��� ����";
		$temp=SendSMS($sms_id, $sms_authkey, $tel_list, "", $fromtel, $date, $msg, $etcmsg); 
		$resmsg=explode("[SMS]",$temp);
		$onload = "<script>alert('".$resmsg[1]."');</script>";
	} else {
		$onload="<script>alert('SMS �Ӵϰ� �����մϴ�. ������ �̿��Ͻñ� �ٶ��ϴ�.');</script>";
	}
}

//if($maxcount>0 && strlen($onload)==0) $onload="<script>alert('���� ".$maxcount."���� SMS�� �߼��Ͻ� �� �ֽ��ϴ�.');</script>";

?>

<? INCLUDE "header.php"; ?>

<style type="text/css">
<!--
TEXTAREA {  clip:   rect(   ); overflow: hidden; background-image:url('');font-family:����;}
.phone {  font-family:����; height: 80px; width: 173px;color: #191919;  FONT-SIZE: 9pt; font-style: normal; background-color: #A8E4ED;; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px}
-->
</style>

<script type="text/javascript" src="lib.js.php"></script>
<script type="text/javascript" src="calendar.js.php"></script>
<script language="JavaScript">
function CheckForm() {
<?if($isdisabled=="1"){?>
	if(document.form1.msg.value.length==0) {
		alert("������ �޼����� �Է��ϼ���.");
		document.form1.msg.focus();
		return;
	}
	cal_pre2();

	for(i=1;i<=3;i++) {
		if(document.form1["from_tel"+i].value.length==0) {
			alert("������ ��� ��ȭ��ȣ�� �Է��ϼ���.");
			document.form1["from_tel"+i].focus();
			return;
		}
		if(!IsNumeric(document.form1["from_tel"+i].value)) {
			alert("���ڸ� �Է��ϼ���.");
			document.form1["from_tel"+i].focus();
			break; return;
		}
	}
	from_tel=document.form1.from_tel1.value+document.form1.from_tel2.value+document.form1.from_tel3.value;
	if(from_tel.length<8) {
		alert("������ ��� ��ȭ��ȣ �Է��� �߸��Ǿ����ϴ�.");
		document.form1.from_tel1.focus();
		return;
	}
	cnt=document.form1.to_list.options.length - 1;
	if(cnt==0) {
		alert("�޴� ��� �߰��� �ȵǾ����ϴ�.");
		document.form1.to_list.focus();
		return;
	}
	if (cnt > <?=$maxcount?>) {
		alert("SMS �Ӵϰ� �����մϴ�.\n\n<?=$maxcount?>�� ���� �߼� �����մϴ�.");
		document.form1.to_list.focus();
		return;
	}
	document.form1.tel_list.value="";
	for(i=1;i<=cnt;i++) {
		if(i==1) {
			document.form1.tel_list.value+=document.form1.to_list.options[i].value;
		} else {
			document.form1.tel_list.value+=","+document.form1.to_list.options[i].value;
		}
	}
	document.form1.type.value="up";
	document.form1.submit();
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
			document.form1.from_tel1.value="<?=$return_tel[0]?>";
			document.form1.from_tel2.value="<?=$return_tel[1]?>";
			document.form1.from_tel3.value="<?=$return_tel[2]?>";
			document.form1.clicknum.checked = true;
		} else {
			document.form1.from_tel1.value="";
			document.form1.from_tel2.value="";
			document.form1.from_tel3.value="";
			document.form1.clicknum.checked = false;
		}
	} else {
		if(checked==true) {
			document.form1.from_tel1.value="<?=$return_tel[0]?>";
			document.form1.from_tel2.value="<?=$return_tel[1]?>";
			document.form1.from_tel3.value="<?=$return_tel[2]?>";
		} else {
			document.form1.from_tel1.value="";
			document.form1.from_tel2.value="";
			document.form1.from_tel3.value="";
		}
	}
}

function ToAdd() {
	for(i=2;i<=3;i++) {
		if(!IsNumeric(document.form1["to_tel"+i].value)) {
			alert("���ڸ� �Է��ϼ���.");
			document.form1["to_tel"+i].focus();
			break; return;
		}
	}
	tel_txt=document.form1.to_tel1.value+"-"+document.form1.to_tel2.value+"-"+document.form1.to_tel3.value;
	tel_val=document.form1.to_tel1.value+""+document.form1.to_tel2.value+""+document.form1.to_tel3.value;
	if(tel_txt.length<12 || tel_txt.length>13) {
		alert("��ȭ��ȣ �Է��� �߸��Ǿ����ϴ�.");
		return;
	}
	to_list=document.form1.to_list;
	if(to_list.options.length>50) {
		alert("�޴� ����� 1ȸ 50�� ���� �����մϴ�.");
		return;
	}
	for(i=1;i<to_list.options.length;i++) {
		if(tel_val==to_list.options[i].value) {
			alert("�̹� �߰��� ��ȣ�Դϴ�.\n\n�ٽ� Ȯ���Ͻñ� �ٶ��ϴ�.");
			document.form1.to_tel1.selectedIndex=0;
			document.form1.to_tel2.value="";
			document.form1.to_tel3.value="";
			return;
		}
	}

	new_option = document.createElement("OPTION");
	new_option.text=tel_txt;
	new_option.value=tel_val;
	to_list.add(new_option);
	cnt=to_list.options.length - 1;
	to_list.options[0].text = "------------------- ���Ÿ��("+cnt+") ----------------------";
	document.form1.to_tel1.selectedIndex=0;
	document.form1.to_tel2.value="";
	document.form1.to_tel3.value="";
}

function ToDelete() {
	to_list=document.form1.to_list;
	for(i=1;i<to_list.options.length;i++) {
		if(to_list.options[i].selected==true){
			to_list.options[i]=null;
			cnt=to_list.options.length - 1;
			to_list.options[0].text = "------------------- ���Ÿ��("+cnt+") ----------------------";
			return;
		}
	}
	alert("������ ��ȣ�� �����ϼ���.");
	to_list.focus();
}

function sms_addressbook() {
	window.open("market_smsaddresspop.php","smsaddresspop","width=400,height=350,scrollbars=no");
}

function change_rsend(val) {
	if (val==0) {
		document.form1.rsend_date.disabled=true;
		document.form1.rsend_hour.disabled=true;
		document.form1.rsend_minute.disabled=true;
	} else if(val==1) {
		document.form1.rsend_date.disabled=false;
		document.form1.rsend_hour.disabled=false;
		document.form1.rsend_minute.disabled=false;
		alert("����߼��� 14�� �̳��� �����Ͽ���\n\n������ ������ ������ �Ͻñ� �ٶ��ϴ�.");
	}
}

function addChar(aspchar) {
<?if($isdisabled=="1"){?>
	document.form1.msg.value += aspchar;
	cal_pre2();
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
<table cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed">
<tr>
	<td valign="top">
	<table cellpadding="0" cellspacing="0" width=100% style="table-layout:fixed">
	<tr>
		<td>
		<table cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed"  background="images/con_bg.gif">
		<col width=198></col>
		<col width=10></col>
		<col width=></col>
		<tr>
			<td valign="top"  background="images/leftmenu_bg.gif">
			<? include ("menu_market.php"); ?>
			</td>

			<td></td>
			<td valign="top">




<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td height="29" colspan="3">
			<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td height="28" class="link" align="left" background="images/con_link_bg.gif"><img src="images/top_link_house.gif" border="0" valign="absmiddle">������ġ : ���������� &gt; SMS �߼�/���� &gt; <span class="2depth_select">SMS ���� �߼�</span></td>
			</tr>
			</table>
		</td>
	</tr>   
	<tr>
        <td width="16"><img src="images/con_t_01.gif" width="16" height="16" border="0"></td>
        <td background="images/con_t_01_bg.gif"></td>
        <td width="16"><img src="images/con_t_02.gif" width="16" height="16" border="0"></td>
    </tr>
    <tr>
        <td width="16" background="images/con_t_04_bg1.gif"></td>
        <td bgcolor="#ffffff" style="padding:10px">








			<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td height="8"></td>
			</tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/market_smssinglesend_title.gif" ALT=""></TD>
					</tr><tr>
<TD width="100%" background="images/title_bg.gif" height="21"></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr>
				<td height="3"></td>
			</tr>
			<tr>
				<td style="padding-bottom:3pt;">
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/distribute_01.gif"></TD>
					<TD COLSPAN=2 background="images/distribute_02.gif"></TD>
					<TD><IMG SRC="images/distribute_03.gif"></TD>
				</TR>
				<TR>
					<TD background="images/distribute_04.gif"></TD>
					<TD class="notice_blue"><IMG SRC="images/distribute_img.gif" ></TD>
					<TD width="100%" class="notice_blue">Ư�� ������ SMS�� �߼��� �� �ֽ��ϴ�.</TD>
					<TD background="images/distribute_07.gif"></TD>
				</TR>
				<TR>
					<TD><IMG SRC="images/distribute_08.gif"></TD>
					<TD COLSPAN=2 background="images/distribute_09.gif"></TD>
					<TD><IMG SRC="images/distribute_10.gif"></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr>
				<td height=20></td>
			</tr>
			<form name=form1 action="<?=$_SERVER[PHP_SELF]?>" method=post>
			<input type=hidden name=type>
			<input type=hidden name=tel_list>
			<tr>
				<td>
				<table cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td width="224" valign="top">
					<table align="center" cellpadding="0" cellspacing="0" width="200">
					<tr>
						<td>
						<TABLE WIDTH=200 BORDER=0 CELLPADDING=0 CELLSPACING=0 align="center">
						<TR>
							<TD><IMG SRC="images/sms_top_01.gif" WIDTH=200 HEIGHT="30" ALT=""></TD>
						</TR>
						<TR>
							<TD align=center height="90" background="images/sms_bg.gif" valign="top"><TEXTAREA name=msg rows=5 cols=26 onkeyup="cal_pre2();" onchange="cal_pre2();" class="textarea_hide" <?if($isdisabled!="1") echo "disabled";?>></TEXTAREA></TD>
						</TR>
						<TR>
							<TD align=center height="26" background="images/sms_down_01.gif"><input type="text" name="len_msg" value="0" style="PADDING-RIGHT:5px; WIDTH:20px; TEXT-ALIGN:right" onfocus="this.blur();" class="input_hide">bytes (�ִ�80 bytes)<SCRIPT>cal_pre2('mem_join',false);</SCRIPT></TD>
						</TR>
						<TR>
							<TD HEIGHT=6></TD>
						</TR>
						<TR>
							<TD align="center" style="font-size:13px; font-weight:bold;">�߼۰��ɰǼ� : <span style="color:#f00;"><?=number_format($maxcount);?></span>��</TD>
						</TR>
						<TR>
							<TD HEIGHT=6></TD>
						</TR>
						<TR>
							<TD>
							<TABLE cellSpacing=1 cellPadding=0 width="100%" bgColor="#EEEEEE" border=0>
							<TR align=middle bgColor=#ffffff>
<?
				$specialchar = array("��","��","��","��","��","��","��","��","��","��","��","��","��","��","��","��","��","��","��","��","��","��","��","��","��","��","��","��","��","��","��","��","��","��","��","��","��","��","��","��","��","��","��","��","��","��","?","��","��","��","��","��","��","��","��","��","��","��","��","��","��","��","��","��","��","��","��","��","��","(",")","��","��","��","��","��","��","��","��","��");

				for($i=0;$i<count($specialchar);$i++) {
					if ($i>0 && $i%10==0) {
						echo "</tr>\n";
						echo "<TR align=middle bgColor=#ffffff>\n";
					}
					echo "<td width=10% style=\"CURSOR: hand; LINE-HEIGHT: 14pt; FONT-FAMILY: ����\" onmouseover=\"this.style.background='#DFF6FF'\" onmouseout=\"this.style.background='#FFFFFF'\" onclick=\"addChar('".$specialchar[$i]."');\">".$specialchar[$i]."</td>\n";
				}
?>
							</TABLE>
							</TD>
						</TR>
						</TABLE>
						</td>
					</tr>
					</table>
					</td>
					<td width="11" valign="top">&nbsp;</td>
					<td  valign="top">
					<table cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td  bgcolor="#ededed" style="padding:4pt;">
						<table cellpadding="0" cellspacing="0" width="100%" bgcolor="white">
						<tr>
							<td width="100%">
							<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
							<TR>
								<TD align=center  colspan="2" height="35" background="images/blueline_bg.gif"><b><font color=#555555>�޴��� ���ڸ޼���(SMS) �߼����� �Է�</span></b></TD>
							</TR>
							<TR>
								<TD colspan="2" background="images/table_con_line.gif"></TD>
							</TR>
							<TR>
								<TD width="148" class="table_cell" height="40"><img src="images/icon_point2.gif" width="8" height="11" border="0">������ ���</TD>
								<TD  class="td_con1" height="40"><p class="LIPoint"><IMG height=5 width=0><input type=text name=from_tel1 size=4 maxlength=3 onKeyUp="return strnumkeyup(this);" class="input"> - <INPUT onkeyup="return strnumkeyup(this);" maxLength=4 size=5 name=from_tel2 class="input"> - <input type=text name=from_tel3 size=5 maxlength=4 onKeyUp="return strnumkeyup(this);" class="input"><input type=checkbox id="idx_clicknum" name=clicknum onclick="DefaultFrom(this.checked,'')"> <a href="javascript:DefaultFrom('','1');"><img src="images/btn_tel.gif" width="67" height="16" border="0"></a></TD>
							</TR>
							<TR>
								<TD colspan="2" background="images/table_con_line.gif"></TD>
							</TR>
							<TR>
								<TD width="148" class="table_cell" valign="top"><img src="images/icon_point2.gif" width="8" height="11" border="0">�޴� ���</TD>
								<TD  class="td_con1">
								<table cellpadding="0" cellspacing="0" width="100%">
								<tr>
									<td width="100%">
									<table cellpadding="0" cellspacing="0">
									<tr>
										<td width="170"><p class="LIPoint">
										<select name=to_tel1 style="width:45" class="select">
										<option value="010">010</option>
										<option value="011">011</option>
										<option value="016">016</option>
										<option value="017">017</option>
										<option value="018">018</option>
										<option value="019">019</option>
										</select> - <input type=text name=to_tel2 size=4 maxlength=4 onKeyUp="return strnumkeyup(this);" class="input"> - <input type=text name=to_tel3 size=4 maxlength=4 onKeyUp="return strnumkeyup(this);" class="input"></td>
										<td width="1"><a href="javascript:ToAdd();"><img src="images/btn_add1.gif" width="50" height="22" border="0" hspace="2"></a></td>
										<td width="25"><a href="javascript:ToDelete();"><img src="images/btn_del.gif" width="50" height="22" border="0"></a></td>
										<td width="25"><a href="javascript:sms_addressbook();"><img src="images/btn_addresssearch.gif" width="88" height="25" border="0" hspace="2"></a></td>
									</tr>
									</table>
									</td>
								</tr>
								<tr>
									<td width="100%" style="padding-top:2pt;"><select name=to_list size=10 style="WIDTH:100%" class="select">
										<option value="" style="BACKGROUND-COLOR: #ffff00">------------------- ���Ÿ��(0) ----------------------</option>
										</select></td>
								</tr>
								</table>
								</TD>
							</TR>
							<TR>
								<TD colspan="2" background="images/table_con_line.gif"></TD>
							</TR>
							<TR>
								<TD width="148" class="table_cell" height="40"><img src="images/icon_point2.gif" width="8" height="11" border="0">�߼� ����</TD>
								<TD  class="td_con1"><input type=radio id="idx_rsend1" name=rsend value="0" checked onclick="change_rsend(this.value);"><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_rsend1>���</label>
								<input type=radio id="idx_rsend2" name=rsend value="1" onclick="change_rsend(this.value);"><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_rsend2>����</label>
									<input type=text name=rsend_date value="<?=date("Y-m-d");?>" size=12 onfocus="this.blur();" OnClick="Calendar(this)" style="BACKGROUND: #efefef; TEXT-ALIGN: center" class="input" disabled>
									<select name=rsend_hour disabled class="select">
<?
				for($i=1;$i<=24;$i++) {
					$i=substr("0".$i,-2);
					if($i==date("H"))
						echo "<option value=\"".$i."\" selected>".$i."��</option>\n";
					else
						echo "<option value=\"".$i."\">".$i."��</option>\n";
				}
?>
									</select>
									<select name=rsend_minute disabled class="select">
<?
				for($i=0;$i<=59;$i++) {
					$i=substr("0".$i,-2);
					if($i==date("i"))
						echo "<option value=\"".$i."\" selected>".$i."��</option>\n";
					else
						echo "<option value=\"".$i."\">".$i."��</option>\n";
				}
?>
									</select>
								</TD>
							</TR>
							</TABLE>
							</td>
						</tr>
						</table>
						</td>
					</tr>
					</table>
					</td>
				</tr>
				</table>
				</td>
			</tr>
			<tr>
				<td height=6></td>
			</tr>
			<tr>
				<td>
				<table cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td>&nbsp;</td>
					<td align=center><a href="javascript:CheckForm();"><img src="images/btn_sms3.gif" width="123" height="38" border="0" <?if($isdisabled!="1") echo "style=\"filter:Alpha(Opacity=60) Gray\"";?>></a>&nbsp;&nbsp;<a href="market_smsfill.php"><img src="images/btn_sms4.gif" width="123" height="38" border="0" hspace="2"></a></td>
				</tr>
				</table>
				</td>
			</tr>
			</form>
			<tr>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/manual_top1.gif" WIDTH=15 height="45" ALT=""></TD>
					<TD><IMG SRC="images/manual_title.gif" WIDTH=113 height="45" ALT=""></TD>
					<TD width="100%" background="images/manual_bg.gif" height="35"></TD>
					<TD background="images/manual_bg.gif"></TD>
					<td background="images/manual_bg.gif"><IMG SRC="images/manual_top2.gif" WIDTH=18 height="45" ALT=""></td>
				</TR>
				<TR>
					<TD background="images/manual_left1.gif"></TD>
					<TD COLSPAN=3 width="100%" valign="top" bgcolor="white" style="padding-top:8pt; padding-bottom:8pt; padding-left:4pt;" class="menual_bg">
					<table cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td width="20" align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td ><span class="font_dotline">SMS ���� �߼�</span></td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td  class="space_top">- SMS ���ڸ޼��� ������� ���Ἥ�� �Դϴ�. SMS�� ���� ���� �� ��� �����մϴ�.</td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td  class="space_top">- SMS ���ڸ޼����� 1ȸ �ִ� 80Byte, ��� �ο��� 50�� ���� �߼��� �����մϴ�.</td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td  class="space_top">- ��Ʈ��ũ ���� �� ��Ż� ������ ���� &quot;���&quot; �߼� ��쿡�� �ټ� �ð��� ������ �� �ֽ��ϴ�.</td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td  class="space_top">- SMS ����߼��� 14�� �̳��� �����մϴ�.</td>
					</tr>
					</table>
					</TD>
					<TD background="images/manual_right1.gif"></TD>
				</TR>
				<TR>
					<TD><IMG SRC="images/manual_left2.gif" WIDTH=15 HEIGHT=8 ALT=""></TD>
					<TD COLSPAN=3 background="images/manual_down.gif"></TD>
					<TD><IMG SRC="images/manual_right2.gif" WIDTH=18 HEIGHT=8 ALT=""></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr>
				<td height="50"></td>
			</tr>
			</table>
</td>
        <td width="16" background="images/con_t_02_bg.gif"></td>
    </tr>
    <tr>
        <td width="16"><img src="images/con_t_04.gif" width="16" height="16" border="0"></td>
        <td background="images/con_t_04_bg.gif"></td>
        <td width="16"><img src="images/con_t_03.gif" width="16" height="16" border="0"></td>
    </tr>
    <tr><td height="20"></td></tr>
</table>


			</td>
		</tr>
		</table>
		</td>
	</tr>
	</table>
	</td>
</tr>
</table>
<?=$onload?>

<? INCLUDE "copyright.php"; ?>