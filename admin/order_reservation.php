<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
INCLUDE ("access.php");

include($Dir.'lib/ext/reservation_func.php');

####################### ������ ���ٱ��� check ###############
$PageCode = "or-1";
$MenuCode = "order";
if (!$_usersession->isAllowedTask($PageCode)) {
	INCLUDE ("AccessDeny.inc.php");
	exit;
}
#########################################################

	$more = $_GET[more];
	if( $_GET[vdate] ) {
		$chan_Y =substr($_GET[vdate],0,4);
		$chan_M =substr($_GET[vdate],4,2);
		$chan_D =substr($_GET[vdate],6,2);
		$vdate = $_GET[vdate];
	} else {
		$chan_Y=date("Y");
		$chan_M=date("m");
		$chan_D=date("d");
		$vdate = $chan_Y.$chan_M.$chan_D;
	}

	$chkDate = $chan_Y."�� ".($chan_M?$chan_M."�� ":"").($chan_D?$chan_D."��":"");



	$t=mktime(0,0,0,$chan_M,1,$chan_Y);
	$week=date("w",$t);
	$lastday=date("t",$t);
	$day=1;
?>

<? INCLUDE "header.php"; ?>

<script type="text/javascript" src="lib.js.php"></script>
<script language="JavaScript">
	<!--
		// Ķ���� ��/�� �ٲ�
		function dateChg ( date, more ) {
			location.href="?vdate="+date+( more ? "&more=A" : "" );
		}

		// �޸�
		function MemoMouseOut(target) {
			obj = event.srcElement;
			WinObj=eval("document.all."+target);
			WinObj.style.visibility = "hidden";
			clearTimeout(obj._tid);
		}

		// �ֹ� �� ����
		function OrderDetailView(ordercode) {
			window.open("order_detail.php?ordercode="+ordercode,"orderdetail_a","scrollbars=yes,width=724,height=600");
		}

		// ����¡
		function searchLoad (url) {
			location.href=url;
		}
	-->
</script>

