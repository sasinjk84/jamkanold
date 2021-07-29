<?
session_start();
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
INCLUDE ("access.php");

include($Dir.'lib/ext/reservation_func.php'); // ���� ��ǰ

####################### ������ ���ٱ��� check ###############
$PageCode = "or-1";
$MenuCode = "order";
if (!$_usersession->isAllowedTask($PageCode)) {
	INCLUDE ("AccessDeny.inc.php");
	exit;
}
#########################################################

$imagepath=$Dir.DataDir."shopimages/product/";

function getDeligbn($arrdeli,$strdeli,$true=true) {
	$tempdeli=$arrdeli;
	$res=true;
	while(@list($key,$val)=@each($tempdeli)) {
		if($true==true) {
			if(!preg_match("/^(".$strdeli.")$/", $val)) {
				$res=false;
				break;
			}
		} else {
			if(preg_match("/^(".$strdeli.")$/", $val)) {
				$res=false;
				break;
			}
		}
	}
	return $res;
}


$id = $_GET['id'];

if($id) {
	$_POST['search_start']=$_POST['search_end']='';
	$_POST['s_check'] = "mi";
	$_POST['search'] = $id;
}

$CurrentTime = time();
$period[0] = date("Y-m-d",$CurrentTime);
$period[1] = date("Y-m-d",$CurrentTime-(60*60*24*7));
$period[2] = date("Y-m-d",$CurrentTime-(60*60*24*14));
$period[3] = date("Y-m-d",mktime(0,0,0,date("m")-1,date("d"),date("Y")));
$period[4] = date("Y-m-d",mktime(0,0,0,date("m")-6,date("d"),date("Y")));
$period[5] = date("Y-m-d",mktime(0,0,0,date("m"),date("d"),date("Y")-1));

$orderby=$_POST["orderby"];
if(strlen($orderby)==0) $orderby="DESC";

$paystate=$_POST["paystate"];
$deli_gbn=$_POST["deli_gbn"];
$s_type=$_POST["s_type"];
//$s_check=$_POST["s_check"];
$search=$_POST["search"];
///////
$searchtype=$_POST["searchtype"];
if(strlen($searchtype)==0) $searchtype="0";
if(!preg_match("/^(0|1)$/", $searchtype)) {
	$searchtype="0";
}

$s_check=$_POST["s_check"];
if(strlen($s_check)==0) $s_check="A";
if(!preg_match("/^(A|B|C|D|E|F|G|H|I)$/", $s_check)) {
	$s_check="A";
}

$searchprice=$_POST["searchprice"];
$gong_gbn=$_POST["gong_gbn"];
if(!preg_match("/^(Y|N)$/", $gong_gbn)) {
	$gong_gbn="N";
}


$gift_gbn=(preg_match("/^(A|G)$/", $_POST["gift_gbn"]) ? $_POST["gift_gbn"] : "A" );

$search_start=$_POST["search_start"];
$search_end=$_POST["search_end"];
$vperiod=(int)$_POST["vperiod"];


$devices=( empty($_POST["devices"]) ? "All" : $_POST["devices"] );

$search_start=$search_start?$search_start:$period[3];
$search_end=$search_end?$search_end:date("Y-m-d",$CurrentTime);
$search_s=$search_start?str_replace("-","",$search_start."000000"):str_replace("-","",$period[3]."000000");
$search_e=$search_end?str_replace("-","",$search_end."235959"):date("Ymd",$CurrentTime)."235959";


if(!isset($_POST['search_start'])) {
	$search_start=$search_start?$search_start:$period[3];
	$search_s=$search_start?str_replace("-","",$search_start."000000"):str_replace("-","",$period[3]."000000");
}
else {
	$search_start = $_POST["search_start"];;
	$search_s=$search_start?str_replace("-","",$search_start."000000"):'';
}

if(!isset($_POST['search_end'])) {
	$search_end=$search_end?$search_end:date("Y-m-d",$CurrentTime);
	$search_e=$search_end?str_replace("-","",$search_end."235959"):date("Ymd",$CurrentTime)."235959";
}
else {
	$search_end = $_POST["search_end"];
	$search_e=$search_end?str_replace("-","",$search_end."235959"):'';
}


$tempstart = explode("-",$search_start);
$tempend = explode("-",$search_end);
@$termday = (mktime(0,0,0,$tempend[1],$tempend[2],$tempend[0])-mktime(0,0,0,$tempstart[1],$tempstart[2],$tempstart[0]))/86400;
/*
if ($termday>366) {
	echo "<script>alert('�˻��Ⱓ�� 1���� �ʰ��� �� �����ϴ�.');location='".$_SERVER[PHP_SELF]."';</script>";
	exit;
}
*/

$qry_from = "tblorderinfo a";
/*
if($search_s) {
	if(substr($search_s,0,8)==substr($search_e,0,8)) {
		$qry.= "WHERE a.ordercode LIKE '".substr($search_s,0,8)."%' ";
	} else {
		$qry.= "WHERE a.ordercode>='".$search_s."' AND a.ordercode <='".$search_e."' ";
	}
}else $qry.="WHERE a.ordercode!='' ";

if(strlen($s_type)>0) {
	$qry.= " && gift='{$s_type}' ";
}
if(strlen($deli_gbn)>0)	{
	if($deli_gbn=='YA' || $deli_gbn=='YB' || $deli_gbn=='YC') {
		$qry.= "AND a.status='".$deli_gbn."' ";
	}
	else $qry.= "AND a.deli_gbn='".$deli_gbn."' ";
}
if($paystate=="Y") {		//�Ա�
	$qry.= "AND ((MID(a.paymethod,1,1) IN ('B','O','Q') AND LENGTH(a.bank_date)=14) OR (MID(a.paymethod,1,1) IN ('C','P','M','V') AND a.pay_admin_proc!='C' AND a.pay_flag='0000')) ";
} else if($paystate=="B") {	//���Ա�
	$qry.= "AND ((MID(a.paymethod,1,1) IN ('B','O','Q') AND (a.bank_date IS NULL OR a.bank_date='')) OR (MID(a.paymethod,1,1) IN ('C','P','M','V') AND a.pay_flag!='0000' AND a.pay_admin_proc='C')) ";
} else if($paystate=="C") {	//ȯ��
	$qry.= "AND ((MID(a.paymethod,1,1) IN ('B','O','Q') AND LENGTH(a.bank_date)=9) OR (MID(a.paymethod,1,1) IN ('C','P','M','V') AND a.pay_flag='0000' AND a.pay_admin_proc='C')) ";
}
if(strlen($search)>0) {
	if($s_check=="cd") $qry.= "AND a.ordercode='".$search."' ";
	else if($s_check=="pn") {
		$qry.= "AND a.ordercode=b.ordercode ";
		$qry.= "AND NOT (b.productcode LIKE 'COU%' OR b.productcode LIKE '999999%') ";
		$qry.= "AND b.productname LIKE '%".$search."%' ";
		$qry_from.= ",tblorderproduct b";
	}
	else if($s_check=="mn") $qry.= "AND a.sender_name like '%".$search."%' ";
	else if($s_check=="mi") $qry.= "AND a.id='".$search."' ";
	else if($s_check=="cn") $qry.= "AND a.id LIKE 'X".$search."%' ";
}*/
//�κ�ȯ�� ��ȸ
$part_cancel = $_POST["part_cancel"];


$qry = " WHERE 1=1 ";
if($searchtype=="1") {	//�ֹ��ݾ����� �˻�
	$qry.= "AND ordercode>'".date("Ymd",($curtime-(60*60*24*180)))."' AND paymethod='B' ";
	$qry.= "AND deli_gbn='N' AND price='".$searchprice."' ";
}else{
	if($search_s) {
		if(substr($search_s,0,8)==substr($search_e,0,8)) {
			$qry.= "and a.ordercode LIKE '".substr($search_s,0,8)."%' ";
		} else {
			$qry.= "and a.ordercode>='".$search_s."' AND a.ordercode <='".$search_e."' ";
		}
	}else $qry.="and a.ordercode!='' ";

	if(strlen($s_type)>0) {
		$qry.= " && gift='{$s_type}' ";
	}
	if(strlen($deli_gbn)>0)	{
		if($deli_gbn=='YA' || $deli_gbn=='YB' || $deli_gbn=='YC') {
			$qry.= "AND a.status='".$deli_gbn."' ";
		}
		else $qry.= "AND a.deli_gbn='".$deli_gbn."' ";
	}

	if($paystate=="Y") {		//�Ա�
		$qry.= "AND ((MID(a.paymethod,1,1) IN ('B','O','Q') AND LENGTH(a.bank_date)=14) OR (MID(a.paymethod,1,1) IN ('C','P','M','V') AND a.pay_admin_proc!='C' AND a.pay_flag='0000')) ";
	} else if($paystate=="B") {	//���Ա�
		$qry.= "AND ((MID(a.paymethod,1,1) IN ('B','O','Q') AND (a.bank_date IS NULL OR a.bank_date='')) OR (MID(a.paymethod,1,1) IN ('C','P','M','V') AND a.pay_flag!='0000' AND a.pay_admin_proc='C')) ";
	} else if($paystate=="C") {	//ȯ��
		$qry.= "AND ((MID(a.paymethod,1,1) IN ('B','O','Q') AND LENGTH(a.bank_date)=9) OR (MID(a.paymethod,1,1) IN ('C','P','M','V') AND a.pay_flag='0000' AND a.pay_admin_proc='C')) ";
	}

	if(strlen($search) > 0){
		switch($s_check) {
			case "A":	//�ֹ���
				if(strlen($search)>=6) {
					$qry.= "AND sender_name = '".$search."' ";
				} else {
					$qry.= "AND ordercode>'".date("Ymd",($curtime-(60*60*24*180)))."' ";
					$qry.= "AND sender_name LIKE '".$search."%' ";
				}
				break;
			case "B":	//������
				if(strlen($search)>=6) {
					$qry.= "AND receiver_name = '".$search."' ";
				} else {
					$qry.= "AND ordercode>'".date("Ymd",($curtime-(60*60*24*180)))."' ";
					$qry.= "AND receiver_name LIKE '".$search."%' ";
				}
				break;
			case "C":	//���̵�
				$qry.= "AND id='".$search."' ";
				break;
			case "D":	//�ֹ���ȣ
				$qry.= "AND ordercode>'".date("Ymd",($curtime-(60*60*24*180)))."' ";
				$qry.= "AND id LIKE 'X".$search."%' ";
				break;
			case "E":	//�̸���
				$qry.= "AND ordercode>'".date("Ymd",($curtime-(60*60*24*30)))."' ";
				$qry.= "AND sender_email LIKE '".$search."%' ";
				break;
			case "F":	//�ּ�
				$qry.= "AND ordercode>'".date("Ymd",($curtime-(60*60*24*30)))."' ";
				$qry.= "AND receiver_addr LIKE '%".$search."%' ";
				break;
			case "G":	//��ȭ��ȣ
				$qry.= "AND ordercode>'".date("Ymd",($curtime-(60*60*24*30)))."' ";
				$qry.= "AND sender_tel LIKE '%".$search."%' ";
				break;
			case "H":	//�Ա��ڸ�
				$qry.= "AND ordercode>'".date("Ymd",($curtime-(60*60*24*30)))."' ";
				$qry.= "AND order_msg LIKE '%�Ա��� : ".$search."%' ";
				break;
			case "I":	//�����ȣ
				$qry.= "AND ordercode>'".date("Ymd",($curtime-(60*60*24*10)))."' ";
				$qry.= "AND deli_num LIKE '".$search."%' ";
				break;
		}
	}

	if ($part_cancel) {
		$qry.= 	"and  ( select count(*) from tblorderproduct where ordercode=a.ordercode and status = '".$part_cancel."')>0 ";
	}
}


