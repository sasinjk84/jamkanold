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

	
	<p class="noticeWrap"><span class="red">(��)�� �ʼ��Է� �׸��Դϴ�.</span></p>
	<div class="joinCompanyWrap">
	<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<?if($_data->resno_type!="N" && strlen($adultauthid)>0){###### ������ ���̵� �����ϸ� �Ǹ����� �ȳ���Ʈ######?>
	<tr>
		<td>&nbsp;-&nbsp;&nbsp;�Է��Ͻ� �̸��� �ֹι�ȣ�� <font color="#F02800"><b>�Ǹ�Ȯ���� �Ǿ�� ȸ�������� �Ϸ��Ͻ� �� �ֽ��ϴ�.</td>
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
				<th><span class="red">��</span>ID</th>
				<td colspan="3"><INPUT type=text name="email" value="<?=$email?>" maxLength="100" style="WIDTH:50%;" class="input" placeholder=""> <A href="javascript:mailcheck();" class="btn_gray"><span>���� ����</span></a>
				<p style="color:#F02800;padding:10px 0px">*Naver ����(aaa@naver.com) �� �ٸ� �����ּҷ� �����Ͽ� ���� �����մϴ�.</p>
				</td>
			</tr>
			<!--
			<tr>
				<th ><span class="red">��</span>���̵�</th>
				<td colspan="3"><INPUT type=text name="id" value="<?=$id?>" maxLength="12" style="WIDTH:120px;" class="input"> <A href="javascript:idcheck();" class="btn_gray"><span>���̵� �ߺ�Ȯ��</span></a></td>
			</tr>
			<tr>
				<th><span class="red">��</span>�̸���</th>
				<td colspan="3"><INPUT type=text name="email" value="<?=$email?>" maxLength="100" style="WIDTH:30%;" class="input"> <A href="javascript:mailcheck();" class="btn_gray"><span>�̸��� �ߺ�Ȯ��</span></a></td>
			</tr>
			-->
			<tr>
				<th><span class="red">��</span>��й�ȣ</th>
				<td><INPUT type=password name="passwd1" value="<?=$passwd1?>" maxLength="20" style="WIDTH:120px;" class="input"></td>
				<th><span class="red">��</span>��й�ȣȮ��</th>
				<td><INPUT type=password name="passwd2" value="<?=$passwd2?>" maxLength="20" style="WIDTH:120px;" class="input"></td>
			</tr>
			<tr>
				<th><span class="red">��</span>�޴���</th>
				<td colspan="3"><INPUT type=text maxLength="15" name="mobile" value="<?=$mobile?>" style="WIDTH:275px;border: #F02800 1px solid;" class="input"> <A href="javascript:idcheck();" class="btn_red"><span>��������</span></a></td>
			</tr>
			<tr>
				<th><span class="red">��</span>�̸�</th>
				<td colspan="3"><INPUT type=text name="name" value="<?=$name?>" maxLength="15" style="WIDTH:275px;" class="input"></td>
			</tr>
			<? if($_data->resno_type!="N"){?>
			<tr>
				<th><span class="red">��</span>�ֹε�Ϲ�ȣ</th>
				<td colspan="3"><INPUT type=text name="resno1" value="<?=$resno1?>" maxLength="6" onkeyup="return strnumkeyup2(this);" style="WIDTH:50px;" class="input"> - <INPUT type=password name="resno2" value="<?=$resno2?>" maxLength="7" onkeyup="return strnumkeyup2(this);" style="WIDTH:58px;" class="input"></td>
			</tr>
			<? }?>
			<? if($extconf['reqgender'] != 'H'){?>
			<tr>
				<? if($extconf['reqgender'] == 'Y'){?><th><span class="red">��</span><?}else{?><td align="left" style="padding-left:27px;"><?}?>����</th>
				<td colspan="3"><INPUT type="radio" name="gender" value="1" class="radio">���� &nbsp;&nbsp; <INPUT type="radio" name="gender" value="2" class="radio">����</td>
			</tr>
			<? }?>
			<? if($extconf['reqbirth'] != 'H'){?>
			<tr>
				<th><? if($extconf['reqbirth'] == 'Y'){?><span class="red">��</span><?}?>�������</th>
				<td colspan="3"><INPUT type="text" name="birth" value="" maxLength="10" style="WIDTH:275px; " class="input"> ( ex : <?=date('Y-m-d')?> )</td>
			</tr>
			<? }?>
			<tr>
				<th><span class="red">��</span>����ȭ</th>
				<td colspan="3"><INPUT type=text name="home_tel" value="<?=$home_tel?>" maxLength="15" style="WIDTH:275px;" class="input"></td>
			</tr>
			<tr>
				<th><span class="red">��</span>���ּ�</th>
				<td colspan="3">
				<table cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td style="border:0px;">
						<!--<INPUT type=text name="home_post1" value="<?=$home_post1?>" readOnly style="WIDTH:40px;" class="input"> - <INPUT type=text name="home_post2" value="<?=$home_post2?>" readOnly style="WIDTH:40px;" class="input"><a href="javascript:f_addr_search('form1','home_post','home_addr1',2);"><img src="<?=$Dir?>images/common/mbjoin/<?=$_data->design_mbjoin?>/memberjoin_skin1_btn2.gif" border="0" align="absmiddle" hspace="3"></a>-->



						<div style="overflow:hidden">
							<INPUT type="text" name="home_post1" id="home_post1" readOnly style="WIDTH:60px;" class="input"> 
							<A href="javascript:addr_search_for_daumapi('home_post1','home_addr1','home_addr2');"  class="btn_line basic_button grayBtn"><span>�ּ�ã��</span></a>
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
				<th style="padding-left:16px">ȸ���ּ�</th>
				<td colspan="3">

					<div style="overflow:hidden">
						<INPUT type="text" name="office_post1" id="office_post1" readOnly style="WIDTH:60px;" class="input"> 
						<A href="javascript:addr_search_for_daumapi('office_post1','office_addr1','office_addr2');"  class="btn_line basic_button grayBtn"><span>�ּ�ã��</span></a>
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
				<th style="padding-left:16px">�߰�������</th>
				<td colspan="3"><b><?=$_COOKIE['url_name']?>(<?=$_COOKIE['url_id']?>)</b>���� �ʴ�� <b><font color="#FD9999"> ������ <?=$_data->recom_addreserve?>��</font></b>�� �߰� ������ �帳�ϴ�.<input type="hidden" name="rec_id" value="<?=$_COOKIE['url_id']?>"></td>
			</tr>
<?
		}else{
?>
			<tr>
				<th style="padding-left:16px">��õ��</th>
				<td colspan="3"><b><?=$_COOKIE['url_name']?>(<?=$_COOKIE['url_id']?>)</b>���� �ʴ븦 �޾ҽ��ϴ�.<input type="hidden" name="rec_id" value="<?=$_COOKIE['url_id']?>" style="WIDTH:275px;"></td>
			</tr>
<?
		}
	}else{
?>
			<tr>
				<th style="padding-left:16px">��õID</th>
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
				<th><span class="red">��</span>���� ���ſ���</th>
				<td colspan="3"><INPUT type=radio  class="radio" name="news_mail_yn" value="Y" id="idx_news_mail_yn0" <?if($news_mail_yn=="Y")echo"checked";?> style="BORDER:none;"><LABEL onmouseover="style.textDecoration='underline'" style="CURSOR: hand" onmouseout="style.textDecoration='none'" for="idx_news_mail_yn0">�޽��ϴ�.</LABEL> <INPUT type=radio class="radio"  name="news_mail_yn" value="N" id="idx_news_mail_yn1" <?if($news_mail_yn=="N")echo"checked";?> style="BORDER:none;"><LABEL onmouseover="style.textDecoration='underline'" style="CURSOR: hand" onmouseout="style.textDecoration='none'" for="idx_news_mail_yn1">���� �ʽ��ϴ�.</LABEL></td>
			</tr>
			<tr>
				<th class="thLast"><span class="red">��</span>SMS ���ſ���</th>
				<td colspan="3"  class="tdLast"><INPUT type=radio class="radio"  name="news_sms_yn" value="Y" id="idx_news_sms_yn0" <?if($news_sms_yn=="Y")echo"checked";?> style="BORDER:none;"><LABEL onmouseover="style.textDecoration='underline'" style="CURSOR: hand" onmouseout="style.textDecoration='none'" for="idx_news_sms_yn0">�޽��ϴ�.</LABEL> <INPUT type=radio class="radio"  name="news_sms_yn" value="N" id="idx_news_sms_yn1" <?if($news_sms_yn=="N")echo"checked";?> style="BORDER:none;"><LABEL onmouseover="style.textDecoration='underline'" style="CURSOR: hand" onmouseout="style.textDecoration='none'" for="idx_news_sms_yn1">���� �ʽ��ϴ�.</LABEL></td>
			</tr>
		</table>
	</td>
</tr>
</table>

</div>
<div class="btnWrap">
	<a href="javascript:CheckForm();" class="btn_grayB"><span>ȸ������ �Ϸ�</span></a>
	<a href="javascript:history.go(-1);"; class="btn_lineB"><span>�ٽ��ۼ�</span></a>
</div>

