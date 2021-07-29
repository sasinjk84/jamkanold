<?
if(substr(getenv("SCRIPT_NAME"),-21)=="/productlist_text.php"){
	header("HTTP/1.0 404 Not Found");
	exit;
}

$codeevent="";
if(strpos(" ".$body,"[CODEEVENT]")) {
	if($_cdata->title_type=="image") {
		if(file_exists($Dir.DataDir."shopimages/etc/CODE".$code.".gif")) {
			$codeevent="<img src=\"".$Dir.DataDir."shopimages/etc/CODE".$code.".gif\" border=0 align=absmiddle>";
		}
	} else if($_cdata->title_type=="html") {
		if(strlen($_cdata->title_body)>0) {
			if (strpos(strtolower($_cdata->title_body),"<table")!==false)
				$codeevent=$_cdata->title_body;
			else
				$codeevent=ereg_replace("\n","<br>",$_cdata->title_body);
		}
	}
}


$codegroup="";
//_pr($_data);

if($_data->ETCTYPE["CODEYES"]=="Y") {
	/*
	if($num=strpos($body,"[CODEGROUP]")) {
		$iscode=false;
		$category_listitem = array();
		$citems = getCategoryItems($code,true);
		if(is_array($citems['items'])){
			if($citems['depth'] > 0 && count($citems['items']) <1){
				$citems = getCategoryItems(substr($code,0,($citems['depth']-1)*3),true);
			}else if($citems['pcode'] == $code){
				$citems = getCategoryItems(substr($code,0,$citems['depth']*3),true);
			}
		}
		//_pr($citems);
		if(is_array($citems['items']) && count($citems['items']) > 0 ){
			foreach($citems['items'] as $citem){
				if( $citems['depth'] > 0 AND $citem['type'] != "LX" ) {
					if($codeA == $citem['codeA'] && $codeB == $citem['codeB'] && $codeC == $citem['codeC'] && $codeD == $citem['codeD']){
						array_push($category_listitem,"<a href=\"".$Dir.FrontDir."productlist.php?code=".$citem['codeA'].$citem['codeB'].$citem['codeC'].$citem['codeD']."\"><FONT class=subcodename style=\"font-weight:bold\">".$citem['code_name']."</FONT></a>");
					}else{
						array_push($category_listitem,"<a href=\"".$Dir.FrontDir."productlist.php?code=".$citem['codeA'].$citem['codeB'].$citem['codeC'].$citem['codeD']."\"><FONT class=subcodename>".$citem['code_name']."</FONT></a>");
					}
				}
			}

			$iscode = true;

			$codegroup ="<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n";
			$codegroup .="<tr><td style=\"padding:10px;\" class=subcodename>";
			$codegroup .= implode('&nbsp;|&nbsp;',$category_listitem);
			$codegroup .='</td></tr>';
			$codegroup .='</table>';
		}
	}*/
	if($num=strpos($body,"[CODEGROUP]")) {
		$iscode=false;
		$citemsOnParent = false;
		$category_listitem = array();

		$citems = getCategoryItems($code,true);


		if(is_array($citems['items'])){
			if($citems['depth'] > 0 && count($citems['items']) <1){
				$chkLinkCode = $citems['pcode'];
				$citems = getCategoryItems(substr($code,0,($citems['depth']-1)*3),true);
				$citemsOnParent = true;
			}else if($citems['pcode'] == $code){
				$citems = getCategoryItems(substr($code,0,$citems['depth']*3),true);
			}
		}

		if(is_array($citems['items']) && count($citems['items']) > 0 ){
			$tcnt = 0;
			$codeitems = array(array('text'=>'전체','link'=>'/front/productlist.php?code='.$citems['pcode'],'cnt'=>0,'issel'=>true));

			foreach($citems['items'] as $cidx=>$citem){
				$sql = "select count(*) from tblcategorycode c left join tblproduct p on c.productcode=p.productcode where c.categorycode like '".$citem['linkcode']."%' and p.display='Y'";
				if(false !== $cntres = mysql_query($sql,get_db_conn())){
					$citemspcnt = mysql_result($cntres,0,0);
					$codeitems[0]['cnt']+=$citemspcnt;
					if(true === $issel = ($citemsOnParent && $chkLinkCode == $citem['linkcode'])){
						$codeitems[0]['issel'] = false;
					}
					array_push($codeitems,array('text'=>$citem['code_name'],'link'=>'/front/productlist.php?code='.$citem['linkcode'],'cnt'=>$citemspcnt,'issel'=>$issel));
				}
			}
			$citemloop = ceil((count($citems['items'])+1) / 5);

			$codegroup = '<table border="0" cellpadding="0" cellspacing="0" class="subCategoryBox"><caption>카테고리 그룹</caption>';

			for($ci=0;$ci<$citemloop;$ci++){
				$codegroup .= '<tr>';
				for($j=0;$j<6;$j++){
					if(_array($codeitems[$ci*6+$j])){
						$citemobj = $codeitems[$ci*6+$j];
						$codegroup .= '<td class="'.($citemobj['issel']?'selCategory':'').'">';
						if(!_empty($citemobj['link'])){
							$codegroup .= '<a href="'.$citemobj['link'].'">'.$citemobj['text'].' <span class="citemproductcnt">'.number_format($citemobj['cnt']).'</span></a></td>';
						}else{
							$codegroup .= $citemobj['text'].' <span class="citemproductcnt">'.number_format($citemobj['cnt']).'</span></td>';
						}
					}else{
						$codegroup .= '<td>&nbsp;</td>';
					}
				}
				$codegroup .= '</tr>';
			}

			$codegroup .= '</table>';

		}
	}
}

