<?
if(strlen($Dir)==0) {
	$Dir="../";
}
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/shopdata.php");

//장바구니 상품 카운터
if(strlen($_ShopInfo->getMemid())==0) {	//비회원
	$basketcount = _basketCount('tblbasket_noraml',$_ShopInfo->getTempkey());
}else{
	$basketcount = _basketCount('tblbasket_noraml',$_ShopInfo->getMemid());
}

if ($_data->frame_type=="N" || strlen($_data->frame_type)==0) {	//투프레임
	if ((strlen($_REQUEST["id"])>0 && strlen($_REQUEST["passwd"])>0) || $_REQUEST["type"]=="logout" || $_REQUEST["type"]=="exit") {
		include($Dir."lib/loginprocess.php");
		exit;
	}
}

if(file_exists($Dir.DataDir."shopimages/etc/logo.gif")) {
	$width = getimagesize($Dir.DataDir."shopimages/etc/logo.gif");
	$logo = "<img src=\"".$Dir.DataDir."shopimages/etc/logo.gif\" border=0 ";
	if($width[0]>200) $logo.="width=200 ";
	if($width[1]>65) $logo.="height=65 ";
	$logo.=">";
} else {
	$logo = "<img src=\"".$Dir."images/".$_data->icon_type."/logo.gif\" border=0>";
}

if ($_data->frame_type=="N") {
$main_target="target=main";

$result2 = mysql_query("SELECT rightmargin FROM tbltempletinfo WHERE icon_type='".$_data->icon_type."'",get_db_conn());
if ($row2=mysql_fetch_object($result2)) $rightmargin=$row2->rightmargin;
else $rightmargin=0;
mysql_free_result($result2);

$URL = $_SERVER['HTTP_HOST'];
?>

<html>
<head>
	<meta http-equiv="CONTENT-TYPE"	content="text/html;charset=EUC-KR">
	<META http-equiv="X-UA-Compatible" content="IE=Edge" />

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

		// 검색박스 오픈 div
		//function selectValue(obj){
		//	alert("A");
			//var _obj = document.getElementById("select_"+obj);

			
			/*var obj;
			var div = eval("document.all.select_" + obj);

			if (div.style.display == ''){
				div.style.display = "none";
			} else {
				div.style.top = 30; //상단에서 좌표
				div.style.display = "";
			}*/
		//}
		//-->
	</SCRIPT>
</head>

<body rightmargin="<?=$rightmargin?>" leftmargin="0" marginwidth="0" topmargin="0" marginheight="0"	style="overflow-x: hidden;overflow-y:hidden;">
	<table width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td>
				<?
				}

				if($_data->align_type=="Y")
					echo "<center>";
				if ($_data->frame_type=="N") {
				?>
			</td>
		</tr>
	</table>
</body>
</html>
<? } ?>

<!--
<script type="text/javascript" src="/upload/js/jquery-1.7.1.min.js"></script>
<script type="text/javascript">var $j= jQuery.noConflict();</script>
<script language="javascript" type="text/javascript" src="<?=$Dir?>js/miniCalendar.js"></script>
<script language="javascript" type="text/javascript" src="/upload/js/jquery.gmallTab.js"></script>-->
<link rel="stylesheet" type="text/css" href="/css/common.css" />

