<?
if(substr(getenv("SCRIPT_NAME"),-10)=="/menup.php"){
	header("HTTP/1.0 404 Not Found");
	exit;
}



if ($_data->frame_type!="N") include($Dir.MainDir.$_data->onetop_type.".php");
else if($_data->align_type=="Y") echo "<center>";

$leftbody="";
switch(substr(strrchr(getenv("SCRIPT_NAME"),"/"),1)) {
	case "productsearch.php":	//�˻�����
		$design_type="SEA";
		break;
	case "productlist.php":	//��ǰ ī�װ���
		$design_type=substr($_REQUEST["code"],0,3);
		break;
	case "productdetail.php":
		$design_type=(strlen($_REQUEST["code"])==12?substr($_REQUEST["code"],0,3):substr($_REQUEST["productcode"],0,3));
		break;
	case "board.php":
	case "couponlist.php":
	case "productgift.php":
	case "attendance.php":
	case "member_urlhongbo.php":
		$design_type="BOA";
		break;
	case "productblist.php":
		$design_type="BRL";
		break;
	case "productbmap.php":
		$design_type="BRM";
		break;
	case "order.php":
	case "orderend.php":
		$design_type="ORD";
		break;

	case "mypage.php":
	case "mypage_coupon.php":
	case "mypage_memberout.php":
	case "mypage_orderlist.php":
	case "mypage_rentallist.php":
	case "mypage_bookinglist.php":
	case "mypage_bookinglist_week.php":
	case "mypage_bookinglist_day.php":
	case "mypage_reservelist.php":
	case "mypage_personal.php":
	case "mypage_reserve.php":
	case "mypage_usermodify.php":
	case "mypage_custsect.php":
	case "mypage_promote.php":
	case "mypage_gonggu.php":
	case "mypage_gonggu1.php":
	case "mypage_gonggu2.php":
	case "mypage_gonggu3.php":
	case "mypage_delivery.php":
	case "wishlist.php":
	case "reviewall.php":
	case "basket.php":
		if(strlen($_ShopInfo->getMemid())>0) {
			$design_type="MYP";
		}else{
			$design_type="";
		}
		break;
	case "member_agree.php":
	case "member_join.php":
	case "login.php":
	case "findpwd.php":
	case "venderProposal.php":
		$design_type="MEM";
		break;

	case "community.php":
	case "newpage.php":
		if (strlen($newobj->menu_code)>0 && $newobj->menu_code!="MAI") $design_type=$newobj->menu_code; else $design_type="";
		break;

	case "index.php":
	case "main.php":
	case "productnew.php":
	case "producthot.php":
	case "productbest.php":
	case "productspecial.php":
		$design_type="MAI";
		break;
	default:
		if (substr(getenv("SCRIPT_NAME"),0,10)=="/main/main") $design_type="MAI";
		else $design_type="";
}

if($board == 'qna' || $board == 'notice' || $board == 'faq' || $board == 'experience'){
	$design_type="NE4";
}

//echo $design_type;

$sql = "SELECT body FROM ".$designnewpageTables." WHERE type='leftmenu' AND code='".$design_type."' ";
$result=mysql_query($sql,get_db_conn());
if ($row=mysql_fetch_object($result)) {
	$leftbody=$row->body;
	$leftbody=str_replace("[DIR]",$Dir,$leftbody);
	mysql_free_result($result);
} else {
	mysql_free_result($result);
	$result=mysql_query("SELECT * FROM tbldesign",get_db_conn());
	if ($row=mysql_fetch_object($result)) {
		$leftbody=$row->body_left;
		$leftbody=str_replace("[DIR]",$Dir,$leftbody);
	}
	mysql_free_result($result);
}

//��������
$match=array();
$default_lnotice=array("1","Y","Y","4","N","2");
if (preg_match("/\[NOTICE([0-9NY_]{1,9})\]/",$leftbody,$match)) {
	$match_array=explode("_",$match[1]);
	for ($i=0;$i<strlen($match_array[0]);$i++) {
		$default_lnotice[$i]=$match_array[0][$i];
	}
	$lnotice_yn="Y";
}
$lnotice_type=$default_lnotice[0];	// �������� Ÿ��
$lnotice_title=$default_lnotice[1];	// �������� Ÿ��Ʋǥ�ÿ���
$lnotice_gan=$default_lnotice[2];		// �������� ���� ����
$lnotice_new=$default_lnotice[3];		// �������� �ű� ������ ��뿩��
$lnotice_timegap=$default_lnotice[4]*24; // �������� �űԾ����� ���� ��¥
$lnotice_ganyes="YES";
$lnotice_titlelen=(($match_array[1]+0)>200)?"200":($match_array[1]+0); // �������� ������ ����

