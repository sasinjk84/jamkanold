<?
	//���ø� Ÿ�� 000
	$_data->design_mypage = '000';
?>

<table cellpadding="0" cellspacing="0" width="100%">
<tr>
	<td style="padding:5px;padding-top:0px;">
	<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td valign="bottom">
		<TABLE WIDTH="100%" BORDER="0" CELLPADDING="0" CELLSPACING="0">
		<TR>
			<TD><A HREF="<?=$Dir.FrontDir?>mypage.php"><IMG SRC="<?=$Dir?>images/common/mypersonal_skin3_menu1r.gif" BORDER="0"></A></TD>
			<TD><A HREF="<?=$Dir.FrontDir?>mypage_orderlist.php"><IMG SRC="<?=$Dir?>images/common/mypersonal_skin3_menu2.gif" BORDER="0"></A></TD>
			<TD><A HREF="<?=$Dir.FrontDir?>mypage_personal.php"><IMG SRC="<?=$Dir?>images/common/mypersonal_skin3_menu3.gif" BORDER="0"></A></TD>
			<TD><A HREF="<?=$Dir.FrontDir?>wishlist.php"><IMG SRC="<?=$Dir?>images/common/mypersonal_skin3_menu4.gif" BORDER="0"></A></TD>
			<TD><A HREF="<?=$Dir.FrontDir?>mypage_reserve.php"><IMG SRC="<?=$Dir?>images/common/mypersonal_skin3_menu5.gif" BORDER="0"></A></TD>
			<TD><A HREF="<?=$Dir.FrontDir?>mypage_coupon.php"><IMG SRC="<?=$Dir?>images/common/mypersonal_skin3_menu6.gif" BORDER="0"></A></TD>
			<?if($_data->recom_url_ok == "Y" || $_data->sns_ok == "Y"){?><TD><A HREF="<?=$Dir.FrontDir?>mypage_promote.php"><IMG SRC="<?=$Dir?>images/common/mypersonal_skin3_menu10.gif" BORDER="0"></A></TD><?}?>
			<TD><A HREF="<?=$Dir.FrontDir?>mypage_gonggu.php"><IMG SRC="<?=$Dir?>images/common/mypersonal_skin3_menu11.gif" BORDER="0"></A></TD>
			<? if(getVenderUsed()==true) { ?><TD><A HREF="<?=$Dir.FrontDir?>mypage_custsect.php"><IMG SRC="<?=$Dir?>images/common/mypersonal_skin3_menu9.gif" BORDER="0"></A></TD><? } ?>
			<TD><A HREF="<?=$Dir.FrontDir?>mypage_usermodify.php"><IMG SRC="<?=$Dir?>images/common/mypersonal_skin3_menu7.gif" BORDER="0"></A></TD>
			<TD><A HREF="<?=$Dir.FrontDir?>mypage_memberout.php"><IMG SRC="<?=$Dir?>images/common/mypersonal_skin3_menu8.gif" BORDER="0"></A></TD>
			<TD width="100%" background="<?=$Dir?>images/common/mypersonal_skin3_menubg.gif"></TD>
		</TR>
		</TABLE>
		</td>
	</tr>
	<tr>
		<td height="20"></td>
	</tr>




