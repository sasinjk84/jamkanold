<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/venderlib.php");

$isaccesspass=true;
INCLUDE ("access.php");

function getDeligbn($ordercode,$deli_gbn) {
	//N:��ó��, X:��ۿ�û, S:�߼��غ�, Y:��ۿϷ�, C:�ֹ����, R:�ݼ�, D:��ҿ�û, E:ȯ�Ҵ��[��������� ��츸]
	$sql = "SELECT deli_gbn FROM tblorderproduct WHERE ordercode='".$ordercode."' AND NOT (productcode LIKE 'COU%' OR productcode LIKE '999999%') ";
	$sql.= "GROUP BY deli_gbn ";
	$result=mysql_query($sql,get_db_conn());
	$arrdeli=array();
	while($row=mysql_fetch_object($result)) {
		$arrdeli[$row->deli_gbn]=true;
	}
	mysql_free_result($result);

	$res="";
	if($deli_gbn=="N" && count($arrdeli)>0) {
		$res="N";
	} else if($deli_gbn=="S" && count($arrdeli)>0) {
		if($arrdeli["N"]==true) $res="N";
		else $res="S";
	} else if($deli_gbn=="Y"){
		$res = "Y";
	}

	if(preg_match("/^(N|S|Y)$/",$res)) {
		//��ó��, �߼��غ� ������ ������ü������ ���� ����� �ٷ� ����
		$sql = "UPDATE tblorderinfo SET deli_gbn='".$res."' WHERE ordercode='".$ordercode."' ";
		mysql_query($sql,get_db_conn());
	}
	return $res;
}

$ordercode=$_POST["ordercode"];

if($ordercode==NULL) {
	echo "<script>alert('�߸��� �����Դϴ�.');window.close();</script>";
	exit;
}

$sql = "SELECT a.*, SUM(IF((b.productcode!='99999999990X' AND NOT (b.productcode LIKE 'COU%')), b.price*b.quantity,NULL)) as sumprice, ";
$sql.= "SUM(b.reserve*b.quantity) as sumreserve, ";
$sql.= "SUM(IF(b.productcode='99999999990X', b.price,NULL)) as sumdeliprice, ";
$sql.= "SUM(IF(b.productcode LIKE 'COU%', b.price,NULL)) as sumcouprice ";
$sql.= "FROM tblorderinfo a, tblorderproduct b WHERE a.ordercode='".$ordercode."' AND a.ordercode=b.ordercode ";
$sql.= "AND b.vender='".$_VenderInfo->getVidx()."' ";
$sql.= "GROUP BY a.ordercode ";
$result=mysql_query($sql,get_db_conn());
$_ord=mysql_fetch_object($result);
mysql_free_result($result);
if(!$_ord) {
	echo "<script>alert(\"�ش� �ֹ������� �������� �ʽ��ϴ�.\");window.close();</script>";
	exit;
}

$mode=$_POST["mode"];
$prcodes=$_POST["prcodes"];
$deli_gbn=$_POST["deli_gbn"];
if($mode=="deligbnup" && strlen($prcodes)>0 && preg_match("/^(N|S|Y)$/",$deli_gbn) && preg_match("/^(N|X|S)$/",$_ord->deli_gbn)) {	//ó������ ����
	$prcodes=substr($prcodes,0,-1);
	$prlist=ereg_replace(',','\',\'',$prcodes);
	$sql = "UPDATE tblorderproduct SET deli_gbn='".$deli_gbn."', ";
	if($deli_gbn=="Y") $sql.= "deli_date='".date("YmdHis")."' ";
	else $sql.= "deli_date=NULL ";
	$sql.= "WHERE vender='".$_VenderInfo->getVidx()."' ";
	$sql.= "AND ordercode='".$ordercode."' AND productcode IN ('".$prlist."') ";
	$sql.= "AND NOT (productcode LIKE 'COU%' OR productcode LIKE '999999%') ";
	if(mysql_query($sql,get_db_conn())) {
		if($_ord->deli_gbn!=$deli_gbn) {
			$rescode=getDeligbn($ordercode,$deli_gbn);
			if(strlen($rescode)>0) {
				$_ord->deli_gbn=$rescode;
			}
		}
		$onload="<script>alert('��û�Ͻ� �۾��� �Ϸ�Ǿ����ϴ�.');</script>";
	} else {
		$onload="<script>alert('��û�Ͻ� �۾��� ������ �߻��Ͽ����ϴ�.');</script>";
	}
} else if($mode=="deliinfoup" && strlen($prcodes)>0) {	//������� ����
	$deliinfo=substr($prcodes,0,-1);
	$ardeli=explode("|",$deliinfo);
	for($i=0;$i<count($ardeli);$i++) {
		$prcode=$deli_com=$deli_num="";
		$prinfo=explode(",",$ardeli[$i]);
		for($j=0;$j<count($prinfo);$j++) {
			if (substr($prinfo[$j],0,7)=="PRCODE=") $prcode=substr($prinfo[$j],7);
			else if (substr($prinfo[$j],0,9)=="DELI_COM=") $deli_com=substr($prinfo[$j],9);
			else if (substr($prinfo[$j],0,9)=="DELI_NUM=") $deli_num=substr($prinfo[$j],9);
			else if (substr($prinfo[$j],0,4)=="UID=") $uid=substr($prinfo[$j],4);

			
		}

		if(strlen($prcode)==18) {
			$sql = "UPDATE tblorderproduct SET deli_com='".$deli_com."', deli_num='".$deli_num."' ";
			$sql.= "WHERE vender='".$_VenderInfo->getVidx()."' ";
			$sql.= "AND ordercode='".$ordercode."' AND uid='".$uid."' ";
			$sql.= "AND NOT (productcode LIKE 'COU%' OR productcode LIKE '999999%') ";
			mysql_query($sql,get_db_conn());
		}
		
	}
	$onload="<script>alert('��û�Ͻ� �۾��� �Ϸ�Ǿ����ϴ�.');</script>";
} else if($mode=="returninfoup" && strlen($prcodes)>0 && $_ord->deli_gbn=="Y") {	//�ݼ�ó��
	$prcodes=substr($prcodes,0,-1);
	$prlist=ereg_replace(',','\',\'',$prcodes);
	$sql = "UPDATE tblorderproduct SET deli_gbn='R' ";
	$sql.= "WHERE vender='".$_VenderInfo->getVidx()."' ";
	$sql.= "AND ordercode='".$ordercode."' AND productcode IN ('".$prlist."') ";
	$sql.= "AND NOT (productcode LIKE 'COU%' OR productcode LIKE '999999%') ";
	if(mysql_query($sql,get_db_conn())) {
		$onload="<script>alert('��û�Ͻ� �۾��� �Ϸ�Ǿ����ϴ�.');</script>";
	} else {
		$onload="<script>alert('��û�Ͻ� �۾��� ������ �߻��Ͽ����ϴ�.');</script>";
	}
}

