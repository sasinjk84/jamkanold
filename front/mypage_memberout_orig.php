<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/shopdata.php");

if(strlen($_ShopInfo->getMemid())==0) {
	Header("Location:".$Dir.FrontDir."login.php?chUrl=".getUrl());
	exit;
}

if($_data->memberout_type=="N") {
	echo "<html><head><title></title></head><body onload=\"alert('ȸ��Ż�� �Ͻ� �� �����ϴ�.\\n\\n���θ� ��ڿ��� �����Ͻñ� �ٶ��ϴ�.');history.go(-1)\"></body></html>";exit;
}


$leftmenu="Y";
$sql="SELECT body,leftmenu FROM ".$designnewpageTables." WHERE type='memberout'";
$result=mysql_query($sql,get_db_conn());
if($row=mysql_fetch_object($result)) {
	$body=$row->body;
	$body=str_replace("[DIR]",$Dir,$body);
	$leftmenu=$row->leftmenu;
	$newdesign="Y";
}
mysql_free_result($result);

?>

<HTML>
<HEAD>
<TITLE><?=$_data->shoptitle?> - ȸ��Ż���ϱ�</TITLE>
<META http-equiv="CONTENT-TYPE" content="text/html; charset=EUC-KR">
<META http-equiv="X-UA-Compatible" content="IE=5" />
<META name="description" content="<?=(strlen($_data->shopdescription)>0?$_data->shopdescription:$_data->shoptitle)?>">
<META name="keywords" content="<?=$_data->shopkeyword?>">
<script type="text/javascript" src="<?=$Dir?>lib/lib.js.php"></script>
<?include($Dir."lib/style.php")?>
</HEAD>
<SCRIPT LANGUAGE="JavaScript">
<!--
function CheckForm() {
	if(confirm("ȸ��Ż�� �Ͻðڽ��ϱ�?")==true) {
		document.form1.type.value="exit";
		document.form1.submit();
	}
}
//-->
</SCRIPT>
<body<?=(substr($_data->layoutdata["MOUSEKEY"],0,1)=="Y"?" oncontextmenu=\"return false;\"":"")?><?=(substr($_data->layoutdata["MOUSEKEY"],1,1)=="Y"?" ondragstart=\"return false;\" onselectstart=\"return false;\"":"")?> leftmargin="0" marginwidth="0" topmargin="0" marginheight="0"><?=(substr($_data->layoutdata["MOUSEKEY"],2,1)=="Y"?"<meta http-equiv=\"ImageToolbar\" content=\"No\">":"")?>

<?
	$_data->menu_type = 'nomenu';
	include ($Dir.MainDir.$_data->menu_type.".php");
	include_once("./mypage_groupinfo.php");
?>

<!-- ����������-ȸ��Ż�� ��� �޴� -->
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
		<li class="leftline"><a href="/front/mypage_coupon.php">��������</a></li>
		<? if($_data->recom_url_ok == "Y" || $_data->sns_ok == "Y"){ ?><li class="leftline"><a href="/front/mypage_promote.php">ȫ������</a></li><? } ?>
		<? if(getVenderUsed()==true) { ?><li class="leftline"><a href="/front/mypage_custsect.php">�ܰ����</a></li><? } ?>
		<li class="leftline"><a href="/front/mypage_usermodify.php">ȸ������</a></li>
		<li class="nowMyage"><a href="/front/mypage_memberout.php">ȸ��Ż��</a></li>
	</ul>
</div>

<div class="currentTitle">
	<div class="titleimage">ȸ��Ż��</div>
	<div class="current">Ȩ &gt; ���������� &gt; <SPAN class="nowCurrent">ȸ��Ż��</span></div>
</div>

<table border="0" cellpadding="0" cellspacing="0" width="100%">
<form name=form1 action="<?=$_SERVER[PHP_SELF]?>" method=post>
<input type=hidden name=type>
<?
if ($leftmenu!="N") {
	echo "<tr>\n";
	if ($_data->title_type=="Y" && file_exists($Dir.DataDir."design/memberout_title.gif")) {
		echo "<td><img src=\"".$Dir.DataDir."design/memberout_title.gif\" border=\"0\" alt=\"ȸ��Ż��\"></td>\n";
	} else {
		echo "<td>\n";
		/*
		echo "<TABLE WIDTH=100% BORDER=0 CELLPADDING=0 CELLSPACING=0>\n";
		echo "<TR>\n";
		echo "	<TD><IMG SRC=".$Dir."images/".$_data->icon_type."/memberout_title_head.gif ALT=></TD>\n";
		echo "	<TD width=100% valign=top background=".$Dir."images/".$_data->icon_type."/memberout_title_bg.gif></TD>\n";
		echo "	<TD width=40><IMG SRC=".$Dir."images/".$_data->icon_type."/memberout_title_tail.gif ALT=></TD>\n";
		echo "</TR>\n";
		echo "</TABLE>\n";
		*/
		echo "</td>\n";
	}
	echo "</tr>\n";
}