<?
	if(strlen($_ShopInfo->getMemid())>0 && strlen($_ShopInfo->getMemgroup())>0) {
		$arr_dctype=array("B"=>"����","C"=>"ī��","N"=>"");
		$sql = "SELECT a.name,b.group_code,b.group_name,b.group_payment,b.group_usemoney,b.group_addmoney ";
		$sql.= "FROM tblmember a, tblmembergroup b WHERE a.id='".$_ShopInfo->getMemid()."' AND b.group_code=a.group_code ";
		$result=mysql_query($sql,get_db_conn());
		
		if($row=mysql_fetch_object($result)) {
?>
	<tr>
		<td height="30" style="font-size:11px; letter-spacing:-0.5px;">��<font color="#444444"><b><?=$row->name?></b></font>��, �ݰ����ϴ�. ������ ������ ���������� Ȯ���� �� �ֽ��ϴ�.</td>
	</tr>
	<tr>
		<td>
			<!-- ȸ������ BOX START -->
			<table border="0" cellpadding="0" cellspacing="6" bgcolor="#eaeaea" width="100%">
				<tr>
					<td bgcolor="#ffffff">
						<table border="0" cellpadding="0" cellspacing="0" width="100%">
							<tr style="padding:20px;">
								<td valign="top">
									<!-- �� ���� �⺻���� START -->
									<table border="0" cellpadding="0" cellspacing="0">
										<tr>
											<td><img src="<?=$Dir?>images/common/mypage/<?=$_data->design_mypage?>/mypage_mem_icon02.gif"></td>
											<td width="20"></td>
											<td valign="top">
												<table width="100%" cellpadding="0" cellspacing="0" border="0">
													<tr>
														<td height="22" valign="top" colspan="3" class="mypage_list_title"><b>���� ����</b><img src="<?=$Dir?>images/common/mypage/<?=$_data->design_mypage?>/mypage_mem_icon03.gif" border="0"></td>
													</tr>
													<tr>
														<td style="color:#444444; font-size:11px;"><b>�ּ�</b></td>
														<td width="20" align="center" style="font-size:11px;">:</td>
														<td style="font-size:11px;"><?=str_replace("=","&nbsp;",$_mdata->home_addr)?></td>
													</tr>
													<tr>
														<td style="color:#444444; font-size:11px;"><b>��ȭ��ȣ</b></td>
														<td width="20" align="center" style="font-size:11px;">:</td>
														<td style="font-size:11px;"><?=$_mdata->home_tel?><?if(strlen($_mdata->mobile)>0)echo ", ".$_mdata->mobile;?></td>
													</tr>
													<tr>
														<td style="color:#444444; font-size:11px;"><b>�̸���</b></td>
														<td width="20" align="center" style="font-size:11px;">:</td>
														<td style="font-size:11px;"><A HREF="mailto:<?=$_mdata->email?>"><?=$_mdata->email?></A></td>
													</tr>
												</table>
											</td>
										</tr>
									</table>
									<!-- �� ���� �⺻���� END -->
								</td>
								<td width="45%" valign="top">
									<!-- ������/����/��ǰ�� ��ȣ���� START -->
									<table border="0" cellpadding="0" cellspacing="0">
										<tr>
											<td valign="top" style="padding-right:20px; border-right:1px solid #d5d5d5;">
												<table border="0" cellpadding="0" cellspacing="0">
													<tr>
														<td height="22" valign="top" class="mypage_list_title"><b>������</b><A HREF="<?=$Dir.FrontDir?>mypage_reserve.php"><img src="<?=$Dir?>images/common/mypage/<?=$_data->design_mypage?>/mypage_mem_icon03.gif" border="0"></a></td>
													</tr>
													<tr>
														<td class="mypage_mem_info"><?=number_format($_mdata->reserve)?>��</td>
													</tr>
													<tr>
														<td height="25" align="center" valign="bottom"><A HREF="<?=$Dir.FrontDir?>mypage_reserve.php"><img src="<?=$Dir?>images/common/mypage/<?=$_data->design_mypage?>/mypage_mem_icon05.gif" border="0" alt="�ڼ�������" /></a></td>
													</tr>
												</table>
											</td>
											<td valign="top" style="padding:0px 15px 0px 15px; border-right:1px solid #d5d5d5;">
												<table border="0" cellpadding="0" cellspacing="0">
													<tr>
														<td height="22" valign="top" class="mypage_list_title"><b>����</b><A HREF="<?=$Dir.FrontDir?>mypage_coupon.php"><img src="<?=$Dir?>images/common/mypage/<?=$_data->design_mypage?>/mypage_mem_icon03.gif" border="0"></a></td>
													</tr>
													<tr>
														<td align="center" class="mypage_mem_info"><?=number_format($coupon_cnt)?>��</td>
													</tr>
													<tr>
														<td height="25" align="center" valign="bottom"><A HREF="<?=$Dir.FrontDir?>mypage_coupon.php"><img src="<?=$Dir?>images/common/mypage/<?=$_data->design_mypage?>/mypage_mem_icon05.gif" border="0" alt="�ڼ�������" /></a></td>
													</tr>
												</table>
											</td>
											<td valign="top" style="padding:0px 10px 0px 15px;">
												<table border="0" cellpadding="0" cellspacing="0">
													<tr>
														<td height="22" valign="top" class="mypage_list_title"><b>��ǰ��</b><img src="<?=$Dir?>images/common/mypage/<?=$_data->design_mypage?>/mypage_mem_icon03.gif" border="0"></td>
													</tr>
													<tr>
														<td align="center"><A HREF="<?=$Dir.FrontDir?>mypage_auth.php"><img src="<?=$Dir?>images/common/mypage/<?=$_data->design_mypage?>/mypage_mem_icon04.gif" border="0" alt="�����ϱ�" /></a></td>
													</tr>
												</table>
											</td>
										</tr>
									</table>
									<!-- ������/����/��ǰ�� ��ȣ���� END -->
								</td>
							</tr>
							<tr><td height="1" colspan="2" bgcolor="#eaeaea"></td></tr>
							<tr style="padding:20px;">
								<td valign="top">
									<!-- ȸ����� �� �߰�����/�����ȳ� START -->
									<table border="0" cellpadding="0" cellspacing="0">
										<tr>
											<td><img src="<?=$Dir?>images/common/mypage/<?=$_data->design_mypage?>/mypage_mem_icon01.gif"></td>
											<td width="20"></td>
											<td style="font-size:11px; letter-spacing:-0.5px;">
												<?=$row->name?>���� ȸ������� [<b><font color="#3f77ca"><?=$row->group_name?></font></b>] �Դϴ�.<br>
<?if (substr($row->group_code,0,1)!="M") {?>
												<span style="line-height:160%; letter-spacing:-0.5px;"><?=$row->name?>���� <b><font color="#3f77ca"><?=number_format($row->group_usemoney)?></font></b>�� �̻� <?=$arr_dctype[$row->group_payment]?>���Ž� 
<?
	$type=substr($row->group_code,0,2);

	if($type=="RW") echo "<b><font color=#3f77ca>".number_format($row->group_addmoney)."</font></b>����<br><b>�߰�����</b>�� �帳�ϴ�.";
	else if($type=="RP") echo "���� �������� ".number_format($row->group_addmoney)."�踦<br>������ �帳�ϴ�.";
	else if($type=="SW") echo "���� �ݾ��� ".number_format($row->group_addmoney)."����<br><b>�߰�����</b>�� �帳�ϴ�.";
	else if($type=="SP") echo "���� �ݾ��� ".number_format($row->group_addmoney)."%��<br><b>�߰�����</b>�� �帳�ϴ�.";
}
?>
												</span>
											</td>
										</tr>
									</table>									
									<!-- ȸ����� �� �߰�����/�����ȳ� END -->
								</td>
								<td valign="top">
									<!-- SNS ä�ΰ��� START -->
									<table cellpadding="0" cellspacing="0" border="0">
										<tr>
											<td height="22" valign="top" colspan="3" class="mypage_list_title"><b>SNS ä�ΰ���</b><img src="<?=$Dir?>images/common/mypage/<?=$_data->design_mypage?>/mypage_mem_icon03.gif" border="0"></td>
										</tr>
<?
	if($_data->recom_url_ok == "Y" ){
		if(strlen($_ShopInfo->getMemid())>0) {
			$arRecomType = explode("", $_data->recom_memreserve_type);
			$sAddRecom = "";
			if($arRecomType[0] == "A"){
				$sAddRecom = "ȸ������ <font color=\"#CC0000\">URL�ּ�</font>�� ���� �ű�ȸ�����Խ� ȸ���Կ��� <u>".$_data->recom_memreserve."���� �������� ����</u>�˴ϴ�.";
			}else if($arRecomType[0] == "B"){
				$sAddRecom = "ȸ������ <font color=\"#CC0000\">URL�ּ�</font>�� ���� ������ ȸ���� ù ���Ű� �̷������ <u>";
				if($arRecomType[1] == "A"){
					if($arRecomType[2] == "N"){
						$sAddRecom .= $_data->recom_memreserve."����";
					}else if($arRecomType[2] == "Y"){
						$sAddRecom .= "���űݾ��� ".$_data->recom_memreserve."%��";
					}
				}else if($arRecomType[1] == "B"){
					$sAddRecom .= "���űݾ׿� ����";
				}
				$sAddRecom .= " ������</u>�� ȸ���Կ��� ���޵˴ϴ�.";
			}

			$sql = "SELECT COUNT(*) as cnt FROM tblmember WHERE rec_id='".$_ShopInfo->getMemid()."'";
			$result = mysql_query($sql,get_db_conn());
			$row = mysql_fetch_object($result);
			$recom_cnt = $row->cnt;
			mysql_free_result($result);
?>
										<tr>
											<td style="font-size:11px; letter-spacing:-1px; line-height:130%;">
												�� URL �ּ� : <font style="letter-spacing:0px;">http://<?=$_ShopInfo->getShopurl()?>?token=<?=$url_id?></font><br>
												�� URL �ű� ȸ������ : <?=$recom_cnt?>�� [������ ���ʽ� : 1,000��]
											</td>
										</tr>
<?
		}
	}
	if($_data->sns_ok == "Y" && strlen($_ShopInfo->getMemid())>0) {
?>
										<tr>
											<td>
												<table border="0" cellpadding="0" cellspacing="0">
													<tr>
														<td><? if(TWITTER_ID !="TWITTER_ID"){?><img src="../images/design/icon_twitter_off.gif" border="0" align="absmiddle" id="twLoginBtn"><?}?></td>
														<td><? if(FACEBOOK_ID!="FACEBOOK_ID"){?><img src="../images/design/icon_facebook_off.gif" border="0" align="absmiddle" hspace="3" id="fbLoginBtn"><?}?></td>
														<td><? if(ME2DAY_ID!="ME2DAY_ID"){?><img src="../images/design/icon_me2day_off.gif" border="0" align="absmiddle" id="meLoginBtn"><?}?></td>
														<td><a href="mypage_promote.php"><img src="<?=$Dir?>images/common/mypage/<?=$_data->design_mypage?>/mypage_sns_icon01.gif" border="0" align="absmiddle" hspace="3" alt="SNSä�ΰ���"></a></td>
<? if($_data->recom_url_ok == "Y" ){?>
														<td><a href="../front/member_urlhongbo.php" onclick="win_hongboUrl();return false;" target="_blank"><img src="<?=$Dir?>images/common/mypage/<?=$_data->design_mypage?>/mypage_sns_icon02.gif" border="0" align="absmiddle"></a></td>
<?}?>
													</tr>
												</table>
											</td>
										</tr>
<?}?>
									</table>
									<!-- SNS ä�ΰ��� END -->
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			<!-- ȸ������ BOX END -->
		</td>
	</tr>
<?
		}
	}
