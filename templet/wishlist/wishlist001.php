<table cellpadding="0" cellspacing="0" width="100%">
	<!--
	<tr>
		<td>
		<TABLE WIDTH="100%" BORDER="0" CELLPADDING="0" CELLSPACING="0">
		<TR>
			<TD><A HREF="<?=$Dir.FrontDir?>mypage.php"><IMG SRC="<?=$Dir?>images/common/mypersonal_skin3_menu1.gif" BORDER="0"></A></TD>
			<TD><A HREF="<?=$Dir.FrontDir?>mypage_orderlist.php"><IMG SRC="<?=$Dir?>images/common/mypersonal_skin3_menu2.gif" BORDER="0"></A></TD>
			<TD><A HREF="<?=$Dir.FrontDir?>mypage_personal.php"><IMG SRC="<?=$Dir?>images/common/mypersonal_skin3_menu3.gif" BORDER="0"></A></TD>
			<TD><A HREF="<?=$Dir.FrontDir?>wishlist.php"><IMG SRC="<?=$Dir?>images/common/mypersonal_skin3_menu4r.gif" BORDER="0"></A></TD>
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
	-->
	<tr><td height="10"></td></tr>
<?
	$qry = "WHERE a.id='".$_ShopInfo->getMemid()."' ";
	$qry.= "AND a.productcode=b.productcode AND b.display='Y' ";
	$qry.= "AND (b.group_check='N' OR c.group_code='".$_ShopInfo->getMemgroup()."') ";
	if( $wishCate != 'A' AND $wishCate > 0 ) $qry.= "AND category = ".$wishCate." ";
	else if($wishCate=="0") $qry.= "AND (category = ".$wishCate." or category is null)";

	$sql = "SELECT COUNT(*) as t_count ";
	$sql.= "FROM tblwishlist a, tblproduct b ";
	$sql.= "LEFT OUTER JOIN tblproductgroupcode c ON b.productcode=c.productcode ";
	$sql.= $qry;

	$result=mysql_query($sql,get_db_conn());
	$row=mysql_fetch_object($result);
	$t_count = (int)$row->t_count;
	
	if($t_count < 1){ // 카운트 없을 경우 폴더 삭제 처리 2015.08.04 고객 요청
		//$sql = "delete FROM tblwishlist_category WHERE memid = '".$_ShopInfo->getMemid()."' ";
		//mysql_query($sql,get_db_conn());
		
		//$wishCateList = array();
		//$wishCate = 'A';
	}
	
	mysql_free_result($result);
	$setup[list_num] = 100;
	$pagecount = (($t_count - 1) / $setup[list_num]) + 1;