//����������
$match=array();
$default_linfo=array("1","Y","4");
if (preg_match("/\[INFO([0-9NY_]{1,7})\]/",$leftbody,$match)) {
	$match_array=explode("_",$match[1]);
	for ($i=0 ; $i < strlen($match_array[0]) ; $i ++) {
		$default_linfo[$i]=$match_array[0][$i];
	}
	$linfo_yn="Y";
}
$linfo_type=$default_linfo[0];	// ���������� Ÿ��
$linfo_title=$default_linfo[1];	// ���������� Ÿ��Ʋǥ�ÿ���
$linfo_gan=$default_linfo[2];		// ���������� ���� ����
$linfo_ganyes="YES";
$linfo_titlelen=(($match_array[1]+0)>200)?"200":($match_array[1]+0); // ���������� ������ ����


$shop_count=$_ShopInfo->getShopCount();
$searchkeyword="";
if($posnum=strpos($leftbody,"[SEARCHKEYWORD")) {
	$s_tmp=explode("_",substr($leftbody,$posnum+1,strpos($leftbody,"]",$posnum)-$posnum-1));
	$flength=(int)$s_tmp[1];
	if($flength==0) $flength=80;

	$searchkeyword="<input type=text name=search value=\"".$_POST["search"]."\" onkeydown=\"CheckKeyLeftSearch()\" style=\"width:$flength\">";
}
?>
<SCRIPT LANGUAGE="JavaScript">
<!--
var quickview_path="<?=$Dir.FrontDir?>product.quickview.xml.php";
var quickfun_path="<?=$Dir.FrontDir?>product.quickfun.xml.php";
function sendmail() {
	window.open("<?=$Dir.FrontDir?>email.php","email_pop","height=100,width=500");
}

