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

$shopname=$_shopdata->shopname;

$msg_list = array(
					"mem_join"=>"[".strip_tags($shopname)."] [NAME]�� ȸ�� ������ �����մϴ�.",
					"mem_order"=>"[".strip_tags($shopname)."] [NAME]�Բ����� [PRODUCT]�� �ֹ��ϼ̽��ϴ�.",
					"mem_bank"=>"[NAME]��~ [PRICE]�� [ACCOUNT] �Աݹٶ��ϴ�. [".strip_tags($shopname)."]",
					"mem_bankok"=>"[".strip_tags($shopname)."] [DATE]�� �ֹ��� �Ա�Ȯ�� �Ǿ����ϴ�. �ٷ� �߼��� �帮�ڽ��ϴ�.",
					"mem_delivery"=>"[".strip_tags($shopname)."]���� [DATE]�� �ֹ��� ��ǰ�� �߼��� ��Ƚ��ϴ�. �����մϴ�.",
					"mem_delinum"=>"[".strip_tags($shopname)."] [DELICOM] �����ȣ : [DELINUM] ���� �߼�ó�� �Ǿ����ϴ�.",
					"mem_birth"=>"[".strip_tags($shopname)."] [NAME]�� [DATE]�� �¾�� ������ ���Դϴ�. ������ ���ϵ帳�ϴ�!",
					"mem_auth"=>"[NAME]���� ȸ������ ó���� �Ϸ�Ǿ����ϴ�. [".strip_tags($shopname)."]",
					"mem_passwd"=>"[".$shopname."]ȫ�浿�� ID : hong27 PW : gilddong�Դϴ�.",
					"admin_join"=>"ȫ�浿���� hong27��� ID�� �����ϼ̽��ϴ�.",
					"admin_order"=>"ȫ�浿���� [��ǰ1,��ǰ2] ī��(����) �����ϼ̽��ϴ�.",
					"admin_cancel"=>"ȫ�浿�Բ��� 2007/01/01�� �ֹ��Ͻ� �ֹ��� ����ϼ̽��ϴ�.",
					"admin_board"=>"[�׽�Ʈ �Խ���]�� �űԱ��� [����]���� �ԷµǾ����ϴ�.",
					"admin_soldout"=>"[��ǰ1]�� [ȫ�浿]�� �ֹ��� ���ؼ� ǰ���Ǿ����ϴ�.",
					"mem_gift"=>"[".$shopname."] ������ȣ[AUTHCODE] [NAME]���� ��ǰ���� �����ϼ̽��ϴ�.",
					"socialshopping"=>"[".strip_tags($shopname)."] [PRODUCT_NAME]���Ŵ޼�����. �������",
					"product_hongbo"=>"[".strip_tags($shopname)."] [URL]",
					"mem_present"=>"[".strip_tags($shopname)."] [URL] [NAME]���� �����ϼ̽��ϴ�.",
					"mem_pester"=>"[NAME]���� ������: [URL] (�󼼳��� ����Ȯ��)"
					);
					
					
$msg_extra = array();
$msg_extra['booking']= array('����',"[".strip_tags($shopname)."] [NAME]���� ������ ���� �Ǿ����ϴ�.");
$msg_extra['autocancel']= array('�ڵ����',"[".strip_tags($shopname)."] [NAME]���� ������Ա����� �ڵ� ��� �Ǿ����ϴ�.");
//$msg_extra['bookingcancel']= array('�ڵ����',"[".strip_tags($shopname)."] [NAME]���� ������Ա����� �ڵ� ��� �Ǿ����ϴ�.");


$type=$_POST["type"];
$sms_id=$_POST["sms_id"];
$sms_authkey=$_POST["sms_authkey"];
$sms_uname=$_POST["sms_uname"];
$return_tel1=$_POST["return_tel1"];
$return_tel2=$_POST["return_tel2"];
$return_tel3=$_POST["return_tel3"];
if(strlen($return_tel1)>0 && strlen($return_tel2)>0 && strlen($return_tel3)>0) {
	$return_tel=$return_tel1."-".$return_tel2."-".$return_tel3;
}
$admin_tel1=$_POST["admin_tel1"];
$admin_tel2=$_POST["admin_tel2"];
$admin_tel3=$_POST["admin_tel3"];
if(strlen($admin_tel1)>0 && strlen($admin_tel2)>0 && strlen($admin_tel3)>0) {
	$admin_tel=$admin_tel1."-".$admin_tel2."-".$admin_tel3;
}
$subadmin1_tel1=$_POST["subadmin1_tel1"];
$subadmin1_tel2=$_POST["subadmin1_tel2"];
$subadmin2_tel3=$_POST["subadmin1_tel3"];
if(strlen($subadmin1_tel1)>0 && strlen($subadmin1_tel2)>0 && strlen($subadmin1_tel3)>0) {
	$subadmin1_tel=$subadmin1_tel1."-".$subadmin1_tel2."-".$subadmin1_tel3;
}
$subadmin2_tel1=$_POST["subadmin2_tel1"];
$subadmin2_tel2=$_POST["subadmin2_tel2"];
$subadmin2_tel3=$_POST["subadmin2_tel3"];
if(strlen($subadmin2_tel1)>0 && strlen($subadmin2_tel2)>0 && strlen($subadmin2_tel3)>0) {
	$subadmin2_tel=$subadmin2_tel1."-".$subadmin2_tel2."-".$subadmin2_tel3;
}
$subadmin3_tel1=$_POST["subadmin3_tel1"];
$subadmin3_tel2=$_POST["subadmin3_tel2"];
$subadmin3_tel3=$_POST["subadmin3_tel3"];
if(strlen($subadmin3_tel1)>0 && strlen($subadmin3_tel2)>0 && strlen($subadmin3_tel3)>0) {
	$subadmin3_tel=$subadmin3_tel1."-".$subadmin3_tel2."-".$subadmin3_tel3;
}
$check_sleep_time=$_POST["check_sleep_time"];
$sleep_time1=$_POST["sleep_time1"];
$sleep_time2=$_POST["sleep_time2"];
$mem_join=(strlen($_POST["mem_join"])>0?$_POST["mem_join"]:"N");
$mem_order=(strlen($_POST["mem_order"])>0?$_POST["mem_order"]:"N");
$mem_delivery=(strlen($_POST["mem_delivery"])>0?$_POST["mem_delivery"]:"N");
$mem_delinum=(strlen($_POST["mem_delinum"])>0?$_POST["mem_delinum"]:"N");
$mem_bank=(strlen($_POST["mem_bank"])>0?$_POST["mem_bank"]:"N");
$mem_bankok=(strlen($_POST["mem_bankok"])>0?$_POST["mem_bankok"]:"N");
$mem_bankokvender=(strlen($_POST["mem_bankokvender"])>0?$_POST["mem_bankokvender"]:"N");
$mem_birth=(strlen($_POST["mem_birth"])>0?$_POST["mem_birth"]:"N");
$mem_auth=(strlen($_POST["mem_auth"])>0?$_POST["mem_auth"]:"N");
$mem_passwd=(strlen($_POST["mem_passwd"])>0?$_POST["mem_passwd"]:"N");
$admin_join=(strlen($_POST["admin_join"])>0?$_POST["admin_join"]:"N");
$admin_order=(strlen($_POST["admin_order"])>0?$_POST["admin_order"]:"N");
$vender_order=(strlen($_POST["vender_order"])>0?$_POST["vender_order"]:"N");
$admin_cancel=(strlen($_POST["admin_cancel"])>0?$_POST["admin_cancel"]:"N");
$admin_soldout=(strlen($_POST["admin_soldout"])>0?$_POST["admin_soldout"]:"N");
$admin_board=(strlen($_POST["admin_board"])>0?$_POST["admin_board"]:"N");

$msg_mem_join=$_POST["msg_mem_join"];
$msg_mem_order=$_POST["msg_mem_order"];
$mem_delivery=$_POST["mem_delivery"];
$msg_mem_delinum=$_POST["msg_mem_delinum"];
$msg_mem_bank=$_POST["msg_mem_bank"];
$msg_mem_bankok=$_POST["msg_mem_bankok"];
$msg_mem_birth=$_POST["msg_mem_birth"];
$msg_mem_auth=$_POST["msg_mem_auth"];

// 2012-03-09
$mem_gift=(strlen($_POST["mem_gift"])>0?$_POST["mem_gift"]:"N");
$msg_mem_gift=$_POST["msg_mem_gift"];
$use_mms=$_POST["use_mms"];
$socialshopping=(strlen($_POST["socialshopping"])>0?$_POST["socialshopping"]:"N");
$msg_socialshopping=$_POST["msg_socialshopping"];
$product_hongbo=(strlen($_POST["product_hongbo"])>0?$_POST["product_hongbo"]:"N");
$mem_present=(strlen($_POST["mem_present"])>0?$_POST["mem_present"]:"N");
$msg_mem_present=$_POST["msg_mem_present"];
$mem_pester=(strlen($_POST["mem_pester"])>0?$_POST["mem_pester"]:"N");
$msg_mem_pester=$_POST["msg_mem_pester"];

