<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
INCLUDE ("access.php");

####################### ������ ���ٱ��� check ###############
$PageCode = "sh-3";
$MenuCode = "shop";
if (!$_usersession->isAllowedTask($PageCode)) {
	INCLUDE ("AccessDeny.inc.php");
	exit;
}
#########################################################

$maxnum=10;
$maxlength=2000;
$maxj=5; //���������۷�
$maxi=5; //������ ����

$sql = "SELECT order_msg ";
$sql.= "FROM tblshopinfo ";
$result=mysql_query($sql,get_db_conn());
if ($data=mysql_fetch_object($result)) {
	$order_msg = $data->order_msg;
}
mysql_free_result($result);

$type=$_POST["type"];
$up_deli_area=$_POST["up_deli_area"];
$up_deli_type=$_POST["up_deli_type"];
$up_deli_setperiod=$_POST["up_deli_setperiod"];
$up_delivery=$_POST["up_delivery"];
$up_deli_basefee=$_POST["up_deli_basefee".$up_delivery];
$up_deli_miniprice=(int)$_POST["up_deli_miniprice"];
$up_wantdate=$_POST["up_wantdate"];
$up_day1=$_POST["up_day1"];
$up_time1=$_POST["up_time1"];
$up_time2=$_POST["up_time2"];
$up_bankname=$_POST["up_bankname"];
for($i=0; $i<$maxi; $i++) {
	if(strlen($_POST["up_deli_limitup"][$i])>0 || strlen($_POST["up_deli_limitdown"][$i])>0 || strlen($_POST["up_deli_limitfee"][$i])>0) {
		if($_POST["up_deli_limitup"][$i]>=0 && $_POST["up_deli_limitdown"][$i]>=0 && $_POST["up_deli_limitfee"][$i]>=0) {
			$up_deli_limit_imp[] = (int)$_POST["up_deli_limitup"][$i]."".$_POST["up_deli_limitdown"][$i]."".(int)$_POST["up_deli_limitfee"][$i];
		}
	}
}
$up_deli_limit = @implode("=", $up_deli_limit_imp);
for($j=0; $j<$maxj; $j++) {
	if($_POST["up_gradedeliareanum"][$j]=="Y" && strlen($_POST["up_gradedeli_area"][$j])>0) {
		$up_deli_area_limit_imp[$j] = $_POST["up_gradedeli_area"][$j];
		for($i=0; $i<$maxi; $i++) {
			if(strlen($_POST["up_gradedeli_limitup"][$j][$i])>0 || strlen($_POST["up_gradedeli_limitdown"][$j][$i])>0 || strlen($_POST["up_gradedeli_limitfee"][$j][$i])>0) {
				if($_POST["up_gradedeli_limitup"][$j][$i]>=0 && $_POST["up_gradedeli_limitdown"][$j][$i]>=0 && $_POST["up_gradedeli_limitfee"][$j][$i]>=0) {
					$up_deli_area_limit_imp[$j].= "=".(int)$_POST["up_gradedeli_limitup"][$j][$i]."".$_POST["up_gradedeli_limitdown"][$j][$i]."".(int)$_POST["up_gradedeli_limitfee"][$j][$i];
				}
			}
		}
	}
}
$up_deli_area_limit = @implode(":", $up_deli_area_limit_imp);

if ($type=="up") {
	$up_deli_basefeetype="Y";
	if($up_delivery=="F") $up_deli_basefee=0;
	else if($up_delivery=="Y") $up_deli_basefee=-9;
	else if($up_delivery=="N" || $up_delivery=="Q") $up_deli_basefeetype="N";
	else if(strlen($up_deli_basefee) < 1) $up_deli_basefee = 0;

	$tmp_order_msg = $order_msg;
	$tmp_order_msg2 = explode("=",$tmp_order_msg);
	$message = addslashes($tmp_order_msg2[0]);

	if($up_wantdate=="Y") $message.="=".$up_day1.$up_time1.$up_time2;
	else if($up_wantdate=="A") $message.="=".$up_day1;
	else $message.="=";

	$message.="=".$up_bankname;    

	$sql = "UPDATE tblshopinfo SET ";
	$sql.= "deli_type			= '".$up_deli_type."', ";
	$sql.= "deli_basefee		= '".$up_deli_basefee."', ";
	$sql.= "deli_basefeetype	= '".$up_deli_basefeetype."', ";
	$sql.= "deli_miniprice		= '".$up_deli_miniprice."', ";
	$sql.= "deli_setperiod		= '".$up_deli_setperiod."', ";
	$sql.= "deli_limit			= '".$up_deli_limit."', ";
	$sql.= "order_msg			= '".$message."', ";
	$sql.= "deli_area			= '".$up_deli_area."', ";
	$sql.= "deli_area_limit		= '".$up_deli_area_limit."' ";
	$update = mysql_query($sql,get_db_conn());
	DeleteCache("tblshopinfo.cache");
	$onload="<script> alert(\"��ǰ ��۰��� ������ �Ϸ�Ǿ����ϴ�.\"); </script>";

	$log_content = "## ��۰��ü��� ## - ��۷�: $up_deli_basefee(���� ��۷� : $deli_basefee) �ֹ������� $up_deli_miniprice ���� ������ û��(0�̸� �������߰�)";
	ShopManagerLog($_ShopInfo->getId(),$connect_ip,$log_content);
}

$sql = "SELECT deli_type,deli_basefee,deli_basefeetype,deli_miniprice,deli_setperiod,deli_limit, ";
$sql.= "order_msg,deli_area,deli_area_limit FROM tblshopinfo ";
$result=mysql_query($sql,get_db_conn());
if ($data=mysql_fetch_object($result)) {
	$deli_type = $data->deli_type;
	$deli_basefee = $data->deli_basefee;
	$deli_basefeetype = $data->deli_basefeetype;
	$deli_limit = $data->deli_limit;
	$deli_area_limit = $data->deli_area_limit;
	if(strlen($deli_limit)>0) {
		if($deli_basefeetype == "Y")
			$delivery="P";
		else
			$delivery="Q";
		$deli_limit_exp = explode("=",$deli_limit);
		unset($deli_limitup);
		unset($deli_limitdown);
		unset($deli_limitfee);
		for($i=0; $i<count($deli_limit_exp); $i++) {
			$deli_limit_exp2=explode("",$deli_limit_exp[$i]);
			$deli_limitup[] = $deli_limit_exp2[0];
			$deli_limitdown[] = $deli_limit_exp2[1];
			$deli_limitfee[] = $deli_limit_exp2[2];
		}
	} else {
		if($deli_basefee==-9) $delivery="Y";
		else if($deli_basefee==0) $delivery="F";
		else {
			if($deli_basefeetype == "Y")
				$delivery="M";
			else
				$delivery="N";
		}
	}

	if(strlen($deli_area_limit)>0) {
		$deli_area_limit_exp = explode(":",$deli_area_limit);

		unset($gradedeli_area[$i]);
		for($i=0; $i<count($deli_area_limit_exp); $i++) {
			$deli_area_limit_exp1=explode("=",$deli_area_limit_exp[$i]);
			$gradedeli_area[] = $deli_area_limit_exp1[0];

			unset($gradedeli_limitup[$i]);
			unset($gradedeli_limitdown[$i]);
			unset($gradedeli_limitfee[$i]);
			for($j=1; $j<count($deli_area_limit_exp1); $j++) {
				$deli_area_limit_exp2=explode("",$deli_area_limit_exp1[$j]);
				$gradedeli_limitup[$i][] = $deli_area_limit_exp2[0];
				$gradedeli_limitdown[$i][] = $deli_area_limit_exp2[1];
				$gradedeli_limitfee[$i][] = $deli_area_limit_exp2[2];
			}
		}
	}
	if($deli_basefee<0) $deli_basefee=0;
	$deli_miniprice = $data->deli_miniprice;
	$deli_setperiod = $data->deli_setperiod;
	$deli_area = $data->deli_area;
	$order_msg = $data->order_msg;
}
mysql_free_result($result);
?>

