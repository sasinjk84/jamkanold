<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/shopdata.php");
include_once($Dir."lib/ext/member_func.php");
include_once($Dir."lib/ext/basket_func.php");

header("Cache-Control: no-cache, must-revalidate");
header("Content-Type: text/xml; charset=EUC-KR");

$imagepath=$Dir.DataDir."shopimages/multi/";

$productcode=$_REQUEST["productcode"];
$qftype=$_GET["qftype"];
$bttype=$_GET["bttype"];
$opts=$_GET["opts"];
$option1=$_GET["option1"];
$option2=$_GET["option2"];
$mode=$_GET["mode"];
$code=$_GET["code"];
$ordertype=$_GET["ordertype"];	//바로구매 구분 (바로구매시 => ordernow)
$quantity=(int)$_REQUEST["quantity"];	//구매수량
if($quantity==0) $quantity=1;

$_REQUEST['p_bookingStartDate'] =  $_REQUEST["bookingStartDate"];
$_REQUEST['p_bookingEndDate'] = $_REQUEST["bookingEndDate"];

// 주문타입별 장바구니 테이블
$basket = basketTable($ordertype);

$errmsg="";
if(strlen($productcode)==18) {
	$codeA=substr($productcode,0,3);
	$codeB=substr($productcode,3,3);
	$codeC=substr($productcode,6,3);
	$codeD=substr($productcode,9,3);

	$sql = "SELECT * FROM tblproductcode WHERE codeA='".$codeA."' AND codeB='".$codeB."' AND codeC='".$codeC."' AND codeD='".$codeD."' ";
	$result=mysql_query($sql,get_db_conn());
	if($row=mysql_fetch_object($result)) {
		$_cdata=$row;
		if($row->group_code=="NO") {	//숨김 분류
			$errmsg="판매가 종료된 상품입니다.";
		} else if($row->group_code=="ALL" && strlen($_ShopInfo->getMemid())==0) {	//회원만 접근가능
			$errmsg="회원전용 상품입니다.\\n\\n로그인 후 이용하시기 바랍니다.";
		} else if(strlen($row->group_code)>0 && $row->group_code!="ALL" && $row->group_code!=$_ShopInfo->getMemgroup()) {	//그룹회원만 접근
			$errmsg="해당 분류의 접근 권한이 없습니다.";
		} else {
			if (empty($option1))  $option1=0;
			if (empty($option2))  $option2=0;
			if (empty($opts))  $opts="0";

			//Wishlist 담기
			if($mode=="wishlist") {
				if(strlen($_ShopInfo->getMemid())==0) {	//비회원
					$errmsg="로그인을 하셔야 본 서비스를 이용하실 수 있습니다.";
				} else {
					$sql = "SELECT productname,quantity,display,option1,option2,option_quantity,etctype,group_check FROM tblproduct ";
					$sql.= "WHERE productcode='".$productcode."' ";
					$result=mysql_query($sql,get_db_conn());
					if($row=mysql_fetch_object($result)) {
						if($row->display!="Y") {
							$errmsg="해당 상품은 판매가 되지 않는 상품입니다.\\n";
						}
						if($row->group_check!="N") {
							if(strlen($_ShopInfo->getMemid())>0) {
								$sqlgc = "SELECT COUNT(productcode) AS groupcheck_count FROM tblproductgroupcode ";
								$sqlgc.= "WHERE productcode='".$productcode."' ";
								$sqlgc.= "AND group_code='".$_ShopInfo->getMemgroup()."' ";
								$resultgc=mysql_query($sqlgc,get_db_conn());
								if($rowgc=@mysql_fetch_object($resultgc)) {
									if($rowgc->groupcheck_count<1) {
										$errmsg="해당 상품은 지정 등급 전용 상품입니다.\\n";
									}
									@mysql_free_result($resultgc);
								} else {
									$errmsg="해당 상품은 지정 등급 전용 상품입니다.\\n";
								}
							} else {
								$errmsg="해당 상품은 회원 전용 상품입니다.\\n";
							}
						}
						if(strlen($errmsg)==0) {
							if(strlen(dickerview($row->etctype,0,1))>0) {
								$errmsg="해당 상품은 판매가 되지 않습니다.\\n";
							}
						}
						if(empty($option1) && strlen($row->option1)>0)  $option1=1;
						if(empty($option2) && strlen($row->option2)>0)  $option2=1;
					} else {
						$errmsg="해당 상품이 존재하지 않습니다.\\n";
					}
					mysql_free_result($result);

					if(!$errmsg)
					{
						$sql = "SELECT COUNT(*) as totcnt FROM tblwishlist WHERE id='".$_ShopInfo->getMemid()."' ";
						$result2=mysql_query($sql,get_db_conn());
						$row2=mysql_fetch_object($result2);
						$totcnt=$row2->totcnt;
						mysql_free_result($result2);
						$maxcnt=100;
						if($totcnt>=$maxcnt) {
							$sql = "SELECT b.productcode ";
							$sql.= "FROM tblwishlist a, tblproduct b ";
							$sql.= "LEFT OUTER JOIN tblproductgroupcode c ON b.productcode=c.productcode ";
							$sql.= "WHERE a.id='".$_ShopInfo->getMemid()."' AND a.productcode=b.productcode ";
							$sql.= "AND b.display='Y' ";
							$sql.= "AND (b.group_check='N' OR c.group_code='".$_ShopInfo->getMemgroup()."') ";
							$sql.= "GROUP BY b.productcode ";
							$result2=mysql_query($sql,get_db_conn());
							$i=0;
							$wishprcode="";
							while($row2=mysql_fetch_object($result2)) {
								$wishprcode.="'".$row2->productcode."',";
								$i++;
							}
							mysql_free_result($result2);
							$totcnt=$i;
							$wishprcode=substr($wishprcode,0,-1);
							if(strlen($wishprcode)>0) {
								$sql = "DELETE FROM tblwishlist WHERE id='".$_ShopInfo->getMemid()."' ";
								$sql.= "AND productcode NOT IN (".$wishprcode.") ";
								mysql_query($sql,get_db_conn());
							}
						}
						if($totcnt<$maxcnt) {
							$sql = "SELECT COUNT(*) as cnt FROM tblwishlist WHERE id='".$_ShopInfo->getMemid()."' ";
							$sql.= "AND productcode='".$productcode."' AND opt1_idx='".$option1."' ";
							$sql.= "AND opt2_idx='".$option2."' AND optidxs='".$opts."' ";
							$result2=mysql_query($sql,get_db_conn());
							$row2=mysql_fetch_object($result2);
							$cnt=$row2->cnt;
							mysql_free_result($result2);
							if($cnt<=0) {
								$sql = "INSERT tblwishlist SET ";
								$sql.= "id			= '".$_ShopInfo->getMemid()."', ";
								$sql.= "productcode	= '".$productcode."', ";
								$sql.= "opt1_idx	= '".$option1."', ";
								$sql.= "opt2_idx	= '".$option2."', ";
								$sql.= "optidxs		= '".$opts."', ";
								$sql.= "date		= '".date("YmdHis")."' ";
								mysql_query($sql,get_db_conn());
							} else {
								$sql = "UPDATE tblwishlist SET date='".date("YmdHis")."' ";
								$sql.= "WHERE id='".$_ShopInfo->getMemid()."' ";
								$sql.= "AND productcode='".$productcode."' ";
								$sql.= "AND opt1_idx='".$option1."' AND opt2_idx='".$option2."' AND optidxs='".$opts."' ";
								mysql_query($sql,get_db_conn());
							}

							echo "<script type=\"text/javascript\">
							$('create_openwin').setStyle('display','none');
							if(confirm('WishList에 해당 상품을 등록하였습니다.\\n\\n  WishList 페이지로 이동 하겠습니까?')) { location.href='".$Dir.FrontDir."wishlist.php'; }
							".(strlen($bttype)>0?"else { if(typeof(setFollowFunc)!='undefined') { setFollowFunc('Today','noselectmenu'); setFollowFunc('Wishlist','noselectmenu'); } }":"else { if(typeof(setFollowFunc)!='undefined') { setFollowFunc('Today','noselectmenu'); setFollowFunc('Wishlist','selectmenu'); } }")."
							</script>"; exit;
						} else {
							echo "<script type=\"text/javascript\">
							$('create_openwin').setStyle('display','none');
							if(confirm('1. WishList에는 ".$maxcnt."개 까지만 등록이 가능합니다.\\n2. 새 상품을 담기 위해서는 기존 WishList 상품을 삭제 후 등록할 수 있습니다.\\n\\n                    WishList 페이지로 이동 하겠습니까?'))
								location.href='".$Dir.FrontDir."wishlist.php';
							</script>"; exit;
						}
					}
				}
			}
			else if($mode=="basket_insert") {//장바구니 담기				
				if (strlen($productcode)==18) {
					$return = addBasket($_REQUEST);					
					if(!_empty($return['err'])){
						echo "<script type=\"text/javascript\">alert('".$return['err']."'); $('create_openwin').setStyle('display','none');</script>"; 
						exit;
					}else{
						if($ordertype=="ordernow") {	//바로구매
							echo "<script type=\"text/javascript\">location.href='".$Dir.FrontDir."login.php?chUrl=".urlencode( $Dir.FrontDir."order.php?ordertype=ordernow" )."';</script>";
							exit;
						} else {
							echo "
							<script type=\"text/javascript\">
								$('create_openwin').setStyle('display','none');
								if(confirm('장바구니에 해당 상품을 등록하였습니다.\\n\\n장바구니 페이지로 이동 하겠습니까?')) { location.href='".$Dir.FrontDir."basket.php'; }
								".(strlen($bttype)>0?"else { if(typeof(setFollowFunc)!='undefined') { setFollowFunc('Today','noselectmenu'); setFollowFunc('Basket','noselectmenu'); } }":"else { if(typeof(setFollowFunc)!='undefined') { setFollowFunc('Today','noselectmenu');  setFollowFunc('Basket','selectmenu'); } }")."
							</script>"; exit;
						}
					}
				}
			}
			else
			{
				$sql = productQuery ();
				$sql.= "WHERE a.productcode='".$productcode."' AND a.display='Y' ";
				$sql.= "AND (a.group_check='N' OR b.group_code='".$_ShopInfo->getMemgroup()."') ";
				$result=mysql_query($sql,get_db_conn());
				if($row=mysql_fetch_object($result)) {
					$_pdata=$row;
				} else {
					$errmsg="해당 상품 정보가 존재하지 않습니다.";
				}
				mysql_free_result($result);
			}
		}
	} else {
		$errmsg="해당 분류가 존재하지 않습니다.";
	}
} else {
	$errmsg="해당 상품이 존재하지 않습니다.";
}