$arpm=array("B"=>"������","V"=>"������ü","O"=>"�������","Q"=>"�������(�Ÿź�ȣ)","C"=>"�ſ�ī��",/*"P"=>"�ſ�ī��(�Ÿź�ȣ)",*/"M"=>"�ڵ���");

$pmethod="";
$presult="";
$prescd="N";
if(preg_match("/^(B){1}/", $_ord->paymethod)) {	//������
	$pmethod="������";
	if (strlen($_ord->bank_date)==9 && substr($_ord->bank_date,8,1)=="X") $presult="<font color=005000> ȯ��</font>";
	else if (strlen($_ord->bank_date)>0) {
		$presult="<font color=004000>�ԱݿϷ�</font>";
		$prescd="Y";
	} else {
		$presult="�Աݴ��";
	}
} else if(preg_match("/^(V){1}/", $_ord->paymethod)) {	//������ü
	$pmethod="������ü";
	if (strcmp($_ord->pay_flag,"0000")!=0) $presult="<font color=#757575>��������</font>";
	else if ($_ord->pay_flag=="0000" && $_ord->pay_admin_proc=="C") $presult="<font color=005000>ȯ��</font>";
	else if ($_ord->pay_flag=="0000") {
		$presult="<font color=0000a0>�����Ϸ�</font>";
		$prescd="Y";
	}
} else if(preg_match("/^(M){1}/", $_ord->paymethod)) {	//�ڵ���
	$pmethod="�ڵ���";
	if (strcmp($_ord->pay_flag,"0000")!=0) $presult="<font color=#757575>��������</font>";
	else if ($_ord->pay_flag=="0000" && $_ord->pay_admin_proc=="C") $presult="<font color=005000> ��ҿϷ�</font>";
	else if ($_ord->pay_flag=="0000") {
		$presult="<font color=0000a0>�����Ϸ�</font>";
		$prescd="Y";
	}
} else if(preg_match("/^(O|Q){1}/", $_ord->paymethod)) {	//�������
	$pmethod="�������";
	if (strcmp($_ord->pay_flag,"0000")!=0) $presult="<font color=#757575>�ֹ�����</font>";
	else if ($_ord->pay_flag=="0000" && $_ord->pay_admin_proc=="C") $presult="<font color=005000>ȯ��</font>";
	else if ($_ord->pay_flag=="0000" && strlen($_ord->bank_date)==0) $presult="<font color=red>���Ա�</font>";
	else if ($_ord->pay_flag=="0000" && strlen($_ord->bank_date)>0) {
		$presult="<font color=0000a0>�ԱݿϷ�</font>";
		$prescd="Y";
	}
} else {
	$pmethod="�ſ�ī��";
	if (strcmp($_ord->pay_flag,"0000")!=0) $presult="<font color=#757575>ī�����</font>";
	else if ($_ord->pay_flag=="0000" && $_ord->pay_admin_proc=="N") $presult="<font color=red>ī�����</font>";
	else if ($_ord->pay_flag=="0000" && $_ord->pay_admin_proc=="Y") {
		$presult="<font color=0000a0>�����Ϸ�</font>";
		$prescd="Y";
	}
	else if ($_ord->pay_flag=="0000" && $_ord->pay_admin_proc=="C") $presult="<font color=005000>��ҿϷ�</font>";
}

