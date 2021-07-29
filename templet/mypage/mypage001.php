<div class="mypageMainWrap">
<!-- 주문현황 -->
<? include $Dir.FrontDir."mypage_top.php"; ?>

<div class="sectionTitleWrap">
	<div class="sectionTitle">최근 예약/주문/배송/렌탈 내역</div>
	<div style="float:right;"><A HREF="<?=$Dir.FrontDir?>mypage_orderlist.php">더보기 +</div>
</div>


<table cellpadding="0" cellspacing="0" width="100%" class="mypageMainCon">
	<!--
	<tr>
		<td>
			<table cellpadding="0" cellspacing="0" width="100%" border="0">
				<tr>
					<td><a href="#orderList_box" onclick="getOrderList(1);return false;"><img src="<?=$Dir?>images/common/mypage/<?=$_data->design_mypage?>/order_01.gif" align="absmiddle" id="orderList_type1" alt="일반상품주문"></a></td>
					<td><a href="#orderList_box" onclick="getOrderList(2);return false;"><img src="<?=$Dir?>images/common/mypage/<?=$_data->design_mypage?>/order_02.gif" align="absmiddle" id="orderList_type2" alt="공동구매주문"></a></td>
					<td><a href="#orderList_box" onclick="getOrderList(3);return false;"><img src="<?=$Dir?>images/common/mypage/<?=$_data->design_mypage?>/order_03.gif" align="absmiddle" id="orderList_type3" alt="상품권주문"></a></td>
					<td width="100%" align="right"><A HREF="<?=$Dir.FrontDir?>mypage_orderlist.php"><IMG SRC="<?=$Dir?>images/common/mypage/<?=$_data->design_mypage?>/mypage_skin3_btn01.gif" BORDER="0" alt="전체보기"></A></td>
				</tr>
			</table>
		</td>
	</tr>
	-->
	<tr>
		<td>
<!-- 일반상품 주문(최근 주문내역) START -->
		<table cellpadding="0" cellspacing="0" width="100%" border="0" class="orderlistTbl" id="list01">
			<colgroup>
				<col width="160"></col>
				<col width="*"></col>
				<col width="130"></col>
				<col width="100"></col>
				<col width="120"></col>
				<col width="120"></col>
				<col width="100"></col>
			</colgroup>
			<tr>
				<th>일자/주문정보</th>
				<th>상품명/요약설명</th>
				<th>예약일정</th>
				<th style="text-align:center;width:90px;">예약상태</th>
				<th>가격(수량)</th>
				<th>판매자</th>
				<th>상태</th>
			</tr>
