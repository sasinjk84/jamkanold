<div style="clear:both;height:6px;background:url('/data/design/img/main/top_boxline.gif') no-repeat;font-size:0px;"></div>
<div style="padding:10px 30px;background:#ffffff url('/data/design/img/main/bg_boxline.gif') repeat-y;overflow:hidden;">
	<div style="float:left;padding-left:50px;height:35px;line-height:35px;background:url('/data/design/img/sub/icon_basket.gif') no-repeat;color:#696969;font-size:25px;font-weight:600;">��ٱ���</div>
	<div style="float:right;margin-top:3px;"><img src="/data/design/img/sub/step_basket.gif" alt="" /></div>
	<div style="clear:both;"></div>
</div>
<div style="height:6px;background:url('/data/design/img/main/bot_boxline.gif') no-repeat;font-size:0px;"></div>

<div style="clear:both;margin-top:20px;height:6px;background:url('/data/design/img/main/top_boxline.gif') no-repeat;font-size:0px;"></div>
<div style="padding:20px 30px;background:#ffffff url('/data/design/img/main/bg_boxline.gif') repeat-y;overflow:hidden;">
<?
	// ����Ʈ ���� ����
	if($_data->oneshot_ok=="Y") {
		$codeA=$_POST["codeA"];
		$codeB=$_POST["codeB"];
		$codeC=$_POST["codeC"];
		$codeD=$_POST["codeD"];
		$likecode=$codeA.$codeB.$codeC.$codeD;
?>

<table cellpadding="0" cellspacing="0" width="100%" border="0">
	<tr>
		<td><img src="<?=$Dir?>images/common/basket/<?=$_data->design_basket?>/oneshot_primage001_stext.gif" border="0"></td>
	</tr>
	<tr>
		<td bgcolor="#E8E8E8" style="padding:8px;">

			<table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#ffffff">
				<tr>
					<td bgcolor="#ffffff" style="padding:15px;">

						<form name=form1 method=post action="<?=$_SERVER[PHP_SELF]?>">
						<table cellpadding="0" cellspacing="0" width="100%" border="0">
							<input type=hidden name=productcode>
							<input type=hidden name=quantity>
							<input type=hidden name=option1>
							<input type=hidden name=option2>
							<input type=hidden name=assembleuse>
							<input type=hidden name=package_num>
							<tr>
								<td><IMG SRC="<?=$Dir?>images/common/basket/oneshot_primage001.gif" border="0" width=50 height=50 name="oneshot_primage"></td>
								<td align="center">

									<table cellpadding="0" cellspacing="0">
										<tr>
											<td style="padding:2px;"><select name="codeA" onchange="SearchChangeCate(this,1);CheckCode();" style="width:150;font-size:11px;"><option value="">--- 1�� ī�װ� ���� ---</option></SELECT></td>
											<td style="padding:2px;"><select name="codeB" onchange="SearchChangeCate(this,2);CheckCode();" style="width:150;font-size:11px;"><option value="">--- 2�� ī�װ� ���� ---</option></SELECT></td>
											<td style="padding:2px;"><select name="codeC" onchange="SearchChangeCate(this,3);CheckCode();" style="width:150;font-size:11px;"><option value="">--- 3�� ī�װ� ���� ---</option></SELECT></td>
										</tr>
										<TR>
											<TD style="padding:2px;"><select name="codeD" onchange="CheckCode();" style="width:150;font-size:11px;"><option value="">--- 4�� ī�װ� ���� ---</option></SELECT></td>
											<td colspan="2" style="padding:2px;"><select name="tmpprcode" onchange="CheckProduct();" style="width:306px;font-size:11px;"><option value="">��ǰ ����</option>
												<?
													if(strlen($likecode)==12) {
														$sql = "SELECT a.productcode,a.productname,a.sellprice,a.tinyimage,a.quantity,a.option1,a.option2,a.etctype,a.selfcode,a.assembleuse,a.package_num ";
														$sql.= "FROM tblproduct AS a ";
														$sql.= "LEFT OUTER JOIN tblproductgroupcode b ON a.productcode=b.productcode ";
														$sql.= "WHERE a.productcode LIKE '".$likecode."%' AND a.display='Y' ";
														$sql.= "AND (a.group_check='N' OR b.group_code='".$_ShopInfo->getMemgroup()."') ";
														$sql.= "ORDER BY a.productname ";
														$result=mysql_query($sql,get_db_conn());
														$ii=0;
														$prlistscript="<script>\n";
														while($row=mysql_fetch_object($result)) {
															if(strlen(dickerview($row->etctype,$row->sellprice,1))==0) {
																$miniq = 1;
																if (strlen($row->etctype)>0) {
																	$etctemp = explode("",$row->etctype);
																	for ($i=0;$i<count($etctemp);$i++) {
																		if (substr($etctemp[$i],0,6)=="MINIQ=") $miniq=substr($etctemp[$i],6);  // �ּ��ֹ�����
																	}
																}
																echo "<option value=\"".$ii."\">".strip_tags(str_replace("<br>", " ", viewselfcode($row->productname,$row->selfcode)))." - ".number_format($row->sellprice)."��";
																if(strlen($row->quantity)!=0 && $row->quantity<=0) echo " (ǰ��)";
																echo "</option>\n";

																if(strlen($row->quantity)!=0 && $row->quantity<=0) {
																	$tmpq=0;
																} else {
																	$tmpq=$row->quantity;
																	if($row->quantity==NULL) $tmpq=1000;
																}
																$prlistscript.="var plist=new pralllist();\n";
																$prlistscript.="plist.productcode='".$row->productcode."';\n";
																$prlistscript.="plist.tinyimage='".$row->tinyimage."';\n";
																$prlistscript.="plist.option1=1;\n";
																$prlistscript.="plist.option2=1;\n";
																$prlistscript.="plist.quantity=".$tmpq.";\n";
																$prlistscript.="plist.miniq=".$miniq.";\n";
																$prlistscript.="plist.assembleuse='".($row->assembleuse=="Y"?"Y":"N")."';\n";
																$prlistscript.="plist.package_num='".((int)$row->package_num>0?$row->package_num:"")."';\n";
																$prlistscript.="prall[".$ii."]=plist;\n";
																$prlistscript.="plist=null;\n";
																$ii++;
															}
														}
														mysql_free_result($result);
														$prlistscript.="</script>\n";
													}
												?>
												</SELECT>
											</td>
										</tr>
									</table>

								</td>
								<td><a href="javascript:OneshotBasketIn();"><IMG SRC="<?=$Dir?>images/common/basket/<?=$_data->design_basket?>/basket_skin3_btn1.gif" border="0"></a></td>
							</tr>
						</table>
						</form>


					</td>
				</tr>
			</table>

		</td>
	</tr>
	<?
		$sql = "SELECT * FROM tblproductcode ";
		if(strlen($_ShopInfo->getMemid())==0 || $_ShopInfo->getMemid()=="deleted") {
			$sql.= "WHERE group_code='' ";
		} else {
			$sql.= "WHERE (group_code='' OR group_code='ALL' OR group_code='".$_ShopInfo->getMemgroup()."') ";
		}
		$sql.= "AND (type!='T' AND type!='TX' AND type!='TM' AND type!='TMX') ORDER BY sequence DESC ";
		$i=0;
		$ii=0;
		$iii=0;
		$iiii=0;
		$strcodelist = "";
		$strcodelist.= "<script>\n";
		$result = mysql_query($sql,get_db_conn());
		$selcode_name="";
		while($row=mysql_fetch_object($result)) {
			$strcodelist.= "var clist=new CodeList();\n";
			$strcodelist.= "clist.codeA='".$row->codeA."';\n";
			$strcodelist.= "clist.codeB='".$row->codeB."';\n";
			$strcodelist.= "clist.codeC='".$row->codeC."';\n";
			$strcodelist.= "clist.codeD='".$row->codeD."';\n";
			$strcodelist.= "clist.type='".$row->type."';\n";
			$strcodelist.= "clist.code_name='".$row->code_name."';\n";
			if($row->type=="L" || $row->type=="T" || $row->type=="LX" || $row->type=="TX") {
				$strcodelist.= "lista[".$i."]=clist;\n";
				$i++;
			}
			if($row->type=="LM" || $row->type=="TM" || $row->type=="LMX" || $row->type=="TMX") {
				if ($row->codeC=="000" && $row->codeD=="000") {
					$strcodelist.= "listb[".$ii."]=clist;\n";
					$ii++;
				} else if ($row->codeD=="000") {
					$strcodelist.= "listc[".$iii."]=clist;\n";
					$iii++;
				} else if ($row->codeD!="000") {
					$strcodelist.= "listd[".$iiii."]=clist;\n";
					$iiii++;
				}
			}
			$strcodelist.= "clist=null;\n\n";
		}
		mysql_free_result($result);
		$strcodelist.= "CodeInit();\n";
		$strcodelist.= "</script>\n";

		echo $strcodelist;

		echo $prlistscript;

		echo "<script>SearchCodeInit('".$codeA."','".$codeB."','".$codeC."','".$codeD."');</script>";
	?>
	<tr><td height="30"></td></tr>
</table>
<?
	} // ���ǵ� ���� ��
?>





<a href="?ordertype=">�Ϲ�</a>
/
<a href="?ordertype=pester">������</a>
/
<a href="?ordertype=present">�����ϱ�</a>


<table border="0" cellpadding="0" cellspacing="0" width="96%" class="itemListTbl">
	<caption style="text-align:left; display:none;">��ٱ��� ����Ʈ</caption>
	<colgroup>
		<col width="30">
		<col width="">
		<col width="40">
		<col width="90">
		<col width="70">
		<? if (strlen($_ShopInfo->getMemid())>0){ ?>
		<col width="140">
		<?}else{?>
		<col width="40">
		<?}?>
		<col width="70">
	</colgroup>
	<thead>
		<tr>
			<th class="thstyle">&nbsp;</th>
			<th class="thstyle">��ǰ��/�ɼ�</th>
			<th class="thstyle">����</th>
			<th class="thstyle">�ֹ��ݾ�</th>
			<th class="thstyle">��ۺ�</th>
			<th class="thstyle">����</th>
			<th class="thstyle">���</th>
		</tr>
	</thead>

	<tbody>
		<?
			$formcount = 0;
			$couponable = false;
			$reserveuseable = false;
			$productRealPrice = 0;
	//		_pr($basketItems);
			if($basketItems['productcnt'] <1){
		?>
		<tr>
			<td colspan="8" style="text-align:center; height:30px;">��ٱ��Ͽ� ��ϵ� ��ǰ�� �����ϴ�.</td>
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
								$venderDeliPrint .= "<b>������ ����</b>". ( $vendervalue['conf']['groupDeli'] > 1 ? "(ȸ�� ��� ��ۺ� ��å ����)" : "" );

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

		<form name="form_<?=$formcount?>" method=post action="<?=$Dir.FrontDir?>basket.php">
			<input type=hidden name="mode">
			<input type=hidden name="code" value="<?=$code?>">
			<input type=hidden name="productcode" value="<?=$product['productcode']?>">
			<input type=hidden name="orgquantity" value="<?=$product['quantity']?>">
			<input type=hidden name="orgoption1" value="<?=$product['opt1_idx']?>">
			<input type=hidden name="orgoption2" value="<?=$product['opt2_idx']?>">
			<input type=hidden name="opts" value="<?=$product['optidxs']?>">
			<input type=hidden name="brandcode" value="<?=$brandcode?>">
			<input type=hidden name="assemble_list" value="<?=$product['assemble_list']?>">
			<input type=hidden name="assemble_idx" value="<?=$product['assemble_idx']?>">
			<input type=hidden name="package_idx" value="<?=$product['package_idx']?>">
			<input type=hidden name="productname" value="<?=strip_tags($product['productname'])?>">
			<input type=hidden name="ordertype" value="<?=$ordertype?>">
		<tr>
			<td class="tdstyle2" style="text-align:center"><input type="checkbox" name="basket_select_item" value="<?=$product['basketidx']?>" ></td>
			<td class="tdstyle" style="text-align:center">
				<div style="float:left; width:55px;"><img src="<?=$product['tinyimage']['src']?>" <?=$imageSize?> /></div>
				<div style="float:left; margin-left:5px; text-align:left;">
					
					<a href="<?=$Dir.FrontDir?>productdetail.php?productcode=<?=$product['productcode']?>"><?=rentalIcon($product['rental'])?><font color="#000000" style="font-size:12px;"><b><?=viewproductname($product['productname'],$product['etctype'],$product['selfcode'],$product['addcode'])?></b></font></a>

					<?=($sellChk)?"":"<font color=\"#FF0000\">[�Ǹ�����]</font>"?>
					<a href="<?=$Dir.FrontDir?>productdetail.php?productcode=<?=$product['productcode']?>"><?//=viewproductname($productname,$product['etctype'],$product['selfcode'],$product['addcode']) ?><?=$bankonly_html?><?=$setquota_html?><? //=$deli_str ?></font></a><br />

					<!-- ��ǰ���� -->
					<img src="<?=$Dir?>images/common/won_icon1.gif" border="0" vspace="1" style="position:relative; top:0.2em;"> <font color="#666666"><?=number_format($product['sellprice'])?> ��</font>&nbsp;

					<? if ($_data->reserve_maxuse>=0) { ?>
						<img src="<?=$Dir?>images/common/basket/<?=$_data->design_basket?>/basket_skin3_icon003.gif" border="0" vspace="1" style="position:relative; top:0.2em;"> <font color="#666666"><? echo number_format($product['reserve']) ?> ��</font><br />
					<? } else { ?>
						<font color="#444444">����</font><br />
					<? } ?>

					<span style="font-size:11px;">
						<?
							// ���� ����
							if($product['bankonly'] == 'Y'){ ?><img src="<?=$Dir?>images/common/bankonly.gif" border=0 align=absmiddle><? }

							// ������
							if($product['setquota'] == 'Y'){ ?><img src="<?=$Dir?>images/common/setquota.gif" border=0 align=absmiddle><? }

							// ���� �� ���� ����
							$sptxt = array();
							if($product['cateAuth']['coupon'] == 'N') array_push($sptxt,'<IMG SRC=/images/common/basket/001/basket_spe_icon002x.gif hspace=1 alt=�������� ����Ұ� />');
							if($product['cateAuth']['reserve'] == 'N') array_push($sptxt,'<IMG SRC=/images/common/basket/001/basket_spe_icon001x.gif alt=������ ���Ұ� />');
							if($product['cateAuth']['gift'] == 'Y' && checkGiftSet()) array_push($sptxt,'<IMG SRC=/images/common/basket/001/basket_spe_icon004o.gif alt=����ǰ ����Ұ� />');
							if($product['cateAuth']['refund'] == 'N') array_push($sptxt,'<img src=/images/common/basket/001/basket_spe_icon003x.gif hspace=1 alt=��ȯ/��ǰ �Ұ� />');
							if(_array($sptxt)){
								echo implode(' ',$sptxt)."<br />";
							}
						?>
					</span>

					<span>
						<?
							//�ɼ� 1
							if (_array($product['option1'])) {

								echo "<img src=\"".$Dir."images/common/basket/".$_data->design_basket."/basket_skin3_icon002.gif\" border=\"0\" align=\"absmiddle\">";

								$tok = $product['option1'];
								$count=count($tok);
								echo "&nbsp; ".$tok[0]." ";
								echo "<select name=option1 size=1 onchange=\"CheckForm('upd',".$formcount.")\">\n";
								for($o1=1;$o1<$count;$o1++){
									if(strlen($tok[$o1])>0){
										$sel = ($o1==$product['opt1_idx']) ? " selected" : "";
										echo "<option value=\"".$o1."\" ".$sel.">".$tok[$o1]."</option>";
									}
								}
								echo "</select>";
							}

							// �ɼ� 2
							if (_array($product['option2'])) {
								$tok = $product['option2'];
								$count=count($tok);
								echo "&nbsp; ".$tok[0]." ";
								echo "<select name=option2 size=1 onchange=\"CheckForm('upd',".$formcount.")\">\n";
								for($o2=1;$o2<$count;$o2++){
									if(strlen($tok[$o2])>0){
										$sel = ($o2==$product['opt2_idx']) ? " selected" : "";
										echo "<option value=\"".$o2."\" ".$sel.">".$tok[$o2]."</option>";
									}
								}
								echo "</select>";
							}
						?>
					</span>
				</div>

				<!-- �뿩 ���� -->
				<?
				//if ( !empty($product['rentStartDate']) ) {
				if ( $product['rental'] == 2 ) {
					$rentOptionCHG = "<input type='button' value='�ɼǺ���' onclick=\"rentOptionCHG( ".$product['pridx'].", '".$ordertype."' );\">";
					?>
					<div>
						�뿩���� : <?= $product['rentStartDate'] ?> ~ <?= $product['rentEndDate'] ?>
						<?=$rentOptionCHG?>
					</div>
				<?
				}
				?>

			</td>
			<td class="tdstyle" align="center">

				<?
				if( $product['rental'] == 2 ) {
					foreach ( $product['rentPriceMsg'] as $k => $v ) {
						echo $v."<br>";
					}
					echo $rentOptionCHG;
				} else {
					if ($sellChk) {
						?>
						<table cellpadding="0" cellspacing="0">
							<tr>
								<td><input type=text name="quantity" value="<?= $product['quantity'] ?>" maxlength="4"
										   onkeyup="strnumkeyup(this)"
										   style="text-align:center; background-color:#f5f5f5; color:#999999; WIDTH:27px; height:17px; border:1px solid #ccc;">
								</td>
							</tr>
							<tr>
								<td><a href="javascript:CheckForm('upd',<?= $formcount ?>)"><IMG
											SRC="<?= $Dir ?>images/common/basket/<?= $_data->design_basket ?>/basket_skin3_btn2.gif"
											border="0" alt="����"></a></td>
							</tr>
						</table>
					<?
					} else {
						?>
						<input type=text name="quantity" value="<?= $product['quantity'] ?>" size="3" maxlength="4" readonly style="WIDTH:30px;BORDER:#DFDFDF 1px solid;HEIGHT:18px;BACKGROUND-COLOR:#F7F7F7;padding-top:2pt;padding-bottom:1pt;height:19px">
					<?
					}
				}
				?>
			</td>
			<td class="tdstyle" align="center"><b><?=number_format($product['realprice'])?>��</b></td>
			<?
				if( strlen($deliPrt) > 0 ) {
			?>
			<td class="tdstyle" align="center" <?=$deliPrtRowspan?>><?=$deliPrt?></td>
			<?
				}
			?>
			<td class="tdstyle">
			<?
				$mycoupons = $ablecoupons =array();
				if($_data->coupon_ok=="Y" && $product['cateAuth']['coupon'] == 'Y' && checkGroupUseCoupon( $_ShopInfo->getMemgroup() ) ){
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
			<td class="tdstyle2" align="center" style="font-size:0px;">
				<?
					if($sellChk){
						if (strlen($_ShopInfo->getMemid())>0 && $_ShopInfo->getMemid()!="deleted") {
							echo "<a href=\"javascript:go_wishlist('".($formcount)."');\"><IMG SRC=\"".$Dir."images/common/basket/".$_data->design_basket."/basket_skin3_btn3.gif\" border=\"0\" alt=\"���ϱ�\"></a><br />";
						} else {
							echo "<a href=\"javascript:check_login();\"><IMG SRC=\"".$Dir."images/common/basket/".$_data->design_basket."/basket_skin3_btn3.gif\" border=\"0\" alt=\"���ϱ�\"></a><br />";
						}
					}
				?>
				<a href="javascript:CheckForm('del',<?=$formcount?>)"><IMG SRC="<?=$Dir?>images/common/basket/<?=$_data->design_basket?>/basket_skin3_btn4.gif" border="0" vspace="3" alt="����"></a>
			</td>
		</tr>
		</form>
		<?
			}// end for
		?>
		<tr>
			<td colspan="7" bgcolor="#f9f9f9" style="padding:15px 10px; text-align:right;">
				<div style="font-size:11px; margin-bottom:5px;">
				<?=$venderDeliPrint?>
				</div>
				��ۺ� : <b><?=number_format($vendervalue['deliprice'])?></b>�� / <b>�հ� : </b><span style="color:#ff6600; font-size:15px; font-family:tahoma; font-weight:bold;"><?=number_format($vendervalue['sumprice'])?>��</span>
			</td>
		</tr>
		<tr><td colspan=7 height=1 bgcolor="#DDDDDD"></td></tr>
	<?
				} // end foreach
			} // end if
	?>
	</tbody>
	<tfoot>
		<tr><td colspan=7 height=2 bgcolor="#DDDDDD"></td></tr>
		<tr>
			<td colspan="7" bgcolor="#eeeeee" style="padding:10px 0px;">
				<div style="float:left;">
					<table border="0" cellpadding="0" cellspacing="0">
						<tr>
							<td style="padding-left:10px;"><img src="<?=$Dir?>images/common/basket/<?=$_data->design_basket?>/basket_skin3_icontitle.gif" alt="" /></td>
							<td style="padding-left:5px;"><img style="cursor:pointer;" src="<?=$Dir?>images/common/basket/<?=$_data->design_basket?>/basket_skin3_icon004.gif" onclick="javascript:CheckForm('del','sel')" alt="�����ϱ�" /></a></td>
						</tr>
					</table>
				</div>

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
</div>
<div style="height:6px;background:url('/data/design/img/main/bot_boxline.gif') no-repeat;font-size:0px;"></div>



