<table cellpadding="0" cellspacing="0" width="100%">
<tr>
	<td style="padding:5px;padding-top:0px;">
	<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td valign="bottom">
		<TABLE WIDTH="100%" BORDER="0" CELLPADDING="0" CELLSPACING="0">
		<TR>
			<TD><A HREF="<?=$Dir.FrontDir?>mypage.php"><IMG SRC="<?=$Dir?>images/common/mypersonal_skin1_menu1r.gif" BORDER="0"></A></TD>
			<TD><A HREF="<?=$Dir.FrontDir?>mypage_orderlist.php"><IMG SRC="<?=$Dir?>images/common/mypersonal_skin1_menu2.gif" BORDER="0"></A></TD>
			<TD><A HREF="<?=$Dir.FrontDir?>mypage_personal.php"><IMG SRC="<?=$Dir?>images/common/mypersonal_skin1_menu3.gif" BORDER="0"></A></TD>
			<TD><A HREF="<?=$Dir.FrontDir?>wishlist.php"><IMG SRC="<?=$Dir?>images/common/mypersonal_skin1_menu4.gif" BORDER="0"></A></TD>
			<TD><A HREF="<?=$Dir.FrontDir?>mypage_reserve.php"><IMG SRC="<?=$Dir?>images/common/mypersonal_skin1_menu5.gif" BORDER="0"></A></TD>
			<TD><A HREF="<?=$Dir.FrontDir?>mypage_coupon.php"><IMG SRC="<?=$Dir?>images/common/mypersonal_skin1_menu6.gif" BORDER="0"></A></TD>
			<?if($_data->recom_url_ok == "Y" || $_data->sns_ok == "Y"){?><TD><A HREF="<?=$Dir.FrontDir?>mypage_promote.php"><IMG SRC="<?=$Dir?>images/common/mypersonal_skin1_menu10.gif" BORDER="0"></A></TD><?}?>
			<TD><A HREF="<?=$Dir.FrontDir?>mypage_gonggu.php"><IMG SRC="<?=$Dir?>images/common/mypersonal_skin1_menu11.gif" BORDER="0"></A></TD>
			<? if(getVenderUsed()==true) { ?><TD><A HREF="<?=$Dir.FrontDir?>mypage_custsect.php"><IMG SRC="<?=$Dir?>images/common/mypersonal_skin1_menu9.gif" BORDER="0"></A></TD><? } ?>
			<TD><A HREF="<?=$Dir.FrontDir?>mypage_usermodify.php"><IMG SRC="<?=$Dir?>images/common/mypersonal_skin1_menu7.gif" BORDER="0"></A></TD>
			<TD><A HREF="<?=$Dir.FrontDir?>mypage_memberout.php"><IMG SRC="<?=$Dir?>images/common/mypersonal_skin1_menu8.gif" BORDER="0"></A></TD>
			<TD width="100%" background="<?=$Dir?>images/common/mypersonal_skin1_menubg.gif"></TD>
		</TR>
		</TABLE>
		</td>
	</tr>
	<tr>
		<td height="20"></td>
	</tr>
	<tr>
		<td valign="bottom" style="font-size:11px;letter-spacing:-0.5pt;color:#A1A1A1;">
		<table cellpadding="0" cellspacing="0" width="100%">
		<tr>
			<td>
			<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td width="50%" valign="top">
				<table cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td><IMG SRC="<?=$Dir?>images/common/mypage/<?=$_data->design_mypage?>/mypage_skin1_tabel01.gif" border="0"></td>
				</tr>
				<tr>
					<td background="<?=$Dir?>images/common/mypage/<?=$_data->design_mypage?>/mypage_skin1_tabel01bg.gif" style="padding-top:10px;">
					<table align="center" cellpadding="0" cellspacing="0" border="0">
					<tr>
						<td valign="top">
						<table cellpadding="0" cellspacing="0" width="100%">
						<tr>
							<td align="center" style="font-size:11px;letter-spacing:-0.5pt;"><font color="#000000"><b>������<br>��������</b></font></td>
						</tr>
						<tr>
							<td align="center" style="font-size:18px;line-height:22px;letter-spacing:-0.5pt;"><font color="#FF4C00"><b><?=number_format($_mdata->reserve)?>��</b></font></td>
						</tr>
						<tr>
							<td align="center"><A HREF="<?=$Dir.FrontDir?>mypage_reserve.php"><IMG SRC="<?=$Dir?>images/common/mypage/<?=$_data->design_mypage?>/mypage_skin1_tabel01btn.gif" BORDER="0"></A></td>
						</tr>
						</table>
						</td>
						<td align="center"><img src="<?=$Dir?>images/common/mypage/<?=$_data->design_mypage?>/mypage_skin1_tabel01line.gif" border="0"></td>
						<td valign="top">
						<table cellpadding="0" cellspacing="0" width="100%">
						<tr>
							<td align="center" style="font-size:11px;letter-spacing:-0.5pt;"><font color="#000000"><b>��밡��<br>��������</b></font></td>
						</tr>
						<tr>
							<td align="center" style="font-size:18px;line-height:22px;letter-spacing:-0.5pt;"><font color="#FF4C00"><b><?=number_format($coupon_cnt)?>��</b></font></td>
						</tr>
						<tr>
							<td align="center"><A HREF="<?=$Dir.FrontDir?>mypage_coupon.php"><IMG SRC="<?=$Dir?>images/common/mypage/<?=$_data->design_mypage?>/mypage_skin1_tabel01btn.gif" BORDER="0"></A></td>
						</tr>
						</table>
						</td>
						<td align="center"><img src="<?=$Dir?>images/common/mypage/<?=$_data->design_mypage?>/mypage_skin1_tabel01line.gif" border="0"></td>
						<td valign="top">
						<table cellpadding="0" cellspacing="0" width="100%">
						<tr>
							<td align="center" style="font-size:11px;letter-spacing:-0.5pt;"><font color="#000000"><b>��ǰ�ǹ�ȣ<br>����</b></font></td>
						</tr>
						<tr>
							<td align="center"  style="padding-top:8px"><A HREF="<?=$Dir.FrontDir?>mypage_auth.php"><IMG SRC="<?=$Dir?>images/common/mypage/<?=$_data->design_mypage?>/mypage_skin1_authbtn.gif" BORDER="0" alt="����"></A></td>
						</tr>
						</table>
						</td>
					</tr>
					</table>
					</td>
				</tr>
				<tr>
					<td background="<?=$Dir?>images/mypage_skin1_tabel01bg.gif"><IMG SRC="<?=$Dir?>images/common/mypage/<?=$_data->design_mypage?>/mypage_skin1_tabel01down.gif" border="0"></td>
				</tr>
				</table>
				</td>
				<td width="50%" align="right" valign="top">
					<table cellpadding="0" cellspacing="0">
					<tr>
						<td><IMG SRC="<?=$Dir?>images/common/mypage/<?=$_data->design_mypage?>/mypage_skin1_tabel02.gif" border="0"></td>
					</tr>
					<tr>
						<td background="<?=$Dir?>images/common/mypage/<?=$_data->design_mypage?>/mypage_skin1_tabel01bg.gif" style="padding-top:10px;padding-left:15px;">
						<table cellpadding="0" cellspacing="0" width="100%">
						<col width="6"></col>
						<col width="49"></col>
						<col width="5"></col>
						<col></col>
						<tr style="letter-spacing:-0.5pt;">
							<td><img src="<?=$Dir?>images/common/mypage/<?=$_data->design_mypage?>/mypage_skin1_tabelpoint.gif" border="0"></td>
							<td style="font-size:11px;"><font color="#000000"><b>���ּ�</b></font></td>
							<td style="font-size:11px;">:</td>
							<td style="font-size:11px;"><?=str_replace("=","<br>",$_mdata->home_addr)?></td>
						</tr>
						<tr style="letter-spacing:-0.5pt;">
							<td><img src="<?=$Dir?>images/common/mypage/<?=$_data->design_mypage?>/mypage_skin1_tabelpoint.gif" border="0"></td>
							<td style="font-size:11px;"><font color="#000000"><b>��ȭ��ȣ</b></font></td>
							<td style="font-size:11px;">:</td>
							<td style="font-size:11px;"><?=$_mdata->home_tel?><?if(strlen($_mdata->mobile)>0)echo ", ".$_mdata->mobile;?></td>
						</tr>
						<tr style="letter-spacing:-0.5pt;">
							<td><img src="<?=$Dir?>images/common/mypage/<?=$_data->design_mypage?>/mypage_skin1_tabelpoint.gif" border="0"></td>
							<td style="font-size:11px;"><font color="#000000"><b>�̸���</b></font></td>
							<td style="font-size:11px;">:</td>
							<td style="font-size:11px;"><A HREF="mailto:<?=$_mdata->email?>"><?=$_mdata->email?></A></td>
						</tr>
						</table>
						</td>
					</tr>
					<tr>
						<td><IMG SRC="<?=$Dir?>images/common/mypage/<?=$_data->design_mypage?>/mypage_skin1_tabel02down.gif" border="0"></td>
					</tr>
					</table>
				</td>
			</tr>
			</table>
			</td>
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
			<td height="20"></td>
		</tr>
		<tr>
			<td>
			<table cellpadding="0" cellspacing="8" width="100%" bgcolor="#E8E8E8">
			<tr>
				<td background="<?=$Dir?>images/common/mypage/<?=$_data->design_mypage?>/design_search_skin1_tbg.gif" style="padding:10px;">
				<table width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td>
					<?if(file_exists($Dir.DataDir."shopimages/etc/groupimg_".$row->group_code.".gif")){?>
					<img src="<?=$Dir.DataDir?>shopimages/etc/groupimg_<?=$row->group_code?>.gif" border="0">
					<?}else{?>
					<img src="<?=$Dir?>images/common/group_img.gif" border="0">
					<?}?>
					</td>
					<td width="100%">
					<B><?=$row->name?></B>���� <B><FONT color="#EELA02">[<?=$row->group_name?>]</FONT></B> ȸ���Դϴ�.<br>
					<?if (substr($row->group_code,0,1)!="M") {?>
					<B><?=$row->name?></B>���� <FONT color="#EELA02"><B><?=number_format($row->group_usemoney)?>��</B></FONT> �̻� <?=$arr_dctype[$row->group_payment]?>���Ž�,
					<?
					$type=substr($row->group_code,0,2);
					if($type=="RW") echo "�����ݿ� ".number_format($row->group_addmoney)."���� <font color=\"#EE1A02\"><B>�߰� ����</B></font>�� �帳�ϴ�.";
					else if($type=="RP") echo "���� �������� ".number_format($row->group_addmoney)."�踦 <font color=\"#EE1A02\"><B>����</B></font>�� �帳�ϴ�.";
					else if($type=="SW") echo "���űݾ� ".number_format($row->group_addmoney)."���� <font color=\"#EE1A02\"><B>�߰� ����</B></font>�� �帳�ϴ�.";
					else if($type=="SP") echo "���űݾ��� ".number_format($row->group_addmoney)."%�� <font color=\"#EE1A02\"><B>�߰� ����</B></font>�� �帳�ϴ�.";
					?>
					<?}?>
					</td>
				</tr>
				</table>
				</td>
			</tr>
			</table>
			</td>
		</tr>
