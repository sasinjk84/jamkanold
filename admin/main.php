<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");

INCLUDE ("access.php");

//�ڵ��±޼���
include "groupauto.php";
//�ڵ��±޼���


$curdate = date("Ymd");

//�����;˶�
$masterAlarm = 0;

/* ������ �����û�� �ִ� ��� */
$sql = "SELECT count(*) as cnt FROM vender_more_info WHERE commission_status='1'";
$result1=mysql_query($sql,get_db_conn());
$_vmdata=mysql_fetch_object($result1);
mysql_free_result($result1);

if($_vmdata->cnt>0){
	$masterAlarm++;
}
/* ������ �����û�� �ִ� ��� */

/* ������ü ���Խ��ǿ� ���ǰ� �ִ� ��� */
$sql = "SELECT COUNT(*) as cnt FROM tblvenderadminqna WHERE re_date is NULL ";
$result2=mysql_query($sql,get_db_conn());
$_qnadata=mysql_fetch_object($result2);
mysql_free_result($result2);

if($_qnadata->cnt>0){
	$masterAlarm++;
}
/* ������ü ���Խ��ǿ� ���ǰ� �ִ� ��� */

/* ȸ�� ��޺� ���κ����û�� �ִ� ��� */
$sql = "SELECT productcode FROM discount_chgrequest";
$result3=mysql_query($sql,get_db_conn());
$_dcdata=mysql_fetch_object($result3);
mysql_free_result($result3);

if($_dcdata->productcode>0){
	$masterAlarm++;
}
/* ȸ�� ��޺� ���κ����û�� �ִ� ��� */

/* ��õ�� ���������û�� �ִ� ��� */
$sql = "SELECT productcode FROM req_chgresellerreserv";
$result4=mysql_query($sql,get_db_conn());
$_revdata=mysql_fetch_object($result4);
mysql_free_result($result4);

if($_revdata->productcode>0){
	$masterAlarm++;
}
/* ��õ�� ���������û�� �ִ� ��� */

/* �����ݺ����û�� �ִ� ��� */
$sql = "SELECT productcode FROM reserve_chgrequest";
$result5=mysql_query($sql,get_db_conn());
$_rcdata=mysql_fetch_object($result5);
mysql_free_result($result5);

if($_rcdata->productcode>0){
	$masterAlarm++;
}
/* �����ݺ����û�� �ִ� ��� */


/* ��õ�� �����ݺ����û�� �ִ� ��� */
$sql = "SELECT productcode FROM reseller_reserve_chgrequest";
$result6=mysql_query($sql,get_db_conn());
$_rrcdata=mysql_fetch_object($result6);
mysql_free_result($result6);

if($_rrcdata->productcode>0){
	$masterAlarm++;
}
/* ��õ�� �����ݺ����û�� �ִ� ��� */

?>

<? INCLUDE ("header.php"); ?>
<script>try {parent.topframe.ChangeMenuImg(0);}catch(e){}</script>
<style>td	{font-family:"����,����";color:#4B4B4B;font-size:12px;line-height:17px;}</style>
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
	// cookie ���ڿ� ��ü�� �˻� 
	while(i <= document.cookie.length) 
	{ 
		start = i 
		end = start + name.length 
		// name�� ������ ���ڰ� �ִٸ� 
		if(document.cookie.substring(start, end) == name) 
		{
			Found = true 
			break 
		} 
		i++ 
	}
		
	// name ���ڿ��� cookie���� ã�Ҵٸ� 
	if(Found == true) 
	{ 
		start = end + 1 
		end = document.cookie.indexOf(";", start) 
		// ������ �κ��̶� �� ���� �ǹ�(���������� ";"�� ����) 
		if(end < start) 
		end = document.cookie.length 
		// name�� �ش��ϴ� value���� �����Ͽ� �����Ѵ�. 
		return document.cookie.substring(start, end) 
	} 
	// ã�� ���ߴٸ� 
	return "" 
} 

function setCookie( name, value, expiredays ) { 
	var todayDate = new Date(); 
		todayDate.setDate( todayDate.getDate() + expiredays ); 
		document.cookie = name + "=" + escape( value ) + "; path=/; expires=" + todayDate.toGMTString() + ";" 
} 

//######################################################################################################
//��������
function shop_noticeview(type,code) {
	alert("�˼��մϴ�. ��� �� �̿��Ͻñ� �ٶ��ϴ�.");
}

//��ü�帧��
function shop_process() {
	alert("�˼��մϴ�. ��� �� �̿��Ͻñ� �ٶ��ϴ�.");
}

//�޴���
function shop_menual() {
	alert("�˼��մϴ�. ��� �� �̿��Ͻñ� �ٶ��ϴ�.");
}

//���θ� TIP&���
function shop_tip() {
	alert("�˼��մϴ�. ��� �� �̿��Ͻñ� �ٶ��ϴ�.");
}

//���� �� ������� �Ű�
function shop_report() {
	alert("�˼��մϴ�. ��� �� �̿��Ͻñ� �ٶ��ϴ�.");
}

