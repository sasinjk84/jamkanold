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
	echo "<html><head><title></title></head><body onload=\"alert('ȸ??Ż???? ?Ͻ? ?? ?????ϴ?.\\n\\n???θ? ??ڿ??? ?????Ͻñ? ?ٶ??ϴ?.');history.go(-1)\"></body></html>";exit;
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
<TITLE><?=$_data->shoptitle?> - ȸ??Ż???ϱ?</TITLE>
<META http-equiv="CONTENT-TYPE" content="text/html; charset=EUC-KR">
<META http-equiv="X-UA-Compatible" content="IE=Edge" />

<META name="description" content="<?=(strlen($_data->shopdescription)>0?$_data->shopdescription:$_data->shoptitle)?>">
<META name="keywords" content="<?=$_data->shopkeyword?>">
<script type="text/javascript" src="<?=$Dir?>lib/lib.js.php"></script>
<?include($Dir."lib/style.php")?>
</HEAD>
<SCRIPT LANGUAGE="JavaScript">
<!--
function CheckForm() {
	if(confirm("ȸ??Ż???? ?Ͻðڽ??ϱ??")==true) {
		document.form1.type.value="exit";
		document.form1.submit();
	}
}
//-->
</SCRIPT>
<body<?=(substr($_data->layoutdata["MOUSEKEY"],0,1)=="Y"?" oncontextmenu=\"return false;\"":"")?><?=(substr($_data->layoutdata["MOUSEKEY"],1,1)=="Y"?" ondragstart=\"return false;\" onselectstart=\"return false;\"":"")?> leftmargin="0" marginwidth="0" topmargin="0" marginheight="0"><?=(substr($_data->layoutdata["MOUSEKEY"],2,1)=="Y"?"<meta http-equiv=\"ImageToolbar\" content=\"No\">":"")?>

<?
	include ($Dir.MainDir.$_data->menu_type.".php");
	include_once("./mypage_groupinfo.php");
?>

<!-- ??????????-ȸ??Ż?? ???? ?޴? -->
<div class="currentTitle">
	<div class="titleimage">ȸ??Ż??</div>
	<!--<div class="current"><img src="/data/design/img/sub/icon_home.gif" border="0" alt="" /> Ȩ &gt; ?????????? &gt; <SPAN class="nowCurrent">ȸ??Ż??</span></div>-->
</div>



	<table border="0" cellpadding="0" cellspacing="0" width="100%">
		<form name=form1 action="<?=$_SERVER[PHP_SELF]?>" method=post>
		<input type=hidden name=type>
<?
if ($leftmenu!="N") {
	echo "<tr>\n";
	if ($_data->title_type=="Y" && file_exists($Dir.DataDir."design/memberout_title.gif")) {
		echo "<td><img src=\"".$Dir.DataDir."design/memberout_title.gif\" border=\"0\" alt=\"ȸ??Ż??\"></td>\n";
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
if($newdesign=="Y") {	//??????????

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
			<td>
									<TABLE cellSpacing="0" cellPadding="0" border="0" style="padding:10px;border-width:1px;border-color:#ededed;border-style:solid;width:70%;margin:0px auto;">
										<TR>
											<TD style="padding:30px;text-align:center;">
											<?if($_data->memberout_type=="Y"){?>
											<B><font color="#FF3300"><?=$_data->shopname?></B> ???θ?</font> Ż????û?? ?Ͻ? ????, ???θ? ??ڰ? Ȯ?? ?? Ż??ó???? ?ص帮??<BR>
											<?}else if($_data->memberout_type=="O"){?>
											<B><font color="#FF3300"><?=$_data->shopname?></B> ???θ?</font> ȸ??Ż???? ?Ͻ? ????, ?¶??ο??? ???? ó???Ǹ?<BR>
											<?}?>
											<B><?=$_ShopInfo->getMemname()?> (<?=$_ShopInfo->getMemid()?>)</B> ȸ???Բ??? ?ش? ID?? ?̿??ϼ̴? ???? ???????? ?̿??? ?Ұ????ϰ? ?˴ϴ?.<BR><BR>
											???? ???Խ? ?Է??Ͻ? ?Ż??????? ???? ?????Ǹ? ?׵??? ?????ϼ̴? ??????, ???? ???? ?̿??Ͻ? ?? ?????ϴ?.<BR>
											?ٸ?, ?ֹ??Ͻ? ?????? ???ؼ??? ????ó???? ?ȵǿ??? ???? ?????Ͻñ? ?ٶ??ϴ?.<BR><BR>
											ȸ??Ż???? ?Ͻðڽ??ϱ??</TD>
										</TR>
									</TABLE>
									<p style="padding:30px;text-align:center;">
										<A HREF="javascript:CheckForm()" class="btn_gray"><span>ȸ??Ż??</span></a> <A HREF="javascript:history.go(-1);" class="btn_line">????????</a>
									</p>


			</td>
		</tr>
<?
}
?>
		</form>
	</table>


<? include ($Dir."lib/bottom.php") ?>
</BODY>
</HTML>