<?
		$delicomlist=getDeliCompany();
		$orderlists = getMyOrderList(5);
		$returnableCnt = 0;
		if($orderlists['total'] < 1){ ?>
			<tr><td colspan="6" style="text-align:center;padding:30px;">최근 1개월 이내에 구매하신 내역이 없습니다.</td></tr>		
<?		}else{
			$idx=0;
			foreach($orderlists['orders'] as $row){
				$orderproducts = array();
				$orderproducts = getOrderProduct($row->ordercode);
				$rowspan = count($orderproducts);
		?>

			<tr>
				<td rowspan="<?=$rowspan?>">
					<div style="margin:10px 0px 10px 10px;">
						<b><?=substr($row->ordercode,0,4)?>/<?=substr($row->ordercode,4,2)?>/<?=substr($row->ordercode,6,2)?></b><br />
						결제방법 : <?=getPaymethodStr($row->paymethod)?><br />
						결제금액 : <b><font color="#000000"><?=number_format($row->price)?></font></b>원<br />
						<a HREF="javascript:OrderDetailPop('<?=$row->ordercode?>')" onmouseover="window.status='주문내역조회';return true;" onmouseout="window.status='';return true;"><img src="<?=$Dir?>images/common/mypage/<?=$_data->design_mypage?>/mypage_order_icon01.gif" alt="주문 상세정보" /></a>
					</div>
				</td>
	<?			$isfirst = true;
				foreach($orderproducts as $jj=>$row2){
					if(!$isfirst) echo '<tr>';
					else $isfirst = false;
					
					$prentinfo['codeinfo'] = venderRentInfo($row2->vender,$row2->pridx,$row2->productcode);

					// 판매자 정보
					if($row2->istrust=="0"){
						$v_sql = "SELECT com_name, com_tel FROM tblvenderinfo WHERE vender=".$row2->vender;
					}else{
						$v_sql = "SELECT com_name, com_tel FROM tblvenderinfo WHERE vender=".$row2->trust_vender;
					}
					$v_res = mysql_query($v_sql, get_db_conn());
					list($com_name,$com_tel) = mysql_fetch_row($v_res);
					// 판매자 정보
					
					$optvalue = "";
					if (ereg("^(\[OPTG)([0-9]{3})(\])$", $row2->opt1_name)) {
						$optioncode = $row2->opt1_name;
						$row2->opt1_name = "";
						$sql = "SELECT opt_name FROM tblorderoption WHERE ordercode='" . $row->ordercode . "' AND productcode='" . $row2->productcode . "' AND opt_idx='" . $optioncode . "' limit 1 ";
						$res = mysql_query($sql, get_db_conn());
						if ($res && mysql_num_rows($res)) {
							$optvalue = mysql_result($res, 0, 0);
						}
						mysql_free_result($res);
					}
	?>
				<td style="padding:10px;" class="mypage_list_cont">
					<div style="width:25px;float:left;text-align:left;">
						<?
						//if($row->deli_gbn != "C" && !($row->pay_admin_proc == "C" && $row->pay_flag == "0000") && count($orderproducts) > 1 && $row2->status == ''){
						if(false && $row->deli_gbn != "C" && !($row->pay_admin_proc == "C" && $row->pay_flag == "0000") && count($orderproducts) > 1 ){
							?>
							<input type="checkbox" name="chk_<?= $row->ordercode ?>"
								   id="chk_<?= $row->ordercode ?>_<?= $jj ?>" value="<?= $row2->productcode ?>"/>
							<input type="hidden" name="chk_uid_<?= $row->ordercode ?>"
								   id="chk_uid_<?= $row->ordercode ?>_<?= $jj ?>" value="<?= $row2->uid ?>"/>
							<?
							$chkbox_count++;
						}
						?>
					</div>
					<div>					
					<A HREF="javascript:OrderDetailProduct('<?= $row->ordercode ?>','<?= $row2->productcode ?>')" onmouseover="window.status='상품정보조회';return true;" onmouseout="window.status='';return true;">
						<img src="<?=$Dir.((strlen($row2->tinyimage)>0 && file_exists($Dir.DataDir."shopimages/product/".$row2->tinyimage))?DataDir.'shopimages/product/'.urlencode($row2->tinyimage):"images/no_img.gif")?>" border="0" width="50" style="float:left;margin-right:5px;"/><?= $reservation ?><?= $row2->productname ?></a>
					<?
					if (!_empty($optvalue)) echo "<br><img src=\"" . $Dir . "images/common/icn_option.gif\" border=0 align=absmiddle> " . $optvalue . "";
					if(!_empty($row2->start)){ // 렌탈
						echo '<br>'.$row2->opt1_name;
						if($prentinfo['codeinfo']['pricetype']=="long") echo "개월";
					}else{
						if(!_empty($row2->opt1_name)){
							echo '<br>'.$row2->opt1_name;
							if(!_empty($row2->opt2_name)) echo ' / '.$row2->opt2_name;
						}
					}	?>
					<br />
				<? if($row2->deli_gbn=="Y" && $row2->status==""  && $_data->review_type !="N")  { ?><A HREF="javascript:OrderReview('<?=$row2->productcode?>')"><img src="<?=$Dir?>images/common/mypage/<?=$_data->design_mypage?>/mypage_order_icon04.gif" alt="상품평작성" /></a><? }else{ ?><img src="<?=$Dir?>images/common/mypage/<?=$_data->design_mypage?>/mypage_order_icon04_off.gif" alt="상품평작성" /><? } ?>
				</td>
				
				<td style="text-align:center">
					<?
					if(!_empty($row2->start)){
						if($prentinfo['codeinfo']['pricetype']=="long"){
							echo $row2->opt2_name;
						}else{
							$diff = datediff_rent($row2->end,$row2->start);
							if(substr($row2->start,11,2) == '00' && $diff['hour'] < 1){
								echo substr($row2->start,0,10).' ~ <br>'.date('Y-m-d',strtotime($row2->end)+1);
							}else{
								echo substr($row2->start,0,-6).' ~ <br>'.date('Y-m-d H',strtotime($row2->end)+1);
							}
								echo '<br>('.(($diff['day'] >0)?$diff['day'].'일':'').(($diff['hour'] >0)?$diff['hour'].'시간':'').')';
						}
					}else echo 'Buying';
					?>
				</td>
				<td style="text-align:center">
				<?php
				switch($row2->prd_status) {
					case 'Y': echo "예약확정"; break;
					case 'D': echo "예약지연"; break;
					case 'F': echo "예약불가(상품불량)"; break;
					case 'W': echo "예약불가(재고부족)"; break;
					case 'I': echo "예약불가(상품정보상이)"; break;
					case 'N': echo "예약 대기중"; break;
					default: echo "예약 대기중";
				}
				?>
				</td>
				<td style=" text-align:center"><? echo number_format($row2->quantity*$row2->price).'원<br>('.number_format($row2->quantity).')'; ?></td>
				<td style="text-align:center"><?php echo $com_name."<br/>".$com_tel;?></td>
				<td style="text-align:center">
					<? if(_empty($row2->start)){ // 구매 상품 					
							$deli_link = '-';
							$deli_url= $trans_num=$company_name="";
							if($row2->deli_gbn=="Y" AND strlen($row2->deli_num) > 0 ) {
								$deli_link = '';
								if($row2->deli_com>0 && $delicomlist[$row2->deli_com]) {
									$deli_url=$delicomlist[$row2->deli_com]->deli_url;
									$trans_num=$delicomlist[$row2->deli_com]->trans_num;
									$company_name=$delicomlist[$row2->deli_com]->company_name;
									$deli_link .= $company_name."<br>".$row2->deli_num."<br>";
									if(strlen($row2->deli_num)>0 && strlen($deli_url)>0) {
										if(strlen($trans_num)>0) {
											$arrtransnum=explode(",",$trans_num);
											$pattern=array("(\[1\])","(\[2\])","(\[3\])","(\[4\])");
											$replace=array(substr($row2->deli_num,0,$arrtransnum[0]),substr($row2->deli_num,$arrtransnum[0],$arrtransnum[1]),substr($row2->deli_num,$arrtransnum[0]+$arrtransnum[1],$arrtransnum[2]),substr($row2->deli_num,$arrtransnum[0]+$arrtransnum[1]+$arrtransnum[2],$arrtransnum[3]));
											$deli_url=preg_replace($pattern,$replace,$deli_url);
										} else {
											$deli_url.=$row2->deli_num;
										}
										$deli_link .='<A HREF="javascript:DeliSearch(\''.$deli_url.'\')"><img src="'.$Dir.'images/common/btn_mypagedeliview.gif" border="0"></A>';
									}
								}else{
									$deli_link = "";
								}
							}else{
								$deli_link = "";
							}
							echo '<font color="#000000" class="nodispDiv">'.orderProductDeliStatusStr($row2,$row, $cnt).'</font><br>'.$deli_link;
					}else{ // 렌탈 상품
						
						if($row2->deli_gbn=="D" || $row2->deli_gbn=="W"){
							$onclick = "onclick=\"javascript:OrderCancelPop('".$row->ordercode."')\" style='cursor:pointer'";
						}else{
							$onclick = "";
						}

						$deli_link = '-';
						$deli_url= $trans_num=$company_name="";
						if($row2->deli_gbn=="Y" AND strlen($row2->deli_num) > 0 ) {
							$deli_link = '';
							if($row2->deli_com>0 && $delicomlist[$row2->deli_com]) {
								$deli_url=$delicomlist[$row2->deli_com]->deli_url;
								$trans_num=$delicomlist[$row2->deli_com]->trans_num;
								$company_name=$delicomlist[$row2->deli_com]->company_name;
								$deli_link .= $company_name."<br>".$row2->deli_num."<br>";
								if(strlen($row2->deli_num)>0 && strlen($deli_url)>0) {
									if(strlen($trans_num)>0) {
										$arrtransnum=explode(",",$trans_num);
										$pattern=array("(\[1\])","(\[2\])","(\[3\])","(\[4\])");
										$replace=array(substr($row2->deli_num,0,$arrtransnum[0]),substr($row2->deli_num,$arrtransnum[0],$arrtransnum[1]),substr($row2->deli_num,$arrtransnum[0]+$arrtransnum[1],$arrtransnum[2]),substr($row2->deli_num,$arrtransnum[0]+$arrtransnum[1]+$arrtransnum[2],$arrtransnum[3]));
										$deli_url=preg_replace($pattern,$replace,$deli_url);
									} else {
										$deli_url.=$row2->deli_num;
									}
									$deli_link .='<A HREF="javascript:DeliSearch(\''.$deli_url.'\')"><img src="'.$Dir.'images/common/btn_mypagedeliview.gif" border="0"></A>';
								}
							}else{
								$deli_link = "";
							}
						}else{
							$deli_link = "";
						}

						echo '<font color="#000000" class="nodispDiv" '.$onclick.'>'.orderProductDeliStatusStr($row2,$row, $cnt).'</font><br>'.$deli_link.'<br>';

						switch($row2->rentstatus){
							case 'BC': echo '미입금 취소'; break;
							case 'CC': echo '예약 취소'; break;
							case 'BR':
								$reqgap = time() - strtotime($row2->regDate);
								//if($reqgap > 12*60*60) echo '예약 취소';
								echo 12 - floor($reqgap/(60*60)).' 시간후<br> 예약취소';
								break;
							case 'BO': echo '예약 확정'; break;
							case 'BI':
								$diff = datediff_rent($row2->end);
								if(_isInt($diff['day'])) $diff['hour']+=$diff['day']*24;
								echo '렌탈중<br> '.$diff['hour'].'시간남음';
								break;
							case 'BE': echo '렌탈종료'; break;
							case 'CR': echo '반납대기'; break;
							case 'OT': 
								$ovtime = time() - strtotime($row2->end);
								echo '미반납<br/>('.floor($ovtime/(60*60)).'시간)';
								break;
							case 'CE': echo '반납완료'; break;
							case 'NR': echo '반납불가'; break;
						}
					}?>
				</td>
			</tr>
		<? } // end foreach product?>
<?
		} // end foreach order 
	} // end if