if( $devices != "All" ) {
	$qry.= "AND device = '".$devices."' ";
}


if( $gift_gbn == "G" ) {
	$qry.= "AND ( a.gift = '1' OR a.gift = '2' )";
}


if($id) $setup[page_num] = 10000;
else $setup[page_num] = 10;

if( empty($_SESSION[paging_list_num]) ) {
	$_SESSION[paging_list_num] = 10;
}


if( strlen($_POST['paging_list_num_sel']) > 0 ) {
	$_SESSION[paging_list_num] = $_POST['paging_list_num_sel'];
}

$setup[list_num] = $_SESSION[paging_list_num];

$block=$_REQUEST["block"];
$gotopage=$_REQUEST["gotopage"];
if ($block != "") {
	$nowblock = $block;
	$curpage  = $block * $setup[page_num] + $gotopage;
} else {
	$nowblock = 0;
}

if (($gotopage == "") || ($gotopage == 0)) {
	$gotopage = 1;
}

if($type=="delete" && strlen($ordercodes)>0) {	//�ֹ��� ����
	$ordercode=ereg_replace(",","','",$ordercodes);
	mysql_query("INSERT INTO tblorderinfotemp SELECT * FROM tblorderinfo WHERE ordercode IN ('".$ordercode."')",get_db_conn());
	mysql_query("INSERT INTO tblorderproducttemp SELECT * FROM tblorderproduct WHERE ordercode IN ('".$ordercode."')",get_db_conn());
	mysql_query("INSERT INTO tblorderoptiontemp SELECT * FROM tblorderoption WHERE ordercode IN ('".$ordercode."')",get_db_conn());

	mysql_query("DELETE FROM tblorderinfo WHERE ordercode IN ('".$ordercode."')",get_db_conn());
	mysql_query("DELETE FROM tblorderproduct WHERE ordercode IN ('".$ordercode."')",get_db_conn());
	mysql_query("DELETE FROM tblorderoption WHERE ordercode IN ('".$ordercode."')",get_db_conn());

	$log_content = "## �ֹ����� ���� ## - �ֹ���ȣ : ".$ordercodes;
	ShopManagerLog($_ShopInfo->getId(),$connect_ip,$log_content);
	$onload="<script>alert('�����Ͻ� �ֹ������� �����Ͽ����ϴ�.');</script>";
}

$t_count=0;
$t_price=0;
$sql = "SELECT COUNT(DISTINCT(a.ordercode)) as t_count FROM ".$qry_from." ".$qry." ";
$result = mysql_query($sql,get_db_conn());
$row=mysql_fetch_object($result);
$t_count=$row->t_count;
mysql_free_result($result);
$pagecount = (($t_count - 1) / $setup[list_num]) + 1;


$sql = "SELECT vendercnt FROM tblshopcount ";
$result=mysql_query($sql,get_db_conn());
$row=mysql_fetch_object($result);
$vendercnt=$row->vendercnt;
mysql_free_result($result);

if($vendercnt>0){
	$venderlist=array();
	$sql = "SELECT vender,id,com_name,delflag FROM tblvenderinfo ORDER BY id ASC ";
	$result=mysql_query($sql,get_db_conn());
	while($row=mysql_fetch_object($result)) {
		$venderlist[$row->vender]=$row;
	}
	mysql_free_result($result);
}




// ����Ʈ���� �Ա�ó��


?>

<? include "header.php"; ?>

<script type="text/javascript" src="lib.js.php"></script>
<script type="text/javascript" src="calendar.js.php"></script>
<script language="JavaScript">

// ����Ʈ���� �Ա�ó��
function orderBankOK ( OrderCode ) {
	if( confirm( " \""+OrderCode+"\" �ֹ���\n ���� �Ա� �Ϸ� ó�� �Ͻðڽ��ϱ�?\n\n(�Ա�Ȯ�� SMS�� �ڵ� �߼� �˴ϴ�.)") ) {
		processFrm.location.href="order_list.bankOK.php?ordercode="+OrderCode;
	}
}

// ����Ʈ���� �ϰ� �Ա�ó��
function orderBankOkChkAll () {
	if( confirm( "���õ� ��� �ֹ��� ���� �Ա� �Ϸ� ó�� �Ͻðڽ��ϱ�?") ) {
		var a = 0 ;
		var OrderCodeList = "";
		for(i=1;i<document.form2.chkordercode.length;i++) {
			if(document.form2.chkordercode[i].checked==true) {
				var OrderCode = document.form2.chkordercode[i].value.substring(1);
				try {
					OrderCodeList += OrderCode+",";
				} catch ( e ) { }
				a++;
			}
		}

		if( a == 0 ) {
			alert("�ԱݿϷ� �� �ֹ��� �����ϼ���!");
			return;
		} else {
			processFrm.location.href="order_list.bankOK.php?ordercode="+OrderCodeList;
		}

	}
}


// ����Ʈ���� �߼��غ� ó��
function orderDeliReadyProduct ( OrderCode, values, productName ) {
	if( confirm( " \""+productName+"\" ��������� �����մϴ�!") ) {
		processFrm.location.href="order_list.deliReadyProduct.php?delitype="+values+"&ordercode="+OrderCode;
	}
}

// ����Ʈ���� ���û�ǰ ��ۿ��� ����
function orderDeliReadyProductChkAll() {
	if( document.form2.orderDeliReadyProductobj.value!=''){
		var delitype = document.form2.orderDeliReadyProductobj.value;
		if( confirm( "���õ� ��� �ֹ���ǰ�� ���ǥ�⸦ �ٲٽðڽ��ϱ�?") ) {
			var a = 0 ;
			var OrderCodeList = "";
			for(i=0;i<document.form2.chkOrderProductCode.length;i++) {
				if(document.form2.chkOrderProductCode[i].checked==true) {
					var OrderCode = document.form2.chkOrderProductCode[i].value;
					OrderCodeList += OrderCode+",";
					a++;
				}
			}

			if( a == 0 ) {
				alert("ó�� �� �ֹ���ǰ�� �����ϼ���!");
				return;
			} else {
				processFrm.location.href="order_list.deliReadyProduct.php?delitype="+delitype+"&ordercode="+OrderCodeList;
				//window.open("order_list.deliReadyProduct.php?delitype="+delitype+"&ordercode="+OrderCodeList);
			}
		}
	}
}

// ����Ʈ���� �ֹ� �ϰ� �߼��غ� ó��
function orderDeliReadyOkChkAll () {
	if( confirm( "���õ� ��� �ֹ��� ���� �߼��غ� ó�� �Ͻðڽ��ϱ�?\n��, �Ա�ó�� ���� ���� �ֹ��� ���� �˴ϴ�.") ) {
		var a = 0 ;
		var OrderCodeList = "";
		for(i=1;i<document.form2.chkordercode.length;i++) {
			if(document.form2.chkordercode[i].checked==true) {
				var OrderCode = document.form2.chkordercode[i].value.substring(1);
				OrderCodeList += OrderCode+",";
				a++;
			}
		}

		if( a == 0 ) {
			alert("�߼��غ� �� �ֹ��� �����ϼ���!");
			return;
		} else {
			processFrm.location.href="order_list.deliReadyOrder.php?ordercode="+OrderCodeList;
		}

	}
}


// �ϰ� ����ڵ� �Է�
function OrderDeliCodeUpdate () {
	var a = 0 ;
	var OrderCodeList = "";
	for(i=1;i<document.form2.chkordercode.length;i++) {
		if(document.form2.chkordercode[i].checked==true) {
			var OrderCode = document.form2.chkordercode[i].value.substring(1);
			OrderCodeList += OrderCode+",";
			a++;
		}
	}

	if( a == 0 ) {
		alert("�߼ۿϷ� & �����ȣ�� �Է� �� �ֹ��� �����ϼ���!");
		return;
	} else {
		window.open("about:blank","OrderDeliCodeUpdatePop","width=717,height=640,scrollbars=yes");
		var F = document.OrderDeliCodeUpdatePopForm;
		F.ordercode.value=OrderCodeList;
		F.action="order_list.orderEnd.php";
		F.target="OrderDeliCodeUpdatePop";
		F.method="POST";
		F.submit();
	}
	F.ordercode.value='';
}

<?if($vendercnt>0){?>
function viewVenderInfo(vender) {
	window.open("about:blank","vender_infopop","width=100,height=100,scrollbars=yes");
	document.vForm.vender.value=vender;
	document.vForm.target="vender_infopop";
	document.vForm.submit();
}
<?}?>

function searchForm() {
	document.form1.action="order_list.php";
	document.form1.submit();
}

function OrderDetailView(ordercode) {
	//document.detailform.ordercode.value = ordercode;
	window.open("order_detail.php?ordercode="+ordercode,"orderdetail","scrollbars=yes,width=724,height=600");
	//document.detailform.submit();
}

function OnChangePeriod(val) {
	var pForm = document.form1;
	var period = new Array(7);
	period[0] = "<?=$period[0]?>";
	period[1] = "<?=$period[1]?>";
	period[2] = "<?=$period[2]?>";
	period[3] = "<?=$period[3]?>";
	period[4] = "<?=$period[4]?>";
	period[5] = "<?=$period[5]?>";
	period[6] = "";

	pForm.search_start.value = period[val];
	if(val==6) pForm.search_end.value = '';
	else pForm.search_end.value = period[0];
}

function GoPage(block,gotopage) {
	document.idxform.block.value = block;
	document.idxform.gotopage.value = gotopage;
	document.idxform.submit();
}

function GoOrderby(orderby) {
	document.idxform.block.value = "";
	document.idxform.gotopage.value = "";
	document.idxform.orderby.value = orderby;
	document.idxform.submit();
}

function MemberView(id){
	window.open("about:blank","MemberView","width=1000,height=600,scrollbars=yes");
	document.member_form.target="MemberView";
	document.member_form.popup.value="OK";
	parent.topframe.ChangeMenuImg(4);
	document.member_form.search.value=id;
	document.member_form.submit();
}

function SenderSearch(sender) {
	window.open("about:blank","SenderSearch","width=1000,height=600,scrollbars=yes");
	document.sender_form.target="SenderSearch";
	document.sender_form.popup.value="OK";
	document.sender_form.search.value=sender;
	document.sender_form.submit();
}

function ReserveInOut(id){
	window.open("about:blank","reserve_set","width=245,height=140,scrollbars=no");
	document.reserveform.target="reserve_set";
	document.reserveform.id.value=id;
	document.reserveform.type.value="reserve";
	document.reserveform.submit();
}

var clickno=0;
function MemoMouseOver(target) {
	obj = event.srcElement;
	WinObj=eval("document.all."+target);
	obj._tid = setTimeout("MemoView(WinObj)",200);
}
function MemoView(WinObj) {
	WinObj.style.visibility = "visible";
}
function MemoMouseOut(target) {
	obj = event.srcElement;
	WinObj=eval("document.all."+target);
	WinObj.style.visibility = "hidden";
	clearTimeout(obj._tid);
}

function ProductMouseOver(Obj) {
	obj = event.srcElement;
	WinObj=document.getElementById(Obj);
	obj._tid = setTimeout("ProductViewImage(WinObj)",200);
}
function ProductViewImage(WinObj) {
	WinObj.style.display = "";

}
function ProductMouseOut(Obj) {
	obj = event.srcElement;
	WinObj = document.getElementById(Obj);
	WinObj.style.display = "none";
	clearTimeout(obj._tid);
}

function CheckAll(){
   chkval=document.form2.allcheck.checked;
   cnt=document.form2.tot.value;
   for(i=1;i<=cnt;i++){
      document.form2.chkordercode[i].checked=chkval;
   }
}

