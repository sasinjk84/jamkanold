<?
if(strlen($Dir)==0) {
	$Dir="../";
}
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/shopdata.php");

//��ٱ��� ��ǰ ī����
//$basketcount = _basketCount('tblbasket_normal',$_ShopInfo->getTempkey());
if(strlen($_ShopInfo->getMemid())==0) {	//��ȸ��
	$basketcount = _basketCount('tblbasket_normal',$_ShopInfo->getTempkey());
}else{
	$basketcount = _basketCount2('tblbasket_normal',$_ShopInfo->getMemid());
}

if ($_data->frame_type=="N" || strlen($_data->frame_type)==0) {	//��������
	if ((strlen($_REQUEST["id"])>0 && strlen($_REQUEST["passwd"])>0) || $_REQUEST["type"]=="logout" || $_REQUEST["type"]=="exit") {
		include($Dir."lib/loginprocess.php");
		exit;
	}
}

if(file_exists($Dir.DataDir."shopimages/etc/logo.gif")) {
	$width = getimagesize($Dir.DataDir."shopimages/etc/logo.gif");
	$logo = "<img src=\"".$Dir.DataDir."shopimages/etc/logo.gif\" border=0 dynsrc=\"".$Dir.DataDir."shopimages/etc/logo.gif\"  loop=infinite ";
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

		// �˻��ڽ� ���� div
		//function selectValue(obj){
		//	alert("A");
			//var _obj = document.getElementById("select_"+obj);

			
			/*var obj;
			var div = eval("document.all.select_" + obj);

			if (div.style.display == ''){
				div.style.display = "none";
			} else {
				div.style.top = 30; //��ܿ��� ��ǥ
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

			if($_data->align_type=="Y")	echo "<center>";
			if ($_data->frame_type=="N") {
			?>
		</td>
	</tr>
</table>
</body>
</html>
<? } ?>



<!--��ܵ����� ����--->
<script src="/js/jquery-ui-1.11.4/external/jquery/jquery.js"></script>
<script src="/js/jquery-ui-1.11.4/jquery-ui.js"></script>

<script type="text/javascript">var $j= jQuery.noConflict();</script>
<script language="javascript" type="text/javascript" src="<?=$Dir?>js/miniCalendar.js"></script>
<script language="javascript" type="text/javascript" src="/upload/js/jquery.gmallTab.js"></script>
<script language="javascript" type="text/javascript" src="/js/jquery.bpopup.min.js"></script>

<link href="/js/jquery-ui-1.11.4/jquery-ui.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="/css/common.css" />
<link rel="stylesheet" type="text/css" href="/css/jamkan.css" />

<? if($_data->align_type=="Y"){ ?>
	<div id="wrapTop" style="text-align:center;">
<? }else{ ?>
	<div id="wrapTop">
<? } ?>

<div class="topLogoAndSearchWrap">
	<div class="topLogoAndSearch">
		<div class="topLogo">
			<a href="<?=$Dir.MainDir?>main.php" <?=$main_target?>><!--<span>��� �� ��~</span><br />--><?=$logo?></a>
		</div>

		<div class="topPrSearch">
			<? /*
			<ul class="searchTab" id="menu2" style="display:block;">
				<li><img src="/data/design/img/top/t_search_tab1_on.gif" border="0" alt="" /></li>
				<li onClick="DisplaySearchTab(1)"><img src="/data/design/img/top/t_search_tab2.gif" border="0" alt="" /></li>
			</ul>
			<ul class="searchTab" id="menu1" style="display:none;">
				<li onClick="DisplaySearchTab(2)"><img src="/data/design/img/top/t_search_tab1.gif" border="0" alt="" /></li>
				<li><img src="/data/design/img/top/t_search_tab2_on.gif" border="0" alt="" /></li>
			</ul>				
			<div style="clear:both;"></div>
			*/?>
			<div class="topSearch">
				<div>
					<!--Search-->
					<form name="search_tform" method="get" action="<?=$Dir.FrontDir?>productsearch.php" <?=$main_target?>>
						<input type="hidden" name="searchType" id="searchType" value="" />
<!-- 2018.10.24 
						<div class="searchCalendal">-->
							<!--p class="selectLink"><a href="javascript:selectValue('searchType')"><span id="selectsearchTypetitle">All</span></a></p>
							<div id="select_searchType" style="display:none;position:absolute;width:80px;background:#ffffff;padding:5px;border:1px solid #ec2f36;">
								<ul>
									<li><a href="javascript:selectValue('searchType','','All');">All</a></li>
									<li><a href="javascript:selectValue('searchType','1','Rental');">Rental</a></li>
									<li><a href="javascript:selectValue('searchType','2','Sell');">Sell</a></li>
								</ul>
							</div-->
<!-- 2018.10.24 
							<input type="hidden" name="searchSel1" value="0" />
						</div>
-->
						<!-- 2018.10.24 
						<div class="searchCalendal"><input type="text" name="bookingsdate" class="datePickInput" id="bookingsdate" value="<?//=date("Ymd")?>" placeholder="�뿩" readonly /></div>
						<div class="searchCalendal">&nbsp;~&nbsp;</div>
						<div class="searchCalendal"><input type="text" name="bookingedate" class="datePickInput" id="bookingedate" value="<?//=date("Ymd")?>" placeholder="�ݳ�" readonly /></div>
						-->
						<input type="hidden" name="bookingStartDate" id="bookingStartDate">
						<input type="hidden" name="bookingEndDate" id="bookingEndDate">
						<script language="javascript" type="text/javascript">
						$j(function(){
							$j("#bookingsdate" ).datepicker({
								showOn: "both",
								dateFormat:'mm/dd(DD)',
								dayNames: ['��','��','ȭ','��','��','��','��'],
								buttonImage: "/data/design/img/top/mini_cal_calen1.gif",
								minDate: 0,
								buttonImageOnly: true,
								buttonText: "�뿩",
								altField: "#bookingStartDate",
								altFormat: "yymmdd",
								onClose: function( selectedDate ) {
								}
								,onSelect:function(selectedDate,picker){
									$j("#bookingedate").datepicker( "option", "minDate", selectedDate );
								}
							});
							
							$j("#bookingedate" ).datepicker({
								showOn: "both",
								dateFormat:'mm/dd(DD)',
								dayNames: ['��','��','ȭ','��','��','��','��'],
								buttonImage: "/data/design/img/top/mini_cal_calen1.gif",
								minDate: 0,
								buttonImageOnly: true,
								buttonText: "�ݳ�",
								altField: "#bookingEndDate",
								altFormat: "yymmdd",
								onClose: function( selectedDate ) {
								}
								,onSelect:function(selectedDate,picker){
									$j("#bookingsdate").datepicker( "option", "maxDate", selectedDate );

								}
							});
						});
						</script>
						<script>
							show_cal('<?=date("Ymd")?>','bookingStartDateCal','bookingStartDate');
							show_cal('<?=date("Ymd")?>','bookingEndDateCal','bookingEndDate');
						</script>
						<div class="searchInput" id="searchInputDiv"><input type="text" name="search" id="searchInputFld" value="<?=$_POST["search"]?>" placeholder="��� �������� ������, ������" onKeyDown="CheckKeyTopSearch();" /></div>
						<A HREF="javascript:TopSearchCheck();" style="float:right;padding:4px 8px 0px 0px;" id="searchBtnIcon"><img src="/data/design/img/top/search_bt_n.gif" border="0" alt="" /></a>
					</form>
					
				</div>
			</div>

		</div>

		<div class="topMenuRight">
			<ul class="prMenu">
				<li style="border-right:1px solid #ededed;"><a href="/front/community.php?code=1">������</a></li>
				<li><a href="/front/venderProposal.php">��������</a></li>
			</ul>
		</div>
	</div>
</div>
<div class="topMenuWrap">

			<? /*
			<script language="javascript" type="text/javascript">
			$j(function(){
				$j('#topBarMenu').css('width',$j('#topBarMenu>#allCategoryLayerBtn').outerWidth()+$j('#topBarMenu>.topCategory').outerWidth()+$j('#topBarMenu>.topCategoryMenu').outerWidth()+50);
			});
			</script>
			*/ ?>

			<div style="width:1415px;margin:0 auto;padding-top:13px;" id="topBarMenu">
				<div style="float:left;position:relative;z-index:100;padding-top:11px;" id="allCategoryLayerBtn">
					<a href="javascript:categoryAllView();"><img src="/data/design/img/top/t_category_all.gif" border="0" alt="" /></a>
					<? $categoryitems = getCategoryItems($scode); 
					//_pr($categoryitems);
					?>
					<div id="categoryAllList" style="display:none;position:absolute;top:52px;left:0px;width:1415px;background:#ffffff;border:2px solid #ff3300;border-top:none;box-sizing:border-box;">
						<style type="text/css">
							.categoryAllTBL{ display:block; width:100%;}
							.categoryAllTBL th{ height:30px; border-bottom:1px solid #ff0000; border-left:1px solid #efefef; text-align:center;width:5%; color: #ea2f36;}
/*							.categoryAllTBL th{ height:30px; border-bottom:1px solid #ff0000; border-left:1px solid #efefef; text-align:center;width:<?=round(100/count($categoryitems['items']))?>%; color: #ea2f36;}*/
							.categoryAllTBL th.first{ border-left:0px;}
							.categoryAllTBL td{  border-left:1px solid #efefef; padding-top:10px;}
							.categoryAllTBL td span{ display:block; padding:3px 0px; margin-left:20px;}
							.categoryAllTBL td.first{ border-left:0px;}
							#btn_close_categoryAllList{display:block;position:absolute;bottom:0px;right:0px;width:40px;height:40px;line-height:36px;background:#ff0000;color:#fff;font-size:30px;font-weight:100;text-align:center}
						</style>

						<a href="javascript:;" id="btn_close_categoryAllList">&times;</a>

						<div style="width:100%;overflow:hidden;">
							<table border="0" cellpadding="0" cellspacing="0" class="categoryAllTBL">
								<tr>
					<?	
						$isfirst = true;
						foreach($categoryitems['items'] as $cinfo){ 
						
					?>
								<th <? if($isfirst) echo 'class="first"'; ?>><?=$cinfo['code_name']?></th>
					<?	
							$isfirst = false;
						} ?>
								</tr>
								<tr>
					<?	
						$isfirst = true;
						foreach($categoryitems['items'] as $cinfo){ ?>
								<td valign="top" <? if($isfirst) echo 'class="first"'; ?>>
								<? $tsitems =  getCategoryItems($cinfo['codeA'],true);
									foreach($tsitems['items'] as $scinfo){ ?>
									<span><a href="/front/productlist.php?code=<?=$scinfo['linkcode']?>"><?=$scinfo['code_name']?></a></span>
								<? }?>
									</td>
					<?	
							$isfirst = false;
						} ?>
								</tr>
								<tr>
					<?	
						$isfirst = true;
						foreach($categoryitems['items'] as $cinfo){ 
						
					?>
								<td <? if($isfirst) echo 'class="first"'; ?>>&nbsp;</td>
					<?	
							$isfirst = false;
						} ?>
								</tr>
							</table>
						</div>
					</div>
				</div>
				<ul class="topCategory">
				<? /*
					<li><a href="/front/productlist.php?code=003">��ǰ</a></li>
					<li><a href="/front/productlist.php?code=004">����</a></li>
					<li><a href="/front/productlist.php?code=005">�ǻ�</a></li>
					<li><a href="/front/productlist.php?code=006">����</a></li>
					<li><a href="/front/productlist.php?code=007">�����̼�</a></li>
					<li><a href="/front/productlist.php?code=008">���</a></li>
					<li><a href="/front/productlist.php?code=009">�Կ������</a></li>
					<li><a href="/front/productlist.php?code=010">�繫��</a></li>
					<li><a href="/front/productlist.php?code=011">����</a></li>
					*/ 
					foreach($categoryitems['items'] as $cinfo){ ?>
					<li><a href="/front/productlist.php?code=<?=$cinfo['codeA']?>"><?=$cinfo['code_name']?></a></li>
					<? } ?>

					<?php
					/*
					<!--<li><a href="/board/board.php?board=event">EVENT</a></li>-->
					<!--<li><a href="/front/productspecial.php">SALE</a></li>-->
					<!--<li><a href="/front/community.php?code=2">Ŀ�´�Ƽ</a>-->
					*/
					?>
				</ul>
				<ul class="topCategoryMenu">
					<li class="cart" onClick="location.href='<?=$Dir.FrontDir?>basket.php'" style="overflow:hidden;">
						<img src="/data/design/img/top/icon_cart.png" align="absmiddle" style="margin-right:3px;vertical-align:-5px" /><span style="padding:0px 4px">īƮ</span>
						<span style="display:inline-block;width:18px;height:18px;line-height:18px;background:#ffffff;font-weight:bold;color:#ea2f36;text-align:center;border-radius:50px;font-size:10px;vertical-align:1px"><?=$basketcount?></span>
					</li>
					<li class="wishList" onMouseOver="wishlistView('wishProduct','open')" onMouseOut="mypageView('wishProduct','out')">
						<a href="/front/wishlist.php"><img src="/data/design/img/top/icon_wish.png" align="absmiddle" style="margin-right:3px;vertical-align:-5px" />��</a>
						<div id="wishProduct" onMouseOver="wishlistView('wishProduct','over')" onMouseOut="mypageView('wishProduct','out')">
							<!-- <div><a href="#"><img src="/data/design/img/top/btn_wishlist_prev.gif" border="0" alt="" /></a></div> -->
							<?
							if(_empty($_ShopInfo->getMemid())){
								echo "<div style=\"text-align:center;font-size:12px;color:#ea2f36;\">ȸ�� �α�����<br />�ʿ��մϴ�.</div>";
							}else{
								$wishSQL = "SELECT p.tinyimage, p.productcode FROM tblwishlist as w, tblproduct as p  WHERE w.id = '".$_ShopInfo->getMemid()."' AND w.productcode = p.productcode ORDER BY w.date ";
								$wishRES = mysql_query($wishSQL,get_db_conn());
								$wishCnt =  mysql_num_rows($wishRES);
								$wishSQL .= " DESC LIMIT 3 ";
								$wishRES = mysql_query($wishSQL,get_db_conn());
								if( 0 == $wishCnt ) {
									echo "<div style=\"text-align:center;font-size:12px;\">Empty</div>";
								}
								while ( $wishROW = mysql_fetch_assoc($wishRES) ) {
									echo "<div style=\"margin:2px 0px;text-align:center;\"><a href=\"/front/productdetail.php?productcode=".$wishROW['productcode']."\"><img src=\"/data/shopimages/product/".$wishROW['tinyimage']."\" width=\"70\"></a></div>";
								}
								if( $wishCnt > 3 ) {
									echo "<div><a href=\"/front/wishlist.php\"><img src=\"/data/design/img/top/btn_wishlist_next.gif\" border=\"0\" alt=\"������\" /></a></div>";
								}
							}
							?>
						</div>
					</li>
					<? /*
					<li class="signAccount">
						<a href="#" onMouseOver="mypageView('memberMenuAll','open')" onMouseOut="mypageView('memberMenuAll','out')">
							<? if(strlen($_ShopInfo->getMemid())==0){ ######## �α����� ���ߴ�#######?>
							<span style="vertical-align:middle;padding-right:3px;"><img src="/data/design/img/top/icon_member.png"></span>�α���/����
							<?}else{?>
							<span style="vertical-align:middle;padding-right:3px;"><img src="/data/design/img/top/icon_member.png"></span><span style="font-size:12px;"><?=$_ShopInfo->getMemname()?></span>
							<?}?>
						</a>
						<div id="memberMenuAll" onMouseOver="mypageView('memberMenuAll','over')" onMouseOut="mypageView('memberMenuAll','out')">
							<ul>
								<? if(strlen($_ShopInfo->getMemid())==0){ ######## �α����� ���ߴ�#######?>
									<input type="hidden" name="id" size="10" />
									<input type="hidden" name="passwd" size="10" onKeyDown="TopCheckKeyLogin();" />
									<li class="firstLi"><a href="<?=$Dir.FrontDir?>login.php" >�α���</a></li>
<!--									<li class="firstLi"><a href="<?=$Dir.FrontDir?>login.php" class="button white medium">�α���</a></li>-->
									<li><a href="<?=$Dir.FrontDir?>member_classification.php">ȸ������</a></li>
									<li><a href="<?=$Dir.FrontDir?>findpwd.php">ID/PW ã��</a></li>
									<? if(isWholesale() == 'Y') { ?>
										<li><a href="<?=$Dir.FrontDir?>member_agree.php?memtype=C">����ȸ����û</a></li>
									<? } ?>
								<? }else{ ?>
									<li class="firstLi"><a href="javascript:logout();">�α׾ƿ�</a></li>
									<!--<li><a href="<?//=$Dir.FrontDir?>mypage_usermodify.php">��������</a></li>-->
									<li><a href="<?=$Dir.FrontDir?>mypage.php">����������</a></li>
								<? } ?>
								<li><a href="<?=$Dir.FrontDir?>mypage_reserve.php">������ ����</a></li>
								<li><a href="<?=$Dir.FrontDir?>mypage_usermodify.php">ȸ������</a></li>
							</ul>
						</div>
					</li>
					*/ ?>

					<? if(strlen($_ShopInfo->getMemid())==0){ ######## �α����� ���ߴ�#######?>
						<li class="signAccount">
							<a href="<?=$Dir.FrontDir?>login.php" style="display:inline-block;margin-right:20px;"><img src="/data/design/img/top/icon_member.png" align="absmiddle" style="margin-right:3px;vertical-align:-5px" />�α���</a>
							<a href="<?=$Dir.FrontDir?>member_classification.php" style="margin-right:5px">����</a>
						</li>
					<? }else{ ?>
						<li class="signAccount" onMouseOver="mypageView('memberMenuAll','open')" onMouseOut="mypageView('memberMenuAll','out')" onClick="mypageView('memberMenuAll','open')">
							<img src="/data/design/img/top/icon_member.png" align="absmiddle" style="margin-right:3px;vertical-align:-5px" /><span style="color:#fff;font-size:13px"><?=$_ShopInfo->getMemname()?></span>
							<div id="memberMenuAll" onMouseOver="mypageView('memberMenuAll','over')" onMouseOut="mypageView('memberMenuAll','out')">
								<ul>
								<? if(strlen($_ShopInfo->getMemid())==0){ ######## �α����� ���ߴ�#######?>
									<input type="hidden" name="id" size="10" />
									<input type="hidden" name="passwd" size="10" onKeyDown="TopCheckKeyLogin();" />
								<? }else{ ?>
									<li class="firstLi"><a href="javascript:logout();">�α׾ƿ�</a></li>
									<!--<li><a href="<?//=$Dir.FrontDir?>mypage_usermodify.php">��������</a></li>-->
									<li><a href="<?=$Dir.FrontDir?>mypage.php">����������</a></li>
									<li><a href="<?=$Dir.FrontDir?>mypage_reserve.php">������ ����</a></li>
									<li><a href="<?=$Dir.FrontDir?>mypage_usermodify.php">ȸ������</a></li>
								<? } ?>
								</ul>
							</div>
						</li>
					<? } ?>
				</ul>
			</div>
		</div>
	</div>

	<script language="javascript">
		<!--
		//���ã�� �߰�
		function favorite(){
			window.external.AddFavorite("http://<?=$URL?>","ZAMKKAN");
		}

		// �˻� ����/�ݱ�
		function selectValue(obj,key,loc){		
			if(obj == 'start'){
				$j('div[id^=select_]').css('display','none');
				$j('#bookingStartDateCal').css('display','block');
				$j('#bookingEndDateCal').css('display','none');
			}else if(obj == 'end'){
				$j('div[id^=select_]').css('display','none');
				$j('#bookingStartDateCal').css('display','none');
				$j('#bookingEndDateCal').css('display','block');
			}else{
				soff = $j('#searchBtnIcon').offset();
				$j('#bookingStartDateCal').css('display','none');
				$j('#bookingEndDateCal').css('display','none');
				var _obj = document.getElementById("select_"+obj);
				$j('div[id^=select_]').find('div[id!=select_'+obj+']').css('display','none');
				
				if(_obj.style.display == "none"){
					_obj.style.display = "";
				}else{
					_obj.style.display ="none";
				}
				$j('#searchInputFld').css('width',50);
				
				if($j.trim(key).length){
					if($j('form[name=search_tform]').find('input[name='+obj+']').length) $j('form[name=search_tform]').find('input[name='+obj+']').val(key);
					if(document.getElementById("searchSel"+obj)) document.getElementById("searchSel"+obj).value = key;
				}
				
				if($j.trim(loc).length){
					if(document.getElementById("select"+obj+"title")) document.getElementById("select"+obj+"title").innerText = loc;
				}
				
				ioff = $j('#searchInputFld').offset();
				var _w = parseInt(soff.left - ioff.left) -20;
				$j('#searchInputFld').css('width',_w);
				
			}
		}


		function selectValueView(obj,key,loc){
			var _obj = document.getElementById("selectView_"+obj);

			if(_obj.style.display == "none"){
				_obj.style.display = "";
			}else{
				_obj.style.display ="none";
			}
			document.getElementById("searchSelView"+obj).value = key;
			document.getElementById("selectView"+obj+"title").innerText = loc;
		}


		// ���������� �޴�
		function mypageView(obj,type){
			var obj;
			var memberMenuAll = eval("document.all." + obj);

			if(type=='open'){ memberMenuAll.style.display = "block";
			}else if (type == 'over'){ memberMenuAll.style.display = "block";
			}else if (type == 'out'){ memberMenuAll.style.display = "none";
			}
		}

		// WishList 3��
		function wishlistView(obj,type){
			var obj;
			var wishProduct = eval("document.all." + obj);

			if(type=='open'){ wishProduct.style.display = "block";
			}else if (type == 'over'){ wishProduct.style.display = "block";
			}else if (type == 'out'){ wishProduct.style.display = "none";
			}
		}

		// ��ü ī�װ� ����
		function categoryAllView() {
			/*
			if(typeof(document.all["categoryAllList"])=="object") {
				if(document.all["categoryAllList"].style.display=="none") {
					document.all["categoryAllList"].style.display="";
				} else {
					document.all["categoryAllList"].style.display="none";
				}
			}
			*/
		}

		$j("#allCategoryLayerBtn").on("click",function(){
			event.stopPropagation();
			$j("#categoryAllList").stop().toggle();
		});

		$j(document).click(function(){
			$j('#categoryAllList').hide();
		});

/*
		function DisplaySearchTab(index) {
			for (i=1; i<=2; i++) {
				if (index == i) {
					thisMenu = eval("menu" + index + ".style");
					thisMenu.display = "";
				} else {
					otherMenu = eval("menu" + i + ".style");
					otherMenu.display = "none";
				}
			}
			search_tform.searchType.value = index;
		}


		function DisplaySearchViewTab(index) {
			for (i = 1; i <= 2; i++){
				if (index == i) {
					thisMenu = eval("smenu" + index + ".style");
					thisMenu.display = "";
				} else {
					otherMenu = eval("smenu" + i + ".style");
					otherMenu.display = "none";
				}
			}
			form1.searchType.value = index;
		}
*/
		//-->
	</script>