?>
		</table>
<!-- 일반상품 주문(최근 주문내역) END -->

<!-- 공동구매 주문(최근 주문내역) START -->
		<table cellpadding="0" cellspacing="0" width="100%" border="0" bgcolor="E7E7E7" style="table-layout:fixed;display:none;" id="list02">
		<!-- 주문일자, 주문 상품명, 배송상태, 배송추적, 결제방법, 결제금액, 상세정보  -->
		<col width="180"></col>
		<col></col>
		<col width="80"></col>
		<col width="80"></col>
		<col width="80"></col>
		<tr>
			<td height="2" colspan="5" bgcolor="#666666"></td>
		</tr>
		<tr height="30" align="center" bgcolor="#F8F8F8" style="letter-spacing:-0.5pt;">
			<td align="left" style="padding-left:15px;" class="mypage_list_title">주문일(결제정보)</td>
			<td class="mypage_list_title">상품명/옵션</td>
			<td class="mypage_list_title">주문상태</td>
			<td class="mypage_list_title">배송추적</td>
			<td class="mypage_list_title">상품평</td>
		</tr>
		<tr>
			<td height="1" colspan="5" bgcolor="#ededed"></td>
		</tr>
<?
		$orderlists = getMyOrderList(2,'2');
		$returnableCnt = 0;

		if($orderlists['total'] < 1){ ?>
		<tr height=40><td colspan=5 align=center bgcolor=#FFFFFF  style="padding:30px;">최근 1개월 이내에 구매하신 내역이 없습니다.</td></tr>
		<tr><td height=1 colspan=5 bgcolor=#999999></td></tr>
<?		}else{
			foreach($orderlists['orders'] as $row){
				$orderproducts = array();
				$orderproducts = getOrderProduct($row->ordercode);
		?>

		<tr bgcolor=#FFFFFF onmouseover="this.style.background='#ffffff'" onmouseout="this.style.background='#FFFFFF'" style="padding-bottom:8px;">
			<td class="mypage_order_line" valign="top" style="padding-top:10; padding-bottom:10;">
				<table border="0" cellpadding="0" cellspacing="0" width="100%">
					<tr><td height="30" class="mypage_order_line2"><b><?=substr($row->ordercode,0,4)?>/<?=substr($row->ordercode,4,2)?>/<?=substr($row->ordercode,6,2)?></b></td></tr>
					<tr><td height=5></td></tr>
					<tr><td class="mypage_list_cont">결제방법 : <?=getPaymethodStr($row->paymethod)?></td></tr>
					<tr><td class="mypage_list_cont">결제금액 : <font color="#000000"><b><?=number_format($row->price)?></b></font>원</td></tr>
					<tr><td height=8></td></tr>
				</table>
			</td>
			<td colspan=5>
				<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
				<col width=></col>
				<col width=70></col>
				<col width=80></col>
				<col width=70></col>
				<col width=70></col>
	<?
				$cnt = count($orderproducts);
				for($jj=0;$jj < $cnt;$jj++){
					$row2 = $orderproducts[$jj];
					if($jj>0) echo '<tr><td colspan=4 height=1 bgcolor=#F5F5F5></tr>';

					if($row2->deli_gbn=="D" || $row2->deli_gbn=="W"){
						$onclick = "onclick=\"javascript:OrderCancelPop('".$row->ordercode."')\" style='cursor:pointer'";
					}else{
						$onclick = "";
					}
	?>


					<tr>
						<td style="padding:10px; ine-height:11pt"><A HREF="javascript:OrderDetailPop('<?=$row->ordercode?>')" onmouseover="window.status='주문내역조회';return true;" onmouseout="window.status='';return true;"><?=$row2->productname?></a></td>
						<td align=center class="mypage_list_cont2"><font color="#000000" <?=$onclick?>><? echo orderProductDeliStatusStr($row2,$row, $cnt); ?></font></td>
						<td align=center style="font-size:8pt;padding-top:3">
						<?
						$deli_link = '-';
						$deli_url="";
						$trans_num="";
						$company_name="";
						if($row2->deli_gbn=="Y" AND strlen($row2->deli_num) > 0 ) {
							if($row2->deli_com>0 && $delicomlist[$row2->deli_com]) {
								$deli_url=$delicomlist[$row2->deli_com]->deli_url;
								$trans_num=$delicomlist[$row2->deli_com]->trans_num;
								$company_name=$delicomlist[$row2->deli_com]->company_name;
								$deli_link .= $company_name."<br>".$row2->deli_num."<br>";
								if(strlen($row2->deli_num)>0 && strlen($deli_url)>0) {
									if(strlen($trans_num)>0) {
										$arrtransnum=explode(",",$trans_num);
										$pattern=array("(\[1\])","(\[2\])","(\[3\])","(\[4\])");
										$replace=array(substr($row2->deli_num,0,$arrtransnum[0]),substr($row2->deli_num,$arrtransnum[0],$arrtransnum[1]),substr($row2->deli_num,$arrtransnum[0]+$arrtransnum[1],$arrtransnum[2]),substr($row2->deli_num,$arrtransnum[0]+$arrtransnum[1]+$arrtransnum[2],$arrtransnum[3]));
										$deli_url=preg_replace($pattern,$replace,$deli_url);
									} else {
										$deli_url.=$row2->deli_num;
									}
									$deli_link .='<A HREF="javascript:DeliSearch(\''.$deli_url.'\')"><img src="'.$Dir.'images/common/btn_mypagedeliview.gif" border="0"></A>';
								}
							}else{
								$deli_link = '';
							}
						}else{
							$deli_link = '';
						}
						echo $deli_link;
						?>
						</td>
						<td align="center"><? if($row2->deli_gbn=="Y" && $_data->review_type !="N")  { ?><!-- <A HREF="javascript:OrderReview('<?=$row->ordercode?>','<?=$row2->productcode?>')" onmouseover="window.status='상품평';return true;" onmouseout="window.status='';return true;"> -->
						<A HREF="javascript:OrderReview('<?=$row2->productcode?>')"><img src="<?=$Dir?>images/common/mypage/<?=$_data->design_mypage?>/mypage_order_icon04.gif" alt="상품평작성" /></a><? }else{ ?><img src="<?=$Dir?>images/common/mypage/<?=$_data->design_mypage?>/mypage_order_icon04_off.gif" alt="상품평작성" /><? } ?></td>
					</tr>
			<?	} // end for $jj ?>
				</table>
			</td>
		</tr>
		<tr><td colspan=5 height=1 bgcolor=#999999></td></tr>
		<? 	} // end foreach
		if($returnableCnt > 0){ ?>
		<tr><td colspan=5 height=1 bgcolor=#E7E7E7></td></tr>
		<? } ?>
	<? } // end if ?>
		</table>
