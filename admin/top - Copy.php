<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
INCLUDE ("access.php");

$istop=true;

$topwidth=398;
$topwidth2=97;
$topmaxwidth=890;
$topalign=10;

// S �˻�

if($_GET['member_search'] && $_GET['member_search'] != "")	$_member_search	=	$_GET['member_search'];
if($_POST['member_search'] && $_POST['member_search'] != "")	$_member_search	=	$_POST['member_search'];
$member_search		=	($_member_search && $_member_search != "")		?	$_member_search : "I";
$search							=	(!empty($_GET['search']))							?	$_GET['search'] : "";
// E �˻�

/* �޴� ���� üũ �߰� �κ� */
$access_menu = 1;
if(count($_usersession->taskcode_rip) > 0){
	$access_menu = 0;
}else{
	$access_menu = 1;
}

if($access_menu == 1){
	//$arrmenu = "'','shop','design','member','product','order','auction','market','community','counter','service','marketing','mobile'";
	$arrmenu = "'','shop','member','product','order','auction','market','community','counter','service','mobile'";
	//$imenu = 12;
	$imenu = 10;
}else{
	$arrmenu = "''";
	$tdmenu  = "";
	$imenu	 = 1;
	if($_usersession->istaskcode_rip("sh")){ //��������
		$tdmenu 	.= "<td><a href=\"javascript:GoMenu($imenu,'shop_basicinfo.php');\" HIDEFOCUS=\"true\"><img src=\"images/topmenu_shop_default.gif\"  border=\"0\" name=\"image\" class=\"topNaviMenu\"alt=\"��������\" onMouseOut=\"MouseOut(this,$imenu)\" onMouseOver=\"MouseOver(this,$imenu)\" style=\"filter:blendTrans(duration=0.3)\"></a></td>";
		$arrmenu 	.= ",'shop'";
		$imenu++;
	}
	if($_usersession->istaskcode_rip("de")){ //�����ΰ���
		$tdmenu 	.= "<td><a href=\"javascript:GoMenu($imenu,'design_option.php');\" HIDEFOCUS=\"true\"><img src=\"images/topmenu_design_default.gif\"  border=\"0\" name=\"image\" class=\"topNaviMenu\"onMouseOut=\"MouseOut(this,$imenu)\" onMouseOver=\"MouseOver(this,$imenu)\" style=\"filter:blendTrans(duration=0.3)\"></a></td>";
		$arrmenu 	.= ",'design'";
		$imenu++;
	}
	if($_usersession->istaskcode_rip("me")){ //ȸ������
		$tdmenu 	.= "<td><a href=\"javascript:GoMenu($imenu,'member_list.php');\" HIDEFOCUS=\"true\"><img src=\"images/topmenu_member_default.gif\"  border=\"0\" name=\"image\" class=\"topNaviMenu\"onMouseOut=\"MouseOut(this,$imenu)\" onMouseOver=\"MouseOver(this,$imenu)\" style=\"filter:blendTrans(duration=0.3)\"></a></td>";
		$arrmenu 	.= ",'member'";
		$imenu++;
	}
	if($_usersession->istaskcode_rip("pr")){ //��ǰ����
		$tdmenu 	.= "<td><a href=\"javascript:GoMenu($imenu,'product_code.php');\" HIDEFOCUS=\"true\"><img src=\"images/topmenu_product_default.gif\"  border=\"0\" name=\"image\" class=\"topNaviMenu\"onMouseOut=\"MouseOut(this,$imenu)\" onMouseOver=\"MouseOver(this,$imenu)\" style=\"filter:blendTrans(duration=0.3)\"></a></td>";
		$arrmenu 	.= ",'product'";
		$imenu++;
	}
	if($_usersession->istaskcode_rip("or")){ //�ֹ�/����
		$tdmenu 	.= "<td><a href=\"javascript:GoMenu($imenu,'order_list.php');\" HIDEFOCUS=\"true\"><img src=\"images/topmenu_order_default.gif\"  border=\"0\" name=\"image\" class=\"topNaviMenu\"onMouseOut=\"MouseOut(this,$imenu)\" onMouseOver=\"MouseOver(this,$imenu)\" style=\"filter:blendTrans(duration=0.3)\"></a></td>";
		$arrmenu 	.= ",'order'";
		$imenu++;
	}
	if($_usersession->istaskcode_rip("go")){ //����/���
		$tdmenu 	.= "<td><a href=\"javascript:GoMenu($imenu,'todaysale.php');\" HIDEFOCUS=\"true\"><img src=\"images/topmenu_auction_default.gif\"  border=\"0\" name=\"image\" class=\"topNaviMenu\"onMouseOut=\"MouseOut(this,$imenu)\" onMouseOver=\"MouseOver(this,$imenu)\" style=\"filter:blendTrans(duration=0.3)\"></a></td>";
		$arrmenu 	.= ",'auction'";
		$imenu++;
	}
	if($_usersession->istaskcode_rip("ma")){ //���θ��
		$tdmenu 	.= "<td><a href=\"javascript:GoMenu($imenu,'market_eventpopup.php');\" HIDEFOCUS=\"true\"><img src=\"images/topmenu_market_default.gif\"  border=\"0\" name=\"image\" class=\"topNaviMenu\"onMouseOut=\"MouseOut(this,$imenu)\" onMouseOver=\"MouseOver(this,$imenu)\" style=\"filter:blendTrans(duration=0.3)\"></a></td>";
		$arrmenu 	.= ",'market'";
		$imenu++;
	}
	if($_usersession->istaskcode_rip("co")){ //Ŀ�´�Ƽ
		$tdmenu 	.= "<td><a href=\"javascript:GoMenu($imenu,'community_list.php');\" HIDEFOCUS=\"true\"><img src=\"images/topmenu_community_default.gif\"  border=\"0\" name=\"image\" class=\"topNaviMenu\"onMouseOut=\"MouseOut(this,$imenu)\" onMouseOver=\"MouseOver(this,$imenu)\" style=\"filter:blendTrans(duration=0.3)\"></a></td>";
		$arrmenu 	.= ",'community'";
		$imenu++;
	}
	if($_usersession->istaskcode_rip("st")){ //���м�
		$tdmenu 	.= "<td><a href=\"javascript:GoMenu($imenu,'counter_index.php');\" HIDEFOCUS=\"true\"><img src=\"images/topmenu_counter_default.gif\"  border=\"0\" name=\"image\" class=\"topNaviMenu\"onMouseOut=\"MouseOut(this,$imenu)\" onMouseOver=\"MouseOver(this,$imenu)\" style=\"filter:blendTrans(duration=0.3)\"></a></td>";
		$arrmenu 	.= ",'counter'";
		$imenu++;
	}
	if($_usersession->istaskcode_rip("se")){ //�ΰ�����
		$tdmenu 	.= "<td><a href=\"javascript:GoMenu($imenu,'service_payment.php');\" HIDEFOCUS=\"true\"><img src=\"images/topmenu_service_default.gif\"  border=\"0\" name=\"image\" class=\"topNaviMenu\"onMouseOut=\"MouseOut(this,$imenu)\" onMouseOver=\"MouseOver(this,$imenu)\" style=\"filter:blendTrans(duration=0.3)\"></a></td>";
		$arrmenu 	.= ",'service'";
		$imenu++;
	}
	if($_usersession->istaskcode_rip("mk")){ //������
		$tdmenu 	.= "<td><a href=\"javascript:GoMenu($imenu,'marketing_index.php');\" HIDEFOCUS=\"true\"><img src=\"images/topmenu_marketing_default.gif\"  border=\"0\" name=\"image\" class=\"topNaviMenu\"onMouseOut=\"MouseOut(this,$imenu)\" onMouseOver=\"MouseOver(this,$imenu)\" style=\"filter:blendTrans(duration=0.3)\"></a></td>";
		$arrmenu 	.= ",'marketing'";
		$imenu++;
	}
	if($_usersession->istaskcode_rip("mo")){ //����ϼ�
		$tdmenu 	.= "<td><a href=\"javascript:GoMenu($imenu,'mobile_config.php');\" HIDEFOCUS=\"true\"><img src=\"images/topmenu_mobile_default.gif\"  border=\"0\" name=\"image\" class=\"topNaviMenu\"onMouseOut=\"MouseOut(this,$imenu)\" onMouseOver=\"MouseOver(this,$imenu)\" style=\"filter:blendTrans(duration=0.3)\"></a></td>";
		$arrmenu 	.= ",'mobile'";
		$imenu++;
	}
	($imenu > 1)? $imenu = $imenu - 1 : $imenu = $imenu ;
}
?>

