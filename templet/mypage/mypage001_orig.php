<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td>
			<!-- ȸ������ BOX START -->
			<table border="0" cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td style="background:#eeeeee; padding:8px;">
						<table border="0" cellpadding="0" cellspacing="0" width="100%" style="background:#ffffff;">
							<tr>
								<td valign="top" style="width:45%; padding:20px 15px;">
									<!-- �� ���� �⺻���� START -->
									<table border="0" cellpadding="0" cellspacing="0" width="100%">
										<tr>
											<td width="67"><img src="<?=$Dir?>images/common/mypage/<?=$_data->design_mypage?>/myinfo_tit.gif" alt="" /></td>
											<td valign="top" style="width:280px; border-right:1px solid #e9eaee;">
												<table width="96%" cellpadding="0" cellspacing="0" border="0">
													<tr>
														<td height="20" valign="top" colspan="3" class="mypage_list_title"><b>���� ����</b><img src="<?=$Dir?>images/common/mypage/<?=$_data->design_mypage?>/mypage_mem_icon03.gif" border="0"></td>
													</tr>
													<tr>
														<td width="60" style="font-weight:bold;">�ּ�</td>
														<td width="20" align="center" style="font-size:11px;">:</td>
														<td><?=str_replace("=","&nbsp;",$_mdata->home_addr)?></td>
													</tr>
													<tr>
														<td style="font-weight:bold;">��ȭ��ȣ</td>
														<td width="20" align="center" style="font-size:11px;">:</td>
														<td><?=$_mdata->home_tel?><?if(strlen($_mdata->mobile)>0)echo ", ".$_mdata->mobile;?></td>
													</tr>
													<tr>
														<td style="font-weight:bold;">�̸���</td>
														<td width="20" align="center" style="font-size:11px;">:</td>
														<td><A HREF="mailto:<?=$_mdata->email?>"><?=$_mdata->email?></A></td>
													</tr>
												</table>
											</td>
										</tr>
									</table>
									<!-- �� ���� �⺻���� END -->
								</td>
								<td>
									<!-- ������/����/��ǰ�� ��ȣ���� START -->
									<table border="0" cellpadding="0" cellspacing="0">
										<tr>
											<td width="67"><img src="<?=$Dir?>images/common/mypage/<?=$_data->design_mypage?>/mypoint_tit.gif" alt="" /></td>
											<td>
												<table border="0" cellpadding="0" cellspacing="0">
													<tr>
														<td height="20" valign="top" class="mypage_list_title"><b>������</b><A HREF="<?=$Dir.FrontDir?>mypage_reserve.php"><img src="<?=$Dir?>images/common/mypage/<?=$_data->design_mypage?>/mypage_mem_icon03.gif" border="0"></a></td>
													</tr>
													<tr>
														<td class="mypage_mem_info"><?=number_format($_mdata->reserve)?>��</td>
													</tr>
													<tr>
														<td height="25" valign="bottom"><A HREF="<?=$Dir.FrontDir?>mypage_reserve.php"><img src="<?=$Dir?>images/common/mypage/<?=$_data->design_mypage?>/mypage_mem_icon05.gif" border="0" alt="�ڼ�������" /></a></td>
													</tr>
												</table>
											</td>
											<td width="67" style="padding-left:20px;"><img src="<?=$Dir?>images/common/mypage/<?=$_data->design_mypage?>/mycoupon_tit.gif" alt="" /></td>
											<td valign="top">
												<table border="0" cellpadding="0" cellspacing="0">
													<tr>
														<td height="20" valign="top" class="mypage_list_title"><b>����</b><A HREF="<?=$Dir.FrontDir?>mypage_coupon.php"><img src="<?=$Dir?>images/common/mypage/<?=$_data->design_mypage?>/mypage_mem_icon03.gif" border="0"></a></td>
													</tr>
													<tr>
														<td class="mypage_mem_info"><?=number_format($coupon_cnt)?>��</td>
													</tr>
													<tr>
														<td height="25" valign="bottom"><A HREF="<?=$Dir.FrontDir?>mypage_coupon.php"><img src="<?=$Dir?>images/common/mypage/<?=$_data->design_mypage?>/mypage_mem_icon05.gif" border="0" alt="�ڼ�������" /></a></td>
													</tr>
												</table>
											</td>
											<td width="67" style="padding-left:20px;"><img src="<?=$Dir?>images/common/mypage/<?=$_data->design_mypage?>/mygiftcard_tit.gif" alt="" /></td>
											<td valign="top">
												<table border="0" cellpadding="0" cellspacing="0">
													<tr>
														<td height="20" valign="top" class="mypage_list_title"><b>��ǰ��</b><img src="<?=$Dir?>images/common/mypage/<?=$_data->design_mypage?>/mypage_mem_icon03.gif" border="0"></td>
													</tr>
													<tr>
														<td><a href="javascript:addGiftcard();"><img src="<?=$Dir?>images/common/mypage/<?=$_data->design_mypage?>/mypage_mem_icon04.gif" border="0" alt="�����ϱ�" /></a></td>
													</tr>
												</table>
											</td>
										</tr>
									</table>
									<!-- ������/����/��ǰ�� ��ȣ���� END -->
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td height="20"></td></tr>
				<tr>
					<td>
						<table border="0" cellpadding="0" cellspacing="0" width="100%" style="border:1px solid #eeeeee; border-bottom:none;">
							<tr>
								<?
									if(strlen($_ShopInfo->getMemid())>0) {
										$arr_dctype=array("B"=>"����","C"=>"ī��","N"=>"");
										if( strlen($_ShopInfo->getMemgroup())>0 ) {
											$sql = "SELECT a.name,b.group_code,b.group_name,b.group_payment,b.group_usemoney,b.group_addmoney,b.group_order_price,b.group_order_cnt FROM tblmember a, tblmembergroup b WHERE a.id='".$_ShopInfo->getMemid()."' AND b.group_code=a.group_code ";

										} else {
											$sql = "SELECT name FROM tblmember WHERE id='".$_ShopInfo->getMemid()."' ";
										}

										$result=mysql_query($sql,get_db_conn());

										if($row=mysql_fetch_object($result)) {
											if(!_empty($row->group_name)){
												// ȸ�� ��� ���� ó��
												$fsql = "select * from tblmembergroup where group_order_price > '".$row->group_order_price."' and group_order_cnt >= '".$row->group_order_price."' order by group_order_price asc,group_order_cnt asc limit 1";

												$nginfo = false;
												if(false !== $fres = mysql_query($fsql,get_db_conn())){
													if(mysql_num_rows($fres)){
														$nginfo = mysql_fetch_assoc($fres);
													}else{
														$fsql = "select * from tblmembergroup order by group_order_price desc,group_order_cnt desc limit 1";
														if(false !== $fres = mysql_query($fsql,get_db_conn())){
															if(mysql_num_rows($fres)){
																$nginfo = mysql_fetch_assoc($fres);
															}
														}
													}
												}

												$consql = "select * from extra_conf where type='autogroup'";
												$gconfig = array();
												if($nginfo !== false && false !== $conres = mysql_query($consql,get_db_conn())){
													while($crow = mysql_fetch_assoc($conres)){
														$gconfig[$crow['name']]= $crow['value'];
													}
													$lastday = intval(date('t'));
													$currday = intval(date('d'));
												//	$gconfig['rangestart'] = 29;
													$gap = $gconfig['rangestart'] - $currday;

													$gconfig['check']['end'] =strtotime(date('Y-m-'.$gconfig['rangestart']));

													if($gap < 0){
														$gconfig['check']['end'] = strtotime('+1 month',$gconfig['check']['end']);
													}
													$gconfig['check']['start'] = strtotime('-'.($gconfig['rangemonth']).' month',$gconfig['check']['end']);
													$gconfig['check']['rprice'] = $nginfo['group_order_price'];
													$gconfig['check']['rcnt'] = $nginfo['group_order_cnt'];
													$gconfig['check']['price'] = $gconfig['check']['cnt'] = 0;

													$orderinfoSQL = "SELECT SUM(price) as sumprice, COUNT(price) AS sumcount FROM tblorderinfo WHERE id = '".$_ShopInfo->getMemid()."' AND deli_gbn = 'Y' AND ordercode >= '".date('Ymd',$gconfig['checkRange']['start'])."000000' AND ordercode <= '".date('Ymd',$gconfig['checkRange']['end'])."235959' group by id ";
													if(false !== $ores = mysql_query($orderinfoSQL,get_db_conn())){
														if(mysql_num_rows($ores)){
															$gconfig['check']['price'] = mysql_result($ores,0,0);
															$gconfig['check']['rprice'] -= $gconfig['check']['price'];
															$gconfig['check']['cnt'] -= mysql_result($ores,0,1);
															$gconfig['check']['rcnt'] -= $gconfig['check']['cnt'];
														}
													}

													if($gconfig['check']['rprice'] < 0) $gconfig['check']['rprice']=0;
													if($gconfig['check']['rcnt'] < 0) $gconfig['check']['rcnt']=0;
												}
											}
								?>
								<td width="53%" valign="top" style="padding:20px;">
									<!-- ȸ����� �� �߰�����/�����ȳ� START -->
									<table border="0" cellpadding="0" cellspacing="0" style="border-right:1px solid #eeeeee;">
										<tr>
											<td>
												<?	if(file_exists($Dir.DataDir."shopimages/etc/groupimg_".$row->group_code.".gif")){ ?>
													<img src="<?=$Dir.DataDir?>shopimages/etc/groupimg_<?=$row->group_code?>.gif" border=0>
												<? }else{ ?>
													<img src="<?=$Dir?>images/common/mypage/<?=$_data->design_mypage?>/mypage_mem_icon01.gif">
												<? } ?>
											</td>
											<td width="16"></td>
											<td style="letter-spacing:-0.5px; padding-right:20px;">
												<div class="mypage_list_title"><b>ȸ����� ����</b><img src="<?=$Dir?>images/common/mypage/<?=$_data->design_mypage?>/mypage_mem_icon03.gif" border="0" /></div>
												<?=$row->name?>���� ȸ������� [<b><font color="#ff6600"><?=$row->group_name?></font></b>] �Դϴ�.<br />
												<?if (substr($row->group_code,0,1)!="M") {?>
													<span style="line-height:15px; letter-spacing:-0.5px;"><?=$row->name?>���� <b><font color="#ff6600"><?=number_format($row->group_usemoney)?>��</font></b> �̻� <?=$arr_dctype[$row->group_payment]?>���Ž�
												<?
													$type=substr($row->group_code,0,2);

													if($type=="RW") echo "<b><font color=#3f77ca>".number_format($row->group_addmoney)."</font></b>���� <b>�߰�����</b>�� �帳�ϴ�.";
													else if($type=="RP") echo "���� �������� ".number_format($row->group_addmoney)."�踦 ������ �帳�ϴ�.";
													else if($type=="SW") echo "���űݾ��� ".number_format($row->group_addmoney)."���� <b>�߰�����</b>�� �帳�ϴ�.";
													else if($type=="SP") echo "���űݾ��� ".number_format($row->group_addmoney)."%�� <b>�߰�����</b>�� �帳�ϴ�.";
												}
												?>
												</span>
												<div style="margin-top:5px; font-size:0px;">
													<a href="/front/mypage_usermodify.php"><img src="<?=$Dir?>images/common/mypage/<?=$_data->design_mypage?>/btn_memedit.gif" border="0" alt="" /></a>
													<a href="/front/mypage_personal.php"><img src="<?=$Dir?>images/common/mypage/<?=$_data->design_mypage?>/btn_myqna.gif" border="0" alt="" /></a>
												</div>
											</td>
										</tr>
									</table>
									<!-- ȸ����� �� �߰�����/�����ȳ� END -->
								</td>
								<?
										}
									}
								?>

								<td valign="top" style="padding:20px;">
									<!-- SNS ä�ΰ��� START -->
									<table cellpadding="0" cellspacing="0" border="0" width="100%">
										<tr>
											<td colspan="3"><div class="mypage_list_title"><b>SNS ä�ΰ���</b><img src="<?=$Dir?>images/common/mypage/<?=$_data->design_mypage?>/mypage_mem_icon03.gif" border="0" /></div></td>
										</tr>

										<?
											if($_data->sns_ok == "Y" && strlen($_ShopInfo->getMemid())>0) {
												$sql = "select * from tblmembersnsinfo where id='".$_ShopInfo->getMemid()."' and state='Y'";
												$snsInfo = array();
												if(false !== $res = mysql_query($sql,get_db_conn())){
													while($srow = mysql_fetch_assoc($res)){
														$snsInfo[$srow['type']] = $srow;
													}
												}
										?>
										<tr>
											<td>
												<table border="0" cellpadding="0" cellspacing="0">
													<tr>
														<td><? if(TWITTER_ID !="TWITTER_ID"){?><img src="../images/design/icon_twitter_<?=(!empty($snsInfo['t']['oauth_token']) && !empty($snsInfo['t']['oauth_token2']))?'on':'off'?>.gif" border="0" align="absmiddle" id="twLoginBtn"><? } ?></td>
														<td><? if(FACEBOOK_ID!="FACEBOOK_ID"){?><img src="../images/design/icon_facebook_<?=(!empty($snsInfo['t']['oauth_token']))?'on':'off'?>.gif" border="0" align="absmiddle" hspace="3" id="fbLoginBtn"><?}?></td>
														<!--<td><?// if(ME2DAY_ID!="ME2DAY_ID"){?><img src="../images/design/icon_me2day_off.gif" border="0" align="absmiddle" id="meLoginBtn"><?//}?></td>-->
														<td><a href="mypage_promote.php"><img src="<?=$Dir?>images/common/mypage/<?=$_data->design_mypage?>/mypage_sns_icon01.gif" border="0" align="absmiddle" hspace="3" alt="SNSä�ΰ���"></a></td>
														<? if($_data->recom_url_ok == "Y" ){?>
														<td><a href="../front/member_urlhongbo.php"><img src="<?=$Dir?>images/common/mypage/<?=$_data->design_mypage?>/mypage_sns_icon02.gif" border="0" align="absmiddle"></a></td>
														<?}?>
													</tr>
													<tr><td height="8"></td></tr>
												</table>
											</td>
										</tr>
										<?
											}

											if($_data->recom_url_ok == "Y" ){
												if(strlen($_ShopInfo->getMemid())>0) {
													$arRecomType = explode("", $_data->recom_memreserve_type);
													$sAddRecom = "";
													if($arRecomType[0] == "A"){
														$sAddRecom = " <font color=\"#CC0000\">�� URL</font>�� ���� �ű�ȸ�� ���Խ� ȸ���Բ� <u>".$_data->recom_memreserve."���� ������ ����</u>";
													}else if($arRecomType[0] == "B"){
														$sAddRecom = "<font color=\"#CC0000\">�� URL</font>�� ���� ������ ȸ���� ù ���Ű� �̷������ <u>";
														if($arRecomType[1] == "A"){
															if($arRecomType[2] == "N"){
																$sAddRecom .= $_data->recom_memreserve."����";
															}else if($arRecomType[2] == "Y"){
																$sAddRecom .= "���űݾ��� ".$_data->recom_memreserve."%��";
															}
														}else if($arRecomType[1] == "B"){
															$sAddRecom .= "���űݾ׿� ����";
														}
														$sAddRecom .= " ������</u>�� ȸ���Բ� ����";
													}

													$sql = "SELECT COUNT(*) as cnt FROM tblmember WHERE rec_id='".$_ShopInfo->getMemid()."'";
													$result = mysql_query($sql,get_db_conn());
													$row = mysql_fetch_object($result);
													$recom_cnt = $row->cnt;
													mysql_free_result($result);
										?>
										<tr>
											<td style="letter-spacing:-1px;">
												<b>��</b> �� URL �ּ� : <font style="letter-spacing:0px;">http://<?=$_ShopInfo->getShopurl()?>?token=<?=$url_id?></font><br>
												<b>��</b> �� URL �ű� ȸ������ : <strong style="letter-spacing:0px;"><?=$recom_cnt?></strong>�� [������ ���ʽ� : <strong style="letter-spacing:0px;"><?=$_data->recom_memreserve?></strong>��]
											</td>
										</tr>
										<tr>
											<td style="color:#999999; padding-left:8px;">- <?=$sAddRecom ?></td>
										</tr>
										<?
												}
											}
										?>
									</table>
									<!-- SNS ä�ΰ��� END -->
								</td>
							</tr>

							<tr>
								<td colspan="2" style="padding:20px; border-top:1px solid #eeeeee;">
									<table width="100%" cellpadding="0" cellspacing="0" border="0">
										<tr>
											<td>
												<div class="mypage_list_title"><b>��� �߰�����</b><img src="<?=$Dir?>images/common/mypage/<?=$_data->design_mypage?>/mypage_mem_icon03.gif" border="0"></div>
												<?
													if($nginfo !== false){ ?>
													<div style="background:#f5f5f5; height:30px; line-height:30px; border:1px solid #e8e8e8; padding-left:10px; color:#666666;">
														<span style="color:#339c09; font-weight:bold;"><?=$nginfo['group_name']?></span> ȸ������ ���� ���űݾ� : <span style="color:#333333; font-weight:bold;"><?=number_format($gconfig['check']['rprice'])?>��</span> &nbsp;|&nbsp; ���� ���ŰǼ� : <span style="color:#ff4400; font-weight:bold;"><?=number_format($gconfig['check']['rcnt'])?>��</span>
													</div>

													<ul style="line-height:16px; margin-top:5px;">
														<li style="width:100%;"><b>��</b> ���űⰣ(�������űݾ� �����Ⱓ) : <span style="color:#333333;"><?=date('Y.m.d',$gconfig['check']['start'])?> ~ <?=date('Y.m.d',$gconfig['check']['end'])?></span></li>
														<li style="width:100%;"><b>��</b> ���ŰǼ� : <span style="color:#333333;"><?=number_format($gconfig['check']['cnt'])?>��</span></li>
														<li style="width:100%;"><b>��</b> ��������Ⱓ : <span style="color:#333333;"><?=$gconfig['keepclass']?>����</span></li>
														<li style="width:100%;"><b>��</b> ���űݾ� : <span style="color:#333333;"><?=number_format($gconfig['check']['price'])?>��</span> (�߼ۿϷ�� ������Ʈ �˴ϴ�.)</li>
													</ul>
												<?	} ?>
											</td>
										</tr>
									</table>
								</td>
							</tr>

						</table>
					</td>
				</tr>
			</table>
			<!-- ȸ������ BOX END -->

			<table border="0" cellpadding="0" cellspacing="0" class="myOrderTbl">
				<tr>
					<th><span style="font-size:10px;">ORDER</span><br />�ֹ���Ȳ</th>
					<td>
						�ֹ���Ȳ<br />
						<a href="./mypage_orderlist.php"><strong><?=$ordercount?></strong>��</a>
					</td>
					<td>
						�߼��غ�<br />
						<a href="./mypage_orderlist.php"><strong><?=$deliready?></strong>��</a>
					</td>
					<td>
						�߼ۿϷ�<br />
						<a href="./mypage_orderlist.php"><strong><?=$delicomplate?></strong>��</a>
					</td>
					<th><span style="font-size:10px;">TAKE BACK/REFUND</span><br />��ǰ/ȯ�� ��Ȳ</th>
					<td>
						ȯ�ҽ�û<br />
						<a href="javascript:goOrderType('R');"><strong><?=$refund?></strong>��</a>
					</td>
					<td style="border:0px;">
						ȯ�ҿϷ�<br />
						<a href="javascript:goOrderType('R');"><strong><?=$repayment?></strong>��</a>
					</td>
				</tr>
			</table>

		</td>
	</tr>


	<tr><td height="40"></td></tr>
	<tr>
		<td width="152"><IMG SRC="<?=$Dir?>images/common/mypage/<?=$_data->design_mypage?>/mypage_skin3_text01.gif" border="0"></td>
	</tr>
	<tr>
		<td height="5"></td>
	</tr>
	<tr>
		<td>
			<table cellpadding="0" cellspacing="0" width="100%" border="0">
				<tr>
					<td><a href="#orderList_box" onclick="getOrderList(1);return false;"><img src="<?=$Dir?>images/common/mypage/<?=$_data->design_mypage?>/order_01.gif" align="absmiddle" id="orderList_type1" alt="�Ϲݻ�ǰ�ֹ�"></a></td>
					<td><a href="#orderList_box" onclick="getOrderList(2);return false;"><img src="<?=$Dir?>images/common/mypage/<?=$_data->design_mypage?>/order_02.gif" align="absmiddle" id="orderList_type2" alt="���������ֹ�"></a></td>
					<td><a href="#orderList_box" onclick="getOrderList(3);return false;"><img src="<?=$Dir?>images/common/mypage/<?=$_data->design_mypage?>/order_03.gif" align="absmiddle" id="orderList_type3" alt="��ǰ���ֹ�"></a></td>
					<td width="100%" align="right"><A HREF="<?=$Dir.FrontDir?>mypage_orderlist.php"><IMG SRC="<?=$Dir?>images/common/mypage/<?=$_data->design_mypage?>/mypage_skin3_btn01.gif" BORDER="0" alt="��ü����"></A></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>

