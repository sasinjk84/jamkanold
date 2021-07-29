<?
	$Dir="../";
	include_once($Dir."lib/init.php");
	include_once($Dir."lib/lib.php");
	include_once($Dir."lib/shopdata.php");

	//SNS 로그인
	@include_once($Dir."lib/sns_init.php");

	if(strlen($_ShopInfo->getMemid())>0) {
		header("Location:mypage_usermodify.php");
		if( $preview===false ) exit;
	}

	$leftmenu="Y";
	$sql="SELECT body,leftmenu FROM ".$designnewpageTables." WHERE type='joinagree'";
	$result=mysql_query($sql,get_db_conn());
	if($row=mysql_fetch_object($result)) {
		$body=$row->body;
		$body=str_replace("[DIR]",$Dir,$body);
		$leftmenu=$row->leftmenu;
		$newdesign="Y";
	}
	mysql_free_result($result);
?>

<HTML>
<HEAD>
<TITLE><?=$_data->shoptitle?> - 회원가입</TITLE>
<META http-equiv="CONTENT-TYPE" content="text/html; charset=EUC-KR">
<META http-equiv="X-UA-Compatible" content="IE=Edge" />
<META name="description" content="<?=(strlen($_data->shopdescription)>0?$_data->shopdescription:$_data->shoptitle)?>">
<META name="keywords" content="<?=$_data->shopkeyword?>">
<script type="text/javascript" src="<?=$Dir?>lib/lib.js.php"></script>
<script type="text/javascript" src="/js/jquery-1.8.3.min.js"></script>
<script language="javascript" type="text/javascript">
$j = jQuery.noConflict();
</script>
<?include($Dir."lib/style.php")?>
</HEAD>
<style>
.joinClassification{width:600px;margin:0px auto;}
.joinClassification a{display:block;margin:15px 0px;border:1px solid #dddddd;border-radius:10px;overflow:hidden;padding: 15px;color:#aaaaaa;-moz-transition: all 0.3s ease; -ms-transition: all 0.3s ease; -o-transition: all 0.3s ease;transition: all 0.3s ease;}
.joinClassification a:hover{background:#f9f9f9;}
.joinClassification a p{font-weight:bold;color:#333333;font-size:20px;padding:13px 0px 5px;}
.joinClassification a div{color:#aaaaaa;}
.joinClassification a .icon{float:left;width: 100px;padding:0px;}
.joinClassification a img{width:80%;margin-right:20px;}
.joinClassification a .go{float: right;width:35px;border-left: 1px solid #e5e5e5;    padding: 10px 0px 10px 15px; margin-top: 20px;}
.joinClassification a .go img{width:50%;}
</style>

<body<?=(substr($_data->layoutdata["MOUSEKEY"],0,1)=="Y"?" oncontextmenu=\"return false;\"":"")?><?=(substr($_data->layoutdata["MOUSEKEY"],1,1)=="Y"?" ondragstart=\"return false;\" onselectstart=\"return false;\"":"")?> leftmargin="0" marginwidth="0" topmargin="0" marginheight="0"><?=(substr($_data->layoutdata["MOUSEKEY"],2,1)=="Y"?"<meta http-equiv=\"ImageToolbar\" content=\"No\">":"")?>

<? include ($Dir.MainDir.$_data->menu_type.".php") ?>

<!-- 기존소스백업
<div class="currentTitle">
	<div class="titleimage">회원가입</div>
</div>

<div class="joinClassification">
	<a href="https://sso2.tvcf.co.kr/index.html" target="blank">
		<p class="go"><img src="/data/design/img/sub/icon_nero.png"></p>
		<p class="icon"><img src="/data/design/img/sub/icon_tvcf.png"></p>
		<p>Tvcf로 가입</p>
		TVCF 전문가회원, 아카데미회원 가입은 tvcf 계정만 가능합니다.
	</a>

	<?
		/*
		<a href="">
			<p class="go"><img src="/data/design/img/sub/icon_nero.png"></p>
			<p class="icon"><img src="/data/design/img/sub/icon_naver.png"></p>
			<p style="padding-top:20px;">네이버아이디로 가입</p>
		</a>
		*/
		echo $naver->login();
	?>

	<a href="/front/businessLicense_check.php">
		<p class="go"><img src="/data/design/img/sub/icon_nero.png"></p>
		<p class="icon"><img src="/data/design/img/sub/icon_business.png"></p>
		<p>사업자 구매회원 가입</p>사업자등록증을 보유한 구매회원
	</a>

</div>
-->

<style>
.member {
  width: 320px;
  margin: 0 auto;
  text-align: center;
  padding-top: 70px;
}
.member > h1 {
  font-size: 30px;
  font-weight: normal;
  color: #3B3B3B;
  padding-bottom: 8px;
  text-align:center;
}
.member > h1 img {
  width: 106px;
  height: 19px;
  vertical-align: middle;
  margin: -5px 8px 0 0;
}
.member > h5 {
  font-size: 13px;
  font-weight: normal;
  color: #ACACAC;
  padding-bottom: 60px;
  text-align:center;
}
.member > .buttonWrap div{
  margin-bottom:18px;
}
.member > .buttonWrap > a {
  margin-bottom: 16px;
  display:block;
}
.member > .buttonWrap > a > img {
  cursor: pointer;
}
.member > .buttonWrap > a > div {
  height: 21px;
  padding-top: 4px;
  text-align:center;
}
.member > .hr {
  color: #c3c3c3;
  padding: 0 0 20px 0;
}
.member > .form {
  position: relative;
  text-align: left;
}
.member > .checks {
  margin-top: 4px;
  text-align: right;
}
.member > .checks > a {
  color: #000;
  font-size: 11px;
  text-decoration: none;
}
.member > .checks > a:hover {
  color: #109ba5;
}
.member > .memGroup {
  height: 78px;
}
.member > .memGroup > .join2 {
  margin-top: 5px;
  text-align: right;
}
.member > .memGroup > .join2 > button {
  font-size: 12px;
  letter-spacing: -2px;
  color: #000;
  text-decoration: underline;
  border: 0;
  background: transparent;
  cursor: pointer;
}
.member > .memGroup > .join2 > button:hover {
  color: #109ba5;
}
.member > .memGroup > .mail_check {
  padding: 4px 0 0 0;
}
.member > .memGroup > .mail_check > div:nth-child(1) a {
  color: #109ba5;
}
.member > .memGroup > .mail_check > div:nth-child(2) {
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 4px 0;
}
.member > .memGroup > .mail_check > div:nth-child(2) > input[type=text] {
  width: 90px;
  height: 24px;
  text-align: center;
  padding: 0 4px;
  margin-right: 4px;
  border-radius: 4px;
  border: 1px solid #b9b9b9;
  background: #fff;
}
.member > .memGroup > .mail_check > div:nth-child(2) > button {
  height: 24px;
  font-size: 12px;
  color: #fff;
  border: 0;
  border-radius: 4px;
  padding: 0 5px;
  background: #109ba5;
}
.member > .memGroup > .mail_check > div:nth-child(2) > button:hover {
  background: #09838c;
}
.member > .memGroup > .mail_check > div:nth-child(2) > span {
  color: #ff4400;
  padding-left: 4px;
}
.member > .memGroup > .validate {
  width: 100%;
  font-size: 12px;
  color: #ff4400;
  padding-top: 4px;
}
.member > .memGroup > .validate > span {
  color: #09adef;
}
.member > .agree {
  padding: 0 0 8px 0;
}
.member > .buttons {
  margin: 0 0 20px 0;
  text-align:center;
}
.member > .buttons > .join {
  width: 120px;
  height: 50px;
  font-size: 14px;
  color: #109ba5;
  border: 0;
  border-radius: 4px;
  margin: 50px 5px 0 0;
  background: #F2F2F2;
  outline: none;
  cursor: pointer;
}
.member > .buttons > .start {
  width: 100%;
  height: 50px;
  font-size: 14px;
  color: #fff;
  border: 0;
  border-radius: 4px;
  background: #109ba5;
  outline: none;
  cursor: pointer;
  transition: 0.2s;
}
.member > .buttons > .start:hover {
  background: #09838c;
  box-shadow: 0px 3px 5px -1px rgba(0, 0, 0, 0.2), 0px 6px 10px 0px rgba(0, 0, 0, 0.14), 0px 1px 18px 0px rgba(0, 0, 0, 0.12);
  transition: 0.2s;
}
.member > .buttons > .login {
  width: 185px;
  height: 50px;
  font-size: 14px;
  color: #fff;
  border: 0;
  border-radius: 4px;
  margin: 50px 0 0 5px;
  background: #109ba5;
  outline: none;
  cursor: pointer;
  transition: 0.2s;
}
.member > .buttons > .login:hover {
  background: #09838c;
  box-shadow: 0px 3px 5px -1px rgba(0, 0, 0, 0.2), 0px 6px 10px 0px rgba(0, 0, 0, 0.14), 0px 1px 18px 0px rgba(0, 0, 0, 0.12);
  transition: 0.2s;
}
.member > .buttons > #refreshUrl {
  color: #8a8a8a;
  letter-spacing: -1px;
  margin-top: 20px;
  border: 0;
  text-decoration: underline;
  background: transparent;
  cursor: pointer;
}
.member > .buttons > #refreshUrl > span {
  display: inline-block;
  padding-right: 6px;
  text-decoration: none;
}
.member > .buttons > #refreshUrl > span:hover {
  color: #8a8a8a;
}
.member > .buttons > #refreshUrl:hover {
  color: #109ba5;
}
.member > .buttons > #refreshUrl:hover span {
  color: #8a8a8a;
}
.member > .agree a {
  color: #109ba5;
}
.member > .safari_error {
  padding-top: 30px;
}
.member > .safari_error a {
  color: #ff4400;
}
.member > .chrome {
  padding-top: 12px;
}
.member > .chrome > img {
  width: 16px;
}
.member > .chrome > span {
  color: #1ea3d8;
}
.form > div:nth-child(2) {
  margin: 20px 0 0 0;
}
:focus {
  outline: none;
}
.box {
  position: relative;
  text-align: left;
}
.effect-17 {
  font-size: 18px;
  width: 100%;
  box-sizing: border-box;
  border: 0;
  padding: 6px 0;
  border-bottom: 1px solid #d2d2d2;
  background-color: transparent;
  outline: none;
}
.effect-17:-webkit-autofill {
  -webkit-box-shadow: 0 0 0px 1000px white inset;
}
.effect-17 ~ .focus-border {
  position: absolute;
  bottom: 0;
  left: 50%;
  width: 0;
  height: 1px;
  background-color: #109ba5;
  transition: 0.2s;
}
.effect-17:focus ~ .focus-border,
.has-content.effect-17 ~ .focus-border {
  width: 100%;
  transition: 0.2s;
  left: 0;
}
.effect-17 ~ label {
  position: absolute;
  left: 0;
  width: 100%;
  top: 9px;
  font-size: 15px;
  font-style: italic;
  color: #aaa;
  transition: 0.2s;
  z-index: -1;
  letter-spacing: -1px;
}
.effect-17:focus ~ label,
.has-content.effect-17 ~ label {
  top: -12px;
  font-size: 11px;
  color: #109ba5;
  transition: 0.2s;
  z-index: 1;
}