//��ǰ ��ü ����
function CheckProductAll () {
	var chkval=document.form2.allcheckProduct.checked;
	cnt=document.form2.prtot.value;
	for(i=0;i<=cnt;i++){
		if( document.form2.chkOrderProductCode[i].style.display == 'inline' ) {
			document.form2.chkOrderProductCode[i].checked=chkval;
		}
	}
}


function AddressPrint() {
	document.form1.action="order_address_excel.php";
	document.form1.submit();
	document.form1.action="";
}

function OrderExcel() {
	document.form1.action="order_excel.php";
	document.form1.submit();
	document.form1.action="";
}

function OrderDelete(ordercode) {
	if(confirm("�ش� �ֹ����� �����Ͻðڽ��ϱ�?")) {
		document.idxform.type.value="delete";
		document.idxform.ordercodes.value=ordercode+",";
		document.idxform.submit();
	}
}

function OrderDeliPrint() {
	alert("����� ����� �غ��߿� �ֽ��ϴ�.");
}

function OrderCheckPrint() {
	document.printform.ordercodes.value="";
	for(i=1;i<document.form2.chkordercode.length;i++) {
		if(document.form2.chkordercode[i].checked==true) {
			document.printform.ordercodes.value+=document.form2.chkordercode[i].value.substring(1)+",";
		}
	}
	if(document.printform.ordercodes.value.length==0) {
		alert("�����Ͻ� �ֹ����� �����ϴ�.");
		return;
	}
	if(confirm("�Һ��ڿ� �ֹ����� ����Ͻðڽ��ϱ�?")) {
		document.printform.gbn.value="N";
	} else {
		document.printform.gbn.value="Y";
	}
	document.printform.target="hiddenframe";
	document.printform.submit();
}

function OrderCheckExcel() {
	document.checkexcelform.ordercodes.value="";
	for(i=1;i<document.form2.chkordercode.length;i++) {
		if(document.form2.chkordercode[i].checked==true) {
			document.checkexcelform.ordercodes.value+=document.form2.chkordercode[i].value.substring(1)+",";
		}
	}
	if(document.checkexcelform.ordercodes.value.length==0) {
		alert("�����Ͻ� �ֹ����� �����ϴ�.");
		return;
	}
	document.checkexcelform.action="order_excel.php";
	document.checkexcelform.submit();
}

function OrderCheckDelete() {
	document.idxform.ordercodes.value="";
	for(i=1;i<document.form2.chkordercode.length;i++) {
		if(document.form2.chkordercode[i].checked==true) {
			if(document.form2.chkordercode[i].value.substring(0,1)=="N") {
				alert("������ �Ұ����� �ֹ����� ���ԵǾ��ֽ��ϴ�.");
				return;
			} else {
				document.idxform.ordercodes.value+=document.form2.chkordercode[i].value.substring(1)+",";
			}
		}
	}
	if(document.idxform.ordercodes.value.length==0) {
		alert("�����Ͻ� �ֹ����� �����ϴ�.");
		return;
	}
	if(confirm("�����Ͻ� �ֹ����� �����Ͻðڽ��ϱ�? ")) {
		document.idxform.type.value="delete";
		document.idxform.submit();
	}
}

function ProductInfo(code,prcode,popup,chk) {
	document.form_reg.code.value=code;
	document.form_reg.prcode.value=prcode;
	document.form_reg.popup.value=popup;
	if (popup=="YES") {
		if(chk == "0") { document.form_reg.action="product_register.add.php";}
		else if(chk == "3") { document.form_reg.action="social_shopping2.php";}
		else {document.form_reg.action="product2_register.add.php";}
		document.form_reg.target="register";
		window.open("about:blank","register","width=1000,height=700,scrollbars=yes,status=no");
	} else {
		document.form_reg.action="product_register.php";
		document.form_reg.target="";
	}
	document.form_reg.submit();
}


var shop="<?=($searchtype=="0"?"layer1":"layer2")?>";
var ArrLayer = new Array ("layer1","layer2");
function ViewLayer(gbn){
	if(gbn=="layer2") {
		if(document.form1.gong_gbn[1].checked==true) {
			alert("�������� �˻��� �Ϲ��ֹ������� �˻��Ͻ� �� �ֽ��ϴ�.");
			document.form1.gong_gbn[0].checked=true;
			document.form1.s_check.disabled=false;
		}
	}
	if(document.all){
		for(i=0;i<2;i++) {
			if (ArrLayer[i] == gbn)
				document.all[ArrLayer[i]].style.display="";
			else
				document.all[ArrLayer[i]].style.display="none";
		}
	} else if(document.getElementById){
		for(i=0;i<2;i++) {
			if (ArrLayer[i] == gbn)
				document.getElementByld[ArrLayer[i]].style.display="";
			else
				document.getElementByld[ArrLayer[i]].style.display="none";
		}
	} else if(document.layers){
		for(i=0;i<2;i++) {
			if (ArrLayer[i] == gbn)
				document.layers[ArrLayer[i]].display="";
			else
				document.layers[ArrLayer[i]].display="none";
		}
	}
	shop=gbn;
}


//����¡ ��� ���� ����
function paging_list_num_chg ( f, v ) {
	if( f.gotopage ) f.gotopage.value='1';
	f.method = 'POST';
	f.submit();
}


// SMS
function MemberSMS(tel) {
	document.smsform.number.value=tel;
	window.open("about:blank","sendsmspop","width=220,height=350,scrollbars=no");
	document.smsform.submit();
}

</script>



<!-- �ֹ��� ����(�޸� ����) CSS -->
<style>
	.orderMemo {
		position:absolute;
		z-index:100;
		visibility:hidden;
		margin-left:0px;
		margin-top:19px;
		background:#ffffff;
		width:240px;
		padding:8px 10px;

		border:2px solid #bbbbbb;
		-moz-border-radius: 10px;
		-webkit-border-radius: 10px;
		border-radius: 10px;
	}
	.orderMemo ul {list-style:none; margin:0px; padding:0px;}
	.orderMemo li {padding:0px;}
	.liUnderline {border-bottom:1px dotted #dddddd; padding-bottom:10px;}

	.orangeFont {color:#222222; font-weight:bold;}
</style>

<table cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed">
<tr>
	<td valign="top">
	<table cellpadding="0" cellspacing="0" width=100% style="table-layout:fixed">
	<tr>
		<td>
		<table cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed"  background="images/con_bg.gif">
		<colgroup>
		<col width=198>
		<col width=10>
		<col>
		</colgroup>
		<tr>
			<td valign="top"  background="images/leftmenu_bg.gif">
			<? include ("menu_order.php"); ?>
			</td>

			<td></td>
			<td valign="top">

<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td height="29" colspan="3">
			<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td height="28" class="link" align="left" background="images/con_link_bg.gif"><img src="images/top_link_house.gif" border="0" valign="absmiddle">������ġ : �ֹ�/���� &gt; �ֹ���ȸ �� ��۰��� &gt; <span class="2depth_select">���ں� �ֹ���ȸ/���</span></td>
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
					<TD><IMG SRC="images/order_list_title.gif" ALT=""></TD>
					</tr>
<tr>
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
					<TD width="100%" class="notice_blue">���ں� ���θ��� ��� �ֹ���Ȳ �� �ֹ������� Ȯ��/ó���Ͻ� �� �ֽ��ϴ�.</TD>
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
				<td height="20"></td>
			</tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/order_list_stitle1.gif" WIDTH="187" HEIGHT=31 ALT=""></TD>
					<TD width="100%" background="images/shop_basicinfo_stitle_bg.gif"></TD>
					<TD><IMG SRC="images/shop_basicinfo_stitle_end.gif" WIDTH=10 HEIGHT=31 ALT=""></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr>
				<td height=5></td>
			</tr>
			<tr>
				<td style="padding-left:25px; padding-bottom:10px;">
					<span class="notice_blue">
						1) �ֹ��������� <b>�ֹ��ڸ�</b> Ŭ���ϸ� ȸ���� �ֹ���Ȳ�� ��ȸ�Ͻ� �� �ֽ��ϴ�.<br />
						2) �ֹ���������<b>ȸ��ID</b>�� Ŭ���ϸ� ȸ������ ������ �����մϴ�.<br />
						3) �ֹ��������� <b>SMS������</b>�� Ŭ���Ͻø� �ֹ��ڿ��� ������� �� �����ȳ� �޽����� �ٷ� �߼��� �� �ֽ��ϴ�.
					</span>
				</td>
			</tr>
			<form name=form1 action="<?=$_SERVER[PHP_SELF]?>" method=post>
			<tr>
				<td>
				<table cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td width="100%" bgcolor="#ededed" style="padding:4pt;">
					<table cellpadding="0" cellspacing="0" width="100%" bgcolor="white">
					<tr>
						<td width="100%">
						<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
						<TR>
							<TD class="table_cell" width="139"><img src="images/icon_point5.gif" width="8" height="11" border="0">�Ⱓ����</TD>
							<TD class="td_con1" >
							<input type=text name=search_start value="<?=$search_start?>" size=13 onfocus="this.blur();" OnClick="Calendar(this)"  class="input_selected"> ~ <input type=text name=search_end value="<?=$search_end?>" size=13 onfocus="this.blur();" OnClick="Calendar(this)"  class="input_selected">
							<img src=images/btn_today01.gif border=0 align=absmiddle style="cursor:hand" onclick="OnChangePeriod(0)" alt="����">
							<img src=images/btn_day07.gif border=0 align=absmiddle style="cursor:hand" onclick="OnChangePeriod(1)" alt="7��">
							<img src=images/btn_day14.gif border=0 align=absmiddle style="cursor:hand" onclick="OnChangePeriod(2)" alt="14��">
							<img src=images/btn_day30.gif border=0 align=absmiddle style="cursor:hand" onclick="OnChangePeriod(3)" alt="1����">
							<img src=images/btn_mon06.gif border=0 align=absmiddle style="cursor:hand" onclick="OnChangePeriod(4)" alt="6����">
							<img src=images/btn_year1.gif border=0 align=absmiddle style="cursor:hand" onclick="OnChangePeriod(5)" alt="1��">
							<img src=images/btn_all.gif border=0 align=absmiddle style="cursor:hand" onclick="OnChangePeriod(6)" alt='��ü'>
							</TD>
						</TR>
						<TR>
							<TD colspan="2" background="images/table_con_line.gif"></TD>
						</TR>
						<TR>
							<TD class="table_cell" width="139"><img src="images/icon_point5.gif" width="8" height="11" border="0">��������</TD>
							<TD class="td_con1" >
<?
							$arps=array("\"\":��ü����","Y:�Ա�","B:���Ա�","C:ȯ��");
							for($i=0;$i<count($arps);$i++) {
									$tmp=split(":",$arps[$i]);
									if($tmp[0]==$paystate || (strlen($paystate)==0 && $i==0)) {
										echo "<input type=radio id=\"idx_paystate".$i."\" name=paystate value=\"".$tmp[0]."\" checked > <label style=\"cursor:hand; TEXT-DECORATION: none\" onmouseover=\"style.textDecoration='underline'\" onmouseout=\"style.textDecoration='none'\" for=idx_paystate".$i.">".$tmp[1]."</label>\n";
									} else {
										echo "<input type=radio id=\"idx_paystate".$i."\" name=paystate value=\"".$tmp[0]."\" > <label style=\"cursor:hand; TEXT-DECORATION: none\" onmouseover=\"style.textDecoration='underline'\" onmouseout=\"style.textDecoration='none'\" for=idx_paystate".$i.">".$tmp[1]."</label>\n";
									}
									echo "&nbsp;&nbsp;&nbsp;\n";
								}
