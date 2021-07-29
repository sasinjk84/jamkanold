<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");

INCLUDE ("access.php");

//자동승급설정
include "groupauto.php";
//자동승급설정


$curdate = date("Ymd");

//마스터알람
$masterAlarm = 0;

/* 수수료 변경요청이 있는 경우 */
$sql = "SELECT count(*) as cnt FROM vender_more_info WHERE commission_status='1'";
$result1=mysql_query($sql,get_db_conn());
$_vmdata=mysql_fetch_object($result1);
mysql_free_result($result1);

if($_vmdata->cnt>0){
	$masterAlarm++;
}
/* 수수료 변경요청이 있는 경우 */

/* 입점업체 상담게시판에 문의가 있는 경우 */
$sql = "SELECT COUNT(*) as cnt FROM tblvenderadminqna WHERE re_date is NULL ";
$result2=mysql_query($sql,get_db_conn());
$_qnadata=mysql_fetch_object($result2);
mysql_free_result($result2);

if($_qnadata->cnt>0){
	$masterAlarm++;
}
/* 입점업체 상담게시판에 문의가 있는 경우 */

/* 회원 등급별 할인변경신청이 있는 경우 */
$sql = "SELECT productcode FROM discount_chgrequest";
$result3=mysql_query($sql,get_db_conn());
$_dcdata=mysql_fetch_object($result3);
mysql_free_result($result3);

if($_dcdata->productcode>0){
	$masterAlarm++;
}
/* 회원 등급별 할인변경신청이 있는 경우 */

/* 추천인 적립변경신청이 있는 경우 */
$sql = "SELECT productcode FROM req_chgresellerreserv";
$result4=mysql_query($sql,get_db_conn());
$_revdata=mysql_fetch_object($result4);
mysql_free_result($result4);

if($_revdata->productcode>0){
	$masterAlarm++;
}
/* 추천인 적립변경신청이 있는 경우 */

/* 적립금변경신청이 있는 경우 */
$sql = "SELECT productcode FROM reserve_chgrequest";
$result5=mysql_query($sql,get_db_conn());
$_rcdata=mysql_fetch_object($result5);
mysql_free_result($result5);

if($_rcdata->productcode>0){
	$masterAlarm++;
}
/* 적립금변경신청이 있는 경우 */


/* 추천인 적립금변경신청이 있는 경우 */
$sql = "SELECT productcode FROM reseller_reserve_chgrequest";
$result6=mysql_query($sql,get_db_conn());
$_rrcdata=mysql_fetch_object($result6);
mysql_free_result($result6);

if($_rrcdata->productcode>0){
	$masterAlarm++;
}
/* 추천인 적립금변경신청이 있는 경우 */

?>

