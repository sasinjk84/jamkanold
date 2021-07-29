
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
</style>



<div style="clear:both;height:6px;background:url('/data/design/img/main/top_boxline.gif') no-repeat;font-size:0px;"></div>
<div style="padding:10px 30px;background:#ffffff url('/data/design/img/main/bg_boxline.gif') repeat-y;overflow:hidden;">
	<div style="float:left;padding-left:50px;height:35px;line-height:35px;background:url('/data/design/img/sub/icon_basket.gif') no-repeat;color:#696969;font-size:25px;font-weight:600;">�ֹ�/����</div>
	<div style="float:right;margin-top:3px;"><img src="/data/design/img/sub/step_basket.gif" alt="" /></div>
	<div style="clear:both;"></div>
</div>
<div style="height:6px;background:url('/data/design/img/main/bot_boxline.gif') no-repeat;font-size:0px;"></div>



<div style="clear:both;margin-top:20px;height:6px;background:url('/data/design/img/main/top_boxline.gif') no-repeat;font-size:0px;"></div>
<div style="width:100%;padding:20px 30px;background:#ffffff url('/data/design/img/main/bg_boxline.gif') repeat-y;overflow:hidden;">
<div style="height:20px; text-align:right; color:#999; font-size:11px; font-family:����; letter-spacing:-1px;">�ֹ������� �Է��Ͻ� ��, <span style="color:red;">������ư</span>�� �����ּ���.</div>