?>
							</TD>
						</TR>
						<TR>
							<TD colspan="2" background="images/table_con_line.gif"></TD>
						</TR>
						</TABLE>
						<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
							<TR>
								<TD class="table_cell" width="139"><img src="images/icon_point5.gif" width="8" height="11" border="0">�˻���� ����</TD>
								<TD class="td_con1" >
									<input type=radio id="idx_searchtype1" name=searchtype value="0" onClick="ViewLayer('layer1')" <?if($searchtype=="0") echo "checked";?>>
									<label style='cursor:hand; TEXT-DECORATION: none' onMouseOver="style.textDecoration='underline'" onMouseOut="style.textDecoration='none'" for=idx_searchtype1>�̸����� �˻�</label>
									&nbsp;&nbsp;&nbsp;
									<input type=radio id="idx_searchtype2" name=searchtype value="1" onClick="ViewLayer('layer2')" <?if($searchtype=="1") echo "checked";?>>
									<label style='cursor:hand; TEXT-DECORATION: none' onMouseOver="style.textDecoration='underline'" onMouseOut="style.textDecoration='none'" for=idx_searchtype2>�������� �˻�</label>
								</TD>
							</TR>
						</table>
						<div id=layer1 style="margin-left:0;display:hide; display:<?=($searchtype=="0"?"block":"none")?> ;border-style:solid; border-width:0; border-color:black;background:#FFFFFF;padding:0;">
							<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
								<TR>
									<TD colspan="2" background="images/table_con_line.gif"><img src="images/table_con_line.gif" width="4" height="1" border="0"></TD>
								</TR>
								<TR>
									<TD class="table_cell" width="139"><img src="images/icon_point5.gif" width="8" height="11" border="0">�˻����� �� �Է�</TD>
									<TD class="td_con1" >&nbsp;�˻����� :
										<select name=s_check class="select">
											<option value="A" <?if($s_check=="A")echo"selected";?>>�ֹ���</option>
											<option value="B" <?if($s_check=="B")echo"selected";?>>������</option>
											<option value="C" <?if($s_check=="C")echo"selected";?>>���̵�</option>
											<option value="D" <?if($s_check=="D")echo"selected";?>>�ֹ���ȣ</option>
											<option value="E" <?if($s_check=="E")echo"selected";?>>�̸���</option>
											<option value="F" <?if($s_check=="F")echo"selected";?>>�ּ�</option>
											<option value="G" <?if($s_check=="G")echo"selected";?>>��ȭ��ȣ</option>
											<option value="H" <?if($s_check=="H")echo"selected";?>>�Ա��ڸ�</option>
											<option value="I" <?if($s_check=="I")echo"selected";?>>�����ȣ</option>
										</select>
										&nbsp;&nbsp;&nbsp;&nbsp;�˻���&nbsp;:&nbsp;
										<input type=text name=search value="<?=$search?>" size=50 class="input">
									</TD>
								</TR>
								<TR>
									<TD colspan="2" background="images/table_con_line.gif"><img src="images/table_con_line.gif" width="4" height="1" border="0"></TD>
								</TR>
								<TR>
									<TD class="table_cell" width="139"><img src="images/icon_point5.gif" width="8" height="11" border="0">ó���ܰ� ����</TD>
									<TD class="td_con1" >
										<?
								$ardg=array("\"\":��ü����","N:��ó��","S:��۴��(�߼��غ�)","Y:���","C:�ֹ����","R:�ݼ�","D:��ҿ�û","E:ȯ�Ҵ��","H:���(���꺸��)");
								for($i=0;$i<count($ardg);$i++) {
									$tmp=split(":",$ardg[$i]);
									if($tmp[0]==$deli_gbn || (strlen($deli_gbn)==0 && $i==0)) {
										echo "<input type=radio id=\"idx_deli".$i."\" name=deli_gbn value=\"".$tmp[0]."\" checked \"> <label style=\"cursor:hand; TEXT-DECORATION: none\" onmouseover=\"style.textDecoration='underline'\" onmouseout=\"style.textDecoration='none'\" for=idx_deli".$i.">".$tmp[1]."</label>\n";
									} else {
										echo "<input type=radio id=\"idx_deli".$i."\" name=deli_gbn value=\"".$tmp[0]."\"> <label style=\"cursor:hand; TEXT-DECORATION: none\" onmouseover=\"style.textDecoration='underline'\" onmouseout=\"style.textDecoration='none'\" for=idx_deli".$i.">".$tmp[1]."</label>\n";
									}
									echo "&nbsp;&nbsp;&nbsp;\n";
								}
								?>
									</TD>
								</TR>
								<TR>
									<TD colspan="2" background="images/table_con_line.gif"><img src="images/table_con_line.gif" width="4" height="1" border="0"></TD>
								</TR>
								<TR>
									<TD class="table_cell" width="139"><img src="images/icon_point5.gif" width="8" height="11" border="0">�κ�ȯ�� ����</TD>
									<TD class="td_con1" >
										<input type=radio id="part_cancel_01" name=part_cancel value=""  <?if($part_cancel=="") echo 'checked="checked"';?> /> <label style="cursor:hand; TEXT-DECORATION: none" onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="part_cancel_01">��ü</label>&nbsp;&nbsp;
										<input type=radio id="part_cancel_02" name=part_cancel value="RA" <?if($part_cancel=="RA") echo 'checked="checked"';?> /> <label style="cursor:hand; TEXT-DECORATION: none" onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="part_cancel_02">�κ�ȯ�� ��û</label>&nbsp;&nbsp;
										<input type=radio id="part_cancel_03" name=part_cancel value="RC" <?if($part_cancel=="RC") echo 'checked="checked"';?> /> <label style="cursor:hand; TEXT-DECORATION: none" onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="part_cancel_03">�κ�ȯ�� ȯ�ҿϷ�</label>&nbsp;&nbsp;
									</TD>
								</TR>
							</table>
						</div>
						<div id=layer2 style="margin-left:0;display:hide; display:<?=($searchtype=="1"?"block":"none")?> ;border-style:solid; border-width:0; border-color:black;background:#FFFFFF;padding:0;">
							<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
								<TR>
									<TD colspan="2" background="images/table_con_line.gif"><img src="images/table_con_line.gif" width="4" height="1" border="0"></TD>
								</TR>
								<TR>
									<TD class="table_cell" width="139"><img src="images/icon_point5.gif" width="8" height="11" border="0">�ֹ��ݾ� �Է�</TD>
									<TD class="td_con1" >&nbsp;<B>������ �Աݱݾ�:</B>
										<input type=text name=searchprice value="<?=$searchprice?>" size=30 style="PADDING-RIGHT: 5px; TEXT-ALIGN: right" onKeyUp="strnumkeyup(this);"class="input">
										��<br>
										&nbsp;<span class="font_orange">* �Ա��� Ȯ���� �ȵ� ��� �ݾ����� ��ȸ�� �����մϴ�.</span></TD>
								</TR>
							</table>
						</div>
						<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
							<TR>
								<TD colspan="2"  background="images/table_con_line.gif"><img src="images/table_con_line.gif" width="4" height="1" border="0"></TD>
							</TR>
							<tr>
								<TD class="table_cell" width="139"><img src="images/icon_point5.gif" width="8" height="11" border="0">�ֹ����� ����</TD>
								<TD class="td_con1" >
									<input type=radio id="idx_gong_gbn1" name=gong_gbn value="N" <?if($gong_gbn=="N")echo"checked";?> onClick="this.form.s_check.disabled=false;">
									<label style='cursor:hand;' onMouseOver="style.textDecoration='underline'" onMouseOut="style.textDecoration='none'" for=idx_gong_gbn1>�Ϲ��ֹ�</label>
									&nbsp;&nbsp;&nbsp;
									<input type=radio id="idx_gong_gbn2" name=gong_gbn value="Y" <?if($gong_gbn=="Y")echo"checked";?> onClick="alert('�������� �˻��� �̸����θ� �˻��� �˴ϴ�.');this.form.searchtype[0].checked=true;ViewLayer('layer1');this.form.s_check.disabled=true;">
									<label style='cursor:hand;' onMouseOver="style.textDecoration='underline'" onMouseOut="style.textDecoration='none'" for=idx_gong_gbn2>��������</label>
								</TD>
							</tr>
						</TABLE>
						<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
							<TR>
								<TD colspan="2"  background="images/table_con_line.gif"><img src="images/table_con_line.gif" width="4" height="1" border="0"></TD>
							</TR>
							<tr>
								<TD class="table_cell" width="139"><img src="images/icon_point5.gif" width="8" height="11" border="0">��ǰ�� �ֹ� �˻�</TD>
								<TD class="td_con1" >
									<input type=radio id="idx_gift_gbn1" name=gift_gbn value="A" <?if($gift_gbn=="A")echo"checked";?>>
									<label style='cursor:hand;' onMouseOver="style.textDecoration='underline'" onMouseOut="style.textDecoration='none'" for=idx_gift_gbn1>��ü��ǰ</label>
									&nbsp;&nbsp;&nbsp;
									<input type=radio id="idx_gift_gbn2" name=gift_gbn value="G" <?if($gift_gbn=="G")echo"checked";?>>
									<label style='cursor:hand;' onMouseOver="style.textDecoration='underline'" onMouseOut="style.textDecoration='none'" for=idx_gift_gbn2>��ǰ��</label>
								</TD>
							</tr>
						</TABLE>
						<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
							<TR>
								<TD colspan="2"  background="images/table_con_line.gif"><img src="images/table_con_line.gif" width="4" height="1" border="0"></TD>
							</TR>
							<tr>
								<TD class="table_cell" width="139"><img src="images/icon_point5.gif" width="8" height="11" border="0">�ֹ���� ����</TD>
								<TD class="td_con1" >
									<input type=radio id="idx_device0" name=devices value="All" <?if($devices=="All")echo"checked";?>>
									<label style='cursor:hand;' onMouseOver="style.textDecoration='underline'" onMouseOut="style.textDecoration='none'" for=idx_device0>��ü</label>
									&nbsp;&nbsp;&nbsp;
									<input type=radio id="idx_device2" name=devices value="P" <?if($devices=="P")echo"checked";?>>
									<label style='cursor:hand;' onMouseOver="style.textDecoration='underline'" onMouseOut="style.textDecoration='none'" for=idx_device2>PC</label>
									&nbsp;&nbsp;&nbsp;
									<input type=radio id="idx_device1" name=devices value="M" <?if($devices=="M")echo"checked";?>>
									<label style='cursor:hand;' onMouseOver="style.textDecoration='underline'" onMouseOut="style.textDecoration='none'" for=idx_device1>Mobile</label>
									<!-- &nbsp;&nbsp;&nbsp;
									<input type=radio id="idx_device2" name=devices value="A" <?if(devices=="A")echo"checked";?>>
									<label style='cursor:hand;' onMouseOver="style.textDecoration='underline'" onMouseOut="style.textDecoration='none'" for=idx_device2>App</label> -->
								</TD>
							</tr>
						</TABLE>



						<? /*

						<TR>
							<TD class="table_cell" width="139"><img src="images/icon_point5.gif" width="8" height="11" border="0">��������</TD>
							<TD class="td_con1" ><select name="paystate" class="select">
<?
							$arps=array("\"\":��ü����","Y:�Ա�","B:���Ա�","C:ȯ��");
							for($i=0;$i<count($arps);$i++) {
								$tmp=split(":",$arps[$i]);
								echo "<option value=\"".$tmp[0]."\" ";
								if($tmp[0]==$paystate) echo "selected";
								echo ">".$tmp[1]."</option>\n";
							}
?>
							</select></TD>
						</TR>
						<TR>
							<TD colspan="2" background="images/table_con_line.gif"></TD>
						</TR>
						<TR>
							<TD class="table_cell" width="139"><img src="images/icon_point5.gif" width="8" height="11" border="0">ó���ܰ�</TD>
							<TD class="td_con1" ><select name="deli_gbn" class="select">
<?
							$ardg=array("\"\":��ü����","S:�߼��غ�","Y:���","N:��ó��","C:�ֹ����","YA:��ȯ��ǰ��û","YB:��ȯ��ǰ����","YC:��ȯ��ǰ�Ϸ�","R:�ݼ�","D:��ҿ�û","E:ȯ�Ҵ��","H:���(���꺸��)");
							for($i=0;$i<count($ardg);$i++) {
								$tmp=split(":",$ardg[$i]);
								echo "<option value=\"".$tmp[0]."\" ";
								if($tmp[0]==$deli_gbn) echo "selected";
								echo ">".$tmp[1]."</option>\n";
							}
?>
							</select></TD>
						</TR>
						<TR>
							<TD colspan="2" background="images/table_con_line.gif"></TD>
						</TR>
						<tr>
							<TD class="table_cell" width="139"><img src="images/icon_point5.gif" width="8" height="11" border="0">�ֹ�Ÿ��</TD>
							<TD class="td_con1" width="613"><select name="s_type" class="select">
							<option value="" <?if(strlen($s_type)==0)echo"selected";?>>��ü����</option>
							<option value="0" <?if($s_type=="0")echo"selected";?>>�Ϲ��ֹ�</option>
							<option value="3" <?if($s_type=="3")echo"selected";?>>�Ҽȼ���</option>
							<option value="1" <?if($s_type=="1")echo"selected";?>>��ǰ��(�����ϱ�)</option>
							<option value="2" <?if($s_type=="2")echo"selected";?>>��ǰ��(���α���)</option>
							</select></TD>
						</tr>
						<TR>
							<TD colspan="2" background="images/table_con_line.gif"></TD>
						</TR>
						<tr>
							<TD class="table_cell" width="139"><img src="images/icon_point5.gif" width="8" height="11" border="0">�˻���</TD>
							<TD class="td_con1" ><select name="s_check" class="select">
							<option value="cd" <?if($s_check=="cd")echo"selected";?>>�ֹ��ڵ�</option>
							<!--option value="pn" <?if($s_check=="pn")echo"selected";?>>��ǰ��</option-->
							<option value="mn" <?if($s_check=="mn")echo"selected";?>>�����ڼ���</option>
							<option value="mi" <?if($s_check=="mi")echo"selected";?>>����ȸ��ID</option>
							<option value="cn" <?if($s_check=="cn")echo"selected";?>>��ȸ���ֹ���ȣ</option>
							</select>
							<input type=text name=search value="<?=$search?>" style="width:197" class="input"></TD>
						</tr>
						</TABLE>
						*/ ?>

						</td>
					</tr>
					</table>
					</td>
				</tr>
				</table>
				</td>
			</tr>

			</form>


			<tr>
				<td style="padding-top:10pt;">
					<table style="width:100%;">
						<tr>
							<td align="left">
								<a href="order_reservation.php"><img src="images/botteon_order_reservation.gif" height="38" border="0" alt="���� �Ǹ� �ֹ� ����"></a>
							</td>
							<td align="right">
								<a href="javascript:searchForm();"><img src="images/botteon_search.gif" width="113" height="38" border="0"></a>&nbsp;<a href="javascript:OrderExcel();"><img src="images/btn_excel1.gif" width="127" height="38" border="0" hspace="1" alt="�˻� ����Ʈ ���� �ٿ�ε�"></a>&nbsp;<a href="javascript:AddressPrint();"><img src="images/btn_adress.gif" width="130" height="38" border="0"></a>
							</td>
						</tr>
					</table>
				</td>
			</tr>




			<tr>
				<td>


					<?
						// ���� ��ǰ ����Ʈ
						$listOption['vdate'] = date("Ymdhis"); // ��� ����
						$listOption['more'] = "A"; // ������ ���� ��� ǥ�� (A)
						$rsvProdArray = rsvProdList( $listOption );
					?>


					<table border=0 width=100% cellpadding="0" cellspacing="0">
						<tr>
							<td>

								<table border=0 width="100%" cellpadding="0" cellspacing="0">
									<col width="100">
									<col width="">
									<col width="80">
									<tr><td background="images/table_top_line.gif" colSpan="4" /></tr>
									<tr>
										<td align="center" class="table_cell">������</td>
										<td align="center" class="table_cell1">��ǰ�� ([��ۿ�����] �����ǰ)</td>
										<td align="center" class="table_cell1">���ż�</td>
									</tr>
									<tr><td background="images/table_con_line.gif" colSpan="4" /></tr>
							<?
							if( empty($rsvProdArray) ) {
								echo "
									<tr>
										<td height=\"40\" colspan=\"4\" align=\"center\">
											<b>".$chkDate."</b>�� �����Ǹ� ��ǰ�� �����ϴ�.
										</td>
									</tr>
									<tr><td background=\"images/table_con_line.gif\" colSpan=\"4\" /></tr>
								";
							}

							$i = 0;
							foreach ( $rsvProdArray as $listValue ){

								// ��ǰ����
								$listProd = $listValue['product'];
								echo "
									<tr>
										<td align=\"center\" class=\"td_con\">".$listProd['venderName']."</td>
										<td class=\"td_con1\"><a href=\"order_reservation.php?vdate=".$vdate."&productcode=".$listProd['productcode']."\"><strong>[".$listProd['reservation']."]</strong> ".$listProd['productname']."</a></td>
										<td align=\"center\" class=\"td_con1\">".number_format($listProd['orderCount'])."��</td>
									</tr>
									<tr><td background=\"images/table_con_line.gif\" colSpan=\"4\" /></tr>
								";
								$i++;
								if( $i >= 5 ){ break; }
							}
						?>
								</table>
							</td>
						</tr>
					</table>

				</td>
			</tr>



			

			<tr>
				<td style="padding-bottom:3pt;">