<? INCLUDE "header.php"; ?>

<script type="text/javascript" src="lib.js.php"></script>
<script>
function CheckForm() {
	var form = document.form1;
	if(form.up_delivery[2].checked==true){
		if (form.up_deli_basefeeM.value.length==0) {
			alert("��۷Ḧ �Է��ϼ���.");
			form.up_deli_basefeeM.focus();
			return;
		} else if (isNaN(form.up_deli_basefeeM.value)) {
			alert("��۷�� ���ڸ� �Է� �����մϴ�.");
			form.up_deli_basefeeM.focus();
			return;
		} else if(form.up_deli_basefeeM.value<=0) {
			alert("��۷�� 0�� �̻� �Է��ϼž� �մϴ�.");
			form.up_deli_basefeeM.focus();
			return;
		}
	}
	if(form.up_delivery[3].checked==true){
		if (form.up_deli_basefeeN.value.length==0) {
			alert("��۷Ḧ �Է��ϼ���.");
			form.up_deli_basefeeN.focus();
			return;
		} else if (isNaN(form.up_deli_basefeeN.value)) {
			alert("��۷�� ���ڸ� �Է� �����մϴ�.");
			form.up_deli_basefeeN.focus();
			return;
		} else if(form.up_deli_basefeeN.value<=0) {
			alert("��۷�� 0�� �̻� �Է��ϼž� �մϴ�.");
			form.up_deli_basefeeN.focus();
			return;
		}
	}
	if(form.up_delivery[4].checked==true || form.up_delivery[5].checked==true){
		for(var i=0; i<<?=$maxi?>; i++) {
			if(document.getElementById("deli_limitup"+i).value.length>0 && (isNaN(document.getElementById("deli_limitup"+i).value) || document.getElementById("deli_limitup"+i).value<0)) {
				alert('����űݾ��� 0 �̻��� ���ڸ� �Է� �����մϴ�.');
				document.getElementById("deli_limitup"+i).focus();
				return;
			} else if(document.getElementById("deli_limitdown"+i).value.length>0 && (isNaN(document.getElementById("deli_limitdown"+i).value) || document.getElementById("deli_limitdown"+i).value<0)) {
				alert('����űݾ��� 0 �̻��� ���ڸ� �Է� �����մϴ�.');
				document.getElementById("deli_limitdown"+i).focus();
				return;
			} else if(document.getElementById("deli_limitfee"+i).value.length>0 && (isNaN(document.getElementById("deli_limitfee"+i).value) || document.getElementById("deli_limitfee"+i).value<0)) {
				alert('��۷�ݾ��� 0 �̻��� ���ڸ� �Է� �����մϴ�.');
				document.getElementById("deli_limitfee"+i).focus();
				return;
			}
		}
	}
	var k=1;
	for(i=0;i<<?=$maxj?>;i++){
		if(document.getElementById("idx_gradedeliareanum"+i).checked==true) {
			if(document.getElementById("idx_gradedeli_area"+i).value.length==0) {
				alert(k+"��° Ư���������� �Է��� �ּ���.");
				document.getElementById("idx_gradedeli_area"+i).focus();
				return;
			}
			for(var j=0; j<<?=$maxi?>; j++) {
				if(document.getElementById("gradedeli"+i+"_limitup"+j).value.length>0 && (isNaN(document.getElementById("gradedeli"+i+"_limitup"+j).value) || document.getElementById("gradedeli"+i+"_limitup"+j).value<0)) {
					alert(k+"��° ����űݾ��� 0 �̻��� ���ڸ� �Է� �����մϴ�.");
					document.getElementById("gradedeli"+i+"_limitup"+j).focus();
					return;
				} else if(document.getElementById("gradedeli"+i+"_limitdown"+j).value.length>0 && (isNaN(document.getElementById("gradedeli"+i+"_limitdown"+j).value) || document.getElementById("gradedeli"+i+"_limitdown"+j).value<0)) {
					alert(k+"��° ����űݾ��� 0 �̻��� ���ڸ� �Է� �����մϴ�.");
					document.getElementById("gradedeli"+i+"_limitdown"+j).focus();
					return;
				} else if(document.getElementById("deli"+i+"_limitfee"+j).value.length>0 && (isNaN(document.getElementById("deli"+i+"_limitfee"+j).value) || document.getElementById("deli"+i+"_limitfee"+j).value<0)) {
					alert(k+"��° ��۷�ݾ��� 0 �̻��� ���ڸ� �Է� �����մϴ�.");
					document.getElementById("deli"+i+"_limitfee"+j).focus();
					return;
				}
			}
		}
		k++;
	}

	form.up_deli_area.value="";
	for(i=0;i<<?=$maxnum?>;i++){
		if((form.up_deliarea[i].value.length==0 && form.up_deliareaprice[i].value.length>0) 
		|| (form.up_deliarea[i].value.length>0 && form.up_deliareaprice[i].value.length==0)) {
			alert("Ư�� ������� �߰���۷Ḧ �Ѵ� �Է��ϼž� �մϴ�");
			form.up_deliarea[i].focus();
			return;
		}
		if (isNaN(form.up_deliareaprice[i].value)) {
			alert("�߰���۷�� ���ڸ� �Է� �����մϴ�.");
			form.up_deliareaprice[i].focus();
			return;
		}
		if (form.up_deliareaprice[i].value.length>0 && Math.abs(form.up_deliareaprice[i].value)==0) {
			alert("�߰���۷�� 0�� �̻� �Է��ϼž� �մϴ�.");
			form.up_deliareaprice[i].focus();
			return;
		}
		if(form.up_deliarea[i].value.length>0 && form.up_deliareaprice[i].value.length>0){
			form.up_deli_area.value+=form.up_deliarea[i].value+"|"+form.up_deliareaprice[i].value+"|";
		}
	}
	messlength = CheckLength(form.up_deli_area);
	if(messlength > <?=$maxlength?>){
		alert('�� �Է°����� ���̰� �ѱ� <?=($maxlength/2)?>�ڱ����Դϴ�. �ٽ��ѹ� Ȯ���Ͻñ� �ٶ��ϴ�.');
		form.up_deliarea[0].focus();
		return;
	}
	form.type.value="up";
	form.submit();
}