<!-- �Ϲݻ�ǰ �ֹ�(�ֱ� �ֹ�����) START -->
		<table cellpadding="0" cellspacing="0" width="100%" border="0" bgcolor="E7E7E7" style="table-layout:fixed" id="list01">
		<!-- �ֹ�����, �ֹ� ��ǰ��, ��ۻ���, �������, �������, �����ݾ�, ������  -->
		<col width="180"></col>
		<col></col>
		<col width="80"></col>
		<col width="80"></col>
		<col width="80"></col>
		<tr>
			<td height="2" colspan="5" bgcolor="#666666"></td>
		</tr>
		<tr height="30" align="center" bgcolor="#F8F8F8">
			<td align="left" style="padding-left:15px;" class="mypage_list_title">�ֹ���(��������)</td>
			<td class="mypage_list_title">��ǰ��/�ɼ�</td>
			<td class="mypage_list_title">�ֹ�����</td>
			<td class="mypage_list_title">�������</td>
			<td class="mypage_list_title">��ǰ��</td>
		</tr>
		<tr>
			<td height="1" colspan="5" bgcolor="#DDDDDD"></td>
		</tr>
<?
		$delicomlist=getDeliCompany();
		$orderlists = getMyOrderList(5);
		$returnableCnt = 0;
		if($orderlists['total'] < 1){ ?>
		<tr height=40><td colspan=5 align=center bgcolor=#FFFFFF>�ֱ� 1���� �̳��� �����Ͻ� ������ �����ϴ�.</td></tr>
		<tr><td height=1 colspan=5 bgcolor=#999999></td></tr>
<?		}else{
			$idx=0;
			foreach($orderlists['orders'] as $row){
				/*
				if($idx == 5){
					break;
				}
				*/
				$orderproducts = array();
				$orderproducts = getOrderProduct($row->ordercode);
		?>

		<tr bgcolor="#FFFFFF" onmouseover="this.style.background='#ffffff'" onmouseout="this.style.background='#FFFFFF'">
			<td class="mypage_order_line" valign="top" style="padding-top:10; padding-bottom:10;">
				<table border="0" cellpadding="0" cellspacing="0" width="100%">
					<tr><td height="30" class="mypage_order_line2"><b><?=substr($row->ordercode,0,4)?>/<?=substr($row->ordercode,4,2)?>/<?=substr($row->ordercode,6,2)?></b></td></tr>
					<tr><td height=5></td></tr>
					<tr><td class="mypage_list_cont">������� : <?=getPaymethodStr($row->paymethod)?></td></tr>
					<tr><td class="mypage_list_cont">�����ݾ� : <b><font color="#000000"><?=number_format($row->price)?></font></b>��</td></tr>
					<tr><td height=5></td></tr>
					<tr><td class="mypage_list_cont"><A HREF="javascript:OrderDetailPop('<?=$row->ordercode?>')" onmouseover="window.status='�ֹ�������ȸ';return true;" onmouseout="window.status='';return true;"><img src="<?=$Dir?>images/common/mypage/<?=$_data->design_mypage?>/mypage_order_icon01.gif" alt="�ֹ� ������" /></a>
					<?
					/*
					if (preg_match("/^(B){1}/", $row->paymethod) && strlen($row->bank_date)<12 && $row->deli_gbn=="N") {
						echo "<br/><a href=\"javascript:order_cancel('".$row->tempkey."', '".$row->ordercode."','".$row->bank_date."')\" onMouseOver=\"window.status='�ֹ����';return true;\"><img src=\"".$Dir."images/common/orderdetailpop_ordercancel.gif\" align=absmiddle border=0></a>\n";
					}
					*/
					?>
					</td></tr>
				</table>
			</td>
			<td colspan=4>
				<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
				<col></col>
				<col width=80></col>
				<col width=80></col>
				<col width=80></col>
	<?
				$chkbox_count = 0;
				$cnt = count($orderproducts);
				for($jj=0;$jj < $cnt;$jj++){
					$row2 = $orderproducts[$jj];
					if($jj>0) echo '<tr><td colspan=4 height=1 bgcolor=#E5E5E5></tr>';
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
	?>
					<tr>
						<td style="padding:10px;" class="mypage_list_cont">
							<div style="width:25px;float:left;text-align:left;">
							<?
								if ($row->deli_gbn!="C" && !($row->pay_admin_proc=="C" && $row->pay_flag=="0000") && count($orderproducts)>1 && $row2->status=='') {?>
								<input type="checkbox" name="chk_<?= $row->ordercode ?>" id="chk_<?= $row->ordercode ?>_<?= $jj ?>" value="<?=$row2->productcode?>"/>
								<input type="hidden" name="chk_uid_<?= $row->ordercode ?>" id="chk_uid_<?= $row->ordercode ?>_<?= $jj ?>" value="<?=$row2->uid?>"/>
							<?
								$chkbox_count++;
								}
							?>
							</div>
							<div>
								<?
									$reservation = "";
									if( $row2->reservation != "0000-00-00" ) {
										$reservation = "[�����ۻ�ǰ(��ۿ�����:".$row2->reservation.")]<br />";
									}
								?>
								<A HREF="javascript:OrderDetailProduct('<?=$row->ordercode?>','<?=$row2->productcode?>')" onmouseover="window.status='�ֹ�������ȸ';return true;" onmouseout="window.status='';return true;"><img src="<?=(strlen($row2->tinyimage)>0 && file_exists($Dir.DataDir."shopimages/product/".$row2->tinyimage)==true)?$Dir.DataDir.'shopimages/product/'.urlencode($row2->tinyimage):$Dir."images/no_img.gif"?>" border="0" width="50" style="float:left;margin-right:5px;"/><?=$reservation?><?=$row2->productname?></a>
								<?
								if(!_empty($optvalue)) 	echo "<br><img src=\"".$Dir."images/common/icn_option.gif\" border=0 align=absmiddle> ".$optvalue."";
								?>
								</td>
								<td align="center" class="mypage_list_cont2"><font color="#000000"><? echo orderProductDeliStatusStr($row2,$row, $cnt); ?></font></td>
								<td align=center style="font-size:8pt;padding-top:3;">
								<?
								$deli_link = '-';
								$deli_url="";
								$trans_num="";
								$company_name="";
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
									}
								}
								echo $deli_link;
								?>
							</div>
						</td>
						<td align="center"><? if($row2->deli_gbn=="Y" && $row2->status==""  && $_data->review_type !="N")  { ?><!-- <A HREF="javascript:OrderReview('<?=$row->ordercode?>','<?=$row2->productcode?>')" onmouseover="window.status='��ǰ��';return true;" onmouseout="window.status='';return true;"> -->
						<A HREF="javascript:OrderReview('<?=$row2->productcode?>')"><img src="<?=$Dir?>images/common/mypage/<?=$_data->design_mypage?>/mypage_order_icon04.gif" alt="��ǰ���ۼ�" /></a><? }else{ ?><img src="<?=$Dir?>images/common/mypage/<?=$_data->design_mypage?>/mypage_order_icon04_off.gif" alt="��ǰ���ۼ�" /><? } ?></td>
					</tr>
				<?	} // end for $jj ?>

				<? if ($row->deli_gbn!="C" && !($row->pay_admin_proc=="C" && $row->pay_flag=="0000") && $chkbox_count>0 && count($orderproducts)>1) {?>
				<tr><td colspan=4 height=1 bgcolor=#E5E5E5></tr>
				<tr>
					<td style="background-color:#f8f8f8; padding:10px;" class="mypage_list_cont">
						<div style="width:25px;float:left;text-align:left;"><input type="checkbox" name="chk_<?= $row->ordercode ?>_all" id="chk_<?= $row->ordercode ?>_all" value="all" onclick="productAll('chk_<?= $row->ordercode ?>')"/></div>
						<div style="width:85px;float:left;padding-top:3px;"><b>��ü����</b></div>
						<div style="float:left;padding-top:3px;"><b> - ���û�ǰ �ֹ���� ��û</b></div>
					</td>
					<td style="background-color:#f8f8f8;" align="center"><span style="font-size:0px; cursor:pointer"
					<? if (strlen($row->bank_date)<12 && preg_match("/^(B|O|Q){1}/", $row->paymethod)) { ?>
						onclick="order_one_cancel('<?= $row->ordercode ?>', '', 'NO', '<?= $row->tempkey ?>')"
					<? }else{ ?>
						onclick="order_multi_cancel('<?=$row->ordercode?>', <?=$cnt?>, '<?= $row->tempkey ?>', '<?= $row->bank_date?>')"
					<? } ?>
					><img src="<?=$Dir?>images/common/mypage/<?=$_data->design_mypage?>/mypage_order_cancel_icon02.gif" alt="Ȯ��" /></span></td>
					<td style="background-color:#f8f8f8;">&nbsp;</td>
					<td style="background-color:#f8f8f8;">&nbsp;</td>
				</tr>
				<? } ?>

				</table>
			</td>
		</tr>
		<tr><td colspan=5 height=1 bgcolor=#999999></td></tr>
		<?
		$idx++;
				} // end foreach
		if($returnableCnt > 0){ ?>
		<tr><td height=30 colspan=6 bgcolor="#FFFFFF" align="right" style="padding-right:10"><a href=#  onclick="return refund1();"><IMG SRC="<?=$Dir?>images/common/mypage/<?=$_data->design_mypage?>/mypage_order_icon02.gif" BORDER=0 hspace="4" alt="���ðǿ� ���� ��ȯ��û"><a href=#  onclick="return refund2();"><IMG SRC="<?=$Dir?>images/common/mypage/<?=$_data->design_mypage?>/mypage_order_icon03.gif" BORDER=0 alt="���ðǿ� ���� ȯ�ҽ�û"></a></td></tr>
		<tr><td colspan=5 height=1 bgcolor=#E7E7E7></td></tr>
		<? } ?>
	<? } // end if ?>
		</table>