<?
				$arpm=array("B"=>"������","V"=>"������ü","O"=>"�������","Q"=>"�������(�Ÿź�ȣ)","C"=>"�ſ�ī��","P"=>"�ſ�ī��(�Ÿź�ȣ)","M"=>"�ڵ���");

				$sql = "SELECT a.* FROM ".$qry_from." ".$qry." ";
				$sql.= "GROUP BY a.ordercode ORDER BY a.ordercode ".$orderby." ";
				$sql.= "LIMIT " . ($setup[list_num] * ($gotopage - 1)) . ", " . $setup[list_num];

				$result=mysql_query($sql,get_db_conn());
//				$colspan=13;
				$colspan=11;
				if($vendercnt>0) $colspan++;
?>
				<table cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td ><img src="images/icon_8a.gif" width="13" height="13" border="0"><B>���� :
					<?if($orderby=="DESC"){?>
					<A HREF="javascript:GoOrderby('ASC');"><B><FONT class=font_orange>�ֹ����ڼ���</FONT></B></A>
					<?}else{?>
					<A HREF="javascript:GoOrderby('DESC');"><B><FONT class=font_orange>�ֹ����ڼ���</FONT></B></A>
					<?}?>
					</td>
					<td  align="right"><img src="images/icon_8a.gif" width="13" height="13" border="0">�� : <B><?=number_format($t_count)?></B>��, &nbsp;&nbsp;<img src="images/icon_8a.gif" width="13" height="13" border="0">���� <b><?=$gotopage?>/<?=ceil($t_count/$setup[list_num])?></b> ������</td>



					<form name='paging_list_num_form'>
					<td width='120' align="right">
					<strong>��°���:</strong>
					<select name='paging_list_num_sel' onchange='paging_list_num_chg(paging_list_num_form,this.value)'>";
						<?
							$paging_list_num_array = array("10","20","30","50","70","100");
							foreach( $paging_list_num_array as $var ) {
								$sel = ($_SESSION[paging_list_num]==$var)?'selected':'';
								echo	"<option ".$sel." value='".$var."'>".$var."</option>";
							}
						?>
					</select>
					</td>
					<?
						foreach ( $_POST as $t => $v ) {
							if( $t != 'paging_list_num_sel' ) echo "<input type='hidden' name='".$t."' value='".$v."'>";
						}
					?>
					</form>

				</tr>
				</table>

				</td>
			</tr>

			<form name=form2 action="<?=$_SERVER[PHP_SELF]?>" method=post>
			<tr>
				<td>
				<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0 style="table-layout:fixed">
				<colgroup>
				<col width=30>
				<col width=65>
				<col width=86>
				<?if($vendercnt>0){?>
				<col width=61>
				<?}?>
				<col>
			<!--	<col width=30> -->
				<col width=30>
<!--				<col width=60> -->
				<col width=100>
				<col width=70>
				<col width=70>
				<col width=70>
				<col width=40>
				<col width=60>
				</colgroup>
				<input type=hidden name=chkordercode>
				<TR>
					<TD background="images/table_top_line.gif" width="761" colspan="<?=$colspan?>"></TD>
				</TR>
				<TR height=32>
					<TD class="table_cell5" align="center"><input type=checkbox name=allcheck onclick="document.form2.allcheck2.checked=this.checked; CheckAll();"></TD>
					<TD class="table_cell6" align="center">�ֹ�����</TD>
					<TD class="table_cell6" align="center">�ֹ��� ����</TD>
					<?if($vendercnt>0){?>
					<TD class="table_cell6" align="center">������ü</TD>
					<?}?>
					<TD class="table_cell6" align="center">��ǰ��</TD>
<!--					<TD class="table_cell6" align="center"><input type=checkbox name=allcheckProduct onclick="document.form2.allcheckProduct2.checked=this.checked; CheckProductAll();"></TD> -->
					<TD class="table_cell6" align="center">����</TD>
<!--					<TD class="table_cell6" align="center">��ȯ/ȯ��</TD> -->
					<TD class="table_cell6" align="center">��ۿ���</TD>
					<TD class="table_cell6" align="center">��������</TD>
					<TD class="table_cell6" align="center">�����ݾ�</TD>
					<TD class="table_cell6" align="center">ó���ܰ�</TD>
					<TD class="table_cell6" align="center">���</TD>
					<TD class="table_cell6" align="center">�ֹ����</TD>
				</TR>
				<TR>
					<TD colspan="<?=$colspan?>" background="images/table_con_line.gif"></TD>
				</TR>
