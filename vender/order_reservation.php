<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/venderlib.php");
INCLUDE ("access.php");

include($Dir.'lib/ext/reservation_func.php');

if(substr($_venderdata->grant_product,1,1)!="Y") {
	echo "<html></head><body onload=\"alert('��ǰ���� ���� ������ �����ϴ�.\\n\\n���θ��� �����Ͻñ� �ٶ��ϴ�.');history.go(-1)\"></body></html>";exit;
}


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



		// �ֹ� �� ����
		function OrderDetailView(ordercode) {
			document.detailform.ordercode.value = ordercode;
			window.open("","vorderdetail","scrollbars=yes,width=800,height=600");
			document.detailform.submit();
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
<table border=0 cellpadding=0 cellspacing=0 width=100% height="100%" style="table-layout:fixed">
<col width=190></col>
<col width=20></col>
<col width=></col>
<col width=20></col>
<tr>
	<td width=190 valign=top nowrap background="images/minishop_leftbg.gif"><? include ("menu.php"); ?></td>
	<td width=20 nowrap></td>
	<td valign=top style="padding-top:20px">

	<table width="100%"  border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td>
		<table width="100%"  border="0" cellpadding="0" cellspacing="0" >
		<tr>
			<td>
				<table border=0 cellpadding=0 cellspacing=0 width=100% >
				<tr>
					<td style="height:50px;"><img src="images/order_reservation_title.gif"></td>
				</tr>
				<tr>
					<td height=5 background="images/minishop_titlebg.gif">
				</tr>
				</table>
			</td>
		</tr>
		<tr><td height=10></td></tr>
		<tr>
			<td>
				<table border=0 cellpadding=0 cellspacing=0 width=100% >
				<tr>
					<td colspan=3 >


						<table cellpadding="10" cellspacing="1" width="100%" bgcolor="#EFEFF2">
							<tr>
								<td  bgcolor="#F5F5F9" style="padding:20px">
									<table border=0 cellpadding=0 cellspacing=0 width=100%>
										<tr>
											<td class="notice_gray" height="20"><img src="images/icon_dot02.gif" border=0 hspace="4">�����ǰ�� ���� �Ա��Ϻ�, ������ں�, �ֹ����ں� �ֹ���Ȳ �� �ֹ������� Ȯ��/ó���Ͻ� �� �ֽ��ϴ�.</td>
										</tr>
										<!--
										<tr>
											<td class="notice_gray" height="20"><img src="images/icon_dot02.gif" border=0 hspace="4">�ǸŰ�, �Һ��ڰ�, ���԰�, ������, ���� �Է½� �޸�(,)�� �Է��� �� �����ϴ�.</td>
										</tr>
										<tr>
											<td class="notice_gray" height="20"><img src="images/icon_dot02.gif" border=0 hspace="4">�ϰ� ���� �� �ݵ�� [�����ϱ�] ��ư�� Ŭ���ؾ� ����˴ϴ�.</td>
										</tr>
										-->
									</table>
								</td>
							</tr>
						</table>



					</td>
				</tr>
				</table>
				</td>
			</tr>

			<!-- ó���� ���� ��ġ ���� -->
			<tr><td height=40></td></tr>
			<tr>
				<td>
					<table width="100%" bgColor="#e7e7e7" border="0" cellSpacing="1" cellPadding="0">
						<tr height="28" align="center" bgColor="#f5f5f5">
							<td align="center" class="table_cell"><b>���ڼ���</b></td>
							<td align="center" class="table_cell1"><b>���� �ǸŻ�ǰ ��Ȳ</b></td>
						</tr>
						<tr>
							<td width="300" valign="top" align="center" style="background:#ffffff; padding:20px 0px;">

								<!-- �޷� ���� -->
								<!-- ��� �޷� -->
								<table width="250" cellpadding="0" cellspacing="0" style="border:0px solid #CDDDE0; margin-bottom:10px;">
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
								<table width='250' cellpadding='1' cellspacing='1' bgcolor='#B1B5BA' style="font-size:12px;">
									<Tr height='25' bgcolor='F3F3F3' ALIGN='CENTER'>
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
														echo "<DIV style='cursor:pointer; width:100%; height:100%;padding-top:5px;' onclick=\"dateChg( ".$DATE_KEY.");\" >";
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
							<td valign="top" style="background:#ffffff; padding:24px;">

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
												$listOption['productcode'] = $_GET['productcode']; // ��� ����
												$listOption['vender'] = $_VenderInfo->getVidx(); // ��� ����
												$listOption['page'] = $_GET['page']; // ������
												$listOption['more'] = $more; // ������ ���� ��� ǥ��
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
															<tr height="30" bgcolor="#f5f5f5">
																<td align="center" style="border-top:1px solid #dddddd; border-bottom:1px solid #eeeeee; border-right:1px solid #dddddd; font-weight:bold;">������</td>
																<td align="center" style="border-top:1px solid #dddddd; border-bottom:1px solid #eeeeee; border-right:1px solid #dddddd; font-weight:bold;">�̹���</td>
																<td align="center" style="border-top:1px solid #dddddd; border-bottom:1px solid #eeeeee; border-right:1px solid #dddddd; font-weight:bold;">��ǰ��</td>
																<td align="center" style="border-top:1px solid #dddddd; border-bottom:1px solid #eeeeee; font-weight:bold;">���ż�</td>
															</tr>
												<?
													if( empty($rsvProdArray) ) {
														echo "
															<tr>
																<td height=\"40\" colspan=\"4\" align=\"center\" style=\"border-bottom:1px solid #eeeeee;\">
																	<b>".$chkDate."</b>�� �����Ǹ� ��ǰ�� �����ϴ�.
																</td>
															</tr>
														";
													}

													foreach ( $rsvProdArray as $listValue ){

														// ��ǰ����
														$listProd = $listValue['product'];
														echo "
															<tr>
																<td align=\"center\" style=\"border-bottom:1px solid #eeeeee; border-right:1px solid #eeeeee;\">".$listProd['venderName']."</td>
																<td align=\"center\" style=\"padding:5px 0px; border-bottom:1px solid #eeeeee; border-right:1px solid #eeeeee;\"><img src=\"/data/shopimages/product/".$listProd['tinyimage']."\" width=\"50\"></td>
																<td style=\"border-bottom:1px solid #eeeeee; border-right:1px solid #eeeeee; padding-left:10px;\"><a href=\"?vdate=".$vdate."&productcode=".$listProd['productcode']."\"><strong>[".$listProd['reservation']."]</strong> ".$listProd['productname']."</a></td>
																<td align=\"center\" style=\"border-bottom:1px solid #eeeeee;\">".number_format($listProd['orderCount'])."��</td>
															</tr>
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
																			<tr height=\"30\" bgcolor=\"#f5f5f5\">
																				<td align=\"center\" style=\"border-top:1px solid #dddddd; border-bottom:1px solid #eeeeee; border-right:1px solid #dddddd; font-weight:bold;\">�ֹ���</td>
																				<td align=\"center\" style=\"border-top:1px solid #dddddd; border-bottom:1px solid #eeeeee; border-right:1px solid #dddddd; font-weight:bold;\">�ֹ���</td>
																				<td align=\"center\" style=\"border-top:1px solid #dddddd; border-bottom:1px solid #eeeeee; border-right:1px solid #dddddd; font-weight:bold;\">�ɼ�</td>
																				<td align=\"center\" style=\"border-top:1px solid #dddddd; border-bottom:1px solid #eeeeee; border-right:1px solid #dddddd; font-weight:bold;\">����</td>
																				<td align=\"center\" style=\"border-top:1px solid #dddddd; border-bottom:1px solid #eeeeee; border-right:1px solid #dddddd; font-weight:bold;\">��������</td>
																				<td align=\"center\" style=\"border-top:1px solid #dddddd; border-bottom:1px solid #eeeeee; border-right:1px solid #dddddd; font-weight:bold;\">��ۿ���</td>
																				<td align=\"center\" style=\"border-top:1px solid #dddddd; border-bottom:1px solid #eeeeee; border-right:1px solid #dddddd; font-weight:bold;\">��ȯ/ȯ��</td>
																				<td align=\"center\" style=\"border-top:1px solid #dddddd; border-bottom:1px solid #eeeeee; font-weight:bold;\">ó���ܰ�</td>
																			</tr>
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
																				$payState .= "<span id=\"orderBankOKobj_".$listProdOrder['ordercode']."\" style=\"cursor:pointer; display:block;\" onclick=\"orderBankOK('".$listProdOrder['ordercode']."');\"><img src='images/orderBankOK.gif' alt='�Ա�ó��'></span>";
																				$payState .= "<span id=\"orderBankOKOKobj_".$listProdOrder['ordercode']."\" style=\"cursor:pointer; display:none;\"><font color=004000> [�ԱݿϷ�]</font></span>";
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
																		case 'S': $deliState .= "��۴��(�߼��غ�)";  break;
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
																			<td align=\"center\" style=\"border-bottom:1px solid #eeeeee; border-right:1px solid #eeeeee;\"><A HREF=\"javascript:OrderDetailView('".$listProdOrder['ordercode']."');\"><b>".$orderDate."</b></A></td>
																			<td align=\"center\" style=\"border-bottom:1px solid #eeeeee; border-right:1px solid #eeeeee;\">".$listProdOrder['sender_name']."<br />(".$listProdOrder['id'].")</td>
																			<td style=\"padding-left:10px; border-bottom:1px solid #eeeeee; border-right:1px solid #eeeeee;\"\">".$options."&nbsp;</td>
																			<td align=\"center\" style=\"border-bottom:1px solid #eeeeee; border-right:1px solid #eeeeee;\">".$listProdOrder['quantity']."</td>
																			<td align=\"center\" style=\"border-bottom:1px solid #eeeeee; border-right:1px solid #eeeeee;\">".$payState."</td>
																			<td align=\"center\" style=\"border-bottom:1px solid #eeeeee; border-right:1px solid #eeeeee;\">".$deliStep[$listProdOrder['orderProdDeli']]."</td>
																			<td align=\"center\" style=\"border-bottom:1px solid #eeeeee; border-right:1px solid #eeeeee;\">".$status."</td>
																			<td align=\"center\" style=\"border-bottom:1px solid #eeeeee;\">".$deliState."</td>
																		</tr>
																	";
																}
															}
															echo "</table>";
														}
													}
												?>
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
					</table>















				</td>
			</tr>
			<!-- ó���� ���� ��ġ �� -->

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

<iframe name="processFrame" src="about:blank" width="0" height="0" scrolling=no frameborder=no></iframe>


<form name=detailform method="post" action="order_detail.php" target="vorderdetail">
<input type=hidden name=ordercode>
</form>

<?=$onload?>

<? INCLUDE "copyright.php"; ?>