
<style type="text/css">
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

	.btn_reserve_sale, .btn_sale_reserve{margin-right:3px;padding:5px 8px;background:#8093a3;border:1px solid #657584;color:#fff;font-weight:bold;letter-spacing:-1px;cursor:pointer;}
</style>



<? /*<div style="clear:both;height:6px;background:url('/data/design/img/main/top_boxline.gif') no-repeat;font-size:0px;"></div>*/ ?>
<? /*<div style="padding:10px 30px;background:#ffffff url('/data/design/img/main/bg_boxline.gif') repeat-y;overflow:hidden;">*/ ?>
<div style="padding:10px 30px;overflow:hidden;">
	<div style="float:left;padding-left:50px;height:35px;line-height:35px;background:url('/data/design/img/sub/icon_basket.gif') no-repeat;color:#696969;font-size:25px;font-weight:600;">�ֹ�/����</div>
	<div style="float:right;margin-top:3px;"><img src="/data/design/img/sub/step2_basket.gif" alt="" /></div>
	<div style="clear:both;"></div>
</div>
<? /*<div style="height:6px;background:url('/data/design/img/main/bot_boxline.gif') no-repeat;font-size:0px;"></div>*/ ?>



<? /*<div style="clear:both;margin-top:20px;height:6px;background:url('/data/design/img/main/top_boxline.gif') no-repeat;font-size:0px;"></div>*/ ?>
<? /*<div style="padding:40px 0px;background:#ffffff url('/data/design/img/main/bg_boxline.gif') repeat-y;text-align:center;overflow:hidden;">*/ ?>
<div style="padding:40px 0px;background:#fff;overflow:hidden;">
	<div style="width:96%;margin:0px auto;">
		<div id="rangeTxt" style="height:20px;width:300px; float:left;text-align:left; color:#ff0000; font-size:11px; font-family:����; letter-spacing:-1px;"></div>
		<div style="height:20px; width:400px;float:right;text-align:right; color:#999; font-size:11px; font-family:����; letter-spacing:-1px;">�ֹ������� �Է��Ͻ� ��, <span style="color:red;">������ư</span>�� �����ּ���.</div>

<table border="0" cellpadding="0" cellspacing="0" width="100%" class="itemListTbl">
	<caption style="text-align:left; display:none;">�ֹ��� �ۼ�</caption>
	<thead>
		<tr>			
			<th class="thstyle" style="text-align:center">��ǰ����</th>
			<th class="thstyle" style="text-align:center; width:120px;">��ǰ�ݾ�</th>
			<th class="thstyle" style="text-align:center; width:120px">�Ⱓ</th>
			<th class="thstyle" style="text-align:center; width:80px;">����</th>
			<th class="thstyle" style="text-align:center; width:120px;">����</th>
			<th class="thstyle" style="text-align:center width:120px;">�ֹ��ݾ�</th>
			<th class="thstyle" style="text-align:center; width:120px;">��ۺ�</th>
			<th class="thstyle" style="text-align:center; width:100px;">�Ǹ��ڸ�</th>
		</tr>
	</thead>

	<tbody>
	<?	
		$disctotal = $producttotalprice = 0;
		if($basketItems['productcnt'] <1){ ?>
		<tr><td colspan="9" style="text-align:center; height:30px;">��ٱ��Ͽ� ��ϵ� ��ǰ�� �����ϴ�.</td></tr>
	<?	}else{
			$timgsize = 50;
			$k=0;$range_diff=0;
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
								$deliprice = $product['deli_price']*$product['quantity'];
							}else if($product['deli']=="N") {
								$deliprice = $product['deli_price'];
							}

							$delimsg = "����";
							if ($deliprice > 0) {
								$totaldeliprice += $deliprice;
								$delimsg = number_format($deliprice)."��";
							}
							$deliPrt = "������<br>(".$delimsg.")";
						}else if($product['deli']=="F" || $product['deli']=="G"){
							$deliPrt = ($product['deli']=="F"?'��������':'����');
						}else{
							$deliPrt  = "�⺻��ۺ�<br/>(";
							if($vendervalue['sumprice']>=$vendervalue['conf']['deli_mini']){
								$vendervalue['conf']['deli_price']=0;
							}
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
						$deliPrt = "<span>".$product['deli_type']."<span>";
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
				<div style="float:left; margin-left:5px; text-align:left;">					
					<a href="<?=$Dir.FrontDir?>productdetail.php?productcode=<?=$product['productcode']?>"><?=rentalIcon($product['rental'])?><font color="#000000" style="font-size:12px;"><b><?=viewproductname($product['productname'],$product['etctype'],$product['selfcode'],"")?></b></font></a>
					<span style="font-size:11px;">
						<?=$product['addcode']? $product['addcode']."<br>":"";?>
						<?
							if($product['bankonly'] == 'Y'){ ?><img src="<?=$Dir?>images/common/bankonly.gif" border=0 align=absmiddle><? }// ���� ����							
							if($product['setquota'] == 'Y'){ ?><img src="<?=$Dir?>images/common/setquota.gif" border=0 align=absmiddle><? }// ������

							$sptxt = array();
							if($product['cateAuth']['coupon'] == 'N')	$sptxt[] = '<IMG SRC=/images/common/basket/001/basket_spe_icon002x.gif hspace=1 alt=�������� ����Ұ� />';
							//if($product['cateAuth']['reserve'] == 'N')	$sptxt[] = '<IMG SRC=/images/common/basket/001/basket_spe_icon001x.gif alt=������ ���Ұ� />';
							if($product['cateAuth']['gift'] == 'Y' && checkGiftSet()) $sptxt[] ='<IMG SRC=/images/common/basket/001/basket_spe_icon004o.gif alt=����ǰ ����Ұ� />';
							if($product['cateAuth']['refund'] == 'N')	$sptxt[] = '<img src=/images/common/basket/001/basket_spe_icon003x.gif hspace=1 alt=��ȯ/��ǰ �Ұ� />';
							if(_array($sptxt)) echo implode(' ',$sptxt)."<br />";
						?>
					</span>
					<span>
						<? if(_array($product['option1']) || _array($product['option2']) || !_empty($product['optvalue'])){ ?>
								<br /><IMG border=0 align=absMiddle src="../images/common/basket/001/basket_skin3_icon002.gif"> <?=$product['option1'][$product['opt1_idx']]?> <? if(_array($product['option2'])) echo ' / '.$product['option2'][$product['opt2_idx']];
			
								if(!_empty($product['optvalue'])) {
									echo $product['optvalue']."\n";
								}
						}						?>
					</span>
				</div>
			</td>
			<td class="tdstyle" align="center"><b><?=number_format($product['sellprice'])?>��</b></td>
			<td class="tdstyle" align="center">Buying</td>
			<td class="tdstyle" align="center"><?= $product['quantity'] ?></td>
			<td class="tdstyle" align="center"><?=!empty($product['group_discount'])?number_format($product['group_discount']).'��':'&nbsp;'?></td>
			<td class="tdstyle" align="center">
				<font color="#666666"><?=number_format($product['realprice'])?> ��</font>				
			</td>
	<?		if(!_empty($deliPrt)){		?>
			<td class="tdstyle" align="center" <?=$deliPrtRowspan?>><?=$deliPrt?></td>
	<?			}			?>
			<td class="tdstyle2" style="text-align:center"><?=$basketItems['vender'][$vender]['conf']['com_name']?></td>
		</tr>
		<?
		}else{ // ��Ż��ǰ 
			$tmpPinfo = rentProduct::read($product['pridx']);
			
			$rentItem = $product['rentinfo'];
			$roptinfo = &$tmpPinfo['options'][$rentItem['optidx']];
			$sellprice = $rentItem['solvprice']['totalprice'] /$rentItem['quantity'];
			
			$producttotalprice+= $rentItem['solvprice']['totalprice'];
			$disctotal += abs($rentItem['solvprice']['discprice']);
/*
			$producttotalprice+= $rentItem['solvprice']['prdrealprice'] * $rentItem['quantity'];
			$disctotal += abs($rentItem['solvprice']['discprice']+$rentItem['solvprice']['prdrealprice']*$rentItem['solvprice']['member_discount']);
*/
			//������
			$mem_reserve = getProductReserve($product['productcode']);
			$reserve_total += $product['realprice']*$mem_reserve;

			$prentinfo['codeinfo'] = venderRentInfo($product['vender'],$product['pridx'],$product['productcode']);
		?>
		<tr>
			<td class="tdstyle" style="text-align:center">			
				<div style="float:left; width:55px;"><img src="<?=$product['tinyimage']['src']?>" <?=$imageSize?> /></div>
				<div style="float:left; margin-left:5px; text-align:left;">					
					<a href="<?=$Dir.FrontDir?>productdetail.php?productcode=<?=$product['productcode']?>"><?=rentalIcon($product['rental'])?><font color="#000000" style="font-size:12px;"><b><?=viewproductname($product['productname'],$product['etctype'],$product['selfcode'],"")?></b></font></a>
					<a href="<?=$Dir.FrontDir?>productdetail.php?productcode=<?=$product['productcode']?>"><?//=viewproductname($productname,$product['etctype'],$product['selfcode'],$product['addcode']) ?><?=$bankonly_html?><?=$setquota_html?><? //=$deli_str ?></font></a><br />				
					<span>
						<?=$product['addcode']? $product['addcode']."<br>":"";?>
						<?//=$roptinfo['optionName']?><?=($prentinfo['codeinfo']['pricetype']=="long")?"����":"";?>
						<?=($roptinfo['optionName']=="���ϰ���")?"":$roptinfo['optionName'];?><?=($prentinfo['codeinfo']['pricetype']=="long")?"����":"";?>
					</span>						
					<span style="font-size:11px;">
						<?							
							if($product['bankonly'] == 'Y'){ ?><img src="<?=$Dir?>images/common/bankonly.gif" border=0 align=absmiddle><? }// ���� ����							
							if($product['setquota'] == 'Y'){ ?><img src="<?=$Dir?>images/common/setquota.gif" border=0 align=absmiddle><? }// ������

							$sptxt = array();
							if($product['cateAuth']['coupon'] == 'N')	$sptxt[] = '<IMG SRC=/images/common/basket/001/basket_spe_icon002x.gif hspace=1 alt=�������� ����Ұ� />';
							if($product['cateAuth']['reserve'] == 'N')	$sptxt[] = '<IMG SRC=/images/common/basket/001/basket_spe_icon001x.gif alt=������ ���Ұ� />';
							if($product['cateAuth']['gift'] == 'Y' && checkGiftSet()) $sptxt[] ='<IMG SRC=/images/common/basket/001/basket_spe_icon004o.gif alt=����ǰ ����Ұ� />';
							if($product['cateAuth']['refund'] == 'N')	$sptxt[] = '<img src=/images/common/basket/001/basket_spe_icon003x.gif hspace=1 alt=��ȯ/��ǰ �Ұ� />';
							if(_array($sptxt)) echo implode(' ',$sptxt)."<br />";
						?>
					</span>
					
				</div>
			</td>
			<td class="tdstyle" align="center"><b><?//=number_format($sellprice)?><?=number_format($rentItem['solvprice']['prdrealprice'])?>��</b></td>
			<td class="tdstyle" align="center">
				<?
				if($prentinfo['codeinfo']['pricetype']=="long"){
					echo $roptinfo['optionPay'];
				}else{
					if($rentItem['solvprice']['diff']['day']>0) echo $rentItem['solvprice']['diff']['day']."�� ";
					if($rentItem['solvprice']['diff']['hour']>0) echo $rentItem['solvprice']['diff']['hour']."�ð� ";
					echo "<font style='font-size:11px'>";
					echo "<br>".date('m-d H',$rentItem['solvprice']['range'][0]).'<br>'.date('m-d H',$rentItem['solvprice']['range'][1]+1);
					echo "</font>";
					/*
					if($rentItem['solvprice']['timegap'] == '1') echo date('Y-m-d H',$rentItem['solvprice']['range'][0]).'<br>'.date('Y-m-d H',$rentItem['solvprice']['range'][1]);
					else echo date('Y-m-d',$rentItem['solvprice']['range'][0]).'<br>'.date('Y-m-d',$rentItem['solvprice']['range'][1]); 
					*/

					if($sellprice > 0){ 		
						$range_s[$k]=$rentItem['solvprice']['range'][0];
						$range_e[$k]=$rentItem['solvprice']['range'][1];
						//echo $k."==".$range_s[$k]."/".$range_s[$k-1]."<br>".$range_e[$k]."/".$range_e[$k-1];
						if($k!=0 && ($range_s[$k]!=$range_s[$k-1] || $range_e[$k]!=$range_e[$k-1])){
							$range_diff++;
						}
					}
				}
				?>
			</td>
			<td class="tdstyle" align="center"><?=$product['quantity'] ?></td>
			<td class="tdstyle" align="center">
				<?=number_format(abs($rentItem['solvprice']['discprice']+$rentItem['solvprice']['prdrealprice']*$rentItem['solvprice']['member_discount'])).'��'?>
			</td>
			<td class="tdstyle" style="text-align:center"><? echo number_format($product['realprice']);//number_format($rentItem['solvprice']['totalprice'])?>��</td>
	<?		if(!_empty($deliPrt)){?>
			<td class="tdstyle" align="center" <?=$deliPrtRowspan?>><?=$deliPrt?></td>
			<td class="tdstyle2" style="text-align:center" <?=$deliPrtRowspan?>><?=$basketItems['vender'][$vender]['conf']['com_name']?></td>
	<?		}?>
		</tr>	
<?				//} // end rental foreach
			$k++;
		}
	}// end for
		?>
		<? /*
		<tr>
			<td colspan="9" bgcolor="#f9f9f9" style="padding:15px 10px; text-align:right;">
				<div style="font-size:11px; margin-bottom:5px;">
				<?=$venderDeliPrint?>
				</div>
				��ۺ� : <b><?=number_format($vendervalue['deliprice'])?></b>�� / <b>�հ� : </b><span style="color:#ff6600; font-size:15px; font-family:tahoma; font-weight:bold;"><?=number_format($vendervalue['sumprice'])?>��</span>
			</td>
		</tr>
		<tr><td colspan=9 height=1 bgcolor="#DDDDDD"></td></tr> */ ?>
	<?
				} // end foreach
			} // end if
	?>
	</tbody>
	<tfoot>
		<tr><td colspan=8 height=1 bgcolor="#DDDDDD"></td></tr>
		<tr>
			<td colspan="8"  style="padding:10px 0px;">
				<? /*
				<div style="float:left;">
					<table border="0" cellpadding="0" cellspacing="0">
						<tr>
							<td style="padding-left:10px;"><img src="<?=$Dir?>images/common/basket/<?=$_data->design_basket?>/basket_skin3_icontitle.gif" alt="" /></td>
							<td style="padding-left:5px;"><img style="cursor:pointer;" src="<?=$Dir?>images/common/basket/<?=$_data->design_basket?>/basket_skin3_icon004.gif" onclick="javascript:CheckForm('del','sel')" alt="�����ϱ�" /></a></td>
						</tr>
					</table>
				</div>
*/?>
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
							<td style="width:120px;">���� ������</td>
							<td style="text-align:right" id="rsvTxt"><font color="#ff0000"><?=number_format($reserve_total)?></font>��</td>
							<td style="padding-left:10px">
								<button type="button" id="dis_txt" onclick="javascript:changeDiscount('dis')" class="btn_reserve_sale" title="������ ������ιޱ�">������ ������ιޱ�</button>
								<button type="button" id="res_txt" style="display:none" onclick="javascript:changeDiscount('res')" class="btn_sale_reserve" title="������� �ݾ� �����ޱ�">������� �ݾ� �����ޱ�</button>
								<!--
								<span id="dis_txt">
								<a href="javascript:changeDiscount('dis')">������ ������ιޱ�</a>
								</span>
								<span id="res_txt" style="display:none">
								<a href="javascript:changeDiscount('res')">������� �ݾ� �����ޱ�</a>
								</span>-->
							</td>
						</tr>
					</table>
				</div>
				<div style="float:right;">
					<div style="font-size:11px; color:#777777; text-align:right; letter-spacing:-0.5pt; margin-bottom:5px; padding-right:10px; display:none"><?=$groupMemberSale?></div>
					<table border="0" cellpadding="0" cellspacing="0" align="right">
						<tr>
							<td style="width:120px;">�� ��ǰ�ݾ�</td>
							<td style="text-align:right"><?=number_format($producttotalprice)?></td>
						</tr>
						<tr>
							<td>�� ���αݾ�</td>
							<td style="text-align:right">(-) <?=number_format($disctotal)?></td>
						</tr>
						<tr>
							<td>��ۺ�</td>
							<td style="text-align:right"><?=$disp_deliprice?></td>
						</tr>
						<tr id="now_disp" style="display:none">
							<td>�������</td>
							<td style="text-align:right">(-) <span id="disp_reserve_1"></span></td>
						</tr>
						<tr>
							<td colspan="2" style="height:20px;"></td>
						</tr>
						<tr>
							<td>�����ݾ�</td>
							<td style="text-align:right"><span class="basket_etc_price3" style="font-weight:bold" id="disp_last_price_1"><?=$disp_sumprice?></span></td>
						</tr>
						<tr>
							<td colspan="2" width="10"></td>
						</tr>
					</table>
				</div>
			</td>
		</tr>
		<tr><td colspan=8 height=1 bgcolor="#DDDDDD"></td></tr>
	</tfoot>
