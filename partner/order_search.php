<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once("access.php");

$joindate = $_PartnerInfo->getJoindate();
if(strlen($joindate)==0) $joindate=date("YmdHis");
$CurrentTime = time();
$period[0] = date("Y-m-d",$CurrentTime);
$period[1] = date("Y-m-d",$CurrentTime-(60*60*24*7));
$period[2] = date("Y-m",$CurrentTime)."-01";
$period[3] = date("Y",$CurrentTime)."-01-01";
$period[4] = substr($joindate,0,4)."-".substr($joindate,4,2)."-".substr($joindate,6,2);

$up_paymethod=$_POST["up_paymethod"];
$up_deli_gbn=$_POST["up_deli_gbn"];
$search_start=$_POST["search_start"];
$search_end=$_POST["search_end"];
$vperiod=$_POST["vperiod"];

$search_start=$search_start?$search_start:$period[0];
$search_end=$search_end?$search_end:date("Y-m-d",$CurrentTime);
$search_s=$search_start?str_replace("-","",$search_start."000000"):str_replace("-","",$period[0]."000000");
$search_e=$search_end?str_replace("-","",$search_end."235959"):date("Ymd",$CurrentTime)."235959";

${"check_vperiod".$vperiod} = "checked";

$tempstart = explode("-",$search_start);
$tempend = explode("-",$search_end);
$termday = (mktime(0,0,0,$tempend[1],$tempend[2],$tempend[0])-mktime(0,0,0,$tempstart[1],$tempstart[2],$tempstart[0]))/86400;
if ($termday>31) {
	echo "<script>alert('�˻��Ⱓ�� 1������ �ʰ��� �� �����ϴ�.');location='".$_SERVER[PHP_SELF]."';</script>";
	exit;
}

?>

<html>
<head>
<title>���޻� �ֹ���Ȳ</title>
<META http-equiv="CONTENT-TYPE" content="text/html; charset=EUC-KR">

<link rel="stylesheet" href="style.css">
<script type="text/javascript" src="calendar.js.php"></script>
<SCRIPT LANGUAGE="JavaScript">
<!--
function OrderPrint() {
	if (confirm("�ش� �ֹ������� ����Ͻðڽ��ϱ�?")) print();
}
function OnChangePeriod(val) {
	var pForm = document.form1;
	var period = new Array(7);
	period[0] = "<?=$period[0]?>";
	period[1] = "<?=$period[1]?>";
	period[2] = "<?=$period[2]?>";
	period[3] = "<?=$period[3]?>";
	period[4] = "<?=$period[4]?>";

	pForm.search_start.value = period[val];
	pForm.search_end.value = period[0];
}
function CheckForm() {
	document.form1.submit();
}
//-->
</SCRIPT>
</head>
<!--body oncontextmenu="return false" ondragstart="return false" onselectstart="return false" oncontextmenu="return false"-->
<body>
<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed;">
<tr>
	<td width=100%>
	<table border=0 cellpadding=0 cellspacing=0 width=100%>
	<tr>
		<td width="100%" height="25"> <font color="#336699" size="3"><B>�ƢƢƢ� <FONT COLOR="red">[<?=$_PartnerInfo->getPartnerid()?>]</FONT> ���޻� �ֹ���Ȳ �ƢƢƢ�</B></font> <img width=10 height=0> <font color="#cc0000">�� �湮�ڼ� :  <B><?=number_format($hit_cnt)?></B></font></td>
		<td width=150 align=right style="padding-right:0" nowrap><input type=button value="�α׾ƿ�" onclick="location.href='<?=$_SERVER[PHP_SELF]?>?type=logout'"></td>
	</tr>
	<tr><td height=1 bgcolor=#FF4800 colspan=2></td></tr>
	<tr><td height=1 bgcolor=#ffffff colspan=2></td></tr>
	<tr>
		<td colspan=2 style="padding-top:5;"><nobr>
		<LI> �˻������� �����Ͻ� �� �˻��� �Ͻø� �ش� �Ⱓ���� �ͻ縦 ���Ͽ� �湮�� ����ڵ��� �ֹ���Ȳ�� Ȯ���� �� �ֽ��ϴ�. (1���� �̳��� �˻� ����)
		</td>
	</tr>
	</table>
	</td>
</tr>
<tr><td height=20></td></tr>
<tr>
	<td align=center>
	<table border=0 cellpadding=0 cellspacing=0 width=93%>
	<form name=form1 action="<?=$_SERVER[PHP_SELF]?>" method=post>
	<tr>
		<td class="lineleft" width="150" align=right bgcolor="#F0F0F0" style="padding-right:5" nowrap>������� ����</td>
		<td class="line" width=100% bgcolor="#FFFFFF" style="padding-left:5;padding-top:3;padding-bottom:3">
		<select name=up_paymethod>