function SetDisplayTime(tf){
	form1.up_time1.disabled=tf;
	form1.up_time2.disabled=tf;
}

function SetDeliChange(changetype,chnagevalue) {
	if((changetype=="A" || changetype=="B") && chnagevalue.length>0) {
		if(changetype=="A") {
			SetDeliChangeA(chnagevalue);
			SetDeliChangeB('');
		} else {
			SetDeliChangeB(chnagevalue);
			SetDeliChangeA('');
		}
	} else {
		SetDeliChangeA('');
		SetDeliChangeB('');
	}
}
function SetDeliChangeA(chnagevalue) {
	if(chnagevalue=="M" || chnagevalue=="N") {
		if(chnagevalue=="M") {
			document.form1.up_deli_basefeeM.disabled=false;
			document.form1.up_deli_basefeeM.style.background='#FFFFFF';
			document.form1.up_deli_basefeeN.disabled=true;
			document.form1.up_deli_basefeeN.style.background='#EFEFEF';
		} else {	
			document.form1.up_deli_basefeeM.disabled=true;
			document.form1.up_deli_basefeeM.style.background='#EFEFEF';
			document.form1.up_deli_basefeeN.disabled=false;
			document.form1.up_deli_basefeeN.style.background='#FFFFFF';
		}
		document.form1.up_deli_miniprice.disabled=false;
		document.form1.up_deli_miniprice.style.background='#FFFFFF';
	} else {
		document.form1.up_deli_basefeeM.disabled=true;
		document.form1.up_deli_basefeeM.style.background='#EFEFEF';
		document.form1.up_deli_basefeeN.disabled=true;
		document.form1.up_deli_basefeeN.style.background='#EFEFEF';
		document.form1.up_deli_miniprice.disabled=true;
		document.form1.up_deli_miniprice.style.background='#EFEFEF';
	}
}

function SetDeliChangeB(chnagevalue) {
	if(chnagevalue=="P" || chnagevalue=="Q") {
		for(var i=0; i<<?=$maxi?>; i++) {
			document.getElementById("deli_limitup"+i).disabled=false;
			document.getElementById("deli_limitup"+i).style.background='#FFFFFF';
			document.getElementById("deli_limitdown"+i).disabled=false;
			document.getElementById("deli_limitdown"+i).style.background='#FFFFFF';
			document.getElementById("deli_limitfee"+i).disabled=false;
			document.getElementById("deli_limitfee"+i).style.background='#FFFFFF';
		}
	} else {
		for(var i=0; i<<?=$maxi?>; i++) {
			document.getElementById("deli_limitup"+i).disabled=true;
			document.getElementById("deli_limitup"+i).style.background='#EFEFEF';
			document.getElementById("deli_limitdown"+i).disabled=true;
			document.getElementById("deli_limitdown"+i).style.background='#EFEFEF';
			document.getElementById("deli_limitfee"+i).disabled=true;
			document.getElementById("deli_limitfee"+i).style.background='#EFEFEF';
		}
	}
}

function SetValueCopy(insertValue,insertObject) {
	if(document.getElementById(insertObject)) {
		document.getElementById(insertObject).value=insertValue;
	}
}