</table>



<!--
<table border="0" cellpadding="0" cellspacing="0" width="100%" class="itemListTbl">
	<caption style="text-align:left; display:none;">�ֹ��� �ۼ�</caption>
	<thead>
		<tr>
			<th class="thstyle" colspan="2">��ǰ��/�ɼ�</th>
			<th class="thstyle" style="width:50px;">����</th>
			<th class="thstyle" style="width:100px;">�ǸŰ�</th>
			<th class="thstyle" style="width:70px;">������</th>
			<th class="thstyle" style="width:100px;">�հ�</th>
			<th class="thstyle" style="width:70px;">��ۺ�</th>
			<th class="thstyle2" style="width:120px;">����</th>
		</tr>
	</thead>
	<tbody>
<?
$couponable = false;
$reserveuseable = false;
$productRealPrice = 0;
if($basketItems['productcnt'] <1){ ?>
		<tr>
			<td colspan="8" style="height:30px;">��ϵ� ��ǰ�� �����ϴ�.</td>
		</tr>
<? }else{
		$timgsize = 50;
		foreach($basketItems['vender'] as $vender=>$vendervalue){

			for($i=0;$i<count($vendervalue['products']);$i++){
				$product = $vendervalue['products'][$i];

				if(!$couponable && $product['cateAuth']['coupon'] == 'Y'){
					$chkcoupons = array();
					$chkcoupons = getMyCouponList($product['productcode']);
					if(_array($chkcoupons)) $couponable = true;
				}

				if(!$reserveuseable && $product['cateAuth']['reserve'] == 'Y') $reserveuseable = true;
?>
		<tr>
			<td class="tdstyle2" style="width:60px; text-align:center"><img src="<?=$product['tinyimage']['src']?>" <? if($product['tinyimage'][$product['tinyimage']['big']] > $timgsize) echo $product['tinyimage']['big'].'="'.$timgsize.'"'; ?> /></td>
			<td class="tdstyle">
				<a href="<?=$Dir.FrontDir?>productdetail.php?productcode=<?=$product['productcode']?>"><font color="#000000" style="font-size:12px;"><b><?=viewproductname($product['productname'],$product['etctype'],$product['selfcode'],$product['addcode'])?></b></font></a>

			<? if(_array($product['option1']) || _array($product['option2']) || !_empty($product['optvalue'])){ ?>
					<br /><IMG border=0 align=absMiddle src="../images/common/basket/001/basket_skin3_icon002.gif"> <?=$product['option1'][$product['opt1_idx']]?> <? if(_array($product['option2'])) echo ' / '.$product['option2'][$product['opt2_idx']];

					if(!_empty($product['optvalue'])) {
						echo $product['optvalue']."\n";
					}
			}
			?>

			<? if($product['bankonly'] == 'Y'){ ?><img src="<?=$Dir?>images/common/bankonly.gif" border=0 align=absmiddle><? } ?>
			<? if($product['setquota'] == 'Y'){ ?><img src="<?=$Dir?>images/common/setquota.gif" border=0 align=absmiddle><? } ?>
			<?
				// ���� �� ���� ����
				$sptxt = array();
				if($product['cateAuth']['reserve'] == 'N') array_push($sptxt,'<IMG SRC=\'/images/common/basket/001/basket_spe_icon001x.gif\' hspace=\'1\' alt=\'������ ���Ұ�\' />');
				if($product['cateAuth']['coupon'] == 'N') array_push($sptxt,'<IMG SRC=\'/images/common/basket/001/basket_spe_icon002x.gif\' hspace=\'1\' alt=\'�������� ����Ұ�\' />');
				if($product['cateAuth']['refund'] == 'N') array_push($sptxt,'<img src=\'/images/common/basket/001/basket_spe_icon003x.gif\' hspace=\'1\' alt=\'��ȯ/��ǰ �Ұ�\' />');
				if($product['cateAuth']['gift'] == 'Y') array_push($sptxt,'<img src=\'/images/common/basket/001/basket_spe_icon004o.gif\' hspace=\'1\' alt=\'����ǰ ����\' />');
				if(_array($sptxt)){
					//echo '<br />'.implode(' / ',$sptxt);
					echo '<div style=\'margin-top:5px; font-size:0px;\'>'.implode('',$sptxt).'</div>';
				}
			?>
			</td>
			<td class="tdstyle" align="center"><?=$product['quantity']?>��</td>
			<td class="tdstyle" align="center"><?=number_format($product['sellprice'])?>��</td>
			<td class="tdstyle" align="center"><?=number_format($product['reserve'])?>��</td>
			<td class="tdstyle" align="center"><b><?=number_format($product['realprice'])?>��</b></td>
			<td class="tdstyle" align="center" style="font-size:11px; letter-spacing:-1px;">
				<? if($product['deli_price']>0){
					if($product['deli']=="Y"){ ?>������<br><?=number_format($product['deli_price']*$product['quantity'])?>��
			<?		}else if($product['deli']=="N") { ?>������<br /><?=number_format($product['deli_price'])?>��<?		}
				}else if($product['deli']=="F" || $product['deli']=="G"){
					echo ($product['deli']=="F"?'��������':'����');
				}else{
					if($vender > 0) {
						echo '������<br />�⺻���';
					} else {
						echo '�⺻��ۺ�';
						$productRealPrice += $product['realprice'];
					}
				}
			?>
			</td>
			<td class="tdstyle2" style="font-size:11px;">
			<?


			$mycoupons = $ablecoupons =array();
			if($_data->coupon_ok=="Y" && $product['cateAuth']['coupon'] == 'Y' && checkGroupUseCoupon()){
				$mycoupons = getMyCouponList($product['productcode']);
				$ablecoupons = ableCouponOnProduct($product['productcode'],$product['vender'],true);
				if(_array($mycoupons)){
					foreach($mycoupons as $abcoupon){
						echo '<span class="couponDownArea">';
						echo '<b>��</b>&nbsp;'.number_format(intval($abcoupon['sale_money'])).(($abcoupon['sale_type']<'3')?'%':'��').((intval($abcoupon['sale_type'])%2 == 1)?'����':'����');

						if(_array($mycoupon_codes) && in_array($abcoupon['coupon_code'],$mycoupon_codes)){ ?>
							<img src="<?=$Dir?>images/common/order/<?=$_data->design_order?>/icon_get.gif" border="0" style="position:relative; top:0.2em;" alt="������" /><br />
			<?			}else{ ?>
						<a href="javascript:issue_coupon('<?=$abcoupon['coupon_code']?>','<?=$product['productcode']?>')"><img src="<?=$Dir?>images/common/order/<?=$_data->design_order?>/icon_download.gif" style="position:relative; top:0.2em;" border="0" alt="�����ٿ�" /><br /></a>
			<?			}
						echo '</span>';
					}
				}

				if(_array($ablecoupons)){
					foreach($ablecoupons as $abcoupon){
						echo '<span class="couponDownArea">';
						echo '<b>��</b>&nbsp;'.number_format(intval($abcoupon['sale_money'])).(($abcoupon['sale_type']<'3')?'%':'��').((intval($abcoupon['sale_type'])%2 == 1)?'����':'����');

						if(_array($mycoupon_codes) && in_array($abcoupon['coupon_code'],$mycoupon_codes)){ ?>
							<img src="<?=$Dir?>images/common/order/<?=$_data->design_order?>/icon_get.gif" border="0" style="position:relative; top:0.2em;" alt="������" /><br />
			<?			}else{ ?>
						<a href="javascript:issue_coupon('<?=$abcoupon['coupon_code']?>','<?=$product['productcode']?>')"><img src="<?=$Dir?>images/common/order/<?=$_data->design_order?>/icon_download.gif" style="position:relative; top:0.2em;" border="0" alt="�����ٿ�" /><br /></a>
			<?			}
						echo '</span>';
					}
				}
			}else{
				echo '&nbsp;';
			}
			?>
			</td>
		</tr>

<?			}// end for
		} // end foreach
	} // end if
