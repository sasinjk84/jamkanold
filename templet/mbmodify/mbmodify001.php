<script>
<!--
$j(document).on("click", "#gubun05", function() {
	if(confirm("사업자 회원으로 신규 가입하셔야 합니다. 가입하시겠습니까?")){
		document.location.href="/front/businessLicense_check.php?bizcheck=ok";
	}
});
//-->
</script>
	<table cellpadding="0" cellspacing="0" width="100%">
	<!--
	<tr>
		<td>
		<TABLE WIDTH="100%" BORDER="0" CELLPADDING="0" CELLSPACING="0">
		<TR>
			<TD><A HREF="<?=$Dir.FrontDir?>mypage.php"><IMG SRC="<?=$Dir?>images/common/mypersonal_skin3_menu1.gif" BORDER="0"></A></TD>
			<TD><A HREF="<?=$Dir.FrontDir?>mypage_orderlist.php"><IMG SRC="<?=$Dir?>images/common/mypersonal_skin3_menu2.gif" BORDER="0"></A></TD>
			<TD><A HREF="<?=$Dir.FrontDir?>mypage_personal.php"><IMG SRC="<?=$Dir?>images/common/mypersonal_skin3_menu3.gif" BORDER="0"></A></TD>
			<TD><A HREF="<?=$Dir.FrontDir?>wishlist.php"><IMG SRC="<?=$Dir?>images/common/mypersonal_skin3_menu4.gif" BORDER="0"></A></TD>
			<TD><A HREF="<?=$Dir.FrontDir?>mypage_reserve.php"><IMG SRC="<?=$Dir?>images/common/mypersonal_skin3_menu5.gif" BORDER="0"></A></TD>
			<TD><A HREF="<?=$Dir.FrontDir?>mypage_coupon.php"><IMG SRC="<?=$Dir?>images/common/mypersonal_skin3_menu6.gif" BORDER="0"></A></TD>
			<?if($_data->recom_url_ok == "Y" || $_data->sns_ok == "Y"){?><TD><A HREF="<?=$Dir.FrontDir?>mypage_promote.php"><IMG SRC="<?=$Dir?>images/common/mypersonal_skin3_menu10.gif" BORDER="0"></A></TD><?}?>
			<TD><A HREF="<?=$Dir.FrontDir?>mypage_gonggu.php"><IMG SRC="<?=$Dir?>images/common/mypersonal_skin3_menu11.gif" BORDER="0"></A></TD>
			<? if(getVenderUsed()==true) { ?><TD><A HREF="<?=$Dir.FrontDir?>mypage_custsect.php"><IMG SRC="<?=$Dir?>images/common/mypersonal_skin3_menu9.gif" BORDER="0"></A></TD><? } ?>
			<TD><A HREF="<?=$Dir.FrontDir?>mypage_usermodify.php"><IMG SRC="<?=$Dir?>images/common/mypersonal_skin3_menu7r.gif" BORDER="0"></A></TD>
			<TD><A HREF="<?=$Dir.FrontDir?>mypage_memberout.php"><IMG SRC="<?=$Dir?>images/common/mypersonal_skin3_menu8.gif" BORDER="0"></A></TD>
			<TD width="100%" background="<?=$Dir?>images/common/mypersonal_skin3_menubg.gif"></TD>
		</TR>
		</TABLE>
		</td>
	</tr>
	-->
	<tr><td height="10"></td></tr>
	<tr>
		<td>&nbsp;- &nbsp;<font color="#F02800"><b>(＊)는 필수입력 항목입니다.</b></font><br>
		&nbsp;- &nbsp;회원 정보를 수정하신 후 정보수정 버튼을 눌러주십시오.<br>
<!--		<?if($_data->memberout_type=="Y" || $_data->memberout_type=="O") {?>
		&nbsp;- &nbsp;해당 쇼핑몰에서 회원탈퇴를 원하시면 <a href="JavaScript:memberout()">[회원탈퇴]</a>를 눌러주십시요.
		<?}?>-->
		</td>
	</tr>