<?
			}
			mysql_free_result($result);
		}
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
			<td height="30"></td>
		</tr>
		<tr>
			<td align="center"><?=$_ShopInfo->getMemname()?>���� �Ұ��� ������� <span style="font-weight:bold;color:#CC0000"><?=$recom_cnt?></span>���� ģ���� �����Ͽ����ϴ�</td>
		</tr>
		<tr>
			<td align="center"><b><?=$_ShopInfo->getMemname()?>��</b>���� ������ <b>URL�ּ�</b>�� <span style="color:#FF5C29;"><b>http://<?=$_ShopInfo->getShopurl()?>?token=<?=$url_id?></b></span>�Դϴ�.<a href="../front/member_urlhongbo.php" onclick="win_hongboUrl();return false;" target="_blank"><IMG SRC="../images/design/mypage_urlhongbo.gif" WIDTH=61 HEIGHT=22 ALT="" border=0 align="absmiddle"></a></td>
		</tr>
		<tr>
			<td align="center"><?=$sAddRecom ?></td>
		</tr>
<?
		}
	}
	if($_data->sns_ok == "Y" && strlen($_ShopInfo->getMemid())>0) {
?>
		<tr>
			<td height="20"></td>
		</tr>
		<tr>
			<td align="center">
<? if(TWITTER_ID !="TWITTER_ID"){?><img src="../images/design/icon_twitter_off.gif" width="25" height="25" border="0" align="absmiddle" id="twLoginBtn"><?}?>
<? if(FACEBOOK_ID!="FACEBOOK_ID"){?><img src="../images/design/icon_facebook_off.gif" width="25" height="25" border="0" hspace="3" align="absmiddle" id="fbLoginBtn"><?}?>
<? if(ME2DAY_ID!="ME2DAY_ID"){?><img src="../images/design/icon_me2day_off.gif" width="25" height="25" border="0" align="absmiddle" id="meLoginBtn"><?}?> <a href="mypage_promote.php"><img src="../images/design/sns_channel.gif" align="absmiddle"></a>			
			</td>
		</tr>
<?
	}
