<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/shopdata.php");

//본인확인 결과 수신
//_pr($_POST);

$req_result		= $_POST['req_result']; //실명인증 처리결과(Y:승인, N:거부)
$req_name		= $_POST['req_name']; //실명인증 이름
$req_sex			= $_POST['req_sex']; //실명인증 성별(M:남성,W:여성)
$req_birYMD	= $_POST['req_birYMD']; //실명인증 생년월일
$req_cellNo		= $_POST['req_cellNo']; //실명인증 휴대폰 번호


/* 본인확인 서비스 */
/**************************************************************************************/
/* - 결과값 복호화를 위해 IV 값을 Random하게 생성함.(반드시 필요함!!)				*/
/* - input박스 reqNum의 value값을  echo $CurTime.$RandNo  형태로 지정		*/
/**************************************************************************************/
$CurTime = date(YmdHis);  //현재 시각 구하기

//6자리 랜덤값 생성
$RandNo = rand(100000, 999999);

$srvid = "SRNN001";
$srvNo = "001002";
//$reqNum = $CurTime.$RandNo;
$reqNum="0000000000000000"; //인증 안되서 고정값으로 처리(result 에도 동일하게 변경 필요)
$certDate = $CurTime;
$certGb = "H";
$addVar = "";
$retUrl = "32http://beta.jamkan.com/Siren24_v2/pcc_V3_popup_seed2_v2.php";
$exVar = "0000000000000000"; // 확장임시 필드입니다. 수정하지 마세요..

//02. 암호화 파라미터 생성
$reqInfo = $srvid . "^" . $srvNo . "^" . $reqNum . "^" . $certDate . "^" . $certGb . "^" . $addVar . "^" . $exVar;

$key = "3ECA075F0D94C1E583DC5A0968FD6F97";

syslog(LOG_NOTICE, $key);
//03. 본인확인 요청정보 1차암호화
//2014.02.07 KISA 권고사항
//위 변조 및, 불법 시도 차단을 위하여 아래 패턴에 해당하는 문자열만 허용	
if(preg_match('~[^0-9a-zA-Z+/=^]~', $reqInfo, $matches)){
	echo "입력 값 확인이 필요합니다.(req)"; exit;
}

//암호화모듈 설치시 생성된 SciSecuX 파일이 있는 리눅스 경로를 설정해주세요.
$enc_reqInfo = exec("/home/rental/public_html/Siren24_v2/SciSecuX SEED 1 2 $reqInfo $key");	//(ex: /home/name1/php_v2/SciSecuX)

//04. 요청정보 위변조검증값 생성
$hmac_str = exec("/home/rental/public_html/Siren24_v2/SciSecuX HMAC 1 2 $enc_reqInfo $key");

//05. 요청정보 2차암호화
//데이터 생성 규칙 : "요청정보 1차 암호화^위변조검증값^암복화 확장 변수"
$enc_reqInfo = $enc_reqInfo. "^" .$hmac_str. "^" ."0000000000000000";
$enc_reqInfo = exec("/home/rental/public_html/Siren24_v2/SciSecuX SEED 1 2 $enc_reqInfo $key");

$enc_reqInfo = $enc_reqInfo. "^" .$srvid. "^" ."00000000";
$enc_reqInfo = exec("/home/rental/public_html/Siren24_v2/SciSecuX SEED 1 1 $enc_reqInfo $key");
/* 본인확인 서비스 */

$bizno1=trim($_POST["bizno1"]);
$bizno2=trim($_POST["bizno2"]);
$bizno3=trim($_POST["bizno3"]);
$bizno = $bizno1."-".$bizno2."-".$bizno3;

$rsql = "SELECT id FROM tblmember WHERE bizno='".$bizno."'";
$result2 = mysql_query($rsql,get_db_conn());
$num = mysql_num_rows($result2);
mysql_free_result($result2);
if ($num>0) {
	echo "<script>alert('사업자등록번호가 중복되었습니다.');location.href='businessLicense_check.php';</script>";
	exit;
}
?>
<HTML>
<HEAD>
<TITLE><?=$_data->shoptitle?>사업자 회원 가입</TITLE>
<META http-equiv="CONTENT-TYPE" content="text/html; charset=EUC-KR">
<META name="description" content="<?=(strlen($_data->shopdescription)>0?$_data->shopdescription:$_data->shoptitle)?>">
<META name="keywords" content="<?=$_data->shopkeyword?>">
<script type="text/javascript" src="<?=$Dir?>lib/lib.js.php"></script>
<?include($Dir."lib/style.php")?>
<? /*<link rel="stylesheet" type="text/css" href="/css/b2b_style.css" />*/ ?>
<link rel="stylesheet" type="text/css" href="/css/common.css" />
<link rel="stylesheet" type="text/css" href="/css/jamkan.css" />

