<HTML>
<HEAD>
<TITLE><?=$_data->shoptitle?>- �ֹ��� �ۼ�</TITLE>
<META http-equiv="CONTENT-TYPE" content="text/html; charset=EUC-KR">
<META name="description" content="<?=(strlen($_data->shopdescription)>0?$_data->shopdescription:$_data->shoptitle)?>">
<META name="keywords" content="<?=$_data->shopkeyword?>">
<script type="text/javascript" src="<?=$Dir?>lib/lib.js.php"></script>
<script type="text/javascript" src="<?=$Dir?>js/jquery-1.8.3.min.js"></script>
<link type="text/css" rel="stylesheet" href="/css/newUI.css" />
<script>  var $j = jQuery.noConflict(); </script>
<? include($Dir."lib/style.php")?>
<style type="text/css">
	textarea::-webkit-input-placeholder {color: #dddddd;}
	textarea:-ms-input-placeholder {color: #dddddd;}

	.itemListTbl{border-top:1px solid #222222; border-bottom:1px solid #222222; empty-cells:show}
	.itemListTbl thead .thstyle {height:35px; background:#f8f8f8; border:1px solid #e5e5e5; border-left:hidden; }
	.itemListTbl thead .thstyle2 {height:35px; background:#f8f8f8; border:1px solid #e5e5e5; border-left:hidden; border-right:hidden;}

	.itemListTbl tbody .tdstyle {border-right:1px solid #e5e5e5; border-bottom:1px solid #e5e5e5; padding:8px 0px;}
	.itemListTbl tbody .tdstyle2 {border-bottom:1px solid #e5e5e5; padding:5px 10px;}

	.noBodertbl{ border:1px solid #ccc;}
	.noBodertbl tbody .thStyle {color:#000;}
	.noBodertbl tbody th {text-align:right;}
	.noBodertbl tbody td {text-align:right; padding-right:10px;}

	.orderTbl {border:1px solid #bbbbbb;}
	.orderTbl caption {text-align:left; padding:7px 0px;}
	.orderTbl th {width:110px; background:#f8f8f8; border-right:1px solid #eeeeee; border-bottom:1px solid #eeeeee; text-align:left; padding: 5px 0px 5px 15px;}
	.orderTbl td {border-bottom:1px solid #eeeeee; text-align:left; padding: 5px 0px 5px 10px;}
	.orderTbl td.noCont {border:0px; font-size:1px; height:7px; line-height:1px;}
	.orderTbl td.payTbl {width:100%; border:0px;}
	.orderTbl .lastTh {width:110px; border-bottom:none;}
	.orderTbl .lastTd {border-bottom:none;}
	.orderTbl .input {border:1px solid #dddddd; height:22px; line-height:22px;}

	.couponDownArea{ display:block;}

	#addressSelDiv {height:24px; margin-right:10px; margin-bottom:8px; border-bottom:1px solid #ddd;}

	#giftOptionArea table {border:1px solid #fff; margin-top:5px;}
	#giftOptionArea td {border:1px solid #fff; margin-top:5px; font-size:12px; text-align:left;}
</style>
</HEAD>

<body<?=(substr($_data->layoutdata["MOUSEKEY"],0,1)=="Y"?" oncontextmenu=\"return false;\"":"")?><?=(substr($_data->layoutdata["MOUSEKEY"],1,1)=="Y"?" ondragstart=\"return false;\" onselectstart=\"return false;\"":"")?> leftmargin="0" marginwidth="0" topmargin="0" marginheight="0">
<?=(substr($_data->layoutdata["MOUSEKEY"],2,1)=="Y"?"<meta http-equiv=\"ImageToolbar\" content=\"No\">":"")?>
<? include ($Dir.MainDir.$_data->menu_type.".php"); ?>
<form name=form1 action="/front/proc/recommand.php" method=post>
	<table border=0 cellpadding=0 cellspacing=0 width=100%>
		<tr>
			<td align=center>

				<div style="clear:both;height:6px;background:url('/data/design/img/main/top_boxline.gif') no-repeat;font-size:0px;"></div>
				<div style="padding:10px 30px;background:#ffffff url('/data/design/img/main/bg_boxline.gif') repeat-y;overflow:hidden;">
					<div style="float:left;padding-left:50px;height:35px;line-height:35px;background:url('/data/design/img/sub/icon_basket.gif') no-repeat;color:#696969;font-size:25px;font-weight:600;">��õ�ϱ�</div>
					<div style="float:right;margin-top:3px;"><img src="/data/design/img/sub/step_basket.gif" alt="" /></div>
					<div style="clear:both;"></div>
				</div>
				<div style="height:6px;background:url('/data/design/img/main/bot_boxline.gif') no-repeat;font-size:0px;"></div>
				<div style="clear:both;margin-top:20px;height:6px;background:url('/data/design/img/main/top_boxline.gif') no-repeat;font-size:0px;"></div>

				<div style="padding:30px 0px;background:#ffffff url('/data/design/img/main/bg_boxline.gif') repeat-y;text-align:center;overflow:hidden;">

					<div style="width:96%;margin:0px auto;">
					<div style="height:20px; text-align:right; color:#999; font-size:11px; font-family:����; letter-spacing:-1px;">�ֹ������� �Է��Ͻ� ��, <span style="color:red;">������ư</span>�� �����ּ���.</div>
					<div id="recom_table">
					<table border="0" cellpadding="0" cellspacing="0" width="100%" class="itemListTbl">
						<caption style="text-align:left; display:none;">
						�ֹ��� �ۼ�
						</caption>
						<thead>
							<tr>
								<th class="thstyle" style="text-align:center">��ǰ����</th>
								<th class="thstyle" style="text-align:center; width:80px;">����</th>
								<th class="thstyle" style="text-align:center; width:120px">�Ⱓ</th>
								<th class="thstyle" style="text-align:center; width:140px;">��ǰ�ݾ�</th>
								<th class="thstyle" style="text-align:center; width:120px;">���αݾ�</th>
								<th class="thstyle" style="text-align:center; width:120px;">��ۺ�</th>
								<th class="thstyle" style="text-align:center; width:100px;">�Ǹ��ڸ�</th>
								<th class="thstyle" style="text-align:center">�ֹ��ݾ�</th>
							</tr>
						</thead>
						<tbody>
							<?php
		$disctotal = $producttotalprice = 0;
		if($basketItems['productcnt'] <1){ ?>
							<tr>
								<td colspan="9" style="text-align:center; height:30px;">��ٱ��Ͽ� ��ϵ� ��ǰ�� �����ϴ�.</td>
							</tr>
							<?	}else{
			$timgsize = 50;
			foreach($basketItems['vender'] as $vender=>$vendervalue){
				for( $i = 0 ; $i < count($vendervalue['products']) ; $i++ ){
					$product = $vendervalue['products'][$i];
					
					$disctotal += $product['group_discount']*$product['quantity'];
											
					$imageSize = ($product['tinyimage'][$product['tinyimage']['big']] > $timgsize)?$product['tinyimage']['big'].'="'.$timgsize.'"':'';
					$sellChk = ((_empty($product['sell_startdate']) && _empty($product['sell_enddate'])) || ($product['sell_startdate'] >=time() || time()>=$product['sell_enddate']));

					if ($product['deli_type'] == "�ù�") {
						// ��ۺ� ���� ���� ���� ���
						$venderDeliPrint = "";
						$venderDeliPrintCHK = false;
						if( strlen($vendervalue['deli_after']) == 0 AND $vendervalue['conf']['deli_mini'] < 1000000000 AND $vendervalue['delisumprice'] > 0 ) { // ������ �ƴҰ��
							$venderDeliPrint .= "<b>������ ����</b>". ( $vendervalue['conf']['groupDeli'] > 1 ? "(ȸ�� ��� ��ۺ� ��å ����)" : "" );

							if( $vendervalue['delisumprice'] >= $vendervalue['conf']['deli_mini'] ){
								$venderDeliPrint .= "<font color='#ff6600'><strong>[�����]</strong></font>";
								$venderDeliPrintCHK = true;
							}
							$venderDeliPrint .= "&nbsp;:&nbsp;���űݾ��� <b>".number_format($vendervalue['conf']['deli_mini'])."��</b> �̻��� ��� (������ۻ�ǰ ".( $vendervalue['conf']['deli_pricetype'] == "Y" ? "����" : "����" ).")";
						}

						// ��۷�
						$deliPrtChk="";
						$deliPrtRowspan = "";
						if($product['deli_price']>0){
							if($product['deli']=="Y"){
								$deliPrt = "������<br>(". ( ($product['deli_price']*$product['quantity']) > 0 ? number_format($product['deli_price']*$product['quantity'])."��" : "����" ) .")";
							}else if($product['deli']=="N") {
								$deliPrt = "������<br />(". ( $product['deli_price'] > 0 ? number_format($product['deli_price'])."��" : "����" ) .")";
							}
						}else if($product['deli']=="F" || $product['deli']=="G"){
							$deliPrt = ($product['deli']=="F"?'��������':'����');
						}else{
							$deliPrt  = "�⺻��ۺ�<br/>(";
							if ($vendervalue['conf']['deli_price'] > 0) {
								if ($vender == 0 && $venderDeliPrintCHK == true) {
									$deliPrt .= "����";
								} else {
									$totaldeliprice += $vendervalue['conf']['deli_price'];
									$deliPrt .= number_format($vendervalue['conf']['deli_price'])."��";
								}
							} else {
								$deliPrt .= "����";
							}
							$deliPrt .= ")";
							$deliPrtChk = $vender."D";
						}

						// ��ۺ� ���̺� ����
						if( strlen($deliPrtChk) > 0 ) {
							$deliPrtArr[$deliPrtChk]++;
							if( $deliPrtArr[$deliPrtChk] > 1 ) {
								$deliPrt = "";
							} else{
								$deliCount = $basketItems['vender'][$vender]['deliCount'][$product['deli']][($product['deli_price']>0?"1":"0")];
								if( $deliCount > 1 ) {
									$deliPrtRowspan = " rowspan = '".$deliCount."'";
								}
							}
						}
					} else {
						$deliPrt = $product['deli_type'];
					}


		?>
		<? if($product['rental'] != '2'){ // �Ϲ� ��ǰ 
				$producttotalprice+= $product['sellprice']*$product['quantity'];

				//������
				$mem_reserve = getProductReserve($product['productcode']);
				$reserve_total += $producttotalprice*$mem_reserve;
		?>
							<tr>
								<td class="tdstyle" style="text-align:center">
									<div style="float:left; width:55px;"><img src="<?=$product['tinyimage']['src']?>" <?=$imageSize?> /></div>
									<div style="float:left; margin-left:5px; text-align:left;"> <a href="<?=$Dir.FrontDir?>productdetail.php?productcode=<?=$product['productcode']?>">
										<?=rentalIcon($product['rental'])?>
										<font color="#000000" style="font-size:12px;"><b>
										<?=viewproductname($product['productname'],$product['etctype'],$product['selfcode'],$product['addcode'])?>
										</b></font></a> <span style="font-size:11px;">
										<?							
							if($product['bankonly'] == 'Y'){ ?>
										<img src="<?=$Dir?>images/common/bankonly.gif" border=0 align=absmiddle>
										<? }// ���� ����							
							if($product['setquota'] == 'Y'){ ?>
										<img src="<?=$Dir?>images/common/setquota.gif" border=0 align=absmiddle>
										<? }// ������

							$sptxt = array();
							if($product['cateAuth']['coupon'] == 'N')	$sptxt[] = '<IMG SRC=/images/common/basket/001/basket_spe_icon002x.gif hspace=1 alt=�������� ����Ұ� />';
							if($product['cateAuth']['reserve'] == 'N')	$sptxt[] = '<IMG SRC=/images/common/basket/001/basket_spe_icon001x.gif alt=������ ���Ұ� />';
							if($product['cateAuth']['gift'] == 'Y' && checkGiftSet()) $sptxt[] ='<IMG SRC=/images/common/basket/001/basket_spe_icon004o.gif alt=����ǰ ����Ұ� />';
							if($product['cateAuth']['refund'] == 'N')	$sptxt[] = '<img src=/images/common/basket/001/basket_spe_icon003x.gif hspace=1 alt=��ȯ/��ǰ �Ұ� />';
							if(_array($sptxt)) echo implode(' ',$sptxt)."<br />";
						?>
										</span> <span>
										<? if(_array($product['option1']) || _array($product['option2']) || !_empty($product['optvalue'])){ ?>
										<br />
										<IMG border=0 align=absMiddle src="../images/common/basket/001/basket_skin3_icon002.gif">
										<?=$product['option1'][$product['opt1_idx']]?>
										<? if(_array($product['option2'])) echo ' / '.$product['option2'][$product['opt2_idx']];
			
								if(!_empty($product['optvalue'])) {
									echo $product['optvalue']."\n";
								}
						}						?>
										</span> </div>
								</td>
								<td class="tdstyle" align="center">
									<?= $product['quantity'] ?>
								</td>
								<td class="tdstyle" align="center">Buying</td>
								<td class="tdstyle" align="center"><b>
									<?=number_format($product['sellprice'])?>
									��</b></td>
								<td class="tdstyle" align="center">
									<?=!empty($product['group_discount'])?number_format($product['group_discount']).'��':'&nbsp;'?>
								</td>
								<? if(!_empty($deliPrt)){ ?>
								<td class="tdstyle" align="center" <?=$deliPrtRowspan?>>
									<?=$deliPrt?>
								</td>
								<? } ?>
								<td class="tdstyle" style="text-align:center">
									<?=$basketItems['vender'][$vender]['conf']['com_name']?>
								</td>
								<td class="tdstyle2" align="center"> <font color="#666666">
									<?=number_format($product['realprice'])?>
									��</font> </td>
							</tr>
							<?
		}else{ // ��Ż��ǰ 
			$tmpPinfo = rentProduct::read($product['pridx']);
			$rentItem = $product['rentinfo'];
			$roptinfo = &$tmpPinfo['options'][$rentItem['optidx']];
			$sellprice = $rentItem['solvprice']['totalprice'] /$rentItem['quantity'];
			$producttotalprice+= $rentItem['solvprice']['totalprice'];
			$disctotal += abs($rentItem['solvprice']['discprice']);

			//������
			$mem_reseller_reserve = getProductReseller_Reserve($product['productcode']);
			$reserve_total += $product['realprice']*$mem_reseller_reserve;
		?>
							<tr>
								<td class="tdstyle" style="text-align:center">
									<div style="float:left; width:55px;"><img src="<?=$product['tinyimage']['src']?>" <?=$imageSize?> /></div>
									<div style="float:left; margin-left:5px; text-align:left;"><a href="<?=$Dir.FrontDir?>productdetail.php?productcode=<?=$product['productcode']?>">
										<?=rentalIcon($product['rental'])?>
										<font color="#000000" style="font-size:12px;"><b>
										<?=viewproductname($product['productname'],$product['etctype'],$product['selfcode'],$product['addcode'])?>
										</b></font></a> <a href="<?=$Dir.FrontDir?>productdetail.php?productcode=<?=$product['productcode']?>">
										<?//=viewproductname($productname,$product['etctype'],$product['selfcode'],$product['addcode']) ?>
										<?=$bankonly_html?>
										<?=$setquota_html?>							
										</font></a><br />
										<span>
										<?=$roptinfo['optionName']?>
										</span> <span style="font-size:11px;">
										<?							
								if($product['bankonly'] == 'Y'){ ?>
										<img src="<?=$Dir?>images/common/bankonly.gif" border=0 align=absmiddle>
										<? }// ���� ����							
								if($product['setquota'] == 'Y'){ ?>
										<img src="<?=$Dir?>images/common/setquota.gif" border=0 align=absmiddle>
										<? }// ������
	
								$sptxt = array();
								if($product['cateAuth']['coupon'] == 'N')	$sptxt[] = '<IMG SRC=/images/common/basket/001/basket_spe_icon002x.gif hspace=1 alt=�������� ����Ұ� />';
								if($product['cateAuth']['reserve'] == 'N')	$sptxt[] = '<IMG SRC=/images/common/basket/001/basket_spe_icon001x.gif alt=������ ���Ұ� />';
								if($product['cateAuth']['gift'] == 'Y' && checkGiftSet()) $sptxt[] ='<IMG SRC=/images/common/basket/001/basket_spe_icon004o.gif alt=����ǰ ����Ұ� />';
								if($product['cateAuth']['refund'] == 'N')	$sptxt[] = '<img src=/images/common/basket/001/basket_spe_icon003x.gif hspace=1 alt=��ȯ/��ǰ �Ұ� />';
								if(_array($sptxt)) echo implode(' ',$sptxt)."<br />";
							?>
										</span> </div>
								</td>
								<td class="tdstyle" align="center">
									<?=$product['quantity'] ?>
								</td>
								<td class="tdstyle" align="center">
									<?
									if($rentItem['solvprice']['diff']['day']>0) echo $rentItem['solvprice']['diff']['day']."�� ";
									if($rentItem['solvprice']['diff']['hour']>0) echo $rentItem['solvprice']['diff']['hour']."�ð� ";

									if($sellprice > 0){ 				
										echo "<br>".date('Y-m-d H',$rentItem['solvprice']['range'][0]).'<br>'.date('Y-m-d H',$rentItem['solvprice']['range'][1]+1);
									}else{ 
										$endDate = "<br>".date('Y-m-d H',strtotime($rentItem['end'])+1);
										echo substr($rentItem['start'],0,13).'<br>'.$endDate;	
									} 
									/*
									if($rentItem['solvprice']['timegap'] == '1') echo date('Y-m-d H',$rentItem['solvprice']['range'][0]).'<br>'.date('Y-m-d H',$rentItem['solvprice']['range'][1]);
									else echo date('Y-m-d',$rentItem['solvprice']['range'][0]).'<br>'.date('Y-m-d',$rentItem['solvprice']['range'][1]); 
									*/
									?>
								</td>
								<td class="tdstyle" align="center"><b>
									<?//=number_format($sellprice)?><?=number_format($rentItem['solvprice']['prdrealprice'])?>
									��</b></td>
								<td class="tdstyle" align="center">
									<?=number_format(abs($rentItem['solvprice']['discprice'])).'��'?>
								</td>
								<td class="tdstyle" align="center">
								<?php if (!_empty($deliPrt)) { echo $deliPrt; } else { echo '&nbsp;';} ?>
								</td>
								<td class="tdstyle" style="text-align:center">
									<?=$basketItems['vender'][$vender]['conf']['com_name']?>
								</td>
								<td class="tdstyle2" style="text-align:center">
									<? echo number_format($product['realprice']);//number_format($rentItem['solvprice']['totalprice'])?>
									�� </td>
							</tr>
							<?			
			}
	}// end for
				} // end foreach
			} // end if
	?>
						</tbody>
						<tfoot>
							<tr><td colspan=8 height=1 bgcolor="#DDDDDD"></td></tr>
							<tr>
								<td colspan="8"  style="padding:10px 0px;">
								<?
									if ($totaldeliprice > 0) {
										$disp_sumprice = number_format($totaldeliprice + $basketItems['sumprice']).'��';
										$disp_deliprice = '(+)'.number_format($totaldeliprice);
										$basketItems['deli_price'] = $totaldeliprice;
									} else {
										$disp_sumprice = number_format($basketItems['sumprice']).'��';
										$basketItems['deli_price'] = 0;
										$disp_deliprice = '����';
									}
								?>
									<div style="float:left;">
										<input type="hidden" name="reserve_price" id="reserve_price" value="<?=$reserve_total?>">
										<table border="0" cellpadding="0" cellspacing="0">
											<tr>
												<td style="width:120px;">��õ ������</td>
												<td style="text-align:right" id="rsvTxt"><font color="#ff0000"><?=number_format($reserve_total)?></font>��</td>
												<td style="padding-left:10px">
													<!--<span id="dis_txt">
													<a href="javascript:changeDiscount('dis')">������ ������� �ޱ�</a>
													</span>
													<span id="res_txt" style="display:none">
													<a href="javascript:changeDiscount('res')">������ ���� �ޱ�</a>
													</span>-->
												</td>
											</tr>
											<tr>
												<td colspan="3">
													<input type="text" name="reseller_id" id="reseller_id">
													<button type="button" style="padding:5px" onclick="javascript:reseller_search()">ȸ���˻�</button>
													<p>(����ȸ���� �˻��Ǹ�, �Ϲ�ȸ���� ���̵� �Է��ϼ���.)</p>
												</td>
											</tr>
										</table>
									</div>
									<div style="float:right;">
										<div style="font-size:11px; color:#777777; text-align:right; letter-spacing:-0.5pt; margin-bottom:5px; padding-right:10px; display:none">
											<?=$groupMemberSale?>
										</div>
										<table border="0" cellpadding="0" cellspacing="0" align="right">
											<tr>
												<td style="width:120px;">�� ��ǰ�ݾ�</td>
												<td style="text-align:right"><?=number_format($producttotalprice)?></td>
											</tr>
											<tr>
												<td>��ۺ�</td>
												<td style="text-align:right"><?=$disp_deliprice?></td>
											</tr>
											<tr>
												<td>�� ���αݾ�</td>
												<td style="text-align:right">(-)<?=number_format($disctotal)?></td>
											</tr>
											<tr>
												<td colspan="2" style="height:20px;"></td>
											</tr>
											<tr>
												<td>�����ݾ�</td>
												<td style="text-align:right"><span class="basket_etc_price3" style="font-weight:bold"><?=$disp_sumprice?></span></td>
											</tr>
											<tr>
												<td colspan="2" width="10"></td>
											</tr>
										</table>
									</div>
								</td>
							</tr>
						</tfoot>
					</table>
					</div>
					
					<table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin-top:40px;">
						<caption style="display:none;">
						�ֹ��� ���� �Է�
						</caption>
						<tr>
							<td height="100%" valign="top">
								<table border="0" cellpadding="0" cellspacing="0" width="100%" class="orderTbl">
									<caption style="font-weight:bold; font-size:16px; color:#000; letter-spacing:-1px;">
									�� ��ǰ�� ��õ���� ȸ������
									</caption>
									<tr>
										<th>�̸�</th>
										<td>
											<input type="text" name="name" value="" size="15" maxlength="12" class="input" style="BACKGROUND-COLOR:#F7F7F7;" />
										</td>
									</tr>
									<!--tr>
										<th>���¹� ID(����)</th>
										<td>
											<input type="text" name="memid" value="" size="15" maxlength="12" class="input" style="BACKGROUND-COLOR:#F7F7F7;" />
										</td>
									</tr-->
									<tr>
										<th>�޴���ȭ</th>
										<td>
											<input type=text name="sms[]" value="" size="5" maxlength="3" onKeyUp="strnumkeyup(this)" class="input" style="BACKGROUND-COLOR:#F7F7F7;">
											-
											<input type=text name="sms[]" value="" size="5" maxlength="4" onKeyUp="strnumkeyup(this)" class="input" style="BACKGROUND-COLOR:#F7F7F7;">
											-
											<input type=text name="sms[]" value="" size="5" maxlength="4" onKeyUp="strnumkeyup(this)" class="input" style="BACKGROUND-COLOR:#F7F7F7;">
										</td>
									</tr>
									<tr>
										<th>�̸���</th>
										<td>
											<input type="hidden" name="email">
											<input type=text name="email_1" value="" class="input" style="width:100px; BACKGROUND-COLOR:#F7F7F7;">@
											<input type=text name="email_2" value="" class="input" style="width:150px; BACKGROUND-COLOR:#F7F7F7;">
											<select name="email_select" onchange="checkemailadd()">
												<option value="1">�����Է�</option>
												<option value="daum.net">daum.net</option>
												<option value="naver.com">naver.com</option>
												<option value="nate.com">nate.com</option>
												<option value="gmail.com">gmail.com</option>
												<option value="hanmail.net">hanmail.net</option>
											</select>

										<!--
											<input type=text name="email" value="" class="input" style="width:250px; BACKGROUND-COLOR:#F7F7F7;">-->
										</td>
									</tr>
									<!--tr>
										<th>SMS ���۸޽���</th>
										<td>
											<input type=text name="smsmsg" value="" class="input" style="width:96%; BACKGROUND-COLOR:#F7F7F7;" maxlength="80">
										</td>
									</tr-->									
									<tr>
										<th>�޼���</th>
										<td>
											<textarea name="emailmsg" class="input" style="width:96%; height:50px; BACKGROUND-COLOR:#F7F7F7;" placeholder="��õ ��ǰ�� �Բ� �����Ϸ��� �޽����� ���� ��� �Է��ϼ���.
�Է��� ������ �̸��Ϸ� ���޵˴ϴ�."></textarea>
										</td>
									</tr>
								</table>
								<!-- �ֹ��� ���� �Է� END --> 
							</td>
						</tr>
					</table>
					</div>
				</div>
				<div style="height:6px;background:url('/data/design/img/main/bot_boxline.gif') no-repeat;font-size:0px;"></div>
			</td>
		</tr>
		<tr>
			<td height="20"></td>
		</tr>
		<tr>
			<td align=center>
				<div id="paybuttonlayer" name="paybuttonlayer" style="text-align:center;height:43px;display:block;"> <A HREF="javascript:CheckForm()" onMouseOver="window.status='����';return true;"><img src="<?=$Dir?>images/common/basket/001/btn_recommand.gif" border="0" align="absmiddle" alt="��õ�ϱ�" /></A> <A HREF="javascript:ordercancel('cancel')" onMouseOver="window.status='���';return true;"><img src="<?=$Dir?>images/common/order/<?=$_data->design_order?>/btn_cancel.gif" border="0" align="absmiddle" /></A> </div>
				<div id="payinglayer" name="payinglayer" style="display:none;">
					<table border=0 cellpadding=0 cellspacing=0 width=100%>
						<tr>
							<td align=center><img src="<?=$Dir?>images/common/paying_wait.gif" border=0></td>
						</tr>
					</table>
				</div>
			</td>
		</tr>
		<tr>
			<td height="20"></td>
		</tr>
	</table>
</form>

<SCRIPT LANGUAGE="JavaScript">
<!--
function checkemailadd(){
	if(document.form1.email_select.value=="1"){
		document.form1.email_2.readonly = false;
		document.form1.email_2.value = "";
		document.form1.email_2.focus();
	}else{
		document.form1.email_2.readonly = true;
		document.form1.email_2.value = document.form1.email_select.value;
	}
}


	// �ֹ��ϱ�
	function CheckForm() {		
		var chk = true;

		if(document.form1.name.value.length==0) {
			alert("���� �̸��� �Է��ϼ���.");
			document.form1.name.focus();
			return;
		}
		if(!chkNoChar(document.form1.name.value)) {
			alert("�޴º� ���Կ� \\(��������) ,  '(��������ǥ) , \"(ū����ǥ)�� �Է��Ͻ� �� �����ϴ�.");
			document.form1.name.focus();
			return;
		}
		
		$j(document.form1).find('input[name="sms[]"]').each(function(idx,el){	
			if($j.trim($j(el).val()).length <1){
				alert('���� ��ȭ��ȣ�� �Է��ϼ���.');
				$j(el).focus();
				chk = false;
				return false;
			}else if(!IsNumeric($j(el).val())) {
				alert('���� ��ȭ��ȣ�� ���ڸ� �Է� �����մϴ�.');
				$j(el).focus();
				chk = false;
				return false;
			}
		});
		
		if(!chk) return;
		
		if(document.form1.email_1.value.length==0) {
			alert("���� �̸����� �Է��ϼ���.");
			document.form1.email_1.focus();
			return;
		}
		if(document.form1.email_2.value.length==0) {
			alert("���� �̸����� �Է��ϼ���.");
			document.form1.email_2.focus();
			return;
		}
		var email = document.form1.email_1.value + "@" + document.form1.email_2.value;
		document.form1.email.value = email;
		if(document.form1.email.value.length > 0) {
			if(!IsMailCheck(document.form1.email.value)) {
				alert("���� �̸��� ������ �߸��Ǿ����ϴ�.");
				document.form1.email_1.focus();
				return;
			}
		}
		
		if(document.form1.reseller_id.value==""){
			if(confirm("��õ ���Ͱ� �����ϴ�.\r\n������ ��õ�� ȸ���� ���� ���, ���� ���̵�� ��õ�˴ϴ�.\r\n��õ�Ͻðڽ��ϱ�?")){
				document.form1.reseller_id.value="<?=$_ShopInfo->getMemid()?>";
				document.form1.submit();
			}else{
				return;
			}
		}

		document.form1.submit();
		
	}

	// �ֹ����
	function ordercancel(gbn) {
		if(gbn=="cancel") {
			document.location.href="basket.php";
		}
	}

	function reseller_search(){
		window.open("reseller_search.php","reseller_search","width=400,height=500,toolbar=no,menubar=no,scrollbars=yes,status=no");
	}
//-->
</SCRIPT>
<? include ($Dir."lib/bottom.php") ?>
</body>
</html>