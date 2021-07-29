<?
session_start();
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/shopdata.php");
include_once($Dir."lib/venderlib.php");
INCLUDE ("access.php");

$CurrentTime = time();
$period[0] = date("Y-m-d",$CurrentTime);
$period[1] = date("Y-m-d",$CurrentTime-(60*60*24*7));
$period[2] = date("Y-m-d",$CurrentTime-(60*60*24*14));
$period[3] = date("Y-m-d",mktime(0,0,0,date("m")-1,date("d"),date("Y")));

$orderby=$_POST["orderby"];
if(strlen($orderby)==0) $orderby="DESC";

$paystate=$_POST["paystate"];
$deli_gbn=$_POST["deli_gbn"];
$s_check=$_POST["s_check"];
$search=$_POST["search"];
$search_start=$_POST["search_start"];
$search_end=$_POST["search_end"];
$vperiod=(int)$_POST["vperiod"];

$search_start=$search_start?$search_start:$period[0];
$search_end=$search_end?$search_end:date("Y-m-d",$CurrentTime);
$search_s=$search_start?str_replace("-","",$search_start."000000"):str_replace("-","",$period[0]."000000");
$search_e=$search_end?str_replace("-","",$search_end."235959"):date("Ymd",$CurrentTime)."235959";

${"check_vperiod".$vperiod} = "checked";

$tempstart = explode("-",$search_start);
$tempend = explode("-",$search_end);
$termday = (mktime(0,0,0,$tempend[1],$tempend[2],$tempend[0])-mktime(0,0,0,$tempstart[1],$tempstart[2],$tempstart[0]))/86400;
if ($termday>366) {
	echo "<script>alert('�˻��Ⱓ�� 1���� �ʰ��� �� �����ϴ�.');location='".$_SERVER[PHP_SELF]."';</script>";
	exit;
}

//tblorderinfo, tblorderproduct, rent_schedule
//$qry.= "WHERE a.ordercode=b.ordercode AND a.ordercode=rs.ordercode ";
$qry= "WHERE b.vender='".$_VenderInfo->getVidx()."' ";
if(substr($search_s,0,8)==substr($search_e,0,8)) {
	$qry.= "AND a.ordercode LIKE '".substr($search_s,0,8)."%' ";
} else {
	$qry.= "AND a.ordercode>='".$search_s."' AND a.ordercode <='".$search_e."' ";
}
$qry.= "AND NOT (b.productcode LIKE 'COU%' OR b.productcode LIKE '999999%') ";
if($paystate=="Y") {		//�Ա�
	$qry.= "AND ((MID(a.paymethod,1,1) IN ('B','O','Q') AND LENGTH(a.bank_date)=14) OR (MID(a.paymethod,1,1) IN ('C','P','M','V') AND a.pay_admin_proc!='C' AND a.pay_flag='0000')) ";
} else if($paystate=="B") {	//���Ա�
	$qry.= "AND ((MID(a.paymethod,1,1) IN ('B','O','Q') AND (a.bank_date IS NULL OR a.bank_date='')) OR (MID(a.paymethod,1,1) IN ('C','P','M','V') AND a.pay_flag!='0000' AND a.pay_admin_proc='C')) ";
} else if($paystate=="C") {	//ȯ��
	$qry.= "AND ((MID(a.paymethod,1,1) IN ('B','O','Q') AND LENGTH(a.bank_date)=9) OR (MID(a.paymethod,1,1) IN ('C','P','M','V') AND a.pay_flag='0000' AND a.pay_admin_proc='C')) ";
}
if(strlen($search)>0) {
	if($s_check=="cd") $qry.= "AND a.ordercode='".$search."' ";
	else if($s_check=="pn") $qry.= "AND b.productname LIKE '%".$search."%' ";
	else if($s_check=="mn") $qry.= "AND a.sender_name='".$search."' ";
	else if($s_check=="mi") $qry.= "AND a.id='".$search."' ";
	else if($s_check=="cn") $qry.= "AND a.id='".$search."X' ";
}

//if(strlen($deli_gbn)>0)	$qry.= "AND b.deli_gbn='".$deli_gbn."' ";
for($i=0;$i<strlen($deli_gbn);$i++){
	if(strlen($deli_gbn[$i])>0){
		$deliArr .= "'".$deli_gbn[$i]."',";
	}
}
if($deliArr){
	$deliArr = substr($deliArr,0,strlen($deliArr) - 1);
	$stQry.= " OR b.deli_gbn in (".$deliArr.")";
}

//�Աݴ��,����Ȯ��,�뿩,�뿩�Ϸ�,�ݳ��Ϸ�,�ݳ��Ұ�
for($i=0;$i<strlen($status);$i++){
	if(strlen($status[$i])>0){
		$statusArr .= "'".$status[$i]."',";
	}
}
if($statusArr){
	$statusArr = substr($statusArr,0,strlen($statusArr) - 1);
	$stQry.= " OR rs.status in (".$statusArr.")";
}

//����Ұ�
if(strlen($prd_status)>0){
	$stQry.= " OR b.prd_status='".$prd_status."'";
}

if($stQry){	
	$qry.= " AND (1<>1 ".$stQry.")";
}


$setup[page_num] = 10;
$setup[list_num] = $_POST["list_num"]? $_POST["list_num"] : 20;

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

$sql = "SELECT COUNT(DISTINCT(a.ordercode)) as t_count FROM tblorderinfo a left join tblorderproduct b on a.ordercode=b.ordercode ";
$sql.= "left join rent_schedule rs on a.ordercode=rs.ordercode ".$qry." ";
$result = mysql_query($sql,get_db_conn());
$row = mysql_fetch_object($result);
$t_count = $row->t_count;
mysql_free_result($result);
$pagecount = (($t_count - 1) / $setup[list_num]) + 1;