<!-- �Ϲݻ�ǰ �ֹ�(�ֱ� �ֹ�����) END -->

<!-- �������� �ֹ�(�ֱ� �ֹ�����) START -->
		<table cellpadding="0" cellspacing="0" width="100%" border="0" bgcolor="E7E7E7" style="table-layout:fixed;display:none;" id="list02">
		<!-- �ֹ�����, �ֹ� ��ǰ��, ��ۻ���, �������, �������, �����ݾ�, ������  -->
		<col width="180"></col>
		<col></col>
		<col width="80"></col>
		<col width="80"></col>
		<col width="80"></col>
		<tr>
			<td height="2" colspan="5" bgcolor="#666666"></td>
		</tr>
		<tr height="30" align="center" bgcolor="#F8F8F8" style="letter-spacing:-0.5pt;">
			<td align="left" style="padding-left:15px;" class="mypage_list_title">�ֹ���(��������)</td>
			<td class="mypage_list_title">��ǰ��/�ɼ�</td>
			<td class="mypage_list_title">�ֹ�����</td>
			<td class="mypage_list_title">�������</td>
			<td class="mypage_list_title">��ǰ��</td>
		</tr>
		<tr>
			<td height="1" colspan="5" bgcolor="#DDDDDD"></td>
		</tr>