</table>


			<table cellpadding="0" cellspacing="0" width="100%" border="0" class="basicTable_line1">
			<col width="180" align="right"></col>
			<col width="130" style="padding-left:5px;"></col>
			<col width="50" align="right"></col>
			<col style="padding-left:5px;"></col>
			<tr>
				<td align="left" style="padding-left:14px"><font color="#F02800">*</font>이메일</td>
				<td colspan="3"><INPUT type=text name="email" value="<?=$email?>" maxLength="100" style="WIDTH:80%;background:#f5f5f5" class="input" readonly /></td>
			</tr>
			<?
			if($passwd!=""){
			?>
			<tr>
				<td align="left" style="padding-left:14px"><font color="#F02800">*</font>기존비밀번호</td>
				<td colspan="3"><input type=password name="oldpasswd" value="" maxlength="20"  class="input"> 현재 사용중인 비밀번호를 입력하세요.</td>
			</tr>
			<? } ?>
			<tr>
				<td align="left" style="padding-left:27px">신규비밀번호</td>
				<td><INPUT style="WIDTH: 120px" type=password name="passwd1" value="" maxLength="20"  class="input"></td>
				<td>확인</td>
				<td><INPUT style="WIDTH: 120px" type=password name="passwd2"  value=""maxLength="20"  class="input"></td>
			</tr>
			<tr>
				<td></td>
				<td colspan="3">(기존 비밀번호를 사용하시려면 입력하지 마세요.)</td>
			</tr>

			<tr>
				<td align="left" style="padding-left:14px"><font color="#F02800">*</font>이름</td>
				<td colspan="3"><B><?=$name?></B></td>
			</tr>
			<tr>
				<td align="left" style="padding-left:14px"><font color="#F02800">*</font>아이디</td>
				<td colspan="3"><B><?=$id?></B></td>
			</tr>
			<input type="hidden" name="loginType" value="<?=$loginType?>">
			
			
			
			<? if($_data->wholesalemember == 'Y' && $wholesaletype == 'Y'){ ?>
			<tr>
				<td align="left" style="padding-left:27px">사업자번호</td>
				<td colspan="3"><?=$comp_num?></td>
			</tr>
			<tr>
				<td align="left" style="padding-left:27px">대표자</td>
				<td colspan="3"><?=$comp_owner?></td>
			</tr>
			<tr>
				<td align="left" style="padding-left:27px">업태</td>
				<td colspan="3"><?=$comp_type1?></td>
			</tr>
			<tr>
				<td align="left" style="padding-left:27px">종목</td>
				<td colspan="3"><?=$comp_type2?></td>
			</tr>
			<? } ?>

			<? if($gubun == '기업'){ ?>
			<tr>
				<td align="left" style="padding-left:27px">사업자번호</td>
				<td colspan="3"><?=$bizno?></td>
			</tr>
			<tr>
				<td align="left" style="padding-left:27px">사업자/단체</td>
				<td colspan="3">
					<input type=radio id="groupSelect01" name="biz_gubun" value="corp" <?=($biz_gubun=="corp")? "checked" : "";?> class="radio">
					<label onMouseOver="style.textDecoration='underline'" onMouseOut="style.textDecoration='none'" for="groupSelect01">법인/단체 사업자</label>

					<input type=radio id="groupSelect02" name="biz_gubun" value="indi" <?=($biz_gubun=="indi")? "checked" : "";?> class="radio">
					<label onMouseOver="style.textDecoration='underline'" onMouseOut="style.textDecoration='none'" for="groupSelect02">개인사업자</label>

					<input type=radio id="groupSelect03" name="biz_gubun" value="simp" <?=($biz_gubun=="simp")? "checked" : "";?> class="radio">
					<label onMouseOver="style.textDecoration='underline'" onMouseOut="style.textDecoration='none'" for="groupSelect03">간이사업자</label>

					<input type=radio id="groupSelect04" name="biz_gubun" value="social" <?=($biz_gubun=="social")? "checked" : "";?> class="radio">
					<label onMouseOver="style.textDecoration='underline'" onMouseOut="style.textDecoration='none'" for="groupSelect04">사회공헌 단체</label>
				</td>
			</tr>
			<tr>
			<? } ?>
			
			<? if($_data->resno_type!="N"){?>
			<tr>
				<td align="left" style="padding-left:14px"><font color="#F02800">*</font>주민등록번호</td>
				<?if(($_data->resno_type=="M") || ($_data->resno_type=="Y" && (strlen($oldresno)==0 || strlen($oldresno)==41))){?>
				<td colspan="3"><INPUT type=text name="resno1" value="<?=$resno1?>" maxLength="6" onkeyup="return strnumkeyup2(this);" style="WIDTH:50px;;" class="input"> - <INPUT type=password name="resno2" value="<?=(strlen($oldresno)==13?$resno2:"")?>" maxLength="7" onkeyup="return strnumkeyup2(this);" style="WIDTH:58px;" class="input"></td>
				<?}else if($_data->resno_type=="Y"){?>
				<td colspan="3"><B><?=$resno1?> - <?=str_repeat("*",strlen($resno2))?></B></td>
				<?}?>
			</tr>
			<?}?>
			<? if($extconf['reqgender'] != 'H'){?>
			<tr>
				<td align="left" style="padding-left:14px"><font color="#F02800">*</font>성별</td>
				<td colspan="3"><?=$gender?></td>
			</tr>
			<?}?>
			<? if($extconf['reqbirth'] != 'H'){?>
			<tr>
				<td align="left" style="padding-left:14px"><font color="#F02800">*</font>생년월일</td>
				<td colspan="3"><?=$birth?></td>
			</tr>
			<?}?>
			
			<tr>
				<td align="left" style="padding-left:14px"><font color="#F02800">*</font>메일정보 수신여부</td>
				<td colspan="3"><INPUT type=radio name="news_mail_yn" value="Y" id="idx_news_mail_yn0" <?if($news_mail_yn=="Y")echo"checked";?> style="BORDER:none;"><LABEL onmouseover="style.textDecoration='underline'" style="CURSOR: hand" onmouseout="style.textDecoration='none'" for="idx_news_mail_yn0">받습니다.</LABEL> <INPUT type=radio name="news_mail_yn" value="N" id="idx_news_mail_yn1" <?if($news_mail_yn=="N")echo"checked";?> style="BORDER:none;"><LABEL onmouseover="style.textDecoration='underline'" style="CURSOR: hand" onmouseout="style.textDecoration='none'" for="idx_news_mail_yn1">받지 않습니다.</LABEL></td>
			</tr>
			<tr>
				<td align="left" style="padding-left:14px"><font color="#F02800">*</font>SMS정보 수신여부</td>
				<td colspan="3"><INPUT type=radio name="news_sms_yn" value="Y" id="idx_news_sms_yn0" <?if($news_sms_yn=="Y")echo"checked";?> style="BORDER:none;"><LABEL onmouseover="style.textDecoration='underline'" style="CURSOR: hand" onmouseout="style.textDecoration='none'" for="idx_news_sms_yn0">받습니다.</LABEL> <INPUT type=radio name="news_sms_yn" value="N" id="idx_news_sms_yn1" <?if($news_sms_yn=="N")echo"checked";?> style="BORDER:none;"><LABEL onmouseover="style.textDecoration='underline'" style="CURSOR: hand" onmouseout="style.textDecoration='none'" for="idx_news_sms_yn1">받지 않습니다.</LABEL></td>
			</tr>
			<!--tr>
				<td align="left" style="padding-left:14px"><font color="#F02800">*</font>집전화</td>
				<td colspan="3"><INPUT name="home_tel" value="<?=$home_tel?>" maxLength="15" style="WIDTH:120px;" class="input"></td>
			</tr-->
			<tr>
				<td align="left" style="padding-left:14px"><font color="#F02800">*</font>주소</td>
				<td colspan="3">

					<div style="overflow:hidden">
						<INPUT type="text" name="home_post1" id="home_post1" value="<?=$home_post1?>" readOnly style="WIDTH:60px;" class="input" /> 
						<A href="javascript:addr_search_for_daumapi('home_post1','home_addr1','home_addr2');"  class="btn_gray"><span>우편번호 검색</span></a>
					</div>
					<div style="margin:3px 0px;overflow:hidden"><INPUT type="text" name="home_addr1" id="home_addr1" maxLength="100" value="<?=$home_addr1?>" readOnly style="WIDTH:96%;" class="input" /></div>
					<div style="overflow:hidden"><INPUT type="text" name="home_addr2" id="home_addr2" maxLength="100" value="<?=htmlspecialchars($home_addr2,ENT_QUOTES)?>" style="WIDTH:96%;" class="input" /></div>

					<!--
					<table cellpadding="0" cellspacing="0" width="100%">
						<tr>
							<td>
								<INPUT type=text name="home_post1" value="<?=$home_post1?>" readOnly style="WIDTH:40px;;" class="input"> - <INPUT type=text name="home_post2" value="<?=$home_post2?>" readOnly style="WIDTH:40px;;" class="input"><a href="javascript:f_addr_search('form1','home_post','home_addr1',2);"><img src="<?=$Dir?>images/common/mbmodify/<?=$_data->design_mbmodify?>/memberjoin_skin1_btn2.gif" border="0" align="absmiddle" hspace="3"></a>
							</td>
						</tr>
						<tr>
							<td><INPUT type=text name="home_addr1" value="<?=$home_addr1?>" maxLength="100" readOnly style="WIDTH:80%;;" class="input"></td>
						</tr>
						<tr>
							<td><INPUT type=text name="home_addr2" value="<?=htmlspecialchars($home_addr2,ENT_QUOTES)?>" maxLength="100" style="WIDTH:80%;;" class="input"></td>
						</tr>
					</table>
					-->
				</td>
			</tr>
			<tr>
				<td align="left" style="padding-left:14px"><font color="#F02800">*</font>휴대폰</td>
				<td colspan="3">
					<INPUT type=text name="mobile" id="mobile" value="<?=$mobile?>" maxLength="13" style="WIDTH:120px;vertical-align:top" class="input" onkeypress="clearResult()" />
					<input type="hidden" name="result" id="result" value="1" />
					<a href="javascript:;" onclick="openPCCWindow()" style="display:inline-block;padding:0px 10px;line-height:33px;background:#999;color:#fff">변경</a>
				</td>
			</tr>
			<!--tr>
				<td align="left" style="padding-left:27px">회사주소</td>
				<td colspan="3">


					<div style="overflow:hidden">
						<INPUT type="text" name="office_post1" id="office_post1" value="<?=$office_post1?>" readOnly style="WIDTH:60px;" class="input"> 
						<A href="javascript:addr_search_for_daumapi('office_post1','office_addr1','office_addr2');" class="btn_gray"><span>우편번호 검색</span></a>
					</div>
					<div style="margin:3px 0px;overflow:hidden"><INPUT type="text" name="office_addr1" id="office_addr1" maxLength="100" value="<?=$office_addr1?>" readOnly style="WIDTH:96%;" class="input" /></div>
					<div style="overflow:hidden"><INPUT type="text" name="office_addr2" id="office_addr2" maxLength="100" value="<?=htmlspecialchars($office_addr2,ENT_QUOTES)?>" style="WIDTH:96%;" class="input" /></div>