################ 신규상품 ###############
$newitem1=""; $newitem2=""; $newitem3="";
if(preg_match("/^(1|2|3)$/",$newitem_type)) {
	/*
	$sql = "SELECT special_list FROM tblspecialcode WHERE code='".$code."' AND special='1' ";
	$result=mysql_query($sql,get_db_conn());
	$sp_prcode="";
	if($row=mysql_fetch_object($result)) {
		$sp_prcode=ereg_replace(',','\',\'',$row->special_list);
	}
	mysql_free_result($result);
*/
	$sql = "SELECT a.* FROM tblproduct AS a ";
	$sql.= "LEFT OUTER JOIN tblproductgroupcode b ON a.productcode=b.productcode ";
	$sql.= "LEFT OUTER JOIN rent_product_option opt ON a.pridx=opt.pridx ";
	$sql.= "WHERE a.productcode like '".$likecode."%' AND a.display='Y' ";
	$sql.= "AND (a.group_check='N' OR b.group_code='".$_ShopInfo->getMemgroup()."') ";
	if(strlen($not_qry)>0) $sql.= $not_qry." ";
//	$sql.= $search_sql." "; //search
	$sql.= "ORDER BY date desc ";
	$sql.= "LIMIT ".$newitem_product_num;
	$result=mysql_query($sql,get_db_conn());

	//echo $likecode;

	//if(strlen($sp_prcode)>0) {
	if($result && mysql_num_rows($result)) {

		//카테고리 신규상품 탭출력
		$newitem_tab="<li><a href='#'><img src='/data/design/img/sub/tab_prsection1.gif' asrc='/data/design/img/sub/tab_prsection1_on.gif' border='0' alt='' /></a></li>";

		/*
		$sql = "SELECT a.* FROM tblproduct AS a ";
		$sql.= "LEFT OUTER JOIN tblproductgroupcode b ON a.productcode=b.productcode ";
		$sql.= "WHERE a.productcode IN ('".$sp_prcode."') AND a.display='Y' ";
		$sql.= "AND (a.group_check='N' OR b.group_code='".$_ShopInfo->getMemgroup()."') ";
		if(strlen($not_qry)>0) {
			$sql.= $not_qry." ";
		}
		$sql.= "ORDER BY FIELD(a.productcode,'".$sp_prcode."') ";
		$sql.= "LIMIT ".$newitem_product_num;
		$result=mysql_query($sql,get_db_conn());*/
		$i=0;

		if($newitem_type=="1") {	####################################### 이미지A형 ##########################
			$innerpub = file_get_contents($Dir.'newUI/categoryspecial.html');	
			
			$pos = strlen($innerpub);
			if(false !== $pos = strpos($innerpub,'<!-- items -->')){
				if(false === $epos = strpos($innerpub,'<!-- /items -->')) $epos = strlen($innerpub);			
				$conts['items'] = substr($innerpub,$pos+strlen('<!-- items -->'),$epos-$pos-strlen('<!-- items -->'));
			}
			$conts['head'] = substr($innerpub,0,$pos);
			$conts['bott'] = substr($innerpub,$epos);			
			$conts['cont'] = '';
			$conts = str_replace('__ID__','TOP_NEW',$conts);				
			
			if(mysql_num_rows($result)){
				$i=0;
				while($row=mysql_fetch_assoc($result)) {
					$itemtxt = $conts['items'];	
					$row = solvResultforNewUi($row);	
					$row['listfinal'] = (++$i%$newitem1_cols==0)?'endItem':'';					
					foreach($row as $k=>$v){
						$itemtxt = str_replace('product.'.$k,$v,$itemtxt);
					}
					$conts['cont'] .= $itemtxt;		
				}
			}
			$newitem1 = $conts['head'].$conts['cont'].$conts['bott'];
		} else if($newitem_type=="2") {	####################################### 이미지B형 ######################
			$newitem2.="<table border=0 cellpadding=0 cellspacing=0 width=100% style=\"table-layout:fixed\">\n";
			$newitem2.="<tr>\n";
			$newitem2.="	<td>\n";
			$newitem2.="	<table border=0 cellpadding=0 cellspacing=0 width=100%>\n";
			for($j=0;$j<$newitem2_cols;$j++) {
				if($j>0) $newitem2.= "<col width=10></col>\n";
				$newitem2.= "<col width=".floor(100/$newitem2_cols)."%></col>\n";
			}
			$newitem2.="	<tr>\n";

			while($row=mysql_fetch_object($result)) {

				// 리뷰 평점 ( 리뷰 개수 )
				$prAvg = productReviewAverage($row->productcode);
				$prAvgMark = "";
				for( $si = 0 ; $si < $prAvg['average'] ; $si++ ) {
					$prAvgMark .= "<img src=\"/images/003/star_point1.gif\" alt=\"\" />";
				}
				for( $si = $prAvg['average']; $si < 5 ; $si++ ) {
					$prAvgMark .= "<img src=\"/images/003/star_point2.gif\" alt=\"\" />";
				}
				$prAvgMark .= "(".$prAvg['count'].")";

				// 렌탈 아이콘
				$rentalIcon = rentalIcon($row->rental);

				// 예약상품 아이콘 추가
				$row->etctype = reservationEtcType($row->reservation,$row->etctype);

				// 할인율 표시
				$discountRate = ( $row->discountRate > 0 ) ? "<strong>".$row->discountRate."</strong>%↓" : "";

				#####################상품별 회원할인율 적용 시작#######################################
				$strikeStart = '';
				$strikeEnd = '';
				$memberprice = 0;
				$dSql = "SELECT * FROM tblmemberdiscount ";
				$dSql .= "WHERE productcode='".$row->productcode."' AND group_code='".$_ShopInfo->getMemgroup()."'";
				if(false !== $dResult = mysql_query($dSql,get_db_conn())){
					$dRow = mysql_fetch_object($dResult);
					$discountprices = $dRow->discount;
					$discountYN = $dRow->discountYN;
					if($discountprices>0 && $discountYN == 'Y'){
						if($discountprices < 1){
							$memberprice = $row->sellprice - round($row->sellprice*$discountprices);
						}else{
							$memberprice = $row->sellprice - $discountprices;
						}
						$memberprice = number_format($memberprice);
						$strikeStart = "<strike>";
						$strikeEnd = "</strike>";
					}
				}
				#####################상품별 회원할인율 적용 끝 #######################################

				if ($i>0 && $i%$newitem2_cols==0) {
					if($newitem2_colline=="Y") {
						$newitem2.="<tr><td colspan=".$newitem2_colnum." ";
						if(eregi("#prlist_colline",$body)) {
							$newitem2.= "id=prlist_colline></td></tr>\n";
						} else {
							$newitem2.= "height=1><table border=0 cellpadding=0 cellspacing=0 height=1 style=\"table-layout:fixed\"><tr><td height=1 style=\"border:1 dotted #DDDDDD\"><img width=1 height=0></td></tr></table></td></tr>\n";
						}
						$newitem2.="<tr><td colspan=".$newitem2_colnum." height=".$newitem2_gan."></td></tr><tr>\n";
					} else {
						$newitem2.="<tr>\n";
					}
				}

				$tableSize = $_data->primg_minisize;

				if ($i!=0 && $i%$newitem2_cols!=0) {
					$newitem2.="<td width=10 height=100% align=center nowrap>";
					if($newitem2_rowline=="N") $newitem2.="<img width=3 height=0>";
					else if($newitem2_rowline=="Y") {
						$newitem2.="<table border=0 cellpadding=0 cellspacing=0 width=1 height=100 style=\"table-layout:fixed\"><tr><td ";
						if(eregi("#prlist_rowline",$body)) {
							$newitem2.= "id=prlist_rowline height=100></td></tr></table>\n";
						} else {
							$newitem2.= "width=1 height=100 style=\"border:1 dotted #DDDDDD\"><img width=1 height=0></td></tr></table>\n";
						}
					} else if($newitem2_rowline=="L") {
						$newitem2.="<table border=0 cellpadding=0 cellspacing=0 width=1 height=100% style=\"table-layout:fixed\"><tr><td ";
						if(eregi("#prlist_rowline",$body)) {
							$newitem2.= "id=prlist_rowline height=100%></td></tr></table>\n";
						} else {
							$newitem2.= "width=1 height=100% style=\"border:1 dotted #DDDDDD\"><img width=1 height=0></td></tr></table>\n";
						}
					}
					$newitem2.="</td>";
				}

				$newitem2.="<td align=center>\n";
				$newitem2.="<table border=0 cellpadding=0 cellspacing=0 width=100% id=\"N".$row->productcode."\" onmouseover=\"quickfun_show(this,'N".$row->productcode."','','row')\" onmouseout=\"quickfun_show(this,'N".$row->productcode."','none')\" class=\"prInfoBox2\">\n";
				$newitem2.="<col width=\"".$tableSize."\"></col>\n";
				$newitem2.="<col width=\"0\"></col>\n";
				$newitem2.="<col width=></col>\n";

				$newitem2.="<tr>\n";
				$newitem2.="	<td align=center>";
				if (strlen($row->tinyimage)>0 && file_exists($Dir.DataDir."shopimages/product/".$row->tinyimage)==true) {
					$newitem2.="<A HREF=\"".$Dir.FrontDir."productdetail.php?productcode=".$row->productcode.$add_query."\" onmouseover=\"window.status='상품상세조회';return true;\" onmouseout=\"window.status='';return true;\"><img src=\"".$Dir.DataDir."shopimages/product/".urlencode($row->tinyimage)."\" border=0 ";
					$width = getimagesize($Dir.DataDir."shopimages/product/".$row->tinyimage);
					if($_data->ETCTYPE["IMGSERO"]=="Y") {
						if ($width[1]>$width[0] && $width[1]>$_data->primg_minisize2) $newitem2.="height=".$_data->primg_minisize2." ";
						else if (($width[1]>=$width[0] && $width[0]>=$newitem2_imgsize) || $width[0]>=$newitem2_imgsize) $newitem2.="width=".$newitem2_imgsize." ";
					} else {
						if ($width[0]>=$width[1] && $width[0]>=$newitem2_imgsize) $newitem2.="width=".$newitem2_imgsize." ";
						else if ($width[1]>=$newitem2_imgsize) $newitem2.="height=".$newitem2_imgsize." ";
					}
				} else {
					$newitem2.="<img src=\"".$Dir."images/no_img.gif\" border=0 align=center";
				}
				$newitem2.="></A></td>\n";
				$newitem2.="	<td style=\"position:relative;\">".($_data->ETCTYPE["QUICKTOOLS"]!="Y"?"<script>quickfun_write('".$Dir."','N','".$row->productcode."','".($row->quantity=="0"?"":"1")."','row')</script>":"")."</td>";
				$newitem2.="	<td style=\"padding-left:15px; word-break:break-all;\">\n";
				$newitem2.="	<table border=0 cellpadding=0 cellspacing=0 width=100%>\n";
				$newitem2.="	<tr>\n";
				//$newitem2.="		<td align=left valign=top style=\"word-break:break-all;\"><A HREF=\"".$Dir.FrontDir."productdetail.php?productcode=".$row->productcode.$add_query."\" onmouseover=\"window.status='상품상세조회';return true;\" onmouseout=\"window.status='';return true;\"><FONT class=\"prname\">".viewproductname($row->productname,$row->etctype,$row->selfcode)."</FONT></A></td>\n";
				$newitem2.="		<td align=left valign=top style=\"word-break:break-all;\"><A HREF=\"".$Dir.FrontDir."productdetail.php?productcode=".$row->productcode.$add_query."\" onmouseover=\"window.status='상품상세조회';return true;\" onmouseout=\"window.status='';return true;\"><FONT class=\"prname\">".$rentalIcon.viewproductname($row->productname,$row->etctype,$row->selfcode,$row->addcode)."</FONT>".(strlen($row->prmsg)?'<br /><span class="prmsgArea">'.$row->prmsg.'</span>':'')."</A></td>\n";
				$newitem2.="	</tr>\n";

				//모델명/브랜드/제조사/원산지
				if($newitem2_production=="Y" || $newitem2_madein=="Y" || $newitem2_model=="Y" || $newitem2_brand=="Y") {
					$newitem2.="<tr>\n";
					$newitem2.="	<td align=left valign=top style=\"word-break:break-all;\" class=\"prproduction\">";
					if(strlen($row->production)>0 || strlen($row->madein)>0 || strlen($row->model)>0 || strlen($row->brand)>0) {
						unset($addspec);
						if($newitem2_production=="Y" && strlen($row->production)>0) {
							$addspec[]=$row->production;
						}
						if($newitem2_madein=="Y" && strlen($row->madein)>0) {
							$addspec[]=$row->madein;
						}
						if($newitem2_model=="Y" && strlen($row->model)>0) {
							$addspec[]=$row->model;
						}
						//if($newitem2_brand=="Y" && strlen($row->brand)>0) {
						//	$addspec[]=$row->brand;
						//}
						$newitem2.= implode("/", $addspec);
					}
					$newitem2.="	</td>\n";
					$newitem2.="</tr>\n";
				}

				//시중가 + 판매가 + 할인율 + 회원할인가
				$newitem2.= "<tr>
									<td style=\"padding:0px 7px 7px 0px; word-break:break-all;\">
										<table border=0 cellpadding=0 cellspacing=0 width=100%>
											<tr>
												<td>
				";
				if($newitem2_price=="Y" && $row->consumerprice>0) {	//소비자가
					//$newitem2.="	<tr>\n";
					//$newitem2.="		<td align=left valign=top style=\"word-break:break-all;\" class=\"prconsumerprice\"><img src=\"".$Dir."images/common/won_icon2.gif\" border=0 align=absmiddle> <s>".number_format($row->consumerprice)."원</s></td>\n";
					$newitem2.="	<span class=\"prconsumerprice\" style=\"padding-right:2px;\"><strike>".number_format($row->consumerprice)."</strike>원</span>\n";
					//$newitem2.="	</tr>\n";
				}

				// 회원 할인가가 있을 때 가격 class 변경
				if($discountprices > 0){
					$prpriceClass = "";
				}else{
					$prpriceClass = "prprice";
				}

				//$newitem2.="	<tr>\n";
				//$newitem2.="		<td align=left valign=top style=\"word-break:break-all;\" class=\"prprice\">";
				$newitem2.="<span style=\"white-space:nowrap;\">";
				$newitem2.=$strikeStart;
				if($dicker=dickerview($row->etctype,number_format($row->sellprice)."원",1)) {
					$newitem2.= $dicker;
				} else if(strlen($_data->proption_price)==0) {
					//$newitem2.= "<img src=\"".$Dir."images/common/won_icon.gif\" border=0 align=absmiddle> ".$strikeStart.number_format($row->sellprice)."원".$strikeEnd;
					$newitem2.= "<strong class=\"".$prpriceClass."\">".number_format($row->sellprice)."</strong><strong>원</strong>";
					//if (strlen($row->option_price)!=0) $newitem2.= "(기본가)";
				} else {
					//$newitem2.="<img src=\"".$Dir."images/common/won_icon.gif\" border=0 align=absmiddle> ";
					if (strlen($row->option_price)==0) $newitem2.= $strikeStart.number_format($row->sellprice)."원".$strikeEnd;
					else $newitem2.= ereg_replace("\[PRICE\]",number_format($row->sellprice),$_data->proption_price);
				}
				$newitem2.=$strikeEnd;
				$newitem2.="</span>";

				if($row->discountRate > 0){
					$newitem2.="<span class=\"discount\">".$discountRate."</span>";
				}
				$newitem2.="
							</td>
						</tr>
					</table>
				";

				if ($row->quantity=="0") $newitem2.= soldout();
				//$newitem2.="		</td>\n";
				//$newitem2.="	</tr>\n";

				//회원할인가 적용
				if($discountprices>0 && $discountYN == 'Y'){
					$newitem2 .= "<div><span class=\"prprice\">".$memberprice."원</span> <img src=\"".$Dir."images/common/memsale_icon.gif\" align=\"absmiddle\" alt=\"\" /></div>";
				}

				$reserveconv=getReserveConversion($row->reserve,$row->reservetype,$row->sellprice,"Y");
				if($newitem2_reserve=="Y" && $reserveconv>0) {	//적립금
					$newitem2.="	<tr>\n";
					$newitem2.="		<td align=left valign=top style=\"word-break:break-all;\" class=\"prreserve\"><img src=\"".$Dir."images/common/reserve_icon.gif\" border=0 align=absmiddle> ".number_format($reserveconv)."원";
					$newitem2.="		</td>\n";
					$newitem2.="	</tr>\n";
				}

				//태그관련
				if($newitem2_tag>0 && strlen($row->tag)>0) {
					$newitem2.="	<tr>\n";
					$newitem2.="		<td align=left style=\"word-break:break-all;\" class=\"prtag\"><img src=\"".$Dir."images/common/tag_icon.gif\" border=0 align=absmiddle><img width=2 height=0>";
					$arrtaglist=explode(",",$row->tag);
					$jj=0;
					for($ii=0;$ii<count($arrtaglist);$ii++) {
						$arrtaglist[$ii]=ereg_replace("(<|>)","",$arrtaglist[$ii]);
						if(strlen($arrtaglist[$ii])>0) {
							if($jj<$newitem2_tag) {
								if($jj>0) $newitem2.="<img width=2 height=0>+<img width=2 height=0>";
							} else {
								if($jj>0) $newitem2.="<img width=2 height=0>+<img width=2 height=0>";
								break;
							}
							$newitem2.="<a href=\"".$Dir.FrontDir."tag.php?tagname=".urlencode($arrtaglist[$ii])."\" onmouseover=\"window.status='".$arrtaglist[$ii]."';return true;\" onmouseout=\"window.status='';return true;\"><FONT class=\"prtag\">".$arrtaglist[$ii]."</FONT></a>";
							$jj++;
						}
					}
					$newitem2.="		</td>\n";
					$newitem2.="	</tr>\n";
				}

				// 입점사 네임택
				if( nameTechUse($row->vender) ) {
					$classList = array();
					$classResult=mysql_query("SELECT * FROM `tblVenderClassType` ",get_db_conn());
					while($classRow=mysql_fetch_object($classResult)) {
						$classList[$classRow->idx] = $classRow->name;
					}
					$v_info = mysql_fetch_assoc ( mysql_query( "SELECT * FROM `tblvenderinfo` WHERE `vender`=".$row->vender." LIMIT 1;" ,get_db_conn()) );

					// 네임텍 출력
					$newitem2.="	<tr>\n";
					$newitem2.="		<td>\n";
					$newitem2 .= "	<div class=\"nameTagBox2\"><span class=\"name\">".$v_info['com_name']."</span> <span class=\"owner\">(".$v_info['com_owner'].")</span></div>";
					$newitem2 .= "	<div><a href=\"javascript:GoMinishop('/minishop.php?storeid=".$v_info['id']."')\"><img src=\"/images/common/icon_vender_go.gif\" border=\"0\" align=\"absmiddle\" alt=\"전체상품보기\" /></a></div>";
					$newitem2.="		</td>\n";
					$newitem2.="	</tr>\n";
				}

				$newitem2.="	</table>\n";
				$newitem2.="	</td>\n";
				$newitem2.="</tr>\n";
				$newitem2.="</table>\n";
				$newitem2.="</td>\n";
				$i++;

				if ($i==$newitem2_product_num) break;
				if ($i%$newitem2_cols==0) {
					$newitem2.="</tr><tr><td colspan=".$newitem2_colnum." height=".$newitem2_gan."></td></tr>\n";
				}
			}
			if($i>0 && $i<$newitem2_cols) {
				for($k=0; $k<($newitem2_cols-$i); $k++) {
					$newitem2.="<td></td>\n<td></td>\n";
				}
			}
			mysql_free_result($result);

			$newitem2.="	</tr>\n";
			$newitem2.="	</table>\n";
			$newitem2.="	</td>\n";
			$newitem2.="</tr>\n";
			$newitem2.="</table>\n";

		} else if($newitem_type=="3") {	####################################### 리스트형 #######################
			$colspan=4;
			$image_height=60;
			$newitem3 = "<table border=0 cellpadding=0 cellspacing=0 width=100%>\n";
			$newitem3.= "<col width=70></col>\n";
			$newitem3.= "<col width=\"0\"></col>\n";
			$newitem3.= "<col width=></col>\n";
			if($newitem3_production=="Y" || $newitem3_madein=="Y" || $newitem3_model=="Y" || $newitem3_model=="Y") {
				$colspan++;
				$newitem3.= "<col width=120></col>\n";
			}
			if($newitem3_price=="Y") {
				$colspan++;
				$newitem3.= "<col width=90></col>\n";
			}
			$newitem3.= "<col width=120></col>\n";
			if($newitem3_reserve=="Y") {
				$colspan++;
				$newitem3.= "<col width=70></col>\n";
			}
			while($row=mysql_fetch_object($result)) {

				// 리뷰 평점 ( 리뷰 개수 )
				$prAvg = productReviewAverage($row->productcode);
				$prAvgMark = "";
				for( $si = 0 ; $si < $prAvg['average'] ; $si++ ) {
					$prAvgMark .= "<img src=\"/images/003/star_point1.gif\" alt=\"\" />";
				}
				for( $si = $prAvg['average']; $si < 5 ; $si++ ) {
					$prAvgMark .= "<img src=\"/images/003/star_point2.gif\" alt=\"\" />";
				}
				$prAvgMark .= "(".$prAvg['count'].")";

				// 렌탈 아이콘
				$rentalIcon = rentalIcon($row->rental);

				// 예약상품 아이콘 추가
				$row->etctype = reservationEtcType($row->reservation,$row->etctype);

				#####################상품별 회원할인율 적용 시작#######################################
				$dSql = "SELECT discount FROM tblmemberdiscount ";
				$dSql .= "WHERE productcode='".$row->productcode."' AND group_code='".$_ShopInfo->getMemgroup()."'";
				$dResult = mysql_query($dSql,get_db_conn());
				$dRow = mysql_fetch_object($dResult);
				$discountprices = $dRow->discount;
				if($discountprices>0){
					if($discountprices < 1){
						$memberprice = $row->sellprice - round($row->sellprice*$discountprices);
					}else{
						$memberprice = $row->sellprice - $discountprices;
					}
					$memberprice = number_format($memberprice);
					$strikeStart = "<strike>";
					$strikeEnd = "</strike>";
				}
				#####################상품별 회원할인율 적용 끝 #######################################

				if($i>0) {
					$newitem3.="<tr><td colspan=".$colspan." ";
					if(eregi("#prlist_colline",$body)) {
						$newitem3.= "id=prlist_colline></td></tr>\n";
					} else {
						$newitem3.= "height=1><table border=0 cellpadding=0 cellspacing=0 height=1 style=\"table-layout:fixed\"><tr><td height=1 style=\"border:1 dotted #DDDDDD\"><img width=1 height=0></td></tr></table></td></tr>\n";
					}
				}
				$newitem3.= "<tr height=".$image_height." id=\"N".$row->productcode."\" onmouseover=\"quickfun_show(this,'N".$row->productcode."','','row')\" onmouseout=\"quickfun_show(this,'N".$row->productcode."','none')\">\n";
				$newitem3.= "	<td align=center>";
				if (strlen($row->tinyimage)>0 && file_exists($Dir.DataDir."shopimages/product/".$row->tinyimage)==true) {
					$newitem3.= "<A HREF=\"".$Dir.FrontDir."productdetail.php?productcode=".$row->productcode.$add_query."\" onmouseover=\"window.status='상품상세조회';return true;\" onmouseout=\"window.status='';return true;\"><img src=\"".$Dir.DataDir."shopimages/product/".urlencode($row->tinyimage)."\" border=0 ";
					$width = getimagesize($Dir.DataDir."shopimages/product/".$row->tinyimage);
					if ($width[0]>=$width[1] && $width[0]>=60) $newitem3.= "width=60 ";
					else if ($width[1]>=60) $newitem3.= "height=60 ";
				} else {
					$newitem3.= "<img src=\"".$Dir."images/no_img.gif\" height=60 border=0 align=center";
				}
				$newitem3.= "	></A></td>\n";
				$newitem3.="	<td style=\"position:relative;\">".($_data->ETCTYPE["QUICKTOOLS"]!="Y"?"<script>quickfun_write('".$Dir."','N','".$row->productcode."','".($row->quantity=="0"?"":"1")."','row')</script>":"")."</td>";
				//$newitem3.= "	<td style=\"padding-left:5\" style=\"word-break:break-all;\"><A HREF=\"".$Dir.FrontDir."productdetail.php?productcode=".$row->productcode.$add_query."\" onmouseover=\"window.status='상품상세조회';return true;\" onmouseout=\"window.status='';return true;\"><FONT class=\"prname\">".viewproductname($row->productname,$row->etctype,$row->selfcode)."</FONT></A>";
				$newitem3.= "	<td style=\"padding-left:5\" style=\"word-break:break-all;\"><A HREF=\"".$Dir.FrontDir."productdetail.php?productcode=".$row->productcode.$add_query."\" onmouseover=\"window.status='상품상세조회';return true;\" onmouseout=\"window.status='';return true;\"><FONT class=\"prname\">".$rentalIcon.viewproductname($row->productname,$row->etctype,$row->selfcode,$row->addcode)."</FONT>".(strlen($row->prmsg)?'<br /><span class="prmsgArea">'.$row->prmsg.'</span>':'')."</A>";
				if ($row->quantity=="0") $newitem3.= soldout();
				//태그관련
				if($newitem3_tag>0 && strlen($row->tag)>0) {
					$newitem3.="<br><img src=\"".$Dir."images/common/tag_icon.gif\" border=0 align=absmiddle><img width=2 height=0>";
					$arrtaglist=explode(",",$row->tag);
					$jj=0;
					for($ii=0;$ii<count($arrtaglist);$ii++) {
						$arrtaglist[$ii]=ereg_replace("(<|>)","",$arrtaglist[$ii]);
						if(strlen($arrtaglist[$ii])>0) {
							if($jj<$newitem3_tag) {
								if($jj>0) $newitem3.="<img width=2 height=0><FONT class=\"prtag\">+</FONT><img width=2 height=0>";
							} else {
								if($jj>0) $newitem3.="<img width=2 height=0><FONT class=\"prtag\">+</FONT><img width=2 height=0>";
								break;
							}
							$newitem3.="<a href=\"".$Dir.FrontDir."tag.php?tagname=".urlencode($arrtaglist[$ii])."\" onmouseover=\"window.status='".$arrtaglist[$ii]."';return true;\" onmouseout=\"window.status='';return true;\"><FONT class=\"prtag\">".$arrtaglist[$ii]."</FONT></a>";
							$jj++;
						}
					}
				}
				$newitem3.= "</td>\n";
				//모델명/브랜드/제조사/원산지
				if($newitem3_production=="Y" || $newitem3_madein=="Y" || $newitem3_model=="Y" || $newitem3_brand=="Y") {
					$newitem3.="	<td align=center style=\"word-break:break-all;\" class=\"prproduction\">";
					if(strlen($row->production)>0 || strlen($row->madein)>0 || strlen($row->model)>0 || strlen($row->brand)>0) {
						unset($addspec);
						if($newitem3_production=="Y" && strlen($row->production)>0) {
							$addspec[]=$row->production;
						}
						if($newitem3_madein=="Y" && strlen($row->madein)>0) {
							$addspec[]=$row->madein;
						}
						if($newitem3_model=="Y" && strlen($row->model)>0) {
							$addspec[]=$row->model;
						}
						//if($newitem3_brand=="Y" && strlen($row->brand)>0) {
						//	$addspec[]=$row->brand;
						//}
						$newitem3.= implode("/", $addspec);
					}
					$newitem3.="	</td>\n";
				}
				if($newitem3_price=="Y") {
					$newitem3.= "	<td align=center style=\"word-break:break-all;\" class=\"prconsumerprice\"><img src=\"".$Dir."images/common/won_icon2.gif\" border=0 align=absmiddle> <strike>".number_format($row->consumerprice)."</strike>원</td>\n";
				}
				$newitem3.= "	<td align=center style=\"word-break:break-all;\" class=\"prprice\">";
				if($dicker=dickerview($row->etctype,number_format($row->sellprice)."원",1)) {
					$newitem3.= $dicker;
				} else if(strlen($_data->proption_price)==0) {
					$newitem3.= "<img src=\"".$Dir."images/common/won_icon.gif\" border=0 align=absmiddle> ".$strikeStart.number_format($row->sellprice)."원".$strikeEnd;
					if (strlen($row->option_price)!=0) $newitem3.= "(기본가)";
				} else {
					$newitem3.="<img src=\"".$Dir."images/common/won_icon.gif\" border=0 align=absmiddle> ";
					if (strlen($row->option_price)==0) $newitem3.= $strikeStart.number_format($row->sellprice)."원".$strikeEnd;
					else $newitem3.= ereg_replace("\[PRICE\]",number_format($row->sellprice),$_data->proption_price);
				}

				//회원할인가 적용
				if($discountprices>0){
					$newitem3 .= "<br /><img src=\"".$Dir."images/common/memsale_icon.gif\" style=\"position:relative; top:0.1em;\" alt=\"\" /> ".$memberprice."원";
				}

				$newitem3.= "	</td>\n";
				if($newitem3_reserve=="Y") {
					$reserveconv=getReserveConversion($row->reserve,$row->reservetype,$row->sellprice,"Y");
					$newitem3.= "	<td align=center style=\"word-break:break-all;\" class=prreserve><img src=\"".$Dir."images/common/reserve_icon.gif\" border=0 align=absmiddle> ".number_format($reserveconv)."원</td>\n";
				}
				$newitem3.= "</tr>\n";
				$i++;
			}
			$newitem3.= "</table>\n";
		}
	}
}

