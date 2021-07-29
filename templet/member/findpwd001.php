<div style="width:40%;margin:0px auto;">
	<div style="text-align:center;">
<!--<img src="<?=$Dir?>images/member/idsearch_skin3_text01.gif" border="0" />-->
		아이디 또는 비밀번호를 잊어버리셨나요?<br />
		가입하실 때 입력하신 정보와 일치할 경우, E-mail로 아이디와 패스워드를 보내드립니다.
	</div>


<div style="margin:30px;padding:30px;border:1px solid #ededed;">
					<table cellpadding="0" cellspacing="0" width="100%" >
					<colgroup>
						<col width="120"></col>
						<col width="*"></col>
					</colgroup>
						<tr>
							<td>이름</td>
							<td><input type="text" name="name" value="" maxlength="20" style="WIDTH:100%" class="input"></td>
						</tr>
						<tr>
<!--														<td width="120"><?=($_data->resno_type!="N"?"주민등록번호":"가입 메일주소")?></td>-->
							<td>가입 메일주소</td>
							<td><input type=text name=email value="" maxlength=50 style="width:100%" class="input"></td>
<!--														<td width="240" style="padding:2px;"><? if($_data->resno_type!="N"){?><input type=text name=jumin1 value="" maxlength=6 style="width:42%" onkeyup="strnumkeyup2(this);" class="input"> - <input type="password" name=jumin2 value="" maxlength=7 onkeyup="strnumkeyup2(this);" style="width:48%" class="input"><?}else{?><input type=text name=email value="" maxlength=50 style="width:100%" class="input"><?}?></td>-->
						</tr>
					</table>
</div>
		<div style="text-align:center;"><A HREF="javascript:CheckForm()" class="btn_grayB"><span>확인</span></a> <!--<A HREF="<?=$Dir.FrontDir?>login.php" class="btn_line">로그인</a>--></div>


</div>