<?
		$orderlists = getMyOrderList(2,'2');
		$returnableCnt = 0;

		if($orderlists['total'] < 1){ ?>
		<tr height=40><td colspan=5 align=center bgcolor=#FFFFFF>�ֱ� 1���� �̳��� �����Ͻ� ������ �����ϴ�.</td></tr>
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
					<tr><td class="mypage_list_cont">������� : <?=getPaymethodStr($row->paymethod)?></td></tr>
					<tr><td class="mypage_list_cont">�����ݾ� : <font color="#000000"><b><?=number_format($row->price)?></b></font>��</td></tr>
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
	?>

					<tr>
						<td style="padding:10px; ine-height:11pt"><A HREF="javascript:OrderDetailPop('<?=$row->ordercode?>')" onmouseover="window.status='�ֹ�������ȸ';return true;" onmouseout="window.status='';return true;"><?=$row2->productname?></a></td>
						<td align=center class="mypage_list_cont2"><font color="#000000"><? echo orderProductDeliStatusStr($row2,$row, $cnt); ?></font></td>
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
							}
						}
						echo $deli_link;
						?>
						</td>
						<td align="center"><? if($row2->deli_gbn=="Y" && $_data->review_type !="N")  { ?><!-- <A HREF="javascript:OrderReview('<?=$row->ordercode?>','<?=$row2->productcode?>')" onmouseover="window.status='��ǰ��';return true;" onmouseout="window.status='';return true;"> -->
						<A HREF="javascript:OrderReview('<?=$row2->productcode?>')"><img src="<?=$Dir?>images/common/mypage/<?=$_data->design_mypage?>/mypage_order_icon04.gif" alt="��ǰ���ۼ�" /></a><? }else{ ?><img src="<?=$Dir?>images/common/mypage/<?=$_data->design_mypage?>/mypage_order_icon04_off.gif" alt="��ǰ���ۼ�" /><? } ?></td>
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
<!-- �������� �ֹ�(�ֱ� �ֹ�����) END -->