<style>
/* ������ ����Ʈ ��Ÿ�Ͻ�Ʈ */
.pageList{
	width:100%;
	clear:both;
	margin:0 auto 5px;
	padding:5px 0;
	text-align:center;
	overflow:hidden;
}
.pageList a,.pageList strong{
	display:inline-block;
	position:relative;
	margin-right:1px;
	/*padding:10px 10px 15px;*/
	padding-top:10px;
	border:0px solid #fff;
	font:bold 14px Verdana;
	line-height:normal;
	color:#000000;
	text-decoration:none;
	width:30px;
	height:30px;
}
.pageList strong{
	border:1px solid #e9e9e9;
	color:#f23219 !important
}
.pageList a{
	border:1px solid #e9e9e9;
	text-decoration:underline
}
</style>
<table cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed">
<tr>
	<td valign="top">
	<table cellpadding="0" cellspacing="0" width=100% style="table-layout:fixed">
	<tr>
		<td>
		<table cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed"  background="images/con_bg.gif">
		<col width=198></col>
		<col width=10></col>
		<col width=></col>
		<tr>
			<td valign="top"  background="images/leftmenu_bg.gif">
			<? include ("menu_order.php"); ?>
			</td>

			<td></td>
			<td valign="top">




				<table cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td height="29" colspan="3">
							<table cellpadding="0" cellspacing="0" width="100%">
							<tr>
								<td height="28" class="link" align="left" background="images/con_link_bg.gif"><img src="images/top_link_house.gif" border="0" valign="absmiddle">������ġ : �ֹ�/���� &gt; �ֹ���ȸ �� ��۰��� &gt; <span class="2depth_select">���/�Ա��Ϻ� �ֹ�����</span></td>
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
			<tr><td height="8"></td></tr>
			<tr>
				<td>
					<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
						<TR>
							<TD><IMG SRC="images/order_reservation_title.gif" ALT=""></TD>
							</tr><tr>
							<TD width="100%" background="images/title_bg.gif" height="21"></TD>
						</TR>
					</TABLE>
				</td>
			</tr>
			<tr><td height="3"></td></tr>
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
							<TD width="100%" class="notice_blue">�����ǰ�� ���� �Ա��Ϻ�, ������ں�, �ֹ����ں� �ֹ���Ȳ �� �ֹ������� Ȯ��/ó���Ͻ� �� �ֽ��ϴ�.</TD>
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
			<tr><td height="20"></td></tr>
			<tr>
				<td>
					<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
						<TR>
							<TD><IMG SRC="images/order_list_stitle1.gif" WIDTH="187" HEIGHT=31 ALT=""></TD>
							<TD width="100%" background="images/shop_basicinfo_stitle_bg.gif"></TD>
							<TD><IMG SRC="images/shop_basicinfo_stitle_end.gif" WIDTH=10 HEIGHT=31 ALT=""></TD>
						</TR>
					</TABLE>
				</td>
			</tr>
			<tr><td height="8"></td></tr>
			<tr>
				<td>
					<table border='0' width='100%' cellpadding="0" cellspacing="0">
						<tr><td background="images/table_top_line.gif" colSpan="2" /></tr>
						<tr>
							<td align="center" class="table_cell">���ڼ���</td>
							<td align="center" class="table_cell1">���� �ǸŻ�ǰ ��Ȳ</td>
						</tr>
						<tr><td background="images/table_con_line.gif" colSpan="2" /></td>
						<tr>
							<td width='300' valign="top" align="center" style="padding:20px 0px;">

								<!-- �޷� ���� -->
								<!-- ��� �޷� -->
								<table width="260" cellpadding="0" cellspacing="0" style="border:0px solid #CDDDE0; margin-bottom:10px;">
									<tr>
										<td>
											<select onchange="dateChg( this.value, moreAll.checked);">
											<?
												for($Yi=2012;$Yi<=date("Y")+2;$Yi++){
													$sel = "";
													if( $Yi.$chan_M==$chan_Y.$chan_M ) $sel = "selected";
													echo "<option value=".$Yi.$chan_M." ".$sel.">".$Yi."</option>";
												}
											?>
											</select>
											��
										</td>
										<td align='center' id='calendarMonth'>
											<select onchange="dateChg( this.value, moreAll.checked);">
											<?
												echo "<option value='".$chan_Y."'>��ü</option>";
												for($Mi=1;$Mi<=12;$Mi++){
													$sel = ($Mi==$chan_M)?"selected":"";
													echo "<option value='".$chan_Y.str_pad($Mi, 2, "0", STR_PAD_LEFT)."' ".$sel.">".str_pad($Mi, 2, "0", STR_PAD_LEFT)."</option>";
												}
											?>
											</select>
											��
										</td>
										<td width="45%" align="right">
											<?
												if( $chan_M > 0 ) {
											?>
											<input type="button" value="��ü����" onclick="dateChg(<?=$chan_Y.$chan_M?>, moreAll.checked);">
											<?
												}
											?>
										</td>
									</tr>
								</table>
								<!-- ��� �޷� �� -->

								<!-- ������ ���� ��� -->
								<table width="260" cellpadding="0" cellspacing="0" style="border:0px solid #CDDDE0; margin-bottom:10px;">
									<tr>
										<td>
											<input type="checkbox" name="moreAll" id="moreAll" value="A" <?=($more=="A"?"checked":"")?> onclick="dateChg(<?=$chan_Y.$chan_M.$chan_D?>, this.checked);">������ ���� ��� ǥ��
										</td>
									</tr>
								</table>

								<!-- �޷� ���̺� ���� -->
								<?
									if( $chan_M > 0 ) {
								?>
								<table width="260" cellpadding="1" cellspacing="1" bgcolor="#B1B5BA">
									<Tr height="25" bgcolor="#F3F3F3" ALIGN="CENTER">
										<Td width='15%' bgcolor='F3F3F3' style='font-family:verdana; color:#FF000A;'>SUN</td>
										<Td width='14%' style='font-family:verdana; color:#000000;'>MON</td>
										<Td width='14%' style='font-family:verdana; color:#000000;'>TUE</td>
										<Td width='14%' style='font-family:verdana; color:#000000;'>WED</td>
										<Td width='14%' style='font-family:verdana; color:#000000;'>THU</td>
										<Td width='14%' style='font-family:verdana; color:#000000;'>FRI</td>
										<Td width='15%' bgcolor='F3F3F3' style='font-family:verdana; color:#000000;'>SAT</td>
									</tr>
									<?
										for($i=1; $i<=6; $i++){
											echo"<Tr>";
											for($j=0; $j<=6; $j++){

												$chan_M=str_pad($chan_M, 2, "0", STR_PAD_LEFT);
												$Cday=str_pad($day, 2, "0", STR_PAD_LEFT);
												$DATE_KEY = $chan_Y.$chan_M.$Cday;

												//üũ����Ʈ (��,��, ���� ������)
												if( $DATE_KEY==$vdate ) {
													$today_print_content="#F6F6C2";
												} else {
													$today_print_content="#FFFFFF";
												}
												if($week==$j || $day>1){
													if($day <= $lastday){
														echo "<Td height=25 valign='top' align='center' bgcolor='".$today_print_content."'>";
														echo "<DIV style='cursor:pointer; width:100%; height:100%;padding-top:5px;' onclick=\"dateChg( ".$DATE_KEY.", moreAll.value);\" >";
														if($j==0) echo "<font color='FF356D'>";
														if($j==6) echo "<font color='5635FF'>";
																	  echo $day;
																	  echo "</font>";
														echo "</DIV>";
														$day++;
													}else{
														echo "<Td valign='top' align='center' bgcolor='#CCCCCC'>";
													}
												}else{
													echo "<Td valign='top' align='center' bgcolor='#CCCCCC'>";
												}
												echo "</td>\n";
											}
											echo"</tr>\n";
										}
									?>
								</table>
								<?
									}
								?>
								<!-- �޷� ���̺� �� -->
								<!-- �޷� �� -->


							</td>
							<td class="td_con1" valign="top" style="padding:24px;">

								<table border="0" cellpadding="0" cellspacing="0" width="100%">
									<tr>
										<td valign="top" style="height:30px;">
											<div style="float:left;"><strong><?=$chkDate?><?=($more=="A"?" ���� ���":"")?></strong></div>
											<?
												if( strlen($_GET['productcode']) > 0 ) {
													echo "<div style=\"float:right;\"><a href=\"?vdate=".$vdate."\"><img src=\"images/btn_reservation_list.gif\" align=\"absmiddle\" border=\"0\" alt=\"������� ���ư���\" /></a></div>";
												}
											?>
										</td>
									</tr>
									<tr>
										<td>

											<?
												$listOption['vdate'] = $vdate; // ��� ����
												$listOption['productcode'] = $_GET['productcode']; // ��ǰ�ڵ�
												$listOption['page'] = $_GET['page']; // ������
												$listOption['more'] = $more; // ������ ���� ��� ǥ�� (A)
												$rsvProdArray = rsvProdList( $listOption );
											?>

											<table border=0 width=100% cellpadding="0" cellspacing="0">
												<tr>
													<td>

														<table border=0 width="100%" cellpadding="0" cellspacing="0">
															<col width="100">
															<col width="100">
															<col width="">
															<col width="80">
															<tr><td background="images/table_top_line.gif" colSpan="4" /></tr>
															<tr>
																<td align="center" class="table_cell">������</td>
																<td align="center" class="table_cell1">�̹���</td>
																<td align="center" class="table_cell1">��ǰ��</td>
																<td align="center" class="table_cell1">���ż�</td>
															</tr>
															<tr><td background="images/table_con_line.gif" colSpan="4" /></tr>
													<?
													if( empty($rsvProdArray) ) {
														echo "
															<tr>
																<td height=\"40\" colspan=\"4\" align=\"center\">
																	<b>".$chkDate."</b>�� �����Ǹ� ��ǰ�� �����ϴ�.
																</td>
															</tr>
															<tr><td background=\"images/table_con_line.gif\" colSpan=\"4\" /></tr>
														";
													}

													foreach ( $rsvProdArray as $listValue ){

														// ��ǰ����
														$listProd = $listValue['product'];
														echo "
															<tr>
																<td align=\"center\" class=\"td_con\">".$listProd['venderName']."</td>
																<td align=\"center\" class=\"td_con1\"><img src=\"/data/shopimages/product/".$listProd['tinyimage']."\" width=\"50\"></td>
																<td class=\"td_con1\"><a href=\"?vdate=".$vdate."&productcode=".$listProd['productcode']."\"><strong>[".$listProd['reservation']."]</strong> ".$listProd['productname']."</a></td>
																<td align=\"center\" class=\"td_con1\">".number_format($listProd['orderCount'])."��</td>
															</tr>
															<tr><td background=\"images/table_con_line.gif\" colSpan=\"4\" /></tr>
														";

														// �ֹ�����
														if( $listValue['order'] ) {
															echo "
																<tr>
																	<td colspan=\"4\" style=\"padding-top:40px;\">
																		<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">
																			<col width=\"100\">
																			<col width=\"100\">
																			<col width=\"\">
																			<col width=\"60\">
																			<col width=\"100\">
																			<col width=\"100\">
																			<col width=\"100\">
																			<col width=\"80\">
																			<tr><td colspan=\"8\" style=\"padding:3px 0px; font-size:11px; letter-spacing:-1px;\">* <b>�ֹ���</b>�� �����Ͻø� �ֹ� �󼼳��� �� ������¸� ������ �� �ֽ��ϴ�.</td></tr>
																			<tr><td background=\"images/table_top_line.gif\" colSpan=\"8\" /></tr>
																			<tr>
																				<td align=\"center\" class=\"table_cell\">�ֹ���</td>
																				<td align=\"center\" class=\"table_cell1\">�ֹ���</td>
																				<td align=\"center\" class=\"table_cell1\">�ɼ�</td>
																				<td align=\"center\" class=\"table_cell1\">����</td>
																				<td align=\"center\" class=\"table_cell1\">��������</td>
																				<td align=\"center\" class=\"table_cell1\">��ۿ���</td>
																				<td align=\"center\" class=\"table_cell1\">��ȯ/ȯ��</td>
																				<td align=\"center\" class=\"table_cell1\">ó���ܰ�</td>
																			</tr>
																			<tr><td background=\"images/table_con_line.gif\" colSpan=\"8\" /></tr>
															";
															foreach ( $listValue['order'] as $listProdOrderKey => $listProdOrder ){

																if( strlen($listProdOrderKey) > 18 ) {

																	// ��ȯ/ȯ��
																	switch($listProdOrder['status']) {
																		  case 'EA': $status = "��ȯ��û";  break;
																		  case 'EB': $status = "��ȯ����";  break;
																		  case 'EC': $status = "��ȯ�Ϸ�";  break;
																		  case 'RA': $status = "<span style='color:red'>ȯ�ҽ�û</span>";  break;
																		  case 'RB': $status = "ȯ������";  break;
																		  case 'RC': $status = "<span style='color:blue'>ȯ�ҿϷ�</span>"; $row2->deli_gbn = "RC"; break;
																		  default : $status = "&nbsp;";  break;
																	}


																	// ���� ����
																	$payState = "";
																	$payState .= $arpm[substr($listProdOrder['paymethod'],0,1)]."<br>";

																	if(preg_match("/^(B){1}/", $listProdOrder['paymethod'])) {	//������
																		if (strlen($listProdOrder['bank_date'])==9 && substr($listProdOrder['bank_date'],8,1)=="X") {
																			$payState .= "<font color=005000> [ȯ��]</font>";
																		} else if (strlen($listProdOrder['bank_date'])>0) {
																			$payState .= " <font color=004000>[�ԱݿϷ�]</font>";
																		} else {
																			if( !eregi("C|E",$listProdOrder['deli_gbn']) ) {
																				$payState .= "<font color=004000> [���Ա�]</font>";
																			}
																		}
																	} else if(preg_match("/^(V){1}/", $listProdOrder['paymethod'])) {	//������ü
																		if (strcmp($listProdOrder['pay_flag'],"0000")!=0) $payState .= " <font color=#757575>[��������]</font>";
																		else if ($listProdOrder['pay_flag']=="0000" && $listProdOrder['pay_admin_proc']=="C") $payState .= "<font color=005000> [ȯ��]</font>";
																		else if ($listProdOrder['pay_flag']=="0000") $payState .= "<font color=0000a0> [�����Ϸ�]</font>";
																	} else if(preg_match("/^(M){1}/", $listProdOrder['paymethod'])) {	//�ڵ���
																		if (strcmp($listProdOrder['pay_flag'],"0000")!=0) $payState .= " <font color=#757575>[��������]</font>";
																		else if ($listProdOrder['pay_flag']=="0000" && $listProdOrder['pay_admin_proc']=="C") $payState .= "<font color=005000> [��ҿϷ�]</font>";
																		else if ($listProdOrder['pay_flag']=="0000") $payState .= "<font color=0000a0> [�����Ϸ�]</font>";
																	} else if(preg_match("/^(O|Q){1}/", $listProdOrder['paymethod'])) {	//�������
																		if (strcmp($listProdOrder['pay_flag'],"0000")!=0) $payState .= " <font color=#757575>[�ֹ�����]</font>";
																		else if ($listProdOrder['pay_flag']=="0000" && $listProdOrder['pay_admin_proc']=="C") $payState .= "<font color=005000> [ȯ��]</font>";
																		else if ($listProdOrder['pay_flag']=="0000" && strlen($listProdOrder['bank_date'])==0) $payState .= "<font color=red> [���Ա�]</font>";
																		else if ($listProdOrder['pay_flag']=="0000" && strlen($listProdOrder['bank_date'])>0) $payState .= "<font color=0000a0> [�ԱݿϷ�]</font>";
																	} else {
																		if (strcmp($listProdOrder['pay_flag'],"0000")!=0) $payState .= " <font color=#757575>[ī�����]</font>";
																		else if ($listProdOrder['pay_flag']=="0000" && $listProdOrder['pay_admin_proc']=="N") $payState .= "<font color=red> [ī�����]</font>";
																		else if ($listProdOrder['pay_flag']=="0000" && $listProdOrder['pay_admin_proc']=="Y") $payState .= "<font color=0000a0> [�����Ϸ�]</font>";
																		else if ($listProdOrder['pay_flag']=="0000" && $listProdOrder['pay_admin_proc']=="C") $payState .= "<font color=005000> [��ҿϷ�]</font>";
																	}


																	// ó���ܰ�
																	$deliState = "";
																	switch($listProdOrder['orderDeli']) {
																		case 'S': $deliState .= "��۴��<br />(�߼��غ�)";  break;
																		case 'X':
																			if($listProdOrder['gift']=='1') $deliState .= "������ȣ�߼�";
																			else $deliState .= "��ۿ�û";  break;
																		case 'Y':
																			if($listProdOrder['gift']=='1') $deliState .= "�����������Ϸ�";
																			else if($listProdOrder['gift']=='2') $deliState .= "�����Ϸ�";
																			else $deliState .= "���";
																		break;
																		case 'D': $deliState .= "<font color=blue>��ҿ�û</font>";  break;
																		case 'N': $deliState .= "��ó��";  break;
																		case 'E': $deliState .= "<font color=red>ȯ�Ҵ��</font>";  break;
																		case 'C': $deliState .= "<font color=red>�ֹ����</font>";  break;
																		case 'R': $deliState .= "�ݼ�";  break;
																		case 'H': $deliState .= "���(<font color=red>���꺸��</font>)";  break;
																	}
																	if($listProdOrder['orderDeli']=="D" && strlen($listProdOrder['deli_date'])==14) $deliState .= "<br>(���)";
																	if($listProdOrder['orderDeli']=="R" && substr($listProdOrder['ordercode'],20)!="X") {
																		$deliState .= "<br><button class=button2 style=\"width:45;color:blue\" onclick=\"ReserveInOut('".$listProdOrder['id']."');\">������</button>";
																	}


																	// �ɼ�
																	$options = "";
																	if(preg_match('/\[OPTG[0-9]+\]/',$listProdOrder['opt1_name'])){
																		$osql = "select opt_name from tblorderoption where ordercode='".$listProdOrder['ordercode']."' and productcode='".$listProdOrder['productcode']."' and opt_idx='".$listProdOrder['opt1_name']."' limit 1";
																		if(false !== $ores= mysql_query($osql,get_db_conn())){
																			if(mysql_num_rows($ores)){
																				$options .= mysql_result($ores,0,0);
																				mysql_free_result($ores);
																			}
																		}
																	}else{
																		$options.= (strlen($listProdOrder['opt1_name'])>0?$listProdOrder['opt1_name']."&nbsp;":"");
																		$options.= (strlen($listProdOrder['opt2_name'])>0?$listProdOrder['opt2_name']."&nbsp;":"");
																		$options.= (strlen($listProdOrder['opt3_name'])>0?$listProdOrder['opt3_name']."&nbsp;":"");
																		$options.= (strlen($listProdOrder['opt4_name'])>0?$listProdOrder['opt4_name']."&nbsp;":"");
																	}


																	// �ֹ���
																	$orderDateY =substr($listProdOrder['date'],0,4);
																	$orderDateM =substr($listProdOrder['date'],4,2);
																	$orderDateD =substr($listProdOrder['date'],6,2);
																	$orderDate = $orderDateY."-".$orderDateM."-".$orderDateD;

																	echo "
																		<tr>
																			<td align=\"center\" class=\"td_con\"><A HREF=\"javascript:OrderDetailView('".$listProdOrder['ordercode']."');\"><b>".$orderDate."</b></A></td>
																			<td align=\"center\" class=\"td_con1\">".$listProdOrder['sender_name']."<br />(".$listProdOrder['id'].")</td>
																			<td align=\"center\" class=\"td_con1\">".$options."&nbsp;</td>
																			<td align=\"center\" class=\"td_con1\">".$listProdOrder['quantity']."</td>
																			<td align=\"center\" class=\"td_con1\">".$payState."</td>
																			<td align=\"center\" class=\"td_con1\">".$deliStep[$listProdOrder['orderProdDeli']]."</td>
																			<td align=\"center\" class=\"td_con1\">".$status."</td>
																			<td align=\"center\" class=\"td_con1\">".$deliState."</td>
																		</tr>
																		<tr><td background=\"images/table_con_line.gif\" colSpan=\"8\" /></tr>
																	";
																}
															}
															echo "</table>";
														}
													}
												?>
														</table>
													</td>
												</tr>
											</table>

											<?
												echo $rsvProdArray[$_GET['productcode']]['order']['pageList'];
												//_pr($rsvProdArray);
											?>

										</td>
									</tr>
								</table>


							</td>
						</tr>
						<tr>
							<td background="images/table_top_line.gif" colSpan="2" />
						</tr>
					</table>


				</td>
			</tr>

			<tr>
				<td height=20></td>
			</tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/manual_top1.gif" WIDTH=15 height="45" ALT=""></TD>
					<TD><IMG SRC="images/manual_title.gif" WIDTH=113 height="45" ALT=""></TD>
					<TD width="100%" background="images/manual_bg.gif" height="35"></TD>
					<TD background="images/manual_bg.gif"></TD>
					<td background="images/manual_bg.gif"><IMG SRC="images/manual_top2.gif" WIDTH=18 height="45" ALT=""></td>
				</TR>
				<TR>
					<TD background="images/manual_left1.gif"></TD>
					<TD COLSPAN=3 width="100%" valign="top" bgcolor="white" style="padding-top:8pt; padding-bottom:8pt; padding-left:4pt;" class="menual_bg">
					<table cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td width="20" align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td ><span class="font_dotline">���/�Ա��Ϻ� �ֹ���ȸ</span></td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td  class="space_top">- �Ա��Ϻ�, ������ں�, �ֹ����ں� �ֹ���Ȳ �� �ֹ������� Ȯ��/ó���Ͻ� �� �ֽ��ϴ�.</td>
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
    <tr><td height="20"></td></tr>
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