if(strlen($errmsg)>0) {
	echo "<script type=\"text/javascript\">alert('".$errmsg."'); $('create_openwin').setStyle('display','none');</script>"; exit;
}

if($_pdata->assembleuse=="Y" && $qftype != 1) {
	echo "<script type=\"text/javascript\">if(confirm('해당 상품은 상품상세페이지에서 구성상품을 선택 후에만 구매가 가능합니다.\\n\\n                     상품상세페이지로 이동 하겠습니까?')) {location.href='".$Dir.FrontDir."productdetail.php?productcode=".$productcode."';} $('create_openwin').setStyle('display','none');</script>"; exit;
}

if((int)$_pdata->package_num>0 && $qftype != 1) {
	echo "<script type=\"text/javascript\">if(confirm('해당 상품은 패키지 선택 상품으로써 상품상세페이지에서 패키지 정보를 확인 해 주세요.\\n\\n                              상품상세페이지로 이동 하겠습니까?')) {location.href='".$Dir.FrontDir."productdetail.php?productcode=".$productcode."';} $('create_openwin').setStyle('display','none');</script>"; exit;
}

$ref=$_REQUEST["ref"];
if (strlen($ref)==0) {
	$ref=strtolower(ereg_replace("http://","",getenv("HTTP_REFERER")));
	if(strpos($ref,"/") !== false) $ref=substr($ref,0,strpos($ref,"/"));
}

if(strlen($ref)>0 && strlen($_ShopInfo->getRefurl())==0) {
	$sql2="SELECT * FROM tblpartner WHERE url LIKE '%".$ref."%' ";
	$result2 = mysql_query($sql2,get_db_conn());
	if ($row2=mysql_fetch_object($result2)) {
		mysql_query("UPDATE tblpartner SET hit_cnt = hit_cnt+1 WHERE url = '".$row2->url."'",get_db_conn());
		$_ShopInfo->setRefurl($row2->id);
		$_ShopInfo->Save();
	}
	mysql_free_result($result2);
}

if(strlen($productcode)==18) {
	if(strlen($bttype)>0) {
		$viewproduct=$_COOKIE["ViewProduct"];
		if(strrpos(" ".$viewproduct,",".$productcode.",")!==false) {
			if(strlen($viewproduct)==0) {
				$viewproduct=",".$productcode.",";
			} else {
				$viewproduct=",".$productcode.$viewproduct;
			}
			$viewproduct=substr($viewproduct,0,172);
			setcookie("ViewProduct",$viewproduct,0,"/");
		}
	} else {
		$viewproduct=$_COOKIE["ViewProduct"];
		if(strrpos(" ".$viewproduct,",".$productcode.",")!==false) {
			if(strlen($viewproduct)==0) {
				$viewproduct=",".$productcode.",";
			} else {
				$viewproduct=",".$productcode.$viewproduct;
			}
		} else {
			$viewproduct=str_replace(",".$productcode.",",",",$viewproduct);
			$viewproduct=",".$productcode.$viewproduct;
		}
		$viewproduct=substr($viewproduct,0,172);
		setcookie("ViewProduct",$viewproduct,0,"/");
	}
}

#####################상품별 회원할인율 적용 시작#######################################
// 도매 가격 적용 상품 아이콘
$wholeSaleIcon = ( $_pdata->isdiscountprice == 1 ) ? $wholeSaleIconSet:"";

$memberpriceValue = $_pdata->sellprice;
$strikeStart = $strikeEnd = $memberprice = '';
if($_pdata->discountprices>0){
	$memberprice = number_format($_pdata->sellprice - $_pdata->discountprices);
	$strikeStart = "<strike>";
	$strikeEnd = "</strike> ▶ ".$memberprice;
	$memberpriceValue = ($_pdata->sellprice - $_pdata->discountprices);
}
#####################상품별 회원할인율 적용 끝 #######################################

//상품 상세정보 노출정보
if(strlen($_data->exposed_list)==0) {
	$_data->exposed_list=",0,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,";
}
$arexcel = explode(",",substr($_data->exposed_list,1,-1));
$prcnt = count($arexcel);
$arproduct=array(&$prproduction,&$prmadein,&$prconsumerprice,&$prsellprice,&$prreserve,&$praddcode,&$prquantity,&$proption,&$prproductname,&$prdollarprice,&$rentaloption,&$rental,&$rentalView,&$rentalCal,&$rentalSeason1,&$rentalSeason2,&$rentalSeason3,&$priceCalc,&$prdelitype);

if(ereg("^(\[OPTG)([0-9]{4})(\])$",$_pdata->option1)){
	$optcode = substr($_pdata->option1,5,4);
	$_pdata->option1="";
	$_pdata->option_price="";
}

$miniq = 1;
if (strlen($_pdata->etctype)>0) {
	$etctemp = explode("",$_pdata->etctype);
	for ($i=0;$i<count($etctemp);$i++) {
		if (substr($etctemp[$i],0,6)=="MINIQ=")			$miniq=substr($etctemp[$i],6);
	}
}

//입점업체 정보 관련
if($_pdata->vender>0) {
	$sql = "SELECT a.vender, a.id, a.brand_name, a.deli_info, b.prdt_cnt ";
	$sql.= "FROM tblvenderstore a, tblvenderstorecount b ";
	$sql.= "WHERE a.vender='".$_pdata->vender."' AND a.vender=b.vender ";
	$result=mysql_query($sql,get_db_conn());
	if(!$_vdata=mysql_fetch_object($result)) {
		$_pdata->vender=0;
	}
	mysql_free_result($result);
}
?>

<link href="/js/jquery-ui-1.11.4/jquery-ui.css" rel="stylesheet">
<script src="/js/jquery-ui-1.11.4/external/jquery/jquery.js"></script>
<script src="/js/jquery-ui-1.11.4/jquery-ui.js"></script>



<form name="quickfun_form1" method="post" action="<?=$Dir.FrontDir?>basket.php">
<input type="hidden" name="mode">
<input type="hidden" name="delCateIdx" value=''>
<input type="hidden" name="pre_bookingStartDate" id="pre_bookingStartDate">
<input type="hidden" name="pre_bookingEndDate" id="pre_bookingEndDate">
<input type="hidden" name="pre_startTime" id="pre_startTime">
<input type="hidden" name="pre_endTime" id="pre_endTime">
<?

// 대여 상품 정보
//$rentDetail = bookingProductDetail($_pdata->pridx);
$rentDetail = rentProduct::read($_pdata->pridx);
//$rentLocalInfo = $rentDetail['localInfo'][$rentDetail['productInfo']['location']];
$rentLocalInfo = $rentDetail['locationinfo'];

	$prproductname="";
