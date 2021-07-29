<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/venderlib.php");
INCLUDE ("access.php");


$s_date = $_POST["s_date"]? $_POST["s_date"] : date("Ymd");
$e_date = $_POST["e_date"]? $_POST["e_date"] : date("Ymd");
$search_gbn = $_POST["search_gbn"]? $_POST["search_gbn"] : "today";

$search_s=str_replace("-","",$s_date."000000");
$search_e=str_replace("-","",$e_date."235959");

$sql = "SELECT COUNT(*) as cnt ";
$sql.= " FROM tblorderinfo a, tblorderproduct b WHERE b.vender='".$_VenderInfo->getVidx()."' AND a.ordercode=b.ordercode ";
$sql.= " AND (a.ordercode BETWEEN '".$search_s."' AND '".$search_e."') ";
$sql.= " AND NOT (b.productcode LIKE 'COU%' OR b.productcode LIKE '999999%') ";

//주문완료(입금대기)
$sql_1 = $sql." AND a.deli_gbn='N'";
$res_1 = mysql_query($sql_1,get_db_conn());
$row_1 = mysql_fetch_object($res_1);

//결제완료 
$sql_2 = $sql." AND a.deli_gbn='N'";
$res_2 = mysql_query($sql_2,get_db_conn());
$row_2 = mysql_fetch_object($res_2);

//예약확정(배송준비) S
$sql_3 = $sql." AND a.deli_gbn='S'";
$res_3 = mysql_query($sql_3,get_db_conn());
$row_3 = mysql_fetch_object($res_3);

//배송중 Y
$sql_4 = $sql." AND a.deli_gbn='Y' AND a.deli_date<DATE_FORMAT(NOW() + INTERVAL 24 HOUR, '%Y%m%d%H%i')";
$res_4 = mysql_query($sql_4,get_db_conn());
$row_4 = mysql_fetch_object($res_4);

//대여중 Y->하루지나면 대여중으로 변경
$sql_5 = $sql." AND a.deli_gbn='Y' AND a.deli_date>=DATE_FORMAT(NOW() + INTERVAL 24 HOUR, '%Y%m%d%H%i')";
$res_5 = mysql_query($sql_5,get_db_conn());
$row_5 = mysql_fetch_object($res_5);

//취소요청 D
$sql_6 = $sql." AND a.deli_gbn='D'";
$res_6 = mysql_query($sql_6,get_db_conn());
$row_6 = mysql_fetch_object($res_6);

//취소완료 C
$sql_7 = $sql." AND a.deli_gbn='C'";
$res_7 = mysql_query($sql_7,get_db_conn());
$row_7 = mysql_fetch_object($res_7);


$sql = "SELECT count(*) as cnt FROM tblorderproduct WHERE vender='".$_VenderInfo->getVidx()."'";
$sql.= " AND (ordercode BETWEEN '".$search_s."' AND '".$search_e."') ";

//예약불가(재고부족) prd_status=W
$sql_8 = $sql." AND prd_status='W'";
$res_8 = mysql_query($sql_8,get_db_conn());
$row_8 = mysql_fetch_object($res_8);

//예약불가(상품정보상이) prd_status=I
$sql_9 = $sql." AND prd_status='I'";
$res_9 = mysql_query($sql_9,get_db_conn());
$row_9 = mysql_fetch_object($res_9);

//예약불가(상품불량) prd_status=F
$sql_10 = $sql." AND prd_status='F'";
$res_10 = mysql_query($sql_10,get_db_conn());
$row_10 = mysql_fetch_object($res_10);