?>

<html>
<head>
<title>������ ������</title>
<META http-equiv="CONTENT-TYPE" content="text/html; charset=EUC-KR">

<script type="text/javascript" src="lib.js.php"></script>
<link rel="stylesheet" href="style.css">
<script language=Javascript>
window.resizeTo(880,660);

function ProductInfo(code,prcode,popup) {
	document.form_reg.code.value=code;
	document.form_reg.prcode.value=prcode;
	document.form_reg.popup.value=popup;
	if (popup=="YES") {
		document.form_reg.action="product_register.add.php";
		document.form_reg.target="register";
		window.open("about:blank","register","width=820,height=700,scrollbars=yes,status=no");
	} else {
		document.form_reg.action="product_register.php";
		document.form_reg.target="";
	}
	document.form_reg.submit();
}
function ProductMouseOver(cnt) {
	obj = event.srcElement;
	WinObj=eval("document.all.primage"+cnt);
	obj._tid = setTimeout("ProductViewImage(WinObj)",200);
}
function ProductViewImage(WinObj) {
	WinObj.style.visibility = "visible";
}
function ProductMouseOut(Obj) {
	obj = event.srcElement;
	Obj = document.getElementById(Obj);
	Obj.style.visibility = "hidden";
	clearTimeout(obj._tid);
}

function DeliSearch(deli_url){
	window.open(deli_url,"�������","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizeble=yes,copyhistory=no,width=600,height=550");
}

function MemoMouseOver(cnt) {
	obj = event.srcElement;
	WinObj=eval("document.all.memo"+cnt);
	obj._tid = setTimeout("MemoView(WinObj)",200);
}
function MemoView(WinObj) {
	WinObj.style.visibility = "visible";
}
function MemoMouseOut(cnt) {
	obj = event.srcElement;
	WinObj=eval("document.all.memo"+cnt);
	WinObj.style.visibility = "hidden";
	clearTimeout(obj._tid);
}

function CheckAll(){
   chkval=document.form2.allcheck.checked;
   cnt=document.form2.tot.value;
   for(i=1;i<=cnt;i++){
      document.form2.chkprcode[i].checked=chkval;
   }
}

<?if(preg_match("/^(N|X|S)$/",$_ord->deli_gbn) && $_ord->pay_admin_proc!="C" && $prescd=="Y") {?>
function changeDeli(obj) {
	if(document.form2.tot.value==0) {
		alert("��� ��ǰ�� �������� �ʽ��ϴ�.");
		return;
	}
	deli_gbn=obj.value;
	document.form2.prcodes.value="";
	for(i=1;i<document.form2.chkprcode.length;i++) {
		if(document.form2.chkprcode[i].checked==true) {
			document.form2.prcodes.value+=document.form2.chkprcode[i].value+",";
		}
	}
	if(document.form2.prcodes.value.length==0) {
		alert("�����Ͻ� ��ǰ�� �����ϴ�.");
		obj.selectedIndex=0;
		return;
	}
	if(deli_gbn.length>0) {
		delistr="";
		if(deli_gbn=="N") delistr="[��ó��]";
		else if(deli_gbn=="S") delistr="[�߼��غ�]";
		else if(deli_gbn=="Y") delistr="[��ۿϷ�]";
		if(confirm("���õ� ��ǰ�� ó�����¸� "+delistr+" ���·� �����Ͻðڽ��ϱ�?")) {
			document.form2.mode.value="deligbnup";
			document.form2.submit();
		} else {
			document.form2.prcodes.value="";
			obj.selectedIndex=0;
		}
	} else {
		document.form2.prcodes.value="";
		obj.selectedIndex=0;
	}
}
<?}?>

function GoPrdinfo(prcode,target) {
	document.form3.target="";
	document.form3.prcode.value=prcode;
	if(target.length>0) {
		document.form3.target=target;
	}
	document.form3.submit();
}

function changeDeliinfo() {
	if(document.form2.tot.value==0) {
		alert("��� ��ǰ�� �������� �ʽ��ϴ�.");
		return;
	}
	document.form2.prcodes.value="";
	for(i=1;i<document.form2.chkprcode.length;i++) {
		if(document.form2.chkprcode[i].checked==true) {
			document.form2.prcodes.value+="PRCODE="+document.form2.chkprcode[i].value+",DELI_COM="+document.form2.deli_com[i].value+",DELI_NUM="+document.form2.deli_num[i].value+",UID="+document.form2.uid[i].value+"|";
		}
	}
	if(document.form2.prcodes.value.length==0) {
		alert("�����Ͻ� ��ǰ�� �����ϴ�.");
		return;
	}
	if(confirm("���õ� ��ǰ�� ��۾�ü/�����ȣ�� ����(���)�մϴ�.\n\n������ �����Ͻðڽ��ϱ�?")) {
		document.form2.mode.value="deliinfoup";
		document.form2.submit();
	}
}