<?
		$arpm=array("\"\":��ü����","B:������","V:������ü","O:�������","Q:�������(�Ÿź�ȣ)","C:�ſ�ī��",/*"P:�ſ�ī��(�Ÿź�ȣ)",*/"M:�ڵ���");
		for($i=0;$i<count($arpm);$i++) {
			$tmp=split(":",$arpm[$i]);
			echo "<option value=\"".$tmp[0]."\" ";
			if($tmp[0]==$up_paymethod) echo "selected";
			echo ">".$tmp[1]."</option>\n";
		}
?>
		</select>
		</td>
	</tr>
	<tr>
		<td class="lineleft" width="150" align=right bgcolor="#F0F0F0" style="padding-right:5" nowrap>ó������ ����</td>
		<td class="line" width=100% bgcolor="#FFFFFF" style="padding-left:5;padding-top:3;padding-bottom:3">
		<select name=up_deli_gbn>
<?
		$ardg=array("\"\":��ü����","S:�߼��غ�","Y:���","N:��ó��","C:�ֹ����","R:�ݼ�","D:��ҿ�û");
		for($i=0;$i<count($ardg);$i++) {
			$tmp=split(":",$ardg[$i]);
			echo "<option value=\"".$tmp[0]."\" ";
			if($tmp[0]==$up_deli_gbn) echo "selected";
			echo ">".$tmp[1]."</option>\n";
		}
?>
		</select>
		</td>
	</tr>
	<tr>
		<td class="linebottomleft" width="150" align=right bgcolor="#F0F0F0" style="padding-right:5" nowrap>�˻��Ⱓ ����</td>
		<td class="linebottom" width=100% bgcolor="#FFFFFF" style="padding-left:5">
		<input type=text name=search_start value="<?=$search_start?>" size=10 onfocus="this.blur();" OnClick="Calendar(this)" style="background:#efefef"> ~ <input type=text name=search_end value="<?=$search_end?>" size=10 onfocus="this.blur();" OnClick="Calendar(this)" style="background:#efefef">

		<input type=radio id=idx_vperiod0 name=vperiod value="0" style='border:0px;' onclick="OnChangePeriod(this.value)" <?=$check_vperiod0?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_vperiod0>����</label>
		<input type=radio id=idx_vperiod1 name=vperiod value="1" style='border:0px;' onclick="OnChangePeriod(this.value)" <?=$check_vperiod1?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_vperiod1>1����</label>
		<input type=radio id=idx_vperiod2 name=vperiod value="2" style='border:0px;' onclick="OnChangePeriod(this.value)" <?=$check_vperiod2?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_vperiod2>�̴�</label>
		<img width=20 height=0>
		<input type=button value=" ��ȸ�ϱ� " onclick="CheckForm();">
		</td>
	</tr>
	</form>
	</table>
	</td>
</tr>
<tr><td height=10></td></tr>
<tr>
	<td align=center>
<?
	$arpm=array("B"=>"������","V"=>"������ü","O"=>"�������","Q"=>"�������(�Ÿź�ȣ)","C"=>"�ſ�ī��","P"=>"�ſ�ī��(�Ÿź�ȣ)","M"=>"�ڵ���");

	$qry = "WHERE partner_id='".$_PartnerInfo->getPartnerid()."' ";
	if(substr($search_s,0,8)==substr($search_e,0,8)) {
		$qry.= "AND ordercode LIKE '".substr($search_s,0,8)."%' ";
	} else {
		$qry.= "AND ordercode>='".$search_s."' AND ordercode <='".$search_e."' ";
	}
	if(strlen($up_paymethod)>0)	$qry.= "AND paymethod LIKE '".$up_paymethod."%' ";
	if(strlen($up_deli_gbn)>0)		$qry.= "AND deli_gbn='".$up_deli_gbn."' ";
	$qry.= "AND ((paymethod='B' AND pay_flag='N') OR pay_flag='0000') ";

	$sql = "SELECT * FROM tblorderinfo ".$qry." ORDER BY ordercode";
	$result = mysql_query($sql,get_db_conn());
	$count=mysql_num_rows($result);
?>
	<table border=0 cellpadding=0 cellspacing=0 width=93%>
	<tr><td colspan=7>�� �ֹ��Ǽ� : <?=$count?></td></tr>
	<tr bgcolor=#F0F0F0>
		<td class=lineleft width=50 align=center nowrap>No</td>
		<td class=line width=120 align=center nowrap>�ֹ�����</td>
		<td class=line width=100% align=center>��ǰ��</td>
		<td class=line width=100 align=center nowrap>�ֹ���</td>
		<td class=line width=100 align=center nowrap>�������</td>
		<td class=line width=75 align=center nowrap>����</td>
		<td class=line width=115 align=center nowrap>ó������</td>
	</tr>
