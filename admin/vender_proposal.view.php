<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/shopdata.php");
INCLUDE ("access.php");

	$KEY = array_keys($_GET);
	$key = $KEY[0];

	if( strlen( $key ) == 0 ) {
		exit("���� ����");
	}

	$sql = "SELECT * FROM `tblVenderProposal` WHERE `idx`=".$key." LIMIT 1 ; ";
	$result=mysql_query($sql,get_db_conn());
	$row=mysql_fetch_object($result);


	$shopInfo_sql = "SELECT * FROM tblsmsinfo ";
	$shopInfo_result=mysql_query($shopInfo_sql,get_db_conn());
	if ($shopInfo_row=mysql_fetch_object($shopInfo_result)) {
		$sms_id = $shopInfo_row->id;
		$sms_authkey = $shopInfo_row->authkey;
		$return_tel = $shopInfo_row->return_tel;
	}

	if( $_POST['mode'] == "adminChk" ) {

		$UPsql = "UPDATE `tblVenderProposal` SET";
		$UPsql .= "`chk_date`=NOW()";

		if( $_POST[sendSMSchk] == "on" ) {



			$UPsql .= ", `smsMSG` = '".$_POST[sendSMS]."' ";
			SendSMS($sms_id, $sms_authkey, $row->mng_phone, "", $return_tel, '0', $_POST[sendSMS], '����/��������');
		}


		if( $_POST[sendMailchk] == "on" ) {
			$UPsql .= ", `mailMSG` = '".$_POST[sendMail]."' ";
			$header  = "From:".$_shopdata->companyname." <".$_shopdata->info_email.">\r\n";
			$header .= "Content-Type: text/html; charset=euc-kr\r\n";
			sendmail($row->mng_mail, "[".$_shopdata->companyname."] ����/���� ��û �亯�Դϴ�.", $_POST[sendMail], $header);
		}

		$UPsql .= "WHERE `idx`=".$key." LIMIT 1 ;";

		$UPresult=mysql_query($UPsql,get_db_conn());
		header("Location: ?".$key);
	}


?>

<HTML>
<HEAD>
<TITLE><?=$_data->shoptitle?></TITLE>
<META http-equiv="CONTENT-TYPE" content="text/html; charset=EUC-KR">
<script type="text/javascript" src="<?=$Dir?>lib/lib.js.php"></script>
<link rel="stylesheet" href="style.css" type="text/css">
<?include($Dir."lib/style.php")?>

<script type="text/javascript">
<!--

	// SMS üũ
	function byte_check(tx, byt_prt)
	{
		text_data="";
		data=tx.value;
		tot_text_len=0;
		for(rof=0;rof<data.length;rof++)
		{
			if(data.charCodeAt(rof)>255) tot_text_len++;
			tot_text_len++;
			text_data+=data.charAt(rof);

			if(tot_text_len>80)
			{
				alert("80Byte �̻󺸳��������ϴ�");
				tx.value=text_data;
				tot_text_len=tot_text_len-1;
				break;
			}
		}
		byt_prt.innerHTML=tot_text_len;
	}

	// Ȯ�� �Ϸ�
	function chkAdmin (f) {
		if( f.sendSMSchk.checked==true ) {
			if( f.sendSMS.value.length == 0 ){
				alert("�߼��� SMS ������ �Է��ϼ���.");
				f.sendSMS.focus();
				return false;
			}
		}
		if( f.sendMailchk.checked==true ) {
			if( f.sendMail.value.length == 0 ){
				alert("�߼��� ���� ������ �Է��ϼ���.");
				f.sendMail.focus();
				return false;
			}
		}
		if( confirm("���� ������ Ȯ�� �Ϸ� �� �����Դϱ�?") ) {
			f.method="POST";
			f.action='vender_proposal.view.php?<?=$key?>';
			f.submit();
		}
	}


	// ������ü���
	function venderInsert( key ) {
		if( confirm("���� â�� �ݰ� ������ü������� �̵��մϱ�?\n(���� �ʰ� �̵��Ͻ÷��� \"���\")") ) {
			self.close();
		}
		opener.location.href="vender_new.php?key="+key;
	}

//-->
</script>

</HEAD>