<!-- ��ǰ�� �ֹ�(�ֱ� �ֹ�����) START -->
		<table cellpadding="0" cellspacing="0" width="100%" border="0" bgcolor="E7E7E7" style="table-layout:fixed;display:none;" id="list03">
		<!-- �ֹ�����, �ֹ� ��ǰ��, ��ۻ���, �������, �������, �����ݾ�, ������  -->
		<col width="180"></col>
		<col></col>
		<col width="80"></col>
		<col width="80"></col>
		<col width="80"></col>
		<tr>
			<td height="2" colspan="5" bgcolor="#666666"></td>
		</tr>
		<tr height="30" align="center" bgcolor="#F8F8F8" style="letter-spacing:-0.5pt;">
			<td align="left" style="padding-left:15px;" class="mypage_list_title">�ֹ���(��������)</td>
			<td class="mypage_list_title">��ǰ��/�ɼ�</td>
			<td class="mypage_list_title">ó������</td>
			<td class="mypage_list_title">������ȣ</td>
			<td class="mypage_list_title">��ǰ��</td>
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
			echo "<tr><td class=mypage_list_cont>������� : ";

			if (preg_match("/^(B){1}/",$row->paymethod)) echo "�������Ա�";
			else if (preg_match("/^(V){1}/",$row->paymethod)) echo "�ǽð�������ü";
			else if (preg_match("/^(O){1}/",$row->paymethod)) echo "�������";
			else if (preg_match("/^(Q){1}/",$row->paymethod)) echo "�������-<FONT COLOR=\"red\">�Ÿź�ȣ</FONT>";
			else if (preg_match("/^(C){1}/",$row->paymethod)) echo "�ſ�ī��";
			else if (preg_match("/^(P){1}/",$row->paymethod)) echo "�ſ�ī��-<FONT COLOR=\"red\">�Ÿź�ȣ</FONT>";
			else if (preg_match("/^(M){1}/",$row->paymethod)) echo "�޴���";
			else echo "";

			echo "</td></tr>";
			echo "<tr><td class=mypage_list_cont>�����ݾ� : <font color=#000000><b>".number_format($row->price)."</b></font>��</td></tr>";
			echo "<tr><td height=5></td></tr>";
			echo "<tr><td class=mypage_list_cont><A HREF=\"javascript:OrderDetailPop('".$row->ordercode."')\" onmouseover=\"window.status='�ֹ�������ȸ';return true;\" onmouseout=\"window.status='';return true;\"><img src=".$Dir."images/common/mypage/".$_data->design_mypage."/mypage_order_icon01.gif alt=�ֹ�������></a></td></tr>";
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
				if($jj>0) echo "<tr><td colspan=4 height=1 bgcolor=#F5F5F5></tr>";
				echo "<tr>\n";
				echo "	<td style=padding:10px; ine-height:11pt;><A HREF=\"javascript:OrderDetailPop('".$row->ordercode."')\" onmouseover=\"window.status='�ֹ�������ȸ';return true;\" onmouseout=\"window.status='';return true;\">".$row2->productname."</a></td>";
				echo "	<td align=center class=mypage_list_cont2>";
				if ($row2->deli_gbn=="C") echo "�ֹ����";
				else if ($row2->deli_gbn=="D") echo "��ҿ�û";
				else if ($row2->deli_gbn=="E") echo "ȯ�Ҵ��";
				else if ($row2->deli_gbn=="X") {
					if($row->gift=='1') {
						$sql3 = "SELECT * FROM tblgift_info WHERE ordercode='{$row->ordercode}'";
						$result3=mysql_query($sql3,get_db_conn());
						$row3 = mysql_fetch_array($result3);
						mysql_free_result($result3);
						echo "������ȣ�߼�";
					}
					else "�߼��غ�";
				}
				else if ($row2->deli_gbn=="Y") {
					if($row->gift=='1') {
						$sql3 = "SELECT * FROM tblgift_info WHERE ordercode='{$row->ordercode}'";
						$result3=mysql_query($sql3,get_db_conn());
						$row3 = mysql_fetch_array($result3);
						mysql_free_result($result3);
						echo "�����������Ϸ�";
					}
					else if($row->gift=='2') echo "�����Ϸ�";
					else echo "�߼ۿϷ�";
				}
				else if ($row2->deli_gbn=="N") {
					if (strlen($row->bank_date)<12 && preg_match("/^(B|O|Q){1}/", $row->paymethod)) echo "�Ա�Ȯ����";
					else if ($row->pay_admin_proc=="C" && $row->pay_flag=="0000") echo "�������";
					else if (strlen($row->bank_date)>=12 || $row->pay_flag=="0000") echo "�߼��غ�";
					else echo "����Ȯ����";
				} else if ($row2->deli_gbn=="S") {
					echo "�߼��غ�";
				} else if ($row2->deli_gbn=="R") {
					echo "�ݼ�ó��";
				} else if ($row2->deli_gbn=="H") {
					echo "�߼ۿϷ� [���꺸��]";
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
				//echo "	<td align=center><A HREF=\"javascript:OrderDetailPop('".$row->ordercode."')\" onmouseover=\"window.status='�ֹ�������ȸ';return true;\" onmouseout=\"window.status='';return true;\"><img src=".$Dir."images/common/mypage/".$_data->design_mypage."/mypage_order_icon01.gif></a></td>";
?>
							<td align="center"><? if($row2->deli_gbn=="Y" && $_data->review_type !="N")  { ?><!-- <A HREF="javascript:OrderReview('<?=$row->ordercode?>','<?=$row2->productcode?>')" onmouseover="window.status='��ǰ��';return true;" onmouseout="window.status='';return true;"> -->
							<A HREF="javascript:OrderReview('<?=$row2->productcode?>')"><img src="<?=$Dir?>images/common/mypage/<?=$_data->design_mypage?>/mypage_order_icon04.gif" alt="��ǰ���ۼ�" /></a><? }else{ ?><img src="<?=$Dir?>images/common/mypage/<?=$_data->design_mypage?>/mypage_order_icon04_off.gif" alt="��ǰ���ۼ�" /><? } ?></td>
<?
				echo "</tr>\n";
				$jj++;
			}
			mysql_free_result($result2);
			echo "	</table>\n";
			echo "	</td>\n";
			echo "</tr>\n";
			echo "<tr><td colspan=5 height=1 bgcolor=#999999></td></tr>\n";
			$cnt++;
		}
		mysql_free_result($result);

		if ($cnt==0) {
			echo "<tr height=40><td colspan=5 align=center bgcolor=#FFFFFF>�ֱ� 1���� �̳��� �����Ͻ� ������ �����ϴ�.</td></tr>";
			echo "<tr><td height=1 colspan=5 bgcolor=#999999></td></tr>";
		}