function setGradeDeliUse(checkValue,textValue) {
	if(document.getElementById("idx_gradedeliarea"+textValue)) {
		if(checkValue) { 
			document.getElementById("idx_gradedeliarea"+textValue).style.display="";
			document.getElementById("idx_gradedeli_area"+textValue).disabled=false;
			document.getElementById("idx_gradedeli_area"+textValue).style.background='#FFFFFF';
		} else {
			document.getElementById("idx_gradedeliarea"+textValue).style.display="none";
			document.getElementById("idx_gradedeli_area"+textValue).disabled=true;
			document.getElementById("idx_gradedeli_area"+textValue).style.background='#EFEFEF';
		}
	}
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
			<? include ("menu_shop.php"); ?>
			</td>

			<td></td>
			<td valign="top">




<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td height="29" colspan="3">
			<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td height="28" class="link" align="left" background="images/con_link_bg.gif"><img src="images/top_link_house.gif" border="0" valign="absmiddle">������ġ : �������� &gt; ���θ� � ���� &gt; <span class="2depth_select">��ǰ ��۰��� ��ɼ���</span></td>
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
			<tr><td height="8"></td></tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/shop_deli_title.gif" ALT=""></TD>
					</tr>
<tr>
<TD width="100%" background="images/title_bg.gif" height="21"></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr><td height=3></td></tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/distribute_01.gif"></TD>
					<TD COLSPAN=2 background="images/distribute_02.gif"></TD>
					<TD><IMG SRC="images/distribute_03.gif"></TD>
				</TR>
				<TR>
					<TD background="images/distribute_04.gif"></TD>
					<TD class="notice_blue"><IMG SRC="images/distribute_img.gif" ></TD>
					<TD width="100%" class="notice_blue">��ǰ ��۰��� ������ ���θ� ���ݿ� �°� �����Ͻ� �� �ֽ��ϴ�.</TD>
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
					<TD><IMG SRC="images/shop_deli_stitle1.gif" WIDTH="151" HEIGHT=31 ALT=""></TD>
					<TD width="100%" background="images/shop_basicinfo_stitle_bg.gif">&nbsp;</TD>
					<TD><IMG SRC="images/shop_basicinfo_stitle_end.gif" WIDTH=10 HEIGHT=31 ALT=""></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr><td height=3></td></tr>
			<form name=form1 action="<?=$_SERVER[PHP_SELF]?>" method=post>
			<input type=hidden name=type>
			<input type=hidden name=up_deli_area>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/distribute_01.gif"></TD>
					<TD COLSPAN=2 background="images/distribute_02.gif"></TD>
					<TD><IMG SRC="images/distribute_03.gif"></TD>
				</TR>
				<TR>
					<TD background="images/distribute_04.gif"></TD>
					<TD class="notice_blue"><IMG SRC="images/distribute_img.gif" ></TD>
					<TD width="100%" class="notice_blue">1) ���θ��� �̿�ȳ����������� ��۾ȳ��κп� ������ ������ ǥ��˴ϴ�.(�̿�ȳ��� ���� ������ ���� ����)<br>2) 1�Ϸ� ������ 1~4��, 2�Ϸ� ������ 2~5�� �� 3���� ������ ǥ��˴ϴ�.</TD>
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
			<tr><td height=3></td></tr>
			<tr>
				<td>
				<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
				<TR>
					<TD colspan=2 background="images/table_top_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell" width="120"><img src="images/icon_point2.gif" width="8" height="11" border="0">��� ��� ����</TD>
					<TD class="td_con1"><? ${"deli_select_".$deli_type} = "selected"; ?><select name="up_deli_type" class="select">
					<option <?=$deli_select_T?> value="T">�ù�
					<option <?=$deli_select_P?> value="P">�������
					<option <?=$deli_select_I?> value="I">�Ϲݵ��
					<option <?=$deli_select_X?> value="X">�ù�+�������
					<option <?=$deli_select_S?> value="S">�ù�+�Ϲݵ��
					<option <?=$deli_select_M?> value="M">�������
					</select></TD>
				</TR>
				<TR>
					<TD colspan="2"  background="images/table_con_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell" width="120"><img src="images/icon_point2.gif" width="8" height="11" border="0">����</TD>
					<TD class="td_con1" >�ֹ��Ͻų��� ���� ���� �� �ִ� ���� <input type=text name=up_deli_setperiod size=2 maxlength=2 value="<?=$deli_setperiod?>" class="input">�� ~ +3�� �ȿ� ���� �� �ֽ��ϴ�.</TD>
				</TR>
				<TR>
					<TD colspan=2 background="images/table_top_line.gif"></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr><td height="30"></td></tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/shop_deli_stitle2.gif" WIDTH="151" HEIGHT=31 ALT=""></TD>
					<TD width="100%" background="images/shop_basicinfo_stitle_bg.gif">&nbsp;</TD>
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
					<TD><IMG SRC="images/distribute_01.gif"></TD>
					<TD COLSPAN=2 background="images/distribute_02.gif"></TD>
					<TD><IMG SRC="images/distribute_03.gif"></TD>
				</TR>
				<TR>
					<TD background="images/distribute_04.gif"></TD>
					<TD class="notice_blue"><IMG SRC="images/distribute_img.gif" ></TD>
					<TD width="100%" class="notice_blue">1) ����/����, ����, ���� �׸� �� �ϳ��� ���� �����մϴ�.<br>
					2) �⺻ ��۷� ���� �Ǵ� ���� ���ý� �߰��� ������ �׸��� �����ϴ�.<br>
					3) ���� �Ǵ� ���� ���� ��۷� ���ý� �߰��� �ʿ��� �׸��� Ȯ���Ͻ� �� �Է��� �ּ���.<br>
					4) ��ǰ ���/���� ������������ ���������� ��۷Ḧ ������ �� �ֽ��ϴ�.<br>
					5) ��ǰ ���/���� ���������� ������ۺ� ����� ��� �ش� ��ǰ�� <B>�߰��� ������ۺ� û��</B> �˴ϴ�.</TD>
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
			<tr><td height=3></td></tr>
			<tr>
				<td>
				<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
				<TR>
					<TD colspan=2 background="images/table_top_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell" width="120" valign="top"><img src="images/icon_point2.gif" width="8" height="11" border="0">����/���� ��۷�</TD>
					<TD class="td_con1">

					<input type=radio class="radio" id="idx_delivery0" name=up_delivery value="F" <?=($delivery=="F"?"checked":"")?> onClick="SetDeliChange('','');"><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_delivery0>��۷� <font color='#0000FF'><b>����</b></font></label><br>

					<input type=radio class="radio" id="idx_delivery1" name=up_delivery value="Y" <?=($delivery=="Y"?"checked":"")?> onClick="SetDeliChange('','');"><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_delivery1>��۷� <font color='#38A422'><b>����</b></font></label>&nbsp;<span class=font_orange style="font-size:8pt; letter-spacing:-0.5pt;">* ������ ���� ��ٱ��Ͽ� ��۷ᰡ �Һ��� �δ��̶�� ������ ��µ˴ϴ�.</span>
					</td>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<tr>
					<TD class="table_cell" width="120" valign="top"><img src="images/icon_point2.gif" width="8" height="11" border="0">���� ���� ��۷�</TD>
					<TD class="td_con1">
					<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
					<tr>
						<td><input type=radio class="radio" id="idx_delivery2" name=up_delivery value="M" <?=($delivery=="M"?"checked":"")?> onClick="SetDeliChange('A','M')"><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_delivery2>��۷� <font color='#FF0000'><b>����</b></font>(��ۺ� ����� ������� ��ǰ�ݾ׵� <font color='#0000FF'><b>����</b></font>)</label>: <input type=text name=up_deli_basefeeM size=10 maxlength=6 value="<?=($delivery == "M"?$deli_basefee:"")?>" class="input" style="text-align:right;" disabled style="background-Color:#EFEFEF;">��<br>
					
						<input type=radio class="radio" id="idx_delivery3" name=up_delivery value="N" <?=($delivery=="N"?"checked":"")?> onClick="SetDeliChange('A','N')"><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_delivery3>��۷� <font color='#FF0000'><b>����</b></font>(��ۺ� ����� ������� ��ǰ�ݾ��� <font color='#FF0000'><b>����</b></font>)</label>: <input type=text name=up_deli_basefeeN size=10 maxlength=6 value="<?=($delivery == "N"?$deli_basefee:"")?>" class="input" style="text-align:right;" disabled style="background-Color:#EFEFEF;">��</td>
					</tr>
					<tr>
						<td height="5"></td>
					</tr>
					<tr>
						<td style="padding-left:20px;">
						<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
						<tr>
							<td align="center" style="border:3px #4AA0B5 solid;padding:5px;">���űݾ� <input type=text name=up_deli_miniprice size=10 maxlength=10 value="<?=$deli_miniprice?>" class="input" style="text-align:right;">�� �̸��� ��� ��ۺ� û���˴ϴ�.&nbsp;<span class=font_orange style="font-size:8pt; letter-spacing:-0.5pt;">* ���űݾ� 0 �� �Է½� ��� �ݾ׿� ��ۺ� �ΰ��˴ϴ�.</span></TD>
						</tr>
						</table>
						</td>
					</tr>
					</table>
					</td>
				</tr>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<tr>
					<TD class="table_cell" width="120" valign="top"><img src="images/icon_point2.gif" width="8" height="11" border="0">���� ���� ��۷�</TD>
					<TD class="td_con1">
					<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
					<tr>
						<td><input type=radio class="radio" id="idx_delivery4" name=up_delivery value="P" <?=($delivery=="P"?"checked":"")?> onClick="SetDeliChange('B','P')"><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_delivery4>��۷� <font color='#FF0000'><b>����</b></font>(��ۺ� ����� ������� ��ǰ�ݾ׵� <font color='#0000FF'><b>����</b></font>)</label><br>
					
						<input type=radio class="radio" id="idx_delivery5" name=up_delivery value="Q" <?=($delivery=="Q"?"checked":"")?> onClick="SetDeliChange('B','Q')"><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_delivery5>��۷� <font color='#FF0000'><b>����</b></font>(��ۺ� ����� ������� ��ǰ�ݾ��� <font color='#FF0000'><b>����</b></font>)</label></td>
					</tr>
					<tr>
						<td height="5"></td>
					</tr>
					<tr>
						<td style="padding-left:20px;">
						<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
						<tr>
							<td style="border:3px #D7D7D7 solid;" bgcolor="#F9F9F9">
							<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
							<col width=""></col>
							<col width="120"></col>
							<tr>
								<td style="border-right:2px #D7D7D7 solid;" height="7"><img width="0" height="0"></td>
								<td></td>
							</tr>
							<tr align="center" height="30">
								<td style="border-right:2px #D7D7D7 solid;"><b>���� ���� �ݾ� ����</b></td>
								<td><b>��۷�</b></td>
							</tr>
							<tr>
								<td style="border-right:2px #D7D7D7 solid;" height="3"><img width="0" height="0"></td>
								<td></td>
							</tr>
							<tr>
								<td style="padding-left:5px;padding-right:5px;border-right:2px #D7D7D7 solid;"><TABLE cellSpacing=0 cellPadding=0 width="100%" border=0><tr><td height="1" bgcolor="#DADADA"></td></tr></table></td>
								<td style="padding-left:5px;padding-right:5px;"><TABLE cellSpacing=0 cellPadding=0 width="100%" border=0><tr><td height="1" bgcolor="#DADADA"></td></tr></table></td>
							</tr>
							<tr>
								<td style="border-right:2px #D7D7D7 solid;" height="5"><img width="0" height="0"></td>
								<td></td>
							</tr>
							<?
							$j=1;
							for($i=0; $i<$maxi; $i++) { 
							?>
							<tr align="center">
								<td style="padding:5px;padding-bottom:0px;padding-left:0px;border-right:2px #D7D7D7 solid;"><b><?=str_pad($j, 2, "0", STR_PAD_LEFT);?>. </b><input type=text name=up_deli_limitup[] value="<?=($i==0?$i:$deli_limitup[$i])?>" <?=($i==0?" readonly":"")?> size=20 maxlength=10 class="input" style="text-align:right;" id="deli_limitup<?=$i?>"><b>�� �̻�&nbsp;&nbsp;��&nbsp;&nbsp;</b><input type=text name=up_deli_limitdown[] value="<?=$deli_limitdown[$i]?>" size=20 maxlength=10 class="input" id="deli_limitdown<?=$i?>" <?=($j==$maxi?"":" onKeyDown=\"SetValueCopy(this.value,'deli_limitup".$j."');\" onKeyUp=\"SetValueCopy(this.value,'deli_limitup".$j."');\"")?> style="text-align:right;"><b>�� �̸�</b></td>
								<td align="center" style="padding:5px;padding-bottom:0px;"><input type=text name=up_deli_limitfee[] value="<?=$deli_limitfee[$i]?>" size=12 maxlength=6 class="input" style="text-align:right;" id="deli_limitfee<?=$i?>"><b>��</b></td>
							</tr>
							<? 
								$j++;
							} 
							?>
							
							<tr>
								<td style="border-right:2px #D7D7D7 solid;" height="7"><img width="0" height="0"></td>
								<td></td>
							</tr>
							</table>
							</td>
						</tr>
						<tr>
							<td style="padding:2px;"><span class=font_orange>
							* ���Է½� �⺻�� : �� �̻� �׸��� "0", �� �̸� �׸��� ����, ��۷� �׸��� "0" �Դϴ�.<br>
							* �Է½ÿ��� 0 �̻��� ���ڸ� �Է��� �ּ���.<br>* ������� �ʴ� ������ ������� ó���� �ּ���.<br>* ���� ��۷� ������ ������ �ʴ� ���űݾ��� ��۷� ���ᰡ �˴ϴ�.<br>* ���� ��۷� ������ ��ġ�� ��� �켱������ 01 ~ 05 �Դϴ�.
							</span></td>
						</tr>
						</table>
						</td>
					</tr>
					</table>
					</td>
				</tr>
				<TR>
					<TD colspan=2 background="images/table_top_line.gif"></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr>
				<td height="20"></td>
			</tr>
