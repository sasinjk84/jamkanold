<script>
<!--
$j(document).on("click", "#gubun05", function() {
	if(confirm("����� ȸ������ �ű� �����ϼž� �մϴ�. �����Ͻðڽ��ϱ�?")){
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
		<td>&nbsp;- &nbsp;<font color="#F02800"><b>(��)�� �ʼ��Է� �׸��Դϴ�.</b></font><br>
		&nbsp;- &nbsp;ȸ�� ������ �����Ͻ� �� �������� ��ư�� �����ֽʽÿ�.<br>
<!--		<?if($_data->memberout_type=="Y" || $_data->memberout_type=="O") {?>
		&nbsp;- &nbsp;�ش� ���θ����� ȸ��Ż�� ���Ͻø� <a href="JavaScript:memberout()">[ȸ��Ż��]</a>�� �����ֽʽÿ�.
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
				<td align="left" style="padding-left:14px"><font color="#F02800">*</font>�̸���</td>
				<td colspan="3"><INPUT type=text name="email" value="<?=$email?>" maxLength="100" style="WIDTH:80%;background:#f5f5f5" class="input" readonly /></td>
			</tr>
			<?
			if($passwd!=""){
			?>
			<tr>
				<td align="left" style="padding-left:14px"><font color="#F02800">*</font>������й�ȣ</td>
				<td colspan="3"><input type=password name="oldpasswd" value="" maxlength="20"  class="input"> ���� ������� ��й�ȣ�� �Է��ϼ���.</td>
			</tr>
			<? } ?>
			<tr>
				<td align="left" style="padding-left:27px">�űԺ�й�ȣ</td>
				<td><INPUT style="WIDTH: 120px" type=password name="passwd1" value="" maxLength="20"  class="input"></td>
				<td>Ȯ��</td>
				<td><INPUT style="WIDTH: 120px" type=password name="passwd2"  value=""maxLength="20"  class="input"></td>
			</tr>
			<tr>
				<td></td>
				<td colspan="3">(���� ��й�ȣ�� ����Ͻ÷��� �Է����� ������.)</td>
			</tr>

			<tr>
				<td align="left" style="padding-left:14px"><font color="#F02800">*</font>�̸�</td>
				<td colspan="3"><B><?=$name?></B></td>
			</tr>
			<tr>
				<td align="left" style="padding-left:14px"><font color="#F02800">*</font>���̵�</td>
				<td colspan="3"><B><?=$id?></B></td>
			</tr>
			<input type="hidden" name="loginType" value="<?=$loginType?>">
			
			
			
			<? if($_data->wholesalemember == 'Y' && $wholesaletype == 'Y'){ ?>
			<tr>
				<td align="left" style="padding-left:27px">����ڹ�ȣ</td>
				<td colspan="3"><?=$comp_num?></td>
			</tr>
			<tr>
				<td align="left" style="padding-left:27px">��ǥ��</td>
				<td colspan="3"><?=$comp_owner?></td>
			</tr>
			<tr>
				<td align="left" style="padding-left:27px">����</td>
				<td colspan="3"><?=$comp_type1?></td>
			</tr>
			<tr>
				<td align="left" style="padding-left:27px">����</td>
				<td colspan="3"><?=$comp_type2?></td>
			</tr>
			<? } ?>

			<? if($gubun == '���'){ ?>
			<tr>
				<td align="left" style="padding-left:27px">����ڹ�ȣ</td>
				<td colspan="3"><?=$bizno?></td>
			</tr>
			<tr>
				<td align="left" style="padding-left:27px">�����/��ü</td>
				<td colspan="3">
					<input type=radio id="groupSelect01" name="biz_gubun" value="corp" <?=($biz_gubun=="corp")? "checked" : "";?> class="radio">
					<label onMouseOver="style.textDecoration='underline'" onMouseOut="style.textDecoration='none'" for="groupSelect01">����/��ü �����</label>

					<input type=radio id="groupSelect02" name="biz_gubun" value="indi" <?=($biz_gubun=="indi")? "checked" : "";?> class="radio">
					<label onMouseOver="style.textDecoration='underline'" onMouseOut="style.textDecoration='none'" for="groupSelect02">���λ����</label>

					<input type=radio id="groupSelect03" name="biz_gubun" value="simp" <?=($biz_gubun=="simp")? "checked" : "";?> class="radio">
					<label onMouseOver="style.textDecoration='underline'" onMouseOut="style.textDecoration='none'" for="groupSelect03">���̻����</label>

					<input type=radio id="groupSelect04" name="biz_gubun" value="social" <?=($biz_gubun=="social")? "checked" : "";?> class="radio">
					<label onMouseOver="style.textDecoration='underline'" onMouseOut="style.textDecoration='none'" for="groupSelect04">��ȸ���� ��ü</label>
				</td>
			</tr>
			<tr>
			<? } ?>
			
			<? if($_data->resno_type!="N"){?>
			<tr>
				<td align="left" style="padding-left:14px"><font color="#F02800">*</font>�ֹε�Ϲ�ȣ</td>
				<?if(($_data->resno_type=="M") || ($_data->resno_type=="Y" && (strlen($oldresno)==0 || strlen($oldresno)==41))){?>
				<td colspan="3"><INPUT type=text name="resno1" value="<?=$resno1?>" maxLength="6" onkeyup="return strnumkeyup2(this);" style="WIDTH:50px;;" class="input"> - <INPUT type=password name="resno2" value="<?=(strlen($oldresno)==13?$resno2:"")?>" maxLength="7" onkeyup="return strnumkeyup2(this);" style="WIDTH:58px;" class="input"></td>
				<?}else if($_data->resno_type=="Y"){?>
				<td colspan="3"><B><?=$resno1?> - <?=str_repeat("*",strlen($resno2))?></B></td>
				<?}?>
			</tr>
			<?}?>
			<? if($extconf['reqgender'] != 'H'){?>
			<tr>
				<td align="left" style="padding-left:14px"><font color="#F02800">*</font>����</td>
				<td colspan="3"><?=$gender?></td>
			</tr>
			<?}?>
			<? if($extconf['reqbirth'] != 'H'){?>
			<tr>
				<td align="left" style="padding-left:14px"><font color="#F02800">*</font>�������</td>
				<td colspan="3"><?=$birth?></td>
			</tr>
			<?}?>
			
			<tr>
				<td align="left" style="padding-left:14px"><font color="#F02800">*</font>�������� ���ſ���</td>
				<td colspan="3"><INPUT type=radio name="news_mail_yn" value="Y" id="idx_news_mail_yn0" <?if($news_mail_yn=="Y")echo"checked";?> style="BORDER:none;"><LABEL onmouseover="style.textDecoration='underline'" style="CURSOR: hand" onmouseout="style.textDecoration='none'" for="idx_news_mail_yn0">�޽��ϴ�.</LABEL> <INPUT type=radio name="news_mail_yn" value="N" id="idx_news_mail_yn1" <?if($news_mail_yn=="N")echo"checked";?> style="BORDER:none;"><LABEL onmouseover="style.textDecoration='underline'" style="CURSOR: hand" onmouseout="style.textDecoration='none'" for="idx_news_mail_yn1">���� �ʽ��ϴ�.</LABEL></td>
			</tr>
			<tr>
				<td align="left" style="padding-left:14px"><font color="#F02800">*</font>SMS���� ���ſ���</td>
				<td colspan="3"><INPUT type=radio name="news_sms_yn" value="Y" id="idx_news_sms_yn0" <?if($news_sms_yn=="Y")echo"checked";?> style="BORDER:none;"><LABEL onmouseover="style.textDecoration='underline'" style="CURSOR: hand" onmouseout="style.textDecoration='none'" for="idx_news_sms_yn0">�޽��ϴ�.</LABEL> <INPUT type=radio name="news_sms_yn" value="N" id="idx_news_sms_yn1" <?if($news_sms_yn=="N")echo"checked";?> style="BORDER:none;"><LABEL onmouseover="style.textDecoration='underline'" style="CURSOR: hand" onmouseout="style.textDecoration='none'" for="idx_news_sms_yn1">���� �ʽ��ϴ�.</LABEL></td>
			</tr>
			<!--tr>
				<td align="left" style="padding-left:14px"><font color="#F02800">*</font>����ȭ</td>
				<td colspan="3"><INPUT name="home_tel" value="<?=$home_tel?>" maxLength="15" style="WIDTH:120px;" class="input"></td>
			</tr-->
			<tr>
				<td align="left" style="padding-left:14px"><font color="#F02800">*</font>�ּ�</td>
				<td colspan="3">

					<div style="overflow:hidden">
						<INPUT type="text" name="home_post1" id="home_post1" value="<?=$home_post1?>" readOnly style="WIDTH:60px;" class="input" /> 
						<A href="javascript:addr_search_for_daumapi('home_post1','home_addr1','home_addr2');"  class="btn_gray"><span>�����ȣ �˻�</span></a>
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
				<td align="left" style="padding-left:14px"><font color="#F02800">*</font>�޴���</td>
				<td colspan="3">
					<INPUT type=text name="mobile" id="mobile" value="<?=$mobile?>" maxLength="13" style="WIDTH:120px;vertical-align:top" class="input" onkeypress="clearResult()" />
					<input type="hidden" name="result" id="result" value="1" />
					<a href="javascript:;" onclick="openPCCWindow()" style="display:inline-block;padding:0px 10px;line-height:33px;background:#999;color:#fff">����</a>
				</td>
			</tr>
			<!--tr>
				<td align="left" style="padding-left:27px">ȸ���ּ�</td>
				<td colspan="3">


					<div style="overflow:hidden">
						<INPUT type="text" name="office_post1" id="office_post1" value="<?=$office_post1?>" readOnly style="WIDTH:60px;" class="input"> 
						<A href="javascript:addr_search_for_daumapi('office_post1','office_addr1','office_addr2');" class="btn_gray"><span>�����ȣ �˻�</span></a>
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
				<td align="left" style="padding-left:27px">ȸ������</td>
				<td colspan="3">
					<input type=radio id="gubun01" name="gubun" value="�Ϲ�" <?=($gubun=="�Ϲ�")? "checked" : "";?> class="radio">
					<label for="gubun01">�Ϲ�</label>
					<input type=radio id="gubun02" name="gubun" value="������" <?=($gubun=="������")? "checked" : "";?> class="radio">
					<label for="gubun02">������</label>
					<input type=radio id="gubun03" name="gubun" value="����" <?=($gubun=="����")? "checked" : "";?> class="radio">
					<label for="gubun03">����</label>
					<input type=radio id="gubun04" name="gubun" value="�л�" <?=($gubun=="�л�")? "checked" : "";?> class="radio">
					<label for="gubun04">�л�</label>
					<input type=radio id="gubun05" name="gubun" value="���" <?=($gubun=="���")? "checked" : "";?> class="radio">
					<label for="gubun05">���</label>
				</td>
			</tr>
			<tr>
				<td align="left" style="padding-left:27px">�Ҽ�</td>
				<td colspan="3">
					<INPUT type="text" name="sosok" id="sosok" value="<?=$sosok?>" style="WIDTH:96%;" class="input" />
				</td>
			</tr>
			<tr>
				<td align="left" style="padding-left:27px">����</td>
				<td colspan="3">
					<select name="jikjong">
						<option value="">����</option>
						<option value="1" <?=($jikjong=="1")? "selected":"";?>>��������</option>
						<option value="2" <?=($jikjong=="2")? "selected":"";?>>���δ���</option>
						<option value="4" <?=($jikjong=="4")? "selected":"";?>>����Ʈ���δ���</option>
						<option value="8" <?=($jikjong=="8")? "selected":"";?>>����/CM Song</option>
						<option value="16" <?=($jikjong=="16")? "selected":"";?>>�Կ�</option>
						<option value="32" <?=($jikjong=="32")? "selected":"";?>>����</option>
						<option value="64" <?=($jikjong=="64")? "selected":"";?>>�̼�/��Ʈ</option>
						<option value="128" <?=($jikjong=="128")? "selected":"";?>>��Ʈ����</option>
						<option value="256" <?=($jikjong=="256")? "selected":"";?>>����ũ��/�ڵ�</option>
						<option value="512" <?=($jikjong=="512")? "selected":"";?>>��ȹ��/ī��</option>
						<option value="1024" <?=($jikjong=="1024")? "selected":"";?>>�ʸ�/����/NTC</option>
						<option value="2048" <?=($jikjong=="2048")? "selected":"";?>>������ô�ü</option>
						<option value="4096" <?=($jikjong=="4096")? "selected":"";?>>������</option>
						<option value="8192" <?=($jikjong=="8192")? "selected":"";?>>����</option>
						<option value="16384" <?=($jikjong=="16384")? "selected":"";?>>�ؿ��ڵ�</option>
						<option value="32768" <?=($jikjong=="32768")? "selected":"";?>>��ü��</option>
						<option value="65536" <?=($jikjong=="65536")? "selected":"";?>>�����Կ�</option>
						<option value="131072" <?=($jikjong=="131072")? "selected":"";?>>��������</option>
						<option value="262144" <?=($jikjong=="262144")? "selected":"";?>>������ȸ��</option>
						<option value="524288" <?=($jikjong=="524288")? "selected":"";?>>�μ�</option>
						<option value="1048576" <?=($jikjong=="1048576")? "selected":"";?>>SP(����)</option>
						<option value="2097152" <?=($jikjong=="2097152")? "selected":"";?>>������/����ġ</option>
						<option value="4194304" <?=($jikjong=="4194304")? "selected":"";?>>�̺�Ʈ</option>
						<option value="8388608" <?=($jikjong=="8388608")? "selected":"";?>>�𵨿�������</option>
						<option value="16777216" <?=($jikjong=="16777216")? "selected":"";?>>�����а�</option>
						<option value="33554432" <?=($jikjong=="33554432")? "selected":"";?>>��Ÿ</option>
						<option value="67108864" <?=($jikjong=="67108864")? "selected":"";?>>���丮����</option>
						<option value="134217728" <?=($jikjong=="134217728")? "selected":"";?>>�����̼�/����</option>
					</select>
				</td>
			</tr>
			<tr>
				<td align="left" style="padding-left:27px">����</td>
				<td colspan="3">
					<select name="jikgun">
						<option value="">����</option>
						<option value="1" <?=($jikgun=="1")? "selected":"";?>>CD</option>
						<option value="2" <?=($jikgun=="2")? "selected":"";?>>PD</option>
						<option value="4" <?=($jikgun=="4")? "selected":"";?>>CW</option>
						<option value="8" <?=($jikgun=="8")? "selected":"";?>>�����̳�</option>
						<option value="16" <?=($jikgun=="16")? "selected":"";?>>AE</option>
						<option value="32" <?=($jikgun=="32")? "selected":"";?>>������</option>
						<option value="64" <?=($jikgun=="64")? "selected":"";?>>����</option>
						<option value="128" <?=($jikgun=="128")? "selected":"";?>>������</option>
						<option value="256" <?=($jikgun=="256")? "selected":"";?>>����</option>
						<option value="512" <?=($jikgun=="512")? "selected":"";?>>����/�繫</option>
						<option value="1024" <?=($jikgun=="1024")? "selected":"";?>>��Ʈ����</option>
						<option value="2048" <?=($jikgun=="2048")? "selected":"";?>>�÷���</option>
						<option value="4096" <?=($jikgun=="4096")? "selected":"";?>>TD</option>
						<option value="8192" <?=($jikgun=="8192")? "selected":"";?>>��Ÿ�ϸ���Ʈ</option>
						<option value="16384" <?=($jikgun=="16384")? "selected":"";?>>Į�󸮽�Ʈ</option>
						<option value="32768" <?=($jikgun=="32768")? "selected":"";?>>����ȫ��</option>
						<option value="65536" <?=($jikgun=="65536")? "selected":"";?>>����</option>
						<option value="131072" <?=($jikgun=="131072")? "selected":"";?>>�л�</option>
						<option value="262144" <?=($jikgun=="262144")? "selected":"";?>>��Ÿ</option>
						<option value="524288" <?=($jikgun=="524288")? "selected":"";?>>����</option>
						<option value="1048576" <?=($jikgun=="1048576")? "selected":"";?>>ĳ���õ���</option>
					</select>
				</td>
			</tr>

			<?if($recom_ok=="Y") {?>
			<tr height="26">
				<td align="left" style="padding-left:27px">��õID</td>
				<td colspan="3"><?=$str_rec?></td>
			</tr>
			<?}?>
<?
			if(strlen($straddform)>0) {
				echo $straddform;
			}
?>
			</table>

<div style="padding:30px;text-align:center;"><A HREF="javascript:CheckForm()" class="btn_grayB"><span>ȸ����������</span></A><!-- <A HREF="javascript:history.go(-1)"  class="btn_line">��������</A>--></div>
