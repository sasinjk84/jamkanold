<?
if(strlen($Dir)==0) {
	$Dir="../";
}
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/shopdata.php");

if ($_data->frame_type=="N" || strlen($_data->frame_type)==0) {	//��������
	if ((strlen($_REQUEST["id"])>0 && strlen($_REQUEST["passwd"])>0) || $_REQUEST["type"]=="logout" || $_REQUEST["type"]=="exit") {
		include($Dir."lib/loginprocess.php");
		exit;
	}
}

if ($_data->frame_type=="N") {
	$main_target="target=main";

	$result2 = mysql_query("SELECT rightmargin FROM tbltempletinfo WHERE icon_type='".$_data->icon_type."'",get_db_conn());
	if ($row2=mysql_fetch_object($result2)) $rightmargin=$row2->rightmargin;
	else $rightmargin=0;
	mysql_free_result($result2);

?>

<html>
<head>
<meta http-equiv="CONTENT-TYPE" content="text/html;charset=EUC-KR">
<script type="text/javascript" src="<?=$Dir?>lib/lib.js.php"></script>
<? include($Dir."lib/style.php") ?>
<SCRIPT LANGUAGE="JavaScript">
<!--
function sendmail() {
	window.open("<?=$Dir.FrontDir?>email.php","email_pop","height=100,width=100");
}
function estimate(type) {
	if(type=="Y") {
		window.open("<?=$Dir.FrontDir?>estimate_popup.php","estimate_pop","height=100,width=100,scrollbars=yes");
	} else if(type=="O") {
		if(typeof(top.main)=="object") {
			top.main.location.href="<?=$Dir.FrontDir?>estimate.php";
		} else {
			document.location.href="<?=$Dir.FrontDir?>estimate.php";
		}
	}
}
function privercy() {
	window.open("<?=$Dir.FrontDir?>privercy.php","privercy_pop","height=570,width=590,scrollbars=yes");
}
function order_privercy() {
	window.open("<?=$Dir.FrontDir?>privercy.php","privercy_pop","height=570,width=590,scrollbars=yes");
}
function logout() {
	location.href="<?=$Dir.MainDir?>main.php?type=logout";
}
function sslinfo() {
	window.open("<?=$Dir.FrontDir?>sslinfo.php","sslinfo","width=100,height=100,scrollbars=no");
}
function memberout() {
	if(typeof(top.main)=="object") {
		top.main.location.href="<?=$Dir.FrontDir?>mypage_memberout.php";
	} else {
		document.location.href="<?=$Dir.FrontDir?>mypage_memberout.php";
	}
}
function notice_view(type,code) {
	if(type=="view") {
		window.open("<?=$Dir.FrontDir?>notice.php?type="+type+"&code="+code,"notice_view","width=450,height=450,scrollbars=yes");
	} else {
		window.open("<?=$Dir.FrontDir?>notice.php?type="+type,"notice_view","width=450,height=450,scrollbars=yes");
	}
}
function information_view(type,code) {
	if(type=="view") {
		window.open("<?=$Dir.FrontDir?>information.php?type="+type+"&code="+code,"information_view","width=600,height=500,scrollbars=yes");
	} else {
		window.open("<?=$Dir.FrontDir?>information.php?type="+type,"information_view","width=600,height=500,scrollbars=yes");
	}
}
function GoPrdtItem(prcode) {
	window.open("<?=$Dir.FrontDir?>productdetail.php?productcode="+prcode,"prdtItemPop","WIDTH=800,HEIGHT=700 left=0,top=0,toolbar=yes,location=yes,directories=yse,status=yes,menubar=yes,scrollbars=yes,resizable=yes");
}
//-->
</SCRIPT>
</head>

<body topmargin=0 leftmargin=0 rightmargin=<?=$rightmargin?> marginheight=0 marginwidth=0 oncontextmenu="return false" style="overflow-x:hidden;overflow-y:hidden;" ondragstart="return false" onselectstart="return false" oncontextmenu="return false">
<?
}

if($_data->align_type=="Y") echo "<center>";

$imagepath=$Dir.DataDir."shopimages/etc/";