?>
	
	<tr>
		<td>




		<!--
		카테고리
		-->
		<style>
			.linkButtonStyle{height:22px;padding:0px 8px;line-height:22px;border:1px solid #dddddd;}
			.grayButton{background:#666666;}
		</style>
		<div class="orderStateWrap">
			<div style="margin:0px 20px;">
				<div style="overflow:hidden;padding:11px 0px;margin-bottom:15px;">
				<? $all_fd_style = $wishCate=='A'? 'style="font-weight:bold;text-decoration:underline"':''; ?>
				<a href='?cate=A' <?=$all_fd_style?>>전체보기</a>
				<?
				//echo ( $wishCate=='A' ?'■':'');
				$basic_fd_style = $wishCate=='0'? 'style="font-weight:bold;text-decoration:underline"':'';
				echo "<span style=\"padding:0px 10px;color:#dddddd;font-size:10px;\">|</span><a href='?cate=0' ".$basic_fd_style.">기본폴더</a>";
				//echo ($wishCate=="0"?'■':'');
				foreach ( $wishCateList as $k=>$v ) {
					$fd_style = $wishCate==$k? 'style="font-weight:bold;text-decoration:underline"':'';
					echo "<span style=\"padding:0px 10px;color:#dddddd;font-size:10px;\">|</span><a href='?cate=".$k."' ".$fd_style.">".$v."</a>";
					//echo ($wishCate==$k?'■':'');
				}
				?>
				<a href="javascript:wishCateViewOnOff(cateSetDiv);" class="btn_gray" style="margin-left:10px;"><span>새폴더 추가 +</span></a>
				</div>
				<div id="cateSetDiv" style="display:none;margin:10px 0px;padding:20px;background:#f8f8f8;border:1px solid #f2f2f2;">
					<h2 style="font-size:20px;color:#333333;padding-bottom:15px;">나의 찜 폴더 관리</h2>
					<p><input type="text" name="cateTitle" class="input"/> <input type="button" name="btn_insert" id="btn_insert" value="폴더 생성" onclick="wishCateInsert(this.form);" class="btn_line"><input type="button" name="btn_modify" id="btn_modify" style="display:none" value="폴더 수정" onclick="wishCateModify(this.form);" class="btn_line"></p>
					<p>
					<?
						foreach ( $wishCateList as $k=>$v ) {
							echo "<span style=\"padding:10px 20px 10px 0px;\">".$v." <input type='button' value='수정' onclick=\"wishCateModifyOpen('".$v."','".$k."');\" class=\"btn_sline3\"> <input type='button' value='삭제' onclick=\"wishCateDelete(this.form, '".$k."');\"  class=\"btn_sline3\"></span>";
						}
					?>
					</p>
				</div>
			</div>
		</div>

	
<script language="javascript" type="text/javascript">
	function wishCateModifyOpen(title,idx){
		var btn_insert = document.getElementById('btn_insert');
		var btn_modify = document.getElementById('btn_modify');
		btn_insert.style.display = ( btn_insert.style.display == 'none' ) ? 'inline-block' : 'none';
		btn_modify.style.display = ( btn_modify.style.display == 'none' ) ? 'inline-block' : 'none';
		document.form1.cateTitle.value = title;
		document.form1.delCateIdx.value = idx;				
	}
	function moveWishItems(){
		<? if(count($wishCateList) <1){ ?>
		alert('먼저 폴더를 생성해주세요');
		<? }else{ ?>
		//wishCateViewOnOff(cateMultiSelDiv);
		$j("#cateMultiSelDiv").show();
		$j("#cateMultiSelDiv2").hide();
		<? } ?>						
	}
	function copyWishItems(){
		<? if(count($wishCateList) <1){ ?>
		alert('먼저 폴더를 생성해주세요');
		<? }else{ ?>
		//wishCateViewOnOff(cateMultiSelDiv2);
		$j("#cateMultiSelDiv2").show();
		$j("#cateMultiSelDiv").hide();
		<? } ?>						
	}
</script>

<div style="margin-top:20px;">
						<div style="float:left;">
							<div style="overflow:hidden;height:50px;line-height:60px;width:1000px;">
								<a href="javascript:CheckBoxAll();" class="btn_sline2" style="padding:5px 12px;">전체선택</a>
								<a href="javascript:GoDelete();"  class="btn_sline2" style="padding:5px 12px;">삭제</a>
								<a href="javascript:moveWishItems()" class="btn_sline2" style="padding:5px 12px;">이동</a>
								<a href="javascript:copyWishItems()" class="btn_sline2" style="padding:5px 12px;">복사</a>
								<!-- <a href="javascript:wishToBasketMove('');" class="linkButtonStyle">선택한 상품 장바구니 복사</a> -->
								<? /*
								<a href="javascript:wishToBasketMove('pester');" class="linkButtonStyle">선택한 상품 조르기 복사</a>
								<a href="javascript:wishToBasketMove('present');" class="linkButtonStyle">선택한 상품 선물하기 복사</a> */?>


								<span id="cateMultiSelDiv" style="display:none;width:100%;background:#f2f2f2;" class="orderStateWrap1">
									다른폴더로 상품이동
									<select name="selCate" class="select">
										<option value=''>전체</option>
										<option value='0'>기본폴더</option>
										<?
									//	echo $wishCate;
										foreach ( $wishCateList as $k=>$v ) {
											echo "<option value='".$k."'>".$v."</option>";
										}
										?>
									</select>
									<input type="button" value="확인" onclick="wishCateMove(this.form);">
									<input type="button" value="취소" onclick="wishCateViewOnOff(cateMultiSelDiv);">
								</span>
								<span id="cateMultiSelDiv2" style="display:none;width:100%;background:#f2f2f2;" class="orderStateWrap1">
									다른폴더로 상품복사
									<select name="selCate2" class="select">
										<option value='0'>전체</option>
										<?
										foreach ( $wishCateList as $k=>$v ) {
											echo "<option value='".$k."'>".$v."</option>";
										}
										?>
									</select>
									<input type="button" value="확인" onclick="wishCateCopy(this.form);">
									<input type="button" value="취소" onclick="wishCateViewOnOff(cateMultiSelDiv2);">
								</span>


							</div>
							
						</div>

						<div style="float:right;text-align:right;padding-top:15px;">
							<SELECT onchange=ChangeSort(this.value) name="sort" class="select">
								<option value="date_desc"<?if($sort=="date_desc")echo" selected";?>>최근등록순</option>
								<option value="marks_desc"<?if($sort=="marks_desc")echo" selected";?>>구매우선순위순</option>
								<option value="price_desc"<?if($sort=="price_desc")echo" selected";?>>높은가격순</option>
								<option value="price"<?if($sort=="price")echo" selected";?>>낮은가격순</option>
								<option value="name"<?if($sort=="name")echo" selected";?>>상품명순</option>
							</SELECT>
							<SELECT onchange=ChangeListnum(this.value) name="listnum" class="select">
								<option value="10"<?if($listnum==10)echo" selected";?>>10개씩 정렬</option>
								<option value="20"<?if($listnum==20)echo" selected";?>>20개씩 정렬</option>
								<option value="30"<?if($listnum==30)echo" selected";?>>30개씩 정렬</option>
								<option value="40"<?if($listnum==40)echo" selected";?>>40개씩 정렬</option>
								<option value="50"<?if($listnum==50)echo" selected";?>>50개씩 정렬</option>
							</SELECT>
						</div>
						<p style="clear:both;"></p>
</div>


		<table cellpadding="0" cellspacing="0" width="100%" style="margin-top:5px;" class="orderlistTbl" >
	<?
		$tmp_sort=explode("_",$sort);
		$sql = "SELECT a.opt1_idx,a.opt2_idx,a.optidxs,a.deli_type, a.category, b.productcode,b.productname,b.sellprice,b.sellprice as realprice, ";
		$sql.= "b.reserve,b.reservetype,b.addcode,b.tinyimage,b.option_price,b.option_quantity,b.option1,b.option2,b.rental, ";
		$sql.= "b.etctype,a.wish_idx,a.marks,a.memo,b.selfcode,b.assembleuse,b.package_num FROM tblwishlist a, tblproduct b ";
		$sql.= "LEFT OUTER JOIN tblproductgroupcode c ON b.productcode=c.productcode ";
		$sql.= $qry." ";
		if($tmp_sort[0]=="date") $sql.= "ORDER BY a.date ".$tmp_sort[1]." ";
		else if($tmp_sort[0]=="marks") $sql.= "ORDER BY a.marks ".$tmp_sort[1]." ";
		else if($tmp_sort[0]=="price") $sql.= "ORDER BY b.sellprice ".$tmp_sort[1]." ";
		else if($tmp_sort[0]=="name") $sql.= "ORDER BY b.productname ".$tmp_sort[1]." ";
		else $sql.= "ORDER BY a.date DESC ";
		$sql.= "LIMIT " . ($setup[list_num] * ($gotopage - 1)) . ", " . $setup[list_num];
		$result=mysql_query($sql,get_db_conn());
		$cnt=0;
		while($row=mysql_fetch_object($result)) {
			$row->quantity=1;

			if(ereg("^(\[OPTG)([0-9]{4})(\])$",$row->option1)) {
				$optioncode = substr($row->option1,5,4);
				$row->option1="";
				$row->option_price="";
				if($row->optidxs!="") {
					$tempoptcode = substr($row->optidxs,0,-1);
					$exoptcode = explode(",",$tempoptcode);

					$sqlopt = "SELECT * FROM tblproductoption WHERE option_code='".$optioncode."' ";
					$resultopt = mysql_query($sqlopt,get_db_conn());
					if($rowopt = mysql_fetch_object($resultopt)){
						$optionadd = array (&$rowopt->option_value01,&$rowopt->option_value02,&$rowopt->option_value03,&$rowopt->option_value04,&$rowopt->option_value05,&$rowopt->option_value06,&$rowopt->option_value07,&$rowopt->option_value08,&$rowopt->option_value09,&$rowopt->option_value10);
						$opti=0;
						$optvalue="";
						$option_choice = $rowopt->option_choice;
						$exoption_choice = explode("",$option_choice);
						while(strlen($optionadd[$opti])>0){
							if($exoption_choice[$opti]==1 && $exoptcode[$opti]==0){
								$delsql = "DELETE FROM tblbasket WHERE tempkey='".$_ShopInfo->getTempkey()."' ";
								$delsql.= "AND productcode='".$row->productcode."' ";
								$delsql.= "AND opt1_idx='".$row->opt1_idx."' AND opt2_idx='".$row->opt2_idx."' ";
								$delsql.= "AND optidxs='".$row->optidxs."' ";
								mysql_query($delsql,get_db_conn());
							}
							if($exoptcode[$opti]>0){
								$opval = str_replace('"','',explode("",$optionadd[$opti]));
								$optvalue.= ", ".$opval[0]." : ";
								$exop = str_replace('"','',explode(",",$opval[$exoptcode[$opti]]));
								if ($exop[1]>0) $optvalue.=$exop[0]."(<font color=\"#FF3C00\">+".$exop[1]."원</font>)";
								else if($exop[1]==0) $optvalue.=$exop[0];
								else $optvalue.=$exop[0]."(<font color=\"#FF3C00\">".$exop[1]."원</font>)";
								$row->realprice+=($row->quantity*$exop[1]);
							}
							$opti++;
						}
						$optvalue = substr($optvalue,1);
					}
				}
			} else {
				$optvalue="";
			}

			if (strlen($row->option_price)==0) {
				$price = $row->realprice;
				$tempreserve = getReserveConversion($row->reserve,$row->reservetype,$row->sellprice,"N");
				$sellprice=$row->sellprice;
			} else if (strlen($row->opt1_idx)>0) {
				$option_price = $row->option_price;
				$pricetok=explode(",",$option_price);
				$priceindex = count($pricetok);
				$price = $pricetok[$row->opt1_idx-1]*$row->quantity;
				$tempreserve = getReserveConversion($row->reserve,$row->reservetype,$pricetok[$row->opt1_idx-1],"N");
				$sellprice=$pricetok[$row->opt1_idx-1];
			}
			$bankonly_html = ""; $setquota_html = "";
			if (strlen($row->etctype)>0) {
				$etctemp = explode("",$row->etctype);
				for ($i=0;$i<count($etctemp);$i++) {
					switch ($etctemp[$i]) {
						case "BANKONLY": $bankonly = "Y";
							$bankonly_html = " <img src=\"".$Dir."images/common/bankonly.gif\" border=\"0\"> ";
							break;
						case "SETQUOTA":
							if ($_data->card_splittype=="O" && $price>=$_data->card_splitprice) {
								$setquotacnt++;
								$setquota_html = " <img src=\"".$Dir."images/common/setquota.gif\" border=\"0\">";
								$setquota_html.= "</b><font color=\"#000000\" size=\"1\">(";
								$setquota_html.="3~";
								$setquota_html.= $_data->card_splitmonth.")</font>";
							}
							break;
					}
				}
			}

			$number = ($t_count-($setup[list_num] * ($gotopage-1))-$cnt);
			if($cnt>0) {
			}
			echo "<tr>\n";
			echo "	<td>\n";


			
			echo '<div style="margin:0px 20px;">';
			
			echo "	<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\n";
			echo "	<tr>\n";
			echo "	<td width=\"35\"><input type=checkbox name=sels[] value=\"".$row->wish_idx."\" class=\"checkbox\"></td>";
			echo "	<td width=\"140\" align=\"center\" style=\"border:0px solid #eeeeee;\"><A HREF=\"".$Dir.FrontDir."productdetail.php?productcode=".$row->productcode."\" onmouseover=\"window.status='상품상세조회';return true;\" onmouseout=\"window.status='';return true;\">\n";

			if(strlen($row->tinyimage)!=0 && file_exists($Dir.DataDir."shopimages/product/".$row->tinyimage)){
				$file_size=getImageSize($Dir.DataDir."shopimages/product/".$row->tinyimage);
				echo "<img src=\"".$Dir.DataDir."shopimages/product/".$row->tinyimage."\"";
				if($file_size[0]>=$file_size[1]) echo " width=\"90\"";
				echo " border=0>";
			} else {
			echo "<img src=\"".$Dir."images/no_img.gif\" width=\"60\" border=\"0\">";
			}

			echo "</A></td>\n";
			echo "<td style=\"padding-left:5px;padding-right:5px;word-break:break-all;\">
						<A HREF=\"".$Dir.FrontDir."productdetail.php?productcode=".$row->productcode."\" onmouseover=\"window.status='상품상세조회';return true;\" onmouseout=\"window.status='';return true;\"><span color=\"#333333\"><b>".viewproductname($row->productname,$row->etctype,$row->selfcode)."</b></span></A> ".( $row->category > 0 ? "<strong>[폴더:<a href='?cate=".$row->category."'>".$wishCateList[$row->category]."</a>]</strong>" : '' ).$bankonly_html.$setquota_html;

			echo "<p style=\"padding:5px 0px;\">";
			//상품옵션
			if (strlen($row->option1)>0 || strlen($row->option2)>0 || strlen($optvalue)>0) {
				echo "<img src=\"".$Dir."images/common/icn_option.gif\" border=\"0\" align=absmiddle hspace=\"1\">\n";
				// ###### 특성 #########
				if (strlen($row->option1)>0 && $row->opt1_idx>0) {
					$temp = $row->option1;
					$tok = explode(",",$temp);
					$count=count($tok);
					echo $tok[0]." : ".$tok[$row->opt1_idx]."\n";
				}
				if (strlen($row->option2)>0 && $row->opt2_idx>0) {
					$temp = $row->option2;
					$tok = explode(",",$temp);
					$count=count($tok);
					echo ",&nbsp; ".$tok[0]." : ".$tok[$row->opt2_idx]."\n";
				}
				if(strlen($optvalue)>0) {
					echo $optvalue."\n";
				}
			}
			echo "</p>";
			echo "
				<div>
					<SELECT name=up_marks_".$row->wish_idx." style=\"letter-spacing:-0.5pt;font-size:11px;font-family:'돋움,굴림';\">\n";
					$marks_0=$marks_1=$marks_2=$marks_3=$marks_4=$marks_5="";
					${"marks_".$row->marks}="selected";
					echo "
						<OPTION value=0 ".$marks_0." style=\"color:#000000;\">구매우선순위</OPTION>
						<OPTION value=1 ".$marks_1." style=\"color:#000000;\">★</OPTION>
						<OPTION value=2 ".$marks_2." style=\"color:#000000;\">★★</OPTION>
						<OPTION value=3 ".$marks_3." style=\"color:#000000;\">★★★</OPTION>
						<OPTION value=4 ".$marks_4." style=\"color:#000000;\">★★★★</OPTION>
						<OPTION value=5 ".$marks_5." style=\"color:#000000;\">★★★★★★</OPTION>
					</SELECT>\n";
				echo "
					<INPUT type=text name=\"up_memo_".$row->wish_idx."\" value=\"".$row->memo."\" size=\"52\" maxLength=\"100\" style=\"width:250px;height:35px;border-width:1px; border-color:#dddddd; border-style:solid;\">
				</div>\n";
			echo "</td>\n";
			echo "<td width=\"100\">".$row->deli_type."</td>\n";
			echo "<td width=\"100\"><strong style=\"color:#F02800;\">".number_format($price)."원</strong></td>\n";
			echo "<td width=\"100\"><img src=\"".$Dir."images/common/reserve_icon1.gif\" border=\"0\" align=\"absmiddle\"> ".number_format($tempreserve)."원</td>\n";
			echo "<td width=\"70\" align=\"right\">
						<A HREF=\"javascript:CheckForm('ordernow',".$row->wish_idx.")\"><img src=\"".$Dir."images/common/wishlist/".$_data->design_wishlist."/wishlist_skin3_btn04.gif\" border=\"0\" alt=\"바로구매\" /></A>";
			
//					PrdtQuickCls.quickFun(productcode,'2');	
			if($row->rental == '2'){
				echo "		<A HREF=\"javascript:PrdtQuickCls.quickFun('".$row->productcode."','2')\"><img src=\"".$Dir."images/common/wishlist/".$_data->design_wishlist."/wishlist_skin3_btn03.gif\" border=\"0\" alt=\"장바구니 등록\"></A>";
			}else{
				echo "		<A HREF=\"javascript:CheckForm('',".$row->wish_idx.")\"><img src=\"".$Dir."images/common/wishlist/".$_data->design_wishlist."/wishlist_skin3_btn03.gif\" border=\"0\" alt=\"장바구니 등록\"></A>";
			}
			echo "		<!-- <A HREF=\"javascript:CheckForm('pester',".$row->wish_idx.")\"><img src=\"".$Dir."images/common/wishlist/".$_data->design_wishlist."/wishlist_skin3_btn03.gif\" border=\"0\" alt=\"조르기 등록\"></A> -->
						<!-- <A HREF=\"javascript:CheckForm('present',".$row->wish_idx.")\"><img src=\"".$Dir."images/common/wishlist/".$_data->design_wishlist."/wishlist_skin3_btn03.gif\" border=\"0\" alt=\"선물하기 등록\"></A> -->
						<A HREF=\"javascript:SaveMemo(".$row->wish_idx.")\"><img src=\"".$Dir."images/common/wishlist/".$_data->design_wishlist."/wishlist_skin3_btn05.gif\" border=\"0\" alt=\"저장하기\" /></A>
					</td>\n";
			echo "</tr>\n";
			echo "</table>\n";
			echo "</div>\n";

			echo "	</td>\n";
			echo "</tr>\n";

			$miniq = 1; 
			if (strlen($row->etctype)>0) {
				$etctemp = explode("",$row->etctype);
				for ($i=0;$i<count($etctemp);$i++) {
					if (substr($etctemp[$i],0,6)=="MINIQ=") $miniq=substr($etctemp[$i],6);
				}
			}
			echo "<input type=hidden name=productcode_".$row->wish_idx." value=\"".$row->productcode."\">\n";
			echo "<input type=hidden name=option1_".$row->wish_idx." value=\"".$row->opt1_idx."\">\n";
			echo "<input type=hidden name=option2_".$row->wish_idx." value=\"".$row->opt2_idx."\">\n";
			echo "<input type=hidden name=opts_".$row->wish_idx." value=\"".$row->optidxs."\">\n";
			echo "<input type=hidden name=quantity_".$row->wish_idx." value=\"".$miniq."\">\n";
			echo "<input type=hidden name=deli_type_".$row->wish_idx." value=\"".$row->deli_type."\">\n";
			echo "<input type=hidden name=assembleuse_".$row->wish_idx." value=\"".$row->assembleuse."\">\n";
			echo "<input type=hidden name=packagenum_".$row->wish_idx." value=\"".((int)$row->package_num?$row->package_num:"")."\">\n";
			$cnt++;
		}
		mysql_free_result($result);

		if($cnt==0) {
			echo "<tr><td height=\"30\" colspan=\"6\" align=\"center\">해당내역이 없습니다.</td></tr>\n";
		}
?>
			</table>
		</td>
	</tr>
	<tr><td height="20"></td></tr>
	<tr>
<?
		$total_block = intval($pagecount / $setup[page_num]);

		if (($pagecount % $setup[page_num]) > 0) {
			$total_block = $total_block + 1;
		}

		$total_block = $total_block - 1;

		if (ceil($t_count/$setup[list_num]) > 0) {
			// 이전	x개 출력하는 부분-시작
			$a_first_block = "";
			if ($nowblock > 0) {
				$a_first_block .= "<a href='javascript:GoPage(0,1);' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='첫 페이지';return true\"><FONT class=\"prlist\">[1...]</FONT></a>&nbsp;&nbsp;";

				$prev_page_exists = true;
			}

			$a_prev_page = "";
			if ($nowblock > 0) {
				$a_prev_page .= "<a href='javascript:GoPage(".($nowblock-1).",".($setup[page_num]*($block-1)+$setup[page_num]).");' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='이전 ".$setup[page_num]." 페이지';return true\"><FONT class=\"prlist\">[prev]</FONT></a>&nbsp;&nbsp;";

				$a_prev_page = $a_first_block.$a_prev_page;
			}

			if (intval($total_block) <> intval($nowblock)) {
				$print_page = "";
				for ($gopage = 1; $gopage <= $setup[page_num]; $gopage++) {
					if ((intval($nowblock*$setup[page_num]) + $gopage) == intval($gotopage)) {
						$print_page .= "<FONT class=\"choiceprlist\">".(intval($nowblock*$setup[page_num]) + $gopage)."</font> ";
					} else {
						$print_page .= "<a href='javascript:GoPage(".$nowblock.",".(intval($nowblock*$setup[page_num]) + $gopage).");' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='페이지 : ".(intval($nowblock*$setup[page_num]) + $gopage)."';return true\"><FONT class=\"prlist\">[".(intval($nowblock*$setup[page_num]) + $gopage)."]</FONT></a> ";
					}
				}
			} else {
				if (($pagecount % $setup[page_num]) == 0) {
					$lastpage = $setup[page_num];
				} else {
					$lastpage = $pagecount % $setup[page_num];
				}

				for ($gopage = 1; $gopage <= $lastpage; $gopage++) {
					if (intval($nowblock*$setup[page_num]) + $gopage == intval($gotopage)) {
						$print_page .= "<FONT class=\"choiceprlist\">".(intval($nowblock*$setup[page_num]) + $gopage)."</FONT> ";
					} else {
						$print_page .= "<a href='javascript:GoPage(".$nowblock.",".(intval($nowblock*$setup[page_num]) + $gopage).");' onMouseOver=\"window.status='페이지 : ".(intval($nowblock*$setup[page_num]) + $gopage)."';return true\"><FONT class=\"prlist\">[".(intval($nowblock*$setup[page_num]) + $gopage)."]</FONT></a> ";
					}
				}
			}


			$a_last_block = "";
			if ((intval($total_block) > 0) && (intval($nowblock) < intval($total_block))) {
				$last_block = ceil($t_count/($setup[list_num]*$setup[page_num])) - 1;
				$last_gotopage = ceil($t_count/$setup[list_num]);

				$a_last_block .= "&nbsp;&nbsp;<a href='javascript:GoPage(".$last_block.",".$last_gotopage.");' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='마지막 페이지';return true\"><FONT class=\"prlist\">[...".$last_gotopage."]</FONT></a>";

				$next_page_exists = true;
			}

			$a_next_page = "";
			if ((intval($total_block) > 0) && (intval($nowblock) < intval($total_block))) {
				$a_next_page .= "&nbsp;&nbsp;<a href='javascript:GoPage(".($nowblock+1).",".($setup[page_num]*($nowblock+1)+1).");' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='다음 ".$setup[page_num]." 페이지';return true\"><FONT class=\"prlist\">[next]</FONT></a>";

				$a_next_page = $a_next_page.$a_last_block;
			}
		} else {
			$print_page = "<FONT class=\"prlist\">1</FONT>";
		}
?>
		<td>
		<table cellpadding="0" cellspacing="0" width="100%">
		<tr>
			<td align="center"><?=$a_div_prev_page.$a_prev_page.$print_page.$a_next_page.$a_div_next_page?></td>
		</tr>
		</table>
		</td>
	</tr>
</table>