<? INCLUDE ("header.php"); ?>
<script>try {parent.topframe.ChangeMenuImg(0);}catch(e){}</script>
<style>td	{font-family:"굴림,돋움";color:#4B4B4B;font-size:12px;line-height:17px;}</style>
<script src="/js/jquery-ui-1.11.4/external/jquery/jquery.js"></script>
<script src="/js/jquery-ui-1.11.4/jquery-ui.js"></script>
<script type="text/javascript">var $j= jQuery.noConflict();</script>
<script type="text/javascript" src="<?=$Dir?>lib/lib.js.php"></script>
<script language="JavaScript" type="text/javascript">
$j(document).ready(function(){
//    $j("#alarmdiv").animate({bottom:'40px'},1000);
});

$j(function(){
  $j(window).scroll(function(){
    //var scr = $j(window).scrollTop();
	var scr = document.body.scrollTop || document.documentElement.scrollTop;
    $j("#alarmdiv").stop().animate({bottom:-(scr-40)},100);
  });
	$j("#alarmdiv").click(function(){ $j("html,body").animate({scrollTop:0}, 100); });
});

function alarmView(){
	$('alarmdiv').setStyle('display','none');
	MasterAlarm.view();
}

function viewHistory(vender) {
	window.open("vender_ch_pop.php?vender="+vender,"history","height=400,width=780,toolbar=no,menubar=no,scrollbars=yes,status=no");
}

</script>
<SCRIPT LANGUAGE="JavaScript">
<!--
var MasterAlarm = {
	view : function(){
		if(document.getElementById && !document.getElementById("create_openwin")) {
			var create_openwin_div = document.createElement("div");
			create_openwin_div.id = "create_openwin";
			document.body.appendChild(create_openwin_div);
		}
		var path="master_alarm.xml.php";
		$('create_openwin').setStyle('display','none');
		$('create_openwin').setStyle('position','absolute');
		$('create_openwin').setStyle('zIndex','9999');
		$('create_openwin').setStyle('width','550');
		$('create_openwin').setStyle('height','400');
		
		move_layer_center($('create_openwin'),550,400);
		var myajax = new Ajax(path,
			{
				onComplete: function(text) {
					var searchTag = new Element('div').setHTML(text);
					$('create_openwin').setHTML(searchTag.innerHTML);
					$('create_openwin').setStyle('display','block');
					$('create_openwin').setStyle('top','30');
				},
				evalScripts : true
			}
		).request();
		return;
	},
	openwinClose : function(){
		$('alarmdiv').setStyle('display','block');
		$('create_openwin').setStyle('display','none');
		$('create_openwin').setHTML("");
		setCookie( "alarm", "no" , 1 ); 
	}
}


function getCookie(name) 
{ 
	var Found = false 
	var start, end 
	var i = 0 
	// cookie 문자열 전체를 검색 
	while(i <= document.cookie.length) 
	{ 
		start = i 
		end = start + name.length 
		// name과 동일한 문자가 있다면 
		if(document.cookie.substring(start, end) == name) 
		{
			Found = true 
			break 
		} 
		i++ 
	}
		
	// name 문자열을 cookie에서 찾았다면 
	if(Found == true) 
	{ 
		start = end + 1 
		end = document.cookie.indexOf(";", start) 
		// 마지막 부분이라 는 것을 의미(마지막에는 ";"가 없다) 
		if(end < start) 
		end = document.cookie.length 
		// name에 해당하는 value값을 추출하여 리턴한다. 
		return document.cookie.substring(start, end) 
	} 
	// 찾지 못했다면 
	return "" 
} 

function setCookie( name, value, expiredays ) { 
	var todayDate = new Date(); 
		todayDate.setDate( todayDate.getDate() + expiredays ); 
		document.cookie = name + "=" + escape( value ) + "; path=/; expires=" + todayDate.toGMTString() + ";" 
} 

//######################################################################################################
//공지사항
function shop_noticeview(type,code) {
	alert("죄송합니다. 잠시 후 이용하시기 바랍니다.");
}

//전체흐름도
function shop_process() {
	alert("죄송합니다. 잠시 후 이용하시기 바랍니다.");
}

//메뉴얼
function shop_menual() {
	alert("죄송합니다. 잠시 후 이용하시기 바랍니다.");
}

//쇼핑몰 TIP&양식
function shop_tip() {
	alert("죄송합니다. 잠시 후 이용하시기 바랍니다.");
}

//제안 및 불편사항 신고
function shop_report() {
	alert("죄송합니다. 잠시 후 이용하시기 바랍니다.");
}

//벤더제한
function not_vender_alert() {
	alert("입점기능 및 미니샵은 몰인몰(E-market) 버전에서만 사용하실 수 있습니다.");
}
//######################################################################################################


function sms_fill() {
	parent.topframe.GoMenu(7,"market_smsfill.php");
}

function ViewPersonal(idx) {
	window.open("about:blank","personal_pop","width=600,height=550,scrollbars=yes");
	document.perform.idx.value=idx;
	document.perform.submit();
}

function ReviewReply(date,prcode) {
	window.open("about:blank","reply","width=400,height=500,scrollbars=no");
	document.reviewform.target="reply";
	document.reviewform.date.value=date;
	document.reviewform.productcode.value=prcode;
	document.reviewform.submit();
}

function ProductInfo(code,prcode,popup,chk) {
	document.prform.code.value=code;
	document.prform.prcode.value=prcode;
	document.prform.popup.value=popup;
	if (popup=="YES") {
		if(chk == "0") { document.prform.action="product_register.add.php";}
		else if(chk == "3") { document.prform.action="social_shopping2.php";}
		else {document.prform.action="product2_register.add.php";}
		document.prform.target="register";
		window.open("about:blank","register","width=820,height=700,scrollbars=yes,status=no");
	} else {
		document.prform.target="_parent";
		document.prform.action="product_register.php";
	}
	document.prform.submit();
}

function OrderDetailView(ordercode) {
	document.detailform.ordercode.value = ordercode;
	window.open("","orderdetail","scrollbars=yes,width=700,height=600");
	document.detailform.submit();
}
function sms_join() {
	window.open("about:blank","smsjoin","width=450,height=460,scrollbars=no,status=yes");
	document.joinform.submit();
}

	// F5 새로 고침 방지
	document.onkeydown = function() {
		if (event.keyCode == 116) {
			event.returnValue = false;
			event.keyCode = 0;
		}
	};

//-->
</SCRIPT>
<style>
 form{margin:0px; padding:0px;border:0px;}
</style>

<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td background="images/con_bg.gif">
			<table cellpadding="0" cellspacing="0" width="1290" style="table-layout:fixed">
				<tr>
					<td valign="top">
						<table cellpadding="0" cellspacing="0" border=0 width="100%">
							<tr>
								<td>
									<table cellpadding="0" cellspacing="0" width="100%">
										<tr>
											<td width="198" valign="top" background="images/main_left_admini_bg.gif">

<!--######################## 왼쪽 시작 ##########################################################################################################################-->
												<table cellpadding="0" cellspacing="0" width="198">
													<tr>
														<td>

															<!--쇼핑몰 기본정보-->
															<table cellpadding="0" cellspacing="0" width="100%">
																<tr>
																	<td><IMG SRC="images/main_left_admini_title.gif" ALT=""></td>
																</tr>
																<tr>
																	<td>
																		<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
																			<TR>
																				<TD valign="top"  background="images/main_left_admini_infobg.gif" height="160">
																					<table border=0 cellpadding="0" cellspacing="0" width="198">
																						<tr>
																							<td align=center valign=top height=100 style="padding-top:14px">

																							<?
																								################# 등록 상품수 #########################
																								$sql = "SELECT COUNT(*) as totproduct FROM tblproduct ";
																								$result=mysql_query($sql,get_db_conn());
																								$row=mysql_fetch_object($result);
																								$totproduct=(int)$row->totproduct;
																								mysql_free_result($result);

																								################# 입점기능 #################
																								$vender_used = setVenderUsed();
																								################# PG셋팅 ###################
																								$pg_used="";
																								if($f=@file(DirPath.AuthkeyDir."pg")) {
																									$pg_used="<font class=\"font_orange4\">셋팅완료</font>";
																									//$img_icon="<img src=\"images/main_icon_ok.gif\">";
																									$pg_icon="<a style=\"cursor:hand\" onclick=\"alert('인증키 디렉토리에 PG셋팅 키가 존재합니다.')\"><font class=\"white_font\">사용중</font></a>";
																								} else {
																									$pg_used="<a href=\"http://www.getmall.co.kr/front/paymentadd.php\" onclick=\"window.open(this.href,'winKcp','width=870px,height=800px,scrollbars=1,resizable=0,locationbars=0');return false;\" target=\"_blank\"><font color=#FFFFFF>미사용</font></a>";

																									$pg_icon="<a href=\"http://www.getmall.co.kr/front/paymentadd.php\" onclick=\"window.open(this.href,'winKcp','width=870px,height=800px,scrollbars=1,resizable=0,locationbars=0');return false;\" target=\"_blank\"><img src=\"images/main_icon_order.gif\"></a>";
																								}
																								################# SMS 잔여 #################
																								$sql = "SELECT id, authkey FROM tblsmsinfo ";
																								$result=mysql_query($sql,get_db_conn());
																								$row=mysql_fetch_object($result);
																								mysql_free_result($result);
																								$sms_id=$row->id;
																								$sms_authkey=$row->authkey;

																								$sms_count="";
																								if(strlen($sms_id)==0 || strlen($sms_authkey)==0) {
																									$sms_count="<A style=\"cursor:hand\" onclick=\"sms_join();\"><font color=#FFFFFF>미사용</font></A>";
																									$sms_icon="<A style=\"cursor:hand\" onclick=\"sms_join();\"><img src=\"images/main_icon_order.gif\"></A>";
																								} else {
																									$smscountdata=getSmscount($sms_id, $sms_authkey);
																									if(substr($smscountdata,0,2)=="OK") {
																										$sms_count="<font class=\"font_orange4\"><b>".substr($smscountdata,3)."</b></font> 건";
																										$sms_icon="<font class=\"white_font\">사용중</font>";
																									} else if(substr($smscountdata,0,2)=="NO") {
																										$sms_count="<A style=\"cursor:hand\" onclick=\"alert(\'SMS 회원 아이디가 존재하지 않습니다. SMS 기본환경 설정에서 SMS 아이디 및 인증키를 정확히 입력하시기 바랍니다.\');\"><font class=\"font_orange4\"><B>인증오류!!</B></font></A>";
																									} else if(substr($smscountdata,0,2)=="AK") {
																										$sms_count="<A style=\"cursor:hand\" onclick=\"alert(\'SMS 회원 인증키가 일치하지 않습니다. SMS 기본환경 설정에서 인증키를 정확히 입력하시기 바랍니다.\');\"><font class=\"font_orange4\"><B>인증오류!!</B></font></A>";
																									} else {
																										$sms_count="<A style=\"cursor:hand\" onclick=\"alert(\'SMS 서버와 통신이 불가능합니다. 잠시 후 이용하시기 바랍니다.\');\"><font class=\"font_orange4\"><B>통신오류!!</B></font></A>";
																									}
																								}
																							?>
																								<table border=0 cellpadding=0 cellspacing=0 width=100%>
																									<tr>
																										<td align=center style="color:#CCCCCC;font-size:11px;font-family:'verdana', 'arial'"><B>Version <?=_IncomuShopVersionNo?></B> <font style="font-size:9px">(<?=_IncomuShopVersionDate?>)</font></td>
																									</tr>
																									<tr>
																										<td height=10></td>
																									</tr>
																								</table>

																								<table border=0 cellpadding=0 cellspacing=0 width=80%>
																								<col width=45></col>
																								<col width=10></col>
																								<col width=></col>
																								<col width=></col>
																									<tr>
																										<td class="font_gray4">상품</td>
																										<td class="font_gray4">:</td>
																										<td class="font_gray4"><b><span class="font_orange7"><?=$totproduct?></span></b>개 등록</td>
																										<td></td>
																									</tr>
																									<tr>
																										<td class="font_gray4">입점기능</td>
																										<td class="font_gray4">:</td>
																										<td class="font_gray4"><?=$vender_used[0]?></td>
																										<td><?=$vender_used[1]?></td>
																									</tr>
																									<tr>
																										<td class="font_gray4">PG 셋팅</td>
																										<td class="font_gray4">:</td>
																										<td class="font_gray4"><?=$pg_used?></td>
																										<td><?=$pg_icon?></td>
																									</tr>
																									<tr>
																										<td class="font_gray4">SMS잔여</td>
																										<td class="font_gray4">:</td>
																										<td class="font_gray4"><?=$sms_count;?></td>
																										<td><?=$sms_icon?></td>
																									</tr>
																									<tr>
																										<td class="font_gray4" colspan="3">호스팅현황</td>
																										<td><a href="http://objet.kr/default/hosting/hosting.php?exeType=hosting_product&serviceHost=64autobahn&left=2" target="_blank"><img src="images/main_icon_view.gif"></td>
																									</tr>
																									<? $sms_host=getSmshost(&$sms_path); ?>
																									<form name=joinform method=post action="http://<?=$sms_host.$sms_path?>/member/member_join.html" target="smsjoin">
																									<input type=hidden name=shopurl value="<?=$shopurl?>">
																									</form>
																								</table>

																							</td>
																						</tr>																																											
																					</table>
																				</TD>
																			</TR>
																		</TABLE>
																	</td>
																</tr>
															</table>
															<!--쇼핑몰 기본정보-->

														</td>
													</tr>
													<tr>
														<td style="padding-top:17px;padding-bottom:17px;padding-left:9px">
															<!--운영서비스 사용현황-->
															 <iframe src="http://www.getmall.co.kr/frames/admin_main_service.php"  WIDTH="177" height="211" frameborder="0" scrolling="no" marginwidth="0" background="#45464c" marginheight="0" name="service"  allowtransparency="true"></iframe>
														</td>
													</tr>
													<tr>
														<td><img src="images/main_left_line01.gif"></td>
													</tr>													

													<tr>
														<td style="padding-left:9px" height="40"><a href="http://www.getmall.co.kr/manual/" target="_blank"><IMG SRC="images/main_left_menual.gif" ALT="쇼핑몰 운영 메뉴얼"></a></td>
													</tr>
													<tr>
														<td><img src="images/main_left_line01.gif"></td>
													</tr>
													<tr>
														<td style="padding-top:17px;padding-bottom:17px;padding-left:9px"><iframe src="http://www.getmall.co.kr/frames/admin_main_tip.php"  WIDTH="177" height="150" frameborder="0" scrolling="no" marginwidth="0" marginheight="0" background="#45464c" name="tiptech"  allowtransparency="true"></iframe> </td>
													</tr>
													<tr>
														<td><img src="images/main_left_line01.gif"></td>
													</tr>
													<tr>
														<td style="padding-left:9px">

															<!--겟몰 고객센터안내-->
															<table border="0" cellspacing="0" cellpadding="0">
																<tr>
																	<td height="20"></td>
																</tr>
																<tr>
																	<td><IMG SRC="images/main_left_customer_t.gif" ALT=""></td>
																</tr>
																<tr>
																	<td height="20"></td>
																</tr>
																<tr>
																	<td>

																		<table cellpadding="0" cellspacing="0" width="100%" border="0">
																			<tr>
																				<td width="18"><img src="images/main_left_icon01.gif"></td>
																				<td class="font_gray" width="150"><a href="http://www.getmall.co.kr/board/board.php?board=manage" target="_blank"><font color="#FF6600">[게시판에 문의하기]</font></td>
																				<td></td>
																			</tr>
																			<tr>
																				<td><img src="images/main_left_icon01.gif"></td>
																				<td class="font_gray"><a href="http://www.getmall.co.kr" target="_blank">홈페이지 바로가기</a></td>
																				<td></td>
																			</tr>
																			<tr>
																				<td><img src="images/main_left_icon01.gif"></td>
																				<td class="font_gray"><a href="http://www.facebook.com/getmalldream" target="_blank">페이스북</a> / <a href="http://twitter.com/getmalldream" target="_blank">트위터</a></td>
																				<td></td>
																			</tr>
																			<tr>
																				<td><img src="images/main_left_icon01.gif"></td>
																				<td class="font_gray"><a href="http://blog.naver.com/getmall_pr" target="_blank">블로그</a> / <a href="http://cafe.naver.com/mallpd" target="_blank">카페</a></td>
																				<td></td>
																			</tr>
																		</table>

																	</td>
																</tr>
																<tr>
																	<td height="20"></td>
																</tr>
															</table>
															<!--겟몰 고객센터안내-->
														</td>
													</tr>
													<tr>
														<td><img src="images/main_left_line01.gif"></td>
													</tr>

													<?/*?>
													<tr>
														<td>
															<table cellpadding="0" cellspacing="0" width="100%">
																<tr>
																	<td><IMG SRC="images/main_left_start_title.gif" width="198" HEIGHT=28 ALT=""></td>
																</tr>
																<tr>
																	<td align=center background="images/main_left_start_bg.gif" style="padding-top:8pt; padding-bottom:3pt;"><a href="javascript:void(0)" onclick="shop_process()"><IMG SRC="images/main_left_start_btn1.gif" WIDTH=81 HEIGHT=23 ALT="" border="0"></a><a href="javascript:void(0)" onclick="shop_menual()"><IMG SRC="images/main_left_start_btn2.gif" WIDTH=79 HEIGHT=23 ALT="" hspace="3" border="0"></a></td>
																</tr>
																<tr>
																	<td><IMG SRC="images/main_left_start_downimg.gif" width="198" HEIGHT=5 ALT=""></td>
																</tr>
															</table>
														</td>
													</tr>
													<tr>
														<td height=12></td>
													</tr>
													<?*/?>
												</table>
<!--######################## 왼쪽 끝 ################################################################################################-->
											</td>
											<td  valign="top"></td>
											<td width="6" valign="top"><img src="images/space01.gif" width="6" height="1" border="0"></td>
											<td valign="top">
<!--######################## 본문 시작 ################################################################################################-->
												<table cellpadding="0" cellspacing="0" width="100%" border="0">
													<tr>
														<td colspan=3 style="padding-top:20px;">
															<!--주문/배송처리 현황-->
															<table cellpadding="0" cellspacing="0" width="100%"  border="0">
																<tr>
																	<td width="16" background="images/main_state_top_left.jpg" height="34" valign="top"></td>
																	<td align="left" valign="top" rowspan="3"  bgcolor="#FBFBFB">
																		<?

																			$curdate_1 = date("Ymd",mktime(0,0,0,date("m"),date("d")-1,date("Y"))); // 하루전
																			$curdate_2 = date("Ymd",mktime(0,0,0,date("m"),date("d")-2,date("Y"))); // 이틀전
																			$curdate_3 = date("Ymd",mktime(0,0,0,date("m"),date("d")-3,date("Y")));
																			$curdate_4 = date("Ymd",mktime(0,0,0,date("m"),date("d")-4,date("Y")));
																			$curdate_7 = date("Ymd",mktime(0,0,0,date("m"),date("d")-7,date("Y")));

																			/* 회원가입 통계 */
																			$sql = "SELECT ";
																			$sql.= "COUNT(IF(date LIKE '".$curdate."%',1,NULL)) as totmemcnt, ";
																			$sql.= "COUNT(IF(date LIKE '".$curdate_1."%',1,NULL)) as totmemcnt1, ";
																			$sql.= "COUNT(IF(date LIKE '".$curdate_2."%',1,NULL)) as totmemcnt2, ";
																			$sql.= "COUNT(IF(date LIKE '".$curdate_3."%',1,NULL)) as totmemcnt3, ";
																			$sql.= "COUNT(IF(date LIKE '".$curdate_4."%',1,NULL)) as totmemcnt4, ";
																			$sql.= "COUNT(IF(substring(date,1,8) between  '".$curdate_7."' and '".substr($curdate,0,8)."',1,NULL)) as totmemcnt7, ";
																			$sql.= "COUNT(IF(date LIKE '".substr($curdate,0,6)."%',1,NULL)) as totmonmemcnt, ";
																			$sql.= "COUNT(IF(date LIKE CONCAT(REPLACE(left(date_sub(curdate(),interval 1 month), 7),'-',''), '%'),1,NULL)) as totremonmemcnt ";
																			$sql.= "FROM tblmember WHERE 1=1 ";

																			$sql .= " and date LIKE '".substr($curdate,0,6)."%' or date like '".date('Ym',strtotime('-1 month'))."%' ";
																			$filename="admin.main.member.cache";

																			get_db_cache($sql, $resval, $filename, 0);
																			$row=$resval[0];
																			unset($resval);

																			$totmemcnt=(int)$row->totmemcnt;		//오늘 회원가입수
																			$totmemcnt1=(int)$row->totmemcnt1;		//1일전 회원가입수
																			$totmemcnt2=(int)$row->totmemcnt2;		//2일전 회원가입수
																			$totmemcnt3=(int)$row->totmemcnt3;		//3일전 회원가입수
																			$totmemcnt4=(int)$row->totmemcnt4;		//3일전 회원가입수
																			$totmemcnt7=(int)$row->totmemcnt7;			//1주일동안 회원가입수
																			$totmonmemcnt=(int)$row->totmonmemcnt;	//이달 회원가입수
																			$totremonmemcnt=(int)$row->totremonmemcnt;	//이전달 회원가입수
																			/* 회원가입 통계  끝*/

																			/* 게시글 통계 */
																			$sql = "SELECT ";
																			$sql.= "COUNT(IF(FROM_UNIXTIME(writetime,'%Y%m%d')='".$curdate."',1,NULL)) as totbrdcnt, ";
																			$sql.= "COUNT(IF(FROM_UNIXTIME(writetime,'%Y%m%d')='".$curdate_1."',1,NULL)) as totbrdcnt1, ";
																			$sql.= "COUNT(IF(FROM_UNIXTIME(writetime,'%Y%m%d')='".$curdate_2."',1,NULL)) as totbrdcnt2, ";
																			$sql.= "COUNT(IF(FROM_UNIXTIME(writetime,'%Y%m%d')='".$curdate_3."',1,NULL)) as totbrdcnt3, ";
																			$sql.= "COUNT(IF(FROM_UNIXTIME(writetime,'%Y%m%d')='".$curdate_4."',1,NULL)) as totbrdcnt4, ";
																			$sql.= "COUNT(IF(FROM_UNIXTIME(writetime,'%Y%m%d') between  '".$curdate_7."' and '".substr($curdate,0,8)."',1,NULL)) as totbrdcnt7, ";
																			$sql.= "COUNT(IF(FROM_UNIXTIME(writetime,'%Y%m')='".substr($curdate,0,6)."',1,NULL)) as totmonbrdcnt, ";
																			$sql.= "COUNT(IF(FROM_UNIXTIME(writetime,'%Y%m') like CONCAT(REPLACE(left(date_sub(curdate(),interval 1 month), 7),'-',''), '%'),1,NULL)) as totremonbrdcnt ";
																			$sql.= "FROM tblboard WHERE 1=1 ";

																			$sql .= " and FROM_UNIXTIME(writetime,'%Y%m')='".substr($curdate,0,6)."' or FROM_UNIXTIME(writetime,'%Y%m')='".date('Ym',strtotime('-1 month'))."' ";
																			$filename="admin.main.board.cache";

																			get_db_cache($sql, $resval, $filename, 0);
																			$row=$resval[0];
																			unset($resval);

																			$totbrdcnt=(int)$row->totbrdcnt;		//오늘 등록된 게시물수
																			$totbrdcnt1=(int)$row->totbrdcnt1;		//1일전 등록된 게시물수
																			$totbrdcnt2=(int)$row->totbrdcnt2;		//2일전 등록된 게시물수
																			$totbrdcnt3=(int)$row->totbrdcnt3;		//3일전 등록된 게시물수
																			$totbrdcnt7=(int)$row->totbrdcnt7;		//일주일동안 등록된 게시물수
																			$totmonbrdcnt=(int)$row->totmonbrdcnt;	//이달 등록된 게시물수
																			$totremonbrdcnt=(int)$row->totremonbrdcnt;	//이전달 등록된 게시물수
																			/* 게시글 통계 끝 */
																			
																			/* 방문자 통계 */
																			$sql = "SELECT ";
																			$sql.= "SUM(IF(date LIKE '".$curdate."%',cnt,NULL)) as totvstcnt, ";
																			$sql.= "SUM(IF(date LIKE '".$curdate_1."%',cnt,NULL)) as totvstcnt1, ";
																			$sql.= "SUM(IF(date LIKE '".$curdate_2."%',cnt,NULL)) as totvstcnt2, ";
																			$sql.= "SUM(IF(date LIKE '".$curdate_3."%',cnt,NULL)) as totvstcnt3, ";
																			$sql.= "SUM(IF(date LIKE '".$curdate_4."%',cnt,NULL)) as totvstcnt4, ";
																			$sql.= "SUM(IF(substring(date,1,8) between  '".$curdate_7."' and '".substr($curdate,0,8)."',cnt,NULL)) as totvstcnt7, ";
																			$sql.= "SUM(IF(date LIKE '".substr($curdate,0,6)."%',cnt,NULL)) as totmonvstcnt, ";
																			$sql.= "SUM(IF(date like '".date('Ym',strtotime('-1 month'))."%',cnt,NULL)) as totremonvstcnt ";
																			$sql.= "FROM tblcounter WHERE 1=1 ";
																			$sql .= " AND date LIKE '".substr($curdate,0,6)."%' or date like '".date('Ym',strtotime('-1 month'))."%'";
																			$filename="admin.main.count.cache";
																			get_db_cache($sql, $resval, $filename, 0);
																			$row=$resval[0];
																			unset($resval);

																			$totvstcnt=(int)$row->totvstcnt;		//오늘 방문자수
																			$totvstcnt1=(int)$row->totvstcnt1;		//1일전 방문자수
																			$totvstcnt2=(int)$row->totvstcnt2;		//2일전 방문자수
																			$totvstcnt3=(int)$row->totvstcnt3;		//3일전 방문자수
																			$totvstcnt7=(int)$row->totvstcnt7;		//일주일동안 방문자수
																			$totmonvstcnt=(int)$row->totmonvstcnt;	//이달 방문자수
																			$totremonvstcnt=(int)$row->totremonvstcnt;	//이전달 방문자수
																			/* 방문자 통계 끝 */

																			/* 주문 통계 */
																			$sql = "SELECT ";
																			//오늘 주문건수 및 주문금액
																			$sql.= "COUNT(IF(ordercode LIKE '".$curdate."%',1,NULL)) as totordcnt, ";
																			$sql.= "SUM(IF((ordercode LIKE '".$curdate."%') && (deli_gbn IN('Y')),price,0)) as totordprice, ";
																			//오늘 미배송 건수 및 미배송건 금액
																			$sql.= "COUNT(IF((ordercode LIKE '".$curdate."%') && (deli_gbn IN('N','X')),1,NULL)) as totdelaycnt, ";
																			$sql.= "SUM(IF((ordercode LIKE '".$curdate."%') && (deli_gbn IN('N','X')),price,0)) as totdelayprice, ";
																			//오늘 환불/취소 건수
																			$sql.= "COUNT(IF((ordercode LIKE '".$curdate."%') && (deli_gbn IN('C','D')),1,NULL)) as totcancelcnt, ";

																			//1일전 주문건수 및 주문금액
																			$sql.= "COUNT(IF(ordercode LIKE '".$curdate_1."%',1,NULL)) as totordcnt1, ";
																			$sql.= "SUM(IF((ordercode LIKE '".$curdate_1."%') && (deli_gbn IN('Y')),price,0)) as totordprice1, ";
																			//1일전 미배송 건수 및 미배송건 금액
																			$sql.= "COUNT(IF((ordercode LIKE '".$curdate_1."%') && (deli_gbn IN('N','X')),1,NULL)) as totdelaycnt1, ";
																			$sql.= "SUM(IF((ordercode LIKE '".$curdate_1."%') && (deli_gbn IN('N','X')),price,0)) as totdelayprice1, ";
																			//1일전 환불/취소 건수
																			$sql.= "COUNT(IF((ordercode LIKE '".$curdate_1."%') && (deli_gbn IN('C','D')),1,NULL)) as totcancelcnt1, ";

																			//2일전 주문건수 및 주문금액
																			$sql.= "COUNT(IF(ordercode LIKE '".$curdate_2."%',1,NULL)) as totordcnt2, ";
																			$sql.= "SUM(IF((ordercode LIKE '".$curdate_2."%') && (deli_gbn IN('Y')),price,0)) as totordprice2, ";
																			//2일전 미배송 건수 및 미배송건 금액
																			$sql.= "COUNT(IF((ordercode LIKE '".$curdate_2."%') && (deli_gbn IN('N','X')),1,NULL)) as totdelaycnt2, ";
																			$sql.= "SUM(IF((ordercode LIKE '".$curdate_2."%') && (deli_gbn IN('N','X')),price,0)) as totdelayprice2, ";
																			//2일전 환불/취소 건수
																			$sql.= "COUNT(IF((ordercode LIKE '".$curdate_2."%') && (deli_gbn IN('C','D')),1,NULL)) as totcancelcnt2, ";

																			//3일전 주문건수 및 주문금액
																			$sql.= "COUNT(IF(ordercode LIKE '".$curdate_3."%',1,NULL)) as totordcnt3, ";
																			$sql.= "SUM(IF((ordercode LIKE '".$curdate_3."%') && (deli_gbn IN('Y')),price,0)) as totordprice3, ";
																			//3일전 미배송 건수 및 미배송건 금액
																			$sql.= "COUNT(IF((ordercode LIKE '".$curdate_3."%') && (deli_gbn IN('N','X')),1,NULL)) as totdelaycnt3, ";
																			$sql.= "SUM(IF((ordercode LIKE '".$curdate_3."%') && (deli_gbn IN('N','X')),1,0)) as totdelayprice3, ";

																			//4일전 주문건수 및 주문금액
																			$sql.= "COUNT(IF(ordercode LIKE '".$curdate_4."%',1,NULL)) as totordcnt4, ";
																			$sql.= "SUM(IF((ordercode LIKE '".$curdate_4."%') && (deli_gbn IN('Y')),price,0)) as totordprice4, ";
																			//4일전 미배송 건수 및 미배송건 금액
																			$sql.= "COUNT(IF((ordercode LIKE '".$curdate_4."%') && (deli_gbn IN('N','X')),1,NULL)) as totdelaycnt4, ";
																			$sql.= "SUM(IF((ordercode LIKE '".$curdate_4."%') && (deli_gbn IN('N','X')),1,0)) as totdelayprice4, ";

																			//1주일동안 주문건수 및 매출
																			$sql.= "COUNT(IF(substring(ordercode,1,8) between  '".$curdate_7."' and '".substr($curdate,0,8)."',1,NULL)) as totordcnt7, ";
																			$sql.= "SUM(IF((substring(ordercode,1,8) between  '".$curdate_7."' and '".substr($curdate,0,8)."' AND deli_gbn IN('Y')),price,0)) as totordprice7, ";
																			//1주일동안 미배송 건수 및 미배송건 금액
																			$sql.= "COUNT(IF((substring(ordercode,1,8) between '".$curdate_7."' and '".substr($curdate,0,8)."') && (deli_gbn IN('N','X')),1,NULL)) as totdelaycnt7, ";
																			$sql.= "SUM(IF((substring(ordercode,1,8) between '".$curdate_7."' and '".substr($curdate,0,8)."') && (deli_gbn IN('N','X')),price,0)) as totdelayprice7, ";
																			//1주일동안 환불/취소 건수
																			$sql.= "COUNT(IF((substring(ordercode,1,8) between '".$curdate_7."' and '".substr($curdate,0,8)."') && (deli_gbn IN('C','D')),1,NULL)) as totdelayprice7, ";

																			//이전달 주문건수 및 주문금액
																			$sql.= "COUNT(IF(ordercode LIKE CONCAT(REPLACE(left(date_sub(curdate(),interval 1 month), 7),'-',''), '%'),1,NULL)) as totremonordcnt, ";
																			$sql.= "SUM(IF((ordercode LIKE CONCAT(REPLACE(left(date_sub(curdate(),interval 1 month), 7),'-',''), '%') AND deli_gbn IN('Y')),price,0)) as totremonordprice, ";
																			//이전달 미배송 건수 및 미배송건 금액
																			$sql.= "COUNT(IF((ordercode LIKE CONCAT(REPLACE(left(date_sub(curdate(),interval 1 month), 7),'-',''), '%')) && (deli_gbn IN('N','X')),1,NULL)) as totremondelaycnt, ";
																			$sql.= "SUM(IF((ordercode LIKE CONCAT(REPLACE(left(date_sub(curdate(),interval 1 month), 7),'-',''), '%')) && (deli_gbn IN('N','X')),1,0)) as totremondelayprice, ";
																			//이전달 환불/취소 건수
																			$sql.= "COUNT(IF((ordercode LIKE CONCAT(REPLACE(left(date_sub(curdate(),interval 1 month), 7),'-',''), '%')) && (deli_gbn IN('C','D')),1,NULL)) as totrecancelmoncnt, ";

																			//이달 주문건수 및 매출
																			$sql.= "COUNT(IF(ordercode LIKE '".substr($curdate,0,6)."%',1,NULL)) as totmonordcnt, ";
																			$sql.= "SUM(IF((ordercode LIKE '".substr($curdate,0,6)."%' AND deli_gbn IN('Y')),price,0)) as totmonordprice, ";
																			//이달 미배송 건수 및 미배송건 금액
																			$sql.= "COUNT(IF((ordercode LIKE '".substr($curdate,0,6)."%') && (deli_gbn IN('N','X')),1,NULL)) as totmondelaycnt, ";
																			$sql.= "SUM(IF((ordercode LIKE '".substr($curdate,0,6)."%') && (deli_gbn IN('N','X')),price,0)) as totmondelayprice, ";
																			//이달 환불/취소 건수
																			$sql.= "COUNT(IF((ordercode LIKE '".substr($curdate,0,6)."%') && (deli_gbn IN('C','D')),1,NULL)) as totcancelmoncnt ";
																			$sql.= "FROM tblorderinfo WHERE 1=1 ";
																			$sql.=" and ordercode LIKE '".substr($curdate,0,6)."%' or ordercode like '".date('Ym',strtotime('-1 month'))."%' ";

																			$filename="admin.main.order.cache";
																			
																			get_db_cache($sql, $resval, $filename, 0);

																			$row=$resval[0];
																			unset($resval);
																		
																			$totordcnt=(int)$row->totordcnt;			//오늘 주문건수
																			$totordprice=(int)$row->totordprice;		//오늘 주문금액
																			$totdelaycnt=(int)$row->totdelaycnt;		//오늘 미배송건수
																			$totdelayprice=(int)$row->totdelayprice;	//오늘 미배송금액
																			$totcancelcnt=(int)$row->totcancelcnt;	//오늘 환불/취소건수

																			$totordcnt1=(int)$row->totordcnt1;			//1일전 주문건수
																			$totordprice1=(int)$row->totordprice1;		//1일전 주문금액
																			$totdelaycnt1=(int)$row->totdelaycnt1;		//1일전 미배송건수
																			$totdelayprice1=(int)$row->totdelayprice1;	//1일전 미배송금액
																			$totcancelcnt1=(int)$row->totcancelcnt1;	//1일전 환불/취소건수

																			$totordcnt2=(int)$row->totordcnt2;			//2일전 주문건수
																			$totordprice2=(int)$row->totordprice2;		//2일전 주문금액
																			$totdelaycnt2=(int)$row->totdelaycnt2;		//2일전 미배송건수
																			$totdelayprice2=(int)$row->totdelayprice2;	//2일전 미배송금액
																			$totcancelcnt2=(int)$row->totcancelcnt2;	//2일전 환불/취소건수

																			$totordcnt3=(int)$row->totordcnt3;			//3일전 주문건수
																			$totordprice3=(int)$row->totordprice3;		//3일전 주문금액
																			$totdelaycnt3=(int)$row->totdelaycnt3;		//3일전 미배송건수
																			$totdelayprice3=(int)$row->totdelayprice3;	//3일전 미배송금액

																			$totordcnt4=(int)$row->totordcnt4;			//4일전 주문건수
																			$totordprice4=(int)$row->totordprice4;		//4일전 주문금액
																			$totdelaycnt4=(int)$row->totdelaycnt4;		//4일전 미배송건수
																			$totdelayprice4=(int)$row->totdelayprice4;	//4일전 미배송금액

																			$totordcnt7=(int)$row->totordcnt7;			//1주일동안 주문건수
																			$totordprice7=(int)$row->totordprice7;		//1주일동안 주문금액
																			$totdelaycnt7=(int)$row->totdelaycnt7;		//1주일동안 미배송건수
																			$totdelayprice7=(int)$row->totdelayprice7;	//1주일동안 미배송금액
																			$totcancelcnt7=(int)$row->totcancelcnt7;	//오늘 미배송금액

																			$totmonordcnt=(int)$row->totmonordcnt;		//이달의 주문건수
																			$totmonordprice=(int)$row->totmonordprice;	//이달의 매출
																			$totmondelaycnt=(int)$row->totmondelaycnt;	//이달 미배송건수
																			$totmondelayprice=(int)$row->totmondelayprice;//이달 미배송금액
																			$totcancelmoncnt=(int)$row->totcancelmoncnt;	//오늘 환불/취소건수

																			$totremonordcnt=(int)$row->totremonordcnt;		//이전달의 주문건수
																			$totremonordprice=(int)$row->totremonordprice;	//이전달의 매출
																			$totremondelaycnt=(int)$row->totremondelaycnt;	//이전달 미배송건수
																			$totremondelayprice=(int)$row->totremondelayprice;//이전달 미배송금액
																			$totrecancelmoncnt=(int)$row->totrecancelmoncnt;	//오늘 환불/취소건수
																			/* 주문 통계 끝 */
																		?>
																		<!--주문처리현황 시작-->
																		<table cellpadding="0" cellspacing="0" width="100%" border="0">
																			<col width=98></col>
																			<col width=3></col>
																			<col width=97></col>
																			<col width=3></col>
																			<col width=97></col>
																			<col width=3></col>
																			<col width=97></col>
																			<col width=3></col>
																			<col width=101></col>
																			<col width=3></col>
																			<col width=101></col>
																			<col width=3></col>
																			<col width=101></col>

																			<tr>
																				<td background="images/main_state_topbg.jpg" align="left" height="34" class="font_gray5">구분</td>
																				<td background="images/main_state_topbg.jpg" align="center"></td>
																				<td background="images/main_state_topbg.jpg" align="center"class="font_gray5"><?=substr($curdate,4,2)."월".substr($curdate,6,2)."일"?><img src="images/icon_today.gif" border="0" hspace="3"></td>
																				<td background="images/main_state_topbg.jpg" align="center"></td>
																				<td  background="images/main_state_topbg.jpg" align="center"class="font_gray5"><?=substr($curdate_1,4,2)."월".substr($curdate_1,6,2)."일"?></td>
																				<td background="images/main_state_topbg.jpg"></td>
																				<td  background="images/main_state_topbg.jpg" align="center"class="font_gray5"><?=substr($curdate_2,4,2)."월".substr($curdate_2,6,2)."일"?></td>
																				<td background="images/main_state_topbg.jpg"></td>
																				<td  background="images/main_state_topbg.jpg" align="center"class="font_gray5">최근1주</td>
																				<td background="images/main_state_topbg.jpg"></td>
																				<td  background="images/main_state_topbg.jpg" align="center"class="font_gray5">이번달</td>
																				<td background="images/main_state_topbg.jpg"></td>
																				<td  background="images/main_state_topbg.jpg" align="center"class="font_gray5">지난달</td>
																			</tr>
																			<tr>
																				<td height="5" colspan="13" background="images/main_state_line.gif"></td>
																			</tr>
																			<tr>
																				<td height="20" align="left" class="font_gray3">매출(원)</td>
																				<td ></td>
																				<td height="20"  align="center" class="font_gray3a"><span class="font_orange8"><?=number_format($totordprice);?></span></td>
																				<td height="20" ></td>
																				<td height="20"  align="center" class="font_gray3a"><?=number_format($totordprice1);?></td>
																				<td height="20" ></td>
																				<td height="20"  align="center" class="font_gray3a"><?=number_format($totordprice2);?></td>
																				<td height="20" ></td>
																				<td height="20"  align="center" class="font_gray3a"><?=number_format($totordprice7);?></td>
																				<td height="20" ></td>
																				<td height="20"  align="center" class="font_gray3a"><?=number_format($totmonordprice);?></td>
																				<td height="20" ></td>
																				<td height="20"  align="center" class="font_gray3a"><?=number_format($totremonordprice);?></td>
																			</tr>
																			<tr>
																				<td height="3" colspan="13"></td>
																			</tr>
																			<tr>
																				<td height="1" colspan="13" bgcolor="#ECEDF0"></td>
																			</tr>
																			<tr>
																				<td height="3" colspan="13"></td>
																			</tr>
																			<tr>
																				<td height="20" align="left" class="font_gray3">주문(건)</td>
																				<td ></td>
																				<td height="20"  align="center" class="font_gray3a"><span class="font_orange8"><?=number_format($totordcnt);?></span></td>
																				<td height="20" ></td>
																				<td height="20"  align="center" class="font_gray3a"><?=number_format($totordcnt1);?></td>
																				<td height="20" ></td>
																				<td height="20"  align="center" class="font_gray3a"><?=number_format($totordcnt2);?></td>
																				<td height="20" ></td>
																				<td height="20"  align="center" class="font_gray3a"><?=number_format($totordcnt7);?></td>
																				<td height="20" ></td>
																				<td height="20"  align="center" class="font_gray3a"><?=number_format($totmonordcnt);?></td>
																				<td height="20" ></td>
																				<td height="20"  align="center" class="font_gray3a"><?=number_format($totremonordcnt);?></td>
																			</tr>
																			<tr>
																				<td height="3" colspan="13"></td>
																			</tr>
																			<tr>
																				<td height="1" colspan="13" bgcolor="#ECEDF0"></td>
																			</tr>
																			<tr>
																				<td height="3" colspan="13"></td>
																			</tr>
																			<tr>
																				<td height="20" align="left" class="font_gray3">미배송(건)</td>
																				<td ></td>
																				<td height="20"  align="center" class="font_gray3a"><span class="font_orange8"><?=number_format($totdelaycnt);?></span></td>
																				<td height="20" ></td>
																				<td height="20"  align="center" class="font_gray3a"><?=number_format($totdelaycnt1);?></td>
																				<td height="20" ></td>
																				<td height="20"  align="center" class="font_gray3a"><?=number_format($totdelaycnt2);?></td>
																				<td height="20" ></td>
																				<td height="20"  align="center" class="font_gray3a"><?=number_format($totdelaycnt7);?></td>
																				<td height="20" ></td>
																				<td height="20"  align="center" class="font_gray3a"><?=number_format($totmondelaycnt);?></td>
																				<td height="20" ></td>
																				<td height="20"  align="center" class="font_gray3a"><?=number_format($totremondelaycnt);?></td>
																			</tr>
																			<tr>
																				<td height="3" colspan="13"></td>
																			</tr>
																			<tr>
																				<td height="1" colspan="13" bgcolor="#ECEDF0"></td>
																			</tr>
																			<tr>
																				<td height="3" colspan="13"></td>
																			</tr>
																			<tr>
																				<td height="20" align="left" class="font_gray3">환불/취소(건)</td>
																				<td ></td>
																				<td height="20"  align="center" class="font_gray3a"><span class="font_orange8"><?=number_format($totcancelcnt);?></span></td>
																				<td height="20" ></td>
																				<td height="20"  align="center" class="font_gray3a"><?=number_format($totcancelcnt1);?></td>
																				<td height="20" ></td>
																				<td height="20"  align="center" class="font_gray3a"><?=number_format($totcancelcnt2);?></td>
																				<td height="20" ></td>
																				<td height="20"  align="center" class="font_gray3a"><?=number_format($totcancelcnt7);?></td>
																				<td height="20" ></td>
																				<td height="20"  align="center" class="font_gray3a"><?=number_format($totcancelmoncnt);?></td>
																				<td height="20" ></td>
																				<td height="20"  align="center" class="font_gray3a"><?=number_format($totrecancelmoncnt);?></td>
																			</tr>
																			<tr>
																				<td height="3" colspan="13"></td>
																			</tr>
																			<tr>
																				<td height="1" colspan="13" bgcolor="#ECEDF0"></td>
																			</tr>
																			<tr>
																				<td height="3" colspan="13"></td>
																			</tr>
																			<tr>
																				<td height="20" align="left" class="font_gray3">게시글(건)</td>
																				<td ></td>
																				<td height="20"  align="center" class="font_gray3a"><span class="font_orange8"><?=number_format($totbrdcnt);?></span></td>
																				<td height="20" ></td>
																				<td height="20"  align="center" class="font_gray3a"><?=number_format($totbrdcnt1);?></td>
																				<td height="20" ></td>
																				<td height="20"  align="center" class="font_gray3a"><?=number_format($totbrdcnt2);?></td>
																				<td height="20" ></td>
																				<td height="20"  align="center" class="font_gray3a"><?=number_format($totbrdcnt7);?></td>
																				<td height="20" ></td>
																				<td height="20"  align="center" class="font_gray3a"><?=number_format($totmonbrdcnt);?></td>
																				<td height="20" ></td>
																				<td height="20"  align="center" class="font_gray3a"><?=number_format($totremonbrdcnt);?></td>
																			</tr>
																			<tr>
																				<td height="3" colspan="13"></td>
																			</tr>
																			<tr>
																				<td height="1" colspan="13" bgcolor="#ECEDF0"></td>
																			</tr>
																			<tr>
																				<td height="3" colspan="13"></td>
																			</tr>
																			<tr>
																				<td height="20" align="left" class="font_gray3">회원가입(명)</td>
																				<td ></td>
																				<td height="20"  align="center" class="font_gray3a"><span class="font_orange8"><?=number_format($totmemcnt);?></span></td>
																				<td height="20" ></td>
																				<td height="20"  align="center" class="font_gray3a"><?=number_format($totmemcnt1);?></td>
																				<td height="20" ></td>
																				<td height="20"  align="center" class="font_gray3a"><?=number_format($totmemcnt2);?></td>
																				<td height="20" ></td>
																				<td height="20"  align="center" class="font_gray3a"><?=number_format($totmemcnt7);?></td>
																				<td height="20" ></td>
																				<td height="20"  align="center" class="font_gray3a"><?=number_format($totmonmemcnt);?></td>
																				<td height="20" ></td>
																				<td height="20"  align="center" class="font_gray3a"><?=number_format($totremonmemcnt);?></td>
																			</tr>
																			<tr>
																				<td height="3" colspan="13"></td>
																			</tr>
																			<tr>
																				<td height="1" colspan="13" bgcolor="#ECEDF0"></td>
																			</tr>
																			<tr>
																				<td height="3" colspan="13"></td>
																			</tr>
																			<tr>
																				<td height="20" align="left" class="font_gray3">방문자(명)</td>
																				<td ></td>
																				<td height="20"  align="center" class="font_gray3a"><span class="font_orange8"><?=number_format($totvstcnt);?></span></td>
																				<td height="20" ></td>
																				<td height="20"  align="center" class="font_gray3a"><?=number_format($totvstcnt1);?></td>
																				<td height="20" ></td>
																				<td height="20"  align="center" class="font_gray3a"><?=number_format($totvstcnt2);?></td>
																				<td height="20" ></td>
																				<td height="20"  align="center" class="font_gray3a"><?=number_format($totvstcnt7);?></td>
																				<td height="20" ></td>
																				<td height="20"  align="center" class="font_gray3a"><?=number_format($totmonvstcnt);?></td>
																				<td height="20" ></td>
																				<td height="20"  align="center" class="font_gray3a"><?=number_format($totremonvstcnt);?></td>
																			</tr>
																			<tr><td height="5" colspan="13"></td></tr>
																		</table>
																		<!--주문처리현황 끝-->
																	</td>
																	<td width="10" background="images/main_state_topbg.jpg" align="center" height="34" valign="top" style="padding-top:2px"><IMG SRC="images/main_state_top_line.jpg" ALT=""></td>
																	<td width="200" background="images/main_state_topbg.jpg" height="34" class="font_gray5"><IMG SRC="images/main_icon_memo.gif" WIDTH=16 HEIGHT=16 ALT="" align="absmiddle" hspace="4" >최근 배송처리 현황</span></font></b><A HREF="javascript:parent.topframe.GoMenu(5,'order_list.php');"><img src="images/main_icon_more.gif" border="0" align="absmiddle" hspace=3></a></td>
																	<td width="7" background="images/main_state_top_right.jpg" align="right" height="34" valign="top"></td>
																</tr>
																<tr>
																	<td background="images/main_state_bottom_leftbg1.jpg"></td>
																	<td bgcolor="#FBFBFB"></td>
																	<td bgcolor="#FBFBFB"></td>
																	<td background="images/main_state_top_rightbg.jpg"></td>
																</tr>
																<tr>
																	<td width="10" background="images/main_state_bottom_leftbg1.jpg"></td>
																	<td width="10"  align="center" bgcolor="#FBFBFB"></td>
																	<td height="180" align="center" bgcolor="#FBFBFB" valign="top">
																		<!--최근 배송 처리현황 시작-->
																		<table cellpadding="0" cellspacing="0" width="100%" border="0" style="margin-top:9px;">
																			<?
																			$sql = "SELECT * FROM tblorderinfo ";
																			$sql.= "ORDER BY ordercode DESC LIMIT 7 ";
																			$result=mysql_query($sql,get_db_conn());
																			$arpm=array(
																				"B"=>"<img src=\"images/icon_mu.gif\" style=\"position:relative; top:0.2em;\" alt=\"\" />", //무통장
																				"V"=>"<img src=\"images/icon_sil.gif\" style=\"position:relative; top:0.2em;\" alt=\"\" />", //실시간계좌이체
																				"O"=>"<img src=\"images/icon_ga.gif\" style=\"position:relative; top:0.2em;\" alt=\"\" />", //가상계좌
																				"Q"=>"<img src=\"images/icon_mae.gif\" style=\"position:relative; top:0.2em;\" alt=\"\" />", //매매보호(에스크로)
																				"C"=>"<img src=\"images/icon_ca.gif\" style=\"position:relative; top:0.2em;\" alt=\"\" />", //신용카드
																				"P"=>"<img src=\"images/icon_mae.gif\" style=\"position:relative; top:0.2em;\" alt=\"\" />", //매매보호(에스크로)
																				"M"=>"<img src=\"images/icon_han.gif\" style=\"position:relative; top:0.2em;\" alt=\"\" />" //핸드폰
																			);

																			$i=0;
																			while($row = mysql_fetch_object($result)) {
																				$name=$row->sender_name;
																				$date = substr($row->ordercode,4,2).substr($row->ordercode,6,2).substr($row->ordercode,8,2).substr($row->ordercode,10,2);

																				switch($row->deli_gbn) {
																					case 'S': $de_gbn = "<font class=font_blue3>준비</font>";  break;
																					case 'X': $de_gbn = "<font class=font_gray6>요청</font>";  break;
																					case 'Y': $de_gbn = "배송";  break;
																					case 'D': $de_gbn = "<font color=font_blue3>취소</font>";  break;
																					case 'N': $de_gbn = "<font class=font_blue3>미처리</font>";  break;
																					case 'E': $de_gbn = "<font color=red>환불대기</font>";  break;
																					case 'C': $de_gbn = "<font color=red>주문취소</font>";  break;
																					case 'R': $de_gbn = "반송";  break;
																					case 'H': $de_gbn = "배송(<font color=red>정산보류</font>)";  break;
																				}
																			?>
																			<tr>
																				<td class="font_blue3a"><A HREF="javascript:OrderDetailView('<?=$row->ordercode?>');">[<?=$name?>]</td>
																				<td class="font_blue3"><?=$de_gbn?></td>
																				<td align="right" class="font_blue3a"><?=number_format($row->price)?>원 <?=$arpm[substr($row->paymethod,0,1)]?></td>
																			</tr>
																			<?
																			$i++;
																			}
																			mysql_free_result($result);
																			if($i==0) {
																				echo "<tr><td align=center >등록된 데이터가 없습니다.</td></tr>";
																			}
																			?>
																			<tr>
																				<td class="font_blue3a" colspan="3" align="right" valign="bottom"></td>
																			</tr>
																		</table>
																	</td>
																	<td width="16" background="images/main_state_top_rightbg.jpg"></td>
																</tr>
																<tr>
																	<td height="30" colspan="5" align="right" style="padding-right:15px" class="font_gray8">
																		<img src="images/icon_mu.gif" style="position:relative; top:0.2em;" alt="" /> 무통장,
																		<img src="images/icon_sil.gif" style="position:relative; top:0.2em;" alt="" /> 실시간계좌이체,
																		<img src="images/icon_ca.gif" style="position:relative; top:0.2em;" alt="" /> 신용카드,
																		<img src="images/icon_ga.gif" style="position:relative; top:0.2em;" alt="" /> 가상계좌,
																		<img src="images/icon_mae.gif" style="position:relative; top:0.2em;" alt="" /> 매매보호(에스크로),
																		<img src="images/icon_han.gif" style="position:relative; top:0.2em;" alt="" /> 핸드폰결제
																	</td>
																</tr>
																<tr>
																	<td height="10" colspan="5"></td>
																</tr>
															</table>
														</td>
													</tr>
													<tr>
														<td valign="top" width="100%">
<!--#######본문 left시작#######################################################################################################################--->
															<table cellpadding="0" cellspacing="0" width="100%" border="0">
																	<tr>
																		<td  align="left">
																		<!--1:1,문의,후기 게시판 시작-->
																		<table cellpadding="0" cellspacing="0" width="100%" border="0">
																			<tr>
																				<td width="12" background="images/main_title_bg.jpg"><IMG SRC="images/main_title_left.jpg" WIDTH=12 HEIGHT=38 ALT=""></td>
																				<td background="images/main_title_bg.jpg"><IMG SRC="images/main_center_quick_11board_t.gif" WIDTH=80 HEIGHT=24 ALT=""></td>
																				<td width="105" background="images/main_title_bg.jpg"><A href="community_personal.php"><IMG SRC="images/main_icon_more.gif" WIDTH=30 HEIGHT=13 ALT="" border="0"></A></td>
																				<td background="images/main_title_bg.jpg"><IMG SRC="images/main_center_quick_ct_title.gif" WIDTH=80 HEIGHT=24 ALT=""></td>
																				<td width="105" background="images/main_title_bg.jpg"><A href="community_article.php"><IMG SRC="images/main_icon_more.gif" WIDTH=30 HEIGHT=13 ALT="" border="0"></A></td>
																				<td background="images/main_title_bg.jpg"><IMG SRC="images/main_center_quick_qro_title.gif" WIDTH=79 HEIGHT=24 ALT=""></td>
																				<td width="105" background="images/main_title_bg.jpg"><A href="product_review.php"><IMG SRC="images/main_icon_more.gif" WIDTH=30 HEIGHT=13 ALT="" border="0"></A></td>
																				<td width="7" background="images/main_title_bg.jpg"><IMG SRC="images/main_title_right.jpg" ALT=""></td>
																			</tr>
																			<tr>
																				<td height="20" colspan="8"></td>
																			</tr>
																			<tr>
																				<td></td>
																				<td colspan="2" class="font_gray3" valign="top">
																				<form name=perform action="community_personal_pop.php" method=post target="personal_pop">
																				<input type=hidden name=idx>
																				</form>
																				<?
																					$sql = "SELECT idx, subject FROM tblpersonal ";
																					$sql.= "ORDER BY idx DESC LIMIT 5 ";
																					$result=mysql_query($sql,get_db_conn());
																					$i=0;
																					while($row=mysql_fetch_object($result)) {
																						echo "<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\"><tr>\n";
																						echo "	<td width=8><img src=\"images/main_center_point.gif\" border=0></td>\n";
																						echo "	<td><A HREF=\"javascript:ViewPersonal('".$row->idx."');\">".titleCut(33,strip_tags($row->subject))."</A></td>\n";
																						echo "</tr></table>\n";
																						$i++;
																					}
																					mysql_free_result($result);
																					if($i==0) {
																						echo "등록된 데이터가 없습니다.";
																					}
																				?>

																				</td>
																				<td  colspan="2" class="font_gray3" valign="top">

																				<?
																					$sql = "SELECT num,title FROM tblboard ";
																					$sql.= "WHERE pos=0 AND notice='0' ";
																					$sql.= "ORDER BY thread ASC LIMIT 5 ";
																					$result=mysql_query($sql,get_db_conn());
																					$i=0;
																					while($row=mysql_fetch_object($result)) {
																						echo "<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\"><tr>\n";
																						echo "	<td width=8><img src=\"images/main_center_point.gif\" border=0></td>\n";
																						echo "	<td><A HREF=\"community_article.php?exec=view&num=".$row->num."\">".titleCut(33,strip_tags($row->title))."</A></td>\n";
																						echo "</tr></table>\n";
																						$i++;
																					}
																					mysql_free_result($result);
																					if($i==0) {
																						echo "등록된 데이터가 없습니다.";
																					}
																				?>

																				</td>
																				<td colspan="2" class="font_gray3" valign="top">

																				<form name=reviewform action="product_reviewreply.php" method=post>
																				<input type=hidden name=date>
																				<input type=hidden name=productcode>
																				</form>
																				<?
																					$sql = "SELECT a.productcode,a.date,a.content FROM tblproductreview a, tblproduct b ";
																					$sql.= "WHERE a.productcode = b.productcode ORDER BY a.date DESC LIMIT 5 ";
																					$result=mysql_query($sql,get_db_conn());
																					$i=0;
																					while($row=mysql_fetch_object($result)) {
																						$rowcontent = explode("=",$row->content);

																						echo "<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\"><tr>\n";
																						echo "	<td width=8><img src=\"images/main_center_point.gif\" border=0></td>\n";
																						echo "	<td><A HREF=\"javascript:ReviewReply('".$row->date."','".$row->productcode."')\">".titleCut(33,strip_tags($rowcontent[0]))."</A></td>\n";
																						echo "</tr></table>\n";
																						$i++;
																					}
																					mysql_free_result($result);
																					if($i==0) {
																						echo "등록된 데이터가 없습니다.";
																					}
																				?>


																				</td>
																				<td></td>
																			</tr>
																			<tr>
																				<td height="60" colspan="8"></td>
																			</tr>
																		</table>
																		<!--1:1,문의,후기 게시판 끝-->
																		</td>
																	</tr>
																	<tr>
																		<td height=11></td>
																	</tr>
																	<? /*
																	<tr>
																		<td>
																			<table cellpadding="0" cellspacing="0" width="100%" border="0">
																				<tr>
																					<td width="12" background="images/main_title_bg.jpg"><IMG SRC="images/main_title_left.jpg" WIDTH=12 HEIGHT=38 ALT=""></td>
																					<td width="45%" background="images/main_title_bg.jpg"><IMG SRC="images/main_center_quick_title.gif" WIDTH=92 HEIGHT=24 ALT=""></td>
																					<td width="20" background="images/main_title_bg.jpg"></td>
																					<td width="55%" background="images/main_title_bg.jpg">
																						<table cellpadding="0" cellspacing="0" width="100%">
																							<tr>
																								<td width="65" style="padding-left:5px"><IMG SRC="images/main_center_momo_title.gif" WIDTH=65 HEIGHT=24 ALT=""></td>
																								<td align="right"><a href="javascript:OpenWindow('community_schedule_add.php?year=<?=date("Y")?>&month=<?=date("m")?>&day=<?=date("d")?>',350,130,'no','schedule')"><IMG SRC="images/main_center_momo_b1.gif"  ALT="" border="0"></a><a href="community_schedule_year.php"><IMG SRC="images/main_center_momo_b2.gif" ALT="" border="0"></a></td>
																							</tr>
																						</table>
																					</td>
																					<td width="7" background="images/main_title_bg.jpg"><IMG SRC="images/main_title_right.jpg" ALT=""></td>
																				</tr>
																				<tr>
																					<td height="20" colspan="5"></td>
																				</tr>
																				
																				<tr>
																					<td valign="top" colspan="2">

																						<!--바로가기메뉴-->
																						<table cellpadding="0" cellspacing="0" >
																							<tr>
																								<td valign="top" height="22"><a href="order_index.php"><IMG SRC="images/main_center_quick_st1.gif"  ALT="" border="0"></a></td>
																								<td class="font_gray3"  valign="top"><a href="order_list.php">주문조회</a><img src="images/main_center_quick_sel.gif" ><a href="order_delay.php">미배송/미입금</a><img src="images/main_center_quick_sel.gif"  ><a href="order_monthsearch.php">개월별주문조회</a></td>
																							</tr>
																							<tr>
																								<td valign="top" height="22"><a href="member_index.php"><IMG SRC="images/main_center_quick_st2.gif"  ALT="" border="0"></a></td>
																								<td class="font_gray3" valign="top"><a href="member_list.php">회원정보</a><img src="images/main_center_quick_sel.gif"  ><a href="member_outlist.php">탈퇴관리</a><img src="images/main_center_quick_sel.gif"  ><a href="member_mailsend.php">메일발송</a><img src="images/main_center_quick_sel.gif"  ><a href="market_smssinglesend.php">SMS발송</a></td>
																							</tr>
																							<tr>
																								<td valign="top" height="22"><a href="product_code.php"><IMG SRC="images/main_center_quick_st3.gif"  ALT="" border="0"></a></td>
																								<td class="font_gray3" valign="top"><a href="product_register.php">상품등록</a><img src="images/main_center_quick_sel.gif"  ><a href="product_price.php">가격일괄수정</a><img src="images/main_center_quick_sel.gif"  ><a href="product_allsoldout.php">품절상품</a><img src="images/main_center_quick_sel.gif"  ><a href="product_allquantity.php">재고일괄관리</a></td>
																							</tr>
																							<tr>
																								<td valign="top" height="22"><IMG SRC="images/main_center_quick_st4.gif"  ALT=""></td>
																								<td class="font_gray3" valign="top"><A HREF="order_list.php">주문DB백업</A><img src="images/main_center_quick_sel.gif"  ><A HREF="member_list.php">회원DB백업</A><img src="images/main_center_quick_sel.gif"  ><A HREF="product_exceldownload.php">상품DB백업</A></td>
																							</tr>
																						</table>
																						<!--바로가기메뉴-->

																					</td>
																					<td valign="top"></td>
																					<td valign="top" colspan="2">
																						<table cellpadding="0" cellspacing="0" width="100%" border="0">
																							<tr>
																								<td width="9" align="left"><IMG SRC="images/main_design_t_left.gif" WIDTH=9 HEIGHT=9 ALT=""></td>
																								<td height="9" background="images/main_design_t_leftbg.gif"></td>
																								<td width="9" align="right"><IMG SRC="images/main_design_t_right.gif" WIDTH=9 HEIGHT=9 ALT=""></td>
																							</tr>
																							<tr>
																								<td width="9" background="images/main_design_b_leftbg1.gif" align="left"></td>
																								<td background="images/main_design_bg.gif" align="center" >
																									<!--쇼핑메모장-->
																									<table cellpadding="0" cellspacing="0" width="100%">
																										<tr>
																											<td  valign="top">
																												<table cellpadding="1" cellspacing="0" width="100%" border="0">
																													<tr>
																														<td colspan="7" align="center" class="calender_title">(<?=date("Y")?>년 <?=date("m")?>월)</TD>
																													</tr>
																													<tr>
																														<td align=center style="padding-bottom:4pt;"><img src="images/main_calender_date_s.gif"  border="0"></td>
																														<td align=center style="padding-bottom:4pt;"><img src="images/main_calender_date_m.gif"  border="0"></td>
																														<td align=center style="padding-bottom:4pt;"><img src="images/main_calender_date_t.gif"  border="0"></td>
																														<td align=center style="padding-bottom:4pt;"><img src="images/main_calender_date_w.gif"  border="0"></td>
																														<td align=center style="padding-bottom:4pt;"><img src="images/main_calender_date_thu.gif"  border="0"></td>
																														<td align=center style="padding-bottom:4pt;"><img src="images/main_calender_date_fri.gif"  border="0"></td>
																														<td align=center style="padding-bottom:4pt;"><img src="images/main_calender_date_sat.gif"  border="0"></td>
																													</tr>
																												<?
																																			$days = 1;
																																			while(checkdate(date("m"),$days,date("Y"))) {
																																				$days++;
																																			}
																																			$total_days=$days-1;

																																			echo "<tr>\n";

																																			$first_day = date('w', mktime(0,0,0,(int)date("m"),1,date("Y")));
																																			unset($valueStr);
																																			$col = 0;
																																			for($i=0;$i<$first_day;$i++) {
																																				$valueStr .= "<td></td>";
																																				$col++;
																																			}

																																			$sql = "SELECT idx,import,rest,subject,duedate,duetime FROM tblschedule ";
																																			$sql.= "WHERE duedate LIKE '".date("Ym")."%' AND rest='Y' ";
																																			$sql.= "ORDER BY duetime ASC ";
																																			$result = mysql_query($sql,get_db_conn());
																																			unset($restDate);
																																			while($row = mysql_fetch_object($result)) {
																																				$restDate[$row->duedate] = "Y";
																																			}
																																			mysql_free_result($result);

																																			for($j=1;$j<=$total_days;$j++) {
																																				unset($dayname);
																																				$dayname = $j;

																																				$enum = $j;
																																				if ($j < 10) $enum = "0".$j;

																																				$fontclass="";
																																				if ($col == 0) {
																																					$fontclass="calender_sun";
																																				} else if ($col == 6) {
																																					$fontclass = "calender_sat";
																																					if ($restDate[date("Ym").$enum] == "Y") {
																																						$fontclass = "calender_sun";
																																					}
																																				} else {
																																					$fontclass = "calender";
																																					if ($restDate[date("Ym").$enum] == "Y") {
																																						$fontclass = "calender_sun";
																																					}
																																				}
																																				if($enum==date("d")) $fontclass="calender_select";
																																				$dayname = "<font class=".$fontclass.">".$j."</font>";
																																				$valueStr.="<td align=center><a href=\"community_schedule_day.php?year=".date("Y")."&month=".date("m")."&day=".$j."\">".$dayname."</a></td>\n";
																																				$col++;

																																				if ($col == 7) {
																																					$valueStr .= "</tr>";
																																					if ($j != $total_days) {
																																						$valueStr .= "<tr>";
																																					}
																																					$col = 0;
																																				}
																																			}

																																			while($col > 0 && $col < 7) {
																																				$valueStr .= "<td></td>";
																																				$col++;
																																			}
																																			$valueStr .= "</tr>";

																																			echo $valueStr;

																												?>
																												</table>
																											</td>
																											<td width="8" valign="top"></td>
																											<td valign="top" style="padding-top:19px">
																												<table cellpadding="0" cellspacing="0" width="100%" border="0">
																												<?
																																			$sql = "SELECT subject,duedate,rest FROM tblschedule ";
																																			$sql.= "WHERE duedate >= '".date("Ymd")."' ";
																																			$sql.= "ORDER BY duedate, duetime ASC LIMIT 6 ";
																																			$result = mysql_query($sql,get_db_conn());
																																			while($row=mysql_fetch_object($result)) {
																																				$weekday=date("w", mktime(0,0,0,(int)substr($row->duedate,4,2),(int)substr($row->duedate,6,2),(int)substr($row->duedate,0,4)));

																																				$fontclass="";
																																				if ($weekday == 0) {
																																					$fontclass="calender_sun";
																																				} else if ($weekday == 6) {
																																					$fontclass = "calender_sat";
																																					if ($row->rest == "Y") {
																																						$fontclass = "calender_sun";
																																					}
																																				} else {
																																					$fontclass = "calender";
																																					if ($row->rest == "Y") {
																																						$fontclass = "calender_sun";
																																					}
																																				}
																																				if($row->duedate==date("Ymd")) $fontclass="calender_select";

																																				echo "<tr>\n";
																																				echo "	<td width=8><img src=\"images/main_center_point1.gif\" border=0></td>\n";
																																				echo "	<td><A HREF=\"community_schedule_day.php?year=".substr($row->duedate,0,4)."&month=".substr($row->duedate,4,2)."&day=".substr($row->duedate,6,2)."\"><font class=".$fontclass.">[".substr($row->duedate,4,2)."-".substr($row->duedate,6,2)."]</font> <font class=".$fontclass.">".titleCut(15,$row->subject)."</font></A></td>\n";
																																				echo "</tr>\n";
																																			}
																																			mysql_free_result($result);
																												?>
																												</table>
																											</td>
																										</tr>
																									</table>
																						<!--쇼핑메모장-->
																								</td>
																								<td width="9" background="images/main_design_t_rightbg.gif" align="right"></td>
																							</tr>
																							<tr>
																								<td width="9"><IMG SRC="images/main_design_b_left.gif" WIDTH=9 HEIGHT=9 ALT=""></td>
																								<td background="images/main_design_b_leftbg.gif"></td>
																								<td width="9"><IMG SRC="images/main_design_b_right.gif" WIDTH=9 HEIGHT=9 ALT=""></td>
																							</tr>
																						</table>

																					</td>
																				</tr>
																				
																				<tr><td height="25" colspan="5"></td></tr>
																			</table>
																		</td>
																	</tr> 
																	*/ ?>
																	<tr>
																	<td>

																	<table cellpadding="0" cellspacing="0" width="100%">
																		<tr>
																			<td width="12" background="images/main_title_bg.jpg"><IMG SRC="images/main_title_left.jpg" WIDTH=12 HEIGHT=38 ALT=""></td>
																			<td width="50%" background="images/main_title_bg.jpg"><A HREF="product_latestup.php"><IMG SRC="images/main_center_latestup.gif" WIDTH=92 HEIGHT=24 ALT=""></a></td>
																			<td width="20" background="images/main_title_bg.jpg"></td>
																			<td width="50%" background="images/main_title_bg.jpg"><A HREF="product_latestsell.php"><IMG SRC="images/main_center_latestsell.gif" WIDTH=92 HEIGHT=24 ALT=""></a></td>
																			<td width="7" background="images/main_title_bg.jpg"><IMG SRC="images/main_title_right.jpg" ALT=""></td>
																		</tr>
																		<tr>
																			<td height="20" colspan="5"></td>
																		</tr>
																		<tr>
																			<td></td>
																			<td valign="top">


																					<!-- 최근등록상품 시작 -->
																					<table border=0 cellpadding=3 cellspacing=0 width=100%>
																						<tr>
																							<td align=center>
																								<table border=0 cellpadding=0 cellspacing=0 width=98%>
																								<col width=50></col>
																								<col width=></col>
																								<?
																									$sql = "SELECT productcode,productname,tinyimage,regdate,social_chk FROM tblproduct ";
																									$sql.= "ORDER BY regdate DESC LIMIT 5 ";
																									$result=mysql_query($sql,get_db_conn());
																									while($row=mysql_fetch_object($result)) {
																										$gubun = ($row->social_chk=="Y")? "3":((substr($row->productcode,0,3) == "999")? "1":"0");
																										echo "<tr>\n";
																										echo "	<td>";
																										if (strlen($row->tinyimage)>0 && file_exists($Dir.DataDir."shopimages/product/".$row->tinyimage)==true) {
																											echo "<img src=\"".$Dir.DataDir."shopimages/product/".urlencode($row->tinyimage)."\" border=0 width=50 style=\"border:1px #efefef solid\">";
																										} else {
																											echo "<img src=\"".$Dir."images/no_img.gif\" border=0 width=50 style=\"border:1px #efefef solid\">";
																										}
																										echo "	</td>\n";
																										echo "	<td style=\"padding-left:10\">";
																										echo "	<A HREF=\"JavaScript:ProductInfo('".substr($row->productcode,0,12)."','".$row->productcode."','YES','".$gubun."')\">".$row->productname."</A>";
																										echo "	<br><FONT COLOR=#FF7C00>".str_replace("-","/",substr($row->regdate,0,10))."</FONT>\n";
																										echo "	</td>\n";
																										echo "</tr>\n";
																									}
																									mysql_free_result($result);
																								?>
																								</table>
																							</td>
																						</tr>
																					</table>
																				<!-- 최근등록상품 끝 -->


																			</td>
																			<td valign="top"></td>
																			<td valign="top">


																				<!-- 최근판매상품 시작 -->
																					<table border=0 cellpadding=3 cellspacing=0 width=100%>
																						<tr>
																							<td align=center>
																								<table border=0 cellpadding=0 cellspacing=0 width=98%>
																								<?
																									$sql = "SELECT productcode,productname,tinyimage,selldate,social_chk FROM tblproduct ";
																									$sql.= "WHERE selldate!='0000-00-00 00:00:00' ORDER BY selldate DESC LIMIT 5 ";
																									$result=mysql_query($sql,get_db_conn());
																									while($row=mysql_fetch_object($result)) {
																										$gubun = ($row->social_chk=="Y")? "3":((substr($row->productcode,0,3) == "999")? "1":"0");
																										echo "<tr>\n";
																										echo "	<td>";
																										if (strlen($row->tinyimage)>0 && file_exists($Dir.DataDir."shopimages/product/".$row->tinyimage)==true) {
																											echo "<img src=\"".$Dir.DataDir."shopimages/product/".urlencode($row->tinyimage)."\" border=0 width=50 style=\"border:1px #efefef solid\">";
																										} else {
																											echo "<img src=\"".$Dir."images/no_img.gif\" border=0 width=50 style=\"border:1px #efefef solid\">";
																										}
																										echo "	</td>\n";
																										echo "	<td style=\"padding-left:10\">";
																										echo "	<A HREF=\"JavaScript:ProductInfo('".substr($row->productcode,0,12)."','".$row->productcode."','YES','".$gubun."')\">".$row->productname."</A>";
																										echo "	<br><FONT COLOR=#FF7C00>".str_replace("-","/",substr($row->selldate,0,10))."</FONT>\n";
																										echo "	</td>\n";
																										echo "</tr>\n";
																									}
																									mysql_free_result($result);
																								?>
																								</table>
																							</td>
																						</tr>
																					</table>
																					<!-- 최근판매상품 끝 -->


																			</td>
																			<td></td>
																		</tr>
																		<tr>
																			<td height="60" colspan="5"></td>
																		</tr>
																	</table>

																	</td>
																</tr>
															</table>
<!--#######본문 left시작#######################################################################################################################--->

														</td>

<!--######################## 공백 6px #######################################################################################################################--
														<td width="6" valign="top"><img src="images/space01.gif" width="6" height="1" border="0"></td>
<!--#########################################################################################################################################################--

														<td valign="top">
<!--######################## 본문 right 배너 시작 #########################################################################################################--
															<table cellpadding="0" cellspacing="0" width="210">
																<tr>
																	<td>
																		<!--마케팅을 도와주는 주요 운영관리--
																		<table cellpadding="0" cellspacing="0" width="100%"  style="margin-bottom:10px;">
																			<tr>
																				<td width="8"><IMG SRC="images/main_rightbanner_tleft.gif" WIDTH=8 HEIGHT=8 ALT=""></td>
																				<td background="images/main_rightbanner_tbg.gif"></td>
																				<td width="8"><IMG SRC="images/main_rightbanner_tright.gif" WIDTH=8 HEIGHT=8 ALT=""></td>
																			</tr>
																			<tr>
																				<td width="8" background="images/main_rightbanner_lbg.gif"></td>
																				<td style="padding-top:10px;padding-bottom:15px" bgcolor="#ECECEC">

																					<table cellpadding="0" cellspacing="0" width="100%">
																						<tr>
																							<td><IMG SRC="images/main_rightbanner_t01.gif" WIDTH=142 HEIGHT=35 ALT=""></td>
																						</tr>
																						<tr>
																							<td height="20"></td>
																						</tr>
																						<tr>
																							<td height="20">

																								<table cellpadding="0" cellspacing="0" width="85%" align="center">
																									<tr>
																										<td width="16"><IMG SRC="images/main_icon_nero.gif" WIDTH=12 HEIGHT=11 ALT=""></td>
																										<td><A href="javascript:parent.topframe.GoMenu(1,'shop_snsinfo.php');">SNS운영설정</a></td>
																									</tr>
																									<tr>
																										<td><IMG SRC="images/main_icon_nero.gif" WIDTH=12 HEIGHT=11 ALT=""></td>
																										<td><A href="javascript:parent.topframe.GoMenu(1,'shop_recommand.php');">추천인설정</a></td>
																									</tr>
																									<tr>
																										<td><IMG SRC="images/main_icon_nero.gif" WIDTH=12 HEIGHT=11 ALT=""></td>
																										<td><A href="javascript:parent.topframe.GoMenu(1,'shop_reserve.php');">적립금/쿠폰적용설정</a></td>
																									</tr>
																									<tr>
																										<td><IMG SRC="images/main_icon_nero.gif" WIDTH=12 HEIGHT=11 ALT=""></td>
																										<td><A href="javascript:parent.topframe.GoMenu(7,'market_couponnew.php');">쿠폰발급관리</a></td>
																									</tr>
																									<tr>
																										<td><IMG SRC="images/main_icon_nero.gif" WIDTH=12 HEIGHT=11 ALT=""></td>
																										<td><A href="javascript:parent.topframe.GoMenu(4,'product_giftlist.php');">사은품관리</a></td>
																									</tr>
																									<tr>
																										<td><IMG SRC="images/main_icon_nero.gif" WIDTH=12 HEIGHT=11 ALT=""></td>
																										<td><A href="javascript:parent.topframe.GoMenu(1,'market_cash_reserve.php');">현금전환신청관리</a></td>
																									</tr>
																									<tr>
																										<td><IMG SRC="images/main_icon_nero.gif" WIDTH=12 HEIGHT=11 ALT=""></td>
																										<td><A href="javascript:parent.topframe.GoMenu(1,'product2_register.php');">상품권관리</a></td>
																									</tr>
																								</table>

																							</td>
																						</tr>
																					</table>


																				</td>
																				<td width="8" background="images/main_rightbanner_rbg.gif"></td>
																			</tr>
																			<tr>
																				<td width="8"><IMG SRC="images/main_rightbanner_bleft.gif" WIDTH=8 HEIGHT=8 ALT=""></td>
																				<td background="images/main_rightbanner_bbg.gif"></td>
																				<td width="8"><IMG SRC="images/main_rightbanner_bright.gif" WIDTH=8 HEIGHT=8 ALT=""></td>
																			</tr>
																		</table>
																		--마케팅을 도와주는 주요 운영관리-->



																	<!--</td>
																</tr>																
																<tr>
																	<td>

																		<!--효율적인 회원관리--
																		<table cellpadding="0" cellspacing="0" width="100%"  style="margin-bottom:10px;">
																			<tr>
																				<td width="8"><IMG SRC="images/main_rightbanner_tleft.gif" WIDTH=8 HEIGHT=8 ALT=""></td>
																				<td background="images/main_rightbanner_tbg.gif"></td>
																				<td width="8"><IMG SRC="images/main_rightbanner_tright.gif" WIDTH=8 HEIGHT=8 ALT=""></td>
																			</tr>
																			<tr>
																				<td width="8" background="images/main_rightbanner_lbg.gif"></td>
																				<td style="padding-top:10px;padding-bottom:15px" bgcolor="#ECECEC">


																					<table cellpadding="0" cellspacing="0" width="100%">
																						<tr>
																							<td><IMG SRC="images/main_rightbanner_t03.gif" ALT=""></td>
																						</tr>
																						<tr>
																							<td height="7"></td>
																						</tr>
																						<tr>
																							<td height="20">

																								<table cellpadding="0" cellspacing="0" width="85%" align="center">
																									<tr>
																										<td width="16"><IMG SRC="images/main_icon_nero.gif" WIDTH=12 HEIGHT=11 ALT=""></td>
																										<td><a href="member_list.php">회원리스트 관리</a></td>
																									</tr>
																									<tr>
																										<td width="16"><IMG SRC="images/main_icon_nero.gif" WIDTH=12 HEIGHT=11 ALT=""></td>
																										<td><a href="member_groupnew.php">회원등급 기능</a></td>
																									</tr>
																									<tr>
																										<td><IMG SRC="images/main_icon_nero.gif" WIDTH=12 HEIGHT=11 ALT=""></td>
																										<td><a href="member_excelupload.php"><b>회원정보 일괄등록</a></b></td>
																									</tr>
																									<tr>
																										<td><IMG SRC="images/main_icon_nero.gif" WIDTH=12 HEIGHT=11 ALT=""></td>
																										<td><a href="member_mailallsend.php">단체 메일 발송</a></td>
																									</tr>
																									<tr>
																										<td><IMG SRC="images/main_icon_nero.gif" WIDTH=12 HEIGHT=11 ALT=""></td>
																										<td><a href="market_smsgroupsend.php">단체 sms 발송</a></td>
																									</tr>
																								</table>
																							</td>
																						</tr>
																					</table>

																				</td>
																				<td width="8" background="images/main_rightbanner_rbg.gif"></td>
																			</tr>
																			<tr>
																				<td width="8"><IMG SRC="images/main_rightbanner_bleft.gif" WIDTH=8 HEIGHT=8 ALT=""></td>
																				<td background="images/main_rightbanner_bbg.gif"></td>
																				<td width="8"><IMG SRC="images/main_rightbanner_bright.gif" WIDTH=8 HEIGHT=8 ALT=""></td>
																			</tr>
																		</table>
																		--효율적인 회원관리-->

																	<!--</td>
																</tr>
																<tr>
																	<td>

																		<!--강력한 입점기능 미니샵--
																		<table cellpadding="0" cellspacing="0" width="100%"  style="margin-bottom:10px;">
																			<tr>
																				<td width="8"><IMG SRC="images/main_rightbanner_tleft.gif" WIDTH=8 HEIGHT=8 ALT=""></td>
																				<td background="images/main_rightbanner_tbg.gif"></td>
																				<td width="8"><IMG SRC="images/main_rightbanner_tright.gif" WIDTH=8 HEIGHT=8 ALT=""></td>
																			</tr>

																			<tr>
																				<td width="8" background="images/main_rightbanner_lbg.gif"></td>
																				<td style="padding-top:10px;padding-bottom:15px" bgcolor="#ECECEC">


																					<table cellpadding="0" cellspacing="0" width="100%">
																						<tr>
																							<td><IMG SRC="images/main_rightbanner_t04.gif" ALT=""></td>
																						</tr>
																						<tr>
																							<td height="14"></td>
																						</tr>
																						<tr>
																							<td height="20">

																								<table cellpadding="0" cellspacing="0" width="85%" align="center">
																									<tr>
																										<td width="16"><IMG SRC="images/main_icon_nero.gif" WIDTH=12 HEIGHT=11 ALT=""></td>
																										<td><?=(setUseVender()==true?"<a href=\"vender_new.php\">":"<a href=\"javascript:not_vender_alert();\">")?><font color="#666666">입점업체 등록</font></a></td>
																									</tr>
																									<tr>
																										<td width="16"><IMG SRC="images/main_icon_nero.gif" WIDTH=12 HEIGHT=11 ALT=""></td>
																										<td><?=(setUseVender()==true?"<a href=\"vender_management.php\">":"<a href=\"javascript:not_vender_alert();\">")?><font color="#666666">입점업체 관리</font></a></td>
																									</tr>
																									<tr>
																										<td><IMG SRC="images/main_icon_nero.gif" WIDTH=12 HEIGHT=11 ALT=""></td>
																										<td><?=(setUseVender()==true?"<a href=\"vender_orderlist.php\">":"<a href=\"javascript:not_vender_alert();\">")?><font color="#666666">입점업체 주문조회</font></a></td>
																									</tr>
																									<tr>
																										<td><IMG SRC="images/main_icon_nero.gif" WIDTH=12 HEIGHT=11 ALT=""></td>
																										<td><?=(setUseVender()==true?"<a href=\"vender_orderadjust.php\">":"<a href=\"javascript:not_vender_alert();\">")?><font color="#666666">입점업체 정산관리</font></a></td>
																									</tr>
																									<tr>
																										<td><IMG SRC="images/main_icon_nero.gif" WIDTH=12 HEIGHT=11 ALT=""></td>
																										<td><a href="/vender/" target="_blank"><font color="#3399cc"><b>미니샵 로그인</b></font></a></td>
																									</tr>
																								</table>
																							</td>
																						</tr>
																					</table>

																				</td>
																				<td width="8" background="images/main_rightbanner_rbg.gif"></td>
																			</tr>
																			<tr>
																				<td width="8"><IMG SRC="images/main_rightbanner_bleft.gif" WIDTH=8 HEIGHT=8 ALT=""></td>
																				<td background="images/main_rightbanner_bbg.gif"></td>
																				<td width="8"><IMG SRC="images/main_rightbanner_bright.gif" WIDTH=8 HEIGHT=8 ALT=""></td>
																			</tr>
																		</table>
																		--강력한 입점기능 미니샵-->


																	<!--</td>
																</tr>
															</table>
<!--######################## 본문 right배너 끝 ########################################################################--
														</td>
													</tr>
												</table>
<!--######################## 본문 끝 ################################################################################################-->

											<td width="10" valign="top"><img src="images/space01.gif" width="10" height="1" border="0"></td>


											</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			<form name=prform method=post>
				<input type=hidden name=code>
				<input type=hidden name=prcode>
				<input type=hidden name=popup>
			</form>

			<form name=detailform method="post" action="order_detail.php" target="orderdetail">
				<input type=hidden name=ordercode>
			</form>
			<IFRAME name="tempiframe" src="<?=$Dir?>blank.php" width=0 height=0 frameborder=0 scrolling="no" marginheight="0" marginwidth="0"></IFRAME>
		</td>
	</tr>
</table>
<script language="JavaScript" Event="onLoad" For="window">
document.tempiframe.location="main_socketdata.php";
<?
/* 마스터알람이 있는 경우 */
if($masterAlarm>0){
?>
	var alarmCookie=getCookie("alarm"); 
	if (alarmCookie != "no") {
		MasterAlarm.view();
	}else{
		$('alarmdiv').setStyle('display','block');
	}
<?
}	
/* 마스터알람이 있는 경우 */
?>
</script>

<div id="create_openwin" style="display:none"></div>

<style> 
/* 떠다니는 배너 (Floating Menu) */ 
#alarmdiv { 
    position:fixed; _position:absolute; 
	z-index:1; 
    overflow:hidden; 
    right:0; 
    bottom:40; 
    background-color: transparent; 
    padding:0; 
}
</style> 
<div id="alarmdiv" style="display:none"><button onclick="javascript:alarmView();">Click</button></div>

<? INCLUDE ("copyright.php"); ?>