?>
		</table>
<!-- ��ǰ�� �ֹ�(�ֱ� �ֹ�����) END -->


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
<?
	if($_data->personal_ok=="Y") {	//1:1���Խ����� ������̶��,,,,,
?>
	<tr>
		<td height="40"></td>
	</tr>
	<tr>
		<td>
		<!-- �ֱٹ���(1:1) ���� -->
		<table cellpadding="0" cellspacing="0" width="100%">
		<tr>
			<td height="31"><IMG SRC="<?=$Dir?>images/common/mypage/<?=$_data->design_mypage?>/mypage_skin3_text02.gif" border="0"></td>
			<td align="right" style="padding-bottom:3px;"><A HREF="<?=$Dir.FrontDir?>mypage_personal.php"><IMG SRC="<?=$Dir?>images/common/mypage/<?=$_data->design_mypage?>/mypage_skin3_btn01.gif" BORDER="0" alt="��ü����"></A></td>
		</tr>
		</table>
		</td>
	</tr>
	<tr>
		<td>
		<table cellpadding="0" cellspacing="0" width="100%">
		<col width="140"></col>
		<col></col>
		<col width="65"></col>
		<col width="105"></col>
		<tr>
			<td height="2" colspan="4" bgcolor="#666666"></td>
		</tr>
		<tr height="30" align="center" bgcolor="#F8F8F8" style="letter-spacing:-0.5pt;">
			<td class="mypage_list_title">��������</td>
			<td class="mypage_list_title">����</td>
			<td class="mypage_list_title">�亯����</td>
			<td class="mypage_list_title">�亯����</td>
		</tr>
		<tr>
			<td height="1" colspan="4" bgcolor="#DDDDDD"></td>
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
			if($cnt>0) echo "<tr><td height=\"1\" colspan=\"4\" bgcolor=\"#DDDDDD\"></td></tr>\n";

			echo "<tr height=\"28\" align=\"center\">\n";
			echo "	<td><font color=\"#333333\">".$date."</font></td>\n";
			echo "	<td align=\"left\"><A HREF=\"javascript:ViewPersonal('".$row->idx."')\"><font style=\"color:#333333;\">".strip_tags($row->subject)."</font></A></td>\n";
			echo "	<td>";
			if(strlen($row->re_date)==14) {
				echo "<img src=\"".$Dir."images/common/mypersonal_skin_icon1.gif\" border=\"0\" align=\"absmiddle\">";
			} else {
				echo "�亯���";
			}
			echo "	</td>\n";
			echo "	<td><font color=\"#333333\">".$re_date."</font></td>\n";
			echo "</tr>\n";
			$cnt++;
		}
		mysql_free_result($result);
		if ($cnt==0) {
			echo "<tr height=\"30\"><td colspan=\"4\" align=\"center\">���ǳ����� �����ϴ�.</td></tr>";
		}