<? INCLUDE ("header.php"); ?>
<script language="javascript" type="text/javascript" src="/js/jquery-1.10.2.js"></script>
<SCRIPT LANGUAGE="JavaScript">
<!--
document.onkeydown = CheckKeyPress;
document.onkeyup = CheckKeyPress;
function CheckKeyPress() {
	ekey = event.keyCode;
	if(ekey == 38 || ekey == 40 || ekey == 112 || ekey ==17 || ekey == 18 || ekey == 25 || ekey == 122 || ekey == 116) {
		event.keyCode = 0;
		return false;
	}
}

function sSearch(){
	var f = document.mSearch;

	if(!f.search.value){
		alert('�˻�� �Է����ּ���.');
		f.search.focus();
		return false;
	}

	if(document.getElementById('member_search').value=="P"){
		f.target="propop";
		f.method="POST";
		window.open("about:blank","propop","width=800,height=400,scrollbars=yes");
		f.action="product_copy_pop.list.php";
	} else if(document.getElementById('member_search').value=="M") {
		f.target="searchpop";
		f.method="GET";
		window.open("about:blank","searchpop","width=1100,height=800,scrollbars=yes");
		document.getElementById('searchKey').value = document.getElementById('search').value;
		f.action="http://www.getmall.co.kr/manual/search.php";
	}else{
		f.target="mempop";
		f.method="POST";
		window.open("about:blank","mempop","width=800,height=400,scrollbars=yes");
		f.action="member_pop_list.php";
	}

	f.submit();
}