if($_pdata->rental != '2' || $qftype == 1){
	if(strlen($dicker=dickerview($_pdata->etctype,number_format($_pdata->sellprice),1))>0) {
		$prsellprice=$dicker;
		$prdollarprice="";
		$priceindex=0;
	} else if(strlen($optcode)==0 && strlen($_pdata->option_price)>0) {
		$option_price = $_pdata->option_price;
		$pricetok=explode(",",$option_price);
		$priceindex = count($pricetok);
		for($tmp=0;$tmp<=$priceindex;$tmp++) {
			$pricetok[$tmp]=number_format($pricetok[$tmp]);
		}
		$prsellprice.="<td align=\"right\" style=\"word-break:break-all;\"><IMG SRC=\"".$Dir."images/common/icon_line_point.gif\" border=\"0\" align=\"absmiddle\">".( _array($rentLocalInfo) ? "대여가" : "판매가격" )."</td>\n";
		$prsellprice.="<td></td>";
		//$prsellprice.="<td align=\"left\"><img src=\"".$Dir."images/common/won_icon.gif\" border=\"0\" align=\"absmiddle\"><b><FONT color=\"#F02800\" id=\"idx_price\">".number_format($_pdata->sellprice)."원</FONT></b></td>";
		$prsellprice.="<td align=\"left\"><img src=\"".$Dir."images/common/won_icon.gif\" border=\"0\" align=\"absmiddle\"><b>". $strikeStart."<FONT color=\"#F02800\" id=\"qf_idx_price\">".number_format($_pdata->sellprice)."원</FONT>".$strikeEnd."</b></td>";
		$prsellprice.="<input type=hidden name=price value=\"".number_format($_pdata->sellprice)."\">\n";
		$prdollarprice ="";
	} else if(strlen($optcode)>0) {
		$prsellprice.="<td align=\"right\" style=\"word-break:break-all;\"><IMG SRC=\"".$Dir."images/common/icon_line_point.gif\" border=\"0\" align=\"absmiddle\">".( _array($rentLocalInfo) ? "대여가" : "판매가격" )."</td>\n";
		$prsellprice.="<td></td>";
		//$prsellprice.="<td align=\"left\"><img src=\"".$Dir."images/common/won_icon.gif\" border=\"0\" align=\"absmiddle\"><b><FONT color=\"#F02800\" id=\"idx_price\">".number_format($_pdata->sellprice)."원</FONT></b></td>";
		$prsellprice.="<td align=\"left\"><img src=\"".$Dir."images/common/won_icon.gif\" border=\"0\" align=\"absmiddle\"><b>".$strikeStart."<FONT color=\"#F02800\" id=\"qf_idx_price\">".number_format($_pdata->sellprice)."원</FONT>".$strikeEnd."</b></td>";
		$prsellprice.="<input type=hidden name=price value=\"".number_format($_pdata->sellprice)."\">\n";
		$prdollarprice ="";
	} else if(strlen($_pdata->option_price)==0) {
		$prsellprice.="<td align=\"right\" style=\"word-break:break-all;\"><IMG SRC=\"".$Dir."images/common/icon_line_point.gif\" border=\"0\" align=\"absmiddle\">".( _array($rentLocalInfo) ? "대여가" : "판매가격" )."</td>\n";
		$prsellprice.="<td></td>";
		//$prsellprice.="<td align=\"left\"><img src=\"".$Dir."images/common/won_icon.gif\" border=\"0\" align=\"absmiddle\"><b><FONT color=\"#F02800\" id=\"idx_price\">".number_format($_pdata->sellprice)."원</FONT></b></td>";
		$prsellprice.="<td align=\"left\"><img src=\"".$Dir."images/common/won_icon.gif\" border=\"0\" align=\"absmiddle\"><b>".$strikeStart."<FONT color=\"#F02800\" id=\"qf_idx_price\">".number_format($_pdata->sellprice)."원</FONT>".$strikeEnd."</b></td>";
		$prsellprice.="<input type=hidden name=price value=\"".number_format($_pdata->sellprice)."\">\n";
		$prdollarprice ="";
		$priceindex=0;
	}

	if($qftype == 1)
		$prquantity ="<input type=hidden name=\"quantity\" value=\"1\">\n";
	else
	{
		$prquantity.="<td align=\"right\" style=\"word-break:break-all;\"><IMG SRC=\"".$Dir."images/common/icon_line_point.gif\" border=\"0\" align=\"absmiddle\">구매수량</td>\n";
		$prquantity.="<td></td>";
		$prquantity.="<td align=\"left\">\n";
		$prquantity.="<table cellpadding=\"1\" cellspacing=\"0\" width=\"60\">\n";
		$prquantity.="<tr>\n";
		$prquantity.="	<td width=\"33\"><input type=text name=\"quantity\" value=\"".($miniq>1?$miniq:"1")."\" size=\"4\" style=\"font-size:11px;BORDER:#DFDFDF 1px solid;HEIGHT:18px;BACKGROUND-COLOR:#F7F7F7;padding-top:2pt;padding-bottom:1pt;\" onkeyup=\"strnumkeyup(this)\"></td>\n";
		$prquantity.="	<td width=\"33\">\n";
		$prquantity.="	<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\n";
		$prquantity.="	<tr>\n";
		$prquantity.="		<td width=\"5\" height=\"7\"><a href=\"javascript:quickfun_change_quantity('up')\"><img src=\"".$Dir."images/common/btn_plus.gif\" border=\"0\"></a></td>\n";
		$prquantity.="	</tr>\n";
		$prquantity.="	<tr>\n";
		$prquantity.="		<td width=\"5\" height=\"7\"><a href=\"javascript:quickfun_change_quantity('dn')\"><img src=\"".$Dir."images/common/btn_minus.gif\" border=\"0\"></a></td>\n";
		$prquantity.="	</tr>\n";
		$prquantity.="	</table>\n";
		$prquantity.="	</td>\n";
		$prquantity.="	<td width=\"33\">EA</td>\n";
		$prquantity.="</tr>\n";
		$prquantity.="</table>\n";
		$prquantity.="</td>\n";
	}
}