<!-- 공동구매 주문(최근 주문내역) END -->


<!-- 상품권 주문(최근 주문내역) START -->
		<table cellpadding="0" cellspacing="0" width="100%" border="0" bgcolor="E7E7E7" style="table-layout:fixed;display:none;" id="list03">
		<!-- 주문일자, 주문 상품명, 배송상태, 배송추적, 결제방법, 결제금액, 상세정보  -->
		<col width="180"></col>
		<col></col>
		<col width="80"></col>
		<col width="80"></col>
		<col width="80"></col>
		<tr>
			<td height="2" colspan="5" bgcolor="#666666"></td>
		</tr>
		<tr height="30" align="center" bgcolor="#F8F8F8" style="letter-spacing:-0.5pt;">
			<td align="left" style="padding-left:15px;" class="mypage_list_title">주문일(결제정보)</td>
			<td class="mypage_list_title">상품명/옵션</td>
			<td class="mypage_list_title">처리상태</td>
			<td class="mypage_list_title">인증번호</td>
			<td class="mypage_list_title">상품평</td>
		</tr>
		<tr>
			<td height="1" colspan="5" bgcolor="#DDDDDD"></td>
		</tr>
<?

		$curdate=date("Ymd",mktime(0,0,0,(int)date("m")-1,(int)date("d"),date("Y")));
		$sql = "SELECT ordercode, price, paymethod, pay_admin_proc, pay_flag, bank_date, deli_gbn, gift ";
		$sql.= "FROM tblorderinfo WHERE id='".$_ShopInfo->getMemid()."' ";
		$sql.= "AND ordercode >= '".$curdate."' AND (del_gbn='N' OR del_gbn='A') AND gift in('1','2') ";
		$sql.= "ORDER BY ordercode DESC LIMIT 5 ";
		$result=mysql_query($sql,get_db_conn());
		$cnt=0;
		while($row=mysql_fetch_object($result)) {
			echo "<tr bgcolor=#FFFFFF onmouseover=\"this.style.background='#ffffff'\" onmouseout=\"this.style.background='#FFFFFF'\">\n";
			echo "<td class=mypage_order_line valign=top style=padding-top:10px; padding-bottom:10px;>";
			echo "<table border=0 cellpadding=0 cellspacing=0 width=100%>";
			echo "<tr><td height=30 class=mypage_order_line2><b>".substr($row->ordercode,0,4)."/".substr($row->ordercode,4,2)."/".substr($row->ordercode,6,2)."</b></td></tr>\n";
			echo "<tr><td height=5></td></tr>";
			echo "<tr><td class=mypage_list_cont>결제방법 : ";

			if (preg_match("/^(B){1}/",$row->paymethod)) echo "무통장입금";
			else if (preg_match("/^(V){1}/",$row->paymethod)) echo "실시간계좌이체";
			else if (preg_match("/^(O){1}/",$row->paymethod)) echo "가상계좌";
			else if (preg_match("/^(Q){1}/",$row->paymethod)) echo "가상계좌-<FONT COLOR=\"red\">매매보호</FONT>";
			else if (preg_match("/^(C){1}/",$row->paymethod)) echo "신용카드";
			else if (preg_match("/^(P){1}/",$row->paymethod)) echo "신용카드-<FONT COLOR=\"red\">매매보호</FONT>";
			else if (preg_match("/^(M){1}/",$row->paymethod)) echo "휴대폰";
			else echo "";

			echo "</td></tr>";
			echo "<tr><td class=mypage_list_cont>결제금액 : <font color=#000000><b>".number_format($row->price)."</b></font>원</td></tr>";
			echo "<tr><td height=5></td></tr>";
			echo "<tr><td class=mypage_list_cont><A HREF=\"javascript:OrderDetailPop('".$row->ordercode."')\" onmouseover=\"window.status='주문내역조회';return true;\" onmouseout=\"window.status='';return true;\"><img src=".$Dir."images/common/mypage/".$_data->design_mypage."/mypage_order_icon01.gif alt=주문상세정보></a></td></tr>";
			echo "</table></td>\n";

			echo "	<td colspan=4>\n";
			echo "	<table border=0 cellpadding=0 cellspacing=0 width=100% style=\"table-layout:fixed\">\n";
			echo "	<col width=></col>\n";
			echo "	<col width=80></col>\n";
			echo "	<col width=80></col>\n";
			echo "	<col width=80></col>\n";
			$sql = "SELECT * FROM tblorderproduct WHERE ordercode='".$row->ordercode."' ";
			$sql.= "AND NOT (productcode LIKE 'COU%' OR productcode LIKE '999999%') ";
			$result2=mysql_query($sql,get_db_conn());
			$jj=0;
			while($row2=mysql_fetch_object($result2)) {
				if($jj>0) echo "";
				echo "<tr>\n";
				echo "	<td style=padding:10px; ine-height:11pt;><A HREF=\"javascript:OrderDetailPop('".$row->ordercode."')\" onmouseover=\"window.status='주문내역조회';return true;\" onmouseout=\"window.status='';return true;\">".$row2->productname."</a></td>";
				echo "	<td align=center class=mypage_list_cont2>";
				if ($row2->deli_gbn=="C") echo "주문취소";
				else if ($row2->deli_gbn=="D") echo "취소요청";
				else if ($row2->deli_gbn=="E") echo "환불대기";
				else if ($row2->deli_gbn=="X") {
					if($row->gift=='1') {
						$sql3 = "SELECT * FROM tblgift_info WHERE ordercode='{$row->ordercode}'";
						$result3=mysql_query($sql3,get_db_conn());
						$row3 = mysql_fetch_array($result3);
						mysql_free_result($result3);
						echo "인증번호발송";
					}
					else "발송준비";
				}
				else if ($row2->deli_gbn=="Y") {
					if($row->gift=='1') {
						$sql3 = "SELECT * FROM tblgift_info WHERE ordercode='{$row->ordercode}'";
						$result3=mysql_query($sql3,get_db_conn());
						$row3 = mysql_fetch_array($result3);
						mysql_free_result($result3);
						echo "인증후적립완료";
					}
					else if($row->gift=='2') echo "적립완료";
					else echo "발송완료";
				}
				else if ($row2->deli_gbn=="N") {
					if (strlen($row->bank_date)<12 && preg_match("/^(B|O|Q){1}/", $row->paymethod)) echo "입금확인중";
					else if ($row->pay_admin_proc=="C" && $row->pay_flag=="0000") echo "결제취소";
					else if (strlen($row->bank_date)>=12 || $row->pay_flag=="0000") echo "발송준비";
					else echo "결제확인중";
				} else if ($row2->deli_gbn=="S") {
					echo "발송준비";
				} else if ($row2->deli_gbn=="R") {
					echo "반송처리";
				} else if ($row2->deli_gbn=="H") {
					echo "발송완료 [정산보류]";
				}
				echo "	</td>\n";
				echo "	<td align=center style=\"font-size:11px; padding-top:3\">";
				$deli_url="";
				$trans_num="";
				$company_name="";
				if($row2->deli_gbn=="Y" AND strlen($row2->deli_num) > 0 ) {
					if($row2->deli_com>0 && $delicomlist[$row2->deli_com]) {
						$deli_url=$delicomlist[$row2->deli_com]->deli_url;
						$trans_num=$delicomlist[$row2->deli_com]->trans_num;
						$company_name=$delicomlist[$row2->deli_com]->company_name;
						echo $company_name."<br>".$row2->deli_num."<br>";
						if(strlen($row2->deli_num)>0 && strlen($deli_url)>0) {
							if(strlen($trans_num)>0) {
								$arrtransnum=explode(",",$trans_num);
								$pattern=array("(\[1\])","(\[2\])","(\[3\])","(\[4\])");
								$replace=array(substr($row2->deli_num,0,$arrtransnum[0]),substr($row2->deli_num,$arrtransnum[0],$arrtransnum[1]),substr($row2->deli_num,$arrtransnum[0]+$arrtransnum[1],$arrtransnum[2]),substr($row2->deli_num,$arrtransnum[0]+$arrtransnum[1]+$arrtransnum[2],$arrtransnum[3]));
								$deli_url=preg_replace($pattern,$replace,$deli_url);
							} else {
								$deli_url.=$row2->deli_num;
							}
							echo "<A HREF=\"javascript:DeliSearch('".$deli_url."')\"><img src=".$Dir."images/common/btn_mypagedeliview.gif border=0></A>";
						}
					} else {
						if($row3['authcode1']) {
							echo "{$row3['authcode1']} - {$row3['authcode1']}";
						}
						else echo "-";
					}
				} else {
					if($row3['authcode1']) {
						echo "{$row3['authcode1']} - {$row3['authcode1']}";
					}
					else echo "-";
				}
				echo "	</td>\n";
				//echo "	<td align=center><A HREF=\"javascript:OrderDetailPop('".$row->ordercode."')\" onmouseover=\"window.status='주문내역조회';return true;\" onmouseout=\"window.status='';return true;\"><img src=".$Dir."images/common/mypage/".$_data->design_mypage."/mypage_order_icon01.gif></a></td>";
?>
							<td align="center"><? if($row2->deli_gbn=="Y" && $_data->review_type !="N")  { ?><!-- <A HREF="javascript:OrderReview('<?=$row->ordercode?>','<?=$row2->productcode?>')" onmouseover="window.status='상품평';return true;" onmouseout="window.status='';return true;"> -->
							<A HREF="javascript:OrderReview('<?=$row2->productcode?>')"><img src="<?=$Dir?>images/common/mypage/<?=$_data->design_mypage?>/mypage_order_icon04.gif" alt="상품평작성" /></a><? }else{ ?><img src="<?=$Dir?>images/common/mypage/<?=$_data->design_mypage?>/mypage_order_icon04_off.gif" alt="상품평작성" /><? } ?></td>
<?
				echo "</tr>\n";
				$jj++;
			}
			mysql_free_result($result2);
			echo "	</table>\n";
			echo "	</td>\n";
			echo "</tr>\n";
			$cnt++;
		}
		mysql_free_result($result);

		if ($cnt==0) {
			echo "<tr height=40><td colspan=5 align=center bgcolor=#FFFFFF  style=\"padding:30px;\">최근 1개월 이내에 구매하신 내역이 없습니다.</td></tr>";
		}