//�޴���
function shop_menual() {
	alert("�˼��մϴ�. ��� �� �̿��Ͻñ� �ٶ��ϴ�.");
}

function DescVender_popup() {
	alert("�˼��մϴ�. ��� �� �̿��Ͻñ� �ٶ��ϴ�.");
}

function DescPaygate_popup() {
	alert("�˼��մϴ�. ��� �� �̿��Ͻñ� �ٶ��ϴ�.");
}

function webftp_popup() {
	window.open("design_webftp.popup.php","webftppopup","height=10,width=10");
}

function SendSMS() {
	window.open("sendsms.php","sendsmspop","width=220,height=350,scrollbars=no");
}

function MemberMemo() {
	window.open("member_memoconfirm.php","memopop","width=250,height=120,scrollbars=no");
}

//var menuno = 12;
//var imagename = new Array('','shop','design','member','product','order','auction','market','community','counter','service','marketing','mobile');
var menuno = <?=$imenu?>;
 var imagename = new Array(<?=$arrmenu?>);

function GoMenu(no, url) {
	/*
	alert(document.top.menuimage.length);
	alert(menuno);
	*/
	tobj = $('.topNaviMenu');
	for(i=1;i<=menuno;i++){
	/*
		if(no==i) document.top.image[no-1].src='images/topmenu_'+imagename[no]+'_view.gif';
		else document.top.image[i-1].src='images/topmenu_'+imagename[i]+'_default.gif';
		*/
		if(no==i) tobj[no-1].src='images/topmenu_'+imagename[no]+'_view.gif';
		else tobj[i-1].src='images/topmenu_'+imagename[i]+'_default.gif';
	}

	document.top.clickmenu.value=no;
	parent.bodyframe.location.href=url;
	if(no==0) location.reload();
}