if ($type=="update") {
	########################### TEST ���θ� Ȯ�� ##########################
	DemoShopCheck("������������� �׽�Ʈ�� �Ұ��� �մϴ�.", $_SERVER[PHP_SELF]);
	#######################################################################

	if(strlen($sms_id)>0 && strlen($sms_authkey)>0) {
		$smscountdata=getSmscount($sms_id,$sms_authkey);
		if(substr($smscountdata,0,2)=="OK") {
			$sql = "UPDATE tblsmsinfo SET ";
			$sql.= "id				= '".$sms_id."', ";
			$sql.= "authkey			= '".$sms_authkey."' ";
			mysql_query($sql,get_db_conn());
			$onload="<script>alert('SMS �⺻ȯ�� ������ �Ϸ�Ǿ����ϴ�.')</script>";
		} else {
			if(substr($smscountdata,0,2)=="NO") {
				$onload="<script>alert('SMS ȸ�� ���̵� �������� �ʽ��ϴ�.\\n\\nSMS ȸ�� ���̵� ��Ȯ�� �Է��Ͻñ� �ٶ��ϴ�.');</script>";
			} else if(substr($smscountdata,0,2)=="AK") {
				$onload="<script>alert('SMS ȸ�� ����Ű�� ��ġ���� �ʽ��ϴ�.\\n\\n����Ű�� ��Ȯ�� �Է��Ͻñ� �ٶ��ϴ�.');</script>";
			} else {
				$onload="<script>alert('SMS ������ ����� �Ұ����մϴ�.\\n\\n��� �� �̿��Ͻñ� �ٶ��ϴ�.');</script>";
			}
		}
		$sql = "UPDATE tblsmsinfo SET ";
	} else {
		$sql = "UPDATE tblsmsinfo SET ";
		if(strlen($sms_id)>0) {
			$sql.= "id				= '".$sms_id."', ";
			$sql.= "authkey			= '', ";
		} else {
			$sql.= "id				= '', ";
			$sql.= "authkey			= '".$sms_authkey."', ";
		}
	}

	if ($check_sleep_time=="Y" || ($sleep_time1==$sleep_time2)) {
		$check_sleep_time1=$check_sleep_time2=0;
	} else {
		$check_sleep_time1=$sleep_time2;
		if($sleep_time1==0) $check_sleep_time2=23;
		else $check_sleep_time2=$sleep_time1-1;
	}

	$sql.= "sms_uname		= '".$sms_uname."', ";
	$sql.= "mem_join		= '".$mem_join."', ";
	$sql.= "mem_order		= '".$mem_order."', ";
	$sql.= "mem_delivery	= '".$mem_delivery."', ";
	$sql.= "mem_delinum		= '".$mem_delinum."', ";
	$sql.= "mem_bank		= '".$mem_bank."', ";
	$sql.= "mem_bankok		= '".$mem_bankok."', ";
	$sql.= "mem_bankokvender		= '".$mem_bankokvender."', ";
	$sql.= "mem_birth		= '".$mem_birth."', ";
	$sql.= "mem_auth		= '".$mem_auth."', ";
	$sql.= "mem_passwd		= '".$mem_passwd."', ";
	$sql.= "admin_join		= '".$admin_join."', ";
	$sql.= "admin_order		= '".$admin_order."', ";
	$sql.= "vender_order		= '".$vender_order."', ";
	$sql.= "admin_cancel	= '".$admin_cancel."', ";
	$sql.= "admin_board		= '".$admin_board."', ";
	$sql.= "admin_soldout	= '".$admin_soldout."', ";
	$sql.= "msg_mem_join	= '".$msg_mem_join."', ";
	$sql.= "msg_mem_order	= '".$msg_mem_order."', ";
	$sql.= "msg_mem_delivery= '".$msg_mem_delivery."', ";
	$sql.= "msg_mem_delinum	= '".$msg_mem_delinum."', ";
	$sql.= "msg_mem_bank	= '".$msg_mem_bank."', ";
	$sql.= "msg_mem_bankok	= '".$msg_mem_bankok."', ";
	$sql.= "msg_mem_birth	= '".$msg_mem_birth."', ";
	$sql.= "msg_mem_auth	= '".$msg_mem_auth."', ";
	$sql.= "admin_tel		= '".$admin_tel."', ";
	$sql.= "subadmin1_tel	= '".$subadmin1_tel."', ";
	$sql.= "subadmin2_tel	= '".$subadmin2_tel."', ";
	$sql.= "subadmin3_tel	= '".$subadmin3_tel."', ";
	$sql.= "sleep_time1		= '".$check_sleep_time1."', ";
	$sql.= "sleep_time2		= '".$check_sleep_time2."', ";
	$sql.= "use_mms			= '".$use_mms."', ";
	$sql.= "msg_mem_gift	= '".$msg_mem_gift."', ";
	$sql.= "mem_gift		= '".$mem_gift."', ";
	$sql.= "socialshopping	= '".$socialshopping."', ";
	$sql.= "msg_socialshopping	= '".$msg_socialshopping."', ";
	$sql.= "product_hongbo	= '".$product_hongbo."', ";
	$sql.= "mem_present		= '".$mem_present."', ";
	$sql.= "msg_mem_present	= '".$msg_mem_present."', ";
	$sql.= "mem_pester		= '".$mem_pester."', ";
	$sql.= "msg_mem_pester	= '".$msg_mem_pester."', ";
	$sql.= "return_tel		= '".$return_tel."' ";
	mysql_query($sql,get_db_conn());
	$onload="<script>alert('SMS �⺻ȯ�� ������ �Ϸ�Ǿ����ϴ�.')</script>";
}

$sql = "SELECT * FROM tblsmsinfo ";
$result=mysql_query($sql,get_db_conn());
if ($row=mysql_fetch_object($result)) {
	$sms_id = $row->id;
	$sms_authkey = $row->authkey;
	$admin_tel = explode("-", $row->admin_tel);
	$subadmin1_tel = explode("-", $row->subadmin1_tel);
	$subadmin2_tel = explode("-", $row->subadmin2_tel);
	$subadmin3_tel = explode("-", $row->subadmin3_tel);
	$return_tel = explode("-",$row->return_tel);
	if(strlen($row->msg_mem_join)==0) $row->msg_mem_join=$msg_list[mem_join];
	if(strlen($row->msg_mem_order)==0) $row->msg_mem_order=$msg_list[mem_order];
	if(strlen($row->msg_mem_bankok)==0) $row->msg_mem_bankok=$msg_list[mem_bankok];
	if(strlen($row->msg_mem_delivery)==0) $row->msg_mem_delivery=$msg_list[mem_delivery];
	if(strlen($row->msg_mem_birth)==0) $row->msg_mem_birth=$msg_list[mem_birth];
	if(strlen($row->msg_mem_delinum)==0) $row->msg_mem_delinum=$msg_list[mem_delinum];
	if(strlen($row->msg_mem_bank)==0) $row->msg_mem_bank=$msg_list[mem_bank];
	if(strlen($row->msg_mem_auth)==0) $row->msg_mem_auth=$msg_list[mem_auth];
	if(strlen($row->msg_mem_gift)==0) $row->msg_mem_gift=$msg_list[mem_gift];
	if(strlen($row->msg_socialshopping)==0) $row->msg_socialshopping=$msg_list[socialshopping];
	if(strlen($row->msg_mem_present)==0) $row->msg_mem_present=$msg_list[mem_present];
	if(strlen($row->msg_mem_pester)==0) $row->msg_mem_pester=$msg_list[mem_pester];
	$sleep_time1=$row->sleep_time2;
	$sleep_time2=$row->sleep_time1;
} else {
	$sql = "INSERT INTO tblsmsinfo (sms_uname) VALUES ('".$_shopdata->shopname."')";
	$result=mysql_query($sql,get_db_conn());
}

if ($sleep_time1==0 && $sleep_time2==0) {
	$check_sleep_time="Y";
	$sleep_time1=$sleep_time2=0;
} else {
	$check_sleep_time="N";
	if($sleep_time1==23) $sleep_time1=0;
	else $sleep_time1=$sleep_time1+1;
}

?>

<? INCLUDE "header.php"; ?>

