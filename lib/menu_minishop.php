<?
	if(substr(getenv("SCRIPT_NAME"),-18)=="/menu_minishop.php"){
		header("HTTP/1.0 404 Not Found");
		exit;
	}
	/* 입점업체 정보노출 관련 jdy */
	include_once($Dir."lib/admin_more.php");

	$_dataShopMoreInfo = getShopMoreInfo();
	/* 입점업체 정보노출 관련 jdy */

	if ($_data->frame_type!="N") include($Dir.MainDir."top_minishop.php");
	else if($_data->align_type=="Y") echo "<center>";

	$clipcopy="http://".$_ShopInfo->getShopurl().(MinishopType=="ON"?"minishop/":"minishop.php?storeid=").$_minidata->id;

	$prdataA=$_MiniLib->prdataA;
	$prdataB=$_MiniLib->prdataB;
	$prdataC=$_MiniLib->prdataC;
	$themeprdataA=$_MiniLib->themeprdataA;
	$themeprdataB=$_MiniLib->themeprdataB;

	$_minidata->shop_width = '1440';

	if($_GET['getmall']=='Y'){
		//_pr($prdataC);
		//_pr($prdataB);
	}
?>
<script src="/js/jquery-ui-1.11.4/external/jquery/jquery.js"></script>
<script src="/js/jquery-ui-1.11.4/jquery-ui.js"></script>
<script type="text/javascript">var $j= jQuery.noConflict();</script>
<script language="javascript" type="text/javascript" src="../js/miniCalendar.js"></script>
<script language="javascript" type="text/javascript" src="/upload/js/jquery.gmallTab.js"></script>
<script language="javascript" type="text/javascript" src="/js/jquery.bpopup.min.js"></script>

<SCRIPT LANGUAGE="JavaScript">
	<!--
	var quickview_path="<?=$Dir.FrontDir?>product.quickview.xml.php";
	var quickfun_path="<?=$Dir.FrontDir?>product.quickfun.xml.php";
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

<style type="text/css">
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