?>
		</table>
<!-- 상품권 주문(최근 주문내역) END -->


		<script type="text/javascript">
			<!--
			preOrderTab = 1;
			function getOrderList(type){
				$j("#orderList_type"+preOrderTab).attr('src',$j("#orderList_type"+preOrderTab).attr('src').replace('_on.gif', '.gif'));
				$j("#orderList_type"+type).attr('src',$j("#orderList_type"+type).attr('src').replace('.gif','_on.gif'));
				$j("#list0"+preOrderTab).hide();//.css("display","none");
				$j("#list0"+type).show();//.attr("display","block");
				preOrderTab = type;
			}
			getOrderList(preOrderTab);
			//-->
		</script>
		</td>
	</tr>



<? /*

	<tr>
		<td>
			<div style="float:left;padding-left:10px;font-size:16px;letter-spacing:-1px;">최근 취소/반품/교환 내역</div>
			<div style="float:right;"><A HREF="<?=$Dir.FrontDir?>mypage_orderlist.php">더보기 +</div>
		</td>
	</tr>
	<tr><td height="7"></td></tr>
	<tr>
		<td style="padding:20px;text-align:center;background:url('/data/design/img/sub/bg_boxline2.gif') repeat-y;">
			<!-- 취소/반품/교환 내역 -->
			취소/반품/교환 내역
		</td>
	</tr>



*/ ?>

	<?
	/* // 160105 마이페이지 장바구니 표시 안함.
	// _basketCount() => global.func.php
	$basketcount = _basketCount('tblbasket_normal',$_ShopInfo->getTempkey()); ?>
	<tr>
		<td>
			<div style="float:left;padding-left:10px;font-size:16px;letter-spacing:-1px;">장바구니 (<?=number_format($basketcount)?>)</div>
			<div style="float:right;"><A HREF="<?=$Dir.FrontDir?>basket.php">더보기 +</div>
		</td>
	</tr>
	<tr><td height="7"></td></tr>
	<tr>
		<td style="padding:20px;text-align:center;background:url('/data/design/img/sub/bg_boxline2.gif') repeat-y;">
			<!-- 취소/반품/교환 내역 -->
<?
	if($basketcount>0){
		$basketItems = getBasketByArray('tblbasket_normal');
		if($basketItems['productcnt'] > 1){
			
		}
	}
?>		
		</td>
	</tr>


	*/
	?>