################ 인기상품 ###############
$bestitem1=""; $bestitem2=""; $bestitem3="";
if(preg_match("/^(1|2|3)$/",$bestitem_type)) {
	$sql = "SELECT special_list FROM tblspecialcode WHERE code='".$code."' AND special='2' ";
	$result=mysql_query($sql,get_db_conn());
	$sp_prcode="";
	if($row=mysql_fetch_object($result)) {
		$sp_prcode=ereg_replace(',','\',\'',$row->special_list);
	}
	mysql_free_result($result);

	$bestitem_tab="";
	if(strlen($sp_prcode)>0) {

		//카테고리 인기상품 탭출력
		$bestitem_tab.="<li><a href='#'><img src='/data/design/img/sub/tab_prsection2.gif' asrc='/data/design/img/sub/tab_prsection2_on.gif' border='0' alt='' /></a></li>";

		$sql = "SELECT a.* FROM tblproduct AS a ";
		$sql.= "LEFT OUTER JOIN tblproductgroupcode b ON a.productcode=b.productcode ";
		$sql.= "LEFT OUTER JOIN rent_product_option opt ON a.pridx=opt.pridx ";
		$sql.= "WHERE a.productcode IN ('".$sp_prcode."') AND a.display='Y' ";
		$sql.= "AND (a.group_check='N' OR b.group_code='".$_ShopInfo->getMemgroup()."') ";
		if(strlen($not_qry)>0) {
			$sql.= $not_qry." ";
		}
//		$sql.= $search_sql." "; //search
		$sql.= "ORDER BY FIELD(a.productcode,'".$sp_prcode."') ";
		$sql.= "LIMIT ".$bestitem_product_num;
		$result=mysql_query($sql,get_db_conn());

		$i=0;
		if($bestitem_type=="1") {	####################################### 이미지A형 #########################
			$innerpub = file_get_contents($Dir.'newUI/categoryspecial.html');
			
			$pos = strlen($innerpub);
			if(false !== $pos = strpos($innerpub,'<!-- items -->')){
				if(false === $epos = strpos($innerpub,'<!-- /items -->')) $epos = strlen($innerpub);			
				$conts['items'] = substr($innerpub,$pos+strlen('<!-- items -->'),$epos-$pos-strlen('<!-- items -->'));
			}
			$conts['head'] = substr($innerpub,0,$pos);
			$conts['bott'] = substr($innerpub,$epos);
			$conts['cont'] = '';
			$conts = str_replace('__ID__','TOP_BEST',$conts);
			//$conts = str_replace('__ID__','',$conts);
			
			if(mysql_num_rows($result)){
				$i=0;
				while($row=mysql_fetch_assoc($result)) {
					$itemtxt = $conts['items'];	
					$row = solvResultforNewUi($row);	
					$row['listfinal'] = (++$i%$bestitem1_cols==0)?'endItem':'';
					foreach($row as $k=>$v){
						$itemtxt = str_replace('product.'.$k,$v,$itemtxt);
					}
					$conts['cont'] .= $itemtxt;		
				}
			}
			$bestitem1 = $conts['head'].$conts['cont'].$conts['bott'];
		} else if($bestitem_type=="2") {	####################################### 이미지B형 #################
			$bestitem2.="<table border=0 cellpadding=0 cellspacing=0 width=100% style=\"table-layout:fixed\">\n";
			$bestitem2.="<tr>\n";
			$bestitem2.="	<td>\n";
			$bestitem2.="	<table border=0 cellpadding=0 cellspacing=0 width=100%>\n";
			for($j=0;$j<$bestitem2_cols;$j++) {
				if($j>0) $bestitem2.= "<col width=10></col>\n";
				$bestitem2.= "<col width=".floor(100/$bestitem2_cols)."%></col>\n";
			}
			$bestitem2.="	<tr>\n";

			while($row=mysql_fetch_object($result)) {

				// 리뷰 평점 ( 리뷰 개수 )
				$prAvg = productReviewAverage($row->productcode);
				$prAvgMark = "";
				for( $si = 0 ; $si < $prAvg['average'] ; $si++ ) {
					$prAvgMark .= "<img src=\"/images/003/star_point1.gif\" alt=\"\" />";
				}
				for( $si = $prAvg['average']; $si < 5 ; $si++ ) {
					$prAvgMark .= "<img src=\"/images/003/star_point2.gif\" alt=\"\" />";
				}
				$prAvgMark .= "(".$prAvg['count'].")";

				// 렌탈 아이콘
				$rentalIcon = rentalIcon($row->rental);

				// 예약상품 아이콘 추가
				$row->etctype = reservationEtcType($row->reservation,$row->etctype);

				#####################상품별 회원할인율 적용 시작#######################################
				$strikeStart = '';
				$strikeEnd = '';
				$memberprice = 0;
				$dSql = "SELECT * FROM tblmemberdiscount ";
				$dSql .= "WHERE productcode='".$row->productcode."' AND group_code='".$_ShopInfo->getMemgroup()."'";
				$dResult = mysql_query($dSql,get_db_conn());
				$dRow = mysql_fetch_object($dResult);
				$discountprices = $dRow->discount;
				$discountYN = $dRow->discountYN;
				if($discountprices>0 && $discountYN == 'Y'){					
					if($discountprices < 1){
						$memberprice = $row->sellprice - round($row->sellprice*$discountprices);
					}else{
						$memberprice = $row->sellprice - $discountprices;
					}
					$memberprice = number_format($memberprice);
					$strikeStart = "<strike>";
					$strikeEnd = "</strike>";
				}
				#####################상품별 회원할인율 적용 끝 #######################################

				if ($i>0 && $i%$bestitem2_cols==0) {
					if($bestitem2_colline=="Y") {
						$bestitem2.="<tr><td colspan=".$bestitem2_colnum." ";
						if(eregi("#prlist_colline",$body)) {
							$bestitem2.= "id=prlist_colline></td></tr>\n";
						} else {
							$bestitem2.= "height=1><table border=0 cellpadding=0 cellspacing=0 height=1 style=\"table-layout:fixed\"><tr><td height=1 style=\"border:1 dotted #DDDDDD\"><img width=1 height=0></td></tr></table></td></tr>\n";
						}
						$bestitem2.="<tr><td colspan=".$bestitem2_colnum." height=".$bestitem2_gan."></td></tr><tr>\n";
					} else {
						$bestitem2.="<tr>\n";
					}
				}

				$tableSize = $_data->primg_minisize;

				if ($i!=0 && $i%$bestitem2_cols!=0) {
					$bestitem2.="<td width=10 height=100% align=center nowrap>";
					if($bestitem2_rowline=="N") $bestitem2.="<img width=3 height=0>";
					else if($bestitem2_rowline=="Y") {
						$bestitem2.="<table border=0 cellpadding=0 cellspacing=0 width=1 height=100 style=\"table-layout:fixed\"><tr><td ";
						if(eregi("#prlist_rowline",$body)) {
							$bestitem2.= "id=prlist_rowline height=100></td></tr></table>\n";
						} else {
							$bestitem2.= "width=1 height=100 style=\"border:1 dotted #DDDDDD\"><img width=1 height=0></td></tr></table>\n";
						}
					} else if($bestitem2_rowline=="L") {
						$bestitem2.="<table border=0 cellpadding=0 cellspacing=0 width=1 height=100% style=\"table-layout:fixed\"><tr><td ";
						if(eregi("#prlist_rowline",$body)) {
							$bestitem2.= "id=prlist_rowline height=100%></td></tr></table>\n";
						} else {
							$bestitem2.= "width=1 height=100% style=\"border:1 dotted #DDDDDD\"><img width=1 height=0></td></tr></table>\n";
						}
					}
					$bestitem2.="</td>";
				}

				$bestitem2.="<td align=center>\n";
				$bestitem2.="<table border=0 cellpadding=0 cellspacing=0 width=100% id=\"B".$row->productcode."\" onmouseover=\"quickfun_show(this,'B".$row->productcode."','','row')\" onmouseout=\"quickfun_show(this,'B".$row->productcode."','none')\" class=\"prInfoBox2\">\n";
				$bestitem2.="<col width=\"".$tableSize."\"></col>\n";
				$bestitem2.="<col width=\"0\"></col>\n";
				$bestitem2.="<col width=\"\"></col>\n";

				$bestitem2.="<tr>\n";
				$bestitem2.="	<td align=center>";
				if (strlen($row->tinyimage)>0 && file_exists($Dir.DataDir."shopimages/product/".$row->tinyimage)==true) {
					$bestitem2.="<A HREF=\"".$Dir.FrontDir."productdetail.php?productcode=".$row->productcode.$add_query."\" onmouseover=\"window.status='상품상세조회';return true;\" onmouseout=\"window.status='';return true;\"><img src=\"".$Dir.DataDir."shopimages/product/".urlencode($row->tinyimage)."\" border=0 ";
					$width = getimagesize($Dir.DataDir."shopimages/product/".$row->tinyimage);
					if($_data->ETCTYPE["IMGSERO"]=="Y") {
						if ($width[1]>$width[0] && $width[1]>$_data->primg_minisize2) $bestitem2.="height=".$_data->primg_minisize2." ";
						else if (($width[1]>=$width[0] && $width[0]>=$bestitem2_imgsize) || $width[0]>=$bestitem2_imgsize) $bestitem2.="width=".$bestitem2_imgsize." ";
					} else {
						if ($width[0]>=$width[1] && $width[0]>=$bestitem2_imgsize) $bestitem2.="width=".$bestitem2_imgsize." ";
						else if ($width[1]>=$bestitem2_imgsize) $bestitem2.="height=".$bestitem2_imgsize." ";
					}
				} else {
					$bestitem2.="<img src=\"".$Dir."images/no_img.gif\" border=0 align=center";
				}
				$bestitem2.="></A></td>\n";
				$bestitem2.="	<td style=\"position:relative;\">".($_data->ETCTYPE["QUICKTOOLS"]!="Y"?"<script>quickfun_write('".$Dir."','B','".$row->productcode."','".($row->quantity=="0"?"":"1")."','row')</script>":"")."</td>";
				$bestitem2.="	<td style=\"padding-left:15px; word-break:break-all;\">\n";
				$bestitem2.="	<table border=0 cellpadding=0 cellspacing=0 width=100%>\n";
				$bestitem2.="	<tr>\n";
				$bestitem2.="		<td align=left valign=top style=\"word-break:break-all;\"><A HREF=\"".$Dir.FrontDir."productdetail.php?productcode=".$row->productcode.$add_query."\" onmouseover=\"window.status='상품상세조회';return true;\" onmouseout=\"window.status='';return true;\"><FONT class=\"prname\">".$rentalIcon.viewproductname($row->productname,$row->etctype,$row->selfcode,$row->addcode)."</FONT>".(strlen($row->prmsg)?'<br /><span class="prmsgArea">'.$row->prmsg.'</span>':'')."</A></td>\n";
				$bestitem2.="	</tr>\n";

				//모델명/브랜드/제조사/원산지
				if($bestitem2_production=="Y" || $bestitem2_madein=="Y" || $bestitem2_model=="Y" || $bestitem2_brand=="Y") {
					$bestitem2.="<tr>\n";
					$bestitem2.="	<td align=left valign=top style=\"word-break:break-all;\" class=\"prproduction\">";
					if(strlen($row->production)>0 || strlen($row->madein)>0 || strlen($row->model)>0 || strlen($row->brand)>0) {
						unset($addspec);
						if($bestitem2_production=="Y" && strlen($row->production)>0) {
							$addspec[]=$row->production;
						}
						if($bestitem2_madein=="Y" && strlen($row->madein)>0) {
							$addspec[]=$row->madein;
						}
						if($bestitem2_model=="Y" && strlen($row->model)>0) {
							$addspec[]=$row->model;
						}
						//if($bestitem2_brand=="Y" && strlen($row->brand)>0) {
						//	$addspec[]=$row->brand;
						//}
						$bestitem2.= implode("/", $addspec);
					}
					$bestitem2.="	</td>\n";
					$bestitem2.="</tr>\n";
				}

				//시중가 + 판매가 + 할인율 + 회원할인가
				$bestitem2.= "<tr>
									<td style=\"padding:0px 7px 7px 0px; word-break:break-all;\">
										<table border=0 cellpadding=0 cellspacing=0 width=100%>
											<tr>
												<td>
				";

				if($bestitem2_price=="Y" && $row->consumerprice>0) {	//소비자가
					//$bestitem2.="	<tr>\n";
					//$bestitem2.="		<td align=left valign=top style=\"word-break:break-all;\" class=\"prconsumerprice\"><img src=\"".$Dir."images/common/won_icon2.gif\" border=0 align=absmiddle> <s>".number_format($row->consumerprice)."원</s></td>\n";
					$bestitem2.="	<span class=\"prconsumerprice\" style=\"padding-right:2px;\"><strike>".number_format($row->consumerprice)."</strike>원</span>\n";
					//$bestitem2.="	</tr>\n";
				}

				// 회원 할인가가 있을 때 가격 class 변경
				if($discountprices > 0){
					$prpriceClass = "";
				}else{
					$prpriceClass = "prprice";
				}

				//$bestitem2.="	<tr>\n";
				//$bestitem2.="		<td align=left valign=top style=\"word-break:break-all;\" class=\"prprice\">";
				$bestitem2.="<span style=\"white-space:nowrap;\">";
				$bestitem2.=$strikeStart;
				if($dicker=dickerview($row->etctype,number_format($row->sellprice)."원",1)) {
					$bestitem2.= $dicker;
				} else if(strlen($_data->proption_price)==0) {
					//$bestitem2.= "<img src=\"".$Dir."images/common/won_icon.gif\" border=0 align=absmiddle> ".$strikeStart.number_format($row->sellprice)."원".$strikeEnd;
					$bestitem2.= "<strong class=\"".$prpriceClass."\">".number_format($row->sellprice)."</strong><strong>원</strong>";
					//if (strlen($row->option_price)!=0) $bestitem2.= "(기본가)";
				} else {
					//$bestitem2.="<img src=\"".$Dir."images/common/won_icon.gif\" border=0 align=absmiddle> ";
					if (strlen($row->option_price)==0) $bestitem2.= number_format($row->sellprice)."원";
					else $bestitem2.= ereg_replace("\[PRICE\]",number_format($row->sellprice),$_data->proption_price);
				}
				$bestitem2.=$strikeEnd;
				$bestitem2.="</span>";

				if($row->discountRate > 0){
					$bestitem2.="<span class=\"discount\">".$discountRate."</span>";
				}
				$bestitem2.="
							</td>
						</tr>
					</table>
				";

				if ($row->quantity=="0") $bestitem2.= soldout();
				//$bestitem2.="		</td>\n";
				//$bestitem2.="	</tr>\n";

				//회원할인가 적용
				if($discountprices>0 && $discountYN == 'Y'){
					$bestitem2 .= "<div><span class=\"prprice\">".$memberprice."원</span> <img src=\"".$Dir."images/common/memsale_icon.gif\" align=\"absmiddle\" alt=\"\" /></div>";
				}
				$bestitem2.="		</td>\n";
				$bestitem2.="	</tr>\n";

				$reserveconv=getReserveConversion($row->reserve,$row->reservetype,$row->sellprice,"Y");
				if($bestitem2_reserve=="Y" && $reserveconv>0) {	//적립금
					$bestitem2.="	<tr>\n";
					$bestitem2.="		<td align=left valign=top style=\"word-break:break-all;\" class=\"prreserve\"><img src=\"".$Dir."images/common/reserve_icon.gif\" border=0 align=absmiddle> ".number_format($reserveconv)."원";
					$bestitem2.="		</td>\n";
					$bestitem2.="	</tr>\n";
				}

				//태그관련
				if($bestitem2_tag>0 && strlen($row->tag)>0) {
					$bestitem2.="	<tr>\n";
					$bestitem2.="		<td align=left style=\"word-break:break-all;\" class=\"prtag\"><img src=\"".$Dir."images/common/tag_icon.gif\" border=0 align=absmiddle><img width=2 height=0>";
					$arrtaglist=explode(",",$row->tag);
					$jj=0;
					for($ii=0;$ii<count($arrtaglist);$ii++) {
						$arrtaglist[$ii]=ereg_replace("(<|>)","",$arrtaglist[$ii]);
						if(strlen($arrtaglist[$ii])>0) {
							if($jj<$bestitem2_tag) {
								if($jj>0) $bestitem2.="<img width=2 height=0>+<img width=2 height=0>";
							} else {
								if($jj>0) $bestitem2.="<img width=2 height=0>+<img width=2 height=0>";
								break;
							}
							$bestitem2.="<a href=\"".$Dir.FrontDir."tag.php?tagname=".urlencode($arrtaglist[$ii])."\" onmouseover=\"window.status='".$arrtaglist[$ii]."';return true;\" onmouseout=\"window.status='';return true;\"><FONT class=\"prtag\">".$arrtaglist[$ii]."</FONT></a>";
							$jj++;
						}
					}
					$bestitem2.="		</td>\n";
					$bestitem2.="	</tr>\n";
				}

				// 입점사 네임택
				if( nameTechUse($row->vender) ) {
					$classList = array();
					$classResult=mysql_query("SELECT * FROM `tblVenderClassType` ",get_db_conn());
					while($classRow=mysql_fetch_object($classResult)) {
						$classList[$classRow->idx] = $classRow->name;
					}
					$v_info = mysql_fetch_assoc ( mysql_query( "SELECT * FROM `tblvenderinfo` WHERE `vender`=".$row->vender." LIMIT 1;" ,get_db_conn()) );

					// 네임텍 출력
					$bestitem2.="	<tr>\n";
					$bestitem2.="		<td>\n";
					$bestitem2 .= "	<div class=\"nameTagBox2\"><span class=\"name\">".$v_info['com_name']."</span> <span class=\"owner\">(".$v_info['com_owner'].")</span></div>";
					$bestitem2 .= "	<div><a href=\"javascript:GoMinishop('/minishop.php?storeid=".$v_info['id']."')\"><img src=\"/images/common/icon_vender_go.gif\" border=\"0\" align=\"absmiddle\" alt=\"전체상품보기\" /></a></div>";
					$bestitem2.="		</td>\n";
					$bestitem2.="	</tr>\n";
				}

				$bestitem2.="	</table>\n";
				$bestitem2.="	</td>\n";
				$bestitem2.="</tr>\n";
				$bestitem2.="</table>\n";
				$bestitem2.="</td>\n";
				$i++;

				if ($i==$bestitem2_product_num) break;
				if ($i%$bestitem2_cols==0) {
					$bestitem2.="</tr><tr><td colspan=".$bestitem2_colnum." height=".$bestitem2_gan."></td></tr>\n";
				}
			}
			if($i>0 && $i<$bestitem2_cols) {
				for($k=0; $k<($bestitem2_cols-$i); $k++) {
					$bestitem2.="<td></td>\n<td></td>\n";
				}
			}
			mysql_free_result($result);

			$bestitem2.="	</tr>\n";
			$bestitem2.="	</table>\n";
			$bestitem2.="	</td>\n";
			$bestitem2.="</tr>\n";
			$bestitem2.="</table>\n";
		} else if($bestitem_type=="3") {	####################################### 리스트형 ##################
			$colspan=4;
			$image_height=60;
			$bestitem3 = "<table border=0 cellpadding=0 cellspacing=0 width=100%>\n";
			$bestitem3.= "<col width=70></col>\n";
			$bestitem3.= "<col width=\"0\"></col>\n";
			$bestitem3.= "<col width=></col>\n";
			if($bestitem3_production=="Y" || $bestitem3_madein=="Y" || $bestitem3_model=="Y" || $bestitem3_brand=="Y") {
				$colspan++;
				$bestitem3.= "<col width=120></col>\n";
			}
			if($bestitem3_price=="Y") {
				$colspan++;
				$bestitem3.= "<col width=90></col>\n";
			}
			$bestitem3.= "<col width=120></col>\n";
			if($bestitem3_reserve=="Y") {
				$colspan++;
				$bestitem3.= "<col width=70></col>\n";
			}
			while($row=mysql_fetch_object($result)) {

				// 리뷰 평점 ( 리뷰 개수 )
				$prAvg = productReviewAverage($row->productcode);
				$prAvgMark = "";
				for( $si = 0 ; $si < $prAvg['average'] ; $si++ ) {
					$prAvgMark .= "<img src=\"/images/003/star_point1.gif\" alt=\"\" />";
				}
				for( $si = $prAvg['average']; $si < 5 ; $si++ ) {
					$prAvgMark .= "<img src=\"/images/003/star_point2.gif\" alt=\"\" />";
				}
				$prAvgMark .= "(".$prAvg['count'].")";

				// 렌탈 아이콘
				$rentalIcon = rentalIcon($row->rental);

				// 예약상품 아이콘 추가
				$row->etctype = reservationEtcType($row->reservation,$row->etctype);

				#####################상품별 회원할인율 적용 시작#######################################
				$dSql = "SELECT discount FROM tblmemberdiscount ";
				$dSql .= "WHERE productcode='".$row->productcode."' AND group_code='".$_ShopInfo->getMemgroup()."'";
				$dResult = mysql_query($dSql,get_db_conn());
				$dRow = mysql_fetch_object($dResult);
				$discountprices = $dRow->discount;
				if($discountprices>0){
					if($discountprices < 1){
						$memberprice = $row->sellprice - round($row->sellprice*$discountprices);
					}else{
						$memberprice = $row->sellprice - $discountprices;
					}
					$memberprice = number_format($memberprice);
					$strikeStart = "<strike>";
					$strikeEnd = "</strike>";
				}
				#####################상품별 회원할인율 적용 끝 #######################################

				if($i>0) {
					$bestitem3.="<tr><td colspan=".$colspan." ";
					if(eregi("#prlist_colline",$body)) {
						$bestitem3.= "id=prlist_colline></td></tr>\n";
					} else {
						$bestitem3.= "height=1><table border=0 cellpadding=0 cellspacing=0 height=1 style=\"table-layout:fixed\"><tr><td height=1 style=\"border:1 dotted #DDDDDD\"><img width=1 height=0></td></tr></table></td></tr>\n";
					}
				}
				$bestitem3.= "<tr height=".$image_height." id=\"B".$row->productcode."\" onmouseover=\"quickfun_show(this,'B".$row->productcode."','','row')\" onmouseout=\"quickfun_show(this,'B".$row->productcode."','none')\">\n";
				$bestitem3.= "	<td align=center>";
				if (strlen($row->tinyimage)>0 && file_exists($Dir.DataDir."shopimages/product/".$row->tinyimage)==true) {
					$bestitem3.= "<A HREF=\"".$Dir.FrontDir."productdetail.php?productcode=".$row->productcode.$add_query."\" onmouseover=\"window.status='상품상세조회';return true;\" onmouseout=\"window.status='';return true;\"><img src=\"".$Dir.DataDir."shopimages/product/".urlencode($row->tinyimage)."\" border=0 ";
					$width = getimagesize($Dir.DataDir."shopimages/product/".$row->tinyimage);
					if ($width[0]>=$width[1] && $width[0]>=60) $bestitem3.= "width=60 ";
					else if ($width[1]>=60) $bestitem3.= "height=60 ";
				} else {
					$bestitem3.= "<img src=\"".$Dir."images/no_img.gif\" height=60 border=0 align=center";
				}
				$bestitem3.= "	></A></td>\n";
				$bestitem3.="	<td style=\"position:relative;\">".($_data->ETCTYPE["QUICKTOOLS"]!="Y"?"<script>quickfun_write('".$Dir."','B','".$row->productcode."','".($row->quantity=="0"?"":"1")."','row')</script>":"")."</td>";
				$bestitem3.= "	<td style=\"padding-left:5\" style=\"word-break:break-all;\"><A HREF=\"".$Dir.FrontDir."productdetail.php?productcode=".$row->productcode.$add_query."\" onmouseover=\"window.status='상품상세조회';return true;\" onmouseout=\"window.status='';return true;\"><FONT class=\"prname\">".$rentalIcon.viewproductname($row->productname,$row->etctype,$row->selfcode,$row->addcode)."</FONT>".(strlen($row->prmsg)?'<br /><span class="prmsgArea">'.$row->prmsg.'</span>':'')."</A>";
				if ($row->quantity=="0") $bestitem3.= soldout();
				//태그관련
				if($bestitem3_tag>0 && strlen($row->tag)>0) {
					$bestitem3.="<br><img src=\"".$Dir."images/common/tag_icon.gif\" border=0 align=absmiddle><img width=2 height=0>";
					$arrtaglist=explode(",",$row->tag);
					$jj=0;
					for($ii=0;$ii<count($arrtaglist);$ii++) {
						$arrtaglist[$ii]=ereg_replace("(<|>)","",$arrtaglist[$ii]);
						if(strlen($arrtaglist[$ii])>0) {
							if($jj<$bestitem3_tag) {
								if($jj>0) $bestitem3.="<img width=2 height=0><FONT class=\"prtag\">+</FONT><img width=2 height=0>";
							} else {
								if($jj>0) $bestitem3.="<img width=2 height=0><FONT class=\"prtag\">+</FONT><img width=2 height=0>";
								break;
							}
							$bestitem3.="<a href=\"".$Dir.FrontDir."tag.php?tagname=".urlencode($arrtaglist[$ii])."\" onmouseover=\"window.status='".$arrtaglist[$ii]."';return true;\" onmouseout=\"window.status='';return true;\"><FONT class=\"prtag\">".$arrtaglist[$ii]."</FONT></a>";
							$jj++;
						}
					}
				}
				$bestitem3.= "</td>\n";
				//모델명/브랜드/제조사/원산지
				if($bestitem3_production=="Y" || $bestitem3_madein=="Y" || $bestitem3_model=="Y" || $bestitem3_brand=="Y") {
					$bestitem3.="	<td align=center style=\"word-break:break-all;\" class=\"prproduction\">";
					if(strlen($row->production)>0 || strlen($row->madein)>0 || strlen($row->model)>0 || strlen($row->brand)>0) {
						unset($addspec);
						if($bestitem3_production=="Y" && strlen($row->production)>0) {
							$addspec[]=$row->production;
						}
						if($bestitem3_madein=="Y" && strlen($row->madein)>0) {
							$addspec[]=$row->madein;
						}
						if($bestitem3_model=="Y" && strlen($row->model)>0) {
							$addspec[]=$row->model;
						}
						//if($bestitem3_brand=="Y" && strlen($row->brand)>0) {
						//	$addspec[]=$row->brand;
						//}
						$bestitem3.= implode("/", $addspec);
					}
					$bestitem3.="	</td>\n";
				}
				if($bestitem3_price=="Y") {
					$bestitem3.= "	<td align=center style=\"word-break:break-all;\" class=\"prconsumerprice\"><img src=\"".$Dir."images/common/won_icon2.gif\" border=0 align=absmiddle> <strike>".number_format($row->consumerprice)."</strike>원</td>\n";
				}
				$bestitem3.= "	<td align=center style=\"word-break:break-all;\" class=\"prprice\">";
				if($dicker=dickerview($row->etctype,number_format($row->sellprice)."원",1)) {
					$bestitem3.= $dicker;
				} else if(strlen($_data->proption_price)==0) {
					$bestitem3.= "<img src=\"".$Dir."images/common/won_icon.gif\" border=0 align=absmiddle> ".$strikeStart.number_format($row->sellprice)."원".$strikeEnd;
					if (strlen($row->option_price)!=0) $bestitem3.= "(기본가)";
				} else {
					$bestitem3.="<img src=\"".$Dir."images/common/won_icon.gif\" border=0 align=absmiddle> ";
					if (strlen($row->option_price)==0) $bestitem3.= $strikeStart.number_format($row->sellprice)."원".$strikeEnd;
					else $bestitem3.= ereg_replace("\[PRICE\]",number_format($row->sellprice),$_data->proption_price);
				}

				//회원할인가 적용
				if($discountprices>0){
					$bestitem3 .= "<br /><img src=\"".$Dir."images/common/memsale_icon.gif\" style=\"position:relative; top:0.1em;\" alt=\"\" /> ".$memberprice."원";
				}

				$bestitem3.= "	</td>\n";
				if($bestitem3_reserve=="Y") {
					$reserveconv=getReserveConversion($row->reserve,$row->reservetype,$row->sellprice,"Y");
					$bestitem3.= "	<td align=center style=\"word-break:break-all;\" class=prreserve><img src=\"".$Dir."images/common/reserve_icon.gif\" border=0 align=absmiddle> ".number_format($reserveconv)."원</td>\n";
				}
				$bestitem3.= "</tr>\n";
				$i++;
			}
			$bestitem3.= "</table>\n";
		}
	}
}