function ChangeMenuImg(no) {
	tobj = $('.topNaviMenu');
	for(i=1;i<=menuno;i++){
		/*
		if(no==i) document.top.image[no-1].src='images/topmenu_'+imagename[no]+'_view.gif';
		else document.top.image[i-1].src='images/topmenu_'+imagename[i]+'_default.gif';*/
		if(no==i) tobj[no-1].src='images/topmenu_'+imagename[no]+'_view.gif';
		else tobj[i-1].src='images/topmenu_'+imagename[i]+'_default.gif';
	}
	document.top.clickmenu.value=no;
}


function MouseOver(obj,no) {
	try {
		var clickmenu = document.top.clickmenu.value;
		obj.filters.blendTrans.stop();
		obj.filters.blendTrans.Apply();
		obj.src = 'images/topmenu_'+imagename[no]+'_view.gif';
		obj.filters.blendTrans.Play();
	} catch ( e ) {}
}

function MouseOut(obj,no) {
	try {
		var clickmenu = document.top.clickmenu.value;
		obj.filters.blendTrans.stop();
		obj.filters.blendTrans.Apply();
		obj.src = 'images/topmenu_'+imagename[no]+'_default.gif';
		if(clickmenu!=0 && no==clickmenu) {
			obj.src = 'images/topmenu_'+imagename[no]+'_view.gif';
		} else if(clickmenu!=0 && no!=clickmenu) {
			document.top.image[clickmenu-1].src = 'images/topmenu_'+imagename[clickmenu]+'_view.gif';
		}
		obj.filters.blendTrans.Play();
	} catch ( e ) {}
}

//-->
</SCRIPT>

<!--<script type="text/javascript" src="http://<?=_SellerUrl?>/incomushop/global.js"></script>-->

<table cellpadding="0" cellspacing="0" width="100%" background="images/top_menuimg1.gif" border="0">
	<tr>
		<td  valign="top">
			<table cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td>
						<table cellpadding="0" cellspacing="0" width="1020" border="0">
							<tr>
								<td><A HREF="javascript:GoMenu(0,'main.php');"><span style="color:#F00; font-size:20px; font-weight:bold">Zamkkan ADMIN</span></a></td>
								<td width="100%" valign="top" style="padding-top:14px;padding-right:20px">

									<div align="left">
										<table cellpadding="0" cellspacing="0" width="100%">
											<tr>
												<td width="100%" style="padding-left:60px;padding-top:5px">
												<iframe src="http://www.getmall.co.kr/frames/admin_main_banner.php"  WIDTH="230px" height="17px" frameborder="0" scrolling="no" marginwidth="0" marginheight="0" name="mainbanner"  allowtransparency="true"></iframe><!-- Ŭ�����̽� 15���� �������� �����̺�Ʈ --></td>
												<td><A HREF="http://www.getmall.co.kr/front/designshop.php" target="_blank"><img src="images/top_menu_designshop.gif" border="0" align=absmiddle></A></td>
												<td><A HREF="http://www.getmall.co.kr/front/specialtymall_program.php" target="_blank"><img src="images/top_menu_11.gif" border="0" align=absmiddle></A></td>
												<td><A HREF="javascript:MemberMemo();"><img src="images/top_menu_memo.gif" border="0" align=absmiddle></A></td>
												<td>
													<? if(setUseVender()==true) {?>
													<a href="javascript:GoMenu(1,'vender_management.php');" style="text-decoration:none;"><img src="images/top_menu_emarket.gif" border="0" align=absmiddle></a>
													<? } else { ?>
													<a href="javascript:alert('������� �� �̴ϼ��� ���θ�(E-market) ���������� ����Ͻ� �� �ֽ��ϴ�.');" style="text-decoration:none;"><img src="images/top_menu_emarket.gif" border="0" align=absmiddle></a>
													<? } ?>
												</td>
												<td><A HREF="javascript:GoMenu(0,'sitemap.php');"><img src="images/top_menu_sitemap.gif" border="0" align=absmiddle></a></td>
												<td><A HREF="http://www.getmall.co.kr/front/mypage.siteConn.php?site=<?=urlencode(readAuthKey().'#'.$_ShopInfo->getShopurl().'#'.rand())?>" target="_blank"><img src="images/top_menu_getmall.gif" border="0" align=absmiddle></A></td>
												<td><a HREF="http://<?=$shopurl?>" name="shopurl" target=_blank><img src="images/top_menu_myshop.gif" border="0" align=absmiddle></a></td>
												<td><A HREF="logout.php"><img src="images/top_menu_logout.gif" border="0" align=absmiddle></a></td>
											</tr>
											<tr><td colspan="9" height="11"></td></tr>
											<tr>
												<td colspan="9" align="right">