// 대여
if($_pdata->rental == '2' AND $qftype != 1){
$startD_view = "대여일";
$endD_view = "반납일";

	if($rentDetail['codeinfo']['pricetype'] != 'long'){
		$rental.="<td align=\"right\" style=\"word-break:break-all; \"><IMG SRC=\"".$Dir."images/common/icon_line_point.gif\" border=\"0\" align=\"absmiddle\">기&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;간</td>\n";
		$rental.="<td></td>";
		$rental.="<td align=\"left\">\n";
		$rental.="<table cellpadding=\"1\" cellspacing=\"0\"  width=\"100%\">\n";
		$rental.="<tr>\n";
		$rental.="	<td>\n";
		$rental.="	<table cellpadding=\"0\" border=0 cellspacing=\"0\" width=\"100%\" style=\"margin-top:5px;\">\n";
		$rental.="	<tr>\n";
		$rental.="		<td>\n";
		//$rental.="			<input type=\"text\" name=\"p_bookingStartDate\" id=\"qp_bookingStartDate\" class=\"datePickInput\"  value=\"" . date ("Ymd") . "\" style=\"width:70px;height:25px;float:left\" readonly>";
		$rental.="			<input type=\"text\" name=\"p_bookingSDate\" id=\"p_bookingSDate\" class=\"datePickInput\"  value=\"" .$startD_view. "\" style=\"width:70px;height:25px;float:left\" readonly>";
		$rental.= '<input type="hidden" name="p_bookingStartDate" id="qp_bookingStartDate" value="">';
/*
		if($rentDetail['codeinfo']['pricetype'] != 'period'){//단기기간제제외
			$rental .= '<select name="startTime" id="startTime" onChange="priceCalc2(this.form)" class="select1">';
			if($rentDetail['codeinfo']['checkout_time']==0){
				$end_time = 23;
			}else{
				$end_time = $rentDetail['codeinfo']['checkout_time'];
			}
			for($i=$rentDetail['codeinfo']['checkin_time'];$i<=$end_time;$i++){
				$sel = $i==$rentDetail['codeinfo']['checkin_time']?'selected':'';
				$rental .= '<option value="'.sprintf('%02d',$i).'" '.$sel.'>'.sprintf('%02d',$i).'시</option>';
			}
			$rental .= '</select>';
		}
*/
		if($rentDetail['codeinfo']['pricetype'] != 'period'){//단기기간제제외
			$rental .= '<select name="startTime" id="startTime" onChange="disableCheck(this)" class="select1">';
			$rental .= '<option value="">시간</option>';
			if($rentDetail['codeinfo']['pricetype'] == 'checkout'){//숙박제
				if($rentDetail['codeinfo']['checkout_time']==0 || $rentDetail['codeinfo']['checkin_time']>$rentDetail['codeinfo']['checkout_time']){
					$end_time = 23;
				}else{
					$end_time = $rentDetail['codeinfo']['checkout_time'];
				}
				for($i=$rentDetail['codeinfo']['checkin_time'];$i<=$end_time;$i++){
					$rentDetail['codeinfo']['checkin_time']=$rentDetail['codeinfo']['checkin_time']?$rentDetail['codeinfo']['checkin_time']:date("H")+1;
					$sel = $i==$rentDetail['codeinfo']['checkin_time']?'selected':'';

					$rental .= '<option value="'.sprintf('%02d',$i).'" '.$sel.'>'.sprintf('%02d',$i).'시</option>';
				}
			}else{
				for($i=0;$i<=23;$i++){
					//$rentDetail['codeinfo']['checkin_time']=$rentDetail['codeinfo']['checkin_time']?$rentDetail['codeinfo']['checkin_time']:date("H")+1;
					//$sel = $i==$rentDetail['codeinfo']['rent_stime']?'selected':'';

					if($i<$rentDetail['codeinfo']['rent_stime'] && $i>$rentDetail['codeinfo']['rent_etime']){
						$optionStyle=" class='disabled'";
					}else{
						$optionStyle="";
					}
					$rental .= '<option value="'.sprintf('%02d',$i).'" '.$sel.' '.$optionStyle.'>'.sprintf('%02d',$i).'시</option>';
				}
			}
			$rental .= '</select>';
		}

		$rental.="		</td>
						<td style=\"text-align:center;width:5px;\">
							~
						</td>
						<td>";
		//$rental.="			<input type=\"text\" name=\"p_bookingEndDate\" class=\"datePickInput\" id=\"qp_bookingEndDate\" value=\"" . date ("Ymd") . "\" style=\"width:70px;height:25px;float:left\" readonly>";
		$rental.="			<input type=\"text\" name=\"p_bookingEDate\" class=\"datePickInput\" id=\"p_bookingEDate\" value=\"".$endD_view."\" style=\"width:70px;height:25px;float:left\" readonly>";
		$rental.='<input type="hidden" name="p_bookingEndDate" id="qp_bookingEndDate" value="">';

/*
		if($rentDetail['codeinfo']['pricetype'] != 'period'){//단기기간제제외
			$rental .= '<select name="endTime" id="endTime"  onChange="priceCalc2(this.form)" class="select1">';
			if($rentDetail['codeinfo']['checkout_time']==0){
				$end_time = 23;
			}else{
				$end_time = $rentDetail['codeinfo']['checkout_time'];
			}
			for($i=$rentDetail['codeinfo']['checkin_time'];$i<=$end_time;$i++){
				$sel = $i==$rentDetail['codeinfo']['checkout_time']?'selected':'';
				$rental .= '<option value="'.sprintf('%02d',$i).'" '.$sel.'>'.sprintf('%02d',$i).'시</option>';
			}
			$rental .= '</select>';
		}
*/
		if($rentDetail['codeinfo']['pricetype'] != 'period'){//단기기간제제외
			$rental .= '<select name="endTime" id="endTime"  onChange="disableCheck(this)" class="select1">';
			$rental .= '<option value="">시간</option>';
			if($rentDetail['codeinfo']['pricetype'] == 'checkout'){//숙박제
				if($rentDetail['codeinfo']['checkout_time']==0){
					$end_time = 23;
				}else{
					$end_time = $rentDetail['codeinfo']['checkout_time'];
				}
				for($i=0;$i<=$end_time;$i++){
					if($rentDetail['codeinfo']['checkout_time']==0 && $rentDetail['codeinfo']['pricetype']=="time"){
						$sel = $i==($rentDetail['codeinfo']['checkin_time']+$rentDetail['codeinfo']['base_time'])?'selected':'';
					}else{
						$sel = $i==$rentDetail['codeinfo']['checkout_time']?'selected':'';
					}
					
					$rental .= '<option value="'.sprintf('%02d',$i).'" '.$sel.'>'.sprintf('%02d',$i).'시</option>';
				}
			}else{
				
				for($i=0;$i<=23;$i++){
					if($rentDetail['codeinfo']['rent_stime']==0 && $rentDetail['codeinfo']['pricetype']=="time"){
						//$sel = $i==$rentDetail['codeinfo']['rent_etime']?'selected':'';
					}else{
						//$sel = $i==$rentDetail['codeinfo']['rent_etime']?'selected':'';
					}
					if($i<$rentDetail['codeinfo']['rent_stime'] && $i>$rentDetail['codeinfo']['rent_etime']){
						$optionStyle=" class='disabled'";
					}else{
						$optionStyle="";
					}
					$rental .= '<option value="'.sprintf('%02d',$i).'" '.$sel.' '.$optionStyle.'>'.sprintf('%02d',$i).'시</option>';
				}
			}
			$rental .= '</select>';
		}


		$rental.="		</td>\n";
		$rental.="	</tr>\n";
		$rental.="	</table>\n";
		$rental.="	</td>\n";
		$rental.="</tr>\n";
		$rental.="</table>\n";
		$rental.="</td>\n";
	}
	$rental .= "<input type='hidden' name='rentStatus' value='rental'>";
	$rental .= "<input type='hidden' name='pricetype' id='pricetype' value='".$rentDetail['codeinfo']['pricetype']."'>";
	$rental .= "<input type='hidden' name='checkin_time' id='checkin_time' value='".$rentDetail['codeinfo']['checkin_time']."'>";
	$rental .= "<input type='hidden' name='checkout_time' id='checkout_time' value='".$rentDetail['codeinfo']['checkout_time']."'>";
	$rental .= "<input type='hidden' name='base_time' id='base_time' value='".$rentDetail['codeinfo']['base_time']."'>";
	$rental .= "<input type='hidden' name='base_period' id='base_period' value='".$rentDetail['codeinfo']['base_period']."'>";



	$rentalView="<td align=\"right\" style=\"word-break:break-all;\"><IMG SRC=\"".$Dir."images/common/icon_line_point.gif\" border=\"0\" align=\"absmiddle\">출고현황</td>\n";
	$rentalView.="<td></td>";
	$rentalView.="<td align=\"left\">\n";
	$rentalView.="<input type='button' onclick=\"bookingSchedulePop(".$_pdata->pridx.");\" value=\"현황보기\">";
	$rentalView.="</td>\n";


/*
	$rentalCal="<td align=\"right\" style=\"word-break:break-all;\"><IMG SRC=\"".$Dir."images/common/icon_line_point.gif\" border=\"0\" align=\"absmiddle\">시즌달력</td>\n";
	$rentalCal.="<td></td>";
	$rentalCal.="<td align=\"left\">\n";
	$rentalCal.="		<input type='button' onclick=\"bookingPriceCalendalPop('".substr($_pdata->productcode,0,12)."');\" value=\"달력보기\">";
	$rentalCal.="</td>\n";*/

	$priceCalc="<td colspan=\"3\"><div id=\"priceCalcPrint\" style=\"padding-left:10px; text-align:center;color:#ec2f36;\"></div><input type=\"hidden\" name=\"pridx\" value=\"".$_pdata->pridx."\"><input type=\"hidden\" name=\"quantity\" value=\"1\">ff</td>";



	// 시즌가격
	/*
	$seasonPrice = rentProductSeasonPrice($_pdata->pridx);
	$rentalSeason1 = "";
	if ( $seasonPrice['busySeason'] > 0 ) {
		$rentalSeason1.="<td align=\"right\" style=\"word-break:break-all;\"><IMG SRC=\"".$Dir."images/common/icon_line_point.gif\" border=\"0\" align=\"absmiddle\">성수기</td>\n";
		$rentalSeason1.="<td></td>";
		$rentalSeason1.="<td align=\"left\">\n";
		$rentalSeason1.="		".number_format($seasonPrice['busySeason'])." 추가";
		$rentalSeason1.="</td>\n";
	}
	$rentalSeason2 = "";
	if ( $seasonPrice['semiBusySeason'] > 0 ) {
		$rentalSeason2.="<td align=\"right\" style=\"word-break:break-all;\"><IMG SRC=\"".$Dir."images/common/icon_line_point.gif\" border=\"0\" align=\"absmiddle\">준성수기</td>\n";
		$rentalSeason2.="<td></td>";
		$rentalSeason2.="<td align=\"left\">\n";
		$rentalSeason2.="		".number_format($seasonPrice['semiBusySeason'])." 추가";
		$rentalSeason2.="</td>\n";
	}
	$rentalSeason3 = "";
	if ( $seasonPrice['holidaySeason'] > 0 ) {
		$rentalSeason3.="<td align=\"right\" style=\"word-break:break-all;\"><IMG SRC=\"".$Dir."images/common/icon_line_point.gif\" border=\"0\" align=\"absmiddle\">주말요금</td>\n";
		$rentalSeason3.="<td></td>";
		$rentalSeason3.="<td align=\"left\">\n";
		$rentalSeason3.="		".number_format($seasonPrice['holidaySeason'])." 추가";
		$rentalSeason3.="</td>\n";
	}
	*/

//배송수단선택
	if (strlen($rentDetail['deli_type'])>0 && $rentDetail['codeinfo']['pricetype']!="checkout") {
		$deli_type = explode(',', $rentDetail['deli_type']);
		
		$prdelitype.="<td align=\"right\" style=\"word-break:break-all; \"><IMG SRC=\"".$Dir."images/common/icon_line_point.gif\" border=\"0\" align=\"absmiddle\">배&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;송</td>\n";
		$prdelitype.="<td></td>\n";
		$prdelitype.="<td>";
		$prdelitype.="<input type=\"hidden\" name=\"delitype_count\" value=\"".count($deli_type)."\">";
		if(count($deli_type)==1){
			$prdelitype.=$deli_type[0];
			$prdelitype.="<input type=\"hidden\" name=\"ord_deli_type\" value=\"".$deli_type[0]."\">";
		}else{
			$prdelitype.="<select name=\"ord_deli_type\" style=\"border:1px solid #333333;height:35px;\">\n";
			$prdelitype.="<option value=''>선택하세요</option>";
			for($i=0,$end=count($deli_type);$i<$end;$i++) {
				if($i==0) $deli_selected = " selected";
				else $deli_selected = "";
				$prdelitype.="<option value=\"".$deli_type[$i]."\" ".$deli_selected.">".$deli_type[$i]."</option>";
			}
			$prdelitype.="</select>\n";
		}
		$prdelitype.="</td>\n";

	}


	// 렌탈상품 옵션
	$rentaloption = "";
	if($rentDetail['multiOpt'] == '0'){		
		
		$oinfo = array_shift($rentDetail['options']);
		$rentaloption .= "<td align=\"right\" style=\"word-break:break-all; width:80px;\"><IMG SRC=\"".$Dir."images/common/icon_line_point.gif\" border=\"0\" align=\"absmiddle\">상&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;태</td>\n";
		$rentaloption.="<td></td>";
		$rentaloption .= "<td>";
		if($rentDetail['codeinfo']['pricetype']=="long"){
			if($rentDetail['codeinfo']['ownership']=="re"){
				$deposit = "보증금 ".number_format($oinfo['deposit'])."원, ";
			}
			if($oinfo['optionPay']=="일시납"){
				$rentaloption .= "일시불 ".number_format($oinfo['nomalPrice'])."원, ".$deposit.$oinfo['optionName']."개월 + 선납금 : ".number_format($oinfo['prepay'])."원";
			}else{
				$rentaloption .= "월 ".number_format($oinfo['nomalPrice']/$oinfo['optionName'])."원, ".$deposit.$oinfo['optionName']."개월 + 선납금 : ".number_format($oinfo['prepay'])."원";
			}
		}else{
			$rentaloption .= rentProduct::_status($oinfo['grade']);
		}

		$rentaloption .= "</td></tr>";		
		$rentaloption .= "<tr><td align=\"right\" style=\"word-break:break-all; width:80px;\"><IMG SRC=\"".$Dir."images/common/icon_line_point.gif\" border=\"0\" align=\"absmiddle\">수&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;량</td>\n";
		$rentaloption.="<td></td>";
		$rentaloption .= "<td><input type='text' value='1' style='width:30px;' class=\"input rentOptionSelect\" name='rentOptions' idxcode=\"".$oinfo['idx']."\">개<input type='hidden' value='' name='rentOptionList'><input type='hidden' value='1' name='quantity'></td></tr>";
		//$rentaloption .= "<td><input type='text' value='1' style='width:30px;' class=\"input rentOptionSelect\" name='rentOptions' idxcode=\"".$oinfo['idx']."\" onchange=\"priceCalc2(this.form)\">개<input type='hidden' value='' name='rentOptionList'><input type='hidden' value='0' name='quantity'></td></tr>";

	}else{
		$rentaloption.="<td colspan=\"3\">";
		$rentaloption .= "<table border=\"1\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\n";
		$i=0;
		foreach($rentDetail['options'] as $optinfo){
			if(!_empty($rentDetail['gdiscount']['discount'])){			
				$optinfo['nomalPrice'] = tempSolvDiscount($optinfo['nomalPrice'],$rentDetail['gdiscount']['discount']);
				$optinfo['halfPrice'] = tempSolvDiscount($optinfo['halfPrice'],$rentDetail['gdiscount']['discount']);
				$optinfo['busySeason'] = tempSolvDiscount($optinfo['busySeason'],$rentDetail['gdiscount']['discount']);
				$optinfo['semiBusySeason'] = tempSolvDiscount($optinfo['semiBusySeason'],$rentDetail['gdiscount']['discount']);
				$optinfo['holidaySeason'] = tempSolvDiscount($optinfo['holidaySeason'],$rentDetail['gdiscount']['discount']);
			}
			$rentaloption .= "
				<tr align='center'>
					<td>".$optinfo['optionName']."</td>
					<td style=\"width:120px;\"><table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"FFFFFF\">";
									
			if($rentDetail['codeinfo']['pricetype'] == 'time' && !_empty($optinfo['halfPrice'])){
				$rentaloption .= "<tr>
									<td>· 12시간</td>
									<td align=\"right\">".number_format($optinfo['halfPrice'])."원</td>
								</tr>";
				$rentaloption .= "<tr>
									<td>· 24시간</td>
									<td align=\"right\">".number_format($optinfo['nomalPrice'])."원</td>
								</tr>";																				
			}else{
				$rentaloption .= "<tr>
									<td>".(($rentDetail['codeinfo']['useseason'] == '1')?'· 일반가':'')."</td>
									<td align=\"right\">".number_format($optinfo['nomalPrice'])."원</td>
								</tr>";																				
				
			}						
									
			if($rentDetail['codeinfo']['useseason'] == '1'){																			
				$rentaloption .= "
								<tr>
									<td>· 성수기</td>
									<td align=\"right\"> ".number_format($optinfo['nomalPrice']+$optinfo['busySeason'])."원</td>
								</tr>
								<tr>
									<td>· 준성수기</td>
									<td align=\"right\">".number_format($optinfo['nomalPrice']+$optinfo['semiBusySeason'])."원</td>
								</tr>
								<tr>
									<td>· 주말가</td>
									<td align=\"right\">".number_format($optinfo['nomalPrice']+$optinfo['holidaySeason'])."원</td>
								</tr>";
			}						
			
				$rentaloption .= "
								</table>
					</td>
					<td>";

					if($rentDetail['codeinfo']['pricetype']=="long"){
						if($rentDetail['codeinfo']['ownership']=="re"){
							$deposit = "보증금 ".number_format($optinfo['deposit'])."원, ";
						}
						if($optinfo['optionPay']=="일시납"){
							$rentaloption .= "일시불 ".number_format($optinfo['nomalPrice'])."원, ".$deposit.$optinfo['optionName']."개월 + 선납금 : ".number_format($optinfo['prepay'])."원";
						}else{
							$rentaloption .= "월 ".number_format($optinfo['nomalPrice']/$optinfo['optionName'])."원, ".$deposit.$optinfo['optionName']."개월 + 선납금 : ".number_format($optinfo['prepay'])."원";
						}
					}else{
						$rentaloption .= rentProduct::_status($optinfo['grade']);
					}

				if($i==0) $optval = 1;
				else $optval = 0;
				$rentaloption .= "</td>
					<td width='40'>
						<input type='text' value='".$optval."' style=\"width: 30px\" name='rentOptions' idxcode=\"" . $optinfo['idx'] . "\">
					</td>
				</tr>";
				/*
				$rentaloption .= "
								</table>
					</td>
					<td>".rentProduct::_status($optinfo['grade']). "</td>
					<td width='40'>
						<input type='text' value='0' style=\"width: 30px\" name='rentOptions' idxcode=\"" . $optinfo['idx'] . "\" onkeypress=\"priceCalc(this.form);\" onkeyup=\"priceCalc(this.form);\">
					</td>
				</tr>";
				*/
			$i++;
		}
		$rentaloption .= "</table>\n";
		$rentaloption .= "<input type='hidden' value='' name='rentOptionList'>";
		$rentaloption .= "<input type='hidden' value='' name='quantity'>";
		$rentaloption .="</td>\n";
	}

	$proption1 .= $rentaloption;
}else{
	$proption1 .= "<input type='hidden' name='rentStatus' value='sell'>";
	if (strlen($_pdata->option1) > 0) {
		$temp = $_pdata->option1;
		$tok = explode(",", $temp);
		$count = count($tok);
		$proption1 .= "<tr height=\"22\">\n";
		$proption1 .= "	<td align=\"right\" style=\"word-break:break-all;\"><IMG SRC=\"" . $Dir . "images/common/icon_line_point.gif\" border=\"0\" align=\"absmiddle\">$tok[0]</td>\n";
		$proption1 .= "	<td></td>\n";
		$proption1 .= "	<td align=\"left\">";
		if ($priceindex != 0) {
			$proption1 .= "<select name=\"option1\" size=\"1\" style=\"width:98%;font-size:11px;background-color:#404040;letter-spacing:-0.5pt;\" ";
			$proption1 .= "onchange=\"quickfun_change_price(1,document.quickfun_form1.option1.selectedIndex-1,";
			if (strlen($_pdata->option2) > 0) $proption1 .= "document.quickfun_form1.option2.selectedIndex-1";
			else $proption1 .= "''";
			$proption1 .= ")\">\n";
		} else {
			$proption1 .= "<select name=\"option1\" size=\"1\" style=\"width:98%;font-size:11px;background-color:#404040;letter-spacing:-0.5pt;\" ";
			$proption1 .= "onchange=\"quickfun_change_price(0,document.quickfun_form1.option1.selectedIndex-1,";
			if (strlen($_pdata->option2) > 0) $proption1 .= "document.quickfun_form1.option2.selectedIndex-1";
			else $proption1 .= "''";
			$proption1 .= ")\">\n";
		}

		$optioncnt = explode(",", substr($_pdata->option_quantity, 1));
		$proption1 .= "<option value=\"\" style=\"color:#ffffff;\">옵션을 선택하세요\n";
		$proption1 .= "<option value=\"\" style=\"color:#ffffff;\">-----------------\n";
		for ($i = 1; $i < $count; $i++) {
			if (strlen($tok[$i]) > 0) $proption1 .= "<option value=\"$i\" style=\"color:#ffffff;\">$tok[$i]\n";
			if (strlen($_pdata->option2) == 0 && $optioncnt[$i - 1] == "0") $proption1 .= " (품절)";
		}
		$proption1 .= "</select>";
	} else {
		//$proption1.="<input type=hidden name=option1>";
	}

	$proption2="";
	if(strlen($_pdata->option2)>0) {
		$temp = $_pdata->option2;
		$tok = explode(",",$temp);
		$count2=count($tok);

		$proption2.="<tr height=\"22\">\n";
		$proption2.="	<td align=\"right\" style=\"word-break:break-all;\"><IMG SRC=\"".$Dir."images/common/icon_line_point.gif\" border=\"0\" align=\"absmiddle\">$tok[0]</td>\n";
		$proption2.="	<td></td>\n";
		$proption2.="	<td align=\"left\">";
		$proption2.="<select name=\"option2\" size=\"1\" style=\"width:98%;font-size:11px;background-color:#404040;letter-spacing:-0.5pt;\" ";
		$proption2.="onchange=\"quickfun_change_price(0,";
		if(strlen($_pdata->option1)>0) $proption2.="document.quickfun_form1.option1.selectedIndex-1";
		else $proption2.="''";
		$proption2.=",document.quickfun_form1.option2.selectedIndex-1)\">\n";
		$proption2.="<option value=\"\" style=\"color:#ffffff;\">옵션을 선택하세요\n";
		$proption2.="<option value=\"\" style=\"color:#ffffff;\">-----------------\n";
		for($i=1;$i<$count2;$i++) if(strlen($tok[$i])>0) $proption2.="<option value=\"$i\" style=\"color:#ffffff;\">$tok[$i]\n";
		$proption2.="</select>";
		$proption2.="	</td>\n";
		$proption2.="</tr>\n";

	} else {
		if(strlen($_pdata->option1)>0) {
		$proption1.="	</td>\n";
		$proption1.="</tr>\n";
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
			while(strlen($optionadd[$opti])>0) {
				$proption3.="[OPT]";
				$proption3.="<select name=\"mulopt\" size=1 style=\"width:98%;font-size:11px;background-color:#404040;letter-spacing:-0.5pt;\" onchange=\"quickfun_chopprice('$opti')\"";
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
		}
		mysql_free_result($result);
	}
}
$priceCalc="<td colspan=\"3\"><div id=\"priceCalcPrint\" style=\"padding-left:10px; text-align:center;color:#ec2f36;\"></div></td>";

?>
<input type="hidden" name="code" value="<?=$code?>">
<input type="hidden" name="productcode" value="<?=$productcode?>">
<input type="hidden" name="bttype" value="<?=$bttype?>">
<input type="hidden" name="ordertype">
<input type="hidden" name="opts">
<input type="hidden" name="selCate">
<input type="hidden" name="selFolder" id="selFolder">
<input type="hidden" name="return_url" id="return_url" value="list">

<?
if($qftype == 1){//찜하기
?>

<script language="javascript" type="text/javascript">
function wishCateModifyOpen(title,idx){
	var cateSetDiv2 = document.getElementById('cateSetDiv2');
	var okDiv = document.getElementById('okDiv');
	cateSetDiv2.style.display = ( cateSetDiv2.style.display == 'none' ) ? 'block' : 'none';
	okDiv.style.display = ( okDiv.style.display == 'none' ) ? 'block' : 'none';
	document.quickfun_form1.cateTitle2.value = title;
	document.quickfun_form1.delCateIdx.value = idx;			
}

function wishCateViewOnOff2 ( t,t2 ) {
	t.style.display = ( t.style.display == 'none' ) ? 'block' : 'none';
	t2.style.display = ( t2.style.display == 'none' ) ? 'block' : 'none';
}

function wishManage ( mode, idx ) {
	if(mode=="cateDelete"){
		if( confirm('폴더 삭제시 폴더 내에 저장된 상품들도 함께 삭제됩니다\r\n폴더를 삭제하시겠습니까?') ) {

			data = 'mode='+mode+'&delCateIdx='+idx;

			jQuery.ajax({
				url: "/front/wishPopup.php",
				type: "POST",
				data: data,
				success: function(res) {
					$j('#wishResult').html(res);
				},
				error: function(result) {
					console.log(result);
				},
				timeout: 30000
			});
		}
	}else if(mode=="cateModify"){

		data = 'mode='+mode+'&delCateIdx='+document.quickfun_form1.delCateIdx.value+'&cateTitle='+$j("#cateTitle2").val();

		jQuery.ajax({
			url: "/front/wishPopup.php",
			type: "POST",
			data: data,
			success: function(res) {
				wishCateModifyOpen(cateSetDiv2,okDiv);
				$j('#wishResult').html(res);
			},
			error: function(result) {
				console.log(result);
			},
			timeout: 30000
		});


	}else if(mode=="cateInsert"){
		data = 'mode='+mode+'&cateTitle='+$j("#cateTitle").val();

		jQuery.ajax({
			url: "/front/wishPopup.php",
			type: "POST",
			data: data,
			success: function(res) {
				wishCateViewOnOff2(cateSetDiv,okDiv);
				$j('#wishResult').html(res);
			},
			error: function(result) {
				console.log(result);
			},
			timeout: 30000
		});

	}
	
}


</script>
<div class="searchPw popwin">
	<div class="spw_wrap">
		<p class="tit">찜하기</p>
		<p class="desc">
			<? if(strlen($_pdata->addcode)>0) echo "<font style=\"color:#FF7900;font-size:12px\"><B>[".$_pdata->addcode."]</B></font> ";
				echo "		<FONT style=\"color:#000000;font-size:12px\"><B>".viewproductname($_pdata->productname,$_pdata->etctype,$_pdata->selfcode)."</B></FONT>";?>
		</p>
		<div class="spwform">
			<fieldset>
				<legend>찜하기</legend>
				<ul id="wishResult">
					<li style="overflow:hidden;">
						<input type="checkbox" class="checkbox" name="sel[]" value="0" checked>기본폴더
						<span style="float:right;"><img src="/data/design/img/detail/icon_lock1.gif"></span></li>
					<?
					// 찜 카테고리 리스트
					$wishCateList = wishCateList();
					foreach ( $wishCateList as $k=>$v ) {					
						echo "<li style=\"overflow:hidden;\"><input type=\"checkbox\" class=\"checkbox\" name=\"sel[]\" value=\"".$k."\">".$v;
						if(strlen( $_ShopInfo->getMemid() ) > 0){
							echo "<p style=\"float:right;\"><input type='image' value='수정'src=\"/data/design/img/detail/icon_edit.gif\"  onclick=\"wishCateModifyOpen('".$v."','".$k."');return false;\"> ";
							echo "<input type='image' src=\"/data/design/img/detail/icon_close.gif\" value='삭제' onclick=\"wishManage('cateDelete', '".$k."');return false;\" style=\"margin-left:5px;\"></p>";
						}
						echo "</li>";
					}
					?>
				</ul>
				<p id="cateSetDiv2" style="display:none">
					<input type="text" name="cateTitle2" id="cateTitle2" style="width:100px" />
					<input type="button" value="수정" onclick="wishManage('cateModify','');"> 
					<input type="button" value="취소" onclick="javascript:wishCateViewOnOff2(cateSetDiv2,okDiv);"> 
				</p>
				<? if (strlen($_ShopInfo->getMemid())>0){ ?>
				<p style="border-top:1px solid #ededed;padding:20px 0px;margin-top:15px;"><a href="javascript:wishCateViewOnOff2(cateSetDiv,okDiv);"><span style="font-weight:bold;font-size:15px;color:#ea2f36;">+ 새폴더 추가</span></a></p>
				<? } ?>
				<p id="cateSetDiv" style="display:none">
					<input type="text" name="cateTitle" id="cateTitle" style="width:65%;border:1px solid #333333;height:35px;padding-left:10px;" placeholder="프로젝트 주제명 입력" />
					<input type="button" value="만들기" onclick="wishManage('cateInsert','');" class="btn_gray1"> 
					<input type="button" value="취소" onclick="javascript:wishCateViewOnOff2(cateSetDiv,okDiv);" class="btn_line"> 
				</p>
				<p id="okDiv" style="width:130px;margin:0px auto;">
					<input type="button" value="취소" class="btn_line btn_close closeBtn"> 
					<!--input type="button"  value="확인" onclick="javascript:CheckForm('wishlist','<?=$opti?>')" class="btn_line btn_login"-->

					<?
						if(strlen($dicker)==0) {
							if (strlen($_ShopInfo->getMemid())>0 && $_ShopInfo->getMemid()!="deleted") {
								echo "<input type=\"button\"  value=\"확인\" onclick=\"javascript:quickfun_CheckForm('wishlist','".$opti."')\" class=\"btn_line btn_login\">\n";
							} else {
								echo "<input type=\"button\"  value=\"확인\" onclick=\"javascript:quickfun_check_login()\" class=\"btn_line btn_login\">\n";
							}
						}
					?>

				</p>
				<p id="confirmResult" style="width:100%;margin:5px auto;text-align:center"></p>
			</fieldset>
		</div>
	</div>
</div>

<?
}else{
?>


<script language="javascript" type="text/javascript">
function basketFolderModifyOpen(title,idx){
	var basketfdSetDiv2 = document.getElementById('basketfdSetDiv2');
	var okbasketDiv = document.getElementById('okbasketDiv');
	basketfdSetDiv2.style.display = ( basketfdSetDiv2.style.display == 'none' ) ? 'block' : 'none';
	okbasketDiv.style.display = ( okbasketDiv.style.display == 'none' ) ? 'block' : 'none';
	document.quickfun_form1.folderName2.value = title;
	document.quickfun_form1.delCateIdx.value = idx;			
}

function basketFolderViewOnOff2 ( t,t2 ) {
	t.style.display = ( t.style.display == 'none' ) ? 'block' : 'none';
	t2.style.display = ( t2.style.display == 'none' ) ? 'block' : 'none';
}

function basketManage ( mode, idx ) {
	if(mode=="cateDelete"){
		if( confirm('폴더를 삭제하시겠습니까?') ) {

			data = 'mode='+mode+'&delCateIdx='+idx;

			jQuery.ajax({
				url: "/front/basketPopup.php",
				type: "POST",
				data: data,
				success: function(res) {
					$j('#basketResult').html(res);
				},
				error: function(result) {
					console.log(result);
				},
				timeout: 30000
			});
		}
	}else if(mode=="cateModify"){

		data = 'mode='+mode+'&delCateIdx='+document.quickfun_form1.delCateIdx.value+'&folderName='+$j("#folderName2").val();

		jQuery.ajax({
			url: "/front/basketPopup.php",
			type: "POST",
			data: data,
			success: function(res) {
				basketFolderModifyOpen(basketfdSetDiv2,okbasketDiv);
				$j('#basketResult').html(res);
			},
			error: function(result) {
				console.log(result);
			},
			timeout: 30000
		});


	}else if(mode=="cateInsert"){
		data = 'mode='+mode+'&folderName='+$j("#folderName").val();

		jQuery.ajax({
			url: "/front/basketPopup.php",
			type: "POST",
			data: data,
			success: function(res) {
				basketFolderViewOnOff2(basketfdSetDiv,okbasketDiv);
				$j('#basketResult').html(res);
			},
			error: function(result) {
				console.log(result);
			},
			timeout: 30000
		});

	}
	
}


</script>
<div class="searchPw popwin">
	<div class="spw_wrap">
		<p class="tit">장바구니 담기</p>
		<p class="desc">
			<? if(strlen($_pdata->addcode)>0) echo "<font style=\"color:#FF7900;font-size:12px\"><B>[".$_pdata->addcode."]</B></font> ";
				echo "		<FONT style=\"color:#000000;font-size:12px\"><B>".viewproductname($_pdata->productname,$_pdata->etctype,$_pdata->selfcode)."</B></FONT>";?>
		
			<table border=0 cellpadding=0 cellspacing=0 width=100%>
			<col width="80"></col>
			<col width="5"></col>
			<col width=></col>
<?

			for($i=0;$i<$prcnt;$i++) {
				if(substr($arexcel[$i],0,1)=="O") {	//공백
					echo "<tr><td colspan=\"4\" height=\"5\" bgcolor=\"#FFFFFF\"></td></tr>\n";
				} else if ($arexcel[$i]=="7") {	//옵션

					if(strlen($proption1)>0 || strlen($proption2)>0 || strlen($proption3)>0) {

						if(strlen($proption1)>0) {
							$proption.=$proption1;
						}
						if(strlen($proption2)>0) {
							$proption.=$proption2;
						}
						if(strlen($proption3)>0) {
							$add_proption ="<tr height=\"22\">\n";
							$add_proption.="	<td align=\"right\" style=\"word-break:break-all;\"><IMG SRC=\"".$Dir."images/common/icon_line_point.gif\" border=\"0\" align=\"absmiddle\">상품옵션</td>\n";
							$add_proption.="	<td></td>\n";
							$add_proption.="	<td align=\"left\">\n";
							$add_proption.="	<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\n";
							$add_proption2 ="	</table>\n";
							$add_proption2.="	</td>\n";
							$add_proption2.="</tr>\n";

							$pattern=array("[OPT]","[OPTEND]");
							$replace=array("<tr><td>","</td></tr>");
							$proption.=$add_proption.str_replace($pattern,$replace,$proption3).$add_proption2;
						}

						echo $arproduct[$arexcel[$i]];
					} else {
						$proption ="<input type=hidden name=\"option1\">\n";
						$proption.="<input type=hidden name=\"option2\">\n";
					}

					
				} else if(strlen($arproduct[$arexcel[$i]])>0) {	//
					echo "<tr height=\"22\">".$arproduct[$arexcel[$i]]."</tr>\n";
					if($arexcel[$i]=="9") $dollarok="Y";
				}						
			}

?>
			</table>
		</p>
		<div class="spwform">
			<fieldset>
				<legend>장바구니 담기</legend>
				<ul id="basketResult">
					<li style="overflow:hidden;">
						<input type="radio" class="checkbox" name="selfd[]" value="0" checked>기본폴더
						<span style="float:right;"><img src="/data/design/img/detail/icon_lock1.gif"></span></li>
					<?
					$folders = array();
					if(false !== $fres = mysql_query("select * from basket_folder where id='".$_ShopInfo->getMemid()."' order by bfidx desc",get_db_conn())){		
						while($frow = mysql_fetch_assoc($fres)){			
							if(!_empty($frow['name'])) $folders[$frow['bfidx']] = $frow['name'];
						}
					}
					foreach ( $folders as $k=>$v ) {					
						echo "<li style=\"overflow:hidden;\"><input type=\"radio\" class=\"checkbox\" name=\"selfd[]\" value=\"".$k."\">".$v;
						if(strlen( $_ShopInfo->getMemid() ) > 0){
							echo "<p style=\"float:right;\"><input type='image' value='수정'src=\"/data/design/img/detail/icon_edit.gif\"  onclick=\"basketFolderModifyOpen('".$v."','".$k."');return false;\"> ";
							echo "<input type='image' src=\"/data/design/img/detail/icon_close.gif\" value='삭제' onclick=\"basketManage('cateDelete', '".$k."');return false;\" style=\"margin-left:5px;\"></p>";
						}
						echo "</li>";
					}
					?>
				</ul>
				<p id="basketfdSetDiv2" style="display:none">
					<input type="text" name="folderName2" id="folderName2" style="width:100px" />
					<input type="button" value="수정" onclick="basketManage('cateModify','');"> 
					<input type="button" value="취소" onclick="javascript:basketFolderViewOnOff2(basketfdSetDiv2,okbasketDiv);"> 
				</p>
				<? if (strlen($_ShopInfo->getMemid())>0){ ?>
				<p style="border-top:1px solid #ededed;padding:20px 0px;margin-top:15px;"><a href="javascript:basketFolderViewOnOff2(basketfdSetDiv,okbasketDiv);"><span style="font-weight:bold;font-size:15px;color:#ea2f36;">+ 새폴더 추가</span></a></p>
				<? } ?>
				<p id="basketfdSetDiv" style="display:none">
					<input type="text" name="folderName" id="folderName" style="width:65%;border:1px solid #333333;height:35px;padding-left:10px;" placeholder="프로젝트 주제명 입력" />
					<input type="button" value="만들기" onclick="basketManage('cateInsert','');" class="btn_gray1"> 
					<input type="button" value="취소" onclick="javascript:basketFolderViewOnOff2(basketfdSetDiv,okbasketDiv);" class="btn_line"> 
				</p>
				<p id="okbasketDiv" style="width:200px;margin:0px auto;">
					<input type="button" value="취소" class="btn_line btn_close closeBtn" onclick="javascript:PrdtQuickCls.openwinClose()"> 

					<?
						if(strlen($dicker)==0) {

							if(strlen($_pdata->quantity)>0 && $_pdata->quantity<=0)
							{
								if($qftype == 3)
									echo "<input type=\"button\" value=\"확인\" onclick=\"javascript:alert('품절 상태이므로 바로구매할 수 없습니다.')\" class=\"btn_line btn_login\">\n";
								else if($qftype == 2)
									echo "<input type=\"button\" value=\"확인\" onclick=\"javascript:alert('품절 상태이므로 장바구니 담을 수 없습니다.')\" class=\"btn_line btn_login\">\n";
							}
							else
							{
								if($qftype == 3){
									echo "<input type=\"button\" value=\"확인\" onclick=\"javascript:quickfun_CheckForm('ordernow','".$opti."')\" class=\"btn_line btn_login\">\n";
								}else if($qftype == 2){
									echo "<input type=\"button\" value=\"확인\" onclick=\"javascript:quickfun_CheckForm('','".$opti."')\" class=\"btn_line btn_login\">\n";
									echo "<input type=\"button\" value=\"우선담기\" onclick=\"javascript:quickfun_CheckForm('prebasket','".$opti."')\" class=\"btn_line btn_login\">\n";
								}
							}
						}
					?>

				</p>
				<p id="confirmResult" style="width:100%;margin:5px auto;text-align:center"></p>
			</fieldset>
		</div>
	</div>
</div>
<?
}
?>

<?
if(strlen($optcode)==0) {
	$maxnum=($count2-1)*10;
	if($optioncnt>0) {
		for($i=0;$i<$maxnum;$i++) {
			if ($i!=0) $quickfun_num .= ",";
			if(strlen($optioncnt[$i])==0) $quickfun_num .= "100000";
			else $quickfun_num .= $optioncnt[$i];
		}
	}

	if($priceindex>0) $quickfun_price = number_format($_pdata->sellprice)."|".number_format($_pdata->sellprice)."|";
	for($i=0; $i<$priceindex; $i++) {
		if($i!=0)
			$quickfun_price .= "|";
		$quickfun_price .= $pricetok[$i];
	}
}
?>
</form>
<?=$quickfun_price?>
<script type="text/javascript">
/*
	quickfun_setform.quickfun_miniq.value="<?=($miniq>1?$miniq:1)?>";
	quickfun_setform.quickfun_num.value="<?=$quickfun_num?>";
	quickfun_setform.quickfun_dicker.value="<?=(int)@strlen($dicker)?>";
	quickfun_setform.quickfun_price.value="<?=$quickfun_price?>";
	quickfun_setform.quickfun_priceindex.value="<?=$priceindex?>";
	quickfun_setform.quickfun_login.value="<?=$Dir.FrontDir?>login.php?chUrl=";
	quickfun_setform.quickfun_login2.value="<?=urlencode("?".getenv("QUERY_STRING"))?>";


	Drag.init($("layerbox-top"),$("create_openwin"));
*/
</script>

<script language="javascript" type="text/javascript">
//$j(".datePickInput").datepicker({dateFormat:"yymmdd",minDate: 0});
function disableCheck(obj) { 
	if (obj[obj.selectedIndex].className=='disabled') { 
		alert("선택하신 시간은 영업시간이 아니기 때문에 방문이 불가능합니다.\n "); 
		for (var i=0; obj[i].className=="disabled"; i++); 
		obj.selectedIndex = i; 
		return; 
	} 
	//priceCalc2(obj);

	if($j('#pricetype').val()!="long"){
		var now = new Date();
		var nowDay = now.getFullYear()+"-"+("0"+(now.getMonth()+1)).slice(-2)+"-"+("0"+now.getDate()).slice(-2);
		var nowTime = now.getHours();

		if($j('#qp_bookingStartDate').val() !="" && $j('#qp_bookingStartDate').val()==nowDay && $j('#startTime').val()<=nowTime){
			alert("현재시간보다 빠른 시간은 선택할 수 없습니다.");
			return false;
		}

		if($j('#qp_bookingStartDate').val() !="" && $j('#qp_bookingStartDate').val()==$j('#qp_bookingEndDate').val() && $j('#endTime').val()!="" && $j('#startTime').val()>=$j('#endTime').val()){
			alert("대여일과 반납일이 같은 경우 반납시간이 대여시간보다 빠를 수는 없습니다.");
			return false;
		}
	}
}
$j(function(){
	if($j("#p_bookingSDate")){
		$j("#p_bookingSDate").datepicker({
			//showOn: "both",
			dateFormat:'mm/dd(DD)',
			dayNames: ['일','월','화','수','목','금','토'],
			//buttonImage: "/images/mini_cal_calen.gif",
			minDate: 0,
			buttonImageOnly: true,
			//buttonText: "대여",
			altField: "#qp_bookingStartDate",
			altFormat: "yy-mm-dd",
			onClose: function( selectedDate ) {
			}
			,onSelect:function(selectedDate,picker){
				if($j("#p_bookingEDate").val()<$j("#p_bookingSDate").val()){
					$j("#p_bookingSDate").val("대여일");
				}
				$j("#p_bookingSDate").css("color","#000000");
			 }
		});

		$j("#p_bookingEDate").datepicker({
			//showOn: "both",
			dateFormat:'mm/dd(DD)',
			dayNames: ['일','월','화','수','목','금','토'],
			//buttonImage: "/images/mini_cal_calen.gif",
			minDate: 0,
			buttonImageOnly: true,
			//buttonText: "반납",
			altField: "#qp_bookingEndDate",
			altFormat: "yy-mm-dd",
			onClose: function( selectedDate ) {
			}
			,onSelect:function(selectedDate,picker){
				if($j("#p_bookingSDate").val()=="대여일"){
					alert("대여일 먼저 선택하세요.");$j("#p_bookingEDate").val("반납일");
				}
				if($j("#p_bookingEDate").val()<$j("#p_bookingSDate").val()){
					alert("반납일이 대여일이전일 수 없습니다.");$j("#p_bookingEDate").val("반납일");
				}
				$j("#p_bookingEDate").css("color","#000000");
			 }
		});

	}

	if($j("#p_bookingSDate").val()==""){
		$j('#startTime').val("");
	}
	if($j("#p_bookingEDate").val()==""){
		$j('#endTime').val("");
	}
});

</script>