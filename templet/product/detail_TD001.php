<?
	$_cdata->detail_type = 'AD001'
?>

<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr>
	<td>
	<table border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed">
		<tr>
			<td height="40">
				<table border="0" cellpadding="0" cellspacing="0">
					<tr>
						<td style="padding-right:5px;"><?//=$codenavi?><a href="/">홈</a> &gt; 전용상품권</td>
						<td align="right" style="padding-right:3px; background-repeat:no-repeat; background-position:right;"><A HREF="javascript:ClipCopy('http://<?=$_ShopInfo->getShopurl()?>?<?=getenv("QUERY_STRING")?>')"><img src="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/btn_addr_copy.gif" border="0"></A></td>
					</tr>
				</table>
			</td>
		</tr>
		<tr><td height="20"></td></tr>
		<tr>
			<td style="padding:0px 5px;">
			<table cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed;">
			<form name=form1 method=post action="<?=$Dir.FrontDir?>basket.php">
			<tr>
				<td>
					<table cellpadding="0" cellspacing="0" width="100%" height="100">
					<col width="45%"></col>
					<col width="20"></col>
					<col width=></col>
					<tr>
						<td valign="top" height="100">
							<table cellpadding="0" cellspacing="0" width="100%" height="100%" border="0" style="border:1px solid #e8e8e8;">
							<tr>
								<!-- 상품권 이미지-->
								<td align="center">
									<div style="margin:0 auto; width:222px; padding-top:45px; height:105px; line-height:30px; background:url('/images/design/giftcard_bg.gif') no-repeat;">
										<span style="color:#aaaaaa; line-height:30px; letter-spacing:-2px; font-family:'verdana','돋움'; font-size:30px; font-weight:700;"><?=number_format($_pdata->consumerprice)?></span>원
									</div>
								</td>
							</tr>

							<tr><td height="10"></td></tr>

							<!-- SNS 버튼 출력 -->
							<? INCLUDE ($Dir.TempletDir."product/sns_btn.php"); ?>

						</table>

						<!-- QR 코드 -->
						<!--<img src="http://<?//=$_ShopInfo->getShopurl()?>pqrcode.php?productcode=<?//=$productcode?>" />-->

					</td>
					<td></td>
					<td valign="top">
						<table cellpadding="0" cellspacing="0" width="100%" border="0">
							<tr><td height="10"></td></tr>
							<tr>
								<td>
									<span class="prdetailname""><?=viewproductname($_pdata->productname,$_pdata->etctype,"")?></span>
									<div class="prdetailmsg"><?=$_pdata->prmsg?></div>
								</td>
							</tr>
							<tr><td bgcolor="#444444" HEIGHT="2"></td></tr>
							<tr><td height="12"></td></tr>
							<tr>
								<td>
									<table cellpadding="0" cellspacing="0" width="100%" border="0">
										<!--<col width="15" align="center"></col>-->
										<col width="64"></col>
										<col width="12"></col>
										<col width=></col>
				<?
					//$prproductname ="<td><IMG SRC=\"".$Dir."images/common/product/".$_cdata->detail_type."/pdetail_skin_point.gif\" border=\"0\"></td>\n";
					$prproductname.="<td>상품명</td>\n";
					$prproductname.="<td></td>";
					$prproductname.="<td>".$_pdata->productname."</td>\n";
				
					/*if(strlen($_pdata->production)>0) {
						$prproduction ="<td><IMG SRC=\"".$Dir."images/common/product/".$_cdata->detail_type."/pdetail_skin_point.gif\" border=\"0\"></td>\n";
						$prproduction.="<td>제조회사</td>\n";
						$prproduction.="<td></td>";
						$prproduction.="<td>".$_pdata->production."</td>\n";
					}
					if(strlen($_pdata->madein)>0) {
						$prmadein ="<td><IMG SRC=\"".$Dir."images/common/product/".$_cdata->detail_type."/pdetail_skin_point.gif\" border=\"0\"></td>\n";
						$prmadein.="<td>원산지</td>\n";
						$prmadein.="<td></td>";
						$prmadein.="<td>".$_pdata->madein."</td>\n";
					}
					if(strlen($_pdata->model)>0) {
						$prmodel ="<td><IMG SRC=\"".$Dir."images/common/product/".$_cdata->detail_type."/pdetail_skin_point.gif\" border=\"0\"></td>\n";
						$prmodel.="<td>모델명</td>\n";
						$prmodel.="<td></td>";
						$prmodel.="<td>".$_pdata->model."</td>\n";
					}
					if(strlen($_pdata->brand)>0) {
						$prbrand ="<td><IMG SRC=\"".$Dir."images/common/product/".$_cdata->detail_type."/pdetail_skin_point.gif\" border=\"0\"></td>\n";
						$prbrand.="<td>브랜드</td>\n";
						$prbrand.="<td></td>";
						if($_data->ETCTYPE["BRANDPRO"]=="Y") {
							$prbrand.="<td><a href=\"".$Dir.FrontDir."productblist.php?brandcode=".$_pdata->brandcode."\">".$_pdata->brand."</a></td>\n";
						} else {
							$prbrand.="<td>".$_pdata->brand."</td>\n";
						}
					}
					
					if(strlen($_pdata->userspec)>0) {
						$specarray= explode("=",$_pdata->userspec);
						for($i=0; $i<count($specarray); $i++) {
							$specarray_exp = explode("", $specarray[$i]);
							if(strlen($specarray_exp[0])>0 || strlen($specarray_exp[1])>0) {
								${"pruserspec".$i} ="<td><IMG SRC=\"".$Dir."images/common/product/".$_cdata->detail_type."/pdetail_skin_point.gif\" border=\"0\"></td>\n";
								${"pruserspec".$i}.="<td>".$specarray_exp[0]."</td>\n";
								${"pruserspec".$i}.="<td></td>";
								${"pruserspec".$i}.="<td>".$specarray_exp[1]."</td>\n";
							} else {
								${"pruserspec".$i} = "";
							}
						}
					}
					if(strlen($_pdata->selfcode)>0) {
						$prselfcode ="<td><IMG SRC=\"".$Dir."images/common/product/".$_cdata->detail_type."/pdetail_skin_point.gif\" border=\"0\"></td>\n";
						$prselfcode.="<td>진열코드</td>\n";
						$prselfcode.="<td></td>";
						$prselfcode.="<td>".$_pdata->selfcode."</td>\n";
					}
					if(strlen($_pdata->opendate)>0) {
						$propendate ="<td><IMG SRC=\"".$Dir."images/common/product/".$_cdata->detail_type."/pdetail_skin_point.gif\" border=\"0\"></td>\n";
						$propendate.="<td>출시일</td>\n";
						$propendate.="<td></td>";
						$propendate.="<td>".@substr($_pdata->opendate,0,4).(@substr($_pdata->opendate,4,2)?"-".@substr($_pdata->opendate,4,2):"").(@substr($_pdata->opendate,6,2)?"-".@substr($_pdata->opendate,6,2):"")."</td>\n";
					}*/
					if($_pdata->consumerprice>0) {
						//$prconsumerprice ="<td><IMG SRC=\"".$Dir."images/common/product/".$_cdata->detail_type."/pdetail_skin_point.gif\" border=\"0\"></td>\n";
						$prconsumerprice.="<td>적립금액</td>\n";
						$prconsumerprice.="<td></td>";
						$prconsumerprice.="<td><IMG SRC=\"".$Dir."images/common/won_icon2.gif\" border=\"0\" align=absmiddle><span style=\"font-weight:700;\">".number_format($_pdata->consumerprice)."원</span></td>\n";
					}
					/*
					$SellpriceValue=0;
					if(strlen($dicker=dickerview($_pdata->etctype,number_format($_pdata->sellprice),1))>0) {
						$prsellprice ="<td><IMG SRC=\"".$Dir."images/common/product/".$_cdata->detail_type."/pdetail_skin_point.gif\" border=\"0\"></td>\n";
						$prsellprice.="<td>판매가격</td>\n";
						$prsellprice.="<td></td>";
						$prsellprice.="<td>".$dicker."</td>\n";
						$prdollarprice="";
						$priceindex=0;
					} else if(strlen($optcode)==0 && strlen($_pdata->option_price)>0) {
						$option_price = $_pdata->option_price;
						$pricetok=explode(",",$option_price);
						$priceindex = count($pricetok);
						for($tmp=0;$tmp<=$priceindex;$tmp++) {
							$pricetokdo[$tmp]=number_format($pricetok[$tmp]/$ardollar[1],2);
							$pricetok[$tmp]=number_format($pricetok[$tmp]);
						}
						$prsellprice ="<td><IMG SRC=\"".$Dir."images/common/product/".$_cdata->detail_type."/pdetail_skin_point.gif\" border=\"0\"></td>\n";
						$prsellprice.="<td>판매가격</td>\n";
						$prsellprice.="<td></td>";
						//$prsellprice.="<td><img src=\"".$Dir."images/common/won_icon.gif\" border=\"0\" align=\"absmiddle\"><b><FONT color=\"#F02800\" id=\"idx_price\">".number_format($_pdata->sellprice)."원</FONT></b></td>\n";
						$prsellprice.="<td><img src=\"".$Dir."images/common/won_icon.gif\" border=\"0\" align=\"absmiddle\"><b>".$strikeStart."<FONT color=\"#F02800\" id=\"idx_price\">".number_format($_pdata->sellprice)."원</FONT>".$strikeEnd.$mempricestr."</b></td>\n";


						$prsellprice.="<input type=hidden name=price value=\"".number_format($_pdata->sellprice)."\">\n";

						$prdollarprice ="<td><IMG SRC=\"".$Dir."images/common/product/".$_cdata->detail_type."/pdetail_skin_point.gif\" border=\"0\"></td>\n";
						$prdollarprice.="<td>해외화폐</td>\n";
						$prdollarprice.="<td></td>";
						$prdollarprice.="<td><FONT id=\"idx_dollarprice\">".$ardollar[0]." ".number_format($_pdata->sellprice/$ardollar[1],2)." ".$ardollar[2]."</FONT></td>\n";
						$prdollarprice.="<input type=hidden name=dollarprice value=\"".number_format($_pdata->sellprice/$ardollar[1],2)."\">\n";
						$SellpriceValue=str_replace(",","",$pricetok[0]);
					} else if(strlen($optcode)>0) {
						$prsellprice ="<td><IMG SRC=\"".$Dir."images/common/product/".$_cdata->detail_type."/pdetail_skin_point.gif\" border=\"0\"></td>\n";
						$prsellprice.="<td>판매가격</td>\n";
						$prsellprice.="<td></td>";
						//$prsellprice.="<td><img src=\"".$Dir."images/common/won_icon.gif\" border=\"0\" align=\"absmiddle\"><b><FONT color=\"#F02800\" id=\"idx_price\">".number_format($_pdata->sellprice)."원</FONT></b></td>\n";
						$prsellprice.="<td><img src=\"".$Dir."images/common/won_icon.gif\" border=\"0\" align=\"absmiddle\"><b>".$strikeStart."<FONT color=\"#F02800\" id=\"idx_price\">".number_format($_pdata->sellprice)."원</FONT>".$strikeEnd.$mempricestr."</b></td>\n";
						$prsellprice.="<input type=hidden name=price value=\"".number_format($_pdata->sellprice)."\">\n";

						$prdollarprice ="<td><IMG SRC=\"".$Dir."images/common/product/".$_cdata->detail_type."/pdetail_skin_point.gif\" border=\"0\"></td>\n";
						$prdollarprice.="<td>해외화폐</td>\n";
						$prdollarprice.="<td></td>";
						$prdollarprice.="<td><FONT id=\"idx_dollarprice\">".$ardollar[0]." ".number_format($_pdata->sellprice/$ardollar[1],2)." ".$ardollar[2]."</FONT></td>\n";
						$prdollarprice.="<input type=hidden name=dollarprice value=\"".number_format($_pdata->sellprice/$ardollar[1],2)."\">\n";
						$SellpriceValue=$_pdata->sellprice;
					} else if(strlen($_pdata->option_price)==0) {*/
						/* 상품권*/
							//$prsellprice ="<td><IMG SRC=\"".$Dir."images/common/product/".$_cdata->detail_type."/pdetail_skin_point.gif\" border=\"0\"></td>\n";
							$prsellprice.="<td>판매가격</td>\n";
							$prsellprice.="<td></td>";
							//$prsellprice.="<td><img src=\"".$Dir."images/common/won_icon.gif\" border=\"0\" align=\"absmiddle\"><b><FONT color=\"#F02800\" id=\"idx_price\">".number_format($_pdata->sellprice)."원</FONT></b></td>\n";
							$prsellprice.="<td style=\"border:0px;\"><img src=\"".$Dir."images/common/won_icon.gif\" border=\"0\" align=\"absmiddle\"><b>".$strikeStart."<span class=\"sellprice\" style=\"color:red; font-family:verdana; font-size:24px; line-height:24px;\" id=\"idx_price\">".number_format($_pdata->sellprice)."원</span>".$strikeEnd.$mempricestr."</b></td>\n";

							$prsellprice.="<input type=hidden name=price value=\"".number_format($_pdata->sellprice)."\">\n";

							//$prdollarprice ="<td><IMG SRC=\"".$Dir."images/common/product/".$_cdata->detail_type."/pdetail_skin_point.gif\" border=\"0\"></td>\n";
							$prdollarprice.="<td>해외화폐</td>\n";
							$prdollarprice.="<td></td>";
							$prdollarprice.="<td><FONT id=\"idx_dollarprice\">".$ardollar[0]." ".number_format($_pdata->sellprice/$ardollar[1],2)." ".$ardollar[2]."</FONT></td>\n";
							$prdollarprice.="<input type=hidden name=dollarprice value=\"".number_format($_pdata->sellprice/$ardollar[1],2)."\">\n";
							$SellpriceValue=$_pdata->sellprice;
						
						$priceindex=0;
					//}

					/*// 도매가 관련 추가
					if(isSeller() == 'Y' AND $_pdata->productdisprice > 0 ){
						$prsellprice .="</tr><tr><td><IMG SRC=\"".$Dir."images/common/product/".$_cdata->detail_type."/pdetail_skin_point.gif\" border=\"0\"></td>\n";
						$prsellprice.="<td>도매가격</td>\n";
						$prsellprice.="<td></td>";
						$prsellprice.="<td><img src=\"".$Dir."images/common/won_icon.gif\" border=\"0\" align=\"absmiddle\"><b><FONT color=\"#F02800\" id=\"idx_wsprice\">".number_format($_pdata->productdisprice)."원</FONT></b></td>\n";
					}
					// #도매가 관련 추가


					// 할인율
					if($_pdata->discountRate>0) {
						$prsellprice.="</tr><tr><td><IMG SRC=\"".$Dir."images/common/product/".$_cdata->detail_type."/pdetail_skin_point.gif\" border=\"0\"></td>\n";
						$prsellprice.="<td>할인율</td>\n";
						$prsellprice.="<td></td>";
						$prsellprice.="<td><IMG SRC=\"".$Dir."images/common/won_icon2.gif\" border=\"0\" align=absmiddle>".number_format($_pdata->discountRate)."%</td></tr><tr>\n";
					}

					//사은품 관련 추가
					//if(strlen($_pdata->madein)>0) {
					if(!_empty($giftprice) && intval($giftprice) > 0){
						$prgift ="<td><IMG SRC=\"".$Dir."images/common/product/".$_cdata->detail_type."/pdetail_skin_point.gif\" border=\"0\"></td>\n";
						$prgift.="<td>사은품</td>\n";
						$prgift.="<td></td>";
						$prgift.="<td><div style=position:relative;>정책보기 <a href=# onmouseover=\"showGift.style.visibility='visible'\" onmouseout=\"showGift.style.visibility='hidden'\">[?]</a></div>\n";
						$prgift.="<div id=\"showGift\" style=\"width:260px; margin:0px; padding:10px; position:absolute; background:#ffffff; color:#666; font-size:11px; border:1 solid #ccc; visible; z-index:100; visibility:hidden;\">상품 판매가격이 아닌 할인혜택적용 이후 실 구매가격이 <b>".number_format($giftprice)."원</b> 이상 적용되며, 재고수량이 정해진 경우나 개별상품에 따라 사은품이 적용 불가능할 수 있습니다.</div></td>";
					}
					//}

					//배송비 관련 추가
					if(substr($_pdata->productcode,0,3)!='999') {
						//if(strlen($_pdata->madein)>0) {
							$prtrans ="<td><IMG SRC=\"".$Dir."images/common/product/".$_cdata->detail_type."/pdetail_skin_point.gif\" border=\"0\"></td>\n";
							$prtrans.="<td>배송비</td>\n";
							$prtrans.="<td></td>";
							$prtrans.="<td style=font-size:11px;>".$delipriceTxt." / ".$deliRangeStr."</td>\n";
						//}
					}


					$_pdata->sellprice = ( $memberprice > 0 ) ? $memberprice : $_pdata->sellprice;
					$reserveconv=getReserveConversion($_pdata->reserve,$_pdata->reservetype,$_pdata->sellprice,"Y");
					//sns홍보일 경우 적립금
					if($_data->sns_ok == "Y" && $_pdata->sns_state == "Y" && $sell_memid !=""){
						$reserveconv = getReserveConversionSNS($reserveconv,$_pdata->sns_reserve2,$_pdata->sns_reserve2_type,$_pdata->sellprice,"Y");
					}

					$categoryAuth = categoryAuth ( $productcode );

					if($reserveconv>0 AND $categoryAuth['reserve'] == "Y") {
						$prreserve ="<td><IMG SRC=\"".$Dir."images/common/product/".$_cdata->detail_type."/pdetail_skin_point.gif\" border=\"0\"></td>\n";
						$prreserve.="<td>적립금</td>\n";
						$prreserve.="<td></td>";
						$prreserve.="<td><IMG SRC=\"".$Dir."images/common/reserve_icon1.gif\" border=\"0\" align=absmiddle>";
						if($sell_memid !=""){
							$prreserve.="<span style=\"color:#CC0000\">(sns홍보)</span> ";
						}
						$prreserve.="<b><FONT id=\"idx_reserve\">".number_format($reserveconv)."원</font></b></td>\n";
					}else{
						$prreserve = '';
					}
					if(strlen($_pdata->addcode)>0) {
						$praddcode ="<td><IMG SRC=\"".$Dir."images/common/product/".$_cdata->detail_type."/pdetail_skin_point.gif\" border=\"0\"></td>\n";
						$praddcode.="<td>특이사항</td>\n";
						$praddcode.="<td></td>";
						$praddcode.="<td>".$_pdata->addcode."</td>\n";
					}
					*/

					//$prquantity ="<td><IMG SRC=\"".$Dir."images/common/product/".$_cdata->detail_type."/pdetail_skin_point.gif\" border=\"0\"></td>\n";
					$prquantity.="<td><span class=\"prSellprice\">구매수량</span></td>\n";
					$prquantity.="<td></td>";
					$prquantity.="<td>\n";
					$prquantity.="<table cellpadding=\"1\" cellspacing=\"0\" width=\"60\">\n";
					$prquantity.="<tr>\n";
					$prquantity.="	<td width=\"33\"><input type=text name=\"quantity\" value=\"".($miniq>1?$miniq:"1")."\" size=\"4\" class=\"input\" style=\"BACKGROUND-COLOR:#F7F7F7;\"".(($_pdata->assembleuse=="Y" || substr($productcode,0,3)=='999')?" readonly":" onkeyup=\"strnumkeyup(this)\"");
					if(substr($productcode,0,3)=='999') $prquantity.=" readonly";
					$prquantity.="></td>\n";
					$prquantity.="	<td width=\"33\" style=\"padding-left:4px;padding-right:4px;\">\n";
					if(substr($productcode,0,3)!='999') {
						$prquantity.="	<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\n";
						$prquantity.="	<tr>\n";
						$prquantity.="		<td width=\"5\" height=\"7\" valign=\"top\" style=\"padding-bottom:1px;\"><a href=\"javascript:change_quantity('up')\"><img src=\"".$Dir."images/common/product/".$_cdata->detail_type."/pdetail_skin_neroup.gif\" border=\"0\"></a></td>\n";
						$prquantity.="	</tr>\n";
						$prquantity.="	<tr>\n";
						$prquantity.="		<td width=\"5\" height=\"7\" valign=\"bottom\" style=\"padding-top:1px;\"><a href=\"javascript:change_quantity('dn')\"><img src=\"".$Dir."images/common/product/".$_cdata->detail_type."/pdetail_skin_nerodown.gif\" border=\"0\"></a></td>\n";
						$prquantity.="	</tr>\n";
						$prquantity.="	</table>\n";
					}
					$prquantity.="	</td>\n";
					$prquantity.="	<td width=\"33\">EA</td>\n";
					$prquantity.="</tr>\n";
					$prquantity.="</table>\n";
					$prquantity.="</td>\n";

					// 패키지 선택 출력
					/*$arrpackage_title=array();
					$arrpackage_list=array();
					$arrpackage_price=array();
					$arrpackage_pricevalue=array();
					if((int)$_pdata->package_num>0) {
						$sql = "SELECT * FROM tblproductpackage WHERE num='".(int)$_pdata->package_num."' ";
						$result = mysql_query($sql,get_db_conn());
						$package_count=0;
						if($row = @mysql_fetch_object($result)) {
							mysql_free_result($result);
							if(strlen($row->package_title)>0) {
								$arrpackage_title = explode("",$row->package_title);
								$arrpackage_list = explode("",$row->package_list);
								$arrpackage_price = explode("",$row->package_price);

								$package_listrep = str_replace("","",$row->package_list);

								if(strlen($package_listrep)>0) {
									$sql = "SELECT pridx,productcode,productname,sellprice,tinyimage,quantity,etctype FROM tblproduct ";
									$sql.= "WHERE pridx IN ('".str_replace(",","','",$package_listrep)."') ";
									$sql.= "AND assembleuse!='Y' ";
									$sql.= "AND display='Y' ";
									$result2 = mysql_query($sql,get_db_conn());
									while($row2 = @mysql_fetch_object($result2)) {
										$arrpackage_proinfo[productcode][$row2->pridx] = $row2->productcode;
										$arrpackage_proinfo[productname][$row2->pridx] = $row2->productname;
										$arrpackage_proinfo[sellprice][$row2->pridx] = $row2->sellprice;
										$arrpackage_proinfo[tinyimage][$row2->pridx] = $row2->tinyimage;
										$arrpackage_proinfo[quantity][$row2->pridx] = $row2->quantity;
										$arrpackage_proinfo[etctype][$row2->pridx] = $row2->etctype;
									}
									@mysql_free_result($result2);
								}

								for($t=1; $t<count($arrpackage_list); $t++) {
									$arrpackage_pricevalue[0]=0;
									$arrpackage_pricevalue[$t]=0;
									if(strlen($arrpackage_list[$t])>0) {
										$arrpackage_list_exp = explode(",",$arrpackage_list[$t]);
										$sumsellprice=0;
										for($tt=0; $tt<count($arrpackage_list_exp); $tt++) {
											$sumsellprice += (int)$arrpackage_proinfo[sellprice][$arrpackage_list_exp[$tt]];
										}

										if((int)$sumsellprice>0) {
											$arrpackage_pricevalue[$t]=(int)$sumsellprice;
											if(strlen($arrpackage_price[$t])>0) {
												$arrpackage_price_exp = explode(",",$arrpackage_price[$t]);
												if(strlen($arrpackage_price_exp[0])>0 && $arrpackage_price_exp[0]>0) {
													$sumsellpricecal=0;
													if($arrpackage_price_exp[1]=="Y") {
														$sumsellpricecal = ((int)$sumsellprice*$arrpackage_price_exp[0])/100;
													} else {
														$sumsellpricecal = $arrpackage_price_exp[0];
													}
													if($sumsellpricecal>0) {
														if($arrpackage_price_exp[2]=="Y") {
														} else {
															$sumsellpricecal = $sumsellprice+$sumsellpricecal;
														}
														if($sumsellpricecal>0) {
															if($arrpackage_price_exp[4]=="F") {
																$sumsellpricecal = floor($sumsellpricecal/($arrpackage_price_exp[3]*10))*($arrpackage_price_exp[3]*10);
															} else if($arrpackage_price_exp[4]=="R") {
																$sumsellpricecal = round($sumsellpricecal/($arrpackage_price_exp[3]*10))*($arrpackage_price_exp[3]*10);
															} else {
																$sumsellpricecal = ceil($sumsellpricecal/($arrpackage_price_exp[3]*10))*($arrpackage_price_exp[3]*10);
															}
															$arrpackage_pricevalue[$t]=$sumsellpricecal;
														}
													}
												}
											}
										}
									}
									$propackage_option.= "<option value=\"".$t."\" style=\"color:#ffffff;\">".$arrpackage_title[$t]."</option>\n";
									$package_count++;
								}
							}
						}

						if($package_count>0) {
							$prpackage ="<tr height=\"22\">";
							$prpackage.="	<td><IMG SRC=\"".$Dir."images/common/product/".$_cdata->detail_type."/pdetail_skin_point.gif\" border=\"0\"></td>\n";
							$prpackage.="	<td>패키지선택</td>\n";
							$prpackage.="	<td></td>";
							$prpackage.="	<td>\n";
							$prpackage.="	<select name=\"package_idx\" size=\"1\" style=\"font-size:11px;background-color:#404040;letter-spacing:-0.5pt;\" ";
							if($_data->proption_size>0) $prpackage.="style=\"width : ".$_data->proption_size."px;\" ";
							$prpackage.=")\" onchange=\"packagecal()\">\n";
							$prpackage.=	"<option value=\"\" style=\"color:#ffffff;\">패키지를 선택하세요</option>\n";
							$prpackage.=	"<option value=\"\" style=\"color:#ffffff;\">-------------------\n";
							$prpackage.=	$propackage_option;
							$prpackage.="	</select>\n";
							$prpackage.="	</td>\n";
							$prpackage.="</tr>\n";
							$prpackage.="<input type=hidden name=\"package_type\" value=\"".$row->package_type."\">\n";
						}
					}

					$proption1="";
					if(strlen($_pdata->option1)>0) {
						$temp = $_pdata->option1;
						$tok = explode(",",$temp);
						$count=count($tok);
						$proption1.="<table cellpadding=\"0\" cellspacing=\"0\">\n";
						$proption1.="<tr>\n";
						$proption1.="	<td align=\"right\">$tok[0]&nbsp;:&nbsp;</td>\n";
						$proption1.="	<td>";
						if ($priceindex!=0) {
							$proption1.="<select name=\"option1\" size=\"1\" style=\"font-size:11px;color:#ffffff;background-color:#404040;letter-spacing:-0.5pt;\" ";
							if($_data->proption_size>0) $proption1.="style=\"width : ".$_data->proption_size."px\" ";
							$proption1.="onchange=\"change_price(1,document.form1.option1.selectedIndex-1,";
							if(strlen($_pdata->option2)>0) $proption1.="document.form1.option2.selectedIndex-1";
							else $proption1.="''";
							$proption1.=")\">\n";
						} else {
							$proption1.="<select name=\"option1\" size=\"1\" style=\"font-size:11px;color:#ffffff;background-color:#404040;letter-spacing:-0.5pt;\" ";
							if($_data->proption_size>0) $proption1.="style=\"width : ".$_data->proption_size."px\" ";
							$proption1.="onchange=\"change_price(0,document.form1.option1.selectedIndex-1,";
							if(strlen($_pdata->option2)>0) $proption1.="document.form1.option2.selectedIndex-1";
							else $proption1.="''";
							$proption1.=")\">\n";
						}

						$optioncnt = explode(",",substr($_pdata->option_quantity,1));
						$proption1.="<option value=\"\" style=\"color:#ffffff;\">옵션을 선택하세요\n";
						$proption1.="<option value=\"\" style=\"color:#ffffff;\">-----------------\n";
						$option_price = $_pdata->option_price;
						for($i=1;$i<$count;$i++) {
							$pricetokTemp = 0;
							if( !empty($option_price) ) {
								$pricetok=explode(",",$option_price);
								if( $pricetok[$i-1] > 0 ){
									$pricetokTemp = ( $pricetok[$i-1] - $discountprices ) - $_pdata->sellprice;
									$pricetokTempFlag = ($pricetokTemp>0) ? "+" : "";
								}
							}
							$priceView = ( $pricetokTemp == 0 ) ? "" : " (".$pricetokTempFlag.number_format($pricetokTemp)."원)";
							if(strlen($tok[$i])>0) $proption1.="<option value=\"".$i."\" style=\"color:#ffffff;\">".$tok[$i].$priceView."\n";
							if(strlen($_pdata->option2)==0 && $optioncnt[$i-1]=="0") $proption1.=" (품절)";
						}
						$proption1.="</select>";
					} else {
						//$proption1.="<input type=hidden name=option1>";
					}

					$proption2="";
					if(strlen($_pdata->option2)>0) {
						$temp = $_pdata->option2;
						$tok = explode(",",$temp);
						$count2=count($tok);
						if(strlen($_pdata->option1)<=0) {
							$proption2.="<table cellpadding=\"0\" cellspacing=\"0\">\n";
						}
						$proption2.="<tr>\n";
						$proption2.="	<td align=\"right\">$tok[0]&nbsp;:&nbsp;</td>\n";
						$proption2.="	<td>";
						$proption2.="<select name=\"option2\" size=\"1\" style=\"font-size:11px;color:#ffffff;background-color:#404040;letter-spacing:-0.5pt;\" ";
						if($_data->proption_size>0) $proption2.="style=\"width : ".$_data->proption_size."px\" ";
						$proption2.="onchange=\"change_price(0,";
						if(strlen($_pdata->option1)>0) $proption2.="document.form1.option1.selectedIndex-1";
						else $proption2.="''";
						$proption2.=",document.form1.option2.selectedIndex-1)\">\n";
						$proption2.="<option value=\"\" style=\"color:#ffffff;\">옵션을 선택하세요\n";
						$proption2.="<option value=\"\" style=\"color:#ffffff;\">-----------------\n";
						for($i=1;$i<$count2;$i++) if(strlen($tok[$i])>0) $proption2.="<option value=\"$i\" style=\"color:#ffffff;\">$tok[$i]\n";
						$proption2.="</select>";
						$proption2.="	</td>\n";
						$proption2.="</tr>\n";
						$proption2.="</table>\n";
					} else {
						//$proption2.="<input type=hidden name=option2>";
						if(strlen($_pdata->option1)>0) {
						$proption1.="	</td>\n";
						$proption1.="</tr>\n";
						$proption1.="</table>\n";
						}
					}

					if(strlen($optcode)>0) {
						$sql = "SELECT * FROM tblproductoption WHERE option_code='".$optcode."' ";
						$result = mysql_query($sql,get_db_conn());
						if($row = mysql_fetch_object($result)) {
							$optionadd = array (&$row->option_value01,&$row->option_value02,&$row->option_value03,&$row->option_value04,&$row->option_value05,&$row->option_value06,&$row->option_value07,&$row->option_value08,&$row->option_value09,&$row->option_value10);
							$opti=0;
							$option_choice = $row->option_choice;
							$exoption_choice = explode("",$option_choice);
							$proption3.="<TABLE cellSpacing=\"0\" cellPadding=\"0\" border=\"0\">\n";
							while(strlen($optionadd[$opti])>0) {
								$proption3.="[OPT]";
								$proption3.="<select name=\"mulopt\" style=\"font-size:11px;background-color:#404040;letter-spacing:-0.5pt;\" onchange=\"chopprice('$opti')\"";
								if($_data->proption_size>0) $proption3.=" style=\"width : ".$_data->proption_size."px\"";
								$proption3.=">";
								$opval = str_replace('"','',explode("",$optionadd[$opti]));
								$proption3.="<option value=\"0,0\" style=\"color:#ffffff;\">--- ".$opval[0].($exoption_choice[$opti]==1?"(필수)":"(선택)")." ---";
								$opcnt=count($opval);
								for($j=1;$j<$opcnt;$j++) {
									$exop = str_replace('"','',explode(",",$opval[$j]));
									$proption3.="<option value=\"".$opval[$j]."\" style=\"color:#ffffff;\">";
									if($exop[1]>0) $proption3.=$exop[0]."(+".$exop[1]."원)";
									else if($exop[1]==0) $proption3.=$exop[0];
									else $proption3.=$exop[0]."(".$exop[1]."원)";
								}
								$proption3.="</select><input type=hidden name=\"opttype\" value=\"0\"><input type=hidden name=\"optselect\" value=\"".$exoption_choice[$opti]."\">[OPTEND]";
								$opti++;
							}
							$proption3.="<input type=hidden name=\"mulopt\"><input type=hidden name=\"opttype\"><input type=hidden name=\"optselect\">";
							$proption3.="</TABLE>\n";
						}
						mysql_free_result($result);
					}

					// 사용불가체크 관련
					$useableStr = '';
					
					foreach($_pdata->checkAbles as $chkidx=>$etcchk){
						switch($chkidx){
							case 'coupon': $etcname= '할인쿠폰'; break;
							case 'reserve': $etcname= '적립금'; break;
							case 'gift': $etcname= '구매사은품'; break;
							case 'return': $etcname= '반품/교환'; break;
							default:continue;
						}

						$useableStr .="<tr height=\"22\">";
						$useableStr.="	<td><IMG SRC=\"".$Dir."images/common/product/".$_cdata->detail_type."/pdetail_skin_point.gif\" border=\"0\"></td>\n";
						$useableStr.="	<td>".$etcname."</td>\n";
						$useableStr.="	<td></td>";
						$useableStr.="	<td>".(($etcchk == 'Y')?'<span style="color:blue">적용가능</span>':'<span style="color:red">적용불가</span>').'</td>';
					}
					*/
					//$useableStr.="<tr height=\"22\">";


					if($_pdata->checkAbles['return'] != 'Y'){ // 교환 반품 가능
						//$useableStr.="	<td><IMG SRC=\"".$Dir."images/common/product/".$_cdata->detail_type."/pdetail_skin_point.gif\" border=\"0\"></td>\n";
						$useableStr.="	<td>반품/교환</td>\n";
						$useableStr.="	<td></td>";
						$useableStr.="	<td><span style='color:red'>불가</span></td>";
					}

					//echo $useableStr;
					//#사용불가체크 관련

					/// 쿠폰 전체 팝업 링크(상품권은 제외하고 출력하기 J.Bum)
					/*if(substr($_pdata->productcode,0,3)!='999') {
						$couponpoplink = '';
						if(_array($couponItems)){
							//$couponpoplink.="	<td><IMG SRC=\"".$Dir."images/common/product/".$_cdata->detail_type."/pdetail_skin_point.gif\" border=\"0\"></td>\n";
							$couponpoplink.="	<td>쿠폰</td>\n";
							$couponpoplink.="	<td></td>";
							$couponpoplink.='	<td><a href="javascript:ableCouponPOP(\''.$_pdata->productcode.'\')"><u>적용가능 전체쿠폰<u></a></td>';
						}
					}*/

					for($i=0;$i<$prcnt;$i++) {
						if(substr($arexcel[$i],0,1)=="O") {	//공백
							echo "<tr><td colspan=\"4\" height=\"5\" bgcolor=\"#FFFFFF\"></td></tr>\n";
						} else if ($arexcel[$i]=="7") {	//옵션
							if(strlen($proption1)>0 || strlen($proption2)>0 || strlen($proption3)>0) {
								$proption ="<tr height=\"22\">";
								//$proption.="	<td><IMG SRC=\"".$Dir."images/common/product/".$_cdata->detail_type."/pdetail_skin_point.gif\" border=\"0\"></td>\n";
								$proption.="	<td>상품옵션</td>\n";
								$proption.="	<td></td>";
								$proption.="	<td>\n";
								//$proption.="	<TABLE cellSpacing=\"0\" cellPadding=\"0\" border=\"0\">\n";
								if(strlen($proption1)>0) {
									$proption.=$proption1;
								}
								if(strlen($proption2)>0) {
									$proption.=$proption2;
								}
								if(strlen($proption3)>0) {
									$pattern=array("[OPT]","[OPTEND]");
									$replace=array("<tr><td>","</td></tr>");
									$proption.=str_replace($pattern,$replace,$proption3);
								}
								//$proption.="	</table>\n";
								$proption.="	</td>\n";
								$proption.="</tr>\n";

								echo $arproduct[$arexcel[$i]];
							} else {
								$proption ="<input type=hidden name=\"option1\">\n";
								$proption.="<input type=hidden name=\"option2\">\n";
							}
						} else if(strlen(trim($arproduct[$arexcel[$i]]))>0) {	//
							echo "<tr height=\"22\">".$arproduct[$arexcel[$i]]."</tr>\n";
							//echo "<tr><td height=1 bgcolor=#FFFFFF></td></tr>\n";
							if($arexcel[$i]=="9") $dollarok="Y";
						}
					}

	?>
						</table>
						</td>
					</tr>
	<script language="JavaScript">
	var miniq=<?=($miniq>1?$miniq:1)?>;
	var ardollar=new Array(3);
	ardollar[0]="<?=$ardollar[0]?>";
	ardollar[1]="<?=$ardollar[1]?>";
	ardollar[2]="<?=$ardollar[2]?>";
	<?
	if(strlen($optcode)==0) {
		$maxnum=($count2-1)*10;
		if($optioncnt>0) {
			echo "num = new Array(";
			for($i=0;$i<$maxnum;$i++) {
				if ($i!=0) echo ",";
				if(strlen($optioncnt[$i])==0) echo "100000";
				else echo $optioncnt[$i];
			}
			echo ");\n";
		}
	?>

	function change_price(temp,temp2,temp3) {
	<?=(strlen($dicker)>0)?"return;\n":"";?>
		if(temp3=="") temp3=1;
		price = new Array(
			<?
				if($priceindex>0) {
					echo "'".number_format($_pdata->sellprice)."','".number_format($_pdata->sellprice)."',";
					for($i=0;$i<$priceindex;$i++) {
						if ($i>0) {
							echo ",";
						}
						echo "'".number_format($pricetok[$i])."'";
					}
				}
			?>
		);
		doprice = new Array(
			<?
				if($priceindex>0) {
					echo "'".number_format($_pdata->sellprice/$ardollar[1],2)."','".number_format($_pdata->sellprice/$ardollar[1],2)."',";
					for($i=0;$i<$priceindex;$i++) {
						if ($i!=0) {
							echo ",";
						}
						echo "'".$pricetokdo[$i]."'";
					}
				}
			?>
		);

		if(temp==1) {
			if (document.form1.option1.selectedIndex><? echo $priceindex+2 ?>)
				temp = <?=$priceindex?>;
			else temp = document.form1.option1.selectedIndex;
			document.form1.price.value = price[temp];
			var priceValue = document.form1.price.value.replace(/,/gi,"");
			document.all["idx_price"].innerHTML = number_format( priceValue ) + "원";
			if (document.all["memberprice"]) {
				var discountprices = parseInt(<?=$discountprices?>);
				priceValue = priceValue - discountprices;
				document.all["memberprice"].innerHTML = number_format(priceValue);
			}
	<?if($_pdata->reservetype=="Y" && $_pdata->reserve>0) { ?>
			if(document.getElementById("idx_reserve")) {
				var reserveInnerValue="0";
				if(priceValue>0) {
					var ReservePer=<?=$_pdata->reserve?>;
					var ReservePriceValue=Number(priceValue);
					if(ReservePriceValue>0) {
						reserveInnerValue = Math.round(ReservePer*ReservePriceValue*0.01)+"";
						var result = "";
						for(var i=0; i<reserveInnerValue.length; i++) {
							var tmp = reserveInnerValue.length-(i+1);
							if(i%3==0 && i!=0) result = "," + result;
							result = reserveInnerValue.charAt(tmp) + result;
						}
						reserveInnerValue = result;
					}
				}
				document.getElementById("idx_reserve").innerHTML = reserveInnerValue+"원";
			}
	<? } ?>
			if(typeof(document.form1.dollarprice)=="object") {
				document.form1.dollarprice.value = doprice[temp];
				document.all["idx_dollarprice"].innerHTML=ardollar[0]+" "+document.form1.dollarprice.value+" "+ardollar[2];
			}
		}
		//packagecal(); //패키지 상품 적용
		if(temp2>0 && temp3>0) {
			if(num[(temp3-1)*10+(temp2-1)]==0){
				alert('해당 상품의 옵션은 품절되었습니다. 다른 상품을 선택하세요.');
				if(document.form1.option1.type!="hidden") document.form1.option1.focus();
				return;
			}
		} else {
			if(temp2<=0 && document.form1.option1.type!="hidden") document.form1.option1.focus();
			else document.form1.option2.focus();
			return;
		}
	}

	<? } else if(strlen($optcode)>0) { ?>

	function chopprice(temp){
	<?=(strlen($dicker)>0)?"return;\n":"";?>
		ind = document.form1.mulopt[temp];
		price = ind.options[ind.selectedIndex].value;
		originalprice = document.form1.price.value.replace(/,/g, "");
		document.form1.price.value=Number(originalprice)-Number(document.form1.opttype[temp].value);
		if(price.indexOf(",")>0) {
			optprice = price.substring(price.indexOf(",")+1);
		} else {
			optprice=0;
		}
		document.form1.price.value=Number(document.form1.price.value)+Number(optprice);
		if(typeof(document.form1.dollarprice)=="object") {
			document.form1.dollarprice.value=(Math.round(((Number(document.form1.price.value))/ardollar[1])*100)/100);
			document.all["idx_dollarprice"].innerHTML=ardollar[0]+" "+document.form1.dollarprice.value+" "+ardollar[2];
		}
		document.form1.opttype[temp].value=optprice;
		var num_str = document.form1.price.value.toString()
		var result = ''

		for(var i=0; i<num_str.length; i++) {
			var tmp = num_str.length-(i+1)
			if(i%3==0 && i!=0) result = ',' + result
			result = num_str.charAt(tmp) + result
		}
		document.form1.price.value = result;
		document.all["idx_price"].innerHTML=document.form1.price.value+"원";
		packagecal(); //패키지 상품 적용
	}

	<?}?>
	<? if($_pdata->assembleuse=="Y") { ?>
	function setTotalPrice(tmp) {
	<?=(strlen($dicker)>0)?"return;\n":"";?>
		var i=true;
		var j=1;
		var totalprice=0;
		while(i) {
			if(document.getElementById("acassemble"+j)) {
				if(document.getElementById("acassemble"+j).value) {
					arracassemble = document.getElementById("acassemble"+j).value.split("|");
					if(arracassemble[2].length) {
						totalprice += arracassemble[2]*1;
					}
				}
			} else {
				i=false;
			}
			j++;
		}
		totalprice = totalprice*tmp;
		var num_str = totalprice.toString();
		var result = '';
		for(var i=0; i<num_str.length; i++) {
			var tmp = num_str.length-(i+1);
			if(i%3==0 && i!=0) result = ',' + result;
			result = num_str.charAt(tmp) + result;
		}
		if(typeof(document.form1.price)=="object") { document.form1.price.value=totalprice; }
		if(typeof(document.form1.dollarprice)=="object") {
			document.form1.dollarprice.value=(Math.round(((Number(document.form1.price.value))/ardollar[1])*100)/100);
			document.all["idx_dollarprice"].innerHTML=ardollar[0]+" "+document.form1.dollarprice.value+" "+ardollar[2];
		}
		if(document.getElementById("idx_assembleprice")) { document.getElementById("idx_assembleprice").value = result; }
		if(document.getElementById("idx_price")) { document.getElementById("idx_price").innerHTML = result+"원"; }
		if(document.getElementById("idx_price_graph")) { document.getElementById("idx_price_graph").innerHTML = result+"원"; }
		<?if($_pdata->reservetype=="Y" && $_pdata->reserve>0) { ?>
			if(document.getElementById("idx_reserve")) {
				var reserveInnerValue="0";
				if(document.form1.price.value.length>0) {
					var ReservePer=<?=$_pdata->reserve?>;
					var ReservePriceValue=Number(document.form1.price.value.replace(/,/gi,""));
					if(ReservePriceValue>0) {
						reserveInnerValue = Math.round(ReservePer*ReservePriceValue*0.01)+"";
						var result = "";
						for(var i=0; i<reserveInnerValue.length; i++) {
							var tmp = reserveInnerValue.length-(i+1);
							if(i%3==0 && i!=0) result = "," + result;
							result = reserveInnerValue.charAt(tmp) + result;
						}
						reserveInnerValue = result;
					}
				}
				document.getElementById("idx_reserve").innerHTML = reserveInnerValue+"원";
			}
		<? } ?>
	}
	<? } ?>

	function packagecal() {
	<?=(count($arrpackage_pricevalue)==0?"return;\n":"")?>
		pakageprice = new Array(<? for($i=0;$i<count($arrpackage_pricevalue);$i++) { if ($i!=0) { echo ",";} echo "'".$arrpackage_pricevalue[$i]."'"; }?>);
		var result = "";
		var intgetValue = document.form1.price.value.replace(/,/g, "");
		var temppricevalue = "0";
		for(var j=1; j<pakageprice.length; j++) {
			if(document.getElementById("idx_price"+j)) {
				temppricevalue = (Number(intgetValue)+Number(pakageprice[j])).toString();
				result="";
				for(var i=0; i<temppricevalue.length; i++) {
					var tmp = temppricevalue.length-(i+1);
					if(i%3==0 && i!=0) result = "," + result;
					result = temppricevalue.charAt(tmp) + result;
				}
				document.getElementById("idx_price"+j).innerHTML=result+"원";
			}
		}

		if(typeof(document.form1.package_idx)=="object") {
			var packagePriceValue = Number(intgetValue)+Number(pakageprice[Number(document.form1.package_idx.value)]);

			if(packagePriceValue>0) {
				result = "";
				packagePriceValue = packagePriceValue.toString();
				for(var i=0; i<packagePriceValue.length; i++) {
					var tmp = packagePriceValue.length-(i+1);
					if(i%3==0 && i!=0) result = "," + result;
					result = packagePriceValue.charAt(tmp) + result;
				}
				returnValue = result;
			} else {
				returnValue = "0";
			}
			if(document.getElementById("idx_price")) {
				document.getElementById("idx_price").innerHTML=returnValue+"원";
			}
			if(document.getElementById("idx_price_graph")) {
				document.getElementById("idx_price_graph").innerHTML=returnValue+"원";
			}
			if(typeof(document.form1.dollarprice)=="object") {
				document.form1.dollarprice.value=Math.round((packagePriceValue/ardollar[1])*100)/100;
				if(document.getElementById("idx_price_graph")) {
					document.getElementById("idx_price_graph").innerHTML=ardollar[0]+" "+document.form1.dollarprice.value+" "+ardollar[2];
				}
			}
		}
	}
	</script>

					<tr><td height="12"></td></tr>
					<tr><td HEIGHT="1" bgcolor="#E8E8E8"></td></tr>