//ī�װ� ��
function categoryView(obj,type){
	var obj;
	var div = eval("document.all." + obj);

	if(type == 'open'){
		div.style.display = "block";
	}else if (type == 'over'){
		div.style.display = "block";
	}else if (type == 'out'){
		div.style.display = "none";
	}
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

function lpoll_result(type,code) {
	if(type=="result") {
		k=0;
		for (i=0;i<document.lpoll_form.poll_sel.length;i++) {
			if(document.lpoll_form.poll_sel[i].checked) {
				url="<?=$Dir.FrontDir?>survey.php?type=result&survey_code="+code+"&val="+document.lpoll_form.poll_sel[i].value;
				k=1;
			}
		}
		if (k==1) {
			window.open(url,"survey","width=450,height=400,scrollbars=yes");
		} else {
			alert ("��ǥ�Ͻ� �׸��� ������ �ּ���");return;
		}
	} else {
		window.open ("<?=$Dir.FrontDir?>survey.php?type=view&survey_code="+code,"survey","width=450,height=400,scrollbars=yes");
	}
}

<? if(substr($_data->layoutdata["MOUSEKEY"],3,1)=="Y"){?>
function funkeyclick() {
    if (navigator.appName=="Netscape" && (e.which==3 || e.which==2)) return;
    else if (navigator.appName=="Microsoft Internet Explorer" && (event.button==2 || event.button==3 || event.keyCode==93)) return;

    if(navigator.appName=="Microsoft Internet Explorer" && (event.ctrlKey && event.keyCode==78)) return false;
}
document.onmousedown=funkeyclick;
document.onkeydown=funkeyclick;
<? }?>
//-->
</SCRIPT>

<?
if(substr($_data->layoutdata["SHOPBGTYPE"],0,1)=="B") {			//���� ����
	echo "<style>\n";
	if(substr($_data->layoutdata["SHOPBGTYPE"],1,1)=="Y") {
		echo "#tableposition { background-color: transparent; }\n";
	} else {
		echo "#tableposition { background-color: #FFFFFF; }\n";
	}
	if(substr($_data->layoutdata["BGCOLOR"],0,1)=="N") {
		echo "BODY {background-color: ".(strlen(substr($_data->layoutdata["BGCOLOR"],1,7))==7?substr($_data->layoutdata["BGCOLOR"],1,7):"#FFFFFF")."}\n";
	} else {
		echo "BODY {background-color: transparent}\n";
	}
	echo "</style>\n";
} else if(substr($_data->layoutdata["SHOPBGTYPE"],0,1)=="I") {	//��׶��� ����
	echo "<style>\n";
	if(substr($_data->layoutdata["SHOPBGTYPE"],1,1)=="N") {
		echo "#tableposition { background-color: #FFFFFF; }\n";
	} else {
		echo "#tableposition { background-color: transparent; }\n";
	}
	if(file_exists($Dir.DataDir."shopimages/etc/background.gif")) {
		echo "BODY {\n";
		echo "background-image: url('".$Dir.DataDir."shopimages/etc/background.gif');\n";
		$background_repeat=array("A"=>"repeat","B"=>"repeat-x","C"=>"repeat-y","D"=>"no-repeat");
		echo "background-repeat: ".$background_repeat[substr($_data->layoutdata["BACKGROUND"],2,1)].";\n";
		$background_position=array("A"=>"top left","B"=>"top center","C"=>"top right","D"=>"center left","E"=>"center center","F"=>"center right","G"=>"bottom left","H"=>"bottom center","I"=>"bottom right");
		echo "background-position: ".$background_position[substr($_data->layoutdata["BACKGROUND"],1,1)].";\n";
		if(substr($_data->layoutdata["BACKGROUND"],0,1)=="Y") {
			echo "background-attachment: fixed;\n";
		}
	}
	echo "</style>\n";
}
?><style type="text/css">
.zbutton{background-color:#2b91af;
    border-radius: 10 px;
    box-shadow: 0 2px 3px rgba(0, 0, 0, 0.3);
    color: #fff;
    cursor: pointer;
    display: inline-block;
    padding: 10px 20px;
    text-align: center;
    text-decoration: none
}
.zbutton: hover {background-color: #1e1e1e}
.zbutton>span{font-size:84%}
.zbutton.b-close,.zbutton.bClose{border-radius:7px 7px 7px 7px;box-shadow:none;font:bold 131% sans-serif;padding:0 6px 2px;position:absolute;right:-7px;top:-7px}


#zoomDiv{
   background-color: #fff;
   border-radius: 10px 10px 10px 10px;
   box-shadow: 0 0 25px 5px #999;color:#111;
   display: none;
   min-width: 450px;
   padding: 25px
}
</style>
<div id="zoomDiv">
	<span class="zbutton b-close" style="width:30px;"><span>X</span></span>
	<div class="zoomContent" style="height: auto; width: auto;"></div>
</div>
<table border=0 width="<?=($_data->layoutdata["SHOPWIDTH"]>0?$_data->layoutdata["SHOPWIDTH"]:"980")?>" cellpadding=0 cellspacing=0 id="tableposition">
<tr>
	<td width=100% valign=top>
	<table border=0 cellpadding=0 cellspacing=0 width=100% height=100%>
	<col width="<?=$_data->layoutdata["SHOPLEFTMENUWIDTH"]?>"></col>
	<col width="*"></col>
	<tr>
		<td valign=top>
		<!-- ���������� ���ʸ޴� ���� -->

<?
		if(strlen($leftbody)>0) {
			include ($Dir.MainDir."menu_text.php");
			include ($Dir."lib/leftevent.php");

			$pattern=array("(\[VISIT\])","(\[VISIT2\])","(\[RSS\])","(\[SEARCHFORMSTART\])","(\[SEARCHKEYWORD((\_){0,1})([0-9]{0,3})\])","(\[SEARCHOK\])","(\[SEARCHFORMEND\])","(\[EMAIL\])","(\[LOGIN\])","(\[LOGOUT\])","(\[MEMBEROUT\])","(\[LOGINFORM\])","(\[LOGINFORMU\])","(\[BRANDMAP\])","(\[REVIEW\])","(\[BASKET\])","(\[ORDER\])","(\[PRODUCTNEW\])","(\[PRODUCTBEST\])","(\[PRODUCTHOT\])","(\[PRODUCTSPECIAL\])","(\[RESERVEVIEW\])","(\[MYPAGE\])","(\[MEMBER\])","(\[AUCTION\])","(\[GONGGU\])","(\[ESTIMATE\])","(\[SHOPTEL([a-zA-Z0-9_\/\-.]{0,})\])","(\[BANNER\])","(\[LEFTEVENT\])","(\[NOTICE([1-4]{1})([YN]{0,1})([1-9]{0,1})([YN]{0,1})([1-9]{0,1})(\_){0,1}([0-9]{0,3})\])","(\[INFO([1-4]{1})([YN]{0,1})([1-9]{0,1})(\_){0,1}([0-9]{0,3})\])","(\[SPEITEM(\_N){0,}\])","(\[POLL(\_N){0,}\])","(\[PRLIST([a-zA-Z0-9_?\/\-.]+)\])","(\[BOARDLIST([a-zA-Z0-9_?\/\-.]+)\])","(\[BRANDLIST((\_){0,1})([0-9]{0,3})\])");

			if(strlen($_ShopInfo->getMemid())>0) {
				$replace=array($shop_count,$shop_count." (<a href=\"".$Dir.MainDir."main.php?type=logout\">Logout</a>)",$Dir.FrontDir."rssinfo.php","<form name=search_lform method=get action=\"".$Dir.FrontDir."productsearch.php\">",$searchkeyword,"javascript:LeftSearchCheck()","</form>","javascript:sendmail()","javascript:alert('�α������Դϴ�.');","javascript:logout()","javascript:memberout()",$left_loginform,$left_loginformu,$Dir.FrontDir."productbmap.php",$Dir.FrontDir."reviewall.php",$Dir.FrontDir."basket.php",$Dir.FrontDir."mypage_orderlist.php",$Dir.FrontDir."productnew.php",$Dir.FrontDir."productbest.php",$Dir.FrontDir."producthot.php",$Dir.FrontDir."productspecial.php",$Dir.FrontDir."mypage_reserve.php",$Dir.FrontDir."mypage.php",$Dir.FrontDir."mypage_usermodify.php",$Dir.AuctionDir."auction.php",$Dir.GongguDir."gonggu.php","javascript:estimate('".$_data->estimate_ok."')",$shoptel,$left_banner,$eventbody,$left_notice,$left_info,$lspeitem,$lpoll,$prlist,$boardlist,$brandlist);
			} else {
				$replace=array($shop_count,$shop_count,$Dir.FrontDir."rssinfo.php","<form name=search_lform method=get action=\"".$Dir.FrontDir."productsearch.php\">",$searchkeyword,"javascript:LeftSearchCheck()","</form>","javascript:sendmail()",$Dir.FrontDir."login.php?chUrl=".getUrl(),$Dir.FrontDir."login.php?chUrl=".getUrl(),$Dir.FrontDir."login.php?chUrl=".getUrl(),$left_loginform,$left_loginformu,$Dir.FrontDir."productbmap.php",$Dir.FrontDir."reviewall.php",$Dir.FrontDir."basket.php",$Dir.FrontDir."mypage_orderlist.php",$Dir.FrontDir."productnew.php",$Dir.FrontDir."productbest.php",$Dir.FrontDir."producthot.php",$Dir.FrontDir."productspecial.php",$Dir.FrontDir."mypage_reserve.php",$Dir.FrontDir."mypage.php",$Dir.FrontDir."member_agree.php",$Dir.AuctionDir."auction.php",$Dir.GongguDir."gonggu.php","javascript:estimate('".$_data->estimate_ok."')",$shoptel,$left_banner,$eventbody,$left_notice,$left_info,$lspeitem,$lpoll,$prlist,$boardlist,$brandlist);
			}

			array_push($pattern,"(\[TODAYSALE\])");
			array_push($replace,$Dir.TodaySaleDir);
			include_once($Dir."front/mypage_groupinfo.php");
			
			
			$userreserve=0;
			if((false !== $userreres = mysql_query("SELECT reserve FROM tblmember WHERE id='".$_ShopInfo->getMemid()."'",get_db_conn())) && mysql_num_rows($userreres)){
				$userreserve = mysql_result($userreres,0,0);
			}
			
			$usercouponcnt = 0;
			if((false !== $usercures = mysql_query("SELECT COUNT(*) as cnt FROM tblcouponissue WHERE id='".$_ShopInfo->getMemid()."' AND used='N' AND (date_end>='".date("YmdH")."' OR date_end='')",get_db_conn()))){
				$usercouponcnt = mysql_result($usercures,0,0);
			}
			
			$userbasketcount = _basketCount('tblbasket_normal',$_ShopInfo->getTempkey());
					
			array_push($pattern,"(\[USERNAME\])","(\[USERGROUP\])","(\[USERRESERVE\])","(\[USERCOUPONCNT\])","(\[USERBASKETCNT\])");
			array_push($replace,$_ShopInfo->getMemname(),$usergroupname,number_format($userreserve),number_format($usercouponcnt),number_format($userbasketcount));
		
		
			$leftbody = preg_replace($pattern,$replace,$leftbody);
		} else {
			//$leftbody="���ʸ޴� ������ �ȵǾ����ϴ�.";
		}
		echo $leftbody;
?>
		<span style="display:none;"><?=$_data->countpath?></span>
		<!-- ���������� ���ʸ޴� �� -->
		</td>
		<td align=center valign=top>