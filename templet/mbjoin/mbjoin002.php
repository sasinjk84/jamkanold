<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr>
	<td style="padding-left:10;padding-right:10">
	<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<?if($_data->resno_type!="N" && strlen($adultauthid)>0){###### ������ ���̵� �����ϸ� �Ǹ����� �ȳ���Ʈ######?>
	<tr>
		<td>&nbsp;-&nbsp;&nbsp;�Է��Ͻ� �̸��� �ֹι�ȣ�� <font color="#F02800"><b>�Ǹ�Ȯ��</b></font>�� �Ǿ�� ȸ�������� �Ϸ��Ͻ� �� �ֽ��ϴ�.</td>
	</tr>
	<?}?>
	<tr>
		<td>&nbsp;-&nbsp;&nbsp;<font color="#F02800"><b>(��)�� �ʼ��Է� �׸��Դϴ�.</b></font></td>
	</tr>
	<tr>
		<td height="10"></td>
	</tr>
	<tr>
		<td>
		<table cellpadding="0" cellspacing="0" width="100%">
		<tr>
			<td>
			<table border="0" cellpadding="0" cellspacing="0" width="100%">
			<col width="150" align="right"></col>
			<col width="130" style="padding-left:5px;"></col>
			<col width="100" align="right"></col>
			<col style="padding-left:5px;"></col>
			<tr>
				<td height="1" bgcolor="#E6E6E6"></td>
				<td colspan="3" height="1" bgcolor="#E6E6E6"></td>
			</tr>
			<tr>
				<td height="5" colspan="4"></td>
			</tr>
			<tr>
				<td align="left" style="padding-left:14px"><font color="#F02800"><b>��</b></font><font color="#000000"><b>���̵�</b></font></td>
				<td colspan="3"><INPUT type=text name="id" value="<?=$id?>" maxLength="12" style="WIDTH:120px;BACKGROUND-COLOR:#F7F7F7;" class="input"><A href="javascript:idcheck();"><img src="<?=$Dir?>images/common/mbjoin/<?=$_data->design_mbjoin?>/memberjoin_skin2_btn1.gif" border="0" align="absmiddle" hspace="3"></a></td>
			</tr>
			<tr>
				<td height="10" colspan="4" background="<?=$Dir?>images/common/mbjoin/memberjoin_p_skin_line.gif"></td>
			</tr>
			<tr>
				<td align="left" style="padding-left:14px"><font color="#F02800"><b>��</b></font><font color="#000000"><b>��й�ȣ</b></font></td>
				<td><INPUT type=text type=password name="passwd1" value="<?=$passwd1?>" maxLength="20" style="WIDTH:120px;BACKGROUND-COLOR:#F7F7F7;" class="input"></td>
				<td align="left" style="padding-left:14px"><font color="#F02800"><b>��</b></font><font color="#000000"><b>��й�ȣȮ��</b></font></td>
				<td><INPUT type=text type=password name="passwd2" value="<?=$passwd2?>" maxLength="20" style="WIDTH:120px;BACKGROUND-COLOR:#F7F7F7;" class="input"></td>
			</tr>
			<tr>
				<td HEIGHT="10" colspan="4" background="<?=$Dir?>images/common/mbjoin/memberjoin_p_skin_line.gif"></td>
			</tr>
			<tr>
				<td align="left" style="padding-left:14px"><font color="#F02800"><b>��</b></font><font color="#000000"><b>�̸�</b></font></td>
				<td colspan="3"><INPUT type=text name="name" value="<?=$name?>" maxLength="15" style="WIDTH:120px;BACKGROUND-COLOR:#F7F7F7;" class="input"></td>
			</tr>
			<tr>
				<td height="10" colspan="4" background="<?=$Dir?>images/common/mbjoin/memberjoin_p_skin_line.gif"></td>
			</tr>
			<?if($_data->resno_type!="N"){?>
			<tr>
				<td align="left" style="padding-left:14px"><font color="#F02800"><b>��</b></font><font color="#000000"><b>�ֹε�Ϲ�ȣ</b></font></td>
				<td colspan="3"><INPUT type=text name="resno1" value="<?=$resno1?>" maxLength="6" onkeyup="return strnumkeyup2(this);" style="WIDTH:50px;BACKGROUND-COLOR:#F7F7F7;" class="input"> - <INPUT type=password name="resno2" value="<?=$resno2?>" maxLength="7" onkeyup="return strnumkeyup2(this);" style="WIDTH:58px;BACKGROUND-COLOR:#F7F7F7;" class="input"></td>
			</tr>
			<tr>
				<td height="10" colspan="4" background="<?=$Dir?>images/common/mbjoin/memberjoin_p_skin_line.gif"></td>
			</tr>
			<?}?>
			<? if($extconf['reqgender'] == 'Y'){?>
			<tr>
				<td align="left" style="padding-left:14px"><font color="#F02800"><b>��</b></font><font color="#000000"><b>����</b></font></td>
				<td colspan="3"><INPUT type="radio" name="gender" value="1">���� / <INPUT type="radio" name="gender" value="2">����</td>
			</tr>
			<tr>
				<td height="10" colspan="4" background="<?=$Dir?>images/common/mbjoin/memberjoin_p_skin_line.gif"></td>
			</tr>
			<? }?>
			<? if($extconf['reqbirth'] == 'Y'){?>
			<tr>
				<td align="left" style="padding-left:14px"><font color="#F02800"><b>��</b></font><font color="#000000"><b>�������</b></font></td>
				<td colspan="3"><INPUT type="text" name="birth" value="" maxLength="10" style="WIDTH:50px;BACKGROUND-COLOR:#F7F7F7;" class="input"> ( ex : <?=date('Y-m-d')?> )</td>
			</tr>
			<tr>
				<td height="10" colspan="4" background="<?=$Dir?>images/common/mbjoin/memberjoin_p_skin_line.gif"></td>
			</tr>
			<? }?>
			<tr>
				<td align="left" style="padding-left:14px"><font color="#F02800"><b>��</b></font><font color="#000000"><b>�̸���</b></font></td>
				<td colspan="3"><INPUT type=text name="email" value="<?=$email?>" maxLength="100" style="WIDTH:80%;BACKGROUND-COLOR:#F7F7F7;" class="input"></td>
			</tr>
			<tr>
				<td height="10" colspan="4" background="<?=$Dir?>images/common/mbjoin/memberjoin_p_skin_line.gif"></td>
			</tr>
			<tr>
				<td align="left" style="padding-left:14px"><font color="#F02800"><b>��</b></font><font color="#000000"><b>�������� ���ſ���</b></font></td>
				<td colspan="3"><INPUT type=radio name="news_mail_yn" value="Y" id="idx_news_mail_yn0" <?if($news_mail_yn=="Y")echo"checked";?> style="BORDER:none;"><LABEL onmouseover="style.textDecoration='underline'" style="CURSOR: hand" onmouseout="style.textDecoration='none'" for="idx_news_mail_yn0">�޽��ϴ�.</LABEL> <INPUT type=radio name="news_mail_yn" value="N" id="idx_news_mail_yn1" <?if($news_mail_yn=="N")echo"checked";?> style="BORDER:none;"><LABEL onmouseover="style.textDecoration='underline'" style="CURSOR: hand" onmouseout="style.textDecoration='none'" for="idx_news_mail_yn1">���� �ʽ��ϴ�.</LABEL></td>
			</tr>
			<tr>
				<td height="10" colspan="4" background="<?=$Dir?>images/common/mbjoin/memberjoin_p_skin_line.gif"></td>
			</tr>
			<tr>
				<td align="left" style="padding-left:14px"><font color="#F02800"><b>��</b></font><font color="#000000"><b>SMS���� ���ſ���</b></font></td>
				<td colspan="3"><INPUT type=radio name="news_sms_yn" value="Y" id="idx_news_sms_yn0" <?if($news_sms_yn=="Y")echo"checked";?> style="BORDER:none;"><LABEL onmouseover="style.textDecoration='underline'" style="CURSOR: hand" onmouseout="style.textDecoration='none'" for="idx_news_sms_yn0">�޽��ϴ�.</LABEL> <INPUT type=radio name="news_sms_yn" value="N" id="idx_news_sms_yn1" <?if($news_sms_yn=="N")echo"checked";?> style="BORDER:none;"><LABEL onmouseover="style.textDecoration='underline'" style="CURSOR: hand" onmouseout="style.textDecoration='none'" for="idx_news_sms_yn1">���� �ʽ��ϴ�.</LABEL></td>
			</tr>
			<tr>
				<td height="10" colspan="4" background="<?=$Dir?>images/common/mbjoin/memberjoin_p_skin_line.gif"></td>
			</tr>
			<tr>
				<td align="left" style="padding-left:14px"><font color="#F02800"><b>��</b></font><font color="#000000"><b>����ȭ</b></font></td>
				<td colspan="3"><INPUT type=text name="home_tel" value="<?=$home_tel?>" maxLength="15" style="WIDTH:120px;BACKGROUND-COLOR:#F7F7F7;" class="input"></td>
			</tr>
			<tr>
				<td height="10" colspan="4" background="<?=$Dir?>images/common/mbjoin/memberjoin_p_skin_line.gif"></td>
			</tr>
			<tr>
				<td align="left" style="padding-left:14px"><font color="#F02800"><b>��</b></font><font color="#000000"><b>���ּ�</b></font></td>
				<td colspan="3">
				<table cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td><INPUT type=text name="home_post1" value="<?=$home_post1?>" readOnly style="WIDTH:40px;BACKGROUND-COLOR:#F7F7F7;" class="input"> - <INPUT type=text name="home_post2" value="<?=$home_post2?>" readOnly style="WIDTH:40px;BACKGROUND-COLOR:#F7F7F7;" class="input"><a href="javascript:f_addr_search('form1','home_post','home_addr1',2);"><img src="<?=$Dir?>images/common/mbjoin/<?=$_data->design_mbjoin?>/memberjoin_skin2_btn2.gif" border="0" align="absmiddle" hspace="3"></a></td>
				</tr>
				<tr>
					<td><INPUT type=text name="home_addr1" value="<?=$home_addr1?>" maxLength="100" readOnly style="WIDTH:80%;BACKGROUND-COLOR:#F7F7F7;" class="input"></td>
				</tr>
				<tr>
					<td><INPUT type=text name="home_addr2" value="<?=$home_addr2?>" maxLength="100" style="WIDTH:80%;BACKGROUND-COLOR:#F7F7F7;" class="input"></td>
				</tr>
				</table>
				</td>
			</tr>
			<tr>
				<td height="10" colspan="4" background="<?=$Dir?>images/common/mbjoin/memberjoin_p_skin_line.gif"></td>
			</tr>
			<tr>
				<td align="left" style="padding-left:14px"><font color="#F02800"><b>��</b></font><font color="#000000"><b>�����ȭ(�޴���)</b></font></td>
				<td colspan="3"><INPUT type=text name="mobile" value="<?=$mobile?>" maxLength="15" style="WIDTH:120px;BACKGROUND-COLOR:#F7F7F7;" class="input"></td>
			</tr>
			<tr>
				<td height="10" colspan="4" background="<?=$Dir?>images/common/mbjoin/memberjoin_p_skin_line.gif"></td>
			</tr>
			<tr>
				<td align="left" style="padding-left:27px"><font color="#000000"><b>ȸ���ּ�</b></font></td>
				<td colspan="3">
				<table cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td><INPUT type=text name="office_post1" value="<?=$office_post1?>" readOnly style="WIDTH:40px;BACKGROUND-COLOR:#F7F7F7;" class="input"> - <INPUT type=text name="office_post2" value="<?=$office_post2?>" readOnly style="WIDTH:40px;BACKGROUND-COLOR:#F7F7F7;" class="input"><a href="javascript:f_addr_search('form1','office_post','office_addr1',2);"><img src="<?=$Dir?>images/common/mbjoin/<?=$_data->design_mbjoin?>/memberjoin_skin2_btn2.gif" border="0" align="absmiddle" hspace="3"></a></td>
				</tr>
				<tr>
					<td><INPUT type=text name="office_addr1" value="<?=$office_addr1?>" maxLength="100" readOnly style="WIDTH:80%;BACKGROUND-COLOR:#F7F7F7;" class="input"></td>
				</tr>
				<tr>
					<td><INPUT type=text name="office_addr2" value="<?=$office_addr2?>" maxLength="100" style="WIDTH:80%;BACKGROUND-COLOR:#F7F7F7;" class="input"></td>
				</tr>
				</table>
				</td>
			</tr>
