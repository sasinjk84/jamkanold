<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/shopdata.php");

if(strlen($_ShopInfo->getMemid())==0) {
	Header("Location:".$Dir.FrontDir."login.php?chUrl=".getUrl());
	exit;
}

if($_data->coupon_ok!="Y") {
	echo "<html><head><title></title></head><body onload=\"alert('�� ���θ������� ���� ����� �������� �ʽ��ϴ�.');location.href='".$Dir.FrontDir."mypage.php'\"></body></html>";exit;
}

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

?>

<HTML>
<HEAD>
<TITLE><?=$_data->shoptitle?> - ���� ��������</TITLE>
<META http-equiv="CONTENT-TYPE" content="text/html; charset=EUC-KR">
<META http-equiv="X-UA-Compatible" content="IE=5" />
<META name="description" content="<?=(strlen($_data->shopdescription)>0?$_data->shopdescription:$_data->shoptitle)?>">
<META name="keywords" content="<?=$_data->shopkeyword?>">
<script type="text/javascript" src="<?=$Dir?>lib/lib.js.php"></script>
<?include($Dir."lib/style.php")?>
<SCRIPT LANGUAGE="JavaScript">
<!--
function addOffCoupon(){
	window.open('/front/offlinecoupon_auth.php','OffLineCoupon','width=300,height=200');
}
//-->
</SCRIPT>
</HEAD>

<body<?=(substr($_data->layoutdata["MOUSEKEY"],0,1)=="Y"?" oncontextmenu=\"return false;\"":"")?><?=(substr($_data->layoutdata["MOUSEKEY"],1,1)=="Y"?" ondragstart=\"return false;\" onselectstart=\"return false;\"":"")?> leftmargin="0" marginwidth="0" topmargin="0" marginheight="0"><?=(substr($_data->layoutdata["MOUSEKEY"],2,1)=="Y"?"<meta http-equiv=\"ImageToolbar\" content=\"No\">":"")?>

<?
	include ($Dir.MainDir.$_data->menu_type.".php");
	include_once("./mypage_groupinfo.php");
?>



<!-- ����������-�������� ��� �޴� -->
<div class="mypagemembergroup">
	<div class="groupinfotext">�ȳ��ϼ���? <strong class="st1"><?=$_ShopInfo->getMemname()?></strong>��. ȸ������ ����� <strong class="st2"><?=$groupname?></strong>�Դϴ�.</span></div>
	<div class="gruopinfogo"><a href="/front/newpage.php?code=1">ȸ����å���� &gt;</a></div>
</div>
<div class="mypagetmenu">
	<ul>
		<li class="leftline"><a href="/front/mypage.php">����������</a></li>
		<li class="leftline"><a href="/front/mypage_orderlist.php">�ֹ�����</a></li>
		<li class="leftline"><a href="/front/mypage_personal.php">1:1 ����</a></li>
		<li class="leftline"><a href="/front/mypage_reserve.php">������</a></li>
		<li class="leftline"><a href="/front/wishlist.php">���ϱ�</a></li>
		<li class="nowMyage"><a href="/front/mypage_coupon.php">��������</a></li>
		<? if($_data->recom_url_ok == "Y" || $_data->sns_ok == "Y"){ ?><li><a href="/front/mypage_promote.php">ȫ������</a></li><? } ?>
		<? if(getVenderUsed()==true) { ?><li><a href="/front/mypage_custsect.php">�ܰ����</a></li><? } ?>
		<li><a href="/front/mypage_usermodify.php">ȸ������</a></li>
		<li><a href="/front/mypage_memberout.php">ȸ��Ż��</a></li>
	</ul>
</div>
<div class="currentTitle">
	<div class="titleimage">��������</div>
	<div class="current">Ȩ &gt; ���������� &gt; <SPAN class="nowCurrent">��������</span></div>
</div>
<!-- ����������-�������� ��� �޴� -->



<table border="0" cellpadding="0" cellspacing="0" width="100%">
<?
$leftmenu="Y";
if($_data->design_mycoupon=="U") {
	$sql="SELECT body,leftmenu FROM ".$designnewpageTables." WHERE type='mycoupon'";
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
	if ($_data->title_type=="Y" && file_exists($Dir.DataDir."design/mycoupon_title.gif")) {
		echo "<td><img src=\"".$Dir.DataDir."design/mycoupon_title.gif\" border=\"0\" alt=\"���� ��������\"></td>\n";
	} else {
		echo "<td>\n";
		/*
		echo "<TABLE WIDTH=100% BORDER=0 CELLPADDING=0 CELLSPACING=0>\n";
		echo "<TR>\n";
		echo "	<TD><IMG SRC=".$Dir."images/".$_data->icon_type."/mycoupon_title_head.gif ALT=></TD>\n";
		echo "	<TD width=100% valign=top background=".$Dir."images/".$_data->icon_type."/mycoupon_title_bg.gif></TD>\n";
		echo "	<TD width=40><IMG SRC=".$Dir."images/".$_data->icon_type."/mycoupon_title_tail.gif ALT=></TD>\n";
		echo "</TR>\n";
		echo "</TABLE>\n";
		*/
		echo "</td>\n";
	}
	echo "</tr>\n";
}

echo "<tr>\n";
echo "	<td align=\"center\">\n";
include ($Dir.TempletDir."mycoupon/mycoupon".$_data->design_mycoupon.".php");
echo "	</td>\n";
echo "</tr>\n";
?>

</table>

<? include ($Dir."lib/bottom.php") ?>

<?=$onload?>

</BODY>
</HTML>