</style>

	<div class="member">
        <h1><img src="/data/design/img/sub/jamkan_logo.svg" alt="jamkan로고" class="" />회원가입</h1>
        <h5>잠깐 빌려 쓰고 싶을때, 잠깐닷컴</h5>
	
		<!-- 네이버 / tvcf / 사업자 회원가입 버튼 시작 -->
		<div class="buttonWrap">
		
			<a href="https://sso2.tvcf.co.kr/index.html" target="blank">
				<img src="/data/design/img/sub/tvcf-1.svg" alt="tvcf 아이디로 가입">
				<div style="text-indent:10px;">전문가, 학생 가입은 TVCF 계정만 가능합니다.</div>
			</a>
			
	<?
		/*
			<div>
				<img src="/data/design/img/sub/naver-1.svg" alt="네이버 아이디로 가입">
				<div></div>
			</div>
		*/
		echo $naver->login();
	?>
		<div style="height:5px"></div>
			
			<a href="/front/businessLicense_check.php">
				<img src="/data/design/img/sub/corp-1.svg" alt="사업자로 시작하기">
				<div style="text-indent:-30px;">사업자등록증을 보유한 구매 회원</div>
			</a>
		</div>
	
		<!-- 네이버 / tvcf / 사업자 회원가입 버튼 끝 -->	

	
		

			<div class="buttons">
			  <button id="refreshUrl"  type="button" onclick="location.href='/front/login.php' "><span>이미 회원이십니까?</span>로그인</button>
			</div>




    </div>
	
    <!-- Script here -->
    <script src="https://code.jquery.com/jquery-2.2.3.min.js"></script>
    <script>
      // JavaScript for label effects only
      $(window).load(function() {
        $(".box input").val("");

        $(".box input").focusout(function() {
          if ($(this).val() != "") {
            $(this).addClass("has-content");
          } else {
            $(this).removeClass("has-content");
          }
        });
      });

    </script>



<? include ($Dir."lib/bottom.php") ?>

</BODY>
</HTML>