<?
			if($delivery=="M" || $delivery=="N") { 
				echo "<script>SetDeliChange('A','".$delivery."');</script>";
			} else if($delivery=="P" || $delivery=="Q") { 
				echo "<script>SetDeliChange('B','".$delivery."');</script>";
			} else { 
				echo "<script>SetDeliChange('','');</script>";
			}
?>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/shop_deli_stitle8.gif" border="0"></TD>
					<TD width="100%" background="images/shop_basicinfo_stitle_bg.gif">&nbsp;</TD>
					<TD><IMG SRC="images/shop_basicinfo_stitle_end.gif" WIDTH=10 HEIGHT=31 ALT=""></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr>
				<td height=3></td>
			</tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/distribute_01.gif"></TD>
					<TD COLSPAN=2 background="images/distribute_02.gif"></TD>
					<TD><IMG SRC="images/distribute_03.gif"></TD>
				</TR>
				<TR>
					<TD background="images/distribute_04.gif"></TD>
					<TD class="notice_blue"><IMG SRC="images/distribute_img.gif" ></TD>
					<TD width="100%" class="notice_blue">1) Ư������ ���� ���� ��ۺ� �ش�� ��� �����ۺ�� Ư������ �����ۺ�� ��ü �˴ϴ�.(������۷� ���� û����)<br>
					2) Ư������, ���� ��۷� ���� ��ο� �ش�ɶ����� �����۷�� ��ü �˴ϴ�.<br>
					3) ���� �׸� ���Է½� �⺻�� : �� �̻� �׸��� "0", �� �̸� �׸��� ����, ��۷� �׸��� "0" �Դϴ�.<br>
					4) ���� �׸� �Է½ÿ��� 0 �̻��� ���ڸ� �Է��� �ּ���.<br>
					5) ���� �׸� ������� �ʴ� ������ ������� ó���� �ּ���.<br>
					6) ���� ��۷� ������ ��ġ�� ��� �켱������ 01 ~ 05 �Դϴ�. 
					</TD>
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
				<td height="3"></td>
			</tr>
			<tr>
				<td style="border:4px #EDEDED solid;" bgcolor="#FFF7F0">
				<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
				<tr>
					<td>
					<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
					<col width="50"></col>
					<col width="50"></col>
					<col width=""></col>
					<TR height="36" align="center">
						<TD background="images/blueline_bg.gif"><font color="#666666"><b>���</b></font></TD>
						<TD background="images/blueline_bg.gif" style="border-left:#EDEDED 1px solid;border-right:#EDEDED 1px solid;"><font color="#666666"><b>��ȣ</b></font></TD>
						<TD background="images/blueline_bg.gif"><font color="#666666"><b>������ (����,�갣 ��), ���� ��۷�</b></font></TD>
					</TR>
					<TR>
						<TD colspan="3" bgcolor="#EDEDED" height="1"></TD>
					</TR>