<style type="text/css">
<!--
TEXTAREA {  clip:   rect(   ); overflow: hidden; background-image:url('');font-family:����;}
.phone {  font-family:����; height: 80px; width: 173px;color: #191919;  FONT-SIZE: 9pt; font-style: normal; background-color: #A8E4ED;; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px}
-->
</style>

<script type="text/javascript" src="lib.js.php"></script>
<script language="JavaScript">
function CheckForm() {
	for(i=1;i<=3;i++) {
		if(!IsNumeric(document.form1["return_tel"+i].value)) {
			alert("���ڸ� �Է��ϼ���.");
			document.form1["return_tel"+i].focus();
			break; return;
		}
	}
	for(i=1;i<=3;i++) {
		if(!IsNumeric(document.form1["admin_tel"+i].value)) {
			alert("���ڸ� �Է��ϼ���.");
			document.form1["admin_tel"+i].focus();
			break; return;
		}
	}
	for(i=1;i<=3;i++) {
		if(!IsNumeric(document.form1["subadmin1_tel"+i].value)) {
			alert("���ڸ� �Է��ϼ���.");
			document.form1["subadmin1_tel"+i].focus();
			break; return;
		}
	}
	for(i=1;i<=3;i++) {
		if(!IsNumeric(document.form1["subadmin2_tel"+i].value)) {
			alert("���ڸ� �Է��ϼ���.");
			document.form1["subadmin2_tel"+i].focus();
			break; return;
		}
	}
	for(i=1;i<=3;i++) {
		if(!IsNumeric(document.form1["subadmin3_tel"+i].value)) {
			alert("���ڸ� �Է��ϼ���.");
			document.form1["subadmin3_tel"+i].focus();
			break; return;
		}
	}
	document.form1.type.value="update";
	document.form1.submit();
}
function CheckSleepTime(disabled) {
	document.form1.sleep_time1.disabled=disabled;
	document.form1.sleep_time2.disabled=disabled;
}
function cal_pre2(field,ismsg) {
	var strcnt,obj_msg,obj_len;
	var reserve=0;

	obj_msg = document.form1["msg_"+field];
	obj_len = document.form1["len_"+field];

	strcnt = cal_byte2(obj_msg.value);

	if(strcnt > 80)	{
		reserve = strcnt - 80;
		if(ismsg==true) {
			alert('�޽��� ������ 80����Ʈ�� ������ �����ϴ�.\n\n�ۼ��Ͻ� �޼��� ������ '+ reserve +'byte�� �ʰ��Ǿ����ϴ�.\n\n�ʰ��� �κ��� �ڵ����� �����˴ϴ�.');
		}
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

function cal_pre3(field,ismsg) {
	var strcnt,obj_msg,obj_len;
	var reserve=0;

	obj_msg = document.form1["msg_"+field];
	obj_len = document.form1["len_"+field];

	strcnt = cal_byte2(obj_msg.value);

	if(strcnt > 80)	{
		if(document.form1.use_mms[1].checked==true) {
			if(strcnt==81 && ismsg==true) alert('�Է³����� 80byte�� �Ѿ�MMS�� ��ȯ�˴ϴ�');
			obj_len.value=strcnt;
		}
		else {
			reserve = strcnt - 80;
			if(ismsg==true) {
				alert('���� MMS���������� �����Ǿ� �ֽ��ϴ�. ��������� ��ȯ �� ��밡���մϴ�');
			}
			obj_msg.value = nets_check2(obj_msg.value);
			strcnt = cal_byte2(obj_msg.value);
			obj_len.value=strcnt;
		}
		return;
	}
	obj_len.value=strcnt;
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
				<td height="28" class="link" align="left" background="images/con_link_bg.gif"><img src="images/top_link_house.gif" border="0" valign="absmiddle">������ġ : ���������� &gt; SMS �߼�/����  &gt; <span class="2depth_select">SMS �⺻ȯ�� ����</span></td>
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
			<form name=form1 action="<?=$_SERVER[PHP_SELF]?>" method=post>
			<input type=hidden name=type>

			<tr><td height="8"></td></tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/market_smsconfig_title.gif"  ALT=""></TD>
					</tr><tr>
<TD width="100%" background="images/title_bg.gif" height="21"></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr><td height="3"></td></tr>
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
					<TD width="100%" class="notice_blue">SMS ���ڼ��� �⺻ȯ���  �����޴��� ������ �� �ֽ��ϴ�.</TD>
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
			<tr><td height="20"></td></tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/market_smsconfig_stitle1.gif"  ALT=""></TD>
					<TD width="100%" background="images/shop_basicinfo_stitle_bg.gif"></TD>
					<TD><IMG SRC="images/shop_basicinfo_stitle_end.gif" WIDTH=10 HEIGHT=31 ALT=""></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr><td height=3></td></tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/distribute_01.gif" WIDTH=7 HEIGHT=7 ALT=""></TD>
					<TD COLSPAN=2 background="images/distribute_02.gif"></TD>
					<TD><IMG SRC="images/distribute_03.gif" WIDTH=8 HEIGHT=7 ALT=""></TD>
				</TR>
				<TR>
					<TD background="images/distribute_04.gif"></TD>
					<TD class="notice_blue" valign="top"><IMG SRC="images/distribute_img.gif"></TD>
					<TD width="100%" class="notice_blue">1) <b><span class="font_orange">SMS ���񽺴� ���� ���񽺷μ� �Ӵϸ� �����ϼž߸� �̿��� �����մϴ�.</b></span><br>2) ȸ�� ��ȭ��ȣ�� SMS �߼۽� ȸ����ȭ��ȣ�� ������ ��ȣ�̴� ������ �޴��� ��ȣ�� �Է��Ͻñ⸦ �����մϴ�.<br>3) ������ �޴��� ��ȣ�� �����ڿ��� SMS �߼۽� �ʿ������� �Է��� �ּ���.<br>4) <b>�ο�� �޴��� ��ȣ�� �Է��Ͻø� �����ڿ��� SMS �߼۽� ���ÿ� �߼��� �˴ϴ�.</b><br>5) SMS �ӽ��ߴ� ����� �ش� �ð����� SMS�� �߼��� �ȵǸ�, �߼��� �ȵǾ��� �޼������� �ӽ��ߴ��� ����� �� �ϰ� �߼۵˴ϴ�.</TD>
					<TD background="images/distribute_07.gif"></TD>
				</TR>
				<TR>
					<TD><IMG SRC="images/distribute_08.gif" WIDTH=7 HEIGHT=8 ALT=""></TD>
					<TD COLSPAN=2 background="images/distribute_09.gif"></TD>
					<TD><IMG SRC="images/distribute_10.gif" WIDTH=8 HEIGHT=8 ALT=""></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr>
				<td height=3></td>
			</tr>
			<tr>
				<td>
				<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
				<TR>
					<TD background="images/table_top_line.gif" colspan=2></TD>
				</TR>
				<TR>
					<TD class="table_cell" width="139"><img src="images/icon_point2.gif" width="8" height="11" border="0">SMS ���̵�</TD>
					<TD class="td_con1"><INPUT maxLength=20 size=40 name=sms_id value="<?=$row->id?>" class="input" style=width:30%>&nbsp;<span class="font_orange">��SMS ���Խ� ��û�Ͻ� ���̵� �Է��ϼ���.</span></TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell" width="139"><img src="images/icon_point2.gif" width="8" height="11" border="0">SMS ����Ű</TD>
					<TD class="td_con1"><INPUT maxLength=32 size=40 name=sms_authkey value="<?=$row->authkey?>" class="input" style=width:30%>&nbsp;<span class="font_orange">��SMS ȸ�� ����Ű�� ��Ȯ�� �Է��ϼ���.</span></TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell" width="139"><img src="images/icon_point2.gif" width="8" height="11" border="0">���θ� ������</TD>
					<TD class="td_con1"><INPUT maxLength=20 size=40 name=sms_uname value="<?=$row->sms_uname?>" class="input" style=width:30%></TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell" width="139"><img src="images/icon_point2.gif" width="8" height="11" border="0">ȸ�� ��ȭ��ȣ</TD>
					<TD class="td_con1">
					<INPUT onkeyup="return strnumkeyup(this);" maxLength=3 size=5 name=return_tel1 value="<?=$return_tel[0]?>" class="input"> -
					<INPUT onkeyup="return strnumkeyup(this);" maxLength=4 size=5 name=return_tel2 value="<?=$return_tel[1]?>" class="input"> -
					<INPUT onkeyup="return strnumkeyup(this);" maxLength=4 size=5 name=return_tel3 value="<?=$return_tel[2]?>" class="input">&nbsp;<span class="font_orange">��SMS �߼۽� <B>�⺻ ȸ�Ź�ȣ</B>�� �����˴ϴ�.<br/>���������� ���񽺸� ���ؼ���  ���ھȳ� �� ǥ��Ǵ� ȸ�� ��ȭ��ȣ�� ���� �ǻ���� �̿������ �ʿ��մϴ�.<br/>��ȸ����ȭ��ȣ ���� ��Ż��� �̿������ ������ ���� �� �ѽ��� �����ּ���.(070-7585-3299)</span>
					</TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell" width="139"><img src="images/icon_point2.gif" width="8" height="11" border="0">������ �޴��� ��ȣ</TD>
					<TD class="td_con1">
					<INPUT onkeyup="return strnumkeyup(this);" maxLength=3 size=5 name=admin_tel1 value="<?=$admin_tel[0]?>" class="input"> -
					<INPUT onkeyup="return strnumkeyup(this);" maxLength=4 size=5 name=admin_tel2 value="<?=$admin_tel[1]?>" class="input"> -
					<INPUT onkeyup="return strnumkeyup(this);" maxLength=4 size=5 name=admin_tel3 value="<?=$admin_tel[2]?>" class="input">
					</TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<tr>
					<TD class="table_cell" width="139"><img src="images/icon_point2.gif" width="8" height="11" border="0">�ο��1 �޴��� ��ȣ</TD>
					<TD class="td_con1">
					<INPUT onkeyup="return strnumkeyup(this);" maxLength=3 size=5 name=subadmin1_tel1 value="<?=$subadmin1_tel[0]?>" class="input"> -
					<INPUT onkeyup="return strnumkeyup(this);" maxLength=4 size=5 name=subadmin1_tel2 value="<?=$subadmin1_tel[1]?>" class="input"> -
					<INPUT onkeyup="return strnumkeyup(this);" maxLength=4 size=5 name=subadmin1_tel3 value="<?=$subadmin1_tel[2]?>" class="input">
					</TD>
				</tr>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<tr>
					<TD class="table_cell" width="139"><img src="images/icon_point2.gif" width="8" height="11" border="0">�ο��2 �޴��� ��ȣ</TD>
					<TD class="td_con1">
					<INPUT onkeyup="return strnumkeyup(this);" maxLength=3 size=5 name=subadmin2_tel1 value="<?=$subadmin2_tel[0]?>" class="input"> -
					<INPUT onkeyup="return strnumkeyup(this);" maxLength=4 size=5 name=subadmin2_tel2 value="<?=$subadmin2_tel[1]?>" class="input"> -
					<INPUT onkeyup="return strnumkeyup(this);" maxLength=4 size=5 name=subadmin2_tel3 value="<?=$subadmin2_tel[2]?>" class="input">
					</TD>
				</tr>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<tr>
					<TD class="table_cell" width="139"><img src="images/icon_point2.gif" width="8" height="11" border="0">�ο��3 �޴��� ��ȣ</TD>
					<TD class="td_con1">
					<INPUT onkeyup="return strnumkeyup(this);" maxLength=3 size=5 name=subadmin3_tel1 value="<?=$subadmin3_tel[0]?>" class="input"> -
					<INPUT onkeyup="return strnumkeyup(this);" maxLength=4 size=5 name=subadmin3_tel2 value="<?=$subadmin3_tel[1]?>" class="input"> -
					<INPUT onkeyup="return strnumkeyup(this);" maxLength=4 size=5 name=subadmin3_tel3 value="<?=$subadmin3_tel[2]?>" class="input">
					</TD>
				</tr>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<tr>
					<TD class="table_cell" width="139"><img src="images/icon_point2.gif" width="8" height="11" border="0">SMS �ӽ��ߴ�</TD>
					<TD class="td_con1">
					<INPUT style="BORDER-RIGHT: medium none; BORDER-TOP: medium none; BORDER-LEFT: medium none; BORDER-BOTTOM: medium none" onclick=CheckSleepTime(true) type=radio value=Y name=check_sleep_time <?=($check_sleep_time=="Y"?"checked":"")?>>�������  &nbsp;&nbsp;
					<INPUT style="BORDER-RIGHT: medium none; BORDER-TOP: medium none; BORDER-LEFT: medium none; BORDER-BOTTOM: medium none" onclick=CheckSleepTime(false) type=radio value=N name=check_sleep_time <?=($check_sleep_time=="N"?"checked":"")?>>����
					<SELECT name=sleep_time1 class="select" style=width:70px>
<?
					for($i=0;$i<24;$i++){
						echo "<option value='".$i."'";
						if($i==$sleep_time1) echo " selected";
						echo ">".($i>12?"pm":"am")." ".substr("0".$i,-2)."</option>";
					}
?>
					</SELECT>
					�� ����
					<SELECT name=sleep_time2 class="select"  style=width:70px>
<?
					for($i=0;$i<24;$i++){
						echo "<option value='".$i."'";
						if($i==$sleep_time2) echo " selected";
						echo ">".($i>12?"pm":"am")." ".substr("0".$i,-2)."</option>";
					}
