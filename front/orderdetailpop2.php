<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/shopdata.php");

#### PG ����Ÿ ���� ####
$_ShopInfo->getPgdata();
########################

function getDeligbn($strdeli,$true=true) {
	global $_ShopInfo, $ordercode, $arrdeli;
	if(!is_array($arrdeli)) {
		$sql = "SELECT deli_gbn FROM tblorderproduct WHERE ordercode='".$ordercode."' AND NOT (productcode LIKE 'COU%' OR productcode LIKE '999999%') ";
		$sql.= "GROUP BY deli_gbn ";
		$result=mysql_query($sql,get_db_conn());
		$arrdeli=array();
		while($row=mysql_fetch_object($result)) {
			$arrdeli[]=$row->deli_gbn;
		}
		mysql_free_result($result);
	}

	$res=true;
	for($i=0;$i<count($arrdeli);$i++) {
		if($true==true) {
			if(!preg_match("/^(".$strdeli.")$/", $arrdeli[$i])) {
				$res=false;
				break;
			}
		} else {
			if(preg_match("/^(".$strdeli.")$/", $arrdeli[$i])) {
				$res=false;
				break;
			}
		}
	}
	return $res;
}

$ordercode=isset($_POST["ordercode"]) ? $_POST["ordercode"] : $_GET["ordercode"];	//�α����� ȸ���� ��ȸ��
$ordername=$_POST["ordername"]; //��ȸ�� ��ȸ�� �ֹ��ڸ�
$ordercodeid=$_POST["ordercodeid"];	//��ȸ�� ��ȸ�� �ֹ���ȣ 6�ڸ�
$print=$_POST["print"];	//OK�� ��� ����Ʈ

if(strlen($ordercodeid)>0 && strlen($ordercodeid)!=6) {
	echo "<html><head><title></title></head><body onload=\"alert('�ֹ���ȣ 6�ڸ��� ��Ȯ�� �Է��Ͻñ� �ٶ��ϴ�.');window.close();\"></body></html>";exit;
}

$gift_type=explode("|",$_data->gift_type);

$type=$_POST["type"];
$tempkey=$_POST["tempkey"];
$rescode=$_POST["rescode"];

####### ����ũ�� ���Ű��� #######
if ($type=="okescrow" && strlen($ordercode)>0 && $rescode=="Y") {
	$sql = "UPDATE tblorderinfo SET escrow_result='Y' ";
	$sql.= "WHERE ordercode='".$ordercode."' ";
	$sql.= "AND (MID(paymethod,1,1)='Q' OR MID(paymethod,1,1)='P') ";
	$sql.= "AND deli_gbn='Y' ";
	$result = mysql_query($sql,get_db_conn());

	echo "<script>alert('���Ű��� �Ǿ����ϴ�.');self.close();</script>";
	exit;
}

####### �ֹ���� (����ũ�� ����) #######
if ($type=="cancel" || ($type=="okescrow" && $rescode=="C" && strlen($ordercode)>0)) { //�Ÿź�ȣ �ֹ�������
	$sql = "SELECT price,deli_gbn,reserve,sender_name,paymethod,bank_date FROM tblorderinfo ";
	$sql.= "WHERE ordercode='".$ordercode."' ";
	if($type=="cancel") $sql.= "AND tempkey='".$tempkey."' ";
	$result=mysql_query($sql,get_db_conn());
	if($row=mysql_fetch_object($result)) {
		if (
		(preg_match("/^(Q|P){1}/", $row->paymethod) && !preg_match("/^(C|D|E|H)$/", $row->deli_gbn) && getDeligbn("C|D|E|H",false))
		|| ($_data->ordercancel==0 && ($row->deli_gbn=="S" || $row->deli_gbn=="N") && getDeligbn("N|S",true)) //tblorderproduct�� deli_gbn�� "S|N"�� �ִ��� Ȯ���Ѵ�.
		|| ($_data->ordercancel==2 && $row->deli_gbn=="N" && getDeligbn("N",true)) //tblorderproduct�� deli_gbn�� "N"�� �ִ��� Ȯ���Ѵ�.
		|| ($_data->ordercancel=="1" && $row->paymethod=="B" && strlen($row->bank_date)<12 && $row->deli_gbn=="N" && getDeligbn("N",true))
		) {  // ��۱����� ��� ���� ����� ��������쿡�� �ֹ� ���, ���� �����ϰ�� �ԱݾȵȰǸ�

			if(preg_match("/^(Q|P){1}/", $row->paymethod)) $deliok="D";
			else $deliok="C";

			$sql = "UPDATE tblorderinfo SET deli_gbn='".$deliok."' WHERE ordercode='".$ordercode."' ";
			if($type=="cancel") $sql.= "AND tempkey='".$tempkey."' ";
			if(mysql_query($sql,get_db_conn())) {
				$sql = "UPDATE tblorderproduct SET deli_gbn='".$deliok."' ";
				$sql.= "WHERE ordercode='".$ordercode."' ";
				$sql.= "AND NOT (productcode LIKE 'COU%' AND productcode LIKE '999999%') ";
				mysql_query($sql,get_db_conn());

				if(empty($ordercodeid) && strlen($_ShopInfo->getMemid())>0 && $row->reserve>0) {
					$sql = "UPDATE tblmember SET reserve=reserve+".abs($row->reserve)." ";
					$sql.= "WHERE id='".$_ShopInfo->getMemid()."' ";
					mysql_query($sql,get_db_conn());

					$sql = "INSERT tblreserve SET ";
					$sql.= "id			= '".$_ShopInfo->getMemid()."', ";
					$sql.= "reserve		= ".$row->reserve.", ";
					$sql.= "reserve_yn	= 'Y', ";
					$sql.= "content		= '�ֹ� ��Ұǿ� ���� ������ ȯ��', ";
					$sql.= "orderdata	= '".$ordercode."=".$row->price."', ";
					$sql.= "date		= '".date("YmdHis")."' ";
					mysql_query($sql,get_db_conn());
				}

				/////////////// �ֹ���ҽ� �����ڿ��� ������ �߼�
				$maildata=$row->sender_name."������ <font color=blue>".date("Y")."�� ".date("m")."�� ".date("d")."��</font>�� �Ʒ��� ���� �ֹ��� ����ϼ̽��ϴ�.<br><br>";
				$maildata.="<li> ��ҵ� �ֹ��� ��ȣ : $ordercode<br><br>";
				$maildata.="��ҵ� �ֹ��� �����ڸ޴��� �ֹ���ȸ���� Ȯ���Ͻ� �� �ֽ��ϴ�.";

				if (strlen($_data->shopname)>0) $mailshopname = "=?ks_c_5601-1987?B?".base64_encode($_data->shopname)."?=";
				$header=getMailHeader($mailshopname,$_data->info_email);
				if(ismail($_data->info_email)) {
					sendmail($_data->info_email, $_data->shopname." �ֹ���� Ȯ�� �����Դϴ�.", $maildata, $header);
				}

				if(strlen($_data->okcancel_msg)==0)  $_data->okcancel_msg="���������� �ֹ��� ��ҵǾ����ϴ�!";
				if (preg_match("/^(Q){1}/", $row->paymethod) && strlen($row->bank_date)>=12) $_data->okcancel_msg.=" ���������� �������� ��� �� ȯ��ó���˴ϴ�.";
				if (preg_match("/^(P){1}/", $row->paymethod) && $row->pay_flag=="0000") $_data->okcancel_msg.=" ���������� �������� ��� �� ī�����ó���˴ϴ�.";

				$sqlsms = "SELECT * FROM tblsmsinfo WHERE admin_cancel='Y' ";
				$resultsms= mysql_query($sqlsms,get_db_conn());
				if($rowsms=mysql_fetch_object($resultsms)) {
					if(strlen($ordercode)>0) {
						$sms_id=$rowsms->id;
						$sms_authkey=$rowsms->authkey;

						$totellist=$rowsms->admin_tel;
						if(strlen($rowsms->subadmin1_tel)>8) $totellist.=",".$rowsms->subadmin1_tel;
						if(strlen($rowsms->subadmin2_tel)>8) $totellist.=",".$rowsms->subadmin2_tel;
						if(strlen($rowsms->subadmin3_tel)>8) $totellist.=",".$rowsms->subadmin3_tel;
						$fromtel=$rowsms->return_tel;

						$smsmsg=$row->sender_name."�Բ��� ".substr($ordercode,0,4)."/".substr($ordercode,4,2)."/".substr($ordercode,6,2)."�� �ֹ��Ͻ� �ֹ��� ����ϼ̽��ϴ�.";
						$etcmsg="�ֹ���� �޼���(������)";
						if($rowsms->sleep_time1!=$rowsms->sleep_time2) {
							$date="0";
							$time = date("Hi");
							if($rowsms->sleep_time2<"12" && $time<=substr("0".$rowsms->sleep_time2,-2)."59") $time+=2400;
							if($rowsms->sleep_time2<"12" && $rowsms->sleep_time1>$rowsms->sleep_time2) $rowsms->sleep_time2+=24;

							if($time<substr("0".$rowsms->sleep_time1,-2)."00" || $time>=substr("0".$rowsms->sleep_time2,-2)."59"){
								if($time<substr("0".$rowsms->sleep_time1,-2)."00") $day = date("d");
								else $day=date("d")+1;
								$date = date("Y-m-d H:i:s",mktime($rowsms->sleep_time1,0,0,date("m"),$day,date("Y")));
							}
						}
						$temp=SendSMS($sms_id, $sms_authkey, $totellist, "", $fromtel, $date, $smsmsg, $etcmsg);
					}
				}
				mysql_free_result($resultsms);
				$onload="<script>alert('".$_data->okcancel_msg."');</script>";
			} else {
				$onload="<script>alert('��û�Ͻ� �۾��� ������ �߻��Ͽ����ϴ�.');</script>";
			}
		} else if (preg_match("/^(Q|P){1}/", $row->paymethod) && preg_match("/^(D)$/", $row->deli_gbn)) {
			$onload="<script>alert('���������� �������� ��� �� ȯ��ó���˴ϴ�.');</script>";
		} else if($_data->ordercancel==0) {
			if(strlen($_data->nocancel_msg)==0) $onload="<script>alert(\"�̹� ��۵� ��ǰ�� �ֽ��ϴ�. ���θ��� �����ֽñ� �ٶ��ϴ�.\");</script>";
			else $onload="<script>alert('$_data->nocancel_msg');</script>";
		} else if($_data->ordercancel==2) {
			if(strlen($_data->nocancel_msg)==0) $onload="<script>alert(\"�߼��غ� �Ϸ�Ǿ� �ù�ȸ�翡 ���޵� ��ǰ�� �ֽ��ϴ�. ���θ��� �����ֽñ� �ٶ��ϴ�.\");</script>";
			else $onload="<script>alert('$_data->nocancel_msg');</script>";
		} else {
			if(strlen($_data->nocancel_msg)==0) $onload="<script>alert(\"��������� ȯ��/��Ҵ� ���θ��� �����ֽñ� �ٶ��ϴ�.\");</script>";
			else $onload="<script>alert('$_data->nocancel_msg');</script>";
		}
	}
}