<table border="0" cellpadding="0" cellspacing="0" width="100%" class="itemListTbl">
	<caption style="text-align:left; display:none;">�ֹ��� �ۼ�</caption>
	<colgroup>
		<col>
		<col>
		<col width="50">
		<col width="80">
		<col width="80">
		<col width="80">
		<col width="80">
		<col width="120">
	</colgroup>
	<thead>
		<tr>
			<th class="thstyle" colspan="2">��ǰ��/�ɼ�1</th>
			<th class="thstyle">����</th>
			<th class="thstyle">�ǸŰ�</th>
			<th class="thstyle">������</th>
			<th class="thstyle">�հ�</th>
			<th class="thstyle">��ۺ�</th>
			<th class="thstyle2">����</th>
		</tr>
	</thead>
	<tbody>
		<?
			$formcount = 0;
			$couponable = false;
			$reserveuseable = false;
			$productRealPrice = 0;
			if($basketItems['productcnt'] <1){
		?>
		<tr>
			<td colspan="8" style="text-align:center; height:30px;">��ϵ� ��ǰ�� �����ϴ�.</td>
		</tr>
		<?
			}else{
					$timgsize = 50;
					foreach($basketItems['vender'] as $vender=>$vendervalue){
						for( $i = 0 ; $i < count($vendervalue['products']) ; $i++ ){
							$product = $vendervalue['products'][$i];

							if(!$couponable && $product['cateAuth']['coupon'] == 'Y'){
								$chkcoupons = array();
								$chkcoupons = getMyCouponList($product['productcode']);
								if(_array($chkcoupons)) {
									$couponable = true;
								}
							}
							if(!$reserveuseable && $product['cateAuth']['reserve'] == 'Y') {
								$reserveuseable = true;
							}

							if($product['tinyimage'][$product['tinyimage']['big']] > $timgsize) {
								$imageSize = $product['tinyimage']['big'].'="'.$timgsize.'"';
							} else{
								$imageSize = "";
							}


							$formcount++;


							//
							$arPresent[$formcount] = $product['present_state'];
							$arPester[$formcount] = $product['pester_state'];
							$sellChk = true;
							if($product['sell_startdate'] && $product['sell_enddate']){
								$sellChk = false;
								if($product['sell_startdate']<time() && time()<$product['sell_enddate']){
									$sellChk = true;
								}
							}



							// ��ۺ� ���� ���� ���� ���
							$venderDeliPrint = "";
							$venderDeliPrintCHK = false;
							if( strlen($vendervalue['deli_after']) == 0 AND $vendervalue['conf']['deli_mini'] < 1000000000 AND $vendervalue['delisumprice'] > 0 ) { // ������ �ƴҰ��
								$venderDeliPrint .= "<b>������ ����</b>". ( $vendervalue['conf']['groupDeli'] > 1 ? "(ȸ����� �����å ����)" : "" );

								if( $vendervalue['delisumprice'] >= $vendervalue['conf']['deli_mini'] ){
									$venderDeliPrint .= "<font color='#ff6600'><strong>[�����]</strong></font>";
									$venderDeliPrintCHK = true;
								}

								//$venderDeliPrint .= "&nbsp;:&nbsp;�����纰 ���űݾ�(<b>".number_format($vendervalue['delisumprice'])."��</b>, ������ۻ�ǰ ".( $vendervalue['conf']['deli_pricetype'] == "Y" ? "����" : "����" ).")�� <b>".number_format($vendervalue['conf']['deli_mini'])."��</b> �̻��� ���";
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
								if($vender == 0) {
									$deliPrt = "�⺻��ۺ�<br />(". ( $vendervalue['conf']['deli_price'] > 0 && $venderDeliPrintCHK == false ? number_format($vendervalue['conf']['deli_price'])."��" : "����" ) .")";
									$productRealPrice += $product['realprice'];
								} else {
									//$deliPrt = "������<br />�⺻���<br />(". ( $vendervalue['conf']['deli_price'] > 0 ? number_format($vendervalue['conf']['deli_price'])."��" : "����" ) .")";
									$deliPrt = "�⺻���<br />(". ( $vendervalue['conf']['deli_price'] > 0 ? number_format($vendervalue['conf']['deli_price'])."��" : "����" ) .")";
								}
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
				if($product['cateAuth']['gift'] == 'Y' && checkGiftSet()) array_push($sptxt,'<img src=\'/images/common/basket/001/basket_spe_icon004o.gif\' hspace=\'1\' alt=\'����ǰ ����\' />');
				if(_array($sptxt)){
					//echo '<br />'.implode(' / ',$sptxt);
					echo '<div style=\'margin-top:5px; font-size:0px;\'>'.implode('',$sptxt).'</div>';
				}
			?>

				<!-- �뿩 ���� -->
				<?
				//if ( !empty($product['rentStartDate']) ) {
				if ( $product['rental'] == 2 ) {
					?>
					<div>
						�뿩���� : <?= $product['rentStartDate'] ?> ~ <?= $product['rentEndDate'] ?>
					</div>
				<?
				}
				?>
			</td>
			<td class="tdstyle" align="center">
				<?
				//if ( !empty($product['rentStartDate']) ) {
				if ( $product['rental'] == 2 ) {
					foreach ( $product['rentPriceMsg'] as $k => $v ) {
						echo $v."<br>";
					}
				} else {
					echo $product['quantity']."��";
				}
				?>
			</td>
			<td class="tdstyle" align="center"><?=number_format($product['sellprice'])?>��</td>
			<td class="tdstyle" align="center"><?=number_format($product['reserve'])?>��</td>
			<td class="tdstyle" align="center"><b><?=number_format($product['realprice'])?>��</b></td>
			<?
				if( strlen($deliPrt) > 0 ) {
			?>
			<td class="tdstyle" align="center" <?=$deliPrtRowspan?>><?=$deliPrt?></td>
			<?
				}
			?>



			<td class="tdstyle2">
			<?
				$mycoupons = $ablecoupons =array();
				if($_data->coupon_ok=="Y" && $product['cateAuth']['coupon'] == 'Y' && checkGroupUseCoupon()){
					$mycoupons = getMyCouponList($product['productcode']);
					$ablecoupons = ableCouponOnProduct($product['productcode'],$product['vender'],true);
					if(_array($mycoupons)){
						foreach($mycoupons as $abcoupon){
							echo "<span class=\"couponDownArea\">";
							echo "<b>��</b>&nbsp;".number_format(intval($abcoupon['sale_money'])).(($abcoupon['sale_type']<'3')?'%':'��').((intval($abcoupon['sale_type'])%2 == 1)?"����":"����");

							if(_array($mycoupon_codes) && in_array($abcoupon['coupon_code'],$mycoupon_codes)){
								echo "<img src=\"".$Dir."images/common/order/".$_data->design_order."/icon_get.gif\" border=\"0\" style=\"position:relative; top:0.2em;\" alt=\"������\" /><br />";
							}else{
								echo "<a href=\"javascript:issue_coupon('".$abcoupon['coupon_code']."','".$product['productcode']."');\"><img src=\"".$Dir."images/common/order/".$_data->design_order."/icon_download.gif\" style=\"position:relative; top:0.2em;\" border=\"0\" alt=\"�����ٿ�\" /><br /></a>";
							}
							echo '</span>';
						}
					}

					if(_array($ablecoupons)){
						foreach($ablecoupons as $abcoupon){
							echo '<span class="couponDownArea">';
							echo '<b>��</b>&nbsp;'.number_format(intval($abcoupon['sale_money'])).(($abcoupon['sale_type']<'3')?'%':'��').((intval($abcoupon['sale_type'])%2 == 1)?'����':'����');

							if(_array($mycoupon_codes) && in_array($abcoupon['coupon_code'],$mycoupon_codes)){
								echo "<img src=\"".$Dir."images/common/order/".$_data->design_order."/icon_get.gif\" border=\"0\" style=\"position:relative; top:0.2em;\" alt=\"������\" /><br />";
							}else{
								echo "<a href=\"javascript:issue_coupon('".$abcoupon['coupon_code']."','".$product['productcode']."');\"><img src=\"".$Dir."images/common/order/".$_data->design_order."/icon_download.gif\" style=\"position:relative; top:0.2em;\" border=\"0\" alt=\"�����ٿ�\" /><br /></a>";
							}
							echo '</span>';
						}
					}
				}

				if(!$ablecoupons){
					echo "&nbsp;";
				}
			?>
			</td>
		</tr>
		<?
			}// end for
		?>
		<tr>
			<td colspan="8" bgcolor="#f9f9f9" style="padding:15px 10px; text-align:right;">
				<div style="font-size:11px; margin-bottom:5px;">
				<?=$venderDeliPrint?>
				</div>
				��ۺ� : <b><?=number_format($vendervalue['deliprice'])?></b>�� / <b>�հ� : </b><span style="color:#ff6600; font-size:15px; font-family:tahoma; font-weight:bold;"><?=number_format($vendervalue['sumprice'])?>��</span>
			</td>
		</tr>
		<tr><td colspan=8 height=1 bgcolor="#DDDDDD"></td></tr>
	<?
				} // end foreach
			} // end if
	?>
	</tbody>
	<tfoot>
		<tr><td colspan=8 height=2 bgcolor="#DDDDDD"></td></tr>
		<tr>
			<td colspan="3" bgcolor="#eeeeee" style="padding-left:20px;">
				<? if( $ordertype == "" ) { ?><a href="/front/basket.php?ordertype=<?=$ordertype?>"><img src="<?=$Dir?>images/common/order/<?=$_data->design_order?>/btn_basket.gif" border="0" alt="��ٱ��� ���ư���" /></a><? } ?>
			</td>
			<td colspan="5" bgcolor="#eeeeee" style="padding:10px 0px;">
				<div style="float:right;">
					<div style="font-size:11px; color:#777777; text-align:right; letter-spacing:-0.5pt; margin-bottom:5px; padding-right:10px;"><?=$groupMemberSale?></div>
					<table border="0" cellpadding="0" cellspacing="0" align="right">
						<tr>
							<td><!--<img src="<?=$Dir?>images/common/basket/<?=$_data->design_basket?>/basket_skin3_point.gif" alt="������" />-->������ : <span class="basket_etc_price"><?=number_format($basketItems['reserve'])?></span><b>��</b></td>
							<td width="20"></td>

							<?
								// !--��ۺ� �հ�
								if($basketItems['deli_price']>0){
							?>
							<td><!--<img src="<?=$Dir?>images/common/basket/<?=$_data->design_basket?>/basket_skin3_trans.gif">-->��ۺ� : <span class="basket_etc_price2"><?=number_format($basketItems['deli_price'])?></span><b>��</b></td>
							<td width="20"></td>
							<?
								}
							?>

							<?
								//VAT �հ�ݾ�
								if($_data->ETCTYPE["VATUSE"]=="Y") {
									$sumpricevat = return_vat($basketItems['sumprice']);
							?>
							<td><!--<img src="<?=$Dir?>images/common/basket/<?=$_data->design_basket?>/basket_skin3_vat.gif">-->VAT�հ�ݾ� : <span class="basket_etc_price"><?=number_format($sumpricevat)?></span><B>��</B></td>
							<td width="20"></td>
							<?
								}
							?>

							<td><!--<img src="<?=$Dir?>images/common/basket/<?=$_data->design_basket?>/basket_skin3_tprice.gif" alt="�� �����ݾ�" />--><b>�� �����ݾ� : <span class="basket_etc_price3"><?=number_format($basketItems['sumprice'])?>��</span></b></td>
							<td width="10"></td>
						</tr>
					</table>
				</div>

			</td>
		</tr>
		<tr><td colspan=7 height=1 bgcolor="#DDDDDD"></td></tr>
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




<table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin-top:40px;">
	<caption style="display:none;">�ֹ��� ���� �Է�</caption>
	<tr>
		<td width="50%" height="100%" valign="top" style="padding-right:15px;">

<!-- �ֹ��� ���� �Է� START -->
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
			<input type=text name="spost1" size="3" onclick="this.blur();get_post('s')" value="<?=$home_post1?>" class="input" style="BACKGROUND-COLOR:#F7F7F7;"> - <input type=text name="spost2" size="3" onclick="this.blur();get_post('s')" value="<?=$home_post2?>" class="input" style="BACKGROUND-COLOR:#F7F7F7;"> <a href="javascript:get_post('s');"><img src="<?=$Dir?>images/common/order/<?=$_data->design_order?>/order_skin_btn2.gif" border="0" align="absmiddle" /></a><br />
			<input type=text name="saddr1" style="width:96%; BACKGROUND-COLOR:#F7F7F7;" value="<?=$home_addr1?>" class="input" readonly><br />
			<input type=text name="saddr2" style="width:96%; BACKGROUND-COLOR:#F7F7F7;" class="input" value="<?=!_empty($home_addr2)?$home_addr2:'������ �ּ�'?>">
		</td>
	</tr>
</table>
<!-- �ֹ��� ���� �Է� END -->

		</td>
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
					<th>�������̸�</th>
					<td><input type=text name="receiver_name" size="15" maxlength="12" class="input" style="BACKGROUND-COLOR:#F7F7F7;" value="<?=$receiver_name?>"></td>
				</tr>
				<tr>
					<th>��ȭ��ȣ</th>
					<td><input type=text name="receiver_tel11" size="5" maxlength="3" onKeyUp="strnumkeyup(this)" class="input" style="BACKGROUND-COLOR:#F7F7F7;" value="<?=$receiver_tel11?>"> - <input type=text name="receiver_tel12" size="5" maxlength="4" onKeyUp="strnumkeyup(this)" class="input" style="BACKGROUND-COLOR:#F7F7F7;" value="<?=$receiver_tel12?>"> - <input type=text name="receiver_tel13" size="5" maxlength="4" onKeyUp="strnumkeyup(this)" class="input" style="BACKGROUND-COLOR:#F7F7F7;" value="<?=$receiver_tel13?>"></td>
				</tr>
				<tr>
					<th>�ڵ�����ȣ</th>
					<td><input type=text name="receiver_tel21" size="5" maxlength="3" onKeyUp="strnumkeyup(this)" class="input" style="BACKGROUND-COLOR:#F7F7F7;" value="<?=$receiver_tel21?>"> - <input type=text name="receiver_tel22" size="5" maxlength="4" onKeyUp="strnumkeyup(this)" class="input" style="BACKGROUND-COLOR:#F7F7F7;" value="<?=$receiver_tel22?>"> - <input type=text name="receiver_tel23" size="5" maxlength="4" onKeyUp="strnumkeyup(this)" class="input" style="BACKGROUND-COLOR:#F7F7F7;" value="<?=$receiver_tel23?>"></td>
				</tr>
				<tr>
					<th>�̸���</th>
					<td><input type=text name="email" value="<?=$receiver_email?>" size="30" class="input" style="width:96%; BACKGROUND-COLOR:#F7F7F7;"></td>
				</tr>
				<tr>
					<th class="lastTh">�ּ�</th>
					<td class="lastTd" height="<?=$addrHight?>">
						<? if(!_empty($_ShopInfo->getMemid()) && $ordertype  != "present"){ ?>
						<div id="addressSelDiv"><input type=radio name="addrtype" value="H" onclick="addrchoice()" style="border:none;">����&nbsp;<input type=radio name="addrtype" value="O" onclick="addrchoice()" style="border:none;">ȸ��&nbsp;<input type=radio name="addrtype" value="B" onclick="addrchoice()" style="border:none;">�ֱ� �����&nbsp;<input type=radio name="addrtype" value="N" onclick="get_post('r')" style="border:none;">�ű� �����</div>
						<? } ?>
						<input type=text name="rpost1" size="3" onclick="this.blur();get_post('r')" class="input" style="BACKGROUND-COLOR:#F7F7F7;" value="<?=$receiver_zip1?>"> - <input type=text name="rpost2" size="3" onclick="this.blur();get_post('r')" class="input" style="BACKGROUND-COLOR:#F7F7F7;" value="<?=$receiver_zip2?>"> <a href="javascript:get_post('r');"><img src="<?=$Dir?>images/common/order/<?=$_data->design_order?>/order_skin_btn2.gif" border="0" align="absmiddle" /></a><br />
						<input type=text name="raddr1" size="50" style="width:96%; BACKGROUND-COLOR:#F7F7F7;" class="input" readonly value="<?=$receiver_addr1?>"><br />
						<input type=text name="raddr2" size="50" style="width:96%; BACKGROUND-COLOR:#F7F7F7;" class="input"  value="<?=$receiver_addr2?>">
					</td>
				</tr>
			</table>
			<!-- ����� ���� �Է� END -->
		</td>
	</tr>
</table>

<table border="0" cellpadding="0" cellspacing="0" width="100%" class="orderTbl" style="margin-top:15px;">
	<tr>
		<th>�ֹ��޽���<br />(50�� ����)</th>
		<td>
			<input type="text" name="order_prmsg" value="" class="input" style="width:99%; BACKGROUND-COLOR:#F7F7F7;" /><br />
			�ù� ���忡 ���� �޽��� �Դϴ�. ��) ����� ���ǿ� �ð��ּ���.
		</td>
	</tr>
