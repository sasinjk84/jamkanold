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
			<col width="170"></col>
			<col width="130"></col>
			<col width="148"></col>
			<col></col>
			<? if(!$loginType){ //SNS�α���(���̹�)�� �� ?>
				<? /*
				<tr>
					<th><span class="red">��</span>ID</th>
					<td colspan="3"><INPUT type=text name="email" value="<?=$email?>" maxLength="100" style="WIDTH:50%;" class="input" placeholder=""> <A href="javascript:mailcheck();" class="btn_gray"><span>���� ����</span></a>
					<p style="color:#F02800;padding:10px 0px">*Naver ����(aaa@naver.com) �� �ٸ� �����ּҷ� �����Ͽ� ���� �����մϴ�.</p>
					</td>
				</tr>
				*/ ?>

				<tr>
					<th><span class="red">��</span> ���̵�</th>
					<td colspan="3">
						<INPUT type="text" name="id" value="<?=$id?>" maxLength="100" placeholder="���̵�� ����� �̸��� �ּ� �Է�" style="WIDTH:275px" class="input" />
					</td>
				</tr>
			<? } ?>

			<? if($loginType=="tvcf"){ //TVCF�α��� ?>
			<INPUT type="hidden" name="id" value="<?=$id?>" />
			<? } ?>

			<tr>
				<th ><span class="red">��</span> �̸���</th>
				<td colspan="3">
					<INPUT type="text" name="email" id="email" value="<?=$email?>" maxLength="100" placeholder="�̸��� �ּ� �Է�" class="input" style="WIDTH:275px; border:none; border-bottom:1px solid #ddd" onkeyup="email_check('email');" autocomplete="off" readonly />

					<div class="mainForm1LinkBtn2" id='email_cert' style="display:none">
						<a href="javascript:cert_key_open();" onclick="ga('send', 'event', '��ưŬ��', 'ȸ������ ��������', 'ȸ������ ������');" class="btn_red"><span>�����ϱ�</span></a>
					</div>

					<!--<span style="padding-left:5px;color:#F02800">*�����ּ� ����� ������ �ʿ��մϴ�.</span>-->

					<div id='msg_email' style="display:none;margin-top:10px"></div>
					<input type="hidden" name="email_enabled" id="email_enabled" />
					<input type="hidden" name="cert_value" id="cert_value" />

					<div class="mainForm1LinkBtn3" id='email_cert2' style="display:none;margin-top:4px">
						<input type="text" name="cret_num" id="cret_num" placeholder="�̸��� ������ȣ�� �Է��ϼ���." class="input" style="width:275px;border:1px solid #F02800;box-sizing:border-box;" autocomplete="off" />
						<a href="javascript:cert_key_ok();" class="btn_red"><span>����</span></a><a href="javascript:cert_key_go();" onclick="ga('send', 'event', '��ưŬ��', 'ȸ������ �������� ��߼�', 'ȸ������ ������');" class="btn_gray"><span>��߼�</span></a>
						<div style="margin-top:10px">
							���� ������ ���� �̸��� ������ �ִ� 5�� ���� ������ �� �ֽ��ϴ�.<br />
							���������� 5�� �Ŀ��� �������� ���� ��� ���� ó��, �뷮 �ʰ�, �޽��� ���� ���� ���� Ȯ���� �ּ���.
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
					<A href="javascript:idcheck();" class="btn_gray"><span>��������</span></a>
					<p style="color:#F02800;padding:10px 0px">*���̹�(naver) ���� �� �ٸ� �����ּ� ���� ���������� �ʿ��մϴ�.</p>
					-->
				</td>
			</tr>
			<tr>
				<th><span class="red">��</span>�̸�</th>
				<td colspan="3">
					<INPUT type="text" name="name" id="name" value="<?=$name?>" maxLength="15" style="WIDTH:275px;border: #F02800 1px solid;vertical-align:middle" class="input" readonly onclick="openPCCWindow()" />
					<A href="javascript:;" onclick="openPCCWindow()" class="btn_red" style="vertical-align:middle"><span>��������</span></a>

					<input type="hidden" name="result" id="result" />
				</td>
			</tr>

			<tr>
				<th><span class="red">��</span> ��й�ȣ</th>
				<td><INPUT type="password" name="passwd1" value="<?=$passwd1?>" maxLength="20" style="WIDTH:120px" class="input" /></td>
				<th style="text-align:right"><span class="red">��</span>��й�ȣ Ȯ��</th>
				<td><INPUT type="password" name="passwd2" value="<?=$passwd2?>" maxLength="20" style="WIDTH:120px" class="input" /></td>
			</tr>

			

			<? if($_data->resno_type!="N"){ ?>
			<tr>
				<th><span class="red">��</span>�ֹε�Ϲ�ȣ</th>
				<td colspan="3"><INPUT type=text name="resno1" value="<?=$resno1?>" maxLength="6" onkeyup="return strnumkeyup2(this);" style="WIDTH:50px;" class="input"> - <INPUT type=password name="resno2" value="<?=$resno2?>" maxLength="7" onkeyup="return strnumkeyup2(this);" style="WIDTH:58px;" class="input"></td>
			</tr>
			<? } ?>

			<tr style="display:none" id="mobile_tr">
				<th><span class="red">��</span>�޴�����ȣ</th>
				<td colspan="3">
					<INPUT type="text" maxLength="15" name="mobile" id="mobile" value="<?=$req_cellNo?>" style="WIDTH:275px;background:#f8f8f8" class="input" readonly />
				</td>
			</tr>

			<!--tr>
				<th><span class="red">��</span>��ȭ��ȣ</th>
				<td colspan="3"><INPUT type=text name="home_tel" value="<?=$home_tel?>" maxLength="15" style="WIDTH:275px;" class="input"></td>
			</tr-->
			<INPUT type=hidden name="home_tel" value="<?=$home_tel?>" maxLength="15" style="WIDTH:275px;" class="input">

			<? if($extconf['reqgender'] != 'H'){ ?>
				<tr style="display:none" id="gender_tr">
					<? if($extconf['reqgender'] == 'Y'){ ?><th><span class="red">��</span><? }else{ ?><th align="left" style="padding-left:27px"><? } ?>����</th>
					<td colspan="3">
						<label style="cursor:pointer"><INPUT type="radio" name="gender_chk" value="1" class="radio" disabled /> ����</label> &nbsp;&nbsp;
						<label style="cursor:pointer"><INPUT type="radio" name="gender_chk" value="2" class="radio" disabled /> ����</label>
					</td>
				</tr>
				<INPUT type="hidden" name="gender" id="gender" value="" />
			<? } ?>

			<? if($extconf['reqbirth'] != 'H'){ ?>
			<tr style="display:none" id="birthday_tr">
				<th><? if($extconf['reqbirth'] == 'Y'){?><span class="red">��</span><? } ?>�������</th>
				<td colspan="3">
					<INPUT type="text" name="birth" id="birth_day" value="<?=$req_birYMD?>" maxLength="10" style="WIDTH:275px;background:#f8f8f8" class="input" readonly />
				</td>
			</tr>
			<? } ?>

			<? /*
			<tr>
				<th><!--span class="red">��</span-->�ּ�</th>
				<td colspan="3">
					<div style="overflow:hidden">
						<INPUT type="text" name="home_post1" id="home_post1" readOnly style="WIDTH:60px;" class="input"> 
						<A href="javascript:addr_search_for_daumapi('home_post1','home_addr1','home_addr2');"  class="btn_line basic_button grayBtn"><span>�ּ�ã��</span></a>
					</div>
					<div style="margin:3px 0px;overflow:hidden"><INPUT type="text" name="home_addr1" id="home_addr1" maxLength="100" readOnly style="WIDTH:96%;" class="input" /></div>
					<div style="overflow:hidden"><INPUT type="text" name="home_addr2" id="home_addr2" maxLength="100" style="WIDTH:96%;" class="input" /></div>
				</td>
			</tr>
			*/ ?>
			<!--
			<tr>
				<th style="padding-left:16px">ȸ���ּ�</th>
				<td colspan="3">

					<div style="overflow:hidden">
						<INPUT type="text" name="office_post1" id="office_post1" readOnly style="WIDTH:60px;" class="input"> 
						<A href="javascript:addr_search_for_daumapi('office_post1','office_addr1','office_addr2');"  class="btn_line basic_button grayBtn"><span>�ּ�ã��</span></a>
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
				<th style="padding-left:16px">�߰�������</th>
				<td colspan="3"><b><?=$_COOKIE['url_name']?>(<?=$_COOKIE['url_id']?>)</b>���� �ʴ�� <b><font color="#FD9999"> ������ <?=$_data->recom_addreserve?>��</font></b>�� �߰� ������ �帳�ϴ�.<input type="hidden" name="rec_id" value="<?=$_COOKIE['url_id']?>"></td>
			</tr>
			<? }else{ ?>
			<tr>
				<th style="padding-left:16px">��õ��</th>
				<td colspan="3"><b><?=$_COOKIE['url_name']?>(<?=$_COOKIE['url_id']?>)</b>���� �ʴ븦 �޾ҽ��ϴ�.<input type="hidden" name="rec_id" value="<?=$_COOKIE['url_id']?>" style="WIDTH:275px;"></td>
			</tr>
			<?
					}
				}else{
			?>
			<tr>
				<th style="padding-left:16px">��õ ID</th>
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
				<th><span class="red">��</span>�̸��� ���ſ���</th>
				<td colspan="3">
					<INPUT type="radio" class="radio" name="news_mail_yn" value="Y" id="idx_news_mail_yn0" <?if($news_mail_yn=="Y")echo"checked";?> style="BORDER:none;"> <LABEL style="CURSOR:pointer" for="idx_news_mail_yn0">�޽��ϴ�.</LABEL>&nbsp;&nbsp;
					<INPUT type="radio" class="radio" name="news_mail_yn" value="N" id="idx_news_mail_yn1" <?if($news_mail_yn=="N")echo"checked";?> style="BORDER:none;"> <LABEL style="CURSOR:pointer" for="idx_news_mail_yn1">���� �ʽ��ϴ�.</LABEL>
				</td>
			</tr-->
			<? /*
			<tr>
				<th class="thLast"><span class="red">��</span>SMS ���ſ���</th>
				<td colspan="3" class="tdLast">
					<INPUT type="radio" class="radio" name="news_sms_yn" value="Y" id="idx_news_sms_yn0" <?if($news_sms_yn=="Y")echo"checked";?> style="BORDER:none;"> <LABEL style="CURSOR:pointer" for="idx_news_sms_yn0">�޽��ϴ�.</LABEL>&nbsp;&nbsp;
					<INPUT type="radio" class="radio" name="news_sms_yn" value="N" id="idx_news_sms_yn1" <?if($news_sms_yn=="N")echo"checked";?> style="BORDER:none;"> <LABEL style="CURSOR:pointer" for="idx_news_sms_yn1">���� �ʽ��ϴ�.</LABEL>
				</td>
			</tr>
			*/ ?>
			<tr>
				<th class="thLast">���� ���ż���</th>
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
<p style="margin-bottom:50px;text-align:center">* ���Խ� <a href="javascript:viewPolicy();">[ȸ�����]</a>�� <a href="javascript:viewProtect()">[����������޹�ħ]</a>�� �����ϰ� �˴ϴ�.</p>

<div class="btnWrap">
	<a href="javascript:CheckForm();" class="btn_grayB"><span>ȸ������ �Ϸ�</span></a>
	<a href="javascript:history.go(-1);"; class="btn_lineB"><span>�ٽ��ۼ�</span></a>
</div>


<!-- �̿��� ��ü���� -->
<div class="policyView" id="policyView" style='display: none;'>
	<div class='viewBox1'>
		<div class='viewCloseBtn'><a style='line-height: 120%; font-size: 30px; text-decoration: none;' href='javascript:hiddenPolicy();'>��</a></div>
		<h4>ȸ������ �������</h4>
		<div class="viewBox2"><?=$agreement?></div>
	</div>
</div>
<!-- ����������޹�ħ ��ü���� -->
<div class='policyView' id='ProtectView' style='display: none;'>
	<div class='viewBox1'>
		<div class='viewCloseBtn'><a style='line-height: 120%; font-size: 30px; text-decoration: none;' href='javascript:hiddenProtect();'>��</a></div>
		<h4>����������޹�ħ ����</h4>
		<div class='viewBox2'><?=$privercy?></div>
	</div>
</div>

<link rel="stylesheet" href="/css/jquery-ui/jquery-ui.min.css">
<script type="text/javascript" src="/js/jquery-ui.min.js"></script>

<script type="text/javascript">
<!--
/* �̿��� ���� */
function viewPolicy(){
	$j("#policyView").show();
}
/* �̿��� �ݱ� */
function hiddenPolicy(){
	$j("#policyView").hide();
}
/* ����������޹�ħ ���� */
function viewProtect(){
	$j("#ProtectView").show();
}
/* ����������޹�ħ �ݱ� */
function hiddenProtect(){
	$j("#ProtectView").hide();
}
</script>