####### �ֹ��� ���� #######
if($type=="delete" && strlen($ordercode)>0 && strlen($tempkey)>0) {
	$sql = "SELECT del_gbn FROM tblorderinfo WHERE ordercode='".$ordercode."' AND tempkey='".$tempkey."' ";
	$result=mysql_query($sql,get_db_conn());
	$row=mysql_fetch_object($result);
	mysql_free_result($result);
	$del_gbn = $row->del_gbn;
	if($del_gbn=="N" || $del_gbn==NULL) $okdel="Y";
	else if($del_gbn=="A") $okdel="R";
	else {
		echo "<html><head><title></title></head><body onload=\"alert('�ش� �ֹ����� �̹� ����ó���� �Ǿ����ϴ�.');window.close();opener.location.reload();\"></body></html>";exit;
	}

	$sql = "UPDATE tblorderinfo SET del_gbn='".$okdel."' WHERE ordercode='".$ordercode."' AND tempkey='".$tempkey."' ";
	mysql_query($sql,get_db_conn());
	echo "<html><head><title></title></head><body onload=\"alert('�ش� �ֹ����� ����ó�� �Ͽ����ϴ�.');window.close();opener.location.reload();\"></body></html>";exit;
}

?>

<html>
<head>
<title>�ֹ����� ��ȸ</title>
<meta http-equiv="CONTENT-TYPE" content="text/html;charset=EUC-KR">

<script type="text/javascript" src="<?=$Dir?>lib/lib.js.php"></script>
<style>
td {font-family:����;color:333333;font-size:9pt;}

tr {font-family:����;color:333333;font-size:9pt;}
BODY,TD,SELECT,DIV,form,TEXTAREA,center,option,pre,blockquote {font-family:����;color:333333;font-size:9pt;}

A:link    {color:333333;text-decoration:none;}

A:visited {color:333333;text-decoration:none;}

A:active  {color:333333;text-decoration:none;}