?>
	</tbody>
	<tfoot>
		<tr>
			<td colspan="3" bgcolor="#eeeeee" style="padding-left:20px;"><a href="/front/basket.php"><img src="<?=$Dir?>images/common/order/<?=$_data->design_order?>/btn_basket.gif" border="0" alt="��ٱ��� ���ư���" /></a></td>
			<td colspan="5" bgcolor="#eeeeee" style="padding:15px 10px;" align="right">
				<table border="0" cellpadding="0" cellspacing="0">
					<tr>
						< !--
						<th class="thstyle">������ :&nbsp;</th>
						<th style="height:20px; line-height:20px; padding-right:20px;"><span style="font-size:24px; font-family:Tahoma;"><?//=number_format($basketItems['reserve'])?></span>��</th>
						<th class="thstyle">��ۺ� :&nbsp;</th>
						<th style="height:20px; line-height:20px; padding-right:20px;"><span style="font-size:24px; font-family:Tahoma;"><?//=number_format($basketItems['deli_price'])?></span>��</th>
						-- >
						<td valign="bottom"><b>�� �ֹ��ݾ�</b> :&nbsp;</td>
						<td style="color:#0097da; height:20px; line-height:20px;"><span style="font-size:20px; font-family:Tahoma; font-weight:bold;"><?=number_format($basketItems['sumprice'])?></span>��&nbsp;</td>
						<td valign="bottom">(��ۺ� <?=number_format($basketItems['deli_price'])?>��)</td>
					</tr>
				</table>
			</td>
		</tr>
	</tfoot>
