<!-- 원본소스
<style>
.loginWrap .memLoginBox{padding:0px;}
.loginWrap input::placeholder{color: #848484;}
.input{height:45px;line-height:45px;}
.loginWrap{overflow:hidden;width:750px;margin:0px auto;border: 1px solid #dddddd;border-radius: 10px;}
.banner{width:780px;margin:0px auto;}
.loginWrap .jamkanLogin{float:left;width:46%;padding:6% 5%;border-right:1px solid #e8e8e8;}
.loginWrap .login_button a{padding:15px;border-radius:6px;-moz-transition: all 0.3s ease;-ms-transition: all 0.3s ease;-o-transition: all 0.3s ease;transition: all 0.3s ease;}
.loginWrap .login_button a:hover{background:#222222;}
.loginWrap .join .button {width: 100px;line-height: 14px;font-size: 13px;border:1px solid #cccccc;border-radius:3px;padding:0px;height:25px;}
.loginWrap .join p{font-size:13px;}
.notice{display:none;}
.loginWrap .join>div{margin-top:15px;}
.loginWrap .login_button a{margin-top:20px;}
.loginWrap .etcLogin{overflow:hidden;float:right;width:35%;padding:6% 4% 3%;}
.loginWrap .etcLogin div{display:block;overflow:hidden;padding-bottom:20px;margin-bottom:20px;border-bottom:1px solid #f1f1f1;}
.loginWrap .etcLogin div .title{font-weight:bold;font-size:15px;color:#333333;display:block;line-height: 25px;}
.loginWrap .etcLogin div .title span{padding-right: 17px;background: url(/data/design/img/sub/icon_nero.png)no-repeat;background-position: center;background-size: 50%;margin-left: 5px;box-sizing: border-box;}
.loginWrap .etcLogin div .icon{float:left;padding-right:10px;}
.loginWrap .etcLogin div .icon img{width:55px;}
.loginWrap .join{margin-top:15px;text-align:left;}
.loginWrap .join a{padding-right:6px;margin-right:6px;border-right:1px solid #f1f1f1;font-size:13px;}
</style>
<p class="notice" style="text-align:center;">
	<span>회원가입을 하시면 쇼핑몰에서 운영하는 각종 이벤트에 참여하실 수 있습니다.</span><br />
	또한 저희 쇼핑몰에서 엄선한 추천상품 및 이벤트 정보 등 다양한 쇼핑 정보를 메일로 받으실 수 있습니다.
</p>
<div class="loginWrap">
	<div class="jamkanLogin">
		<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td align="center">

					//로그인
					<div class="memLoginBox">
						<table border="0" cellpadding="0" cellspacing="0" style="width:100%;">
							<caption style="display:none;">회원 로그인</caption>
							<tbody>
							<tr>
								<td valign="top">
									<table border="0" cellpadding="0" cellspacing="0" style="width:100%;">
										<tr>
											<th style="display:none;">아이디</th>
											<td width="400"><input type="text" name="id" maxlength="20" value="" style="width:100%;border-bottom:0px;" class="input" placeholder="아이디 또는 E-mail" /></td>
										</tr>
										<tr>
											<th style="display:none;">비밀번호</th>
											<td><input type="password" name="passwd" maxlength="20" value=""style="width:100%" onkeydown="CheckKeyForm1()"  class="input" placeholder="비밀번호"/></td>
										</tr>

										[IFSSL]
										<tr>
											<th style="display:none;"></th>
											<td style="padding-top:5px;">[SSLCHECK] <a href=[SSLINFO]>보안접속</a></td>
										</tr>
										[ENDSSL]
									</table>
								</td>
							</tr>
							</tbody>
						</table>
						<div class="login_button"><a href="JavaScript:CheckForm()">로그인</a></div>
					</div>
					//로그인

					//비회원 주문조회
					[IFORDER]
					<div class="nomemOrderSearch">
						<table border="0" cellpadding="0" cellspacing="0">
							<caption style="display:none;">비회원 주문조회</caption>
							<thead>
								<th><IMG SRC="[DIR]images/member/login_con_text5a_skin2.gif" border="0" /></th>
							</thead>
							<tbody>
							<tr>
								<td style="padding-left:14px;">
									<table border="0" cellpadding="0" cellspacing="0">
										<tr>
											<th>주문자명</th>
											<td><input type="text" name="ordername" maxlength="20" value="" /></td>
										</tr>
										<tr>
											<th>주문번호</th>
											<td><input type="text" name="ordercodeid" maxlength="20" value="" onkeydown="CheckKeyForm2()" class="input" /></td>
										</tr>
									</table>
								</td>
								<td width="10"></td>
								<td><a href=[ORDEROK]>주문조회</a></td>
							</tr>
							</tbody>
						</table>
					</div>
					[ENDORDER]
					//비회원 주문조회

					//회원가입/아이디비번 찾기
					<div class="join">
						<a href="/front/member_classification.php">회원가입</a>
						<a href="/front/findpwd.php">ID/PW 찾기</a>
					</div>
					//회원가입/아이디비번 찾기

					//비회원구매/로그인
					[IFNOLOGIN]
					<table border="0" cellpadding="0" cellspacing="0" width="100%">
						<tr>
							<td align="center" style="padding-top:15px;">
								<table cellpadding="0" cellspacing="0">
									<tr>
										<td width="390"><A HREF=[NOLOGIN]><IMG SRC="[DIR]images/member/login_con_text5_skin2.gif" border="0"></a></td>
										<td></td>
									</tr>
									<tr>
										<td><A HREF=[NOLOGIN]><IMG SRC="[DIR]images/member/login_con_text5_skin2_text01.gif" border="0"></a></td>
										<td><A HREF=[NOLOGIN]><IMG SRC="[DIR]images/member/login_con_btn4_skin2.gif" border="0"></A></td>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td background="[DIR]images/member/login_con_line_skine2.gif" height="28"></td>
						</tr>
					</table>
					[ENDNOLOGIN]
					//비회원구매/로그인

				</td>
			</tr>
		</table>
	</div>
	<div class="etcLogin">
		<div>
			<p class="icon" style="margin-bottom:30px;"><img src="/data/design/img/sub/icon_tvcf.png"></p>
			<a href="https://sso2.tvcf.co.kr/index.html" class="title">tvcf 간편 로그인<span></span></a>
			 TVCF의 전문가회원, 아카데미회원은  tvcf 간편로그인해주세요.<br/>
			 <a href="http://www.tvcf.co.kr/" target="_blank" style="font-size:12px;border-bottom:1px solid #FF4400;color:#FF4400;padding: 3px 0px;line-height: 30px;"> TVCF 바로가기</a>
		</div>
		<div>[NAVERLOGIN]</div>
	</div>
</div>
-->




<style>
.currentTitle .titleimage{display:none;}

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
  text-align:center;
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

.loginWrap1{
overflow: hidden;
    width: 400px;
    margin: 0px auto;
}
</style>
<div class="loginWrap1">
	<!--로그인-->
	<div class="member">
        <h1><img src="/data/design/img/sub/jamkan_logo.svg" alt="jamkan로고" class="" />로그인</h1>
        <h5>잠깐 빌려 쓰고 싶을때, 잠깐닷컴</h5>

		<div class="buttonWrap">
			<a href="https://sso2.tvcf.co.kr/index.html" class="title" target="blank">
				<img src="/data/design/img/sub/tvcf.svg" alt="tvcf 아이디로 로그인">
				<div style="text-indent:5px;padding-top:5px;font-size:13px;">전문가, 학생 가입은 TVCF 계정만 가능합니다.</div>
			</a>
			[NAVERLOGIN]
		</div>

		<div class="hr" >- - - - -  - - - - - - - - - - &nbsp; 혹은 &nbsp; - - - - -- - - - - - - - - - -</div>

		<div class="form">
		  <div class="box input-effect">
			<input type="text" name="id" class="effect-17" placeholder="" ng-model="id" />
			<label>아이디 또는 이메일</label>
			<span class="focus-border"></span>
		  </div>

		  <div class="box input-effect">
			<input type="password" name="passwd" class="effect-17" placeholder="" ng-model="pw" ng-keypress="mySubmit($event)" />
			<label>비밀번호</label>
			<span class="focus-border"></span>
		  </div>
		</div>

		<div class="memGroup" style="display:none;">
			<div class="validate">
				아이디나 패스워드가 틀립니다
			</div>
		</div>

		<div class="checks">
			<a href="/front/findpwd.php">비밀번호 찾기</a>
		</div>

		<div class="buttons">
		  <button class="join" type="button" onclick="location.href='/front/member_classification.php' ">가입하기</button>
		  <button class="login" type="button" onclick="JavaScript:CheckForm()">로그인</button>
		</div>

	</div>
	<!--로그인-->



	<!--비회원 주문조회-->
	<div class="member" style="margin-top:100px;display:none;">
        <h1><img src="/data/design/img/sub/jamkan_logo.svg" alt="jamkan로고" class="" />비회원 주문조회</h1>
        <h5>주문번호는 상품 주문시 발송된 이메일에서 확인하실 수 있습니다.</h5>
		<div class="form">
		  <div class="box input-effect">
			<input type="text" name="ordername" maxlength="20" value="" class="effect-17" placeholder="" />
			<label>주문자명</label>
			<span class="focus-border"></span>
		  </div>

		  <div class="box input-effect">
			<input  class="effect-17" type="text" name="ordercodeid" maxlength="20" value="" onkeydown="CheckKeyForm2()" />
			<label>주문번호</label>
			<span class="focus-border"></span>
		  </div>
		</div>

		<div class="buttons">
		  <button class="login" type="button" style="width:100%;">주문조회</button>
		</div>
	</div>
	<!--비회원 주문조회-->




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
</div>

<div class="banner">[BANNER]</div>