<?
$Dir="../";

include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/shopdata.php");
include_once($Dir."lib/ext/product_func.php");
include_once($Dir."lib/ext/member_func.php");
include_once($Dir."lib/ext/order_func.php");

if(strlen($_ShopInfo->getMemid())==0) {
	Header("Location:".$Dir.FrontDir."login.php?chUrl=".getUrl());
	exit;
}

$sql = "SELECT * FROM tblmember WHERE id='".$_ShopInfo->getMemid()."' ";
$result=mysql_query($sql,get_db_conn());
if($row=mysql_fetch_object($result)) {
	$_mdata=$row;
	if($row->member_out=="Y") {
		$_ShopInfo->SetMemNULL();
		$_ShopInfo->Save();
		echo "<html><head><title></title></head><body onload=\"alert('ȸ�� ���̵� �������� �ʽ��ϴ�.');location.href='".$Dir.FrontDir."login.php';\"></body></html>";exit;
	}

	if($row->authidkey!=$_ShopInfo->getAuthidkey()) {
		$_ShopInfo->SetMemNULL();
		$_ShopInfo->Save();
		echo "<html><head><title></title></head><body onload=\"alert('ó������ �ٽ� �����Ͻñ� �ٶ��ϴ�.');location.href='".$Dir.FrontDir."login.php';\"></body></html>";exit;
	}
	$url_id = $row->url_id;
}
mysql_free_result($result);



$selfcodefont_start = "<font class=\"prselfcode\">"; //�����ڵ� ��Ʈ ����
$selfcodefont_end = "</font>"; //�����ڵ� ��Ʈ ��


// ���� ����
$cdate = date("YmdH");
if($_data->coupon_ok=="Y") {
	$sql = "SELECT COUNT(*) as cnt FROM tblcouponissue WHERE id='".$_ShopInfo->getMemid()."' AND used='N' AND (date_end>='".$cdate."' OR date_end='') ";
	$result = mysql_query($sql,get_db_conn());
	$row = mysql_fetch_object($result);
	$coupon_cnt = $row->cnt;
	mysql_free_result($result);
} else {
	$coupon_cnt=0;
}



//�ֹ���Ȳ ����
$orderid = isset($_ShopInfo->memid)?trim($_ShopInfo->memid):"";

if(strlen($orderid) > 0){
	$oiSQL = "SELECT ";
	$oiSQL .= "COUNT(oi.ordercode) AS ordercount, "; // �ֹ���Ȳ ��
	$oiSQL .= "SUM(IF((oi.pay_admin_proc = 'Y' OR oi.pay_admin_proc = 'N') AND oi.deli_gbn = 'N',1,0)) AS delireadycount, "; // �߼��غ� ��
	$oiSQL .= "SUM(IF(oi.deli_gbn = 'Y', 1,0)) AS delicomplatecount, "; // �߼ۿϷ� ��
	$oiSQL .= "SUM(IF(op.status = 'RA',1,0)) AS refundcount, "; //ȯ�ҿ�û��
	$oiSQL .= "SUM(IF(op.status = 'RC',1,0)) AS repaymentcount "; //ȯ�ҿϷ��
	$oiSQL .= "FROM tblorderinfo AS oi LEFT OUTER JOIN tblorderproduct AS op ON(oi.ordercode = op.ordercode) ";
	$oiSQL .= "WHERE oi.id = '".$orderid."' ";

	$ordercount = "";
	$deliready = "";
	$delicomplate = "";
	$refund = "";
	$repayment = "";

	if(false !== $oiRes = mysql_query($oiSQL, get_db_conn())){
		$oiNumRow = mysql_num_rows($oiRes);
		if($oiNumRow > 0){
			$oiRow = mysql_fetch_assoc($oiRes);

			// �ֹ���Ȳ,�߼��غ�,�߼ۿϷ�� ��ü�ֹ����� ī���� ����
			// ȯ�ҽ�û,ȯ�ҿϷ�� ��ǰ������ ī���� ����
			$ordercount = $oiRow['ordercount']; // �ֹ���Ȳ ��
			$deliready = _empty($oiRow['delireadycount'])?0:$oiRow['delireadycount']; // �߼��غ� ��
			$delicomplate = _empty($oiRow['delicomplatecount'])?0:$oiRow['delicomplatecount']; // �߼ۿϷ� ��
			$refund = _empty($oiRow['refundcount'])?0:$oiRow['refundcount']; // ȯ�ҿ�û��
			$repayment = _empty($oiRow['repaymentcount'])?0:$oiRow['repaymentcount']; // ȯ�ҿ�û��

			/*
			$delicomplate = $oiRow['delicomplatecount']; //�߼ۿϷ� ��
			$refund = $oiRow['refundcount']; //ȯ�ҿ�û��
			$repayment = $oiRow['repaymentcount']; //ȯ�ҿ�û��*/
		}

		mysql_free_result($oiRes);
	}
}
//�ֹ���Ȳ ��
?>