<? if(_empty($_ShopInfo->getMemid())){?>
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
<? }?>
</table>



<?
	if(substr($ordertype,0,6) != "pester"){
?>


	<!-- �������� ���� START-->
	<?
	if(substr($ordertype,0,6)!= "pester" && $socialshopping != "social" && !_empty($_ShopInfo->getMemid()) && (($reserveuseable && $okreserve > 0 && ($user_reserve -$_data->reserve_maxuse) > 0) || (($_data->coupon_ok=="Y" && checkGroupUseCoupon()) || $couponable))){
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

	<?
		//echo $reserveuseable.'<br>'.$okreserve.'<br>'.$user_reserve.'<br>'.$_data->reserve_maxuse.'<br>';
		if( ($_data->coupon_ok=="Y" && checkGroupUseCoupon() && $couponable) OR ($reserveuseable && ( $okreserve > 0 OR ($user_reserve -$_data->reserve_maxuse) > 0 ) ) ) {
	?>
	<table border="0" cellpadding="0" cellspacing="0" width="100%" class="orderTbl" style="margin-top:30px;">
		<caption><img src="<?=$Dir?>images/common/order/<?=$_data->design_order?>/order_title_t02.gif"></caption>
	<?	if($_data->coupon_ok=="Y" && checkGroupUseCoupon() && $couponable) { // ���� ��� ����?>
		<tr>
			<th>�������� ����</th>
			<td>
				<li>���αݾ� : <input type="text" name="coupon_price" id="coupon_price" class="st02_1" maxlength="8" value="0" readonly="readonly" /> �� </li>
				<li><A HREF="javascript:coupon_check()" onmouseover="window.status='��������';return true;"><img src="<?=$Dir?>images/common/order/<?=$_data->design_order?>/order_skin_btn1.gif" border="0" align="absmiddle"></A> <a href="javascript:resetCoupon()"><img src="<?=$Dir?>images/common/order/<?=$_data->design_order?>/order_skin_btn3.gif" border="0" align="absmiddle"></a> <?=$offlineCouponInputButton?></li><br />
			���� ������ ��ȸ�Ͻ� �� ���������Ͻø� ����(Ȥ�� �߰�����) ������ ������ �� �ֽ��ϴ�.</td>
		</tr>
	<? } ?>

	<?
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
				<input type="text" name="oriuser_reserve" class="st02_1" maxlength="8" value="<?=number_format($user_reserve)?>" readonly="readonly" /> �� �߿�
				<input type="text" name="usereserve" id="usereserve" class="st02_1" maxlength="8" value="0"  <?=($okreserve<1)?'disabled="disabled"':''?>  /> ���� ���<br /><span style="color:red"> <span style="font-weight:bold"><?=number_format($okreserve)?>��</span> ���� ���������� ����Ͽ� �����ϽǼ� �ֽ��ϴ�.(�����ݻ��Ұ� ��ǰ ���ܱݾ�)</span><br />
				<?
					}else{
				?>
					<input type="hidden1" name="usereserve" id="usereserve" class="st02_1" maxlength="8" value="0" />
					<strong>[���������� : <?=number_format($user_reserve)?>]</strong>
				<?
					}
				?>
				(�����Ͻ� �������� <span style="font-weight:bold"><?=number_format($_data->reserve_maxuse)?>�� �̻�</span>�� ��� ��� �����մϴ�.)
			</td>
		</tr>
	<? } ?>
	</table>
	<?
		}
	?>


	<?	if(!$reserveuseable ||  $okreserve <= 0 || ($user_reserve -$_data->reserve_maxuse) < 0) { ?>
	<input type="hidden" name="oriuser_reserve" class="st02_1" maxlength="8" value="<?=number_format($user_reserve)?>" />
	<input type="hidden" name="usereserve" value="0" />
	<? } ?>
	<?
	}else{ ?>
	<input type="hidden" name="usereserve" id="usereserve" value="0" />
	<? } ?>
	<!-- �������� ���� END -->

	<?	if(!_empty($_ShopInfo->getMemid()) && $_data->coupon_ok !="Y" || !$couponable) { ?><span id="disp_coupon" style="display:none">0</span><? } ?>



	<!-- ���� ����ǰ ���� START -->
	<?
		if( $giftInfoSetArray[0] == "C" OR ( $giftInfoSetArray[0] == "M" AND !_empty($_ShopInfo->getMemid()) ) ){
	?>
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
	<?
		}
	?>
	<!-- ���� ����ǰ ���� END -->






	<?
		// �׷� ���� �Ǵ� ����
		if($sumprice>0 && !_empty($group_type)) {
	?>

	<div style="height:94px; text-align:left; line-height:18px; border:6px solid #eee; background:#fff; margin-top:20px; padding:10px;">
		<ul style="list-style:none;">
			<li style="width:150px; float:left; text-align:center; font-size:0px;"><?=$royal_img?></li>
			<li style="float:left; padding:16px;">
				<?=$groupMemberSale?>
				<span id="groupDeliFree"></span>
			</li>
		</ul>
	</div>
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
		<div style="float:left; width:100%;" id="orderPaySel">
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
	<div style="float:left; width:100%;" id="orderPaySel">
		<table width="100%" border="0" cellpadding="0" cellspacing="0" class="orderTbl" style="margin-top:30px;">
			<caption><img src="<?=$Dir?>images/common/order/<?=$_data->design_order?>/order_title_t03.gif"></caption>
			<tr>
				<th class="lastTh">���� ���� ����</th>
				<td class="lastTd">
					<?
						// �������� - order.php    ���� ���� ����
						echo $payType;
					?>
				</td>
			</tr>
		</table>
	</div>


	<style>
		.paytype {border:1px solid #ddd; border-bottom:hidden; float:left; width:65%; margin-top:10px;}
		.paytype caption {display:none;}
		.paytype th {height:25px; padding-left:10px; color:#666; text-align:left; border-bottom:1px solid #ddd;}
		.paytype td {padding:6px 0px 6px 10px; border-bottom:1px solid #ddd;}
		.paytext {font-size:11px;}

		.payTotal {float:right; margin-top:10px; border:1px solid #ddd; border-bottom:hidden;}
		.payTotal th {width:120px; padding-left:15px; background-color:#f5f5f5; font-size:11px; font-family:����; color:#666; text-align:left; border-right:1px solid #ddd; border-bottom:1px solid #ddd;}
		.payTotal td {padding:3px 10px; border-bottom:1px solid #ddd; text-align:right;}
	</style>

	<!-- �⺻ �ȳ� ������ -->
	<div id="simg0" class="paytype">
		<table border="0" cellpadding="0" cellspacing="0" width="100%" height="<?=$payHeight?>">
			<caption>���� ���� ����</caption>
			<tr>
				<th><img src="<?=$Dir?>images/common/order/<?=$_data->design_order?>/order_skin_point.gif" border="0">���������� ������ �ּ���.</th>
			</tr>
			<tr>
				<td height="100%" class="paytext">
					- ���� ������ �����Ͻ� �� �Ʒ��� <b>�����ϱ�</b> ��ư�� Ŭ���� �ֽñ� �ٶ��ϴ�.<br />
					- �ֹ��ڿ� ����� ������ ��Ȯ�ϰ� �Է��Ͽ����� �ٽ��ѹ� Ȯ���� �ֽñ� �ٶ��ϴ�.
				</td>
			</tr>
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



	<div class="payTotal">
		<table border="0" cellpadding="0" cellspacing="0" width="300">
			<caption style="display:none;">�� ���� ����</caption>
			<tr>
				<th>�հ�</th>
				<td><span style="font-size:13px;"><?=number_format($sumprice+$sumpricevat)?></span> ��</td>
			</tr>
			<? if(!_empty($_ShopInfo->getMemid())){ ?>
			<tr>
				<th>������ ���</th>
				<td><span id="disp_reserve">0</span> ��</td>
			</tr>
			<?	if($_data->coupon_ok =="Y" && $couponable) { ?>
			<tr>
				<th>��������</th>
				<td><span id="disp_coupon">0</span> ��</td>
			</tr>
			<? } ?>
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
				<th>���� �����ݾ�</th>
				<td style="color:red; font-weight:bold;"><span id="disp_last_price" style="font-size:18px; font-family:Tahoma;"><?=number_format($basketItems['sumprice']+$basketItems['deli_price']+$basketItems['sumpricevat'])?></span> ��</td>
			</tr>
		</table>
	</div>

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
<div style="height:6px;background:url('/data/design/img/main/bot_boxline.gif') no-repeat;font-size:0px;"></div>