$topbody="";
$sql = "SELECT * FROM tbldesign ";
$result=mysql_query($sql,get_db_conn());
$row=mysql_fetch_object($result);
mysql_free_result($result);
if($row->top_set=="Y") {
	$xsize=$row->top_xsize;
	$ysize=$row->top_ysize;
	$menu_align=$row->menu_align;
	$background=$row->background;
	$logo_loc=$row->logo_loc;
	$menu_list=$row->menu_list;
	$link1=$row->link1;
	$link2=$row->link2;
	$link3=$row->link3;
	$link4=$row->link4;
	$link5=$row->link5;

	$arralign=array("L"=>"left","C"=>"center","R"=>"right");

	$menu_all_name=array(
		1=>"����������",
		2=>"ȸ��Ұ�",
		3=>"�̿�ȳ�",
		4=>"ȸ������/����",
		5=>"��ٱ���",
		6=>"�ֹ���ȸ",
		7=>"�α���",
		8=>"�α׾ƿ�",
		9=>"ȸ��Ż��",
		10=>"����������",
		11=>"������",
		12=>"�űԻ�ǰ",
		13=>"�α��ǰ",
		14=>"��õ��ǰ",
		15=>"Ư����ǰ",
		16=>"�߰��̹���1",
		17=>"�߰��̹���2",
		18=>"�߰��̹���3",
		19=>"�߰��̹���4",
		20=>"�߰��̹���5"
	);
	$menu_all_url=array(
		1=>"[HOME]",
		2=>"[COMPANY]",
		3=>"[USEINFO]",
		4=>"[MEMBER]",
		5=>"[BASKET]",
		6=>"[ORDER]",
		7=>"[LOGIN]",
		8=>"[LOGOUT]",
		9=>"[MEMBEROUT]",
		10=>"[MYPAGE]",
		11=>"[EMAIL]",
		12=>"[PRODUCTNEW]",
		13=>"[PRODUCTBEST]",
		14=>"[PRODUCTHOT]",
		15=>"[PRODUCTSPECIAL]",
		16=>&$link1,
		17=>&$link2,
		18=>&$link3,
		19=>&$link4,
		20=>&$link5
	);
	$arr_menu_list=explode(",",$menu_list);

	$top_menu ="<table border=0 cellpadding=0 cellspacing=0>\n";
	$top_menu.="<tr>\n";
	for($i=0;$i<count($arr_menu_list);$i++) {
		$top_menu.="<td><A HREF=".$menu_all_url[$arr_menu_list[$i]]."><img src=\"".$imagepath."easytopmenu".$arr_menu_list[$i].".gif\" border=0 align=absmiddle alt=\"".$menu_all_name[$arr_menu_list[$i]]."\"></A></td>";
	}
	$top_menu.="</tr>\n";
	$top_menu.="</table>\n";

	$topbody = "<table border=0 cellpadding=0 cellspacing=0 width=".$xsize." height=".$ysize." ";
	if($background=="Y") $topbody.="background=\"".$imagepath."easytopbg.gif\"";
	$topbody.= ">";
	if($logo_loc=="T") {
		$topbody.= "<tr>\n";
		$topbody.= "	<td align=left><A HREF=\"".$Dir.MainDir."main.php\" ".$main_target."><img src=\"".$imagepath."logo.gif\" border=0 align=absmiddle></A></td>\n";
		$topbody.= "</tr>\n";
		$topbody.= "<tr>\n";
		$topbody.= "	<td align=".$arralign[$menu_align].">".$top_menu."</td>\n";
		$topbody.= "</tr>\n";
	} else if($logo_loc="Y") {
		$topbody.= "<tr>\n";
		$topbody.= "	<td align=left nowrap><A HREF=\"".$Dir.MainDir."main.php\" ".$main_target."><img src=\"".$imagepath."logo.gif\" border=0 align=absmiddle></A></td>\n";
		$topbody.= "	<td width=100% align=".$arralign[$menu_align].">".$top_menu."</td>\n";
		$topbody.= "</tr>\n";
	}
	$topbody.= "</table>\n";

	$shop_count=$_ShopInfo->getShopCount();
	$pattern=array(
			"(\[VISIT\])",
			"(\[VISIT2\])",
			"(\[HOME\])",
			"(\[USEINFO\])",
			"(\[MEMBER\])",
			"(\[LOGIN\])",
			"(\[LOGOUT\])",
			"(\[MEMBEROUT\])",
			"(\[LOGO\])",
			"(\[LOGINFORM\])",
			"(\[LOGINFORMU\])",
			"(\[BASKET\])",
			"(\[ORDER\])",
			"(\[RESERVEVIEW\])",
			"(\[MYPAGE\])",
			"(\[REVIEW\])",
			"(\[BOARD\])",
			"(\[AUCTION\])",
			"(\[GONGGU\])",
			"(\[ESTIMATE\])",
			"(\[COMPANY\])",
			"(\[EMAIL\])",
			"(\[PRODUCTNEW\])",
			"(\[PRODUCTBEST\])",
			"(\[PRODUCTHOT\])",
			"(\[PRODUCTSPECIAL\])",
			"(\[TAG\])",
			"(\[RSS\])",
			"(\[NOTICE([1-4]{1})([YN]{0,1})([1-9]{0,1})([YN]{0,1})([1-9]{0,1})(\_){0,1}([0-9]{0,3})\])",
			"(\[SEARCHFORMSTART\])",
			"(\[SEARCHKEYWORD((\_){0,1})([0-9]{0,3})\])",
			"(\[SEARCHOK\])",
			"(\[SEARCHFORMEND\])",

			"(\[LOGIN_START\])",
			"(\[LOGIN_END\])",
			"(\[LOGOUT_START\])",
			"(\[LOGOUT_END\])"
	);

	if(strlen($_ShopInfo->getMemid())>0) {
		$replace=array(
				$shop_count,
				$shop_count." (<a href=\"".$Dir.MainDir."main.php?type=logout\">Logout</a>)",
				$Dir.MainDir."main.php $main_target",
				$Dir.FrontDir."useinfo.php $main_target",
				$Dir.FrontDir."member_agree.php $main_target",
				"javascript:alert('�α������Դϴ�.')",
				$Dir.MainDir."main.php?type=logout",
				"javascript:memberout()",
				$logo,
				$top_loginform,
				$top_loginformu,
				$Dir.FrontDir."basket.php $main_target",
				$Dir.FrontDir."mypage_orderlist.php $main_target",
				$Dir.FrontDir."mypage_reserve.php $main_target",
				$Dir.FrontDir."mypage.php $main_target",
				$Dir.FrontDir."reviewall.php $main_target",
				$Dir.BoardDir."board.php?board=qna $main_target",
				$Dir.AuctionDir."auction.php $main_target",
				$Dir.FrontDir."gonggu_main.php $main_target",
				"javascript:estimate('".$_data->estimate_ok."')",
				$Dir.FrontDir."company.php $main_target",
				"javascript:sendmail()",
				$Dir.FrontDir."productnew.php $main_target",
				$Dir.FrontDir."productbest.php $main_target",
				$Dir.FrontDir."producthot.php $main_target",
				$Dir.FrontDir."productspecial.php $main_target",
				$Dir.FrontDir."tag.php $main_target",
				$Dir.FrontDir."rssinfo.php $main_target",
				$tnotice,
				"<form name=search_tform method=get action=\"".$Dir.FrontDir."productsearch.php\" $main_target>",
				$searchkeyword,
				"javascript:TopSearchCheck()",
				"</form>",

				"<!-- ",
				" -->",
				"",
				""
		);




	} else {
		$replace=array(
			$shop_count,
			$shop_count,
			$Dir.MainDir."main.php $main_target",
			$Dir.FrontDir."useinfo.php $main_target",
			$Dir.FrontDir."member_agree.php $main_target",
			$Dir.FrontDir."login.php $main_target",
			$Dir.FrontDir."login.php $main_target",
			"javascript:memberout()",
			$logo,
			$top_loginform,
			$top_loginformu,
			$Dir.FrontDir."basket.php $main_target",
			$Dir.FrontDir."mypage_orderlist.php $main_target",
			$Dir.FrontDir."mypage_reserve.php $main_target",
			$Dir.FrontDir."mypage.php $main_target",
			$Dir.FrontDir."reviewall.php $main_target",
			$Dir.BoardDir."board.php?board=qna $main_target",
			$Dir.AuctionDir."auction.php $main_target",
			$Dir.FrontDir."gonggu_main.php $main_target",
			"javascript:estimate('".$_data->estimate_ok."')",
			$Dir.FrontDir."company.php $main_target",
			"javascript:sendmail()",
			$Dir.FrontDir."productnew.php $main_target",
			$Dir.FrontDir."productbest.php $main_target",
			$Dir.FrontDir."producthot.php $main_target",
			$Dir.FrontDir."productspecial.php $main_target",
			$Dir.FrontDir."tag.php $main_target",
			$Dir.FrontDir."rssinfo.php $main_target",
			$tnotice,
			"<form name=search_tform method=get action=\"".$Dir.FrontDir."productsearch.php\" $main_target>",
			$searchkeyword,
			"javascript:TopSearchCheck()",
			"</form>",

				"",
				"",
				"<!-- ",
				" -->"
		);

	}

	$basketcount = _basketCount('tblbasket_normal',$_ShopInfo->getTempkey());
	
	array_push($pattern,"(\[BASKETCOUNT\])");
	array_push($replace,$basketcount);


	array_push($pattern,"(\[TODAYSALE\])");
	array_push($replace,$Dir.TodaySaleDir);
	$topbody = preg_replace($pattern,$replace,$topbody);
} else {
	$topbody="<center><br><FONT style=\"font-size:12px\">��ܸ޴� ������ �ȵǾ����ϴ�.</FONT></center>";
}

echo $topbody;

if ($_data->frame_type=="N") {
?>
</body>
</html>
<?
}
?>