################ 추천상품 ###############
$hotitem1=""; $hotitem2=""; $hotitem3="";
if(preg_match("/^(1|2|3)$/",$hotitem_type)) {
	$sql = "SELECT special_list FROM tblspecialcode WHERE code='".$code."' AND special='3' ";
	$result=mysql_query($sql,get_db_conn());
	$sp_prcode="";
	if($row=mysql_fetch_object($result)) {
		$sp_prcode=ereg_replace(',','\',\'',$row->special_list);
	}
	mysql_free_result($result);

	$hotitem_tab="";
	if(strlen($sp_prcode)>0) {

		//카테고리 신규상품 탭출력
		$hotitem_tab.="<li><a href='#'><img src='/data/design/img/sub/tab_prsection3.gif' asrc='/data/design/img/sub/tab_prsection3_on.gif' border='0' alt='' /></a></li>";

		$sql = "SELECT a.* FROM tblproduct AS a ";
		$sql.= "LEFT OUTER JOIN tblproductgroupcode b ON a.productcode=b.productcode ";
		$sql.= "LEFT OUTER JOIN rent_product_option opt ON a.pridx=opt.pridx ";
		$sql.= "WHERE a.productcode IN ('".$sp_prcode."') AND a.display='Y' ";
		$sql.= "AND (a.group_check='N' OR b.group_code='".$_ShopInfo->getMemgroup()."') ";
		if(strlen($not_qry)>0) {
			$sql.= $not_qry." ";
		}
//		$sql.= $search_sql." "; //search
		$sql.= "ORDER BY FIELD(a.productcode,'".$sp_prcode."') ";
		$sql.= "LIMIT ".$hotitem_product_num;
		$result=mysql_query($sql,get_db_conn());

		$i=0;
		if($hotitem_type=="1") {	####################################### 이미지A형 ##########################
			$innerpub = file_get_contents($Dir.'newUI/categoryspecial.html');
			
			$pos = strlen($innerpub);
			if(false !== $pos = strpos($innerpub,'<!-- items -->')){
				if(false === $epos = strpos($innerpub,'<!-- /items -->')) $epos = strlen($innerpub);			
				$conts['items'] = substr($innerpub,$pos+strlen('<!-- items -->'),$epos-$pos-strlen('<!-- items -->'));
			}
			$conts['head'] = substr($innerpub,0,$pos);
			$conts['bott'] = substr($innerpub,$epos);
			$conts['cont'] = '';
			$conts = str_replace('__ID__','TOP_HOT',$conts);
			//$conts = str_replace('__ID__','',$conts);
			
			if(mysql_num_rows($result)){
				$i=0;
				while($row=mysql_fetch_assoc($result)) {
					$itemtxt = $conts['items'];	
					$row = solvResultforNewUi($row);	
					$row['listfinal'] = (++$i%$hotitem1_cols==0)?'endItem':'';
					foreach($row as $k=>$v){
						$itemtxt = str_replace('product.'.$k,$v,$itemtxt);
					}
					$conts['cont'] .= $itemtxt;
				}
			}
			$hotitem1 = $conts['head'].$conts['cont'].$conts['bott'];
		} else if($hotitem_type=="2") {	####################################### 이미지B형 ######################
			$hotitem2.="<table border=0 cellpadding=0 cellspacing=0 width=100% style=\"table-layout:fixed\">\n";
			$hotitem2.="<tr>\n";
			$hotitem2.="	<td>\n";
			$hotitem2.="	<table border=0 cellpadding=0 cellspacing=0 width=100%>\n";
			for($j=0;$j<$hotitem2_cols;$j++) {
				if($j>0) $hotitem2.= "<col width=10></col>\n";
				$hotitem2.= "<col width=".floor(100/$hotitem2_cols)."%></col>\n";
			}
			$hotitem2.="	<tr>\n";

			while($row=mysql_fetch_object($result)) {

				// 리뷰 평점 ( 리뷰 개수 )
				$prAvg = productReviewAverage($row->productcode);
				$prAvgMark = "";
				for( $si = 0 ; $si < $prAvg['average'] ; $si++ ) {
					$prAvgMark .= "<img src=\"/images/003/star_point1.gif\" alt=\"\" />";
				}
				for( $si = $prAvg['average']; $si < 5 ; $si++ ) {
					$prAvgMark .= "<img src=\"/images/003/star_point2.gif\" alt=\"\" />";
				}
				$prAvgMark .= "(".$prAvg['count'].")";

				// 렌탈 아이콘
				$rentalIcon = rentalIcon($row->rental);

				// 예약상품 아이콘 추가
				$row->etctype = reservationEtcType($row->reservation,$row->etctype);

				// 할인율 표시
				$discountRate = ( $row->discountRate > 0 ) ? "<strong>".$row->discountRate."</strong>%↓" : "";

				#####################상품별 회원할인율 적용 시작#######################################
				$strikeStart = '';
				$strikeEnd = '';
				$memberprice = 0;
				$dSql = "SELECT * FROM tblmemberdiscount ";
				$dSql .= "WHERE productcode='".$row->productcode."' AND group_code='".$_ShopInfo->getMemgroup()."'";
				$dResult = mysql_query($dSql,get_db_conn());
				$dRow = mysql_fetch_object($dResult);
				$discountprices = $dRow->discount;
				$discountYN = $dRow->discountYN;
				if($discountprices>0 && $discountYN == 'Y'){
					if($discountprices < 1){
						$memberprice = $row->sellprice - round($row->sellprice*$discountprices);
					}else{
						$memberprice = $row->sellprice - $discountprices;
					}
					$memberprice = number_format($memberprice);
					$strikeStart = "<strike>";
					$strikeEnd = "</strike>";
				}
				#####################상품별 회원할인율 적용 끝 #######################################

				if ($i>0 && $i%$hotitem2_cols==0) {
					if($hotitem2_colline=="Y") {
						$hotitem2.="<tr><td colspan=".$hotitem2_colnum." ";
						if(eregi("#prlist_colline",$body)) {
							$hotitem2.= "id=prlist_colline></td></tr>\n";
						} else {
							$hotitem2.= "height=1><table border=0 cellpadding=0 cellspacing=0 height=1 style=\"table-layout:fixed\"><tr><td height=1 style=\"border:1 dotted #DDDDDD\"><img width=1 height=0></td></tr></table></td></tr>\n";
						}
						$hotitem2.="<tr><td colspan=".$hotitem2_colnum." height=".$hotitem2_gan."></td></tr><tr>\n";
					} else {
						$hotitem2.="<tr>\n";
					}
				}

				$tableSize = $_data->primg_minisize;

				if ($i!=0 && $i%$hotitem2_cols!=0) {
					$hotitem2.="<td width=10 height=100% align=center nowrap>";
					if($hotitem2_rowline=="N") $hotitem2.="<img width=3 height=0>";
					else if($hotitem2_rowline=="Y") {
						$hotitem2.="<table border=0 cellpadding=0 cellspacing=0 width=1 height=100 style=\"table-layout:fixed\"><tr><td ";
						if(eregi("#prlist_rowline",$body)) {
							$hotitem2.= "id=prlist_rowline height=100></td></tr></table>\n";
						} else {
							$hotitem2.= "width=1 height=100 style=\"border:1 dotted #DDDDDD\"><img width=1 height=0></td></tr></table>\n";
						}
					} else if($hotitem2_rowline=="L") {
						$hotitem2.="<table border=0 cellpadding=0 cellspacing=0 width=1 height=100% style=\"table-layout:fixed\"><tr><td ";
						if(eregi("#prlist_rowline",$body)) {
							$hotitem2.= "id=prlist_rowline height=100%></td></tr></table>\n";
						} else {
							$hotitem2.= "width=1 height=100% style=\"border:1 dotted #DDDDDD\"><img width=1 height=0></td></tr></table>\n";
						}
					}
					$hotitem2.="</td>";
				}

				$hotitem2.="<td align=center>\n";
				$hotitem2.="<table border=0 cellpadding=0 cellspacing=0 width=100% id=\"H".$row->productcode."\" onmouseover=\"quickfun_show(this,'H".$row->productcode."','','row')\" onmouseout=\"quickfun_show(this,'H".$row->productcode."','none')\" class=\"prInfoBox2\">\n";
				$hotitem2.="<col width=\"".$tableSize."\"></col>\n";
				$hotitem2.="<col width=\"0\"></col>\n";
				$hotitem2.="<col width=></col>\n";
				$hotitem2.="<tr>\n";
				$hotitem2.="	<td align=center>";
				if (strlen($row->tinyimage)>0 && file_exists($Dir.DataDir."shopimages/product/".$row->tinyimage)==true) {
					$hotitem2.="<A HREF=\"".$Dir.FrontDir."productdetail.php?productcode=".$row->productcode.$add_query."\" onmouseover=\"window.status='상품상세조회';return true;\" onmouseout=\"window.status='';return true;\"><img src=\"".$Dir.DataDir."shopimages/product/".urlencode($row->tinyimage)."\" border=0 ";
					$width = getimagesize($Dir.DataDir."shopimages/product/".$row->tinyimage);
					if($_data->ETCTYPE["IMGSERO"]=="Y") {
						if ($width[1]>$width[0] && $width[1]>$_data->primg_minisize2) $hotitem2.="height=".$_data->primg_minisize2." ";
						else if (($width[1]>=$width[0] && $width[0]>=$hotitem2_imgsize) || $width[0]>=$hotitem2_imgsize) $hotitem2.="width=".$hotitem2_imgsize." ";
					} else {
						if ($width[0]>=$width[1] && $width[0]>=$hotitem2_imgsize) $hotitem2.="width=".$hotitem2_imgsize." ";
						else if ($width[1]>=$hotitem2_imgsize) $hotitem2.="height=".$hotitem2_imgsize." ";
					}
				} else {
					$hotitem2.="<img src=\"".$Dir."images/no_img.gif\" border=0 align=center";
				}
				$hotitem2.="></A></td>\n";
				$hotitem2.="	<td style=\"position:relative;\">".($_data->ETCTYPE["QUICKTOOLS"]!="Y"?"<script>quickfun_write('".$Dir."','H','".$row->productcode."','".($row->quantity=="0"?"":"1")."','row')</script>":"")."</td>";
				$hotitem2.="	<td style=\"padding-left:15px; word-break:break-all;\">\n";
				$hotitem2.="	<table border=0 cellpadding=0 cellspacing=0 width=100%>\n";
				$hotitem2.="	<tr>\n";
				$hotitem2.="		<td align=left valign=top style=\"word-break:break-all;\"><A HREF=\"".$Dir.FrontDir."productdetail.php?productcode=".$row->productcode.$add_query."\" onmouseover=\"window.status='상품상세조회';return true;\" onmouseout=\"window.status='';return true;\"><FONT class=\"prname\">".$rentalIcon.viewproductname($row->productname,$row->etctype,$row->selfcode,$row->addcode)."</FONT>".(strlen($row->prmsg)?'<br /><span class="prmsgArea">'.$row->prmsg.'</span>':'')."</A></td>\n";
				$hotitem2.="	</tr>\n";

				//모델명/브랜드/제조사/원산지
				if($hotitem2_production=="Y" || $hotitem2_madein=="Y" || $hotitem2_model=="Y" || $hotitem2_brand=="Y") {
					$hotitem2.="<tr>\n";
					$hotitem2.="	<td align=left valign=top style=\"word-break:break-all;\" class=\"prproduction\">";
					if(strlen($row->production)>0 || strlen($row->madein)>0 || strlen($row->model)>0 || strlen($row->brand)>0) {
						unset($addspec);
						if($hotitem2_production=="Y" && strlen($row->production)>0) {
							$addspec[]=$row->production;
						}
						if($hotitem2_madein=="Y" && strlen($row->madein)>0) {
							$addspec[]=$row->madein;
						}
						if($hotitem2_model=="Y" && strlen($row->model)>0) {
							$addspec[]=$row->model;
						}
						//if($hotitem2_brand=="Y" && strlen($row->brand)>0) {
						//	$addspec[]=$row->brand;
						//}
						$hotitem2.= implode("/", $addspec);
					}
					$hotitem2.="	</td>\n";
					$hotitem2.="</tr>\n";
				}

				//시중가 + 판매가 + 할인율 + 회원할인가
				$hotitem2.= "<tr>
									<td style=\"padding:0px 7px 7px 0px; word-break:break-all;\">
										<table border=0 cellpadding=0 cellspacing=0 width=100%>
											<tr>
												<td>
				";
				if($hotitem2_price=="Y" && $row->consumerprice>0) {	//소비자가
					//$hotitem2.="	<tr>\n";
					//$hotitem2.="		<td align=left valign=top style=\"word-break:break-all;\" class=\"prconsumerprice\"><img src=\"".$Dir."images/common/won_icon2.gif\" border=0 align=absmiddle> <s>".number_format($row->consumerprice)."원</s></td>\n";
					$hotitem2.="	<span class=\"prconsumerprice\" style=\"padding-right:2px;\"><strike>".number_format($row->consumerprice)."</strike>원</span>\n";
					//$hotitem2.="	</tr>\n";
				}

				// 회원 할인가가 있을 때 가격 class 변경
				if($discountprices > 0){
					$prpriceClass = "";
				}else{
					$prpriceClass = "prprice";
				}

				//$hotitem2.="	<tr>\n";
				//$hotitem2.="		<td align=left valign=top style=\"word-break:break-all;\" class=\"prprice\">";
				$hotitem2.="<span style=\"white-space:nowrap;\">";
				$hotitem2.=$strikeStart;
				if($dicker=dickerview($row->etctype,number_format($row->sellprice)."원",1)) {
					$hotitem2.= $dicker;
				} else if(strlen($_data->proption_price)==0) {
					//$hotitem2.= "<img src=\"".$Dir."images/common/won_icon.gif\" border=0 align=absmiddle> ".$strikeStart.number_format($row->sellprice)."원".$strikeEnd;
					$hotitem2.= "<strong class=\"".$prpriceClass."\">".number_format($row->sellprice)."</strong><strong>원</strong>";
					//if (strlen($row->option_price)!=0) $hotitem2.= "(기본가)";
				} else {
					//$hotitem2.="<img src=\"".$Dir."images/common/won_icon.gif\" border=0 align=absmiddle> ";
					if (strlen($row->option_price)==0) $hotitem2.= $strikeStart.number_format($row->sellprice)."원".$strikeEnd;
					else $hotitem2.= ereg_replace("\[PRICE\]",number_format($row->sellprice),$_data->proption_price);
				}
				$hotitem2.=$strikeEnd;
				$hotitem2.="</span>";

				if($row->discountRate > 0){
					$hotitem2.="<span class=\"discount\">".$discountRate."</span>";
				}
				$hotitem2.="
							</td>
						</tr>
					</table>
				";

				if ($row->quantity=="0") $hotitem2.= soldout();
				//$hotitem2.="		</td>\n";
				//$hotitem2.="	</tr>\n";

				//회원할인가 적용
				if($discountprices>0 && $discountYN == 'Y'){
					$hotitem2 .= "<div><span class=\"prprice\">".$memberprice."원</span> <img src=\"".$Dir."images/common/memsale_icon.gif\" align=\"absmiddle\" alt=\"\" /></div>";
				}

				$reserveconv=getReserveConversion($row->reserve,$row->reservetype,$row->sellprice,"Y");
				if($hotitem2_reserve=="Y" && $reserveconv>0) {	//적립금
					$hotitem2.="	<tr>\n";
					$hotitem2.="		<td align=left valign=top style=\"word-break:break-all;\" class=\"prreserve\"><img src=\"".$Dir."images/common/reserve_icon.gif\" border=0 align=absmiddle> ".number_format($reserveconv)."원";
					$hotitem2.="		</td>\n";
					$hotitem2.="	</tr>\n";
				}

				//태그관련
				if($hotitem2_tag>0 && strlen($row->tag)>0) {
					$hotitem2.="	<tr>\n";
					$hotitem2.="		<td align=left style=\"word-break:break-all;\" class=\"prtag\"><img src=\"".$Dir."images/common/tag_icon.gif\" border=0 align=absmiddle><img width=2 height=0>";
					$arrtaglist=explode(",",$row->tag);
					$jj=0;
					for($ii=0;$ii<count($arrtaglist);$ii++) {
						$arrtaglist[$ii]=ereg_replace("(<|>)","",$arrtaglist[$ii]);
						if(strlen($arrtaglist[$ii])>0) {
							if($jj<$hotitem2_tag) {
								if($jj>0) $hotitem2.="<img width=2 height=0>+<img width=2 height=0>";
							} else {
								if($jj>0) $hotitem2.="<img width=2 height=0>+<img width=2 height=0>";
								break;
							}
							$hotitem2.="<a href=\"".$Dir.FrontDir."tag.php?tagname=".urlencode($arrtaglist[$ii])."\" onmouseover=\"window.status='".$arrtaglist[$ii]."';return true;\" onmouseout=\"window.status='';return true;\"><FONT class=\"prtag\">".$arrtaglist[$ii]."</FONT></a>";
							$jj++;
						}
					}
					$hotitem2.="		</td>\n";
					$hotitem2.="	</tr>\n";
				}

				// 입점사 네임택
				if( nameTechUse($row->vender) ) {
					$classList = array();
					$classResult=mysql_query("SELECT * FROM `tblVenderClassType` ",get_db_conn());
					while($classRow=mysql_fetch_object($classResult)) {
						$classList[$classRow->idx] = $classRow->name;
					}
					$v_info = mysql_fetch_assoc ( mysql_query( "SELECT * FROM `tblvenderinfo` WHERE `vender`=".$row->vender." LIMIT 1;" ,get_db_conn()) );

					// 네임텍 출력
					$hotitem2.="
						<tr>
							<td>
								<div class=\"nameTagBox2\"><span class=\"name\">".$v_info['com_name']."</span> <span class=\"owner\">(".$v_info['com_owner'].")</span></div>
								<div><a href=\"javascript:GoMinishop('/minishop.php?storeid=".$v_info['id']."')\"><img src=\"/images/common/icon_vender_go.gif\" border=\"0\" align=\"absmiddle\" alt=\"전체상품보기\" /></a></div>
							</td>
						</tr>
					";
				}

				$hotitem2.="	</table>\n";
				$hotitem2.="	</td>\n";
				$hotitem2.="</tr>\n";
				$hotitem2.="</table>\n";
				$hotitem2.="</td>\n";
				$i++;

				if ($i==$hotitem2_product_num) break;
				if ($i%$hotitem2_cols==0) {
					$hotitem2.="</tr><tr><td colspan=".$hotitem2_colnum." height=".$hotitem2_gan."></td></tr>\n";
				}
			}
			if($i>0 && $i<$hotitem2_cols) {
				for($k=0; $k<($hotitem2_cols-$i); $k++) {
					$hotitem2.="<td></td>\n<td></td>\n";
				}
			}
			mysql_free_result($result);

			$hotitem2.="	</tr>\n";
			$hotitem2.="	</table>\n";
			$hotitem2.="	</td>\n";
			$hotitem2.="</tr>\n";
			$hotitem2.="</table>\n";
		} else if($hotitem_type=="3") {	####################################### 리스트형 #######################
			$colspan=4;
			$image_height=60;
			$hotitem3 = "<table border=0 cellpadding=0 cellspacing=0 width=100%>\n";
			$hotitem3.= "<col width=70></col>\n";
			$hotitem3.= "<col width=\"0\"></col>\n";
			$hotitem3.= "<col width=></col>\n";
			if($hotitem3_production=="Y" || $hotitem3_madein=="Y" || $hotitem3_model=="Y" || $hotitem3_brand=="Y") {
				$colspan++;
				$hotitem3.= "<col width=120></col>\n";
			}
			if($hotitem3_price=="Y") {
				$colspan++;
				$hotitem3.= "<col width=90></col>\n";
			}
			$hotitem3.= "<col width=120></col>\n";
			if($hotitem3_reserve=="Y") {
				$colspan++;
				$hotitem3.= "<col width=70></col>\n";
			}
			while($row=mysql_fetch_object($result)) {

				// 리뷰 평점 ( 리뷰 개수 )
				$prAvg = productReviewAverage($row->productcode);
				$prAvgMark = "";
				for( $si = 0 ; $si < $prAvg['average'] ; $si++ ) {
					$prAvgMark .= "<img src=\"/images/003/star_point1.gif\" alt=\"\" />";
				}
				for( $si = $prAvg['average']; $si < 5 ; $si++ ) {
					$prAvgMark .= "<img src=\"/images/003/star_point2.gif\" alt=\"\" />";
				}
				$prAvgMark .= "(".$prAvg['count'].")";

				// 렌탈 아이콘
				$rentalIcon = rentalIcon($row->rental);

				// 예약상품 아이콘 추가
				$row->etctype = reservationEtcType($row->reservation,$row->etctype);

				#####################상품별 회원할인율 적용 시작#######################################
				$dSql = "SELECT discount FROM tblmemberdiscount ";
				$dSql .= "WHERE productcode='".$row->productcode."' AND group_code='".$_ShopInfo->getMemgroup()."'";
				$dResult = mysql_query($dSql,get_db_conn());
				$dRow = mysql_fetch_object($dResult);
				$discountprices = $dRow->discount;
				if($discountprices>0){
					if($discountprices < 1){
						$memberprice = $row->sellprice - round($row->sellprice*$discountprices);
					}else{
						$memberprice = $row->sellprice - $discountprices;
					}
					$memberprice = number_format($memberprice);
					$strikeStart = "<strike>";
					$strikeEnd = "</strike>";
				}
				#####################상품별 회원할인율 적용 끝 #######################################

				if($i>0) {
					$hotitem3.="<tr><td colspan=".$colspan." ";
					if(eregi("#prlist_colline",$body)) {
						$hotitem3.= "id=prlist_colline></td></tr>\n";
					} else {
						$hotitem3.= "height=1><table border=0 cellpadding=0 cellspacing=0 height=1 style=\"table-layout:fixed\"><tr><td height=1 style=\"border:1 dotted #DDDDDD\"><img width=1 height=0></td></tr></table></td></tr>\n";
					}
				}
				$hotitem3.= "<tr height=".$image_height." id=\"H".$row->productcode."\" onmouseover=\"quickfun_show(this,'H".$row->productcode."','','row')\" onmouseout=\"quickfun_show(this,'H".$row->productcode."','none')\">\n";
				$hotitem3.= "	<td align=center>";
				if (strlen($row->tinyimage)>0 && file_exists($Dir.DataDir."shopimages/product/".$row->tinyimage)==true) {
					$hotitem3.= "<A HREF=\"".$Dir.FrontDir."productdetail.php?productcode=".$row->productcode.$add_query."\" onmouseover=\"window.status='상품상세조회';return true;\" onmouseout=\"window.status='';return true;\"><img src=\"".$Dir.DataDir."shopimages/product/".urlencode($row->tinyimage)."\" border=0 ";
					$width = getimagesize($Dir.DataDir."shopimages/product/".$row->tinyimage);
					if ($width[0]>=$width[1] && $width[0]>=60) $hotitem3.= "width=60 ";
					else if ($width[1]>=60) $hotitem3.= "height=60 ";
				} else {
					$hotitem3.= "<img src=\"".$Dir."images/no_img.gif\" height=60 border=0 align=center";
				}
				$hotitem3.= "	></A></td>\n";
				$hotitem3.="	<td style=\"position:relative;\">".($_data->ETCTYPE["QUICKTOOLS"]!="Y"?"<script>quickfun_write('".$Dir."','H','".$row->productcode."','".($row->quantity=="0"?"":"1")."','row')</script>":"")."</td>";
				$hotitem3.= "	<td style=\"padding-left:5\" style=\"word-break:break-all;\"><A HREF=\"".$Dir.FrontDir."productdetail.php?productcode=".$row->productcode.$add_query."\" onmouseover=\"window.status='상품상세조회';return true;\" onmouseout=\"window.status='';return true;\"><FONT class=\"prname\">".$rentalIcon.viewproductname($row->productname,$row->etctype,$row->selfcode,$row->addcode)."</FONT>".(strlen($row->prmsg)?'<br /><span class="prmsgArea">'.$row->prmsg.'</span>':'')."</A>";
				if ($row->quantity=="0") $hotitem3.= soldout();
				//태그관련
				if($hotitem3_tag>0 && strlen($row->tag)>0) {
					$hotitem3.="<br><img src=\"".$Dir."images/common/tag_icon.gif\" border=0 align=absmiddle><img width=2 height=0>";
					$arrtaglist=explode(",",$row->tag);
					$jj=0;
					for($ii=0;$ii<count($arrtaglist);$ii++) {
						$arrtaglist[$ii]=ereg_replace("(<|>)","",$arrtaglist[$ii]);
						if(strlen($arrtaglist[$ii])>0) {
							if($jj<$hotitem3_tag) {
								if($jj>0) $hotitem3.="<img width=2 height=0><FONT class=\"prtag\">+</FONT><img width=2 height=0>";
							} else {
								if($jj>0) $hotitem3.="<img width=2 height=0><FONT class=\"prtag\">+</FONT><img width=2 height=0>";
								break;
							}
							$hotitem3.="<a href=\"".$Dir.FrontDir."tag.php?tagname=".urlencode($arrtaglist[$ii])."\" onmouseover=\"window.status='".$arrtaglist[$ii]."';return true;\" onmouseout=\"window.status='';return true;\"><FONT class=\"prtag\">".$arrtaglist[$ii]."</FONT></a>";
							$jj++;
						}
					}
				}
				$hotitem3.= "</td>\n";
				//모델명/브랜드/제조사/원산지
				if($hotitem3_production=="Y" || $hotitem3_madein=="Y" || $hotitem3_model=="Y" || $hotitem3_brand=="Y") {
					$hotitem3.="	<td align=center style=\"word-break:break-all;\" class=\"prproduction\">";
					if(strlen($row->production)>0 || strlen($row->madein)>0 || strlen($row->model)>0 || strlen($row->brand)>0) {
						unset($addspec);
						if($hotitem3_production=="Y" && strlen($row->production)>0) {
							$addspec[]=$row->production;
						}
						if($hotitem3_madein=="Y" && strlen($row->madein)>0) {
							$addspec[]=$row->madein;
						}
						if($hotitem3_model=="Y" && strlen($row->model)>0) {
							$addspec[]=$row->model;
						}
						//if($hotitem3_brand=="Y" && strlen($row->brand)>0) {
						//	$addspec[]=$row->brand;
						//}
						$hotitem3.= implode("/", $addspec);
					}
					$hotitem3.="	</td>\n";
				}
				if($hotitem3_price=="Y") {
					$hotitem3.= "	<td align=center style=\"word-break:break-all;\" class=\"prconsumerprice\"><img src=\"".$Dir."images/common/won_icon2.gif\" border=0 align=absmiddle> <strike>".number_format($row->consumerprice)."</strike>원</td>\n";
				}
				$hotitem3.= "	<td align=center style=\"word-break:break-all;\" class=\"prprice\">";
				if($dicker=dickerview($row->etctype,number_format($row->sellprice)."원",1)) {
					$hotitem3.= $dicker;
				} else if(strlen($_data->proption_price)==0) {
					$hotitem3.= "<img src=\"".$Dir."images/common/won_icon.gif\" border=0 align=absmiddle> ".$strikeStart.number_format($row->sellprice)."원".$strikeEnd;
					if (strlen($row->option_price)!=0) $hotitem3.= "(기본가)";
				} else {
					$hotitem3.="<img src=\"".$Dir."images/common/won_icon.gif\" border=0 align=absmiddle> ";
					if (strlen($row->option_price)==0) $hotitem3.= $strikeStart.number_format($row->sellprice)."원".$strikeEnd;
					else $hotitem3.= ereg_replace("\[PRICE\]",number_format($row->sellprice),$_data->proption_price);
				}

				//회원할인가 적용
				if($discountprices>0){
					$hotitem3 .= "<br /><img src=\"".$Dir."images/common/memsale_icon.gif\" style=\"position:relative; top:0.1em;\" alt=\"\" /> ".$memberprice."원";
				}

				$hotitem3.= "	</td>\n";
				if($hotitem3_reserve=="Y") {
					$reserveconv=getReserveConversion($row->reserve,$row->reservetype,$row->sellprice,"Y");
					$hotitem3.= "	<td align=center style=\"word-break:break-all;\" class=prreserve><img src=\"".$Dir."images/common/reserve_icon.gif\" border=0 align=absmiddle> ".number_format($reserveconv)."원</td>\n";
				}
				$hotitem3.= "</tr>\n";
				$i++;
			}
			$hotitem3.= "</table>\n";
		}
	}
}


