<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/shopdata.php");
INCLUDE ("access.php");

####################### ������ ���ٱ��� check ###############
$PageCode = "sh-3";
$MenuCode = "shop";
if (!$_usersession->isAllowedTask($PageCode)) {
	INCLUDE ("AccessDeny.inc.php");
	exit;
}
#########################################################

$type=$_POST["type"];
$up_newbank_account=$_POST["up_newbank_account"];
$up_oldbank_account=$_POST["up_oldbank_account"];
$up_payment_type=$_POST["up_payment_type"];
$up_card_payfee=$_POST["up_card_payfee"];
$up_bank_percent=$_POST["up_bank_percent"];
$up_card_splittype=$_POST["up_card_splittype"];
$up_card_splitmonth=$_POST["up_card_splitmonth"];
$up_card_splitprice=$_POST["up_card_splitprice"];
$up_auto_order_cancel=$_POST["up_auto_order_cancel"];
$up_bankmess=$_POST["up_bankmess"];
$up_cardmess=$_POST["up_cardmess"];
$up_saletype=$_POST["up_saletype"];
$up_bank_miniprice=$_POST["up_bank_miniprice"];
$up_card_miniprice=$_POST["up_card_miniprice"];


$chagepwd = isset($_POST['changepwd'])? trim($_POST['changepwd']):"";

// ����������
if ($up_saletype=="-" && $up_bank_percent!=0) $up_bank_percent+=50;
if ($up_bank_percent!=0) $up_card_payfee = "-".$up_bank_percent;


#### ���̽����� ��ҽ� ��й�ȣ ��������
$cancelpwdSQL = "SELECT value FROM extra_conf WHERE type = 'payment' AND name='cancel' ";
if(false !== $cancelpwdRes = mysql_query($cancelpwdSQL,get_db_conn())){
	$cancelNum = mysql_num_rows($cancelpwdRes);
	$cancelPWD = mysql_result($cancelpwdRes,0,0);
	if($cancelNum> 0){
		mysql_free_result($cancelpwdRes);
	}
}

#### PG���� ��������
$_ShopInfo->getPgdata();
$pg_info = GetEscrowType($_data->card_id);

$pgcorp = trim($pg_info['PG']);

if($pgcorp != "E"){
	$display = 'style="display:none"';
}

if ($type=="up") {
	$sql = "UPDATE tblshopinfo SET ";
	$sql.= "bank_miniprice			= '".$up_bank_miniprice."', ";
	$sql.= "card_miniprice			= '".$up_card_miniprice."', ";
	$sql.= "payment_type		= '".$up_payment_type."', ";
	$sql.= "card_payfee		= '".$up_card_payfee."', ";
	$sql.= "bank_account		= '".$up_oldbank_account."=".$up_bankmess."=".$up_cardmess."', ";
	$sql.= "card_splittype		= '".$up_card_splittype."', ";
	$sql.= "card_splitmonth		= '".$up_card_splitmonth."', ";
	$sql.= "card_splitprice		= '".$up_card_splitprice."', ";
	$sql.= "auto_order_cancel	= '".$up_auto_order_cancel."' ";
	$result = mysql_query($sql,get_db_conn());
	DeleteCache("tblshopinfo.cache");

	$log_content = "## �������ü��� ## - ����:$up_card_splittype ������:$up_card_splitmonth �ݾ�:$up_card_splitprice ī������� : $up_card_payfee(���̳ʽ��̸� ������������) $up_payment_type �ּ��ֹ�����: $bank_miniprice ī������ֹ����� : $card_miniprice �ڵ�������� : $auto_order_cancel";
	ShopManagerLog($_ShopInfo->getId(),$connect_ip,$log_content);

	if($cancelNum > 0){
		if(strlen($chagepwd)>0 && $pgcorp == "E"){
			$cancelSQL = "UPDATE extra_conf SET value='".$chagepwd."' WHERE type='payment' AND name='cancel' ";
			@mysql_query($cancelSQL,get_db_conn());
		}
	}else{
		if(strlen($chagepwd)>0 && $pgcorp == "E"){
			$cancelSQL = "INSERT INTO extra_conf SET type='payment', name='cancel', value='".$chagepwd."' ";
			@mysql_query($cancelSQL,get_db_conn());
		}
	}
	$onload="<script> alert('�������� ������ �Ϸ�Ǿ����ϴ�.');location.replace('./shop_payment.php');</script>";
} else if ($type=="add") {
	if (strlen(trim($up_newbank_account))>0) {
		$sql = "SELECT bank_account FROM tblshopinfo ";
		$result = mysql_query($sql,get_db_conn());
		if ($row=mysql_fetch_object($result)) {
			if (strlen($row->bank_account)>0){
			   $arbank_account=explode("=",$row->bank_account);
			   $sql = "UPDATE tblshopinfo SET ";
			   $sql.= "bank_account='";
			   if(strlen($arbank_account[0])>0) {
				   $sql.=$arbank_account[0].",";
			   }
			   $sql.=$up_newbank_account."=".$arbank_account[1]."=".$arbank_account[2]."'";
			} else {
				$sql = "UPDATE tblshopinfo SET bank_account = '".$up_newbank_account."' ";
			}
			mysql_query($sql,get_db_conn());
			DeleteCache("tblshopinfo.cache");
			$onload = "<script> alert('������ �Ա� �ű� ���� �߰��� �Ϸ�Ǿ����ϴ�.'); </script>";
		}
		mysql_free_result($result);
	}
} else if ($type=="del") {
	if (strlen(trim($up_newbank_account))>0) {
		$sql = "SELECT bank_account FROM tblshopinfo ";
		$result = mysql_query($sql,get_db_conn());
		if ($row=mysql_fetch_object($result)) {
			if (strlen($row->bank_account)>0) {
				$bank_account=explode("=",$row->bank_account);
				$temp = $bank_account[0];
				$tok = strtok($temp,",");
				$count = 0; $temp2="";
				while ($tok) {
					if ($up_newbank_account!=$tok) {
						if ($count==0) $temp2=$tok;
						else $temp2=$temp2.",".$tok;
						$count++;
					}
					$tok = strtok(",");
				}
			}
			$sql = "UPDATE tblshopinfo SET ";
			$sql.= "bank_account = '".$temp2."=".$bank_account[1]."=".$bank_account[2]."' ";
			mysql_query($sql,get_db_conn());
			DeleteCache("tblshopinfo.cache");
			$onload="<script> alert('�����Ͻ� ������ �Աݰ��� ������ �Ϸ�Ǿ����ϴ�.'); </script>";
		}
	}
}else if($type == "pwd"){
	if($cancelNum > 0){
		if(strlen($chagepwd)>0 && $pgcorp == "E"){
			$cancelSQL = "UPDATE extra_conf SET value='".$chagepwd."' WHERE type='payment' AND name='cancel' ";
			if(mysql_query($cancelSQL,get_db_conn())){
				echo "<script>alert(\"������� ��й�ȣ�� ���������� �����Ǿ����ϴ�..\");location.replace('./shop_payment.php');</script>";
			}else{
				echo "<script>alert(\"��Ʈ��ũ ������ ó������ �ʾҽ��ϴ�.\\n��� �� �ٽ� �õ��� �ּ���.\");location.replace('./shop_payment.php');</script>";
			}
		}
	}else{
		if(strlen($chagepwd)>0 && $pgcorp == "E"){
			$cancelSQL = "INSERT INTO extra_conf SET type='payment', name='cancel', value='".$chagepwd."' ";
			if(mysql_query($cancelSQL,get_db_conn())){
				echo "<script>alert(\"������� ��й�ȣ�� ���������� ��ϵǾ����ϴ�.\");location.replace('./shop_payment.php');</script>";
			}else{
				echo "<script>alert(\"��Ʈ��ũ ������ ó������ �ʾҽ��ϴ�.\\n��� �� �ٽ� �õ��� �ּ���.\");location.replace('./shop_payment.php');</script>";
			}
		}
	}
}


