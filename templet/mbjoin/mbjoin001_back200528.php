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
			<col width="170" align="right"></col>
			<col width="130"></col>
			<col width="130" align="right"></col>
			<col></col>

			<tr>
				<th><span class="red">＊</span>ID</th>
				<td colspan="3"><INPUT type=text name="email" value="<?=$email?>" maxLength="100" style="WIDTH:50%;" class="input" placeholder=""> <A href="javascript:mailcheck();" class="btn_gray"><span>메일 인증</span></a>
				<p style="color:#F02800;padding:10px 0px">*Naver 메일(aaa@naver.com) 외 다른 메일주소로 수정하여 가입 가능합니다.</p>
				</td>
			</tr>
			<!--
			<tr>
				<th ><span class="red">＊</span>아이디</th>
				<td colspan="3"><INPUT type=text name="id" value="<?=$id?>" maxLength="12" style="WIDTH:120px;" class="input"> <A href="javascript:idcheck();" class="btn_gray"><span>아이디 중복확인</span></a></td>
			</tr>
			<tr>
				<th><span class="red">＊</span>이메일</th>
				<td colspan="3"><INPUT type=text name="email" value="<?=$email?>" maxLength="100" style="WIDTH:30%;" class="input"> <A href="javascript:mailcheck();" class="btn_gray"><span>이메일 중복확인</span></a></td>
			</tr>
			-->
			<tr>
				<th><span class="red">＊</span>비밀번호</th>
				<td><INPUT type=password name="passwd1" value="<?=$passwd1?>" maxLength="20" style="WIDTH:120px;" class="input"></td>
				<th><span class="red">＊</span>비밀번호확인</th>
				<td><INPUT type=password name="passwd2" value="<?=$passwd2?>" maxLength="20" style="WIDTH:120px;" class="input"></td>
			</tr>
			<tr>
				<th><span class="red">＊</span>휴대폰</th>
				<td colspan="3"><INPUT type=text maxLength="15" name="mobile" value="<?=$mobile?>" style="WIDTH:275px;border: #F02800 1px solid;" class="input"> <A href="javascript:idcheck();" class="btn_red"><span>본인인증</span></a></td>
			</tr>
			<tr>
				<th><span class="red">＊</span>이름</th>
				<td colspan="3"><INPUT type=text name="name" value="<?=$name?>" maxLength="15" style="WIDTH:275px;" class="input"></td>
			</tr>
			<? if($_data->resno_type!="N"){?>
			<tr>
				<th><span class="red">＊</span>주민등록번호</th>
				<td colspan="3"><INPUT type=text name="resno1" value="<?=$resno1?>" maxLength="6" onkeyup="return strnumkeyup2(this);" style="WIDTH:50px;" class="input"> - <INPUT type=password name="resno2" value="<?=$resno2?>" maxLength="7" onkeyup="return strnumkeyup2(this);" style="WIDTH:58px;" class="input"></td>
			</tr>
			<? }?>
			<? if($extconf['reqgender'] != 'H'){?>
			<tr>
				<? if($extconf['reqgender'] == 'Y'){?><th><span class="red">＊</span><?}else{?><td align="left" style="padding-left:27px;"><?}?>성별</th>
				<td colspan="3"><INPUT type="radio" name="gender" value="1" class="radio">남자 &nbsp;&nbsp; <INPUT type="radio" name="gender" value="2" class="radio">여자</td>
			</tr>
			<? }?>
			<? if($extconf['reqbirth'] != 'H'){?>
			<tr>
				<th><? if($extconf['reqbirth'] == 'Y'){?><span class="red">＊</span><?}?>생년월일</th>
				<td colspan="3"><INPUT type="text" name="birth" value="" maxLength="10" style="WIDTH:275px; " class="input"> ( ex : <?=date('Y-m-d')?> )</td>
			</tr>
			<? }?>
			<tr>
				<th><span class="red">＊</span>집전화</th>
				<td colspan="3"><INPUT type=text name="home_tel" value="<?=$home_tel?>" maxLength="15" style="WIDTH:275px;" class="input"></td>
			</tr>
			<tr>
				<th><span class="red">＊</span>집주소</th>
				<td colspan="3">
				<table cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td style="border:0px;">
						<!--<INPUT type=text name="home_post1" value="<?=$home_post1?>" readOnly style="WIDTH:40px;" class="input"> - <INPUT type=text name="home_post2" value="<?=$home_post2?>" readOnly style="WIDTH:40px;" class="input"><a href="javascript:f_addr_search('form1','home_post','home_addr1',2);"><img src="<?=$Dir?>images/common/mbjoin/<?=$_data->design_mbjoin?>/memberjoin_skin1_btn2.gif" border="0" align="absmiddle" hspace="3"></a>-->



						<div style="overflow:hidden">
							<INPUT type="text" name="home_post1" id="home_post1" readOnly style="WIDTH:60px;" class="input"> 
							<A href="javascript:addr_search_for_daumapi('home_post1','home_addr1','home_addr2');"  class="btn_line basic_button grayBtn"><span>주소찾기</span></a>
						</div>
						<div style="margin:3px 0px;overflow:hidden"><INPUT type="text" name="home_addr1" id="home_addr1" maxLength="100" readOnly style="WIDTH:96%;" class="input" /></div>
						<div style="overflow:hidden"><INPUT type="text" name="home_addr2" id="home_addr2" maxLength="100" style="WIDTH:96%;" class="input" /></div>


					</td>
				</tr>
				<!--
				<tr>
					<td><INPUT type=text name="home_addr1" value="<?=$home_addr1?>" maxLength="100" readOnly style="WIDTH:80%;" class="input"></td>
				</tr>
				<tr>
					<td><INPUT type=text name="home_addr2" value="<?=$home_addr2?>" maxLength="100" style="WIDTH:80%;" class="input"></td>
				</tr>
				-->
				</table>
				</td>
			</tr>
			<tr>
				<th style="padding-left:16px">회사주소</th>
				<td colspan="3">

					<div style="overflow:hidden">
						<INPUT type="text" name="office_post1" id="office_post1" readOnly style="WIDTH:60px;" class="input"> 
						<A href="javascript:addr_search_for_daumapi('office_post1','office_addr1','office_addr2');"  class="btn_line basic_button grayBtn"><span>주소찾기</span></a>
					</div>
					<div style="margin:3px 0px;overflow:hidden"><INPUT type="text" name="office_addr1" id="office_addr1" maxLength="100" readOnly style="WIDTH:96%;" class="input" /></div>
					<div style="overflow:hidden"><INPUT type="text" name="office_addr2" id="office_addr2" maxLength="100" style="WIDTH:96%;" class="input" /></div>

					<!--
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
					-->
				</td>
			</tr>
