<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/shopdata.php");


$token = Trim($_REQUEST["token"]);
$ref = Trim($_REQUEST["ref"]);

?><!DOCTYPE HTML>
<HEAD>
<TITLE><?=$_data->shoptitle?> - 회원로그인</TITLE>
<META http-equiv="CONTENT-TYPE" content="text/html; charset=EUC-KR">
<META http-equiv="X-UA-Compatible" content="IE=Edge" />

<META name="description" content="<?=(strlen($_data->shopdescription)>0?$_data->shopdescription:$_data->shoptitle)?>">
<META name="keywords" content="<?=$_data->shopkeyword?>">
<script type="text/javascript" src="<?=$Dir?>lib/lib.js.php"></script>

<script type="text/javascript" src="/js/jquery-1.10.2.min.js"></script>
<script language="javascript">
<!--
/*
function ssoLogin(){
	var data = {"id":"jamkan00@jamkan.com","pw":"jamkan01"};
	data = JSON.stringify(data);
	jQuery.ajax({
		url: "https://sso2.tvcf.co.kr/api/token",
		type: "POST",
		contentType: 'application/json',
		data: data,
		dataType: 'json',
		success: function(res) {
			$("#key").val(res);
			ssoGetUserInfo();
		},
		beforeSend: function() {
		},
		complete: function() {
		},
		error: function(result) {
			console.log(result);
		},
		timeout: 30000
	});

}
*/

function ssoGetUserInfo(){
	var data = {"key":"<?=$token?>"};
	//var data = {"key":$("#key").val()};
	data = JSON.stringify(data);

	jQuery.ajax({
		url: "https://sso2.tvcf.co.kr/auth/Jamkan.com/GetUser/",
		type: "POST",
		contentType: 'application/json',
		data: data,
		dataType: 'json',
		success: function(res) {
			$("#memid").val(res.Data[0]['G_USER_ID']);
			$("#memname").val(res.Data[0]['G_USER_NAME']);
			$("#mememail").val(res.Data[0]['G_USER_EMAIL']);
			$("#mobile").val(res.Data[0]['G_USER_MOBILE']);
			$("#birth").val(res.Data[0]['G_USER_BIRTH']);
			$("#gender").val(res.Data[0]['G_USER_SEX']);
			$("#gubun").val(res.Data[0]['G_USER_TYPE']);
			$("#sosok").val(res.Data[0]['G_USER_CORP']);
			$("#geekjong").val(res.Data[0]['G_USER_GEEKJONG']);
			$("#upjong").val(res.Data[0]['G_USER_UPJONG']);
			document.ssoLoginForm.submit();
		},
		beforeSend: function() {
		},
		complete: function() {
		},
		error: function(result) {
			console.log(result);
		},
		timeout: 30000
	});

}

ssoGetUserInfo();
//-->
</script>
</head>
<body>
<form name="ssoLoginForm" method="post" action="/lib/login_sso_process.php">
<input type="hidden" name="loginType" value="tvcf">
<input type="hidden" name="id" id="memid">
<input type="hidden" name="name" id="memname">
<input type="hidden" name="email" id="mememail">
<input type="hidden" name="home_tel" id="mobile">
<input type="hidden" name="birth" id="birth">
<input type="hidden" name="gender" id="gender">
<input type="hidden" name="gubun" id="gubun">
<input type="hidden" name="geekjong" id="geekjong">
<input type="hidden" name="sosok" id="sosok">
<input type="hidden" name="upjong" id="upjong">
<input type="hidden" name="key" id="key" value="<?=$token?>">
</form>
<!--
<button onclick="javascript:ssoLogin()">로그인</button>

<button onclick="javascript:ssoGetUserInfo()">회원정보</button>
-->
</body>

</html>