//발주지연 : 결제완료후 예약확정지연(24시간)
$sql_11 = "SELECT count(*) as cnt ";
$sql_11.= "FROM tblorderinfo a, tblorderproduct b WHERE b.vender='".$_VenderInfo->getVidx()."' AND a.ordercode=b.ordercode ";
$sql_11.= " AND NOT (b.productcode LIKE 'COU%' OR b.productcode LIKE '999999%') ";
$sql_11.= " AND a.deli_gbn='N' AND ((a.paymethod='B' AND a.bank_date >= DATE_FORMAT(NOW() + INTERVAL 24 HOUR, '%Y%m%d%H%i')) ";
$sql_11.= " OR a.paymethod<>'B' AND left(a.ordercode,14) >= DATE_FORMAT(NOW() + INTERVAL 24 HOUR, '%Y%m%d%H%i'))";
$res_11 = mysql_query($sql_11,get_db_conn());
$row_11 = mysql_fetch_object($res_11);

//발송지연 : 예약확정후 발송지연(24시간)
$sql_12 = "SELECT count(*) as cnt FROM tblorderproduct WHERE vender='".$_VenderInfo->getVidx()."'";
$sql_12.= " AND NOT (productcode LIKE 'COU%' OR productcode LIKE '999999%') ";
$sql_12.= " AND deli_gbn='S' AND deli_date >= DATE_FORMAT(NOW() + INTERVAL 24 HOUR, '%Y%m%d%H%i')";
$res_12 = mysql_query($sql_12,get_db_conn());
$row_12 = mysql_fetch_object($res_12);

//취소지연 : 취소신청후 처리지연(24시간)
$sql_13 = "SELECT count(*) as cnt FROM tblorderproduct WHERE vender='".$_VenderInfo->getVidx()."'";
$sql_13.= " AND NOT (productcode LIKE 'COU%' OR productcode LIKE '999999%') ";
$sql_13.= " AND deli_gbn='D' AND cancel_date >= DATE_FORMAT(NOW() + INTERVAL 24 HOUR, '%Y%m%d%H%i')";
$res_13 = mysql_query($sql_13,get_db_conn());
$row_13 = mysql_fetch_object($res_13);

//마스터알람
$masterAlarm = 0;

/* 위탁상품 등록신청이 있는 경우 */
$sql = "SELECT productcode FROM tblproduct p inner join rent_product rp ON p.pridx=rp.pridx  ";
$sql.= "WHERE rp.istrust='0' AND rp.trust_vender='".$_VenderInfo->getVidx()."' AND rp.trust_approve='N'";

//$sql = "SELECT pridx FROM rent_product  ";
//$sql.= "WHERE istrust='0' AND trust_vender='".$_VenderInfo->getVidx()."' AND trust_approve='N'";
$result=mysql_query($sql,get_db_conn());
$_trust_data=mysql_fetch_object($result);
mysql_free_result($result);

if($_trust_data->productcode>0){
	$masterAlarm++;
}
/* 위탁상품 등록신청이 있는 경우 */

/* 보낸업체인 경우 위탁상품 등록신청후 승인여부확인 */
$sql = "SELECT productcode FROM tblproduct p inner join rent_product rp ON p.pridx=rp.pridx  ";
$sql.= "WHERE rp.istrust='0' AND p.vender='".$_VenderInfo->getVidx()."' AND rp.trust_approve<>'N'";
$result=mysql_query($sql,get_db_conn());
$_trust_data=mysql_fetch_object($result);
mysql_free_result($result);

if($_trust_data->productcode>0){
	$masterAlarm++;
}
/* 보낸업체인 경우 위탁상품 등록신청후 승인여부확인 */

/* 위탁상품 수수료 변경신청이 있는 경우 */
$sql = "SELECT productcode FROM tbltrustcommission ";
$sql.= "WHERE (trust_vender='".$_VenderInfo->getVidx()."' OR vender='".$_VenderInfo->getVidx()."') ";
$sql.= "AND modify_vender<>'".$_VenderInfo->getVidx()."' ";
$sql.= "AND status='1'";

$result=mysql_query($sql,get_db_conn());
$_commi_data=mysql_fetch_object($result);
mysql_free_result($result);