<? if($_data->align_type=="Y"){ ?>
	<div id="wrapTop" style="text-align:center;">
<? }else{ ?>
	<div id="wrapTop">
<? } ?>

		<style>
			body{background:#f5f6fa;}
			#wrapTop{text-align:center;margin:0 auto;margin-bottom:10px;}
			.topMinishopLogoAndSearch{float:left;margin-left:30px;}
			.topMinishopLogo{float:right;margin-top:7px;margin-right:20px;}
			.topPrSearch2{display:none;float:left;width:220px;}
			.topSearch2{clear:both;height:25px;padding:0px 5px;background:#ffffff;overflow:hidden;}
			.searchInput2 input{float:left;width:190px;height:25px;line-height:25px;text-align:left;border:0px;}

			.topMenuRight{float:right;position:relative;}
			.topMenuRight .prMenu{position:absolute;bottom:0px;right:0px;}
			.topMenuRight .prMenu li{float:left;padding-right:15px;font-weight:600;font-family:Nanum Gothic;letter-spacing:-1px;}
			.topMenuRight .prMenu li a:link{color:#777777;font-size:15px;}
			.topMenuRight .prMenu li a:hover{color:#777777;font-size:15px;}
			.topMenuRight .prMenu li a:visited{color:#777777;font-size:15px;}
			
			#mypageMenuAll{position:absolute;display:none;top:30px;left:0px;width:120px;padding:10px 0px;background:#ffffff;border:1px solid #d32a2f;z-index:10;}
			#mypageMenuAll li{clear:both;width:98%;padding:4px 4px 4px 10px;text-align:left;letter-spacing:-1px;}
			#mypageMenuAll li a{display:block;height:auto;line-height:160%;}
			#mypageMenuAll li a:hover{color:#242424;}

			.topCategory{float:left;margin-left:40px;}
			.topCategory li{float:left;padding:0px 20px;height:41px;line-height:41px;}
			.topCategory li a:link{color:#ffffff;font-size:15px;font-weight:600;}
			.topCategory li a:active{color:#ffffff;font-size:15px;font-weight:600;}
			.topCategory li a:hover{color:#ffffff;font-size:15px;font-weight:600;}
			.topCategory li a:visited{color:#ffffff;font-size:15px;font-weight:600;}
			/*
			.topMinishopCategoryMenu{float:right;margin-right:70px;}
			.topMinishopCategoryMenu li{float:left;background:url("/data/design/img/top/t_menu_line.gif") no-repeat;background-position:0% 50%;height:24px;line-height:24px;}
			*/
			.topMinishopCategoryMenu{float:right;}
			.topMinishopCategoryMenu li{float:left;}
			.topMinishopCategoryMenu .firstLi{background:none;}
			.topMinishopCategoryMenu .firstLi a{height:22px;line-height:20px;margin-top:4px;border:1px solid #ddd;border-radius:3px;box-sizing:border-box;}
			.topMinishopCategoryMenu li a{display:inline-block;*display:inline;*zoom:1;height:30px;line-height:30px;padding:0px 10px;font-size:12px;}
			.topMinishopCategoryMenu li:last-child a{padding:0px;padding-left:10px;}
			/*
			.topMinishopCategoryMenu li a:link{color:#ffffff;font-size:12px;}
			.topMinishopCategoryMenu li a:active{color:#ffffff;font-size:12px;}
			.topMinishopCategoryMenu li a:hover{color:#ffffff;font-size:12px;}
			.topMinishopCategoryMenu li a:visited{color:#ffffff;font-size:12px;}
			*/
		</style>

		<div style="text-align:center;">
			<? /*<div style="width:1440px;margin:0 auto;padding:10px 0px;background:#ea2f36;overflow:hidden;">*/ ?>
			<div style="width:1440px;margin:0 auto;padding:4px 0px;border-bottom:0px solid #ea2f36;">
				<div class="topMinishopLogoAndSearch">
					<? /*<div class="topMinishopLogo"><a href="<?//=$Dir.MainDir?//>main.php" <?//=$main_target?//>><img src="/data/design/img/top/t_logo_minishop_b.gif" border="0" alt="" /></a></div>*/ ?>
					<div class="topPrSearch2">
						<div class="topSearch2">
							<!--Search-->
							<form name="search_tform" method="get" action="<?=$Dir.FrontDir?>productsearch.php" <?=$main_target?>>
								<!--input type="hidden" name="searchType" id="searchType" value="1" /-->
								<div class="searchInput2"><input type="text" name="search" value="<?=$_POST["search"]?>" onKeyDown="CheckKeyTopSearch();" /></div>
							</form>
							<div><A HREF="javascript:TopSearchCheck();" style="float:right;"><img src="/data/design/img/top/search_bt.gif" border="0" width="21px" height="21px" style="position:relative;top:-23px;" alt="" /></a></div>
						</div>
					</div>
				</div>

				<ul class="topMinishopCategoryMenu">
					<? if(strlen($_ShopInfo->getMemid())==0){ ######## 로그인을 안했다#######?>
						<input type="hidden" name="id" size="10" />
						<input type="hidden" name="passwd" size="10" onKeyDown="TopCheckKeyLogin();" />
						<li class="firstLi"><a href="<?=$Dir.FrontDir?>login.php?chUrl=<?=getUrl()?>">로그인</a></li>
						<li><a href="<?=$Dir.FrontDir?>member_agree.php">회원가입</a></li>
						<? /*<li><a href="<?=$Dir.FrontDir?>findpwd.php">아이디/비밀번호 찾기</a></li>*/ ?>
						<? if(isWholesale() == 'Y') { ?>
							<li><a href="<?=$Dir.FrontDir?>member_agree.php?memtype=C">도매회원신청</a></li>
						<? } ?>
					<? }else{ ?>
						<li class="firstLi"><a href="javascript:logout();">로그아웃</a></li>
						<li><a href="<?//=$Dir.FrontDir?>mypage_usermodify.php">정보수정</a></li>
					<? } ?>
					<li><a href="<?=$Dir.FrontDir?>basket.php">장바구니</a></li>
					<? if(strlen($_ShopInfo->getMemid())==0){ ######## 로그인을 안했다#######?>
					<? }else{ ?>
					<li><a href="<?=$Dir.FrontDir?>mypage_orderlist.php">주문배송</a></li>
					<li style="position:relative;">
						<a href="<?=$Dir.FrontDir?>mypage.php" onMouseOver="mypageView('mypageMenuAll','open')" onMouseOut="mypageView('mypageMenuAll','out')">마이페이지</a>
						<ul id="mypageMenuAll" onMouseOver="mypageView('mypageMenuAll','over')" onMouseOut="mypageView('mypageMenuAll','out')">
							<li><a href="#">주문내역</a></li>
							<li><a href="#">관심상품</a></li>
							<li><a href="#">쿠폰관리</a></li>
							<li><a href="#">포인트관리</a></li>
							<li><a href="#">회원정보</a></li>
						</ul>
					</li>
					<? } ?>
					<li><a href="<?=$Dir.FrontDir?>community.php?code=1">고객센터</a></li>
				</ul>
				<div class="topMinishopLogo"><a href="<?=$Dir.MainDir?>main.php" <?=$main_target?>><img src="/data/design/img/top/t_logo_minishop_b.gif" border="0" alt="" /></a></div>
				<div style="clear:both;"></div>
			</div>
		</div>
	</div>

	<script language="javascript">
		<!--
		// 마이페이지 메뉴
		function mypageView(obj,type){
			var obj;
			var mypageMenuAll = eval("document.all." + obj);

			if(type=='open'){ mypageMenuAll.style.display = "block";
			}else if (type == 'over'){ mypageMenuAll.style.display = "block";
			}else if (type == 'out'){ mypageMenuAll.style.display = "none";
			}
		}
		//-->
	</script>