$sql = "SELECT bank_account,payment_type,deli_basefee,deli_miniprice,card_payfee,card_splittype, ";
$sql.= "card_splitmonth, card_splitprice,card_miniprice,bank_miniprice, auto_order_cancel FROM tblshopinfo ";
$result=mysql_query($sql,get_db_conn());
if ($row=mysql_fetch_object($result)) {
	$arbank_account=explode("=",$row->bank_account);
	$bank_account=$arbank_account[0];
	$bankmess=$arbank_account[1];
	$cardmess=$arbank_account[2];
	$payment_type = $row->payment_type;
	$deli_basefee = $row->deli_basefee;
	$deli_miniprice = $row->deli_miniprice;
	$card_payfee = $row->card_payfee;
	$bank_percent = 0;
	$card_splittype = $row->card_splittype;
	$card_splitmonth = $row->card_splitmonth;
	$card_splitprice = $row->card_splitprice;
	$card_miniprice = $row->card_miniprice;
	$bank_miniprice = $row->bank_miniprice;	
	$auto_order_cancel = $row->auto_order_cancel;	

	if ($card_payfee<=0) { 
		$saletype="+";
		$bank_percent = abs($row->card_payfee); 
		if($bank_percent>50){
			$bank_percent-=50;
			$saletype="-";
		}
		$card_payfee=0; 
	}
}
mysql_free_result($result);
if($card_payfee < 1) $card_payfee = 0;

${"check_payment_type".$payment_type} = "checked";
${"check_card_splittype".$card_splittype} = "checked";




?>

<? INCLUDE "header.php"; ?>

<script type="text/javascript" src="lib.js.php"></script>
<script language="JavaScript">
function CheckForm() {
	var temp;
	temp = parseInt(document.form1.up_card_payfee.value);
	temp2 = parseInt(document.form1.up_bank_percent.value);
	if (document.form1.up_card_splitprice.value<50000) {
		alert("������ ���ɱݾ��� 50,000�� �̻��Դϴ�.(�Һδ� 5�����̻��϶� ����)");
		document.form1.up_card_splitprice.focus();
		return;
	}
	if(!(-1 < temp && temp < 100)) {
		alert("ī������������ 0���� 99������ ���ڸ� �����մϴ�.");
		document.form1.up_card_payfee.focus();
		return;
	}
	if(!(-1 < temp2 && temp2 < 51)) {
		alert("���ݰ��� ������ ����/���������� 0���� 50������ ���ڸ� �����մϴ�.");
		document.form1.up_bank_percent.focus();
		return;
	}
	if (temp!=0 && temp2!=0) {
		alert("ī����� ������ �߰��� ���ݰ��� ����/���� ��å�� ���û�� �Ұ��մϴ�.");
		return;
	}
	document.form1.type.value="up";
	document.form1.submit();
}

function AccountDel(account) {
	document.form1.up_newbank_account.value=account;
	document.form1.type.value="del";
	document.form1.submit();
}

function AccountAdd(){
	document.form1.up_newbank_account.value = document.form1.up_newbank_account1.value;
	if (document.form1.up_newbank_account2.value.length>0) {
		document.form1.up_newbank_account.value += " (������:" + document.form1.up_newbank_account2.value + ")";
	}
	if (document.form1.up_newbank_account.value.length==0) {
		alert("������¹�ȣ�� �Է��ϼ���.");
		return;
	}
	document.form1.type.value="add";
	document.form1.submit();
}