</table>
-->
<?
	$reserveuseable = false;
	//echo $product['cateAuth']['reserve'];
	if(!$reserveuseable && $product['cateAuth']['reserve'] == 'Y') $reserveuseable = true;
?>

<!--�ֹ�������

<table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin-top:40px;">
	<caption style="display:none;">�ֹ��� ���� �Է�</caption>
	<tr>
		<td width="50%" height="100%" valign="top" style="padding-right:15px;">

<?
$is_sms="N";
$sql = "SELECT * FROM tblsmsinfo WHERE (mem_order='Y' OR mem_delivery='Y') ";
$result=mysql_query($sql,get_db_conn());
if($rows=mysql_num_rows($result)) $is_sms="Y";
mysql_free_result($result);

$memberName = '';
$addrHight = '80';
$payHeight = '70';
if( !_empty($_ShopInfo->getMemid()) ) {
	$memberName = ' readonly';
	$addrHight = '110';
	$payHeight = '144';
}

?>


<table border="0" cellpadding="0" cellspacing="0" width="100%" class="orderTbl">
	<caption><img src="<?=$Dir?>images/common/order/<?=$_data->design_order?>/order_title_t04.gif" /></caption>
	<tr>
		<th>�ֹ����̸�</th>
		<td><input type="text" name="sender_name" value="<?=$name?>" size="15" maxlength="12" class="input" style="BACKGROUND-COLOR:#F7F7F7;" <?=$memberName?> /></td>
	</tr>
	<tr>
		<th>��ȭ��ȣ</th>
		<td><input type=text name="sender_tel1" value="<?=$home_tel[0] ?>" size="5" maxlength="3" onKeyUp="strnumkeyup(this)" class="input" style="BACKGROUND-COLOR:#F7F7F7;"> - <input type=text name="sender_tel2" value="<?=$home_tel[1] ?>" size="5" maxlength="4" onKeyUp="strnumkeyup(this)" class="input" style="BACKGROUND-COLOR:#F7F7F7;"> - <input type=text name="sender_tel3" value="<?=$home_tel[2] ?>" size="5" maxlength="4" onKeyUp="strnumkeyup(this)" class="input" style="BACKGROUND-COLOR:#F7F7F7;"></td>
	</tr>
	<tr>
		<th>�޴�����ȣ</th>
		<td><input type=text name="sender_hp1" value="<?=$mobile[0] ?>" size="5" maxlength="3" onKeyUp="strnumkeyup(this)" class="input" style="BACKGROUND-COLOR:#F7F7F7;"> - <input type=text name="sender_hp2" value="<?=$mobile[1] ?>" size="5" maxlength="4" onKeyUp="strnumkeyup(this)" class="input" style="BACKGROUND-COLOR:#F7F7F7;"> - <input type=text name="sender_hp3" value="<?=$mobile[2] ?>" size="5" maxlength="4" onKeyUp="strnumkeyup(this)" class="input" style="BACKGROUND-COLOR:#F7F7F7;"></td>
	</tr>
	<tr>
		<th>�̸���</th>
		<td><input type=text name="sender_email" value="<?=$email?>" class="input" style="width:96%; BACKGROUND-COLOR:#F7F7F7;"></td>
	</tr>
	<tr>
		<th class="lastTh">�ּ�</th>
		<td class="lastTd" height="<?=$addrHight?>">


			<div style="overflow:hidden">
				<INPUT type="text" name="spost1" id="spost1" value="<?=$home_post1?>" readOnly style="WIDTH:60px;BACKGROUND:#F7F7F7" class="input" /> 
				<A href="javascript:addr_search_for_daumapi('spost1','saddr1','saddr2');"><img src="<?=$Dir?>images/common/order/<?=$_data->design_order?>/order_skin_btn2.gif" border="0" align="absmiddle" /></a>
			</div>
			<div style="margin:3px 0px;overflow:hidden"><INPUT type="text" name="saddr1" id="saddr1" maxLength="100" value="<?=$home_addr1?>" readOnly style="WIDTH:96%;BACKGROUND:#F7F7F7" class="input" /></div>
			<div style="overflow:hidden"><INPUT type="text" name="saddr2" id="saddr2" maxLength="100" value="<?=!_empty($home_addr2)?$home_addr2:'������ �ּ�'?>" style="WIDTH:96%;BACKGROUND:#F7F7F7" class="input" /></div>

		</td>
	</tr>
</table>
 �ֹ��� ���� �Է� END -->

<input type="hidden" name="range_diff" value="<?=$range_diff?>">
<input type="hidden" name="sender_name" value="<?=$name?>">
<input type=hidden name="sender_tel1" value="<?=$home_tel[0] ?>">
<input type=hidden name="sender_tel2" value="<?=$home_tel[1] ?>">
<input type=hidden name="sender_tel3" value="<?=$home_tel[2] ?>">
<input type=hidden name="sender_hp1" value="<?=$mobile[0] ?>">
<input type=hidden name="sender_hp2" value="<?=$mobile[1] ?>">
<input type=hidden name="sender_hp3" value="<?=$mobile[2] ?>">
<input type=hidden name="sender_email" value="<?=$email?>">
<INPUT type="hidden" name="spost1" id="spost1" value="<?=$home_post1?>"/> 
<INPUT type="hidden" name="saddr1" id="saddr1" maxLength="100" value="<?=$home_addr1?>"/>
<INPUT type="hidden" name="saddr2" id="saddr2" maxLength="100" value="<?=!_empty($home_addr2)?$home_addr2:'������ �ּ�'?>"/>