function changeReturninfo() {
	if(document.form2.tot.value==0) {
		alert("��� ��ǰ�� �������� �ʽ��ϴ�.");
		return;
	}
	document.form2.prcodes.value="";
	for(i=1;i<document.form2.chkprcode.length;i++) {
		if(document.form2.chkprcode[i].checked==true) {
			document.form2.prcodes.value+=document.form2.chkprcode[i].value+",";
		}
	}
	if(document.form2.prcodes.value.length==0) {
		alert("�����Ͻ� ��ǰ�� �����ϴ�.");
		return;
	}
	if(confirm("���õ� ��ǰ�� �ݼ�ó�� ���·� ���� �Ͻðڽ��ϱ�?")) {
		document.form2.mode.value="returninfoup";
		document.form2.submit();
	}
}
</script>
</head>
<body marginwidth=0 marginheight=0 leftmargin=0 topmargin=0>
<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
<tr>
	<td style="padding:10px">
	<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
	<tr>
		<td><img src=images/icon_dot03.gif border=0 align=absmiddle> <B>�ֹ�����</B> <font style="font-size:8pt;color:#2A97A7">(�ش� �ֹ����� ���������Դϴ�.)</font></td>
	</tr>
	<tr><td height=2></td></tr>
	<tr><td height=1 bgcolor=red></td></tr>
	<tr>
		<td>
		<table border=0 cellpadding=0 cellspacing=1 width=100% bgcolor=E7E7E7 style="table-layout:fixed">
		<col width=70></col>
		<col width=></col>
		<col width=80></col>
		<col width=85></col>
		<col width=75></col>
		<col width=70></col>
		<col width=70></col>
		<col width=70></col>
		<col width=75></col>
		<col width=95></col>
		<tr height=32 align=center bgcolor=#FEFCDA>
			<td>�ֹ�����</td>
			<td>�ֹ��ڵ�</td>
			<td>�������</td>
			<td>��������</td>
			<td>�� �Ǹž�</td>
			<td>�� ��ۺ�</td>
			<td>�� ������</td>
			<td>��������</td>
			<td>�� �հ�</td>
			<td>ó������</td>
		</tr>
		<tr height=32 bgcolor=#FFFFFF style="padding:4">
			<td align=center style="font-size:8pt;line-height:9pt"><?=substr($_ord->ordercode,0,4)."/".substr($_ord->ordercode,4,2)."/".substr($_ord->ordercode,6,2)." (".substr($_ord->ordercode,8,2).":".substr($_ord->ordercode,8,2).")"?></td>
			<td align=center style="font-size:8pt"><?=$_ord->ordercode?></td>
			<td align=center><?=$pmethod?></td>
			<td align=center><?=$presult?></td>
			<td align=right style="padding-right:5"><?=number_format($_ord->sumprice)?></td>
			<td align=right style="padding-right:5"><?=($_ord->sumdeliprice>0?"+":"").number_format($_ord->sumdeliprice)?></td>
			<td align=right style="padding-right:5"><?=($_ord->sumreserve>0?"-":"").number_format($_ord->sumreserve)?></td>
			<td align=right style="padding-right:5"><?=number_format($_ord->sumcouprice)?></td>
			<td align=right style="padding-right:5"><B><?=number_format($_ord->sumprice+$_ord->sumdeliprice-($_ord->sumreserve-$_ord->sumcouprice))?></B></td>
			<td align=center>