//검색키워드
if($search_keyword) {
	$arr_search_keyword = explode(',',$search_keyword);

	$q = "";
	$q_start = $q_end = "";
	for($i=0; $i<sizeof($arr_search_keyword); $i++) {
		//$arr_search_keyword[$i] = str_replace(':','',$arr_search_keyword[$i]);

		$arr_search_kw[$i] = explode(':',$arr_search_keyword[$i]);
		
		if($arr_search_kw[$i][0]=="brand" || $arr_search_kw[$i][0]=="vender" || $arr_search_kw[$i][0]=="location" || $arr_search_kw[$i][0]=="price" || $arr_search_kw[$i][0]=="deli"){ 
			if($i > 0) { 
				//같은 그룹인 경우 or 다른 그룹인 경우 and
				if($arr_search_kw[$i-1][0]==$arr_search_kw[$i][0]){
					$q = $q." OR ";
				}else{
					$q = $q." AND ";
				}
			}
		}else{
			if($i > 0) { 
				//같은 그룹인 경우 or 다른 그룹인 경우 and
				if($arr_search_kw[$i-1][1]==$arr_search_kw[$i][1]){
					$q = $q." OR ";
				}else{
					$q = $q." AND ";
				}
			}
		}

		if($arr_search_kw[$i][0]=="brand"){
			$q = $q."(a.brand LIKE '%".$arr_search_kw[$i][1]."%')";
		}else if($arr_search_kw[$i][0]=="vender"){
			$q = $q."(a.vender = '".$arr_search_kw[$i][1]."')";
		}else if($arr_search_kw[$i][0]=="location"){
			$q = $q."(rp.location LIKE '%".$arr_search_kw[$i][1]."%')";
		}else if($arr_search_kw[$i][0]=="price"){
			$arr_price = explode("-",$arr_search_kw[$i][1]);
			$q = $q."(opt.nomalPrice BETWEEN ".$arr_price[0]." AND ".$arr_price[1].")";
		}else if($arr_search_kw[$i][0]=="deli"){
			$q = $q."(a.deli_type LIKE '%".$arr_search_kw[$i][1]."%')";
		}else{
			$q = $q."(a.catekeyword LIKE '%".$arr_search_keyword[$i]."%')";
		}
		
		//OR사이에 있는 불필요한 () 없애기
		if($arr_search_kw[$i][0]=="brand" || $arr_search_kw[$i][0]=="vender" || $arr_search_kw[$i][0]=="location" || $arr_search_kw[$i][0]=="price" || $arr_search_kw[$i][0]=="deli"){
			if($arr_search_kw[$i-1][0]==$arr_search_kw[$i][0]){
				$q = str_replace(") OR (", " OR ",$q);
			}
		}else{
			if($arr_search_kw[$i-1][1]==$arr_search_kw[$i][1]){
				$q = str_replace(") OR (", " OR ",$q);
			}
		}
		
	}
	$search_sql.= "AND (".$q.") ";
}

