<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
INCLUDE ("access.php");

####################### ������ ���ٱ��� check ###############
$PageCode = "vd-1";
$MenuCode = "vender";
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

$maxcount=0;

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
$venderlist=$_POST["venderlist"];
$gubun=$_POST["gubun"];
$msg=$_POST["msg"];
$from_tel1=$_POST["from_tel1"];
$from_tel2=$_POST["from_tel2"];
$from_tel3=$_POST["from_tel3"];
$clicknum=$_POST["clicknum"];

if($gubun!="ALL" && $gubun!="VENDER") $gubun="VENDER";

if($type=="result") {
	if($gubun=="ALL") {
		if($maxcount>0) {
			unset($tel_list);
			unset($name_list);
			$fromtel=$from_tel1."-".$from_tel2."-".$from_tel3;
			$cnt=0;
			$sql = "SELECT p_mobile, p_name FROM tblvenderinfo ";
			$sql.= "WHERE delflag='N' AND p_mobile !='' ";
			$result=mysql_query($sql,get_db_conn());
			while($row = mysql_fetch_object($result)){
				$row->p_mobile=ereg_replace(",","",$row->p_mobile);
				$row->p_mobile=ereg_replace("-","",$row->p_mobile);
				if(strlen($row->p_mobile)<10 || strlen($row->p_mobile)>11){
				} else {
					$tel_list.=",".$row->p_mobile;
					$name_list.=",".ereg_replace(",","",$row->p_name);
					$cnt++;
				}
			}
			mysql_free_result($result);
			$tel_list=substr($tel_list,1);
			$name_list=substr($name_list,1);
			if($cnt <=$maxcount && $cnt>0) {
				$etcmsg="������ü SMS �޼��� ����";
				$temp=SendSMS($sms_id, $sms_authkey, $tel_list, $name_list, $fromtel, 0, $msg, $etcmsg);
				$resmsg=explode("[SMS]",$temp);
				$mess = "\\n".$resmsg[1];
			} else if($cnt==0) {
				$mess="\\nSMS�� �߼��� ��ü�� �����ϴ�.";
			} else {
				$mess="\\nSMS �Ӵϰ� �����մϴ�. ������ �̿��Ͻñ� �ٶ��ϴ�.";
			}
		} else {
			$mess="\\nSMS �Ӵϰ� �����մϴ�. ������ �̿��Ͻñ� �ٶ��ϴ�.";
		}
		echo "<script>alert('".$mess."');location='".$_SERVER[PHP_SELF]."';</script>";
		exit;
	} else if($gubun=="VENDER") {
		$aruser = explode(",",$venderlist);
		$cnt = count($aruser);
		if($cnt>1) {
			if($maxcount>0) {
				unset($tel_list);
				unset($name_list);
				$fromtel=$from_tel1."-".$from_tel2."-".$from_tel3;

				$venderlist = substr($venderlist,1);
				$venderlist = ereg_replace(",","','",$venderlist);

				$sql = "SELECT p_mobile, p_name FROM tblvenderinfo ";
				//$sql.= "WHERE id IN ('".$venderlist."') AND delflag='N' AND p_mobile != '' ";
				$sql.= "WHERE id = '".$venderlist."' AND delflag='N' AND p_mobile != '' ";
				$result=mysql_query($sql,get_db_conn());
				$cnt=0;
				while($row = mysql_fetch_object($result)){
					$row->p_mobile=ereg_replace(",","",$row->p_mobile);
					$row->p_mobile=ereg_replace("-","",$row->p_mobile);
					if(strlen($row->p_mobile)<10 || strlen($row->p_mobile)>11){
					} else {
						$tel_list.=",".$row->p_mobile;
						$name_list.=",".ereg_replace(",","",$row->p_name);
						$cnt++;
					}
				}
				$tel_list=substr($tel_list,1);
				$name_list=substr($name_list,1);
				if($cnt <=$maxcount && $cnt>0) {
					$etcmsg="������ü SMS �޼��� ����";
					$temp=SendSMS($sms_id, $sms_authkey, $tel_list, $name_list, $fromtel, 0, $msg, $etcmsg);
					$resmsg=explode("[SMS]",$temp);
					$mess = "\\n".$resmsg[1];
				} else if($cnt==0) {
					$mess="\\nSMS�� �߼��� ��ü�� �����ϴ�.";
				} else {
					$mess="\\nSMS �Ӵϰ� �����մϴ�. ������ �̿��Ͻñ� �ٶ��ϴ�.";
				}
			} else {
				$mess="\\nSMS �Ӵϰ� �����մϴ�. ������ �̿��Ͻñ� �ٶ��ϴ�.";
			}
		} else {
			$mess="\\nSMS �߼��� ��ü�� �����ϴ�.";
		}
		echo "<script>alert('".$mess."');location='".$_SERVER[PHP_SELF]."';</script>";
		exit;
	}
}