<?
		for($k=0;$k<$maxj;$k++){
?>
					<TR bgColor="#FFFFFF" align="center">
						<TD background="images/blueline_bg.gif" valign="top" style="padding-top:2px;"><input type=checkbox name="up_gradedeliareanum[]" value="Y" id="idx_gradedeliareanum<?=$k?>" onclick="setGradeDeliUse(this.checked,'<?=$k?>');" <?=(strlen($gradedeli_area[$k])>0?"checked":"")?>></td>
						<TD valign="top" style="border-left:#EDEDED 1px solid;border-right:#EDEDED 1px solid;padding-top:6px;"><b><?=($k+1)?></b></td>
						<TD>
						<TABLE cellSpacing=0 cellPadding=0 width="96%" border=0>
						<tr align="center">
							<td height="25">
							<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
							<col width="60"></col>
							<col width=""></col>
							<tr>
								<td><b>������ : </b></td>
								<td><input type=text name="up_gradedeli_area[]" value="<?=$gradedeli_area[$k]?>" id="idx_gradedeli_area<?=$k?>" size="78" style="width:100%; <?=(strlen($gradedeli_area[$k])>0?"\"background:#FFFFFF;\"":"background:#EFEFEF;\"")?>" class="input"></td>
							</tr>
							</table>
							</td>
						</tr>
						<tr id="idx_gradedeliarea<?=$k?>" <?=(strlen($gradedeli_area[$k])>0?"style=\"display:;\"":"style=\"display:none;\"")?>>
							<td>
							<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
							<tr>
								<td style="border:3px #D7D7D7 solid;" bgcolor="#FFF7F0">
								<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
								<col width=""></col>
								<col width="120"></col>
								<tr>
									<td style="border-right:2px #D7D7D7 solid;" height="7"><img width="0" height="0"></td>
									<td></td>
								</tr>
								<tr align="center" height="30">
									<td style="border-right:2px #D7D7D7 solid;"><b>����űݾ׼���</b></td>
									<td><b>��۷�</b></td>
								</tr>
								<tr>
									<td style="border-right:2px #D7D7D7 solid;" height="3"><img width="0" height="0"></td>
									<td></td>
								</tr>
								<tr>
									<td style="padding-left:5px;padding-right:5px;border-right:2px #D7D7D7 solid;"><TABLE cellSpacing=0 cellPadding=0 width="100%" border=0><tr><td height="1" bgcolor="#DADADA"></td></tr></table></td>
									<td style="padding-left:5px;padding-right:5px;"><TABLE cellSpacing=0 cellPadding=0 width="100%" border=0><tr><td height="1" bgcolor="#DADADA"></td></tr></table></td>
								</tr>
								<tr>
									<td style="border-right:2px #D7D7D7 solid;" height="5"><img width="0" height="0"></td>
									<td></td>
								</tr>
								<?
								$j=1;
								for($i=0; $i<$maxi; $i++) { 
								?>
								<tr align="center">
									<td style="padding:5px;padding-bottom:0px;padding-left:0px;border-right:2px #D7D7D7 solid;"><b><?=str_pad($j, 2, "0", STR_PAD_LEFT);?>. </b><input type=text name=up_gradedeli_limitup[<?=$k?>][] value="<?=($i==0?$i:$gradedeli_limitup[$k][$i])?>" <?=($i==0?" readonly":"")?> size=20 maxlength=10 class="input" style="text-align:right;" id="gradedeli<?=$k?>_limitup<?=$i?>"><b>�� �̻�&nbsp;&nbsp;��&nbsp;&nbsp;</b><input type=text name=up_gradedeli_limitdown[<?=$k?>][] value="<?=$gradedeli_limitdown[$k][$i]?>" size=20 maxlength=10 class="input" id="gradedeli<?=$k?>_limitdown<?=$i?>" <?=($j==$maxi?"":" onKeyDown=\"SetValueCopy(this.value,'gradedeli".$k."_limitup".$j."');\" onKeyUp=\"SetValueCopy(this.value,'gradedeli".$k."_limitup".$j."');\"")?> style="text-align:right;"><b>�� �̸�</b></td>
									<td align="center" style="padding:5px;padding-bottom:0px;"><input type=text name=up_gradedeli_limitfee[<?=$k?>][] value="<?=$gradedeli_limitfee[$k][$i]?>" size=12 maxlength=6 class="input" style="text-align:right;" id="deli<?=$k?>_limitfee<?=$i?>"><b>��</b></td>
								</tr>
								<? 
									$j++;
								} 
								?>
								
								<tr>
									<td style="border-right:2px #D7D7D7 solid;" height="7"><img width="0" height="0"></td>
									<td></td>
								</tr>
								</table>
								</td>
							</tr>
							</table>
							</td>
						</tr>
						<tr>
							<td height="2"></td>
						</tr>
						</table>
						</td>
					</tr>
					<TR>
						<TD colspan="3" bgcolor="#EDEDED" height="1"></TD>
					</TR>
<?
		}
?>
					</TABLE>
					</td>
				</tr>
				</table>
				</td>
			</tr>
			<tr>
				<td height=10></td>
			</tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD class="notice_blue" valign="top">



					<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
					<TR>
						<TD class="notice_blue" valign="top"  height="35" ></TD>
					</TR>
					<TR>
						<TD width="100%" class="space"><span class=font_blue><SPAN class="font_orange"><b>Ư��������� �̸��� ��Ȯ�� �Է��ϼ���.</b></SPAN><SPAN class=font_color1><BR></SPAN><SPAN class="font_orange"><b>�߸� �Է½� Ư����������� �ƴ� ���� ��ۺ� �߰� �ɼ� �ֽ��ϴ�.</span></b><SPAN class=font_color1><!--<IMG src="images/icon_addr.gif" align=absMiddle border=0 width="85" height="28">--></span>
						<TABLE class=tdbg cellSpacing=1 cellPadding=3 border=0 width="100%" bgcolor="#D6E0E3">
						<TR class=td3 align=middle>
							<TD bgcolor="#ECFAFF">Ư���������</TD>
							<TD bgcolor="#ECFAFF">�����߻�����</TD>
							<TD bgColor="#ECFAFF">&nbsp;�ٸ��Է�(O)&nbsp;</TD>
							<TD bgcolor="#ECFAFF">Ʋ���Է�(X)</TD>
						</TR>
						<TR class=td3 align=middle>
							<TD bgcolor="#F7FDFF">��� �Է½�</TD>
							<TD bgcolor="#F7FDFF">��õ ������ <FONT style="BACKGROUND-COLOR: #40BFEE">���</FONT>�� / ��� ��õ�� ���и� <FONT style="BACKGROUND-COLOR: #40BFEE">���</FONT>��</TD>
							<TD bgColor="#F7FDFF">��ɸ�</TD>
							<TD bgcolor="#F7FDFF">���, ��ɵ�, ��ɸ�</TD>
						</TR>
						<TR class=td3 align=middle>
							<TD bgcolor="#F7FDFF">���� �Է½�</TD>
							<TD bgcolor="#F7FDFF">�泲 <FONT style="BACKGROUND-COLOR: #40BFEE">����</FONT>�� <FONT style="BACKGROUND-COLOR: #40BFEE">����</FONT>�� / �λ� ������ <FONT style="BACKGROUND-COLOR: #40BFEE">����</FONT>��<BR>���� ������ ����� <FONT style="BACKGROUND-COLOR: #40BFEE">����</FONT>��</TD>
							<TD bgColor="#F7FDFF">������</TD>
							<TD bgcolor="#F7FDFF">����, ������, ������</TD>
						</TR>
						<TR class=td3 align=middle>
							<TD bgcolor="#F7FDFF">���ֽ� �Է½�</TD>
							<TD bgcolor="#F7FDFF">���� �����ֱ� �ֿ��� ���ֱص����<BR>���� �������� ��ȫ�� �����ֱ�û<BR>���� <FONT style="BACKGROUND-COLOR: #40BFEE">���ֽ�</FONT> ���Ե� ���������ؾ����û</TD>
							<TD bgColor="#F7FDFF">����</TD>
							<TD bgcolor="#F7FDFF">���ֽ�, ���ֱ�</TD>
						</TR>
						</TABLE>
						</TD>
					</TR>
					</TABLE>



					</TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr>
				<td height="10"></td>
			</tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/shop_deli_stitle4.gif" WIDTH="151" HEIGHT=31 ALT=""></TD>
					<TD width="100%" background="images/shop_basicinfo_stitle_bg.gif">&nbsp;</TD>
					<TD><IMG SRC="images/shop_basicinfo_stitle_end.gif" WIDTH=10 HEIGHT=31 ALT=""></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr>
				<td height=3></td>
			</tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/distribute_01.gif"></TD>
					<TD COLSPAN=2 background="images/distribute_02.gif"></TD>
					<TD><IMG SRC="images/distribute_03.gif"></TD>
				</TR>
				<TR>
					<TD background="images/distribute_04.gif"></TD>
					<TD class="notice_blue"><IMG SRC="images/distribute_img.gif" ></TD>
					<TD width="100%" class="notice_blue">�����ۺ� + Ư������ ��ۺ� �߰� �� ������ �����մϴ�.</TD>
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
				<td height=3></td>
			</tr>
			<tr>
				<td><span class=font_orange>* �߰���۷� ���ν�(-) �Է��ϼ���.</span></td>
			</tr>
			<tr>
				<td>
				<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
				<TR>
					<TD background="images/table_top_line.gif" colspan="3"></TD>
				</TR>
				<TR bgColor=#f0f0f0 height=26>
					<TD class="table_cell" align=middle width="31">��ȣ</TD>
					<TD class="table_cell1" align=middle >������ (����,�갣 ��)</TD>
					<TD class="table_cell1" align=middle width="112">�߰���۷� (+,-)</TD>
				</TR>
				<TR>
					<TD colspan="3" background="images/table_con_line.gif"></TD>
				</TR>