?>
		<tr>
			<td height="1" colspan="4" bgcolor="#DDDDDD"></td>
		</tr>
		</table>
		</td>
	</tr>
<?
	}
?>
	<tr>
		<td height="40"></td>
	</tr>
	<tr>
		<td>
		<!-- ���ɻ�ǰ(wish list) ��� -->
		<table cellpadding="0" cellspacing="0" width="100%">
		<tr>
			<td height="31"><IMG SRC="<?=$Dir?>images/common/mypage/<?=$_data->design_mypage?>/mypage_skin3_text03.gif" border="0"></td>
			<td align="right" style="padding-bottom:3px;"><A HREF="<?=$Dir.FrontDir?>wishlist.php"><IMG SRC="<?=$Dir?>images/common/mypage/<?=$_data->design_mypage?>/mypage_skin3_btn01.gif" BORDER="0" alt="��ü����"></A></td>
		</tr>
		</table>
		</td>
	</tr>
	<tr>
		<td>
		<table cellpadding="0" cellspacing="0" width="100%">
		<tr>
			<td height="2" bgcolor="#666666"></td>
		</tr>
		<tr>
			<td height="15"></td>
		</tr>
		<tr>
			<td>
			<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td>
				<table cellpadding="2" cellspacing="0" width="100%">
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

					// ������ ǥ��
					$discountRate = ( $row->discountRate > 0 ) ? "<strong>".$row->discountRate."</strong>%��" : "";

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
					echo "<A HREF=\"".$Dir.FrontDir."productdetail.php?productcode=".$row->productcode.$add_query."\" onmouseover=\"window.status='��ǰ����ȸ';return true;\" onmouseout=\"window.status='';return true;\">";
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
					echo "	<td style=\"padding:5px 7px; word-break:break-all;\"><A HREF=\"".$Dir.FrontDir."productdetail.php?productcode=".$row->productcode.$add_query."\" onmouseover=\"window.status='��ǰ����ȸ';return true;\" onmouseout=\"window.status='';return true;\"><FONT class=\"prname\">".viewproductname($row->productname,$row->etctype,$row->selfcode)."</FONT></A></td>\n";
					echo "</tr>";


					//���߰� + �ǸŰ� + ������ + ȸ�����ΰ�
					echo "
						<tr>
							<td style=\"padding:0px 7px 7px 7px; word-break:break-all;\">
								<table border=0 cellpadding=0 cellspacing=0 width=100%>
									<tr>
										<td>
					";
					if($row->consumerprice!=0) {
						echo "<span class=\"mainconprice\"><strike>".number_format($row->consumerprice)."��</strike></span>\n";
					}

					echo "<span style=\"white-space:nowrap;\">";
					if($dicker=dickerview($row->etctype,"<strong class=\"mainprprice\">".number_format($row->sellprice)."��</strong>",1)) {
						echo $strikeStart.$dicker.$strikeEnd;
					} else if(strlen($_data->proption_price)==0) {
						echo "<strong class=\"mainprprice\">".number_format($row->sellprice)."��</strong>";
						if (strlen($row->option_price)!=0) echo "<FONT color=\"#FF0000\">(�ɼǺ���)</FONT>";
					} else {
						//echo "<img src=\"".$Dir."images/common/won_icon3.gif\" border=\"0\" align=\"absmiddle\"> ";
						if (strlen($row->option_price)==0) echo "<strong class=\"mainprprice\">".number_format($row->sellprice)."��</strong>";
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

					//ȸ�����ΰ� ����
					if( $memberprice > 0 ) {
						echo "<div><span class=\"prprice\">".dickerview($row->etctype,$memberprice)."��</span> <img src=\"".$Dir."images/common/memsale_icon.gif\" align=\"absmiddle\" alt=\"\" /></div>\n";
					}

					if ($row->quantity=="0") echo soldout(1);

					$reserveconv=getReserveConversion($row->reserve,$row->reservetype,$row->sellprice,"Y");
					if($reserveconv>0) {
						echo "<div style=\"margin-top:5px;\"><img src=\"".$Dir."images/common/reserve_icon.gif\" border=\"0\" style=\"vertical-ailgn:middle;\" /> <span class=\"mainreserve\">".number_format($reserveconv)."</span>��</div>\n";
					}
					echo "</td>";
					echo "</tr>";

					// ������ ������
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
						$venderNameTag .= "	<a href=\"javascript:GoMinishop('/minishop.php?storeid=".$v_info['id']."')\"><img src=\"/images/common/icon_vender_go.gif\" border=\"0\" alt=\"��ü��ǰ����\" /></a>";
						$venderNameTag .= "</div>";

						// ������ ���
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
					echo "<td height=\"30\" colspan=\"9\" align=\"center\">WishList�� ��� ��ǰ�� �����ϴ�.</td>";
				}

