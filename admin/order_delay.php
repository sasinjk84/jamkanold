<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
INCLUDE ("access.php");

####################### ������ ���ٱ��� check ###############
$PageCode = "or-1";
$MenuCode = "order";
if (!$_usersession->isAllowedTask($PageCode)) {
	INCLUDE ("AccessDeny.inc.php");
	exit;
}
#########################################################

function getDeligbn($arrdeli,$strdeli,$true=true) {
	$tempdeli=$arrdeli;
	$res=true;
	while(list($key,$val)=each($tempdeli)) {
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
$termday = (mktime(0,0,0,$tempend[1],$tempend[2],$tempend[0])-mktime(0,0,0,$tempstart[1],$tempstart[2],$tempstart[0]))/86400;

/*
if ($termday>31) {
	echo "<script>alert('�̹��/���Ա� �ֹ���ȸ �Ⱓ�� 1���� �ʰ��� �� �����ϴ�.');location='".$_SERVER[PHP_SELF]."';</script>";
	exit;
}
*/

$qry_from = "tblorderinfo a";
if($search_s) {
	if(substr($search_s,0,8)==substr($search_e,0,8)) {
		$qry.= "WHERE a.ordercode LIKE '".substr($search_s,0,8)."%' ";
	} else {
		$qry.= "WHERE a.ordercode>='".$search_s."' AND a.ordercode <='".$search_e."' ";
	}
}
else $qry.="WHERE a.ordercode!='' ";
if(strlen($deli_gbn)>0)	$qry.= "AND a.deli_gbn='".$deli_gbn."' ";
else $qry.= "AND a.deli_gbn!='Y' ";

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
	else if($s_check=="mn") $qry.= "AND a.sender_name='".$search."' ";
	else if($s_check=="mi") $qry.= "AND a.id='".$search."' ";
	else if($s_check=="cn") $qry.= "AND a.id LIKE 'X".$search."%' ";
}

$setup[page_num] = 10;
$setup[list_num] = 10;

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

?>

<? INCLUDE "header.php"; ?>

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

<script type="text/javascript" src="lib.js.php"></script>
<script type="text/javascript" src="calendar.js.php"></script>
<script language="JavaScript">



// ����Ʈ���� �Ա�ó��
function orderBankOK ( OrderCode ) {
	if( confirm( " \""+OrderCode+"\" �ֹ���\n ���� �Ա� �Ϸ� ó�� �Ͻðڽ��ϱ�?") ) {
		processFrm.location.href="order_list.bankOK.php?ordercode="+OrderCode;
		eval( "document.getElementById('orderBankOKobj_"+OrderCode+"').style.display='none';" );
		eval( "document.getElementById('orderBankOKOKobj_"+OrderCode+"').style.display='block';" );
	}
}

// ����Ʈ���� �ϰ� �Ա�ó��
function orderBankOkChkAll () {
	if( confirm( "���õ� ��� �ֹ���\n ���� �Ա� �Ϸ� ó�� �Ͻðڽ��ϱ�?") ) {
		var a = 0 ;
		var OrderCodeList = "";
		for(i=1;i<document.form2.chkordercode.length;i++) {
			if(document.form2.chkordercode[i].checked==true) {
				var OrderCode = document.form2.chkordercode[i].value.substring(1);
				try {
					OrderCodeList += OrderCode+",";
					eval( "document.getElementById('orderBankOKobj_"+OrderCode+"').style.display='none';" );
					eval( "document.getElementById('orderBankOKOKobj_"+OrderCode+"').style.display='block';" );
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
		processFrm.location.href="order_list.deliReady.php?delitype="+values+"&ordercode="+OrderCode;
	}
}

// ����Ʈ���� �ϰ� �߼��غ� ó��
function orderDeliReadyProductChkAll() {
	if( confirm( "���õ� ��� �ֹ��� �߼��غ� ó�� �Ͻðڽ��ϱ�?") ) {
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
			alert("�߼��غ� ó�� �� �ֹ��� �����ϼ���!");
			return;
		} else {
			processFrm.location.href="order_list.deliReady.php?delitype=S&ordercode="+OrderCodeList;
		}

	}
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
	document.form1.action="order_delay.php";
	document.form1.submit();
}

function OrderDetailView(ordercode) {
	document.detailform.ordercode.value = ordercode;
	window.open("","orderdetail","scrollbars=yes,width=700,height=600");
	document.detailform.submit();
}

function OnChangePeriod(val) {
	var pForm = document.form1;
	var period = new Array(7);
	period[0] = "<?=$period[0]?>";
	period[1] = "<?=$period[1]?>";
	period[2] = "<?=$period[2]?>";
	period[3] = "<?=$period[3]?>";
	period[4] = "";

	pForm.search_start.value = period[val];
	if(val==4) pForm.search_end.value = '';
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
	parent.topframe.ChangeMenuImg(4);
	document.member_form.search.value=id;
	document.member_form.submit();
}

function SenderSearch(sender) {
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
      document.form2.chkordercode[i].checked=chkval;
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

function OrderSendSMS() {
	document.smsform.ordercodes.value="";
	for(i=1;i<document.form2.chkordercode.length;i++) {
		if(document.form2.chkordercode[i].checked==true) {
			document.smsform.ordercodes.value+="'"+document.form2.chkordercode[i].value.substring(1)+"',";
		}
	}
	if(document.smsform.ordercodes.value.length==0) {
		alert("SMS�� �߼��� �ֹ����� �����ϼ���.");
		return;
	}
	window.open("about:blank","sendsmspop","width=220,height=350,scrollbars=no");
	document.smsform.type.value="order";
	document.smsform.submit();
}

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
			<? include ("menu_order.php"); ?>
			</td>

			<td></td>
			<td valign="top">




<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td height="29" colspan="3">
			<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td height="28" class="link" align="left" background="images/con_link_bg.gif"><img src="images/top_link_house.gif" border="0" valign="absmiddle">������ġ : �ֹ�/���� &gt; �ֹ���ȸ �� ��۰��� &gt; <span class="2depth_select">�̹��/���Ա� �ֹ�����</span></td>
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
					<TD><IMG SRC="images/order_delay_title.gif"ALT=""></TD>
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
					<TD width="100%" class="notice_blue">�̹��/���Ա� ó���� �ֹ� ������ �Ͻ� �� �ֽ��ϴ�.</TD>
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
				<td height=3></td>
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
							<img src=images/btn_today01.gif border=0 align=absmiddle style="cursor:hand" onclick="OnChangePeriod(0)">
							<img src=images/btn_day07.gif border=0 align=absmiddle style="cursor:hand" onclick="OnChangePeriod(1)">
							<img src=images/btn_day14.gif border=0 align=absmiddle style="cursor:hand" onclick="OnChangePeriod(2)">
							<img src=images/btn_day30.gif border=0 align=absmiddle style="cursor:hand" onclick="OnChangePeriod(3)">
							<img src=images/btn_all.gif border=0 align=absmiddle style="cursor:hand" onclick="OnChangePeriod(4)" alt='��ü' >
							</TD>
						</TR>
						<TR>
							<TD colspan="2" background="images/table_con_line.gif"></TD>
						</TR>
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
							$ardg=array("\"\":��ü����","S:�߼��غ�","N:��ó��","C:�ֹ����","R:�ݼ�","D:��ҿ�û");
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
						</td>
					</tr>
					</table>
					</td>
				</tr>
				</table>
				</td>
			</tr>
			<tr>
				<td style="padding-top:4pt;" align="right"><a href="javascript:searchForm();"><img src="images/botteon_search.gif" width="113" height="38" border="0"></a></td>
			</tr>
			</form>
			<tr>
				<td height="20"></td>
			</tr>
			<form name=form2 action="<?=$_SERVER[PHP_SELF]?>" method=post>
			<tr>
				<td style="padding-bottom:3pt;">
<?
				$arpm=array("B"=>"������","V"=>"������ü","O"=>"�������","Q"=>"�������(�Ÿź�ȣ)","C"=>"�ſ�ī��","P"=>"�ſ�ī��(�Ÿź�ȣ)","M"=>"�ڵ���");

				$sql = "SELECT a.* FROM ".$qry_from." ".$qry." ";
				$sql.= "GROUP BY a.ordercode ORDER BY a.ordercode ".$orderby." ";
				$sql.= "LIMIT " . ($setup[list_num] * ($gotopage - 1)) . ", " . $setup[list_num];
				$result=mysql_query($sql,get_db_conn());

				$colspan=10;
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
				</tr>
				</table>
				</td>
			</tr>
			<tr>
				<td>
				<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0 style="table-layout:fixed">
				<col width=30></col>
				<col width=65></col>
				<col width=100></col>
				<?if($vendercnt>0){?>
				<col width=60></col>
				<?}?>
				<col width=></col>
				<col width=30></col>
				<col width=60></col>
				<col width=60></col>
				<col width=60></col>
				<col width=60></col>
				<col width=40></col>
				<input type=hidden name=chkordercode>
				<TR>
					<TD background="images/table_top_line.gif" colspan="<?=$colspan?>"></TD>
				</TR>
				<TR height=32>
					<TD class="table_cell5" align="center"><input type=checkbox name=allcheck onclick="CheckAll()"></TD>
					<TD class="table_cell6" align="center">�ֹ�����</TD>
					<TD class="table_cell6" align="center">�ֹ��� ����</TD>
					<?if($vendercnt>0){?>
					<TD class="table_cell6" align="center">������ü</TD>
					<?}?>
					<TD class="table_cell6" align="center">��ǰ��</TD>
					<TD class="table_cell6" align="center">����</TD>
					<TD class="table_cell6" align="center">��ۿ���</TD>
					<TD class="table_cell6" align="center">�����ݾ�</TD>
					<TD class="table_cell6" align="center">�����ݾ�</TD>
					<TD class="table_cell6" align="center">ó���ܰ�</TD>
					<TD class="table_cell6" align="center">���</TD>
				</TR>
				<TR>
					<TD colspan="<?=$colspan?>" background="images/table_con_line.gif"></TD>
				</TR>
<?
				$colspan=10;
				if($vendercnt>0) $colspan++;

				$curdate = date("YmdHi",mktime(date("H")-2,date("i"),0,date("m"),date("d"),date("Y")));
				$curdate5 = date("Ymd",mktime(0,0,0,date("m"),date("d")-5,date("Y")));
				$cnt=0;
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

					$sql = "SELECT * FROM tblorderproduct WHERE ordercode='".$row->ordercode."' ";
					$sql.= "AND ordercode='".$row->ordercode."' ";
					$sql.= "AND NOT (productcode LIKE 'COU%' OR productcode LIKE '999999%') ";
					if(strlen($search)>0 && $s_check=="pn") {
						$sql.= "AND productname LIKE '%".$search."%' ";
					}
					$result2=mysql_query($sql,get_db_conn());
					$jj=0;
					unset($prval);
					unset($arrdeli);
					while($row2=mysql_fetch_object($result2)) {
						$arrdeli[$row2->deli_gbn]=$row2->deli_gbn;
						if($jj>0) $prval.="<tr><td colspan=".($vendercnt>0?"4":"3")." height=1 bgcolor=#E7E7E7></tr>";
						$prval.="<tr>\n";
						if($vendercnt>0) {
							$prval.="	<td class=\"td_con5\" align=center style=\"font-size:8pt\">".(strlen($venderlist[$row2->vender]->vender)>0?"<B><a href=\"javascript:viewVenderInfo(".$row2->vender.")\">".$venderlist[$row2->vender]->id."</a></B>":"-")."</td>\n";
							$prval.="	<td class=\"td_con6\" style=\"font-size:8pt;padding:3;line-height:10pt\">".titleCut(58,$row2->productname)."";
						} else
							$prval.="	<td class=\"td_con5\" style=\"font-size:8pt;padding:3;line-height:10pt\">".titleCut(58,$row2->productname)."";
						if(substr($row2->productcode,-4)!="GIFT") {
							$prval.=" <a href=\"JavaScript:ProductInfo('".substr($row2->productcode,0,12)."','".$row2->productcode."','YES')\"><img src=images/newwindow.gif border=0 align=absmiddle></a>";
						}
						$prval.="	</td>\n";
						$prval.="	<td class=\"td_con6\" align=center style=\"font-size:8pt;\">".$row2->quantity."</td>\n";
						$prval.="	<td class=\"td_con6\" align=center style=\"font-size:8pt;padding:3\">";
						switch($row2->deli_gbn) {
							case 'S': $prval.="�߼��غ�";  break;
							case 'X': $prval.="��ۿ�û";  break;
							case 'Y': $prval.="���";  break;
							case 'D': $prval.="<font color=blue>��ҿ�û</font>";  break;
							case 'N': $prval.="��ó��";  break;
							case 'E': $prval.="<font color=red>ȯ�Ҵ��</font>";  break;
							case 'C': $prval.="<font color=red>�ֹ����</font>";  break;
							case 'R': $prval.="�ݼ�";  break;
							case 'H': $prval.="���(<font color=red>���꺸��</font>)";  break;
						}
						if($row2->deli_gbn=="D" && strlen($row2->deli_date)==14) $prval.=" (���)";
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

					/*
					echo "<tr bgcolor=".$thiscolor." onmouseover=\"this.style.background='#FEFBD1'\" onmouseout=\"this.style.background='".$thiscolor."'\">\n";
					echo "<td class=\"td_con5\" align=center style=\"font-size:8pt;line-height:10pt\"><input type=checkbox name=chkordercode value=\"".$delgbn.$row->ordercode."\" ><br>".$number."</td>\n";
					echo "	<td class=\"td_con6\" align=center style=\"font-size:8pt;padding:3;line-height:11pt\"><A HREF=\"javascript:OrderDetailView('".$row->ordercode."')\">".$date."</A></td>\n";
					echo "	<td class=\"td_con6\" style=\"font-size:8pt;padding:3;line-height:11pt\">\n";
					echo "	�ֹ���: <A HREF=\"javascript:SenderSearch('".$name."');\"><FONT COLOR=\"blue\">".$name."</font></A>";
					if(strlen($stridX)>0) {
						echo "<br> �ֹ���ȣ: ".$stridX;
					} else if(strlen($stridM)>0) {
						echo "<br> ���̵�: ".$stridM;
					}


					$order_msg=explode("[MEMO]",$row->order_msg);
					if(strlen($row->order_msg)>0 || $row->paymethod=="B") {
						echo "	<br> �޼���:&nbsp;<img src=\"images/btn_memo.gif\" width=\"35\" height=\"29\" border=\"0\" onMouseOver='MemoMouseOver($cnt)' onMouseOut=\"MemoMouseOut($cnt);\" align=\"absmiddle\">";
						echo "	<div id=memo".$cnt." style=\"position:absolute; z-index:100; visibility:hidden;\">\n";
						echo "	<table width=400 border=0 cellspacing=0 cellpadding=0 bgcolor=#A47917>\n";
						echo "	<tr>\n";
						echo "		<td width=80 nowrap></td>\n";
						echo "		<td width=100%></td>\n";
						echo "	</tr>\n";
						if(strlen($order_msg[0])>0) {
							echo "	<tr>\n";
							echo "		<td align=right style=\"padding-right:5\"><font color=#ffffff>�� �� �� :</td>\n";
							echo "		<td style=\"padding-left:5;padding-right:10;line-height:12pt\"><font color=#FFFFFF>".strip_tags($order_msg[0])."</td>\n";
							echo "	</tr>";
						}
						if(strlen($order_msg[1])>0) {
							echo "	<tr><td colspan=2 height=10></td></tr>\n";
							echo "	<tr>\n";
							echo "		<td align=right style=\"padding-right:5\"><font color=#ffffff>�ֹ��޸� :</td>\n";
							echo "		<td style=\"padding-left:5;padding-right:10;line-height:12pt\"><font color=#FFFFFF>".strip_tags($order_msg[1])."</td>\n";
							echo "	</tr>";
						}
						if(strlen($order_msg[2])>0) {
							echo "	<tr><td colspan=2 height=10></td></tr>\n";
							echo "	<tr>\n";
							echo "		<td align=right style=\"padding-right:5\"><font color=#ffffff>�� �� �� :</td>\n";
							echo "		<td style=\"padding-left:5;padding-right:10;line-height:12pt\"><font color=#FFFFFF>".strip_tags($order_msg[2])."</td>\n";
							echo "	</tr>";
						}
						if($row->paymethod=="B") {
							echo "	<tr><td colspan=2 height=10></td></tr>\n";
							echo "	<tr>\n";
							echo "		<td align=right style=\"padding-right:5\"><font color=#ffffff>�Աݰ��� :</td>\n";
							echo "		<td style=\"padding-left:5;padding-right:10\"><font color=#FFFFFF>".$row->pay_data."</td>\n";
							echo "	</tr>\n";
						}
						echo "	</table>\n";
						echo "	</div>\n";
					}

					echo "	</td>\n";
					*/


					echo "<tr bgcolor=".$thiscolor." onmouseover=\"this.style.background='#FEFBD1'\" onmouseout=\"this.style.background='".$thiscolor."'\">\n";
					echo "<td class=\"td_con5\" align=center style=\"font-size:8pt;line-height:10pt\"><input type=checkbox name=chkordercode value=\"".$delgbn.$row->ordercode."\"><br>".$number."</td>\n";
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
						echo "<td><A HREF=\"javascript:MemberSMS('".$row->sender_tel."')\" title='".$row->sender_tel."'><IMG src=\"images/member_mobile.gif\" align=absMiddle border=0></A></td>";
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



					echo "	<td class=\"td_con6\" colspan=".($vendercnt>0?"4":"3")." height=100%>\n";
					echo "	<table border=0 cellpadding=0 cellspacing=0 width=100% height=100% style=\"table-layout:fixed\">\n";
					if($vendercnt>0) {
						echo "<col width=60></col>\n";
					}
					echo "	<col width=></col>\n";
					echo "	<col width=30></col>\n";
					echo "	<col width=60></col>\n";
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
							echo "<span id=\"orderBankOKobj_".$row->ordercode."\" style=\"cursor:pointer; display:block;\" onclick=\"orderBankOK('".$row->ordercode."');\"><img src='images/orderBankOK.gif' alt='�Ա�ó��'></span>";
							echo "<span id=\"orderBankOKOKobj_".$row->ordercode."\" style=\"cursor:pointer; display:none;\"><font color=004000> [�ԱݿϷ�]</font></span>";
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
					if($row->deli_gbn=="D" && strlen($row->deli_date)==14) echo "<br>(���)";
					if($row->deli_gbn=="R" && substr($row->ordercode,20)!="X") {
						echo "<br><button class=button2 style=\"width:45;color:blue\" onclick=\"ReserveInOut('".$row->id."');\">������</button>";
					}
					echo "	</td>\n";
					echo "	<td class=\"td_con6\" align=center>".$strdel."</td>\n";
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
					<a href="javascript:orderBankOkChkAll();"><img src="images/btn_bankOkAll.gif" border="0" alt='�ϰ� �Ա� Ȯ��'></a>
					<!-- <a href="javascript:orderDeliReadyOkChkAll();"><img src="images/btn_deliReadyOkAll.gif" border="0" alt='�ϰ� �߼� �غ�'></a> -->
				</td>
			</tr>

			<tr>
				<td style="padding-top:4pt;"><!-- <a href="javascript:OrderDeliPrint();"><img src="images/btn_print.gif" width="127" height="38" border="0" hspace="1"></a>&nbsp; --><a href="javascript:OrderCheckPrint();"><img src="images/btn_juprint.gif" width="127" height="38" border="0" hspace="0"></a>&nbsp;<!-- <a href="javascript:OrderCheckExcel();"><img src="images/btn_excel1.gif" width="127" height="38" border="0" hspace="1"></a>&nbsp; --><a href="javascript:OrderSendSMS();"><img src="images/btn_sms.gif" width="127" height="38" border="0"></a></td>
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
			</tr>
			<input type=hidden name=tot value="<?=$cnt?>">
			</form>

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
			<input type=hidden name=search value="<?=$search?>">
			<input type=hidden name=search_start value="<?=$search_start?>">
			<input type=hidden name=search_end value="<?=$search_end?>">
			<input type=hidden name=paymethod value="<?=$paymethod?>">
			<input type=hidden name=paystate value="<?=$paystate?>">
			<input type=hidden name=deli_gbn value="<?=$deli_gbn?>">
			</form>

			<form name=member_form action="member_list.php" method=post>
			<input type=hidden name=search>
			</form>

			<form name=sender_form action="order_namesearch.php" method=post>
			<input type=hidden name=search>
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
			<input type=hidden name=type>
			<input type=hidden name=ordercodes>
			<input type=hidden name=number>
			</form>

			<?if($vendercnt>0){?>
			<form name=vForm action="vender_infopop.php" method=post>
			<input type=hidden name=vender>
			</form>
			<?}?>

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
						<td ><span class="font_dotline">�̹��/���Ա� �ֹ�����</span></td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td  class="space_top">- �̹��/���Ա� ó���� �ֹ� ������ �Ͻ� �� �ֽ��ϴ�.</td>
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
						<td  class="space_top">- ī����� �ֹ����� 2�ð��Ŀ� ������ �����մϴ�.</td>
					</tr>
					<tr>
						<td colspan="2" height="20"></td>
					</tr>
					<tr>
						<td width="20" align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td><span class="font_dotline">�̹��/���Ա� �ֹ����� �ΰ����</span></td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td class="space_top">- �������� : üũ�� �ֹ����� ������� �ϰ� ����մϴ�.(���� ���� �غ��߿� �ֽ��ϴ�.)</td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td class="space_top">- �ֹ������ : üũ�� �ֹ����� �Һ��ڿ� �ֹ����� �ϰ� ����մϴ�.</td>
					</tr>
					<!-- <tr>
						<td width="20" align="right">&nbsp;</td>
						<td class="space_top">- �����ٿ�ε� : üũ�� �ֹ����� �������� �������� �ٿ�ε� �޽��ϴ�.<br>
						<b>&nbsp;&nbsp;</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;���� �ֹ��� �׸� ������ <a href="javascript:parent.topframe.GoMenu(5,'order_excelinfo.php');"><span class="font_blue">�ֹ�/���� > �ֹ���ȸ �� ��۰��� > �ֹ�����Ʈ �������� ����</span></a> ���� �����մϴ�.</td>
					</tr> -->
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td class="space_top">- SMS �߼� : üũ�� ��� �ֹ��ǿ� ���� SMS �����ð� �߼۸� �ߺ��� �޴��� ��ȣ�� 1���� ���ֵ˴ϴ�.<br>
						<b>&nbsp;&nbsp;</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;��ũ�θ� ����Ͽ� ���Ű��� �̸����� SMS�� �߼۵� �����մϴ�. ��) [NAME] ====> ����</td>
					</tr>
					<tr>
						<td colspan="2" height="20"></td>
					</tr>
					<tr>
						<td width="20" align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td><span class="font_dotline">�̹��/���Ա� �ֹ����� ���ǻ���</span></td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td class="space_top">- �̹��/���Ա� �ֹ���ȸ �Ⱓ�� 1���� �ʰ��� �� �����ϴ�.</td>
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