<HTML>
<HEAD>
<TITLE><?=$_data->shoptitle?> - ����������</TITLE>
<META http-equiv="CONTENT-TYPE" content="text/html; charset=EUC-KR">

<META name="description" content="<?=(strlen($_data->shopdescription)>0?$_data->shopdescription:$_data->shoptitle)?>">
<META name="keywords" content="<?=$_data->shopkeyword?>">
<script type="text/javascript" src="<?=$Dir?>lib/lib.js.php"></script>
<script type="text/javascript" src="<?=$Dir?>lib/jquery-1.4.2.min.js"></script>
<?include($Dir."lib/style.php")?>
<SCRIPT LANGUAGE="JavaScript">
<!--
function OrderDetailPop(ordercode) {
	document.form2.ordercode.value=ordercode;
	window.open("about:blank","orderpop","width=610,height=500,scrollbars=yes");
	document.form2.submit();
}

/*function OrderReview(ordercode,productcode){
	window.open("/front/review_writepop.php?ordercode="+ordercode+'&productcode='+productcode,"Review","height=200,width=500,scrollbars=no,resizable=no");
}*/

function OrderReview(productcode){

	var _form = document.reviewForm;


		var writepop = "reviewritepop";
		var url = "./prreview_write_pop.php";
		window.open(url,writepop,"height=550,width=500,scrollbars=no,resizable=no");
		_form.productcode.value=productcode;
		_form.target = writepop;
		_form.action = url;
		_form.submit();

}

function OrderDetailProduct(ordercode,productcode){
	document.location.href="/front/productdetail.php?productcode="+productcode;
}


function DeliSearch(deli_url){
	window.open(deli_url,"�������","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizeble=yes,copyhistory=no,width=800,height=550");
}
function DeliveryPop(ordercode) {
	document.form3.ordercode.value=ordercode;
	window.open("about:blank","delipop","width=600,height=370,scrollbars=no");
	document.form3.submit();
}
function ViewPersonal(idx) {
	window.open("about:blank","mypersonalview","width=600,height=450,scrollbars=yes");
	document.form4.idx.value=idx;
	document.form4.submit();
}

function addGiftcard(){
	window.open('/front/mypage_auth.php','addgiftcard','width=300,height=200');
	return;
}

function refund1(){
	//frm = document.reForm;
	frm = document.form1;

	tmps = '';
	goods = '';
	for (i = cnt = 0; i < frm.elements.length; i++) {
		if(frm.elements[i].name == 'Item[]' && frm.elements[i].checked == true) {
			cnt++;

			if(!goods) goods = frm.elements[i].value;
			else goods = goods + '|' + frm.elements[i].value;
			if(!tmps) tmps = frm.elements[i].ordCode;
			else if(tmps!=frm.elements[i].ordCode) {
				alert("���� �ֹ����� ��ǰ�� ���� �մϴ�.");
				return false;
			}
		}
	}

	if ( cnt <= 0) {
		alert("��ȯ��û�� ��ǰ�� �����ϼ���!");
		return false;
	}
	frm = document.reForm;
	frm.goods.value = goods;
	frm.order_num.value = tmps;
	frm.action = "refund1.php";
	frm.submit();
	return false;
}

function refund2(){
	//frm = document.reForm;
	frm = document.form1;
	tmps = '';
	goods = '';
	for (i = cnt = 0; i < frm.elements.length; i++) {
		if(frm.elements[i].name == 'Item[]' && frm.elements[i].checked == true) {
			cnt++;

			if(!goods) goods = frm.elements[i].value;
			else goods = goods + '|' + frm.elements[i].value;
			if(!tmps) tmps = frm.elements[i].ordCode;
			else if(tmps!=frm.elements[i].ordCode) {
				alert("���� �ֹ����� ��ǰ�� ���� �մϴ�.");
				return false;
			}
		}
	}

	if ( cnt <= 0) {
		alert("ȯ�ҽ�û�� ��ǰ�� �����ϼ���!");
		return false;
	}


	frm = document.reForm;
	frm.goods.value = goods;
	frm.order_num.value = tmps;
	frm.action = "refund2.php";
	frm.submit();
	return false;
}