<form name="menualSearch" action="http://www.getmall.co.kr/manual/search.php" method="GET" target="_BLANK">

														<table cellpadding="0" cellspacing="0" align="right" border="0">
															<tr>

																<!-- �Ŵ��� �˻��ڽ� ��׶���ó�� ���� 20131024 J.Bum -->
																<script language="JavaScript">
																<!--
																	function changeTabId(){
																		if(document.getElementById("searchKey").value == ""){
																			obj = document.getElementById("searchKey").style;
																			obj.backgroundImage = "url(images/searchbox_bg.gif)";
																			obj.backgroundRepeat = "no-repeat";
																			obj.backgroundPosition = "5px 0px";
																		}
																	}
																//-->
																</script>

																<!-- �޴��� �˻� -->

																<td><input type="text" name="searchKey" class="input" size="23" style="padding-left:5px; font-size:11px; color:#eeeeee; background-color:#6C6C6D; background-image:url(images/searchbox_bg.gif); border-style:none; height:18px; font-family:����;" onBlur="changeTabId();" onFocus="this.style.backgroundImage='';" /></td>
																<td><input type="image" src="images/top_btn_search2.gif"></td>
																</form>

																<td width="50"></td>



																<!--	S : ȸ�� �˻�	-->
																<form name="mSearch" id="mSearch" action="" onsubmit="return sSearch()">
																<td style="padding-right:4px;">
																	<SELECT name="member_search" id="member_search" style="font-size:11px; color:#eeeeee; background-color:#6C6C6D;">
																		<OPTION value="I" selected>ȸ�����̵�</OPTION>
																		<OPTION value="N">ȸ���̸�</OPTION>
																		<OPTION value="P">��ǰ�� �˻�</OPTION>
																		<!-- <OPTION value="M">�޴��� �˻�</OPTION> -->
																	</SELECT>
																	<script type="text/javascript">
																	//<![CDATA[
																	document.getElementById('member_search').value = "<?=$member_search;?>";
																	//]]>
																	</script>
																</td>
																<td><INPUT name="search" id="search" class="input" size="15" style="padding-left:5px; font-size:11px; color:#eeeeee; background-color:#6C6C6D; border-style:none;height:18px;font-family:����" ></td>
																<td><input type="image" src="images/top_btn_search2.gif"></td>
																<INPUT type="hidden" name="searchKey" value="">
																<!--	E : ȸ�� �˻�	-->




															</tr>
														</table>