<body topmargin="0" leftmargin="0">
	<TABLE cellSpacing=0 cellPadding=0 width="100%" border="0" align="center">
		<tr>
			<td background="images/venderdetail_top_bg.gif" height="20"><img src="images/venderdetail_top.gif"></td>
		</tr>
		<tr><td height="15"></td></tr>
		<tr>
			<td align="center">
				<TABLE cellSpacing=0 cellPadding=0 width="96%" border="0">
					<col width="160"></col>
					<col width=""></col>
					<TR><TD height="1" colspan="2" bgcolor="#b9b9b9"></TD></TR>
					<TR><TD height="1" colspan="2" bgcolor="#ededed"></TD></TR>
					<TR>
						<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">���ǳ���</TD>
						<TD class="td_con1">&nbsp;<?=$row->type?></TD>
					</TR>
					<TR><TD height="1" colspan="2" bgcolor="#ededed"></TD></TR>
					<TR>
						<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">ȸ���</TD>
						<TD class="td_con1">&nbsp;<?=$row->company?></TD>
					</TR>
					<TR><TD height="1" colspan="2" bgcolor="#ededed"></TD></TR>
					<TR>
						<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">����� ������ �ּ�</TD>
						<TD class="td_con1">&nbsp;<?=$row->comp_zip?><br />&nbsp;<?=$row->comp_addr1?><br />&nbsp;<?=$row->comp_addr2?></TD>
					</TR>
					<TR><TD height="1" colspan="2" bgcolor="#ededed"></TD></TR>
					<TR>
						<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">������Ʈ �ּ�</TD>
						<TD class="td_con1">&nbsp;<?=$row->comp_site?></TD>
					</TR>
					<TR><TD height="1" colspan="2" bgcolor="#ededed"></TD></TR>
					<TR>
						<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">���⵵ �����</TD>
						<TD class="td_con1">&nbsp;<?=$row->pre_sell?></TD>
					</TR>
					<TR><TD height="1" colspan="2" bgcolor="#ededed"></TD></TR>
					<TR>
						<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">���� ��</TD>
						<TD class="td_con1">&nbsp;<?=$row->comp_mem_no?></TD>
					</TR>
					<TR><TD height="1" colspan="2" bgcolor="#ededed"></TD></TR>
					<TR>
						<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">���ո�, ���¸��� ��<br>&nbsp;&nbsp;�� �� ������</TD>
						<TD class="td_con1">&nbsp;<?=nl2br($row->etc_mall)?></TD>
					</TR>
					<TR><TD height="1" colspan="2" bgcolor="#ededed"></TD></TR>
					<TR>
						<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">����� ����</TD>
						<TD class="td_con1">&nbsp;<?=$row->mng_name?></TD>
					</TR>
					<TR><TD height="1" colspan="2" bgcolor="#ededed"></TD></TR>
					<TR>
						<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">��ȭ��ȣ</TD>
						<TD class="td_con1">&nbsp;<?=$row->mng_tell?></TD>
					</TR>
					<TR><TD height="1" colspan="2" bgcolor="#ededed"></TD></TR>
					<TR>
						<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">�ڵ���</TD>
						<TD class="td_con1">&nbsp;<?=$row->mng_phone?></TD>
					</TR>
					<TR><TD height="1" colspan="2" bgcolor="#ededed"></TD></TR>
					<TR>
						<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">�̸���</TD>
						<TD class="td_con1">&nbsp;<?=$row->mng_mail?></TD>
					</TR>
					<TR><TD height="1" colspan="2" bgcolor="#ededed"></TD></TR>
					<TR>
						<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">�� ���ǳ���</TD>
						<TD class="td_con1">&nbsp;<?=nl2br($row->contents)?></TD>
					</TR>
					<TR><TD height="1" colspan="2" bgcolor="#ededed"></TD></TR>


					<form name="okSendForm">
					<TR>
						<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">������ Ȯ��</TD>
						<TD class="td_con1">
							<?
								if( $row->chk_date > 0 ) {
									echo "������ Ȯ�� �Ϸ� : ".$row->chk_date;
									if( strlen($row->smsMSG) ) {
										echo "<span title='".$row->smsMSG."'> [SMS] </span>";
									}
									if( strlen($row->mailMSG) ) {
										echo "<span title='".$row->mailMSG."'> [mail] </span>";
									}
								} else {
							?>


								<?
									if ( strlen($row->mng_phone) > 10 ) {
								?>
								<input type="checkbox" name="sendSMSchk" onclick="sendSMStable.style.display=(this.checked)?'block':'none';">SMS �߼� ( <?=$row->mng_phone?> )
								<TABLE WIDTH="200" BORDER=0 CELLPADDING=0 CELLSPACING=0 id="sendSMStable" style="display:none;">
									<TR>
										<TD><IMG SRC="images/sms_top_01.gif" WIDTH=200 HEIGHT="30" ALT=""></TD>
									</TR>
									<TR>
										<TD align=center height="90" background="images/sms_bg.gif" valign="top"><TEXTAREA class="textarea_hide" onkeyup="byte_check(sendSMS,prtbyte);" name="sendSMS" rows=5 cols="26" onchange="byte_check(sendSMS,prtbyte);"><?=$row->msg_mem_birth?></TEXTAREA></TD>
									</TR>
									<TR>
										<TD align=center height="26" background="images/sms_down_01.gif">
										<strong id="prtbyte">0</strong> bytes (�ִ�80 bytes)
										<SCRIPT>byte_check(sendSMS,prtbyte);</SCRIPT>
										</TD>
									</TR>
								</TABLE>
								<?
									}
								?>

								<?
									if ( strlen($row->mng_mail) > 5 ) {
								?>
								<input type="checkbox" name="sendMailchk" onclick="sendMail.style.display=(this.checked)?'block':'none';"> Mail �߼� ( <?=$row->mng_mail?> )
								<textarea name="sendMail" style="display:none; width:450px; height:200px;"></textarea>
								<br />
								<a href="#" onclick="chkAdmin(okSendForm);"><img src="images/btn_admincheckok.gif" border="0" alt="" /></a><!--<input type="button" onclick="chkAdmin(okSendForm);" value="������ Ȯ�� �Ϸ�">-->
								<?
									}
								?>


							<?
								}
							?>
						</TD>
					</TR>
					<input type="hidden" name="key" value="<?=$key?>">
					<input type="hidden" name="mode" value="adminChk">
					</form>



					<TR><TD height="1" colspan="2" bgcolor="#ededed"></TD></TR>
					<TR>
						<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">�����ð�</TD>
						<TD class="td_con1">&nbsp;<?=$row->reg_date?></TD>
					</TR>
					<TR><TD height="1" colspan="2" bgcolor="#b9b9b9"></TD></TR>
				</table>
			</td>
		</tr>
		<tr><td height=10></td></tr>
		<tr>
			<td align="center">
				<a href="javascript:self.close();"><img src="images/btn_close.gif" border="0" alt="�ݱ�" /></a>
				<a href="javascript:venderInsert(<?=$key?>);"><img src="images/btn_vender_insert.gif" border="0" alt="������ü���" /></a>
			</td>
		</tr>
	</table>
</BODY>
</HTML>