$menu_myhome=$Dir.FrontDir."mypage.php";
$menu_myorder=$Dir.FrontDir."mypage_orderlist.php";
$menu_mypersonal=$Dir.FrontDir."mypage_personal.php";
$menu_mywish=$Dir.FrontDir."wishlist.php";
$menu_myreserve=$Dir.FrontDir."mypage_reserve.php";
$menu_mycoupon=$Dir.FrontDir."mypage_coupon.php";
$menu_myinfo=$Dir.FrontDir."mypage_usermodify.php";
$menu_myout=$Dir.FrontDir."mypage_memberout.php";
$menu_mycustsect=$Dir.FrontDir."mypage_custsect.html";
if($newdesign=="Y") {	//����������

	$pattern=array(
	"(\[MENU_MYHOME\])",
	"(\[MENU_MYORDER\])",
	"(\[MENU_MYPERSONAL\])",
	"(\[MENU_MYWISH\])",
	"(\[MENU_MYRESERVE\])",
	"(\[MENU_MYCOUPON\])",
	"(\[MENU_MYINFO\])",
	"(\[MENU_MYOUT\])",
	"(\[MENU_MYCUSTSECT\])",
	"(\[SHOP\])","(\[OK\])","(\[ID\])","(\[NAME\])","(\[CANCEL\])");
	$replace=array($menu_myhome,$menu_myorder,$menu_mypersonal,$menu_mywish,$menu_myreserve,$menu_mycoupon,$menu_myinfo,$menu_myout,$menu_mycustsect,$_data->shopname,"javascript:CheckForm()",$_ShopInfo->getMemid(),$_ShopInfo->getMemname(),"javascript:location.href='".$Dir.FrontDir."mypage.php'");
	$body=preg_replace($pattern,$replace,$body);
	echo "<tr>\n";
	echo "	<td align=\"center\">".$body."</td>";
	echo "</tr>\n";
} else {
?>
<tr>
	<td style="padding-top:20px;">
	<table cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed">
	<tr>
		<td><IMG SRC="<?=$Dir?>images/common/mypage_memberout_text.gif" border="0"></td>
	</tr>
	<tr>
		<td align="center">
		<table cellpadding="0" cellspacing="0" width="100%">
		<tr>
			<td background="<?=$Dir?>images/common/join_yak_t01.gif"><IMG SRC="<?=$Dir?>images/common/join_yak_t01_left.gif" border="0"></td>
			<td background="<?=$Dir?>images/common/join_yak_t01.gif"></td>
			<td align="right" background="<?=$Dir?>images/common/join_yak_t01.gif"><IMG SRC="<?=$Dir?>images/common/join_yak_t01_right.gif" border="0"></td>
		</tr>
		<tr>
			<td background="<?=$Dir?>images/common/join_yak_t02.gif"></td>
			<td width="100%">
			<TABLE cellSpacing="0" cellPadding="0" border="0" width="100%" style="border-width:1pt;border-color:rgb(222,222,222);border-style:solid;TABLE-LAYOUT: fixed">
			<TR>
				<TD>
				<TABLE cellSpacing="0" cellPadding="0" border="0" style="padding:10px;">
				<TR>
					<TD style="padding:15px;">
					<?if($_data->memberout_type=="Y"){?>
					<B><font color="#FF3300"><?=$_data->shopname?></B> ���θ�</font> Ż���û�� �Ͻ� ���, ���θ� ��ڰ� Ȯ�� �� Ż��ó���� �ص帮��<BR>
					<?}else if($_data->memberout_type=="O"){?>
					<B><font color="#FF3300"><?=$_data->shopname?></B> ���θ�</font> ȸ��Ż�� �Ͻ� ���, �¶��ο��� ��� ó���Ǹ�<BR>
					<?}?>
					<B><?=$_ShopInfo->getMemname()?> (<?=$_ShopInfo->getMemid()?>)</B> ȸ���Բ��� �ش� ID�� �̿��ϼ̴� ��� ������ �̿��� �Ұ����ϰ� �˴ϴ�.<BR><BR>
					���� ���Խ� �Է��Ͻ� �Ż������� ��� �����Ǹ� �׵��� �����ϼ̴� ������, ���� ���� �̿��Ͻ� �� �����ϴ�.<BR>
					�ٸ�, �ֹ��Ͻ� ������ ���ؼ��� ����ó���� �ȵǿ��� ���� �����Ͻñ� �ٶ��ϴ�.<BR><BR>
					ȸ��Ż�� �Ͻðڽ��ϱ�?</TD>
				</TR>
				</TABLE>
				</TD>
			</TR>
			</TABLE>
			</td>
			<td background="<?=$Dir?>images/common/join_yak_t04.gif"></td>
		</tr>
		<tr>
			<td background="<?=$Dir?>images/common/join_yak_t03.gif"><IMG SRC="<?=$Dir?>images/common/join_yak_t03_left.gif" border="0"></td>
			<td background="<?=$Dir?>images/common/join_yak_t03.gif"></td>
			<td align="right" background="<?=$Dir?>images/common/join_yak_t03.gif"><IMG SRC="<?=$Dir?>images/common/join_yak_t03_right.gif" border="0"></td>
		</tr>
		</table>
		</td>
	</tr>
	<tr>
		<td height="20"></td>
	</tr>
	<tr>
		<td align="center"><A HREF="javascript:CheckForm()"><img src="<?=$Dir?>images/common/btn_memberout.gif" border="0"></a><A HREF="javascript:history.go(-1);"><img src="<?=$Dir?>images/common/btn_mback.gif" border="0" hspace="5"></a></td>
	</tr>
	</table>
	</td>
</tr>
<tr>
	<td height="20"></td>
</tr>
<?
}
?>
</form>
</table>

<? include ($Dir."lib/bottom.php") ?>

</BODY>
</HTML>