-->
				<!--
				<table cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td><INPUT type=text name="office_post1" value="<?=$office_post1?>" readOnly style="WIDTH:40px;;" class="input"> - <INPUT type=text name="office_post2" value="<?=$office_post2?>" readOnly style="WIDTH:40px;;" class="input"><a href="javascript:f_addr_search('form1','office_post','office_addr1',2);"><img src="<?=$Dir?>images/common/mbmodify/<?=$_data->design_mbmodify?>/memberjoin_skin1_btn2.gif" border="0" align="absmiddle" hspace="3"></a></td>
				</tr>
				<tr>
					<td><INPUT type=text name="office_addr1" value="<?=$office_addr1?>" maxLength="100" readOnly style="WIDTH:80%;;" class="input"></td>
				</tr>
				<tr>
					<td><INPUT type=text name="office_addr2" value="<?=htmlspecialchars($office_addr2,ENT_QUOTES)?>" maxLength="100" style="WIDTH:80%;;" class="input"></td>
				</tr>
				</table>
				-->
				<!--</td>
			</tr-->
			<tr>
				<td align="left" style="padding-left:27px">회원구분</td>
				<td colspan="3">
					<input type=radio id="gubun01" name="gubun" value="일반" <?=($gubun=="일반")? "checked" : "";?> class="radio">
					<label for="gubun01">일반</label>
					<input type=radio id="gubun02" name="gubun" value="전문가" <?=($gubun=="전문가")? "checked" : "";?> class="radio">
					<label for="gubun02">전문가</label>
					<input type=radio id="gubun03" name="gubun" value="교수" <?=($gubun=="교수")? "checked" : "";?> class="radio">
					<label for="gubun03">교수</label>
					<input type=radio id="gubun04" name="gubun" value="학생" <?=($gubun=="학생")? "checked" : "";?> class="radio">
					<label for="gubun04">학생</label>
					<input type=radio id="gubun05" name="gubun" value="기업" <?=($gubun=="기업")? "checked" : "";?> class="radio">
					<label for="gubun05">기업</label>
				</td>
			</tr>
			<tr>
				<td align="left" style="padding-left:27px">소속</td>
				<td colspan="3">
					<INPUT type="text" name="sosok" id="sosok" value="<?=$sosok?>" style="WIDTH:96%;" class="input" />
				</td>
			</tr>
			<tr>
				<td align="left" style="padding-left:27px">직종</td>
				<td colspan="3">
					<select name="jikjong">
						<option value="">선택</option>
						<option value="1" <?=($jikjong=="1")? "selected":"";?>>광고대행사</option>
						<option value="2" <?=($jikjong=="2")? "selected":"";?>>프로덕션</option>
						<option value="4" <?=($jikjong=="4")? "selected":"";?>>포스트프로덕션</option>
						<option value="8" <?=($jikjong=="8")? "selected":"";?>>녹음/CM Song</option>
						<option value="16" <?=($jikjong=="16")? "selected":"";?>>촬영</option>
						<option value="32" <?=($jikjong=="32")? "selected":"";?>>조명</option>
						<option value="64" <?=($jikjong=="64")? "selected":"";?>>미술/셋트</option>
						<option value="128" <?=($jikjong=="128")? "selected":"";?>>아트디렉터</option>
						<option value="256" <?=($jikjong=="256")? "selected":"";?>>메이크업/코디</option>
						<option value="512" <?=($jikjong=="512")? "selected":"";?>>기획사/카피</option>
						<option value="1024" <?=($jikjong=="1024")? "selected":"";?>>필름/현상/NTC</option>
						<option value="2048" <?=($jikjong=="2048")? "selected":"";?>>광고관련단체</option>
						<option value="4096" <?=($jikjong=="4096")? "selected":"";?>>광고주</option>
						<option value="8192" <?=($jikjong=="8192")? "selected":"";?>>성우</option>
						<option value="16384" <?=($jikjong=="16384")? "selected":"";?>>해외코디</option>
						<option value="32768" <?=($jikjong=="32768")? "selected":"";?>>매체사</option>
						<option value="65536" <?=($jikjong=="65536")? "selected":"";?>>사진촬영</option>
						<option value="131072" <?=($jikjong=="131072")? "selected":"";?>>사진제판</option>
						<option value="262144" <?=($jikjong=="262144")? "selected":"";?>>디자인회사</option>
						<option value="524288" <?=($jikjong=="524288")? "selected":"";?>>인쇄</option>
						<option value="1048576" <?=($jikjong=="1048576")? "selected":"";?>>SP(옥외)</option>
						<option value="2097152" <?=($jikjong=="2097152")? "selected":"";?>>마케팅/리서치</option>
						<option value="4194304" <?=($jikjong=="4194304")? "selected":"";?>>이벤트</option>
						<option value="8388608" <?=($jikjong=="8388608")? "selected":"";?>>모델에이젼시</option>
						<option value="16777216" <?=($jikjong=="16777216")? "selected":"";?>>관련학과</option>
						<option value="33554432" <?=($jikjong=="33554432")? "selected":"";?>>기타</option>
						<option value="67108864" <?=($jikjong=="67108864")? "selected":"";?>>스토리보드</option>
						<option value="134217728" <?=($jikjong=="134217728")? "selected":"";?>>로케이션/헌팅</option>
					</select>
				</td>
			</tr>
			<tr>
				<td align="left" style="padding-left:27px">직군</td>
				<td colspan="3">
					<select name="jikgun">
						<option value="">선택</option>
						<option value="1" <?=($jikgun=="1")? "selected":"";?>>CD</option>
						<option value="2" <?=($jikgun=="2")? "selected":"";?>>PD</option>
						<option value="4" <?=($jikgun=="4")? "selected":"";?>>CW</option>
						<option value="8" <?=($jikgun=="8")? "selected":"";?>>디자이너</option>
						<option value="16" <?=($jikgun=="16")? "selected":"";?>>AE</option>
						<option value="32" <?=($jikgun=="32")? "selected":"";?>>마케팅</option>
						<option value="64" <?=($jikgun=="64")? "selected":"";?>>감독</option>
						<option value="128" <?=($jikgun=="128")? "selected":"";?>>조감독</option>
						<option value="256" <?=($jikgun=="256")? "selected":"";?>>조수</option>
						<option value="512" <?=($jikgun=="512")? "selected":"";?>>관리/사무</option>
						<option value="1024" <?=($jikgun=="1024")? "selected":"";?>>아트디렉터</option>
						<option value="2048" <?=($jikgun=="2048")? "selected":"";?>>플래너</option>
						<option value="4096" <?=($jikgun=="4096")? "selected":"";?>>TD</option>
						<option value="8192" <?=($jikgun=="8192")? "selected":"";?>>스타일리스트</option>
						<option value="16384" <?=($jikgun=="16384")? "selected":"";?>>칼라리스트</option>
						<option value="32768" <?=($jikgun=="32768")? "selected":"";?>>광고홍보</option>
						<option value="65536" <?=($jikgun=="65536")? "selected":"";?>>음악</option>
						<option value="131072" <?=($jikgun=="131072")? "selected":"";?>>학생</option>
						<option value="262144" <?=($jikgun=="262144")? "selected":"";?>>기타</option>
						<option value="524288" <?=($jikgun=="524288")? "selected":"";?>>가수</option>
						<option value="1048576" <?=($jikgun=="1048576")? "selected":"";?>>캐스팅디렉터</option>
					</select>
				</td>
			</tr>

			<?if($recom_ok=="Y") {?>
			<tr height="26">
				<td align="left" style="padding-left:27px">추천ID</td>
				<td colspan="3"><?=$str_rec?></td>
			</tr>
			<?}?>
<?
			if(strlen($straddform)>0) {
				echo $straddform;
			}
?>
			</table>

<div style="padding:30px;text-align:center;"><A HREF="javascript:CheckForm()" class="btn_grayB"><span>회원정보수정</span></A><!-- <A HREF="javascript:history.go(-1)"  class="btn_line">이전으로</A>--></div>