<table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin-top:40px;">
	<caption style="display:none;">����� ���� �Է�</caption>
	<tr>
		<td valign="top">
			<!-- ����� ���� �Է� START -->
			<table border="0" cellpadding="0" cellspacing="0" width="100%" class="orderTbl">
				<caption>
				<div style="position:relative; margin:0px; padding:0px; font-size:0px;">
					<img src="<?=$Dir?>images/common/order/<?=$_data->design_order?>/order_title_t05.gif">
					<div style="position:absolute; top:0px; left:135px;"><? if($ordertype  != "present"){?><span style="font-size:11px; font-weight:bold"> <input type=checkbox name="same" value="Y" onclick="SameCheck(this.checked)">�ֹ��� ������ ������</span><? }?></div>
				</div>
				</caption>
				<tr>
					<th>������<font style="color:#ff0000">*</font></th>
					<td><input type=text name="receiver_name" size="15" maxlength="12" class="input" style="BACKGROUND-COLOR:#F7F7F7;" value="<?=$receiver_name?>"></td>
				</tr>
				<tr>
					<th>��ȭ��ȣ<font style="color:#ff0000">*</font></th>
					<td><input type=text name="receiver_tel11" size="5" maxlength="3" onKeyUp="strnumkeyup(this)" class="input" style="BACKGROUND-COLOR:#F7F7F7;" value="<?=$receiver_tel11?>"> - <input type=text name="receiver_tel12" size="5" maxlength="4" onKeyUp="strnumkeyup(this)" class="input" style="BACKGROUND-COLOR:#F7F7F7;" value="<?=$receiver_tel12?>"> - <input type=text name="receiver_tel13" size="5" maxlength="4" onKeyUp="strnumkeyup(this)" class="input" style="BACKGROUND-COLOR:#F7F7F7;" value="<?=$receiver_tel13?>"></td>
				</tr>
				<tr>
					<th>�޴���ȭ</th>
					<td><input type=text name="receiver_tel21" size="5" maxlength="3" onKeyUp="strnumkeyup(this)" class="input" style="BACKGROUND-COLOR:#F7F7F7;" value="<?=$receiver_tel21?>"> - <input type=text name="receiver_tel22" size="5" maxlength="4" onKeyUp="strnumkeyup(this)" class="input" style="BACKGROUND-COLOR:#F7F7F7;" value="<?=$receiver_tel22?>"> - <input type=text name="receiver_tel23" size="5" maxlength="4" onKeyUp="strnumkeyup(this)" class="input" style="BACKGROUND-COLOR:#F7F7F7;" value="<?=$receiver_tel23?>">
					&nbsp;(��� ������ ������ ��ȣ�� �Է����ּ���.)
					</td>
				</tr>
				<!--tr>
					<th>�̸���</th>
					<td><input type=text name="email" value="<?=$receiver_email?>" size="30" class="input" style="width:96%; BACKGROUND-COLOR:#F7F7F7;"></td>
				</tr-->
				<tr>
					<th class="lastTh">�ּ�<font style="color:#ff0000">*</font></th>
					<td class="lastTd" height="<?=$addrHight?>">
						<? if(!_empty($_ShopInfo->getMemid()) && $ordertype  != "present"){ ?>
						<div id="addressSelDiv" style="margin:5px 0px 10px 0px;border-bottom:none">
							<label style="cursor:pointer"><input class="radio" type=radio name="addrtype" value="H" onclick="addrchoice()" style="vertical-align:middle"> ����</label>&nbsp;&nbsp;
							<label style="cursor:pointer"><input class="radio" type=radio name="addrtype" value="O" onclick="addrchoice()" style="vertical-align:middle"> ȸ��</label>&nbsp;&nbsp;
							<label style="cursor:pointer"><input class="radio" type=radio name="addrtype" value="B" onclick="addrchoice()" style="vertical-align:middle"> �ֱ� �����</label>&nbsp;&nbsp;
							<label style="cursor:pointer"><input class="radio" type=radio name="addrtype" value="N" onclick="addr_search_for_daumapi('rpost1','raddr1','raddr2')" style="vertical-align:middle"> �ű� �����</label>&nbsp;&nbsp;
							<label style="cursor:pointer"><input class="radio" type="radio" name="addrtype" value="A" onclick="addrchoice()" style="vertical-align:middle" /> �ּҷ�</label>
						</div>
						<? } ?>

						<div style="overflow:hidden">
							<INPUT type="text" name="rpost1" id="rpost1" value="<?=$receiver_zip1?>" readOnly style="WIDTH:60px;BACKGROUND:#F7F7F7" class="input" /> 
							<A href="javascript:addr_search_for_daumapi('rpost1','raddr1','raddr2');"><img src="<?=$Dir?>images/common/order/<?=$_data->design_order?>/order_skin_btn2.gif" border="0" align="absmiddle" /></a>
						</div>
						<div style="margin:3px 0px;overflow:hidden"><INPUT type="text" name="raddr1" id="raddr1" maxLength="100" value="<?=$receiver_addr1?>" readOnly style="WIDTH:96%;BACKGROUND:#F7F7F7" class="input" /></div>
						<div style="overflow:hidden"><INPUT type="text" name="raddr2" id="raddr2" maxLength="100" value="<?=$receiver_addr2?>" style="WIDTH:96%;BACKGROUND:#F7F7F7" class="input" /></div>

						<!--
						<input type=text name="rpost1" size="3" onclick="this.blur();get_post('r')" class="input" style="BACKGROUND-COLOR:#F7F7F7;" value="<?=$receiver_zip1?>"> - <input type=text name="rpost2" size="3" onclick="this.blur();get_post('r')" class="input" style="BACKGROUND-COLOR:#F7F7F7;" value="<?=$receiver_zip2?>"> <a href="javascript:get_post('r');"><img src="<?=$Dir?>images/common/order/<?=$_data->design_order?>/order_skin_btn2.gif" border="0" align="absmiddle" /></a><br />
						<input type=text name="raddr1" size="50" style="width:96%; BACKGROUND-COLOR:#F7F7F7;" class="input" readonly value="<?=$receiver_addr1?>"><br />
						<input type=text name="raddr2" size="50" style="width:96%; BACKGROUND-COLOR:#F7F7F7;" class="input"  value="<?=$receiver_addr2?>">
						-->
						<select name="order_message" onchange="change(this.form);" style="margin-top:4px;">
							<option value=0>��� �� ��û������ �������ּ���.</option>
							<option value=1>��� �� ���� �ٶ��ϴ�.</option>
							<option value=2>���� �� ���ǿ� �ð��ּ���.</option>
							<option value=3>���� �� ��ȭ �Ǵ� ���� �����ּ���.</option>
							<option value=4>�����Է�</option>
						</select><br/>
						<input type="text" name="order_prmsg" value="" class="input" style="width:50%; BACKGROUND-COLOR:#F7F7F7;" />
						 ��) ���忡�� ��ȭ�ּ���.
					</td>
				</tr>
			</table>
			<!-- ����� ���� �Է� END -->
		</td>
	</tr>
</table>

<!--table border="0" cellpadding="0" cellspacing="0" width="100%" class="orderTbl" style="margin-top:15px;">
	<tr>
		<th>�ֹ��޽���<br />(50�� ����)</th>
		<td>
			<input type="text" name="order_prmsg" value="" class="input" style="width:99%; BACKGROUND-COLOR:#F7F7F7;" /><br /><br/>
			<select name="order_message" onchange="change(this.form);">
				<option value=0>�ֹ��޽����� �������ּ���.</option>
				<option value=1>��� �� ���� �ٶ��ϴ�.</option>
				<option value=2>���� �� ���ǿ� �ð��ּ���.</option>
				<option value=3>���� �� ��ȭ �Ǵ� ���� �����ּ���.</option>
				<option value=4>�����Է�</option>
			</select><br/>
			�ù� ���忡 ���� �޽��� �Դϴ�. ��) ����� ���ǿ� �ð��ּ���.
		</td>
	</tr>-->