?>


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
			<colgroup>
				<col width="180">
				<col>
				<col width="80">
				<col width="90">
				<col width="80">
				<col width="80">
			</colgroup>
		<tr>
			<td height="2" colspan="6" bgcolor="#666666"></td>
		</tr>
		<tr height="30" align="center" bgcolor="#F8F8F8">
			<td align="left" style="padding-left:15px;" class="mypage_list_title">�ֹ���(��������)</td>
			<td class="mypage_list_title">��ǰ��/�ɼ�</td>
			<td class="mypage_list_title">�ֹ�����</td>
			<td class="mypage_list_title">��ȯ/ȯ��ó��</td>
			<td class="mypage_list_title">�������</td>
			<td class="mypage_list_title">��ǰ��</td>
		</tr>
		<tr>
			<td height="1" colspan="6" bgcolor="#DDDDDD"></td>
		</tr>
<?
		$delicomlist=getDeliCompany();
		$orderlists = getMyOrderList(5);
		$returnableCnt = 0;
		if($orderlists['total'] < 1){ ?>
		<tr height=40><td colspan=6 align=center bgcolor=#FFFFFF>�ֱ� 1���� �̳��� �����Ͻ� ������ �����ϴ�.</td></tr>
		<tr><td height=1 colspan=6 bgcolor=#999999></td></tr>
<?		}else{
			foreach($orderlists['orders'] as $row){
				$orderproducts = array();
				$orderproducts = getOrderProduct($row->ordercode);
		?>

		<tr bgcolor="#FFFFFF" onmouseover="this.style.background='#ffffff'" onmouseout="this.style.background='#FFFFFF'" style="padding-top:10; padding-bottom:10">
			<td style="font-size:11px;" class="mypage_order_line">
				<table border="0" cellpadding="0" cellspacing="0" width="100%">
					<tr><td height="26" class="mypage_order_line2"><b><?=substr($row->ordercode,0,4)?>/<?=substr($row->ordercode,4,2)?>/<?=substr($row->ordercode,6,2)?></b></td></tr>
					<tr><td height=5></td></tr>
					<tr><td class="mypage_list_cont">������� : <?=getPaymethodStr($row->paymethod)?></td></tr>
					<tr><td class="mypage_list_cont">�����ݾ� : <b><font color="#000000"><?=number_format($row->price)?></font></b>��</td></tr>
					<tr><td height=5></td></tr>
					<tr><td align=center><A HREF="javascript:OrderDetailPop('<?=$row->ordercode?>')" onmouseover="window.status='�ֹ�������ȸ';return true;" onmouseout="window.status='';return true;"><img src="<?=$Dir?>images/common/mypage/<?=$_data->design_mypage?>/mypage_order_icon01.gif" alt="����" /></a></td></tr>
				</table>
			</td>
			<td colspan=5>
				<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
					<colgroup>
						<col>
						<col width=80>
						<col width=90>
						<col width=80>
						<col width=80>
					</colgroup>
	<?
				$orderproductsCnt = count($orderproducts);
				for($jj=0;$jj < $orderproductsCnt ;$jj++){
					$row2 = $orderproducts[$jj];
					//if($jj>0) echo '<tr><td colspan=5 height=1 bgcolor=#E5E5E5></tr>';
	?>

					<tr>
						<td class="mypage_list_cont"><A HREF="javascript:OrderDetailPop('<?=$row->ordercode?>')" onmouseover="window.status='�ֹ�������ȸ';return true;" onmouseout="window.status='';return true;"><img src="<?=(strlen($row2->tinyimage)>0 && file_exists($Dir.DataDir."shopimages/product/".$row2->tinyimage)==true)?$Dir.DataDir.'shopimages/product/'.urlencode($row2->tinyimage):$Dir."images/no_img.gif"?>" border="0" width="50" /><?=$row2->productname?></a></td>
						<td align="center" class="mypage_list_cont2"><font color="#000000"><? echo orderProductDeliStatusStr($row2,$row,$orderproductsCnt); ?></font></td>
						<td align="center" class="mypage_list_cont2"><font color="#3f77ca">
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
								echo $pststr;
							} 
							?></font></td>
						<td align=center style="font-size:8pt;padding-top:3;">
						<?		
						$deli_link = '-';
						$deli_url="";
						$trans_num="";
						$company_name="";
						if($row2->deli_gbn=="Y") {
							if($row2->deli_com>0 && $delicomlist[$row2->deli_com]) {
								$deli_url=$delicomlist[$row2->deli_com]->deli_url;
								$trans_num=$delicomlist[$row2->deli_com]->trans_num;
								$company_name=$delicomlist[$row2->deli_com]->company_name;
								$deli_link .= $company_name."<br>";
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
						<td align="center"><A HREF="javascript:OrderDetailPop('<?=$row->ordercode?>')" onmouseover="window.status='�ֹ�������ȸ';return true;" onmouseout="window.status='';return true;"><img src="<?=$Dir?>images/common/mypage/<?=$_data->design_mypage?>/mypage_order_icon04.gif" alt="����" /></a></td>
					</tr>
			<?	} // end for $jj ?>
				</table>
			</td>
		</tr>		
		<tr><td colspan=6 height=1 bgcolor=#999999></td></tr>
		<? 	} // end foreach 
		if($returnableCnt > 0){ ?>		
		<tr><td height=30 colspan=6 bgcolor="#FFFFFF" align="right" style="padding-right:10"><a href=#  onclick="return refund1();"><IMG SRC="<?=$Dir?>images/common/mypage/<?=$_data->design_mypage?>/mypage_order_icon02.gif" BORDER=0 hspace="4" alt="���ðǿ� ���� ��ȯ��û"><a href=#  onclick="return refund2();"><IMG SRC="<?=$Dir?>images/common/mypage/<?=$_data->design_mypage?>/mypage_order_icon03.gif" BORDER=0 alt="���ðǿ� ���� ȯ�ҽ�û"></a></td></tr>
		<tr><td colspan=6 height=1 bgcolor=#E7E7E7></td></tr>
		<? } ?>
	<? } // end if ?>
		</table>
<!-- �Ϲݻ�ǰ �ֹ�(�ֱ� �ֹ�����) END -->

<!-- �������� �ֹ�(�ֱ� �ֹ�����) START -->
		<table cellpadding="0" cellspacing="0" width="100%" border="0" bgcolor="E7E7E7" style="table-layout:fixed;display:none;" id="list02">
		<!-- �ֹ�����, �ֹ� ��ǰ��, ��ۻ���, �������, �������, �����ݾ�, ������  -->
			<colgroup>
				<col width="180">
				<col>
				<col width="80">
				<col width="90">
				<col width="80">
				<col width="80">
			</colgroup>
		<tr>
			<td height="2" colspan="6" bgcolor="#666666"></td>
		</tr>
		<tr height="30" align="center" bgcolor="#F8F8F8" style="letter-spacing:-0.5pt;">
			<td align="left" style="padding-left:15px;" class="mypage_list_title">�ֹ���(��������)</td>
			<td class="mypage_list_title">��ǰ��/�ɼ�</td>
			<td class="mypage_list_title">�ֹ�����</td>
			<td class="mypage_list_title">��ȯ/ȯ��ó��</td>
			<td class="mypage_list_title">�������</td>
			<td class="mypage_list_title">�󼼺���</td>
		</tr>
		<tr>
			<td height="1" colspan="6" bgcolor="#DDDDDD"></td>
		</tr>
<?
		$orderlists = getMyOrderList(2,'2');
		$returnableCnt = 0;
		
		if($orderlists['total'] < 1){ ?>
		<tr height=40><td colspan=6 align=center bgcolor=#FFFFFF>�ֱ� 1���� �̳��� �����Ͻ� ������ �����ϴ�.</td></tr>
		<tr><td height=1 colspan=6 bgcolor=#999999></td></tr>
<?		}else{
			foreach($orderlists['orders'] as $row){ 
				$orderproducts = array();
				$orderproducts = getOrderProduct($row->ordercode);
		?>

		<tr bgcolor=#FFFFFF onmouseover="this.style.background='#ffffff'" onmouseout="this.style.background='#FFFFFF'">
			<td style="font-size:11px;" class="mypage_order_line">
				<table border="0" cellpadding="0" cellspacing="0" width="100%">
					<tr><td height="30" class="mypage_order_line2"><b><?=substr($row->ordercode,0,4)?>/<?=substr($row->ordercode,4,2)?>/<?=substr($row->ordercode,6,2)?></b></td></tr>
					<tr><td height=5></td></tr>
					<tr><td class="mypage_list_cont">������� : <?=getPaymethodStr($row->paymethod)?></td></tr>
					<tr><td class="mypage_list_cont">�����ݾ� : <b><?=number_format($row->price)?></b>��</td></tr>
					<tr><td height=8></td></tr>
				</table>
			</td>
			<td colspan=5>
				<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
					<colgroup>
						<col>
						<col width=70>
						<col width=80>
						<col width=70>
						<col width=70>
					</colgroup>
	<?
				$orderproductsCnt = count($orderproducts);
				for($jj=0;$jj < count($orderproducts);$jj++){
					$row2 = $orderproducts[$jj];
					if($jj>0) echo '<tr><td colspan=4 height=1 bgcolor=#F5F5F5></tr>';
	?>

					<tr>
						<td style="padding-left:15;line-height:11pt"><A HREF="javascript:OrderDetailPop('<?=$row->ordercode?>')" onmouseover="window.status='�ֹ�������ȸ';return true;" onmouseout="window.status='';return true;"><?=$row2->productname?></a></td>
						<td align=center class="mypage_list_cont2"><font color="#000000"><? echo orderProductDeliStatusStr($row2,$row,$orderproductsCnt); ?></font></td>
						<td align=center>
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
								$pststr = '�Ұ�';
							}
							echo $pststr;
							?></td>
						<td align=center style="font-size:8pt;padding-top:3">
						<?		
						$deli_link = '-';
						$deli_url="";
						$trans_num="";
						$company_name="";
						if($row2->deli_gbn=="Y") {
							if($row2->deli_com>0 && $delicomlist[$row2->deli_com]) {
								$deli_url=$delicomlist[$row2->deli_com]->deli_url;
								$trans_num=$delicomlist[$row2->deli_com]->trans_num;
								$company_name=$delicomlist[$row2->deli_com]->company_name;
								$deli_link .= $company_name."<br>";
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
						<td><A HREF="javascript:OrderDetailPop('<?=$row->ordercode?>')" onmouseover="window.status='�ֹ�������ȸ';return true;" onmouseout="window.status='';return true;"><font color=#ff6600>�ֹ�������</font></td>
					</tr>
			<?	} // end for $jj ?>
				</table>
			</td>
		</tr>		
		<tr><td colspan=6 height=1 bgcolor=#999999></td></tr>
		<? 	} // end foreach 
		if($returnableCnt > 0){ ?>		
		<tr><td height=30 colspan=5 bgcolor="#FFFFFF" align="right"><a href=#  onclick="return refund1();"><IMG SRC="<?=$Dir?>images/common/mypage/<?=$_data->design_mypage?>/mypage_order_icon02.gif" BORDER=0 alt="���ðǿ� ���� ��ȯ��û"></a> &nbsp;|&nbsp; <a href=#  onclick="return refund2();"><IMG SRC="<?=$Dir?>images/common/mypage/<?=$_data->design_mypage?>/mypage_order_icon03.gif" BORDER=0 alt="���ðǿ� ���� ȯ�ҽ�û"></a></td></tr>
		<tr><td colspan=5 height=1 bgcolor=#E7E7E7></td></tr>
		<? } ?>
	<? } // end if ?>
		</table>
<!-- �������� �ֹ�(�ֱ� �ֹ�����) END -->


<!-- ��ǰ�� �ֹ�(�ֱ� �ֹ�����) START -->
		<table cellpadding="0" cellspacing="0" width="100%" border="0" bgcolor="E7E7E7" style="table-layout:fixed;display:none;" id="list03">
		<!-- �ֹ�����, �ֹ� ��ǰ��, ��ۻ���, �������, �������, �����ݾ�, ������  -->
			<colgroup>
				<col width="180">
				<col>
				<col width="80">
				<col width="90">
				<col width="80">
				<col width="80">
			</colgroup>
		<tr>
			<td height="2" colspan="6" bgcolor="#666666"></td>
		</tr>
		<tr height="30" align="center" bgcolor="#F8F8F8" style="letter-spacing:-0.5pt;">
			<td align="left" style="padding-left:15px;" class="mypage_list_title">�ֹ���(��������)</td>
			<td class="mypage_list_title">��ǰ��/�ɼ�</td>
			<td class="mypage_list_title">ó������</td>
			<td class="mypage_list_title">��ȯ/ȯ��ó��</td>
			<td class="mypage_list_title">������ȣ</td>
			<td class="mypage_list_title">�ֹ���ȸ</td>
		</tr>
		<tr>
			<td height="1" colspan="6" bgcolor="#DDDDDD"></td>
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
			echo "	<td class=mypage_order_line>";
			echo "<table border=0 cellpadding=0 cellspacing=0 width=100%>";
			echo "	<td style=font-size:11px;padding-left:15px; height=30 class=mypage_order_line2>".substr($row->ordercode,0,4)."/".substr($row->ordercode,4,2)."/".substr($row->ordercode,6,2)."</td>\n";
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
			echo "<tr><td class=mypage_list_cont>�����ݾ� : ".number_format($row->price)."��</td></tr>";
			echo "<tr><td height=5></td></tr>";
			echo "</table></td>\n";

			echo "	<td colspan=5>\n";
			echo "	<table border=0 cellpadding=0 cellspacing=0 width=100% style=\"table-layout:fixed\">\n";
			echo "	<colgroup>";
			echo "	<col>\n";
			echo "	<col width=80>\n";
			echo "	<col width=90>\n";
			echo "	<col width=80>\n";
			echo "	<col width=80>\n";
			echo "	</colgroup>";
			$sql = "SELECT * FROM tblorderproduct WHERE ordercode='".$row->ordercode."' ";
			$sql.= "AND NOT (productcode LIKE 'COU%' OR productcode LIKE '999999%') ";
			$result2=mysql_query($sql,get_db_conn());
			$jj=0;
			while($row2=mysql_fetch_object($result2)) {
				if($jj>0) echo "<tr><td colspan=5 height=1 bgcolor=#F5F5F5></tr>";
				echo "<tr>\n";
				echo "	<td style=font-size:11px;padding-left:15;line-height:11pt;><A HREF=\"javascript:OrderDetailPop('".$row->ordercode."')\" onmouseover=\"window.status='�ֹ�������ȸ';return true;\" onmouseout=\"window.status='';return true;\">".$row2->productname."</a></td>";
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
				echo "	<td align=center><input type=checkbox name=></td>";
				echo "	<td align=center style=\"font-size:11px; padding-top:3\">";
				$deli_url="";
				$trans_num="";
				$company_name="";
				if($row2->deli_gbn=="Y") {
					if($row2->deli_com>0 && $delicomlist[$row2->deli_com]) {
						$deli_url=$delicomlist[$row2->deli_com]->deli_url;
						$trans_num=$delicomlist[$row2->deli_com]->trans_num;
						$company_name=$delicomlist[$row2->deli_com]->company_name;
						echo $company_name."<br>";
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
				echo "	<td align=center><A HREF=\"javascript:OrderDetailPop('".$row->ordercode."')\" onmouseover=\"window.status='�ֹ�������ȸ';return true;\" onmouseout=\"window.status='';return true;\"><img src=".$Dir."images/common/mypage/".$_data->design_mypage."/mypage_order_icon01.gif></a></td>";
				echo "</tr>\n";
				$jj++;
			}
			mysql_free_result($result2);
			echo "	</table>\n";
			echo "	</td>\n";
			echo "</tr>\n";
			echo "<tr><td colspan=6 height=1 bgcolor=#999999></td></tr>\n";
			echo "<tr><td height=30 colspan=6 bgcolor=#FFFFFF align=right><a href=#><IMG SRC=".$Dir."images/common/mypage/".$_data->design_mypage."/mypage_order_icon02.gif BORDER=0 alt=\"���ðǿ� ���� ��ȯ��û\"></a> &nbsp;|&nbsp; <a href=#><IMG SRC=".$Dir."images/common/mypage/".$_data->design_mypage."/mypage_order_icon03.gif BORDER=0 alt=\"���ðǿ� ���� ȯ�ҽ�û\"></a></td></tr>\n";
			echo "<tr><td colspan=6 height=1 bgcolor=#E7E7E7></td></tr>\n";
			$cnt++;
		}
		mysql_free_result($result);

		if ($cnt==0) {
			echo "<tr height=40><td colspan=6 align=center bgcolor=#FFFFFF>�ֱ� 1���� �̳��� �����Ͻ� ������ �����ϴ�.</td></tr>";
			echo "<tr><td height=1 colspan=6 bgcolor=#999999></td></tr>";
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
			<colgroup>
				<col width="140">
				<col>
				<col width="65">
				<col width="105">
			</colgroup>
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
			echo "	<td align=\"left\"><A HREF=\"javascript:ViewPersonal('".$row->idx."')\"><font style=font-size:12px color:#333333;\">".strip_tags($row->subject)."</font></A></td>\n";
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
			<td height="5"></td>
		</tr>
		<tr>
			<td>
			<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td>
				<table cellpadding="2" cellspacing="0" width="100%">
				<TR>
<?
				$sql = "SELECT b.productcode,b.productname,b.sellprice,b.quantity,b.reserve,b.reservetype,b.tinyimage, ";
				$sql.= "b.option_price,b.option_quantity,b.selfcode,b.etctype FROM tblwishlist a, tblproduct b ";
				$sql.= "LEFT OUTER JOIN tblproductgroupcode c ON b.productcode=c.productcode ";
				$sql.= "WHERE a.id='".$_ShopInfo->getMemid()."' AND a.productcode=b.productcode ";
				$sql.= "AND (b.group_check='N' OR c.group_code='".$_ShopInfo->getMemgroup()."') ";
				$sql.= "AND b.display='Y' LIMIT 5 ";
				$result=mysql_query($sql,get_db_conn());
				$cnt=0;
				while($row=mysql_fetch_object($result)) {
					if ($cnt!=0 && $cnt%5==0) {
						echo "</tr><tr><td colspan=\"9\" height=\"10\"></td></tr>\n";
					}
					if ($cnt!=0 && $cnt%5!=0) {
						echo "<td width=\"10\" nowrap></td>";
					}
					echo "<td width=\"20%\" align=\"center\" valign=\"top\">\n";
					echo "<TABLE cellspacing=\"0\" cellpadding=\"0\" width=\"100%\" border=\"0\" id=\"W".$row->productcode."\" onmouseover=\"quickfun_show(this,'W".$row->productcode."','')\" onmouseout=\"quickfun_show(this,'W".$row->productcode."','none')\">\n";
					echo "<TR height=\"100\">\n";
					echo "	<TD align=\"center\">";
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
					echo "	<td align=\"center\" style=\"word-break:break-all;\"><A HREF=\"".$Dir.FrontDir."productdetail.php?productcode=".$row->productcode.$add_query."\" onmouseover=\"window.status='��ǰ����ȸ';return true;\" onmouseout=\"window.status='';return true;\"><FONT class=\"prname\">".viewproductname($row->productname,$row->etctype,$row->selfcode)."</FONT></A></td>\n";
					echo "</tr>\n";
					echo "<tr>\n";
					echo "	<td align=\"center\" class=\"prprice\">";
					if($dicker=dickerview($row->etctype,number_format($row->sellprice)."��",1)) {
						echo $dicker;
					} else if(strlen($_data->proption_price)==0) {
						echo "<img src=\"".$Dir."images/common/won_icon.gif\" border=\"0\" align=\"absmiddle\"> ".number_format($row->sellprice)."��";
						if (strlen($row->option_price)!=0) echo "<FONT color=\"#FF0000\">(�ɼǺ���)</FONT>";
					} else {
						echo "<img src=\"".$Dir."images/common/won_icon3.gif\" border=\"0\" align=\"absmiddle\"> ";
						if (strlen($row->option_price)==0) echo number_format($row->sellprice)."��";
						else echo ereg_replace("\[PRICE\]",number_format($row->sellprice),$_data->proption_price);
					}
					if ($row->quantity=="0") echo soldout(1);
					echo "	</td>\n";
					echo "</tr>\n";
					$reserveconv=getReserveConversion($row->reserve,$row->reservetype,$row->sellprice,"Y");
					if($reserveconv>0) {
						echo "<tr>\n";
						echo "	<td align=\"center\" class=\"prreserve\"><img src=\"".$Dir."images/common/reserve_icon.gif\" border=\"0\" align=\"absmiddle\"> ".number_format($reserveconv)."��</td>\n";
						echo "</tr>\n";
					}
					echo "</table>\n";
					echo "</td>";

					$cnt++;
				}
				if($cnt>0 && $cnt<5) {
					for($k=0; $k<(5-$cnt); $k++) {
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
		</table>
		</td>
	</tr>
	<tr>
		<td height="20"></td>
	</tr>
	</table>
	</td>
</tr>
</table>