<?
	if($_data->personal_ok=="Y") {	//1:1고객게시판을 사용중이라면,,,,,
?>

	<tr>
		<td>

			<!-- 최근문의(1:1) 내역 -->
			<div class="sectionTitleWrap">
				<div class="sectionTitle">1:1 문의 내역</div>
				<div style="float:right;"><A HREF="<?=$Dir.FrontDir?>mypage_personal.php">더보기 +</A></div>
			</div>

			<table cellpadding="0" cellspacing="0" width="100%" class="orderlistTbl">
				<col width="140"></col>
				<col></col>
				<col width="65"></col>
				<col width="105"></col>
				<tr>
					<th>문의일자</th>
					<th>제목</th>
					<th>답변여부</th>
					<th>답변일자</th>
				</tr>
<?
		$sql = "SELECT idx,subject,date,re_date FROM tblpersonal ";
		$sql.= "WHERE id='".$_ShopInfo->getMemid()."' ";
		$sql.= "ORDER BY idx DESC LIMIT 5 ";
		$result = mysql_query($sql,get_db_conn());
		$cnt=0;
		while($row=mysql_fetch_object($result)) {
			$date = substr($row->date,0,4)."/".substr($row->date,4,2)."/".substr($row->date,6,2)."(".substr($row->date,8,2).":".substr($row->date,10,2).")";
			$re_date="-";
			if(strlen($row->re_date)==14) {
				$re_date = substr($row->re_date,0,4)."/".substr($row->re_date,4,2)."/".substr($row->re_date,6,2)."(".substr($row->re_date,8,2).":".substr($row->re_date,10,2).")";
			}
			if($cnt>0) echo "\n";

			echo "<tr>\n";
			echo "	<td>".$date."</td>\n";
			echo "	<td align=\"left\"><A HREF=\"javascript:ViewPersonal('".$row->idx."')\">".strip_tags($row->subject)."</A></td>\n";
			echo "	<td>";
			if(strlen($row->re_date)==14) {
				echo "<img src=\"".$Dir."images/common/mypersonal_skin_icon1.gif\" border=\"0\" align=\"absmiddle\">";
			} else {
				echo "답변대기";
			}
			echo "	</td>\n";
			echo "	<td>".$re_date."</td>\n";
			echo "</tr>\n";
			$cnt++;
		}
		mysql_free_result($result);
		if ($cnt==0) {
			echo "<tr height=\"30\"><td colspan=\"4\" align=\"center\" style=\"padding:30px;\">1:1 문의 내역이 없습니다.</td></tr>";
		}
?>
			</table>
		</td>
	</tr>
<? } ?>