A:hover  {color:#CC0000;text-decoration:none;}
</style>
<SCRIPT LANGUAGE="JavaScript">
<!--
window.moveTo(10,10);
window.resizeTo(800,650);
window.name="orderpop";

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

function DeliSearch(url){
	window.open(url,'�����ȸ','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,copyhistory=no,width=550,height=500');
}

function view_product(productcode) {
	opener.location.href="<?=$Dir.FrontDir?>productdetail.php?productcode="+productcode;
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

function order_cancel(tempkey,ordercode) {	//�ֹ����
	if (confirm("�ֹ��� ����Ͻðڽ��ϱ�?")) {
		document.form1.tempkey.value=tempkey;
		document.form1.ordercode.value=ordercode;
		document.form1.type.value="cancel";
		document.form1.submit();
	}
}
function order_del(tempkey,ordercode) {	//�ֹ��� ����
	if(confirm("�ֹ��ǿ� ���ؼ� ��Ҵ� ���� �ʰ�, ��ȸ�� �Ұ����մϴ�.\n\n�ֹ��� ���븸 �����Ͻðڽ��ϱ�?")) {
		document.form1.tempkey.value=tempkey;
		document.form1.ordercode.value=ordercode;
		document.form1.type.value="delete";
		document.form1.submit();
	}
}

function get_taxsave(ordercode) {	//���ݿ����� ��û
	window.open("about:blank","taxsavepop","width=266,height=220,scrollbars=no");
	document.taxsaveform.ordercode.value=ordercode;
	document.taxsaveform.submit();
}

function setPackageShow(packageid) {
	if(packageid.length>0 && document.getElementById(packageid)) {
		if(document.getElementById(packageid).style.display=="none") {
			document.getElementById(packageid).style.display="";
		} else {
			document.getElementById(packageid).style.display="none";
		}
	}
}
//-->
</SCRIPT>
</head>

<body topmargin=0 leftmargin=0 rightmargin=0 marginheight=0 marginwidth=0>
<center>
<table border=0 cellpadding=0 cellspacing=0 width=100%>
<tr>
	<td align=center style="padding:10,10,10,10">
	<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
	<tr><td align=center height=30 bgcolor=#454545><FONT COLOR="#FFFFFF"><B>�ֹ���ȸ</B></FONT></td></tr>
<?
	if (strlen($ordercodeid)>0 && strlen($ordername)>0) {	//��ȸ�� �ֹ���ȸ
		$curdate = date("Ymd",mktime(0,0,0,date("m"),date("d")-90,date("Y")))."00000";
		$sql = "SELECT * FROM tblorderinfo WHERE ordercode > '".$curdate."' AND id LIKE 'X".$ordercodeid."%' ";
		$sql.= "AND sender_name='".$ordername."' ";
		$result=mysql_query($sql,get_db_conn());
		if($row=mysql_fetch_object($result)) {
			$_ord=$row;
			$ordercode=$row->ordercode;
			$gift_price=$row->price-$row->deli_price;
		} else {
			echo "<tr height=200><td align=center>��ȸ�Ͻ� �ֹ������� �����ϴ�.<br><br>ȸ���ֹ��� �ƴѰ�� �ֹ��� 90���� ����Ͽ��ٸ� ������ ���ǹٶ��ϴ�.</td></tr>\n";
			echo "<tr><td align=center><input type=button value='�� ��' style=\"cursor:hand;color:#FFFFFF;border-color:#666666;background-color:#666666;font-size:8pt;font-family:Tahoma;height:20px;width:70\" onclick=\"window.close()\"></td></tr>\n";
			echo "</table>";
			exit;
		}
		mysql_free_result($result);
	} else {
		$sql = "SELECT * FROM tblorderinfo WHERE ordercode='".$ordercode."' ";
		$result=mysql_query($sql,get_db_conn());
		if($row=mysql_fetch_object($result)) {
			$_ord=$row;
			$gift_price=$row->price-$row->deli_price;
		} else {
			echo "<tr height=200><td align=center>��ȸ�Ͻ� �ֹ������� �����ϴ�.</td></tr>\n";
			echo "<tr><td align=center><input type=button value='�� ��' style=\"cursor:hand;color:#FFFFFF;border-color:#666666;background-color:#666666;font-size:8pt;font-family:Tahoma;height:20px;width:70\" onclick=\"window.close()\"></td></tr>\n";
			echo "</table>";
			exit;
		}
		mysql_free_result($result);
	}
?>
	<tr><td height=10></td></tr>
	<tr>
		<td style="padding-left:20">
		<img src="<?=$Dir?>images/common/orderdetailpop_img.gif" border=0 align=absmiddle>
		&nbsp;&nbsp;&nbsp;
		<img src="<?=$Dir?>images/common/orderdetailpop_arrow.gif" border=0 align=absmiddle>
		<FONT COLOR="#EE1A02"><B><?=$_ord->sender_name?></B></FONT>�Բ��� <FONT COLOR="#111682"><?=substr($_ord->ordercode,0,4)?>�� <?=substr($_ord->ordercode,4,2)?>�� <?=substr($_ord->ordercode,6,2)?>��</FONT> �ֹ��Ͻ� �����Դϴ�.
		</td>
	</tr>
	<tr><td height=10></td></tr>
	<tr>
		<td><img src=<?=$Dir?>images/icon_dot.gif border=0 align=absmiddle> <B>�ֹ���ǰ ����</B></td>
	</tr>
	<tr>
		<td>
		<table border=0 cellpadding=0 cellspacing=1 bgcolor=E7E7E7 width=100% style="table-layout:fixed">
		<col width=45></col>
		<col width=></col>
		<col width=75></col>
		<col width=75></col>
		<col width=30></col>
		<col width=70></col>
		<col width=30></col>
		<col width=70></col>
		<col width=100></col>
		<tr height=28 bgcolor=#F5F5F5>
			<td align=center>�̹���</td>
			<td align=center>��ǰ��</td>
			<td align=center>���û���1</td>
			<td align=center>���û���2</td>
			<td align=center>����</td>
			<td align=center>����</td>
			<td align=center>�޸�</td>
			<td align=center>ó������</td>
			<td align=center>������ȣ</td>
		</tr>
<?
		$sql="SELECT * FROM tbldelicompany ORDER BY company_name ";
		$result=mysql_query($sql,get_db_conn());
		$delicomlist=array();
		while($row=mysql_fetch_object($result)) {
			$delicomlist[$row->code]=$row;
		}
		mysql_free_result($result);

		$sql = "SELECT productcode,productname,opt1_name,opt2_name,tempkey,addcode,quantity,price,reserve, ";
		$sql.= "quantity*price as sumprice, deli_gbn, deli_com, deli_num, deli_date, order_prmsg, package_idx, assemble_idx, assemble_info ";
		$sql.= "FROM tblorderproduct WHERE ordercode='".$ordercode."' ";
		$result=mysql_query($sql,get_db_conn());
		$cnt=0;
		$gift_check="N";
		$taxsaveprname="";
		$etcdata=array();
		$in_reserve=0;

		while($row=mysql_fetch_object($result)) {
			if (substr($row->productcode,0,3)=="COU") {
				if ($gift_check=="N" && strpos($row->productcode,"GIFT")!==false) $gift_check="Y";
				$etcdata[]=$row;
				continue;
			}
			$gift_tempkey=$row->tempkey;
			$taxsaveprname.=$row->productname.",";

			$optvalue="";
			if(ereg("^(\[OPTG)([0-9]{3})(\])$",$row->opt1_name)) {
				$optioncode=$row->opt1_name;
				$row->opt1_name="";
				$sql = "SELECT opt_name FROM tblorderoption WHERE ordercode='".$ordercode."' AND productcode='".$row->productcode."' ";
				$sql.= "AND opt_idx='".$optioncode."' ";
				$result2=mysql_query($sql,get_db_conn());
				if($row2=mysql_fetch_object($result2)) {
					$optvalue=$row2->opt_name;
				}
				mysql_free_result($result2);
			}

			$in_reserve+=$row->quantity*$row->reserve;

			$file="";
			if(file_exists($Dir.DataDir."shopimages/product/".$row->productcode."3.gif"))
				$file=$row->productcode."3.gif";
			else if(file_exists($Dir.DataDir."shopimages/product/".$row->productcode."3.jpg"))
				$file=$row->productcode."3.jpg";

			$packagestr = "";
			$packageliststr = "";
			$assemblestr = "";
			$rowspanstr = 1;
			if(strlen(str_replace("","",str_replace(":","",str_replace("=","",$row->assemble_info))))>0) {
				$assemble_infoall_exp = explode("=",$row->assemble_info);

				if($row->package_idx>0 && strlen(str_replace("","",str_replace(":","",$assemble_infoall_exp[0])))>0) {
					$rowspanstr++;
					$package_info_exp = explode(":", $assemble_infoall_exp[0]);
					$packagestr.="<br><img src=\"".$Dir."images/common/icn_package.gif\" border=0 align=absmiddle> ".$package_info_exp[3]."(<font color=#FF3C00>+".number_format($package_info_exp[2])."��</font>)";
					$productname_package_list_exp = explode("",$package_info_exp[1]);
					$packageliststr.="<tr bgcolor=\"#FFFFFF\">\n";
					$packageliststr.="	<td colspan=\"6\" style=\"padding-left:5px;\">\n";
					$packageliststr.= "	<table border=0 width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">\n";
					$packageliststr.= "	<tr>\n";
					if(count($productname_package_list_exp)>0 && strlen($productname_package_list_exp[0])>0) {
						$packageliststr.= "		<td width=\"50\" valign=\"top\" style=\"padding-left:12px;\" nowrap><font color=\"#FF7100\" style=\"line-height:10px;\">��<br>����<b>��</b></font></td>\n";
						$packageliststr.= "		<td width=\"100%\">\n";
						$packageliststr.= "		<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-left:1px #DDDDDD solid;\">\n";

						for($k=0; $k<count($productname_package_list_exp); $k++) {
							$packageliststr.= "		<tr>\n";
							$packageliststr.= "			<td bgcolor=\"#FFFFFF\"".($k>0?"style=\"border-top:1px #DDDDDD solid;\"":"").">\n";
							$packageliststr.= "			<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\n";
							$packageliststr.= "			<col width=\"\"></col>\n";
							$packageliststr.= "			<col width=\"124\"></col>\n";
							$packageliststr.= "			<tr>\n";
							$packageliststr.= "				<td style=\"padding:4px;word-break:break-all;font-size:8pt;\"><font color=\"#000000\">".$productname_package_list_exp[$k]."</font>&nbsp;</td>\n";
							$packageliststr.= "				<td align=\"center\" style=\"padding:4px;border-left:1px #DDDDDD solid;font-size:8pt;\">�� ��ǰ 1���� ����1��</td>\n";
							$packageliststr.= "			</tr>\n";
							$packageliststr.= "			</table>\n";
							$packageliststr.= "			</td>\n";
							$packageliststr.= "		</tr>\n";
						}
						$packageliststr.= "		</table>\n";
						$packageliststr.= "		</td>\n";
					} else {
						$packageliststr.= "		<td width=\"50\" valign=\"top\" style=\"padding-left:12px;\" nowrap><font color=\"#FF7100\" style=\"line-height:10px;\">��<br>����<b>��</b></font></td>\n";
						$packageliststr.= "		<td width=\"100%\">\n";
						$packageliststr.= "		<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-left:1px #DDDDDD solid;\">\n";
						$packageliststr.= "		<tr>\n";
						$packageliststr.= "			<td bgcolor=\"#FFFFFF\" style=\"padding:4px;word-break:break-all;font-size:8pt;\"><font color=\"#000000\">������ǰ�� �������� �ʴ� ��Ű��</font></td>\n";
						$packageliststr.= "		</tr>\n";
						$packageliststr.= "		</table>\n";
						$packageliststr.= "		</td>\n";
					}
					$packageliststr.= "	</tr>\n";
					$packageliststr.= "	</table>\n";
					$packageliststr.="	</td>\n";
					$packageliststr.="</tr>\n";
				}

				if($row->assemble_idx>0 && strlen(str_replace("","",str_replace(":","",$assemble_infoall_exp[1])))>0) {
					$rowspanstr++;
					$assemblestr.="<tr bgcolor=\"#FFFFFF\">\n";
					$assemblestr.="	<td colspan=\"6\" style=\"padding-left:5px;\">\n";
					$assemblestr.="	<table border=0 width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">\n";
					$assemblestr.="	<tr>\n";
					$assemblestr.="		<td width=\"50\" valign=\"top\" style=\"padding-left:5px;\" nowrap><font color=\"#FF7100\" style=\"line-height:10px;\">��<br>����<b>��</b></font></td>\n";
					$assemblestr.="		<td width=\"100%\">\n";
					$assemblestr.="		<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-left:1px #DDDDDD solid;\">\n";

					$assemble_info_exp = explode(":", $assemble_infoall_exp[1]);

					if(count($assemble_info_exp)>2) {
						$assemble_productname_exp = explode("", $assemble_info_exp[1]);
						$assemble_sellprice_exp = explode("", $assemble_info_exp[2]);

						for($k=0; $k<count($assemble_productname_exp); $k++) {
							$assemblestr.="		<tr>\n";
							$assemblestr.="			<td bgcolor=\"#FFFFFF\"".($k>0?"style=\"border-top:1px #DDDDDD solid;\"":"").">\n";
							$assemblestr.="			<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\n";
							$assemblestr.="			<col width=\"\"></col>\n";
							$assemblestr.="			<col width=\"67\"></col>\n";
							$assemblestr.="			<col width=\"124\"></col>\n";
							$assemblestr.="			<tr>\n";
							$assemblestr.="				<td style=\"padding:4px;word-break:break-all;font-size:8pt;\">".$assemble_productname_exp[$k]."&nbsp;</td>\n";
							$assemblestr.="				<td align=\"right\" style=\"padding:4px;border-left:1px #DDDDDD solid;border-right:1px #DDDDDD solid;font-size:8pt\">".number_format((int)$assemble_sellprice_exp[$k])."</td>\n";
							$assemblestr.="				<td align=\"center\" style=\"padding:4px;font-size:8pt\">�� ��ǰ 1���� ����1��</td>\n";
							$assemblestr.="			</tr>\n";
							$assemblestr.="			</table>\n";
							$assemblestr.="			</td>\n";
							$assemblestr.="		</tr>\n";
						}
					}
					@mysql_free_result($alproresult);
					$assemblestr.="		</table>\n";
					$assemblestr.="		</td>\n";
					$assemblestr.="	</tr>\n";
					$assemblestr.="	</table>\n";
					$assemblestr.="	</td>\n";
					$assemblestr.="</tr>\n";
				}
			}

			echo "<tr bgcolor=#FFFFFF>\n";
			echo "	<td align=center rowspan=\"".$rowspanstr."\" style=\"padding:2px;\">\n";
			if(strlen($file)>0) {
				echo "		<span onMouseOver='ProductMouseOver($cnt)' onMouseOut=\"ProductMouseOut('primage".$cnt."');\">";
				echo "		<img src=".$Dir.DataDir."shopimages/product/".$file." border=0 width=40 height=40>";
				echo "		</span>\n";
				echo "		<div id=primage".$cnt." style=\"position:absolute; z-index:100; visibility:hidden;\">\n";
				echo "		<table border=0 cellspacing=0 cellpadding=0 width=170>\n";
				echo "		<tr bgcolor=#FFFFFF>\n";
				echo "			<td align=center width=100% height=150 style=\"border:#000000 solid 1px\"><img src=".$Dir.DataDir."shopimages/product/".$file."></td>\n";
				echo "		</tr>\n";
				echo "		</table>\n";
				echo "		</div>\n";
			} else {
				echo "&nbsp;";
			}
			echo "	</td>\n";
			echo "	<td style=\"font-size:8pt;padding:5,5,5,5\">";
			if (substr($row->productcode,0,3)!="999" && substr($row->productcode,0,3)!="COU") {
				echo "<a href=\"javascript:view_product('".$row->productcode."')\">";
			}
			echo ($row->sumprice<0?"<font color=#0000FF>":"").$row->productname.(strlen($row->addcode)>0?" - $row->addcode":"")."</a>";
			if(strlen($optvalue)>0) {
				echo "<br><img src=\"".$Dir."images/common/icn_option.gif\" border=0 align=absmiddle> ".$optvalue."";
			}
			if(strlen($packagestr)>0) {
				echo $packagestr;
			}
			echo "	</td>\n";
			echo "	<td align=center style=\"font-size:8pt\">".$row->opt1_name."</td>\n";
			echo "	<td align=center style=\"font-size:8pt\">".$row->opt2_name."</td>\n";
			echo "	<td align=center style=\"font-size:8pt\">".$row->quantity."</td>\n";
			echo "	<td align=right style=\"font-size:8pt;padding-right:5\"><FONT COLOR=\"#EE1A02\"><B>".number_format($row->sumprice)."</B></FONT></td>\n";
			if(strlen($row->order_prmsg)>0) {
				echo "	<td align=center style=\"font-size:8pt;color:red\"><a style=\"cursor:hand;\" onMouseOver='MemoMouseOver($cnt)' onMouseOut=\"MemoMouseOut($cnt);\">�޸�</a>";
				echo "	<div id=memo".$cnt." style=\"left:160px;position:absolute; z-index:100; visibility:hidden;\">\n";
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
			echo "	<td align=center style=\"font-size:8pt\" rowspan=\"".$rowspanstr."\">";
			if ($row->deli_gbn=="C") echo "�ֹ����";
			else if ($row->deli_gbn=="D") echo "��ҿ�û";
			else if ($row->deli_gbn=="E") echo "ȯ�Ҵ��";
			else if ($row->deli_gbn=="X") echo "������ȣ�߼�";
			else if ($row->deli_gbn=="Y") {
				if($_ord->gift=='1') {
					echo "���� �� �����Ϸ�";
				}
				else echo "�����Ϸ�";
			}
			else if ($row->deli_gbn=="N") {
				if (strlen($_ord->bank_date)<12 && preg_match("/^(B|O|Q){1}/", $_ord->paymethod)) echo "�Ա�Ȯ����";
				else if ($_ord->pay_admin_proc=="C" && $_ord->pay_flag=="0000") echo "�������";
				else if (strlen($_ord->bank_date)>=12 || $_ord->pay_flag=="0000") echo "������ȣ�߼�";
				else echo "����Ȯ����";
			} else if ($row->deli_gbn=="S") {
				echo "�߼��غ�";
			} else if ($row->deli_gbn=="R") {
				echo "�ݼ�ó��";
			} else if ($row->deli_gbn=="H") {
				echo "�߼ۿϷ� [���꺸��]";
			}
			echo "	</td>\n";
			echo "	<td align=center style=\"font-size:8pt\" rowspan=\"".$rowspanstr."\">";

			if($_ord->gift=='1') {
				$sql = "SELECT * FROM tblgift_info WHERE ordercode='{$ordercode}'";
				$result2=mysql_query($sql,get_db_conn());
				if($row2 = mysql_fetch_array($result2)) {
					echo "{$row2['authcode1']} - {$row2['authcode2']} <br />���ȸ�� : {$row2['use_id']} (".date("m-d H:i",$row2['use_date']).")";
				}
				mysql_free_result($result2);

			}
			else echo "-";

			echo "	</td>\n";
			echo "</tr>\n";
			echo $assemblestr;
			echo $packageliststr;
			$cnt++;
		}
		mysql_free_result($result);
?>
		</table>
		</td>
	</tr>
	<tr><td height=20></td></tr>
	<tr>
		<td><img src=<?=$Dir?>images/icon_dot.gif border=0 align=absmiddle> <B>�߰����/����/��������</B></td>
	</tr>
	<tr>
		<td>
		<table border=0 cellpadding=0 cellspacing=1 width=100% bgcolor=E7E7E7 style="table-layout:fixed">
		<col width=90></col>
		<col width=220></col>
		<col width=70></col>
		<col width=70></col>
		<col width=></col>
		<tr height=28 align=center bgcolor=F5F5F5>
			<td>�׸�</td>
			<td>����</td>
			<td>�ݾ�</td>
			<td>������</td>
			<td>�ش� ��ǰ��</td>
		</tr>
<?
		for($i=0;$i<count($etcdata);$i++) {
			$in_reserve+=$etcdata[$j]->reserve;

			if(ereg("^(COU)([0-9]{8})(X)$",$etcdata[$i]->productcode)) {				#����
				echo "<tr bgcolor=#FFFFFF>\n";
				echo "	<td align=center style=\"padding:7,5;font-size:8pt;line-height:10pt\">���� ���</td>\n";
				echo "	<td style=\"padding:7,5;font-size:8pt;line-height:10pt\">".$etcdata[$i]->productname."</td>\n";
				echo "	<td align=right style=\"padding:7,5;font-size:8pt;line-height:10pt\">".($etcdata[$i]->price!=0?number_format($etcdata[$i]->price)."��":"&nbsp;")."</td>\n";
				echo "	<td align=right style=\"padding:7,5;font-size:8pt;line-height:10pt\">".($etcdata[$i]->reserve!=0?number_format($etcdata[$i]->reserve)."��":"&nbsp;")."</td>\n";
				echo "	<td style=\"padding:7,5;font-size:8pt;line-height:10pt\">".$etcdata[$i]->order_prmsg."</td>\n";
				echo "</tr>\n";
			} else if(ereg("^(9999999999)([0-9]{1})(X)$",$etcdata[$i]->productcode)) {
				if($etcdata[$i]->productcode=="99999999999X") {
					echo "<tr bgcolor=#FFFFFF>\n";
					echo "	<td align=center style=\"padding:7,5;font-size:8pt;line-height:10pt\">���� ����</td>\n";
					echo "	<td style=\"padding:7,5;font-size:8pt;line-height:10pt\">".$etcdata[$i]->productname."</td>\n";
					echo "	<td align=right style=\"padding:7,5;font-size:8pt;line-height:10pt\">".($etcdata[$i]->price!=0?number_format($etcdata[$i]->price)."��":"&nbsp;")."</td>\n";
					echo "	<td align=right style=\"padding:7,5;font-size:8pt;line-height:10pt\">".($etcdata[$i]->reserve!=0?number_format($etcdata[$i]->reserve)."��":"&nbsp;")."</td>\n";
					echo "	<td style=\"padding:7,5;font-size:8pt;line-height:10pt\" align=center>�ֹ��� ��ü����</td>\n";
					echo "</tr>\n";
				} else if($etcdata[$i]->productcode=="99999999998X") {
					echo "<tr bgcolor=#FFFFFF>\n";
					echo "	<td align=center style=\"padding:7,5;font-size:8pt;line-height:10pt\">���� ������</td>\n";
					echo "	<td style=\"padding:7,5;font-size:8pt;line-height:10pt\">".$etcdata[$i]->productname."</td>\n";
					echo "	<td align=right style=\"padding:7,5;font-size:8pt;line-height:10pt\">".($etcdata[$i]->price!=0?number_format($etcdata[$i]->price)."��":"&nbsp;")."</td>\n";
					echo "	<td align=right style=\"padding:7,5;font-size:8pt;line-height:10pt\">".($etcdata[$i]->reserve!=0?number_format($etcdata[$i]->reserve)."��":"&nbsp;")."</td>\n";
					echo "	<td style=\"padding:7,5;font-size:8pt;line-height:10pt\" align=center>�ֹ��� ��ü����</td>\n";
					echo "</tr>\n";
				} else if($etcdata[$i]->productcode=="99999999990X") {
					echo "<tr bgcolor=#FFFFFF>\n";
					echo "	<td align=center style=\"padding:7,5;font-size:8pt;line-height:10pt\">��۷�</td>\n";
					echo "	<td style=\"padding:7,5;font-size:8pt;line-height:10pt\">".$etcdata[$i]->productname."</td>\n";
					echo "	<td align=right style=\"padding:7,5;font-size:8pt;line-height:10pt\">".($etcdata[$i]->price!=0?number_format($etcdata[$i]->price)."��":"&nbsp;")."</td>\n";
					echo "	<td align=right style=\"padding:7,5;font-size:8pt;line-height:10pt\">".($etcdata[$i]->reserve!=0?number_format($etcdata[$i]->reserve)."��":"&nbsp;")."</td>\n";
					echo "	<td style=\"padding:7,5;font-size:8pt;line-height:10pt\">".$etcdata[$i]->order_prmsg."</td>\n";
					echo "</tr>\n";
				} else if($etcdata[$i]->productcode=="99999999997X") {
					echo "<tr bgcolor=#FFFFFF>\n";
					echo "	<td align=center style=\"padding:7,5;font-size:8pt;line-height:10pt\">�ΰ���(VAT)</td>\n";
					echo "	<td style=\"padding:7,5;font-size:8pt;line-height:10pt\">".$etcdata[$i]->productname."</td>\n";
					echo "	<td align=right style=\"padding:7,5;font-size:8pt;line-height:10pt\">".($etcdata[$i]->price!=0?number_format($etcdata[$i]->price)."��":"&nbsp;")."</td>\n";
					echo "	<td align=right style=\"padding:7,5;font-size:8pt;line-height:10pt\"></td>\n";
					echo "	<td style=\"padding:7,5;font-size:8pt;line-height:10pt\" align=center>�ֹ��� ��ü����</td>\n";
					echo "</tr>\n";
				}
			}
		}
		$dc_price=(int)$_ord->dc_price;
		$salemoney=0;
		$salereserve=0;
		if($dc_price<>0) {
			if($dc_price>0) $salereserve=$dc_price;
			else $salemoney=-$dc_price;
			if(strlen($_ord->ordercode)==20 && substr($_ord->ordercode,-1)!="X") {
				$sql = "SELECT b.group_name FROM tblmember a, tblmembergroup b ";
				$sql.= "WHERE a.id='".$_ord->id."' AND b.group_code=a.group_code AND MID(b.group_code,1,1)!='M' ";
				$result=mysql_query($sql,get_db_conn());
				if($row=mysql_fetch_object($result)) {
					$group_name=$row->group_name;
				}
				mysql_free_result($result);
			}
			echo "<tr bgcolor=#FFFFFF>\n";
			echo "	<td align=center style=\"padding:7,5;font-size:8pt;line-height:10pt\">�׷�����/����</td>\n";
			echo "	<td style=\"padding:7,5;font-size:8pt;line-height:10pt\">�׷�ȸ�� ����/���� : ".$group_name."</td>\n";
			echo "	<td align=right style=\"padding:7,5;font-size:8pt;line-height:10pt\">".($salemoney>0?"-".number_format($salemoney)."��":"&nbsp;")."</td>\n";
			echo "	<td align=right style=\"padding:7,5;font-size:8pt;line-height:10pt\">".($salereserve>0?"+ ".number_format($salereserve)."��":"&nbsp;")."</td>\n";
			echo "	<td align=center style=\"padding:7,5;font-size:8pt;line-height:10pt\">�ֹ��� ��ü ����</td>\n";
			echo "</tr>\n";
			$in_reserve+=$salereserve;
		}

		if($_ord->reserve>0) {
			echo "<tr bgcolor=#FFFFFF>\n";
			echo "	<td align=center style=\"padding:7,5;font-size:8pt;line-height:10pt\">������ ���</td>\n";
			echo "	<td style=\"padding:7,5;font-size:8pt;line-height:10pt\">������ ������ ".number_format($_ord->reserve)."�� ���</td>\n";
			echo "	<td align=right style=\"padding:7,5;font-size:8pt;line-height:10pt\">-".number_format($_ord->reserve)."��</td>\n";
			echo "	<td align=right style=\"padding:7,5;font-size:8pt;line-height:10pt\">&nbsp;</td>\n";
			echo "	<td align=center style=\"padding:7,5;font-size:8pt;line-height:10pt\">�ֹ��� ��ü ����</td>\n";
			echo "</tr>\n";
		}
?>
		</table>
		</td>
	</tr>
	<tr><td height=10></td></tr>
<?
	if($_ord->price>0) {
?>
	<tr>
		<td align=center style="font-size:10pt;color:#EE1A02">
		<B>���� �հ�ݾ� : <?=number_format($_ord->price)?>��</B>
<?
		if($in_reserve>0) {
			echo " &nbsp; <font style=\"color:blue;font-size:9pt\">(�����ݾ� : <B>".number_format($in_reserve)."</B>��)</font>";
		}
?>
		</td>
	</tr>
	<tr><td height=10></td></tr>
	<tr>
		<td align=center>
		<table border=0 cellpadding=5 cellspacing=1 width=100% bgcolor=#E7E7E7 style="table-layout:fixed">
		<col width=110></col>
		<col width=></col>
		<?if(strlen($ordercode)==21 && substr($ordercode,-1)=="X"){?>
		<tr>
			<td align=center bgcolor=#F5F5F5 style="padding:7,10">�ֹ�Ȯ�ι�ȣ</td>
			<td bgcolor=#ffffff style="padding:7,10"><b><?=substr($_ord->id,1,6)?></td>
		</tr>
		<?}?>
		<tr>
			<td align=center bgcolor=#F5F5F5 style="padding:7,10">�ֹ�����</td>
			<td bgcolor=#ffffff style="padding:7,10"><?=substr($ordercode,0,4).".".substr($ordercode,4,2).".".substr($ordercode,6,2)?></td>
		</tr>
		<? if($_ord->gift=='1') { ?>
		<tr>
			<td align=center bgcolor=#F5F5F5 style="padding:7,10">�޴º�</td>
			<td bgcolor=#ffffff style="padding:7,10"><?=$_ord->receiver_name?></td>
		</tr>
		<tr>
			<td align=center bgcolor=#F5F5F5 style="padding:7,10">�޴º� �̸���</td>
			<td bgcolor=#ffffff style="padding:7,10"><?=ereg_replace("�ּ� :","",$_ord->receiver_addr)?></td>
		</tr>
		<tr>
			<td align=center bgcolor=#F5F5F5 style="padding:7,10">�޴º� ����ó</td>
			<td bgcolor=#ffffff style="padding:7,10"><?=$_ord->receiver_tel1?></td>
		</tr>
		<? } ?>

		<tr>
			<td align=center bgcolor=#F5F5F5 style="padding:7,10">�������</td>
			<td bgcolor=#ffffff style="padding:7,10">
<?
			if (preg_match("/^(B|O|Q){1}/",$_ord->paymethod)) {	//������, �������, ������� ����ũ��
				if($_ord->paymethod=="B") echo "<font color=#FF5D00>�������Ա�</font>\n";
				else if(substr($_ord->paymethod,0,1)=="O") echo "<font color=#FF5D00>�������</font>\n";
				else echo "�Ÿź�ȣ - �������";

				if(!preg_match("/^(C|D)$/",$_ord->deli_gbn) || $_ord->paymethod=="B") echo "�� ".$_ord->pay_data." ��";
				else echo "�� ���� ��� ��";

				if (strlen($_ord->bank_date)>=12) {
					echo "</td>\n</tr>\n";
					echo "<tr>\n";
					echo "	<td align=center bgcolor=#F5F5F5 style=\"padding:7,10\">�Ա�Ȯ��</td>\n";
					echo "	<td bgcolor=#ffffff style=\"padding:7,10\"><font color=red>".substr($_ord->bank_date,0,4)."/".substr($_ord->bank_date,4,2)."/".substr($_ord->bank_date,6,2)." (".substr($_ord->bank_date,8,2).":".substr($_ord->bank_date,10,2).")</font>";
				} else if(strlen($_ord->bank_date)==9) {
					echo "</td>\n</tr>\n";
					echo "<tr>\n";
					echo "	<td align=center bgcolor=#F5F5F5 style=\"padding:7,10\">�Ա�Ȯ��</td>\n";
					echo "	<td bgcolor=#ffffff style=\"padding:7,10\">ȯ��";
				}
			} else if(substr($_ord->paymethod,0,1)=="M") {	//�ڵ��� ����
				echo "�ڵ��� ������ ";
				if ($_ord->pay_flag=="0000") {
					if($_ord->pay_admin_proc=="C") echo "�� <font color=red>������� �Ϸ�</font> ��";
					else echo "<font color=red>������ ���������� �̷�������ϴ�.</font>";
				}
				else echo "������ ���еǾ����ϴ�.";
				echo " ��";
			} else if(substr($_ord->paymethod,0,1)=="P") {	//�Ÿź�ȣ �ſ�ī��
				echo "�Ÿź�ȣ - �ſ�ī��";
				if($_ord->pay_flag=="0000") {
					if($_ord->pay_admin_proc=="C") echo "�� <font color=red>ī����� ��ҿϷ�</font> ��";
					else if($_ord->pay_admin_proc=="Y") echo "�� ī�� ���� �Ϸ� * �����մϴ�. : ���ι�ȣ ".$_ord->pay_auth_no." ��";
				}
				else echo "�� ".$_ord->pay_data." ��";
			} else if (substr($_ord->paymethod,0,1)=="C") {	//�Ϲݽſ�ī��
				echo "<font color=#FF5D00>�ſ�ī��</font>\n";
				if($_ord->pay_flag=="0000") {
					if($_ord->pay_admin_proc=="C") echo "�� <font color=red>ī����� ��ҿϷ�</font> ��";
					else if($_ord->pay_admin_proc=="Y") echo "�� ī�� ���� �Ϸ� * �����մϴ�. : ���ι�ȣ ".$_ord->pay_auth_no." ��";
				}
				else echo "�� ".$_ord->pay_data." ��";
			} else if (substr($_ord->paymethod,0,1)=="V") {
				echo "�ǽð� ������ü : ";
				if ($_ord->pay_flag=="0000") {
					if($_ord->pay_admin_proc=="C") echo "�� <font color=005000> [ȯ��]</font> ��";
					else echo "<font color=red>".$_ord->pay_data."</font>";
				}
				else echo "������ ���еǾ����ϴ�.";
			}
?>
			</td>
		</tr>
		<tr>
			<td align=center bgcolor=#F5F5F5 style="padding:7,10">�����ݾ�</td>
			<td bgcolor=#ffffff style="padding:7,10"><font color=#0000FF><b><?=number_format($_ord->price)."��</b>".($_ord->reserve>0?"(������ ".number_format($_ord->reserve)."�� ����)":"")?></font></td>
		</tr>
<?
		$order_msg=explode("[MEMO]",$_ord->order_msg);
		if(strlen($order_msg[0])>0) {
?>
		<tr>
			<td align=center bgcolor=#F5F5F5 style="padding:7,10">���޸�</td>
			<td bgcolor=#ffffff style="padding:7,10">
			<?=nl2br($order_msg[0])?>
			</td>
		</tr>
		<?}?>

		<?if(strlen($order_msg[2])>0) {?>
		<tr>
			<td align=center bgcolor=#F5F5F5 style="padding:7,10">�����޸�</td>
			<td bgcolor=#ffffff style="padding:7,10">
			<?=nl2br($order_msg[2])?>
			</td>
		</tr>
		<?}?>
		</table>
		</td>
	</tr>
<?
	}
?>
	<tr><td height=10></td></tr>
	<tr>
		<td align=center>
		<A HREF="javascript:window.close()"><img src="<?=$Dir?>images/common/orderdetailpop_close.gif" align=absmiddle border=0></A>
<?
		if($print!="OK") {
			if (
			   ($_data->ordercancel==0 && ($_ord->deli_gbn=="S" || $_ord->deli_gbn=="N") && getDeligbn("S|N",true))/*�ֹ���ۿϷ� ���� ��Ұ� �����ϸ� �߼��غ�� �ֹ��� �Ǵ� ��ó���� �ֹ����� ��� ����*/
			|| ($_data->ordercancel==2 && $_ord->deli_gbn=="N" && getDeligbn("N",true))/*�ֹ�����غ� ������ ��Ұ� �����ϸ� ��ó���� �ֹ����� ��� ����*/
			|| ($_data->ordercancel==1 && preg_match("/^(B){1}/", $_ord->paymethod) && strlen($_ord->bank_date)<12 && $_ord->deli_gbn=="N" && getDeligbn("N",true)) /*�ֹ������Ϸ� ������ ��Ұ� �����ϸ� �������Ա����� �Ա��� ��ó���� �ֹ����� ��� ����*/
			) {
				if(!preg_match("/^(Q){1}/", $_ord->paymethod)) {
					echo "<a href=\"javascript:order_cancel('".$_ord->tempkey."', '".$_ord->ordercode."')\" onMouseOver=\"window.status='�ֹ����';return true;\"><img src=\"".$Dir."images/common/orderdetailpop_ordercancel.gif\" align=absmiddle border=0></a>\n";
				}
			} else if($_data->ordercancel==1 && (($_ord->paymethod=="B" && strlen($_ord->bank_date)>=12) || ( preg_match("/^(C|P){1}/", $_ord->paymethod) && strcmp($_ord->pay_flag,"0000")==0)) && $_ord->deli_gbn=="N" && getDeligbn("N",true)){
				if(strlen($_data->nocancel_msg)==0) $_data->nocancel_msg="�ֹ���Ұ� ���� �ʽ��ϴ�.\\n���θ��� �����ϼ���.";
				echo "<a href=\"javascript:alert('".$_data->nocancel_msg."')\"><img src=\"".$Dir."images/common/orderdetailpop_ordercancel.gif\" align=absmiddle border=0></a>\n";
			}

			if($_ord->del_gbn!="A" && $_ord->del_gbn!="Y" && getDeligbn("A|Y",false)
			&& !(substr($_ord->paymethod,0,1)=="Q" && strlen($_ord->bank_date)>=12 && $_ord->deli_gbn!="C")  //�Ÿź�ȣ ��������̰� �Ա�Ȯ�εǰ� �ֹ���Ұ� �ƴѰ��
			&& !(substr($_ord->paymethod,0,1)=="P" && $_ord->pay_flag=="0000" && $_ord->deli_gbn!="C")      //�Ÿź�ȣ �ſ�ī���̰� ī�强�� �ֹ���Ұ� �ƴѰ��
			&& strlen($_ShopInfo->getMemid())>0 /* ��ȸ���� ��������ȵǰ� */) {
				echo "<a href=\"javascript:order_del('".$_ord->tempkey."', '".$_ord->ordercode."')\" onMouseOver=\"window.status='�������';return true;\"><img src=\"".$Dir."images/common/orderdetailpop_del.gif\" align=absmiddle border=0></a>\n";
			}

			if(preg_match("/^(B|O|Q){1}/", $_ord->paymethod) && $_ord->deli_gbn!="C") {
				if($_data->tax_type!="N" && $_ord->price>=1) {
					echo "<a href=\"javascript:get_taxsave('".$_ord->ordercode."')\" onMouseOver=\"window.status='���ݿ�����';return true;\"><img src=\"".$Dir."images/common/orderdetailpop_cashbill.gif\" align=absmiddle border=0></a>\n";
				}
			}

			if(((substr($_ord->paymethod,0,1)=="P" && $_ord->pay_admin_proc=="Y") || (substr($_ord->paymethod,0,1)=="Q" && $_ord->pay_flag=="0000")) && !preg_match("/^(Y|C)$/",$_ord->escrow_result) && $_ord->deli_gbn!="C") {
				/*
				����ũ�� ������ ������ �´�.
				*/
				$pgid_info="";
				$pg_type="";
				switch (substr($_ord->paymethod,0,1)) {
					case "B":
						break;
					case "V":
						$pgid_info=GetEscrowType($_data->trans_id);
						$pg_type=$pgid_info["PG"];
						break;
					case "O":
						$pgid_info=GetEscrowType($_data->virtual_id);
						$pg_type=$pgid_info["PG"];
						break;
					case "Q":
						$pgid_info=GetEscrowType($_data->escrow_id);
						$pg_type=$pgid_info["PG"];
						break;
					case "C":
						$pgid_info=GetEscrowType($_data->card_id);
						$pg_type=$pgid_info["PG"];
						break;
					case "P":
						$pgid_info=GetEscrowType($_data->card_id);
						$pg_type=$pgid_info["PG"];
						break;
					case "M":
						$pgid_info=GetEscrowType($_data->mobile_id);
						$pg_type=$pgid_info["PG"];
						break;
				}
				$pg_type=trim($pg_type);

				// ���ó���� �Ǿ�߸� �Ÿź�ȣ
				if ($_ord->deli_gbn=="Y") {
					echo "<a href=\"javascript:escrow_ok('".$_ord->ordercode."')\" onMouseOver=\"window.status='����Ȯ��';return true;\"><img src=\"".$Dir."images/common/orderdetailpop_okorder.gif\" align=absmiddle border=0></a>\n";
				} else if (substr($_ord->paymethod,0,1)=="Q" && !preg_match("/^(D|E|H)$/", $_ord->deli_gbn) && getDeligbn("D|E|H",false)) {
					#<!--- ��� ( ��� & ȯ�� �Ѳ����� ó��) -->
					echo "<a href=\"javascript:escrow_cancel('".$_ord->tempkey."','".$_ord->ordercode."','".$_ord->bank_date."')\" onMouseOver=\"window.status='�������';return true;\"><img src=\"".$Dir."images/common/orderdetailpop_ordercancel.gif\" align=absmiddle border=0></a>\n";
				}
			}

			// ######### ����ǰ�� �������� ���� �ֹ��� ��� ����ǰ�� ������ �� �ֵ��� ����
			if (($_ord->paymethod=="B" || (preg_match("/^(V|O|Q|C|P|M){1}/", $_ord->paymethod) && strcmp($_ord->pay_flag,"0000")==0)) && $_ord->deli_gbn=="N" && getDeligbn("N",true) && $gift_check=="N" && $gift_type[3]=="Y") {
				if ($gift_type[2]=="A" || strlen($gift_type[2])==0 || ($gift_type[2]=="B" && $_ord->paymethod=="B")) {
					if (($gift_type[0]=="M" && strlen($_ShopInfo->getMemid())>0) || $gift_type[0]=="C") { // ȸ������, ��ȸ��+ȸ��
						$sql = "SELECT COUNT(*) as gift_cnt FROM tblgiftinfo ";
						if($gift_type[1]=="N") {
							$sql.= "WHERE gift_startprice<=".$gift_price." AND gift_endprice>".$gift_price." ";
						} else  {
							$sql.= "WHERE gift_startprice<=".$gift_price." ";
						}
						$sql.= "AND (gift_quantity is NULL OR gift_quantity>0) ";
						$result=mysql_query($sql,get_db_conn());
						$row=mysql_fetch_object($result);
						$gift_cnt=$row->gift_cnt;
						mysql_free_result($result);
						if ($gift_cnt>0) {
							$gift_body = "<a href=\"javascript:getGift()\"><img src='".$Dir."images/common/orderdetailpop_gift.gif' border=0 align=absmiddle></a>\n";
							$gift_body.= "<form name=giftform method=post action=\"".$Dir.FrontDir."gift_choice.php\" target=\"gift_popwin\">\n";
							$gift_body.= "<input type=hidden name=gift_price value=\"".$gift_price."\">\n";
							$gift_body.= "<input type=hidden name=ordercode value=\"".$_ord->ordercode."\">\n";
							$gift_body.= "<input type=hidden name=gift_mode value=\"orderdetailpop\">\n";
							$gift_body.= "<input type=hidden name=gift_tempkey value=\"".$gift_tempkey."\">\n";
							$gift_body.= "</form>\n";
							$gift_body.= "<script language='javascript'>\n";
							$gift_body.= "function getGift() {\n";
							$gift_body.= "	gift_popwin = window.open('about:blank','gift_popwin','width=700,height=600,scrollbars=yes');\n";
							$gift_body.= "	document.giftform.target='gift_popwin';\n";
							$gift_body.= "	document.giftform.submit();\n";
							$gift_body.= "	gift_popwin.focus();\n";
							$gift_body.= "}\n";
							$gift_body.= "</script>\n";
							echo $gift_body;
						}
					}
				}
			}
		}
?>
		</td>
	</tr>
	</table>
	</td>
</tr>
</table>
<form name=form1 action="orderdetailpop.php" method=post>
<input type=hidden name=tempkey>
<input type=hidden name=ordercode>
<input type=hidden name=type>
<input type=hidden name=ordercodeid value="<?=$ordercodeid?>">
<input type=hidden name=ordername value="<?=$ordername?>">
</form>
<form name=taxsaveform method=post action="<?=$Dir.FrontDir?>taxsave.php" target=taxsavepop>
<input type=hidden name=ordercode>
<input type=hidden name=productname value="<?=urlencode(titleCut(30,htmlspecialchars(strip_tags($taxsaveprname),ENT_QUOTES)))?>">
</form>
<form name=escrowform action="<?=$Dir?>paygate/okescrow.php" method=post>
<input type=hidden name=ordercode value="">
<?if($pg_type=="D") {?>
<input type=hidden name=sendtype value="">
<? } else { ?>
<input type=hidden name=sitecd value="<?=urlencode($pgid_info["ID"])?>">
<input type=hidden name=sitekey value="<?=urlencode($pgid_info["KEY"])?>">
<? } ?>
<input type=hidden name=return_host value="<?=urlencode(getenv("HTTP_HOST"))?>">
<input type=hidden name=return_script value="<?=urlencode(str_replace(getenv("HTTP_HOST"),"",$_ShopInfo->getShopurl()).FrontDir."orderdetailpop.php")?>">
<input type=hidden name=return_data value="<?=urlencode("type=okescrow&ordercode=".$ordercode)?>">
</form>

<form name=vform action="<?=$Dir?>paygate/set_bank_account.php" method=post target="baccountpop">
<input type=hidden name=ordercode value="<?=$ordercode?>">
</form>

<form name=form3 method=post>
<input type=hidden name=ordercode value="<?=$ordercode?>">
</form>

<?if($pg_type=="B"){?>
<SCRIPT language=JavaScript src="http://pgweb.dacom.net/js/DACOMEscrow.js"></SCRIPT>
<?}?>

<SCRIPT LANGUAGE="JavaScript">
<!--
function escrow_ok(ordercode) {	//����ũ�� ���Ű���
	if(confirm("��� �Ϸ�� �Ÿź�ȣ �����ǿ� ���ؼ� ���Ű���/���Ű��� ó�� �Ͻðڽ��ϱ�?")) {
<?if($pg_type=="B"){?>
		var resdata=checkDacomESC('<?=$pgid_info["ID"]?>',ordercode,'');
		if(resdata=="0000") {
			document.form3.submit();
		}
<?}else if($pg_type=="D"){?>
		document.escrowform.sendtype.value="";
		document.escrowform.ordercode.value=ordercode;
		window.open("about:blank","okescrowpop","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizeble=no,copyhistory=no,width=100,height=100");
		document.escrowform.target="okescrowpop";
		document.escrowform.submit();
<?}else{?>
		document.escrowform.ordercode.value=ordercode;
		window.open("about:blank","okescrowpop","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizeble=no,copyhistory=no,width=100,height=100");
		document.escrowform.target="okescrowpop";
		document.escrowform.submit();
<?}?>
	}
}
function escrow_cancel(tempkey,ordercode,bank_date) {	//����ũ�� ������� (�������)
	if(bank_date.length>=12) {
<?if($pg_type=="D"){?>
		if(confirm("�Ÿź�ȣ �ֹ����� ���ؼ� ȯ��ó�� ��û�� �Ͻðڽ��ϱ�?")) {
			document.escrowform.sendtype.value="CNCL";
			document.escrowform.ordercode.value=ordercode;
			window.open("about:blank","okescrowpop","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizeble=no,copyhistory=no,width=100,height=100");
			document.escrowform.target="okescrowpop";
			document.escrowform.submit();
			return;
		}
<?}else{?>
		if(!confirm("ȯ�Ұ��������� �Է��Ͻ÷��� [Ȯ��]�� Ŭ���Ͻð�,\n\nȯ�Ұ��������� �Է��ϼ̴ٸ� [���]�� Ŭ���ϼ���.")) {
			if(confirm("�Ÿź�ȣ �ֹ����� ���ؼ� ȯ��ó�� ��û�� �Ͻðڽ��ϱ�?")) {
				document.form1.tempkey.value=tempkey;
				document.form1.ordercode.value=ordercode;
				document.form1.type.value="cancel";
				document.form1.submit();
			}
			return;
		}
<?} ?>
	} else {
		if(confirm("���Ա� �ֹ��� ���ؼ� �ֹ���� �Ͻðڽ��ϱ�?")) {
			document.form1.tempkey.value=tempkey;
			document.form1.ordercode.value=ordercode;
			document.form1.type.value="cancel";
			document.form1.submit();
		}
		return;
	}
	window.open("about:blank","baccountpop","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizeble=no,copyhistory=no,width=100,height=100");
	document.vform.submit();
}
//-->
</SCRIPT>


</center>

<? if($print=="OK") echo "<script>print();</script>";?>

<?=$onload?>

</body>
</html>