function order_one_cancel(ordercode, productcode, can, tempkey,uid) {

	if (can=="yes") {
		if (confirm("�ֹ���Ұ� �Ϸ�Ǹ� ���޿����� ������ �� �ֹ��� ��������� ��� ��ҵǸ� ��ҵ� �ֹ����� �ٽ� �ǵ��� �� �����ϴ�")) {
		window.open("<?=$Dir.FrontDir?>order_one_cancel_pop.php?ordercode="+ordercode+"&productcode="+productcode+"&uid="+uid,"one_cancel","width=610,height=500,scrollbars=yes");
		}
	}else{
		if (confirm("�Ա�Ȯ���� �ֹ��� '��ü���'�� �����մϴ�. \n��ü��Ҹ� ���Ͻô� ��� ���Ÿ� ���ϴ� ��ǰ�� �ٽ� �ֹ����ּ���.\n���ֹ��� ���� �ֹ� ��ü����Ͻðڽ��ϱ�?")) {

			document.form2.tempkey.value=tempkey;
			document.form2.type.value="cancel";

			document.form2.ordercode.value=ordercode;
			window.open("about:blank","orderpop","width=610,height=500,scrollbars=yes");
			document.form2.submit();

			document.form2.tempkey.value="";
			document.form2.type.value="";

		}
		//alert("�Ա�Ȯ���� �ֹ��� '��ü���'�� �����մϴ�. \n��ü ��� �� ���Ÿ� ���ϴ� ��ǰ�� �ٽ� �ֹ��Ͽ� �ֽʽÿ�.");
	}
}

/*
function order_cancel(tempkey,ordercode,bankdate) {	//�ֹ����
	if (confirm("�ֹ��� ����Ͻðڽ��ϱ�?")) {

		document.form2.tempkey.value=tempkey;
		document.form2.type.value="cancel";

		document.form2.ordercode.value=ordercode;
		window.open("about:blank","orderpop","width=610,height=500,scrollbars=yes");
		document.form2.submit();

		document.form2.tempkey.value="";
		document.form2.type.value="";
	}
}
*/

function order_cancel(tempkey,ordercode,bankdate) {	//�ֹ����
	if (confirm("�ֹ��� ����Ͻðڽ��ϱ�?")) {

		document.detailform.tempkey.value=tempkey;
		document.detailform.type.value="cancel_one";

		document.detailform.ordercode.value=ordercode;
		window.open("about:blank","orderpop","width=610,height=500,scrollbars=yes");
		document.detailform.submit();

		document.detailform.tempkey.value="";
		document.detailform.type.value="";
	}
 }

function productAll(chk_name) {

	chk_all = document.getElementById(chk_name+"_all");

	chk = document.getElementsByName(chk_name);
	for(i=0;i<chk.length;i++) {
		chk[i].checked=chk_all.checked;
	}
}

function order_multi_cancel(ordercode, cnt, tempkey, bankdate) {

	chk_name= "chk_"+ordercode;
	chk_uid_name= "chk_uid_"+ordercode;

	productcode = "";
	uid = "";
	product_chk = 0;

	chk = document.getElementsByName(chk_name);
	chk_uid = document.getElementsByName(chk_uid_name);
	for(i=0;i<chk.length;i++) {
		if (chk[i].checked) {


			if (productcode=="") {
				productcode = chk[i].value;
			}else{
				productcode = productcode+"$$"+chk[i].value;
			}

			if (uid=="") {
				uid = chk_uid[i].value;
			}else{
				uid = uid+"$$"+chk_uid[i].value;
			}

			product_chk++
		}
	}

	if (product_chk==0) {
		alert("���õ� ��ǰ�� �����ϴ�.");
	}else{

		if( chk.length == product_chk ) {
			order_cancel(tempkey,ordercode,bankdate);
		} else {
			if (confirm("�ֹ���Ұ� �Ϸ�Ǹ� ���޿����� ������ �� �ֹ��� ��������� ��� ��ҵǸ� ��ҵ� �ֹ����� �ٽ� �ǵ��� �� �����ϴ�")) {
				window.open("<?=$Dir.FrontDir?>order_one_cancel_pop.php?ordercode="+ordercode+"&productcode="+productcode+"&uid="+uid,"one_cancel","width=610,height=500,scrollbars=yes");
			}
		}
	}
}

//-->
</SCRIPT>
</HEAD>

<body<?=(substr($_data->layoutdata["MOUSEKEY"],0,1)=="Y"?" oncontextmenu=\"return false;\"":"")?><?=(substr($_data->layoutdata["MOUSEKEY"],1,1)=="Y"?" ondragstart=\"return false;\" onselectstart=\"return false;\"":"")?> leftmargin="0" marginwidth="0" topmargin="0" marginheight="0"><?=(substr($_data->layoutdata["MOUSEKEY"],2,1)=="Y"?"<meta http-equiv=\"ImageToolbar\" content=\"No\">":"")?>

<?
	include ($Dir.MainDir.$_data->menu_type.".php");
	include_once("./mypage_groupinfo.php");
?>