<? /*	<!-- 관심상품(wish list) 목록 -->
	<tr>
		<td>
			<div style="float:left;padding-left:10px;font-size:16px;letter-spacing:-1px;">최근 찜한 상품 (3)</div>
			<div style="float:right;"><A HREF="<?=$Dir.FrontDir?>wishlist.php">더보기 +</div>
		</td>
	</tr>
	<tr><td height="7"></td></tr>
	<tr>
		<td style="padding:15px;background:url('/data/design/img/sub/bg_boxline2.gif') repeat-y;">
			<table cellpadding="0" cellspacing="0" width="100%" border="0">
				<TR>
<?
				$sql = "SELECT b.productcode, b.productname, b.sellprice, b.consumerprice, b.quantity, b.reserve, b.reservetype, b.tinyimage, b.discountRate, b.vender, ";
				$sql.= "b.option_price, b.option_quantity, b.selfcode, b.etctype FROM tblwishlist a, tblproduct b ";
				$sql.= "LEFT OUTER JOIN tblproductgroupcode c ON b.productcode=c.productcode ";
				$sql.= "WHERE a.id='".$_ShopInfo->getMemid()."' AND a.productcode=b.productcode ";
				$sql.= "AND (b.group_check='N' OR c.group_code='".$_ShopInfo->getMemgroup()."') ";
				$sql.= "AND b.display='Y' LIMIT 8 ";
				$result=mysql_query($sql,get_db_conn());
				$cnt=0;

				while($row=mysql_fetch_object($result)) {
					if ($cnt!=0 && $cnt%4==0) {
						echo "</tr><tr><td colspan=\"9\" height=\"10\"></td></tr>\n";
					}

					// 할인율 표시
					$discountRate = ( $row->discountRate > 0 ) ? "<strong>".$row->discountRate."</strong>%↓" : "";

					$memberpriceValue = $row->sellprice;
					$strikeStart = $strikeEnd = $memberprice = '';
					if($row->discountprices>0){
						$memberprice = number_format($row->sellprice - $row->discountprices);
						$strikeStart = "<strike>";
						$strikeEnd = "</strike>";
						$memberpriceValue = ($row->sellprice - $row->discountprices);
					}

					$tableSize = $_data->primg_minisize;

					if ($cnt!=0 && $cnt%4!=0) {
						echo "<td width=\"10\" nowrap></td>";
					}
					echo "<td width=\"25%\" align=\"center\" valign=\"top\">\n";
					echo "<TABLE cellspacing=\"0\" cellpadding=\"0\" width=\"".$tableSize."\" border=\"0\" id=\"W".$row->productcode."\" onmouseover=\"quickfun_show(this,'W".$row->productcode."','')\" onmouseout=\"quickfun_show(this,'W".$row->productcode."','none')\" class=\"prInfoBox\">\n";
					echo "<TR>\n";
					echo "	<TD class=\"prImage\" height=\"100\" align=\"center\">";
					echo "<A HREF=\"".$Dir.FrontDir."productdetail.php?productcode=".$row->productcode.$add_query."\" onmouseover=\"window.status='상품상세조회';return true;\" onmouseout=\"window.status='';return true;\">";
					if (strlen($row->tinyimage)>0 && file_exists($Dir.DataDir."shopimages/product/".$row->tinyimage)==true) {
						echo "<img src=\"".$Dir.DataDir."shopimages/product/".urlencode($row->tinyimage)."\" border=\"0\" ";
						$width = getimagesize($Dir.DataDir."shopimages/product/".$row->tinyimage);
						if($_data->ETCTYPE["IMGSERO"]=="Y") {
							if ($width[1]>$width[0] && $width[1]>$_data->primg_minisize2) echo "height=\"".$_data->primg_minisize2."\" ";
							else if (($width[1]>=$width[0] && $width[0]>=$_data->primg_minisize) || $width[0]>=$_data->primg_minisize) echo "width=\"".$_data->primg_minisize."\" ";
						} else {
							if ($width[0]>=$width[1] && $width[0]>=$_data->primg_minisize) echo "width=\"".$_data->primg_minisize."\" ";
							else if ($width[1]>=$_data->primg_minisize) echo "height=\"".$_data->primg_minisize."\" ";
						}
					} else {
						echo "<img src=\"".$Dir."images/no_img.gif\" border=\"0\" align=\"center\"";
					}
					echo "	></A></td>";
					echo "</tr>\n";

					echo "<tr><td height=\"3\" style=\"position:relative;\">".($_data->ETCTYPE["QUICKTOOLS"]!="Y"?"<script>quickfun_write('".$Dir."','W','".$row->productcode."','".($row->quantity=="0"?"":"1")."')</script>":"")."</td></tr>\n";

					echo "<tr>";
					echo "	<td style=\"padding:5px 7px; word-break:break-all;\"><A HREF=\"".$Dir.FrontDir."productdetail.php?productcode=".$row->productcode.$add_query."\" onmouseover=\"window.status='상품상세조회';return true;\" onmouseout=\"window.status='';return true;\"><FONT class=\"prname\">".viewproductname($row->productname,$row->etctype,$row->selfcode)."</FONT></A></td>\n";
					echo "</tr>";


					//시중가 + 판매가 + 할인율 + 회원할인가
					echo "
						<tr>
							<td style=\"padding:0px 7px 7px 7px; word-break:break-all;\">
								<table border=0 cellpadding=0 cellspacing=0 width=100%>
									<tr>
										<td>
					";
					if($row->consumerprice!=0) {
						echo "<span class=\"mainconprice\"><strike>".number_format($row->consumerprice)."원</strike></span>\n";
					}

					echo "<span style=\"white-space:nowrap;\">";
					if($dicker=dickerview($row->etctype,"<strong class=\"mainprprice\">".number_format($row->sellprice)."원</strong>",1)) {
						echo $strikeStart.$dicker.$strikeEnd;
					} else if(strlen($_data->proption_price)==0) {
						echo "<strong class=\"mainprprice\">".number_format($row->sellprice)."원</strong>";
						if (strlen($row->option_price)!=0) echo "<FONT color=\"#FF0000\">(옵션변동)</FONT>";
					} else {
						//echo "<img src=\"".$Dir."images/common/won_icon3.gif\" border=\"0\" align=\"absmiddle\"> ";
						if (strlen($row->option_price)==0) echo "<strong class=\"mainprprice\">".number_format($row->sellprice)."원</strong>";
						else echo ereg_replace("\[PRICE\]",number_format($row->sellprice),$_data->proption_price);
					}
					echo "</span>";
					echo "</td>";

					if($row->discountRate > 0){
						echo "<td align=\"right\" valign=\"bottom\" class=\"discount\">".$discountRate."</td>";
					}
					echo "
							</tr>
						</table>
					";

					//회원할인가 적용
					if( $memberprice > 0 ) {
						echo "<div><span class=\"prprice\">".dickerview($row->etctype,$memberprice)."원</span> <img src=\"".$Dir."images/common/memsale_icon.gif\" align=\"absmiddle\" alt=\"\" /></div>\n";
					}

					if ($row->quantity=="0") echo soldout(1);

					$reserveconv=getReserveConversion($row->reserve,$row->reservetype,$row->sellprice,"Y");
					if($reserveconv>0) {
						echo "<div style=\"margin-top:5px;\"><img src=\"".$Dir."images/common/reserve_icon.gif\" border=\"0\" style=\"vertical-ailgn:middle;\" /> <span class=\"mainreserve\">".number_format($reserveconv)."</span>원</div>\n";
					}
					echo "</td>";
					echo "</tr>";

					// 입점사 네임택
					if( $row->vender > 0 ) {
						$classList = array();
						$classResult=mysql_query("SELECT * FROM `tblVenderClassType` ",get_db_conn());
						while($classRow=mysql_fetch_object($classResult)) {
							$classList[$classRow->idx] = $classRow->name;
						}
						$v_info = mysql_fetch_assoc ( mysql_query( "SELECT * FROM `tblvenderinfo` WHERE `vender`=".$row->vender." LIMIT 1;" ,get_db_conn()) );

						$venderNameTag = "<div style=\"float:left; width:60px;\"><img src=\"".$com_image_url.$v_info['com_image']."\" onerror=\"this.src='/images/no_img.gif';\" width=\"48\" style=\"border:1px solid #dddddd;\" /></div>";
						$venderNameTag .= "<div style=\"float:left; width:65%; font-size:11px;\">";
						$venderNameTag .= "	<span class=\"name\">".$v_info['com_name']."</span> <span class=\"owner\">(".$v_info['com_owner'].")</span><br />";
						$venderNameTag .= "	<a href=\"javascript:GoMinishop('/minishop.php?storeid=".$v_info['id']."')\"><img src=\"/images/common/icon_vender_go.gif\" border=\"0\" alt=\"전체상품보기\" /></a>";
						$venderNameTag .= "</div>";

						// 네임텍 출력
						echo "
							<tr>
								<td class=\"nameTagBox\">".$venderNameTag."</td>
							</tr>
						";
					}

					echo "</table>\n";
					echo "</td>";

					$cnt++;
				}
				if($cnt>0 && $cnt<4) {
					for($k=0; $k<(4-$cnt); $k++) {
						echo "<td width=\"10\" nowrap></td>\n<td width=\"20%\"></td>\n";
					}
				}
				mysql_free_result($result);
				if ($cnt==0) {
					echo "<td height=\"30\" colspan=\"9\" align=\"center\">WishList에 담긴 상품이 없습니다.</td>";
				}

?>
				</tr>
			</table>
		</td>
	</tr>
*/ ?>
</table>
</div>

<form name="goOrderForm" action="./mypage_orderlist.php" method="post">
	<input type="hidden" name="ordgbn" value="" />
	<input type="hidden" name="type" value="" />
</form>

<script type="text/javascript">
	function goOrderType(temp){
		var _form = document.goOrderForm;

		_form.ordgbn.value=temp;
		_form.submit();
	}
</script>