<table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin-top:40px;">
	<tr>
		<td align="center">

			<?
				if(strlen($code)>0) {
					if($brandcode>0) {
						$shopping_url=$Dir.FrontDir."productblist.php?code=".substr($code,0,12)."&brandcode=".$brandcode;
					} else {
						$shopping_url=$Dir.FrontDir."productlist.php?code=".substr($code,0,12);
					}
				} else {
					$shopping_url=$Dir.MainDir."main.php";
				}
			?>
			<!--<A HREF="javascript:estimatePop();"><IMG SRC="<?=$Dir?>images/common/basket/<?=$_data->design_basket?>/basket_skin3_btn8.gif" border="0" hspace="2" alt="������ ����"></a>
			<A HREF="javascript:basket_clear()"><IMG SRC="<?=$Dir?>images/common/basket/<?=$_data->design_basket?>/basket_skin3_btn6.gif" border="0" hspace="2" alt="��ٱ��� ����"></a>-->
			<A HREF="#"><IMG SRC="<?=$Dir?>images/common/basket/<?=$_data->design_basket?>/basket_skin3_btn9.gif" border="0" hspace="2" alt="üũ �� ����"></a>
			<?
				if ($basketItems['sumprice']>=$_data->bank_miniprice) {
					if( $ordertype == "pester" ) {
						if (strlen($_ShopInfo->getMemid())>0 && $_ShopInfo->getMemid()!="deleted") {
							echo "<a href=\"javascript:chkPester();\"><img src=".$Dir."images/common/basket/".$_data->design_basket."/basket_skin3_icon005.gif border=\"0\" hspace=\"2\" alt=\"������\" /></a>";
						} else {
							echo "<a href=\"javascript:check_login();\"><img src=".$Dir."images/common/basket/".$_data->design_basket."/basket_skin3_icon005.gif border=\"0\" hspace=\"2\" alt=\"������\" /></a>";
						}
					}

					if( $ordertype == "present" ) {
						echo "<A HREF=\"#none\"><img src=\"".$Dir."images/common/basket/".$_data->design_basket."/basket_skin3_icon006.gif\" border=\"0\" onclick=\"chkPresent()\" hspace=\"2\" alt=\"�����ϱ�\" /></a>";
					}
				}

				if ($basketItems['sumprice']>=$_data->bank_miniprice ) {
					if( $ordertype == "" ) {
			?>
				<A HREF="<?=$Dir.FrontDir?>login.php?chUrl=<?=urlencode($Dir.FrontDir."order.php")?>"><IMG SRC="<?=$Dir?>images/common/basket/<?=$_data->design_basket?>/basket_skin3_btn7.gif" border="0" hspace="2" alt="��ǰ�ֹ��ϱ�"></a>
			<?
					}
				} else {
			?>
				<br><font color="#FF3300"><b>�ֹ������� �ּ� �ݾ��� <?=number_format($_data->bank_miniprice)?>�� �Դϴ�.</b></font>
			<?
				}
			?>
			<A HREF="<?=$shopping_url?>"><IMG SRC="<?=$Dir?>images/common/basket/<?=$_data->design_basket?>/basket_skin3_btn5.gif" border="0" hspace="2" alt="���ΰ���ϱ�"></a>
		</td>
	</tr>
</table>