//price
if($search_price_s || $search_price_e) {
	if($search_price_s && $search_price_e) {
		$search_sql.= "AND a.sellprice BETWEEN ".$search_price_s." AND ".$search_price_e." ";
	}
	else if($search_price_s && !$search_price_e) {
		$search_sql.= "AND a.sellprice >= ".$search_price_s." ";
	}
	else if(!$search_price_s && $search_price_e) {
		$search_sql.= "AND a.sellprice <= ".$search_price_e." ";
	}
}
//검색키워드start
$search_codeA = substr($likecode,0,3);
$skwlist = "<div class=\"product_opt active\">";

if($_REQUEST[search_keyword]) { $_active = 'active'; }
else { $_active = ''; }
$skwlist .= '<h1 class="'.$_active.'">Search More<span><img src="/images/tri.png"></span></h1>';
$skwlist .= '<ul class="search_more">';

//브랜드
$bsql = "SELECT bridx,brandname FROM tblproductbrand b LEFT JOIN tblproduct prd ON b.bridx=prd.brand ";
$bsql.= "WHERE productcode LIKE '".$likecode."%' GROUP BY bridx";
$bresult = mysql_query($bsql,get_db_conn());
$bnum = mysql_num_rows($bresult);
if($bnum>0){
	$skwlist .= '	<li class="keyword">';
	$skwlist .= '		<p class="keywordTitle">브랜드';
	if($bnum>10){
		$skwlist .= ' <span id="icon_more" title="brand" style="cursor:pointer">+</span>';
	}
	$skwlist .= '</p>';
	$skwlist .= '		<ul class="search_keyword">';		
	$i=0;
	while ($bRow=mysql_fetch_object($bresult)) {

		$skwlist .= ' <li><label for="search_keyword_brand'.$i.'"><input type="checkbox" id="search_keyword_brand'.$i.'" name="search_keyword[]" value="brand:'.$bRow->bridx.'"';
		if(strpos($search_keyword,'brand:'.$bRow->bridx)>-1){$skwlist .= ' checked'; }
		$skwlist .= ' /><div><span>'.$bRow->brandname.'</span></div></label></li>';

		$i++;
		if($i%10==0){
			$skwlist .= '</ul></li>';
			$skwlist .=' <li class="keyword list_more brand" style="display:none"><p class="keywordTitle">&nbsp;</p><ul class="search_keyword">';
		}
	}
	$skwlist .= '		</ul>';
	$skwlist .= '	</li>';
}

//벤더목록
$vsql = "SELECT v.vender,v.com_name FROM tblvenderinfo v LEFT JOIN tblproduct prd ON prd.vender=v.vender ";
$vsql.= "WHERE prd.productcode LIKE '".$likecode."%' ";
$vsql.= "GROUP BY vender ";
$vresult = mysql_query($vsql,get_db_conn());
$vnum = mysql_num_rows($vresult);
if($vnum>0){
	$skwlist .= '	<li class="keyword">';
	$skwlist .= '		<p class="keywordTitle">대여사';
	if($vnum>10){
		$skwlist .= ' <span id="icon_more" title="vender" style="cursor:pointer">+</span>';
	}
	$skwlist .= '</p>';
	$skwlist .= '		<ul class="search_keyword">';
	$i=0;
	while ($vRow=mysql_fetch_object($vresult)) {
		$skwlist .= ' <li><label for="search_keyword_vender'.$i.'"><input type="checkbox" id="search_keyword_vender'.$i.'" name="search_keyword[]" value="vender:'.$vRow->vender.'"';
		if(strpos($search_keyword,'vender:'.$vRow->vender)>-1){$skwlist .= ' checked'; }
		$skwlist .= ' /><div><span>'.$vRow->com_name.'</span></div></label></li>';

		$i++;
		if($i%10==0){
			$skwlist .= '</ul></li>';
			$skwlist .=' <li class="keyword list_more vender" style="display:none"><p class="keywordTitle">&nbsp;</p><ul class="search_keyword">';
		}

	}
	$skwlist .= '		</ul>';
	$skwlist .= '	</li>';
}

//소재지
$lsql = "SELECT lc.location,lc.title FROM rent_location lc LEFT JOIN rent_product rp ON lc.location=rp.location ";
$lsql.= "LEFT JOIN tblproduct prd ON rp.pridx=prd.pridx ";
$lsql.= "WHERE prd.productcode LIKE '".$likecode."%' ";
$lsql.= "AND lc.display='1' ";
$lsql.= "GROUP BY location ";
$lresult = mysql_query($lsql,get_db_conn());
$lnum = mysql_num_rows($lresult);

if($lnum>0){
	$skwlist .= '	<li class="keyword">';
	$skwlist .= '		<p class="keywordTitle">소재지';
	if($lnum>10){
		$skwlist .= ' <span id="icon_more" title="location" style="cursor:pointer">+</span>';
	}
	$skwlist .= '</p>';
	$skwlist .= '		<ul class="search_keyword">';
	$i=0;
	while ($lRow=mysql_fetch_object($lresult)) {

		$skwlist .= '		<li><label for="search_keyword_location'.$i.'"><input type="checkbox" id="search_keyword_location'.$i.'" name="search_keyword[]" value="location:'.$lRow->location.'"';
				if(strpos($search_keyword,'location:'.$lRow->location)>-1){$skwlist .= ' checked'; }
				$skwlist .= ' /><div><span>'.$lRow->title.'</span></div></label></li>';
		$i++;
		if($i%10==0){
			$skwlist .= '</ul></li>';
			$skwlist .=' <li class="keyword list_more location" style="display:none"><p class="keywordTitle">&nbsp;</p><ul class="search_keyword">';
		}

	}
	$skwlist .= '		</ul>';
	$skwlist .= '	</li>';
}

//가격
$psql = "SELECT ROUND(MAX(nomalPrice),-4) as maxPrice FROM rent_product_option opt LEFT JOIN tblproduct prd ON opt.pridx=prd.pridx ";
$psql.= "WHERE productcode LIKE '".$likecode."%' ";
$presult = mysql_query($psql,get_db_conn());
$pRow=mysql_fetch_object($presult);
$price1 = $pRow->maxPrice*0.2;
$price2 = $pRow->maxPrice*0.5;
$price3 = $pRow->maxPrice*0.8;

$price_list = array("0-".$price1,$price1."-".$price2,$price2."-".$price3,$price3."-".$pRow->maxPrice);
$pricetext_list = array(number_format($price1)."원 이하",number_format($price1)."원 ~ ".number_format($price2)."원",number_format($price2)."원 - ".number_format($price3)."원",number_format($price3)."원 이상");

$skwlist .= '	<li class="keyword">';
$skwlist .= '		<p class="keywordTitle">가격</p>';
$skwlist .= '		<ul class="search_keyword">';

for ($i=0;$i<count($price_list);$i++) {
	$skwlist .= '			<li><label for="search_keyword_price'.$i.'"><input type="checkbox" id="search_keyword_price'.$i.'" name="search_keyword[]" value="price:'.$price_list[$i].'"';
				if(strpos($search_keyword,'price:'.$price_list[$i])>-1){$skwlist .= ' checked'; }
				$skwlist .= ' /><div><span>'.$pricetext_list[$i].'</span></div></label></li>';
}
$skwlist .= '		<li><span id="inputSpan"><input type="text" id="search_price_s" name="search_price_s" style="border:0" value="'.$search_price_s.'">원</span> ~ ';
$skwlist .= '		<span id="inputSpan"><input type="text" id="search_price_e" name="search_price_e" style="border:0" value="'.$search_price_e.'">원</span> ';
$skwlist .= '		<img src="/data/design/img/sub/icon_search.png" class="btn_search_price" align="absmiddle"></li>';

$skwlist .= '		</ul>';
$skwlist .= '	</li>';

$deli_list = array("택배","퀵서비스","방문수령","용달","장소예약");
$skwlist .= '	<li class="keyword">';
$skwlist .= '		<p class="keywordTitle">배송</p>';
$skwlist .= '		<ul class="search_keyword">';

for ($i=0;$i<count($deli_list);$i++) {
	$skwlist .= '		<li><label for="search_keyword_deli'.$i.'"><input type="checkbox" id="search_keyword_deli'.$i.'" name="search_keyword[]" value="deli:'.$deli_list[$i].'"';
			if(strpos($search_keyword,'deli:'.$deli_list[$i])>-1){$skwlist .= ' checked'; }
			$skwlist .= ' /><div><span>'.$deli_list[$i].'</span></div></label></li>';
}
$skwlist .= '		</ul>';
$skwlist .= '	</li>';


$ksql = "SELECT kw.kg_idx,kwgroup,use_yn ";
$ksql.= "FROM tblkeyword kw LEFT JOIN tblkwgroup kg ON kw.kg_idx=kg.kg_idx ";
//$ksql.= "WHERE code LIKE '".$search_codeA."%' AND productcode='' AND use_yn='Y' GROUP BY kw.kg_idx";
$ksql.= "WHERE code LIKE '".$search_codeA."%' AND use_yn='Y' GROUP BY kw.kg_idx";
$kres = mysql_query($ksql,get_db_conn());
while($krow = mysql_fetch_object($kres)){
	
	//등록된 검색키워드분류가 있는지 확인
	$ksql_ = "SELECT count(productcode) pcnt FROM tblproduct ";
	$ksql_.= "WHERE productcode LIKE '".$likecode."%' AND catekeyword LIKE '%:".$krow->kwgroup.":%' AND display='Y' ";
	//$ksql_.= "WHERE productcode LIKE '".$likecode."%' AND catekeyword LIKE '%||".$krow->kwgroup.":%' AND display='Y' ";
	$kres_ = mysql_query($ksql_,get_db_conn());
	$krow_ = mysql_fetch_object($kres_);

	if($krow_->pcnt>0){

		$skwlist .= '	<li class="keyword">';
		$skwlist .= '		<p class="keywordTitle">'.$krow->kwgroup.'</p>';
		$skwlist .= '		<ul class="search_keyword">';
		
		$ksql2 = "SELECT keyword FROM tblkeyword ";
		$ksql2.= "WHERE code LIKE '".$search_codeA."%' AND kg_idx='".$krow->kg_idx."' AND use_yn='Y' GROUP BY keyword ORDER BY kw_idx";
		$kres2 = mysql_query($ksql2,get_db_conn());
		
		$kw_cnt=1;
		while($krow2 = mysql_fetch_object($kres2)){

			$ksql2_ = "SELECT count(productcode) pcnt FROM tblproduct ";
			$ksql2_.= "WHERE productcode LIKE '".$likecode."%' AND catekeyword LIKE '%".$krow->kwgroup.":".$krow2->keyword."%' AND display='Y' ";
			//$ksql2_.= "WHERE productcode LIKE '".$likecode."%' AND catekeyword LIKE '%".$krow->kwgroup.":".$krow2->keyword."%' AND display='Y' ";
			$kres2_ = mysql_query($ksql2_,get_db_conn());
			$krow2_ = mysql_fetch_object($kres2_);

			if($krow2_->pcnt>0){
				$skwlist .= '<li><label for="search_keyword'.$krow->kg_idx.$kw_cnt.'"><input type="checkbox" id="search_keyword'.$krow->kg_idx.$kw_cnt.'" name="search_keyword[]" value="'.$krow2->kw_idx.":".$krow->kwgroup.":".$krow2->keyword.'"';
				if(strpos($search_keyword,$krow2->kw_idx.":".$krow->kwgroup.":".$krow2->keyword)>-1){$skwlist .= ' checked'; }
				$skwlist .= ' /><div><span>'.$krow2->keyword.'</span></div></label></li>';

				//$skwlist .= '<li><label for="search_keyword'.$krow->kg_idx.$kw_cnt.'"><input type="checkbox" id="search_keyword'.$krow->kg_idx.$kw_cnt.'" name="search_keyword[]" value="'.$krow->kwgroup.":".$krow2->keyword.'"';
				//if(sizeof($search_keyword) > 1) { $skwlist .= ' checked'; }
				//$skwlist .= ' /><div><span>'.$krow2->keyword.'</span></div></label></li>';
				$kw_cnt++;
			}
		}

		$skwlist .= '		</ul>';
		$skwlist .= '	</li>';

	}

}

$skwlist .= '</ul>';
$skwlist .= '</div>';
//검색키워드end	


//상품 정렬 관련
$_date="";
$_sellcount_desc="";
$_price="";
$_price_desc="";

switch(trim($sort)){
	case "price":
		$_price="class=\"sortOn\"";
	break;

	case "price_desc":
		$_price_desc="class=\"sortOn\"";
	break;

	case "reserve_desc":
		$_reserve_desc="class=\"sortOn\"";
	break;

	case "new_desc":
		$_new="class=\"sortOn\"";
	break;

	case "review_desc":
		$_review_desc="class=\"sortOn\"";
	break;

	default:
	case "best_desc":
		$_best_desc="class=\"sortOn\"";
	break;

}
/*
if($listnum == 40) $sel40 = "selected";
if($listnum == 60) $sel60 = "selected";
if($listnum == 80) $sel80 = "selected";

$listselect = "
	<select name=\"listnum2\" onchange=\"ChangeNum(this)\">
		<option value='40' ".$sel40.">40개씩 보기</option>
		<option value='60' ".$sel60.">60개씩 보기</option>
		<option value='80' ".$sel80.">80개씩 보기</option>
	</select>
";
*/
if($listnum == 40) $sel40 = "selected";
if($listnum == 60) $sel60 = "selected";
if($listnum == 80) $sel80 = "selected";

$listselect = "<select name=\"listnum2\" onchange=\"ChangeNum(this)\">";
for($i=1;$i<4;$i++){
	$tnum = 6* (8*$i);
	$tsel = $listnum == $tnum?'selected':'';
	$listselect .= "<option value='".$tnum."' ".$tsel.">".$tnum."개씩 보기</option>";
}
$listselect .= "</select>";


