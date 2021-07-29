<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td>
			<!-- 주문현황 -->
			<? include $Dir.FrontDir."mypage_top.php"; ?>
		</td>
	</tr>
	<tr>
		<td height="20"></td>
	</tr>
</table>
<style>
.daySearch {display: table;overflow: hidden;width:100%; table-layout: fixed;padding:0;margin:0;}
.daySearch ul li {height:30px;}

.searchTitle {display:inline;margin-right:20px; font-weight:bold}
.searchBtn{display:inline;margin-left:50px;}
.searchBtn button{display:inline-block;height:24px;margin:-1px;padding:2px 10px 0px 10px;border:1px solid grey;background:#eeeeee;line-height:24px;cursor:pointer}

.searchBtn2{display:inline;margin-left:50px;}
.searchBtn2 button{display:inline-block;height:24px;margin:2px 0px;padding:2px 10px 0px 10px;border:none;border-radius:2px;background:#666666;color:#ffffff;line-height:24px;cursor:pointer}
.searchBtn2 input{display:inline-block;height:24px;width:100px;margin:2px 0px;line-height:24px;}
</style>

<table cellpadding="0" cellspacing="0" width="100%">	
	<tr>
		<td bgcolor="#EAEAEA" style="padding:6px;">
			<table cellpadding="0" cellspacing="0" width="100%" bgcolor="#ffffff">
				<tr>
					<td style="padding:25px;" class="daySearch">
						<ul>
							<li>
								<div class="searchTitle">기간별 조회</div>
								<div class="searchBtn">
									<button type="button" onclick="javascript:GoSearch('TODAY')">오늘</button>
									<button type="button" onclick="javascript:GoSearch('15DAY')">15일</button>
									<button type="button" onclick="javascript:GoSearch('1MONTH')">1개월</button>
									<button type="button" onclick="javascript:GoSearch('3MONTH')">3개월</button>
									<button type="button" onclick="javascript:GoSearch('6MONTH')">6개월</button>
								</div>
							</li>
							<li>
								<div class="searchTitle">일자별 조회</div>
								<div class="searchBtn2">
									<input type="text" name="s_curdate" id="s_curdate" value="<?=$s_curdate?>"> ~
									<input type="text" name="e_curdate" id="e_curdate" value="<?=$e_curdate?>">
									<script type="text/javascript">
									  $j( "#s_curdate" ).datepicker({dateFormat:"yymmdd"});
									  $j( "#e_curdate" ).datepicker({dateFormat:"yymmdd"});
									</script>
									<button type="button" onclick="javascript:CheckForm();">조회하기</button>
								</div>
							</li>
						</ul>
						
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td style="padding:10px; font-size:12px; letter-spacing:-0.5pt; line-height:21px;">* 가장 최근 주문 <font color="#F02800" style="font-size:11px;letter-spacing:-0.5pt;"><b>6개월 자료까지 제공</b></font>되며, <font color="#000000" style="font-size:11px;letter-spacing:-0.5pt;"><b>6개월 이전 자료는 일자를 지정해서 조회</b></font>하시기 바랍니다.<br>
			&nbsp;&nbsp;&nbsp;(일자별로 조회시 최대 지난 3년 동안의 주문내역 조회가 가능합니다)<br>
			*&nbsp;한 번에 조회 가능한 기간은 6개월로 일자 선택시 조회 기간을 6개월 이내로 선택하셔야 합니다.</td>
	</tr>	
	<tr>
		<td height="40"></td>
	</tr>
</table>
<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
			<td >
	
	<table border="0" cellpadding="0" cellspacing="0" width="100%">
		<tr>
			<td valign="bottom" style="background:url(<?=$Dir?>images/common/orderlist/<?=$_data->design_orderlist?>/mypersonal_skin3_menubg.gif)">
				<TABLE BORDER="0" CELLPADDING="0" CELLSPACING="0">
					<TR>
						<TD><A HREF="javascript:GoOrdGbn('A')"><img src="<?=$Dir?>images/common/orderlist/<?=$_data->design_orderlist?>/menu01<?=($ordgbn=="A"?"on":"off")?>.gif" border="0"></A></TD>
						<TD><A HREF="javascript:GoOrdGbn('S')"><img src="<?=$Dir?>images/common/orderlist/<?=$_data->design_orderlist?>/menu02<?=($ordgbn=="S"?"on":"off")?>.gif" border="0"></TD>
						<TD><A HREF="javascript:GoOrdGbn('C')"><img src="<?=$Dir?>images/common/orderlist/<?=$_data->design_orderlist?>/menu03<?=($ordgbn=="C"?"on":"off")?>.gif" border="0"></TD>
						<? if($type != 3){?>
						<TD><A HREF="javascript:GoOrdGbn('R')"><img src="<?=$Dir?>images/common/orderlist/<?=$_data->design_orderlist?>/menu04<?=($ordgbn=="R"?"on":"off")?>.gif" border="0"></A></TD>						
						<? } ?>
					</TR>
				</TABLE>
			</td>
		</tr>
		
				<tr>
		
		
				<td>
		
		<table cellpadding="0" cellspacing="0" width="100%" border="0" bgcolor="#F8F8F8" style="table-layout:fixed">
			<!-- 주문일자, 주문 상품명, 배송상태, 배송추적, 결제방법, 결제금액, 상세정보  -->
			<col width="180" />
			<col />
			<col width="150" />
			<col width="90" />
			<col width="80" />
			<col width="80" />
			<tr height="30" align="center" bgcolor="#F8F8F8">
				<td>주문일(결제정보)</td>
				<td>상품명/옵션</td>
				<td>예약상태</b></font></td>
				<td><?=($type == 3)?"인증처리상태":"배송상태"?></td>
				<!--<td>교환/환불처리</b></font></td>-->
				<td><?=($type == 3)?"인증번호":"배송추적"?></td>
				<td>상품평</td>
			</tr>
			<tr>
				<td height="1" colspan="6" bgcolor="#ededed"></td>
			</tr>
			<?
		$delicomlist=getDeliCompany();
		$returnableCnt = 0; // 하단에서 교환,환불 가능 상품 확인 을 위해
		
		$e_curdate = $e_curdate."999999999999";
/*
		$s_curtime=mktime(0,0,0,$s_month,$s_day,$s_year);
		$s_curdate=date("Ymd",$s_curtime);
		$e_curtime=mktime(0,0,0,$e_month,$e_day,$e_year);
		$e_curdate=date("Ymd",$e_curtime)."999999999999";
*/

		$orderlists = getOrderList($s_curdate,$e_curdate,$ordgbn,$type,$gotopage);

		if($orderlists['total'] < 1){ ?>
			<tr>
				<td colspan="6" style="padding:10px 0px; text-align:center; background:#FFFFFF;padding:30px;">등록된 주문 내역이 없습니다.</td>
			</tr>
			<tr>
				<td colspan="6" height="1" bgcolor="#ededed"></td>
			</tr>
			<?		}else{
			foreach($orderlists['orders'] as $row){
				$orderproducts = array();
				$orderproducts = getOrderProduct($row->ordercode);
?>
			<tr bgcolor="#FFFFFF" onmouseover="this.style.background='#ffffff';" onmouseout="this.style.background='#FFFFFF';">
				<td style="padding-top:10; padding-bottom:10;" class="mypage_order_line" valign="top">
					<table border="0" cellpadding="0" cellspacing="0" width="100%">
						<tr>
							<td height="26" class="mypage_order_line2"><b>
								<?=substr($row->ordercode,0,4)?>
								/
								<?=substr($row->ordercode,4,2)?>
								/
								<?=substr($row->ordercode,6,2)?>
								</b></td>
						</tr>
						<tr>
							<td height=5></td>
						</tr>
						<tr>
							<td class="mypage_list_cont">결제방법 :
								<?=getPaymethodStr($row->paymethod)?>
							</td>
						</tr>
						<tr>
							<td class="mypage_list_cont">결제금액 : <b><font color="#000000">
								<?=number_format($row->price)?>
								</font></b>원</td>
						</tr>
						<tr>
							<td height=5></td>
						</tr>
						<tr>
							<td class="mypage_list_cont"><A HREF="javascript:OrderDetailPop('<?=$row->ordercode?>')" onmouseover="window.status='주문내역조회';return true;" onmouseout="window.status='';return true;"><img src="<?=$Dir?>images/common/mypage/<?=$_data->design_mypage?>/mypage_order_icon01.gif" alt="보기" /></a>
								<?
					/*
					if (preg_match("/^(B){1}/", $row->paymethod) && strlen($row->bank_date)<12 && $row->deli_gbn=="N") {
						echo "<br/><a href=\"javascript:order_cancel('".$row->tempkey."', '".$row->ordercode."','".$row->bank_date."')\" onMouseOver=\"window.status='주문취소';return true;\"><img src=\"".$Dir."images/common/orderdetailpop_ordercancel.gif\" align=absmiddle border=0></a>\n";
					}
					*/
					?>
							</td>
						</tr>
					</table>
				</td>
				
					<td colspan="5">
				<table border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed">
					<col width="80%"/>
					<col width="150" />
					<col width="90" />
					<col width="80"></col>
					<!--<col width="80" />-->
					<col width="80" />
					<?				$chkbox_count = 0;
				$cnt = count($orderproducts);
				for($jj=0;$jj < $cnt;$jj++){
					$row2 = $orderproducts[$jj];
					if($jj>0) echo '<tr><td colspan="6" height="1" bgcolor="#E7E7E7"></tr>';
					$optvalue="";
					if(ereg("^(\[OPTG)([0-9]{3})(\])$",$row2->opt1_name)) {
						$optioncode=$row2->opt1_name;
						$row2->opt1_name="";
						$sql = "SELECT opt_name FROM tblorderoption WHERE ordercode='".$row->ordercode."' AND productcode='".$row2->productcode."' AND opt_idx='".$optioncode."' limit 1 ";
						$res=mysql_query($sql,get_db_conn());
						if($res && mysql_num_rows($res)){
							$optvalue= mysql_result($res,0,0);
						}
						mysql_free_result($res);
					}

					$prentinfo['codeinfo'] = venderRentInfo($row2->vender,$row2->pridx,$row2->productcode);
	?>
					<tr>
						<td style="font-size:8pt; padding:10px; line-height:11pt;">
							<div style="width:25px;float:left;text-align:left;">
								<? /* if ($row->deli_gbn!="C" && !($row->pay_admin_proc=="C" && $row->pay_flag=="0000") && count($orderproducts)>1 && $row2->status=='') {?>
								<input type="checkbox" name="chk_<?= $row->ordercode ?>" id="chk_<?= $row->ordercode ?>_<?= $jj ?>" value="<?=$row2->productcode?>"/>
								<input type="hidden" name="chk_uid_<?= $row->ordercode ?>" id="chk_uid_<?= $row->ordercode ?>_<?= $jj ?>" value="<?=$row2->uid?>"/>
								<?
									$chkbox_count++;
									}
								*/ ?>
							</div>
							<div>
							<?
									$reservation = "";
									if( $row2->reservation != "0000-00-00" ) {
										$reservation = "[예약배송상품(배송예정일:".$row2->reservation.")]<br />";
									}
								?>
							<A HREF="javascript:OrderDetailProduct('<?=$row->ordercode?>','<?=$row2->productcode?>')" onmouseover="window.status='주문내역조회';return true;" onmouseout="window.status='';return true;"><img src="<?=(strlen($row2->tinyimage)>0 && file_exists($Dir.DataDir."shopimages/product/".$row2->tinyimage)==true)?$Dir.DataDir.'shopimages/product/'.urlencode($row2->tinyimage):$Dir."images/no_img.gif"?>" border="0" width="50" style="float:left;margin-right:5px;"/>
							<?=$reservation?>
							<?=$row2->productname?>
							</a>
							<?
									if(!_empty($optvalue)) 	echo "<br><img src=\"".$Dir."images/common/icn_option.gif\" border=0 align=absmiddle> ".$optvalue."";
									if(!_empty($row2->start)){ // 렌탈
										echo '<br>'.$row2->opt1_name;
										if($prentinfo['codeinfo']['pricetype']=="long") echo "개월";
									}else{
										if(!_empty($row2->opt1_name)){
											echo '<br>'.$row2->opt1_name;
											if(!_empty($row2->opt2_name)) echo ' / '.$row2->opt2_name;
										}
									}
								?>
							
							<!-- 대여 일정 -->
							<?
								// 렌탈 상품 주문 옵션
								$data['ordercode'] = $row->ordercode;
								$data['productcode'] = $row2->productcode;
								//_pr($row2);
								$productScheduleList = productScheduleList($data);
								//_pr($productScheduleList);
								/*
								foreach ($productScheduleList as $productScheduleValue) {
									echo "<br>대여기간 : ".$productScheduleValue['bookingStartDate']." ~ ".$productScheduleValue['bookingEndDate'];
									foreach ( $productScheduleValue['opt'] as $optValue) {
										echo "<br>" . $optValue['optionName'] . " : " . $optValue['orderCnt'] . "개";
									}
								}*/
								if(!_empty($row2->start)){
									if($prentinfo['codeinfo']['pricetype']=="long"){
										echo $row2->opt2_name;
									}else{
										$diff = datediff_rent($row2->end,$row2->start);
										echo '<br>대여기간 : '.substr($row2->start,0,-6).'시 ~ '.date('Y-m-d H',strtotime($row2->end)+1).'시';
										echo ' ('.(($diff['day'] >0)?$diff['day'].'일':'').(($diff['hour'] >0)?$diff['hour'].'시간':'').')';
									}
								}
								?>
						</td>
						<td align="center" style="font-size:8pt;"><font color="#000000">
						<?php
						switch($row2->prd_status) {
							case 'Y': echo "예약확정"; break;
							//case 'D': echo "예약지연"; break;
							case 'F': echo "예약불가(상품불량)"; break;
							case 'W': echo "예약불가(재고부족)"; break;
							case 'I': echo "예약불가(상품정보상이)"; break;
							case 'N': echo "예약 대기중"; break;
							default: echo "예약 대기중";
						}
						?>
						</font></td>

						<?
						$deliStatus = orderProductDeliStatusStr($row2,$row,$cnt);
						if($row2->deli_gbn=="D" || $row2->deli_gbn=="W"){
							$onclick = "onclick=\"javascript:OrderCancelPop('".$row->ordercode."')\" style='cursor:pointer'";
						}else{
							$onclick = "";
						}
						?>
						<td align="center" style="font-size:8pt;"><font color="#000000" <?=$onclick?>><?=$deliStatus?></font></td>
						<!--
								<td style="text-align:center">
									<font color="#3f77ca">
									<?
										if(getProductAbleInfo($row2->productcode,'return') == 'Y'){
											$pststr = orderProductStatusStr($row2->status);
											if(_empty($pststr)){
												if($row2->deli_gbn != 'Y') $pststr = '-';
												else if(strtotime('-15 day') > strtotime(substr($row->ordercode,0,8))) $pststr = '--';
												else{
													$pststr = '<input type="checkbox" value="'.$row2->uid.'" ordCode="'.$row->ordercode.'" name="Item[]" />';
													$returnableCnt++;
												}
											}
										}else{
											$pststr = '불가';
										}
										echo $pststr;
									?>
									</font>
								</td>
								<td></td>
								-->
						<td align="center" style="font-size:8pt;padding-top:3;">
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
									}
								}
								echo $deli_link;
								?>
							</div>
						</td>
						<td align=center>
							<? if($row2->deli_gbn=="Y" && $_data->review_type !="N") { ?>
							<A HREF="javascript:OrderReview('<?=$row->ordercode?>','<?=$row2->productcode?>')" onmouseover="window.status='상품평';return true;" onmouseout="window.status='';return true;"><img src="<?=$Dir?>images/common/mypage_detailview.gif" border="0" alt="상품평작성" /></A>
							<? }else{ ?>
							<img src="<?=$Dir?>images/common/mypage_detailview_off.gif" alt="상품평작성" />
							<? } ?>
						</td>
					</tr>
					<? } // end for by jj ?>
					<? if(false && $row->deli_gbn!="C" && !($row->pay_admin_proc=="C" && $row->pay_flag=="0000") && $chkbox_count>0 && count($orderproducts)>1) {?>
					<tr>
						<td colspan=4 height=1 bgcolor=#E5E5E5>
					</tr>
					<tr>
						
							<td style="background-color:#f8f8f8;" style="padding:10px;" class="mypage_list_cont">
						<div style="width:25px;float:left;text-align:left;">
							<input type="checkbox" name="chk_<?= $row->ordercode ?>_all" id="chk_<?= $row->ordercode ?>_all" value="all" onclick="productAll('chk_<?= $row->ordercode ?>')"/>
						</div>
						<div style="width:85px;float:left;padding-top:3px;"><b>전체선택</b></div>
						<div style="float:left;padding-top:3px;"><b> - 선택상품 주문취소 신청</b></div>
					
							</td>
					
					
						<td style="background-color:#f8f8f8;" align="center"><span style="cursor:pointer"
					<? if (strlen($row->bank_date)<12 && preg_match("/^(B|O|Q){1}/", $row->paymethod)) { ?>
						onclick="order_one_cancel('<?= $row->ordercode ?>', '', 'NO', '<?= $row->tempkey ?>')"
					<? }else{ ?>
						onclick="order_multi_cancel('<?=$row->ordercode?>')"
					<? } ?>
					><img src="<?=$Dir?>images/common/mypage/<?=$_data->design_mypage?>/mypage_order_cancel_icon02.gif" alt="확인" /></span></td>
						<td style="background-color:#f8f8f8;">&nbsp;</td>
						<td style="background-color:#f8f8f8;">&nbsp;</td>
					</tr>
					<? } ?>
				</table>
			
					</td>
			
			
					</tr>
			
			<tr>
				<td colspan="6" height="1" bgcolor="#d1d1d1"></td>
			</tr>
			<?		} // end foreach
	} // end if
?>
		</table>
		
				</td>
		
		
				</tr>
		
		<!--
	<? if($returnableCnt > 0) { ?>
	<tr>
		<td>
			<div id="btn_sel" style="text-align:right; margin-top:10px; margin-right:10px">
				<a href="#" onclick="return refund1();"><img src="<?=$Dir?>images/common/mypage/<?=$_data->design_mypage?>/mypage_order_icon02.gif" alt="선택건에 대해 교환신청" /></a>
				<a href="#" onclick="return refund2();"><img src="<?=$Dir?>images/common/mypage/<?=$_data->design_mypage?>/mypage_order_icon03.gif" alt="선택건에 대해 환불신청"/></a>
			</div>
		</td>
	</tr>
	<?}?>
-->
		<tr>
			<td height="10"></td>
		</tr>
		<tr>
			<?
	$pages = new pages(array('total_page'=>$orderlists['total_page'],'page'=>$orderlists['page'],'pageblocks'=>$setup[page_num],'links'=>"javascript:newGoPage('%u')"));
?>
			<td align="center">
				<?=$pages->_solv()->_result('fulltext')?>
			</td>
		</tr>
	</table>
	
			</td>
	
	
			</tr>
</table>