<? if($odrChk &&($_pdata->present_state == "Y" || $_pdata->pester_state == "Y")){ ?>
					<tr><td height="12"></td></tr>
					<tr>
						<td style="font-size:0px;">
							<?if($_pdata->pester_state == "Y"){?><a href="javascript:CheckForm('<?=(eregi("S",$_cdata->type))? "pester":""?>','<?=$opti?>')"><img src="<?=$Dir?>images/design/productdetail_pester.gif" border="0" /></a><?}?>
							<?if($_pdata->present_state == "Y"){?><a href="javascript:CheckForm('<?=(eregi("S",$_cdata->type))? "present":""?>','<?=$opti?>')"><img src="<?=$Dir?>images/design/productdetail_present.gif" hspace="4" border="0" /></a><?}?>
						</td>
					</tr>
<? } ?>

					<tr><td height="12"></td></tr>
					<tr>
						<td valign="top">
	<?
					if(substr($productcode,0,3)=='999') {
						if(strlen($_pdata->quantity)>0 && $_pdata->quantity<=0)
							echo "<FONT style=\"color:#F02800;\"><b>품 절</b></FONT>";
						else {
							echo "<a href=\"javascript:CheckForm('ordernow2','".$opti."')\" onMouseOver=\"window.status='선물하기';return true;\"><img src=\"../images/design/happycopon_btn02_large.gif\" alt=\"\" /></a>\n";
							echo "<a href=\"javascript:CheckForm('ordernow3','".$opti."')\" onMouseOver=\"window.status='본인구매';return true;\"><img src=\"../images/design/happycopon_btn01_large.gif\" alt=\"\" /></a>\n";
						}
					}
					else if(strlen($dicker)==0) {
						if(strlen($_pdata->quantity)>0 && $_pdata->quantity<=0)
							echo "<FONT style=\"color:#F02800;\"><b>품 절</b></FONT>";
						else {
							echo "<a href=\"javascript:CheckForm('ordernow','".$opti."')\" onMouseOver=\"window.status='바로구매';return true;\"><IMG SRC=\"".$Dir."images/common/product/".$_cdata->detail_type."/pdetail_skin_btn01.gif\" border=0 align=middle></a>\n";
							echo "<a href=\"javascript:CheckForm('','".$opti."')\" onMouseOver=\"window.status='장바구니담기';return true;\"><IMG SRC=\"".$Dir."images/common/product/".$_cdata->detail_type."/pdetail_skin_btn02.gif\" hspace=\"3\" border=\"0\" align=middle></a>\n";
						}
						if (strlen($_ShopInfo->getMemid())>0 && $_ShopInfo->getMemid()!="deleted") {
							echo "<a href=\"javascript:CheckForm('wishlist','".$opti."')\"><IMG SRC=\"".$Dir."images/common/product/".$_cdata->detail_type."/pdetail_skin_btn03.gif\" border=0 align=middle></a>\n";
						} else {
							echo "<a href=\"javascript:check_login();\"><IMG SRC=\"".$Dir."images/common/product/".$_cdata->detail_type."/pdetail_skin_btn03.gif\" border=0 align=absmiddle></a>\n";
						}
					}
	?>
	
						</td>
					</tr>
					<input type=hidden name=code value="<?=$code?>">
					<input type=hidden name=productcode value="<?=$productcode?>">
					<input type=hidden name=ordertype>
					<input type=hidden name=opts>
					<input type=hidden name=sell_memid value="<?=$sell_memid?>">
					<?=($brandcode>0?"<input type=hidden name=brandcode value=\"".$brandcode."\">\n":"")?>


					<!-- 상품상세 공통 이벤트 관리(상품 스펙 바로 아래) -->
					<? if($detailimg_eventloc=="1"){ ?>
					<!--
					<tr><td height="20"></td></tr>
					<tr>
						<td><?//=$detailimg_body?></td>
					</tr>
					-->
					<?}?>

					</table>
					</td>
				</tr>
				</table>
				</td>
			</tr>


			<!-- 입점사 정보 및 입점사 정보/상품 출력 j.bum -->
			<?if($_pdata->vender>0){?>
			<tr><td height="20"></td></tr>
			<tr>
				<td>
					<table border="0" cellpadding="0" cellspacing="1" width="100%" bgcolor="#e5e5e5">
						<tr>
							<td bgcolor="#f9f9f9" width="180" align="center" valign="top">
								<!-- 입점사 정보 START -->
								<? $v_info = mysql_fetch_assoc ( mysql_query( "SELECT * FROM `tblvenderinfo` WHERE `vender`=".$_pdata->vender." LIMIT 1;" ,get_db_conn()) ); ?>
								<div style="width:150px; margin:15 auto; text-align:left;">
									<ul style="list-style:none; margin:0px; padding:0px;">
										<li style="height:80px; text-align:center; overflow-y:hidden; border:1px solid #dddddd; font-size:0px;">
											<img src="/data/shopimages/vender/<?=$v_info[com_image]?>" onerror="this.src='/images/003/logo.gif';" width="150" border="0" alt="" />
										</li>
										<li style="padding:10px 0px 4px 0px;">
											<img src="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/icoStore.gif" border="0" align="absmiddle">
											<A HREF="javascript:GoMinishop('<?=$Dir.(MinishopType=="ON"?"minishop/":"minishop.php?storeid=").$_vdata->id?>')" style="text-decoration:none;"><span style="color:#666666; font-weight:bold;"><?=$_vdata->brand_name?></span> (<?=$v_info[com_owner]?>)</A>
										</li>
										<li style="padding-bottom:5px;"><span style="font-size:11px; letter-spacing:-1px;">등록상품수 : <B><?=$_vdata->prdt_cnt?></B>개</span></li>
										<li><a href="javascript:custRegistMinishop();"><img src="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/btsConnectshop.gif" border="0" alt="단골매장등록" /></a></li>
										<li><A HREF="javascript:GoMinishop('<?=$Dir.(MinishopType=="ON"?"minishop/":"minishop.php?storeid=").$_vdata->id?>')"><img src="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/bts_custsect_go.gif" border="0" alt="전체상품보기" /></a></li>
									</ul>
								</div>
								<!-- 입점사 정보 END -->
							</td>
							<td bgcolor="#ffffff" valign="top" style="padding:10px 0px;">
								<!-- 입점사 상품 출력 START -->
									<?=$venderproduct?>
								<!-- 입점사 상품 출력 END -->
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<?}?>


	<?
	//상품권 하단출력제외
	if(substr($productcode,0,3)!='999') {
		if($package_count>0) { //패키지 상품 출력
	?>
			<!-- 패키지 상품 출력 시작 //-->
			<tr>
				<td height="20"></td>
			</tr>
			<tr>
				<td>
				<table cellpadding="0" cellspacing="0" width="100%" height="100">
				<tr>
					<td><IMG SRC="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/skin_tag_t01.gif" border="0"></td>
					<td width="100%" background="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/skin_tag_t02.gif"></td>
					<td><IMG SRC="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/skin_tag_t03.gif" border="0"></td>
				</tr>
				<tr>
					<td height="100%" background="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/skin_tag_t08.gif"></td>
					<td width="100%" bgcolor="#F8F8F8" valign="top" style="padding:3px;">
					<table border="0" cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td bgcolor="#FFFFFF" style="border:1px #EDEDED solid;">
						<table border="0" cellpadding="0" cellspacing="0" width="100%">
						<col width="130"></col>
						<col width=""></col>
	<?
			$packagecoll=5;
			for($j=1; $j<count($arrpackage_title); $j++) {
				$arrpackage_list_exp = explode(",", $arrpackage_list[$j]);
	?>
						<tr>
							<td align="center" bgcolor="#F8F8F8" style="padding:5px;border-right:1px #EDEDED solid;border-bottom:1px #EDEDED solid;">
							<table border="0" cellpadding="0" cellspacing="0">
							<tr>
								<td align="center"><b><?=$arrpackage_title[$j]?></b></td>
							</tr>
							<tr>
								<td align="center" style="padding:3px;"><?=(strlen($dicker)>0?$dicker:"<img src=\"".$Dir."images/common/won_icon.gif\" border=\"0\" align=\"absmiddle\"><b><FONT color=\"#F02800\" id=\"idx_price".$j."\">".number_format($SellpriceValue+$arrpackage_pricevalue[$j])."원</font></b>")?></td>
							</tr>
							</table>
							</td>
							<td style="border-bottom:1px #EDEDED solid;">
							<table border="0" cellpadding="0" cellspacing="0" width=100%>
							<tr>
								<td width=100% style="padding:5">
								<table border="0" cellpadding="0" cellspacing="0" width="100%">
								<tr>
									<td width="<?=ceil(100/$packagecoll)?>%" valign="top" align="center" style="padding:5px;">
									<table border="0" cellpadding="0" cellspacing="0" width="90">
									<tr>
										<td align="center" valign=middle style="border:1px #EAEAEA solid;padding:10px;" bgcolor="#EDEDED">
	<?
						if (strlen($_pdata->tinyimage)>0 && file_exists($Dir.DataDir."shopimages/product/".$_pdata->tinyimage)==true) {
							echo "<img src=\"".$Dir.DataDir."shopimages/product/".urlencode($_pdata->tinyimage)."\" border=\"0\" ";
							$width = getimagesize($Dir.DataDir."shopimages/product/".$_pdata->tinyimage);
							if($width[0]>$width[1]) echo "width=\"70\"> ";
							else echo "height=\"70\">";
						} else {
							echo "<img src=\"".$Dir."images/no_img.gif\" width=\"70\" border=\"0\">";
						}
	?></td>
									</tr>
									<tr>
										<td height="3"></td>
									</tr>
									<tr>
										<td align="center" style="word-break:break-all;padding:10px;padding-top:0px;color:#BEBEBE;"><b>기본상품</b></td>
									</tr>
									</table>
									</td>
	<?
				for($ttt=1; $ttt<count($arrpackage_list_exp); $ttt++) {
					if(strlen($arrpackage_proinfo[productcode][$arrpackage_list_exp[$ttt]])>0) {
	?>
									<?=($ttt%$packagecoll==0?"</tr><tr>":"")?>
									<td width="<?=ceil(100/$packagecoll)?>%" valign="top" align="center" style="padding:5px;">
									<table border="0" cellpadding="0" cellspacing="0" width="90">
									<tr>
										<td valign="top">
										<table border="0" cellpadding="0" cellspacing="0" id="P<?=$arrpackage_proinfo[productcode][$arrpackage_list_exp[$ttt]]?>" onmouseover="quickfun_show(this,'P<?=$arrpackage_proinfo[productcode][$arrpackage_list_exp[$ttt]]?>','')" onmouseout="quickfun_show(this,'P<?=$arrpackage_proinfo[productcode][$arrpackage_list_exp[$ttt]]?>','none')">
										<tr>
											<td align="center" valign=middle style="border:1px #EAEAEA solid;padding:10px;" bgcolor="#EDEDED"><A HREF="<?=$Dir.FrontDir?>productdetail.php?productcode=<?=$arrpackage_proinfo[productcode][$arrpackage_list_exp[$ttt]]?>" onmouseover="window.status='상품상세조회';return true;" onmouseout="window.status='';return true;">

	<?
						if (strlen($arrpackage_proinfo[tinyimage][$arrpackage_list_exp[$ttt]])>0 && file_exists($Dir.DataDir."shopimages/product/".$arrpackage_proinfo[tinyimage][$arrpackage_list_exp[$ttt]])==true) {
							echo "<img src=\"".$Dir.DataDir."shopimages/product/".urlencode($arrpackage_proinfo[tinyimage][$arrpackage_list_exp[$ttt]])."\" border=\"0\" ";
							$width = getimagesize($Dir.DataDir."shopimages/product/".$arrpackage_proinfo[tinyimage][$arrpackage_list_exp[$ttt]]);
							if($width[0]>$width[1]) echo "width=\"70\"> ";
							else echo "height=\"70\">";
						} else {
							echo "<img src=\"".$Dir."images/no_img.gif\" width=\"70\" border=\"0\" align=\"center\">";
						}
	?></A></td>
										</tr>
										<tr>
											<td height="3" style="position:relative;"><?//=($_data->ETCTYPE["QUICKTOOLS"]!="Y"?"<script>quickfun_write('".$Dir."','P','".$arrpackage_proinfo[productcode][$arrpackage_list_exp[$ttt]]."','".($arrpackage_proinfo[quantity][$arrpackage_list_exp[$ttt]]=="0"?"":"1")."')</script>":"")?></td></tr>
										</tr>
										<tr>
											<td align="center" style="word-break:break-all;padding:10px;padding-top:0px;"><A HREF="<?=$Dir.FrontDir?>productdetail.php?productcode=<?=$arrpackage_proinfo[productcode][$arrpackage_list_exp[$ttt]]?>" onmouseover="window.status='상품상세조회';return true;" onmouseout="window.status='';return true;"><FONT class="prname"><?=viewproductname($arrpackage_proinfo[productname][$arrpackage_list_exp[$ttt]],$arrpackage_proinfo[etctype][$arrpackage_list_exp[$ttt]],"")?></FONT></A></td>
										</tr>
										</table>
										</td>
									</tr>
									</table>
									</td>
	<?
					}
				}

				if($ttt<$packagecoll) {
					$empty_count = $packagecoll-$ttt;
					for($ttt=0; $ttt<$empty_count; $ttt++) {
	?>
									<td width="<?=ceil(100/$packagecoll)?>%"></td>
	<?
					}
				}
	?>
								</tr>
								</table>
								</td>
							</tr>
							</table>
							</td>
						</tr>

	<?
			}
	?>
						</table>
						</td>
					</tr>
					</table>
					</td>
					<td background="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/skin_tag_t04.gif"></td>
				</tr>
				<tr>
					<td><IMG SRC="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/skin_tag_t07.gif" border="0"></td>
					<td width="100%" background="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/skin_tag_t06.gif"></td>
					<td><IMG SRC="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/skin_tag_t05.gif" border="0"></td>
				</tr>
				</table>
				</td>
			</tr>
			<!-- 패키지 상품 출력 끝 //-->
	<?
		} //패키지 상품 출력 끝
	?>
	<?
		if($_pdata->assembleuse=="Y" && count($_adata)>0) {
	?>
			<tr>
				<td height="20"></td>
			</tr>
			<tr>
				<td>
				<table cellpadding="0" cellspacing="0" width="100%" height="100">
				<tr>
					<td><IMG SRC="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/skin_tag_t01.gif" border="0"></td>
					<td width="100%" background="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/skin_tag_t02.gif"></td>
					<td><IMG SRC="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/skin_tag_t03.gif" border="0"></td>
				</tr>
				<tr>
					<td height="100%" background="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/skin_tag_t08.gif"></td>
					<td width="100%" bgcolor="#F8F8F8" valign="top">
					<table border="0" cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td>
	<?
			$assemble_type_exp = explode("",$_adata->assemble_type);
			$assemble_title_exp = explode("",$_adata->assemble_title);
			$assemble_pridx_exp = explode("",$_adata->assemble_pridx);
			$assemble_list_exp = explode("",$_adata->assemble_list);

			if(count($assemble_type_exp)>0) {
	?>
						<table border="0" cellpadding="0" cellspacing="0" width="100%">
						<input type=hidden name=assemble_type value="<?=implode("|",$assemble_type_exp)?>">
						<input type=hidden name=assemble_list value="">
						<input type=hidden name=assembleuse value="Y">
						<col width="60"></col>
						<col width=""></col>
	<?
				for($j=1; $j<count($assemble_type_exp); $j++) {
					$assemble_list_pexp = explode(",",$assemble_list_exp[$j]);

	?>
						<tr>
							<td valign="bottom" style="padding:5px;"><?
						if(strlen($assemble_pridx_exp[$j])>0 && (strlen($_acdata[$assemble_pridx_exp[$j]]->quantity)==0 || $_acdata[$assemble_pridx_exp[$j]]->quantity>=$miniq)) {
							if(strlen($_acdata[$assemble_pridx_exp[$j]]->tinyimage)>0 && file_exists($Dir.DataDir."shopimages/product/".$_acdata[$assemble_pridx_exp[$j]]->tinyimage)) {
								echo "<a href=\"javascript:assemble_proinfo('".$j."');\"><img src=\"".$Dir.DataDir."shopimages/product/".$_acdata[$assemble_pridx_exp[$j]]->tinyimage."\" border=\"0\" id=\"acimage".$j."\" width=\"50\" height=\"40\"></a>";
							} else {
								echo "<a href=\"javascript:assemble_proinfo('".$j."');\"><img src=\"".$Dir."images/acimage.gif\" border=\"0\" id=\"acimage".$j."\" width=\"50\" height=\"40\"></a>";
							}
							$assemble_state = "M";
						} else {
							echo "<a href=\"javascript:assemble_proinfo('".$j."');\"><img src=\"".$Dir."images/acimage.gif\" border=\"0\" id=\"acimage".$j."\" width=\"50\" height=\"40\"></a>";
							$assemble_state = "A";
						}
							?></td>
							<td valign="bottom" style="padding:5px;">
							<table border="0" cellpadding="0" cellspacing="0" width="100%">
							<tr>
								<td colspan="2"><span style="font-size:12px;"><b><?=$assemble_title_exp[$j]?></b></font></td>
							</tr>
							<tr>
								<td width="100%"><select name="acassembleselect[]" id="acassemble<?=$j?>" onchange="setAssenbleChange(this,'<?=$j?>');" onclick="setCurrentSelect(this.selectedIndex);" style="font-size:12px;letter-spacing:-0.5pt;width:100%;">
								<option value=""><?=($assemble_type_exp[$j]=="Y"?"&nbsp;&nbsp;&nbsp;━━━━━━━━━━━━━━━━&nbsp;[필수항목] 선택해 주세요&nbsp;━━━━━━━━━━━━━━━━━&nbsp;&nbsp;":"&nbsp;&nbsp;&nbsp;━━━━━━━━━━━━━━━━━━━&nbsp;선택해 주세요&nbsp;&nbsp;━━━━━━━━━━━━━━━━━━━ ")?></option>
	<?
						for($k=1; $k<count($assemble_list_pexp); $k++) {
							if(strlen($_acdata[$assemble_list_pexp[$k]]->pridx)>0 && (strlen($_acdata[$assemble_list_pexp[$k]]->quantity)==0 || $_acdata[$assemble_list_pexp[$k]]->quantity>0)) {
								if($_acdata[$assemble_list_pexp[$k]]->pridx==$_acdata[$assemble_pridx_exp[$j]]->pridx) {
									echo "<option value=\"".$_acdata[$assemble_list_pexp[$k]]->productcode."|".$_acdata[$assemble_list_pexp[$k]]->quantity."|".$_acdata[$assemble_list_pexp[$k]]->sellprice."|G|".htmlspecialchars($_acdata[$assemble_list_pexp[$k]]->tinyimage)."\" selected style=\"color:#FF00FF;\">".$_acdata[$assemble_list_pexp[$k]]->productname." / 기본선택</option>\n";
								} else {
									$minus_price = 0;
									$minus_price = $_acdata[$assemble_list_pexp[$k]]->sellprice - $_acdata[$assemble_pridx_exp[$j]]->sellprice;
									if($minus_price>0) {
										echo "<option value=\"".$_acdata[$assemble_list_pexp[$k]]->productcode."|".$_acdata[$assemble_list_pexp[$k]]->quantity."|".$_acdata[$assemble_list_pexp[$k]]->sellprice."|".$assemble_state."|".htmlspecialchars($_acdata[$assemble_list_pexp[$k]]->tinyimage)."\" style=\"color:#FF4C00;\">".$_acdata[$assemble_list_pexp[$k]]->productname.($minus_price>0?" / +".number_format($minus_price):" / ".number_format($minus_price))."</option>\n";
									} else if($minus_price>0) {
										echo "<option value=\"".$_acdata[$assemble_list_pexp[$k]]->productcode."|".$_acdata[$assemble_list_pexp[$k]]->quantity."|".$_acdata[$assemble_list_pexp[$k]]->sellprice."|".$assemble_state."|".htmlspecialchars($_acdata[$assemble_list_pexp[$k]]->tinyimage)."\" style=\"color:#FF00FF;\">".$_acdata[$assemble_list_pexp[$k]]->productname.($minus_price>0?" / +".number_format($minus_price):" / ".number_format($minus_price))."</option>\n";
									} else {
										echo "<option value=\"".$_acdata[$assemble_list_pexp[$k]]->productcode."|".$_acdata[$assemble_list_pexp[$k]]->quantity."|".$_acdata[$assemble_list_pexp[$k]]->sellprice."|".$assemble_state."|".htmlspecialchars($_acdata[$assemble_list_pexp[$k]]->tinyimage)."\" style=\"color:#003399;\">".$_acdata[$assemble_list_pexp[$k]]->productname.($minus_price>0?" / +".number_format($minus_price):" / ".number_format($minus_price))."</option>\n";
									}
								}
							}
						}
	?>
								</select></td>
							</tr>
							</table>
							</td>
						</tr>
	<?
				}
	?>
						</table>
						</td>
					</tr>
					<tr>
						<td style="padding-top:20px;padding-left:5px;padding-right:5px;padding-bottom:10px;"><TABLE cellSpacing=0 cellPadding=0 width="100%" border=0><tr><td height="1" bgcolor="#DADADA"></td></tr></table></td>
					</tr>
					<tr>
						<td style="padding:5px;">
						<table border="0" cellpadding="0" cellspacing="0" width="100%">
						<tr>
							<td align="center" bgcolor="#FFFFFF" style="padding:10px;border:1px #DADADA solid;">
							<table border="0" cellpadding="0" cellspacing="0">
							<tr>
								<td>
								<table border="0" cellpadding="0" cellspacing="0">
								<tr>
									<td><span style="font-size:16px;color:#000000;line-height:18px;"><b>구매수량&nbsp;:&nbsp;</b></span></td>
									<td>
									<table cellpadding="0" cellspacing="0">
									<tr>
										<td><input type=text name="assemblequantity" value="<?=($miniq>1?$miniq:"1")?>" size="4" class="input" style="height:24px;" readonly></td>
										<td style="padding-left:4px;padding-right:4px;">
										<table cellpadding="0" cellspacing="0">
										<tr>
											<td valign="top" style="padding-bottom:1px;"><a href="javascript:change_quantity('up')"><img src="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/pdetail_skin_neroup2.gif" border="0"></a></td>
										</tr>
										<tr>
											<td valign="bottom" style="padding-top:1px;"><a href="javascript:change_quantity('dn')"><img src="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/pdetail_skin_nerodown2.gif" border="0"></a></td>
										</tr>
										</table>
										</td>
									</tr>
									</table>
									</td>
								</tr>
								</table>
								</td>
								<?if(strlen($dicker)==0) { ?>
								<td style="padding-left:20px;">
								<table border="0" cellpadding="0" cellspacing="0">
								<tr>
									<td><span style="font-size:16px;color:#000000;line-height:18px;"><b>합계금액&nbsp;:&nbsp;</b></span></td>
									<td>
									<table cellpadding="0" cellspacing="0">
									<tr>
										<td><input type=text name="assembleprice" id="idx_assembleprice" value="<?=number_format($miniq>1?$miniq*$_pdata->sellprice:$_pdata->sellprice)?>" size="12" style="height:24px;text-align:right;font-weight:bold;font-size:14px;BORDER:#DFDFDF 1px solid;BACKGROUND-COLOR:#FFFFFF;padding-top:4pt;padding-bottom:1pt;padding-right:2pt;" readonly></td>
										<td style="padding-left:20px;"><a href="javascript:CheckForm('','')" onMouseOver="window.status='장바구니담기';return true;"><IMG SRC="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/pdetail_skin_btn02.gif" hspace="3" border="0" align=middle></a></td>
									</tr>
									</table>
									</td>
								</tr>
								</table>
								</td>
								<? } ?>
							</tr>
							</table>
							</td>
						</tr>
						</table>
						</td>
					</tr>
					</table>
	<?
			}
	?>
					</td>
					<td background="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/skin_tag_t04.gif"></td>
				</tr>
				<tr>
					<td><IMG SRC="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/skin_tag_t07.gif" border="0"></td>
					<td width="100%" background="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/skin_tag_t06.gif"></td>
					<td><IMG SRC="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/skin_tag_t05.gif" border="0"></td>
				</tr>
				</table>
				</td>
			</tr>
	<?
		}
	?>
			</form>

			<tr><td height="20"></td></tr>
			<tr>
				<td style="border:1px solid #dddddd; border-left:hidden; border-right:hidden; padding:15px; 0px;">
					<?=$coupon_body?>

				</td>
			</tr>

			<!-- 상품상세 공통 이벤트 관리(상품 상세정보 바로 위) -->
			<?if($detailimg_eventloc=="2"){?>
			<tr>
				<td height="20"></td>
			</tr>
			<tr>
				<td><?=$detailimg_body?></td>
			</tr>
			<?}?>

			<?if($_data->ETCTYPE["TAGTYPE"]!="N") {?>
			<tr><td height="20"></td></tr>
			<tr>
				<td>
				<table cellpadding="0" cellspacing="0" width="100%" height="100">
				<tr>
					<td><IMG SRC="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/skin_tag_t01.gif" border="0"></td>
					<td width="100%" background="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/skin_tag_t02.gif"></td>
					<td><IMG SRC="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/skin_tag_t03.gif" border="0"></td>
				</tr>
				<tr>
					<td height="100%" background="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/skin_tag_t08.gif"></td>
					<td width="100%" bgcolor="#F8F8F8" valign="top">
					<!-- 태그관련 -->

					<style type="text/css">
					<!--
					.tagtitle	{position:relative; width:100%; margin:2px 0 7px; height:15px;}
					.tagtitle li {padding:0px;}
					.taglist	{
						position:absolute; left:30px; width:90%; height:50px; overflow:hidden; line-height:20px; background:#ffffff;
						border:1px solid #E8E8E8;padding:0px 0px 0px 0px;
					}
					.taglist_on	{
						position:absolute; left:30px; width:90%; height:100px; overflow:auto; overflow-x:hidden; line-height:20px; background:#ffffff;
						border:1px solid #E8E8E8;padding:0px 0px 0px 0px;
					}
					.tag_more	{position:absolute; right:10px; top:0;}
					.tag_more	img	{margin:3px 0}
					.taginput	{background:#FAFAFA; padding:2px 0 2px 30px; }

					.prtaglistclass	{padding:5px 0px 0px 10px; }
					-->
					</style>

					<SCRIPT LANGUAGE="JavaScript">
					<!--
					function tagView()	{
						obj_T = document.getElementById('tag');
						obj_B = document.getElementById('tag_btn');

						if (obj_T.className == "taglist")	{
							obj_T.className = "taglist_on";
							obj_B.src="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/btn_tagmoreclose.gif";
							obj_B.alt="닫기";
						} else	{
							obj_T.className = "taglist";
							obj_B.src="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/btn_tagmore.gif";
							obj_B.alt="더보기";
						}
					}
					//-->
					</SCRIPT>
					<div class="taginput">
						<img src="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/shoppingtag_text.gif" alt="본상품의 태그를 넣어주세요" align=absmiddle>
						<input type="text" name="searchtagname" maxlength="50" style="background-color:white; border:#D5D5D5 1px solid; width:190px; height:18px;" autocomplete="off" onkeyup="check_tagvalidate(event, this);">
						<a href="javascript:void(0)" onclick="tagCheck('<?=$productcode?>')" onmouseover="window.status='태그달기';return true;" onmouseout="window.status='';return true;"><img src="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/btn_tagreg.gif" border=0 align=absmiddle alt="태그넣기"></a>
						<span style="font-size:8pt;">* 한번에 하나의 태그만 넣어주세요 </span>
					</div>
					<ul class="tagtitle">
						<li>
						<div class="taglist" id="tag">
							<div id="tagtitlediv">
							<img src="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/title_shoppingtag.gif" align=absmiddle alt="쇼핑태그">
							</div>
							<div id="prtaglist" class="prtaglistclass">
	<?
									$arrtaglist=explode(",",$_pdata->tag);
									$jj=0;
									for($i=0;$i<count($arrtaglist);$i++) {
										$arrtaglist[$i]=ereg_replace("(<|>)","",$arrtaglist[$i]);
										if(strlen($arrtaglist[$i])>0) {
											if($jj>0) echo ",&nbsp;&nbsp;";
											echo "<a href=\"".$Dir.FrontDir."tag.php?tagname=".urlencode($arrtaglist[$i])."\" onmouseover=\"window.status='".$arrtaglist[$i]."';return true;\" onmouseout=\"window.status='';return true;\">".$arrtaglist[$i]."</a>";
											$jj++;
										}
									}
	?>
							</div>
							<div class="tag_more"><a href="javascript:tagView()" onmouseover="window.status='태그더보기';return true;" onmouseout="window.status='';return true;"><img src="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/btn_tagmore.gif" border=0 alt="더보기" id="tag_btn"></a></div>
						</div>
						</li>
					</ul>
					</td>
					<!-- 태그관련 -->
					<td background="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/skin_tag_t04.gif"></td>
				</tr>
				<tr>
					<td><IMG SRC="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/skin_tag_t07.gif" border="0"></td>
					<td width="100%" background="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/skin_tag_t06.gif"></td>
					<td><IMG SRC="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/skin_tag_t05.gif" border="0"></td>
				</tr>
				</table>
				</td>
			</tr>
			<?
				}

				//관련상품 (상세정보 상단)
				if($_data->coll_loc=="1") {
					echo "<tr>\n";
					echo "	<td height=\"20\"></td>\n";
					echo "</tr>\n";
					echo "<tr>\n";
					echo "	<td><a name=\"2\"></a>\n";
					echo "	<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" class=\"prDetailTab\">\n";
					echo "	<tr>\n";
					echo "		<td><a href=\"#1\">상품상세정보</td>\n";
					echo "		<td><a href=\"#2\">관련상품</a></td>\n";
					echo "		<td><a href=\"#3\">배송/AS/환불안내</a></td>\n";
					echo "		<td><a href=\"#4\">사용후기</a></td>\n";
					echo "		<td><a href=\"#5\">상품Q&A</a></td>\n";
					if($_data->sns_ok == "Y" && $_pdata->sns_state == "Y"){
						echo "		<td><a href=\"#6\">SNS 소문내기</a></td>\n";
					}
					if($_data->sns_ok == "Y" && $_pdata->gonggu_product == "Y"){
						echo "		<td><a href=\"#7\">공동구매신청(".$product_Gonggu_Count.")</a></td>\n";
					}
					//echo "		<td width=\"100%\" background=\"".$Dir."images/common/product/".$_cdata->detail_type."/pdetail_skin_detailtitlebg.gif\"></td>\n";
					echo "	</tr>\n";
					echo "	</table>\n";
					echo "	</td>\n";
					echo "</tr>\n";
					echo "<tr>\n";
					echo "	<td>".$collection_body."</td>\n";
					echo "</tr>\n";
				}
			?>

			<tr><td height="20"></td></tr>
			<tr>
				<td>
					<a name="1"></a>
					<table cellpadding="0" cellspacing="0" width="100%" class="prDetailTab">
						<tr>
							<td class="prDetailTabOn"><a href="#1">상품상세정보<!--<img src="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/pdetail_skin_detailtitle1.gif" border="0">--></a></td>
							<? if($_data->coll_loc != '0' && strlen($collection_list) > 0) { ?><td class="prDetailTabOff"><a href="#2">관련상품<!--<img src="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/pdetail_skin_detailtitle2r.gif" border="0">--></a></td><? }?>
							<td class="prDetailTabOff"><a href="#3">배송/AS/환불안내<!--<img src="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/pdetail_skin_detailtitle3r.gif" border="0">--></a></td>
							<td class="prDetailTabOff"><a href="#4">사용후기 (<?=$counttotal?>)<!--<img src="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/pdetail_skin_detailtitle4r.gif" border="0">--></a></td>
							<td class="prDetailTabOff"><a href="#5">상품Q&A (<?=$qnacount?>)<!--<img src="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/pdetail_skin_detailtitle5r.gif" border="0">--></a></td>
							<? if($_data->sns_ok == "Y" && $_pdata->sns_state == "Y"){?><td class="prDetailTabOff"><a href="#6">SNS 소문내기 (<?=$product_SNS_Count?>)<!--<img src="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/pdetail_skin_detailtitle6r.gif" border="0">--></a></td><?}?>
							<? if($_data->sns_ok == "Y" && $_pdata->gonggu_product == "Y"){?><td class="prDetailTabOff"><a href="#7">공동구매신청(<?=$product_Gonggu_Count?>)<!--<img src="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/pdetail_skin_detailtitle7r.gif" border="0">--></a></td><?}?>
							<!--<td width="20%" background="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/pdetail_skin_detailtitlebg.gif"></td>-->
							<!--<td class="prDetailTabNull">&nbsp;</td>-->
						</tr>
					</table>
					<table cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed;">
						<tr>
							<td valign="top">
								<table cellpadding="0" cellspacing="0" width="100%">
									<tr>
										<td style="padding:5px;">
										<?
											if(strlen($detail_filter)>0) {
												$_pdata->content = preg_replace($filterpattern,$filterreplace,$_pdata->content);
											}

											if (strpos($_pdata->content,"table>")!=false || strpos($_pdata->content,"TABLE>")!=false)
												echo "<pre>".$_pdata->content."</pre>";
											else if(strpos($_pdata->content,"</")!=false)
												echo ereg_replace("\n","<br>",$_pdata->content);
											else if(strpos($_pdata->content,"img")!=false || strpos($_pdata->content,"IMG")!=false)
												echo ereg_replace("\n","<br>",$_pdata->content);
											else
												echo ereg_replace(" ","&nbsp;",ereg_replace("\n","<br>",$_pdata->content));
										?>
										</td>
									</tr>
									<tr>
										<td>
											<?
												// 상품정보고시
												$ditems = _getProductDetails($_pdata->pridx);
												if(_array($ditems) && count($ditems) > 0){
											?>
											<table border="0" cellpadding="0" cellspacing="0" class="productInfoGosi">
												<caption>전자상거래소비자보호법 시행규칙에 따른 상품정보제공 고시</caption>
												<?
													foreach($ditems as $ditem){
												?>
												<tr>
													<th><?=$ditem['dtitle']?></th>
													<td><?=nl2br($ditem['dcontent'])?></td>
												</tr>
												<?
													}// end foreach
												?>
											</table>
											<?
												} // end if
											?>
										</td>
									</tr>
								</table>
							</td>
							<?
								//관련상품 (상세정보 우측)
								if($_data->coll_loc=="3") {
									echo "	<td width=\"3\" nowrap></td>\n";
									echo "	<td width=\"165\" valign=\"top\">\n";
									echo "	<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\n";
									echo "	<tr>\n";
									echo "		<td height=\"5\"></td>\n";
									echo "	</tr>\n";
									echo "	<tr>\n";
									echo "		<td>".$collection_body."</td>\n";
									echo "	</tr>\n";
									echo "	</table>\n";
									echo "	</td>\n";
								}
							?>
						</tr>
					</table>
				</td>
			</tr>




			<!-- 입점사 네임텍 출력 -->
			<?
				if( nameTechUse($_pdata->vender) ) {
			?>
			<tr>
				<td>
					<table border="0" cellpadding="0" cellspacing="0" width="100%" style="border:1px solid #dddddd; margin-top:20px;">
						<tr>
							<td style="width:130px; text-align:center; background:#f9f9f9;"><img src="<?=$com_image_url.$v_info['com_image']?>" width="100" /></td>
							<td style="padding:10px 15px;">
								<div style="height:30px; border-bottom:1px solid #dddddd;">
									<div style="float:left; height:27px; line-height:27px;">
										<?=$v_info['com_name']?>&nbsp;&nbsp;<span style="color:#dddddd;">|</span>&nbsp;&nbsp;대표 : <?=$v_info['com_owner']?>
										<? if( $v_info['class'] > 0 ){ ?>
										&nbsp;&nbsp;<span style="color:#dddddd;">|</span>&nbsp;&nbsp;등급 : <?=$classList[$v_info['class']]?>
										<? } ?>
									</div>
									<div style="float:right; margin:0px; padding:0px;">
										<a href="javascript:GoMinishop('../minishop.php?storeid=<?=$v_info['id']?>')"><img src="/images/common/btn_vender_allpr.gif" border="0" alt="전체상품보기" /></a>
										<a href="javascript:custRegistMinishop();"><img src="/images/common/btn_vender_addstor.gif" border="0" alt="단골매장등록" /></a>
									</div>
								</div>

								<div>
									<table border="0" cellpadding="0" cellspacing="0" width="100%" class="venderInfoTbl">
										<caption>판매자 정보</caption>
										<tr>
											<th>사업자번호</th>
											<td>: <?=$v_info['com_num']?></td>
											<th>통신판매업신고번호</th>
											<td>: <?=$v_info['ec_num']?></td>
										</tr>
										<tr>
											<th>연락처</th>
											<td>: <?=$v_info['com_tel']?></td>
											<th>E-mail</th>
											<td>: <?=$v_info['p_email']?></td>
										</tr>
										<tr>
											<th>사업장소재지</th>
											<td>: <?=$v_info['com_addr']?></td>
											<th>사업자구분</th>
											<td>: <?=$v_info['com_type']?></td>
										</tr>
									</table>
								</div>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<?
				}
			?>

			<!-- 상품상세 공통 이벤트 관리(상품 상세정보 바로 아래) -->
			<? if($detailimg_eventloc=="3"){ ?>
			<tr><td height="20"></td></tr>
			<tr>
				<td><?=$detailimg_body?></td>
			</tr>
			<? } ?>

			<?
				//관련상품 (상세정보 하단)
				if($_data->coll_loc=="2") {
					echo "<tr><td height=\"20\"></td></tr>\n";
					echo "<tr>\n";
					echo "	<td><a name=\"2\"></a>\n";
					echo "	<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" class=\"prDetailTab\">\n";
					echo "	<tr>\n";
					echo "		<td class=\"prDetailTabOff2\"><a href=\"#1\">상품상세정보<!--<img src=\"".$Dir."images/common/product/".$_cdata->detail_type."/pdetail_skin_detailtitle1r.gif\" border=\"0\">--></td>\n";

					if($_data->coll_loc != '0' && strlen($collection_list) > 0) {
						echo "	<td class=\"prDetailTabOn\"><a href=\"#2\">관련상품<!--<img src=\"".$Dir."images/common/product/".$_cdata->detail_type."/pdetail_skin_detailtitle2.gif\" border=\"0\">--></a></td>\n";
					}

					echo "		<td class=\"prDetailTabOff\"><a href=\"#3\">배송/AS/환불안내<!--<img src=\"".$Dir."images/common/product/".$_cdata->detail_type."/pdetail_skin_detailtitle3r.gif\" border=\"0\">--></a></td>\n";
					echo "		<td class=\"prDetailTabOff\"><a href=\"#4\">사용후기 (".$counttotal.")<!--<img src=\"".$Dir."images/common/product/".$_cdata->detail_type."/pdetail_skin_detailtitle4r.gif\" border=\"0\">--></a></td>\n";
					echo "		<td class=\"prDetailTabOff\"><a href=\"#5\">상품Q&A (".$qnacount.")<!--<img src=\"".$Dir."images/common/product/".$_cdata->detail_type."/pdetail_skin_detailtitle5r.gif\" border=\"0\">--></a></td>\n";
					if($_data->sns_ok == "Y" && $_pdata->sns_state == "Y"){
						echo "		<td class=\"prDetailTabOff\"><a href=\"#6\">SNS 소문내기 (".$product_SNS_Count.")<!--<img src=\"".$Dir."images/common/product/".$_cdata->detail_type."/pdetail_skin_detailtitle6r.gif\" border=\"0\">--></a></td>\n";
					}
					if($_data->sns_ok == "Y" && $_pdata->gonggu_product == "Y"){
						echo "		<td class=\"prDetailTabOff\"><a href=\"#7\">공동구매신청(".$product_Gonggu_Count.")<!--<img src=\"".$Dir."images/common/product/".$_cdata->detail_type."/pdetail_skin_detailtitle7r.gif\" border=\"0\">--></a></td>\n";
					}
					//echo "		<td width=\"100%\" background=\"".$Dir."images/common/product/".$_cdata->detail_type."/pdetail_skin_detailtitlebg.gif\"></td>\n";
					//echo "		<td class=\"prDetailTabNull\">&nbsp;</td>\n";
					echo "	</tr>\n";
					echo "	</table>\n";
					echo "	</td>\n";
					echo "</tr>\n";
					echo "<tr><td height=\"10\"></td></tr>\n";
					echo "<tr>\n";
					echo "	<td>".$collection_body."</td>\n";
					echo "</tr>\n";
				}

				//배송/교환/환불정보
				if(strlen($deli_info)>0) {
					echo "	<tr>\n";
					echo "		<td height=\"40\"></td>\n";
					echo "	</tr>\n";
					echo "<tr>\n";
					echo "	<td valign=\"top\"><a name=\"3\"></a>\n";
					echo "	<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"table-layout:fixed\">\n";
					echo "	<tr>\n";
					echo "		<td width=\"100%\">\n";
					echo "		<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" class=\"prDetailTab\">\n";
					echo "		<tr>\n";
					echo "			<td class=\"prDetailTabOff2\"><a href=\"#1\">상품상세정보<!--<img src=\"".$Dir."images/common/product/".$_cdata->detail_type."/pdetail_skin_detailtitle1ra.gif\" border=\"0\">--></td>\n";

					if($_data->coll_loc != '0' && strlen($collection_list) > 0) {
						echo "			<td class=\"prDetailTabOff2\"><a href=\"#2\">관련상품<!--<img src=\"".$Dir."images/common/product/".$_cdata->detail_type."/pdetail_skin_detailtitle2ra.gif\" border=\"0\">--></a></td>\n";
					}

					echo "			<td class=\"prDetailTabOn\"><a href=\"#3\">배송/AS/환불안내<!--<img src=\"".$Dir."images/common/product/".$_cdata->detail_type."/pdetail_skin_detailtitle3.gif\" border=\"0\">--></a></td>\n";
					echo "			<td class=\"prDetailTabOff\"><a href=\"#4\">사용후기 (".$counttotal.")<!--<img src=\"".$Dir."images/common/product/".$_cdata->detail_type."/pdetail_skin_detailtitle4r.gif\" border=\"0\">--></a></td>\n";
					echo "			<td class=\"prDetailTabOff\"><a href=\"#5\">상품Q&A (".$qnacount.")<!--<img src=\"".$Dir."images/common/product/".$_cdata->detail_type."/pdetail_skin_detailtitle5r.gif\" border=\"0\">--></a></td>\n";
					if($_data->sns_ok == "Y" && $_pdata->sns_state == "Y"){
						echo "		<td class=\"prDetailTabOff\"><a href=\"#6\">SNS 소문내기 (".$product_SNS_Count.")<!--<img src=\"".$Dir."images/common/product/".$_cdata->detail_type."/pdetail_skin_detailtitle6r.gif\" border=\"0\">--></a></td>\n";
					}
					if($_data->sns_ok == "Y" && $_pdata->gonggu_product == "Y"){
						echo "		<td class=\"prDetailTabOff\"><a href=\"#7\">공동구매신청(".$product_Gonggu_Count.")<!--<img src=\"".$Dir."images/common/product/".$_cdata->detail_type."/pdetail_skin_detailtitle7r.gif\" border=\"0\">--></a></td>\n";
					}
					//echo "			<td width=\"100%\" background=\"".$Dir."images/common/product/".$_cdata->detail_type."/pdetail_skin_detailtitlebg.gif\"></td>\n";
					//echo "		<td class=\"prDetailTabNull\">&nbsp;</td>\n";
					echo "		</tr>\n";
					echo "		</table>\n";
					echo "		</td>\n";
					echo "	</tr>\n";
					echo "	<tr>\n";
					echo "		<td height=\"15\"></td>\n";
					echo "	</tr>\n";
					echo "	<tr>\n";
					echo "		<td>".$deli_info."</td>\n";
					echo "	</tr>\n";
					echo "	</table>\n";
					echo "	</td>\n";
					echo "</tr>\n";
				}
			?>

			<!-- 구매후기 -->
			<?if($_data->review_type!="N") {?>
			<tr><td height="40"></td></tr>
			<tr>
				<td valign="top">
					<a name="review"></a>
					<table border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed;">
						<tr>
							<td width="100%"><a name="4"></a>
								<table cellpadding="0" cellspacing="0" width="100%" class="prDetailTab">
									<tr>
										<td class="prDetailTabOff2"><a href="#1">상품상세정보<!--<img src="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/pdetail_skin_detailtitle1ra.gif" border="0">--></a></td>
										<? if($_data->coll_loc != '0' && strlen($collection_list) > 0) { ?><td class="prDetailTabOff2"><a href="#2">관련상품<!--<img src="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/pdetail_skin_detailtitle2r.gif" border="0">--></a></td><? } ?>
										<td class="prDetailTabOff2"><a href="#3">배송/AS/환불안내<!--<img src="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/pdetail_skin_detailtitle3ra.gif" border="0">--></a></td>
										<td class="prDetailTabOn"><a href="#4">사용후기 (<?=$counttotal?>)<!--<img src="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/pdetail_skin_detailtitle4.gif" border="0">--></a></td>
										<td class="prDetailTabOff"><a href="#5">상품Q&A (<?=$qnacount?>)<!--<img src="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/pdetail_skin_detailtitle5r.gif" border="0">--></a></td>
										<? if($_data->sns_ok == "Y" && $_pdata->sns_state == "Y"){?><td class="prDetailTabOff"><a href="#6">SNS 소문내기 (<?=$product_SNS_Count?>)<!--<img src="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/pdetail_skin_detailtitle6r.gif" border="0">--></a></td><?}?>
										<? if($_data->sns_ok == "Y" && $_pdata->gonggu_product == "Y"){?><td class="prDetailTabOff"><a href="#7">공동구매신청(<?=$product_Gonggu_Count?>)<!--<img src="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/pdetail_skin_detailtitle7r.gif" border="0">--></a></td><?}?>
										<!--<td width="100%" background="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/pdetail_skin_detailtitlebg.gif"></td>-->
										<!--<td class="prDetailTabNull">&nbsp;</td>-->
									</tr>
								</table>
							</td>
						</tr>
						<!-- 상품 평점 -->
						<tr>
							<td style="padding:30px 0px 10px 0px;;">
								<style>
									.reviewPoint {width:100%; border-top:1px solid #eeeeee; border-bottom:1px solid #eeeeee;}
									.reviewPoint th {background:#f9f9f9; padding-left:25px;}
									.reviewPoint th strong {float:left; color:#ff4400; font-size:27px; font-weight:bold; font-family:arial; line-height:30px;}
									.reviewPoint th h4 {font-size:10px; font-weight:bold; font-family:arial;}

									.reviewPoint td {width:18%; padding:10px 0px; border-left:1px solid #eeeeee; text-align:center;}
									.reviewPoint td h4 {font-size:12px;}
									.reviewPoint td span {color:#222222; font-size:17px; font-family:arial; line-height:24px;}
								</style>

								<table border="0" cellpadding="0" cellspacing="0" width="100%" class="reviewPoint">
									<tr>
										<th>
											<strong><?=$avertotalscore?></strong>
											<div style="float:left; margin-left:10px; text-align:left;">
												<h4>AVERAGE POINT</h4>
												<div><?=$reviewstarcount?></div>
											</div>
										</th>
										<td>
											<h4>품질</h4>
											<span><?=$averquality?></span>
											<div><?=$qualitystarcount?></div>
										</td>
										<td>
											<h4>가격</h4>
											<span><?=$averprice?></span>
											<div><?=$pricestarcount?></div>
										</td>
										<td>
											<h4>배송</h4>
											<span><?=$averdelitime?></span>
											<div><?=$delitimestarcount?></div>
										</td>
										<td>
											<h4>추천</h4>
											<span><?=$averrecommend?></span>
											<div><?=$recommendstarcount?></div>
										</td>
									</tr>
								</table>
							</td>
						</tr>
						<!-- 상품 평점 -->
						<tr>
							<td style="padding:10px;"><? INCLUDE ($Dir.FrontDir."prreview.php"); ?></td>
						</tr>
					</table>
				</td>
			</tr>
			<?}?>

			<!-- 상품Q/A -->
			<?if(strlen($qnasetup->board)>0){?>
			<tr><td height="40"></td></tr>
			<tr>
				<td valign="top"><a name="5"></a>
					<table border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed">
						<tr>
							<td>
								<table cellpadding="0" cellspacing="0" width="100%" class="prDetailTab">
									<tr>
										<td class="prDetailTabOff2"><a href="#1">상품상세정보<!--<img src="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/pdetail_skin_detailtitle1ra.gif" border="0">--></a></td>
										<? if($_data->coll_loc != '0' && strlen($collection_list) > 0) { ?><td class="prDetailTabOff2"><a href="#2">관련상품<!--<img src="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/pdetail_skin_detailtitle2r.gif" border="0">--></a></td><? } ?>
										<td class="prDetailTabOff2"><a href="#3">배송/AS/환불안내<!--<img src="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/pdetail_skin_detailtitle3r.gif" border="0">--></a></td>
										<td class="prDetailTabOff2"><a href="#4">사용후기 (<?=$counttotal?>)<!--<img src="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/pdetail_skin_detailtitle4ra.gif" border="0">--></a></td>
										<td class="prDetailTabOn"><a href="#5">상품Q&A (<?=$qnacount?>)<!--<img src="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/pdetail_skin_detailtitle5.gif" border="0">--></a></td>
										<? if($_data->sns_ok == "Y" && $_pdata->sns_state == "Y"){?><td class="prDetailTabOff"><a href="#6">SNS 소문내기 (<?=$product_SNS_Count?>)<!--<img src="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/pdetail_skin_detailtitle6r.gif" border="0">--></a></td><?}?>
										<? if($_data->sns_ok == "Y" && $_pdata->gonggu_product == "Y"){?><td class="prDetailTabOff"><a href="#7">공동구매신청(<?=$product_Gonggu_Count?>)<!--<img src="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/pdetail_skin_detailtitle7r.gif" border="0">--></a></td><?}?>
										<!--<td width="100%" background="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/pdetail_skin_detailtitlebg.gif"></td>-->
										<!--<td class="prDetailTabNull">&nbsp;</td>-->
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td style="padding:15px 5px 0px 5px;"><? INCLUDE ($Dir.FrontDir."prqna.php"); ?></td>
						</tr>
					</table>
				</td>
			</tr>
			<?}?>

			<!-- SNS 소문내기 -->
			<? if($_data->sns_ok == "Y" && $_pdata->sns_state == "Y"){?>
			<tr><td height="40"></td></tr>
			<tr>
				<td valign="top"><a name="6"></a>
					<table border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed">
						<tr>
							<td>
								<table cellpadding="0" cellspacing="0" width="100%" class="prDetailTab">
									<tr>
										<td class="prDetailTabOff2"><a href="#1">상품상세정보<!--<img src="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/pdetail_skin_detailtitle1ra.gif" border="0">--></a></td>
										<? if($_data->coll_loc != '0' && strlen($collection_list) > 0) { ?><td class="prDetailTabOff2"><a href="#2">관련상품<!--<img src="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/pdetail_skin_detailtitle2r.gif" border="0">--></a></td><? } ?>
										<td class="prDetailTabOff2"><a href="#3">배송/AS/환불안내<!--<img src="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/pdetail_skin_detailtitle3r.gif" border="0">--></a></td>
										<td class="prDetailTabOff2"><a href="#4">사용후기 (<?=$counttotal?>)<!--<img src="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/pdetail_skin_detailtitle4r.gif" border="0">--></a></td>
										<td class="prDetailTabOff2"><a href="#5">상품Q&A (<?=$qnacount?>)<!--<img src="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/pdetail_skin_detailtitle5r.gif" border="0">--></a></td>
										<td class="prDetailTabOn"><a href="#6">SNS 소문내기 (<?=$product_SNS_Count?>)<!--<img src="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/pdetail_skin_detailtitle6.gif" border="0">--></a></td>
										<? if($_data->sns_ok == "Y" && $_pdata->gonggu_product == "Y"){?><td class="prDetailTabOff"><a href="#7">공동구매신청(<?=$product_Gonggu_Count?>)<!--<img src="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/pdetail_skin_detailtitle7r.gif" border="0">--></a></td><?}?>
										<!--<td width="100%" background="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/pdetail_skin_detailtitlebg.gif"></td>-->
										<!--<td class="prDetailTabNull">&nbsp;</td>-->
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td style="padding:5,5,0,5">
							<?INCLUDE ($Dir.TempletDir."product/sns_product_cmt.php"); echo $sProductCmt;?>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<?}?>

			<!-- 공동구매 -->
			<? if($_data->sns_ok == "Y" && $_pdata->gonggu_product == "Y"){ ?>
			<tr><td height="40"></td></tr>
			<tr>
				<td valign="top"><a name="7"></a>
					<table border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed">
						<tr>
							<td>
								<table cellpadding="0" cellspacing="0" width="100%" class="prDetailTab">
									<tr>
										<td class="prDetailTabOff2"><a href="#1">상품상세정보<!--<img src="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/pdetail_skin_detailtitle1ra.gif" border="0">--></a></td>
										<? if($_data->coll_loc != '0' && strlen($collection_list) > 0) { ?><td class="prDetailTabOff2"><a href="#2">관련상품<!--<a href="#2"><img src="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/pdetail_skin_detailtitle2r.gif" border="0">--></a></td><? } ?>
										<td class="prDetailTabOff2"><a href="#3">배송/AS/환불안내<!--<img src="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/pdetail_skin_detailtitle3r.gif" border="0">--></a></td>
										<td class="prDetailTabOff2"><a href="#4">사용후기 (<?=$counttotal?>)<!--<img src="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/pdetail_skin_detailtitle4r.gif" border="0">--></a></td>
										<td class="prDetailTabOff2"><a href="#5">상품Q&A (<?=$qnacount?>)<!--<img src="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/pdetail_skin_detailtitle5r.gif" border="0">--></a></td>
										<? if($_data->sns_ok == "Y" && $_pdata->sns_state == "Y"){?><td class="prDetailTabOff2"><a href="#6">SNS 소문내기 (<?=$product_SNS_Count?>)<!--<img src="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/pdetail_skin_detailtitle6r.gif" border="0">--></a></td><?}?>
										<td class="prDetailTabOn"><a href="#7">공동구매신청(<?=$product_Gonggu_Count?>)<!--<img src="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/pdetail_skin_detailtitle7.gif" border="0">--></a></td>
										<!--<td width="100%" background="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/pdetail_skin_detailtitlebg.gif"></td>-->
										<!--<td class="prDetailTabNull">&nbsp;</td>-->
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td style="padding:5,5,0,5">
							<?INCLUDE ($Dir.TempletDir."product/sns_gonggu_cmt.php"); echo $sGongguCmt;?>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<?
				}
			}
			?>
			<tr><td height="20"></td></tr>
			</table>
			</td>
		</tr>
		</table>
		</td>
	</tr>
</table>