<!-- ���������� ��� �޴� -->
<div class="mypagemembergroup">
	<div class="groupinfotext">�ȳ��ϼ���? <strong class="st1"><?=$_ShopInfo->getMemname()?></strong>��. ȸ������ ����� <strong class="st2"><?=$groupname?></strong>�Դϴ�.</span></div>
	<div class="gruopinfogo"><a href="/front/newpage.php?code=1">ȸ����å���� &gt;</a></div>
</div>
<div class="mypagetmenu">
	<ul>
		<li class="nowMyage"><a href="/front/mypage.php">����������</a></li>
		<li><a href="/front/mypage_orderlist.php">�ֹ�����</a></li>
		<li><a href="/front/mypage_personal.php">1:1 ����</a></li>
		<li><a href="/front/mypage_reserve.php">������</a></li>
		<li><a href="/front/wishlist.php">���ϱ�</a></li>
		<li><a href="/front/mypage_coupon.php">��������</a></li>
		<? if($_data->recom_url_ok == "Y" || $_data->sns_ok == "Y"){ ?><li><a href="/front/mypage_promote.php">ȫ������</a></li><? } ?>
		<? if(getVenderUsed()==true) { ?><li><a href="/front/mypage_custsect.php">�ܰ����</a></li><? } ?>
		<li><a href="/front/mypage_usermodify.php">ȸ������</a></li>
		<li><a href="/front/mypage_memberout.php">ȸ��Ż��</a></li>
	</ul>
</div>
<div class="currentTitle">
	<div class="titleimage">����������</div>
	<div class="current">Ȩ &gt; ���������� &gt; <SPAN class="nowCurrent">����������</span></div>
</div>
<!-- ���������� ��� �޴� -->

<table border="0" cellpadding="0" cellspacing="0" width="100%">
<?
$leftmenu="Y";
if($_data->design_mypage=="U") {
	$sql="SELECT body,leftmenu FROM ".$designnewpageTables." WHERE type='mypage'";
	$result=mysql_query($sql,get_db_conn());
	if($row=mysql_fetch_object($result)) {
		$body=$row->body;
		$body=str_replace("[DIR]",$Dir,$body);
		$leftmenu=$row->leftmenu;
		$newdesign="Y";
	}
	mysql_free_result($result);
}

if ($leftmenu!="N") {
	echo "<tr>\n";
	if ($_data->title_type=="Y" && file_exists($Dir.DataDir."design/mypage_title.gif")) {
		echo "<td><img src=\"".$Dir.DataDir."design/mypage_title.gif\" border=\"0\" alt=\"����������\"></td>\n";
	} else {
		echo "<td>\n";
		/*
		echo "<TABLE WIDTH=100% BORDER=0 CELLPADDING=0 CELLSPACING=0>\n";
		echo "<TR>\n";
		echo "	<TD><IMG SRC=".$Dir."images/".$_data->icon_type."/mypage_title_head.gif ALT=></TD>\n";
		echo "	<TD width=100% valign=top background=".$Dir."images/".$_data->icon_type."/mypage_title_bg.gif></TD>\n";
		echo "	<TD width=40><IMG SRC=".$Dir."images/".$_data->icon_type."/mypage_title_tail.gif ALT=></TD>\n";
		echo "</TR>\n";
		echo "</TABLE>\n";
		*/
		echo "</td>\n";
	}
	echo "</tr>\n";
}

echo "<tr>\n";
echo "	<td align=\"center\">\n";
include ($Dir.TempletDir."mypage/mypage".$_data->design_mypage.".php");
echo "<form name=form1 method=post action=\"".$_SERVER[PHP_SELF]."\">\n";
echo "	<input type=hidden name=ordgbn value=\"".$ordgbn."\">\n";
echo "	<input type=hidden name=type value=\"".$type."\">\n";
echo '</form>';
echo "	</td>\n";
echo "</tr>\n";
?>

<form name="reForm" method="post" />
	<input type="hidden" name="goods" />
	<input type="hidden" name="order_num" />
</form>

<form name=form2 method=post action="<?=$Dir.FrontDir?>orderdetailpop.php" target="orderpop">
<input type=hidden name=ordercode>
<input type=hidden name=tempkey>
<input type=hidden name=type>
</form>
<form name=form3 method=post action="<?=$Dir.FrontDir?>deliverypop.php" target="delipop">
<input type=hidden name=ordercode>
</form>
<form name=form4 action="<?=$Dir.FrontDir?>mypage_personalview.php" method=post target="mypersonalview">
<input type=hidden name=idx>
</form>


	<form name=detailform method=post action="<?=$Dir.FrontDir?>orderdetailpop.php" target="orderpop">
		<input type=hidden name=ordercode>
		<input type=hidden name=tempkey>
		<input type=hidden name=type>
	</form>

</table>
<form name="reviewForm" method="post">
	<input type="hidden" name="productcode" value=""/>
</form>
<?=$onload?>

<? include ($Dir."lib/bottom.php") ?>

</BODY>
</HTML>
