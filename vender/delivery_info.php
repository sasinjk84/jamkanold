<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/venderlib.php");
INCLUDE ("access.php");

$maxnum=10;
$maxlength=2000;
$maxj=5; //���������۷�
$maxi=5; //������ ����
$mode=$_POST["mode"];
if($mode=="update") {
	$up_deli_pricetype="Y";
	$up_deli_info1=$_POST["up_deli_info1"];
	$up_deli_info2=$_POST["up_deli_info2"];
	$up_deli_info3=$_POST["up_deli_info3"];
	$up_deli_info4=$_POST["up_deli_info4"];
	$up_deli_info5=$_POST["up_deli_info5"];
	$up_deli_area=$_POST["up_deli_area"];
	$up_delivery=$_POST["up_delivery"];
	$up_deli_price=$_POST["up_deli_price".$up_delivery];
	$up_deli_mini=$_POST["up_deli_mini"];
	$deli_com=$_POST["deli_com"];

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

	if($up_delivery=="F") $up_deli_price=0;
	else if($up_delivery=="S") $up_deli_price=-8;
	else if($up_delivery=="Y") $up_deli_price=-9;
	else if($up_delivery=="N" || $up_delivery=="Q") $up_deli_pricetype="N";
	else if(strlen($up_deli_price) < 1) $up_deli_price = 0;

	$sql = "UPDATE tblvenderinfo SET ";
	$sql.= "deli_info1		= '".$up_deli_info1."', ";
	$sql.= "deli_info2		= '".$up_deli_info2."', ";
	$sql.= "deli_info3		= '".$up_deli_info3."', ";
	$sql.= "deli_info4		= '".$up_deli_info4."', ";
	$sql.= "deli_info5		= '".$up_deli_info5."', ";
	$sql.= "deli_super		= '".$up_delivery."', ";
	$sql.= "deli_price		= '".$up_deli_price."', ";
	$sql.= "deli_pricetype	= '".$up_deli_pricetype."', ";
	$sql.= "deli_mini		= '".$up_deli_mini."', ";
	$sql.= "deli_area		= '".$up_deli_area."', ";
	$sql.= "deli_limit		= '".$up_deli_limit."', ";
	$sql.= "deli_area_limit	= '".$up_deli_area_limit."', ";
	$sql.= "deli_company	= '".$deli_com."' ";
	$sql.= "WHERE vender='".$_VenderInfo->getVidx()."' ";

	if(mysql_query($sql,get_db_conn())) {
		echo "<html></head><body onload=\"alert('��û�Ͻ� �۾��� �����Ͽ����ϴ�.');parent.location.reload()\"></body></html>";exit;
	} else {
		echo "<html></head><body onload=\"alert('��û�Ͻ� �۾��� ������ �߻��Ͽ����ϴ�.')\"></body></html>";exit;
	}
}

$deli_info1 = $_venderdata->deli_info1;
$deli_info2 = $_venderdata->deli_info2;
$deli_info3 = $_venderdata->deli_info3;
$deli_info4 = $_venderdata->deli_info4;
$deli_info5 = $_venderdata->deli_info5;