?>
				</tr>
				</TABLE>
				</td>
			</tr>
			</table>
			</td>
		</tr>
		<tr><td height="40"></td></tr>

<?
	//�̸��� & SMS ���ŵ��� Ȯ��
	if($_mdata->news_yn=="Y") {
		$news_mail_yn="Y";
		$news_sms_yn="Y";
	} else if($_mdata->news_yn=="M") {
		$news_mail_yn="Y";
		$news_sms_yn="N";
	} else if($_mdata->news_yn=="S") {
		$news_mail_yn="N";
		$news_sms_yn="Y";
	} else if($_mdata->news_yn=="N") {
		$news_mail_yn="N";
		$news_sms_yn="N";
	}

	//���� �α��� ���� Ȯ��
	$loginYear = substr($_mdata->logindate,0,4);
	$loginMonth = substr($_mdata->logindate,4,2);
	$loginDay = substr($_mdata->logindate,6,2);
	$loginHour = substr($_mdata->logindate,8,2);
	$loginMinute = substr($_mdata->logindate,10,2);
	$loginSec = substr($_mdata->logindate,12,2);
?>

		<tr>
			<td>

				<div class="recInfoDivLeft">
					<div style="height:27px; background:#fbfbfb; border-bottom:1px solid #edeeef;">
						<div style="float:left; padding:5px 15px; font-size:11px; color:#8f8f8f;">�޴��� : <span style="color:#444444; font-weight:bold;"><?=$_mdata->mobile?></span></div>
						<div style="float:right; padding:5px 15px; font-size:11px; color:#8f8f8f;">SMS���� : <span style="color:#444444;">
						<?
							if($news_mail_yn=="Y"){
								echo "���ŵ���";
							}else{
								echo "���Űź�";
							}
						?>
						</span>
						</div>
					</div>
					<div style="padding:7px 15px;">
						<span style="font-size:11px; color:#8f8f8f;">������ �α��� : <?=$loginYear?>/<?=$loginMonth?>/<?=$loginDay?> <?=$loginHour?>:<?=$loginMinute?>:<?=$loginSec?></span>
						<a href="/front/mypage_usermodify.php">[��������]</a>
					</div>
				</div>

				<div class="recInfoDivRight">
					<div style="height:27px; background:#fbfbfb; border-bottom:1px solid #edeeef;">
						<div style="float:left; padding:5px 15px; font-size:11px; color:#8f8f8f;">�̸��� : <span style="color:#444444; font-weight:bold;"><?=$_mdata->email?></span></div>
						<div style="float:right; padding:5px 15px; font-size:11px; color:#8f8f8f;">��������</span> : <span style="color:#444444;">
							<?
								if($news_sms_yn=="Y"){
									echo "���ŵ���";
								}else{
									echo "���Űź�";
								}
							?>
							</span>
						</div>
					</div>
					<div style="padding:7px 15px;">
						<span style="font-size:11px; color:#8f8f8f;">������ �α��� : <?=$loginYear?>/<?=$loginMonth?>/<?=$loginDay?> <?=$loginHour?>:<?=$loginMinute?>:<?=$loginSec?></span>
						<a href="/front/mypage_usermodify.php">[��������]</a>
					</div>
				</div>

			</td>
		</tr>
		</table>
		</td>
	</tr>
	<tr><td height="20"></td></tr>
</table>

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