if($_commi_data->productcode>0){
	$masterAlarm++;
}
/* 위탁상품 수수료 변경신청이 있는 경우 */

/* 위탁상품 노출중지 및 위탁관리 계약철회 */
$sql = "SELECT tc_idx FROM tbltrustcancel tc LEFT JOIN tbltrustagree ta ON tc.ta_idx=ta.ta_idx ";
$sql.= "WHERE tc.cancel_agree='Y' AND tc.status='3' AND ta.give_vender='".$_VenderInfo->getVidx()."'";
$result=mysql_query($sql,get_db_conn());
$_cancel_data=mysql_fetch_object($result);
mysql_free_result($result);

if($_cancel_data->tc_idx>0){
	$masterAlarm++;
}
/* 위탁상품 노출중지 및 위탁관리 계약철회 */


/*위탁업체 등록 :: 아직 확인안했을때 알림(approve_check='N')*/
$sql = "SELECT tm_idx FROM tbltrustmanage WHERE vender='".$_VenderInfo->getVidx()."' AND approve<>'N' AND approve_check='N'";
$result=mysql_query($sql,get_db_conn());
$_tm_data=mysql_fetch_object($result);
mysql_free_result($result);

if($_tm_data->tm_idx>0){
	$masterAlarm++;
}
/*위탁업체 등록*/

/*위탁계약신청 승인및 취소된 경우 :: 아직 확인안했을때 알림(approve_check='N')*/
$sql = "SELECT ta_idx FROM tbltrustagree WHERE give_vender='".$_VenderInfo->getVidx()."' AND approve<>'N' AND approve_check='N'";
$result=mysql_query($sql,get_db_conn());
$_ta_data=mysql_fetch_object($result);
mysql_free_result($result);

if($_ta_data->ta_idx>0){
	$masterAlarm++;
}
/*위탁계약신청 승인및 취소된 경우*/


?>


<? INCLUDE "header.php"; ?>

<script type="text/javascript" src="lib.js.php"></script>
<link href="/js/jquery-ui-1.11.4/jquery-ui.css" rel="stylesheet">
<script src="/js/jquery-ui-1.11.4/external/jquery/jquery.js"></script>
<script src="/js/jquery-ui-1.11.4/jquery-ui.js"></script>

<script type="text/javascript">var $j= jQuery.noConflict();</script>
<script type="text/javascript" src="/lib/lib.js.php"></script>
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
	$j('alarmdiv').setStyle('display','none');
	MasterAlarm.view();
}