$deli_price = $_venderdata->deli_price;
$deli_pricetype = $_venderdata->deli_pricetype;
$deli_limit = $_venderdata->deli_limit;
$deli_area_limit = $_venderdata->deli_area_limit;
if(strlen($deli_limit)>0) {
	if($deli_pricetype == "Y")
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
	if($deli_price==-9) $delivery="Y";
	else if($deli_price==-8) $delivery="S";
	else if($deli_price==0) $delivery="F";
	else {
		if($deli_pricetype == "Y")
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
if($deli_price<0) $deli_price=0;
$deli_mini = $_venderdata->deli_mini;
if($deli_mini<1) $deli_mini=1;
$deli_area = $_venderdata->deli_area;

$delicomlist=array();
$sql="SELECT code, company_name FROM tbldelicompany ORDER BY code ";
$result=mysql_query($sql,get_db_conn());
while($row=mysql_fetch_object($result)) {
	$delicomlist[]=$row;
}
mysql_free_result($result);

?>

<? INCLUDE "header.php"; ?>

<script type="text/javascript" src="lib.js.php"></script>
<script language="JavaScript">
function CheckForm() {
	var form = document.form1;
	if(form.up_delivery[3].checked==true){
		if (form.up_deli_priceM.value.length==0) {
			alert("��۷Ḧ �Է��ϼ���.");
			form.up_deli_priceM.focus();
			return;
		} else if (isNaN(form.up_deli_priceM.value)) {
			alert("��۷�� ���ڸ� �Է� �����մϴ�.");
			form.up_deli_priceM.focus();
			return;
		} else if(form.up_deli_priceM.value<=0) {
			alert("��۷�� 0�� �̻� �Է��ϼž� �մϴ�.");
			form.up_deli_priceM.focus();
			return;
		}
	}
	if(form.up_delivery[4].checked==true){
		if (form.up_deli_priceN.value.length==0) {
			alert("��۷Ḧ �Է��ϼ���.");
			form.up_deli_priceN.focus();
			return;
		} else if (isNaN(form.up_deli_priceN.value)) {
			alert("��۷�� ���ڸ� �Է� �����մϴ�.");
			form.up_deli_priceN.focus();
			return;
		} else if(form.up_deli_priceN.value<=0) {
			alert("��۷�� 0�� �̻� �Է��ϼž� �մϴ�.");
			form.up_deli_priceN.focus();
			return;
		}
	}
	if(form.up_delivery[5].checked==true || form.up_delivery[6].checked==true){
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
				alert('��۷�±ݾ��� 0 �̻��� ���ڸ� �Է� �����մϴ�.');
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
	if(confirm("�����Ͻ� ������ �����Ͻðڽ��ϱ�?")) {
		form.mode.value="update";
		form.target="processFrame";
		form.submit();
	}
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
			document.form1.up_deli_priceM.disabled=false;
			document.form1.up_deli_priceM.style.background='#FFFFFF';
			document.form1.up_deli_priceN.disabled=true;
			document.form1.up_deli_priceN.style.background='#C0C0C0';
		} else {
			document.form1.up_deli_priceM.disabled=true;
			document.form1.up_deli_priceM.style.background='#C0C0C0';
			document.form1.up_deli_priceN.disabled=false;
			document.form1.up_deli_priceN.style.background='#FFFFFF';
		}
		document.form1.up_deli_mini.disabled=false;
		document.form1.up_deli_mini.style.background='#FFFFFF';
	} else {
		document.form1.up_deli_priceM.disabled=true;
		document.form1.up_deli_priceM.style.background='#C0C0C0';
		document.form1.up_deli_priceN.disabled=true;
		document.form1.up_deli_priceN.style.background='#C0C0C0';
		document.form1.up_deli_mini.disabled=true;
		document.form1.up_deli_mini.style.background='#C0C0C0';
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
			document.getElementById("deli_limitup"+i).style.background='#C0C0C0';
			document.getElementById("deli_limitdown"+i).disabled=true;
			document.getElementById("deli_limitdown"+i).style.background='#C0C0C0';
			document.getElementById("deli_limitfee"+i).disabled=true;
			document.getElementById("deli_limitfee"+i).style.background='#C0C0C0';
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

<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
<col width=190></col>
<col width=20></col>
<col width=></col>
<col width=5></col>
<tr>
	<td width=190 valign=top nowrap background="images/minishop_leftbg.gif"><? include ("menu.php"); ?></td>
	<td width=20 nowrap></td>
	<td valign=top style="padding-top:20px">

	<table width="100%"  border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td>
		<table width="100%"  border="0" cellpadding="0" cellspacing="0" >
		<tr>
			<td>
				<table border=0 cellpadding=0 cellspacing=0 width=100% >
				<tr>
					<td><img src="images/delivery_info_title.gif"></td>
				</tr>
				<tr>
					<td height=5 background="images/minishop_titlebg.gif">
				</tr>
				</table>
			</td>
		</tr>
		<tr><td height=10></td></tr>
		<tr>
			<td>
				<table border=0 cellpadding=0 cellspacing=0 width=100% >
				<tr>
					<td colspan=3 >


						<table cellpadding="10" cellspacing="1" width="100%" bgcolor="#EFEFF2">
							<tr>
								<td  bgcolor="#F5F5F9" style="padding:20px">
									<table border=0 cellpadding=0 cellspacing=0 width=100%>
										<tr>
											<td class="notice_gray" height="20"><img src="images/icon_dot02.gif" border=0 hspace="4">���޾�ü�� ��۷� ������ �����Ͻ� �� �ֽ��ϴ�.</td>
										</tr>
										<tr>
											<td class="notice_gray" height="20"><img src="images/icon_dot02.gif" border=0 hspace="4">Vender �����ڰ� ����� ��ǰ��ۺ�� ���� ����Ʈ�� �Էµ� ��ۺ� �������� ������� �ʽ��ϴ�.</td>
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

			<!-- ó���� ���� ��ġ ���� -->
			<tr><td height=40></td></tr>
			<tr>
				<td>

				<table border=0 cellpadding=0 cellspacing=0 width=100%>

				<form name=form1 method=post action="<?=$_SERVER[PHP_SELF]?>">
				<input type=hidden name=mode>
				<input type=hidden name=up_deli_area>
				<input type=hidden name=deli_com>

				<tr>
					<td><img src="images/delivery_info_stitle04.gif"></td>
				</tr>
				<tr><td height=5></td></tr>
				<tr><td height=1 bgcolor=#cccccc></td></tr>
				</table>

				<table border=0 cellpadding=7 cellspacing=1 bgcolor=#dddddd width=100%>				
				<col width=120></col>
				<col width=></col>
				<tr>
					<td bgcolor=#f0f0f0 style="padding-left:10;"><b>���� �ù�� ����</b></td>
					<td bgcolor=#ffffff style="padding-left:10">
						<select name="deli_com" id="deli_com">
						<?
							$sql3="SELECT deli_company FROM tblvenderinfo WHERE vender='".$_VenderInfo->getVidx()."'";
							$result3=mysql_query($sql3,get_db_conn());
							$row2 = mysql_fetch_object($result3);

							if($row2->deli_company==""){
						?>
								<option value="">����</option>
						<?
							} else {
								$company_code;
								for($i=0; $i<count($delicomlist);$i++){
									if($delicomlist[$i]->code == $row2->deli_company){
										$company_code = $i;
									}
								}
						?>
								<option value="<?=$row2->deli_company?>"><?=$delicomlist[$company_code]->company_name?></option>
						<?
							}
						?>
						<?
						for($yy=0;$yy<count($delicomlist);$yy++) {
							if($pg_type=="B" && preg_match("/^(Q|P){1}/", $_ord->paymethod)) {
								if(strlen($delicomlist[$yy]->dacom_code)>0) {
									echo "<option value=\"".$delicomlist[$yy]->code."\">".$delicomlist[$yy]->company_name."</option>\n";
								}
							} else {
								echo "<option value=\"".$delicomlist[$yy]->code."\">".$delicomlist[$yy]->company_name."</option>\n";
							}
						} ?>
						</select>
					</td>
				</tr>
				</table>
				<table width=100% cellpadding=0 cellspacing=0>

				<tr><td height=20></td></tr>

				<tr>
					<td><img src="images/delivery_info_stitle01.gif"></td>
				</tr>
				<tr><td height=5></td></tr>
				<tr><td height=1 bgcolor=#cccccc></td></tr>
				</table>

				<table border=0 cellpadding=7 cellspacing=1 bgcolor=#dddddd width=100%>
				<col width=120></col>
				<col width=></col>
				<TR>
					<TD bgcolor=#f0f0f0 style="padding-left:10"><b>�ù�߼۾ȳ�����</b></TD>
					<TD bgcolor=#ffffff style="padding-left:10">
						<input type=text name=up_deli_info1 size=50 value="<?=$deli_info1?>">
						��)16�� ���� �ֹ� �� ���� ���
					</TD>
				</TR>
				<TR>
					<TD bgcolor=#f0f0f0 style="padding-left:10"><b>����/���� ��۷�</b></TD>
					<TD bgcolor=#ffffff style="padding-left:10">
					<input type=radio id="idx_delivery0" name=up_delivery value="S" <?=($delivery=="S"?"checked":"")?> onClick="SetDeliChange('','');"><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_delivery0>��۷� <font color='#ff0000'><b>�������չ��</b></font></label><br>

					<input type=radio id="idx_delivery1" name=up_delivery value="F" <?=($delivery=="F"?"checked":"")?> onClick="SetDeliChange('','');"><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_delivery1>��۷� <font color='#0000FF'><b>����</b></font></label><br>

					<input type=radio id="idx_delivery2" name=up_delivery value="Y" <?=($delivery=="Y"?"checked":"")?> onClick="SetDeliChange('','');"><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_delivery2>��۷� <font color='#38A422'><b>����</b></font></label>&nbsp;<span class=notice_blue>* ������ ���� ��ٱ��Ͽ� ��۷ᰡ �Һ��� �δ��̶�� ������ ��µ˴ϴ�.</span>
					</td>
				</TR>
				<tr>
					<TD bgcolor=#f0f0f0 style="padding-left:10"><b>���� ���� ��۷�</b></TD>
					<TD bgcolor=#ffffff style="padding-left:10">
					<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
					<tr>
						<td><input type=radio id="idx_delivery3" name=up_delivery value="M" <?=($delivery=="M"?"checked":"")?> onClick="SetDeliChange('A','M')"><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_delivery3>��۷� <font color='#FF0000'><b>����</b></font>(��ۺ� ����� ������� ��ǰ�ݾ׵� <font color='#0000FF'><b>����</b></font>)</label>: <input type=text name=up_deli_priceM size=10 maxlength=6 value="<?=($delivery == "M"?$deli_price:"")?>" style="text-align:right;" disabled style="background-Color:#C0C0C0;">��<br>

						<input type=radio id="idx_delivery4" name=up_delivery value="N" <?=($delivery=="N"?"checked":"")?> onClick="SetDeliChange('A','N')"><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_delivery4>��۷� <font color='#FF0000'><b>����</b></font>(��ۺ� ����� ������� ��ǰ�ݾ��� <font color='#FF0000'><b>����</b></font>)</label>: <input type=text name=up_deli_priceN size=10 maxlength=6 value="<?=($delivery == "N"?$deli_price:"")?>" style="text-align:right;" disabled style="background-Color:#C0C0C0;">��</td>
					</tr>
					<tr>
						<td height="5"></td>
					</tr>
					<tr>
						<td style="padding-left:20px;">
						<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
						<tr>
							<td >���űݾ� <input type=text name=up_deli_mini size=10 maxlength=10 value="<?=$deli_mini?>" style="text-align:right;">�� �̸��� ��� ��ۺ� û���˴ϴ�.<br><span style="font-size:8pt;color:#2A97A7;line-height:11pt;letter-spacing:-0.5pt;"><!-- * ���űݾ� 0 �� �Է½� ��� �ݾ׿� ��ۺ� �ΰ��˴ϴ�. --></span></TD>
						</tr>
						</table>
						</td>
					</tr>
					</table>
					</td>
				</tr>
				<tr>
					<TD bgcolor=#f0f0f0 style="padding-left:10"><b>���� ���� ��۷�</b></TD>
					<TD bgcolor=#ffffff style="padding-left:10">
					<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
					<tr>
						<td><input type=radio id="idx_delivery5" name=up_delivery value="P" <?=($delivery=="P"?"checked":"")?> onClick="SetDeliChange('B','P')"><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_delivery5>��۷� <font color='#FF0000'><b>����</b></font>(��ۺ� ����� ������� ��ǰ�ݾ׵� <font color='#0000FF'><b>����</b></font>)</label><br>

						<input type=radio id="idx_delivery6" name=up_delivery value="Q" <?=($delivery=="Q"?"checked":"")?> onClick="SetDeliChange('B','Q')"><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_delivery6>��۷� <font color='#FF0000'><b>����</b></font>(��ۺ� ����� ������� ��ǰ�ݾ��� <font color='#FF0000'><b>����</b></font>)</label></td>
					</tr>
					<tr>
						<td height="5"></td>
					</tr>
					<tr>
						<td style="padding-left:20px;">
						<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
						<tr>
							<td style="border:3px #FF7100 solid;" bgcolor="#FCFCFC">
							<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
							<col width=""></col>
							<col width="120"></col>
							<tr>
								<td style="border-right:2px #FF7100 solid;" height="7"><img width="0" height="0"></td>
								<td></td>
							</tr>
							<tr align="center" height="30">
								<td style="border-right:2px #FF7100 solid;"><b>����<img width="10" height="0">���űݾ�<img width="10" height="0">����</b></td>
								<td><b>��<img width="15" height="0">��<img width="15" height="0">��</b></td>
							</tr>
							<tr>
								<td style="border-right:2px #FF7100 solid;" height="3"><img width="0" height="0"></td>
								<td></td>
							</tr>
							<tr>
								<td style="padding-left:5px;padding-right:5px;border-right:2px #FF7100 solid;"><TABLE cellSpacing=0 cellPadding=0 width="100%" border=0><tr><td height="1" bgcolor="#DADADA"></td></tr></table></td>
								<td style="padding-left:5px;padding-right:5px;"><TABLE cellSpacing=0 cellPadding=0 width="100%" border=0><tr><td height="1" bgcolor="#DADADA"></td></tr></table></td>
							</tr>
							<tr>
								<td style="border-right:2px #FF7100 solid;" height="5"><img width="0" height="0"></td>
								<td></td>
							</tr>
							<?
							$j=1;
							for($i=0; $i<$maxi; $i++) {
							?>
							<tr align="center">
								<td style="padding:5px;padding-bottom:0px;padding-left:0px;border-right:2px #FF7100 solid;"><b><?=str_pad($j, 2, "0", STR_PAD_LEFT);?>. </b><input type=text name=up_deli_limitup[] value="<?=($i==0?$i:$deli_limitup[$i])?>" <?=($i==0?" readonly":"")?> size=14 maxlength=10 style="text-align:right;" id="deli_limitup<?=$i?>"><b>�� �̻�&nbsp;&nbsp;��&nbsp;&nbsp;</b><input type=text name=up_deli_limitdown[] value="<?=$deli_limitdown[$i]?>" size=14 maxlength=10 id="deli_limitdown<?=$i?>" <?=($j==$maxi?"":" onKeyDown=\"SetValueCopy(this.value,'deli_limitup".$j."');\" onKeyUp=\"SetValueCopy(this.value,'deli_limitup".$j."');\"")?> style="text-align:right;"><b>�� �̸�</b></td>
								<td align="center" style="padding:5px;padding-bottom:0px;"><input type=text name=up_deli_limitfee[] value="<?=$deli_limitfee[$i]?>" size=12 maxlength=6 style="text-align:right;" id="deli_limitfee<?=$i?>"><b>��</b></td>
							</tr>
							<?
								$j++;
							}
							?>

							<tr>
								<td style="border-right:2px #FF7100 solid;" height="7"><img width="0" height="0"></td>
								<td></td>
							</tr>
							</table>
							</td>
						</tr>
						<tr>
							<td style="padding:2px;"><span class=notice_blue>* ���Է½� �⺻�� : �� �̻� �׸��� "0", �� �̸� �׸��� ����, ��۷� �׸��� "0" �Դϴ�.<br>
							* �Է½ÿ��� 0 �̻��� ���ڸ� �Է��� �ּ���.<br>* ������� �ʴ� ������ ������� ó���� �ּ���.<br>* ���� ��۷� ������ ������ �ʴ� ���űݾ��� ��۷� ���ᰡ �˴ϴ�.<br>* ���� ��۷� ������ ��ġ�� ��� �켱������ 01 ~ 05 �Դϴ�.
							</span></td>
						</tr>
						</table>
						</td>
					</tr>
					</table>
					</td>
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

				</table>
				<table border=0 cellpadding=0 cellspacing=0 width=100%>
				<tr>
					<td style="padding:5,0,0,5;"><span class=notice_blue>
					- ����/����, ����, ���� �׸� �� �ϳ��� ���� �����մϴ�.
					<br>
					- �⺻��۷� ���� �Ǵ� ���� ���ý� �߰��� ������ �׸��� �����ϴ�.
					<br>
					- ���� �Ǵ� ���� ���� ��۷� ���ý� �߰��� �ʿ��� �׸��� Ȯ���Ͻ� �� �Է��� �ּ���.
					<br>
					- ��ǰ ���/���� ������������ ���������� ��۷Ḧ ������ �� �ֽ��ϴ�.
					<br>
					- ��ǰ ���/���� ���������� ������ۺ� ����� ��� �ش� ��ǰ�� <b>�߰��� ������ۺ� û��</b> �˴ϴ�.
					</td>
				</tr>
				</table>

				<table border=0 cellpadding=0 cellspacing=0 width=100%>
				<tr><td height=20></td></tr>
				<tr>
					<td><img src="images/delivery_info_stitle02.gif"></td>
				</tr>
				<tr><td height=5></td></tr>
				<tr><td height=1 bgcolor=#cccccc></td></tr>
				</table>

				<table border=0 cellpadding=0 cellspacing=1 bgcolor=#dddddd width=100%>
				<tr>
					<td bgcolor=#ffffff>
					<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
					<tr>
						<td>
						<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
						<col width="50"></col>
						<col width="50"></col>
						<col width=""></col>
						<TR height="36" align="center">
							<TD bgcolor=#f0f0f0><b>���</b></TD>
							<TD bgcolor=#f0f0f0 style="border-left:#DDDDDD 1px solid;border-right:#DDDDDD 1px solid;"><b>��ȣ</b></TD>
							<TD bgcolor=#f0f0f0><b>������ (����,�갣 ��), ���� ��۷�</b></TD>
						</TR>
						<TR>
							<TD colspan="3" bgcolor="#DDDDDD" height="1"></TD>
						</TR>
<?
		for($k=0;$k<$maxj;$k++){
?>
						<TR bgColor="#FFFFFF" align="center">
							<TD valign="top" style="padding-top:4px;"><input type=checkbox name="up_gradedeliareanum[]" value="Y" id="idx_gradedeliareanum<?=$k?>" onclick="setGradeDeliUse(this.checked,'<?=$k?>');" <?=(strlen($gradedeli_area[$k])>0?"checked":"")?>></td>
							<TD valign="top" style="border-left:#DDDDDD 1px solid;border-right:#DDDDDD 1px solid;padding-top:4px;"><b><?=($k+1)?></b></td>
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
									<td style="border:3px #FF7100 solid;" bgcolor="#FFF7F0">
									<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
									<col width=""></col>
									<col width="120"></col>
									<tr>
										<td style="border-right:2px #FF7100 solid;" height="7"><img width="0" height="0"></td>
										<td></td>
									</tr>
									<tr align="center" height="30">
										<td style="border-right:2px #FF7100 solid;"><b>����<img width="10" height="0">���űݾ�<img width="10" height="0">����</b></td>
										<td><b>��<img width="15" height="0">��<img width="15" height="0">��</b></td>
									</tr>
									<tr>
										<td style="border-right:2px #FF7100 solid;" height="3"><img width="0" height="0"></td>
										<td></td>
									</tr>
									<tr>
										<td style="padding-left:5px;padding-right:5px;border-right:2px #FF7100 solid;"><TABLE cellSpacing=0 cellPadding=0 width="100%" border=0><tr><td height="1" bgcolor="#DADADA"></td></tr></table></td>
										<td style="padding-left:5px;padding-right:5px;"><TABLE cellSpacing=0 cellPadding=0 width="100%" border=0><tr><td height="1" bgcolor="#DADADA"></td></tr></table></td>
									</tr>
									<tr>
										<td style="border-right:2px #FF7100 solid;" height="5"><img width="0" height="0"></td>
										<td></td>
									</tr>
									<?
									$j=1;
									for($i=0; $i<$maxi; $i++) {
									?>
									<tr align="center">
										<td style="padding:5px;padding-bottom:0px;padding-left:0px;border-right:2px #FF7100 solid;"><b><?=str_pad($j, 2, "0", STR_PAD_LEFT);?>. </b><input type=text name=up_gradedeli_limitup[<?=$k?>][] value="<?=($i==0?$i:$gradedeli_limitup[$k][$i])?>" <?=($i==0?" readonly":"")?> size=18 maxlength=10 class="input" style="text-align:right;" id="gradedeli<?=$k?>_limitup<?=$i?>"><b>�� �̻�&nbsp;&nbsp;��&nbsp;&nbsp;</b><input type=text name=up_gradedeli_limitdown[<?=$k?>][] value="<?=$gradedeli_limitdown[$k][$i]?>" size=18 maxlength=10 class="input" id="gradedeli<?=$k?>_limitdown<?=$i?>" <?=($j==$maxi?"":" onKeyDown=\"SetValueCopy(this.value,'gradedeli".$k."_limitup".$j."');\" onKeyUp=\"SetValueCopy(this.value,'gradedeli".$k."_limitup".$j."');\"")?> style="text-align:right;"><b>�� �̸�</b></td>
										<td align="center" style="padding:5px;padding-bottom:0px;"><input type=text name=up_gradedeli_limitfee[<?=$k?>][] value="<?=$gradedeli_limitfee[$k][$i]?>" size=12 maxlength=6 class="input" style="text-align:right;" id="deli<?=$k?>_limitfee<?=$i?>"><b>��</b></td>
									</tr>
									<?
										$j++;
									}
									?>

									<tr>
										<td style="border-right:2px #FF7100 solid;" height="7"><img width="0" height="0"></td>
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
							<TD colspan="3" bgcolor="#DDDDDD" height="1"></TD>
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
				</table>

				<table border=0 cellpadding=0 cellspacing=0 width=100%>
				<tr><td height=40></td></tr>
				<tr>
					<td><img src="images/delivery_info_stitle03.gif"></td>
				</tr>
				<tr><td height=5></td></tr>
				<tr><td height=1 bgcolor=#cccccc></td></tr>
				</table>

				<table border=0 cellpadding=4 cellspacing=1 bgcolor=#dddddd width=100%>
				<col width=50></col>
				<col width=></col>
				<col width=130></col>
				<tr>
					<td bgcolor="#F0F0F0" align=center nowrap><b>��ȣ</td>
					<td bgcolor="#F0F0F0" align=center><b>������ (����,�갣 ��)</td>
					<td bgcolor="#F0F0F0" align=center nowrap><b>�߰���۷� (+,-)</td>
				</tr>
<?
				$array_deli=explode("|",$deli_area);
				for($i=0;$i<$maxnum;$i++){
?>
				<tr bgcolor=#FFFFFF>
					<td align=center><?=($i+1)?></td>
					<td align=center><input class=input type=text name=up_deliarea size=78 style="width:95%" value="<?=$array_deli[$i*2]?>"></td>
					<td align=center><input class=input type=text name=up_deliareaprice size=15 maxlength=10 value="<?=$array_deli[$i*2+1]?>"></td>
				</tr>
<?
				}
?>
				</table>
				<table border=0 cellpadding=0 cellspacing=0 width=100%>
				<tr>
					<td style="padding:5,0,0,5;" class=notice_blue>
					- ���Ͻô� Ư�������� �޸�(",")�� �����Ͽ� �Է��ϼ���. (�� : ����,������)
					<br>
					- ��ϵ� Ư���������� �ֹ��� �� ��� �⺻ ��۷� �ܿ� �߰� ��۷ᰡ �ΰ��˴ϴ�.
					</td>
				</tr>
				</table>

				<table border=0 cellpadding=0 cellspacing=0 width=100%>
				<tr><td height=40></td></tr>
				<tr>
					<td><b>>>��ۼ��ܺ� �̿����ȳ�</b></td>
				</tr>
				<tr><td height=5></td></tr>
				<tr><td height=1 bgcolor=#cccccc></td></tr>
				</table>

				<table border=0 cellpadding=4 cellspacing=1 bgcolor=#dddddd width=100%>
				<col width=100></col>
				<col width=></col>
				<tr>
					<td bgcolor="#F0F0F0" align=center nowrap><b>��ۼ���</td>
					<td bgcolor="#F0F0F0" align=center><b>����</td>
				</tr>
				
				<tr bgcolor=#FFFFFF>
					<td align=center>������</td>
					<td align=center><textarea name=up_deli_info2 style="width:95%;height:100px"><?=$deli_info2?></textarea></td>
				</tr>
				<tr bgcolor=#FFFFFF>
					<td align=center>�湮����</td>
					<td align=center><textarea name=up_deli_info3 style="width:95%;height:100px"><?=$deli_info3?></textarea></td>
				</tr>
				<tr bgcolor=#FFFFFF>
					<td align=center>���</td>
					<td align=center><textarea name=up_deli_info4 style="width:95%;height:100px"><?=$deli_info4?></textarea></td>
				</tr>
				<tr bgcolor=#FFFFFF>
					<td align=center>��ҿ���</td>
					<td align=center><textarea name=up_deli_info5 style="width:95%;height:100px"><?=$deli_info5?></textarea></td>
				</tr>

				</table>

				<table border=0 cellpadding=0 cellspacing=0 width=100%>
				<tr><td height=20></td></tr>
				<tr>
					<td align=center>
					<A HREF="javascript:CheckForm()"><img src="images/btn_save01.gif" border=0></A>
					</td>
				</tr>

				</form>

				</table>

				<iframe name="processFrame" src="about:blank" width="0" height="0" scrolling=no frameborder=no></iframe>

				</td>
			</tr>
			<!-- ó���� ���� ��ġ �� -->

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