</form>
												</td>
											</tr>
										</table>
									</div>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td>
						<table cellpadding="0" cellspacing="0" width="100%" border="0">
							<tr>
								<td width="100%" >
									<table cellpadding="0" cellspacing="0" border="0" style="width:1000px;">
										<form name="top" method="post">
										<? /*
										<tr>
											<? if($access_menu == 1){ ?>
											<td><a href="javascript:GoMenu(1,'shop_basicinfo.php');" HIDEFOCUS="true"><img src="images/topmenu_shop_default.gif"  border="0" name="image" class="topNaviMenu" alt="��������" onMouseOut="MouseOut(this,1)" onMouseOver="MouseOver(this,1)" style="filter:blendTrans(duration=0.3)"></a></td>
										<!--	<td><a href="javascript:GoMenu(2,'design_option.php');" HIDEFOCUS="true"><img src="images/topmenu_design_default.gif"  border="0" name="image" class="topNaviMenu" onMouseOut="MouseOut(this,2)" onMouseOver="MouseOver(this,2)" style="filter:blendTrans(duration=0.3)"></a></td> -->
											<td><a href="javascript:GoMenu(3,'member_list.php');" HIDEFOCUS="true"><img src="images/topmenu_member_default.gif"  border="0" name="image" class="topNaviMenu" onMouseOut="MouseOut(this,3)" onMouseOver="MouseOver(this,3)" style="filter:blendTrans(duration=0.3)"></a></td>
											<td><a href="javascript:GoMenu(4,'product_code.php');" HIDEFOCUS="true"><img src="images/topmenu_product_default.gif"  border="0" name="image" class="topNaviMenu" onMouseOut="MouseOut(this,4)" onMouseOver="MouseOver(this,4)" style="filter:blendTrans(duration=0.3)"></a></td>
											<td><a href="javascript:GoMenu(5,'order_list.php');" HIDEFOCUS="true"><img src="images/topmenu_order_default.gif"  border="0" name="image" class="topNaviMenu" onMouseOut="MouseOut(this,5)" onMouseOver="MouseOver(this,5)" style="filter:blendTrans(duration=0.3)"></a></td>
											<td><a href="javascript:GoMenu(6,'todaysale.php');" HIDEFOCUS="true"><img src="images/topmenu_auction_default.gif"  border="0" name="image" class="topNaviMenu" onMouseOut="MouseOut(this,6)" onMouseOver="MouseOver(this,6)" style="filter:blendTrans(duration=0.3)"></a></td>
											<td><a href="javascript:GoMenu(7,'market_eventpopup.php');" HIDEFOCUS="true"><img src="images/topmenu_market_default.gif"  border="0" name="image" class="topNaviMenu" onMouseOut="MouseOut(this,7)" onMouseOver="MouseOver(this,7)" style="filter:blendTrans(duration=0.3)"></a></td>
											<td><a href="javascript:GoMenu(8,'community_list.php');" HIDEFOCUS="true"><img src="images/topmenu_community_default.gif"  border="0" name="image" class="topNaviMenu" onMouseOut="MouseOut(this,8)" onMouseOver="MouseOver(this,8)" style="filter:blendTrans(duration=0.3)"></a></td>
											<td><a href="javascript:GoMenu(9,'counter_index.php');" HIDEFOCUS="true"><img src="images/topmenu_counter_default.gif"  border="0" name="image" class="topNaviMenu" onMouseOut="MouseOut(this,9)" onMouseOver="MouseOver(this,9)" style="filter:blendTrans(duration=0.3)"></a></td>
											<td><a href="javascript:GoMenu(10,'service_payment.php');" HIDEFOCUS="true"><img src="images/topmenu_service_default.gif"  border="0" name="image" class="topNaviMenu" onMouseOut="MouseOut(this,10)" onMouseOver="MouseOver(this,10)" style="filter:blendTrans(duration=0.3)"></a></td>
										<!--	<td><a href="javascript:GoMenu(11,'marketing_index.php');" HIDEFOCUS="true"><img src="images/topmenu_marketing_default.gif"  border="0" name="image" class="topNaviMenu" onMouseOut="MouseOut(this,11)" onMouseOver="MouseOver(this,11)" style="filter:blendTrans(duration=0.3)"></a></td> -->
											<td><a href="javascript:GoMenu(12,'mobile_config.php');" HIDEFOCUS="true"><img src="images/topmenu_mobile_default.gif"  border="0" name="image" class="topNaviMenu" onMouseOut="MouseOut(this,12)" onMouseOver="MouseOver(this,12)" style="filter:blendTrans(duration=0.3)"></a></td>
											<!-- <td><a href="javascript:GoMenu(13,'vender_index.php');" HIDEFOCUS="true"><img src="images/topmenu_vender_default.gif"  border="0" name="image" class="topNaviMenu" onMouseOut="MouseOut(this,13)" onMouseOver="MouseOver(this,13)" style="filter:blendTrans(duration=0.3)"></a></td>
											<td><a href="javascript:GoMenu(14,'event_index.php');" HIDEFOCUS="true"><img src="images/topmenu_event_default.gif"  border="0" name="image" class="topNaviMenu" onMouseOut="MouseOut(this,14)" onMouseOver="MouseOver(this,14)" style="filter:blendTrans(duration=0.3)"></a></td> -->
											<? }else{ ?>
												<?=$tdmenu?>
											<? } ?>
										</tr>*/ ?>
										<tr>
											<? if($access_menu == 1){ ?>
											<td><a href="javascript:GoMenu(1,'shop_basicinfo.php');" HIDEFOCUS="true"><img src="images/topmenu_shop_default.gif"  border="0" name="image" class="topNaviMenu" alt="��������" onMouseOut="MouseOut(this,1)" onMouseOver="MouseOver(this,1)" style="filter:blendTrans(duration=0.3)"></a></td>										
											<td><a href="javascript:GoMenu(2,'member_list.php');" HIDEFOCUS="true"><img src="images/topmenu_member_default.gif"  border="0" name="image" class="topNaviMenu" onMouseOut="MouseOut(this,2)" onMouseOver="MouseOver(this,2)" style="filter:blendTrans(duration=0.3)"></a></td>
											<td><a href="javascript:GoMenu(3,'product_code.php');" HIDEFOCUS="true"><img src="images/topmenu_product_default.gif"  border="0" name="image" class="topNaviMenu" onMouseOut="MouseOut(this,3)" onMouseOver="MouseOver(this,3)" style="filter:blendTrans(duration=0.3)"></a></td>
											<td><a href="javascript:GoMenu(4,'order_list.php');" HIDEFOCUS="true"><img src="images/topmenu_order_default.gif"  border="0" name="image" class="topNaviMenu" onMouseOut="MouseOut(this,4)" onMouseOver="MouseOver(this,4)" style="filter:blendTrans(duration=0.3)"></a></td>
											<td><a href="javascript:GoMenu(5,'todaysale.php');" HIDEFOCUS="true"><img src="images/topmenu_auction_default.gif"  border="0" name="image" class="topNaviMenu" onMouseOut="MouseOut(this,5)" onMouseOver="MouseOver(this,5)" style="filter:blendTrans(duration=0.3)"></a></td>
											<td><a href="javascript:GoMenu(6,'market_eventpopup.php');" HIDEFOCUS="true"><img src="images/topmenu_market_default.gif"  border="0" name="image" class="topNaviMenu" onMouseOut="MouseOut(this,6)" onMouseOver="MouseOver(this,6)" style="filter:blendTrans(duration=0.3)"></a></td>
											<td><a href="javascript:GoMenu(7,'community_list.php');" HIDEFOCUS="true"><img src="images/topmenu_community_default.gif"  border="0" name="image" class="topNaviMenu" onMouseOut="MouseOut(this,7)" onMouseOver="MouseOver(this,7)" style="filter:blendTrans(duration=0.3)"></a></td>
											<td><a href="javascript:GoMenu(8,'counter_index.php');" HIDEFOCUS="true"><img src="images/topmenu_counter_default.gif"  border="0" name="image" class="topNaviMenu" onMouseOut="MouseOut(this,8)" onMouseOver="MouseOver(this,8)" style="filter:blendTrans(duration=0.3)"></a></td>
											<td><a href="javascript:GoMenu(9,'service_payment.php');" HIDEFOCUS="true"><img src="images/topmenu_service_default.gif"  border="0" name="image" class="topNaviMenu" onMouseOut="MouseOut(this,9)" onMouseOver="MouseOver(this,9)" style="filter:blendTrans(duration=0.3)"></a></td>
											<td><a href="javascript:GoMenu(10,'mobile_config.php');" HIDEFOCUS="true"><img src="images/topmenu_mobile_default.gif"  border="0" name="image" class="topNaviMenu" onMouseOut="MouseOut(this,10)" onMouseOver="MouseOver(this,10)" style="filter:blendTrans(duration=0.3)"></a></td>
											<? }else{ ?>
												<?=$tdmenu?>
											<? } ?>
										</tr>
										<input type="hidden" name="clickmenu" value="0">
										</form>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
		<td style="padding-left:20px;"></td>
	</tr>
</table>
</body>
</html>