function GoPrdinfo(prcode,target) {
	document.form3.target="";
	document.form3.prcode.value=prcode;
	if(target.length>0) {
		document.form3.target=target;
	}
	document.form3.submit();
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
		var path="master_alarm.xml.php?vender=<?=$_VenderInfo->getVidx()?>";
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
//-->
</script>

<script language="JavaScript">
function GoNoticeView(artid) {
	url="shop_notice.php?type=view&artid="+artid;
	document.location.href=url;
}
function GoCounselView(artid) {
	url="shop_counsel.php?type=view&artid="+artid;
	document.location.href=url;
}

var NowYear=parseInt(<?=date('Y')?>);
var NowMonth=parseInt(<?=date('m')?>);
var NowDay=parseInt(<?=date('d')?>);
function getMonthDays(sYear,sMonth) {
	var Months_day = new Array(0,31,28,31,30,31,30,31,31,30,31,30,31)
	var intThisYear = new Number(), intThisMonth = new Number();
	datToday = new Date();													// 현재 날자 설정

	intThisYear = parseInt(sYear);
	intThisMonth = parseInt(sMonth);

	if (intThisYear == 0) intThisYear = datToday.getFullYear();				// 값이 없을 경우
	if (intThisMonth == 0) intThisMonth = parseInt(datToday.getMonth())+1;	// 월 값은 실제값 보다 -1 한 값이 돼돌려 진다.


	if ((intThisYear % 4)==0) {													// 4년마다 1번이면 (사로나누어 떨어지면)
		if ((intThisYear % 100) == 0) {
			if ((intThisYear % 400) == 0) {
				Months_day[2] = 29;
			}
		} else {
			Months_day[2] = 29;
		}
	}
	intLastDay = Months_day[intThisMonth];										// 마지막 일자 구함
	return intLastDay;
}

function GoSearch(gbn) {
	switch(gbn) {
		case "TODAY":
			s_date = new Date(parseInt(NowYear), parseInt(NowMonth), parseInt(NowDay));
			break;
		case "7DAY":
			s_date = new Date(parseInt(NowYear), parseInt(NowMonth), parseInt(NowDay)-7);
			break;
		case "TOMONTH":
			s_date = new Date(parseInt(NowYear), parseInt(NowMonth), parseInt(1));
			break;
		case "PREMONTH":
			s_date = new Date(parseInt(NowYear), parseInt(NowMonth)-1, parseInt(1));
			break;
		default :
			s_date = new Date(parseInt(NowYear), parseInt(NowMonth), parseInt(NowDay));
			break;
	}

	s_year=parseInt((s_date.getMonth() < 1 ? s_date.getFullYear()-1 : s_date.getFullYear() ));
	s_month=parseInt((s_date.getMonth() < 1 ? 12 : s_date.getMonth() ));
	s_day=s_date.getDate();
	//e_year=NowYear;
	e_year=parseInt((s_date.getMonth() < 1 ? s_date.getFullYear()-1 : s_date.getFullYear() ));
	e_month=(gbn=="PREMONTH")? s_month : NowMonth;
	e_day=(gbn=="PREMONTH")? getMonthDays(s_year,s_month) : NowDay;

	s_month = s_month<10? "0"+s_month: s_month;
	s_day = s_day<10? "0"+s_day: s_day;

	e_month = e_month<10? "0"+e_month: e_month;
	e_day = e_day<10? "0"+e_day: e_day;

	document.form1.s_date.value = s_year+""+s_month+""+s_day;
	document.form1.e_date.value = e_year+""+e_month+""+e_day;
	document.form1.search_gbn.value=gbn.toLowerCase();
	document.form1.submit();
}
</script>






<table border=0 cellpadding=0 cellspacing=0 width=100% height="100%" style="table-layout:fixed">
<col width=190></col>
<col width=20></col>
<col width=></col>
<col width=20></col>
<tr>
	<td width=190 valign=top nowrap background="images/minishop_leftbg.gif"><? include ("menu.php"); ?></td>
	<td width=20 nowrap></td>
	<td valign=top>

	<table width="100%"  border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td>
		<table width="100%"  border="0" cellpadding="0" cellspacing="0"  bgcolor="#ffffff">
		<tr>
			<td style="padding-top:30px">

			<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
			<col width=></col>
			<col width=10></col>
			<col width=220></col>
			<tr>
				<td valign=top>
				<!-- 중앙 내용 시작 -->

					<section>
						<article class="search">
							<form name="form1" method="post" action="<?=$PHP_SELF?>">
							<input type="hidden" name="search_gbn" value="<?=$search_gbn?>">
							<input type="text" name="s_date" id="s_date" value="<?=$s_date?>"> ~
							<input type="text" name="e_date" id="e_date" value="<?=$e_date?>">
							<script type="text/javascript">
								$j( "#s_date" ).datepicker({dateFormat:"yymmdd"});
								$j( "#e_date" ).datepicker({dateFormat:"yymmdd"});

								$j( "#e_date" ).change(function(){   
									document.form1.submit();
								}); 
							</script>
							<button type="button" class="btn_day <?=($search_gbn=="today")? "btn_on":""; ?> " id="today" onclick="javascript:GoSearch('TODAY')">오늘</button>
							<button type="button" class="btn_day <?=($search_gbn=="7day")? "btn_on":""; ?>" id="7day"  onclick="javascript:GoSearch('7DAY')">7일</button>
							<button type="button" class="btn_day <?=($search_gbn=="tomonth")? "btn_on":""; ?>" id="tomonth" onclick="javascript:GoSearch('TOMONTH')">이번달</button>
							<button type="button" class="btn_day <?=($search_gbn=="premonth")? "btn_on":""; ?>" id="premonth" onclick="javascript:GoSearch('PREMONTH')">지난달</button>
							</form>
						</article>
						<article class="status">
							<div class="status_list">
								<h4><img src="<?=$Dir?>images/icon_dot.gif" border=0 align=absmiddle> 처리지연현황</h4>
								<ul>
									<li><img src="images/icon_dot01.gif"> 발주지연 : <span class="orange"><?=$row_11->cnt?></span> 건</li>
									<li><img src="images/icon_dot01.gif"> 발송지연 : <span class="orange"><?=$row_12->cnt?></span> 건</li>
									<li><img src="images/icon_dot01.gif"> 취소지연 : <span class="orange"><?=$row_13->cnt?></span> 건</li>
								</ul>
							</div>
							<div class="status_list">
								<h4><img src="<?=$Dir?>images/icon_dot.gif" border=0 align=absmiddle> 판매현황</h4>
								<ul>
									<li><img src="images/icon_dot01.gif"> 주문완료(입금대기) : <?=$row_1->cnt?> 건</li>
									<li><img src="images/icon_dot01.gif"> 결제완료(체크요청) : <span class="orange"><?=$row_2->cnt?></span> 건</li>
									<li><img src="images/icon_dot01.gif"> 예약확정(배송준비) : <span class="orange"><?=$row_3->cnt?></span> 건</li>
									<li><img src="images/icon_dot01.gif"> 배송중 : <?=$row_4->cnt?> 건</li>
									<li><img src="images/icon_dot01.gif"> 대여중 : <?=$row_5->cnt?> 건</li>
									<li><img src="images/icon_dot01.gif"> 대여완료(반납대기) : <span class="orange"><?=bookingCount($s_date,$e_date,'BE')?></span> 건</li>
									<li><img src="images/icon_dot01.gif"> 반납완료 : <?=bookingCount($s_date,$e_date,'CE')?> 건</li>
									<li><img src="images/icon_dot01.gif"> 반납불가(파손/미반납) : <?=bookingCount($s_date,$e_date,'NR')?> 건</li>
								</ul>
							</div>
							<div class="status_list">
								<h4><img src="<?=$Dir?>images/icon_dot.gif" border=0 align=absmiddle> 취소현황</h4>
								<ul>
									<li><img src="images/icon_dot01.gif"> 취소요청(환불/철회) : <span class="orange"><?=$row_6->cnt?></span> 건</li>
									<li>&nbsp;</li>
									<li><img src="images/icon_dot01.gif"> 예약불가(재고부족) : <?=$row_8->cnt?> 건</li>
									<li><img src="images/icon_dot01.gif"> 예약불가(상품정보상이) : <?=$row_9->cnt?> 건</li>
									<li><img src="images/icon_dot01.gif"> 예약불가(상품불량) : <?=$row_10->cnt?> 건</li>
									<li>&nbsp;</li>
									<li><img src="images/icon_dot01.gif"> 취소완료 : <?=$row_7->cnt?> 건</li>
								</ul>
							</div>
						</article>

<?
						//받은위탁정보가져오기
						$sql = "SELECT count(ta_idx) as cnt FROM tbltrustagree ";
						$sql.= "WHERE take_vender='".$_VenderInfo->getVidx()."' ";
						$sql.= "AND approve='N'";
						$result=mysql_query($sql,get_db_conn());
						$data=mysql_fetch_object($result);

?>
						<article class="trust">
							<h4 class="trustTitle"><img src="<?=$Dir?>images/icon_dot.gif" border=0 align=absmiddle> 위탁계약관리</h4>
							<div class="trustDesc"><? if($data->cnt>0){ ?>확인하지 않은 위탁관리 요청이 있습니다.<? } ?></div>
							<div class="txtRight"><a href="trust_list.php">+ 자세히보기</a></div>
						</article>

						<article class="notice">
							<h4 class="bbsTitle"><img src="<?=$Dir?>images/icon_dot.gif" border=0 align=absmiddle> 공지사항&업그레이드</h4>
							<div class="txtRight"><A HREF="shop_notice.php"><img src=images/btn_readall.gif border=0></A></div>
							<ul>
<?
								$sql = "SELECT date,subject,access FROM tblvenderadminnotice ";
								$sql.= "WHERE (vender='".$_VenderInfo->getVidx()."' OR vender='0') ";
								$sql.= "ORDER BY date DESC LIMIT 5";
								$result=mysql_query($sql,get_db_conn());
								$i=0;
								while($row=mysql_fetch_object($result)) {
									$date=substr($row->date,4,2)."/".substr($row->date,6,2);
									echo "<li>";
									echo "<A HREF=\"javascript:GoNoticeView('".$row->date."')\"><font class='verdana' style='font-size:8pt'>[".$date."]</font> ".strip_tags($row->subject)."</A>\n";
									echo "</li>\n";
									$i++;
								}
								mysql_free_result($result);
?>
							</ul>
						</article>

						<article class="qna">
							<h4 class="bbsTitle"><img src="<?=$Dir?>images/icon_dot.gif" border=0 align=absmiddle> e스토어 판매자 1:1 문의</h4>
							<div class="txtRight">
								<A HREF="shop_counsel.php?type=write"><img src=images/btn_qnawrite.gif border=0></A>
								<A HREF="shop_counsel.php"><img src=images/btn_readall.gif border=0></A>
							</div>
							<ul>
<?
								$sql = "SELECT date,subject,access,re_date FROM tblvenderadminqna ";
								$sql.= "WHERE vender='".$_VenderInfo->getVidx()."' ";
								$sql.= "ORDER BY date DESC LIMIT 5";
								$result=mysql_query($sql,get_db_conn());
								$i=0;
								while($row=mysql_fetch_object($result)) {
									$date=substr($row->date,4,2)."/".substr($row->date,6,2);
									$re_icn="";
									if(strlen($row->re_date)==14) {
										$re_icn="<img src=images/icn_counsel_ok.gif border=0 class='re_icon'>";
									} else {
										$re_icn="<img src=images/icn_counsel_no.gif border=0 class='re_icon'>";
									}
									echo "<li>\n";
									echo "	<A HREF=\"javascript:GoCounselView('".$row->date."')\"><font class='verdana' style='font-size:8pt'>[".$date."]</font> ".strip_tags($row->subject)."</A>";
									echo "	".$re_icn;
									echo "</li>\n";
									$i++;
								}
								mysql_free_result($result);
?>
							</ul>			
						</article>

					</section>



				<!-- 중앙 내용 끝 -->
				</td>

				<td></td>

				<td valign=top>
				<table width="100%" border="0" cellpadding="0" cellspacing="0" style="table-layout:fixed;border:1px solid #EEEEEE" bgcolor="#ffffff">
				<tr>
					<td style="padding:3" valign=top>
					<!-- 오른쪽 내용 시작 -->
<?
					$sql = "SELECT * FROM tblvenderstorecount WHERE vender='".$_VenderInfo->getVidx()."' ";
					$result=mysql_query($sql,get_db_conn());
					$row=mysql_fetch_object($result);
					mysql_free_result($result);
					$prdt_allcnt=$row->prdt_allcnt;
					$prdt_cnt=$row->prdt_cnt;
					$cust_cnt=$row->cust_cnt;
					$count_total=$row->count_total;
					$count_today=0;

					$period_0 = date("Ymd");
					$period_1 = date("Ymd",time()-(60*60*24*1));
					$period_2 = date("Ymd",time()-(60*60*24*2));
					$period_3 = date("Ymd",time()-(60*60*24*3));
					$period_4 = date("Ymd",time()-(60*60*24*4));
					$period_5 = date("Ymd",time()-(60*60*24*5));
					$period_6 = date("Ymd",time()-(60*60*24*6));
					$period_7 = date("Ymd",time()-(60*60*24*7));
					$visit[$period_1]=0;
					$visit[$period_2]=0;
					$visit[$period_3]=0;
					$visit[$period_4]=0;
					$visit[$period_5]=0;
					$visit[$period_6]=0;
					$visit[$period_7]=0;
					$sql = "SELECT date,cnt FROM tblvenderstorevisit ";
					$sql.= "WHERE vender='".$_VenderInfo->getVidx()."' ";
					$sql.= "AND date<='".$period_0."' AND date >='".$period_7."' ";
					$result=mysql_query($sql,get_db_conn());
					$sumvisit=0;
					while($row=mysql_fetch_object($result)) {
						if($row->date==$period_0) {
							$count_today=$row->cnt;
						} else {
							$sumvisit=$sumvisit+$row->cnt;
							$visit[$row->date]=$row->cnt;
						}
					}
					mysql_free_result($result);
?>
					<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
					<tr>
						<td bgcolor=#F2F2F6 style="padding:7">
						<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
						<tr><td height=7></td></tr>
						<tr>
							<td><img src=images/icon_dot07.gif border=0 width=5 height=13 align=absmiddle> <b>내 판매상품 현황</b></td>
						</tr>
						<tr><td height=5></td></tr>
						<tr>
							<td bgcolor=#FFFFFF style="padding:10,15;border:1px solid #E7E7E7">
							<img src=images/icon_dot06.gif border=0 align=absmiddle> 상품등록 제한<?=($_venderdata->product_max>0?"<font class=verdana style=\"font-size:8pt\"><B>".$_venderdata->product_max."</B></font> 개":"<B>무제한</B>")?>
							<br><img width=0 height=3></br>
							<img src=images/icon_dot06.gif border=0 align=absmiddle> 등록 상품(판매중)<font class=verdana style="font-size:8pt"><B><?=$prdt_allcnt?></B></font> 개
							<br><img width=0 height=3></br>
							<img src=images/icon_dot06.gif border=0 align=absmiddle> <font color=#737373>진열중/진열안함</font><font class=verdana style="font-size:8pt"><B><?=$prdt_cnt?></B>개/<font class=verdana style="font-size:8pt"><B><?=$prdt_allcnt?></B>개
							</td>
						</tr>
						<tr><td height=20></td></tr>
						<tr>
							<td><img src=images/icon_dot07.gif border=0 width=5 height=13 align=absmiddle> <b>내 미니샵 방문현황</b></td>
						</tr>
						<tr><td height=5></td></tr>
						<tr>
							<td bgcolor=#FFFFFF style="padding:10,15;border:1px solid #E7E7E7">
							<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
							<col width=35></col>
							<col width=></col>
							<col width=40></col>
<?
							$MAX_barsize=88;
							while(list($key,$val)=each($visit)) {
								echo "<tr height=17>\n";
								echo "	<td style=\"font-size:8pt;color:737373\">".substr($key,4,2)."/".substr($key,6,2)."</td>\n";
								echo "	<td style=\"font-size:8pt;color:737373\">";
								if($val>0) {
									echo "<img src=\"images/icon_dot07.gif\" width=".@round(($val / $sumvisit)*$MAX_barsize)." height=3 align=absmiddle>";
								}
								echo "	</td>\n";
								echo "	<td align=right style=\"font-size:8pt;color:737373\">".number_format($val)."명</td>\n";
								echo "</tr>\n";
							}
?>
							<tr><td colspan=3 height=5></td></tr>
							<tr><td colspan=3 height=1 background=images/bg_storeInfo.gif></td></tr>
							<tr><td colspan=3 height=5></td></tr>
							<tr>
								<td colspan=3>
								오늘/전체<font class=verdana style="font-size:8pt"><B><?=$count_today?></B>명/<font class=verdana style="font-size:8pt"><B><?=$count_total?></B>명
								</td>
							</tr>
							</table>
							</td>
						</tr>
						</table>
						</td>
					</tr>
					<tr><td height=5></td></tr>

					<tr>
						<td valign=top bgcolor=#FEFCDA style="padding:7">
						<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
						<tr><td height=7></td></tr>
						<tr>
							<td><img src=images/icon_dot07.gif border=0 width=5 height=13 align=absmiddle> <b>주요기능 바로가기</b></td>
						</tr>
						<tr><td height=5></td></tr>
						<tr>
							<td bgcolor=#FFFFFF >
							<table border=0 cellpadding=0 cellspacing=0 width=100%>
								<tr>
									<td bgcolor=#FFFFFF style="padding:10,15;border:1px solid #E7E7E7">
									<img src=images/icon_dot06.gif border=0 align=absmiddle> <A HREF="delivery_info.php">배송관련기능설정</A>
									<br><img width=0 height=3></br>
									<img src=images/icon_dot06.gif border=0 align=absmiddle> <A HREF="minishop_design.php">디자인관리</A>
									<br><img width=0 height=3></br>
									<img src=images/icon_dot06.gif border=0 align=absmiddle> <A HREF="main_design.php">메인화면관리</A>
									<br><img width=0 height=3></br>
									<img src=images/icon_dot06.gif border=0 align=absmiddle> <A HREF="minishop_notice.php">미니샵공지사항</A>
									<br><img width=0 height=3></br>
									<img src=images/icon_dot06.gif border=0 align=absmiddle> <A HREF="product_register.php"><b>상품등록</b></A>
									<br><img width=0 height=3></br>
									<img src=images/icon_dot06.gif border=0 align=absmiddle> <A HREF="product_myprd.php"><b>상품관리</b></A>
									<br><img width=0 height=3></br>
									<img src=images/icon_dot06.gif border=0 align=absmiddle> <A HREF="order_list.php"><b>주문조회</b></A
									><br><img width=0 height=3></br>
									<img src=images/icon_dot06.gif border=0 align=absmiddle> <A HREF="sellstat_list.php"><b>정산조회</b></A>
									<br><img width=0 height=3></br>
									<img src=images/icon_dot06.gif border=0 align=absmiddle> <A HREF="sellstat_sale.php"><b>매출분석</b></A>
									<br><img width=0 height=3></br>
									<img src=images/icon_dot06.gif border=0 align=absmiddle> <A HREF="coupon_list.php">쿠폰관리</A>
									</td>
							</tr>

							</table>
							</td>
						</tr>
						</table>
						</td>
						</tr>


					</table>
					<!-- 오른쪽 내용 끝 -->
					</td>
				</tr>
				</table>
				</td>
			</tr>
			</table>

			</td>
		</tr>
		</table>
		</td>
	</tr>
	</table>

	</td>
</tr>
</table>

<form name=form3 method=post action="product_prdmodify.php">
<input type=hidden name=prcode>
</form>

<?=$onload?>

<script language="JavaScript" Event="onLoad" For="window">
<?
/* 마스터알람이 있는 경우 */
if($masterAlarm>0){
?>
	var alarmCookie=getCookie("alarm"); 
	if (alarmCookie != "no") {
		MasterAlarm.view();
	}else{
		$j('alarmdiv').setStyle('display','block');
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

<? INCLUDE "copyright.php"; ?>