.tableTop .link{color:#ffffff;}

.starmark1{
	width: 15px;
	height: 15px;
	background: url(/upload/img/icon/starmarkbg1.png)no-repeat;
	font-size: 0px;
	display: inline-block;
	overflow: hidden;
	margin-right: 1px;
}

.leftMenuTitle{font-weight:bold;font-size:1.2em;color:#333333;padding-bottom:20px;}
.leftMenuTitle1{font-weight:bold;font-size:1.2em;color:#333333;padding-bottom:10px;padding-top:10px;}
.leftMenuWrap{width:80%;margin:15px auto;}
.leftMenuWrap .leftMenu{width:100%;}
.leftMenuWrap .leftMenu .menu a{font-weight:500;color:#333333;padding:20px 0px 10px;display:block;    -webkit-transition: all 0.2s ease;
    -moz-transition: all 0.3s ease;
    -ms-transition: all 0.3s ease;
    -o-transition: all 0.3s ease;}
.leftMenuWrap .leftMenu .menu a:hover{color:#000000;}
.leftMenuWrap .leftMenu .subMenu a{font-weight:300;padding:3px 0px;display:block;font-size:13px;    -webkit-transition: all 0.2s ease;
    -moz-transition: all 0.3s ease;
    -ms-transition: all 0.3s ease;
    -o-transition: all 0.3s ease;}
.leftMenuWrap .leftMenu .subMenu a:hover{color:#EA2F36;}

.leftMenuWrap .leftMenu .subMenu1{padding-left:4px;padding-top:4px;}
.leftMenuWrap .leftMenu .subMenu1 a{background:url(/data/design/img/sub/icon_minishop_leftmenu.png)no-repeat;    background-position: 0px 9px;font-weight:300;padding:3px 0px 3px 12px;display:block;font-size:12px;    -webkit-transition: all 0.2s ease;
    -moz-transition: all 0.3s ease;
    -ms-transition: all 0.3s ease;
    -o-transition: all 0.3s ease;}
.leftMenuWrap .leftMenu .subMenu1 a:hover{color:#EA2F36;}

.leftMenuWrap .menu{border-top: 1px solid #f1f1f1;}

.leftMenuWrap .leftMenu .subMenu2{padding-left:21px;padding-top:2px;}
.leftMenuWrap .leftMenu .subMenu2 a{background:url(/data/design/img/sub/icon_minishop_leftmenu.png)no-repeat;    background-position: 0px 9px;font-weight:300;padding:3px 0px 3px 12px;display:block;font-size:12px;    -webkit-transition: all 0.2s ease;
    -moz-transition: all 0.3s ease;
    -ms-transition: all 0.3s ease;
    -o-transition: all 0.3s ease;}
.leftMenuWrap .leftMenu .subMenu2 a:hover{color:#EA2F36;}

.btnWrap{overflow:hidden;margin:20px 0px;}
.btnWrap a{float:left;width:35%;padding: 10px;
font-size:13px;
	background: #ffffff;
    border: 1px solid #eeeeee;
    border-radius: 5px;margin-right:2.5%;    -webkit-transition: all 0.2s ease;
    -moz-transition: all 0.3s ease;
    -ms-transition: all 0.3s ease;
    -o-transition: all 0.3s ease;}

.btnWrap a:hover{background:#dcdfe9;border:1px solid #d3d5df;}
</style>

<div id="zoomDiv">
	<span class="zbutton b-close" style="width:30px;"><span>X</span></span>
	<div class="zoomContent" style="height: auto; width: auto;"></div>
</div>

<table border=0 width="<?=$_minidata->shop_width?>" cellpadding="0" cellspacing="0" id="tableposition">
	<!--
	<tr><td height=3></td></tr>
	<tr>
		<td style="padding-top:8px; padding-left:22px">
			<!-- 현재위치 들어가는부분 ($minishop_navi) --//>
			<table border=0 cellpadding=0 cellspacing=0>
				<tr height=19>
					<td style="padding-right:5;padding-bottom:1px;"><?=$strlocation?></td>
					<td style="padding-right:3px;padding-bottom:3px"><A HREF="javascript:ClipCopy('<?=$clipcopy?>')"><img src="<?=$Dir?>images/minishop/btn_addr_copy.gif" border=0></A></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td height=3></td></tr>
	-->

	<tr>
		<td>
			<?
				if($_GET['getmall']=='Y'){
					echo substr($_minidata->top_backimg,-17,6);
					//echo $_minidata->top_backimg;
					//echo "BG : #B3D6E6";
					//echo "FONT : #40555F";
				}
			?>

			<div style="padding:20px;background:#<?=(substr($_minidata->top_backimg,-17,6))?>;border-radius:7px;">
			<!--
			<div style="height:6px;font-size:0px;background: url('/data/design/img/main/top_boxline.gif') no-repeat;"></div>
			<table border="0" cellpadding="0" cellspacing="0" width="100%" style="padding-top:20px;background:url('/data/design/img/main/bg_boxline.gif') repeat-y;">
			-->
			<table border="0" cellpadding="0" cellspacing="0" width="100%">
				<colgroup>
					<col width="35%">
					<col>
					<col width="35%">
				</colgroup>
				<!-- 상단 타이틀부분 들어가는 곳 -->
				<tr>
					<td valign="bottom" style="padding-bottom:10px;padding-left:30px;">
						<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tableTop">
							<tr>
								<td>
									<a href='<?=$Dir.FrontDir?>minishop.php?sellvidx=<?=$_minidata->vender?>'><span style="color:#ffffff;font-size:24px;font-weight:700;"><?=$_minidata->brand_name?></span></a><!--<a href='<?=$Dir.FrontDir?>minishop.php?sellvidx=<?=$_minidata->vender?>'><img src="<?=$_minidata->logo?>" width=165 height=80 border=0></a>-->
									<p style="font-size:11px;" class="link"><?=$clipcopy?><a href="javascript:bookmarksite('[잠깐]<?=$_minidata->brand_name?>','<?=$clipcopy?>')" ><img src="/upload/img/icon/boxplus.png" style="border:0px; margin-left:3px;display:none;vertical-align:middel;alignment-adjust:baseline" /></a></p>
								</td>
							</tr>
						</table>

						<form name=custregminiform method=post>
							<input type=hidden name=sellvidx value="<?=$_minidata->vender?>">
							<input type=hidden name=memberlogin value="<?=(strlen($_ShopInfo->getMemid())>0?"Y":"N")?>">
						</form>
					</td>
					<td style="text-align:center;background:url('<?//=$_minidata->top_backimg?>') no-repeat;background-position:right top;color:#212e37;font-size:22px;font-weight:600;"><!--THANK YOU FOR VISITING OUR STORE--></td>
					<td align="right" valign="bottom" style="padding-right:30px;padding-bottom:10px;color:#fff;">
					<?
					$revcnt = 0;					
					$sql = "select count(*) from tblregiststore where vender='".$_minidata->vender."'";
					if(false !== $res = mysql_query($sql,get_db_conn())){
						$revcnt = mysql_result($res,0,0);
					}
					
					$shopinfo = array();
					$sql = "select * from tblvenderinfo where vender='".$_minidata->vender."'";
					if(false !== $res = mysql_query($sql,get_db_conn())){
						if(mysql_num_rows($res)) $shopinfo = mysql_fetch_assoc($res);
					}
					
					$starmark = 0;
					$sql = "select starmark from tblvenderinfo where vender='".$_minidata->vender."'";
					if(false !== $res = mysql_query($sql,get_db_conn())){
						if(mysql_num_rows($res)) $starmark = mysql_result($res,0,0);
					}
					?>
						<script language="javascript" type="text/javascript">
						function toggleShopInfo(){
							var el = document.getElementById('miniShopInfo');
							if(el.style.display == 'none'){
								el.style.display = '';
							}else{
								el.style.display = 'none';
							}
						}
						</script>
						판매자정보
						<span style="display:inline-block; width:15px; height:15px; background:url(/upload/img/icon/minishop_icon01.png) no-repeat 50% bottom; margin-right:15px; position:relative; cursor:pointer" onclick="javascript:toggleShopInfo();">
							<div style="width:240px; height:75px; padding:15px; border-radius:7px;box-shadow: rgba(0,0,0,.117647) 0 10px 20px;border:1px solid #ff0000; background:#fff; top:20px; left:-110px; position:absolute; display:none" id="miniShopInfo">
								회사명 : <?=$shopinfo['com_name']?><br />
								연락처 : <?=$shopinfo['com_tel']?><br />
								주&nbsp;&nbsp;소 : <?=$shopinfo['com_addr']?>
							</div>
						</span>

						<span style="display:inline-block; width:15px; height:15px; background:url(/upload/img/icon/home.png) no-repeat 50% bottom; margin-right:5px;"></span><A HREF="javascript:custRegistMinishop()" style="color:#fff;">찜한 쇼핑몰(<?=number_format($revcnt)?>)</A>

						<? /*<div style="text-align:right; margin-top:5px;">*/ ?>
							<span style="display:inline-block; margin-left:15px;">판매자만족도 :</span> <? for($i=1;$i<=5;$i++){ $addclass = ($starmark>= $i)?' active':''; ?><div class="starmark1 <?=$addclass?>" style="position:relative;top:2px;">★</div><? } ?>
						<? /*</div>*/ ?>
					</td>

					<!--
					<td valign=top background="<?=$_minidata->top_backimg?>" style="background-repeat:no-repeat;background-position:right top">
						<table border=0 cellpadding=0 cellspacing=0 width=100%>
							<tr>
								<td valign=top>
									<table border=0 cellpadding=0 cellspacing=0 width=88%>
										<tr>
											<td style="color:#<?=$_minidata->top_fontcolor?>;padding:20,0,0,30">
												<FONT style="font-size:13"><B><?=$_minidata->brand_name?></B></font>( <?=$_minidata->prdt_cnt?>개 상품 / <?=$_minidata->id?></FONT>)
												<p><?=$clipcopy?></p>
											</td>
										</tr>
										<tr><td height=5></td></tr>
										<tr>
											<td style="color:#<?=$_minidata->top_fontcolor?>;padding:0,0,0,30">
											<FONT style="font-size:11px;word-spacing:-2px;"><?=$_minidata->brand_description?></font>
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
					-->

				</tr>
			</table>
			</div>

		</td>
	</tr>
	<tr><td height=10></td></tr>
	<tr>
		<td valign=top>
			<table border=0 cellpadding=0 cellspacing=0 width=100% height=100%>
				<tr>
					<td width="242" valign="top" height="100%">
						<? if($_SERVER['PHP_SELF'] != "/front/minishop.productsearch.php"){  ?>
						<div style="width:222px;border:1px solid #eee;border-radius:5px;box-sizing:border-box;background:#<?=($_minidata->color=='EA2F36'?"fff":$_minidata->color)?>;text-align:center;overflow:hidden;">
							<div class="categoryListDiv" style="margin:10px 0px;">
								<table cellpadding="0" cellspacing="0" width="100%" height="100%" border="0">
									<!--<tr>
										<td height=10 valign=top background="<?=$Dir?>images/minishop/bg/<?=$_minidata->color?>_bg_title.gif" style="padding:0,7;"></td>
									</tr>-->
									<tr>
										<!--<td valign=top background="<?=$Dir?>images/minishop/bg/<?=$_minidata->color?>_bg_bg.gif" style="padding-top:5"  height="100%">-->
										<td align="center" valign="top" height="100%">
											<table cellpadding="0" cellspacing="0" border="0"class="leftMenuWrap">
												<? if(substr($_minidata->code_distype,0,1)=="Y") { ?>
												<tr>
													<td align="center">
														<p class="leftMenuTitle">Category</p>
														<table cellspacing="0" cellpadding="0" border="0" class="leftMenu">
															<?
																$sqlqq = "SELECT SUBSTRING(a.productcode,1,3) as prcode, COUNT(*) as prcnt ";
																$sqlqq.= "FROM tblproduct AS a ";
																$sqlqq.= "LEFT OUTER JOIN tblproductgroupcode b ON a.productcode=b.productcode ";
																$sqlqq.= "LEFT OUTER JOIN rent_product rp ON a.pridx=rp.pridx ";
																$sqlqq.= "WHERE 1=1 and (a.vender='".$sellvidx."' and (rp.trust_vender is NULL or rp.trust_vender='0')) ";
																$sqlqq.= "OR (rp.trust_vender='".$sellvidx."' AND rp.trust_vender<>a.vender AND rp.trust_approve='Y') ";
																$sqlqq.= "AND a.display='Y' ";
																$sqlqq.= "AND (a.group_check='N' OR b.group_code='".$_ShopInfo->getMemgroup()."') ";
																$sqlqq.= "GROUP BY prcode ";
																$resultqq=mysql_query($sqlqq,get_db_conn());
																while($rowqq=mysql_fetch_object($resultqq)) {
																	$codesqq["A"][] = $rowqq->prcode;
																	$codesqq["cnt"][$rowqq->prcode] = $rowqq->prcnt;
																}
																mysql_free_result($resultqq);


																$sqltt = "SELECT codeA, codeB, codeC, codeD, code_name FROM tblproductcode ";
																$sqltt.= "WHERE codeA in ('".implode("','",$codesqq["A"])."') and codeB ='000' and codeC ='000' and codeD ='000' ";
																$sqltt.= "AND group_code!='NO' AND (type LIKE 'L%') ";
																$sqltt.= "ORDER BY codeA,codeB,codeC,codeD ASC ";
																$resulttt=mysql_query($sqltt,get_db_conn());
																$nn = 0;
																while($rowtt=mysql_fetch_object($resulttt)) {

																	if($nn>0) echo "<tr><td height=10></td></tr>\n";

																	echo "<tr>\n";
																	echo "	<td  class=\"menu\" >";
																	if(substr($code,0,3)==$rowtt->codeA and substr($code,3,3) == "000"){
																		echo "<A HREF=\"javascript:GoSection('".$_minidata->vender."','10','".$rowtt->codeA.$rowtt->codeB.$rowtt->codeC.$rowtt->codeD."')\" style='color:#ea2f36;'>".$rowtt->code_name."[".(int)$codesqq["cnt"][$rowtt->codeA]."]</A>";
																	}else{
																		echo "<A HREF=\"javascript:GoSection('".$_minidata->vender."','10','".$rowtt->codeA.$rowtt->codeB.$rowtt->codeC.$rowtt->codeD."')\">".$rowtt->code_name."[".(int)$codesqq["cnt"][$rowtt->codeA]."]</A>";
																	}
																	echo "	</td>\n";
																	echo "</tr>\n";

																		//서브 카테고리
																		//if(substr($code,0,3)==$rowtt->codeA){
																			//B

																			$sqlqq1 = "SELECT SUBSTRING(a.productcode,1,6) as prcode, COUNT(*) as prcnt ";
																			$sqlqq1.= "FROM tblproduct AS a ";
																			$sqlqq1.= "LEFT OUTER JOIN tblproductgroupcode b ON a.productcode=b.productcode ";
																			$sqlqq1.= "LEFT OUTER JOIN rent_product rp ON a.pridx=rp.pridx ";
																			$sqlqq1.= "WHERE a.productcode like '".$rowtt->codeA."%' and (a.vender='".$sellvidx."' and (rp.trust_vender is NULL or rp.trust_vender='0')) ";
																			$sqlqq1.= "OR (rp.trust_vender='".$sellvidx."' AND rp.trust_vender<>a.vender AND rp.trust_approve='Y') ";
																			$sqlqq1.= "AND a.display='Y' ";
																			$sqlqq1.= "AND (a.group_check='N' OR b.group_code='".$_ShopInfo->getMemgroup()."') ";
																			$sqlqq1.= "GROUP BY prcode ";
																			$resultqq1=mysql_query($sqlqq1,get_db_conn());
																			while($rowqq1=mysql_fetch_object($resultqq1)) {
																				$codesqq["B"][] = substr($rowqq1->prcode,3,3);
																				$codesqq["cnt"][$rowqq1->prcode] = $rowqq1->prcnt;
																			}
																			mysql_free_result($resultqq1);
																			

																			$sqltt1 = "SELECT codeA, codeB, codeC, codeD, code_name FROM tblproductcode ";
																			$sqltt1.= "WHERE codeA = '".$rowtt->codeA."' and codeB in ('".implode("','",$codesqq["B"])."') and codeC ='000' and codeD ='000' ";
																			$sqltt1.= "AND group_code!='NO' AND (type LIKE 'L%') ";
																			$sqltt1.= "ORDER BY codeA,codeB,codeC,codeD ASC ";
																			$resulttt1=mysql_query($sqltt1,get_db_conn());
																			while($rowtt1=mysql_fetch_object($resulttt1)) {
																				echo "<tr>\n";
																				echo "	<td class=\"subMenu\">";
																				if(substr($code,0,6)==$rowtt1->codeA.$rowtt1->codeB and substr($code,6,3) == "000"){
																					echo "<A HREF=\"javascript:GoSection('".$_minidata->vender."','10','".$rowtt1->codeA.$rowtt1->codeB.$rowtt1->codeC.$rowtt1->codeD."')\" style='color:#ea2f36;'>".$rowtt1->code_name."[".(int)$codesqq["cnt"][$rowtt1->codeA.$rowtt1->codeB]."]</A>";
																				}else{
																					echo "<A HREF=\"javascript:GoSection('".$_minidata->vender."','10','".$rowtt1->codeA.$rowtt1->codeB.$rowtt1->codeC.$rowtt1->codeD."')\">".$rowtt1->code_name."[".(int)$codesqq["cnt"][$rowtt1->codeA.$rowtt1->codeB]."]</A>";
																				}
																				echo "	</td>\n";
																				echo "</tr>\n";


																				if(substr($code,0,6)==$rowtt1->codeA.$rowtt1->codeB){
																					//C
																					$sqlqq2 = "SELECT SUBSTRING(a.productcode,1,9) as prcode, COUNT(*) as prcnt ";
																					$sqlqq2.= "FROM tblproduct AS a ";
																					$sqlqq2.= "LEFT OUTER JOIN tblproductgroupcode b ON a.productcode=b.productcode ";
																					$sqlqq2.= "LEFT OUTER JOIN rent_product rp ON a.pridx=rp.pridx ";
																					$sqlqq2.= "WHERE a.productcode like '".$rowtt1->codeA.$rowtt1->codeB."%' and (a.vender='".$sellvidx."' and (rp.trust_vender is NULL or rp.trust_vender='0')) ";
																					$sqlqq2.= "OR (rp.trust_vender='".$sellvidx."' AND rp.trust_vender<>a.vender AND rp.trust_approve='Y') ";
																					$sqlqq2.= "AND a.display='Y' ";
																					$sqlqq2.= "AND (a.group_check='N' OR b.group_code='".$_ShopInfo->getMemgroup()."') ";
																					$sqlqq2.= "GROUP BY prcode ";
																					$resultqq2=mysql_query($sqlqq2,get_db_conn());
																					while($rowqq2=mysql_fetch_object($resultqq2)) {
																						$codesqq["C"][] = substr($rowqq2->prcode,6,3);
																						$codesqq["cnt"][$rowqq2->prcode] = $rowqq2->prcnt;
																					}
																					mysql_free_result($resultqq2);

																					$sqltt2 = "SELECT codeA, codeB, codeC, codeD, code_name FROM tblproductcode ";
																					$sqltt2.= "WHERE codeA = '".$rowtt1->codeA."' and codeB ='".$rowtt1->codeB."' and codeC in ('".implode("','",$codesqq["C"])."') and codeD ='000' ";
																					$sqltt2.= "AND group_code!='NO' AND (type LIKE 'L%') ";
																					$sqltt2.= "ORDER BY codeA,codeB,codeC,codeD ASC ";
																					$resulttt2=mysql_query($sqltt2,get_db_conn());
																					while($rowtt2=mysql_fetch_object($resulttt2)) {
																						echo "<tr>\n";
																						echo "	<td class=\"subMenu1\">";
																						if(substr($code,0,9)==$rowtt2->codeA.$rowtt2->codeB.$rowtt2->codeC  and substr($code,9,3) == "000"){
																							echo "<A HREF=\"javascript:GoSection('".$_minidata->vender."','10','".$rowtt2->codeA.$rowtt2->codeB.$rowtt2->codeC.$rowtt2->codeD."')\" style='color:#ea2f36;'>".$rowtt2->code_name."[".(int)$codesqq["cnt"][$rowtt2->codeA.$rowtt2->codeB.$rowtt2->codeC]."]</A>";
																						}else{
																							echo "<A HREF=\"javascript:GoSection('".$_minidata->vender."','10','".$rowtt2->codeA.$rowtt2->codeB.$rowtt2->codeC.$rowtt2->codeD."')\">".$rowtt2->code_name."[".(int)$codesqq["cnt"][$rowtt2->codeA.$rowtt2->codeB.$rowtt2->codeC]."]</A>";
																						}
																						echo "	</td>\n";
																						echo "</tr>\n";

																						if(substr($code,0,9)==$rowtt2->codeA.$rowtt2->codeB.$rowtt2->codeC){
																							//D
																							$sqlqq3 = "SELECT SUBSTRING(a.productcode,1,12) as prcode, COUNT(*) as prcnt ";
																							$sqlqq3.= "FROM tblproduct AS a ";
																							$sqlqq3.= "LEFT OUTER JOIN tblproductgroupcode b ON a.productcode=b.productcode ";
																							$sqlqq3.= "LEFT OUTER JOIN rent_product rp ON a.pridx=rp.pridx ";
																							$sqlqq3.= "WHERE a.productcode like '".$rowtt2->codeA.$rowtt2->codeB.$rowtt2->codeC."%' and (a.vender='".$sellvidx."' and (rp.trust_vender is NULL or rp.trust_vender='0')) ";
																							$sqlqq3.= "OR (rp.trust_vender='".$sellvidx."' AND rp.trust_vender<>a.vender AND rp.trust_approve='Y') ";
																							$sqlqq3.= "AND a.display='Y' ";
																							$sqlqq3.= "AND (a.group_check='N' OR b.group_code='".$_ShopInfo->getMemgroup()."') ";
																							$sqlqq3.= "GROUP BY prcode ";
																							$resultqq3=mysql_query($sqlqq3,get_db_conn());
																							while($rowqq3=mysql_fetch_object($resultqq3)) {
																								$codesqq["D"][] = substr($rowqq3->prcode,9,3);
																								$codesqq["cnt"][$rowqq3->prcode] = $rowqq3->prcnt;
																							}
																							mysql_free_result($resultqq3);

																							$sqltt3 = "SELECT codeA, codeB, codeC, codeD, code_name FROM tblproductcode ";
																							$sqltt3.= "WHERE codeA = '".$rowtt2->codeA."' and codeB ='".$rowtt2->codeB."' and codeC ='".$rowtt2->codeC."' and codeD in ('".implode("','",$codesqq["D"])."')  ";
																							$sqltt3.= "AND group_code!='NO' AND (type LIKE 'L%') ";
																							$sqltt3.= "ORDER BY codeA,codeB,codeC,codeD ASC ";
																							$resulttt3=mysql_query($sqltt3,get_db_conn());
																							while($rowtt3=mysql_fetch_object($resulttt3)) {
																								echo "<tr>\n";
																								echo "	<td class=\"subMenu2\">";
																								if(substr($code,0,12)==$rowtt3->codeA.$rowtt3->codeB.$rowtt3->codeC.$rowtt3->codeD){
																									echo "<A HREF=\"javascript:GoSection('".$_minidata->vender."','10','".$rowtt3->codeA.$rowtt3->codeB.$rowtt3->codeC.$rowtt3->codeD."')\" style='color:#ea2f36;'>".$rowtt3->code_name."[".(int)$codesqq["cnt"][$rowtt3->codeA.$rowtt3->codeB.$rowtt3->codeC.$rowtt3->codeD]."]</A>";
																								}else{
																									echo "<A HREF=\"javascript:GoSection('".$_minidata->vender."','10','".$rowtt3->codeA.$rowtt3->codeB.$rowtt3->codeC.$rowtt3->codeD."')\">".$rowtt3->code_name."[".(int)$codesqq["cnt"][$rowtt3->codeA.$rowtt3->codeB.$rowtt3->codeC.$rowtt3->codeD]."]</A>";
																								}
																								echo "	</td>\n";
																								echo "</tr>\n";
																							}
																						}
																					}
																				}
																			}
																	//	}
																	
																	$nn++;
																}
																mysql_free_result($resulttt);
															?>



															<?
																/*
																if(substr($code,0,3)=="000"){

																	for($i=0;$i<count($prdataA);$i++) {
																		$tmpcode=$prdataA[$i]->codeA.$prdataA[$i]->codeB.$prdataA[$i]->codeC.$prdataA[$i]->codeD;

																		if($i>0) echo "<tr><td height=10></td></tr>\n";

																		echo "<tr>\n";
																		echo "	<td  class=\"menu\">";
																		if($tgbn!="10" || $code!=$tmpcode) {
																			if($prdataA[$i]->codeB=='000'){ //1차 카테고리
																				echo "<A HREF=\"javascript:GoSection('".$_minidata->vender."','10','".$tmpcode."')\">".$prdataA[$i]->code_name."[".(int)$_MiniLib->codecnt[substr($tmpcode,0,3)]."]</A>";
																			}else{ //2차 카테고리
																				echo "<A HREF=\"javascript:GoSection('".$_minidata->vender."','10','".$tmpcode."')\">".$prdataA[$i]->code_name."[".(int)$_MiniLib->codecnt[substr($tmpcode,0,6)]."]</A>";
																			}
																		} else {
																			echo "<span style=\"text-decoration:underline\">".$prdataA[$i]->code_name."[".(int)$_MiniLib->codecnt[substr($tmpcode,0,6)]."]</span>";
																		}
																		echo "	</td>\n";
																		echo "</tr>\n";

																		unset($strprdata);
																		for($j=0;$j<count($prdataB[$prdataA[$i]->codeA]);$j++) {
																			$tmpcode=$prdataB[$prdataA[$i]->codeA][$j]->codeA.$prdataB[$prdataA[$i]->codeA][$j]->codeB.$prdataB[$prdataA[$i]->codeA][$j]->codeC.$prdataB[$prdataA[$i]->codeA][$j]->codeD;
																			//if($j>0) $strprdata.=" | ";
																			if($j>0) $strprdata.="  ";
																			if($tgbn!="10" || $code!=$tmpcode) {
																				if($prdataB[$prdataA[$i]->codeA][$j]->codeC=='000'){ //2차 카테고리
																					$strprdata.="<A HREF=\"javascript:GoSection('".$_minidata->vender."','10','".$tmpcode."')\">".$prdataB[$prdataA[$i]->codeA][$j]->code_name."[".(int)$_MiniLib->codecnt[substr($tmpcode,0,6)]."]</A>";
																				}else{ //4차 카테고리
																					$strprdata.="<A HREF=\"javascript:GoSection('".$_minidata->vender."','10','".$tmpcode."')\">".$prdataB[$prdataA[$i]->codeA][$j]->code_name."[".(int)$_MiniLib->codecnt[substr($tmpcode,0,12)]."]</A>";
																				}
																			} else {
																				$strprdata.="<span style=\"text-decoration:underline\">".$prdataB[$prdataA[$i]->codeA][$j]->code_name."[".(int)$_MiniLib->codecnt[substr($tmpcode,0,12)]."]</span>";
																			}
																		}

																		if(strlen($strprdata)>0) {
																			echo "<tr>\n";
																			echo "	<td class=\"subMenu\">".$strprdata."</td>\n";
																			echo "</tr>\n";
																		}
																	}
																}else{
															

																		$codeBsel=substr($code,3,3);
																		$codeCsel=substr($code,6,3);
																		$codeDsel=substr($code,9,3);

																		if($codeBsel == "000"){
																			$ni = 1;
																			$ni2 = 2;
																		}else if($codeCsel == "000"){
																			$ni = 2;
																			$ni2 = 3;
																		}else if($codeDsel == "000"){
																			$ni = 3;
																			$ni2 = 4;
																		}else{
																			$ni = 3;
																			$ni2 = 5;
																		}
																		
																		$a_line = 0;
																		if(substr($code,3*$ni,3)=="000"){

																			echo "<tr>
																				<td style=\"padding-left:15px\"><a href=\"javascript:GoSection('".$_minidata->vender."','10','".substr($code,0,3*$ni)."')\"><b>".$_MiniLib->codename[$code]."[".(int)$_MiniLib->codecnt[substr($code,0,3*$ni)]."]</b></a></td>
																			</tr>
																			";
																			$a_line = 1;
																		}

																		unset($strprdata);
																		for($j=0;$j<count($prdataB[substr($code,0,3*$ni)]);$j++) {

																			$tmpcode=$prdataB[substr($code,0,3*$ni)][$j]->codeA.$prdataB[substr($code,0,3*$ni)][$j]->codeB.$prdataB[substr($code,0,3*$ni)][$j]->codeC.$prdataB[substr($code,0,3*$ni)][$j]->codeD;
																			

																			if($j>0) $strprdata.="  ";
																			if($tgbn!="10" || $code!=$tmpcode) {
																				if($prdataB[substr($code,0,3*$ni)][$j]->codeC=='000'){ //2차 카테고리
																					$strprdata.="<A HREF=\"javascript:GoSection('".$_minidata->vender."','10','".$tmpcode."')\">".$prdataB[substr($code,0,3*$ni)][$j]->code_name."[".(int)$_MiniLib->codecnt[substr($tmpcode,0,3*$ni2)]."]</A>";
																				}else{ //4차 카테고리
																					$strprdata.="<A HREF=\"javascript:GoSection('".$_minidata->vender."','10','".$tmpcode."')\">".$prdataB[substr($code,0,3*$ni)][$j]->code_name."[".(int)$_MiniLib->codecnt[substr($tmpcode,0,3*$ni2)]."]</A>";
																				}
																			} else {
																				$strprdata.="<span style=\"text-decoration:underline\"><b>".$prdataB[substr($code,0,3*$ni)][$j]->code_name."[".(int)$_MiniLib->codecnt[substr($tmpcode,0,3*$ni2)]."]</b></span>";
																			}
																		}
																	
																		if(strlen($strprdata)>0) {
																			echo "<tr>\n";
																			echo "	<td style=\"padding-left:25px\">".$strprdata."</td>\n";
																			echo "</tr>\n";
																		}
																		
																}
																*/
															?>
														</table>
													</td>
												</tr>
												<? } ?>

												<? if(substr($_minidata->code_distype,1,1)=="Y" && count($themeprdataA)>0){ ?>
												<tr>
													<td>
														<table width=100% cellspacing=0 cellpadding=0 border=0>
															<tr>
																<!--<td>&nbsp;&nbsp;<FONT COLOR="<?=$_minidata->fontcolor?>"><B>테마 카테고리</B></FONT></td>-->
																<td style="padding-left:10"><img src="<?=$Dir?>images/minishop/tmcategory_title.gif" border=0></td>
															</tr>
															<tr>
																<td>
																	<table border=0 cellpadding=0 cellspacing=0 width=100%>
																		<?
																			for($i=0;$i<count($themeprdataA);$i++) {
																				$tmpcode=$themeprdataA[$i]->codeA."000";
																				if($i>0) echo "<tr><td height=10></td></tr>\n";
																				echo "<tr>\n";
																			//	echo "	<td><img src=\"".$Dir."images/minishop/icon_catedot.gif\" border=0> ";
																				echo "	<td> ";
																				if($tgbn!="20" || $code!=$tmpcode) {
																					echo "<A HREF=\"javascript:GoSection('".$_minidata->vender."','20','".$tmpcode."')\"><B>".$themeprdataA[$i]->code_name."</B></A>";
																				} else {
																					echo "<FONT style=\"text-decoration: underline;\"><B>".$themeprdataA[$i]->code_name."</B></font>";
																				}
																				echo "	</td>\n";
																				echo "</tr>\n";
																				unset($strprdata);
																				for($j=0;$j<count($themeprdataB[$themeprdataA[$i]->codeA]);$j++) {
																					$tmpcode=$themeprdataB[$themeprdataA[$i]->codeA][$j]->codeA.$themeprdataB[$themeprdataA[$i]->codeA][$j]->codeB;
																					if($j>0) $strprdata.=" | ";
																					if($tgbn!="20" || $code!=$tmpcode) {
																						$strprdata.="<A HREF=\"javascript:GoSection('".$_minidata->vender."','20','".$tmpcode."')\">".$themeprdataB[$themeprdataA[$i]->codeA][$j]->code_name."</A>";
																					} else {
																						$strprdata.="<FONT style=\"text-decoration: underline;\">".$themeprdataB[$themeprdataA[$i]->codeA][$j]->code_name."</FONT>";
																					}
																				}
																				if(strlen($strprdata)>0) {
																					echo "<tr>\n";
																					echo "	<td style=\"padding:5,0,0,15\">".$strprdata."</td>\n";
																					echo "</tr>\n";
																				}
																			}
																		?>
																	</table>
																</td>
															</tr>
														</table>
													</td>
												</tr>
												<? } ?>

												<!--
												<tr><td height=25></td></tr>
												<?//if(strlen($_minidata->cust_info)>0){?>
												<?// if ($_dataShopMoreInfo['info_view']==1 || strlen($_dataShopMoreInfo['info_view']) ==0) { ?>
												<tr>
													<td>
														<table width=100% cellspacing=0 cellpadding=0 border=0>
															<tr>
																<td><img src="<?=$Dir?>images/minishop/menu_cust.gif" border=0></td>
															</tr>
															<tr>
																<td>
																	<table width=100% border=0 cellspacing=0 cellpadding=0>
																		<tr>
																			<td height=18><img src="<?=$Dir?>images/minishop/menu_cust_text01.gif" border=0 alt="문의전화"></td>
																		</tr>
																		<tr><td height=3></td></tr>
																		<tr> 
																			<td height=17><img src="<?=$Dir?>images/minishop/icon_phone01.gif" border=0 align=absmiddle> <?=$_minidata->custdata["TEL"]?></td>
																		</tr>
																		<tr> 
																			<td height=17><img src="<?=$Dir?>images/minishop/icon_fax01.gif" border=0 align=absmiddle> <?=$_minidata->custdata["FAX"]?></td>
																		</tr>
																		<tr> 
																			<td height=17 style="word-break:break-all"><img src="<?=$Dir?>images/minishop/icon_email01.gif" border=0 align=absmiddle> <?=$_minidata->custdata["EMAIL"]?></td>
																		</tr>
																	</table>

																	<table width=100% border=0 cellspacing=0 cellpadding=0>
																		<tr><td height=10></td></tr>
																		<tr>
																			<td><img src="<?=$Dir?>images/minishop/menu_cust_text02.gif" border=0 alt="고객상담시간"></td>
																		</tr>
																		<tr><td height=3></td></tr>
																		<tr>
																			<td>평일 : <?=$_minidata->custdata["TIME1"]?></td>
																		</tr>
																		<tr>
																			<td>토요일 : <?=$_minidata->custdata["TIME2"]?></td>
																		</tr>
																		<tr>
																			<td>일ㆍ공휴일 : <?=$_minidata->custdata["TIME3"]?></td>
																		</tr>
																	</table>
																</td>
															</tr>
														</table>
													</td>
												</tr>
												<tr><td height=5></td></tr>
												<?// } ?>
												<?//}?>
												-->
											</table>
										</td>
									</tr>
									<!--
									<tr><td height=10 valign=top background="<?=$Dir?>images/minishop/bg/<?=$_minidata->color?>_bg_bottom.gif" style="padding:0,7;"></td></tr>
									-->
								</table>
							</div>
						</div>

						<? } ?>


						<table cellpadding="0" cellspacing="0" width="222" border="0" style="margin-top:10px;">
							<tr>
								<td style="padding: 10px;background: #ffffff;border: 1px solid #eeeeee;border-radius: 5px;">
									<form name="MinishopSearchForm">
										<input type="hidden" name="sellvidx" value="" />
										<select name="search_gbn" style="float:left;height:20px;margin-right:5px;font-size:13px;border:0px;">
											<option value="store">미니샵</option>
											<option value="all">전체</option>
										</select>
										<input type=text name="search" value="" onkeydown="if (event.keyCode == 13) return SearchMinishop();" style="float:left;width:110px;height:20px;border:0px;" /> <img src="/data/design/img/top/search_bt.gif" width="21px" height="21px" border="0" style="cursor:hand;float:right;" onClick="SearchMinishop()" />
									</form>
								</td>
							</tr>
						</table>

						<script>
							function SearchMinishop() {
								if(document.MinishopSearchForm.search.value.length<=0) {
									alert("검색어를 입력하세요.");
									document.MinishopSearchForm.search.focus();
									return;
								} else {
									if(document.MinishopSearchForm.search_gbn.value=="all") {
										document.MinishopSearchForm.action="<?=$Dir.FrontDir?>productsearch.php";
										document.MinishopSearchForm.submit();
									} else {
										document.MinishopSearchForm.sellvidx.value="<?=$_minidata->vender?>";
										document.MinishopSearchForm.action="<?=$Dir.FrontDir?>minishop.productsearch.php";
										document.MinishopSearchForm.submit();
									}
								}
							}
						</script>

						<!-- 공지사항 -->
						<table width="222" cellspacing="0" cellpadding="0" border="0" style="margin-top:20px;">
							<tr>
								<td style="padding-bottom:4px;border-bottom:1px solid #eeeeee;">
									<table width="100%" cellspacing="0" cellpadding="0" border="0">
										<tr>
											<td><p class="leftMenuTitle1">Notice <A HREF="javascript:GoNoticeList('<?=$_minidata->vender?>')">+<!--<img src="/data/design/img/sub/mini_notice_more.gif" border="0" alt="" />--></A></p> </td>
											<td align="right"></td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td style="padding-top:10px;">
									<table border=0 cellpadding=0 cellspacing=0 width=100%>
									<?
										$sql = "SELECT date,subject FROM tblvendernotice WHERE vender='".$_minidata->vender."' ";
										$sql.= "ORDER BY date DESC LIMIT 5 ";
										$result=mysql_query($sql,get_db_conn());
										$nums=mysql_num_rows($result);
										while($row=mysql_fetch_object($result)) {
											echo "<tr><td><span style=word-break:break-all;height:16;overflow:hidden;><A HREF=\"javascript:GoNoticeView('".$_minidata->vender."','".$row->date."')\"><B>·</B> ".titleCut(23,strip_tags($row->subject))."</A></span></td></tr>\n";
											echo "<tr><td height=3></td></tr>\n";
										}
										mysql_free_result($result);

										if($nums == 0){
											echo "<tr><td style=\"font-weight:300;color:#bbbbbb;font-size:13px;\">등록된 공지사항이 없습니다.</td></tr>";
										}
									?>
									</table>
								</td>
							</tr>
							<!--
							<tr>
								<td style="padding-top:20px;">
									<div style="float:left;"><a href="/board/board.php?board=qna"><img src="/data/design/img/sub/icon_mini_qna.gif" border="0" alt="" /></a></div>
									<div style="float:right;"><a href="/board/board.php?board=faq"><img src="/data/design/img/sub/icon_mini_faq.gif" border="0" alt="" /></a></div>
								</td>
							</tr>
							-->
						</table>
						<div class="btnWrap">
							<a href="/board/board.php?board=qna">Q&A</a>
							<a href="/board/board.php?board=faq">FAQ</a>
						</div>
					</td>
					<!--<td width="<?=($_minidata->shop_width-200)?>" align=center valign=top nowrap>-->
					<td align="center" valign="top">