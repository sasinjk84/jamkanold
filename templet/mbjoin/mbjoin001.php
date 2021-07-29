<style>
.btn_gray {padding: 6px 10px;width: 70px;height: 35px;}
.btn_red {padding: 6px 10px;width: 70px;height: 35px;
    text-align: center;
    font-size: 13px;
    box-sizing: border-box;
    line-height: 14px;
    background: #F02800;
    border: #F02800 1px solid;
    color: #ffffff;
    border-radius: 2px;
}
input::placeholder{color: #848484;}
.btn_red span{color:#ffffff;}

#ui-datepicker-div{width:252px;margin-top:5px}
.ui-datepicker .ui-datepicker-title{color:#666}
.ui-datepicker select.ui-datepicker-month{margin-left:10px}
</style>

	<p class="noticeWrap"><span class="red">(＊)는 필수입력 항목입니다.</span></p>
	<div class="joinCompanyWrap">
	<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<?if($_data->resno_type!="N" && strlen($adultauthid)>0){###### 서신평 아이디가 존재하면 실명인증 안내멘트######?>
	<tr>
		<td>&nbsp;-&nbsp;&nbsp;입력하신 이름과 주민번호의 <font color="#F02800"><b>실명확인이 되어야 회원가입을 완료하실 수 있습니다.</td>
	</tr>
	<?}?>

	<tr>
		<td>
		<table border="0" cellpadding="0" cellspacing="0" width="100%" class="basicTable_line2">
			<col width="170"></col>
			<col width="130"></col>
			<col width="148"></col>
			<col></col>
			<? if(!$loginType){ //SNS로그인(네이버)일 때 ?>
				<? /*
				<tr>
					<th><span class="red">＊</span>ID</th>
					<td colspan="3"><INPUT type=text name="email" value="<?=$email?>" maxLength="100" style="WIDTH:50%;" class="input" placeholder=""> <A href="javascript:mailcheck();" class="btn_gray"><span>메일 인증</span></a>
					<p style="color:#F02800;padding:10px 0px">*Naver 메일(aaa@naver.com) 외 다른 메일주소로 수정하여 가입 가능합니다.</p>
					</td>
				</tr>
				*/ ?>

				<tr>
					<th><span class="red">＊</span> 아이디</th>
					<td colspan="3">
						<INPUT type="text" name="id" value="<?=$id?>" maxLength="100" placeholder="아이디로 사용할 이메일 주소 입력" style="WIDTH:275px" class="input" />
					</td>
				</tr>
			<? } ?>

			<? if($loginType=="tvcf"){ //TVCF로그인 ?>
			<INPUT type="hidden" name="id" value="<?=$id?>" />
			<? } ?>

			<tr>
				<th ><span class="red">＊</span> 이메일</th>
				<td colspan="3">
					<INPUT type="text" name="email" id="email" value="<?=$email?>" maxLength="100" placeholder="이메일 주소 입력" class="input" style="WIDTH:275px; border:none; border-bottom:1px solid #ddd" onkeyup="email_check('email');" autocomplete="off" readonly />

					<div class="mainForm1LinkBtn2" id='email_cert' style="display:none">
						<a href="javascript:cert_key_open();" onclick="ga('send', 'event', '버튼클릭', '회원가입 메일인증', '회원가입 페이지');" class="btn_red"><span>인증하기</span></a>
					</div>

					<!--<span style="padding-left:5px;color:#F02800">*메일주소 변경시 인증이 필요합니다.</span>-->

					<div id='msg_email' style="display:none;margin-top:10px"></div>
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
						/*
						$j('#email').blur(function() {
							email_check('email');
							if($j('#email_enabled').val() == '000' && $j('#cert_value').val() != '000' ){
								if($j("#email_cert2").css("display") == "none"){
									$j('#email_cert').fadeIn();
									$j('#email_cert').css('display','inline-block');
									$j('#id').css('background', '');
								}
							}
						});

						$j('#email').focus(function() {
							$j('#email_cert').css({"display":"none"});
						});
						*/
					</script>

					<!--
					<A href="javascript:idcheck();" class="btn_gray"><span>메일인증</span></a>
					<p style="color:#F02800;padding:10px 0px">*네이버(naver) 메일 외 다른 메일주소 사용시 메일인증이 필요합니다.</p>
					-->
				</td>
			</tr>
			<tr>
				<th><span class="red">＊</span>이름</th>
				<td colspan="3">
					<INPUT type="text" name="name" id="name" value="<?=$name?>" maxLength="15" style="WIDTH:275px;border: #F02800 1px solid;vertical-align:middle" class="input" readonly onclick="openPCCWindow()" />
					<A href="javascript:;" onclick="openPCCWindow()" class="btn_red" style="vertical-align:middle"><span>본인인증</span></a>

					<input type="hidden" name="result" id="result" />
				</td>
			</tr>

			<tr>
				<th><span class="red">＊</span> 비밀번호</th>
				<td><INPUT type="password" name="passwd1" value="<?=$passwd1?>" maxLength="20" style="WIDTH:120px" class="input" /></td>
				<th style="text-align:right"><span class="red">＊</span>비밀번호 확인</th>
				<td><INPUT type="password" name="passwd2" value="<?=$passwd2?>" maxLength="20" style="WIDTH:120px" class="input" /></td>
			</tr>

			

			<? if($_data->resno_type!="N"){ ?>
			<tr>
				<th><span class="red">＊</span>주민등록번호</th>
				<td colspan="3"><INPUT type=text name="resno1" value="<?=$resno1?>" maxLength="6" onkeyup="return strnumkeyup2(this);" style="WIDTH:50px;" class="input"> - <INPUT type=password name="resno2" value="<?=$resno2?>" maxLength="7" onkeyup="return strnumkeyup2(this);" style="WIDTH:58px;" class="input"></td>
			</tr>
			<? } ?>

			<tr style="display:none" id="mobile_tr">
				<th><span class="red">＊</span>휴대폰번호</th>
				<td colspan="3">
					<INPUT type="text" maxLength="15" name="mobile" id="mobile" value="<?=$req_cellNo?>" style="WIDTH:275px;background:#f8f8f8" class="input" readonly />
				</td>
			</tr>

			<!--tr>
				<th><span class="red">＊</span>전화번호</th>
				<td colspan="3"><INPUT type=text name="home_tel" value="<?=$home_tel?>" maxLength="15" style="WIDTH:275px;" class="input"></td>
			</tr-->
			<INPUT type=hidden name="home_tel" value="<?=$home_tel?>" maxLength="15" style="WIDTH:275px;" class="input">

			<? if($extconf['reqgender'] != 'H'){ ?>
				<tr style="display:none" id="gender_tr">
					<? if($extconf['reqgender'] == 'Y'){ ?><th><span class="red">＊</span><? }else{ ?><th align="left" style="padding-left:27px"><? } ?>성별</th>
					<td colspan="3">
						<label style="cursor:pointer"><INPUT type="radio" name="gender_chk" value="1" class="radio" disabled /> 남성</label> &nbsp;&nbsp;
						<label style="cursor:pointer"><INPUT type="radio" name="gender_chk" value="2" class="radio" disabled /> 여성</label>
					</td>
				</tr>
				<INPUT type="hidden" name="gender" id="gender" value="" />
			<? } ?>

			<? if($extconf['reqbirth'] != 'H'){ ?>
			<tr style="display:none" id="birthday_tr">
				<th><? if($extconf['reqbirth'] == 'Y'){?><span class="red">＊</span><? } ?>생년월일</th>
				<td colspan="3">
					<INPUT type="text" name="birth" id="birth_day" value="<?=$req_birYMD?>" maxLength="10" style="WIDTH:275px;background:#f8f8f8" class="input" readonly />
				</td>
			</tr>
			<? } ?>

			<? /*
			<tr>
				<th><!--span class="red">＊</span-->주소</th>
				<td colspan="3">
					<div style="overflow:hidden">
						<INPUT type="text" name="home_post1" id="home_post1" readOnly style="WIDTH:60px;" class="input"> 
						<A href="javascript:addr_search_for_daumapi('home_post1','home_addr1','home_addr2');"  class="btn_line basic_button grayBtn"><span>주소찾기</span></a>
					</div>
					<div style="margin:3px 0px;overflow:hidden"><INPUT type="text" name="home_addr1" id="home_addr1" maxLength="100" readOnly style="WIDTH:96%;" class="input" /></div>
					<div style="overflow:hidden"><INPUT type="text" name="home_addr2" id="home_addr2" maxLength="100" style="WIDTH:96%;" class="input" /></div>
				</td>
			</tr>
			*/ ?>
			<!--
			<tr>
				<th style="padding-left:16px">회사주소</th>
				<td colspan="3">

					<div style="overflow:hidden">
						<INPUT type="text" name="office_post1" id="office_post1" readOnly style="WIDTH:60px;" class="input"> 
						<A href="javascript:addr_search_for_daumapi('office_post1','office_addr1','office_addr2');"  class="btn_line basic_button grayBtn"><span>주소찾기</span></a>
					</div>
					<div style="margin:3px 0px;overflow:hidden"><INPUT type="text" name="office_addr1" id="office_addr1" maxLength="100" readOnly style="WIDTH:96%;" class="input" /></div>
					<div style="overflow:hidden"><INPUT type="text" name="office_addr2" id="office_addr2" maxLength="100" style="WIDTH:96%;" class="input" /></div>

					
					<table cellpadding="0" cellspacing="0" width="100%">
						<tr>
							<td>
								<INPUT type=text name="office_post1" value="<?=$office_post1?>" readOnly style="WIDTH:40px;" class="input"> - <INPUT type=text name="office_post2" value="<?=$office_post2?>" readOnly style="WIDTH:40px;" class="input"><a href="javascript:f_addr_search('form1','office_post','office_addr1',2);"><img src="<?=$Dir?>images/common/mbjoin/<?=$_data->design_mbjoin?>/memberjoin_skin1_btn2.gif" border="0" align="absmiddle" hspace="3"></a>
							</td>
						</tr>
						<tr>
							<td><INPUT type=text name="office_addr1" value="<?=$office_addr1?>" maxLength="100" readOnly style="WIDTH:80%;" class="input"></td>
						</tr>
						<tr>
							<td><INPUT type=text name="office_addr2" value="<?=$office_addr2?>" maxLength="100" style="WIDTH:80%;" class="input"></td>
						</tr>
					</table>
					
				</td>
			</tr>
			-->
			<?
				if($recom_ok=="Y") {
					if($recom_url_ok=="Y" && $_COOKIE['url_id'] != ""){
						if($_data->recom_addreserve >0){
			?>
			<tr>
				<th style="padding-left:16px">추가적립금</th>
				<td colspan="3"><b><?=$_COOKIE['url_name']?>(<?=$_COOKIE['url_id']?>)</b>님의 초대로 <b><font color="#FD9999"> 적립금 <?=$_data->recom_addreserve?>원</font></b>을 추가 적립해 드립니다.<input type="hidden" name="rec_id" value="<?=$_COOKIE['url_id']?>"></td>
			</tr>
			<? }else{ ?>
			<tr>
				<th style="padding-left:16px">추천인</th>
				<td colspan="3"><b><?=$_COOKIE['url_name']?>(<?=$_COOKIE['url_id']?>)</b>님의 초대를 받았습니다.<input type="hidden" name="rec_id" value="<?=$_COOKIE['url_id']?>" style="WIDTH:275px;"></td>
			</tr>
			<?
					}
				}else{
			?>
			<tr>
				<th style="padding-left:16px">추천 ID</th>
				<td colspan="3"><INPUT type="text" name="rec_id" maxLength="12"  value="<?=$rec_id?>" style="WIDTH:275px;" class="input"></td>
			</tr>
			<?
					}
				}

				if(strlen($straddform)>0) {
					echo $straddform;
				}
			?>
			<!--tr>
				<th><span class="red">＊</span>이메일 수신여부</th>
				<td colspan="3">
					<INPUT type="radio" class="radio" name="news_mail_yn" value="Y" id="idx_news_mail_yn0" <?if($news_mail_yn=="Y")echo"checked";?> style="BORDER:none;"> <LABEL style="CURSOR:pointer" for="idx_news_mail_yn0">받습니다.</LABEL>&nbsp;&nbsp;
					<INPUT type="radio" class="radio" name="news_mail_yn" value="N" id="idx_news_mail_yn1" <?if($news_mail_yn=="N")echo"checked";?> style="BORDER:none;"> <LABEL style="CURSOR:pointer" for="idx_news_mail_yn1">받지 않습니다.</LABEL>
				</td>
			</tr-->
			<? /*
			<tr>
				<th class="thLast"><span class="red">＊</span>SMS 수신여부</th>
				<td colspan="3" class="tdLast">
					<INPUT type="radio" class="radio" name="news_sms_yn" value="Y" id="idx_news_sms_yn0" <?if($news_sms_yn=="Y")echo"checked";?> style="BORDER:none;"> <LABEL style="CURSOR:pointer" for="idx_news_sms_yn0">받습니다.</LABEL>&nbsp;&nbsp;
					<INPUT type="radio" class="radio" name="news_sms_yn" value="N" id="idx_news_sms_yn1" <?if($news_sms_yn=="N")echo"checked";?> style="BORDER:none;"> <LABEL style="CURSOR:pointer" for="idx_news_sms_yn1">받지 않습니다.</LABEL>
				</td>
			</tr>
			*/ ?>
			<tr>
				<th class="thLast">정보 수신설정</th>
				<td colspan="3" class="tdLast">
					<label><input type="checkbox" name="news_mail_yn" value="Y" style="width:20px;height:20px;vertical-align:-5px" checked /> E-mail</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<label><input type="checkbox" name="news_sms_yn" value="Y" style="width:20px;height:20px;vertical-align:-5px" checked /> SMS</label>
				</td>
			</tr>
		</table>
	</td>
</tr>
</table>

</div>
<p style="margin-bottom:50px;text-align:center">* 가입시 <a href="javascript:viewPolicy();">[회원약관]</a>과 <a href="javascript:viewProtect()">[개인정보취급방침]</a>을 동의하게 됩니다.</p>

<div class="btnWrap">
	<a href="javascript:CheckForm();" class="btn_grayB"><span>회원가입 완료</span></a>
	<a href="javascript:history.go(-1);"; class="btn_lineB"><span>다시작성</span></a>
</div>


<!-- 이용약관 전체보기 -->
<div class="policyView" id="policyView" style='display: none;'>
	<div class='viewBox1'>
		<div class='viewCloseBtn'><a style='line-height: 120%; font-size: 30px; text-decoration: none;' href='javascript:hiddenPolicy();'>×</a></div>
		<h4>회원가입 약관보기</h4>
		<div class="viewBox2"><?=$agreement?></div>
	</div>
</div>
<!-- 개인정보취급방침 전체보기 -->
<div class='policyView' id='ProtectView' style='display: none;'>
	<div class='viewBox1'>
		<div class='viewCloseBtn'><a style='line-height: 120%; font-size: 30px; text-decoration: none;' href='javascript:hiddenProtect();'>×</a></div>
		<h4>개인정보취급방침 보기</h4>
		<div class='viewBox2'><?=$privercy?></div>
	</div>
</div>

<link rel="stylesheet" href="/css/jquery-ui/jquery-ui.min.css">
<script type="text/javascript" src="/js/jquery-ui.min.js"></script>

<script type="text/javascript">
<!--
/* 이용약관 열기 */
function viewPolicy(){
	$j("#policyView").show();
}
/* 이용약관 닫기 */
function hiddenPolicy(){
	$j("#policyView").hide();
}
/* 개인정보취급방침 열기 */
function viewProtect(){
	$j("#ProtectView").show();
}
/* 개인정보취급방침 닫기 */
function hiddenProtect(){
	$j("#ProtectView").hide();
}
</script>