<?
			switch($_ord->deli_gbn) {
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
			if($row2->deli_gbn=="D" && strlen($row2->deli_date)==14) echo " (���)";
?>
			</td>
		</tr>
		</table>
		</td>
	</tr>
	<tr><td height=20></td></tr>
	<tr>
		<td><img src=images/icon_dot03.gif border=0 align=absmiddle> <B>�ֹ���ǰ ���� ó��</B> <font style="font-size:8pt;color:#2A97A7">(���ó�� ���� �ֹ� ��ǰ�� �ɼǻ��� �� ������ �� Ȯ���ϼż� ����Ͻñ� �ٶ��ϴ�.)</font></td>
	</tr>
	<tr><td height=2></td></tr>
	<tr><td height=1 bgcolor=red></td></tr>

	<form name=form2 action="<?=$_SERVER[PHP_SELF]?>" method=post>
	<input type=hidden name=mode>
	<input type=hidden name=ordercode value="<?=$ordercode?>">
	<input type=hidden name=prcodes>

	<tr>
		<td>
		<table border=0 cellpadding=0 cellspacing=1 width=100% bgcolor=E7E7E7 style="table-layout:fixed">
		<col width=20></col>
		<col width=></col>
		<col width=75></col>
		<col width=75></col>
		<col width=25></col>
		<col width=40></col>
		<col width=52></col>
		<col width=65></col>
		<col width=65></col>
		<col width=25></col>
		<col width=86></col>
		<col width=98></col>
		<tr height=28 align=center bgcolor=F5F5F5>
			<input type=hidden name=chkprcode>
			<input type=hidden name=deli_com>
			<input type=hidden name=deli_num>
			<input type=hidden name=uid>
			<td><input type=checkbox name=allcheck onclick="CheckAll()"></td>
			<td>��ǰ��/Ư��ǥ��</td>
			<td>���û���1</td>
			<td>���û���2</td>
			<td>����</td>
			<td>������</td>
			<td>����</td>
			<td>ó������</td>
			<td>ȯ�ҿ���</td>
			<td>�޸�</td>
			<td>��۾�ü</td>
			<td>�����ȣ</td>
		</tr>
<?
		$sql = "SELECT * FROM tblorderproduct WHERE vender='".$_VenderInfo->getVidx()."' AND ordercode='".$_ord->ordercode."' ";
		$result=mysql_query($sql,get_db_conn());
		$cnt=0;
		$etcdata=array();
		while($row=mysql_fetch_object($result)) {
			if (substr($row->productcode,0,3)=="999" || substr($row->productcode,0,3)=="COU") {
				$etcdata[]=$row;
				continue;
			}

			echo "<input type=hidden name=uid value=\"".$row->uid."\">";
			echo "<tr bgcolor=#FFFFFF>\n";
			echo "	<td align=center><input type=checkbox name=chkprcode value=\"".$row->productcode."\"></td>\n";
			echo "	<td style=\"padding:3;font-size:8pt;line-height:10pt\">";

			$optvalue="";
			if(ereg("^(\[OPTG)([0-9]{3})(\])$",$row->opt1_name)) {
				$optioncode=$row->opt1_name;
				$row->opt1_name="";
				$sql = "SELECT opt_name FROM tblorderoption WHERE ordercode='".$_ord->ordercode."' AND productcode='".$row->productcode."' ";
				$sql.= "AND opt_idx='".$optioncode."' ";
				$result2=mysql_query($sql,get_db_conn());
				if($row2=mysql_fetch_object($result2)) {
					$optvalue=$row2->opt_name;
				}
				mysql_free_result($result2);
			}

			if(file_exists($Dir.DataDir."shopimages/product/".$row->productcode."3.gif")) $file=$row->productcode."3.gif";
			else if(file_exists($Dir.DataDir."shopimages/product/".$row->productcode."3.jpg")) $file=$row->productcode."3.jpg";
			else $file="NO";

			if($file!="NO") {
				echo "	".(strlen($row->selfcode)?"�����ڵ� : ".$row->selfcode."<br>":"")."<span onMouseOver='ProductMouseOver($cnt)' onMouseOut=\"ProductMouseOut('primage".$cnt."');\">".$row->productname."<a href=\"JavaScript:GoPrdinfo('".$row->productcode."','_blank')\"> <img src=images/newwindow.gif align=absmiddle border=0></a>";
				if(strlen($optvalue)>0) echo "<br><font color=red>�ɼǻ��� : ".$optvalue."</font>";
				if(strlen($row->addcode)>0) echo "<br><font color=red>Ư��ǥ�� : ".$row->addcode."</font>";
				echo "	</span>\n";
				echo "	<div id=primage".$cnt." style=\"position:absolute; z-index:100; visibility:hidden;\">\n";
				echo "	<table border=0 cellspacing=1 cellpadding=0 bgcolor=#000000 width=170>\n";
				echo "	<tr bgcolor=#FFFFFF>\n";
				echo "		<td align=center width=100% height=150><img name=bigimgs src=\"".$Dir.DataDir."shopimages/product/".$file."\"></td>\n";
				echo "	</tr>\n";
				echo "	<tr bgcolor=#FFFFFF>\n";
				echo "		<td height=54 bgcolor=#f5f5f5><table border=0><tr><td style=\"line-height:12pt\">���� �ֹ���,����/�̵� ��ǰ�� �̹����� ��ġ���� ������ ������ <font color=red>�����Ͽ� ���</font>�ٶ��ϴ�.</td></tr></table></td>\n";
				echo "	</tr>\n";
				echo "	</table>\n";
				echo "	</div>\n";
			} else {
				echo "".(strlen($row->selfcode)?"�����ڵ� : ".$row->selfcode."<br>":"").$row->productname."";
				if(strlen($optvalue)>0) echo "<br><font color=red>�ɼǻ��� : ".$optvalue."</font>";
				if(strlen($row->addcode)>0) echo "<br><font color=red>Ư��ǥ�� : ".$row->addcode."</font>";
			}
			echo "	</td>\n";
			echo "	<td align=center style=\"padding:3;font-size:8pt;line-height:10pt\">".$row->opt1_name."</td>\n";
			echo "	<td align=center style=\"padding:3;font-size:8pt;line-height:10pt\">".$row->opt2_name."</td>\n";
			echo "	<td align=center style=\"font-size:8pt\">".$row->quantity."</td>\n";
			echo "	<td align=right style=\"padding:3;font-size:8pt\">".number_format($row->reserve*$row->quantity)."</td>\n";
			echo "	<td align=right style=\"padding:3;font-size:8pt\">".number_format($row->price*$row->quantity)."</td>\n";
			echo "	<td align=center style=\"font-size:8pt\">";
			switch($row->deli_gbn) {
				case 'S': echo "�߼��غ�";  break;
				case 'X': echo "��ۿ�û";  break;
				case 'Y': echo "���";  break;
				case 'D': echo "<font color=blue>��ҿ�û</font>";  break;
				case 'N': echo "��ó��";  break;
				case 'E': echo "<font color=red>ȯ�Ҵ��</font>";  break;
				case 'C': echo "<font color=red>�ֹ����</font>";  break;
				case 'R': echo "<font color=red>�ݼ�</font>";  break;
				case 'H': echo "���(<font color=red>���꺸��</font>)";  break;
			}
			if($row->deli_gbn=="D" && strlen($row->deli_date)==14) echo " (���)";
			echo "	</td>\n";
			if($row->status) {
				echo "	<td align=center style=\"font-size:8pt;color:red\">";
				switch($row->status) {
					case 'EA': echo "��ȯ��û";  break;
					case 'EB': echo "��ȯ����";  break;
					case 'EC': echo "��ȯ�Ϸ�";  break;
					case 'RA':
						echo "ȯ�ҽ�û";

						$sql = "select requestor from part_cancel_want where uid='".$row->uid."'";
						$result2=mysql_query($sql,get_db_conn());
						if($row2=mysql_fetch_object($result2)) {

							if ($row2->requestor ==1) {
								 echo "<br/><span style=\"color:blue\">(��)</span>";
							}
						}
						mysql_free_result($result2);


						break;
					case 'RB': echo "ȯ������";  break;
					case 'RC': echo "ȯ�ҿϷ�";  break;
				}
				echo "</td>";
				$classred1 = "<font style='color:red'>";
				$classred2 = "</font>";
		}else{
			echo "	<td align=center style=\"font-size:8pt\">&nbsp;</td>";
		}
			if(strlen($row->order_prmsg)>0) {
				echo "	<td align=center style=\"font-size:8pt;color:red\"><a style=\"cursor:hand;\" onMouseOver='MemoMouseOver($cnt)' onMouseOut=\"MemoMouseOut($cnt);\">�޸�</a>";
				echo "	<div id=memo".$cnt." style=\"left:160px;top:110px;position:absolute; z-index:100; visibility:hidden;\">\n";
				echo "	<table width=400 border=0 cellspacing=0 cellpadding=0 bgcolor=#A47917>\n";
				echo "	<tr>\n";
				echo "		<td style=\"padding:5;line-height:12pt\"><font color=#FFFFFF>".nl2br(strip_tags($row->order_prmsg))."</td>\n";
				echo "	</tr>";
				echo "	</table>\n";
				echo "	</div>\n";
				echo "	</td>\n";
			} else {
				echo "	<td align=center style=\"font-size:8pt\">-</td>\n";
			}
			echo "	<td align=center>";
			echo "	<select name=deli_com style=\"width:80px\">\n";
			echo "	<option value=\"\">����</option>\n";
			$sql="SELECT * FROM tbldelicompany ORDER BY company_name ";
			$result2=mysql_query($sql,get_db_conn());
			$deli_url="";
			$trans_num="";
			$company_name="";
			while($row2=mysql_fetch_object($result2)) {
				echo "		<option value=\"".$row2->code."\"";
				if($row->deli_com>0 && $row->deli_com==$row2->code) {
					echo " selected";
					$deli_url=$row2->deli_url;
					$trans_num=$row2->trans_num;
					$company_name=$row2->company_name;
				}
				echo ">".$row2->company_name."</option>\n";
			}
			mysql_free_result($result2);
			echo "	</select>\n";
			echo "	</td>\n";
			echo "	<td style=\"padding:3\">";
			echo "	<input type=text name=deli_num value=\"".$row->deli_num."\" size=8 maxlength=20 style=\"font-size:8pt\" onkeyup=\"strnumkeyup(this)\"><img width=2 height=0>";
			if(strlen($row->deli_num)>0 && strlen($deli_url)>0) {
				if(strlen($trans_num)>0) {
					$arrtransnum=explode(",",$trans_num);
					$pattern=array("(\[1\])","(\[2\])","(\[3\])","(\[4\])");
					$replace=array(substr($row->deli_num,0,$arrtransnum[0]),substr($row->deli_num,$arrtransnum[0],$arrtransnum[1]),substr($row->deli_num,$arrtransnum[0]+$arrtransnum[1],$arrtransnum[2]),substr($row->deli_num,$arrtransnum[0]+$arrtransnum[1]+$arrtransnum[2],$arrtransnum[3]));
					$deli_url=preg_replace($pattern,$replace,$deli_url);
				} else {
					$deli_url.=$row->deli_num;
				}
				echo "<input type=button value='����' style=\"cursor:hand;color:#FFFFFF;border-color:#666666;background-color:#666666;font-size:8pt;font-family:Tahoma;height:18px;width:30\" onclick=\"DeliSearch('".$deli_url."')\">";
			} else {
				echo "<input type=button value='����' style=\"cursor:hand;color:#FFFFFF;border-color:#666666;background-color:#666666;font-size:8pt;font-family:Tahoma;height:18px;width:30\">";
			}

			echo "	</td>\n";
			echo "</tr>\n";
			$cnt++;
		}
		mysql_free_result($result);
?>
		<input type=hidden name=tot value="<?=$cnt?>">

		</table>
		</td>
	</tr>
	<tr><td height=5></td></tr>
	<tr>
		<td>
		<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
		<tr>
			<td style="padding-left:2">
			<?if(preg_match("/^(N|X|S)$/",$_ord->deli_gbn) && $_ord->pay_admin_proc!="C" && $prescd=="Y") {?>
			<img src=images/arrow_ltr.gif border=0 align=absmiddle>
			&nbsp; <B>������ ��ǰ�� ���ؼ�</B>
			<select name=deli_gbn onchange="changeDeli(this)">
			<option value="">ó������ ����</option>
			<option value="N">��ó��</option>
			<option value="S">�߼��غ�</option>
			<option value="Y">��ۿϷ�</option>
			</select>
			<?}?>
			&nbsp;
			<A HREF="javascript:changeDeliinfo()"><img src=images/btn_deliinfomodify.gif border=0 align=absmiddle></A>
			<? if($_ord->deli_gbn=="Y") { ?>&nbsp;<A HREF="javascript:changeReturninfo()"><img src="images/ordtl_btndelino.gif" border="0" align="absmiddle"></A><? } ?>
			</td>
		</tr>
		</table>
		</td>
	</tr>

	</form>

	<tr><td height=20></td></tr>
	<tr>
		<td><img src=images/icon_dot03.gif border=0 align=absmiddle> <B>�߰����/���γ���</B></td>
	</tr>
	<tr><td height=2></td></tr>
	<tr><td height=1 bgcolor=red></td></tr>
	<tr>
		<td>
		<table border=0 cellpadding=0 cellspacing=1 width=100% bgcolor=E7E7E7 style="table-layout:fixed">
		<col width=100></col>
		<col width=230></col>
		<col width=70></col>
		<col width=></col>
		<tr height=28 align=center bgcolor=F5F5F5>
			<td>�׸�</td>
			<td>����</td>
			<td>�ݾ�</td>
			<td>�ش� ��ǰ��</td>
		</tr>
<?
		if(count($etcdata)>0) {
			for($i=0;$i<count($etcdata);$i++) {
				if(ereg("^(COU)([0-9]{8})(X)$",$etcdata[$i]->productcode)) {				#����
					echo "<tr bgcolor=#FFFFFF>\n";
					echo "	<td align=center style=\"padding:7,5;font-size:8pt;line-height:10pt\">��������</td>\n";
					echo "	<td style=\"padding:7,5;font-size:8pt;line-height:10pt\">".$etcdata[$i]->productname."</td>\n";
					echo "	<td align=right style=\"padding:7,5;font-size:8pt;line-height:10pt\">".number_format($etcdata[$i]->price)."��</td>\n";
					echo "	<td style=\"padding:7,5;font-size:8pt;line-height:10pt\">".$etcdata[$i]->order_prmsg."</td>\n";
					echo "</tr>\n";
				} else if($etcdata[$i]->productcode=="99999999990X") {						#��۷�
					echo "<tr bgcolor=#FFFFFF>\n";
					echo "	<td align=center style=\"padding:5;font-size:8pt;line-height:10pt\">��۷�</td>\n";
					echo "	<td style=\"padding:7,5;font-size:8pt;line-height:10pt\">".$etcdata[$i]->productname."</td>\n";
					echo "	<td align=right style=\"padding:7,5;font-size:8pt;line-height:10pt\">".number_format($etcdata[$i]->price)."��</td>\n";
					echo "	<td style=\"padding:7,5;font-size:8pt;line-height:10pt\">".$etcdata[$i]->order_prmsg."</td>\n";
					echo "</tr>\n";
				}
			}
		} else {
			echo "<tr bgcolor=#FFFFFF><td colspan=4 align=center height=28>�߰����/���γ����� �����ϴ�.</td></tr>";
		}
?>
		</table>
		</td>
	</tr>
	<tr><td height=20></td></tr>
	<tr>
		<td>
		<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
		<col width=></col>
		<col width=15></col>
		<col width=></col>
		<tr>
			<td valign=top>
			<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
			<tr>
				<td><img src=images/icon_dot03.gif border=0 align=absmiddle> <B>�ֹ��� ����</B></td>
			</tr>
			<tr><td height=2></td></tr>
			<tr><td height=1 bgcolor=red></td></tr>
			<tr>
				<td>
				<table border=0 cellpadding=0 cellspacing=1 bgcolor=E7E7E7 width=100% style="table-layout:fixed">
				<col width=80></col>
				<col width=></col>
				<tr>
					<td bgcolor=#F5F5F5 style="padding:5,10">����(ID)</td>
					<td bgcolor=#FFFFFF style="padding:5,10;;font-size:8pt">
<?
					echo $_ord->sender_name;
					if(strlen($_ord->ordercode)==20 && substr($_ord->ordercode,-1)!="X") {
						echo " (".$_ord->id.") ";
					} else {
						echo " (��ȸ��)";
					}
?>
					</td>
				</tr>
				<tr>
					<td bgcolor=#F5F5F5 style="padding:5,10">����ó</td>
					<td bgcolor=#FFFFFF style="padding:5,10;;font-size:8pt"><?=$_ord->sender_tel?></td>
				</tr>
				<tr>
					<td bgcolor=#F5F5F5 style="padding:5,10">�̸���</td>
					<td bgcolor=#FFFFFF style="padding:5,10;font-size:8pt"><?=$_ord->sender_email?></td>
				</tr>
				<tr>
					<td bgcolor=#F5F5F5 style="padding:5,10">��û����</td>
					<td bgcolor=#FFFFFF style="padding:5,10;;font-size:8pt">
<?

					$sql5 = "SELECT order_prmsg FROM tblorderproduct WHERE vender='".$_VenderInfo->getVidx()."' and ordercode='".$_ord->ordercode."'";
					$result5=mysql_query($sql5,get_db_conn());
					$row5 = mysql_fetch_object($result5);

					$message1=explode("[MEMO]",$row5->order_prmsg);
					$message1[0]=ereg_replace("\"","&quot;",$message1[0]);
					$message1[0]=str_replace("\"","",$message1[0]);
					$ordmsg1=explode("\r\n",$message1[0]);

					echo $ordmsg1[0];

					$message=explode("[MEMO]",$_ord->order_msg);
					$message[0]=ereg_replace("\"","&quot;",$message[0]);
					$message[0]=str_replace("\"","",$message[0]);
					$ordmsg=explode("\r\n",$message[0]);
?>
					</td>
				</tr>
				</table>
				</td>
			</tr>
			</table>
			</td>

			<td>&nbsp;</td>

			<td valign=top>
			<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
			<tr>
				<td><img src=images/icon_dot03.gif border=0 align=absmiddle> <B>������ ����</B></td>
			</tr>
			<tr><td height=2></td></tr>
			<tr><td height=2 bgcolor=red></td></tr>
			<tr>
				<td>
				<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
				<tr>
					<td style="border-left-width:2px;border-bottom-width:2px;border-right-width:2px; border-left-color:red;border-bottom-color:red;border-right-color:red; border-left-style:solid;border-bottom-style:solid;border-right-style:solid;">
					<table border=0 cellpadding=0 cellspacing=1 bgcolor=E7E7E7 width=100% style="table-layout:fixed">
					<col width=80></col>
					<col width=></col>
					<tr>
						<td bgcolor=#F5F5F5 style="padding:5,10">����</td>
						<td bgcolor=#FFFFFF style="padding:5,10;font-weight:bold"><?=$_ord->receiver_name?></td>
					</tr>
					<tr>
						<td bgcolor=#F5F5F5 style="padding:5,10">����ó</td>
						<td bgcolor=#FFFFFF style="padding:5,10;font-weight:bold">
						<?=$_ord->receiver_tel1.(strlen($_ord->receiver_tel2)>0?" , ".$_ord->receiver_tel2:"")?>
						</td>
					</tr>
					<tr height=56>
						<td bgcolor=#F5F5F5 style="padding:5,10">�ּ�</td>
						<td bgcolor=#FFFFFF style="padding:5,10; line-height:12pt;font-weight:bold">
<?
						$address = eregi_replace("\n"," ",trim($_ord->receiver_addr));
						$address = eregi_replace("\r"," ",$address);
						$pos=strpos($address,"�ּ�");
						if ($pos>0) {
							$post = trim(substr($address,0,$pos));
							$address = substr($address,$pos+7);
						}
						$post = ereg_replace("�����ȣ : ","",$post);
						//$arpost = explode("-",$post);

						echo "[".$post."] ".$address;
?>
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
		<tr><td colspan=3 height=20></td></tr>
		<tr>
			<td valign=top>
			<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
			<tr>
				<td><img src=images/icon_dot03.gif border=0 align=absmiddle> <B>�ֹ����� �޸�</B> <font style="font-size:8pt;color:#2A97A7">(���θ� ��� ���� �ֹ����� �޸��Դϴ�.)</font></td>
			</tr>
			<tr><td height=2></td></tr>
			<tr><td height=1 bgcolor=red></td></tr>
			<tr>
				<td>
				<textarea style="width:100%; height:50; font-size:8pt" readonly><?=$message[1]?></textarea>
				</td>
			</tr>
			</table>
			</td>

			<td>&nbsp;</td>

			<td valign=top>
			<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
			<tr>
				<td><img src=images/icon_dot03.gif border=0 align=absmiddle> <B>�� �˸���</B> <font style="font-size:8pt;color:#2A97A7">(���θ� ��� ���� ������ �˸��� �޸��Դϴ�.)</font></td>
			</tr>
			<tr><td height=2></td></tr>
			<tr><td height=1 bgcolor=red></td></tr>
			<tr>
				<td>
				<textarea style="width:100%; height:50; font-size:8pt" readonly><?=$message[2]?></textarea>
				</td>
			</tr>
			</table>
			</td>
		</tr>
		</table>
		</td>
	</tr>
	<tr><td height=30></td></tr>
	<tr>
		<td align=center><A HREF="javascript:window.close()"><img src=images/btn_close03.gif border=0></A></td>
	</tr>
	<tr><td height=10></td></tr>
	</table>
	</td>
</tr>

<form name=form3 method=post action="product_prdmodify.php">
<input type=hidden name=prcode>
</form>

</table>

<?=$onload?>

</body>
</html>