<?
				$colspan=13;
				if($vendercnt>0) $colspan++;

				$curdate = date("YmdHi",mktime(date("H")-2,date("i"),0,date("m"),date("d"),date("Y")));
				$curdate5 = date("Ymd",mktime(0,0,0,date("m"),date("d")-5,date("Y")));
				$cnt=0;
				$prcnt=0;
				$thisordcd="";
				$thiscolor="#FFFFFF";
				while($row=mysql_fetch_object($result)) {

					$number = ($t_count-($setup[list_num] * ($gotopage-1))-$cnt);


					$date = substr($row->ordercode,0,4)."/".substr($row->ordercode,4,2)."/".substr($row->ordercode,6,2)." (".substr($row->ordercode,8,2).":".substr($row->ordercode,10,2).")";
					$name=$row->sender_name;
					unset($stridX);
					unset($stridM);
					if(substr($row->ordercode,20)=="X") {	//��ȸ��
						$stridX = substr($row->id,1,6);
					} else {	//ȸ��
						$stridM = "<A HREF=\"javascript:MemberView('".$row->id."');\"><FONT COLOR=\"blue\">".$row->id."</FONT></A>";
					}
					if($thisordcd!=$row->ordercode) {
						$thisordcd=$row->ordercode;
						if($thiscolor=="#FFFFFF") {
							$thiscolor="#FEF8ED";
						} else {
							$thiscolor="#FFFFFF";
						}
					}

					$sql = "
						SELECT
							a.*,
							r.options as rentOptions, r.bookingStartDate, r.bookingEndDate, r.status as rentStatus,
							(select tinyimage from tblproduct b where a.productcode=b.productcode ) tinyimage,
							(select reservation from tblproduct b where a.productcode=b.productcode ) reservation
						FROM
							tblorderproduct a
							LEFT JOIN rent_product_schedule r ON a.productcode = r.productcode AND a.ordercode = r.ordercode
						WHERE
							a.ordercode='".$row->ordercode."'
							AND NOT (a.productcode LIKE 'COU%' OR a.productcode LIKE '999999%')
					";
					/*
					if(strlen($search)>0 && $s_check=="pn") {
						$sql.= "AND a.productname LIKE '%".$search."%' ";
					}*/

					$result2=mysql_query($sql,get_db_conn());
					$jj=0;
					unset($prval);
					unset($arrdeli);
					$viewOrderCHK = true;
					while($row2=mysql_fetch_object($result2)) {
						$imgcnt++;
						if (strlen($row2->tinyimage)>0 && file_exists($imagepath.$row2->tinyimage)==true){
							$tinyImg= "<td class=\"td_con6\" align=center><img src='".$imagepath.$row2->tinyimage."' height=40 width=40 border=1 onMouseOver=\"ProductMouseOver('primage".$imgcnt."')\" onMouseOut=\"ProductMouseOut('primage".$imgcnt."');\">";
						} else {
							$tinyImg="<td class=\"td_con6\" align=center><img src=images/space01.gif >";
						}
						$tinyImg .="<div id=\"primage".$imgcnt."\" style=\"position:absolute; z-index:100; display:none;\"><table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"170\">\n";
						$tinyImg .=	"	<tr bgcolor=\"#FFFFFF\">\n";
						if (strlen($row2->tinyimage)>0 && file_exists($imagepath.$row2->tinyimage)==true){
							$tinyImg .=	"	<td align=\"center\" width=\"100%\" height=\"150\" style=\"border:#000000 solid 1px;\"><img src=\"".$imagepath.$row2->tinyimage."\" border=\"0\"></td>\n";
						}
						$tinyImg .= "	</tr>\n";
						$tinyImg .= "	</table>\n";
						$tinyImg .= "</div></td>\n";

						$arrdeli[$row2->deli_gbn]=$row2->deli_gbn;
						if($jj>0) $prval.="<tr><td colspan=".($vendercnt>0?"6":"5")." height=1 bgcolor=#E7E7E7></tr>";
						$prval.="<tr>\n";


						if($vendercnt>0) {
							$prval.="	<td class=\"td_con6\" align=center style=\"font-size:8pt\">".(strlen($venderlist[$row2->vender]->vender)>0?"<B><a href=\"javascript:viewVenderInfo(".$row2->vender.")\">".$venderlist[$row2->vender]->id."</a></B>":"-")."</td>\n";
							$prval.=$tinyImg;
							$prval.="	<td class=\"td_con6\" style=\"font-size:8pt;padding:3;line-height:10pt\">".((substr($row2->productcode,0,3) == '899')?'<span style="font-weight:bold;color:orange">[Ÿ�Ӽ���]</span>':'')."<font color=#0066cc>".titleCut(58,$row2->productname)."</font>";
						} else{
							$prval.=$tinyImg;
							$prval.="	<td class=\"td_con6\" style=\"font-size:8pt;padding:3;line-height:10pt\">".((substr($row2->productcode,0,3) == '899')?'<span style="font-weight:bold;color:orange">[Ÿ�Ӽ���]</span>':'')."<font color=#0066cc>".titleCut(58,$row2->productname)."</font>";
						}

						if(substr($row2->productcode,-4)!="GIFT") {
							$prval.=" <a href=\"JavaScript:ProductInfo('".substr($row2->productcode,0,12)."','".$row2->productcode."','YES','".$row->gift."')\"><img src=images/newwindow.gif border=0 align=absmiddle></a>";
						}


						$prval.="<br>";
						if(preg_match('/\[OPTG[0-9]+\]/',$row2->opt1_name)){
							$osql = "select opt_name from tblorderoption where ordercode='".$row->ordercode."' and productcode='".$row2->productcode."' and opt_idx='".$row2->opt1_name."' limit 1";
							if(false !== $ores= mysql_query($osql,get_db_conn())){
								if(mysql_num_rows($ores)){
									$prval .= $classred1.mysql_result($ores,0,0).$classred2;
									mysql_free_result($ores);
								}
							}
						}else{
							$prval.= (strlen($row2->opt1_name)>0?$classred1.$row2->opt1_name.$classred2."&nbsp;&nbsp;&nbsp;":"");
							$prval.= (strlen($row2->opt2_name)>0?$classred1.$row2->opt2_name.$classred2."&nbsp;&nbsp;&nbsp;":"");
							$prval.= (strlen($row2->opt3_name)>0?$row2->opt3_name."&nbsp;&nbsp;&nbsp;":"");
							$prval.= (strlen($row2->opt4_name)>0?$row2->opt4_name."&nbsp;&nbsp;&nbsp;":"");
						}

						// �뿩��ǰ
						if( strlen($row2->bookingStartDate) > 0 AND strlen($row2->bookingEndDate) > 0 ) {
							$printOptions = "";
							$rentOptionsA = explode("|",$row2->rentOptions);
							foreach ( $rentOptionsA as $rentOptionsAv ) {
								$rentOptionsB = explode(":", $rentOptionsAv);
								if (strlen($rentOptionsB[0]) > 0 AND strlen($rentOptionsB[1]) > 0) {
									$prodOptInfo = rentProductOptionInfo($rentOptionsB[0]);
									$printOptions .= $prodOptInfo['optionName'].":".$rentOptionsB[1]." ��<br>";
								}
							}
							$prval .= "<font color='0000FF'><strong>[�뿩��ǰ : ".$row2->bookingStartDate." ~ ".$row2->bookingEndDate."]</strong></font><br>".$printOptions." ";
						}

						// ���� ��ǰ ǥ��
						if( $row2->reservation != "0000-00-00" AND strtotime($row2->reservation) > strtotime(date("Ymd")) ) {
							$prval .= "<font color='ff0000'><strong>[���� �Ǹ� ��ǰ : ".$row2->reservation."]</strong></font>";
						}


						$optkey = "";
						if( strlen($row2->opt1_name) > 0 ) $prval.="	</td>\n";


						// �Ա�Ȯ�� üũ
						// ��ȯ(EA|EB|EC) / ȯ��(RA|RB|RC)�� ----- ��ǰ�� üũ �ڽ� ǥ�� ���� �߰� 2014-03-10 x2chi
						// ��ۻ��°� �ֹ����(C),ȯ�Ҵ��(E),�ݼ�(R) �ΰ�� üũ�ڽ� ǥ�� ���� �߰� 2014-03-19 x2chi
						$inMoneyCHK = true;
						if( ( preg_match("/^(B){1}/", $row->paymethod) AND strlen($row->bank_date)==0 ) OR eregi("RA|RB|RC",$row2->status) OR eregi("C|R|E",$row2->deli_gbn) ) {
							$inMoneyCHK = false;
						}

						// �ֹ�����Ʈ �ϰ� ó���� (�Ա�Ȯ��,�߼��غ�,��۰���) üũ �ڽ� ���⿩�� 2014-03-19 x2chi
						if( eregi("RA|RB|RC",$row2->status) OR eregi("C|R|E",$row2->deli_gbn) OR substr($row2->productcode,0,3) == "999") {
							$viewOrderCHK = false;
						}

						// ��ǰ�� üũ �ڽ�
						/*
						$chkOrderProductCodeDisplay = ( $inMoneyCHK ) ?  "inline" : "none" ;
						$prval.="	<td class=\"td_con6\" align=center style=\"font-size:8pt;\">&nbsp;<input type=checkbox name=chkOrderProductCode id=\"chkOrderProductCode_".$row2->uid."\" value=\"".$row2->uid."\" style=\"display:".$chkOrderProductCodeDisplay.";\" >&nbsp;</td>\n";*/
						$prcnt++;


						if( strlen($row2->rentOptions) > 0 ) {
							$prval.="	<td class=\"td_con6\" align=center style=\"font-size:8pt;\">-</td>\n";
						} else {
							$prval.="	<td class=\"td_con6\" align=center style=\"font-size:8pt;\">".$row2->quantity."</td>\n";
						}


						/* �߰� �κ� */
						$prval.="	<td class=\"td_con6\" align=center style=\"font-size:8pt;\">";
						switch($row2->status) {
							  case 'EA': $prval .= "��ȯ��û";  break;
							  case 'EB': $prval .= "��ȯ����";  break;
							  case 'EC': $prval .= "��ȯ�Ϸ�";  break;
							  case 'RA': $prval .= "<span style='color:red'>ȯ�ҽ�û</span>";  break;
							  case 'RB': $prval .= "ȯ������";  break;
							  case 'RC': $prval .= "<span style='color:blue'>ȯ�ҿϷ�</span>"; $row2->deli_gbn = "RC"; break;
							  default : $prval .= "&nbsp;";  break;
						}
						$prval.="	</td>\n";
						/* - �߰��κ� */


						$deliStep = array(
							'S'=>"��۴��(�߼��غ�)",
							'X'=>"��ۿ�û",
							'Y'=>"���",
							'D'=>"<font color=blue>��ҿ�û</font>",
							'N'=>"��ó��",
							'E'=>"<font color=red>ȯ�Ҵ��</font>",
							'C'=>"<font color=red>�ֹ����</font>",
							'R'=>"�ݼ�",
							'H'=>"���(<font color=red>���꺸��</font>)",
							'RC'=>"<span style='color:blue'>ȯ�ҿϷ�</span>"
						);
						$prval.="	<td class=\"td_con6\" align=center style=\"font-size:8pt;padding:3\">";
						if($row->gift==0 || $row->gift==3) {
							/*
							$prval.="<select id='orderDeliReadyProductobj_".$row->ordercode.$row2->productcode."' onchange=\"orderDeliReadyProduct('".$row->ordercode."|".$row2->productcode."', this.value, '".$row2->productname."');\" style=\"width: 90px; height: 18px; font-size: 8pt;\">";
							foreach( $deliStep as $key=>$var ) {
								$sel = ( $key == $row2->deli_gbn ) ? "selected" : "";
								$prval.="<option value='".$key."' ".$sel.">".$var."</option>";
							}
							$prval.="</select>";
							*/

							$prval.= $deliStep[$row2->deli_gbn]."";
							if($row2->deli_gbn=="D" && strlen($row2->deli_date)==14) $prval.=" (���)";
						}
						else {
							if($row->gift=='1') $prval.="<font style='color:#3399ff'>��ǰ��<br />(�����ϱ�)</font>";
							else if($row->gift=='2')  $prval.="<font style='color:#3399ff'>��ǰ��<br />(���α���)</font>";
						}
						$prval.="	</td>\n";
						$prval.="</tr>\n";
						$jj++;
					}
					mysql_free_result($result2);

					if (preg_match("/^(N|C|R|D)$/", $row->deli_gbn) && getDeligbn($arrdeli,"N|C|R|D",true)) {
						if (preg_match("/^(O|Q){1}/", $row->paymethod) && strlen($row->bank_date)==0 && substr($row->ordercode,0,8)<=$curdate5) {	//��������� ��� ���Աݵ� �����Ϳ� ���ؼ� 5���� ������ ��� ����
							#��������
							$strdel = "<a href=\"javascript:OrderDelete('".$row->ordercode."')\"><img src=\"images/bu_delete.gif\" border=\"0\" align=\"absmiddle\"></a>";
							$delgbn="Y";
						} else if($row->deli_gbn!="C" && preg_match("/^(C|V){1}/", $row->paymethod) && substr($row->ordercode,0,12)>$curdate) { //�ֹ���Ұ� �ƴϰ�, ī��/������ü �ǿ� ���ؼ� 2�ð� ���� �����ʹ� ���� �Ұ���
							#���� �Ұ���
							$strdel = "<font color=#3D3D3D>--</font></td>";
							$delgbn="N";
						} else {
							if (preg_match("/^(Q|P){1}/", $row->paymethod) && $row->deli_gbn!="C") {	//�Ÿź�ȣ �������/�ſ�ī��� ������� ������ �Ұ���
								#���� �Ұ���
								$strdel = "<font color=#3D3D3D>--</font></a>";
								$delgbn="N";
							} else if (strcmp($row->pay_flag,"0000")==0 && $row->pay_admin_proc!="C" && !preg_match("/^(V|O|Q){1}/", $row->paymethod)) {//�ſ�ī��/�޴��� �������� ��� �� ������ ����
								#���� ��� �� ���� �����մϴ�!!
								$strdel = "<a href=\"javascript:alert('���� ��� �� ������ �����մϴ�.')\"><img src=\"images/bu_delete.gif\" border=\"0\" align=\"absmiddle\"></a>";
								$delgbn="N";
							} else {
								#���� ����
								$strdel = "<a href=\"javascript:OrderDelete('".$row->ordercode."')\"><img src=\"images/bu_delete.gif\" border=\"0\" align=\"absmiddle\"></a>";
								$delgbn="Y";
							}
						}
					} else {
						#���� �Ұ���
						$strdel = "--";
						$delgbn="N";
					}

					if($cnt>0)
					{
						echo "<tr>\n";
						echo "	<TD height=1 background=\"images/table_con_line.gif\" colspan=\"".$colspan."\"></TD>\n";
						echo "</tr>\n";
					}

					echo "<tr bgcolor=".$thiscolor." onmouseover=\"this.style.background='#FEFBD1'\" onmouseout=\"this.style.background='".$thiscolor."'\">\n";

					$chkOrderCHKBOXDisplay = ( $viewOrderCHK ) ?  "inline" : "none" ;
					echo "<td class=\"td_con5\" align=center style=\"font-size:8pt;line-height:10pt\"><input type=checkbox name=chkordercode value=\"".$delgbn.$row->ordercode."\" style=\"display:".$chkOrderCHKBOXDisplay.";\"><br>".$number."</td>\n";
					echo "	<td class=\"td_con6\" align=center style=\"font-size:8pt;padding:3;line-height:11pt\"><A HREF=\"javascript:OrderDetailView('".$row->ordercode."')\">".$date."<br><img src=\"images/orderDetailPop.gif\" alt='�ֹ�������' border=\"0\" align=\"absmiddle\"></A></td>\n";
					echo "	<td class=\"td_con6\" style=\"font-size:8pt;padding:3;line-height:11pt\">\n";
					echo "	�ֹ���: <A HREF=\"javascript:SenderSearch('".$name."');\"><FONT COLOR=\"blue\">".$name."</font></A>";
					if(strlen($stridX)>0) {
						echo "<br> �ֹ���ȣ: ".$stridX;
					} else if(strlen($stridM)>0) {
						echo "<br> ���̵�: ".$stridM;
					}


					echo "<br /><table border=0 cellpadding=0 cellspacing=0>";
					echo "<tr>";

					if( strlen($row->sender_tel) > 0 ) {
						echo "<td><A HREF=\"javascript:MemberSMS('".$row->sender_tel."')\" title='".$row->sender_tel."'><IMG src=\"images/member_mobile2.gif\" onmouseover=\"this.src='images/member_mobile2_on.gif'\" onmouseout=\"this.src='images/member_mobile2.gif'\" align=absMiddle border=0></A></td>";
					}

					$order_msg=explode("[MEMO]",$row->order_msg);
					//if(strlen($row->order_msg)>0 || $row->paymethod=="B") {

							// �޸�
							if(strlen($order_msg[0])>0) {
								echo "<td><img src=\"images/member_memo.gif\" alt='' border=\"0\" align=\"absmiddle\" onMouseOver=\"MemoMouseOver('memo".$cnt."');\" onMouseOut=\"MemoMouseOut('memo".$cnt."');\"></td>";
								echo "	<div id=memo".$cnt." class=\"orderMemo\">";
								echo "		<ul>";
								//echo "<FIELDSET>";
								echo "			<li><span class=orangeFont>�޼���</span></li>";
								echo "			<li>".nl2br(strip_tags($order_msg[0]))."</li>"; //class=liUnderline
								//echo "</FIELDSET>";
								echo "		</ul>";
								echo "	</div>";
							}

							//�ֹ��޸�
							if(strlen($order_msg[1])>0) {
								echo "<td><img src=\"images/member_memo.gif\" alt='' border=\"0\" align=\"absmiddle\" onMouseOver=\"this.src='images/member_memo_on.gif'; MemoMouseOver('ordermemo".$cnt."');\" onMouseOut=\"this.src='images/member_memo.gif'; MemoMouseOut('ordermemo".$cnt."');\"></td>";
								echo "	<div id=ordermemo".$cnt." class=\"orderMemo\">";
								echo "		<ul>";
								//echo "<FIELDSET>";
								echo "			<li><span class=orangeFont>�ֹ��޸�</span></li>";
								echo "			<li>".nl2br(strip_tags($order_msg[1]))."</li>";
								//echo "</FIELDSET>";
								echo "		</ul>";
								echo "	</div>";
							}

							//���˸���
							if(strlen($order_msg[2])>0) {
								echo "<td><img src=\"images/member_alrim.gif\" alt='' border=\"0\" align=\"absmiddle\" onMouseOver=\"this.src='images/member_alrim_on.gif'; MemoMouseOver('alert".$cnt."');\" onMouseOut=\"this.src='images/member_alrim.gif'; MemoMouseOut('alert".$cnt."');\"></td>";
								echo "	<div id=alert".$cnt." class=\"orderMemo\">";
								echo "		<ul>";
								//echo "<FIELDSET>";
								echo "			<li><span class=orangeFont>���˸���</span></li>";
								echo "			<li>".nl2br(strip_tags($order_msg[2]))."</li>";
								//echo "</FIELDSET>";
								echo "		</ul>";
								echo "	</div>";
							}

							//�Աݰ���
							if($row->paymethod=="B") {
								echo "<td><img src=\"images/member_bank.gif\" alt='' border=\"0\" align=\"absmiddle\" onMouseOver=\"this.src='images/member_bank_on.gif'; MemoMouseOver('acount".$cnt."');\" onMouseOut=\"this.src='images/member_bank.gif'; MemoMouseOut('acount".$cnt."');\"></td>";
								echo "	<div id=acount".$cnt." class=\"orderMemo\">";
								echo "		<ul>";
								//echo "<FIELDSET>";
								echo "			<li><span class=orangeFont>�Աݰ���</span></li>";
								echo "			<li>".nl2br(strip_tags($row->pay_data))."</li>";
								//echo "</FIELDSET>";
								echo "		</ul>";
								echo "	</div>";
							}
					//}

					echo "</tr>";
					echo "</table>";

					echo "	</td>\n";
					echo "	<td class=\"td_con6\" colspan=".($vendercnt>0?"6":"5")." height=100%>\n";
					echo "	<table border=0 cellpadding=0 cellspacing=0 width=100% height=100% style=\"table-layout:fixed\">\n";
					echo "<colgroup>";
					if($vendercnt>0) {
						echo "<col width=60>\n";
					}
					echo "	<col width=50>\n";
					echo "	<col>\n";
					echo "	<col width=30>\n";
					echo "	<col width=30>\n";
					echo "	<col width=60>\n";
					echo "	<col width=100>\n";
					echo "</colgroup>";
					echo $prval;
					echo "	</table>\n";
					echo "	</td>\n";
					echo "	<td class=\"td_con6\" align=center style=\"font-size:8pt;padding:3;line-height:12pt\">";
					echo $arpm[substr($row->paymethod,0,1)]."<br>";
					if(preg_match("/^(B){1}/", $row->paymethod)) {	//������
						if (strlen($row->bank_date)==9 && substr($row->bank_date,8,1)=="X") {
							echo "<font color=005000> [ȯ��]</font>";
						} else if (strlen($row->bank_date)>0) {
							echo " <font color=004000>[�ԱݿϷ�]</font>";
						} else {
							if( !eregi("C|E",$row->deli_gbn) ) {
								if($row->gift == "0"){
									echo "<span id=\"orderBankOKobj_".$row->ordercode."\" style=\"cursor:pointer; display:block;\" onclick=\"orderBankOK('".$row->ordercode."');\"><img src='images/orderBankOK.gif' alt='�Ա�ó��'></span>";
								}else{
									echo "<span id=\"orderBankOKobj_".$row->ordercode."\" style=\"cursor:pointer; display:block;\">[���Ա�]</span>";
								}
								echo "<span id=\"orderBankOKOKobj_".$row->ordercode."\" style=\"cursor:pointer; display:none;\"><font color=004000> [�ԱݿϷ�]</font></span>";

							}
						}
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
					echo "	<td class=\"td_con6\" align=right style=\"font-size:8pt;padding:3\">".number_format($row->price)."</td>\n";
					echo "	<td class=\"td_con6\" align=center style=\"font-size:8pt;padding:3;line-height:11pt\">";
					switch($row->deli_gbn) {
						case 'S': echo "��۴��(�߼��غ�)";  break;
						case 'X':
							if($row->gift=='1') echo "������ȣ�߼�";
							else echo "��ۿ�û";  break;
						case 'Y':
							if($row->gift=='1') echo "�����������Ϸ�";
							else if($row->gift=='2') echo "�����Ϸ�";
							else echo "���";
						break;
						case 'D': echo "<font color=blue>��ҿ�û</font>";  break;
						case 'N': echo "��ó��";  break;
						case 'E': echo "<font color=red>ȯ�Ҵ��</font>";  break;
						case 'C': echo "<font color=red>�ֹ����</font>";  break;
						case 'R': echo "�ݼ�";  break;
						case 'H': echo "���(<font color=red>���꺸��</font>)";  break;
					}
					if($row->deli_gbn=="D" && strlen($row->deli_date)==14) echo "<br>(���)";
					if($row->deli_gbn=="R" && substr($row->ordercode,20)!="X") {
						echo "<br><button class=button2 style=\"width:45;color:blue\" onclick=\"ReserveInOut('".$row->id."');\">������</button>";
					}
					echo "	</td>\n";
					echo "	<td class=\"td_con6\" align=center>".$strdel."</td>\n";

					//���� ����̽�
					$deviceinfo="";
					switch($row->device){
						case "M":
							$deviceinfo = "Mobile";
						break;
						case "P":
							$deviceinfo = "PC";
						break;
						default:
							$deviceinfo = "--";
						break;

					}
					echo "	<td class=\"td_con6\" align=center>".$deviceinfo."</td>\n";
					echo "</tr>\n";

					$cnt++;
				}
				mysql_free_result($result);
				if($cnt==0) {
					echo "<tr height=28 bgcolor=#FFFFFF><td colspan=".$colspan." align=center>��ȸ�� ������ �����ϴ�.</td></tr>\n";
				}
?>
				<TR>
					<TD background="images/table_top_line.gif" colspan="<?=$colspan?>"></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr>
				<td style="padding-top:4pt;">
					<table width='100%'>
						<tr>
							<td align='left'>
								<input type=checkbox name=allcheck2 onclick="document.form2.allcheck.checked=this.checked; CheckAll();">
								<a href="javascript:orderBankOkChkAll();"><img src="images/btn_bankOkAll.gif" border="0" alt='�����ֹ� �ϰ��Ա�Ȯ��'></a>
								<a href="javascript:orderDeliReadyOkChkAll();"><img src="images/btn_deliReadyOkAll.gif" border="0" alt='�����ֹ� �ϰ��߼��غ�'></a>
								<a href="javascript:OrderDeliCodeUpdate();"><img src="images/btn_orderDeliCodeUpload.gif" border="0" alt="�߼۴���ֹ� �ϰ���� ����(�˾�)"></a>
								<br>
								�� ���Աݰ�, �ֹ����(��û) �� ȯ��(��û)���� �����ϰ� �ֹ�ó�����¸� �����մϴ�.
							</td>
							<td width='460px' align='left'>
								<?
									$deliStep = array(
										'S'=>"��۴��(�߼��غ�)",
										'Y'=>"���",
										'N'=>"��ó��"
									);
								?>

								<input type=checkbox name=allcheckProduct2 onclick="document.form2.allcheckProduct.checked=this.checked; CheckProductAll();">�� ���û�ǰ ��ۿ��� ����
								<select name='orderDeliReadyProductobj' id='orderDeliReadyProductobj' onchange="orderDeliReadyProductChkAll();" style="height: 18px; font-size: 8pt;">
								<option value=''>����</option>
								<?
									foreach( $deliStep as $key=>$var ) {
										echo "<option value='".$key."'>".$var."</option>";
									}
								?>
								</select>
								<font color="red">"<strong>��ۿ���</strong>"<u>ǥ��</u>�� ����</font>
								<br>
								<font color="red">�� ��ۿ��� ���� ������ <u>�Ա�Ȯ��</u> �� �����մϴ�.</font>
								<!-- -->
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td style="padding-top:4pt;">
					<!-- <a href="javascript:OrderDeliPrint();"><img src="images/btn_print.gif" width="127" height="38" border="0" hspace="1"></a>&nbsp; -->
					<a href="javascript:OrderCheckPrint();"><img src="images/btn_juprint.gif" width="127" height="38" border="0" hspace="0"></a>
					<a href="javascript:OrderCheckExcel();"><img src="images/btn_excel1.gif" width="127" height="38" border="0" hspace="1" alt="���� �ֹ� ���� �ٿ�ε�"></a>
					<a href="javascript:OrderCheckDelete();"><img src="images/btn_judel.gif" width="127" height="38" border="0"></a>
					<a href="order_csvdelivery.php"><img src="images/btn_orderDeliCodeExcelUpload.gif" height="38" border="0" hspace="1" alt="�ֹ�����Ʈ �ϰ���� ����(����)"></a>
				</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td align="center">
				<table cellpadding="0" cellspacing="0" width="100%">
<?
				$total_block = intval($pagecount / $setup[page_num]);

				if (($pagecount % $setup[page_num]) > 0) {
					$total_block = $total_block + 1;
				}

				$total_block = $total_block - 1;

				if (ceil($t_count/$setup[list_num]) > 0) {
					// ����	x�� ����ϴ� �κ�-����
					$a_first_block = "";
					if ($nowblock > 0) {
						$a_first_block .= "<a href='javascript:GoPage(0,1);' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='ù ������';return true\"><IMG src=\"images/icon_first.gif\" border=0 align=\"absmiddle\"></a>&nbsp;&nbsp;";

						$prev_page_exists = true;
					}

					$a_prev_page = "";
					if ($nowblock > 0) {
						$a_prev_page .= "<a href='javascript:GoPage(".($nowblock-1).",".($setup[page_num]*($block-1)+$setup[page_num]).");' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='���� ".$setup[page_num]." ������';return true\">[prev]</a>&nbsp;&nbsp;";

						$a_prev_page = $a_first_block.$a_prev_page;
					}

					// �Ϲ� �������� ������ ǥ�úκ�-����

					if (intval($total_block) <> intval($nowblock)) {
						$print_page = "";
						for ($gopage = 1; $gopage <= $setup[page_num]; $gopage++) {
							if ((intval($nowblock*$setup[page_num]) + $gopage) == intval($gotopage)) {
								$print_page .= "<span class=font_orange2><B>[".(intval($nowblock*$setup[page_num]) + $gopage)."]</B></span> ";
							} else {
								$print_page .= "<a href='javascript:GoPage(".$nowblock.",".(intval($nowblock*$setup[page_num]) + $gopage).");' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='������ : ".(intval($nowblock*$setup[page_num]) + $gopage)."';return true\">[".(intval($nowblock*$setup[page_num]) + $gopage)."]</a> ";
							}
						}
					} else {
						if (($pagecount % $setup[page_num]) == 0) {
							$lastpage = $setup[page_num];
						} else {
							$lastpage = $pagecount % $setup[page_num];
						}

						for ($gopage = 1; $gopage <= $lastpage; $gopage++) {
							if (intval($nowblock*$setup[page_num]) + $gopage == intval($gotopage)) {
								$print_page .= "<span class=font_orange2><B>[".(intval($nowblock*$setup[page_num]) + $gopage)."]</B></span> ";
							} else {
								$print_page .= "<a href='javascript:GoPage(".$nowblock.",".(intval($nowblock*$setup[page_num]) + $gopage).");' onMouseOver=\"window.status='������ : ".(intval($nowblock*$setup[page_num]) + $gopage)."';return true\">[".(intval($nowblock*$setup[page_num]) + $gopage)."]</a> ";
							}
						}
					}		// ������ �������� ǥ�úκ�-��


					$a_last_block = "";
					if ((intval($total_block) > 0) && (intval($nowblock) < intval($total_block))) {
						$last_block = ceil($t_count/($setup[list_num]*$setup[page_num])) - 1;
						$last_gotopage = ceil($t_count/$setup[list_num]);

						$a_last_block .= "&nbsp;&nbsp;<a href='javascript:GoPage(".$last_block.",".$last_gotopage.");' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='������ ������';return true\"><IMG src=\"images/icon_last.gif\" border=0 align=\"absmiddle\" width=\"17\" height=\"14\"></a>";

						$next_page_exists = true;
					}

					// ���� 10�� ó���κ�...

					$a_next_page = "";
					if ((intval($total_block) > 0) && (intval($nowblock) < intval($total_block))) {
						$a_next_page .= "&nbsp;&nbsp;<a href='javascript:GoPage(".($nowblock+1).",".($setup[page_num]*($nowblock+1)+1).");' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='���� ".$setup[page_num]." ������';return true\">[next]</a>";

						$a_next_page = $a_next_page.$a_last_block;
					}
				} else {
					$print_page = "<B>[1]</B>";
				}
				echo "<tr>\n";
				echo "	<td width=\"100%\" class=\"font_size\"><p align=\"center\">\n";
				echo "		".$a_div_prev_page.$a_prev_page.$print_page.$a_next_page.$a_div_next_page;
				echo "	</td>\n";
				echo "</tr>\n";
?>
				</table>
				</td>

				<input type=hidden name=tot value="<?=$cnt?>">
				<input type=hidden name=prtot value="<?=$prcnt?>">
				</form>
			</tr>



			<form name=detailform method="post" action="order_detail.php" target="orderdetail">
			<input type=hidden name=ordercode>
			</form>

			<form name=idxform action="<?=$_SERVER[PHP_SELF]?>" method=post>
			<input type=hidden name=type>
			<input type=hidden name=ordercodes>
			<input type=hidden name=block value="<?=$block?>">
			<input type=hidden name=gotopage value="<?=$gotopage?>">
			<input type=hidden name=orderby value="<?=$orderby?>">
			<input type=hidden name=s_check value="<?=$s_check?>">
			<input type=hidden name=s_type value="<?=$s_type?>">
			<input type=hidden name=search value="<?=$search?>">
			<input type=hidden name=search_start value="<?=$search_start?>">
			<input type=hidden name=search_end value="<?=$search_end?>">
			<input type=hidden name=paymethod value="<?=$paymethod?>">
			<input type=hidden name=paystate value="<?=$paystate?>">
			<input type=hidden name=deli_gbn value="<?=$deli_gbn?>">
			<input type=hidden name=devices value="<?=$devices?>">
			<input type=hidden name=searchprice value="<?=$searchprice?>">
			<input type=hidden name=part_cancel value="<?=$part_cancel?>">
			<input type=hidden name=gift_gbn value="<?=$gift_gbn?>">
			</form>

			<form name=member_form action="member_list.php" method=post>
			<input type=hidden name=search>
			<input type=hidden name=popup>
			</form>

			<form name=sender_form action="order_namesearch.php" method=post>
			<input type=hidden name=search>
			<input type=hidden name=popup>
			</form>

			<form name=reserveform action="reserve_money.php" method=post>
			<input type=hidden name=type>
			<input type=hidden name=id>
			</form>

			<form name=printform action="order_print_pop.php" method=post target="ordercheckprint">
			<input type=hidden name=ordercodes>
			<input type=hidden name=gbn>
			</form>

			<form name=checkexcelform action="order_excel.php" method=post>
			<input type=hidden name=ordercodes>
			</form>

			<form name=mailform action="member_mailsend.php" method=post>
			<input type=hidden name=rmail>
			</form>

			<form name=form_reg action="product_register.php" method=post>
			<input type=hidden name=code>
			<input type=hidden name=prcode>
			<input type=hidden name=popup>
			</form>

			<form name=smsform action="sendsms.php" method=post target="sendsmspop">
			<input type=hidden name=number>
			</form>

			<?if($vendercnt>0){?>
			<form name=vForm action="vender_infopop.php" method=post>
			<input type=hidden name=vender>
			</form>
			<?}?>

			<!-- �ϰ� ����ڵ� �Է� -->
			<form name=OrderDeliCodeUpdatePopForm>
				<input type=hidden name=ordercode>
			</form>

			<tr>
				<td height=20></td>
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
						<td><span class="font_dotline">���ں� �ֹ���ȸ/���</span></td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td class="space_top">- ���ں� ���θ��� ��� �ֹ���Ȳ �� �ֹ������� Ȯ��/ó���Ͻ� �� �ֽ��ϴ�.</td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td class="space_top">- �ֹ���ȣ�� Ŭ���ϸ� <b>�ֹ��󼼳���</b>�� ��µǸ�, �ֹ����� Ȯ�� �� �ֹ� ó���� �����մϴ�.</td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td class="space_top">- ����ũ��(������� ��ġ��) ������ ���� �ֹ��� ���Աݽ� 5�ϵڿ� ������ �����մϴ�.</td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<tdclass="space_top">- ī����� �ֹ����� 2�ð��Ŀ� ������ �����մϴ�.</td>
					</tr>
					<tr>
						<td colspan="2" height="20"></td>
					</tr>
					<tr>
						<td width="20" align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td><span class="font_dotline">�ϰ� ó�� �ΰ� ���</span></td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td class="space_top">- �������� : üũ�� �ֹ����� ������� �ϰ� ����մϴ�.(���� ���� �غ��߿� �ֽ��ϴ�.)</td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td class="space_top">- �ֹ������ : üũ�� �ֹ����� �Һ��ڿ� �ֹ����� �ϰ� ����մϴ�.</td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td class="space_top">- �����ٿ�ε� : üũ�� �ֹ����� �������� �������� �ٿ�ε� �޽��ϴ�.<br>
						<b>&nbsp;&nbsp;</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;���� �ֹ��� �׸� ������ <a href="javascript:parent.topframe.GoMenu(5,'order_excelinfo.php');"><span class="font_blue">�ֹ�/���� > �ֹ���ȸ �� ��۰��� > �ֹ�����Ʈ �������� ����</span></a> ���� �����մϴ�.</td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td class="space_top">- �ֹ������� : üũ�� �ֹ����� �ϰ� ���� �մϴ�.</td>
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
<iframe name="processFrm" frameborder="0" style="display:none"></iframe>
<?=$onload?>

<? INCLUDE "copyright.php"; ?>