?>
		</table>
		</td>
	</tr>
	<tr>
		<td height="20"></td>
	</tr>
	<tr>
		<td>
		<table cellpadding="0" cellspacing="0" width="100%">
		<tr>
			<td><IMG SRC="<?=$Dir?>images/common/mypage/<?=$_data->design_mypage?>/mypage_skin1_text01.gif" border="0"></td>
			<td align="right" style="padding-bottom:3px;"></td>
		</tr>
		</table>
		</td>
	</tr>
	<tr>
		<td>
			<table cellpadding="0" cellspacing="0" width="100%" border="0">
			<tr>
				<td><a href="#orderList_box" onclick="getOrderList(1);return false;"><img src="<?=$Dir?>images/common/mypage/<?=$_data->design_mypage?>/order_01.gif" align="absmiddle" id="orderList_type1"></a></td>
				<td><a href="#orderList_box" onclick="getOrderList(2);return false;"><img src="<?=$Dir?>images/common/mypage/<?=$_data->design_mypage?>/order_02.gif" align="absmiddle" id="orderList_type2"></a></td>
				<td><a href="#orderList_box" onclick="getOrderList(3);return false;"><img src="<?=$Dir?>images/common/mypage/<?=$_data->design_mypage?>/order_03.gif" align="absmiddle" id="orderList_type3"></a></td>
				<td width="100%" align="right"><A HREF="<?=$Dir.FrontDir?>mypage_orderlist.php"><IMG SRC="<?=$Dir?>images/common/mypage/<?=$_data->design_mypage?>/mypage_skin1_btn01.gif" BORDER="0"></A></td>
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
			foreach($orderlists['orders'] as $row){
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
					<tr><td class="mypage_list_cont"><A HREF="javascript:OrderDetailPop('<?=$row->ordercode?>')" onmouseover="window.status='�ֹ�������ȸ';return true;" onmouseout="window.status='';return true;"><img src="<?=$Dir?>images/common/mypage/<?=$_data->design_mypage?>/mypage_order_icon01.gif" alt="�ֹ� ������" /></a></td></tr>
				</table>
			</td>
			<td colspan=4>
				<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
				<col></col>
				<col width=80></col>
				<col width=80></col>
				<col width=80></col>
	<?			for($jj=0;$jj < count($orderproducts);$jj++){
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
						<td style="padding:10px;" class="mypage_list_cont"><A HREF="javascript:OrderDetailProduct('<?=$row->ordercode?>','<?=$row2->productcode?>')" onmouseover="window.status='�ֹ�������ȸ';return true;" onmouseout="window.status='';return true;"><img src="<?=(strlen($row2->tinyimage)>0 && file_exists($Dir.DataDir."shopimages/product/".$row2->tinyimage)==true)?$Dir.DataDir.'shopimages/product/'.urlencode($row2->tinyimage):$Dir."images/no_img.gif"?>" border="0" width="50" style="float:left;margin-right:5px;"/><?=$row2->productname?></a>
						<?
						if(!_empty($optvalue)) 	echo "<br><img src=\"".$Dir."images/common/icn_option.gif\" border=0 align=absmiddle> ".$optvalue."";
						?>
						</td>
						<td align="center" class="mypage_list_cont2"><font color="#000000"><? echo orderProductDeliStatusStr($row2,$row); ?></font></td>
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
						<td align="center"><? if($row2->deli_gbn=="Y" && $_data->review_type !="N")  { ?><A HREF="javascript:OrderReview('<?=$row->ordercode?>','<?=$row2->productcode?>')" onmouseover="window.status='��ǰ��';return true;" onmouseout="window.status='';return true;"><img src="<?=$Dir?>images/common/mypage/<?=$_data->design_mypage?>/mypage_order_icon04.gif" alt="��ǰ���ۼ�" /></a><? }else{ ?><img src="<?=$Dir?>images/common/mypage/<?=$_data->design_mypage?>/mypage_order_icon04_off.gif" alt="��ǰ���ۼ�" /><? } ?></td>
					</tr>
			<?	} // end for $jj ?>
				</table>
			</td>
		</tr>
		<tr><td colspan=5 height=1 bgcolor=#999999></td></tr>
		<? 	} // end foreach 
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
	<?			for($jj=0;$jj < count($orderproducts);$jj++){
					$row2 = $orderproducts[$jj];
					if($jj>0) echo '<tr><td colspan=4 height=1 bgcolor=#F5F5F5></tr>';
	?>

					<tr>
						<td style="padding:10px; ine-height:11pt"><A HREF="javascript:OrderDetailPop('<?=$row->ordercode?>')" onmouseover="window.status='�ֹ�������ȸ';return true;" onmouseout="window.status='';return true;"><?=$row2->productname?></a></td>
						<td align=center class="mypage_list_cont2"><font color="#000000"><? echo orderProductDeliStatusStr($row2,$row); ?></font></td>
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
						<td align="center"><? if($row2->deli_gbn=="Y" && $_data->review_type !="N")  { ?><A HREF="javascript:OrderReview('<?=$row->ordercode?>','<?=$row2->productcode?>')" onmouseover="window.status='��ǰ��';return true;" onmouseout="window.status='';return true;"><img src="<?=$Dir?>images/common/mypage/<?=$_data->design_mypage?>/mypage_order_icon04.gif" alt="��ǰ���ۼ�" /></a><? }else{ ?><img src="<?=$Dir?>images/common/mypage/<?=$_data->design_mypage?>/mypage_order_icon04_off.gif" alt="��ǰ���ۼ�" /><? } ?></td>
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
				//echo "	<td align=center><A HREF=\"javascript:OrderDetailPop('".$row->ordercode."')\" onmouseover=\"window.status='�ֹ�������ȸ';return true;\" onmouseout=\"window.status='';return true;\"><img src=".$Dir."images/common/mypage/".$_data->design_mypage."/mypage_order_icon01.gif></a></td>";
?>
							<td align="center"><? if($row2->deli_gbn=="Y" && $_data->review_type !="N")  { ?><A HREF="javascript:OrderReview('<?=$row->ordercode?>','<?=$row2->productcode?>')" onmouseover="window.status='��ǰ��';return true;" onmouseout="window.status='';return true;"><img src="<?=$Dir?>images/common/mypage/<?=$_data->design_mypage?>/mypage_order_icon04.gif" alt="��ǰ���ۼ�" /></a><? }else{ ?><img src="<?=$Dir?>images/common/mypage/<?=$_data->design_mypage?>/mypage_order_icon04_off.gif" alt="��ǰ���ۼ�" /><? } ?></td>
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
		<td height="20"></td>
	</tr>
	<tr>
		<td>
		<table cellpadding="0" cellspacing="0" width="100%">
		<tr>
			<td><IMG SRC="<?=$Dir?>images/common/mypage/<?=$_data->design_mypage?>/mypage_skin1_text02.gif" border="0"></td>
			<td align="right" style="padding-bottom:3px;"><A HREF="<?=$Dir.FrontDir?>mypage_personal.php"><IMG SRC="<?=$Dir?>images/common/mypage/<?=$_data->design_mypage?>/mypage_skin1_btn01.gif" BORDER="0"></A></td>
		</tr>
		</table>
		</td>
	</tr>
	<tr>
		<td>
		<table cellpadding="0" cellspacing="0" width="100%">
		<col width="110"></col>
		<col></col>
		<col width="65"></col>
		<col width="105"></col>
		<tr>
			<td height="2" colspan="4" bgcolor="#000000"></td>
		</tr>
		<tr height="30" align="center" bgcolor="#F8F8F8" style="letter-spacing:-0.5pt;">
			<td><font color="#333333"><b>���ǳ�¥</b></font></td>
			<td><font color="#333333"><b>����</b></font></td>
			<td><font color="#333333"><b>�亯����</b></font></td>
			<td><font color="#333333"><b>�亯��¥</b></font></td>
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
			echo "	<td align=\"left\"><A HREF=\"javascript:ViewPersonal('".$row->idx."')\"><font color=\"#333333\">".strip_tags($row->subject)."</font></A></td>\n";
			echo "	<td>";
			if(strlen($row->re_date)==14) {
				echo "<img src=\"".$Dir."images/common/mypersonal_skin_icon1.gif\" border=\"0\" align=\"absmiddle\">";
			} else {
				echo "<img src=\"".$Dir."images/common/mypersonal_skin_icon2.gif\" border=\"0\" align=\"absmiddle\">";
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
		<td height="20"></td>
	</tr>
	<tr>
		<td>
		<table cellpadding="0" cellspacing="0" width="100%">
		<tr>
			<td><IMG SRC="<?=$Dir?>images/common/mypage/<?=$_data->design_mypage?>/mypage_skin1_text03.gif" border="0"></td>
			<td align="right" style="padding-bottom:3px;"><A HREF="<?=$Dir.FrontDir?>wishlist.php"><IMG SRC="<?=$Dir?>images/common/mypage/<?=$_data->design_mypage?>/mypage_skin1_btn01.gif" BORDER="0"></A></td>
		</tr>
		</table>
		</td>
	</tr>
	<tr>
		<td>
		<table cellpadding="0" cellspacing="0" width="100%">
		<tr>
			<td height="2" bgcolor="#000000"></td>
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
					echo "<TABLE cellSpacing=\"0\" cellPadding=\"0\" width=\"100%\" border=\"0\" id=\"W".$row->productcode."\" onmouseover=\"quickfun_show(this,'W".$row->productcode."','')\" onmouseout=\"quickfun_show(this,'W".$row->productcode."','none')\">\n";
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
						echo "	<td align=\"center\" class=\"prreserve\"><img src=\"".$Dir."images/common/reserve_icon.gif\" border=\"0\" align=\"absmiddle\">".number_format($reserveconv)."��</td>\n";
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
		<tr>
			<td height="10"></td>
		</tr>
		<tr>
			<td height="1" bgcolor="#DDDDDD"></td>
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