?>
					</SELECT>
					�� ����
					<?if($check_sleep_time=="Y")echo"<script>CheckSleepTime(true);</script>\n"; ?>
					</TD>
				</tr>
				<TR>
					<TD background="images/table_top_line.gif" colspan=2></TD>
				</TR>
				<tr>
					<TD class="table_cell" width="139"><img src="images/icon_point2.gif" width="8" height="11" border="0">MMS���</TD>
					<TD class="td_con1">
					<INPUT type="radio" name="use_mms" value="N" <?if($row->use_mms=="N") echo "checked"?>>������
					<INPUT type="radio" name="use_mms" value="Y" <?if($row->use_mms=="Y") echo "checked"?>>�����
					</TD>
				</tr>
				<TR>
					<TD background="images/table_top_line.gif" colspan=2></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr><td height="30">&nbsp;</td></tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/market_smsconfig_stitle2.gif" ALT=""></TD>
					<TD width="100%" background="images/shop_basicinfo_stitle_bg.gif"></TD>
					<TD><IMG SRC="images/shop_basicinfo_stitle_end.gif" WIDTH=10 HEIGHT=31 ALT=""></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr><td height=3></td></tr>
			<tr>
				<td>
				<table cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td width="262" valign="top">
					<table align="center" cellpadding="0" cellspacing="0" width="200">
					<tr>
						<td height="23"><INPUT style="BORDER-RIGHT: medium none; BORDER-TOP: medium none; BORDER-LEFT: medium none; BORDER-BOTTOM: medium none" type=checkbox value=Y name=mem_join <?if($row->mem_join=="Y") echo "checked"?>>ȸ������ ���ϸ޼���</td>
					</tr>
					<tr>
						<td>
						<TABLE WIDTH=200 BORDER=0 CELLPADDING=0 CELLSPACING=0 align="center">
						<TR>
							<TD><IMG SRC="images/sms_top_01.gif" WIDTH=200 HEIGHT="30" ALT=""></TD>
						</TR>
						<TR>
							<TD align=center height="90" background="images/sms_bg.gif" valign="top"><TEXTAREA class="textarea_hide" onkeyup="cal_pre2('mem_join',true);" name=msg_mem_join rows=5 cols="26" onchange="cal_pre2('mem_join',true);"><?=$row->msg_mem_join?></TEXTAREA></TD>
						</TR>
						<TR>
							<TD align=center height="26" background="images/sms_down_01.gif">
							<INPUT style="PADDING-RIGHT:5px; WIDTH:20px; TEXT-ALIGN:right" onfocus=this.blur(); value=0 name=len_mem_join size="3" class="input_hide">bytes (�ִ�80 bytes)
							<SCRIPT>cal_pre2('mem_join',false);</SCRIPT>
							</TD>
						</TR>
						</TABLE>
						</td>
					</tr>
					</table>
					</td>
					<td width="262" valign="top">
					<table align="center" cellpadding="0" cellspacing="0" width="200">
					<tr>
						<td height="23"><INPUT style="BORDER-RIGHT: medium none; BORDER-TOP: medium none; BORDER-LEFT: medium none; BORDER-BOTTOM: medium none" type=checkbox value=Y name=mem_order <?if($row->mem_order=="Y") echo "checked"?>>��ǰ�ֹ� �ȳ��޼���</td>
					</tr>
					<tr>
						<td>
						<TABLE WIDTH=200 BORDER=0 CELLPADDING=0 CELLSPACING=0 align="center">
						<TR>
							<TD><IMG SRC="images/sms_top_01.gif" WIDTH=200 HEIGHT="30" ALT=""></TD>
						</TR>
						<TR>
							<TD align=center height="90" background="images/sms_bg.gif" valign="top"><TEXTAREA class="textarea_hide" onkeyup="cal_pre2('mem_order',true);" name=msg_mem_order rows=5 cols="26" onchange="cal_pre2('mem_order',true);"><?=$row->msg_mem_order?></TEXTAREA></TD>
						</TR>
						<TR>
							<TD align=center height="26" background="images/sms_down_01.gif">
							<INPUT style="PADDING-RIGHT:5px; WIDTH:20px; TEXT-ALIGN:right" onfocus=this.blur(); value=0 name=len_mem_order size="3" class="input_hide">bytes (�ִ�80 bytes)
							<SCRIPT>cal_pre2('mem_order',false);</SCRIPT>
							</TD>
						</TR>
						</TABLE>
						</td>
					</tr>
					</table>
					</td>
					<td width="262" valign="top">
					<table align="center" cellpadding="0" cellspacing="0" width="200">
					<tr>
						<td height="23"><INPUT style="BORDER-RIGHT: medium none; BORDER-TOP: medium none; BORDER-LEFT: medium none; BORDER-BOTTOM: medium none" type=checkbox value=Y name=mem_delivery <?if($row->mem_delivery=="Y") echo "checked"?>>��ǰ�߼� �ȳ��޼���</td>
					</tr>
					<tr>
						<td>
						<TABLE WIDTH=200 BORDER=0 CELLPADDING=0 CELLSPACING=0 align="center">
						<TR>
							<TD><IMG SRC="images/sms_top_01.gif" WIDTH=200 HEIGHT="30" ALT=""></TD>
						</TR>
						<TR>
							<TD align=center height="90" background="images/sms_bg.gif" valign="top"><TEXTAREA class="textarea_hide" onkeyup="cal_pre2('mem_delivery',true);" name=msg_mem_delivery rows=5 cols="26" onchange="cal_pre2('mem_delivery',true);"><?=$row->msg_mem_delivery?></TEXTAREA></TD>
						</TR>
						<TR>
							<TD align=center height="26" background="images/sms_down_01.gif">
							<INPUT style="PADDING-RIGHT:5px; WIDTH:20px; TEXT-ALIGN:right" onfocus=this.blur(); value=0 name=len_mem_delivery size="3" class="input_hide">bytes (�ִ�80 bytes)
							<SCRIPT>cal_pre2('mem_delivery',false);</SCRIPT>
							</TD>
						</TR>
						</TABLE>
						</td>
					</tr>
					</table>
					</td>
				</tr>
				<tr>
					<td width="262" valign="top">&nbsp;</td>
					<td width="262" valign="top">&nbsp;</td>
					<td width="262" valign="top">&nbsp;</td>
				</tr>
				<tr>
					<td width="262" height="85" valign="top">
					<table align="center" cellpadding="0" cellspacing="0" width="200">
					<tr>
						<td height="23"><INPUT style="BORDER-RIGHT: medium none; BORDER-TOP: medium none; BORDER-LEFT: medium none; BORDER-BOTTOM: medium none" type=checkbox value=Y name=mem_delinum <?if($row->mem_delinum=="Y") echo "checked"?>>�����ȣ �ȳ��޼���</td>
					</tr>
					<tr>
						<td>
						<TABLE WIDTH=200 BORDER=0 CELLPADDING=0 CELLSPACING=0 align="center">
						<TR>
							<TD><IMG SRC="images/sms_top_01.gif" WIDTH=200 HEIGHT="30" ALT=""></TD>
						</TR>
						<TR>
							<TD align=center height="90" background="images/sms_bg.gif" valign="top"><TEXTAREA class="textarea_hide" onkeyup="cal_pre2('mem_delinum',true);" name=msg_mem_delinum rows=5 cols="26" onchange="cal_pre2('mem_delinum',true);"><?=$row->msg_mem_delinum?></TEXTAREA></TD>
						</TR>
						<TR>
							<TD align=center height="26" background="images/sms_down_01.gif">
							<INPUT style="PADDING-RIGHT:5px; WIDTH:20px; TEXT-ALIGN:right" onfocus=this.blur(); value=0 name=len_mem_delinum size="3" class="input_hide">bytes (�ִ�80 bytes)
							<SCRIPT>cal_pre2('mem_delinum',false);</SCRIPT>
							</TD>
						</TR>
						</TABLE>
						</td>
					</tr>
					<tr>
						<td class="font_blue1">* �ù�ȸ��/�����ȣ�� ��ǰ�߼۽� ������<br>&nbsp;&nbsp;�ڵ� �ȳ�</td>
					</tr>
					</table>
					</td>
					<td width="262" height="85" valign="top">
					<table align="center" cellpadding="0" cellspacing="0" width="200">
					<tr>
						<td height="23"><INPUT style="BORDER-RIGHT: medium none; BORDER-TOP: medium none; BORDER-LEFT: medium none; BORDER-BOTTOM: medium none" type=checkbox value=Y name=mem_bank <?if($row->mem_bank=="Y") echo "checked"?>>�������Ա� �ȳ��޼���</td>
					</tr>
					<tr>
						<td>
						<TABLE WIDTH=200 BORDER=0 CELLPADDING=0 CELLSPACING=0 align="center">
						<TR>
							<TD><IMG SRC="images/sms_top_01.gif" WIDTH=200 HEIGHT="30" ALT=""></TD>
						</TR>
						<TR>
							<TD align=center height="90" background="images/sms_bg.gif" valign="top"><TEXTAREA class="textarea_hide" onkeyup="cal_pre2('mem_bank',true);" name=msg_mem_bank rows=5 cols="26" onchange="cal_pre2('mem_bank',true);"><?=$row->msg_mem_bank?></TEXTAREA></TD>
						</TR>
						<TR>
							<TD align=center height="26" background="images/sms_down_01.gif">
							<INPUT style="PADDING-RIGHT:5px; WIDTH:20px; TEXT-ALIGN:right" onfocus=this.blur(); value=0 name=len_mem_bank size="3" class="input_hide">bytes (�ִ�80 bytes)
							<SCRIPT>cal_pre2('mem_bank',false);</SCRIPT>
							</TD>
						</TR>
						</TABLE>
						</td>
					</tr>
					<tr>
						<td class="font_blue1">* ���¹�ȣ�� ��ǰ�ֹ���, ���� ������<br>&nbsp; ���¹�ȣ �ȳ�</td>
					</tr>
					</table>
					</td>
					<td width="262" height="85" valign="top">
					<table align="center" cellpadding="0" cellspacing="0" width="200">
					<tr>
						<td height="23"><INPUT style="BORDER-RIGHT: medium none; BORDER-TOP: medium none; BORDER-LEFT: medium none; BORDER-BOTTOM: medium none" type=checkbox value=Y name=mem_bankok <?if($row->mem_bankok=="Y") echo "checked"?>>�Ա�Ȯ�� �ȳ��޼���
						<br>
						<INPUT style="BORDER-RIGHT: medium none; BORDER-TOP: medium none; BORDER-LEFT: medium none; BORDER-BOTTOM: medium none" type=checkbox value=Y name=mem_bankokvender <?if($row->mem_bankokvender=="Y") echo "checked"?>>�����翡�� �޼��� �߼�</td>
					</tr>
					<tr>
						<td>
						<TABLE WIDTH=200 BORDER=0 CELLPADDING=0 CELLSPACING=0 align="center">
						<TR>
							<TD><IMG SRC="images/sms_top_01.gif" WIDTH=200 HEIGHT="30" ALT=""></TD>
						</TR>
						<TR>
							<TD align=center height="90" background="images/sms_bg.gif" valign="top"><TEXTAREA class="textarea_hide" onkeyup="cal_pre2('mem_bankok',true);" name=msg_mem_bankok rows=5 cols="26" onchange="cal_pre2('mem_bankok',true);"><?=$row->msg_mem_bankok?></TEXTAREA></TD>
						</TR>
						<TR>
							<TD align=center height="26" background="images/sms_down_01.gif">
							<INPUT style="PADDING-RIGHT:5px; WIDTH:20px; TEXT-ALIGN:right" onfocus=this.blur(); value=0 name=len_mem_bankok size="3" class="input_hide">bytes (�ִ�80 bytes)
							<SCRIPT>cal_pre2('mem_bankok',false);</SCRIPT>
							</TD>
						</TR>
						</TABLE>
						</td>
					</tr>
					</table>
					</td>
				</tr>
				<tr>
					<td width="262" height="18" valign="top">&nbsp;</td>
					<td width="262" height="18" valign="top">&nbsp;</td>
					<td width="262" height="18" valign="top">&nbsp;</td>
				</tr>
				<tr>
					<td width="262" height="35" valign="top">
					<table align="center" cellpadding="0" cellspacing="0" width="200">
					<tr>
						<td height="23"><INPUT style="BORDER-RIGHT: medium none; BORDER-TOP: medium none; BORDER-LEFT: medium none; BORDER-BOTTOM: medium none" type=checkbox value=Y name=mem_birth <?if($row->mem_birth=="Y") echo "checked"?>>����ȸ�� �ڵ��޼���</td>
					</tr>
					<tr>
						<td>
						<TABLE WIDTH=200 BORDER=0 CELLPADDING=0 CELLSPACING=0 align="center">
						<TR>
							<TD><IMG SRC="images/sms_top_01.gif" WIDTH=200 HEIGHT="30" ALT=""></TD>
						</TR>
						<TR>
							<TD align=center height="90" background="images/sms_bg.gif" valign="top"><TEXTAREA class="textarea_hide" onkeyup="cal_pre2('mem_birth',true);" name=msg_mem_birth rows=5 cols="26" onchange="cal_pre2('mem_birth',true);"><?=$row->msg_mem_birth?></TEXTAREA></TD>
						</TR>
						<TR>
							<TD align=center height="26" background="images/sms_down_01.gif">
							<INPUT style="PADDING-RIGHT:5px; WIDTH:20px; TEXT-ALIGN:right" onfocus=this.blur(); value=0 name=len_mem_birth size="3" class="input_hide">bytes (�ִ�80 bytes)
							<SCRIPT>cal_pre2('mem_birth',false);</SCRIPT>
							</TD>
						</TR>
						</TABLE>
						</td>
					</tr>
					</table>
					</td>
					<td width="262" height="35" valign="top">
					<table align="center" cellpadding="0" cellspacing="0" width="200">
					<tr>
						<td height="23"><INPUT style="BORDER-RIGHT: medium none; BORDER-TOP: medium none; BORDER-LEFT: medium none; BORDER-BOTTOM: medium none" type=checkbox value=Y name=mem_auth <?if($row->mem_auth=="Y") echo "checked"?>>ȸ������ �ȳ��޼���</td>
					</tr>
					<tr>
						<td>
						<TABLE WIDTH=200 BORDER=0 CELLPADDING=0 CELLSPACING=0 align="center">
						<TR>
							<TD><IMG SRC="images/sms_top_01.gif" WIDTH=200 HEIGHT="30" ALT=""></TD>
						</TR>
						<TR>
							<TD align=center height="90" background="images/sms_bg.gif" valign="top"><TEXTAREA class="textarea_hide" onkeyup="cal_pre2('mem_auth',true);" name=msg_mem_auth rows=5 cols="26" onchange="cal_pre2('mem_auth',true);"><?=$row->msg_mem_auth?></TEXTAREA></TD>
						</TR>
						<TR>
							<TD align=center height="26" background="images/sms_down_01.gif">
							<INPUT style="PADDING-RIGHT:5px; WIDTH:20px; TEXT-ALIGN:right" onfocus=this.blur(); value=0 name=len_mem_auth size="3" class="input_hide">bytes (�ִ�80 bytes)
							<SCRIPT>cal_pre2('mem_auth',false);</SCRIPT></TD>
						</TR>
						</TABLE>
						</td>
					</tr>
					</table>
					</td>
					<td width="262" height="35" valign="top">
					<table align="center" cellpadding="0" cellspacing="0" width="200">
					<tr>
						<td height="23"><INPUT style="BORDER-RIGHT: medium none; BORDER-TOP: medium none; BORDER-LEFT: medium none; BORDER-BOTTOM: medium none" type=checkbox value=Y name=mem_passwd <?if($row->mem_passwd=="Y") echo "checked"?>>��й�ȣ �н� �ȳ��޼���</td>
					</tr>
					<tr>
						<td>
						<TABLE WIDTH=200 BORDER=0 CELLPADDING=0 CELLSPACING=0 align="center">
						<TR>
							<TD><IMG SRC="images/sms_top_01.gif" WIDTH=200 HEIGHT="30" ALT=""></TD>
						</TR>
						<TR>
							<TD align=center height="90" background="images/sms_bg.gif" valign="top"><TEXTAREA class="textarea_hide" onkeyup="cal_pre2('mem_passwd',true);" name=msg_mem_passwd rows=5 cols="26" onchange="cal_pre2('mem_passwd',true);"><?=$msg_list[mem_passwd]?></TEXTAREA></TD>
						</TR>
						<TR>
							<TD align=center height="26" background="images/sms_down_01.gif">
							<INPUT style="PADDING-RIGHT:5px; WIDTH:20px; TEXT-ALIGN:right" onfocus=this.blur(); value=0 name=len_mem_passwd size="3" class="input_hide">bytes (�ִ�80 bytes)
							<SCRIPT>cal_pre2('mem_passwd',false);</SCRIPT>
							</TD>
						</TR>
						</TABLE>
						</td>
					</tr>
					</table>
					</td>
				</tr>
				<tr>
					<td width="262" height="18" valign="top">&nbsp;</td>
					<td width="262" height="18" valign="top">&nbsp;</td>
					<td width="262" height="18" valign="top">&nbsp;</td>
				</tr>
				<tr>
					<td width="262" height="35" valign="top">
					<table align="center" cellpadding="0" cellspacing="0" width="200">
					<tr>
						<td height="23"><INPUT style="BORDER-RIGHT: medium none; BORDER-TOP: medium none; BORDER-LEFT: medium none; BORDER-BOTTOM: medium none" type=checkbox value=Y name=mem_gift <?if($row->mem_gift=="Y") echo "checked"?>>��ǰ�Ǽ����ϱ� </td>
					</tr>
					<tr>
						<td>
						<TABLE WIDTH=200 BORDER=0 CELLPADDING=0 CELLSPACING=0 align="center">
						<TR>
							<TD><IMG SRC="images/sms_top_01.gif" WIDTH=200 HEIGHT="30" ALT=""></TD>
						</TR>
						<TR>
							<TD align=center height="90" background="images/sms_bg.gif" valign="top"><TEXTAREA class="textarea_hide" onkeyup="cal_pre3('mem_gift',true);" name=msg_mem_gift rows=5 cols="26" onchange="cal_pre3('mem_gift',true);"><?=$row->msg_mem_gift?></TEXTAREA></TD>
						</TR>
						<TR>
							<TD align=center height="26" background="images/sms_down_01.gif">
							<INPUT style="PADDING-RIGHT:5px; WIDTH:20px; TEXT-ALIGN:right" onfocus=this.blur(); value=0 name=len_mem_gift size="3" class="input_hide">bytes
							<SCRIPT>cal_pre3('mem_gift',false);</SCRIPT>
							</TD>
						</TR>
						</TABLE>
						</td>
					  </tr>
					</table>
					</td>
					<td width="262" height="35" valign="top">
					<table align="center" cellpadding="0" cellspacing="0" width="200">
					<tr>
						<td height="23"><INPUT style="BORDER-RIGHT: medium none; BORDER-TOP: medium none; BORDER-LEFT: medium none; BORDER-BOTTOM: medium none" type=checkbox value=Y name=socialshopping <?if($row->socialshopping=="Y") echo "checked"?>>�Ҽȼ��� ���Ŵ޼� ���н� ������Ҹ޼��� </td>
					</tr>
					<tr>
						<td>
						<TABLE WIDTH=200 BORDER=0 CELLPADDING=0 CELLSPACING=0 align="center">
						<TR>
							<TD><IMG SRC="images/sms_top_01.gif" WIDTH=200 HEIGHT="30" ALT=""></TD>
						</TR>
						<TR>
							<TD align=center height="90" background="images/sms_bg.gif" valign="top"><TEXTAREA class="textarea_hide" onkeyup="cal_pre3('socialshopping',true);" name=msg_socialshopping rows=5 cols="26" onchange="cal_pre3('socialshopping',true);"><?=$row->msg_socialshopping?></TEXTAREA></TD>
						</TR>
						<TR>
							<TD align=center height="26" background="images/sms_down_01.gif">
							<INPUT style="PADDING-RIGHT:5px; WIDTH:20px; TEXT-ALIGN:right" onfocus=this.blur(); value=0 name=len_socialshopping size="3" class="input_hide">bytes
							<SCRIPT>cal_pre3('socialshopping',false);</SCRIPT>
							</TD>
						</TR>
						</TABLE>
						</td>
					  </tr>
					</table>
					</td>
					<td width="262" height="35" valign="top">
					<table align="center" cellpadding="0" cellspacing="0" width="200">
					<tr>
						<td height="23"><INPUT style="BORDER-RIGHT: medium none; BORDER-TOP: medium none; BORDER-LEFT: medium none; BORDER-BOTTOM: medium none" type=checkbox value=Y name=product_hongbo <?if($row->product_hongbo=="Y") echo "checked"?>>��ǰȫ���޼��� </td>
					</tr>
					<tr>
						<td>
						<TABLE WIDTH=200 BORDER=0 CELLPADDING=0 CELLSPACING=0 align="center">
						<TR>
							<TD><IMG SRC="images/sms_top_01.gif" WIDTH=200 HEIGHT="30" ALT=""></TD>
						</TR>
						<TR>
							<TD align=center height="90" background="images/sms_bg.gif" valign="top"><TEXTAREA class="textarea_hide" onkeyup="cal_pre3('product_hongbo',true);" name=msg_product_hongbo rows=5 cols="26" onchange="cal_pre3('product_hongbo',true);"><?=$msg_list[product_hongbo]?></TEXTAREA></TD>
						</TR>
						<TR>
							<TD align=center height="26" background="images/sms_down_01.gif">
							<INPUT style="PADDING-RIGHT:5px; WIDTH:20px; TEXT-ALIGN:right" onfocus=this.blur(); value=0 name=len_product_hongbo size="3" class="input_hide">bytes
							<SCRIPT>cal_pre3('product_hongbo',false);</SCRIPT>
							</TD>
						</TR>
						</TABLE>
						</td>
					  </tr>
					</table>
					</td>
				</tr>
				<tr>
					<td width="262" height="18" valign="top">&nbsp;</td>
					<td width="262" height="18" valign="top">&nbsp;</td>
					<td width="262" height="18" valign="top">&nbsp;</td>
				</tr>
				<tr>
					<td width="262" height="35" valign="top">
					<table align="center" cellpadding="0" cellspacing="0" width="200">
					<tr>
						<td height="23"><INPUT style="BORDER-RIGHT: medium none; BORDER-TOP: medium none; BORDER-LEFT: medium none; BORDER-BOTTOM: medium none" type=checkbox value=Y name=mem_present <?if($row->mem_present=="Y") echo "checked"?>>��ǰ �����ϱ� �޼���</td>
					</tr>
					<tr>
						<td>
						<TABLE WIDTH=200 BORDER=0 CELLPADDING=0 CELLSPACING=0 align="center">
						<TR>
							<TD><IMG SRC="images/sms_top_01.gif" WIDTH=200 HEIGHT="30" ALT=""></TD>
						</TR>
						<TR>
							<TD align=center height="90" background="images/sms_bg.gif" valign="top"><TEXTAREA class="textarea_hide" onkeyup="cal_pre3('mem_present',true);" name=msg_mem_present rows=5 cols="26" onchange="cal_pre3('mem_present',true);"><?=$row->msg_mem_present?></TEXTAREA></TD>
						</TR>
						<TR>
							<TD align=center height="26" background="images/sms_down_01.gif">
							<INPUT style="PADDING-RIGHT:5px; WIDTH:20px; TEXT-ALIGN:right" onfocus=this.blur(); value=0 name=len_mem_present size="3" class="input_hide">bytes
							<SCRIPT>cal_pre3('mem_present',false);</SCRIPT>
							</TD>
						</TR>
						</TABLE>
						</td>
					  </tr>
					</table>
					</td>
					<td width="262" height="35" valign="top">