if ($_POST['mode'] == "statusUP") {
	$status_gbn = $_POST['status_gbn'];
	$products   = $_POST['products'];

	$products = explode(',', $products);
	for ($i=0,$end=count($products);$i<$end;$i++) {
		mysql_query("UPDATE tblorderproduct SET prd_status = '{$status_gbn}',prd_status_date= '".date("YmdHis")."' WHERE uid = ".$products[$i],get_db_conn());

		$sql = "SELECT * FROM tblorderinfo ordinfo, tblorderproduct ordprd WHERE ordinfo.ordercode=ordprd.ordercode AND ordprd.uid = ".$products[$i];
		$result = mysql_query($sql, get_db_conn());
		$_ord = mysql_fetch_object($result);
		mysql_free_result($result);

		if(strlen($_ord->receiver_tel1)>0) {
			$sql ="SELECT * FROM tblsmsinfo WHERE (mem_delivery='Y' OR mem_delinum='Y') ";
			$result=mysql_query($sql,get_db_conn());
			if($rowsms=mysql_fetch_object($result)) {
				$sms_id=$rowsms->id;
				$sms_authkey=$rowsms->authkey;

				$deliprice=$_ord->price;
				$deliname=$_ord->sender_name;
				$shopname = $_data->shopname;
/*
				switch($status_gbn) {
					//case "D": $status = "��������"; break;
					case "F": $status = "����Ұ�(��ǰ�ҷ�)"; break;
					case "W": $status = "����Ұ�(������)"; break;
					case "I": $status = "����Ұ�(��ǰ��������)"; break;
					case "Y": $status = "����Ȯ��"; break;
					//default: $status = "üũ��û";
				}

				$msg_delivery_statue="[".strip_tags($shopname)."] ".$_ord->sender_name."���Բ��� �ֹ��Ͻ� ��ǰ�� [".$status."] �����̴� Ȯ�ιٶ��ϴ�.";
*/
				switch($status_gbn) {
					case "F": $msg_delivery_statue = "���Բ��� �ֹ��Ͻ� [".$_ord->productname."]�� ���� ����Ұ�(��ǰ�ҷ�)�� ���� �ֹ���� �� ȯ�� �����Դϴ�."; break;
					case "W": $msg_delivery_statue = "���Բ��� �ֹ��Ͻ� [".$_ord->productname."]�� ���� ����Ұ�(������)�� ���� �ֹ���� �� ȯ�� �����Դϴ�."; break;
					case "I": $msg_delivery_statue = "���Բ��� �ֹ��Ͻ� [".$_ord->productname."]�� ���� ����Ұ�(��ǰ��������)�� ���� �ֹ���� �� ȯ�� �����Դϴ�."; break;
					case "Y": $msg_delivery_statue = "���Բ��� �ֹ��Ͻ� [".$_ord->productname."]�� ���� ������ Ȯ���Ǿ����ϴ�."; break;
				}

				


				$fromtel=$rowsms->return_tel;
				$etcmsg="�����ǰ���¸޼���(ȸ��)";
				$temp=SendSMS($sms_id, $sms_authkey, $_ord->receiver_tel1, "", $fromtel, date("YmdHis"), $msg_delivery_statue, $etcmsg);
			}
			mysql_free_result($result);
		}
	}
}


//�� ��ǰ��
$allCnt=0;
$sql = "SELECT COUNT(DISTINCT(a.ordercode)) as cnt FROM tblorderinfo a left join tblorderproduct b on a.ordercode=b.ordercode ";
$sql.= "left join rent_schedule rs on a.ordercode=rs.ordercode ".$qry." ";
$result = mysql_query($sql,get_db_conn());
$row = mysql_fetch_object($result);
$allCnt = $row->cnt;

//���� ��Ź��ǰ��
$sql = "SELECT COUNT(DISTINCT(a.ordercode)) as cnt FROM tblorderinfo a left join tblorderproduct b on a.ordercode=b.ordercode ";
$sql.= "left join rent_schedule rs on a.ordercode=rs.ordercode ";
$sql.= "left join tblproduct p on b.productcode=p.productcode ";
$sql.= "left join rent_product r on p.pridx=r.pridx ".$qry." ";
$sql.= "AND r.trust_vender='".$_VenderInfo->getVidx()."' and r.istrust ='0' ";
$sql.= "AND p.vender<>'".$_VenderInfo->getVidx()."' ";
$result = mysql_query($sql,get_db_conn());
$row = mysql_fetch_object($result);
$takeCnt = $row->cnt;


//���� ��Ź��ǰ��
$sql = "SELECT COUNT(DISTINCT(a.ordercode)) as cnt FROM tblorderinfo a left join tblorderproduct b on a.ordercode=b.ordercode ";
$sql.= "left join rent_schedule rs on a.ordercode=rs.ordercode ";
$sql.= "left join tblproduct p on b.productcode=p.productcode ";
$sql.= "left join rent_product r on p.pridx=r.pridx ".$qry." ";
$sql.= "AND r.trust_vender<>'".$_VenderInfo->getVidx()."' and r.istrust ='0' ";
$sql.= "AND p.vender='".$_VenderInfo->getVidx()."' ";
$result = mysql_query($sql,get_db_conn());
$row = mysql_fetch_object($result);
$giveCnt = $row->cnt;
?>

<? INCLUDE "header.php"; ?>
<style>
	#orderDeliForm{border:0px;padding:0px;margin:0px;}
</style>
<script type="text/javascript" src="lib.js.php"></script>
<script type="text/javascript" src="calendar.js.php"></script>

<link href="/js/jquery-ui-1.11.4/jquery-ui.css" rel="stylesheet">
<script src="/js/jquery-ui-1.11.4/external/jquery/jquery.js"></script>
<script src="/js/jquery-ui-1.11.4/jquery-ui.js"></script>

<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>-->


<script language="JavaScript">
$(document).ready(function() {

	$('.search_all').click(function() {
		if( $(this).is(":checked") ) {
			$('.search_status').prop("checked",true);
		}
		else {
			$('.search_status').prop("checked",false);
		}
	})

	$('.search_status').on("change",function() {
		if($('.search_status:not(:checked)').length==0){
             $('.search_all').prop("checked",true);
		}
		else {
             $('.search_all').prop("checked",false);
		}
	})

})




function searchForm() {
	document.sForm.submit();
}


function searchForm2(val) {
	document.sForm.list_num.value=val;
	document.sForm.submit();
}

function OrderDetailView(ordercode) {
	document.detailform.ordercode.value = ordercode;
	window.open("","vorderdetail","scrollbars=yes,width=800,height=600");
	document.detailform.submit();
}

function searchSender(name) {
	document.sForm.s_check.value="mn";
	document.sForm.search.value=name;
	document.sForm.submit();
}

function searchId(id) {
	document.sForm.s_check.value="mi";
	document.sForm.search.value=id;
	document.sForm.submit();
}

function CheckAll(){
   chkval=document.form2.allcheck.checked;
   cnt=document.form2.tot.value;
   for(i=1;i<=cnt;i++){
      document.form2.chkordercode[i].checked=chkval;
   }
}

function CheckPrdAll() {
	if ( $('[name="allprdcheck"]').is(":checked") ) {
		$('.chk_no:not(:disabled)').prop("checked","checked");
		$('[name="allprdcheck2"]').prop("checked","checked");
	} else {
		$('.chk_no').prop("checked","");
		$('[name="allprdcheck2"]').prop("checked","");
	}
	/*
	if ( $('[name="allprdcheck"]').is(":checked") ) {
		$('.chkordprd:not(:disabled)').prop("checked","checked");
		$('[name="allprdcheck2"]').prop("checked","checked");
	} else {
		$('.chkordprd').prop("checked","");
		$('[name="allprdcheck2"]').prop("checked","");
	}
	*/
}

function CheckPrdAll2() {
	if ( $('[name="allprdcheck2"]').is(":checked")) {
		$('.chk_no').prop("checked","checked");
		$('[name="allprdcheck"]').prop("checked","checked");
	} else {
		$('.chk_no').prop("checked","");
		$('[name="allprdcheck"]').prop("checked","");
	}
	/*
	if ( $('[name="allprdcheck2"]').is(":checked") ) {
		$('.chkordprd').prop("checked","checked");
		$('[name="allprdcheck"]').prop("checked","checked");
	} else {
		$('.chkordprd').prop("checked","");
		$('[name="allprdcheck"]').prop("checked","");
	}
	*/
}