<SCRIPT LANGUAGE="JavaScript">
<!--
function CheckFormData(data) {
	var numstr = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
	var thischar;
	var count = 0;
	data = data.toUpperCase( data )

	for ( var i=0; i < data.length; i++ ) {
		thischar = data.substring(i, i+1 );
		if ( numstr.indexOf( thischar ) != -1 )
			count++;
	}
	if ( count == data.length )
		return(true);
	else
		return(false);
}

function CheckForm() {
	form=document.form1;

	if(form.id.value.length==0) {
		alert("아이디를 입력하세요."); form.id.focus(); return;
	}
	if(form.id.value.length<4 || form.id.value.length>12) {
		alert("아이디는 4자 이상 12자 이하로 입력하셔야 합니다."); form.id.focus(); return;
	}
	if (CheckFormData(form.id.value)==false) {
		alert("ID는 영문, 숫자를 조합하여 4~12자 이내로 등록이 가능합니다."); form.id.focus(); return;
	}
	if(form.passwd1.value.length==0) {
		alert("비밀번호를 입력하세요."); form.passwd1.focus(); return;
	}
	if(form.passwd1.value!=form.passwd2.value) {
		alert("비밀번호가 일치하지 않습니다."); form.passwd2.focus(); return;
	}

	if(form.result.value.length==0) {
		alert("본인인증을 진행하세요."); form.mobile.focus(); return;
	}

	var naverchk=document.form1.email.value;
	if(naverchk.indexOf("naver") != -1){
		//네이버는 인증 불필요
	}else{
		/*
		if(document.form1.cert_value.value != "000"){
			alert("이메일을 인증해 주세요.");
			return;
		}
		*/
	}

	if(form.mobile.value.length==0) {
		alert("휴대전화를 입력하세요."); form.mobile.focus(); return;
	}

	if(form.email.value.length==0) {
		alert("이메일을 입력하세요."); form.email.focus(); return;
	}
	if(!IsMailCheck(form.email.value)) {
		alert("이메일 형식이 맞지않습니다.\n\n확인하신 후 다시 입력하세요."); form.email.focus(); return;
	}

	if(form.idChk.value=="0") {
		alert("아이디 중복 체크를 하셔야 합니다!");
		idcheck();
		return;
	}

	/*
	if(form.mailChk.value=="0") {
		alert("이메일 중복 체크를 하셔야 합니다!");
		mailcheck();
		return;
	}
	*/

<?if($_data->ssl_type=="Y" && strlen($_data->ssl_domain)>0 && strlen($_data->ssl_port)>0 && $_data->ssl_pagelist["MJOIN"]=="Y") {?>
		form.action='https://<?=$_data->ssl_domain?><?=($_data->ssl_port!="443"?":".$_data->ssl_port:"")?>/<?=RootPath.SecureDir?>member_join.php';
<?}?>
	if(confirm("회원가입을 하겠습니까?"))
		form.submit();
	else
		return;
}


function idcheck() {
	form1.idChk.value="0";
	window.open("<?=$Dir.FrontDir?>iddup.php?id="+document.form1.id.value,"","height=260,width=282");
}

function mailcheck() {
	if(!IsMailCheck(form1.email.value)) {
		alert("이메일 형식이 맞지않습니다.\n\n확인하신 후 다시 입력하세요.");
		form1.email.focus();
		return;
	}
	form1.mailChk.value="0";
	window.open("<?=$Dir.FrontDir?>mailcheck.php?email="+document.form1.email.value,"","height=150,width=200");
}
//-->
</SCRIPT>
</HEAD>
<body<?=(substr($_data->layoutdata["MOUSEKEY"],0,1)=="Y"?" oncontextmenu=\"return false;\"":"")?><?=(substr($_data->layoutdata["MOUSEKEY"],1,1)=="Y"?" ondragstart=\"return false;\" onselectstart=\"return false;\"":"")?> leftmargin="0" marginwidth="0" topmargin="0" marginheight="0"><?=(substr($_data->layoutdata["MOUSEKEY"],2,1)=="Y"?"<meta http-equiv=\"ImageToolbar\" content=\"No\">":"")?>
<? include ($Dir.MainDir.$_data->menu_type.".php") ?>

<!-- 사업자 회원가입 폼 start -->
<form name=form1 action="member_join.php" method="post" enctype="multipart/form-data">
<input type=hidden name="type" value="biz_insert">
<input type=hidden name="loginType" value="biz">
<input type=hidden name="idChk" value="0">
<input type=hidden name="mailChk" value="0">
<?if($_data->ssl_type=="Y" && strlen($_data->ssl_domain)>0 && strlen($_data->ssl_port)>0 && $_data->ssl_pagelist["MJOIN"]=="Y") {?>
<input type=hidden name=shopurl value="<?=getenv("HTTP_HOST")?>">
<?}?>
<input type=hidden name="member_gubun" value="company">
<input type=hidden name="bizno1" value="<?=$_POST['bizno1']?>">
<input type=hidden name="bizno2" value="<?=$_POST['bizno2']?>">
<input type=hidden name="bizno3" value="<?=$_POST['bizno3']?>">
<input type=hidden name="name" value="<?=$_POST['companyName']?>">