<?	
if($recom_ok=="Y") {
	if($recom_url_ok=="Y" && $_COOKIE['url_id'] != ""){
		if($_data->recom_addreserve >0){
?>
			<tr>
				<td height="10" colspan="4" background="<?=$Dir?>images/common/mbjoin/memberjoin_p_skin_line.gif"></td>
			</tr>
			<tr>
				<td align="left" style="padding-left:27px"><font color="#000000"><b>�߰�������</b></font></td>
				<td colspan="3"><b><?=$_COOKIE['url_name']?>(<?=$_COOKIE['url_id']?>)</b>���� �ʴ�� <b><font color="#FD9999"> ������ <?=$_data->recom_addreserve?>��</font></b>�� �߰� ������ �帳�ϴ�.<input type="hidden" name="rec_id" value="<?=$_COOKIE['url_id']?>"></td>
			</tr>
<?
		}else{
?>
			<tr>
				<td height="10" colspan="4" background="<?=$Dir?>images/common/mbjoin/memberjoin_p_skin_line.gif"></td>
			</tr>
			<tr>
				<td align="left" style="padding-left:27px"><font color="#000000"><b>��õ��</b></font></td>
				<td colspan="3"><b><?=$_COOKIE['url_name']?>(<?=$_COOKIE['url_id']?>)</b>���� �ʴ븦 �޾ҽ��ϴ�.<input type="hidden" name="rec_id" value="<?=$_COOKIE['url_id']?>" style="WIDTH:120px;BACKGROUND-COLOR:#F7F7F7;"></td>
			</tr>
<?
		}
	}else{
?>
			<tr>
				<td height="10" colspan="4" background="<?=$Dir?>images/common/mbjoin/memberjoin_p_skin_line.gif"></td>
			</tr>
			<tr>
				<td align="left" style="padding-left:27px"><font color="#000000"><b>��õID</b></font></td>
				<td colspan="3"><INPUT type="text" name="rec_id" maxLength="12"  value="<?=$rec_id?>" style="WIDTH:120px;BACKGROUND-COLOR:#F7F7F7;" class="input"></td>
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
			</table>
			</td>
		</tr>

		</table>
		</td>
	</tr>
	<tr>
		<td height="20"></td>
	</tr>
	<tr>
		<td align="center"><a href="javascript:CheckForm();"><img src="<?=$Dir?>images/common/mbjoin/002/memberjoin_skin2_btn3.gif" border="0"></a><a href="javascript:history.go(-1);";><img src="<?=$Dir?>images/common/mbjoin/002/memberjoin_skin2_btn4.gif" border="0" hspace="6"></a></td>
	</tr>
	<tr>
		<td height="20"></td>
	</tr>
	</table>
	</td>
</tr>
</table>