<? if(_empty($_ShopInfo->getMemid())){?>
<table border="0" cellpadding="0" cellspacing="0" width="100%" class="orderTbl" style="margin-top:15px;">
	<tr>
		<th class="lastTh">��ȸ��<br />�������� ����</th>
		<td class="lastTd">
			<div style="border:#dfdfdf 1px solid;padding:5px 10px;overflow-y:auto;HEIGHT:100px; WIDTH:99%;font-size:12px;"><?=$privercybody?></DIV>
			<div style="text-align:left; padding-top:7px;">
				<?=$_data->shopname?>�� <font color="#FF4C00"><b>����������޹�ħ</b></FONT>�� �����ϰڽ��ϱ�?
				<input type=radio id=idx_dongiY name=dongi value="Y" style="border:none"><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_dongiY><b><font color="#000000">�����մϴ�.</font></b></label><input type=radio id="idx_dongiN" name=dongi value="N" style="border:none"><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_dongiN><b><font color="#000000">�������� �ʽ��ϴ�.</font></b></label>
			</div>
		</td>
	</tr>
</table>
<? }?>


<?
	if(substr($ordertype,0,6) != "pester"){
?>


	<!-- �������� ���� START-->
	<?
	//echo 'dd'.$reserveuseable." ccc".$okreserve;
	//if(substr($ordertype,0,6)!= "pester" && $socialshopping != "social" && !_empty($_ShopInfo->getMemid()) && (($reserveuseable && $okreserve > 0 && ($user_reserve -$_data->reserve_maxuse) > 0) || (($_data->coupon_ok=="Y" && checkGroupUseCoupon()) || $couponable))) {
	if(substr($ordertype,0,6)!= "pester" && $socialshopping != "social" && !_empty($_ShopInfo->getMemid())) {
		if ($_data->reserve_maxuse>=0 && $user_reserve!=0) {
			if($okreserve<0){
				$okreserve=(int)($sumprice*abs($okreserve)/100);
				if($reserve_maxprice>$sumprice) $okreserve=0;
				else if($okreserve>$user_reserve) $okreserve=$user_reserve;
			}
		}
		if($_data->reserve_maxuse > $user_reserve) $okreserve = 0;
		else $okreserve = min($okreserve,$basketItems['reserve_price']);
	?>

	<table border="0" cellpadding="0" cellspacing="0" width="100%" class="orderTbl" style="margin-top:30px;">
		<caption><img src="<?=$Dir?>images/common/order/<?=$_data->design_order?>/order_title_t02.gif"></caption>

	<?	if($_data->coupon_ok=="Y" && checkGroupUseCoupon() && $couponable) { // ���� ��� ����?>
		<!-- <tr>
			<th>�������� ����</th>
			<td>
				<li>���αݾ� : <input type="text" name="coupon_price" id="coupon_price" class="st02_1" maxlength="8" value="0" readonly="readonly" /> �� </li>
				<li><A HREF="javascript:coupon_check()" onmouseover="window.status='��������';return true;"><img src="<?=$Dir?>images/common/order/<?=$_data->design_order?>/order_skin_btn1.gif" border="0" align="absmiddle"></A> <a href="javascript:resetCoupon()"><img src="<?=$Dir?>images/common/order/<?=$_data->design_order?>/order_skin_btn3.gif" border="0" align="absmiddle"></a> <?=$offlineCouponInputButton?></li><br />
			���� ������ ��ȸ�Ͻ� �� ���������Ͻø� ����(Ȥ�� �߰�����) ������ ������ �� �ֽ��ϴ�.</td>
		</tr> -->
	<? } ?>

	<?
		$reserveuseable = false;
		if(!$reserveuseable && $product['cateAuth']['reserve'] == 'Y') $reserveuseable = true;

		if($reserveuseable){
			/*
				���� ������ ��� - ��ۺ� ���� ������ ���ܱݾ�����  ��ۺ� ���� ����???
			*/
	?>
		<tr>
			<th class="lastTh">�����ݻ��</th>
			<td class="lastTd">
				<?
					if($okreserve > 0 && $user_reserve - $_data->reserve_maxuse >= 0){
				?>
				<input type="hidden" name="oriuser_reserve" id="oriuser_reserve" class="st02_1" maxlength="8" value="<?=$user_reserve?>" readonly="readonly" />
				<input type="text" name="usereserve" id="usereserve" class="st02_1" maxlength="8" value="0"  <?=($okreserve<1)?'disabled="disabled"':''?> /> �� <input type="button" value="���׻��" onclick="allReserve()">(����������: <font style="color:#ff0000;font-weight:bold"><?=number_format($user_reserve)?>��</font> | <?=number_format($_data->reserve_maxuse)?>���̻���� ��밡��)
				
				<!--<br /><span style="color:red"> <span style="font-weight:bold"><?=number_format($okreserve)?>��</span> ���� ���������� ����Ͽ� �����ϽǼ� �ֽ��ϴ�.(�����ݻ��Ұ� ��ǰ ���ܱݾ�)</span><br /-->
				<?
					}else{
				?>
					<input type="hidden" name="usereserve" id="usereserve" value="0" />
					<strong>[���������� : <?=number_format($user_reserve)?>��]</strong>
				<?
					}
				?>
				<!--(�����Ͻ� �������� <span style="font-weight:bold"><?=number_format($_data->reserve_maxuse)?>�� �̻�</span>�� ��� ��� �����մϴ�.)-->
				<input type="hidden" name="usereserve2" id="usereserve2" value="0" />
			</td>
		</tr>
	<? } ?>
	</table>
<?/*

	<?	if(!$reserveuseable ||  $okreserve <= 0 || ($user_reserve -$_data->reserve_maxuse) < 0) { ?>
	<input type="hidden" name="oriuser_reserve" class="st02_1" maxlength="8" value="<?=number_format($user_reserve)?>" />
	<input type="text" name="usereserve" id="usereserve" value="0" />
	<? } ?>
	<?
	}else{ ?>
	<input type="hidden" name="usereserve" id="usereserve" value="0" />
	<? 
	*/
	}
	?>
	<!-- �������� ���� END -->

	<? if(!_empty($_ShopInfo->getMemid()) && $_data->coupon_ok !="Y" || !$couponable){ ?>
		<span id="disp_coupon" style="display:none">0</span>
	<? } ?>



	<!-- ���� ����ǰ ���� START -->
	<?
		if( $giftInfoSetArray[0] == "C" OR ( $giftInfoSetArray[0] == "M" AND !_empty($_ShopInfo->getMemid()) ) ){
	?>
	<!-- ���� ����ǰ ����(����� ����/jbum ó�� 20160831)
	<table border="0" cellpadding="0" cellspacing="0" width="100%" class="orderTbl" style="margin-top:30px; display:none" id="giftSelectArea">
		<caption><img src="<?=$Dir?>images/common/order/<?=$_data->design_order?>/order_title_t06.gif"></caption>
		<tr>
			<th>����ǰ ���ް��� ���űݾ�</th>
			<td><input type="text" name="gift01" id="gift01" class="input" style="background-color:#ffffff; padding-left:4px;" maxlength="8" readonly value="<?=$basketItems['gift_price']?>" /> ��</td>
		</tr>
		<tr>
			<th>����ǰ �����ϱ�</th>
			<td style="padding:10px;">
				<div id="noGiftOptionArea">���� ������ ����ǰ�� �����ϴ�.</div>
				<table cellpadding="0" cellspacing="0" border="0" width="100%" class="noBodertbl" id="giftOptionBox" style="display:none">
					<tr>
						<td rowspan="2" style="border:1px solid #efefef; width:120px; height:120px; text-align:center"><img src="/images/no_img.gif" id="gift_img" /></td>
						<td style="padding:10px;" valign="top">
							<div style="width:100%; text-align:left;">
								<select name="giftval_seq" class="st13_1_1">
									<option value="" style="font-weight:bold;">:: ����ǰ���� ::</option>
								</select>
							</div>
							<div id="giftOptionArea">
								<table border="0" cellpadding="0" cellspacing="0">
									<tr>
										<td>�ɼ�1</td>
										<td><select name="giftOpt1" style="width:90%"></select></td>
									</tr>
								</table>
							</div>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<th class="lastTh">����ǰ ��û����<br />(50�� ����)</th>
			<td class="lastTd">
				����ǰ �����Ͽ� ��û������ ������ ��� ������ �ּ���.<br />
				<input type="tel" name="gift_msg" class="input" style="width:99%;" maxlength="50" disabled="disabled" /></td>
		</tr>
	</table>
	-->
	<?
		}
	?>
	<!-- ���� ����ǰ ���� END -->






	<?
		// �׷� ���� �Ǵ� ����
		if($sumprice>0 && !_empty($group_type)) {
	?>

	<!-- <div style="height:94px; text-align:left; line-height:18px; border:6px solid #eee; background:#fff; margin-top:20px; padding:10px;">
		<ul style="list-style:none;">
			<li style="width:150px; float:left; text-align:center; font-size:0px;"><?=$royal_img?></li>
			<li style="float:left; padding:16px;">
				<?=$groupMemberSale?>
				<span id="groupDeliFree"></span>
			</li>
		</ul>
	</div> -->
	<?
		}
	?>










	<!-- �����ϱ� -->
	<? if($ordertype  == "present"){?>
		<table cellpadding="0" cellspacing="0" width="100%" class="orderTbl" style="margin-top:30px;">
			<caption>* ���� �޴� ģ������ ������ ��������.</caption>
			<tr>
				<th>�̸���</th>
				<td><input type=text name="receiver_email" value="" class="input" style="width:99%; BACKGROUND-COLOR:#F7F7F7;"></td>
			</tr>
			<tr>
				<th>����</th>
				<td><textarea name="receiver_message" style="WIDTH:99%; HEIGHT:70px; padding:5px; line-height:17px; border:solid 1px #DFDFDF;"></textarea></td>
			</tr>
		</table>
	<? }?>



		<!-- �뿩 ���� -->
		<?
		$rentSQL = "SELECT * FROM rent_basket_temp WHERE tempkey = '".$_ShopInfo->getTempkey()."' ";
		$rentRES = mysql_query($rentSQL);
		if ( mysql_num_rows($rentRES) ) {
			$rentROW = mysql_fetch_assoc($rentRES);
		?>
		<div style="float:right; width:65%;" id="orderPaySel">
			<table width="100%" border="0" cellpadding="0" cellspacing="0" class="orderTbl" style="margin-top:30px;">
				<caption><img src="<?= $Dir ?>images/common/order/<?= $_data->design_order ?>/order_title_rent.gif" alt="�뿩 �Ⱓ">
				</caption>
				<tr>
					<th class="lastTh">�뿩 ����</th>
					<td class="lastTd">
						<?=$rentROW['sdate']?> ~ <?=$rentROW['edate']?>
					</td>
				</tr>
			</table>
		</div>
		<?
		}
		?>




	<!-- �������� ���� START -->
	<style>
		.paytype {border:1px solid #ddd; border-bottom:hidden; float:right; width:65%; margin-top:10px;}
		.paytype caption {display:none;}
		.paytype th {height:25px; padding-left:10px; color:#666; text-align:left; border-bottom:1px solid #ddd;}
		.paytype td {padding:6px 0px 6px 10px; border-bottom:1px solid #ddd;}
		.paytext {font-size:11px;}

		.payTotal {float:left; margin-top:10px; border:1px solid #ddd; border-bottom:hidden;}
		.payTotal th {width:120px; padding-left:15px; background-color:#f5f5f5; font-size:11px; font-family:����; color:#666; text-align:left; border-right:1px solid #ddd; border-bottom:1px solid #ddd;}
		.payTotal td {padding:3px 10px; border-bottom:1px solid #ddd; text-align:right;}
	</style>

	
	<table width="100%" border="0" cellpadding="0" cellspacing="0" class="orderTbl" style="margin-top:30px;">
		<caption><img src="<?=$Dir?>images/common/order/<?=$_data->design_order?>/order_title_t03.gif"></caption>
	</table>
	<div class="payTotal">
		<table border="0" cellpadding="0" cellspacing="0" width="400">
			<caption style="display:none;">�� ���� ����</caption>
			<tr>
				<th>�� ��ǰ�ݾ�</th>
				<td><span style="font-size:13px;"><?=number_format($sumprice+$sumpricevat)?></span> ��</td>
			</tr>
			<tr>
				<th>��ǰ����</th>
				<td>(-) <?=number_format($disctotal)?> ��</td>
			</tr>
			<tr>
				<th>�������</th>
				<td>(-) <span id="now_disp_last">0</span> ��</td>
			</tr>
			<? if(!_empty($_ShopInfo->getMemid())){ ?>
			<tr>
				<th>������</th>
				<td>(-) <span id="disp_reserve">0</span> ��</td>
			</tr>
			<!--
			<?	if($_data->coupon_ok =="Y" && $couponable) { ?>
			<tr>
				<th>��������</th>
				<td><span id="disp_coupon">0</span> ��</td>
			</tr>
			<? } ?>
			-->
			<tr>
				<th>ȸ���׷�(�߰�)����</th>
				<td><span id="disp_groupdiscount">0</span> ��</td>
			</tr>
			<? } ?>
			<tr>
				<th>��ۺ�</th>
				<td><span id="disp_deliprice"><!-- <?=number_format($basketItems['deli_price'])?> -->0</span> ��</td>
			</tr>
			<tr>
				<th><b>�����ݾ�</b></th>
				<td style="color:red; font-weight:bold;"><span id="disp_last_price" style="font-size:18px; font-family:Tahoma;"><?=number_format($basketItems['sumprice']+$basketItems['deli_price']+$basketItems['sumpricevat'])?></span> ��</td>
			</tr>
		</table>
	</div>

	<div style="float:right; width:65%;" id="orderPaySel">
		<table width="100%" border="0" cellpadding="0" cellspacing="0">
			<? /*
			<tr>
				<td style="font-size:13px;color:#ff0000;line-height:20px;">
					�ſ�ŷ� �ĺ��Ա��� ��� �������Աݼ����� �������ּ���.<br/>
					��, �ſ���� ��� ��å�� ���յ��� ���� ��� ������Ұ� �� �� �ֽ��ϴ�.
				</td>
			</tr>
			*/ ?>

			<tr>
				<td class="lastTd" style="padding-top:10px">
					<?
						// �������� - order.php    ���� ���� ����
						echo $payType;
					?>
				</td>
			</tr>
		</table>
	</div>

	<!-- �⺻ �ȳ� ������ -->
	<div id="simg0" class="paytype">
		<table border="0" cellpadding="0" cellspacing="0" width="100%" height="<?=$payHeight?>">
			<caption>���� ���� ����</caption>
			<tr>
				<th style="text-align:center">��������� �����ϼ���.</th>
			</tr>
			<!--tr>
				<td height="100%" class="paytext">
					- ���� ������ �����Ͻ� �� �Ʒ��� <b>�����ϱ�</b> ��ư�� Ŭ���� �ֽñ� �ٶ��ϴ�.<br />
					- �ֹ��ڿ� ����� ������ ��Ȯ�ϰ� �Է��Ͽ����� �ٽ��ѹ� Ȯ���� �ֽñ� �ٶ��ϴ�.
				</td>
			</tr-->
		</table>
	</div>

	<!-- ������ �Ա� -->
	<div id="simg1" style="display:none;" class="paytype">
		<table border="0" cellpadding="0" cellspacing="0" width="100%" height="<?=$payHeight?>">
			<caption>������ �Ա�</caption>
			<tr>
				<th colspan="2"><img src="<?=$Dir?>images/common/order/<?=$_data->design_order?>/order_skin_point.gif" border="0">������ �Ա�</th>
			</tr>
			<tr>
				<th><img src="<?=$Dir?>images/common/order/<?=$_data->design_order?>/order_skin_point.gif" border="0">�Աݰ��� ����</th>
				<td>
				<? $arrpayinfo=explode("=",$_data->bank_account); ?>
					<select name="sel_bankinfo" class="st51_1_5">
						<option value=""><?=_empty($arrpayinfo[1])?'�Ա� ���¹�ȣ ���� (�ݵ�� �ֹ��� �������� �Ա�)':$arrpayinfo[1]?></option>
				<? if(!_empty($arrpayinfo[0])){
						$count = 0;
						$tok = strtok($arrpayinfo[0],",");
						while($tok){ ?>
						<option value="<?=$tok?>" ><?=$tok?></option>
				<?			$tok = strtok(",");
							$count++;
						} // end while
					} // end if
					?>
					</select>
				</td>
			</tr>
			<tr>
				<th><img src="<?=$Dir?>images/common/order/<?=$_data->design_order?>/order_skin_point.gif" border="0">�Ա��ڸ�</th>
				<td><input type="text" name="bankname" value="" size="12" class="input" style="BACKGROUND-COLOR:#F7F7F7;"> <font color="#999999" style="font-size:11px; letter-spacing:-0.5px;">(�ֹ��ڿ� ������� �����ϼŵ� �˴ϴ�.)</font></td>
			</tr>
			<tr>
				<td colspan="2" height="100%" class="paytext">
					- ������ �Ա��� ��� <FONT COLOR="#EE1A02">�Ա�Ȯ�� �� </font> ���ó���� ����Ǹ�, �����ϰ� ������ ��ǰ�� ����մϴ�.
				</td>
			</tr>
		</table>
	</div>

	<!-- ī����� -->
	<div id="simg2" style="display:none;" class="paytype">
		<table border="0" cellpadding="0" cellspacing="0" width="100%" height="<?=$payHeight?>">
			<caption>�ſ�ī��</caption>
			<tr>
				<th><img src="<?=$Dir?>images/common/order/<?=$_data->design_order?>/order_skin_point.gif" border="0">�ſ�ī��</th>
			</tr>
			<tr>
				<td height="100%" class="paytext">
					- �ſ�ī�� ������ ������ ���� ������, 128bit SSL�� ��ȣȭ�� ����â�� ���� ��ϴ�.<br />
					- ���� ��, ī������� [<FONT COLOR="#EE1A02">����������</font>]���� ǥ�õ˴ϴ�!
					</span>
				</td>
			</tr>
		</table>
	</div>

	<!-- �ǽð�������ü -->
	<div id="simg3" style="display:none;" class="paytype">
		<table border="0" cellpadding="0" cellspacing="0" width="100%" height="<?=$payHeight?>">
			<caption>�ǽð� ������ü</caption>
			<tr>
				<th><img src="<?=$Dir?>images/common/order/<?=$_data->design_order?>/order_skin_point.gif" border="0">�ǽð� ������ü</th>
			</tr>
			<tr>
				<td height="100%" class="paytext">
					- ���ΰ��� �����Է����� �����ݾ��� ��ü�Ǵ� ���� �Դϴ�.<br />
					- ���ͳݹ�ŷ�� ������ ���ȹ���� �����ϹǷ� �����ϸ�, ������ ������ ���� �ʽ��ϴ�.
				</td>
			</tr>
		</table>
	</div>

	<!-- ������� -->
	<div id="simg4" style="display:none;" class="paytype">
		<table border="0" cellpadding="0" cellspacing="0" width="100%" height="<?=$payHeight?>">
			<caption>�������</caption>
			<tr>
				<th><img src="<?=$Dir?>images/common/order/<?=$_data->design_order?>/order_skin_point.gif" border="0">�������</th>
			</tr>
			<tr>
				<td height="100%" class="paytext">- ����! 1ȸ�� ����(�������) �Աݽ�, �̸�/�ݾ��� �ݵ�� ��ġ�Ǿ�� �Ա�Ȯ���� �����մϴ�.</td>
			</tr>
		</table>
	</div>

	<!-- ������ݿ�ġ��(����ũ��) -->
	<div id="simg5" style="display:none;" class="paytype">
		<table border="0" cellpadding="0" cellspacing="0" width="100%" height="<?=$payHeight?>">
			<caption>������ݿ�ġ��(����ũ��)</caption>
			<tr>
				<th><img src="<?=$Dir?>images/common/order/<?=$_data->design_order?>/order_skin_point.gif" border="0">������ݿ�ġ��(����ũ��)</th>
			</tr>
			<tr>
				<td height="100%" class="paytext">
					- ����ũ�θ� ���ؼ� ���Ű����� �Ͻ� �� �ִ� ��������Դϴ�.<br>
					- ����! 1ȸ�� ����(�������) �Աݽ�, �̸�/�ݾ��� �ݵ�� ��ġ�Ǿ�� �Ա�Ȯ���� �����մϴ�.
				</td>
			</tr>
		</table>
	</div>

	<!-- �ڵ��� ���� -->
	<div id="simg6" style="display:none;" class="paytype">
		<table border="0" cellpadding="0" cellspacing="0" width="100%" height="<?=$payHeight?>">
			<tr>
				<th><img src="<?=$Dir?>images/common/order/<?=$_data->design_order?>/order_skin_point.gif" border="0">�ڵ��� ����</th>
			</tr>
			<tr>
				<td colspan="2" height="100%" class="paytext">
					- ���������� ������ ���� ������, ���� ����� ����â�� ���� ��ϴ�.<br>
					- ���� ��, �ڵ��� ��� û������ '(��)�ٳ�' �� ǥ�õ˴ϴ�.
				</td>
			</tr>
		</table>
	</div>
	<!-- �������� ���� END -->



	

<?
	}
?>

	<!-- ������ -->
	<? if(substr($ordertype,0,6) == "pester"){?>
		<table cellpadding="0" cellspacing="0" width="100%" class="orderTbl" style="margin-top:30px;">
			<caption><img src="<?=$Dir?>images/common/order/<?=$_data->design_order?>/order_title_t07.gif" alt="" /></caption>
			<tr>
				<th>���� �̸�</th>
				<td><input type=text name="pester_name" size="15" maxlength="12" class="input" style="BACKGROUND-COLOR:#F7F7F7;"></td>
			</tr>
			<tr>
				<th>���� ��ȭ��ȣ</th>
				<td><input type=text name="pester_tel1" value="" size="5" maxlength="3" onKeyUp="strnumkeyup(this)" class="input" style="BACKGROUND-COLOR:#F7F7F7;"> - <input type=text name="pester_tel2" value="" size="5" maxlength="4" onKeyUp="strnumkeyup(this)" class="input" style="BACKGROUND-COLOR:#F7F7F7;"> - <input type=text name="pester_tel3" value="" size="5" maxlength="4" onKeyUp="strnumkeyup(this)" class="input" style="BACKGROUND-COLOR:#F7F7F7;"></td>
			</tr>
			<tr>
				<th>���� �̸���</th>
				<td><input type=text name="pester_email" value="" size="30" class="input" style="width:80%;BACKGROUND-COLOR:#F7F7F7;"></td>
			</tr>
			<tr>
				<th>SMS ���۸޼���</th>
				<td><textarea name="pester_smstxt" style="WIDTH:98%; HEIGHT:70px; padding:5px; line-height:17px; border:solid 1px #DFDFDF;"></textarea></td>
			</tr>
			<tr>
				<th>E-MAIL<br />���۸޼���</th>
				<td><textarea name="pester_emailtxt" style="WIDTH:98%; HEIGHT:70px; padding:5px; line-height:17px; border:solid 1px #DFDFDF;"></textarea></td>
			</tr>
		</table>
	<? }?>

	</div>
</div>

<? /*<div style="height:6px;background:url('/data/design/img/main/bot_boxline.gif') no-repeat;font-size:0px;"></div>*/ ?>