//상품목록 ($prlist_type이 1:이미지A형,2:이미지B형,3:리스트형,4:공구형일 경우에만)
$prlist1=""; $prlist2=""; $prlist3=""; $prlist4="";
if(preg_match("/^(1|2|3|4)$/",$prlist_type)) {
	$sql = "SELECT COUNT(distinct a.productcode) as t_count ";
	$sql.= "FROM tblproduct AS a left join tblcategorycode as cc on cc.productcode = a.productcode ";
	$sql.= "LEFT OUTER JOIN tblproductgroupcode b ON a.productcode=b.productcode ";
	$sql.= "LEFT OUTER JOIN rent_product rp ON rp.pridx=a.pridx ";
	$sql.= "LEFT OUTER JOIN rent_product_option opt ON a.pridx=opt.pridx ";
	$sql.= $qry." ";
	$sql.= "AND (a.group_check='N' OR b.group_code='".$_ShopInfo->getMemgroup()."') ";
	if(strlen($not_qry)>0) {
		$sql.= $not_qry." ";
	}
	$sql.= $search_sql." "; //search
	$result=mysql_query($sql,get_db_conn());
	$row=mysql_fetch_object($result);
	$t_count = (int)$row->t_count;
	mysql_free_result($result);
	$pagecount = (($t_count - 1) / $setup[list_num]) + 1;

	if($t_count<=0) {
		$prlist1 = "<table border=0 cellpadding=0 cellspacing=0 width=100%><tr><td align=center valign=middle height=30>등록된 상품이 없습니다.</td></tr></table>";
		$prlist2 = "<table border=0 cellpadding=0 cellspacing=0 width=100%><tr><td align=center valign=middle height=30>등록된 상품이 없습니다.</td></tr></table>";
		$prlist3 = "<table border=0 cellpadding=0 cellspacing=0 width=100%><tr><td align=center valign=middle height=100>등록된 상품이 없습니다.</td></tr></table>";
		$prlist4 = "<table border=0 cellpadding=0 cellspacing=0 width=100%><tr><td align=center valign=middle height=50>등록된 상품이 없습니다.</td></tr></table>";
	} else {
		$tmp_sort=explode("_",$sort);
		if($tmp_sort[0]=="reserve") {
			$addsortsql=",IF(a.reservetype='N',a.reserve*1,a.reserve*a.sellprice*0.01) AS reservesort ";
		}
		$sql = "SELECT distinct a.productcode,a.pridx,a.productname,a.addcode,a.sellprice,a.quantity,a.consumerprice,a.reserve,a.reservetype,a.production, ";
		if($_cdata->sort=="date2") $sql.="IF(a.quantity<=0,'11111111111111',a.date) as date, ";
		$sql.= "a.tag, a.tinyimage, a.minimage, a.maximage, a.etctype, a.option_price, a.madein, a.model, a.brand, a.selfcode, a.prmsg, a.discountRate, a.vender, a.reservation, a.rental ";
		$sql.= $addsortsql;
		$sql.= ",(select count(num) from tblproductreview where productcode=a.productcode) totalreview ";
		$sql.= "FROM tblproduct AS a  left join tblcategorycode as cc on cc.productcode = a.productcode ";
		$sql.= "LEFT OUTER JOIN tblproductgroupcode b ON a.productcode=b.productcode ";
		$sql.= "LEFT OUTER JOIN rent_product rp ON rp.pridx=a.pridx ";
		$sql.= "LEFT OUTER JOIN rent_product_option opt ON a.pridx=opt.pridx ";
		$sql.= $qry." ";
		$sql.= "AND (a.group_check='N' OR b.group_code='".$_ShopInfo->getMemgroup()."') ";

		if(strlen($not_qry)>0) {
			$sql.= $not_qry." ";
		}
		$sql.= $search_sql." "; //search
		if($tmp_sort[0]=="production") $sql.= "ORDER BY a.production ".$tmp_sort[1]." ";
		else if($tmp_sort[0]=="name") $sql.= "ORDER BY a.productname ".$tmp_sort[1]." ";
		else if($tmp_sort[0]=="price") $sql.= "ORDER BY a.sellprice ".$tmp_sort[1]." ";
		else if($tmp_sort[0]=="reserve") $sql.= "ORDER BY reservesort ".$tmp_sort[1]." ";
		else if($tmp_sort[0]=="new") $sql.= "ORDER BY a.regdate ".$tmp_sort[1]." ";
		else if($tmp_sort[0]=="best") $sql.= "ORDER BY sellcount ".$tmp_sort[1]." ";
		else if($tmp_sort[0]=="review") $sql.= "ORDER BY totalreview ".$tmp_sort[1]." ";
		else {
			if(strlen($_cdata->sort)==0 || $_cdata->sort=="date" || $_cdata->sort=="date2") {
				if(eregi("T",$_cdata->type) && strlen($t_prcode)>0) {
					$sql.= "ORDER BY FIELD(a.productcode,'".$t_prcode."'),date DESC ";
				} else {
					$sql.= "ORDER BY date DESC ";
				}
			} else if($_cdata->sort=="productname") {
				$sql.= "ORDER BY a.productname ";
			} else if($_cdata->sort=="production") {
				$sql.= "ORDER BY a.production ";
			} else if($_cdata->sort=="price") {
				$sql.= "ORDER BY a.sellprice ";
			}
		}
		$sql.= "LIMIT " . ($setup[list_num] * ($gotopage - 1)) . ", " . $setup[list_num];

		$result=mysql_query($sql,get_db_conn());

		$i=0;
		if($prlist_type=="1") {	####################################### 이미지A형 #################################
		//	$innerpub = file_get_contents($Dir.'newUI/prlist_category.html');
			$innerpub = file_get_contents($Dir.'newUI/prlisttype2.html');
			
			$pos = strlen($innerpub);
			if(false !== $pos = strpos($innerpub,'<!-- items -->')){
				if(false === $epos = strpos($innerpub,'<!-- /items -->')) $epos = strlen($innerpub);			
				$conts['items'] = substr($innerpub,$pos+strlen('<!-- items -->'),$epos-$pos-strlen('<!-- items -->'));
			}
			$conts['head'] = substr($innerpub,0,$pos);
			$conts['bott'] = substr($innerpub,$epos);
			$conts['cont'] = '';
			$conts = str_replace('__ID__','ProductList',$conts);
			
			if(mysql_num_rows($result)){
				$i=0;
				while($row=mysql_fetch_assoc($result)) {
					$itemtxt = $conts['items'];	
					//echo $i."=".$row['productcode']."/";
					$row = solvResultforNewUi($row);	
					$row['listfinal'] = (++$i%$prlist1_cols==0)?'endItem':'';
					foreach($row as $k=>$v){
						$itemtxt = str_replace('product.'.$k,$v,$itemtxt);
					}
					$conts['cont'] .= $itemtxt;		
					if($row['listfinal'] == 'endItem') $conts['cont'] .= '<div style="clear:both; height:0px; font-size:0px; border:0px; padding:0px; margin:0px;"></div>';
				}
			}
			$prlist1 = $conts['head'].$conts['cont'].$conts['bott'];

		} else if($prlist_type=="2") {	####################################### 이미지B형 #########################
			$prlist2 = "<table border=0 cellpadding=0 cellspacing=0 width=100%>\n";
			for($j=0;$j<$prlist2_cols;$j++) {
				if($j>0) $prlist2.= "<col width=10></col>\n";
				$prlist2.= "<col width=".floor(100/$prlist2_cols)."%></col>\n";
			}
			$prlist2.= "<tr>\n";

			while($row=mysql_fetch_object($result)) {

				// 리뷰 평점 ( 리뷰 개수 )
				$prAvg = productReviewAverage($row->productcode);
				$prAvgMark = "";
				for( $si = 0 ; $si < $prAvg['average'] ; $si++ ) {
					$prAvgMark .= "<img src=\"/images/003/star_point1.gif\" alt=\"\" />";
				}
				for( $si = $prAvg['average']; $si < 5 ; $si++ ) {
					$prAvgMark .= "<img src=\"/images/003/star_point2.gif\" alt=\"\" />";
				}
				$prAvgMark .= "(".$prAvg['count'].")";

				// 렌탈 아이콘
				$rentalIcon = rentalIcon($row->rental);

				// 예약상품 아이콘 추가
				$row->etctype = reservationEtcType($row->reservation,$row->etctype);

				// 할인율 표시
				$discountRate = ( $row->discountRate > 0 ) ? "<strong>".$row->discountRate."</strong>%↓" : "";

				#####################상품별 회원할인율 적용 시작#######################################
				$strikeStart = '';
				$strikeEnd = '';
				$memberprice = 0;
				$dSql = "SELECT * FROM tblmemberdiscount ";
				$dSql .= "WHERE productcode='".$row->productcode."' AND group_code='".$_ShopInfo->getMemgroup()."'";
				$dResult = mysql_query($dSql,get_db_conn());
				$dRow = mysql_fetch_object($dResult);
				$discountprices = $dRow->discount;
				$discountYN = $dRow->discountYN;
				if($discountprices>0 && $discountYN == 'Y'){
					if($discountprices < 1){
						$memberprice = $row->sellprice - round($row->sellprice*$discountprices);
					}else{
						$memberprice = $row->sellprice - $discountprices;
					}
					$memberprice = number_format($memberprice);
					$strikeStart = "<strike>";
					$strikeEnd = "</strike>";
				}
				#####################상품별 회원할인율 적용 끝 #######################################

				$number = ($t_count-($setup[list_num] * ($gotopage-1))-$i);
				if ($i>0 && $i%$prlist2_cols==0) {
					if($prlist2_colline=="Y") {
						$prlist2.="<tr><td colspan=".$prlist2_colnum." ";
						if(eregi("#prlist_colline",$body)) {
							$prlist2.= "id=prlist_colline></td></tr>\n";
						} else {
							$prlist2.= "height=1><table border=0 cellpadding=0 cellspacing=0 height=1 style=\"table-layout:fixed\"><tr><td height=1 style=\"border:1 dotted #DDDDDD\"><img width=1 height=0></td></tr></table></td></tr>\n";
						}
						$prlist2.="<tr><td colspan=".$prlist2_colnum." height=".$prlist2_gan."></td></tr><tr>\n";
					} else {
						$prlist2.="<tr>\n";
					}
				}

				$tableSize = $_data->primg_minisize;

				if ($i!=0 && $i%$prlist2_cols!=0) {
					$prlist2.="<td width=10 height=100% align=center nowrap>";
					if($prlist2_rowline=="N") $prlist2.="<img width=3 height=0>";
					else if($prlist2_rowline=="Y") {
						$prlist2.="<table border=0 cellpadding=0 cellspacing=0 width=1 height=100 style=\"table-layout:fixed\"><tr><td ";
						if(eregi("#prlist_rowline",$body)) {
							$prlist2.= "id=prlist_rowline height=100></td></tr></table>\n";
						} else {
							$prlist2.= "width=1 height=100 style=\"border:1 dotted #DDDDDD\"><img width=1 height=0></td></tr></table>\n";
						}
					} else if($prlist2_rowline=="L") {
						$prlist2.="<table border=0 cellpadding=0 cellspacing=0 width=1 height=100% style=\"table-layout:fixed\"><tr><td ";
						if(eregi("#prlist_rowline",$body)) {
							$prlist2.= "id=prlist_rowline height=100%></td></tr></table>\n";
						} else {
							$prlist2.= "width=1 height=100% style=\"border:1 dotted #DDDDDD\"><img width=1 height=0></td></tr></table>\n";
						}
					}
					$prlist2.="</td>";
				}
				$prlist2.="<td align=center>\n";
				$prlist2.= "<table border=0 cellpadding=0 cellspacing=0 width=100% id=\"G".$row->productcode."\" onmouseover=\"quickfun_show(this,'G".$row->productcode."','','row')\" onmouseout=\"quickfun_show(this,'G".$row->productcode."','none')\" class=\"prInfoBox2\">\n";
				$prlist2.="<col width=\"".$tableSize."\"></col>\n";
				$prlist2.="<col width=\"0\"></col>\n";
				$prlist2.="<col width=\"\"></col>\n";

				$prlist2.= "<tr>\n";
				$prlist2.= "	<td align=center class=\"prImage\">";
				if (strlen($row->tinyimage)>0 && file_exists($Dir.DataDir."shopimages/product/".$row->tinyimage)==true) {
					$prlist2.= "<A HREF=\"".$Dir.FrontDir."productdetail.php?productcode=".$row->productcode.$add_query."&sort=".$sort."\" onmouseover=\"window.status='상품상세조회';return true;\" onmouseout=\"window.status='';return true;\"><img src=\"".$Dir.DataDir."shopimages/product/".urlencode($row->tinyimage)."\" border=0 ";
					$width = getimagesize($Dir.DataDir."shopimages/product/".$row->tinyimage);
					if($_data->ETCTYPE["IMGSERO"]=="Y") {
						if ($width[1]>$width[0] && $width[1]>$_data->primg_minisize2) $prlist2.= "height=".$_data->primg_minisize2." ";
						else if (($width[1]>=$width[0] && $width[0]>=$_data->primg_minisize) || $width[0]>=$_data->primg_minisize) $prlist2.= "width=".$_data->primg_minisize." ";
					} else {
						if ($width[0]>=$width[1] && $width[0]>=$_data->primg_minisize) $prlist2.= "width=".$_data->primg_minisize." ";
						else if ($width[1]>=$_data->primg_minisize) $prlist2.= "height=".$_data->primg_minisize." ";
					}
				} else {
					$prlist2.= "<img src=\"".$Dir."images/no_img.gif\" border=0 align=center";
				}
				$prlist2.= "	></A></td>\n";
				$prlist2.="	<td style=\"position:relative;\">".($_data->ETCTYPE["QUICKTOOLS"]!="Y"?"<script>quickfun_write('".$Dir."','G','".$row->productcode."','".($row->quantity=="0"?"":"1")."','row')</script>":"")."</td>";
				$prlist2.= "	<td valign=middle style=\"padding-left:15\">\n";
				$prlist2.= "	<table border=0 cellpadding=0 cellspacing=0 width=100%>\n";
				$prlist2.= "<tr>";
				$prlist2.= "	<td align=left valign=top style=\"word-break:break-all;\"><A HREF=\"".$Dir.FrontDir."productdetail.php?productcode=".$row->productcode.$add_query."&sort=".$sort."\" onmouseover=\"window.status='상품상세조회';return true;\" onmouseout=\"window.status='';return true;\"><FONT class=\"prname\">".$rentalIcon.viewproductname($row->productname,$row->etctype,$row->selfcode,$row->addcode)."</FONT>".(strlen($row->prmsg)?'<br /><span class="prmsgArea">'.$row->prmsg.'</span>':'')."</A></td>\n";
				$prlist2.= "</tr>\n";

				//모델명/브랜드/제조사/원산지
				if($prlist2_production=="Y" || $prlist2_madein=="Y" || $prlist2_model=="Y" || $prlist2_brand=="Y") {
					$prlist2.="<tr>\n";
					$prlist2.="	<td align=left valign=top style=\"word-break:break-all;\" class=\"prproduction\">";
					if(strlen($row->production)>0 || strlen($row->madein)>0 || strlen($row->model)>0 || strlen($row->brand)>0) {
						unset($addspec);
						if($prlist2_production=="Y" && strlen($row->production)>0) {
							$addspec[]=$row->production;
						}
						if($prlist2_madein=="Y" && strlen($row->madein)>0) {
							$addspec[]=$row->madein;
						}
						if($prlist2_model=="Y" && strlen($row->model)>0) {
							$addspec[]=$row->model;
						}
						//if($prlist2_brand=="Y" && strlen($row->brand)>0) {
						//	$addspec[]=$row->brand;
						//}
						$prlist2.= implode("/", $addspec);
					}
					$prlist2.="	</td>\n";
					$prlist2.="</tr>\n";
				}

				//시중가 + 판매가 + 할인율 + 회원할인가
				$prlist2.= "<tr>
									<td style=\"padding:0px 7px 7px 0px; word-break:break-all;\">
										<table border=0 cellpadding=0 cellspacing=0 width=100%>
											<tr>
												<td>
				";
				if($prlist2_price=="Y" && $row->consumerprice>0) {	//소비자가
					$prlist2.="	<span class=\"prconsumerprice\" style=\"padding-right:2px;\"><strike>".number_format($row->consumerprice)."</strike>원</span>\n";
					//$prlist2.="	<td align=left valign=top style=\"word-break:break-all;\" class=\"prconsumerprice\"><img src=\"".$Dir."images/common/won_icon2.gif\" border=0 align=absmiddle> <strike>".number_format($row->consumerprice)."</strike>원</td>\n";
				}

				// 회원 할인가가 있을 때 가격 class 변경
				if($discountprices>0){
					$prpriceClass = "";
				}else{
					$prpriceClass = "prprice";
				}

				$prlist2.="<span style=\"white-space:nowrap;\">";
				$prlist2.=$strikeStart;
				if($dicker=dickerview($row->etctype,number_format($row->sellprice)."원",1)) {
					$prlist2.= $dicker;
				} else if(strlen($_data->proption_price)==0) {
					$prlist2.= "<strong class=\"".$prpriceClass."\">".number_format($row->sellprice)."</strong><strong>원</strong>";
					//$prlist2.= "<img src=\"".$Dir."images/common/won_icon.gif\" border=0 align=absmiddle> ".$strikeStart.number_format($row->sellprice)."원".$strikeEnd;
					//if (strlen($row->option_price)!=0) $prlist2.= "(기본가)";
				} else {
					//$prlist2.= "<img src=\"".$Dir."images/common/won_icon.gif\" border=0 align=absmiddle> ";
					if (strlen($row->option_price)==0) $prlist2.= $strikeStart.number_format($row->sellprice)."원".$strikeEnd;
					else $prlist2.= ereg_replace("\[PRICE\]",number_format($row->sellprice),$_data->proption_price);
				}
				$prlist2.=$strikeEnd;
				$prlist2.="</span>";

				if($row->discountRate > 0){
					$prlist2.="<span class=\"discount\">".$discountRate."</span>";
				}
				$prlist2.="
							</td>
						</tr>
					</table>
				";

				if ($row->quantity=="0") $prlist2.= soldout();

				//회원할인가 적용
				if($discountprices>0 && $discountYN == 'Y'){
					$prlist2 .= "<div><span class=\"prprice\">".$memberprice."원</span> <img src=\"".$Dir."images/common/memsale_icon.gif\" align=\"absmiddle\" alt=\"\" /></div>";
				}

				$reserveconv=getReserveConversion($row->reserve,$row->reservetype,$row->sellprice,"Y");
				if($prlist2_reserve=="Y" && $reserveconv>0) {	//적립금
					$prlist2.="<tr>\n";
					$prlist2.="	<td align=left valign=top style=\"word-break:break-all;\" class=\"prreserve\"><img src=\"".$Dir."images/common/reserve_icon.gif\" border=0 align=absmiddle> ".number_format($reserveconv)."원";
					$prlist2.="	</td>\n";
					$prlist2.="</tr>\n";
				}
				$prlist2.= "	</td>\n";
				$prlist2.= "</tr>\n";

				//태그관련
				if($prlist2_tag>0 && strlen($row->tag)>0) {
					$prlist2.="	<tr>\n";
					$prlist2.="		<td align=left style=\"word-break:break-all;\" class=\"prtag\"><img src=\"".$Dir."images/common/tag_icon.gif\" border=0 align=absmiddle><img width=2 height=0>";
					$arrtaglist=explode(",",$row->tag);
					$jj=0;
					for($ii=0;$ii<count($arrtaglist);$ii++) {
						$arrtaglist[$ii]=ereg_replace("(<|>)","",$arrtaglist[$ii]);
						if(strlen($arrtaglist[$ii])>0) {
							if($jj<$prlist2_tag) {
								if($jj>0) $prlist2.="<img width=2 height=0>+<img width=2 height=0>";
							} else {
								if($jj>0) $prlist2.="<img width=2 height=0>+<img width=2 height=0>";
								break;
							}
							$prlist2.="<a href=\"".$Dir.FrontDir."tag.php?tagname=".urlencode($arrtaglist[$ii])."\" onmouseover=\"window.status='".$arrtaglist[$ii]."';return true;\" onmouseout=\"window.status='';return true;\"><FONT class=\"prtag\">".$arrtaglist[$ii]."</FONT></a>";
							$jj++;
						}
					}
					$prlist2.="		</td>\n";
					$prlist2.="	</tr>\n";
				}

				// 입점사 네임택
				if( nameTechUse($row->vender) ) {
					$classList = array();
					$classResult=mysql_query("SELECT * FROM `tblVenderClassType` ",get_db_conn());
					while($classRow=mysql_fetch_object($classResult)) {
						$classList[$classRow->idx] = $classRow->name;
					}
					$v_info = mysql_fetch_assoc ( mysql_query( "SELECT * FROM `tblvenderinfo` WHERE `vender`=".$row->vender." LIMIT 1;" ,get_db_conn()) );

					// 네임텍 출력
					$prlist2.="
						<tr>
							<td>
								<div class=\"nameTagBox2\"><span class=\"name\">".$v_info['com_name']."</span> <span class=\"owner\">(".$v_info['com_owner'].")</span></div>
								<div><a href=\"javascript:GoMinishop('/minishop.php?storeid=".$v_info['id']."')\"><img src=\"/images/common/icon_vender_go.gif\" border=\"0\" align=\"absmiddle\" alt=\"전체상품보기\" /></a></div>
							</td>
						</tr>
					";
				}

				$prlist2.= "	</table>\n";
				$prlist2.= "	</td>\n";
				$prlist2.= "</tr>\n";
				$prlist2.= "</table>\n";
				$prlist2.= "</td>\n";

				$i++;

				if ($i%$prlist2_cols==0) {
					$prlist2.="</tr><tr><td colspan=".$prlist2_colnum." height=".$prlist2_gan."></td></tr>\n";
				}
			}
			if($i>0 && $i<$prlist2_cols) {
				for($k=0; $k<($prlist2_cols-$i); $k++) {
					$prlist2.="<td></td>\n<td></td>\n";
				}
			}
			$prlist2.= "</tr>\n";
			$prlist2.= "</table>\n";

		} else if($prlist_type=="3") {	####################################### 리스트형 ##########################

			$colspan=4;
			$image_height=27;
			$prlist3 = "<table border=0 cellpadding=0 cellspacing=0 width=100%>\n";
			if($prlist3_image_yn=="Y") {
				$image_height=60;
				$prlist3.= "<col width=70></col>\n";
			} else {
				$prlist3.= "<col width=40></col>\n";
			}
			$prlist3.= "<col width=\"0\"></col>\n";
			$prlist3.= "<col width=></col>\n";
			if($prlist3_production=="Y" || $prlist3_madein=="Y" || $prlist3_model=="Y" || $prlist3_brand=="Y") {
				$colspan++;
				$prlist3.= "<col width=120></col>\n";
			}
			if($prlist3_price=="Y") {
				$colspan++;
				$prlist3.= "<col width=90></col>\n";
			}
			$prlist3.= "<col width=120></col>\n";
			if($prlist3_reserve=="Y") {
				$colspan++;
				$prlist3.= "<col width=70></col>\n";
			}
			while($row=mysql_fetch_object($result)) {

				// 리뷰 평점 ( 리뷰 개수 )
				$prAvg = productReviewAverage($row->productcode);
				$prAvgMark = "";
				for( $si = 0 ; $si < $prAvg['average'] ; $si++ ) {
					$prAvgMark .= "<img src=\"/images/003/star_point1.gif\" alt=\"\" />";
				}
				for( $si = $prAvg['average']; $si < 5 ; $si++ ) {
					$prAvgMark .= "<img src=\"/images/003/star_point2.gif\" alt=\"\" />";
				}
				$prAvgMark .= "(".$prAvg['count'].")";

				// 렌탈 아이콘
				$rentalIcon = rentalIcon($row->rental);

				// 예약상품 아이콘 추가
				$row->etctype = reservationEtcType($row->reservation,$row->etctype);

				#####################상품별 회원할인율 적용 시작#######################################
				$dSql = "SELECT discount FROM tblmemberdiscount ";
				$dSql .= "WHERE productcode='".$row->productcode."' AND group_code='".$_ShopInfo->getMemgroup()."'";
				$dResult = mysql_query($dSql,get_db_conn());
				$dRow = mysql_fetch_object($dResult);
				$discountprices = $dRow->discount;
				if($discountprices>0){
					if($discountprices < 1){
						$memberprice = $row->sellprice - round($row->sellprice*$discountprices);
					}else{
						$memberprice = $row->sellprice - $discountprices;
					}
					$memberprice = number_format($memberprice);
					$strikeStart = "<strike>";
					$strikeEnd = "</strike>";
				}
				#####################상품별 회원할인율 적용 끝 #######################################

				$number = ($t_count-($setup[list_num] * ($gotopage-1))-$i);
				if($i>0) {
					$prlist3.="<tr><td colspan=".$colspan." ";
					if(eregi("#prlist_colline",$body)) {
						$prlist3.= "id=prlist_colline></td></tr>\n";
					} else {
						$prlist3.= "height=1><table border=0 cellpadding=0 cellspacing=0 height=1 style=\"table-layout:fixed\"><tr><td height=1 style=\"border:1 dotted #DDDDDD\"><img width=1 height=0></td></tr></table></td></tr>\n";
					}
				}
				$prlist3.= "<tr height=".$image_height." id=\"G".$row->productcode."\" onmouseover=\"quickfun_show(this,'G".$row->productcode."','','row')\" onmouseout=\"quickfun_show(this,'G".$row->productcode."','none')\">\n";
				if($prlist3_image_yn!="Y") {
					$prlist3.= "	<td align=center>".$number."</td>\n";
				}
				if($prlist3_image_yn=="Y") {
					$prlist3.= "	<td align=center>";
					if (strlen($row->tinyimage)>0 && file_exists($Dir.DataDir."shopimages/product/".$row->tinyimage)==true) {
						$prlist3.= "<A HREF=\"".$Dir.FrontDir."productdetail.php?productcode=".$row->productcode.$add_query."&sort=".$sort."\" onmouseover=\"window.status='상품상세조회';return true;\" onmouseout=\"window.status='';return true;\"><img src=\"".$Dir.DataDir."shopimages/product/".urlencode($row->tinyimage)."\" border=0 ";
						$width = getimagesize($Dir.DataDir."shopimages/product/".$row->tinyimage);
						if ($width[0]>=$width[1] && $width[0]>=60) $prlist3.= "width=60 ";
						else if ($width[1]>=60) $prlist3.= "height=60 ";
					} else {
						$prlist3.= "<img src=\"".$Dir."images/no_img.gif\" height=60 border=0 align=center";
					}
					$prlist3.= "	></A></td>\n";
				}
				$prlist3.="		<td style=\"position:relative;\">".($_data->ETCTYPE["QUICKTOOLS"]!="Y"?"<script>quickfun_write('".$Dir."','G','".$row->productcode."','".($row->quantity=="0"?"":"1")."','row')</script>":"")."</td>";
				$prlist3.= "	<td style=\"padding-left:5\" style=\"word-break:break-all;\"><A HREF=\"".$Dir.FrontDir."productdetail.php?productcode=".$row->productcode.$add_query."&sort=".$sort."\" onmouseover=\"window.status='상품상세조회';return true;\" onmouseout=\"window.status='';return true;\"><FONT class=\"prname\">".$rentalIcon.viewproductname($row->productname,$row->etctype,$row->selfcode,$row->addcode)."</FONT>".(strlen($row->prmsg)?'<br /><span class="prmsgArea">'.$row->prmsg.'</span>':'')."</A>";
				if ($row->quantity=="0") $prlist3.= soldout();
				//태그관련
				if($prlist3_tag>0 && strlen($row->tag)>0) {
					$prlist3.="<br><img src=\"".$Dir."images/common/tag_icon.gif\" border=0 align=absmiddle><img width=2 height=0>";
					$arrtaglist=explode(",",$row->tag);
					$jj=0;
					for($ii=0;$ii<count($arrtaglist);$ii++) {
						$arrtaglist[$ii]=ereg_replace("(<|>)","",$arrtaglist[$ii]);
						if(strlen($arrtaglist[$ii])>0) {
							if($jj<$prlist3_tag) {
								if($jj>0) $prlist3.="<img width=2 height=0><FONT class=\"prtag\">+</FONT><img width=2 height=0>";
							} else {
								if($jj>0) $prlist3.="<img width=2 height=0><FONT class=\"prtag\">+</FONT><img width=2 height=0>";
								break;
							}
							$prlist3.="<a href=\"".$Dir.FrontDir."tag.php?tagname=".urlencode($arrtaglist[$ii])."\" onmouseover=\"window.status='".$arrtaglist[$ii]."';return true;\" onmouseout=\"window.status='';return true;\"><FONT class=\"prtag\">".$arrtaglist[$ii]."</FONT></a>";
							$jj++;
						}
					}
				}
				$prlist3.= "</td>\n";
				//모델명/브랜드/제조사/원산지
				if($prlist3_production=="Y" || $prlist3_madein=="Y" || $prlist3_model=="Y" || $prlist3_brand=="Y") {
					$prlist3.="	<td align=center style=\"word-break:break-all;\" class=\"prproduction\">";
					if(strlen($row->production)>0 || strlen($row->madein)>0 || strlen($row->model)>0 || strlen($row->brand)>0) {
						unset($addspec);
						if($prlist3_production=="Y" && strlen($row->production)>0) {
							$addspec[]=$row->production;
						}
						if($prlist3_madein=="Y" && strlen($row->madein)>0) {
							$addspec[]=$row->madein;
						}
						if($prlist3_model=="Y" && strlen($row->model)>0) {
							$addspec[]=$row->model;
						}
						//if($prlist3_brand=="Y" && strlen($row->brand)>0) {
						//	$addspec[]=$row->brand;
						//}
						$prlist3.= implode("/", $addspec);
					}
					$prlist3.="	</td>\n";
				}
				if($prlist3_price=="Y") {
					$prlist3.= "	<td align=center style=\"word-break:break-all;\" class=\"prconsumerprice\"><img src=\"".$Dir."images/common/won_icon2.gif\" border=0 align=absmiddle> <strike>".number_format($row->consumerprice)."</strike>원</td>\n";
				}
				$prlist3.= "	<td align=center style=\"word-break:break-all;\" class=\"prprice\">";
				if($dicker=dickerview($row->etctype,number_format($row->sellprice)."원",1)) {
					$prlist3.= $dicker;
				} else if(strlen($_data->proption_price)==0) {
					$prlist3.= "<img src=\"".$Dir."images/common/won_icon.gif\" border=0 align=absmiddle> ".$strikeStart.number_format($row->sellprice)."원".$strikeEnd;
					if (strlen($row->option_price)!=0) $prlist3.= "(기본가)";
				} else {
					$prlist3.="<img src=\"".$Dir."images/common/won_icon.gif\" border=0 align=absmiddle> ";
					if (strlen($row->option_price)==0) $prlist3.= $strikeStart.number_format($row->sellprice)."원".$strikeEnd;
					else $prlist3.= ereg_replace("\[PRICE\]",number_format($row->sellprice),$_data->proption_price);
				}

				//회원할인가 적용
				if($discountprices>0){
					$prlist3 .= "<br /><img src=\"".$Dir."images/common/memsale_icon.gif\" style=\"position:relative; top:0.1em;\" alt=\"\" /> ".$memberprice."원";
				}

				$prlist3.= "	</td>\n";
				if($prlist3_reserve=="Y") {
					$reserveconv=getReserveConversion($row->reserve,$row->reservetype,$row->sellprice,"Y");
					$prlist3.= "	<td align=center style=\"word-break:break-all;\" class=prreserve><img src=\"".$Dir."images/common/reserve_icon.gif\" border=0 align=absmiddle> ".number_format($reserveconv)."원</td>\n";
				}
				$prlist3.= "</tr>\n";
				$i++;
			}
			$prlist3.= "</table>\n";
		} else if($prlist_type=="4") {	####################################### 공구형 ###########################

			$prlist4 = "<table border=0 cellpadding=0 cellspacing=0 width=100% style=\"table-layout:fixed\">\n";

			$prlist4.= "<tr>\n";
			while($row=mysql_fetch_object($result)) {
				$number = ($t_count-($setup[list_num] * ($gotopage-1))-$i);
				$prlist4.="<td align=center width=\"".(100/$prlist4_cols)."%\">\n";
				$prlist4.="<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"table-layout:fixed\" id=\"G".$row->productcode."\" onmouseover=\"quickfun_show(this,'G".$row->productcode."','')\" onmouseout=\"quickfun_show(this,'G".$row->productcode."','none')\">\n";
				$prlist4.="<col width=100></col>\n";
				$prlist4.="<col width=></col>\n";
				$prlist4.="<tr>\n";
				$prlist4.="	<td height=\"35\" colspan=\"2\" style=\"padding-top:5\"><div style=\"padding-left:15px;white-space:nowrap;width:210px;overflow:hidden;text-overflow:ellipsis;\"><a href='".$Dir.FrontDir."productdetail.php?productcode=".$row->productcode.$add_query."&sort=".$sort."' onmouseover=\"window.status='상품상세조회';return true;\" onmouseout=\"window.status='';return true;\"><font color=\"#000000\" style=\"font-size:11px;letter-spacing:-0.5pt;\"><b>".$row->productname."</b></font></a></div></td>\n";
				$prlist4.="</tr>\n";
				$prlist4.="<tr>\n";
				$prlist4.="	<td align=center valign=\"top\">\n";
				$prlist4.="	<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" style=\"table-layout:fixed\">\n";
				$prlist4.="	<tr>\n";
				$prlist4.="		<td align=\"center\" valign=\"middle\">\n";
				$prlist4.="		<A HREF=\"".$Dir.FrontDir."productdetail.php?productcode=".$row->productcode.$add_query."&sort=".$sort."\" onmouseover=\"window.status='상품상세조회';return true;\" onmouseout=\"window.status='';return true;\">";
				if (strlen($row->tinyimage)>0 && file_exists($Dir.DataDir."shopimages/product/".$row->tinyimage)==true) {
					$prlist4.="<img src=\"".$Dir.DataDir."shopimages/product/".urlencode($row->tinyimage)."\" border=0 ";
					$width = getimagesize($Dir.DataDir."shopimages/product/".$row->tinyimage);
					if(($width[0]>80 || $width[1]>80) && $width[0]>$width[1]) {
						$prlist4.=" width=80";
					} else if($width[0]>80 || $width[1]>80) {
						$prlist4.=" height=80";
					}
				} else {
					$prlist4.="<img src=\"".$Dir."images/no_img.gif\" border=0 align=center width=80 height=80";
				}
				$prlist4.="	></A></td>\n";
				$prlist4.="	</tr>\n";
				$prlist4.="	<tr>\n";
				$prlist4.="		<td height=\"3\" style=\"position:relative;\">".($_data->ETCTYPE["QUICKTOOLS"]!="Y"?"<script>quickfun_write('".$Dir."','G','".$row->productcode."','".($row->quantity=="0"?"":"1")."')</script>":"")."</td>";
				$prlist4.="	</tr>\n";
				$prlist4.="	<tr>\n";
				$prlist4.="		<td align=\"center\"><a href='".$Dir.FrontDir."productdetail.php?productcode=".$row->productcode.$add_query."&sort=".$sort."' onmouseover=\"window.status='상품상세조회';return true;\" onmouseout=\"window.status='';return true;\"><IMG SRC=\"".$Dir."images/common/btn_detail.gif\" border=\"0\"></a></td>\n";
				$prlist4.="	</tr>\n";
				$prlist4.="	</table>\n";
				$prlist4.="	</td>\n";
				$prlist4.="	<td valign=\"top\">\n";
				$prlist4.="	<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\n";
				$prlist4.="	<col width=52></col>\n";
				$prlist4.="	<col width=5></col>\n";
				$prlist4.="	<col width=></col>\n";
				$prlist4.="	<tr>\n";
				$prlist4.="		<td style=\"font-size:11\">\n";
				$prlist4.="			<img src=\"".$Dir."images/common/cat_graybullet.gif\" border=\"0\" align=\"absmiddle\"> 시중가";
				$prlist4.="		</td>\n";
				$prlist4.="		<td>: </td>\n";
				$prlist4.="		<td align=\"right\" style=\"font-size:11\"> <s>".number_format($row->consumerprice)."원</s></td>\n";
				$prlist4.="	</tr>\n";
				$prlist4.="	<tr>\n";
				$prlist4.="		<td style=\"font-size:11\">\n";
				$prlist4.="			<img src=\"".$Dir."images/common/cat_graybullet.gif\" border=\"0\" align=\"absmiddle\"> 현재가";
				$prlist4.="		</td>\n";
				$prlist4.="		<td>: </td>\n";
				$prlist4.="		<td align=\"right\" style=\"font-size:11;color:#FE7F00\">".number_format($row->sellprice)."원</td>\n";
				$prlist4.="	</tr>\n";
				$prlist4.="	<tr>\n";
				$prlist4.="		<td style=\"font-size:11\">\n";
				$prlist4.="			<img src=\"".$Dir."images/common/cat_graybullet.gif\" border=\"0\" align=\"absmiddle\"> 남은수량";
				$prlist4.="		</td>\n";
				$prlist4.="		<td>: </td>\n";
				$prlist4.="		<td align=\"right\" style=\"font-size:11\">\n";
				if(strlen($row->quantity)==0 || $row->quantity==NULL) {
					$prlist4.="무제한";
				} else {
					$prlist4.=$row->quantity."개";
				}
				$prlist4.="		</td>\n";
				$prlist4.="	</tr>\n";
				$prlist4.="	<tr>\n";
				$prlist4.="		<td height=\"13\" colspan=\"3\"></td>\n";
				$prlist4.="	</tr>\n";
				$prlist4.="	</table>\n";
				$prlist4.="	<table width=\"102\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" background=\"".$Dir."images/common/list_box.gif\">\n";
				$prlist4.="	<tr>\n";
				$prlist4.="		<td width=\"102\" height=\"52\" background=\"<?=$Dir?>images/common/plist_skin_listbox.gif\">\n";
				$prlist4.="		<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\n";
				$prlist4.="		<tr align=\"center\">\n";
				$prlist4.="			<td width=\"43\" height=\"40\" align=\"center\" valign=\"top\" style=\"color:#696969;font-size:11px;\">".number_format($row->consumerprice)."</td>\n";
				$prlist4.="			<td width=\"43\" valign=\"middle\" style=\"color:#FE7F00;font-size:11px;\">".number_format($row->sellprice)."</td>\n";
				$prlist4.="		</tr>\n";
				$prlist4.="		</table>\n";
				$prlist4.="		</td>\n";
				$prlist4.="	</tr>\n";
				$prlist4.="	<tr>\n";
				$prlist4.="		<td width=\"102\">\n";
				$prlist4.="		<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\n";
				$prlist4.="		<tr>\n";
				$prlist4.="			<td width=\"43\" align=\"right\" style=\"font-size:11px;\">시작가</td>\n";
				$prlist4.="			<td width=\"43\" align=\"right\" style=\"font-size:11px;\">공구가</td>\n";
				$prlist4.="		</tr>\n";
				$prlist4.="		</table>\n";
				$prlist4.="		</td>\n";
				$prlist4.="	</tr>\n";
				$prlist4.="	</table>\n";
				$prlist4.="	</td>\n";
				$prlist4.="</tr>\n";
				$prlist4.="</table>\n";
				$prlist4.="</td>\n";

				$i++;

				if ($i%$prlist4_cols==0) {
					$prlist4.="</tr><tr><td colspan=".$prlist4_colnum." height=".$prlist4_gan."></td></tr><tr>\n";
				}
			}
			if($i>0 && $i<$prlist4_cols) {
				for($k=0; $k<($prlist4_cols-$i); $k++) {
					$prlist4.="<td></td>\n";
				}
			}
			$prlist4.= "</tr>\n";
			$prlist4.= "</table>\n";
		}
		mysql_free_result($result);

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

			// 일반 블럭에서의 페이지 표시부분-시작

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
			}		// 마지막 블럭에서의 표시부분-끝


			$a_last_block = "";
			if ((intval($total_block) > 0) && (intval($nowblock) < intval($total_block))) {
				$last_block = ceil($t_count/($setup[list_num]*$setup[page_num])) - 1;
				$last_gotopage = ceil($t_count/$setup[list_num]);

				$a_last_block .= "&nbsp;&nbsp;<a href='javascript:GoPage(".$last_block.",".$last_gotopage.");' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='마지막 페이지';return true\"><FONT class=\"prlist\">[...".$last_gotopage."]</FONT></a>";

				$next_page_exists = true;
			}

			// 다음 10개 처리부분...

			$a_next_page = "";
			if ((intval($total_block) > 0) && (intval($nowblock) < intval($total_block))) {
				$a_next_page .= "&nbsp;&nbsp;<a href='javascript:GoPage(".($nowblock+1).",".($setup[page_num]*($nowblock+1)+1).");' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='다음 ".$setup[page_num]." 페이지';return true\"><FONT class=\"prlist\">[next]</FONT></a>";

				$a_next_page = $a_next_page.$a_last_block;
			}
		} else {
			$print_page = "<FONT class=\"prlist\">1</FONT>";
		}
		$list_page=$a_div_prev_page.$a_prev_page.$print_page.$a_next_page.$a_div_next_page;
	}
}
?>