//��������
function not_vender_alert() {
	alert("������� �� �̴ϼ��� ���θ�(E-market) ���������� ����Ͻ� �� �ֽ��ϴ�.");
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

	// F5 ���� ��ħ ����
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

<!--######################## ���� ���� ##########################################################################################################################-->
												<table cellpadding="0" cellspacing="0" width="198">
													<tr>
														<td>

															<!--���θ� �⺻����-->
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
																								################# ��� ��ǰ�� #########################
																								$sql = "SELECT COUNT(*) as totproduct FROM tblproduct ";
																								$result=mysql_query($sql,get_db_conn());
																								$row=mysql_fetch_object($result);
																								$totproduct=(int)$row->totproduct;
																								mysql_free_result($result);

																								################# ������� #################
																								$vender_used = setVenderUsed();
																								################# PG���� ###################
																								$pg_used="";
																								if($f=@file(DirPath.AuthkeyDir."pg")) {
																									$pg_used="<font class=\"font_orange4\">���ÿϷ�</font>";
																									//$img_icon="<img src=\"images/main_icon_ok.gif\">";
																									$pg_icon="<a style=\"cursor:hand\" onclick=\"alert('����Ű ���丮�� PG���� Ű�� �����մϴ�.')\"><font class=\"white_font\">�����</font></a>";
																								} else {
																									$pg_used="<a href=\"http://www.getmall.co.kr/front/paymentadd.php\" onclick=\"window.open(this.href,'winKcp','width=870px,height=800px,scrollbars=1,resizable=0,locationbars=0');return false;\" target=\"_blank\"><font color=#FFFFFF>�̻��</font></a>";

																									$pg_icon="<a href=\"http://www.getmall.co.kr/front/paymentadd.php\" onclick=\"window.open(this.href,'winKcp','width=870px,height=800px,scrollbars=1,resizable=0,locationbars=0');return false;\" target=\"_blank\"><img src=\"images/main_icon_order.gif\"></a>";
																								}
																								################# SMS �ܿ� #################
																								$sql = "SELECT id, authkey FROM tblsmsinfo ";
																								$result=mysql_query($sql,get_db_conn());
																								$row=mysql_fetch_object($result);
																								mysql_free_result($result);
																								$sms_id=$row->id;
																								$sms_authkey=$row->authkey;

																								$sms_count="";
																								if(strlen($sms_id)==0 || strlen($sms_authkey)==0) {
																									$sms_count="<A style=\"cursor:hand\" onclick=\"sms_join();\"><font color=#FFFFFF>�̻��</font></A>";
																									$sms_icon="<A style=\"cursor:hand\" onclick=\"sms_join();\"><img src=\"images/main_icon_order.gif\"></A>";
																								} else {
																									$smscountdata=getSmscount($sms_id, $sms_authkey);
																									if(substr($smscountdata,0,2)=="OK") {
																										$sms_count="<font class=\"font_orange4\"><b>".substr($smscountdata,3)."</b></font> ��";
																										$sms_icon="<font class=\"white_font\">�����</font>";
																									} else if(substr($smscountdata,0,2)=="NO") {
																										$sms_count="<A style=\"cursor:hand\" onclick=\"alert(\'SMS ȸ�� ���̵� �������� �ʽ��ϴ�. SMS �⺻ȯ�� �������� SMS ���̵� �� ����Ű�� ��Ȯ�� �Է��Ͻñ� �ٶ��ϴ�.\');\"><font class=\"font_orange4\"><B>��������!!</B></font></A>";
																									} else if(substr($smscountdata,0,2)=="AK") {
																										$sms_count="<A style=\"cursor:hand\" onclick=\"alert(\'SMS ȸ�� ����Ű�� ��ġ���� �ʽ��ϴ�. SMS �⺻ȯ�� �������� ����Ű�� ��Ȯ�� �Է��Ͻñ� �ٶ��ϴ�.\');\"><font class=\"font_orange4\"><B>��������!!</B></font></A>";
																									} else {
																										$sms_count="<A style=\"cursor:hand\" onclick=\"alert(\'SMS ������ ����� �Ұ����մϴ�. ��� �� �̿��Ͻñ� �ٶ��ϴ�.\');\"><font class=\"font_orange4\"><B>��ſ���!!</B></font></A>";
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
																										<td class="font_gray4">��ǰ</td>
																										<td class="font_gray4">:</td>
																										<td class="font_gray4"><b><span class="font_orange7"><?=$totproduct?></span></b>�� ���</td>
																										<td></td>
																									</tr>
																									<tr>
																										<td class="font_gray4">�������</td>
																										<td class="font_gray4">:</td>
																										<td class="font_gray4"><?=$vender_used[0]?></td>
																										<td><?=$vender_used[1]?></td>
																									</tr>
																									<tr>
																										<td class="font_gray4">PG ����</td>
																										<td class="font_gray4">:</td>
																										<td class="font_gray4"><?=$pg_used?></td>
																										<td><?=$pg_icon?></td>
																									</tr>
																									<tr>
																										<td class="font_gray4">SMS�ܿ�</td>
																										<td class="font_gray4">:</td>
																										<td class="font_gray4"><?=$sms_count;?></td>
																										<td><?=$sms_icon?></td>
																									</tr>
																									<tr>
																										<td class="font_gray4" colspan="3">ȣ������Ȳ</td>
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
															<!--���θ� �⺻����-->

														</td>
													</tr>
													<tr>
														<td style="padding-top:17px;padding-bottom:17px;padding-left:9px">
															<!--����� �����Ȳ-->
															 <iframe src="http://www.getmall.co.kr/frames/admin_main_service.php"  WIDTH="177" height="211" frameborder="0" scrolling="no" marginwidth="0" background="#45464c" marginheight="0" name="service"  allowtransparency="true"></iframe>
														</td>
													</tr>
													<tr>
														<td><img src="images/main_left_line01.gif"></td>
													</tr>													

													<tr>
														<td style="padding-left:9px" height="40"><a href="http://www.getmall.co.kr/manual/" target="_blank"><IMG SRC="images/main_left_menual.gif" ALT="���θ� � �޴���"></a></td>
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

															<!--�ٸ� �����;ȳ�-->
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
																				<td class="font_gray" width="150"><a href="http://www.getmall.co.kr/board/board.php?board=manage" target="_blank"><font color="#FF6600">[�Խ��ǿ� �����ϱ�]</font></td>
																				<td></td>
																			</tr>
																			<tr>
																				<td><img src="images/main_left_icon01.gif"></td>
																				<td class="font_gray"><a href="http://www.getmall.co.kr" target="_blank">Ȩ������ �ٷΰ���</a></td>
																				<td></td>
																			</tr>
																			<tr>
																				<td><img src="images/main_left_icon01.gif"></td>
																				<td class="font_gray"><a href="http://www.facebook.com/getmalldream" target="_blank">���̽���</a> / <a href="http://twitter.com/getmalldream" target="_blank">Ʈ����</a></td>
																				<td></td>
																			</tr>
																			<tr>
																				<td><img src="images/main_left_icon01.gif"></td>
																				<td class="font_gray"><a href="http://blog.naver.com/getmall_pr" target="_blank">��α�</a> / <a href="http://cafe.naver.com/mallpd" target="_blank">ī��</a></td>
																				<td></td>
																			</tr>
																		</table>

																	</td>
																</tr>
																<tr>
																	<td height="20"></td>
																</tr>
															</table>
															<!--�ٸ� �����;ȳ�-->
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
<!--######################## ���� �� ################################################################################################-->
											</td>
											<td  valign="top"></td>
											<td width="6" valign="top"><img src="images/space01.gif" width="6" height="1" border="0"></td>
											<td valign="top">
<!--######################## ���� ���� ################################################################################################-->
												<table cellpadding="0" cellspacing="0" width="100%" border="0">
													<tr>
														<td colspan=3 style="padding-top:20px;">
															<!--�ֹ�/���ó�� ��Ȳ-->
															<table cellpadding="0" cellspacing="0" width="100%"  border="0">
																<tr>
																	<td width="16" background="images/main_state_top_left.jpg" height="34" valign="top"></td>
																	<td align="left" valign="top" rowspan="3"  bgcolor="#FBFBFB">
																		<?

																			$curdate_1 = date("Ymd",mktime(0,0,0,date("m"),date("d")-1,date("Y"))); // �Ϸ���
																			$curdate_2 = date("Ymd",mktime(0,0,0,date("m"),date("d")-2,date("Y"))); // ��Ʋ��
																			$curdate_3 = date("Ymd",mktime(0,0,0,date("m"),date("d")-3,date("Y")));
																			$curdate_4 = date("Ymd",mktime(0,0,0,date("m"),date("d")-4,date("Y")));
																			$curdate_7 = date("Ymd",mktime(0,0,0,date("m"),date("d")-7,date("Y")));

																			/* ȸ������ ��� */
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

																			$totmemcnt=(int)$row->totmemcnt;		//���� ȸ�����Լ�
																			$totmemcnt1=(int)$row->totmemcnt1;		//1���� ȸ�����Լ�
																			$totmemcnt2=(int)$row->totmemcnt2;		//2���� ȸ�����Լ�
																			$totmemcnt3=(int)$row->totmemcnt3;		//3���� ȸ�����Լ�
																			$totmemcnt4=(int)$row->totmemcnt4;		//3���� ȸ�����Լ�
																			$totmemcnt7=(int)$row->totmemcnt7;			//1���ϵ��� ȸ�����Լ�
																			$totmonmemcnt=(int)$row->totmonmemcnt;	//�̴� ȸ�����Լ�
																			$totremonmemcnt=(int)$row->totremonmemcnt;	//������ ȸ�����Լ�
																			/* ȸ������ ���  ��*/

																			/* �Խñ� ��� */
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

																			$totbrdcnt=(int)$row->totbrdcnt;		//���� ��ϵ� �Խù���
																			$totbrdcnt1=(int)$row->totbrdcnt1;		//1���� ��ϵ� �Խù���
																			$totbrdcnt2=(int)$row->totbrdcnt2;		//2���� ��ϵ� �Խù���
																			$totbrdcnt3=(int)$row->totbrdcnt3;		//3���� ��ϵ� �Խù���
																			$totbrdcnt7=(int)$row->totbrdcnt7;		//�����ϵ��� ��ϵ� �Խù���
																			$totmonbrdcnt=(int)$row->totmonbrdcnt;	//�̴� ��ϵ� �Խù���
																			$totremonbrdcnt=(int)$row->totremonbrdcnt;	//������ ��ϵ� �Խù���
																			/* �Խñ� ��� �� */
																			
																			/* �湮�� ��� */
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

																			$totvstcnt=(int)$row->totvstcnt;		//���� �湮�ڼ�
																			$totvstcnt1=(int)$row->totvstcnt1;		//1���� �湮�ڼ�
																			$totvstcnt2=(int)$row->totvstcnt2;		//2���� �湮�ڼ�
																			$totvstcnt3=(int)$row->totvstcnt3;		//3���� �湮�ڼ�
																			$totvstcnt7=(int)$row->totvstcnt7;		//�����ϵ��� �湮�ڼ�
																			$totmonvstcnt=(int)$row->totmonvstcnt;	//�̴� �湮�ڼ�
																			$totremonvstcnt=(int)$row->totremonvstcnt;	//������ �湮�ڼ�
																			/* �湮�� ��� �� */

																			/* �ֹ� ��� */
																			$sql = "SELECT ";
																			//���� �ֹ��Ǽ� �� �ֹ��ݾ�
																			$sql.= "COUNT(IF(ordercode LIKE '".$curdate."%',1,NULL)) as totordcnt, ";
																			$sql.= "SUM(IF((ordercode LIKE '".$curdate."%') && (deli_gbn IN('Y')),price,0)) as totordprice, ";
																			//���� �̹�� �Ǽ� �� �̹�۰� �ݾ�
																			$sql.= "COUNT(IF((ordercode LIKE '".$curdate."%') && (deli_gbn IN('N','X')),1,NULL)) as totdelaycnt, ";
																			$sql.= "SUM(IF((ordercode LIKE '".$curdate."%') && (deli_gbn IN('N','X')),price,0)) as totdelayprice, ";
																			//���� ȯ��/��� �Ǽ�
																			$sql.= "COUNT(IF((ordercode LIKE '".$curdate."%') && (deli_gbn IN('C','D')),1,NULL)) as totcancelcnt, ";

																			//1���� �ֹ��Ǽ� �� �ֹ��ݾ�
																			$sql.= "COUNT(IF(ordercode LIKE '".$curdate_1."%',1,NULL)) as totordcnt1, ";
																			$sql.= "SUM(IF((ordercode LIKE '".$curdate_1."%') && (deli_gbn IN('Y')),price,0)) as totordprice1, ";
																			//1���� �̹�� �Ǽ� �� �̹�۰� �ݾ�
																			$sql.= "COUNT(IF((ordercode LIKE '".$curdate_1."%') && (deli_gbn IN('N','X')),1,NULL)) as totdelaycnt1, ";
																			$sql.= "SUM(IF((ordercode LIKE '".$curdate_1."%') && (deli_gbn IN('N','X')),price,0)) as totdelayprice1, ";
																			//1���� ȯ��/��� �Ǽ�
																			$sql.= "COUNT(IF((ordercode LIKE '".$curdate_1."%') && (deli_gbn IN('C','D')),1,NULL)) as totcancelcnt1, ";

																			//2���� �ֹ��Ǽ� �� �ֹ��ݾ�
																			$sql.= "COUNT(IF(ordercode LIKE '".$curdate_2."%',1,NULL)) as totordcnt2, ";
																			$sql.= "SUM(IF((ordercode LIKE '".$curdate_2."%') && (deli_gbn IN('Y')),price,0)) as totordprice2, ";
																			//2���� �̹�� �Ǽ� �� �̹�۰� �ݾ�
																			$sql.= "COUNT(IF((ordercode LIKE '".$curdate_2."%') && (deli_gbn IN('N','X')),1,NULL)) as totdelaycnt2, ";
																			$sql.= "SUM(IF((ordercode LIKE '".$curdate_2."%') && (deli_gbn IN('N','X')),price,0)) as totdelayprice2, ";
																			//2���� ȯ��/��� �Ǽ�
																			$sql.= "COUNT(IF((ordercode LIKE '".$curdate_2."%') && (deli_gbn IN('C','D')),1,NULL)) as totcancelcnt2, ";

																			//3���� �ֹ��Ǽ� �� �ֹ��ݾ�
																			$sql.= "COUNT(IF(ordercode LIKE '".$curdate_3."%',1,NULL)) as totordcnt3, ";
																			$sql.= "SUM(IF((ordercode LIKE '".$curdate_3."%') && (deli_gbn IN('Y')),price,0)) as totordprice3, ";
																			//3���� �̹�� �Ǽ� �� �̹�۰� �ݾ�
																			$sql.= "COUNT(IF((ordercode LIKE '".$curdate_3."%') && (deli_gbn IN('N','X')),1,NULL)) as totdelaycnt3, ";
																			$sql.= "SUM(IF((ordercode LIKE '".$curdate_3."%') && (deli_gbn IN('N','X')),1,0)) as totdelayprice3, ";

																			//4���� �ֹ��Ǽ� �� �ֹ��ݾ�
																			$sql.= "COUNT(IF(ordercode LIKE '".$curdate_4."%',1,NULL)) as totordcnt4, ";
																			$sql.= "SUM(IF((ordercode LIKE '".$curdate_4."%') && (deli_gbn IN('Y')),price,0)) as totordprice4, ";
																			//4���� �̹�� �Ǽ� �� �̹�۰� �ݾ�
																			$sql.= "COUNT(IF((ordercode LIKE '".$curdate_4."%') && (deli_gbn IN('N','X')),1,NULL)) as totdelaycnt4, ";
																			$sql.= "SUM(IF((ordercode LIKE '".$curdate_4."%') && (deli_gbn IN('N','X')),1,0)) as totdelayprice4, ";

																			//1���ϵ��� �ֹ��Ǽ� �� ����
																			$sql.= "COUNT(IF(substring(ordercode,1,8) between  '".$curdate_7."' and '".substr($curdate,0,8)."',1,NULL)) as totordcnt7, ";
																			$sql.= "SUM(IF((substring(ordercode,1,8) between  '".$curdate_7."' and '".substr($curdate,0,8)."' AND deli_gbn IN('Y')),price,0)) as totordprice7, ";
																			//1���ϵ��� �̹�� �Ǽ� �� �̹�۰� �ݾ�
																			$sql.= "COUNT(IF((substring(ordercode,1,8) between '".$curdate_7."' and '".substr($curdate,0,8)."') && (deli_gbn IN('N','X')),1,NULL)) as totdelaycnt7, ";
																			$sql.= "SUM(IF((substring(ordercode,1,8) between '".$curdate_7."' and '".substr($curdate,0,8)."') && (deli_gbn IN('N','X')),price,0)) as totdelayprice7, ";
																			//1���ϵ��� ȯ��/��� �Ǽ�
																			$sql.= "COUNT(IF((substring(ordercode,1,8) between '".$curdate_7."' and '".substr($curdate,0,8)."') && (deli_gbn IN('C','D')),1,NULL)) as totdelayprice7, ";

																			//������ �ֹ��Ǽ� �� �ֹ��ݾ�
																			$sql.= "COUNT(IF(ordercode LIKE CONCAT(REPLACE(left(date_sub(curdate(),interval 1 month), 7),'-',''), '%'),1,NULL)) as totremonordcnt, ";
																			$sql.= "SUM(IF((ordercode LIKE CONCAT(REPLACE(left(date_sub(curdate(),interval 1 month), 7),'-',''), '%') AND deli_gbn IN('Y')),price,0)) as totremonordprice, ";
																			//������ �̹�� �Ǽ� �� �̹�۰� �ݾ�
																			$sql.= "COUNT(IF((ordercode LIKE CONCAT(REPLACE(left(date_sub(curdate(),interval 1 month), 7),'-',''), '%')) && (deli_gbn IN('N','X')),1,NULL)) as totremondelaycnt, ";
																			$sql.= "SUM(IF((ordercode LIKE CONCAT(REPLACE(left(date_sub(curdate(),interval 1 month), 7),'-',''), '%')) && (deli_gbn IN('N','X')),1,0)) as totremondelayprice, ";
																			//������ ȯ��/��� �Ǽ�
																			$sql.= "COUNT(IF((ordercode LIKE CONCAT(REPLACE(left(date_sub(curdate(),interval 1 month), 7),'-',''), '%')) && (deli_gbn IN('C','D')),1,NULL)) as totrecancelmoncnt, ";

																			//�̴� �ֹ��Ǽ� �� ����
																			$sql.= "COUNT(IF(ordercode LIKE '".substr($curdate,0,6)."%',1,NULL)) as totmonordcnt, ";
																			$sql.= "SUM(IF((ordercode LIKE '".substr($curdate,0,6)."%' AND deli_gbn IN('Y')),price,0)) as totmonordprice, ";
																			//�̴� �̹�� �Ǽ� �� �̹�۰� �ݾ�
																			$sql.= "COUNT(IF((ordercode LIKE '".substr($curdate,0,6)."%') && (deli_gbn IN('N','X')),1,NULL)) as totmondelaycnt, ";
																			$sql.= "SUM(IF((ordercode LIKE '".substr($curdate,0,6)."%') && (deli_gbn IN('N','X')),price,0)) as totmondelayprice, ";
																			//�̴� ȯ��/��� �Ǽ�
																			$sql.= "COUNT(IF((ordercode LIKE '".substr($curdate,0,6)."%') && (deli_gbn IN('C','D')),1,NULL)) as totcancelmoncnt ";
																			$sql.= "FROM tblorderinfo WHERE 1=1 ";
																			$sql.=" and ordercode LIKE '".substr($curdate,0,6)."%' or ordercode like '".date('Ym',strtotime('-1 month'))."%' ";

																			$filename="admin.main.order.cache";
																			
																			get_db_cache($sql, $resval, $filename, 0);

																			$row=$resval[0];
																			unset($resval);
																		
																			$totordcnt=(int)$row->totordcnt;			//���� �ֹ��Ǽ�
																			$totordprice=(int)$row->totordprice;		//���� �ֹ��ݾ�
																			$totdelaycnt=(int)$row->totdelaycnt;		//���� �̹�۰Ǽ�
																			$totdelayprice=(int)$row->totdelayprice;	//���� �̹�۱ݾ�
																			$totcancelcnt=(int)$row->totcancelcnt;	//���� ȯ��/��ҰǼ�

																			$totordcnt1=(int)$row->totordcnt1;			//1���� �ֹ��Ǽ�
																			$totordprice1=(int)$row->totordprice1;		//1���� �ֹ��ݾ�
																			$totdelaycnt1=(int)$row->totdelaycnt1;		//1���� �̹�۰Ǽ�
																			$totdelayprice1=(int)$row->totdelayprice1;	//1���� �̹�۱ݾ�
																			$totcancelcnt1=(int)$row->totcancelcnt1;	//1���� ȯ��/��ҰǼ�

																			$totordcnt2=(int)$row->totordcnt2;			//2���� �ֹ��Ǽ�
																			$totordprice2=(int)$row->totordprice2;		//2���� �ֹ��ݾ�
																			$totdelaycnt2=(int)$row->totdelaycnt2;		//2���� �̹�۰Ǽ�
																			$totdelayprice2=(int)$row->totdelayprice2;	//2���� �̹�۱ݾ�
																			$totcancelcnt2=(int)$row->totcancelcnt2;	//2���� ȯ��/��ҰǼ�

																			$totordcnt3=(int)$row->totordcnt3;			//3���� �ֹ��Ǽ�
																			$totordprice3=(int)$row->totordprice3;		//3���� �ֹ��ݾ�
																			$totdelaycnt3=(int)$row->totdelaycnt3;		//3���� �̹�۰Ǽ�
																			$totdelayprice3=(int)$row->totdelayprice3;	//3���� �̹�۱ݾ�

																			$totordcnt4=(int)$row->totordcnt4;			//4���� �ֹ��Ǽ�
																			$totordprice4=(int)$row->totordprice4;		//4���� �ֹ��ݾ�
																			$totdelaycnt4=(int)$row->totdelaycnt4;		//4���� �̹�۰Ǽ�
																			$totdelayprice4=(int)$row->totdelayprice4;	//4���� �̹�۱ݾ�

																			$totordcnt7=(int)$row->totordcnt7;			//1���ϵ��� �ֹ��Ǽ�
																			$totordprice7=(int)$row->totordprice7;		//1���ϵ��� �ֹ��ݾ�
																			$totdelaycnt7=(int)$row->totdelaycnt7;		//1���ϵ��� �̹�۰Ǽ�
																			$totdelayprice7=(int)$row->totdelayprice7;	//1���ϵ��� �̹�۱ݾ�
																			$totcancelcnt7=(int)$row->totcancelcnt7;	//���� �̹�۱ݾ�

																			$totmonordcnt=(int)$row->totmonordcnt;		//�̴��� �ֹ��Ǽ�
																			$totmonordprice=(int)$row->totmonordprice;	//�̴��� ����
																			$totmondelaycnt=(int)$row->totmondelaycnt;	//�̴� �̹�۰Ǽ�
																			$totmondelayprice=(int)$row->totmondelayprice;//�̴� �̹�۱ݾ�
																			$totcancelmoncnt=(int)$row->totcancelmoncnt;	//���� ȯ��/��ҰǼ�

																			$totremonordcnt=(int)$row->totremonordcnt;		//�������� �ֹ��Ǽ�
																			$totremonordprice=(int)$row->totremonordprice;	//�������� ����
																			$totremondelaycnt=(int)$row->totremondelaycnt;	//������ �̹�۰Ǽ�
																			$totremondelayprice=(int)$row->totremondelayprice;//������ �̹�۱ݾ�
																			$totrecancelmoncnt=(int)$row->totrecancelmoncnt;	//���� ȯ��/��ҰǼ�
																			/* �ֹ� ��� �� */
																		?>
																		<!--�ֹ�ó����Ȳ ����-->
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
																				<td background="images/main_state_topbg.jpg" align="left" height="34" class="font_gray5">����</td>
																				<td background="images/main_state_topbg.jpg" align="center"></td>
																				<td background="images/main_state_topbg.jpg" align="center"class="font_gray5"><?=substr($curdate,4,2)."��".substr($curdate,6,2)."��"?><img src="images/icon_today.gif" border="0" hspace="3"></td>
																				<td background="images/main_state_topbg.jpg" align="center"></td>
																				<td  background="images/main_state_topbg.jpg" align="center"class="font_gray5"><?=substr($curdate_1,4,2)."��".substr($curdate_1,6,2)."��"?></td>
																				<td background="images/main_state_topbg.jpg"></td>
																				<td  background="images/main_state_topbg.jpg" align="center"class="font_gray5"><?=substr($curdate_2,4,2)."��".substr($curdate_2,6,2)."��"?></td>
																				<td background="images/main_state_topbg.jpg"></td>
																				<td  background="images/main_state_topbg.jpg" align="center"class="font_gray5">�ֱ�1��</td>
																				<td background="images/main_state_topbg.jpg"></td>
																				<td  background="images/main_state_topbg.jpg" align="center"class="font_gray5">�̹���</td>
																				<td background="images/main_state_topbg.jpg"></td>
																				<td  background="images/main_state_topbg.jpg" align="center"class="font_gray5">������</td>
																			</tr>
																			<tr>
																				<td height="5" colspan="13" background="images/main_state_line.gif"></td>
																			</tr>
																			<tr>
																				<td height="20" align="left" class="font_gray3">����(��)</td>
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
																				<td height="20" align="left" class="font_gray3">�ֹ�(��)</td>
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
																				<td height="20" align="left" class="font_gray3">�̹��(��)</td>
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
																				<td height="20" align="left" class="font_gray3">ȯ��/���(��)</td>
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
																				<td height="20" align="left" class="font_gray3">�Խñ�(��)</td>
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
																				<td height="20" align="left" class="font_gray3">ȸ������(��)</td>
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
																				<td height="20" align="left" class="font_gray3">�湮��(��)</td>
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
																		<!--�ֹ�ó����Ȳ ��-->
																	</td>
																	<td width="10" background="images/main_state_topbg.jpg" align="center" height="34" valign="top" style="padding-top:2px"><IMG SRC="images/main_state_top_line.jpg" ALT=""></td>
																	<td width="200" background="images/main_state_topbg.jpg" height="34" class="font_gray5"><IMG SRC="images/main_icon_memo.gif" WIDTH=16 HEIGHT=16 ALT="" align="absmiddle" hspace="4" >�ֱ� ���ó�� ��Ȳ</span></font></b><A HREF="javascript:parent.topframe.GoMenu(5,'order_list.php');"><img src="images/main_icon_more.gif" border="0" align="absmiddle" hspace=3></a></td>
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
																		<!--�ֱ� ��� ó����Ȳ ����-->
																		<table cellpadding="0" cellspacing="0" width="100%" border="0" style="margin-top:9px;">
																			<?
																			$sql = "SELECT * FROM tblorderinfo ";
																			$sql.= "ORDER BY ordercode DESC LIMIT 7 ";
																			$result=mysql_query($sql,get_db_conn());
																			$arpm=array(
																				"B"=>"<img src=\"images/icon_mu.gif\" style=\"position:relative; top:0.2em;\" alt=\"\" />", //������
																				"V"=>"<img src=\"images/icon_sil.gif\" style=\"position:relative; top:0.2em;\" alt=\"\" />", //�ǽð�������ü
																				"O"=>"<img src=\"images/icon_ga.gif\" style=\"position:relative; top:0.2em;\" alt=\"\" />", //�������
																				"Q"=>"<img src=\"images/icon_mae.gif\" style=\"position:relative; top:0.2em;\" alt=\"\" />", //�Ÿź�ȣ(����ũ��)
																				"C"=>"<img src=\"images/icon_ca.gif\" style=\"position:relative; top:0.2em;\" alt=\"\" />", //�ſ�ī��
																				"P"=>"<img src=\"images/icon_mae.gif\" style=\"position:relative; top:0.2em;\" alt=\"\" />", //�Ÿź�ȣ(����ũ��)
																				"M"=>"<img src=\"images/icon_han.gif\" style=\"position:relative; top:0.2em;\" alt=\"\" />" //�ڵ���
																			);

																			$i=0;
																			while($row = mysql_fetch_object($result)) {
																				$name=$row->sender_name;
																				$date = substr($row->ordercode,4,2).substr($row->ordercode,6,2).substr($row->ordercode,8,2).substr($row->ordercode,10,2);

																				switch($row->deli_gbn) {
																					case 'S': $de_gbn = "<font class=font_blue3>�غ�</font>";  break;
																					case 'X': $de_gbn = "<font class=font_gray6>��û</font>";  break;
																					case 'Y': $de_gbn = "���";  break;
																					case 'D': $de_gbn = "<font color=font_blue3>���</font>";  break;
																					case 'N': $de_gbn = "<font class=font_blue3>��ó��</font>";  break;
																					case 'E': $de_gbn = "<font color=red>ȯ�Ҵ��</font>";  break;
																					case 'C': $de_gbn = "<font color=red>�ֹ����</font>";  break;
																					case 'R': $de_gbn = "�ݼ�";  break;
																					case 'H': $de_gbn = "���(<font color=red>���꺸��</font>)";  break;
																				}
																			?>
																			<tr>
																				<td class="font_blue3a"><A HREF="javascript:OrderDetailView('<?=$row->ordercode?>');">[<?=$name?>]</td>
																				<td class="font_blue3"><?=$de_gbn?></td>
																				<td align="right" class="font_blue3a"><?=number_format($row->price)?>�� <?=$arpm[substr($row->paymethod,0,1)]?></td>
																			</tr>
																			<?
																			$i++;
																			}
																			mysql_free_result($result);
																			if($i==0) {
																				echo "<tr><td align=center >��ϵ� �����Ͱ� �����ϴ�.</td></tr>";
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
																		<img src="images/icon_mu.gif" style="position:relative; top:0.2em;" alt="" /> ������,
																		<img src="images/icon_sil.gif" style="position:relative; top:0.2em;" alt="" /> �ǽð�������ü,
																		<img src="images/icon_ca.gif" style="position:relative; top:0.2em;" alt="" /> �ſ�ī��,
																		<img src="images/icon_ga.gif" style="position:relative; top:0.2em;" alt="" /> �������,
																		<img src="images/icon_mae.gif" style="position:relative; top:0.2em;" alt="" /> �Ÿź�ȣ(����ũ��),
																		<img src="images/icon_han.gif" style="position:relative; top:0.2em;" alt="" /> �ڵ�������
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
<!--#######���� left����#######################################################################################################################--->
															<table cellpadding="0" cellspacing="0" width="100%" border="0">
																	<tr>
																		<td  align="left">
																		<!--1:1,����,�ı� �Խ��� ����-->
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
																						echo "��ϵ� �����Ͱ� �����ϴ�.";
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
																						echo "��ϵ� �����Ͱ� �����ϴ�.";
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
																						echo "��ϵ� �����Ͱ� �����ϴ�.";
																					}
																				?>


																				</td>
																				<td></td>
																			</tr>
																			<tr>
																				<td height="60" colspan="8"></td>
																			</tr>
																		</table>
																		<!--1:1,����,�ı� �Խ��� ��-->
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

																						<!--�ٷΰ���޴�-->
																						<table cellpadding="0" cellspacing="0" >
																							<tr>
																								<td valign="top" height="22"><a href="order_index.php"><IMG SRC="images/main_center_quick_st1.gif"  ALT="" border="0"></a></td>
																								<td class="font_gray3"  valign="top"><a href="order_list.php">�ֹ���ȸ</a><img src="images/main_center_quick_sel.gif" ><a href="order_delay.php">�̹��/���Ա�</a><img src="images/main_center_quick_sel.gif"  ><a href="order_monthsearch.php">�������ֹ���ȸ</a></td>
																							</tr>
																							<tr>
																								<td valign="top" height="22"><a href="member_index.php"><IMG SRC="images/main_center_quick_st2.gif"  ALT="" border="0"></a></td>
																								<td class="font_gray3" valign="top"><a href="member_list.php">ȸ������</a><img src="images/main_center_quick_sel.gif"  ><a href="member_outlist.php">Ż�����</a><img src="images/main_center_quick_sel.gif"  ><a href="member_mailsend.php">���Ϲ߼�</a><img src="images/main_center_quick_sel.gif"  ><a href="market_smssinglesend.php">SMS�߼�</a></td>
																							</tr>
																							<tr>
																								<td valign="top" height="22"><a href="product_code.php"><IMG SRC="images/main_center_quick_st3.gif"  ALT="" border="0"></a></td>
																								<td class="font_gray3" valign="top"><a href="product_register.php">��ǰ���</a><img src="images/main_center_quick_sel.gif"  ><a href="product_price.php">�����ϰ�����</a><img src="images/main_center_quick_sel.gif"  ><a href="product_allsoldout.php">ǰ����ǰ</a><img src="images/main_center_quick_sel.gif"  ><a href="product_allquantity.php">����ϰ�����</a></td>
																							</tr>
																							<tr>
																								<td valign="top" height="22"><IMG SRC="images/main_center_quick_st4.gif"  ALT=""></td>
																								<td class="font_gray3" valign="top"><A HREF="order_list.php">�ֹ�DB���</A><img src="images/main_center_quick_sel.gif"  ><A HREF="member_list.php">ȸ��DB���</A><img src="images/main_center_quick_sel.gif"  ><A HREF="product_exceldownload.php">��ǰDB���</A></td>
																							</tr>
																						</table>
																						<!--�ٷΰ���޴�-->

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
																									<!--���θ޸���-->
																									<table cellpadding="0" cellspacing="0" width="100%">
																										<tr>
																											<td  valign="top">
																												<table cellpadding="1" cellspacing="0" width="100%" border="0">
																													<tr>
																														<td colspan="7" align="center" class="calender_title">(<?=date("Y")?>�� <?=date("m")?>��)</TD>
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
																						<!--���θ޸���-->
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


																					<!-- �ֱٵ�ϻ�ǰ ���� -->
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
																				<!-- �ֱٵ�ϻ�ǰ �� -->


																			</td>
																			<td valign="top"></td>
																			<td valign="top">


																				<!-- �ֱ��ǸŻ�ǰ ���� -->
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
																					<!-- �ֱ��ǸŻ�ǰ �� -->


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
<!--#######���� left����#######################################################################################################################--->

														</td>

<!--######################## ���� 6px #######################################################################################################################--
														<td width="6" valign="top"><img src="images/space01.gif" width="6" height="1" border="0"></td>
<!--#########################################################################################################################################################--

														<td valign="top">
<!--######################## ���� right ��� ���� #########################################################################################################--
															<table cellpadding="0" cellspacing="0" width="210">
																<tr>
																	<td>
																		<!--�������� �����ִ� �ֿ� �����--
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
																										<td><A href="javascript:parent.topframe.GoMenu(1,'shop_snsinfo.php');">SNS�����</a></td>
																									</tr>
																									<tr>
																										<td><IMG SRC="images/main_icon_nero.gif" WIDTH=12 HEIGHT=11 ALT=""></td>
																										<td><A href="javascript:parent.topframe.GoMenu(1,'shop_recommand.php');">��õ�μ���</a></td>
																									</tr>
																									<tr>
																										<td><IMG SRC="images/main_icon_nero.gif" WIDTH=12 HEIGHT=11 ALT=""></td>
																										<td><A href="javascript:parent.topframe.GoMenu(1,'shop_reserve.php');">������/�������뼳��</a></td>
																									</tr>
																									<tr>
																										<td><IMG SRC="images/main_icon_nero.gif" WIDTH=12 HEIGHT=11 ALT=""></td>
																										<td><A href="javascript:parent.topframe.GoMenu(7,'market_couponnew.php');">�����߱ް���</a></td>
																									</tr>
																									<tr>
																										<td><IMG SRC="images/main_icon_nero.gif" WIDTH=12 HEIGHT=11 ALT=""></td>
																										<td><A href="javascript:parent.topframe.GoMenu(4,'product_giftlist.php');">����ǰ����</a></td>
																									</tr>
																									<tr>
																										<td><IMG SRC="images/main_icon_nero.gif" WIDTH=12 HEIGHT=11 ALT=""></td>
																										<td><A href="javascript:parent.topframe.GoMenu(1,'market_cash_reserve.php');">������ȯ��û����</a></td>
																									</tr>
																									<tr>
																										<td><IMG SRC="images/main_icon_nero.gif" WIDTH=12 HEIGHT=11 ALT=""></td>
																										<td><A href="javascript:parent.topframe.GoMenu(1,'product2_register.php');">��ǰ�ǰ���</a></td>
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
																		--�������� �����ִ� �ֿ� �����-->



																	<!--</td>
																</tr>																
																<tr>
																	<td>

																		<!--ȿ������ ȸ������--
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
																										<td><a href="member_list.php">ȸ������Ʈ ����</a></td>
																									</tr>
																									<tr>
																										<td width="16"><IMG SRC="images/main_icon_nero.gif" WIDTH=12 HEIGHT=11 ALT=""></td>
																										<td><a href="member_groupnew.php">ȸ����� ���</a></td>
																									</tr>
																									<tr>
																										<td><IMG SRC="images/main_icon_nero.gif" WIDTH=12 HEIGHT=11 ALT=""></td>
																										<td><a href="member_excelupload.php"><b>ȸ������ �ϰ����</a></b></td>
																									</tr>
																									<tr>
																										<td><IMG SRC="images/main_icon_nero.gif" WIDTH=12 HEIGHT=11 ALT=""></td>
																										<td><a href="member_mailallsend.php">��ü ���� �߼�</a></td>
																									</tr>
																									<tr>
																										<td><IMG SRC="images/main_icon_nero.gif" WIDTH=12 HEIGHT=11 ALT=""></td>
																										<td><a href="market_smsgroupsend.php">��ü sms �߼�</a></td>
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
																		--ȿ������ ȸ������-->

																	<!--</td>
																</tr>
																<tr>
																	<td>

																		<!--������ ������� �̴ϼ�--
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
																										<td><?=(setUseVender()==true?"<a href=\"vender_new.php\">":"<a href=\"javascript:not_vender_alert();\">")?><font color="#666666">������ü ���</font></a></td>
																									</tr>
																									<tr>
																										<td width="16"><IMG SRC="images/main_icon_nero.gif" WIDTH=12 HEIGHT=11 ALT=""></td>
																										<td><?=(setUseVender()==true?"<a href=\"vender_management.php\">":"<a href=\"javascript:not_vender_alert();\">")?><font color="#666666">������ü ����</font></a></td>
																									</tr>
																									<tr>
																										<td><IMG SRC="images/main_icon_nero.gif" WIDTH=12 HEIGHT=11 ALT=""></td>
																										<td><?=(setUseVender()==true?"<a href=\"vender_orderlist.php\">":"<a href=\"javascript:not_vender_alert();\">")?><font color="#666666">������ü �ֹ���ȸ</font></a></td>
																									</tr>
																									<tr>
																										<td><IMG SRC="images/main_icon_nero.gif" WIDTH=12 HEIGHT=11 ALT=""></td>
																										<td><?=(setUseVender()==true?"<a href=\"vender_orderadjust.php\">":"<a href=\"javascript:not_vender_alert();\">")?><font color="#666666">������ü �������</font></a></td>
																									</tr>
																									<tr>
																										<td><IMG SRC="images/main_icon_nero.gif" WIDTH=12 HEIGHT=11 ALT=""></td>
																										<td><a href="/vender/" target="_blank"><font color="#3399cc"><b>�̴ϼ� �α���</b></font></a></td>
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
																		--������ ������� �̴ϼ�-->


																	<!--</td>
																</tr>
															</table>
<!--######################## ���� right��� �� ########################################################################--
														</td>
													</tr>
												</table>
<!--######################## ���� �� ################################################################################################-->

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
/* �����;˶��� �ִ� ��� */
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
/* �����;˶��� �ִ� ��� */
?>
</script>

<div id="create_openwin" style="display:none"></div>

<style> 
/* ���ٴϴ� ��� (Floating Menu) */ 
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