?>

<? INCLUDE "header.php"; ?>

<script type="text/javascript" src="lib.js.php"></script>
<script language="JavaScript">
function CheckForm() {
<?if($isdisabled=="1"){?>
	form=document.form2;
	if(form.gubun[1].checked==true) {
		form.venderlist.value="";
		for(i=1;i<form.allvender.options.length;i++) {
			form.venderlist.value+=","+form.allvender.options[i].value;
		}
		if(form.venderlist.value.length==0) {
			alert("������ü�� �߰��ϼ���.");
			FindVender();
			return;
		}
	}

	if(form.msg.value.length==0) {
		alert("������ �޼����� �Է��ϼ���.");
		form.msg.focus();
		return;
	}
	for(i=1;i<=3;i++) {
		if(form["from_tel"+i].value.length==0) {
			alert("������ ��� ��ȭ��ȣ�� �Է��ϼ���.");
			form["from_tel"+i].focus();
			return;
		}
		if(!IsNumeric(form["from_tel"+i].value)) {
			alert("���ڸ� �Է��ϼ���.");
			form["from_tel"+i].focus();
			break; return;
		}
	}
	from_tel=form.from_tel1.value+form.from_tel2.value+form.from_tel3.value;
	if(from_tel.length<8) {
		alert("������ ��� ��ȭ��ȣ �Է��� �߸��Ǿ����ϴ�.");
		form.from_tel1.focus();
		return;
	}
	if(confirm("������ü ����ڿ��� SMS�� �߼��Ͻðڽ��ϱ�?")) {
		form.type.value="result";
		form.submit();
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
		if(document.form2.clicknum.checked==false) {
			document.form2.from_tel1.value="<?=$return_tel[0]?>";
			document.form2.from_tel2.value="<?=$return_tel[1]?>";
			document.form2.from_tel3.value="<?=$return_tel[2]?>";
			document.form2.clicknum.checked = true;
		} else {
			document.form2.from_tel1.value="";
			document.form2.from_tel2.value="";
			document.form2.from_tel3.value="";
			document.form2.clicknum.checked = false;
		}
	} else {
		if(checked==true) {
			document.form2.from_tel1.value="<?=$return_tel[0]?>";
			document.form2.from_tel2.value="<?=$return_tel[1]?>";
			document.form2.from_tel3.value="<?=$return_tel[2]?>";
		} else {
			document.form2.from_tel1.value="";
			document.form2.from_tel2.value="";
			document.form2.from_tel3.value="";
		}
	}
}

function ChangeType(val) {
	if(val.length==0 || val=="ALL" ) {
		document.form2.id.disabled=true;
		document.form2.search_vender.disabled=true;
		document.form2.search_vender.src="images/btn_venderidr.gif";
		document.form2.vender_add.disabled=true;
		document.form2.vender_add.src="images/btn_addr.gif";
		document.form2.vender_del.disabled=true;
		document.form2.vender_del.src="images/btn_del6r.gif";
		document.form2.allvender.disabled=true;
	} else if (val=="VENDER") {
		document.form2.id.disabled=false;
		document.form2.search_vender.disabled=false;
		document.form2.search_vender.src="images/btn_venderid.gif";
		document.form2.vender_add.disabled=false;
		document.form2.vender_add.src="images/btn_add.gif";
		document.form2.vender_del.disabled=false;
		document.form2.vender_del.src="images/btn_del6.gif";
		document.form2.allvender.disabled=false;
	}
}

function FindVender() {
	 document.form2.gubun[1].checked=true;
	 window.open("about:blank","findvender","width=250,height=150,scrollbars=yes");
	 document.mform.submit();
}

function addChar(aspchar) {
<?if($isdisabled=="1"){?>
	document.form2.msg.value += aspchar;
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
	obj_msg = document.form2.msg;
	obj_len = document.form2.len_msg;

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

function ToAdd() {
	id=document.form2.id.value;
	if(id.length==0) {
		alert("������ü ID�� �����Ͻñ� �ٶ��ϴ�.");
		FindVender();
		return;
	}
	allvender=document.form2.allvender;
	for(i=1;i<allvender.options.length;i++) {
		if(id==allvender.options[i].value) {
			alert("�̹� �߰��� ID�Դϴ�.\n\n�ٽ� Ȯ���Ͻñ� �ٶ��ϴ�.");
			document.form2.id.value="";
			return;
		}
	}

	new_option = document.createElement("OPTION");
	new_option.text=id;
	new_option.value=id;
	allvender.add(new_option);
	cnt=allvender.options.length - 1;
	allvender.options[0].text = "----------------------- ������ü ���("+cnt+") -------------------------";
	document.form2.id.value="";
}

function ToDelete() {
	allvender=document.form2.allvender;
	for(i=1;i<allvender.options.length;i++) {
		if(allvender.options[i].selected==true){
			allvender.options[i]=null;
			cnt=allvender.options.length - 1;
			allvender.options[0].text = "----------------------- ������ü ���("+cnt+") -------------------------";
			return;
		}
	}
	alert("������ ID�� �����ϼ���.");
	allvender.focus();
}

</script>

<style type="text/css">
<!--
TEXTAREA {  clip:   rect(   ); overflow: hidden; background-image:url('');font-family:����;}
.phone {  font-family:����; height: 80px; width: 173px;color: #191919;  FONT-SIZE: 9pt; font-style: normal; background-color: #A8E4ED;; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px}
-->
</style>
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
			<? include ("menu_vender.php"); ?>
			</td>

			<td></td>
			<td valign="top">




<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td height="29" colspan="3">
			<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td height="28" class="link" align="left" background="images/con_link_bg.gif"><img src="images/top_link_house.gif" border="0" valign="absmiddle">������ġ : �������� &gt; ������ü ���� &gt; <span class="2depth_select">SMS ��������</span></td>
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
					<TD><IMG SRC="images/vender_smssend_title.gif" border="0"></TD>
					</tr></tr>
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
					<TD background="images/distribute_04.gif"><IMG SRC="images/distribute_04.gif" ></TD>
					<TD class="notice_blue"><IMG SRC="images/distribute_img.gif" ></TD>
					<TD width="100%" class="notice_blue"><p>������ü ��ü �Ǵ� Ư�� ��ü���� SMS ���� ������ �� �� �ֽ��ϴ�.</p></TD>
					<TD background="images/distribute_07.gif"><IMG SRC="images/distribute_07.gif" ></TD>
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
			<form name=form2 action="<?=$_SERVER[PHP_SELF]?>" method=post onSubmit="return false;">
			<input type=hidden name=type>
			<input type=hidden name=venderlist>
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
							<TD height="90" background="images/sms_bg.gif" valign="top"><p align="center"><TEXTAREA class="textarea_hide" name=msg rows=5 cols=26 bgcolor="#A8E4ED" onkeyup="cal_pre2();" onchange="cal_pre2();" <?if($isdisabled!="1") echo "disabled";?>><?=$msg?></TEXTAREA></TD>
						</TR>
						<TR>
							<TD height="26" background="images/sms_down_01.gif"><p align="center"><input type="text" name="len_msg" value="0" style="PADDING-RIGHT:5px; WIDTH:20px; TEXT-ALIGN:right" onfocus="this.blur();" class="input_hide">bytes (�ִ�80 bytes)<script>cal_pre2();</script></TD>
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
					<td width="11" valign="top"><p>&nbsp;</p></td>
					<td  valign="top" width="100%">
					<table cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td >
						<table cellpadding="0" cellspacing="0" width="100%">
						<tr>
							<td  bgcolor="#ededed" style="padding:4pt;">
							<table cellpadding="0" cellspacing="0" width="100%" bgcolor="white">
							<tr>
								<td width="100%">
								<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
								<TR>
									<TD  colspan="2" height="35" background="images/blueline_bg.gif"><p align="center"><b><font color="#333333">������ü SMS �߼� ���� �Է�</font></b></p></TD>
								</TR>
								<TR>
									<TD colspan="2"  background="images/table_con_line.gif"><img src="images/table_con_line.gif" width="4" height="1" border="0"></TD>
								</TR>
								<TR>
									<TD width="164" class="table_cell" height="40"><img src="images/icon_point2.gif" width="8" height="11" border="0">������ ���</TD>
									<TD  class="td_con1" height="40"><p class="LIPoint"><IMG height=5 width=0><input type=text name=from_tel1 value="<?=$from_tel1?>" size=5 maxlength=3 onKeyUp="return strnumkeyup(this);" class="input"> - <input type=text name=from_tel2 value="<?=$from_tel2?>" size=5 maxlength=4 onKeyUp="return strnumkeyup(this);" class="input"> - <input type=text name=from_tel3 value="<?=$from_tel3?>" size=5 maxlength=4 onKeyUp="return strnumkeyup(this);" class="input"><input type=checkbox id="idx_clicknum" name=clicknum value="Y" <?if($clicknum=="Y") echo "checked";?>  onclick="DefaultFrom(this.checked,'')" style="BORDER-RIGHT: medium none; BORDER-TOP: medium none; BORDER-LEFT: medium none; BORDER-BOTTOM: medium none"> <a href="javascript:DefaultFrom('','1');"><img src="images/btn_tel.gif" width="67" height="16" border="0"></a></p></TD>
								</TR>
								<TR>
									<TD colspan="2"  background="images/table_con_line.gif"><img src="images/table_con_line.gif" width="4" height="1" border="0"></TD>
								</TR>
								<TR>
									<TD width="164" class="table_cell" height="40"><input type=radio id="idx_gubun1" name=gubun value="ALL" onclick="ChangeType(this.value) ;" <?=($gubun=="ALL"?"checked":"")?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_gubun1><B>��� ��ü �߼�</B></label></TD>
									<TD  class="td_con1" height="40">&nbsp;</TD>
								</TR>
								<TR>
									<TD colspan="2"  background="images/table_con_line.gif"><img src="images/table_con_line.gif" width="4" height="1" border="0"></TD>
								</TR>
								<TR>
									<TD width="164" class="table_cell" height="40"><input type=radio id="idx_gubun3" name=gubun value="VENDER" onclick="ChangeType(this.value) ;" <?=($gubun=="VENDER"?"checked":"")?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_gubun3><B>��ü �����߼�</B></label></TD>
									<TD  class="td_con1" height="40">
										��ü ��ȭ��ȣ : <input type=text name=id onfocus="blur()" onclick="FindVender()" style="width:100" class="input">
										<a href="javascript:FindVender();"><img id="search_vender" src="images/btn_venderid.gif" width="74" height="25" border="0" hspace="1" align="absmiddle"></a>
										<a href="javascript:ToAdd();"><img id="vender_add" src="images/btn_add.gif" width="59" height="25" border="0" align="absmiddle"></a>
										<a href="javascript:ToDelete();"><img id="vender_del" src="images/btn_del6.gif" width="59" height="25" border="0" align="absmiddle"></a>
									</TD>
								</TR>
								<TR>
									<TD colspan="2"  background="images/table_con_line.gif"><img src="images/table_con_line.gif" width="4" height="1" border="0"></TD>
								</TR>
								<TR>
									<TD  valign="top" colspan="2" style="padding:10pt;">
									<select name=allvender size=12 style="WIDTH:100%" class="select">
									<option value="" style="BACKGROUND-COLOR: #ffff00">----------------------- ������ü ���(0) -------------------------</option>
									</select>
									</TD>
								</TR>
								</TABLE>
								<script>ChangeType('<?=$gubun?>');</script>
								</td>
							</tr>
							</table>
							</td>
						</tr>
						</table>
						</td>
					</tr>
					<tr>
						<td width="516" height="50"><p align="center"><a href="javascript:CheckForm();"><img src="images/btn_sms3.gif" width="123" height="38" border="0" <?if($isdisabled!="1") echo "style=\"filter:Alpha(Opacity=60) Gray\"";?>></a>&nbsp;&nbsp;<a href="market_smsfill.php"><img src="images/btn_sms4.gif" width="123" height="38" border="0" hspace="2"></a></p></td>
					</tr>
					</table>
					</td>
				</tr>
				</table>
				</td>
			</tr>
			</form>
			<form name=mform action="vender_findpop.php" method=post target=findvender>
			<input type=hidden name=formname value="form2">
			</form>
			<tr>
				<td><p>&nbsp;</p></td>
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
					<TD background="images/manual_left1.gif"><IMG SRC="images/manual_left1.gif" WIDTH=15 HEIGHT="5" ALT=""></TD>
					<TD COLSPAN=3 width="100%" valign="top" class="menual_bg" style="padding-top:8pt; padding-bottom:8pt; padding-left:4pt;">
					<table cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td width="20" align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td ><span class="font_dotline">SMS ���/��ü �߼�</span></td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td  class="space_top"><p>- SMS ���ڸ޼��� ������� ���Ἥ�� �Դϴ�. SMS�� ���� ���� �� ��� �����մϴ�.</p></td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td  class="space_top"><p>- SMS ���ڸ޼����� 1ȸ �ִ� 80Byte �߼� �����մϴ�.</p></td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td  class="space_top"><p>- �޴��� ��ȣ�� �Է��� ȸ�����Ը� �߼��� �˴ϴ�.</p></td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td  class="space_top"><p>- ��Ʈ��ũ ����, ��Ż� ������ ���� �߼۽ð��� �ټ� ������ �� ������ �ð��� ����Ͽ� �߼��Ͻñ� �ٶ��ϴ�.(1�ʴ� 5�� �߼�)</p></td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td  class="space_top"><p>- &quot;SMS ������&quot; ��ư�� �����ð� �߼ۿϷ� �Ǿ��ٴ� �޼����� ���ö����� ��ٷ��ֽñ� �ٶ��ϴ�.</p></td>
					</tr>
					</table>
					</TD>
					<TD background="images/manual_right1.gif"><IMG SRC="images/manual_right1.gif" WIDTH=18 HEIGHT="2" ALT=""></TD>
				</TR>
				<TR>
					<TD><IMG SRC="images/manual_left2.gif" WIDTH=15 HEIGHT=8 ALT=""></TD>
					<TD COLSPAN=3 background="images/manual_down.gif"><IMG SRC="images/manual_down.gif" WIDTH="4" HEIGHT=8 ALT=""></TD>
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