<?
		$array_deli=explode("|",$deli_area);
		for($i=0;$i<$maxnum;$i++){
?>
						<TR bgColor=#ffffff height=25>
							<TD class="table_cell" align=middle bgColor=#f0f0f0 width="31"><?=($i+1)?></td>
							<TD class="td_con1" style="PADDING-LEFT: 10px" align=left><input type=text name=up_deliarea size=78 style="width:99%" value="<?=$array_deli[$i*2]?>" class=input></td>
							<TD class="td_con1" align=middle width="120"><input type=text name=up_deliareaprice size=15 maxlength=10 value="<?=$array_deli[$i*2+1]?>" class=input></td>
						</tr>
						<TR>
							<TD colspan="3" background="images/table_con_line.gif"></TD>
						</TR>
<?
}
?>
				<TR>
					<TD background="images/table_top_line.gif" colspan="3"></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr>
				<td height="20"></td>
			</tr>
			<!--<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/shop_deli_stitle5.gif" WIDTH="151" HEIGHT=31 ALT=""></TD>
					<TD width="100%" background="images/shop_basicinfo_stitle_bg.gif">&nbsp;</TD>
					<TD><IMG SRC="images/shop_basicinfo_stitle_end.gif" WIDTH=10 HEIGHT=31 ALT=""></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr>
				<td height=3></td>
			</tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/distribute_01.gif"></TD>
					<TD COLSPAN=2 background="images/distribute_02.gif"></TD>
					<TD><IMG SRC="images/distribute_03.gif"></TD>
				</TR>
				<TR>
					<TD background="images/distribute_04.gif"></TD>
					<TD class="notice_blue"><IMG SRC="images/distribute_img.gif" ></TD>
					<TD width="100%" class="notice_blue">���� ��� ������ڸ� �Է��� �� �ֽ��ϴ�.</TD>
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
				<td height=3></td>
			</tr>
			<tr>
				<td>
<? 
		if (strlen($time1)==0) $time1=8;
		if (strlen($time2)==0) $time2=21;
		$deliverydate = explode("=",$order_msg);
		if (strlen($deliverydate[1])==6) {
			$wantdate="Y";
			$day1=substr($deliverydate[1],0,2);
			$time1=substr($deliverydate[1],2,2);
			$time2=substr($deliverydate[1],4,2);
		} else if (strlen($deliverydate[1])>0) {
			$wantdate="A";
			$day1=substr($deliverydate[1],0,2);
		} else {
			$wantdate="N";
		}

		$bankname=$deliverydate[2];
		if (strlen($bankname)==0) $bankname="N";
?>
				<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
				<TR>
					<TD colspan=2 background="images/table_top_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell" width="120"><img src="images/icon_point2.gif" width="8" height="11" border="0">��� ������� Ÿ��</TD>
					<TD class="td_con1"><input type=radio class="radio" id="idx_wantdate1" name=up_wantdate value="Y" <?if($wantdate=="Y") echo "checked"?> onclick="SetDisplayTime(false)"><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_wantdate1>00�� 00�� 00��(<b>�ð�����</b>)</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type=radio class="radio" id="idx_wantdate2" name=up_wantdate value="A" <?if($wantdate=="A") echo "checked"?> onclick="SetDisplayTime(true)"><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_wantdate2>00�� 00��(<b>���ڱ���</b>)</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type=radio class="radio" id="idx_wantdate3" name=up_wantdate value="N" <?if($wantdate=="N") echo "checked"?> onclick="SetDisplayTime(true)"><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_wantdate3>������</label></TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell" width="120"><img src="images/icon_point2.gif" width="8" height="11" border="0">��۰�������</TD>
					<TD class="td_con1" >�ֹ��� <select name=up_day1 class="select">
<?
			for ($i=0;$i<30;$i++) {
				$temp=substr("0".$i,-2);
				echo "<option vlaue=".$temp;
				if($day1==$temp) echo " selected";
				echo ">".$temp;
			}
?>
						</select>�� �������� ��� ����
					</TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>													
				<TR>
					<TD class="table_cell" width="120"><img src="images/icon_point2.gif" width="8" height="11" border="0">��۰��ɽð�</TD>
					<TD class="td_con1" ><select name=up_time1 class="select">
<?
			for ($i=0;$i<24;$i++) {
				$temp=substr("0".$i,-2);
				echo "<option vlaue=$temp";
				if($time1==$temp) echo " selected";
				echo ">$temp";
			}
?>
						</select>�� ~ <select name=up_time2 class="select">