<? if($_shopdata->pester_state == "Y"){?>
					<table align="center" cellpadding="0" cellspacing="0" width="200">
					<tr>
						<td height="23"><INPUT style="BORDER-RIGHT: medium none; BORDER-TOP: medium none; BORDER-LEFT: medium none; BORDER-BOTTOM: medium none" type=checkbox value=Y name=mem_pester <?if($row->mem_pester=="Y") echo "checked"?>>�������û �޼���</td>
					</tr>
					<tr>
						<td>
						<TABLE WIDTH=200 BORDER=0 CELLPADDING=0 CELLSPACING=0 align="center">
						<TR>
							<TD><IMG SRC="images/sms_top_01.gif" WIDTH=200 HEIGHT="30" ALT=""></TD>
						</TR>
						<TR>
							<TD align=center height="90" background="images/sms_bg.gif" valign="top"><TEXTAREA class="textarea_hide" onkeyup="cal_pre3('mem_pester',true);" name=msg_mem_pester rows=5 cols="26" onchange="cal_pre3('mem_pester',true);"><?=$row->msg_mem_pester?></TEXTAREA></TD>
						</TR>
						<TR>
							<TD align=center height="26" background="images/sms_down_01.gif">
							<INPUT style="PADDING-RIGHT:5px; WIDTH:20px; TEXT-ALIGN:right" onfocus=this.blur(); value=0 name=len_mem_pester size="3" class="input_hide">bytes
							<SCRIPT>cal_pre3('mem_pester',false);</SCRIPT>
							</TD>
						</TR>
						</TABLE>
						</td>
					  </tr>
					</table>
<?}?>
					</td>
					<td width="262" height="35" valign="top"></td>
				</tr>
				</table>
				</td>
			</tr>
			<tr>
				<td height="30">&nbsp;</td>
			</tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/market_smsconfig_stitle3.gif" ALT=""></TD>
					<TD width="100%" background="images/shop_basicinfo_stitle_bg.gif"></TD>
					<TD><IMG SRC="images/shop_basicinfo_stitle_end.gif" WIDTH=10 HEIGHT=31 ALT=""></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr>
				<td style="padding-top:3px; padding-bottom:3px;">
					<table cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td width="262" valign="top">
						<table align="center" cellpadding="0" cellspacing="0" width="200">
						<tr>
							<td height="23"><INPUT style="BORDER-RIGHT: medium none; BORDER-TOP: medium none; BORDER-LEFT: medium none; BORDER-BOTTOM: medium none" type=checkbox value=Y name=admin_join <?if($row->admin_join=="Y") echo "checked"?>>ȸ������ �뺸�޼���</td>
						</tr>
						<tr>
							<td>
							<TABLE WIDTH=200 BORDER=0 CELLPADDING=0 CELLSPACING=0 align="center">
							<TR>
								<TD><IMG SRC="images/sms_top_01.gif" WIDTH=200 HEIGHT="30" ALT=""></TD>
							</TR>
							<TR>
								<TD align=center height="90" background="images/sms_bg.gif" valign="top">
									<TEXTAREA class="textarea_hide" onkeyup="cal_pre2('admin_join',true);" name=msg_admin_join rows=5 cols="26" onchange="cal_pre2('admin_join',true);"><?=$msg_list[admin_join]?></TEXTAREA>
								</TD>
							</TR>
							<TR>
								<TD align=center height="26" background="images/sms_down_01.gif">
								<INPUT style="PADDING-RIGHT:5px; WIDTH:20px; TEXT-ALIGN:right" onfocus=this.blur(); value=0 name=len_admin_join size="3" class="input_hide">bytes (�ִ�80 bytes)
								<SCRIPT>cal_pre2('admin_join',false);</SCRIPT>
								</TD>
							</TR>
							</TABLE>
							</td>
						</tr>
						</table>
						</td>
						<td width="262" valign="top">
						<table align="center" cellpadding="0" cellspacing="0" width="200">
						<tr>
							<td height="23">
								<INPUT style="BORDER-RIGHT: medium none; BORDER-TOP: medium none; BORDER-LEFT: medium none; BORDER-BOTTOM: medium none" type=checkbox value=Y name=admin_order <?if($row->admin_order=="Y") echo "checked"?>>��ǰ�ֹ� �뺸�޼���<br />
								<INPUT style="BORDER-RIGHT: medium none; BORDER-TOP: medium none; BORDER-LEFT: medium none; BORDER-BOTTOM: medium none" type=checkbox value=Y name=vender_order <?if($row->vender_order=="Y") echo "checked"?>>���� ��ǰ�ֹ� �뺸�޼���
							</td>
						</tr>
						<tr>
							<td>
							<TABLE WIDTH=200 BORDER=0 CELLPADDING=0 CELLSPACING=0 align="center">
							<TR>
								<TD><IMG SRC="images/sms_top_01.gif" WIDTH=200 HEIGHT="30" ALT=""></TD>
							</TR>
							<TR>
								<TD align=center height="90" background="images/sms_bg.gif" valign="top"><TEXTAREA class="textarea_hide" onkeyup="cal_pre2('admin_order',true);" name=msg_admin_order rows=5 cols="26" onchange="cal_pre2('admin_order',true);"><?=$msg_list[admin_order]?></TEXTAREA></TD>
							</TR>
							<TR>
								<TD align=center height="26" background="images/sms_down_01.gif">
								<INPUT style="PADDING-RIGHT:5px; WIDTH:20px; TEXT-ALIGN:right" onfocus=this.blur(); value=0 name=len_admin_order size="3" class="input_hide">bytes (�ִ�80 bytes)
								<SCRIPT>cal_pre2('admin_order',false);</SCRIPT>
								</TD>
							</TR>
							</TABLE>
							</td>
						</tr>
						</table>
						</td>
						<td width="262" valign="top">
						<table align="center" cellpadding="0" cellspacing="0" width="200">
						<tr>
							<td height="23"><INPUT style="BORDER-RIGHT: medium none; BORDER-TOP: medium none; BORDER-LEFT: medium none; BORDER-BOTTOM: medium none" type=checkbox value=Y name=admin_cancel <?if($row->admin_cancel=="Y") echo "checked"?>>�ֹ���� �뺸�޼���</td>
						</tr>
						<tr>
							<td>
							<TABLE WIDTH=200 BORDER=0 CELLPADDING=0 CELLSPACING=0 align="center">
							<TR>
								<TD><IMG SRC="images/sms_top_01.gif" WIDTH=200 HEIGHT="30" ALT=""></TD>
							</TR>
							<TR>
								<TD align=center height="90" background="images/sms_bg.gif" valign="top"><TEXTAREA class="textarea_hide" onkeyup="cal_pre2('admin_cancel',true);" name=msg_admin_cancel rows=5 cols="26" onchange="cal_pre2('admin_cancel',true);"><?=$msg_list[admin_cancel]?></TEXTAREA></TD>
							</TR>
							<TR>
								<TD align=center height="26" background="images/sms_down_01.gif">
								<INPUT style="PADDING-RIGHT:5px; WIDTH:20px; TEXT-ALIGN:right" onfocus=this.blur(); value=41 name=len_admin_cancel size="3" class="input_hide">bytes (�ִ�80 bytes)
								<SCRIPT>cal_pre2('admin_cancel',false);</SCRIPT>
								</TD>
							</TR>
							</TABLE>
							</td>
						</tr>
						</table>
						</td>
					</tr>
					<tr>
						<td width="262" valign="top">&nbsp;</td>
						<td width="262" valign="top">&nbsp;</td>
						<td width="262" valign="top">&nbsp;</td>
					</tr>
					<tr>
						<td width="262" height="85" valign="top">
						<table align="center" cellpadding="0" cellspacing="0" width="200">
						<tr>
							<td height="23"><INPUT style="BORDER-RIGHT: medium none; BORDER-TOP: medium none; BORDER-LEFT: medium none; BORDER-BOTTOM: medium none" type=checkbox value=Y name=admin_soldout <?if($row->admin_soldout=="Y") echo "checked"?>>��ǰǰ�� �뺸�޼���</td>
						</tr>
						<tr>
							<td>
							<TABLE WIDTH=200 BORDER=0 CELLPADDING=0 CELLSPACING=0 align="center">
							<TR>
								<TD><IMG SRC="images/sms_top_01.gif" WIDTH=200 HEIGHT="30" ALT=""></TD>
							</TR>
							<TR>
								<TD align=center height="90" background="images/sms_bg.gif" valign="top"><TEXTAREA class="textarea_hide" onkeyup="cal_pre2('admin_soldout',true);" name=msg_admin_soldout rows=5 cols="26" onchange="cal_pre2('mem_join',true);"><?=$msg_list[admin_soldout]?></TEXTAREA></TD>
							</TR>
							<TR>
								<TD align=center height="26" background="images/sms_down_01.gif">
								<INPUT style="PADDING-RIGHT:5px; WIDTH:20px; TEXT-ALIGN:right" onfocus=this.blur(); value=41 name=len_admin_soldout size="3" class="input_hide">bytes (�ִ�80 bytes)
								<SCRIPT>cal_pre2('admin_soldout',false);</SCRIPT>
								</TD>
							</TR>
							</TABLE>
							</td>
						</tr>
						<tr>
							<td class="font_blue1">* ǰ���� �� ���� ǰ���ø� ����.<br>&nbsp;&nbsp;&nbsp;�ɼǺ� ���� ǰ���� �Ұ���</td>
						</tr>
						</table>
						</td>
						<td width="262" height="85" valign="top">
						<table align="center" cellpadding="0" cellspacing="0" width="200">
						<tr>
							<td height="23"><INPUT style="BORDER-RIGHT: medium none; BORDER-TOP: medium none; BORDER-LEFT: medium none; BORDER-BOTTOM: medium none" type=checkbox value=Y name=admin_board <?if($row->admin_board=="Y") echo "checked"?>>�Խ��� �ű԰Խñ� �뺸�޼���</td>
						</tr>
						<tr>
							<td>
							<TABLE WIDTH=200 BORDER=0 CELLPADDING=0 CELLSPACING=0 align="center">
							<TR>
								<TD><IMG SRC="images/sms_top_01.gif" WIDTH=200 HEIGHT="30" ALT=""></TD>
							</TR>
							<TR>
								<TD align=center height="90" background="images/sms_bg.gif" valign="middle"><TEXTAREA class="textarea_hide" onkeyup="cal_pre2('admin_board',true);" name=msg_admin_board rows=5 cols="26" onchange="cal_pre2('admin_board',true);"><?=$msg_list[admin_board]?></TEXTAREA></TD>
							</TR>
							<TR>
								<TD align=center height="26" background="images/sms_down_01.gif">
								<INPUT style="PADDING-RIGHT:5px; WIDTH:20px; TEXT-ALIGN:right" onfocus=this.blur(); value=41 name=len_admin_board size="3" class="input_hide">bytes (�ִ�80 bytes)
								<SCRIPT>cal_pre2('admin_board',false);</SCRIPT>
								</TD>
							</TR>
							</TABLE>
							</td>
						</tr>
						<tr>
							<td class="font_blue1">* �ű� �Խñ��� ��쿡�� �뺸����<br>&nbsp;&nbsp; (�亯�� ����)</td>
						</tr>
						</table>
						</td>
						<td width="262" height="85" valign="top">&nbsp;</td>
					</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td height="30"><hr size="1" color="#F3F3F3"></td>
			</tr>
			<tr>
				<td align=center><a href="javascript:CheckForm();"><img src="images/botteon_save.gif" width="113" height="38" border="0"></a></td>
			</tr>
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
						<td ><span class="font_dotline">SMS �⺻ ȯ�� ����</span></td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td  class="space_top">- <b><span class="font_orange">SMS�� ���Ἥ�񽺷μ� �̿��� �ݵ�� ������ �ϼž߸� ����� �����մϴ�. </b></span></td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td  class="space_top">- SMS ȸ�� ���ſ� �⺻�޼����� üũ�� �Ͻø� ȸ������ �޼����� �ڵ� �߼۵˴ϴ�.</td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td  class="space_top">- SMS ������ ���ſ� �⺻�޼����� üũ�� �Ͻø� ������ �� �ο�ڿ��� �޼����� �ڵ� �߼۵˴ϴ�.</td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td  class="space_top">- �޼����� 80byte���� �Է� �����Ͽ��� �����ʵ��� �����Ͻñ� �ٶ��ϴ�.</td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td  class="space_top">- �޼��� ������ ��Ÿ��ũ�� �޼��� �߼۽� �ڵ����� �ش� ������ ��������� ����� ����Ͻð� �޼��� �ۼ��� �Ͻñ� �ٶ��ϴ�.</td>
					</tr>
					<tr>
						<td colspan="2" height="20"></td>
					</tr>
					<tr>
						<td width="20" align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td ><font color="black"><b>ȸ������ ���ϸ޼���</b></font></td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td  class="space_top">
						<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
						<TR>
							<TD class="table_cell" style="padding-right:15px; border-top-width:1px; border-top-color:silver; border-top-style:solid;" noWrap align=right width=150 bgColor=#f0f0f0 height="27">[ID]</TD>
							<TD class="td_con1" style="padding-left:5px; border-top-width:1px; border-top-color:silver; border-top-style:solid;" width="100%" height="27">ȸ�� ID�� ����Ǿ� �޼��� ������ �˴ϴ�. (��:hong27)</TD>
						</TR>
						<TR>
							<TD class="table_cell" style="padding-right:15px; border-top-width:1pt; border-bottom-width:1pt; border-top-color:rgb(222,222,222); border-bottom-color:silver; border-top-style:solid; border-bottom-style:solid;" noWrap align=right width=150 bgColor="#F0F0F0" height="27">[NAME]</TD>
							<TD class="td_con1" style="padding-left:5px; border-top-width:1pt; border-bottom-width:1pt; border-top-color:rgb(222,222,222); border-bottom-color:silver; border-top-style:solid; border-bottom-style:solid;" width="100%" height="27">ȸ�� �̸����� ����Ǿ� �޼��� ������ �˴ϴ�. (��:ȫ�浿)</TD>
						</TR>
						</TABLE>
						</td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td  class="space_top">&nbsp;</td>
					</tr>
					<tr>
						<td width="20" align="right"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td ><font color="black"><b>��ǰ�ֹ� �ȳ��޼���</b></font></td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td  class="space_top">
						<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
						<TR>
							<TD class="table_cell" style="padding-right:15px; border-top-width:1px; border-top-color:silver; border-top-style:solid;" noWrap align=right width=150 bgColor=#f0f0f0 height="27">[NAME]</TD>
							<TD class="td_con1" style="padding-left:5px; border-top-width:1px; border-top-color:silver; border-top-style:solid;" width="100%" height="27">ȸ�� �̸����� ����Ǿ� �޼��� ������ �˴ϴ�. (��:ȫ�浿)</TD>
						</TR>
						<TR>
							<TD class="table_cell" style="padding-right:15px; border-top-width:1pt; border-bottom-width:1pt; border-top-color:rgb(222,222,222); border-bottom-color:silver; border-top-style:solid; border-bottom-style:solid;" noWrap align=right width=150 bgColor="#F0F0F0" height="27">[PRODUCT]</TD>
							<TD class="td_con1" style="padding-left:5px; border-top-width:1pt; border-bottom-width:1pt; border-top-color:rgb(222,222,222); border-bottom-color:silver; border-top-style:solid; border-bottom-style:solid;" width="100%" height="27">�ֹ� ��ǰ������ ����Ǿ� �޼��� ������ �˴ϴ�. (��:���Ÿ�� ����LT-3)</TD>
						</TR>
						</TABLE>
						</td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td  class="space_top">&nbsp;</td>
					</tr>
					<tr>
						<td width="20" align="right"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td ><b><font color="black">�������Ա� �ȳ��޼���</font></b></td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td  class="space_top">
						<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
						<TR>
							<TD class="table_cell" style="padding-right:15px; border-top-width:1px; border-top-color:silver; border-top-style:solid;" noWrap align=right width=150 bgColor=#f0f0f0 height="27">[NAME]</TD>
							<TD class="td_con1" style="padding-left:5px; border-top-width:1px; border-top-color:silver; border-top-style:solid;" width="100%">ȸ�� �̸����� ����Ǿ� �޼��� ������ �˴ϴ�. (��:ȫ�浿)</TD>
						</TR>
						<TR>
							<TD class="table_cell" style="padding-right:15px; border-top-width:1px; border-top-color:rgb(222,222,222); border-top-style:solid;" noWrap align=right width=150 bgColor=#f0f0f0 height="27">[PRICE]</TD>
							<TD class="td_con1" style="padding-left:5px; border-top-width:1px; border-top-color:rgb(222,222,222); border-top-style:solid;" width="100%">��ǰ���űݾ����� ����Ǿ� �޼��� ���� (��:50,000)</TD>
						</TR>
						<TR>
							<TD class="table_cell" style="padding-right:15px; border-top-width:1pt; border-bottom-width:1pt; border-top-color:rgb(222,222,222); border-bottom-color:silver; border-top-style:solid; border-bottom-style:solid;" noWrap align=right width=150 bgColor="#F0F0F0" height="27">[ACCOUNT]</TD>
							<TD class="td_con1" style="padding-left:5px; border-top-width:1pt; border-bottom-width:1pt; border-top-color:rgb(222,222,222); border-bottom-color:silver; border-top-style:solid; border-bottom-style:solid;" width="100%" height="27">��ǰ���Ž� ȸ���� ������ �Աݰ��¹�ȣ�� ����Ǿ� �޼��� ����<br>(��:123456-78-901234 ������:�ƹ���)</TD>
						</TR>
						</TABLE>
						</td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td  class="space_top">&nbsp;</td>
					</tr>
					<tr>
						<td width="20" align="right"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td ><b><font color="black">�Ա�Ȯ��/��ǰ�߼� �ȳ��޼���</font></b></td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td  class="space_top">
						<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
						<TR>
							<TD class="table_cell" style="padding-right:15px; border-top-width:1px; border-top-color:silver; border-top-style:solid;" noWrap align=right width=150 bgColor=#f0f0f0 height="27">[NAME]</TD>
							<TD class="td_con1" style="padding-left:5px; border-top-width:1px; border-top-color:silver; border-top-style:solid;" width="100%">ȸ�� �̸����� ����Ǿ� �޼��� ������ �˴ϴ�. (��:ȫ�浿)</TD>
						</TR>
						<TR>
							<TD class="table_cell" style="padding-right:15px; border-top-width:1px; border-top-color:rgb(222,222,222); border-top-style:solid;" noWrap align=right width=150 bgColor=#f0f0f0 height="27">[DATE]</TD>
							<TD class="td_con1" style="padding-left:5px; border-top-width:1px; border-top-color:rgb(222,222,222); border-top-style:solid;" width="100%">�ش� ��/�Ϸ� 	����Ǿ� �޼��� ���� (��:04�� 25��)</TD>
						</TR>
						<TR>
							<TD class="table_cell" style="padding-right:15px; border-top-width:1pt; border-bottom-width:1pt; border-top-color:rgb(222,222,222); border-bottom-color:silver; border-top-style:solid; border-bottom-style:solid;" noWrap align=right width=150 bgColor="#F0F0F0" height="27">[PRICE]</TD>
							<TD class="td_con1" style="padding-left:5px; border-top-width:1pt; border-bottom-width:1pt; border-top-color:rgb(222,222,222); border-bottom-color:silver; border-top-style:solid; border-bottom-style:solid;" width="100%" height="27">��ǰ���űݾ����� ����Ǿ� �޼��� ���� (��:50,000)</TD>
						</TR>
						</TABLE>
						</td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td  class="space_top">&nbsp;</td>
					</tr>
					<tr>
						<td width="20" align="right"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td ><b><font color="black">�����ȣ �ȳ��޼���</font></b></td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td  class="space_top">
						<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
						<TR>
							<TD class="table_cell" style="padding-right:15px; border-top-width:1px; border-top-color:silver; border-top-style:solid;" noWrap align=right width=150 bgColor=#f0f0f0 height="27">[NAME]</TD>
							<TD class="td_con1" style="padding-left:5px; border-top-width:1px; border-top-color:silver; border-top-style:solid;" width="100%">ȸ�� �̸����� ����Ǿ� �޼��� ������ �˴ϴ�. (��:ȫ�浿)</TD>
						</TR>
						<TR>
							<TD class="table_cell" style="padding-right:15px; border-top-width:1px; border-top-color:rgb(222,222,222); border-top-style:solid;" noWrap align=right width=150 bgColor=#f0f0f0 height="27">[DATE]</TD>
							<TD class="td_con1" style="padding-left:5px; border-top-width:1px; border-top-color:rgb(222,222,222); border-top-style:solid;" width="100%">�ش� ��/�Ϸ� ����Ǿ� �޼��� ���� (��:04�� 25��)</TD>
						</TR>
						<TR>
							<TD class="table_cell" style="padding-right:15px; border-top-width:1px; border-top-color:rgb(222,222,222); border-top-style:solid;" noWrap align=right width=150 bgColor=#f0f0f0 height="27">[PRICE]</TD>
							<TD class="td_con1" style="padding-left:5px; border-top-width:1px; border-top-color:rgb(222,222,222); border-top-style:solid;" width="100%">��ǰ���űݾ����� ����Ǿ� �޼��� ���� (��:50,000)</TD>
						</TR>
						<tr>
							<TD class="table_cell" style="padding-right:15px; border-top-width:1px; border-top-color:rgb(222,222,222); border-top-style:solid;" noWrap align=right width=150 bgColor=#f0f0f0 height="27">[DELICOM]</TD>
							<TD class="td_con1" style="padding-left:5px; border-top-width:1px; border-top-color:rgb(222,222,222); border-top-style:solid;" width="100%">�ù�ȸ������� ����Ǿ� �޼��� ���� (��:KGB�ù�)</TD>
						</tr>
						<TR>
							<TD class="table_cell" style="padding-right:15px; border-top-width:1pt; border-bottom-width:1pt; border-top-color:rgb(222,222,222); border-bottom-color:silver; border-top-style:solid; border-bottom-style:solid;" noWrap align=right width=150 bgColor="#F0F0F0" height="27">[DELINUM]</TD>
							<TD class="td_con1" style="padding-left:5px; border-top-width:1pt; border-bottom-width:1pt; border-top-color:rgb(222,222,222); border-bottom-color:silver; border-top-style:solid; border-bottom-style:solid;" width="100%" height="27">�����ȣ�� ����Ǿ� �޼��� ���� (��:1234-5678-9012)</TD>
						</TR>
						</TABLE>
						</td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td  class="space_top">&nbsp;</td>
					</tr>
					<tr>
						<td width="20" align="right"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td ><b><font color="black">����ȸ�� �ڵ��޼���</font></b></td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td  class="space_top">
						<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
						<TR>
							<TD class="table_cell" style="padding-right:15px; border-top-width:1px; border-top-color:silver; border-top-style:solid;" noWrap align=right width=150 bgColor=#f0f0f0 height="27">[NAME]</TD>
							<TD class="td_con1" style="padding-left:5px; border-top-width:1px; border-top-color:silver; border-top-style:solid;" width="100%" height="27">ȸ�� �̸����� ����Ǿ� �޼��� ������ �˴ϴ�. (��:ȫ�浿)</TD>
						</TR>
						<TR>
							<TD class="table_cell" style="padding-right:15px; border-top-width:1pt; border-bottom-width:1pt; border-top-color:rgb(222,222,222); border-bottom-color:silver; border-top-style:solid; border-bottom-style:solid;" noWrap align=right width=150 bgColor="#F0F0F0" height="27">[DATE]</TD>
							<TD class="td_con1" style="padding-left:5px; border-top-width:1pt; border-bottom-width:1pt; border-top-color:rgb(222,222,222); border-bottom-color:silver; border-top-style:solid; border-bottom-style:solid;" width="100%" height="27">�ش� ��/�Ϸ� ����Ǿ� �޼��� ����(��:04�� 25��)-�ֹε�Ϲ�ȣ�� ���ϱ������� ������ ����</TD>
						</TR>
						</TABLE>
						</td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td class="space_top">&nbsp;</td>
					</tr>
					<tr>
						<td width="20" align="right"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td><b><font color="black">�����ϱ� �ڵ��޼���</font></b></td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td class="space_top">
						<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
						<TR>
							<TD class="table_cell" style="padding-right:15px; border-top-width:1px; border-top-color:silver; border-top-style:solid;;" noWrap align=right width=150 bgColor=#f0f0f0 height="27">[NAME]</TD>
							<TD class="td_con1" style="padding-left:5px; border-top-width:1px; border-top-color:silver; border-top-style:solid;" width="100%" height="27">ȸ�� �̸����� ����Ǿ� �޼��� ������ �˴ϴ�. (��:ȫ�浿)</TD>
						</TR>
						<TR>
							<TD class="table_cell" style="padding-right:15px; border-top-width:1px; border-top-color:silver; border-top-style:solid;" noWrap align=right width=150 bgColor="#F0F0F0" height="27">[AUTHCODE]</TD>
							<TD class="td_con1" style="padding-left:5px; border-top-width:1px; border-top-color:silver; border-top-style:solid;" width="100%" height="27">������ȣ (��. 8a5689 - a1ebe2)</TD>
						</TR>
						<TR>
							<TD class="table_cell" style="padding-right:15px; border-top-width:1pt; border-bottom-width:1pt; border-top-color:rgb(222,222,222); border-bottom-color:silver; border-top-style:solid; border-bottom-style:solid;" noWrap align=right width=150 bgColor="#F0F0F0" height="27">[URL]</TD>
							<TD class="td_con1" style="padding-left:5px; border-top-width:1pt; border-bottom-width:1pt; border-top-color:rgb(222,222,222); border-bottom-color:silver; border-top-style:solid; border-bottom-style:solid;" width="100%" height="27">���θ� �ּ�</TD>
						</TR>
						</TABLE>
						</td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td class="space_top">&nbsp;</td>
					</tr>
					<tr>
						<td width="20" align="right"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td><b><font color="black">�������� �̴޼��� ������� �޼���</font></b></td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td class="space_top">
						<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
						<TR>
							<TD class="table_cell" style="padding-right:15px; border-top-width:1pt; border-bottom-width:1pt; border-top-color:rgb(222,222,222); border-bottom-color:silver; border-top-style:solid; border-bottom-style:solid;" noWrap align=right width=150 bgColor="#F0F0F0" height="27">[PRODUCT]</TD>
							<TD class="td_con1" style="padding-left:5px; border-top-width:1pt; border-bottom-width:1pt; border-top-color:rgb(222,222,222); border-bottom-color:silver; border-top-style:solid; border-bottom-style:solid;" width="100%" height="27">�ֹ� ��ǰ������ ����Ǿ� �޼��� ������ �˴ϴ�. (��:���Ÿ�� ����LT-3)</TD>
						</TR>
						</TABLE>
						</td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td class="space_top">&nbsp;</td>
					</tr>
					<tr>
						<td width="20" align="right"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td><b><font color="black">��ǰ ȫ���޼���</font></b></td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td class="space_top">
						<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
						<TR>
							<TD class="table_cell" style="padding-right:15px; border-top-width:1px; border-top-color:silver; border-top-style:solid;" noWrap align=right width=150 bgColor=#f0f0f0 height="27">[NAME]</TD>
							<TD class="td_con1" style="padding-left:5px; border-top-width:1px; border-top-color:silver; border-top-style:solid;" width="100%" height="27">ȸ�� �̸����� ����Ǿ� �޼��� ������ �˴ϴ�. (��:ȫ�浿)</TD>
						</TR>
						<TR>
							<TD class="table_cell" style="padding-right:15px; border-top-width:1px; border-top-color:silver; border-top-style:solid;" noWrap align=right width=150 bgColor="#F0F0F0" height="27">[PRODUCT]</TD>
							<TD class="td_con1" style="padding-left:5px; border-top-width:1px; border-top-color:silver; border-top-style:solid;" width="100%" height="27">ȫ���ϴ� ��ǰ������ ����Ǿ� �޼��� ������ �˴ϴ�. (��:���Ÿ�� ����LT-3</TD>
						</TR>
						<TR>
							<TD class="table_cell" style="padding-right:15px; border-top-width:1pt; border-bottom-width:1pt; border-top-color:rgb(222,222,222); border-bottom-color:silver; border-top-style:solid; border-bottom-style:solid;" noWrap align=right width=150 bgColor="#F0F0F0" height="27">[URL]</TD>
							<TD class="td_con1" style="padding-left:5px; border-top-width:1pt; border-bottom-width:1pt; border-top-color:rgb(222,222,222); border-bottom-color:silver; border-top-style:solid; border-bottom-style:solid;" width="100%" height="27">��ǰ �������� �ּ�</TD>
						</TR>
						</TABLE>
						</td>
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
			</form>
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