<?
if($recom_ok=="Y") {
	if($recom_url_ok=="Y" && $_COOKIE['url_id'] != ""){
		if($_data->recom_addreserve >0){
?>
			<tr>
				<th style="padding-left:16px">추가적립금</th>
				<td colspan="3"><b><?=$_COOKIE['url_name']?>(<?=$_COOKIE['url_id']?>)</b>님의 초대로 <b><font color="#FD9999"> 적립금 <?=$_data->recom_addreserve?>원</font></b>을 추가 적립해 드립니다.<input type="hidden" name="rec_id" value="<?=$_COOKIE['url_id']?>"></td>
			</tr>
<?
		}else{
?>
			<tr>
				<th style="padding-left:16px">추천인</th>
				<td colspan="3"><b><?=$_COOKIE['url_name']?>(<?=$_COOKIE['url_id']?>)</b>님의 초대를 받았습니다.<input type="hidden" name="rec_id" value="<?=$_COOKIE['url_id']?>" style="WIDTH:275px;"></td>
			</tr>
<?
		}
	}else{
?>
			<tr>
				<th style="padding-left:16px">추천ID</th>
				<td colspan="3"><INPUT type="text" name="rec_id" maxLength="12"  value="<?=$rec_id?>" style="WIDTH:275px;" class="input"></td>
			</tr>
<?
	}
}
?>

	<?
				if(strlen($straddform)>0) {
					echo $straddform;
				}
	?>
			<tr>
				<th><span class="red">＊</span>메일 수신여부</th>
				<td colspan="3"><INPUT type=radio  class="radio" name="news_mail_yn" value="Y" id="idx_news_mail_yn0" <?if($news_mail_yn=="Y")echo"checked";?> style="BORDER:none;"><LABEL onmouseover="style.textDecoration='underline'" style="CURSOR: hand" onmouseout="style.textDecoration='none'" for="idx_news_mail_yn0">받습니다.</LABEL> <INPUT type=radio class="radio"  name="news_mail_yn" value="N" id="idx_news_mail_yn1" <?if($news_mail_yn=="N")echo"checked";?> style="BORDER:none;"><LABEL onmouseover="style.textDecoration='underline'" style="CURSOR: hand" onmouseout="style.textDecoration='none'" for="idx_news_mail_yn1">받지 않습니다.</LABEL></td>
			</tr>
			<tr>
				<th class="thLast"><span class="red">＊</span>SMS 수신여부</th>
				<td colspan="3"  class="tdLast"><INPUT type=radio class="radio"  name="news_sms_yn" value="Y" id="idx_news_sms_yn0" <?if($news_sms_yn=="Y")echo"checked";?> style="BORDER:none;"><LABEL onmouseover="style.textDecoration='underline'" style="CURSOR: hand" onmouseout="style.textDecoration='none'" for="idx_news_sms_yn0">받습니다.</LABEL> <INPUT type=radio class="radio"  name="news_sms_yn" value="N" id="idx_news_sms_yn1" <?if($news_sms_yn=="N")echo"checked";?> style="BORDER:none;"><LABEL onmouseover="style.textDecoration='underline'" style="CURSOR: hand" onmouseout="style.textDecoration='none'" for="idx_news_sms_yn1">받지 않습니다.</LABEL></td>
			</tr>
		</table>
	</td>
</tr>
</table>

</div>
<div class="btnWrap">
	<a href="javascript:CheckForm();" class="btn_grayB"><span>회원가입 완료</span></a>
	<a href="javascript:history.go(-1);"; class="btn_lineB"><span>다시작성</span></a>
</div>