<?
	$i=0;
	$sumprice=0;
	while($row=mysql_fetch_object($result)) {
		$i++;
		$sumprice+=$row->price;
		$date = substr($row->ordercode,0,4)."/".substr($row->ordercode,4,2)."/".substr($row->ordercode,6,2)." (".substr($row->ordercode,8,2).":".substr($row->ordercode,10,2).")";
		echo "<tr>\n";
		echo "	<td class=lineleft align=center>".$i."</td>\n";
		echo "	<td class=line align=center>".$date."</td>\n";
		echo "	<td class=line style=\"padding:2;line-height:12pt\">\n";
		$sql = "SELECT * FROM tblorderproduct WHERE ordercode='".$row->ordercode."' ";
		$result2 = mysql_query($sql,get_db_conn());
		$cnt = 0;
		while ($row2=mysql_fetch_object($result2)) { 
			if ($cnt++!=0) {
				echo "<br>";
			}
			echo "$row2->productname";
			if (strlen($row2->opt1_name)>0) echo "($row2->opt1_name)";
			if (strlen($row2->opt2_name)>0) echo "($row2->opt2_name)";
		}
		mysql_free_result($result2);
		echo "	</td>\n";
		echo "	<td class=line align=center>".$row->sender_name."</td>\n";
		echo "	<td class=line align=center>".$arpm[substr($row->paymethod,0,1)]." ";
		if(preg_match("/^(B){1}/", $row->paymethod)) {	//������
			if (strlen($row->bank_date)==9 && substr($row->bank_date,8,1)=="X") echo "<font color=005000> [ȯ��]</font>";
			else if (strlen($row->bank_date)>0) echo " <font color=004000>[�ԱݿϷ�]</font>";
		} else if(preg_match("/^(V){1}/", $row->paymethod)) {	//������ü
			if (strcmp($row->pay_flag,"0000")!=0) echo " <font color=#757575>[��������]</font>";
			else if ($row->pay_flag=="0000" && $row->pay_admin_proc=="C") echo "<font color=005000> [ȯ��]</font>";
			else if ($row->pay_flag=="0000") echo "<font color=0000a0> [�����Ϸ�]</font>";
		} else if(preg_match("/^(M){1}/", $row->paymethod)) {	//�ڵ���
			if (strcmp($row->pay_flag,"0000")!=0) echo " <font color=#757575>[��������]</font>";
			else if ($row->pay_flag=="0000" && $row->pay_admin_proc=="C") echo "<font color=005000> [��ҿϷ�]</font>";
			else if ($row->pay_flag=="0000") echo "<font color=0000a0> [�����Ϸ�]</font>";
		} else if(preg_match("/^(O|Q){1}/", $row->paymethod)) {	//�������
			if (strcmp($row->pay_flag,"0000")!=0) echo " <font color=#757575>[�ֹ�����]</font>";
			else if ($row->pay_flag=="0000" && $row->pay_admin_proc=="C") echo "<font color=005000> [ȯ��]</font>";
			else if ($row->pay_flag=="0000" && strlen($row->bank_date)==0) echo "<font color=red> [���Ա�]</font>";
			else if ($row->pay_flag=="0000" && strlen($row->bank_date)>0) echo "<font color=0000a0> [�ԱݿϷ�]</font>";
		} else {
			if (strcmp($row->pay_flag,"0000")!=0) echo " <font color=#757575>[ī�����]</font>";
			else if ($row->pay_flag=="0000" && $row->pay_admin_proc=="N") echo "<font color=red> [ī�����]</font>";
			else if ($row->pay_flag=="0000" && $row->pay_admin_proc=="Y") echo "<font color=0000a0> [�����Ϸ�]</font>";
			else if ($row->pay_flag=="0000" && $row->pay_admin_proc=="C") echo "<font color=005000> [��ҿϷ�]</font>";
		}
		echo "	</td>\n";
		echo "	<td class=line align=right>".number_format($row->price)."&nbsp;</td>\n";
		echo "	<td class=line align=center>&nbsp;";
		switch($row->deli_gbn) {
			case 'S': echo "�߼��غ�";  break;
			case 'X': echo "��ۿ�û";  break;
			case 'Y': echo "���";  break;
			case 'D': echo "<font color=blue>��ҿ�û</font>";  break;
			case 'N': echo "��ó��";  break;
			case 'E': echo "<font color=red>ȯ�Ҵ��</font>";  break;
			case 'C': echo "<font color=red>�ֹ����</font>";  break;
			case 'R': echo "�ݼ�";  break;
			case 'H': echo "���(<font color=red>���꺸��</font>)";  break;
		}
		if($row->deli_gbn=="D" && strlen($row->deli_date)==14) echo " (���)";
		echo "	&nbsp;</td>\n";
		echo "</tr>\n";
	}
	mysql_free_result($result);
	if ($i==0) {
		echo "<tr><td class=lineleft colspan=7 align=center>�˻��� �ֹ������� �����ϴ�.</td></tr>";
	}
	echo "<tr><td colspan=7 height=1 bgcolor=#dadada></td></tr>\n";
	echo "<tr><td colspan=7 align=right>�հ� : <B>".number_format($sumprice)."</B>��</td></tr>\n";
?>
	<tr>
		<td colspan=7 style="padding-top:5">
		<LI> �ſ�ī�� ���г����� ��ȸ���� �ʽ��ϴ�.
		<LI> ������ �ſ����� ��ȣ�� ���Ͽ� ������ ��ȸ�� �������� �ʽ��ϴ�.
		</td>
	</tr>
	</table>
	</td>
</tr>
<tr><td height=20></td></tr>
</table>
</body>
</html>