<div class="currentTitle">
	<h1 class="titleimage">사업자 회원가입</h1>
</div>
<p class="noticeWrap"><span class="red">(＊)는 필수입력 항목입니다.</span></p>
<div class="joinCompanyWrap">
	<table border="0" cellpadding="0" cellspacing="0" width="100%"  class="basicTable_line2">
		<colgroup>
			<col width="150" align="right"></col>
			<col width="" style="padding-left:5px;"></col>
			<col width="115"></col>
			<col width="" align="right"></col>
		</colgroup>
		<tr>
			<th>상호명</th>
			<td><?=$_POST['companyName']?></td>
			<th>사업자등록번호</th>
			<td><?=$_POST['bizno1']."-".$_POST['bizno2']."-".$_POST['bizno3']?></td>
		</tr>
		<!--사업자/단체 선택 -->
		<tr>
			<th align="left"><span class="red">＊</span>사업자/단체 선택</th>
			<td colspan="3" class="groupSelect">
				<input type="radio" id="groupSelect01" name="biz_gubun" value="corp" class="radio" checked />
				<label onMouseOver="style.textDecoration='underline'" onMouseOut="style.textDecoration='none'" for="groupSelect01">법인/단체 사업자</label>

				<input type="radio" id="groupSelect02" name="biz_gubun" value="indi" class="radio" />
				<label onMouseOver="style.textDecoration='underline'" onMouseOut="style.textDecoration='none'" for="groupSelect02">개인사업자</label>

				<input type="radio" id="groupSelect03" name="biz_gubun" value="simp" class="radio" />
				<label onMouseOver="style.textDecoration='underline'" onMouseOut="style.textDecoration='none'" for="groupSelect03">간이사업자</label>

				<input type="radio" id="groupSelect04" name="biz_gubun" value="social" class="radio" />
				<label onMouseOver="style.textDecoration='underline'" onMouseOut="style.textDecoration='none'" for="groupSelect04">사회공헌 단체</label>
			</td>
		</tr>

		<!-- 아이디 -->
		<tr>
			<th><span class="red">＊</span>아이디</th>
			<td colspan="3" class="id">
				<INPUT type="text" name="id" value="<?=$id?>" maxLength="50" class="input" style="width:275px" /><A href="javascript:idcheck();"class="btn_gray"><span>아이디 중복체크</span></a>
				<p style="color:#F02800;padding:10px 0px">*영문 소문자, 숫자 조합 6~50자(-, _사용가능)</p>
			</td>
		</tr>

		<!-- 비밀번호/비밀번호 확인 -->
		<tr>
			<th><span class="red">＊</span>비밀번호</th>
			<td><INPUT type="password" name="passwd1" value="<?=$passwd1?>" maxLength="20" style="WIDTH:170px;" class="input" /></td>
			<th><span class="red">＊</span>비밀번호확인</th>
			<td><INPUT type="password" name="passwd2" value="<?=$passwd2?>" maxLength="20" style="WIDTH:170px; " class="input" /></td>
		</tr>

		<!-- 휴대폰 -->
		<tr>
			<th><span class="red">＊</span>휴대폰</th>
			<td colspan="3">
				<input type="text" maxlength="15" name="mobile" id="mobile" value="<?=$req_cellNo?>" style="WIDTH:275px;border: #F02800 1px solid;margin-right:5px;" class="input" onclick="openPCCWindow()" readonly /><a href="javascript:;" onclick="openPCCWindow()" class="btn_red"><span>본인인증</span></a>

				<input type="hidden" name="result" id="result" />
			</td>
		</tr>

		<!-- 이메일 -->
		<tr>
			<th><span class="red">＊</span>이메일</th>
			<td colspan="3">
				<input type="text" name="email" id="email" maxlength="100" style="WIDTH:275px" class="input" onkeyup="email_check('email');" autocomplete="off" />

				<div class="mainForm1LinkBtn2" id='email_cert' style="display:none">
					<a href="javascript:cert_key_open();" onclick="ga('send', 'event', '버튼클릭', '회원가입 메일인증', '회원가입 페이지');" class="btn_red"><span>인증하기</span></a>
				</div>

				<!--<span style="padding-left:5px;color:#F02800">*메일주소 변경시 인증이 필요합니다.</span>-->

				<div id="msg_email" style="display:none;margin-top:10px"></div>
				<input type="hidden" name="email_enabled" id="email_enabled" />
				<input type="hidden" name="cert_value" id="cert_value" />

				<div class="mainForm1LinkBtn3" id='email_cert2' style="display:none;margin-top:4px">
					<input type="text" name="cret_num" id="cret_num" placeholder="이메일 인증번호를 입력하세요." class="input" style="width:275px;border:1px solid #F02800;box-sizing:border-box;" autocomplete="off" />
					<a href="javascript:cert_key_ok();" class="btn_red"><span>인증</span></a><a href="javascript:cert_key_go();" onclick="ga('send', 'event', '버튼클릭', '회원가입 메일인증 재발송', '회원가입 페이지');" class="btn_gray"><span>재발송</span></a>
					<div style="margin-top:10px">
						서비스 사정에 따라 이메일 수신이 최대 5분 정도 지연될 수 있습니다.<br />
						인증메일을 5분 후에도 수신하지 못할 경우 스팸 처리, 용량 초과, 메시지 차단 여부 등을 확인해 주세요.
					</div>
				</div>

				<script>
					$j('#email').blur(function() {
						email_check('email');
						/*
						if($j('#email_enabled').val() == '000' && $j('#cert_value').val() != '000' ){
							if($j("#email_cert2").css("display") == "none"){
								$j('#email_cert').fadeIn();
								$j('#email_cert').css('display','inline-block');
								$j('#id').css('background', '');
							}
						}
						*/
					});

					/*
					$j('#email').focus(function() {
						$j('#email_cert').css({"display":"none"});
					});
					*/
				</script>

				<? /*
				<a href="javascript:mailcheck();" class="btn_gray"><span>메일 인증</span></a>
				<p style="color:#F02800;padding:10px 0px">*Naver 메일(aaa@naver.com) 외 다른 메일주소로 수정하여 가입 가능합니다.</p>
				*/ ?>
			</td>
		</tr>

		<!-- 사업자등록증 첨부 -->
		<tr>
			<th class="thLast"><span style="padding-left:12px">사업자등록증</span></th>
			<td colspan="3" class="tdLast">
				<INPUT type="file" name="bizcheck" />
				<p style="color:#F02800;padding:10px 0px">*이미지 및 PDF(gif, jpg, png, pdf) 파일만 첨부가 가능합니다.</p>
			</td>
		</tr>
	</table>