function GoPage(block,gotopage) {
	document.pageForm.block.value=block;
	document.pageForm.gotopage.value=gotopage;
	document.pageForm.submit();
}

function GoOrderby(orderby) {
	document.pageForm.block.value = "";
	document.pageForm.gotopage.value = "";
	document.pageForm.orderby.value = orderby;
	document.pageForm.submit();
}

function AddressPrint() {
	document.sForm.action="order_address_excel.php";
	document.sForm.target="processFrame";
	document.sForm.submit();
	document.sForm.action="";
	document.sForm.target="";
}

function OrderExcel() {
	document.sForm.action="order_excel.php";
	document.sForm.target="processFrame";
	document.sForm.submit();
	document.sForm.target="";
	document.sForm.action="";
}

function OrderCheckExcel() {
	document.checkexcelform.ordercodes.value="";
	for(i=1;i<document.form2.chkordercode.length;i++) {
		if(document.form2.chkordercode[i].checked==true) {
			document.checkexcelform.ordercodes.value+=document.form2.chkordercode[i].value.substring(0)+",";
		}
	}
	if(document.checkexcelform.ordercodes.value.length==0) {
		alert("�����Ͻ� �ֹ����� �����ϴ�.");
		return;
	}
	document.checkexcelform.action="order_excel.php";
	document.checkexcelform.target="processFrame";
	document.checkexcelform.submit();
	document.checkexcelform.target="";
}

function OrderDeliExcel() {
	document.checkexcelform.ordercodes.value="";
	for(i=1;i<document.form2.chkordercode.length;i++) {
		if(document.form2.chkordercode[i].checked==true) {
			document.checkexcelform.ordercodes.value+=document.form2.chkordercode[i].value.substring(0)+",";
		}
	}
	if(document.checkexcelform.ordercodes.value.length==0) {
		alert("�����Ͻ� �ֹ����� �����ϴ�.");
		return;
	}
	document.checkexcelform.action="order_delivery.execel.php";
	document.checkexcelform.target="processFrame";
	document.checkexcelform.submit();
	document.checkexcelform.target="";
}