<?
			for ($i=0;$i<24;$i++) {
				$temp=substr("0".$i,-2);
				echo "<option vlaue=$temp";
				if($time2==$temp) echo " selected";
				echo ">$temp";
			}
?>
						</select>�ñ���
					</TD>
				</TR>
				<TR>
					<TD colspan=2 background="images/table_top_line.gif"></TD>
				</TR>
				</TABLE>
				</td>
			</tr> -->
			<? if($wantdate!="Y") echo "<script>SetDisplayTime(true)</script>"; ?>
			<tr>
				<td><p>&nbsp;</p></td>
			</tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/shop_deli_stitle6.gif" WIDTH="151" HEIGHT=31 ALT=""></TD>
					<TD width="100%" background="images/shop_basicinfo_stitle_bg.gif">&nbsp;</TD>
					<TD><IMG SRC="images/shop_basicinfo_stitle_end.gif" WIDTH=10 HEIGHT=31 ALT=""></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr>
				<td height=3></td>
			</tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/distribute_01.gif"></TD>
					<TD COLSPAN=2 background="images/distribute_02.gif"></TD>
					<TD><IMG SRC="images/distribute_03.gif"></TD>
				</TR>
				<TR>
					<TD background="images/distribute_04.gif"></TD>
					<TD class="notice_blue"><IMG SRC="images/distribute_img.gif" ></TD>
					<TD width="100%" class="notice_blue">�ֹ��ڿ� �Ա��ڸ��� �ٸ� ��� �ֹ��� �Ա��ڸ��� ���� �� �ֽ��ϴ�.</TD>
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
				<td height=3></td>
			</tr>
			<tr>
				<td>
				<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
				<TR>
					<TD colspan=2 background="images/table_top_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell" width="120"><img src="images/icon_point2.gif" width="8" height="11" border="0">�Ա��ڸ� ����</TD>
					<TD class="td_con1"><p><input type=radio id="idx_bankname1" name=up_bankname value="Y" <?if($bankname=="Y") echo "checked"?>> <label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_bankname1>�Ա��ڸ� ����</label>  &nbsp;&nbsp;<input type=radio id="idx_bankname2" name=up_bankname value="N" <?if($bankname=="N") echo "checked"?>> <label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_bankname2>�Ա��ڸ� ���� ����</label></p></TD>
				</TR>
				<TR>
					<TD colspan=2 background="images/table_top_line.gif"></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr>
				<td height=10></td>
			</tr>
			<tr>
				<td align="center"><a href="javascript:CheckForm();"><img src="images/botteon_save.gif" width="113" height="38" border="0"></a></td>
			</tr>
			</form>
			<tr>
				<td height=20></td>
			</tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/manual_top1.gif" WIDTH=15 HEIGHT=45 ALT=""></TD>
					<TD><IMG SRC="images/manual_title.gif" WIDTH=113 HEIGHT=45 ALT=""></TD>
					<TD width="100%" background="images/manual_bg.gif"></TD>
					<TD background="images/manual_bg.gif"></TD>
					<TD><IMG SRC="images/manual_top2.gif" WIDTH=18 HEIGHT=45 ALT=""></TD>
				</TR>
				<TR>
					<TD background="images/manual_left1.gif"><IMG SRC="images/manual_left1.gif" WIDTH=15 HEIGHT="5" ALT=""></TD>
					<TD COLSPAN=3 width="100%" valign="top" bgcolor="white" style="padding-top:8pt; padding-bottom:8pt; padding-left:4pt;" class="menual_bg">
					<table cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td width="20" align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td width="100"><span class="font_dotline">�����۷�</span></td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td width="100%" class="space_top">- ��۷�� [����,����,����] �����մϴ�. ����/���� ���ý� ��۷Ḧ ���� ���� �ʾƵ� �˴ϴ�.</td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td width="100%" class="space_top">- �����۷� ���� ������ۺ� ��ǰ�� ������ ��� ��ǰ�� �����ϰ� ���˴ϴ�.</td>
					</tr>
					<tr>
						<td colspan="2" height="20"></td>
					</tr>
					<tr>
						<td width="20" align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td width="100%"><span class="font_dotline">������۷�</span></td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td width="100%" class="space_top">- ������۷�� ��ǰ���/�������� ������۷Ḧ ������ �� �ֽ��ϴ�.<br>
						<b>&nbsp;&nbsp;</b>������۷� ���� �ش� ��ǰ�� �⺻ ��۷ᰡ �ƴ� ������۷� ������ ������ �˴ϴ�.</td>
					</tr>
					<tr>
						<td colspan="2" height="5"></td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td width="100%" class="space_top"><b>&nbsp;&nbsp;</b><b>�� ���ż����� ��۷� û��</b>(������ۺ� ��ǰ������ ���� ����)<br>
						<b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b>- ������ۺ� ��ǰ ���ż����� ��۷Ḧ û���� �� �ֽ��ϴ�.<br>
						<b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b>- ��ǰ���/�������� ������۷� ����, ��۷� Ÿ�� ����ǰ�� ��� ��۷� ������ üũ<br>
						<b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b>��) ������ۺ� 2,000���� ����ǰ�� ��� ��۷� ������ üũ���� ������ ��ǰ(5,000��) 5�� ����<br>
						<b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b><b>[</b>��ǰ����(5,000��) + ������ۺ�(2,000��)<b>]</b> �� ����(5��) = 35,000��<br>
						</td>
					</tr>
					<tr>
						<td colspan="2" height="5"></td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td width="100%" class="space_top"><b><font color="#0000FF">��) �����۷� ����Ǵ� ���</font></b>(���ž� 3���� �̸��϶� �����۷� 2,500��, ������۷� 2,000��)<br>
						<b>&nbsp;&nbsp;&nbsp;&nbsp;</b>&nbsp;�⺻��ǰ(12,000��) + ������۷� ��ǰ(15,000��) = 27,000��<br>
						<b>&nbsp;&nbsp;&nbsp;&nbsp;</b>&nbsp;�⺻��ǰ ��۷�(2,500��) + ������۷� ��ǰ ��۷�(2,000��) = 4,500��<br>
						<b>&nbsp;&nbsp;&nbsp;&nbsp;</b>&nbsp;�� �����ݾ� = 31,500��<br>
						</td>
					</tr>
					<tr>
						<td colspan="2" height="5"></td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td width="100%" class="space_top"><b><font color="#FF0000">��) �����۷� ������� ���� ���</font></b>(���ž� 3���� �̸��϶� �����۷� 2,500��, ������۷� 2,000��)<br>
						<b>&nbsp;&nbsp;&nbsp;&nbsp;</b>&nbsp;�⺻��ǰ(20,000��) + ������۷� ��ǰ(15,000��) = 35,000��<br>
						<b>&nbsp;&nbsp;&nbsp;&nbsp;</b>&nbsp;�⺻��ǰ ��۷�(0��) + ������۷� ��ǰ ��۷�(2,000��) = 2,000��<br>
						<b>&nbsp;&nbsp;&nbsp;&nbsp;</b>&nbsp;�� �����ݾ� = 37,000��<br>
						</td>
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