</div>
<div class="btnWrap">
	<a href="javascript:CheckForm();" class="btn_grayB"><span>회원가입 완료</span></a>
	<a href="javascript:history.go(-1);" class="btn_lineB"><span>다시작성</span></a>
</div>
<!-- 사업자 회원가입 폼 end -->

</form>

<?=$onload?>

<script>
	var ck_path = "../";
</script>
<script src="/js/ajax_form.js"></script>

<!-- 본인확인 서비스 -->
<script language=javascript>
<!--
	var CBA_window; 

	function openPCCWindow(){ 
		window.name = "JOINWindow";
		var CBA_window = window.open('', 'PCCWindow', 'width=430, height=560, resizable=1, scrollbars=no, status=0, titlebar=0, toolbar=0, left=300, top=200' );

		if(CBA_window == null){ 
			 alert(" ※ 윈도우 XP SP2 또는 인터넷 익스플로러 7 사용자일 경우에는 \n    화면 상단에 있는 팝업 차단 알림줄을 클릭하여 팝업을 허용해 주시기 바랍니다. \n\n※ MSN,야후,구글 팝업 차단 툴바가 설치된 경우 팝업허용을 해주시기 바랍니다.");
		}

		document.reqCBAForm.action = 'https://pcc.siren24.com/pcc_V3/jsp/pcc_V3_j10_v2.jsp';
		document.reqCBAForm.target = 'PCCWindow';
		document.reqCBAForm.submit();
		//return true;
	}

	function sirenResult(name,mobile,sex,birth,result){
		$j("#mobile").val(mobile);
		$j("#result").val(result);
	}

//-->
</script>
<!-- 본인확인 서비스 -->

<!-- 본인확인서비스 요청 form --------------------------->
<form name="reqCBAForm" method="post" action = "" onsubmit="return openPCCWindow()">
	<input type="hidden" name="reqInfo"     value = "<? echo "$enc_reqInfo" ?>">
	<input type="hidden" name="retUrl"      value = "<? echo "$retUrl" ?>">
	<input type="hidden" name="verSion"		value = "2"><!--모듈 버전정보-->
</form>
<!--End 본인확인서비스 요청 form ----------------------->

<script>
	//이메일 중복체크 처음 한번 실행
	$j(function(){
		setTimeout(function(){
			email_check('email');
		},10);
	});
</script>

<? include ($Dir."lib/bottom.php") ?>