function checkmess(){
	if(!confirm('������ü�� �������Һμ��� ����� ���� �ϼž� �մϴ�.\n����� �ȵ� ���¿��� ������ ����� ���������� �� �� �ֽ��ϴ�.\n������ �Һ� ����� �Ǿ��ø� [Ȯ��]��ư�� �����ּž� ����˴ϴ�.\n[���]��ư�� �����ø� �Һ� ������ �������� ����˴ϴ�.')){
		document.form1.up_card_splittype[0].checked=true; 
	}
}
function changePasswd(val){
	var payForm = document.form1;
	if(payForm.changepwd.value == "" || payForm.changepwd.value == null){
		alert("��� ��й�ȣ�� �Է��ϼ���");
		payForm.changepwd.focus();
		return;
	}else{
		if(val != "" && val != 'undefined' && val != null && val=="pwd"){
			if(confirm("��й�ȣ�� �����Ͻðڽ��ϱ�?")){
				payForm.type.value = val;
				payForm.submit();
				return;
			}else{
				return;
			}
		}else{
			alert("�ʼ����� ���޵��� �ʾҽ��ϴ�.\n�ٽ� �õ��� �ֽñ� �ٶ��ϴ�.");
			location.replace("./shop_payment.php");
			return;
		}
	}
}
</script>
<table cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed">
	<tr>
		<td valign="top">
			<table cellpadding="0" cellspacing="0" width=100% style="table-layout:fixed">
				<tr>
					<td>
						<table cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed"  background="images/con_bg.gif">
							<col width="198"></col>
							<col width="10"></col>
							<col width=></col>
							<tr>
								<td valign="top"  background="images/leftmenu_bg.gif"><? include ("menu_shop.php"); ?></td>
								<td></td>
								<td valign="top">
									<table cellpadding="0" cellspacing="0" width="100%">
										<tr>
											<td height="29" colspan="3">
												<table cellpadding="0" cellspacing="0" width="100%">
													<tr>
														<td height="28" class="link" align="left" background="images/con_link_bg.gif"><img src="images/top_link_house.gif" border="0" valign="absmiddle">������ġ : �������� &gt; ���θ� � ���� &gt; <span class="2depth_select">��ǰ �������� ��ɼ���</span></td>
													</tr>
												</table>
											</td>
										</tr>
										<tr>
											<td width="16"><img src="images/con_t_01.gif" width="16" height="16" border="0"></td>
											<td background="images/con_t_01_bg.gif"></td>
											<td width="16"><img src="images/con_t_02.gif" width="16" height="16" border="0"></td>
										</tr>
										<tr>
											<td width="16" background="images/con_t_04_bg1.gif"></td>
											<td bgcolor="#ffffff" style="padding:10px">
												<table cellpadding="0" cellspacing="0" width="100%">
													<tr>
														<td height="8"></td>
													</tr>
													<tr>
														<td>
															<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
																<TR>
																	<TD><IMG SRC="images/shop_payment_title.gif"  ALT=""></TD>
																</tr>
																<tr>
																	<TD width="100%" background="images/title_bg.gif" height="21"></TD>
																</TR>
															</TABLE>
														</td>
													</tr>
													<tr>
														<td height="3"></td>
													</tr>
													<tr>
														<td style="padding-bottom:3pt;">
															<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
																<TR>
																	<TD><IMG SRC="images/distribute_01.gif"></TD>
																	<TD COLSPAN=2 background="images/distribute_02.gif"></TD>
																	<TD><IMG SRC="images/distribute_03.gif"></TD>
																</TR>
																<TR>
																	<TD background="images/distribute_04.gif"></TD>
																	<TD class="notice_blue"><IMG SRC="images/distribute_img.gif" ></TD>
																	<TD width="100%" class="notice_blue"><p>����/�ſ�ī�� ������ ������, �ּұ��ž�, �������Һ�, �ſ�ī�� ��������� ������ �� �ֽ��ϴ�.</p></TD>
																	<TD background="images/distribute_07.gif"></TD>
																</TR>
																<TR>
																	<TD><IMG SRC="images/distribute_08.gif"></TD>
																	<TD COLSPAN=2 background="images/distribute_09.gif"></TD>
																	<TD><IMG SRC="images/distribute_10.gif"></TD>
																</TR>
															</TABLE>
														</td>
													</tr>
													<tr>
														<td>
															<form name="form1" action="<?=$_SERVER[PHP_SELF]?>" method="post">
																<input type="hidden" name="type">
																<input type="hidden" name="up_newbank_account">
																<input type="hidden" name="up_oldbank_account" value="<?=$bank_account?>">
																<table cellpadding="0" cellspacing="0" width="100%" border="0">
																	<tr>
																		<td height="10"></td>
																	</tr>
																	<tr>
																		<td><IMG SRC="images/shop_payment_stitle1.gif" border="0"></td>
																	</tr>
																	<tr>
																		<td height="5"></td>
																	</tr>
																	<tr>
																		<td>
																			<table cellpadding="0" cellspacing="0" width="100%">
																				<TR>
																					<TD bgcolor="#B9B9B9" height="1"></TD>
																				</TR>
																				<tr>
																					<td>
																						<table cellpadding="0" cellspacing="0" width="100%">
																							<col width="150"></col>
																							<col width=""></col>
																							<TR>
																								<TD class="table_cell"><b><img src="images/icon_point2.gif" border="0"></b>������� ����</TD>
																								<TD class="td_con1">
																									<input type=radio id="idx_payment_type1" name=up_payment_type value="Y" <?=$check_payment_typeY?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_payment_type1>�ſ�ī��+�¶��ΰ���</label>
																									<input type=radio id="idx_payment_type2" name=up_payment_type value="C" <?=$check_payment_typeC?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_payment_type2>�ſ�ī������� ����</label>
																									<input type=radio id="idx_payment_type3" name=up_payment_type value="N" <?=$check_payment_typeN?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_payment_type3>�¶��ΰ����� ����</label>
																								</TD>
																							</TR>
																						</table>
																					</td>
																				</tr>
																				<TR>
																					<TD bgcolor="#B9B9B9" height="1"></TD>
																				</TR>
																			</table>
																		</td>
																	</tr>
																	<tr>
																		<td height="10"></td>
																	</tr>
																	
																	<tr>
																		<td style="padding-bottom:3pt;">
																			<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
																				<TR>
																					<TD><IMG SRC="images/distribute_01.gif"></TD>
																					<TD COLSPAN=2 background="images/distribute_02.gif"></TD>
																					<TD><IMG SRC="images/distribute_03.gif"></TD>
																				</TR>
																				<TR>
																					<TD background="images/distribute_04.gif"></TD>
																					<TD class="notice_blue"><IMG SRC="images/distribute_img.gif" ></TD>
																					<TD width="100%" class="notice_blue">
																						<p>
																						1) ī������� ������ȸ��� ��� �� ���Ҿ��̵�(����Ʈ�ڵ�)�� ȸ�翡 �˷��ּž� ��� �����մϴ�.<br />
																						2) �����߰�ȸ��� ���� ������ ȸ�縸 �����˴ϴ�.(ȸ�� Ȩ������ ����)
																						</p>
																					</TD>
																					<TD background="images/distribute_07.gif"></TD>
																				</TR>
																				<TR>
																					<TD><IMG SRC="images/distribute_08.gif"></TD>
																					<TD COLSPAN=2 background="images/distribute_09.gif"></TD>
																					<TD><IMG SRC="images/distribute_10.gif"></TD>
																				</TR>
																			</TABLE>
																		</td>
																	</tr>
																	<tr>
																		<td height="30"></td>
																	</tr>
																	<!-- ��й�ȣ �ڽ�-->
																	<tr <?=$display?>>
																		<td>
																			<table cellpadding="0" cellspacing="0" width="100%">
																				<tr>
																					<td><IMG SRC="images/paycancel_title.gif" border="0" alt="���̽����� ������� ��й�ȣ" /></td>
																				</tr>
																				<tr>
																					<td height="5"></td>
																				</tr>
																				<tr>
																					<td>
																						<table cellpadding="0" cellspacing="0" width="100%">
																							<TR>
																								<TD class="notice_blue" colspan="2">
																									<div style="margin-left:24px; margin-bottom:10px;">
																										<p style="color:red">
																											1) �ش� ��� ��й�ȣ�� ���̽����� �ſ�ī�� ������� ����ó���� �ݵ�� �ʿ��մϴ�.<br />
																											2) �ش� ��� ��й�ȣ�� ���̽����̿� �ڵ��������� �����Ƿ�, ���̽����� ��������ڿ��� ��Һ�й�ȣ ����� �ݵ�� ������ ������ �����Ǿ�� �մϴ�.<br />
																											&nbsp;&nbsp;&nbsp;&nbsp;(���̽����� ������� ��й�ȣ ���� : ��������� ������ > ������ ���� > �⺻����)<br />
																											3) ���̽����� ��Һ�й�ȣ�� ��ġ���� ���� ��� ���θ� ���������ڿ��� ī����Ұ� ���� �ʽ��ϴ�.<br />
																											4) ��Һ�й�ȣ �Է� �� [��й�ȣ ����] ��ư�� �ݵ�� �����ּž� �������� ����˴ϴ�.
																										</p>
																									</div>
																								</TD>
																							</TR>
																							<TR>
																								<TD bgcolor="#B9B9B9" height="1"></TD>
																							</TR>
																							<tr>
																								<td>
																									<table cellpadding="0" cellspacing="0" width="100%">
																										<col width="150"></col>
																										<col width=""></col>
																										<TR>
																											<TD class="table_cell"><b><img src="images/icon_point2.gif" border="0" title=""></b>������� ��й�ȣ</TD>
																											<TD class="td_con1">
																												<input type="password" name="changepwd" value="<?=$cancelPWD?>"/> <a href="javascript:changePasswd('pwd');"><img src="images/btn_savepass.gif" align="absmiddle" border="0" alt="��й�ȣ ����" /></a>
																											</TD>
																										</TR>
																									</table>
																								</td>
																							</tr>
																							<TR>
																								<TD bgcolor="#B9B9B9" height="1"></TD>
																							</TR>
																						</table>
																					</td>
																				</tr>
																				<tr>
																					<td height="30"></td>
																				</tr>
																			</table>
																		</td>
																	</tr>
																	<!--��й�ȣ-->
																	
																	<tr>
																		<td>
																			<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
																				<TR>
																					<TD><IMG SRC="images/shop_payment_stitle2.gif" WIDTH="192" HEIGHT=31 ALT=""></TD>
																					<TD width="100%" background="images/shop_basicinfo_stitle_bg.gif">&nbsp;</TD>
																					<TD><IMG SRC="images/shop_basicinfo_stitle_end.gif" WIDTH=10 HEIGHT=31 ALT=""></TD>
																				</TR>
																			</TABLE>
																		</td>
																	</tr>
																	<tr>
																		<td height="3"></td>
																	</tr>
																	<tr>
																		<td style="padding-bottom:3pt;">
																			<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
																				<TR>
																					<TD><IMG SRC="images/distribute_01.gif"></TD>
																					<TD COLSPAN=2 background="images/distribute_02.gif"></TD>
																					<TD><IMG SRC="images/distribute_03.gif"></TD>
																				</TR>
																				<TR>
																					<TD background="images/distribute_04.gif"></TD>
																					<TD class="notice_blue"><IMG SRC="images/distribute_img.gif" ></TD>
																					<TD width="100%" class="notice_blue"><p>1) ������ �Һο� ���� �߰�������� ���� �δ��Դϴ�.(�⺻ ī�����������+�������Һ� ������)<br>
																					2) ������ �Һδ� �����߰�ȸ��� ������ ��� �� ����˴ϴ�.(�����߰�ȸ�翡�� �����ϴ� �����޴����� ��û �� ����)<br>
																					3) ������ �Һ� ���� ī��, ������ �������� ���� �߰������� ���̵��� �ش� �����߰�ȸ���� �ȳ��� �����ø� �˴ϴ�.<br>
																					4) ī��縶�� ��ü ������ ������縦 �ϴ� ��쿡�� �����߰�ȸ���� �����޴����� �����ڼ��� ���� ������ �Һ� �������� ����.
																					</p></TD>
																					<TD background="images/distribute_07.gif"></TD>
																				</TR>
																				<TR>
																					<TD><IMG SRC="images/distribute_08.gif"></TD>
																					<TD COLSPAN=2 background="images/distribute_09.gif"></TD>
																					<TD><IMG SRC="images/distribute_10.gif"></TD>
																				</TR>
																			</TABLE>
																		</td>
																	</tr>
																	<tr>
																		<td height=3></td>
																	</tr>
																	<tr>
																		<td>
																			<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
																				<TR>
																					<TD colspan=2 background="images/table_top_line.gif"></TD>
																				</TR>
																				<TR>
																					<TD class="table_cell" width="139"><img src="images/icon_point2.gif" width="8" height="11" border="0">������ �Һ� ����</TD>
																					<TD class="td_con1" ><input type=radio id="idx_card_splittype1" name=up_card_splittype value="N" <?=$check_card_splittypeN?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_card_splittype1>������ �Һ� ������</label><BR>
																					<input type=radio id="idx_card_splittype2" name=up_card_splittype value="Y" <?=$check_card_splittypeY?> onclick="checkmess()"><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_card_splittype2>������ �Һ� ����(�����δ�,<font color=red>��ü��ǰ����</font>)</label><BR>
																					<!--<input type=radio id="idx_card_splittype3" name=up_card_splittype value="O" <?=$check_card_splittypeO?> onclick="checkmess()"><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_card_splittype3>������ �Һ� ����(�����δ�,<font color=red>������ǰ����</font> �� <B>[�ǸŻ�ǰ�űԵ��/����]</B>���� üũ)</label>--></TD>
																				</TR>
																				<TR>
																					<TD colspan="2" background="images/table_con_line.gif"></TD>
																				</TR>
																					<TR>
																						<TD class="table_cell" width="139"><img src="images/icon_point2.gif" width="8" height="11" border="0">������ ���� ������</TD>
																						<TD class="td_con1" >
																							<select name=up_card_splitmonth class="select">
																								<?
																									for ($i=3;$i<=12;$i++) {
																									echo "<option value='$i'";
																									if ($i==$card_splitmonth) echo " selected";
																									echo ">".$i."����\n";
																									}
																								?>
																							</select> �̳�<FONT color=#0054a6><BR> </FONT><span class="font_blue">* 3������ ���ý� 3������ ������, ������ �Ϲ��Һ�<BR> * �����߰�ȸ�� �����޴����� ī�帶�� �Һΰ����� ���ð���, ��, ������ �Һΰ����� �ܿ� ��� �Ϲ��Һ�<BR>* �����߰�ȸ���� �����޴��� ������ ������ ���� �������� �����ϰ� ����</span>
																						</TD>
																					</TR>
																				<TR>
																					<TD colspan="2" background="images/table_con_line.gif"></TD>
																				</TR>
																				<TR>
																					<TD class="table_cell" width="139"><img src="images/icon_point2.gif" width="8" height="11" border="0">������ ���ɱݾ�</TD>
																					<TD class="td_con1" ><input type=text name=up_card_splitprice value="<?=$card_splitprice?>" size=7 maxlength=7 class="input_selected"> �̻�(�޸�����)</TD>
																				</TR>
																				<TR>
																					<TD colspan=2 background="images/table_top_line.gif"></TD>
																				</TR>
																			</TABLE>
																		</td>
																	</tr>
																	<tr>
																		<td height="30"></td>
																	</tr>
																	<tr>
																		<td>
																			<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
																				<TR>
																					<TD><IMG SRC="images/shop_payment_stitle3.gif" WIDTH="192" HEIGHT=31 ALT=""></TD>
																					<TD width="100%" background="images/shop_basicinfo_stitle_bg.gif">&nbsp;</TD>
																					<TD><IMG SRC="images/shop_basicinfo_stitle_end.gif" WIDTH=10 HEIGHT=31 ALT=""></TD>
																				</TR>
																			</TABLE>
																		</td>
																	</tr>
																	<tr>
																		<td height=3></td>
																	</tr>
																	<tr>
																		<td>
																			<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
																				<TR>
																					<TD background="images/table_top_line.gif" colspan="2"></TD>
																				</TR>
																				<TR>
																					<TD class="table_cell" align="center">���� �� ���¹�ȣ</TD>
																					<TD class="table_cell1" align="center">���ð��»���</TD>
																				</TR>
																				<TR>
																					<TD colspan="2" background="images/table_con_line.gif"></TD>
																				</TR>
																					<?
																						if (strlen($bank_account)>0) {
																							$temp = $bank_account;
																							$tok = strtok($temp,",");
																							while ($tok) {
																								echo "<TR><TD class=\"td_con2\">$tok</td>\n";
																								echo "<TD class=\"td_con1\" align=\"center\"><a href=\"javascript:AccountDel('$tok');\"><img src=\"images/btn_delet.gif\" width=\"108\" height=\"25\" border=\"0\"></a></td></tr>\n";
																								echo "<TR><TD colspan=\"2\" background=\"images/table_con_line.gif\"></TD></TR>";
																								$tok = strtok(",");
																							}
																						} else {
																							echo "<TR><TD class=\"td_con2\" colspan=2 align=center>��ϵ� ���������� �����ϴ�.</td></tr>\n";
																							echo "<TR><TD colspan=\"2\" background=\"images/table_con_line.gif\"></TD></TR>";
																						}
																					?>
																				<TR>
																					<TD background="images/table_top_line.gif" colspan="2"></TD>
																				</TR>
																			</TABLE>
																		</td>
																	</tr>
																	<tr>
																		<td height=2></td>
																	</tr>
																	<tr>
																		<td>
																			<table cellpadding="0" cellspacing="0" width="100%">
																				<tr>
																					<td  bgcolor="#EDEDED" style="padding:4pt;">
																						<table cellpadding="0" cellspacing="0" width="100%" bgcolor="white">
																							<tr>
																								<td width="100%">
																									<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
																										<TR>
																											<TD  colspan="2" height="35" background="images/blueline_bg.gif"><p align="center"><b><font color="#0099CC">���¹�ȣ �Է��ϱ�</font></b></TD>
																										</TR>
																										<TR>
																											<TD colspan="2" background="images/table_con_line.gif"></TD>
																										</TR>
																										<TR>
																											<TD width="148" class="table_cell"><b><img src="images/icon_point2.gif" width="8" height="11" border="0">���� �� ���¹�ȣ</b></TD>
																											<TD  class="td_con1"><input type=text name=up_newbank_account1 size=27 maxlength=50 class="input">&nbsp;&nbsp;<span class="font_orange">* ��) 00���� 123-4567-8910 ������� �Է�</span></TD>
																										</TR>
																										<TR>
																											<TD colspan="2" background="images/table_con_line.gif"></TD>
																										</TR>
																										<TR>
																											<TD width="148" class="table_cell"><b><img src="images/icon_point2.gif" width="8" height="11" border="0">������</b></TD>
																											<TD  class="td_con1">
																												<table cellpadding="0" cellspacing="0" width="100%">
																													<tr>
																														<td width="569"><input type=text name=up_newbank_account2 size=12 maxlength=30 class="input"> <a href="javascript:AccountAdd();"><img src="images/btn_bank.gif" width="105" height="26" border="0" align=absmiddle></a></td>
																													</tr>
																												</table>
																											</TD>
																										</TR>
																									</TABLE>
																								</td>
																							</tr>
																						</table>
																					</td>
																				</tr>
																			</table>
																		</td>
																	</tr>
																	<tr>
																		<td height="30"></td>
																	</tr>
																	<tr>
																		<td>
																			<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
																				<TR>
																					<TD><IMG SRC="images/shop_payment_stitle4.gif" WIDTH="192" HEIGHT=31 ALT=""></TD>
																					<TD width="100%" background="images/shop_basicinfo_stitle_bg.gif">&nbsp;</TD>
																					<TD><IMG SRC="images/shop_basicinfo_stitle_end.gif" WIDTH=10 HEIGHT=31 ALT=""></TD>
																				</TR>
																			</TABLE>
																		</td>
																	</tr>
																	<tr>
																		<td style="padding-top:3pt; padding-bottom:3pt;">
																			<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
																				<TR>
																					<TD><IMG SRC="images/distribute_01.gif"></TD>
																					<TD COLSPAN=2 background="images/distribute_02.gif"></TD>
																					<TD><IMG SRC="images/distribute_03.gif"></TD>
																				</TR>
																				<TR>
																					<TD background="images/distribute_04.gif"></TD>
																					<TD class="notice_blue"><IMG SRC="images/distribute_img.gif" ></TD>
																					<TD width="100%" class="notice_blue"><p>1) ����â�� ������ �Ա� �⺻�ȳ� ���� <b>(�ݵ�� �ֹ��� �������� �Ա�)</b>�� ���ϴ� ������ ������ �� �ֽ��ϴ�.<br>2) ����â�� �ſ�ī�� ���� �⺻�ȳ� ���� <b>[�������ǸŰ�]</b>�� ���ϴ� ������ ������ �� �ֽ��ϴ�.</p></TD>
																					<TD background="images/distribute_07.gif"></TD>
																				</TR>
																				<TR>
																					<TD><IMG SRC="images/distribute_08.gif"></TD>
																					<TD COLSPAN=2 background="images/distribute_09.gif"></TD>
																					<TD><IMG SRC="images/distribute_10.gif"></TD>
																				</TR>
																			</TABLE>
																		</td>
																	</tr>
																	<tr>
																		<td>
																			<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
																				<TR>
																					<TD colspan=2 background="images/table_top_line.gif"></TD>
																				</TR>
																				<TR>
																					<TD class="table_cell" width="139"><img src="images/icon_point2.gif" width="8" height="11" border="0">������ �Ա�<br>&nbsp;&nbsp;�ȳ����� ����</TD>
																					<TD class="td_con1" ><input type=text name=up_bankmess value="<?=trim($bankmess)?>" size=80 maxlength=80 class="input" style=width:100%></TD>
																				</TR>
																				<TR>
																					<TD colspan="2" background="images/table_con_line.gif"></TD>
																				</TR>
																				<TR>
																					<TD class="table_cell" width="139"><img src="images/icon_point2.gif" width="8" height="11" border="0">�ſ�ī�� ����<br>&nbsp;&nbsp;�ȳ����� ����</TD>
																					<TD class="td_con1" ><input type=text name=up_cardmess value="<?=trim($cardmess)?>" size=50 maxlength=50 class="input" style=width:100%></TD>
																				</TR>
																				<TR>
																					<TD colspan=2 background="images/table_top_line.gif"></TD>
																				</TR>
																			</TABLE>
																		</td>
																	</tr>
																	<tr>
																		<td height="30"></td>
																	</tr>
																	<tr>
																		<td>
																			<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
																				<TR>
																				<TD><IMG SRC="images/shop_payment_stitle5.gif" WIDTH="192" HEIGHT=31 ALT=""></TD>
																				<TD width="100%" background="images/shop_basicinfo_stitle_bg.gif">&nbsp;</TD>
																				<TD><IMG SRC="images/shop_basicinfo_stitle_end.gif" WIDTH=10 HEIGHT=31 ALT=""></TD>
																				</TR>
																			</TABLE>
																		</td>
																	</tr>
																	<tr>
																		<td style="padding-top:3pt; padding-bottom:3pt;">
																			<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
																				<TR>
																					<TD><IMG SRC="images/distribute_01.gif"></TD>
																					<TD COLSPAN=2 background="images/distribute_02.gif"></TD>
																					<TD><IMG SRC="images/distribute_03.gif"></TD>
																				</TR>
																				<TR>	
																					<TD background="images/distribute_04.gif"></TD>
																					<TD class="notice_blue"><IMG SRC="images/distribute_img.gif" ></TD>
																					<TD width="100%" class="notice_blue"><p>1) ���ݱ��Ű��� + ī����� ������ ����Դϴ�.<br>2) ���ݱ��ſ� ī������� ���� ������ �����Ǿ� ������ ���� å���� �߻��� �� �ֽ��ϴ�.</p></TD>
																					<TD background="images/distribute_07.gif"></TD>
																				</TR>
																				<TR>
																					<TD><IMG SRC="images/distribute_08.gif"></TD>
																					<TD COLSPAN=2 background="images/distribute_09.gif"></TD>
																					<TD><IMG SRC="images/distribute_10.gif"></TD>
																				</TR>
																			</TABLE>
																		</td>
																	</tr>
																	<tr>
																		<td>
																			<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
																				<TR>
																					<TD colspan=2 background="images/table_top_line.gif"></TD>
																				</TR>
																				<TR>
																					<TD class="table_cell" width="139"><img src="images/icon_point2.gif" width="8" height="11" border="0">ī����� ������ �߰�</TD>
																					<TD class="td_con1"><input type=text name=up_card_payfee value="<?=$card_payfee?>" size=5 maxlength=2 style="font-size:9pt" class="input">% �� �����Ḧ <span class="font_orange">�߰�</span> (10�� ���� ����)</TD>
																				</TR>
																				<TR>
																					<TD colspan=2 background="images/table_top_line.gif"></TD>
																				</TR>
																			</TABLE>
																		</td>
																	</tr>
																	<tr>
																		<td height="30"></td>
																	</tr>
																	<tr>
																		<td>
																			<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
																				<TR>
																				<TD><IMG SRC="images/shop_payment_stitle6.gif" WIDTH="192" HEIGHT=31 ALT=""></TD>
																				<TD width="100%" background="images/shop_basicinfo_stitle_bg.gif">&nbsp;</TD>
																				<TD><IMG SRC="images/shop_basicinfo_stitle_end.gif" WIDTH=10 HEIGHT=31 ALT=""></TD>
																				</TR>
																			</TABLE>
																		</td>
																	</tr>
																	<tr>
																		<td style="padding-top:3pt; padding-bottom:3pt;">
																			<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
																				<TR>
																					<TD><IMG SRC="images/distribute_01.gif"></TD>
																					<TD COLSPAN=2 background="images/distribute_02.gif"></TD>
																					<TD><IMG SRC="images/distribute_03.gif"></TD>
																				</TR>
																				<TR>
																					<TD background="images/distribute_04.gif"></TD>
																					<TD class="notice_blue"><IMG SRC="images/distribute_img.gif" ></TD>
																					<TD width="100%" class="notice_blue"><p>1) ���ݰ����� ����/�߰� �������� �ִ� ��� ( ī����������� �߰��� ���� ���Ұ�)<br>2) �߰� �������� �α����� ȸ�����Ը� ����˴ϴ�.(�߰� ������ �Ϲ�ȸ�����Ե� ����)<br>3) ���ݱ��ſ� ī������� ���� ������ �����Ǿ� ������ ���� å���� �߻��� �� �ֽ��ϴ�.</p></TD>
																					<TD background="images/distribute_07.gif"></TD>
																				</TR>
																				<TR>
																					<TD><IMG SRC="images/distribute_08.gif"></TD>
																					<TD COLSPAN=2 background="images/distribute_09.gif"></TD>
																					<TD><IMG SRC="images/distribute_10.gif"></TD>
																				</TR>
																			</TABLE>
																		</td>
																	</tr>
																	<tr>
																		<td>
																			<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
																				<TR>
																					<TD colspan=2 background="images/table_top_line.gif"></TD>
																				</TR>
																				<TR>
																					<TD class="table_cell" width="139"><img src="images/icon_point2.gif" width="8" height="11" border="0">���ݰ��� ����</TD>
																					<TD class="td_con1"><input type=text name=up_bank_percent value="<?=$bank_percent?>" size=5 maxlength=2 style="font-size:9pt" class="input">%		<select name=up_saletype class="input">
																							<option value="+" <?=($saletype=="+"?"selected":"")?>>����
																							<option value="-" <?=($saletype=="-"?"selected":"")?>>����
																						</select> ���� (10������ ����, ��ۺ�� ����)
																					</TD>
																				</TR>
																				<TR>
																					<TD colspan=2 background="images/table_top_line.gif"></TD>
																				</TR>
																			</TABLE>
																		</td>
																	</tr>
																	<tr>
																		<td height="30"></td>
																	</tr>
																	<tr>
																		<td>
																			<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
																				<TR>
																					<TD><IMG SRC="images/shop_deli_stitle7.gif" WIDTH="180" HEIGHT=31 ALT=""></TD>
																					<TD width="100%" background="images/shop_basicinfo_stitle_bg.gif">&nbsp;</TD>
																					<TD><IMG SRC="images/shop_basicinfo_stitle_end.gif" WIDTH=10 HEIGHT=31 ALT=""></TD>
																				</TR>
																			</TABLE>
																		</td>
																	</tr>
																	<tr>
																		<td height=3></td>
																	</tr>
																	<tr>
																		<td>
																			<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
																				<TR>
																					<TD><IMG SRC="images/distribute_01.gif"></TD>
																					<TD COLSPAN=2 background="images/distribute_02.gif"></TD>
																					<TD><IMG SRC="images/distribute_03.gif"></TD>
																				</TR>
																				<TR>
																					<TD background="images/distribute_04.gif"></TD>
																					<TD class="notice_blue"><IMG SRC="images/distribute_img.gif" ></TD>
																					<TD width="100%" class="notice_blue">1) �ֹ� ���ɱݾ� ���� ���� �ݾ��� �ֹ��� ���� �ʽ��ϴ�.(���� ��ǰ�ݾ� ���� - ��۷� �� ī������� ����)<br>2) 0���� �Է��ϸ� ��� �ݾ��� �ֹ��˴ϴ�.<br>3) �ſ�ī�� �ֹ� ���ɱݾ� ���� ���� �ݾ��� ������ �Ա��� �����մϴ�.<br>&nbsp;&nbsp;&nbsp;&nbsp;�ſ�ī�� �ֹ� ���ɱݾ��� ������ �������� ũ�ų� ���ƾ� �մϴ�.<br>4) ������ ������ �ſ�ī�� ������ ������ �����Ǿ� ������ ���� å���� �߻��� �� �ֽ��ϴ�.</TD>
																					<TD background="images/distribute_07.gif"></TD>
																				</TR>
																				<TR>
																					<TD><IMG SRC="images/distribute_08.gif"></TD>
																					<TD COLSPAN=2 background="images/distribute_09.gif"></TD>
																					<TD><IMG SRC="images/distribute_10.gif"></TD>
																				</TR>
																			</TABLE>
																		</td>
																	</tr>
																	<tr>
																		<td height=3></td>
																	</tr>
																	<tr>
																		<td>
																			<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
																				<TR>
																					<TD colspan=2 background="images/table_top_line.gif"></TD>
																				</TR>
																				<TR>
																					<TD class="table_cell" width="139"><img src="images/icon_point2.gif" width="8" height="11" border="0">������ ����</TD>
																					<TD class="td_con1"> �ֹ� ���� �ݾ� : <input type=text name=up_bank_miniprice size=15 maxlength=7 value="<?=$bank_miniprice?>" class="input">��</TD>
																				</TR>
																				<TR>
																					<TD colspan="2" background="images/table_con_line.gif"></TD>
																				</TR>
																				<TR>
																					<TD class="table_cell" width="139"><img src="images/icon_point2.gif" width="8" height="11" border="0">�ſ�ī�� ����</TD>
																					<TD class="td_con1" > �ֹ� ���� �ݾ� : <input type=text name=up_card_miniprice size=15 maxlength=7 value="<?=$card_miniprice?>" class="input">��</TD>
																				</TR>
																				<TR>
																					<TD colspan=2 background="images/table_top_line.gif"></TD>
																				</TR>
																			</TABLE>
																		</td>
																	</tr>
																	<tr>
																		<td height="30"></td>
																	</tr>
																	<tr>
																		<td>
																			<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
																				<TR>
																					<TD width="20%"><IMG SRC="images/shop_payment_stitle7.gif"  HEIGHT=31 ALT="�Ҽȼ��� �ֹ���Ҽ���"></TD>
																					<TD width="80%" background="images/shop_basicinfo_stitle_bg.gif">&nbsp;</TD>
																					<TD><IMG SRC="images/shop_basicinfo_stitle_end.gif" WIDTH=10 HEIGHT=31 ALT=""></TD>
																				</TR>
																			</TABLE>
																		</td>
																	</tr>
																	<tr>
																		<td height=3></td>
																	</tr>
																	<!-- ��ȣ���� cron �ȵ��ư�. -->
																	<!-- <tr>
																		<td>
																			<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
																				<TR>
																					<TD><IMG SRC="images/distribute_01.gif"></TD>
																					<TD COLSPAN=2 background="images/distribute_02.gif"></TD>
																					<TD><IMG SRC="images/distribute_03.gif"></TD>
																				</TR>
																				<TR>
																					<TD background="images/distribute_04.gif"></TD>
																					<TD class="notice_blue"><IMG SRC="images/distribute_img.gif" ></TD>
																					<TD width="100%" class="notice_blue">1) �Ҽȼ��� ���ż��� �ο� �̴޼��� ������� ��� �ڵ�/���� ���� ����.</TD>
																					<TD background="images/distribute_07.gif"></TD>
																				</TR>
																				<TR>
																					<TD><IMG SRC="images/distribute_08.gif"></TD>
																					<TD COLSPAN=2 background="images/distribute_09.gif"></TD>
																					<TD><IMG SRC="images/distribute_10.gif"></TD>
																				</TR>
																			</TABLE>
																		</td>
																	</tr>
																	<tr>
																		<td height=3></td>
																	</tr> -->
																	<tr>
																		<td>
																			<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
																				<TR>
																					<TD><IMG SRC="images/distribute_01.gif"></TD>
																					<TD COLSPAN=2 background="images/distribute_02.gif"></TD>
																					<TD><IMG SRC="images/distribute_03.gif"></TD>
																				</TR>
																				<TR>
																					<TD background="images/distribute_04.gif"></TD>
																					<TD class="notice_blue"><IMG SRC="images/distribute_img.gif" ></TD>
																					<TD width="100%" class="notice_blue">1) �Ҽȼ��� ���ż��� �ο� �̴޼��� ������� ��� �ڵ�/���� ���� ����.</TD>
																					<TD background="images/distribute_07.gif"></TD>
																				</TR>
																				<TR>
																					<TD><IMG SRC="images/distribute_08.gif"></TD>
																					<TD COLSPAN=2 background="images/distribute_09.gif"></TD>
																					<TD><IMG SRC="images/distribute_10.gif"></TD>
																				</TR>
																			</TABLE>
																		</td>
																	</tr>
																	<tr>
																		<td height=3></td>
																	</tr>
																	<tr>
																		<td>
																			<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
																				<tr>
																					<td height=3></td>
																				</tr>
																				<TR>
																					<TD colspan=2 background="images/table_top_line.gif"></TD>
																				</TR>
																				<TR>
																					<TD class="table_cell" width="139"><img src="images/icon_point2.gif" width="8" height="11" border="0">�������</TD>
																					<TD class="td_con1" > <input type=radio id="up_auto_order_cancel1" name=up_auto_order_cancel value="Y" <?=($auto_order_cancel =="Y")? "checked":""?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=up_auto_order_cancel1>�ڵ��������</label> <input type=radio id="up_auto_order_cancel2" name=up_auto_order_cancel value="N" <?=($auto_order_cancel =="N")? "checked":""?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=up_auto_order_cancel2>������ �������</label></TD>
																				</TR>
																				<TR>
																					<TD colspan=2 background="images/table_top_line.gif"></TD>
																				</TR>
																			</TABLE>
																		</td>
																	</tr>
																	<tr>
																		<td>&nbsp;</td>
																	</tr>
																	<tr>
																		<td align="center"><a href="javascript:CheckForm();"><img src="images/botteon_save.gif" width="113" height="38" border="0"></a></td>
																	</tr>
																</table>
															</form>
														</td>
													</tr>
													<tr>
														<td height=20></td>
													</tr>
													<tr>
														<td>
															<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
																<TR>
																	<TD><IMG SRC="images/manual_top1.gif" WIDTH=15 HEIGHT=45 ALT=""></TD>
																	<TD><IMG SRC="images/manual_title.gif" WIDTH=113 HEIGHT=45 ALT=""></TD>
																	<TD width="100%" background="images/manual_bg.gif"></TD>
																	<TD background="images/manual_bg.gif"></TD>
																	<TD><IMG SRC="images/manual_top2.gif" WIDTH=18 HEIGHT=45 ALT=""></TD>
																</TR>
																<TR>
																	<TD background="images/manual_left1.gif"></TD>
																	<TD COLSPAN=3 width="100%" valign="top" bgcolor="white" style="padding-top:8pt; padding-bottom:8pt; padding-left:4pt;" class="menual_bg">
																		<table cellpadding="0" cellspacing="0" width="100%">
																			<tr>
																				<td width="20" align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
																				<td ><span class="font_dotline">�������� ���� �� ����</span></td>
																			</tr>
																			<tr>
																				<td width="20" align="right">&nbsp;</td>
																				<td  class="space_top">- �ǽð�������ü, �������, �ڵ�������, ����ũ�ΰ����� �����߰�ȸ��� ��� �� ȸ�翡 �˷��ֽø� �˴ϴ�.</td>
																			</tr>
																			<tr>
																				<td width="20" align="right">&nbsp;</td>
																				<td  class="space_top">- �̿� �߰����� ���� ������ ���α׷��� ������ ������ �������ܸ� �����մϴ�.</td>
																			</tr>
																		</table>
																	</TD>
																	<TD background="images/manual_right1.gif"></TD>
																</TR>
																<TR>
																	<TD><IMG SRC="images/manual_left2.gif" WIDTH=15 HEIGHT=8 ALT=""></TD>
																	<TD COLSPAN=3 background="images/manual_down.gif"></TD>
																	<TD><IMG SRC="images/manual_right2.gif" WIDTH=18 HEIGHT=8 ALT=""></TD>
																</TR>
															</TABLE>
														</td>
													</tr>
													<tr>
														<td height="50"></td>
													</tr>
												</table>
											</td>
											<td width="16" background="images/con_t_02_bg.gif"></td>
										</tr>
										<tr>
											<td width="16"><img src="images/con_t_04.gif" width="16" height="16" border="0"></td>
											<td background="images/con_t_04_bg.gif"></td>
											<td width="16"><img src="images/con_t_03.gif" width="16" height="16" border="0"></td>
										</tr>
										<tr>
											<td height="20"></td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<?=$onload?>
<? INCLUDE "copyright.php"; ?>