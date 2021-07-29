<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");

INCLUDE ("access.php");

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

?>
<HTML>
<HEAD>
<meta>
<title>관리자 페이지</title>
<META http-equiv="CONTENT-TYPE" content="text/html; charset=UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="robots" content="index,nofollow">

<META http-equiv="X-UA-Compatible" content="IE=edge" />
<!--[if lt lE 9]>
<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js">
</script>
<![endif]-->
<link rel="stylesheet" href="style.css">
<link href="/js/jquery-ui-1.11.4/jquery-ui.css" rel="stylesheet">
<script src="/js/jquery-ui-1.11.4/external/jquery/jquery.js"></script>
<script src="/js/jquery-ui-1.11.4/jquery-ui.js"></script>
<script type="text/javascript">var $j= jQuery.noConflict();</script>
<script type="text/javascript" src="<?=$Dir?>lib/lib.js.php"></script>

<script language="JavaScript" type="text/javascript">

$(function(){
  $(window).scroll(function(){
	var scr = document.body.scrollTop || document.documentElement.scrollTop;
    $("#alarmdiv").stop().animate({bottom:-(scr-40)},100);
  });
	$("#alarmdiv").click(function(){ $("html,body").animate({scrollTop:0}, 100); });
});

function alarmView(){
	$('alarmdiv').setStyle('display','none');
	MasterAlarm.view();
}

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


//-->
</SCRIPT>
<body>

<script language="JavaScript" Event="onLoad" For="window">
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

</body>
</html>