function OrderDeliCodeUpdate () {
	var a = 0 ;
	var OrderCodeList = "";
	for(i=1;i<document.form2.chkordercode.length;i++) {
		if(document.form2.chkordercode[i].checked==true) {
			var OrderCode = document.form2.chkordercode[i].value.substring(0);
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

function ChangePrdStatus() {
	var chd = [], cnt = 0,
		prdStatus = $('#prdStatus').val();

	$('.chkordprd').each(function() {
		if ($(this).is(':checked')) {
			cnt++;
			chd.push($(this).val())
		}
	});

	if (cnt == 0) {
		alert("������ ��ǰ�� �����ϴ�. �ٽ� �������ֽʽÿ�.");
		$('#prdStatus').val("");
		return false;
	}

	if (confirm("������ ��ǰ�� ���¸� �����Ͻðڽ��ϱ�?")) {
		document.prdStatusForm.status_gbn.value = prdStatus;
		document.prdStatusForm.products.value = chd;

		document.prdStatusForm.submit();
	}
}

var NowYear=parseInt(<?=date('Y')?>);
var NowMonth=parseInt(<?=date('m')?>);
var NowDay=parseInt(<?=date('d')?>);
function getMonthDays(sYear,sMonth) {
	var Months_day = new Array(0,31,28,31,30,31,30,31,31,30,31,30,31)
	var intThisYear = new Number(), intThisMonth = new Number();
	datToday = new Date();													// ���� ���� ����

	intThisYear = parseInt(sYear);
	intThisMonth = parseInt(sMonth);

	if (intThisYear == 0) intThisYear = datToday.getFullYear();				// ���� ���� ���
	if (intThisMonth == 0) intThisMonth = parseInt(datToday.getMonth())+1;	// �� ���� ������ ���� -1 �� ���� �ŵ��� ����.


	if ((intThisYear % 4)==0) {													// 4�⸶�� 1���̸� (��γ����� ��������)
		if ((intThisYear % 100) == 0) {
			if ((intThisYear % 400) == 0) {
				Months_day[2] = 29;
			}
		} else {
			Months_day[2] = 29;
		}
	}
	intLastDay = Months_day[intThisMonth];										// ������ ���� ����
	return intLastDay;
}

function GoSearch(gbn) {
	switch(gbn) {
		case "TODAY":
			s_date = new Date(parseInt(NowYear), parseInt(NowMonth), parseInt(NowDay));
			$(".btn_day").removeClass("btn_on");$("#today").addClass("btn_on");
			break;
		case "7DAY":
			s_date = new Date(parseInt(NowYear), parseInt(NowMonth), parseInt(NowDay)-7);
			$(".btn_day").removeClass("btn_on");$("#7day").addClass("btn_on");
			break;
		case "TOMONTH":
			s_date = new Date(parseInt(NowYear), parseInt(NowMonth), parseInt(1));
			$(".btn_day").removeClass("btn_on");$("#tomonth").addClass("btn_on");
			break;
		case "PREMONTH":
			s_date = new Date(parseInt(NowYear), parseInt(NowMonth)-1, parseInt(1));
			$(".btn_day").removeClass("btn_on");$("#premonth").addClass("btn_on");
			break;
		default :
			s_date = new Date(parseInt(NowYear), parseInt(NowMonth), parseInt(NowDay));
			break;
	}

	s_year=parseInt((s_date.getMonth() < 1 ? s_date.getFullYear()-1 : s_date.getFullYear() ));
	s_month=parseInt((s_date.getMonth() < 1 ? 12 : s_date.getMonth() ));
	s_day=s_date.getDate();
	//e_year=NowYear;
	e_year=parseInt((s_date.getMonth() < 1 ? s_date.getFullYear()-1 : s_date.getFullYear() ));
	e_month=(gbn=="PREMONTH")? s_month : NowMonth;
	e_day=(gbn=="PREMONTH")? getMonthDays(s_year,s_month) : NowDay;

	s_month = s_month<10? "0"+s_month: s_month;
	s_day = s_day<10? "0"+s_day: s_day;
	e_month = e_month<10? "0"+e_month: e_month;
	e_day = e_day<10? "0"+e_day: e_day;
	document.sForm.s_date.value = s_year+"-"+s_month+"-"+s_day;
	document.sForm.e_date.value = e_year+"-"+e_month+"-"+e_day;
	document.sForm.search_gbn.value=gbn.toLowerCase();
}
</script>
<table border=0 cellpadding=0 cellspacing=0 width=100% height="100%" style="table-layout:fixed">
<col width=190></col>
<col width=20></col>
<col width=></col>
<col width=20></col>
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
					<td><img src="images/order_list_title.gif"></td>
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
				<table cellpadding="10" cellspacing="1" width="100%" bgcolor="#EFEFF2">
					<tr>
						<td  bgcolor="#F5F5F9" style="padding:20px">
							<table border=0 cellpadding=0 cellspacing=0 width=100%>
								<tr>
									<td class="notice_gray" height="20"><img src="images/icon_dot02.gif" border=0 hspace="4">�����翡�� ����� ��ǰ�� �ֹ����� �� �� �ֽ��ϴ�.</td>
								</tr>
								<tr>
									<td class="notice_gray" height="20"><img src="images/icon_dot02.gif" border=0 hspace="4">�ֹ����� Ŭ���� ��ǰ�� ���� ��� ������ Ȯ���� �� �ֽ��ϴ�.</td>
								</tr>
								<tr>
									<td class="notice_gray" height="20"><img src="images/icon_dot02.gif" border=0 hspace="4">���� ���θ�����  ���º���� ������ �ֹ���ȸ ���°��� �ڵ� �����˴ϴ�.(�ű��ֹ�/�Ա�Ȯ��/��۴ܰ�)</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr><td height=10></td></tr>
		<tr>
			<td>
				<table cellpadding="10" cellspacing="7" width="100%" bgcolor="#EFEFF2">
					<tr>
						<td bgcolor="#ffffff">
							<ul class="orderSearchTop">
								<li><a href="?gubun=">��ü <font class="<?=$gubun==""? "skyblue":"orderNum";?>"><?=$allCnt+$takeCnt?></font>��</a></li>
								<li>�� <a href="?gubun=me">�� ��ǰ �ֹ���ȸ <font class="<?=$gubun=="me"? "skyblue":"orderNum";?>"><?=$allCnt-$giveCnt?></font>��</a></li>
								<li>�� <a href="?gubun=take">������Ź �ֹ���ȸ <font class="<?=$gubun=="take"? "skyblue":"orderNum";?>"><?=$takeCnt?></font>��</a></li>
								<li>�� <a href="?gubun=give">������Ź �ֹ���ȸ <font class="<?=$gubun=="give"? "skyblue":"orderNum";?>"><?=$giveCnt?></font>��</a></li>
							</ul>
						</td>
					</tr>
				</table>
			</td>
		</tr>

		<!-- ó���� ���� ��ġ ���� -->
		<tr>
			<td>
				<form name=sForm action="<?=$_SERVER[PHP_SELF]?>" method=post>
				<input type="hidden" name="code" value="<?=$code?>">
				<input type="hidden" name="gubun" value="<?=$gubun?>">
				<input type="hidden" name="search_gbn" value="<?=$search_gbn?>">
				<input type="hidden" name="list_num" value="<?=$setup[list_num]?>">
				<div class="searchTab">
					<div class="searchTab1">
							<span class="searchTab1_1">
						����
						</span>
						<span class="searchTab1_2">
							<input type="checkbox" class="search_all" name="search_all" value="all" <?=($search_all=="all")?"checked":"";?> >��ü
							<input type="checkbox" class="search_status" name="status[]" value="BR" <?=strpos($statusArr,"BR")?"checked":"";?>>�Աݴ��
							<input type="checkbox" class="search_status" name="status[]" value="BO" <?=strpos($statusArr,"BO")?"checked":"";?>>����Ȯ��
							<input type="checkbox" class="search_status" name="deli_gbn[]" value="Y" <?=strpos($deliArr,"Y")?"checked":"";?>>���
							<input type="checkbox" class="search_status" name="status[]" value="BI" <?=strpos($statusArr,"BI")?"checked":"";?>>�뿩
							<input type="checkbox" class="search_status" name="status[]" value="BE" <?=strpos($statusArr,"BE")?"checked":"";?>>�뿩�Ϸ�
							<input type="checkbox" class="search_status" name="status[]" value="CE" <?=strpos($statusArr,"CE")?"checked":"";?>>�ݳ��Ϸ�
							<input type="checkbox" class="search_status" name="deli_gbn[]" value="D" <?=strpos($deliArr,"D")?"checked":"";?>>��ҿ�û
							<input type="checkbox" class="search_status" name="deli_gbn[]" value="C" <?=strpos($deliArr,"C")?"checked":"";?>>��ҿϷ�
							<input type="checkbox" class="search_status" name="prd_status" value="F,W,I" <?=($prd_status=="F,W,I")?"checked":"";?>>����Ұ�
							<input type="checkbox" class="search_status" name="status[]" value="NR" <?=strpos($statusArr,"NR")?"checked":"";?>>�ݳ��Ұ�
						</span>
					</div>
					<div class="searchTab3">
						<div class="searchTab3_1">
						�Ⱓ
						</div>
						<div class="searchTab3_2">
							<input type="text" name="search_start" id="s_date" value="<?=$search_start?>"> ~
							<input type="text" name="search_end" id="e_date" value="<?=$search_end?>">
							<script type="text/javascript">
								$( "#s_date" ).datepicker({dateFormat:"yy-mm-dd"});
								$( "#e_date" ).datepicker({dateFormat:"yy-mm-dd"});
							</script>
						</div>
						<div class="searchTab3_3">
							<input type="radio" name="s_check" value="cd" <?if($s_check=="cd")echo"checked";?>>�ֹ��ڵ�
							<input type="radio" name="s_check" value="pn" <?if($s_check=="pn")echo"checked";?>>��ǰ��
							<input type="radio" name="s_check" value="mn" <?if($s_check=="mn")echo"checked";?>>�ֹ��ڼ���
							<input type="radio" name="s_check" value="mi" <?if($s_check=="mi")echo"checked";?>>�ֹ�ȸ��ID
						</div>
					</div>
					<div class="searchTab4">
						
						<div class="searchTab4_1">
							<button type="button" class="btn_day <?=($search_gbn=="today")?"btn_on":"";?>" id="today" onclick="javascript:GoSearch('TODAY')">����</button>
							<button type="button" class="btn_day <?=($search_gbn=="7day")?"btn_on":"";?>" id="7day" onclick="javascript:GoSearch('7DAY')">7��</button>
							<button type="button" class="btn_day <?=($search_gbn=="tomonth")?"btn_on":"";?>" id="tomonth" onclick="javascript:GoSearch('TOMONTH')">�̹���</button>
							<button type="button" class="btn_day <?=($search_gbn=="premonth")?"btn_on":"";?>" id="premonth" onclick="javascript:GoSearch('PREMONTH')">������</button>
						</div>
						<div class="searchTab4_2">
							<input type="text" name="search" id="search" value="<?=$search?>" placeholder="�Է����� �ʰ� �˻��ϸ� ��ü�˻��˴ϴ�.">
						</div>
					</div>

					<div class="searchTab5">
						<button type="submit" class="searchBtn" onclick="javascript:searchForm()">�˻�</button>
					</div>
					
					<div class="clear"></div>
				</div>
				</form>
				
				<div class="tableTop">
					<div class="tableTop1_1">�˻���� ( �� <font class="skyblue"><?=number_format($t_count)?></font>�� )</div>
					<div class="tableTop1_2">�� �Ⱓ�� ���� ������ ��쿡�� �ִ� 6���������� �Ⱓ �� �˻��� �����մϴ�.</div>
					<div class="tableTop1_3">
						<select name="list_num" onchange="javascript:searchForm2(this.options[this.selectedIndex].value);">
							<option value="20" <?=$setup[list_num]==20? "selected":"";?>>20���� ����</option>
							<option value="30" <?=$setup[list_num]==30? "selected":"";?>>30���� ����</option>
							<option value="50" <?=$setup[list_num]==50? "selected":"";?>>50���� ����</option>
							<option value="100" <?=$setup[list_num]==100? "selected":"";?>>100���� ����</option>
							<option value="200" <?=$setup[list_num]==200? "selected":"";?>>200���� ����</option>
						</select>
					</div>
					<div class="tableTop2_1">
						<button type="text" onclick="javascript:OrderExcel()">��ü�ٿ�ε�</button> 
						<button type="text" onclick="javascript:OrderCheckExcel()">���ôٿ�ε�</button>
					</div>
					<div class="tableTop2_2">
						<?
						$sql2_ = "SELECT booking_confirm FROM tblvenderinfo WHERE vender='".$_VenderInfo->getVidx()."'";
						$res2_=mysql_query($sql2_,get_db_conn());
						$bcrow_=mysql_fetch_object($res2_);

						if($bcrow_->booking_confirm!="now"){
							$arrconfirmTime = explode(":",$bcrow_->booking_confirm);
							if($arrconfirmTime[0]=="00"){
								$confirmTime = $arrconfirmTime[1]."��";
							}else{
								$confirmTime = $arrconfirmTime[0]."�ð�";
							}
							echo "<span style='color:#ff0000'>�ֹ� �� ".$confirmTime." �̳� ���Ȯ�� �ٶ��ϴ�.</span>";
						}
						?>
						<button type="text" onclick="javascript:location.href='order_csvdelivery.php'">���ε�</button>
					</div>
				</div>

				 <!--A HREF="javascript:OrderExcel()"><img src=images/btn_orderexceldown.gif border=0 align=absmiddle></A>
					<A HREF="javascript:AddressPrint()"><img src=images/btn_addressdown.gif border=0 align=absmiddle></A-->

				<!--table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
				<col width=130></col>
				<col width=200></col>
				<col width=></col>
				<tr><td colspan=3 height=20></td></tr>
				<tr>
					<td>
					</td>
					<td valign=bottom style="padding-left:20">
					<B>���� :</B> 
					<?if($orderby=="DESC"){?>
					<A HREF="javascript:GoOrderby('ASC');" style="color:blue"><B>�ֹ����ڼ�<FONT COLOR="red">��</FONT></B></A>
					<?}else{?>
					<A HREF="javascript:GoOrderby('DESC');" style="color:blue"><B>�ֹ����ڼ�<FONT COLOR="red">��</FONT></B></A>
					<?}?>
					</td>
					<td align=right valign=bottom>
					�� �ֹ��� : <B><?=number_format($t_count)?></B>��, &nbsp;&nbsp;
					���� <B><?=$gotopage?>/<?=ceil($t_count/$setup[list_num])?></B> ������
					</td>
				</tr>
				<tr><td colspan=3 height=1 bgcolor=#cccccc></td></tr>
				</table-->

				<table border=0 cellpadding=0 cellspacing=1 width=100% bgcolor=E7E7E7 style="table-layout:fixed">
				<!-- ����,��ȣ,��ǰ��,������,�ֹ���,ID/�ֹ���ȣ,����,�Ǹűݾ�,��������,ó������ -->
				<form name=form2 action="<?=$_SERVER[PHP_SELF]?>" method=post>
				<col width=25></col>
				<col width=70></col>
				<col width=130></col>
				<col width=120></col>
				<col width=90></col>
				<col width=></col>
				<col width=30></col>
				<col width=35></col>
				<col width=60></col>
				<col width=60></col>
				<col width=60></col>
				<col width=60></col>
				<col width=90></col>
				<col width=80></col>
				<tr height=32 align=center bgcolor=F5F5F5>
					<input type=hidden name=chkordercode>
					<td><input type=checkbox name=allcheck onclick="CheckAll()"></td>
					<td><B>�ֹ�����</B></td>
					<td><B>�ֹ��ڵ�</B></td>
					<td>
						<select name="gubun_vender" onchange="javascript:SearchPrd3(this.options[this.selectedIndex].value);">
							<option value="">����</option>
							<option value="<?=$_VenderInfo->getVidx()?>::me" <?=$_VenderInfo->getVidx()."::me"==$gubun_vender? "selected":"";?>>����ǰ</option>
							<?
							$sql2 = "SELECT ta.ta_idx,ta.give_vender,ta.take_vender FROM tbltrustagree ta ";
							$sql2.= "WHERE (ta.give_vender='".$_VenderInfo->getVidx()."' OR ta.take_vender='".$_VenderInfo->getVidx()."') ";
							$sql2.= "AND ta.approve='Y' ";

							//$sql2 = "SELECT r.trust_vender,p.vender FROM tblproduct p left join rent_product r on p.pridx=r.pridx WHERE (p.vender='".$_VenderInfo->getVidx()."' or r.trust_vender is not null) AND r.istrust='0' GROUP BY r.trust_vender";
							$res2=mysql_query($sql2,get_db_conn());
							while($row2=mysql_fetch_object($res2)){
								if($_VenderInfo->getVidx()==$row2->take_vender){//������Ź	
									$search_vender = $row2->give_vender."::take";
									$vener_idx = $row2->give_vender;
								}else{	//������Ź
									$search_vender = $row2->take_vender."::give";
									$vener_idx = $row2->take_vender;
								}

								//if($_VenderInfo->getVidx()==$row2->vender) $search_vender = $row2->trust_vender."::give";	//������Ź
								//else $search_vender = $row2->vender."::take";	//������Ź
								$sql2_ = "SELECT com_name FROM tblvenderinfo WHERE vender='".$vener_idx."'";
								$res2_=mysql_query($sql2_,get_db_conn());
								$data2_=mysql_fetch_object($res2_);
							?>
							<option value="<?=$search_vender?>" <?=$search_vender==$gubun_vender? "selected":"";?>><?=$data2_->com_name?></option>
							<?
							}
							?>
						</select>
					</td>
					<td><B>�ֹ�������</B></td><!-- �ֹ���, ID, �ֹ���ȣ -->
					<td><B>��ǰ��</B></td>
					<td><input type='checkbox' name='allprdcheck' onclick="CheckPrdAll()"></td>
					<td><B>����</B></td>
					<td><B>�Ǹűݾ�</B></td>
					<td><B>�����ð�</B></td>
					<td><B>�ʰ��ð�</B></td>
					<td><B>����ó��</B></td>
					<td><B>ó������</B></td>
					<td><B>��������</B></td>
				</tr>
<?
				$colspan=11;
				$sql = "SELECT a.ordercode,a.id,a.paymethod,a.pay_data,a.bank_date,a.pay_flag,a.pay_auth_no, ";
				$sql.= "a.pay_admin_proc,a.escrow_result,a.sender_name,a.del_gbn,a.price ";
				$sql.= "FROM tblorderinfo a left join tblorderproduct b on a.ordercode=b.ordercode ";
				$sql.= "left join rent_schedule rs on a.ordercode=rs.ordercode ".$qry." ";
				$sql.= "GROUP BY a.ordercode ORDER BY a.ordercode ".$orderby." ";
				$sql.= "LIMIT " . ($setup[list_num] * ($gotopage - 1)) . ", " . $setup[list_num];
				$result=mysql_query($sql,get_db_conn());
				$i=0;
				while($row=mysql_fetch_object($result)) {
					$number = ($t_count-($setup[list_num] * ($gotopage-1))-$i);
					$date = substr($row->ordercode,0,4).".".substr($row->ordercode,4,2).".".substr($row->ordercode,6,2)." <br>".substr($row->ordercode,8,2).":".substr($row->ordercode,10,2);
					$name=$row->sender_name;
					unset($stridX);
					unset($stridM);
					if(substr($row->ordercode,20)=="X") {	//��ȸ��
						$stridX = substr($row->id,1,6);
					} else {	//ȸ��
						$stridM = "<A HREF=\"javascript:searchId('".$row->id."');\"><FONT COLOR=\"blue\">".$row->id."</FONT></A>";
					}
					echo "<tr bgcolor=#FFFFFF onmouseover=\"this.style.background='#FEFBD1'\" onmouseout=\"this.style.background='#FFFFFF'\">\n";
					echo "	<td align=center><input type='checkbox' name='chkordercode' value=\"".$row->ordercode."\"></td>\n";
					echo "	<td align=center style=\"padding:3;line-height:11pt\"><A HREF=\"javascript:OrderDetailView('".$row->ordercode."')\">".$date."</A></td>\n";
					echo "	<td align=center style=\"padding:3;line-height:11pt\"><A HREF=\"javascript:OrderDetailView('".$row->ordercode."')\">".$row->ordercode."</A></td>\n";

					//����(����ǰ,������Ź,������Ź)
					echo "	<td align=center style=\"font-size:8pt\">";
						$sql = "SELECT * FROM tblproduct p left join tblorderproduct op on op.productcode=p.productcode ";
						$sql.= "left join rent_product rp on p.pridx=rp.pridx ";
						$sql.= "WHERE op.vender='".$_VenderInfo->getVidx()."' AND op.ordercode='".$row->ordercode."' ";
						$tRes=mysql_query($sql,get_db_conn());
						$tData=mysql_fetch_object($tRes);
						if($tData->trust_vender==$_VenderInfo->getVidx()) $search_vender = $row->vender;	//������Ź
						else $search_vender = $tData->trust_vender;	//������Ź

						$sql2 = "SELECT com_name FROM tblvenderinfo WHERE vender='".$search_vender."'";
						$res2=mysql_query($sql2,get_db_conn());
						$data2=mysql_fetch_object($res2);
						if($tData->istrust==1) echo "�系��ǰ";
						else if($tData->istrust==0 && $tData->trust_vender==$_VenderInfo->getVidx()) echo "��(������Ź)<br>".$data2->com_name;
						else if($tData->istrust==0 && $tData->trust_vender<>$_VenderInfo->getVidx()) echo "��(������Ź)<br>".$data2->com_name;
					echo "	</td>\n";

					echo "	<td style=\"padding:3;line-height:11pt;text-align:center\">\n";
					echo "	<A HREF=\"javascript:searchSender('".$name."');\"><FONT COLOR=\"blue\">".$name."</font></A>";
					if(strlen($stridX)>0) {
						echo "<br> �ֹ���ȣ : ".$stridX;
					} else if(strlen($stridM)>0) {
						echo "<br> ".$stridM;
					}
					echo "	</td>\n";
					echo "	<td colspan=8>\n";
					echo "	<table border=0 cellpadding=0 cellspacing=0 width=100% style=\"table-layout:fixed\">\n";
					echo "	<col width=></col>\n";
					echo "	<col width=1></col>\n";
					echo "	<col width=30></col>\n";
					echo "	<col width=1></col>\n";
					//echo "	<col width=80></col>\n";
					//echo "	<col width=1></col>\n";
					echo "	<col width=35></col>\n";
					echo "	<col width=1></col>\n";
					echo "	<col width=60></col>\n";
					echo "	<col width=1></col>\n";
					echo "	<col width=60></col>\n";
					echo "	<col width=1></col>\n";
					echo "	<col width=60></col>\n";
					echo "	<col width=1></col>\n";
					echo "	<col width=60></col>\n";
					echo "	<col width=1></col>\n";
					echo "	<col width=90></col>\n";
					$sql = "SELECT * FROM tblorderproduct WHERE vender='".$_VenderInfo->getVidx()."' AND ordercode='".$row->ordercode."' ";
					$sql.= "AND NOT (productcode LIKE 'COU%' OR productcode LIKE '999999%') ";
					//if(strlen($deli_gbn)>0)	$sql.= "AND deli_gbn='".$deli_gbn."' ";
					if(strlen($search)>0 && $s_check=="pn") {
						$sql.= "AND productname LIKE '%".$search."%' ";
					}
					$result2=mysql_query($sql,get_db_conn());
					$jj=0;
					while($row2=mysql_fetch_object($result2)) {
						if($jj>0) echo "<tr><td colspan=15 height=1 bgcolor=#E7E7E7></tr>";
						echo "<tr>\n";
						echo "	<td style=\"padding:3;line-height:11pt\">".$row2->productname."</td>\n";
						echo "	<td bgcolor=#E7E7E7></td>\n";
						echo "	<td style=\"padding:3;line-height:11pt\">";
						
						if($tData->booking_confirm==""){
							$bookingConfirm = $bcrow_->booking_confirm;
						}else{
							$bookingConfirm = $tData->booking_confirm;
						}

						//����Ȯ�����Ŀ��� ��ǰ���� ������ϵ��� ����
						//if($row2->deli_gbn!="S" && $row2->deli_gbn!="X" && $row2->deli_gbn!="Y"){
						if($row2->deli_gbn!="X" && $row2->deli_gbn!="Y"){
							if($row2->prd_status=="N" && $bookingConfirm!="now"){
								$ordprd_status = "chk_no";
							}else{
								$ordprd_status = "";
							}
							echo "	<input type='checkbox' name='chkordprd' class='chkordprd ".$ordprd_status."' value=\"".$row2->uid."\">\n";
						}else{
							echo "	<input type='checkbox' name='chkordprd' class='chkordprd' value=\"".$row2->uid."\" disabled='disabled'>\n";
						}

						echo "	</td>\n";
						echo "	<td bgcolor=#E7E7E7></td>\n";
						
						echo "	<td align=center>".$row2->quantity."</td>\n";
						echo "	<td bgcolor=#E7E7E7></td>\n";
						echo "	<td align=right style=\"padding:3\">".number_format($row->price)."&nbsp;</td>\n";
						echo "	<td bgcolor=#E7E7E7></td>\n";
						echo "	<td align=center>";
						if($row->bank_date!="" && (preg_match("/^(B){1}/", $row->paymethod) || preg_match("/^(O|Q){1}/", $row->paymethod))) {
							echo substr($row->bank_date,4,2)."/".substr($row->bank_date,6,2)."<br>".substr($row->bank_date,8,2).":".substr($row->bank_date,10,2);
						}else{
						}
						echo "	</td>\n";
						echo "	<td bgcolor=#E7E7E7></td>\n";
						echo "	<td align=center>";

						if($row2->prd_status_date){
							$statusDate = mktime(substr($row2->prd_status_date,8,2),substr($row2->prd_status_date,10,2),substr($row2->prd_status_date,12,2),substr($row2->prd_status_date,4,2),substr($row2->prd_status_date,6,2),substr($row2->prd_status_date,0,4));
						}else{
							$statusDate = time();
						}
						$bankDate = mktime(substr($row->bank_date,8,2),substr($row->bank_date,10,2),substr($row->bank_date,12,2),substr($row->bank_date,4,2),substr($row->bank_date,6,2),substr($row->bank_date,0,4));

						$total_secs = abs($statusDate-$bankDate);
						$diff_in_days = floor($total_secs/86400);//�ʰ� ��¥
						$rest_hours = $total_secs%86400;
						$diff_in_hours = floor($rest_hours/3600);//�ʰ� �ð�
						$rest_mins = $rest_hours % 3600;
						$diff_in_mins = floor($rest_mins/60);//�ʰ� ��
						$diff_in_secs = floor($rest_mins%60);//�ʰ� ��
						$diff_text = "";
						if($diff_in_days>0) $diff_text .= $diff_in_days."�� ";
						if($diff_in_hours>0) $diff_text .= $diff_in_hours."�ð� ";
						if($diff_in_mins>0) $diff_text .= $diff_in_mins."�� ";
						if($diff_in_secs>0) $diff_text .= $diff_in_secs."�� ";
						
						if($row->bank_date && $bookingConfirm!="now"){
							if($row2->prd_status=="N"){
								echo "<span style='color:#ff0000'>".$diff_text."</span>";
							}else{
								echo $diff_text;
							}
						}
						echo "	</td>\n";
						echo "	<td bgcolor=#E7E7E7></td>\n";

						echo "	<td align=center>";

						if($bookingConfirm=="now"){
							switch($row2->prd_status) {
								case 'Y': echo "�ڵ�����Ȯ��"; break;
								case 'F': echo "����Ұ�(��ǰ�ҷ�)"; break;
								case 'W': echo "����Ұ�(������)"; break;
								case 'I': echo "����Ұ�(��ǰ��������)"; break;
								case 'N': echo "�ڵ�����Ȯ��"; break;
								default: echo "�ڵ�����Ȯ��";
							}
						}else{
							switch($row2->prd_status) {
								case 'Y': echo "����Ȯ��"; break;
								//case 'D': echo "��������"; break;
								case 'F': echo "����Ұ�(��ǰ�ҷ�)"; break;
								case 'W': echo "����Ұ�(������)"; break;
								case 'I': echo "����Ұ�(��ǰ��������)"; break;
								case 'N': echo "��������"; break;
								default: echo "��������";
							}
						}
						echo "</td>\n";
						echo "	<td bgcolor=#E7E7E7></td>\n";

						echo "	<td align=center style=\"padding:3\">";
						switch($row2->deli_gbn) {
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
						echo "	</td>\n";
						echo "</tr>\n";
						$jj++;
					}
					mysql_free_result($result2);
					echo "	</table>\n";
					echo "	</td>\n";
					echo "	<td align=center style=\"padding:3;line-height:12pt\">";
					if(preg_match("/^(B){1}/", $row->paymethod)) {	//������
						echo "������<br>";
						if (strlen($row->bank_date)==9 && substr($row->bank_date,8,1)=="X") echo "<font color=005000>[ȯ��]</font>";
						else if (strlen($row->bank_date)>0) {
							echo "<font color=004000>[�ԱݿϷ�]</font>";
						} else {
							echo "[�Աݴ��]";
						}
					} else if(preg_match("/^(V){1}/", $row->paymethod)) {	//������ü
						echo "������ü<br>";
						if (strcmp($row->pay_flag,"0000")!=0) echo "<font color=#757575>[��������]</font>";
						else if ($row->pay_flag=="0000" && $row->pay_admin_proc=="C") echo "<font color=005000>[ȯ��]</font>";
						else if ($row->pay_flag=="0000") {
							echo "<font color=0000a0>[�����Ϸ�]</font>";
						}
					} else if(preg_match("/^(M){1}/", $row->paymethod)) {	//�ڵ���
						echo "�ڵ���<br>";
						if (strcmp($row->pay_flag,"0000")!=0) echo "<font color=#757575>[��������]</font>";
						else if ($row->pay_flag=="0000" && $row->pay_admin_proc=="C") echo "<font color=005000>[��ҿϷ�]</font>";
						else if ($row->pay_flag=="0000") {
							echo "<font color=0000a0>[�����Ϸ�]</font>";
						}
					} else if(preg_match("/^(O|Q){1}/", $row->paymethod)) {	//�������
						echo "�������<br>";
						if (strcmp($row->pay_flag,"0000")!=0) echo "<font color=#757575>[�ֹ�����]</font>";
						else if ($row->pay_flag=="0000" && $row->pay_admin_proc=="C") echo "<font color=005000>[ȯ��]</font>";
						else if ($row->pay_flag=="0000" && strlen($row->bank_date)==0) echo "<font color=red>[���Ա�]</font>";
						else if ($row->pay_flag=="0000" && strlen($row->bank_date)>0) {
							echo "<font color=0000a0>[�ԱݿϷ�]</font>";
						}
					} else {
						echo "�ſ�ī��<br>";
						if (strcmp($row->pay_flag,"0000")!=0) echo "<font color=#757575>[ī�����]</font>";
						else if ($row->pay_flag=="0000" && $row->pay_admin_proc=="N") echo "<font color=red>[ī�����]</font>";
						else if ($row->pay_flag=="0000" && $row->pay_admin_proc=="Y") {
							echo "<font color=0000a0>[�����Ϸ�]</font>";
						}
						else if ($row->pay_flag=="0000" && $row->pay_admin_proc=="C") echo "<font color=005000>[��ҿϷ�]</font>";
					}
					echo "	</td>\n";
					echo "</tr>\n";
					$i++;
				}
				mysql_free_result($result);
				$cnt=$i;
				if($i==0) {
					echo "<tr height=28 bgcolor=#FFFFFF><td colspan=".$colspan." align=center>��ȸ�� ������ �����ϴ�.</td></tr>\n";
				} else if($i>0) {
					$total_block = intval($pagecount / $setup[page_num]);
					if (($pagecount % $setup[page_num]) > 0) {
						$total_block = $total_block + 1;
					}
					$total_block = $total_block - 1;
					if (ceil($t_count/$setup[list_num]) > 0) {
						// ����	x�� ����ϴ� �κ�-����
						$a_first_block = "";
						if ($nowblock > 0) {
							$a_first_block .= "<a href='javascript:GoPage(0,1);' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='ù ������';return true\"><img src=".$Dir."images/minishop/btn_miniprev_end.gif border=0 align=absmiddle></a> ";
							$prev_page_exists = true;
						}
						$a_prev_page = "";
						if ($nowblock > 0) {
							$a_prev_page .= "<a href='javascript:GoPage(".($nowblock-1).",".($setup[page_num]*($block-1)+$setup[page_num]).");' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='���� ".$setup[page_num]." ������';return true\"><img src=".$Dir."images/minishop/btn_miniprev.gif border=0 align=absmiddle></a> ";

							$a_prev_page = $a_first_block.$a_prev_page;
						}
						if (intval($total_block) <> intval($nowblock)) {
							$print_page = "";
							for ($gopage = 1; $gopage <= $setup[page_num]; $gopage++) {
								if ((intval($nowblock*$setup[page_num]) + $gopage) == intval($gotopage)) {
									$print_page .= "<FONT color=red><B>".(intval($nowblock*$setup[page_num]) + $gopage)."</B></font> ";
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
									$print_page .= "<FONT color=red><B>".(intval($nowblock*$setup[page_num]) + $gopage)."</B></FONT> ";
								} else {
									$print_page .= "<a href='javascript:GoPage(".$nowblock.",".(intval($nowblock*$setup[page_num]) + $gopage).");' onMouseOver=\"window.status='������ : ".(intval($nowblock*$setup[page_num]) + $gopage)."';return true\">[".(intval($nowblock*$setup[page_num]) + $gopage)."]</a> ";
								}
							}
						}
						$a_last_block = "";
						if ((intval($total_block) > 0) && (intval($nowblock) < intval($total_block))) {
							$last_block = ceil($t_count/($setup[list_num]*$setup[page_num])) - 1;
							$last_gotopage = ceil($t_count/$setup[list_num]);
							$a_last_block .= " <a href='javascript:GoPage(".$last_block.",".$last_gotopage.");' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='������ ������';return true\"><img src=".$Dir."images/minishop/btn_mininext_end.gif border=0 align=absmiddle></a>";
							$next_page_exists = true;
						}
						$a_next_page = "";
						if ((intval($total_block) > 0) && (intval($nowblock) < intval($total_block))) {
							$a_next_page .= " <a href='javascript:GoPage(".($nowblock+1).",".($setup[page_num]*($nowblock+1)+1).");' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='���� ".$setup[page_num]." ������';return true\"><img src=".$Dir."images/minishop/btn_mininext.gif border=0 align=absmiddle></a>";
							$a_next_page = $a_next_page.$a_last_block;
						}
					} else {
						$print_page = "<B>1</B>";
					}
					$pageing=$a_div_prev_page.$a_prev_page.$print_page.$a_next_page.$a_div_next_page;
				}
?>
				<input type=hidden name=tot value="<?=$cnt?>">
				</form>

				<form name=detailform method="post" action="order_detail.php" target="vorderdetail">
				<input type=hidden name=ordercode>
				</form>

				</table>
				<table border=0 cellpadding=0 cellspacing=0 width=100% style="margin-top:5px;">
					<tr>
						<td>
                        <? /*
							<a href="javascript:OrderDeliCodeUpdate();"><img src="images/btn_orderDeliCodeUpload.gif" border="0" alt="�߼۴���ֹ� �ϰ���� ����(�˾�)"></a>*/ ?>
							<!-- <a href="javascript:OrderDeliExcel();"><img src="images/btn_orderDeliExcelPrint.gif" border="0" alt="������� �ϰ� ���� ó�� �ٿ�ε�"></a>
							<a href="./order_csvdelivery.php"><img src="images/btn_orderDeliCodeExcelUpload.gif" border="0" alt="������� �ϰ� ���� ó�� �ٿ�ε�"></a> -->
						</td>
						<td style="text-align:right;padding-right:10px;">
							<input type='checkbox' name='allprdcheck2' onclick="CheckPrdAll2()"> �� ���û�ǰ ��ǰ���� 
							<select name="prdStatus" id="prdStatus">
								<option value="">����</option>
								<option value="Y">����Ȯ��</option>
								<option value="F">����Ұ�(��ǰ�ҷ�)</option>
								<option value="W">����Ұ�(������)</option>
								<option value="I">����Ұ�(��ǰ��������)</option>
								<!--
								<option value="N">üũ��û</option>
								<option value="D">��������</option>
								-->								
							</select> <input type="button" value="Ȯ��" onclick="ChangePrdStatus()">
						</td>
					</tr>
				</table>
				<table border=0 cellpadding=0 cellspacing=0 width=100%>
				<tr>
					<td align=center style="padding-top:10"><?=$pageing?></td>
				</tr>
				</table>

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

<form name="prdStatusForm" method="POST" action="<?=$_SERVER[PHP_SELF]?>">
<input type="hidden" name="status_gbn" />
<input type="hidden" name="products" />
<input type="hidden" name="mode" value="statusUP" />
</form>

<form name=pageForm method=post action="<?=$_SERVER[PHP_SELF]?>">
<input type=hidden name=search_start value="<?=$search_start?>">
<input type=hidden name=search_end value="<?=$search_end?>">
<input type=hidden name=s_check value="<?=$s_check?>">
<input type=hidden name=search value="<?=$search?>">
<input type=hidden name=paystate value="<?=$paystate?>">
<input type=hidden name=deli_gbn value="<?=$deli_gbn?>">
<input type=hidden name=orderby value="<?=$orderby?>">
<input type=hidden name=block>
<input type=hidden name=gotopage>
<input type="hidden" name="list_num" value="<?=$setup[list_num]?>">
</form>

<form name=checkexcelform action="order_excel.php" method=post>
<input type=hidden name=ordercodes>
</form>

</table>
<!-- �����ȣ �ϰ� ó���� ���� �� �߰� ���� -->
<form id="orderDeliForm" name=OrderDeliCodeUpdatePopForm>
	<input type=hidden name=ordercode>
</form>
<!-- �����ȣ �ϰ� ó���� ���� �� �߰� �� -->
<iframe name="processFrame" src="about:blank" width="600" height="400" scrolling=no frameborder=no></iframe>

<?=$onload?>

<? INCLUDE "copyright.php"; ?>